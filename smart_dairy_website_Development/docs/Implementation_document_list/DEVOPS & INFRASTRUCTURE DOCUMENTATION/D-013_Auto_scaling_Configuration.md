# SMART DAIRY LTD.
## AUTO-SCALING CONFIGURATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Scaling Architecture](#2-scaling-architecture)
3. [Cluster Autoscaler](#3-cluster-autoscaler)
4. [Horizontal Pod Autoscaler](#4-horizontal-pod-autoscaler)
5. [Vertical Pod Autoscaler](#5-vertical-pod-autoscaler)
6. [KEDA for Event-driven Scaling](#6-keda-for-event-driven-scaling)
7. [Scaling Policies](#7-scaling-policies)
8. [Performance Testing](#8-performance-testing)
9. [Cost Considerations](#9-cost-considerations)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive configuration guidelines for implementing auto-scaling mechanisms for the Smart Dairy Smart Web Portal System and Integrated ERP running on Amazon EKS. It covers horizontal and vertical scaling strategies for Odoo, FastAPI, Celery workers, and supporting infrastructure components.

### 1.2 Horizontal vs Vertical Scaling

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SCALING STRATEGIES COMPARISON                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  HORIZONTAL SCALING (HPA)        vs      VERTICAL SCALING (VPA)            │
│  ═══════════════════════               ═══════════════════════              │
│                                                                             │
│  ┌─────────────────────┐               ┌─────────────────────┐             │
│  │  Add More Pods      │               │  Add More Resources │             │
│  │  ┌───┐ ┌───┐ ┌───┐ │               │  ┌───────────────┐  │             │
│  │  │Pod│ │Pod│ │Pod│ │               │  │   BIGGER POD  │  │             │
│  │  └───┘ └───┘ └───┘ │               │  │  ┌───┐        │  │             │
│  │  ┌───┐ ┌───┐       │               │  │  │CPU│ 4 Core │  │             │
│  │  │Pod│ │Pod│       │               │  │  └───┘        │  │             │
│  │  └───┘ └───┘       │               │  │  ┌────┐       │  │             │
│  │                     │               │  │  │MEM │ 8GB   │  │             │
│  │  Scale OUT/IN       │               │  │  └────┘       │  │             │
│  │  (More instances)   │               │  │               │  │             │
│  └─────────────────────┘               │  Scale UP/DOWN    │  │             │
│                                        │  (Bigger instance)│  │             │
│                                        └─────────────────────┘             │
│                                                                             │
│  Use Cases:                            Use Cases:                           │
│  • Web tier (Odoo/API)                 • Database pods                      │
│  • API requests handling               • Memory-intensive batch jobs        │
│  • Stateless microservices             • Stateful applications              │
│  • Queue workers (Celery)              • Initial resource right-sizing      │
│                                                                             │
│  Benefits:                             Benefits:                            │
│  • High availability                   • No pod churn                       │
│  • Better fault tolerance              • Simpler for stateful workloads     │
│  • Cost-effective (pay per use)        • Immediate resource adjustment      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.3 Scaling Objectives

| Objective | Target | Metric |
|-----------|--------|--------|
| **CPU Utilization** | 70% | Average across pods |
| **Web Tier Scaling** | 3-20 pods | Based on CPU + custom metrics |
| **Worker Scaling** | Queue-based | Redis queue depth |
| **Response Time** | <200ms p95 | API endpoints |
| **Scale-up Time** | <60 seconds | From trigger to new pods ready |
| **Scale-down Time** | <5 minutes | With stabilization window |

### 1.4 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| Kubernetes | EKS | 1.28+ |
| Metrics Server | Kubernetes Metrics Server | 0.6+ |
| Cluster Autoscaler | AWS Cluster Autoscaler | 1.28+ |
| HPA | Native Kubernetes HPA | v2 |
| VPA | Vertical Pod Autoscaler | 0.14+ |
| KEDA | Kubernetes Event-driven Autoscaling | 2.12+ |
| Monitoring | Prometheus + CloudWatch | 2.47+ |
| Custom Metrics | Prometheus Adapter | 0.11+ |

---

## 2. SCALING ARCHITECTURE

### 2.1 Auto-scaling Stack Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      SMART DAIRY AUTO-SCALING ARCHITECTURE                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌─────────────────────────────────────────────────────────────────┐      │
│   │                     EKS CONTROL PLANE                            │      │
│   │  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │      │
│   │  │ API Server  │  │   HPA       │  │   Cluster Autoscaler    │  │      │
│   │  │ Controller  │  │ Controller  │  │   Controller            │  │      │
│   │  └──────┬──────┘  └──────┬──────┘  └───────────┬─────────────┘  │      │
│   └─────────┼────────────────┼─────────────────────┼────────────────┘      │
│             │                │                     │                        │
│   ╔═════════╧════════════════╧═════════════════════╧═══════════════╗      │
│   ║              DECISION ENGINE (Scaling Decisions)                ║      │
│   ╚════════════════════════════════════════════════════════════════╝      │
│                             │                                              │
│   ┌─────────────────────────┴───────────────────────────────────────┐      │
│   │                     WORKER NODES (Managed Node Groups)           │      │
│   │                                                                  │      │
│   │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │      │
│   │  │  Web Tier Node  │  │  Worker Nodes   │  │  General Nodes  │  │      │
│   │  │  ┌───────────┐  │  │  ┌───────────┐  │  │  ┌───────────┐  │  │      │
│   │  │  │ Odoo Pod  │  │  │  │ Celery    │  │  │  │ FastAPI   │  │  │      │
│   │  │  │ HPA: 3-20 │  │  │  │ KEDA      │  │  │  │ HPA: 2-10 │  │  │      │
│   │  │  │ CPU: 70%  │  │  │  │ Queue     │  │  │  │ CPU: 70%  │  │  │      │
│   │  │  └───────────┘  │  │  │ Based     │  │  │  └───────────┘  │  │      │
│   │  │  ┌───────────┐  │  │  │ 2-50      │  │  │  ┌───────────┐  │  │      │
│   │  │  │ VPA       │  │  │  │ workers   │  │  │  │ Redis     │  │  │      │
│   │  │  │ Recommend │  │  │  └───────────┘  │  │  │ VPA       │  │  │      │
│   │  │  └───────────┘  │  └─────────────────┘  │  └───────────┘  │  │      │
│   │  └─────────────────┘                       └─────────────────┘  │      │
│   │                                                                  │      │
│   └──────────────────────────────────────────────────────────────────┘      │
│                             │                                              │
│   ┌─────────────────────────┴───────────────────────────────────────┐      │
│   │                      METRICS SOURCES                             │      │
│   │  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │      │
│   │  │ Metrics     │  │ Prometheus  │  │ CloudWatch              │  │      │
│   │  │ Server      │  │ Adapter     │  │ Container Insights      │  │
│   │  │ (CPU/Mem)   │  │ (Custom)    │  │ (AWS Metrics)           │  │      │
│   │  └─────────────┘  └─────────────┘  └─────────────────────────┘  │      │
│   └──────────────────────────────────────────────────────────────────┘      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Component Responsibilities

| Component | Scaling Type | Target Resource | Trigger Source |
|-----------|--------------|-----------------|----------------|
| **HPA** | Horizontal | Pods (Deployments) | CPU/Memory/Custom Metrics |
| **VPA** | Vertical | Pod Resources | Historical Usage |
| **Cluster Autoscaler** | Horizontal | EC2 Instances | Unschedulable Pods |
| **KEDA** | Event-driven | Pod Replicas | External events (queues) |

### 2.3 Scaling Decision Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     SCALING DECISION FLOW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   METRIC THRESHOLD BREACHED                                                 │
│         │                                                                   │
│         ▼                                                                   │
│   ┌─────────────┐                                                           │
│   │ HPA Check   │───────────────────────────────────────────────────┐       │
│   └──────┬──────┘                                                   │       │
│          │                                                          │       │
│          ▼                                                          │       │
│   ┌──────────────────┐     ┌──────────────────┐                     │       │
│   │ Pods Scaleable?  │────▶│  YES: Scale Pods │─────────────────────┤       │
│   │ (Within limits)  │     └──────────────────┘                     │       │
│   └────────┬─────────┘                                              │       │
│            │ NO                                                     │       │
│            ▼                                                        │       │
│   ┌──────────────────┐     ┌──────────────────┐                     │       │
│   │ Node Capacity?   │────▶│  NO: Trigger     │────▶┌────────────┐ │       │
│   │ (Room for pods)  │     │  Cluster Autoscaler  │ │  Scale     │ │       │
│   └──────────────────┘     └──────────────────┘     │  EC2 ASG   │ │       │
│                                                     └────────────┘ │       │
│                                                          │         │       │
│            ┌───────────────────────────────────────────────┘         │       │
│            ▼                                                        │       │
│   ┌──────────────────┐                                              │       │
│   │ New Node Ready   │──────────────────────────────────────────────┘       │
│   │ Pods Scheduled   │                                                      │
│   └──────────────────┘                                                      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. CLUSTER AUTOSCALER

### 3.1 AWS Auto Scaling Groups Configuration

```yaml
# Terraform: EKS Managed Node Groups with Auto Scaling
module "eks" {
  source  = "terraform-aws-modules/eks/aws"
  version = "19.21.0"

  cluster_name    = "smart-dairy-prod"
  cluster_version = "1.28"

  # ... other configuration ...

  eks_managed_node_groups = {
    web_tier = {
      name           = "web-tier-ng"
      instance_types = ["m6i.large", "m6i.xlarge"]
      capacity_type  = "ON_DEMAND"
      
      min_size     = 3
      max_size     = 20
      desired_size = 3

      labels = {
        tier = "web"
        workload = "odoo-api"
      }

      taints = []

      block_device_mappings = {
        xvda = {
          device_name = "/dev/xvda"
          ebs = {
            volume_size           = 100
            volume_type           = "gp3"
            iops                  = 3000
            encrypted             = true
            kms_key_id            = aws_kms_key.eks.arn
            delete_on_termination = true
          }
        }
      }
    }

    workers = {
      name           = "worker-ng"
      instance_types = ["c6i.xlarge", "c6i.2xlarge"]
      capacity_type  = "SPOT"  # Spot for cost savings on workers
      
      min_size     = 2
      max_size     = 15
      desired_size = 2

      labels = {
        tier = "workers"
        workload = "celery"
      }

      taints = [{
        key    = "dedicated"
        value  = "workers"
        effect = "NO_SCHEDULE"
      }]
    }

    general = {
      name           = "general-ng"
      instance_types = ["m6i.large", "m5.large", "m5a.large"]
      capacity_type  = "SPOT"
      
      min_size     = 2
      max_size     = 10
      desired_size = 2

      labels = {
        tier = "general"
      }
    }
  }
}
```

### 3.2 Cluster Autoscaler Deployment

```yaml
# cluster-autoscaler-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: cluster-autoscaler
  namespace: kube-system
  labels:
    app.kubernetes.io/name: cluster-autoscaler
spec:
  replicas: 1
  selector:
    matchLabels:
      app.kubernetes.io/name: cluster-autoscaler
  template:
    metadata:
      labels:
        app.kubernetes.io/name: cluster-autoscaler
    spec:
      priorityClassName: system-cluster-critical
      serviceAccountName: cluster-autoscaler
      containers:
        - name: cluster-autoscaler
          image: registry.k8s.io/autoscaling/cluster-autoscaler:v1.28.2
          imagePullPolicy: Always
          command:
            - ./cluster-autoscaler
            - --v=4
            - --stderrthreshold=info
            - --cloud-provider=aws
            - --skip-nodes-with-local-storage=false
            - --expander=least-waste
            - --node-group-auto-discovery=asg:tag=k8s.io/cluster-autoscaler/enabled,k8s.io/cluster-autoscaler/smart-dairy-prod
            - --balance-similar-node-groups=true
            - --skip-nodes-with-system-pods=false
            - --scale-down-delay-after-add=10m
            - --scale-down-delay-after-delete=1m
            - --scale-down-delay-after-failure=3m
            - --scale-down-unneeded-time=10m
            - --scale-down-unready-time=20m
            - --scan-interval=10s
            - --max-graceful-termination-sec=600
            - --max-node-provision-time=15m
          env:
            - name: AWS_REGION
              value: "ap-south-1"
          resources:
            requests:
              cpu: 100m
              memory: 300Mi
            limits:
              cpu: 1000m
              memory: 1000Mi
          volumeMounts:
            - name: ssl-certs
              mountPath: /etc/ssl/certs/ca-certificates.crt
              readOnly: true
      volumes:
        - name: ssl-certs
          hostPath:
            path: "/etc/ssl/certs/ca-bundle.crt"
```

### 3.3 Cluster Autoscaler IAM Policy

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "autoscaling:DescribeAutoScalingGroups",
        "autoscaling:DescribeAutoScalingInstances",
        "autoscaling:DescribeLaunchConfigurations",
        "autoscaling:DescribeScalingActivities",
        "autoscaling:SetDesiredCapacity",
        "autoscaling:TerminateInstanceInAutoScalingGroup",
        "ec2:DescribeImages",
        "ec2:GetInstanceTypesFromInstanceRequirements",
        "eks:DescribeNodegroup"
      ],
      "Resource": ["*"]
    }
  ]
}
```

### 3.4 Scale-up and Scale-down Triggers

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CLUSTER AUTOSCALER TRIGGERS                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  SCALE-UP TRIGGERS                                                          │
│  ═════════════════                                                          │
│                                                                             │
│  1. UNSCHEDULABLE PODS                                                       │
│     • Pod cannot be scheduled due to insufficient CPU/memory                │
│     • Node selector/affinity rules cannot be satisfied                      │
│     • Taints/tolerations mismatch                                           │
│                                                                             │
│  2. PENDING PODS TIMEOUT                                                     │
│     • Pods in Pending state > 30 seconds                                    │
│     • Required resources exceed any existing node capacity                  │
│                                                                             │
│  SCALE-DOWN TRIGGERS                                                        │
│  ═══════════════════                                                        │
│                                                                             │
│  1. NODE UNDERUTILIZATION                                                    │
│     • Node CPU < 50% for 10 minutes (--scale-down-unneeded-time)          │
│     • All pods can be evicted to other nodes                                │
│     • No system pods (except daemonsets) running                            │
│                                                                             │
│  2. SCALE-DOWN CONDITIONS                                                    │
│     • 10m after last scale-up (--scale-down-delay-after-add)               │
│     • 1m after node deletion (--scale-down-delay-after-delete)             │
│     • 3m after failed scale-down (--scale-down-delay-after-failure)        │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.5 Instance Type Configuration

| Node Group | Instance Types | Min | Max | Capacity Type |
|------------|----------------|-----|-----|---------------|
| **web-tier-ng** | m6i.large, m6i.xlarge | 3 | 20 | ON_DEMAND |
| **worker-ng** | c6i.xlarge, c6i.2xlarge | 2 | 15 | SPOT |
| **general-ng** | m6i.large, m5.large, m5a.large | 2 | 10 | SPOT |
| **database-ng** | r6i.xlarge, r6i.2xlarge | 2 | 4 | ON_DEMAND |

---

## 4. HORIZONTAL POD AUTOSCALER

### 4.1 Metrics Server Setup

```yaml
# metrics-server-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: metrics-server
  namespace: kube-system
spec:
  selector:
    matchLabels:
      k8s-app: metrics-server
  template:
    metadata:
      labels:
        k8s-app: metrics-server
    spec:
      serviceAccountName: metrics-server
      containers:
      - name: metrics-server
        image: registry.k8s.io/metrics-server/metrics-server:v0.6.4
        args:
          - --cert-dir=/tmp
          - --secure-port=10250
          - --kubelet-preferred-address-types=InternalIP,ExternalIP,Hostname
          - --kubelet-use-node-status-port
          - --metric-resolution=15s
          - --kubelet-insecure-tls  # For testing only; use proper certs in prod
        ports:
        - containerPort: 10250
          name: https
          protocol: TCP
        resources:
          requests:
            cpu: 100m
            memory: 200Mi
          limits:
            cpu: 200m
            memory: 400Mi
---
apiVersion: v1
kind: Service
metadata:
  name: metrics-server
  namespace: kube-system
spec:
  ports:
  - port: 443
    protocol: TCP
    targetPort: 10250
  selector:
    k8s-app: metrics-server
```

### 4.2 Prometheus Adapter for Custom Metrics

```yaml
# prometheus-adapter-values.yaml
prometheus:
  url: http://prometheus.monitoring.svc
  port: 9090

rules:
  default: false
  custom:
    # API Request Rate Metric
    - seriesQuery: 'http_requests_total{namespace!="",pod!=""}'
      resources:
        overrides:
          namespace:
            resource: namespace
          pod:
            resource: pod
      name:
        matches: "^(.*)_total"
        as: "${1}_per_second"
      metricsQuery: 'sum(rate(<<.Series>>{<<.LabelMatchers>>}[1m])) by (<<.GroupBy>>)'
    
    # Celery Queue Depth Metric
    - seriesQuery: 'celery_queue_length{namespace!="",queue!=""}'
      resources:
        overrides:
          namespace:
            resource: namespace
          queue:
            resource: configmap
      name:
        matches: "celery_queue_length"
        as: "celery_queue_depth"
      metricsQuery: 'max_over_time(<<.Series>>{<<.LabelMatchers>>}[5m])'
    
    # PostgreSQL Connection Count
    - seriesQuery: 'pg_stat_activity_count{namespace!=""}'
      resources:
        overrides:
          namespace:
            resource: namespace
      name:
        matches: "pg_stat_activity_count"
        as: "postgres_connections"
      metricsQuery: 'sum(<<.Series>>{<<.LabelMatchers>>}) by (<<.GroupBy>>)'
    
    # Odoo Active Sessions
    - seriesQuery: 'odoo_sessions_active{namespace!=""}'
      resources:
        overrides:
          namespace:
            resource: namespace
      name:
        matches: "odoo_sessions_active"
        as: "odoo_active_sessions"
      metricsQuery: 'sum(<<.Series>>{<<.LabelMatchers>>}) by (<<.GroupBy>>)'
```

### 4.3 Odoo Web Tier HPA

```yaml
# hpa-odoo-web.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-web-hpa
  namespace: smart-dairy
  labels:
    app: odoo
    tier: web
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-web
  minReplicas: 3
  maxReplicas: 20
  metrics:
    # Primary: CPU Utilization (Target: 70%)
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    
    # Secondary: Memory Utilization (Target: 80%)
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
    
    # Custom: HTTP Request Rate per Pod
    - type: Pods
      pods:
        metric:
          name: http_requests_per_second
        target:
          type: AverageValue
          averageValue: "100"
  
  behavior:
    # Scale-up: Fast response to load
    scaleUp:
      stabilizationWindowSeconds: 30
      policies:
        - type: Percent
          value: 100
          periodSeconds: 15
        - type: Pods
          value: 4
          periodSeconds: 15
      selectPolicy: Max
    
    # Scale-down: Conservative to avoid flapping
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
        - type: Percent
          value: 10
          periodSeconds: 60
        - type: Pods
          value: 2
          periodSeconds: 60
      selectPolicy: Min
```

### 4.4 FastAPI Application HPA

```yaml
# hpa-fastapi-api.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: fastapi-api-hpa
  namespace: smart-dairy
  labels:
    app: fastapi
    tier: api
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: fastapi-api
  minReplicas: 2
  maxReplicas: 10
  metrics:
    # Primary: CPU Utilization
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    
    # Custom: API Latency (p95 < 200ms)
    - type: Object
      object:
        metric:
          name: http_request_duration_seconds
        describedObject:
          apiVersion: networking.k8s.io/v1
          kind: Ingress
          name: fastapi-ingress
        target:
          type: Value
          value: "0.2"
  
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 15
      policies:
        - type: Percent
          value: 50
          periodSeconds: 15
        - type: Pods
          value: 2
          periodSeconds: 15
      selectPolicy: Max
    
    scaleDown:
      stabilizationWindowSeconds: 180
      policies:
        - type: Percent
          value: 25
          periodSeconds: 60
        - type: Pods
          value: 1
          periodSeconds: 60
      selectPolicy: Min
```

### 4.5 Worker HPA (Combined with KEDA)

```yaml
# hpa-celery-worker.yaml (Fallback for CPU-based scaling)
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: celery-worker-hpa
  namespace: smart-dairy
  labels:
    app: celery
    tier: workers
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: celery-worker
  minReplicas: 2
  maxReplicas: 15
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 75
    
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
  
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
        - type: Percent
          value: 50
          periodSeconds: 60
    
    scaleDown:
      stabilizationWindowSeconds: 600
      policies:
        - type: Percent
          value: 10
          periodSeconds: 120
```

---

## 5. VERTICAL POD AUTOSCALER

### 5.1 VPA Installation

```yaml
# vpa-install.yaml - Vertical Pod Autoscaler manifests
apiVersion: v1
kind: ServiceAccount
metadata:
  name: vpa-recommender
  namespace: kube-system
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: system:vpa-recommender
rules:
  - apiGroups: [""]
    resources: ["pods", "nodes", "limitranges"]
    verbs: ["get", "list", "watch"]
  - apiGroups: [""]
    resources: ["events"]
    verbs: ["create", "patch"]
  - apiGroups: ["autoscaling.k8s.io"]
    resources: ["verticalpodautoscalers"]
    verbs: ["get", "list", "watch"]
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: vpa-recommender
  namespace: kube-system
spec:
  replicas: 1
  selector:
    matchLabels:
      app: vpa-recommender
  template:
    metadata:
      labels:
        app: vpa-recommender
    spec:
      serviceAccountName: vpa-recommender
      containers:
        - name: recommender
          image: registry.k8s.io/autoscaling/vpa-recommender:0.14.0
          args:
            - --storage=prometheus
            - --prometheus-address=http://prometheus.monitoring.svc:9090
            - --prometheus-cadvisor-job-name=kubernetes-cadvisor
            - --v=4
          resources:
            requests:
              cpu: 50m
              memory: 500Mi
            limits:
              cpu: 200m
              memory: 1000Mi
---
# VPA Updater - handles auto-update mode
apiVersion: apps/v1
kind: Deployment
metadata:
  name: vpa-updater
  namespace: kube-system
spec:
  replicas: 1
  selector:
    matchLabels:
      app: vpa-updater
  template:
    metadata:
      labels:
        app: vpa-updater
    spec:
      serviceAccountName: vpa-updater
      containers:
        - name: updater
          image: registry.k8s.io/autoscaling/vpa-updater:0.14.0
          args:
            - --min-replicas=1
            - --eviction-tolerance=0.5
            - --v=4
          resources:
            requests:
              cpu: 50m
              memory: 100Mi
            limits:
              cpu: 200m
              memory: 300Mi
```

### 5.2 VPA Configuration for Odoo

```yaml
# vpa-odoo-web.yaml
apiVersion: autoscaling.k8s.io/v1
kind: VerticalPodAutoscaler
metadata:
  name: odoo-web-vpa
  namespace: smart-dairy
spec:
  targetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-web
  updatePolicy:
    # Auto: Automatically update pod resources (requires pod restart)
    # Off: Only provide recommendations
    # Initial: Only apply at pod creation
    updateMode: "Off"  # Start with Off, monitor recommendations, then switch to Auto
    
    # Minimum replicas to apply updates (prevents update loop with HPA)
    minReplicas: 1
    
    # Eviction requirements
    evictionRequirements:
      - resources: ["cpu", "memory"]
        changeRequirement: TargetHigherThanRequests
  
  resourcePolicy:
    containerPolicies:
      - containerName: odoo
        minAllowed:
          cpu: 250m
          memory: 512Mi
        maxAllowed:
          cpu: 2000m
          memory: 4Gi
        controlledResources: ["cpu", "memory"]
        controlledValues: RequestsAndLimits
---
# vpa-postgres.yaml
apiVersion: autoscaling.k8s.io/v1
kind: VerticalPodAutoscaler
metadata:
  name: postgres-vpa
  namespace: smart-dairy
spec:
  targetRef:
    apiVersion: apps/v1
    kind: StatefulSet
    name: postgres
  updatePolicy:
    updateMode: "Off"  # Recommendations only for databases
  resourcePolicy:
    containerPolicies:
      - containerName: postgres
        minAllowed:
          cpu: 500m
          memory: 1Gi
        maxAllowed:
          cpu: 4000m
          memory: 16Gi
        controlledResources: ["cpu", "memory"]
```

### 5.3 VPA Modes Explained

| Mode | Description | Use Case |
|------|-------------|----------|
| **Off** | Generates recommendations only | Production, validate recommendations first |
| **Initial** | Applies at pod creation only | Stateless applications |
| **Recreate** | Evicts and recreates pods with new resources | Tolerant to disruption |
| **Auto** | Automatically updates resources | Development/testing (not with HPA) |

### 5.4 VPA Recommendations

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    VPA RECOMMENDATION EXAMPLE                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  $ kubectl get vpa odoo-web-vpa -n smart-dairy -o yaml                      │
│                                                                             │
│  status:                                                                    │
│    recommendation:                                                          │
│      containerRecommendations:                                              │
│        - containerName: odoo                                                │
│          lowerBound:         # Minimum safe resources                       │
│            cpu: 350m                                                          │
│            memory: 768Mi                                                      │
│          target:             # Recommended resources                        │
│            cpu: 500m                                                          │
│            memory: 1Gi                                                        │
│          upperBound:         # Maximum recommended                          │
│            cpu: 1500m                                                         │
│            memory: 3Gi                                                        │
│          uncappedTarget:     # Without min/max constraints                  │
│            cpu: 650m                                                          │
│            memory: 1.2Gi                                                      │
│    conditions:                                                              │
│      - type: RecommendationProvided                                         │
│        status: "True"                                                       │
│      - type: LowConfidence                                                  │
│        status: "False"                                                      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 6. KEDA FOR EVENT-DRIVEN SCALING

### 6.1 KEDA Installation

```yaml
# keda-install.yaml - Using Helm values
# helm repo add kedacore https://kedacore.github.io/charts
# helm repo update
# helm install keda kedacore/keda --namespace keda --create-namespace

# keda-values.yaml
resources:
  operator:
    requests:
      cpu: 100m
      memory: 100Mi
    limits:
      cpu: 1000m
      memory: 1000Mi
  metricServer:
    requests:
      cpu: 100m
      memory: 100Mi
    limits:
      cpu: 1000m
      memory: 1000Mi

podIdentity:
  aws:
    provider: aws
    identityOwner: workload  # or operator

prometheus:
  metricServer:
    enabled: true
    port: 9022
    portName: metrics
    serviceMonitor:
      enabled: true
      namespace: monitoring
```

### 6.2 KEDA ScaledObject for Celery Workers

```yaml
# keda-celery-scaledobject.yaml
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: celery-worker-scaledobject
  namespace: smart-dairy
  labels:
    app: celery
    tier: workers
spec:
  scaleTargetRef:
    name: celery-worker
    kind: Deployment
    apiVersion: apps/v1
  
  pollingInterval: 10          # Check metrics every 10 seconds
  cooldownPeriod: 300          # Wait 5 min before scaling down
  minReplicaCount: 2           # Always keep 2 workers
  maxReplicaCount: 50          # Max 50 workers during peak
  advanced:
    restoreToOriginalReplicaCount: false
    horizontalPodAutoscalerConfig:
      name: celery-worker-keda-hpa
      behavior:
        scaleDown:
          stabilizationWindowSeconds: 300
          policies:
            - type: Percent
              value: 10
              periodSeconds: 60
        scaleUp:
          stabilizationWindowSeconds: 30
          policies:
            - type: Percent
              value: 100
              periodSeconds: 15
  
  triggers:
    # Primary: Redis Queue Depth
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        passwordFromEnv: REDIS_PASSWORD
        listName: celery
        listLength: "50"       # 1 pod per 50 queued items
        activationListLength: "10"  # Scale from 0 only when >10 items
      authenticationRef:
        name: keda-redis-trigger-auth
    
    # Secondary: Pending Task Age
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        passwordFromEnv: REDIS_PASSWORD
        listName: celery:queue:pending
        listLength: "100"
      authenticationRef:
        name: keda-redis-trigger-auth
    
    # CPU Trigger (Fallback)
    - type: cpu
      metricType: Utilization
      metadata:
        value: "75"
---
apiVersion: keda.sh/v1alpha1
kind: TriggerAuthentication
metadata:
  name: keda-redis-trigger-auth
  namespace: smart-dairy
spec:
  secretTargetRef:
    - parameter: password
      name: redis-secret
      key: password
```

### 6.3 KEDA ScaledObject for Specific Queue Types

```yaml
# keda-celery-high-priority-scaledobject.yaml
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: celery-high-priority-scaledobject
  namespace: smart-dairy
  labels:
    app: celery
    tier: workers
    priority: high
spec:
  scaleTargetRef:
    name: celery-worker-high-priority
  
  minReplicaCount: 1
  maxReplicaCount: 20
  pollingInterval: 5           # Faster polling for high priority
  cooldownPeriod: 60
  
  triggers:
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        listName: celery:queue:high
        listLength: "20"       # More aggressive scaling
        activationListLength: "1"
      authenticationRef:
        name: keda-redis-trigger-auth
---
# keda-celery-bulk-scaledobject.yaml
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: celery-bulk-scaledobject
  namespace: smart-dairy
  labels:
    app: celery
    tier: workers
    type: bulk
spec:
  scaleTargetRef:
    name: celery-worker-bulk
  
  minReplicaCount: 0           # Scale to zero when idle
  maxReplicaCount: 30
  
  triggers:
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        listName: celery:queue:bulk
        listLength: "200"      # Higher threshold for bulk jobs
        activationListLength: "50"
      authenticationRef:
        name: keda-redis-trigger-auth
```

### 6.4 KEDA ScaledJob for Batch Processing

```yaml
# keda-report-generation-scaledjob.yaml
apiVersion: keda.sh/v1alpha1
kind: ScaledJob
metadata:
  name: report-generation-scaledjob
  namespace: smart-dairy
spec:
  jobTargetRef:
    parallelism: 1
    completions: 1
    activeDeadlineSeconds: 3600
    template:
      metadata:
        labels:
          app: report-generator
      spec:
        containers:
          - name: report-generator
            image: smart-dairy/report-generator:v1.2.0
            env:
              - name: REPORT_ID
                valueFrom:
                  fieldRef:
                    fieldPath: metadata.annotations['report-id']
            resources:
              requests:
                cpu: 500m
                memory: 1Gi
              limits:
                cpu: 2000m
                memory: 4Gi
        restartPolicy: Never
  
  pollingInterval: 30
  successfulJobsHistoryLimit: 3
  failedJobsHistoryLimit: 3
  maxReplicaCount: 10
  
  triggers:
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        listName: celery:queue:reports
        listLength: "1"        # One job per queue item
      authenticationRef:
        name: keda-redis-trigger-auth
```

### 6.5 Queue Depth Metric Configuration

```python
# Celery queue monitoring exporter for Prometheus
# celery_metrics_exporter.py

from prometheus_client import Gauge, start_http_server
from celery import Celery
import time
import os

# Metrics
QUEUE_LENGTH = Gauge('celery_queue_length', 'Number of tasks in queue', ['queue'])
ACTIVE_WORKERS = Gauge('celery_active_workers', 'Number of active workers', ['queue'])
TASKS_PER_SECOND = Gauge('celery_tasks_per_second', 'Task processing rate', ['queue'])
PENDING_TASK_AGE = Gauge('celery_pending_task_age_seconds', 'Age of oldest pending task', ['queue'])

app = Celery('smart_dairy')
app.config_from_object({
    'broker_url': os.environ.get('REDIS_URL', 'redis://redis:6379/0'),
    'result_backend': os.environ.get('REDIS_URL', 'redis://redis:6379/0'),
})

def collect_metrics():
    """Collect Celery queue metrics."""
    inspector = app.control.inspect()
    
    # Get scheduled tasks
    scheduled = inspector.scheduled() or {}
    active = inspector.active() or {}
    reserved = inspector.reserved() or {}
    
    queues = ['celery', 'high', 'bulk', 'reports', 'emails']
    
    for queue in queues:
        # Queue length from Redis
        length = app.backend.client.llen(queue)
        QUEUE_LENGTH.labels(queue=queue).set(length)
        
        # Active workers for this queue
        active_count = sum(1 for worker_tasks in active.values() 
                          for task in worker_tasks if task.get('queue') == queue)
        ACTIVE_WORKERS.labels(queue=queue).set(active_count)

def main():
    start_http_server(8080)
    while True:
        collect_metrics()
        time.sleep(10)

if __name__ == '__main__':
    main()
```

---

## 7. SCALING POLICIES

### 7.1 Min/Max Replicas per Service

| Service | Min Replicas | Max Replicas | HPA Enabled | KEDA Enabled |
|---------|--------------|--------------|-------------|--------------|
| **odoo-web** | 3 | 20 | Yes | No |
| **odoo-longpolling** | 2 | 5 | Yes | No |
| **fastapi-api** | 2 | 10 | Yes | No |
| **celery-default** | 2 | 50 | No | Yes (Queue) |
| **celery-high-priority** | 1 | 20 | No | Yes (Queue) |
| **celery-bulk** | 0 | 30 | No | Yes (Queue) |
| **nginx-ingress** | 2 | 10 | Yes | No |
| **redis** | 1 | 1 | No | No (Stateful) |
| **postgres** | 2 | 2 | No | No (Stateful) |

### 7.2 Stabilization Windows

```yaml
# stabilization-policy-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: scaling-policies
  namespace: smart-dairy
data:
  policies.yaml: |
    services:
      odoo-web:
        scaleUp:
          stabilizationWindow: 30s
          policies:
            - type: Percent
              value: 100
              period: 15s
        scaleDown:
          stabilizationWindow: 300s
          policies:
            - type: Percent
              value: 10
              period: 60s
      
      celery-workers:
        scaleUp:
          stabilizationWindow: 15s  # Fast scale-up for queue bursts
          policies:
            - type: Percent
              value: 200
              period: 15s
        scaleDown:
          stabilizationWindow: 600s  # Long cooldown for workers
          policies:
            - type: Percent
              value: 5
              period: 120s
      
      fastapi-api:
        scaleUp:
          stabilizationWindow: 15s
          policies:
            - type: Pods
              value: 2
              period: 15s
        scaleDown:
          stabilizationWindow: 180s
          policies:
            - type: Pods
              value: 1
              period: 60s
```

### 7.3 Business Hours Scaling

```yaml
# keda-cron-scaledobject.yaml
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: odoo-business-hours-scaledobject
  namespace: smart-dairy
spec:
  scaleTargetRef:
    name: odoo-web
  
  minReplicaCount: 3
  maxReplicaCount: 20
  
  triggers:
    # Normal CPU-based scaling
    - type: cpu
      metricType: Utilization
      metadata:
        value: "70"
    
    # Business hours override (IST timezone)
    - type: cron
      metadata:
        timezone: Asia/Kolkata
        start: 0 8 * * 1-6    # 8 AM IST, Mon-Sat
        end: 0 20 * * 1-6     # 8 PM IST, Mon-Sat
        desiredReplicas: "5"  # Min 5 during business hours
    
    # Peak hours (higher minimum)
    - type: cron
      metadata:
        timezone: Asia/Kolkata
        start: 0 9 * * 1-6    # 9 AM IST
        end: 0 11 * * 1-6     # 11 AM IST
        desiredReplicas: "8"  # Min 8 during peak morning
    
    # Monthly closing override
    - type: cron
      metadata:
        timezone: Asia/Kolkata
        start: 0 0 1 * *      # 1st of every month
        end: 0 23 3 * *       # Until 3rd of every month
        desiredReplicas: "10" # Higher capacity for month-end
```

### 7.4 Scaling Policies Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SCALING POLICY DECISION MATRIX                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────┬─────────────────┬─────────────────┬─────────────────┐  │
│  │   Metric Type   │   Scale Up      │   Scale Down    │   Stabilization │  │
│  ├─────────────────┼─────────────────┼─────────────────┼─────────────────┤  │
│  │  CPU > 70%      │  +100% or +4    │  N/A            │  30s up /       │  │
│  │  (Web Tier)     │  every 15s      │                 │  5m down        │  │
│  ├─────────────────┼─────────────────┼─────────────────┼─────────────────┤  │
│  │  Queue Depth    │  +200% or       │  -10% every     │  15s up /       │  │
│  │  (Celery)       │  +10 pods/15s   │  2min           │  10m down       │  │
│  ├─────────────────┼─────────────────┼─────────────────┼─────────────────┤  │
│  │  Latency > 200ms│  +50% or +2     │  -25% every     │  15s up /       │  │
│  │  (API)          │  pods/15s       │  1min           │  3m down        │  │
│  ├─────────────────┼─────────────────┼─────────────────┼─────────────────┤  │
│  │  Memory > 80%   │  +50% every     │  -20% every     │  60s up /       │  │
│  │  (All Services) │  30s            │  2min           │  5m down        │  │
│  ├─────────────────┼─────────────────┼─────────────────┼─────────────────┤  │
│  │  Node Capacity  │  +1 node every  │  -1 node every  │  10m up /       │  │
│  │  (Cluster)      │  30s (max 15)   │  10min (when    │  10m down       │  │
│  │                 │                 │  unneeded)      │                 │  │
│  └─────────────────┴─────────────────┴─────────────────┴─────────────────┘  │
│                                                                             │
│  SCALE-DOWN PREVENTION CONDITIONS:                                          │
│  • Within 10 minutes of scale-up                                            │
│  • Any pod in the deployment is less than 5 minutes old                     │
│  • Cluster autoscaler has scaled a node in the last 10 minutes              │
│  • Any pod has eviction annotations preventing eviction                     │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. PERFORMANCE TESTING

### 8.1 Load Testing with k6

```javascript
// load-test-odoo.js - k6 load testing script
import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { Rate, Trend } from 'k6/metrics';

// Custom metrics
const apiLatency = new Trend('api_latency_p95');
const errorRate = new Rate('errors');

// Test configuration
export const options = {
  stages: [
    // Ramp up
    { duration: '2m', target: 100 },   // 100 users
    { duration: '2m', target: 500 },   // 500 users
    { duration: '2m', target: 1000 },  // 1000 users
    // Steady state
    { duration: '5m', target: 1000 },  // Sustained load
    // Spike test
    { duration: '1m', target: 2000 },  // Sudden spike
    { duration: '2m', target: 2000 },  // Sustained spike
    // Ramp down
    { duration: '3m', target: 100 },   // Reduce load
    { duration: '2m', target: 0 },     // Cool down
  ],
  thresholds: {
    http_req_duration: ['p(95)<200'],  // 95% of requests under 200ms
    http_req_failed: ['rate<0.01'],    // Error rate under 1%
    errors: ['rate<0.05'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'https://smartdairy.example.com';

export default function () {
  group('API Endpoints', () => {
    // Dashboard load
    const dashboardRes = http.get(`${BASE_URL}/web`, {
      tags: { endpoint: 'dashboard' },
    });
    check(dashboardRes, {
      'dashboard status is 200': (r) => r.status === 200,
      'dashboard loads in <500ms': (r) => r.timings.duration < 500,
    });
    apiLatency.add(dashboardRes.timings.duration);
    errorRate.add(dashboardRes.status !== 200);
    
    sleep(1);
    
    // API call
    const apiRes = http.get(`${BASE_URL}/api/v1/inventory/summary`, {
      headers: {
        'Authorization': `Bearer ${__ENV.API_TOKEN}`,
      },
      tags: { endpoint: 'inventory_summary' },
    });
    check(apiRes, {
      'API status is 200': (r) => r.status === 200,
      'API response <200ms': (r) => r.timings.duration < 200,
    });
    apiLatency.add(apiRes.timings.duration);
    errorRate.add(apiRes.status !== 200);
    
    sleep(Math.random() * 2 + 1);  // Random think time 1-3s
  });
}
```

### 8.2 HPA Validation Script

```bash
#!/bin/bash
# validate-hpa.sh - HPA validation script

NAMESPACE="smart-dairy"
DEPLOYMENT="odoo-web"
HPA_NAME="odoo-web-hpa"

echo "=== HPA Validation Test ==="
echo "Namespace: $NAMESPACE"
echo "Deployment: $DEPLOYMENT"
echo "HPA: $HPA_NAME"
echo ""

# Check current status
echo "1. Current HPA Status:"
kubectl get hpa $HPA_NAME -n $NAMESPACE -o wide

# Check current pod count
echo -e "\n2. Current Pod Count:"
kubectl get deployment $DEPLOYMENT -n $NAMESPACE -o jsonpath='{.spec.replicas}'
echo " replicas"

# Generate load
echo -e "\n3. Generating load with kubectl run..."
kubectl run -n $NAMESPACE load-generator \
  --image=williamyeh/wrk \
  --rm -it --restart=Never -- \
  -t12 -c400 -d300s \
  --latency \
  http://odoo-web/

# Monitor scaling
echo -e "\n4. Monitoring scaling behavior..."
for i in {1..30}; do
  echo "$(date '+%H:%M:%S') - Pods: $(kubectl get deployment $DEPLOYMENT -n $NAMESPACE -o jsonpath='{.status.readyReplicas}') / HPA Target: $(kubectl get hpa $HPA_NAME -n $NAMESPACE -o jsonpath='{.status.currentMetrics[0].resource.current.averageUtilization}')%"
  sleep 10
done

# Validate results
echo -e "\n5. Final HPA Status:"
kubectl get hpa $HPA_NAME -n $NAMESPACE -o yaml
echo "=== Test Complete ==="
```

### 8.3 Cluster Autoscaler Validation

```bash
#!/bin/bash
# validate-cluster-autoscaler.sh

echo "=== Cluster Autoscaler Validation ==="

# Check CA pod status
echo "1. Cluster Autoscaler Pod Status:"
kubectl get pods -n kube-system -l app.kubernetes.io/name=cluster-autoscaler

# Check CA logs
echo -e "\n2. Recent Scaling Events:"
kubectl logs -n kube-system -l app.kubernetes.io/name=cluster-autoscaler --tail=100 | grep -E "(Scale-up|Scale-down|unneeded)"

# Create pods that cannot be scheduled (trigger scale-up)
echo -e "\n3. Triggering scale-up with unschedulable pods..."
cat <<EOF | kubectl apply -f -
apiVersion: apps/v1
kind: Deployment
metadata:
  name: test-scale-trigger
  namespace: default
spec:
  replicas: 20
  selector:
    matchLabels:
      app: test-scale
  template:
    metadata:
      labels:
        app: test-scale
    spec:
      nodeSelector:
        test-node: "true"  # This label doesn't exist, pods will be unschedulable
      containers:
      - name: nginx
        image: nginx
        resources:
          requests:
            cpu: "2"  # High CPU request
            memory: "4Gi"
EOF

# Monitor for scale-up
echo -e "\n4. Monitoring for node scale-up..."
INITIAL_NODES=$(kubectl get nodes --no-headers | wc -l)
echo "Initial nodes: $INITIAL_NODES"

for i in {1..60}; do
  CURRENT_NODES=$(kubectl get nodes --no-headers | wc -l)
  echo "$(date '+%H:%M:%S') - Nodes: $CURRENT_NODES"
  if [ "$CURRENT_NODES" -gt "$INITIAL_NODES" ]; then
    echo "✓ Scale-up detected! New node added."
    break
  fi
  sleep 10
done

# Cleanup
echo -e "\n5. Cleaning up test deployment..."
kubectl delete deployment test-scale-trigger -n default

echo "=== Validation Complete ==="
```

### 8.4 Scaling Performance Metrics

```yaml
# scaling-performance-test.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: scaling-test-expectations
  namespace: smart-dairy
data:
  expectations.yaml: |
    tests:
      hpa_scale_up:
        description: "HPA scale-up from 3 to 10 pods"
        trigger: "CPU load > 70%"
        expected_duration: "< 60 seconds"
        max_time: 60s
        metrics:
          - pod_startup_time
          - hpa_decision_time
          - pod_ready_time
      
      hpa_scale_down:
        description: "HPA scale-down from 10 to 3 pods"
        trigger: "CPU load < 30%"
        expected_duration: "< 5 minutes"
        max_time: 300s
        metrics:
          - stabilization_window
          - pod_termination_grace_period
      
      cluster_scale_up:
        description: "Cluster scale-up for unschedulable pods"
        trigger: "Pending pods with resource requirements"
        expected_duration: "< 3 minutes"
        max_time: 180s
        metrics:
          - node_provision_time
          - pod_scheduling_time
          - node_ready_time
      
      queue_based_scaling:
        description: "KEDA scale-up based on queue depth"
        trigger: "Queue depth > 50 messages"
        expected_duration: "< 30 seconds"
        max_time: 30s
        metrics:
          - metric_collection_time
          - keda_decision_time
          - pod_creation_time
```

---

## 9. COST CONSIDERATIONS

### 9.1 Spot Instance Strategy

```yaml
# spot-instance-config.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: celery-worker-spot
  namespace: smart-dairy
spec:
  replicas: 3
  selector:
    matchLabels:
      app: celery-worker
      capacity: spot
  template:
    metadata:
      labels:
        app: celery-worker
        capacity: spot
    spec:
      affinity:
        nodeAffinity:
          requiredDuringSchedulingIgnoredDuringExecution:
            nodeSelectorTerms:
              - matchExpressions:
                  - key: eks.amazonaws.com/capacityType
                    operator: In
                    values:
                      - SPOT
      tolerations:
        - key: "spot"
          operator: "Equal"
          value: "true"
          effect: "NoSchedule"
      containers:
        - name: celery
          image: smart-dairy/celery-worker:v1.2.0
          resources:
            requests:
              cpu: 500m
              memory: 1Gi
      # Spot interruption handling
      terminationGracePeriodSeconds: 120
      # Priority class for eviction
      priorityClassName: spot-priority
---
apiVersion: scheduling.k8s.io/v1
kind: PriorityClass
metadata:
  name: spot-priority
value: -1  # Lower priority, evicted first if needed
preemptionPolicy: PreemptLowerPriority
description: "Priority class for spot instances - low priority for graceful eviction"
```

### 9.2 Savings Plans and Reserved Capacity

```yaml
# capacity-optimization.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: capacity-strategy
  namespace: kube-system
data:
  strategy: |
    # Smart Dairy Capacity Optimization Strategy
    
    ## Reserved Instances / Savings Plans (70% of baseline)
    reserved_capacity:
      instance_types:
        - m6i.large
        - m6i.xlarge
      coverage: 70%
      purchase_option: "compute_savings_plan"  # or "reserved_instances"
      term: "1_year"
      payment_option: "partial_upfront"
    
    ## Spot Instances (Variable workload)
    spot_capacity:
      instance_types:
        - c6i.xlarge
        - c6i.2xlarge
        - c5.xlarge  # Fallback
        - c5.2xlarge
      max_price: "0.15"  # 60% of on-demand
      diversification: true
      interruption_handling: "drain_and_replace"
      workloads:
        - celery-workers
        - report-generators
        - batch-jobs
    
    ## On-Demand (Critical workloads only)
    on_demand:
      instance_types:
        - m6i.xlarge
        - r6i.xlarge
      workloads:
        - odoo-web
        - postgres
        - redis
```

### 9.3 Cost Allocation and Monitoring

```yaml
# cost-allocation.yaml
apiVersion: v1
kind: ServiceAccount
metadata:
  name: kubecost
  namespace: monitoring
  annotations:
    eks.amazonaws.com/role-arn: arn:aws:iam::123456789012:role/kubecost-role
---
# Kubecost Helm values for cost monitoring
# kubecost-values.yaml
kubecostProductConfigs:
  clusterName: "smart-dairy-prod"
  currencyCode: "INR"
  awsServiceKeyName: ""
  awsServiceKeyPassword: ""
  
prometheus:
  server:
    resources:
      requests:
        cpu: 500m
        memory: 1Gi
      limits:
        cpu: 1000m
        memory: 2Gi

cost-analyzer:
  resources:
    requests:
      cpu: 200m
      memory: 500Mi
    limits:
      cpu: 1000m
      memory: 1Gi
  
  # Cost allocation tags
  sharedNamespaces:
    - kube-system
    - monitoring
    - ingress-nginx
  
  # Idle resource allocation
  idle: true
  
  # Right-sizing recommendations
  rightSizing: true
```

### 9.4 Scaling Efficiency Metrics

```python
# scaling_efficiency_calculator.py
"""Calculate scaling efficiency and cost impact."""

import json
from dataclasses import dataclass
from typing import List

@dataclass
class ScalingEvent:
    timestamp: str
    direction: str  # 'up' or 'down'
    replicas_before: int
    replicas_after: int
    reason: str
    duration_ms: int

@dataclass
class EfficiencyReport:
    total_scale_events: int
    successful_scale_events: int
    avg_scale_up_time_ms: float
    avg_scale_down_time_ms: float
    over_provision_ratio: float
    cost_efficiency_score: float

class ScalingEfficiencyAnalyzer:
    def __init__(self):
        self.events: List[ScalingEvent] = []
    
    def add_event(self, event: ScalingEvent):
        self.events.append(event)
    
    def generate_report(self) -> EfficiencyReport:
        scale_up_events = [e for e in self.events if e.direction == 'up']
        scale_down_events = [e for e in self.events if e.direction == 'down']
        
        avg_scale_up = sum(e.duration_ms for e in scale_up_events) / len(scale_up_events) if scale_up_events else 0
        avg_scale_down = sum(e.duration_ms for e in scale_down_events) / len(scale_down_events) if scale_down_events else 0
        
        # Calculate over-provisioning ratio (peak vs average)
        peak_replicas = max((e.replicas_after for e in self.events), default=0)
        avg_replicas = sum((e.replicas_after for e in self.events)) / len(self.events) if self.events else 0
        over_provision_ratio = (peak_replicas - avg_replicas) / avg_replicas if avg_replicas else 0
        
        # Cost efficiency score (0-100)
        # Higher score = better efficiency
        efficiency_score = 100 - (over_provision_ratio * 50) - (avg_scale_up / 1000)
        
        return EfficiencyReport(
            total_scale_events=len(self.events),
            successful_scale_events=len([e for e in self.events if e.duration_ms < 60000]),
            avg_scale_up_time_ms=avg_scale_up,
            avg_scale_down_time_ms=avg_scale_down,
            over_provision_ratio=over_provision_ratio,
            cost_efficiency_score=max(0, min(100, efficiency_score))
        )
    
    def export_metrics(self) -> dict:
        report = self.generate_report()
        return {
            "scaling_efficiency": {
                "total_events": report.total_scale_events,
                "success_rate": report.successful_scale_events / report.total_scale_events if report.total_scale_events else 0,
                "avg_scale_up_ms": report.avg_scale_up_time_ms,
                "avg_scale_down_ms": report.avg_scale_down_time_ms,
                "over_provision_ratio": report.over_provision_ratio,
                "efficiency_score": report.cost_efficiency_score
            }
        }

# Usage
analyzer = ScalingEfficiencyAnalyzer()
# Add events from Kubernetes events API or monitoring system
# analyzer.add_event(ScalingEvent(...))
# report = analyzer.generate_report()
# print(json.dumps(analyzer.export_metrics(), indent=2))
```

---

## 10. TROUBLESHOOTING

### 10.1 Failed Scaling Issues

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FAILED SCALING TROUBLESHOOTING                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  SYMPTOM: HPA shows "failed to get cpu utilization"                         │
│  ═══════════════════════════════════════════════════                        │
│  1. Check Metrics Server:                                                   │
│     kubectl get pods -n kube-system -l k8s-app=metrics-server               │
│                                                                             │
│  2. Verify metrics are available:                                           │
│     kubectl top nodes                                                       │
│     kubectl top pods -n smart-dairy                                         │
│                                                                             │
│  3. Check Metrics Server logs:                                              │
│     kubectl logs -n kube-system deployment/metrics-server                   │
│                                                                             │
│  4. Fix: Restart Metrics Server                                             │
│     kubectl rollout restart deployment/metrics-server -n kube-system        │
│                                                                             │
│  ────────────────────────────────────────────────────────────────────────   │
│                                                                             │
│  SYMPTOM: Pods stuck in Pending, no new nodes created                       │
│  ════════════════════════════════════════════════════════                   │
│  1. Check Cluster Autoscaler status:                                        │
│     kubectl get pods -n kube-system -l app.kubernetes.io/name=cluster-autoscaler  │
│                                                                             │
│  2. Check CA logs for scale-up errors:                                      │
│     kubectl logs -n kube-system -l app.kubernetes.io/name=cluster-autoscaler  │
│                                                                             │
│  3. Verify IAM permissions:                                                 │
│     • autoscaling:SetDesiredCapacity                                        │
│     • autoscaling:DescribeAutoScalingGroups                                 │
│                                                                             │
│  4. Check ASG limits:                                                       │
│     aws autoscaling describe-auto-scaling-groups --region ap-south-1        │
│                                                                             │
│  5. Check for taints/tolerations mismatch                                   │
│                                                                             │
│  ────────────────────────────────────────────────────────────────────────   │
│                                                                             │
│  SYMPTOM: KEDA not scaling based on queue depth                             │
│  ════════════════════════════════════════════════════════                   │
│  1. Check KEDA operator status:                                             │
│     kubectl get pods -n keda                                                │
│                                                                             │
│  2. Verify ScaledObject status:                                             │
│     kubectl get scaledobject celery-worker-scaledobject -n smart-dairy      │
│                                                                             │
│  3. Check trigger authentication:                                           │
│     kubectl get triggerauthentication keda-redis-trigger-auth -n smart-dairy│
│                                                                             │
│  4. Verify Redis connection:                                                │
│     kubectl exec -it redis-pod -- redis-cli LLEN celery                     │
│                                                                             │
│  5. Check KEDA metrics:                                                     │
│     kubectl get --raw "/apis/external.metrics.k8s.io/v1beta1"               │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 10.2 Metric Issues

```bash
#!/bin/bash
# diagnose-metric-issues.sh

NAMESPACE="smart-dairy"

echo "=== Metric Diagnostics ==="

# Check if metrics-server is running
echo -e "\n1. Metrics Server Status:"
if kubectl get deployment metrics-server -n kube-system > /dev/null 2>&1; then
    echo "✓ Metrics Server deployment exists"
    kubectl get deployment metrics-server -n kube-system -o wide
else
    echo "✗ Metrics Server deployment NOT found"
fi

# Check metrics API availability
echo -e "\n2. Metrics API Availability:"
kubectl get --raw "/apis/metrics.k8s.io/v1beta1" 2>/dev/null && echo "✓ Metrics API available" || echo "✗ Metrics API unavailable"

# Test pod metrics
echo -e "\n3. Pod Metrics (last 5):"
kubectl top pods -n $NAMESPACE --sort-by=cpu 2>/dev/null | head -6 || echo "✗ Cannot retrieve pod metrics"

# Check Prometheus Adapter
echo -e "\n4. Prometheus Adapter Status:"
if kubectl get deployment prometheus-adapter -n monitoring > /dev/null 2>&1; then
    echo "✓ Prometheus Adapter deployment exists"
    kubectl get pods -n monitoring -l app=prometheus-adapter
else
    echo "✗ Prometheus Adapter NOT found"
fi

# Check custom metrics
echo -e "\n5. Custom Metrics API:"
kubectl get --raw "/apis/custom.metrics.k8s.io/v1beta1" 2>/dev/null | jq '.resources[].name' 2>/dev/null | head -10 || echo "✗ Custom metrics unavailable"

# Check external metrics (KEDA)
echo -e "\n6. External Metrics API:"
kubectl get --raw "/apis/external.metrics.k8s.io/v1beta1" 2>/dev/null | jq '.' || echo "✗ External metrics unavailable"

# HPA status
echo -e "\n7. HPA Status:"
kubectl get hpa -n $NAMESPACE -o wide 2>/dev/null || echo "No HPA resources found"

# VPA status
echo -e "\n8. VPA Status:"
kubectl get vpa -n $NAMESPACE 2>/dev/null || echo "No VPA resources found"

# KEDA status
echo -e "\n9. KEDA ScaledObject Status:"
kubectl get scaledobject -n $NAMESPACE 2>/dev/null || echo "No ScaledObject resources found"

echo -e "\n=== Diagnostics Complete ==="
```

### 10.3 Cooldown Problems

```yaml
# troubleshoot-cooldown.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: cooldown-troubleshooting
  namespace: smart-dairy
data:
  guide: |
    # Cooldown Troubleshooting Guide
    
    ## Flapping Prevention
    
    ### Symptom: Rapid scale-up/scale-down cycles
    
    1. **Increase stabilization window:**
       ```yaml
       behavior:
         scaleDown:
           stabilizationWindowSeconds: 600  # Increase from 300
       ```
    
    2. **Add minimum replica change policy:**
       ```yaml
       policies:
         - type: Pods
           value: 2  # Minimum 2 pods change
           periodSeconds: 60
       ```
    
    3. **Implement scale-down delay:**
       ```bash
       # Add annotation to prevent scale-down
       kubectl annotate deployment app \
         cluster-autoscaler.kubernetes.io/scale-down-disabled="true"
       ```
    
    ## Stuck Scaling
    
    ### Symptom: Scale-up not triggering despite high load
    
    1. **Check metric collection interval:**
       ```bash
       kubectl get hpa app -o yaml | grep -A5 "currentMetrics"
       ```
    
    2. **Verify metrics are current:**
       ```bash
       kubectl describe hpa app | grep -A10 "Metrics"
       ```
    
    3. **Check for metric staleness:**
       - Metrics older than 5 minutes will not trigger scaling
       - Restart metrics-server if needed
    
    ## Delayed Scale-Down
    
    ### Symptom: Pods not scaling down after load drops
    
    1. **Check pod age:**
       ```bash
       kubectl get pods -o jsonpath='{range .items[*]}{.metadata.name}{"\t"}{.status.startTime}{"\n"}{end}'
       ```
    
    2. **Verify no PDB violations:**
       ```bash
       kubectl get pdb -n smart-dairy
       ```
    
    3. **Check for blocking finalizers:**
       ```bash
       kubectl get pods --field-selector=status.phase=Terminating
       ```
```

### 10.4 Common Commands

```bash
# HPA Commands
# ============

# Watch HPA in real-time
kubectl get hpa -n smart-dairy -w

# Get detailed HPA status
kubectl describe hpa odoo-web-hpa -n smart-dairy

# Check HPA events
kubectl get events -n smart-dairy --field-selector involvedObject.kind=HorizontalPodAutoscaler

# Edit HPA
kubectl edit hpa odoo-web-hpa -n smart-dairy

# Delete and recreate HPA
kubectl delete hpa odoo-web-hpa -n smart-dairy
kubectl apply -f hpa-odoo-web.yaml

# Cluster Autoscaler Commands
# ===========================

# View CA logs
kubectl logs -n kube-system -l app.kubernetes.io/name=cluster-autoscaler --tail=100 -f

# Check node status
kubectl top nodes
kubectl describe node <node-name>

# View node events
kubectl get events --field-selector involvedObject.kind=Node

# Force CA rescan
kubectl annotate deployment cluster-autoscaler -n kube-system \
  cluster-autoscaler.kubernetes.io/last-updated="$(date +%s)"

# KEDA Commands
# =============

# List scaled objects
kubectl get scaledobject -n smart-dairy

# Check scaled object status
kubectl describe scaledobject celery-worker-scaledobject -n smart-dairy

# Get KEDA operator logs
kubectl logs -n keda deployment/keda-operator

# Check external metrics
kubectl get --raw "/apis/external.metrics.k8s.io/v1beta1/namespaces/smart-dairy/redis-celery" | jq .

# VPA Commands
# ============

# Get VPA recommendations
kubectl get vpa -n smart-dairy -o yaml

# Check VPA recommender logs
kubectl logs -n kube-system deployment/vpa-recommender

# Update VPA mode
kubectl patch vpa odoo-web-vpa -n smart-dairy --type='merge' \
  -p='{"spec":{"updatePolicy":{"updateMode":"Auto"}}}'

# Metrics Commands
# ================

# Check metrics API
kubectl get --raw "/apis/metrics.k8s.io/v1beta1/pods"

# Get pod metrics
kubectl top pods -n smart-dairy --sort-by=cpu

# Get container-level metrics
kubectl top pod odoo-web-xxx -n smart-dairy --containers

# Prometheus query
kubectl exec -it prometheus-pod -n monitoring -- \
  wget -qO- 'http://localhost:9090/api/v1/query?query=rate(http_requests_total[5m])'
```

---

## 11. APPENDICES

### Appendix A: Complete HPA YAML Collection

```yaml
# A.1 All HPA Resources Combined
# ---
# File: all-hpa-resources.yaml
# Description: Complete HPA configurations for Smart Dairy
# ---

apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-web-hpa
  namespace: smart-dairy
  labels:
    app: odoo
    component: hpa
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-web
  minReplicas: 3
  maxReplicas: 20
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
    - type: Pods
      pods:
        metric:
          name: http_requests_per_second
        target:
          type: AverageValue
          averageValue: "100"
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 30
      policies:
        - type: Percent
          value: 100
          periodSeconds: 15
        - type: Pods
          value: 4
          periodSeconds: 15
      selectPolicy: Max
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
        - type: Percent
          value: 10
          periodSeconds: 60
        - type: Pods
          value: 2
          periodSeconds: 60
      selectPolicy: Min

---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-longpolling-hpa
  namespace: smart-dairy
  labels:
    app: odoo
    component: hpa
    type: longpolling
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-longpolling
  minReplicas: 2
  maxReplicas: 5
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
        - type: Pods
          value: 1
          periodSeconds: 30
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
        - type: Pods
          value: 1
          periodSeconds: 120

---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: fastapi-api-hpa
  namespace: smart-dairy
  labels:
    app: fastapi
    component: hpa
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: fastapi-api
  minReplicas: 2
  maxReplicas: 10
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 75
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 15
      policies:
        - type: Percent
          value: 50
          periodSeconds: 15
        - type: Pods
          value: 2
          periodSeconds: 15
      selectPolicy: Max
    scaleDown:
      stabilizationWindowSeconds: 180
      policies:
        - type: Percent
          value: 25
          periodSeconds: 60
        - type: Pods
          value: 1
          periodSeconds: 60
      selectPolicy: Min

---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: nginx-ingress-hpa
  namespace: ingress-nginx
  labels:
    app: nginx
    component: hpa
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: ingress-nginx-controller
  minReplicas: 2
  maxReplicas: 10
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
    - type: Pods
      pods:
        metric:
          name: nginx_connections_active
        target:
          type: AverageValue
          averageValue: "1000"
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 30
      policies:
        - type: Percent
          value: 100
          periodSeconds: 15
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
        - type: Percent
          value: 10
          periodSeconds: 60
```

### Appendix B: Metrics Server Setup

```yaml
# B.1 Complete Metrics Server Installation
# ---
# File: metrics-server-complete.yaml
# ---

apiVersion: v1
kind: ServiceAccount
metadata:
  name: metrics-server
  namespace: kube-system
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: system:aggregated-metrics-reader
  labels:
    rbac.authorization.k8s.io/aggregate-to-view: "true"
    rbac.authorization.k8s.io/aggregate-to-edit: "true"
    rbac.authorization.k8s.io/aggregate-to-admin: "true"
rules:
  - apiGroups: ["metrics.k8s.io"]
    resources: ["pods", "nodes"]
    verbs: ["get", "list", "watch"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: metrics-server:system:auth-delegator
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: ClusterRole
  name: system:auth-delegator
subjects:
  - kind: ServiceAccount
    name: metrics-server
    namespace: kube-system
---
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: metrics-server-auth-reader
  namespace: kube-system
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: Role
  name: extension-apiserver-authentication-reader
subjects:
  - kind: ServiceAccount
    name: metrics-server
    namespace: kube-system
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: system:metrics-server
rules:
  - apiGroups: [""]
    resources: ["pods", "nodes", "nodes/stats", "namespaces", "configmaps"]
    verbs: ["get", "list", "watch"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: system:metrics-server
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: ClusterRole
  name: system:metrics-server
subjects:
  - kind: ServiceAccount
    name: metrics-server
    namespace: kube-system
---
apiVersion: v1
kind: Service
metadata:
  name: metrics-server
  namespace: kube-system
  labels:
    kubernetes.io/name: "Metrics-server"
spec:
  selector:
    k8s-app: metrics-server
  ports:
    - port: 443
      protocol: TCP
      targetPort: https
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: metrics-server
  namespace: kube-system
  labels:
    k8s-app: metrics-server
spec:
  selector:
    matchLabels:
      k8s-app: metrics-server
  strategy:
    rollingUpdate:
      maxUnavailable: 0
  template:
    metadata:
      labels:
        k8s-app: metrics-server
    spec:
      serviceAccountName: metrics-server
      containers:
        - name: metrics-server
          image: registry.k8s.io/metrics-server/metrics-server:v0.6.4
          args:
            - --cert-dir=/tmp
            - --secure-port=10250
            - --kubelet-preferred-address-types=InternalIP,ExternalIP,Hostname
            - --kubelet-use-node-status-port
            - --metric-resolution=15s
            # For production, remove --kubelet-insecure-tls and use proper certs
            - --kubelet-insecure-tls
          ports:
            - containerPort: 10250
              name: https
              protocol: TCP
          livenessProbe:
            failureThreshold: 3
            httpGet:
              path: /livez
              port: https
              scheme: HTTPS
            periodSeconds: 10
          readinessProbe:
            failureThreshold: 3
            httpGet:
              path: /readyz
              port: https
              scheme: HTTPS
            periodSeconds: 10
          securityContext:
            readOnlyRootFilesystem: true
            runAsNonRoot: true
            runAsUser: 1000
          resources:
            requests:
              cpu: 100m
              memory: 200Mi
            limits:
              cpu: 200m
              memory: 400Mi
          volumeMounts:
            - name: tmp-dir
              mountPath: /tmp
      nodeSelector:
        kubernetes.io/os: linux
      priorityClassName: system-cluster-critical
      serviceAccountName: metrics-server
      volumes:
        - name: tmp-dir
          emptyDir: {}
---
apiVersion: apiregistration.k8s.io/v1
kind: APIService
metadata:
  name: v1beta1.metrics.k8s.io
  labels:
    k8s-app: metrics-server
spec:
  service:
    name: metrics-server
    namespace: kube-system
  group: metrics.k8s.io
  version: v1beta1
  insecureSkipTLSVerify: true
  groupPriorityMinimum: 100
  versionPriority: 100
```

### Appendix C: Cluster Autoscaler Complete Configuration

```yaml
# C.1 Cluster Autoscaler Full Configuration
# ---
# File: cluster-autoscaler-complete.yaml
# ---

apiVersion: v1
kind: ServiceAccount
metadata:
  name: cluster-autoscaler
  namespace: kube-system
  annotations:
    eks.amazonaws.com/role-arn: arn:aws:iam::123456789012:role/cluster-autoscaler-role
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: cluster-autoscaler
rules:
  - apiGroups: [""]
    resources: ["events", "endpoints"]
    verbs: ["create", "patch"]
  - apiGroups: [""]
    resources: ["pods/eviction"]
    verbs: ["create"]
  - apiGroups: [""]
    resources: ["pods/status"]
    verbs: ["update"]
  - apiGroups: [""]
    resources: ["endpoints"]
    resourceNames: ["cluster-autoscaler"]
    verbs: ["get", "update"]
  - apiGroups: [""]
    resources: ["nodes"]
    verbs: ["watch", "list", "get", "update"]
  - apiGroups: [""]
    resources: ["namespaces", "pods", "services", "replicationcontrollers", "persistentvolumeclaims", "persistentvolumes"]
    verbs: ["watch", "list", "get"]
  - apiGroups: ["extensions"]
    resources: ["replicasets", "daemonsets"]
    verbs: ["watch", "list", "get"]
  - apiGroups: ["policy"]
    resources: ["poddisruptionbudgets"]
    verbs: ["watch", "list"]
  - apiGroups: ["apps"]
    resources: ["statefulsets", "replicasets", "daemonsets"]
    verbs: ["watch", "list", "get"]
  - apiGroups: ["storage.k8s.io"]
    resources: ["storageclasses", "csinodes", "csidrivers", "csistoragecapacities"]
    verbs: ["watch", "list", "get"]
  - apiGroups: ["batch", "extensions"]
    resources: ["jobs"]
    verbs: ["get", "list", "watch", "patch"]
  - apiGroups: ["coordination.k8s.io"]
    resources: ["leases"]
    verbs: ["create"]
  - apiGroups: ["coordination.k8s.io"]
    resourceNames: ["cluster-autoscaler"]
    resources: ["leases"]
    verbs: ["get", "update"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  name: cluster-autoscaler
  namespace: kube-system
rules:
  - apiGroups: [""]
    resources: ["configmaps"]
    verbs: ["create", "list", "watch"]
  - apiGroups: [""]
    resources: ["configmaps"]
    resourceNames: ["cluster-autoscaler-status", "cluster-autoscaler-priority-expander"]
    verbs: ["delete", "get", "update", "watch"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: cluster-autoscaler
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: ClusterRole
  name: cluster-autoscaler
subjects:
  - kind: ServiceAccount
    name: cluster-autoscaler
    namespace: kube-system
---
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: cluster-autoscaler
  namespace: kube-system
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: Role
  name: cluster-autoscaler
subjects:
  - kind: ServiceAccount
    name: cluster-autoscaler
    namespace: kube-system
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: cluster-autoscaler
  namespace: kube-system
  labels:
    app.kubernetes.io/name: cluster-autoscaler
    app.kubernetes.io/version: 1.28.2
spec:
  replicas: 1
  selector:
    matchLabels:
      app.kubernetes.io/name: cluster-autoscaler
  template:
    metadata:
      labels:
        app.kubernetes.io/name: cluster-autoscaler
      annotations:
        prometheus.io/scrape: "true"
        prometheus.io/port: "8085"
    spec:
      priorityClassName: system-cluster-critical
      securityContext:
        runAsNonRoot: true
        runAsUser: 65534
        fsGroup: 65534
      serviceAccountName: cluster-autoscaler
      containers:
        - name: cluster-autoscaler
          image: registry.k8s.io/autoscaling/cluster-autoscaler:v1.28.2
          imagePullPolicy: Always
          command:
            - ./cluster-autoscaler
            - --v=4
            - --stderrthreshold=info
            - --cloud-provider=aws
            - --skip-nodes-with-local-storage=false
            - --expander=least-waste
            - --node-group-auto-discovery=asg:tag=k8s.io/cluster-autoscaler/enabled,k8s.io/cluster-autoscaler/smart-dairy-prod
            - --balance-similar-node-groups=true
            - --skip-nodes-with-system-pods=false
            # Scale-down configuration
            - --scale-down-delay-after-add=10m
            - --scale-down-delay-after-delete=1m
            - --scale-down-delay-after-failure=3m
            - --scale-down-unneeded-time=10m
            - --scale-down-unready-time=20m
            - --scale-down-utilization-threshold=0.5
            # Scanning intervals
            - --scan-interval=10s
            - --max-graceful-termination-sec=600
            - --max-node-provision-time=15m
            - --node-deletion-delay-timeout=2m
            # Resource limits for CA itself
            - --cores-total=8:1000
            - --memory-total=32:4000
          env:
            - name: AWS_REGION
              value: "ap-south-1"
          ports:
            - containerPort: 8085
              name: prometheus
              protocol: TCP
          resources:
            requests:
              cpu: 100m
              memory: 300Mi
            limits:
              cpu: 1000m
              memory: 1000Mi
          volumeMounts:
            - name: ssl-certs
              mountPath: /etc/ssl/certs/ca-certificates.crt
              readOnly: true
      volumes:
        - name: ssl-certs
          hostPath:
            path: "/etc/ssl/certs/ca-bundle.crt"
```

### Appendix D: KEDA Complete Configuration

```yaml
# D.1 KEDA Installation and Configuration
# ---
# File: keda-complete-config.yaml
# ---

# Trigger Authentication for Redis
apiVersion: keda.sh/v1alpha1
kind: TriggerAuthentication
metadata:
  name: keda-redis-trigger-auth
  namespace: smart-dairy
spec:
  secretTargetRef:
    - parameter: password
      name: redis-secret
      key: password
    - parameter: host
      name: redis-secret
      key: host
---
# Trigger Authentication for AWS CloudWatch
apiVersion: keda.sh/v1alpha1
kind: TriggerAuthentication
metadata:
  name: keda-aws-trigger-auth
  namespace: smart-dairy
spec:
  podIdentity:
    provider: aws
---
# ScaledObject for Celery Workers
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: celery-worker-scaledobject
  namespace: smart-dairy
  annotations:
    # Allow HPA to coexist
    scaledobject.keda.sh/name: celery-worker-hpa
spec:
  scaleTargetRef:
    name: celery-worker
  pollingInterval: 10
  cooldownPeriod: 300
  minReplicaCount: 2
  maxReplicaCount: 50
  advanced:
    restoreToOriginalReplicaCount: false
    horizontalPodAutoscalerConfig:
      name: celery-worker-keda-hpa
      behavior:
        scaleDown:
          stabilizationWindowSeconds: 300
          policies:
            - type: Percent
              value: 10
              periodSeconds: 60
        scaleUp:
          stabilizationWindowSeconds: 30
          policies:
            - type: Percent
              value: 100
              periodSeconds: 15
  triggers:
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        listName: celery
        listLength: "50"
        activationListLength: "10"
      authenticationRef:
        name: keda-redis-trigger-auth
---
# ScaledObject for Email Workers
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: celery-email-scaledobject
  namespace: smart-dairy
spec:
  scaleTargetRef:
    name: celery-email-worker
  minReplicaCount: 0
  maxReplicaCount: 20
  triggers:
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        listName: celery:queue:emails
        listLength: "10"
        activationListLength: "1"
      authenticationRef:
        name: keda-redis-trigger-auth
---
# ScaledObject for SQS-based processing
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: sqs-processor-scaledobject
  namespace: smart-dairy
spec:
  scaleTargetRef:
    name: sqs-processor
  minReplicaCount: 0
  maxReplicaCount: 30
  triggers:
    - type: aws-sqs-queue
      metadata:
        queueURL: https://sqs.ap-south-1.amazonaws.com/123456789012/smart-dairy-imports
        queueLength: "5"
        awsRegion: "ap-south-1"
      authenticationRef:
        name: keda-aws-trigger-auth
---
# ScaledObject for Kafka-based scaling
apiVersion: keda.sh/v1alpha1
kind: ScaledObject
metadata:
  name: kafka-consumer-scaledobject
  namespace: smart-dairy
spec:
  scaleTargetRef:
    name: kafka-consumer
  minReplicaCount: 2
  maxReplicaCount: 20
  triggers:
    - type: kafka
      metadata:
        bootstrapServers: kafka.smart-dairy.svc:9092
        consumerGroup: dairy-data-processors
        topic: dairy-events
        lagThreshold: "100"
        activationLagThreshold: "10"
---
# ScaledJob for Batch Report Generation
apiVersion: keda.sh/v1alpha1
kind: ScaledJob
metadata:
  name: report-generation-scaledjob
  namespace: smart-dairy
spec:
  jobTargetRef:
    parallelism: 1
    completions: 1
    activeDeadlineSeconds: 3600
    ttlSecondsAfterFinished: 300
    template:
      metadata:
        labels:
          app: report-generator
      spec:
        containers:
          - name: report-generator
            image: smart-dairy/report-generator:v1.2.0
            env:
              - name: REDIS_HOST
                value: redis.smart-dairy.svc
            resources:
              requests:
                cpu: 500m
                memory: 1Gi
              limits:
                cpu: 2000m
                memory: 4Gi
        restartPolicy: Never
  pollingInterval: 30
  successfulJobsHistoryLimit: 5
  failedJobsHistoryLimit: 5
  maxReplicaCount: 10
  triggers:
    - type: redis
      metadata:
        address: redis.smart-dairy.svc:6379
        listName: celery:queue:reports
        listLength: "1"
      authenticationRef:
        name: keda-redis-trigger-auth
```

### Appendix E: Quick Reference Commands

```bash
#!/bin/bash
# quick-reference.sh - Quick commands for auto-scaling operations

# =====================================================
# HPA OPERATIONS
# =====================================================

# Apply all HPA configurations
apply_hpa() {
    kubectl apply -f hpa-odoo-web.yaml
    kubectl apply -f hpa-fastapi-api.yaml
    kubectl apply -f hpa-nginx-ingress.yaml
    echo "HPA configurations applied"
}

# Watch HPA scaling in real-time
watch_hpa() {
    watch -n 2 'kubectl get hpa -n smart-dairy -o wide'
}

# Get HPA details
hpa_details() {
    kubectl describe hpa odoo-web-hpa -n smart-dairy
}

# =====================================================
# CLUSTER AUTOSCALER OPERATIONS
# =====================================================

# View CA logs
ca_logs() {
    kubectl logs -n kube-system -l app.kubernetes.io/name=cluster-autoscaler --tail=100 -f
}

# Check CA status
ca_status() {
    kubectl get pods -n kube-system -l app.kubernetes.io/name=cluster-autoscaler
}

# Get node utilization
node_utilization() {
    kubectl top nodes
    kubectl get nodes -o custom-columns=NAME:.metadata.name,STATUS:.status.conditions[-1].type,AGE:.metadata.creationTimestamp
}

# =====================================================
# KEDA OPERATIONS
# =====================================================

# Check KEDA status
keda_status() {
    kubectl get pods -n keda
    kubectl get scaledobject -n smart-dairy
}

# Get scaled object details
scaled_object_details() {
    kubectl describe scaledobject celery-worker-scaledobject -n smart-dairy
}

# Check external metrics
external_metrics() {
    kubectl get --raw "/apis/external.metrics.k8s.io/v1beta1" | jq .
}

# =====================================================
# VPA OPERATIONS
# =====================================================

# Get VPA recommendations
vpa_recommendations() {
    kubectl get vpa -n smart-dairy -o yaml | grep -A20 "recommendation:"
}

# Switch VPA mode
vpa_set_mode() {
    local mode=$1
    kubectl patch vpa odoo-web-vpa -n smart-dairy --type='merge' \
      -p="{\"spec\":{\"updatePolicy\":{\"updateMode\":\"${mode}\"}}}"
}

# =====================================================
# METRICS OPERATIONS
# =====================================================

# Check metrics API
metrics_api() {
    echo "=== Metrics Server ==="
    kubectl top nodes 2>/dev/null || echo "Metrics Server not available"
    echo -e "\n=== Custom Metrics ==="
    kubectl get --raw "/apis/custom.metrics.k8s.io/v1beta1" 2>/dev/null | jq '.resources[].name' 2>/dev/null | head -5 || echo "Custom metrics unavailable"
    echo -e "\n=== External Metrics ==="
    kubectl get --raw "/apis/external.metrics.k8s.io/v1beta1" 2>/dev/null | jq '.resources[].name' 2>/dev/null | head -5 || echo "External metrics unavailable"
}

# =====================================================
# TESTING OPERATIONS
# =====================================================

# Generate load test
generate_load() {
    local target=${1:-http://odoo-web.smart-dairy.svc}
    local duration=${2:-60s}
    kubectl run -n smart-dairy load-generator \
      --image=williamyeh/wrk \
      --rm -it --restart=Never -- \
      -t12 -c400 -d${duration} --latency ${target}
}

# Check queue depth
check_queue() {
    kubectl exec -it redis-0 -n smart-dairy -- redis-cli LLEN celery
}

# =====================================================
# MENU
# =====================================================

case "$1" in
    hpa|apply-hpa) apply_hpa ;;
    watch) watch_hpa ;;
    hpa-details) hpa_details ;;
    ca-logs) ca_logs ;;
    ca-status) ca_status ;;
    nodes) node_utilization ;;
    keda) keda_status ;;
    keda-details) scaled_object_details ;;
    metrics) metrics_api ;;
    vpa) vpa_recommendations ;;
    vpa-mode) vpa_set_mode "$2" ;;
    load) generate_load "$2" "$3" ;;
    queue) check_queue ;;
    *) echo "Usage: $0 {hpa|watch|ca-logs|keda|metrics|vpa|load|queue}"
esac
```

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | DevOps Engineer | Initial document creation |

---

**Approval**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | DevOps Engineer | _________________ | _______ |
| Owner | DevOps Lead | _________________ | _______ |
| Reviewer | CTO | _________________ | _______ |

---

*End of Document D-013*
