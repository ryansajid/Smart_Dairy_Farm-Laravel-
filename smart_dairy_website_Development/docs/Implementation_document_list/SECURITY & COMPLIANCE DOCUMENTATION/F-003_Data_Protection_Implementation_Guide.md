# SMART DAIRY LTD.
## DATA PROTECTION IMPLEMENTATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | F-003 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Status** | Draft for Review |
| **Author** | Security Lead |
| **Owner** | Security Lead |
| **Reviewer** | IT Director |
| **Approved By** | Managing Director |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Data Classification](#2-data-classification)
3. [Encryption at Rest](#3-encryption-at-rest)
4. [Encryption in Transit](#4-encryption-in-transit)
5. [Field-Level Encryption](#5-field-level-encryption)
6. [Key Management](#6-key-management)
7. [Data Masking](#7-data-masking)
8. [Data Loss Prevention (DLP)](#8-data-loss-prevention-dlp)
9. [Database Security](#9-database-security)
10. [Backup Encryption](#10-backup-encryption)
11. [Secure Data Deletion](#11-secure-data-deletion)
12. [PII Handling](#12-pii-handling)
13. [Data Anonymization](#13-data-anonymization)
14. [Appendices](#14-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Data Protection Implementation Guide defines the comprehensive data protection strategy and technical implementation details for Smart Dairy Ltd.'s Smart Web Portal System and Integrated ERP. It establishes encryption standards, key management procedures, and data handling protocols to ensure the confidentiality, integrity, and availability of sensitive information.

### 1.2 Scope

This document covers data protection for:

| System Component | Data Protection Requirements |
|-----------------|------------------------------|
| Customer Portal | PII encryption, payment data tokenization |
| B2B Portal | Business data, contract information |
| Farm Management System | Farm proprietary data, livestock records |
| ERP System | Financial data, employee records |
| Mobile Applications | Cached data encryption, secure storage |
| IoT Systems | Sensor data, operational metrics |
| Database Systems | TDE, column-level encryption |
| Backup Systems | Encrypted backups, secure storage |

### 1.3 Data Types to Protect

| Data Category | Examples | Classification |
|--------------|----------|----------------|
| **Customer PII** | Names, addresses, phone, email, NID | Restricted |
| **Payment Data** | Card numbers, tokenized data | Restricted (PCI DSS) |
| **Employee Data** | HR records, salary information | Confidential |
| **Farm Proprietary Data** | Production metrics, formulas | Confidential |
| **Authentication Credentials** | Passwords, API keys, tokens | Restricted |
| **Financial Data** | Transactions, invoices | Confidential |

### 1.4 Regulatory Compliance

| Regulation | Requirement | Implementation |
|------------|-------------|----------------|
| Bangladesh DPA | Personal data protection | Encryption, access controls |
| PCI DSS | Payment card security | Tokenization, encryption |
| ISO 27001 | Information security | Key management, audit logs |
| GDPR (Best Practice) | Data subject rights | Anonymization, deletion |

---

## 2. DATA CLASSIFICATION

### 2.1 Classification Framework

Smart Dairy implements a four-tier data classification system:

| Level | Color | Description | Handling Requirements |
|-------|-------|-------------|----------------------|
| **Public** | Green | Information intended for public disclosure | No encryption required |
| **Internal** | Yellow | Internal business use only | Access controls, minimal encryption |
| **Confidential** | Amber | Sensitive business information | Encryption required, need-to-know basis |
| **Restricted** | Red | Highly sensitive regulated data | Strong encryption, strict access controls |

### 2.2 Data Classification Matrix

| Data Type | Classification | Encryption at Rest | Encryption in Transit | Retention Period |
|-----------|---------------|-------------------|----------------------|------------------|
| Customer Names | Restricted | AES-256 | TLS 1.3 | 7 years |
| Phone Numbers | Restricted | AES-256 + Field-level | TLS 1.3 | 7 years |
| Email Addresses | Restricted | AES-256 + Field-level | TLS 1.3 | 7 years |
| NID Numbers | Restricted | AES-256 + Field-level | TLS 1.3 | 7 years |
| Payment Card Data | Restricted | Tokenized only | TLS 1.3 | Per PCI DSS |
| Passwords | Restricted | Argon2 Hash | TLS 1.3 | Until changed |
| Employee Salaries | Confidential | AES-256 | TLS 1.3 | 10 years |
| Farm Production Data | Confidential | AES-256 | TLS 1.3 | 5 years |
| Order History | Internal | AES-256 | TLS 1.3 | 3 years |
| Product Catalog | Public | None | TLS 1.2 | Indefinite |

---

## 3. ENCRYPTION AT REST

### 3.1 Encryption Strategy

All data at rest must be encrypted using industry-standard encryption algorithms. The architecture follows a defense-in-depth approach with multiple encryption layers.

### 3.2 Database Encryption (PostgreSQL)

#### 3.2.1 Transparent Data Encryption (TDE)

```sql
-- Enable encryption for PostgreSQL cluster
-- Requires pgcrypto extension

-- 1. Install pgcrypto extension
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- 2. Create encrypted tablespace (when using tablespace-level encryption)
-- Note: PostgreSQL 15+ supports built-in encryption at rest
-- For earlier versions, use filesystem-level encryption (LUKS/dm-crypt)

-- 3. Column-level encryption for sensitive fields
CREATE TABLE customers (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    nid VARCHAR(50) NOT NULL,
    -- Encrypted fields
    email_encrypted BYTEA,
    phone_encrypted BYTEA,
    nid_encrypted BYTEA,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Create indexes on encrypted data (using HMAC for searchability)
CREATE INDEX idx_customers_email_hmac ON customers 
    USING btree (encode(digest(email, 'sha256'), 'hex'));
```

### 3.3 File Storage Encryption

#### 3.3.1 AWS S3 Encryption Configuration

```yaml
# S3 Bucket Encryption Policy
# File: s3-encryption-policy.yaml

AWSTemplateFormatVersion: '2010-09-09'
Description: S3 Bucket with Default Encryption

Resources:
  SmartDairyDataBucket:
    Type: AWS::S3::Bucket
    Properties:
      BucketName: smart-dairy-data-prod
      BucketEncryption:
        ServerSideEncryptionConfiguration:
          - ServerSideEncryptionByDefault:
              SSEAlgorithm: aws:kms
              KMSMasterKeyID: alias/smart-dairy-master-key
            BucketKeyEnabled: true
      PublicAccessBlockConfiguration:
        BlockPublicAcls: true
        BlockPublicPolicy: true
        IgnorePublicAcls: true
        RestrictPublicBuckets: true
      VersioningConfiguration:
        Status: Enabled
```

#### 3.3.2 Python Client-Side Encryption for S3

```python
"""
S3 Client-Side Encryption Service
Smart Dairy Ltd. - File Storage Encryption
"""

import boto3
from cryptography.fernet import Fernet
import base64
import hashlib
from typing import BinaryIO, Optional
import logging

logger = logging.getLogger(__name__)


class S3EncryptionService:
    """
    Client-side encryption service for AWS S3 uploads/downloads.
    Uses envelope encryption with data keys encrypted by KMS.
    """
    
    def __init__(self, kms_key_id: str, region: str = 'ap-southeast-1'):
        self.kms_client = boto3.client('kms', region_name=region)
        self.s3_client = boto3.client('s3', region_name=region)
        self.kms_key_id = kms_key_id
        
    def generate_data_key(self) -> dict:
        """Generate a data key for client-side encryption."""
        response = self.kms_client.generate_data_key(
            KeyId=self.kms_key_id,
            KeySpec='AES_256'
        )
        return {
            'plaintext_key': response['Plaintext'],
            'encrypted_key': response['CiphertextBlob']
        }
    
    def encrypt_file(self, file_content: bytes, metadata: dict = None) -> dict:
        """Encrypt file content using envelope encryption."""
        # Generate data key
        data_key = self.generate_data_key()
        
        # Create Fernet cipher with data key
        f = Fernet(base64.urlsafe_b64encode(data_key['plaintext_key']))
        
        # Encrypt file content
        encrypted_content = f.encrypt(file_content)
        
        # Clear plaintext key from memory
        data_key['plaintext_key'] = b'\x00' * len(data_key['plaintext_key'])
        
        # Calculate checksums
        original_checksum = hashlib.sha256(file_content).hexdigest()
        
        return {
            'encrypted_content': encrypted_content,
            'encrypted_data_key': base64.b64encode(data_key['encrypted_key']).decode(),
            'original_checksum': original_checksum,
            'encryption_version': '1.0',
            'algorithm': 'AES-256-GCM+Fernet',
            'metadata': metadata or {}
        }
    
    def decrypt_file(self, encrypted_content: bytes, 
                     encrypted_data_key: str) -> bytes:
        """Decrypt file content using envelope encryption."""
        # Decrypt data key using KMS
        encrypted_key_bytes = base64.b64decode(encrypted_data_key)
        
        response = self.kms_client.decrypt(
            CiphertextBlob=encrypted_key_bytes
        )
        plaintext_key = response['Plaintext']
        
        try:
            # Decrypt file content
            f = Fernet(base64.urlsafe_b64encode(plaintext_key))
            decrypted_content = f.decrypt(encrypted_content)
            
            return decrypted_content
        finally:
            # Clear plaintext key from memory
            plaintext_key = b'\x00' * len(plaintext_key)
    
    def upload_encrypted_file(self, bucket: str, key: str, 
                              file_content: bytes,
                              metadata: dict = None) -> dict:
        """Upload an encrypted file to S3."""
        # Encrypt file
        encryption_result = self.encrypt_file(file_content, metadata)
        
        # Prepare S3 metadata
        s3_metadata = {
            'x-amz-meta-encrypted-data-key': encryption_result['encrypted_data_key'],
            'x-amz-meta-original-checksum': encryption_result['original_checksum'],
            'x-amz-meta-encryption-version': encryption_result['encryption_version'],
            'x-amz-meta-algorithm': encryption_result['algorithm'],
            **{f'x-amz-meta-{k}': str(v) for k, v in (metadata or {}).items()}
        }
        
        # Upload to S3
        self.s3_client.put_object(
            Bucket=bucket,
            Key=key,
            Body=encryption_result['encrypted_content'],
            Metadata=s3_metadata,
            ServerSideEncryption='aws:kms',
            SSEKMSKeyId=self.kms_key_id
        )
        
        logger.info(f"Encrypted file uploaded: s3://{bucket}/{key}")
        
        return {
            'bucket': bucket,
            'key': key,
            'checksum': encryption_result['original_checksum'],
            'encrypted': True
        }
```

### 3.4 Backup Encryption

```bash
#!/bin/bash
# PostgreSQL Backup Encryption Script
# Smart Dairy Ltd. - Secure Backup Process

set -euo pipefail

# Configuration
BACKUP_DIR="/backup/postgresql"
ENCRYPTED_DIR="/backup/encrypted"
GPG_RECIPIENT="backup@smartdairybd.com"
RETENTION_DAYS=30
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="smart_dairy_backup_${DATE}.sql"

# Create backup
pg_dump -h localhost -U postgres -d smart_dairy_prod \
    --format=custom \
    --compress=9 \
    --file="${BACKUP_DIR}/${BACKUP_FILE}"

# Encrypt backup with GPG
gpg --encrypt \
    --recipient "${GPG_RECIPIENT}" \
    --trust-model always \
    --output "${ENCRYPTED_DIR}/${BACKUP_FILE}.gpg" \
    "${BACKUP_DIR}/${BACKUP_FILE}"

# Verify encryption
if gpg --list-packets "${ENCRYPTED_DIR}/${BACKUP_FILE}.gpg" > /dev/null 2>&1; then
    echo "Backup encrypted successfully"
    rm -f "${BACKUP_DIR}/${BACKUP_FILE}"
else
    echo "Encryption failed!"
    exit 1
fi

# Calculate checksum
cd "${ENCRYPTED_DIR}"
sha256sum "${BACKUP_FILE}.gpg" >> checksums.txt

# Upload to S3 with SSE-KMS
aws s3 cp "${ENCRYPTED_DIR}/${BACKUP_FILE}.gpg" \
    "s3://smart-dairy-backups/postgresql/" \
    --sse aws:kms \
    --sse-kms-key-id alias/smart-dairy-backup-key

# Clean old backups
find "${ENCRYPTED_DIR}" -name "*.gpg" -mtime +${RETENTION_DAYS} -delete

echo "Backup completed: ${BACKUP_FILE}.gpg"
```

---

## 4. ENCRYPTION IN TRANSIT

### 4.1 TLS 1.3 Implementation

All data in transit must use TLS 1.3 with strong cipher suites. TLS 1.2 is allowed as a fallback only for legacy clients.

### 4.2 Nginx TLS Configuration

```nginx
# /etc/nginx/conf.d/tls.conf
# TLS 1.3 Configuration for Smart Dairy

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name api.smartdairybd.com;
    
    # SSL Certificates
    ssl_certificate /etc/ssl/certs/smartdairy.crt;
    ssl_certificate_key /etc/ssl/private/smartdairy.key;
    
    # TLS 1.3 Only (disable older versions)
    ssl_protocols TLSv1.3;
    
    # Modern TLS 1.3 cipher suites
    ssl_ciphers TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256;
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
    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options DENY always;
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name api.smartdairybd.com;
    return 301 https://$server_name$request_uri;
}
```

### 4.3 Certificate Management

```python
"""
Certificate Management Service
Smart Dairy Ltd. - Automated Certificate Rotation
"""

import boto3
from datetime import datetime, timedelta
import logging
import subprocess

logger = logging.getLogger(__name__)


class CertificateManager:
    """
    Manages SSL/TLS certificates using Let's Encrypt and AWS ACM.
    Handles automatic renewal and deployment.
    """
    
    DOMAINS = [
        'smartdairybd.com',
        'www.smartdairybd.com',
        'api.smartdairybd.com',
        'admin.smartdairybd.com',
        'farm.smartdairybd.com'
    ]
    
    def __init__(self):
        self.acm_client = boto3.client('acm', region_name='ap-southeast-1')
        
    def obtain_certificate(self, email: str = 'admin@smartdairybd.com') -> str:
        """Obtain new certificate using Let's Encrypt with DNS challenge."""
        domains_arg = ' '.join(f'-d {d}' for d in self.DOMAINS)
        
        cmd = f'certbot certonly --dns-route53 --non-interactive --agree-tos --email {email} {domains_arg} --cert-name smartdairy'
        
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True)
        
        if result.returncode == 0:
            logger.info("Certificate obtained successfully")
            return '/etc/letsencrypt/live/smartdairy/'
        else:
            logger.error(f"Certificate acquisition failed: {result.stderr}")
            raise RuntimeError("Certificate acquisition failed")
    
    def import_to_acm(self, cert_path: str) -> str:
        """Import certificate to AWS Certificate Manager."""
        with open(f"{cert_path}/cert.pem", 'r') as f:
            certificate = f.read()
        with open(f"{cert_path}/privkey.pem", 'r') as f:
            private_key = f.read()
        with open(f"{cert_path}/chain.pem", 'r') as f:
            chain = f.read()
        
        response = self.acm_client.import_certificate(
            Certificate=certificate,
            PrivateKey=private_key,
            CertificateChain=chain,
            Tags=[
                {'Key': 'Environment', 'Value': 'Production'},
                {'Key': 'Application', 'Value': 'SmartDairy'},
                {'Key': 'ManagedBy', 'Value': 'CertManager'}
            ]
        )
        
        cert_arn = response['CertificateArn']
        logger.info(f"Certificate imported to ACM: {cert_arn}")
        
        return cert_arn
    
    def check_expiry(self, cert_arn: str) -> int:
        """Check days until certificate expiry."""
        response = self.acm_client.describe_certificate(
            CertificateArn=cert_arn
        )
        
        expiry = response['Certificate']['NotAfter']
        days_until = (expiry - datetime.now(expiry.tzinfo)).days
        
        return days_until
    
    def should_renew(self, cert_arn: str, threshold_days: int = 30) -> bool:
        """Check if certificate should be renewed."""
        days_until = self.check_expiry(cert_arn)
        return days_until <= threshold_days
```

---

## 5. FIELD-LEVEL ENCRYPTION

### 5.1 PII Field Encryption Service

```python
"""
Field-Level Encryption Service
Smart Dairy Ltd. - PII Protection
"""

from cryptography.hazmat.primitives.ciphers.aead import AESGCM
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
from cryptography.hazmat.backends import default_backend
import base64
import os
import hashlib
from typing import Optional, Dict, List
from enum import Enum
import logging

logger = logging.getLogger(__name__)


class PIIField(Enum):
    """Enumeration of PII field types."""
    EMAIL = "email"
    PHONE = "phone"
    NID = "nid"
    NAME = "name"
    ADDRESS = "address"
    DATE_OF_BIRTH = "dob"


class FieldEncryptionService:
    """
    Service for encrypting sensitive PII fields at the application level.
    Uses AES-256-GCM for authenticated encryption.
    """
    
    # Field-specific masking patterns
    MASKING_PATTERNS = {
        PIIField.EMAIL: lambda x: f"{x[:3]}{'*' * (len(x) - 6)}{x[-3:]}" if len(x) > 6 else "***",
        PIIField.PHONE: lambda x: f"{x[:4]}{'*' * (len(x) - 6)}{x[-2:]}" if len(x) > 6 else "****",
        PIIField.NID: lambda x: f"{x[:4]}{'*' * (len(x) - 8)}{x[-4:]}" if len(x) > 8 else "********",
        PIIField.NAME: lambda x: f"{x[0]}{'*' * (len(x) - 1)}",
        PIIField.ADDRESS: lambda x: f"{x[:10]}{'*' * (len(x) - 10)}" if len(x) > 10 else "**********",
    }
    
    def __init__(self, master_key: bytes, key_version: str = "1"):
        """Initialize the encryption service."""
        if len(master_key) != 32:
            raise ValueError("Master key must be 32 bytes (256 bits)")
        
        self.master_key = master_key
        self.key_version = key_version
        self._field_keys: Dict[PIIField, bytes] = {}
        self._init_field_keys()
    
    def _init_field_keys(self):
        """Derive field-specific keys from master key."""
        for field in PIIField:
            kdf = PBKDF2HMAC(
                algorithm=hashes.SHA256(),
                length=32,
                salt=field.value.encode(),
                iterations=100000,
                backend=default_backend()
            )
            self._field_keys[field] = kdf.derive(self.master_key)
    
    def encrypt_field(self, field_type: PIIField, plaintext: str,
                     associated_data: Optional[bytes] = None) -> str:
        """Encrypt a single PII field."""
        if not plaintext:
            return ""
        
        # Generate random nonce (12 bytes for GCM)
        nonce = os.urandom(12)
        
        # Get field-specific key
        key = self._field_keys[field_type]
        
        # Prepare associated data
        aad = associated_data or field_type.value.encode()
        
        # Encrypt
        aesgcm = AESGCM(key)
        ciphertext = aesgcm.encrypt(
            nonce,
            plaintext.encode('utf-8'),
            aad
        )
        
        # Format: version:nonce:ciphertext (all base64 encoded)
        encrypted_package = (
            f"{self.key_version}:"
            f"{base64.b64encode(nonce).decode()}:"
            f"{base64.b64encode(ciphertext).decode()}"
        )
        
        return encrypted_package
    
    def decrypt_field(self, field_type: PIIField, ciphertext: str,
                     associated_data: Optional[bytes] = None) -> str:
        """Decrypt a single PII field."""
        if not ciphertext or ':' not in ciphertext:
            return ciphertext
        
        try:
            # Parse encrypted package
            version, nonce_b64, ct_b64 = ciphertext.split(':')
            nonce = base64.b64decode(nonce_b64)
            ct = base64.b64decode(ct_b64)
            
            # Handle key version (for rotation)
            key = self._get_key_for_version(field_type, version)
            
            # Prepare associated data
            aad = associated_data or field_type.value.encode()
            
            # Decrypt
            aesgcm = AESGCM(key)
            plaintext = aesgcm.decrypt(nonce, ct, aad)
            
            return plaintext.decode('utf-8')
            
        except Exception as e:
            logger.error(f"Decryption failed for {field_type.value}: {e}")
            raise ValueError(f"Failed to decrypt field: {field_type.value}")
    
    def _get_key_for_version(self, field_type: PIIField, version: str) -> bytes:
        """Get the appropriate key for a specific version."""
        if version == self.key_version:
            return self._field_keys[field_type]
        raise ValueError(f"Unknown key version: {version}")
    
    def mask_field(self, field_type: PIIField, value: str) -> str:
        """Mask a field value for display/logging purposes."""
        if not value:
            return ""
        
        mask_func = self.MASKING_PATTERNS.get(field_type)
        if mask_func:
            return mask_func(value)
        return "*" * len(value)
    
    def get_searchable_hash(self, field_type: PIIField, value: str) -> str:
        """Generate a deterministic hash for searchable encrypted fields."""
        key = self._field_keys[field_type]
        data = f"{field_type.value}:{value.lower().strip()}".encode()
        return hashlib.sha256(key + data).hexdigest()
```

### 5.2 Tokenization Service for Payment Data

```python
"""
Payment Data Tokenization Service
Smart Dairy Ltd. - PCI DSS Compliance
"""

import hashlib
import hmac
import secrets
import string
from datetime import datetime, timedelta
from typing import Optional, Dict, Tuple
import redis
import logging

logger = logging.getLogger(__name__)


class PaymentTokenizationService:
    """
    Tokenization service for payment card data.
    Replaces sensitive card data with non-sensitive tokens.
    """
    
    TOKEN_PREFIX = "SD"
    FORMAT_PRESERVING = "fp"
    RANDOM = "rnd"
    DETERMINISTIC = "det"
    
    def __init__(self, vault_key: str, redis_host: str = 'localhost'):
        self.vault_key = vault_key
        self.redis = redis.Redis(
            host=redis_host,
            port=6379,
            db=0,
            decode_responses=True
        )
        
    def tokenize_card(self, card_number: str, expiry_month: str,
                     expiry_year: str, cvv: str,
                     cardholder_name: str,
                     token_type: str = FORMAT_PRESERVING) -> Dict:
        """Tokenize payment card data."""
        # Validate card number (Luhn check)
        if not self._validate_card_number(card_number):
            raise ValueError("Invalid card number")
        
        # Generate token
        token = self._generate_token(card_number, token_type)
        
        # Store in secure vault
        vault_data = {
            'card_number': self._encrypt_for_vault(card_number),
            'expiry_month': expiry_month,
            'expiry_year': expiry_year,
            'cvv': self._encrypt_for_vault(cvv),
            'cardholder_name': cardholder_name,
            'card_bin': card_number[:6],
            'card_last4': card_number[-4:],
            'created_at': datetime.utcnow().isoformat(),
            'token_type': token_type
        }
        
        # Store with TTL
        vault_key = f"token:{token}"
        self.redis.hset(vault_key, mapping=vault_data)
        self.redis.expire(vault_key, 63072000)  # 2 years
        
        # Remove CVV immediately (PCI requirement)
        self.redis.hdel(vault_key, 'cvv')
        
        logger.info(f"Card tokenized: {token[:8]}...")
        
        return {
            'token': token,
            'card_bin': vault_data['card_bin'],
            'card_last4': vault_data['card_last4'],
            'expiry_month': expiry_month,
            'expiry_year': expiry_year,
            'cardholder_name': cardholder_name,
            'token_type': token_type,
            'created_at': vault_data['created_at']
        }
    
    def detokenize_card(self, token: str) -> Optional[Dict]:
        """Retrieve original card data from token. RESTRICTED USE."""
        vault_key = f"token:{token}"
        data = self.redis.hgetall(vault_key)
        
        if not data:
            logger.warning(f"Token not found: {token[:8]}...")
            return None
        
        return {
            'card_number': self._decrypt_from_vault(data['card_number']),
            'expiry_month': data['expiry_month'],
            'expiry_year': data['expiry_year'],
            'cardholder_name': data['cardholder_name'],
            'card_bin': data['card_bin'],
            'card_last4': data['card_last4']
        }
    
    def _generate_token(self, card_number: str, token_type: str) -> str:
        """Generate a unique token."""
        if token_type == self.FORMAT_PRESERVING:
            random_part = ''.join(secrets.choice(string.digits) for _ in range(16))
            formatted = f"{random_part[:4]}-{random_part[4:8]}-{random_part[8:12]}-{random_part[12:16]}"
            token = f"{self.TOKEN_PREFIX}-{formatted}"
        else:
            random_part = ''.join(secrets.choice(string.ascii_uppercase + string.digits) for _ in range(20))
            token = f"{self.TOKEN_PREFIX}-{random_part}"
        
        return token
    
    def _validate_card_number(self, card_number: str) -> bool:
        """Validate card number using Luhn algorithm."""
        if not card_number.isdigit() or len(card_number) < 13:
            return False
        
        digits = [int(d) for d in card_number]
        odd_digits = digits[-1::-2]
        even_digits = digits[-2::-2]
        
        checksum = sum(odd_digits)
        for d in even_digits:
            checksum += sum(divmod(d * 2, 10))
        
        return checksum % 10 == 0
    
    def _encrypt_for_vault(self, data: str) -> str:
        """Encrypt data for vault storage."""
        return hashlib.sha256(f"{self.vault_key}:{data}".encode()).hexdigest()
    
    def _decrypt_from_vault(self, encrypted: str) -> str:
        """Decrypt data from vault."""
        return encrypted
```

---

## 6. KEY MANAGEMENT

### 6.1 AWS KMS Integration

```python
"""
AWS Key Management Service Integration
Smart Dairy Ltd. - Centralized Key Management
"""

import boto3
from botocore.exceptions import ClientError
from datetime import datetime, timedelta
from typing import Optional, Dict, List
import json
import logging

logger = logging.getLogger(__name__)


class AWSKMSManager:
    """Manages encryption keys using AWS KMS."""
    
    def __init__(self, region: str = 'ap-southeast-1'):
        self.kms_client = boto3.client('kms', region_name=region)
    
    def create_key(self, alias: str, description: str,
                  key_usage: str = 'ENCRYPT_DECRYPT',
                  key_spec: str = 'SYMMETRIC_DEFAULT') -> str:
        """Create a new KMS key."""
        tags = [
            {'TagKey': 'Application', 'TagValue': 'SmartDairy'},
            {'TagKey': 'Environment', 'TagValue': 'Production'},
            {'TagKey': 'ManagedBy', 'TagValue': 'KMSManager'}
        ]
        
        try:
            response = self.kms_client.create_key(
                Description=description,
                KeyUsage=key_usage,
                KeySpec=key_spec,
                Tags=tags,
                Origin='AWS_KMS'
            )
            
            key_id = response['KeyMetadata']['KeyId']
            
            # Create alias
            self.kms_client.create_alias(
                AliasName=alias,
                TargetKeyId=key_id
            )
            
            # Enable automatic rotation
            self.kms_client.enable_key_rotation(KeyId=key_id)
            
            logger.info(f"Created KMS key: {key_id} with alias {alias}")
            return key_id
            
        except ClientError as e:
            logger.error(f"Failed to create KMS key: {e}")
            raise
    
    def encrypt(self, key_id: str, plaintext: bytes,
                context: Optional[Dict[str, str]] = None) -> bytes:
        """Encrypt data using KMS key."""
        try:
            params = {'KeyId': key_id, 'Plaintext': plaintext}
            if context:
                params['EncryptionContext'] = context
            
            response = self.kms_client.encrypt(**params)
            return response['CiphertextBlob']
            
        except ClientError as e:
            logger.error(f"Encryption failed: {e}")
            raise
    
    def decrypt(self, ciphertext: bytes,
                context: Optional[Dict[str, str]] = None) -> bytes:
        """Decrypt data using KMS."""
        try:
            params = {'CiphertextBlob': ciphertext}
            if context:
                params['EncryptionContext'] = context
            
            response = self.kms_client.decrypt(**params)
            return response['Plaintext']
            
        except ClientError as e:
            logger.error(f"Decryption failed: {e}")
            raise
    
    def generate_data_key(self, key_id: str, key_spec: str = 'AES_256') -> Dict:
        """Generate a data key for envelope encryption."""
        try:
            response = self.kms_client.generate_data_key(
                KeyId=key_id,
                KeySpec=key_spec
            )
            
            return {
                'plaintext_key': response['Plaintext'],
                'encrypted_key': response['CiphertextBlob']
            }
            
        except ClientError as e:
            logger.error(f"Data key generation failed: {e}")
            raise
```

### 6.2 HashiCorp Vault Integration

```python
"""
HashiCorp Vault Integration
Smart Dairy Ltd. - Secrets Management
"""

import hvac
from hvac.exceptions import VaultError
from typing import Optional, Dict, Any
import logging

logger = logging.getLogger(__name__)


class VaultManager:
    """Manages secrets using HashiCorp Vault."""
    
    def __init__(self, url: str, token: Optional[str] = None):
        self.client = hvac.Client(url=url)
        if token:
            self.client.token = token
        
        if not self.client.is_authenticated():
            raise VaultError("Failed to authenticate with Vault")
    
    def store_secret(self, path: str, secret: Dict[str, Any],
                     mount_point: str = 'secret') -> None:
        """Store a secret in Vault."""
        try:
            self.client.secrets.kv.v2.create_or_update_secret(
                path=path,
                secret=secret,
                mount_point=mount_point
            )
            logger.info(f"Secret stored at {mount_point}/{path}")
            
        except VaultError as e:
            logger.error(f"Failed to store secret: {e}")
            raise
    
    def retrieve_secret(self, path: str,
                       mount_point: str = 'secret') -> Optional[Dict]:
        """Retrieve a secret from Vault."""
        try:
            response = self.client.secrets.kv.v2.read_secret_version(
                path=path,
                mount_point=mount_point
            )
            return response['data']['data']
            
        except VaultError as e:
            logger.error(f"Failed to retrieve secret: {e}")
            return None
    
    def get_database_credentials(self, role: str,
                                  mount_point: str = 'database') -> Dict:
        """Get dynamic database credentials from Vault."""
        try:
            response = self.client.secrets.database.generate_credentials(
                name=role,
                mount_point=mount_point
            )
            
            return {
                'username': response['data']['username'],
                'password': response['data']['password'],
                'lease_id': response['lease_id'],
                'lease_duration': response['lease_duration'],
                'renewable': response['renewable']
            }
            
        except VaultError as e:
            logger.error(f"Failed to get database credentials: {e}")
            raise
    
    def transit_encrypt(self, key_name: str, plaintext: str) -> str:
        """Encrypt data using Vault's Transit engine."""
        try:
            response = self.client.secrets.transit.encrypt(
                name=key_name,
                plaintext=plaintext
            )
            return response['data']['ciphertext']
            
        except VaultError as e:
            logger.error(f"Transit encryption failed: {e}")
            raise
    
    def transit_decrypt(self, key_name: str, ciphertext: str) -> str:
        """Decrypt data using Vault's Transit engine."""
        try:
            response = self.client.secrets.transit.decrypt(
                name=key_name,
                ciphertext=ciphertext
            )
            return response['data']['plaintext']
            
        except VaultError as e:
            logger.error(f"Transit decryption failed: {e}")
            raise
```

### 6.3 Key Rotation Procedures

```python
"""
Key Rotation Service
Smart Dairy Ltd. - Automated Key Rotation
"""

from datetime import datetime, timedelta
from typing import List, Dict, Callable, Optional
import threading
import schedule
import time
import logging

logger = logging.getLogger(__name__)


class KeyRotationService:
    """Manages automated key rotation for all encryption keys."""
    
    def __init__(self):
        self.rotation_jobs: List[Dict] = []
        self.running = False
        self.thread: Optional[threading.Thread] = None
    
    def register_kms_key_rotation(self, kms_manager, key_id: str,
                                   rotation_days: int = 90) -> None:
        """Register an AWS KMS key for automatic rotation."""
        try:
            kms_manager.kms_client.enable_key_rotation(KeyId=key_id)
            
            self.rotation_jobs.append({
                'type': 'kms',
                'manager': kms_manager,
                'key_id': key_id,
                'schedule': rotation_days,
                'last_check': datetime.utcnow()
            })
            
            logger.info(f"Registered KMS key rotation for {key_id}")
            
        except Exception as e:
            logger.error(f"Failed to register KMS key rotation: {e}")
            raise
    
    def register_vault_key_rotation(self, vault_manager, key_name: str,
                                     rotation_days: int = 90) -> None:
        """Register a Vault Transit key for rotation."""
        job = {
            'type': 'vault',
            'manager': vault_manager,
            'key_name': key_name,
            'schedule': rotation_days,
            'next_rotation': datetime.utcnow() + timedelta(days=rotation_days),
            'rotate_func': self._rotate_vault_key
        }
        self.rotation_jobs.append(job)
        logger.info(f"Registered Vault key rotation for {key_name}")
    
    def register_application_key_rotation(self, key_name: str,
                                          current_key: bytes,
                                          rotation_func: Callable,
                                          rotation_days: int = 90) -> None:
        """Register an application-level key for rotation."""
        job = {
            'type': 'application',
            'key_name': key_name,
            'current_key': current_key,
            'schedule': rotation_days,
            'next_rotation': datetime.utcnow() + timedelta(days=rotation_days),
            'rotate_func': rotation_func
        }
        self.rotation_jobs.append(job)
        logger.info(f"Registered application key rotation for {key_name}")
    
    def _rotate_vault_key(self, job: Dict) -> None:
        """Execute Vault Transit key rotation."""
        try:
            vault = job['manager']
            key_name = job['key_name']
            
            # Rotate key (creates new key version)
            vault.client.secrets.transit.rotate_key(name=key_name)
            
            job['next_rotation'] = datetime.utcnow() + timedelta(days=job['schedule'])
            logger.info(f"Rotated Vault key: {key_name}")
            
        except Exception as e:
            logger.error(f"Vault key rotation failed: {e}")
    
    def check_and_rotate(self) -> None:
        """Check all keys and rotate if needed."""
        now = datetime.utcnow()
        
        for job in self.rotation_jobs:
            try:
                if job['type'] == 'kms':
                    key_id = job['key_id']
                    response = job['manager'].kms_client.get_key_rotation_status(KeyId=key_id)
                    if not response['KeyRotationEnabled']:
                        logger.warning(f"KMS rotation disabled for {key_id}, re-enabling")
                        job['manager'].kms_client.enable_key_rotation(KeyId=key_id)
                    job['last_check'] = now
                    
                elif job['type'] in ['vault', 'application']:
                    if now >= job['next_rotation']:
                        job['rotate_func'](job)
                        
            except Exception as e:
                logger.error(f"Rotation check failed: {e}")
    
    def start_scheduler(self) -> None:
        """Start the rotation scheduler in a background thread."""
        self.running = True
        schedule.every().day.at("02:00").do(self.check_and_rotate)
        
        def run_scheduler():
            while self.running:
                schedule.run_pending()
                time.sleep(60)
        
        self.thread = threading.Thread(target=run_scheduler, daemon=True)
        self.thread.start()
        logger.info("Key rotation scheduler started")
    
    def stop_scheduler(self) -> None:
        """Stop the rotation scheduler."""
        self.running = False
        if self.thread:
            self.thread.join(timeout=5)
        logger.info("Key rotation scheduler stopped")
    
    def emergency_rotation(self, key_identifier: str) -> bool:
        """Execute emergency key rotation for a specific key."""
        for job in self.rotation_jobs:
            if (job.get('key_name') == key_identifier or 
                job.get('key_id') == key_identifier):
                try:
                    if 'rotate_func' in job:
                        job['rotate_func'](job)
                    logger.warning(f"Emergency rotation executed for {key_identifier}")
                    return True
                except Exception as e:
                    logger.error(f"Emergency rotation failed: {e}")
                    return False
        
        logger.error(f"Key not found for emergency rotation: {key_identifier}")
        return False
```

---

## 7. DATA MASKING

### 7.1 Dynamic Data Masking Service

```python
"""
Dynamic Data Masking Utilities
Smart Dairy Ltd. - Data Protection for Non-Production
"""

import re
import hashlib
import random
from typing import Optional, Dict, Callable, List
from enum import Enum
from dataclasses import dataclass
import faker


class MaskingType(Enum):
    """Types of data masking."""
    FULL = "full"
    PARTIAL = "partial"
    EMAIL = "email"
    CREDIT_CARD = "credit_card"
    PHONE = "phone"
    HASH = "hash"
    RANDOM = "random"
    NULL = "null"


@dataclass
class MaskingRule:
    """Configuration for a masking rule."""
    field_name: str
    masking_type: MaskingType
    preserve_format: bool = True
    custom_pattern: Optional[str] = None


class DataMaskingService:
    """Service for dynamically masking sensitive data."""
    
    def __init__(self, locale: str = 'en_US'):
        self.fake = faker.Faker(locale)
        self.faker_bangladesh = faker.Faker('bn_BD')
        self._masking_functions: Dict[MaskingType, Callable] = {
            MaskingType.FULL: self._mask_full,
            MaskingType.PARTIAL: self._mask_partial,
            MaskingType.EMAIL: self._mask_email,
            MaskingType.CREDIT_CARD: self._mask_credit_card,
            MaskingType.PHONE: self._mask_phone,
            MaskingType.HASH: self._mask_hash,
            MaskingType.RANDOM: self._mask_random,
            MaskingType.NULL: self._mask_null,
        }
    
    def mask_value(self, value: any, rule: MaskingRule) -> any:
        """Mask a single value according to the rule."""
        if value is None:
            return None
        
        mask_func = self._masking_functions.get(rule.masking_type)
        if not mask_func:
            return value
        
        return mask_func(value, rule)
    
    def _mask_full(self, value: any, rule: MaskingRule) -> str:
        """Complete masking with asterisks."""
        if isinstance(value, str):
            return '*' * len(value)
        return '****'
    
    def _mask_partial(self, value: str, rule: MaskingRule) -> str:
        """Partial masking - show first/last chars."""
        if len(value) <= 4:
            return '*' * len(value)
        
        visible_chars = min(3, len(value) // 4)
        return (
            value[:visible_chars] + 
            '*' * (len(value) - 2 * visible_chars) + 
            value[-visible_chars:]
        )
    
    def _mask_email(self, value: str, rule: MaskingRule) -> str:
        """Email-specific masking."""
        if '@' not in value:
            return self._mask_partial(value, rule)
        
        local, domain = value.split('@', 1)
        if len(local) <= 2:
            masked_local = '*' * len(local)
        else:
            masked_local = local[0] + '*' * (len(local) - 2) + local[-1]
        
        return f"{masked_local}@{domain}"
    
    def _mask_credit_card(self, value: str, rule: MaskingRule) -> str:
        """Credit card masking - show last 4 digits only."""
        digits = re.sub(r'\D', '', str(value))
        if len(digits) < 13:
            return '*' * len(str(value))
        
        return f"****-****-****-{digits[-4:]}"
    
    def _mask_phone(self, value: str, rule: MaskingRule) -> str:
        """Phone number masking for Bangladesh format."""
        digits = re.sub(r'\D', '', str(value))
        
        if len(digits) == 11 and digits.startswith('01'):
            # Bangladesh mobile: 01XX-XXX-XXXX
            return f"{digits[:4]}-****-{digits[-4:]}"
        elif len(digits) == 13 and digits.startswith('880'):
            # With country code: +880-1XXX-XXX-XXX
            return f"+880-{digits[3:5]}**-***-{digits[-4:]}"
        else:
            if len(digits) > 8:
                return digits[:3] + '*' * (len(digits) - 6) + digits[-3:]
            return '*' * len(str(value))
    
    def _mask_hash(self, value: any, rule: MaskingRule) -> str:
        """Deterministic hashing for consistency."""
        if isinstance(value, str):
            value = value.encode()
        return hashlib.sha256(value).hexdigest()[:16]
    
    def _mask_random(self, value: any, rule: MaskingRule) -> any:
        """Replace with random fake data."""
        if isinstance(value, str):
            if '@' in value:
                return self.fake.email()
            elif re.match(r'^\+?\d', value):
                return self.fake.phone_number()
            elif re.match(r'^\d{10,17}$', re.sub(r'\D', '', value)):
                return ''.join(random.choices('0123456789', k=len(value)))
            else:
                return self.fake.name()
        return value
    
    def _mask_null(self, value: any, rule: MaskingRule) -> None:
        """Replace with NULL."""
        return None


class DatabaseMasker:
    """Database-level data masking for non-production environments."""
    
    def __init__(self, masking_service: DataMaskingService):
        self.masking = masking_service
        self.rules: Dict[str, List[MaskingRule]] = self._default_rules()
    
    def _default_rules(self) -> Dict[str, List[MaskingRule]]:
        """Default masking rules for Smart Dairy tables."""
        return {
            'res_partner': [
                MaskingRule('name', MaskingType.RANDOM),
                MaskingRule('email', MaskingType.EMAIL),
                MaskingRule('phone', MaskingType.PHONE),
                MaskingRule('mobile', MaskingType.PHONE),
                MaskingRule('street', MaskingType.RANDOM),
                MaskingRule('street2', MaskingType.RANDOM),
                MaskingRule('vat', MaskingType.FULL),
            ],
            'res_users': [
                MaskingRule('login', MaskingType.EMAIL),
                MaskingRule('password', MaskingType.FULL),
            ],
            'payment_token': [
                MaskingRule('token', MaskingType.FULL),
                MaskingRule('card_number', MaskingType.CREDIT_CARD),
            ],
            'hr_employee': [
                MaskingRule('name', MaskingType.RANDOM),
                MaskingRule('work_email', MaskingType.EMAIL),
                MaskingRule('work_phone', MaskingType.PHONE),
                MaskingRule('private_email', MaskingType.EMAIL),
                MaskingRule('private_phone', MaskingType.PHONE),
                MaskingRule('nid', MaskingType.HASH),
                MaskingRule('bank_account_id', MaskingType.FULL),
            ],
            'smart_dairy_farmer': [
                MaskingRule('contact_name', MaskingType.RANDOM),
                MaskingRule('contact_phone', MaskingType.PHONE),
                MaskingRule('farm_location_gps', MaskingType.FULL),
            ],
        }
    
    def mask_record(self, table: str, record: Dict) -> Dict:
        """Apply masking rules to a database record."""
        rules = self.rules.get(table, [])
        masked = record.copy()
        
        for rule in rules:
            if rule.field_name in masked:
                masked[rule.field_name] = self.masking.mask_value(
                    masked[rule.field_name], rule
                )
        
        return masked
```

---

## 8. DATA LOSS PREVENTION (DLP)

### 8.1 DLP Policy Framework

```python
"""
Data Loss Prevention (DLP) Service
Smart Dairy Ltd. - Preventing Data Exfiltration
"""

import re
from typing import List, Dict, Optional, Set
from enum import Enum
from dataclasses import dataclass
import json
import logging

logger = logging.getLogger(__name__)


class DLPAction(Enum):
    """Actions that DLP can take."""
    ALLOW = "allow"
    LOG = "log"
    WARN = "warn"
    BLOCK = "block"
    QUARANTINE = "quarantine"


class DLPSeverity(Enum):
    """Severity levels for DLP violations."""
    LOW = 1
    MEDIUM = 2
    HIGH = 3
    CRITICAL = 4


@dataclass
class DLPViolation:
    """Represents a DLP policy violation."""
    policy_name: str
    severity: DLPSeverity
    action: DLPAction
    data_type: str
    detected_in: str
    details: str
    timestamp: str


class DLPEngine:
    """Data Loss Prevention engine for detecting unauthorized data exfiltration."""
    
    # Patterns for PII detection
    PII_PATTERNS = {
        'bangladesh_phone': re.compile(r'(?:\+?88)?01[3-9]\d{8}'),
        'email': re.compile(r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b'),
        'nid_bangladesh': re.compile(r'\b\d{10,17}\b'),
        'credit_card': re.compile(r'\b(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14})\b'),
    }
    
    def __init__(self):
        self.policies: List[Dict] = []
    
    def add_policy(self, name: str, description: str,
                   conditions: List[Dict], action: DLPAction,
                   severity: DLPSeverity = DLPSeverity.MEDIUM) -> None:
        """Add a DLP policy."""
        self.policies.append({
            'name': name,
            'description': description,
            'conditions': conditions,
            'action': action,
            'severity': severity
        })
        logger.info(f"Added DLP policy: {name}")
    
    def scan_content(self, content: str, context: str = "",
                    source_ip: str = "") -> List[DLPViolation]:
        """Scan content for policy violations."""
        violations = []
        from datetime import datetime
        
        for policy in self.policies:
            if self._check_policy(content, policy):
                violation = DLPViolation(
                    policy_name=policy['name'],
                    severity=policy['severity'],
                    action=policy['action'],
                    data_type=self._identify_data_type(content),
                    detected_in=context,
                    details=f"Source: {source_ip}",
                    timestamp=datetime.utcnow().isoformat()
                )
                violations.append(violation)
                self._log_violation(violation)
        
        # Additional PII scanning
        pii_violations = self._scan_for_pii(content, context, source_ip)
        violations.extend(pii_violations)
        
        return violations
    
    def _check_policy(self, content: str, policy: Dict) -> bool:
        """Check if content matches a policy."""
        for condition in policy['conditions']:
            condition_type = condition.get('type')
            
            if condition_type == 'regex':
                pattern = re.compile(condition['pattern'])
                if pattern.search(content):
                    return True
                    
            elif condition_type == 'keyword':
                keyword = condition['keyword'].lower()
                if keyword in content.lower():
                    return True
                    
            elif condition_type == 'count':
                pattern = re.compile(condition['pattern'])
                matches = len(pattern.findall(content))
                threshold = condition.get('threshold', 1)
                if matches >= threshold:
                    return True
        
        return False
    
    def _scan_for_pii(self, content: str, context: str,
                     source_ip: str) -> List[DLPViolation]:
        """Scan content for PII patterns."""
        from datetime import datetime
        violations = []
        
        for pii_type, pattern in self.PII_PATTERNS.items():
            matches = pattern.findall(content)
            
            if matches:
                if len(matches) >= 10:
                    severity = DLPSeverity.CRITICAL
                elif len(matches) >= 5:
                    severity = DLPSeverity.HIGH
                else:
                    severity = DLPSeverity.MEDIUM
                
                violation = DLPViolation(
                    policy_name=f'PII_Detection_{pii_type}',
                    severity=severity,
                    action=DLPAction.BLOCK if severity.value >= 3 else DLPAction.LOG,
                    data_type=pii_type,
                    detected_in=context,
                    details=f"Found {len(matches)} instances from {source_ip}",
                    timestamp=datetime.utcnow().isoformat()
                )
                violations.append(violation)
        
        return violations
    
    def _identify_data_type(self, content: str) -> str:
        """Identify the type of data in content."""
        for pii_type, pattern in self.PII_PATTERNS.items():
            if pattern.search(content):
                return pii_type
        return "unknown"
    
    def _log_violation(self, violation: DLPViolation) -> None:
        """Log a DLP violation."""
        log_entry = {
            'timestamp': violation.timestamp,
            'policy': violation.policy_name,
            'severity': violation.severity.name,
            'action': violation.action.value,
            'data_type': violation.data_type,
            'context': violation.detected_in,
            'details': violation.details
        }
        
        logger.warning(f"DLP Violation: {json.dumps(log_entry)}")


class DLPRequestInterceptor:
    """Intercepts HTTP requests/responses for DLP scanning."""
    
    def __init__(self, dlp_engine: DLPEngine):
        self.dlp = dlp_engine
        self.max_scan_size = 10 * 1024 * 1024  # 10MB
    
    def scan_request(self, request) -> Optional[Dict]:
        """Scan incoming request for policy violations."""
        content = self._extract_content(request)
        
        if len(content) > self.max_scan_size:
            logger.warning(f"Request too large to scan: {len(content)} bytes")
            return None
        
        violations = self.dlp.scan_content(
            content,
            context=f"{request.method} {request.path}",
            source_ip=request.remote_addr
        )
        
        critical_violations = [v for v in violations 
                              if v.severity == DLPSeverity.CRITICAL]
        
        if critical_violations:
            return {
                'error': 'Request blocked by DLP policy',
                'code': 'DLP_BLOCKED',
                'details': [v.policy_name for v in critical_violations]
            }
        
        return None
    
    def _extract_content(self, request) -> str:
        """Extract text content from request."""
        content = ""
        
        if hasattr(request, 'args') and request.args:
            content += str(request.args)
        if hasattr(request, 'form') and request.form:
            content += str(request.form)
        if hasattr(request, 'is_json') and request.is_json:
            content += str(request.json)
        if hasattr(request, 'data') and request.data:
            try:
                content += request.data.decode('utf-8')
            except:
                pass
        
        return content
```

---

## 9. DATABASE SECURITY

### 9.1 PostgreSQL Transparent Data Encryption (TDE)

```sql
-- PostgreSQL TDE Implementation
-- Requires pgcrypto extension and proper filesystem encryption

-- 1. Enable pgcrypto extension
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- 2. Create encryption functions
CREATE OR REPLACE FUNCTION encrypt_column(
    p_data TEXT,
    p_key TEXT
) RETURNS BYTEA AS $$
BEGIN
    RETURN pgp_sym_encrypt(
        p_data,
        p_key,
        'cipher-algo=aes256, compress-algo=2'
    );
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

CREATE OR REPLACE FUNCTION decrypt_column(
    p_data BYTEA,
    p_key TEXT
) RETURNS TEXT AS $$
BEGIN
    RETURN pgp_sym_decrypt(p_data, p_key);
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- 3. Create table with encrypted columns
CREATE TABLE customer_pii (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Encrypted PII fields
    email_encrypted BYTEA,
    phone_encrypted BYTEA,
    nid_encrypted BYTEA,
    address_encrypted BYTEA,
    
    -- Searchable hashes (HMAC)
    email_hash VARCHAR(64),
    phone_hash VARCHAR(64),
    nid_hash VARCHAR(64),
    
    CONSTRAINT unique_email_hash UNIQUE (email_hash)
);

-- 4. Create index on hashes for lookups
CREATE INDEX idx_customer_pii_email ON customer_pii(email_hash);
CREATE INDEX idx_customer_pii_phone ON customer_pii(phone_hash);

-- 5. Row-Level Security (RLS) policies
ALTER TABLE customer_pii ENABLE ROW LEVEL SECURITY;

CREATE POLICY customer_pii_access_policy ON customer_pii
    FOR ALL
    TO application_user
    USING (
        customer_id IN (
            SELECT id FROM res_partner 
            WHERE company_id = current_setting('app.current_company_id')::INTEGER
        )
    );

-- 6. Audit logging trigger
CREATE TABLE audit_log (
    id SERIAL PRIMARY KEY,
    table_name VARCHAR(100),
    record_id INTEGER,
    action VARCHAR(10),
    old_values JSONB,
    new_values JSONB,
    changed_by INTEGER,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address INET
);

CREATE OR REPLACE FUNCTION audit_trigger_func()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        INSERT INTO audit_log (table_name, record_id, action, old_values, changed_by)
        VALUES (TG_TABLE_NAME, OLD.id, 'DELETE', row_to_json(OLD), 
                current_setting('app.current_user_id')::INTEGER);
        RETURN OLD;
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO audit_log (table_name, record_id, action, old_values, new_values, changed_by)
        VALUES (TG_TABLE_NAME, NEW.id, 'UPDATE', row_to_json(OLD), row_to_json(NEW),
                current_setting('app.current_user_id')::INTEGER);
        RETURN NEW;
    ELSIF TG_OP = 'INSERT' THEN
        INSERT INTO audit_log (table_name, record_id, action, new_values, changed_by)
        VALUES (TG_TABLE_NAME, NEW.id, 'INSERT', row_to_json(NEW),
                current_setting('app.current_user_id')::INTEGER);
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- Apply audit trigger to sensitive tables
CREATE TRIGGER customer_pii_audit
AFTER INSERT OR UPDATE OR DELETE ON customer_pii
FOR EACH ROW EXECUTE FUNCTION audit_trigger_func();
```

### 9.2 Column-Level Encryption Access Control

```python
"""
Database Column Encryption Access Control
Smart Dairy Ltd. - Fine-grained access to encrypted columns
"""

from contextlib import contextmanager
from typing import Optional, List, Dict
import psycopg2
from psycopg2.extras import RealDictCursor
import os


class EncryptedColumnAccess:
    """Manages access to encrypted database columns."""
    
    def __init__(self, connection_params: Dict):
        self.connection_params = connection_params
        self.encryption_key = os.environ.get('DB_ENCRYPTION_KEY')
    
    @contextmanager
    def _get_connection(self, user_context: Dict):
        """Get database connection with user context set."""
        conn = psycopg2.connect(**self.connection_params)
        cursor = conn.cursor()
        
        try:
            # Set application context for RLS
            cursor.execute(
                "SELECT set_config('app.current_user_id', %s, false)",
                (str(user_context.get('user_id', 0)),)
            )
            cursor.execute(
                "SELECT set_config('app.current_company_id', %s, false)",
                (str(user_context.get('company_id', 0)),)
            )
            cursor.execute(
                "SELECT set_config('app.encryption_key', %s, false)",
                (self.encryption_key,)
            )
            
            yield conn, cursor
            
        finally:
            cursor.close()
            conn.close()
    
    def get_customer_pii(self, customer_id: int, 
                         user_context: Dict,
                         fields: Optional[List[str]] = None) -> Optional[Dict]:
        """Retrieve customer PII with decryption."""
        # Check permissions
        if not self._check_permission(user_context, 'read_pii'):
            raise PermissionError("User not authorized to read PII")
        
        allowed_fields = fields or ['email', 'phone', 'nid', 'address']
        
        with self._get_connection(user_context) as (conn, cursor):
            select_parts = ['id', 'customer_id']
            for field in allowed_fields:
                select_parts.append(
                    f"decrypt_column({field}_encrypted, current_setting('app.encryption_key')) as {field}"
                )
            
            query = f"""
                SELECT {', '.join(select_parts)}
                FROM customer_pii
                WHERE customer_id = %s
            """
            
            cursor.execute(query, (customer_id,))
            result = cursor.fetchone()
            
            if result:
                return dict(result)
            return None
    
    def update_customer_pii(self, customer_id: int,
                           data: Dict,
                           user_context: Dict) -> bool:
        """Update customer PII with encryption."""
        if not self._check_permission(user_context, 'write_pii'):
            raise PermissionError("User not authorized to modify PII")
        
        with self._get_connection(user_context) as (conn, cursor):
            updates = []
            params = []
            
            for field, value in data.items():
                if field in ['email', 'phone', 'nid', 'address']:
                    updates.append(f"""
                        {field}_encrypted = encrypt_column(%s, current_setting('app.encryption_key')),
                        {field}_hash = encode(digest(%s, 'sha256'), 'hex')
                    """)
                    params.extend([value, value])
            
            if updates:
                query = f"""
                    UPDATE customer_pii
                    SET {', '.join(updates)}, updated_at = CURRENT_TIMESTAMP
                    WHERE customer_id = %s
                """
                params.append(customer_id)
                
                cursor.execute(query, params)
                conn.commit()
                
                self._log_access(user_context, 'UPDATE', customer_id, list(data.keys()))
                return True
            
            return False
    
    def search_by_email(self, email: str, user_context: Dict) -> List[Dict]:
        """Search customers by email using hash (no decryption needed)."""
        import hashlib
        email_hash = hashlib.sha256(email.lower().encode()).hexdigest()
        
        with self._get_connection(user_context) as (conn, cursor):
            cursor.execute("""
                SELECT customer_id 
                FROM customer_pii 
                WHERE email_hash = %s
            """, (email_hash,))
            
            return [row[0] for row in cursor.fetchall()]
    
    def _check_permission(self, user_context: Dict, permission: str) -> bool:
        """Check if user has required permission."""
        user_roles = user_context.get('roles', [])
        
        permission_map = {
            'read_pii': ['admin', 'customer_service', 'data_admin'],
            'write_pii': ['admin', 'data_admin'],
            'delete_pii': ['admin', 'data_admin'],
        }
        
        allowed_roles = permission_map.get(permission, [])
        return any(role in allowed_roles for role in user_roles)
    
    def _log_access(self, user_context: Dict, action: str, 
                   record_id: int, fields: List[str]) -> None:
        """Log access to sensitive data."""
        pass  # Implementation for audit logging
```

---

## 10. BACKUP ENCRYPTION

### 10.1 Backup Encryption Strategy

All backups must be encrypted using a multi-layered approach:

1. **Database dumps**: Encrypted using GPG with AES-256
2. **File backups**: Client-side encryption before S3 upload
3. **Key protection**: Backup encryption keys stored in AWS KMS / HashiCorp Vault
4. **Retention**: Encrypted backups retained according to data classification

### 10.2 Backup Encryption Scripts

```python
"""
Backup Encryption Service
Smart Dairy Ltd. - Secure Backup Management
"""

import subprocess
import os
import hashlib
from datetime import datetime
from typing import Optional, Dict, List
import boto3
from pathlib import Path
import logging

logger = logging.getLogger(__name__)


class BackupEncryptionService:
    """Manages encrypted backups for Smart Dairy databases."""
    
    def __init__(self, config: Dict):
        self.config = config
        self.gpg_key_id = config.get('gpg_key_id')
        self.backup_dir = Path(config.get('backup_dir', '/backup'))
        self.retention_days = config.get('retention_days', 30)
        self.s3_bucket = config.get('s3_bucket')
        self.s3_client = boto3.client('s3') if self.s3_bucket else None
    
    def create_encrypted_backup(self, db_name: str, 
                                backup_type: str = 'full') -> Dict:
        """Create an encrypted database backup."""
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        backup_name = f"{db_name}_{backup_type}_{timestamp}"
        
        backup_path = self.backup_dir / backup_name
        backup_path.mkdir(parents=True, exist_ok=True)
        
        try:
            # Create database dump
            dump_file = self._create_database_dump(db_name, backup_type, backup_path)
            
            # Calculate checksum before encryption
            original_checksum = self._calculate_checksum(dump_file)
            
            # Encrypt with GPG
            encrypted_file = self._encrypt_file(dump_file)
            
            # Verify encryption
            if not self._verify_encryption(encrypted_file):
                raise RuntimeError("Encryption verification failed")
            
            # Remove unencrypted dump
            os.remove(dump_file)
            
            # Calculate checksum of encrypted file
            encrypted_checksum = self._calculate_checksum(encrypted_file)
            
            # Upload to S3
            s3_key = None
            if self.s3_client:
                s3_key = self._upload_to_s3(encrypted_file, db_name)
            
            # Create metadata
            metadata = {
                'backup_name': backup_name,
                'db_name': db_name,
                'backup_type': backup_type,
                'timestamp': timestamp,
                'original_checksum': original_checksum,
                'encrypted_checksum': encrypted_checksum,
                'encrypted_file': str(encrypted_file),
                's3_key': s3_key,
                'gpg_key_id': self.gpg_key_id,
                'encryption_algorithm': 'AES-256'
            }
            
            self._save_metadata(metadata, backup_path)
            
            logger.info(f"Backup created: {backup_name}")
            return metadata
            
        except Exception as e:
            logger.error(f"Backup failed: {e}")
            self._cleanup_backup(backup_path)
            raise
    
    def _create_database_dump(self, db_name: str, backup_type: str,
                              backup_path: Path) -> Path:
        """Create PostgreSQL database dump."""
        if backup_type == 'full':
            dump_file = backup_path / f"{db_name}.dump"
            cmd = [
                'pg_dump',
                '-h', self.config.get('db_host', 'localhost'),
                '-U', self.config.get('db_user', 'postgres'),
                '-d', db_name,
                '--format=custom',
                '--compress=6',
                '-f', str(dump_file)
            ]
        else:
            dump_file = backup_path / f"{db_name}_schema.sql"
            cmd = [
                'pg_dump',
                '-h', self.config.get('db_host', 'localhost'),
                '-U', self.config.get('db_user', 'postgres'),
                '-d', db_name,
                '--schema-only',
                '-f', str(dump_file)
            ]
        
        env = os.environ.copy()
        env['PGPASSWORD'] = self.config.get('db_password', '')
        
        result = subprocess.run(cmd, capture_output=True, text=True, env=env)
        
        if result.returncode != 0:
            raise RuntimeError(f"pg_dump failed: {result.stderr}")
        
        return dump_file
    
    def _encrypt_file(self, file_path: Path) -> Path:
        """Encrypt file using GPG."""
        encrypted_path = file_path.with_suffix(file_path.suffix + '.gpg')
        
        cmd = [
            'gpg',
            '--encrypt',
            '--recipient', self.gpg_key_id,
            '--trust-model', 'always',
            '--output', str(encrypted_path),
            str(file_path)
        ]
        
        result = subprocess.run(cmd, capture_output=True, text=True)
        
        if result.returncode != 0:
            raise RuntimeError(f"GPG encryption failed: {result.stderr}")
        
        return encrypted_path
    
    def _verify_encryption(self, encrypted_file: Path) -> bool:
        """Verify that file is properly encrypted."""
        cmd = ['gpg', '--list-packets', str(encrypted_file)]
        result = subprocess.run(cmd, capture_output=True, text=True)
        return result.returncode == 0
    
    def _calculate_checksum(self, file_path: Path) -> str:
        """Calculate SHA-256 checksum."""
        sha256 = hashlib.sha256()
        with open(file_path, 'rb') as f:
            for chunk in iter(lambda: f.read(8192), b''):
                sha256.update(chunk)
        return sha256.hexdigest()
    
    def _upload_to_s3(self, file_path: Path, db_name: str) -> str:
        """Upload encrypted backup to S3."""
        s3_key = f"backups/{db_name}/{file_path.name}"
        
        self.s3_client.upload_file(
            str(file_path),
            self.s3_bucket,
            s3_key,
            ExtraArgs={
                'ServerSideEncryption': 'aws:kms',
                'StorageClass': 'STANDARD_IA'
            }
        )
        
        logger.info(f"Uploaded to S3: s3://{self.s3_bucket}/{s3_key}")
        return s3_key
    
    def _save_metadata(self, metadata: Dict, backup_path: Path) -> None:
        """Save backup metadata."""
        import json
        metadata_file = backup_path / 'metadata.json'
        with open(metadata_file, 'w') as f:
            json.dump(metadata, f, indent=2)
    
    def restore_backup(self, backup_name: str, 
                       target_db: str,
                       verify_checksum: bool = True) -> bool:
        """Restore from an encrypted backup."""
        backup_path = self.backup_dir / backup_name
        metadata_file = backup_path / 'metadata.json'
        
        # Load metadata
        import json
        with open(metadata_file) as f:
            metadata = json.load(f)
        
        encrypted_file = Path(metadata['encrypted_file'])
        
        # Verify encrypted checksum
        if verify_checksum:
            current_checksum = self._calculate_checksum(encrypted_file)
            if current_checksum != metadata['encrypted_checksum']:
                raise ValueError("Encrypted file checksum mismatch!")
        
        # Decrypt
        decrypted_file = self._decrypt_file(encrypted_file)
        
        try:
            # Verify original checksum
            if verify_checksum:
                current_checksum = self._calculate_checksum(decrypted_file)
                if current_checksum != metadata['original_checksum']:
                    raise ValueError("Decrypted file checksum mismatch!")
            
            # Restore database
            self._restore_database(decrypted_file, target_db, 
                                   metadata['backup_type'])
            
            logger.info(f"Restored backup: {backup_name} to {target_db}")
            return True
            
        finally:
            # Cleanup decrypted file
            if decrypted_file.exists():
                os.remove(decrypted_file)
    
    def _decrypt_file(self, encrypted_file: Path) -> Path:
        """Decrypt a GPG encrypted file."""
        decrypted_path = encrypted_file.with_suffix('')
        
        cmd = [
            'gpg',
            '--decrypt',
            '--output', str(decrypted_path),
            str(encrypted_file)
        ]
        
        result = subprocess.run(cmd, capture_output=True, text=True)
        
        if result.returncode != 0:
            raise RuntimeError(f"GPG decryption failed: {result.stderr}")
        
        return decrypted_path
    
    def _restore_database(self, dump_file: Path, target_db: str,
                          backup_type: str) -> None:
        """Restore database from dump."""
        if backup_type == 'full':
            cmd = [
                'pg_restore',
                '-h', self.config.get('db_host', 'localhost'),
                '-U', self.config.get('db_user', 'postgres'),
                '-d', target_db,
                '--clean',
                '--if-exists',
                str(dump_file)
            ]
        else:
            cmd = [
                'psql',
                '-h', self.config.get('db_host', 'localhost'),
                '-U', self.config.get('db_user', 'postgres'),
                '-d', target_db,
                '-f', str(dump_file)
            ]
        
        env = os.environ.copy()
        env['PGPASSWORD'] = self.config.get('db_password', '')
        
        result = subprocess.run(cmd, capture_output=True, text=True, env=env)
        
        if result.returncode != 0:
            raise RuntimeError(f"Restore failed: {result.stderr}")
    
    def _cleanup_backup(self, backup_path: Path) -> None:
        """Cleanup failed backup files."""
        import shutil
        if backup_path.exists():
            shutil.rmtree(backup_path)
```

---

## 11. SECURE DATA DELETION

### 11.1 Data Purging and Right to Erasure

```python
"""
Secure Data Deletion Service
Smart Dairy Ltd. - GDPR-compliant Data Erasure
"""

import os
import shutil
import hashlib
import secrets
from typing import List, Dict, Optional
from datetime import datetime, timedelta
import psycopg2
from pathlib import Path
import logging

logger = logging.getLogger(__name__)


class SecureDeletionService:
    """
    Implements secure data deletion in accordance with GDPR
    right to erasure and internal data retention policies.
    """
    
    OVERWRITE_PASSES = 3  # Number of overwrite passes for files
    
    def __init__(self, db_config: Dict, vault_path: str):
        self.db_config = db_config
        self.vault_path = Path(vault_path)
    
    def delete_customer_data(self, customer_id: int, 
                            reason: str,
                            requested_by: str) -> Dict:
        """Execute secure deletion of all customer data (GDPR Article 17)."""
        deletion_report = {
            'customer_id': customer_id,
            'requested_by': requested_by,
            'reason': reason,
            'timestamp': datetime.utcnow().isoformat(),
            'deleted_tables': [],
            'anonymized_tables': [],
            'backup_references': []
        }
        
        try:
            conn = psycopg2.connect(**self.db_config)
            cursor = conn.cursor()
            
            # 1. Delete from primary customer table
            cursor.execute("""
                DELETE FROM res_partner 
                WHERE id = %s 
                RETURNING name, email
            """, (customer_id,))
            
            result = cursor.fetchone()
            if not result:
                raise ValueError(f"Customer {customer_id} not found")
            
            deletion_report['deleted_tables'].append('res_partner')
            
            # 2. Delete from PII table
            cursor.execute("""
                DELETE FROM customer_pii 
                WHERE customer_id = %s
            """, (customer_id,))
            deletion_report['deleted_tables'].append('customer_pii')
            
            # 3. Anonymize order history (retain for accounting)
            cursor.execute("""
                UPDATE sale_order 
                SET partner_id = NULL,
                    partner_invoice_id = NULL,
                    partner_shipping_id = NULL,
                    anonymized = TRUE,
                    anonymized_at = CURRENT_TIMESTAMP
                WHERE partner_id = %s
            """, (customer_id,))
            deletion_report['anonymized_tables'].append('sale_order')
            
            # 4. Delete communication history
            cursor.execute("""
                DELETE FROM mail_message 
                WHERE res_id = %s AND model = 'res.partner'
            """, (customer_id,))
            deletion_report['deleted_tables'].append('mail_message')
            
            # 5. Delete activity logs
            cursor.execute("""
                DELETE FROM mail_activity 
                WHERE res_id = %s AND res_model = 'res.partner'
            """, (customer_id,))
            deletion_report['deleted_tables'].append('mail_activity')
            
            # 6. Log deletion for audit
            cursor.execute("""
                INSERT INTO gdpr_deletion_log 
                (customer_id, deleted_at, requested_by, reason, tables_affected)
                VALUES (%s, CURRENT_TIMESTAMP, %s, %s, %s)
            """, (customer_id, requested_by, reason, 
                  str(deletion_report['deleted_tables'])))
            
            conn.commit()
            
            # 7. Schedule backup purge
            self._schedule_backup_purge(customer_id)
            
            logger.info(f"Customer {customer_id} data deleted by {requested_by}")
            
            return deletion_report
            
        except Exception as e:
            conn.rollback()
            logger.error(f"Deletion failed for customer {customer_id}: {e}")
            raise
        finally:
            cursor.close()
            conn.close()
    
    def secure_delete_file(self, file_path: str, 
                          method: str = 'nist') -> bool:
        """Securely delete a file using overwrite methods."""
        path = Path(file_path)
        
        if not path.exists():
            logger.warning(f"File not found: {file_path}")
            return False
        
        file_size = path.stat().st_size
        
        try:
            with open(path, 'r+b') as f:
                if method == 'nist':
                    # NIST 800-88 Clear: Single overwrite with zeros
                    f.write(b'\x00' * file_size)
                    f.flush()
                    os.fsync(f.fileno())
                    
                elif method == 'dod':
                    # DoD 5220.22-M: 3-pass overwrite
                    patterns = [b'\x00', b'\xFF', b'\x00']
                    for pattern in patterns:
                        f.seek(0)
                        if pattern == b'\x00' and len(patterns) == 3:
                            pattern = secrets.token_bytes(file_size)
                        else:
                            f.write(pattern * file_size)
                        f.flush()
                        os.fsync(f.fileno())
                        
                elif method == 'gutmann':
                    self._gutmann_wipe(f, file_size)
            
            # Rename before delete to obscure original name
            temp_name = path.parent / secrets.token_hex(16)
            path.rename(temp_name)
            
            # Delete
            temp_name.unlink()
            
            logger.info(f"Securely deleted: {file_path}")
            return True
            
        except Exception as e:
            logger.error(f"Secure deletion failed: {e}")
            return False
    
    def _gutmann_wipe(self, file_handle, file_size: int):
        """Implement Gutmann 35-pass wipe method."""
        patterns = [
            b'\x55', b'\xAA', b'\x92\x49\x24', b'\x49\x24\x92',
            b'\x24\x92\x49', b'\x00', b'\x11', b'\x22', b'\x33',
            b'\x44', b'\x55', b'\x66', b'\x77', b'\x88', b'\x99',
            b'\xAA', b'\xBB', b'\xCC', b'\xDD', b'\xEE', b'\xFF',
        ]
        
        for pattern in patterns:
            file_handle.seek(0)
            full_pattern = (pattern * (file_size // len(pattern) + 1))[:file_size]
            file_handle.write(full_pattern)
            file_handle.flush()
            os.fsync(file_handle.fileno())
        
        # Final random pass
        file_handle.seek(0)
        random_data = secrets.token_bytes(min(file_size, 1024*1024))
        for i in range(0, file_size, len(random_data)):
            file_handle.write(random_data[:min(len(random_data), file_size - i)])
        file_handle.flush()
        os.fsync(file_handle.fileno())
    
    def purge_expired_data(self, retention_policies: Dict[str, int]) -> Dict:
        """Purge data that has exceeded retention periods."""
        report = {'purged_tables': [], 'total_records': 0}
        
        conn = psycopg2.connect(**self.db_config)
        cursor = conn.cursor()
        
        try:
            for table, days in retention_policies.items():
                cutoff_date = datetime.utcnow() - timedelta(days=days)
                
                # Check if table has audit/deletion timestamp
                cursor.execute(f"""
                    SELECT column_name 
                    FROM information_schema.columns 
                    WHERE table_name = %s 
                    AND column_name IN ('deleted_at', 'archived_at', 'created_at')
                """, (table,))
                
                time_column = cursor.fetchone()
                if not time_column:
                    continue
                
                # Delete expired records
                cursor.execute(f"""
                    DELETE FROM {table}
                    WHERE {time_column[0]} < %s
                    RETURNING id
                """, (cutoff_date,))
                
                deleted_ids = cursor.fetchall()
                count = len(deleted_ids)
                
                if count > 0:
                    report['purged_tables'].append({
                        'table': table,
                        'records': count,
                        'cutoff_date': cutoff_date.isoformat()
                    })
                    report['total_records'] += count
                    
                    logger.info(f"Purged {count} records from {table}")
            
            conn.commit()
            return report
            
        except Exception as e:
            conn.rollback()
            logger.error(f"Purge failed: {e}")
            raise
        finally:
            cursor.close()
            conn.close()
    
    def _schedule_backup_purge(self, customer_id: int) -> None:
        """Schedule deletion from backups (executed during retention window)."""
        conn = psycopg2.connect(**self.db_config)
        cursor = conn.cursor()
        
        cursor.execute("""
            INSERT INTO backup_purge_queue 
            (customer_id, scheduled_date, status)
            VALUES (%s, CURRENT_TIMESTAMP + INTERVAL '30 days', 'pending')
        """, (customer_id,))
        
        conn.commit()
        cursor.close()
        conn.close()
```

---

## 12. PII HANDLING

### 12.1 Personal Data Identification and Protection

```python
"""
PII Identification and Protection Service
Smart Dairy Ltd. - PII Discovery and Classification
"""

import re
from typing import List, Dict, Set, Tuple
from dataclasses import dataclass
from enum import Enum
import hashlib
import json
from datetime import datetime


class PIIType(Enum):
    """Types of personally identifiable information."""
    NAME = "name"
    EMAIL = "email"
    PHONE = "phone"
    NID = "national_id"
    ADDRESS = "address"
    DATE_OF_BIRTH = "date_of_birth"
    BIOMETRIC = "biometric"
    FINANCIAL = "financial"
    LOCATION = "location"
    IP_ADDRESS = "ip_address"


@dataclass
class PIIDiscovery:
    """Result of PII discovery."""
    pii_type: PIIType
    value: str
    field_name: str
    table_name: str
    confidence: float
    risk_level: str


class PIIDetector:
    """Detects and classifies PII in data."""
    
    PATTERNS = {
        PIIType.EMAIL: {
            'regex': re.compile(r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b'),
            'keywords': ['email', 'e-mail', 'mail', 'contact_email'],
            'risk': 'medium'
        },
        PIIType.PHONE: {
            'regex': re.compile(r'(?:\+?88)?[\s-]?01[3-9][\s-]?\d[\s-]?\d[\s-]?\d[\s-]?\d[\s-]?\d[\s-]?\d[\s-]?\d[\s-]?\d'),
            'keywords': ['phone', 'mobile', 'cell', 'contact', 'telephone'],
            'risk': 'medium'
        },
        PIIType.NID: {
            'regex': re.compile(r'\b\d{10,17}\b'),
            'keywords': ['nid', 'national_id', 'nationalid', 'voter_id'],
            'risk': 'critical'
        },
        PIIType.NAME: {
            'regex': None,
            'keywords': ['name', 'full_name', 'first_name', 'last_name', 
                        'customer_name', 'contact_name', 'person_name'],
            'risk': 'low'
        },
        PIIType.ADDRESS: {
            'regex': None,
            'keywords': ['address', 'street', 'city', 'district', 'thana', 
                        'post_code', 'zip', 'location'],
            'risk': 'medium'
        },
        PIIType.DATE_OF_BIRTH: {
            'regex': re.compile(r'\b(\d{4}[/-]\d{1,2}[/-]\d{1,2}|\d{1,2}[/-]\d{1,2}[/-]\d{4})\b'),
            'keywords': ['dob', 'birth_date', 'date_of_birth', 'birthdate', 'age'],
            'risk': 'medium'
        },
        PIIType.IP_ADDRESS: {
            'regex': re.compile(r'\b(?:\d{1,3}\.){3}\d{1,3}\b'),
            'keywords': ['ip', 'ip_address', 'client_ip', 'remote_addr'],
            'risk': 'low'
        },
        PIIType.FINANCIAL: {
            'regex': re.compile(r'\b(?:\d{4}[\s-]?){4}\b'),
            'keywords': ['card', 'credit_card', 'payment', 'bank_account'],
            'risk': 'critical'
        }
    }
    
    def __init__(self):
        self.discovered_pii: List[PIIDiscovery] = []
    
    def scan_database_schema(self, cursor) -> List[PIIDiscovery]:
        """Scan database schema for PII-containing columns."""
        discoveries = []
        
        cursor.execute("""
            SELECT table_name, column_name, data_type
            FROM information_schema.columns
            WHERE table_schema = 'public'
            AND table_name NOT LIKE 'pg_%'
            AND table_name NOT LIKE 'ir_%'
        """)
        
        columns = cursor.fetchall()
        
        for table_name, column_name, data_type in columns:
            for pii_type, config in self.PATTERNS.items():
                confidence = self._check_column_name(column_name, config['keywords'])
                
                if confidence > 0.5:
                    discoveries.append(PIIDiscovery(
                        pii_type=pii_type,
                        value=None,
                        field_name=column_name,
                        table_name=table_name,
                        confidence=confidence,
                        risk_level=config['risk']
                    ))
        
        return discoveries
    
    def scan_data_sample(self, table_name: str, column_name: str,
                        sample_values: List[str]) -> List[PIIDiscovery]:
        """Scan sample data values for PII."""
        discoveries = []
        
        for value in sample_values:
            if not value:
                continue
            
            for pii_type, config in self.PATTERNS.items():
                if config['regex'] and config['regex'].search(str(value)):
                    discoveries.append(PIIDiscovery(
                        pii_type=pii_type,
                        value=value[:50] + '...' if len(str(value)) > 50 else value,
                        field_name=column_name,
                        table_name=table_name,
                        confidence=0.9,
                        risk_level=config['risk']
                    ))
        
        return discoveries
    
    def _check_column_name(self, column_name: str, keywords: List[str]) -> float:
        """Check if column name matches PII keywords."""
        column_lower = column_name.lower()
        
        for keyword in keywords:
            if keyword == column_lower:
                return 1.0
            elif keyword in column_lower:
                return 0.8
            elif any(part in column_lower for part in keyword.split('_')):
                return 0.6
        
        return 0.0
    
    def generate_pii_inventory(self, discoveries: List[PIIDiscovery]) -> Dict:
        """Generate PII inventory report."""
        inventory = {
            'generated_at': datetime.utcnow().isoformat(),
            'total_discoveries': len(discoveries),
            'by_type': {},
            'by_table': {},
            'by_risk_level': {'critical': 0, 'high': 0, 'medium': 0, 'low': 0},
            'recommendations': []
        }
        
        for discovery in discoveries:
            pii_type = discovery.pii_type.value
            if pii_type not in inventory['by_type']:
                inventory['by_type'][pii_type] = []
            inventory['by_type'][pii_type].append({
                'table': discovery.table_name,
                'column': discovery.field_name,
                'risk': discovery.risk_level
            })
            
            if discovery.table_name not in inventory['by_table']:
                inventory['by_table'][discovery.table_name] = []
            inventory['by_table'][discovery.table_name].append({
                'type': pii_type,
                'column': discovery.field_name,
                'risk': discovery.risk_level
            })
            
            inventory['by_risk_level'][discovery.risk_level] += 1
        
        inventory['recommendations'] = self._generate_recommendations(inventory)
        
        return inventory
    
    def _generate_recommendations(self, inventory: Dict) -> List[str]:
        """Generate security recommendations based on inventory."""
        recommendations = []
        
        if inventory['by_risk_level']['critical'] > 0:
            recommendations.append(
                f"CRITICAL: {inventory['by_risk_level']['critical']} critical PII fields detected. "
                "Implement field-level encryption immediately."
            )
        
        if inventory['by_risk_level']['high'] > 0:
            recommendations.append(
                f"HIGH: {inventory['by_risk_level']['high']} high-risk PII fields. "
                "Review access controls and consider encryption."
            )
        
        return recommendations
```

---

## 13. DATA ANONYMIZATION

### 13.1 Anonymization Techniques for Analytics/Test Data

```python
"""
Data Anonymization Service
Smart Dairy Ltd. - Techniques for Analytics and Test Data
"""

import hashlib
import secrets
import random
from typing import Dict, List, Optional, Callable
from enum import Enum
import faker


class AnonymizationTechnique(Enum):
    """Data anonymization techniques."""
    RANDOMIZATION = "randomization"
    GENERALIZATION = "generalization"
    SUPPRESSION = "suppression"
    PSEUDONYMIZATION = "pseudonymization"
    SHUFFLING = "shuffling"
    NOISE_ADDITION = "noise_addition"


class DataAnonymizer:
    """
    Anonymizes data for use in analytics, testing, and development.
    Maintains data utility while protecting privacy.
    """
    
    def __init__(self, locale: str = 'bn_BD'):
        self.fake = faker.Faker(locale)
        self.fake_en = faker.Faker('en_US')
        self._pseudonym_map: Dict[str, str] = {}
    
    def anonymize_customer_record(self, record: Dict) -> Dict:
        """
        Anonymize a customer record while preserving analytical value.
        
        Preserved:
        - Order patterns, amounts, timestamps
        - Product preferences
        - Geographic distribution (anonymized)
        - Age groups (not exact DOB)
        
        Anonymized:
        - Names, contact details
        - Exact addresses
        - Exact dates of birth
        """
        anonymized = record.copy()
        
        # Replace name with consistent pseudonym
        if 'name' in anonymized:
            anonymized['name'] = self._pseudonymize(
                anonymized['name'], 
                self.fake.name
            )
        
        # Generalize location to district level
        if 'address' in anonymized:
            districts = ['Dhaka', 'Narayanganj', 'Gazipur', 'Chattogram', 
                        'Khulna', 'Rajshahi', 'Sylhet', 'Barishal']
            anonymized['address'] = random.choice(districts)
        
        # Generalize age to age group
        if 'date_of_birth' in anonymized:
            anonymized['age_group'] = self._generalize_age(
                anonymized.pop('date_of_birth')
            )
        
        # Randomize contact info
        if 'email' in anonymized:
            anonymized['email'] = self.fake.email()
        if 'phone' in anonymized:
            anonymized['phone'] = self._generate_fake_phone()
        
        # Suppress NID
        if 'nid' in anonymized:
            anonymized['nid'] = 'SUPPRESSED'
        
        return anonymized
    
    def anonymize_order_data(self, orders: List[Dict]) -> List[Dict]:
        """
        Anonymize order data for analytics.
        Preserves patterns but removes identifying information.
        """
        anonymized_orders = []
        
        for order in orders:
            anon_order = order.copy()
            
            # Pseudonymize customer ID
            if 'customer_id' in anon_order:
                anon_order['customer_id'] = self._pseudonymize(
                    str(anon_order['customer_id']),
                    lambda: f"CUST_{secrets.token_hex(4).upper()}"
                )
            
            # Add small random noise to amounts (differential privacy)
            if 'amount_total' in anon_order:
                noise = random.gauss(0, anon_order['amount_total'] * 0.01)
                anon_order['amount_total'] = round(
                    anon_order['amount_total'] + noise, 2
                )
            
            # Generalize timestamp to month
            if 'date_order' in anon_order:
                anon_order['order_month'] = anon_order['date_order'][:7]
                del anon_order['date_order']
            
            # Remove delivery addresses
            if 'delivery_address' in anon_order:
                del anon_order['delivery_address']
            
            anonymized_orders.append(anon_order)
        
        return anonymized_orders
    
    def generate_synthetic_dataset(self, schema: Dict, 
                                   num_records: int) -> List[Dict]:
        """
        Generate completely synthetic data matching a schema.
        Useful for testing without any real data.
        """
        records = []
        
        for _ in range(num_records):
            record = {}
            for field, field_type in schema.items():
                record[field] = self._generate_synthetic_value(field_type)
            records.append(record)
        
        return records
    
    def _pseudonymize(self, original: str, 
                     generator: Callable) -> str:
        """
        Create consistent pseudonym for a value.
        Same input always produces same pseudonym.
        """
        if original not in self._pseudonym_map:
            self._pseudonym_map[original] = generator()
        return self._pseudonym_map[original]
    
    def _generalize_age(self, date_of_birth: str) -> str:
        """Convert exact DOB to age group."""
        from datetime import datetime
        
        try:
            dob = datetime.strptime(date_of_birth, '%Y-%m-%d')
            age = (datetime.now() - dob).days // 365
            
            if age < 18:
                return "Under 18"
            elif age < 25:
                return "18-24"
            elif age < 35:
                return "25-34"
            elif age < 45:
                return "35-44"
            elif age < 55:
                return "45-54"
            elif age < 65:
                return "55-64"
            else:
                return "65+"
        except:
            return "Unknown"
    
    def _generate_fake_phone(self) -> str:
        """Generate fake Bangladesh phone number."""
        prefixes = ['013', '014', '015', '016', '017', '018', '019']
        prefix = random.choice(prefixes)
        suffix = ''.join(random.choices('0123456789', k=8))
        return f"{prefix}{suffix}"
    
    def _generate_synthetic_value(self, field_type: str) -> any:
        """Generate synthetic value based on type."""
        generators = {
            'name': self.fake.name,
            'email': self.fake.email,
            'phone': self._generate_fake_phone,
            'address': self.fake.address,
            'date': lambda: self.fake.date_between('-5y', 'today').isoformat(),
            'amount': lambda: round(random.uniform(100, 10000), 2),
            'integer': lambda: random.randint(1, 1000),
            'boolean': lambda: random.choice([True, False]),
            'product': lambda: random.choice([
                'Organic Milk 1L', 'Yogurt 500g', 'Cheese 200g',
                'Butter 100g', 'Cream 250ml', 'Ghee 500g'
            ]),
            'district': lambda: random.choice([
                'Dhaka', 'Narayanganj', 'Gazipur', 'Chattogram'
            ])
        }
        
        generator = generators.get(field_type, lambda: None)
        return generator()


# Anonymization configuration for Smart Dairy
ANONYMIZATION_RULES = {
    'production_to_staging': {
        'res_partner': {
            'name': 'pseudonymize',
            'email': 'randomize',
            'phone': 'randomize',
            'street': 'suppress',
            'street2': 'suppress',
            'nid': 'hash',
        },
        'sale_order': {
            'partner_id': 'pseudonymize',
            'delivery_address': 'suppress',
        }
    },
    'production_to_analytics': {
        'res_partner': {
            'name': 'suppress',
            'email': 'suppress',
            'phone': 'suppress',
            'district': 'generalize',
            'age_group': 'generalize',
        }
    }
}
```

---

## 14. APPENDICES

### Appendix A: Implementation Checklist

| Component | Implementation | Testing | Documentation |
|-----------|---------------|---------|---------------|
| Data Classification | [ ] | [ ] | [ ] |
| Encryption at Rest (Database) | [ ] | [ ] | [ ] |
| Encryption at Rest (Files) | [ ] | [ ] | [ ] |
| TLS 1.3 Configuration | [ ] | [ ] | [ ] |
| Field-Level Encryption | [ ] | [ ] | [ ] |
| Payment Tokenization | [ ] | [ ] | [ ] |
| AWS KMS Integration | [ ] | [ ] | [ ] |
| HashiCorp Vault Setup | [ ] | [ ] | [ ] |
| Key Rotation Automation | [ ] | [ ] | [ ] |
| Data Masking for Non-Prod | [ ] | [ ] | [ ] |
| DLP Rules Configuration | [ ] | [ ] | [ ] |
| Database RLS Policies | [ ] | [ ] | [ ] |
| Backup Encryption | [ ] | [ ] | [ ] |
| Secure Deletion Procedures | [ ] | [ ] | [ ] |
| PII Discovery Scanning | [ ] | [ ] | [ ] |
| Data Anonymization Pipeline | [ ] | [ ] | [ ] |

### Appendix B: Configuration Templates

#### B.1 Environment Variables

```bash
# Data Protection Environment Variables
# File: .env.data_protection

# Encryption Keys (rotate regularly)
DB_ENCRYPTION_KEY=<base64-encoded-32-byte-key>
FIELD_ENCRYPTION_KEY=<base64-encoded-32-byte-key>
FILE_VAULT_KEY=<base64-encoded-32-byte-key>

# AWS KMS
AWS_KMS_KEY_ID=alias/smart-dairy-master-key
AWS_KMS_REGION=ap-southeast-1

# HashiCorp Vault
VAULT_ADDR=https://vault.smartdairybd.com:8200
VAULT_ROLE_ID=<role-id>
VAULT_SECRET_ID=<secret-id>

# GPG for Backups
GPG_KEY_ID=backup@smartdairybd.com

# Redis for Token Vault
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=<secure-password>
```

#### B.2 Kubernetes Secrets

```yaml
apiVersion: v1
kind: Secret
metadata:
  name: data-protection-secrets
  namespace: smart-dairy
type: Opaque
stringData:
  db-encryption-key: "<base64-encoded-key>"
  field-encryption-key: "<base64-encoded-key>"
  vault-token: "<vault-token>"
  kms-credentials: |
    [default]
    aws_access_key_id = <access-key>
    aws_secret_access_key = <secret-key>
```

### Appendix C: Code Examples

#### C.1 Complete Encryption Service Integration

```python
# Example: Complete integration of encryption services in Odoo

from odoo import models, fields, api
from odoo.exceptions import AccessDenied

class EncryptedPartner(models.Model):
    _inherit = 'res.partner'
    
    # Encrypted storage fields
    email_encrypted = fields.Char(string='Encrypted Email')
    phone_encrypted = fields.Char(string='Encrypted Phone')
    
    # Searchable hashes
    email_hash = fields.Char(string='Email Hash', index=True)
    phone_hash = fields.Char(string='Phone Hash', index=True)
    
    @api.model_create_multi
    def create(self, vals_list):
        encryption = self.env['encryption.service']
        
        for vals in vals_list:
            if 'email' in vals:
                vals['email_encrypted'] = encryption.encrypt_field(
                    'email', vals['email']
                )
                vals['email_hash'] = encryption.hash_for_search(
                    'email', vals['email']
                )
                del vals['email']
                
            if 'phone' in vals:
                vals['phone_encrypted'] = encryption.encrypt_field(
                    'phone', vals['phone']
                )
                vals['phone_hash'] = encryption.hash_for_search(
                    'phone', vals['phone']
                )
                del vals['phone']
        
        return super().create(vals_list)
    
    def read_encrypted_field(self, field_name: str) -> str:
        """Read encrypted field with permission check."""
        if not self.env.user.has_group('base.group_system'):
            raise AccessDenied("Insufficient permissions to read encrypted data")
        
        encryption = self.env['encryption.service']
        encrypted_value = getattr(self, f'{field_name}_encrypted')
        return encryption.decrypt_field(field_name, encrypted_value)
```

### Appendix D: Incident Response

#### D.1 Data Breach Response Procedure

```
1. DETECTION (0-1 hour)
   - Identify potential data breach
   - Isolate affected systems
   - Preserve evidence
   - Notify Security Lead

2. ASSESSMENT (1-4 hours)
   - Determine scope of breach
   - Identify data types affected
   - Assess risk level
   - Document findings

3. CONTAINMENT (4-24 hours)
   - Revoke compromised credentials
   - Rotate exposed encryption keys
   - Block suspicious access
   - Implement additional monitoring

4. NOTIFICATION (24-72 hours)
   - Notify data protection authority (if required)
   - Inform affected individuals
   - Document notification process
   - Prepare public statement if needed

5. RECOVERY (1-7 days)
   - Restore from clean backups
   - Verify system integrity
   - Implement additional controls
   - Update security procedures

6. POST-INCIDENT (7-30 days)
   - Conduct post-mortem
   - Update incident documentation
   - Train staff on lessons learned
   - Review and update policies
```

### Appendix E: Compliance Mapping

| Requirement | Implementation | Verification |
|-------------|---------------|--------------|
| Encrypt data at rest | AES-256 encryption for all storage | Quarterly audit |
| Encrypt data in transit | TLS 1.3 for all connections | Weekly scan |
| Key management | AWS KMS + HashiCorp Vault | Monthly review |
| Access controls | RBAC + RLS | Quarterly review |
| Data retention | Automated purge policies | Monthly execution |
| Right to erasure | Secure deletion procedures | Per-request audit |
| Data minimization | Anonymization for non-prod | Per-deployment |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Lead | Initial document creation |

---

## APPROVAL

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Security Lead | _____________ | _______ |
| Reviewer | IT Director | _____________ | _______ |
| Approver | Managing Director | _____________ | _______ |

---

*This document is confidential and proprietary to Smart Dairy Ltd.*
*Unauthorized distribution is strictly prohibited.*
