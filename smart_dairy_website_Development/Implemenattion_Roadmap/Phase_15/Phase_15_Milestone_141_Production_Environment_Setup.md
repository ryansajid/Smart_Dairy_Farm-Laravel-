# Milestone 141: Production Environment Setup

## Smart Dairy Digital Smart Portal + ERP — Phase 15: Deployment & Handover

| Field                | Detail                                                    |
|----------------------|-----------------------------------------------------------|
| **Milestone**        | 141 of 150                                                |
| **Title**            | Production Environment Setup                              |
| **Phase**            | Phase 15 — Deployment & Handover (Part A: Deployment)     |
| **Days**             | Days 701–705 (of 750)                                     |
| **Duration**         | 5 working days                                            |
| **Version**          | 1.0                                                       |
| **Status**           | Draft                                                     |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated**     | 2026-02-05                                                |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Provision and configure production-ready AWS infrastructure with high availability, auto-scaling, security hardening, and comprehensive monitoring. By Day 705, the production environment must be fully operational with all infrastructure as code (Terraform), Kubernetes cluster (EKS), managed database (RDS), caching layer (ElastiCache), and observability stack (Prometheus/Grafana/ELK) ready for application deployment.

### 1.2 Objectives

1. Deploy AWS VPC with 3-AZ high availability architecture using Terraform
2. Provision Amazon EKS cluster with managed node groups (application + database workloads)
3. Set up Amazon RDS PostgreSQL 16 Multi-AZ with read replicas and automated backups
4. Deploy Amazon ElastiCache Redis 7 cluster with automatic failover
5. Configure Amazon CloudFront CDN with S3 origin for static assets
6. Establish Application Load Balancer with SSL termination
7. Implement auto-scaling policies for EKS nodes and application pods
8. Deploy Prometheus/Grafana monitoring stack with production dashboards
9. Set up ELK stack for centralized log aggregation and analysis
10. Configure CI/CD pipeline for production deployments with blue-green strategy

### 1.3 Key Deliverables

| # | Deliverable | Owner | Format |
|---|-------------|-------|--------|
| 1 | Terraform VPC module | Dev 1 | HCL files |
| 2 | Terraform EKS module | Dev 1 | HCL files |
| 3 | Terraform RDS module | Dev 1 | HCL files |
| 4 | Terraform ElastiCache module | Dev 2 | HCL files |
| 5 | Terraform CloudFront module | Dev 2 | HCL files |
| 6 | Kubernetes namespace manifests | Dev 2 | YAML files |
| 7 | Kubernetes deployment manifests | Dev 2 | YAML files |
| 8 | Prometheus/Grafana stack | Dev 2 | Helm charts |
| 9 | ELK stack configuration | Dev 2 | Kubernetes YAML |
| 10 | GitHub Actions production pipeline | Dev 2 | YAML workflow |
| 11 | Infrastructure architecture diagram | Dev 3 | PNG/SVG |
| 12 | Production environment runbook | Dev 3 | Markdown |
| 13 | Environment validation report | All | Markdown |

### 1.4 Prerequisites

- Phase 14 testing complete with sign-off
- AWS production account provisioned with required service limits
- Domain names registered (smartdairy.com.bd, api.smartdairy.com.bd)
- SSL certificates issued via AWS ACM
- GitHub repository access with production secrets configured
- Budget approval for AWS production costs (~BDT 25 Lakh/year)

### 1.5 Success Criteria

- [ ] All Terraform modules apply successfully without errors
- [ ] EKS cluster healthy with 3+ worker nodes across 3 AZs
- [ ] RDS Multi-AZ failover tested and verified (<60s failover)
- [ ] ElastiCache Redis responding with <1ms latency
- [ ] CloudFront serving static assets with cache hit ratio >80%
- [ ] Prometheus scraping all targets with 100% uptime
- [ ] Grafana dashboards displaying real-time metrics
- [ ] ELK stack ingesting logs from all services
- [ ] CI/CD pipeline successfully deploys sample application
- [ ] All infrastructure documented in runbooks

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Requirement Description | Day(s) | Task Reference |
|--------|--------|-------------------------|--------|----------------|
| NFR-AVAIL-01 | BRD §3 | 99.9% system availability | 701-703 | Multi-AZ VPC, EKS, RDS |
| NFR-PERF-01 | BRD §3 | Page load time < 3 seconds | 702-703 | CloudFront CDN |
| NFR-SEC-01 | BRD §3 | All data encrypted in transit | 701-702 | TLS 1.3, ACM certs |
| NFR-SEC-03 | BRD §3 | Data encryption at rest | 702 | RDS KMS, S3 encryption |
| NFR-DR-01 | BRD §3 | RTO < 4 hours | 702 | Multi-AZ, snapshots |
| NFR-DR-02 | BRD §3 | RPO < 1 hour | 702 | Automated backups |
| D-001 §4 | Impl Guide | Multi-environment infrastructure | 701-705 | Terraform modules |
| D-004 §1-5 | Impl Guide | Kubernetes deployment | 701-703 | EKS configuration |
| D-008 §1-3 | Impl Guide | Monitoring setup | 704-705 | Prometheus/Grafana |
| D-009 §1-2 | Impl Guide | Log aggregation (ELK) | 704-705 | ELK stack |
| D-012 §2 | Impl Guide | Disaster recovery architecture | 702 | Multi-AZ, backups |

---

## 3. Day-by-Day Breakdown

---

### Day 701 — AWS Core Infrastructure (VPC, EKS)

**Objective:** Deploy foundational AWS networking infrastructure and EKS cluster using Terraform.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Initialize Terraform production workspace with S3 backend — 1h
2. Deploy VPC with 3-AZ public/private/database subnets — 2h
3. Configure NAT gateways (one per AZ for HA) — 1h
4. Deploy EKS control plane with logging enabled — 2.5h
5. Configure IAM roles for EKS service account (IRSA) — 1.5h

**Code: Terraform Main Configuration**

```hcl
# infrastructure/production/main.tf

terraform {
  required_version = ">= 1.6.0"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.30"
    }
    kubernetes = {
      source  = "hashicorp/kubernetes"
      version = "~> 2.24"
    }
    helm = {
      source  = "hashicorp/helm"
      version = "~> 2.12"
    }
  }

  backend "s3" {
    bucket         = "smart-dairy-terraform-state-prod"
    key            = "production/terraform.tfstate"
    region         = "ap-south-1"
    encrypt        = true
    dynamodb_table = "terraform-state-lock-prod"
  }
}

provider "aws" {
  region = var.aws_region

  default_tags {
    tags = {
      Environment  = "production"
      Project      = "Smart-Dairy-Platform"
      ManagedBy    = "Terraform"
      CostCenter   = "SD-PROD-001"
      Owner        = "smart-dairy-dev-team"
      CreatedDate  = "2026-02-05"
    }
  }
}

data "aws_caller_identity" "current" {}
data "aws_region" "current" {}

# Local values
locals {
  cluster_name = "smart-dairy-prod-eks"
  environment  = "production"

  vpc_cidr = "10.0.0.0/16"
  azs      = ["ap-south-1a", "ap-south-1b", "ap-south-1c"]

  private_subnets  = ["10.0.1.0/24", "10.0.2.0/24", "10.0.3.0/24"]
  public_subnets   = ["10.0.101.0/24", "10.0.102.0/24", "10.0.103.0/24"]
  database_subnets = ["10.0.201.0/24", "10.0.202.0/24", "10.0.203.0/24"]
}
```

**Code: VPC Module**

