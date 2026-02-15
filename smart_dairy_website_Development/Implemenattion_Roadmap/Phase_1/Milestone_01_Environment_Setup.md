# Milestone 1: Environment Setup & Tool Installation

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field                | Detail                                                    |
|----------------------|-----------------------------------------------------------|
| **Milestone**        | 1 of 10                                                   |
| **Title**            | Environment Setup & Tool Installation                     |
| **Phase**            | Phase 1 — Foundation (Part A: Infrastructure & Core Setup) |
| **Days**             | Days 1–10 (of 100)                                        |
| **Version**          | 1.0                                                       |
| **Status**           | Draft                                                     |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated**     | 2026-02-03                                                |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Establish a fully functional, reproducible, and secure development environment for the Smart Dairy Digital Smart Portal + ERP project. By Day 10, all three developers must have identical, working local environments with Docker-based services, Odoo 19 CE installed, CI/CD pipeline operational, monitoring stack running, and all coding standards documented.

### 1.2 Objectives

1. Provision Ubuntu 24.04 LTS workstations with all required SDKs, runtimes, and tools
2. Initialize Git repository with GitFlow branching strategy and pre-commit hooks
3. Build Docker Compose development environment (Odoo 19 CE, PostgreSQL 16, Redis 7, Nginx)
4. Install and configure Odoo 19 CE from source with Smart Dairy database
5. Plan database schema and initialize PostgreSQL 16 cluster
6. Establish security foundations (SSH, SSL, secrets management)
7. Implement CI/CD pipeline (GitHub Actions) with linting, testing, and build stages
8. Deploy monitoring and logging infrastructure (Prometheus, Grafana, ELK)
9. Author development documentation, coding standards, and API conventions
10. Conduct Milestone 1 review, demo, and retrospective

### 1.3 Key Deliverables

| # | Deliverable                         | Owner  | Format            |
|---|-------------------------------------|--------|-------------------|
| 1 | Configured Ubuntu 24.04 workstations | All    | Running systems    |
| 2 | Git repository with GitFlow         | Dev 2  | GitHub repo        |
| 3 | Docker Compose dev environment      | Dev 2  | docker-compose.yml |
| 4 | Odoo 19 CE running in Docker        | Dev 1  | Docker container   |
| 5 | PostgreSQL 16 cluster (dev)         | Dev 1  | Docker service     |
| 6 | Redis 7 session/cache store         | Dev 2  | Docker service     |
| 7 | CI/CD pipeline (GitHub Actions)     | Dev 2  | .github/workflows/ |
| 8 | Monitoring stack (Prometheus+Grafana)| Dev 2 | Docker services    |
| 9 | ELK logging stack                   | Dev 3  | Docker services    |
| 10| Development documentation           | All    | Markdown files     |
| 11| Coding standards document           | All    | Markdown file      |
| 12| Milestone 1 review report           | All    | Markdown file      |

### 1.4 Prerequisites

- Three Linux-capable workstations (min 16 GB RAM, 256 GB SSD, 4-core CPU)
- GitHub organization account created for Smart Dairy
- Internet access for package downloads
- Smart Dairy brand assets (logo, colors) from marketing team

### 1.5 Success Criteria

- [ ] All 3 developers can run `docker compose up` and access Odoo at `http://localhost:8069`
- [ ] PostgreSQL 16 accessible on port 5432, Redis on 6379
- [ ] CI/CD pipeline triggers on push and runs lint+test stages successfully
- [ ] Prometheus collects metrics; Grafana dashboards display system health
- [ ] ELK stack ingests Odoo and Nginx logs; Kibana shows log entries
- [ ] Pre-commit hooks enforce code formatting on every commit
- [ ] All documentation committed to `docs/` directory in repository

---

## 2. Requirement Traceability Matrix

| Req ID       | Source    | Requirement Description                          | Day(s) | Task Reference         |
|-------------|-----------|--------------------------------------------------|--------|------------------------|
| D-001 §2    | Impl Guide| Infrastructure Architecture — Development Env    | 1–3    | Workstation, Docker    |
| D-001 §4    | Impl Guide| Multi-environment strategy (dev/staging/prod)     | 3, 7   | Docker profiles, CI/CD |
| D-003 §1-4  | Impl Guide| Docker Configuration — all containers             | 3      | Docker Compose         |
| D-006 §1-3  | Impl Guide| CI/CD Pipeline Configuration                      | 7      | GitHub Actions         |
| B-001 §2    | Impl Guide| Technical Design — module structure               | 2, 4   | Repo structure, Odoo   |
| G-001 §1    | Impl Guide| Test Strategy — test infrastructure               | 7      | CI test runner         |
| C-001 §1-2  | Impl Guide| Database Design — schema planning                 | 5      | Schema design          |
| F-001 §2    | Impl Guide| Security Architecture — dev security baseline     | 6      | SSL, secrets, SSH      |
| B-014 §1    | Impl Guide| Error Handling & Logging Standards                | 8      | ELK, structured logs   |
| D-009 §1-2  | Impl Guide| Log Aggregation & Analysis (ELK)                  | 8      | ELK stack              |
| NFR-PERF-01 | BRD §3    | Page load time < 3 seconds                        | 8      | Monitoring baseline    |
| NFR-AVAIL-01| BRD §3    | System availability 99.9%                          | 8      | Monitoring/alerting    |
| NFR-SEC-01  | BRD §3    | All data encrypted in transit                      | 6      | SSL/TLS setup          |

---

## 3. Day-by-Day Breakdown

---

### Day 1 — Development Workstation Setup

**Objective:** Provision all three developer workstations with Ubuntu 24.04 LTS, required runtimes, SDKs, and IDE configuration.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Install Ubuntu 24.04 LTS (fresh or upgrade) — 1h
2. Install Python 3.11+ and core tools — 2h
3. Install PostgreSQL 16 client tools and pgAdmin 4 — 2h
4. Configure VS Code with backend extensions — 1.5h
5. Verify all installations — 1.5h

```bash
# Python 3.11+ installation
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:deadsnakes/ppa -y
sudo apt install -y python3.11 python3.11-venv python3.11-dev python3-pip

# Create global virtual environment tooling
python3.11 -m pip install --user pipx
pipx install poetry
pipx install black
pipx install flake8
pipx install isort
pipx install pylint
pipx install pre-commit

# PostgreSQL 16 client
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sudo apt update
sudo apt install -y postgresql-client-16 libpq-dev

# pgAdmin 4
curl -fsS https://www.pgadmin.org/static/packages_pgadmin_org.pub | sudo gpg --dearmor -o /usr/share/keyrings/pgadmin.gpg
echo "deb [signed-by=/usr/share/keyrings/pgadmin.gpg] https://ftp.postgresql.org/pub/pgadmin/pgadmin4/apt/$(lsb_release -cs) pgadmin4 main" | sudo tee /etc/apt/sources.list.d/pgadmin4.list
sudo apt update && sudo apt install -y pgadmin4-desktop

# Odoo 19 CE system dependencies
sudo apt install -y git gcc g++ make libxml2-dev libxslt1-dev \
  libjpeg-dev libfreetype6-dev zlib1g-dev libsasl2-dev libldap2-dev \
  libssl-dev libtiff5-dev libopenjp2-7-dev liblcms2-dev \
  libwebp-dev libharfbuzz-dev libfribidi-dev libxcb1-dev \
  node-less npm wkhtmltopdf
```

**VS Code Extensions (Dev 1):**
```json
{
  "recommendations": [
    "ms-python.python",
    "ms-python.vscode-pylance",
    "ms-python.black-formatter",
    "ms-python.isort",
    "charliermarsh.ruff",
    "trinhanhngoc.vscode-odoo",
    "jigar-patel.OdooSnippets",
    "mtxr.sqltools",
    "mtxr.sqltools-driver-pg",
    "eamodio.gitlens"
  ]
}
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Install Ubuntu 24.04 LTS — 1h
2. Install Docker Engine 24+ and Docker Compose v2 — 2h
3. Install Node.js 20 LTS and Nginx — 1.5h
4. Configure VS Code with DevOps extensions — 1.5h
5. Verify Docker and container runtime — 2h

```bash
# Docker Engine
sudo apt install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Add user to docker group (no sudo needed for docker commands)
sudo usermod -aG docker $USER
newgrp docker

# Verify
docker --version        # Docker version 24+
docker compose version  # Docker Compose v2.x

# Node.js 20 LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
npm --version

