# SMART DAIRY LTD.
## ENVIRONMENT MANAGEMENT GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-007 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Environment Strategy](#2-environment-strategy)
3. [Development Environment](#3-development-environment)
4. [Staging Environment](#4-staging-environment)
5. [Production Environment](#5-production-environment)
6. [Environment Configuration](#6-environment-configuration)
7. [Data Management](#7-data-management)
8. [Environment Operations](#8-environment-operations)
9. [Troubleshooting](#9-troubleshooting)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the environment management strategy for the Smart Dairy Smart Web Portal System and Integrated ERP. It covers the setup, configuration, and operational procedures for development, staging, and production environments.

### 1.2 Environment Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    ENVIRONMENT LANDSCAPE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │     DEV     │───▶│   STAGING   │───▶│  PRODUCTION │         │
│  │             │    │             │    │             │         │
│  │ • Local dev │    │ • Pre-prod  │    │ • Live site │         │
│  │ • Feature   │    │ • UAT       │    │ • Customer  │         │
│  │   branches  │    │ • Load test │    │   facing    │         │
│  │ • Unit tests│    │ • Integration│   │ • 99.9% SLI │         │
│  │             │    │   tests     │    │             │         │
│  │ 2 replicas  │    │ 3 replicas  │    │ 5+ replicas │         │
│  │ Auto-deploy │    │ Manual gate │    │ Change      │         │
│  │   from PR   │    │   from dev  │    │   advisory  │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│         │                  │                  │                 │
│         └──────────────────┴──────────────────┘                 │
│                            │                                     │
│                    ┌───────┴───────┐                            │
│                    │ SHARED TOOLS  │                            │
│                    │ • GitHub      │                            │
│                    │ • ECR/ACR     │                            │
│                    │ • Terraform   │                            │
│                    │ State         │                            │
│                    └───────────────┘                            │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Environment Characteristics

| Aspect | Development | Staging | Production |
|--------|-------------|---------|------------|
| **Purpose** | Feature development | Testing & validation | Live operations |
| **Availability** | Best effort | 99% | 99.9% |
| **Data** | Synthetic/anonymized | Production-like masked | Real production |
| **Access** | Developers | QA + Stakeholders | Limited ops only |
| **Scale** | Minimal (1-2 nodes) | Medium (3-5 nodes) | Full (5+ nodes) |
| **Cost** | Minimized | Moderate | Full budget |

---

## 2. ENVIRONMENT STRATEGY

### 2.1 Multi-Environment Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    MULTI-ENVIRONMENT ARCHITECTURE                │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DEVELOPMENT                                                    │
│  ├── Local Docker Compose (Developer laptops)                   │
│  ├── Cloud Dev Environment (EKS namespace: smart-dairy-dev)     │
│  └── Feature Environments (Ephemeral per PR)                    │
│                                                                  │
│  STAGING                                                        │
│  ├── Staging Environment (EKS namespace: smart-dairy-staging)   │
│  ├── UAT Environment (EKS namespace: smart-dairy-uat)           │
│  └── Performance Test Environment (On-demand)                   │
│                                                                  │
│  PRODUCTION                                                     │
│  ├── Production Primary (ap-south-1)                            │
│  │   └── EKS namespace: smart-dairy-prod                        │
│  └── Production DR (ap-southeast-1)                             │
│      └── EKS namespace: smart-dairy-prod-dr                     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Promotion Strategy

| From | To | Trigger | Validation |
|------|-----|---------|------------|
| Local | Dev | Push to feature branch | Unit tests pass |
| Dev | Staging | Merge to develop branch | Integration tests |
| Staging | UAT | Tag release candidate | QA sign-off |
| UAT | Production | Manual approval | CAB approval |
| Production | DR | Continuous sync | DR validation |

---

## 3. DEVELOPMENT ENVIRONMENT

### 3.1 Local Development Setup

```yaml
# docker-compose.dev.yaml
version: '3.8'

services:
  odoo:
    build:
      context: ./odoo
      dockerfile: Dockerfile.dev
    ports:
      - "8069:8069"
      - "8072:8072"
    volumes:
      - ./odoo/addons:/mnt/extra-addons
      - ./odoo/config:/etc/odoo
      - odoo-data:/var/lib/odoo
    environment:
      - HOST=db
      - USER=odoo
      - PASSWORD=odoo
      - DEBUG=1
    depends_on:
      - db
      - redis

  db:
    image: postgres:16-alpine
    environment:
      - POSTGRES_USER=odoo
      - POSTGRES_PASSWORD=odoo
      - POSTGRES_DB=smart_dairy_dev
    volumes:
      - postgres-data:/var/lib/postgresql/data
    ports:
      - "5432:5432"

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"

  api:
    build:
      context: ./api
      dockerfile: Dockerfile.dev
    ports:
      - "8000:8000"
    volumes:
      - ./api:/app
    environment:
      - DATABASE_URL=postgresql://odoo:odoo@db:5432/smart_dairy_dev
      - REDIS_URL=redis://redis:6379/0
      - DEBUG=1
    depends_on:
      - db
      - redis

  mailhog:
    image: mailhog/mailhog
    ports:
      - "1025:1025"  # SMTP
      - "8025:8025"  # Web UI

volumes:
  odoo-data:
  postgres-data:
```

### 3.2 Cloud Development Environment

```bash
#!/bin/bash
# setup-dev-env.sh

NAMESPACE="smart-dairy-dev"
USER=${1:-$USER}

# Create personal dev namespace
kubectl create namespace ${NAMESPACE}-${USER}

# Apply base configuration
helm upgrade --install smart-dairy-dev ./helm-charts \
    --namespace ${NAMESPACE}-${USER} \
    --values ./helm-charts/values.yaml \
    --values ./helm-charts/values-dev.yaml \
    --set ingress.host=${USER}-dev.smartdairy.bd \
    --set postgresql.auth.password=$(openssl rand -base64 12)

# Port-forward for local access
echo "Setting up port forwards..."
kubectl port-forward svc/smart-dairy-dev-odoo 8069:8069 -n ${NAMESPACE}-${USER} &
kubectl port-forward svc/smart-dairy-dev-api 8000:8000 -n ${NAMESPACE}-${USER} &

echo "Dev environment ready!"
echo "Odoo: http://localhost:8069"
echo "API: http://localhost:8000"
```

### 3.3 Feature Branch Environments (Ephemeral)

```yaml
# .github/workflows/ephemeral-env.yaml
name: Ephemeral Environment

on:
  pull_request:
    types: [opened, synchronize, reopened]

jobs:
  deploy-ephemeral:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Configure AWS
        uses: aws-actions/configure-aws-credentials@v4
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ap-south-1

      - name: Deploy ephemeral environment
        run: |
          ENV_NAME="pr-${{ github.event.number }}"
          NAMESPACE="smart-dairy-ephemeral-${ENV_NAME}"
          
          kubectl create namespace ${NAMESPACE} --dry-run=client -o yaml | kubectl apply -f -
          
          helm upgrade --install smart-dairy-${ENV_NAME} ./helm-charts \
            --namespace ${NAMESPACE} \
            --values ./helm-charts/values-dev.yaml \
            --set ingress.host="${ENV_NAME}.ephemeral.smartdairy.bd" \
            --set odoo.image.tag="pr-${{ github.event.number }}" \
            --set postgresql.fullnameOverride="postgres-${ENV_NAME}" \
            --wait --timeout 300s

      - name: Comment PR
        uses: actions/github-script@v7
        with:
          script: |
            github.rest.issues.createComment({
              issue_number: context.issue.number,
              owner: context.repo.owner,
              repo: context.repo.repo,
              body: `🚀 Ephemeral environment deployed!
              
              - URL: https://pr-${context.issue.number}.ephemeral.smartdairy.bd
              - Will be destroyed when PR is closed`
            })
```

---

## 4. STAGING ENVIRONMENT

### 4.1 Staging Configuration

```yaml
# values-staging.yaml
# Key differences from production

global:
  environment: staging

odoo:
  replicaCount: 2
  
  config:
    # Test configuration
    test_enable: true
    demo_data: true
    
  resources:
    requests:
      cpu: 500m
      memory: 1Gi
    limits:
      cpu: 1000m
      memory: 2Gi

postgresql:
  primary:
    persistence:
      size: 100Gi
    # Use db.r6g.large instead of db.r6g.xlarge

monitoring:
  enabled: true
  alertSeverity: warning  # Don't page for staging

# Staging-specific settings
staging:
  dataRefresh:
    enabled: true
    schedule: "0 2 * * 0"  # Weekly Sunday 2 AM
    source: production
    masking: true
```

### 4.2 Data Refresh Process

```bash
#!/bin/bash
# staging-data-refresh.sh

set -e

SOURCE_DB="smart-dairy-postgres-prod"
TARGET_DB="smart-dairy-postgres-staging"
MASKING_SCRIPT="./scripts/mask-sensitive-data.sql"

echo "Starting staging data refresh..."

# 1. Create snapshot of production
aws rds create-db-snapshot \
    --db-instance-identifier ${SOURCE_DB} \
    --db-snapshot-identifier prod-pre-refresh-$(date +%Y%m%d)

# 2. Export production data (anonymized)
pg_dump -h ${SOURCE_DB}.xxx.ap-south-1.rds.amazonaws.com \
    -U admin \
    --exclude-table="audit_log" \
    --exclude-table="session_log" \
    --format=custom \
    smart_dairy_prod > /tmp/prod-data.dump

# 3. Stop staging application
kubectl scale deployment smart-dairy-staging-odoo --replicas=0

# 4. Reset staging database
psql -h ${TARGET_DB}.xxx.ap-south-1.rds.amazonaws.com \
    -U admin \
    -c "DROP DATABASE IF EXISTS smart_dairy_staging;"
psql -h ${TARGET_DB}.xxx.ap-south-1.rds.amazonaws.com \
    -U admin \
    -c "CREATE DATABASE smart_dairy_staging;"

# 5. Import data
pg_restore -h ${TARGET_DB}.xxx.ap-south-1.rds.amazonaws.com \
    -U admin \
    -d smart_dairy_staging \
    /tmp/prod-data.dump

# 6. Apply data masking
psql -h ${TARGET_DB}.xxx.ap-south-1.rds.amazonaws.com \
    -U admin \
    -d smart_dairy_staging \
    -f ${MASKING_SCRIPT}

# 7. Start staging application
kubectl scale deployment smart-dairy-staging-odoo --replicas=2

# 8. Clean up
rm /tmp/prod-data.dump

echo "Staging refresh complete!"
```

---

## 5. PRODUCTION ENVIRONMENT

### 5.1 Production Configuration

```yaml
# values-prod.yaml

global:
  environment: production

odoo:
  replicaCount: 5
  
  autoscaling:
    enabled: true
    minReplicas: 5
    maxReplicas: 20
    targetCPUUtilizationPercentage: 70
    targetMemoryUtilizationPercentage: 80
  
  resources:
    requests:
      cpu: 1000m
      memory: 2Gi
    limits:
      cpu: 4000m
      memory: 8Gi
  
  config:
    workers: 8
    max_cron_threads: 4
    limit_memory_soft: 2147483648
    limit_memory_hard: 2684354560
    proxy_mode: true
    
  pdb:
    enabled: true
    minAvailable: 3

postgresql:
  enabled: false  # Use RDS instead

externalDatabase:
  host: smart-dairy-postgres.xxx.ap-south-1.rds.amazonaws.com
  port: 5432
  database: smart_dairy_prod
  existingSecret: smart-dairy-db-credentials

# High availability settings
ha:
  enabled: true
  multiAz: true
  crossRegionBackup: ap-southeast-1

# Strict resource quotas
resourceQuota:
  enabled: true
  hard:
    requests.cpu: "20"
    requests.memory: 40Gi
    limits.cpu: "40"
    limits.memory: 80Gi
    pods: "50"

# Network policies
networkPolicy:
  enabled: true
  ingress:
    - from:
        - namespaceSelector:
            matchLabels:
              name: ingress-nginx
      ports:
        - protocol: TCP
          port: 8069
```

### 5.2 Change Management

```
┌─────────────────────────────────────────────────────────────────┐
│                    PRODUCTION CHANGE PROCESS                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. CHANGE REQUEST                                               │
│     └── Create CAB ticket with impact assessment                │
│                                                                  │
│  2. REVIEW                                                       │
│     ├── Technical review by senior engineer                     │
│     ├── Risk assessment                                         │
│     └── Approval from Change Advisory Board                     │
│                                                                  │
│  3. PREPARATION                                                  │
│     ├── Prepare rollback plan                                   │
│     ├── Schedule maintenance window                             │
│     └── Notify stakeholders                                     │
│                                                                  │
│  4. IMPLEMENTATION                                               │
│     ├── Execute during maintenance window                       │
│     ├── Monitor metrics continuously                            │
│     └── Keep rollback ready                                     │
│                                                                  │
│  5. VALIDATION                                                   │
│     ├── Smoke tests                                             │
│     ├── Health checks                                           │
│     └── User acceptance validation                              │
│                                                                  │
│  6. CLOSEOUT                                                     │
│     ├── Update documentation                                    │
│     ├── Post-implementation review                              │
│     └── Update CMDB                                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 6. ENVIRONMENT CONFIGURATION

### 6.1 Configuration Management

```yaml
# configmap-base.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: smart-dairy-config
data:
  # Application settings
  ODOO_LOG_LEVEL: "info"
  ODOO_LOG_DB: "False"
  ODOO_MAX_CRON_THREADS: "2"
  ODOO_WORKERS: "4"
  
  # Feature flags
  FEATURE_B2B_PORTAL: "true"
  FEATURE_SUBSCRIPTION: "true"
  FEATURE_IOT_INTEGRATION: "true"
  
  # Performance settings
  LIMIT_MEMORY_SOFT: "2147483648"
  LIMIT_MEMORY_HARD: "2684354560"
  LIMIT_TIME_CPU: "60"
  LIMIT_TIME_REAL: "120"
  
  # Localization
  DEFAULT_LANG: "en_US"
  SUPPORTED_LANGS: "en_US,bn_BD"
  
  # Security
  SESSION_COOKIE_SECURE: "true"
  CSRF_COOKIE_SECURE: "true"
  SECURE_SSL_REDIRECT: "true"
```

### 6.2 Environment-Specific Secrets

```yaml
# External Secrets Operator configuration
apiVersion: external-secrets.io/v1beta1
kind: ExternalSecret
metadata:
  name: smart-dairy-secrets
spec:
  refreshInterval: 1h
  secretStoreRef:
    kind: ClusterSecretStore
    name: aws-secrets-manager
  target:
    name: smart-dairy-credentials
    creationPolicy: Owner
  data:
    - secretKey: database-password
      remoteRef:
        key: smart-dairy/production/database
        property: password
    
    - secretKey: redis-password
      remoteRef:
        key: smart-dairy/production/redis
        property: password
    
    - secretKey: api-secret-key
      remoteRef:
        key: smart-dairy/production/api
        property: secret-key
    
    - secretKey: jwt-signing-key
      remoteRef:
        key: smart-dairy/production/auth
        property: jwt-signing-key
```

---

## 7. DATA MANAGEMENT

### 7.1 Database Per Environment

| Environment | Database Name | Instance Size | Backup |
|-------------|---------------|---------------|--------|
| Development | smart_dairy_dev | db.t3.medium | Daily |
| Staging | smart_dairy_staging | db.r6g.large | Daily |
| Production | smart_dairy_prod | db.r6g.xlarge | Continuous |
| Analytics | smart_dairy_analytics | db.r6g.2xlarge | Daily |

### 7.2 Data Classification

| Data Type | Dev | Staging | Production |
|-----------|-----|---------|------------|
| Customer PII | Masked | Masked | Real |
| Financial Data | Synthetic | Masked | Real |
| Order History | Synthetic | Production sample | Real |
| Product Catalog | Real | Real | Real |
| System Config | Dev-specific | Staging-specific | Production |

---

## 8. ENVIRONMENT OPERATIONS

### 8.1 Daily Operations

```bash
#!/bin/bash
# daily-ops.sh

ENVIRONMENT=${1:-staging}
NAMESPACE="smart-dairy-${ENVIRONMENT}"

echo "Daily operations for ${ENVIRONMENT}..."

# Check pod status
echo "=== Pod Status ==="
kubectl get pods -n ${NAMESPACE}

# Check resource usage
echo "=== Resource Usage ==="
kubectl top pods -n ${NAMESPACE}

# Check events
echo "=== Recent Events ==="
kubectl get events -n ${NAMESPACE} --sort-by='.lastTimestamp' | tail -20

# Check certificates
echo "=== Certificate Status ==="
kubectl get certificates -n ${NAMESPACE}

# Database connectivity test
echo "=== Database Connectivity ==="
kubectl exec -it deployment/smart-dairy-${ENVIRONMENT}-odoo -n ${NAMESPACE} -- \
    python3 -c "import psycopg2; print('DB connection OK')"
```

### 8.2 Environment Health Check

```yaml
# health-check.yaml
apiVersion: batch/v1
kind: CronJob
metadata:
  name: environment-health-check
spec:
  schedule: "*/5 * * * *"
  jobTemplate:
    spec:
      template:
        spec:
          containers:
            - name: health-check
              image: curlimages/curl:latest
              command:
                - /bin/sh
                - -c
                - |
                  # Check Odoo health
                  HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" \
                    https://smartdairy.bd/web/health)
                  
                  if [ "$HTTP_CODE" != "200" ]; then
                    echo "CRITICAL: Odoo health check failed"
                    curl -X POST ${ALERT_WEBHOOK} \
                      -d '{"severity":"critical","message":"Odoo unhealthy"}'
                    exit 1
                  fi
                  
                  # Check API health
                  HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" \
                    https://api.smartdairy.bd/health)
                  
                  if [ "$HTTP_CODE" != "200" ]; then
                    echo "CRITICAL: API health check failed"
                    exit 1
                  fi
                  
                  echo "All health checks passed"
          restartPolicy: OnFailure
```

---

## 9. TROUBLESHOOTING

### 9.1 Environment Comparison

```bash
#!/bin/bash
# compare-environments.sh

echo "Comparing Staging vs Production..."

# Compare Helm values
echo "=== Configuration Differences ==="
diff -u <(helm get values smart-dairy-staging) \
         <(helm get values smart-dairy-prod) || true

# Compare image versions
echo "=== Image Versions ==="
echo "Staging:"
kubectl get deployment smart-dairy-staging-odoo -o jsonpath='{.spec.template.spec.containers[0].image}'
echo ""
echo "Production:"
kubectl get deployment smart-dairy-prod-odoo -o jsonpath='{.spec.template.spec.containers[0].image}'

# Compare resource usage
echo "=== Resource Usage ==="
echo "Staging:"
kubectl top pods -n smart-dairy-staging
echo "Production:"
kubectl top pods -n smart-dairy-prod
```

### 9.2 Common Environment Issues

| Issue | Symptoms | Solution |
|-------|----------|----------|
| Config drift | Environments behave differently | Compare configs, re-sync from source |
| Resource exhaustion | OOMKilled, CPU throttling | Scale up or optimize resources |
| Certificate expiry | HTTPS errors | Renew certificates |
| Database lag | Slow queries, timeouts | Check indexes, connection pooling |
| Network isolation | Cannot reach external services | Check security groups, NACLs |

---

## 10. APPENDICES

### Appendix A: Environment URLs

| Environment | URL | Notes |
|-------------|-----|-------|
| Development | dev.smartdairy.bd | Continuous deployment |
| Feature Branches | pr-NNN.ephemeral.smartdairy.bd | Auto-created per PR |
| Staging | staging.smartdairy.bd | Pre-production testing |
| UAT | uat.smartdairy.bd | Business validation |
| Production | smartdairy.bd | Live system |
| Production API | api.smartdairy.bd | Mobile app backend |

### Appendix B: Environment Access Matrix

| Role | Dev | Staging | Production |
|------|-----|---------|------------|
| Developer | Full | Read | None |
| QA Engineer | Read | Full | None |
| DevOps | Full | Full | Read (ops only) |
| SRE | Read | Read | Full |
| Product Owner | Read | Full | Read |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | DevOps Engineer | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