```hcl
# infrastructure/production/vpc.tf

module "vpc" {
  source  = "terraform-aws-modules/vpc/aws"
  version = "5.4.0"

  name = "smart-dairy-prod-vpc"
  cidr = local.vpc_cidr

  azs              = local.azs
  private_subnets  = local.private_subnets
  public_subnets   = local.public_subnets
  database_subnets = local.database_subnets

  # NAT Gateway - One per AZ for high availability
  enable_nat_gateway     = true
  single_nat_gateway     = false
  one_nat_gateway_per_az = true

  # DNS settings
  enable_dns_hostnames = true
  enable_dns_support   = true

  # VPC Flow Logs
  enable_flow_log                      = true
  create_flow_log_cloudwatch_iam_role  = true
  create_flow_log_cloudwatch_log_group = true
  flow_log_max_aggregation_interval    = 60
  flow_log_destination_type            = "cloud-watch-logs"

  # Database subnet group
  create_database_subnet_group       = true
  create_database_subnet_route_table = true

  # Subnet tags for EKS
  public_subnet_tags = {
    "kubernetes.io/role/elb"                    = 1
    "kubernetes.io/cluster/${local.cluster_name}" = "shared"
  }

  private_subnet_tags = {
    "kubernetes.io/role/internal-elb"           = 1
    "kubernetes.io/cluster/${local.cluster_name}" = "shared"
  }

  tags = {
    Name = "smart-dairy-prod-vpc"
  }
}

# VPC Endpoints for AWS services (reduces NAT costs)
module "vpc_endpoints" {
  source  = "terraform-aws-modules/vpc/aws//modules/vpc-endpoints"
  version = "5.4.0"

  vpc_id = module.vpc.vpc_id

  endpoints = {
    s3 = {
      service         = "s3"
      service_type    = "Gateway"
      route_table_ids = module.vpc.private_route_table_ids
      tags            = { Name = "smart-dairy-s3-endpoint" }
    }
    ecr_api = {
      service             = "ecr.api"
      private_dns_enabled = true
      subnet_ids          = module.vpc.private_subnets
      security_group_ids  = [aws_security_group.vpc_endpoints.id]
      tags                = { Name = "smart-dairy-ecr-api-endpoint" }
    }
    ecr_dkr = {
      service             = "ecr.dkr"
      private_dns_enabled = true
      subnet_ids          = module.vpc.private_subnets
      security_group_ids  = [aws_security_group.vpc_endpoints.id]
      tags                = { Name = "smart-dairy-ecr-dkr-endpoint" }
    }
    logs = {
      service             = "logs"
      private_dns_enabled = true
      subnet_ids          = module.vpc.private_subnets
      security_group_ids  = [aws_security_group.vpc_endpoints.id]
      tags                = { Name = "smart-dairy-logs-endpoint" }
    }
  }
}

resource "aws_security_group" "vpc_endpoints" {
  name_prefix = "smart-dairy-vpc-endpoints-"
  description = "Security group for VPC endpoints"
  vpc_id      = module.vpc.vpc_id

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = [local.vpc_cidr]
    description = "HTTPS from VPC"
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Allow all outbound"
  }

  tags = {
    Name = "smart-dairy-vpc-endpoints-sg"
  }
}
```

**Code: EKS Cluster**

```hcl
# infrastructure/production/eks.tf

module "eks" {
  source  = "terraform-aws-modules/eks/aws"
  version = "19.21.0"

  cluster_name    = local.cluster_name
  cluster_version = "1.29"

  vpc_id     = module.vpc.vpc_id
  subnet_ids = module.vpc.private_subnets

  # Cluster endpoint access
  cluster_endpoint_public_access  = true
  cluster_endpoint_private_access = true

  # Cluster logging
  cluster_enabled_log_types = [
    "api",
    "audit",
    "authenticator",
    "controllerManager",
    "scheduler"
  ]

  # Cluster addons
  cluster_addons = {
    coredns = {
      most_recent = true
      configuration_values = jsonencode({
        replicaCount = 2
        resources = {
          limits = {
            cpu    = "100m"
            memory = "150Mi"
          }
          requests = {
            cpu    = "100m"
            memory = "70Mi"
          }
        }
      })
    }
    kube-proxy = {
      most_recent = true
    }
    vpc-cni = {
      most_recent              = true
      before_compute           = true
      service_account_role_arn = module.vpc_cni_irsa.iam_role_arn
      configuration_values = jsonencode({
        env = {
          ENABLE_PREFIX_DELEGATION = "true"
          WARM_PREFIX_TARGET       = "1"
        }
      })
    }
    aws-ebs-csi-driver = {
      most_recent              = true
      service_account_role_arn = module.ebs_csi_irsa.iam_role_arn
    }
  }

  # Managed node groups
  eks_managed_node_groups = {
    # Application nodes
    application = {
      name           = "smart-dairy-app-nodes"
      instance_types = ["t3.xlarge"]

      min_size     = 3
      max_size     = 15
      desired_size = 5

      capacity_type = "ON_DEMAND"

      labels = {
        role        = "application"
        environment = "production"
      }

      taints = []

      update_config = {
        max_unavailable_percentage = 25
      }

      iam_role_additional_policies = {
        AmazonSSMManagedInstanceCore = "arn:aws:iam::aws:policy/AmazonSSMManagedInstanceCore"
      }

      block_device_mappings = {
        xvda = {
          device_name = "/dev/xvda"
          ebs = {
            volume_size           = 100
            volume_type           = "gp3"
            iops                  = 3000
            throughput            = 125
            encrypted             = true
            delete_on_termination = true
          }
        }
      }

      tags = {
        NodeGroup = "application"
      }
    }

    # Database workload nodes (for StatefulSets if needed)
    database = {
      name           = "smart-dairy-db-nodes"
      instance_types = ["r6i.xlarge"]

      min_size     = 2
      max_size     = 6
      desired_size = 3

      capacity_type = "ON_DEMAND"

      labels = {
        role = "database"
      }

      taints = [{
        key    = "dedicated"
        value  = "database"
        effect = "NO_SCHEDULE"
      }]

      block_device_mappings = {
        xvda = {
          device_name = "/dev/xvda"
          ebs = {
            volume_size           = 200
            volume_type           = "gp3"
            iops                  = 6000
            throughput            = 250
            encrypted             = true
            delete_on_termination = true
          }
        }
      }

      tags = {
        NodeGroup = "database"
      }
    }
  }

  # aws-auth configmap
  manage_aws_auth_configmap = true

  aws_auth_roles = [
    {
      rolearn  = "arn:aws:iam::${data.aws_caller_identity.current.account_id}:role/SmartDairyAdminRole"
      username = "admin"
      groups   = ["system:masters"]
    }
  ]

  tags = {
    Name = local.cluster_name
  }
}

# IRSA for VPC CNI
module "vpc_cni_irsa" {
  source  = "terraform-aws-modules/iam/aws//modules/iam-role-for-service-accounts-eks"
  version = "5.32.0"

  role_name             = "smart-dairy-vpc-cni-irsa"
  attach_vpc_cni_policy = true
  vpc_cni_enable_ipv4   = true

  oidc_providers = {
    main = {
      provider_arn               = module.eks.oidc_provider_arn
      namespace_service_accounts = ["kube-system:aws-node"]
    }
  }
}

# IRSA for EBS CSI Driver
module "ebs_csi_irsa" {
  source  = "terraform-aws-modules/iam/aws//modules/iam-role-for-service-accounts-eks"
  version = "5.32.0"

  role_name             = "smart-dairy-ebs-csi-irsa"
  attach_ebs_csi_policy = true

  oidc_providers = {
    main = {
      provider_arn               = module.eks.oidc_provider_arn
      namespace_service_accounts = ["kube-system:ebs-csi-controller-sa"]
    }
  }
}
```

**End-of-Day 701 Deliverables:**

- [ ] Terraform S3 backend configured with state locking
- [ ] VPC deployed with 9 subnets across 3 AZs
- [ ] NAT gateways operational (3x for HA)
- [ ] VPC endpoints for S3, ECR, CloudWatch Logs
- [ ] EKS control plane deployed with logging
- [ ] All IAM roles and policies created

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Configure AWS CLI and kubectl for EKS access — 1h
2. Set up S3 buckets for static assets and backups — 2h
3. Configure Route 53 hosted zones — 1.5h
4. Request and validate ACM SSL certificates — 1.5h
5. Initialize GitHub Actions secrets for production — 2h

**Code: S3 Buckets**

