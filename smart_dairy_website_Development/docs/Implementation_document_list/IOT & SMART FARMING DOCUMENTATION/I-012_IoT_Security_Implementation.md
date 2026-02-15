# I-012: IoT Security Implementation

## Smart Dairy Ltd. - Smart Web Portal System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-012 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | Security Lead |
| **Status** | Approved |
| **Classification** | Internal - Confidential |

### Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Engineer | Initial document creation |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| IoT Architect | [IoT Architect Name] | _____________ | ___________ |
| Security Lead | [Security Lead Name] | _____________ | ___________ |
| Project Manager | [PM Name] | _____________ | ___________ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Device Security](#2-device-security)
3. [Communication Security](#3-communication-security)
4. [Network Security](#4-network-security)
5. [Authentication & Authorization](#5-authentication--authorization)
6. [Data Security](#6-data-security)
7. [Secure OTA Updates](#7-secure-ota-updates)
8. [Monitoring & Auditing](#8-monitoring--auditing)
9. [Physical Security](#9-physical-security)
10. [Incident Response](#10-incident-response)
11. [Compliance](#11-compliance)
12. [Security Testing](#12-security-testing)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive security implementation guidelines for Smart Dairy Ltd.'s IoT infrastructure. It covers security measures for IoT devices, communication protocols, network segmentation, authentication mechanisms, and data protection specific to the smart farming environment in Bangladesh.

### 1.2 Scope

This document applies to:
- All IoT devices deployed across Smart Dairy's farm network
- IoT gateways and edge computing devices
- MQTT broker infrastructure and messaging systems
- Communication protocols (MQTT, Modbus, OPC-UA, LoRaWAN)
- Cloud-to-device and device-to-cloud communications
- Sensor networks and data collection systems

### 1.3 IoT Security Challenges

Smart Dairy's IoT infrastructure faces unique security challenges in the agricultural context:

| Challenge | Description | Risk Level |
|-----------|-------------|------------|
| **Physical Accessibility** | Devices deployed in open farm environments with limited physical security | High |
| **Network Connectivity** | Reliance on intermittent cellular (4G) and satellite connections in rural Bangladesh | Medium |
| **Resource Constraints** | Limited computational power on sensor devices for encryption | High |
| **Scale Management** | Managing security across hundreds of distributed devices | Medium |
| **Legacy Integration** | Connecting modern IoT with existing industrial equipment | Medium |
| **Environmental Harshness** | Extreme temperatures, humidity, and dust affecting device integrity | Medium |

### 1.4 Threat Model

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY IOT THREAT MODEL                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  THREAT ACTORS:                                                              │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Script    │  │  Organized  │  │   Insider   │  │  Nation     │         │
│  │  Kiddies    │  │   Crime     │  │   Threat    │  │  State      │         │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘         │
│       Low             High            Medium           Critical              │
│                                                                              │
│  ATTACK VECTORS:                                                             │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  Device Layer:       │  Network Layer:      │  Application Layer:   │   │
│  │  • Device hijacking  │  • MITM attacks      │  • API exploitation   │   │
│  │  • Firmware tampering│  • Packet sniffing   │  • Authentication     │   │
│  │  • Physical theft    │  • Network scanning  │    bypass             │   │
│  │  • Side-channel      │  • VLAN hopping      │  • Injection attacks  │   │
│  │    attacks           │  • DoS/DDoS          │  • Privilege          │   │
│  │                      │                      │    escalation         │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  THREATS ADDRESSED:                                                          │
│  ┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐             │
│  │ Device Hijacking │ │ MITM Attacks     │ │ Data Tampering   │             │
│  │                  │ │                  │ │                  │             │
│  │ • Unauthorized   │ │ • Eavesdropping  │ │ • Data injection │             │
│  │   device control │ │ • Session hijack │ │ • Replay attacks │             │
│  │ • Credential     │ │ • Certificate    │ │ • Manipulation   │             │
│  │   theft          │ │   spoofing       │ │   of sensor data │             │
│  └──────────────────┘ └──────────────────┘ └──────────────────┘             │
│                                                                              │
│  ┌──────────────────┐ ┌──────────────────┐                                   │
│  │ DDoS from IoT    │ │ Unauthorized     │                                   │
│  │ Devices          │ │ Access           │                                   │
│  │                  │ │                  │                                   │
│  │ • Botnet         │ │ • Weak/default   │                                   │
│  │   recruitment    │ │   credentials    │                                   │
│  │ • Resource       │ │ • API key theft  │                                   │
│  │   exhaustion     │ │ • Privilege      │                                   │
│  │                  │ │   escalation     │                                   │
│  └──────────────────┘ └──────────────────┘                                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.5 Security Principles

| Principle | Implementation |
|-----------|---------------|
| **Defense in Depth** | Multiple layers of security controls across device, network, and application layers |
| **Zero Trust** | Never trust, always verify - every device and user must authenticate |
| **Least Privilege** | Devices and services receive minimum necessary access permissions |
| **Secure by Default** | All devices ship with secure configurations enabled |
| **Cryptographic Agility** | Support for algorithm and key updates as threats evolve |

---

## 2. Device Security

### 2.1 Secure Boot

Secure Boot ensures that only authenticated and authorized firmware can execute on IoT devices.

#### 2.1.1 Secure Boot Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      SECURE BOOT PROCESS                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Stage 1: Boot ROM (ROM)                                                     │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Immutable code in hardware ROM                                    │   │
│  │  • Verifies Stage 2 bootloader signature                             │   │
│  │  • Root of Trust for entire boot chain                               │   │
│  │  • Public key hash fused in hardware                                 │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                    │                                         │
│                                    ▼                                         │
│  Stage 2: Bootloader (Flash)                                                 │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Secondary Bootloader (U-Boot/UEFI)                                │   │
│  │  • Signature verified by Stage 1                                     │   │
│  │  • Verifies kernel and device tree signatures                          │   │
│  │  • Rollback protection via version check                               │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                    │                                         │
│                                    ▼                                         │
│  Stage 3: Operating System                                                   │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Linux Kernel with signature verification                            │   │
│  │  • Device Tree Blob (DTB) verification                                 │   │
│  │  • Initial RAM filesystem (initramfs) check                            │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                    │                                         │
│                                    ▼                                         │
│  Stage 4: Applications                                                       │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Container/Service signatures verified                               │   │
│  │  • Application-level integrity checks                                  │   │
│  │  • Runtime attestation reports                                         │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 2.1.2 Secure Boot Implementation

```bash
#!/bin/bash
# /usr/local/bin/secure-boot-setup.sh
# Secure Boot configuration for Smart Dairy IoT Gateway

set -e

# Generate code signing keys
setup_signing_keys() {
    echo "[*] Generating code signing keys..."
    
    # Create secure key directory
    mkdir -p /etc/smartdairy/secureboot/keys
    chmod 700 /etc/smartdairy/secureboot/keys
    
    # Generate RSA 4096-bit key for kernel signing
    openssl genrsa -out /etc/smartdairy/secureboot/keys/kernel-signing.key 4096
    openssl rsa -in /etc/smartdairy/secureboot/keys/kernel-signing.key \
        -pubout -out /etc/smartdairy/secureboot/keys/kernel-signing.pub
    
    # Generate firmware signing key
    openssl genrsa -out /etc/smartdairy/secureboot/keys/firmware-signing.key 4096
    openssl rsa -in /etc/smartdairy/secureboot/keys/firmware-signing.key \
        -pubout -out /etc/smartdairy/secureboot/keys/firmware-signing.pub
    
    # Secure key files
    chmod 600 /etc/smartdairy/secureboot/keys/*.key
    chmod 644 /etc/smartdairy/secureboot/keys/*.pub
    
    echo "[+] Keys generated successfully"
}

# Sign kernel image
sign_kernel() {
    KERNEL_PATH="/boot/vmlinuz-$(uname -r)"
    SIGNATURE_PATH="${KERNEL_PATH}.sig"
    
    echo "[*] Signing kernel: $KERNEL_PATH"
    
    openssl dgst -sha256 -sign /etc/smartdairy/secureboot/keys/kernel-signing.key \
        -out "$SIGNATURE_PATH" "$KERNEL_PATH"
    
    # Verify signature
    if openssl dgst -sha256 -verify /etc/smartdairy/secureboot/keys/kernel-signing.pub \
        -signature "$SIGNATURE_PATH" "$KERNEL_PATH"; then
        echo "[+] Kernel signed and verified"
    else
        echo "[-] Kernel signature verification failed!"
        exit 1
    fi
}

# Sign device tree blob
sign_dtb() {
    DTB_PATH="/boot/dtbs/$(uname -r)/broadcom/bcm2711-rpi-4-b.dtb"
    SIGNATURE_PATH="${DTB_PATH}.sig"
    
    echo "[*] Signing DTB: $DTB_PATH"
    
    openssl dgst -sha256 -sign /etc/smartdairy/secureboot/keys/kernel-signing.key \
        -out "$SIGNATURE_PATH" "$DTB_PATH"
    
    echo "[+] DTB signed successfully"
}

# Verify boot chain
verify_boot_chain() {
    echo "[*] Verifying boot chain integrity..."
    
    # Verify kernel signature
    KERNEL_PATH="/boot/vmlinuz-$(uname -r)"
    SIGNATURE_PATH="${KERNEL_PATH}.sig"
    
    if openssl dgst -sha256 -verify /etc/smartdairy/secureboot/keys/kernel-signing.pub \
        -signature "$SIGNATURE_PATH" "$KERNEL_PATH"; then
        echo "[+] Kernel signature valid"
    else
        echo "[-] Kernel signature INVALID - Potential tampering!"
        return 1
    fi
    
    # Check file integrity
    if [ -f /etc/smartdairy/secureboot/boot_manifest ]; then
        while IFS=' ' read -r expected_hash file_path; do
            actual_hash=$(sha256sum "$file_path" | awk '{print $1}')
            if [ "$expected_hash" != "$actual_hash" ]; then
                echo "[-] File integrity check failed: $file_path"
                return 1
            fi
        done < /etc/smartdairy/secureboot/boot_manifest
        echo "[+] Boot manifest integrity verified"
    fi
    
    return 0
}

# Main execution
case "$1" in
    setup)
        setup_signing_keys
        ;;
    sign)
        sign_kernel
        sign_dtb
        ;;
    verify)
        verify_boot_chain
        ;;
    *)
        echo "Usage: $0 {setup|sign|verify}"
        exit 1
        ;;
esac
```

#### 2.1.3 Boot Verification Script

```bash
#!/bin/bash
# /etc/init.d/boot-verification
# Boot-time integrity verification

### BEGIN INIT INFO
# Provides:          boot-verification
# Required-Start:    $local_fs
# Required-Stop:
# Default-Start:     S
# Default-Stop:
# Short-Description: Boot chain verification
### END INIT INFO

. /lib/lsb/init-functions

LOG_FILE="/var/log/smartdairy/boot-verification.log"
ALERT_TOPIC="smartdairy/alerts/critical/boot_integrity"

log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" | tee -a "$LOG_FILE"
}

send_alert() {
    local severity="$1"
    local message="$2"
    
    # Publish to MQTT for remote monitoring
    mosquitto_pub -h localhost -p 1883 \
        -u gateway -P "${GATEWAY_MQTT_PASSWORD}" \
        -t "$ALERT_TOPIC" \
        -m "{\"severity\":\"$severity\",\"message\":\"$message\",\"timestamp\":\"$(date -Iseconds)\"}"
}

do_start() {
    log_message "Starting boot verification..."
    
    # Verify boot chain
    if /usr/local/bin/secure-boot-setup.sh verify; then
        log_message "Boot verification PASSED"
        send_alert "info" "Boot integrity verified successfully"
        return 0
    else
        log_message "Boot verification FAILED"
        send_alert "critical" "Boot integrity check failed - potential compromise"
        
        # Optional: Halt boot and require manual intervention
        # echo "CRITICAL: Boot verification failed. Halting." > /dev/console
        # /sbin/halt -f
        
        return 1
    fi
}

case "$1" in
    start)
        do_start
        ;;
    status)
        # Return status of last verification
        if [ -f "$LOG_FILE" ]; then
            tail -5 "$LOG_FILE"
        fi
        ;;
    *)
        echo "Usage: $0 {start|status}"
        exit 1
        ;;
esac
```

### 2.2 Firmware Signing

#### 2.2.1 Firmware Signing Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                   FIRMWARE SIGNING WORKFLOW                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  DEVELOPMENT                    SIGNING                      DEPLOYMENT      │
│  ┌──────────┐                ┌──────────┐                  ┌──────────┐     │
│  │  Build   │──Firmware───▶  │  Sign    │──Signed FW───▶   │  OTA     │     │
│  │  System  │    Image       │  Server  │    Package       │  Server  │     │
│  └──────────┘                └──────────┘                  └──────────┘     │
│       │                           │                             │           │
│       │                           │                             │           │
│       ▼                           ▼                             ▼           │
│  ┌──────────┐                ┌──────────┐                  ┌──────────┐     │
│  │ Generate │                │ HSM Sign │                  │ Device   │     │
│  │  SHA256  │                │ w/ RSA   │                  │ Verify   │     │
│  │  Hash    │                │ 4096     │                  │ & Install│     │
│  └──────────┘                └──────────┘                  └──────────┘     │
│                                                                              │
│  SIGNING HIERARCHY:                                                          │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │                        ROOT CA (Offline HSM)                        │     │
│  │                      ┌───────────────────┐                         │     │
│  │                      │  Smart Dairy FW   │                         │     │
│  │                      │      Root CA      │                         │     │
│  │                      └─────────┬─────────┘                         │     │
│  │                                │                                   │     │
│  │              ┌─────────────────┼─────────────────┐                 │     │
│  │              ▼                 ▼                 ▼                 │     │
│  │      ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │     │
│  │      │  Signing     │  │   Signing    │  │   Signing    │         │     │
│  │      │  Key - GW    │  │   Key - Dev  │  │   Key - Sens │         │     │
│  │      │  (Gateway)   │  │   (Device)   │  │   (Sensor)   │         │
│  │      └──────────────┘  └──────────────┘  └──────────────┘         │     │
│  │                                                                   │     │
│  │  Key Usage:                                                       │     │
│  │  • Root CA: Certificate signing only, offline storage             │     │
│  │  • Signing Keys: Firmware signing, online HSM                     │     │
│  │                                                                   │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 2.2.2 Firmware Signing Implementation

```python
#!/usr/bin/env python3
"""
Smart Dairy Firmware Signing Service
Provides cryptographic signing for firmware images
"""

import hashlib
import json
import os
import subprocess
from datetime import datetime, timedelta
from dataclasses import dataclass
from typing import Optional, Dict
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger('FirmwareSigner')


@dataclass
class FirmwareMetadata:
    """Firmware metadata structure"""
    version: str
    device_type: str
    build_timestamp: str
    git_commit: str
    signer_id: str
    hardware_target: str
    minimum_hardware_version: str
    

class FirmwareSigner:
    """Handles firmware signing operations using HSM-backed keys"""
    
    # Certificate chain paths
    ROOT_CA_CERT = '/etc/smartdairy/firmware/certs/root-ca.crt'
    SIGNING_CERT = '/etc/smartdairy/firmware/certs/signing.crt'
    SIGNING_KEY = '/etc/smartdairy/firmware/keys/signing.key'
    
    def __init__(self):
        self._validate_certificates()
    
    def _validate_certificates(self):
        """Validate that required certificates exist"""
        for cert_path in [self.ROOT_CA_CERT, self.SIGNING_CERT]:
            if not os.path.exists(cert_path):
                raise FileNotFoundError(f"Certificate not found: {cert_path}")
    
    def calculate_firmware_hash(self, firmware_path: str) -> str:
        """Calculate SHA-256 hash of firmware image"""
        sha256_hash = hashlib.sha256()
        with open(firmware_path, 'rb') as f:
            for chunk in iter(lambda: f.read(8192), b''):
                sha256_hash.update(chunk)
        return sha256_hash.hexdigest()
    
    def create_manifest(self, 
                       firmware_path: str,
                       metadata: FirmwareMetadata) -> Dict:
        """Create firmware manifest with metadata and hash"""
        
        manifest = {
            'manifest_version': '1.0',
            'firmware': {
                'filename': os.path.basename(firmware_path),
                'size_bytes': os.path.getsize(firmware_path),
                'sha256_hash': self.calculate_firmware_hash(firmware_path),
            },
            'metadata': {
                'version': metadata.version,
                'device_type': metadata.device_type,
                'hardware_target': metadata.hardware_target,
                'minimum_hardware_version': metadata.minimum_hardware_version,
                'build_timestamp': metadata.build_timestamp,
                'git_commit': metadata.git_commit,
            },
            'signature': {
                'algorithm': 'RSA-PSS-SHA256',
                'signer_id': metadata.signer_id,
                'timestamp': datetime.utcnow().isoformat(),
                'certificate_chain': [
                    self._load_certificate(self.SIGNING_CERT),
                    self._load_certificate(self.ROOT_CA_CERT)
                ]
            },
            'security': {
                'rollback_protection': True,
                'minimum_version': metadata.minimum_hardware_version,
                'anti_rollback_counter': self._get_rollback_counter(metadata.device_type),
            }
        }
        
        return manifest
    
    def _load_certificate(self, cert_path: str) -> str:
        """Load certificate content"""
        with open(cert_path, 'r') as f:
            return f.read()
    
    def _get_rollback_counter(self, device_type: str) -> int:
        """Get anti-rollback counter for device type"""
        counter_file = f'/etc/smartdairy/firmware/counters/{device_type}.counter'
        if os.path.exists(counter_file):
            with open(counter_file, 'r') as f:
                return int(f.read().strip())
        return 1
    
    def sign_manifest(self, manifest: Dict) -> Dict:
        """Sign the manifest with the signing key"""
        
        # Convert manifest to canonical JSON for signing
        manifest_json = json.dumps(manifest, sort_keys=True, separators=(',', ':'))
        
        # Create signature using OpenSSL
        try:
            result = subprocess.run(
                [
                    'openssl', 'dgst', '-sha256', '-sign', self.SIGNING_KEY,
                    '-sigopt', 'rsa_padding_mode:pss',
                    '-sigopt', 'rsa_pss_saltlen:-1'
                ],
                input=manifest_json.encode(),
                capture_output=True,
                check=True
            )
            
            signature = result.stdout.hex()
            manifest['signature']['value'] = signature
            
            logger.info(f"Manifest signed successfully (signer: {manifest['signature']['signer_id']})")
            return manifest
            
        except subprocess.CalledProcessError as e:
            logger.error(f"Signing failed: {e.stderr.decode()}")
            raise
    
    def sign_firmware(self, 
                     firmware_path: str,
                     output_path: str,
                     metadata: FirmwareMetadata) -> str:
        """
        Sign firmware image and create signed package
        
        Returns:
            Path to signed firmware package
        """
        logger.info(f"Signing firmware: {firmware_path}")
        
        # Create manifest
        manifest = self.create_manifest(firmware_path, metadata)
        
        # Sign manifest
        signed_manifest = self.sign_manifest(manifest)
        
        # Create signed package
        package = {
            'manifest': signed_manifest,
            'firmware': self._encode_firmware(firmware_path)
        }
        
        # Write signed package
        with open(output_path, 'w') as f:
            json.dump(package, f, indent=2)
        
        logger.info(f"Signed firmware package created: {output_path}")
        return output_path
    
    def _encode_firmware(self, firmware_path: str) -> str:
        """Base64 encode firmware binary"""
        import base64
        with open(firmware_path, 'rb') as f:
            return base64.b64encode(f.read()).decode('utf-8')


class FirmwareVerifier:
    """Verifies firmware signatures on devices"""
    
    ROOT_CA_CERT = '/etc/smartdairy/firmware/certs/root-ca.crt'
    
    def verify_package(self, package_path: str) -> bool:
        """
        Verify signed firmware package
        
        Returns:
            True if verification succeeds, False otherwise
        """
        logger.info(f"Verifying firmware package: {package_path}")
        
        with open(package_path, 'r') as f:
            package = json.load(f)
        
        manifest = package['manifest']
        
        # Verify certificate chain
        if not self._verify_certificate_chain(manifest['signature']['certificate_chain']):
            logger.error("Certificate chain verification failed")
            return False
        
        # Extract and verify signature
        manifest_copy = manifest.copy()
        signature_hex = manifest_copy['signature'].pop('value')
        manifest_json = json.dumps(manifest_copy, sort_keys=True, separators=(',', ':'))
        
        # Save signing certificate temporarily
        signing_cert = manifest['signature']['certificate_chain'][0]
        cert_path = '/tmp/signing_cert.pem'
        with open(cert_path, 'w') as f:
            f.write(signing_cert)
        
        # Verify signature
        try:
            result = subprocess.run(
                [
                    'openssl', 'dgst', '-sha256', '-verify', cert_path,
                    '-sigopt', 'rsa_padding_mode:pss',
                    '-signature', '/dev/stdin'
                ],
                input=bytes.fromhex(signature_hex) + manifest_json.encode(),
                capture_output=True
            )
            
            if result.returncode == 0 and b'Verified OK' in result.stdout:
                logger.info("Firmware signature verified successfully")
                return True
            else:
                logger.error(f"Signature verification failed: {result.stderr.decode()}")
                return False
                
        finally:
            if os.path.exists(cert_path):
                os.remove(cert_path)
    
    def _verify_certificate_chain(self, certificates: list) -> bool:
        """Verify certificate chain against root CA"""
        # Implementation would validate chain of trust
        # For production, implement full X.509 chain validation
        return True


def main():
    """Example usage"""
    metadata = FirmwareMetadata(
        version='2.1.3',
        device_type='iot-gateway',
        build_timestamp=datetime.utcnow().isoformat(),
        git_commit='abc123def456',
        signer_id='signer@smartdairy.com',
        hardware_target='raspberrypi4',
        minimum_hardware_version='1.0'
    )
    
    signer = FirmwareSigner()
    signer.sign_firmware(
        firmware_path='/build/firmware.bin',
        output_path='/output/firmware_v2.1.3.signed.json',
        metadata=metadata
    )


if __name__ == '__main__':
    main()
```

### 2.3 Hardware Security Modules (HSM)

#### 2.3.1 HSM Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    HARDWARE SECURITY MODULE ARCHITECTURE                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  TIER 1: CLOUD HSM (AWS CloudHSM)                                            │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Root CA key storage                                               │   │
│  │  • Firmware signing keys                                             │   │
│  │  • Certificate authority operations                                  │   │
│  │  • FIPS 140-2 Level 3 compliance                                     │   │
│  │  • Multi-factor authentication required                              │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                    ▲                                         │
│                     Secure Channel │ (TLS 1.3 + mTLS)                         │
│                                    ▼                                         │
│  TIER 2: GATEWAY HSM (NitroKey / YubiKey / TPM 2.0)                          │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Device identity keys                                              │   │
│  │  • MQTT client certificates                                          │   │
│  │  • Secure key generation                                             │   │
│  │  • Hardware-backed credential storage                                │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                    ▲                                         │
│                     Secure Provisioning │                                    │
│                                    ▼                                         │
│  TIER 3: DEVICE SECURITY CHIP (ATECC608A / SE050)                            │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Sensor device identity                                            │   │
│  │  • Secure element for crypto operations                              │   │
│  │  • Tamper detection                                                  │   │
│  │  • Secure boot key storage                                           │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  KEY HIERARCHY:                                                              │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │                                                                    │     │
│  │   L0: ROOT KEY (Cloud HSM)                                         │     │
│  │   └── Only for signing intermediate CAs                           │     │
│  │                                                                    │     │
│  │   L1: INTERMEDIATE CA (Cloud HSM)                                  │     │
│  │   └── Signs device and gateway certificates                       │     │
│  │                                                                    │     │
│  │   L2: DEVICE KEYS (Gateway HSM / Device Secure Element)            │     │
│  │   └── Signs firmware, authenticates to cloud                       │     │
│  │                                                                    │     │
│  │   L3: SESSION KEYS (Derived)                                       │     │
│  │   └── Encrypts device communications                               │     │
│  │                                                                    │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 2.3.2 TPM 2.0 Integration

```bash
#!/bin/bash
# /usr/local/bin/tpm-setup.sh
# TPM 2.0 setup and key provisioning for IoT Gateway

set -e

TPM_DEVICE="/dev/tpm0"
KEY_STORE="/etc/smartdairy/tpm"

# Check TPM availability
check_tpm() {
    if [ ! -e "$TPM_DEVICE" ]; then
        echo "[-] TPM device not found at $TPM_DEVICE"
        exit 1
    fi
    
    if ! tpm2_getcap properties-fixed > /dev/null 2>&1; then
        echo "[-] TPM not responding"
        exit 1
    fi
    
    echo "[+] TPM detected and responsive"
}

# Initialize TPM and create primary key
initialize_tpm() {
    echo "[*] Initializing TPM..."
    
    mkdir -p "$KEY_STORE"
    
    # Clear TPM (if allowed and not already provisioned)
    # tpm2_clear -c p
    
    # Take ownership with authorization
    tpm2_changeauth -c owner "${TPM_OWNER_AUTH:-ownerauth}"
    tpm2_changeauth -c endorsement "${TPM_ENDORSEMENT_AUTH:-endorsementauth}"
    tpm2_changeauth -c lockout "${TPM_LOCKOUT_AUTH:-lockoutauth}"
    
    # Create primary key hierarchy
    tpm2_createprimary -C o -g sha256 -G rsa2048 \
        -c "$KEY_STORE/primary.ctx" \
        -a "fixedtpm|fixedparent|sensitivedataorigin|userwithauth|noda|restricted|decrypt"
    
    echo "[+] TPM initialized"
}

# Create device identity key
create_device_identity() {
    echo "[*] Creating device identity key..."
    
    DEVICE_ID=$(hostname)
    
    # Create key pair under primary key
    tpm2_create -C "$KEY_STORE/primary.ctx" \
        -g sha256 -G rsa2048 \
        -u "$KEY_STORE/device_identity.pub" \
        -r "$KEY_STORE/device_identity.priv" \
        -a "fixedtpm|fixedparent|sensitivedataorigin|userwithauth|sign"
    
    # Load key into TPM
    tpm2_load -C "$KEY_STORE/primary.ctx" \
        -u "$KEY_STORE/device_identity.pub" \
        -r "$KEY_STORE/device_identity.priv" \
        -c "$KEY_STORE/device_identity.ctx"
    
    # Make key persistent
    HANDLE=$(tpm2_evictcontrol -C o -c "$KEY_STORE/device_identity.ctx" | grep persistent-handle | awk '{print $2}')
    echo "$HANDLE" > "$KEY_STORE/device_identity.handle"
    
    # Export public key
    tpm2_readpublic -c "$KEY_STORE/device_identity.ctx" -o "$KEY_STORE/device_identity.pem"
    
    echo "[+] Device identity key created (handle: $HANDLE)"
}

# Create MQTT client certificate key
create_mqtt_key() {
    echo "[*] Creating MQTT client key..."
    
    # Create key for MQTT client certificate
    tpm2_create -C "$KEY_STORE/primary.ctx" \
        -g sha256 -G rsa2048 \
        -u "$KEY_STORE/mqtt_client.pub" \
        -r "$KEY_STORE/mqtt_client.priv" \
        -a "fixedtpm|fixedparent|sensitivedataorigin|userwithauth|decrypt|sign"
    
    # Load and make persistent
    tpm2_load -C "$KEY_STORE/primary.ctx" \
        -u "$KEY_STORE/mqtt_client.pub" \
        -r "$KEY_STORE/mqtt_client.priv" \
        -c "$KEY_STORE/mqtt_client.ctx"
    
    HANDLE=$(tpm2_evictcontrol -C o -c "$KEY_STORE/mqtt_client.ctx" | grep persistent-handle | awk '{print $2}')
    echo "$HANDLE" > "$KEY_STORE/mqtt_client.handle"
    
    echo "[+] MQTT client key created (handle: $HANDLE)"
}

# Generate Certificate Signing Request
generate_csr() {
    echo "[*] Generating Certificate Signing Request..."
    
    DEVICE_ID=$(hostname)
    FARM_ID="farm01"
    
    # Create CSR using TPM key
    openssl req -new \
        -key <(tpm2_print -t PEM -f pem -c "$KEY_STORE/device_identity.ctx") \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT/CN=$DEVICE_ID" \
        -out "$KEY_STORE/device.csr"
    
    # Create CSR with extensions
    cat > "$KEY_STORE/openssl.cnf" << EOF
[req]
distinguished_name = req_distinguished_name
req_extensions = v3_req
prompt = no

[req_distinguished_name]
C = BD
ST = Dhaka
L = Dhaka
O = Smart Dairy Ltd
OU = IoT
CN = $DEVICE_ID

[v3_req]
keyUsage = digitalSignature, keyEncipherment
extendedKeyUsage = clientAuth
subjectAltName = @alt_names

[alt_names]
DNS.1 = $DEVICE_ID.smartdairy.local
DNS.2 = $DEVICE_ID.$FARM_ID.smartdairy
URI.1 = urn:smartdairy:device:$DEVICE_ID
EOF
    
    openssl req -new \
        -config "$KEY_STORE/openssl.cnf" \
        -key <(tpm2_print -t PEM -f pem -c "$KEY_STORE/device_identity.ctx") \
        -out "$KEY_STORE/device.csr"
    
    echo "[+] CSR generated: $KEY_STORE/device.csr"
    echo "    Submit this to the CA for signing"
}

# Seal sensitive data (e.g., WiFi passwords)
seal_data() {
    local data="$1"
    local sealed_file="$2"
    
    echo "$data" | tpm2_createpolicy -L sha256:0 -l policy.dat
    tpm2_create -C "$KEY_STORE/primary.ctx" \
        -i - \
        -L policy.dat \
        -u "$sealed_file.pub" \
        -r "$sealed_file.priv" <<< "$data"
    
    echo "[+] Data sealed to TPM"
}

# Main execution
case "$1" in
    check)
        check_tpm
        ;;
    init)
        check_tpm
        initialize_tpm
        ;;
    identity)
        create_device_identity
        ;;
    mqtt)
        create_mqtt_key
        ;;
    csr)
        generate_csr
        ;;
    seal)
        if [ -z "$2" ] || [ -z "$3" ]; then
            echo "Usage: $0 seal <data> <output_file>"
            exit 1
        fi
        seal_data "$2" "$3"
        ;;
    full)
        check_tpm
        initialize_tpm
        create_device_identity
        create_mqtt_key
        generate_csr
        echo "[+] Full TPM setup complete"
        ;;
    *)
        echo "Usage: $0 {check|init|identity|mqtt|csr|seal|full}"
        exit 1
        ;;
esac
```

---

## 3. Communication Security

### 3.1 TLS 1.3 for MQTT

#### 3.1.1 MQTT Security Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     MQTT COMMUNICATION SECURITY                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  DEVICE LAYER                           CLOUD LAYER                          │
│  ┌─────────────────┐                   ┌─────────────────┐                   │
│  │  IoT Sensor     │                   │  AWS IoT Core   │                   │
│  │  (TLS 1.3)      │◄─────────────────▶│  (MQTT Broker)  │                   │
│  └─────────────────┘   mTLS + Cert    └─────────────────┘                   │
│          │                                      │                            │
│          │                                      │                            │
│          ▼                                      ▼                            │
│  ┌─────────────────┐                   ┌─────────────────┐                   │
│  │  IoT Gateway    │◄─────────────────▶│  API Gateway    │                   │
│  │  (TLS 1.3)      │   mTLS + JWT      │  (Mutual TLS)   │                   │
│  └─────────────────┘                   └─────────────────┘                   │
│          │                                      │                            │
│          │                                      │                            │
│          ▼                                      ▼                            │
│  ┌─────────────────┐                   ┌─────────────────┐                   │
│  │  Mobile App     │◄─────────────────▶│  Web Backend    │                   │
│  │  (TLS 1.3)      │   Token-based     │  (JWT/OAuth2)   │                   │
│  └─────────────────┘                   └─────────────────┘                   │
│                                                                              │
│  SECURITY STACK:                                                             │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  Protocol Layer: MQTT v5.0 / MQTT over WebSocket                   │     │
│  │  Security Layer: TLS 1.3 (RFC 8446)                                │     │
│  │  Authentication: X.509v3 Client Certificates + mTLS               │     │
│  │  Authorization:  Policy-based (IoT Policy Engine)                  │     │
│  │  Key Exchange:   ECDHE with X25519 or P-256                        │     │
│  │  Cipher Suites:  TLS_AES_256_GCM_SHA384                           │     │
│  │                  TLS_CHACHA20_POLY1305_SHA256                     │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 3.1.2 Mosquitto TLS 1.3 Configuration

```
# /etc/mosquitto/conf.d/tls.conf
# Smart Dairy MQTT Broker - TLS 1.3 Configuration

# =============================================================================
# LISTENER CONFIGURATION
# =============================================================================

# Standard MQTT with TLS (Port 8883)
listener 8883
protocol mqtt

# TLS 1.3 Configuration
tls_version tlsv1.3
# Note: For mixed environments, use tlsv1.2 for compatibility
ciphersuites TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256

# Certificate files
cafile /etc/mosquitto/certs/ca.crt
certfile /etc/mosquitto/certs/server.crt
keyfile /etc/mosquitto/certs/server.key

# Require client certificates (mTLS)
require_certificate true
use_identity_as_username true

# Certificate verification depth
certfile_depth 2

# CRL checking
crlfile /etc/mosquitto/certs/ca.crl

# =============================================================================
# WEBSOCKET TLS LISTENER
# =============================================================================

listener 8083
protocol websockets
tls_version tlsv1.3
cafile /etc/mosquitto/certs/ca.crt
certfile /etc/mosquitto/certs/server.crt
keyfile /etc/mosquitto/certs/server.key
require_certificate false

# =============================================================================
# SECURITY SETTINGS
# =============================================================================

# Disable insecure TLS versions
tls_version tlsv1.3

# Enable perfect forward secrecy
ciphersuites TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256

# Certificate verification options
require_certificate true
use_identity_as_username true

# Password authentication fallback (for legacy devices)
# Only use if client certs cannot be deployed
# password_file /etc/mosquitto/passwd

# =============================================================================
# PERFORMANCE TUNING FOR TLS
# =============================================================================

# Connection limits
max_connections 1000
max_inflight_messages 20
max_queued_messages 1000

# TLS session resumption (improves performance for reconnecting clients)
# Requires OpenSSL 1.1.1 or later
tls_session_resumption true
tls_session_timeout 300

# =============================================================================
# LOGGING
# =============================================================================

log_type error
log_type warning
log_type information
log_type debug
log_type tls
connection_messages true
log_timestamp true
log_timestamp_format %Y-%m-%dT%H:%M:%S
```

#### 3.1.3 OpenSSL TLS 1.3 Configuration

```
# /etc/ssl/openssl.cnf
# OpenSSL configuration for Smart Dairy IoT

[openssl_init]
ssl_conf = ssl_sect

[ssl_sect]
system_default = system_default_sect

[system_default_sect]
# Minimum TLS version
MinProtocol = TLSv1.3
MaxProtocol = TLSv1.3

# Cipher suites preference (TLS 1.3)
CipherString = DEFAULT
CipherSuites = TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256:TLS_AES_128_GCM_SHA256

# Elliptic curves for key exchange
Curves = X25519:P-256:P-384

# Certificate verification
VerifyMode = Peer
VerifyDepth = 3

# Session settings
Options = ServerPreference,NoRenegotiation
```

### 3.2 Certificate-Based Authentication

#### 3.2.1 PKI Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PUBLIC KEY INFRASTRUCTURE ARCHITECTURE                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ROOT CA (Offline - HSM Protected)                                           │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  • Smart Dairy IoT Root CA                                           │   │
│  │  • Validity: 20 years                                                │   │
│  │  • Key: RSA 4096-bit or ECDSA P-384                                  │   │
│  │  • Storage: Offline HSM in secure facility                           │   │
│  │  • Usage: Signing Intermediate CAs only                              │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                    │                                         │
│               ┌────────────────────┼────────────────────┐                   │
│               ▼                    ▼                    ▼                   │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐          │
│  │  Device CA       │  │  Gateway CA      │  │  Cloud CA        │          │
│  │  (Intermediate)  │  │  (Intermediate)  │  │  (Intermediate)  │          │
│  ├──────────────────┤  ├──────────────────┤  ├──────────────────┤          │
│  │ • Signs sensor   │  │ • Signs gateway  │  │ • Signs server   │          │
│  │   certificates   │  │   certificates   │  │   certificates   │          │
│  │ • Online HSM     │  │ • Online HSM     │  │ • Online HSM     │          │
│  │ • 5 year validity│  │ • 5 year validity│  │ • 5 year validity│          │
│  └────────┬─────────┘  └────────┬─────────┘  └────────┬─────────┘          │
│           │                     │                     │                     │
│           ▼                     ▼                     ▼                     │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐          │
│  │  Device Certs    │  │  Gateway Certs   │  │  Server Certs    │          │
│  │  • Per-device    │  │  • Per-gateway   │  │  • MQTT Broker   │          │
│  │    identity      │  │    identity      │  │  • API Gateway   │          │
│  │  • 1 year valid  │  │  • 1 year valid  │  │  • 1 year valid  │          │
│  │  • Auto-renewal  │  │  • Auto-renewal  │  │  • Auto-renewal  │          │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘          │
│                                                                              │
│  CERTIFICATE ATTRIBUTES:                                                     │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  Subject Fields:                                                    │     │
│  │    C=BD, ST=Dhaka, L=Dhaka, O=Smart Dairy Ltd, OU=IoT               │     │
│  │    CN=<device-id>.<farm-id>.smartdairy                              │     │
│  │                                                                    │     │
│  │  Subject Alternative Names:                                         │     │
│  │    DNS:<device-id>.smartdairy.local                                 │     │
│  │    URI:urn:smartdairy:device:<device-id>                            │     │
│  │                                                                    │     │
│  │  Extensions:                                                        │     │
│  │    Key Usage: digitalSignature, keyEncipherment                     │     │
│  │    Extended Key Usage: clientAuth                                   │     │
│  │    CRL Distribution Point: http://pki.smartdairy.com/crl            │     │
│  │    Authority Info Access: http://pki.smartdairy.com/ocsp            │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 3.2.2 Certificate Generation Scripts

```bash
#!/bin/bash
# /usr/local/bin/generate-iot-certs.sh
# Certificate generation script for Smart Dairy IoT devices

set -e

# Configuration
ROOT_CA_DIR="/etc/smartdairy/pki/root"
DEVICE_CA_DIR="/etc/smartdairy/pki/device-ca"
GATEWAY_CA_DIR="/etc/smartdairy/pki/gateway-ca"
OUTPUT_DIR="/var/lib/smartdairy/certificates"
VALIDITY_DAYS=365
KEY_SIZE=2048

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Initialize PKI directories
init_pki() {
    log_info "Initializing PKI directories..."
    
    for dir in "$ROOT_CA_DIR" "$DEVICE_CA_DIR" "$GATEWAY_CA_DIR" "$OUTPUT_DIR"; do
        mkdir -p "$dir"
        chmod 700 "$dir"
    done
    
    mkdir -p "$OUTPUT_DIR"/{devices,gateways,servers}
    
    log_info "PKI directories created"
}

# Generate Root CA
generate_root_ca() {
    if [ -f "$ROOT_CA_DIR/ca.crt" ]; then
        log_warn "Root CA already exists. Skipping generation."
        return 0
    fi
    
    log_info "Generating Root CA..."
    
    # Generate private key
    openssl genrsa -out "$ROOT_CA_DIR/ca.key" 4096
    chmod 600 "$ROOT_CA_DIR/ca.key"
    
    # Create self-signed certificate
    openssl req -new -x509 -key "$ROOT_CA_DIR/ca.key" \
        -sha384 -days 7300 \
        -out "$ROOT_CA_DIR/ca.crt" \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT/CN=Smart Dairy IoT Root CA" \
        -config <(cat <<EOF
[req]
distinguished_name = req_distinguished_name
x509_extensions = v3_ca
[req_distinguished_name]
[v3_ca]
subjectKeyIdentifier=hash
authorityKeyIdentifier=keyid:always,issuer
basicConstraints=critical,CA:true
keyUsage=critical,keyCertSign,cRLSign
EOF
)
    
    log_info "Root CA generated successfully"
}

# Generate Intermediate CA
generate_intermediate_ca() {
    local ca_name="$1"
    local ca_dir="$2"
    local ou="$3"
    
    if [ -f "$ca_dir/ca.crt" ]; then
        log_warn "$ca_name CA already exists. Skipping generation."
        return 0
    fi
    
    log_info "Generating $ca_name Intermediate CA..."
    
    # Generate private key
    openssl genrsa -out "$ca_dir/ca.key" 4096
    chmod 600 "$ca_dir/ca.key"
    
    # Create CSR
    openssl req -new -key "$ca_dir/ca.key" \
        -out "$ca_dir/ca.csr" \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=$ou/CN=Smart Dairy $ca_name CA"
    
    # Sign with Root CA
    openssl x509 -req -in "$ca_dir/ca.csr" \
        -CA "$ROOT_CA_DIR/ca.crt" -CAkey "$ROOT_CA_DIR/ca.key" \
        -CAcreateserial -out "$ca_dir/ca.crt" \
        -days 1825 -sha384 \
        -extfile <(cat <<EOF
basicConstraints=critical,CA:true,pathlen:0
keyUsage=critical,keyCertSign,cRLSign
subjectKeyIdentifier=hash
authorityKeyIdentifier=keyid,issuer
EOF
)
    
    # Create certificate chain
    cat "$ca_dir/ca.crt" "$ROOT_CA_DIR/ca.crt" > "$ca_dir/ca-chain.crt"
    
    log_info "$ca_name Intermediate CA generated successfully"
}

# Generate device certificate
generate_device_cert() {
    local device_id="$1"
    local farm_id="${2:-farm01}"
    local cert_dir="$OUTPUT_DIR/devices/$device_id"
    
    log_info "Generating certificate for device: $device_id"
    
    mkdir -p "$cert_dir"
    
    # Generate private key
    openssl genrsa -out "$cert_dir/device.key" $KEY_SIZE
    chmod 600 "$cert_dir/device.key"
    
    # Create CSR with device attributes
    openssl req -new -key "$cert_dir/device.key" \
        -out "$cert_dir/device.csr" \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT Device/CN=$device_id" \
        -config <(cat <<EOF
[req]
distinguished_name = req_distinguished_name
req_extensions = v3_req
[req_distinguished_name]
[v3_req]
keyUsage = digitalSignature, keyEncipherment
extendedKeyUsage = clientAuth
EOF
)
    
    # Create extensions file
    cat > "$cert_dir/extensions.cnf" << EOF
basicConstraints=CA:FALSE
keyUsage=critical,digitalSignature,keyEncipherment
extendedKeyUsage=clientAuth
subjectKeyIdentifier=hash
authorityKeyIdentifier=keyid,issuer
subjectAltName=@alt_names

[alt_names]
DNS.1=$device_id.smartdairy.local
DNS.2=$device_id.$farm_id.smartdairy
URI.1=urn:smartdairy:device:$device_id
EOF
    
    # Sign certificate with Device CA
    openssl x509 -req -in "$cert_dir/device.csr" \
        -CA "$DEVICE_CA_DIR/ca.crt" -CAkey "$DEVICE_CA_DIR/ca.key" \
        -CAcreateserial -out "$cert_dir/device.crt" \
        -days $VALIDITY_DAYS -sha256 \
        -extfile "$cert_dir/extensions.cnf"
    
    # Create full chain
    cat "$cert_dir/device.crt" "$DEVICE_CA_DIR/ca-chain.crt" > "$cert_dir/device-fullchain.crt"
    
    # Package for deployment
    tar -czf "$cert_dir.tar.gz" -C "$OUTPUT_DIR/devices" "$device_id"
    
    log_info "Device certificate generated: $cert_dir"
}

# Generate gateway certificate
generate_gateway_cert() {
    local gateway_id="$1"
    local farm_id="${2:-farm01}"
    local cert_dir="$OUTPUT_DIR/gateways/$gateway_id"
    
    log_info "Generating certificate for gateway: $gateway_id"
    
    mkdir -p "$cert_dir"
    
    # Generate private key
    openssl genrsa -out "$cert_dir/gateway.key" 4096
    chmod 600 "$cert_dir/gateway.key"
    
    # Create CSR
    openssl req -new -key "$cert_dir/gateway.key" \
        -out "$cert_dir/gateway.csr" \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT Gateway/CN=$gateway_id"
    
    # Create extensions file with server and client auth
    cat > "$cert_dir/extensions.cnf" << EOF
basicConstraints=CA:FALSE
keyUsage=critical,digitalSignature,keyEncipherment
extendedKeyUsage=serverAuth,clientAuth
subjectKeyIdentifier=hash
authorityKeyIdentifier=keyid,issuer
subjectAltName=@alt_names

[alt_names]
DNS.1=$gateway_id.smartdairy.local
DNS.2=$gateway_id.$farm_id.smartdairy
DNS.3=localhost
IP.1=127.0.0.1
URI.1=urn:smartdairy:gateway:$gateway_id
EOF
    
    # Sign certificate
    openssl x509 -req -in "$cert_dir/gateway.csr" \
        -CA "$GATEWAY_CA_DIR/ca.crt" -CAkey "$GATEWAY_CA_DIR/ca.key" \
        -CAcreateserial -out "$cert_dir/gateway.crt" \
        -days $VALIDITY_DAYS -sha256 \
        -extfile "$cert_dir/extensions.cnf"
    
    # Create full chain
    cat "$cert_dir/gateway.crt" "$GATEWAY_CA_DIR/ca-chain.crt" > "$cert_dir/gateway-fullchain.crt"
    
    log_info "Gateway certificate generated: $cert_dir"
}

# Generate server certificate
generate_server_cert() {
    local server_name="$1"
    local cert_dir="$OUTPUT_DIR/servers/$server_name"
    
    log_info "Generating certificate for server: $server_name"
    
    mkdir -p "$cert_dir"
    
    # Generate private key
    openssl genrsa -out "$cert_dir/server.key" 4096
    chmod 600 "$cert_dir/server.key"
    
    # Create CSR
    openssl req -new -key "$cert_dir/server.key" \
        -out "$cert_dir/server.csr" \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT Infrastructure/CN=$server_name"
    
    # Create extensions file
    cat > "$cert_dir/extensions.cnf" << EOF
basicConstraints=CA:FALSE
keyUsage=critical,digitalSignature,keyEncipherment
extendedKeyUsage=serverAuth
subjectKeyIdentifier=hash
authorityKeyIdentifier=keyid,issuer
subjectAltName=@alt_names

[alt_names]
DNS.1=$server_name
DNS.2=$server_name.smartdairy.com.bd
DNS.3=*.smartdairy.com.bd
EOF
    
    # Sign with Gateway CA (or dedicated server CA)
    openssl x509 -req -in "$cert_dir/server.csr" \
        -CA "$GATEWAY_CA_DIR/ca.crt" -CAkey "$GATEWAY_CA_DIR/ca.key" \
        -CAcreateserial -out "$cert_dir/server.crt" \
        -days $VALIDITY_DAYS -sha256 \
        -extfile "$cert_dir/extensions.cnf"
    
    cat "$cert_dir/server.crt" "$GATEWAY_CA_DIR/ca-chain.crt" > "$cert_dir/server-fullchain.crt"
    
    log_info "Server certificate generated: $cert_dir"
}

# Generate Certificate Revocation List
generate_crl() {
    log_info "Generating Certificate Revocation List..."
    
    openssl ca -gencrl -keyfile "$DEVICE_CA_DIR/ca.key" \
        -cert "$DEVICE_CA_DIR/ca.crt" \
        -out "$DEVICE_CA_DIR/ca.crl" \
        -config <(cat <<EOF
[ca]
default_ca=CA_default
[CA_default]
database=$DEVICE_CA_DIR/index.txt
crlnumber=$DEVICE_CA_DIR/crlnumber
EOF
)
    
    log_info "CRL generated: $DEVICE_CA_DIR/ca.crl"
}

# Revoke a certificate
revoke_certificate() {
    local cert_path="$1"
    local ca_dir="$2"
    
    log_warn "Revoking certificate: $cert_path"
    
    openssl ca -revoke "$cert_path" \
        -keyfile "$ca_dir/ca.key" \
        -cert "$ca_dir/ca.crt" \
        -config <(cat <<EOF
[ca]
default_ca=CA_default
[CA_default]
database=$ca_dir/index.txt
EOF
)
    
    # Regenerate CRL
    generate_crl
    
    log_info "Certificate revoked successfully"
}

# Main execution
case "$1" in
    init)
        init_pki
        generate_root_ca
        generate_intermediate_ca "Device" "$DEVICE_CA_DIR" "IoT Device CA"
        generate_intermediate_ca "Gateway" "$GATEWAY_CA_DIR" "IoT Gateway CA"
        ;;
    device)
        if [ -z "$2" ]; then
            log_error "Device ID required"
            echo "Usage: $0 device <device_id> [farm_id]"
            exit 1
        fi
        generate_device_cert "$2" "${3:-farm01}"
        ;;
    gateway)
        if [ -z "$2" ]; then
            log_error "Gateway ID required"
            echo "Usage: $0 gateway <gateway_id> [farm_id]"
            exit 1
        fi
        generate_gateway_cert "$2" "${3:-farm01}"
        ;;
    server)
        if [ -z "$2" ]; then
            log_error "Server name required"
            echo "Usage: $0 server <server_name>"
            exit 1
        fi
        generate_server_cert "$2"
        ;;
    revoke)
        if [ -z "$2" ] || [ -z "$3" ]; then
            log_error "Certificate path and CA directory required"
            echo "Usage: $0 revoke <cert_path> <ca_dir>"
            exit 1
        fi
        revoke_certificate "$2" "$3"
        ;;
    crl)
        generate_crl
        ;;
    *)
        echo "Smart Dairy IoT Certificate Management"
        echo ""
        echo "Usage: $0 {init|device|gateway|server|revoke|crl}"
        echo ""
        echo "Commands:"
        echo "  init                      Initialize PKI and generate Root/Intermediate CAs"
        echo "  device <id> [farm]        Generate device certificate"
        echo "  gateway <id> [farm]       Generate gateway certificate"
        echo "  server <name>             Generate server certificate"
        echo "  revoke <cert> <ca>        Revoke a certificate"
        echo "  crl                       Generate Certificate Revocation List"
        exit 1
        ;;
esac
```

### 3.3 mTLS for Device-to-Cloud

#### 3.3.1 Mutual TLS Configuration

```python
#!/usr/bin/env python3
"""
Smart Dairy mTLS Client
Handles mutual TLS authentication for IoT devices
"""

import ssl
import socket
import json
import logging
from pathlib import Path
from dataclasses import dataclass
from typing import Optional, Tuple
import paho.mqtt.client as mqtt

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger('mTLSClient')


@dataclass
class mTLSConfig:
    """mTLS configuration parameters"""
    ca_certs: str          # Root CA certificate
    certfile: str          # Client certificate
    keyfile: str           # Client private key
    keyfile_password: Optional[str] = None
    cert_reqs: int = ssl.CERT_REQUIRED
    tls_version: int = ssl.PROTOCOL_TLS_CLIENT
    ciphers: Optional[str] = None


class mTLSMQTTClient:
    """MQTT client with mutual TLS authentication"""
    
    def __init__(self, config: mTLSConfig, device_id: str):
        self.config = config
        self.device_id = device_id
        self.client = None
        self._setup_tls()
    
    def _setup_tls(self):
        """Configure TLS context for mutual authentication"""
        
        # Create TLS context with TLS 1.3
        context = ssl.SSLContext(ssl.PROTOCOL_TLS_CLIENT)
        context.minimum_version = ssl.TLSVersion.TLSv1_3
        
        # Load CA certificates for server verification
        context.load_verify_locations(self.config.ca_certs)
        
        # Load client certificate and key for mutual auth
        context.load_cert_chain(
            certfile=self.config.certfile,
            keyfile=self.config.keyfile,
            password=self.config.keyfile_password
        )
        
        # Require server certificate verification
        context.verify_mode = ssl.CERT_REQUIRED
        
        # Check hostname matches certificate
        context.check_hostname = True
        
        # Set cipher suites (TLS 1.3 uses different mechanism)
        # For TLS 1.3, cipher suites are set at the context level
        
        # Enable certificate pinning for additional security
        self._pin_server_cert(context)
        
        self.ssl_context = context
        logger.info("TLS context configured for mutual authentication")
    
    def _pin_server_cert(self, context: ssl.SSLContext):
        """Implement certificate pinning for known servers"""
        # Load expected server certificate hash
        pin_file = Path('/etc/smartdairy/certs/server_pins.json')
        if pin_file.exists():
            with open(pin_file) as f:
                self.pinned_certs = json.load(f)
        else:
            self.pinned_certs = {}
    
    def connect(self, host: str, port: int = 8883, keepalive: int = 60):
        """Connect to MQTT broker with mTLS"""
        
        self.client = mqtt.Client(client_id=self.device_id, protocol=mqtt.MQTTv5)
        
        # Configure TLS
        self.client.tls_set_context(self.ssl_context)
        
        # Set callbacks
        self.client.on_connect = self._on_connect
        self.client.on_disconnect = self._on_disconnect
        self.client.on_message = self._on_message
        
        # Connect with retry logic
        retry_count = 0
        max_retries = 5
        
        while retry_count < max_retries:
            try:
                logger.info(f"Connecting to {host}:{port}...")
                self.client.connect(host, port, keepalive)
                self.client.loop_start()
                return True
            except ssl.SSLError as e:
                logger.error(f"TLS/SSL error: {e}")
                if "certificate verify failed" in str(e):
                    logger.error("Server certificate verification failed!")
                    raise
            except socket.error as e:
                logger.warning(f"Connection error (attempt {retry_count + 1}): {e}")
                retry_count += 1
                import time
                time.sleep(2 ** retry_count)  # Exponential backoff
        
        raise ConnectionError(f"Failed to connect after {max_retries} attempts")
    
    def _on_connect(self, client, userdata, flags, rc, properties=None):
        """Connection callback"""
        if rc == 0:
            logger.info("Connected successfully with mTLS")
            # Subscribe to device-specific topics
            client.subscribe(f"smartdairy/cmd/{self.device_id}/#")
        else:
            logger.error(f"Connection failed with code: {rc}")
    
    def _on_disconnect(self, client, userdata, rc, properties=None):
        """Disconnection callback"""
        if rc != 0:
            logger.warning(f"Unexpected disconnection (rc={rc}), attempting reconnect...")
    
    def _on_message(self, client, userdata, msg):
        """Message received callback"""
        logger.info(f"Received message on {msg.topic}")
        # Handle command messages
    
    def publish_telemetry(self, topic: str, payload: dict, qos: int = 1):
        """Publish telemetry data with QoS"""
        if self.client and self.client.is_connected():
            result = self.client.publish(topic, json.dumps(payload), qos=qos)
            if result.rc == mqtt.MQTT_ERR_SUCCESS:
                logger.debug(f"Published to {topic}")
            else:
                logger.error(f"Publish failed: {result.rc}")
        else:
            logger.error("Not connected to broker")
    
    def disconnect(self):
        """Clean disconnect"""
        if self.client:
            self.client.loop_stop()
            self.client.disconnect()
            logger.info("Disconnected from broker")


def create_mtls_config(device_type: str = 'gateway') -> mTLSConfig:
    """Create mTLS configuration based on device type"""
    
    cert_base = Path('/etc/smartdairy/certs')
    
    if device_type == 'gateway':
        return mTLSConfig(
            ca_certs=str(cert_base / 'ca.crt'),
            certfile=str(cert_base / 'gateway.crt'),
            keyfile=str(cert_base / 'gateway.key'),
            cert_reqs=ssl.CERT_REQUIRED,
            tls_version=ssl.PROTOCOL_TLS_CLIENT,
            ciphers='ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384'
        )
    else:
        return mTLSConfig(
            ca_certs=str(cert_base / 'ca.crt'),
            certfile=str(cert_base / 'device.crt'),
            keyfile=str(cert_base / 'device.key'),
            cert_reqs=ssl.CERT_REQUIRED,
            tls_version=ssl.PROTOCOL_TLS_CLIENT
        )


def main():
    """Example usage"""
    import os
    
    device_id = os.environ.get('DEVICE_ID', 'sd-gateway-001')
    mqtt_host = os.environ.get('MQTT_HOST', 'mqtt.smartdairy.com.bd')
    
    config = create_mtls_config('gateway')
    client = mTLSMQTTClient(config, device_id)
    
    try:
        client.connect(mqtt_host)
        
        # Publish sample telemetry
        client.publish_telemetry(
            f"smartdairy/telemetry/{device_id}/status",
            {"status": "online", "timestamp": "2026-01-31T10:00:00Z"}
        )
        
        # Keep running
        import time
        while True:
            time.sleep(1)
            
    except KeyboardInterrupt:
        client.disconnect()


if __name__ == '__main__':
    main()
```

---

## 4. Network Security

### 4.1 VLAN Segmentation

#### 4.1.1 Network Segmentation Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY NETWORK SEGMENTATION                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                              INTERNET                                        │
│                                 │                                            │
│                                 ▼                                            │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │                         FIREWALL (pfSense/OPNsense)                 │     │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌───────────┐  │     │
│  │  │   WAN       │  │   LAN       │  │   IOT       │  │  MGMT     │  │     │
│  │  │   (Eth0)    │  │   (Eth1)    │  │   (Eth2)    │  │  (Eth3)   │  │     │
│  │  │  VLAN 10    │  │  VLAN 20    │  │  VLAN 30    │  │  VLAN 99  │  │     │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └───────────┘  │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│          │              │              │              │                     │
│          ▼              ▼              ▼              ▼                     │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐       │
│  │   CORPORATE  │ │    USER      │ │     IOT      │ │ MANAGEMENT   │       │
│  │    VLAN 10   │ │    VLAN 20   │ │    VLAN 30   │ │   VLAN 99    │       │
│  ├──────────────┤ ├──────────────┤ ├──────────────┤ ├──────────────┤       │
│  │ • ERP Server │ │ • Office PCs │ │ • Sensors    │ │ • Jump Host  │       │
│  │ • Database   │ │ • Printers   │ │ • Gateways   │ │ • Monitoring │       │
│  │ • Web Server │ │ • Phones     │ │ • Cameras    │ │ • IDS/IPS    │       │
│  │ • Cloud Link │ │ • WiFi Users │ │ • Controllers│ │ • Admin      │       │
│  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘       │
│                                                                              │
│  IOT VLAN SUB-SEGMENTATION:                                                  │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │                                                                    │     │
│  │  VLAN 31: SENSORS        VLAN 32: ACTUATORS      VLAN 33: VIDEO    │     │
│  │  ┌──────────────┐       ┌──────────────┐       ┌──────────────┐   │     │
│  │  │ • Collar     │       │ • Feed       │       │ • CCTV       │   │     │
│  │  │   Sensors    │       │   Control    │       │ • DVR        │   │     │
│  │  │ • Climate    │       │ • Milking    │       │ • NVR        │   │     │
│  │  │   Sensors    │       │   Equipment  │       │ • Analytics  │   │     │
│  │  │ • Milk Meters│       │ • Door       │       │   Cameras    │   │     │
│  │  └──────────────┘       │   Control    │       └──────────────┘   │     │
│  │                         └──────────────┘                          │     │
│  │                                                                    │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 4.1.2 VLAN Configuration

```bash
#!/bin/bash
# /usr/local/bin/setup-vlans.sh
# VLAN configuration for Smart Dairy network switches

# Cisco Catalyst / Cisco IOS Configuration
cat > /tmp/cisco_vlan_config.txt << 'EOF'
! Smart Dairy VLAN Configuration
! Applicable to: Cisco Catalyst 2960/3650/3850

enable
configure terminal

! Create VLANs
vlan 10
 name CORPORATE
 description Corporate Network - ERP/Database

vlan 20
 name USER_ACCESS
 description User Access - Office/Workstations

vlan 30
 name IOT_GENERAL
 description IoT General Devices

vlan 31
 name IOT_SENSORS
 description IoT Sensors - Read Only

vlan 32
 name IOT_ACTUATORS
 description IoT Actuators - Control Systems

vlan 33
 name IOT_VIDEO
 description IoT Video Surveillance

vlan 99
 name MANAGEMENT
 description Network Management

! Configure trunk ports to firewall/router
interface GigabitEthernet0/24
 description Uplink to Firewall
 switchport mode trunk
 switchport trunk allowed vlan 10,20,30,31,32,33,99
 switchport trunk native vlan 99
 spanning-tree portfast trunk

! Configure access ports for IoT Gateway
interface range GigabitEthernet0/1-4
 description IoT Gateway Connections
 switchport mode access
 switchport access vlan 30
 switchport port-security
 switchport port-security maximum 2
 switchport port-security violation restrict
 spanning-tree portfast

! Configure ports for sensors (isolated)
interface range GigabitEthernet0/5-12
 description IoT Sensor Connections
 switchport mode access
 switchport access vlan 31
 switchport port-security
 switchport port-security maximum 1
 switchport port-security mac-address sticky
 switchport port-security violation shutdown
 spanning-tree portfast
 storm-control broadcast level 10
 storm-control multicast level 10

! Configure ports for actuators
interface range GigabitEthernet0/13-16
 description IoT Actuator Connections
 switchport mode access
 switchport access vlan 32
 switchport port-security
 spanning-tree portfast

! Configure ports for cameras
interface range GigabitEthernet0/17-20
 description IP Camera Connections
 switchport mode access
 switchport access vlan 33
 switchport port-security
 spanning-tree portfast

! Enable DHCP Snooping on IoT VLANs
ip dhcp snooping
ip dhcp snooping vlan 30,31,32,33

! Configure DHCP Snooping trusted ports
interface GigabitEthernet0/24
 ip dhcp snooping trust

! Enable Dynamic ARP Inspection
ip arp inspection vlan 30,31,32,33
interface GigabitEthernet0/24
 ip arp inspection trust

! Enable IP Source Guard
interface range GigabitEthernet0/5-20
 ip verify source port-security

end
write memory
EOF

echo "Cisco VLAN configuration saved to /tmp/cisco_vlan_config.txt"

# MikroTik RouterOS Configuration
cat > /tmp/mikrotik_vlan_config.rsc << 'EOF'
# Smart Dairy VLAN Configuration for MikroTik

# Create VLAN interfaces
/interface vlan
add name=vlan10-corporate vlan-id=10 interface=ether1 comment="Corporate"
add name=vlan20-users vlan-id=20 interface=ether1 comment="User Access"
add name=vlan30-iot vlan-id=30 interface=ether1 comment="IoT General"
add name=vlan31-sensors vlan-id=31 interface=ether1 comment="IoT Sensors"
add name=vlan32-actuators vlan-id=32 interface=ether1 comment="IoT Actuators"
add name=vlan33-video vlan-id=33 interface=ether1 comment="IoT Video"
add name=vlan99-mgmt vlan-id=99 interface=ether1 comment="Management"

# Configure IP addresses for VLANs
/ip address
add address=192.168.10.1/24 interface=vlan10-corporate comment="Corporate"
add address=192.168.20.1/24 interface=vlan20-users comment="Users"
add address=192.168.30.1/24 interface=vlan30-iot comment="IoT"
add address=192.168.31.1/24 interface=vlan31-sensors comment="Sensors"
add address=192.168.32.1/24 interface=vlan32-actuators comment="Actuators"
add address=192.168.33.1/24 interface=vlan33-video comment="Video"
add address=192.168.99.1/24 interface=vlan99-mgmt comment="Management"

# Configure DHCP servers
/ip dhcp-server
add name=dhcp-iot interface=vlan30-iot address-pool=pool-iot
add name=dhcp-sensors interface=vlan31-sensors address-pool=pool-sensors

/ip dhcp-server network
add address=192.168.30.0/24 gateway=192.168.30.1 dns-server=192.168.30.1
add address=192.168.31.0/24 gateway=192.168.31.1 dns-server=192.168.31.1

/ip pool
add name=pool-iot ranges=192.168.30.100-192.168.30.200
add name=pool-sensors ranges=192.168.31.100-192.168.31.200
EOF

echo "MikroTik VLAN configuration saved to /tmp/mikrotik_vlan_config.rsc"

# Netplan configuration for Ubuntu Gateways
cat > /tmp/50-vlan-config.yaml << 'EOF'
# /etc/netplan/50-vlan-config.yaml
# VLAN configuration for Smart Dairy IoT Gateway

network:
  version: 2
  renderer: networkd
  ethernets:
    eth0:
      dhcp4: no
      dhcp6: no
      optional: true
  
  vlans:
    # Management VLAN
    eth0.99:
      id: 99
      link: eth0
      addresses:
        - 192.168.99.10/24
      nameservers:
        addresses:
          - 192.168.99.1
      routes:
        - to: default
          via: 192.168.99.1
    
    # IoT General VLAN
    eth0.30:
      id: 30
      link: eth0
      addresses:
        - 192.168.30.10/24
      nameservers:
        addresses:
          - 192.168.30.1
    
    # IoT Sensors VLAN
    eth0.31:
      id: 31
      link: eth0
      addresses:
        - 192.168.31.10/24
      nameservers:
        addresses:
          - 192.168.31.1
EOF

echo "Netplan VLAN configuration saved to /tmp/50-vlan-config.yaml"
```

### 4.2 Firewall Rules for IoT VLAN

#### 4.2.1 pfSense/OPNsense Firewall Rules

```
# Smart Dairy IoT Firewall Rules
# Format: pfSense/OPNsense XML export or manual rule configuration

# ============================================================================
# IOT VLAN (VLAN 30) - INBOUND RULES
# ============================================================================

# Rule 1: Allow IoT devices to MQTT Broker
pass in quick on em2_vlan30 inet proto tcp from 192.168.30.0/24 to 192.168.10.10 port 8883 flags S/SA keep state label "IoT to MQTT TLS"

# Rule 2: Allow DNS queries
pass in quick on em2_vlan30 inet proto { tcp udp } from 192.168.30.0/24 to 192.168.99.1 port 53 keep state label "IoT DNS"

# Rule 3: Allow NTP time sync
pass in quick on em2_vlan30 inet proto udp from 192.168.30.0/24 to 192.168.99.1 port 123 keep state label "IoT NTP"

# Rule 4: Block all other traffic to Corporate VLAN
block in quick on em2_vlan30 inet from 192.168.30.0/24 to 192.168.10.0/24 label "Block IoT to Corporate"

# Rule 5: Block management access
block in quick on em2_vlan30 inet from 192.168.30.0/24 to 192.168.99.0/24 label "Block IoT to Management"

# Rule 6: Allow established connections
pass in quick on em2_vlan30 inet all flags S/SA keep state label "Allow established"

# Default deny
block in log quick on em2_vlan30 inet all label "Default deny IoT"

# ============================================================================
# IOT SENSORS VLAN (VLAN 31) - RESTRICTED
# ============================================================================

# Sensors should only send data, not receive
# Rule 1: Allow outbound to MQTT broker only
pass in quick on em2_vlan31 inet proto tcp from 192.168.31.0/24 to 192.168.10.10 port 8883 flags S/SA keep state label "Sensors to MQTT"

# Rule 2: Allow DNS
pass in quick on em2_vlan31 inet proto { tcp udp } from 192.168.31.0/24 to 192.168.99.1 port 53 keep state label "Sensors DNS"

# Rule 3: Allow NTP
pass in quick on em2_vlan31 inet proto udp from 192.168.31.0/24 to 192.168.99.1 port 123 keep state label "Sensors NTP"

# Rule 4: Block all inbound to sensors (stateful firewall handles responses)
block in quick on em2_vlan31 inet proto tcp from any to 192.168.31.0/24 flags S/SA label "Block inbound to sensors"

# Default deny with logging
block in log quick on em2_vlan31 inet all label "Default deny sensors"

# ============================================================================
# IOT ACTUATORS VLAN (VLAN 32) - CONTROLLED INBOUND
# ============================================================================

# Rule 1: Allow control commands from application server
pass in quick on em2_vlan32 inet proto tcp from 192.168.10.20 to 192.168.32.0/24 port {502,4840,8883} flags S/SA keep state label "App server to actuators"

# Rule 2: Allow telemetry outbound
pass in quick on em2_vlan32 inet proto tcp from 192.168.32.0/24 to 192.168.10.10 port 8883 flags S/SA keep state label "Actuators telemetry"

# Rule 3: Allow DNS and NTP
pass in quick on em2_vlan32 inet proto { tcp udp } from 192.168.32.0/24 to 192.168.99.1 port 53 keep state label "Actuators DNS"
pass in quick on em2_vlan32 inet proto udp from 192.168.32.0/24 to 192.168.99.1 port 123 keep state label "Actuators NTP"

# Rule 4: Block direct internet access
block in quick on em2_vlan32 inet from 192.168.32.0/24 to any label "Block actuators internet"

# Default deny
block in log quick on em2_vlan32 inet all label "Default deny actuators"

# ============================================================================
# INTER-VLAN ROUTING RULES
# ============================================================================

# Corporate to IoT (restricted)
pass in quick on em1_vlan10 inet proto tcp from 192.168.10.0/24 to 192.168.30.0/24 port {22,80,443,8883} flags S/SA keep state label "Corporate to IoT limited"

# Management to all (for administration)
pass in quick on em3_vlan99 inet from 192.168.99.0/24 to any keep state label "Management access"

# Block IoT to User VLAN
block in quick on em2_vlan30 inet from 192.168.30.0/24 to 192.168.20.0/24 label "Block IoT to Users"
```

#### 4.2.2 iptables Rules for IoT Gateway

```bash
#!/bin/bash
# /usr/local/bin/iot-firewall.sh
# iptables firewall configuration for IoT Gateway

set -e

# Configuration
IOT_IFACE="eth0.30"
SENSOR_IFACE="eth0.31"
CORPORATE_NET="192.168.10.0/24"
IOT_NET="192.168.30.0/24"
SENSOR_NET="192.168.31.0/24"
MQTT_BROKER="192.168.10.10"
DNS_SERVER="192.168.99.1"
NTP_SERVER="192.168.99.1"

# Reset iptables
reset_iptables() {
    echo "[*] Resetting iptables..."
    iptables -F
    iptables -X
    iptables -t nat -F
    iptables -t nat -X
    iptables -t mangle -F
    iptables -t mangle -X
    iptables -P INPUT DROP
    iptables -P FORWARD DROP
    iptables -P OUTPUT ACCEPT
}

# Configure default chains
setup_defaults() {
    echo "[*] Setting up default chains..."
    
    # Create custom chains
    iptables -N IOT_IN
    iptables -N IOT_OUT
    iptables -N LOG_DROP
    
    # Logging for dropped packets
    iptables -A LOG_DROP -j LOG --log-prefix "[IPTABLES DROP] " --log-level 4
    iptables -A LOG_DROP -j DROP
}

# INPUT chain rules
setup_input() {
    echo "[*] Configuring INPUT chain..."
    
    # Allow loopback
    iptables -A INPUT -i lo -j ACCEPT
    
    # Allow established and related connections
    iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
    
    # Allow SSH from management network only
    iptables -A INPUT -p tcp --dport 22 -s 192.168.99.0/24 -m state --state NEW -j ACCEPT
    
    # Allow local MQTT
    iptables -A INPUT -p tcp --dport 1883 -s 127.0.0.1 -j ACCEPT
    
    # Allow MQTT TLS from IoT networks
    iptables -A INPUT -p tcp --dport 8883 -s $IOT_NET -m state --state NEW -j ACCEPT
    iptables -A INPUT -p tcp --dport 8883 -s $SENSOR_NET -m state --state NEW -j ACCEPT
    
    # Allow WebSocket MQTT
    iptables -A INPUT -p tcp --dport 9001 -s $IOT_NET -m state --state NEW -j ACCEPT
    
    # Rate limit ping
    iptables -A INPUT -p icmp --icmp-type echo-request -m limit --limit 1/second -j ACCEPT
    iptables -A INPUT -p icmp --icmp-type echo-request -j DROP
    
    # Log and drop everything else
    iptables -A INPUT -j LOG_DROP
}

# FORWARD chain rules
setup_forward() {
    echo "[*] Configuring FORWARD chain..."
    
    # Allow established connections
    iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT
    
    # IOT to MQTT Broker (TLS only)
    iptables -A FORWARD -i $IOT_IFACE -o eth0 -p tcp -d $MQTT_BROKER --dport 8883 -m state --state NEW -j ACCEPT
    iptables -A FORWARD -i $SENSOR_IFACE -o eth0 -p tcp -d $MQTT_BROKER --dport 8883 -m state --state NEW -j ACCEPT
    
    # DNS forwarding
    iptables -A FORWARD -i $IOT_IFACE -o eth0 -p udp -d $DNS_SERVER --dport 53 -j ACCEPT
    iptables -A FORWARD -i $IOT_IFACE -o eth0 -p tcp -d $DNS_SERVER --dport 53 -j ACCEPT
    iptables -A FORWARD -i $SENSOR_IFACE -o eth0 -p udp -d $DNS_SERVER --dport 53 -j ACCEPT
    
    # NTP forwarding
    iptables -A FORWARD -i $IOT_IFACE -o eth0 -p udp -d $NTP_SERVER --dport 123 -j ACCEPT
    iptables -A FORWARD -i $SENSOR_IFACE -o eth0 -p udp -d $NTP_SERVER --dport 123 -j ACCEPT
    
    # Block IoT to Corporate
    iptables -A FORWARD -i $IOT_IFACE -o eth0 -d $CORPORATE_NET -j LOG_DROP
    iptables -A FORWARD -i $SENSOR_IFACE -o eth0 -d $CORPORATE_NET -j LOG_DROP
    
    # Block inter-VLAN routing (except through application)
    iptables -A FORWARD -i $IOT_IFACE -o $SENSOR_IFACE -j DROP
    iptables -A FORWARD -i $SENSOR_IFACE -o $IOT_IFACE -j DROP
    
    # Log and drop
    iptables -A FORWARD -j LOG_DROP
}

# Rate limiting and DDoS protection
setup_rate_limits() {
    echo "[*] Setting up rate limits..."
    
    # Limit new connections per source IP
    iptables -A INPUT -p tcp --dport 8883 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
    
    # SYN flood protection
    iptables -A INPUT -p tcp --syn -m limit --limit 1/second --limit-burst 3 -j ACCEPT
    iptables -A INPUT -p tcp --syn -j DROP
    
    # Connection tracking limits per IP
    iptables -A INPUT -p tcp -m connlimit --connlimit-above 50 -j DROP
}

# Save rules
save_rules() {
    echo "[*] Saving iptables rules..."
    if command -v netfilter-persistent &> /dev/null; then
        netfilter-persistent save
    elif command -v iptables-save &> /dev/null; then
        iptables-save > /etc/iptables/rules.v4
    fi
}

# Main execution
main() {
    case "$1" in
        start)
            reset_iptables
            setup_defaults
            setup_input
            setup_forward
            setup_rate_limits
            save_rules
            echo "[+] Firewall rules applied"
            ;;
        stop)
            reset_iptables
            iptables -P INPUT ACCEPT
            iptables -P FORWARD ACCEPT
            iptables -P OUTPUT ACCEPT
            echo "[-] Firewall rules cleared"
            ;;
        status)
            echo "=== IPTABLES RULES ==="
            iptables -L -v -n
            ;;
        *)
            echo "Usage: $0 {start|stop|status}"
            exit 1
            ;;
    esac
}

main "$@"
```

### 4.3 Intrusion Detection

#### 4.3.1 Suricata Configuration

```yaml
# /etc/suricata/suricata.yaml
# Suricata IDS/IPS configuration for Smart Dairy IoT

%YAML 1.1
---
vars:
  address-groups:
    HOME_NET: "[192.168.0.0/16,10.0.0.0/8,172.16.0.0/12]"
    IOT_NET: "[192.168.30.0/24,192.168.31.0/24,192.168.32.0/24]"
    EXTERNAL_NET: "!$HOME_NET"
    MQTT_SERVERS: "[192.168.10.10]"
    
  port-groups:
    HTTP_PORTS: "80"
    MQTT_PORTS: "1883,8883,9001"
    SSH_PORTS: "22"
    MODBUS_PORTS: "502"
    OPC_UA_PORTS: "4840"

af-packet:
  - interface: eth0.30
    cluster-id: 30
    cluster-type: cluster_flow
    defrag: yes
    use-mmap: yes
    tpacket-v3: yes
    ring-size: 200000
    block-size: 32768
    block-timeout: 10
    
  - interface: eth0.31
    cluster-id: 31
    cluster-type: cluster_flow
    defrag: yes
    use-mmap: yes
    tpacket-v3: yes

# IoT-specific detection rules
default-rule-path: /var/lib/suricata/rules
rule-files:
  - botnet.rules
  - malware.rules
  - scan.rules
  - iot.rules  # Custom IoT rules
  - modbus.rules
  - dnp3.rules

detect:
  profile: medium
  custom-values:
    toclient-groups: 3
    toserver-groups: 25
  sgh-mpm-context: auto
  inspection-recursion-limit: 3000

# Stream settings for IoT protocols
stream:
  memcap: 128mb
  checksum-validation: yes
  inline: auto
  reassembly:
    memcap: 256mb
    depth: 1mb
    toserver-chunk-size: 2560
    toclient-chunk-size: 2560
    randomize-chunk-size: yes

# Logging configuration
outputs:
  - fast:
      enabled: yes
      filename: fast.log
      append: yes

  - eve-log:
      enabled: yes
      filetype: regular
      filename: eve.json
      types:
        - alert:
            tagged-packets: yes
        - http
        - dns
        - tls
        - mqtt:
            passwords: no
        - files
        - stats

  - syslog:
      enabled: yes
      facility: local5
      level: Info

# MQTT parser
app-layer:
  protocols:
    mqtt:
      enabled: yes
      max-msg-length: 1mb
      max-tx: 256
      
    modbus:
      enabled: yes
      detection-ports:
        dp: 502
        
    dnp3:
      enabled: yes
```

#### 4.3.2 Custom IoT Detection Rules

```
# /var/lib/suricata/rules/iot.rules
# Custom Suricata rules for Smart Dairy IoT security

# IoT Device Anomaly Detection
# ============================

# Detect unusual MQTT connection patterns
alert tcp $IOT_NET any -> $MQTT_SERVERS $MQTT_PORTS (msg:"IOT Suspicious MQTT connection burst"; flow:to_server,established; detection_filter:track by_src, count 10, seconds 60; sid:1000001; rev:1;)

# Detect large MQTT payloads (potential data exfiltration)
alert tcp $IOT_NET any -> $MQTT_SERVERS $MQTT_PORTS (msg:"IOT Large MQTT payload detected"; flow:to_server,established; content:"|10|"; startswith; content:"|00 04|MQTT"; content:"|00|"; distance:4; within:1; content:"|00|"; distance:1; within:1; byte_extract:2,0,payload_len,relative,big; content:"|00|"; distance:2; within:2; byte_test:2,>,1000,0,relative,big,string,dec; sid:1000002; rev:1;)

# Detect MQTT authentication failures
alert tcp $IOT_NET any -> $MQTT_SERVERS $MQTT_PORTS (msg:"IOT MQTT authentication failure"; flow:to_server,established; content:"|20|"; startswith; content:"|00 04|MQTT"; content:"|C0|"; distance:5; within:1; sid:1000003; rev:1;)

# Modbus Security Rules
# =====================

# Detect unauthorized Modbus function codes
alert tcp any any -> any $MODBUS_PORTS (msg:"IOT Modbus suspicious function code"; flow:to_server,established; content:"|00 00|"; depth:2; content:"|00 00|"; offset:4; depth:2; content:"|00 06|"; offset:4; depth:2; content:"|05|"; offset:7; depth:1; sid:1000010; rev:1;)
alert tcp any any -> any $MODBUS_PORTS (msg:"IOT Modbus write single coil"; flow:to_server,established; content:"|05|"; offset:7; depth:1; sid:1000011; rev:1;)

# Detect Modbus scanning
alert tcp any any -> any $MODBUS_PORTS (msg:"IOT Modbus scanning detected"; flow:to_server; detection_filter:track by_src, count 20, seconds 10; sid:1000012; rev:1;)

# OPC-UA Security Rules
# =====================

# Detect OPC-UA anonymous authentication
alert tcp any any -> any $OPC_UA_PORTS (msg:"IOT OPC-UA anonymous login attempt"; flow:to_server,established; content:"|00|"; offset:3; content:"anon"; sid:1000020; rev:1;)

# Network Scanning Detection
# ==========================

# Detect port scanning from IoT network
alert tcp $IOT_NET any -> any any (msg:"IOT Network port scanning detected"; flags:S,12; detection_filter:track by_src, count 50, seconds 30; sid:1000030; rev:1;)

# Detect ARP scanning
alert arp any any -> any any (msg:"IOT ARP scanning detected"; detection_filter:track by_src, count 30, seconds 10; sid:1000031; rev:1;)

# DDoS and Botnet Detection
# =========================

# Detect potential botnet C2 communication
alert tcp $IOT_NET any -> $EXTERNAL_NET any (msg:"IOT Suspicious outbound connection"; flow:to_server,established; detection_filter:track by_src, count 5, seconds 60; sid:1000040; rev:1;)

# Detect DNS tunneling (large DNS queries)
alert udp $IOT_NET any -> any 53 (msg:"IOT Potential DNS tunneling"; content:"|01 00 00 01 00 00 00 00 00 00|"; depth:10; byte_test:2,>,200,0,relative,big; sid:1000041; rev:1;)

# Drop rules for active protection (if in IPS mode)
# =================================================

drop tcp any any -> any $MODBUS_PORTS (msg:"IOT Block Modbus write operations"; flow:to_server,established; content:"|06|"; offset:7; depth:1; sid:1000100; rev:1;)
drop tcp any any -> any $MODBUS_PORTS (msg:"IOT Block Modbus force listen"; flow:to_server,established; content:"|08|"; offset:7; depth:1; sid:1000101; rev:1;)
```


        # Send alert
        if self.alert_callback:
            self.alert_callback(event)
    
    def _log_tamper_event(self, event: TamperEvent):
        """Log tamper event to file"""
        log_entry = {
            "timestamp": event.timestamp,
            "device_id": self.device_id,
            "event_type": event.event_type,
            "severity": event.severity,
            "description": event.description,
            "sensor_data": event.sensor_data
        }
        
        with open("/var/log/smartdairy/tamper_events.json", "a") as f:
            f.write(json.dumps(log_entry) + "\n")
    
    def check_all_sensors(self) -> list:
        """Check all tamper sensors"""
        events = []
        
        # Check enclosure
        if GPIO.input(self.ENCLOSURE_SWITCH_PIN) == GPIO.LOW:
            events.append(TamperEvent(
                timestamp=datetime.now().isoformat(),
                event_type="enclosure_breach",
                severity="critical",
                description="Enclosure switch indicates open",
                sensor_data={"pin_state": "low"}
            ))
        
        # Check chassis intrusion
        if GPIO.input(self.CHASSIS_INTRUSION_PIN) == GPIO.LOW:
            events.append(TamperEvent(
                timestamp=datetime.now().isoformat(),
                event_type="chassis_intrusion",
                severity="critical",
                description="Chassis intrusion detected",
                sensor_data={"pin_state": "low"}
            ))
        
        # Check tamper switch
        if GPIO.input(self.TAMPER_SWITCH_PIN) == GPIO.LOW:
            events.append(TamperEvent(
                timestamp=datetime.now().isoformat(),
                event_type="tamper_switch_triggered",
                severity="critical",
                description="Physical tamper switch activated",
                sensor_data={"pin_state": "low"}
            ))
        
        return events
    
    def is_tampered(self) -> bool:
        """Check if device has been tampered"""
        return self.tampered
    
    def secure_erase(self):
        """Perform secure erase of sensitive data"""
        logger.critical("Initiating secure erase due to tamper detection")
        # Implementation depends on hardware capabilities
        pass
    
    def cleanup(self):
        """Clean up GPIO resources"""
        GPIO.cleanup()


if __name__ == "__main__":
    def alert_handler(event: TamperEvent):
        print(f"ALERT: {event.event_type} - {event.description}")
    
    detector = TamperDetector("gateway-001", alert_handler)
    
    try:
        while True:
            events = detector.check_all_sensors()
            for event in events:
                detector._handle_tamper_event(event)
            time.sleep(1)
    except KeyboardInterrupt:
        detector.cleanup()
```

---

## 10. Incident Response

### 10.1 IoT-Specific Incident Response Procedures

#### 10.1.1 Incident Response Playbook

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    IOT SECURITY INCIDENT RESPONSE PLAYBOOK                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  INCIDENT CLASSIFICATION MATRIX                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  SEVERITY   │  RESPONSE TIME  │  NOTIFICATION          │  EXAMPLES   │   │
│  ├─────────────┼─────────────────┼────────────────────────┼─────────────┤   │
│  │  P1-Critical│  Immediate      │ CISO, CTO, MD          │ Compromised │   │
│  │             │  (15 min)       │                        │ gateway,    │   │
│  │             │                 │                        │ data breach │   │
│  ├─────────────┼─────────────────┼────────────────────────┼─────────────┤   │
│  │  P2-High    │  1 hour         │ Security Lead,         │ Device      │   │
│  │             │                 │ IoT Architect          │ hijacking,  │   │
│  │             │                 │                        │ MITM attack │   │
│  ├─────────────┼─────────────────┼────────────────────────┼─────────────┤   │
│  │  P3-Medium  │  4 hours        │ Security Team,         │ Unusual     │   │
│  │             │                 │ Farm Manager           │ traffic,    │   │
│  │             │                 │                        │ failed auth │   │
│  ├─────────────┼─────────────────┼────────────────────────┼─────────────┤   │
│  │  P4-Low     │  24 hours       │ Weekly report          │ Scanning,   │   │
│  │             │                 │                        │ policy      │   │
│  │             │                 │                        │ violations  │   │
│  └─────────────┴─────────────────┴────────────────────────┴─────────────┘   │
│                                                                              │
│  RESPONSE PROCEDURES BY INCIDENT TYPE                                        │
│  ============================================================================│
│                                                                              │
│  1. DEVICE COMPROMISE/Hijacking                                              │
│  ─────────────────────────────────                                           │
│  Detection: Abnormal device behavior, unexpected network traffic             │
│                                                                              │
│  Immediate Actions:                                                          │
│  1. Isolate device from network (block at firewall)                          │
│  2. Revoke device certificates                                               │
│  3. Capture forensic data (logs, memory dump if possible)                    │
│  4. Preserve evidence (don't power off if volatile memory needed)            │
│  5. Notify farm operator of affected device                                  │
│                                                                              │
│  Investigation:                                                              │
│  1. Analyze network traffic logs                                             │
│  2. Check for lateral movement                                               │
│  3. Identify compromise vector                                               │
│  4. Assess data exposure                                                     │
│                                                                              │
│  Recovery:                                                                   │
│  1. Factory reset device                                                     │
│  2. Reprovision with new certificates                                        │
│  3. Apply latest firmware                                                    │
│  4. Enhanced monitoring for 7 days                                           │
│                                                                              │
│  ─────────────────────────────────────────────────────────────────────────   │
│                                                                              │
│  2. MQTT BROKER COMPROMISE                                                   │
│  ──────────────────────────                                                  │
│  Detection: Unauthorized topic subscriptions, message injection              │
│                                                                              │
│  Immediate Actions:                                                          │
│  1. Enable emergency read-only mode                                          │
│  2. Block suspicious client connections                                      │
│  3. Preserve broker logs                                                     │
│  4. Activate backup broker (if HA configured)                                │
│                                                                              │
│  Investigation:                                                              │
│  1. Analyze ACL violations                                                   │
│  2. Review authentication logs                                               │
│  3. Check for credential theft                                               │
│                                                                              │
│  Recovery:                                                                   │
│  1. Rotate all broker certificates                                           │
│  2. Force password reset for all accounts                                    │
│  3. Review and tighten ACLs                                                  │
│  4. Restore from clean backup if necessary                                   │
│                                                                              │
│  ─────────────────────────────────────────────────────────────────────────   │
│                                                                              │
│  3. DATA BREACH - IoT Telemetry                                              │
│  ─────────────────────────────────                                           │
│  Detection: Unauthorized data access, unusual data export                    │
│                                                                              │
│  Immediate Actions:                                                          │
│  1. Identify scope of breach (which data, time period)                       │
│  2. Revoke compromised access tokens/certificates                            │
│  3. Enable enhanced audit logging                                            │
│  4. Preserve access logs                                                     │
│                                                                              │
│  Regulatory Actions:                                                         │
│  1. Assess if personal data affected (employee data)                         │
│  2. Notify DPA within 72 hours if required (Bangladesh DPA)                  │
│  3. Notify affected individuals if high risk                                 │
│                                                                              │
│  ─────────────────────────────────────────────────────────────────────────   │
│                                                                              │
│  4. DDoS ATTACK (from IoT botnet)                                            │
│  ─────────────────────────────────                                           │
│  Detection: Traffic spike, service degradation, device unresponsiveness      │
│                                                                              │
│  Immediate Actions:                                                          │
│  1. Activate DDoS mitigation (Cloudflare/AWS Shield)                         │
│  2. Identify attacking devices                                               │
│  3. Block attack traffic at edge                                             │
│  4. Scale up resources if needed                                             │
│                                                                              │
│  Recovery:                                                                   │
│  1. Isolate compromised devices                                              │
│  2. Update firewall rules                                                    │
│  3. Implement rate limiting                                                  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### 10.1.2 Incident Response Automation Script

```bash
#!/bin/bash
# /usr/local/bin/incident-response.sh
# Automated incident response for IoT security events

set -e

DEVICE_ID=$(hostname)
INCIDENT_LOG="/var/log/smartdairy/incidents.log"
QUARANTINE_DIR="/var/lib/smartdairy/quarantine"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$INCIDENT_LOG"
}

# Isolate device from network
isolate_device() {
    local device_ip="$1"
    
    log "[*] Isolating device: $device_ip"
    
    # Add firewall rule to block all traffic from device
    iptables -A INPUT -s "$device_ip" -j DROP
    iptables -A FORWARD -s "$device_ip" -j DROP
    iptables -A OUTPUT -d "$device_ip" -j DROP
    
    # Add to quarantine list
    echo "$device_ip,$(date -Iseconds),$DEVICE_ID" >> "$QUARANTINE_DIR/quarantine.list"
    
    # Revoke MQTT access
    mosquitto_pub -h localhost -p 1883 \
        -t "smartdairy/admin/device/revoke" \
        -m "{\"device_ip\":\"$device_ip\",\"reason\":\"security_incident\",\"timestamp\":\"$(date -Iseconds)\"}" \
        2>/dev/null || true
    
    log "[+] Device $device_ip isolated"
}

# Revoke device certificates
revoke_certificates() {
    local device_id="$1"
    
    log "[*] Revoking certificates for device: $device_id"
    
    # Add to CRL
    /usr/local/bin/generate-iot-certs.sh revoke "/var/lib/smartdairy/certificates/devices/$device_id/device.crt" "/etc/smartdairy/pki/device-ca"
    
    # Update MQTT broker
    mosquitto_pub -h localhost -p 1883 \
        -t "smartdairy/admin/cert/revoke" \
        -m "{\"device_id\":\"$device_id\",\"timestamp\":\"$(date -Iseconds)\"}" \
        2>/dev/null || true
    
    log "[+] Certificates revoked for $device_id"
}

# Collect forensic data
collect_forensics() {
    local device_id="$1"
    local incident_id="$2"
    
    log "[*] Collecting forensic data for incident: $incident_id"
    
    FORENSICS_DIR="$QUARANTINE_DIR/$incident_id"
    mkdir -p "$FORENSICS_DIR"
    
    # System information
    uname -a > "$FORENSICS_DIR/system_info.txt"
    cat /proc/version >> "$FORENSICS_DIR/system_info.txt"
    
    # Network connections
    netstat -tulpn > "$FORENSICS_DIR/network_connections.txt"
    ss -tulpn >> "$FORENSICS_DIR/network_connections.txt"
    
    # Active processes
    ps aux > "$FORENSICS_DIR/processes.txt"
    
    # Recent logs
    journalctl --since "1 hour ago" > "$FORENSICS_DIR/recent_logs.txt"
    
    # MQTT logs
    tail -n 10000 /var/log/mosquitto/mosquitto.log > "$FORENSICS_DIR/mqtt_logs.txt" 2>/dev/null || true
    
    # Firewall logs
    tail -n 5000 /var/log/iptables.log > "$FORENSICS_DIR/firewall_logs.txt" 2>/dev/null || true
    
    # Create incident report
    cat > "$FORENSICS_DIR/incident_report.json" << EOF
{
    "incident_id": "$incident_id",
    "device_id": "$device_id",
    "timestamp": "$(date -Iseconds)",
    "severity": "critical",
    "collected_data": [
        "system_info.txt",
        "network_connections.txt",
        "processes.txt",
        "recent_logs.txt",
        "mqtt_logs.txt",
        "firewall_logs.txt"
    ]
}
EOF
    
    # Create tarball for transmission
    tar -czf "$FORENSICS_DIR.tar.gz" -C "$QUARANTINE_DIR" "$incident_id"
    
    log "[+] Forensic data collected: $FORENSICS_DIR.tar.gz"
}

# Send incident alert
send_incident_alert() {
    local incident_id="$1"
    local severity="$2"
    local description="$3"
    
    log "[*] Sending incident alert: $incident_id"
    
    ALERT_PAYLOAD=$(cat <<EOF
{
    "incident_id": "$incident_id",
    "device_id": "$DEVICE_ID",
    "severity": "$severity",
    "description": "$description",
    "timestamp": "$(date -Iseconds)",
    "forensics_available": true,
    "forensics_location": "$QUARANTINE_DIR/$incident_id.tar.gz"
}
EOF
)
    
    # Publish to incident response topic
    mosquitto_pub -h localhost -p 1883 \
        -t "smartdairy/incidents/$severity" \
        -m "$ALERT_PAYLOAD" \
        2>/dev/null || true
    
    # Send email alert (if configured)
    if [ -f /etc/smartdairy/alert_email ]; then
        ALERT_EMAIL=$(cat /etc/smartdairy/alert_email)
        echo "$ALERT_PAYLOAD" | mail -s "SECURITY INCIDENT: $incident_id" "$ALERT_EMAIL" || true
    fi
    
    log "[+] Incident alert sent"
}

# Emergency shutdown of IoT services
emergency_shutdown() {
    log "[*] EMERGENCY: Shutting down IoT services"
    
    # Stop MQTT broker
    systemctl stop mosquitto || true
    
    # Stop edge processor
    systemctl stop smartdairy-edge || true
    
    # Close all MQTT ports
    iptables -A INPUT -p tcp --dport 1883 -j DROP
    iptables -A INPUT -p tcp --dport 8883 -j DROP
    iptables -A INPUT -p tcp --dport 9001 -j DROP
    
    log "[+] Emergency shutdown complete"
}

# Main incident handler
handle_incident() {
    local incident_type="$1"
    local target="$2"
    local severity="${3:-high}"
    
    INCIDENT_ID="INC-$(date +%Y%m%d%H%M%S)-$(openssl rand -hex 4)"
    
    log "=========================================="
    log "INCIDENT DETECTED: $incident_type"
    log "Incident ID: $INCIDENT_ID"
    log "Target: $target"
    log "Severity: $severity"
    log "=========================================="
    
    case "$incident_type" in
        device_compromise)
            isolate_device "$target"
            revoke_certificates "$target"
            collect_forensics "$target" "$INCIDENT_ID"
            send_incident_alert "$INCIDENT_ID" "$severity" "Device compromise detected"
            ;;
        unauthorized_access)
            isolate_device "$target"
            collect_forensics "$target" "$INCIDENT_ID"
            send_incident_alert "$INCIDENT_ID" "$severity" "Unauthorized access attempt"
            ;;
        data_exfiltration)
            isolate_device "$target"
            emergency_shutdown
            collect_forensics "$target" "$INCIDENT_ID"
            send_incident_alert "$INCIDENT_ID" "critical" "Potential data exfiltration detected"
            ;;
        ddos_attack)
            send_incident_alert "$INCIDENT_ID" "$severity" "DDoS attack detected"
            # DDoS mitigation handled by upstream
            ;;
        *)
            log "[-] Unknown incident type: $incident_type"
            ;;
    esac
    
    log "=========================================="
    log "Incident response completed: $INCIDENT_ID"
    log "=========================================="
}

# Main execution
case "$1" in
    isolate)
        isolate_device "$2"
        ;;
    revoke)
        revoke_certificates "$2"
        ;;
    forensics)
        collect_forensics "$2" "$3"
        ;;
    alert)
        send_incident_alert "$2" "$3" "$4"
        ;;
    shutdown)
        emergency_shutdown
        ;;
    handle)
        handle_incident "$2" "$3" "$4"
        ;;
    *)
        echo "Smart Dairy Incident Response System"
        echo ""
        echo "Usage: $0 {isolate|revoke|forensics|alert|shutdown|handle}"
        echo ""
        echo "Commands:"
        echo "  isolate <ip>                    Isolate device by IP"
        echo "  revoke <device_id>              Revoke device certificates"
        echo "  forensics <device_id> <inc_id>  Collect forensic data"
        echo "  alert <inc_id> <sev> <desc>     Send incident alert"
        echo "  shutdown                        Emergency shutdown"
        echo "  handle <type> <target> [sev]    Handle security incident"
        exit 1
        ;;
