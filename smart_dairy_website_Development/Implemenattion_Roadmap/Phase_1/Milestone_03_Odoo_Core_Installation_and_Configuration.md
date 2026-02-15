# MILESTONE 3: ODOO CORE INSTALLATION & CONFIGURATION

## Smart Dairy Smart Portal + ERP System Implementation

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Milestone** | Milestone 3 |
| **Title** | Odoo Core Installation & Configuration |
| **Duration** | Days 21-30 (10 Working Days) |
| **Phase** | Phase 1 - Foundation |
| **Version** | 1.0 |
| **Status** | Draft |
| **Prepared By** | Technical Implementation Team |
| **Reviewed By** | Solution Architect, Project Manager |
| **Approved By** | CTO |

---

## TABLE OF CONTENTS

1. [Milestone Overview and Objectives](#1-milestone-overview-and-objectives)
2. [Odoo 19 CE Installation (Day 21-23)](#2-odoo-19-ce-installation-day-21-23)
3. [Core Module Configuration (Day 24-26)](#3-core-module-configuration-day-24-26)
4. [Bangladesh Localization Setup (Day 27-28)](#4-bangladesh-localization-setup-day-27-28)
5. [User Management and Security (Day 29)](#5-user-management-and-security-day-29)
6. [Milestone Review and Sign-off (Day 30)](#6-milestone-review-and-sign-off-day-30)
7. [Technical Appendices](#7-technical-appendices)
8. [Troubleshooting Guides](#8-troubleshooting-guides)

---

## 1. MILESTONE OVERVIEW AND OBJECTIVES

### 1.1 Executive Summary

Milestone 3 represents the critical foundation phase for the Smart Dairy Smart Portal + ERP System implementation. This milestone establishes the core Odoo 19 Community Edition infrastructure, configures essential modules, implements Bangladesh localization, and establishes security frameworks. The successful completion of this milestone provides the robust foundation upon which all subsequent customizations and vertical solutions will be built.

### 1.2 Strategic Objectives

| Objective ID | Objective Description | Success Criteria | Priority |
|--------------|----------------------|------------------|----------|
| M3-OBJ-001 | Deploy production-ready Odoo 19 CE instance | System accessible via HTTPS with 99.9% uptime | Critical |
| M3-OBJ-002 | Activate and configure all core ERP modules | All 12 core modules operational and tested | Critical |
| M3-OBJ-003 | Implement Bangladesh localization | VAT compliance, local chart of accounts active | Critical |
| M3-OBJ-004 | Establish user management framework | Role-based access control (RBAC) implemented | Critical |
| M3-OBJ-005 | Create development environment parity | Staging environment mirrors production | High |
| M3-OBJ-006 | Document all configurations | Complete technical documentation delivered | High |

### 1.3 Scope Definition

#### 1.3.1 In-Scope Items

- Odoo 19 Community Edition installation from source
- PostgreSQL 15+ database setup and optimization
- Python 3.11+ virtual environment configuration
- Gunicorn WSGI server configuration
- Nginx reverse proxy setup with SSL/TLS
- Core module activation and configuration:
  - Base and Web framework
  - Website and E-commerce
  - Sales and CRM
  - Purchase and Inventory
  - Accounting and Finance
  - Manufacturing (MRP)
  - Point of Sale
  - Human Resources
  - Project Management
- Bangladesh localization (l10n_bd) implementation
- Company information setup
- Chart of accounts configuration
- VAT and tax configuration
- Fiscal year establishment
- User roles and permissions
- Security policy implementation
- Email template configuration
- Report template setup

#### 1.3.2 Out-of-Scope Items

- Custom module development (Milestone 4+)
- IoT sensor integration (Milestone 7+)
- Mobile application development (Milestone 5+)
- Advanced AI/ML features (Milestone 8+)
- Third-party payment gateway integration (Milestone 6+)
- SMS gateway integration (Milestone 6+)
- Data migration from legacy systems (Milestone 9)

### 1.4 Resource Allocation

| Role | Resource Count | Allocation | Responsibilities |
|------|---------------|------------|------------------|
| Dev-Lead | 1 | 100% | Architecture, installation, security, localization |
| Dev-1 (Backend Specialist) | 1 | 100% | Database, accounting, inventory, MRP modules |
| Dev-2 (Frontend Specialist) | 1 | 100% | Frontend assets, website, e-commerce, UI testing |
| DevOps Engineer | 1 | 50% | Server provisioning, CI/CD setup, monitoring |
| QA Engineer | 1 | 25% | Testing strategy, test case preparation |

### 1.5 Technology Stack

| Component | Version | Purpose |
|-----------|---------|---------|
| Odoo | 19.0 Community Edition | Core ERP Platform |
| Python | 3.11+ | Application Runtime |
| PostgreSQL | 15+ | Primary Database |
| Gunicorn | 21+ | WSGI HTTP Server |
| Nginx | 1.24+ | Reverse Proxy & Load Balancer |
| Node.js | 18+ | Frontend Asset Compilation |
| wkhtmltopdf | 0.12.6 | PDF Report Generation |
| Redis | 7+ | Session Store & Cache |

### 1.6 Deliverables

| Deliverable ID | Deliverable Name | Format | Owner |
|----------------|------------------|--------|-------|
| M3-DEL-001 | Odoo 19 Production Instance | URL | Dev-Lead |
| M3-DEL-002 | Odoo 19 Staging Instance | URL | DevOps Engineer |
| M3-DEL-003 | Core Module Configuration Report | PDF | Dev-Lead |
| M3-DEL-004 | Bangladesh Localization Configuration | Document | Dev-Lead |
| M3-DEL-005 | User Management Configuration | Document | Dev-Lead |
| M3-DEL-006 | Security Configuration Document | PDF | Dev-Lead |
| M3-DEL-007 | Installation Scripts Package | ZIP | Dev-Lead |
| M3-DEL-008 | Configuration Backup Package | ZIP | Dev-1 |
| M3-DEL-009 | Test Results Report | PDF | QA Engineer |
| M3-DEL-010 | Milestone Sign-off Document | PDF | Project Manager |

### 1.7 Dependencies and Prerequisites

| Dependency | Source | Status | Impact |
|------------|--------|--------|--------|
| Server Infrastructure | Milestone 1 | Required | Critical - Cannot proceed without servers |
| Database Server Setup | Milestone 2 | Required | Critical - PostgreSQL must be ready |
| SSL Certificates | DevOps | Required | High - Production requires HTTPS |
| Domain Name Configuration | DevOps | Required | High - DNS must be configured |
| Company Legal Information | Business Team | Required | High - Needed for localization |
| BIN/TIN Information | Finance Team | Required | Medium - For VAT configuration |

### 1.8 Risk Assessment

| Risk ID | Risk Description | Probability | Impact | Mitigation Strategy |
|---------|------------------|-------------|--------|---------------------|
| M3-RISK-001 | Installation failure due to dependency conflicts | Medium | High | Use isolated virtual environments; test on staging first |
| M3-RISK-002 | Bangladesh localization data incomplete | Medium | High | Engage Odoo Bangladesh community; prepare custom data |
| M3-RISK-003 | Database performance issues with large datasets | Low | High | Implement proper indexing; configure connection pooling |
| M3-RISK-004 | Security misconfiguration exposing vulnerabilities | Low | Critical | Follow security hardening guide; conduct penetration testing |
| M3-RISK-005 | Module compatibility issues | Medium | Medium | Test all modules in staging before production deployment |
| M3-RISK-006 | Resource constraints during asset compilation | Medium | Medium | Provision adequate RAM; use swap if necessary |

---

## 2. ODOO 19 CE INSTALLATION (DAY 21-23)

### 2.1 Installation Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CLIENT LAYER                                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐         │
│  │   Web       │  │  Mobile     │  │   Tablet    │  │   API       │         │
│  │   Browser   │  │   App       │  │   Browser   │  │   Client    │         │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘         │
└─────────┼────────────────┼────────────────┼────────────────┼────────────────┘
          │                │                │                │
          └────────────────┴────────────────┴────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         LOAD BALANCER LAYER                                │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         NGINX (SSL/TLS)                               │  │
│  │              - SSL Termination                                         │  │
│  │              - Request Routing                                         │  │
│  │              - Static File Serving                                     │  │
│  │              - Rate Limiting                                           │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        APPLICATION LAYER                                   │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                      GUNICORN WSGI SERVER                             │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │  │
│  │  │  Worker 1   │  │  Worker 2   │  │  Worker 3   │  │  Worker N   │  │  │
│  │  │ (Odoo 19)   │  │ (Odoo 19)   │  │ (Odoo 19)   │  │ (Odoo 19)   │  │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         DATA LAYER                                         │
│  ┌──────────────────────────┐  ┌─────────────────────────────────────────┐  │
│  │    PostgreSQL 15+        │  │           Redis 7+                       │  │
│  │  - Transactional Data    │  │  - Session Store                         │  │
│  │  - Relational Store      │  │  - Cache Layer                           │  │
│  │  - Full-Text Search      │  │  - Job Queue                             │  │
│  └──────────────────────────┘  └─────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Day 21: System Preparation and Prerequisites

#### 2.2.1 Server Specifications

**Production Server Specifications:**

| Component | Minimum | Recommended | Production |
|-----------|---------|-------------|------------|
| CPU | 4 Cores | 8 Cores | 16 Cores |
| RAM | 16 GB | 32 GB | 64 GB |
| Storage | 100 GB SSD | 500 GB SSD | 1 TB NVMe SSD |
| Network | 100 Mbps | 1 Gbps | 1 Gbps |
| OS | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS | Ubuntu 24.04 LTS |

**Staging Server Specifications:**

| Component | Specification |
|-----------|--------------|
| CPU | 4 Cores |
| RAM | 16 GB |
| Storage | 200 GB SSD |
| OS | Ubuntu 24.04 LTS |

#### 2.2.2 System Update and Package Installation

**Task Owner:** Dev-Lead
**Time Estimate:** 4 hours
**Priority:** Critical

```bash
#!/bin/bash
# Filename: odoo_system_prerequisites.sh
# Description: System preparation script for Odoo 19 installation
# Author: Smart Dairy Technical Team
# Version: 1.0
# Date: 2026-02-01

set -e  # Exit on error

echo "=========================================="
echo "Smart Dairy - Odoo 19 System Preparation"
echo "=========================================="
echo ""

# Color codes for output
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

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   log_error "This script must be run as root (use sudo)"
   exit 1
fi

# System information
log_info "Gathering system information..."
OS_VERSION=$(lsb_release -ds)
KERNEL_VERSION=$(uname -r)
ARCH=$(uname -m)
TOTAL_RAM=$(free -h | awk '/^Mem:/ {print $2}')
AVAILABLE_DISK=$(df -h / | awk 'NR==2 {print $4}')

echo "  Operating System: $OS_VERSION"
echo "  Kernel Version: $KERNEL_VERSION"
echo "  Architecture: $ARCH"
echo "  Total RAM: $TOTAL_RAM"
echo "  Available Disk: $AVAILABLE_DISK"
echo ""

# Update system packages
log_info "Updating system packages..."
apt-get update -qq
apt-get upgrade -y -qq
log_info "System packages updated successfully"

# Install essential build tools and dependencies
log_info "Installing essential build tools and dependencies..."
apt-get install -y -qq \
    build-essential \
    git \
    wget \
    curl \
    nodejs \
    npm \
    libxml2-dev \
    libxslt1-dev \
    libevent-dev \
    libsasl2-dev \
    libldap2-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    xfonts-75dpi \
    xfonts-base \
    libfreetype6-dev \
    liblcms2-dev \
    libopenjp2-7-dev \
    libtiff5-dev \
    libwebp-dev \
    libzip-dev \
    zlib1g-dev \
    libssl-dev \
    libffi-dev \
    python3-dev \
    python3-pip \
    python3-venv \
    python3-wheel \
    python3-setuptools \
    pkg-config \
    libtiff-dev \
    libjpeg8-dev \
    libopenjp2-7-dev \
    liblcms2-dev \
    libwebp-dev \
    libharfbuzz-dev \
    libfribidi-dev \
    libxcb1-dev \
    libblas-dev \
    liblapack-dev \
    gfortran \
    fontconfig

log_info "Essential dependencies installed successfully"

# Install PostgreSQL 15
log_info "Installing PostgreSQL 15..."
sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -
apt-get update -qq
apt-get install -y -qq postgresql-15 postgresql-client-15 postgresql-contrib-15

# Start and enable PostgreSQL
systemctl start postgresql
systemctl enable postgresql
log_info "PostgreSQL 15 installed and started"

# Install wkhtmltopdf
log_info "Installing wkhtmltopdf..."
WKHTMLTOPDF_VERSION="0.12.6.1-3"
WKHTMLTOPDF_PACKAGE="wkhtmltox_${WKHTMLTOPDF_VERSION}.jammy_amd64.deb"

if [ "$ARCH" = "x86_64" ]; then
    wget -q "https://github.com/wkhtmltopdf/packaging/releases/download/${WKHTMLTOPDF_VERSION}/${WKHTMLTOPDF_PACKAGE}"
    dpkg -i "$WKHTMLTOPDF_PACKAGE" || apt-get install -f -y
    rm -f "$WKHTMLTOPDF_PACKAGE"
    log_info "wkhtmltopdf installed successfully"
else
    log_warn "Architecture not x86_64. Installing wkhtmltopdf from repository..."
    apt-get install -y -qq wkhtmltopdf
fi

# Verify wkhtmltopdf installation
WKHTMLTOPDF_PATH=$(which wkhtmltopdf)
if [ -n "$WKHTMLTOPDF_PATH" ]; then
    WKHTMLTOPDF_VERSION_INSTALLED=$($WKHTMLTOPDF_PATH --version | head -n 1)
    log_info "wkhtmltopdf version: $WKHTMLTOPDF_VERSION_INSTALLED"
else
    log_error "wkhtmltopdf installation failed"
    exit 1
fi

# Install Node.js and npm (if not already installed)
log_info "Verifying Node.js installation..."
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    NPM_VERSION=$(npm --version)
    log_info "Node.js version: $NODE_VERSION"
    log_info "npm version: $NPM_VERSION"
else
    log_info "Installing Node.js..."
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt-get install -y -qq nodejs
fi

# Install rtlcss for RTL language support
log_info "Installing rtlcss..."
npm install -g rtlcss
log_info "rtlcss installed successfully"

# Install less and less-plugin-clean-css
log_info "Installing LESS compiler..."
npm install -g less less-plugin-clean-css
log_info "LESS compiler installed successfully"

# Create Odoo system user
log_info "Creating Odoo system user..."
if id "odoo" &>/dev/null; then
    log_warn "User 'odoo' already exists"
else
    useradd -m -d /opt/odoo -U -r -s /bin/bash odoo
    log_info "Odoo system user created"
fi

# Install Python 3.11 if not default
PYTHON_VERSION=$(python3 --version 2>&1 | awk '{print $2}' | cut -d. -f1,2)
if [[ "$PYTHON_VERSION" < "3.11" ]]; then
    log_info "Python 3.11+ not detected. Installing Python 3.11..."
    apt-get install -y -qq software-properties-common
    add-apt-repository -y ppa:deadsnakes/ppa
    apt-get update -qq
    apt-get install -y -qq python3.11 python3.11-dev python3.11-venv
    update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.11 1
    log_info "Python 3.11 installed"
else
    log_info "Python $PYTHON_VERSION is already installed"
fi

# Create directory structure
log_info "Creating Odoo directory structure..."
mkdir -p /opt/odoo/{odoo-server,custom-addons,backups,logs,config,scripts}
mkdir -p /var/log/odoo
mkdir -p /etc/odoo
chown -R odoo:odoo /opt/odoo
chown -R odoo:odoo /var/log/odoo
chmod 755 /opt/odoo
chmod 755 /var/log/odoo
log_info "Directory structure created"

# Install Redis
log_info "Installing Redis..."
apt-get install -y -qq redis-server
systemctl start redis-server
systemctl enable redis-server
log_info "Redis installed and started"

# Verify installations
echo ""
echo "=========================================="
echo "Installation Verification"
echo "=========================================="
echo ""

log_info "Verifying installed packages..."
echo "  Git: $(git --version)"
echo "  PostgreSQL: $(psql --version)"
echo "  Python: $(python3 --version)"
echo "  pip: $(pip3 --version)"
echo "  Node.js: $(node --version)"
echo "  npm: $(npm --version)"
echo "  wkhtmltopdf: $($WKHTMLTOPDF_PATH --version | head -n 1)"
echo "  Redis: $(redis-server --version | head -n 1)"

echo ""
log_info "System preparation completed successfully!"
echo ""
echo "Next steps:"
echo "  1. Configure PostgreSQL for Odoo"
echo "  2. Clone Odoo 19 source code"
echo "  3. Create Python virtual environment"
echo "  4. Install Python dependencies"
echo ""

exit 0
```

#### 2.2.3 PostgreSQL Configuration for Odoo

**Task Owner:** Dev-1
**Time Estimate:** 3 hours
**Priority:** Critical

```bash
#!/bin/bash
# Filename: postgresql_odoo_setup.sh
# Description: PostgreSQL configuration for Odoo 19
# Author: Smart Dairy Technical Team
# Version: 1.0

set -e

echo "=========================================="
echo "PostgreSQL Configuration for Odoo 19"
echo "=========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

# Configuration variables
ODOO_DB_USER="odoo_user"
ODOO_DB_PASSWORD="$(openssl rand -base64 32)"
ODOO_DB_NAME="smart_dairy_prod"
POSTGRES_VERSION="15"

log_info "Creating Odoo database user..."
sudo -u postgres psql << EOF
CREATE USER $ODOO_DB_USER WITH PASSWORD '$ODOO_DB_PASSWORD';
ALTER USER $ODOO_DB_USER WITH SUPERUSER;
ALTER USER $ODOO_DB_USER WITH CREATEDB;
\du
EOF

log_info "Database user '$ODOO_DB_USER' created"

# Backup original configuration
log_info "Backing up original PostgreSQL configuration..."
PG_HBA_ORIG="/etc/postgresql/${POSTGRES_VERSION}/main/pg_hba.conf.original"
POSTGRESQL_CONF_ORIG="/etc/postgresql/${POSTGRES_VERSION}/main/postgresql.conf.original"

[ ! -f "$PG_HBA_ORIG" ] && cp /etc/postgresql/${POSTGRES_VERSION}/main/pg_hba.conf "$PG_HBA_ORIG"
[ ! -f "$POSTGRESQL_CONF_ORIG" ] && cp /etc/postgresql/${POSTGRES_VERSION}/main/postgresql.conf "$POSTGRESQL_CONF_ORIG"

# Configure pg_hba.conf for local trust authentication
log_info "Configuring pg_hba.conf..."
cat > /etc/postgresql/${POSTGRES_VERSION}/main/pg_hba.conf << 'EOF'
# PostgreSQL Client Authentication Configuration File
# Type  Database        User            Address                 Method

# Local connections
local   all             postgres                                peer
local   all             odoo_user                               md5
local   all             all                                     md5

# IPv4 local connections:
host    all             all             127.0.0.1/32            md5
host    all             odoo_user       127.0.0.1/32            md5

# IPv6 local connections:
host    all             all             ::1/128                 md5
host    all             odoo_user       ::1/128                 md5

# Replication connections (if needed)
local   replication     all                                     peer
host    replication     all             127.0.0.1/32            md5
host    replication     all             ::1/128                 md5
EOF

log_info "pg_hba.conf configured"

# Configure postgresql.conf for Odoo optimization
log_info "Configuring postgresql.conf for Odoo optimization..."

# Get system resources
TOTAL_RAM_KB=$(grep MemTotal /proc/meminfo | awk '{print $2}')
TOTAL_RAM_MB=$((TOTAL_RAM_KB / 1024))
CPU_COUNT=$(nproc)

# Calculate PostgreSQL settings based on available resources
if [ $TOTAL_RAM_MB -ge 65536 ]; then  # 64GB+
    SHARED_BUFFERS="16GB"
    EFFECTIVE_CACHE_SIZE="48GB"
    WORK_MEM="32MB"
    MAINTENANCE_WORK_MEM="2GB"
    MAX_CONNECTIONS=400
elif [ $TOTAL_RAM_MB -ge 32768 ]; then  # 32GB
    SHARED_BUFFERS="8GB"
    EFFECTIVE_CACHE_SIZE="24GB"
    WORK_MEM="16MB"
    MAINTENANCE_WORK_MEM="1GB"
    MAX_CONNECTIONS=300
elif [ $TOTAL_RAM_MB -ge 16384 ]; then  # 16GB
    SHARED_BUFFERS="4GB"
    EFFECTIVE_CACHE_SIZE="12GB"
    WORK_MEM="8MB"
    MAINTENANCE_WORK_MEM="512MB"
    MAX_CONNECTIONS=200
else
    SHARED_BUFFERS="2GB"
    EFFECTIVE_CACHE_SIZE="6GB"
    WORK_MEM="4MB"
    MAINTENANCE_WORK_MEM="256MB"
    MAX_CONNECTIONS=100
fi

cat >> /etc/postgresql/${POSTGRES_VERSION}/main/postgresql.conf << EOF

# ============================================
# Odoo 19 Optimization Settings
# Generated: $(date)
# System RAM: ${TOTAL_RAM_MB}MB
# CPU Count: $CPU_COUNT
# ============================================

# Connection Settings
listen_addresses = 'localhost'
max_connections = $MAX_CONNECTIONS
superuser_reserved_connections = 3

# Memory Settings
shared_buffers = $SHARED_BUFFERS
effective_cache_size = $EFFECTIVE_CACHE_SIZE
work_mem = $WORK_MEM
maintenance_work_mem = $MAINTENANCE_WORK_MEM
dynamic_shared_memory_type = posix

# Write Ahead Log
wal_level = replica
wal_buffers = 16MB
max_wal_size = 2GB
min_wal_size = 1GB
checkpoint_completion_target = 0.9
random_page_cost = 1.1
effective_io_concurrency = 200

# Query Planner
default_statistics_target = 100
constraint_exclusion = partition

# Logging
log_destination = 'stderr'
logging_collector = on
log_directory = '/var/log/postgresql'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_min_duration_statement = 1000
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_checkpoints = on
log_connections = on
log_disconnections = on
log_lock_waits = on

# Autovacuum
autovacuum = on
autovacuum_max_workers = 5
autovacuum_naptime = 1min

# Locale and Formatting
datestyle = 'iso, mdy'
timezone = 'Asia/Dhaka'
lc_messages = 'en_US.UTF-8'
lc_monetary = 'en_US.UTF-8'
lc_numeric = 'en_US.UTF-8'
lc_time = 'en_US.UTF-8'
default_text_search_config = 'pg_catalog.english'
EOF

log_info "postgresql.conf configured with optimized settings"
log_info "Shared Buffers: $SHARED_BUFFERS"
log_info "Effective Cache Size: $EFFECTIVE_CACHE_SIZE"
log_info "Max Connections: $MAX_CONNECTIONS"

# Create log directory
mkdir -p /var/log/postgresql
chown postgres:postgres /var/log/postgresql

# Restart PostgreSQL
log_info "Restarting PostgreSQL..."
systemctl restart postgresql@${POSTGRES_VERSION}-main

# Verify PostgreSQL is running
if systemctl is-active --quiet postgresql@${POSTGRES_VERSION}-main; then
    log_info "PostgreSQL restarted successfully"
else
    echo "ERROR: PostgreSQL failed to restart"
    exit 1
fi

# Test database connection
log_info "Testing database connection..."
sudo -u postgres psql -c "SELECT version();" > /dev/null 2>&1 && log_info "Database connection successful" || log_warn "Database connection test failed"

# Save credentials to secure file
CREDENTIALS_FILE="/etc/odoo/postgresql_credentials.conf"
mkdir -p /etc/odoo
chmod 700 /etc/odoo
cat > "$CREDENTIALS_FILE" << EOF
# PostgreSQL Credentials for Odoo
# Generated: $(date)
# KEEP THIS FILE SECURE - RESTRICTED ACCESS

DB_HOST=localhost
DB_PORT=5432
DB_USER=$ODOO_DB_USER
DB_PASSWORD=$ODOO_DB_PASSWORD
DB_NAME=$ODOO_DB_NAME

# Connection Pool Settings
DB_MAXCONN=64
EOF

chmod 600 "$CREDENTIALS_FILE"
chown odoo:odoo "$CREDENTIALS_FILE"

log_info "Credentials saved to $CREDENTIALS_FILE"
log_info "PostgreSQL configuration completed!"

echo ""
echo "=========================================="
echo "Summary"
echo "=========================================="
echo "Database User: $ODOO_DB_USER"
echo "Database Name: $ODOO_DB_NAME"
echo "Credentials File: $CREDENTIALS_FILE"
echo ""
echo "IMPORTANT: Secure the credentials file!"
echo "chmod 600 $CREDENTIALS_FILE"
echo ""

exit 0
```

#### 2.2.4 Database Connection Setup (Dev-1 Responsibility)

**Task Owner:** Dev-1
**Time Estimate:** 4 hours
**Priority:** Critical

```python
#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Database Connection Test and Validation Script
Smart Dairy - Odoo 19 Implementation
Author: Dev-1
"""

import psycopg2
import psycopg2.extras
import sys
import os
import time
import logging
from datetime import datetime
from contextlib import contextmanager

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('/var/log/odoo/db_connection_test.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'port': 5432,
    'database': 'postgres',  # Connect to default db first
    'user': 'odoo_user',
    # Password should be read from secure file
}

def load_password_from_file():
    """Load database password from secure credentials file."""
    credentials_file = '/etc/odoo/postgresql_credentials.conf'
    try:
        with open(credentials_file, 'r') as f:
            for line in f:
                if line.startswith('DB_PASSWORD='):
                    return line.split('=', 1)[1].strip()
    except FileNotFoundError:
        logger.error(f"Credentials file not found: {credentials_file}")
        return None
    except PermissionError:
        logger.error(f"Permission denied reading: {credentials_file}")
        return None
    return None

@contextmanager
def get_db_connection(dbname=None):
    """Context manager for database connections."""
    config = DB_CONFIG.copy()
    config['password'] = load_password_from_file()
    if dbname:
        config['database'] = dbname
    
    conn = None
    try:
        conn = psycopg2.connect(**config)
        yield conn
    except psycopg2.Error as e:
        logger.error(f"Database connection error: {e}")
        raise
    finally:
        if conn:
            conn.close()

def test_basic_connection():
    """Test basic database connectivity."""
    logger.info("=" * 60)
    logger.info("Test 1: Basic Database Connection")
    logger.info("=" * 60)
    
    try:
        with get_db_connection() as conn:
            with conn.cursor() as cur:
                cur.execute("SELECT version();")
                version = cur.fetchone()[0]
                logger.info(f"✓ Connected successfully")
                logger.info(f"  PostgreSQL Version: {version}")
                return True
    except Exception as e:
        logger.error(f"✗ Connection failed: {e}")
        return False

def test_database_creation():
    """Test database creation and deletion."""
    logger.info("")
    logger.info("=" * 60)
    logger.info("Test 2: Database Creation")
    logger.info("=" * 60)
    
    test_db_name = f"test_db_{int(time.time())}"
    
    try:
        # Create database
        with get_db_connection('postgres') as conn:
            conn.autocommit = True
            with conn.cursor() as cur:
                cur.execute(f"CREATE DATABASE {test_db_name};")
                logger.info(f"✓ Database '{test_db_name}' created")
        
        # Verify database exists
        with get_db_connection('postgres') as conn:
            with conn.cursor() as cur:
                cur.execute(
                    "SELECT 1 FROM pg_database WHERE datname = %s;",
                    (test_db_name,)
                )
                if cur.fetchone():
                    logger.info(f"✓ Database exists in pg_database")
                
                # Drop test database
                cur.execute(f"DROP DATABASE {test_db_name};")
                logger.info(f"✓ Database '{test_db_name}' dropped")
        
        return True
    except Exception as e:
        logger.error(f"✗ Database creation test failed: {e}")
        return False

def test_user_privileges():
    """Test user privileges and permissions."""
    logger.info("")
    logger.info("=" * 60)
    logger.info("Test 3: User Privileges")
    logger.info("=" * 60)
    
    try:
        with get_db_connection() as conn:
            with conn.cursor() as cur:
                # Check superuser status
                cur.execute("SELECT rolsuper FROM pg_roles WHERE rolname = %s;", 
                          (DB_CONFIG['user'],))
                is_superuser = cur.fetchone()[0]
                logger.info(f"  Superuser: {'Yes' if is_superuser else 'No'}")
                
                # Check createdb privilege
                cur.execute("SELECT rolcreatedb FROM pg_roles WHERE rolname = %s;", 
                          (DB_CONFIG['user'],))
                can_create_db = cur.fetchone()[0]
                logger.info(f"  Can Create DB: {'Yes' if can_create_db else 'No'}")
                
                # List all privileges
                cur.execute("""
                    SELECT rolname, rolsuper, rolinherit, rolcreaterole, 
                           rolcreatedb, rolcanlogin
                    FROM pg_roles 
                    WHERE rolname = %s;
                """, (DB_CONFIG['user'],))
                role_info = cur.fetchone()
                logger.info(f"  Role Info: {role_info}")
                
                if is_superuser and can_create_db:
                    logger.info("✓ User has required privileges")
                    return True
                else:
                    logger.warning("✗ User may not have all required privileges")
                    return False
    except Exception as e:
        logger.error(f"✗ Privilege test failed: {e}")
        return False

def test_connection_pooling():
    """Test multiple concurrent connections."""
    logger.info("")
    logger.info("=" * 60)
    logger.info("Test 4: Connection Pooling")
    logger.info("=" * 60)
    
    from concurrent.futures import ThreadPoolExecutor, as_completed
    
    def test_single_connection(conn_id):
        try:
            with get_db_connection() as conn:
                with conn.cursor() as cur:
                    cur.execute("SELECT pg_backend_pid(), now();")
                    pid, timestamp = cur.fetchone()
                    return (conn_id, pid, timestamp, True)
        except Exception as e:
            return (conn_id, None, None, False)
    
    connection_count = 10
    success_count = 0
    
    with ThreadPoolExecutor(max_workers=connection_count) as executor:
        futures = [executor.submit(test_single_connection, i) 
                   for i in range(connection_count)]
        
        for future in as_completed(futures):
            conn_id, pid, timestamp, success = future.result()
            if success:
                success_count += 1
                logger.info(f"  Connection {conn_id}: PID {pid}")
            else:
                logger.warning(f"  Connection {conn_id}: FAILED")
    
    logger.info(f"✓ {success_count}/{connection_count} concurrent connections successful")
    return success_count == connection_count

def test_performance():
    """Test database performance with sample queries."""
    logger.info("")
    logger.info("=" * 60)
    logger.info("Test 5: Performance Benchmark")
    logger.info("=" * 60)
    
    try:
        with get_db_connection() as conn:
            with conn.cursor() as cur:
                # Simple query benchmark
                start_time = time.time()
                for _ in range(1000):
                    cur.execute("SELECT 1;")
                simple_duration = time.time() - start_time
                
                # Complex query benchmark
                start_time = time.time()
                cur.execute("""
                    SELECT generate_series(1, 10000) as num, 
                           md5(random()::text) as hash
                    ORDER BY hash;
                """)
                cur.fetchall()
                complex_duration = time.time() - start_time
                
                logger.info(f"  1000 simple queries: {simple_duration:.3f}s")
                logger.info(f"  Complex query (10k rows): {complex_duration:.3f}s")
                
                if simple_duration < 1.0 and complex_duration < 5.0:
                    logger.info("✓ Performance within acceptable range")
                    return True
                else:
                    logger.warning("✗ Performance below expected thresholds")
                    return False
    except Exception as e:
        logger.error(f"✗ Performance test failed: {e}")
        return False

def create_smart_dairy_database():
    """Create the main Smart Dairy production database."""
    logger.info("")
    logger.info("=" * 60)
    logger.info("Creating Smart Dairy Production Database")
    logger.info("=" * 60)
    
    db_name = "smart_dairy_prod"
    
    try:
        with get_db_connection('postgres') as conn:
            conn.autocommit = True
            with conn.cursor() as cur:
                # Check if database exists
                cur.execute(
                    "SELECT 1 FROM pg_database WHERE datname = %s;",
                    (db_name,)
                )
                if cur.fetchone():
                    logger.warning(f"Database '{db_name}' already exists")
                    return True
                
                # Create database with proper encoding
                cur.execute(f"""
                    CREATE DATABASE {db_name}
                    WITH OWNER = {DB_CONFIG['user']}
                    ENCODING = 'UTF8'
                    LC_COLLATE = 'en_US.UTF-8'
                    LC_CTYPE = 'en_US.UTF-8'
                    TABLESPACE = pg_default
                    CONNECTION LIMIT = -1;
                """)
                logger.info(f"✓ Database '{db_name}' created successfully")
                
                # Enable required extensions
                cur.execute(f"""
                    \c {db_name};
                    CREATE EXTENSION IF NOT EXISTS pg_trgm;
                    CREATE EXTENSION IF NOT EXISTS unaccent;
                """)
                logger.info("✓ Database extensions enabled")
                
                return True
    except Exception as e:
        logger.error(f"✗ Failed to create database: {e}")
        return False

def main():
    """Main test execution function."""
    logger.info("Smart Dairy - Database Connection Test Suite")
    logger.info(f"Started at: {datetime.now()}")
    
    results = {
        'basic_connection': test_basic_connection(),
        'database_creation': test_database_creation(),
        'user_privileges': test_user_privileges(),
        'connection_pooling': test_connection_pooling(),
        'performance': test_performance(),
    }
    
    # Create production database
    results['create_production_db'] = create_smart_dairy_database()
    
    # Summary
    logger.info("")
    logger.info("=" * 60)
    logger.info("TEST SUMMARY")
    logger.info("=" * 60)
    
    total_tests = len(results)
    passed_tests = sum(results.values())
    
    for test_name, result in results.items():
        status = "✓ PASS" if result else "✗ FAIL"
        logger.info(f"{test_name:.<40} {status}")
    
    logger.info("-" * 60)
    logger.info(f"Total: {passed_tests}/{total_tests} tests passed")
    
    if passed_tests == total_tests:
        logger.info("✓ All tests passed - Database is ready for Odoo installation")
        return 0
    else:
        logger.warning("✗ Some tests failed - Review configuration before proceeding")
        return 1

if __name__ == '__main__':
    sys.exit(main())
```

#### 2.2.5 Frontend Asset Compilation Setup (Dev-2 Responsibility)

**Task Owner:** Dev-2
**Time Estimate:** 3 hours
**Priority:** High

```bash
#!/bin/bash
# Filename: frontend_asset_setup.sh
# Description: Frontend asset compilation setup for Odoo 19
# Author: Dev-2
# Version: 1.0

set -e

echo "=========================================="
echo "Frontend Asset Compilation Setup"
echo "=========================================="
echo ""

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

# Check Node.js version
log_info "Checking Node.js version..."
NODE_VERSION=$(node --version | cut -d'v' -f2)
REQUIRED_VERSION="18.0.0"

version_ge() {
    [ "$1" = "$(echo -e "$1\n$2" | sort -V | tail -n1)" ]
}

if version_ge "$NODE_VERSION" "$REQUIRED_VERSION"; then
    log_info "Node.js version $NODE_VERSION is compatible"
else
    log_warn "Node.js version $NODE_VERSION may have compatibility issues"
    log_warn "Recommended: Node.js 18.x or higher"
fi

# Install global npm packages
log_info "Installing global npm packages..."

# LESS compiler
if ! command -v lessc &> /dev/null; then
    npm install -g less less-plugin-clean-css
    log_info "LESS compiler installed"
else
    log_info "LESS compiler already installed"
fi

# RTLCSS for RTL languages
if ! command -v rtlcss &> /dev/null; then
    npm install -g rtlcss
    log_info "RTLCSS installed"
else
    log_info "RTLCSS already installed"
fi

# ESLint for code quality
npm install -g eslint
log_info "ESLint installed"

# Verify installations
echo ""
log_info "Verifying installations..."
echo "  Node.js: $(node --version)"
echo "  npm: $(npm --version)"
echo "  lessc: $(lessc --version 2>&1 | head -n 1)"
echo "  rtlcss: $(rtlcss --version 2>&1 | head -n 1)"

# Create asset compilation directories
log_info "Creating asset directories..."
mkdir -p /opt/odoo/assets/{css,js,fonts,img}
chown -R odoo:odoo /opt/odoo/assets

# Set up font configuration
log_info "Setting up font configuration..."
mkdir -p /usr/share/fonts/odoo

# Install commonly used fonts
apt-get install -y -qq \
    fonts-liberation \
    fonts-dejavu-core \
    fonts-freefont-ttf \
    fonts-noto-core \
    fonts-noto-cjk \
    fonts-noto-color-emoji

fc-cache -f -v

# Font Awesome (if needed for custom themes)
log_info "Downloading Font Awesome..."
FONTAWESOME_VERSION="6.4.0"
wget -q -O /tmp/fontawesome.zip \
    "https://use.fontawesome.com/releases/v${FONTAWESOME_VERSION}/fontawesome-free-${FONTAWESOME_VERSION}-web.zip"

if [ -f "/tmp/fontawesome.zip" ]; then
    unzip -q -o /tmp/fontawesome.zip -d /opt/odoo/assets/fonts/
    rm /tmp/fontawesome.zip
    log_info "Font Awesome installed"
fi

# Create asset compilation script
cat > /opt/odoo/scripts/compile_assets.sh << 'ASSET_SCRIPT'
#!/bin/bash
# Odoo Asset Compilation Script
# Usage: ./compile_assets.sh [odoo_path] [database] [addons_path]

ODOO_PATH=${1:-"/opt/odoo/odoo-server"}
DATABASE=${2:-"smart_dairy_prod"}
ADDONS_PATH=${3:-"/opt/odoo/odoo-server/addons,/opt/odoo/custom-addons"}

if [ -z "$DATABASE" ]; then
    echo "Error: Database name required"
    exit 1
fi

echo "Compiling Odoo assets..."
echo "  Odoo Path: $ODOO_PATH"
echo "  Database: $DATABASE"
echo "  Addons Path: $ADDONS_PATH"
echo ""

cd "$ODOO_PATH"

# Regenerate assets
python3 odoo-bin \
    -d "$DATABASE" \
    --addons-path="$ADDONS_PATH" \
    -u base \
    --stop-after-init \
    --no-http

if [ $? -eq 0 ]; then
    echo "✓ Asset compilation completed successfully"
else
    echo "✗ Asset compilation failed"
    exit 1
fi

# Clear old assets (optional)
echo "Clearing old asset bundles..."
python3 << PYTHON_EOF
import psycopg2
import sys

try:
    conn = psycopg2.connect(
        dbname="$DATABASE",
        user="odoo_user",
        host="localhost"
    )
    cur = conn.cursor()
    
    # Clear ir_attachment assets
    cur.execute("""
        DELETE FROM ir_attachment 
        WHERE res_model = 'ir.ui.view' 
        AND name LIKE '%asset_%';
    """)
    
    conn.commit()
    cur.close()
    conn.close()
    print("✓ Old asset bundles cleared")
except Exception as e:
    print(f"Warning: Could not clear old assets: {e}")
    sys.exit(0)
PYTHON_EOF

echo ""
echo "Asset compilation complete!"
ASSET_SCRIPT

chmod +x /opt/odoo/scripts/compile_assets.sh
chown odoo:odoo /opt/odoo/scripts/compile_assets.sh

log_info "Asset compilation script created"

# Create CSS optimization configuration
cat > /opt/odoo/assets/.lessrc << 'LESSRC'
{
    "compress": true,
    "sourceMap": false,
    "cleancss": true,
    "cleancssOptions": {
        "compatibility": "ie9",
        "level": 2
    }
}
LESSRC

log_info "LESS configuration created"

echo ""
log_info "Frontend asset setup completed!"
echo ""
echo "Next steps:"
echo "  1. Clone Odoo source code"
echo "  2. Run asset compilation after Odoo installation"
echo ""

exit 0
```



## 3. CORE MODULE CONFIGURATION (DAY 24-26)

### 3.1 Module Activation Strategy

#### 3.1.1 Module Dependency Graph

`
base
+-- web
�   +-- website
�   �   +-- website_sale (E-commerce)
�   �   +-- website_blog
�   +-- web_editor
�   +-- web_tour
+-- mail
+-- utm
+-- http_routing
+-- auth_signup

sale (Sales Management)
+-- account (Accounting)
+-- product
+-- portal
+-- payment
+-- analytic
+-- sales_team
+-- utm

purchase (Purchase Management)
+-- account
+-- product
+-- analytic
+-- mail

stock (Inventory Management)
+-- product
+-- account (optional)
+-- analytic
+-- report

mrp (Manufacturing)
+-- stock
+-- product
+-- account
+-- analytic
+-- resource

account (Accounting/Invoicing)
+-- base
+-- product
+-- analytic
+-- portal
+-- digest

hr (Human Resources)
+-- base
+-- mail
+-- resource
+-- digest
+-- web
`

#### 3.1.2 Module Installation Sequence

**Phase 1: Foundation Modules (Day 24 Morning)**

`python
# Module installation sequence for Smart Dairy
# Execute in Odoo shell or via custom script

FOUNDATION_MODULES = [
    # Core Framework
    'base',
    'web',
    'mail',
    'resource',
    'utm',
    
    # Authentication
    'auth_signup',
    'auth_totp',  # 2FA support
]

BUSINESS_MODULES = [
    # Sales & CRM
    'crm',
    'sale',
    'sale_management',
    'sales_team',
    
    # Purchase & Inventory
    'purchase',
    'stock',
    'product',
    
    # Accounting
    'account',
    'account_accountant',
    
    # Manufacturing
    'mrp',
    'mrp_account',
]

EXTENSION_MODULES = [
    # Website
    'website',
    'website_sale',
    'website_blog',
    'website_payment',
    
    # Point of Sale
    'point_of_sale',
    'pos_sale',
    
    # HR
    'hr',
    'hr_contract',
    'hr_holidays',
    'hr_attendance',
    
    # Project
    'project',
    'project_timesheet_synchro',
]

LOCALIZATION_MODULES = [
    # Bangladesh Localization
    'l10n_bd',
    
    # Multi-currency
    'base_setup',
    'currency_rate_live',
]
`



## ADDITIONAL TECHNICAL SPECIFICATIONS FOR MILESTONE 3

### 3.1 Detailed Module Configuration Scripts

#### 3.1.1 Automated Module Installation Script

```bash
#!/bin/bash
# Filename: comprehensive_odoo_setup.sh
# Description: Complete Odoo 19 CE module installation and configuration
# Author: Dev-Lead
# Version: 1.0

set -euo pipefail

# Configuration variables
ODOO_PATH="/opt/odoo/odoo-server"
CONFIG_FILE="/etc/odoo/odoo.conf"
DATABASE="smart_dairy_prod"
LOG_FILE="/var/log/odoo/m3_setup.log"
BACKUP_DIR="/opt/odoo/backups/pre_m3"

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] INFO:${NC} $1" | tee -a "$LOG_FILE"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARN:${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR:${NC} $1" | tee -a "$LOG_FILE"
}

# Create backup before installation
log "Creating pre-installation backup..."
mkdir -p "$BACKUP_DIR"
pg_dump -h localhost -U odoo_user "$DATABASE" > "$BACKUP_DIR/${DATABASE}_pre_m3_$(date +%Y%m%d_%H%M%S).sql" 2>/dev/null || warn "Could not create database backup"

# Module installation phases
PHASES=(
    "base,web,mail,resource,utm:Phase 1: Core Framework"
    "auth_signup,auth_totp:Phase 2: Authentication"
    "crm,sale,sale_management,sales_team:Phase 3: Sales & CRM"
    "purchase,stock,product:Phase 4: Inventory & Purchase"
    "account,account_accountant:Phase 5: Accounting Base"
    "l10n_bd:Phase 6: Bangladesh Localization"
    "mrp,mrp_account:Phase 7: Manufacturing"
    "website,website_sale,website_blog:Phase 8: Website"
    "point_of_sale,pos_sale:Phase 9: Point of Sale"
    "hr,hr_contract,hr_holidays,hr_attendance:Phase 10: Human Resources"
    "project:Phase 11: Project Management"
)

# Install modules phase by phase
total_phases=${#PHASES[@]}
current_phase=0

for phase_info in "${PHASES[@]}"; do
    IFS=':' read -r modules phase_name <<< "$phase_info"
    current_phase=$((current_phase + 1))
    
    log "[$current_phase/$total_phases] Installing $phase_name"
    log "Modules: $modules"
    
    if python3 "$ODOO_PATH/odoo-bin" -c "$CONFIG_FILE" -d "$DATABASE" -i "$modules" --stop-after-init --no-http 2>&1 | tee -a "$LOG_FILE"; then
        log "✓ $phase_name completed successfully"
    else
        error "✗ $phase_name failed. Check logs at $LOG_FILE"
        exit 1
    fi
    
    # Brief pause between phases
    sleep 5
done

log "All module installation phases completed successfully"

# Post-installation configuration
log "Applying post-installation configurations..."

# Update company information
log "Configuring company information..."
python3 << PYTHON_EOF
import xmlrpc.client

url = 'http://localhost:8069'
db = '$DATABASE'
username = 'admin'
password = 'admin'

common = xmlrpc.client.ServerProxy(f'{url}/xmlrpc/2/common')
uid = common.authenticate(db, username, password, {})
models = xmlrpc.client.ServerProxy(f'{url}/xmlrpc/2/object')

# Update company
company_id = models.execute_kw(db, uid, password, 'res.company', 'search', [[['name', '!=', '']]], {'limit': 1})
if company_id:
    models.execute_kw(db, uid, password, 'res.company', 'write', [
        company_id,
        {
            'name': 'Smart Dairy Ltd.',
            'street': 'Jahir Smart Tower, 205/1 & 205/1/A, West Kafrul',
            'street2': 'Begum Rokeya Sharani, Taltola',
            'city': 'Dhaka',
            'zip': '1207',
            'phone': '+880-XXXXXXXXXX',
            'email': 'info@smartdairybd.com',
            'website': 'https://smartdairybd.com',
            'currency_id': models.execute_kw(db, uid, password, 'res.currency', 'search', [[['name', '=', 'BDT']]])[0],
        }
    ])
    print("Company information updated successfully")

PYTHON_EOF

log "Post-installation configuration completed"

# Final verification
log "Running final verification..."
INSTALLED_MODULES=$(psql -h localhost -U odoo_user -d "$DATABASE" -t -c "SELECT COUNT(*) FROM ir_module_module WHERE state='installed';" 2>/dev/null | xargs)
log "Total installed modules: $INSTALLED_MODULES"

# Display summary
echo ""
echo "========================================="
echo "Milestone 3 Setup Complete"
echo "========================================="
echo "Installation Log: $LOG_FILE"
echo "Database Backup: $BACKUP_DIR"
echo "Installed Modules: $INSTALLED_MODULES"
echo ""
echo "Next Steps:"
echo "  1. Configure Bangladesh localization details"
echo "  2. Set up user accounts and permissions"
echo "  3. Configure chart of accounts"
echo "  4. Run system tests"
echo ""

exit 0
```

### 3.2 Bangladesh Localization Implementation

#### 3.2.1 VAT Configuration Script

```python
#!/usr/bin/env python3
"""
Bangladesh Localization Configuration for Smart Dairy
Configures VAT, TDS, and other Bangladesh-specific accounting requirements
"""

import xmlrpc.client
from typing import List, Dict

class BangladeshLocalization:
    def __init__(self, url: str, db: str, username: str, password: str):
        self.url = url
        self.db = db
        self.username = username
        self.password = password
        self.common = xmlrpc.client.ServerProxy(f'{url}/xmlrpc/2/common')
        self.uid = self.common.authenticate(db, username, password, {})
        self.models = xmlrpc.client.ServerProxy(f'{url}/xmlrpc/2/object')
    
    def configure_vat_taxes(self):
        """Configure Bangladesh VAT tax rates."""
        vat_taxes = [
            {
                'name': 'VAT 15% (Standard)',
                'type_tax_use': 'sale',
                'amount_type': 'percent',
                'amount': 15.0,
                'description': 'Standard VAT rate for most products in Bangladesh',
                'price_include': False,
                'include_base_amount': False,
            },
            {
                'name': 'VAT 5% (Reduced)',
                'type_tax_use': 'sale',
                'amount_type': 'percent',
                'amount': 5.0,
                'description': 'Reduced VAT rate for essential goods',
                'price_include': False,
            },
            {
                'name': 'VAT 0% (Zero-rated)',
                'type_tax_use': 'sale',
                'amount_type': 'percent',
                'amount': 0.0,
                'description': 'Zero-rated for exports and specific products',
                'price_include': False,
            },
            {
                'name': 'VAT 15% (Purchases)',
                'type_tax_use': 'purchase',
                'amount_type': 'percent',
                'amount': 15.0,
                'description': 'Input VAT on purchases',
                'price_include': False,
            },
            {
                'name': 'Import VAT 15%',
                'type_tax_use': 'purchase',
                'amount_type': 'percent',
                'amount': 15.0,
                'description': 'VAT on imported goods',
                'price_include': False,
            },
        ]
        
        for tax_data in vat_taxes:
            existing = self.models.execute_kw(
                self.db, self.uid, self.password,
                'account.tax', 'search',
                [[('name', '=', tax_data['name'])]]
            )
            
            if not existing:
                tax_id = self.models.execute_kw(
                    self.db, self.uid, self.password,
                    'account.tax', 'create',
                    [tax_data]
                )
                print(f"✓ Created tax: {tax_data['name']} (ID: {tax_id})")
            else:
                print(f"⚠ Tax already exists: {tax_data['name']}")
    
    def configure_product_categories_with_tax(self):
        """Assign default taxes to product categories."""
        category_tax_mapping = {
            'Fresh Milk': ('VAT 15% (Standard)', 'VAT 15% (Purchases)'),
            'Yogurt': ('VAT 15% (Standard)', 'VAT 15% (Purchases)'),
            'Cheese': ('VAT 15% (Standard)', 'VAT 15% (Purchases)'),
            'Butter & Ghee': ('VAT 15% (Standard)', 'VAT 15% (Purchases)'),
            'Organic Beef': ('VAT 5% (Reduced)', 'VAT 15% (Purchases)'),
        }
        
        for category_name, (sale_tax_name, purchase_tax_name) in category_tax_mapping.items():
            # Find category
            category_ids = self.models.execute_kw(
                self.db, self.uid, self.password,
                'product.category', 'search',
                [[('name', '=', category_name)]]
            )
            
            if category_ids:
                # Find taxes
                sale_tax_id = self.models.execute_kw(
                    self.db, self.uid, self.password,
                    'account.tax', 'search',
                    [[('name', '=', sale_tax_name), ('type_tax_use', '=', 'sale')]]
                )
                purchase_tax_id = self.models.execute_kw(
                    self.db, self.uid, self.password,
                    'account.tax', 'search',
                    [[('name', '=', purchase_tax_name), ('type_tax_use', '=', 'purchase')]]
                )
                
                update_values = {}
                if sale_tax_id:
                    update_values['taxes_id'] = [(6, 0, sale_tax_id)]
                if purchase_tax_id:
                    update_values['supplier_taxes_id'] = [(6, 0, purchase_tax_id)]
                
                if update_values:
                    self.models.execute_kw(
                        self.db, self.uid, self.password,
                        'product.category', 'write',
                        [category_ids, update_values]
                    )
                    print(f"✓ Updated taxes for category: {category_name}")
    
    def configure_fiscal_positions(self):
        """Configure fiscal positions for different customer types."""
        fiscal_positions = [
            {
                'name': 'B2B - Standard VAT',
                'auto_apply': False,
                'vat_required': True,
            },
            {
                'name': 'B2C - VAT Included',
                'auto_apply': True,
                'vat_required': False,
            },
            {
                'name': 'Export - Zero Rated',
                'auto_apply': False,
                'vat_required': False,
            },
        ]
        
        for fp_data in fiscal_positions:
            existing = self.models.execute_kw(
                self.db, self.uid, self.password,
                'account.fiscal.position', 'search',
                [[('name', '=', fp_data['name'])]]
            )
            
            if not existing:
                fp_id = self.models.execute_kw(
                    self.db, self.uid, self.password,
                    'account.fiscal.position', 'create',
                    [fp_data]
                )
                print(f"✓ Created fiscal position: {fp_data['name']}")

# Execution
if __name__ == '__main__':
    loc = BangladeshLocalization(
        url='http://localhost:8069',
        db='smart_dairy_prod',
        username='admin',
        password='admin'
    )
    
    print("Configuring Bangladesh Localization...")
    print("=" * 50)
    
    loc.configure_vat_taxes()
    loc.configure_product_categories_with_tax()
    loc.configure_fiscal_positions()
    
    print("=" * 50)
    print("Bangladesh Localization configuration complete!")
```

### 3.3 User Management and Security Configuration

#### 3.3.1 Comprehensive User Role Matrix

```python
# User Role and Permission Matrix for Smart Dairy
# This configuration defines all user roles and their access permissions

USER_ROLE_MATRIX = {
    'Managing Director': {
        'groups': [
            'base.group_system',
            'base.group_erp_manager',
            'account.group_account_manager',
            'sales.group_sale_manager',
            'purchase.group_purchase_manager',
            'stock.group_stock_manager',
        ],
        'menu_access': 'all',
        'report_access': 'all',
        'record_rules': 'all',
        'dashboard_access': ['executive_dashboard', 'financial_dashboard', 'operations_dashboard'],
    },
    
    'Finance Controller': {
        'groups': [
            'account.group_account_manager',
            'stock.group_stock_manager',
            'purchase.group_purchase_manager',
        ],
        'menu_access': [
            'accounting_menu',
            'reporting_menu',
            'inventory_readonly_menu',
            'purchase_readonly_menu',
        ],
        'report_access': [
            'financial_reports',
            'tax_reports',
            'vat_mushak_reports',
            'inventory_valuation_reports',
        ],
        'record_rules': [
            'multi_company_rule',
            'account_move_see_all',
        ],
        'dashboard_access': ['financial_dashboard'],
    },
    
    'Operations Director': {
        'groups': [
            'stock.group_stock_manager',
            'purchase.group_purchase_manager',
            'mrp.group_mrp_manager',
        ],
        'menu_access': [
            'inventory_menu',
            'purchase_menu',
            'manufacturing_menu',
            'sales_readonly_menu',
        ],
        'report_access': [
            'inventory_reports',
            'production_reports',
            'procurement_reports',
        ],
        'record_rules': [
            'multi_company_rule',
            'warehouse_multi_company_rule',
        ],
        'dashboard_access': ['operations_dashboard'],
    },
    
    'Sales Manager': {
        'groups': [
            'sales.group_sale_manager',
            'crm.group_crm_manager',
        ],
        'menu_access': [
            'sales_menu',
            'crm_menu',
            'customers_menu',
            'products_readonly_menu',
        ],
        'report_access': [
            'sales_reports',
            'crm_reports',
            'customer_analytics',
            'sales_team_performance',
        ],
        'record_rules': [
            'own_sales_team_records',
            'customer_multi_company_rule',
        ],
        'dashboard_access': ['sales_dashboard', 'crm_dashboard'],
    },
    
    'Sales Executive': {
        'groups': [
            'sales.group_sale_salesman',
        ],
        'menu_access': [
            'sales_menu',
            'crm_menu',
            'customers_menu',
        ],
        'report_access': [
            'own_sales_reports',
            'customer_list',
        ],
        'record_rules': [
            'own_sales_records_only',
        ],
        'dashboard_access': ['personal_sales_dashboard'],
    },
    
    'Farm Manager': {
        'groups': [
            'smart_dairy_farm.group_farm_manager',
            'stock.group_stock_user',
        ],
        'menu_access': [
            'farm_management_menu',
            'herd_management_menu',
            'production_menu',
            'health_management_menu',
            'inventory_readonly_menu',
        ],
        'report_access': [
            'herd_reports',
            'production_reports',
            'health_reports',
            'feed_consumption_reports',
        ],
        'record_rules': [
            'farm_location_records_only',
        ],
        'dashboard_access': ['farm_dashboard'],
    },
    
    'Farm Supervisor': {
        'groups': [
            'smart_dairy_farm.group_farm_worker',
        ],
        'menu_access': [
            'farm_management_menu',
            'daily_operations_menu',
        ],
        'report_access': [
            'daily_production_summary',
            'animal_health_status',
        ],
        'record_rules': [
            'farm_location_records_only',
            'create_only_own_records',
        ],
        'dashboard_access': ['farm_operations_dashboard'],
    },
    
    'Warehouse Manager': {
        'groups': [
            'stock.group_stock_manager',
        ],
        'menu_access': [
            'inventory_menu',
            'warehouse_operations_menu',
            'receipts_menu',
            'deliveries_menu',
            'internal_transfers_menu',
        ],
        'report_access': [
            'inventory_reports',
            'stock_movement_reports',
            'warehouse_efficiency_reports',
        ],
        'record_rules': [
            'assigned_warehouse_only',
        ],
        'dashboard_access': ['warehouse_dashboard'],
    },
    
    'Accountant': {
        'groups': [
            'account.group_account_user',
        ],
        'menu_access': [
            'accounting_menu',
            'customers_menu',
            'vendors_menu',
            'journal_entries_menu',
        ],
        'report_access': [
            'accounting_reports',
            'trial_balance',
            'balance_sheet',
            'profit_loss',
        ],
        'record_rules': [
            'fiscal_year_records_only',
        ],
        'dashboard_access': ['accounting_dashboard'],
    },
}
```

### 3.4 Security Policies Implementation

#### 3.4.1 Password Policy Configuration

```python
# Password policy configuration for Smart Dairy
# These settings enforce strong password requirements

PASSWORD_SECURITY_CONFIG = {
    # Minimum password length
    'min_length': 10,
    
    # Character complexity requirements
    'min_uppercase': 1,
    'min_lowercase': 1,
    'min_digits': 1,
    'min_special': 1,
    'special_characters': '!@#$%^&*()_+-=[]{}|;:,.<>?',
    
    # Password expiration
    'expiration_days': 90,
    'expiration_warning_days': 7,
    
    # Password history (cannot reuse last N passwords)
    'history_count': 5,
    
    # Account lockout settings
    'max_login_attempts': 5,
    'lockout_duration_minutes': 30,
    'reset_lockout_after_minutes': 60,
    
    # Session settings
    'session_timeout_minutes': 30,
    'concurrent_session_limit': 3,
    
    # Two-factor authentication
    'mfa_required_for_groups': [
        'base.group_system',
        'account.group_account_manager',
    ],
    'mfa_optional_for_all': True,
}

# Odoo-specific implementation (via ir.config_parameter)
ODOO_PASSWORD_PARAMS = [
    ('auth_password_policy.minlength', '10'),
    ('auth_signup.reset_password.validity.hours', '24'),
    ('auth_signup.invitation.expiration.hours', '48'),
]
```

#### 3.4.2 Session Security Configuration

```python
# Session and cookie security settings
SESSION_SECURITY_CONFIG = {
    # Session cookie settings
    'session_cookie_secure': True,      # HTTPS only
    'session_cookie_httponly': True,    # Prevent JavaScript access
    'session_cookie_samesite': 'Lax',   # CSRF protection
    
    # Session lifetime
    'session_lifetime_hours': 8,
    'session_inactivity_timeout_minutes': 30,
    
    # CSRF protection
    'csrf_protection': True,
    'csrf_token_validity_hours': 24,
    
    # XSS protection headers
    'x_content_type_options': 'nosniff',
    'x_frame_options': 'SAMEORIGIN',
    'x_xss_protection': '1; mode=block',
    
    # HSTS (HTTP Strict Transport Security)
    'strict_transport_security': True,
    'hsts_max_age_seconds': 31536000,  # 1 year
    'hsts_include_subdomains': True,
    'hsts_preload': True,
}
```

### 3.5 Testing and Validation

#### 3.5.1 Post-Installation Test Suite

```python
#!/usr/bin/env python3
"""
Milestone 3 Post-Installation Test Suite
Validates that all Odoo core components are properly configured
"""

import unittest
import xmlrpc.client
import sys

class OdooInstallationTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.url = 'http://localhost:8069'
        cls.db = 'smart_dairy_prod'
        cls.username = 'admin'
        cls.password = 'admin'
        
        cls.common = xmlrpc.client.ServerProxy(f'{cls.url}/xmlrpc/2/common')
        cls.uid = cls.common.authenticate(cls.db, cls.username, cls.password, {})
        cls.models = xmlrpc.client.ServerProxy(f'{cls.url}/xmlrpc/2/object')
    
    def test_01_connection(self):
        """Test basic Odoo connection."""
        self.assertIsNotNone(self.uid, "Authentication failed")
        version_info = self.common.version()
        self.assertIn('server_version', version_info)
        print(f"✓ Odoo version: {version_info['server_version']}")
    
    def test_02_core_modules_installed(self):
        """Verify core modules are installed."""
        core_modules = [
            'base', 'web', 'mail', 'resource',
            'sale', 'purchase', 'stock', 'account',
            'mrp', 'website', 'website_sale',
        ]
        
        for module in core_modules:
            module_ids = self.models.execute_kw(
                self.db, self.uid, self.password,
                'ir.module.module', 'search',
                [[('name', '=', module), ('state', '=', 'installed')]]
            )
            self.assertTrue(module_ids, f"Module {module} not installed")
            print(f"✓ Module installed: {module}")
    
    def test_03_localization_installed(self):
        """Verify Bangladesh localization is active."""
        loc_id = self.models.execute_kw(
            self.db, self.uid, self.password,
            'ir.module.module', 'search',
            [[('name', '=', 'l10n_bd'), ('state', '=', 'installed')]]
        )
        self.assertTrue(loc_id, "Bangladesh localization not installed")
        print("✓ Bangladesh localization (l10n_bd) installed")
    
    def test_04_company_configured(self):
        """Verify company information is set."""
        company = self.models.execute_kw(
            self.db, self.uid, self.password,
            'res.company', 'search_read',
            [[]],
            {'fields': ['name', 'currency_id', 'country_id'], 'limit': 1}
        )[0]
        
        self.assertEqual(company['name'], 'Smart Dairy Ltd.')
        print(f"✓ Company configured: {company['name']}")
    
    def test_05_chart_of_accounts(self):
        """Verify chart of accounts is loaded."""
        account_count = self.models.execute_kw(
            self.db, self.uid, self.password,
            'account.account', 'search_count',
            [[]]
        )
        self.assertGreater(account_count, 50, "Chart of accounts seems incomplete")
        print(f"✓ Chart of accounts loaded: {account_count} accounts")
    
    def test_06_taxes_configured(self):
        """Verify VAT taxes are configured."""
        tax_count = self.models.execute_kw(
            self.db, self.uid, self.password,
            'account.tax', 'search_count',
            [[]]
        )
        self.assertGreater(tax_count, 0, "No taxes configured")
        print(f"✓ Taxes configured: {tax_count} tax records")
    
    def test_07_warehouse_configured(self):
        """Verify warehouse is configured."""
        warehouse = self.models.execute_kw(
            self.db, self.uid, self.password,
            'stock.warehouse', 'search_read',
            [[]],
            {'fields': ['name', 'code'], 'limit': 1}
        )
        self.assertTrue(warehouse, "No warehouse configured")
        print(f"✓ Warehouse configured: {warehouse[0]['name']}")
    
    def test_08_users_configured(self):
        """Verify user accounts exist."""
        user_count = self.models.execute_kw(
            self.db, self.uid, self.password,
            'res.users', 'search_count',
            [[('active', '=', True)]]
        )
        self.assertGreater(user_count, 1, "Only admin user exists")
        print(f"✓ User accounts: {user_count} active users")

# Run tests
if __name__ == '__main__':
    # Configure test runner
    loader = unittest.TestLoader()
    suite = loader.loadTestsFromModule(sys.modules[__name__])
    runner = unittest.TextTestRunner(verbosity=2)
    result = runner.run(suite)
    
    # Exit with appropriate code
    sys.exit(0 if result.wasSuccessful() else 1)
```

## 4. MILESTONE 3 COMPLETION CHECKLIST

### 4.1 Verification Checklist

| # | Item | Status | Verified By |
|---|------|--------|-------------|
| 1 | Odoo 19 CE installed from source | ☐ | |
| 2 | PostgreSQL database optimized | ☐ | |
| 3 | All core modules installed | ☐ | |
| 4 | Bangladesh localization active | ☐ | |
| 5 | Chart of accounts configured | ☐ | |
| 6 | VAT taxes configured | ☐ | |
| 7 | Company information set | ☐ | |
| 8 | Warehouse configured | ☐ | |
| 9 | User accounts created | ☐ | |
| 10 | Security policies applied | ☐ | |
| 11 | Automated tests passing | ☐ | |
| 12 | Documentation complete | ☐ | |

### 4.2 Sign-off

**Prepared By:**
- Name: _________________
- Signature: _________________
- Date: _________________

**Reviewed By:**
- Name: _________________
- Signature: _________________
- Date: _________________

**Approved By:**
- Name: _________________
- Signature: _________________
- Date: _________________

---

*End of Milestone 3 Documentation*
