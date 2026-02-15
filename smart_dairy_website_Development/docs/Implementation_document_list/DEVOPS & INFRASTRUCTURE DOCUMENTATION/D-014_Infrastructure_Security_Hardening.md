# Document: D-014 - Infrastructure Security Hardening

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | D-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CISO |
| **Status** | Approved |
| **Classification** | Confidential |

## Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Security Engineer | Initial release |

## Distribution List

- DevOps Team
- Security Engineering Team
- Infrastructure Team
- Compliance Team
- Development Leads

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [AWS Security](#2-aws-security)
3. [Network Security](#3-network-security)
4. [EKS Security](#4-eks-security)
5. [Container Security](#5-container-security)
6. [Secrets Management](#6-secrets-management)
7. [Runtime Security](#7-runtime-security)
8. [Patch Management](#8-patch-management)
9. [Backup Security](#9-backup-security)
10. [Compliance Monitoring](#10-compliance-monitoring)
11. [Appendices](#11-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the infrastructure security hardening standards for Smart Dairy Ltd's cloud-native platform. It establishes security configurations, controls, and best practices for AWS infrastructure, EKS clusters, containers, and supporting services.

### 1.2 Scope

| Component | Scope |
|-----------|-------|
| Cloud Platform | AWS (All regions used by Smart Dairy) |
| Container Orchestration | Amazon EKS |
| Compute | EC2, Fargate, EKS Managed Nodes |
| Networking | VPC, Transit Gateway, Direct Connect |
| Storage | EBS, EFS, S3 |
| Databases | RDS, ElastiCache, DocumentDB |
| Security Services | IAM, KMS, CloudTrail, GuardDuty, Security Hub |

### 1.3 Compliance Requirements

Smart Dairy operates under multiple regulatory frameworks:

#### 1.3.1 PCI DSS Compliance
- **Scope**: Payment processing infrastructure
- **Requirements**: Data encryption, access controls, network segmentation, logging
- **Compliance Level**: Level 1 Merchant (processing >6M transactions annually)

#### 1.3.2 GDPR Compliance
- **Scope**: Customer personal data of EU citizens
- **Requirements**: Data minimization, right to erasure, data portability, breach notification
- **DPO Contact**: dpo@smartdairy.com.bd

#### 1.3.3 Bangladesh Data Protection Act 2023
- **Scope**: Personal data of Bangladeshi citizens
- **Requirements**:
  - Data localization for sensitive personal data
  - Consent management for data processing
  - Cross-border data transfer restrictions
  - Breach notification within 72 hours to BTRC

### 1.4 Security Principles

```
+------------------------------------------------------------------+
|                    DEFENSE IN DEPTH STRATEGY                      |
+------------------------------------------------------------------+
|  Layer 7 | Application Security (WAF, Input Validation)          |
|  Layer 6 | Container Security (Pod Security, Network Policies)   |
|  Layer 5 | Orchestration Security (RBAC, Admission Controllers)  |
|  Layer 4 | Network Security (Security Groups, NACLs)             |
|  Layer 3 | Host Security (OS Hardening, Host-based Firewall)     |
|  Layer 2 | Identity Security (IAM, IRSA, Pod Identity)           |
|  Layer 1 | Perimeter Security (CloudFront, Shield, WAF)          |
|  Layer 0 | Physical Security (AWS Data Centers)                  |
+------------------------------------------------------------------+
```

### 1.5 Zero-Trust Architecture

```
+------------------------------------------------------------------+
|                      ZERO-TRUST PRINCIPLES                        |
+------------------------------------------------------------------+
|  1. Never trust, always verify                                    |
|  2. Assume breach - continuous validation                         |
|  3. Least privilege access                                        |
|  4. Micro-segmentation                                            |
|  5. Multi-factor authentication everywhere                        |
|  6. Encrypt everything in transit and at rest                     |
+------------------------------------------------------------------+
```

### 1.6 Bangladesh Data Localization Requirements

```yaml
DataLocalization:
  Requirement: Critical and Sensitive personal data must be stored in Bangladesh
  Implementation:
    PrimaryRegion: ap-south-1  # Mumbai (closest to Bangladesh)
    DataResidency:
      - CustomerPII: ap-south-1
      - PaymentData: ap-south-1
      - AuthenticationLogs: ap-south-1
      - HealthRecords: ap-south-1
    CrossBorderTransfer:
      Allowed: false
      Exception: With explicit consent + BTRC approval
    Encryption:
      AtRest: AES-256 with KMS keys in ap-south-1
      InTransit: TLS 1.3
```

---

## 2. AWS Security

### 2.1 IAM Best Practices

#### 2.1.1 IAM Password Policy

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "EnforceStrongPasswords",
      "Effect": "Deny",
      "Action": [
        "iam:CreateLoginProfile",
        "iam:UpdateLoginProfile"
      ],
      "Resource": "*",
      "Condition": {
        "NumericLessThan": {
          "iam:PasswordPolicy.minPasswordLength": "16"
        }
      }
    }
  ]
}
```

#### 2.1.2 IAM Role Trust Policy (External ID Required)

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "AWS": "arn:aws:iam::111111111111:root"
      },
      "Action": "sts:AssumeRole",
      "Condition": {
        "StringEquals": {
          "sts:ExternalId": "smart-dairy-external-id-2026"
        },
        "Bool": {
          "aws:MultiFactorAuthPresent": "true"
        }
      }
    }
  ]
}
```

#### 2.1.3 EKS Node IAM Role (Least Privilege)

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "EKSWorkerNodePermissions",
      "Effect": "Allow",
      "Action": [
        "ec2:DescribeInstances",
        "ec2:DescribeRegions",
        "ecr:GetAuthorizationToken",
        "ecr:BatchCheckLayerAvailability",
        "ecr:GetDownloadUrlForLayer",
        "ecr:BatchGetImage"
      ],
      "Resource": "*"
    },
    {
      "Sid": "CloudWatchLogs",
      "Effect": "Allow",
      "Action": [
        "logs:CreateLogStream",
        "logs:PutLogEvents"
      ],
      "Resource": "arn:aws:logs:*:*:log-group:/aws/eks/*:*"
    },
    {
      "Sid": "DenyDangerousActions",
      "Effect": "Deny",
      "Action": [
        "iam:*",
        "organizations:*",
        "account:*"
      ],
      "Resource": "*"
    }
  ]
}
```

#### 2.1.4 Service Control Policies (SCPs)

**SCP 1: Deny Root Account Usage**

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "DenyRootAccount",
      "Effect": "Deny",
      "Action": "*",
      "Resource": "*",
      "Condition": {
        "StringLike": {
          "aws:PrincipalArn": [
            "arn:aws:iam::*:root"
          ]
        }
      }
    }
  ]
}
```

**SCP 2: Require Encryption in Transit**

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "DenyUnencryptedConnections",
      "Effect": "Deny",
      "Action": [
        "s3:GetObject",
        "s3:PutObject"
      ],
      "Resource": "*",
      "Condition": {
        "Bool": {
          "aws:SecureTransport": "false"
        }
      }
    },
    {
      "Sid": "DenyUnencryptedRDS",
      "Effect": "Deny",
      "Action": [
        "rds:CreateDBInstance",
        "rds:CreateDBCluster"
      ],
      "Resource": "*",
      "Condition": {
        "Bool": {
          "rds:StorageEncrypted": "false"
        }
      }
    }
  ]
}
```

**SCP 3: Bangladesh Data Residency**

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "RestrictToAPSouth1",
      "Effect": "Deny",
      "Action": [
        "s3:CreateBucket",
        "rds:CreateDBInstance",
        "ec2:CreateVolume",
        "dynamodb:CreateTable",
        "kms:CreateKey"
      ],
      "Resource": "*",
      "Condition": {
        "StringNotEquals": {
          "aws:RequestedRegion": [
            "ap-south-1",
            "ap-southeast-1"
          ]
        }
      }
    }
  ]
}
```

**SCP 4: Require MFA for Destructive Actions**

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "RequireMFAForDelete",
      "Effect": "Deny",
      "Action": [
        "s3:DeleteBucket",
        "rds:DeleteDBInstance",
        "ec2:DeleteVolume",
        "eks:DeleteCluster",
        "kms:ScheduleKeyDeletion"
      ],
      "Resource": "*",
      "Condition": {
        "BoolIfExists": {
          "aws:MultiFactorAuthPresent": "false"
        }
      }
    }
  ]
}
```

### 2.2 CloudTrail Configuration

```yaml
# cloudtrail-organization.yaml
AWSTemplateFormatVersion: '2010-09-09'
Description: 'Organization CloudTrail for Smart Dairy'

