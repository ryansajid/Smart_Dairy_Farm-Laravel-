# PHASE 15: DEPLOYMENT & HANDOVER (Days 701-750)

## Phase Overview
**Duration:** 50 days (Days 701-750)
**Team Size:** 3 developers
**Focus Areas:** Production deployment, security hardening, training, knowledge transfer
**Prerequisites:** Phases 1-14 completed, all testing passed

## Success Criteria
- Production environment fully operational
- Zero-downtime deployment achieved
- All security hardening measures implemented
- Data migration completed successfully
- 24/7 monitoring active
- Complete knowledge transfer
- User training completed
- Final stakeholder sign-off obtained

---

## MILESTONE 15.1: Production Environment Setup (Days 701-705)

### Objective
Set up production-ready infrastructure with high availability, scalability, and security.

### Day 701-702: Cloud Infrastructure Setup
**Assigned to:** Dev 1 (Lead), Dev 2, Dev 3

#### Tasks
**Dev 1: AWS Infrastructure as Code (Terraform)**

```hcl
# infrastructure/terraform/main.tf

terraform {
  required_version = ">= 1.0"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }

  backend "s3" {
    bucket         = "dairy-platform-terraform-state"
    key            = "production/terraform.tfstate"
    region         = "ap-south-1"
    encrypt        = true
    dynamodb_table = "terraform-lock"
  }
}

provider "aws" {
  region = var.aws_region
  default_tags {
    tags = {
      Environment = "production"
      Project     = "Smart-Dairy-Platform"
      ManagedBy   = "Terraform"
    }
  }
}

# VPC Configuration
module "vpc" {
  source = "terraform-aws-modules/vpc/aws"
  version = "5.1.0"

  name = "dairy-platform-vpc"
  cidr = "10.0.0.0/16"

  azs             = ["ap-south-1a", "ap-south-1b", "ap-south-1c"]
  private_subnets = ["10.0.1.0/24", "10.0.2.0/24", "10.0.3.0/24"]
  public_subnets  = ["10.0.101.0/24", "10.0.102.0/24", "10.0.103.0/24"]
  database_subnets = ["10.0.201.0/24", "10.0.202.0/24", "10.0.203.0/24"]

  enable_nat_gateway   = true
  single_nat_gateway   = false # High availability
  enable_dns_hostnames = true
  enable_dns_support   = true

  enable_flow_log                      = true
  create_flow_log_cloudwatch_iam_role  = true
  create_flow_log_cloudwatch_log_group = true

  tags = {
    Name = "dairy-platform-vpc"
  }
}

# EKS Cluster
module "eks" {
  source  = "terraform-aws-modules/eks/aws"
  version = "19.16.0"

  cluster_name    = "dairy-platform-cluster"
  cluster_version = "1.28"

  vpc_id     = module.vpc.vpc_id
  subnet_ids = module.vpc.private_subnets

  cluster_endpoint_public_access  = true
  cluster_endpoint_private_access = true

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

  eks_managed_node_groups = {
    # Application nodes
    application = {
      desired_size = 3
      min_size     = 3
      max_size     = 10

      instance_types = ["t3.large"]
      capacity_type  = "ON_DEMAND"

      labels = {
        role = "application"
      }

      taints = []

      update_config = {
        max_unavailable_percentage = 33
      }
    }

    # Database workload nodes
    database = {
      desired_size = 2
      min_size     = 2
      max_size     = 5

      instance_types = ["r6i.xlarge"]
      capacity_type  = "ON_DEMAND"

      labels = {
        role = "database"
      }

      taints = [{
        key    = "database"
        value  = "true"
        effect = "NoSchedule"
      }]
    }
  }

  # Cluster security group rules
  cluster_security_group_additional_rules = {
    ingress_nodes_ephemeral_ports_tcp = {
      description                = "Nodes on ephemeral ports"
      protocol                   = "tcp"
      from_port                  = 1025
      to_port                    = 65535
      type                       = "ingress"
      source_node_security_group = true
    }
  }

  # Node security group rules
  node_security_group_additional_rules = {
    ingress_self_all = {
      description = "Node to node all ports/protocols"
      protocol    = "-1"
      from_port   = 0
      to_port     = 0
      type        = "ingress"
      self        = true
    }
  }

  tags = {
    Environment = "production"
  }
}

# RDS PostgreSQL (Primary)
resource "aws_db_instance" "primary" {
  identifier = "dairy-platform-db-primary"

  engine               = "postgres"
  engine_version       = "15.4"
  instance_class       = "db.r6i.2xlarge"
  allocated_storage    = 500
  max_allocated_storage = 1000
  storage_type         = "gp3"
  storage_encrypted    = true

  db_name  = "dairy_platform"
  username = var.db_username
  password = var.db_password
  port     = 5432

  vpc_security_group_ids = [aws_security_group.database.id]
  db_subnet_group_name   = aws_db_subnet_group.main.name

  multi_az               = true
  backup_retention_period = 30
  backup_window          = "03:00-04:00"
  maintenance_window     = "sun:04:00-sun:05:00"

  enabled_cloudwatch_logs_exports = ["postgresql", "upgrade"]
  monitoring_interval = 60
  monitoring_role_arn = aws_iam_role.rds_monitoring.arn

  deletion_protection = true
  skip_final_snapshot = false
  final_snapshot_identifier = "dairy-platform-final-snapshot"

  performance_insights_enabled = true
  performance_insights_retention_period = 7

  tags = {
    Name = "dairy-platform-db-primary"
  }
}

# RDS Read Replica
resource "aws_db_instance" "replica" {
  identifier = "dairy-platform-db-replica"

  replicate_source_db = aws_db_instance.primary.identifier

  instance_class    = "db.r6i.xlarge"
  storage_encrypted = true

  publicly_accessible = false

  backup_retention_period = 7

  performance_insights_enabled = true
  performance_insights_retention_period = 7

  tags = {
    Name = "dairy-platform-db-replica"
  }
}

# ElastiCache Redis Cluster
resource "aws_elasticache_replication_group" "redis" {
  replication_group_id       = "dairy-platform-redis"
  replication_group_description = "Redis cluster for caching"

  engine               = "redis"
  engine_version       = "7.0"
  node_type            = "cache.r6g.large"
  number_cache_clusters = 3
  port                 = 6379

  subnet_group_name  = aws_elasticache_subnet_group.redis.name
  security_group_ids = [aws_security_group.redis.id]

  automatic_failover_enabled = true
  multi_az_enabled          = true

  at_rest_encryption_enabled = true
  transit_encryption_enabled = true
  auth_token_enabled        = true
  auth_token                = var.redis_auth_token

  snapshot_retention_limit = 7
  snapshot_window         = "03:00-05:00"
  maintenance_window      = "sun:05:00-sun:07:00"

  log_delivery_configuration {
    destination      = aws_cloudwatch_log_group.redis.name
    destination_type = "cloudwatch-logs"
    log_format       = "json"
    log_type         = "slow-log"
  }

  tags = {
    Name = "dairy-platform-redis"
  }
}

# S3 Buckets
resource "aws_s3_bucket" "assets" {
  bucket = "dairy-platform-assets-prod"

  tags = {
    Name = "dairy-platform-assets"
  }
}

resource "aws_s3_bucket_versioning" "assets" {
  bucket = aws_s3_bucket.assets.id

  versioning_configuration {
    status = "Enabled"
  }
}

resource "aws_s3_bucket_encryption" "assets" {
  bucket = aws_s3_bucket.assets.id

  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm = "AES256"
    }
  }
}

resource "aws_s3_bucket_lifecycle_configuration" "assets" {
  bucket = aws_s3_bucket.assets.id

  rule {
    id     = "archive-old-versions"
    status = "Enabled"

    noncurrent_version_transition {
      noncurrent_days = 30
      storage_class   = "GLACIER"
    }

    noncurrent_version_expiration {
      noncurrent_days = 90
    }
  }
}

# CloudFront Distribution
resource "aws_cloudfront_distribution" "assets" {
  enabled             = true
  is_ipv6_enabled     = true
  http_version        = "http2and3"
  price_class         = "PriceClass_200"
  default_root_object = "index.html"

  origin {
    domain_name = aws_s3_bucket.assets.bucket_regional_domain_name
    origin_id   = "S3-dairy-platform-assets"

    s3_origin_config {
      origin_access_identity = aws_cloudfront_origin_access_identity.assets.cloudfront_access_identity_path
    }
  }

  default_cache_behavior {
    allowed_methods  = ["GET", "HEAD", "OPTIONS"]
    cached_methods   = ["GET", "HEAD"]
    target_origin_id = "S3-dairy-platform-assets"

    forwarded_values {
      query_string = false
      cookies {
        forward = "none"
      }
    }

    viewer_protocol_policy = "redirect-to-https"
    min_ttl                = 0
    default_ttl            = 3600
    max_ttl                = 86400
    compress               = true
  }

  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  viewer_certificate {
    acm_certificate_arn      = aws_acm_certificate.main.arn
    ssl_support_method       = "sni-only"
    minimum_protocol_version = "TLSv1.2_2021"
  }

  tags = {
    Name = "dairy-platform-cdn"
  }
}

# Application Load Balancer
resource "aws_lb" "main" {
  name               = "dairy-platform-alb"
  internal           = false
  load_balancer_type = "application"
  security_groups    = [aws_security_group.alb.id]
  subnets            = module.vpc.public_subnets

  enable_deletion_protection = true
  enable_http2              = true
  enable_waf_fail_open      = false

  access_logs {
    bucket  = aws_s3_bucket.alb_logs.bucket
    enabled = true
  }

  tags = {
    Name = "dairy-platform-alb"
  }
}

# Security Groups
resource "aws_security_group" "alb" {
  name_prefix = "dairy-alb-"
  description = "Security group for ALB"
  vpc_id      = module.vpc.vpc_id

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "HTTPS from internet"
  }

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
    description = "HTTP from internet (redirect to HTTPS)"
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
    description = "Allow all outbound"
  }

  tags = {
    Name = "dairy-platform-alb-sg"
  }
}

resource "aws_security_group" "database" {
  name_prefix = "dairy-db-"
  description = "Security group for RDS database"
  vpc_id      = module.vpc.vpc_id

  ingress {
    from_port       = 5432
    to_port         = 5432
    protocol        = "tcp"
    security_groups = [module.eks.node_security_group_id]
    description     = "PostgreSQL from EKS nodes"
  }

  tags = {
    Name = "dairy-platform-db-sg"
  }
}

resource "aws_security_group" "redis" {
  name_prefix = "dairy-redis-"
  description = "Security group for Redis cluster"
  vpc_id      = module.vpc.vpc_id

  ingress {
    from_port       = 6379
    to_port         = 6379
    protocol        = "tcp"
    security_groups = [module.eks.node_security_group_id]
    description     = "Redis from EKS nodes"
  }

  tags = {
    Name = "dairy-platform-redis-sg"
  }
}

# Auto Scaling
resource "aws_autoscaling_policy" "application" {
  name                   = "dairy-platform-cpu-scaling"
  autoscaling_group_name = module.eks.eks_managed_node_groups["application"].node_group_autoscaling_group_names[0]
  policy_type            = "TargetTrackingScaling"

  target_tracking_configuration {
    predefined_metric_specification {
      predefined_metric_type = "ASGAverageCPUUtilization"
    }
    target_value = 70.0
  }
}

# CloudWatch Log Groups
resource "aws_cloudwatch_log_group" "application" {
  name              = "/aws/eks/dairy-platform/application"
  retention_in_days = 30

  tags = {
    Name = "dairy-platform-app-logs"
  }
}

resource "aws_cloudwatch_log_group" "database" {
  name              = "/aws/rds/dairy-platform/postgresql"
  retention_in_days = 30

  tags = {
    Name = "dairy-platform-db-logs"
  }
}

resource "aws_cloudwatch_log_group" "redis" {
  name              = "/aws/elasticache/dairy-platform/redis"
  retention_in_days = 30

  tags = {
    Name = "dairy-platform-redis-logs"
  }
}

# Outputs
output "eks_cluster_endpoint" {
  description = "EKS cluster endpoint"
  value       = module.eks.cluster_endpoint
}

output "rds_endpoint" {
  description = "RDS primary endpoint"
  value       = aws_db_instance.primary.endpoint
  sensitive   = true
}

output "redis_endpoint" {
  description = "Redis cluster endpoint"
  value       = aws_elasticache_replication_group.redis.primary_endpoint_address
  sensitive   = true
}

output "alb_dns_name" {
  description = "ALB DNS name"
  value       = aws_lb.main.dns_name
}

output "cloudfront_domain" {
  description = "CloudFront distribution domain"
  value       = aws_cloudfront_distribution.assets.domain_name
}
```

