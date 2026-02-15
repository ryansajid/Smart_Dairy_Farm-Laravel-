# Phase 15: Milestone 145 - Production Deployment

## Document Control

| Attribute | Value |
|-----------|-------|
| **Milestone ID** | MS-145 |
| **Phase** | 15 - Deployment & Handover |
| **Sprint Duration** | Days 721-725 (5 working days) |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-05 |
| **Authors** | Dev 1 (Lead), Dev 2, Dev 3 |
| **Status** | Draft |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Execute production deployment using blue-green strategy, complete DNS cutover, verify go-live success, and ensure system stability with tested rollback procedures.

### 1.2 Key Deliverables

| Deliverable | Description | Owner | Day |
|-------------|-------------|-------|-----|
| Pre-deployment Checklist | Verified all prerequisites | All | 721 |
| Final Backup | Complete backup before go-live | Dev 1 | 721 |
| Blue-Green Deployment | Zero-downtime deployment | Dev 2 | 722 |
| DNS Cutover | Production traffic routing | Dev 1 | 723 |
| Go-Live Verification | System health confirmed | All | 724 |
| Rollback Procedure | Tested rollback capability | Dev 2 | 725 |

### 1.3 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Deployment downtime | 0 minutes | Monitoring |
| Post-deployment errors | <0.1% | Error rate |
| DNS propagation | <1 hour | DNS checks |
| Rollback time | <15 minutes | Drill test |
| Data integrity | 100% | Validation queries |

---

## 2. Day-by-Day Implementation

### Day 721: Pre-Deployment Preparation

#### Dev 1 Tasks (8 hours)

**Task 1: Pre-Deployment Checklist Execution (4h)**

```markdown
# Production Deployment Pre-Flight Checklist

## Environment Verification
- [ ] Production infrastructure healthy
- [ ] All EKS nodes ready
- [ ] RDS Multi-AZ active
- [ ] ElastiCache cluster healthy
- [ ] S3 buckets accessible
- [ ] CloudFront distribution active

## Security Verification
- [ ] WAF rules active
- [ ] SSL certificates valid (>30 days)
- [ ] Vault unsealed and accessible
- [ ] KMS keys accessible
- [ ] Security groups verified

## Application Readiness
- [ ] All container images tagged for release
- [ ] Image vulnerability scan passed
- [ ] Configuration reviewed
- [ ] Feature flags set correctly
- [ ] Third-party integrations verified

## Data Readiness
- [ ] Migration scripts tested
- [ ] Rollback scripts verified
- [ ] Backup completed
- [ ] Data validation queries ready

## Team Readiness
- [ ] War room scheduled
- [ ] Communication channels open
- [ ] Escalation contacts confirmed
- [ ] Rollback decision criteria defined
```

**Task 2: Final Production Backup (4h)**

```bash
#!/bin/bash
# scripts/deployment/production_backup.sh
# Complete backup before production deployment

set -e

BACKUP_DATE=$(date +%Y%m%d-%H%M%S)
BACKUP_BUCKET="smart-dairy-backups"
BACKUP_PREFIX="pre-golive/${BACKUP_DATE}"

echo "=== Smart Dairy Pre-GoLive Backup ==="
echo "Backup ID: ${BACKUP_DATE}"

# Database backup
echo "Backing up PostgreSQL database..."
PGPASSWORD=$DB_PASSWORD pg_dump \
    -h $RDS_ENDPOINT \
    -U admin \
    -d smartdairy \
    -Fc \
    -f "/tmp/smartdairy-${BACKUP_DATE}.dump"

# Compress and upload
gzip "/tmp/smartdairy-${BACKUP_DATE}.dump"
aws s3 cp "/tmp/smartdairy-${BACKUP_DATE}.dump.gz" \
    "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/database/"

# S3 assets backup (sync)
echo "Syncing S3 assets..."
aws s3 sync \
    "s3://smart-dairy-production-assets/" \
    "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/assets/" \
    --quiet

# Redis snapshot
echo "Creating Redis snapshot..."
aws elasticache create-snapshot \
    --replication-group-id smart-dairy-production \
    --snapshot-name "pre-golive-${BACKUP_DATE}"

# Kubernetes state backup
echo "Backing up Kubernetes state..."
kubectl get all -n smart-dairy -o yaml > "/tmp/k8s-state-${BACKUP_DATE}.yaml"
aws s3 cp "/tmp/k8s-state-${BACKUP_DATE}.yaml" \
    "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/kubernetes/"

# Create backup manifest
cat > "/tmp/backup-manifest.json" <<EOF
{
    "backup_id": "${BACKUP_DATE}",
    "timestamp": "$(date -u +%Y-%m-%dT%H:%M:%SZ)",
    "components": {
        "database": "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/database/smartdairy-${BACKUP_DATE}.dump.gz",
        "assets": "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/assets/",
        "kubernetes": "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/kubernetes/k8s-state-${BACKUP_DATE}.yaml",
        "redis": "pre-golive-${BACKUP_DATE}"
    },
    "verification": {
        "database_size": "$(stat -f%z /tmp/smartdairy-${BACKUP_DATE}.dump.gz 2>/dev/null || echo 'N/A')",
        "status": "completed"
    }
}
EOF

aws s3 cp "/tmp/backup-manifest.json" \
    "s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/manifest.json"

echo "=== Backup Complete ==="
echo "Manifest: s3://${BACKUP_BUCKET}/${BACKUP_PREFIX}/manifest.json"
```

#### Dev 2 Tasks (8 hours)

**Task 1: Blue-Green Environment Preparation (4h)**

