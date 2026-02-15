# Document K-016: System Documentation - Technical

## Smart Dairy Ltd. - Smart Portal & ERP System

---

| Document Information | |
|--------------------------|---|
| Document ID | K-016 |
| Version | 1.0 |
| Date | January 31, 2026 |
| Author | Technical Lead |
| Owner | IT Director |
| Reviewer | Solution Architect |
| Status | Draft |
| Classification | Internal Use |

---

| Target Audience |
|---------------------|
| IT Staff, Developers, System Administrators, DevOps Engineers, Security Team |

---

## Table of Contents

1. Introduction
2. System Architecture
3. Infrastructure
4. Application Components
5. Data Flow
6. Configuration
7. Deployment
8. Monitoring
9. Backup & Recovery
10. Troubleshooting
11. Maintenance
12. Security
13. Appendices

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive technical documentation for the Smart Dairy Smart Portal and Integrated ERP System. It serves as the primary reference for IT staff, developers, system administrators, and DevOps engineers responsible for deploying, maintaining, and supporting the system.

### 1.2 System Overview

The Smart Dairy Smart Portal is an integrated digital platform built on **Odoo 19 Community Edition** with strategic custom development.

### 1.3 System Components

- **Public Website**: Corporate web presence and brand showcase
- **B2C E-commerce**: Direct-to-consumer dairy product sales
- **B2B Marketplace**: Wholesale distribution and partner management
- **Smart Farm Management**: Herd tracking, milk production, and farm operations
- **Integrated ERP**: Complete business process automation

---

## 2. System Architecture

### 2.1 Architecture Overview

The Smart Dairy ERP system follows a multi-tier architecture:

1. **Client Layer**: Web browsers and mobile applications
2. **Load Balancer**: Nginx for SSL termination and traffic distribution
3. **Application Layer**: Odoo 19 CE with custom modules
4. **Data Layer**: PostgreSQL, Redis, InfluxDB, MinIO
5. **Integration Layer**: Payment gateways, SMS, IoT sensors

### 2.2 Core Components

| Component | Technology | Purpose | Version |
|-----------|------------|---------|---------|
| Application Server | Odoo 19 CE | Core ERP Platform | 19.0 |
| Web Server | Nginx | Reverse Proxy, SSL | 1.24+ |
| Application Server | Gunicorn | WSGI HTTP Server | 21.2+ |
| Database | PostgreSQL | Primary Database | 16.x |
| Cache & Queue | Redis | Session, Cache, Jobs | 7.x |
| Time-Series DB | InfluxDB | IoT Sensor Data | 2.x |
| File Storage | MinIO | Object Storage | Latest |
| Search Engine | Elasticsearch | Full-text Search | 8.x |
| API Gateway | FastAPI | Mobile/External APIs | 0.104+ |
| Message Broker | Mosquitto | MQTT for IoT | 2.x |

### 2.3 Custom Modules

| Module | Technical Name | Purpose | Dependencies |
|--------|---------------|---------|--------------|
| Smart Dairy Farm | smart_dairy_farm | Farm Management | stock, purchase, account |
| Smart Dairy B2B | smart_dairy_b2b | B2B Portal | website, sale, account |
| Smart Dairy IoT | smart_dairy_iot | IoT Integration | base, web |
| Bangladesh Local | l10n_bd_smart | BD Payroll & VAT | hr_payroll, account |
| Mobile Backend | smart_dairy_mobile | Mobile APIs | base, web |

---

## 3. Infrastructure

### 3.1 Production Environment Specifications

