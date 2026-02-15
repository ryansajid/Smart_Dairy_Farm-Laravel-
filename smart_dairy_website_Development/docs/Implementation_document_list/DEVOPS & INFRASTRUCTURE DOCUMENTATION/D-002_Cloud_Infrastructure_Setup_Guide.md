# SMART DAIRY LTD.
## CLOUD INFRASTRUCTURE SETUP GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Cloud Infrastructure Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Prerequisites](#2-prerequisites)
3. [AWS Account Setup](#3-aws-account-setup)
4. [VPC & Network Configuration](#4-vpc--network-configuration)
5. [Compute Resources Setup](#5-compute-resources-setup)
6. [Database Setup](#6-database-setup)
7. [Storage Configuration](#7-storage-configuration)
8. [Security Configuration](#8-security-configuration)
9. [DNS & Route 53 Setup](#9-dns--route-53-setup)
10. [Environment Validation](#10-environment-validation)
11. [Troubleshooting](#11-troubleshooting)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide provides step-by-step instructions for provisioning and configuring the AWS cloud infrastructure for the Smart Dairy Smart Web Portal System and Integrated ERP. It covers the complete setup from initial account configuration to production-ready infrastructure deployment.

### 1.2 Target Infrastructure

```
┌─────────────────────────────────────────────────────────────────┐
│                    AWS INFRASTRUCTURE OVERVIEW                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  REGION: Asia Pacific (Mumbai) - ap-south-1                     │
│  PRIMARY AZs: ap-south-1a, ap-south-1b, ap-south-1c             │
│  DR REGION: Asia Pacific (Singapore) - ap-southeast-1           │
│                                                                  │
│  CORE COMPONENTS:                                                │
│  ├── VPC with public, private, and database subnets             │
│  ├── EKS (Elastic Kubernetes Service) for container workloads   │
│  ├── RDS PostgreSQL with Multi-AZ for database                  │
│  ├── ElastiCache Redis for caching                              │
│  ├── S3 for object storage and backups                          │
│  ├── ALB (Application Load Balancer) for traffic distribution   │
│  ├── CloudFront for CDN and edge caching                        │
│  └── Route 53 for DNS management                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Setup Phases

| Phase | Duration | Components |
|-------|----------|------------|
| **Phase 1** | 2 hours | Account, VPC, networking basics |
| **Phase 2** | 4 hours | Compute, databases, core services |
| **Phase 3** | 3 hours | Security, monitoring, backups |
| **Phase 4** | 2 hours | Validation, testing, documentation |

---

## 2. PREREQUISITES

### 2.1 Required Tools

| Tool | Version | Purpose |
|------|---------|---------|
| AWS CLI | 2.13+ | AWS resource management |
| Terraform | 1.6+ | Infrastructure as Code |
| kubectl | 1.28+ | Kubernetes management |
| eksctl | 0.160+ | EKS cluster management |
| jq | 1.6+ | JSON processing |
| Git | 2.40+ | Version control |

### 2.2 Access Requirements

- AWS Account with root/administrator access
- IAM user with programmatic access
- Access Key ID and Secret Access Key
- Domain name registered and accessible
- SSL/TLS certificates (or plan to use ACM)

---

## 3. AWS ACCOUNT SETUP

### 3.1 Initial Account Configuration

```bash
# Configure AWS CLI
aws configure
AWS Access Key ID [None]: YOUR_ACCESS_KEY
AWS Secret Access Key [None]: YOUR_SECRET_KEY
Default region name [None]: ap-south-1
Default output format [None]: json

# Verify configuration
aws sts get-caller-identity

# Expected output:
# {
#     "UserId": "AIDAXXXXXXXXXXXXXXXX",
#     "Account": "123456789012",
#     "Arn": "arn:aws:iam::123456789012:user/admin"
# }
```

### 3.2 Enable Required Services

```bash
# Create S3 bucket for Terraform state
aws s3api create-bucket \
    --bucket smart-dairy-terraform-state \
    --region ap-south-1 \
    --create-bucket-configuration LocationConstraint=ap-south-1

# Enable versioning for state bucket
aws s3api put-bucket-versioning \
    --bucket smart-dairy-terraform-state \
    --versioning-configuration Status=Enabled

# Enable encryption
aws s3api put-bucket-encryption \
    --bucket smart-dairy-terraform-state \
    --server-side-encryption-configuration '{
        "Rules": [{
            "ApplyServerSideEncryptionByDefault": {
                "SSEAlgorithm": "AES256"
            }
        }]
    }'

# Create DynamoDB table for state locking
aws dynamodb create-table \
    --table-name terraform-state-lock \
    --attribute-definitions AttributeName=LockID,AttributeType=S \
    --key-schema AttributeName=LockID,KeyType=HASH \
    --billing-mode PAY_PER_REQUEST \
    --region ap-south-1
```

---

## 4. VPC & NETWORK CONFIGURATION

### 4.1 VPC Creation

```bash
# Create VPC
VPC_ID=$(aws ec2 create-vpc \
    --cidr-block 10.0.0.0/16 \
    --tag-specifications 'ResourceType=vpc,Tags=[{Key=Name,Value=smart-dairy-vpc},{Key=Environment,Value=production}]' \
    --query 'Vpc.VpcId' \
    --output text)

# Enable DNS hostnames
aws ec2 modify-vpc-attribute \
    --vpc-id $VPC_ID \
    --enable-dns-hostnames

# Enable DNS resolution
aws ec2 modify-vpc-attribute \
    --vpc-id $VPC_ID \
    --enable-dns-support

echo "Created VPC: $VPC_ID"
```

### 4.2 Subnet Configuration

```bash
# Create Internet Gateway
IGW_ID=$(aws ec2 create-internet-gateway \
    --tag-specifications 'ResourceType=internet-gateway,Tags=[{Key=Name,Value=smart-dairy-igw}]' \
    --query 'InternetGateway.InternetGatewayId' \
    --output text)

# Attach IGW to VPC
aws ec2 attach-internet-gateway \
    --internet-gateway-id $IGW_ID \
    --vpc-id $VPC_ID

# Create public subnets (one per AZ)
PUBLIC_SUBNET_1A=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.1.0/24 \
    --availability-zone ap-south-1a \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-public-1a}]' \
    --query 'Subnet.SubnetId' \
    --output text)

PUBLIC_SUBNET_1B=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.2.0/24 \
    --availability-zone ap-south-1b \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-public-1b}]' \
    --query 'Subnet.SubnetId' \
    --output text)

PUBLIC_SUBNET_1C=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.3.0/24 \
    --availability-zone ap-south-1c \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-public-1c}]' \
    --query 'Subnet.SubnetId' \
    --output text)

# Create private subnets
PRIVATE_SUBNET_1A=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.4.0/24 \
    --availability-zone ap-south-1a \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-private-1a}]' \
    --query 'Subnet.SubnetId' \
    --output text)