**Dev 2: Kubernetes Manifests**

```yaml
# kubernetes/production/namespace.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: dairy-platform-prod
  labels:
    name: dairy-platform-prod
    environment: production

---
# kubernetes/production/configmap.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: app-config
  namespace: dairy-platform-prod
data:
  NODE_ENV: "production"
  LOG_LEVEL: "info"
  API_PORT: "3000"
  REDIS_PORT: "6379"
  DB_PORT: "5432"
  DB_NAME: "dairy_platform"
  RATE_LIMIT_WINDOW: "900000"
  RATE_LIMIT_MAX: "100"
  SESSION_TIMEOUT: "3600000"

---
# kubernetes/production/secrets.yaml
apiVersion: v1
kind: Secret
metadata:
  name: app-secrets
  namespace: dairy-platform-prod
type: Opaque
stringData:
  DB_HOST: ${RDS_ENDPOINT}
  DB_USERNAME: ${DB_USERNAME}
  DB_PASSWORD: ${DB_PASSWORD}
  REDIS_HOST: ${REDIS_ENDPOINT}
  REDIS_PASSWORD: ${REDIS_PASSWORD}
  JWT_SECRET: ${JWT_SECRET}
  SESSION_SECRET: ${SESSION_SECRET}
  AWS_ACCESS_KEY_ID: ${AWS_ACCESS_KEY_ID}
  AWS_SECRET_ACCESS_KEY: ${AWS_SECRET_ACCESS_KEY}

---
# kubernetes/production/deployment-backend.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: backend-deployment
  namespace: dairy-platform-prod
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
        prometheus.io/port: "3000"
        prometheus.io/path: "/metrics"
    spec:
      serviceAccountName: dairy-platform-sa

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

      containers:
        - name: backend
          image: ${ECR_REGISTRY}/dairy-platform-backend:${IMAGE_TAG}
          imagePullPolicy: Always

          ports:
            - name: http
              containerPort: 3000
              protocol: TCP

          envFrom:
            - configMapRef:
                name: app-config
            - secretRef:
                name: app-secrets

          resources:
            requests:
              cpu: 500m
              memory: 1Gi
            limits:
              cpu: 2000m
              memory: 4Gi

          livenessProbe:
            httpGet:
              path: /health
              port: http
            initialDelaySeconds: 30
            periodSeconds: 10
            timeoutSeconds: 5
            failureThreshold: 3

          readinessProbe:
            httpGet:
              path: /ready
              port: http
            initialDelaySeconds: 10
            periodSeconds: 5
            timeoutSeconds: 3
            failureThreshold: 3

          lifecycle:
            preStop:
              exec:
                command: ["/bin/sh", "-c", "sleep 15"]

          volumeMounts:
            - name: logs
              mountPath: /app/logs

      volumes:
        - name: logs
          emptyDir: {}

---
# kubernetes/production/deployment-frontend.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: frontend-deployment
  namespace: dairy-platform-prod
  labels:
    app: frontend
spec:
  replicas: 3
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 1
      maxUnavailable: 0
  selector:
    matchLabels:
      app: frontend
  template:
    metadata:
      labels:
        app: frontend
    spec:
      containers:
        - name: frontend
          image: ${ECR_REGISTRY}/dairy-platform-frontend:${IMAGE_TAG}
          imagePullPolicy: Always

          ports:
            - name: http
              containerPort: 80
              protocol: TCP

          resources:
            requests:
              cpu: 200m
              memory: 512Mi
            limits:
              cpu: 1000m
              memory: 2Gi

          livenessProbe:
            httpGet:
              path: /
              port: http
            initialDelaySeconds: 10
            periodSeconds: 10

          readinessProbe:
            httpGet:
              path: /
              port: http
            initialDelaySeconds: 5
            periodSeconds: 5

---
# kubernetes/production/service-backend.yaml
apiVersion: v1
kind: Service
metadata:
  name: backend-service
  namespace: dairy-platform-prod
  labels:
    app: backend
spec:
  type: ClusterIP
  ports:
    - port: 80
      targetPort: 3000
      protocol: TCP
      name: http
  selector:
    app: backend

---
# kubernetes/production/service-frontend.yaml
apiVersion: v1
kind: Service
metadata:
  name: frontend-service
  namespace: dairy-platform-prod
  labels:
    app: frontend
spec:
  type: ClusterIP
  ports:
    - port: 80
      targetPort: 80
      protocol: TCP
      name: http
  selector:
    app: frontend

---
# kubernetes/production/ingress.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: dairy-platform-ingress
  namespace: dairy-platform-prod
  annotations:
    kubernetes.io/ingress.class: alb
    alb.ingress.kubernetes.io/scheme: internet-facing
    alb.ingress.kubernetes.io/target-type: ip
    alb.ingress.kubernetes.io/ssl-redirect: "443"
    alb.ingress.kubernetes.io/certificate-arn: ${ACM_CERTIFICATE_ARN}
    alb.ingress.kubernetes.io/listen-ports: '[{"HTTP": 80}, {"HTTPS": 443}]'
    alb.ingress.kubernetes.io/healthcheck-path: /health
    alb.ingress.kubernetes.io/healthcheck-interval-seconds: "30"
    alb.ingress.kubernetes.io/healthcheck-timeout-seconds: "5"
    alb.ingress.kubernetes.io/healthy-threshold-count: "2"
    alb.ingress.kubernetes.io/unhealthy-threshold-count: "3"
spec:
  rules:
    - host: api.dairyplatform.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: backend-service
                port:
                  number: 80

    - host: www.dairyplatform.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: frontend-service
                port:
                  number: 80

---
# kubernetes/production/hpa-backend.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: backend-hpa
  namespace: dairy-platform-prod
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: backend-deployment
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
          value: 50
          periodSeconds: 60
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
        - type: Percent
          value: 100
          periodSeconds: 30
        - type: Pods
          value: 2
          periodSeconds: 30
      selectPolicy: Max

---
# kubernetes/production/pdb.yaml
apiVersion: policy/v1
kind: PodDisruptionBudget
metadata:
  name: backend-pdb
  namespace: dairy-platform-prod
spec:
  minAvailable: 3
  selector:
    matchLabels:
      app: backend

---
apiVersion: policy/v1
kind: PodDisruptionBudget
metadata:
  name: frontend-pdb
  namespace: dairy-platform-prod
spec:
  minAvailable: 2
  selector:
    matchLabels:
      app: frontend

---
# kubernetes/production/network-policy.yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: backend-network-policy
  namespace: dairy-platform-prod
spec:
  podSelector:
    matchLabels:
      app: backend
  policyTypes:
    - Ingress
    - Egress
  ingress:
    - from:
        - podSelector:
            matchLabels:
              app: frontend
        - namespaceSelector:
            matchLabels:
              name: ingress-nginx
      ports:
        - protocol: TCP
          port: 3000
  egress:
    - to:
        - podSelector:
            matchLabels:
              app: database
      ports:
        - protocol: TCP
          port: 5432
    - to:
        - podSelector:
            matchLabels:
              app: redis
      ports:
        - protocol: TCP
          port: 6379
    - to:
        - namespaceSelector: {}
      ports:
        - protocol: TCP
          port: 443
        - protocol: TCP
          port: 80
```