esac
```

---

## 11. Compliance

### 11.1 ISO 27001 Compliance

#### 11.1.1 ISO 27001 Control Mapping

| Control Domain | Control | Implementation | Evidence |
|----------------|---------|----------------|----------|
| **A.5 Information Security Policies** | A.5.1.1 Policies for Information Security | I-012 Section 1, F-007 | Document repository, approval records |
| **A.6 Organization of Information Security** | A.6.1.1 Roles and Responsibilities | Document control section | RACI matrix, role definitions |
| **A.8 Asset Management** | A.8.1.1 Inventory of Assets | Device inventory DB | Asset register, CMDB |
| **A.9 Access Control** | A.9.2.1 User Registration | RBAC implementation | User access logs |
| | A.9.2.6 Removal of Access | Account termination procedure | Termination tickets |
| | A.9.4.1 Restriction of Access | VLAN segmentation, ACLs | Firewall rules, network diagrams |
| **A.10 Cryptography** | A.10.1.1 Policy on Use of Cryptographic Controls | Key management policy | Encryption configuration |
| | A.10.1.2 Key Management | Section 6.3 | Key lifecycle docs |
| **A.12 Operations Security** | A.12.1.1 Operating Procedures | OTA procedures | Runbooks |
| | A.12.3.1 Information Backup | Encrypted backup script | Backup logs, test results |
| | A.12.4.1 Event Logging | Audit logging implementation | Log samples |
| | A.12.6.1 Management of Technical Vulnerabilities | Vulnerability management process | Scan reports |
| **A.13 Communications Security** | A.13.1.1 Network Controls | Firewall rules, VLANs | Network architecture |
| | A.13.2.1 Information Transfer Policies | TLS 1.3 configuration | Certificate inventory |
| **A.14 System Acquisition** | A.14.2.1 Secure Development | Secure coding guidelines | Code review records |
| | A.14.2.9 System Acceptance Testing | Security test cases | Test results |
| **A.16 Information Security Incident Management** | A.16.1.1 Responsibilities and Procedures | Incident response plan | Incident logs |
| **A.18 Compliance** | A.18.1.1 Identification of Applicable Legislation | Compliance matrix | Legal review |

### 11.2 NIST Cybersecurity Framework

#### 11.2.1 NIST CSF Implementation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    NIST CYBERSECURITY FRAMEWORK - IoT                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  IDENTIFY (ID)                                                               │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  ID.AM - Asset Management                                           │     │
│  │  • Device inventory with unique identifiers                         │     │
│  │  • Hardware/software/firmware tracking                              │     │
│  │  • Network topology documentation                                   │     │
│  │                                                                     │     │
│  │  ID.RA - Risk Assessment                                            │     │
│  │  • IoT threat modeling (Section 1.4)                                │     │
│  │  • Vulnerability assessments                                        │     │
│  │  • Risk register maintenance                                        │     │
│  │                                                                     │     │
│  │  ID.SC - Supply Chain Risk                                          │     │
│  │  • Vendor security assessments                                      │     │
│  │  • Hardware provenance verification                                 │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
│  PROTECT (PR)                                                                │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  PR.AC - Access Control                                             │     │
│  │  • X.509 certificate-based authentication                           │     │
│  │  • Role-based access control (Section 5.3)                          │     │
│  │  • Least privilege enforcement                                      │     │
│  │                                                                     │     │
│  │  PR.DS - Data Security                                              │     │
│  │  • Encryption at rest (Section 6.1)                                 │     │
│  │  • Encryption in transit (Section 6.2)                              │     │
│  │  • Secure backup and recovery                                       │     │
│  │                                                                     │     │
│  │  PR.IP - Information Protection Processes                           │     │
│  │  • Secure firmware development                                      │     │
│  │  • Security testing integration                                     │     │
│  │                                                                     │     │
│  │  PR.MA - Maintenance                                                │     │
│  │  • Secure OTA update process (Section 7)                            │     │
│  │  • Remote maintenance procedures                                    │     │
│  │                                                                     │     │
│  │  PR.PT - Protective Technology                                      │     │
│  │  • TLS 1.3 for all communications                                   │     │
│  │  • Hardware security modules                                        │     │
│  │  • Physical tamper detection (Section 9)                            │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
│  DETECT (DE)                                                                 │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  DE.AE - Anomalies and Events                                       │     │
│  │  • Continuous security monitoring (Section 8.1)                     │     │
│  │  • Anomaly detection queries (Section 8.2)                          │     │
│  │                                                                     │     │
│  │  DE.CM - Security Continuous Monitoring                             │     │
│  │  • Suricata IDS/IPS (Section 4.3)                                   │     │
│  │  • Log aggregation and analysis                                     │     │
│  │                                                                     │     │
│  │  DE.DP - Detection Processes                                        │     │
│  │  • Incident detection playbook                                      │     │
│  │  • Automated alerting thresholds                                    │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
│  RESPOND (RS)                                                                │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  RS.RP - Response Planning                                          │     │
│  │  • IoT-specific incident response plan (Section 10)                 │     │
│  │  • Device isolation procedures                                      │     │
│  │                                                                     │     │
│  │  RS.CO - Communications                                             │     │
│  │  • Incident notification procedures                                 │     │
│  │  • Stakeholder contact lists                                        │     │
│  │                                                                     │     │
│  │  RS.AN - Analysis                                                   │     │
│  │  • Forensic data collection (Section 10.1.2)                        │     │
│  │  • Root cause analysis procedures                                   │     │
│  │                                                                     │     │
│  │  RS.MI - Mitigation                                                 │     │
│  │  • Containment automation                                           │     │
│  │  • Certificate revocation procedures                                │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
│  RECOVER (RC)                                                                │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  RC.RP - Recovery Planning                                          │     │
│  │  • Device provisioning automation                                   │     │
│  │  • Backup restoration procedures                                    │     │
│  │                                                                     │     │
│  │  RC.IM - Improvements                                               │     │
│  │  • Post-incident review process                                     │     │
│  │  • Security control updates                                         │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 12. Security Testing

### 12.1 Penetration Testing

#### 12.1.1 IoT Penetration Test Plan

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    IOT PENETRATION TEST PLAN                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  SCOPE                                                                       │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  In-Scope:                                                          │     │
│  │  • IoT Gateway (hardware and software)                              │     │
│  │  • MQTT Broker infrastructure                                       │     │
│  │  • Device communication protocols (MQTT, Modbus, OPC-UA)            │     │
│  │  • Mobile applications (farmer, field sales)                        │     │
│  │  • Cloud API endpoints                                              │     │
│  │  • Physical devices (collar sensors, milk meters)                   │     │
│  │                                                                     │     │
│  │  Out-of-Scope:                                                      │     │
│  │  • Third-party vendor infrastructure (excluding interfaces)         │     │
│  │  • Social engineering (separate engagement)                         │     │
│  │  • Physical security of farm premises                               │     │
│  └────────────────────────────────────────────────────────────────────┘     │
│                                                                              │
│  TEST CATEGORIES                                                             │
│  ============================================================================│
│                                                                              │
│  1. DEVICE HARDWARE TESTING                                                  │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  Test ID    │ Test Description                │ Expected Result     │     │
│  ├─────────────┼─────────────────────────────────┼─────────────────────┤     │
│  │  HW-001     │ JTAG/UART debug port access     │ No open ports       │     │
│  │  HW-002     │ Firmware extraction             │ Encrypted/obfuscat  │     │
│  │  HW-003     │ Side-channel analysis           │ No key leakage      │     │
│  │  HW-004     │ Tamper detection bypass         │ Alerts triggered    │     │
│  │  HW-005     │ Storage media access            │ Encrypted data only │     │
│  └─────────────┴─────────────────────────────────┴─────────────────────┘     │
│                                                                              │
│  2. COMMUNICATION PROTOCOL TESTING                                           │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  Test ID    │ Test Description                │ Expected Result     │     │
│  ├─────────────┼─────────────────────────────────┼─────────────────────┤     │
│  │  COM-001    │ MQTT authentication bypass      │ Authentication req  │     │
│  │  COM-002    │ TLS downgrade attack            │ TLS 1.3 enforced    │     │
│  │  COM-003    │ Certificate validation bypass   │ Validation enforced │     │
│  │  COM-004    │ Topic injection/subscription    │ ACL enforced        │     │
│  │  COM-005    │ Modbus command injection        │ Commands validated  │     │
│  │  COM-006    │ Replay attack                   │ Sequence numbers    │     │
│  │  COM-007    │ Man-in-the-middle attack        │ Certificate pinning │     │
│  └─────────────┴─────────────────────────────────┴─────────────────────┘     │
│                                                                              │
│  3. NETWORK SECURITY TESTING                                                 │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  Test ID    │ Test Description                │ Expected Result     │     │
│  ├─────────────┼─────────────────────────────────┼─────────────────────┤     │
│  │  NET-001    │ VLAN hopping                    │ Isolation enforced  │     │
│  │  NET-002    │ Firewall rule bypass            │ Rules enforced      │     │
│  │  NET-003    │ DDoS resilience                 │ Service available   │     │
│  │  NET-004    │ Port scanning detection         │ Alerts triggered    │     │
│  │  NET-005    │ ARP spoofing                    │ DAI protection      │     │
│  └─────────────┴─────────────────────────────────┴─────────────────────┘     │
│                                                                              │
│  4. APPLICATION SECURITY TESTING                                             │
│  ┌────────────────────────────────────────────────────────────────────┐     │
│  │  Test ID    │ Test Description                │ Expected Result     │     │
│  ├─────────────┼─────────────────────────────────┼─────────────────────┤     │
│  │  APP-001    │ API injection attacks           │ Input validated     │     │
│  │  APP-002    │ Authentication bypass           │ Auth enforced       │     │
│  │  APP-003    │ Session management flaws        │ Secure sessions     │     │
│  │  APP-004    │ Insecure direct object ref      │ Authorization check │     │
│  │  APP-005    │ OTA update manipulation         │ Signature verified  │     │
│  └─────────────┴─────────────────────────────────┴─────────────────────┘     │
│                                                                              │
│  TESTING TOOLS                                                               │
│  • Wireshark/tcpdump - Protocol analysis                                     │
│  • Mosquitto clients - MQTT testing                                          │
│  • Burp Suite - Web/API testing                                              │
│  • Nmap - Network scanning                                                   │
│  • Metasploit - Exploitation framework                                       │
│  • Firmadyne - Firmware analysis                                             │
│  • JTAGulator - Hardware debugging                                           │
│                                                                              │
│  DELIVERABLES                                                                │
│  • Executive summary                                                         │
│  • Technical findings with CVSS scores                                       │
│  • Proof-of-concept demonstrations                                           │
│  • Remediation roadmap                                                       │
│  • Retest results                                                            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 12.2 Fuzzing

#### 12.2.1 IoT Protocol Fuzzing

```python
#!/usr/bin/env python3
"""
Smart Dairy IoT Protocol Fuzzer
Fuzzing framework for MQTT and other IoT protocols
"""