```hcl
# infrastructure/production/s3.tf

# Static assets bucket
resource "aws_s3_bucket" "assets" {
  bucket = "smart-dairy-prod-assets"

  tags = {
    Name        = "smart-dairy-prod-assets"
    Purpose     = "Static assets CDN origin"
  }
}

resource "aws_s3_bucket_versioning" "assets" {
  bucket = aws_s3_bucket.assets.id

  versioning_configuration {
    status = "Enabled"
  }
}

resource "aws_s3_bucket_server_side_encryption_configuration" "assets" {
  bucket = aws_s3_bucket.assets.id

  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm = "AES256"
    }
  }
}

resource "aws_s3_bucket_public_access_block" "assets" {
  bucket = aws_s3_bucket.assets.id

  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

resource "aws_s3_bucket_cors_configuration" "assets" {
  bucket = aws_s3_bucket.assets.id

  cors_rule {
    allowed_headers = ["*"]
    allowed_methods = ["GET", "HEAD"]
    allowed_origins = ["https://smartdairy.com.bd", "https://www.smartdairy.com.bd"]
    expose_headers  = ["ETag"]
    max_age_seconds = 3600
  }
}

# Backups bucket
resource "aws_s3_bucket" "backups" {
  bucket = "smart-dairy-prod-backups"

  tags = {
    Name    = "smart-dairy-prod-backups"
    Purpose = "Database and application backups"
  }
}

resource "aws_s3_bucket_versioning" "backups" {
  bucket = aws_s3_bucket.backups.id

  versioning_configuration {
    status = "Enabled"
  }
}

resource "aws_s3_bucket_server_side_encryption_configuration" "backups" {
  bucket = aws_s3_bucket.backups.id

  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm     = "aws:kms"
      kms_master_key_id = aws_kms_key.backup.arn
    }
  }
}

resource "aws_s3_bucket_lifecycle_configuration" "backups" {
  bucket = aws_s3_bucket.backups.id

  rule {
    id     = "archive-old-backups"
    status = "Enabled"

    transition {
      days          = 30
      storage_class = "STANDARD_IA"
    }

    transition {
      days          = 90
      storage_class = "GLACIER"
    }

    expiration {
      days = 365
    }
  }
}

# KMS key for backup encryption
resource "aws_kms_key" "backup" {
  description             = "KMS key for Smart Dairy backup encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  tags = {
    Name = "smart-dairy-backup-kms"
  }
}

resource "aws_kms_alias" "backup" {
  name          = "alias/smart-dairy-backup"
  target_key_id = aws_kms_key.backup.key_id
}
```

**Code: Route 53 and ACM**

```hcl
# infrastructure/production/dns.tf

# Route 53 Hosted Zone
data "aws_route53_zone" "main" {
  name         = "smartdairy.com.bd"
  private_zone = false
}

# ACM Certificate for main domain
resource "aws_acm_certificate" "main" {
  domain_name       = "smartdairy.com.bd"
  validation_method = "DNS"

  subject_alternative_names = [
    "*.smartdairy.com.bd",
    "api.smartdairy.com.bd",
    "cdn.smartdairy.com.bd",
    "app.smartdairy.com.bd"
  ]

  lifecycle {
    create_before_destroy = true
  }

  tags = {
    Name = "smart-dairy-prod-cert"
  }
}

# DNS validation records
resource "aws_route53_record" "cert_validation" {
  for_each = {
    for dvo in aws_acm_certificate.main.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  allow_overwrite = true
  name            = each.value.name
  records         = [each.value.record]
  ttl             = 60
  type            = each.value.type
  zone_id         = data.aws_route53_zone.main.zone_id
}

# Certificate validation
resource "aws_acm_certificate_validation" "main" {
  certificate_arn         = aws_acm_certificate.main.arn
  validation_record_fqdns = [for record in aws_route53_record.cert_validation : record.fqdn]
}

# ACM Certificate for CloudFront (must be in us-east-1)
provider "aws" {
  alias  = "us_east_1"
  region = "us-east-1"
}

resource "aws_acm_certificate" "cloudfront" {
  provider = aws.us_east_1

  domain_name       = "cdn.smartdairy.com.bd"
  validation_method = "DNS"

  lifecycle {
    create_before_destroy = true
  }

  tags = {
    Name = "smart-dairy-cdn-cert"
  }
}
```

**End-of-Day 701 Deliverables:**

- [ ] AWS CLI configured with production credentials
- [ ] kubectl configured for EKS cluster access
- [ ] S3 assets bucket created with versioning and encryption
- [ ] S3 backups bucket with lifecycle policies
- [ ] Route 53 hosted zone verified
- [ ] ACM certificates requested and DNS validation in progress
- [ ] GitHub Actions secrets configured

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Create infrastructure architecture diagram — 3h
2. Document production environment specifications — 2h
3. Set up runbook template structure — 2h
4. Configure mobile app production API endpoints — 1h

**Deliverable: Infrastructure Architecture Diagram**

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY PRODUCTION ARCHITECTURE                            │
│                                   AWS ap-south-1                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘

                                    ┌──────────────┐
                                    │   Route 53   │
                                    │   DNS        │
                                    └──────┬───────┘
                                           │
                    ┌──────────────────────┼──────────────────────┐
                    │                      │                      │
                    ▼                      ▼                      ▼
           ┌────────────────┐    ┌────────────────┐    ┌────────────────┐
           │   CloudFront   │    │      ALB       │    │   API Gateway  │
           │   (CDN)        │    │ (Load Balancer)│    │   (FastAPI)    │
           └───────┬────────┘    └───────┬────────┘    └───────┬────────┘
                   │                     │                     │
                   │              ┌──────┴──────┐              │
                   │              │             │              │
                   ▼              ▼             ▼              ▼
           ┌────────────┐  ┌───────────┐ ┌───────────┐ ┌───────────┐
           │     S3     │  │    EKS    │ │    EKS    │ │    EKS    │
           │  (Assets)  │  │  Node 1   │ │  Node 2   │ │  Node 3   │
           └────────────┘  │   AZ-a    │ │   AZ-b    │ │   AZ-c    │
                           └─────┬─────┘ └─────┬─────┘ └─────┬─────┘
                                 │             │             │
                           ┌─────┴─────────────┴─────────────┴─────┐
                           │                                       │
                    ┌──────┴──────┐                        ┌───────┴───────┐
                    │             │                        │               │
                    ▼             ▼                        ▼               ▼
             ┌───────────┐ ┌───────────┐            ┌───────────┐ ┌───────────┐
             │    RDS    │ │    RDS    │            │ ElastiCache│ │ElastiCache│
             │  Primary  │ │  Replica  │            │   Redis    │ │   Redis   │
             │   AZ-a    │ │   AZ-b    │            │  Node 1    │ │  Node 2   │
             └───────────┘ └───────────┘            └───────────┘ └───────────┘

                           ┌─────────────────────────────────────────┐
                           │           MONITORING LAYER              │
                           ├─────────────────────────────────────────┤
                           │  Prometheus  │  Grafana  │  ELK Stack   │
                           │  (Metrics)   │ (Dashboards) │ (Logs)    │
                           └─────────────────────────────────────────┘

VPC: 10.0.0.0/16
├── Public Subnets:   10.0.101.0/24, 10.0.102.0/24, 10.0.103.0/24
├── Private Subnets:  10.0.1.0/24, 10.0.2.0/24, 10.0.3.0/24
└── Database Subnets: 10.0.201.0/24, 10.0.202.0/24, 10.0.203.0/24
```

**End-of-Day 701 Deliverables:**

- [ ] Infrastructure architecture diagram (PNG/SVG)
- [ ] Production environment specification document
- [ ] Runbook template structure created
- [ ] Mobile app production API endpoints configured in config files

---

### Day 702 — Database & Caching Infrastructure

**Objective:** Deploy Amazon RDS PostgreSQL 16 Multi-AZ with read replicas and Amazon ElastiCache Redis 7 cluster for production workloads.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Deploy RDS PostgreSQL 16 Multi-AZ primary — 3h
2. Configure RDS read replica — 1.5h
3. Set up automated backup configuration — 1h
4. Configure RDS parameter group for performance — 1.5h
5. Test RDS connectivity from EKS — 1h

**Code: RDS Configuration**

```hcl
# infrastructure/production/rds.tf

# DB Subnet Group
resource "aws_db_subnet_group" "main" {
  name       = "smart-dairy-prod-db-subnet"
  subnet_ids = module.vpc.database_subnets

  tags = {
    Name = "smart-dairy-prod-db-subnet-group"
  }
}

# RDS Parameter Group
resource "aws_db_parameter_group" "postgres16" {
  family = "postgres16"
  name   = "smart-dairy-prod-postgres16"

  parameter {
    name  = "log_statement"
    value = "ddl"
  }

  parameter {
    name  = "log_min_duration_statement"
    value = "1000"  # Log queries > 1 second
  }

  parameter {
    name  = "shared_preload_libraries"
    value = "pg_stat_statements,auto_explain"
  }

  parameter {
    name  = "pg_stat_statements.track"
    value = "all"
  }

  parameter {
    name  = "auto_explain.log_min_duration"
    value = "5000"  # Auto-explain queries > 5 seconds
  }

  parameter {
    name  = "max_connections"
    value = "500"
  }

  parameter {
    name  = "work_mem"
    value = "256000"  # 256MB
  }

  parameter {
    name  = "maintenance_work_mem"
    value = "1024000"  # 1GB
  }

  parameter {
    name  = "effective_cache_size"
    value = "12582912"  # 12GB
  }

  parameter {
    name  = "random_page_cost"
    value = "1.1"  # SSD optimized
  }

  tags = {
    Name = "smart-dairy-prod-postgres16-params"
  }
}

