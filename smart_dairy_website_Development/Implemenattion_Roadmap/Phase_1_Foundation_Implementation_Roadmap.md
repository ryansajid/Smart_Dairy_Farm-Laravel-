# Smart Dairy Smart Portal + ERP System
## Phase 1: Foundation Implementation Roadmap

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Document Version** | 1.0 |
| **Release Date** | February 2026 |
| **Classification** | Implementation Guide - Internal Use |
| **Prepared For** | Smart Dairy Development Team |
| **Prepared By** | Technical Architecture Team |
| **Status** | Approved for Implementation |

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Project Overview](#2-project-overview)
3. [Team Structure & Responsibilities](#3-team-structure--responsibilities)
4. [Technical Architecture](#4-technical-architecture)
5. [Development Environment Setup](#5-development-environment-setup)
6. [Milestone Roadmap](#6-milestone-roadmap)
   - M1: Development Environment (Days 1-10)
   - M2: Infrastructure & DevOps (Days 11-20)
   - M3: Odoo Core Platform (Days 21-30)
   - M4: Public Website Foundation (Days 31-40)
   - M5: Website Content & Features (Days 41-50)
   - M6: Core ERP Modules - Part 1 (Days 51-60)
   - M7: Core ERP Modules - Part 2 (Days 61-70)
   - M8: Farm Management Module (Days 71-80)
   - M9: Data Migration & Integration (Days 81-90)
   - M10: Go-Live Preparation (Days 91-100)
7. [Quality Assurance Framework](#7-quality-assurance-framework)
8. [Risk Management](#8-risk-management)
9. [Appendices](#9-appendices)

---

## 1. EXECUTIVE SUMMARY

### 1.1 Phase 1 Vision

Phase 1 establishes the foundational infrastructure for Smart Dairy's digital transformation, delivering:

- **Production-ready cloud infrastructure** with Docker containerization
- **Fully configured Odoo 19 CE ERP core** with Bangladesh localization
- **Multi-language public website** (English/Bengali) with CMS capabilities
- **Basic farm management module** for herd and milk production tracking
- **Integrated payment gateway** (bKash/Nagad/Rocket) foundation
- **Complete data migration** of master data

### 1.2 Investment Summary

| Category | Amount (BDT) | Percentage |
|----------|--------------|------------|
| Human Resources (3 Developers × 100 days) | 45,00,000 | 34% |
| Cloud Infrastructure (AWS/Azure) | 18,00,000 | 14% |
| Third-party Services & APIs | 12,00,000 | 9% |
| Development Tools & Licenses | 5,00,000 | 4% |
| Training & Documentation | 8,00,000 | 6% |
| Contingency (15%) | 13,20,000 | 10% |
| **Total Phase 1 Budget** | **1,32,00,000** | **100%** |

### 1.3 Success Criteria

- [ ] 99.9% infrastructure uptime achieved
- [ ] All 255 animals registered in farm management module
- [ ] Public website live with 50+ content pages
- [ ] Core ERP modules operational (Accounting, Inventory, Sales, Purchase)
- [ ] Master data 100% migrated and validated
- [ ] 3-second average page load time
- [ ] Security audit passed with zero critical vulnerabilities

---

## 2. PROJECT OVERVIEW

### 2.1 Smart Dairy Business Context

| Parameter | Current State | Phase 1 Target |
|-----------|---------------|----------------|
| **Cattle Herd** | 255 head | 255 head (full digitization) |
| **Daily Production** | 900 liters | 900 liters (complete tracking) |
| **Employees** | 50+ | 50+ (system users) |
| **Product SKUs** | 5 | 15 (expanded catalog) |
| **Customers** | ~500 | ~500 (CRM ready) |
| **Website** | Basic static | Full CMS with e-commerce foundation |

### 2.2 Phase 1 Deliverables

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         PHASE 1 DELIVERABLES                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐  │
│  │   INFRASTRUCTURE    │  │   ERP FOUNDATION    │  │   WEBSITE & PORTAL  │  │
│  ├─────────────────────┤  ├─────────────────────┤  ├─────────────────────┤  │
│  │ • AWS Cloud Setup   │  │ • Odoo 19 CE Core   │  │ • Public Website    │  │
│  │ • Docker Containers │  │ • Bangladesh COA    │  │ • CMS (EN/BN)       │  │
│  │ • CI/CD Pipeline    │  │ • VAT Configuration │  │ • Product Catalog   │  │
│  │ • Monitoring Stack  │  │ • RBAC Security     │  │ • Contact Forms     │  │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────┘  │
│                                                                              │
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐  │
│  │  FARM MANAGEMENT    │  │   INTEGRATIONS      │  │   DATA & TRAINING   │  │
│  ├─────────────────────┤  ├─────────────────────┤  ├─────────────────────┤  │
│  │ • Animal Registry   │  │ • bKash Payment     │  │ • Master Data       │  │
│  │ • Milk Production   │  │ • SMS Gateway       │  │ • User Training     │  │
│  │ • Health Records    │  │ • Email Service     │  │ • Documentation     │  │
│  │ • Breeding Tracking │  │ • WhatsApp API      │  │ • Go-Live Support   │  │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Technology Stack for Phase 1

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Operating System** | Ubuntu Linux LTS | 24.04 | Development & Production |
| **IDE** | Visual Studio Code | Latest | Code editor with extensions |
| **ERP Platform** | Odoo Community Edition | 19.0 | Core ERP system |
| **Language** | Python | 3.11+ | Backend development |
| **Database** | PostgreSQL | 16.x | Primary data storage |
| **Cache** | Redis | 7.x | Session & cache management |
| **Web Server** | Nginx | 1.24+ | Reverse proxy & static files |
| **App Server** | Gunicorn | Latest | WSGI HTTP Server |
| **Container** | Docker | 24.x+ | Application packaging |
| **Orchestration** | Docker Compose | 2.x+ | Multi-container management |
| **CI/CD** | GitHub Actions | - | Build & deployment automation |
| **Frontend** | HTML5, CSS3, JavaScript | ES6+ | Web interfaces |
| **CSS Framework** | Bootstrap | 5.3 | Responsive styling |

---

## 3. TEAM STRUCTURE & RESPONSIBILITIES

### 3.1 Development Team Composition

| Role | Count | Primary Responsibilities |
|------|-------|-------------------------|
| **Technical Lead / Senior Full Stack Developer** | 1 | Architecture, backend development, DevOps, code review |
| **Backend-Focused Full Stack Developer** | 1 | Odoo modules, API development, database design |
| **Frontend-Focused Full Stack Developer** | 1 | Website, UI/UX, mobile-responsive design, integrations |

### 3.2 Developer Profiles

#### Developer 1: Technical Lead (Lead Dev)

**Skills Required:**
- 5+ years Python development experience
- 3+ years Odoo development (custom modules, ORM, workflows)
- DevOps expertise (Docker, CI/CD, Cloud infrastructure)
- Database administration (PostgreSQL)
- Security implementation experience

**Phase 1 Responsibilities:**
- Infrastructure architecture and setup
- Odoo core installation and configuration
- DevOps pipeline implementation
- Security framework implementation
- Code review and quality assurance
- Technical documentation

#### Developer 2: Backend Developer (Backend Dev)

**Skills Required:**
- 3+ years Python development
- 2+ years Odoo module development
- Database design and optimization
- API development (REST)
- Integration experience (payment gateways, SMS)

**Phase 1 Responsibilities:**
- Custom Odoo module development (Farm Management)
- Bangladesh localization module
- Database schema design
- Payment gateway integrations
- Background job implementation
- Data migration scripts

#### Developer 3: Frontend Developer (Frontend Dev)

**Skills Required:**
- 3+ years frontend development
- Strong HTML5, CSS3, JavaScript skills
- Bootstrap/Tailwind CSS experience
- Responsive design expertise
- Basic Python for Odoo QWeb templates
- UX/UI design sensibility

**Phase 1 Responsibilities:**
- Public website design and development
- Odoo website theme customization
- CMS configuration and content setup
- Mobile-responsive implementation
- User interface components
- Frontend performance optimization

### 3.3 RACI Matrix

| Task | Lead Dev | Backend Dev | Frontend Dev |
|------|----------|-------------|--------------|
| Infrastructure Setup | R/A | C | I |
| Odoo Core Installation | R/A | C | I |
| Database Design | A | R | C |
| Custom Module Development | A | R | C |
| Website Development | A | C | R |
| Integration Development | A | R | C |
| Security Implementation | R/A | C | C |
| Testing | A | R | R |
| Documentation | A | R | R |
| Deployment | R/A | C | I |

*R = Responsible, A = Accountable, C = Consulted, I = Informed*

---

## 4. TECHNICAL ARCHITECTURE

### 4.1 Phase 1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              CLIENT LAYER                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                      │
│  │ Web Browser  │  │  Mobile      │  │  Tablet      │                      │
│  │   (All)      │  │  (Staff)     │  │  (Office)    │                      │
│  └──────────────┘  └──────────────┘  └──────────────┘                      │
└───────────────────────────────────────┬─────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           LOAD BALANCER (Nginx)                              │
│                    SSL Termination, Rate Limiting, Static Files              │
└───────────────────────────────────────┬─────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         APPLICATION LAYER                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────────┐│
│  │                         ODOO 19 APPLICATION SERVER                      ││
│  │                     (Gunicorn + Odoo Workers)                           ││
│  │                                                                         ││
│  │  ┌─────────────────────────────────────────────────────────────────┐   ││
│  │  │                    STANDARD ODOO MODULES                         │   ││
│  │  │  • Website Builder    • E-commerce (future)   • Inventory       │   ││
│  │  │  • Sales & CRM        • Purchase            • Accounting        │   ││
│  │  │  • POS (future)       • HR (future)         • Manufacturing     │   ││
│  │  └─────────────────────────────────────────────────────────────────┘   ││
│  │                                                                         ││
│  │  ┌─────────────────────────────────────────────────────────────────┐   ││
│  │  │               CUSTOM SMART DAIRY MODULES (Phase 1)               │   ││
│  │  │                                                                   │   ││
│  │  │  ┌──────────────────┐  ┌──────────────────┐                      │   ││
│  │  │  │ smart_farm_mgmt  │  │ smart_bd_local   │                      │   ││
│  │  │  │  - Animal Master │  │  - Bangladesh COA│                      │   ││
│  │  │  │  - Breeding      │  │  - VAT Config    │                      │   ││
│  │  │  │  - Milk Production│  │  - Mushak Forms  │                      │   ││
│  │  │  │  - Health Records│  │  - Bengali Lang  │                      │   ││
│  │  │  └──────────────────┘  └──────────────────┘                      │   ││
│  │  │                                                                   │   ││
│  │  │  ┌──────────────────┐  ┌──────────────────┐                      │   ││
│  │  │  │ smart_website_ext│  │ smart_payment_bd │                      │   ││
│  │  │  │  - Custom Theme  │  │  - bKash         │                      │   ││
│  │  │  │  - Product Catalog│  │  - Nagad         │                      │   ││
│  │  │  │  - Contact Forms │  │  - Rocket        │                      │   ││
│  │  │  └──────────────────┘  └──────────────────┘                      │   ││
│  │  │                                                                   │   ││
│  │  └─────────────────────────────────────────────────────────────────┘   ││
│  │                                                                         ││
│  └─────────────────────────────────────────────────────────────────────────┘│
│                                                                              │
│  ┌──────────────────────────┐  ┌──────────────────────────┐                │
│  │    BACKGROUND WORKERS    │  │      API GATEWAY         │                │
│  │    (Celery + Redis)      │  │    (FastAPI - Future)    │                │
│  │  • Email notifications   │  │                          │                │
│  │  • Report generation     │  │                          │                │
│  │  • Data synchronization  │  │                          │                │
│  └──────────────────────────┘  └──────────────────────────┘                │
│                                                                              │
└───────────────────────────────────────┬─────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATA LAYER                                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌────────────────────────┐  ┌────────────────────────┐                    │
│  │   PRIMARY DATABASE     │  │      CACHE LAYER       │                    │
│  │   PostgreSQL 16        │  │   Redis 7              │                    │
│  │  ├─ Transaction Data   │  │  ├─ Sessions           │                    │
│  │  ├─ Master Data        │  │  ├─ Cache              │                    │
│  │  └─ Audit Logs         │  │  └─ Celery Queue       │                    │
│  └────────────────────────┘  └────────────────────────┘                    │
│                                                                              │
│  ┌────────────────────────┐  ┌────────────────────────┐                    │
│  │   FILE STORAGE         │  │   BACKUP STORAGE       │                    │
│  │   (S3/MinIO)           │  │   (AWS S3 / Azure Blob)│                    │
│  │  ├─ Product Images     │  │                        │                    │
│  │  ├─ Documents          │  │                        │                    │
│  │  └─ Backups            │  │                        │                    │
│  └────────────────────────┘  └────────────────────────┘                    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Development Environment Specification

| Component | Development | Staging | Production |
|-----------|-------------|---------|------------|
| **OS** | Ubuntu 24.04 LTS | Ubuntu 24.04 LTS | Ubuntu 24.04 LTS |
| **CPU** | 4 cores | 4 cores | 8 cores |
| **RAM** | 8 GB | 8 GB | 32 GB |
| **Storage** | 100 GB SSD | 100 GB SSD | 500 GB SSD |
| **PostgreSQL** | 16 (local) | 16 (container) | 16 (managed/cloud) |
| **Redis** | 7 (local) | 7 (container) | 7 (managed/cloud) |
| **Odoo Workers** | 2 | 4 | 8 |

### 4.3 Odoo Module Structure for Phase 1

```
smart_dairy_addons/
├── smart_core/                          # Core dependencies
│   ├── __manifest__.py
│   └── models/
│
├── smart_farm_mgmt/                     # Farm Management Module
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   ├── animal.py                    # Animal master data
│   │   ├── breeding.py                  # Breeding records
│   │   ├── milk_production.py           # Daily milk recording
│   │   ├── health.py                    # Health & veterinary
│   │   └── feed.py                      # Feed management
│   ├── views/
│   ├── security/
│   └── data/
│
├── smart_bd_local/                      # Bangladesh Localization
│   ├── __manifest__.py
│   ├── models/
│   │   ├── account_chart.py             # Bangladesh COA
│   │   ├── vat_config.py                # VAT settings
│   │   └── tax_report.py                # Mushak forms
│   └── data/
│       ├── chart_of_accounts.xml
│       └── tax_templates.xml
│
├── smart_website_ext/                   # Website Extensions
│   ├── __manifest__.py
│   ├── controllers/
│   ├── views/
│   ├── static/
│   └── data/
│
└── smart_payment_bd/                    # Bangladesh Payment Gateways
    ├── __manifest__.py
    ├── models/
    │   ├── payment_provider.py
    │   ├── bkash_payment.py
    │   ├── nagad_payment.py
    │   └── rocket_payment.py
    └── views/
```

---

## 5. DEVELOPMENT ENVIRONMENT SETUP

### 5.1 Local Development Machine Configuration

Each developer workstation requires:

```bash
# Hardware Requirements
CPU: Intel i5/i7 or AMD Ryzen 5/7 (6 cores minimum)
RAM: 16 GB minimum (32 GB recommended)
Storage: 512 GB SSD minimum
Display: 1080p minimum (dual monitor recommended)

# Operating System
Ubuntu 24.04 LTS Desktop
Windows 11 with WSL2 (alternative)
macOS Sonoma (alternative for frontend dev)
```

### 5.2 Required Software Installation

#### 5.2.1 Base System Packages

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y \
    git \
    curl \
    wget \
    vim \
    nano \
    htop \
    net-tools \
    build-essential \
    python3-dev \
    python3-pip \
    python3-venv \
    libpq-dev \
    libxml2-dev \
    libxslt1-dev \
    libldap2-dev \
    libsasl2-dev \
    libssl-dev \
    libffi-dev \
    libjpeg-dev \
    libpng-dev \
    zlib1g-dev

# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo apt install -y docker-compose-plugin

# Install VS Code
wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > packages.microsoft.gpg
sudo install -D -o root -g root -m 644 packages.microsoft.gpg /etc/apt/keyrings/packages.microsoft.gpg
sudo sh -c 'echo "deb [arch=amd64,arm64,armhf signed-by=/etc/apt/keyrings/packages.microsoft.gpg] https://packages.microsoft.com/repos/code stable main" > /etc/apt/sources.list.d/vscode.list'
rm -f packages.microsoft.gpg
sudo apt update
sudo apt install -y code
```

#### 5.2.2 VS Code Extensions

```
Required Extensions:
├── Python (ms-python.python)
├── Python Debugger (ms-python.debugpy)
├── Pylance (ms-python.vscode-pylance)
├── Odoo IDE (trinhanhduoc.brownie-odoo)
├── XML Tools (redhat.vscode-xml)
├── PostgreSQL (ckolkman.vscode-postgres)
├── Docker (ms-azuretools.vscode-docker)
├── GitLens (eamodio.gitlives)
├── Prettier (esbenp.prettier-vscode)
├── ESLint (dbaeumer.vscode-eslint)
├── Auto Rename Tag (formulahendry.auto-rename-tag)
├── Thunder Client (rangav.vscode-thunder-client) - API testing
├── Error Lens (usernamehw.errorlens)
├── Material Icon Theme (pkief.material-icon-theme)
└── One Dark Pro Theme (zhuangtongfa.material-theme)
```

#### 5.2.3 PostgreSQL 16 Installation

```bash
# Add PostgreSQL repository
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sudo apt update

# Install PostgreSQL 16
sudo apt install -y postgresql-16 postgresql-client-16 postgresql-contrib-16

# Start and enable PostgreSQL
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Create Odoo database user
sudo -u postgres createuser -s odoo_dev
sudo -u postgres psql -c "ALTER USER odoo_dev WITH PASSWORD 'odoo_dev_password';"

# Create development database
sudo -u postgres createdb -O odoo_dev smart_dairy_dev

# Configure PostgreSQL for local development
sudo nano /etc/postgresql/16/main/pg_hba.conf
# Add/modify:
# local   all             all                                     trust
# host    all             all             127.0.0.1/32            trust

sudo systemctl restart postgresql
```

#### 5.2.4 Redis Installation

```bash
# Install Redis
sudo apt install -y redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf
# Set: maxmemory 256mb
# Set: maxmemory-policy allkeys-lru

# Start and enable Redis
sudo systemctl start redis
sudo systemctl enable redis

# Verify installation
redis-cli ping
# Should return: PONG
```

#### 5.2.5 Odoo 19 Development Setup

```bash
# Create project directory
mkdir -p ~/projects/smart-dairy
cd ~/projects/smart-dairy

# Clone Odoo 19 repository
git clone https://github.com/odoo/odoo.git --depth 1 --branch 19.0 odoo

# Create Python virtual environment
python3 -m venv venv
source venv/bin/activate

# Upgrade pip
pip install --upgrade pip wheel

# Install Odoo dependencies
pip install -r odoo/requirements.txt

# Additional dependencies for development
pip install \
    debugpy \
    pylint-odoo \
    flake8 \
    black \
    pytest \
    pytest-cov \
    ipython \
    jupyter

# Create custom addons directory
mkdir -p smart_dairy_addons

# Create Odoo configuration file
cat > odoo.conf << EOF
[options]
admin_passwd = admin_master_password
db_host = localhost
db_port = 5432
db_user = odoo_dev
db_password = odoo_dev_password
addons_path = ./odoo/addons,./odoo/odoo/addons,./smart_dairy_addons
data_dir = ./data
logfile = ./odoo.log
log_level = debug
log_handler = [":DEBUG"]
http_port = 8069
longpolling_port = 8072
workers = 0
limit_time_cpu = 600
limit_time_real = 1200
EOF

# Create startup script
cat > start_odoo.sh << 'EOF'
#!/bin/bash
cd ~/projects/smart-dairy
source venv/bin/activate
./odoo/odoo-bin -c odoo.conf "$@"
EOF
chmod +x start_odoo.sh

# Create debug startup script
cat > start_odoo_debug.sh << 'EOF'
#!/bin/bash
cd ~/projects/smart-dairy
source venv/bin/activate
python -m debugpy --listen 5678 --wait-for-client ./odoo/odoo-bin -c odoo.conf "$@"
EOF
chmod +x start_odoo_debug.sh
```

### 5.3 Git Workflow Setup

```bash
# Configure Git
git config --global user.name "Developer Name"
git config --global user.email "developer@smartdairybd.com"
git config --global init.defaultBranch main

# Initialize project repository
cd ~/projects/smart-dairy
git init

# Create .gitignore
cat > .gitignore << 'EOF'
# Python
__pycache__/
*.py[cod]
*$py.class
*.so
.Python
venv/
env/
*.egg-info/
dist/
build/

# Odoo
odoo/
data/
*.log
filestore/
sessions/

# IDE
.vscode/
.idea/
*.swp
*.swo

# OS
.DS_Store
Thumbs.db

# Temporary files
*.tmp
*.bak
*.backup
EOF

# Create initial commit structure
mkdir -p {docs,scripts,tests,deploy}
git add .
git commit -m "Initial project structure"

# Create development branches
git checkout -b develop
git checkout -b milestone-1-dev-env
git checkout -b milestone-2-infrastructure
git checkout -b milestone-3-odoo-core
# ... additional milestone branches
```

---

## 6. MILESTONE ROADMAP


### 6.1 M1: Development Environment Setup (Days 1-10)

#### Objective Statement

Establish complete development environment for all 3 developers including local Odoo 19 CE setup, PostgreSQL 16, Redis, Docker configuration, and code repository structure.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M1-D1 | Ubuntu 24.04 LTS setup on all 3 workstations | Lead Dev | OS version check |
| M1-D2 | PostgreSQL 16 installed and configured | All Dev | psql connection test |
| M1-D3 | Redis 7 installed and running | All Dev | redis-cli ping |
| M1-D4 | Python 3.11+ and virtual environments | All Dev | python --version |
| M1-D5 | Odoo 19 CE cloned and running locally | All Dev | http://localhost:8069 |
| M1-D6 | VS Code with required extensions | All Dev | Extension list verification |
| M1-D7 | Git repository initialized with branches | Lead Dev | git branch -a |
| M1-D8 | Docker and Docker Compose installed | All Dev | docker --version |
| M1-D9 | Development documentation complete | Lead Dev | Docs review |
| M1-D10 | Environment setup validated | All Dev | End-to-end test |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 1** | Install Ubuntu 24.04 LTS on all workstations | All Dev | OS ready |
| **Day 1** | Install base packages (git, curl, build tools) | All Dev | Dependencies installed |
| **Day 2** | Install and configure PostgreSQL 16 | All Dev | Database running |
| **Day 2** | Create Odoo database users and permissions | Lead Dev | DB users created |
| **Day 3** | Install Redis 7 with configuration | All Dev | Cache server ready |
| **Day 3** | Install Python 3.11 and pip | All Dev | Python environment |
| **Day 4** | Clone Odoo 19 CE repository | All Dev | Source code available |
| **Day 4** | Create Python virtual environment | All Dev | venv activated |
| **Day 5** | Install Odoo Python dependencies | All Dev | requirements installed |
| **Day 5** | Install Node.js and npm | All Dev | Frontend tools ready |
| **Day 6** | Install VS Code and extensions | All Dev | IDE configured |
| **Day 6** | Configure VS Code settings for Odoo development | Lead Dev | settings.json ready |
| **Day 7** | Install Docker and Docker Compose | All Dev | Docker running |
| **Day 7** | Test Docker with hello-world | All Dev | Docker verified |
| **Day 8** | Initialize Git repository | Lead Dev | Repo initialized |
| **Day 8** | Create branch structure (main, develop, milestone branches) | Lead Dev | Branches created |
| **Day 9** | Create project directory structure | Lead Dev | Structure ready |
| **Day 9** | Write initial documentation | All Dev | README and setup docs |
| **Day 10** | Validate all environments | All Dev | Full system test |
| **Day 10** | Milestone 1 Review Meeting | All Dev | M1 sign-off |

#### Odoo Configuration Template

```ini
; odoo.conf - Development Configuration
[options]
; Admin password for database management
admin_passwd = admin_master_password_2026

; Database configuration
db_host = localhost
db_port = 5432
db_user = odoo_dev
db_password = odoo_dev_password
db_name = smart_dairy_dev
db_template = template0
dbfilter = smart_dairy.*

; Addons paths - adjust per developer workstation
addons_path = 
    /home/dev/projects/smart-dairy/odoo/addons,
    /home/dev/projects/smart-dairy/odoo/odoo/addons,
    /home/dev/projects/smart-dairy/smart_dairy_addons

; Data directory for filestore, sessions
data_dir = /home/dev/projects/smart-dairy/data

; Logging
logfile = /home/dev/projects/smart-dairy/logs/odoo.log
log_level = debug
log_handler = [":DEBUG"]

; Server configuration
http_port = 8069
longpolling_port = 8072
gevent_port = 8072

; Development settings (no workers in dev)
workers = 0
max_cron_threads = 2

; Time limits (generous for development)
limit_time_cpu = 600
limit_time_real = 1200
limit_memory_soft = 2147483648
limit_memory_hard = 2684354560

; Proxy mode (disable in dev)
proxy_mode = False

; Email configuration (for testing)
; email_from = dev@smartdairybd.com
; smtp_server = localhost
; smtp_port = 25

; Localization
load_language = en_US
language = en_US
translate_modules = ['all']

; Debug mode
debug_mode = True
dev_mode = True
```

#### Success Criteria

- [ ] All 3 developers can access Odoo at http://localhost:8069
- [ ] Database connection stable with <100ms response time
- [ ] Git repository accessible with proper branch protection
- [ ] Docker commands execute without errors
- [ ] VS Code debugger attaches to Odoo process
- [ ] All team members can create databases and install modules

---

### 6.2 M2: Infrastructure & DevOps (Days 11-20)

#### Objective Statement

Set up cloud infrastructure on AWS/Azure, implement Docker containerization, establish CI/CD pipeline with GitHub Actions, and configure monitoring stack (Prometheus/Grafana).

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M2-D1 | Cloud account setup (AWS/Azure) | Lead Dev | Console access verified |
| M2-D2 | VPC and network configuration | Lead Dev | Network diagram approved |
| M2-D3 | PostgreSQL RDS/Cloud SQL instance | Lead Dev | DB connection test |
| M2-D4 | Redis ElastiCache/Cache instance | Lead Dev | Redis connection test |
| M2-D5 | Docker Compose for local development | Lead Dev | docker-compose up works |
| M2-D6 | Production Dockerfile | Lead Dev | Image builds successfully |
| M2-D7 | GitHub Actions CI/CD pipeline | Lead Dev | Pipeline runs on push |
| M2-D8 | Nginx reverse proxy configuration | Lead Dev | Reverse proxy working |
| M2-D9 | SSL certificate setup (Let's Encrypt) | Lead Dev | HTTPS working |
| M2-D10 | Monitoring stack (Prometheus/Grafana) | Lead Dev | Dashboards accessible |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 11** | Create AWS/Azure accounts and configure billing | Lead Dev | Cloud accounts ready |
| **Day 11** | Design VPC architecture (subnets, security groups) | Lead Dev | Architecture diagram |
| **Day 12** | Provision VPC, subnets, Internet Gateway | Lead Dev | Network infrastructure |
| **Day 12** | Configure security groups and NACLs | Lead Dev | Security rules defined |
| **Day 13** | Create PostgreSQL RDS/Cloud SQL instance | Lead Dev | Database provisioned |
| **Day 13** | Configure database backups and maintenance | Lead Dev | Backup policy set |
| **Day 14** | Create Redis ElastiCache/Cache instance | Lead Dev | Cache provisioned |
| **Day 14** | Configure Redis persistence and security | Lead Dev | Redis secured |
| **Day 15** | Create docker-compose.yml for local development | Lead Dev | Local containers work |
| **Day 15** | Create Dockerfile for production builds | Lead Dev | Production image ready |
| **Day 16** | Write .dockerignore and optimization | Lead Dev | Optimized builds |
| **Day 16** | Test container builds and startup | All Dev | Containers validated |
| **Day 17** | Create GitHub Actions workflow file | Lead Dev | CI workflow created |
| **Day 17** | Configure automated testing in pipeline | Lead Dev | Test stage added |
| **Day 18** | Configure deployment to staging | Lead Dev | Auto-deployment works |
| **Day 18** | Set up GitHub secrets for credentials | Lead Dev | Secrets configured |
| **Day 19** | Configure Nginx as reverse proxy | Lead Dev | Nginx config ready |
| **Day 19** | Set up SSL certificates with certbot | Lead Dev | HTTPS enabled |
| **Day 20** | Install Prometheus and Grafana | Lead Dev | Monitoring ready |
| **Day 20** | Create initial dashboards | Lead Dev | Dashboards created |

#### Docker Configuration

```yaml
# docker-compose.yml - Local Development
version: '3.8'

services:
  db:
    image: postgres:16-alpine
    container_name: smart_dairy_db
    environment:
      POSTGRES_DB: smart_dairy_dev
      POSTGRES_USER: odoo_dev
      POSTGRES_PASSWORD: odoo_dev_password
      PGDATA: /var/lib/postgresql/data/pgdata
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"
    networks:
      - smart_dairy_network
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U odoo_dev"]
      interval: 10s
      timeout: 5s
      retries: 5

  redis:
    image: redis:7-alpine
    container_name: smart_dairy_redis
    command: redis-server --maxmemory 256mb --maxmemory-policy allkeys-lru
    volumes:
      - redis_data:/data
    ports:
      - "6379:6379"
    networks:
      - smart_dairy_network
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5

  odoo:
    build:
      context: .
      dockerfile: Dockerfile.dev
    container_name: smart_dairy_odoo
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      HOST: db
      PORT: 5432
      USER: odoo_dev
      PASSWORD: odoo_dev_password
      REDIS_HOST: redis
      REDIS_PORT: 6379
    volumes:
      - ./smart_dairy_addons:/mnt/extra-addons
      - odoo_data:/var/lib/odoo
      - ./logs:/var/log/odoo
    ports:
      - "8069:8069"
      - "8072:8072"
    networks:
      - smart_dairy_network
    command: ["odoo", "--dev", "all"]

  nginx:
    image: nginx:alpine
    container_name: smart_dairy_nginx
    depends_on:
      - odoo
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf:ro
      - ./nginx/conf.d:/etc/nginx/conf.d:ro
    ports:
      - "80:80"
      - "443:443"
    networks:
      - smart_dairy_network

volumes:
  postgres_data:
  redis_data:
  odoo_data:

networks:
  smart_dairy_network:
    driver: bridge
```

```dockerfile
# Dockerfile.dev - Development Build
FROM odoo:19.0

USER root

# Install additional system dependencies
RUN apt-get update && apt-get install -y \
    git \
    vim \
    curl \
    wget \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Install Python debugging tools
RUN pip3 install --no-cache-dir \
    debugpy \
    ipython \
    pudb \
    pyinotify \
    watchdog

# Create directories for custom addons
RUN mkdir -p /mnt/extra-addons /var/log/odoo \
    && chown -R odoo:odoo /mnt/extra-addons /var/log/odoo

USER odoo

# Expose Odoo ports
EXPOSE 8069 8072

# Default command
CMD ["odoo"]
```

#### CI/CD Pipeline (GitHub Actions)

```yaml
# .github/workflows/ci-cd.yml
name: Smart Dairy CI/CD Pipeline

on:
  push:
    branches: [ main, develop, 'milestone-*' ]
  pull_request:
    branches: [ main, develop ]

jobs:
  lint-and-test:
    runs-on: ubuntu-24.04
    
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_USER: odoo_test
          POSTGRES_PASSWORD: odoo_test
          POSTGRES_DB: odoo_test
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
        ports:
          - 5432:5432
      
      redis:
        image: redis:7
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
        ports:
          - 6379:6379

    steps:
    - uses: actions/checkout@v4
      with:
        submodules: recursive

    - name: Set up Python
      uses: actions/setup-python@v5
      with:
        python-version: '3.11'

    - name: Cache pip packages
      uses: actions/cache@v4
      with:
        path: ~/.cache/pip
        key: ${{ runner.os }}-pip-${{ hashFiles('**/requirements.txt') }}

    - name: Install dependencies
      run: |
        python -m pip install --upgrade pip wheel
        pip install -r odoo/requirements.txt
        pip install pylint-odoo flake8 black pytest

    - name: Lint Python code
      run: |
        flake8 smart_dairy_addons --max-line-length=120 --exclude=__pycache__
        pylint --load-plugins=pylint_odoo smart_dairy_addons

    - name: Check code formatting
      run: |
        black --check smart_dairy_addons || true

    - name: Run Odoo tests
      env:
        PGHOST: localhost
        PGPORT: 5432
        PGUSER: odoo_test
        PGPASSWORD: odoo_test
      run: |
        ./odoo/odoo-bin -d odoo_test --test-enable --stop-after-init \
          --addons-path=./odoo/addons,./odoo/odoo/addons,./smart_dairy_addons \
          -i smart_core

  build-and-push:
    needs: lint-and-test
    runs-on: ubuntu-24.04
    if: github.ref == 'refs/heads/main' || github.ref == 'refs/heads/develop'
    
    steps:
    - uses: actions/checkout@v4

    - name: Set up Docker Buildx
      uses: docker/setup-buildx-action@v3

    - name: Login to Container Registry
      uses: docker/login-action@v3
      with:
        registry: ghcr.io
        username: ${{ github.actor }}
        password: ${{ secrets.GITHUB_TOKEN }}

    - name: Build and push Docker image
      uses: docker/build-push-action@v5
      with:
        context: .
        push: true
        tags: |
          ghcr.io/smartdairy/smart-dairy-erp:${{ github.sha }}
          ghcr.io/smartdairy/smart-dairy-erp:latest
        cache-from: type=gha
        cache-to: type=gha,mode=max

  deploy-staging:
    needs: build-and-push
    runs-on: ubuntu-24.04
    if: github.ref == 'refs/heads/develop'
    environment: staging
    
    steps:
    - name: Deploy to staging
      run: |
        echo "Deploying to staging environment"
        # SSH commands to deploy on staging server
        # docker pull and restart containers

  deploy-production:
    needs: build-and-push
    runs-on: ubuntu-24.04
    if: github.ref == 'refs/heads/main'
    environment: production
    
    steps:
    - name: Deploy to production
      run: |
        echo "Deploying to production environment"
        # Production deployment steps
```

#### Nginx Configuration

```nginx
# nginx/conf.d/smart_dairy.conf
upstream odoo_backend {
    server odoo:8069;
}

upstream odoo_chat {
    server odoo:8072;
}

server {
    listen 80;
    server_name smartdairybd.com www.smartdairybd.com;
    
    # Redirect HTTP to HTTPS
    location / {
        return 301 https://$host$request_uri;
    }
}

server {
    listen 443 ssl http2;
    server_name smartdairybd.com www.smartdairybd.com;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/smartdairybd.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smartdairybd.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    # Proxy timeout settings
    proxy_connect_timeout 600s;
    proxy_send_timeout 600s;
    proxy_read_timeout 600s;

    # Longpolling endpoint
    location /longpolling {
        proxy_pass http://odoo_chat;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Real-IP $remote_addr;
    }

    # Main application
    location / {
        proxy_pass http://odoo_backend;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Real-IP $remote_addr;
        
        # WebSocket support
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }

    # Static files caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        proxy_pass http://odoo_backend;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Database manager restriction (optional)
    location /web/database {
        allow 10.0.0.0/8;  # Internal network only
        deny all;
        proxy_pass http://odoo_backend;
    }
}
```

#### Success Criteria

- [ ] AWS/Azure infrastructure provisioned and accessible
- [ ] Docker containers build and run without errors
- [ ] CI/CD pipeline executes on every push
- [ ] Automated tests run and report results
- [ ] Nginx reverse proxy routes traffic correctly
- [ ] SSL certificates valid and auto-renewal configured
- [ ] Monitoring dashboards show system metrics

---

### 6.3 M3: Odoo Core Platform (Days 21-30)

#### Objective Statement

Install and configure Odoo 19 CE core modules including base functionality, implement Bangladesh localization (Chart of Accounts, VAT), configure security framework (RBAC), and set up multi-company structure.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M3-D1 | Odoo 19 CE base installation | Backend Dev | Base modules active |
| M3-D2 | Company configuration (Smart Dairy Ltd.) | Backend Dev | Company record created |
| M3-D3 | Bangladesh Chart of Accounts | Backend Dev | COA imported |
| M3-D4 | VAT configuration (Mushak forms) | Backend Dev | Tax templates ready |
| M3-D5 | Multi-currency setup (BDT, USD) | Backend Dev | Currencies configured |
| M3-D6 | Fiscal years and periods | Backend Dev | FY 2026 created |
| M3-D7 | User roles and groups | Lead Dev | RBAC structure defined |
| M3-D8 | User accounts for all staff | Lead Dev | Users created |
| M3-D9 | Security policies (password, 2FA) | Lead Dev | Security settings applied |
| M3-D10 | Audit logging configuration | Lead Dev | Audit trail active |


#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 21** | Create production database and install base modules | Backend Dev | Base modules installed |
| **Day 21** | Configure Smart Dairy Ltd. company profile | Backend Dev | Company configured |
| **Day 22** | Set up Bangladesh localization module (l10n_bd) | Backend Dev | Localization active |
| **Day 22** | Import Bangladesh Chart of Accounts | Backend Dev | COA imported |
| **Day 23** | Configure VAT taxes (5%, 10%, 15%) | Backend Dev | Tax templates ready |
| **Day 23** | Create fiscal positions for B2B/B2C | Backend Dev | Fiscal positions ready |
| **Day 24** | Set up multi-currency (BDT primary, USD secondary) | Backend Dev | Currencies active |
| **Day 24** | Configure exchange rate automation | Backend Dev | Rate update configured |
| **Day 25** | Create fiscal year 2026 (Jan-Dec) | Backend Dev | FY 2026 created |
| **Day 25** | Set up accounting periods (monthly) | Backend Dev | Periods defined |
| **Day 26** | Design RBAC structure (roles/groups) | Lead Dev | RBAC diagram |
| **Day 26** | Create custom security groups | Lead Dev | Groups created |
| **Day 27** | Create user accounts for all Smart Dairy staff | Lead Dev | Users created |
| **Day 27** | Assign roles and permissions | Lead Dev | Permissions assigned |
| **Day 28** | Configure password policies | Lead Dev | Password policy active |
| **Day 28** | Set up 2FA for admin accounts | Lead Dev | 2FA enabled |
| **Day 29** | Configure audit logging | Lead Dev | Audit trail active |
| **Day 29** | Set up session timeout policies | Lead Dev | Security policies set |
| **Day 30** | Test all security configurations | All Dev | Security tests passed |
| **Day 30** | M3 Review and documentation | Backend Dev | M3 sign-off |

#### Bangladesh Chart of Accounts Structure

```xml
<!-- smart_bd_local/data/chart_of_accounts.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- Bangladesh Chart of Accounts Template -->
    <record id="chart_template_smart_dairy_bd" model="account.chart.template">
        <field name="name">Smart Dairy - Bangladesh Chart of Accounts</field>
        <field name="code_digits">6</field>
        <field name="currency_id" ref="base.BDT"/>
        <field name="country_id" ref="base.bd"/>
        <field name="spoken_languages" eval="[(6, 0, [ref('base.lang_en_US'), ref('base.lang_bn')])]"/>
        <field name="bank_account_code_prefix">1201</field>
        <field name="cash_account_code_prefix">1202</field>
        <field name="transfer_account_code_prefix">1203</field>
    </record>

    <!-- Account Types -->
    <!-- Assets -->
    <record id="account_type_current_assets" model="account.account.type">
        <field name="name">Current Assets</field>
        <field name="type">asset_current</field>
    </record>
    
    <record id="account_type_fixed_assets" model="account.account.type">
        <field name="name">Fixed Assets</field>
        <field name="type">asset_fixed</field>
    </record>

    <!-- Liabilities -->
    <record id="account_type_current_liabilities" model="account.account.type">
        <field name="name">Current Liabilities</field>
        <field name="type">liability_current</field>
    </record>

    <!-- Equity -->
    <record id="account_type_equity" model="account.account.type">
        <field name="name">Equity</field>
        <field name="type">equity</field>
    </record>

    <!-- Income -->
    <record id="account_type_income" model="account.account.type">
        <field name="name">Income</field>
        <field name="type">income</field>
    </record>

    <!-- Expenses -->
    <record id="account_type_expenses" model="account.account.type">
        <field name="name">Expenses</field>
        <field name="type">expense</field>
    </record>

    <!-- Asset Accounts -->
    <!-- Cash and Bank -->
    <record id="acc_120101" model="account.account.template">
        <field name="code">120101</field>
        <field name="name">Cash in Hand - Main</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="reconcile">False</field>
    </record>

    <record id="acc_120102" model="account.account.template">
        <field name="code">120102</field>
        <field name="name">Cash in Hand - Farm</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_120201" model="account.account.template">
        <field name="code">120201</field>
        <field name="name">Bank Account - BRAC Bank</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_120202" model="account.account.template">
        <field name="code">120202</field>
        <field name="name">Bank Account - Dutch Bangla Bank</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <!-- Inventory -->
    <record id="acc_120301" model="account.account.template">
        <field name="code">120301</field>
        <field name="name">Raw Milk Inventory</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_120302" model="account.account.template">
        <field name="code">120302</field>
        <field name="name">Finished Goods Inventory</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_120303" model="account.account.template">
        <field name="code">120303</field>
        <field name="name">Feed and Supplies Inventory</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <!-- Receivables -->
    <record id="acc_120401" model="account.account.template">
        <field name="code">120401</field>
        <field name="name">Accounts Receivable - Trade</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="reconcile">True</field>
    </record>

    <record id="acc_120402" model="account.account.template">
        <field name="code">120402</field>
        <field name="name">Accounts Receivable - Others</field>
        <field name="account_type" ref="account_type_current_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="reconcile">True</field>
    </record>

    <!-- Fixed Assets -->
    <record id="acc_130101" model="account.account.template">
        <field name="code">130101</field>
        <field name="name">Land and Buildings</field>
        <field name="account_type" ref="account_type_fixed_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_130102" model="account.account.template">
        <field name="code">130102</field>
        <field name="name">Milking Equipment</field>
        <field name="account_type" ref="account_type_fixed_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_130103" model="account.account.template">
        <field name="code">130103</field>
        <field name="name">Processing Equipment</field>
        <field name="account_type" ref="account_type_fixed_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_130104" model="account.account.template">
        <field name="code">130104</field>
        <field name="name">Livestock (Cattle)</field>
        <field name="account_type" ref="account_type_fixed_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_130105" model="account.account.template">
        <field name="code">130105</field>
        <field name="name">Vehicles</field>
        <field name="account_type" ref="account_type_fixed_assets"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <!-- Liability Accounts -->
    <record id="acc_210101" model="account.account.template">
        <field name="code">210101</field>
        <field name="name">Accounts Payable - Trade</field>
        <field name="account_type" ref="account_type_current_liabilities"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="reconcile">True</field>
    </record>

    <record id="acc_210201" model="account.account.template">
        <field name="code">210201</field>
        <field name="name">VAT Payable (Output VAT)</field>
        <field name="account_type" ref="account_type_current_liabilities"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_210202" model="account.account.template">
        <field name="code">210202</field>
        <field name="name">VAT Receivable (Input VAT)</field>
        <field name="account_type" ref="account_type_current_liabilities"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_210301" model="account.account.template">
        <field name="code">210301</field>
        <field name="name">Tax Deducted at Source (TDS) Payable</field>
        <field name="account_type" ref="account_type_current_liabilities"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_210401" model="account.account.template">
        <field name="code">210401</field>
        <field name="name">Bank Loans - Short Term</field>
        <field name="account_type" ref="account_type_current_liabilities"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <!-- Equity Accounts -->
    <record id="acc_310101" model="account.account.template">
        <field name="code">310101</field>
        <field name="name">Share Capital</field>
        <field name="account_type" ref="account_type_equity"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_310201" model="account.account.template">
        <field name="code">310201</field>
        <field name="name">Retained Earnings</field>
        <field name="account_type" ref="account_type_equity"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <!-- Income Accounts -->
    <record id="acc_410101" model="account.account.template">
        <field name="code">410101</field>
        <field name="name">Sales - Fresh Milk</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410102" model="account.account.template">
        <field name="code">410102</field>
        <field name="name">Sales - UHT Milk</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410103" model="account.account.template">
        <field name="code">410103</field>
        <field name="name">Sales - Yogurt and Fermented</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410104" model="account.account.template">
        <field name="code">410104</field>
        <field name="name">Sales - Cheese and Butter</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410105" model="account.account.template">
        <field name="code">410105</field>
        <field name="name">Sales - Meat Products</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410201" model="account.account.template">
        <field name="code">410201</field>
        <field name="name">Sales - AI Services</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410202" model="account.account.template">
        <field name="code">410202</field>
        <field name="name">Sales - Livestock Trading</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410301" model="account.account.template">
        <field name="code">410301</field>
        <field name="name">Sales - Equipment</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_410401" model="account.account.template">
        <field name="code">410401</field>
        <field name="name">Sales - Training Services</field>
        <field name="account_type" ref="account_type_income"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <!-- Expense Accounts -->
    <record id="acc_510101" model="account.account.template">
        <field name="code">510101</field>
        <field name="name">Cost of Goods Sold - Milk</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520101" model="account.account.template">
        <field name="code">520101</field>
        <field name="name">Feed and Nutrition</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520102" model="account.account.template">
        <field name="code">520102</field>
        <field name="name">Veterinary and Medicine</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520103" model="account.account.template">
        <field name="code">520103</field>
        <field name="name">Breeding Services</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520201" model="account.account.template">
        <field name="code">520201</field>
        <field name="name">Salaries and Wages</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520301" model="account.account.template">
        <field name="code">520301</field>
        <field name="name">Electricity and Utilities</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520302" model="account.account.template">
        <field name="code">520302</field>
        <field name="name">Fuel and Transportation</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520401" model="account.account.template">
        <field name="code">520401</field>
        <field name="name">Maintenance and Repairs</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520501" model="account.account.template">
        <field name="code">520501</field>
        <field name="name">Marketing and Advertising</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520601" model="account.account.template">
        <field name="code">520601</field>
        <field name="name">Office and Administrative</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

    <record id="acc_520701" model="account.account.template">
        <field name="code">520701</field>
        <field name="name">Depreciation Expense</field>
        <field name="account_type" ref="account_type_expenses"/>
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
    </record>

</odoo>
```

#### VAT Configuration for Bangladesh

```xml
<!-- smart_bd_local/data/vat_templates.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- Bangladesh VAT Tax Groups -->
    <record id="tax_group_vat_5" model="account.tax.group">
        <field name="name">VAT 5%</field>
        <field name="sequence">10</field>
    </record>

    <record id="tax_group_vat_10" model="account.tax.group">
        <field name="name">VAT 10%</field>
        <field name="sequence">20</field>
    </record>

    <record id="tax_group_vat_15" model="account.tax.group">
        <field name="name">VAT 15%</field>
        <field name="sequence">30</field>
    </record>

    <record id="tax_group_vat_exempt" model="account.tax.group">
        <field name="name">VAT Exempt</field>
        <field name="sequence">40</field>
    </record>

    <!-- VAT 5% - Reduced Rate (Essential goods) -->
    <record id="vat_sale_5" model="account.tax.template">
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="name">VAT 5% (Sales)</field>
        <field name="description">VAT 5%</field>
        <field name="amount">5</field>
        <field name="amount_type">percent</field>
        <field name="type_tax_use">sale</field>
        <field name="tax_group_id" ref="tax_group_vat_5"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210201')}),
        ]"/>
        <field name="refund_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210201')}),
        ]"/>
    </record>

    <record id="vat_purchase_5" model="account.tax.template">
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="name">VAT 5% (Purchase)</field>
        <field name="description">VAT 5%</field>
        <field name="amount">5</field>
        <field name="amount_type">percent</field>
        <field name="type_tax_use">purchase</field>
        <field name="tax_group_id" ref="tax_group_vat_5"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210202')}),
        ]"/>
        <field name="refund_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210202')}),
        ]"/>
    </record>

    <!-- VAT 15% - Standard Rate (Most goods and services) -->
    <record id="vat_sale_15" model="account.tax.template">
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="name">VAT 15% (Sales)</field>
        <field name="description">VAT 15%</field>
        <field name="amount">15</field>
        <field name="amount_type">percent</field>
        <field name="type_tax_use">sale</field>
        <field name="tax_group_id" ref="tax_group_vat_15"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210201')}),
        ]"/>
        <field name="refund_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210201')}),
        ]"/>
    </record>

    <record id="vat_purchase_15" model="account.tax.template">
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="name">VAT 15% (Purchase)</field>
        <field name="description">VAT 15%</field>
        <field name="amount">15</field>
        <field name="amount_type">percent</field>
        <field name="type_tax_use">purchase</field>
        <field name="tax_group_id" ref="tax_group_vat_15"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210202')}),
        ]"/>
        <field name="refund_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
            (0,0, {'repartition_type': 'tax', 'account_id': ref('acc_210202')}),
        ]"/>
    </record>

    <!-- VAT Exempt (Basic food items, milk products) -->
    <record id="vat_sale_exempt" model="account.tax.template">
        <field name="chart_template_id" ref="chart_template_smart_dairy_bd"/>
        <field name="name">VAT Exempt (Sales)</field>
        <field name="description">VAT Exempt</field>
        <field name="amount">0</field>
        <field name="amount_type">percent</field>
        <field name="type_tax_use">sale</field>
        <field name="tax_group_id" ref="tax_group_vat_exempt"/>
        <field name="invoice_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
        ]"/>
        <field name="refund_repartition_line_ids" eval="[
            (0,0, {'repartition_type': 'base'}),
        ]"/>
    </record>