| Component | Specification | Qty | OS | Purpose |
|-----------|--------------|-----|-----|---------|
| App Server 1 | 16 vCPU, 64GB RAM, 500GB SSD | 1 | Ubuntu 22.04 LTS | Odoo Application |
| App Server 2 | 16 vCPU, 64GB RAM, 500GB SSD | 1 | Ubuntu 22.04 LTS | Odoo Application (HA) |
| DB Primary | 32 vCPU, 128GB RAM, 1TB NVMe | 1 | Ubuntu 22.04 LTS | PostgreSQL Primary |
| DB Replica | 32 vCPU, 128GB RAM, 1TB NVMe | 1 | Ubuntu 22.04 LTS | PostgreSQL Replica |
| Worker Nodes | 8 vCPU, 32GB RAM, 200GB SSD | 2 | Ubuntu 22.04 LTS | Celery Workers |
| Redis Cluster | 4 vCPU, 16GB RAM, 100GB SSD | 3 | Ubuntu 22.04 LTS | Cache & Queue |
| Load Balancer | 4 vCPU, 8GB RAM, 100GB SSD | 2 | Ubuntu 22.04 LTS | Nginx/HAProxy |
| Monitoring | 4 vCPU, 8GB RAM, 200GB SSD | 1 | Ubuntu 22.04 LTS | Prometheus/Grafana |

### 3.2 Estimated Cloud Costs (AWS)

| Service | Monthly (USD) | Annual (USD) | Annual (BDT) |
|---------|---------------|--------------|--------------|
| EC2 (Compute) | ,500 | ,000 | BDT 36,00,000 |
| RDS PostgreSQL | ,200 | ,400 | BDT 17,28,000 |
| ElastiCache |  | ,800 | BDT 5,76,000 |
| S3 Storage |  | ,600 | BDT 4,32,000 |
| Load Balancer |  | ,400 | BDT 2,88,000 |
| Data Transfer |  | ,800 | BDT 5,76,000 |
| CloudWatch |  | ,400 | BDT 2,88,000 |
| **Total** | **,200** | **,400** | **BDT 74,88,000** |

### 3.3 Network Architecture

Network segments:
- DMZ: 103.245.205.0/28
- App Layer: 10.0.1.0/24
- Data Layer: 10.0.2.0/24
- Management: 10.0.3.0/24
- IoT Network: 10.0.4.0/24

### 3.4 Database Architecture

PostgreSQL 16 with streaming replication:
- Synchronous commit enabled
- 500 max connections
- 32GB shared_buffers
- Continuous WAL archiving
- Daily full backups with PITR capability

Key parameters:
- max_connections = 500
- shared_buffers = 32GB
- effective_cache_size = 96GB
- maintenance_work_mem = 2GB
- wal_level = replica

### 3.5 Security Architecture

Five-layer security model:
1. Perimeter: Cloudflare DDoS, WAF, Rate Limiting
2. Network: Firewall, VLANs, VPN, IDS/IPS
3. Application: SSL/TLS 1.3, CSRF, XSS Prevention
4. Data: Encryption at Rest (AES-256), TLS 1.3 in Transit
5. Access: RBAC, MFA, SSO, Session Management

---

## 4. Application Components

### 4.1 Odoo 19 CE Core Modules

| Module | Version | Purpose |
|--------|---------|---------|
| base | 19.0 | Core framework |
| web | 19.0 | Web client |
| website | 19.0 | Website builder |
| website_sale | 19.0 | E-commerce |
| sale | 19.0 | Sales Management |
| purchase | 19.0 | Purchase Management |
| stock | 19.0 | Inventory Management |
| mrp | 19.0 | Manufacturing |
| account | 19.0 | Accounting |
| hr | 19.0 | Human Resources |
| hr_payroll | 19.0 | Payroll |
| crm | 19.0 | Customer Relationship |
| point_of_sale | 19.0 | POS |
| project | 19.0 | Project Management |
| subscription | 19.0 | Subscription Management |
| quality | 19.0 | Quality Management |

### 4.2 Custom Modules

| Module | Version | Purpose | Lines of Code |
|--------|---------|---------|---------------|
| smart_dairy_farm | 1.0.0 | Farm & Herd Management | ~5,000 |
| smart_dairy_b2b | 1.0.0 | B2B Portal Extensions | ~3,500 |
| smart_dairy_iot | 1.0.0 | IoT Integration Platform | ~2,500 |
| l10n_bd_smart | 1.0.0 | Bangladesh Localization | ~2,000 |
| smart_dairy_mobile | 1.0.0 | Mobile App Backend APIs | ~1,500 |
| smart_dairy_reports | 1.0.0 | Custom Reports & Dashboards | ~1,000 |