Resources:
  OrganizationTrail:
    Type: AWS::CloudTrail::Trail
    Properties:
      TrailName: SmartDairy-Organization-Trail
      IsLogging: true
      IsMultiRegionTrail: true
      EnableLogFileValidation: true
      KMSKeyId: !Ref CloudTrailKMSKey
      S3BucketName: !Ref CloudTrailBucket
      S3KeyPrefix: organization-trail
      IncludeGlobalServiceEvents: true
      EventSelectors:
        - ReadWriteType: All
          IncludeManagementEvents: true
          DataResources:
            - Type: AWS::S3::Object
              Values:
                - arn:aws:s3:::
            - Type: AWS::Lambda::Function
              Values:
                - arn:aws:lambda
      InsightSelectors:
        - InsightType: ApiCallRateInsight
        - InsightType: ApiErrorRateInsight

  CloudTrailBucket:
    Type: AWS::S3::Bucket
    Properties:
      BucketName: smartdairy-cloudtrail-logs-prod
      BucketEncryption:
        ServerSideEncryptionConfiguration:
          - ServerSideEncryptionByDefault:
              SSEAlgorithm: aws:kms
              KMSMasterKeyID: !Ref CloudTrailKMSKey
      LifecycleConfiguration:
        Rules:
          - Id: TransitionToGlacier
            Status: Enabled
            Transitions:
              - StorageClass: GLACIER
                TransitionInDays: 90
          - Id: Expiration
            Status: Enabled
            ExpirationInDays: 2555
      PublicAccessBlockConfiguration:
        BlockPublicAcls: true
        BlockPublicPolicy: true
        IgnorePublicAcls: true
        RestrictPublicBuckets: true
      VersioningConfiguration:
        Status: Enabled