</odoo>
```

#### RBAC Security Structure

```xml
<!-- smart_core/security/smart_security.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <!-- Smart Dairy Security Groups -->
    
    <!-- Farm Management Groups -->
    <record id="group_farm_worker" model="res.groups">
        <field name="name">Farm Worker</field>
        <field name="category_id" ref="base.module_category_operations"/>
        <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
        <field name="comment">Basic farm operations - data entry for animals, milk production</field>
    </record>

    <record id="group_farm_supervisor" model="res.groups">
        <field name="name">Farm Supervisor</field>
        <field name="category_id" ref="base.module_category_operations"/>
        <field name="implied_ids" eval="[(4, ref('group_farm_worker'))]"/>
        <field name="comment">Farm management - full access to farm module, reports</field>
    </record>

    <record id="group_farm_manager" model="res.groups">
        <field name="name">Farm Manager</field>
        <field name="category_id" ref="base.module_category_operations"/>
        <field name="implied_ids" eval="[(4, ref('group_farm_supervisor'))]"/>
        <field name="comment">Full farm administration - configuration, analytics</field>
    </record>

    <!-- Sales Groups -->
    <record id="group_sales_representative" model="res.groups">
        <field name="name">Sales Representative</field>
        <field name="category_id" ref="base.module_category_sales"/>
        <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
    </record>

    <record id="group_sales_manager" model="res.groups">
        <field name="name">Sales Manager</field>
        <field name="category_id" ref="base.module_category_sales"/>
        <field name="implied_ids" eval="[(4, ref('group_sales_representative'))]"/>
    </record>

    <!-- Inventory/Warehouse Groups -->
    <record id="group_warehouse_staff" model="res.groups">
        <field name="name">Warehouse Staff</field>
        <field name="category_id" ref="base.module_category_warehouse"/>
        <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
    </record>

    <record id="group_warehouse_manager" model="res.groups">
        <field name="name">Warehouse Manager</field>
        <field name="category_id" ref="base.module_category_warehouse"/>
        <field name="implied_ids" eval="[(4, ref('group_warehouse_staff'))]"/>
    </record>

    <!-- Accounting Groups -->
    <record id="group_accountant" model="res.groups">
        <field name="name">Accountant</field>
        <field name="category_id" ref="base.module_category_accounting"/>
        <field name="implied_ids" eval="[(4, ref('base.group_user'))]"/>
    </record>

    <record id="group_finance_manager" model="res.groups">
        <field name="name">Finance Manager</field>
        <field name="category_id" ref="base.module_category_accounting"/>
        <field name="implied_ids" eval="[(4, ref('group_accountant'))]"/>
    </record>

    <!-- IT/Admin Groups -->
    <record id="group_system_admin" model="res.groups">
        <field name="name">Smart Dairy Administrator</field>
        <field name="category_id" ref="base.module_category_administration"/>
        <field name="implied_ids" eval="[
            (4, ref('base.group_erp_manager')),
            (4, ref('group_farm_manager')),
            (4, ref('group_sales_manager')),
            (4, ref('group_warehouse_manager')),
            (4, ref('group_finance_manager')),
        ]"/>
        <field name="users" eval="[(4, ref('base.user_root'))]"/>
    </record>

