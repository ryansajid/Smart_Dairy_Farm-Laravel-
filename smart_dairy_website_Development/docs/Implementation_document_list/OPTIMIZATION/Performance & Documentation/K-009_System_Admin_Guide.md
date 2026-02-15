# SMART DAIRY LTD.
## SYSTEM ADMINISTRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | K-009 |
| **Version** | 1.0 |
| **Date** | October 22, 2026 |
| **Author** | Technical Writer |
| **Owner** | IT Director |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [System Architecture Overview](#2-system-architecture-overview)
3. [Daily Operations](#3-daily-operations)
4. [User Management](#4-user-management)
5. [Monitoring & Alerting](#5-monitoring--alerting)
6. [Backup Management](#6-backup-management)
7. [Security Administration](#7-security-administration)
8. [Troubleshooting](#8-troubleshooting)
9. [Maintenance Procedures](#9-maintenance-procedures)
10. [Emergency Procedures](#10-emergency-procedures)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This System Administration Guide provides IT administrators with comprehensive procedures for managing, maintaining, and troubleshooting the Smart Dairy ERP system infrastructure on a day-to-day basis.

### 1.2 Target Audience

- System Administrators
- DevOps Engineers
- IT Support Staff
- Infrastructure Team Members

### 1.3 Prerequisites

- AWS CLI configured with appropriate credentials
- kubectl configured for EKS cluster access
- Access to Smart Dairy VPN
- Familiarity with PostgreSQL, Kubernetes, and Odoo

---

## 2. SYSTEM ARCHITECTURE OVERVIEW

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SMART DAIRY SYSTEM ARCHITECTURE                      │
└─────────────────────────────────────────────────────────────────────────────┘

     USERS                                   INFRASTRUCTURE
        │                                          │
        ▼                                          ▼
┌──────────────┐                    ┌──────────────────────────────┐
│   Web/App    │◄──────────────────►│      CloudFront CDN          │
│   Users      │                    │      (Static Assets)         │
└──────────────┘                    └──────────────────────────────┘
        │                                          │
        │                    ┌─────────────────────┘
        │                    │
        ▼                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                              AWS CLOUD (ap-south-1)                          │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                           VPC (10.0.0.0/16)                          │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────┐ │   │
│  │  │   Public     │  │   Private    │  │   Database   │  │   EKS    │ │   │
│  │  │   Subnets    │  │   Subnets    │  │   Subnets    │  │  Cluster │ │   │
│  │  │              │  │              │  │              │  │          │ │   │
│  │  │  • ALB       │  │  • EKS Nodes │  │  • RDS       │  │ • Odoo   │ │   │
│  │  │  • NAT GW    │  │  • FastAPI   │  │  • ElastiCache│ │ • FastAPI│ │   │
│  │  │  • Bastion   │  │  • Workers   │  │  • EMQX      │  │ • Workers│ │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────┘ │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   │
│  │     S3       │  │   EFS        │  │  Route 53    │  │  CloudWatch  │   │
│  │  (Storage)   │  │ (Shared FS)  │  │    (DNS)     │  │  (Monitoring)│   │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Component Inventory

| Component | Technology | Instance/Version | Endpoint |
|-----------|------------|------------------|----------|
| **Web Application** | Odoo 19 CE | 3-10 pods | https://erp.smartdairy.com |
| **API Gateway** | FastAPI | 2-5 pods | https://api.smartdairy.com |
| **Database** | PostgreSQL 16 | db.r6g.2xlarge | smart-dairy-db.cluster-xxx |
| **Cache** | Redis 7 | cache.r6g.large | smart-dairy-cache.xxx |
| **MQTT Broker** | EMQX 5 | 3 nodes | mqtt.smartdairy.com:1883 |
| **Message Queue** | RabbitMQ 3.12 | 3 nodes | amqp://rabbitmq:5672 |
| **Search** | Elasticsearch 8 | 3 nodes | https://es.smartdairy.com |
| **Monitoring** | Prometheus/Grafana | 2 pods each | https://grafana.smartdairy.com |

### 2.3 Access Points

| Service | URL | Access Method |
|---------|-----|---------------|
| **Odoo ERP** | https://erp.smartdairy.com | Web Browser |
| **B2C Store** | https://store.smartdairy.com | Web Browser |
| **B2B Portal** | https://b2b.smartdairy.com | Web Browser |
| **Monitoring** | https://grafana.smartdairy.com | Web Browser |
| **AWS Console** | https://smartdairy.signin.aws | Web Browser |
| **Bastion Host** | bastion.smartdairy.com | SSH |

---

## 3. DAILY OPERATIONS

### 3.1 Daily Health Check

```bash
#!/bin/bash
# daily-health-check.sh - Run every morning

echo "=========================================="
echo "Smart Dairy Daily Health Check"
echo "Date: $(date)"
echo "=========================================="

# 1. Check cluster nodes
echo "[1/8] Checking Kubernetes nodes..."
kubectl get nodes -o wide
kubectl top nodes

# 2. Check critical pods
echo "[2/8] Checking critical pods..."
kubectl get pods -n production -o wide | grep -E 'odoo|fastapi|emqx|postgres'
kubectl top pods -n production | head -20

# 3. Check services
echo "[3/8] Checking services..."
kubectl get svc -n production

# 4. Check ingress
echo "[4/8] Checking ingress..."
kubectl get ingress -n production

# 5. Check database connectivity
echo "[5/8] Checking database..."
kubectl exec -it -n production deploy/odoo-web -- python3 -c "
import psycopg2
try:
    conn = psycopg2.connect(host='smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com', 
                            database='smart_dairy_prod', 
                            user='odoo_readonly', 
                            password='xxx',
                            connect_timeout=5)
    print('✓ Database connection successful')
    conn.close()
except Exception as e:
    print(f'✗ Database connection failed: {e}')
"

# 6. Check disk usage
echo "[6/8] Checking storage..."
kubectl exec -it -n production deploy/odoo-web -- df -h

# 7. Check recent events
echo "[7/8] Checking recent events..."
kubectl get events -n production --sort-by='.lastTimestamp' | tail -20

# 8. Check SSL certificates expiry
echo "[8/8] Checking SSL certificates..."
EXPIRY=$(echo | openssl s_client -servername erp.smartdairy.com -connect erp.smartdairy.com:443 2>/dev/null | openssl x509 -noout -dates | grep notAfter | cut -d= -f2)
echo "ERP SSL Expiry: $EXPIRY"

echo "=========================================="
echo "Health check complete"
echo "=========================================="
```

### 3.2 Log Review

```bash
#!/bin/bash
# log-review.sh - Review yesterday's logs

YESTERDAY=$(date -d "yesterday" +%Y-%m-%d)

echo "Reviewing logs for $YESTERDAY"

# Odoo application logs
echo "=== Odoo Application Logs ==="
kubectl logs -n production -l app=odoo --since=24h | grep -E "ERROR|WARNING|CRITICAL" | head -20

# FastAPI logs
echo "=== FastAPI Logs ==="
kubectl logs -n production -l app=fastapi --since=24h | grep -E "ERROR|EXCEPTION" | head -20

# Database slow queries (if pgBadger available)
echo "=== Database Slow Queries ==="
# Requires pgBadger installation
# pg_badger /var/log/postgresql/postgresql-16-main.log --last-parsed ~/.pgbadger_last_state

# Check for 5xx errors in ALB logs
echo "=== ALB Error Logs ==="
aws s3 cp s3://smart-dairy-logs/alb/$YEAR/$MONTH/$DAY/ /tmp/alb-logs/ --recursive
grep " 5[0-9][0-9] " /tmp/alb-logs/*.log | head -20

echo "Log review complete. Investigate any errors above."
```

### 3.3 Resource Utilization Review

```bash
#!/bin/bash
# resource-review.sh - Weekly resource review

echo "=== Resource Utilization Report ==="

# CPU and Memory usage
kubectl top nodes
echo ""
kubectl top pods -n production --sort-by=cpu | head -10
echo ""
kubectl top pods -n production --sort-by=memory | head -10

# Check HPA status
kubectl get hpa -n production

# Check for pods with high restart counts
echo "=== Pods with High Restart Count ==="
kubectl get pods -n production --field-selector=status.phase=Running -o json | \
    jq -r '.items[] | select(.status.containerStatuses[0].restartCount > 5) | "\(.metadata.name): \(.status.containerStatuses[0].restartCount) restarts"'

# RDS metrics
aws cloudwatch get-metric-statistics \
    --namespace AWS/RDS \
    --metric-name CPUUtilization \
    --dimensions Name=DBInstanceIdentifier,Value=smart-dairy-prod \
    --statistics Average \
    --start-time $(date -u -d '7 days ago' +%Y-%m-%dT%H:%M:%S) \
    --end-time $(date -u +%Y-%m-%dT%H:%M:%S) \
    --period 86400
```

---

## 4. USER MANAGEMENT

### 4.1 AWS IAM User Management

```bash
#!/bin/bash
# aws-user-mgmt.sh

# List all IAM users
echo "=== IAM Users ==="
aws iam list-users --query 'Users[*].{Name:UserName, Created:CreateDate}'

# Check access key age
echo "=== Access Key Age ==="
for user in $(aws iam list-users --query 'Users[*].UserName' --output text); do
    aws iam list-access-keys --user-name $user --query 'AccessKeyMetadata[*].{User:UserName, KeyId:AccessKeyId, Age:CreateDate}'
done

# Rotate access key (if older than 90 days)
rotate_access_key() {
    USERNAME=$1
    OLD_KEY_ID=$2
    
    # Create new key
    NEW_KEY=$(aws iam create-access-key --user-name $USERNAME)
    NEW_KEY_ID=$(echo $NEW_KEY | jq -r '.AccessKey.AccessKeyId')
    NEW_SECRET=$(echo $NEW_KEY | jq -r '.AccessKey.SecretAccessKey')
    
    echo "New key created: $NEW_KEY_ID"
    echo "Secret: $NEW_SECRET"  # Save securely!
    
    # Deactivate old key (don't delete immediately - allows rollback)
    aws iam update-access-key --user-name $USERNAME --access-key-id $OLD_KEY_ID --status Inactive
    echo "Old key $OLD_KEY_ID deactivated"
}
```

### 4.2 Kubernetes RBAC Management

```bash
# View current RBAC
kubectl get roles -n production
kubectl get rolebindings -n production
kubectl get clusterroles
kubectl get clusterrolebindings

# Create service account for CI/CD
cat <<EOF | kubectl apply -f -
apiVersion: v1
kind: ServiceAccount
metadata:
  name: cicd-deployer
  namespace: production
---
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  name: cicd-deployer-role
  namespace: production
rules:
- apiGroups: ["apps"]
  resources: ["deployments"]
  verbs: ["get", "list", "watch", "update", "patch"]
- apiGroups: [""]
  resources: ["pods"]
  verbs: ["get", "list", "watch"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: cicd-deployer-binding
  namespace: production
subjects:
- kind: ServiceAccount
  name: cicd-deployer
  namespace: production
roleRef:
  kind: Role
  name: cicd-deployer-role
  apiGroup: rbac.authorization.k8s.io
EOF

# Generate kubeconfig for service account
kubectl create token cicd-deployer -n production --duration=8760h
```

### 4.3 Database User Management

```sql
-- Create read-only user for reporting
CREATE ROLE readonly_user WITH LOGIN PASSWORD 'secure_password';
GRANT CONNECT ON DATABASE smart_dairy_prod TO readonly_user;
GRANT USAGE ON SCHEMA public, farm, sales TO readonly_user;
GRANT SELECT ON ALL TABLES IN SCHEMA public, farm, sales TO readonly_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public, farm, sales GRANT SELECT ON TABLES TO readonly_user;

-- Create application user
CREATE ROLE app_user WITH LOGIN PASSWORD 'secure_password';
GRANT CONNECT ON DATABASE smart_dairy_prod TO app_user;
GRANT USAGE, CREATE ON SCHEMA public, farm, sales, inventory TO app_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public, farm, sales, inventory TO app_user;

-- Review existing users
SELECT usename, usesuper, usecreatedb, valuntil 
FROM pg_user 
ORDER BY usename;

-- Check user privileges
SELECT grantee, table_schema, table_name, privilege_type
FROM information_schema.table_privileges
WHERE grantee = 'readonly_user';
```

---

## 5. MONITORING & ALERTING

### 5.1 Key Metrics Dashboard

| Metric | Warning | Critical | Action |
|--------|---------|----------|--------|
| **Pod CPU** | > 70% | > 85% | Scale up pods |
| **Pod Memory** | > 75% | > 90% | Scale up/investigate |
| **Node CPU** | > 70% | > 85% | Add nodes |
| **Node Memory** | > 80% | > 90% | Add nodes |
| **Disk Usage** | > 80% | > 90% | Expand volume/cleanup |
| **DB Connections** | > 70% | > 90% | Check connection pool |
| **DB Replication Lag** | > 5s | > 30s | Check replication |
| **5xx Errors** | > 1% | > 5% | Investigate application |
| **Response Time (p95)** | > 500ms | > 2s | Optimize queries |

### 5.2 Alert Response Procedures

```bash
#!/bin/bash
# alert-response.sh - Common alert responses

# Alert: PodCrashLooping
handle_crash_loop() {
    POD=$1
    NAMESPACE=$2
    
    echo "Investigating crash loop for $POD..."
    kubectl describe pod $POD -n $NAMESPACE
    kubectl logs $POD -n $NAMESPACE --previous
    kubectl logs $POD -n $NAMESPACE | tail -100
    
    # Check resource limits
    kubectl get pod $POD -n $NAMESPACE -o yaml | grep -A 5 resources
}

# Alert: HighMemoryUsage
handle_high_memory() {
    DEPLOYMENT=$1
    NAMESPACE=$2
    
    echo "Handling high memory for $DEPLOYMENT..."
    kubectl top pods -n $NAMESPACE -l app=$DEPLOYMENT
    
    # Check for memory leaks
    kubectl logs -n $NAMESPACE -l app=$DEPLOYMENT | grep -i memory | tail -50
    
    # Scale up temporarily
    kubectl scale deployment/$DEPLOYMENT -n $NAMESPACE --replicas=$(($(kubectl get deployment $DEPLOYMENT -n $NAMESPACE -o jsonpath='{.spec.replicas}') + 2))
}

# Alert: DatabaseConnectionSpike
handle_db_connections() {
    echo "Checking database connections..."
    kubectl exec -it -n production deploy/postgres-client -- psql -c "
        SELECT state, count(*) 
        FROM pg_stat_activity 
        GROUP BY state;
    "
    
    # List long-running queries
    kubectl exec -it -n production deploy/postgres-client -- psql -c "
        SELECT pid, usename, state, query_start, query 
        FROM pg_stat_activity 
        WHERE state = 'active' 
        AND query_start < NOW() - INTERVAL '5 minutes';
    "
}
```

### 5.3 Grafana Dashboard URLs

| Dashboard | URL | Purpose |
|-----------|-----|---------|
| **Infrastructure Overview** | /d/infrastructure | Cluster health |
| **Application Performance** | /d/application | Odoo/FastAPI metrics |
| **Database Performance** | /d/database | PostgreSQL metrics |
| **Business Metrics** | /d/business | Orders, revenue, users |
| **IoT Metrics** | /d/iot | Sensor data flow |
| **Cost Analysis** | /d/cost | AWS cost breakdown |

---

## 6. BACKUP MANAGEMENT

### 6.1 Backup Verification

```bash
#!/bin/bash
# backup-verification.sh - Daily backup verification

TODAY=$(date +%Y-%m-%d)
YESTERDAY=$(date -d "yesterday" +%Y-%m-%d)

echo "=== Backup Verification for $YESTERDAY ==="

# 1. Check database backup
echo "[1/4] Checking database backup..."
aws s3 ls s3://smart-dairy-backups/postgresql/full/ | grep $YESTERDAY
if [ $? -eq 0 ]; then
    echo "✓ Database backup found"
    # Check file size
    SIZE=$(aws s3 ls s3://smart-dairy-backups/postgresql/full/ | grep $YESTERDAY | awk '{print $3}')
    echo "  Size: $SIZE bytes"
else
    echo "✗ Database backup NOT found!"
    # Alert on-call
fi

# 2. Check filestore backup
echo "[2/4] Checking filestore backup..."
aws s3 ls s3://smart-dairy-backups/filestore/ | grep $YESTERDAY

# 3. Check Velero backup
echo "[3/4] Checking Velero backup..."
velero backup get | grep $YESTERDAY

# 4. Check backup integrity (weekly)
if [ $(date +%u) -eq 7 ]; then
    echo "[4/4] Running backup integrity check..."
    # Restore to test instance and verify
    aws rds restore-db-instance-from-s3 \
        --db-instance-identifier backup-test \
        --source-engine postgres \
        --source-engine-version 16.0 \
        --s3-bucket-name smart-dairy-backups \
        --s3-prefix postgresql/full/
fi

echo "Backup verification complete"
```

### 6.2 Backup Restoration

```bash
#!/bin/bash
# backup-restore.sh - Restore from backup

RESTORE_TYPE=$1  # database|filestore|kubernetes
TARGET_DATE=$2   # YYYY-MM-DD

restore_database() {
    echo "Restoring database from $TARGET_DATE..."
    # Find backup file
    BACKUP_FILE=$(aws s3 ls s3://smart-dairy-backups/postgresql/full/ | grep $TARGET_DATE | tail -1 | awk '{print $4}')
    
    # Download backup
    aws s3 cp s3://smart-dairy-backups/postgresql/full/$BACKUP_FILE /tmp/
    
    # Restore to staging (never restore directly to prod!)
    pg_restore --host=staging-db --username=postgres --dbname=smart_dairy_restore /tmp/$BACKUP_FILE
    
    echo "Database restored to staging"
}

restore_filestore() {
    echo "Restoring filestore from $TARGET_DATE..."
    aws s3 sync s3://smart-dairy-backups/filestore/$TARGET_DATE/ /tmp/filestore-restore/
    
    # Copy to staging
    kubectl cp /tmp/filestore-restore/ staging/odoo-web:/var/lib/odoo/filestore/
    
    echo "Filestore restored"
}

restore_kubernetes() {
    echo "Restoring Kubernetes from Velero backup..."
    BACKUP_NAME=$(velero backup get | grep $TARGET_DATE | head -1 | awk '{print $1}')
    
    velero restore create --from-backup $BACKUP_NAME --include-namespaces staging
    
    echo "Kubernetes restore initiated"
}

# Execute based on type
case $RESTORE_TYPE in
    database) restore_database ;;
    filestore) restore_filestore ;;
    kubernetes) restore_kubernetes ;;
    *) echo "Usage: $0 {database|filestore|kubernetes} YYYY-MM-DD" ;;
esac
```

---

## 7. SECURITY ADMINISTRATION

### 7.1 Security Patch Management

```bash
#!/bin/bash
# security-patching.sh

# Check for available updates
echo "=== Checking for Security Updates ==="

# EKS node updates
aws eks describe-addon-versions --kubernetes-version 1.28 --addon-name vpc-cni

# Container image updates
kubectl get pods -n production -o jsonpath='{range .items[*]}{.spec.containers[*].image}{"\n"}{end}' | sort | uniq

# Check for CVEs in running images
trivy image smartdairy/odoo:19.0-latest
trivy image smartdairy/fastapi:latest

# Apply security patches
# 1. Update base images
# 2. Rebuild containers
# 3. Rolling update deployment
kubectl set image deployment/odoo-web odoo=smartdairy/odoo:19.0-patched -n production
```

### 7.2 Security Scanning

```bash
#!/bin/bash
# security-scan.sh

# Network policy audit
echo "=== Network Policies ==="
kubectl get networkpolicies -n production -o yaml

# RBAC audit
echo "=== RBAC Audit ==="
kubectl auth can-i --list --namespace=production

# Secret audit (check for expired secrets)
echo "=== Secret Expiry Check ==="
kubectl get secrets -n production -o json | jq '.items[] | select(.type=="kubernetes.io/tls") | {name: .metadata.name, expires: .data."tls.crt" | @base64d | capture("Not After : (?<date>[^\\n]+)")}'

# Pod security context check
echo "=== Pod Security Context ==="
kubectl get pods -n production -o json | jq '.items[] | {name: .metadata.name, runAsRoot: .spec.containers[].securityContext.runAsUser}'
```

### 7.3 SSL Certificate Management

```bash
#!/bin/bash
# ssl-management.sh

# Check certificate expiry
check_cert_expiry() {
    DOMAIN=$1
    EXPIRY_DATE=$(echo | openssl s_client -servername $DOMAIN -connect $DOMAIN:443 2>/dev/null | openssl x509 -noout -dates | grep notAfter | cut -d= -f2)
    EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s)
    CURRENT_EPOCH=$(date +%s)
    DAYS_UNTIL_EXPIRY=$(( ($EXPIRY_EPOCH - $CURRENT_EPOCH) / 86400 ))
    
    echo "$DOMAIN expires in $DAYS_UNTIL_EXPIRY days"
    
    if [ $DAYS_UNTIL_EXPIRY -lt 30 ]; then
        echo "WARNING: Certificate expiring soon!"
    fi
}

# Check all certificates
check_cert_expiry erp.smartdairy.com
check_cert_expiry api.smartdairy.com
check_cert_expiry store.smartdairy.com
check_cert_expiry b2b.smartdairy.com

# Renew certificates (if using cert-manager)
kubectl get certificates -n production
kubectl cert-manager renew -n production --all
```

---

## 8. TROUBLESHOOTING

### 8.1 Common Issues Quick Reference

| Issue | Symptom | Quick Fix |
|-------|---------|-----------|
| **Pod Pending** | Pod stuck in Pending | Check resource limits, node capacity |
| **ImagePullBackOff** | Can't pull container image | Check image tag, registry auth |
| **CrashLoopBackOff** | Pod keeps crashing | Check logs, resource limits |
| **502 Bad Gateway** | ALB returns 502 | Check target health, pod readiness |
| **Database Lock** | Queries hanging | Find and terminate blocking queries |
| **OutOfMemory** | Pod killed by OOM | Increase memory limit or optimize app |
| **Disk Pressure** | Node disk full | Clean up logs, old images |

### 8.2 Troubleshooting Commands

```bash
# Get detailed pod information
kubectl describe pod <pod-name> -n production

# Check pod logs
kubectl logs <pod-name> -n production --tail=100 -f

# Previous container logs (if crashed)
kubectl logs <pod-name> -n production --previous

# Execute into pod
kubectl exec -it <pod-name> -n production -- /bin/bash

# Check resource usage
kubectl top pod <pod-name> -n production

# Check events
kubectl get events -n production --sort-by='.lastTimestamp' | tail -50

# Network debugging
kubectl run -it --rm debug --image=nicolaka/netshoot --restart=Never -- /bin/bash
```

### 8.3 Database Troubleshooting

```sql
-- Check active connections
SELECT datname, usename, state, count(*) 
FROM pg_stat_activity 
GROUP BY datname, usename, state;

-- Find blocking queries
SELECT blocked_locks.pid AS blocked_pid,
       blocked_activity.usename AS blocked_user,
       blocking_locks.pid AS blocking_pid,
       blocking_activity.usename AS blocking_user,
       blocked_activity.query AS blocked_statement
FROM pg_catalog.pg_locks blocked_locks
JOIN pg_catalog.pg_stat_activity blocked_activity ON blocked_activity.pid = blocked_locks.pid
JOIN pg_catalog.pg_locks blocking_locks ON blocking_locks.locktype = blocked_locks.locktype
    AND blocking_locks.relation = blocked_locks.relation
    AND blocking_locks.pid != blocked_locks.pid
JOIN pg_catalog.pg_stat_activity blocking_activity ON blocking_activity.pid = blocking_locks.pid
WHERE NOT blocked_locks.granted;

-- Kill a query
SELECT pg_cancel_backend(<pid>);
-- Or force terminate
SELECT pg_terminate_backend(<pid>);

-- Check table bloat
SELECT schemaname, tablename, 
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size,
    n_dead_tup
FROM pg_stat_user_tables
ORDER BY n_dead_tup DESC;

-- Check index usage
SELECT schemaname, tablename, indexrelname, idx_scan
FROM pg_stat_user_indexes
ORDER BY idx_scan ASC;
```

---

## 9. MAINTENANCE PROCEDURES

### 9.1 Scheduled Maintenance Windows

| Maintenance Type | Frequency | Duration | Window (IST) |
|------------------|-----------|----------|--------------|
| **Kubernetes Updates** | Monthly | 2 hours | Sunday 02:00-04:00 |
| **Database Maintenance** | Monthly | 4 hours | Sunday 02:00-06:00 |
| **Security Patches** | As needed | 1-2 hours | Sunday 02:00-04:00 |
| **SSL Renewal** | As needed | 30 min | Sunday 02:00-02:30 |
| **Log Cleanup** | Weekly | 30 min | Sunday 01:00-01:30 |
| **Full Backup Test** | Quarterly | 4 hours | Sunday 02:00-06:00 |

### 9.2 Maintenance Mode Procedures

```bash
#!/bin/bash
# maintenance-mode.sh

enable_maintenance() {
    echo "Enabling maintenance mode..."
    
    # Scale down non-essential services
    kubectl scale deployment/odoo-web -n production --replicas=1
    kubectl scale deployment/fastapi -n production --replicas=1
    
    # Enable maintenance page
    kubectl apply -f - <<EOF
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: maintenance-ingress
  namespace: production
  annotations:
    nginx.ingress.kubernetes.io/configuration-snippet: |
      return 503;
    nginx.ingress.kubernetes.io/custom-http-errors: "503"
spec:
  rules:
  - host: erp.smartdairy.com
    http:
      paths:
      - path: /
        pathType: Prefix
        backend:
          service:
            name: maintenance-page
            port:
              number: 80
EOF
    
    echo "Maintenance mode enabled"
}

disable_maintenance() {
    echo "Disabling maintenance mode..."
    
    # Remove maintenance ingress
    kubectl delete ingress maintenance-ingress -n production
    
    # Scale back up
    kubectl scale deployment/odoo-web -n production --replicas=3
    kubectl scale deployment/fastapi -n production --replicas=2
    
    echo "Maintenance mode disabled"
}

# Usage
case $1 in
    enable) enable_maintenance ;;
    disable) disable_maintenance ;;
    *) echo "Usage: $0 {enable|disable}" ;;
esac
```

### 9.3 Log Rotation

```yaml
# ConfigMap for log rotation
apiVersion: v1
kind: ConfigMap
metadata:
  name: logrotate-config
  namespace: production
data:
  logrotate.conf: |
    /var/log/odoo/*.log {
        daily
        rotate 30
        compress
        delaycompress
        missingok
        notifempty
        create 0644 odoo odoo
        postrotate
            /bin/kill -HUP $(cat /var/run/odoo.pid 2>/dev/null) 2>/dev/null || true
        endscript
    }
    
    /var/log/fastapi/*.log {
        daily
        rotate 30
        compress
        delaycompress
        missingok
        notifempty
        create 0644 appuser appuser
    }
```

---

## 10. EMERGENCY PROCEDURES

### 10.1 Emergency Contact List

| Role | Name | Phone | Email |
|------|------|-------|-------|
| **Incident Commander** | [Name] | +91-xxx-xxx-0001 | [email] |
| **DR Lead** | [Name] | +91-xxx-xxx-0010 | [email] |
| **Database Lead** | [Name] | +91-xxx-xxx-0020 | [email] |
| **AWS Support** | Enterprise | - | support@amazon.com |
| **PagerDuty Hotline** | - | +1-xxx-xxx-xxxx | - |

### 10.2 Service Degradation Response

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SERVICE DEGRADATION RESPONSE                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  STEP 1: IDENTIFY                                                            │
│  ├── Check Grafana dashboard for anomalies                                   │
│  ├── Review recent deployments                                               │
│  └── Check AWS Service Health Dashboard                                      │
│                                                                              │
│  STEP 2: CONTAIN                                                             │
│  ├── Scale affected service up if resource issue                             │
│  ├── Restart problematic pods if needed                                      │
│  └── Enable circuit breaker if external dependency issue                     │
│                                                                              │
│  STEP 3: ESCALATE                                                            │
│  ├── Notify on-call engineer via PagerDuty                                   │
│  ├── Post incident to #incidents Slack channel                               │
│  └── Update status page if customer-impacting                                │
│                                                                              │
│  STEP 4: RESOLVE                                                             │
│  ├── Apply fix (rollback if deployment-related)                              │
│  ├── Monitor metrics for recovery                                            │
│  └── Verify all services healthy                                             │
│                                                                              │
│  STEP 5: POST-INCIDENT                                                       │
│  ├── Write incident report                                                   │
│  ├── Schedule post-mortem within 48 hours                                    │
│  └── Create action items for prevention                                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 10.3 Rollback Procedures

```bash
#!/bin/bash
# rollback.sh - Emergency rollback

DEPLOYMENT=$1
REVISION=$2  # Optional: specific revision to rollback to

if [ -z "$REVISION" ]; then
    echo "Rolling back $DEPLOYMENT to previous version..."
    kubectl rollout undo deployment/$DEPLOYMENT -n production
else
    echo "Rolling back $DEPLOYMENT to revision $REVISION..."
    kubectl rollout undo deployment/$DEPLOYMENT -n production --to-revision=$REVISION
fi

# Monitor rollout
kubectl rollout status deployment/$DEPLOYMENT -n production

# Verify pods are running
kubectl get pods -n production -l app=$DEPLOYMENT

echo "Rollback complete. Verify application functionality."
```

---

## 11. APPENDICES

### Appendix A: Quick Command Reference

```bash
# Cluster access
aws eks update-kubeconfig --region ap-south-1 --name smart-dairy-prod

# Common kubectl commands
kubectl get all -n production
kubectl logs -f deployment/odoo-web -n production
kubectl exec -it deployment/odoo-web -n production -- /bin/bash
kubectl top nodes
kubectl top pods -n production

# AWS commands
aws s3 ls s3://smart-dairy-backups/
aws rds describe-db-instances --query 'DBInstances[*].DBInstanceIdentifier'
aws ec2 describe-instances --filters Name=tag:Environment,Values=production
```

### Appendix B: Vendor Support Contacts

| Vendor | Support Portal | Phone | Contract ID |
|--------|---------------|-------|-------------|
| **AWS** | support.console.aws.amazon.com | Enterprise | [ID] |
| **Odoo** | www.odoo.com/help | - | [ID] |
| **Cloudflare** | dash.cloudflare.com | - | [ID] |
| **Datadog/New Relic** | app.datadoghq.com | - | [ID] |

### Appendix C: Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | October 22, 2026 | Technical Writer | Initial version |

---

**END OF SYSTEM ADMINISTRATION GUIDE**