```

### 2.3 GuardDuty Configuration

```yaml
# guardduty-configuration.yaml
AWSTemplateFormatVersion: '2010-09-09'
Description: 'GuardDuty Configuration for Smart Dairy'

Resources:
  GuardDutyDetector:
    Type: AWS::GuardDuty::Detector
    Properties:
      Enable: true
      FindingPublishingFrequency: FIFTEEN_MINUTES
      DataSources:
        S3Logs:
          Enable: true
        KubernetesAuditLogs:
          Enable: true
        MalwareProtection:
          ScanEc2InstanceWithFindings:
            Enable: true
```

### 2.4 Security Hub Configuration

```yaml
# security-hub-configuration.yaml
AWSTemplateFormatVersion: '2010-09-17'
Description: 'Security Hub Configuration'

Resources:
  SecurityHub:
    Type: AWS::SecurityHub::Hub
    Properties:
      AutoEnableControls: true

  PCIDSSStandard:
    Type: AWS::SecurityHub::Standard
    Properties:
      StandardsArn: arn:aws:securityhub:::standards/pci-dss/v/3.2.1

  CISStandard:
    Type: AWS::SecurityHub::Standard
    Properties:
      StandardsArn: arn:aws:securityhub:::standards/cis-aws-foundations-benchmark/v/1.2.0

  FoundationalStandard:
    Type: AWS::SecurityHub::Standard
    Properties:
      StandardsArn: arn:aws:securityhub:::standards/aws-foundational-security-best-practices/v/1.0.0

  SecurityHubSNSTopic:
    Type: AWS::SNS::Topic
    Properties:
      TopicName: security-hub-findings
      KmsMasterKeyId: alias/aws/sns
```

---

## 3. Network Security

### 3.1 VPC Architecture

```
+-----------------------------------------------------------------------------+
|                        SMART DAIRY VPC ARCHITECTURE                          |
|                          ap-south-1 (Mumbai)                                 |
+-----------------------------------------------------------------------------+
|                                                                              |
|  +-----------------------------------------------------------------------+   |
|  |                     VPC: vpc-smartdairy-prod                           |   |
|  |                     CIDR: 10.0.0.0/16                                  |   |
|  |                                                                        |   |
|  |  +----------------+  +----------------+  +-------------------------+  |   |
|  |  | Public Subnet  |  | Public Subnet  |  |     NAT Gateway         |  |   |
|  |  | 10.0.1.0/24    |  | 10.0.2.0/24    |  |                         |  |   |
|  |  | ap-south-1a    |  | ap-south-1b    |  |  EIP Allocation         |  |   |
|  |  |                |  |                |  |                         |  |   |
|  |  | ALB (Public)   |  | ALB (Public)   |  |  +-----------------+    |  |   |
|  |  | Bastion Host   |  | NAT Gateway    |  |  |   NAT GW        |    |  |   |
|  |  +----------------+  +----------------+  |  +-----------------+    |  |   |
|  |                                          +-------------------------+  |   |
|  |                                                                        |   |
|  |  +----------------+  +----------------+                                |   |
|  |  | Private Subnet |  | Private Subnet |                                |   |
|  |  | 10.0.10.0/24   |  | 10.0.11.0/24   |                                |   |
|  |  | ap-south-1a    |  | ap-south-1b    |                                |   |
|  |  |                |  |                |                                |   |
|  |  | EKS Worker     |  | EKS Worker     |                                |   |
|  |  | Nodes          |  | Nodes          |                                |   |
|  |  | Application    |  | Application    |                                |   |
|  |  | Pods           |  | Pods           |                                |   |
|  |  +----------------+  +----------------+                                |   |
|  |                                                                        |   |
|  |  +----------------+  +----------------+                                |   |
|  |  | Data Subnet    |  | Data Subnet    |                                |   |
|  |  | 10.0.20.0/24   |  | 10.0.21.0/24   |                                |   |
|  |  | ap-south-1a    |  | ap-south-1b    |                                |   |
|  |  |                |  |                |                                |   |
|  |  | RDS PostgreSQL |  | RDS Replica    |                                |   |
|  |  | ElastiCache    |  | ElastiCache    |                                |   |
|  |  | DocumentDB     |  | DocumentDB     |                                |   |
|  |  +----------------+  +----------------+                                |   |
|  |                                                                        |   |
|  +----------------------------------------------------------------------+   |
|                                                                              |
+-----------------------------------------------------------------------------+
```

### 3.2 VPC Flow Logs

```yaml
# vpc-flow-logs.yaml
AWSTemplateFormatVersion: '2010-09-09'
Description: 'VPC Flow Logs for Smart Dairy'