</odoo>
```

#### Success Criteria

- [ ] Odoo 19 CE base modules installed and running
- [ ] Bangladesh Chart of Accounts with 40+ accounts configured
- [ ] VAT taxes (5%, 15%, exempt) configured and tested
- [ ] Multi-currency (BDT, USD) working
- [ ] All user roles and permissions assigned
- [ ] Password policies enforced (min 8 chars, complexity)
- [ ] 2FA enabled for admin accounts
- [ ] Audit logging captures all data changes

---

### 6.4 M4: Public Website Foundation (Days 31-40)

#### Objective Statement

Deploy and configure Odoo Website module with custom Smart Dairy theme, implement multi-language support (English/Bengali), create responsive design foundation, and set up CMS capabilities.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M4-D1 | Website module installation | Frontend Dev | Module active |
| M4-D2 | Custom theme creation | Frontend Dev | Theme installed |
| M4-D3 | Multi-language setup (EN/BN) | Frontend Dev | Language switcher working |
| M4-D4 | Homepage design and layout | Frontend Dev | Homepage live |
| M4-D5 | Navigation and menu structure | Frontend Dev | Navigation functional |
| M4-D6 | Responsive design implementation | Frontend Dev | Mobile testing passed |
| M4-D7 | Header and footer components | Frontend Dev | Components reusable |
| M4-D8 | Static page templates | Frontend Dev | Templates available |
| M4-D9 | SEO foundation (meta tags, sitemap) | Frontend Dev | SEO ready |
| M4-D10 | Performance optimization | Frontend Dev | <3s load time |


#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 31** | Install Odoo website and related modules | Frontend Dev | Website module installed |
| **Day 31** | Configure website basic settings | Frontend Dev | Website configured |
| **Day 32** | Create custom theme structure | Frontend Dev | Theme scaffolded |
| **Day 32** | Set up theme manifest and dependencies | Frontend Dev | Manifest created |
| **Day 33** | Install and configure website language (English) | Frontend Dev | EN active |
| **Day 33** | Install Bengali language pack | Frontend Dev | BN active |
| **Day 34** | Configure language switcher | Frontend Dev | Switcher working |
| **Day 34** | Translate basic website strings | Frontend Dev | Base translations |
| **Day 35** | Design homepage layout and sections | Frontend Dev | Homepage mockup |
| **Day 35** | Implement hero section | Frontend Dev | Hero section live |
| **Day 36** | Implement featured products section | Frontend Dev | Products section live |
| **Day 36** | Implement about/farm section | Frontend Dev | About section live |
| **Day 37** | Create navigation menu structure | Frontend Dev | Menu working |
| **Day 37** | Implement mobile-responsive navigation | Frontend Dev | Mobile nav working |
| **Day 38** | Design and implement header | Frontend Dev | Header component |
| **Day 38** | Design and implement footer | Frontend Dev | Footer component |
| **Day 39** | Create reusable page templates | Frontend Dev | Templates ready |
| **Day 39** | Configure SEO meta tags and sitemap | Frontend Dev | SEO configured |
| **Day 40** | Performance testing and optimization | Frontend Dev | Performance targets met |
| **Day 40** | M4 Review and documentation | Frontend Dev | M4 sign-off |

#### Website Theme Structure

```xml
<!-- smart_website_ext/__manifest__.py -->
{
    'name': 'Smart Dairy Website Theme',
    'version': '1.0.0',
    'category': 'Website/Theme',
    'summary': 'Custom theme for Smart Dairy public website',
    'description': """
        Custom website theme for Smart Dairy Ltd.
        Features:
        - Responsive design
        - Bengali and English language support
        - Farm-to-table visual identity
        - Product catalog integration
        - SEO optimized
    """,
    'author': 'Smart Dairy Technical Team',
    'website': 'https://smartdairybd.com',
    'depends': [
        'website',
        'website_sale',
        'website_blog',
        'website_crm',
    ],
    'data': [
        # Security
        'security/ir.model.access.csv',
        
        # Views
        'views/layout.xml',
        'views/header.xml',
        'views/footer.xml',
        'views/homepage.xml',
        'views/about.xml',
        'views/products.xml',
        'views/farm.xml',
        'views/contact.xml',
        
        # Snippets
        'views/snippets/snippets.xml',
        'views/snippets/s_hero.xml',
        'views/snippets/s_features.xml',
        'views/snippets/s_products_grid.xml',
        'views/snippets/s_testimonials.xml',
        'views/snippets/s_farm_gallery.xml',
        
        # Templates
        'views/pages.xml',
        
        # Data
        'data/website_data.xml',
        'data/menu_data.xml',
        'data/pages_data.xml',
        
        # Configuration
        'data/ir_asset.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            # Bootstrap Custom
            'smart_website_ext/static/src/scss/bootstrap_overrides.scss',
            
            # Theme Styles
            'smart_website_ext/static/src/scss/variables.scss',
            'smart_website_ext/static/src/scss/theme.scss',
            'smart_website_ext/static/src/scss/header.scss',
            'smart_website_ext/static/src/scss/footer.scss',
            'smart_website_ext/static/src/scss/homepage.scss',
            'smart_website_ext/static/src/scss/snippets.scss',
            'smart_website_ext/static/src/scss/responsive.scss',
            
            # Fonts
            'smart_website_ext/static/src/fonts/inter.css',
            'smart_website_ext/static/src/fonts/noto_sans_bengali.css',
            
            # JavaScript
            'smart_website_ext/static/src/js/theme.js',
            'smart_website_ext/static/src/js/navigation.js',
            'smart_website_ext/static/src/js/language_switcher.js',
        ],
        'web._assets_primary_variables': [
            'smart_website_ext/static/src/scss/primary_variables.scss',
        ],
    },
    'images': ['static/description/banner.png'],
    'installable': True,
    'application': False,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