PRIVATE_SUBNET_1B=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.5.0/24 \
    --availability-zone ap-south-1b \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-private-1b}]' \
    --query 'Subnet.SubnetId' \
    --output text)

PRIVATE_SUBNET_1C=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.6.0/24 \
    --availability-zone ap-south-1c \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-private-1c}]' \
    --query 'Subnet.SubnetId' \
    --output text)

# Create database subnets
DB_SUBNET_1A=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.7.0/24 \
    --availability-zone ap-south-1a \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-db-1a}]' \
    --query 'Subnet.SubnetId' \
    --output text)

DB_SUBNET_1B=$(aws ec2 create-subnet \
    --vpc-id $VPC_ID \
    --cidr-block 10.0.8.0/24 \
    --availability-zone ap-south-1b \
    --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=smart-dairy-db-1b}]' \
    --query 'Subnet.SubnetId' \
    --output text)
```

### 4.3 NAT Gateway and Routing

```bash
# Allocate Elastic IPs for NAT Gateways
EIP_1A=$(aws ec2 allocate-address --query 'AllocationId' --output text)
EIP_1B=$(aws ec2 allocate-address --query 'AllocationId' --output text)

# Create NAT Gateways
NAT_GW_1A=$(aws ec2 create-nat-gateway \
    --subnet-id $PUBLIC_SUBNET_1A \
    --allocation-id $EIP_1A \
    --tag-specifications 'ResourceType=natgateway,Tags=[{Key=Name,Value=smart-dairy-nat-1a}]' \
    --query 'NatGateway.NatGatewayId' \
    --output text)