Resources:
  VPCFlowLogRole:
    Type: AWS::IAM::Role
    Properties:
      RoleName: VPCFlowLogRole
      AssumeRolePolicyDocument:
        Version: '2012-10-17'
        Statement:
          - Effect: Allow
            Principal:
              Service: vpc-flow-logs.amazonaws.com
            Action: sts:AssumeRole

  VPCFlowLog:
    Type: AWS::EC2::FlowLog
    Properties:
      DeliverLogsPermissionArn: !GetAtt VPCFlowLogRole.Arn
      LogGroupName: /aws/vpc/flowlogs/smartdairy-prod
      ResourceId: !Ref SmartDairyVPC
      ResourceType: VPC
      TrafficType: ALL
      LogDestinationType: cloud-watch-logs
      MaxAggregationInterval: 60
```

### 3.3 Security Groups

#### 3.3.1 EKS Node Security Group

```yaml
apiVersion: ec2.aws.crossplane.io/v1beta1
kind: SecurityGroup
metadata:
  name: eks-node-sg
spec:
  forProvider:
    vpcId: vpc-xxxxxxxxxxxxxxxxx
    region: ap-south-1
    description: "EKS Node Security Group - Smart Dairy"
    groupName: eks-node-smartdairy-prod
    ingress:
      # Allow inter-node communication
      - fromPort: 0
        toPort: 65535
        ipProtocol: tcp
        userIdGroupPairs:
          - groupId: sg-eks-node
        description: "Allow inter-node communication"
      # Allow SSH from bastion only
      - fromPort: 22
        toPort: 22
        ipProtocol: tcp
        userIdGroupPairs:
          - groupId: sg-bastion
        description: "SSH from bastion only"
    egress:
      - fromPort: 0
        toPort: 0
        ipProtocol: "-1"
        ipRanges:
          - cidrIp: 0.0.0.0/0
        description: "Allow all outbound"
```

### 3.4 Network ACLs

```yaml
apiVersion: ec2.aws.crossplane.io/v1beta1
kind: NetworkAcl
metadata:
  name: private-subnet-nacl
spec:
  forProvider:
    vpcId: vpc-xxxxxxxxxxxxxxxxx
    region: ap-south-1
    ingress:
      - ruleNumber: 100
        protocol: 6
        ruleAction: allow
        cidrBlock: 10.0.1.0/24
        portRange:
          from: 80
          to: 80
      - ruleNumber: 110
        protocol: 6
        ruleAction: allow
        cidrBlock: 10.0.1.0/24
        portRange:
          from: 443
          to: 443
      - ruleNumber: 32766
        protocol: -1
        ruleAction: deny
        cidrBlock: 0.0.0.0/0
    egress:
      - ruleNumber: 100
        protocol: 6
        ruleAction: allow
        cidrBlock: 0.0.0.0/0
        portRange:
          from: 443
          to: 443
      - ruleNumber: 110
        protocol: 6
        ruleAction: allow
        cidrBlock: 10.0.20.0/24
        portRange:
          from: 5432
          to: 5432
```

### 3.5 AWS WAF Configuration

```yaml
# waf-web-acl.yaml
AWSTemplateFormatVersion: '2010-09-09'
Description: 'WAF WebACL for Smart Dairy'

Resources:
  SmartDairyWebACL:
    Type: AWS::WAFv2::WebACL
    Properties:
      Name: smartdairy-webacl-prod
      Scope: REGIONAL
      DefaultAction:
        Allow: {}
      VisibilityConfig:
        SampledRequestsEnabled: true
        CloudWatchMetricsEnabled: true
        MetricName: SmartDairyWebACL
      Rules:
        # AWS Managed Rules - Common Rule Set
        - Name: AWSManagedRulesCommonRuleSet
          Priority: 1
          Statement:
            ManagedRuleGroupStatement:
              VendorName: AWS
              Name: AWSManagedRulesCommonRuleSet
          OverrideAction:
            None: {}
          VisibilityConfig:
            SampledRequestsEnabled: true
            CloudWatchMetricsEnabled: true
            MetricName: AWSManagedRulesCommonRuleSetMetric

        # Rate Limiting
        - Name: RateLimitRule
          Priority: 2
          Statement:
            RateBasedStatement:
              Limit: 2000
              AggregateKeyType: IP
          Action:
            Block: {}
          VisibilityConfig:
            SampledRequestsEnabled: true
            CloudWatchMetricsEnabled: true
            MetricName: RateLimitRuleMetric

        # Geo-blocking
        - Name: GeoBlockingRule
          Priority: 3
          Statement:
            GeoMatchStatement:
              CountryCodes:
                - BD
                - US
                - SG
              MatchAction: Block
          Action:
            Block: {}
          VisibilityConfig:
            SampledRequestsEnabled: true
            CloudWatchMetricsEnabled: true
            MetricName: GeoBlockingRuleMetric