**Dev 3: CI/CD Pipeline (GitHub Actions)**

```yaml
# .github/workflows/production-deploy.yml
name: Production Deployment

on:
  push:
    branches:
      - main
    tags:
      - 'v*'

env:
  AWS_REGION: ap-south-1
  EKS_CLUSTER_NAME: dairy-platform-cluster
  ECR_REGISTRY: ${AWS_ACCOUNT_ID}.dkr.ecr.ap-south-1.amazonaws.com
  IMAGE_TAG: ${{ github.sha }}

jobs:
  security-scan:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Run Trivy vulnerability scanner
        uses: aquasecurity/trivy-action@master
        with:
          scan-type: 'fs'
          scan-ref: '.'
          format: 'sarif'
          output: 'trivy-results.sarif'

      - name: Upload Trivy results to GitHub Security
        uses: github/codeql-action/upload-sarif@v2
        with:
          sarif_file: 'trivy-results.sarif'

      - name: Run Snyk security scan
        uses: snyk/actions/node@master
        env:
          SNYK_TOKEN: ${{ secrets.SNYK_TOKEN }}

  build-backend:
    runs-on: ubuntu-latest
    needs: security-scan

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v2
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ${{ env.AWS_REGION }}

      - name: Login to Amazon ECR
        id: login-ecr
        uses: aws-actions/amazon-ecr-login@v1

      - name: Build Docker image
        run: |
          docker build \
            --build-arg NODE_ENV=production \
            --build-arg BUILD_DATE=$(date -u +'%Y-%m-%dT%H:%M:%SZ') \
            --build-arg VCS_REF=${{ github.sha }} \
            --tag ${{ env.ECR_REGISTRY }}/dairy-platform-backend:${{ env.IMAGE_TAG }} \
            --tag ${{ env.ECR_REGISTRY }}/dairy-platform-backend:latest \
            --file backend/Dockerfile \
            backend/

      - name: Scan Docker image
        run: |
          docker run --rm \
            -v /var/run/docker.sock:/var/run/docker.sock \
            aquasec/trivy image \
            --severity HIGH,CRITICAL \
            --exit-code 1 \
            ${{ env.ECR_REGISTRY }}/dairy-platform-backend:${{ env.IMAGE_TAG }}

      - name: Push Docker image to ECR
        run: |
          docker push ${{ env.ECR_REGISTRY }}/dairy-platform-backend:${{ env.IMAGE_TAG }}
          docker push ${{ env.ECR_REGISTRY }}/dairy-platform-backend:latest

  build-frontend:
    runs-on: ubuntu-latest
    needs: security-scan

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v2
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ${{ env.AWS_REGION }}

      - name: Login to Amazon ECR
        id: login-ecr
        uses: aws-actions/amazon-ecr-login@v1

      - name: Build Docker image
        run: |
          docker build \
            --build-arg REACT_APP_API_URL=https://api.dairyplatform.com \
            --build-arg REACT_APP_ENV=production \
            --tag ${{ env.ECR_REGISTRY }}/dairy-platform-frontend:${{ env.IMAGE_TAG }} \
            --tag ${{ env.ECR_REGISTRY }}/dairy-platform-frontend:latest \
            --file frontend/Dockerfile \
            frontend/

      - name: Push Docker image to ECR
        run: |
          docker push ${{ env.ECR_REGISTRY }}/dairy-platform-frontend:${{ env.IMAGE_TAG }}
          docker push ${{ env.ECR_REGISTRY }}/dairy-platform-frontend:latest

  deploy-staging:
    runs-on: ubuntu-latest
    needs: [build-backend, build-frontend]
    environment: staging

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v2
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ${{ env.AWS_REGION }}

      - name: Update kubeconfig
        run: |
          aws eks update-kubeconfig \
            --name ${{ env.EKS_CLUSTER_NAME }} \
            --region ${{ env.AWS_REGION }}

      - name: Deploy to staging
        run: |
          kubectl set image deployment/backend-deployment \
            backend=${{ env.ECR_REGISTRY }}/dairy-platform-backend:${{ env.IMAGE_TAG }} \
            -n dairy-platform-staging

          kubectl set image deployment/frontend-deployment \
            frontend=${{ env.ECR_REGISTRY }}/dairy-platform-frontend:${{ env.IMAGE_TAG }} \
            -n dairy-platform-staging

      - name: Wait for rollout
        run: |
          kubectl rollout status deployment/backend-deployment -n dairy-platform-staging --timeout=10m
          kubectl rollout status deployment/frontend-deployment -n dairy-platform-staging --timeout=10m

      - name: Run smoke tests
        run: |
          npm install
          npm run test:smoke -- --env=staging

  deploy-production:
    runs-on: ubuntu-latest
    needs: deploy-staging
    environment: production

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v2
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ${{ env.AWS_REGION }}

      - name: Update kubeconfig
        run: |
          aws eks update-kubeconfig \
            --name ${{ env.EKS_CLUSTER_NAME }} \
            --region ${{ env.AWS_REGION }}

      - name: Create database backup
        run: |
          aws rds create-db-snapshot \
            --db-instance-identifier dairy-platform-db-primary \
            --db-snapshot-identifier pre-deploy-${{ github.sha }} \
            --region ${{ env.AWS_REGION }}

      - name: Blue-Green Deployment - Create Green
        run: |
          # Deploy new version with -green suffix
          kubectl apply -f kubernetes/production/deployment-backend.yaml

          # Update image
          kubectl set image deployment/backend-deployment-green \
            backend=${{ env.ECR_REGISTRY }}/dairy-platform-backend:${{ env.IMAGE_TAG }} \
            -n dairy-platform-prod

      - name: Wait for green deployment
        run: |
          kubectl rollout status deployment/backend-deployment-green \
            -n dairy-platform-prod --timeout=10m

      - name: Run health checks on green
        run: |
          kubectl run health-check --rm -i --restart=Never --image=curlimages/curl -- \
            curl -f http://backend-service-green/health

      - name: Switch traffic to green
        run: |
          kubectl patch service backend-service -n dairy-platform-prod \
            -p '{"spec":{"selector":{"version":"green"}}}'

      - name: Monitor for 5 minutes
        run: |
          sleep 300
          # Check error rates
          ERROR_RATE=$(kubectl logs -n dairy-platform-prod -l app=backend --tail=1000 | grep -c ERROR || echo 0)
          if [ $ERROR_RATE -gt 10 ]; then
            echo "High error rate detected, rolling back..."
            exit 1
          fi

      - name: Remove blue deployment
        if: success()
        run: |
          kubectl delete deployment backend-deployment-blue -n dairy-platform-prod

      - name: Rollback on failure
        if: failure()
        run: |
          kubectl patch service backend-service -n dairy-platform-prod \
            -p '{"spec":{"selector":{"version":"blue"}}}'
          kubectl delete deployment backend-deployment-green -n dairy-platform-prod

      - name: Send deployment notification
        if: always()
        uses: 8398a7/action-slack@v3
        with:
          status: ${{ job.status }}
          text: 'Production deployment ${{ job.status }}'
          webhook_url: ${{ secrets.SLACK_WEBHOOK }}

  post-deployment:
    runs-on: ubuntu-latest
    needs: deploy-production

    steps:
      - name: Run post-deployment tests
        run: npm run test:production

      - name: Update deployment tracking
        run: |
          curl -X POST https://api.dairyplatform.com/internal/deployments \
            -H "Authorization: Bearer ${{ secrets.INTERNAL_API_TOKEN }}" \
            -d '{
              "version": "${{ env.IMAGE_TAG }}",
              "environment": "production",
              "deployedBy": "${{ github.actor }}",
              "deployedAt": "${{ github.event.head_commit.timestamp }}"
            }'

      - name: Create GitHub release
        if: startsWith(github.ref, 'refs/tags/')
        uses: actions/create-release@v1
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
        with:
          tag_name: ${{ github.ref }}
          release_name: Release ${{ github.ref }}
          draft: false
          prerelease: false
```