NAT_GW_1B=$(aws ec2 create-nat-gateway \
    --subnet-id $PUBLIC_SUBNET_1B \
    --allocation-id $EIP_1B \
    --tag-specifications 'ResourceType=natgateway,Tags=[{Key=Name,Value=smart-dairy-nat-1b}]' \
    --query 'NatGateway.NatGatewayId' \
    --output text)

# Create route tables
PUBLIC_RT=$(aws ec2 create-route-table \
    --vpc-id $VPC_ID \
    --tag-specifications 'ResourceType=route-table,Tags=[{Key=Name,Value=smart-dairy-public-rt}]' \
    --query 'RouteTable.RouteTableId' \
    --output text)

PRIVATE_RT_1A=$(aws ec2 create-route-table \
    --vpc-id $VPC_ID \
    --tag-specifications 'ResourceType=route-table,Tags=[{Key=Name,Value=smart-dairy-private-rt-1a}]' \
    --query 'RouteTable.RouteTableId' \
    --output text)

PRIVATE_RT_1B=$(aws ec2 create-route-table \
    --vpc-id $VPC_ID \
    --tag-specifications 'ResourceType=route-table,Tags=[{Key=Name,Value=smart-dairy-private-rt-1b}]' \
    --query 'RouteTable.RouteTableId' \
    --output text)

# Add route to Internet Gateway for public route table
aws ec2 create-route \
    --route-table-id $PUBLIC_RT \
    --destination-cidr-block 0.0.0.0/0 \
    --gateway-id $IGW_ID

# Associate public subnets with public route table
aws ec2 associate-route-table \
    --route-table-id $PUBLIC_RT \
    --subnet-id $PUBLIC_SUBNET_1A

aws ec2 associate-route-table \
    --route-table-id $PUBLIC_RT \
    --subnet-id $PUBLIC_SUBNET_1B

aws ec2 associate-route-table \
    --route-table-id $PUBLIC_RT \
    --subnet-id $PUBLIC_SUBNET_1C

echo "Network setup complete. Waiting for NAT Gateways..."
```

---

## 5. COMPUTE RESOURCES SETUP

### 5.1 EKS Cluster Creation

```bash
# Create EKS cluster
eksctl create cluster \
    --name smart-dairy-prod \
    --region ap-south-1 \
    --vpc-private-subnets $PRIVATE_SUBNET_1A,$PRIVATE_SUBNET_1B,$PRIVATE_SUBNET_1C \
    --vpc-public-subnets $PUBLIC_SUBNET_1A,$PUBLIC_SUBNET_1B,$PUBLIC_SUBNET_1C \
    --node-type m6i.xlarge \
    --nodes-min 3 \
    --nodes-max 10 \
    --nodes-desired 3 \
    --node-volume-size 100 \
    --managed \
    --asg-access \
    --external-dns-access \
    --full-ecr-access \
    --appmesh-access \
    --alb-ingress-access \
    --tags "Environment=production,Project=smart-dairy"

# Verify cluster
kubectl get nodes
kubectl get pods -n kube-system
```

### 5.2 Node Groups Configuration

```bash
# Create additional node group for applications
eksctl create nodegroup \
    --cluster smart-dairy-prod \
    --name app-workloads \
    --node-type m6i.2xlarge \
    --nodes-min 2 \
    --nodes-max 6 \
    --nodes-desired 2 \
    --node-volume-size 200 \
    --node-private-networking \
    --node-labels "workload-type=apps" \
    --tags "Environment=production,Workload=applications"

# Create node group for databases (dedicated instances)
eksctl create nodegroup \
    --cluster smart-dairy-prod \
    --name database-proxy \
    --node-type m6i.xlarge \
    --nodes-min 2 \
    --nodes-max 4 \
    --nodes-desired 2 \
    --node-private-networking \
    --node-labels "workload-type=database" \
    --tags "Environment=production,Workload=database"
```

---

## 6. DATABASE SETUP

### 6.1 RDS PostgreSQL Setup

```bash
# Create DB subnet group
aws rds create-db-subnet-group \
    --db-subnet-group-name smart-dairy-db-subnet \
    --db-subnet-group-description "Subnet group for Smart Dairy databases" \
    --subnet-ids '["'$DB_SUBNET_1A'","'$DB_SUBNET_1B'"]'

