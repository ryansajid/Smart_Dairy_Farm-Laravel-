# Smart Dairy Ltd. - Secrets Management Guide

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | F-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |
| **Classification** | INTERNAL USE ONLY |

### Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Lead | Initial document creation |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| DevOps Lead | _________________ | _________________ | ________ |
| CTO | _________________ | _________________ | ________ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Secret Classification](#2-secret-classification)
3. [HashiCorp Vault Architecture](#3-hashicorp-vault-architecture)
4. [Secret Storage](#4-secret-storage)
5. [Secret Retrieval](#5-secret-retrieval)
6. [Dynamic Secrets](#6-dynamic-secrets)
7. [Secret Rotation](#7-secret-rotation)
8. [Access Control](#8-access-control)
9. [Audit & Monitoring](#9-audit--monitoring)
10. [Kubernetes Integration](#10-kubernetes-integration)
11. [CI/CD Integration](#11-cicd-integration)
12. [Environment Variables](#12-environment-variables)
13. [Certificate Management](#13-certificate-management)
14. [Backup & Disaster Recovery](#14-backup--disaster-recovery)
15. [Migration Strategy](#15-migration-strategy)
16. [Security Hardening](#16-security-hardening)
17. [Troubleshooting](#17-troubleshooting)
18. [Appendices](#18-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidance for implementing and managing secrets using HashiCorp Vault at Smart Dairy Ltd. It establishes standards, procedures, and best practices for secure secret lifecycle management across all environments.

### 1.2 Scope

This guide applies to:
- All applications and services developed by Smart Dairy
- Infrastructure components and automation tools
- CI/CD pipelines and deployment processes
- Third-party integrations requiring authentication
- Certificate and encryption key management

### 1.3 Secrets Management Principles

| Principle | Description |
|-----------|-------------|
| **Zero Trust** | No implicit trust; verify every access request |
| **Least Privilege** | Grant minimum necessary permissions |
| **Defense in Depth** | Multiple security layers protecting secrets |
| **Dynamic Credentials** | Short-lived, automatically generated credentials |
| **Audit Everything** | Comprehensive logging of all secret operations |
| **Encryption at Rest & Transit** | All secrets encrypted both at rest and in transit |

### 1.4 Compliance Requirements

- **GDPR**: Article 32 - Security of processing
- **PCI-DSS**: Requirements 3, 4, 8 - Data protection and access control
- **ISO 27001**: A.9 Access control, A.10 Cryptography
- **SOC 2**: CC6.1, CC6.2, CC6.3 - Logical access security

### 1.5 Document Conventions

```
🔴 CRITICAL: Security-critical configuration or operation
🟡 WARNING: Important caution or potential risk
🟢 INFO: Informational note or best practice
💻 CODE: Code example or configuration snippet
📋 TODO: Action item or task to complete
```

---

## 2. Secret Classification

### 2.1 Secret Types Managed

#### 2.1.1 Database Credentials

| Database | Secret Type | Rotation Frequency | Access Pattern |
|----------|-------------|-------------------|----------------|
| PostgreSQL (Primary) | Dynamic credentials | 24 hours | Application connections |
| PostgreSQL (Read Replica) | Dynamic credentials | 24 hours | Read-only queries |
| Redis | Static password | 30 days | Cache operations |
| MongoDB | Dynamic credentials | 24 hours | Analytics data |

#### 2.1.2 API Keys

| Service | Secret Type | Environment | Rotation Frequency |
|---------|-------------|-------------|-------------------|
| Payment Gateway | Static API Key | Production | 90 days |
| SMS Provider | Static API Key | All | 90 days |
| Email Service | Static API Key | All | 90 days |
| Maps API | Static API Key | All | 180 days |
| Analytics API | Static API Key | All | 180 days |

#### 2.1.3 Cryptographic Keys

| Key Type | Algorithm | Usage | Storage |
|----------|-----------|-------|---------|
| JWT Signing | RS256 | API authentication | Transit key |
| Data Encryption | AES-256-GCM | PII encryption | Transit key |
| Key Encryption | RSA-4096 | Key wrapping | Transit key |
| Hashing | Argon2id | Password hashing | Application-level |

#### 2.1.4 OAuth Credentials

| Provider | Secret Type | Usage |
|----------|-------------|-------|
| Google OAuth | Client ID + Secret | User authentication |
| Microsoft Azure | Client ID + Secret | Enterprise SSO |
| Apple Sign-In | Client ID + Private Key | iOS app authentication |

#### 2.1.5 Cloud Credentials

| Provider | Secret Type | Usage | Rotation |
|----------|-------------|-------|----------|
| AWS | IAM credentials | S3 access, Lambda | Dynamic STS |
| Azure | Service Principal | Blob storage | Dynamic |
| GCP | Service Account | Cloud services | Dynamic |

#### 2.1.6 TLS Certificates

| Certificate Type | Validity | Auto-Renewal | Usage |
|------------------|----------|--------------|-------|
| Wildcard (*.smartdairy.com) | 90 days | Yes | Web services |
| API Gateway | 90 days | Yes | API endpoints |
| Internal mTLS | 1 year | Yes | Service mesh |
| Database | 1 year | Yes | Database encryption |

### 2.2 Secret Classification Levels

```
┌─────────────────────────────────────────────────────────────┐
│                    SECRET CLASSIFICATION                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  🔴 CRITICAL                                                │
│     • Root/admin credentials                                │
│     • Master encryption keys                                │
│     • Production database passwords                         │
│     • TLS private keys                                      │
│     Access: C-level + Security Lead only                    │
│     Rotation: 30 days or on compromise                      │
│                                                             │
│  🟡 HIGH                                                    │
│     • API keys (Payment, Production)                        │
│     • Service account credentials                           │
│     • Application secrets                                   │
│     Access: DevOps Lead + Team Leads                        │
│     Rotation: 90 days                                       │
│                                                             │
│  🟢 MEDIUM                                                  │
│     • Development API keys                                  │
│     • Non-production credentials                            │
│     • Monitoring tokens                                     │
│     Access: Development team                                │
│     Rotation: 180 days                                      │
│                                                             │
│  ⚪ LOW                                                     │
│     • Public API keys (client-side)                         │n│     • Feature flags                                         │
│     Access: All developers                                  │
│     Rotation: Annual                                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. HashiCorp Vault Architecture

### 3.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY VAULT ARCH                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│   ┌─────────────┐    ┌─────────────┐    ┌─────────────┐            │
│   │   App Pods  │    │   App Pods  │    │   App Pods  │            │
│   │  (Frontend) │    │   (API)     │    │  (Workers)  │            │
│   └──────┬──────┘    └──────┬──────┘    └──────┬──────┘            │
│          │                  │                  │                   │
│          └──────────────────┼──────────────────┘                   │
│                             │                                       │
│                    ┌────────▼────────┐                              │
│                    │  Vault Agent    │                              │
│                    │  (Sidecar)      │                              │
│                    └────────┬────────┘                              │
│                             │                                       │
│   ╔═════════════════════════▼═══════════════════════════╗           │
│   ║              KUBERNETES SERVICE MESH                 ║           │
│   ╚═════════════════════════╤═══════════════════════════╝           │
│                             │                                       │
│                    ┌────────▼────────┐                              │
│                    │  VAULT CLUSTER   │                              │
│                    │  ┌─────┐┌─────┐ │                              │
│                    │  │Node1││Node2│ │                              │
│                    │  └──┬──┘└──┬──┘ │                              │
│                    │     └──►◄──┘    │                              │
│                    │        │        │                              │
│                    │     ┌──▼──┐     │                              │
│                    │     │Node3│     │                              │
│                    │     └─────┘     │                              │
│                    └────────┬────────┘                              │
│                             │                                       │
│                    ┌────────▼────────┐                              │
│                    │  HA Backend      │                              │
│                    │  (Raft/etcd)    │                              │
│                    └─────────────────┘                              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Vault Deployment Configuration

#### 3.2.1 Docker Compose Deployment (Development)

```yaml
# vault-docker-compose.yml
version: '3.8'

services:
  vault:
    image: hashicorp/vault:1.15.0
    container_name: vault-dev
    ports:
      - "8200:8200"
    environment:
      VAULT_DEV_ROOT_TOKEN_ID: ${VAULT_DEV_ROOT_TOKEN}
      VAULT_DEV_LISTEN_ADDRESS: 0.0.0.0:8200
      VAULT_ADDR: http://0.0.0.0:8200
    volumes:
      - vault-data:/vault/file
      - ./vault/config:/vault/config:ro
      - ./vault/policies:/vault/policies:ro
    cap_add:
      - IPC_LOCK
    command: server -dev -dev-root-token-id="${VAULT_DEV_ROOT_TOKEN}"
    networks:
      - vault-network

  vault-agent:
    image: hashicorp/vault:1.15.0
    container_name: vault-agent-dev
    depends_on:
      - vault
    volumes:
      - ./vault/agent:/vault/agent:ro
      - vault-agent-sink:/vault/sink
    environment:
      VAULT_ADDR: http://vault:8200
    command: agent -config=/vault/agent/agent-config.hcl
    networks:
      - vault-network

volumes:
  vault-data:
  vault-agent-sink:

networks:
  vault-network:
    driver: bridge
```

#### 3.2.2 Kubernetes Deployment (Production)

```yaml
# vault-namespace.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: vault
  labels:
    name: vault
    pod-security.kubernetes.io/enforce: privileged
---
# vault-server.yaml
apiVersion: apps/v1
kind: StatefulSet
metadata:
  name: vault
  namespace: vault
  labels:
    app.kubernetes.io/name: vault
    app.kubernetes.io/instance: vault
spec:
  serviceName: vault-internal
  replicas: 3
  selector:
    matchLabels:
      app.kubernetes.io/name: vault
      app.kubernetes.io/instance: vault
  template:
    metadata:
      labels:
        app.kubernetes.io/name: vault
        app.kubernetes.io/instance: vault
    spec:
      affinity:
        podAntiAffinity:
          requiredDuringSchedulingIgnoredDuringExecution:
            - labelSelector:
                matchLabels:
                  app.kubernetes.io/name: vault
                  app.kubernetes.io/instance: vault
              topologyKey: kubernetes.io/hostname
      containers:
        - name: vault
          image: hashicorp/vault:1.15.0
          imagePullPolicy: IfNotPresent
          command:
            - "sh"
            - "-c"
            - |
              exec vault server -config=/vault/config/vault.hcl
          securityContext:
            capabilities:
              add:
                - IPC_LOCK
          ports:
            - containerPort: 8200
              name: http
            - containerPort: 8201
              name: internal
          resources:
            requests:
              memory: "512Mi"
              cpu: "500m"
            limits:
              memory: "2Gi"
              cpu: "2000m"
          volumeMounts:
            - name: config
              mountPath: /vault/config
            - name: data
              mountPath: /vault/data
            - name: tls
              mountPath: /vault/tls
            - name: plugins
              mountPath: /vault/plugins
          env:
            - name: VAULT_ADDR
              value: "https://127.0.0.1:8200"
            - name: VAULT_CACERT
              value: /vault/tls/ca.crt
            - name: POD_IP
              valueFrom:
                fieldRef:
                  fieldPath: status.podIP
            - name: VAULT_CLUSTER_ADDR
              value: "https://$(POD_IP):8201"
          livenessProbe:
            httpGet:
              path: /v1/sys/health?standbyok=true&sealedcode=200&uninitcode=200
              port: 8200
              scheme: HTTPS
            initialDelaySeconds: 30
            periodSeconds: 10
          readinessProbe:
            httpGet:
              path: /v1/sys/health?standbyok=true&sealedcode=200&uninitcode=200
              port: 8200
              scheme: HTTPS
            initialDelaySeconds: 5
            periodSeconds: 5
      volumes:
        - name: config
          configMap:
            name: vault-config
        - name: tls
          secret:
            secretName: vault-tls
        - name: plugins
          emptyDir: {}
  volumeClaimTemplates:
    - metadata:
        name: data
      spec:
        accessModes:
          - ReadWriteOnce
        resources:
          requests:
            storage: 10Gi
        storageClassName: fast-ssd
---
# vault-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: vault-config
  namespace: vault
data:
  vault.hcl: |
    ui = true
    
    listener "tcp" {
      address     = "0.0.0.0:8200"
      tls_cert_file = "/vault/tls/tls.crt"
      tls_key_file  = "/vault/tls/tls.key"
      tls_client_ca_file = "/vault/tls/ca.crt"
      
      tls_disable_client_certs = false
      tls_require_and_verify_client_cert = false
    }
    
    storage "raft" {
      path    = "/vault/data"
      node_id = "vault-${HOSTNAME}"
      
      retry_leader_election = true
      
      autopilot {
        cleanup_dead_servers = true
        last_contact_threshold = "10s"
        max_trailing_logs = 250
        min_quorum = 2
        server_stabilization_time = "10s"
      }
    }
    
    seal "awskms" {
      region     = "ap-southeast-1"
      kms_key_id = "arn:aws:kms:ap-southeast-1:ACCOUNT_ID:key/KEY_ID"
    }
    
    service_registration "kubernetes" {}
    
    telemetry {
      prometheus_retention_time = "30s"
      disable_hostname = true
    }
    
    api_addr = "https://vault.vault.svc.cluster.local:8200"
    cluster_addr = "https://$(POD_IP):8201"
    
    log_level = "Info"
    log_format = "json"
    
    default_lease_ttl = "768h"
    max_lease_ttl = "8760h"
    
    plugin_directory = "/vault/plugins"
---
# vault-service.yaml
apiVersion: v1
kind: Service
metadata:
  name: vault
  namespace: vault
  labels:
    app.kubernetes.io/name: vault
spec:
  type: ClusterIP
  ports:
    - port: 8200
      targetPort: 8200
      protocol: TCP
      name: http
  selector:
    app.kubernetes.io/name: vault
---
# vault-internal-service.yaml
apiVersion: v1
kind: Service
metadata:
  name: vault-internal
  namespace: vault
  labels:
    app.kubernetes.io/name: vault
  annotations:
    service.alpha.kubernetes.io/tolerate-unready-endpoints: "true"
spec:
  clusterIP: None
  publishNotReadyAddresses: true
  ports:
    - port: 8200
      name: http
    - port: 8201
      name: internal
  selector:
    app.kubernetes.io/name: vault
```

### 3.3 Vault Components

| Component | Purpose | Configuration |
|-----------|---------|---------------|
| **Vault Server** | Core secret storage and management | HA mode with Raft backend |
| **Vault Agent** | Client-side secret retrieval | Sidecar/Init container pattern |
| **Transit Engine** | Encryption as a service | AES-256-GCM algorithm |
| **KV Store** | Static secret storage | Version 2 with versioning |
| **Database Engine** | Dynamic database credentials | PostgreSQL, MySQL, MongoDB |
| **PKI Engine** | Certificate management | Internal CA for mTLS |
| **AWS/GCP/Azure** | Cloud dynamic credentials | STS token generation |
| **Kubernetes Auth** | K8s service account auth | JWT validation |
| **AppRole Auth** | Application authentication | Role ID + Secret ID |

### 3.4 High Availability Configuration

```
┌─────────────────────────────────────────────────────────────────┐
│                     VAULT HA ARCHITECTURE                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌─────────────────────────────────────────────────────────┐   │
│   │                     LOAD BALANCER                        │   │
│   │              (TLS termination, health checks)            │   │
│   └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│           ┌──────────────────┼──────────────────┐              │
│           │                  │                  │              │
│    ┌──────▼──────┐    ┌──────▼──────┐    ┌──────▼──────┐      │
│    │  Vault-0    │◄──►│  Vault-1    │◄──►│  Vault-2    │      │
│    │   (Active)  │    │  (Standby)  │    │  (Standby)  │      │
│    └──────┬──────┘    └─────────────┘    └─────────────┘      │
│           │                                                     │
│           │  Raft Consensus                                      │
│           ▼                                                     │
│    ┌─────────────┐                                              │
│    │   Storage   │                                              │
│    │  (Raft DB)  │                                              │
│    └─────────────┘                                              │
│                                                                  │
│   Leader Election: Automatic failover < 5 seconds               │
│   Consensus: Raft (3 nodes = tolerate 1 failure)                │
│   Replication: Synchronous, strongly consistent                 │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 4. Secret Storage

### 4.1 KV Secrets Engine (Version 2)

#### 4.1.1 Secret Paths Organization

```
smart-dairy/
├── apps/
│   ├── milk-tracker/
│   │   ├── prod/
│   │   │   ├── database
│   │   │   ├── api-keys
│   │   │   └── jwt-signing-key
│   │   ├── staging/
│   │   └── dev/
│   ├── delivery-service/
│   ├── payment-service/
│   └── notification-service/
├── infrastructure/
│   ├── databases/
│   ├── message-queues/
│   └── monitoring/
├── certificates/
│   ├── tls/
│   └── ca/
└── shared/
    ├── common-settings/
    └── platform-keys/
```

#### 4.1.2 KV Store Configuration

```bash
# Enable KV v2 secrets engine
vault secrets enable -path=smart-dairy/kv kv-v2

# Configure versioning settings
vault kv metadata put -mount=smart-dairy/kv -max-versions=10 -delete-version-after="768h" apps/

# Create secret
vault kv put smart-dairy/kv/apps/milk-tracker/prod/database \
    username="mt_app_user" \
    password="$(openssl rand -base64 32)" \
    host="prod-db.smartdairy.internal" \
    port="5432" \
    database="milk_tracker_prod"

# Read secret
vault kv get smart-dairy/kv/apps/milk-tracker/prod/database

# Read specific version
vault kv get -version=1 smart-dairy/kv/apps/milk-tracker/prod/database

# Update secret (creates new version)
vault kv patch smart-dairy/kv/apps/milk-tracker/prod/database \
    password="$(openssl rand -base64 32)"

# Delete specific version
vault kv delete -versions=2 smart-dairy/kv/apps/milk-tracker/prod/database

# Destroy (permanently delete) specific version
vault kv destroy -versions=2 smart-dairy/kv/apps/milk-tracker/prod/database

# Undelete (recover) deleted version
vault kv undelete -versions=2 smart-dairy/kv/apps/milk-tracker/prod/database
```

### 4.2 Database Credentials

#### 4.2.1 Database Secrets Engine Configuration

```bash
# Enable database secrets engine
vault secrets enable -path=smart-dairy/db database

# Configure PostgreSQL connection
vault write smart-dairy/db/config/milk-tracker-prod \
    plugin_name=postgresql-database-plugin \
    allowed_roles="milk-tracker-readwrite", "milk-tracker-readonly" \
    connection_url="postgresql://{{username}}:{{password}}@prod-db.smartdairy.internal:5432/milk_tracker_prod?sslmode=require" \
    username="vault_admin" \
    password="${VAULT_DB_ADMIN_PASSWORD}"

# Create dynamic role for read/write access
vault write smart-dairy/db/roles/milk-tracker-readwrite \
    db_name=milk-tracker-prod \
    creation_statements="CREATE ROLE \"{{name}}\" WITH LOGIN PASSWORD '{{password}}' VALID UNTIL '{{expiration}}'; \
        GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO \"{{name}}\"; \
        GRANT USAGE ON ALL SEQUENCES IN SCHEMA public TO \"{{name}}\";" \
    revocation_statements="REASSIGN OWNED BY \"{{name}}\" TO vault_admin; DROP OWNED BY \"{{name}}\"; DROP ROLE IF EXISTS \"{{name}}\";" \
    default_ttl="1h" \
    max_ttl="24h"

# Create dynamic role for read-only access
vault write smart-dairy/db/roles/milk-tracker-readonly \
    db_name=milk-tracker-prod \
    creation_statements="CREATE ROLE \"{{name}}\" WITH LOGIN PASSWORD '{{password}}' VALID UNTIL '{{expiration}}'; \
        GRANT SELECT ON ALL TABLES IN SCHEMA public TO \"{{name}}\";" \
    revocation_statements="DROP ROLE IF EXISTS \"{{name}}\";" \
    default_ttl="1h" \
    max_ttl="4h"

# Generate dynamic credentials
vault read smart-dairy/db/creds/milk-tracker-readwrite
```

#### 4.2.2 Database Secret Output Format

```json
{
  "request_id": "8c6b8f8a-...",
  "lease_id": "smart-dairy/db/creds/milk-tracker-readwrite/...",
  "lease_duration": 3600,
  "renewable": true,
  "data": {
    "username": "v-token-milk-track-7h8j9k0l",
    "password": "A1b2C3d4E5f6G7h8I9j0K1l2"
  },
  "warnings": null
}
```

### 4.3 Dynamic Secrets

#### 4.3.1 AWS Dynamic Credentials

```bash
# Enable AWS secrets engine
vault secrets enable -path=smart-dairy/aws aws

# Configure AWS root credentials
vault write smart-dairy/aws/config/root \
    access_key="${AWS_ACCESS_KEY_ID}" \
    secret_key="${AWS_SECRET_ACCESS_KEY}" \
    region="ap-southeast-1"

# Create AWS role for S3 access
vault write smart-dairy/aws/roles/s3-readonly \
    credential_type=iam_user \
    policy_document=-<<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:ListBucket"
      ],
      "Resource": [
        "arn:aws:s3:::smart-dairy-data-prod",
        "arn:aws:s3:::smart-dairy-data-prod/*"
      ]
    }
  ]
}
EOF

# Create AWS role with STS (temporary credentials)
vault write smart-dairy/aws/roles/s3-sts-role \
    credential_type=federation_token \
    iam_tags=Environment=production,Application=milk-tracker \
    policy_document=-<<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "s3:*",
      "Resource": "arn:aws:s3:::smart-dairy-*"
    }
  ]
}
EOF

# Generate AWS credentials
vault read smart-dairy/aws/creds/s3-readonly

# Generate STS credentials
vault read smart-dairy/aws/sts/s3-sts-role ttl=1h
```

#### 4.3.2 Azure Dynamic Service Principals

```bash
# Enable Azure secrets engine
vault secrets enable -path=smart-dairy/azure azure

# Configure Azure credentials
vault write smart-dairy/azure/config \
    subscription_id="${AZURE_SUBSCRIPTION_ID}" \
    tenant_id="${AZURE_TENANT_ID}" \
    client_id="${AZURE_CLIENT_ID}" \
    client_secret="${AZURE_CLIENT_SECRET}" \
    environment="AzurePublicCloud"

# Create Azure role
vault write smart-dairy/azure/roles/blob-contributor \
    application_object_id="" \
    ttl=1h \
    azure_roles=-<<EOF
[
  {
    "role_name": "Storage Blob Data Contributor",
    "scope": "/subscriptions/${AZURE_SUBSCRIPTION_ID}/resourceGroups/smart-dairy-prod/providers/Microsoft.Storage/storageAccounts/smartdairyblob"
  }
]
EOF

# Generate Azure credentials
vault read smart-dairy/azure/creds/blob-contributor
```

---

## 5. Secret Retrieval

### 5.1 Python Integration

#### 5.1.1 Vault Python Client Setup

```python
# vault_client.py
"""
Smart Dairy Vault Client
Handles all Vault interactions for Python applications
"""

import os
import json
import logging
from typing import Dict, Optional, Any
from dataclasses import dataclass
from contextlib import contextmanager

import hvac
from hvac.exceptions import VaultError, Forbidden

logger = logging.getLogger(__name__)


@dataclass
class DatabaseCredentials:
    """Database credentials container"""
    username: str
    password: str
    host: str
    port: int
    database: str
    
    @property
    def connection_string(self) -> str:
        return f"postgresql://{self.username}:{self.password}@{self.host}:{self.port}/{self.database}"


class VaultClient:
    """HashiCorp Vault client for Smart Dairy applications"""
    
    def __init__(self, 
                 vault_addr: Optional[str] = None,
                 vault_token: Optional[str] = None,
                 vault_role: Optional[str] = None,
                 kubernetes_jwt_path: str = "/var/run/secrets/kubernetes.io/serviceaccount/token"):
        """
        Initialize Vault client
        
        Args:
            vault_addr: Vault server URL
            vault_token: Vault token (for token auth)
            vault_role: Vault role (for Kubernetes auth)
            kubernetes_jwt_path: Path to Kubernetes service account token
        """
        self.vault_addr = vault_addr or os.environ.get('VAULT_ADDR', 'https://vault.vault.svc.cluster.local:8200')
        self.vault_token = vault_token or os.environ.get('VAULT_TOKEN')
        self.vault_role = vault_role or os.environ.get('VAULT_ROLE', 'milk-tracker')
        self.kubernetes_jwt_path = kubernetes_jwt_path
        
        self._client: Optional[hvac.Client] = None
        self._authenticate()
    
    def _authenticate(self) -> None:
        """Authenticate with Vault using available method"""
        self._client = hvac.Client(url=self.vault_addr)
        
        # Try Kubernetes auth first (for K8s deployments)
        if os.path.exists(self.kubernetes_jwt_path):
            try:
                self._authenticate_kubernetes()
                logger.info("Authenticated with Vault using Kubernetes auth")
                return
            except Exception as e:
                logger.warning(f"Kubernetes auth failed: {e}")
        
        # Fall back to token auth
        if self.vault_token:
            self._client.token = self.vault_token
            if self._client.is_authenticated():
                logger.info("Authenticated with Vault using token auth")
                return
        
        # Try AppRole auth
        if os.environ.get('VAULT_ROLE_ID') and os.environ.get('VAULT_SECRET_ID'):
            try:
                self._authenticate_approle()
                logger.info("Authenticated with Vault using AppRole auth")
                return
            except Exception as e:
                logger.warning(f"AppRole auth failed: {e}")
        
        raise VaultError("Failed to authenticate with Vault")
    
    def _authenticate_kubernetes(self) -> None:
        """Authenticate using Kubernetes service account"""
        with open(self.kubernetes_jwt_path, 'r') as f:
            jwt = f.read()
        
        self._client.auth.kubernetes.login(
            role=self.vault_role,
            jwt=jwt
        )
    
    def _authenticate_approle(self) -> None:
        """Authenticate using AppRole"""
        self._client.auth.approle.login(
            role_id=os.environ['VAULT_ROLE_ID'],
            secret_id=os.environ['VAULT_SECRET_ID']
        )
    
    def is_authenticated(self) -> bool:
        """Check if client is authenticated"""
        return self._client is not None and self._client.is_authenticated()
    
    def get_static_secret(self, path: str, mount_point: str = "smart-dairy/kv") -> Dict[str, Any]:
        """
        Retrieve static secret from KV store
        
        Args:
            path: Secret path (e.g., 'apps/milk-tracker/prod/database')
            mount_point: KV mount point
            
        Returns:
            Dictionary containing secret data
        """
        try:
            response = self._client.secrets.kv.v2.read_secret_version(
                path=path,
                mount_point=mount_point
            )
            return response['data']['data']
        except VaultError as e:
            logger.error(f"Failed to read secret at {path}: {e}")
            raise
    
    def get_database_credentials(self, role: str, mount_point: str = "smart-dairy/db") -> DatabaseCredentials:
        """
        Generate dynamic database credentials
        
        Args:
            role: Database role name (e.g., 'milk-tracker-readwrite')
            mount_point: Database secrets engine mount point
            
        Returns:
            DatabaseCredentials object
        """
        try:
            response = self._client.secrets.database.generate_credentials(
                name=role,
                mount_point=mount_point
            )
            data = response['data']
            
            # Get connection details from static secret
            conn_info = self.get_static_secret(f'apps/{role.split("-")[0]}/prod/database-connection')
            
            return DatabaseCredentials(
                username=data['username'],
                password=data['password'],
                host=conn_info['host'],
                port=conn_info['port'],
                database=conn_info['database']
            )
        except VaultError as e:
            logger.error(f"Failed to generate database credentials for role {role}: {e}")
            raise
    
    @contextmanager
    def database_connection(self, role: str):
        """
        Context manager for database connections with automatic cleanup
        
        Usage:
            with vault_client.database_connection('milk-tracker-readwrite') as conn:
                cursor = conn.cursor()
                cursor.execute("SELECT * FROM milk_collections")
        """
        import psycopg2
        
        creds = self.get_database_credentials(role)
        conn = None
        lease_id = None
        
        try:
            conn = psycopg2.connect(creds.connection_string)
            yield conn
        finally:
            if conn:
                conn.close()
            # Vault will automatically revoke credentials after TTL
    
    def get_aws_credentials(self, role: str, mount_point: str = "smart-dairy/aws") -> Dict[str, str]:
        """
        Generate dynamic AWS credentials
        
        Args:
            role: AWS role name
            mount_point: AWS secrets engine mount point
            
        Returns:
            Dictionary with access_key, secret_key, and security_token (if STS)
        """
        try:
            response = self._client.secrets.aws.generate_credentials(
                name=role,
                mount_point=mount_point
            )
            return response['data']
        except VaultError as e:
            logger.error(f"Failed to generate AWS credentials for role {role}: {e}")
            raise
    
    def encrypt_data(self, plaintext: str, key_name: str = "app-data", 
                     mount_point: str = "smart-dairy/transit") -> str:
        """
        Encrypt data using Transit engine
        
        Args:
            plaintext: Data to encrypt (base64 encoded)
            key_name: Transit key name
            mount_point: Transit mount point
            
        Returns:
            Ciphertext
        """
        import base64
        
        try:
            encoded = base64.b64encode(plaintext.encode()).decode()
            response = self._client.secrets.transit.encrypt(
                name=key_name,
                plaintext=encoded,
                mount_point=mount_point
            )
            return response['data']['ciphertext']
        except VaultError as e:
            logger.error(f"Failed to encrypt data: {e}")
            raise
    
    def decrypt_data(self, ciphertext: str, key_name: str = "app-data",
                     mount_point: str = "smart-dairy/transit") -> str:
        """
        Decrypt data using Transit engine
        
        Args:
            ciphertext: Encrypted data
            key_name: Transit key name
            mount_point: Transit mount point
            
        Returns:
            Decrypted plaintext
        """
        import base64
        
        try:
            response = self._client.secrets.transit.decrypt(
                name=key_name,
                ciphertext=ciphertext,
                mount_point=mount_point
            )
            decoded = base64.b64decode(response['data']['plaintext'])
            return decoded.decode()
        except VaultError as e:
            logger.error(f"Failed to decrypt data: {e}")
            raise
    
    def sign_jwt(self, claims: Dict[str, Any], key_name: str = "jwt-signing",
                 mount_point: str = "smart-dairy/transit") -> str:
        """
        Sign JWT using Transit engine
        
        Args:
            claims: JWT claims
            key_name: Signing key name
            mount_point: Transit mount point
            
        Returns:
            Signed JWT token
        """
        import jwt
        import json
        import base64
        
        try:
            # Get public key for signing algorithm
            key_info = self._client.secrets.transit.read_key(
                name=key_name,
                mount_point=mount_point
            )
            
            # Create JWT header and payload
            header = {"alg": "RS256", "typ": "JWT"}
            payload = claims
            
            # Sign using Transit
            signing_input = f"{base64.urlsafe_b64encode(json.dumps(header).encode()).decode().rstrip('=')}.{base64.urlsafe_b64encode(json.dumps(payload).encode()).decode().rstrip('=')}"
            
            response = self._client.secrets.transit.sign_data(
                name=key_name,
                hash_algorithm="sha2-256",
                input=base64.b64encode(signing_input.encode()).decode(),
                mount_point=mount_point,
                signature_algorithm="pkcs1v15"
            )
            
            signature = response['data']['signature'].split(":")[1]  # Remove 'vault:v1:' prefix
            return f"{signing_input}.{signature}"
        except Exception as e:
            logger.error(f"Failed to sign JWT: {e}")
            raise


# Singleton instance for reuse
_vault_client: Optional[VaultClient] = None


def get_vault_client() -> VaultClient:
    """Get or create Vault client singleton"""
    global _vault_client
    if _vault_client is None:
        _vault_client = VaultClient()
    return _vault_client
```

#### 5.1.2 FastAPI Integration Example

```python
# main.py
from fastapi import FastAPI, Depends, HTTPException
from contextlib import asynccontextmanager

from vault_client import get_vault_client, DatabaseCredentials


@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan handler"""
    # Startup: Verify Vault connection
    vault = get_vault_client()
    if not vault.is_authenticated():
        raise RuntimeError("Failed to authenticate with Vault")
    print("✅ Vault connection established")
    yield
    # Shutdown
    print("👋 Shutting down")


app = FastAPI(title="Smart Dairy API", lifespan=lifespan)


def get_db_connection():
    """Dependency for database connections"""
    vault = get_vault_client()
    with vault.database_connection('milk-tracker-readwrite') as conn:
        yield conn


@app.get("/api/v1/milk-collections")
def list_milk_collections(conn=Depends(get_db_connection)):
    """List all milk collections"""
    with conn.cursor() as cursor:
        cursor.execute("SELECT * FROM milk_collections ORDER BY collection_date DESC")
        columns = [desc[0] for desc in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]
    return {"data": results}


@app.post("/api/v1/milk-collections")
def create_milk_collection(data: dict, conn=Depends(get_db_connection)):
    """Create a new milk collection record"""
    with conn.cursor() as cursor:
        cursor.execute(
            "INSERT INTO milk_collections (farmer_id, quantity, quality_grade, collection_date) VALUES (%s, %s, %s, NOW()) RETURNING id",
            (data['farmer_id'], data['quantity'], data['quality_grade'])
        )
        conn.commit()
        new_id = cursor.fetchone()[0]
    return {"id": new_id, "status": "created"}
```

### 5.2 Application Configuration with Vault

```python
# config.py
"""Application configuration with Vault integration"""

import os
from functools import lru_cache
from pydantic_settings import BaseSettings
from pydantic import Field

from vault_client import get_vault_client


class Settings(BaseSettings):
    """Application settings"""
    
    # Vault configuration
    vault_addr: str = Field(default="https://vault.vault.svc.cluster.local:8200")
    vault_role: str = Field(default="milk-tracker")
    
    # Application
    app_name: str = Field(default="milk-tracker")
    app_env: str = Field(default="production")
    debug: bool = Field(default=False)
    
    # Database (populated from Vault)
    db_host: str = Field(default="")
    db_port: int = Field(default=5432)
    db_name: str = Field(default="")
    db_user: str = Field(default="")
    db_password: str = Field(default="")
    
    # API Keys (populated from Vault)
    payment_gateway_key: str = Field(default="")
    sms_api_key: str = Field(default="")
    email_api_key: str = Field(default="")
    
    # JWT (populated from Vault)
    jwt_secret: str = Field(default="")
    jwt_algorithm: str = Field(default="RS256")
    jwt_expiration: int = Field(default=3600)
    
    class Config:
        env_file = ".env"
        case_sensitive = False
    
    def load_from_vault(self):
        """Load secrets from Vault"""
        vault = get_vault_client()
        env = self.app_env
        app = self.app_name
        
        # Load database connection info
        db_info = vault.get_static_secret(f'apps/{app}/{env}/database-connection')
        self.db_host = db_info['host']
        self.db_port = db_info['port']
        self.db_name = db_info['database']
        
        # Load API keys
        try:
            payment_keys = vault.get_static_secret(f'apps/{app}/{env}/payment-gateway')
            self.payment_gateway_key = payment_keys['api_key']
        except Exception:
            pass
        
        try:
            sms_keys = vault.get_static_secret(f'shared/{env}/sms-provider')
            self.sms_api_key = sms_keys['api_key']
        except Exception:
            pass
        
        try:
            email_keys = vault.get_static_secret(f'shared/{env}/email-provider')
            self.email_api_key = email_keys['api_key']
        except Exception:
            pass


@lru_cache()
def get_settings() -> Settings:
    """Get cached settings instance"""
    settings = Settings()
    settings.load_from_vault()
    return settings
```

---

## 6. Dynamic Secrets

### 6.1 Database Dynamic Secrets

#### 6.1.1 PostgreSQL Dynamic Role Configuration

```sql
-- Initial database setup for Vault integration
-- Run as postgres superuser

-- Create Vault admin role
CREATE ROLE vault_admin WITH LOGIN PASSWORD '${VAULT_ADMIN_PASSWORD}' SUPERUSER;

-- Create template roles
CREATE ROLE milk_tracker_app;
GRANT CONNECT ON DATABASE milk_tracker_prod TO milk_tracker_app;
GRANT USAGE ON SCHEMA public TO milk_tracker_app;

CREATE ROLE milk_tracker_readonly;
GRANT CONNECT ON DATABASE milk_tracker_prod TO milk_tracker_readonly;
GRANT USAGE ON SCHEMA public TO milk_tracker_readonly;

-- Grant table permissions to template roles
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO milk_tracker_app;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA public TO milk_tracker_app;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO milk_tracker_app;

GRANT SELECT ON ALL TABLES IN SCHEMA public TO milk_tracker_readonly;

-- Set default privileges for future objects
ALTER DEFAULT PRIVILEGES IN SCHEMA public
    GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO milk_tracker_app;
ALTER DEFAULT PRIVILEGES IN SCHEMA public
    GRANT USAGE ON SEQUENCES TO milk_tracker_app;
    
ALTER DEFAULT PRIVILEGES IN SCHEMA public
    GRANT SELECT ON TABLES TO milk_tracker_readonly;
```

#### 6.1.2 Connection Pooling with PgBouncer

```ini
; pgbouncer.ini
[databases]
milk_tracker_prod = host=prod-db.smartdairy.internal port=5432 dbname=milk_tracker_prod

[pgbouncer]
listen_port = 6432
listen_addr = 0.0.0.0
auth_type = hba
auth_hba_file = /etc/pgbouncer/pg_hba.conf
auth_user = pgbouncer_auth
auth_query = SELECT username, password FROM pgbouncer.get_auth($1)

; Pool settings for dynamic credentials
pool_mode = transaction
max_client_conn = 10000
default_pool_size = 50
min_pool_size = 10
reserve_pool_size = 25
reserve_pool_timeout = 3

; Connection settings for short-lived credentials
server_idle_timeout = 600
server_lifetime = 3600
server_connect_timeout = 5
server_login_retry = 5

; Logging
log_connections = 1
log_disconnections = 1
log_pooler_errors = 1
stats_period = 60

; TLS
client_tls_sslmode = require
client_tls_key_file = /etc/pgbouncer/server.key
client_tls_cert_file = /etc/pgbouncer/server.crt
client_tls_ca_file = /etc/pgbouncer/ca.crt
```

### 6.2 Cloud Dynamic Credentials

#### 6.2.1 AWS STS Federation Token

```bash
# Create role with federation token (STS)
vault write smart-dairy/aws/roles/s3-federated \
    credential_type=federation_token \
    ttl=1h \
    iam_groups="smart-dairy-s3-access" \
    policy_document=-<<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "S3Access",
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:PutObject",
        "s3:DeleteObject",
        "s3:ListBucket"
      ],
      "Resource": [
        "arn:aws:s3:::smart-dairy-*",
        "arn:aws:s3:::smart-dairy-*/*"
      ]
    }
  ]
}
EOF

# Read credentials
vault read smart-dairy/aws/creds/s3-federated

# Output:
# Key                Value
# ---                -----
# access_key         ASIA...
# secret_key         wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
# security_token     FwoGZXIvYXdzEBYaDK...
```

#### 6.2.2 Python AWS Integration

```python
# aws_vault_client.py
import boto3
from botocore.credentials import RefreshableCredentials
from botocore.session import get_session
from datetime import datetime
from vault_client import get_vault_client


class VaultAWSProvider:
    """AWS credentials provider using Vault dynamic secrets"""
    
    def __init__(self, vault_role: str = "s3-federated"):
        self.vault_role = vault_role
        self.vault = get_vault_client()
    
    def get_credentials(self):
        """Fetch fresh credentials from Vault"""
        creds = self.vault.get_aws_credentials(self.vault_role)
        
        return {
            'access_key': creds['access_key'],
            'secret_key': creds['secret_key'],
            'token': creds.get('security_token'),
            'expiry_time': datetime.utcnow().isoformat()  # Vault manages TTL
        }
    
    def get_session(self):
        """Get boto3 session with refreshable credentials"""
        session = get_session()
        
        refreshable_credentials = RefreshableCredentials.create_from_metadata(
            metadata=self.get_credentials(),
            refresh_using=self.get_credentials,
            method='vault-dynamic'
        )
        
        session._credentials = refreshable_credentials
        return boto3.Session(botocore_session=session)


# Usage
aws_provider = VaultAWSProvider("s3-federated")
s3_client = aws_provider.get_session().client('s3')

# This will automatically refresh credentials when needed
s3_client.upload_file('local_file.txt', 'smart-dairy-data', 'uploads/file.txt')
```

---

## 7. Secret Rotation

### 7.1 Automated Rotation

#### 7.1.1 Database Secret Rotation Script

```python
# rotation/db_rotation.py
"""
Database secret rotation automation for Smart Dairy
"""

import os
import sys
import logging
import psycopg2
from datetime import datetime, timedelta
from typing import List, Tuple

import hvac
from kubernetes import client, config

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class DatabaseSecretRotator:
    """Rotates database credentials using Vault"""
    
    def __init__(self):
        self.vault_client = hvac.Client(url=os.environ['VAULT_ADDR'])
        self.vault_client.token = os.environ['VAULT_TOKEN']
        
        # Load K8s config
        config.load_incluster_config()
        self.k8s_api = client.CoreV1Api()
    
    def rotate_static_password(self, vault_path: str, k8s_secret: str, 
                               namespace: str = "milk-tracker") -> str:
        """
        Rotate static database password
        
        1. Generate new password
        2. Update database
        3. Update Vault
        4. Update K8s secret
        5. Trigger rolling restart
        """
        import secrets
        
        new_password = secrets.token_urlsafe(32)
        
        # Get current credentials
        current = self.vault_client.secrets.kv.v2.read_secret_version(
            path=vault_path,
            mount_point="smart-dairy/kv"
        )['data']['data']
        
        old_password = current['password']
        username = current['username']
        
        try:
            # Update database password
            self._update_db_password(
                host=current['host'],
                port=current['port'],
                database=current['database'],
                admin_user=os.environ['DB_ADMIN_USER'],
                admin_pass=os.environ['DB_ADMIN_PASSWORD'],
                target_user=username,
                new_password=new_password
            )
            
            # Update Vault
            self.vault_client.secrets.kv.v2.create_or_update_secret(
                path=vault_path,
                mount_point="smart-dairy/kv",
                secret={**current, 'password': new_password, 'rotated_at': datetime.utcnow().isoformat()}
            )
            
            # Update K8s secret
            self._update_k8s_secret(
                name=k8s_secret,
                namespace=namespace,
                data={'DB_PASSWORD': new_password}
            )
            
            logger.info(f"Successfully rotated password for {username}")
            
            # Grace period: keep old password valid temporarily
            self._schedule_old_password_cleanup(
                host=current['host'],
                username=username,
                old_password=old_password,
                delay_minutes=5
            )
            
            return new_password
            
        except Exception as e:
            logger.error(f"Rotation failed: {e}")
            # Rollback: restore old password
            self._update_db_password(
                host=current['host'],
                port=current['port'],
                database=current['database'],
                admin_user=os.environ['DB_ADMIN_USER'],
                admin_pass=os.environ['DB_ADMIN_PASSWORD'],
                target_user=username,
                new_password=old_password
            )
            raise
    
    def _update_db_password(self, host: str, port: int, database: str,
                           admin_user: str, admin_pass: str,
                           target_user: str, new_password: str):
        """Update password in PostgreSQL"""
        conn = psycopg2.connect(
            host=host, port=port, database=database,
            user=admin_user, password=admin_pass
        )
        try:
            with conn.cursor() as cur:
                cur.execute(
                    "ALTER USER \"%s\" WITH PASSWORD '%s'",
                    (target_user, new_password)
                )
            conn.commit()
        finally:
            conn.close()
    
    def _update_k8s_secret(self, name: str, namespace: str, data: dict):
        """Update Kubernetes secret"""
        import base64
        
        encoded_data = {
            k: base64.b64encode(v.encode()).decode()
            for k, v in data.items()
        }
        
        try:
            self.k8s_api.patch_namespaced_secret(
                name=name,
                namespace=namespace,
                body={'data': encoded_data}
            )
        except client.exceptions.ApiException as e:
            if e.status == 404:
                self.k8s_api.create_namespaced_secret(
                    namespace=namespace,
                    body=client.V1Secret(
                        metadata=client.V1ObjectMeta(name=name),
                        data=encoded_data
                    )
                )
    
    def _schedule_old_password_cleanup(self, host: str, username: str, 
                                       old_password: str, delay_minutes: int):
        """Schedule cleanup of old password after grace period"""
        # This would typically create a K8s Job or use a delayed queue
        logger.info(f"Scheduled cleanup of old password for {username} in {delay_minutes} minutes")


def rotate_all_database_secrets():
    """Rotate all database secrets in rotation schedule"""
    rotator = DatabaseSecretRotator()
    
    rotation_schedule = [
        ('apps/milk-tracker/prod/database', 'db-credentials', 'milk-tracker'),
        ('apps/delivery-service/prod/database', 'delivery-db-creds', 'delivery-service'),
        ('apps/payment-service/prod/database', 'payment-db-creds', 'payment-service'),
    ]
    
    for vault_path, k8s_secret, namespace in rotation_schedule:
        try:
            rotator.rotate_static_password(vault_path, k8s_secret, namespace)
        except Exception as e:
            logger.error(f"Failed to rotate {vault_path}: {e}")
            # Continue with next secret


if __name__ == "__main__":
    rotate_all_database_secrets()
```

#### 7.1.2 Kubernetes CronJob for Rotation

```yaml
# rotation-cronjob.yaml
apiVersion: batch/v1
kind: CronJob
metadata:
  name: secret-rotation
  namespace: vault
spec:
  schedule: "0 2 * * 0"  # Every Sunday at 2 AM
  concurrencyPolicy: Forbid
  successfulJobsHistoryLimit: 3
  failedJobsHistoryLimit: 3
  jobTemplate:
    spec:
      template:
        spec:
          serviceAccountName: secret-rotator
          containers:
            - name: rotator
              image: smart-dairy/secret-rotator:v1.0.0
              command:
                - python
                - /app/rotation/db_rotation.py
              env:
                - name: VAULT_ADDR
                  value: "https://vault.vault.svc.cluster.local:8200"
                - name: VAULT_TOKEN
                  valueFrom:
                    secretKeyRef:
                      name: rotator-vault-token
                      key: token
                - name: DB_ADMIN_USER
                  valueFrom:
                    secretKeyRef:
                      name: db-admin-credentials
                      key: username
                - name: DB_ADMIN_PASSWORD
                  valueFrom:
                    secretKeyRef:
                      name: db-admin-credentials
                      key: password
              resources:
                requests:
                  memory: "128Mi"
                  cpu: "100m"
                limits:
                  memory: "256Mi"
                  cpu: "200m"
          restartPolicy: OnFailure
---
apiVersion: v1
kind: ServiceAccount
metadata:
  name: secret-rotator
  namespace: vault
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: secret-rotator
rules:
  - apiGroups: [""]
    resources: ["secrets"]
    verbs: ["get", "list", "create", "update", "patch"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: secret-rotator
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: ClusterRole
  name: secret-rotator
subjects:
  - kind: ServiceAccount
    name: secret-rotator
    namespace: vault
```

### 7.2 Manual Rotation Procedures

#### 7.2.1 Emergency Secret Rotation

```bash
#!/bin/bash
# emergency_rotation.sh
# Emergency secret rotation procedure

set -euo pipefail

SECRET_PATH="${1:-}"
ENVIRONMENT="${2:-prod}"
REASON="${3:-Emergency rotation}"

if [ -z "$SECRET_PATH" ]; then
    echo "Usage: $0 <secret_path> <environment> <reason>"
    echo "Example: $0 apps/milk-tracker/database prod 'Key compromise'"
    exit 1
fi

# Log rotation event
echo "$(date -Iseconds) - Emergency rotation started for $SECRET_PATH" | tee -a /var/log/vault/emergency_rotation.log
echo "Reason: $REASON"

# 1. Revoke all leases for this secret
echo "Step 1: Revoking leases..."
LEASES=$(vault list -format=json sys/leases/lookup/smart-dairy/db/creds/$(basename $SECRET_PATH) 2>/dev/null || echo "[]")
echo "$LEASES" | jq -r '.[]' | while read lease; do
    echo "Revoking lease: $lease"
    vault lease revoke "smart-dairy/db/creds/$(basename $SECRET_PATH)/$lease" || true
done

# 2. Rotate static secret
if vault kv get "smart-dairy/kv/$SECRET_PATH" > /dev/null 2>&1; then
    echo "Step 2: Rotating static secret..."
    
    # Backup current secret
    CURRENT=$(vault kv get -format=json "smart-dairy/kv/$SECRET_PATH")
    echo "$CURRENT" > "/backup/secrets/${SECRET_PATH//\//_}_$(date +%Y%m%d_%H%M%S).json"
    
    # Generate new secret
    NEW_PASSWORD=$(openssl rand -base64 48)
    
    # Update database (if applicable)
    DB_HOST=$(echo "$CURRENT" | jq -r '.data.data.host // empty')
    if [ -n "$DB_HOST" ]; then
        echo "Updating database password..."
        PGPASSWORD="$DB_ADMIN_PASSWORD" psql \
            -h "$DB_HOST" \
            -U vault_admin \
            -c "ALTER USER \"$(echo "$CURRENT" | jq -r '.data.data.username')\" WITH PASSWORD '$NEW_PASSWORD';"
    fi
    
    # Update Vault
    vault kv patch "smart-dairy/kv/$SECRET_PATH" password="$NEW_PASSWORD" rotated_at="$(date -Iseconds)" rotation_reason="$REASON"
fi

# 3. Force application restart
if [ "$ENVIRONMENT" = "prod" ]; then
    echo "Step 3: Triggering rolling restart..."
    kubectl rollout restart deployment -l app=$(basename $SECRET_PATH) -n $(dirname $SECRET_PATH | cut -d'/' -f2)
    kubectl rollout status deployment -l app=$(basename $SECRET_PATH) -n $(dirname $SECRET_PATH | cut -d'/' -f2) --timeout=5m
fi

# 4. Verify
sleep 10
if vault kv get "smart-dairy/kv/$SECRET_PATH" > /dev/null 2>&1; then
    ROTATED_AT=$(vault kv get -format=json "smart-dairy/kv/$SECRET_PATH" | jq -r '.data.data.rotated_at // empty')
    if [ -n "$ROTATED_AT" ]; then
        echo "✅ Rotation successful - new secret active since $ROTATED_AT"
    else
        echo "⚠️ Rotation may have failed - no rotation timestamp found"
    fi
fi

echo "$(date -Iseconds) - Emergency rotation completed" | tee -a /var/log/vault/emergency_rotation.log
```

---

## 8. Access Control

### 8.1 Vault Policies

#### 8.1.1 Application Policies

```hcl
# policies/milk-tracker-prod.hcl
# Policy for Milk Tracker production application

# Allow reading own database credentials
path "smart-dairy/db/creds/milk-tracker-readwrite" {
  capabilities = ["read"]
}

path "smart-dairy/db/creds/milk-tracker-readonly" {
  capabilities = ["read"]
}

# Allow reading own static secrets
path "smart-dairy/kv/data/apps/milk-tracker/prod/*" {
  capabilities = ["read", "list"]
}

# Allow listing (but not reading) shared secrets
path "smart-dairy/kv/metadata/shared/*" {
  capabilities = ["list"]
}

# Allow reading specific shared secrets
path "smart-dairy/kv/data/shared/prod/sms-provider" {
  capabilities = ["read"]
}

path "smart-dairy/kv/data/shared/prod/email-provider" {
  capabilities = ["read"]
}

# Allow encryption/decryption with application key
path "smart-dairy/transit/encrypt/app-data" {
  capabilities = ["update"]
}

path "smart-dairy/transit/decrypt/app-data" {
  capabilities = ["update"]
}

# Allow signing JWTs
path "smart-dairy/transit/sign/jwt-signing" {
  capabilities = ["update"]
}

# Allow AWS credentials for S3
path "smart-dairy/aws/creds/s3-readonly" {
  capabilities = ["read"]
}

# Allow renewing own token
path "auth/token/renew-self" {
  capabilities = ["update"]
}

# Allow looking up own token
path "auth/token/lookup-self" {
  capabilities = ["read"]
}
```

#### 8.1.2 Administrator Policies

```hcl
# policies/admin.hcl
# Full admin access (use sparingly)

path "*" {
  capabilities = ["create", "read", "update", "delete", "list", "sudo"]
}
```

```hcl
# policies/devops.hcl
# DevOps team policy

# Manage application secrets
path "smart-dairy/kv/*" {
  capabilities = ["create", "read", "update", "delete", "list"]
}

# Manage database configuration
path "smart-dairy/db/config/*" {
  capabilities = ["create", "read", "update", "delete", "list"]
}

# Manage database roles
path "smart-dairy/db/roles/*" {
  capabilities = ["create", "read", "update", "delete", "list"]
}

# Revoke database leases
path "smart-dairy/db/revoke/*" {
  capabilities = ["update"]
}

# Manage AWS configuration
path "smart-dairy/aws/config/*" {
  capabilities = ["create", "read", "update", "delete"]
}

path "smart-dairy/aws/roles/*" {
  capabilities = ["create", "read", "update", "delete", "list"]
}

# Manage Transit keys
path "smart-dairy/transit/keys/*" {
  capabilities = ["create", "read", "update", "delete", "list"]
}

# Manage PKI
path "smart-dairy/pki/*" {
  capabilities = ["create", "read", "update", "delete", "list"]
}

# Read system health
path "sys/health" {
  capabilities = ["read", "sudo"]
}

path "sys/seal-status" {
  capabilities = ["read"]
}

# Manage policies (read-only, no sudo)
path "sys/policies/acl/*" {
  capabilities = ["read", "list"]
}

# View audit logs
path "sys/audit" {
  capabilities = ["read", "sudo"]
}

# View metrics
path "sys/metrics" {
  capabilities = ["read"]
}
```

#### 8.1.3 Security Team Policy

```hcl
# policies/security.hcl
# Security team policy - audit and incident response

# Read all secrets (audit only)
path "smart-dairy/kv/*" {
  capabilities = ["read", "list"]
}

# View all auth methods
path "sys/auth" {
  capabilities = ["read", "sudo"]
}

path "sys/auth/*" {
  capabilities = ["read", "list"]
}

# View audit devices
path "sys/audit" {
  capabilities = ["read", "sudo"]
}

path "sys/audit/*" {
  capabilities = ["read", "list"]
}

# View lease information
path "sys/leases/*" {
  capabilities = ["read", "list"]
}

# Revoke leases during incident response
path "sys/leases/revoke/*" {
  capabilities = ["update"]
}

# View policies
path "sys/policies/acl/*" {
  capabilities = ["read", "list"]
}

# View seal status
path "sys/seal-status" {
  capabilities = ["read"]
}

# View health
path "sys/health" {
  capabilities = ["read"]
}

# View metrics
path "sys/metrics" {
  capabilities = ["read"]
}
```

### 8.2 Authentication Methods

#### 8.2.1 Kubernetes Authentication

```bash
# Enable Kubernetes auth method
vault auth enable -path=smart-dairy/k8s kubernetes

# Configure Kubernetes auth
vault write auth/smart-dairy/k8s/config \
    token_reviewer_jwt="$(cat /var/run/secrets/kubernetes.io/serviceaccount/token)" \
    kubernetes_host="https://$KUBERNETES_PORT_443_TCP_ADDR:443" \
    kubernetes_ca_cert="$(cat /var/run/secrets/kubernetes.io/serviceaccount/ca.crt)" \
    issuer="https://kubernetes.default.svc.cluster.local"

# Create role for milk-tracker
vault write auth/smart-dairy/k8s/role/milk-tracker \
    bound_service_account_names="milk-tracker,milk-tracker-sa" \
    bound_service_account_namespaces="milk-tracker" \
    policies="milk-tracker-prod" \
    ttl=1h \
    max_ttl=4h

# Create role for delivery-service
vault write auth/smart-dairy/k8s/role/delivery-service \
    bound_service_account_names="delivery-service,delivery-sa" \
    bound_service_account_namespaces="delivery-service" \
    policies="delivery-service-prod" \
    ttl=1h \
    max_ttl=4h
```

#### 8.2.2 AppRole Authentication

```bash
# Enable AppRole auth for CI/CD
vault auth enable -path=smart-dairy/approle approle

# Create role for CI/CD
vault write auth/smart-dairy/approle/role/cicd \
    token_policies="cicd-policy" \
    secret_id_ttl=1h \
    secret_id_num_uses=1 \
    token_ttl=30m \
    token_max_ttl=1h \
    bind_secret_id=true

# Get Role ID
vault read auth/smart-dairy/approle/role/cicd/role-id

# Generate Secret ID (one-time use)
vault write -f auth/smart-dairy/approle/role/cicd/secret-id
```

#### 8.2.3 GitHub Authentication (for Developers)

```bash
# Enable GitHub auth
vault auth enable -path=smart-dairy/github github

# Configure GitHub organization
vault write auth/smart-dairy/github/config \
    organization="smart-dairy"

# Map teams to policies
vault write auth/smart-dairy/github/map/teams/developers \
    value="developer-policy"

vault write auth/smart-dairy/github/map/teams/devops \
    value="devops"

vault write auth/smart-dairy/github/map/teams/security \
    value="security"
```

---

## 9. Audit & Monitoring

### 9.1 Audit Logging

#### 9.1.1 File Audit Device

```bash
# Enable file audit device
vault audit enable file file_path=/var/log/vault/audit.log

# Verify audit device
vault audit list -detailed

# Format: JSON
# Location: /var/log/vault/audit.log
# Rotation: Daily via logrotate
```

#### 9.1.2 Syslog Audit Device

```bash
# Enable syslog audit device
vault audit enable syslog tag="vault-audit" facility="AUTH"
```

#### 9.1.3 Socket Audit Device (for SIEM)

```bash
# Enable socket audit for Splunk/ELK integration
vault audit enable socket address="splunk-hec.smartdairy.internal:8088" socket_type=tcp
```

### 9.2 Audit Log Format

```json
{
  "time": "2026-01-31T10:30:00.123456789Z",
  "type": "request",
  "auth": {
    "client_token": "hmac-sha256:abc123...",
    "accessor": "hmac-sha256:def456...",
    "display_name": "k8s-milk-tracker",
    "policies": ["milk-tracker-prod", "default"],
    "token_policies": ["milk-tracker-prod", "default"],
    "metadata": {
      "service_account_name": "milk-tracker",
      "service_account_namespace": "milk-tracker",
      "service_account_secret_name": "milk-tracker-token-abc123",
      "role": "milk-tracker"
    },
    "entity_id": "abc-def-123",
    "token_type": "service"
  },
  "request": {
    "operation": "read",
    "path": "smart-dairy/db/creds/milk-tracker-readwrite",
    "remote_address": "10.0.1.25",
    "namespace": {
      "id": "root"
    }
  }
}
```

### 9.3 Monitoring & Alerting

#### 9.3.1 Prometheus Metrics

```yaml
# vault-metrics-servicemonitor.yaml
apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: vault
  namespace: monitoring
  labels:
    app: vault
spec:
  selector:
    matchLabels:
      app.kubernetes.io/name: vault
  namespaceSelector:
    matchNames:
      - vault
  endpoints:
    - port: http
      path: /v1/sys/metrics
      params:
        format:
          - prometheus
      interval: 30s
      scrapeTimeout: 10s
      scheme: https
      tlsConfig:
        insecureSkipVerify: true
      bearerTokenFile: /var/run/secrets/kubernetes.io/serviceaccount/token
```

#### 9.3.2 Key Metrics to Monitor

| Metric | Description | Alert Threshold |
|--------|-------------|-----------------|
| `vault_core_unsealed` | Vault seal status | = 0 (critical) |
| `vault_raft_voters` | Number of Raft voters | < 2 (warning) |
| `vault_token_creation` | Token creation rate | > 100/min (warning) |
| `vault_expire_num_leases` | Total leases | > 100,000 (warning) |
| `vault_audit_log_failure` | Audit log failures | > 0 (critical) |
| `vault_core_active` | Active node status | = 0 for 60s (critical) |

#### 9.3.3 Anomaly Detection Rules

```yaml
# vault-alerting-rules.yaml
groups:
  - name: vault-alerts
    rules:
      - alert: VaultSealed
        expr: vault_core_unsealed{job="vault"} == 0
        for: 0m
        labels:
          severity: critical
        annotations:
          summary: "Vault is sealed"
          description: "Vault instance {{ $labels.instance }} is sealed"
      
      - alert: VaultHighLeaseCount
        expr: vault_expire_num_leases{job="vault"} > 100000
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "High lease count in Vault"
          description: "Vault has {{ $value }} leases, consider cleanup"
      
      - alert: VaultAuditLogFailure
        expr: rate(vault_audit_log_failure_count{job="vault"}[5m]) > 0
        for: 0m
        labels:
          severity: critical
        annotations:
          summary: "Vault audit log failure"
          description: "Vault audit log is failing, Vault may seal"
      
      - alert: VaultTokenCreationSpike
        expr: rate(vault_token_creation_count{job="vault"}[5m]) > 100/60
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "Unusual token creation rate"
          description: "Token creation rate is {{ $value }} per second"
      
      - alert: VaultRaftVoterLow
        expr: vault_raft_voters{job="vault"} < 2
        for: 1m
        labels:
          severity: critical
        annotations:
          summary: "Vault Raft quorum at risk"
          description: "Only {{ $value }} Raft voters available"
```

### 9.4 Security Event Monitoring

```python
# security_monitor.py
"""
Security event monitoring for Vault
"""

import json
import re
from datetime import datetime, timedelta
from collections import defaultdict
import requests


class VaultSecurityMonitor:
    """Monitor Vault audit logs for security events"""
    
    SUSPICIOUS_PATTERNS = [
        (r"path.*secret.*prod", "PROD_SECRET_ACCESS", "medium"),
        (r"operation.*delete.*path.*secret", "SECRET_DELETION", "high"),
        (r"auth.*root", "ROOT_TOKEN_USAGE", "critical"),
        (r"path.*sys/policy.*update", "POLICY_CHANGE", "high"),
        (r"path.*sys/auth.*update", "AUTH_METHOD_CHANGE", "high"),
        (r"errors.*permission denied", "ACCESS_DENIED", "low"),
        (r"errors.*invalid token", "INVALID_TOKEN", "medium"),
    ]
    
    def __init__(self, webhook_url: str):
        self.webhook_url = webhook_url
        self.events_buffer = []
        
    def analyze_log_entry(self, log_entry: dict):
        """Analyze a single audit log entry"""
        events = []
        
        log_str = json.dumps(log_entry)
        
        for pattern, event_type, severity in self.SUSPICIOUS_PATTERNS:
            if re.search(pattern, log_str, re.IGNORECASE):
                event = {
                    "timestamp": log_entry.get("time"),
                    "type": event_type,
                    "severity": severity,
                    "client": log_entry.get("auth", {}).get("display_name"),
                    "path": log_entry.get("request", {}).get("path"),
                    "operation": log_entry.get("request", {}).get("operation"),
                    "remote_ip": log_entry.get("request", {}).get("remote_address"),
                }
                events.append(event)
                
                if severity in ["high", "critical"]:
                    self._send_alert(event)
        
        return events
    
    def detect_anomalies(self, time_window: int = 3600):
        """Detect anomalies over time window"""
        cutoff = datetime.utcnow() - timedelta(seconds=time_window)
        recent_events = [e for e in self.events_buffer 
                        if datetime.fromisoformat(e["timestamp"]) > cutoff]
        
        # Count events by client
        client_counts = defaultdict(int)
        for event in recent_events:
            client_counts[event["client"]] += 1
        
        # Alert on high-frequency clients
        for client, count in client_counts.items():
            if count > 100:
                self._send_alert({
                    "type": "HIGH_FREQUENCY_ACCESS",
                    "severity": "medium",
                    "client": client,
                    "count": count,
                    "message": f"Client {client} made {count} requests in {time_window}s"
                })
        
        # Detect failed auth patterns
        failed_auths = [e for e in recent_events if "invalid" in e["type"].lower()]
        if len(failed_auths) > 10:
            ips = set(e["remote_ip"] for e in failed_auths)
            self._send_alert({
                "type": "BRUTE_FORCE_ATTEMPT",
                "severity": "high",
                "source_ips": list(ips),
                "attempts": len(failed_auths)
            })
    
    def _send_alert(self, event: dict):
        """Send alert to security team"""
        payload = {
            "text": f"🚨 Vault Security Alert: {event['type']}",
            "attachments": [{
                "color": "danger" if event["severity"] == "critical" else "warning",
                "fields": [
                    {"title": "Severity", "value": event["severity"], "short": True},
                    {"title": "Timestamp", "value": event.get("timestamp", "N/A"), "short": True},
                    {"title": "Client", "value": event.get("client", "N/A"), "short": True},
                    {"title": "Path", "value": event.get("path", "N/A"), "short": True},
                ],
                "footer": "Smart Dairy Vault Security",
                "ts": int(datetime.utcnow().timestamp())
            }]
        }
        
        try:
            requests.post(self.webhook_url, json=payload, timeout=5)
        except Exception as e:
            print(f"Failed to send alert: {e}")


if __name__ == "__main__":
    import sys
    
    monitor = VaultSecurityMonitor(
        webhook_url="https://hooks.slack.com/services/YOUR/WEBHOOK/URL"
    )
    
    for line in sys.stdin:
        try:
            log_entry = json.loads(line)
            monitor.analyze_log_entry(log_entry)
        except json.JSONDecodeError:
            continue
```

---

## 10. Kubernetes Integration

### 10.1 Vault Agent Sidecar Pattern

```yaml
# milk-tracker-deployment-with-vault.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: milk-tracker
  namespace: milk-tracker
spec:
  replicas: 3
  selector:
    matchLabels:
      app: milk-tracker
  template:
    metadata:
      labels:
        app: milk-tracker
      annotations:
        vault.hashicorp.com/agent-inject: "true"
        vault.hashicorp.com/role: "milk-tracker"
        vault.hashicorp.com/agent-inject-secret-db: "smart-dairy/db/creds/milk-tracker-readwrite"
        vault.hashicorp.com/agent-inject-template-db: |
          {{ with secret "smart-dairy/db/creds/milk-tracker-readwrite" -}}
          export DB_USERNAME="{{ .Data.username }}"
          export DB_PASSWORD="{{ .Data.password }}"
          {{- end }}
        vault.hashicorp.com/agent-inject-secret-api-keys: "smart-dairy/kv/apps/milk-tracker/prod/api-keys"
        vault.hashicorp.com/agent-inject-template-api-keys: |
          {{ with secret "smart-dairy/kv/apps/milk-tracker/prod/api-keys" -}}
          export PAYMENT_API_KEY="{{ .Data.payment_gateway }}"
          export SMS_API_KEY="{{ .Data.sms_provider }}"
          export EMAIL_API_KEY="{{ .Data.email_provider }}"
          {{- end }}
        vault.hashicorp.com/agent-inject-secret-jwt: "smart-dairy/kv/apps/milk-tracker/prod/jwt"
        vault.hashicorp.com/agent-inject-template-jwt: |
          {{ with secret "smart-dairy/kv/apps/milk-tracker/prod/jwt" -}}
          export JWT_SIGNING_KEY="{{ .Data.signing_key }}"
          export JWT_ALGORITHM="{{ .Data.algorithm }}"
          {{- end }}
        vault.hashicorp.com/agent-pre-populate: "true"
        vault.hashicorp.com/agent-pre-populate-only: "false"
        vault.hashicorp.com/agent-run-as-user: "1000"
        vault.hashicorp.com/agent-requests-cpu: "100m"
        vault.hashicorp.com/agent-limits-cpu: "200m"
        vault.hashicorp.com/agent-requests-mem: "128Mi"
        vault.hashicorp.com/agent-limits-mem: "256Mi"
    spec:
      serviceAccountName: milk-tracker
      containers:
        - name: milk-tracker
          image: smart-dairy/milk-tracker:v2.1.0
          ports:
            - containerPort: 8080
          command:
            - /bin/sh
            - -c
            - |
              # Source Vault-injected secrets
              source /vault/secrets/db
              source /vault/secrets/api-keys
              source /vault/secrets/jwt
              
              # Run application
              exec /app/milk-tracker
          env:
            - name: VAULT_ADDR
              value: "https://vault.vault.svc.cluster.local:8200"
          resources:
            requests:
              memory: "256Mi"
              cpu: "250m"
            limits:
              memory: "512Mi"
              cpu: "500m"
---
apiVersion: v1
kind: ServiceAccount
metadata:
  name: milk-tracker
  namespace: milk-tracker
```

### 10.2 Vault CSI Provider

```yaml
# vault-csi-provider.yaml
apiVersion: secrets-store.csi.x-k8s.io/v1
kind: SecretProviderClass
metadata:
  name: vault-milk-tracker
  namespace: milk-tracker
spec:
  provider: vault
  secretObjects:
    - secretName: milk-tracker-db-credentials
      type: Opaque
      data:
        - objectName: db-username
          key: username
        - objectName: db-password
          key: password
    - secretName: milk-tracker-api-keys
      type: Opaque
      data:
        - objectName: payment-key
          key: payment_api_key
        - objectName: sms-key
          key: sms_api_key
  parameters:
    vaultAddress: "https://vault.vault.svc.cluster.local:8200"
    vaultKubernetesMountPath: "smart-dairy/k8s"
    roleName: "milk-tracker"
    objects: |
      - objectName: "db-username"
        secretPath: "smart-dairy/db/creds/milk-tracker-readwrite"
        secretKey: "username"
      - objectName: "db-password"
        secretPath: "smart-dairy/db/creds/milk-tracker-readwrite"
        secretKey: "password"
      - objectName: "payment-key"
        secretPath: "smart-dairy/kv/apps/milk-tracker/prod/api-keys"
        secretKey: "payment_gateway"
      - objectName: "sms-key"
        secretPath: "smart-dairy/kv/apps/milk-tracker/prod/api-keys"
        secretKey: "sms_provider"
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: milk-tracker-csi
  namespace: milk-tracker
spec:
  replicas: 3
  template:
    spec:
      serviceAccountName: milk-tracker
      containers:
        - name: milk-tracker
          image: smart-dairy/milk-tracker:v2.1.0
          volumeMounts:
            - name: vault-secrets
              mountPath: /mnt/secrets-store
              readOnly: true
          env:
            - name: DB_USERNAME
              valueFrom:
                secretKeyRef:
                  name: milk-tracker-db-credentials
                  key: username
            - name: DB_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: milk-tracker-db-credentials
                  key: password
      volumes:
        - name: vault-secrets
          csi:
            driver: secrets-store.csi.k8s.io
            readOnly: true
            volumeAttributes:
              secretProviderClass: "vault-milk-tracker"
```

### 10.3 Init Container Pattern

```yaml
# init-container-pattern.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: milk-tracker-init
  namespace: milk-tracker
spec:
  template:
    spec:
      serviceAccountName: milk-tracker
      initContainers:
        - name: vault-agent-init
          image: hashicorp/vault:1.15.0
          command:
            - vault
            - agent
            - -config=/etc/vault/config.hcl
            - -exit-after-auth
          volumeMounts:
            - name: vault-config
              mountPath: /etc/vault
            - name: shared-data
              mountPath: /vault/secrets
          env:
            - name: VAULT_ADDR
              value: "https://vault.vault.svc.cluster.local:8200"
      containers:
        - name: milk-tracker
          image: smart-dairy/milk-tracker:v2.1.0
          volumeMounts:
            - name: shared-data
              mountPath: /vault/secrets
              readOnly: true
      volumes:
        - name: vault-config
          configMap:
            name: vault-agent-config
        - name: shared-data
          emptyDir:
            medium: Memory
```

---

## 11. CI/CD Integration

### 11.1 GitLab CI Integration

```yaml
# .gitlab-ci.yml
stages:
  - build
  - test
  - security-scan
  - deploy

variables:
  VAULT_ADDR: "https://vault.smartdairy.internal:8200"
  VAULT_NAMESPACE: "smart-dairy"

# Authenticate with Vault using CI/CD role
.vault-auth:
  before_script:
    - apk add --no-cache curl jq
    - |
      # Authenticate with Vault using JWT
      VAULT_TOKEN=$(curl -s --request POST \
        --data '{"jwt": "'"$CI_JOB_JWT_V2"'", "role": "gitlab-ci"}' \
        ${VAULT_ADDR}/v1/auth/smart-dairy/jwt/login | jq -r '.auth.client_token')
      export VAULT_TOKEN
      echo "Authenticated with Vault"

build:
  stage: build
  extends: .vault-auth
  script:
    # Get Docker registry credentials from Vault
    - |
      DOCKER_CREDS=$(curl -s --header "X-Vault-Token: $VAULT_TOKEN" \
        ${VAULT_ADDR}/v1/smart-dairy/kv/cicd/docker-registry | jq -r '.data.data')
      export DOCKER_USERNAME=$(echo $DOCKER_CREDS | jq -r '.username')
      export DOCKER_PASSWORD=$(echo $DOCKER_CREDS | jq -r '.password')
    - docker login -u $DOCKER_USERNAME -p $DOCKER_PASSWORD registry.smartdairy.internal
    - docker build -t $CI_REGISTRY_IMAGE:$CI_COMMIT_SHA .
    - docker push $CI_REGISTRY_IMAGE:$CI_COMMIT_SHA

test:
  stage: test
  extends: .vault-auth
  script:
    # Get test database credentials
    - |
      DB_CREDS=$(curl -s --header "X-Vault-Token: $VAULT_TOKEN" \
        ${VAULT_ADDR}/v1/smart-dairy/db/creds/test-runner | jq -r '.data')
      export DB_USER=$(echo $DB_CREDS | jq -r '.username')
      export DB_PASS=$(echo $DB_CREDS | jq -r '.password')
    - pytest --db-user=$DB_USER --db-pass=$DB_PASS

security-scan:
  stage: security-scan
  extends: .vault-auth
  script:
    # Get SonarQube token from Vault
    - |
      SONAR_TOKEN=$(curl -s --header "X-Vault-Token: $VAULT_TOKEN" \
        ${VAULT_ADDR}/v1/smart-dairy/kv/cicd/sonarqube | jq -r '.data.data.token')
    - sonar-scanner -Dsonar.login=$SONAR_TOKEN

deploy-staging:
  stage: deploy
  extends: .vault-auth
  environment:
    name: staging
  script:
    # Get kubeconfig from Vault
    - |
      KUBECONFIG_DATA=$(curl -s --header "X-Vault-Token: $VAULT_TOKEN" \
        ${VAULT_ADDR}/v1/smart-dairy/kv/cicd/kubeconfig-staging | jq -r '.data.data.config')
      echo "$KUBECONFIG_DATA" | base64 -d > ~/.kube/config
    - kubectl set image deployment/milk-tracker milk-tracker=$CI_REGISTRY_IMAGE:$CI_COMMIT_SHA
    - kubectl rollout status deployment/milk-tracker

deploy-production:
  stage: deploy
  extends: .vault-auth
  environment:
    name: production
  when: manual
  only:
    - main
  script:
    # Production deployment requires additional approval
    - |
      DEPLOY_KEY=$(curl -s --header "X-Vault-Token: $VAULT_TOKEN" \
        ${VAULT_ADDR}/v1/smart-dairy/kv/cicd/deploy-production | jq -r '.data.data.key')
      # Validate deployment key
      if [ "$DEPLOY_APPROVAL_KEY" != "$DEPLOY_KEY" ]; then
        echo "Production deployment not approved"
        exit 1
      fi
    - kubectl set image deployment/milk-tracker milk-tracker=$CI_REGISTRY_IMAGE:$CI_COMMIT_SHA
    - kubectl rollout status deployment/milk-tracker
```

### 11.2 GitHub Actions Integration

```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    permissions:
      contents: read
      id-token: write
    
    steps:
      - uses: actions/checkout@v4
      
      - name: Import Secrets from Vault
        uses: hashicorp/vault-action@v3
        with:
          url: https://vault.smartdairy.internal:8200
          method: jwt
          path: smart-dairy/github-actions
          role: github-actions
          secrets: |
            smart-dairy/kv/cicd/docker-registry username | DOCKER_USERNAME ;
            smart-dairy/kv/cicd/docker-registry password | DOCKER_PASSWORD ;
            smart-dairy/kv/cicd/kubeconfig-prod config | KUBECONFIG_DATA ;
            smart-dairy/db/creds/deployment username | DB_USERNAME ;
            smart-dairy/db/creds/deployment password | DB_PASSWORD ;
      
      - name: Login to Registry
        run: echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin registry.smartdairy.internal
      
      - name: Build and Push
        run: |
          docker build -t registry.smartdairy.internal/milk-tracker:${{ github.sha }} .
          docker push registry.smartdairy.internal/milk-tracker:${{ github.sha }}
      
      - name: Deploy to Kubernetes
        run: |
          echo "$KUBECONFIG_DATA" | base64 -d > kubeconfig
          export KUBECONFIG=kubeconfig
          kubectl set image deployment/milk-tracker milk-tracker=registry.smartdairy.internal/milk-tracker:${{ github.sha }}
          kubectl rollout status deployment/milk-tracker
```

### 11.3 Jenkins Pipeline

```groovy
// Jenkinsfile
pipeline {
    agent any
    
    environment {
        VAULT_ADDR = 'https://vault.smartdairy.internal:8200'
    }
    
    stages {
        stage('Vault Auth') {
            steps {
                script {
                    // Authenticate with Vault using AppRole
                    def vaultToken = sh(
                        script: """
                            curl -s --request POST \
                                --data '{"role_id": "${env.VAULT_ROLE_ID}", "secret_id": "${env.VAULT_SECRET_ID}"}' \
                                ${VAULT_ADDR}/v1/auth/smart-dairy/approle/login | jq -r '.auth.client_token'
                        """,
                        returnStdout: true
                    ).trim()
                    env.VAULT_TOKEN = vaultToken
                }
            }
        }
        
        stage('Get Secrets') {
            steps {
                script {
                    // Get database credentials
                    def dbCreds = sh(
                        script: """
                            curl -s --header "X-Vault-Token: ${env.VAULT_TOKEN}" \
                                ${VAULT_ADDR}/v1/smart-dairy/db/creds/jenkins | jq -r '.data'
                        """,
                        returnStdout: true
                    ).trim()
                    
                    def json = readJSON text: dbCreds
                    env.DB_USERNAME = json.username
                    env.DB_PASSWORD = json.password
                    
                    // Get Docker registry credentials
                    def dockerCreds = sh(
                        script: """
                            curl -s --header "X-Vault-Token: ${env.VAULT_TOKEN}" \
                                ${VAULT_ADDR}/v1/smart-dairy/kv/cicd/docker-registry | jq -r '.data.data'
                        """,
                        returnStdout: true
                    ).trim()
                    
                    def dockerJson = readJSON text: dockerCreds
                    env.DOCKER_USERNAME = dockerJson.username
                    env.DOCKER_PASSWORD = dockerJson.password
                }
            }
        }
        
        stage('Build') {
            steps {
                sh 'echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin'
                sh 'docker build -t milk-tracker:$BUILD_NUMBER .'
                sh 'docker push milk-tracker:$BUILD_NUMBER'
            }
        }
        
        stage('Test') {
            steps {
                sh 'pytest --db-user=$DB_USERNAME --db-pass=$DB_PASSWORD'
            }
        }
        
        stage('Deploy') {
            steps {
                sh 'kubectl set image deployment/milk-tracker milk-tracker=milk-tracker:$BUILD_NUMBER'
            }
        }
    }
    
    post {
        always {
            // Revoke Vault token
            sh 'curl -s --header "X-Vault-Token: ${VAULT_TOKEN}" --request POST ${VAULT_ADDR}/v1/auth/token/revoke-self || true'
        }
    }
}
```

---

## 12. Environment Variables

### 12.1 Secure Environment Variable Handling

#### 12.1.1 Environment Variable Policy

| Variable Type | Storage | Example |
|--------------|---------|---------|
| Non-sensitive config | ConfigMap / .env file | `APP_NAME`, `LOG_LEVEL` |
| Secrets | Vault + K8s Secrets | `DB_PASSWORD`, `API_KEY` |
| Dynamic secrets | Vault only | `DB_USERNAME` (dynamic) |
| TLS certificates | Vault PKI / cert-manager | `TLS_CERT`, `TLS_KEY` |

#### 12.1.2 .env File Management

```bash
# .env.example - Template (safe to commit)
# Copy to .env.local and fill in values

# Application
APP_NAME=milk-tracker
APP_ENV=development
LOG_LEVEL=info
DEBUG=true

# Database (non-sensitive)
DB_HOST=localhost
DB_PORT=5432
DB_NAME=milk_tracker_dev

# Secrets (use Vault in production)
# DB_USERNAME and DB_PASSWORD will be injected by Vault Agent

# External Services
# PAYMENT_API_KEY - from Vault
# SMS_API_KEY - from Vault
# EMAIL_API_KEY - from Vault
```

```python
# env_loader.py
"""
Secure environment variable loading with Vault integration
"""

import os
import re
from typing import Dict, Optional
from pathlib import Path

from vault_client import get_vault_client


class SecureEnvLoader:
    """Load environment variables securely"""
    
    # Patterns that indicate sensitive values
    SENSITIVE_PATTERNS = [
        re.compile(r'.*password.*', re.I),
        re.compile(r'.*secret.*', re.I),
        re.compile(r'.*key.*', re.I),
        re.compile(r'.*token.*', re.I),
        re.compile(r'.*credential.*', re.I),
        re.compile(r'.*private.*', re.I),
    ]
    
    @staticmethod
    def load_env_file(env_file: str = ".env") -> Dict[str, str]:
        """Load environment variables from file (non-sensitive only)"""
        env_vars = {}
        env_path = Path(env_file)
        
        if not env_path.exists():
            return env_vars
        
        with open(env_path, 'r') as f:
            for line in f:
                line = line.strip()
                if not line or line.startswith('#'):
                    continue
                
                if '=' in line:
                    key, value = line.split('=', 1)
                    key = key.strip()
                    value = value.strip().strip('"\'')
                    
                    # Skip sensitive values in .env files
                    if SecureEnvLoader._is_sensitive(key):
                        print(f"⚠️ Skipping sensitive variable '{key}' from .env file")
                        continue
                    
                    env_vars[key] = value
        
        return env_vars
    
    @staticmethod
    def load_from_vault(secret_path: str, prefix: str = "") -> Dict[str, str]:
        """Load environment variables from Vault"""
        vault = get_vault_client()
        secrets = vault.get_static_secret(secret_path)
        
        env_vars = {}
        for key, value in secrets.items():
            env_key = f"{prefix}{key.upper()}" if prefix else key.upper()
            env_vars[env_key] = str(value)
        
        return env_vars
    
    @staticmethod
    def _is_sensitive(key: str) -> bool:
        """Check if key appears to be sensitive"""
        return any(pattern.match(key) for pattern in SecureEnvLoader.SENSITIVE_PATTERNS)
    
    @classmethod
    def load_all(cls, app_name: str, env: str = "development") -> Dict[str, str]:
        """
        Load environment variables from all sources
        Priority: Vault > Environment > .env file
        """
        env_vars = {}
        
        # 1. Load from .env file (non-sensitive only)
        env_vars.update(cls.load_env_file(".env"))
        env_vars.update(cls.load_env_file(f".env.{env}"))
        
        # 2. Load from Vault (all app secrets)
        try:
            vault_vars = cls.load_from_vault(f'apps/{app_name}/{env}/env')
            env_vars.update(vault_vars)
        except Exception as e:
            print(f"Warning: Could not load secrets from Vault: {e}")
        
        # 3. Override with actual environment variables
        env_vars.update({k: v for k, v in os.environ.items()})
        
        return env_vars


# Usage in application
def initialize_environment():
    """Initialize environment with secure secret loading"""
    app_name = os.environ.get('APP_NAME', 'milk-tracker')
    app_env = os.environ.get('APP_ENV', 'development')
    
    # Load environment variables
    env_vars = SecureEnvLoader.load_all(app_name, app_env)
    
    # Set in os.environ for compatibility
    for key, value in env_vars.items():
        os.environ[key] = value
    
    return env_vars
```

### 12.2 Docker Secrets

```yaml
# docker-compose.secrets.yml
version: '3.8'

services:
  milk-tracker:
    image: smart-dairy/milk-tracker:latest
    secrets:
      - db_password
      - api_keys
      - jwt_signing_key
    environment:
      - DB_PASSWORD_FILE=/run/secrets/db_password
      - API_KEYS_FILE=/run/secrets/api_keys
      - JWT_KEY_FILE=/run/secrets/jwt_signing_key

secrets:
  db_password:
    external: true
  api_keys:
    external: true
  jwt_signing_key:
    external: true
```

```bash
# Create Docker secrets from Vault
DB_PASS=$(vault kv get -field=password smart-dairy/kv/apps/milk-tracker/prod/database)
echo "$DB_PASS" | docker secret create db_password -

API_KEYS=$(vault kv get -format=json smart-dairy/kv/apps/milk-tracker/prod/api-keys | jq -c '.data.data')
echo "$API_KEYS" | docker secret create api_keys -
```

---

## 13. Certificate Management

### 13.1 Vault PKI Setup

```bash
# Enable PKI secrets engine
vault secrets enable -path=smart-dairy/pki pki

# Configure max lease TTL
vault secrets tune -max-lease-ttl=87600h smart-dairy/pki

# Generate root CA
vault write smart-dairy/pki/root/generate/internal \
    common_name="Smart Dairy Root CA" \
    ttl=87600h \
    key_type=rsa \
    key_bits=4096

# Configure CA and CRL URLs
vault write smart-dairy/pki/config/urls \
    issuing_certificates="https://vault.smartdairy.internal:8200/v1/smart-dairy/pki/ca" \
    crl_distribution_points="https://vault.smartdairy.internal:8200/v1/smart-dairy/pki/crl"

# Create intermediate CA
vault secrets enable -path=smart-dairy/pki_int pki
vault secrets tune -max-lease-ttl=43800h smart-dairy/pki_int

# Generate intermediate CSR
vault write -format=json smart-dairy/pki_int/intermediate/generate/internal \
    common_name="Smart Dairy Intermediate CA" \
    ttl=43800h \
    key_type=rsa \
    key_bits=4096 | jq -r '.data.csr' > pki_intermediate.csr

# Sign intermediate with root CA
vault write -format=json smart-dairy/pki/root/sign-intermediate \
    csr=@pki_intermediate.csr \
    format=pem_bundle \
    ttl=43800h | jq -r '.data.certificate' > intermediate.cert.pem

# Set signed intermediate
vault write smart-dairy/pki_int/intermediate/set-signed certificate=@intermediate.cert.pem
```

### 13.2 Certificate Roles

```bash
# Create role for application certificates
vault write smart-dairy/pki_int/roles/smart-dairy-app \
    allowed_domains="smartdairy.internal,smartdairy.com,*.smartdairy.com" \
    allow_subdomains=true \
    allow_glob_domains=true \
    max_ttl=720h \
    ttl=168h \
    key_type=rsa \
    key_bits=2048 \
    require_cn=true \
    allowed_uri_sans="spiffe://smartdairy.io/*" \
    client_flag=true \
    server_flag=true

# Create role for database certificates
vault write smart-dairy/pki_int/roles/database \
    allowed_domains="db.smartdairy.internal,postgres.smartdairy.internal" \
    allow_subdomains=true \
    max_ttl=2160h \
    ttl=720h \
    key_type=rsa \
    key_bits=2048

# Create role for service mesh mTLS
vault write smart-dairy/pki_int/roles/mesh-mtls \
    allowed_domains="mesh.smartdairy.internal" \
    allow_subdomains=true \
    max_ttl=168h \
    ttl=24h \
    key_type=ec \
    key_bits=256
```

### 13.3 Certificate Issuance

```bash
# Issue application certificate
vault write smart-dairy/pki_int/issue/smart-dairy-app \
    common_name="milk-tracker.smartdairy.com" \
    alt_names="milk-tracker.prod.svc.cluster.local,milk-tracker" \
    ttl=168h

# Output includes:
# - certificate (PEM)
# - issuing_ca (PEM)
# - ca_chain (PEM list)
# - private_key (PEM)
# - private_key_type
# - serial_number
```

### 13.4 Certificate Renewal Automation

```python
# cert_manager.py
"""
Automated certificate management with Vault PKI
"""

import os
import json
import logging
from datetime import datetime, timedelta
from pathlib import Path
from typing import Optional

import hvac
from cryptography import x509
from cryptography.hazmat.backends import default_backend

logger = logging.getLogger(__name__)


class VaultCertManager:
    """Manage TLS certificates using Vault PKI"""
    
    def __init__(self, vault_client: hvac.Client):
        self.vault = vault_client
        self.cert_dir = Path("/etc/certs")
        self.cert_dir.mkdir(parents=True, exist_ok=True)
    
    def issue_certificate(self, role: str, common_name: str, 
                          alt_names: Optional[list] = None,
                          ttl: str = "168h") -> dict:
        """
        Issue a new certificate from Vault PKI
        
        Returns:
            Dictionary with certificate paths
        """
        params = {
            "common_name": common_name,
            "ttl": ttl
        }
        if alt_names:
            params["alt_names"] = ",".join(alt_names)
        
        response = self.vault.secrets.pki.generate_certificate(
            name=role,
            mount_point="smart-dairy/pki_int",
            **params
        )
        
        data = response["data"]
        
        # Save certificate files
        cert_path = self.cert_dir / f"{common_name}.crt"
        key_path = self.cert_dir / f"{common_name}.key"
        chain_path = self.cert_dir / f"{common_name}.chain"
        
        cert_path.write_text(data["certificate"])
        key_path.write_text(data["private_key"])
        chain_path.write_text("\n".join(data["ca_chain"]))
        
        # Set appropriate permissions
        key_path.chmod(0o600)
        
        logger.info(f"Issued certificate for {common_name} (serial: {data['serial_number']})")
        
        return {
            "certificate": str(cert_path),
            "private_key": str(key_path),
            "ca_chain": str(chain_path),
            "serial_number": data["serial_number"],
            "expiration": self._get_expiration(data["certificate"])
        }
    
    def _get_expiration(self, cert_pem: str) -> datetime:
        """Extract expiration date from certificate"""
        cert = x509.load_pem_x509_certificate(cert_pem.encode(), default_backend())
        return cert.not_valid_after
    
    def check_renewal_needed(self, cert_path: Path, threshold_days: int = 7) -> bool:
        """Check if certificate needs renewal"""
        if not cert_path.exists():
            return True
        
        cert_pem = cert_path.read_text()
        cert = x509.load_pem_x509_certificate(cert_pem.encode(), default_backend())
        
        time_until_expiry = cert.not_valid_after - datetime.utcnow()
        return time_until_expiry < timedelta(days=threshold_days)
    
    def renew_if_needed(self, role: str, common_name: str, 
                        alt_names: Optional[list] = None) -> Optional[dict]:
        """Renew certificate if it's nearing expiration"""
        cert_path = self.cert_dir / f"{common_name}.crt"
        
        if self.check_renewal_needed(cert_path):
            logger.info(f"Certificate for {common_name} needs renewal")
            return self.issue_certificate(role, common_name, alt_names)
        
        return None
    
    def revoke_certificate(self, serial_number: str) -> bool:
        """Revoke a certificate"""
        try:
            self.vault.secrets.pki.revoke_certificate(
                serial_number=serial_number,
                mount_point="smart-dairy/pki_int"
            )
            logger.info(f"Revoked certificate {serial_number}")
            return True
        except Exception as e:
            logger.error(f"Failed to revoke certificate: {e}")
            return False


def main():
    """Certificate renewal daemon"""
    import time
    
    vault_client = hvac.Client(url=os.environ["VAULT_ADDR"])
    vault_client.token = os.environ["VAULT_TOKEN"]
    
    cert_manager = VaultCertManager(vault_client)
    
    # Define certificates to manage
    certificates = [
        {
            "role": "smart-dairy-app",
            "common_name": "milk-tracker.smartdairy.com",
            "alt_names": ["milk-tracker.prod.svc.cluster.local"]
        },
        {
            "role": "database",
            "common_name": "postgres.smartdairy.internal",
            "alt_names": ["db.smartdairy.internal"]
        }
    ]
    
    while True:
        for cert_config in certificates:
            try:
                result = cert_manager.renew_if_needed(
                    cert_config["role"],
                    cert_config["common_name"],
                    cert_config.get("alt_names")
                )
                if result:
                    logger.info(f"Renewed certificate: {result}")
            except Exception as e:
                logger.error(f"Failed to check/renew certificate: {e}")
        
        # Check every hour
        time.sleep(3600)


if __name__ == "__main__":
    main()
```

### 13.5 Kubernetes cert-manager Integration

```yaml
# cert-manager-issuer.yaml
apiVersion: cert-manager.io/v1
kind: Issuer
metadata:
  name: vault-pki-issuer
  namespace: milk-tracker
spec:
  vault:
    server: https://vault.vault.svc.cluster.local:8200
    path: smart-dairy/pki_int/sign/smart-dairy-app
    auth:
      kubernetes:
        mountPath: /v1/auth/smart-dairy/k8s
        role: milk-tracker
        serviceAccountRef:
          name: milk-tracker
---
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: milk-tracker-tls
  namespace: milk-tracker
spec:
  secretName: milk-tracker-tls
  issuerRef:
    name: vault-pki-issuer
    kind: Issuer
  commonName: milk-tracker.smartdairy.com
  dnsNames:
    - milk-tracker.smartdairy.com
    - milk-tracker.milk-tracker.svc.cluster.local
    - milk-tracker
  usages:
    - server auth
    - client auth
  privateKey:
    algorithm: RSA
    size: 2048
  duration: 168h
  renewBefore: 24h
```

---

## 14. Backup & Disaster Recovery

### 14.1 Vault Backup Strategy

```bash
#!/bin/bash
# vault-backup.sh
# Daily Vault backup script

set -euo pipefail

BACKUP_DIR="/backup/vault/$(date +%Y%m%d)"
RETENTION_DAYS=30
VAULT_ADDR="https://vault.vault.svc.cluster.local:8200"

mkdir -p "$BACKUP_DIR"

echo "Starting Vault backup at $(date)"

# 1. Backup Raft snapshot
# Requires vault operator raft snapshot save
echo "Creating Raft snapshot..."
kubectl exec -n vault vault-0 -- vault operator raft snapshot save /tmp/vault.snap
echo "Done"

# 2. Download snapshot
kubectl cp vault/vault-0:/tmp/vault.snap "$BACKUP_DIR/vault.snap"

# 3. Backup seal configuration
echo "Backing up seal configuration..."
kubectl get configmap vault-config -n vault -o yaml > "$BACKUP_DIR/vault-config.yaml"

# 4. Backup policies
echo "Backing up Vault policies..."
mkdir -p "$BACKUP_DIR/policies"
vault policy list | while read policy; do
    vault policy read "$policy" > "$BACKUP_DIR/policies/${policy}.hcl"
done

# 5. Backup auth methods configuration
echo "Backing up auth methods..."
vault auth list -format=json > "$BACKUP_DIR/auth-methods.json"

# 6. Backup secrets engines configuration
echo "Backing up secrets engines..."
vault secrets list -format=json > "$BACKUP_DIR/secrets-engines.json"

# 7. Encrypt backup
echo "Encrypting backup..."
gpg --symmetric --cipher-algo AES256 --compress-algo 1 \
    --output "$BACKUP_DIR.tar.gz.gpg" \
    -c "$BACKUP_DIR"

# 8. Upload to S3
echo "Uploading to S3..."
aws s3 cp "$BACKUP_DIR.tar.gz.gpg" "s3://smart-dairy-vault-backups/"

# 9. Cleanup local backup
rm -rf "$BACKUP_DIR" "$BACKUP_DIR.tar.gz.gpg"

# 10. Cleanup old backups (S3 lifecycle policy should handle this)
echo "Backup completed at $(date)"
```

### 14.2 Disaster Recovery Procedures

#### 14.2.1 Raft Snapshot Restore

```bash
#!/bin/bash
# vault-restore.sh
# Emergency Vault restore from snapshot

SNAPSHOT_FILE="${1:-}"

if [ -z "$SNAPSHOT_FILE" ]; then
    echo "Usage: $0 <snapshot_file>"
    exit 1
fi

if [ ! -f "$SNAPSHOT_FILE" ]; then
    echo "Snapshot file not found: $SNAPSHOT_FILE"
    exit 1
fi

echo "🚨 VAULT DISASTER RECOVERY PROCEDURE"
echo "======================================"
echo "This will restore Vault from snapshot: $SNAPSHOT_FILE"
echo "Current time: $(date)"
echo ""
read -p "Are you sure? Type 'RESTORE' to continue: " confirm

if [ "$confirm" != "RESTORE" ]; then
    echo "Aborted"
    exit 1
fi

# 1. Scale down applications that depend on Vault
echo "Step 1: Scaling down dependent applications..."
kubectl scale deployment -n milk-tracker --all --replicas=0
kubectl scale deployment -n delivery-service --all --replicas=0
kubectl scale deployment -n payment-service --all --replicas=0

# 2. Stop Vault (keep cluster intact)
echo "Step 2: Stopping Vault..."
kubectl exec -n vault vault-0 -- vault operator step-down || true
kubectl rollout pause deployment vault -n vault

# 3. Copy snapshot to Vault pod
echo "Step 3: Copying snapshot to Vault..."
kubectl cp "$SNAPSHOT_FILE" vault/vault-0:/tmp/restore.snap

# 4. Restore snapshot
echo "Step 4: Restoring snapshot..."
kubectl exec -n vault vault-0 -- vault operator raft snapshot restore /tmp/restore.snap

# 5. Verify restore
echo "Step 5: Verifying restore..."
sleep 10
kubectl rollout resume deployment vault -n vault
kubectl rollout status statefulset vault -n vault --timeout=5m

# 6. Check Vault status
echo "Step 6: Checking Vault status..."
vault status

# 7. Verify seal status
SEAL_STATUS=$(vault status -format=json | jq -r '.sealed')
if [ "$SEAL_STATUS" = "true" ]; then
    echo "⚠️  Vault is sealed. Manual unseal required."
    echo "Use: vault operator unseal <unseal_key>"
else
    echo "✅ Vault is unsealed"
fi

# 8. Scale up applications
echo "Step 8: Scaling up applications..."
kubectl scale deployment -n milk-tracker --all --replicas=3
kubectl scale deployment -n delivery-service --all --replicas=3
kubectl scale deployment -n payment-service --all --replicas=3

echo ""
echo "======================================"
echo "Restore completed at $(date)"
echo "Verify application functionality before declaring all-clear"
```

#### 14.2.2 Site Failure Recovery

```
┌─────────────────────────────────────────────────────────────────┐
│              DISASTER RECOVERY - SITE FAILURE                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  PRIMARY SITE (Singapore)     │   DR SITE (Sydney)              │
│  =========================    │   ================              │
│                               │                                  │
│  ❌ Vault Cluster DOWN        │   ✅ Vault Cluster STANDBY      │
│  ❌ Applications AFFECTED     │   ✅ Ready for promotion        │
│                               │                                  │
├─────────────────────────────────────────────────────────────────┤
│  RECOVERY PROCEDURE:                                             │
│                                                                  │
│  1. DECLARE DISASTER                                             │
│     - Alert on-call team                                         │
│     - Notify stakeholders                                        │
│     - Open incident in PagerDuty                                 │
│                                                                  │
│  2. ACTIVATE DR SITE                                             │
│     - Promote Sydney cluster to active                         │
│     - Update DNS to point to DR site                           │
│     - Verify Vault is unsealed and operational                 │
│                                                                  │
│  3. REDIRECT TRAFFIC                                             │
│     - Update load balancer configuration                       │
│     - Activate DR applications                                 │
│     - Verify connectivity                                        │
│                                                                  │
│  4. MONITOR & STABILIZE                                          │
│     - Monitor error rates                                        │
│     - Verify secret retrieval                                    │
│     - Check audit logging                                        │
│                                                                  │
│  5. POST-INCIDENT                                                │
│     - Root cause analysis                                        │
│     - Plan failback to primary                                   │
│     - Update DR procedures                                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 15. Migration Strategy

### 15.1 From Static Secrets to Vault

#### 15.1.1 Migration Assessment

```python
# migration_assessment.py
"""
Assess current secret usage for Vault migration
"""

import re
import json
from pathlib import Path
from collections import defaultdict


class SecretMigrationAssessor:
    """Assess codebase for secrets migration"""
    
    SECRET_PATTERNS = {
        'database': re.compile(r'(postgres://|mysql://|mongodb://)[^\s]*', re.I),
        'api_key': re.compile(r'(api[_-]?key|apikey)\s*[=:]\s*["\']?[a-zA-Z0-9_\-]{16,}', re.I),
        'password': re.compile(r'(password|passwd|pwd)\s*[=:]\s*["\'][^"\']+["\']', re.I),
        'token': re.compile(r'(token|secret)\s*[=:]\s*["\']?[a-zA-Z0-9_\-]{20,}', re.I),
        'aws_key': re.compile(r'AKIA[0-9A-Z]{16}', re.I),
        'private_key': re.compile(r'-----BEGIN (RSA |EC |DSA |OPENSSH )?PRIVATE KEY-----'),
    }
    
    def __init__(self, source_dir: str):
        self.source_dir = Path(source_dir)
        self.findings = defaultdict(list)
    
    def scan_file(self, file_path: Path) -> list:
        """Scan a single file for secrets"""
        findings = []
        
        try:
            content = file_path.read_text(encoding='utf-8', errors='ignore')
        except Exception:
            return findings
        
        for secret_type, pattern in self.SECRET_PATTERNS.items():
            for match in pattern.finditer(content):
                findings.append({
                    'type': secret_type,
                    'file': str(file_path),
                    'line': content[:match.start()].count('\n') + 1,
                    'match': match.group()[:50] + '...' if len(match.group()) > 50 else match.group()
                })
        
        return findings
    
    def scan_repository(self):
        """Scan entire repository"""
        for file_path in self.source_dir.rglob('*'):
            if file_path.is_file() and file_path.suffix in [
                '.py', '.js', '.ts', '.yaml', '.yml', 
                '.json', '.env', '.properties', '.conf'
            ]:
                findings = self.scan_file(file_path)
                for finding in findings:
                    self.findings[finding['type']].append(finding)
    
    def generate_report(self) -> dict:
        """Generate migration assessment report"""
        return {
            'summary': {
                'total_findings': sum(len(v) for v in self.findings.values()),
                'by_type': {k: len(v) for k, v in self.findings.items()},
                'files_affected': len(set(
                    f['file'] for findings in self.findings.values() 
                    for f in findings
                ))
            },
            'findings': dict(self.findings),
            'recommendations': self._generate_recommendations()
        }
    
    def _generate_recommendations(self) -> list:
        """Generate migration recommendations"""
        recommendations = []
        
        if self.findings['database']:
            recommendations.append({
                'priority': 'HIGH',
                'type': 'database',
                'action': 'Configure Vault Database secrets engine for dynamic credentials',
                'effort': 'Medium'
            })
        
        if self.findings['api_key']:
            recommendations.append({
                'priority': 'HIGH',
                'type': 'api_key',
                'action': 'Migrate API keys to Vault KV store',
                'effort': 'Low'
            })
        
        if self.findings['aws_key']:
            recommendations.append({
                'priority': 'CRITICAL',
                'type': 'aws_credentials',
                'action': 'Configure Vault AWS secrets engine for dynamic STS credentials',
                'effort': 'Medium'
            })
        
        recommendations.append({
            'priority': 'MEDIUM',
            'type': 'infrastructure',
            'action': 'Deploy Vault Agent sidecar for secret injection',
            'effort': 'High'
        })
        
        return recommendations


# Usage
if __name__ == "__main__":
    assessor = SecretMigrationAssessor("/path/to/source")
    assessor.scan_repository()
    report = assessor.generate_report()
    print(json.dumps(report, indent=2))
```

#### 15.1.2 Phased Migration Plan

```
┌─────────────────────────────────────────────────────────────────┐
│              PHASED MIGRATION TO VAULT                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  PHASE 1: FOUNDATION (Week 1-2)                                  │
│  ─────────────────────────────                                   │
│  ✅ Deploy Vault cluster to development                          │
│  ✅ Configure basic KV secrets engine                            │
│  ✅ Set up authentication (Kubernetes, AppRole)                 │
│  ✅ Create base policies                                         │
│  ✅ Document secret paths structure                              │
│                                                                  │
│  PHASE 2: STATIC SECRETS (Week 3-4)                              │
│  ─────────────────────────────                                   │
│  ✅ Migrate non-production API keys                              │
│  ✅ Migrate configuration secrets                                │
│  ✅ Update applications to use Vault Agent                       │
│  ✅ Test in staging environment                                  │
│  ✅ Deploy to production (low-risk secrets only)                 │
│                                                                  │
│  PHASE 3: DYNAMIC SECRETS (Week 5-7)                             │
│  ─────────────────────────────                                   │
│  ✅ Configure Database secrets engine                            │
│  ✅ Migrate database credentials                                 │
│  ✅ Configure AWS secrets engine                                 │
│  ✅ Migrate cloud credentials                                    │
│  ✅ Implement dynamic credential rotation                        │
│                                                                  │
│  PHASE 4: ADVANCED FEATURES (Week 8-10)                          │
│  ─────────────────────────────                                   │
│  ✅ Deploy Transit encryption engine                             │
│  ✅ Implement application-level encryption                       │
│  ✅ Configure PKI for certificate management                     │
│  ✅ Migrate TLS certificates                                     │
│  ✅ Set up comprehensive monitoring                              │
│                                                                  │
│  PHASE 5: OPTIMIZATION (Week 11-12)                              │
│  ─────────────────────────────                                   │
│  ✅ Performance tuning                                           │
│  ✅ Disaster recovery testing                                    │
│  ✅ Security audit                                               │
│  ✅ Team training                                                │
│  ✅ Documentation updates                                        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### 15.1.3 Migration Script

```python
# migrate_secrets.py
"""
Migrate static secrets to Vault
"""

import os
import json
import hvac
from pathlib import Path


def migrate_env_to_vault(env_file: str, vault_path: str, 
                         vault_mount: str = "smart-dairy/kv"):
    """
    Migrate .env file secrets to Vault
    
    Args:
        env_file: Path to .env file
        vault_path: Destination path in Vault (e.g., 'apps/milk-tracker/prod/env')
        vault_mount: Vault KV mount point
    """
    vault = hvac.Client(url=os.environ['VAULT_ADDR'])
    vault.token = os.environ['VAULT_TOKEN']
    
    secrets = {}
    
    with open(env_file, 'r') as f:
        for line in f:
            line = line.strip()
            if not line or line.startswith('#') or '=' not in line:
                continue
            
            key, value = line.split('=', 1)
            key = key.strip()
            value = value.strip().strip('"\'')
            
            # Only migrate sensitive values
            if any(s in key.lower() for s in ['password', 'secret', 'key', 'token']):
                secrets[key] = value
    
    if secrets:
        print(f"Migrating {len(secrets)} secrets to {vault_path}")
        vault.secrets.kv.v2.create_or_update_secret(
            path=vault_path,
            mount_point=vault_mount,
            secret=secrets
        )
        print(f"✅ Migrated secrets to {vault_path}")
        
        # Backup original file
        backup_path = f"{env_file}.migrated.{datetime.now().strftime('%Y%m%d')}"
        os.rename(env_file, backup_path)
        print(f"📁 Backed up original to {backup_path}")
    else:
        print("No secrets found to migrate")


def migrate_json_secrets(json_file: str, vault_path: str,
                         vault_mount: str = "smart-dairy/kv"):
    """Migrate JSON secrets file to Vault"""
    vault = hvac.Client(url=os.environ['VAULT_ADDR'])
    vault.token = os.environ['VAULT_TOKEN']
    
    with open(json_file, 'r') as f:
        secrets = json.load(f)
    
    print(f"Migrating secrets from {json_file} to {vault_path}")
    vault.secrets.kv.v2.create_or_update_secret(
        path=vault_path,
        mount_point=vault_mount,
        secret=secrets
    )
    print(f"✅ Migrated {len(secrets)} secrets")


if __name__ == "__main__":
    import sys
    from datetime import datetime
    
    if len(sys.argv) < 4:
        print("Usage: python migrate_secrets.py <env|json> <file> <vault_path>")
        sys.exit(1)
    
    file_type = sys.argv[1]
    file_path = sys.argv[2]
    vault_path = sys.argv[3]
    
    if file_type == "env":
        migrate_env_to_vault(file_path, vault_path)
    elif file_type == "json":
        migrate_json_secrets(file_path, vault_path)
    else:
        print(f"Unknown file type: {file_type}")
        sys.exit(1)
```

---

## 16. Security Hardening

### 16.1 Vault Security Configuration

```hcl
# vault-security.hcl
# Security hardening configuration

# Disable dev mode
dev = false

# Enable TLS (minimum TLS 1.2)
listener "tcp" {
  address = "0.0.0.0:8200"
  tls_min_version = "tls12"
  tls_cipher_suites = "TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256,TLS_ECDHE_ECDSA_WITH_AES_128_GCM_SHA256,TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384,TLS_ECDHE_ECDSA_WITH_AES_256_GCM_SHA384"
  tls_prefer_server_cipher_suites = true
}

# Audit logging (cannot be disabled once enabled)
audit "file" {
  path = "/var/log/vault/audit.log"
}

# Strict default lease TTL
default_lease_ttl = "768h"
max_lease_ttl = "8760h"

# Disable unused features
disable_mlock = false
disable_cache = false

# Enable response wrapping for enhanced security
raw_storage_endpoint = false
```

### 16.2 Network Security

```yaml
# network-policies.yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: vault-network-policy
  namespace: vault
spec:
  podSelector:
    matchLabels:
      app.kubernetes.io/name: vault
  policyTypes:
    - Ingress
    - Egress
  ingress:
    # Allow API access from application namespaces
    - from:
        - namespaceSelector:
            matchLabels:
              vault-access: "true"
      ports:
        - protocol: TCP
          port: 8200
    # Allow internal cluster communication
    - from:
        - podSelector:
            matchLabels:
              app.kubernetes.io/name: vault
      ports:
        - protocol: TCP
          port: 8201
  egress:
    # Allow DNS
    - to:
        - namespaceSelector: {}
      ports:
        - protocol: UDP
          port: 53
    # Allow KMS access for auto-unseal
    - to:
        - ipBlock:
            cidr: 0.0.0.0/0  # Restrict to AWS IP ranges in production
      ports:
        - protocol: TCP
          port: 443
---
# Label namespaces that need Vault access
apiVersion: v1
kind: Namespace
metadata:
  name: milk-tracker
  labels:
    vault-access: "true"
```

### 16.3 Secret Handling Best Practices

```python
# secure_practices.py
"""
Secure secret handling patterns for Smart Dairy applications
"""

import os
import weakref
from typing import Optional
from contextlib import contextmanager
import secrets as secrets_module


class SecureString:
    """
    String wrapper that attempts to clear memory on deletion.
    Note: This provides defense in depth but cannot guarantee
    complete removal from memory due to Python's memory management.
    """
    
    def __init__(self, value: str):
        self._value = bytearray(value.encode('utf-8'))
        self._finalizer = weakref.finalize(self, self._clear, self._value)
    
    @staticmethod
    def _clear(data: bytearray):
        """Clear the bytearray"""
        for i in range(len(data)):
            data[i] = 0
    
    def __str__(self) -> str:
        return self._value.decode('utf-8')
    
    def __repr__(self) -> str:
        return "SecureString(***hidden***)"
    
    def __del__(self):
        self._finalizer()
    
    def get_value(self) -> str:
        """Get string value (use sparingly)"""
        return self._value.decode('utf-8')


class SecretManager:
    """
    Secure secret management with automatic cleanup
    """
    
    def __init__(self):
        self._secrets = {}
    
    def store(self, key: str, value: str):
        """Store secret securely"""
        self._secrets[key] = SecureString(value)
    
    def get(self, key: str) -> Optional[str]:
        """Retrieve secret"""
        if key in self._secrets:
            return self._secrets[key].get_value()
        return None
    
    def clear(self):
        """Clear all secrets"""
        self._secrets.clear()
    
    def __del__(self):
        self.clear()


@contextmanager
def temporary_secret(secret_value: str):
    """
    Context manager for temporary secret usage.
    Secret is automatically cleared when exiting context.
    """
    secret = SecureString(secret_value)
    try:
        yield secret
    finally:
        del secret


def mask_sensitive(data: dict, sensitive_keys: list = None) -> dict:
    """
    Create a copy of data with sensitive values masked.
    Useful for logging.
    """
    if sensitive_keys is None:
        sensitive_keys = ['password', 'secret', 'token', 'key', 'credential']
    
    masked = {}
    for key, value in data.items():
        if any(s in key.lower() for s in sensitive_keys):
            masked[key] = "***REDACTED***"
        elif isinstance(value, dict):
            masked[key] = mask_sensitive(value, sensitive_keys)
        else:
            masked[key] = value
    
    return masked


def generate_secure_token(length: int = 32) -> str:
    """Generate cryptographically secure random token"""
    return secrets_module.token_urlsafe(length)


# Security checklist decorator
def security_check(func):
    """Decorator to add security logging"""
    def wrapper(*args, **kwargs):
        # Log function call (without secrets)
        safe_args = [str(a)[:50] for a in args]
        safe_kwargs = {k: str(v)[:50] for k, v in kwargs.items()}
        
        # Execute
        result = func(*args, **kwargs)
        
        return result
    return wrapper


# Example usage
if __name__ == "__main__":
    # Secure token generation
    api_key = generate_secure_token()
    print(f"Generated API key: {api_key[:8]}...")
    
    # Temporary secret usage
    with temporary_secret("super-secret-value") as secret:
        print(f"Using secret: {secret}")
    # Secret is automatically cleared here
    
    # Masking for logs
    config = {
        "db_host": "localhost",
        "db_password": "secret123",
        "api_key": "abc123"
    }
    safe_config = mask_sensitive(config)
    print(f"Safe to log: {safe_config}")
```

---

## 17. Troubleshooting

### 17.1 Common Issues

#### 17.1.1 Vault Sealed

```bash
# Check seal status
vault status

# If sealed, unseal with key shares
vault operator unseal <unseal-key-1>
vault operator unseal <unseal-key-2>
vault operator unseal <unseal-key-3>

# Check auto-unseal status (if configured)
vault operator raft autopilot state
```

#### 17.1.2 Permission Denied

```bash
# Check current token capabilities
vault token capabilities <path>

# Example:
vault token capabilities smart-dairy/kv/apps/milk-tracker/prod/database

# Check token policies
vault token lookup

# Verify policy permissions
vault policy read <policy-name>
```

#### 17.1.3 Database Connection Failures

```bash
# Test database connection from Vault
vault read smart-dairy/db/config/milk-tracker-prod

# Check database engine status
vault read sys/mounts/smart-dairy/db/tune

# Verify database role
vault read smart-dairy/db/roles/milk-tracker-readwrite

# Test credential generation
vault read smart-dairy/db/creds/milk-tracker-readwrite
```

#### 17.1.4 Kubernetes Auth Failures

```bash
# Check Kubernetes auth configuration
vault read auth/smart-dairy/k8s/config

# Verify role exists
vault read auth/smart-dairy/k8s/role/milk-tracker

# Test JWT authentication manually
kubectl exec -it vault-0 -n vault -- /bin/sh
cat /var/run/secrets/kubernetes.io/serviceaccount/token | \
  vault write auth/smart-dairy/k8s/login role=milk-tracker jwt=-
```

### 17.2 Debugging Commands

```bash
# Enable debug logging
export VAULT_LOG_LEVEL=debug

# Check audit logs
tail -f /var/log/vault/audit.log | jq .

# Check server logs
kubectl logs -n vault -l app.kubernetes.io/name=vault --tail=100 -f

# Check raft status
vault operator raft list-peers

# Check for active leader
vault operator raft autopilot state

# Verify network connectivity
vault debug -output=/tmp/vault-debug.tar.gz
```

### 17.3 Recovery Procedures

```bash
# Generate debug bundle
vault debug -duration=30s -output=/tmp/vault-debug-$(date +%Y%m%d).tar.gz

# Step down current leader (trigger election)
vault operator step-down

# Remove raft peer (if node is permanently lost)
vault operator raft remove-peer <peer-id>

# Force lease revocation (emergency only)
vault lease revoke -prefix=true smart-dairy/db/creds/

# Regenerate root token (requires unseal keys)
vault operator generate-root -init
# Then provide unseal keys from multiple operators
vault operator generate-root -nonce=<nonce> <unseal-key>
```

---

## 18. Appendices

### Appendix A: Configuration Examples

#### A.1 Complete Vault Configuration

```hcl
# vault-complete.hcl
ui = true

# Listener configuration
listener "tcp" {
  address = "0.0.0.0:8200"
  
  # TLS Configuration
  tls_cert_file = "/vault/tls/tls.crt"
  tls_key_file = "/vault/tls/tls.key"
  tls_client_ca_file = "/vault/tls/ca.crt"
  tls_min_version = "tls12"
  
  # Proxy protocol (if behind load balancer)
  proxy_protocol_behavior = "allow_authorized"
  proxy_protocol_authorized_addrs = "10.0.0.0/8"
}

# Storage backend
storage "raft" {
  path = "/vault/data"
  node_id = "vault-0"
  
  retry_leader_election = true
  
  autopilot {
    cleanup_dead_servers = true
    last_contact_threshold = "10s"
    max_trailing_logs = 250
    min_quorum = 2
    server_stabilization_time = "10s"
  }
  
  # Performance tuning
  snapshot_threshold = 8192
  snapshot_interval = "30s"
}

# Auto-unseal with AWS KMS
seal "awskms" {
  region = "ap-southeast-1"
  kms_key_id = "arn:aws:kms:ap-southeast-1:123456789:key/vault-unseal"
}

# Service registration for Kubernetes
service_registration "kubernetes" {
  namespace = "vault"
  pod_name = "${HOSTNAME}"
}

# Telemetry
telemetry {
  prometheus_retention_time = "30s"
  disable_hostname = true
  enable_hostname_label = false
}

# API and cluster addresses
api_addr = "https://vault.vault.svc.cluster.local:8200"
cluster_addr = "https://$(POD_IP):8201"

# Logging
log_level = "Info"
log_format = "json"

# Lease settings
default_lease_ttl = "768h"
max_lease_ttl = "8760h"

# Security settings
disable_mlock = false
disable_cache = false
enable_ui = true
raw_storage_endpoint = false

# Plugin directory
plugin_directory = "/vault/plugins"
```

#### A.2 Complete Policy Set

```hcl
# policies/default.hcl
# Default policy for all tokens

# Allow tokens to look up their own properties
path "auth/token/lookup-self" {
  capabilities = ["read"]
}

# Allow tokens to renew themselves
path "auth/token/renew-self" {
  capabilities = ["update"]
}

# Allow tokens to revoke themselves
path "auth/token/revoke-self" {
  capabilities = ["update"]
}

# Allow a token to look up its own capabilities on a path
path "sys/capabilities-self" {
  capabilities = ["update"]
}

# Allow a token to look up its own entity by id or name
path "identity/entity/id/{{identity.entity.id}}" {
  capabilities = ["read"]
}

path "identity/entity/name/{{identity.entity.name}}" {
  capabilities = ["read"]
}
```

### Appendix B: Code Samples

#### B.1 Complete Flask Application Integration

```python
# flask_vault_app.py
"""
Complete Flask application with Vault integration
"""

from flask import Flask, jsonify, request
import hvac
import os
import psycopg2
from functools import wraps

app = Flask(__name__)

# Initialize Vault client
def get_vault_client():
    client = hvac.Client(url=os.environ.get('VAULT_ADDR'))
    
    # Kubernetes authentication
    with open('/var/run/secrets/kubernetes.io/serviceaccount/token', 'r') as f:
        jwt = f.read()
    
    client.auth.kubernetes.login(
        role='milk-tracker',
        jwt=jwt
    )
    
    return client

# Database connection decorator
def with_db_connection(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        vault = get_vault_client()
        
        # Get dynamic credentials
        creds = vault.secrets.database.generate_credentials(
            name='milk-tracker-readwrite'
        )['data']
        
        conn = psycopg2.connect(
            host='prod-db.smartdairy.internal',
            port=5432,
            database='milk_tracker_prod',
            user=creds['username'],
            password=creds['password']
        )
        
        try:
            return f(conn, *args, **kwargs)
        finally:
            conn.close()
    return decorated

@app.route('/api/v1/collections', methods=['GET'])
@with_db_connection
def get_collections(conn):
    """Get all milk collections"""
    with conn.cursor() as cur:
        cur.execute("SELECT * FROM milk_collections ORDER BY collection_date DESC")
        columns = [desc[0] for desc in cur.description]
        results = [dict(zip(columns, row)) for row in cur.fetchall()]
    return jsonify({'data': results})

@app.route('/api/v1/collections', methods=['POST'])
@with_db_connection
def create_collection(conn):
    """Create a new milk collection"""
    data = request.get_json()
    
    with conn.cursor() as cur:
        cur.execute(
            """INSERT INTO milk_collections 
               (farmer_id, quantity, quality_grade, collection_date) 
               VALUES (%s, %s, %s, NOW()) 
               RETURNING id""",
            (data['farmer_id'], data['quantity'], data['quality_grade'])
        )
        conn.commit()
        new_id = cur.fetchone()[0]
    
    return jsonify({'id': new_id, 'status': 'created'}), 201

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8080)
```

### Appendix C: Runbooks

#### C.1 On-Call Runbook: Vault Down

```markdown
# Runbook: Vault Service Down

## Symptoms
- Applications cannot retrieve secrets
- 5xx errors in application logs
- Vault health check failing
- High error rate in monitoring

## Immediate Actions (5 minutes)

1. Check Vault status
   ```bash
   kubectl get pods -n vault
   kubectl logs -n vault -l app.kubernetes.io/name=vault --tail=50
   ```

2. Check if Vault is sealed
   ```bash
   kubectl exec -n vault vault-0 -- vault status
   ```

3. If sealed, initiate unseal procedure
   - Contact 2 other key holders
   - Each provides one unseal key
   - Run: `vault operator unseal <key>`

## Escalation (if not resolved in 15 minutes)

1. Page DevOps Lead
2. Prepare for DR site activation
3. Notify CTO if production impact > 30 minutes

## Post-Incident

1. Document root cause
2. Update this runbook
3. Schedule retro within 48 hours
```

#### C.2 Secret Rotation Runbook

```markdown
# Runbook: Emergency Secret Rotation

## Trigger Conditions
- Suspected secret compromise
- Employee termination
- Security incident
- Compliance requirement

## Procedure

1. Identify secrets to rotate
   ```bash
   vault kv list smart-dairy/kv/apps/<app>/<env>
   ```

2. Revoke all leases
   ```bash
   vault lease revoke -prefix=true smart-dairy/db/creds/<role>
   ```

3. Rotate secrets using script
   ```bash
   ./emergency_rotation.sh apps/<app>/<env>/database prod "<reason>"
   ```

4. Verify rotation
   ```bash
   vault kv get smart-dairy/kv/apps/<app>/<env>/<secret>
   ```

5. Restart applications
   ```bash
   kubectl rollout restart deployment/<app> -n <namespace>
   ```

6. Verify application functionality
   - Check logs for errors
   - Run smoke tests
   - Monitor error rates

## Verification Checklist

- [ ] Old secrets invalidated
- [ ] New secrets generated
- [ ] Applications restarted
- [ ] No errors in logs
- [ ] Functionality verified
- [ ] Monitoring green
```

### Appendix D: Glossary

| Term | Definition |
|------|------------|
| **Vault** | HashiCorp Vault - tool for secrets management |
| **KV Store** | Key-Value secrets engine for static secrets |
| **Dynamic Secret** | Short-lived, on-demand generated credentials |
| **Lease** | Time-bound grant of access to a secret |
| **Seal/Unseal** | Encrypt/decrypt Vault's master key |
| **Policy** | Access control rules for Vault paths |
| **Auth Method** | Authentication mechanism (K8s, AppRole, etc.) |
| **PKI** | Public Key Infrastructure for certificates |
| **Transit** | Encryption as a service engine |
| **Raft** | Consensus protocol for Vault HA |
| **HMAC** | Hash-based Message Authentication Code |
| **mTLS** | Mutual TLS for service authentication |
| **CSI** | Container Storage Interface for K8s |
| **Sidecar** | Auxiliary container pattern in K8s |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Lead | Initial release |

---

**Document Classification:** INTERNAL USE ONLY

**Next Review Date:** July 31, 2026

**Distribution:** DevOps Team, Security Team, Development Team Leads

---

*End of Document F-014: Secrets Management Guide*
