# SMART DAIRY LTD.
## KUBERNETES DEPLOYMENT GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-004 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Lead |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Kubernetes Architecture](#2-kubernetes-architecture)
3. [Cluster Setup](#3-cluster-setup)
4. [Application Deployment](#4-application-deployment)
5. [Configuration Management](#5-configuration-management)
6. [Networking & Ingress](#6-networking--ingress)
7. [Storage Configuration](#7-storage-configuration)
8. [Security & RBAC](#8-security--rbac)
9. [Monitoring & Logging](#9-monitoring--logging)
10. [Backup & Disaster Recovery](#10-backup--disaster-recovery)
11. [Operations Runbook](#11-operations-runbook)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive guidelines for deploying the Smart Dairy Smart Web Portal System and Integrated ERP on Kubernetes. It covers cluster architecture, application deployment patterns, operational procedures, and best practices for production-grade Kubernetes deployments.

### 1.2 Scope

- AWS EKS (Elastic Kubernetes Service) setup
- Application containerization and deployment
- Helm chart development and management
- Service mesh and networking
- Security policies and RBAC
- Monitoring, logging, and alerting
- Backup and disaster recovery procedures

### 1.3 Prerequisites

| Requirement | Version | Purpose |
|-------------|---------|---------|
| kubectl | 1.28+ | Kubernetes CLI |
| Helm | 3.13+ | Package management |
| AWS CLI | 2.13+ | AWS integration |
| eksctl | 0.160+ | EKS cluster management |
| Docker | 24.0+ | Container building |

---

## 2. KUBERNETES ARCHITECTURE

### 2.1 Cluster Topology

```
┌─────────────────────────────────────────────────────────────────┐
│                    AWS EKS CLUSTER ARCHITECTURE                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │                     CONTROL PLANE (EKS Managed)              ││
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐           ││
│  │  │ API Server  │ │   etcd      │ │ Controller  │           ││
│  │  │ (HA)        │ │ (HA)        │ │ Manager     │           ││
│  │  └─────────────┘ └─────────────┘ └─────────────┘           ││
│  │  ┌─────────────┐                                            ││
│  │  │ Scheduler   │                                            ││
│  │  └─────────────┘                                            ││
│  └─────────────────────────────────────────────────────────────┘│
│                              │                                   │
│                              ▼                                   │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │                    WORKER NODES (EC2)                        ││
│  │                                                              ││
│  │  ┌─────────────────────────────────────────────────────────┐││
│  │  │                 NODE GROUP: Odoo (3-10 nodes)            │││
│  │  │  Instance: c6i.2xlarge (8 vCPU, 16GB RAM)               │││
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐                │││
│  │  │  │ Odoo Pod │ │ Odoo Pod │ │ Celery   │                │││
│  │  │  │ (Web)    │ │ (Web)    │ │ Worker   │                │││
│  │  │  └──────────┘ └──────────┘ └──────────┘                │││
│  │  └─────────────────────────────────────────────────────────┘││
│  │                                                              ││
│  │  ┌─────────────────────────────────────────────────────────┐││
│  │  │              NODE GROUP: API (2-5 nodes)                 │││
│  │  │  Instance: c6i.xlarge (4 vCPU, 8GB RAM)                 │││
│  │  │  ┌──────────┐ ┌──────────┐                             │││
│  │  │  │ FastAPI  │ │ FastAPI  │                             │││
│  │  │  │ Pod      │ │ Pod      │                             │││
│  │  │  └──────────┘ └──────────┘                             │││
│  │  └─────────────────────────────────────────────────────────┘││
│  │                                                              ││
│  │  ┌─────────────────────────────────────────────────────────┐││
│  │  │              NODE GROUP: System (2 nodes)                │││
│  │  │  Instance: c6i.large (2 vCPU, 4GB RAM)                  │││
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐                │││
│  │  │  │ Ingress  │ │Monitoring│ │ Logging  │                │││
│  │  │  │Controller│ │ Stack    │ │ Stack    │                │││
│  │  │  └──────────┘ └──────────┘ └──────────┘                │││
│  │  └─────────────────────────────────────────────────────────┘││
│  │                                                              ││
│  └─────────────────────────────────────────────────────────────┘│
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Namespace Structure

```yaml
# Namespace organization
namespaces:
  # Environment isolation
  - production
  - staging
  - development
  
  # System namespaces
  - kube-system          # Kubernetes system components
  - ingress-nginx        # Ingress controller
  - cert-manager         # TLS certificate management
  - monitoring           # Prometheus, Grafana
  - logging              # ELK / Loki stack
  - argocd              # GitOps deployment
  - vault               # Secrets management
```

---

## 3. CLUSTER SETUP

### 3.1 EKS Cluster Creation

```bash
#!/bin/bash
# scripts/create-eks-cluster.sh

CLUSTER_NAME="smart-dairy-prod"
REGION="ap-south-1"
KUBERNETES_VERSION="1.28"

# Create EKS cluster using eksctl
eksctl create cluster \
  --name $CLUSTER_NAME \
  --region $REGION \
  --version $KUBERNETES_VERSION \
  --vpc-private-subnets subnet-0abc123,subnet-0def456 \
  --vpc-public-subnets subnet-0ghi789,subnet-0jkl012 \
  --managed \
  --node-type c6i.2xlarge \
  --nodes 3 \
  --nodes-min 3 \
  --nodes-max 10 \
  --node-volume-size 100 \
  --node-volume-type gp3 \
  --ssh-access \
  --ssh-public-key smart-dairy-eks-key \
  --tags "Project=SmartDairy,Environment=Production" \
  --asg-access \
  --external-dns-access \
  --full-ecr-access

# Enable addons
eksctl utils update-kube-proxy --cluster $CLUSTER_NAME --approve
eksctl utils update-aws-node --cluster $CLUSTER_NAME --approve
eksctl utils update-coredns --cluster $CLUSTER_NAME --approve
```

### 3.2 Node Groups Configuration

```yaml
# eksctl nodegroup configuration
apiVersion: eksctl.io/v1alpha5
kind: ClusterConfig

metadata:
  name: smart-dairy-prod
  region: ap-south-1
  version: "1.28"

managedNodeGroups:
  # Odoo Application Nodes
  - name: odoo-ng
    instanceType: c6i.2xlarge
    desiredCapacity: 3
    minSize: 3
    maxSize: 10
    volumeSize: 100
    volumeType: gp3
    privateNetworking: true
    labels:
      app: odoo
      node-type: application
    taints:
      - key: dedicated
        value: odoo
        effect: NoSchedule
    iam:
      withAddonPolicies:
        autoScaler: true
        cloudWatch: true
        ebs: true
        efs: true
    tags:
      nodegroup-type: odoo

  # API Gateway Nodes
  - name: api-ng
    instanceType: c6i.xlarge
    desiredCapacity: 2
    minSize: 2
    maxSize: 5
    volumeSize: 50
    privateNetworking: true
    labels:
      app: fastapi
      node-type: api
    iam:
      withAddonPolicies:
        autoScaler: true
        cloudWatch: true

  # System Nodes (Monitoring, Ingress)
  - name: system-ng
    instanceType: c6i.large
    desiredCapacity: 2
    minSize: 2
    maxSize: 4
    volumeSize: 50
    privateNetworking: false
    labels:
      node-type: system
```

---

## 4. APPLICATION DEPLOYMENT

### 4.1 Helm Chart Structure

```
helm/
└── smart-dairy/
    ├── Chart.yaml
    ├── values.yaml
    ├── values-production.yaml
    ├── values-staging.yaml
    └── templates/
        ├── _helpers.tpl
        ├── namespace.yaml
        ├── configmap.yaml
        ├── secret.yaml
        ├── odoo/
        │   ├── deployment.yaml
        │   ├── service.yaml
        │   ├── hpa.yaml
        │   └── pdb.yaml
        ├── fastapi/
        │   ├── deployment.yaml
        │   ├── service.yaml
        │   ├── hpa.yaml
        │   └── pdb.yaml
        ├── celery/
        │   ├── deployment.yaml
        │   └── hpa.yaml
        ├── ingress/
        │   ├── ingress.yaml
        │   └── certificate.yaml
        └── monitoring/
            ├── servicemonitor.yaml
            └── alerts.yaml
```

### 4.2 Odoo Deployment

```yaml
# templates/odoo/deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: {{ include "smart-dairy.fullname" . }}-odoo
  labels:
    app: {{ include "smart-dairy.name" . }}
    component: odoo
spec:
  replicas: {{ .Values.odoo.replicaCount }}
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 25%
      maxUnavailable: 25%
  selector:
    matchLabels:
      app: {{ include "smart-dairy.name" . }}
      component: odoo
  template:
    metadata:
      labels:
        app: {{ include "smart-dairy.name" . }}
        component: odoo
      annotations:
        prometheus.io/scrape: "true"
        prometheus.io/port: "8069"
        prometheus.io/path: "/metrics"
    spec:
      affinity:
        podAntiAffinity:
          preferredDuringSchedulingIgnoredDuringExecution:
            - weight: 100
              podAffinityTerm:
                labelSelector:
                  matchExpressions:
                    - key: component
                      operator: In
                      values:
                        - odoo
                topologyKey: kubernetes.io/hostname
      nodeSelector:
        app: odoo
      tolerations:
        - key: "dedicated"
          operator: "Equal"
          value: "odoo"
          effect: "NoSchedule"
      containers:
        - name: odoo
          image: "{{ .Values.odoo.image.repository }}:{{ .Values.odoo.image.tag }}"
          imagePullPolicy: {{ .Values.odoo.image.pullPolicy }}
          ports:
            - name: http
              containerPort: 8069
              protocol: TCP
            - name: longpoll
              containerPort: 8072
              protocol: TCP
          env:
            - name: HOST
              value: {{ .Values.database.host | quote }}
            - name: PORT
              value: {{ .Values.database.port | quote }}
            - name: USER
              valueFrom:
                secretKeyRef:
                  name: {{ .Values.database.secretName }}
                  key: username
            - name: PASSWORD
              valueFrom:
                secretKeyRef:
                  name: {{ .Values.database.secretName }}
                  key: password
            - name: REDIS_HOST
              value: {{ .Values.redis.host | quote }}
          resources:
            limits:
              cpu: "2"
              memory: "4Gi"
            requests:
              cpu: "1"
              memory: "2Gi"
          livenessProbe:
            httpGet:
              path: /web/health
              port: http
            initialDelaySeconds: 60
            periodSeconds: 30
            timeoutSeconds: 10
            failureThreshold: 3
          readinessProbe:
            httpGet:
              path: /web/health
              port: http
            initialDelaySeconds: 30
            periodSeconds: 10
            timeoutSeconds: 5
            failureThreshold: 3
          volumeMounts:
            - name: odoo-data
              mountPath: /var/lib/odoo
            - name: odoo-sessions
              mountPath: /var/lib/odoo/sessions
            - name: odoo-config
              mountPath: /etc/odoo
              readOnly: true
      volumes:
        - name: odoo-data
          persistentVolumeClaim:
            claimName: {{ include "smart-dairy.fullname" . }}-odoo-data
        - name: odoo-sessions
          emptyDir: {}
        - name: odoo-config
          configMap:
            name: {{ include "smart-dairy.fullname" . }}-odoo-config
```

### 4.3 Horizontal Pod Autoscaling

```yaml
# templates/odoo/hpa.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: {{ include "smart-dairy.fullname" . }}-odoo
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: {{ include "smart-dairy.fullname" . }}-odoo
  minReplicas: {{ .Values.odoo.autoscaling.minReplicas }}
  maxReplicas: {{ .Values.odoo.autoscaling.maxReplicas }}
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
  behavior:
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
        - type: Percent
          value: 10
          periodSeconds: 60
    scaleUp:
      stabilizationWindowSeconds: 0
      policies:
        - type: Percent
          value: 100
          periodSeconds: 15
        - type: Pods
          value: 4
          periodSeconds: 15
      selectPolicy: Max
```

---

## 5. CONFIGURATION MANAGEMENT

### 5.1 ConfigMaps

```yaml
# templates/configmap.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: {{ include "smart-dairy.fullname" . }}-odoo-config
data:
  odoo.conf: |
    [options]
    addons_path = /mnt/extra-addons
    data_dir = /var/lib/odoo
    ; Logging
    logfile = /var/log/odoo/odoo.log
    log_level = info
    ; Performance
    workers = 4
    max_cron_threads = 2
    ; Session
    session_gc = 100000
    ; Database
    db_host = {{ .Values.database.host }}
    db_port = {{ .Values.database.port }}
    db_name = {{ .Values.database.name }}
    ; Security
    list_db = False
    proxy_mode = True
```

### 5.2 Secrets Management

```yaml
# Using External Secrets Operator with AWS Secrets Manager
apiVersion: external-secrets.io/v1beta1
kind: ExternalSecret
metadata:
  name: smart-dairy-db-credentials
spec:
  refreshInterval: 1h
  secretStoreRef:
    kind: ClusterSecretStore
    name: aws-secrets-manager
  target:
    name: smart-dairy-db-credentials
    creationPolicy: Owner
  data:
    - secretKey: username
      remoteRef:
        key: production/smart-dairy/database
        property: username
    - secretKey: password
      remoteRef:
        key: production/smart-dairy/database
        property: password
```

---

## 6. NETWORKING & INGRESS

### 6.1 Ingress Configuration

```yaml
# templates/ingress/ingress.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: {{ include "smart-dairy.fullname" . }}
  annotations:
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
    nginx.ingress.kubernetes.io/proxy-body-size: "100m"
    nginx.ingress.kubernetes.io/proxy-read-timeout: "600"
    nginx.ingress.kubernetes.io/proxy-send-timeout: "600"
    nginx.ingress.kubernetes.io/enable-cors: "true"
    nginx.ingress.kubernetes.io/cors-allow-origin: "https://smartdairybd.com"
    cert-manager.io/cluster-issuer: "letsencrypt-prod"
    nginx.ingress.kubernetes.io/rate-limit: "100"
spec:
  ingressClassName: nginx
  tls:
    - hosts:
        - smartdairybd.com
        - www.smartdairybd.com
        - api.smartdairybd.com
      secretName: smartdairy-tls
  rules:
    - host: smartdairybd.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: {{ include "smart-dairy.fullname" . }}-odoo
                port:
                  number: 8069
    - host: api.smartdairybd.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: {{ include "smart-dairy.fullname" . }}-fastapi
                port:
                  number: 8000
```

### 6.2 Service Mesh (Istio)

```yaml
# Istio VirtualService for traffic management
apiVersion: networking.istio.io/v1beta1
kind: VirtualService
metadata:
  name: smart-dairy-odoo
spec:
  hosts:
    - smartdairybd.com
  gateways:
    - smart-dairy-gateway
  http:
    - match:
        - uri:
            prefix: /api/
      route:
        - destination:
            host: smart-dairy-fastapi
            port:
              number: 8000
    - route:
        - destination:
            host: smart-dairy-odoo
            port:
              number: 8069
      retries:
        attempts: 3
        perTryTimeout: 5s
        retryOn: 5xx,gateway-error
```

---

## 7. STORAGE CONFIGURATION

### 7.1 Persistent Volumes

```yaml
# PVC for Odoo filestore
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: smart-dairy-odoo-data
spec:
  accessModes:
    - ReadWriteMany
  storageClassName: efs-sc
  resources:
    requests:
      storage: 100Gi

---
# Storage Class for EFS
apiVersion: storage.k8s.io/v1
kind: StorageClass
metadata:
  name: efs-sc
provisioner: efs.csi.aws.com
parameters:
  provisioningMode: efs-ap
  fileSystemId: fs-0abc123def456
  directoryPerms: "700"
```

---

## 8. SECURITY & RBAC

### 8.1 RBAC Configuration

```yaml
# Service Account for Odoo
apiVersion: v1
kind: ServiceAccount
metadata:
  name: smart-dairy-odoo
  namespace: production
automountServiceAccountToken: false

---
# Role for ConfigMap access
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  name: odoo-config-reader
  namespace: production
rules:
  - apiGroups: [""]
    resources: ["configmaps"]
    resourceNames: ["smart-dairy-odoo-config"]
    verbs: ["get", "list"]

---
# RoleBinding
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: odoo-config-reader-binding
  namespace: production
subjects:
  - kind: ServiceAccount
    name: smart-dairy-odoo
    namespace: production
roleRef:
  kind: Role
  name: odoo-config-reader
  apiGroup: rbac.authorization.k8s.io
```

### 8.2 Network Policies

```yaml
# Restrict traffic between pods
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: odoo-network-policy
  namespace: production
spec:
  podSelector:
    matchLabels:
      component: odoo
  policyTypes:
    - Ingress
    - Egress
  ingress:
    - from:
        - podSelector:
            matchLabels:
              component: ingress-nginx
      ports:
        - protocol: TCP
          port: 8069
  egress:
    - to:
        - podSelector:
            matchLabels:
              component: postgres
      ports:
        - protocol: TCP
          port: 5432
    - to:
        - podSelector:
            matchLabels:
              component: redis
      ports:
        - protocol: TCP
          port: 6379
```

---

## 9. MONITORING & LOGGING

### 9.1 Prometheus ServiceMonitor

```yaml
apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: smart-dairy-odoo-metrics
  namespace: monitoring
spec:
  selector:
    matchLabels:
      component: odoo
  namespaceSelector:
    matchNames:
      - production
  endpoints:
    - port: http
      path: /metrics
      interval: 30s
      scrapeTimeout: 10s
```

### 9.2 Logging Configuration

```yaml
# Fluent Bit configuration for log shipping
apiVersion: v1
kind: ConfigMap
metadata:
  name: fluent-bit-config
data:
  fluent-bit.conf: |
    [INPUT]
        Name              tail
        Tag               odoo.*
        Path              /var/log/odoo/*.log
        Parser            json
        DB                /var/log/flb_odoo.db
    
    [FILTER]
        Name              kubernetes
        Match             odoo.*
        Merge_Log         On
    
    [OUTPUT]
        Name              opensearch
        Match             odoo.*
        Host              opensearch-cluster
        Port              9200
        Index             smart-dairy-logs
        Type              _doc
```

---

## 10. BACKUP & DISASTER RECOVERY

### 10.1 Velero Backup Configuration

```bash
#!/bin/bash
# Install Velero for cluster backups
velero install \
  --provider aws \
  --plugins velero/velero-plugin-for-aws:v1.8.0 \
  --bucket smart-dairy-k8s-backups \
  --backup-location-config region=ap-south-1 \
  --snapshot-location-config region=ap-south-1 \
  --secret-file ./credentials-velero

# Create scheduled backup
velero schedule create smart-dairy-daily \
  --schedule="0 2 * * *" \
  --include-namespaces production \
  --ttl 720h0m0s \
  --storage-location default
```

### 10.2 Disaster Recovery Runbook

| Scenario | RTO | RPO | Recovery Steps |
|----------|-----|-----|----------------|
| **Pod failure** | 5 min | 0 | Automatic reschedule by Kubernetes |
| **Node failure** | 10 min | 0 | Auto-scaling replaces node |
| **AZ failure** | 30 min | 0 | Multi-AZ deployment |
| **Region failure** | 4 hours | 1 hour | Failover to DR region |

---

## 11. OPERATIONS RUNBOOK

### 11.1 Common Operations

```bash
# View cluster status
kubectl get nodes -o wide
kubectl top nodes

# Check pod status
kubectl get pods -n production
kubectl describe pod <pod-name> -n production

# View logs
kubectl logs -f deployment/smart-dairy-odoo -n production
kubectl logs -f deployment/smart-dairy-odoo -n production --previous

# Scale deployment
kubectl scale deployment smart-dairy-odoo --replicas=5 -n production

# Rollout restart
kubectl rollout restart deployment/smart-dairy-odoo -n production

# Rollback
kubectl rollout undo deployment/smart-dairy-odoo -n production
```

### 11.2 Troubleshooting

| Issue | Command | Solution |
|-------|---------|----------|
| Pod stuck pending | `kubectl describe pod <name>` | Check resource quotas, node capacity |
| CrashLoopBackOff | `kubectl logs <pod>` | Check application logs, fix error |
| ImagePullBackOff | `kubectl describe pod <name>` | Verify image tag, registry access |
| OOMKilled | `kubectl top pod <name>` | Increase memory limit |
| High CPU | `kubectl top pod <name>` | Scale horizontally, optimize code |

---

## 12. APPENDICES

### Appendix A: kubectl Cheat Sheet

```bash
# Context and Configuration
kubectl config current-context
kubectl config use-context <context-name>

# Resource Management
kubectl get all -n <namespace>
kubectl apply -f <file.yaml>
kubectl delete -f <file.yaml>

# Debugging
kubectl exec -it <pod> -- /bin/bash
kubectl port-forward <pod> 8080:80
kubectl cp <pod>:/path/file ./local-file
```

### Appendix B: Helm Commands

```bash
# Install/Upgrade
helm upgrade --install smart-dairy ./helm/smart-dairy \
  --namespace production \
  --values values-production.yaml

# Rollback
helm rollback smart-dairy <revision> -n production

# List releases
helm list -n production

# Template rendering
helm template smart-dairy ./helm/smart-dairy -f values-production.yaml
```

---

**END OF KUBERNETES DEPLOYMENT GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | DevOps Lead | Initial version |