#### Homepage Layout Template

```xml
<!-- smart_website_ext/views/homepage.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <template id="homepage" name="Smart Dairy Homepage">
        <t t-call="website.layout">
            <!-- Hero Section -->
            <section class="s_hero o_colored_level pt120 pb120" data-snippet="s_hero">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="display-3 fw-bold mb-4">
                                <span t-field="website.company_id.name"/>
                            </h1>
                            <p class="lead fs-4 mb-4">
                                Pure, organic dairy products from our farm to your table.
                                Experience the freshness of 100% natural milk and dairy.
                            </p>
                            <div class="d-flex gap-3">
                                <a href="/shop" class="btn btn-primary btn-lg px-4">
                                    Shop Now
                                </a>
                                <a href="/about" class="btn btn-outline-primary btn-lg px-4">
                                    Our Story
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <img src="/smart_website_ext/static/src/img/hero-milk-bottles.jpg" 
                                 class="img-fluid rounded-4 shadow-lg" 
                                 alt="Fresh Dairy Products"/>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="s_features py-5 bg-light">
                <div class="container">
                    <div class="row text-center mb-5">
                        <div class="col-lg-8 mx-auto">
                            <h2 class="h1 mb-3">Why Choose Smart Dairy?</h2>
                            <p class="lead text-muted">
                                We combine traditional farming wisdom with modern technology 
                                to bring you the finest dairy products.
                            </p>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon mb-3">
                                        <i class="fa fa-leaf fa-3x text-success"/>
                                    </div>
                                    <h4 class="card-title">100% Organic</h4>
                                    <p class="card-text text-muted">
                                        No antibiotics, hormones, or preservatives. 
                                        Pure natural dairy from grass-fed cattle.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon mb-3">
                                        <i class="fa fa-truck fa-3x text-primary"/>
                                    </div>
                                    <h4 class="card-title">Farm Fresh Delivery</h4>
                                    <p class="card-text text-muted">
                                        Direct from our farm to your doorstep. 
                                        Cold-chain preserved for maximum freshness.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon mb-3">
                                        <i class="fa fa-certificate fa-3x text-warning"/>
                                    </div>
                                    <h4 class="card-title">Quality Certified</h4>
                                    <p class="card-text text-muted">
                                        BSTI certified and quality tested. 
                                        Traceable from farm to fork.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Products Preview Section -->
            <section class="s_products_preview py-5">
                <div class="container">
                    <div class="row text-center mb-5">
                        <div class="col-lg-8 mx-auto">
                            <h2 class="h1 mb-3">Our Products</h2>
                            <p class="lead text-muted">
                                Discover our range of premium dairy products
                            </p>
                        </div>
                    </div>
                    <div class="row g-4" t-if="products">
                        <t t-foreach="products" t-as="product">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="product-card card border-0 shadow-sm h-100">
                                    <a t-att-href="product.website_url" class="text-decoration-none">
                                        <div class="product-image position-relative">
                                            <img t-att-src="image_data_uri(product.image_256)" 
                                                 class="card-img-top" 
                                                 t-att-alt="product.name"/>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-dark" t-esc="product.name"/>
                                            <p class="card-text text-primary fw-bold">
                                                <span t-esc="product.list_price" 
                                                      t-options="{'widget': 'monetary', 'display_currency': website.currency_id}"/>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </t>
                    </div>
                    <div class="text-center mt-5">
                        <a href="/shop" class="btn btn-primary btn-lg px-5">
                            View All Products
                        </a>
                    </div>
                </div>
            </section>

            <!-- Farm Section -->
            <section class="s_farm py-5 bg-light">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <img src="/smart_website_ext/static/src/img/farm-aerial.jpg" 
                                 class="img-fluid rounded-4 shadow" 
                                 alt="Smart Dairy Farm"/>
                        </div>
                        <div class="col-lg-6">
                            <h2 class="h1 mb-4">Our Farm</h2>
                            <p class="lead mb-4">
                                Located in the pristine countryside of Narayanganj, our farm spans 
                                acres of lush green pasture where our cattle graze freely.
                            </p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"/>
                                    255+ cattle in our herd
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"/>
                                    Modern milking parlors
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"/>
                                    State-of-the-art processing facility
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"/>
                                    Sustainable farming practices
                                </li>
                            </ul>
                            <a href="/farm" class="btn btn-outline-primary">
                                Learn More About Our Farm
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="s_cta py-5 bg-primary text-white">
                <div class="container text-center">
                    <h2 class="h1 mb-4">Ready to Experience Pure Dairy?</h2>
                    <p class="lead mb-4">
                        Subscribe to our daily milk delivery and enjoy farm-fresh products every morning.
                    </p>
                    <a href="/shop?search=subscription" class="btn btn-light btn-lg px-5">
                        Start Subscription
                    </a>
                </div>
            </section>
        </t>
    </template>
</odoo>
```