# Nginx (for local testing outside Docker)
sudo apt install -y nginx
```

**VS Code Extensions (Dev 2):**
```json
{
  "recommendations": [
    "ms-azuretools.vscode-docker",
    "redhat.vscode-yaml",
    "ms-vscode.makefile-tools",
    "eamodio.gitlens",
    "GitHub.vscode-github-actions",
    "ms-python.python",
    "dbaeumer.vscode-eslint",
    "esbenp.prettier-vscode",
    "hashicorp.terraform"
  ]
}
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Install Ubuntu 24.04 LTS — 1h
2. Install Node.js 20 LTS and frontend toolchain — 1.5h
3. Install Flutter SDK 3.16+ and Dart 3.2+ — 2h
4. Install Chrome/Chromium and Android SDK — 1.5h
5. Configure VS Code with frontend extensions — 1h
6. Verify all toolchains — 1h

```bash
# Node.js 20 LTS (same as Dev 2)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Flutter SDK
sudo snap install flutter --classic
flutter doctor
flutter config --enable-web

# Chrome for DevTools
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
sudo apt --fix-broken install -y

# Android SDK (for mobile development)
sudo apt install -y android-sdk
flutter config --android-sdk /usr/lib/android-sdk

# Verify
node --version        # v20.x
flutter --version     # Flutter 3.16+
dart --version        # Dart 3.2+
google-chrome --version
```

**VS Code Extensions (Dev 3):**
```json
{
  "recommendations": [
    "Dart-Code.dart-code",
    "Dart-Code.flutter",
    "bradlc.vscode-tailwindcss",
    "dbaeumer.vscode-eslint",
    "esbenp.prettier-vscode",
    "formulahendry.auto-rename-tag",
    "ecmel.vscode-html-css",
    "ms-vscode.live-server",
    "eamodio.gitlens",
    "usernamehw.errorlens"
  ]
}
```

**End-of-Day 1 Deliverables:**
- [ ] All 3 workstations running Ubuntu 24.04 LTS
- [ ] Python 3.11+, Docker 24+, Node.js 20, Flutter 3.16+ installed
- [ ] VS Code configured with role-specific extensions
- [ ] All `--version` checks documented and passing

---

### Day 2 — Git Repository & Project Structure

**Objective:** Initialize the Git repository with a professional project structure, GitFlow branching strategy, pre-commit hooks, and PR/issue templates.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Create Odoo custom addons directory structure — 3h
2. Set up Python virtual environment and requirements — 2.5h
3. Create `.gitignore` for Python/Odoo — 1h
4. Create initial module scaffolding placeholders — 1.5h

```
smart-dairy-erp/                        # Repository root
├── .github/
│   ├── workflows/
│   │   ├── ci.yml                      # Main CI pipeline
│   │   ├── security.yml                # Security scanning
│   │   └── deploy-staging.yml          # Staging deployment
│   ├── PULL_REQUEST_TEMPLATE.md
│   └── ISSUE_TEMPLATE/
│       ├── bug_report.md
│       ├── feature_request.md
│       └── task.md
├── smart_dairy_addons/                 # Odoo custom modules
│   ├── smart_farm_mgmt/                # Farm management module
│   │   ├── __init__.py
│   │   ├── __manifest__.py
│   │   ├── models/
│   │   ├── views/
│   │   ├── security/
│   │   ├── data/
│   │   ├── wizards/
│   │   ├── reports/
│   │   ├── static/
│   │   └── tests/
│   ├── smart_b2b_portal/               # B2B portal module
│   ├── smart_b2c_portal/               # B2C e-commerce module
│   ├── smart_bd_local/                 # Bangladesh localization
│   ├── smart_api_gateway/              # API integration layer
│   ├── smart_iot/                      # IoT integration module
│   └── smart_security/                 # Security extensions
├── smart_dairy_api/                    # FastAPI gateway
│   ├── main.py
│   ├── routers/
│   ├── middleware/
│   ├── auth/
│   ├── schemas/
│   ├── services/
│   └── tests/
├── frontend/                           # Frontend assets
│   ├── src/
│   ├── public/
│   └── package.json
├── mobile/                             # Flutter mobile app
│   ├── lib/
│   ├── test/
│   ├── android/
│   ├── ios/
│   └── pubspec.yaml
├── docker/                             # Docker configurations
│   ├── odoo/
│   │   └── Dockerfile
│   ├── api/
│   │   └── Dockerfile
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── conf.d/
│   └── monitoring/
├── scripts/                            # Utility scripts
│   ├── setup.sh
│   ├── backup.sh
│   └── seed_data.py
├── docs/                               # Project documentation
│   ├── setup-guide.md
│   ├── coding-standards.md
│   ├── api-guidelines.md
│   └── architecture/
├── docker-compose.yml
├── docker-compose.override.yml
├── docker-compose.test.yml
├── Makefile
├── requirements.txt
├── requirements-dev.txt
├── .env.example
├── .gitignore
├── .editorconfig
├── .pre-commit-config.yaml
├── pyproject.toml
└── README.md
```

**requirements.txt:**
```txt
# Odoo 19 CE dependencies (pinned)
odoo==19.0
psycopg2-binary==2.9.9
redis==5.0.1
python-dotenv==1.0.0
Pillow==10.2.0
lxml==5.1.0
passlib==1.7.4
PyJWT==2.8.0
cryptography==42.0.2
```

**requirements-dev.txt:**
```txt
-r requirements.txt
pytest==8.0.0
pytest-cov==4.1.0
pytest-xdist==3.5.0
black==24.1.0
flake8==7.0.0
isort==5.13.0
pylint==3.0.3
pylint-odoo==8.0.19
pre-commit==3.6.0
coverage==7.4.0
bandit==1.7.7
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Initialize GitHub repository and configure GitFlow — 2h
2. Configure branch protection rules — 1.5h
3. Set up pre-commit hooks — 2h
4. Create PR and issue templates — 1.5h
5. Create Makefile for common commands — 1h

**GitFlow Branching Strategy:**
```
main            ← Production-ready code (protected, requires PR + 2 reviews)
  └─ develop    ← Integration branch (protected, requires PR + 1 review)
      ├─ feature/SD-001-animal-management    ← Feature branches
      ├─ feature/SD-002-milk-recording
      ├─ bugfix/SD-010-fix-date-validation
      └─ release/v0.1.0                      ← Release branches
hotfix/v0.0.1   ← Emergency production fixes (from main)
```

**.pre-commit-config.yaml:**
```yaml
repos:
  - repo: https://github.com/pre-commit/pre-commit-hooks
    rev: v4.5.0
    hooks:
      - id: trailing-whitespace
      - id: end-of-file-fixer
      - id: check-yaml
      - id: check-json
      - id: check-merge-conflict
      - id: check-added-large-files
        args: ['--maxkb=500']
      - id: detect-private-key
      - id: no-commit-to-branch
        args: ['--branch', 'main', '--branch', 'develop']

  - repo: https://github.com/psf/black
    rev: 24.1.0
    hooks:
      - id: black
        args: ['--line-length=120']

  - repo: https://github.com/pycqa/isort
    rev: 5.13.0
    hooks:
      - id: isort
        args: ['--profile=black', '--line-length=120']

  - repo: https://github.com/pycqa/flake8
    rev: 7.0.0
    hooks:
      - id: flake8
        args: ['--max-line-length=120', '--ignore=E501,W503']

  - repo: https://github.com/PyCQA/bandit
    rev: 1.7.7
    hooks:
      - id: bandit
        args: ['-c', 'pyproject.toml']
        additional_dependencies: ['bandit[toml]']
```

**Makefile:**
```makefile
.PHONY: help up down restart logs shell test lint format build clean

help: ## Show this help message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up: ## Start all services
	docker compose up -d

down: ## Stop all services
	docker compose down

restart: ## Restart all services
	docker compose restart

logs: ## Tail all service logs
	docker compose logs -f --tail=100

shell-odoo: ## Open shell in Odoo container
	docker compose exec odoo bash

shell-db: ## Open psql in PostgreSQL container
	docker compose exec db psql -U odoo -d smart_dairy

test: ## Run Python tests
	docker compose exec odoo python -m pytest smart_dairy_addons/ --cov --cov-report=html -v

lint: ## Run all linters
	pre-commit run --all-files

format: ## Format Python code
	black smart_dairy_addons/ smart_dairy_api/ --line-length 120
	isort smart_dairy_addons/ smart_dairy_api/ --profile black --line-length 120

build: ## Build Docker images
	docker compose build --no-cache

clean: ## Remove containers, volumes, and images
	docker compose down -v --rmi local

seed: ## Seed database with demo data
	docker compose exec odoo python scripts/seed_data.py

backup: ## Backup database
	./scripts/backup.sh
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Set up frontend project structure — 2h
2. Initialize Flutter mobile project — 2h
3. Configure Tailwind CSS and build tooling — 2h
4. Create `.editorconfig` and frontend linting — 2h