# Security Group for RDS
resource "aws_security_group" "rds" {
  name_prefix = "smart-dairy-rds-"
  description = "Security group for RDS PostgreSQL"
  vpc_id      = module.vpc.vpc_id

  ingress {
    from_port       = 5432
    to_port         = 5432
    protocol        = "tcp"
    security_groups = [module.eks.node_security_group_id]
    description     = "PostgreSQL from EKS nodes"
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Allow all outbound"
  }

  tags = {
    Name = "smart-dairy-rds-sg"
  }
}

# RDS Primary Instance (Multi-AZ)
resource "aws_db_instance" "primary" {
  identifier = "smart-dairy-prod-db-primary"

  engine               = "postgres"
  engine_version       = "16.1"
  instance_class       = "db.r6i.2xlarge"
  allocated_storage    = 500
  max_allocated_storage = 2000
  storage_type         = "gp3"
  storage_encrypted    = true
  kms_key_id           = aws_kms_key.rds.arn

  db_name  = "smart_dairy_prod"
  username = var.db_master_username
  password = var.db_master_password
  port     = 5432

  vpc_security_group_ids = [aws_security_group.rds.id]
  db_subnet_group_name   = aws_db_subnet_group.main.name
  parameter_group_name   = aws_db_parameter_group.postgres16.name

  # High Availability
  multi_az = true

  # Backup configuration
  backup_retention_period = 30
  backup_window          = "03:00-04:00"
  maintenance_window     = "sun:04:00-sun:05:00"
  copy_tags_to_snapshot  = true
  skip_final_snapshot    = false
  final_snapshot_identifier = "smart-dairy-prod-final-${formatdate("YYYY-MM-DD", timestamp())}"

  # Logging
  enabled_cloudwatch_logs_exports = ["postgresql", "upgrade"]

  # Monitoring
  monitoring_interval = 60
  monitoring_role_arn = aws_iam_role.rds_monitoring.arn

  # Performance Insights
  performance_insights_enabled          = true
  performance_insights_retention_period = 7
  performance_insights_kms_key_id       = aws_kms_key.rds.arn

  # Protection
  deletion_protection = true

  tags = {
    Name = "smart-dairy-prod-db-primary"
  }

  lifecycle {
    prevent_destroy = true
  }
}

# RDS Read Replica
resource "aws_db_instance" "replica" {
  identifier = "smart-dairy-prod-db-replica"

  replicate_source_db = aws_db_instance.primary.identifier

  instance_class    = "db.r6i.xlarge"
  storage_encrypted = true

  vpc_security_group_ids = [aws_security_group.rds.id]
  parameter_group_name   = aws_db_parameter_group.postgres16.name

  # Backup (replicas can have their own backup)
  backup_retention_period = 7

  # Monitoring
  monitoring_interval = 60
  monitoring_role_arn = aws_iam_role.rds_monitoring.arn

  # Performance Insights
  performance_insights_enabled          = true
  performance_insights_retention_period = 7

  tags = {
    Name = "smart-dairy-prod-db-replica"
  }
}

# KMS key for RDS encryption
resource "aws_kms_key" "rds" {
  description             = "KMS key for Smart Dairy RDS encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  tags = {
    Name = "smart-dairy-rds-kms"
  }
}

resource "aws_kms_alias" "rds" {
  name          = "alias/smart-dairy-rds"
  target_key_id = aws_kms_key.rds.key_id
}

# IAM Role for RDS Enhanced Monitoring
resource "aws_iam_role" "rds_monitoring" {
  name = "smart-dairy-rds-monitoring-role"

  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Action = "sts:AssumeRole"
      Effect = "Allow"
      Principal = {
        Service = "monitoring.rds.amazonaws.com"
      }
    }]
  })
}

resource "aws_iam_role_policy_attachment" "rds_monitoring" {
  role       = aws_iam_role.rds_monitoring.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonRDSEnhancedMonitoringRole"
}

# Outputs
output "rds_primary_endpoint" {
  description = "RDS primary endpoint"
  value       = aws_db_instance.primary.endpoint
  sensitive   = true
}

output "rds_replica_endpoint" {
  description = "RDS replica endpoint"
  value       = aws_db_instance.replica.endpoint
  sensitive   = true
}
```

**End-of-Day 702 Deliverables (Dev 1):**

- [ ] RDS PostgreSQL 16 primary deployed (Multi-AZ)
- [ ] RDS read replica configured
- [ ] Automated backups enabled (30-day retention)
- [ ] Performance Insights enabled
- [ ] RDS connectivity verified from EKS

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Deploy ElastiCache Redis 7 cluster — 3h
2. Configure Redis authentication and encryption — 1.5h
3. Set up CloudFront distribution — 2h
4. Configure Application Load Balancer — 1.5h

**Code: ElastiCache Redis**

```hcl
# infrastructure/production/elasticache.tf

# ElastiCache Subnet Group
resource "aws_elasticache_subnet_group" "redis" {
  name       = "smart-dairy-prod-redis-subnet"
  subnet_ids = module.vpc.private_subnets

  tags = {
    Name = "smart-dairy-prod-redis-subnet-group"
  }
}

# Security Group for Redis
resource "aws_security_group" "redis" {
  name_prefix = "smart-dairy-redis-"
  description = "Security group for ElastiCache Redis"
  vpc_id      = module.vpc.vpc_id

  ingress {
    from_port       = 6379
    to_port         = 6379
    protocol        = "tcp"
    security_groups = [module.eks.node_security_group_id]
    description     = "Redis from EKS nodes"
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Allow all outbound"
  }

  tags = {
    Name = "smart-dairy-redis-sg"
  }
}

# ElastiCache Parameter Group
resource "aws_elasticache_parameter_group" "redis7" {
  family = "redis7"
  name   = "smart-dairy-prod-redis7"

  parameter {
    name  = "maxmemory-policy"
    value = "allkeys-lru"
  }

  parameter {
    name  = "notify-keyspace-events"
    value = "Ex"
  }

  tags = {
    Name = "smart-dairy-prod-redis7-params"
  }
}

# ElastiCache Replication Group (Redis Cluster)
resource "aws_elasticache_replication_group" "redis" {
  replication_group_id       = "smart-dairy-prod-redis"
  description                = "Smart Dairy production Redis cluster"

  engine               = "redis"
  engine_version       = "7.0"
  node_type            = "cache.r6g.large"
  num_cache_clusters   = 3
  port                 = 6379

  subnet_group_name  = aws_elasticache_subnet_group.redis.name
  security_group_ids = [aws_security_group.redis.id]
  parameter_group_name = aws_elasticache_parameter_group.redis7.name

  # High Availability
  automatic_failover_enabled = true
  multi_az_enabled          = true

  # Security
  at_rest_encryption_enabled = true
  transit_encryption_enabled = true
  auth_token                 = var.redis_auth_token
  kms_key_id                = aws_kms_key.redis.arn

  # Maintenance
  maintenance_window = "sun:05:00-sun:07:00"

  # Snapshots
  snapshot_retention_limit = 7
  snapshot_window         = "03:00-05:00"

  # Logging
  log_delivery_configuration {
    destination      = aws_cloudwatch_log_group.redis_slow_log.name
    destination_type = "cloudwatch-logs"
    log_format       = "json"
    log_type         = "slow-log"
  }

  log_delivery_configuration {
    destination      = aws_cloudwatch_log_group.redis_engine_log.name
    destination_type = "cloudwatch-logs"
    log_format       = "json"
    log_type         = "engine-log"
  }

  tags = {
    Name = "smart-dairy-prod-redis"
  }
}

# CloudWatch Log Groups for Redis
resource "aws_cloudwatch_log_group" "redis_slow_log" {
  name              = "/aws/elasticache/smart-dairy-prod/redis/slow-log"
  retention_in_days = 30

  tags = {
    Name = "smart-dairy-redis-slow-log"
  }
}

resource "aws_cloudwatch_log_group" "redis_engine_log" {
  name              = "/aws/elasticache/smart-dairy-prod/redis/engine-log"
  retention_in_days = 30

  tags = {
    Name = "smart-dairy-redis-engine-log"
  }
}

