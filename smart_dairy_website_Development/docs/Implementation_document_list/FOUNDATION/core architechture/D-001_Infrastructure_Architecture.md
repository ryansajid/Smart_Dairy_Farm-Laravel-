# SMART DAIRY LTD.
## INFRASTRUCTURE ARCHITECTURE DOCUMENT
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-001 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Status** | Draft for Review |
| **Author** | DevOps Lead |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |
| **Approved By** | Managing Director |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Infrastructure Strategy](#2-infrastructure-strategy)
3. [Cloud Architecture](#3-cloud-architecture)
4. [Network Architecture](#4-network-architecture)
5. [Compute Resources](#5-compute-resources)
6. [Storage Architecture](#6-storage-architecture)
7. [Database Infrastructure](#7-database-infrastructure)
8. [Container Orchestration](#8-container-orchestration)
9. [Monitoring & Logging](#9-monitoring--logging)
10. [Disaster Recovery](#10-disaster-recovery)
11. [Security Infrastructure](#11-security-infrastructure)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Infrastructure Architecture Document defines the complete infrastructure design for the Smart Dairy Smart Web Portal System and Integrated ERP. It provides the blueprint for provisioning, configuring, and managing all infrastructure components required to support the application stack.

### 1.2 Scope

This document covers:
- Cloud platform selection and architecture
- Network design and security groups
- Compute resource specifications
- Storage architecture (block, object, database)
- Container orchestration strategy
- Monitoring and observability infrastructure
- Disaster recovery and business continuity
- Security infrastructure and controls

### 1.3 Infrastructure Requirements Summary

| Requirement | Target |
|-------------|--------|
| **Availability** | 99.9% uptime |
| **Scalability** | Support 3x growth (255 → 800 cattle, 900L → 15,000L milk) |
| **Performance** | < 2s page load, < 500ms API response |
| **Recovery** | RTO < 4 hours, RPO < 1 hour |
| **Security** | ISO 27001, PCI DSS compliance |
| **Geography** | Primary: Bangladesh region, DR: Secondary region |

---

## 2. INFRASTRUCTURE STRATEGY

### 2.1 Cloud Platform Selection

**Selected Platform: AWS (Amazon Web Services)**

**Justification:**
- **Regional Presence**: AWS Mumbai (ap-south-1) provides low latency for Bangladesh users
- **Service Maturity**: Comprehensive managed services (RDS, ElastiCache, S3)
- **Bangladesh Connectivity**: Good connectivity to Bangladesh via submarine cables
- **Compliance**: ISO 27001, SOC 2, PCI DSS compliant infrastructure
- **Cost**: Competitive pricing with reserved instance options

**Alternative Platforms Evaluated:**

| Platform | Pros | Cons | Decision |
|----------|------|------|----------|
| **Azure** | Strong enterprise integration | Higher cost in South Asia | Not selected |
| **GCP** | Excellent ML/AI capabilities | Smaller presence in region | Not selected |
| **Bangladesh Local** | Data sovereignty, low cost | Limited services, reliability | Future consideration |

### 2.2 Environment Strategy

| Environment | Purpose | Deployment Frequency | Data |
|-------------|---------|---------------------|------|
| **Production** | Live system | Controlled releases | Production data |
| **Staging** | Pre-production testing | Every sprint | Anonymized production |
| **QA** | Testing environment | Daily builds | Synthetic data |
| **Development** | Developer workspaces | On demand | Minimal seed data |
| **DR** | Disaster recovery | Replicated continuously | Replicated from prod |

### 2.3 Infrastructure as Code (IaC)

**Tools:**
- **Terraform**: Infrastructure provisioning
- **Ansible**: Configuration management
- **Helm**: Kubernetes package management
- **GitHub Actions**: CI/CD pipeline

**Repository Structure:**
```
infrastructure/
├── terraform/
│   ├── modules/
│   │   ├── vpc/
│   │   ├── eks/
│   │   ├── rds/
│   │   └── s3/
│   ├── environments/
│   │   ├── production/
│   │   ├── staging/
│   │   └── development/
│   └── main.tf
├── ansible/
│   ├── playbooks/
│   └── roles/
├── helm/
│   ├── smart-dairy/
│   └── monitoring/
└── scripts/
```

---

## 3. CLOUD ARCHITECTURE

### 3.1 AWS Account Structure

```
Smart Dairy AWS Organization
├── Production Account
│   ├── VPC: vpc-smartdairy-prod
│   ├── RDS: PostgreSQL Primary
│   ├── EKS: Kubernetes Cluster
│   └── Route 53: smartdairybd.com
│
├── Staging Account
│   ├── VPC: vpc-smartdairy-staging
│   ├── RDS: PostgreSQL Staging
│   └── EKS: Kubernetes Staging
│
├── Development Account
│   ├── VPC: vpc-smartdairy-dev
│   └── Shared development resources
│
└── Shared Services Account
    ├── CI/CD Pipelines
    ├── Container Registry (ECR)
    └── Logging Aggregation
```

### 3.2 High-Level Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                              AWS CLOUD (ap-south-1)                                          │
│                                    Mumbai Region                                             │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐   │
│  │                              VPC: 10.0.0.0/16                                        │   │
│  │                                                                                      │   │
│  │  ┌───────────────────────────────────────────────────────────────────────────────┐  │   │
│  │  │                         PUBLIC SUBNETS (AZ-a, AZ-b)                            │  │   │
│  │  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │  │   │
│  │  │  │    ALB      │  │   NAT GW    │  │   Bastion   │  │   VPN Gateway       │  │  │   │
│  │  │  │ (Web)       │  │   (HA)      │  │   Host      │  │   (Admin Access)    │  │  │   │
│  │  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────────────┘  │  │   │
│  │  └───────────────────────────────────────────────────────────────────────────────┘  │   │
│  │                                                                                      │   │
│  │  ┌───────────────────────────────────────────────────────────────────────────────┐  │   │
│  │  │                      APPLICATION SUBNETS (Private)                             │  │   │
│  │  │                                                                                │  │   │
│  │  │  ┌─────────────────────────────────────────────────────────────────────────┐  │  │   │
│  │  │  │                    EKS CLUSTER (Kubernetes)                              │  │  │   │
│  │  │  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐    │  │  │   │
│  │  │  │  │ Odoo Pods   │  │ API Gateway │  │ Celery      │  │ Nginx       │    │  │  │   │
│  │  │  │  │ (3-10)      │  │ Pods (2-5)  │  │ Workers     │  │ Ingress     │    │  │  │   │
│  │  │  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘    │  │  │   │
│  │  │  └─────────────────────────────────────────────────────────────────────────┘  │  │   │
│  │  └───────────────────────────────────────────────────────────────────────────────┘  │   │
│  │                                                                                      │   │
│  │  ┌───────────────────────────────────────────────────────────────────────────────┐  │   │
│  │  │                         DATA SUBNETS (Private)                                 │  │   │
│  │  │  ┌─────────────────────┐  ┌─────────────┐  ┌─────────────┐  ┌────────────┐  │  │   │
│  │  │  │   RDS PostgreSQL    │  │   Redis     │  │ Elasticsearch│  │   EFS      │  │  │   │
│  │  │  │   ├─ Primary        │  │  Cluster    │  │  Cluster    │  │  (Shared   │  │  │   │
│  │  │  │   └─ Replica        │  │             │  │             │  │   Storage) │  │  │   │
│  │  │  └─────────────────────┘  └─────────────┘  └─────────────┘  └────────────┘  │  │   │
│  │  └───────────────────────────────────────────────────────────────────────────────┘  │   │
│  │                                                                                      │   │
│  └─────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                              │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐   │
│  │    S3 Buckets    │  │  CloudFront CDN  │  │   Route 53       │  │   CloudWatch     │   │
│  │  - Static Files  │  │  - Global Cache  │  │   - DNS          │  │   - Monitoring   │   │
│  │  - Backups       │  │                  │  │   - Health Checks│  │   - Logging      │   │
│  │  - Media         │  │                  │  │                  │  │                  │   │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘  └──────────────────┘   │
│                                                                                              │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. NETWORK ARCHITECTURE

### 4.1 VPC Design

**VPC Configuration:**
- **CIDR Block**: 10.0.0.0/16 (65,536 IP addresses)
- **DNS Hostnames**: Enabled
- **DNS Resolution**: Enabled
- **Tenancy**: Default

**Subnet Allocation:**

| Subnet Type | AZ-a | AZ-b | AZ-c | Purpose |
|-------------|------|------|------|---------|
| **Public** | 10.0.1.0/24 | 10.0.2.0/24 | 10.0.3.0/24 | ALB, NAT GW |
| **Application** | 10.0.11.0/24 | 10.0.12.0/24 | 10.0.13.0/24 | EKS Pods |
| **Database** | 10.0.21.0/24 | 10.0.22.0/24 | 10.0.23.0/24 | RDS, Redis |
| **Management** | 10.0.31.0/24 | 10.0.32.0/24 | - | Bastion, VPN |

### 4.2 Network Security Groups

**Security Group: alb-sg**
```hcl
resource "aws_security_group" "alb" {
  name_prefix = "alb-"
  vpc_id      = aws_vpc.main.id

  # HTTP
  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # HTTPS
  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # Outbound
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
```

**Security Group: eks-nodes-sg**
```hcl
resource "aws_security_group" "eks_nodes" {
  name_prefix = "eks-nodes-"
  vpc_id      = aws_vpc.main.id

  # Allow ALB access
  ingress {
    from_port       = 8069
    to_port         = 8069
    protocol        = "tcp"
    security_groups = [aws_security_group.alb.id]
  }

  # Allow internal cluster communication
  ingress {
    from_port = 0
    to_port   = 65535
    protocol  = "tcp"
    self      = true
  }

  # Allow from bastion
  ingress {
    from_port       = 22
    to_port         = 22
    protocol        = "tcp"
    security_groups = [aws_security_group.bastion.id]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
```

**Security Group: rds-sg**
```hcl
resource "aws_security_group" "rds" {
  name_prefix = "rds-"
  vpc_id      = aws_vpc.main.id

  # PostgreSQL from EKS
  ingress {
    from_port       = 5432
    to_port         = 5432
    protocol        = "tcp"
    security_groups = [aws_security_group.eks_nodes.id]
  }

  # PostgreSQL from bastion
  ingress {
    from_port       = 5432
    to_port         = 5432
    protocol        = "tcp"
    security_groups = [aws_security_group.bastion.id]
  }
}
```

### 4.3 Network ACLs

```hcl
resource "aws_network_acl" "public" {
  vpc_id = aws_vpc.main.id
  subnet_ids = aws_subnet.public[*].id

  # Allow HTTP
  ingress {
    protocol   = "tcp"
    rule_no    = 100
    action     = "allow"
    cidr_block = "0.0.0.0/0"
    from_port  = 80
    to_port    = 80
  }

  # Allow HTTPS
  ingress {
    protocol   = "tcp"
    rule_no    = 110
    action     = "allow"
    cidr_block = "0.0.0.0/0"
    from_port  = 443
    to_port    = 443
  }

  # Allow ephemeral ports for return traffic
  ingress {
    protocol   = "tcp"
    rule_no    = 120
    action     = "allow"
    cidr_block = "0.0.0.0/0"
    from_port  = 1024
    to_port    = 65535
  }

  # Deny all other inbound
  ingress {
    protocol   = "-1"
    rule_no    = 200
    action     = "deny"
    cidr_block = "0.0.0.0/0"
  }

  # Allow all outbound
  egress {
    protocol   = "-1"
    rule_no    = 100
    action     = "allow"
    cidr_block = "0.0.0.0/0"
  }
}
```

---

## 5. COMPUTE RESOURCES

### 5.1 EKS Cluster Configuration

**Cluster Specifications:**
- **Version**: 1.28
- **VPC**: vpc-smartdairy-prod
- **Subnets**: Private subnets in AZ-a, AZ-b
- **Endpoint Access**: Private (VPN/bastion required)
- **Logging**: API server, Audit, Authenticator

**Node Groups:**

| Node Group | Instance Type | Min | Max | Desired | Purpose |
|------------|---------------|-----|-----|---------|---------|
| **System** | t3.medium | 2 | 4 | 2 | CoreDNS, kube-proxy |
| **General** | m6i.xlarge | 2 | 10 | 3 | Odoo, API Gateway |
| **Workers** | m6i.large | 1 | 5 | 2 | Celery, background jobs |
| **Spot** | m6i.large (spot) | 0 | 20 | 0 | Burst capacity |

**Terraform Configuration:**
```hcl
module "eks" {
  source  = "terraform-aws-modules/eks/aws"
  version = "19.0"

  cluster_name    = "smart-dairy-prod"
  cluster_version = "1.28"

  vpc_id     = aws_vpc.main.id
  subnet_ids = aws_subnet.private[*].id

  eks_managed_node_groups = {
    general = {
      name           = "general-workloads"
      instance_types = ["m6i.xlarge"]
      
      min_size     = 2
      max_size     = 10
      desired_size = 3

      capacity_type = "ON_DEMAND"
      
      labels = {
        workload = "general"
      }

      taints = []

      update_config = {
        max_unavailable_percentage = 25
      }
    }

    workers = {
      name           = "background-workers"
      instance_types = ["m6i.large"]
      
      min_size     = 1
      max_size     = 5
      desired_size = 2

      labels = {
        workload = "workers"
      }

      taints = [{
        key    = "dedicated"
        value  = "workers"
        effect = "NO_SCHEDULE"
      }]
    }
  }

  # Add-ons
  cluster_addons = {
    coredns = {
      most_recent = true
    }
    kube-proxy = {
      most_recent = true
    }
    vpc-cni = {
      most_recent = true
    }
    aws-ebs-csi-driver = {
      most_recent = true
    }
  }
}
```

### 5.2 Auto-Scaling Configuration

**Horizontal Pod Autoscaler (HPA):**
```yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-hpa
  namespace: smart-dairy
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
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
      - type: Pods
        value: 4
        periodSeconds: 60
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
      - type: Pods
        value: 2
        periodSeconds: 120
```

**Cluster Autoscaler:**
```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: cluster-autoscaler
  namespace: kube-system
spec:
  template:
    spec:
      containers:
      - name: cluster-autoscaler
        image: k8s.gcr.io/autoscaling/cluster-autoscaler:v1.28.0
        command:
        - ./cluster-autoscaler
        - --cloud-provider=aws
        - --namespace=kube-system
        - --node-group-auto-discovery=asg:tag=k8s.io/cluster-autoscaler/enabled,k8s.io/cluster-autoscaler/smart-dairy-prod
        - --balance-similar-node-groups
        - --skip-nodes-with-system-pods=false
```

---

## 6. STORAGE ARCHITECTURE

### 6.1 S3 Buckets

**Bucket Structure:**

| Bucket Name | Purpose | Encryption | Lifecycle |
|-------------|---------|------------|-----------|
| smartdairy-prod-static | Static assets | SSE-S3 | - |
| smartdairy-prod-media | User uploads | SSE-KMS | 90 days → Glacier |
| smartdairy-prod-backups | Database backups | SSE-KMS | 30 days → Glacier |
| smartdairy-prod-logs | Application logs | SSE-S3 | 30 days → Delete |
| smartdairy-prod-exports | Report exports | SSE-KMS | 7 days → Delete |

**Terraform Configuration:**
```hcl
resource "aws_s3_bucket" "static" {
  bucket = "smartdairy-prod-static"
}

resource "aws_s3_bucket_versioning" "static" {
  bucket = aws_s3_bucket.static.id
  versioning_configuration {
    status = "Enabled"
  }
}

resource "aws_s3_bucket_server_side_encryption_configuration" "static" {
  bucket = aws_s3_bucket.static.id
  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm = "AES256"
    }
  }
}

resource "aws_s3_bucket_public_access_block" "static" {
  bucket = aws_s3_bucket.static.id

  block_public_acls       = false
  block_public_policy     = false
  ignore_public_acls      = false
  restrict_public_buckets = false
}

resource "aws_s3_bucket_policy" "static" {
  bucket = aws_s3_bucket.static.id
  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid       = "PublicReadGetObject"
        Effect    = "Allow"
        Principal = "*"
        Action    = "s3:GetObject"
        Resource  = "${aws_s3_bucket.static.arn}/*"
      }
    ]
  })
}
```

### 6.2 EFS (Elastic File System)

**Configuration:**
- **Performance Mode**: General Purpose
- **Throughput Mode**: Bursting
- **Encryption**: Enabled (AWS KMS)
- **Lifecycle Policy**: 30 days → IA (Infrequent Access)

**Mount Points:**
- AZ-a: 10.0.21.5
- AZ-b: 10.0.22.5

**Persistent Volume Claim:**
```yaml
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: odoo-data-pvc
  namespace: smart-dairy
spec:
  accessModes:
    - ReadWriteMany
  storageClassName: efs-sc
  resources:
    requests:
      storage: 100Gi
```

---

## 7. DATABASE INFRASTRUCTURE

### 7.1 RDS PostgreSQL Configuration

**Primary Instance:**
```hcl
resource "aws_db_instance" "primary" {
  identifier = "smart-dairy-postgres-primary"
  
  engine         = "postgres"
  engine_version = "16.1"
  instance_class = "db.r6g.2xlarge"
  
  allocated_storage     = 500
  max_allocated_storage = 2000
  storage_type          = "gp3"
  storage_encrypted     = true
  
  db_name  = "smartdairy"
  username = "odoo_admin"
  password = var.db_password
  
  multi_az               = true
  publicly_accessible    = false
  vpc_security_group_ids = [aws_security_group.rds.id]
  db_subnet_group_name   = aws_db_subnet_group.main.name
  
  backup_retention_period = 30
  backup_window          = "03:00-04:00"
  maintenance_window     = "Mon:04:00-Mon:05:00"
  
  performance_insights_enabled    = true
  performance_insights_retention_period = 7
  
  deletion_protection = true
  skip_final_snapshot = false
  final_snapshot_identifier = "smart-dairy-final-snapshot"
  
  enabled_cloudwatch_logs_exports = ["postgresql", "upgrade"]
}
```

**Read Replica:**
```hcl
resource "aws_db_instance" "replica" {
  identifier = "smart-dairy-postgres-replica"
  
  replicate_source_db = aws_db_instance.primary.arn
  
  instance_class = "db.r6g.xlarge"
  
  publicly_accessible = false
  vpc_security_group_ids = [aws_security_group.rds.id]
}
```

### 7.2 Redis (ElastiCache) Configuration

**Cluster Configuration:**
```hcl
resource "aws_elasticache_replication_group" "redis" {
  replication_group_id = "smart-dairy-redis"
  description          = "Redis cluster for Smart Dairy"
  
  node_type            = "cache.r6g.large"
  num_cache_clusters   = 2
  automatic_failover_enabled = true
  multi_az_enabled     = true
  
  engine_version       = "7.0"
  port                 = 6379
  
  parameter_group_name = aws_elasticache_parameter_group.redis.name
  subnet_group_name    = aws_elasticache_subnet_group.main.name
  security_group_ids   = [aws_security_group.redis.id]
  
  at_rest_encryption_enabled = true
  transit_encryption_enabled = true
  
  snapshot_retention_limit = 7
  snapshot_window         = "05:00-06:00"
}
```

### 7.3 Elasticsearch Configuration

**Domain Configuration:**
```hcl
resource "aws_elasticsearch_domain" "main" {
  domain_name           = "smart-dairy-search"
  elasticsearch_version = "OpenSearch_2.5"
  
  cluster_config {
    instance_type            = "m6g.large.elasticsearch"
    instance_count           = 3
    dedicated_master_enabled = true
    dedicated_master_type    = "m6g.large.elasticsearch"
    dedicated_master_count   = 3
    zone_awareness_enabled   = true
    zone_awareness_config {
      availability_zone_count = 3
    }
  }
  
  ebs_options {
    ebs_enabled = true
    volume_type = "gp3"
    volume_size = 100
  }
  
  encrypt_at_rest {
    enabled = true
  }
  
  node_to_node_encryption {
    enabled = true
  }
  
  domain_endpoint_options {
    enforce_https       = true
    tls_security_policy = "Policy-Min-TLS-1-2-2019-07"
  }
  
  vpc_options {
    subnet_ids         = aws_subnet.data[*].id
    security_group_ids = [aws_security_group.elasticsearch.id]
  }
}
```

---

## 8. CONTAINER ORCHESTRATION

### 8.1 Kubernetes Architecture

**Namespace Structure:**
```
smart-dairy/
├── ingress-nginx/          # Ingress controller
├── cert-manager/           # TLS certificate management
├── monitoring/             # Prometheus, Grafana
├── logging/                # ELK Stack
├── smart-dairy/            # Application workloads
│   ├── odoo/              # Odoo deployments
│   ├── api-gateway/       # FastAPI services
│   └── celery/            # Background workers
└── databases/              # Stateful services (if running in-cluster)
```

### 8.2 Helm Chart Structure

```yaml
# Chart.yaml
apiVersion: v2
name: smart-dairy
description: Smart Dairy ERP Deployment
type: application
version: 1.0.0
appVersion: "19.0"

dependencies:
  - name: postgresql
    version: 12.x.x
    repository: https://charts.bitnami.com/bitnami
    condition: postgresql.enabled
  
  - name: redis
    version: 18.x.x
    repository: https://charts.bitnami.com/bitnami
    condition: redis.enabled
```

```yaml
# values.yaml
odoo:
  replicaCount: 3
  
  image:
    repository: smart-dairy/odoo
    tag: "19.0-latest"
    pullPolicy: Always
  
  service:
    type: ClusterIP
    port: 8069
  
  ingress:
    enabled: true
    className: nginx
    annotations:
      cert-manager.io/cluster-issuer: "letsencrypt-prod"
      nginx.ingress.kubernetes.io/proxy-body-size: "100m"
    hosts:
      - host: erp.smartdairybd.com
        paths:
          - path: /
            pathType: Prefix
    tls:
      - secretName: odoo-tls
        hosts:
          - erp.smartdairybd.com
  
  resources:
    limits:
      cpu: 2000m
      memory: 4Gi
    requests:
      cpu: 1000m
      memory: 2Gi
  
  autoscaling:
    enabled: true
    minReplicas: 3
    maxReplicas: 10
    targetCPUUtilizationPercentage: 70
    targetMemoryUtilizationPercentage: 80
  
  persistence:
    enabled: true
    storageClass: efs-sc
    accessMode: ReadWriteMany
    size: 100Gi
```

---

## 9. MONITORING & LOGGING

### 9.1 Prometheus & Grafana

**Prometheus Configuration:**
```yaml
# prometheus-values.yaml
prometheus:
  prometheusSpec:
    retention: 30d
    retentionSize: 50GB
    resources:
      requests:
        cpu: 500m
        memory: 1Gi
      limits:
        cpu: 1000m
        memory: 2Gi
    additionalScrapeConfigs:
      - job_name: 'odoo-metrics'
        static_configs:
          - targets: ['odoo-metrics:8069']
        metrics_path: '/metrics'
        scrape_interval: 30s

alertmanager:
  enabled: true
  config:
    global:
      smtp_smarthost: 'smtp.gmail.com:587'
      smtp_from: 'alerts@smartdairybd.com'
    route:
      receiver: 'default-receiver'
      routes:
        - match:
            severity: critical
          receiver: 'pagerduty'
        - match:
            severity: warning
          receiver: 'email'
    receivers:
      - name: 'default-receiver'
        email_configs:
          - to: 'ops@smartdairybd.com'
      - name: 'pagerduty'
        pagerduty_configs:
          - service_key: '<pagerduty-key>'
```

**Key Metrics to Monitor:**

| Category | Metric | Threshold | Alert |
|----------|--------|-----------|-------|
| **Availability** | Uptime | < 99.9% | Critical |
| **Performance** | Response Time | > 3s | Warning |
| **Error Rate** | 5xx Errors | > 0.1% | Warning |
| **Database** | Connection Pool | > 80% | Warning |
| **Cache** | Hit Rate | < 70% | Warning |
| **Disk** | Usage | > 85% | Warning |
| **Memory** | Usage | > 90% | Critical |
| **Queue** | Lag | > 5 min | Warning |

### 9.2 Logging with ELK Stack

**Fluent Bit Configuration:**
```yaml
config:
  service: |
    [SERVICE]
        Flush 1
        Daemon Off
        Log_Level info
        Parsers_File parsers.conf
  
  inputs: |
    [INPUT]
        Name tail
        Path /var/log/containers/*.log
        Parser docker
        Tag kube.*
        Mem_Buf_Limit 5MB
    
    [INPUT]
        Name systemd
        Tag host.*
        Systemd_Filter _SYSTEMD_UNIT=docker.service
  
  outputs: |
    [OUTPUT]
        Name es
        Match kube.*
        Host elasticsearch-master
        Port 9200
        Logstash_Format On
        Logstash_Prefix smart-dairy
        Retry_Limit False
        Suppress_Type_Name On
```

---

## 10. DISASTER RECOVERY

### 10.1 Backup Strategy

| Data Type | Frequency | Retention | Method |
|-----------|-----------|-----------|--------|
| **Database** | Daily + Continuous (PITR) | 30 days | RDS automated backups |
| **File Storage** | Daily | 30 days | S3 versioning + Cross-region replication |
| **Configuration** | On change | 90 days | Git repository + S3 |
| **Logs** | Real-time | 90 days | S3 + Glacier |

### 10.2 DR Architecture

```
Primary Region (Mumbai)          DR Region (Singapore)
┌─────────────────┐              ┌─────────────────┐
│   RDS Primary   │─────────────►│  RDS Replica    │
│   (Read/Write)  │  Async Repl  │  (Read Only)    │
└─────────────────┘              └─────────────────┘
         │                                │
         │ Failover                       │ Promote
         ▼                                ▼
┌─────────────────┐              ┌─────────────────┐
│   EKS Cluster   │              │  EKS Cluster    │
│   (Active)      │              │  (Standby)      │
└─────────────────┘              └─────────────────┘
         │                                │
         │                                │
┌─────────────────┐              ┌─────────────────┐
│   Route 53      │              │  Route 53       │
│   (Primary)     │◄────────────►│  (Failover)     │
└─────────────────┘              └─────────────────┘
```

### 10.3 RTO/RPO Definitions

| System | RTO | RPO | Recovery Method |
|--------|-----|-----|-----------------|
| **Core ERP** | 4 hours | 1 hour | Automated failover |
| **Database** | 2 hours | 5 minutes | RDS Multi-AZ + PITR |
| **File Storage** | 1 hour | 0 (sync) | S3 Cross-region replication |
| **Mobile API** | 2 hours | 0 (stateless) | Re-deploy to DR |

---

## 11. SECURITY INFRASTRUCTURE

### 11.1 AWS WAF Configuration

```hcl
resource "aws_wafv2_web_acl" "main" {
  name        = "smart-dairy-waf"
  description = "WAF rules for Smart Dairy"
  scope       = "REGIONAL"

  default_action {
    allow {}
  }

  rule {
    name     = "RateLimitRule"
    priority = 1

    action {
      block {}
    }

    statement {
      rate_based_statement {
        limit              = 2000
        aggregate_key_type = "IP"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "RateLimitRule"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "AWSManagedRulesCommonRuleSet"
    priority = 2

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesCommonRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesCommonRuleSetMetric"
      sampled_requests_enabled   = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name                = "smart-dairy-waf"
    sampled_requests_enabled   = true
  }
}
```

### 11.2 Secrets Management

**AWS Secrets Manager:**
```hcl
resource "aws_secretsmanager_secret" "db_password" {
  name                    = "smart-dairy/db-password"
  description             = "Database password for Smart Dairy"
  recovery_window_in_days = 7
}

resource "aws_secretsmanager_secret_version" "db_password" {
  secret_id     = aws_secretsmanager_secret.db_password.id
  secret_string = var.db_password
}
```

**External Secrets Operator:**
```yaml
apiVersion: external-secrets.io/v1beta1
kind: ExternalSecret
metadata:
  name: db-credentials
  namespace: smart-dairy
spec:
  refreshInterval: 1h
  secretStoreRef:
    kind: ClusterSecretStore
    name: aws-secrets-manager
  target:
    name: db-credentials
    creationPolicy: Owner
  data:
  - secretKey: password
    remoteRef:
      key: smart-dairy/db-password
      property: password
```

---

## 12. APPENDICES

### Appendix A: Cost Estimation

| Service | Monthly Cost (USD) |
|---------|-------------------|
| EKS Cluster | $73 |
| EC2 (EKS Nodes) | $800 |
| RDS PostgreSQL | $600 |
| ElastiCache Redis | $200 |
| Elasticsearch | $400 |
| ALB | $50 |
| S3 Storage | $100 |
| CloudFront | $50 |
| CloudWatch | $100 |
| Data Transfer | $200 |
| **Total** | **~$2,573/month** |

### Appendix B: Infrastructure Checklist

**Pre-Deployment:**
- [ ] VPC created with proper CIDR
- [ ] Subnets created in all AZs
- [ ] Internet Gateway attached
- [ ] NAT Gateways deployed (HA)
- [ ] Route tables configured
- [ ] Security groups defined
- [ ] NACLs configured

**Compute:**
- [ ] EKS cluster provisioned
- [ ] Node groups created
- [ ] Cluster autoscaler deployed
- [ ] HPA configured

**Data:**
- [ ] RDS instance provisioned
- [ ] Read replica created
- [ ] Backup policy configured
- [ ] Redis cluster deployed
- [ ] Elasticsearch domain created
- [ ] S3 buckets created

**Security:**
- [ ] WAF rules applied
- [ ] SSL certificates created
- [ ] Secrets stored in Secrets Manager
- [ ] IAM roles configured
- [ ] Security groups reviewed

---

**END OF INFRASTRUCTURE ARCHITECTURE DOCUMENT**

**Next Document:** F-001 Security Architecture Document