**.editorconfig:**
```ini
root = true

[*]
indent_style = space
indent_size = 4
end_of_line = lf
charset = utf-8
trim_trailing_whitespace = true
insert_final_newline = true

[*.{js,jsx,ts,tsx,vue,css,scss,html,xml}]
indent_size = 2

[*.{yml,yaml}]
indent_size = 2

[*.md]
trim_trailing_whitespace = false

[Makefile]
indent_style = tab
```

**End-of-Day 2 Deliverables:**
- [ ] GitHub repository initialized with GitFlow branches (main, develop)
- [ ] Complete project directory structure committed
- [ ] Pre-commit hooks installed and verified
- [ ] PR and issue templates working
- [ ] Makefile with all common commands
- [ ] All developers can clone, install hooks, and run `make help`

---

### Day 3 — Docker Development Environment

**Objective:** Build the complete Docker Compose development environment with Odoo 19 CE, PostgreSQL 16, Redis 7, Nginx, and supporting services.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Write Odoo 19 CE Dockerfile (multi-stage build) — 3h
2. Create `odoo.conf` configuration — 2h
3. Configure PostgreSQL 16 container with tuned settings — 3h

**docker/odoo/Dockerfile:**
```dockerfile
# Stage 1: Build dependencies
FROM python:3.11-slim-bookworm AS builder

RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    gcc \
    g++ \
    libxml2-dev \
    libxslt1-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zlib1g-dev \
    libsasl2-dev \
    libldap2-dev \
    libssl-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt /tmp/requirements.txt
RUN pip install --prefix=/install --no-cache-dir -r /tmp/requirements.txt

# Stage 2: Runtime
FROM python:3.11-slim-bookworm AS runtime

ENV ODOO_HOME=/opt/odoo
ENV PYTHONUNBUFFERED=1
ENV PYTHONDONTWRITEBYTECODE=1

# Runtime dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libxml2 libxslt1.1 libjpeg62-turbo libfreetype6 \
    zlib1g libsasl2-2 libldap-2.5-0 libssl3 libpq5 \
    fonts-noto-cjk wkhtmltopdf \
    git curl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=builder /install /usr/local

# Create odoo user
RUN useradd -m -d ${ODOO_HOME} -s /bin/bash odoo

# Clone Odoo 19 CE
RUN git clone --depth 1 --branch 19.0 https://github.com/odoo/odoo.git ${ODOO_HOME}/odoo-server

# Create directories
RUN mkdir -p ${ODOO_HOME}/custom-addons \
             ${ODOO_HOME}/data \
             ${ODOO_HOME}/logs \
             ${ODOO_HOME}/config \
    && chown -R odoo:odoo ${ODOO_HOME}

COPY docker/odoo/odoo.conf ${ODOO_HOME}/config/odoo.conf

USER odoo
WORKDIR ${ODOO_HOME}

EXPOSE 8069 8072

ENTRYPOINT ["python3", "/opt/odoo/odoo-server/odoo-bin"]
CMD ["-c", "/opt/odoo/config/odoo.conf"]
```

**docker/odoo/odoo.conf:**
```ini
[options]
; ---------- Paths ----------
addons_path = /opt/odoo/odoo-server/addons,/opt/odoo/custom-addons
data_dir = /opt/odoo/data

; ---------- Database ----------
db_host = db
db_port = 5432
db_user = odoo
db_password = ${DB_PASSWORD}
db_name = smart_dairy
db_maxconn = 64

; ---------- Server ----------
http_port = 8069
longpolling_port = 8072
proxy_mode = True
workers = 0
max_cron_threads = 1

; ---------- Performance (dev) ----------
limit_memory_hard = 2684354560
limit_memory_soft = 2147483648
limit_time_cpu = 600
limit_time_real = 1200
limit_time_real_cron = -1

; ---------- Logging ----------
log_level = info
log_handler = :INFO,odoo.sql_db:WARNING,werkzeug:WARNING
logfile = /opt/odoo/logs/odoo-server.log

; ---------- Security ----------
admin_passwd = ${ODOO_MASTER_PASSWORD}
list_db = True
```

**docker/postgres/postgresql.conf (development-tuned):**
```ini
# Memory
shared_buffers = 512MB
effective_cache_size = 1536MB
work_mem = 16MB
maintenance_work_mem = 128MB

# WAL
wal_level = replica
max_wal_senders = 3
wal_buffers = 16MB

# Query Planner
random_page_cost = 1.1
effective_io_concurrency = 200
default_statistics_target = 100

# Logging
log_min_duration_statement = 100
log_statement = 'mod'
log_line_prefix = '%t [%p] %u@%d '
log_checkpoints = on
log_connections = on
log_disconnections = on
log_lock_waits = on

# Locale
lc_messages = 'en_US.UTF-8'
lc_monetary = 'en_US.UTF-8'
lc_numeric = 'en_US.UTF-8'
lc_time = 'en_US.UTF-8'

# Extensions
shared_preload_libraries = 'pg_stat_statements'
pg_stat_statements.max = 10000
pg_stat_statements.track = all
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Write main `docker-compose.yml` — 3h
2. Write `docker-compose.override.yml` for dev — 1.5h
3. Configure Docker networks and volumes — 1.5h
4. Test and validate full stack startup — 2h

**docker-compose.yml:**
```yaml
version: "3.8"