```

---

## 4. EKS Security

### 4.1 EKS Cluster Configuration

```yaml
apiVersion: eks.aws.crossplane.io/v1beta1
kind: Cluster
metadata:
  name: smartdairy-prod-cluster
spec:
  forProvider:
    region: ap-south-1
    version: "1.29"
    roleArn: arn:aws:iam::123456789012:role/eks-cluster-role
    resourcesVpcConfig:
      subnetIds:
        - subnet-private-1a
        - subnet-private-1b
      endpointPrivateAccess: true
      endpointPublicAccess: false
      securityGroupIds:
        - sg-eks-control-plane
    encryptionConfig:
      - provider:
          keyArn: arn:aws:kms:ap-south-1:123456789012:key/eks-secrets-key
        resources:
          - secrets
    logging:
      clusterLogging:
        - enabled: true
          types:
            - api
            - audit
            - authenticator
            - controllerManager
            - scheduler
```

### 4.2 Pod Security Standards

```yaml
apiVersion: policy/v1beta1
kind: PodSecurityPolicy
metadata:
  name: smartdairy-restricted
  annotations:
    seccomp.security.alpha.kubernetes.io/allowedProfiles: 'runtime/default'
spec:
  privileged: false
  allowPrivilegeEscalation: false
  requiredDropCapabilities:
    - ALL
  volumes:
    - 'configMap'
    - 'emptyDir'
    - 'secret'
    - 'persistentVolumeClaim'
  runAsUser:
    rule: 'MustRunAsNonRoot'
  fsGroup:
    rule: 'MustRunAs'
    ranges:
      - min: 1
        max: 65535
  readOnlyRootFilesystem: true
```

### 4.3 Network Policies

#### 4.3.1 Default Deny All

```yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: default-deny-ingress
  namespace: smartdairy
spec:
  podSelector: {}
  policyTypes:
    - Ingress
---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: default-deny-egress
  namespace: smartdairy
spec:
  podSelector: {}
  policyTypes:
    - Egress
```

#### 4.3.2 Backend API Policy

```yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: api-network-policy
  namespace: smartdairy
spec:
  podSelector:
    matchLabels:
      app: api
      tier: backend
  policyTypes:
    - Ingress
    - Egress
  ingress:
    - from:
        - podSelector:
            matchLabels:
              app: frontend
              tier: frontend
      ports:
        - protocol: TCP
          port: 8080
  egress:
    - to:
        - podSelector:
            matchLabels:
              app: postgresql
      ports:
        - protocol: TCP
          port: 5432
    - to:
        - namespaceSelector: {}
          podSelector:
            matchLabels:
              k8s-app: kube-dns
      ports:
        - protocol: UDP
          port: 53
```

### 4.4 RBAC Configuration

#### 4.4.1 CI/CD Service Account

```yaml
apiVersion: v1
kind: ServiceAccount
metadata:
  name: cicd-deployer
  namespace: smartdairy
  annotations:
    eks.amazonaws.com/role-arn: arn:aws:iam::123456789012:role/cicd-deployer-role
automountServiceAccountToken: false
---
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata:
  name: cicd-deployer
  namespace: smartdairy
rules:
  - apiGroups: [""]
    resources: ["services", "configmaps", "secrets"]
    verbs: ["get", "list", "create", "update", "patch"]
  - apiGroups: ["apps"]
    resources: ["deployments"]
    verbs: ["get", "list", "create", "update", "patch"]
---
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata:
  name: cicd-deployer-binding
  namespace: smartdairy
subjects:
  - kind: ServiceAccount
    name: cicd-deployer
    namespace: smartdairy
roleRef:
  kind: Role
  name: cicd-deployer
  apiGroup: rbac.authorization.k8s.io
```

### 4.5 IRSA Configuration

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "Federated": "arn:aws:iam::123456789012:oidc-provider/oidc.eks.ap-south-1.amazonaws.com/id/EXAMPLE"
      },
      "Action": "sts:AssumeRoleWithWebIdentity",
      "Condition": {
        "StringEquals": {
          "oidc.eks.ap-south-1.amazonaws.com/id/EXAMPLE:sub": "system:serviceaccount:smartdairy:app-service-account",
          "oidc.eks.ap-south-1.amazonaws.com/id/EXAMPLE:aud": "sts.amazonaws.com"
        }
      }
    }
  ]
}
```

### 4.6 OPA/Gatekeeper Policies

#### Constraint: Require Resource Limits