#### Deliverables
- [ ] Cloud infrastructure provisioned (AWS/Azure/GCP)
- [ ] Kubernetes cluster configured
- [ ] CI/CD pipeline operational
- [ ] Auto-scaling configured
- [ ] Load balancing setup complete

---

### Day 703-705: Monitoring & Observability Setup
**Assigned to:** All Developers

#### Tasks
**Dev 1: Prometheus & Grafana Setup**

```yaml
# kubernetes/monitoring/prometheus.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: monitoring

---
apiVersion: v1
kind: ConfigMap
metadata:
  name: prometheus-config
  namespace: monitoring
data:
  prometheus.yml: |
    global:
      scrape_interval: 15s
      evaluation_interval: 15s
      external_labels:
        cluster: 'dairy-platform-prod'
        environment: 'production'

    alerting:
      alertmanagers:
        - static_configs:
            - targets:
                - alertmanager:9093

    rule_files:
      - /etc/prometheus/rules/*.yml

    scrape_configs:
      - job_name: 'kubernetes-apiservers'
        kubernetes_sd_configs:
          - role: endpoints
        scheme: https
        tls_config:
          ca_file: /var/run/secrets/kubernetes.io/serviceaccount/ca.crt
        bearer_token_file: /var/run/secrets/kubernetes.io/serviceaccount/token
        relabel_configs:
          - source_labels: [__meta_kubernetes_namespace, __meta_kubernetes_service_name, __meta_kubernetes_endpoint_port_name]
            action: keep
            regex: default;kubernetes;https

      - job_name: 'kubernetes-nodes'
        kubernetes_sd_configs:
          - role: node
        scheme: https
        tls_config:
          ca_file: /var/run/secrets/kubernetes.io/serviceaccount/ca.crt
        bearer_token_file: /var/run/secrets/kubernetes.io/serviceaccount/token

      - job_name: 'kubernetes-pods'
        kubernetes_sd_configs:
          - role: pod
        relabel_configs:
          - source_labels: [__meta_kubernetes_pod_annotation_prometheus_io_scrape]
            action: keep
            regex: true
          - source_labels: [__meta_kubernetes_pod_annotation_prometheus_io_path]
            action: replace
            target_label: __metrics_path__
            regex: (.+)
          - source_labels: [__address__, __meta_kubernetes_pod_annotation_prometheus_io_port]
            action: replace
            regex: ([^:]+)(?::\d+)?;(\d+)
            replacement: $1:$2
            target_label: __address__

      - job_name: 'dairy-platform-backend'
        kubernetes_sd_configs:
          - role: pod
            namespaces:
              names:
                - dairy-platform-prod
        relabel_configs:
          - source_labels: [__meta_kubernetes_pod_label_app]
            action: keep
            regex: backend

      - job_name: 'postgres'
        static_configs:
          - targets:
              - postgres-exporter:9187

      - job_name: 'redis'
        static_configs:
          - targets:
              - redis-exporter:9121

---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: prometheus
  namespace: monitoring
spec:
  replicas: 2
  selector:
    matchLabels:
      app: prometheus
  template:
    metadata:
      labels:
        app: prometheus
    spec:
      serviceAccountName: prometheus
      containers:
        - name: prometheus
          image: prom/prometheus:v2.45.0
          args:
            - '--config.file=/etc/prometheus/prometheus.yml'
            - '--storage.tsdb.path=/prometheus'
            - '--storage.tsdb.retention.time=30d'
            - '--web.enable-lifecycle'
            - '--web.enable-admin-api'
          ports:
            - containerPort: 9090
          resources:
            requests:
              cpu: 500m
              memory: 2Gi
            limits:
              cpu: 2000m
              memory: 8Gi
          volumeMounts:
            - name: config
              mountPath: /etc/prometheus
            - name: storage
              mountPath: /prometheus
      volumes:
        - name: config
          configMap:
            name: prometheus-config
        - name: storage
          persistentVolumeClaim:
            claimName: prometheus-pvc

---
# kubernetes/monitoring/grafana.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: grafana
  namespace: monitoring
spec:
  replicas: 1
  selector:
    matchLabels:
      app: grafana
  template:
    metadata:
      labels:
        app: grafana
    spec:
      containers:
        - name: grafana
          image: grafana/grafana:10.0.0
          ports:
            - containerPort: 3000
          env:
            - name: GF_SECURITY_ADMIN_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: grafana-secrets
                  key: admin-password
            - name: GF_INSTALL_PLUGINS
              value: grafana-piechart-panel,grafana-worldmap-panel
          resources:
            requests:
              cpu: 200m
              memory: 512Mi
            limits:
              cpu: 1000m
              memory: 2Gi
          volumeMounts:
            - name: storage
              mountPath: /var/lib/grafana
            - name: dashboards
              mountPath: /etc/grafana/provisioning/dashboards
            - name: datasources
              mountPath: /etc/grafana/provisioning/datasources
      volumes:
        - name: storage
          persistentVolumeClaim:
            claimName: grafana-pvc
        - name: dashboards
          configMap:
            name: grafana-dashboards
        - name: datasources
          configMap:
            name: grafana-datasources

---
# kubernetes/monitoring/alertmanager.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: alertmanager-config
  namespace: monitoring
data:
  alertmanager.yml: |
    global:
      resolve_timeout: 5m
      slack_api_url: ${SLACK_WEBHOOK_URL}

    route:
      group_by: ['alertname', 'cluster', 'service']
      group_wait: 10s
      group_interval: 10s
      repeat_interval: 12h
      receiver: 'slack-critical'
      routes:
        - match:
            severity: critical
          receiver: slack-critical
          continue: true

        - match:
            severity: warning
          receiver: slack-warning

    receivers:
      - name: 'slack-critical'
        slack_configs:
          - channel: '#alerts-critical'
            title: '{{ .GroupLabels.alertname }}'
            text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'
            send_resolved: true

      - name: 'slack-warning'
        slack_configs:
          - channel: '#alerts-warning'
            title: '{{ .GroupLabels.alertname }}'
            text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'
            send_resolved: true

    inhibit_rules:
      - source_match:
          severity: 'critical'
        target_match:
          severity: 'warning'
        equal: ['alertname', 'cluster', 'service']
```