services:
  db:
    image: postgres:16-bookworm
    container_name: smart_dairy_db
    environment:
      POSTGRES_USER: odoo
      POSTGRES_PASSWORD: ${DB_PASSWORD:-odoo_dev_2026}
      POSTGRES_DB: smart_dairy
      PGDATA: /var/lib/postgresql/data/pgdata
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./docker/postgres/postgresql.conf:/etc/postgresql/postgresql.conf
      - ./docker/postgres/init:/docker-entrypoint-initdb.d
    ports:
      - "5432:5432"
    command: postgres -c config_file=/etc/postgresql/postgresql.conf
    networks:
      - backend
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U odoo -d smart_dairy"]
      interval: 10s
      timeout: 5s
      retries: 5
    restart: unless-stopped

  redis:
    image: redis:7-alpine
    container_name: smart_dairy_redis
    command: redis-server --requirepass ${REDIS_PASSWORD:-redis_dev_2026} --maxmemory 256mb --maxmemory-policy allkeys-lru
    volumes:
      - redis_data:/data
    ports:
      - "6379:6379"
    networks:
      - backend
    healthcheck:
      test: ["CMD", "redis-cli", "-a", "${REDIS_PASSWORD:-redis_dev_2026}", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5
    restart: unless-stopped

  odoo:
    build:
      context: .
      dockerfile: docker/odoo/Dockerfile
    container_name: smart_dairy_odoo
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      DB_PASSWORD: ${DB_PASSWORD:-odoo_dev_2026}
      REDIS_URL: redis://:${REDIS_PASSWORD:-redis_dev_2026}@redis:6379/0
      ODOO_MASTER_PASSWORD: ${ODOO_MASTER_PASSWORD:-admin_dev_2026}
    volumes:
      - ./smart_dairy_addons:/opt/odoo/custom-addons
      - odoo_data:/opt/odoo/data
      - odoo_logs:/opt/odoo/logs
    ports:
      - "8069:8069"
      - "8072:8072"
    networks:
      - frontend
      - backend
    restart: unless-stopped

  nginx:
    image: nginx:1.25-alpine
    container_name: smart_dairy_nginx
    depends_on:
      - odoo
    volumes:
      - ./docker/nginx/nginx.conf:/etc/nginx/nginx.conf:ro
      - ./docker/nginx/conf.d:/etc/nginx/conf.d:ro
    ports:
      - "80:80"
      - "443:443"
    networks:
      - frontend
    restart: unless-stopped

  mailhog:
    image: mailhog/mailhog:latest
    container_name: smart_dairy_mailhog
    ports:
      - "1025:1025"
      - "8025:8025"
    networks:
      - backend
    restart: unless-stopped

networks:
  frontend:
    driver: bridge
  backend:
    driver: bridge

volumes:
  postgres_data:
  redis_data:
  odoo_data:
  odoo_logs:
```

**.env.example:**
```env
# Database
DB_PASSWORD=odoo_dev_2026

# Redis
REDIS_PASSWORD=redis_dev_2026

# Odoo
ODOO_MASTER_PASSWORD=admin_dev_2026

# Environment
ENVIRONMENT=development
DEBUG=true
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Write Nginx reverse proxy configuration — 3h
2. Configure hot-reload for frontend development — 2.5h
3. Set up Chrome DevTools remote debugging — 1h
4. Test full stack access through Nginx — 1.5h

**docker/nginx/nginx.conf:**
```nginx
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for" '
                    'rt=$request_time';

    access_log /var/log/nginx/access.log main;

    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    client_max_body_size 50m;
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

    # Rate limiting zones
    limit_req_zone $binary_remote_addr zone=general:10m rate=30r/s;
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;

    upstream odoo {
        server odoo:8069;
    }

    upstream odoo-longpolling {
        server odoo:8072;
    }

    server {
        listen 80;
        server_name localhost;

        # Security headers
        add_header X-Frame-Options "SAMEORIGIN" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header X-XSS-Protection "0" always;
        add_header Referrer-Policy "strict-origin-when-cross-origin" always;

        # Longpolling
        location /longpolling {
            proxy_pass http://odoo-longpolling;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
            proxy_read_timeout 3600;
        }

        # Odoo
        location / {
            limit_req zone=general burst=20 nodelay;
            proxy_pass http://odoo;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
            proxy_redirect off;
            proxy_read_timeout 300;
        }

        # Static files caching
        location ~* /web/static/ {
            proxy_pass http://odoo;
            proxy_cache_valid 200 90m;
            expires 24h;
            add_header Cache-Control "public, immutable";
        }
    }
}
```

**End-of-Day 3 Deliverables:**
- [ ] `docker compose up -d` starts all services cleanly
- [ ] Odoo accessible at `http://localhost:8069` and `http://localhost` (Nginx)
- [ ] PostgreSQL accessible at `localhost:5432`
- [ ] Redis accessible at `localhost:6379`
- [ ] MailHog UI at `http://localhost:8025`
- [ ] All containers healthy (`docker compose ps` shows healthy)

---

### Day 4 — Odoo 19 CE Installation & Base Configuration

**Objective:** Complete Odoo 19 CE installation from source, create Smart Dairy database, install base modules, and configure core server parameters.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Initialize Smart Dairy database via Odoo database manager — 2h
2. Install base modules (base, web, website) — 2h
3. Configure server parameters for development — 2h
4. Verify Odoo admin access and module list — 2h

```bash
# Initialize database (via Odoo CLI)
docker compose exec odoo python3 /opt/odoo/odoo-server/odoo-bin \
  -c /opt/odoo/config/odoo.conf \
  -d smart_dairy \
  -i base,web,website \
  --without-demo=all \
  --stop-after-init

# Verify installation
docker compose exec odoo python3 /opt/odoo/odoo-server/odoo-bin shell \
  -c /opt/odoo/config/odoo.conf -d smart_dairy <<EOF
modules = env['ir.module.module'].search([('state', '=', 'installed')])
for m in modules:
    print(f"{m.name}: {m.state}")
EOF
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Configure Redis as Odoo session store — 3h
2. Configure Odoo email with MailHog — 2h
3. Set up database backup script — 3h

**Redis Session Store (Python patch for Odoo):**
```python
# smart_dairy_addons/smart_security/models/redis_session.py
"""
Redis-based session store for Odoo.
Replaces the default file-based session storage.
"""
import json
import logging
import redis
from odoo.http import Root, Session
from odoo.tools import config

_logger = logging.getLogger(__name__)

REDIS_URL = config.get('redis_url', 'redis://localhost:6379/0')
SESSION_TIMEOUT = int(config.get('session_timeout', 7200))  # 2 hours default

redis_client = redis.Redis.from_url(REDIS_URL, decode_responses=True)


class RedisSessionStore:
    """Store Odoo sessions in Redis for performance and scalability."""

    def __init__(self, session_class=None):
        self.redis = redis_client
        self.prefix = "odoo:session:"
        self.timeout = SESSION_TIMEOUT

    def save(self, session):
        key = self.prefix + session.sid
        data = json.dumps(dict(session))
        self.redis.setex(key, self.timeout, data)

    def delete(self, session):
        key = self.prefix + session.sid
        self.redis.delete(key)

    def get(self, sid):
        key = self.prefix + sid
        data = self.redis.get(key)
        if data:
            self.redis.expire(key, self.timeout)  # Refresh TTL
            return json.loads(data)
        return {}

    def is_valid(self, sid):
        key = self.prefix + sid
        return self.redis.exists(key)
```

**Backup Script (scripts/backup.sh):**
```bash
#!/usr/bin/env bash
set -euo pipefail

# Configuration
BACKUP_DIR="/opt/backups/smart_dairy"
DB_CONTAINER="smart_dairy_db"
DB_USER="odoo"
DB_NAME="smart_dairy"
RETENTION_DAYS=7
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

mkdir -p "${BACKUP_DIR}"

echo "[$(date)] Starting backup of ${DB_NAME}..."

# Database dump (custom format for parallel restore)
docker exec ${DB_CONTAINER} pg_dump \
  -U ${DB_USER} \
  -d ${DB_NAME} \
  -Fc \
  --compress=9 \
  > "${BACKUP_DIR}/${DB_NAME}_${TIMESTAMP}.dump"

# File store backup
docker cp smart_dairy_odoo:/opt/odoo/data "${BACKUP_DIR}/filestore_${TIMESTAMP}"

# Verify backup
FILE_SIZE=$(stat -c %s "${BACKUP_DIR}/${DB_NAME}_${TIMESTAMP}.dump")
if [ "${FILE_SIZE}" -lt 1000 ]; then
    echo "[ERROR] Backup file suspiciously small: ${FILE_SIZE} bytes"
    exit 1
fi

echo "[$(date)] Backup completed: ${BACKUP_DIR}/${DB_NAME}_${TIMESTAMP}.dump (${FILE_SIZE} bytes)"

# Cleanup old backups
find "${BACKUP_DIR}" -name "*.dump" -mtime +${RETENTION_DAYS} -delete
find "${BACKUP_DIR}" -name "filestore_*" -type d -mtime +${RETENTION_DAYS} -exec rm -rf {} +

echo "[$(date)] Old backups cleaned (retention: ${RETENTION_DAYS} days)"
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Customize Odoo login page with Smart Dairy branding — 3h
2. Configure Odoo web client theme — 2.5h
3. Create first OWL test component — 2.5h

**First OWL Component (Hello Smart Dairy):**
```javascript
/** @odoo-module **/
// smart_dairy_addons/smart_farm_mgmt/static/src/components/hello_smart_dairy/hello_smart_dairy.js

import { Component, useState } from "@odoo/owl";
import { registry } from "@web/core/registry";

export class HelloSmartDairy extends Component {
    static template = "smart_farm_mgmt.HelloSmartDairy";
    static props = {};

    setup() {
        this.state = useState({
            farmName: "Smart Dairy Ltd.",
            totalCattle: 255,
            dailyProduction: 900,
            lastUpdated: new Date().toLocaleDateString(),
        });
    }

    get productionPerCow() {
        return (this.state.dailyProduction / 75).toFixed(1);
    }
}

// Register as a systray item for testing
registry.category("systray").add("HelloSmartDairy", {
    Component: HelloSmartDairy,
});
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- smart_dairy_addons/smart_farm_mgmt/static/src/components/hello_smart_dairy/hello_smart_dairy.xml -->
<templates xml:space="preserve">
    <t t-name="smart_farm_mgmt.HelloSmartDairy">
        <div class="sd-hello-widget p-2">
            <span class="fw-bold text-primary" t-esc="state.farmName"/>
            <span class="badge bg-success ms-1" t-esc="state.totalCattle + ' cattle'"/>
        </div>
    </t>
</templates>
```

**End-of-Day 4 Deliverables:**
- [ ] Odoo 19 CE running with `smart_dairy` database
- [ ] Base modules installed (base, web, website)
- [ ] Admin login working at `http://localhost:8069`
- [ ] Redis session store configured
- [ ] MailHog catching Odoo emails
- [ ] Backup script functional
- [ ] First OWL component rendering in Odoo

---

### Day 5 — Database Schema Planning

**Objective:** Design the core database schema per C-001 Database Design Document. Create schemas, key tables, extensions, and roles.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Design and create database schemas — 2h
2. Create core farm tables — 4h
3. Plan table partitioning strategy — 2h

```sql
-- Schema creation
CREATE SCHEMA IF NOT EXISTS farm;
CREATE SCHEMA IF NOT EXISTS sales;
CREATE SCHEMA IF NOT EXISTS inventory;
CREATE SCHEMA IF NOT EXISTS accounting;
CREATE SCHEMA IF NOT EXISTS analytics;
CREATE SCHEMA IF NOT EXISTS audit;
CREATE SCHEMA IF NOT EXISTS iot;

-- Core farm tables (aligned with C-001)
CREATE TABLE farm.breed (
    id              SERIAL PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    code            VARCHAR(10) UNIQUE NOT NULL,
    breed_type      VARCHAR(20) CHECK (breed_type IN ('dairy', 'beef', 'dual', 'indigenous')),
    avg_milk_yield  NUMERIC(6,2),
    description     TEXT,
    created_at      TIMESTAMPTZ DEFAULT NOW(),
    updated_at      TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE farm.animal (
    id                SERIAL PRIMARY KEY,
    name              VARCHAR(100),
    ear_tag           VARCHAR(20) UNIQUE NOT NULL,
    rfid_tag          VARCHAR(50) UNIQUE,
    breed_id          INTEGER REFERENCES farm.breed(id),
    birth_date        DATE,
    gender            VARCHAR(10) CHECK (gender IN ('female', 'male')),
    status            VARCHAR(20) DEFAULT 'active'
                      CHECK (status IN ('calf','heifer','lactating','dry','pregnant',
                                        'bull','sold','dead','culled')),
    sire_id           INTEGER REFERENCES farm.animal(id),
    dam_id            INTEGER REFERENCES farm.animal(id),
    weight_kg         NUMERIC(7,2),
    acquisition_date  DATE,
    acquisition_type  VARCHAR(20) CHECK (acquisition_type IN ('born','purchased','donated')),
    acquisition_cost  NUMERIC(12,2),
    disposal_date     DATE,
    disposal_type     VARCHAR(20),
    barn_id           INTEGER,
    assigned_worker_id INTEGER,
    photo_url         VARCHAR(500),
    notes             TEXT,
    created_at        TIMESTAMPTZ DEFAULT NOW(),
    updated_at        TIMESTAMPTZ DEFAULT NOW()
);

-- Partitioned milk production table (quarterly)
CREATE TABLE farm.milk_production (
    id              SERIAL,
    animal_id       INTEGER NOT NULL REFERENCES farm.animal(id),
    date            DATE NOT NULL,
    session         VARCHAR(10) CHECK (session IN ('morning', 'evening')),
    quantity_liters NUMERIC(6,2) NOT NULL CHECK (quantity_liters >= 0),
    fat_percentage  NUMERIC(4,2) CHECK (fat_percentage BETWEEN 1.0 AND 10.0),
    snf_percentage  NUMERIC(4,2) CHECK (snf_percentage BETWEEN 5.0 AND 15.0),
    protein_pct     NUMERIC(4,2) CHECK (protein_pct BETWEEN 1.0 AND 8.0),
    temperature_c   NUMERIC(4,1),
    quality_grade   VARCHAR(2) CHECK (quality_grade IN ('A', 'B', 'C', 'R')),
    recorder_id     INTEGER,
    notes           TEXT,
    created_at      TIMESTAMPTZ DEFAULT NOW(),
    PRIMARY KEY (id, date)
) PARTITION BY RANGE (date);

-- Create quarterly partitions for current year
CREATE TABLE farm.milk_production_2026_q1
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-01-01') TO ('2026-04-01');

CREATE TABLE farm.milk_production_2026_q2
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-04-01') TO ('2026-07-01');

CREATE TABLE farm.milk_production_2026_q3
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-07-01') TO ('2026-10-01');

CREATE TABLE farm.milk_production_2026_q4
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-10-01') TO ('2027-01-01');

-- Health records
CREATE TABLE farm.health_record (
    id              SERIAL PRIMARY KEY,
    animal_id       INTEGER NOT NULL REFERENCES farm.animal(id),
    date            TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    record_type     VARCHAR(20) NOT NULL
                    CHECK (record_type IN ('checkup','symptom','diagnosis',
                                           'treatment','surgery','vaccination','deworming')),
    description     TEXT,
    diagnosis       TEXT,
    treatment       TEXT,
    medication      VARCHAR(200),
    dosage          VARCHAR(100),
    cost            NUMERIC(10,2),
    veterinarian_id INTEGER,
    follow_up_date  DATE,
    withdrawal_end  DATE,
    status          VARCHAR(20) DEFAULT 'active'
                    CHECK (status IN ('active','resolved','chronic','follow_up')),
    created_at      TIMESTAMPTZ DEFAULT NOW()
);

-- Breeding records
CREATE TABLE farm.breeding_record (
    id                    SERIAL PRIMARY KEY,
    animal_id             INTEGER NOT NULL REFERENCES farm.animal(id),
    date                  DATE NOT NULL,
    breeding_type         VARCHAR(10) CHECK (breeding_type IN ('natural','ai','et')),
    sire_id               INTEGER REFERENCES farm.animal(id),
    semen_batch           VARCHAR(50),
    ai_technician_id      INTEGER,
    heat_detection_method VARCHAR(50),
    pregnancy_check_date  DATE,
    pregnancy_status      VARCHAR(20) DEFAULT 'pending'
                          CHECK (pregnancy_status IN ('pending','confirmed','negative','aborted')),
    expected_calving_date DATE,
    actual_calving_date   DATE,
    calving_difficulty    INTEGER CHECK (calving_difficulty BETWEEN 1 AND 5),
    calf_id               INTEGER REFERENCES farm.animal(id),
    notes                 TEXT,
    created_at            TIMESTAMPTZ DEFAULT NOW()
);

-- Indexes (per C-001 indexing strategy)
CREATE INDEX idx_animal_status ON farm.animal(status);
CREATE INDEX idx_animal_breed ON farm.animal(breed_id);
CREATE INDEX idx_animal_ear_tag ON farm.animal(ear_tag);
CREATE INDEX idx_milk_animal_date ON farm.milk_production(animal_id, date);
CREATE INDEX idx_health_animal_date ON farm.health_record(animal_id, date);
CREATE INDEX idx_breeding_animal ON farm.breeding_record(animal_id);
CREATE INDEX idx_breeding_pregnancy ON farm.breeding_record(pregnancy_status)
    WHERE pregnancy_status IN ('pending', 'confirmed');
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Install PostgreSQL extensions — 2h
2. Create database roles and permissions — 3h
3. Configure pgBouncer for connection pooling — 3h

```sql
-- Extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "btree_gin";
CREATE EXTENSION IF NOT EXISTS "pg_stat_statements";

-- Database roles
CREATE ROLE smart_dairy_admin WITH LOGIN PASSWORD 'admin_secure_pw' CREATEDB CREATEROLE;
CREATE ROLE smart_dairy_app WITH LOGIN PASSWORD 'app_secure_pw';
CREATE ROLE smart_dairy_readonly WITH LOGIN PASSWORD 'readonly_secure_pw';
CREATE ROLE smart_dairy_backup WITH LOGIN PASSWORD 'backup_secure_pw';

-- Grant permissions
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA farm TO smart_dairy_admin;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA farm TO smart_dairy_app;
GRANT SELECT ON ALL TABLES IN SCHEMA farm TO smart_dairy_readonly;
GRANT SELECT ON ALL TABLES IN SCHEMA farm TO smart_dairy_backup;

-- Default privileges for future tables
ALTER DEFAULT PRIVILEGES IN SCHEMA farm
    GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO smart_dairy_app;
ALTER DEFAULT PRIVILEGES IN SCHEMA farm
    GRANT SELECT ON TABLES TO smart_dairy_readonly;
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Design frontend data models and TypeScript interfaces — 4h
2. Plan OWL store architecture — 4h

```typescript
// frontend/src/types/farm.ts

export interface Animal {
    id: number;
    name: string;
    ear_tag: string;
    rfid_tag?: string;
    breed_id: number;
    breed_name?: string;
    birth_date: string;
    gender: 'female' | 'male';
    status: AnimalStatus;
    sire_id?: number;
    dam_id?: number;
    weight_kg?: number;
    photo_url?: string;
    // Computed
    age_months?: number;
    daily_avg_yield?: number;
    days_in_milk?: number;
}

export type AnimalStatus =
    | 'calf' | 'heifer' | 'lactating' | 'dry'
    | 'pregnant' | 'bull' | 'sold' | 'dead' | 'culled';

export interface MilkRecord {
    id: number;
    animal_id: number;
    date: string;
    session: 'morning' | 'evening';
    quantity_liters: number;
    fat_percentage: number;
    snf_percentage: number;
    protein_pct?: number;
    temperature_c?: number;
    quality_grade: 'A' | 'B' | 'C' | 'R';
}

export interface HealthRecord {
    id: number;
    animal_id: number;
    date: string;
    record_type: 'checkup' | 'symptom' | 'diagnosis' | 'treatment'
                | 'surgery' | 'vaccination' | 'deworming';
    description?: string;
    diagnosis?: string;
    treatment?: string;
    medication?: string;
    cost?: number;
    status: 'active' | 'resolved' | 'chronic' | 'follow_up';
}

export interface BreedingRecord {
    id: number;
    animal_id: number;
    date: string;
    breeding_type: 'natural' | 'ai' | 'et';
    pregnancy_status: 'pending' | 'confirmed' | 'negative' | 'aborted';
    expected_calving_date?: string;
    actual_calving_date?: string;
}
```

**End-of-Day 5 Deliverables:**
- [ ] All 7 database schemas created (farm, sales, inventory, accounting, analytics, audit, iot)
- [ ] Core farm tables created with constraints and indexes
- [ ] Milk production table partitioned quarterly
- [ ] PostgreSQL extensions installed (uuid-ossp, pgcrypto, pg_trgm, pg_stat_statements)
- [ ] Database roles created with appropriate permissions
- [ ] Frontend TypeScript interfaces defined for all entities

---

### Day 6 — Security Foundations

**Objective:** Establish the security baseline for the development environment: SSH keys, SSL certificates, secrets management, and secure configuration.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Generate SSH key pairs and configure Git SSH — 2h
2. Create SSL certificate management script — 2.5h
3. Design `.env` file structure with secrets — 2h
4. Document secrets management procedures — 1.5h

```bash
# SSH key generation for each developer
ssh-keygen -t ed25519 -C "dev1@smartdairy.com" -f ~/.ssh/smart_dairy_dev1

# Add to GitHub
cat ~/.ssh/smart_dairy_dev1.pub
# → Copy to GitHub Settings → SSH keys

# Configure SSH for GitHub
cat >> ~/.ssh/config << 'EOF'
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/smart_dairy_dev1
    IdentitiesOnly yes
EOF
```

**SSL Certificate Script (scripts/generate_dev_certs.sh):**
```bash
#!/usr/bin/env bash
set -euo pipefail

CERT_DIR="./docker/nginx/ssl"
mkdir -p "${CERT_DIR}"

# Generate self-signed CA
openssl req -x509 -nodes -newkey rsa:4096 \
    -keyout "${CERT_DIR}/ca-key.pem" \
    -out "${CERT_DIR}/ca-cert.pem" \
    -days 365 \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy Dev/CN=Smart Dairy Dev CA"

# Generate server certificate
openssl req -nodes -newkey rsa:2048 \
    -keyout "${CERT_DIR}/server-key.pem" \
    -out "${CERT_DIR}/server-req.pem" \
    -subj "/C=BD/ST=Dhaka/L=Dhaka/O=Smart Dairy/CN=localhost"

# Sign with CA
openssl x509 -req -in "${CERT_DIR}/server-req.pem" \
    -CA "${CERT_DIR}/ca-cert.pem" \
    -CAkey "${CERT_DIR}/ca-key.pem" \
    -CAcreateserial \
    -out "${CERT_DIR}/server-cert.pem" \
    -days 365

echo "Certificates generated in ${CERT_DIR}"
chmod 600 "${CERT_DIR}"/*.pem
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Configure Docker secrets management — 2.5h
2. Set up HashiCorp Vault development container — 3h
3. Configure PostgreSQL SSL connections — 2.5h

**Docker Compose addition for Vault:**
```yaml
  vault:
    image: hashicorp/vault:1.15
    container_name: smart_dairy_vault
    cap_add:
      - IPC_LOCK
    environment:
      VAULT_DEV_ROOT_TOKEN_ID: dev-root-token-2026
      VAULT_DEV_LISTEN_ADDRESS: 0.0.0.0:8200
    ports:
      - "8200:8200"
    networks:
      - backend
    restart: unless-stopped
```

```bash
# Initialize Vault secrets for Smart Dairy
export VAULT_ADDR='http://localhost:8200'
export VAULT_TOKEN='dev-root-token-2026'

# Enable KV secrets engine
vault secrets enable -path=smart-dairy kv-v2

# Store application secrets
vault kv put smart-dairy/database \
    host=db port=5432 user=odoo password=odoo_dev_2026 name=smart_dairy

vault kv put smart-dairy/redis \
    url=redis://:redis_dev_2026@redis:6379/0

vault kv put smart-dairy/odoo \
    master_password=admin_dev_2026

vault kv put smart-dairy/encryption \
    fernet_key=$(python3 -c "from cryptography.fernet import Fernet; print(Fernet.generate_key().decode())")
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Configure Content Security Policy headers in Nginx — 3h
2. Set up HTTPS for local development with mkcert — 2.5h
3. Configure secure cookie settings — 2.5h

```bash
# Install mkcert for trusted local HTTPS
sudo apt install -y libnss3-tools
curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64"
chmod +x mkcert-*-linux-amd64
sudo mv mkcert-*-linux-amd64 /usr/local/bin/mkcert

mkcert -install
mkcert localhost 127.0.0.1 ::1
# → Generates localhost+2.pem and localhost+2-key.pem
```

**End-of-Day 6 Deliverables:**
- [ ] SSH keys configured for all developers with GitHub access
- [ ] SSL certificates generated for development environment
- [ ] `.env.example` and `.env` structure documented
- [ ] HashiCorp Vault running and seeded with dev secrets
- [ ] PostgreSQL SSL enabled
- [ ] Nginx HTTPS configured for local development
- [ ] Security documentation committed

---

### Day 7 — CI/CD Pipeline — Build Stage

**Objective:** Implement the CI/CD pipeline using GitHub Actions with lint, test, build, and deploy-staging stages.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Write Python linting workflow (flake8, pylint-odoo) — 3h
2. Write unit test runner workflow — 3h
3. Configure code coverage reporting — 2h

```yaml
# .github/workflows/python-lint.yml
name: Python Lint

on:
  pull_request:
    paths:
      - 'smart_dairy_addons/**'
      - 'smart_dairy_api/**'
      - '*.py'

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Set up Python 3.11
        uses: actions/setup-python@v5
        with:
          python-version: '3.11'
          cache: 'pip'

      - name: Install dependencies
        run: |
          pip install flake8 pylint pylint-odoo isort black bandit

      - name: Run Black (format check)
        run: black --check --line-length 120 smart_dairy_addons/ smart_dairy_api/

      - name: Run isort (import order check)
        run: isort --check-only --profile black --line-length 120 smart_dairy_addons/ smart_dairy_api/

      - name: Run Flake8
        run: flake8 smart_dairy_addons/ smart_dairy_api/ --max-line-length 120

      - name: Run Bandit (security)
        run: bandit -r smart_dairy_addons/ smart_dairy_api/ -c pyproject.toml
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Write main CI pipeline YAML — 3h
2. Configure Docker image build and push — 2.5h
3. Set up GitHub Actions secrets — 2.5h

```yaml
# .github/workflows/ci.yml
name: Smart Dairy CI

on:
  push:
    branches: [develop, main]
  pull_request:
    branches: [develop, main]

env:
  PYTHON_VERSION: '3.11'
  NODE_VERSION: '20'
  REGISTRY: ghcr.io
  IMAGE_NAME: ${{ github.repository }}

jobs:
  # Stage 1: Lint
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}
          cache: 'pip'
      - run: pip install -r requirements-dev.txt
      - run: black --check --line-length 120 smart_dairy_addons/ smart_dairy_api/
      - run: isort --check-only --profile black smart_dairy_addons/ smart_dairy_api/
      - run: flake8 smart_dairy_addons/ smart_dairy_api/ --max-line-length 120
      - run: bandit -r smart_dairy_addons/ smart_dairy_api/ -c pyproject.toml

  # Stage 2: Test
  test:
    needs: lint
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_USER: odoo
          POSTGRES_PASSWORD: test_password
          POSTGRES_DB: smart_dairy_test
        ports: ['5432:5432']
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
      redis:
        image: redis:7-alpine
        ports: ['6379:6379']
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}
          cache: 'pip'
      - run: pip install -r requirements-dev.txt
      - name: Run Tests
        env:
          DB_HOST: localhost
          DB_PORT: 5432
          DB_USER: odoo
          DB_PASSWORD: test_password
          REDIS_URL: redis://localhost:6379/0
        run: |
          pytest smart_dairy_addons/ smart_dairy_api/ \
            --cov --cov-report=xml --cov-report=html \
            --junitxml=test-results.xml -v
      - name: Upload Coverage
        uses: codecov/codecov-action@v4
        with:
          file: ./coverage.xml
      - name: Upload Test Results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: test-results
          path: test-results.xml

  # Stage 3: Build Docker Image
  build:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/develop' || github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v4
      - uses: docker/setup-buildx-action@v3
      - uses: docker/login-action@v3
        with:
          registry: ${{ env.REGISTRY }}
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}
      - uses: docker/build-push-action@v5
        with:
          context: .
          file: docker/odoo/Dockerfile
          push: true
          tags: |
            ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:${{ github.sha }}
            ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}:latest
          cache-from: type=gha
          cache-to: type=gha,mode=max
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Write frontend linting workflow — 2.5h
2. Set up Flutter CI pipeline — 3h
3. Configure Lighthouse CI — 2.5h