```yaml
apiVersion: templates.gatekeeper.sh/v1
kind: ConstraintTemplate
metadata:
  name: k8srequiredresources
spec:
  crd:
    spec:
      names:
        kind: K8sRequiredResources
  targets:
    - target: admission.k8s.gatekeeper.sh
      rego: |
        package k8srequiredresources

        violation[{"msg": msg}] {
          container := input.review.object.spec.containers[_]
          required := input.parameters.limits[_]
          not container.resources.limits[required]
          msg := sprintf("Container %s must define %s limit", [container.name, required])
        }
```

```yaml
apiVersion: constraints.gatekeeper.sh/v1beta1
kind: K8sRequiredResources
metadata:
  name: require-resource-limits
spec:
  match:
    kinds:
      - apiGroups: [""]
        kinds: ["Pod"]
  parameters:
    limits:
      - cpu
      - memory
    requests:
      - cpu
      - memory
```

#### Constraint: Block Latest Tag

```yaml
apiVersion: constraints.gatekeeper.sh/v1beta1
kind: K8sBlockLatestTag
metadata:
  name: block-latest-tag
spec:
  match:
    kinds:
      - apiGroups: [""]
        kinds: ["Pod"]
```

#### Constraint: No Privileged Containers

```yaml
apiVersion: templates.gatekeeper.sh/v1
kind: ConstraintTemplate
metadata:
  name: k8spspprivileged
spec:
  crd:
    spec:
      names:
        kind: K8sPSPPrivileged
  targets:
    - target: admission.k8s.gatekeeper.sh
      rego: |
        package k8spspprivileged

        violation[{"msg": msg}] {
          container := input.review.object.spec.containers[_]
          container.securityContext.privileged == true
          msg := sprintf("Privileged container is not allowed: %s", [container.name])
        }
```

---

## 5. Container Security

### 5.1 Security Context

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: secure-app-pod
  namespace: smartdairy
spec:
  securityContext:
    runAsNonRoot: true
    runAsUser: 1000
    runAsGroup: 1000
    fsGroup: 1000
    seccompProfile:
      type: RuntimeDefault
  containers:
    - name: app
      image: smartdairy/app:v1.2.3
      securityContext:
        allowPrivilegeEscalation: false
        readOnlyRootFilesystem: true
        capabilities:
          drop:
            - ALL
      volumeMounts:
        - name: tmp
          mountPath: /tmp
  volumes:
    - name: tmp
      emptyDir: {}
  automountServiceAccountToken: false
```

### 5.2 Secure Dockerfile

```dockerfile
# Stage 1: Build
FROM node:20-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production && npm cache clean --force
COPY . .
RUN npm run build

# Stage 2: Production - Distroless
FROM gcr.io/distroless/nodejs20-debian12:nonroot
COPY --from=builder --chown=nonroot:nonroot /app/dist /app/dist
COPY --from=builder --chown=nonroot:nonroot /app/node_modules /app/node_modules
WORKDIR /app
USER nonroot:nonroot
EXPOSE 3000
HEALTHCHECK --interval=30s --timeout=3s CMD node -e "require('http').get('http://localhost:3000/health')"
CMD ["dist/main.js"]
```

### 5.3 Trivy Scanning

```yaml
name: Trivy Security Scan
on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Build image
        run: docker build -t smartdairy/app:${{ github.sha }} .
      - name: Run Trivy vulnerability scanner
        uses: aquasecurity/trivy-action@master
        with:
          image-ref: 'smartdairy/app:${{ github.sha }}'
          format: 'sarif'
          output: 'trivy-results.sarif'
          severity: 'CRITICAL,HIGH'
          exit-code: '1'
      - name: Upload results
        uses: github/codeql-action/upload-sarif@v2
        with:
          sarif_file: 'trivy-results.sarif'
```

---

## 6. Secrets Management

### 6.1 AWS Secrets Manager

```yaml
AWSTemplateFormatVersion: '2010-09-09'
Resources:
  DatabaseSecret:
    Type: AWS::SecretsManager::Secret
    Properties:
      Name: smartdairy/prod/database/credentials
      Description: 'PostgreSQL database credentials'
      KmsKeyId: !Ref SecretsKMSKey
      SecretString: |
        {
          "username": "smartdairy_admin",
          "password": "{{resolve:secretsmanager:${DBPassword}}}",
          "engine": "postgres",
          "host": "smartdairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com",
          "port": 5432,
          "dbname": "smartdairy_prod"
        }

  DatabaseSecretRotation:
    Type: AWS::SecretsManager::RotationSchedule
    Properties:
      SecretId: !Ref DatabaseSecret
      RotationLambdaARN: !GetAtt SecretsRotationFunction.Arn
      RotationRules:
        AutomaticallyAfterDays: 30
```

### 6.2 External Secrets Operator

```yaml
apiVersion: external-secrets.io/v1beta1
kind: ClusterSecretStore
metadata:
  name: aws-secrets-manager
spec:
  provider:
    aws:
      service: SecretsManager
      region: ap-south-1
      auth:
        jwt:
          serviceAccountRef:
            name: external-secrets-sa
            namespace: security