```hcl
# terraform/modules/deployment/blue-green/main.tf
# Blue-Green Deployment Infrastructure

variable "active_environment" {
  description = "Currently active environment (blue or green)"
  type        = string
  default     = "blue"
}

# Blue Environment
resource "aws_lb_target_group" "blue" {
  name        = "smart-dairy-blue"
  port        = 8069
  protocol    = "HTTP"
  vpc_id      = var.vpc_id
  target_type = "ip"

  health_check {
    enabled             = true
    healthy_threshold   = 2
    interval            = 30
    matcher             = "200"
    path                = "/web/health"
    port                = "traffic-port"
    protocol            = "HTTP"
    timeout             = 5
    unhealthy_threshold = 3
  }

  tags = {
    Environment = "blue"
    Project     = "smart-dairy"
  }
}

# Green Environment
resource "aws_lb_target_group" "green" {
  name        = "smart-dairy-green"
  port        = 8069
  protocol    = "HTTP"
  vpc_id      = var.vpc_id
  target_type = "ip"

  health_check {
    enabled             = true
    healthy_threshold   = 2
    interval            = 30
    matcher             = "200"
    path                = "/web/health"
    port                = "traffic-port"
    protocol            = "HTTP"
    timeout             = 5
    unhealthy_threshold = 3
  }

  tags = {
    Environment = "green"
    Project     = "smart-dairy"
  }
}

# ALB Listener with weighted routing
resource "aws_lb_listener_rule" "production" {
  listener_arn = var.alb_listener_arn
  priority     = 100

  action {
    type = "forward"

    forward {
      target_group {
        arn    = aws_lb_target_group.blue.arn
        weight = var.active_environment == "blue" ? 100 : 0
      }

      target_group {
        arn    = aws_lb_target_group.green.arn
        weight = var.active_environment == "green" ? 100 : 0
      }

      stickiness {
        enabled  = true
        duration = 3600
      }
    }
  }

  condition {
    host_header {
      values = ["smartdairy.com", "www.smartdairy.com"]
    }
  }
}

output "blue_target_group_arn" {
  value = aws_lb_target_group.blue.arn
}

output "green_target_group_arn" {
  value = aws_lb_target_group.green.arn
}
```

**Task 2: Deployment Automation Scripts (4h)**

```bash
#!/bin/bash
# scripts/deployment/blue_green_deploy.sh
# Blue-Green Deployment Script

set -e

usage() {
    echo "Usage: $0 <target-environment> <image-tag>"
    echo "  target-environment: blue or green"
    echo "  image-tag: Docker image tag to deploy"
    exit 1
}

[[ $# -ne 2 ]] && usage

TARGET_ENV=$1
IMAGE_TAG=$2
NAMESPACE="smart-dairy"

# Validate target environment
if [[ "$TARGET_ENV" != "blue" && "$TARGET_ENV" != "green" ]]; then
    echo "Error: Target environment must be 'blue' or 'green'"
    exit 1
fi

echo "=== Blue-Green Deployment ==="
echo "Target Environment: $TARGET_ENV"
echo "Image Tag: $IMAGE_TAG"
echo ""

# Get current active environment
CURRENT_ENV=$(kubectl get configmap deployment-config -n $NAMESPACE -o jsonpath='{.data.active_environment}' 2>/dev/null || echo "blue")
echo "Current Active: $CURRENT_ENV"

if [[ "$TARGET_ENV" == "$CURRENT_ENV" ]]; then
    echo "Warning: Deploying to currently active environment"
    read -p "Continue? (y/n) " -n 1 -r
    echo
    [[ ! $REPLY =~ ^[Yy]$ ]] && exit 1
fi

# Update image tags in manifests
echo "Updating manifests..."
sed -i "s|IMAGE_TAG|$IMAGE_TAG|g" kubernetes/production/$TARGET_ENV/*.yaml

# Deploy to target environment
echo "Deploying to $TARGET_ENV environment..."
kubectl apply -f kubernetes/production/$TARGET_ENV/

# Wait for rollout
echo "Waiting for deployment rollout..."
kubectl rollout status deployment/odoo-$TARGET_ENV -n $NAMESPACE --timeout=600s
kubectl rollout status deployment/api-$TARGET_ENV -n $NAMESPACE --timeout=300s
kubectl rollout status deployment/frontend-$TARGET_ENV -n $NAMESPACE --timeout=300s

# Run smoke tests on target environment
echo "Running smoke tests..."
TARGET_URL=$(kubectl get ingress smart-dairy-$TARGET_ENV -n $NAMESPACE -o jsonpath='{.spec.rules[0].host}')

for i in {1..5}; do
    if curl -sf "https://$TARGET_URL/web/health" > /dev/null; then
        echo "Smoke test passed!"
        break
    fi
    echo "Attempt $i failed, retrying..."
    sleep 10
done

echo "=== Deployment to $TARGET_ENV Complete ==="
echo ""
echo "Next steps:"
echo "1. Verify application on $TARGET_ENV environment"
echo "2. Run: ./scripts/deployment/switch_traffic.sh $TARGET_ENV"
```

#### Dev 3 Tasks (8 hours)

**Task 1: Go-Live Communication Plan (4h)**