```yaml
# .github/workflows/frontend.yml
name: Frontend CI

on:
  pull_request:
    paths:
      - 'frontend/**'
      - 'smart_dairy_addons/**/static/**'

jobs:
  lint-frontend:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
          cache-dependency-path: frontend/package-lock.json
      - run: cd frontend && npm ci
      - run: cd frontend && npm run lint
      - run: cd frontend && npm run format:check

  flutter-test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
      - run: cd mobile && flutter pub get
      - run: cd mobile && flutter analyze
      - run: cd mobile && flutter test --coverage
```

**End-of-Day 7 Deliverables:**
- [ ] CI pipeline triggers on push to develop/main
- [ ] Lint stage runs (Black, Flake8, isort, Bandit)
- [ ] Test stage runs with PostgreSQL and Redis services
- [ ] Build stage creates Docker image on develop/main
- [ ] Frontend linting workflow operational
- [ ] Flutter CI pipeline operational
- [ ] Code coverage reporting configured

---

### Day 8 — Monitoring & Logging Infrastructure

**Objective:** Deploy Prometheus + Grafana for metrics monitoring and ELK stack for centralized log aggregation.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Configure Odoo structured logging — 3h
2. Create custom log formatters (JSON format) — 2.5h
3. Set up log rotation — 2.5h