# KMS key for Redis encryption
resource "aws_kms_key" "redis" {
  description             = "KMS key for Smart Dairy Redis encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  tags = {
    Name = "smart-dairy-redis-kms"
  }
}

resource "aws_kms_alias" "redis" {
  name          = "alias/smart-dairy-redis"
  target_key_id = aws_kms_key.redis.key_id
}

# Outputs
output "redis_primary_endpoint" {
  description = "Redis primary endpoint"
  value       = aws_elasticache_replication_group.redis.primary_endpoint_address
  sensitive   = true
}
```

**Code: CloudFront Distribution**

```hcl
# infrastructure/production/cloudfront.tf

# CloudFront Origin Access Identity
resource "aws_cloudfront_origin_access_identity" "assets" {
  comment = "Smart Dairy Assets OAI"
}

# S3 Bucket Policy for CloudFront
resource "aws_s3_bucket_policy" "assets" {
  bucket = aws_s3_bucket.assets.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Sid       = "AllowCloudFrontAccess"
      Effect    = "Allow"
      Principal = {
        AWS = aws_cloudfront_origin_access_identity.assets.iam_arn
      }
      Action   = "s3:GetObject"
      Resource = "${aws_s3_bucket.assets.arn}/*"
    }]
  })
}

# CloudFront Distribution
resource "aws_cloudfront_distribution" "main" {
  enabled             = true
  is_ipv6_enabled     = true
  http_version        = "http2and3"
  price_class         = "PriceClass_200"
  default_root_object = "index.html"
  comment             = "Smart Dairy Production CDN"

  aliases = ["cdn.smartdairy.com.bd"]

  # S3 Origin for static assets
  origin {
    domain_name = aws_s3_bucket.assets.bucket_regional_domain_name
    origin_id   = "S3-smart-dairy-assets"

    s3_origin_config {
      origin_access_identity = aws_cloudfront_origin_access_identity.assets.cloudfront_access_identity_path
    }
  }

  # API Origin (ALB)
  origin {
    domain_name = aws_lb.main.dns_name
    origin_id   = "ALB-smart-dairy-api"

    custom_origin_config {
      http_port              = 80
      https_port             = 443
      origin_protocol_policy = "https-only"
      origin_ssl_protocols   = ["TLSv1.2"]
    }
  }

  # Default cache behavior (S3 static assets)
  default_cache_behavior {
    allowed_methods  = ["GET", "HEAD", "OPTIONS"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "S3-smart-dairy-assets"

    forwarded_values {
      query_string = false
      cookies {
        forward = "none"
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 0
    default_ttl            = 86400     # 1 day
    max_ttl                = 31536000  # 1 year
    compress               = true
  }

  # API cache behavior
  ordered_cache_behavior {
    path_pattern     = "/api/*"
    allowed_methods  = ["DELETE", "GET", "HEAD", "OPTIONS", "PATCH", "POST", "PUT"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "ALB-smart-dairy-api"

    forwarded_values {
      query_string = true
      headers      = ["Authorization", "Origin", "Accept", "Accept-Language"]
      cookies {
        forward = "all"
      }
    }

    viewer_protocol_policy = "https-only"
    min_ttl                = 0
    default_ttl            = 0
    max_ttl                = 0
  }

  # Custom error responses
  custom_error_response {
    error_code         = 403
    response_code      = 200
    response_page_path = "/index.html"
  }

  custom_error_response {
    error_code         = 404
    response_code      = 200
    response_page_path = "/index.html"
  }

  # Geo restriction
  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  # SSL Certificate
  viewer_certificate {
    acm_certificate_arn      = aws_acm_certificate.cloudfront.arn
    ssl_support_method       = "sni-only"
    minimum_protocol_version = "TLSv1.2_2021"
  }

  # WAF Web ACL (will be created in Milestone 143)
  # web_acl_id = aws_wafv2_web_acl.main.arn

  tags = {
    Name = "smart-dairy-prod-cdn"
  }
}

# Route 53 record for CDN
resource "aws_route53_record" "cdn" {
  zone_id = data.aws_route53_zone.main.zone_id
  name    = "cdn.smartdairy.com.bd"
  type    = "A"

  alias {
    name                   = aws_cloudfront_distribution.main.domain_name
    zone_id                = aws_cloudfront_distribution.main.hosted_zone_id
    evaluate_target_health = false
  }
}

# Output
output "cloudfront_distribution_id" {
  description = "CloudFront distribution ID"
  value       = aws_cloudfront_distribution.main.id
}

output "cloudfront_domain_name" {
  description = "CloudFront domain name"
  value       = aws_cloudfront_distribution.main.domain_name
}
```

**End-of-Day 702 Deliverables (Dev 2):**

- [ ] ElastiCache Redis 7 cluster deployed (3 nodes)
- [ ] Redis authentication and encryption enabled
- [ ] CloudFront distribution configured
- [ ] Route 53 record for CDN created

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Document database architecture specifications — 3h
2. Create environment configuration guide — 2.5h
3. Update mobile app production endpoints — 2.5h

**End-of-Day 702 Deliverables (Dev 3):**

- [ ] Database architecture document complete
- [ ] Environment configuration guide created
- [ ] Mobile app production endpoints configured

---

### Day 703 — Kubernetes Production Configuration

**Objective:** Configure Kubernetes namespaces, deployments, services, ingress, and auto-scaling for production workloads.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create Kubernetes namespaces and RBAC — 2h
2. Configure storage classes and persistent volumes — 2h
3. Deploy network policies for security — 2h
4. Configure resource quotas and limits — 2h

**Code: Kubernetes Namespace and RBAC**

```yaml
# kubernetes/production/namespace.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: smart-dairy-prod
  labels:
    name: smart-dairy-prod
    environment: production
    istio-injection: enabled

---
# kubernetes/production/resource-quota.yaml
apiVersion: v1
kind: ResourceQuota
metadata:
  name: production-quota
  namespace: smart-dairy-prod
spec:
  hard:
    requests.cpu: "50"
    requests.memory: 100Gi
    limits.cpu: "100"
    limits.memory: 200Gi
    pods: "200"
    services: "50"
    persistentvolumeclaims: "20"

---
# kubernetes/production/limit-range.yaml
apiVersion: v1
kind: LimitRange
metadata:
  name: default-limits
  namespace: smart-dairy-prod
spec:
  limits:
    - default:
        cpu: "1"
        memory: "2Gi"
      defaultRequest:
        cpu: "250m"
        memory: "512Mi"
      max:
        cpu: "4"
        memory: "8Gi"
      min:
        cpu: "100m"
        memory: "128Mi"
      type: Container

---
# kubernetes/production/network-policy.yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: default-deny-ingress
  namespace: smart-dairy-prod
spec:
  podSelector: {}
  policyTypes:
    - Ingress

---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: allow-backend-ingress
  namespace: smart-dairy-prod
spec:
  podSelector:
    matchLabels:
      app: backend
  policyTypes:
    - Ingress
  ingress:
    - from:
        - namespaceSelector:
            matchLabels:
              name: ingress-nginx
        - podSelector:
            matchLabels:
              app: frontend
      ports:
        - protocol: TCP
          port: 8069

---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: allow-backend-egress
  namespace: smart-dairy-prod
spec:
  podSelector:
    matchLabels:
      app: backend
  policyTypes:
    - Egress
  egress:
    # Allow DNS
    - to: []
      ports:
        - protocol: UDP
          port: 53
    # Allow PostgreSQL
    - to:
        - ipBlock:
            cidr: 10.0.201.0/24
      ports:
        - protocol: TCP
          port: 5432
    # Allow Redis
    - to:
        - ipBlock:
            cidr: 10.0.1.0/24
      ports:
        - protocol: TCP
          port: 6379
    # Allow HTTPS outbound (for external APIs)
    - to: []
      ports:
        - protocol: TCP
          port: 443
```

**End-of-Day 703 Deliverables (Dev 1):**

- [ ] Kubernetes namespaces created
- [ ] RBAC roles and bindings configured
- [ ] Storage classes for GP3 volumes
- [ ] Network policies applied
- [ ] Resource quotas and limits set

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Deploy backend and frontend deployments — 3h
2. Configure services and ingress — 2h
3. Set up Horizontal Pod Autoscaler — 1.5h
4. Configure Pod Disruption Budgets — 1.5h

**Code: Kubernetes Deployments**

```yaml
# kubernetes/production/deployment-backend.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: odoo-backend
  namespace: smart-dairy-prod
  labels:
    app: backend
    version: v1
spec:
  replicas: 5
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 2
      maxUnavailable: 1
  selector:
    matchLabels:
      app: backend
  template:
    metadata:
      labels:
        app: backend
        version: v1
      annotations:
        prometheus.io/scrape: "true"
        prometheus.io/port: "8069"
        prometheus.io/path: "/metrics"
    spec:
      serviceAccountName: smart-dairy-backend

      affinity:
        podAntiAffinity:
          preferredDuringSchedulingIgnoredDuringExecution:
            - weight: 100
              podAffinityTerm:
                labelSelector:
                  matchExpressions:
                    - key: app
                      operator: In
                      values:
                        - backend
                topologyKey: kubernetes.io/hostname
        nodeAffinity:
          requiredDuringSchedulingIgnoredDuringExecution:
            nodeSelectorTerms:
              - matchExpressions:
                  - key: role
                    operator: In
                    values:
                      - application

      containers:
        - name: odoo
          image: ${ECR_REGISTRY}/smart-dairy-backend:${IMAGE_TAG}
          imagePullPolicy: Always

          ports:
            - name: http
              containerPort: 8069
              protocol: TCP
            - name: longpolling
              containerPort: 8072
              protocol: TCP

          envFrom:
            - configMapRef:
                name: odoo-config
            - secretRef:
                name: odoo-secrets

          resources:
            requests:
              cpu: 500m
              memory: 1Gi
            limits:
              cpu: 2000m
              memory: 4Gi

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

          lifecycle:
            preStop:
              exec:
                command: ["/bin/sh", "-c", "sleep 15"]

          volumeMounts:
            - name: odoo-data
              mountPath: /var/lib/odoo
            - name: odoo-addons
              mountPath: /mnt/extra-addons

      volumes:
        - name: odoo-data
          persistentVolumeClaim:
            claimName: odoo-data-pvc
        - name: odoo-addons
          emptyDir: {}

      terminationGracePeriodSeconds: 60

---
# kubernetes/production/hpa-backend.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-backend-hpa
  namespace: smart-dairy-prod
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-backend
  minReplicas: 5
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
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
        - type: Percent
          value: 25
          periodSeconds: 60
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
        - type: Percent
          value: 100
          periodSeconds: 30
        - type: Pods
          value: 4
          periodSeconds: 30
      selectPolicy: Max

---
# kubernetes/production/pdb-backend.yaml
apiVersion: policy/v1
kind: PodDisruptionBudget
metadata:
  name: odoo-backend-pdb
  namespace: smart-dairy-prod
spec:
  minAvailable: 3
  selector:
    matchLabels:
      app: backend

---
# kubernetes/production/service-backend.yaml
apiVersion: v1
kind: Service
metadata:
  name: odoo-backend-svc
  namespace: smart-dairy-prod
  labels:
    app: backend
spec:
  type: ClusterIP
  ports:
    - name: http
      port: 80
      targetPort: 8069
      protocol: TCP
    - name: longpolling
      port: 8072
      targetPort: 8072
      protocol: TCP
  selector:
    app: backend

---
# kubernetes/production/ingress.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: smart-dairy-ingress
  namespace: smart-dairy-prod
  annotations:
    kubernetes.io/ingress.class: alb
    alb.ingress.kubernetes.io/scheme: internet-facing
    alb.ingress.kubernetes.io/target-type: ip
    alb.ingress.kubernetes.io/ssl-redirect: "443"
    alb.ingress.kubernetes.io/certificate-arn: ${ACM_CERTIFICATE_ARN}
    alb.ingress.kubernetes.io/listen-ports: '[{"HTTP": 80}, {"HTTPS": 443}]'
    alb.ingress.kubernetes.io/healthcheck-path: /web/health
    alb.ingress.kubernetes.io/healthcheck-interval-seconds: "30"
    alb.ingress.kubernetes.io/healthcheck-timeout-seconds: "5"
    alb.ingress.kubernetes.io/healthy-threshold-count: "2"
    alb.ingress.kubernetes.io/unhealthy-threshold-count: "3"
    alb.ingress.kubernetes.io/target-group-attributes: stickiness.enabled=true,stickiness.lb_cookie.duration_seconds=86400
spec:
  ingressClassName: alb
  rules:
    - host: app.smartdairy.com.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: odoo-backend-svc
                port:
                  number: 80
    - host: api.smartdairy.com.bd
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: fastapi-gateway-svc
                port:
                  number: 80
```

**End-of-Day 703 Deliverables (Dev 2):**

- [ ] Backend deployment configured (5 replicas)
- [ ] Frontend deployment configured (3 replicas)
- [ ] Services created for all applications
- [ ] ALB Ingress configured with SSL
- [ ] HPA configured for auto-scaling
- [ ] PDB configured for availability

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Document Kubernetes architecture — 3h
2. Create deployment runbooks — 3h
3. Update documentation with configuration details — 2h

**End-of-Day 703 Deliverables (Dev 3):**

- [ ] Kubernetes architecture document
- [ ] Deployment runbook created
- [ ] Configuration documentation updated

---

### Day 704 — Monitoring & Observability

**Objective:** Deploy comprehensive monitoring stack with Prometheus, Grafana, AlertManager, and ELK for production observability.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Configure Prometheus for custom metrics — 3h
2. Set up database monitoring (PostgreSQL exporter) — 2.5h
3. Configure Redis monitoring exporter — 2.5h

**End-of-Day 704 Deliverables (Dev 1):**

- [ ] Prometheus custom metrics configured
- [ ] PostgreSQL exporter deployed
- [ ] Redis exporter deployed
- [ ] Database dashboards created

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Deploy Prometheus using Helm — 2h
2. Deploy Grafana with production dashboards — 2h
3. Configure AlertManager with PagerDuty — 2h
4. Deploy ELK stack for log aggregation — 2h

**Code: Prometheus Helm Values**

```yaml
# kubernetes/monitoring/prometheus-values.yaml
prometheus:
  prometheusSpec:
    replicas: 2
    retention: 30d
    retentionSize: 50GB

    resources:
      requests:
        cpu: 500m
        memory: 2Gi
      limits:
        cpu: 2000m
        memory: 8Gi

    storageSpec:
      volumeClaimTemplate:
        spec:
          storageClassName: gp3
          accessModes: ["ReadWriteOnce"]
          resources:
            requests:
              storage: 100Gi

    additionalScrapeConfigs:
      - job_name: 'smart-dairy-backend'
        kubernetes_sd_configs:
          - role: pod
            namespaces:
              names:
                - smart-dairy-prod
        relabel_configs:
          - source_labels: [__meta_kubernetes_pod_label_app]
            action: keep
            regex: backend
          - source_labels: [__meta_kubernetes_pod_annotation_prometheus_io_scrape]
            action: keep
            regex: true

      - job_name: 'postgresql'
        static_configs:
          - targets:
              - postgres-exporter.monitoring:9187

      - job_name: 'redis'
        static_configs:
          - targets:
              - redis-exporter.monitoring:9121

alertmanager:
  alertmanagerSpec:
    replicas: 2
    storage:
      volumeClaimTemplate:
        spec:
          storageClassName: gp3
          accessModes: ["ReadWriteOnce"]
          resources:
            requests:
              storage: 10Gi

  config:
    global:
      resolve_timeout: 5m
      slack_api_url: '${SLACK_WEBHOOK_URL}'
      pagerduty_url: 'https://events.pagerduty.com/v2/enqueue'

    route:
      group_by: ['alertname', 'cluster', 'service']
      group_wait: 10s
      group_interval: 10s
      repeat_interval: 12h
      receiver: 'slack-critical'
      routes:
        - match:
            severity: critical
          receiver: pagerduty-critical
          continue: true
        - match:
            severity: critical
          receiver: slack-critical
        - match:
            severity: warning
          receiver: slack-warning

    receivers:
      - name: 'pagerduty-critical'
        pagerduty_configs:
          - service_key: '${PAGERDUTY_SERVICE_KEY}'
            severity: critical
            description: '{{ .CommonAnnotations.description }}'

      - name: 'slack-critical'
        slack_configs:
          - channel: '#smart-dairy-alerts-critical'
            title: ':fire: {{ .GroupLabels.alertname }}'
            text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'
            send_resolved: true

      - name: 'slack-warning'
        slack_configs:
          - channel: '#smart-dairy-alerts-warning'
            title: ':warning: {{ .GroupLabels.alertname }}'
            text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'
            send_resolved: true

grafana:
  replicas: 2

  adminPassword: '${GRAFANA_ADMIN_PASSWORD}'

  persistence:
    enabled: true
    storageClassName: gp3
    size: 20Gi

  resources:
    requests:
      cpu: 200m
      memory: 512Mi
    limits:
      cpu: 1000m
      memory: 2Gi

  dashboardProviders:
    dashboardproviders.yaml:
      apiVersion: 1
      providers:
        - name: 'smart-dairy'
          orgId: 1
          folder: 'Smart Dairy'
          type: file
          disableDeletion: false
          editable: true
          options:
            path: /var/lib/grafana/dashboards/smart-dairy

  dashboards:
    smart-dairy:
      kubernetes-cluster:
        gnetId: 6417
        revision: 1
        datasource: Prometheus
      postgresql:
        gnetId: 9628
        revision: 7
        datasource: Prometheus
      redis:
        gnetId: 11835
        revision: 1
        datasource: Prometheus
      nginx:
        gnetId: 9614
        revision: 1
        datasource: Prometheus
```

**Code: Alert Rules**

```yaml
# kubernetes/monitoring/prometheus-rules.yaml
apiVersion: monitoring.coreos.com/v1
kind: PrometheusRule
metadata:
  name: smart-dairy-alerts
  namespace: monitoring
spec:
  groups:
    - name: smart-dairy.rules
      rules:
        # High CPU Usage
        - alert: HighCPUUsage
          expr: |
            (sum(rate(container_cpu_usage_seconds_total{namespace="smart-dairy-prod"}[5m])) by (pod)
            / sum(container_spec_cpu_quota{namespace="smart-dairy-prod"}/container_spec_cpu_period{namespace="smart-dairy-prod"}) by (pod)) > 0.85
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "High CPU usage detected"
            description: "Pod {{ $labels.pod }} has high CPU usage ({{ $value | humanizePercentage }})"

        # High Memory Usage
        - alert: HighMemoryUsage
          expr: |
            (sum(container_memory_working_set_bytes{namespace="smart-dairy-prod"}) by (pod)
            / sum(container_spec_memory_limit_bytes{namespace="smart-dairy-prod"}) by (pod)) > 0.85
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "High memory usage detected"
            description: "Pod {{ $labels.pod }} has high memory usage ({{ $value | humanizePercentage }})"

        # Pod Not Ready
        - alert: PodNotReady
          expr: |
            kube_pod_status_ready{namespace="smart-dairy-prod", condition="true"} == 0
          for: 5m
          labels:
            severity: critical
          annotations:
            summary: "Pod not ready"
            description: "Pod {{ $labels.pod }} has been not ready for more than 5 minutes"

        # High Error Rate
        - alert: HighErrorRate
          expr: |
            (sum(rate(http_requests_total{namespace="smart-dairy-prod", status=~"5.."}[5m]))
            / sum(rate(http_requests_total{namespace="smart-dairy-prod"}[5m]))) > 0.05
          for: 5m
          labels:
            severity: critical
          annotations:
            summary: "High HTTP error rate"
            description: "HTTP 5xx error rate is above 5% ({{ $value | humanizePercentage }})"

        # Database Connection Issues
        - alert: DatabaseConnectionsHigh
          expr: |
            pg_stat_activity_count > 400
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "High database connections"
            description: "PostgreSQL connections are high ({{ $value }})"

        # Redis Memory High
        - alert: RedisMemoryHigh
          expr: |
            redis_memory_used_bytes / redis_memory_max_bytes > 0.85
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "Redis memory usage high"
            description: "Redis memory usage is above 85% ({{ $value | humanizePercentage }})"

        # Disk Space Low
        - alert: DiskSpaceLow
          expr: |
            (node_filesystem_avail_bytes{mountpoint="/"} / node_filesystem_size_bytes{mountpoint="/"}) < 0.15
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "Low disk space"
            description: "Disk space is below 15% on {{ $labels.instance }}"
```

**End-of-Day 704 Deliverables (Dev 2):**

- [ ] Prometheus deployed with custom scrape configs
- [ ] Grafana deployed with production dashboards
- [ ] AlertManager configured with PagerDuty/Slack
- [ ] Alert rules created for critical metrics
- [ ] ELK stack deployed for log aggregation

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Create monitoring documentation — 3h
2. Document alert runbooks — 3h
3. Create Kibana dashboards for log analysis — 2h

**End-of-Day 704 Deliverables (Dev 3):**

- [ ] Monitoring documentation complete
- [ ] Alert runbooks created
- [ ] Kibana dashboards configured

---

### Day 705 — Environment Validation & Documentation

**Objective:** Validate all infrastructure components, execute smoke tests, and complete documentation.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Run infrastructure validation scripts — 3h
2. Test RDS failover and recovery — 2h
3. Verify backup and restore procedures — 2h
4. Document validation results — 1h

**Code: Infrastructure Validation Script**

```bash
#!/bin/bash
# scripts/validate-production-environment.sh

set -euo pipefail

echo "=================================================================="
echo "     SMART DAIRY PRODUCTION ENVIRONMENT VALIDATION"
echo "=================================================================="
echo "Date: $(date)"
echo "=================================================================="

PASS=0
FAIL=0

check_result() {
    if [ $1 -eq 0 ]; then
        echo "✅ PASS: $2"
        ((PASS++))
    else
        echo "❌ FAIL: $2"
        ((FAIL++))
    fi
}

echo ""
echo "[1/12] Verifying EKS Cluster..."
NODE_COUNT=$(kubectl get nodes --no-headers 2>/dev/null | grep -c Ready || echo 0)
if [ "$NODE_COUNT" -ge 3 ]; then
    check_result 0 "EKS cluster has $NODE_COUNT healthy nodes"
else
    check_result 1 "EKS cluster has only $NODE_COUNT nodes (expected >= 3)"
fi

echo ""
echo "[2/12] Verifying Kubernetes Namespaces..."
if kubectl get namespace smart-dairy-prod &>/dev/null; then
    check_result 0 "Namespace 'smart-dairy-prod' exists"
else
    check_result 1 "Namespace 'smart-dairy-prod' not found"
fi

echo ""
echo "[3/12] Verifying RDS PostgreSQL..."
RDS_STATUS=$(aws rds describe-db-instances \
    --db-instance-identifier smart-dairy-prod-db-primary \
    --query 'DBInstances[0].DBInstanceStatus' \
    --output text 2>/dev/null || echo "NOT_FOUND")
if [ "$RDS_STATUS" == "available" ]; then
    check_result 0 "RDS primary is available"
else
    check_result 1 "RDS primary status: $RDS_STATUS"
fi

echo ""
echo "[4/12] Verifying RDS Multi-AZ..."
MULTI_AZ=$(aws rds describe-db-instances \
    --db-instance-identifier smart-dairy-prod-db-primary \
    --query 'DBInstances[0].MultiAZ' \
    --output text 2>/dev/null || echo "false")
if [ "$MULTI_AZ" == "True" ]; then
    check_result 0 "RDS Multi-AZ is enabled"
else
    check_result 1 "RDS Multi-AZ is NOT enabled"
fi

echo ""
echo "[5/12] Verifying ElastiCache Redis..."
REDIS_STATUS=$(aws elasticache describe-replication-groups \
    --replication-group-id smart-dairy-prod-redis \
    --query 'ReplicationGroups[0].Status' \
    --output text 2>/dev/null || echo "NOT_FOUND")
if [ "$REDIS_STATUS" == "available" ]; then
    check_result 0 "ElastiCache Redis is available"
else
    check_result 1 "ElastiCache Redis status: $REDIS_STATUS"
fi

echo ""
echo "[6/12] Verifying CloudFront Distribution..."
CF_STATUS=$(aws cloudfront get-distribution \
    --id ${CLOUDFRONT_DISTRIBUTION_ID} \
    --query 'Distribution.Status' \
    --output text 2>/dev/null || echo "NOT_FOUND")
if [ "$CF_STATUS" == "Deployed" ]; then
    check_result 0 "CloudFront distribution is deployed"
else
    check_result 1 "CloudFront status: $CF_STATUS"
fi

echo ""
echo "[7/12] Verifying Prometheus..."
PROM_PODS=$(kubectl get pods -n monitoring -l app.kubernetes.io/name=prometheus --no-headers 2>/dev/null | grep -c Running || echo 0)
if [ "$PROM_PODS" -ge 1 ]; then
    check_result 0 "Prometheus has $PROM_PODS running pods"
else
    check_result 1 "Prometheus pods not running"
fi

echo ""
echo "[8/12] Verifying Grafana..."
GRAFANA_PODS=$(kubectl get pods -n monitoring -l app.kubernetes.io/name=grafana --no-headers 2>/dev/null | grep -c Running || echo 0)
if [ "$GRAFANA_PODS" -ge 1 ]; then
    check_result 0 "Grafana has $GRAFANA_PODS running pods"
else
    check_result 1 "Grafana pods not running"
fi

echo ""
echo "[9/12] Verifying SSL Certificates..."
CERT_STATUS=$(aws acm describe-certificate \
    --certificate-arn ${ACM_CERTIFICATE_ARN} \
    --query 'Certificate.Status' \
    --output text 2>/dev/null || echo "NOT_FOUND")
if [ "$CERT_STATUS" == "ISSUED" ]; then
    check_result 0 "ACM certificate is issued"
else
    check_result 1 "ACM certificate status: $CERT_STATUS"
fi

echo ""
echo "[10/12] Verifying S3 Buckets..."
ASSETS_BUCKET=$(aws s3api head-bucket --bucket smart-dairy-prod-assets 2>&1 || echo "NOT_FOUND")
if [ -z "$ASSETS_BUCKET" ]; then
    check_result 0 "S3 assets bucket exists"
else
    check_result 1 "S3 assets bucket not found"
fi

echo ""
echo "[11/12] Verifying VPC Flow Logs..."
FLOW_LOG=$(aws ec2 describe-flow-logs \
    --filter "Name=resource-id,Values=${VPC_ID}" \
    --query 'FlowLogs[0].FlowLogStatus' \
    --output text 2>/dev/null || echo "NOT_FOUND")
if [ "$FLOW_LOG" == "ACTIVE" ]; then
    check_result 0 "VPC flow logs are active"
else
    check_result 1 "VPC flow logs status: $FLOW_LOG"
fi

echo ""
echo "[12/12] Verifying CloudWatch Log Groups..."
LOG_GROUPS=$(aws logs describe-log-groups \
    --log-group-name-prefix /aws/eks/smart-dairy \
    --query 'logGroups | length(@)' \
    --output text 2>/dev/null || echo 0)
if [ "$LOG_GROUPS" -ge 1 ]; then
    check_result 0 "CloudWatch log groups exist ($LOG_GROUPS)"
else
    check_result 1 "CloudWatch log groups not found"
fi

echo ""
echo "=================================================================="
echo "                    VALIDATION SUMMARY"
echo "=================================================================="
echo "Total Checks: $((PASS + FAIL))"
echo "Passed: $PASS"
echo "Failed: $FAIL"
echo "=================================================================="

if [ $FAIL -eq 0 ]; then
    echo "✅ ALL PRODUCTION ENVIRONMENT CHECKS PASSED"
    exit 0
else
    echo "❌ SOME CHECKS FAILED - REVIEW REQUIRED"
    exit 1
fi
```

**End-of-Day 705 Deliverables (Dev 1):**

- [ ] Infrastructure validation script executed
- [ ] RDS failover tested (<60 seconds)
- [ ] Backup/restore procedures verified
- [ ] Validation results documented

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Configure CI/CD production pipeline — 3h
2. Test deployment pipeline with sample application — 2h
3. Verify monitoring and alerting — 2h
4. Complete CI/CD documentation — 1h

**End-of-Day 705 Deliverables (Dev 2):**

- [ ] CI/CD production pipeline configured
- [ ] Sample deployment successful
- [ ] Monitoring verified end-to-end
- [ ] Alerting tested (test alert sent)
- [ ] CI/CD documentation complete

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Compile all documentation into final format — 3h
2. Create environment validation report — 2h
3. Review and finalize runbooks — 2h
4. Prepare Milestone 141 sign-off package — 1h

**End-of-Day 705 Deliverables (Dev 3):**

- [ ] All documentation compiled
- [ ] Environment validation report created
- [ ] Runbooks finalized
- [ ] Milestone 141 sign-off package ready

---

## 4. Technical Specifications

### 4.1 AWS Resource Specifications

| Resource | Specification | Quantity |
|----------|---------------|----------|
| VPC | 10.0.0.0/16, 3 AZs | 1 |
| Subnets | Public (3), Private (3), Database (3) | 9 |
| NAT Gateways | 1 per AZ | 3 |
| EKS Cluster | v1.29, control plane logging | 1 |
| EKS Node Groups | t3.xlarge (app), r6i.xlarge (db) | 2 |
| RDS PostgreSQL | db.r6i.2xlarge, Multi-AZ, 500GB GP3 | 1 primary + 1 replica |
| ElastiCache Redis | cache.r6g.large, 3 nodes | 1 cluster |
| S3 Buckets | Assets, Backups | 2 |
| CloudFront | HTTP/2+3, global edge | 1 distribution |
| ACM Certificates | Wildcard *.smartdairy.com.bd | 2 |
| ALB | Internet-facing | 1 |

### 4.2 Kubernetes Resource Specifications

| Resource | Specification |
|----------|---------------|
| Namespace | smart-dairy-prod |
| Backend Pods | 5 replicas (min 3, max 20) |
| Frontend Pods | 3 replicas (min 2, max 10) |
| CPU Limits | 2 cores per pod |
| Memory Limits | 4Gi per pod |
| Storage Class | gp3 (encrypted) |

---

## 5. Testing & Validation

### 5.1 Infrastructure Tests

| Test | Expected Result | Actual | Pass/Fail |
|------|-----------------|--------|-----------|
| VPC connectivity | All subnets routable | TBD | |
| EKS cluster health | 3+ nodes Ready | TBD | |
| RDS connectivity | Port 5432 accessible | TBD | |
| RDS failover | <60 second recovery | TBD | |
| Redis connectivity | Port 6379 accessible | TBD | |
| Redis latency | <1ms p99 | TBD | |
| CloudFront cache | >80% hit ratio | TBD | |
| SSL certificate | Valid, Grade A+ | TBD | |
| Prometheus targets | 100% UP | TBD | |
| Grafana dashboards | All widgets loading | TBD | |

### 5.2 Validation Checklist

- [ ] All Terraform resources applied successfully
- [ ] EKS cluster accessible via kubectl
- [ ] RDS database accepting connections
- [ ] Redis cluster responding to PING
- [ ] CloudFront serving assets
- [ ] SSL certificates valid
- [ ] Monitoring stack operational
- [ ] CI/CD pipeline functional
- [ ] All documentation complete

---

## 6. Risk & Mitigation

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R-141-01 | AWS service limits exceeded | Medium | High | Pre-request limit increases 14 days before |
| R-141-02 | Terraform state corruption | Low | Critical | S3 versioning, DynamoDB locking, regular backups |
| R-141-03 | EKS cluster provisioning delays | Medium | High | Start early, have fallback region |
| R-141-04 | ACM certificate validation delays | Low | Medium | Request certificates 48 hours early |
| R-141-05 | RDS provisioning time exceeds estimate | Medium | Medium | Allow 2-hour buffer for RDS creation |
| R-141-06 | Network configuration errors | Medium | High | Thorough review, staged rollout |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By | Status |
|------------|--------|-------------|--------|
| Phase 14 sign-off | QA Lead | Day 701 | Pending |
| AWS account access | IT Admin | Day 701 | Pending |
| Domain DNS access | Registrar | Day 701 | Pending |
| GitHub secrets | DevOps | Day 701 | Pending |
| Budget approval | Finance | Day 701 | Pending |

### 7.2 Output Handoffs

| Deliverable | Recipient | Handoff Date |
|-------------|-----------|--------------|
| Production infrastructure | Milestone 142 (Data Migration) | Day 705 |
| Terraform state | Operations team | Day 705 |
| Access credentials | Milestone 143 (Security) | Day 705 |
| Monitoring access | All developers | Day 705 |
| Documentation | Knowledge base | Day 705 |

---

## Milestone 141 Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Backend Lead (Dev 1) | | | |
| Full-Stack (Dev 2) | | | |
| Frontend Lead (Dev 3) | | | |
| Technical Architect | | | |
| Project Manager | | | |

---

**Document End**

*Milestone 141: Production Environment Setup*
*Smart Dairy Digital Smart Portal + ERP — Phase 15*
*Version 1.0 | Days 701-705*
