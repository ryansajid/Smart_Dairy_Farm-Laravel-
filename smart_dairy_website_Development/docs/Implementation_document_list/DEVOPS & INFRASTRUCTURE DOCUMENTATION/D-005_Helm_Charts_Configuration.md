# SMART DAIRY LTD.
## HELM CHARTS & CONFIGURATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-005 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Kubernetes Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Helm Architecture](#2-helm-architecture)
3. [Chart Structure](#3-chart-structure)
4. [Core Charts](#4-core-charts)
5. [Configuration Values](#5-configuration-values)
6. [Deployment Procedures](#6-deployment-procedures)
7. [Environment Overrides](#7-environment-overrides)
8. [Chart Maintenance](#8-chart-maintenance)
9. [Troubleshooting](#9-troubleshooting)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the Helm chart structure and configuration management for deploying the Smart Dairy Smart Web Portal System on Kubernetes. Helm provides templating, versioning, and release management for all Kubernetes resources.

### 1.2 Helm Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    HELM DEPLOYMENT ARCHITECTURE                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                    HELM REPOSITORY                       │   │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐        │   │
│  │  │ smart-dairy │ │  postgresql │ │    redis    │        │   │
│  │  │   1.2.3     │ │    12.1.0   │ │   17.4.0    │        │   │
│  │  │  (main app) │ │  (database) │ │   (cache)   │        │   │
│  │  └─────────────┘ └─────────────┘ └─────────────┘        │   │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐        │   │
│  │  │   nginx     │ │ cert-manager│ │  grafana    │        │   │
│  │  │  ingress    │ │    tls      │ │ monitoring  │        │   │
│  │  └─────────────┘ └─────────────┘ └─────────────┘        │   │
│  └──────────────────────────┬──────────────────────────────┘   │
│                             │                                    │
│  ┌──────────────────────────┴──────────────────────────────┐   │
│  │                    RELEASE MANAGEMENT                    │   │
│  │                                                          │   │
│  │   dev-release    staging-release    prod-release        │   │
│  │   (values-dev)   (values-staging)   (values-prod)       │   │
│  │                                                          │   │
│  └──────────────────────────┬──────────────────────────────┘   │
│                             │                                    │
│                             ▼                                    │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │                    EKS CLUSTER                           │   │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐    │   │
│  │  │  Odoo    │ │ FastAPI  │ │  Celery  │ │  Nginx   │    │   │
│  │  │   Pods   │ │   Pods   │ │  Workers │ │ Ingress  │    │   │
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Helm Version

| Component | Version | Purpose |
|-----------|---------|---------|
| Helm | 3.13+ | Package manager |
| Helmfile | 0.158+ | Environment management |
| Chart Museum | 0.30+ | Chart repository |

---

## 2. HELM ARCHITECTURE

### 2.1 Repository Structure

```
helm-charts/
├── Chart.yaml                          # Main chart metadata
├── values.yaml                         # Default values
├── values-dev.yaml                     # Development overrides
├── values-staging.yaml                 # Staging overrides
├── values-prod.yaml                    # Production overrides
├── charts/                             # Chart dependencies
│   ├── postgresql-12.1.0.tgz
│   ├── redis-17.4.0.tgz
│   └── ingress-nginx-4.8.0.tgz
├── templates/                          # Kubernetes templates
│   ├── _helpers.tpl                    # Helper templates
│   ├── _configmap.tpl                  # Config helpers
│   ├── deployment-odoo.yaml
│   ├── deployment-api.yaml
│   ├── deployment-worker.yaml
│   ├── service.yaml
│   ├── ingress.yaml
│   ├── hpa.yaml                        # Horizontal Pod Autoscaler
│   ├── pdb.yaml                        # Pod Disruption Budget
│   └── secrets.yaml
└── crds/                               # Custom Resource Definitions
```

### 2.2 Chart Dependencies

```yaml
# Chart.yaml
apiVersion: v2
name: smart-dairy
version: 1.2.3
appVersion: "2.0.0"
description: Smart Dairy Smart Web Portal System and Integrated ERP
type: application
keywords:
  - erp
  - dairy
  - odoo
  - smart-farming
home: https://smartdairy.bd
sources:
  - https://github.com/smartdairy/smart-dairy-erp
maintainers:
  - name: DevOps Team
    email: devops@smartdairy.bd

dependencies:
  - name: postgresql
    version: 12.1.0
    repository: https://charts.bitnami.com/bitnami
    condition: postgresql.enabled
    tags:
      - database

  - name: redis
    version: 17.4.0
    repository: https://charts.bitnami.com/bitnami
    condition: redis.enabled
    tags:
      - cache

  - name: ingress-nginx
    version: 4.8.0
    repository: https://kubernetes.github.io/ingress-nginx
    condition: ingress-nginx.enabled
    tags:
      - ingress

  - name: cert-manager
    version: 1.12.0
    repository: https://charts.jetstack.io
    condition: cert-manager.enabled
    tags:
      - tls

  - name: kube-prometheus-stack
    version: 51.0.0
    repository: https://prometheus-community.github.io/helm-charts
    condition: monitoring.enabled
    tags:
      - monitoring
```

---

## 3. CHART STRUCTURE

### 3.1 Main Application Chart

```yaml
# templates/deployment-odoo.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: {{ include "smart-dairy.fullname" . }}-odoo
  labels:
    {{- include "smart-dairy.labels" . | nindent 4 }}
    app.kubernetes.io/component: odoo
spec:
  {{- if not .Values.odoo.autoscaling.enabled }}
  replicas: {{ .Values.odoo.replicaCount }}
  {{- end }}
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 25%
      maxUnavailable: 10%
  selector:
    matchLabels:
      {{- include "smart-dairy.selectorLabels" . | nindent 6 }}
      app.kubernetes.io/component: odoo
  template:
    metadata:
      annotations:
        checksum/config: {{ include (print $.Template.BasePath "/configmap.yaml") . | sha256sum }}
        prometheus.io/scrape: "true"
        prometheus.io/port: "8069"
        {{- with .Values.odoo.podAnnotations }}
        {{- toYaml . | nindent 8 }}
        {{- end }}
      labels:
        {{- include "smart-dairy.selectorLabels" . | nindent 8 }}
        app.kubernetes.io/component: odoo
    spec:
      serviceAccountName: {{ include "smart-dairy.serviceAccountName" . }}
      securityContext:
        {{- toYaml .Values.odoo.podSecurityContext | nindent 8 }}
      
      initContainers:
        # Wait for database
        - name: wait-for-db
          image: busybox:1.36
          command:
            - sh
            - -c
            - |
              until nc -z {{ include "smart-dairy.postgresql.host" . }} 5432; do
                echo "Waiting for database..."
                sleep 2
              done
          resources:
            limits:
              cpu: 100m
              memory: 64Mi

      containers:
        - name: odoo
          image: "{{ .Values.odoo.image.repository }}:{{ .Values.odoo.image.tag }}"
          imagePullPolicy: {{ .Values.odoo.image.pullPolicy }}
          
          ports:
            - name: http
              containerPort: 8069
              protocol: TCP
            - name: longpolling
              containerPort: 8072
              protocol: TCP
          
          env:
            - name: HOST
              value: {{ include "smart-dairy.postgresql.host" . | quote }}
            - name: PORT
              value: "5432"
            - name: USER
              value: {{ .Values.postgresql.auth.username | quote }}
            - name: PASSWORD
              valueFrom:
                secretKeyRef:
                  name: {{ include "smart-dairy.fullname" . }}-db
                  key: password
            - name: REDIS_HOST
              value: {{ include "smart-dairy.redis.host" . | quote }}
            - name: REDIS_PORT
              value: "6379"
            {{- range .Values.odoo.extraEnv }}
            - name: {{ .name }}
              value: {{ .value | quote }}
            {{- end }}
          
          volumeMounts:
            - name: odoo-data
              mountPath: /var/lib/odoo
            - name: odoo-config
              mountPath: /etc/odoo
            - name: odoo-addons
              mountPath: /mnt/extra-addons
          
          livenessProbe:
            httpGet:
              path: /web/health
              port: http
            initialDelaySeconds: 60
            periodSeconds: 30
            timeoutSeconds: 5
            failureThreshold: 3
          
          readinessProbe:
            httpGet:
              path: /web/health
              port: http
            initialDelaySeconds: 30
            periodSeconds: 10
            timeoutSeconds: 5
            failureThreshold: 3
          
          resources:
            {{- toYaml .Values.odoo.resources | nindent 12 }}
          
          securityContext:
            {{- toYaml .Values.odoo.securityContext | nindent 12 }}
      
      volumes:
        - name: odoo-data
          persistentVolumeClaim:
            claimName: {{ include "smart-dairy.fullname" . }}-odoo-data
        - name: odoo-config
          configMap:
            name: {{ include "smart-dairy.fullname" . }}-odoo-config
        - name: odoo-addons
          persistentVolumeClaim:
            claimName: {{ include "smart-dairy.fullname" . }}-odoo-addons
      
      {{- with .Values.odoo.nodeSelector }}
      nodeSelector:
        {{- toYaml . | nindent 8 }}
      {{- end }}
      
      {{- with .Values.odoo.affinity }}
      affinity:
        {{- toYaml . | nindent 8 }}
      {{- end }}
      
      {{- with .Values.odoo.tolerations }}
      tolerations:
        {{- toYaml . | nindent 8 }}
      {{- end }}
```

### 3.2 Helper Templates

```yaml
# templates/_helpers.tpl
{{/* vim: set filetype=mustache: */}}
{{/*
Expand the name of the chart.
*/}}
{{- define "smart-dairy.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Create a default fully qualified app name.
*/}}
{{- define "smart-dairy.fullname" -}}
{{- if .Values.fullnameOverride }}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- $name := default .Chart.Name .Values.nameOverride }}
{{- if contains $name .Release.Name }}
{{- .Release.Name | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- end }}
{{- end }}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "smart-dairy.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Common labels
*/}}
{{- define "smart-dairy.labels" -}}
helm.sh/chart: {{ include "smart-dairy.chart" . }}
{{ include "smart-dairy.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}

{{/*
Selector labels
*/}}
{{- define "smart-dairy.selectorLabels" -}}
app.kubernetes.io/name: {{ include "smart-dairy.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}

{{/*
PostgreSQL host
*/}}
{{- define "smart-dairy.postgresql.host" -}}
{{- if .Values.postgresql.enabled }}
{{- printf "%s-postgresql" (include "smart-dairy.fullname" .) }}
{{- else }}
{{- .Values.externalDatabase.host }}
{{- end }}
{{- end }}

{{/*
Redis host
*/}}
{{- define "smart-dairy.redis.host" -}}
{{- if .Values.redis.enabled }}
{{- printf "%s-redis-master" (include "smart-dairy.fullname" .) }}
{{- else }}
{{- .Values.externalRedis.host }}
{{- end }}
{{- end }}
```

---

## 4. CORE CHARTS

### 4.1 Application Services Chart

```yaml
# templates/deployment-api.yaml - FastAPI Mobile API
apiVersion: apps/v1
kind: Deployment
metadata:
  name: {{ include "smart-dairy.fullname" . }}-api
  labels:
    app.kubernetes.io/component: api
spec:
  replicas: {{ .Values.api.replicaCount }}
  selector:
    matchLabels:
      app.kubernetes.io/component: api
  template:
    metadata:
      labels:
        app.kubernetes.io/component: api
    spec:
      containers:
        - name: api
          image: "{{ .Values.api.image.repository }}:{{ .Values.api.image.tag }}"
          ports:
            - containerPort: 8000
          env:
            - name: DATABASE_URL
              valueFrom:
                secretKeyRef:
                  name: {{ include "smart-dairy.fullname" . }}-db
                  key: url
            - name: REDIS_URL
              value: "redis://{{ include "smart-dairy.redis.host" . }}:6379/0"
          resources:
            requests:
              cpu: 200m
              memory: 512Mi
            limits:
              cpu: 1000m
              memory: 1Gi
---
# templates/deployment-worker.yaml - Celery Workers
apiVersion: apps/v1
kind: Deployment
metadata:
  name: {{ include "smart-dairy.fullname" . }}-worker
spec:
  replicas: {{ .Values.worker.replicaCount }}
  selector:
    matchLabels:
      app.kubernetes.io/component: worker
  template:
    metadata:
      labels:
        app.kubernetes.io/component: worker
    spec:
      containers:
        - name: worker
          image: "{{ .Values.worker.image.repository }}:{{ .Values.worker.image.tag }}"
          command:
            - celery
            - -A
            - smart_dairy
            - worker
            - -l
            - info
            - -Q
            - default,emails,reports,iot
          env:
            - name: DATABASE_URL
              valueFrom:
                secretKeyRef:
                  name: {{ include "smart-dairy.fullname" . }}-db
                  key: url
            - name: REDIS_URL
              value: "redis://{{ include "smart-dairy.redis.host" . }}:6379/0"
          resources:
            requests:
              cpu: 100m
              memory: 256Mi
            limits:
              cpu: 500m
              memory: 512Mi
```

### 4.2 Ingress Configuration

```yaml
# templates/ingress.yaml
{{- if .Values.ingress.enabled }}
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: {{ include "smart-dairy.fullname" . }}
  annotations:
    kubernetes.io/ingress.class: nginx
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
    nginx.ingress.kubernetes.io/proxy-body-size: "100m"
    nginx.ingress.kubernetes.io/proxy-read-timeout: "600"
    nginx.ingress.kubernetes.io/proxy-send-timeout: "600"
    cert-manager.io/cluster-issuer: {{ .Values.certManager.issuerName }}
    nginx.ingress.kubernetes.io/rate-limit: "100"
    nginx.ingress.kubernetes.io/rate-limit-window: "1m"
spec:
  tls:
    - hosts:
        - {{ .Values.ingress.host }}
        - www.{{ .Values.ingress.host }}
        - api.{{ .Values.ingress.host }}
        - admin.{{ .Values.ingress.host }}
      secretName: {{ include "smart-dairy.fullname" . }}-tls
  rules:
    - host: {{ .Values.ingress.host }}
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: {{ include "smart-dairy.fullname" . }}-odoo
                port:
                  number: 8069
    - host: api.{{ .Values.ingress.host }}
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: {{ include "smart-dairy.fullname" . }}-api
                port:
                  number: 8000
{{- end }}
```

---

## 5. CONFIGURATION VALUES

### 5.1 Default Values

```yaml
# values.yaml - Default configuration
# Global settings
global:
  imageRegistry: ""
  imagePullSecrets: []
  storageClass: "gp3"

# Odoo ERP Configuration
odoo:
  enabled: true
  replicaCount: 2
  
  image:
    repository: smartdairy/odoo
    tag: "19.0"
    pullPolicy: IfNotPresent
  
  resources:
    requests:
      cpu: 500m
      memory: 1Gi
    limits:
      cpu: 2000m
      memory: 4Gi
  
  autoscaling:
    enabled: true
    minReplicas: 2
    maxReplicas: 10
    targetCPUUtilizationPercentage: 70
    targetMemoryUtilizationPercentage: 80
  
  persistence:
    data:
      enabled: true
      size: 50Gi
      accessMode: ReadWriteOnce
    addons:
      enabled: true
      size: 10Gi
      accessMode: ReadWriteMany
  
  config:
    load_language: "en_US,bn_BD"
    admin_passwd: "admin"
    dbfilter: "^%d$"
    proxy_mode: true
    workers: 4
    max_cron_threads: 2
    limit_memory_soft: 2147483648
    limit_memory_hard: 2684354560

# FastAPI Mobile API
api:
  enabled: true
  replicaCount: 3
  
  image:
    repository: smartdairy/api
    tag: "2.0.0"
    pullPolicy: IfNotPresent
  
  resources:
    requests:
      cpu: 200m
      memory: 512Mi
    limits:
      cpu: 1000m
      memory: 1Gi

# Celery Workers
worker:
  enabled: true
  replicaCount: 2
  
  image:
    repository: smartdairy/worker
    tag: "2.0.0"
    pullPolicy: IfNotPresent

# PostgreSQL Configuration
postgresql:
  enabled: true
  auth:
    username: odoo
    database: smart_dairy_prod
    existingSecret: ""
  primary:
    persistence:
      enabled: true
      size: 500Gi
      storageClass: gp3
    resources:
      requests:
        cpu: 500m
        memory: 1Gi
      limits:
        cpu: 2000m
        memory: 4Gi

# Redis Configuration
redis:
  enabled: true
  architecture: standalone
  auth:
    enabled: true
  master:
    persistence:
      enabled: true
      size: 20Gi
    resources:
      requests:
        cpu: 100m
        memory: 256Mi
      limits:
        cpu: 500m
        memory: 512Mi

# Ingress Configuration
ingress:
  enabled: true
  host: smartdairy.bd
  className: nginx
  annotations: {}

# Certificate Management
certManager:
  enabled: true
  issuerName: letsencrypt-prod

# Monitoring
monitoring:
  enabled: true
```

### 5.2 Production Overrides

```yaml
# values-prod.yaml - Production overrides
odoo:
  replicaCount: 3
  
  resources:
    requests:
      cpu: 1000m
      memory: 2Gi
    limits:
      cpu: 4000m
      memory: 8Gi
  
  autoscaling:
    enabled: true
    minReplicas: 3
    maxReplicas: 15
  
  config:
    workers: 8
    max_cron_threads: 4

api:
  replicaCount: 5
  
  resources:
    requests:
      cpu: 500m
      memory: 1Gi
    limits:
      cpu: 2000m
      memory: 2Gi

postgresql:
  primary:
    persistence:
      size: 1000Gi
    resources:
      requests:
        cpu: 1000m
        memory: 2Gi
      limits:
        cpu: 4000m
        memory: 16Gi

# Pod Disruption Budget
pdb:
  enabled: true
  minAvailable: 2
```

---

## 6. DEPLOYMENT PROCEDURES

### 6.1 Standard Deployment

```bash
#!/bin/bash
# deploy.sh - Standard deployment script

set -e

ENVIRONMENT=${1:-staging}
VERSION=${2:-latest}
NAMESPACE="smart-dairy-${ENVIRONMENT}"

echo "Deploying Smart Dairy to ${ENVIRONMENT}..."

# Add Helm repositories
helm repo add bitnami https://charts.bitnami.com/bitnami
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx
helm repo add jetstack https://charts.jetstack.io
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo update

# Create namespace if not exists
kubectl create namespace ${NAMESPACE} --dry-run=client -o yaml | kubectl apply -f -

# Build dependencies
helm dependency build ./helm-charts

# Deploy/Upgrade
helm upgrade --install smart-dairy ./helm-charts \
    --namespace ${NAMESPACE} \
    --values ./helm-charts/values.yaml \
    --values ./helm-charts/values-${ENVIRONMENT}.yaml \
    --set odoo.image.tag=${VERSION} \
    --set api.image.tag=${VERSION} \
    --set worker.image.tag=${VERSION} \
    --wait \
    --timeout 600s

# Verify deployment
kubectl rollout status deployment/smart-dairy-odoo -n ${NAMESPACE}
kubectl rollout status deployment/smart-dairy-api -n ${NAMESPACE}

echo "Deployment complete!"
```

### 6.2 Helmfile for Multi-Environment

```yaml
# helmfile.yaml
releases:
  # Core infrastructure
  - name: ingress-nginx
    namespace: ingress-nginx
    chart: ingress-nginx/ingress-nginx
    version: 4.8.0
    values:
      - values/ingress-nginx.yaml
    
  - name: cert-manager
    namespace: cert-manager
    chart: jetstack/cert-manager
    version: 1.12.0
    values:
      - values/cert-manager.yaml
    hooks:
      - events: ["postsync"]
        command: kubectl
        args: ["apply", "-f", "manifests/cluster-issuer.yaml"]

  - name: kube-prometheus-stack
    namespace: monitoring
    chart: prometheus-community/kube-prometheus-stack
    version: 51.0.0
    values:
      - values/prometheus.yaml
    
  # Application
  - name: smart-dairy
    namespace: {{ .Environment.Name }}
    chart: ./helm-charts
    version: 1.2.3
    values:
      - helm-charts/values.yaml
      - helm-charts/values-{{ .Environment.Name }}.yaml
    secrets:
      - helm-charts/secrets-{{ .Environment.Name }}.yaml

environments:
  dev:
    values:
      - environments/dev.yaml
  staging:
    values:
      - environments/staging.yaml
  prod:
    values:
      - environments/prod.yaml
```

---

## 7. ENVIRONMENT OVERRIDES

### 7.1 Development Environment

```yaml
# values-dev.yaml
odoo:
  replicaCount: 1
  
  resources:
    requests:
      cpu: 100m
      memory: 512Mi
    limits:
      cpu: 500m
      memory: 1Gi
  
  persistence:
    data:
      size: 10Gi
    addons:
      size: 5Gi

postgresql:
  primary:
    persistence:
      size: 50Gi
    resources:
      requests:
        cpu: 100m
        memory: 256Mi
      limits:
        cpu: 500m
        memory: 1Gi

ingress:
  host: dev.smartdairy.bd
```

### 7.2 Staging Environment

```yaml
# values-staging.yaml
odoo:
  replicaCount: 2
  
  resources:
    requests:
      cpu: 250m
      memory: 1Gi
    limits:
      cpu: 1000m
      memory: 2Gi

postgresql:
  primary:
    persistence:
      size: 100Gi

ingress:
  host: staging.smartdairy.bd
```

---

## 8. CHART MAINTENANCE

### 8.1 Version Management

```bash
# Update chart version
# 1. Update version in Chart.yaml
# 2. Update dependencies
helm dependency update

# 3. Package chart
helm package .

# 4. Upload to repository
helm push smart-dairy-1.2.3.tgz oci://registry.smartdairy.bd/charts

# 5. Update index
helm repo index . --url https://charts.smartdairy.bd
```

### 8.2 Upgrade Procedures

```bash
# Check for updates
helm list --namespace smart-dairy-prod

# Preview changes
helm diff upgrade smart-dairy ./helm-charts \
    --namespace smart-dairy-prod \
    --values ./helm-charts/values-prod.yaml

# Perform upgrade
helm upgrade smart-dairy ./helm-charts \
    --namespace smart-dairy-prod \
    --values ./helm-charts/values-prod.yaml \
    --wait \
    --timeout 600s

# Rollback if needed
helm rollback smart-dairy 2 --namespace smart-dairy-prod
```

---

## 9. TROUBLESHOOTING

### 9.1 Common Issues

| Issue | Command | Solution |
|-------|---------|----------|
| Failed release | `helm list --failed` | Check events with `kubectl describe` |
| Image pull errors | `kubectl get events` | Verify image tag and registry auth |
| Config errors | `helm template .` | Validate templates before deploy |
| Resource conflicts | `helm get manifest` | Check for duplicate resource names |
| Pod not starting | `kubectl logs` | Check application logs |

### 9.2 Debug Commands

```bash
# Render templates locally
helm template smart-dairy ./helm-charts --values values-prod.yaml

# Get release history
helm history smart-dairy --namespace smart-dairy-prod

# Get manifest
helm get manifest smart-dairy --namespace smart-dairy-prod

# Get values
helm get values smart-dairy --namespace smart-dairy-prod

# Test release
helm test smart-dairy --namespace smart-dairy-prod
```

---

## 10. APPENDICES

### Appendix A: Helm Commands Quick Reference

| Command | Purpose |
|---------|---------|
| `helm install` | Install a new chart |
| `helm upgrade` | Upgrade an existing release |
| `helm rollback` | Rollback to previous revision |
| `helm uninstall` | Remove a release |
| `helm list` | List all releases |
| `helm status` | Show release status |
| `helm get values` | Show deployed values |
| `helm template` | Render templates locally |
| `helm lint` | Check chart for issues |
| `helm package` | Package chart for distribution |

### Appendix B: Chart Testing

```yaml
# templates/tests/test-connection.yaml
apiVersion: v1
kind: Pod
metadata:
  name: "{{ include "smart-dairy.fullname" . }}-test-connection"
  annotations:
    "helm.sh/hook": test
spec:
  containers:
    - name: wget
      image: busybox
      command:
        - wget
        - --spider
        - "{{ include "smart-dairy.fullname" . }}-odoo:8069/web/health"
  restartPolicy: Never
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Kubernetes Engineer | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