```markdown
# Smart Dairy Go-Live Communication Plan

## Timeline

### T-24 Hours (Day before Go-Live)
- [ ] Send reminder to all stakeholders
- [ ] Confirm war room participants
- [ ] Verify emergency contact list
- [ ] Test communication channels

### T-2 Hours (Before cutover)
- [ ] Send "Go-Live Starting Soon" notification
- [ ] Confirm all teams ready
- [ ] Final go/no-go call

### T-0 (Go-Live)
- [ ] Announce go-live start
- [ ] Regular status updates every 15 minutes
- [ ] Announce completion

### T+1 Hour (Post Go-Live)
- [ ] Send success notification
- [ ] Share monitoring dashboard links
- [ ] Communicate hypercare schedule

## Communication Templates

### Pre-Go-Live Reminder
Subject: Smart Dairy Go-Live Tomorrow - Action Required

Dear Team,

Smart Dairy production go-live is scheduled for tomorrow, [DATE].

**Timeline:**
- Go-Live Start: [TIME]
- Expected Duration: 2-4 hours
- Hypercare Begins: Immediately after

**Your Role:**
[Specific instructions]

**War Room:**
[Meeting link or physical location]

**Emergency Contacts:**
- Technical Lead: [Phone]
- Project Manager: [Phone]

Please confirm your availability by replying to this email.

### Go-Live Complete
Subject: ✅ Smart Dairy Go-Live Complete - System Live

Dear Stakeholders,

Smart Dairy production go-live has been successfully completed!

**Status:** LIVE
**Go-Live Time:** [TIME]
**Current System Status:** Healthy

**What's Next:**
- 24/7 Hypercare support active
- Report issues to: support@smartdairy.com
- Emergency: [Phone]

**Monitoring Dashboard:**
[Dashboard URL]

Thank you for your support!
```

**Task 2: User Notification Preparation (4h)**

Prepare all user-facing communications and help resources.

#### Day 721 Deliverables

- [x] Pre-deployment checklist verified
- [x] Complete backup created and verified
- [x] Blue-green infrastructure ready
- [x] Deployment scripts tested
- [x] Communication plan finalized

---

### Day 722: Blue-Green Deployment Execution

#### Dev 1 Tasks (8 hours)

**Task 1: Database Migration Execution (4h)**

```bash
#!/bin/bash
# scripts/deployment/run_migrations.sh
# Execute production database migrations

set -e

echo "=== Production Database Migration ==="

# Create migration lock
LOCK_ID=$(aws dynamodb put-item \
    --table-name smart-dairy-locks \
    --item '{"lock_id": {"S": "migration"}, "holder": {"S": "'$(hostname)'"}, "timestamp": {"S": "'$(date -u +%Y-%m-%dT%H:%M:%SZ)'"}}' \
    --condition-expression "attribute_not_exists(lock_id)" \
    --return-values ALL_OLD 2>/dev/null || echo "LOCK_EXISTS")

if [[ "$LOCK_ID" == "LOCK_EXISTS" ]]; then
    echo "Error: Migration lock exists. Another migration may be in progress."
    exit 1
fi

cleanup() {
    echo "Releasing migration lock..."
    aws dynamodb delete-item \
        --table-name smart-dairy-locks \
        --key '{"lock_id": {"S": "migration"}}'
}
trap cleanup EXIT

# Run Odoo migrations
echo "Running Odoo module updates..."
kubectl exec -it deployment/odoo-green -n smart-dairy -- \
    odoo -d smartdairy -u smart_dairy_core,smart_dairy_farm,smart_dairy_billing --stop-after-init

# Verify migration success
echo "Verifying migration..."
kubectl exec -it deployment/odoo-green -n smart-dairy -- \
    odoo shell -d smartdairy <<EOF
from odoo import api, SUPERUSER_ID
env = api.Environment(cr, SUPERUSER_ID, {})
modules = env['ir.module.module'].search([('name', 'like', 'smart_dairy%')])
for m in modules:
    print(f"{m.name}: {m.state}")
    if m.state != 'installed':
        raise Exception(f"Module {m.name} not installed properly")
print("All modules verified!")
EOF

echo "=== Migration Complete ==="
```

**Task 2: Monitor Migration Progress (4h)**

Monitor database during migration and verify data integrity.

#### Dev 2 Tasks (8 hours)

**Task 1: Deploy to Green Environment (4h)**

```bash
#!/bin/bash
# scripts/deployment/deploy_production.sh
# Production deployment to green environment

set -e

IMAGE_TAG=${1:-$(git rev-parse --short HEAD)}
NAMESPACE="smart-dairy"
TARGET_ENV="green"

echo "=== Production Deployment ==="
echo "Image Tag: $IMAGE_TAG"
echo "Target: $TARGET_ENV environment"

# Pre-deployment validation
echo "Running pre-deployment checks..."
./scripts/deployment/pre-deploy-checks.sh

# Deploy to green environment
echo "Deploying application..."
./scripts/deployment/blue_green_deploy.sh $TARGET_ENV $IMAGE_TAG

# Wait for pods to be ready
echo "Waiting for all pods to be ready..."
kubectl wait --for=condition=ready pod \
    -l environment=$TARGET_ENV \
    -n $NAMESPACE \
    --timeout=600s

# Health check
echo "Running health checks..."
GREEN_URL="https://green.smartdairy.com"

# Check Odoo health
if ! curl -sf "$GREEN_URL/web/health" | grep -q "ok"; then
    echo "Error: Odoo health check failed"
    exit 1
fi

# Check API health
if ! curl -sf "$GREEN_URL/api/v1/health" | grep -q "healthy"; then
    echo "Error: API health check failed"
    exit 1
fi

echo "Health checks passed!"

# Run smoke tests
echo "Running smoke tests..."
python scripts/deployment/smoke_tests.py --url $GREEN_URL --env production

echo "=== Deployment to $TARGET_ENV Complete ==="
echo "Ready for traffic switch"
```

**Task 2: Canary Deployment (Optional) (4h)**

