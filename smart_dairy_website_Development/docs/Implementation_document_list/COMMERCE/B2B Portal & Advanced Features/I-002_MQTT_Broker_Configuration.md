# SMART DAIRY LTD.
## MQTT BROKER CONFIGURATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-002 |
| **Version** | 1.0 |
| **Date** | July 28, 2026 |
| **Author** | IoT Engineer |
| **Owner** | IoT Engineer |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [MQTT Overview](#2-mqtt-overview)
3. [Broker Selection](#3-broker-selection)
4. [EMQX Installation](#4-emqx-installation)
5. [Configuration](#5-configuration)
6. [Security Setup](#6-security-setup)
7. [Clustering & High Availability](#7-clustering--high-availability)
8. [Monitoring & Maintenance](#8-monitoring--maintenance)
9. [Performance Tuning](#9-performance-tuning)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive configuration guidelines for deploying and managing the MQTT broker infrastructure for Smart Dairy's IoT ecosystem. The MQTT broker serves as the central message hub connecting farm sensors, milk meters, mobile applications, and the ERP system.

### 1.2 Scope

| Component | Description |
|-----------|-------------|
| **MQTT Broker** | EMQX Enterprise/Opensource deployment |
| **Protocols** | MQTT 3.1.1, MQTT 5.0, MQTT over WebSocket |
| **Security** | TLS/SSL, Authentication, Authorization |
| **Integration** | PostgreSQL auth, Webhook to ERP, Kafka bridge |
| **Monitoring** | Prometheus metrics, Grafana dashboards |

### 1.3 Scale Requirements

| Metric | Phase 1 (Year 1) | Phase 2 (Year 2) | Phase 3 (Year 3) |
|--------|------------------|------------------|------------------|
| Connected Devices | 200 | 500 | 1000 |
| Messages/Second | 500 | 1500 | 3000 |
| Concurrent Connections | 300 | 800 | 1500 |
| Data Retention | 7 days | 30 days | 90 days |

---

## 2. MQTT OVERVIEW

### 2.1 MQTT Protocol Basics

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         MQTT COMMUNICATION MODEL                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PUBLISH/SUBSCRIBE PATTERN                                                   │
│                                                                              │
│                            ┌─────────────┐                                   │
│                            │ MQTT BROKER │                                   │
│                            │    (EMQX)   │                                   │
│                            └──────┬──────┘                                   │
│                                   │                                          │
│            PUBLISH                │                 SUBSCRIBE                │
│   ┌───────────┐                   │                   ┌───────────┐          │
│   │  Milk     │───────────────────┼──────────────────▶│  Farm     │          │
│   │  Meter    │  farm/barn_a/milk │                   │  Dashboard│          │
│   │  (Device) │  /volume          │                   │  (App)    │          │
│   └───────────┘                   │                   └───────────┘          │
│                                   │                                          │
│   ┌───────────┐                   │                   ┌───────────┐          │
│   │  RFID     │───────────────────┼──────────────────▶│  Mobile   │          │
│   │  Reader   │  farm/gate/rfid   │                   │  App      │          │
│   │  (Device) │                   │                   │  (Client) │          │
│   └───────────┘                   │                   └───────────┘          │
│                                   │                                          │
│            TOPIC: smartdairy/farm/{location}/{device_type}/{device_id}/data │
│                                                                              │
│  QoS LEVELS:                                                                 │
│  • QoS 0: At most once (fire and forget)                                     │
│  • QoS 1: At least once (acknowledged delivery)                              │
│  • QoS 2: Exactly once (guaranteed delivery)                                 │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Topic Structure

```
smartdairy/
├── farm/
│   ├── {barn_id}/
│   │   ├── milking_parlor/
│   │   │   ├── milk_meter/{device_id}/
│   │   │   │   ├── data          # Milk volume, conductivity
│   │   │   │   ├── status        # Device health
│   │   │   │   └── config        # Configuration commands
│   │   │   ├── quality_analyzer/{device_id}/
│   │   │   │   └── data          # Fat, protein, SNF
│   │   │   └── rfid_reader/{device_id}/
│   │   │       └── scan          # Animal identification
│   │   ├── environmental/
│   │   │   ├── temperature/{sensor_id}
│   │   │   ├── humidity/{sensor_id}
│   │   │   ├── ammonia/{sensor_id}
│   │   │   └── co2/{sensor_id}
│   │   └── feeding_station/{station_id}/
│   │       ├── consumption
│   │       └── inventory
│   └── gates/
│       └── rfid_reader/{reader_id}/
│           └── scan
├── animal/
│   ├── {rfid_tag}/
│   │   ├── activity
│   │   ├── rumination
│   │   ├── temperature
│   │   └── location
│   └── herd/
│       └── location_update
├── cold_storage/
│   ├── {unit_id}/
│   │   ├── temperature
│   │   └── humidity
│   └── alerts
├── alerts/
│   ├── milk_quality
│   ├── animal_health
│   ├── environmental
│   └── system
├── commands/
│   └── device/{device_id}/
│       └── control
└── system/
    ├── health
    ├── config
    └── logs
```

---

## 3. BROKER SELECTION

### 3.1 EMQX vs Alternatives

| Feature | EMQX | Mosquitto | HiveMQ | VerneMQ |
|---------|------|-----------|--------|---------|
| **License** | Apache 2.0 | EPL/EDL | Commercial | Apache 2.0 |
| **Clustering** | Native | None | Native | Native |
| **Performance** | 1M+ conn | 10K conn | 1M+ conn | 500K conn |
| **Persistence** | Built-in | None | Built-in | Built-in |
| **Rule Engine** | Built-in | None | Built-in | Plugin |
| **Monitoring** | Built-in | Basic | Built-in | Plugin |
| **Bangladesh Support** | Good | Good | Limited | Limited |

### 3.2 Selected Solution: EMQX 5.x

**Reasons:**
- Open source with enterprise features
- Native clustering for high availability
- Built-in rule engine for data transformation
- PostgreSQL authentication integration
- Webhook support for ERP integration
- Excellent monitoring capabilities

---

## 4. EMQX INSTALLATION

### 4.1 Docker Deployment (Recommended)

```yaml
# docker-compose.yml
version: '3.8'

services:
  emqx:
    image: emqx/emqx:5.4.1
    container_name: emqx
    restart: always
    environment:
      - EMQX_NAME=emqx
      - EMQX_HOST=emqx@emqx.smartdairy.local
      - EMQX_CLUSTER__DISCOVERY_STRATEGY=static
      - EMQX_CLUSTER__STATIC__SEEDS=emqx@emqx.smartdairy.local
      - EMQX_DASHBOARD__DEFAULT_USERNAME=admin
      - EMQX_DASHBOARD__DEFAULT_PASSWORD=${EMQX_ADMIN_PASSWORD}
      - EMQX_AUTHENTICATION__1__MECHANISM=password_based
      - EMQX_AUTHENTICATION__1__BACKEND=postgresql
      - EMQX_AUTHENTICATION__1__DATABASE=smart_dairy
      - EMQX_AUTHENTICATION__1__USERNAME=emqx_auth
      - EMQX_AUTHENTICATION__1__PASSWORD=${EMQX_DB_PASSWORD}
      - EMQX_AUTHENTICATION__1__SERVER=postgres:5432
    ports:
      - "1883:1883"       # MQTT
      - "8883:8883"       # MQTT over TLS
      - "8083:8083"       # MQTT over WebSocket
      - "8084:8084"       # MQTT over WSS
      - "18083:18083"     # Dashboard
    volumes:
      - emqx-data:/opt/emqx/data
      - emqx-etc:/opt/emqx/etc
      - ./emqx.conf:/opt/emqx/etc/emqx.conf:ro
      - ./certs:/opt/emqx/etc/certs:ro
    networks:
      - smartdairy-network
    healthcheck:
      test: ["CMD", "emqx", "ctl", "status"]
      interval: 30s
      timeout: 10s
      retries: 3

  emqx-node-2:
    image: emqx/emqx:5.4.1
    container_name: emqx-node-2
    restart: always
    environment:
      - EMQX_NAME=emqx
      - EMQX_HOST=emqx@emqx-node-2.smartdairy.local
      - EMQX_CLUSTER__DISCOVERY_STRATEGY=static
      - EMQX_CLUSTER__STATIC__SEEDS=emqx@emqx.smartdairy.local
    ports:
      - "1884:1883"
      - "8884:8883"
      - "18084:18083"
    volumes:
      - emqx-data-2:/opt/emqx/data
      - emqx-etc-2:/opt/emqx/etc
    networks:
      - smartdairy-network
    depends_on:
      - emqx

volumes:
  emqx-data:
  emqx-etc:
  emqx-data-2:
  emqx-etc-2:

networks:
  smartdairy-network:
    driver: bridge
```

### 4.2 Kubernetes Deployment

```yaml
# emqx-deployment.yaml
apiVersion: apps/v1
kind: StatefulSet
metadata:
  name: emqx
  namespace: smartdairy
spec:
  serviceName: emqx-headless
  replicas: 3
  selector:
    matchLabels:
      app: emqx
  template:
    metadata:
      labels:
        app: emqx
    spec:
      containers:
      - name: emqx
        image: emqx/emqx:5.4.1
        ports:
        - containerPort: 1883
          name: mqtt
        - containerPort: 8883
          name: mqtts
        - containerPort: 8083
          name: ws
        - containerPort: 8084
          name: wss
        - containerPort: 18083
          name: dashboard
        env:
        - name: EMQX_NAME
          value: "emqx"
        - name: EMQX_CLUSTER__DISCOVERY_STRATEGY
          value: "dns"
        - name: EMQX_CLUSTER__DNS__NAME
          value: "emqx-headless.smartdairy.svc.cluster.local"
        - name: EMQX_CLUSTER__DNS__RECORD_TYPE
          value: "srv"
        resources:
          requests:
            memory: "512Mi"
            cpu: "500m"
          limits:
            memory: "2Gi"
            cpu: "2000m"
        volumeMounts:
        - name: emqx-data
          mountPath: /opt/emqx/data
        - name: emqx-config
          mountPath: /opt/emqx/etc/emqx.conf
          subPath: emqx.conf
  volumeClaimTemplates:
  - metadata:
      name: emqx-data
    spec:
      accessModes: ["ReadWriteOnce"]
      resources:
        requests:
          storage: 10Gi
---
apiVersion: v1
kind: Service
metadata:
  name: emqx
  namespace: smartdairy
spec:
  type: LoadBalancer
  selector:
    app: emqx
  ports:
  - name: mqtt
    port: 1883
    targetPort: 1883
  - name: mqtts
    port: 8883
    targetPort: 8883
  - name: ws
    port: 8083
    targetPort: 8083
  - name: wss
    port: 8084
    targetPort: 8084
  - name: dashboard
    port: 18083
    targetPort: 18083
---
apiVersion: v1
kind: Service
metadata:
  name: emqx-headless
  namespace: smartdairy
spec:
  type: ClusterIP
  clusterIP: None
  selector:
    app: emqx
  ports:
  - name: mqtt
    port: 1883
    targetPort: 1883
```

---

## 5. CONFIGURATION

### 5.1 Main Configuration File (emqx.conf)

```hcl
# EMQX Configuration File
# Location: /opt/emqx/etc/emqx.conf

## =============================================================================
## NODE CONFIGURATION
## =============================================================================

node {
  name = "emqx@127.0.0.1"
  cookie = "smart_dairy_emqx_secret_cookie_change_in_production"
  data_dir = "/opt/emqx/data"
}

## =============================================================================
## CLUSTER CONFIGURATION
## =============================================================================

cluster {
  name = smartdairy
  discovery_strategy = static
  static {
    seeds = ["emqx@emqx-1.smartdairy.local", "emqx@emqx-2.smartdairy.local"]
  }
}

## =============================================================================
## LISTENERS
## =============================================================================

listeners.tcp.default {
  bind = "0.0.0.0:1883"
  max_connections = 10000
  acceptors = 16
}

listeners.ssl.default {
  bind = "0.0.0.0:8883"
  max_connections = 10000
  ssl_options {
    certfile = "/opt/emqx/etc/certs/server.crt"
    keyfile = "/opt/emqx/etc/certs/server.key"
    cacertfile = "/opt/emqx/etc/certs/ca.crt"
    verify = verify_peer
    fail_if_no_peer_cert = false
  }
}

listeners.ws.default {
  bind = "0.0.0.0:8083"
  max_connections = 5000
  websocket {
    mqtt_path = "/mqtt"
  }
}

listeners.wss.default {
  bind = "0.0.0.0:8084"
  max_connections = 5000
  ssl_options {
    certfile = "/opt/emqx/etc/certs/server.crt"
    keyfile = "/opt/emqx/etc/certs/server.key"
  }
}

## =============================================================================
## AUTHENTICATION (PostgreSQL)
## =============================================================================

authentication = [
  {
    mechanism = password_based
    backend = postgresql
    enable = true
    
    password_hash_algorithm {
      name = sha256
      salt_position = suffix
    }
    
    database = smart_dairy
    username = emqx_auth
    password = "${EMQX_DB_PASSWORD}"
    server = "postgres.smartdairy.local:5432"
    pool_size = 8
    
    query = """
      SELECT password_hash as password_hash, 
             salt as salt,
             'mqtt_user' as is_superuser
      FROM iot.device_credentials 
      WHERE device_id = ${username} 
      AND enabled = true
      AND (expires_at IS NULL OR expires_at > NOW())
    """
  }
]

## =============================================================================
## AUTHORIZATION (PostgreSQL ACL)
## =============================================================================

authorization {
  sources = [
    {
      type = postgresql
      enable = true
      
      database = smart_dairy
      username = emqx_auth
      password = "${EMQX_DB_PASSWORD}"
      server = "postgres.smartdairy.local:5432"
      pool_size = 8
      
      query = """
        SELECT permission, action, topic 
        FROM iot.device_acl 
        WHERE device_id = ${username}
        AND (topic = ${topic} OR topic LIKE ${topic})
      """
    }
  ]
  
  no_match = deny
  deny_action = disconnect
  cache {
    enable = true
    max_size = 10000
    ttl = 5m
  }
}

## =============================================================================
## PERSISTENCE (Message Retention)
## =============================================================================

retainer {
  enable = true
  msg_expiry_interval = 1h
  msg_clear_interval = 30m
  max_payload_size = 1MB
  storage_type = built_in_database
  
  backend {
    type = built_in_database
    max_retained_messages = 10000
  }
}

## =============================================================================
## WEBHOOK (ERP Integration)
## =============================================================================

rule_engine {
  rules {
    milk_data_to_erp {
      sql = """
        SELECT 
          payload.data.animal_rfid as cow_id,
          payload.data.volume_liters as volume,
          payload.data.conductivity_ms_cm as conductivity,
          payload.device_id as meter_id,
          timestamp as event_time
        FROM "smartdairy/farm/+/milking_parlor/milk_meter/+/data"
        WHERE payload.data.volume_liters > 0
      """
      
      actions {
        webhook {
          url = "https://api.smartdairybd.com/iot/v1/milk-production"
          method = POST
          headers {
            "Content-Type" = "application/json"
            "Authorization" = "Bearer ${WEBHOOK_TOKEN}"
            "X-Source" = "emqx-webhook"
          }
          body = "${.}"
          max_retries = 3
        }
      }
    }
    
    alert_to_notification {
      sql = """
        SELECT *
        FROM "smartdairy/alerts/+/+"
      """
      
      actions {
        webhook {
          url = "https://api.smartdairybd.com/iot/v1/alerts"
          method = POST
          headers {
            "Content-Type" = "application/json"
            "Authorization" = "Bearer ${ALERT_WEBHOOK_TOKEN}"
          }
          body = "${.}"
        }
      }
    }
  }
}

## =============================================================================
## LOGGING
## =============================================================================

log {
  console {
    enable = true
    level = warning
    formatter = text
  }
  
  file {
    enable = true
    level = info
    path = "/opt/emqx/log"
    rotation {
      enable = true
      max_size = 100MB
      max_files = 10
    }
  }
}

## =============================================================================
## DASHBOARD
## =============================================================================

dashboard {
  listeners.http {
    bind = 18083
    num_acceptors = 4
    max_connections = 512
    backlog = 512
    send_timeout = 30s
    inet6 = false
    ipv6_v6only = false
  }
  
  default_username = "admin"
  default_password = "${EMQX_ADMIN_PASSWORD}"
  
  swagger_support = true
  i18n_lang = en
}

## =============================================================================
## PROMETHEUS METRICS
## =============================================================================

prometheus {
  enable = true
  interval = 15s
  push_gateway_server = "http://prometheus-pushgateway:9091"
  headers {
    "Authorization" = "Bearer ${PROMETHEUS_TOKEN}"
  }
  
  collectors {
    vm_stats = true
    mnesia = true
  }
}
```

---

## 6. SECURITY SETUP

### 6.1 TLS/SSL Certificate Generation

```bash
#!/bin/bash
# generate-certs.sh
# Generate self-signed certificates for MQTT TLS

CERT_DIR="./certs"
mkdir -p $CERT_DIR

# Generate CA private key
openssl genrsa -out $CERT_DIR/ca.key 4096

# Generate CA certificate
openssl req -new -x509 -days 3650 -key $CERT_DIR/ca.key \
  -out $CERT_DIR/ca.crt \
  -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/CN=Smart Dairy IoT CA"

# Generate server private key
openssl genrsa -out $CERT_DIR/server.key 2048

# Generate server certificate request
openssl req -new -key $CERT_DIR/server.key \
  -out $CERT_DIR/server.csr \
  -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Ltd/CN=mqtt.smartdairybd.com"

# Sign server certificate with CA
cat > $CERT_DIR/server.ext << EOF
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, keyEncipherment
extendedKeyUsage = serverAuth
subjectAltName = @alt_names

[alt_names]
DNS.1 = mqtt.smartdairybd.com
DNS.2 = emqx.smartdairy.local
DNS.3 = *.smartdairy.local
IP.1 = 192.168.1.100
IP.2 = 127.0.0.1
EOF

openssl x509 -req -in $CERT_DIR/server.csr \
  -CA $CERT_DIR/ca.crt -CAkey $CERT_DIR/ca.key \
  -CAcreateserial -out $CERT_DIR/server.crt \
  -days 365 -sha256 -extfile $CERT_DIR/server.ext

# Set permissions
chmod 600 $CERT_DIR/*.key
chmod 644 $CERT_DIR/*.crt

echo "Certificates generated in $CERT_DIR"
echo "Deploy ca.crt to IoT devices"
echo "Deploy server.crt and server.key to EMQX broker"
```

### 6.2 Device Authentication Setup

```sql
-- PostgreSQL schema for device authentication
-- Run in smart_dairy database

-- Device credentials table
CREATE TABLE iot.device_credentials (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) UNIQUE NOT NULL,
    device_type VARCHAR(50) NOT NULL,
    password_hash VARCHAR(256) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    enabled BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT NOW(),
    expires_at TIMESTAMP,
    last_connected_at TIMESTAMP,
    metadata JSONB
);

-- Device ACL table
CREATE TABLE iot.device_acl (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) NOT NULL REFERENCES iot.device_credentials(device_id),
    permission VARCHAR(10) NOT NULL CHECK (permission IN ('allow', 'deny')),
    action VARCHAR(10) NOT NULL CHECK (action IN ('publish', 'subscribe', 'all')),
    topic VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Create indexes
CREATE INDEX idx_device_acl_device_id ON iot.device_acl(device_id);
CREATE INDEX idx_device_acl_topic ON iot.device_acl(topic);

-- Insert sample device credentials
INSERT INTO iot.device_credentials (device_id, device_type, password_hash, salt)
VALUES (
    'milk_meter_001',
    'milk_meter',
    encode(digest('secure_password_random_salt' || 'random_salt', 'sha256'), 'hex'),
    'random_salt'
);

-- Grant permissions to milk meter
INSERT INTO iot.device_acl (device_id, permission, action, topic)
VALUES 
    ('milk_meter_001', 'allow', 'publish', 'smartdairy/farm/+/milking_parlor/milk_meter/001/data'),
    ('milk_meter_001', 'allow', 'subscribe', 'smartdairy/commands/device/001/+'),
    ('milk_meter_001', 'deny', 'subscribe', 'smartdairy/farm/+/milking_parlor/milk_meter/+/data');
```

---

## 7. CLUSTERING & HIGH AVAILABILITY

### 7.1 Cluster Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      EMQX CLUSTER ARCHITECTURE                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                        LOAD BALANCER (HAProxy)                       │    │
│  │  ┌───────────────────────────────────────────────────────────────┐  │    │
│  │  │  TCP (1883)  │  TLS (8883)  │  WS (8083)  │  Dashboard (18083) │  │    │
│  │  └───────────────────────────────────────────────────────────────┘  │    │
│  └──────────────────────┬──────────────────────────────────────────────┘    │
│                         │                                                   │
│         ┌───────────────┼───────────────┐                                   │
│         │               │               │                                   │
│  ┌──────▼──────┐ ┌──────▼──────┐ ┌──────▼──────┐                          │
│  │  EMQX Node 1 │ │  EMQX Node 2 │ │  EMQX Node 3 │                          │
│  │  (Master)   │ │  (Slave)    │ │  (Slave)    │                          │
│  │             │ │             │ │             │                          │
│  │  ┌────────┐ │ │  ┌────────┐ │ │  ┌────────┐ │                          │
│  │  │ MQTT   │◄┼─►│  │ MQTT   │◄┼─►│  │ MQTT   │ │                          │
│  │  │ Broker │ │ │  │ Broker │ │ │  │ Broker │ │                          │
│  │  └────────┘ │ │  └────────┘ │ │  └────────┘ │                          │
│  │       │      │ │       │      │ │       │      │                          │
│  │  ┌────▼────┐ │ │  ┌────▼────┐ │ │  ┌────▼────┐ │                          │
│  │  │ Mnesia  │◄┼─►│  │ Mnesia  │◄┼─►│  │ Mnesia  │ │                          │
│  │  │ (DB)    │ │ │  │ (DB)    │ │ │  │ (DB)    │ │                          │
│  │  └─────────┘ │ │  └─────────┘ │ │  └─────────┘ │                          │
│  └──────────────┘ └──────────────┘ └──────────────┘                          │
│                                                                              │
│  SHARED STORAGE: PostgreSQL (Auth), MinIO (Messages - optional)             │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. MONITORING & MAINTENANCE

### 8.1 Health Check Commands

```bash
# Check EMQX status
emqx_ctl status

# Check cluster status
emqx_ctl cluster status

# List connected clients
emqx_ctl clients list

# Show subscription statistics
emqx_ctl subscriptions show <client_id>

# Check listener status
emqx_ctl listeners

# View connected nodes
emqx_ctl broker stats
```

### 8.2 Prometheus Metrics

| Metric | Description | Alert Threshold |
|--------|-------------|-----------------|
| `emqx_connections_count` | Current connections | > 8000 |
| `emqx_messages_received` | Messages received/sec | - |
| `emqx_messages_sent` | Messages sent/sec | - |
| `emqx_messages_dropped` | Dropped messages | > 100/min |
| `emqx_session_count` | Active sessions | - |
| `emqx_subscriptions_count` | Total subscriptions | - |
| `emqx_license_expiry` | License expiry days | < 30 days |

---

## 9. PERFORMANCE TUNING

### 9.1 System Limits

```bash
# /etc/sysctl.conf for EMQX host

# Increase file descriptors
fs.file-max = 2097152

# TCP tuning
net.core.somaxconn = 65535
net.ipv4.tcp_max_syn_backlog = 65535
net.ipv4.ip_local_port_range = 1024 65535

# Network buffers
net.core.rmem_default = 262144
net.core.wmem_default = 262144
net.core.rmem_max = 16777216
net.core.wmem_max = 16777216
net.ipv4.tcp_rmem = 4096 4096 16777216
net.ipv4.tcp_wmem = 4096 4096 16777216
```

---

## 10. TROUBLESHOOTING

### 10.1 Common Issues

| Issue | Symptoms | Solution |
|-------|----------|----------|
| Connection refused | Client can't connect | Check firewall, listener config |
| Authentication failed | Invalid credentials | Check PostgreSQL auth table |
| Message loss | High dropped count | Increase QoS, check consumer rate |
| Cluster split | Nodes not seeing each | Check network, cookie config |
| High memory | OOM errors | Tune cache settings, scale horizontally |

---

## 11. APPENDICES

### Appendix A: Database Schema

```sql
-- Complete database setup for IoT authentication
CREATE SCHEMA iot;

-- Device credentials
CREATE TABLE iot.device_credentials (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) UNIQUE NOT NULL,
    device_type VARCHAR(50) NOT NULL,
    password_hash VARCHAR(256) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    enabled BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT NOW(),
    expires_at TIMESTAMP,
    last_connected_at TIMESTAMP,
    metadata JSONB DEFAULT '{}'::jsonb
);

-- Device ACL
CREATE TABLE iot.device_acl (
    id SERIAL PRIMARY KEY,
    device_id VARCHAR(100) NOT NULL REFERENCES iot.device_credentials(device_id) ON DELETE CASCADE,
    permission VARCHAR(10) NOT NULL,
    action VARCHAR(10) NOT NULL,
    topic VARCHAR(255) NOT NULL,
    qos INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Connection log
CREATE TABLE iot.connection_log (
    id BIGSERIAL PRIMARY KEY,
    device_id VARCHAR(100) NOT NULL,
    connected_at TIMESTAMP NOT NULL,
    disconnected_at TIMESTAMP,
    ip_address INET,
    reason VARCHAR(100)
);

-- Create indexes
CREATE INDEX idx_device_credentials_enabled ON iot.device_credentials(enabled);
CREATE INDEX idx_device_acl_device_id ON iot.device_acl(device_id);
CREATE INDEX idx_connection_log_device_id ON iot.connection_log(device_id);
CREATE INDEX idx_connection_log_connected_at ON iot.connection_log(connected_at);
```

### Appendix B: Quick Reference

```bash
# Start EMQX
docker-compose up -d emqx

# View logs
docker logs -f emqx

# Enter container
docker exec -it emqx sh

# Reload configuration
emqx_ctl reload

# Backup data
docker exec emqx emqx_ctl data export /opt/emqx/data/backup.json
```

---

**END OF MQTT BROKER CONFIGURATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | July 28, 2026 | IoT Engineer | Initial version |
