# D-011: SSL Certificate Management

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | D-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |
| **Classification** | Internal |
| **Status** | Approved |

---

## Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | DevOps Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Certificate Architecture](#2-certificate-architecture)
3. [cert-manager Setup](#3-cert-manager-setup)
4. [Let's Encrypt Integration](#4-lets-encrypt-integration)
5. [Certificate Request Flow](#5-certificate-request-flow)
6. [AWS ACM Alternative](#6-aws-acm-alternative)
7. [Renewal Process](#7-renewal-process)
8. [Certificate Deployment](#8-certificate-deployment)
9. [Private CA](#9-private-ca)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the SSL/TLS certificate management strategy for Smart Dairy Ltd's web portal infrastructure. It establishes standards, procedures, and technical configurations for obtaining, deploying, and maintaining SSL certificates across all environments.

### 1.2 SSL/TLS Importance

Transport Layer Security (TLS) and its predecessor, Secure Sockets Layer (SSL), are cryptographic protocols designed to provide:

- **Data Encryption**: Protects data in transit from eavesdropping and man-in-the-middle attacks
- **Authentication**: Verifies the identity of the server through digital certificates
- **Data Integrity**: Ensures data is not modified during transmission
- **Trust**: Displays security indicators (padlock icon, HTTPS) to users

### 1.3 Compliance Requirements

| Regulation | Requirement | Implementation |
|------------|-------------|----------------|
| **PCI DSS** | TLS 1.2+ for cardholder data | Minimum TLS 1.2 enforced |
| **GDPR** | Appropriate security measures | End-to-end encryption |
| **Bangladesh ICT Act** | Secure data transmission | Certificate transparency |
| **OWASP Top 10** | Secure communications | HSTS, secure cipher suites |

### 1.4 Domains in Scope

| Domain | Purpose | Certificate Type |
|--------|---------|------------------|
| `smartdairy.bd` | Main domain | Wildcard or dedicated |
| `*.smartdairy.bd` | All subdomains | Wildcard certificate |
| `api.smartdairy.bd` | API endpoints | Covered by wildcard |
| `admin.smartdairy.bd` | Admin portal | Covered by wildcard |
| `www.smartdairy.bd` | WWW redirect | Covered by wildcard |

---

## 2. Certificate Architecture

### 2.1 Certificate Types Comparison

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    CERTIFICATE ARCHITECTURE OVERVIEW                     │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│   ┌─────────────────┐      ┌─────────────────┐      ┌─────────────────┐ │
│   │   Let's Encrypt │      │   Let's Encrypt │      │    AWS ACM      │ │
│   │  Wildcard Cert  │      │ Individual Cert │      │  Public Cert    │ │
│   │  *.smartdairy.bd│      │ api.smartdairy  │      │  CloudFront/ALB │ │
│   └────────┬────────┘      └────────┬────────┘      └────────┬────────┘ │
│            │                        │                        │          │
│            ▼                        ▼                        ▼          │
│   ┌─────────────────────────────────────────────────────────────────┐  │
│   │                    Kubernetes Ingress Nginx                      │  │
│   │                    (TLS Termination Layer)                       │  │
│   └─────────────────────────────────────────────────────────────────┘  │
│                                                                          │
│   ┌─────────────────────────────────────────────────────────────────┐  │
│   │                    EKS Cluster (Application)                     │  │
│   └─────────────────────────────────────────────────────────────────┘  │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Wildcard vs Individual Certificates

| Aspect | Wildcard Certificate | Individual Certificates |
|--------|---------------------|------------------------|
| **Coverage** | `*.smartdairy.bd` | Single domain only |
| **Cost** | Free (Let's Encrypt) | Free (Let's Encrypt) |
| **Management** | Single certificate | Multiple certificates |
| **Security** | Key compromise affects all | Isolated per domain |
| **Automation** | DNS-01 challenge required | HTTP-01 challenge possible |
| **Use Case** | Multiple subdomains | High-security single domain |

**Recommendation**: Use wildcard certificate for `*.smartdairy.bd` covering all subdomains.

### 2.3 Multi-Domain SAN Certificate

For specific requirements, a Subject Alternative Name (SAN) certificate can include:

```
Primary Domain: smartdairy.bd
SAN Entries:
  - *.smartdairy.bd
  - api.smartdairy.bd
  - admin.smartdairy.bd
  - www.smartdairy.bd
```

### 2.4 Certificate Chain Architecture

```
Root CA: ISRG Root X1 (Let's Encrypt)
    │
    ▼
Intermediate CA: R3 (Let's Encrypt)
    │
    ▼
Leaf Certificate: *.smartdairy.bd
```

---

## 3. cert-manager Setup

### 3.1 Installation in EKS

#### 3.1.1 Prerequisites

- Kubernetes 1.24+
- kubectl configured for EKS cluster
- Helm 3.x installed
- Route 53 hosted zone for `smartdairy.bd`

#### 3.1.2 Install cert-manager via Helm

```bash
# Add the Jetstack Helm repository
helm repo add jetstack https://charts.jetstack.io
helm repo update

# Create namespace
kubectl create namespace cert-manager

# Install cert-manager with CRDs
helm install cert-manager jetstack/cert-manager \
  --namespace cert-manager \
  --version v1.12.0 \
  --set installCRDs=true \
  --set prometheus.enabled=true \
  --set prometheus.servicemonitor.enabled=true

# Verify installation
kubectl get pods --namespace cert-manager
```

#### 3.1.3 Installation Verification

```bash
# Check cert-manager pods are running
kubectl get pods -n cert-manager

# Expected output:
# NAME                                      READY   STATUS
# cert-manager-7c9c4f5bb5-abc12             1/1     Running
# cert-manager-cainjector-5c8c4f5bb5-xyz34  1/1     Running
# cert-manager-webhook-7c9c4f5bb5-567de     1/1     Running
```

### 3.2 RBAC Configuration

```yaml
# cert-manager-rbac.yaml
apiVersion: v1
kind: ServiceAccount
metadata:
  name: cert-manager-route53
  namespace: cert-manager
  annotations:
    eks.amazonaws.com/role-arn: arn:aws:iam::ACCOUNT_ID:role/cert-manager-route53
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: cert-manager-controller-certificates
rules:
  - apiGroups: ["cert-manager.io"]
    resources: ["certificates", "certificaterequests", "orders", "challenges"]
    verbs: ["get", "list", "watch", "create", "update", "patch", "delete"]
```

### 3.3 AWS IAM Role for Route 53

```json
// cert-manager-route53-trust-policy.json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "Federated": "arn:aws:iam::ACCOUNT_ID:oidc-provider/oidc.eks.REGION.amazonaws.com/id/EXAMPLED539D4633E53DE1B716D3041E"
      },
      "Action": "sts:AssumeRoleWithWebIdentity",
      "Condition": {
        "StringEquals": {
          "oidc.eks.REGION.amazonaws.com/id/EXAMPLED539D4633E53DE1B716D3041E:sub": "system:serviceaccount:cert-manager:cert-manager-route53",
          "oidc.eks.REGION.amazonaws.com/id/EXAMPLED539D4633E53DE1B716D3041E:aud": "sts.amazonaws.com"
        }
      }
    }
  ]
}
```

```json
// cert-manager-route53-policy.json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "route53:GetChange",
      "Resource": "arn:aws:route53:::change/*"
    },
    {
      "Effect": "Allow",
      "Action": [
        "route53:ChangeResourceRecordSets",
        "route53:ListResourceRecordSets"
      ],
      "Resource": "arn:aws:route53:::hostedzone/Z2K8XL3EXAMPLE"
    },
    {
      "Effect": "Allow",
      "Action": "route53:ListHostedZonesByName",
      "Resource": "*"
    }
  ]
}
```

---

## 4. Let's Encrypt Integration

### 4.1 ACME Protocol Overview

The Automatic Certificate Management Environment (ACME) protocol automates:
- Domain validation
- Certificate issuance
- Certificate renewal

### 4.2 Staging vs Production

| Environment | Rate Limits | Use Case |
|-------------|-------------|----------|
| **Staging** | Very high | Testing, development |
| **Production** | 50 certs/week | Production workloads |

### 4.3 Staging ClusterIssuer Configuration

```yaml
# clusterissuer-letsencrypt-staging.yaml
apiVersion: cert-manager.io/v1
kind: ClusterIssuer
metadata:
  name: letsencrypt-staging
spec:
  acme:
    # Staging server URL
    server: https://acme-staging-v02.api.letsencrypt.org/directory
    
    # Email for expiration notices
    email: devops@smartdairy.bd
    
    # Secret to store ACME account private key
    privateKeySecretRef:
      name: letsencrypt-staging-account-key
    
    # Enable ACME v2
    solvers:
      # DNS-01 challenge for wildcard certificates
      - selector:
          dnsZones:
            - "smartdairy.bd"
        dns01:
          route53:
            region: ap-south-1
            hostedZoneID: Z2K8XL3EXAMPLE
            # Using IRSA (IAM Roles for Service Accounts)
            # No access keys needed when using EKS with OIDC
      
      # HTTP-01 challenge for non-wildcard (fallback)
      - selector: {}
        http01:
          ingress:
            class: nginx
```

### 4.4 Production ClusterIssuer Configuration

```yaml
# clusterissuer-letsencrypt-production.yaml
apiVersion: cert-manager.io/v1
kind: ClusterIssuer
metadata:
  name: letsencrypt-production
spec:
  acme:
    # Production server URL
    server: https://acme-v02.api.letsencrypt.org/directory
    
    # Email for expiration notices
    email: devops@smartdairy.bd
    
    # Secret to store ACME account private key
    privateKeySecretRef:
      name: letsencrypt-production-account-key
    
    # Enable ACME v2
    solvers:
      # DNS-01 challenge for wildcard certificates
      - selector:
          dnsZones:
            - "smartdairy.bd"
        dns01:
          route53:
            region: ap-south-1
            hostedZoneID: Z2K8XL3EXAMPLE
            # IRSA - IAM Roles for Service Accounts
      
      # HTTP-01 challenge for non-wildcard (fallback)
      - selector: {}
        http01:
          ingress:
            class: nginx
```

### 4.5 Apply ClusterIssuers

```bash
# Apply staging ClusterIssuer
kubectl apply -f clusterissuer-letsencrypt-staging.yaml

# Apply production ClusterIssuer
kubectl apply -f clusterissuer-letsencrypt-production.yaml

# Verify ClusterIssuers
kubectl get clusterissuers
kubectl describe clusterissuer letsencrypt-production
```

---

## 5. Certificate Request Flow

### 5.1 Automated Issuance Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    CERTIFICATE ISSUANCE FLOW                             │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌──────────┐ │
│  │  Certificate│───▶│  Certificate│───▶│    Order    │───▶│ Challenge│ │
│  │   Resource  │    │   Request   │    │   (ACME)    │    │ (DNS-01) │ │
│  └─────────────┘    └─────────────┘    └──────┬──────┘    └────┬─────┘ │
│                                               │                │       │
│                                               ▼                ▼       │
│                                        ┌─────────────────────────────┐│
│                                        │     Let's Encrypt CA        ││
│                                        └─────────────┬───────────────┘│
│                                                      │                │
│                                                      ▼                │
│                                               ┌─────────────┐         │
│                                               │   Route 53  │         │
│                                               │   DNS TXT   │         │
│                                               └─────────────┘         │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Wildcard Certificate Resource

```yaml
# certificate-wildcard-smartdairy.yaml
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: wildcard-smartdairy-bd
  namespace: ingress-nginx
spec:
  # Secret name where certificate will be stored
  secretName: wildcard-smartdairy-bd-tls
  
  # Certificate validity duration
  duration: 2160h  # 90 days (Let's Encrypt default)
  renewBefore: 360h  # Renew 15 days before expiry
  
  # Subject information
  subject:
    organizations:
      - Smart Dairy Ltd
    organizationalUnits:
      - IT Operations
    countries:
      - BD
    localities:
      - Dhaka
  
  # Private key configuration
  privateKey:
    algorithm: RSA
    encoding: PKCS1
    size: 2048
    rotationPolicy: Always
  
  # DNS names covered by this certificate
  dnsNames:
    - smartdairy.bd
    - "*.smartdairy.bd"
    - api.smartdairy.bd
    - admin.smartdairy.bd
    - www.smartdairy.bd
  
  # Issuer reference
  issuerRef:
    name: letsencrypt-production
    kind: ClusterIssuer
    group: cert-manager.io
```

### 5.3 DNS-01 Challenge for Wildcard

The DNS-01 challenge proves domain control by creating a TXT record:

```
_acme-challenge.smartdairy.bd.  IN  TXT  "dns-challenge-token-value"
```

**Advantages of DNS-01:**
- Supports wildcard certificates (`*.smartdairy.bd`)
- Works with internal services
- No requirement for HTTP access

### 5.4 Certificate Application Commands

```bash
# Apply staging certificate first for testing
kubectl apply -f certificate-wildcard-smartdairy-staging.yaml

# Check certificate status
kubectl describe certificate wildcard-smartdairy-bd -n ingress-nginx

# Check certificate request
kubectl get certificaterequests -n ingress-nginx

# Check order status
kubectl get orders -n ingress-nginx

# Check challenge status
kubectl get challenges -n ingress-nginx

# Once staging works, apply production
kubectl apply -f certificate-wildcard-smartdairy.yaml
```

### 5.5 Certificate Status Monitoring

```bash
# Watch certificate readiness
kubectl wait --for=condition=Ready certificate/wildcard-smartdairy-bd \
  --namespace=ingress-nginx --timeout=300s

# Check secret was created
kubectl get secret wildcard-smartdairy-bd-tls -n ingress-nginx

# Decode certificate (for verification)
kubectl get secret wildcard-smartdairy-bd-tls \
  -n ingress-nginx \
  -o jsonpath='{.data.tls\.crt}' | base64 -d | openssl x509 -text -noout
```

---

## 6. AWS ACM Alternative

### 6.1 When to Use AWS ACM

| Use Case | Recommendation |
|----------|---------------|
| ALB/NLB TLS termination | AWS ACM (simpler integration) |
| CloudFront distribution | AWS ACM (required) |
| EKS with NLB | AWS ACM |
| Pod-to-pod mTLS | cert-manager |

### 6.2 Request Certificate via AWS Console

1. Navigate to **AWS Certificate Manager** → **Request certificate**
2. Select **Request a public certificate**
3. Add domain names:
   - `smartdairy.bd`
   - `*.smartdairy.bd`
4. Select **DNS validation**
5. Add tags: `Environment: Production`, `Project: SmartDairy`
6. Create CNAME records in Route 53 for validation

### 6.3 Request Certificate via AWS CLI

```bash
# Request certificate
aws acm request-certificate \
  --domain-name smartdairy.bd \
  --subject-alternative-names "*.smartdairy.bd" \
  --validation-method DNS \
  --idempotency-token smartdairy2026 \
  --region ap-south-1 \
  --tags Key=Environment,Value=Production Key=Project,Value=SmartDairy

# Get certificate ARN
CERT_ARN=$(aws acm list-certificates \
  --query 'CertificateSummaryList[?DomainName==`smartdairy.bd`].CertificateArn' \
  --output text --region ap-south-1)

echo "Certificate ARN: $CERT_ARN"
```

### 6.4 ALB with ACM Certificate

```yaml
# alb-ingress-with-acm.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: smartdairy-alb-ingress
  namespace: production
  annotations:
    # AWS ALB Ingress Controller annotations
    alb.ingress.kubernetes.io/scheme: internet-facing
    alb.ingress.kubernetes.io/target-type: ip
    alb.ingress.kubernetes.io/listen-ports: '[{"HTTPS":443}]'
    alb.ingress.kubernetes.io/certificate-arn: arn:aws:acm:ap-south-1:ACCOUNT_ID:certificate/CERTIFICATE_ID
    alb.ingress.kubernetes.io/ssl-policy: ELBSecurityPolicy-TLS13-1-2-2021-06
    alb.ingress.kubernetes.io/healthcheck-path: /health
    alb.ingress.kubernetes.io/healthcheck-port: traffic-port
spec:
  ingressClassName: alb
  rules:
    - host: api.smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: api-service
                port:
                  number: 80
```

### 6.5 CloudFront with ACM Certificate

```yaml
# cloudfront-distribution.yaml (AWS CloudFormation)
AWSTemplateFormatVersion: '2010-09-09'
Resources:
  SmartDairyCloudFront:
    Type: AWS::CloudFront::Distribution
    Properties:
      DistributionConfig:
        Enabled: true
        DefaultCacheBehavior:
          TargetOriginId: S3Origin
          ViewerProtocolPolicy: redirect-to-https
          CachePolicyId: 658327ea-f89d-4fab-a63d-7e88639e58f6  # CachingOptimized
        Origins:
          - Id: S3Origin
            DomainName: smartdairy-static.s3.amazonaws.com
            S3OriginConfig:
              OriginAccessIdentity: ''
        Aliases:
          - static.smartdairy.bd
          - www.smartdairy.bd
        ViewerCertificate:
          AcmCertificateArn: arn:aws:acm:us-east-1:ACCOUNT_ID:certificate/CERTIFICATE_ID
          SslSupportMethod: sni-only
          MinimumProtocolVersion: TLSv1.2_2021
```

### 6.6 ACM vs cert-manager Decision Matrix

| Factor | AWS ACM | cert-manager |
|--------|---------|--------------|
| Cost | Free | Free |
| Wildcard support | Yes | Yes |
| Automatic renewal | Yes | Yes |
| Multi-cloud | No | Yes |
| Kubernetes native | No | Yes |
| Private CA | Yes (ACM PCA) | Yes (CA Issuer) |
| Export private key | No | Yes |

---

## 7. Renewal Process

### 7.1 Automatic Renewal

cert-manager automatically renews certificates:

```
Renewal Window = Certificate Expiry - renewBefore (default: 30 days)
```

For Let's Encrypt (90-day certificates):
- Certificate issued: Day 0
- Renewal begins: Day 60
- Must renew by: Day 90

### 7.2 Renewal Monitoring

```bash
# Check certificate expiry dates
kubectl get certificates -A -o custom-columns=\
  "NAME:.metadata.name,NAMESPACE:.metadata.namespace,READY:.status.conditions[0].status,EXPIRES:.status.notAfter"

# Detailed certificate info
kubectl get certificate wildcard-smartdairy-bd -n ingress-nginx -o yaml
```

### 7.3 Prometheus Alerting Rules

```yaml
# cert-manager-alerts.yaml
groups:
  - name: cert-manager
    rules:
      # Alert when certificate is expiring in less than 30 days
      - alert: CertificateExpiringSoon
        expr: |
          certmanager_certificate_expiration_timestamp_seconds - time() < 2592000
        for: 1h
        labels:
          severity: warning
        annotations:
          summary: "Certificate expiring soon"
          description: "Certificate {{ $labels.name }} in namespace {{ $labels.namespace }} expires in less than 30 days"
      
      # Alert when certificate is expiring in less than 7 days
      - alert: CertificateExpiringCritical
        expr: |
          certmanager_certificate_expiration_timestamp_seconds - time() < 604800
        for: 1h
        labels:
          severity: critical
        annotations:
          summary: "Certificate expiring critically soon"
          description: "Certificate {{ $labels.name }} in namespace {{ $labels.namespace }} expires in less than 7 days"
      
      # Alert when certificate is not ready
      - alert: CertificateNotReady
        expr: |
          certmanager_certificate_ready_status{condition="False"} == 1
        for: 5m
        labels:
          severity: critical
        annotations:
          summary: "Certificate not ready"
          description: "Certificate {{ $labels.name }} in namespace {{ $labels.namespace }} is not ready"
      
      # Alert on failed renewals
      - alert: CertificateRenewalFailed
        expr: |
          increase(certmanager_certificate_renewal_failures_total[1h]) > 0
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "Certificate renewal failed"
          description: "Certificate {{ $labels.name }} renewal has failed"
```

### 7.4 Certificate Expiration Dashboard

```yaml
# Grafana dashboard configuration (JSON model excerpt)
{
  "dashboard": {
    "title": "SSL Certificate Monitoring",
    "panels": [
      {
        "title": "Certificate Expiry",
        "type": "stat",
        "targets": [
          {
            "expr": "(certmanager_certificate_expiration_timestamp_seconds - time()) / 86400",
            "legendFormat": "{{name}} ({{namespace}})"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "d",
            "thresholds": {
              "steps": [
                {"color": "red", "value": 0},
                {"color": "yellow", "value": 7},
                {"color": "green", "value": 30}
              ]
            }
          }
        }
      }
    ]
  }
}
```

### 7.5 Manual Renewal Trigger

```bash
# Force immediate renewal (not recommended for normal operations)
kubectl annotate certificate wildcard-smartdairy-bd \
  -n ingress-nginx \
  cert-manager.io/issue-temporary-certificate="true"

# Or delete and recreate (triggers new issuance)
kubectl delete secret wildcard-smartdairy-bd-tls -n ingress-nginx
# cert-manager will automatically recreate
```

---

## 8. Certificate Deployment

### 8.1 Kubernetes Secrets

cert-manager stores certificates as Kubernetes TLS secrets:

```yaml
# Secret structure created by cert-manager
apiVersion: v1
kind: Secret
type: kubernetes.io/tls
metadata:
  name: wildcard-smartdairy-bd-tls
  namespace: ingress-nginx
data:
  tls.crt: <base64-encoded certificate>
  tls.key: <base64-encoded private key>
  ca.crt: <base64-encoded CA certificate (optional)>
```

### 8.2 Ingress Annotations

#### 8.2.1 Nginx Ingress with TLS

```yaml
# ingress-smartdairy-tls.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: smartdairy-ingress
  namespace: production
  annotations:
    # Nginx Ingress Controller annotations
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
    nginx.ingress.kubernetes.io/force-ssl-redirect: "true"
    
    # HSTS Configuration
    nginx.ingress.kubernetes.io/hsts: "true"
    nginx.ingress.kubernetes.io/hsts-max-age: "31536000"
    nginx.ingress.kubernetes.io/hsts-include-subdomains: "true"
    nginx.ingress.kubernetes.io/hsts-preload: "true"
    
    # SSL Configuration
    nginx.ingress.kubernetes.io/ssl-protocols: "TLSv1.2 TLSv1.3"
    nginx.ingress.kubernetes.io/ssl-ciphers: "ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384"
    nginx.ingress.kubernetes.io/ssl-prefer-server-ciphers: "true"
    
    # Proxy settings
    nginx.ingress.kubernetes.io/proxy-body-size: "50m"
    nginx.ingress.kubernetes.io/proxy-read-timeout: "300"
    nginx.ingress.kubernetes.io/proxy-send-timeout: "300"
    
    # Rate limiting
    nginx.ingress.kubernetes.io/limit-rps: "10"
    nginx.ingress.kubernetes.io/limit-connections: "5"
    
    # cert-manager annotation for automatic certificate
    cert-manager.io/cluster-issuer: "letsencrypt-production"
    
    # Additional annotations
    nginx.ingress.kubernetes.io/configuration-snippet: |
      add_header X-Frame-Options "SAMEORIGIN" always;
      add_header X-Content-Type-Options "nosniff" always;
      add_header X-XSS-Protection "1; mode=block" always;
      add_header Referrer-Policy "strict-origin-when-cross-origin" always;
spec:
  ingressClassName: nginx
  tls:
    - hosts:
        - smartdairy.bd
        - www.smartdairy.bd
        - api.smartdairy.bd
        - admin.smartdairy.bd
      secretName: wildcard-smartdairy-bd-tls
  rules:
    - host: smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: frontend-service
                port:
                  number: 80
    - host: www.smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: frontend-service
                port:
                  number: 80
    - host: api.smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: api-gateway-service
                port:
                  number: 80
    - host: admin.smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: admin-panel-service
                port:
                  number: 80
```

#### 8.2.2 Ingress with Automatic Certificate Creation

```yaml
# ingress-with-auto-certificate.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: smartdairy-auto-tls
  namespace: production
  annotations:
    cert-manager.io/cluster-issuer: letsencrypt-production
    cert-manager.io/common-name: api.smartdairy.bd
    acme.cert-manager.io/http01-edit-in-place: "true"
spec:
  ingressClassName: nginx
  tls:
    - hosts:
        - api.smartdairy.bd
      secretName: api-smartdairy-bd-auto-tls
  rules:
    - host: api.smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: api-service
                port:
                  number: 80
```

### 8.3 Multiple Ingress Controllers

```yaml
# api-ingress-specific-controller.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: api-ingress
  namespace: production
  annotations:
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
spec:
  ingressClassName: nginx-internal  # Use internal ingress
  tls:
    - hosts:
        - internal-api.smartdairy.bd
      secretName: internal-api-tls
  rules:
    - host: internal-api.smartdairy.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: internal-api-service
                port:
                  number: 8080
```

### 8.4 Certificate per Namespace

```yaml
# namespace-specific-certificate.yaml
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: api-cert
  namespace: production  # Certificate in production namespace
spec:
  secretName: api-tls-secret
  issuerRef:
    name: letsencrypt-production
    kind: ClusterIssuer
  dnsNames:
    - api.smartdairy.bd
---
# Reference from different namespace (requires secret replication)
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: api-ingress
  namespace: staging
spec:
  ingressClassName: nginx
  tls:
    - hosts:
        - api-staging.smartdairy.bd
      secretName: api-tls-secret  # Must exist in this namespace
```

---

## 9. Private CA

### 9.1 Use Cases for Internal CA

- Service-to-service mTLS within cluster
- Internal API authentication
- Development/staging environments
- Internal tooling (Grafana, Prometheus, etc.)

### 9.2 Create Self-Signed CA Issuer

```yaml
# ca-issuer-selfsigned.yaml
# Step 1: Create a self-signed CA certificate
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: smartdairy-ca
  namespace: cert-manager
spec:
  isCA: true
  secretName: smartdairy-ca-secret
  commonName: "SmartDairy Internal CA"
  subject:
    organizations:
      - Smart Dairy Ltd
    organizationalUnits:
      - Internal PKI
    countries:
      - BD
  issuerRef:
    name: selfsigned-issuer
    kind: ClusterIssuer
---
# Step 2: Create the selfsigned issuer
apiVersion: cert-manager.io/v1
kind: ClusterIssuer
metadata:
  name: selfsigned-issuer
spec:
  selfSigned: {}
---
# Step 3: Create the CA Issuer
apiVersion: cert-manager.io/v1
kind: ClusterIssuer
metadata:
  name: smartdairy-ca-issuer
spec:
  ca:
    secretName: smartdairy-ca-secret
```

### 9.3 Issue Internal Service Certificates

```yaml
# internal-service-certificate.yaml
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: grafana-internal-cert
  namespace: monitoring
spec:
  secretName: grafana-internal-tls
  issuerRef:
    name: smartdairy-ca-issuer
    kind: ClusterIssuer
  dnsNames:
    - grafana.monitoring.svc.cluster.local
    - grafana.internal.smartdairy.bd
  usages:
    - server auth
    - client auth
  privateKey:
    algorithm: RSA
    encoding: PKCS1
    size: 2048
```

### 9.4 mTLS Between Services

```yaml
# client-certificate-for-mtls.yaml
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: service-client-cert
  namespace: production
spec:
  secretName: service-client-tls
  issuerRef:
    name: smartdairy-ca-issuer
    kind: ClusterIssuer
  commonName: service-account
  dnsNames:
    - service-a.production.svc.cluster.local
  usages:
    - client auth
---
# server-certificate-for-mtls.yaml
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: service-server-cert
  namespace: production
spec:
  secretName: service-server-tls
  issuerRef:
    name: smartdairy-ca-issuer
    kind: ClusterIssuer
  commonName: service-b-server
  dnsNames:
    - service-b.production.svc.cluster.local
  usages:
    - server auth
```

### 9.5 Trust Distribution

```yaml
# trust-bundle-configmap.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: ca-bundle
  namespace: production
data:
  ca.crt: |
    -----BEGIN CERTIFICATE-----
    # CA certificate content here
    -----END CERTIFICATE-----
---
# Mount CA bundle in application
apiVersion: apps/v1
kind: Deployment
metadata:
  name: my-app
  namespace: production
spec:
  template:
    spec:
      containers:
        - name: app
          image: my-app:latest
          volumeMounts:
            - name: ca-bundle
              mountPath: /etc/ssl/certs
              readOnly: true
      volumes:
        - name: ca-bundle
          configMap:
            name: ca-bundle
```

---

## 10. Troubleshooting

### 10.1 Common Issues and Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Certificate stuck in `Pending` | DNS propagation delay | Wait 5-10 minutes |
| `Authorization failed` | Incorrect DNS zone ID | Verify Route 53 zone ID |
| `Failed to determine hosted zone` | IAM permissions | Check Route 53 IAM policy |
| `Rate limit exceeded` | Too many requests | Switch to staging, wait 1 hour |
| `Secret not found` | Certificate not ready | Check cert-manager logs |

### 10.2 Debugging ACME Challenges

```bash
# Check certificate status
kubectl describe certificate wildcard-smartdairy-bd -n ingress-nginx

# Check certificate request
kubectl describe certificaterequest -n ingress-nginx

# Check ACME order
kubectl describe order -n ingress-nginx

# Check ACME challenge
kubectl describe challenge -n ingress-nginx

# View cert-manager logs
kubectl logs -n cert-manager deployment/cert-manager --tail=100

# View cert-manager webhook logs
kubectl logs -n cert-manager deployment/cert-manager-webhook --tail=50
```

### 10.3 Challenge Debugging Commands

```bash
# List all challenges
kubectl get challenges -A

# Get detailed challenge info
kubectl get challenge -n ingress-nginx -o yaml

# Check DNS TXT record propagation
dig TXT _acme-challenge.smartdairy.bd

# Check with specific DNS server
dig @8.8.8.8 TXT _acme-challenge.smartdairy.bd

# Check Route 53 record
aws route53 list-resource-record-sets \
  --hosted-zone-id Z2K8XL3EXAMPLE \
  --query "ResourceRecordSets[?Name=='_acme-challenge.smartdairy.bd.']"
```

### 10.4 Rate Limit Issues

Let's Encrypt rate limits:
- **Certificates per domain**: 50 per week
- **Duplicate certificates**: 5 per week
- **Failed validations**: 5 per hour
- **ACME account creation**: 10 per 3 hours

```bash
# Check rate limit status (staging)
curl -I https://acme-staging-v02.api.letsencrypt.org/directory

# If rate limited, check when limit resets in cert-manager logs
kubectl logs -n cert-manager deployment/cert-manager | grep -i "rate limit"
```

### 10.5 Certificate Validation Script

```bash
#!/bin/bash
# validate-certificates.sh

echo "=== Certificate Validation Report ==="
echo "Date: $(date)"
echo ""

# Get all certificates
kubectl get certificates -A -o json | jq -r '.items[] | "\(.metadata.namespace)/\(.metadata.name)"' | while read cert; do
  ns=$(echo $cert | cut -d'/' -f1)
  name=$(echo $cert | cut -d'/' -f2)
  
  echo "Checking: $name in namespace $ns"
  
  # Get certificate details
  kubectl get certificate $name -n $ns -o json | jq -r '
    "  Status: \(.status.conditions[0].status)",
    "  Reason: \(.status.conditions[0].reason // "N/A")",
    "  Message: \(.status.conditions[0].message // "N/A")",
    "  Expires: \(.status.notAfter // "Unknown")",
    ""
  '
done

echo "=== End of Report ==="
```

### 10.6 Force Certificate Re-issuance

```bash
# Method 1: Delete the secret (cert-manager recreates)
kubectl delete secret wildcard-smartdairy-bd-tls -n ingress-nginx

# Method 2: Add annotation to trigger renewal
kubectl annotate certificate wildcard-smartdairy-bd \
  -n ingress-nginx \
  cert-manager.io/issue-temporary-certificate-="true" --overwrite

# Method 3: Delete and recreate certificate
kubectl delete certificate wildcard-smartdairy-bd -n ingress-nginx
kubectl apply -f certificate-wildcard-smartdairy.yaml
```

### 10.7 Emergency Contacts

| Role | Name | Contact |
|------|------|---------|
| DevOps Lead | [Name] | [Email/Phone] |
| CTO | [Name] | [Email/Phone] |
| AWS Support | - | AWS Console Support Case |
| Let's Encrypt | - | community@letsencrypt.org |

---

## 11. Appendices

### 11.1 Certificate Chain

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        CERTIFICATE CHAIN                                 │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  Root CA: ISRG Root X1 (Let's Encrypt)                                  │
│  ├── Validity: 2015-06-04 to 2035-06-04                                 │
│  ├── Serial: 40:01:77:21:4D:69:78:2F:5E:1F:4D:58:6F:48:76:C0:E8:59:7F   │
│  └── SHA-256 Fingerprint:                                               │
│      96:BC:EC:06:26:49:76:F3:74:60:77:9A:AF:88:68:6F                    │
│                                                                          │
│  Intermediate CA: R3 (Let's Encrypt)                                    │
│  ├── Signed by: ISRG Root X1                                            │
│  ├── Validity: 2020-09-04 to 2025-09-15                                 │
│  └── Used for: Signing end-entity certificates                          │
│                                                                          │
│  Leaf Certificate: *.smartdairy.bd                                      │
│  ├── Signed by: R3                                                      │
│  ├── Validity: 90 days (Let's Encrypt standard)                         │
│  └── Subject: CN=*.smartdairy.bd                                        │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

### 11.2 CSR Generation

#### 11.2.1 Generate CSR with OpenSSL

```bash
# Generate private key
openssl genrsa -out smartdairy.bd.key 2048

# Generate CSR
openssl req -new -key smartdairy.bd.key -out smartdairy.bd.csr \
  -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IT/CN=smartdairy.bd"

# Generate CSR with SAN extensions
cat > openssl.cnf <<EOF
[req]
distinguished_name = req_distinguished_name
req_extensions = v3_req
prompt = no

[req_distinguished_name]
C = BD
ST = Dhaka
L = Dhaka
O = Smart Dairy Ltd
OU = IT
CN = smartdairy.bd

[v3_req]
keyUsage = keyEncipherment, dataEncipherment
extendedKeyUsage = serverAuth
subjectAltName = @alt_names

[alt_names]
DNS.1 = smartdairy.bd
DNS.2 = *.smartdairy.bd
DNS.3 = api.smartdairy.bd
DNS.4 = admin.smartdairy.bd
DNS.5 = www.smartdairy.bd
EOF

openssl req -new -key smartdairy.bd.key -out smartdairy.bd.csr -config openssl.cnf
```

#### 11.2.2 Verify CSR

```bash
# View CSR details
openssl req -text -noout -verify -in smartdairy.bd.csr

# Check SAN entries
openssl req -text -noout -in smartdairy.bd.csr | grep -A1 "X509v3 Subject Alternative Name"
```

### 11.3 Certificate Formats

| Format | Extension | Use Case |
|--------|-----------|----------|
| PEM | `.pem`, `.crt`, `.key` | Default for Linux/Kubernetes |
| DER | `.der`, `.cer` | Windows/Java |
| PKCS#12 | `.pfx`, `.p12` | Windows import/export |
| PKCS#7 | `.p7b` | Certificate chains |

```bash
# Convert PEM to PKCS#12
openssl pkcs12 -export \
  -in certificate.crt \
  -inkey private.key \
  -out certificate.pfx \
  -name "SmartDairy Certificate"

# Convert PKCS#12 to PEM
openssl pkcs12 -in certificate.pfx -out certificate.pem -nodes

# Extract certificate only from PKCS#12
openssl pkcs12 -in certificate.pfx -clcerts -nokeys -out certificate.crt

# Extract private key only from PKCS#12
openssl pkcs12 -in certificate.pfx -nocerts -nodes -out private.key
```

### 11.4 TLS Configuration Reference

#### 11.4.1 Cipher Suites

```yaml
# Recommended cipher suites (TLS 1.2+)
nginx.ingress.kubernetes.io/ssl-ciphers: "ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384"
```

#### 11.4.2 TLS Versions

| Version | Status | Recommendation |
|---------|--------|----------------|
| SSL 2.0 | Deprecated | Disabled |
| SSL 3.0 | Deprecated | Disabled |
| TLS 1.0 | Deprecated | Disabled |
| TLS 1.1 | Deprecated | Disabled |
| TLS 1.2 | Supported | Enabled |
| TLS 1.3 | Recommended | Enabled |

### 11.5 Document Templates

#### 11.5.1 New Domain Request Template

```
Domain Certificate Request
==========================
Date: [YYYY-MM-DD]
Requested by: [Name/Team]
Approved by: [DevOps Lead]

Domain Details:
- Domain Name: [subdomain.smartdairy.bd]
- Environment: [dev/staging/production]
- Service: [Application name]
- Certificate Type: [wildcard/individual/SAN]
- Issuer: [letsencrypt-staging/letsencrypt-production/CA]

DNS Configuration:
- Route 53 Zone ID: [Z...]
- Record Type: [A/CNAME]
- Target: [ALB/CloudFront/IP]

Security Requirements:
- [ ] HSTS enabled
- [ ] TLS 1.2+ only
- [ ] Rate limiting
- [ ] WAF protection

Additional Notes:
[...]
```

### 11.6 Useful Commands Reference

```bash
# Check certificate expiry from command line
echo | openssl s_client -servername smartdairy.bd -connect smartdairy.bd:443 2>/dev/null | openssl x509 -noout -dates

# Test SSL configuration
nmap --script ssl-enum-ciphers -p 443 smartdairy.bd

# SSL Labs test (external)
# https://www.ssllabs.com/ssltest/analyze.html?d=smartdairy.bd

# Check certificate transparency logs
curl -s "https://crt.sh/?q=%.smartdairy.bd&output=json" | jq .

# Test OCSP stapling
echo | openssl s_client -connect smartdairy.bd:443 -status 2>/dev/null | grep -i "ocsp response"
```

### 11.7 Glossary

| Term | Definition |
|------|------------|
| **ACME** | Automatic Certificate Management Environment - protocol for certificate automation |
| **CA** | Certificate Authority - entity that issues certificates |
| **CN** | Common Name - primary domain in a certificate |
| **CSR** | Certificate Signing Request - request for certificate issuance |
| **DNS-01** | ACME challenge using DNS TXT records |
| **HSTS** | HTTP Strict Transport Security - security header |
| **HTTP-01** | ACME challenge using HTTP file validation |
| **IRSA** | IAM Roles for Service Accounts - AWS EKS feature |
| **mTLS** | Mutual TLS - both client and server authenticate |
| **OCSP** | Online Certificate Status Protocol - certificate revocation check |
| **PKI** | Public Key Infrastructure - system for certificate management |
| **SAN** | Subject Alternative Name - additional domains in certificate |
| **TLS** | Transport Layer Security - cryptographic protocol |

---

## Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | DevOps Engineer | _________________ | _______ |
| Owner | DevOps Lead | _________________ | _______ |
| Reviewer | CTO | _________________ | _______ |

---

*Document Control: This document is controlled and maintained by the DevOps team. All changes must be approved by the DevOps Lead and CTO.*