```python
# smart_dairy_addons/smart_security/models/logging_config.py
"""
Structured JSON logging for Smart Dairy Odoo instance.
Enables log aggregation via ELK stack.
"""
import json
import logging
import traceback
from datetime import datetime

class JsonFormatter(logging.Formatter):
    """Format log records as JSON for ELK Stack ingestion."""

    def format(self, record):
        log_entry = {
            "timestamp": datetime.utcnow().isoformat() + "Z",
            "level": record.levelname,
            "logger": record.name,
            "message": record.getMessage(),
            "module": record.module,
            "function": record.funcName,
            "line": record.lineno,
            "process_id": record.process,
            "thread_id": record.thread,
        }

        if record.exc_info:
            log_entry["exception"] = {
                "type": record.exc_info[0].__name__ if record.exc_info[0] else None,
                "message": str(record.exc_info[1]) if record.exc_info[1] else None,
                "traceback": traceback.format_exception(*record.exc_info),
            }

        if hasattr(record, 'request_id'):
            log_entry["request_id"] = record.request_id

        if hasattr(record, 'user_id'):
            log_entry["user_id"] = record.user_id

        return json.dumps(log_entry)
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Deploy Prometheus with exporters — 3h
2. Deploy Grafana with dashboards — 3h
3. Configure alerting rules — 2h

**docker-compose.monitoring.yml:**
```yaml
version: "3.8"