# Create RDS PostgreSQL instance
aws rds create-db-instance \
    --db-instance-identifier smart-dairy-postgres \
    --db-instance-class db.r6g.xlarge \
    --engine postgres \
    --engine-version 16.1 \
    --allocated-storage 500 \
    --storage-type gp3 \
    --storage-encrypted \
    --master-username dbadmin \
    --master-user-password 'YourStrongPassword123!' \
    --vpc-security-group-ids $DB_SECURITY_GROUP \
    --db-subnet-group-name smart-dairy-db-subnet \
    --multi-az \
    --publicly-accessible false \
    --backup-retention-period 30 \
    --preferred-backup-window 03:00-04:00 \
    --preferred-maintenance-window Mon:04:00-Mon:05:00 \
    --enable-performance-insights \
    --performance-insights-retention-period 7 \
    --enable-cloudwatch-logs-exports '["postgresql","upgrade"]' \
    --deletion-protection \
    --tags '[{"Key":"Environment","Value":"production"},{"Key":"Project","Value":"smart-dairy"}]'

# Create TimescaleDB instance for IoT data
aws rds create-db-instance \
    --db-instance-identifier smart-dairy-analytics \
    --db-instance-class db.r6g.2xlarge \
    --engine postgres \
    --engine-version 16.1 \
    --allocated-storage 1000 \
    --storage-type gp3 \
    --storage-encrypted \
    --master-username tsdbadmin \
    --master-user-password 'YourStrongPassword123!' \
    --vpc-security-group-ids $DB_SECURITY_GROUP \
    --db-subnet-group-name smart-dairy-db-subnet \
    --multi-az \
    --publicly-accessible false \
    --backup-retention-period 30 \
    --tags '[{"Key":"Environment","Value":"production"},{"Key":"Project","Value":"smart-dairy"},{"Key":"Purpose","Value":"analytics"}]'
```

### 6.2 ElastiCache Redis Setup

```bash
# Create ElastiCache subnet group
aws elasticache create-cache-subnet-group \
    --cache-subnet-group-name smart-dairy-redis-subnet \
    --cache-subnet-group-description "Subnet group for Redis" \
    --subnet-ids '["'$PRIVATE_SUBNET_1A'","'$PRIVATE_SUBNET_1B'"]'

# Create Redis cluster
aws elasticache create-cache-cluster \
    --cache-cluster-id smart-dairy-redis \
    --engine redis \
    --engine-version 7.0 \
    --cache-node-type cache.r6g.large \
    --num-cache-nodes 2 \
    --cache-subnet-group-name smart-dairy-redis-subnet \
    --security-group-ids $REDIS_SECURITY_GROUP \
    --auto-minor-version-upgrade \
    --snapshot-retention-limit 7 \
    --preferred-maintenance-window sun:05:00-sun:06:00 \
    --tags '[{"Key":"Environment","Value":"production"},{"Key":"Project","Value":"smart-dairy"}]'
```

---

## 7. STORAGE CONFIGURATION

### 7.1 S3 Buckets Setup

```bash
# Application file storage
aws s3api create-bucket \
    --bucket smart-dairy-filestore \
    --region ap-south-1 \
    --create-bucket-configuration LocationConstraint=ap-south-1

# Backup storage
aws s3api create-bucket \
    --bucket smart-dairy-backups \
    --region ap-south-1 \
    --create-bucket-configuration LocationConstraint=ap-south-1

# Static assets
aws s3api create-bucket \
    --bucket smart-dairy-static-assets \
    --region ap-south-1 \
    --create-bucket-configuration LocationConstraint=ap-south-1

# Enable versioning and encryption for all buckets
for BUCKET in smart-dairy-filestore smart-dairy-backups smart-dairy-static-assets; do
    aws s3api put-bucket-versioning \
        --bucket $BUCKET \
        --versioning-configuration Status=Enabled
    
    aws s3api put-bucket-encryption \
        --bucket $BUCKET \
        --server-side-encryption-configuration '{
            "Rules": [{
                "ApplyServerSideEncryptionByDefault": {
                    "SSEAlgorithm": "AES256"
                },
                "BucketKeyEnabled": true
            }]
        }'
    
    # Block public access
    aws s3api put-public-access-block \
        --bucket $BUCKET \
        --public-access-block-configuration '{
            "BlockPublicAcls": true,
            "IgnorePublicAcls": true,
            "BlockPublicPolicy": true,
            "RestrictPublicBuckets": true
        }'
done