**Dev 2: Application Performance Monitoring**

```javascript
// backend/src/config/apm.js
const apm = require('elastic-apm-node').start({
  serviceName: 'dairy-platform-backend',
  secretToken: process.env.ELASTIC_APM_SECRET_TOKEN,
  serverUrl: process.env.ELASTIC_APM_SERVER_URL,
  environment: process.env.NODE_ENV,

  // Transaction settings
  transactionSampleRate: 1.0,
  captureBody: 'errors',
  captureHeaders: true,

  // Error settings
  captureErrorLogStackTraces: 'always',
  stackTraceLimit: 50,

  // Performance settings
  metricsInterval: '30s',

  // Custom settings
  globalLabels: {
    cluster: 'dairy-platform-prod',
    region: 'ap-south-1'
  },

  // Ignore specific routes
  ignoreUrls: [
    '/health',
    '/ready',
    '/metrics'
  ]
});

// Custom transaction tracking
function trackTransaction(name, type, callback) {
  const transaction = apm.startTransaction(name, type);

  try {
    const result = callback();
    transaction.result = 'success';
    return result;
  } catch (error) {
    transaction.result = 'error';
    apm.captureError(error);
    throw error;
  } finally {
    transaction.end();
  }
}

// Custom span tracking
function trackSpan(name, type, callback) {
  const span = apm.startSpan(name, type);

  try {
    return callback();
  } finally {
    if (span) span.end();
  }
}

module.exports = {
  apm,
  trackTransaction,
  trackSpan
};
```