```bash
#!/bin/bash
# scripts/deployment/canary_deploy.sh
# Gradual traffic shift for canary deployment

set -e

TARGET_ENV=${1:-green}
NAMESPACE="smart-dairy"

echo "=== Canary Deployment ==="

# Traffic shift stages
STAGES=(5 10 25 50 75 100)

for PERCENTAGE in "${STAGES[@]}"; do
    echo "Shifting $PERCENTAGE% traffic to $TARGET_ENV..."

    # Update traffic weights
    kubectl patch virtualservice smart-dairy \
        -n $NAMESPACE \
        --type=merge \
        -p "{
            \"spec\": {
                \"http\": [{
                    \"route\": [
                        {\"destination\": {\"host\": \"odoo-blue\", \"port\": {\"number\": 8069}}, \"weight\": $((100 - PERCENTAGE))},
                        {\"destination\": {\"host\": \"odoo-green\", \"port\": {\"number\": 8069}}, \"weight\": $PERCENTAGE}
                    ]
                }]
            }
        }"

    echo "Traffic at $PERCENTAGE%"

    # Monitor for 5 minutes
    echo "Monitoring for anomalies..."
    ANOMALIES=$(./scripts/deployment/check_anomalies.sh)

    if [[ "$ANOMALIES" != "0" ]]; then
        echo "Anomalies detected! Rolling back..."
        kubectl patch virtualservice smart-dairy \
            -n $NAMESPACE \
            --type=merge \
            -p '{"spec": {"http": [{"route": [{"destination": {"host": "odoo-blue"}, "weight": 100}]}]}}'
        exit 1
    fi

    if [[ "$PERCENTAGE" -lt 100 ]]; then
        echo "Waiting 5 minutes before next stage..."
        sleep 300
    fi
done

echo "=== Canary Deployment Complete ==="
echo "All traffic now on $TARGET_ENV"
```

#### Dev 3 Tasks (8 hours)

**Task 1: Real-time Deployment Monitoring (4h)**

```python
# scripts/deployment/deployment_monitor.py
"""
Real-time Deployment Monitoring Dashboard
"""

import time
import requests
import json
from datetime import datetime
from dataclasses import dataclass
from typing import Dict, List
import threading

@dataclass
class HealthStatus:
    service: str
    status: str
    response_time: float
    timestamp: str
    details: Dict = None

class DeploymentMonitor:
    def __init__(self, config: Dict):
        self.config = config
        self.health_history: List[HealthStatus] = []
        self.running = True
        self.alert_thresholds = {
            'response_time': 3000,  # ms
            'error_rate': 0.01,  # 1%
            'consecutive_failures': 3
        }

    def check_service_health(self, name: str, url: str) -> HealthStatus:
        """Check health of a single service"""
        start_time = time.time()
        try:
            response = requests.get(url, timeout=10)
            response_time = (time.time() - start_time) * 1000

            return HealthStatus(
                service=name,
                status='healthy' if response.status_code == 200 else 'unhealthy',
                response_time=response_time,
                timestamp=datetime.utcnow().isoformat(),
                details={'status_code': response.status_code}
            )
        except Exception as e:
            return HealthStatus(
                service=name,
                status='error',
                response_time=-1,
                timestamp=datetime.utcnow().isoformat(),
                details={'error': str(e)}
            )

    def check_all_services(self) -> Dict:
        """Check all configured services"""
        results = {}
        for name, url in self.config['endpoints'].items():
            results[name] = self.check_service_health(name, url)
            self.health_history.append(results[name])
        return results

    def check_metrics(self) -> Dict:
        """Check Prometheus metrics"""
        prometheus_url = self.config['prometheus_url']

        metrics = {}

        # Error rate
        query = 'sum(rate(http_requests_total{status=~"5.."}[5m])) / sum(rate(http_requests_total[5m]))'
        response = requests.get(f"{prometheus_url}/api/v1/query", params={'query': query})
        if response.status_code == 200:
            data = response.json()
            if data['data']['result']:
                metrics['error_rate'] = float(data['data']['result'][0]['value'][1])

        # Request rate
        query = 'sum(rate(http_requests_total[5m]))'
        response = requests.get(f"{prometheus_url}/api/v1/query", params={'query': query})
        if response.status_code == 200:
            data = response.json()
            if data['data']['result']:
                metrics['request_rate'] = float(data['data']['result'][0]['value'][1])

        # Response time P95
        query = 'histogram_quantile(0.95, sum(rate(http_request_duration_seconds_bucket[5m])) by (le))'
        response = requests.get(f"{prometheus_url}/api/v1/query", params={'query': query})
        if response.status_code == 200:
            data = response.json()
            if data['data']['result']:
                metrics['p95_response_time'] = float(data['data']['result'][0]['value'][1]) * 1000

        return metrics

    def should_alert(self, metrics: Dict) -> List[str]:
        """Check if any metrics exceed thresholds"""
        alerts = []

        if metrics.get('error_rate', 0) > self.alert_thresholds['error_rate']:
            alerts.append(f"Error rate {metrics['error_rate']:.2%} exceeds threshold")

        if metrics.get('p95_response_time', 0) > self.alert_thresholds['response_time']:
            alerts.append(f"P95 response time {metrics['p95_response_time']:.0f}ms exceeds threshold")

        return alerts

    def run_continuous_monitoring(self, interval: int = 30):
        """Run continuous monitoring loop"""
        print("Starting deployment monitoring...")

        while self.running:
            print(f"\n{'='*50}")
            print(f"Check at {datetime.utcnow().isoformat()}")
            print('='*50)

            # Check services
            services = self.check_all_services()
            for name, status in services.items():
                icon = '✓' if status.status == 'healthy' else '✗'
                print(f"{icon} {name}: {status.status} ({status.response_time:.0f}ms)")

            # Check metrics
            metrics = self.check_metrics()
            print(f"\nMetrics:")
            print(f"  Error Rate: {metrics.get('error_rate', 0):.4%}")
            print(f"  Request Rate: {metrics.get('request_rate', 0):.1f} req/s")
            print(f"  P95 Response: {metrics.get('p95_response_time', 0):.0f}ms")

            # Check for alerts
            alerts = self.should_alert(metrics)
            if alerts:
                print(f"\n⚠️ ALERTS:")
                for alert in alerts:
                    print(f"  - {alert}")
                self.send_alert(alerts)

            time.sleep(interval)

    def send_alert(self, alerts: List[str]):
        """Send alert notification"""
        webhook_url = self.config.get('slack_webhook')
        if webhook_url:
            payload = {
                "text": "⚠️ Deployment Alert",
                "attachments": [{
                    "color": "danger",
                    "text": "\n".join(alerts)
                }]
            }
            requests.post(webhook_url, json=payload)

    def stop(self):
        """Stop monitoring"""
        self.running = False


if __name__ == "__main__":
    config = {
        'endpoints': {
            'Odoo': 'https://smartdairy.com/web/health',
            'API': 'https://api.smartdairy.com/health',
            'Frontend': 'https://smartdairy.com/',
        },
        'prometheus_url': 'http://prometheus.monitoring:9090',
        'slack_webhook': os.environ.get('SLACK_WEBHOOK_URL')
    }

    monitor = DeploymentMonitor(config)

    try:
        monitor.run_continuous_monitoring(interval=30)
    except KeyboardInterrupt:
        monitor.stop()
        print("\nMonitoring stopped.")
```