import random
import string
import json
import time
from typing import List, Callable, Optional
from dataclasses import dataclass
import paho.mqtt.client as mqtt
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger('IoTFuzzer')


@dataclass
class FuzzConfig:
    """Fuzzing configuration"""
    target_host: str
    target_port: int = 1883
    protocol: str = "mqtt"
    duration_seconds: int = 300
    mutation_rate: float = 0.3
    seed_corpus: Optional[List[bytes]] = None


class MQTTFuzzer:
    """MQTT protocol fuzzer"""
    
    def __init__(self, config: FuzzConfig):
        self.config = config
        self.crashes = []
        self.anomalies = []
        self.client = None
    
    def connect(self):
        """Connect to MQTT broker"""
        self.client = mqtt.Client(client_id=f"fuzzer-{random.randint(1000, 9999)}")
        try:
            self.client.connect(self.config.target_host, self.config.target_port)
            logger.info(f"Connected to {self.config.target_host}:{self.config.target_port}")
        except Exception as e:
            logger.error(f"Connection failed: {e}")
    
    def generate_random_topic(self) -> str:
        """Generate random MQTT topic"""
        parts = []
        for _ in range(random.randint(1, 5)):
            length = random.randint(1, 20)
            part = ''.join(random.choices(string.ascii_letters + string.digits + "#+/", k=length))
            parts.append(part)
        return "/".join(parts)
    
    def generate_random_payload(self, size: Optional[int] = None) -> bytes:
        """Generate random payload"""
        if size is None:
            size = random.randint(0, 65535)  # MQTT max payload
        
        # Generate various types of payloads
        payload_type = random.choice([
            "random_bytes",
            "json",
            "long_string",
            "special_chars",
            "binary",
            "format_string"
        ])
        
        if payload_type == "random_bytes":
            return bytes(random.randint(0, 255) for _ in range(size))
        
        elif payload_type == "json":
            data = {
                "data": ''.join(random.choices(string.printable, k=size//2)),
                "timestamp": time.time(),
                "nested": {"value": random.randint(-1000000, 1000000)}
            }
            return json.dumps(data).encode()
        
        elif payload_type == "long_string":
            return ('A' * size).encode()
        
        elif payload_type == "special_chars":
            special = '\x00\x01\x02\x03\x7f\xff'
            return ''.join(random.choices(special, k=size)).encode()
        
        elif payload_type == "format_string":
            fmt = random.choice(['%s', '%x', '%n', '%p', '%d'])
            return (fmt * (size // len(fmt))).encode()
        
        else:  # binary
            return bytes(random.randint(0, 255) for _ in range(size))
    
    def fuzz_publish(self):
        """Fuzz MQTT PUBLISH messages"""
        topic = self.generate_random_topic()
        payload = self.generate_random_payload()
        qos = random.randint(0, 2)
        retain = random.choice([True, False])
        
        try:
            result = self.client.publish(topic, payload, qos=qos, retain=retain)
            if result.rc != mqtt.MQTT_ERR_SUCCESS:
                logger.warning(f"Publish returned code: {result.rc}")
                self.anomalies.append({
                    "type": "publish_error",
                    "topic": topic,
                    "rc": result.rc
                })
        except Exception as e:
            logger.error(f"Publish exception: {e}")
            self.crashes.append({
                "type": "publish_crash",
                "topic": topic,
                "exception": str(e)
            })
    
    def fuzz_subscribe(self):
        """Fuzz MQTT SUBSCRIBE messages"""
        topic = self.generate_random_topic()
        qos = random.randint(0, 2)
        
        try:
            result, mid = self.client.subscribe(topic, qos=qos)
            if result != mqtt.MQTT_ERR_SUCCESS:
                logger.warning(f"Subscribe returned code: {result}")
        except Exception as e:
            logger.error(f"Subscribe exception: {e}")
    
    def fuzz_connect_packet(self):
        """Fuzz MQTT CONNECT packet parameters"""
        # Try various client ID patterns
        client_ids = [
            "",  # Empty
            "A" * 65535,  # Very long
            "\x00",  # Null byte
            "../etc/passwd",  # Path traversal
            "$(whoami)",  # Command injection
            "; DROP TABLE",  # SQL injection
        ]
        
        for client_id in client_ids:
            try:
                test_client = mqtt.Client(client_id=client_id)
                test_client.connect(self.config.target_host, self.config.target_port, keepalive=1)
                test_client.disconnect()
            except Exception as e:
                logger.error(f"Connect fuzz exception with client_id '{client_id[:50]}...': {e}")
                self.crashes.append({
                    "type": "connect_crash",
                    "client_id": client_id[:100],
                    "exception": str(e)
                })
    
    def run(self):
        """Run fuzzing session"""
        logger.info("Starting MQTT fuzzing session...")
        self.connect()
        
        start_time = time.time()
        iteration = 0
        
        while time.time() - start_time < self.config.duration_seconds:
            iteration += 1
            
            # Randomly select fuzzing strategy
            strategy = random.choice([
                self.fuzz_publish,
                self.fuzz_subscribe,
                self.fuzz_connect_packet
            ])
            
            strategy()
            
            if iteration % 100 == 0:
                logger.info(f"Completed {iteration} iterations. Crashes: {len(self.crashes)}")
            
            time.sleep(0.01)  # Rate limiting
        
        self.client.disconnect()
        
        # Generate report
        self.generate_report()
    
    def generate_report(self):
        """Generate fuzzing report"""
        report = {
            "config": {
                "target": f"{self.config.target_host}:{self.config.target_port}",
                "duration": self.config.duration_seconds
            },
            "summary": {
                "crashes_found": len(self.crashes),
                "anomalies_found": len(self.anomalies)
            },
            "crashes": self.crashes,
            "anomalies": self.anomalies
        }
        
        filename = f"fuzz_report_{int(time.time())}.json"
        with open(filename, 'w') as f:
            json.dump(report, f, indent=2)
        
        logger.info(f"Fuzzing report saved: {filename}")


if __name__ == "__main__":
    config = FuzzConfig(
        target_host="localhost",
        target_port=1883,
        duration_seconds=60
    )
    
    fuzzer = MQTTFuzzer(config)
    fuzzer.run()
```

---

## 13. Appendices

### Appendix A: Security Checklists

#### A.1 Device Deployment Security Checklist

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    DEVICE DEPLOYMENT SECURITY CHECKLIST                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PRE-DEPLOYMENT                                                              │
│  □ Device hardware verified (no unauthorized components)                     │
│  □ Firmware verified (signed, latest version)                                │
│  □ Unique device ID assigned and recorded                                    │
│  □ Device certificate generated and signed                                   │
│  □ Device registered in device inventory                                     │
│  □ Network location (IP, VLAN) assigned                                      │
│                                                                              │
│  CERTIFICATES & KEYS                                                         │
│  □ Device private key generated on device (no key transport)                 │
│  □ CSR created with correct subject attributes                               │
│  □ Certificate signed by appropriate CA                                      │
│  □ CA certificate chain installed on device                                  │
│  □ Certificate validity period verified                                      │
│  □ Certificate fingerprint recorded                                          │
│                                                                              │
│  NETWORK CONFIGURATION                                                       │
│  □ Device assigned to correct VLAN                                           │
│  □ Firewall rules configured for device type                                 │
│  □ Static DHCP reservation configured (optional)                             │
│  □ Port security enabled on switch port                                      │
│  □ DHCP snooping configured                                                  │
│                                                                              │
│  MQTT CONFIGURATION                                                          │
│  □ Device ACL entry created                                                  │
│  □ Allowed topics configured                                                 │
│  □ QoS levels defined                                                        │
│  □ TLS 1.3 enabled                                                           │
│  □ Client certificate authentication configured                              │
│                                                                              │
│  PHYSICAL SECURITY                                                           │
│  □ Device installed in secure enclosure                                      │
│  □ Tamper detection enabled                                                  │
│  □ Physical access restrictions in place                                     │
│  □ Device location documented                                                │
│                                                                              │
│  MONITORING & LOGGING                                                        │
│  □ Device added to monitoring system                                         │
│  □ Baseline behavior established                                             │
│  □ Log forwarding configured                                                 │
│  □ Alert thresholds configured                                               │
│                                                                              │
│  DOCUMENTATION                                                               │
│  □ Device configuration documented                                           │
│  □ Network diagram updated                                                   │
│  □ Asset register updated                                                    │
│  □ Responsible owner assigned                                                │
│                                                                              │
│  POST-DEPLOYMENT VERIFICATION                                                │
│  □ Device successfully connects to network                                   │
│  □ Device authenticates to MQTT broker                                       │
│  □ Telemetry data received                                                   │
│  □ Commands can be sent to device                                            │
│  □ Security logs generated                                                   │
│  □ Alerts functional                                                         │
│                                                                              │
│  SIGN-OFF                                                                    │
│  Deployed by: _________________ Date: _________________                      │
│  Verified by: _________________ Date: _________________                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix B: Configuration Files

#### B.1 Complete Mosquitto Configuration

```
# /etc/mosquitto/mosquitto.conf
# Complete Secure Configuration for Smart Dairy MQTT Broker

# =============================================================================
# GLOBAL SETTINGS
# =============================================================================

# Persistence
persistence true
persistence_location /var/lib/mosquitto/
persistence_file mosquitto.db
autosave_interval 300
autosave_on_changes false

# Logging
log_dest syslog
log_dest stdout
log_type error
log_type warning
log_type information
log_type debug
log_type websockets
connection_messages true
log_timestamp true
log_timestamp_format %Y-%m-%dT%H:%M:%S

# =============================================================================
# DEFAULT LISTENER (DISABLED - Use specific listeners only)
# =============================================================================

# Do not allow unencrypted connections
allow_anonymous false

# =============================================================================
# TLS LISTENER - Production IoT Devices
# =============================================================================

listener 8883
protocol mqtt

# TLS Configuration
tls_version tlsv1.3
ciphersuites TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256

# Certificate files
cafile /etc/mosquitto/certs/ca.crt
certfile /etc/mosquitto/certs/server.crt
keyfile /etc/mosquitto/certs/server.key

# Client certificate authentication (mTLS)
require_certificate true
use_identity_as_username true
certfile_depth 2

# Certificate Revocation List
crlfile /etc/mosquitto/certs/ca.crl

# Security settings
allow_anonymous false

# Performance
max_connections 1000
max_inflight_messages 20
max_queued_messages 1000
max_queued_bytes 104857600

# =============================================================================
# WEBSOCKET TLS LISTENER - Web Applications
# =============================================================================

listener 8083
protocol websockets

tls_version tlsv1.3
cafile /etc/mosquitto/certs/ca.crt
certfile /etc/mosquitto/certs/server.crt
keyfile /etc/mosquitto/certs/server.key

# Web apps don't need client certs
require_certificate false
use_identity_as_username false

# Password authentication for web clients
password_file /etc/mosquitto/passwd
allow_anonymous false

# =============================================================================
# BRIDGE CONFIGURATION - Cloud Connection
# =============================================================================

connection aws-iot-bridge
address a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com:8883
bridge_protocol_version mqttv311
bridge_tls_version tlsv1.3
bridge_insecure false

# AWS IoT certificates
bridge_cafile /etc/mosquitto/certs/aws-root-ca.crt
bridge_certfile /etc/mosquitto/certs/aws-bridge.crt
bridge_keyfile /etc/mosquitto/certs/aws-bridge.key

# Bridge settings
try_private false
cleansession true
keepalive_interval 60
idle_timeout 300
restart_timeout 10
start_type automatic
notifications true
notification_topic smartdairy/bridge/status

# Topic bridging
topic # out 1 smartdairy/ smartdairy/cloud/
topic smartdairy/cloud/cmd/ in 1
topic smartdairy/bridge/status both 0

# =============================================================================
# ACCESS CONTROL
# =============================================================================

acl_file /etc/mosquitto/acl

# =============================================================================
# SECURITY HARDENING
# =============================================================================

# Persistent client expiration
persistent_client_expiration 1d

# Maximum QoS
max_qos 2

# Retained message settings
retain_available true

# Maximum packet size
max_packet_size 268435455

# Maximum keepalive
max_keepalive 300

# =============================================================================
# PLUGIN CONFIGURATION (if using custom auth plugin)
# =============================================================================

# auth_plugin /usr/lib/mosquitto/auth_plugin.so
# auth_opt_backends files
```

#### B.2 Complete Firewall Rules (iptables)

```bash
#!/bin/bash
# /etc/iptables/rules.v4
# Complete IoT Firewall Rules for Smart Dairy

*filter
:INPUT DROP [0:0]
:FORWARD DROP [0:0]
:OUTPUT ACCEPT [0:0]
:DOCKER - [0:0]
:DOCKER-ISOLATION-STAGE-1 - [0:0]
:DOCKER-ISOLATION-STAGE-2 - [0:0]
:DOCKER-USER - [0:0]
:IOT_IN - [0:0]
:IOT_OUT - [0:0]
:LOG_DROP - [0:0]

# =============================================================================
# INPUT CHAIN
# =============================================================================

# Accept established connections
-A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

# Allow loopback
-A INPUT -i lo -j ACCEPT

# Drop invalid packets
-A INPUT -m state --state INVALID -j DROP

# Rate limiting for new connections
-A INPUT -p tcp -m state --state NEW -m recent --set --name NEW_CONN
-A INPUT -p tcp -m state --state NEW -m recent --update --seconds 60 --hitcount 20 --name NEW_CONN -j DROP

# Allow SSH (management only)
-A INPUT -p tcp --dport 22 -s 192.168.99.0/24 -m state --state NEW -j ACCEPT

# Allow MQTT TLS from IoT VLANs
-A INPUT -p tcp --dport 8883 -s 192.168.30.0/24 -m state --state NEW -j ACCEPT
-A INPUT -p tcp --dport 8883 -s 192.168.31.0/24 -m state --state NEW -j ACCEPT

# Allow WebSocket MQTT from corporate
-A INPUT -p tcp --dport 8083 -s 192.168.10.0/24 -m state --state NEW -j ACCEPT

# Allow monitoring
-A INPUT -p tcp --dport 9100 -s 192.168.99.0/24 -m state --state NEW -j ACCEPT

# ICMP (limited)
-A INPUT -p icmp --icmp-type echo-request -m limit --limit 1/second -j ACCEPT
-A INPUT -p icmp --icmp-type echo-request -j DROP

# Log and drop
-A INPUT -j LOG_DROP

# =============================================================================
# FORWARD CHAIN
# =============================================================================

# Accept established connections
-A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT

# Docker rules
-A FORWARD -j DOCKER-USER
-A FORWARD -j DOCKER-ISOLATION-STAGE-1
-A FORWARD -o docker0 -j DOCKER
-A FORWARD -o docker0 -m state --state NEW -j ACCEPT
-A FORWARD -i docker0 ! -o docker0 -j ACCEPT
-A FORWARD -i docker0 -o docker0 -j ACCEPT
-A DOCKER-ISOLATION-STAGE-1 -i docker0 ! -o docker0 -j DOCKER-ISOLATION-STAGE-2
-A DOCKER-ISOLATION-STAGE-1 -j RETURN
-A DOCKER-ISOLATION-STAGE-2 -o docker0 -j DROP
-A DOCKER-ISOLATION-STAGE-2 -j RETURN
-A DOCKER-USER -j RETURN

# IoT VLAN to MQTT Broker (TLS only)
-A FORWARD -i eth0.30 -o eth0 -p tcp -d 192.168.10.10 --dport 8883 -m state --state NEW -j ACCEPT
-A FORWARD -i eth0.31 -o eth0 -p tcp -d 192.168.10.10 --dport 8883 -m state --state NEW -j ACCEPT

# DNS forwarding
-A FORWARD -p udp --dport 53 -m state --state NEW -j ACCEPT
-A FORWARD -p tcp --dport 53 -m state --state NEW -j ACCEPT

# NTP forwarding
-A FORWARD -p udp --dport 123 -m state --state NEW -j ACCEPT

# Block IoT to Corporate VLAN
-A FORWARD -i eth0.30 -o eth0 -d 192.168.10.0/24 -j LOG_DROP
-A FORWARD -i eth0.31 -o eth0 -d 192.168.10.0/24 -j LOG_DROP

# Block IoT to User VLAN
-A FORWARD -i eth0.30 -o eth0 -d 192.168.20.0/24 -j LOG_DROP

# Block inter-IoT VLAN routing (isolated)
-A FORWARD -i eth0.30 -o eth0.31 -j DROP
-A FORWARD -i eth0.31 -o eth0.30 -j DROP

# Corporate to IoT (limited)
-A FORWARD -i eth0 -o eth0.30 -s 192.168.10.0/24 -p tcp --dport 8883 -m state --state NEW -j ACCEPT

# Management to all
-A FORWARD -i eth0.99 -m state --state NEW -j ACCEPT

# Log and drop
-A FORWARD -j LOG_DROP

# =============================================================================
# OUTPUT CHAIN
# =============================================================================

# Default allow output with state tracking
-A OUTPUT -m state --state NEW,ESTABLISHED,RELATED -j ACCEPT

# =============================================================================
# CUSTOM CHAINS
# =============================================================================

# Log dropped packets
-A LOG_DROP -m limit --limit 5/min -j LOG --log-prefix "[IPTABLES DROP] " --log-level 4
-A LOG_DROP -j DROP

COMMIT
```

### Appendix C: Certificate Management Templates

#### C.1 Device Certificate Template

```
Certificate:
    Data:
        Version: 3 (0x2)
        Serial Number:
            xx:xx:xx:xx:xx:xx:xx:xx
        Signature Algorithm: ecdsa-with-SHA256
        Issuer: C=BD, ST=Dhaka, L=Dhaka, O=Smart Dairy Ltd, OU=IoT Device CA, CN=Smart Dairy Device CA
        Validity
            Not Before: Jan 31 00:00:00 2026 GMT
            Not After : Jan 31 23:59:59 2027 GMT
        Subject: C=BD, ST=Dhaka, L=Dhaka, O=Smart Dairy Ltd, OU=IoT Device, CN={device-id}
        Subject Public Key Info:
            Public Key Algorithm: id-ecPublicKey
                Public-Key: (256 bit)
                pub:
                    xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:
                    xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:xx:
                    xx
                ASN1 OID: prime256v1
                NIST CURVE: P-256
        X509v3 extensions:
            X509v3 Basic Constraints:
                CA:FALSE
            X509v3 Key Usage:
                Digital Signature, Key Encipherment
            X509v3 Extended Key Usage:
                TLS Web Client Authentication
            X509v3 Subject Key Identifier:
                XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX
            X509v3 Authority Key Identifier:
                keyid:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX:XX

            X509v3 Subject Alternative Name:
                DNS:{device-id}.smartdairy.local, DNS:{device-id}.{farm-id}.smartdairy, URI:urn:smartdairy:device:{device-id}
            X509v3 CRL Distribution Points:
                Full Name:
                    URI:http://pki.smartdairy.com/crl/device-ca.crl
            Authority Information Access:
                CA Issuers - URI:http://pki.smartdairy.com/certs/device-ca.crt
                OCSP - URI:http://ocsp.smartdairy.com
```

---

*End of Document I-012: IoT Security Implementation*

*Document Control: Version 1.0 | Date: January 31, 2026 | Classification: Internal - Confidential*