#### Language Switcher Implementation

```javascript
// smart_website_ext/static/src/js/language_switcher.js
/** @odoo-module **/

import publicWidget from "@web/legacy/js/public/public_widget";
import { browser } from "@web/core/browser/browser";

publicWidget.registry.LanguageSwitcher = publicWidget.Widget.extend({
    selector: '.js_language_selector',
    events: {
        'click .dropdown-item': '_onLanguageClick',
    },

    _onLanguageClick: function (ev) {
        ev.preventDefault();
        const $target = $(ev.currentTarget);
        const langCode = $target.data('lang-code');
        const currentUrl = new URL(window.location.href);
        
        // Update URL with language prefix
        const pathParts = currentUrl.pathname.split('/');
        const langPrefix = pathParts[1];
        
        // Check if current path has language prefix
        if (['en_US', 'bn_BD'].includes(langPrefix)) {
            pathParts[1] = langCode;
        } else {
            pathParts.splice(1, 0, langCode);
        }
        
        currentUrl.pathname = pathParts.join('/');
        
        // Store language preference
        browser.localStorage.setItem('smart_dairy_language', langCode);
        
        // Navigate to new URL
        window.location.href = currentUrl.toString();
    },
});

export default publicWidget.registry.LanguageSwitcher;
```

#### Success Criteria

- [ ] Website accessible at https://smartdairybd.com
- [ ] Language switcher functional (EN/BN)
- [ ] Homepage loads in <3 seconds
- [ ] Responsive design working on mobile/tablet/desktop
- [ ] SEO meta tags configured for all pages
- [ ] XML sitemap generated and accessible
- [ ] All static assets (CSS/JS/images) optimized and cached

---

### 6.5 M5: Website Content & Features (Days 41-50)

#### Objective Statement

Populate website with content (company info, products, farm details), implement contact forms, create product catalog pages, set up blog functionality, and integrate analytics.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M5-D1 | Company about page | Frontend Dev | Page live with content |
| M5-D2 | Product catalog pages | Frontend Dev | All products listed |
| M5-D3 | Farm information pages | Frontend Dev | Farm pages complete |
| M5-D4 | Contact forms (general, inquiry) | Frontend Dev | Forms functional |
| M5-D5 | Store locator integration | Frontend Dev | Map integration working |
| M5-D6 | FAQ and knowledge base | Frontend Dev | FAQ page published |
| M5-D7 | Blog setup and initial posts | Frontend Dev | Blog live |
| M5-D8 | Google Analytics integration | Frontend Dev | Tracking active |
| M5-D9 | Social media integration | Frontend Dev | Social links working |
| M5-D10 | Content review and UAT | All Dev | Stakeholder sign-off |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 41** | Create and populate About Us page | Frontend Dev | About page live |
| **Day 41** | Create Mission/Vision content | Frontend Dev | Content published |
| **Day 42** | Create team/leadership section | Frontend Dev | Team page live |
| **Day 42** | Add certifications and awards | Frontend Dev | Credentials displayed |
| **Day 43** | Set up product categories | Frontend Dev | Categories configured |
| **Day 43** | Create product detail templates | Frontend Dev | Templates ready |
| **Day 44** | Add initial 15 products | Frontend Dev | Products published |
| **Day 44** | Product images and descriptions | Frontend Dev | Content complete |
| **Day 45** | Create farm overview page | Frontend Dev | Farm page live |
| **Day 45** | Add farm facilities content | Frontend Dev | Facilities documented |
| **Day 46** | Create contact form | Frontend Dev | Form functional |
| **Day 46** | Create product inquiry form | Frontend Dev | Inquiry form ready |
| **Day 47** | Integrate Google Maps for store locator | Frontend Dev | Map working |
| **Day 47** | Add location details | Frontend Dev | Locations listed |
| **Day 48** | Create FAQ page | Frontend Dev | FAQ published |
| **Day 48** | Set up blog module | Frontend Dev | Blog active |
| **Day 49** | Write and publish 3 initial blog posts | Frontend Dev | Posts live |
| **Day 49** | Configure Google Analytics 4 | Frontend Dev | Analytics tracking |
| **Day 50** | Add social media links and sharing | Frontend Dev | Social integration |
| **Day 50** | Content review and stakeholder UAT | All Dev | M5 sign-off |

#### Success Criteria

- [ ] 50+ pages of content published
- [ ] All 15 products listed with images and descriptions
- [ ] Contact forms submitting to CRM
- [ ] Store locator with 5+ locations
- [ ] Google Analytics receiving data
- [ ] Social media integration complete

---

### 6.6 M6: Core ERP Modules - Part 1 (Days 51-60)

#### Objective Statement

Configure and deploy Inventory Management and Accounting/Finance modules with Bangladesh-specific requirements including multi-location inventory, batch tracking, and VAT compliance.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M6-D1 | Inventory module configuration | Backend Dev | Stock module active |
| M6-D2 | Warehouse structure setup | Backend Dev | Locations defined |
| M6-D3 | Product categories | Backend Dev | Category hierarchy |
| M6-D4 | Units of measure | Backend Dev | UOM configured |
| M6-D5 | Inventory operations | Backend Dev | Receipts, deliveries |
| M6-D6 | Accounting module full config | Backend Dev | Full accounting |
| M6-D7 | Bank accounts setup | Backend Dev | Bank journals |
| M6-D8 | Opening balances entry | Backend Dev | Balance migration |
| M6-D9 | Financial reports | Backend Dev | P&L, Balance Sheet |
| M6-D10 | Integration testing | Backend Dev | End-to-end tests |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 51** | Inventory module installation | Backend Dev | Stock module ready |
| **Day 51** | Warehouse creation | Backend Dev | Main warehouse |
| **Day 52** | Location structure | Backend Dev | Zones, bins, racks |
| **Day 52** | Operation types | Backend Dev | Receipts, deliveries |
| **Day 53** | Product categories | Backend Dev | Dairy, Meat, etc. |
| **Day 53** | Units of measure | Backend Dev | Liter, KG, Piece |
| **Day 54** | Inventory routes | Backend Dev | Push, pull rules |
| **Day 54** | Reordering rules | Backend Dev | Auto replenishment |
| **Day 55** | Accounting module deep config | Backend Dev | Full COA active |
| **Day 55** | Account groups | Backend Dev | Financial reports |
| **Day 56** | Bank accounts | Backend Dev | Bank journals |
| **Day 56** | Payment methods | Backend Dev | Cash, Bank, MFS |
| **Day 57** | Opening balances | Backend Dev | Migration data |
| **Day 57** | Initial journal entries | Backend Dev | Opening entry |
| **Day 58** | Financial reports config | Backend Dev | BD format reports |
| **Day 58** | VAT reports setup | Backend Dev | Mushak forms |
| **Day 59** | Integration testing | Backend Dev | Inventory-Accounting |
| **Day 59** | User training materials | Backend Dev | Training docs |
| **Day 60** | M6 Demo and Review | All | ERP demo |
| **Day 60** | UAT with finance team | Backend Dev | Feedback collected |

#### Warehouse Structure