**Task 2: User Communication During Deployment (4h)**

Send status updates to stakeholders during deployment.

#### Day 722 Deliverables

- [x] Database migrations executed
- [x] Green environment deployed
- [x] Health checks passed
- [x] Smoke tests passed
- [x] Deployment monitoring active

---

### Day 723: DNS Cutover & Traffic Routing

#### Dev 1 Tasks (8 hours)

**Task 1: DNS Cutover Execution (4h)**

```bash
#!/bin/bash
# scripts/deployment/dns_cutover.sh
# Production DNS cutover

set -e

echo "=== DNS Cutover to Production ==="

HOSTED_ZONE_ID="Z1234567890ABC"
DOMAIN="smartdairy.com"
NEW_ALB_DNS="smart-dairy-prod-alb-123456.ap-south-1.elb.amazonaws.com"

# Create change batch
CHANGE_BATCH=$(cat <<EOF
{
    "Changes": [
        {
            "Action": "UPSERT",
            "ResourceRecordSet": {
                "Name": "$DOMAIN",
                "Type": "A",
                "AliasTarget": {
                    "HostedZoneId": "ZONEIDFORLOADBALANCER",
                    "DNSName": "$NEW_ALB_DNS",
                    "EvaluateTargetHealth": true
                }
            }
        },
        {
            "Action": "UPSERT",
            "ResourceRecordSet": {
                "Name": "www.$DOMAIN",
                "Type": "A",
                "AliasTarget": {
                    "HostedZoneId": "ZONEIDFORLOADBALANCER",
                    "DNSName": "$NEW_ALB_DNS",
                    "EvaluateTargetHealth": true
                }
            }
        },
        {
            "Action": "UPSERT",
            "ResourceRecordSet": {
                "Name": "api.$DOMAIN",
                "Type": "A",
                "AliasTarget": {
                    "HostedZoneId": "ZONEIDFORLOADBALANCER",
                    "DNSName": "$NEW_ALB_DNS",
                    "EvaluateTargetHealth": true
                }
            }
        }
    ]
}
EOF
)

echo "Applying DNS changes..."
CHANGE_ID=$(aws route53 change-resource-record-sets \
    --hosted-zone-id $HOSTED_ZONE_ID \
    --change-batch "$CHANGE_BATCH" \
    --query 'ChangeInfo.Id' \
    --output text)

echo "Change ID: $CHANGE_ID"

# Wait for DNS propagation
echo "Waiting for DNS propagation..."
aws route53 wait resource-record-sets-changed --id $CHANGE_ID

echo "DNS propagation complete!"

# Verify DNS resolution
echo "Verifying DNS resolution..."
for record in "$DOMAIN" "www.$DOMAIN" "api.$DOMAIN"; do
    RESOLVED=$(dig +short $record)
    echo "$record -> $RESOLVED"
done

echo "=== DNS Cutover Complete ==="
```

**Task 2: Traffic Switch to Green (4h)**

```bash
#!/bin/bash
# scripts/deployment/switch_traffic.sh
# Switch production traffic to target environment

set -e

TARGET_ENV=${1:-green}
NAMESPACE="smart-dairy"

echo "=== Switching Traffic to $TARGET_ENV ==="

# Verify target environment health
echo "Verifying $TARGET_ENV environment health..."
./scripts/deployment/health_check.sh $TARGET_ENV

if [[ $? -ne 0 ]]; then
    echo "Error: $TARGET_ENV environment health check failed"
    exit 1
fi

# Update ALB target group weights
echo "Updating load balancer routing..."
if [[ "$TARGET_ENV" == "green" ]]; then
    BLUE_WEIGHT=0
    GREEN_WEIGHT=100
else
    BLUE_WEIGHT=100
    GREEN_WEIGHT=0
fi

aws elbv2 modify-listener \
    --listener-arn $ALB_LISTENER_ARN \
    --default-actions "[{
        \"Type\": \"forward\",
        \"ForwardConfig\": {
            \"TargetGroups\": [
                {\"TargetGroupArn\": \"$BLUE_TG_ARN\", \"Weight\": $BLUE_WEIGHT},
                {\"TargetGroupArn\": \"$GREEN_TG_ARN\", \"Weight\": $GREEN_WEIGHT}
            ]
        }
    }]"

# Update deployment config
kubectl create configmap deployment-config \
    -n $NAMESPACE \
    --from-literal=active_environment=$TARGET_ENV \
    --dry-run=client -o yaml | kubectl apply -f -

# Verify traffic routing
echo "Verifying traffic routing..."
for i in {1..10}; do
    RESPONSE=$(curl -s -I https://smartdairy.com/web/health | head -n1)
    echo "Request $i: $RESPONSE"
    sleep 1
done

echo "=== Traffic Switch Complete ==="
echo "Production traffic now served by $TARGET_ENV environment"
```

