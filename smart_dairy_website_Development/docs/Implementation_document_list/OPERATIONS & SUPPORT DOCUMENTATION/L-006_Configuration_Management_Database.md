# Configuration Management Database (CMDB)

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | L-006 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Configuration Manager |
| **Owner** | Configuration Manager |
| **Reviewer** | IT Director |
| **Classification** | Internal Use |
| **Status** | Approved |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Configuration Manager | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Configuration Items (CIs)](#2-configuration-items-cis)
3. [CI Attributes](#3-ci-attributes)
4. [CI Relationships](#4-ci-relationships)
5. [CI Lifecycle](#5-ci-lifecycle)
6. [CMDB Structure](#6-cmdb-structure)
7. [Discovery and Population](#7-discovery-and-population)
8. [Maintenance Procedures](#8-maintenance-procedures)
9. [Access Control](#9-access-control)
10. [Auditing and Reconciliation](#10-auditing-and-reconciliation)
11. [Integration with Other Processes](#11-integration-with-other-processes)
12. [Tools (GLPI/OCS Inventory)](#12-tools-glpiocs-inventory)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the Configuration Management Database (CMDB) framework for Smart Dairy Ltd.'s Smart Web Portal System and associated IT infrastructure. The CMDB serves as the authoritative source of information for all Configuration Items (CIs) and their relationships, enabling effective IT service management.

### 1.2 Scope

The CMDB covers:

| In Scope | Out of Scope |
|----------|--------------|
| All production infrastructure components | Personal user devices not connected to corporate network |
| Network equipment and configurations | Non-IT assets (furniture, stationary) |
| Software applications and licenses | Temporary test environments (unless shared) |
| IoT devices and sensors | Personal software installations |
| Documentation and knowledge assets | Consumer-grade IoT devices |
| IT services and service components | Deprecated systems (after archival period) |

### 1.3 CMDB Benefits

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       CMDB BUSINESS VALUE PROPOSITION                        │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐
│  IMPACT ANALYSIS    │    │  CHANGE MANAGEMENT  │    │  INCIDENT MANAGEMENT│
├─────────────────────┤    ├─────────────────────┤    ├─────────────────────┤
│ • Identify affected │    │ • Assess change risk│    │ • Faster root cause │
│   components        │    │ • Plan maintenance  │    │   analysis          │
│ • Evaluate cascade  │    │ • Minimize service  │    │ • Understand service│
│   effects           │    │   disruption        │    │   dependencies      │
│ • Plan for resilience│   │ • Validate rollback │    │ • Reduce MTTR       │
└──────────┬──────────┘    └──────────┬──────────┘    └──────────┬──────────┘
           │                          │                          │
           └──────────────────────────┼──────────────────────────┘
                                      │
                                      ▼
           ┌─────────────────────────────────────────────────────┐
           │         OPTIMIZED IT SERVICE DELIVERY               │
           │  • Cost optimization through asset visibility       │
           │  • Compliance and audit readiness                   │
           │  • Strategic planning based on accurate data        │
           │  • Improved vendor and contract management          │
           └─────────────────────────────────────────────────────┘
```

### 1.4 Definitions

| Term | Definition |
|------|------------|
| **Configuration Item (CI)** | Any component that needs to be managed to deliver an IT service |
| **CMDB** | Configuration Management Database - stores all CI information |
| **CMS** | Configuration Management System - includes CMDB and supporting processes |
| **Relationship** | Connection between CIs showing dependency, composition, or association |
| **CI Class** | Category of CIs with similar attributes (e.g., Server, Application) |
| **CI Type** | Specific classification within a CI class |
| **Baseline** | Reference point capturing CI state at a specific time |

---

## 2. Configuration Items (CIs)

### 2.1 CI Hierarchy Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     SMART DAIRY CMDB CI HIERARCHY                           │
└─────────────────────────────────────────────────────────────────────────────┘

                        ┌──────────────────┐
                        │  SMART DAIRY IT  │
                        │    SERVICES      │
                        └────────┬─────────┘
                                 │
        ┌────────────────────────┼────────────────────────┐
        │                        │                        │
        ▼                        ▼                        ▼
┌───────────────┐      ┌───────────────┐      ┌───────────────┐
│   HARDWARE    │      │   SOFTWARE    │      │   SERVICES    │
└───────┬───────┘      └───────┬───────┘      └───────┬───────┘
        │                      │                      │
   ┌────┴────┐            ┌────┴────┐            ┌────┴────┐
   │         │            │         │            │         │
   ▼         ▼            ▼         ▼            ▼         ▼
┌──────┐  ┌──────┐    ┌──────┐  ┌──────┐    ┌──────┐  ┌──────┐
│Server│  │Network│   │App   │  │License│   │IT    │  │Business│
│      │  │       │   │      │  │       │   │Service│  │Service │
└──┬───┘  └──┬───┘    └──┬───┘  └──┬───┘    └──┬───┘  └──┬───┘
   │         │           │         │           │         │
   ▼         ▼           ▼         ▼           ▼         ▼
┌──────┐  ┌──────┐    ┌──────┐  ┌──────┐    ┌──────┐  ┌──────┐
│Physical│ │Router │   │ERP   │  │Microsoft│  │B2C   │  │Order   │
│Server │  │      │   │System│  │365     │   │Portal│  │Processing│
└──────┘  └──────┘    └──────┘  └──────┘    └──────┘  └──────┘
```

### 2.2 Hardware Configuration Items

#### 2.2.1 Servers

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Physical Servers** | Bare-metal compute resources | Dell PowerEdge, HPE ProLiant |
| **Virtual Servers** | VM-based compute resources | VMware VMs, AWS EC2 instances |
| **Container Hosts** | Kubernetes/EKS worker nodes | EKS managed node groups |
| **Database Servers** | Dedicated database hardware | RDS instances, PostgreSQL servers |

**Server CI Attributes:**
| Attribute | Description | Example |
|-----------|-------------|---------|
| CI ID | Unique identifier | SRV-PROD-001 |
| Hostname | System hostname | sd-erp-prod-01 |
| Manufacturer | Hardware vendor | Dell |
| Model | Specific model | PowerEdge R750 |
| Serial Number | Hardware serial | ABC123456789 |
| CPU | Processor details | Intel Xeon Gold 6248R |
| Memory | RAM capacity | 128 GB DDR4 |
| Storage | Disk configuration | 2x 960GB SSD RAID 1 |
| Network Interfaces | NIC details | 2x 10GbE |
| Operating System | OS and version | Ubuntu 22.04 LTS |
| Location | Physical/datacenter location | AWS ap-south-1a |
| Owner | Responsible team | DevOps Team |

#### 2.2.2 Network Equipment

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Routers** | Layer 3 network devices | Cisco ISR, Juniper SRX |
| **Switches** | Layer 2 network devices | Cisco Catalyst, HPE Aruba |
| **Firewalls** | Security appliances | Palo Alto PA-3200, AWS WAF |
| **Load Balancers** | Traffic distribution | AWS ALB, NGINX Plus |
| **Wireless Access Points** | WiFi infrastructure | Cisco Aironet, Aruba APs |
| **VPN Gateways** | Remote access devices | Cisco ASA, AWS Client VPN |

#### 2.2.3 IoT Devices

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Milk Meters** | Production monitoring | Afimilk, DeLaval meters |
| **Environmental Sensors** | Barn conditions | Temperature, humidity sensors |
| **RFID Readers** | Cattle identification | Fixed and handheld readers |
| **Wearable Devices** | Cattle health monitoring | Pedometers, rumination sensors |
| **Cold Chain Sensors** | Temperature monitoring | Wireless temperature loggers |
| **IoT Gateways** | Data aggregation | Edge computing gateways |
| **Biogas Monitors** | Energy production sensors | Gas flow meters, analyzers |

**IoT Device CI Attributes:**
| Attribute | Description | Example |
|-----------|-------------|---------|
| CI ID | Unique identifier | IOT-MILK-001 |
| Device Type | Category | Milk Meter |
| Manufacturer | Device vendor | Afimilk |
| Model | Specific model | AfiFlow 4.0 |
| Serial Number | Device serial | MM2026001234 |
| MAC Address | Network identifier | 00:1A:2B:3C:4D:5E |
| IP Address | Network address | 10.0.100.45 |
| Firmware Version | Current firmware | v2.5.1 |
| Farm Location | Physical location | Milking Parlor #1 |
| Associated Cattle | Linked animals | Cow #1234, #1235 |
| Battery Status | Power level | 85% |
| Last Calibration | Calibration date | 2026-01-15 |
| Owner | Responsible team | Farm Operations |

#### 2.2.4 Storage Devices

| CI Type | Description | Examples |
|---------|-------------|----------|
| **SAN Storage** | Enterprise storage | Dell EMC PowerStore |
| **NAS Devices** | Network-attached storage | Synology, QNAP |
| **Backup Appliances** | Backup storage | Veeam appliances |
| **Tape Libraries** | Archive storage | IBM TS4500 |

#### 2.2.5 End-User Devices

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Laptops/Desktops** | User workstations | Dell Latitude, HP EliteBook |
| **Mobile Devices** | Smartphones/tablets | iPhone, Samsung Galaxy |
| **Printers/Scanners** | Output devices | HP LaserJet, Canon scanners |
| **Kiosks** | Self-service terminals | B2C ordering kiosks |
| **POS Terminals** | Point of sale devices | Retail POS systems |

### 2.3 Software Configuration Items

#### 2.3.1 Applications

| CI Type | Description | Examples |
|---------|-------------|----------|
| **ERP System** | Enterprise Resource Planning | Odoo 19 CE |
| **Database Systems** | Data storage software | PostgreSQL 16, TimescaleDB |
| **Web Servers** | HTTP serving | NGINX, Apache |
| **Application Servers** | App runtime | Gunicorn, Node.js |
| **Cache Systems** | In-memory storage | Redis, Memcached |
| **Message Queues** | Async messaging | RabbitMQ, AWS SQS |
| **Monitoring Tools** | System observability | Prometheus, Grafana |
| **Security Tools** | Security management | WAF, IDS/IPS systems |

#### 2.3.2 Software Components

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Microservices** | Containerized services | Order Service, Inventory Service |
| **APIs** | Application interfaces | REST APIs, GraphQL endpoints |
| **Batch Jobs** | Scheduled processes | Nightly reports, data sync |
| **Integration Scripts** | Data connectors | ETL scripts, import/export tools |

#### 2.3.3 Software Licenses

| CI Type | Description | Examples |
|---------|-------------|----------|
| **OS Licenses** | Operating system | Windows Server, RHEL |
| **Application Licenses** | Commercial software | Microsoft 365, Adobe CC |
| **Database Licenses** | Database software | Oracle, SQL Server |
| **Security Licenses** | Security products | Antivirus, DLP licenses |
| **Development Tools** | Dev software | JetBrains, Visual Studio |

**License CI Attributes:**
| Attribute | Description | Example |
|-----------|-------------|---------|
| CI ID | Unique identifier | LIC-MS365-001 |
| License Type | Category | Subscription/Perpetual |
| Vendor | Software vendor | Microsoft |
| Product Name | Licensed product | Microsoft 365 E3 |
| License Key | Activation key | XXXX-XXXX-XXXX |
| Quantity | Number of licenses | 50 users |
| Purchase Date | Acquisition date | 2026-01-15 |
| Expiry Date | License expiration | 2027-01-14 |
| Cost | Purchase cost | BDT 450,000 |
| Assignment | Allocated to | IT Department |
| Compliance Status | License position | Compliant/Over-deployed |

### 2.4 Documentation Configuration Items

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Technical Documentation** | System documentation | Architecture docs, runbooks |
| **User Manuals** | End-user guides | ERP user guide, mobile app manual |
| **Policies** | Governance documents | Security policy, data retention policy |
| **Procedures** | Operational procedures | Backup procedures, change management |
| **Contracts** | Vendor agreements | SLA contracts, support agreements |
| **Certificates** | Compliance docs | SSL certificates, ISO certifications |

**Documentation CI Attributes:**
| Attribute | Description | Example |
|-----------|-------------|---------|
| CI ID | Unique identifier | DOC-TDD-001 |
| Document Type | Category | Technical Design |
| Title | Document name | Smart Dairy TDD |
| Version | Current version | v2.1 |
| Author | Document owner | Solution Architect |
| Review Date | Last review | 2026-01-20 |
| Expiry Date | Valid until | 2026-12-31 |
| Storage Location | Document URL | SharePoint/Confluence |
| Approval Status | Current status | Approved |

### 2.5 Services Configuration Items

#### 2.5.1 IT Services

| CI Type | Description | Examples |
|---------|-------------|----------|
| **Business Applications** | Core business services | B2C E-commerce Service |
| **Infrastructure Services** | Foundation services | DNS Service, DHCP Service |
| **Platform Services** | Shared services | Kubernetes Platform |
| **Security Services** | Protection services | Authentication Service |

#### 2.5.2 Business Services

| CI Type | Description | Service Owner |
|---------|-------------|---------------|
| **Order Management Service** | End-to-end order processing | Sales Director |
| **Milk Production Service** | Farm production tracking | Farm Manager |
| **Inventory Management Service** | Stock control and warehousing | Operations Manager |
| **Customer Service** | Customer support and engagement | Customer Service Manager |
| **Financial Service** | Accounting and reporting | Finance Director |
| **HR Service** | Personnel management | HR Manager |

---

## 3. CI Attributes

### 3.1 Core CI Attributes (All CI Types)

| Attribute Name | Required | Description | Format |
|----------------|----------|-------------|--------|
| **CI ID** | Yes | Unique identifier | XXX-YYYY-ZZZ (e.g., SRV-PROD-001) |
| **Name** | Yes | Descriptive name | Free text (max 100 chars) |
| **Description** | Yes | Detailed description | Free text (max 500 chars) |
| **CI Type** | Yes | Classification | Dropdown from CI Types |
| **CI Class** | Yes | Category | Hardware/Software/Service/Doc |
| **Status** | Yes | Lifecycle status | Planned/Active/Maintenance/Retired |
| **Owner** | Yes | Responsible person/team | User/Group reference |
| **Location** | Yes | Physical/logical location | Location reference |
| **Criticality** | Yes | Business importance | Critical/High/Medium/Low |
| **Created Date** | Auto | Record creation timestamp | ISO 8601 datetime |
| **Last Updated** | Auto | Last modification timestamp | ISO 8601 datetime |
| **Updated By** | Auto | User who last modified | User reference |

### 3.2 Extended Attributes by CI Class

#### Hardware Extended Attributes

| Attribute | Description | Example Values |
|-----------|-------------|----------------|
| Asset Tag | Physical asset identifier | AST-2026-001234 |
| Manufacturer | Hardware vendor | Dell, HP, Cisco |
| Model | Product model | PowerEdge R750 |
| Serial Number | Factory serial | ABC123456789 |
| Warranty Start | Warranty begin date | 2026-01-15 |
| Warranty End | Warranty expiration | 2029-01-14 |
| Purchase Date | Acquisition date | 2026-01-10 |
| Purchase Cost | Acquisition cost | BDT 850,000 |
| Supplier | Vendor name | Smart Technologies BD |
| Technical Specifications | Hardware details | CPU, RAM, Storage specs |
| Network Interfaces | Connectivity details | eth0: 10.0.1.10 |
| Power Consumption | Wattage | 750W |
| Rack Position | Data center location | RACK-A10-U15 |

#### Software Extended Attributes

| Attribute | Description | Example Values |
|-----------|-------------|----------------|
| Version | Current version | 19.0.1 |
| Release Date | Version release | 2025-10-15 |
| Vendor | Software provider | Odoo SA |
| License Type | Licensing model | GPL-3/Commercial |
| Installation Path | Deployment location | /opt/odoo/ |
| Configuration Files | Config locations | /etc/odoo/odoo.conf |
| Dependencies | Required components | PostgreSQL, Python 3.10 |
| Database Schema | Associated DB | smart_dairy_erp |
| Backup Schedule | Backup frequency | Daily 02:00 |
| Support Contract | Support details | Vendor support until 2027 |

#### Service Extended Attributes

| Attribute | Description | Example Values |
|-----------|-------------|----------------|
| Service Level | SLA classification | Gold/Silver/Bronze |
| Availability Target | Uptime requirement | 99.9% |
| Service Hours | Operational hours | 24x7 or Business Hours |
| Service Owner | Business owner | Sales Director |
| Technical Owner | IT owner | Application Manager |
| Users/Customers | User count | 500 B2C customers |
| Recovery Time Objective | RTO | 4 hours |
| Recovery Point Objective | RPO | 1 hour |
| Cost Center | Budget allocation | CC-IT-001 |
| Chargeback Rate | Service cost | BDT 5,000/month |

### 3.3 Attribute Templates

#### Server CI Template

```yaml
CI_ID: SRV-PROD-001
Name: Production ERP Application Server
Description: Primary application server hosting Odoo ERP system
CI_Type: Virtual Server
CI_Class: Hardware
Status: Active
Owner: DevOps Team
Location: AWS ap-south-1a
Criticality: Critical

# Extended Attributes
Asset_Tag: AWS-EC2-i-0abcd1234
Manufacturer: AWS
Model: m6i.2xlarge
Serial_Number: i-0abcd1234efgh5678
Warranty_Start: 2026-01-01
Warranty_End: N/A (Cloud)
Purchase_Date: 2026-01-01
Purchase_Cost: Monthly BDT 25,000
Supplier: Amazon Web Services
Technical_Specifications:
  CPU: 8 vCPU (Intel Xeon)
  RAM: 32 GB
  Storage: 200 GB EBS SSD
Network_Interfaces:
  - eth0: 10.0.4.15 (Private)
Operating_System: Ubuntu 22.04 LTS
```

#### IoT Device CI Template

```yaml
CI_ID: IOT-MILK-001
Name: Milking Parlor 1 - Milk Meter #1
Description: Automated milk measurement device in primary milking parlor
CI_Type: Milk Meter
CI_Class: Hardware
Status: Active
Owner: Farm Operations
Location: Farm - Milking Parlor #1
Criticality: High

# Extended Attributes
Asset_Tag: IOT-2026-001
Manufacturer: Afimilk
Model: AfiFlow 4.0
Serial_Number: AFM2026BD001234
Warranty_Start: 2026-01-15
Warranty_End: 2028-01-14
Purchase_Date: 2026-01-10
Purchase_Cost: BDT 180,000
Supplier: Afimilk Israel via Local Distributor
Firmware_Version: v4.2.1
MAC_Address: 00:1A:2B:3C:4D:5E
IP_Address: 10.0.100.45
Farm_Location: Milking Parlor #1, Stall 1-5
Associated_Cattle: [COW-001, COW-002, COW-003, COW-004, COW-005]
Battery_Status: N/A (Wired)
Last_Calibration: 2026-01-25
Next_Calibration: 2026-04-25
IoT_Gateway: IOT-GW-001
```

---

## 4. CI Relationships

### 4.1 Relationship Types

| Relationship | Description | Cardinality | Example |
|--------------|-------------|-------------|---------|
| **Depends On** | CI requires another CI to function | Many-to-Many | ERP depends on Database |
| **Used By** | CI is utilized by another CI | One-to-Many | Database used by ERP |
| **Part Of** | CI is a component of another CI | Many-to-One | Disk is part of Server |
| **Runs On** | Software executes on hardware | Many-to-One | Odoo runs on Server |
| **Connected To** | Network connectivity between CIs | Many-to-Many | Switch connected to Router |
| **Contains** | Parent contains child CIs | One-to-Many | Site contains Servers |
| **Managed By** | Management relationship | Many-to-One | Server managed by Ansible |
| **Licensed To** | License assignment | One-to-Many | MS365 licensed to Users |
| **Documents** | Documentation relationship | One-to-Many | Manual documents System |
| **Supports** | Service support relationship | Many-to-Many | Network supports ERP |
| **Hosts** | Hosting relationship | One-to-Many | Kubernetes hosts Microservices |
| **Stores Data For** | Data storage relationship | One-to-Many | Database stores data for App |

### 4.2 Relationship Model

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY CI RELATIONSHIP MODEL                        │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                          INFRASTRUCTURE LAYER                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────┐         ┌─────────────┐         ┌─────────────┐           │
│  │   Router    │◀═══════▶│   Switch    │◀═══════▶│    Load     │           │
│  │   (RT-01)   │ Connected│   (SW-01)   │ Connected│  Balancer   │           │
│  └──────┬──────┘         └──────┬──────┘         └──────┬──────┘           │
│         │                        │                      │                  │
│         │ Part Of                │ Part Of              │ Part Of          │
│         ▼                        ▼                      ▼                  │
│  ┌─────────────┐         ┌─────────────┐         ┌─────────────┐           │
│  │   Network   │         │   Network   │         │   Network   │           │
│  │  Backbone   │         │   Access    │         │   DMZ       │           │
│  └─────────────┘         └─────────────┘         └─────────────┘           │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Supports
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           COMPUTE LAYER                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐  │
│  │ EKS Master  │    │ EKS Worker  │    │ EKS Worker  │    │  Database   │  │
│  │   Nodes     │    │  Node #1    │    │  Node #2    │    │  Server     │  │
│  │ (K8S-CTRL)  │    │ (K8S-W01)   │    │ (K8S-W02)   │    │ (DB-PROD)   │  │
│  └──────┬──────┘    └──────┬──────┘    └──────┬──────┘    └──────┬──────┘  │
│         │                  │                  │                  │         │
│         │ Hosts            │ Hosts            │ Hosts            │         │
│         ▼                  ▼                  ▼                  │         │
│  ┌─────────────────────────────────────────────────────┐         │         │
│  │              KUBERNETES PLATFORM                    │         │         │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐ │         │         │
│  │  │  Odoo   │  │  B2C    │  │  B2B    │  │  Farm   │ │         │         │
│  │  │   ERP   │  │  Web    │  │  Portal │  │  Mgmt   │ │         │         │
│  │  │  Pod    │  │  Pod    │  │  Pod    │  │  Pod    │ │         │         │
│  │  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘ │         │         │
│  │       │            │            │            │      │         │         │
│  │       │ Depends On │ Depends On │ Depends On │      │         │         │
│  └───────┼────────────┴────────────┴────────────┼──────┘         │         │
│          │                                      │                │         │
│          ▼                                      ▼                │         │
│  ┌─────────────────────────────────────────────────────────┐     │         │
│  │                 DATABASE LAYER                          │     │         │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐      │     │         │
│  │  │ PostgreSQL  │  │PostgreSQL  │  │   Redis     │      │     │         │
│  │  │ (Primary)   │  │ (Replica)   │  │   Cache     │      │     │         │
│  │  │  Stores     │  │  Replicates │  │  Caches     │      │     │         │
│  │  │  Data For   │  │  Data For   │  │  Data For   │      │     │         │
│  │  └─────────────┘  └─────────────┘  └─────────────┘      │     │         │
│  └─────────────────────────────────────────────────────────┘     │         │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Depends On
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           IOT LAYER                                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      IoT Gateway (IOT-GW-001)                       │   │
│  │  ┌─────────────┬─────────────┬─────────────┬─────────────────────┐  │   │
│  │  │ Milk Meter  │  Env Sensor │  RFID Reader│   Cold Chain        │  │   │
│  │  │ (IOT-001)   │ (IOT-101)   │ (IOT-201)   │   Sensor (IOT-301)  │  │   │
│  │  │   Part Of   │   Part Of   │   Part Of   │      Part Of        │  │   │
│  │  └─────────────┴─────────────┴─────────────┴─────────────────────┘  │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Supports
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        SERVICE LAYER                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    B2C E-Commerce Service                           │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌────────────┐ │   │
│  │  │  Product    │  │   Order     │  │  Payment    │  │  Delivery  │ │   │
│  │  │  Catalog    │  │ Processing  │  │ Processing  │  │  Service   │ │   │
│  │  │  Component  │  │  Component  │  │  Component  │  │ Component  │ │   │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └─────┬──────┘ │   │
│  │         │                │                │               │        │   │
│  │         └────────────────┴────────────────┴───────────────┘        │   │
│  │                            Part Of                                 │   │
│  └────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Relationship Examples

#### ERP System Relationships

| Source CI | Relationship | Target CI | Impact |
|-----------|--------------|-----------|--------|
| Odoo ERP | Runs On | EKS Worker Node #1 | Node failure affects ERP |
| Odoo ERP | Depends On | PostgreSQL Primary | DB failure affects ERP |
| Odoo ERP | Depends On | Redis Cache | Cache miss increases load |
| Odoo ERP | Uses | AWS ALB | Load balancer distributes traffic |
| Odoo ERP | Licensed To | Smart Dairy Ltd. | License compliance required |
| Odoo ERP | Documented By | DOC-K-010 | User manual available |
| Odoo ERP | Managed By | DevOps Team | Team responsible for ops |

#### IoT Device Relationships

| Source CI | Relationship | Target CI | Impact |
|-----------|--------------|-----------|--------|
| Milk Meter IOT-001 | Part Of | IoT Gateway GW-001 | Gateway failure affects meter |
| Milk Meter IOT-001 | Connected To | Farm Network | Network required for data |
| Milk Meter IOT-001 | Monitors | Cow #1234 | Data linked to specific animal |
| Milk Meter IOT-001 | Stores Data In | TimescaleDB | Time-series data storage |
| IoT Gateway GW-001 | Depends On | MQTT Broker | Broker required for messaging |

---

## 5. CI Lifecycle

### 5.1 Lifecycle States

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         CI LIFECYCLE STATE DIAGRAM                          │
└─────────────────────────────────────────────────────────────────────────────┘

                              ┌───────────┐
                              │           │
                    ┌────────▶│  PLANNED  │◀────────┐
                    │         │           │         │
                    │         └─────┬─────┘         │
                    │               │               │
         ┌──────────┴─────────┐     │     ┌─────────┴──────────┐
         │  Procurement       │     │     │  Decommissioning   │
         │  Approval          │     │     │  Request           │
         └──────────┬─────────┘     │     └─────────┬──────────┘
                    │               │               │
                    │               ▼               │
                    │         ┌───────────┐         │
                    │         │           │         │
                    │         │  ACTIVE   │         │
                    │         │           │         │
                    │         └─────┬─────┘         │
                    │               │               │
                    │               │               │
         ┌──────────┴─────────┐     │     ┌─────────┴──────────┐
         │  Deployment        │     │     │  Retirement        │
         │  Complete          │     │     │  Approved          │
         └────────────────────┘     │     └────────────────────┘
                                    │
                                    ▼
                              ┌───────────┐
                              │           │
                    ┌────────▶│MAINTENANCE│◀────────┐
                    │         │           │         │
                    │         └─────┬─────┘         │
                    │               │               │
         ┌──────────┴─────────┐     │     ┌─────────┴──────────┐
         │  Maintenance       │     │     │  Maintenance       │
         │  Window Start      │     │     │  Complete          │
         └────────────────────┘     │     └────────────────────┘
                                    │
                                    ▼
                              ┌───────────┐
                              │           │
                              │  RETIRED  │
                              │           │
                              └───────────┘
                                    │
                                    ▼
                              ┌───────────┐
                              │  ARCHIVED │
                              │  (CMDB)   │
                              └───────────┘
```

### 5.2 State Definitions

| State | Description | Entry Criteria | Exit Criteria |
|-------|-------------|----------------|---------------|
| **Planned** | CI is approved for acquisition/procurement | Budget approved, PO raised | Physical receipt or deployment ready |
| **Active** | CI is operational and in production | Successfully deployed, tested | Decommissioning requested or failure |
| **Maintenance** | CI is temporarily unavailable | Scheduled maintenance starts | Maintenance completed successfully |
| **Retired** | CI is no longer in production use | Decommissioning approved, data migrated | Archival record created |
| **Archived** | CI record kept for historical reference | 90 days after retirement | Per retention policy (7 years) |

### 5.3 State Transitions

| From State | To State | Trigger | Authorized Role |
|------------|----------|---------|-----------------|
| Planned | Active | Deployment verification | Configuration Manager |
| Active | Maintenance | Maintenance RFC approved | Change Manager |
| Maintenance | Active | Maintenance completion | Configuration Manager |
| Maintenance | Retired | Unrecoverable failure | IT Director |
| Active | Retired | Decommissioning RFC approved | Configuration Manager |
| Retired | Archived | 90-day retention period | Automated |

### 5.4 Lifecycle Management Procedures

#### New CI Onboarding

1. **Request**: Submit CI Registration Request via GLPI
2. **Review**: Configuration Manager validates request
3. **Classification**: Assign CI Type and Class
4. **Identification**: Generate unique CI ID
5. **Data Collection**: Gather all required attributes
6. **Relationship Mapping**: Identify and document relationships
7. **Verification**: Physical/logical verification of CI
8. **Registration**: Add CI to CMDB with "Planned" status
9. **Deployment**: Deploy CI to production
10. **Activation**: Update status to "Active"

#### CI Decommissioning

1. **Request**: Submit Decommissioning Request
2. **Impact Assessment**: Review relationships and dependencies
3. **RFC Creation**: Create Change Request for removal
4. **Approval**: Obtain CAB approval (if required)
5. **Data Backup**: Ensure data retention requirements met
6. **Dependency Update**: Update/remove related CI relationships
7. **Physical Removal**: Remove hardware/decommission software
8. **Status Update**: Change CI status to "Retired"
9. **Final Review**: 90-day retention before archival
10. **Archival**: Move to archived status

---

## 6. CMDB Structure

### 6.1 CMDB Hierarchy

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY CMDB HIERARCHY                           │
└─────────────────────────────────────────────────────────────────────────────┘

SMART DAIRY CMDB
│
├── HARDWARE
│   ├── SERVERS
│   │   ├── Physical Servers
│   │   │   ├── Dell PowerEdge R750 (SRV-PHY-001)
│   │   │   └── HPE ProLiant DL380 (SRV-PHY-002)
│   │   ├── Virtual Servers
│   │   │   ├── EKS Worker Node #1 (SRV-VM-001)
│   │   │   └── EKS Worker Node #2 (SRV-VM-002)
│   │   └── Container Hosts
│   │       └── EKS Node Group (SRV-K8S-001)
│   │
│   ├── NETWORK
│   │   ├── Routers
│   │   │   └── AWS Transit Gateway (NET-RT-001)
│   │   ├── Switches
│   │   │   └── VPC Networking (NET-SW-001)
│   │   ├── Firewalls
│   │   │   ├── AWS Security Groups (NET-FW-001)
│   │   │   └── AWS WAF (NET-FW-002)
│   │   └── Load Balancers
│   │       └── AWS ALB Production (NET-LB-001)
│   │
│   ├── STORAGE
│   │   ├── SAN/NAS
│   │   │   └── AWS EBS Volumes (STR-SAN-001)
│   │   ├── Object Storage
│   │   │   ├── S3 File Storage (STR-S3-001)
│   │   │   └── S3 Backup Storage (STR-S3-002)
│   │   └── Backup Systems
│   │       └── AWS Backup (STR-BKP-001)
│   │
│   ├── IOT DEVICES
│   │   ├── Milk Meters
│   │   │   ├── Milking Parlor #1 - MM1 (IOT-MILK-001)
│   │   │   └── Milking Parlor #1 - MM2 (IOT-MILK-002)
│   │   ├── Environmental Sensors
│   │   │   ├── Barn Temp Sensor #1 (IOT-ENV-001)
│   │   │   └── Barn Humidity Sensor #1 (IOT-ENV-002)
│   │   ├── RFID Readers
│   │   │   └── Parlor Entrance Reader (IOT-RFID-001)
│   │   └── IoT Gateways
│   │       └── Farm Gateway #1 (IOT-GW-001)
│   │
│   └── END USER DEVICES
│       ├── Laptops
│       ├── Desktops
│       ├── Mobile Devices
│       └── Printers
│
├── SOFTWARE
│   ├── APPLICATIONS
│   │   ├── ERP System
│   │   │   └── Odoo 19 CE (APP-ERP-001)
│   │   ├── Database Systems
│   │   │   ├── PostgreSQL 16 (APP-DB-001)
│   │   │   ├── TimescaleDB (APP-DB-002)
│   │   │   └── Redis (APP-DB-003)
│   │   ├── Web Servers
│   │   │   └── NGINX (APP-WEB-001)
│   │   └── Monitoring
│   │       ├── Prometheus (APP-MON-001)
│   │       └── Grafana (APP-MON-002)
│   │
│   ├── MICROSERVICES
│   │   ├── B2C Order Service (MS-B2C-001)
│   │   ├── Inventory Service (MS-INV-001)
│   │   ├── Payment Service (MS-PAY-001)
│   │   └── Notification Service (MS-NOT-001)
│   │
│   └── LICENSES
│       ├── Microsoft 365 (LIC-MS-001)
│       ├── Adobe Creative Cloud (LIC-ADOBE-001)
│       └── JetBrains Toolbox (LIC-JB-001)
│
├── SERVICES
│   ├── BUSINESS SERVICES
│   │   ├── B2C E-Commerce (SVC-BUS-001)
│   │   ├── B2B Marketplace (SVC-BUS-002)
│   │   ├── Farm Management (SVC-BUS-003)
│   │   └── ERP Service (SVC-BUS-004)
│   │
│   ├── INFRASTRUCTURE SERVICES
│   │   ├── DNS Service (SVC-INF-001)
│   │   ├── Certificate Management (SVC-INF-002)
│   │   ├── Backup Service (SVC-INF-003)
│   │   └── Monitoring Service (SVC-INF-004)
│   │
│   └── PLATFORM SERVICES
│       ├── Kubernetes Platform (SVC-PLT-001)
│       ├── API Gateway (SVC-PLT-002)
│       └── Identity Management (SVC-PLT-003)
│
└── DOCUMENTATION
    ├── TECHNICAL DOCUMENTS
    │   ├── Architecture Documents
    │   ├── Runbooks
    │   └── API Documentation
    ├── USER DOCUMENTS
    │   ├── User Manuals
    │   └── Quick Reference Guides
    └── GOVERNANCE DOCUMENTS
        ├── Policies
        ├── Procedures
        └── Contracts
```

### 6.2 CI Categories and Classes

| Category | Class | Description | Count Target |
|----------|-------|-------------|--------------|
| **Infrastructure** | Server | Compute resources | 50+ |
| **Infrastructure** | Network | Network equipment | 30+ |
| **Infrastructure** | Storage | Storage systems | 10+ |
| **Infrastructure** | IoT | Farm IoT devices | 100+ |
| **Software** | Application | Business applications | 20+ |
| **Software** | Component | Software components | 50+ |
| **Software** | License | Software licenses | 40+ |
| **Service** | Business | Business services | 15+ |
| **Service** | Technical | Infrastructure services | 25+ |
| **Documentation** | Technical | Technical docs | 100+ |

### 6.3 Naming Convention

| CI Class | Naming Pattern | Example |
|----------|----------------|---------|
| Physical Server | SRV-PHY-NNN | SRV-PHY-001 |
| Virtual Server | SRV-VM-NNN | SRV-VM-001 |
| Container Host | SRV-K8S-NNN | SRV-K8S-001 |
| Network Device | NET-TYPE-NNN | NET-RT-001 |
| IoT Device | IOT-TYPE-NNN | IOT-MILK-001 |
| Application | APP-TYPE-NNN | APP-ERP-001 |
| Microservice | MS-TYPE-NNN | MS-B2C-001 |
| License | LIC-VENDOR-NNN | LIC-MS-001 |
| Service | SVC-TYPE-NNN | SVC-BUS-001 |
| Document | DOC-TYPE-NNN | DOC-TDD-001 |

---

## 7. Discovery and Population

### 7.1 Discovery Methods

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CMDB DISCOVERY ARCHITECTURE                              │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                          DATA SOURCES                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │ OCS Inventory│  │   AWS APIs  │  │   SNMP      │  │   SSH/WMI   │        │
│  │   Agents    │  │  (EC2, EKS) │  │  Scanning   │  │   Scripts   │        │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘        │
│         │                │                │                │               │
│         └────────────────┴────────────────┴────────────────┘               │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    GLPI FUSIONINVENTORY PLUGIN                      │   │
│  │                    (Discovery & Inventory Engine)                   │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                         GLPI CMDB                                   │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌────────────┐ │   │
│  │  │  Computers  │  │  Network    │  │   Printers  │  │  Software  │ │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └────────────┘ │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Discovery Tools

| Tool | Purpose | Coverage |
|------|---------|----------|
| **OCS Inventory NG** | Automated hardware/software inventory | Servers, workstations |
| **FusionInventory** | GLPI integration agent | All managed devices |
| **AWS Config** | Cloud resource tracking | AWS infrastructure |
| **AWS Systems Manager** | Instance inventory | EC2 instances |
| **SNMP Scanning** | Network device discovery | Switches, routers, printers |
| **Nmap** | Network port scanning | IP-based discovery |
| **IoT Gateway APIs** | IoT device discovery | Farm sensors and meters |

### 7.3 Discovery Procedures

#### Server Discovery (OCS Inventory)

```bash
# Install OCS Inventory Agent on Linux servers
wget https://github.com/OCSInventory-NG/UnixAgent/releases/download/v2.10.0/Ocsinventory-Unix-Agent-2.10.0.tar.gz
tar -xzf Ocsinventory-Unix-Agent-2.10.0.tar.gz
cd Ocsinventory-Unix-Agent-2.10.0
sudo perl Makefile.PL
sudo make
sudo make install

# Configure agent
sudo vi /etc/ocsinventory/ocsinventory-agent.cfg
# Set server URL: https://glpi.smartdairy.com/ocsinventory

# Run initial inventory
sudo ocsinventory-agent --server=https://glpi.smartdairy.com/ocsinventory
```

#### AWS Resource Discovery

```bash
# Discover EC2 instances
aws ec2 describe-instances \
    --query 'Reservations[*].Instances[*].[InstanceId,InstanceType,State.Name,Tags[?Key==`Name`].Value|[0]]' \
    --output table

# Discover EKS clusters
aws eks list-clusters --query 'clusters' --output table

# Discover RDS instances
aws rds describe-db-instances \
    --query 'DBInstances[*].[DBInstanceIdentifier,DBInstanceClass,Engine,DBInstanceStatus]' \
    --output table

# Discover Load Balancers
aws elbv2 describe-load-balancers \
    --query 'LoadBalancers[*].[LoadBalancerName,Type,State.Code]' \
    --output table
```

#### IoT Device Discovery

```python
# MQTT-based IoT device discovery script
import paho.mqtt.client as mqtt
import json

def on_connect(client, userdata, flags, rc):
    client.subscribe("farm/devices/+/status")

def on_message(client, userdata, msg):
    device_data = json.loads(msg.payload)
    # Process device discovery data
    register_iot_ci(device_data)

client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message
client.connect("iot.smartdairy.com", 1883, 60)
client.loop_forever()
```

### 7.4 Discovery Schedule

| Discovery Type | Frequency | Scope | Tool |
|----------------|-----------|-------|------|
| Full Network Scan | Weekly | All IP ranges | Nmap + SNMP |
| Server Inventory | Daily | All servers | OCS Agent |
| AWS Resource Sync | Hourly | Cloud resources | AWS Config |
| IoT Device Scan | Every 15 min | Farm devices | MQTT Discovery |
| Software Inventory | Daily | Installed software | OCS Agent |
| Manual Verification | Quarterly | Critical CIs | CMDB Audit |

### 7.5 Population Workflow

1. **Discovery**: Automated tools scan and collect CI data
2. **Normalization**: Data standardized to CMDB schema
3. **Deduplication**: Identify and merge duplicate records
4. **Reconciliation**: Match discovered data with existing CIs
5. **Validation**: Configuration Manager reviews new CIs
6. **Classification**: Assign CI Type and Class
7. **Enrichment**: Add manual attributes and relationships
8. **Approval**: Final approval for CMDB entry
9. **Population**: Add to CMDB with appropriate status

---

## 8. Maintenance Procedures

### 8.1 Regular Maintenance Activities

| Activity | Frequency | Responsible | Duration |
|----------|-----------|-------------|----------|
| **Discovery Verification** | Weekly | CMDB Administrator | 4 hours |
| **Relationship Review** | Monthly | Configuration Manager | 8 hours |
| **Data Quality Audit** | Quarterly | CMDB Administrator | 16 hours |
| **Full CMDB Reconciliation** | Bi-annually | Configuration Manager | 40 hours |
| **Retention Cleanup** | Annually | CMDB Administrator | 8 hours |
| **License Compliance Check** | Monthly | License Manager | 4 hours |

### 8.2 Data Quality Standards

| Quality Metric | Target | Measurement |
|----------------|--------|-------------|
| **Completeness** | > 95% | Required attributes populated |
| **Accuracy** | > 98% | Verified against source |
| **Timeliness** | < 24 hours | Updates reflected in CMDB |
| **Consistency** | 100% | Naming conventions followed |
| **Uniqueness** | 100% | No duplicate CIs |

### 8.3 Data Maintenance Procedures

#### CI Update Process

1. **Change Detection**: Identify CI changes through discovery or manual input
2. **RFC Reference**: Link to approved Change Request (if applicable)
3. **Update Request**: Submit CMDB Update Request in GLPI
4. **Validation**: Verify change legitimacy
5. **Data Update**: Modify CI attributes in GLPI
6. **Relationship Update**: Update affected relationships
7. **Verification**: Confirm changes are accurate
8. **Audit Log**: Record change in CMDB history

#### Bulk Updates

For mass updates (e.g., ownership changes, location moves):

1. **Justification**: Document reason for bulk update
2. **Impact Analysis**: Review affected relationships
3. **Staging**: Prepare changes in test environment
4. **Approval**: Obtain Configuration Manager approval
5. **Backup**: Export current state before changes
6. **Execution**: Perform bulk update
7. **Verification**: Spot-check updated records
8. **Rollback Plan**: Maintain ability to revert

### 8.4 Reconciliation Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        RECONCILIATION WORKFLOW                              │
└─────────────────────────────────────────────────────────────────────────────┘

[START]
   │
   ▼
┌──────────────────────┐
│ 1. IDENTIFY GAPS     │
│ • Discovery vs CMDB  │
│ • Missing CIs        │
│ • Orphaned CIs       │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 2. CLASSIFY GAPS     │
│ • New CIs to add     │
│ • CIs to update      │
│ • CIs to retire      │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 3. VERIFY PHYSICAL   │
│ • Physical audit     │
│ • Network scan       │
│ • Documentation      │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 4. RESOLVE DISCREPS  │
│ • Add missing CIs    │
│ • Update attributes  │
│ • Retire stale CIs   │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 5. UPDATE CMDB       │
│ • Apply changes      │
│ • Update relationships│
│ • Document actions   │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ 6. REPORT RESULTS    │
│ • Metrics update     │
│ • Exception report   │
│ • Process improvement│
└──────────┬───────────┘
           │
           ▼
         [END]
```

---

## 9. Access Control

### 9.1 CMDB Access Roles

| Role | Permissions | Users |
|------|-------------|-------|
| **CMDB Administrator** | Full CRUD access, configuration | Configuration Manager |
| **Configuration Manager** | Full CRUD access, reporting | IT Service Manager |
| **CI Owner** | Update owned CIs, view related | Team Leads |
| **Change Manager** | Update CI status, view all | Change Manager |
| **Incident Manager** | View all, update during incidents | Incident Manager |
| **Service Desk** | View all, create new CIs | Service Desk Agents |
| **Read-Only User** | View access only | Auditors, Management |

### 9.2 Access Control Matrix

| Function | Admin | Config Mgr | CI Owner | Change Mgr | Incident Mgr | Service Desk | Read-Only |
|----------|:-----:|:----------:|:--------:|:----------:|:------------:|:------------:|:---------:|
| View CIs | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Create CIs | ✓ | ✓ | ✓ | ✓ | - | ✓ | - |
| Update CIs | ✓ | ✓ | Own* | Status | During Incident | Limited | - |
| Delete CIs | ✓ | - | - | - | - | - | - |
| Manage Relationships | ✓ | ✓ | Own* | ✓ | ✓ | - | - |
| Run Reports | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Configure CMDB | ✓ | - | - | - | - | - | - |
| Export Data | ✓ | ✓ | - | - | - | - | - |
| Import Data | ✓ | ✓ | - | - | - | - | - |

*Own = CIs owned by user's team

### 9.3 Authentication and Authorization

- **Authentication**: SSO integration with Azure AD
- **Authorization**: Role-based access control (RBAC) in GLPI
- **Audit Trail**: All changes logged with user, timestamp, and old/new values
- **Session Management**: 8-hour session timeout

---

## 10. Auditing and Reconciliation

### 10.1 Audit Schedule

| Audit Type | Frequency | Scope | Owner |
|------------|-----------|-------|-------|
| **Compliance Audit** | Annual | All CIs against financial records | Internal Audit |
| **Data Quality Audit** | Quarterly | 10% sample of CIs | Configuration Manager |
| **Security Audit** | Bi-annual | Access logs, privileged actions | Security Team |
| **Process Audit** | Bi-annual | CMDB procedures adherence | IT Director |
| **IoT Device Audit** | Monthly | Farm IoT devices inventory | Farm IT |

### 10.2 Audit Checklist

#### Data Quality Audit

- [ ] Verify all active CIs have required attributes populated
- [ ] Check for duplicate CIs
- [ ] Validate relationship accuracy
- [ ] Verify CI ownership is current
- [ ] Check status accuracy against actual state
- [ ] Validate naming convention compliance
- [ ] Review orphaned CIs (no relationships)
- [ ] Verify license compliance data

#### Compliance Audit

- [ ] Compare CMDB assets to financial asset register
- [ ] Verify procurement documentation for high-value CIs
- [ ] Check warranty and support contract coverage
- [ ] Validate software license compliance
- [ ] Review disposal documentation for retired assets

### 10.3 Reconciliation Reports

| Report | Frequency | Distribution |
|--------|-----------|--------------|
| **CMDB Health Dashboard** | Weekly | IT Management |
| **Unverified CIs Report** | Monthly | CI Owners |
| **Orphaned CIs Report** | Monthly | Configuration Manager |
| **License Compliance Report** | Monthly | License Manager |
| **Full CMDB Export** | Quarterly | IT Director |

---

## 11. Integration with Other Processes

### 11.1 Process Integration Map

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CMDB PROCESS INTEGRATION MAP                             │
└─────────────────────────────────────────────────────────────────────────────┘

                              ┌─────────────┐
                              │    CMDB     │
                              │   (GLPI)    │
                              └──────┬──────┘
                                     │
        ┌────────────────────────────┼────────────────────────────┐
        │              │             │             │              │
        ▼              ▼             ▼             ▼              ▼
┌──────────────┐ ┌──────────────┐ ┌──────────┐ ┌──────────┐ ┌──────────────┐
│   CHANGE     │ │  INCIDENT    │ │ PROBLEM  │ │ RELEASE  │ │   SERVICE    │
│ MANAGEMENT   │ │ MANAGEMENT   │ │MANAGEMENT│ │MANAGEMENT│ │   LEVEL      │
└──────┬───────┘ └──────┬───────┘ └────┬─────┘ └────┬─────┘ │ MANAGEMENT   │
       │                │              │            │       └──────────────┘
       │                │              │            │
       ▼                ▼              ▼            ▼
┌──────────────┐ ┌──────────────┐ ┌──────────┐ ┌──────────┐
│ Impact       │ │ Configuration│ │ Known    │ │ CI Baseline│
│ Analysis     │ │ Item Data    │ │ Error DB │ │ for Rollback│
└──────────────┘ └──────────────┘ └──────────┘ └──────────┘

INPUTS TO CMDB:
═══════════════
• Change Management: CI updates from RFCs
• Incident Management: CI status changes during incidents
• Problem Management: Known errors linked to CIs
• Release Management: New CIs from deployments
• Service Level Management: Service definitions and SLAs

OUTPUTS FROM CMDB:
══════════════════
• Impact Analysis for Changes and Incidents
• Asset data for Financial Management
• Configuration data for Availability Management
• CI information for Capacity Management
• Relationship data for Service Continuity
```

### 11.2 Integration Details

#### Change Management Integration

| Integration Point | Data Flow | Trigger |
|-------------------|-----------|---------|
| RFC Impact Analysis | CMDB → Change Mgmt | RFC submission |
| CI Updates | Change Mgmt → CMDB | Change implementation |
| Scheduled Changes | Change Mgmt → CMDB | Maintenance window |
| Rollback Baseline | CMDB → Change Mgmt | Pre-change capture |

#### Incident Management Integration

| Integration Point | Data Flow | Trigger |
|-------------------|-----------|---------|
| Affected CI Lookup | CMDB → Incident Mgmt | Incident logging |
| Related CIs Impact | CMDB → Incident Mgmt | Incident diagnosis |
| Configuration Changes | Incident Mgmt → CMDB | Workaround implementation |
| Known Error Association | CMDB ↔ Incident Mgmt | Problem identification |

#### Asset Management Integration

| Integration Point | Data Flow | Trigger |
|-------------------|-----------|---------|
| Financial Data | Asset Mgmt → CMDB | New asset procurement |
| Depreciation Info | Asset Mgmt → CMDB | Monthly update |
| Disposal Records | Asset Mgmt → CMDB | Asset retirement |
| Procurement Status | CMDB → Asset Mgmt | CI status changes |

### 11.3 Automated Integration Points

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    AUTOMATED INTEGRATION WORKFLOWS                          │
└─────────────────────────────────────────────────────────────────────────────┘

1. CHANGE-TRIGGERED CI UPDATE
═══════════════════════════════════
[Change Approved] → [Deploy to Production] → [Auto-discover] → [Update CMDB]
                                                          
2. INCIDENT CI STATUS UPDATE
═══════════════════════════════════
[Incident Created] → [Select Affected CI] → [Auto-update Status: Maintenance]
                                              ↓
[Incident Resolved] ← [Auto-restore Status] ← [Resolution]

3. AWS RESOURCE SYNC
═══════════════════════════════════
[AWS Config Change] → [Lambda Function] → [GLPI API Call] → [Update CI]

4. IOT DEVICE AUTO-REGISTRATION
═══════════════════════════════════
[New Device Connected] → [MQTT Message] → [Auto-create CI] → [Pending Review]
```

---

## 12. Tools (GLPI/OCS Inventory)

### 12.1 Tool Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY CMDB TOOL ARCHITECTURE                       │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                              GLPI v10.x                                     │
│                   (Configuration Management System)                         │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │   Assets    │  │   Tickets   │  │  Problems   │  │   Changes   │        │
│  │  (CMDB)     │  │ (Incidents) │  │             │  │             │        │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘        │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │    Items    │  │  Management │  │  Financial  │  │   Reports   │        │
│  │  (CIs)      │  │             │  │  Info       │  │             │        │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘        │
└─────────────────────────────────────────────────────────────────────────────┘
          │                                              │
          │ REST API                                     │ FusionInventory Plugin
          │                                              │
          ▼                                              ▼
┌─────────────────────┐                      ┌─────────────────────┐
│   Custom Dashboard  │                      │  OCS Inventory NG   │
│   & Reports         │                      │  (Discovery Engine) │
└─────────────────────┘                      └──────────┬──────────┘
                                                        │
          ┌─────────────────────────────────────────────┼─────────────┐
          │                    │                        │             │
          ▼                    ▼                        ▼             ▼
   ┌─────────────┐     ┌─────────────┐         ┌─────────────┐ ┌─────────────┐
   │ OCS Agents  │     │ AWS Config  │         │    SNMP     │ │  IoT Gateway│
   │ (Servers)   │     │ Integration │         │   Scanner   │ │    APIs     │
   └─────────────┘     └─────────────┘         └─────────────┘ └─────────────┘
```

### 12.2 GLPI Configuration

#### CI Classes Configuration

```php
// GLPI CI Classes for Smart Dairy
$CFG_GLPI['ci_classes'] = [
    'Computer' => ['name' => 'Servers', 'icon' => 'fas fa-server'],
    'NetworkEquipment' => ['name' => 'Network Equipment', 'icon' => 'fas fa-network-wired'],
    'Peripheral' => ['name' => 'IoT Devices', 'icon' => 'fas fa-microchip'],
    'Monitor' => ['name' => 'Sensors', 'icon' => 'fas fa-thermometer-half'],
    'Printer' => ['name' => 'Printers', 'icon' => 'fas fa-print'],
    'Software' => ['name' => 'Software', 'icon' => 'fas fa-code'],
    'License' => ['name' => 'Licenses', 'icon' => 'fas fa-key'],
    'Document' => ['name' => 'Documentation', 'icon' => 'fas fa-file-alt'],
    'Service' => ['name' => 'Services', 'icon' => 'fas fa-concierge-bell'],
];
```

#### Custom Fields for IoT Devices

| Field Name | Type | Description |
|------------|------|-------------|
| Device Type | Dropdown | Milk Meter, Env Sensor, RFID, etc. |
| MAC Address | Text | Network identifier |
| Firmware Version | Text | Current firmware |
| Farm Location | Dropdown | Barn, Parlor, etc. |
| Associated Cattle | Text | Linked animal IDs |
| Battery Status | Number | Battery percentage |
| Last Calibration | Date | Calibration date |
| Next Calibration | Date | Due calibration |

### 12.3 OCS Inventory Configuration

#### Agent Deployment

```bash
# Deploy OCS Agent to all servers via Ansible
# playbook-deploy-ocs-agent.yml

- name: Deploy OCS Inventory Agent
  hosts: all_servers
  become: yes
  tasks:
    - name: Download OCS Agent
      get_url:
        url: "https://github.com/OCSInventory-NG/UnixAgent/releases/download/v2.10.0/Ocsinventory-Unix-Agent-2.10.0.tar.gz"
        dest: /tmp/ocs-agent.tar.gz

    - name: Install dependencies
      package:
        name:
          - perl
          - libxml-simple-perl
          - libcompress-zlib-perl
        state: present

    - name: Extract and install agent
      unarchive:
        src: /tmp/ocs-agent.tar.gz
        dest: /tmp/
        remote_src: yes

    - name: Configure agent
      template:
        src: ocsinventory-agent.cfg.j2
        dest: /etc/ocsinventory/ocsinventory-agent.cfg
      vars:
        ocs_server: "https://glpi.smartdairy.com/ocsinventory"

    - name: Run initial inventory
      command: ocsinventory-agent
```

### 12.4 Integration Scripts

#### AWS to GLPI Sync Script

```python
#!/usr/bin/env python3
"""
AWS Resource to GLPI CMDB Synchronization
Syncs EC2, RDS, and other AWS resources to GLPI
"""

import boto3
import requests
import json
from datetime import datetime

class AWSGLPISync:
    def __init__(self, glpi_url, glpi_token):
        self.glpi_url = glpi_url
        self.headers = {
            'Authorization': f'user_token {glpi_token}',
            'Content-Type': 'application/json'
        }
        self.ec2 = boto3.client('ec2')
        self.rds = boto3.client('rds')
    
    def sync_ec2_instances(self):
        """Sync EC2 instances to GLPI Computers"""
        instances = self.ec2.describe_instances()
        
        for reservation in instances['Reservations']:
            for instance in reservation['Instances']:
                ci_data = {
                    'name': self.get_instance_name(instance),
                    'ci_id': f"SRV-VM-{instance['InstanceId'][-6:]}",
                    'serial': instance['InstanceId'],
                    'uuid': instance.get('InstanceId'),
                    'comment': f"AWS EC2 {instance['InstanceType']}",
                    'states_id': 1,  # Active
                    'manufacturers_id': 18,  # AWS
                    'computertypes_id': 2,  # Virtual Server
                }
                self.create_or_update_ci(ci_data)
    
    def sync_rds_instances(self):
        """Sync RDS instances to GLPI Databases"""
        databases = self.rds.describe_db_instances()
        
        for db in databases['DBInstances']:
            ci_data = {
                'name': db['DBInstanceIdentifier'],
                'ci_id': f"DB-PROD-{db['DBInstanceIdentifier']}",
                'comment': f"{db['Engine']} {db['EngineVersion']}",
                'states_id': 1,
            }
            self.create_or_update_ci(ci_data)
    
    def create_or_update_ci(self, ci_data):
        """Create or update CI in GLPI"""
        # Check if CI exists
        search = requests.get(
            f"{self.glpi_url}/Computer",
            headers=self.headers,
            params={'searchText[name]': ci_data['name']}
        )
        
        if search.json() and len(search.json()) > 0:
            # Update existing
            ci_id = search.json()[0]['id']
            response = requests.put(
                f"{self.glpi_url}/Computer/{ci_id}",
                headers=self.headers,
                json=ci_data
            )
        else:
            # Create new
            response = requests.post(
                f"{self.glpi_url}/Computer",
                headers=self.headers,
                json=ci_data
            )
        
        return response.json()

if __name__ == '__main__':
    sync = AWSGLPISync(
        glpi_url='https://glpi.smartdairy.com/apirest.php',
        glpi_token='YOUR_GLPI_API_TOKEN'
    )
    sync.sync_ec2_instances()
    sync.sync_rds_instances()
```

---

## 13. Appendices

### Appendix A: CI Registration Form Template

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              CONFIGURATION ITEM REGISTRATION FORM                           │
│                        L-006-F-001                                          │
└─────────────────────────────────────────────────────────────────────────────┘

Section 1: Basic Information
─────────────────────────────
Request ID: ___________________
Request Date: ___________________
Requested By: ___________________
Department: ___________________

Section 2: CI Classification
─────────────────────────────
CI Class: □ Hardware  □ Software  □ Service  □ Documentation
CI Type: ___________________
CI Category: ___________________

Section 3: CI Details
─────────────────────
CI Name: ___________________
Description: _____________________________________________________________
_________________________________________________________________________

Manufacturer/Vendor: ___________________
Model/Version: ___________________
Serial Number/License Key: ___________________
Asset Tag: ___________________

Section 4: Location and Ownership
──────────────────────────────────
Physical Location: ___________________
Datacenter/Rack: ___________________
Owner/Team: ___________________
Technical Contact: ___________________

Section 5: Financial Information
─────────────────────────────────
Purchase Date: ___________________
Purchase Cost: ___________________
Supplier: ___________________
Warranty Expiry: ___________________

Section 6: Relationships
────────────────────────
Depends On: ___________________
Runs On: ___________________
Connected To: ___________________
Part Of: ___________________

Section 7: Approval
───────────────────
Requested By: _________________ Date: _______
Technical Review: _________________ Date: _______
Configuration Manager: _________________ Date: _______

Generated CI ID: ___________________
```

### Appendix B: CI Attribute Templates

#### Server CI Template (YAML)

```yaml
ci_id: SRV-PROD-001
name: Production ERP Application Server
class: Hardware
type: Virtual Server
status: Active
criticality: Critical

# Identification
asset_tag: AWS-EC2-i-0abcd1234
manufacturer: AWS
model: m6i.2xlarge
serial_number: i-0abcd1234efgh5678

# Technical Specifications
specifications:
  cpu: 8 vCPU (Intel Xeon Platinum)
  memory: 32 GB DDR4
  storage:
    - type: EBS SSD
      size: 200 GB
      iops: 3000
  network:
    - interface: eth0
      ip_address: 10.0.4.15
      subnet: private-app-1a
      security_group: sg-erp-prod

# Software
operating_system:
  name: Ubuntu
  version: 22.04 LTS
  kernel: 5.15.0

installed_software:
  - name: Docker
    version: 24.0.0
  - name: Kubernetes
    version: 1.28.0

# Location
location:
  type: Cloud
  provider: AWS
  region: ap-south-1
  availability_zone: ap-south-1a
  vpc: vpc-smartdairy-prod

# Ownership
owner: DevOps Team
technical_contact: devops-lead@smartdairy.com
business_owner: IT Director

# Financial
procurement:
  purchase_date: 2026-01-01
  purchase_cost: Monthly BDT 25,000
  supplier: Amazon Web Services
  contract_id: AWS-ENTERPRISE-2026

# Lifecycle
lifecycle:
  status: Active
  deployment_date: 2026-01-15
  review_date: 2026-07-15
  retirement_date: null

# Relationships
relationships:
  depends_on:
    - ci_id: DB-PROD-001
      type: Database
  runs_on:
    - ci_id: SRV-HOST-001
      type: Hypervisor
  connected_to:
    - ci_id: NET-LB-001
      type: Load Balancer

# Monitoring
monitoring:
  enabled: true
  tools:
    - Prometheus
    - CloudWatch
  alert_groups:
    - production-critical

# Backup
backup:
  enabled: true
  schedule: Daily 02:00
  retention: 30 days
  target: S3 Backup Bucket

# Documentation
documentation:
  runbook: https://wiki.smartdairy.com/runbooks/erp-server
  architecture: DOC-ARCH-001
```

### Appendix C: Relationship Map Templates

#### Service to Component Mapping

```
B2C E-Commerce Service (SVC-BUS-001)
│
├── Components:
│   ├── B2C Web Application (APP-WEB-001)
│   ├── Payment Gateway Integration (APP-PAY-001)
│   ├── Notification Service (MS-NOT-001)
│   └── Order Processing Service (MS-ORD-001)
│
├── Infrastructure:
│   ├── EKS Cluster (SRV-K8S-001)
│   ├── Application Load Balancer (NET-LB-001)
│   ├── PostgreSQL Database (DB-PROD-001)
│   └── Redis Cache (APP-DB-003)
│
├── Dependencies:
│   ├── bKash API (EXT-BKASH-001)
│   ├── Nagad API (EXT-NAGAD-001)
│   └── SMS Gateway (EXT-SMS-001)
│
└── Supports:
    ├── SSL Certificate (SEC-SSL-001)
    └── WAF Rules (SEC-WAF-001)
```

#### Infrastructure Dependency Chain

```
Internet Users
│
▼
CloudFront CDN
│
▼
Route 53 DNS
│
▼
AWS ALB (NET-LB-001)
│
▼
EKS Cluster (SRV-K8S-001)
├── Worker Node #1 (SRV-VM-001)
│   ├── Odoo ERP Pod
│   └── B2C Web Pod
├── Worker Node #2 (SRV-VM-002)
│   ├── B2B Portal Pod
│   └── Farm Mgmt Pod
└── Worker Node #3 (SRV-VM-003)
    └── Background Jobs
│
▼
RDS PostgreSQL (DB-PROD-001)
├── Primary Instance
└── Standby Replica
│
▼
ElastiCache Redis (APP-DB-003)
```

### Appendix D: Discovery Procedures

#### Network Discovery Procedure

```
PROCEDURE: NET-DIS-001
Title: Network Device Discovery
Version: 1.0

Purpose:
Discover and inventory all network devices in Smart Dairy infrastructure.

Scope:
All network equipment including routers, switches, firewalls, and access points.

Tools Required:
- Nmap
- SNMP scanner
- GLPI Network Discovery module

Procedure:

1. PREPARATION
   1.1. Verify access to all network segments
   1.2. Confirm SNMP community strings are configured
   1.3. Review previous discovery results

2. DISCOVERY EXECUTION
   2.1. Run ping sweep: nmap -sn 10.0.0.0/16
   2.2. Run port scan: nmap -sS -O 10.0.0.0/16
   2.3. Execute SNMP walk on discovered devices
   2.4. Capture device details (model, firmware, interfaces)

3. DATA COLLECTION
   3.1. Record device IP addresses
   3.2. Gather MAC addresses
   3.3. Collect firmware versions
   3.4. Document interface configurations
   3.5. Identify uplink/downlink connections

4. CMDB POPULATION
   4.1. Create new CI records for unknown devices
   4.2. Update existing CI records
   4.3. Document parent-child relationships
   4.4. Map network topology connections

5. VERIFICATION
   5.1. Physically verify critical devices
   5.2. Confirm network diagram accuracy
   5.3. Update documentation

Frequency: Monthly
Owner: Network Administrator
```

#### IoT Device Discovery Procedure

```
PROCEDURE: IOT-DIS-001
Title: IoT Farm Device Discovery
Version: 1.0

Purpose:
Maintain accurate inventory of all IoT devices on Smart Dairy farm.

Scope:
Milk meters, environmental sensors, RFID readers, and cold chain monitors.

Tools Required:
- IoT Gateway management interface
- MQTT client
- GLPI with custom IoT plugin
- Mobile device with scanner app

Procedure:

1. GATEWAY CHECK
   1.1. Access IoT Gateway admin console
   1.2. Export connected device list
   1.3. Note any offline devices

2. MQTT DISCOVERY
   2.1. Subscribe to farm/devices/+/status
   2.2. Collect device heartbeat messages
   2.3. Record last seen timestamps
   2.4. Identify new device connections

3. PHYSICAL AUDIT
   3.1. Scan device QR codes/barcode labels
   3.2. Verify physical installation locations
   3.3. Check device status indicators
   3.4. Document any damage or issues
   3.5. Verify calibration dates on meters

4. DATA SYNCHRONIZATION
   4.1. Import discovered devices to GLPI
   4.2. Update device status (Active/Offline)
   4.3. Update location information
   4.4. Record firmware versions
   4.5. Update battery levels

5. ANOMALY RESOLUTION
   5.1. Investigate missing devices
   5.2. Update decommissioned devices
   5.3. Flag devices requiring maintenance
   5.4. Schedule calibration for due devices

Frequency: Weekly
Owner: Farm IT Technician
```

### Appendix E: GLPI Configuration Guide

#### Initial GLPI Setup for CMDB

```
1. INSTALLATION
   - Install GLPI 10.x on Ubuntu 22.04 LTS
   - Configure Apache/Nginx web server
   - Setup MySQL/MariaDB database
   - Configure SSL certificate

2. PLUGIN INSTALLATION
   - FusionInventory Plugin (for discovery)
   - Fields Plugin (for custom attributes)
   - More Reporting Plugin (for dashboards)
   - Data Injection Plugin (for bulk imports)

3. ASSET CONFIGURATION
   - Enable all CI types (Computers, Network, etc.)
   - Configure custom fields for IoT devices
   - Setup status values: Planned, Active, Maintenance, Retired
   - Configure location hierarchy (Datacenter → Room → Rack)

4. USER CONFIGURATION
   - Create CMDB Administrator role
   - Setup SSO with Azure AD
   - Configure notification rules
   - Setup automated actions

5. INTEGRATION
   - Configure OCS Inventory integration
   - Setup AWS Config sync
   - Configure IoT Gateway API connection
   - Setup backup integration
```

### Appendix F: CMDB Metrics and KPIs

| Metric | Formula | Target | Frequency |
|--------|---------|--------|-----------|
| **CI Accuracy** | (Verified CIs / Total CIs) × 100 | > 98% | Monthly |
| **Completeness** | (CIs with all required attributes / Total CIs) × 100 | > 95% | Monthly |
| **Discovery Coverage** | (Discovered CIs / Expected CIs) × 100 | > 99% | Weekly |
| **Stale CI Rate** | (CIs not updated in 90 days / Total CIs) × 100 | < 5% | Monthly |
| **Relationship Accuracy** | (Verified relationships / Total relationships) × 100 | > 95% | Quarterly |
| **License Compliance** | (Compliant licenses / Total licenses) × 100 | 100% | Monthly |
| **CMDB Update Time** | Average time from change to CMDB update | < 24 hours | Weekly |

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Configuration Manager | Initial document creation |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*

**Next Review Date:** July 31, 2026