services:
  prometheus:
    image: prom/prometheus:v2.49.0
    container_name: smart_dairy_prometheus
    volumes:
      - ./docker/monitoring/prometheus.yml:/etc/prometheus/prometheus.yml
      - ./docker/monitoring/alert_rules.yml:/etc/prometheus/alert_rules.yml
      - prometheus_data:/prometheus
    ports:
      - "9090:9090"
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.retention.time=30d'
    networks:
      - monitoring

  grafana:
    image: grafana/grafana:10.3.0
    container_name: smart_dairy_grafana
    environment:
      GF_SECURITY_ADMIN_PASSWORD: grafana_dev_2026
      GF_USERS_ALLOW_SIGN_UP: 'false'
    volumes:
      - ./docker/monitoring/grafana/provisioning:/etc/grafana/provisioning
      - grafana_data:/var/lib/grafana
    ports:
      - "3000:3000"
    depends_on:
      - prometheus
    networks:
      - monitoring

  postgres-exporter:
    image: prometheuscommunity/postgres-exporter:v0.15.0
    container_name: smart_dairy_pg_exporter
    environment:
      DATA_SOURCE_NAME: "postgresql://odoo:${DB_PASSWORD:-odoo_dev_2026}@db:5432/smart_dairy?sslmode=disable"
    ports:
      - "9187:9187"
    networks:
      - monitoring
      - backend

  redis-exporter:
    image: oliver006/redis_exporter:v1.56.0
    container_name: smart_dairy_redis_exporter
    environment:
      REDIS_ADDR: "redis://redis:6379"
      REDIS_PASSWORD: "${REDIS_PASSWORD:-redis_dev_2026}"
    ports:
      - "9121:9121"
    networks:
      - monitoring
      - backend

  node-exporter:
    image: prom/node-exporter:v1.7.0
    container_name: smart_dairy_node_exporter
    ports:
      - "9100:9100"
    networks:
      - monitoring

volumes:
  prometheus_data:
  grafana_data:

networks:
  monitoring:
    driver: bridge
```

**docker/monitoring/prometheus.yml:**
```yaml
global:
  scrape_interval: 15s
  evaluation_interval: 15s

rule_files:
  - "alert_rules.yml"

scrape_configs:
  - job_name: 'prometheus'
    static_configs:
      - targets: ['localhost:9090']

  - job_name: 'node'
    static_configs:
      - targets: ['node-exporter:9100']

  - job_name: 'postgres'
    static_configs:
      - targets: ['postgres-exporter:9187']

  - job_name: 'redis'
    static_configs:
      - targets: ['redis-exporter:9121']
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Deploy ELK Stack (Elasticsearch + Logstash + Kibana) — 4h
2. Configure Logstash pipelines — 2.5h
3. Create Kibana dashboards — 1.5h

**docker-compose.logging.yml:**
```yaml
version: "3.8"

services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.12.0
    container_name: smart_dairy_elasticsearch
    environment:
      - discovery.type=single-node
      - xpack.security.enabled=false
      - "ES_JAVA_OPTS=-Xms512m -Xmx512m"
    volumes:
      - es_data:/usr/share/elasticsearch/data
    ports:
      - "9200:9200"
    networks:
      - logging

  logstash:
    image: docker.elastic.co/logstash/logstash:8.12.0
    container_name: smart_dairy_logstash
    volumes:
      - ./docker/monitoring/logstash/pipeline:/usr/share/logstash/pipeline
    ports:
      - "5044:5044"
    depends_on:
      - elasticsearch
    networks:
      - logging

  kibana:
    image: docker.elastic.co/kibana/kibana:8.12.0
    container_name: smart_dairy_kibana
    environment:
      ELASTICSEARCH_HOSTS: http://elasticsearch:9200
    ports:
      - "5601:5601"
    depends_on:
      - elasticsearch
    networks:
      - logging

volumes:
  es_data:

networks:
  logging:
    driver: bridge
```

**End-of-Day 8 Deliverables:**
- [ ] Prometheus scraping metrics from PostgreSQL, Redis, Node exporters
- [ ] Grafana accessible at `http://localhost:3000` with system dashboards
- [ ] ELK stack running: Elasticsearch (9200), Logstash (5044), Kibana (5601)
- [ ] Odoo logs formatted as JSON and shipped to Logstash
- [ ] Nginx access logs shipped to Logstash
- [ ] Basic alerting rules configured (disk, memory, CPU)

---

### Day 9 — Development Documentation & Coding Standards

**Objective:** Author comprehensive development documentation including setup guide, coding standards, API conventions, and contribution workflow.

#### Dev 1 — Backend Lead (8h)

**Tasks:**
1. Write development setup guide (README.md) — 2h
2. Write Odoo module development standards — 3h
3. Write API design guidelines per E-001 — 3h

**Key Documentation Content:**
- Module naming convention: `smart_` prefix for all custom modules
- Model naming: `smart.dairy.model_name` (dot notation)
- Field naming: `snake_case`, boolean fields prefixed with `is_` or `has_`
- Method naming: `action_` for button actions, `_compute_` for computed fields, `_check_` for constraints
- SQL: Never raw SQL in models; use ORM methods; parameterized queries only in reports

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**
1. Write Docker development workflow guide — 2h
2. Write CI/CD pipeline usage documentation — 2h
3. Write environment configuration guide — 2h
4. Write deployment procedures document — 2h

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**
1. Write OWL component development standards — 3h
2. Write CSS/SCSS conventions — 2h
3. Write mobile development setup guide — 2h
4. Write accessibility guidelines — 1h

**End-of-Day 9 Deliverables:**
- [ ] `docs/setup-guide.md` — Complete development environment setup
- [ ] `docs/coding-standards.md` — Python, OWL, CSS, SQL standards
- [ ] `docs/api-guidelines.md` — REST API conventions per E-001
- [ ] `docs/docker-workflow.md` — Docker development procedures
- [ ] `docs/cicd-guide.md` — CI/CD pipeline usage
- [ ] `docs/frontend-guide.md` — OWL and frontend standards
- [ ] `docs/mobile-guide.md` — Flutter development guide
- [ ] `docs/accessibility.md` — WCAG 2.1 AA compliance guide