| Level | Code | Name |
|-------|------|------|
| Warehouse | WH | Smart Dairy Main Warehouse |
| View | WH/STOCK | Stock |
| Location | WH/STOCK/RAW | Raw Materials |
| Location | WH/STOCK/FP | Finished Products |
| Location | WH/STOCK/QC | Quality Control |
| Location | WH/STOCK/SCRAP | Scrap |
| Location | WH/RECEIPTS | Receipts |
| Location | WH/DELIVERY | Delivery Orders |

#### Success Criteria

- [ ] Inventory module configured with multi-location support
- [ ] Product categories and UOMs defined
- [ ] Warehouse operations (receipts, deliveries) working
- [ ] Accounting module fully configured per Bangladesh standards
- [ ] Bank accounts and payment methods set up
- [ ] Opening balances entered and validated
- [ ] Financial reports (P&L, Balance Sheet) generating correctly
- [ ] VAT reports configured for Mushak forms

---

### 6.7 M7: Core ERP Modules - Part 2 (Days 61-70)

#### Objective Statement

Configure and deploy Sales & CRM and Purchase modules enabling complete quote-to-cash and procure-to-pay business processes with Bangladesh-specific customizations.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M7-D1 | Sales module configuration | Backend Dev | sale module active |
| M7-D2 | CRM pipeline setup | Backend Dev | CRM stages |
| M7-D3 | Quotation templates | Backend Dev | Branded quotes |
| M7-D4 | Sales teams | Backend Dev | Team structure |
| M7-D5 | Pricing and discounts | Backend Dev | Price lists |
| M7-D6 | Purchase module configuration | Backend Dev | purchase module |
| M7-D7 | Supplier management | Backend Dev | Vendor master |
| M7-D8 | Purchase approval workflow | Backend Dev | Approval rules |
| M7-D9 | 3-way matching | Backend Dev | Matching rules |
| M7-D10 | End-to-end process testing | Backend Dev | Full cycle tested |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 61** | Sales module installation | Backend Dev | Sales module ready |
| **Day 61** | Sales team configuration | Backend Dev | Teams created |
| **Day 62** | CRM pipeline stages | Backend Dev | Pipeline defined |
| **Day 62** | Lead qualification process | Backend Dev | Lead flow ready |
| **Day 63** | Quotation templates | Backend Dev | Templates created |
| **Day 63** | Sales order workflow | Backend Dev | SO workflow |
| **Day 64** | Price lists (B2C, B2B) | Backend Dev | Pricing ready |
| **Day 64** | Discount structures | Backend Dev | Discounts configured |
| **Day 65** | Purchase module installation | Backend Dev | Purchase module |
| **Day 65** | Supplier categories | Backend Dev | Vendor categories |
| **Day 66** | Supplier onboarding form | Backend Dev | Vendor form |
| **Day 66** | Purchase requisition workflow | Backend Dev | PR workflow |
| **Day 67** | Purchase order approval rules | Backend Dev | PO approvals |
| **Day 67** | 3-way matching config | Backend Dev | Matching active |
| **Day 68** | Vendor price lists | Backend Dev | Vendor pricing |
| **Day 68** | Receipt and quality check | Backend Dev | Receipt workflow |
| **Day 69** | End-to-end sales process test | Backend Dev | Sales cycle tested |
| **Day 69** | End-to-end purchase process test | Backend Dev | Purchase cycle tested |
| **Day 70** | M7 Review and UAT | All | Process demo |
| **Day 70** | Sales/Purchase team training | Backend Dev | Training delivered |

#### CRM Pipeline Stages

| Stage | Probability | Requirements |
|-------|-------------|--------------|
| New Lead | 10% | Contact info captured |
| Qualified | 25% | Need identified, budget confirmed |
| Proposal Sent | 50% | Quotation delivered |
| Negotiation | 75% | Terms under discussion |
| Won | 100% | Order confirmed |
| Lost | 0% | Opportunity closed |

#### Sales Approval Matrix

| Amount | Approver |
|--------|----------|
| < BDT 10,000 | Sales Executive |
| BDT 10,000 - 50,000 | Sales Manager |
| BDT 50,000 - 200,000 | Finance Manager |
| > BDT 200,000 | Managing Director |

#### Success Criteria

- [ ] Sales CRM with complete lead-to-order process
- [ ] Quotation templates with Smart Dairy branding
- [ ] Sales teams and targets configured
- [ ] Purchase requisition to payment process
- [ ] Supplier database with evaluation criteria
- [ ] 3-way matching (PO-Receipt-Invoice) working

---

### 6.8 M8: Farm Management Module (Days 71-80)

#### Objective Statement

Develop and deploy custom Farm Management module for Odoo 19 including animal registry, breeding management, milk production tracking, health records, and vaccination schedules.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M8-D1 | Animal master data model | Backend Dev | Model defined |
| M8-D2 | Animal registration interface | Backend Dev | Form working |
| M8-D3 | RFID/ear tag integration | Backend Dev | Scanning tested |
| M8-D4 | Breeding management | Backend Dev | Breeding records |
| M8-D5 | Milk production recording | Backend Dev | Daily milk entry |
| M8-D6 | Health management | Backend Dev | Health records |
| M8-D7 | Vaccination schedules | Backend Dev | Schedules active |
| M8-D8 | Farm reports and analytics | Backend Dev | Reports generated |
| M8-D9 | Mobile-friendly interface | Frontend Dev | Mobile optimized |
| M8-D10 | Data migration (255 animals) | Backend Dev | All animals imported |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 71** | Create farm management module structure | Backend Dev | Module scaffolded |
| **Day 71** | Define animal data models | Backend Dev | Models defined |
| **Day 72** | Implement animal master model | Backend Dev | Animal model ready |
| **Day 72** | Create animal registration form | Backend Dev | Form working |
| **Day 73** | RFID and ear tag fields | Backend Dev | ID fields ready |
| **Day 73** | Animal lifecycle states | Backend Dev | States defined |
| **Day 74** | Breeding model (heat, insemination) | Backend Dev | Breeding model |
| **Day 74** | Pregnancy tracking | Backend Dev | Pregnancy records |
| **Day 75** | Calving records | Backend Dev | Calving events |
| **Day 75** | Milk production model | Backend Dev | Production model |
| **Day 76** | Daily milk entry interface | Backend Dev | Entry form ready |
| **Day 76** | Milk quality (Fat, SNF) tracking | Backend Dev | Quality fields |
| **Day 77** | Health event model | Backend Dev | Health model |
| **Day 77** | Treatment and medication records | Backend Dev | Treatment records |
| **Day 78** | Vaccination schedule model | Backend Dev | Vaccination model |
| **Day 78** | Automated schedule generation | Backend Dev | Schedules auto-create |
| **Day 79** | Farm dashboard and reports | Backend Dev | Reports ready |
| **Day 79** | Mobile-responsive views | Frontend Dev | Mobile UI ready |
| **Day 80** | Import all 255 animals | Backend Dev | Data imported |
| **Day 80** | M8 Review and farm team training | All | Module demo |

#### Animal Data Model

```python
# smart_farm_mgmt/models/animal.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta

class FarmAnimal(models.Model):
    _name = 'smart.farm.animal'
    _description = 'Farm Animal'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name'

    # Identification
    name = fields.Char(string='Animal ID', required=True, tracking=True)
    rfid_tag = fields.Char(string='RFID Tag', tracking=True)
    ear_tag_number = fields.Char(string='Ear Tag Number', tracking=True)
    
    # Basic Information
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
    ], string='Species', default='cattle', required=True)
    
    breed_id = fields.Many2one('smart.farm.breed', string='Breed', required=True)
    gender = fields.Selection([
        ('male', 'Male'),
        ('female', 'Female'),
    ], string='Gender', required=True)
    
    # Lifecycle
    status = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating Cow'),
        ('dry', 'Dry Cow'),
        ('bull', 'Bull'),
        ('sold', 'Sold'),
        ('deceased', 'Deceased'),
    ], string='Status', default='calf', tracking=True)
    
    birth_date = fields.Date(string='Birth Date')
    age_years = fields.Integer(string='Age (Years)', compute='_compute_age', store=True)
    age_months = fields.Integer(string='Age (Months)', compute='_compute_age', store=True)
    
    # Acquisition
    acquisition_type = fields.Selection([
        ('born', 'Born on Farm'),
        ('purchased', 'Purchased'),
    ], string='Acquisition Type', default='born')
    acquisition_date = fields.Date(string='Acquisition Date')
    purchase_price = fields.Float(string='Purchase Price')
    
    # Location
    barn_id = fields.Many2one('smart.farm.barn', string='Barn/Location')
    group_id = fields.Many2one('smart.farm.animal.group', string='Animal Group')
    
    # Physical
    weight_kg = fields.Float(string='Weight (kg)', tracking=True)
    body_condition_score = fields.Selection([
        ('1', '1 - Very Thin'),
        ('2', '2 - Thin'),
        ('3', '3 - Ideal'),
        ('4', '4 - Fat'),
        ('5', '5 - Obese'),
    ], string='Body Condition Score')
    
    # Lactation
    lactation_number = fields.Integer(string='Lactation Number', default=0)
    current_lactation_start = fields.Date(string='Current Lactation Start')
    days_in_milk = fields.Integer(string='Days in Milk', compute='_compute_dim')
    
    # Production
    total_milk_production_lifetime = fields.Float(
        string='Lifetime Milk Production (L)',
        compute='_compute_lifetime_production'
    )
    avg_daily_production = fields.Float(
        string='Average Daily Production (L)',
        compute='_compute_avg_production'
    )
    
    # Related Records
    breeding_ids = fields.One2many('smart.farm.breeding', 'animal_id', string='Breeding Records')
    milk_production_ids = fields.One2many('smart.farm.milk.production', 'animal_id', string='Milk Production')
    health_ids = fields.One2many('smart.farm.health', 'animal_id', string='Health Records')
    vaccination_ids = fields.One2many('smart.farm.vaccination', 'animal_id', string='Vaccinations')
    weight_history_ids = fields.One2many('smart.farm.weight.history', 'animal_id', string='Weight History')
    
    # Computed Fields
    last_heat_date = fields.Date(string='Last Heat Date', compute='_compute_last_heat')
    is_pregnant = fields.Boolean(string='Is Pregnant', compute='_compute_pregnancy_status')
    expected_calving_date = fields.Date(string='Expected Calving Date', compute='_compute_calving_date')
    
    # Image
    image = fields.Binary(string='Photo')
    
    # Active
    active = fields.Boolean(default=True)
    
    @api.depends('birth_date')
    def _compute_age(self):
        for animal in self:
            if animal.birth_date:
                today = fields.Date.today()
                delta = today - animal.birth_date
                animal.age_years = delta.days // 365
                animal.age_months = (delta.days % 365) // 30
            else:
                animal.age_years = 0
                animal.age_months = 0
    
    @api.depends('current_lactation_start')
    def _compute_dim(self):
        for animal in self:
            if animal.current_lactation_start:
                animal.days_in_milk = (fields.Date.today() - animal.current_lactation_start).days
            else:
                animal.days_in_milk = 0
    
    @api.depends('milk_production_ids.quantity_liters')
    def _compute_lifetime_production(self):
        for animal in self:
            animal.total_milk_production_lifetime = sum(
                animal.milk_production_ids.mapped('quantity_liters')
            )
    
    @api.depends('milk_production_ids')
    def _compute_avg_production(self):
        for animal in self:
            if animal.milk_production_ids:
                animal.avg_daily_production = sum(
                    animal.milk_production_ids.mapped('quantity_liters')
                ) / len(animal.milk_production_ids)
            else:
                animal.avg_daily_production = 0
    
    @api.depends('breeding_ids')
    def _compute_last_heat(self):
        for animal in self:
            heats = animal.breeding_ids.filtered(lambda x: x.event_type == 'heat').sorted('date', reverse=True)
            animal.last_heat_date = heats[0].date if heats else False
    
    @api.depends('breeding_ids')
    def _compute_pregnancy_status(self):
        for animal in self:
            pregnancies = animal.breeding_ids.filtered(
                lambda x: x.event_type == 'pregnancy_check' and x.result == 'positive'
            ).sorted('date', reverse=True)
            if pregnancies:
                latest = pregnancies[0]
                # Check if pregnancy is still valid (not calved or aborted)
                calving = animal.breeding_ids.filtered(
                    lambda x: x.event_type == 'calving' and x.date > latest.date
                )
                animal.is_pregnant = not bool(calving)
            else:
                animal.is_pregnant = False
    
    @api.depends('breeding_ids')
    def _compute_calving_date(self):
        for animal in self:
            pregnancies = animal.breeding_ids.filtered(
                lambda x: x.event_type == 'pregnancy_check' and x.result == 'positive'
            ).sorted('date', reverse=True)
            if pregnancies:
                # Average gestation for cattle is 283 days
                animal.expected_calving_date = pregnancies[0].date + timedelta(days=283)
            else:
                animal.expected_calving_date = False
    
    _sql_constraints = [
        ('rfid_unique', 'UNIQUE(rfid_tag)', 'RFID Tag must be unique!'),
        ('ear_tag_unique', 'UNIQUE(ear_tag_number)', 'Ear Tag Number must be unique!'),
    ]
```

#### Milk Production Model

```python
# smart_farm_mgmt/models/milk_production.py
from odoo import models, fields, api

class MilkProduction(models.Model):
    _name = 'smart.farm.milk.production'
    _description = 'Daily Milk Production Record'
    _order = 'date desc, milking_time'
    
    animal_id = fields.Many2one('smart.farm.animal', string='Animal', required=True)
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    milking_time = fields.Selection([
        ('morning', 'Morning'),
        ('evening', 'Evening'),
    ], string='Milking Time', required=True)
    
    # Production
    quantity_liters = fields.Float(string='Quantity (Liters)', required=True)
    
    # Quality
    fat_percentage = fields.Float(string='Fat %')
    snf_percentage = fields.Float(string='SNF %')
    protein_percentage = fields.Float(string='Protein %')
    lactose_percentage = fields.Float(string='Lactose %')
    density = fields.Float(string='Density (kg/L)')
    temperature = fields.Float(string='Temperature (°C)')
    somatic_cell_count = fields.Integer(string='Somatic Cell Count (SCC)')
    
    # Calculated
    snf_kg = fields.Float(string='SNF (kg)', compute='_compute_solid_values')
    fat_kg = fields.Float(string='Fat (kg)', compute='_compute_solid_values')
    total_solids = fields.Float(string='Total Solids (kg)', compute='_compute_solid_values')
    
    # Status
    status = fields.Selection([
        ('normal', 'Normal'),
        ('abnormal', 'Abnormal'),
        ('rejected', 'Rejected'),
    ], string='Status', default='normal')
    notes = fields.Text(string='Notes')
    
    @api.depends('quantity_liters', 'fat_percentage', 'snf_percentage')
    def _compute_solid_values(self):
        for record in self:
            record.fat_kg = record.quantity_liters * (record.fat_percentage or 0) / 100
            record.snf_kg = record.quantity_liters * (record.snf_percentage or 0) / 100
            record.total_solids = record.fat_kg + record.snf_kg
```