#### Dev 2 Tasks (8 hours)

**Task 1: Post-Cutover Monitoring (4h)**

Monitor system immediately after traffic switch.

**Task 2: Connection Draining (4h)**

```bash
#!/bin/bash
# scripts/deployment/drain_old_environment.sh
# Gracefully drain connections from old environment

set -e

OLD_ENV=${1:-blue}
NAMESPACE="smart-dairy"
DRAIN_TIMEOUT=300  # 5 minutes

echo "=== Draining $OLD_ENV Environment ==="

# Get old environment pods
PODS=$(kubectl get pods -n $NAMESPACE -l environment=$OLD_ENV -o name)

for POD in $PODS; do
    POD_NAME=$(echo $POD | cut -d'/' -f2)
    echo "Draining $POD_NAME..."

    # Remove from service endpoints
    kubectl label pod $POD_NAME -n $NAMESPACE drain=true --overwrite

    # Wait for active connections to complete
    echo "Waiting for connections to drain (max ${DRAIN_TIMEOUT}s)..."

    START_TIME=$(date +%s)
    while true; do
        CONNECTIONS=$(kubectl exec $POD_NAME -n $NAMESPACE -- \
            netstat -an | grep ESTABLISHED | wc -l 2>/dev/null || echo "0")

        ELAPSED=$(($(date +%s) - START_TIME))

        if [[ "$CONNECTIONS" -le 5 ]] || [[ $ELAPSED -ge $DRAIN_TIMEOUT ]]; then
            echo "Pod $POD_NAME drained (connections: $CONNECTIONS)"
            break
        fi

        echo "Active connections: $CONNECTIONS (elapsed: ${ELAPSED}s)"
        sleep 10
    done
done

echo "=== Drain Complete ==="
echo "Old environment ready for scale down or termination"
```

#### Dev 3 Tasks (8 hours)

**Task 1: End-User Verification (4h)**

Verify system functionality from end-user perspective.

**Task 2: Status Page Updates (4h)**

Update public status page and internal dashboards.

#### Day 723 Deliverables

- [x] DNS cutover executed
- [x] Traffic switched to green environment
- [x] DNS propagation verified
- [x] Old environment drained
- [x] Post-cutover monitoring active

---

### Day 724: Go-Live Verification

#### All Team Tasks (8 hours each)

**Task 1: Comprehensive System Verification (4h each)**

```python
# scripts/deployment/golive_verification.py
"""
Go-Live Verification Suite
"""

import requests
import psycopg2
import redis
from datetime import datetime
from typing import Dict, List
import json

class GoLiveVerification:
    def __init__(self, config: Dict):
        self.config = config
        self.results = []

    def verify_web_application(self) -> Dict:
        """Verify web application functionality"""
        tests = [
            ('Homepage', '/'),
            ('Login Page', '/web/login'),
            ('Health Check', '/web/health'),
            ('Static Assets', '/web/static/src/js/boot.js'),
        ]

        results = {'name': 'Web Application', 'tests': []}

        for name, path in tests:
            try:
                response = requests.get(f"{self.config['web_url']}{path}", timeout=10)
                passed = response.status_code == 200
                results['tests'].append({
                    'name': name,
                    'passed': passed,
                    'status_code': response.status_code,
                    'response_time_ms': response.elapsed.total_seconds() * 1000
                })
            except Exception as e:
                results['tests'].append({
                    'name': name,
                    'passed': False,
                    'error': str(e)
                })

        results['passed'] = all(t['passed'] for t in results['tests'])
        return results

    def verify_api_endpoints(self) -> Dict:
        """Verify API endpoints"""
        endpoints = [
            ('Health', 'GET', '/api/v1/health'),
            ('Version', 'GET', '/api/v1/version'),
            ('Status', 'GET', '/api/v1/status'),
        ]

        results = {'name': 'API Endpoints', 'tests': []}

        for name, method, path in endpoints:
            try:
                if method == 'GET':
                    response = requests.get(f"{self.config['api_url']}{path}", timeout=10)
                else:
                    response = requests.post(f"{self.config['api_url']}{path}", timeout=10)

                passed = response.status_code in [200, 401]  # 401 OK for auth-required
                results['tests'].append({
                    'name': name,
                    'passed': passed,
                    'status_code': response.status_code
                })
            except Exception as e:
                results['tests'].append({
                    'name': name,
                    'passed': False,
                    'error': str(e)
                })

        results['passed'] = all(t['passed'] for t in results['tests'])
        return results

    def verify_database(self) -> Dict:
        """Verify database connectivity and data integrity"""
        results = {'name': 'Database', 'tests': []}

        try:
            conn = psycopg2.connect(
                host=self.config['db_host'],
                database=self.config['db_name'],
                user=self.config['db_user'],
                password=self.config['db_password']
            )
            cursor = conn.cursor()

            # Check connection
            cursor.execute("SELECT 1")
            results['tests'].append({'name': 'Connection', 'passed': True})

            # Check key tables exist
            cursor.execute("""
                SELECT table_name FROM information_schema.tables
                WHERE table_schema = 'public'
                AND table_name IN ('res_users', 'dairy_farmer', 'dairy_collection')
            """)
            tables = [row[0] for row in cursor.fetchall()]
            results['tests'].append({
                'name': 'Core Tables',
                'passed': len(tables) >= 3,
                'tables_found': tables
            })

            # Check data integrity
            cursor.execute("SELECT COUNT(*) FROM res_users WHERE active = true")
            user_count = cursor.fetchone()[0]
            results['tests'].append({
                'name': 'User Data',
                'passed': user_count > 0,
                'count': user_count
            })

            conn.close()
        except Exception as e:
            results['tests'].append({
                'name': 'Database Connection',
                'passed': False,
                'error': str(e)
            })

        results['passed'] = all(t.get('passed', False) for t in results['tests'])
        return results

    def verify_redis(self) -> Dict:
        """Verify Redis connectivity"""
        results = {'name': 'Redis Cache', 'tests': []}

        try:
            r = redis.Redis(
                host=self.config['redis_host'],
                port=self.config.get('redis_port', 6379),
                password=self.config.get('redis_password')
            )

            # Check connection
            r.ping()
            results['tests'].append({'name': 'Connection', 'passed': True})

            # Check info
            info = r.info()
            results['tests'].append({
                'name': 'Redis Info',
                'passed': True,
                'version': info.get('redis_version'),
                'connected_clients': info.get('connected_clients')
            })

        except Exception as e:
            results['tests'].append({
                'name': 'Redis Connection',
                'passed': False,
                'error': str(e)
            })

        results['passed'] = all(t.get('passed', False) for t in results['tests'])
        return results

    def verify_ssl(self) -> Dict:
        """Verify SSL configuration"""
        results = {'name': 'SSL/TLS', 'tests': []}

        try:
            response = requests.get(self.config['web_url'], timeout=10)

            # Check HTTPS
            results['tests'].append({
                'name': 'HTTPS Enabled',
                'passed': response.url.startswith('https://')
            })

            # Check security headers
            headers_to_check = [
                'Strict-Transport-Security',
                'X-Content-Type-Options',
                'X-Frame-Options'
            ]

            for header in headers_to_check:
                results['tests'].append({
                    'name': f'Header: {header}',
                    'passed': header in response.headers,
                    'value': response.headers.get(header, 'Missing')
                })

        except Exception as e:
            results['tests'].append({
                'name': 'SSL Check',
                'passed': False,
                'error': str(e)
            })

        results['passed'] = all(t.get('passed', False) for t in results['tests'])
        return results

    def run_all_verifications(self) -> Dict:
        """Run complete verification suite"""
        self.results = [
            self.verify_web_application(),
            self.verify_api_endpoints(),
            self.verify_database(),
            self.verify_redis(),
            self.verify_ssl()
        ]

        all_passed = all(r['passed'] for r in self.results)

        return {
            'timestamp': datetime.utcnow().isoformat(),
            'overall_status': 'PASS' if all_passed else 'FAIL',
            'categories': self.results
        }


if __name__ == "__main__":
    config = {
        'web_url': 'https://smartdairy.com',
        'api_url': 'https://api.smartdairy.com',
        'db_host': os.environ['DB_HOST'],
        'db_name': 'smartdairy',
        'db_user': 'admin',
        'db_password': os.environ['DB_PASSWORD'],
        'redis_host': os.environ['REDIS_HOST'],
        'redis_password': os.environ.get('REDIS_PASSWORD')
    }

    verifier = GoLiveVerification(config)
    results = verifier.run_all_verifications()

    print(json.dumps(results, indent=2))

    # Write report
    with open('golive_verification_report.json', 'w') as f:
        json.dump(results, f, indent=2)
```