---

### Day 10 — Milestone 1 Review & Sprint Planning

**Objective:** Conduct sprint review, retrospective, demonstrate all infrastructure to stakeholders, and plan Milestone 2.

#### All Developers — Collaborative Day (8h each)

**Morning (9:00 – 12:00) — Sprint Review & Demo (3h)**

| Time  | Activity                            | Presenter |
|-------|-------------------------------------|-----------|
| 9:00  | Milestone 1 scope recap             | Dev 2     |
| 9:15  | Docker environment demo             | Dev 2     |
| 9:45  | Odoo 19 CE walkthrough              | Dev 1     |
| 10:15 | Database schema review              | Dev 1     |
| 10:45 | CI/CD pipeline demo (trigger build) | Dev 2     |
| 11:00 | Monitoring dashboards walkthrough   | Dev 2     |
| 11:20 | ELK log analysis demo               | Dev 3     |
| 11:40 | Frontend tooling & OWL demo         | Dev 3     |
| 11:55 | Q&A                                 | All       |

**Afternoon (13:00 – 15:00) — Sprint Retrospective (2h)**

| Category        | Template Questions                                    |
|----------------|-------------------------------------------------------|
| What went well  | What tools/processes worked smoothly?                 |
| What didn't     | What caused delays or frustration?                    |
| Action items    | What specific changes for Milestone 2?                |
| Risks surfaced  | Any new technical risks identified?                   |

**Late Afternoon (15:00 – 17:00) — Milestone 2 Planning (2h)**

- Review Milestone 2 scope (Core Odoo Modules)
- Break down tasks per developer
- Identify dependencies and blockers
- Commit planning artifacts to repository

**Milestone 1 Completion Checklist:**

| #  | Deliverable                    | Status | Verified By |
|----|-------------------------------|--------|-------------|
| 1  | Ubuntu 24.04 LTS on all devs  | ☐      |             |
| 2  | Git repo with GitFlow          | ☐      |             |
| 3  | Docker Compose environment     | ☐      |             |
| 4  | Odoo 19 CE running             | ☐      |             |
| 5  | PostgreSQL 16 with schema      | ☐      |             |
| 6  | Redis 7 session store          | ☐      |             |
| 7  | CI/CD pipeline (lint+test+build)| ☐     |             |
| 8  | Prometheus + Grafana           | ☐      |             |
| 9  | ELK logging stack              | ☐      |             |
| 10 | Development documentation      | ☐      |             |
| 11 | Coding standards committed     | ☐      |             |
| 12 | Security foundations (SSL, Vault)| ☐    |             |

**End-of-Day 10 Deliverables:**
- [ ] Sprint review presentation completed
- [ ] Retrospective action items documented
- [ ] Milestone 2 sprint backlog created
- [ ] All Milestone 1 artifacts committed and tagged
- [ ] Git tag `v0.0.1-milestone-1` created

---

## 4. Technical Specifications

### 4.1 System Requirements

| Component        | Minimum        | Recommended     |
|-----------------|----------------|-----------------|
| OS              | Ubuntu 22.04   | Ubuntu 24.04    |
| RAM             | 16 GB          | 32 GB           |
| CPU             | 4 cores        | 8 cores         |
| Disk            | 256 GB SSD     | 512 GB NVMe     |
| Docker Engine   | 24.0           | Latest stable   |
| Python          | 3.11           | 3.11.x          |
| Node.js         | 20 LTS         | 20 LTS          |
| Flutter         | 3.16           | Latest stable   |

### 4.2 Docker Architecture

```
┌─────────────────────────────────────────────────────┐
│                   Host Machine                       │
│  ┌─────────────────────────────────────────────────┐│
│  │              Docker Engine                       ││
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐     ││
│  │  │  Nginx   │  │   Odoo   │  │ FastAPI  │     ││
│  │  │  :80/443 │──│  :8069   │  │  :8000   │     ││
│  │  └──────────┘  └──────────┘  └──────────┘     ││
│  │       │              │              │           ││
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐     ││
│  │  │ Postgres │  │  Redis   │  │ MailHog  │     ││
│  │  │  :5432   │  │  :6379   │  │  :8025   │     ││
│  │  └──────────┘  └──────────┘  └──────────┘     ││
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐     ││
│  │  │Prometheus│  │ Grafana  │  │  Kibana  │     ││
│  │  │  :9090   │  │  :3000   │  │  :5601   │     ││
│  │  └──────────┘  └──────────┘  └──────────┘     ││
│  └─────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────┘
```

### 4.3 Port Allocation

| Port  | Service              | Network   |
|-------|---------------------|-----------|
| 80    | Nginx (HTTP)         | frontend  |
| 443   | Nginx (HTTPS)        | frontend  |
| 5432  | PostgreSQL 16        | backend   |
| 6379  | Redis 7              | backend   |
| 8069  | Odoo HTTP            | both      |
| 8072  | Odoo Longpolling     | both      |
| 8025  | MailHog UI           | backend   |
| 8200  | HashiCorp Vault      | backend   |
| 9090  | Prometheus           | monitoring|
| 9100  | Node Exporter        | monitoring|
| 9121  | Redis Exporter       | monitoring|
| 9187  | PostgreSQL Exporter  | monitoring|
| 9200  | Elasticsearch        | logging   |
| 3000  | Grafana              | monitoring|
| 5044  | Logstash             | logging   |
| 5601  | Kibana               | logging   |

---

## 5. Testing & Validation

### 5.1 Environment Verification Tests

```bash
#!/usr/bin/env bash
# scripts/verify_environment.sh

echo "=== Smart Dairy Environment Verification ==="

# Check Docker services
echo "--- Docker Services ---"
docker compose ps --format "table {{.Name}}\t{{.Status}}\t{{.Ports}}"

# Check Odoo
echo "--- Odoo Health ---"
curl -s -o /dev/null -w "HTTP %{http_code}" http://localhost:8069/web/login

# Check PostgreSQL
echo "--- PostgreSQL ---"
docker compose exec db pg_isready -U odoo -d smart_dairy

# Check Redis
echo "--- Redis ---"
docker compose exec redis redis-cli -a redis_dev_2026 ping

# Check Prometheus
echo "--- Prometheus ---"
curl -s http://localhost:9090/-/healthy

# Check Grafana
echo "--- Grafana ---"
curl -s -o /dev/null -w "HTTP %{http_code}" http://localhost:3000/api/health

# Check Elasticsearch
echo "--- Elasticsearch ---"
curl -s http://localhost:9200/_cluster/health | python3 -m json.tool

echo "=== Verification Complete ==="
```

---

## 6. Risk & Mitigation

| # | Risk                                | Probability | Impact   | Mitigation                                                  |
|---|-------------------------------------|-------------|----------|--------------------------------------------------------------|
| 1 | Docker compatibility issues         | Medium      | High     | Test on clean Ubuntu 24.04 VM; document workarounds          |
| 2 | Odoo 19 CE source unavailable       | Low         | Critical | Mirror Odoo source; maintain local Git clone                 |
| 3 | PostgreSQL 16 perf on dev hardware  | Low         | Medium   | Tune postgresql.conf for dev resources; reduce shared_buffers|
| 4 | CI/CD pipeline complexity           | Medium      | Medium   | Start simple (lint+test); iterate in later milestones        |
| 5 | ELK stack memory consumption        | Medium      | Medium   | Limit ES heap to 512MB; use single-node mode                 |
| 6 | Pre-commit hooks slowing commits    | Low         | Low      | Exclude slow checks from pre-commit; run in CI only          |
| 7 | Developer skill gap (Docker/Odoo)   | Medium      | High     | Pair programming; allocate Day 9 for documentation           |

---

## 7. Dependencies & Handoffs

### 7.1 External Dependencies

| Dependency              | Required By | Status    |
|------------------------|-------------|-----------|
| GitHub organization     | Day 2       | Needed    |
| Odoo 19 CE source code  | Day 3       | Available |
| Docker Hub access        | Day 3       | Available |
| Smart Dairy brand assets | Day 4       | Needed    |

### 7.2 Outputs for Milestone 2

| Output                            | Used In Milestone 2 For             |
|-----------------------------------|--------------------------------------|
| Docker environment with Odoo 19   | Installing/configuring core modules  |
| PostgreSQL 16 with schemas        | Module data storage                  |
| CI/CD pipeline                    | Validating module installations      |
| Monitoring stack                  | Tracking module performance          |
| Coding standards                  | Guiding module customization code    |
| Redis session store               | Session management for modules       |

---

*End of Milestone 1 Document*
*Next: [Milestone 2 — Core Odoo Modules Installation & Configuration](./Milestone_02_Core_Odoo_Modules.md)*