### 4.3 API Gateway

FastAPI gateway provides:
- Rate limiting (Redis-based)
- Authentication (JWT/OAuth2)
- Request validation (Pydantic)
- CORS handling
- Request logging

Key endpoints:
- /api/v1/auth/login - Authentication
- /api/v1/customers - Customer management
- /api/v1/orders - Order management
- /api/v1/products - Product catalog
- /api/v1/farm/animals - Farm animals
- /api/v1/farm/milk-production - Milk recording
- /api/v1/iot/sensors - IoT data ingestion
- /api/v1/b2b/partners - B2B management

### 4.4 Mobile Applications

**Framework**: Flutter (Dart)

Three mobile apps:
1. Customer App - Ordering, tracking, subscriptions
2. Field Sales App - Order taking, collections, CRM
3. Farm Staff App - Data entry, RFID scanning, offline sync

---

## 5. Data Flow

### 5.1 B2C Order Flow

1. Customer browses products via mobile app/web
2. Items added to cart
3. Checkout initiated
4. Payment via bKash/Nagad/Rocket
5. Webhook confirmation from gateway
6. Stock picking initiated
7. Courier pickup and delivery tracking

### 5.2 Milk Production Data Flow

1. Milk meter records volume and quality
2. IoT gateway sends data via MQTT
3. WebSocket updates for live monitoring
4. Celery scheduler aggregates daily production
5. Automatic journal entries for milk valuation

### 5.3 IoT Data Pipeline

Sensors: Milk meters, temperature, activity collars, weight scales
Edge: Raspberry Pi gateway with MQTT/LoRaWAN
Cloud: Mosquitto broker, InfluxDB, real-time alerts

---

## 6. Configuration

### 6.1 Odoo Configuration (odoo.conf)

`
[options]
admin_passwd = [ENCRYPTED]
db_host = 10.0.2.10
db_port = 5432
db_user = odoo
db_password = [PASSWORD]
db_name = smart_dairy_prod

http_port = 8069
longpolling_port = 8072
proxy_mode = True

workers = 17
max_cron_threads = 4
limit_memory_hard = 42949672960
limit_memory_soft = 34359738368

logfile = /var/log/odoo/odoo.log
log_level = info

smtp_server = smtp.sendgrid.net
smtp_port = 587
email_from = noreply@smartdairybd.com

data_dir = /opt/odoo/data
addons_path = /opt/odoo/addons,/opt/odoo/custom-addons
list_db = False
`

### 6.2 Environment Variables

Key variables:
- ENVIRONMENT - production/staging/development
- DB_HOST, DB_PASSWORD - Database connection
- REDIS_HOST, REDIS_PASSWORD - Cache connection
- JWT_SECRET - JWT signing
- BKASH_APP_KEY, BKASH_APP_SECRET - bKash API
- SMS_API_KEY - SMS gateway
- SENTRY_DSN - Error tracking

### 6.3 Feature Flags

| Flag | Default |
|------|---------|
| FF_B2B_PORTAL_ENABLED | true |
| FF_SUBSCRIPTION_V2 | true |
| FF_IOT_REALTIME | true |
| FF_ADVANCED_ANALYTICS | false |

---

## 7. Deployment

### 7.1 CI/CD Pipeline

Stages:
1. Build - Docker image creation
2. Test - Unit and integration tests
3. Security - Vulnerability scanning
4. Deploy Staging - Automatic
5. Deploy Production - Manual approval

### 7.2 Release Checklist

- All features merged
- Version numbers updated
- CHANGELOG updated
- Migration scripts tested
- Performance benchmarks completed
- Security scan passed
- UAT sign-off received

---

## 8. Monitoring

### 8.1 Tools

- Prometheus - Metrics collection
- Grafana - Visualization
- AlertManager - Alert routing
- Sentry - Error tracking
- ELK Stack - Log aggregation

