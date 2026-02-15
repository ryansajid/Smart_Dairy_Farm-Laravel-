# I-008: IoT Gateway Configuration

## Smart Dairy Ltd. - Smart Web Portal System Implementation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-008 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Architect |
| **Reviewer** | DevOps Lead |
| **Status** | Approved |
| **Classification** | Internal |

### Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | IoT Engineer | Initial document creation |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| IoT Architect | [IoT Architect Name] | _____________ | ___________ |
| DevOps Lead | [DevOps Lead Name] | _____________ | ___________ |
| Project Manager | [PM Name] | _____________ | ___________ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Hardware Selection](#2-hardware-selection)
3. [Operating System](#3-operating-system)
4. [Network Configuration](#4-network-configuration)
5. [MQTT Broker Setup](#5-mqtt-broker-setup)
6. [Edge Processing](#6-edge-processing)
7. [Protocol Translation](#7-protocol-translation)
8. [Security Configuration](#8-security-configuration)
9. [Device Management](#9-device-management)
10. [Data Buffering](#10-data-buffering)
11. [Integration with Cloud](#11-integration-with-cloud)
12. [Monitoring](#12-monitoring)
13. [Troubleshooting](#13-troubleshooting)
14. [Disaster Recovery](#14-disaster-recovery)
15. [Appendices](#15-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive configuration guidelines for IoT gateways deployed across Smart Dairy Ltd.'s farm network. It covers hardware setup, software configuration, security implementation, and operational procedures specific to Bangladesh's infrastructure conditions.

### 1.2 Scope

This document applies to:
- All edge IoT gateways installed at farm locations
- Gateway hardware procurement and configuration
- Network setup and connectivity management
- Data processing at the edge
- Cloud integration and synchronization
- Security and monitoring procedures

### 1.3 Edge Gateway Role in IoT Architecture

```
+-----------------------------------------------------------------------------+
|                           SMART DAIRY IOT ARCHITECTURE                       |
+-----------------------------------------------------------------------------+
|                                                                              |
|  +-------------------------------------------------------------------------+|
|  |                        CLOUD LAYER                                       ||
|  |  +-------------+  +-------------+  +-------------+  +-------------+     ||
|  |  |   AWS IoT   |  |  Data Lake  |  |   Analytics |  |   Web App   |     ||
|  |  |    Core     |  |  (S3/RDS)   |  |   Engine    |  |   Portal    |     ||
|  |  +-------------+  +-------------+  +-------------+  +-------------+     ||
|  +-------------------------------------------------------------------------+|
|                                    ^                                         |
|                         MQTT/MQTTS/HTTPS                                     |
|                                    |                                         |
|  +-------------------------------------------------------------------------+|
|  |                     EDGE GATEWAY LAYER                                   ||
|  |  +-------------+  +-------------+  +-------------+  +-------------+    ||
|  |  |   MQTT      |  |   Edge      |  |   Local     |  |   Protocol  |    ||
|  |  |   Broker    |  | Processing  |  |   Storage   |  |  Translator |    ||
|  |  |(Mosquitto)  |  |  (Python)   |  |  (SQLite)   |  |             |    ||
|  |  +-------------+  +-------------+  +-------------+  +-------------+    ||
|  +-------------------------------------------------------------------------+|
|                                    ^                                         |
|                           LoRa/Zigbee/Modbus/OPC-UA                          |
|                                    |                                         |
|  +-------------------------------------------------------------------------+|
|  |                     DEVICE LAYER (Sensors & Actuators)                   ||
|  |  +------------+ +------------+ +------------+ +------------+           ||
|  |  |  Collar    | |   Milk     | |  Climate   | |   Feed     |           ||
|  |  |  Sensors   | |  Meters    | |  Sensors   | | Controller |           ||
|  |  +------------+ +------------+ +------------+ +------------+           ||
|  |  +------------+ +------------+ +------------+ +------------+           ||
|  |  |   Water    | |    GPS     | |   Health   | |   Camera   |           ||
|  |  |  Sensors   | | Trackers   | | Monitors   | |  (CCTV)    |           ||
|  |  +------------+ +------------+ +------------+ +------------+           ||
|  +-------------------------------------------------------------------------+|
|                                                                              |
+-----------------------------------------------------------------------------+
```

### 1.4 Gateway Functions

The IoT gateway performs the following critical functions:

| Function | Description | Priority |
|----------|-------------|----------|
| **Protocol Translation** | Convert Modbus, OPC-UA, LoRaWAN to MQTT | Critical |
| **Data Aggregation** | Collect and batch sensor data | Critical |
| **Edge Processing** | Filter, transform, and analyze data locally | High |
| **Local Buffering** | Store data during network outages | Critical |
| **Security Enforcement** | TLS encryption, certificate management | Critical |
| **Device Management** | OTA updates, configuration, monitoring | High |
| **Network Resilience** | Multi-connectivity with failover | Critical |

---

## 2. Hardware Selection

### 2.1 Recommended Gateway Hardware

#### 2.1.1 Option A: Raspberry Pi 4 (Budget Deployment)

| Specification | Details |
|---------------|---------|
| **Model** | Raspberry Pi 4 Model B (4GB/8GB RAM) |
| **CPU** | Broadcom BCM2711, Quad-core Cortex-A72 @ 1.8GHz |
| **RAM** | 4GB or 8GB LPDDR4 |
| **Storage** | 128GB Industrial microSD or 256GB SSD via USB3 |
| **Connectivity** | Gigabit Ethernet, Dual-band WiFi, Bluetooth 5.0 |
| **GPIO** | 40-pin GPIO for sensor interfacing |
| **Power** | 5V/3A USB-C (15W) |
| **Operating Temp** | 0C to 50C (requires heatsink for Bangladesh climate) |

**Pros:**
- Low cost (~$100-150 with accessories)
- Large community support
- Easy to replace

**Cons:**
- Consumer-grade (not industrial)
- microSD card limited write cycles
- Requires additional cooling

**Bangladesh Considerations:**
- Install with large heatsink and fan
- Use industrial-grade microSD or USB SSD
- Connect to UPS for power protection

#### 2.1.2 Option B: Intel NUC (Medium Deployment)

| Specification | Details |
|---------------|---------|
| **Model** | Intel NUC 11 Essential or Pro |
| **CPU** | Intel Celeron N5105 or i3/i5/i7 |
| **RAM** | 8GB-16GB DDR4 |
| **Storage** | 256GB-512GB NVMe SSD |
| **Connectivity** | Gigabit Ethernet, WiFi 6, Bluetooth 5.2 |
| **USB Ports** | 4x USB 3.2 |
| **Power** | 19V/65W or 12V DC input |
| **Operating Temp** | 0C to 50C |

**Pros:**
- x86 architecture (better software compatibility)
- Reliable NVMe storage
- More processing power for edge analytics

**Cons:**
- Higher cost (~$400-800)
- Higher power consumption
- Requires active cooling

**Bangladesh Considerations:**
- Install in ventilated enclosure
- Use wide-input DC-DC converter (9-36V) for vehicle compatibility

#### 2.1.3 Option C: Industrial Gateway (Enterprise Deployment)

| Specification | Details |
|---------------|---------|
| **Model** | Advantech UNO-220 / Siemens IoT2050 / Beckhoff C6015 |
| **CPU** | ARM Cortex-A53 / Intel Atom x5 |
| **RAM** | 2GB-4GB DDR3L/DDR4 |
| **Storage** | 32GB-64GB eMMC + SD card slot |
| **Connectivity** | 2x Gigabit Ethernet, 4G LTE, WiFi, LoRaWAN |
| **Serial Ports** | RS-232/RS-485, CAN Bus |
| **Power** | 12-24V DC wide input |
| **Operating Temp** | -20C to 70C (industrial grade) |
| **Protection** | IP40/IP65 enclosure options |

**Pros:**
- Industrial-grade reliability
- Wide operating temperature range
- Built-in 4G LTE modem
- DIN rail mounting
- Long MTBF (Mean Time Between Failures)

**Cons:**
- Higher cost (~$600-1500)
- Limited processing power vs NUC

**Bangladesh Considerations:**
- Best option for harsh farm environments
- Built-in 4G eliminates need for external modem
- Wide voltage input tolerates fluctuations

### 2.2 Hardware Selection Matrix

| Criterion | Raspberry Pi 4 | Intel NUC | Industrial Gateway | Weight |
|-----------|---------------|-----------|-------------------|--------|
| Cost | 5/5 | 2/5 | 2/5 | 20% |
| Reliability | 2/5 | 4/5 | 5/5 | 25% |
| Processing Power | 3/5 | 5/5 | 3/5 | 15% |
| Temperature Range | 2/5 | 3/5 | 5/5 | 15% |
| Power Consumption | 5/5 | 3/5 | 4/5 | 10% |
| Connectivity | 3/5 | 4/5 | 5/5 | 10% |
| Ease of Maintenance | 5/5 | 3/5 | 3/5 | 5% |
| **Weighted Score** | **3.35** | **3.55** | **4.05** | 100% |

### 2.3 Recommended Configuration by Farm Type

| Farm Type | Recommended Hardware | Quantity | Notes |
|-----------|---------------------|----------|-------|
| Small Farm (<50 cattle) | Raspberry Pi 4 (8GB) + Industrial Case | 1 | Budget option with proper cooling |
| Medium Farm (50-200 cattle) | Intel NUC or Industrial Gateway | 1-2 | One per production zone |
| Large Farm (200-500 cattle) | Industrial Gateway (Primary) + Raspberry Pi (Backup) | 2-3 | Redundancy for critical areas |
| Corporate/Processing | Industrial Gateway (HA Pair) | 2+ | High availability cluster |

### 2.4 Essential Accessories for Bangladesh Deployment

| Accessory | Purpose | Specification |
|-----------|---------|---------------|
| **UPS** | Power backup during outages | APC Back-UPS 650VA (min 30 min runtime) |
| **Solar Kit** | Extended backup for remote farms | 100W panel + 100Ah battery + charge controller |
| **Heatsink/Case** | Thermal management | Aluminum passive heatsink case or fan-cooled |
| **Surge Protector** | Voltage fluctuation protection | 240V surge protector with MOV |
| **Industrial SD Card** | Reliable storage | SanDisk Industrial microSD (64GB+) |
| **4G LTE Modem** | Cellular backup | Huawei E3372 or Quectel EC25 |
| **Enclosure** | Dust and moisture protection | IP65 rated, with cable glands |

---

## 3. Operating System

### 3.1 Operating System Comparison

| OS | Pros | Cons | Use Case |
|----|------|------|----------|
| **Ubuntu Core 22** | Secure, transactional updates, container support, 10-year support | Limited package repository | Production gateways |
| **Raspberry Pi OS Lite (64-bit)** | Easy to use, large community, good hardware support | Less secure, manual updates | Development, small farms |
| **Yocto Linux** | Customizable, minimal footprint, industrial support | Complex to build and maintain | Custom industrial deployments |
| **BalenaOS** | Container-focused, fleet management, OTA updates | Vendor lock-in, cloud-dependent | Large fleet management |

### 3.2 Recommended: Ubuntu Core 22

Ubuntu Core is the recommended OS for production deployments due to its:
- **Snap-based architecture** for secure, transactional updates
- **Automatic rollback** on failed updates
- **Strict confinement** for enhanced security
- **10-year security maintenance** commitment
- **Small footprint** optimized for IoT devices

### 3.3 Ubuntu Core Installation

#### 3.3.1 Prerequisites
- Ubuntu Core 22 image for your hardware
- Raspberry Pi Imager or dd tool
- microSD card (32GB minimum, 64GB recommended)
- SSH key pair for authentication

#### 3.3.2 Installation Steps

```bash
# 1. Download Ubuntu Core 22 image
wget https://cdimage.ubuntu.com/ubuntu-core/22/stable/current/ubuntu-core-22-arm64+raspi.img.xz

# 2. Verify checksum
sha256sum ubuntu-core-22-arm64+raspi.img.xz

# 3. Flash to SD card (replace /dev/sdX with your device)
xzcat ubuntu-core-22-arm64+raspi.img.xz | sudo dd of=/dev/sdX bs=4M status=progress
sync

# 4. Create SSH key configuration
touch /media/$USER/system-boot/ssh/disable-password-login
mkdir -p /media/$USER/system-boot/user-data

# 5. Copy SSH public key
cat > /media/$USER/system-boot/user-data << 'EOF'
#cloud-config
ssh_authorized_keys:
  - ssh-rsa AAAAB3NzaC1yc2E...your-public-key... smartdairy@iot
EOF

# 6. Eject and boot
eject /dev/sdX
```

#### 3.3.3 First Boot Configuration

```bash
# SSH into the gateway (default user: ubuntu)
ssh ubuntu@<gateway-ip>

# Set hostname
sudo hostnamectl set-hostname sd-gateway-farm01

# Configure timezone
sudo timedatectl set-timezone Asia/Dhaka

# Install essential snaps
sudo snap install core22
sudo snap install network-manager
sudo snap install docker

# Enable automatic updates
sudo snap set system refresh.timer=02:00-04:00
sudo snap set system refresh.retain=2
```

### 3.4 Alternative: Raspberry Pi OS Lite Installation

For smaller deployments or development environments:

```bash
# 1. Download Raspberry Pi OS Lite (64-bit)
# Use Raspberry Pi Imager to flash

# 2. Enable SSH by creating empty 'ssh' file in boot partition
touch /boot/ssh

# 3. Configure WiFi (optional)
cat > /boot/wpa_supplicant.conf << 'EOF'
country=BD
ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
update_config=1

network={
    ssid="FarmNetwork_5G"
    psk="your-secure-password"
    key_mgmt=WPA-PSK
}
EOF

# 4. Boot and configure
# Default user: pi (change immediately)
ssh pi@<gateway-ip>

# Update system
sudo apt update && sudo apt full-upgrade -y

# Install required packages
sudo apt install -y \
    mosquitto mosquitto-clients \
    docker.io docker-compose \
    python3-pip python3-venv \
    sqlite3 \
    htop iotop \
    vim nano \
    curl wget \
    net-tools \
    ufw \
    openvpn \
    chrony

# Enable services
sudo systemctl enable --now mosquitto
sudo systemctl enable --now docker
sudo systemctl enable --now chrony
```

---

## 4. Network Configuration

### 4.1 Network Architecture

```
+-----------------------------------------------------------------------------+
|                         FARM GATEWAY NETWORK                                 |
+-----------------------------------------------------------------------------+
|                                                                              |
|  +-------------------------------------------------------------------------+|
|  |                     INTERNET (Cloud)                                     ||
|  |         AWS IoT Core / Azure IoT Hub / Dashboard                         ||
|  +------------------------+------------------------------+                  ||
|                           |                                                |
|              +------------+------------+                                   |
|              |            |            |                                   |
|         Primary     Secondary     Tertiary                                 |
|       (Ethernet)    (4G LTE)    (WiFi Client)                              |
|              |            |            |                                   |
|              v            v            v                                   |
|  +-------------------------------------------------------------------------+|
|  |                    GATEWAY ROUTING TABLE                                 ||
|  |  Metric  Interface    Purpose                                            ||
|  |  -----------------------------------------------------------------      ||
|  |  100     eth0         Primary (Fiber/Broadband)                         ||
|  |  200     wwan0        Secondary (4G LTE failover)                       ||
|  |  300     wlan0        Tertiary (WiFi client/backup)                     ||
|  |                                                                          ||
|  |  Default route via eth0 (metric 100)                                    ||
|  |  Failover to wwan0 if eth0 down                                         ||
|  +-------------------------------------------------------------------------+|
|                           |                                                |
|                           v                                                |
|  +-------------------------------------------------------------------------+|
|  |                   LOCAL DEVICE NETWORK                                   ||
|  |  +---------+  +---------+  +---------+  +---------+                     ||
|  |  | Sensors |  | Sensors |  | Sensors |  | Sensors |                     ||
|  |  | LoRa    |  | Zigbee  |  | Modbus  |  | OPC-UA  |                     ||
|  |  | (USB)   |  | (USB)   |  | (RS485) |  | (ETH)   |                     ||
|  |  +---------+  +---------+  +---------+  +---------+                     ||
|  +-------------------------------------------------------------------------+|
|                                                                              |
+-----------------------------------------------------------------------------+
```

### 4.2 Network Interface Configuration

#### 4.2.1 Netplan Configuration (Ubuntu)

```yaml
# /etc/netplan/01-network-config.yaml
network:
  version: 2
  ethernets:
    eth0:
      dhcp4: true
      dhcp6: false
      # Static IP option for reliability
      # addresses:
      #   - 192.168.1.100/24
      # gateway4: 192.168.1.1
      # nameservers:
      #   addresses:
      #     - 8.8.8.8
      #     - 8.8.4.4
      
  wifis:
    wlan0:
      dhcp4: true
      dhcp6: false
      optional: true
      access-points:
        "FarmNetwork_5G":
          password: "your-wifi-password"
      # Lower priority for failover
      routes:
        - to: default
          via: 192.168.2.1
          metric: 300

  # 4G Modem (USB) - Secondary connection
  modems:
    cdc-wdm0:
      dhcp4: true
      dhcp6: false
      optional: true
      apn: "internet"  # Adjust for carrier (Grameenphone, Robi, Banglalink)
      routes:
        - to: default
          metric: 200
```

Apply configuration:
```bash
sudo netplan generate
sudo netplan apply
```

#### 4.2.2 Connection Prioritization Script

```bash
#!/bin/bash
# /usr/local/bin/network-failover.sh
# Network failover monitoring script

PRIMARY_IF="eth0"
SECONDARY_IF="wwan0"
TERTIARY_IF="wlan0"

# Test connectivity
test_connection() {
    local interface=$1
    ping -I $interface -c 3 -W 5 8.8.8.8 > /dev/null 2>&1
    return $?
}

# Check primary connection
if test_connection $PRIMARY_IF; then
    # Ensure primary has lowest metric
    if ! ip route | grep -q "default.*$PRIMARY_IF.*metric 100"; then
        sudo ip route change default dev $PRIMARY_IF metric 100
        logger "Network: Switched to primary connection ($PRIMARY_IF)"
    fi
elif test_connection $SECONDARY_IF; then
    # Failover to secondary
    if ! ip route | grep -q "default.*$SECONDARY_IF.*metric 200"; then
        sudo ip route change default dev $SECONDARY_IF metric 200
        logger "Network: Failover to secondary connection ($SECONDARY_IF)"
    fi
elif test_connection $TERTIARY_IF; then
    # Failover to tertiary
    if ! ip route | grep -q "default.*$TERTIARY_IF.*metric 300"; then
        sudo ip route change default dev $TERTIARY_IF metric 300
        logger "Network: Failover to tertiary connection ($TERTIARY_IF)"
    fi
else
    logger "Network: ALL CONNECTIONS DOWN - Operating in offline mode"
fi
```

Add to crontab:
```bash
# Check connectivity every minute
* * * * * /usr/local/bin/network-failover.sh
```

### 4.3 4G LTE Modem Configuration (Bangladesh Carriers)

#### 4.3.1 Supported Modems

| Modem | Chipset | Speed | Compatibility |
|-------|---------|-------|---------------|
| Huawei E3372 | HiSilicon | 150Mbps | Excellent |
| Quectel EC25 | Qualcomm | 150Mbps | Excellent |
| SIM7600E | Qualcomm | 150Mbps | Good |
| ZTE MF710M | Qualcomm | 100Mbps | Good |

#### 4.3.2 APN Configuration by Carrier

| Carrier | APN | Username | Password | Notes |
|---------|-----|----------|----------|-------|
| Grameenphone | gpinternet | - | - | Most reliable in rural areas |
| Robi (Airtel) | internet | - | - | Good coverage |
| Banglalink | blweb | - | - | Competitive pricing |
| Teletalk | wap | - | - | Government owned |

#### 4.3.3 Modem Setup (Using ModemManager)

```bash
# Install ModemManager
sudo apt install -y modemmanager

# Check modem detection
mmcli -L

# Get modem details
mmcli -m 0

# Configure connection
sudo mmcli -m 0 --simple-connect="apn=gpinternet"

# Enable automatic connection
cat > /etc/NetworkManager/system-connections/4G-Connection.nmconnection << 'EOF'
[connection]
id=4G-Connection
uuid=your-uuid-here
type=gsm
autoconnect=true
autoconnect-priority=2

[gsm]
apn=gpinternet
number=*99#

[ipv4]
method=auto
never-default=true

[ipv6]
method=ignore
EOF

sudo chmod 600 /etc/NetworkManager/system-connections/4G-Connection.nmconnection
sudo nmcli connection reload
sudo nmcli connection up 4G-Connection
```

### 4.4 WiFi Access Point Mode (Optional)

For local device connectivity:

```bash
# Install hostapd and dnsmasq
sudo apt install -y hostapd dnsmasq

# Configure hostapd
sudo tee /etc/hostapd/hostapd.conf << 'EOF'
interface=wlan1
driver=nl80211
ssid=SmartDairy_Gateway
hw_mode=g
channel=7
wmm_enabled=0
macaddr_acl=0
auth_algs=1
ignore_broadcast_ssid=0
wpa=2
wpa_passphrase=FarmIoT2024!
wpa_key_mgmt=WPA-PSK
wpa_pairwise=TKIP
rsn_pairwise=CCMP
EOF

# Configure dnsmasq
sudo tee /etc/dnsmasq.conf << 'EOF'
interface=wlan1
dhcp-range=192.168.4.2,192.168.4.100,255.255.255.0,24h
dhcp-option=3,192.168.4.1
dhcp-option=6,192.168.4.1
server=8.8.8.8
EOF

# Configure static IP for AP interface
sudo tee -a /etc/dhcpcd.conf << 'EOF'
interface wlan1
    static ip_address=192.168.4.1/24
    nohook wpa_supplicant
EOF

# Enable services
sudo systemctl unmask hostapd
sudo systemctl enable hostapd
sudo systemctl enable dnsmasq
```

---

## 5. MQTT Broker Setup

### 5.1 Mosquitto Installation

```bash
# Install Mosquitto
sudo apt install -y mosquitto mosquitto-clients

# Create Mosquitto configuration directory
sudo mkdir -p /etc/mosquitto/certs
sudo mkdir -p /var/lib/mosquitto
sudo chown mosquitto:mosquitto /var/lib/mosquitto
```

### 5.2 Mosquitto Configuration

```ini
# /etc/mosquitto/mosquitto.conf
# Smart Dairy IoT Gateway MQTT Broker Configuration

# =============================================================================
# BASIC SETTINGS
# =============================================================================

# Persistence
persistence true
persistence_location /var/lib/mosquitto/
autosave_interval 300

# Logging
log_dest syslog
log_dest stdout
log_dest topic
log_type error
log_type warning
log_type information
log_type debug
connection_messages true
log_timestamp true

# =============================================================================
# LISTENERS
# =============================================================================

# Local listener (insecure, for local devices only - behind firewall)
listener 1883 127.0.0.1
protocol mqtt
allow_anonymous true

# Local network listener (with authentication)
listener 1883 192.168.1.0/24
protocol mqtt
allow_anonymous false
password_file /etc/mosquitto/passwd
acl_file /etc/mosquitto/acl

# Secure TLS listener (for remote connections)
listener 8883
protocol mqtt
allow_anonymous false
password_file /etc/mosquitto/passwd
acl_file /etc/mosquitto/acl
cafile /etc/mosquitto/certs/ca.crt
certfile /etc/mosquitto/certs/server.crt
keyfile /etc/mosquitto/certs/server.key
tls_version tlsv1.2
require_certificate false
use_identity_as_username false

# WebSocket listener (for web dashboard)
listener 9001
protocol websockets
allow_anonymous false
password_file /etc/mosquitto/passwd

# =============================================================================
# AUTHENTICATION
# =============================================================================

# Password file (create with: mosquitto_passwd -c /etc/mosquitto/passwd admin)
# Additional users: mosquitto_passwd /etc/mosquitto/passwd username

# =============================================================================
# BRIDGE CONFIGURATION (To Cloud)
# =============================================================================

# AWS IoT Core Bridge
connection aws-iot-bridge
address a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com:8883
bridge_protocol_version mqttv311
bridge_insecure false
bridge_cafile /etc/mosquitto/certs/aws-root-ca.crt
bridge_certfile /etc/mosquitto/certs/aws-device.crt
bridge_keyfile /etc/mosquitto/certs/aws-device.key
try_private false
cleansession true
keepalive_interval 60
idle_timeout 300
notification_topic smartdairy/bridge/status
notifications true

# Topics to bridge to cloud (out)
topic # out 1 smartdairy/farm01/ smartdairy/data/farm01/
topic smartdairy/cmd/farm01/+ in 1

# Local topic for bridge status
topic smartdairy/bridge/status both 0

# =============================================================================
# PERFORMANCE TUNING
# =============================================================================

# Maximum connections
max_connections 100

# Message size limits
max_inflight_messages 20
max_queued_messages 1000
max_queued_bytes 104857600  # 100MB

# Retry settings
retry_interval 20
sys_interval 10

# =============================================================================
# SECURITY SETTINGS
# =============================================================================

# Allow persistent client data
persistent_client_expiration 1d

# Maximum keepalive
max_keepalive 300
```

### 5.3 User and ACL Configuration

```bash
# Create password file
sudo touch /etc/mosquitto/passwd
sudo mosquitto_passwd -b /etc/mosquitto/passwd admin SmartDairy_Admin_2024
sudo mosquitto_passwd -b /etc/mosquitto/passwd gateway FarmGateway_2024
sudo mosquitto_passwd -b /etc/mosquitto/passwd device DeviceClient_2024
sudo mosquitto_passwd -b /etc/mosquitto/passwd cloud CloudBridge_2024

# Set permissions
sudo chmod 640 /etc/mosquitto/passwd
sudo chown mosquitto:mosquitto /etc/mosquitto/passwd
```

```
# /etc/mosquitto/acl
# Smart Dairy MQTT Access Control List

# Admin user - full access
user admin
topic readwrite #

# Gateway service user
user gateway
topic readwrite smartdairy/farm01/#
topic read smartdairy/cmd/farm01/#
topic write smartdairy/bridge/#

# Device clients - limited access
user device
topic write smartdairy/farm01/sensors/#
topic write smartdairy/farm01/status/#
topic read smartdairy/cmd/farm01/${clientid}/#

# Cloud bridge - read all, write commands only
user cloud
topic read smartdairy/farm01/#
topic write smartdairy/cmd/farm01/#
topic write smartdairy/bridge/#
```

### 5.4 TLS Certificate Generation

```bash
#!/bin/bash
# /usr/local/bin/generate-mqtt-certs.sh
# Generate self-signed certificates for MQTT TLS

CERT_DIR="/etc/mosquitto/certs"
DAYS=3650

# Create directory
sudo mkdir -p $CERT_DIR
cd $CERT_DIR

# Generate CA key and certificate
sudo openssl genrsa -out ca.key 4096
sudo openssl req -new -x509 -days $DAYS -key ca.key -out ca.crt \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT/CN=SmartDairy CA"

# Generate server key and CSR
sudo openssl genrsa -out server.key 2048
sudo openssl req -new -key server.key -out server.csr \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT/CN=$(hostname)"

# Sign server certificate
sudo openssl x509 -req -in server.csr -CA ca.crt -CAkey ca.key \
    -CAcreateserial -out server.crt -days $DAYS

# Set permissions
sudo chmod 600 *.key
sudo chmod 644 *.crt
sudo chown -R mosquitto:mosquitto $CERT_DIR

# Clean up
sudo rm -f server.csr

echo "MQTT TLS certificates generated successfully in $CERT_DIR"
```

### 5.5 Restart and Test

```bash
# Restart Mosquitto
sudo systemctl restart mosquitto
sudo systemctl enable mosquitto

# Test local connection
mosquitto_sub -t "test/topic" -v &
mosquitto_pub -t "test/topic" -m "Hello from Smart Dairy Gateway"

# Test TLS connection
mosquitto_sub -h localhost -p 8883 \
    --cafile /etc/mosquitto/certs/ca.crt \
    -u gateway -P FarmGateway_2024 \
    -t "smartdairy/farm01/#" -v
```

---


## 6. Edge Processing

### 6.1 Edge Processing Architecture

```
+-----------------------------------------------------------------------------+
|                         EDGE PROCESSING PIPELINE                             |
+-----------------------------------------------------------------------------+
|                                                                              |
|  +-------------------------------------------------------------------------+|
|  |                     DATA INGESTION LAYER                                 ||
|  |                                                                          ||
|  |   MQTT Topics:                                                           ||
|  |   - smartdairy/raw/collar/{cow_id}/accelerometer                         ||
|  |   - smartdairy/raw/collar/{cow_id}/temperature                           ||
|  |   - smartdairy/raw/milk/{stall_id}/volume                                ||
|  |   - smartdairy/raw/milk/{stall_id}/quality                               ||
|  |   - smartdairy/raw/climate/{zone_id}/temperature                         ||
|  |   - smartdairy/raw/climate/{zone_id}/humidity                            ||
|  |   - smartdairy/raw/health/{cow_id}/vitals                                ||
|  +-------------------------------+-----------------------------------------+|
|                                    |                                         |
|                                    v                                         |
|  +-------------------------------------------------------------------------+|
|  |                   DATA PROCESSING ENGINE                                 ||
|  |                                                                          ||
|  |   +--------------+  +--------------+  +--------------+                 ||
|  |   |   Filter     |  |  Transform   |  |  Aggregate   |                 ||
|  |   |              |  |              |  |              |                 ||
|  |   | - Remove     |  | - Unit       |  | - Time       |                 ||
|  |   |   outliers   |  |   conversion |  |   windows    |                 ||
|  |   | - Validate   |  | - Calculate  |  | - Batch      |                 ||
|  |   |   ranges     |  |   derived    |  |   sizes      |                 ||
|  |   | - Deduplicate|  |   metrics    |  | - Statistics |                 ||
|  |   +--------------+  +--------------+  +--------------+                 ||
|  |                                                                          ||
|  |   +--------------+  +--------------+  +--------------+                 ||
|  |   |    Alert     |  |   Anomaly    |  |    ML        |                 ||
|  |   |   Engine     |  |  Detection   |  |  Inference   |                 ||
|  |   |              |  |              |  |              |                 ||
|  |   | - Threshold  |  | - Statistical|  | - Health     |                 ||
|  |   |   alerts     |  |   methods    |  |   scoring    |                 ||
|  |   | - Condition  |  | - ML models  |  | - Predictive |                 ||
|  |   |   rules      |  |   (optional) |  |   analytics  |                 ||
|  |   +--------------+  +--------------+  +--------------+                 ||
|  +-------------------------------+-----------------------------------------+|
|                                    |                                         |
|                                    v                                         |
|  +-------------------------------------------------------------------------+|
|  |                     DATA OUTPUT LAYER                                    ||
|  |                                                                          ||
|  |   Processed Topics:                                                      ||
|  |   - smartdairy/processed/collar/{cow_id}/activity_score                 ||
|  |   - smartdairy/processed/milk/{stall_id}/daily_yield                    ||
|  |   - smartdairy/processed/climate/{zone_id}/comfort_index                ||
|  |   - smartdairy/alerts/{severity}/{type}                                  ||
|  |   - smartdairy/batch/{timestamp}/aggregated_data                        ||
|  |                                                                          ||
|  |   Storage:                                                               ||
|  |   - Local SQLite (buffer/backup)                                         ||
|  |   - Cloud sync queue                                                     ||
|  +-------------------------------------------------------------------------+|
|                                                                              |
+-----------------------------------------------------------------------------+
```

### 6.2 Python Edge Processing Service

```python
#!/usr/bin/env python3
"""
Smart Dairy Edge Processing Service
Processes IoT sensor data at the gateway before cloud transmission
"""

import json
import time
import sqlite3
import logging
from datetime import datetime, timedelta
from collections import defaultdict, deque
from threading import Lock, Thread
import paho.mqtt.client as mqtt
from dataclasses import dataclass, asdict
from typing import Dict, List, Optional, Callable
import queue

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('/var/log/smartdairy/edge_processor.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger('EdgeProcessor')


@dataclass
class SensorReading:
    """Represents a single sensor reading"""
    topic: str
    value: float
    timestamp: datetime
    device_id: str
    sensor_type: str
    raw_data: dict


@dataclass
class ProcessedData:
    """Represents processed sensor data"""
    device_id: str
    sensor_type: str
    timestamp: datetime
    value: float
    statistics: dict
    alerts: List[dict]
    metadata: dict


class LocalBuffer:
    """SQLite-based local buffer for offline periods"""
    
    def __init__(self, db_path: str = '/var/lib/smartdairy/buffer.db'):
        self.db_path = db_path
        self.lock = Lock()
        self._init_db()
    
    def _init_db(self):
        with sqlite3.connect(self.db_path) as conn:
            conn.execute('''
                CREATE TABLE IF NOT EXISTS sensor_buffer (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    topic TEXT NOT NULL,
                    payload TEXT NOT NULL,
                    timestamp TEXT NOT NULL,
                    processed INTEGER DEFAULT 0,
                    retry_count INTEGER DEFAULT 0
                )
            ''')
            conn.execute('''
                CREATE INDEX IF NOT EXISTS idx_processed ON sensor_buffer(processed)
            ''')
            conn.execute('''
                CREATE INDEX IF NOT EXISTS idx_timestamp ON sensor_buffer(timestamp)
            ''')
            conn.commit()
    
    def store(self, topic: str, payload: dict):
        with self.lock:
            with sqlite3.connect(self.db_path) as conn:
                conn.execute(
                    'INSERT INTO sensor_buffer (topic, payload, timestamp) VALUES (?, ?, ?)',
                    (topic, json.dumps(payload), datetime.now().isoformat())
                )
                conn.commit()
    
    def retrieve_pending(self, limit: int = 100) -> List[dict]:
        with self.lock:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.execute(
                    '''SELECT id, topic, payload, timestamp FROM sensor_buffer 
                       WHERE processed = 0 AND retry_count < 5
                       ORDER BY timestamp LIMIT ?''',
                    (limit,)
                )
                rows = cursor.fetchall()
                return [
                    {'id': row[0], 'topic': row[1], 'payload': json.loads(row[2]), 'timestamp': row[3]}
                    for row in rows
                ]
    
    def mark_processed(self, message_id: int):
        with self.lock:
            with sqlite3.connect(self.db_path) as conn:
                conn.execute('UPDATE sensor_buffer SET processed = 1 WHERE id = ?', (message_id,))
                conn.commit()
    
    def increment_retry(self, message_id: int):
        with self.lock:
            with sqlite3.connect(self.db_path) as conn:
                conn.execute('UPDATE sensor_buffer SET retry_count = retry_count + 1 WHERE id = ?',
                    (message_id,))
                conn.commit()
    
    def cleanup_old(self, days: int = 7):
        cutoff = (datetime.now() - timedelta(days=days)).isoformat()
        with self.lock:
            with sqlite3.connect(self.db_path) as conn:
                conn.execute('DELETE FROM sensor_buffer WHERE processed = 1 AND timestamp < ?', (cutoff,))
                conn.commit()


class DataFilter:
    """Filter and validate sensor data"""
    
    VALID_RANGES = {
        'temperature': (-10.0, 50.0),
        'humidity': (0.0, 100.0),
        'acceleration': (-16.0, 16.0),
        'heart_rate': (30.0, 150.0),
        'milk_volume': (0.0, 50.0),
        'rumination': (0.0, 24.0),
    }
    
    def __init__(self):
        self.last_values: Dict[str, tuple] = {}
        self.dedup_window = timedelta(seconds=5)
    
    def filter_reading(self, reading: SensorReading) -> Optional[SensorReading]:
        if reading.sensor_type in self.VALID_RANGES:
            min_val, max_val = self.VALID_RANGES[reading.sensor_type]
            if not (min_val <= reading.value <= max_val):
                logger.warning(f"Out of range: {reading.value} for {reading.sensor_type}")
                return None
        
        topic = reading.topic
        current_time = reading.timestamp
        if topic in self.last_values:
            last_value, last_time = self.last_values[topic]
            if abs(reading.value - last_value) < 0.001 and (current_time - last_time) < self.dedup_window:
                return None
        
        self.last_values[topic] = (reading.value, current_time)
        return reading


class AlertEngine:
    """Generate alerts based on sensor data"""
    
    ALERT_THRESHOLDS = {
        'collar.temperature': {
            'critical_high': 41.5, 'warning_high': 39.5,
            'warning_low': 37.5, 'critical_low': 35.0,
        },
        'climate.temperature': {
            'critical_high': 32.0, 'warning_high': 28.0, 'warning_low': 10.0,
        },
        'collar.activity': {'critical_low': 100, 'warning_low': 300},
        'milk.conductivity': {'warning_high': 7.0, 'critical_high': 8.5},
    }
    
    def __init__(self):
        self.alert_state: Dict[str, str] = {}
    
    def check_alerts(self, reading: SensorReading) -> List[dict]:
        alerts = []
        key = f"{reading.device_id}.{reading.sensor_type}"
        
        if key not in self.ALERT_THRESHOLDS:
            return alerts
        
        thresholds = self.ALERT_THRESHOLDS[key]
        value = reading.value
        level = None
        
        if 'critical_high' in thresholds and value >= thresholds['critical_high']:
            level = 'critical'
            message = f"Critical high {reading.sensor_type}: {value}"
        elif 'critical_low' in thresholds and value <= thresholds['critical_low']:
            level = 'critical'
            message = f"Critical low {reading.sensor_type}: {value}"
        elif 'warning_high' in thresholds and value >= thresholds['warning_high']:
            level = 'warning'
            message = f"Warning high {reading.sensor_type}: {value}"
        elif 'warning_low' in thresholds and value <= thresholds['warning_low']:
            level = 'warning'
            message = f"Warning low {reading.sensor_type}: {value}"
        
        if level and self.alert_state.get(key) != level:
            self.alert_state[key] = level
            alerts.append({
                'level': level, 'device_id': reading.device_id,
                'sensor_type': reading.sensor_type, 'value': value,
                'message': message, 'timestamp': reading.timestamp.isoformat(),
            })
        elif not level and key in self.alert_state:
            del self.alert_state[key]
            alerts.append({
                'level': 'clear', 'device_id': reading.device_id,
                'sensor_type': reading.sensor_type, 'value': value,
                'message': f"{reading.sensor_type} normal: {value}",
                'timestamp': reading.timestamp.isoformat(),
            })
        
        return alerts


class EdgeProcessor:
    """Main edge processing service"""
    
    def __init__(self, config: dict):
        self.config = config
        self.buffer = LocalBuffer()
        self.filter = DataFilter()
        self.alert_engine = AlertEngine()
        self.local_client = None
        self.cloud_client = None
        self.processing_queue = queue.Queue(maxsize=10000)
        self.stats = {'messages_received': 0, 'messages_filtered': 0, 
                      'messages_processed': 0, 'alerts_generated': 0, 'buffered_messages': 0}
        self.running = False
    
    def connect_local(self):
        self.local_client = mqtt.Client(client_id="edge_processor")
        self.local_client.on_connect = self._on_local_connect
        self.local_client.on_message = self._on_local_message
        self.local_client.on_disconnect = self._on_local_disconnect
        
        if self.config.get('local_auth'):
            self.local_client.username_pw_set(self.config['local_username'], self.config['local_password'])
        
        self.local_client.connect(self.config.get('local_host', 'localhost'), 
                                   self.config.get('local_port', 1883))
        self.local_client.loop_start()
    
    def _on_local_connect(self, client, userdata, flags, rc):
        logger.info(f"Connected to local broker: {rc}")
        client.subscribe("smartdairy/raw/#")
    
    def _on_local_message(self, client, userdata, msg):
        try:
            self.processing_queue.put(msg)
            self.stats['messages_received'] += 1
        except queue.Full:
            logger.error("Queue full, dropping message")
    
    def _on_local_disconnect(self, client, userdata, rc):
        logger.warning(f"Disconnected from local broker: {rc}")
    
    def _parse_message(self, msg) -> Optional[SensorReading]:
        try:
            payload = json.loads(msg.payload.decode())
            topic_parts = msg.topic.split('/')
            device_id = topic_parts[-2] if len(topic_parts) >= 2 else 'unknown'
            sensor_type = topic_parts[-1] if topic_parts else 'unknown'
            
            return SensorReading(
                topic=msg.topic, value=float(payload.get('value', 0)),
                timestamp=datetime.fromisoformat(payload.get('timestamp', datetime.now().isoformat())),
                device_id=device_id, sensor_type=sensor_type, raw_data=payload
            )
        except Exception as e:
            logger.error(f"Parse error: {e}")
            return None
    
    def _process_reading(self, reading: SensorReading):
        filtered = self.filter.filter_reading(reading)
        if filtered is None:
            self.stats['messages_filtered'] += 1
            return
        
        alerts = self.alert_engine.check_alerts(filtered)
        
        processed = {
            'device_id': filtered.device_id, 'sensor_type': filtered.sensor_type,
            'timestamp': filtered.timestamp.isoformat(), 'value': filtered.value,
            'alerts': alerts, 'farm_id': self.config['farm_id'],
            'gateway_id': self.config['gateway_id'],
        }
        
        for alert in alerts:
            alert_topic = f"smartdairy/alerts/{alert['level']}/{alert['sensor_type']}"
            self._publish(alert_topic, alert)
            self.stats['alerts_generated'] += 1
        
        processed_topic = f"smartdairy/processed/{filtered.device_id}/{filtered.sensor_type}"
        self._publish(processed_topic, processed)
        self.stats['messages_processed'] += 1
    
    def _publish(self, topic: str, payload: dict):
        if self.cloud_client and self.cloud_client.is_connected():
            try:
                self.cloud_client.publish(topic, json.dumps(payload), qos=1)
            except Exception as e:
                logger.error(f"Publish error: {e}")
                self.buffer.store(topic, payload)
                self.stats['buffered_messages'] += 1
        else:
            self.buffer.store(topic, payload)
            self.stats['buffered_messages'] += 1
    
    def _sync_worker(self):
        while self.running:
            # Sync buffered messages logic here
            time.sleep(10)
    
    def start(self):
        logger.info("Starting Edge Processor")
        self.running = True
        self.connect_local()
        Thread(target=self._sync_worker, daemon=True).start()
        
        while self.running:
            try:
                msg = self.processing_queue.get(timeout=1)
                reading = self._parse_message(msg)
                if reading:
                    self._process_reading(reading)
            except queue.Empty:
                continue
            except Exception as e:
                logger.error(f"Error: {e}")
    
    def stop(self):
        logger.info("Stopping Edge Processor")
        self.running = False
        if self.local_client:
            self.local_client.loop_stop()


def main():
    config = {
        'farm_id': 'farm01', 'gateway_id': 'gw-001',
        'local_host': 'localhost', 'local_port': 1883,
        'local_auth': True, 'local_username': 'gateway', 'local_password': 'FarmGateway_2024',
    }
    processor = EdgeProcessor(config)
    try:
        processor.start()
    except KeyboardInterrupt:
        processor.stop()


if __name__ == '__main__':
    main()
```

### 6.3 Service Configuration

```ini
# /etc/systemd/system/smartdairy-edge.service
[Unit]
Description=Smart Dairy Edge Processing Service
After=network.target mosquitto.service
Wants=mosquitto.service

[Service]
Type=simple
User=smartdairy
Group=smartdairy
WorkingDirectory=/opt/smartdairy/edge
ExecStart=/opt/smartdairy/edge/venv/bin/python /opt/smartdairy/edge/edge_processor.py
Restart=always
RestartSec=10
StandardOutput=journal
StandardError=journal
SyslogIdentifier=smartdairy-edge
LimitNOFILE=65536
MemoryMax=512M
CPUQuota=50%
NoNewPrivileges=true
ProtectSystem=strict
ProtectHome=true
ReadWritePaths=/var/lib/smartdairy /var/log/smartdairy

[Install]
WantedBy=multi-user.target
```

Enable service:
```bash
sudo useradd -r -s /bin/false smartdairy
sudo mkdir -p /opt/smartdairy/edge /var/lib/smartdairy /var/log/smartdairy
sudo chown -R smartdairy:smartdairy /opt/smartdairy /var/lib/smartdairy /var/log/smartdairy

sudo -u smartdairy python3 -m venv /opt/smartdairy/edge/venv
sudo -u smartdairy /opt/smartdairy/edge/venv/bin/pip install paho-mqtt

sudo systemctl daemon-reload
sudo systemctl enable smartdairy-edge
sudo systemctl start smartdairy-edge
```

---

## 7. Protocol Translation

### 7.1 Supported Protocols

| Protocol | Use Case | Translation Target |
|----------|----------|-------------------|
| **Modbus RTU/TCP** | Industrial sensors, PLCs | MQTT |
| **OPC-UA** | Modern industrial equipment | MQTT |
| **LoRaWAN** | Long-range wireless sensors | MQTT |
| **Zigbee** | Short-range mesh sensors | MQTT |

### 7.2 Modbus to MQTT Bridge

```python
#!/usr/bin/env python3
"""
Modbus to MQTT Bridge for Smart Dairy
"""

import json
import time
import logging
from pymodbus.client import ModbusSerialClient, ModbusTcpClient
import paho.mqtt.client as mqtt
from threading import Thread
from dataclasses import dataclass
from typing import List

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger('ModbusBridge')


@dataclass
class ModbusRegister:
    name: str
    address: int
    register_type: str
    data_type: str
    scale: float = 1.0
    offset: float = 0.0
    unit: str = ''


@dataclass
class ModbusDevice:
    name: str
    device_id: str
    connection_type: str
    connection_params: dict
    slave_id: int
    registers: List[ModbusRegister]
    poll_interval: int = 10


class ModbusToMQTTBridge:
    def __init__(self, mqtt_config: dict, devices: List[ModbusDevice]):
        self.mqtt_config = mqtt_config
        self.devices = devices
        self.mqtt_client = None
        self.modbus_clients = {}
        self.running = True
    
    def connect_mqtt(self):
        self.mqtt_client = mqtt.Client(client_id="modbus_bridge")
        if self.mqtt_config.get('username'):
            self.mqtt_client.username_pw_set(self.mqtt_config['username'], self.mqtt_config['password'])
        self.mqtt_client.connect(self.mqtt_config['host'], self.mqtt_config.get('port', 1883))
        self.mqtt_client.loop_start()
    
    def connect_modbus(self, device: ModbusDevice):
        try:
            if device.connection_type == 'rtu':
                client = ModbusSerialClient(
                    port=device.connection_params['port'],
                    baudrate=device.connection_params.get('baudrate', 9600),
                    bytesize=device.connection_params.get('bytesize', 8),
                    parity=device.connection_params.get('parity', 'N'),
                    stopbits=device.connection_params.get('stopbits', 1),
                    timeout=device.connection_params.get('timeout', 3)
                )
            elif device.connection_type == 'tcp':
                client = ModbusTcpClient(
                    host=device.connection_params['host'],
                    port=device.connection_params.get('port', 502),
                    timeout=device.connection_params.get('timeout', 3)
                )
            else:
                return None
            
            if client.connect():
                logger.info(f"Connected: {device.name}")
                return client
        except Exception as e:
            logger.error(f"Connection error {device.name}: {e}")
        return None
    
    def read_register(self, client, register: ModbusRegister, slave_id: int):
        try:
            count = 2 if register.data_type in ['uint32', 'float32'] else 1
            
            if register.register_type == 'holding':
                result = client.read_holding_registers(register.address, count, slave=slave_id)
            elif register.register_type == 'input':
                result = client.read_input_registers(register.address, count, slave=slave_id)
            elif register.register_type == 'coil':
                result = client.read_coils(register.address, 1, slave=slave_id)
            else:
                return None
            
            if result.isError():
                return None
            
            if register.data_type == 'bool':
                value = result.bits[0]
            elif register.data_type == 'uint16':
                value = result.registers[0]
            elif register.data_type == 'int16':
                value = result.registers[0] if result.registers[0] < 32768 else result.registers[0] - 65536
            elif register.data_type == 'float32':
                import struct
                packed = struct.pack('>HH', result.registers[0], result.registers[1])
                value = struct.unpack('>f', packed)[0]
            else:
                value = result.registers[0]
            
            return value * register.scale + register.offset
        except Exception as e:
            logger.error(f"Read error {register.name}: {e}")
            return None
    
    def poll_device(self, device: ModbusDevice):
        client = self.modbus_clients.get(device.device_id)
        if not client or not client.is_socket_open():
            client = self.connect_modbus(device)
            if client:
                self.modbus_clients[device.device_id] = client
            else:
                return
        
        readings = {}
        timestamp = time.strftime('%Y-%m-%dT%H:%M:%S')
        
        for register in device.registers:
            value = self.read_register(client, register, device.slave_id)
            if value is not None:
                readings[register.name] = {'value': value, 'unit': register.unit, 'timestamp': timestamp}
        
        if readings:
            topic = f"smartdairy/raw/modbus/{device.device_id}"
            payload = {'device_name': device.name, 'device_id': device.device_id, 
                       'timestamp': timestamp, 'readings': readings}
            self.mqtt_client.publish(topic, json.dumps(payload), qos=1)
    
    def device_worker(self, device: ModbusDevice):
        while self.running:
            try:
                self.poll_device(device)
            except Exception as e:
                logger.error(f"Poll error {device.name}: {e}")
            time.sleep(device.poll_interval)
    
    def start(self):
        logger.info("Starting Modbus Bridge")
        self.connect_mqtt()
        for device in self.devices:
            Thread(target=self.device_worker, args=(device,), daemon=True).start()
        while True:
            time.sleep(1)
    
    def stop(self):
        self.running = False
        for client in self.modbus_clients.values():
            client.close()
        if self.mqtt_client:
            self.mqtt_client.loop_stop()


def get_default_devices():
    return [
        ModbusDevice(
            name="Climate Controller Barn A", device_id="climate_barn_a",
            connection_type="tcp", connection_params={'host': '192.168.1.101', 'port': 502},
            slave_id=1, poll_interval=30,
            registers=[
                ModbusRegister('temperature', 0, 'input', 'float32', unit='C'),
                ModbusRegister('humidity', 2, 'input', 'float32', unit='%RH'),
                ModbusRegister('co2_level', 4, 'input', 'uint16', unit='ppm'),
            ]
        ),
        ModbusDevice(
            name="Cooling Tank", device_id="cooling_tank_01",
            connection_type="rtu", connection_params={'port': '/dev/ttyUSB0', 'baudrate': 9600},
            slave_id=2, poll_interval=60,
            registers=[
                ModbusRegister('tank_temperature', 0, 'input', 'float32', unit='C'),
                ModbusRegister('milk_volume', 2, 'input', 'float32', unit='liters'),
            ]
        ),
    ]


def main():
    mqtt_config = {'host': 'localhost', 'port': 1883, 'username': 'gateway', 'password': 'FarmGateway_2024'}
    bridge = ModbusToMQTTBridge(mqtt_config, get_default_devices())
    bridge.start()


if __name__ == '__main__':
    main()
```

---


## 8. Security Configuration

### 8.1 Firewall Configuration (UFW)

```bash
#!/bin/bash
# /usr/local/bin/setup-firewall.sh

sudo ufw --force reset
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allow SSH
sudo ufw allow from 192.168.1.0/24 to any port 22 proto tcp comment 'SSH Admin'

# Allow MQTT TLS
sudo ufw allow from 192.168.1.0/24 to any port 8883 proto tcp comment 'MQTT TLS'

# Allow MQTT WebSocket
sudo ufw allow from 192.168.1.0/24 to any port 9001 proto tcp comment 'MQTT WebSocket'

# Allow local MQTT
sudo ufw allow from 127.0.0.1 to any port 1883 proto tcp comment 'MQTT Local'

# Allow HTTP/HTTPS
sudo ufw allow from 192.168.1.0/24 to any port 80 proto tcp comment 'HTTP Local'
sudo ufw allow from 192.168.1.0/24 to any port 443 proto tcp comment 'HTTPS Local'

# Allow Modbus TCP
sudo ufw allow from 192.168.2.0/24 to any port 502 proto tcp comment 'Modbus Devices'

# Allow OPC-UA
sudo ufw allow from 192.168.2.0/24 to any port 4840 proto tcp comment 'OPC-UA Devices'

sudo ufw allow icmp
sudo ufw --force enable
sudo ufw status verbose
```

### 8.2 Fail2Ban Configuration

```ini
# /etc/fail2ban/jail.local
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5
backend = systemd

[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log
maxretry = 3

[mosquitto-auth]
enabled = true
filter = mosquitto
port = 8883
logpath = /var/log/mosquitto/mosquitto.log
maxretry = 5
```

### 8.3 VPN Configuration (WireGuard)

```bash
#!/bin/bash
# WireGuard VPN setup

sudo apt install -y wireguard

# Generate keys
wg genkey | sudo tee /etc/wireguard/privatekey | wg pubkey | sudo tee /etc/wireguard/publickey

# Gateway client configuration
sudo tee /etc/wireguard/wg0.conf << 'EOF'
[Interface]
PrivateKey = <gateway-private-key>
Address = 10.200.200.2/32

[Peer]
PublicKey = <server-public-key>
Endpoint = vpn.smartdairy.com.bd:51820
AllowedIPs = 10.200.200.0/24
PersistentKeepalive = 25
EOF

sudo systemctl enable wg-quick@wg0
sudo systemctl start wg-quick@wg0
```

### 8.4 Certificate Management

```bash
#!/bin/bash
# /usr/local/bin/cert-manager.sh

CERT_DIR="/etc/smartdairy/certs"
CA_CERT="$CERT_DIR/ca.crt"

init_ca() {
    mkdir -p $CERT_DIR
    if [ ! -f "$CA_CERT" ]; then
        openssl genrsa -out $CERT_DIR/ca.key 4096
        openssl req -new -x509 -days 3650 -key $CERT_DIR/ca.key -out $CA_CERT \
            -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT/CN=SmartDairy Root CA"
        chmod 600 $CERT_DIR/ca.key
    fi
}

generate_device_cert() {
    DEVICE_ID=$1
    DEVICE_CERT="$CERT_DIR/$DEVICE_ID.crt"
    DEVICE_KEY="$CERT_DIR/$DEVICE_ID.key"
    
    openssl genrsa -out $DEVICE_KEY 2048
    openssl req -new -key $DEVICE_KEY -out $CERT_DIR/$DEVICE_ID.csr \
        -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/OU=IoT/CN=$DEVICE_ID"
    openssl x509 -req -in $CERT_DIR/$DEVICE_ID.csr -CA $CA_CERT -CAkey $CERT_DIR/ca.key \
        -CAcreateserial -out $DEVICE_CERT -days 365
    
    chmod 600 $DEVICE_KEY
    chmod 644 $DEVICE_CERT
    rm $CERT_DIR/$DEVICE_ID.csr
    echo "Certificate generated for $DEVICE_ID"
}

case "$1" in
    init) init_ca ;;
    generate) generate_device_cert $2 ;;
    *) echo "Usage: $0 {init|generate <device_id>}" ;;
esac
```

---

## 9. Device Management

### 9.1 OTA Update System

```bash
#!/bin/bash
# /usr/local/bin/ota-update.sh

UPDATE_SERVER="https://updates.smartdairy.com.bd"
DEVICE_ID=$(hostname)
CURRENT_VERSION=$(cat /opt/smartdairy/version 2>/dev/null || echo "0.0.0")
UPDATE_DIR="/tmp/smartdairy-update"
ROLLBACK_DIR="/opt/smartdairy-backup"

log() { echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a /var/log/smartdairy/ota.log; }

check_update() {
    LATEST=$(curl -s "$UPDATE_SERVER/latest?device=$DEVICE_ID&current=$CURRENT_VERSION")
    [ -z "$LATEST" ] || [ "$LATEST" = "$CURRENT_VERSION" ] && return 1
    log "Update available: $CURRENT_VERSION -> $LATEST"
    return 0
}

download_update() {
    VERSION=$1
    log "Downloading update $VERSION..."
    rm -rf $UPDATE_DIR
    mkdir -p $UPDATE_DIR
    curl -fsSL "$UPDATE_SERVER/download/$VERSION" -o $UPDATE_DIR/update.tar.gz || return 1
    tar -xzf $UPDATE_DIR/update.tar.gz -C $UPDATE_DIR
    cd $UPDATE_DIR && sha256sum -c checksums.txt || return 1
    log "Update verified"
}

backup_current() {
    log "Creating backup..."
    rm -rf $ROLLBACK_DIR
    mkdir -p $ROLLBACK_DIR
    cp -r /opt/smartdairy/* $ROLLBACK_DIR/ 2>/dev/null
    sqlite3 /var/lib/smartdairy/buffer.db ".backup '$ROLLBACK_DIR/buffer.db.backup'" 2>/dev/null
    echo $CURRENT_VERSION > $ROLLBACK_DIR/version
}

apply_update() {
    log "Applying update..."
    systemctl stop smartdairy-edge smartdairy-modbus mosquitto
    cp -r $UPDATE_DIR/smartdairy/* /opt/smartdairy/
    [ -f $UPDATE_DIR/migrate.sh ] && bash $UPDATE_DIR/migrate.sh
    echo $1 > /opt/smartdairy/version
    systemctl start mosquitto smartdairy-edge smartdairy-modbus
    sleep 10
    if systemctl is-active --quiet smartdairy-edge; then
        log "Update successful"
        return 0
    fi
    return 1
}

rollback() {
    log "Rolling back..."
    systemctl stop smartdairy-edge smartdairy-modbus
    cp -r $ROLLBACK_DIR/* /opt/smartdairy/
    cp $ROLLBACK_DIR/buffer.db.backup /var/lib/smartdairy/buffer.db 2>/dev/null
    systemctl start mosquitto smartdairy-edge smartdairy-modbus
}

if check_update; then
    LATEST=$(curl -s "$UPDATE_SERVER/latest?device=$DEVICE_ID&current=$CURRENT_VERSION")
    download_update $LATEST && backup_current && apply_update $LATEST || rollback
fi
rm -rf $UPDATE_DIR
```

---

## 10. Data Buffering

### 10.1 Local Buffer Strategy

```
+-----------------------------------------------------------------------------+
|                          DATA BUFFERING STRATEGY                             |
+-----------------------------------------------------------------------------+
|                                                                              |
|  +-------------------------------------------------------------------------+|
|  |                        DATA FLOW                                         ||
|  |                                                                          ||
|  |   Sensors -> Edge Processor -> [Memory Buffer] -> MQTT Publish          ||
|  |                      |              |              |                    ||
|  |                      v              v              v                    ||
|  |                [SQLite Buffer]  [Batch Queue]  [Cloud Status]           ||
|  |                      |              |              |                    ||
|  |                      +--------------+--------------+                    ||
|  |                                     |                                  ||
|  |                              Cloud Connected?                          ||
|  |                            +--------+--------+                         ||
|  |                            |                 |                         ||
|  |                           YES               NO                         ||
|  |                            |                 |                         ||
|  |                            v                 v                         ||
|  |                    +--------------+   +--------------+                ||
|  |                    | Upload to    |   | Store in     |                ||
|  |                    | Cloud        |   | SQLite       |                ||
|  |                    |              |   | (persistent) |                ||
|  |                    +--------------+   +--------------+                ||
|  |                            |                 |                         ||
|  |                            v                 v                         ||
|  |                    +--------------+   +--------------+                ||
|  |                    | Clear buffer |   | Retry every  |                ||
|  |                    | Mark synced  |   | 30 seconds   |                ||
|  |                    +--------------+   +--------------+                ||
|  +-------------------------------------------------------------------------+|
|                                                                              |
|  Buffer Sizes:                                                               |
|  - Memory Buffer: 10,000 messages (5 min retention)                         |
|  - SQLite Buffer: 100,000 messages (7 days retention)                       |
|  - Batch Size: 100 messages per upload                                      |
|                                                                              |
+-----------------------------------------------------------------------------+
```

### 10.2 Sync Strategy Configuration

```python
#!/usr/bin/env python3
"""
Cloud Synchronization Manager
"""

import json
import time
import sqlite3
import logging
from datetime import datetime, timedelta
from typing import List, Dict
import requests

logger = logging.getLogger('SyncManager')


class CloudSyncManager:
    def __init__(self, config: dict):
        self.config = config
        self.db_path = config.get('buffer_db', '/var/lib/smartdairy/sync_buffer.db')
        self.cloud_endpoint = config['cloud_endpoint']
        self.api_key = config['api_key']
        self.batch_size = config.get('batch_size', 100)
        self.sync_interval = config.get('sync_interval', 30)
        self.max_retries = config.get('max_retries', 5)
        self._init_db()
    
    def _init_db(self):
        with sqlite3.connect(self.db_path) as conn:
            conn.execute('''
                CREATE TABLE IF NOT EXISTS sync_queue (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    topic TEXT NOT NULL,
                    payload TEXT NOT NULL,
                    timestamp TEXT NOT NULL,
                    priority INTEGER DEFAULT 5,
                    retry_count INTEGER DEFAULT 0,
                    last_retry TEXT,
                    synced INTEGER DEFAULT 0
                )
            ''')
            conn.execute('CREATE INDEX IF NOT EXISTS idx_sync ON sync_queue(synced, priority, timestamp)')
            conn.execute('''
                CREATE TABLE IF NOT EXISTS sync_stats (
                    date TEXT PRIMARY KEY, messages_sent INTEGER DEFAULT 0,
                    messages_failed INTEGER DEFAULT 0, last_sync TEXT
                )
            ''')
            conn.commit()
    
    def queue_message(self, topic: str, payload: dict, priority: int = 5):
        try:
            with sqlite3.connect(self.db_path) as conn:
                conn.execute('INSERT INTO sync_queue (topic, payload, timestamp, priority) VALUES (?, ?, ?, ?)',
                    (topic, json.dumps(payload), datetime.now().isoformat(), priority))
                conn.commit()
        except Exception as e:
            logger.error(f"Queue error: {e}")
    
    def _get_pending(self) -> List[dict]:
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.execute('''
                SELECT id, topic, payload, timestamp, retry_count, priority FROM sync_queue
                WHERE synced = 0 AND retry_count < ? ORDER BY priority, timestamp LIMIT ?
            ''', (self.max_retries, self.batch_size))
            cols = [c[0] for c in cursor.description]
            return [dict(zip(cols, row)) for row in cursor.fetchall()]
    
    def _upload(self, messages: List[dict]) -> bool:
        batch = [{'topic': m['topic'], 'payload': json.loads(m['payload']), 
                  'timestamp': m['timestamp'], 'priority': m['priority']} for m in messages]
        try:
            response = requests.post(f"{self.cloud_endpoint}/batch",
                headers={'Authorization': f'Bearer {self.api_key}'},
                json={'messages': batch}, timeout=30)
            return response.status_code == 200 and response.json().get('success', False)
        except Exception as e:
            logger.warning(f"Upload error: {e}")
            return False
    
    def _mark_synced(self, ids: List[int]):
        with sqlite3.connect(self.db_path) as conn:
            conn.execute(f"UPDATE sync_queue SET synced = 1 WHERE id IN ({','.join('?'*len(ids))})", ids)
            conn.commit()
    
    def _increment_retry(self, ids: List[int]):
        with sqlite3.connect(self.db_path) as conn:
            conn.execute(f"UPDATE sync_queue SET retry_count = retry_count + 1, last_retry = ? WHERE id IN ({','.join('?'*len(ids))})",
                [datetime.now().isoformat()] + ids)
            conn.commit()
    
    def run(self):
        while True:
            messages = self._get_pending()
            if messages:
                if self._upload(messages):
                    self._mark_synced([m['id'] for m in messages])
                    logger.info(f"Synced {len(messages)} messages")
                else:
                    self._increment_retry([m['id'] for m in messages])
            time.sleep(self.sync_interval)


if __name__ == '__main__':
    config = {
        'cloud_endpoint': 'https://api.smartdairy.com.bd/v1',
        'api_key': 'your-api-key',
        'buffer_db': '/var/lib/smartdairy/sync_buffer.db',
        'batch_size': 100, 'sync_interval': 30, 'max_retries': 5
    }
    CloudSyncManager(config).run()
```

---

## 11. Integration with Cloud

### 11.1 AWS IoT Core Integration

```python
#!/usr/bin/env python3
"""
AWS IoT Core Integration
"""

import json
import boto3
import logging

logger = logging.getLogger('AWSIoT')


class AWSIoTIntegration:
    def __init__(self, region: str = 'ap-south-1'):
        self.region = region
        self.iot_client = boto3.client('iot', region_name=region)
    
    def create_thing(self, thing_name: str, thing_type: str = 'SmartDairyGateway'):
        try:
            try:
                self.iot_client.create_thing_type(thingTypeName=thing_type,
                    thingTypeDescription='Smart Dairy IoT Gateway')
            except self.iot_client.exceptions.ResourceAlreadyExistsException:
                pass
            return self.iot_client.create_thing(thingName=thing_name, thingTypeName=thing_type,
                attributePayload={'attributes': {'farm_id': 'farm01', 'location': 'Bangladesh'}})
        except Exception as e:
            logger.error(f"Create thing error: {e}")
            raise
    
    def create_certificate(self, thing_name: str):
        response = self.iot_client.create_keys_and_certificate(setAsActive=True)
        cert_arn = response['certificateArn']
        cert_dir = f"/etc/smartdairy/aws/{thing_name}"
        import os
        os.makedirs(cert_dir, exist_ok=True)
        with open(f"{cert_dir}/certificate.pem.crt", 'w') as f: f.write(response['certificatePem'])
        with open(f"{cert_dir}/private.pem.key", 'w') as f: f.write(response['keyPair']['PrivateKey'])
        self.iot_client.attach_thing_principal(thingName=thing_name, principal=cert_arn)
        return {'cert_arn': cert_arn, 'cert_dir': cert_dir}
```

### 11.2 Mosquitto Bridge Config for AWS IoT

```
# AWS IoT Core Bridge Configuration
connection aws-iot-bridge
address a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com:8883
bridge_protocol_version mqttv311
bridge_cafile /etc/mosquitto/certs/AmazonRootCA1.pem
bridge_certfile /etc/smartdairy/aws/gw-001/certificate.pem.crt
bridge_keyfile /etc/smartdairy/aws/gw-001/private.pem.key
tls_version tlsv1.2
try_private false
cleansession true
keepalive_interval 60
notification_topic smartdairy/bridge/status
notifications true
topic smartdairy/farm01/# out 1
topic smartdairy/cmd/farm01/+ in 1
remote_clientid gw-001
```

---

## 12. Monitoring

### 12.1 Gateway Health Monitoring Script

```python
#!/usr/bin/env python3
"""
Gateway Health Monitor
"""

import json
import time
import psutil
import logging
from datetime import datetime
import paho.mqtt.client as mqtt

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger('HealthMonitor')


class GatewayHealthMonitor:
    def __init__(self, mqtt_config: dict, farm_id: str, gateway_id: str):
        self.mqtt_config = mqtt_config
        self.farm_id = farm_id
        self.gateway_id = gateway_id
        self.mqtt_client = None
    
    def connect(self):
        self.mqtt_client = mqtt.Client(client_id=f"health_{self.gateway_id}")
        if self.mqtt_config.get('username'):
            self.mqtt_client.username_pw_set(self.mqtt_config['username'], self.mqtt_config['password'])
        self.mqtt_client.connect(self.mqtt_config['host'], self.mqtt_config.get('port', 1883))
        self.mqtt_client.loop_start()
    
    def get_metrics(self):
        cpu = psutil.cpu_percent(interval=1)
        mem = psutil.virtual_memory()
        disk = psutil.disk_usage('/')
        net = psutil.net_io_counters()
        
        return {
            'timestamp': datetime.now().isoformat(),
            'gateway_id': self.gateway_id,
            'farm_id': self.farm_id,
            'cpu': {'percent': cpu},
            'memory': {'percent': mem.percent, 'available_gb': round(mem.available/(1024**3), 2)},
            'disk': {'percent': disk.percent, 'free_gb': round(disk.free/(1024**3), 2)},
            'network': {'bytes_sent': net.bytes_sent, 'bytes_recv': net.bytes_recv},
        }
    
    def publish(self):
        metrics = self.get_metrics()
        topic = f"smartdairy/monitoring/gateway/{self.gateway_id}/health"
        self.mqtt_client.publish(topic, json.dumps(metrics), qos=1)
    
    def run(self, interval: int = 60):
        self.connect()
        while True:
            self.publish()
            time.sleep(interval)


if __name__ == '__main__':
    config = {'host': 'localhost', 'port': 1883, 'username': 'gateway', 'password': 'FarmGateway_2024'}
    GatewayHealthMonitor(config, 'farm01', 'gw-001').run()
```

---


## 13. Troubleshooting

### 13.1 Common Issues and Solutions

#### Issue 1: Gateway cannot connect to cloud MQTT

**Symptoms:**
- Local MQTT working fine
- Bridge status shows "disconnected"
- Messages accumulating in local buffer

**Diagnosis Steps:**
```bash
# Check network connectivity
ping -c 4 a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com

# Check TLS certificate validity
openssl x509 -in /etc/mosquitto/certs/aws-device.crt -text -noout | grep -A2 "Validity"

# Test MQTT connection manually
mosquitto_pub -h a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com -p 8883 \
    --cafile /etc/mosquitto/certs/aws-root-ca.crt \
    --cert /etc/mosquitto/certs/aws-device.crt \
    --key /etc/mosquitto/certs/aws-device.key \
    -t "test" -m "hello" -d

# Check Mosquitto logs
sudo journalctl -u mosquitto -n 50 --no-pager
```

**Solutions:**
1. Verify internet connectivity: `ping 8.8.8.8`
2. Check DNS resolution: `nslookup a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com`
3. Renew certificates if expired
4. Check firewall rules: `sudo ufw status`
5. Verify AWS IoT policy allows the device

#### Issue 2: High CPU/Memory Usage

**Symptoms:**
- Gateway responding slowly
- Services being killed by OOM
- Temperature warnings

**Diagnosis:**
```bash
# Check resource usage
htop

# Check for memory leaks in Python
sudo pmap $(pgrep -f edge_processor) | tail -1

# Check disk space
df -h

# Check for zombie processes
ps aux | grep Z
```

**Solutions:**
1. Reduce log verbosity in Mosquitto config
2. Increase batch size to reduce processing frequency
3. Clean up old log files: `sudo journalctl --vacuum-time=7d`
4. Add swap space for Raspberry Pi:
```bash
sudo dphys-swapfile swapoff
sudo sed -i 's/CONF_SWAPSIZE=.*/CONF_SWAPSIZE=1024/' /etc/dphys-swapfile
sudo dphys-swapfile setup
sudo dphys-swapfile swapon
```

#### Issue 3: Modbus Connection Failures

**Symptoms:**
- No data from Modbus devices
- Timeout errors in logs

**Diagnosis:**
```bash
# Check serial port permissions
ls -la /dev/ttyUSB*
groups $(whoami)

# Test Modbus connection
python3 -c "from pymodbus.client import ModbusSerialClient; \
    c = ModbusSerialClient(port='/dev/ttyUSB0', baudrate=9600); \
    print(c.connect())"

# Check for port conflicts
sudo lsof /dev/ttyUSB0
```

**Solutions:**
1. Add user to dialout group: `sudo usermod -a -G dialout smartdairy`
2. Check cable connections and termination resistors
3. Verify baudrate settings match
4. Check for electrical interference (common in dairy environments)

### 13.2 Log Locations

| Component | Log Location | Rotation |
|-----------|--------------|----------|
| Mosquitto | /var/log/mosquitto/mosquitto.log | Daily |
| Edge Processor | /var/log/smartdairy/edge_processor.log | 10MB |
| Modbus Bridge | /var/log/smartdairy/modbus_bridge.log | 10MB |
| System | /var/log/syslog | Daily |
| Auth | /var/log/auth.log | Weekly |

### 13.3 Diagnostic Commands Quick Reference

```bash
# Check service status
sudo systemctl status smartdairy-edge smartdairy-modbus mosquitto

# Restart all services
sudo systemctl restart mosquitto smartdairy-edge smartdairy-modbus

# Check MQTT connections
sudo mosquitto_sub -t '$SYS/#' -v -W 5

# Test local MQTT
mosquitto_pub -t 'test' -m 'hello' && mosquitto_sub -t 'test' -C 1

# Check network interfaces
ip addr show
ip route show

# Check firewall
sudo ufw status verbose
sudo iptables -L -v -n

# Check certificate expiration
openssl x509 -in /etc/mosquitto/certs/server.crt -noout -dates

# Generate diagnostic report
sudo /usr/local/bin/remote-diagnostics.sh
```

---

## 14. Disaster Recovery

### 14.1 Backup Strategy

| Data | Backup Frequency | Retention | Location |
|------|-----------------|-----------|----------|
| Configuration | Daily | 30 days | Cloud + Local |
| SQLite Buffer | Hourly | 7 days | Local + Cloud sync |
| TLS Certificates | On change | Permanent | Cloud vault + USB |
| Docker Images | Weekly | 4 versions | Cloud registry |

### 14.2 Backup Script

```bash
#!/bin/bash
# /usr/local/bin/backup-gateway.sh

BACKUP_DIR="/var/backups/smartdairy"
S3_BUCKET="s3://smartdairy-backups"
DATE=$(date +%Y%m%d-%H%M%S)
mkdir -p $BACKUP_DIR

# Create backup archive
tar -czf $BACKUP_DIR/gateway-backup-$DATE.tar.gz \
    /etc/mosquitto \
    /etc/smartdairy \
    /opt/smartdairy \
    /var/lib/smartdairy \
    /etc/systemd/system/smartdairy*.service

# Upload to S3
aws s3 cp $BACKUP_DIR/gateway-backup-$DATE.tar.gz $S3_BUCKET/gateways/$(hostname)/

# Clean old local backups
find $BACKUP_DIR -name "gateway-backup-*.tar.gz" -mtime +7 -delete

# Clean old S3 backups (keep 30 days)
aws s3 ls $S3_BUCKET/gateways/$(hostname)/ | awk '{print $4}' | \
    head -n -30 | xargs -I {} aws s3 rm $S3_BUCKET/gateways/$(hostname)/{}

echo "Backup completed: gateway-backup-$DATE.tar.gz"
```

### 14.3 Gateway Replacement Procedure

1. **Prepare New Hardware**
   - Flash SD card with Ubuntu Core image
   - Configure network (same IP as old gateway)
   - Install base packages

2. **Restore Configuration**
   ```bash
   # Download latest backup
   aws s3 cp s3://smartdairy-backups/gateways/$(hostname)/gateway-backup-latest.tar.gz /tmp/
   
   # Extract configuration
   sudo tar -xzf /tmp/gateway-backup-latest.tar.gz -C /
   
   # Restore permissions
   sudo chown -R mosquitto:mosquitto /etc/mosquitto
   sudo chown -R smartdairy:smartdairy /opt/smartdairy /var/lib/smartdairy
   ```

3. **Start Services**
   ```bash
   sudo systemctl daemon-reload
   sudo systemctl restart mosquitto smartdairy-edge smartdairy-modbus
   ```

4. **Verify Operation**
   - Check MQTT connections
   - Verify sensor data flow
   - Test cloud connectivity

5. **Update DNS/Inventory**
   - Update any DNS records if IP changed
   - Update gateway inventory in cloud dashboard

---

## 15. Appendices

### Appendix A: Docker Compose Configuration

```yaml
# /opt/smartdairy/docker-compose.yml
version: '3.8'

services:
  mosquitto:
    image: eclipse-mosquitto:2.0
    container_name: smartdairy-mosquitto
    restart: unless-stopped
    ports:
      - "1883:1883"
      - "8883:8883"
      - "9001:9001"
    volumes:
      - ./mosquitto/config:/mosquitto/config
      - ./mosquitto/data:/mosquitto/data
      - ./mosquitto/log:/mosquitto/log
      - ./certs:/mosquitto/certs
    networks:
      - smartdairy-net

  edge-processor:
    build:
      context: ./edge-processor
      dockerfile: Dockerfile
    container_name: smartdairy-edge
    restart: unless-stopped
    depends_on:
      - mosquitto
    volumes:
      - edge-data:/data
      - ./config:/config:ro
    environment:
      - MQTT_HOST=mosquitto
      - FARM_ID=${FARM_ID}
      - GATEWAY_ID=${GATEWAY_ID}
    networks:
      - smartdairy-net

  modbus-bridge:
    build:
      context: ./modbus-bridge
      dockerfile: Dockerfile
    container_name: smartdairy-modbus
    restart: unless-stopped
    depends_on:
      - mosquitto
    devices:
      - /dev/ttyUSB0:/dev/ttyUSB0
    volumes:
      - ./config/modbus.yaml:/config/modbus.yaml:ro
    environment:
      - MQTT_HOST=mosquitto
    networks:
      - smartdairy-net

  health-monitor:
    build:
      context: ./health-monitor
      dockerfile: Dockerfile
    container_name: smartdairy-health
    restart: unless-stopped
    depends_on:
      - mosquitto
    privileged: true
    volumes:
      - /proc:/host/proc:ro
      - /sys:/host/sys:ro
    environment:
      - MQTT_HOST=mosquitto
      - FARM_ID=${FARM_ID}
      - GATEWAY_ID=${GATEWAY_ID}
    networks:
      - smartdairy-net

  watchtower:
    image: containrrr/watchtower
    container_name: watchtower
    restart: unless-stopped
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    environment:
      - WATCHTOWER_CLEANUP=true
      - WATCHTOWER_POLL_INTERVAL=3600
      - WATCHTOWER_LABEL_ENABLE=true

volumes:
  edge-data:
    driver: local

networks:
  smartdairy-net:
    driver: bridge
```

### Appendix B: Edge Processor Dockerfile

```dockerfile
# /opt/smartdairy/edge-processor/Dockerfile
FROM python:3.11-slim

WORKDIR /app

# Install dependencies
RUN pip install --no-cache-dir paho-mqtt numpy

# Copy application
COPY edge_processor.py /app/
COPY entrypoint.sh /app/

# Create non-root user
RUN useradd -m -u 1000 smartdairy && \
    mkdir -p /data && \
    chown -R smartdairy:smartdairy /app /data

USER smartdairy

VOLUME ["/data"]

ENTRYPOINT ["./entrypoint.sh"]
CMD ["python", "edge_processor.py"]
```

### Appendix C: Environment Configuration Template

```bash
# /opt/smartdairy/.env
# Copy to .env and customize for each gateway

# Gateway Identity
FARM_ID=farm01
GATEWAY_ID=gw-001
LOCATION="Mymensingh, Bangladesh"

# MQTT Configuration
MQTT_HOST=localhost
MQTT_PORT=1883
MQTT_USERNAME=gateway
MQTT_PASSWORD=change_me_in_production

# Cloud Configuration
CLOUD_PROVIDER=aws
AWS_IOT_ENDPOINT=a1b2c3d4e5f6g7-ats.iot.ap-south-1.amazonaws.com
AWS_REGION=ap-south-1

# Buffer Configuration
BUFFER_DB_PATH=/data/buffer.db
BATCH_SIZE=100
SYNC_INTERVAL=30

# Network Configuration
PRIMARY_IFACE=eth0
BACKUP_IFACE=wwan0
FAILOVER_ENABLED=true

# Logging
LOG_LEVEL=INFO
LOG_RETENTION_DAYS=7
```

### Appendix D: Pre-Deployment Checklist

- [ ] Hardware received and inspected
- [ ] SD card flashed with correct OS image
- [ ] Hostname configured (sd-gateway-{farm_id})
- [ ] Network interfaces configured (eth0, wwan0)
- [ ] 4G modem tested with local SIM
- [ ] UPS connected and tested
- [ ] Mosquitto installed and TLS certificates generated
- [ ] MQTT bridge configured for cloud
- [ ] Edge processor deployed and running
- [ ] Modbus/OPC-UA devices connected and tested
- [ ] Firewall rules applied
- [ ] Fail2ban configured
- [ ] VPN client configured (if required)
- [ ] Monitoring agent installed
- [ ] Backup script configured
- [ ] OTA update configured
- [ ] Gateway registered in cloud dashboard
- [ ] Initial data flow verified
- [ ] Alerts tested
- [ ] Documentation updated with gateway details
- [ ] On-site staff trained on basic troubleshooting

### Appendix E: Bangladesh-Specific Configurations

#### E.1 Power Backup Setup

```bash
# APC UPS Configuration
sudo apt install -y apcupsd

# /etc/apcupsd/apcupsd.conf
cat << 'EOF' | sudo tee /etc/apcupsd/apcupsd.conf
UPSNAME SmartDairy_UPS
UPSCABLE usb
UPSTYPE usb
DEVICE
LOCKFILE /var/lock
ONBATTERYDELAY 6
BATTERYLEVEL 30
MINUTES 10
TIMEOUT 0
ANNOY 300
ANNOYDELAY 60
NOLOGON disable
KILLDELAY 0
NETSERVER on
NISIP 127.0.0.1
NISPORT 3551
EVENTSFILE /var/log/apcupsd.events
EVENTSFILEMAX 10
EOF

sudo systemctl restart apcupsd
```

#### E.2 Solar Backup (Optional)

```bash
# For remote farms without reliable grid power
# Using MPPT charge controller with USB/RS485 monitoring

# Install solar monitoring script
sudo tee /usr/local/bin/solar-monitor.sh << 'EOF'
#!/bin/bash
# Monitor solar charge controller via Modbus
python3 /opt/smartdairy/scripts/solar_monitor.py
EOF
```

#### E.3 Temperature Management

```bash
# Install temperature monitoring
sudo apt install -y lm-sensors
sudo sensors-detect --auto

# Fan control script for Raspberry Pi
sudo tee /usr/local/bin/fan-control.py << 'EOF'
#!/usr/bin/env python3
import RPi.GPIO as GPIO
import time
import os

FAN_PIN = 18
TEMP_ON = 60  # Celsius
TEMP_OFF = 50

GPIO.setmode(GPIO.BCM)
GPIO.setup(FAN_PIN, GPIO.OUT)

def get_temp():
    with open('/sys/class/thermal/thermal_zone0/temp') as f:
        return int(f.read()) / 1000

try:
    fan_on = False
    while True:
        temp = get_temp()
        if temp > TEMP_ON and not fan_on:
            GPIO.output(FAN_PIN, GPIO.HIGH)
            fan_on = True
        elif temp < TEMP_OFF and fan_on:
            GPIO.output(FAN_PIN, GPIO.LOW)
            fan_on = False
        time.sleep(5)
except KeyboardInterrupt:
    GPIO.cleanup()
EOF
```

#### E.4 Voltage Protection

```bash
# Monitor for voltage fluctuations
# Using INA219 or similar over I2C

sudo tee /usr/local/bin/voltage-monitor.py << 'EOF'
#!/usr/bin/env python3
import board
import busio
import adafruit_ina219
import json
from datetime import datetime

i2c = busio.I2C(board.SCL, board.SDA)
ina219 = adafruit_ina219.INA219(i2c)

reading = {
    'timestamp': datetime.now().isoformat(),
    'bus_voltage': ina219.bus_voltage + ina219.shunt_voltage,
    'current': ina219.current,
    'power': ina219.power
}

print(json.dumps(reading))
EOF
```

### Appendix F: Emergency Contacts

| Role | Contact | Phone | Email |
|------|---------|-------|-------|
| IoT Architect | [Name] | +880-1XXX-XXXXXX | architect@smartdairy.com.bd |
| DevOps Lead | [Name] | +880-1XXX-XXXXXX | devops@smartdairy.com.bd |
| Field Engineer | [Name] | +880-1XXX-XXXXXX | field@smartdairy.com.bd |
| ISP Support | Grameenphone | 121 | - |
| Hardware Vendor | [Vendor] | +880-1XXX-XXXXXX | - |

---

## Document Control

### Approval Signatures

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Prepared By | IoT Engineer | _____________ | ___________ |
| Reviewed By | DevOps Lead | _____________ | ___________ |
| Approved By | IoT Architect | _____________ | ___________ |
| Authorized By | Project Manager | _____________ | ___________ |

### Distribution List

| Copy No. | Recipient | Date Distributed |
|----------|-----------|------------------|
| 1 | IoT Architect | ___________ |
| 2 | DevOps Lead | ___________ |
| 3 | Field Engineering Team | ___________ |
| 4 | Project Documentation | ___________ |

---

*End of Document I-008: IoT Gateway Configuration*

*Smart Dairy Ltd. - All Rights Reserved*