**Dev 3: Logging & Log Aggregation**

```javascript
// backend/src/config/logger.js
const winston = require('winston');
const { ElasticsearchTransport } = require('winston-elasticsearch');

const esTransportOpts = {
  level: 'info',
  clientOpts: {
    node: process.env.ELASTICSEARCH_URL,
    auth: {
      username: process.env.ELASTICSEARCH_USERNAME,
      password: process.env.ELASTICSEARCH_PASSWORD
    }
  },
  index: 'dairy-platform-logs'
};

const logger = winston.createLogger({
  level: process.env.LOG_LEVEL || 'info',
  format: winston.format.combine(
    winston.format.timestamp(),
    winston.format.errors({ stack: true }),
    winston.format.json()
  ),
  defaultMeta: {
    service: 'dairy-platform-backend',
    environment: process.env.NODE_ENV,
    hostname: process.env.HOSTNAME
  },
  transports: [
    new winston.transports.Console({
      format: winston.format.combine(
        winston.format.colorize(),
        winston.format.simple()
      )
    }),
    new ElasticsearchTransport(esTransportOpts)
  ]
});

// Request logging middleware
const requestLogger = (req, res, next) => {
  const start = Date.now();

  res.on('finish', () => {
    const duration = Date.now() - start;

    logger.info('HTTP Request', {
      method: req.method,
      url: req.url,
      status: res.statusCode,
      duration,
      ip: req.ip,
      userAgent: req.get('user-agent'),
      userId: req.user?.id
    });
  });

  next();
};

module.exports = { logger, requestLogger };
```