# Enable cross-region replication for backups
aws s3api put-bucket-replication \
    --bucket smart-dairy-backups \
    --replication-configuration '{
        "Role": "arn:aws:iam::123456789012:role/S3ReplicationRole",
        "Rules": [{
            "ID": "BackupReplication",
            "Status": "Enabled",
            "Destination": {
                "Bucket": "arn:aws:s3:::smart-dairy-backups-dr",
                "StorageClass": "STANDARD_IA"
            }
        }]
    }'
```

---

## 8. SECURITY CONFIGURATION

### 8.1 Security Groups

```bash
# Create ALB Security Group
ALB_SG=$(aws ec2 create-security-group \
    --group-name smart-dairy-alb-sg \
    --description "Security group for Application Load Balancer" \
    --vpc-id $VPC_ID \
    --query 'GroupId' \
    --output text)

# Allow HTTPS inbound
aws ec2 authorize-security-group-ingress \
    --group-id $ALB_SG \
    --protocol tcp \
    --port 443 \
    --cidr 0.0.0.0/0

# Allow HTTP inbound (redirects to HTTPS)
aws ec2 authorize-security-group-ingress \
    --group-id $ALB_SG \
    --protocol tcp \
    --port 80 \
    --cidr 0.0.0.0/0

# Create EKS Worker Security Group
EKS_SG=$(aws ec2 create-security-group \
    --group-name smart-dairy-eks-worker-sg \
    --description "Security group for EKS worker nodes" \
    --vpc-id $VPC_ID \
    --query 'GroupId' \
    --output text)

# Allow traffic from ALB
aws ec2 authorize-security-group-ingress \
    --group-id $EKS_SG \
    --protocol tcp \
    --port 8080 \
    --source-group $ALB_SG

# Create Database Security Group
DB_SG=$(aws ec2 create-security-group \
    --group-name smart-dairy-db-sg \
    --description "Security group for RDS databases" \
    --vpc-id $VPC_ID \
    --query 'GroupId' \
    --output text)

# Allow PostgreSQL from EKS workers only
aws ec2 authorize-security-group-ingress \
    --group-id $DB_SG \
    --protocol tcp \
    --port 5432 \
    --source-group $EKS_SG

echo "Security Groups Created:"
echo "ALB: $ALB_SG"
echo "EKS: $EKS_SG"
echo "DB: $DB_SG"
```

---

## 9. DNS & ROUTE 53 SETUP

### 9.1 Hosted Zone Configuration

```bash
# Create hosted zone
aws route53 create-hosted-zone \
    --name smartdairy.bd \
    --caller-reference $(date +%s) \
    --hosted-zone-config Comment="Smart Dairy Production",PrivateZone=false

# Get hosted zone ID
HOSTED_ZONE_ID=$(aws route53 list-hosted-zones-by-name \
    --dns-name smartdairy.bd \
    --query 'HostedZones[0].Id' \
    --output text)

# Create A record for main domain
aws route53 change-resource-record-sets \
    --hosted-zone-id $HOSTED_ZONE_ID \
    --change-batch '{
        "Changes": [{
            "Action": "CREATE",
            "ResourceRecordSet": {
                "Name": "smartdairy.bd",
                "Type": "A",
                "AliasTarget": {
                    "HostedZoneId": "ZP97RAFLXT7K",
                    "DNSName": "'$ALB_DNS_NAME'",
                    "EvaluateTargetHealth": true
                }
            }
        }]
    }'

# Create subdomains
for SUBDOMAIN in www app api admin; do
    aws route53 change-resource-record-sets \
        --hosted-zone-id $HOSTED_ZONE_ID \
        --change-batch '{
            "Changes": [{
                "Action": "CREATE",
                "ResourceRecordSet": {
                    "Name": "'$SUBDOMAIN'.smartdairy.bd",
                    "Type": "CNAME",
                    "TTL": 300,
                    "ResourceRecords": [{"Value": "smartdairy.bd"}]
                }
            }]
        }'
done
```

---

## 10. ENVIRONMENT VALIDATION

### 10.1 Verification Checklist

```bash
#!/bin/bash
# validation.sh - Infrastructure validation script

echo "=== SMART DAIRY INFRASTRUCTURE VALIDATION ==="

# Check VPC
echo "Checking VPC..."
aws ec2 describe-vpcs --vpc-ids $VPC_ID --query 'Vpcs[0].State'