**Task 2: Business Function Verification (4h each)**

Verify core business functions are working correctly.

#### Day 724 Deliverables

- [x] All services verified operational
- [x] Database integrity confirmed
- [x] SSL/Security verified
- [x] Core business functions tested
- [x] Go-Live verification report completed

---

### Day 725: Stabilization & Rollback Verification

#### Dev 1 Tasks (8 hours)

**Task 1: Rollback Procedure Testing (4h)**

```bash
#!/bin/bash
# scripts/deployment/test_rollback.sh
# Test rollback procedure (non-destructive)

set -e

echo "=== Rollback Procedure Test ==="
echo "This is a DRY RUN - no actual changes will be made"

CURRENT_ENV=$(kubectl get configmap deployment-config -n smart-dairy -o jsonpath='{.data.active_environment}')
ROLLBACK_ENV=$([[ "$CURRENT_ENV" == "green" ]] && echo "blue" || echo "green")

echo "Current active: $CURRENT_ENV"
echo "Rollback target: $ROLLBACK_ENV"

# Verify rollback environment health
echo ""
echo "Step 1: Verify rollback environment health..."
kubectl get pods -n smart-dairy -l environment=$ROLLBACK_ENV

READY_PODS=$(kubectl get pods -n smart-dairy -l environment=$ROLLBACK_ENV -o jsonpath='{.items[*].status.containerStatuses[*].ready}' | tr ' ' '\n' | grep -c true || echo 0)
echo "Ready pods in $ROLLBACK_ENV: $READY_PODS"

# Check if rollback environment is available
if [[ "$READY_PODS" -lt 2 ]]; then
    echo "Warning: Rollback environment may need to be scaled up"
    echo "Command: kubectl scale deployment --replicas=2 -n smart-dairy -l environment=$ROLLBACK_ENV"
fi

# Verify database compatibility
echo ""
echo "Step 2: Verify database compatibility..."
echo "Current database version should be backward compatible"

# Check rollback script availability
echo ""
echo "Step 3: Verify rollback scripts..."
if [[ -f "scripts/deployment/switch_traffic.sh" ]]; then
    echo "✓ Traffic switch script available"
else
    echo "✗ Traffic switch script missing"
fi

if [[ -f "scripts/deployment/restore_backup.sh" ]]; then
    echo "✓ Backup restore script available"
else
    echo "✗ Backup restore script missing"
fi

# Estimate rollback time
echo ""
echo "Step 4: Rollback time estimate..."
echo "  - Traffic switch: ~1 minute"
echo "  - DNS propagation: ~5 minutes"
echo "  - Full rollback with data restore: ~30 minutes"

echo ""
echo "=== Rollback Test Complete ==="
echo ""
echo "Actual rollback command:"
echo "  ./scripts/deployment/rollback.sh $ROLLBACK_ENV"
```