#### Deliverables
- [ ] Prometheus monitoring configured
- [ ] Grafana dashboards created
- [ ] AlertManager configured
- [ ] APM tools integrated
- [ ] Log aggregation setup (ELK/EFK)
- [ ] 24/7 monitoring active

---

## MILESTONE 15.2-15.10: [Remaining Milestones]

**The document continues with:**

- Milestone 15.2: Data Migration (Days 706-710)
- Milestone 15.3: Security Hardening (Days 711-715)
- Milestone 15.4: Staging Deployment (Days 716-720)
- Milestone 15.5: Production Deployment (Days 721-725)
- Milestone 15.6: Go-Live Support (Days 726-730)
- Milestone 15.7: User Training (Days 731-735)
- Milestone 15.8: Knowledge Transfer (Days 736-740)
- Milestone 15.9: Performance Monitoring (Days 741-745)
- Milestone 15.10: Project Closure (Days 746-750)

---

## Phase 15 Success Metrics

### Deployment Metrics
- [ ] Zero-downtime deployment achieved
- [ ] Production environment stable
- [ ] 99.9% uptime in first month
- [ ] All security measures active
- [ ] Data migration successful (100%)

### Training & Handover Metrics
- [ ] All users trained
- [ ] Documentation complete
- [ ] Knowledge transfer complete
- [ ] Support team ready
- [ ] Final stakeholder sign-off

---

**End of Phase 15 Document** (Partial - Full document includes all 10 milestones with detailed deployment procedures, training materials, and handover checklists)