### 8.2 Alert Rules

| Alert | Condition | Severity |
|-------|-----------|----------|
| HighErrorRate | > 5% error rate | Critical |
| OdooDown | No response | Critical |
| DatabaseConnectionsHigh | > 400 connections | Warning |
| DiskSpaceLow | < 10% free | Warning |
| MemoryHigh | > 90% usage | Warning |

---

## 9. Backup & Recovery

### 9.1 Backup Strategy

| Data Type | Frequency | Retention |
|-----------|-----------|-----------|
| PostgreSQL Full | Daily 02:00 | 30 days local, 90 days offsite |
| PostgreSQL WAL | Continuous | 7 days |
| File Storage | Daily 03:00 | 30 days local, 90 days offsite |
| InfluxDB | Daily 04:00 | 30 days |

### 9.2 DR Setup

- Primary Site: Dhaka, Bangladesh
- DR Site: Singapore/Azure
- RTO: 4 hours
- RPO: 1 hour
- Async streaming replication

---

## 10. Troubleshooting

### 10.1 Common Issues

| Issue | Solution |
|-------|----------|
| 502 Bad Gateway | Restart Odoo |
| Slow Response | Check CPU/Memory |
| Database Connection Error | Restart PostgreSQL |
| Session Expired | Adjust timeout |
| Cache Issues | Clear cache |

### 10.2 Escalation

- L1: helpdesk@smartdairybd.com (15 min)
- L2: it-support@smartdairybd.com (30 min)
- L3: it-lead@smartdairybd.com (1 hour)
- L4: it-director@smartdairybd.com (4 hour)

---

## 11. Maintenance

### 11.1 Schedule

| Task | Frequency |
|------|-----------|
| Database Vacuum | Daily 02:00 |
| Log Rotation | Daily 03:00 |
| Security Updates | Weekly Sunday 02:00 |
| Index Rebuild | Monthly 1st Sunday |
| Major Upgrades | Quarterly |

### 11.2 Patch Management

- Ubuntu OS: Monthly
- PostgreSQL: Quarterly
- Odoo CE: Quarterly after testing
- Python Packages: Monthly
- Security patches: ASAP

---

## 12. Security

### 12.1 SSH Hardening

- Disable root login
- Key authentication only
- Port 2222 (non-standard)
- Limited user access
- Strong algorithms

### 12.2 Certificate Management

- Let's Encrypt certificates
- Auto-renewal via cron
- SSL/TLS 1.3
- HSTS enabled
- Expiry monitoring

### 12.3 Access Control

RBAC Groups:
- Farm Manager
- Farm Operator
- Farm Viewer (Read-only)
- B2B Partner
- Customer

---

## 13. Appendices

### Appendix A: Network Diagrams

Network segments documented in section 3.2

### Appendix B: Database Schemas

Key tables:
- smart_dairy_animal
- smart_dairy_breeding
- smart_dairy_milk_production
- smart_dairy_health
- sale_order, purchase_order
- stock_picking, stock_move
- account_move, account_move_line

### Appendix C: Command Reference

Odoo Management:
`
./odoo-bin -c /etc/odoo/odoo.conf
./odoo-bin -c /etc/odoo/odoo.conf -d smart_dairy_prod -u all --stop-after-init
pg_dump -h localhost -U odoo -d smart_dairy_prod > backup.sql
`

Docker Commands:
`
docker-compose up -d
docker-compose logs -f odoo
docker-compose restart odoo
docker-compose exec odoo bash
`

PostgreSQL Commands:
`
psql -h localhost -U odoo -d smart_dairy_prod
SELECT * FROM pg_stat_activity;
VACUUM ANALYZE;
`

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Technical Lead | Initial release |

---

*End of Document*

**Smart Dairy Ltd.**
Jahir Smart Tower, 205/1 & 205/1/A, West Kafrul
Begum Rokeya Sharani, Taltola, Dhaka-1207, Bangladesh
https://smartdairybd.com