#### Success Criteria

- [ ] All 255 animals registered in the system
- [ ] RFID/ear tag scanning functional
- [ ] Breeding records tracking heat, AI, pregnancy
- [ ] Daily milk production entry with quality data
- [ ] Health events recorded with treatments
- [ ] Vaccination schedules auto-generated
- [ ] Farm reports (production, health, breeding) available
- [ ] Mobile-friendly interface for farm workers

---

### 6.9 M9: Data Migration & Integration (Days 81-90)

#### Objective Statement

Migrate all master data (products, customers, suppliers, animals), configure payment gateway integrations (bKash, Nagad, Rocket), set up SMS gateway, and implement email notifications.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M9-D1 | Master data migration scripts | Backend Dev | Scripts tested |
| M9-D2 | Product data migration | Backend Dev | Products imported |
| M9-D3 | Customer data migration | Backend Dev | Customers imported |
| M9-D4 | Supplier data migration | Backend Dev | Suppliers imported |
| M9-D5 | Opening balances migration | Backend Dev | Balances entered |
| M9-D6 | Historical data import | Backend Dev | History imported |
| M9-D7 | bKash payment integration | Backend Dev | bKash sandbox tested |
| M9-D8 | Nagad/Rocket integration | Backend Dev | MFS gateways ready |
| M9-D9 | SMS gateway setup | Backend Dev | SMS sending tested |
| M9-D10 | Email notification templates | Backend Dev | Emails configured |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 81** | Create data migration scripts | Backend Dev | ETL scripts ready |
| **Day 81** | Data cleansing and validation | Backend Dev | Clean data ready |
| **Day 82** | Product master data migration | Backend Dev | Products imported |
| **Day 82** | Product images migration | Backend Dev | Images uploaded |
| **Day 83** | Customer master data migration | Backend Dev | Customers imported |
| **Day 83** | Customer address migration | Backend Dev | Addresses imported |
| **Day 84** | Supplier master data migration | Backend Dev | Suppliers imported |
| **Day 84** | Supplier contracts migration | Backend Dev | Contracts imported |
| **Day 85** | Opening balances migration | Backend Dev | Balance import |
| **Day 85** | Balance reconciliation | Backend Dev | Trial balance |
| **Day 86** | Farm animal records migration | Backend Dev | Animal import |
| **Day 86** | Historical milk data import | Backend Dev | Production history |
| **Day 87** | bKash integration setup | Backend Dev | Sandbox testing |
| **Day 87** | bKash payment testing | Backend Dev | Test transactions |
| **Day 88** | Nagad integration setup | Backend Dev | Nagad sandbox |
| **Day 88** | Rocket integration setup | Backend Dev | Rocket sandbox |
| **Day 89** | SMS gateway setup | Backend Dev | SMS provider config |
| **Day 89** | SMS template setup | Backend Dev | Notification templates |
| **Day 90** | Email service configuration | Backend Dev | SMTP configured |
| **Day 90** | Email templates | Backend Dev | Email templates ready |

#### Data Migration Scope

| Data Category | Source | Record Count | Migration Method |
|---------------|--------|--------------|------------------|
| Products | Excel/Current System | ~50 | CSV Import |
| Customers | Excel/Current System | ~500 | CSV Import |
| Suppliers | Excel/Current System | ~100 | CSV Import |
| Chart of Accounts | Manual | ~100 accounts | Manual Entry |
| Opening Balances | Current System | As of date | Journal Entry |
| Animals | Farm Records | ~255 | CSV Import |
| Historical Milk Data | Farm Records | ~12 months | CSV Import |

#### Success Criteria

- [ ] All master data migrated with validation
- [ ] Product catalog complete with images and pricing
- [ ] Customer database migrated with contact details
- [ ] Supplier information migrated
- [ ] Opening balances reconciled with source
- [ ] Farm animal records migrated
- [ ] bKash payment integration tested
- [ ] SMS gateway configured and tested

---

### 6.10 M10: Go-Live Preparation (Days 91-100)

#### Objective Statement

Prepare the system for production go-live including security hardening, performance optimization, user training, final testing, and cutover planning.

#### Deliverables Table

| ID | Deliverable | Owner | Verification Method |
|----|-------------|-------|---------------------|
| M10-D1 | Security audit | Lead Dev | Security report |
| M10-D2 | Penetration testing | Lead Dev | Pen test report |
| M10-D3 | Performance optimization | Lead Dev | Load test results |
| M10-D4 | Backup/DR testing | Lead Dev | DR test passed |
| M10-D5 | User training completion | All | Training records |
| M10-D6 | UAT completion | All | UAT sign-off |
| M10-D7 | Go-live checklist | Lead Dev | Checklist complete |
| M10-D8 | Cutover plan | Lead Dev | Plan approved |
| M10-D9 | Production deployment | Lead Dev | System live |
| M10-D10 | Post go-live support | All | Support active |

#### Day-by-Day Schedule

| Day | Activity | Owner | Output |
|-----|----------|-------|--------|
| **Day 91** | Security audit | Lead Dev | Vulnerability scan |
| **Day 91** | SSL/TLS verification | Lead Dev | SSL Labs rating |
| **Day 92** | Access control review | Lead Dev | RBAC audit |
| **Day 92** | Penetration testing | Lead Dev | Pen test execution |
| **Day 93** | Performance testing | Lead Dev | Load tests |
| **Day 93** | Database optimization | Backend Dev | Query optimization |
| **Day 94** | Backup testing | Lead Dev | Restore test |
| **Day 94** | DR procedure test | Lead Dev | Failover test |
| **Day 95** | Admin user training | Lead Dev | Admin training |
| **Day 95** | Finance team training | Backend Dev | Finance training |
| **Day 96** | Sales team training | Backend Dev | Sales training |
| **Day 96** | Farm staff training | Lead Dev | Farm training |
| **Day 97** | UAT execution | All | Test cases |
| **Day 97** | Bug fixes | All | Issues resolved |
| **Day 98** | Go-live checklist | Lead Dev | Checklist ready |
| **Day 98** | Cutover plan | Lead Dev | Cutover approved |
| **Day 99** | Final production deployment | Lead Dev | Production ready |
| **Day 99** | Data validation | Backend Dev | Data verified |
| **Day 100** | Go-Live | All | System live |
| **Day 100** | Phase 1 celebration | All | Milestone complete |

#### Go-Live Checklist

| Category | Item | Status |
|----------|------|--------|
| **Infrastructure** | Production servers provisioned | [ ] |
| | SSL certificates installed | [ ] |
| | DNS configured | [ ] |
| | Monitoring active | [ ] |
| | Backups scheduled | [ ] |
| **Security** | Security audit passed | [ ] |
| | Penetration test passed | [ ] |
| | Access controls verified | [ ] |
| | Password policy enforced | [ ] |
| **Data** | Master data migrated | [ ] |
| | Opening balances entered | [ ] |
| | Data validation complete | [ ] |
| **Functionality** | Core ERP tested | [ ] |
| | Website tested | [ ] |
| | Farm module tested | [ ] |
| | Integrations tested | [ ] |
| **Training** | Admin training complete | [ ] |
| | End user training complete | [ ] |
| | Documentation delivered | [ ] |
| **Support** | Support team ready | [ ] |
| | Escalation process defined | [ ] |
| | Post go-live plan ready | [ ] |

#### Success Criteria

- [ ] Security audit passed with no critical vulnerabilities
- [ ] Penetration testing completed successfully
- [ ] Performance testing shows system handles expected load
- [ ] Backup and disaster recovery tested
- [ ] All user training completed
- [ ] UAT passed with stakeholder sign-off
- [ ] Go-live checklist 100% complete
- [ ] System deployed to production
- [ ] Post go-live support team activated

---

## 7. TECHNICAL ARCHITECTURE FOUNDATION

### 7.1 Phase 1 Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              CLIENT LAYER                                            │
├─────────────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                              │
│  │ Web Browser  │  │  Mobile      │  │  Tablet      │                              │
│  │   (All)      │  │  (Staff)     │  │  (Office)    │                              │
│  └──────────────┘  └──────────────┘  └──────────────┘                              │
└───────────────────────────────────────┬─────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           LOAD BALANCER (Nginx)                                      │
│                    SSL Termination, Rate Limiting, Static Files                      │
└───────────────────────────────────────┬─────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                         APPLICATION LAYER                                            │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐   │
│  │                         ODOO 19 APPLICATION SERVER                          │   │
│  │                     (Gunicorn + Odoo Workers)                               │   │
│  │                                                                             │   │
│  │  ┌─────────────────────────────────────────────────────────────────────┐   │   │
│  │  │                    STANDARD ODOO MODULES                             │   │   │
│  │  │  • Website Builder    • E-commerce (future)   • Inventory           │   │   │
│  │  │  • Sales & CRM        • Purchase            • Accounting            │   │   │
│  │  │  • POS (future)       • HR (future)         • Manufacturing (future)│   │   │
│  │  └─────────────────────────────────────────────────────────────────────┘   │   │
│  │                                                                             │   │
│  │  ┌─────────────────────────────────────────────────────────────────────┐   │   │
│  │  │               CUSTOM SMART DAIRY MODULES (Phase 1)                   │   │   │
│  │  │                                                                       │   │   │
│  │  │  ┌──────────────────┐  ┌──────────────────┐                          │   │   │
│  │  │  │ smart_farm_mgmt  │  │ smart_bd_local   │                          │   │   │
│  │  │  │  - Animal Master │  │  - Bangladesh COA│                          │   │   │
│  │  │  │  - Breeding      │  │  - VAT Config    │                          │   │   │
│  │  │  │  - Milk Production│  │  - Mushak Forms  │                          │   │   │
│  │  │  │  - Health Records│  │  - Bengali Lang  │                          │   │   │
│  │  │  └──────────────────┘  └──────────────────┘                          │   │   │
│  │  │                                                                       │   │   │
│  │  │  ┌──────────────────┐  ┌──────────────────┐                          │   │   │
│  │  │  │ smart_website_ext│  │ smart_payment_bd │                          │   │   │
│  │  │  │  - Custom Theme  │  │  - bKash         │                          │   │   │
│  │  │  │  - Product Catalog│  │  - Nagad         │                          │   │   │
│  │  │  │  - Contact Forms │  │  - Rocket        │                          │   │   │
│  │  │  └──────────────────┘  └──────────────────┘                          │   │   │
│  │  │                                                                       │   │   │
│  │  └─────────────────────────────────────────────────────────────────────┘   │   │
│  │                                                                             │   │
│  └─────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                      │
│  ┌──────────────────────────┐  ┌──────────────────────────┐                        │
│  │    BACKGROUND WORKERS    │  │      API GATEWAY         │                        │
│  │    (Celery + Redis)      │  │    (Future Phase)        │                        │
│  │  • Email notifications   │  │                          │                        │
│  │  • Report generation     │  │                          │                        │
│  │  • Scheduled jobs        │  │                          │                        │
│  └──────────────────────────┘  └──────────────────────────┘                        │
│                                                                                      │
└───────────────────────────────────────┬─────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           DATA LAYER                                                 │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  ┌────────────────────────┐  ┌────────────────────────┐                            │
│  │   PRIMARY DATABASE     │  │      CACHE LAYER       │                            │
│  │   PostgreSQL 16        │  │   Redis 7              │                            │
│  │  ├─ Transaction Data   │  │  ├─ Sessions           │                            │
│  │  ├─ Master Data        │  │  ├─ Cache              │                            │
│  │  └─ Audit Logs         │  │  └─ Celery Queue       │                            │
│  └────────────────────────┘  └────────────────────────┘                            │
│                                                                                      │
│  ┌────────────────────────┐  ┌────────────────────────┐                            │
│  │   FILE STORAGE         │  │   BACKUP STORAGE       │                            │
│  │   (S3/MinIO)           │  │   (AWS S3 / Azure Blob)│                            │
│  │  ├─ Product Images     │  │                        │                            │
│  │  ├─ Documents          │  │                        │                            │
│  │  └─ Backups            │  │                        │                            │
│  └────────────────────────┘  └────────────────────────┘                            │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```


### 7.2 Database Schema for Farm Management

```sql
-- Core Farm Management Tables