# Check Subnets
echo "Checking Subnets..."
aws ec2 describe-subnets --subnet-ids $PUBLIC_SUBNET_1A --query 'Subnets[0].State'
aws ec2 describe-subnets --subnet-ids $PRIVATE_SUBNET_1A --query 'Subnets[0].State'

# Check EKS
echo "Checking EKS Cluster..."
aws eks describe-cluster --name smart-dairy-prod --query 'cluster.status'

# Check RDS
echo "Checking RDS..."
aws rds describe-db-instances \
    --db-instance-identifier smart-dairy-postgres \
    --query 'DBInstances[0].DBInstanceStatus'

# Check S3
echo "Checking S3 Buckets..."
aws s3api head-bucket --bucket smart-dairy-filestore

# Check Security Groups
echo "Checking Security Groups..."
aws ec2 describe-security-groups --group-ids $ALB_SG --query 'SecurityGroups[0].GroupName'

echo "=== VALIDATION COMPLETE ==="
```

### 10.2 Smoke Tests

```bash
# Test EKS connectivity
kubectl run test-pod --image=nginx --restart=Never --rm -i -- echo "EKS is working"

# Test database connectivity
kubectl run postgres-test \
    --image=postgres:16 \
    --rm -i \
    -- psql postgres://dbadmin:password@smart-dairy-postgres.xxx.ap-south-1.rds.amazonaws.com:5432/postgres -c "SELECT 1;"

# Test Redis connectivity
kubectl run redis-test \
    --image=redis:7 \
    --rm -i \
    -- redis-cli -h smart-dairy-redis.xxx.cache.amazonaws.com ping
```

---

## 11. TROUBLESHOOTING

### 11.1 Common Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| NAT Gateway not active | Still provisioning | Wait 5-10 minutes |
| EKS nodes not joining | Wrong IAM roles | Verify node IAM instance profile |
| RDS connection refused | Security group | Verify DB security group allows EKS |
| S3 access denied | IAM permissions | Check bucket policy and IAM roles |
| DNS not resolving | Propagation delay | Wait up to 48 hours for global propagation |

### 11.2 Cleanup Commands

```bash
# WARNING: Use only if you need to tear down infrastructure

# Delete EKS cluster
eksctl delete cluster --name smart-dairy-prod --region ap-south-1

# Delete RDS instances
aws rds delete-db-instance \
    --db-instance-identifier smart-dairy-postgres \
    --skip-final-snapshot

# Delete VPC (will fail if resources still exist)
aws ec2 delete-vpc --vpc-id $VPC_ID
```

---

## 12. APPENDICES

### Appendix A: Complete Resource List

| Resource Type | Name | ARN/ID |
|---------------|------|--------|
| VPC | smart-dairy-vpc | vpc-xxx |
| EKS Cluster | smart-dairy-prod | arn:aws:eks:... |
| RDS PostgreSQL | smart-dairy-postgres | db-xxx |
| ElastiCache | smart-dairy-redis | smart-dairy-redis |
| S3 Buckets | smart-dairy-* | arn:aws:s3:::... |
| ALB | smart-dairy-alb | arn:aws:elasticloadbalancing:... |

### Appendix B: Terraform Alternative

```hcl
# main.tf - Terraform configuration for Smart Dairy infrastructure
terraform {
  backend "s3" {
    bucket         = "smart-dairy-terraform-state"
    key            = "infrastructure/terraform.tfstate"
    region         = "ap-south-1"
    dynamodb_table = "terraform-state-lock"
  }
}

provider "aws" {
  region = var.aws_region
}

module "vpc" {
  source  = "terraform-aws-modules/vpc/aws"
  version = "5.0"

  name = "smart-dairy-vpc"
  cidr = "10.0.0.0/16"

  azs             = ["ap-south-1a", "ap-south-1b", "ap-south-1c"]
  private_subnets = ["10.0.1.0/24", "10.0.2.0/24", "10.0.3.0/24"]
  public_subnets  = ["10.0.101.0/24", "10.0.102.0/24", "10.0.103.0/24"]
  database_subnets = ["10.0.201.0/24", "10.0.202.0/24"]

  enable_nat_gateway = true
  single_nat_gateway = false
  enable_dns_hostnames = true
}
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Cloud Infrastructure Engineer | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