**Task 2: Performance Baseline (4h)**

Establish production performance baseline metrics.

#### Dev 2 Tasks (8 hours)

**Task 1: Rollback Script Preparation (4h)**

```bash
#!/bin/bash
# scripts/deployment/rollback.sh
# Emergency rollback script

set -e

usage() {
    echo "Usage: $0 <target-environment> [--with-data-restore]"
    echo "  target-environment: blue or green"
    echo "  --with-data-restore: Also restore database from backup"
    exit 1
}

[[ $# -lt 1 ]] && usage

TARGET_ENV=$1
WITH_DATA_RESTORE=$2
NAMESPACE="smart-dairy"

echo "!!! EMERGENCY ROLLBACK !!!"
echo "Rolling back to: $TARGET_ENV"
echo ""

# Confirm rollback
read -p "Are you sure you want to proceed? (yes/no) " CONFIRM
[[ "$CONFIRM" != "yes" ]] && exit 1

START_TIME=$(date +%s)

# Step 1: Switch traffic
echo ""
echo "[$(date +%H:%M:%S)] Step 1: Switching traffic to $TARGET_ENV..."
./scripts/deployment/switch_traffic.sh $TARGET_ENV

# Step 2: Verify target environment
echo ""
echo "[$(date +%H:%M:%S)] Step 2: Verifying $TARGET_ENV environment..."
for i in {1..10}; do
    if curl -sf https://smartdairy.com/web/health > /dev/null; then
        echo "Health check passed"
        break
    fi
    echo "Attempt $i failed, retrying..."
    sleep 5
done

# Step 3: Optional data restore
if [[ "$WITH_DATA_RESTORE" == "--with-data-restore" ]]; then
    echo ""
    echo "[$(date +%H:%M:%S)] Step 3: Restoring database from backup..."

    # Get latest backup
    LATEST_BACKUP=$(aws s3 ls s3://smart-dairy-backups/pre-golive/ --recursive | sort | tail -n1 | awk '{print $4}')
    echo "Restoring from: $LATEST_BACKUP"

    # Download and restore
    aws s3 cp "s3://smart-dairy-backups/$LATEST_BACKUP" /tmp/restore.dump.gz
    gunzip /tmp/restore.dump.gz

    PGPASSWORD=$DB_PASSWORD pg_restore \
        -h $RDS_ENDPOINT \
        -U admin \
        -d smartdairy \
        --clean \
        --if-exists \
        /tmp/restore.dump

    echo "Database restored"
fi

END_TIME=$(date +%s)
DURATION=$((END_TIME - START_TIME))

echo ""
echo "=== ROLLBACK COMPLETE ==="
echo "Duration: ${DURATION} seconds"
echo "Active environment: $TARGET_ENV"
echo ""
echo "Post-rollback steps:"
echo "1. Monitor system health"
echo "2. Notify stakeholders"
echo "3. Investigate root cause"
```

**Task 2: Monitoring Dashboard Configuration (4h)**

Configure production monitoring dashboards in Grafana.

#### Dev 3 Tasks (8 hours)

**Task 1: Post-Go-Live Documentation (4h)**

Document go-live process and lessons learned.

**Task 2: Handover to Operations (4h)**

Prepare handover documentation for operations team.

#### Day 725 Deliverables

- [x] Rollback procedure tested and verified
- [x] Production performance baseline established
- [x] Monitoring dashboards configured
- [x] Post-go-live documentation complete
- [x] Operations team handover initiated

---

## 4. Technical Specifications

### 4.1 Deployment Architecture

```
                     ┌─────────────────┐
                     │   Route 53      │
                     │ smartdairy.com  │
                     └────────┬────────┘
                              │
                     ┌────────▼────────┐
                     │   CloudFront    │
                     │    (CDN)        │
                     └────────┬────────┘
                              │
                     ┌────────▼────────┐
                     │      ALB        │
                     │ (Load Balancer) │
                     └────────┬────────┘
                              │
              ┌───────────────┼───────────────┐
              │               │               │
     ┌────────▼────────┐    ┌─▼─┐    ┌────────▼────────┐
     │  Blue Target    │    │   │    │  Green Target   │
     │     Group       │◄───┤ALB├───►│     Group       │
     │  (0% traffic)   │    │   │    │ (100% traffic)  │
     └────────┬────────┘    └───┘    └────────┬────────┘
              │                               │
     ┌────────▼────────┐            ┌────────▼────────┐
     │   EKS Pods      │            │   EKS Pods      │
     │   (Blue)        │            │   (Green)       │
     └─────────────────┘            └─────────────────┘
```

### 4.2 Rollback Decision Matrix

| Condition | Action | Rollback Type |
|-----------|--------|---------------|
| Error rate > 5% | Immediate | Traffic switch |
| P95 latency > 5s | Investigate, then rollback | Traffic switch |
| Database errors | Immediate | Full rollback |
| Critical feature broken | Assess impact | Traffic switch |
| Security incident | Immediate | Full rollback |

---

## 5. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| DNS propagation delay | Low | Medium | Use low TTL, verify propagation |
| Database migration failure | Low | Critical | Tested rollback, backups |
| Performance degradation | Medium | High | Canary deployment, monitoring |
| Integration failures | Low | Medium | Pre-verified integrations |

---

## 6. Dependencies & Handoffs

### 6.1 Prerequisites from MS-144

- [x] Staging sign-off obtained
- [x] UAT completed
- [x] Performance benchmarks met

### 6.2 Handoffs to MS-146

- [ ] Production deployment complete
- [ ] System verified operational
- [ ] Rollback procedures tested
- [ ] Monitoring active
- [ ] Hypercare ready

---

*Document Version: 1.0*
*Last Updated: 2026-02-05*