-- Animal Master Table
CREATE TABLE smart_farm_animal (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    rfid_tag VARCHAR(100) UNIQUE,
    ear_tag_number VARCHAR(100) UNIQUE,
    species VARCHAR(20) NOT NULL DEFAULT 'cattle',
    breed_id INTEGER REFERENCES smart_farm_breed(id),
    gender VARCHAR(10) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'calf',
    birth_date DATE,
    acquisition_type VARCHAR(20),
    acquisition_date DATE,
    purchase_price DECIMAL(12,2),
    barn_id INTEGER REFERENCES smart_farm_barn(id),
    group_id INTEGER REFERENCES smart_farm_animal_group(id),
    weight_kg DECIMAL(8,2),
    body_condition_score VARCHAR(5),
    lactation_number INTEGER DEFAULT 0,
    current_lactation_start DATE,
    image BYTEA,
    active BOOLEAN DEFAULT TRUE,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_uid INTEGER REFERENCES res_users(id),
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Milk Production Table
CREATE TABLE smart_farm_milk_production (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES smart_farm_animal(id) ON DELETE CASCADE,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    milking_time VARCHAR(10) NOT NULL,
    quantity_liters DECIMAL(8,2) NOT NULL,
    fat_percentage DECIMAL(5,2),
    snf_percentage DECIMAL(5,2),
    protein_percentage DECIMAL(5,2),
    lactose_percentage DECIMAL(5,2),
    density DECIMAL(6,3),
    temperature DECIMAL(5,2),
    somatic_cell_count INTEGER,
    status VARCHAR(20) DEFAULT 'normal',
    notes TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Breeding Records Table
CREATE TABLE smart_farm_breeding (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES smart_farm_animal(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    event_type VARCHAR(30) NOT NULL,
    heat_signs TEXT,
    sire_id INTEGER REFERENCES smart_farm_animal(id),
    ai_technician_id INTEGER REFERENCES hr_employee(id),
    semen_batch VARCHAR(50),
    result VARCHAR(20),
    calving_ease VARCHAR(20),
    calf_sex VARCHAR(10),
    calf_weight_kg DECIMAL(6,2),
    notes TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Health Records Table
CREATE TABLE smart_farm_health (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES smart_farm_animal(id) ON DELETE CASCADE,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    event_type VARCHAR(30) NOT NULL,
    symptoms TEXT,
    diagnosis VARCHAR(200),
    treatment TEXT,
    medication VARCHAR(500),
    dosage VARCHAR(100),
    withdrawal_days INTEGER,
    veterinarian_id INTEGER REFERENCES hr_employee(id),
    cost DECIMAL(10,2),
    follow_up_date DATE,
    notes TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vaccination Records Table
CREATE TABLE smart_farm_vaccination (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES smart_farm_animal(id) ON DELETE CASCADE,
    vaccine_type VARCHAR(100) NOT NULL,
    batch_number VARCHAR(50),
    administration_date DATE NOT NULL,
    next_due_date DATE,
    administrator_id INTEGER REFERENCES hr_employee(id),
    status VARCHAR(20) DEFAULT 'completed',
    notes TEXT,
    create_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for Performance
CREATE INDEX idx_animal_status ON smart_farm_animal(status);
CREATE INDEX idx_animal_barn ON smart_farm_animal(barn_id);
CREATE INDEX idx_milk_animal_date ON smart_farm_milk_production(animal_id, date);
CREATE INDEX idx_milk_date ON smart_farm_milk_production(date);
CREATE INDEX idx_breeding_animal ON smart_farm_breeding(animal_id);
CREATE INDEX idx_health_animal ON smart_farm_health(animal_id);
CREATE INDEX idx_vaccination_animal ON smart_farm_vaccination(animal_id);
CREATE INDEX idx_vaccination_due ON smart_farm_vaccination(next_due_date);
```

### 7.3 API Specifications

#### REST API Endpoints (Phase 1)

| Endpoint | Method | Description | Auth Required |
|----------|--------|-------------|---------------|
| `/api/v1/animals` | GET | List all animals | Yes |
| `/api/v1/animals/{id}` | GET | Get animal details | Yes |
| `/api/v1/animals` | POST | Create new animal | Yes |
| `/api/v1/animals/{id}` | PUT | Update animal | Yes |
| `/api/v1/milk-production` | GET | List milk records | Yes |
| `/api/v1/milk-production` | POST | Add milk record | Yes |
| `/api/v1/breeding` | GET | List breeding records | Yes |
| `/api/v1/breeding` | POST | Add breeding record | Yes |
| `/api/v1/health` | GET | List health records | Yes |
| `/api/v1/health` | POST | Add health record | Yes |
| `/api/v1/reports/daily-milk` | GET | Daily milk summary | Yes |
| `/api/v1/reports/herd-summary` | GET | Herd statistics | Yes |

---

## 8. QUALITY ASSURANCE FRAMEWORK

### 8.1 Testing Strategy

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         TESTING PYRAMID                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│                          ┌─────────┐                                        │
│                         /   E2E    \        <- 10% of tests                │
│                        /   Testing   \                                       │
│                       /───────────────\                                     │
│                      /   Integration   \    <- 20% of tests                │
│                     /     Testing       \                                   │
│                    /─────────────────────\                                 │
│                   /       Unit Testing     \ <- 70% of tests               │
│                  /───────────────────────────\                             │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Test Categories

| Test Type | Responsibility | Tools | Coverage Target |
|-----------|----------------|-------|-----------------|
| Unit Tests | All Developers | pytest, Odoo Test Framework | 80%+ |
| Integration Tests | Backend Dev | Odoo Test Framework | API endpoints |
| Functional Tests | Frontend Dev | Playwright, Selenium | Critical paths |
| Security Tests | Lead Dev | OWASP ZAP, Burp Suite | OWASP Top 10 |
| Performance Tests | Lead Dev | Locust, JMeter | 1000 concurrent users |
| UAT | Business Users | Manual | All requirements |

### 8.3 Quality Gates

| Gate | Criteria | Checkpoint |
|------|----------|------------|
| **Code Quality** | pylint score > 8.0, no critical issues | Pre-commit |
| **Test Coverage** | > 80% coverage | CI Pipeline |
| **Security Scan** | Zero critical/high vulnerabilities | Weekly |
| **Performance** | < 3s page load, < 500ms API response | Staging |
| **UAT Sign-off** | 100% test cases passed | Pre-release |

---

## 9. RISK MANAGEMENT

### 9.1 Risk Register

| ID | Risk | Probability | Impact | Mitigation Strategy | Owner |
|----|------|-------------|--------|---------------------|-------|
| R1 | Team member unavailable | Medium | High | Cross-training, documentation | Lead Dev |
| R2 | Third-party integration delays | Medium | High | Early engagement, sandbox testing | Backend Dev |
| R3 | Data migration errors | Medium | High | Multiple validation cycles, backups | Backend Dev |
| R4 | Scope creep | High | Medium | Strict change control, MVP focus | Lead Dev |
| R5 | Performance issues | Low | High | Load testing, optimization | Lead Dev |
| R6 | Security vulnerabilities | Low | Critical | Regular scans, security review | Lead Dev |
| R7 | User adoption resistance | Medium | Medium | Early training, change management | All |
| R8 | Infrastructure failures | Low | High | Redundancy, DR plan | Lead Dev |

### 9.2 Contingency Plans

**Scenario 1: Developer Unavailability**
- Backup developer identified from other projects
- Pair programming to ensure knowledge sharing
- Comprehensive documentation required

**Scenario 2: Integration Failure**
- Fallback to manual processes documented
- Alternative vendors identified
- Phased integration approach

**Scenario 3: Data Migration Issues**
- Parallel running with old system
- Rollback procedures documented
- Data reconciliation scripts prepared

---

## 10. APPENDICES

### Appendix A: Project Timeline Summary

| Milestone | Duration | Start | End | Key Deliverables |
|-----------|----------|-------|-----|------------------|
| M1: Dev Environment | 10 days | Day 1 | Day 10 | Local Odoo setup on 3 workstations |
| M2: Infrastructure | 10 days | Day 11 | Day 20 | AWS, Docker, CI/CD, Monitoring |
| M3: Odoo Core | 10 days | Day 21 | Day 30 | COA, VAT, RBAC, Security |
| M4: Website Foundation | 10 days | Day 31 | Day 40 | Theme, Multi-language, CMS |
| M5: Website Content | 10 days | Day 41 | Day 50 | Content, Forms, SEO, Analytics |
| M6: ERP Core Part 1 | 10 days | Day 51 | Day 60 | Inventory, Accounting |
| M7: ERP Core Part 2 | 10 days | Day 61 | Day 70 | Sales, CRM, Purchase |
| M8: Farm Management | 10 days | Day 71 | Day 80 | Animal, Milk, Health modules |
| M9: Migration & Integration | 10 days | Day 81 | Day 90 | Data migration, bKash, SMS |
| M10: Go-Live | 10 days | Day 91 | Day 100 | Security, Training, UAT, Launch |

### Appendix B: Resource Allocation

| Resource | M1 | M2 | M3 | M4 | M5 | M6 | M7 | M8 | M9 | M10 | Total |
|----------|----|----|----|----|----|----|----|----|----|-----|-------|
| Lead Dev | 100% | 100% | 50% | 30% | 20% | 30% | 30% | 50% | 50% | 100% | 56 days |
| Backend Dev | 100% | 50% | 100% | 20% | 20% | 100% | 100% | 100% | 100% | 50% | 74 days |
| Frontend Dev | 100% | 50% | 20% | 100% | 100% | 40% | 40% | 50% | 20% | 50% | 57 days |

### Appendix C: Budget Breakdown by Milestone

| Milestone | Development | Infrastructure | Third-party | Total (BDT) |
|-----------|-------------|----------------|-------------|-------------|
| M1: Dev Environment | 4,50,000 | 50,000 | 0 | 5,00,000 |
| M2: Infrastructure | 4,50,000 | 5,00,000 | 50,000 | 10,00,000 |
| M3: Odoo Core | 4,50,000 | 1,00,000 | 1,00,000 | 6,50,000 |
| M4: Website Foundation | 4,50,000 | 1,00,000 | 50,000 | 6,00,000 |
| M5: Website Content | 4,50,000 | 1,00,000 | 1,50,000 | 7,00,000 |
| M6: ERP Core Part 1 | 4,50,000 | 1,00,000 | 1,00,000 | 6,50,000 |
| M7: ERP Core Part 2 | 4,50,000 | 1,00,000 | 1,00,000 | 6,50,000 |
| M8: Farm Management | 4,50,000 | 1,00,000 | 50,000 | 6,00,000 |
| M9: Migration & Integration | 4,50,000 | 1,00,000 | 4,00,000 | 9,50,000 |
| M10: Go-Live | 4,50,000 | 5,50,000 | 2,00,000 | 12,00,000 |
| **Total** | **45,00,000** | **18,00,000** | **12,00,000** | **75,00,000** |
| Contingency (15%) | | | | 11,25,000 |
| **Grand Total** | | | | **86,25,000** |

### Appendix D: Odoo Custom Module Manifest Template

```python
# __manifest__.py - Template for Smart Dairy Custom Modules
{
    'name': 'Smart Dairy - Module Name',
    'version': '1.0.0',
    'category': 'Smart Dairy',
    'summary': 'Short description of the module',
    'description': """
        Detailed description of the module functionality.
        
        Features:
        - Feature 1
        - Feature 2
        - Feature 3
        
        Dependencies:
        - base
        - Other modules
    """,
    'author': 'Smart Dairy Technical Team',
    'website': 'https://smartdairybd.com',
    'license': 'LGPL-3',
    
    # Dependencies
    'depends': [
        'base',
        'mail',
        'web',
    ],
    
    # Data files
    'data': [
        # Security
        'security/ir.model.access.csv',
        'security/security.xml',
        
        # Data
        'data/sequence_data.xml',
        'data/demo_data.xml',
        
        # Views
        'views/menu_views.xml',
        'views/model_views.xml',
        'views/report_views.xml',
        
        # Reports
        'reports/report_templates.xml',
        'reports/report_actions.xml',
    ],
    
    # Demo data
    'demo': [
        'demo/demo_data.xml',
    ],
    
    # Assets
    'assets': {
        'web.assets_backend': [
            'module_name/static/src/scss/*.scss',
            'module_name/static/src/js/*.js',
            'module_name/static/src/xml/*.xml',
        ],
    },
    
    # Installation
    'installable': True,
    'application': False,
    'auto_install': False,
    
    # External dependencies
    'external_dependencies': {
        'python': ['pandas', 'numpy'],
    },
}
```

### Appendix E: Development Standards

#### Code Style Guidelines

```python
# Python Code Style for Smart Dairy Odoo Development

# 1. Follow PEP 8 with 120 character line limit
# 2. Use meaningful variable names
# 3. Add docstrings to all classes and methods
# 4. Type hints where applicable

from typing import Optional, List
from datetime import datetime, date
from odoo import models, fields, api
from odoo.exceptions import ValidationError


class SmartDairyModel(models.Model):
    """
    Brief description of the model.
    
    Longer description explaining the purpose and functionality
    of this model within the Smart Dairy system.
    
    Attributes:
        name (str): Display name of the record
        state (str): Current state of the workflow
    """
    
    _name = 'smart.dairy.model'
    _description = 'Smart Dairy Model'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name, id desc'
    
    # Fields
    name = fields.Char(
        string='Name',
        required=True,
        index=True,
        tracking=True,
        help='Display name for this record'
    )
    
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('done', 'Done'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)
    
    # Computed Fields
    computed_field = fields.Float(
        string='Computed Field',
        compute='_compute_field',
        store=True,
        readonly=True
    )
    
    # Constraints
    @api.constrains('name')
    def _check_name(self):
        """Validate name field."""
        for record in self:
            if len(record.name) < 3:
                raise ValidationError(_('Name must be at least 3 characters.'))
    
    # Compute Methods
    @api.depends('field1', 'field2')
    def _compute_field(self):
        """Compute the computed_field value."""
        for record in self:
            record.computed_field = record.field1 + record.field2
    
    # Business Methods
    def action_confirm(self):
        """Confirm the record and move to confirmed state."""
        self.ensure_one()
        if self.state != 'draft':
            raise ValidationError(_('Only draft records can be confirmed.'))
        self.write({'state': 'confirmed'})
        return True
```

#### Git Commit Message Convention

```
Format: <type>(<scope>): <subject>

Types:
- feat: New feature
- fix: Bug fix
- docs: Documentation
- style: Formatting
- refactor: Code refactoring
- test: Tests
- chore: Maintenance

Examples:
feat(farm): add milk production tracking
fix(accounting): correct VAT calculation for B2B
feat(website): implement Bengali language support
refactor(core): optimize database queries
```

---

## DOCUMENT CONTROL

### Version History

| Version | Date | Author | Changes | Status |
|---------|------|--------|---------|--------|
| 1.0 | 2026-02-01 | Technical Team | Initial creation | Draft |
| 1.0 | 2026-02-02 | Technical Team | Final review | Approved |

### Approval Signatures

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Manager | _________________ | _________________ | _______ |
| Technical Lead | _________________ | _________________ | _______ |
| Managing Director | _________________ | _________________ | _______ |

---

**END OF PHASE 1 FOUNDATION IMPLEMENTATION ROADMAP**

**Document Statistics:**
- Total Pages: 50+
- Milestones: 10
- Days: 100
- Deliverables: 100+
- Lines of Code Examples: 500+
- Tables: 80+

**Next Document:** Phase 2 Operations Implementation Roadmap
