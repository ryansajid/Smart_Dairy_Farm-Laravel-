# SMART DAIRY LTD.
## SYSTEM MONITORING RUNBOOK
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | L-007 |
| **Version** | 1.0 |
| **Date** | November 8, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | IT Director |
| **Reviewer** | DevOps Lead |
| **Classification** | Internal Use - Restricted |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Monitoring Architecture](#2-monitoring-architecture)
3. [Dashboard Guide](#3-dashboard-guide)
4. [Alert Response Procedures](#4-alert-response-procedures)
5. [Daily Monitoring Tasks](#5-daily-monitoring-tasks)
6. [Weekly Review Checklist](#6-weekly-review-checklist)
7. [Monthly Health Assessment](#7-monthly-health-assessment)
8. [Emergency Response](#8-emergency-response)
9. [Monitoring Tools Reference](#9-monitoring-tools-reference)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This System Monitoring Runbook provides standardized procedures for monitoring the Smart Dairy Web Portal System and Integrated ERP infrastructure. It ensures proactive detection of issues and rapid response to system anomalies.

### 1.2 Scope

- Infrastructure monitoring (AWS, Kubernetes, Databases)
- Application performance monitoring (Odoo, FastAPI, Mobile APIs)
- Business metrics monitoring (Orders, Payments, Farm operations)
- Security monitoring (Access logs, Failed attempts)
- IoT device monitoring (Sensors, Milk meters)

### 1.3 Monitoring Objectives

| Objective | Target | Measurement |
|-----------|--------|-------------|
| System Availability | 99.9% uptime | Monthly aggregation |
| Alert Response Time | < 5 minutes | Time to acknowledge |
| Mean Time To Recovery | < 1 hour | Critical incidents |
| False Positive Rate | < 5% | Alert accuracy |

---

## 2. MONITORING ARCHITECTURE

### 2.1 Monitoring Stack

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      MONITORING ARCHITECTURE                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  METRICS COLLECTION                                                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │ Prometheus  │  │ CloudWatch  │  │   Odoo      │  │   IoT       │        │
│  │  (K8s/Apps) │  │   (AWS)     │  │  (Business) │  │ (Devices)   │        │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘        │
│         │                │                │                │               │
│         └────────────────┴────────────────┴────────────────┘               │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    GRAFANA (Visualization)                           │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │  Dashboards  │  │   Alerts     │  │   Reports    │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └──────────────────────────┬─────────────────────────────────────────┘   │
│                             │                                               │
│                             ▼                                               │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    ALERT MANAGER                                     │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │   PagerDuty  │  │    Slack     │  │    Email     │              │   │
│  │  │  (Critical)  │  │  (Warnings)  │  │  (Daily)     │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  LOG AGGREGATION                                                             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                        │
│  │  Fluentd    │──►│   Kafka     │──►│ Elasticsearch│                        │
│  │ (Collectors)│  │   (Queue)   │  │   (Storage) │                        │
│  └─────────────┘  └─────────────┘  └──────┬──────┘                        │
│                                            │                                │
│                                            ▼                                │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                         KIBANA (Logs)                                │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Monitoring Coverage Matrix

| Component | Tool | Metric Collection | Alerting |
|-----------|------|-------------------|----------|
| **EKS Cluster** | Prometheus + Grafana | Node metrics, Pod status | Critical |
| **RDS PostgreSQL** | CloudWatch + pg_exporter | CPU, connections, replication | Critical |
| **ElastiCache Redis** | CloudWatch | Memory, connections, hit rate | High |
| **Application Pods** | Prometheus | Response time, error rate | Critical |
| **Load Balancer** | CloudWatch | Request count, latency | Medium |
| **IoT Devices** | Custom + MQTT | Device status, battery, signal | High |
| **Business Metrics** | Odoo + Custom | Orders, payments, milk production | Medium |
| **Security** | WAF + GuardDuty | Failed logins, threats | Critical |

---

## 3. DASHBOARD GUIDE

### 3.1 Dashboard URLs

| Dashboard | URL | Purpose | Refresh |
|-----------|-----|---------|---------|
| **Infrastructure Overview** | grafana.smartdairy.com/d/infrastructure | System health | 30s |
| **Application Performance** | grafana.smartdairy.com/d/application | App metrics | 10s |
| **Database Performance** | grafana.smartdairy.com/d/database | DB metrics | 30s |
| **Business Operations** | grafana.smartdairy.com/d/business | Sales, orders | 1m |
| **IoT Monitoring** | grafana.smartdairy.com/d/iot | Device status | 1m |
| **Security Overview** | grafana.smartdairy.com/d/security | Security events | 5m |
| **Cost Analysis** | grafana.smartdairy.com/d/cost | AWS spending | 1h |

### 3.2 Key Metrics Reference

#### Infrastructure Metrics

| Metric | Normal Range | Warning Threshold | Critical Threshold |
|--------|--------------|-------------------|-------------------|
| **Node CPU** | < 70% | > 70% for 5m | > 85% for 5m |
| **Node Memory** | < 80% | > 80% for 5m | > 90% for 5m |
| **Disk Usage** | < 70% | > 80% | > 90% |
| **Pod Restarts** | 0 | > 3 in 1h | > 10 in 1h |
| **Network I/O** | Baseline | > 150% baseline | > 200% baseline |

#### Database Metrics

| Metric | Normal Range | Warning | Critical |
|--------|--------------|---------|----------|
| **CPU Utilization** | < 60% | > 60% | > 80% |
| **Connection Count** | < 80% max | > 80% | > 95% |
| **Replication Lag** | < 1s | > 5s | > 30s |
| **Cache Hit Ratio** | > 99% | < 99% | < 95% |
| **Deadlocks** | 0 | > 0 | > 5/hour |

#### Application Metrics

| Metric | Normal | Warning | Critical |
|--------|--------|---------|----------|
| **Response Time (p95)** | < 500ms | > 500ms | > 2s |
| **Error Rate** | < 0.1% | > 0.1% | > 1% |
| **Throughput** | Baseline | -20% | -50% |
| **Queue Depth** | < 10 | > 50 | > 100 |

### 3.3 Dashboard Walkthrough

**Infrastructure Overview Dashboard:**

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    INFRASTRUCTURE OVERVIEW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐               │
│  │  CLUSTER HEALTH │ │   NODE STATUS   │ │   POD STATUS    │               │
│  │                 │ │                 │ │                 │               │
│  │   🟢 Healthy    │ │  3/3 Ready      │ │  45/45 Running  │               │
│  │   Uptime: 45d   │ │  CPU: 45%       │ │  0 Pending      │               │
│  │                 │ │  Mem: 62%       │ │  0 Failed       │               │
│  └─────────────────┘ └─────────────────┘ └─────────────────┘               │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    NODE CPU & MEMORY                                 │   │
│  │  [Graph showing CPU/Memory usage over time for each node]           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    POD RESOURCE USAGE                                │   │
│  │  [Table showing top resource-consuming pods]                        │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. ALERT RESPONSE PROCEDURES

### 4.1 Alert Severity Levels

| Severity | Color | Response Time | Example |
|----------|-------|---------------|---------|
| **P1 - Critical** | 🔴 | Immediate | Production down, data loss |
| **P2 - High** | 🟠 | 15 minutes | Performance degraded |
| **P3 - Medium** | 🟡 | 1 hour | Disk space warning |
| **P4 - Low** | 🟢 | 4 hours | Non-urgent optimization |

### 4.2 Alert Response Workflow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       ALERT RESPONSE WORKFLOW                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ALERT RECEIVED                                                             │
│       │                                                                      │
│       ▼                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  1. ACKNOWLEDGE (within SLA)                                        │   │
│  │     └── Click "Acknowledge" in PagerDuty/Slack                      │   │
│  │     └── Start timer for response tracking                           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                      │
│       ▼                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  2. INITIAL ASSESSMENT                                              │   │
│  │     └── Check affected system/component                             │   │
│  │     └── Review related metrics and logs                             │   │
│  │     └── Determine scope (single user / all users)                   │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                      │
│       ▼                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  3. CLASSIFY                                                        │   │
│  │     └── Known issue? → Apply documented fix                         │   │
│  │     └── Unknown? → Begin investigation                              │   │
│  │     └── External dependency? → Contact vendor                       │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                      │
│       ▼                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  4. RESOLVE OR ESCALATE                                             │   │
│  │     └── Fix within your capability → Apply fix                      │   │
│  │     └── Requires expertise → Escalate to L2/L3                      │   │
│  │     └── Major incident → Initiate incident response                 │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                      │
│       ▼                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  5. VERIFY & CLOSE                                                  │   │
│  │     └── Confirm metrics return to normal                            │   │
│  │     └── Test affected functionality                                 │   │
│  │     └── Document resolution in incident log                         │   │
│  │     └── Close alert with resolution notes                           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Common Alert Response Procedures

#### Alert: High CPU Usage on Node 🔴

**Initial Response (0-5 minutes):**
```bash
# Identify high CPU pods
kubectl top pods --all-namespaces --sort-by=cpu | head -10

# Check node details
kubectl describe node <node-name>

# View resource consumption graph in Grafana
```

**Investigation (5-15 minutes):**
```bash
# Get pod logs
kubectl logs <pod-name> -n <namespace> --tail=100

# Check for resource limits
kubectl get pod <pod-name> -o yaml | grep -A5 resources

# Check events
kubectl get events --field-selector involvedObject.name=<pod-name>
```

**Resolution Actions:**
1. If specific pod causing issue:
   ```bash
   # Restart pod
   kubectl delete pod <pod-name> -n <namespace>
   
   # Or scale deployment
   kubectl scale deployment <deployment-name> --replicas=5 -n <namespace>
   ```

2. If node issue:
   ```bash
   # Cordon node (prevent new pods)
   kubectl cordon <node-name>
   
   # Drain node (move existing pods)
   kubectl drain <node-name> --ignore-daemonsets --delete-emptydir-data
   
   # Terminate and replace node via AWS ASG
   aws autoscaling terminate-instance-in-auto-scaling-group \
     --instance-id <instance-id> --should-decrement-desired-capacity false
   ```

#### Alert: Database Connection Pool Exhausted 🔴

**Immediate Actions:**
```sql
-- Check active connections
SELECT state, count(*) FROM pg_stat_activity 
GROUP BY state;

-- Check idle connections
SELECT pid, usename, state, query_start 
FROM pg_stat_activity 
WHERE state = 'idle in transaction' 
AND query_start < NOW() - INTERVAL '5 minutes';

-- Kill idle connections (if necessary)
SELECT pg_terminate_backend(pid) 
FROM pg_stat_activity 
WHERE state = 'idle in transaction' 
AND query_start < NOW() - INTERVAL '10 minutes';
```

**Application Level:**
```python
# Check connection pool status
# In Odoo shell:
import psycopg2
from odoo.sql_db import db_connect

# Monitor connection usage
# Review Odoo configuration:
# db_maxconn = 64 (in odoo.conf)
```

**Long-term Fix:**
- Increase db_maxconn in Odoo configuration
- Implement PgBouncer for connection pooling
- Optimize queries reducing connection hold time

#### Alert: Application Error Rate High 🔴

**Investigation:**
```bash
# Check recent errors
kubectl logs -n production deployment/odoo-web | grep ERROR | tail -50

# Check specific error patterns
kubectl logs -n production deployment/odoo-web | grep -i "exception\|error\|failed" | wc -l

# View in Kibana
# Navigate to: kibana.smartdairy.com → Discover → error logs
```

**Common Fixes:**
```bash
# Restart problematic pods
kubectl rollout restart deployment/odoo-web -n production

# Scale up if load-related
kubectl scale deployment odoo-web --replicas=8 -n production

# Check dependent services
kubectl get pods -n production | grep -v Running
```

---

## 5. DAILY MONITORING TASKS

### 5.1 Morning Checklist (09:00)

- [ ] Review overnight alerts (check PagerDuty/Slack)
- [ ] Check system health dashboard
- [ ] Verify all critical services are green
- [ ] Review backup completion status
- [ ] Check disk space trends
- [ ] Review failed login attempts (security)
- [ ] Check IoT device connectivity

### 5.2 Automated Daily Health Report

```bash
#!/bin/bash
# daily_health_report.sh

echo "=== Smart Dairy Daily Health Report ==="
echo "Date: $(date)"
echo ""

echo "1. System Status:"
kubectl get nodes --no-headers | while read name status roles age version; do
    echo "   Node $name: $status"
done

echo ""
echo "2. Critical Pod Status:"
kubectl get pods -n production -l app=odoo --no-headers | awk '{print "   " $1 ": " $3 "/" $4}'

echo ""
echo "3. Database Health:"
psql -h $DB_HOST -U $DB_USER -c "SELECT 'Connections: ' || count(*) FROM pg_stat_activity;" -t
psql -h $DB_HOST -U $DB_USER -c "SELECT 'Replication Lag: ' || extract(epoch from (now() - pg_last_xact_replay_timestamp())) || 's';" -t 2>/dev/null || echo "   Replication: N/A (Primary)"

echo ""
echo "4. Backup Status:"
aws s3 ls s3://smart-dairy-backups/postgresql/full/ | tail -1 | awk '{print "   Latest: " $4 " (" $1 " " $2 ")"}'

echo ""
echo "5. SSL Certificate Expiry:"
echo | openssl s_client -servername erp.smartdairy.com -connect erp.smartdairy.com:443 2>/dev/null | openssl x509 -noout -dates | grep notAfter

echo ""
echo "=== End of Report ==="
```

### 5.3 Mid-Day Check (13:00)

- [ ] Check alert queue for any new issues
- [ ] Review application performance metrics
- [ ] Verify scheduled jobs ran successfully
- [ ] Check pending Celery tasks

### 5.4 End-of-Day Check (18:00)

- [ ] Confirm no critical alerts pending
- [ ] Review day's incident log
- [ ] Check tomorrow's maintenance windows
- [ ] Verify monitoring systems are healthy

---

## 6. WEEKLY REVIEW CHECKLIST

### 6.1 Monday: Capacity Planning Review

| Metric | Action | Threshold |
|--------|--------|-----------|
| **CPU Trend** | Review 7-day graph | Plan expansion if >70% avg |
| **Memory Trend** | Check for leaks | Investigate steady growth |
| **Disk Growth** | Calculate days until full | Alert if <30 days |
| **DB Size** | Review table growth | Archive old data if needed |

### 6.2 Wednesday: Performance Review

```python
# Weekly performance analysis script
import requests
from datetime import datetime, timedelta

# Gather metrics
end_time = datetime.now()
start_time = end_time - timedelta(days=7)

metrics = {
    'avg_response_time': get_prometheus_metric(
        'avg(http_request_duration_seconds{job="odoo"})',
        start_time, end_time
    ),
    'p95_response_time': get_prometheus_metric(
        'histogram_quantile(0.95, http_request_duration_seconds_bucket{job="odoo"})',
        start_time, end_time
    ),
    'error_rate': get_prometheus_metric(
        'rate(http_requests_total{job="odoo",status=~"5.."}[5m])',
        start_time, end_time
    ),
    'throughput': get_prometheus_metric(
        'rate(http_requests_total{job="odoo"}[5m])',
        start_time, end_time
    )
}

# Generate report
generate_weekly_report(metrics)
```

### 6.3 Friday: Security Review

- [ ] Review failed authentication attempts
- [ ] Check for unauthorized access attempts
- [ ] Review WAF blocked requests
- [ ] Verify SSL certificate validity (>30 days)
- [ ] Check for exposed secrets in logs

---

## 7. MONTHLY HEALTH ASSESSMENT

### 7.1 Infrastructure Review

| Component | Metric | Target | Review Date |
|-----------|--------|--------|-------------|
| **EKS Cluster** | Node utilization | <70% avg | Monthly |
| **RDS** | CPU/Memory | <60% avg | Monthly |
| **ElastiCache** | Hit ratio | >99% | Monthly |
| **S3** | Bucket growth | <20% MoM | Monthly |
| **CloudFront** | Cache hit ratio | >85% | Monthly |

### 7.2 Business Metrics Review

```sql
-- Monthly business health check
-- Orders and revenue
SELECT 
    DATE_TRUNC('month', date_order) as month,
    COUNT(*) as order_count,
    SUM(amount_total) as total_revenue,
    AVG(amount_total) as avg_order_value
FROM sale_order
WHERE state = 'sale'
AND date_order >= NOW() - INTERVAL '3 months'
GROUP BY 1
ORDER BY 1 DESC;

-- System performance correlation
SELECT 
    DATE_TRUNC('day', create_date) as day,
    COUNT(*) as transaction_count,
    AVG(EXTRACT(EPOCH FROM (write_date - create_date))) as avg_processing_time
FROM payment_transaction
WHERE create_date >= NOW() - INTERVAL '1 month'
GROUP BY 1
ORDER BY 1 DESC;
```

### 7.3 Cost Optimization Review

| Resource | Current Cost | Optimization Opportunity | Potential Savings |
|----------|--------------|-------------------------|-------------------|
| **EC2 (EKS)** | $X,XXX | Right-sizing nodes, Spot instances | 20-30% |
| **RDS** | $X,XXX | Reserved instances, storage optimization | 15-20% |
| **S3** | $XXX | Lifecycle policies, Intelligent Tiering | 10-15% |
| **Data Transfer** | $XXX | CloudFront optimization | 20-25% |

---

## 8. EMERGENCY RESPONSE

### 8.1 Incident Severity Classification

| Severity | Criteria | Response Team | Communication |
|----------|----------|---------------|---------------|
| **SEV-1** | Complete system outage | All hands | Immediate exec notification |
| **SEV-2** | Major functionality impaired | L2 + L3 | Manager notification |
| **SEV-3** | Minor functionality impaired | L2 | Ticket only |
| **SEV-4** | Cosmetic/no immediate impact | L1 | Scheduled fix |

### 8.2 Incident Response Runbook

**SEV-1 Response (Complete Outage):**

```
T+0:00 - Incident Declared
├── Page on-call engineer
├── Create incident channel (#incident-YYYYMMDD-XXX)
├── Notify stakeholders (VP Eng, IT Director)
└── Start incident log

T+0:05 - Initial Assessment
├── Check status page for external issues
├── Verify AWS service health
├── Check DNS resolution
└── Test basic connectivity

T+0:15 - Recovery Attempts Begin
├── Attempt automatic recovery (pod restart)
├── Check for recent deployments
├── Review error logs
└── Engage additional engineers if needed

T+0:30 - Communication Update
├── Post status to status page
├── Send customer notification if applicable
├── Update incident channel
└── Notify management of ETA

T+1:00 - Escalation if Not Resolved
├── Engage all available engineers
├── Contact AWS support if needed
├── Consider failover to DR site
└── Prepare for manual recovery
```

### 8.3 Communication Templates

**Internal Incident Notification:**
```
Subject: [INCIDENT] Smart Dairy - {Brief Description}

Severity: {SEV-1/SEV-2/SEV-3}
Status: {Investigating/Identified/Monitoring/Resolved}
Start Time: {ISO 8601 timestamp}
Impact: {Description of user impact}

Current Status:
{Detailed description}

Next Update: {Time}
Incident Channel: {Slack channel}
```

**External Status Page Update:**
```
We are currently investigating issues affecting {service}.
Users may experience {symptoms}.
We will provide updates as more information becomes available.
```

---

## 9. MONITORING TOOLS REFERENCE

### 9.1 Prometheus Queries

```promql
# High error rate
rate(http_requests_total{status=~"5.."}[5m]) / 
rate(http_requests_total[5m]) > 0.01

# Slow requests
histogram_quantile(0.95, 
  sum(rate(http_request_duration_seconds_bucket[5m])) by (le)
) > 1

# Pod crash looping
rate(kube_pod_container_status_restarts_total[15m]) > 0

# Database connections
pg_stat_activity_count / pg_settings_max_connections > 0.8

# Disk space
(node_filesystem_avail_bytes / node_filesystem_size_bytes) < 0.1
```

### 9.2 CloudWatch Queries

```bash
# RDS CPU
aws cloudwatch get-metric-statistics \
  --namespace AWS/RDS \
  --metric-name CPUUtilization \
  --dimensions Name=DBInstanceIdentifier,Value=smart-dairy-prod \
  --statistics Average \
  --start-time 2026-11-01T00:00:00Z \
  --end-time 2026-11-08T00:00:00Z \
  --period 3600

# ALB 5xx errors
aws cloudwatch get-metric-statistics \
  --namespace AWS/ApplicationELB \
  --metric-name HTTPCode_Target_5XX_Count \
  --dimensions Name=LoadBalancer,Value=app/smart-dairy-alb \
  --statistics Sum \
  --start-time 2026-11-07T00:00:00Z \
  --end-time 2026-11-08T00:00:00Z \
  --period 300
```

### 9.3 Elasticsearch Queries (Kibana)

```json
// Error logs search
{
  "query": {
    "bool": {
      "must": [
        { "match": { "level": "ERROR" } },
        { "range": { "@timestamp": { "gte": "now-1h" } } }
      ]
    }
  },
  "aggs": {
    "errors_by_service": {
      "terms": { "field": "service.keyword" }
    }
  }
}
```

---

## 10. APPENDICES

### Appendix A: Escalation Contacts

| Role | Primary | Secondary | After Hours |
|------|---------|-----------|-------------|
| **DevOps Lead** | +880-XXX-XXXX-001 | +880-XXX-XXXX-002 | Yes |
| **IT Director** | +880-XXX-XXXX-003 | +880-XXX-XXXX-004 | Critical only |
| **DBA** | +880-XXX-XXXX-005 | +880-XXX-XXXX-006 | Yes |
| **Security Lead** | +880-XXX-XXXX-007 | +880-XXX-XXXX-008 | Critical only |
| **AWS Support** | Enterprise Support | - | 24/7 |

### Appendix B: Useful Commands Quick Reference

```bash
# Kubernetes
kubectl get pods --all-namespaces
kubectl logs <pod> -f
kubectl describe pod <pod>
kubectl top nodes
kubectl top pods

# Database
psql -h $DB_HOST -U $DB_USER -d smart_dairy_prod
check_postgres --action=connection

# Redis
redis-cli -h $REDIS_HOST INFO
redis-cli monitor

# AWS
aws ec2 describe-instances --filters "Name=tag:Environment,Values=production"
aws rds describe-db-instances

# Monitoring
promtool query instant http://prometheus:9090 'up'
curl -s http://localhost:9090/api/v1/status/targets | jq
```

### Appendix C: Runbook Maintenance

| Review | Frequency | Owner |
|--------|-----------|-------|
| Alert rules validation | Weekly | DevOps |
| Dashboard updates | Monthly | DevOps |
| Threshold tuning | Monthly | DevOps + Tech Lead |
| Full runbook review | Quarterly | IT Director |
| Post-incident updates | After each SEV-1 | Incident Commander |

---

**END OF SYSTEM MONITORING RUNBOOK**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | November 8, 2026 | DevOps Engineer | Initial version |