---
apiVersion: external-secrets.io/v1beta1
kind: ExternalSecret
metadata:
  name: database-credentials
  namespace: smartdairy
spec:
  refreshInterval: 1h
  secretStoreRef:
    kind: ClusterSecretStore
    name: aws-secrets-manager
  target:
    name: database-credentials
    creationPolicy: Owner
  data:
    - secretKey: DB_HOST
      remoteRef:
        key: smartdairy/prod/database/credentials
        property: host
    - secretKey: DB_PASSWORD
      remoteRef:
        key: smartdairy/prod/database/credentials
        property: password
```

---

## 7. Runtime Security

### 7.1 Falco Configuration

```yaml
# falco-values.yaml
driver:
  kind: modern_ebpf
  modern_ebpf:
    leastPrivileged: true

customRules:
  smartdairy-rules.yaml: |
    - rule: Unauthorized Sensitive File Access
      desc: Detect unauthorized access to sensitive files
      condition: >
        spawned_process and
        (fd.name contains "/etc/shadow" or
         fd.name contains "/var/run/secrets/kubernetes.io") and
        not user.name in (root, kubelet)
      output: "Unauthorized sensitive file access (user=%user.name file=%fd.name)"
      priority: WARNING
    
    - rule: Crypto Mining Detected
      desc: Detect crypto mining
      condition: >
        spawned_process and
        (proc.name in (xmrig, minerd) or
         proc.cmdline contains "stratum+tcp")
      output: "Crypto mining detected (user=%user.name)"
      priority: CRITICAL
    
    - rule: Reverse Shell
      desc: Detect reverse shell
      condition: >
        spawned_process and
        (proc.name in (bash, sh) and fd.name contains "/dev/tcp/")
      output: "Reverse shell detected (user=%user.name)"
      priority: CRITICAL

falcosidekick:
  enabled: true
  config:
    slack:
      webhookurl: "${SLACK_WEBHOOK_URL}"
      minimumpriority: "warning"
```

### 7.2 Audit Logging

```yaml
apiVersion: audit.k8s.io/v1
kind: Policy
rules:
  - level: RequestResponse
    resources:
      - group: ""
        resources: ["pods", "secrets"]
    namespaces: ["smartdairy"]
  
  - level: RequestResponse
    resources:
      - group: "rbac.authorization.k8s.io"
        resources: ["roles", "rolebindings"]
  
  - level: RequestResponse
    resources:
      - group: ""
        resources: ["pods/exec", "pods/attach"]
  
  - level: Metadata
    omitStages:
      - RequestReceived
```

---

## 8. Patch Management

### 8.1 EKS Managed Node Updates

```yaml
apiVersion: eks.aws.crossplane.io/v1beta1
kind: NodeGroup
metadata:
  name: smartdairy-prod-workers
spec:
  forProvider:
    clusterName: smartdairy-prod-cluster
    version: "1.29"
    releaseVersion: "1.29.0-20240115"
    updateConfig:
      maxUnavailable: 1
      maxUnavailablePercentage: 25
```

### 8.2 Dependabot Configuration

```yaml
# .github/dependabot.yml
version: 2
updates:
  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "daily"
    target-branch: "develop"
    labels:
      - "dependencies"
      - "security"
    open-pull-requests-limit: 10

  - package-ecosystem: "docker"
    directory: "/"
    schedule:
      interval: "weekly"
    target-branch: "develop"
```

---

## 9. Backup Security

### 9.1 Velero Configuration

```yaml
apiVersion: velero.io/v1
kind: BackupStorageLocation
metadata:
  name: aws
  namespace: velero
spec:
  provider: aws
  objectStorage:
    bucket: smartdairy-velero-backups
    prefix: production
  config:
    region: ap-south-1
    kmsKeyId: arn:aws:kms:ap-south-1:123456789012:key/velero-backup-key
---
apiVersion: velero.io/v1
kind: Schedule
metadata:
  name: smartdairy-daily-backup
  namespace: velero
spec:
  schedule: "0 2 * * *"
  template:
    includedNamespaces:
      - smartdairy
    snapshotVolumes: true
    ttl: 720h0m0s
```

### 9.2 S3 Backup Bucket

```yaml
AWSTemplateFormatVersion: '2010-09-09'
Resources:
  VeleroBackupBucket:
    Type: AWS::S3::Bucket
    Properties:
      BucketName: smartdairy-velero-backups
      ObjectLockEnabled: true
      ObjectLockConfiguration:
        ObjectLockEnabled: Enabled
        Rule:
          DefaultRetention:
            Mode: COMPLIANCE
            Days: 35
      BucketEncryption:
        ServerSideEncryptionConfiguration:
          - ServerSideEncryptionByDefault:
              SSEAlgorithm: aws:kms
      VersioningConfiguration:
        Status: Enabled
