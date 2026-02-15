# PCI DSS Compliance Guide

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | F-004 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | Security Lead |
| **Reviewer** | IT Director |
| **Classification** | Confidential |
| **Status** | Draft |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Security Lead | Initial version |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Security Lead | [To be filled] | _____________ | _______ |
| IT Director | [To be filled] | _____________ | _______ |
| CTO | [To be filled] | _____________ | _______ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Scope Definition](#2-scope-definition)
3. [Requirement 1: Firewall Configuration](#3-requirement-1-firewall-configuration)
4. [Requirement 2: System Defaults](#4-requirement-2-system-defaults)
5. [Requirement 3: Stored Cardholder Data](#5-requirement-3-stored-cardholder-data)
6. [Requirement 4: Encryption in Transit](#6-requirement-4-encryption-in-transit)
7. [Requirement 5: Anti-Virus/Malware](#7-requirement-5-anti-virumalware)
8. [Requirement 6: Secure Development](#8-requirement-6-secure-development)
9. [Requirement 7: Access Control](#9-requirement-7-access-control)
10. [Requirement 8: Authentication](#10-requirement-8-authentication)
11. [Requirement 9: Physical Security](#11-requirement-9-physical-security)
12. [Requirement 10: Logging & Monitoring](#12-requirement-10-logging--monitoring)
13. [Requirement 11: Vulnerability Management](#13-requirement-11-vulnerability-management)
14. [Requirement 12: Security Policies](#14-requirement-12-security-policies)
15. [Compliance Validation](#15-compliance-validation)
16. [Third-Party Payment Processors](#16-third-party-payment-processors)
17. [Appendices](#17-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidance for achieving and maintaining Payment Card Industry Data Security Standard (PCI DSS) 4.0 compliance for Smart Dairy Ltd.'s Smart Web Portal System. It serves as the primary reference for all personnel involved in payment processing activities.

### 1.2 PCI DSS 4.0 Overview

PCI DSS is a global security standard designed to protect cardholder data and sensitive authentication data wherever it is processed, stored, or transmitted. Version 4.0, released in March 2022, introduces:

- **Enhanced flexibility** in meeting security objectives
- **Expanded multifactor authentication (MFA)** requirements
- **Updated encryption protocols** and key management
- **Improved password security** requirements
- **Enhanced e-commerce and phishing** security measures
- **Customized approach** as an alternative to defined requirements

### 1.3 Compliance Objectives

Smart Dairy Ltd. is committed to:

1. Protecting cardholder data across all payment channels
2. Minimizing PCI DSS scope through tokenization and third-party processors
3. Achieving SAQ A or SAQ A-EP compliance level
4. Maintaining continuous compliance through ongoing security practices
5. Ensuring secure integration with Bangladesh payment providers

### 1.4 Regulatory Context in Bangladesh

Operating in Bangladesh, Smart Dairy Ltd. must comply with:

- **PCI DSS 4.0** (global card payment security standard)
- **Bangladesh Bank Guidelines** on digital payment security
- **Digital Security Act 2018** (Bangladesh)
- **Data Protection requirements** under Bangladesh ICT Act

### 1.5 Document Audience

| Audience | Purpose |
|----------|---------|
| Developers | Implement secure coding practices |
| System Administrators | Configure secure infrastructure |
| Security Team | Oversee compliance activities |
| Management | Understand compliance obligations |
| Auditors | Validate compliance posture |

---

## 2. Scope Definition

### 2.1 Cardholder Data Environment (CDE)

The Cardholder Data Environment includes all system components that store, process, or transmit cardholder data or sensitive authentication data.

#### 2.1.1 Smart Dairy CDE Components

| Component | Location | Data Type | PCI Scope |
|-----------|----------|-----------|-----------|
| Web Application Servers | AWS Cloud | Tokens only | Limited scope |
| Database Servers | AWS RDS | Tokens, metadata | Limited scope |
| Payment Integration Layer | Internal | Transaction data | Limited scope |
| SSLCommerz Integration | Third-party | Full CHD | Out of scope (redirect) |
| bKash API | Third-party | Tokenized | Out of scope |
| Nagad API | Third-party | Tokenized | Out of scope |
| Rocket API | Third-party | Tokenized | Out of scope |

### 2.2 Out-of-Scope Elements

The following are explicitly excluded from PCI DSS scope due to tokenization approach:

- Primary Account Numbers (PAN) in raw form
- Card Verification Values (CVV/CVC)
- Magnetic stripe data
- PIN data
- Full track data

### 2.3 Self-Assessment Questionnaire (SAQ) Types

#### 2.3.1 SAQ A - Card-Not-Present Merchants

**Applicable if:** All cardholder data functions are fully outsourced to PCI DSS validated third-party service providers.

**Requirements:** 2.3.1, 8.3.6, 9.2.1, 11.2.1, 11.2.2, 11.6.1, 12.1.1, 12.2.1, 12.3.1, 12.4.1, 12.5.1, 12.6.1, 12.6.2, 12.7.1, 12.8.1, 12.8.2, 12.8.3, 12.8.4, 12.8.5, 12.10.1, 12.10.2, 12.10.3, 12.10.4, 12.10.5, 12.10.6, 12.10.7

**Smart Dairy Target:** SAQ A (with redirect/iFrame method)

#### 2.3.2 SAQ A-EP - Partially Outsourced E-Commerce

**Applicable if:** Merchant website affects the security of the payment transaction, but cardholder data is not handled.

**Requirements:** All PCI DSS requirements except 3.2, 3.4, 3.5, 3.6, 3.7, 3.8

**Smart Dairy Alternative:** SAQ A-EP (if using Direct Post method)

### 2.4 Scope Minimization Strategy

```
┌─────────────────────────────────────────────────────────────────┐
│                    PCI DSS SCOPE DIAGRAM                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌──────────────┐                                               │
│   │   Customer   │                                               │
│   └──────┬───────┘                                               │
│          │ HTTPS                                                 │
│          ▼                                                       │
│   ┌──────────────────────────────────────────────────────┐      │
│   │         SMART DAIRY WEB PORTAL                        │      │
│   │  ┌─────────────┐    ┌─────────────┐                  │      │
│   │  │  Web Server │    │    API      │                  │      │
│   │  │   (Token)   │◄──►│   Gateway   │                  │      │
│   │  └─────────────┘    └──────┬──────┘                  │      │
│   │                            │                         │      │
│   │  ┌─────────────┐           │                         │      │
│   │  │  Database   │◄──────────┘                         │      │
│   │  │  (Tokens)   │                                     │      │
│   │  └─────────────┘                                     │      │
│   └──────────────────────────┬───────────────────────────┘      │
│                              │                                   │
│          ┌───────────────────┼───────────────────┐              │
│          │                   │                   │              │
│          ▼                   ▼                   ▼              │
│   ┌──────────┐        ┌──────────┐        ┌──────────┐         │
│   │ SSLCommerz│        │  bKash   │        │  Nagad   │         │
│   │  (Full    │        │(Tokenized│        │(Tokenized│         │
│   │  CHD)     │        │          │        │          │         │
│   └──────────┘        └──────────┘        └──────────┘         │
│                                                                  │
│   ════════════════════════════════════════════════════════     │
│   GREEN: Smart Dairy Systems (Limited Scope)                    │
│   BLUE: Third-Party Processors (Their Scope)                    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.5 Tokenization Approach

| Payment Method | Integration | Token Storage | PCI Scope |
|----------------|-------------|---------------|-----------|
| Credit/Debit Cards | SSLCommerz Redirect | Token only | SAQ A |
| bKash | API + Tokenization | bKash Token | SAQ A |
| Nagad | API + Tokenization | Nagad Token | SAQ A |
| Rocket | API + Tokenization | Rocket Token | SAQ A |

---

## 3. Requirement 1: Firewall Configuration

### 3.1 Requirement Overview

Install and maintain a firewall configuration to protect cardholder data.

### 3.2 Network Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     NETWORK SEGMENTATION                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Internet ──► WAF ──► DMZ ──► Application ──► Database          │
│                       Zone         Zone         Zone            │
│                                                                  │
│  ┌─────────┐   ┌─────────┐   ┌─────────────┐   ┌─────────────┐ │
│  │   WAF   │──►│  ALB    │──►│  App Server │──►│   RDS DB    │ │
│  │ (AWS    │   │ (Public)│   │  (Private)  │   │  (Private)  │ │
│  │ WAFv2)  │   └─────────┘   └─────────────┘   └─────────────┘ │
│  └─────────┘        │              │                 │          │
│                     ▼              ▼                 ▼          │
│                ┌─────────┐   ┌─────────────┐   ┌─────────────┐ │
│                │ Bastion │   │  ECS/EKS    │   │  Redis      │ │
│                │  Host   │   │  Cluster    │   │  Cache      │ │
│                │(Jump Box)│   └─────────────┘   └─────────────┘ │
│                └─────────┘                                      │
│                                                                  │
│  Firewall Rules:                                                 │
│  • Internet: Port 80/443 only                                    │
│  • DMZ to App: Application ports only                            │
│  • App to DB: Database ports only                                │
│  • Deny all others by default                                    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 3.3 Firewall Configuration Standards

#### 3.3.1 AWS Security Group Rules

| Direction | Source | Destination | Port | Protocol | Action | Purpose |
|-----------|--------|-------------|------|----------|--------|---------|
| Inbound | 0.0.0.0/0 | ALB | 80 | TCP | Allow | HTTP redirect |
| Inbound | 0.0.0.0/0 | ALB | 443 | TCP | Allow | HTTPS traffic |
| Inbound | ALB SG | App SG | 8080 | TCP | Allow | App traffic |
| Inbound | App SG | DB SG | 5432 | TCP | Allow | PostgreSQL |
| Inbound | Bastion SG | App SG | 22 | TCP | Allow | SSH admin |
| Inbound | VPN | All | * | * | Allow | Admin access |
| Outbound | App SG | 0.0.0.0/0 | 443 | TCP | Allow | API calls |
| Outbound | All | All | * | * | Deny | Default deny |

#### 3.3.2 Network Access Control Lists (NACLs)

| Rule # | Type | Protocol | Port Range | Source | Allow/Deny |
|--------|------|----------|------------|--------|------------|
| 100 | HTTPS | TCP | 443 | 0.0.0.0/0 | Allow |
| 110 | HTTP | TCP | 80 | 0.0.0.0/0 | Allow |
| 120 | SSH | TCP | 22 | 10.0.0.0/8 | Allow |
| * | All | All | All | 0.0.0.0/0 | Deny |

### 3.4 Router Configuration

```yaml
# Example AWS Network Firewall Rule Group
rules:
  - name: allow_https_outbound
    action: pass
    protocol: tcp
    destination_port: 443
    destination: any
    
  - name: deny_payment_api_direct
    action: drop
    protocol: any
    destination: 
      - sslcommerz.com
      - bkash.com
      - nagad.com
    source: internal_network
    reason: "Force proxy inspection"
    
  - name: allow_dns
    action: pass
    protocol: udp
    destination_port: 53
    
  - name: default_deny
    action: drop
    protocol: any
    priority: 9999
```

### 3.5 Configuration Review Procedures

| Activity | Frequency | Responsible | Evidence |
|----------|-----------|-------------|----------|
| Security Group Review | Monthly | DevOps | AWS Config report |
| NACL Audit | Quarterly | Security | Compliance scan |
| Rule Change Approval | As needed | Change Manager | Ticket ID |
| Firewall Log Review | Weekly | SOC | SIEM dashboard |

### 3.6 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 1.1.1 | Firewall configuration standard | Section 3.3 |
| 1.1.2 | Network diagram | Appendix A |
| 1.2.1 | Traffic restriction to CHD | Security groups |
| 1.3.1 | DMZ implementation | VPC architecture |
| 1.4.1 | Personal firewall on mobile | MDM policy |

---

## 4. Requirement 2: System Defaults

### 4.1 Requirement Overview

Do not use vendor-supplied defaults for system passwords and other security parameters.

### 4.2 System Hardening Standards

#### 4.2.1 Server Configuration Baseline

```yaml
# CIS Benchmark Hardening - Ubuntu 22.04
hardening:
  password_policy:
    min_length: 12
    complexity: required
    history: 12
    max_age_days: 90
    lockout_attempts: 5
    lockout_duration: 30
    
  services:
    disable:
      - telnet
      - ftp
      - nfs
      - smb
    enable:
      - ssh (port 22, key auth only)
      - ntp
      - auditd
      
  filesystem:
    separate_partitions:
      - /var
      - /var/log
      - /tmp
      - /home
    nosuid_on:
      - /tmp
      - /var/tmp
      
  kernel_parameters:
    net.ipv4.ip_forward: 0
    net.ipv4.conf.all.send_redirects: 0
    net.ipv4.conf.all.accept_redirects: 0
    net.ipv4.conf.all.secure_redirects: 0
    net.ipv4.icmp_echo_ignore_broadcasts: 1
    kernel.randomize_va_space: 2
```

#### 4.2.2 Database Hardening (PostgreSQL)

```sql
-- PostgreSQL Security Configuration
-- File: postgresql.conf modifications

-- Connection settings
listen_addresses = 'localhost,10.0.x.x'  -- Restrict to app subnet
port = 5432
max_connections = 200
ssl = on
ssl_cert_file = 'server.crt'
ssl_key_file = 'server.key'
ssl_ca_file = 'ca.crt'

-- Authentication settings
password_encryption = scram-sha-256
auth_delay.milliseconds = 500

-- Logging settings
log_connections = on
log_disconnections = on
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_statement = 'ddl'  -- Log all DDL statements
log_min_duration_statement = 1000  -- Log slow queries

-- Access control
-- pg_hba.conf
# TYPE  DATABASE        USER            ADDRESS                 METHOD
hostssl all             all             10.0.0.0/8              scram-sha-256
hostssl replication     replicator      10.0.1.0/24             scram-sha-256
local   all             postgres                                peer
```

### 4.3 Default Credential Management

| System Type | Default Action | Verification Method |
|-------------|----------------|---------------------|
| AWS EC2 | Change key pair on launch | Instance metadata |
| RDS PostgreSQL | Generate random master password | Secrets Manager |
| Application accounts | No default passwords | Terraform state |
| Third-party APIs | No default keys | Environment variables |
| Container base images | Remove default users | Image scanning |

### 4.4 Configuration Management

```yaml
# Ansible Playbook Snippet - System Hardening
- name: Apply CIS Level 1 Hardening
  hosts: all
  become: yes
  roles:
    - role: cis.ubuntu_22_04
      vars:
        ubuntu2204cis_level_1: true
        ubuntu2204cis_level_2: false
        ubuntu2204cis_rule_1_1_1_1: true   # Disable cramfs
        ubuntu2204cis_rule_1_1_1_2: true   # Disable freevxfs
        ubuntu2204cis_rule_4_2_1_1: true   # Ensure rsyslog installed
        ubuntu2204cis_rule_5_3_1: true     # Configure password complexity
        
- name: Configure SSH Hardening
  hosts: all
  tasks:
    - name: Disable root login
      lineinfile:
        path: /etc/ssh/sshd_config
        regexp: '^PermitRootLogin'
        line: 'PermitRootLogin no'
      notify: restart sshd
      
    - name: Disable password authentication
      lineinfile:
        path: /etc/ssh/sshd_config
        regexp: '^PasswordAuthentication'
        line: 'PasswordAuthentication no'
      notify: restart sshd
      
    - name: Set SSH protocol
      lineinfile:
        path: /etc/ssh/sshd_config
        regexp: '^Protocol'
        line: 'Protocol 2'
      notify: restart sshd
```

### 4.5 Wireless Security

| Control | Implementation | Status |
|---------|----------------|--------|
| Wireless in CDE | Prohibited | N/A - No wireless in scope |
| Guest wireless | Segmented network | Implemented |
| Admin wireless | WPA3-Enterprise | Implemented |
| Wireless scanning | Quarterly audits | Scheduled |

### 4.6 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 2.1.1 | Change default passwords | Password policy doc |
| 2.1.2 | Configuration standards | Section 4.2 |
| 2.2.1 | Single function per server | Architecture doc |
| 2.2.2 | Only necessary services | Hardening scripts |
| 2.2.3 | Security patches | Patch management log |
| 2.2.4 | System hardening | CIS benchmarks |
| 2.3.1 | Encryption for admin access | SSH configuration |

---

## 5. Requirement 3: Stored Cardholder Data

### 5.1 Requirement Overview

Protect stored cardholder data.

### 5.2 Data Classification and Retention

#### 5.2.1 Cardholder Data Elements

| Data Element | Storage | Render Stored | Protection Required |
|--------------|---------|---------------|---------------------|
| Primary Account Number (PAN) | **PROHIBITED** | N/A | N/A |
| Cardholder Name | **PROHIBITED** | N/A | N/A |
| Service Code | **PROHIBITED** | N/A | N/A |
| Expiration Date | **PROHIBITED** | N/A | N/A |
| Full Track Data | **PROHIBITED** | N/A | N/A |
| CVV/CVC/CVV2 | **PROHIBITED** | N/A | N/A |
| PIN/PIN Block | **PROHIBITED** | N/A | N/A |
| Token | ALLOWED | Yes | Encryption at rest |
| Last 4 digits | ALLOWED | Yes | Standard protection |
| Transaction ID | ALLOWED | Yes | Standard protection |

#### 5.2.2 Tokenization Strategy

```
┌─────────────────────────────────────────────────────────────────┐
│                    TOKENIZATION FLOW                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Step 1: Customer enters card data                               │
│  ┌──────────────┐                                               │
│  │  Customer    │───► Card: 4111-1111-1111-1111                  │
│  │  Browser     │      CVV: 123                                   │
│  └──────────────┘      Exp: 12/25                                 │
│           │                                                      │
│           ▼                                                      │
│  Step 2: Data sent directly to SSLCommerz (redirect)             │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │              SSLCommerz Secure Page                     │    │
│  │  • Card data never touches Smart Dairy servers          │    │
│  │  • SSLCommerz validates and tokenizes                   │    │
│  └─────────────────────────────────────────────────────────┘    │
│           │                                                      │
│           ▼                                                      │
│  Step 3: Token returned to Smart Dairy                           │
│  ┌──────────────┐                                               │
│  │ Smart Dairy  │◄─── Token: tok_4x8s9d2f1g3h5j7k9l0m          │
│  │   Server     │      Last4: 1111                               │
│  └──────────────┘      Exp: 12/25                                 │
│           │                                                      │
│           ▼                                                      │
│  Step 4: Store token (never actual card data)                    │
│  ┌──────────────┐                                               │
│  │  Database    │    Tokens encrypted at rest                   │
│  │  (Encrypted) │                                               │
│  └──────────────┘                                               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 5.3 Encryption at Rest

#### 5.3.1 Database Encryption

```yaml
# AWS RDS Encryption Configuration
encryption:
  storage_encryption:
    enabled: true
    kms_key: "arn:aws:kms:region:account:key/rds-key"
    algorithm: AES-256
    
  column_level_encryption:
    sensitive_fields:
      - field: payment_token
        encryption: AES-256-GCM
        key_rotation: 90_days
      - field: customer_phone
        encryption: AES-256-CBC
        key_rotation: 180_days
      - field: transaction_metadata
        encryption: AES-256-GCM
        
  application_encryption:
    library: "cryptography (Python)"
    algorithm: "Fernet (AES-128-CBC)"
    key_management: "AWS KMS"
```

#### 5.3.2 Key Management

| Key Type | Storage | Rotation | Access Control |
|----------|---------|----------|----------------|
| Data Encryption Keys | AWS KMS | 90 days | IAM roles only |
| Key Encryption Keys | AWS KMS HSM | 365 days | Dual control |
| API Keys | AWS Secrets Manager | 180 days | Automatic rotation |
| Database passwords | AWS Secrets Manager | 90 days | Automatic rotation |

### 5.4 Data Retention and Disposal

#### 5.4.1 Retention Policy

| Data Type | Retention Period | Disposal Method | Trigger |
|-----------|------------------|-----------------|---------|
| Payment tokens | 2 years post-transaction | Cryptographic erasure | Scheduled job |
| Transaction logs | 7 years | Secure deletion | Annual purge |
| Audit logs | 1 year | Archive then delete | Quarterly |
| Temporary payment data | Immediate | Memory wipe | Post-transaction |
| Failed transaction logs | 30 days | Secure deletion | Daily |

#### 5.4.2 Secure Disposal Procedures

```python
# Secure Data Disposal Implementation
import os
import secrets
from cryptography.fernet import Fernet

class SecureDataDisposal:
    """PCI DSS compliant data disposal"""
    
    @staticmethod
    def secure_delete(file_path, passes=3):
        """Overwrite file before deletion"""
        if not os.path.exists(file_path):
            return
            
        file_size = os.path.getsize(file_path)
        
        with open(file_path, 'ba+', buffering=0) as f:
            for pass_num in range(passes):
                # Overwrite with random data
                f.seek(0)
                f.write(secrets.token_bytes(file_size))
                f.flush()
                os.fsync(f.fileno())
                
        os.remove(file_path)
    
    @staticmethod
    def crypto_shred(key_id):
        """Delete encryption key to render data unrecoverable"""
        # Delete KMS key (7-day waiting period)
        kms_client.schedule_key_deletion(
            KeyId=key_id,
            PendingWindowInDays=7
        )
    
    @staticmethod
    def clear_memory(data_var):
        """Overwrite sensitive variable in memory"""
        if isinstance(data_var, bytearray):
            for i in range(len(data_var)):
                data_var[i] = 0
        elif isinstance(data_var, str):
            # Strings are immutable, create new object
            data_var = '\x00' * len(data_var)
```

### 5.5 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 3.1.1 | Data retention policy | Section 5.4.1 |
| 3.2.1 | No full track data | Tokenization flow |
| 3.2.2 | No CVV storage | Security policy |
| 3.3.1 | Mask PAN when displayed | UI guidelines |
| 3.4.1 | Render PAN unreadable | Tokenization |
| 3.5.1 | Key management | KMS configuration |
| 3.6.1 | Key generation | HSM procedures |
| 3.7.1 | Key custody | Key custodian policy |

---

## 6. Requirement 4: Encryption in Transit

### 6.1 Requirement Overview

Encrypt transmission of cardholder data across open, public networks.

### 6.2 TLS Implementation

#### 6.2.1 TLS Configuration Standards

```nginx
# Nginx TLS Configuration - PCI DSS Compliant
server {
    listen 443 ssl http2;
    server_name smartdairy.com.bd;
    
    # Certificate configuration
    ssl_certificate /etc/ssl/certs/smartdairy.crt;
    ssl_certificate_key /etc/ssl/private/smartdairy.key;
    
    # Protocol versions - TLS 1.2 minimum (PCI DSS 4.0 requirement)
    ssl_protocols TLSv1.2 TLSv1.3;
    
    # Cipher suites - Strong cryptography only
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
    
    ssl_prefer_server_ciphers off;
    
    # Session configuration
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;
    
    # OCSP Stapling
    ssl_stapling on;
    ssl_stapling_verify on;
    ssl_trusted_certificate /etc/ssl/certs/ca-chain.crt;
    resolver 8.8.8.8 8.8.4.4 valid=300s;
    resolver_timeout 5s;
    
    # Security headers
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always;
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header Referrer-Policy strict-origin-when-cross-origin;
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name smartdairy.com.bd;
    return 301 https://$server_name$request_uri;
}
```

#### 6.2.2 TLS Version Requirements

| Protocol | Status | PCI DSS 4.0 Compliance |
|----------|--------|------------------------|
| SSLv2 | PROHIBITED | Not allowed |
| SSLv3 | PROHIBITED | Not allowed |
| TLS 1.0 | PROHIBITED | Not allowed |
| TLS 1.1 | PROHIBITED | Not allowed |
| TLS 1.2 | REQUIRED | Minimum acceptable |
| TLS 1.3 | RECOMMENDED | Preferred |

### 6.3 Certificate Management

| Aspect | Implementation | Details |
|--------|----------------|---------|
| Certificate Authority | DigiCert / Let's Encrypt Enterprise | Trusted CA |
| Key Length | RSA 2048-bit minimum / ECDSA P-256 | Strong keys |
| Validity Period | 397 days maximum | Industry standard |
| Certificate Type | Domain Validated (DV) minimum | Organization (OV) preferred |
| Wildcard Usage | Not for payment endpoints | Specific domains only |
| Renewal | Automated 30 days before expiry | Cert-manager in Kubernetes |

### 6.4 API Security

```python
# Python API TLS Configuration
import ssl
import certifi
import requests
from urllib.parse import urlparse

class SecureHTTPClient:
    """PCI DSS compliant HTTP client"""
    
    def __init__(self):
        # Create secure SSL context
        self.ssl_context = ssl.create_default_context(
            purpose=ssl.Purpose.SERVER_AUTH,
            cafile=certifi.where()
        )
        
        # Minimum TLS 1.2
        self.ssl_context.minimum_version = ssl.TLSVersion.TLSv1_2
        
        # Disable weak ciphers
        self.ssl_context.set_ciphers('ECDHE+AESGCM:ECDHE+CHACHA20:DHE+AESGCM:DHE+CHACHA20:!aNULL:!MD5:!DSS')
        
    def get_session(self):
        """Create requests session with secure defaults"""
        session = requests.Session()
        adapter = requests.adapters.HTTPAdapter(
            max_retries=3,
            pool_connections=10,
            pool_maxsize=10
        )
        session.mount('https://', adapter)
        return session
    
    def validate_endpoint(self, url):
        """Validate payment endpoint security"""
        parsed = urlparse(url)
        
        if parsed.scheme != 'https':
            raise ValueError("Payment endpoints must use HTTPS")
            
        # Check for known payment processors
        allowed_domains = [
            'sslcommerz.com',
            'sandbox.sslcommerz.com',
            'bkash.com',
            'nagad.com.bd',
            'rocket.com.bd'
        ]
        
        if not any(domain in parsed.netloc for domain in allowed_domains):
            raise ValueError(f"Unknown payment endpoint: {url}")
            
        return True
```

### 6.5 Wireless Transmission

| Control | Implementation | Status |
|---------|----------------|--------|
| Wireless in CDE | Not implemented | N/A |
| Wireless encryption | WPA3-Enterprise for admin | Implemented |
| Guest wireless | No CHD transmission | Policy enforced |
| Bluetooth | Disabled on CDE systems | Hardened |

### 6.6 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 4.1.1 | TLS for all CHD transmission | Section 6.2 |
| 4.2.1 | Never send CHD unencrypted | Tokenization policy |
| 4.2.2 | End-user messaging | Security awareness |

---

## 7. Requirement 5: Anti-Virus/Malware

### 7.1 Requirement Overview

Protect all systems against malware and regularly update anti-virus software or programs.

### 7.2 Anti-Malware Strategy

#### 7.2.1 Defense-in-Depth Approach

| Layer | Solution | Coverage | Update Frequency |
|-------|----------|----------|------------------|
| Endpoint | CrowdStrike Falcon | All servers | Real-time |
| Network | AWS GuardDuty | VPC traffic | Continuous |
| Email | Microsoft Defender | Email gateway | Hourly |
| Container | Prisma Cloud | ECR images | Per scan |
| DNS | Cloudflare Gateway | DNS filtering | Real-time |

#### 7.2.2 Malware Detection Configuration

```yaml
# CrowdStrike Falcon Configuration
falcon:
  prevention_policies:
    - name: "Linux Production Servers"
      platform: LINUX
      settings:
        detect_on_write: true
        quarantine_on_write: true
        cloud_machine_learning: "AGGRESSIVE"
        sensor_machine_learning: "AGGRESSIVE"
        
    - name: "Container Workloads"
      platform: CONTAINER
      settings:
        detect_on_write: true
        quarantine_on_write: false  # Immutable containers
        container_image_scanning: true
        runtime_protection: true
        
  detection_policies:
    - name: "PCI DSS Enhanced"
      settings:
        heuristic_level: "HIGH"
        behavioral_analysis: true
        memory_exploit_detection: true
        script_based_attack_detection: true
        
  response_policies:
    - name: "Critical Systems"
      settings:
        kill_process: true
        quarantine_file: true
        notify_soc: true
        create_ticket: true
```

### 7.3 System Coverage

| System Type | Anti-Malware | Status | Exception Justification |
|-------------|--------------|--------|------------------------|
| Application Servers | CrowdStrike Falcon | Required | N/A |
| Database Servers | CrowdStrike Falcon | Required | N/A |
| Bastion Hosts | CrowdStrike Falcon | Required | N/A |
| CI/CD Runners | Prisma Cloud + Falcon | Required | N/A |
| Container Hosts | Prisma Cloud | Required | N/A |
| Network Appliances | Not applicable | N/A | No general-purpose OS |
| AWS Managed Services | AWS GuardDuty | Required | Shared responsibility |

### 7.4 Update and Maintenance

| Activity | Frequency | Automation | Verification |
|----------|-----------|------------|--------------|
| Signature updates | Real-time | Automatic | Falcon dashboard |
| Engine updates | Weekly | Automatic | Change log review |
| Policy review | Monthly | Manual | Security review |
| Full system scan | Weekly | Scheduled | Scan reports |
| Threat hunting | Daily | Semi-automated | SOC procedures |

### 7.5 Malware Response Procedures

```
┌─────────────────────────────────────────────────────────────────┐
│                 MALWARE INCIDENT RESPONSE                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. DETECTION                                                    │
│     ├── Falcon alert triggered                                 │
│     ├── SOC receives notification                               │
│     └── Ticket auto-created in Jira                            │
│                                                                  │
│  2. CONTAINMENT                                                  │
│     ├── Isolate affected system (automated)                    │
│     ├── Snapshot for forensics                                 │
│     └── Block file hash across environment                      │
│                                                                  │
│  3. ANALYSIS                                                     │
│     ├── Determine infection vector                             │
│     ├── Assess scope of compromise                             │
│     └── Identify indicators of compromise (IoCs)               │
│                                                                  │
│  4. ERADICATION                                                  │
│     ├── Terminate compromised instance                         │
│     ├── Rotate all credentials                                 │
│     └── Patch vulnerability                                    │
│                                                                  │
│  5. RECOVERY                                                     │
│     ├── Deploy clean instance from AMI                         │
│     ├── Restore data from verified backup                      │
│     └── Return to production                                   │
│                                                                  │
│  6. POST-INCIDENT                                                │
│     ├── Document lessons learned                               │
│     ├── Update detection rules                                 │
│     └── Notify stakeholders if CHD involved                    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 7.6 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 5.1.1 | Anti-malware deployed | Coverage matrix |
| 5.2.1 | Malware signature updates | Falcon console |
| 5.2.2 | Automatic updates | Policy settings |
| 5.3.1 | System scanning | Scan schedules |
| 5.4.1 | Anti-phishing protection | Email security |

---

## 8. Requirement 6: Secure Development

### 8.1 Requirement Overview

Develop and maintain secure systems and applications.

### 8.2 Secure Software Development Lifecycle (SSDLC)

```
┌─────────────────────────────────────────────────────────────────┐
│                    SSDLC PROCESS FLOW                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐      │
│  │REQUIRE  │───►│  DESIGN │───►│ DEVELOP │───►│  TEST   │      │
│  │  MENTS  │    │         │    │         │    │         │      │
│  └────┬────┘    └────┬────┘    └────┬────┘    └────┬────┘      │
│       │              │              │              │            │
│       ▼              ▼              ▼              ▼            │
│  ┌───────────────────────────────────────────────────────┐     │
│  │                   SECURITY ACTIVITIES                  │     │
│  │  • Threat modeling    • Secure code review            │     │
│  │  • Security reqs      • SAST/DAST scanning            │     │
│  │  • Architecture       • Penetration testing           │     │
│  └───────────────────────────────────────────────────────┘     │
│       │              │              │              │            │
│       └──────────────┴──────────────┴──────────────┘            │
│                          │                                       │
│                          ▼                                       │
│                   ┌─────────────┐                                │
│                   │   DEPLOY    │◄── Security gate              │
│                   │   MENT      │     (Must pass all checks)    │
│                   └──────┬──────┘                                │
│                          │                                       │
│                          ▼                                       │
│                   ┌─────────────┐                                │
│                   │  MAINTAIN   │                                │
│                   │  (Runtime)  │                                │
│                   └─────────────┘                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 8.3 Security in Requirements

| Activity | Output | Responsible |
|----------|--------|-------------|
| Security requirement analysis | Security user stories | Security Lead |
| Threat modeling (STRIDE) | Threat model document | Security Architect |
| Privacy impact assessment | PIA report | DPO |
| Compliance mapping | Compliance checklist | Compliance Officer |

### 8.4 Secure Coding Standards

#### 8.4.1 OWASP Top 10 Mitigation

| Vulnerability | Prevention | Verification |
|---------------|------------|--------------|
| Injection (A01) | Parameterized queries | SAST scan |
| Broken Auth (A02) | MFA, session management | Pen test |
| Sensitive Data (A03) | Encryption, tokenization | Code review |
| XXE (A04) | Disable XML external entities | SAST scan |
| Access Control (A05) | RBAC, authorization checks | DAST scan |
| Security Misconfig (A06) | Hardening baselines | Config scan |
| XSS (A07) | Output encoding, CSP | DAST scan |
| Deserialization (A08) | Input validation | SAST scan |
| Vulnerable Components (A09) | Dependency scanning | SCA scan |
| Logging (A10) | Comprehensive audit logging | Log review |

#### 8.4.2 Payment-Specific Security

```python
# Secure Payment Processing Example
from cryptography.fernet import Fernet
import re
import logging

class SecurePaymentProcessor:
    """PCI DSS compliant payment processing"""
    
    # NEVER log sensitive data
    SENSITIVE_PATTERNS = [
        r'\d{4}[\s-]?\d{4}[\s-]?\d{4}[\s-]?\d{4}',  # PAN
        r'\b\d{3,4}\b',  # CVV
    ]
    
    @staticmethod
    def sanitize_log_message(message: str) -> str:
        """Remove sensitive data from log messages"""
        sanitized = message
        for pattern in SecurePaymentProcessor.SENSITIVE_PATTERNS:
            sanitized = re.sub(pattern, '[REDACTED]', sanitized)
        return sanitized
    
    def process_payment(self, payment_request):
        """Process payment with security controls"""
        # 1. Validate input
        if not self._validate_payment_request(payment_request):
            raise ValueError("Invalid payment request")
        
        # 2. Log sanitized data only
        safe_log = self.sanitize_log_message(str(payment_request))
        logging.info(f"Processing payment: {safe_log}")
        
        # 3. Use TLS for all external calls
        response = self._call_payment_gateway(
            payment_request,
            verify_ssl=True,
            min_tls_version='1.2'
        )
        
        # 4. Handle response securely
        if response.success:
            # Store token, never PAN
            self._store_payment_token(response.token)
            
        return response
```

### 8.5 Code Review Process

| Review Type | Frequency | Tools | Acceptance Criteria |
|-------------|-----------|-------|---------------------|
| Peer review | Every PR | GitHub PR | 2 approvals |
| Security review | High-risk changes | Manual | Security sign-off |
| Automated SAST | Every commit | SonarQube | No critical issues |
| Automated DAST | Daily | OWASP ZAP | No high findings |
| Dependency scan | Every build | Snyk | No critical CVEs |
| Container scan | Every build | Trivy | No critical CVEs |

### 8.6 Change Management

```yaml
# Change Management Workflow
change_management:
  categories:
    standard:
      description: "Pre-approved, low-risk changes"
      examples: ["Patch updates", "Config changes"]
      approval: Automated
      
    normal:
      description: "Standard changes requiring review"
      examples: ["Feature deployment", "Database migration"]
      approval: CAB (Change Advisory Board)
      
    emergency:
      description: "Critical security fixes"
      examples: ["Zero-day patch", "Security incident response"]
      approval: Emergency CAB
      post_review: Required
      
  security_gates:
    - name: "SAST Scan"
      required: true
      block_on: [critical, high]
      
    - name: "Dependency Check"
      required: true
      block_on: [critical]
      
    - name: "DAST Scan"
      required: true
      block_on: [critical, high]
      
    - name: "Container Scan"
      required: true
      block_on: [critical]
      
    - name: "Security Review"
      required: true
      for: [payment_changes, auth_changes]
```

### 8.7 Web Application Firewall (WAF)

#### 8.7.1 AWS WAFv2 Configuration

```json
{
  "Name": "SmartDairy-PCI-WAF",
  "Rules": [
    {
      "Name": "AWSManagedRulesCommonRuleSet",
      "Priority": 1,
      "Statement": {
        "ManagedRuleGroupStatement": {
          "VendorName": "AWS",
          "Name": "AWSManagedRulesCommonRuleSet"
        }
      },
      "OverrideAction": { "None": {} },
      "VisibilityConfig": {
        "SampledRequestsEnabled": true,
        "CloudWatchMetricsEnabled": true,
        "MetricName": "AWSManagedRulesCommonRuleSetMetric"
      }
    },
    {
      "Name": "AWSManagedRulesKnownBadInputsRuleSet",
      "Priority": 2,
      "Statement": {
        "ManagedRuleGroupStatement": {
          "VendorName": "AWS",
          "Name": "AWSManagedRulesKnownBadInputsRuleSet"
        }
      }
    },
    {
      "Name": "AWSManagedRulesSQLiRuleSet",
      "Priority": 3,
      "Statement": {
        "ManagedRuleGroupStatement": {
          "VendorName": "AWS",
          "Name": "AWSManagedRulesSQLiRuleSet"
        }
      }
    },
    {
      "Name": "RateLimitRule",
      "Priority": 4,
      "Statement": {
        "RateBasedStatement": {
          "Limit": 2000,
          "AggregateKeyType": "IP"
        }
      },
      "Action": { "Block": {} }
    },
    {
      "Name": "PaymentEndpointProtection",
      "Priority": 5,
      "Statement": {
        "AndStatement": {
          "Statements": [
            {
              "ByteMatchStatement": {
                "SearchString": "/api/payments",
                "FieldToMatch": { "UriPath": {} },
                "PositionalConstraint": "CONTAINS"
              }
            },
            {
              "NotStatement": {
                "Statement": {
                  "GeoMatchStatement": {
                    "CountryCodes": ["BD"]
                  }
                }
              }
            }
          ]
        }
      },
      "Action": { "Block": {} }
    }
  ]
}
```

### 8.8 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 6.1.1 | Vulnerability management | Security roadmap |
| 6.2.1 | Security patches | Patch management |
| 6.3.1 | SSDLC | Section 8.2 |
| 6.3.2 | Software security patches | Change log |
| 6.4.1 | Production change control | Section 8.6 |
| 6.4.2 | Separation of duties | Access matrix |
| 6.4.3 | Production data protection | Data masking |
| 6.4.4 | Live PAN testing | Tokenized test data |
| 6.4.5 | Change documentation | Change tickets |
| 6.5.1 | Address common vulnerabilities | Section 8.4 |
| 6.5.2 | Software security patches | Patch schedule |

---

## 9. Requirement 7: Access Control

### 9.1 Requirement Overview

Restrict access to cardholder data by business need-to-know.

### 9.2 Access Control Principles

#### 9.2.1 Need-to-Know Matrix

| Role | Customer Data | Payment Tokens | Transaction Logs | System Config |
|------|--------------|----------------|------------------|---------------|
| Customer Service | Own customers only | No access | Own customers only | No access |
| Finance | No access | No access | Aggregate reports only | No access |
| Developer | No access | No access | Anonymized data | Staging only |
| DevOps | No access | No access | System metrics | Production |
| Security | Audit access | Audit access | Full access | Full access |
| DBA | No access | Backup/restore only | Maintenance only | Database only |

#### 9.2.2 Role-Based Access Control (RBAC)

```yaml
# RBAC Configuration
roles:
  customer_service_representative:
    permissions:
      - customer:read:own
      - order:read:own
      - support_ticket:write
    restrictions:
      - no_payment_data
      - no_admin_functions
      - session_timeout: 30min
      
  finance_analyst:
    permissions:
      - reports:read:aggregate
      - invoice:read
      - reconciliation:write
    restrictions:
      - no_individual_transactions
      - no_customer_pii
      
  developer:
    permissions:
      - code:write
      - staging:deploy
      - logs:read:anonymized
    restrictions:
      - no_production_access
      - no_customer_data
      
  devops_engineer:
    permissions:
      - infrastructure:manage
      - production:deploy
      - monitoring:read
    restrictions:
      - no_direct_database_query
      - all_access_logged
      - break_glass_required_for_sensitive
      
  security_admin:
    permissions:
      - audit:read
      - policy:write
      - incident:manage
    restrictions:
      - dual_control_required
      - all_actions_logged
```

### 9.3 Access Request Process

```
┌─────────────────────────────────────────────────────────────────┐
│                 ACCESS REQUEST WORKFLOW                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. REQUEST                                                      │
│     ├── Employee submits access request form                    │
│     ├── Manager provides business justification                 │
│     └── Data owner approval (for sensitive access)              │
│                                                                  │
│  2. REVIEW                                                       │
│     ├── Security reviews for compliance with least privilege   │
│     ├── HR verifies employment status                           │
│     └── Background check if required                            │
│                                                                  │
│  3. APPROVAL                                                     │
│     ├── Immediate supervisor approval                           │
│     ├── Data owner approval                                     │
│     └── Security approval for elevated access                   │
│                                                                  │
│  4. PROVISIONING                                                 │
│     ├── Automated provisioning for standard roles               │
│     ├── Manual provisioning for custom access                   │
│     └── Access verification by requester                        │
│                                                                  │
│  5. DOCUMENTATION                                                │
│     ├── Ticket created with all approvals                       │
│     ├── Access matrix updated                                   │
│     └── Review date scheduled                                   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 9.4 Access Review Procedures

| Review Type | Frequency | Scope | Responsible |
|-------------|-----------|-------|-------------|
| Quarterly access review | Every 90 days | All system access | Data Owners |
| Termination review | Daily | Departed employees | HR + IT |
| Transfer review | Within 24 hours | Role changes | Managers |
| Privileged access review | Monthly | Admin accounts | Security |
| Service account review | Quarterly | Automated accounts | DevOps |

### 9.5 AWS IAM Configuration

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "AllowLimitedS3Access",
      "Effect": "Allow",
      "Action": [
        "s3:GetObject",
        "s3:PutObject"
      ],
      "Resource": "arn:aws:s3:::smart-dairy-app-bucket/*",
      "Condition": {
        "StringEquals": {
          "s3:x-amz-server-side-encryption": "AES256"
        }
      }
    },
    {
      "Sid": "DenyUnencryptedConnections",
      "Effect": "Deny",
      "Action": "*",
      "Resource": "*",
      "Condition": {
        "Bool": {
          "aws:SecureTransport": "false"
        }
      }
    },
    {
      "Sid": "RequireMFAForSensitiveOperations",
      "Effect": "Deny",
      "Action": [
        "iam:CreateUser",
        "iam:DeleteUser",
        "iam:AttachUserPolicy"
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

### 9.6 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 7.1.1 | Access restriction policy | Section 9.2 |
| 7.2.1 | Need-to-know access | Access matrix |
| 7.2.2 | Access authorization | Approval workflow |
| 7.2.3 | System access control | IAM policies |
| 7.2.4 | Access removal | Termination procedure |
| 7.2.5 | Access review | Review schedule |

---

## 10. Requirement 8: Authentication

### 10.1 Requirement Overview

Identify users and authenticate access to system components.

### 10.2 Authentication Standards

#### 10.2.1 Password Requirements

| Requirement | Specification | PCI DSS 4.0 Reference |
|-------------|---------------|----------------------|
| Minimum length | 12 characters | 8.3.6 |
| Complexity | Upper, lower, number, special | 8.3.6 |
| History | Last 4 passwords cannot be reused | 8.3.7 |
| Maximum age | 90 days | 8.3.9 |
| Lockout threshold | 5 failed attempts | 8.3.4 |
| Lockout duration | 30 minutes | 8.3.4 |
| First login change | Required | 8.3.5 |
| Password hints | Prohibited | 8.3.2 |

#### 10.2.2 Multi-Factor Authentication (MFA)

| Scenario | MFA Requirement | Implementation |
|----------|-----------------|----------------|
| Admin access to CDE | REQUIRED | Hardware token/YubiKey |
| Remote access to network | REQUIRED | TOTP (Google Authenticator) |
| Access to cardholder data | REQUIRED | Push notification (Duo) |
| Standard user access | RECOMMENDED | SMS or TOTP |
| Service accounts | N/A | Certificate-based |

### 10.3 MFA Implementation

```python
# MFA Verification Implementation
import pyotp
import qrcode
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2

class MFAService:
    """Multi-factor authentication service"""
    
    def __init__(self):
        self.issuer = "SmartDairy"
        
    def enroll_user(self, user_id: str, method: str = "totp"):
        """Enroll user in MFA"""
        if method == "totp":
            # Generate TOTP secret
            secret = pyotp.random_base32()
            
            # Create provisioning URI
            totp = pyotp.TOTP(secret)
            uri = totp.provisioning_uri(
                name=user_id,
                issuer_name=self.issuer
            )
            
            # Generate QR code
            qr = qrcode.make(uri)
            
            # Store encrypted secret
            self._store_secret(user_id, secret)
            
            return {
                'secret': secret,  # Show once for backup
                'qr_code': qr,
                'backup_codes': self._generate_backup_codes(user_id)
            }
            
        elif method == "hardware":
            # YubiKey/WebAuthn enrollment
            return self._enroll_webauthn(user_id)
    
    def verify_totp(self, user_id: str, token: str) -> bool:
        """Verify TOTP token"""
        secret = self._get_secret(user_id)
        if not secret:
            return False
            
        totp = pyotp.TOTP(secret)
        
        # Allow 1 step before/after for time drift
        return totp.verify(token, valid_window=1)
    
    def _generate_backup_codes(self, user_id: str, count: int = 10) -> list:
        """Generate single-use backup codes"""
        import secrets
        codes = []
        for _ in range(count):
            code = ''.join(secrets.choice('0123456789') for _ in range(8))
            codes.append(code)
            # Store hashed code
            self._store_backup_code(user_id, code)
        return codes
```

### 10.4 Session Management

| Control | Implementation | Configuration |
|---------|----------------|---------------|
| Session timeout | 15 minutes idle | Application setting |
| Maximum session | 8 hours | Application setting |
| Concurrent sessions | 1 per user | Session management |
| Session ID entropy | 128 bits minimum | Framework default |
| Secure cookie flag | Required | Set-Cookie header |
| HttpOnly flag | Required | Set-Cookie header |
| SameSite | Strict | Set-Cookie header |

### 10.5 Privileged Access Management

```yaml
# Privileged Access Management Configuration
pam:
  break_glass_accounts:
    - name: "emergency_admin"
      type: "shared"
      password_sharding: true
      activation:
        require_approval: true
        approvers: ["cto", "security_lead"]
        time_limit: 4_hours
      logging:
        session_recording: true
        command_logging: true
        real_time_alerting: true
        
  just_in_time_access:
    enabled: true
    max_duration: 2_hours
    require_approval_for:
      - production_database
      - payment_gateway_config
      - encryption_key_access
      
  session_recording:
    ssh: true
    rdp: true
    database: true
    retention: 1_year
    
  credential_vault:
    rotation:
      automatic: true
      frequency: 90_days
      on_departure: immediate
```

### 10.6 Authentication for Service Accounts

| Service Account Type | Authentication Method | Rotation |
|---------------------|----------------------|----------|
| Database connections | IAM database auth | Automatic |
| API-to-API | mTLS certificates | 90 days |
| AWS services | IAM roles | N/A (temporary) |
| Container registry | ECR IAM policy | N/A |
| Third-party APIs | API keys (Secrets Manager) | 180 days |

### 10.7 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 8.1.1 | User identification policy | Auth policy |
| 8.2.1 | Strong authentication | Section 10.2 |
| 8.3.1 | MFA for remote access | MFA configuration |
| 8.3.2 | MFA for admin access | MFA configuration |
| 8.3.3 | MFA for CDE access | MFA configuration |
| 8.3.4 | Account lockout | Password policy |
| 8.3.5 | Unique password first login | Onboarding process |
| 8.3.6 | Password complexity | Password policy |
| 8.3.7 | Password history | AD/LDAP config |
| 8.3.8 | Password storage | Hashing algorithm |
| 8.3.9 | Password expiration | Password policy |

---

## 11. Requirement 9: Physical Security

### 11.1 Requirement Overview

Restrict physical access to cardholder data.

### 11.2 Physical Access Controls

#### 11.2.1 Smart Dairy Office Locations

| Location | Address | CDE Presence | Physical Controls |
|----------|---------|--------------|-------------------|
| Headquarters | Dhaka, Bangladesh | No CDE | Standard office security |
| Development Center | Dhaka, Bangladesh | No CDE | Enhanced access control |
| AWS Cloud | Multiple regions | Full CDE | AWS physical security |

#### 11.2.2 Physical Security Layers

```
┌─────────────────────────────────────────────────────────────────┐
│                PHYSICAL SECURITY ZONES                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │  PUBLIC ZONE                                              │  │
│  │  • Reception area                                         │  │
│  │  • Visitor log required                                   │  │
│  │  • No CDE access                                          │  │
│  └───────────────────────────────────────────────────────────┘  │
│                          │                                       │
│                          ▼                                       │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │  CONTROLLED ZONE (Employee Badge Required)                │  │
│  │  • Office areas                                           │  │
│  │  • Meeting rooms                                          │  │
│  │  • Break rooms                                            │  │
│  └───────────────────────────────────────────────────────────┘  │
│                          │                                       │
│                          ▼                                       │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │  RESTRICTED ZONE (Authorized Personnel Only)              │  │
│  │  • Server room (if any on-premise)                        │  │
│  │  • Network equipment                                      │  │
│  │  • No CDE - Cloud hosted only                             │  │
│  └───────────────────────────────────────────────────────────┘  │
│                                                                  │
│  Note: All CDE systems hosted in AWS data centers               │
│  AWS Physical Security: https://aws.amazon.com/compliance/      │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 11.3 AWS Data Center Security (Shared Responsibility)

| Security Layer | AWS Responsibility | Smart Dairy Responsibility |
|----------------|-------------------|---------------------------|
| Facility perimeter | Physical access control | N/A |
| Building access | 24/7 security staff | N/A |
| Equipment disposal | Secure decommissioning | Data deletion |
| Hardware maintenance | Physical hardware | Instance management |
| Environmental controls | Power, cooling, fire | N/A |
| Network infrastructure | Physical network | Logical network |

### 11.4 Media Handling

| Media Type | Storage | Disposal | Retention |
|------------|---------|----------|-----------|
| Backup tapes | AWS S3 Glacier | Cryptographic deletion | 7 years |
| USB drives | Prohibited | N/A | N/A |
| DVDs/CDs | Prohibited | N/A | N/A |
| Paper documents | Locked cabinet | Cross-cut shredder | Per policy |
| Hard drives | N/A (cloud only) | AWS managed | N/A |
| Mobile devices | Encrypted | Remote wipe | Per policy |

### 11.5 Visitor Procedures

| Procedure | Implementation | Documentation |
|-----------|----------------|---------------|
| Visitor log | Digital sign-in system | Visitor management system |
| Escort required | Yes, at all times | Visitor badge |
| Photo ID verification | Required | Copy retained |
| NDA for sensitive areas | Required | Signed document |
| Visitor badge return | Required at exit | Checkout log |

### 11.6 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 9.1.1 | Physical access controls | AWS SOC 2 |
| 9.1.2 | Facility entry controls | Access log |
| 9.2.1 | Authorized physical access | Access control list |
| 9.3.1 | Physical security for media | Media handling policy |
| 9.4.1 | Media storage | Storage procedures |
| 9.5.1 | Media distribution | Encryption policy |
| 9.6.1 | Media disposal | Disposal procedures |
| 9.7.1 | Media inventory | Asset inventory |
| 9.8.1 | Device destruction | AWS procedures |
| 9.9.1 | Point of interaction devices | N/A - No POS |

---

## 12. Requirement 10: Logging & Monitoring

### 12.1 Requirement Overview

Log and monitor all access to network resources and cardholder data.

### 12.2 Audit Logging Requirements

#### 12.2.1 Required Audit Events

| Event Type | Systems to Log | Log Content |
|------------|----------------|-------------|
| All individual user access | All systems | User ID, timestamp, action, result |
| All administrator access | All admin systems | Elevated privilege usage |
| All access to CHD | CDE systems | Token access, transaction IDs |
| Invalid access attempts | All systems | Failed login, authorization failures |
| Changes to CHD | CDE systems | Data modifications |
| Privilege changes | All systems | Permission modifications |
| Initialization/stop | Critical systems | Service start/stop |
| System alerts | Security systems | IDS/IPS alerts |
| Creation/deletion of system objects | All systems | File, user, group changes |

#### 12.2.2 Log Format Standard

```json
{
  "timestamp": "2026-01-31T10:30:00Z",
  "log_version": "1.0",
  "event": {
    "type": "authentication",
    "action": "login_attempt",
    "result": "success"
  },
  "actor": {
    "type": "user",
    "id": "john.doe",
    "ip_address": "203.0.113.45",
    "user_agent": "Mozilla/5.0..."
  },
  "target": {
    "system": "smart-dairy-portal",
    "resource": "/api/admin/dashboard"
  },
  "context": {
    "session_id": "sess_abc123xyz",
    "mfa_used": true,
    "mfa_method": "totp"
  },
  "compliance": {
    "pci_dss": true,
    "data_classification": "restricted"
  }
}
```

### 12.3 Centralized Logging Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│               LOGGING INFRASTRUCTURE                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ Application  │  │   Database   │  │   Network    │          │
│  │    Logs      │  │    Logs      │  │    Logs      │          │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘          │
│         │                  │                  │                 │
│         └──────────────────┼──────────────────┘                 │
│                            │                                     │
│                            ▼                                     │
│                   ┌─────────────────┐                           │
│                   │  AWS CloudWatch │                           │
│                   │     Logs        │                           │
│                   └────────┬────────┘                           │
│                            │                                     │
│                            ▼                                     │
│                   ┌─────────────────┐                           │
│                   │   Amazon S3     │                           │
│                   │ (Long-term Arch)│                           │
│                   └────────┬────────┘                           │
│                            │                                     │
│                            ▼                                     │
│                   ┌─────────────────┐                           │
│                   │  AWS OpenSearch │                           │
│                   │   (Analytics)   │                           │
│                   └────────┬────────┘                           │
│                            │                                     │
│                            ▼                                     │
│                   ┌─────────────────┐                           │
│                   │   SIEM/Splunk   │                           │
│                   │  (Monitoring)   │                           │
│                   └─────────────────┘                           │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 12.4 Log Retention

| Log Type | Retention Period | Storage Location | Encryption |
|----------|------------------|------------------|------------|
| Security events | 1 year hot, 7 years archive | CloudWatch + S3 | AES-256 |
| Authentication logs | 1 year hot, 7 years archive | CloudWatch + S3 | AES-256 |
| Application logs | 90 days hot, 1 year archive | CloudWatch + S3 | AES-256 |
| Access logs | 1 year hot, 3 years archive | CloudWatch + S3 | AES-256 |
| Audit trails | 1 year hot, 7 years archive | CloudWatch + S3 Glacier | AES-256 |

### 12.5 Security Monitoring

#### 12.5.1 Detection Rules

```yaml
# SIEM Detection Rules
detection_rules:
  - name: "Multiple Failed Logins"
    severity: high
    condition: 
      event_type: "authentication_failure"
      threshold: 5
      window: 5_minutes
      group_by: ["source_ip", "target_user"]
    action: 
      - alert_soc
      - block_ip
      - notify_admin
      
  - name: "Privilege Escalation"
    severity: critical
    condition:
      event_type: "permission_change"
      new_role: ["admin", "superuser"]
    action:
      - alert_soc_immediate
      - require_approval
      
  - name: "After Hours Admin Access"
    severity: medium
    condition:
      event_type: "admin_login"
      time_range: "22:00-06:00"
      timezone: "Asia/Dhaka"
    action:
      - alert_soc
      - require_mfa_verification
      
  - name: "Unusual Data Access Pattern"
    severity: high
    condition:
      event_type: "data_access"
      anomaly_score: "> 0.8"
    action:
      - alert_soc
      - create_investigation_ticket
      
  - name: "Payment Gateway Configuration Change"
    severity: critical
    condition:
      event_type: "config_change"
      resource: ["payment_gateway", "sslcommerz", "bkash"]
    action:
      - alert_soc_immediate
      - notify_security_lead
      - require_approval
```

### 12.6 Log Review Procedures

| Review Type | Frequency | Responsible | Scope |
|-------------|-----------|-------------|-------|
| Automated alert review | Continuous | SIEM | All systems |
| Security log analysis | Daily | SOC Analyst | Security events |
| Admin access review | Weekly | Security Lead | Privileged access |
| Full log review | Monthly | Compliance | All CDE systems |
| Trend analysis | Quarterly | Security Team | Patterns, anomalies |

### 12.7 Time Synchronization

| Requirement | Implementation | Verification |
|-------------|----------------|--------------|
| NTP servers | AWS Time Sync (169.254.169.123) | Automated |
| Time accuracy | ±1 second | CloudWatch metrics |
| Time zone | UTC for logs, Asia/Dhaka for display | Configuration |
| Clock synchronization | Every 60 seconds | systemd-timesyncd |

### 12.8 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 10.1.1 | Audit trails | Section 12.2 |
| 10.2.1 | Individual access logging | Log configuration |
| 10.3.1 | Audit trail entries | Log format spec |
| 10.4.1 | Time synchronization | NTP configuration |
| 10.5.1 | Log protection | S3 bucket policies |
| 10.6.1 | Log review | Review procedures |
| 10.7.1 | Log retention | Retention policy |

---

## 13. Requirement 11: Vulnerability Management

### 13.1 Requirement Overview

Regularly test security systems and networks.

### 13.2 Vulnerability Scanning

#### 13.2.1 Scan Schedule

| Scan Type | Frequency | Tool | Scope |
|-----------|-----------|------|-------|
| Internal vulnerability scan | Quarterly | Tenable.io | All CDE systems |
| External vulnerability scan | Quarterly | Approved ASV | Internet-facing |
| Container image scan | Every build | Trivy | All containers |
| Infrastructure scan | Weekly | AWS Inspector | All AWS resources |
| Dependency scan | Every build | Snyk | All dependencies |
| Code security scan | Every commit | SonarQube | All code |

#### 13.2.2 ASV Scan Requirements

| Requirement | Implementation | Status |
|-------------|----------------|--------|
| Approved Scanning Vendor | [To be selected] | Pending |
| Scan frequency | Quarterly minimum | Scheduled |
| Remediation timeline | Critical: 30 days, High: 60 days | Defined |
| Scan coverage | All public-facing IPs | Documented |
| Rescan after fixes | Required | Process defined |

### 13.3 Penetration Testing

| Test Type | Frequency | Scope | Provider |
|-----------|-----------|-------|----------|
| External penetration test | Annual | All public-facing systems | External firm |
| Internal penetration test | Annual | CDE, internal network | External firm |
| Web application test | Annual | Smart Dairy Portal | External firm |
| API security test | Annual | All payment APIs | External firm |
| Mobile app test | Annual | Customer mobile app | External firm |
| Segmentation test | Annual | Network segmentation | Internal + External |

### 13.4 Intrusion Detection

```yaml
# AWS GuardDuty Configuration
guardduty:
  enabled: true
  detectors:
    - region: ap-south-1
      status: enabled
      findings:
        - name: "UnauthorizedAccess:IAMUser/InstanceCredentialExfiltration"
          severity: high
          auto_block: true
          
        - name: "CryptoCurrency:EC2/BitcoinTool.B"
          severity: medium
          notify: true
          
        - name: "CredentialAccess:IAMUser/AnomalousBehavior"
          severity: high
          auto_block: true
          
  integration:
    sns_topic: "arn:aws:sns:region:account:guardduty-alerts"
    lambda_function: "guardduty-response-handler"
    security_hub: true
    
  response_automation:
    - condition: "severity >= HIGH"
      action: 
        - "isolate_instance"
        - "create_security_ticket"
        - "notify_soc"
```

### 13.5 File Integrity Monitoring (FIM)

| Component | Tool | Monitored Files | Alert Threshold |
|-----------|------|-----------------|-----------------|
| Application servers | AIDE | /etc, /bin, /usr/bin, config files | Any change |
| Database servers | AIDE | /etc, database config, SSL certs | Any change |
| Containers | Falco | Runtime behavior | Anomalous activity |

### 13.6 Change Detection

```python
# Configuration Drift Detection
import boto3
import hashlib
import json

class ConfigurationDriftDetector:
    """Detect unauthorized configuration changes"""
    
    def __init__(self):
        self.config = boto3.client('config')
        self.sns = boto3.client('sns')
        
    def check_critical_resources(self):
        """Check for unauthorized changes to CDE resources"""
        critical_resources = [
            'arn:aws:ec2:*:*:instance/*',
            'arn:aws:rds:*:*:db:*',
            'arn:aws:s3:::smart-dairy-*',
            'arn:aws:iam::*:role/*admin*'
        ]
        
        for resource in critical_resources:
            response = self.config.get_resource_config_history(
                resourceType='AWS::AllSupported',
                resourceId=resource
            )
            
            for item in response['configurationItems']:
                if self._is_unauthorized_change(item):
                    self._alert_security_team(item)
    
    def _is_unauthorized_change(self, config_item):
        """Determine if change was authorized"""
        # Check against approved change tickets
        # Verify change was made by authorized entity
        # Compare against baseline
        pass
```

### 13.7 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 11.1.1 | Wireless scanning | Quarterly scans |
| 11.2.1 | Internal vulnerability scans | Scan reports |
| 11.2.2 | External vulnerability scans | ASV reports |
| 11.3.1 | Penetration testing | Pen test reports |
| 11.3.2 | Segmentation testing | Segmentation tests |
| 11.4.1 | IDS/IPS implementation | GuardDuty config |
| 11.4.2 | Critical file monitoring | FIM configuration |
| 11.5.1 | Change detection | Config rules |
| 11.6.1 | Software integrity | Code signing |

---

## 14. Requirement 12: Security Policies

### 14.1 Requirement Overview

Maintain a policy that addresses information security for all personnel.

### 14.2 Information Security Policy

#### 14.2.1 Policy Hierarchy

```
┌─────────────────────────────────────────────────────────────────┐
│                   POLICY HIERARCHY                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  LEVEL 1: Information Security Policy                   │   │
│  │  (Board approved, annual review)                        │   │
│  └─────────────────────────────────────────────────────────┘   │
│                              │                                   │
│          ┌───────────────────┼───────────────────┐              │
│          │                   │                   │              │
│          ▼                   ▼                   ▼              │
│  ┌─────────────┐      ┌─────────────┐      ┌─────────────┐     │
│  │ LEVEL 2:    │      │  LEVEL 2:   │      │  LEVEL 2:   │     │
│  │  Policies   │      │  Standards  │      │  Procedures │     │
│  │  (e.g.,     │      │  (e.g.,     │      │  (e.g.,     │     │
│  │   Access    │      │   Password  │      │   Incident  │     │
│  │   Control   │      │   Standard) │      │   Response) │     │
│  │   Policy)   │      │             │      │             │     │
│  └─────────────┘      └─────────────┘      └─────────────┘     │
│          │                   │                   │              │
│          └───────────────────┼───────────────────┘              │
│                              │                                   │
│                              ▼                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  LEVEL 3: Guidelines, Baselines, Forms                  │   │
│  │  (Implementation details)                               │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 14.3 Security Awareness Program

| Training Component | Audience | Frequency | Delivery |
|-------------------|----------|-----------|----------|
| Security awareness | All employees | Annual | Online course |
| PCI DSS awareness | Payment processing staff | Annual | Instructor-led |
| Phishing simulation | All employees | Quarterly | Simulated attacks |
| Secure coding | Developers | Annual + new hire | Workshop |
| Incident response | Security team | Annual | Tabletop exercise |
| Policy updates | All employees | As needed | Email notification |

### 14.4 Incident Response Plan

```
┌─────────────────────────────────────────────────────────────────┐
│               INCIDENT RESPONSE PHASES                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────┐                                                    │
│  │PREPARATION│                                                  │
│  │          │  • Incident response team defined                 │
│  │          │  • Contact lists maintained                       │
│  │          │  • Tools and procedures ready                     │
│  └────┬────┘                                                   │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐   │
│  │DETECTION│────►│ANALYSIS │────►│CONTAIN- │────►│ERADICA- │   │
│  │& REPORT │     │         │     │  MENT   │     │  TION   │   │
│  └─────────┘     └─────────┘     └─────────┘     └────┬────┘   │
│       ▲                                               │         │
│       │                                               ▼         │
│       │                                          ┌─────────┐   │
│       └──────────────────────────────────────────│RECOVERY │   │
│                                                  └────┬────┘   │
│                                                       │         │
│                                                       ▼         │
│                                                  ┌─────────┐   │
│                                                  │POST-    │   │
│                                                  │INCIDENT │   │
│                                                  │ACTIVITY │   │
│                                                  └─────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### 14.4.1 Incident Classification

| Severity | Definition | Response Time | Notification |
|----------|------------|---------------|--------------|
| Critical | Confirmed CHD breach, system compromise | 15 minutes | Executive, Legal, Law enforcement |
| High | Suspected breach, significant vulnerability | 1 hour | Security lead, Management |
| Medium | Policy violation, minor security event | 4 hours | Security team |
| Low | Near miss, informational | 24 hours | Team lead |

### 14.5 Business Continuity

| Component | Recovery Objective | Implementation |
|-----------|-------------------|----------------|
| RTO (Recovery Time Objective) | 4 hours | Multi-AZ deployment |
| RPO (Recovery Point Objective) | 15 minutes | Continuous backup |
| Backup frequency | Continuous | RDS automated backups |
| Backup retention | 35 days | RDS configuration |
| Archive retention | 7 years | S3 Glacier |
| DR site | AWS DR region | Cross-region replication |

### 14.6 Vendor Management

| Requirement | Implementation | Evidence |
|-------------|----------------|----------|
| Vendor due diligence | Security questionnaire | Vendor files |
| Contractual security requirements | Security addendum | Contracts |
| Annual security review | Vendor assessment | Review reports |
| Incident notification | Contract clause | SLA documentation |
| Right to audit | Contract clause | Audit procedures |

### 14.7 Policy Review Schedule

| Policy | Owner | Review Frequency | Last Review | Next Review |
|--------|-------|------------------|-------------|-------------|
| Information Security Policy | CISO | Annual | Jan 2026 | Jan 2027 |
| Access Control Policy | Security Lead | Annual | Jan 2026 | Jan 2027 |
| Incident Response Policy | Security Lead | Annual | Jan 2026 | Jan 2027 |
| Acceptable Use Policy | HR | Annual | Jan 2026 | Jan 2027 |
| Data Classification Policy | DPO | Annual | Jan 2026 | Jan 2027 |
| Vendor Management Policy | Procurement | Annual | Jan 2026 | Jan 2027 |

### 14.8 Compliance Mapping

| PCI DSS 4.0 Requirement | Implementation | Evidence Location |
|-------------------------|----------------|-------------------|
| 12.1.1 | Security policy | Policy document |
| 12.1.2 | Risk assessment | Risk register |
| 12.1.3 | Usage policies | Acceptable use policy |
| 12.2.1 | Security roles | Org chart |
| 12.3.1 | Security procedures | Procedure library |
| 12.4.1 | Security monitoring | SOC procedures |
| 12.5.1 | Security testing | Test schedule |
| 12.6.1 | Security awareness | Training records |
| 12.7.1 | Background checks | HR procedures |
| 12.8.1 | Third-party security | Vendor agreements |
| 12.9.1 | Incident response | IR plan |
| 12.10.1 | IR plan testing | Test reports |
| 12.11.1 | Quarterly security reviews | Review records |

---

## 15. Compliance Validation

### 15.1 Self-Assessment Questionnaire (SAQ)

#### 15.1.1 SAQ Selection

| Criterion | Smart Dairy Status |
|-----------|-------------------|
| Card-present transactions | No |
| E-commerce | Yes |
| CHD stored | No (tokenized) |
| Third-party processor | Yes (SSLCommerz, bKash, Nagad) |
| **Selected SAQ** | **SAQ A** |

#### 15.1.2 SAQ A Requirements

| Req | Description | Smart Dairy Implementation | Evidence |
|-----|-------------|---------------------------|----------|
 | 2.3.1 | TLS for remote access | AWS Systems Manager | Configuration |
| 8.3.6 | MFA for CDE access | Duo Security / YubiKey | User list |
| 9.2.1 | Physical access controls | AWS data center security | AWS SOC 2 |
| 11.2.1 | Internal vulnerability scan | Tenable.io | Scan reports |
| 11.2.2 | External vulnerability scan | Approved ASV | ASV reports |
| 11.6.1 | Change/tamper detection | AWS Config | Config rules |
| 12.1.1 | Security policy | InfoSec policy | Policy document |
| 12.2.1 | Risk assessment | Annual risk assessment | Risk register |
| 12.3.1 | Security procedures | Procedure library | Documentation |
| 12.4.1 | Security monitoring | Security operations | SOC procedures |
| 12.5.1 | Security testing | Test schedule | Test reports |
| 12.6.1 | Security awareness training | Training program | Training records |
| 12.6.2 | Security awareness content | Phishing simulation | Campaign results |
| 12.7.1 | Background checks | HR procedures | Policy document |
| 12.8.1 | TPSP list | Vendor inventory | Vendor list |
| 12.8.2 | TPSP due diligence | Vendor assessments | Assessment reports |
| 12.8.3 | TPSP contracts | Security addendums | Contracts |
| 12.8.4 | TPSP monitoring | Annual reviews | Review records |
| 12.8.5 | TPSP incident notification | Contract clauses | SLA documentation |
| 12.10.1 | Incident response plan | IR plan | Plan document |
| 12.10.2 | Incident response procedures | IR procedures | Documentation |
| 12.10.3 | Incident response training | Annual training | Training records |
| 12.10.4 | Incident monitoring/detection | SIEM/Splunk | Configuration |
| 12.10.5 | Incident response testing | Tabletop exercises | Test reports |
| 12.10.6 | Incident reporting | Reporting procedures | Documentation |
| 12.10.7 | Incident evidence retention | Evidence procedures | Policy document |

### 15.2 Compliance Validation Process

```
┌─────────────────────────────────────────────────────────────────┐
│              COMPLIANCE VALIDATION CYCLE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌───────────────┐                                               │
│  │  GATHER       │  • Collect evidence                           │
│  │  EVIDENCE     │  • Document controls                          │
│  │               │  • Screenshot configurations                  │
│  └───────┬───────┘                                               │
│          │                                                        │
│          ▼                                                        │
│  ┌───────────────┐                                               │
│  │  COMPLETE     │  • Fill SAQ                                   │
│  │  SAQ          │  • Answer all questions                       │
│  │               │  • Attach evidence                            │
│  └───────┬───────┘                                               │
│          │                                                        │
│          ▼                                                        │
│  ┌───────────────┐                                               │
│  │  INTERNAL     │  • Security Lead review                       │
│  │  REVIEW       │  • IT Director review                         │
│  │               │  • Fix any gaps                               │
│  └───────┬───────┘                                               │
│          │                                                        │
│          ▼                                                        │
│  ┌───────────────┐                                               │
│  │  ASV SCAN     │  • External vulnerability scan                │
│  │  (if needed)  │  • Remediate findings                         │
│  │               │  • Rescan if necessary                        │
│  └───────┬───────┘                                               │
│          │                                                        │
│          ▼                                                        │
│  ┌───────────────┐                                               │
│  │  SUBMIT       │  • Submit to acquiring bank                   │
│  │  TO BANK      │  • Include Attestation of Compliance          │
│  │               │  • Include ASV scan (if required)             │
│  └───────┬───────┘                                               │
│          │                                                        │
│          ▼                                                        │
│  ┌───────────────┐                                               │
│  │  MAINTAIN     │  • Continuous compliance                      │
│  │  COMPLIANCE   │  • Quarterly scans                            │
│  │               │  • Monthly reviews                            │
│  └───────────────┘                                               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 15.3 Attestation of Compliance (AOC)

| Field | Value |
|-------|-------|
| Merchant Name | Smart Dairy Ltd. |
| Merchant DBA | Smart Dairy |
| Merchant Location | Dhaka, Bangladesh |
| Merchant ID | [To be assigned by acquirer] |
| SAQ Version | SAQ A v4.0 |
| Assessment Date | [Annual date] |
| Compliant Status | Compliant |
| Signature | [Security Lead] |
| Date | [Annual date] |

### 15.4 Quarterly Compliance Activities

| Month | Activity | Responsible | Evidence |
|-------|----------|-------------|----------|
| Jan/Apr/Jul/Oct | Internal vulnerability scan | Security Team | Scan report |
| Feb/May/Aug/Nov | Access review | Data Owners | Review documentation |
| Mar/Jun/Sep/Dec | Policy review | Compliance Officer | Review records |
| Quarterly | Security awareness training | HR | Training records |
| Quarterly | Incident response review | Security Lead | Review meeting notes |

---

## 16. Third-Party Payment Processors

### 16.1 SSLCommerz Integration

#### 16.1.1 Overview

SSLCommerz is the primary payment gateway for credit/debit card processing in Bangladesh.

| Attribute | Details |
|-----------|---------|
| Provider | SSL Wireless (SSLCommerz) |
| PCI Status | Level 1 Service Provider |
| Integration Method | Redirect/iFrame (SAQ A) or Direct Post (SAQ A-EP) |
| Recommended | Redirect for SAQ A |

#### 16.1.2 SSLCommerz Integration Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│            SSLCOMMERZ INTEGRATION FLOW                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. Customer selects products and proceeds to checkout          │
│                                                                  │
│  2. Smart Dairy creates order and requests payment session      │
│     ┌──────────┐      POST /api/v1/payment/initiate             │
│     │  Smart   │───────────────────────────────────────────────►│
│     │  Dairy   │      {store_id, order_id, amount, currency}   │
│     │  Server  │◄───────────────────────────────────────────────│
│     └──────────┘      {GatewayPageURL, sessionkey}              │
│                                                                  │
│  3. Redirect customer to SSLCommerz secure page                 │
│     ┌──────────┐                                               │
│     │ Customer │───► Browser redirected to GatewayPageURL      │
│     │ Browser  │      (SSLCommerz hosted page)                 │
│     └──────────┘                                               │
│                          │                                       │
│                          ▼                                       │
│     ┌─────────────────────────────────────────────────────────┐ │
│     │              SSLCOMMERZ SECURE PAGE                     │ │
│     │  • Customer enters card details                         │ │
│     │  • 3D Secure authentication (if required)               │ │
│     │  • Transaction processed                                │ │
│     │  • Card data NEVER touches Smart Dairy servers          │ │
│     └─────────────────────────────────────────────────────────┘ │
│                          │                                       │
│                          ▼                                       │
│  4. SSLCommerz redirects back to Smart Dairy                   │
│     ┌──────────┐                                               │
│     │ Customer │◄─── Redirect to Smart Dairy callback URL      │
│     │ Browser  │      with transaction result                  │
│     └──────────┘                                               │
│                          │                                       │
│                          ▼                                       │
│  5. Smart Dairy validates and completes order                  │
│     ┌──────────┐                                               │
│     │  Smart   │───► Validate transaction with SSLCommerz API  │
│     │  Dairy   │───► Update order status                       │
│     │  Server  │───► Store transaction token (not card data)   │
│     └──────────┘                                               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### 16.1.3 SSLCommerz Security Checklist

| Check | Requirement | Verification |
|-------|-------------|--------------|
| ☑️ | SSLCommerz is PCI DSS compliant | Certificate on file |
| ☑️ | Integration uses redirect method | Code review |
| ☑️ | No CHD collected on Smart Dairy pages | Code review |
| ☑️ | Callback validation implemented | Code review |
| ☑️ | IPN (Instant Payment Notification) verified | Configuration |
| ☑️ | Transaction signing validated | Code review |
| ☐ | SSLCommerz certificate pinned | [To implement] |

### 16.2 bKash Integration

#### 16.2.1 Overview

bKash is Bangladesh's largest mobile financial service provider.

| Attribute | Details |
|-----------|---------|
| Provider | bKash Limited |
| PCI Status | Not applicable (tokenized mobile wallet) |
| Integration Method | API with tokenization |
| Payment Flow | Customer authorizes via bKash app/USSD |

#### 16.2.2 bKash Tokenization Flow

```
┌─────────────────────────────────────────────────────────────────┐
│              BKASH TOKENIZATION FLOW                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. Customer selects bKash as payment method                    │
│                                                                  │
│  2. Customer enters bKash wallet number                         │
│     ┌──────────┐                                               │
│     │  Smart   │◄─── Wallet: 01XXXXXXXXX                        │
│     │  Dairy   │                                               │
│     │  Portal  │                                               │
│     └────┬─────┘                                               │
│          │                                                        │
│          ▼                                                        │
│  3. Request token from bKash                                    │
│     ┌──────────┐      POST /tokenized/checkout/create           │
│     │  Smart   │───────────────────────────────────────────────►│
│     │  Dairy   │      {mode, payerReference, callbackURL}       │
│     │  Server  │◄───────────────────────────────────────────────│
│     └──────────┘      {paymentID, bkashURL, callbackURL}        │
│                                                                  │
│  4. Customer completes payment via bKash app/USSD               │
│     • bKash sends PIN verification to customer                   │
│     • Customer confirms payment in bKash app                     │
│     • bKash processes transaction                                │
│                                                                  │
│  5. bKash notifies Smart Dairy                                  │
│     ┌──────────┐      POST /webhook/bkash                       │
│     │  Smart   │◄───────────────────────────────────────────────│
│     │  Dairy   │      {paymentID, status, trxID, amount}        │
│     │  Server  │                                               │
│     └──────────┘                                               │
│                                                                  │
│  6. Verify and complete                                         │
│     • Query bKash API for verification                           │
│     • Store: paymentID, trxID, amount, status (NO wallet info)   │
│     • Complete order                                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 16.3 Nagad Integration

| Attribute | Details |
|-----------|---------|
| Provider | Nagad (Bangladesh Post Office) |
| PCI Status | Not applicable (tokenized mobile wallet) |
| Integration Method | API with tokenization |
| Security | Merchant signing required |

#### 16.3.1 Nagad Security Requirements

| Requirement | Implementation |
|-------------|----------------|
| Merchant signing | RSA-SHA256 signature |
| IP whitelisting | Smart Dairy IPs registered |
| Callback validation | Checksum verification |
| Idempotency | Order ID tracking |

### 16.4 Rocket Integration

| Attribute | Details |
|-----------|---------|
| Provider | Dutch-Bangla Bank (Rocket) |
| PCI Status | Not applicable (tokenized mobile wallet) |
| Integration Method | API with tokenization |

### 16.5 Third-Party Security Due Diligence

| Provider | PCI Status | Contract Review | Assessment Date | Next Review |
|----------|-----------|-----------------|-----------------|-------------|
| SSLCommerz | Level 1 Service Provider | ☑️ | Jan 2026 | Jan 2027 |
| bKash | N/A (Wallet) | ☑️ | Jan 2026 | Jan 2027 |
| Nagad | N/A (Wallet) | ☑️ | Jan 2026 | Jan 2027 |
| Rocket | N/A (Wallet) | ☑️ | Jan 2026 | Jan 2027 |

### 16.6 Integration Security Checklist

```yaml
# Payment Integration Security Checklist
checklist:
  api_security:
    - item: "All API calls use TLS 1.2+"
      status: required
      verification: "SSL Labs scan"
      
    - item: "API keys stored in AWS Secrets Manager"
      status: required
      verification: "Infrastructure review"
      
    - item: "API keys rotated every 180 days"
      status: required
      verification: "Rotation log"
      
    - item: "Request signing implemented"
      status: required
      verification: "Code review"
      
    - item: "Webhook signature verification"
      status: required
      verification: "Code review"
      
  data_handling:
    - item: "No card data logged or stored"
      status: required
      verification: "Log review"
      
    - item: "Tokens stored encrypted"
      status: required
      verification: "Database review"
      
    - item: "Transaction IDs stored for reconciliation"
      status: required
      verification: "Database schema"
      
  error_handling:
    - item: "Generic error messages to users"
      status: required
      verification: "UI testing"
      
    - item: "Detailed errors logged securely"
      status: required
      verification: "Log review"
      
    - item: "Failed transactions retried with idempotency"
      status: required
      verification: "Code review"
```

---

## 17. Appendices

### Appendix A: PCI DSS Compliance Checklist

| Req # | Requirement | Responsible | Status | Evidence |
|-------|-------------|-------------|--------|----------|
| **1** | **Install and Maintain Network Security Controls** | | | |
| 1.1.1 | Security controls defined | Network Admin | ☐ | |
| 1.1.2 | Network diagrams current | Network Admin | ☐ | |
| 1.2.1 | NSC configured to restrict traffic | Network Admin | ☐ | |
| 1.2.4 | NSC installed between wireless and CDE | Network Admin | ☐ N/A |
| 1.2.5 | Inbound traffic limited to necessary | Network Admin | ☐ | |
| 1.2.6 | NSC deny rules implemented | Network Admin | ☐ | |
| 1.3.1 | NSC installed between networks | Network Admin | ☐ | |
| 1.3.2 | CDE inbound traffic limited | Network Admin | ☐ | |
| 1.4.1 | NSC on portable devices | IT Admin | ☐ | |
| **2** | **Apply Secure Configurations** | | | |
| 2.1.1 | Vendor default credentials changed | System Admin | ☐ | |
| 2.1.2 | Security parameters configured | System Admin | ☐ | |
| 2.2.1 | System components configured securely | System Admin | ☐ | |
| 2.2.2 | System configured for single purpose | System Admin | ☐ | |
| 2.2.3 | Unnecessary functionality removed | System Admin | ☐ | |
| 2.2.4 | Only necessary services enabled | System Admin | ☐ | |
| 2.3.1 | Strong cryptography for admin access | System Admin | ☐ | |
| **3** | **Protect Stored Account Data** | | | |
| 3.1.1 | Data retention policy implemented | DPO | ☐ | |
| 3.1.2 | Data storage justified | DPO | ☐ | |
| 3.2.1 | SAD not stored after authorization | Security Lead | ☐ N/A | No CHD stored |
| 3.3.1 | PAN masking implemented | Development | ☐ N/A | No PAN stored |
| 3.4.1 | PAN rendered unreadable | Security Lead | ☐ N/A | Tokenized |
| 3.5.1 | Key management procedures | Security Lead | ☐ | |
| 3.6.1 | Cryptographic keys generated securely | Security Lead | ☐ | |
| 3.6.2 | Key distribution secure | Security Lead | ☐ | |
| 3.6.3 | Key storage secure | Security Lead | ☐ | |
| 3.6.4 | Key updates at end of cryptoperiod | Security Lead | ☐ | |
| 3.6.5 | Key retirement/ replacement | Security Lead | ☐ | |
| 3.6.6 | Manual key-management prohibited | Security Lead | ☐ | |
| 3.7.1 | Key custody documented | Security Lead | ☐ | |
| **4** | **Protect Cardholder Data** | | | |
| 4.1.1 | Strong cryptography for transmission | Network Admin | ☐ | |
| 4.1.2 | Protocols in use documented | Security Lead | ☐ | |
| 4.2.1 | PAN sent securely | Development | ☐ N/A | No PAN transmitted |
| 4.2.2 | End-user messaging secure | Development | ☐ | |
| **5** | **Protect Against Malware** | | | |
| 5.1.1 | Anti-malware deployed | IT Admin | ☐ | |
| 5.1.2 | Anti-malware kept current | IT Admin | ☐ | |
| 5.2.1 | Anti-malware mechanisms current | IT Admin | ☐ | |
| 5.2.2 | Automatic updates enabled | IT Admin | ☐ | |
| 5.2.3 | System scans performed | IT Admin | ☐ | |
| 5.3.1 | Anti-malware active | IT Admin | ☐ | |
| 5.4.1 | Anti-phishing protection | IT Admin | ☐ | |
| **6** | **Develop and Maintain Secure Systems** | | | |
| 6.1.1 | Security vulnerabilities identified | Security Lead | ☐ | |
| 6.2.1 | Security patches installed | System Admin | ☐ | |
| 6.3.1 | Software security patches | Development | ☐ | |
| 6.3.2 | Software security patches applied | Development | ☐ | |
| 6.4.1 | Change management process | Change Manager | ☐ | |
| 6.4.2 | Separation of duties | HR | ☐ | |
| 6.4.3 | Production data protection | Security Lead | ☐ | |
| 6.4.4 | Live PAN testing restrictions | Security Lead | ☐ N/A | No PAN testing |
| 6.4.5 | Change documentation | Change Manager | ☐ | |
| 6.5.1 | Address common coding vulnerabilities | Development | ☐ | |
| **7** | **Restrict Access** | | | |
| 7.1.1 | Access restrictions defined | Security Lead | ☐ | |
| 7.2.1 | Access to system components limited | System Admin | ☐ | |
| 7.2.2 | Access authorization | Security Lead | ☐ | |
| 7.2.3 | Access control system(s) implemented | System Admin | ☐ | |
| 7.2.4 | Access removed upon termination | HR | ☐ | |
| 7.2.5 | Access review | Data Owners | ☐ | |
| **8** | **Identify Users and Authenticate** | | | |
| 8.1.1 | User identification policy | Security Lead | ☐ | |
| 8.2.1 | Strong authentication | Security Lead | ☐ | |
| 8.2.2 | MFA for remote access | Security Lead | ☐ | |
| 8.2.3 | MFA for admin access | Security Lead | ☐ | |
| 8.2.4 | MFA for CDE access | Security Lead | ☐ | |
| 8.2.5 | MFA for user access | Security Lead | ☐ | |
| 8.2.6 | MFA for user access to CDE | Security Lead | ☐ | |
| 8.2.7 | MFA systems security | Security Lead | ☐ | |
| 8.3.1 | MFA for remote network access | Security Lead | ☐ | |
| 8.3.4 | Invalid access attempts | Security Lead | ☐ | |
| 8.3.5 | Password change on first use | Security Lead | ☐ | |
| 8.3.6 | Password complexity | Security Lead | ☐ | |
| 8.3.7 | Password history | Security Lead | ☐ | |
| 8.3.8 | Password storage | Security Lead | ☐ | |
| 8.3.9 | Password expiration | Security Lead | ☐ | |
| 8.4.1 | MFA for admin access | Security Lead | ☐ | |
| 8.5.1 | Service providers' MFA | Security Lead | ☐ | |
| **9** | **Restrict Physical Access** | | | |
| 9.1.1 | Physical security controls | Facilities | ☐ | |
| 9.1.2 | Physical access authorizations | Facilities | ☐ | |
| 9.2.1 | Individual physical access | Facilities | ☐ | |
| 9.2.2 | Physical access revoked | HR/Facilities | ☐ | |
| 9.2.3 | Physical access review | Facilities | ☐ | |
| 9.3.1 | Media back-ups stored off-site | IT Admin | ☐ | |
| 9.4.1 | Media inventory | IT Admin | ☐ | |
| 9.4.2 | Media storage | IT Admin | ☐ | |
| 9.4.3 | Media distribution | IT Admin | ☐ | |
| 9.5.1 | Media disposal | IT Admin | ☐ | |
| 9.6.1 | Strict control of media | IT Admin | ☐ | |
| 9.7.1 | Media storage approval | IT Admin | ☐ | |
| 9.8.1 | Device destruction | IT Admin | ☐ | |
| 9.9.1 | POI devices protected | N/A | ☐ N/A | No POI devices |
| **10** | **Log and Monitor Access** | | | |
| 10.1.1 | Audit trail coverage | Security Lead | ☐ | |
| 10.2.1 | Audit trail entries | Security Lead | ☐ | |
| 10.2.2 | Audit trail automated | Security Lead | ☐ | |
| 10.3.1 | Audit trail files protected | Security Lead | ☐ | |
| 10.3.2 | Audit trail files backed up | Security Lead | ☐ | |
| 10.3.3 | Audit trail files promptly available | Security Lead | ☐ | |
| 10.3.4 | File integrity monitoring | Security Lead | ☐ | |
| 10.4.1 | Synchronized time | System Admin | ☐ | |
| 10.5.1 | Audit trail review | Security Lead | ☐ | |
| 10.6.1 | Log review for anomalies | Security Lead | ☐ | |
| 10.7.1 | Audit trail retention | Security Lead | ☐ | |
| 10.7.2 | Audit trail available online | Security Lead | ☐ | |
| 10.7.3 | Audit trail immediately available | Security Lead | ☐ | |
| **11** | **Test Security Systems** | | | |
| 11.1.1 | Wireless scanning | Security Lead | ☐ | |
| 11.2.1 | Internal vulnerability scans | Security Lead | ☐ | |
| 11.2.2 | External vulnerability scans | Security Lead | ☐ | |
| 11.3.1 | Penetration testing | Security Lead | ☐ | |
| 11.3.2 | Segmentation penetration testing | Security Lead | ☐ | |
| 11.3.3 | Exploitable vulnerabilities corrected | Security Lead | ☐ | |
| 11.4.1 | IDS/IPS implemented | Security Lead | ☐ | |
| 11.4.2 | Critical file monitoring | Security Lead | ☐ | |
| 11.4.3 | Detection mechanisms alert personnel | Security Lead | ☐ | |
| 11.4.4 | Signatures kept current | Security Lead | ☐ | |
| 11.4.5 | Personnel respond to alerts | Security Lead | ☐ | |
| 11.4.6 | Automatic disconnect for threats | Security Lead | ☐ | |
| 11.4.7 | Detection coverage | Security Lead | ☐ | |
| 11.5.1 | Change detection | Security Lead | ☐ | |
| 11.6.1 | Software integrity | Security Lead | ☐ | |
| **12** | **Support Information Security** | | | |
| 12.1.1 | Information security policy | Security Lead | ☐ | |
| 12.1.2 | Risk assessment | Security Lead | ☐ | |
| 12.1.3 | Usage policies | Security Lead | ☐ | |
| 12.1.4 | Security roles | HR | ☐ | |
| 12.2.1 | Security risk assessment | Security Lead | ☐ | |
| 12.2.2 | Risk assessment methodology | Security Lead | ☐ | |
| 12.3.1 | Security testing | Security Lead | ☐ | |
| 12.3.2 | Security testing coverage | Security Lead | ☐ | |
| 12.3.3 | Security testing remediation | Security Lead | ☐ | |
| 12.4.1 | Security monitoring | Security Lead | ☐ | |
| 12.4.2 | Automated mechanisms | Security Lead | ☐ | |
| 12.5.1 | Security testing | Security Lead | ☐ | |
| 12.5.2 | Vulnerability management | Security Lead | ☐ | |
| 12.5.3 | Security control verification | Security Lead | ☐ | |
| 12.6.1 | Security awareness program | HR | ☐ | |
| 12.6.2 | Security awareness content | HR | ☐ | |
| 12.6.3.1 | Security awareness training for personnel | HR | ☐ | |
| 12.6.3.2 | Security awareness training for dev | HR | ☐ | |
| 12.7.1 | Background checks | HR | ☐ | |
| 12.8.1 | Third-party security | Procurement | ☐ | |
| 12.8.2 | Third-party due diligence | Procurement | ☐ | |
| 12.8.3 | Third-party contracts | Legal | ☐ | |
| 12.8.4 | Third-party monitoring | Security Lead | ☐ | |
| 12.8.5 | Third-party incident notification | Legal | ☐ | |
| 12.9.1 | Incident response plan | Security Lead | ☐ | |
| 12.9.2 | Incident response procedures | Security Lead | ☐ | |
| 12.9.3 | Incident response training | Security Lead | ☐ | |
| 12.9.4 | Incident response testing | Security Lead | ☐ | |
| 12.9.5 | Incident response updates | Security Lead | ☐ | |
| 12.10.1 | Business impact analysis | BCP Lead | ☐ | |
| 12.10.2 | BCP and DR plans | BCP Lead | ☐ | |
| 12.10.3 | BCP and DR testing | BCP Lead | ☐ | |
| 12.10.4 | Crisis management | BCP Lead | ☐ | |
| 12.10.5 | BCP and DR reviews | BCP Lead | ☐ | |

### Appendix B: Evidence Templates

#### B.1 Network Diagram Template

```
┌─────────────────────────────────────────────────────────────────┐
│                    NETWORK DIAGRAM                               │
│                    Smart Dairy Ltd.                              │
│                    Date: [Date]                                  │
│                    Version: [Version]                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  [Network diagram showing all system components,                 │
│   network segments, data flows, and security controls]           │
│                                                                  │
│  Legend:                                                         │
│  🔴 = CDE (Cardholder Data Environment)                         │
│  🟡 = Connected systems                                          │
│  🟢 = Out of scope                                               │
│  ➡️  = Data flow                                                  │
│  🔒 = Encryption                                                 │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### B.2 Data Flow Diagram Template

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA FLOW DIAGRAM                             │
│                    [System Name]                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  [Visual representation of CHD flow through the system]          │
│                                                                  │
│  Data Elements:                                                  │
│  • PAN: Primary Account Number                                   │
│  • CHD: Cardholder Data                                          │
│  • SAD: Sensitive Authentication Data                            │
│  • Token: Substitute value for PAN                               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

#### B.3 Access Control Matrix Template

| User/Role | System A | System B | Database | Admin Panel | CDE |
|-----------|----------|----------|----------|-------------|-----|
| Role 1 | Read | None | None | None | None |
| Role 2 | Read/Write | Read | None | None | None |
| Admin | Full | Full | Read | Full | Audit |

#### B.4 Vulnerability Scan Report Template

| Scan Date | System | Critical | High | Medium | Low | Remediated | Notes |
|-----------|--------|----------|------|--------|-----|------------|-------|
| [Date] | [System] | [Count] | [Count] | [Count] | [Count] | [Count] | [Notes] |

#### B.5 Incident Response Template

| Field | Value |
|-------|-------|
| Incident ID | INC-YYYY-NNNN |
| Date/Time Detected | |
| Severity | Critical/High/Medium/Low |
| Category | Security/Privacy/Availability |
| Description | |
| Impact Assessment | |
| Containment Actions | |
| Root Cause | |
| Remediation | |
| Lessons Learned | |
| Closed Date | |

#### B.6 Third-Party Service Provider Template

| Field | Value |
|-------|-------|
| Provider Name | |
| Services Provided | |
| PCI DSS Status | |
| AOC Date | |
| Contract Review Date | |
| Security Requirements in Contract | |
| Incident Notification Clause | |
| Right to Audit Clause | |
| Last Assessment Date | |
| Next Assessment Date | |

### Appendix C: Reference Documents

| Document ID | Title | Location |
|-------------|-------|----------|
| F-001 | Information Security Policy | [Path] |
| F-002 | Access Control Policy | [Path] |
| F-003 | Incident Response Plan | [Path] |
| F-004 | PCI DSS Compliance Guide | This document |
| F-005 | Data Retention Policy | [Path] |
| F-006 | Vendor Management Policy | [Path] |
| F-007 | Security Awareness Training Program | [Path] |
| T-001 | Secure Coding Standards | [Path] |
| T-002 | Penetration Test Report Template | [Path] |
| T-003 | Risk Assessment Template | [Path] |

### Appendix D: Acronyms and Definitions

| Term | Definition |
|------|------------|
| AOC | Attestation of Compliance |
| ASV | Approved Scanning Vendor |
| CDE | Cardholder Data Environment |
| CHD | Cardholder Data |
| CVV | Card Verification Value |
| DAST | Dynamic Application Security Testing |
| FIM | File Integrity Monitoring |
| HSM | Hardware Security Module |
| IDS | Intrusion Detection System |
| IPS | Intrusion Prevention System |
| MFA | Multi-Factor Authentication |
| NTP | Network Time Protocol |
| PAN | Primary Account Number |
| PCI DSS | Payment Card Industry Data Security Standard |
| QSA | Qualified Security Assessor |
| RBAC | Role-Based Access Control |
| ROC | Report on Compliance |
| SAD | Sensitive Authentication Data |
| SAQ | Self-Assessment Questionnaire |
| SAST | Static Application Security Testing |
| SDLC | Software Development Lifecycle |
| TLS | Transport Layer Security |
| TPSP | Third-Party Service Provider |
| WAF | Web Application Firewall |

### Appendix E: Compliance Contacts

| Role | Name | Email | Phone |
|------|------|-------|-------|
| Security Lead | [Name] | [Email] | [Phone] |
| IT Director | [Name] | [Email] | [Phone] |
| CTO | [Name] | [Email] | [Phone] |
| Compliance Officer | [Name] | [Email] | [Phone] |
| DPO | [Name] | [Email] | [Phone] |
| Acquiring Bank Contact | [Name] | [Email] | [Phone] |

---

## Document Approval

This document has been reviewed and approved by:

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Security Lead | | | |
| IT Director | | | |
| CTO | | | |

---

*End of Document*

---

**Document Control Information:**
- Document ID: F-004
- Version: 1.0
- Classification: Confidential
- Next Review Date: July 31, 2026
- Owner: Security Lead