```

---

## 10. Compliance Monitoring

### 10.1 AWS Config Rules

```yaml
AWSTemplateFormatVersion: '2010-09-09'
Resources:
  S3BucketEncryptionRule:
    Type: AWS::Config::ConfigRule
    Properties:
      ConfigRuleName: s3-bucket-server-side-encryption-enabled
      Source:
        Owner: AWS
        SourceIdentifier: S3_BUCKET_SERVER_SIDE_ENCRYPTION_ENABLED

  EBSEncryptedVolumesRule:
    Type: AWS::Config::ConfigRule
    Properties:
      ConfigRuleName: ebs-encrypted-volumes
      Source:
        Owner: AWS
        SourceIdentifier: EC2_EBS_ENCRYPTION_BY_DEFAULT

  RDSStorageEncryptedRule:
    Type: AWS::Config::ConfigRule
    Properties:
      ConfigRuleName: rds-storage-encrypted
      Source:
        Owner: AWS
        SourceIdentifier: RDS_STORAGE_ENCRYPTED

  RootAccountMFAEnabledRule:
    Type: AWS::Config::ConfigRule
    Properties:
      ConfigRuleName: root-account-mfa-enabled
      Source:
        Owner: AWS
        SourceIdentifier: ROOT_ACCOUNT_MFA_ENABLED
```

### 10.2 Prometheus Alerts

```yaml
apiVersion: monitoring.coreos.com/v1
kind: PrometheusRule
metadata:
  name: compliance-alerts
spec:
  groups:
    - name: compliance
      rules:
        - alert: AWSConfigNonCompliant
          expr: aws_config_compliance_compliant == 0
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "AWS Config Rule Non-Compliant"
        
        - alert: HighSeverityGuardDutyFinding
          expr: aws_guardduty_findings_severity > 7
          labels:
            severity: critical
          annotations:
            summary: "High Severity GuardDuty Finding"
        
        - alert: FalcoCriticalAlert
          expr: increase(falco_events_total{priority=~"CRITICAL"}[5m]) > 0
          labels:
            severity: critical
          annotations:
            summary: "Falco Critical Alert"
```

---

## 11. Appendices

### Appendix A: Security Checklist

```markdown
## Pre-Deployment Checks

### IAM & Access Control
- [ ] IAM password policy enforced (min 16 chars)
- [ ] MFA enabled for all IAM users
- [ ] Root account MFA enabled
- [ ] Service accounts use IRSA
- [ ] Least privilege IAM policies applied
- [ ] SCPs configured

### Network Security
- [ ] VPC Flow Logs enabled
- [ ] Security groups follow least privilege
- [ ] Private subnets for EKS nodes
- [ ] WAF configured
- [ ] DDoS protection enabled

### EKS Security
- [ ] Private cluster endpoint only
- [ ] Secrets encryption enabled
- [ ] Audit logging enabled
- [ ] Pod Security Standards enforced
- [ ] Network Policies configured
- [ ] RBAC with least privilege
- [ ] OPA/Gatekeeper policies deployed

### Container Security
- [ ] Non-root containers enforced
- [ ] Read-only root filesystem
- [ ] Security context with dropped capabilities
- [ ] Distroless or minimal base images
- [ ] Container scanning integrated

### Secrets Management
- [ ] AWS Secrets Manager with auto-rotation
- [ ] External Secrets Operator configured

### Runtime Security
- [ ] Falco deployed
- [ ] Audit logging enabled

### Backup & Recovery
- [ ] Automated backups configured
- [ ] Backup encryption enabled
- [ ] S3 Object Lock configured
```

### Appendix B: CIS Benchmarks Summary

| Benchmark | Compliance Status |
|-----------|------------------|
| CIS AWS Foundations v1.5.0 | 95% |
| CIS Amazon EKS v1.2.0 | 92% |
| CIS Docker v1.5.0 | 98% |

### Appendix C: Incident Response Contacts

| Role | Contact |
|------|---------|
| CISO | ciso@smartdairy.com.bd |
| DevOps Lead | devops-lead@smartdairy.com.bd |
| Security Team | security@smartdairy.com.bd |
| AWS Support | https://console.aws.amazon.com/support |
| BTRC | complaint@btrc.gov.bd |

---

## Document Approval

| Role | Name | Date |
|------|------|------|
| Author | Security Engineer | 2026-01-31 |
| Owner | DevOps Lead | 2026-01-31 |
| Reviewer | CISO | 2026-01-31 |
| Approver | CTO | 2026-01-31 |

---

## References

1. NIST Cybersecurity Framework
2. CIS AWS Foundations Benchmark v1.5.0
3. CIS Amazon EKS Benchmark v1.2.0
4. AWS Well-Architected Security Pillar
5. PCI DSS v3.2.1
6. GDPR Official Text
7. Bangladesh Data Protection Act 2023

---

**Classification**: Confidential  
**Retention Period**: 7 years  
**Next Review Date**: July 31, 2026

---

*End of Document D-014 - Infrastructure Security Hardening*
