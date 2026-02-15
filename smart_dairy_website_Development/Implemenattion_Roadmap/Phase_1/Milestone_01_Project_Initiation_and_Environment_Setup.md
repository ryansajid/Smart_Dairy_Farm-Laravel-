# Milestone 01: Project Initiation and Environment Setup

## Smart Dairy Smart Portal + ERP System Implementation

---

**Document Version:** 1.0  
**Last Updated:** 2026-02-02  
**Project:** Smart Dairy Smart Portal + ERP System  
**Milestone Duration:** Days 1-10  
**Status:** Draft  
**Classification:** Internal Technical Documentation  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Milestone Overview and Objectives](#2-milestone-overview-and-objectives)
3. [Pre-Implementation Activities (Day 1-2)](#3-pre-implementation-activities-day-1-2)
4. [Development Environment Setup (Day 3-5)](#4-development-environment-setup-day-3-5)
5. [Infrastructure Provisioning (Day 6-7)](#5-infrastructure-provisioning-day-6-7)
6. [Tools and Access Configuration (Day 8-9)](#6-tools-and-access-configuration-day-8-9)
7. [Milestone Review and Sign-off (Day 10)](#7-milestone-review-and-sign-off-day-10)
8. [Team Structure and Responsibilities](#8-team-structure-and-responsibilities)
9. [Technical Specifications](#9-technical-specifications)
10. [Configuration Code Snippets](#10-configuration-code-snippets)
11. [Setup Scripts](#11-setup-scripts)
12. [Verification Checklists](#12-verification-checklists)
13. [Deliverables](#13-deliverables)
14. [Risk Management](#14-risk-management)
15. [Appendices](#15-appendices)

---

## 1. Executive Summary

This document provides comprehensive technical guidance for Milestone 01 of the Smart Dairy Smart Portal + ERP System implementation project. Milestone 01 encompasses the critical initial phase of project initiation and environment setup, spanning Days 1-10 of the overall project timeline.

The primary objective of this milestone is to establish a robust, scalable, and secure foundation for the entire Smart Dairy ecosystem. This includes configuring development environments, provisioning infrastructure resources, establishing version control workflows, and implementing continuous integration and continuous deployment (CI/CD) pipelines.

### 1.1 Project Context

Smart Dairy is an integrated dairy farm management platform combining:
- B2B Marketplace for dairy products and livestock trading
- Smart Farm Management System with IoT integration
- Enterprise Resource Planning (ERP) system based on Odoo
- Business Intelligence and Analytics dashboard
- Administrative Portal for system management

### 1.2 Milestone 01 Critical Success Factors

| Factor | Target | Measurement |
|--------|--------|-------------|
| Development Environment Availability | 100% | All 3 developers fully operational |
| Infrastructure Uptime | 99.9% | Staging environment accessible |
| Code Repository Setup | Complete | Git workflow established |
| CI/CD Pipeline | Operational | Automated build and deployment |
| Documentation Standards | Established | Technical documentation framework |

### 1.3 Stakeholders

| Role | Name/Team | Responsibility |
|------|-----------|----------------|
| Project Manager | PM Team | Overall milestone coordination |
| Technical Lead | Dev-Lead | Technical architecture and oversight |
| Backend Developer | Dev-1 | Server-side implementation |
| Frontend Developer | Dev-2 | Client-side implementation |
| DevOps Engineer | External | Infrastructure support |
| QA Lead | QA Team | Testing environment validation |

---

## 2. Milestone Overview and Objectives

### 2.1 Milestone Scope

Milestone 01: Project Initiation and Environment Setup establishes the technical foundation upon which all subsequent development activities will be built. This milestone is categorized as a "Foundation Milestone" - meaning its successful completion is a prerequisite for all future milestones.

#### 2.1.1 Scope Boundaries

**In-Scope:**
- All development environment configurations
- Infrastructure provisioning for development and staging
- Version control system setup and workflow definition
- CI/CD pipeline establishment
- Tool configuration and standardization
- Initial security baseline implementation
- Documentation framework establishment

**Out-of-Scope:**
- Production environment deployment
- Application feature development
- User interface design implementation
- Database schema design (covered in Milestone 02)
- Third-party service integrations

### 2.2 Primary Objectives

#### Objective 1: Development Environment Standardization
Establish consistent, reproducible development environments across all team members to eliminate "works on my machine" issues and ensure code portability.

**Success Criteria:**
- All developers can run the full application stack locally
- Environment setup time for new team members < 30 minutes
- Configuration drift monitoring implemented

#### Objective 2: Infrastructure Foundation
Provision and configure the necessary cloud infrastructure to support development, staging, and eventual production workloads.

**Success Criteria:**
- Staging environment fully operational
- Network security groups configured
- Backup and disaster recovery procedures established

#### Objective 3: Development Workflow Implementation
Implement Git-based version control with branching strategy, code review processes, and automated quality checks.

**Success Criteria:**
- Git repository structure defined and implemented
- Pull request workflow documented and enforced
- Pre-commit hooks configured

#### Objective 4: CI/CD Pipeline Establishment
Create automated pipelines for continuous integration (building, testing) and continuous deployment (staging environment).

**Success Criteria:**
- Automated builds triggered on code push
- Automated testing execution
- Automated deployment to staging environment

#### Objective 5: Toolchain Standardization
Standardize development tools, code editors, extensions, and productivity utilities across the team.

**Success Criteria:**
- IDE configuration shared and version-controlled
- Required extensions documented and installed
- Linting and formatting tools configured

### 2.3 Milestone Timeline

```
Day 1-2:  ████████████████████ Pre-Implementation Activities
Day 3-5:  ██████████████████████████████ Development Environment Setup
Day 6-7:  ████████████████ Infrastructure Provisioning
Day 8-9:  ████████████████ Tools and Access Configuration
Day 10:   ████████ Milestone Review and Sign-off
```

### 2.4 Dependencies and Prerequisites

#### Prerequisites (Must be completed before Milestone 01):
- [ ] Project charter approved
- [ ] Budget allocation confirmed
- [ ] Team members onboarded
- [ ] Hardware procurement completed
- [ ] Cloud service accounts established

#### Dependencies (Other milestones depending on Milestone 01):
- Milestone 02: Database Design and Core Schema Implementation
- Milestone 03: User Authentication and Authorization System
- Milestone 04: B2B Marketplace Foundation

### 2.5 Resource Allocation

| Resource Type | Allocation | Details |
|--------------|------------|---------|
| Human Resources | 3 FTE | Dev-Lead, Dev-1, Dev-2 |
| Compute Resources | 4 VMs | 1 dev server, 1 staging server, 2 database servers |
| Storage | 500 GB | Distributed across environments |
| Network Bandwidth | 1 Gbps | Shared across team |
| Budget | $5,000 | Infrastructure and tooling costs |

---

## 3. Pre-Implementation Activities (Day 1-2)

### 3.1 Day 1: Project Kickoff and Architecture Finalization

#### 3.1.1 Morning Session: Project Kickoff (9:00 AM - 12:00 PM)

**Attendees:** All team members, Project Manager, Stakeholders

**Agenda:**
1. Project vision and objectives presentation (30 min)
2. Technical architecture overview (45 min)
3. Team roles and responsibilities clarification (30 min)
4. Communication protocols establishment (15 min)
5. Q&A session (30 min)

**Deliverables:**
- Signed project charter acknowledgment
- Communication plan document
- Meeting minutes and action items

#### 3.1.2 Afternoon Session: Architecture Finalization (1:00 PM - 6:00 PM)

**Owner:** Dev-Lead (Senior Full Stack Developer)

**Tasks:**

##### 3.1.2.1 System Architecture Documentation

Create comprehensive architecture documentation including:

**High-Level Architecture Diagram:**

```
┌─────────────────────────────────────────────────────────────────────────┐
│                              CLIENT LAYER                                │
├─────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │   Web Portal │  │ Mobile App   │  │  Admin Panel │  │   API Client │ │
│  │   (React)    │  │ (React Native)│  │   (Odoo)     │  │   (Various)  │ │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘ │
└─────────┼─────────────────┼─────────────────┼─────────────────┼─────────┘
          │                 │                 │                 │
          └─────────────────┴────────┬────────┴─────────────────┘
                                     │ HTTPS/SSL
┌────────────────────────────────────┼────────────────────────────────────┐
│                              API GATEWAY                                 │
│                         Nginx Reverse Proxy                              │
│                    Rate Limiting | Load Balancing                        │
└────────────────────────────────────┼────────────────────────────────────┘
                                     │
          ┌──────────────────────────┼──────────────────────────┐
          │                          │                          │
┌─────────▼──────────┐    ┌──────────▼──────────┐    ┌──────────▼─────────┐
│   FRONTEND SERVER  │    │    BACKEND SERVER   │    │   ODOO ERP SERVER  │
│    (Node.js/Vite)  │    │     (Python/FastAPI)│    │    (Python/Odoo)   │
│  ┌──────────────┐  │    │  ┌──────────────┐   │    │  ┌──────────────┐  │
│  │  React App   │  │    │  │  REST API    │   │    │  │  Odoo 17     │  │
│  │  Build Output│  │    │  │  Controllers │   │    │  │  Custom      │  │
│  └──────────────┘  │    │  └──────────────┘   │    │  │  Modules     │  │
└────────────────────┘    └──────────┬──────────┘    │  └──────────────┘  │
                                     │               └──────────┬─────────┘
                                     │                          │
          ┌──────────────────────────┴──────────────────────────┘
          │
┌─────────▼──────────────────────────────────────────────────────────────┐
│                         DATA LAYER                                      │
├────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐     │
│  │   PostgreSQL 16  │  │     Redis 7      │  │   MinIO/S3       │     │
│  │  (Primary DB)    │  │   (Cache/Queue)  │  │  (File Storage)  │     │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘     │
└────────────────────────────────────────────────────────────────────────┘
```

**Technology Stack Specification:**

| Layer | Technology | Version | Purpose |
|-------|------------|---------|---------|
| Frontend | React | 18.x | User interface |
| Frontend Build | Vite | 5.x | Build tooling |
| Frontend UI | Tailwind CSS | 3.x | Styling |
| Backend API | FastAPI | 0.104+ | REST API |
| Backend Runtime | Python | 3.11+ | Server logic |
| ERP System | Odoo | 17.0 | Enterprise management |
| Database | PostgreSQL | 16.x | Primary data storage |
| Cache | Redis | 7.x | Caching and sessions |
| Web Server | Nginx | 1.24+ | Reverse proxy |
| Container | Docker | 24.x | Containerization |
| Orchestration | Docker Compose | 2.23+ | Multi-container |
| OS | Ubuntu | 24.04 LTS | Server operating system |

##### 3.1.2.2 Repository Structure Design

**Git Repository Organization:**

```
smart-dairy-portal/
├── .github/
│   ├── workflows/
│   │   ├── ci.yml              # Continuous Integration
│   │   ├── cd-staging.yml      # Staging Deployment
│   │   └── cd-production.yml   # Production Deployment
│   ├── ISSUE_TEMPLATE/
│   │   ├── bug_report.md
│   │   └── feature_request.md
│   └── PULL_REQUEST_TEMPLATE.md
├── .vscode/
│   ├── extensions.json         # Recommended extensions
│   ├── launch.json            # Debug configurations
│   └── settings.json          # Editor settings
├── docker/
│   ├── docker-compose.yml      # Main orchestration
│   ├── docker-compose.dev.yml  # Development override
│   ├── docker-compose.prod.yml # Production override
│   ├── nginx/
│   │   ├── Dockerfile
│   │   ├── nginx.conf         # Main configuration
│   │   ├── ssl/               # SSL certificates
│   │   └── conf.d/            # Site configurations
│   ├── postgres/
│   │   ├── Dockerfile
│   │   ├── init/              # Initialization scripts
│   │   └── config/            # PostgreSQL configuration
│   ├── redis/
│   │   ├── Dockerfile
│   │   └── redis.conf         # Redis configuration
│   └── odoo/
│       ├── Dockerfile
│       └── extra-addons/      # Custom Odoo modules
├── docs/
│   ├── architecture/          # Architecture documents
│   ├── api/                   # API documentation
│   ├── deployment/            # Deployment guides
│   └── development/           # Development guides
├── scripts/
│   ├── setup/                 # Setup scripts
│   ├── backup/                # Backup scripts
│   ├── deployment/            # Deployment scripts
│   └── maintenance/           # Maintenance scripts
├── frontend/                  # React Frontend Application
│   ├── public/
│   ├── src/
│   │   ├── components/        # Reusable components
│   │   ├── pages/             # Page components
│   │   ├── hooks/             # Custom React hooks
│   │   ├── services/          # API services
│   │   ├── store/             # State management
│   │   ├── utils/             # Utility functions
│   │   ├── types/             # TypeScript types
│   │   ├── styles/            # Global styles
│   │   └── assets/            # Static assets
│   ├── tests/
│   ├── .env.example
│   ├── package.json
│   ├── vite.config.ts
│   ├── tsconfig.json
│   └── Dockerfile
├── backend/                   # FastAPI Backend Application
│   ├── app/
│   │   ├── api/               # API routes
│   │   │   ├── v1/
│   │   │   └── deps.py
│   │   ├── core/              # Core configuration
│   │   ├── db/                # Database models
│   │   ├── models/            # Pydantic models
│   │   ├── services/          # Business logic
│   │   ├── utils/             # Utilities
│   │   └── main.py
│   ├── alembic/               # Database migrations
│   ├── tests/
│   ├── requirements/
│   │   ├── base.txt
│   │   ├── dev.txt
│   │   └── prod.txt
│   ├── .env.example
│   └── Dockerfile
├── odoo-addons/               # Custom Odoo Modules
│   ├── smart_dairy_base/
│   ├── smart_dairy_inventory/
│   ├── smart_dairy_sales/
│   └── smart_dairy_purchase/
├── infrastructure/            # Infrastructure as Code
│   ├── terraform/             # Terraform configurations
│   ├── ansible/               # Ansible playbooks
│   └── kubernetes/            # K8s manifests (future)
├── .dockerignore
├── .gitignore
├── .editorconfig
├── .pre-commit-config.yaml
├── Makefile                   # Common commands
├── README.md
└── LICENSE
```

#### 3.1.2.3 Branching Strategy Definition

**Git Flow Implementation:**

```
┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐
│  main   │────▶│ develop │────▶│ feature │     │ hotfix  │
│(production)│  │(integration)│  │ branches │    │branches │
└────┬────┘     └────┬────┘     └────┬────┘     └────┬────┘
     │               │               │               │
     │               │               │               │
     ▼               ▼               ▼               ▼
Production      Staging        Development      Emergency
  Deploy         Deploy         Work            Patches
```

**Branch Naming Conventions:**

| Branch Type | Naming Pattern | Example |
|-------------|----------------|---------|
| Production | `main` | `main` |
| Integration | `develop` | `develop` |
| Features | `feature/<ticket-id>-<description>` | `feature/SD-123-user-authentication` |
| Bug Fixes | `bugfix/<ticket-id>-<description>` | `bugfix/SD-456-login-error` |
| Hotfixes | `hotfix/<ticket-id>-<description>` | `hotfix/SD-789-security-patch` |
| Releases | `release/<version>` | `release/v1.2.0` |

**Commit Message Convention (Conventional Commits):**

```
<type>(<scope>): <subject>

<body>

<footer>
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`, `ci`, `build`

Examples:
- `feat(auth): implement JWT authentication`
- `fix(api): resolve CORS issues on login endpoint`
- `docs(readme): update installation instructions`
- `ci(pipeline): add automated testing stage`

#### 3.1.3 Dev-Lead Tasks (Day 1)

**Task 1.1: Repository Initialization**

```bash
#!/bin/bash
# Script: init-repository.sh
# Description: Initialize Smart Dairy Git repository with proper structure

set -e

echo "=========================================="
echo "Smart Dairy Repository Initialization"
echo "=========================================="

# Create main directory
mkdir -p smart-dairy-portal
cd smart-dairy-portal

# Initialize Git repository
git init
git checkout -b main

# Create directory structure
echo "Creating directory structure..."
mkdir -p .github/workflows
mkdir -p .github/ISSUE_TEMPLATE
mkdir -p .vscode
mkdir -p docker/{nginx,postgres,redis,odoo}/{conf.d,init,config,ssl,extra-addons}
mkdir -p docs/{architecture,api,deployment,development}
mkdir -p scripts/{setup,backup,deployment,maintenance}
mkdir -p frontend/{public,src/{components,pages,hooks,services,store,utils,types,styles,assets},tests}
mkdir -p backend/{app/{api/v1,core,db,models,services,utils},alembic,tests,requirements}
mkdir -p odoo-addons/{smart_dairy_base,smart_dairy_inventory,smart_dairy_sales,smart_dairy_purchase}
mkdir -p infrastructure/{terraform,ansible,kubernetes}

# Create initial files
echo "Creating initial configuration files..."

# .gitignore
cat > .gitignore << 'EOF'
# Dependencies
node_modules/
__pycache__/
*.pyc
*.pyo
*.pyd
.Python
*.so
*.egg
*.egg-info/
dist/
build/

# Environment variables
.env
.env.local
.env.*.local

# IDE
.idea/
.vscode/settings.json
.vscode/launch.json
*.swp
*.swo
*~

# OS
.DS_Store
Thumbs.db

# Logs
logs/
*.log
npm-debug.log*
yarn-debug.log*
yarn-error.log*

# Testing
coverage/
.nyc_output/

# Build outputs
frontend/dist/
backend/htmlcov/

# Database
*.db
*.sqlite
*.sqlite3

# SSL Certificates (local development)
*.pem
*.key
*.crt

# Docker volumes (if mounted locally)
volumes/
data/
EOF

# .dockerignore
cat > .dockerignore << 'EOF'
node_modules
__pycache__
*.pyc
.env
.git
.gitignore
README.md
Dockerfile
docker-compose.yml
.vscode
.idea
*.md
EOF

# .editorconfig
cat > .editorconfig << 'EOF'
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
trim_trailing_whitespace = true

[*.{js,jsx,ts,tsx,json,yml,yaml}]
indent_style = space
indent_size = 2

[*.{py,pyw}]
indent_style = space
indent_size = 4

[*.md]
trim_trailing_whitespace = false

[Makefile]
indent_style = tab
EOF

# README.md
cat > README.md << 'EOF'
# Smart Dairy Smart Portal + ERP System

## Overview
Smart Dairy is a comprehensive dairy farm management platform integrating B2B marketplace, IoT-enabled farm management, and ERP capabilities.

## Quick Start

### Prerequisites
- Docker 24.x+
- Docker Compose 2.23+
- Git

### Installation

1. Clone the repository:
```bash
git clone https://github.com/smartdairy/portal.git
cd smart-dairy-portal
```

2. Copy environment files:
```bash
cp frontend/.env.example frontend/.env
cp backend/.env.example backend/.env
```

3. Start development environment:
```bash
docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml up -d
```

4. Access the application:
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000
- Odoo ERP: http://localhost:8069

## Documentation
See the `docs/` directory for detailed documentation.

## Contributing
Please read [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines.

## License
[License details to be added]
EOF

# Makefile
cat > Makefile << 'EOF'
.PHONY: help build dev prod test clean logs shell

help:
	@echo "Smart Dairy Portal - Available Commands:"
	@echo "  make dev        - Start development environment"
	@echo "  make prod       - Start production environment"
	@echo "  make build      - Build all Docker images"
	@echo "  make test       - Run all tests"
	@echo "  make clean      - Clean up containers and volumes"
	@echo "  make logs       - View container logs"
	@echo "  make shell-be   - Access backend container shell"
	@echo "  make shell-db   - Access database container shell"

COMPOSE_BASE=docker/docker-compose.yml
COMPOSE_DEV=docker/docker-compose.dev.yml
COMPOSE_PROD=docker/docker-compose.prod.yml

dev:
	docker-compose -f $(COMPOSE_BASE) -f $(COMPOSE_DEV) up -d

prod:
	docker-compose -f $(COMPOSE_BASE) -f $(COMPOSE_PROD) up -d

build:
	docker-compose -f $(COMPOSE_BASE) build

test:
	cd backend && python -m pytest
	cd frontend && npm test

clean:
	docker-compose -f $(COMPOSE_BASE) down -v
	docker system prune -f

logs:
	docker-compose -f $(COMPOSE_BASE) logs -f

shell-be:
	docker-compose -f $(COMPOSE_BASE) exec backend bash

shell-db:
	docker-compose -f $(COMPOSE_BASE) exec postgres psql -U smartdairy
EOF

echo "Repository structure created successfully!"
echo "Next steps:"
echo "1. Copy .env.example files and configure environment variables"
echo "2. Run 'make dev' to start development environment"
echo "3. Access the application at http://localhost:3000"
EOF
chmod +x init-repository.sh
```

**Task 1.2: GitHub Repository Configuration**

```bash
#!/bin/bash
# Script: configure-github.sh
# Description: Configure GitHub repository settings

# Create GitHub Actions workflow directory
mkdir -p .github/workflows

# CI Pipeline
cat > .github/workflows/ci.yml << 'EOF'
name: Continuous Integration

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  backend-tests:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_USER: test
          POSTGRES_PASSWORD: test
          POSTGRES_DB: smartdairy_test
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
        ports:
          - 5432:5432
      redis:
        image: redis:7
        ports:
          - 6379:6379

    steps:
    - uses: actions/checkout@v4
    
    - name: Set up Python
      uses: actions/setup-python@v5
      with:
        python-version: '3.11'
    
    - name: Cache pip packages
      uses: actions/cache@v3
      with:
        path: ~/.cache/pip
        key: ${{ runner.os }}-pip-${{ hashFiles('**/requirements.txt') }}
    
    - name: Install dependencies
      run: |
        cd backend
        python -m pip install --upgrade pip
        pip install -r requirements/dev.txt
    
    - name: Run linting
      run: |
        cd backend
        flake8 app tests
        black --check app tests
        isort --check-only app tests
    
    - name: Run type checking
      run: |
        cd backend
        mypy app
    
    - name: Run tests
      env:
        DATABASE_URL: postgresql://test:test@localhost:5432/smartdairy_test
        REDIS_URL: redis://localhost:6379/0
        SECRET_KEY: test-secret-key
      run: |
        cd backend
        pytest --cov=app --cov-report=xml
    
    - name: Upload coverage
      uses: codecov/codecov-action@v3
      with:
        files: ./backend/coverage.xml

  frontend-tests:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v4
    
    - name: Set up Node.js
      uses: actions/setup-node@v4
      with:
        node-version: '20'
        cache: 'npm'
        cache-dependency-path: frontend/package-lock.json
    
    - name: Install dependencies
      run: |
        cd frontend
        npm ci
    
    - name: Run linting
      run: |
        cd frontend
        npm run lint
    
    - name: Run type checking
      run: |
        cd frontend
        npm run type-check
    
    - name: Run tests
      run: |
        cd frontend
        npm test -- --coverage
    
    - name: Build
      run: |
        cd frontend
        npm run build

  security-scan:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v4
    
    - name: Run Trivy vulnerability scanner
      uses: aquasecurity/trivy-action@master
      with:
        scan-type: 'fs'
        format: 'sarif'
        output: 'trivy-results.sarif'
    
    - name: Upload Trivy scan results
      uses: github/codeql-action/upload-sarif@v2
      with:
        sarif_file: 'trivy-results.sarif'
EOF

# Staging deployment workflow
cat > .github/workflows/cd-staging.yml << 'EOF'
name: Deploy to Staging

on:
  push:
    branches: [ develop ]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest
    environment: staging
    
    steps:
    - uses: actions/checkout@v4
    
    - name: Configure SSH
      uses: webfactory/ssh-agent@v0.8.0
      with:
        ssh-private-key: ${{ secrets.STAGING_SSH_KEY }}
    
    - name: Add host to known hosts
      run: |
        mkdir -p ~/.ssh
        ssh-keyscan -H ${{ secrets.STAGING_HOST }} >> ~/.ssh/known_hosts
    
    - name: Deploy to staging
      run: |
        ssh ${{ secrets.STAGING_USER }}@${{ secrets.STAGING_HOST }} << 'REMOTE_SCRIPT'
          cd /opt/smart-dairy
          git pull origin develop
          docker-compose -f docker/docker-compose.yml -f docker/docker-compose.prod.yml pull
          docker-compose -f docker/docker-compose.yml -f docker/docker-compose.prod.yml up -d
          docker-compose exec -T backend alembic upgrade head
          docker system prune -f
        REMOTE_SCRIPT
EOF

# Issue templates
cat > .github/ISSUE_TEMPLATE/bug_report.md << 'EOF'
---
name: Bug report
about: Create a report to help us improve
title: '[BUG] '
labels: bug
assignees: ''

---

**Describe the bug**
A clear and concise description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected behavior**
A clear and concise description of what you expected to happen.

**Screenshots**
If applicable, add screenshots to help explain your problem.

**Environment (please complete the following information):**
 - OS: [e.g. iOS]
 - Browser [e.g. chrome, safari]
 - Version [e.g. 22]

**Additional context**
Add any other context about the problem here.
EOF

cat > .github/ISSUE_TEMPLATE/feature_request.md << 'EOF'
---
name: Feature request
about: Suggest an idea for this project
title: '[FEATURE] '
labels: enhancement
assignees: ''

---

**Is your feature request related to a problem? Please describe.**
A clear and concise description of what the problem is.

**Describe the solution you'd like**
A clear and concise description of what you want to happen.

**Describe alternatives you've considered**
A clear and concise description of any alternative solutions or features you've considered.

**Additional context**
Add any other context or screenshots about the feature request here.
EOF

# Pull request template
cat > .github/PULL_REQUEST_TEMPLATE.md << 'EOF'
## Description
Please include a summary of the changes and which issue is fixed.

Fixes # (issue)

## Type of change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## How Has This Been Tested?
Please describe the tests that you ran to verify your changes.

- [ ] Test A
- [ ] Test B

## Checklist:
- [ ] My code follows the style guidelines of this project
- [ ] I have performed a self-review of my own code
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] I have made corresponding changes to the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests that prove my fix is effective or that my feature works
- [ ] New and existing unit tests pass locally with my changes
EOF
```

#### 3.1.4 Dev-1 Tasks (Day 1)

**Task 1.3: Local Development Environment Setup**

```bash
#!/bin/bash
# Script: setup-dev-1-backend.sh
# Description: Setup local development environment for Backend Developer (Dev-1)

set -e

echo "=========================================="
echo "Dev-1: Backend Developer Environment Setup"
echo "=========================================="

# 1. Install Python 3.11+
echo "Installing Python 3.11..."
sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:deadsnakes/ppa
sudo apt update
sudo apt install -y python3.11 python3.11-dev python3.11-venv python3-pip

# Set Python 3.11 as default
sudo update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.11 1

# 2. Install PostgreSQL 16
echo "Installing PostgreSQL 16..."
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sudo apt update
sudo apt install -y postgresql-16 postgresql-client-16 postgresql-contrib-16

# Start PostgreSQL
sudo systemctl enable postgresql
sudo systemctl start postgresql

# Create database user and database
sudo -u postgres psql << EOF
CREATE USER smartdairy WITH PASSWORD 'smartdairy_dev' CREATEDB;
CREATE DATABASE smartdairy_dev OWNER smartdairy;
CREATE DATABASE smartdairy_test OWNER smartdairy;
GRANT ALL PRIVILEGES ON DATABASE smartdairy_dev TO smartdairy;
GRANT ALL PRIVILEGES ON DATABASE smartdairy_test TO smartdairy;
\q
EOF

# Configure PostgreSQL for local development
sudo tee /etc/postgresql/16/main/conf.d/smartdairy.conf << EOF
# Smart Dairy Development Configuration
listen_addresses = 'localhost'
max_connections = 100
shared_buffers = 256MB
effective_cache_size = 768MB
maintenance_work_mem = 64MB
checkpoint_completion_target = 0.9
wal_buffers = 7864kB
default_statistics_target = 100
random_page_cost = 1.1
effective_io_concurrency = 200
work_mem = 1310kB
min_wal_size = 1GB
max_wal_size = 4GB
EOF

sudo systemctl restart postgresql

# 3. Install Python development tools
echo "Installing Python development tools..."
python3 -m pip install --upgrade pip
pip install --user virtualenv pipenv poetry

# 4. Install additional development tools
echo "Installing additional development tools..."
sudo apt install -y \
    build-essential \
    libpq-dev \
    libffi-dev \
    libssl-dev \
    libxml2-dev \
    libxslt1-dev \
    libldap2-dev \
    libsasl2-dev \
    libtiff5-dev \
    libjpeg8-dev \
    libopenjp2-7-dev \
    zlib1g-dev \
    libfreetype6-dev \
    liblcms2-dev \
    libwebp-dev \
    libharfbuzz-dev \
    libfribidi-dev \
    libxcb1-dev \
    pkg-config

# 5. Install Docker (if not already installed)
echo "Installing Docker..."
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker $USER
    rm get-docker.sh
fi

# 6. Install Docker Compose
echo "Installing Docker Compose..."
DOCKER_COMPOSE_VERSION="v2.23.3"
sudo curl -L "https://github.com/docker/compose/releases/download/${DOCKER_COMPOSE_VERSION}/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# 7. Install additional CLI tools
echo "Installing additional CLI tools..."
sudo apt install -y \
    jq \
    htop \
    tree \
    curl \
    wget \
    git \
    git-flow \
    vim \
    nano

# 8. Install pgAdmin (optional, for database management)
echo "Installing pgAdmin..."
curl -fsS https://www.pgadmin.org/static/packages_pgadmin_org.pub | sudo gpg --dearmor -o /usr/share/keyrings/packages-pgadmin-org.gpg
sudo sh -c 'echo "deb [signed-by=/usr/share/keyrings/packages-pgadmin-org.gpg] https://ftp.postgresql.org/pub/pgadmin/pgadmin4/apt/$(lsb_release -cs) pgadmin4 main" > /etc/apt/sources.list.d/pgadmin4.list'
sudo apt update
sudo apt install -y pgadmin4-desktop

echo "=========================================="
echo "Dev-1 Environment Setup Complete!"
echo "=========================================="
echo ""
echo "PostgreSQL Credentials:"
echo "  Host: localhost"
echo "  Port: 5432"
echo "  Database: smartdairy_dev"
echo "  Username: smartdairy"
echo "  Password: smartdairy_dev"
echo ""
echo "Next steps:"
echo "1. Log out and log back in for Docker group changes"
echo "2. Clone the repository"
echo "3. Run backend setup scripts"
EOF
chmod +x setup-dev-1-backend.sh
```

**Task 1.4: PostgreSQL Configuration Script**

```bash
#!/bin/bash
# Script: configure-postgres-dev.sh
# Description: Configure PostgreSQL for development environment

set -e

echo "Configuring PostgreSQL for Smart Dairy Development..."

# Backup original configuration
sudo cp /etc/postgresql/16/main/postgresql.conf /etc/postgresql/16/main/postgresql.conf.backup
sudo cp /etc/postgresql/16/main/pg_hba.conf /etc/postgresql/16/main/pg_hba.conf.backup

# Create optimized configuration for development
sudo tee /etc/postgresql/16/main/postgresql.conf > /dev/null << 'EOF'
#------------------------------------------------------------------------------
# PostgreSQL 16 Configuration - Smart Dairy Development
#------------------------------------------------------------------------------

#------------------------------------------------------------------------------
# FILE LOCATIONS
#------------------------------------------------------------------------------
data_directory = '/var/lib/postgresql/16/main'
hba_file = '/etc/postgresql/16/main/pg_hba.conf'
ident_file = '/etc/postgresql/16/main/pg_ident.conf'
external_pid_file = '/var/run/postgresql/16-main.pid'

#------------------------------------------------------------------------------
# CONNECTIONS AND AUTHENTICATION
#------------------------------------------------------------------------------
listen_addresses = 'localhost,127.0.0.1,::1'
port = 5432
max_connections = 100
superuser_reserved_connections = 3
unix_socket_directories = '/var/run/postgresql'

# SSL Configuration (for development, can be disabled)
ssl = off

#------------------------------------------------------------------------------
# RESOURCE USAGE (except WAL)
#------------------------------------------------------------------------------
shared_buffers = 256MB
huge_pages = try
temp_buffers = 8MB
max_prepared_transactions = 0
work_mem = 4MB
maintenance_work_mem = 64MB
dynamic_shared_memory_type = posix

# Disk and Memory Limits
effective_cache_size = 1GB
constraint_exclusion = partition
cursor_tuple_fraction = 0.1
from_collapse_limit = 8
join_collapse_limit = 8

#------------------------------------------------------------------------------
# WRITE-AHEAD LOG
#------------------------------------------------------------------------------
wal_level = replica
wal_compression = on
wal_log_hints = on
max_wal_size = 1GB
min_wal_size = 80MB
checkpoint_completion_target = 0.9
checkpoint_flush_after = 256kB
checkpoint_timeout = 5min

# Archiving (disabled for development)
archive_mode = off

#------------------------------------------------------------------------------
# REPLICATION
#------------------------------------------------------------------------------
max_wal_senders = 10
max_replication_slots = 10
wal_keep_size = 0

#------------------------------------------------------------------------------
# QUERY TUNING
#------------------------------------------------------------------------------
enable_bitmapscan = on
enable_hashagg = on
enable_hashjoin = on
enable_indexscan = on
enable_indexonlyscan = on
enable_material = on
enable_mergejoin = on
enable_nestloop = on
enable_parallel_append = on
enable_seqscan = on
enable_sort = on
enable_tidscan = on

# Planner Cost Constants
seq_page_cost = 1.0
random_page_cost = 1.1
cpu_tuple_cost = 0.01
cpu_index_tuple_cost = 0.005
cpu_operator_cost = 0.0025
parallel_tuple_cost = 0.1
parallel_setup_cost = 1000.0
effective_io_concurrency = 200

# Genetic Query Optimizer
geqo = on
geqo_threshold = 12
geqo_effort = 5
geqo_pool_size = 0
geqo_generations = 0
geqo_selection_bias = 2.0
geqo_seed = 0.0

# Other Planner Options
default_statistics_target = 100

#------------------------------------------------------------------------------
# REPORTING AND LOGGING
#------------------------------------------------------------------------------
log_destination = 'stderr'
logging_collector = on
log_directory = 'log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 10MB
log_min_messages = warning
log_min_error_statement = error
log_min_duration_statement = 1000
log_connections = on
log_disconnections = on
log_duration = off
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_timezone = 'UTC'

# Process Title
cluster_name = '16/main'
update_process_title = on

#------------------------------------------------------------------------------
# STATISTICS
#------------------------------------------------------------------------------
track_activities = on
track_counts = on
track_io_timing = on
track_functions = pl
track_activity_query_size = 1024
stats_temp_directory = 'pg_stat_tmp'

#------------------------------------------------------------------------------
# AUTOVACUUM
#------------------------------------------------------------------------------
autovacuum = on
log_autovacuum_min_duration = 250ms
autovacuum_max_workers = 3
autovacuum_naptime = 1min
autovacuum_vacuum_threshold = 50
autovacuum_analyze_threshold = 50
autovacuum_vacuum_scale_factor = 0.2
autovacuum_analyze_scale_factor = 0.1
autovacuum_freeze_min_age = 50000000
autovacuum_freeze_table_age = 150000000
autovacuum_multixact_freeze_min_age = 5000000
autovacuum_multixact_freeze_table_age = 150000000
autovacuum_vacuum_cost_delay = 2ms
autovacuum_vacuum_cost_limit = -1

#------------------------------------------------------------------------------
# CLIENT CONNECTION DEFAULTS
#------------------------------------------------------------------------------
# Locale and Formatting
datestyle = 'iso, mdy'
timezone = 'UTC'
lc_messages = 'en_US.UTF-8'
lc_monetary = 'en_US.UTF-8'
lc_numeric = 'en_US.UTF-8'
lc_time = 'en_US.UTF-8'
default_text_search_config = 'pg_catalog.english'

# Shared Library Preloading
shared_preload_libraries = 'pg_stat_statements'

# pg_stat_statements configuration
pg_stat_statements.max = 10000
pg_stat_statements.track = all

#------------------------------------------------------------------------------
# LOCK MANAGEMENT
#------------------------------------------------------------------------------
deadlock_timeout = 1s
max_locks_per_transaction = 64
max_pred_locks_per_transaction = 64
EOF

# Configure pg_hba.conf for local development
sudo tee /etc/postgresql/16/main/pg_hba.conf > /dev/null << 'EOF'
# PostgreSQL Client Authentication Configuration File
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# Local connections
local   all             postgres                                peer
local   all             all                                     md5

# IPv4 local connections:
host    all             all             127.0.0.1/32            md5
host    all             all             10.0.0.0/8              md5
host    all             all             172.16.0.0/12           md5
host    all             all             192.168.0.0/16          md5

# IPv6 local connections:
host    all             all             ::1/128                 md5

# Allow replication connections
local   replication     all                                     peer
host    replication     all             127.0.0.1/32            md5
host    replication     all             ::1/128                 md5
EOF

# Restart PostgreSQL
sudo systemctl restart postgresql

echo "PostgreSQL configuration complete!"
echo "Configuration files backed up to .backup"
EOF
chmod +x configure-postgres-dev.sh
```

#### 3.1.5 Dev-2 Tasks (Day 1)

**Task 1.5: VS Code Configuration and Extensions Setup**

```bash
#!/bin/bash
# Script: setup-dev-2-frontend.sh
# Description: Setup frontend development environment for Dev-2

set -e

echo "=========================================="
echo "Dev-2: Frontend Developer Environment Setup"
echo "=========================================="

# 1. Install Node.js 20.x LTS
echo "Installing Node.js 20.x..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node --version
npm --version

# 2. Install Yarn (alternative package manager)
echo "Installing Yarn..."
npm install -g yarn
yarn --version

# 3. Install pnpm (fast, disk space efficient package manager)
echo "Installing pnpm..."
npm install -g pnpm
pnpm --version

# 4. Install Vite globally
echo "Installing Vite..."
npm install -g vite

# 5. Install TypeScript globally
echo "Installing TypeScript..."
npm install -g typescript

# 6. Install Git and Git Flow
echo "Installing Git tools..."
sudo apt install -y git git-flow git-core

# Configure Git
git config --global init.defaultBranch main
git config --global core.editor "code --wait"
git config --global core.autocrlf input

# 7. Install VS Code
echo "Installing VS Code..."
wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > packages.microsoft.gpg
sudo install -D -o root -g root -m 644 packages.microsoft.gpg /etc/apt/keyrings/packages.microsoft.gpg
sudo sh -c 'echo "deb [arch=amd64,arm64,armhf signed-by=/etc/apt/keyrings/packages.microsoft.gpg] https://packages.microsoft.com/repos/code stable main" > /etc/apt/sources.list.d/vscode.list'
rm -f packages.microsoft.gpg
sudo apt update
sudo apt install -y code

# 8. Install Chrome (for development and testing)
echo "Installing Google Chrome..."
wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
sudo sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list'
sudo apt update
sudo apt install -y google-chrome-stable

# 9. Install additional development tools
echo "Installing additional tools..."
sudo apt install -y \
    curl \
    wget \
    jq \
    htop \
    tree \
    vim \
    nano

# 10. Install Docker
echo "Installing Docker..."
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker $USER
    rm get-docker.sh
fi

echo "=========================================="
echo "Dev-2 Environment Setup Complete!"
echo "=========================================="
echo ""
echo "Installed Versions:"
echo "  Node.js: $(node --version)"
echo "  npm: $(npm --version)"
echo "  Yarn: $(yarn --version)"
echo "  pnpm: $(pnpm --version)"
echo "  TypeScript: $(tsc --version)"
echo ""
echo "Next steps:"
echo "1. Log out and log back in for Docker group changes"
echo "2. Launch VS Code and install extensions"
echo "3. Clone the repository"
EOF
chmod +x setup-dev-2-frontend.sh
```

**VS Code Settings Configuration:**

```json
// .vscode/settings.json
{
  // Editor Configuration
  "editor.formatOnSave": true,
  "editor.formatOnPaste": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": "explicit",
    "source.organizeImports": "explicit"
  },
  "editor.tabSize": 2,
  "editor.insertSpaces": true,
  "editor.detectIndentation": false,
  "editor.rulers": [80, 120],
  "editor.wordWrap": "on",
  "editor.minimap.enabled": true,
  "editor.bracketPairColorization.enabled": true,
  "editor.guides.bracketPairs": true,
  "editor.suggestSelection": "first",
  "editor.quickSuggestions": {
    "other": true,
    "comments": false,
    "strings": false
  },

  // Files Configuration
  "files.trimTrailingWhitespace": true,
  "files.insertFinalNewline": true,
  "files.trimFinalNewlines": true,
  "files.exclude": {
    "**/node_modules": true,
    "**/dist": true,
    "**/.git": true,
    "**/.DS_Store": true,
    "**/coverage": true,
    "**/__pycache__": true,
    "**/*.pyc": true
  },
  "files.watcherExclude": {
    "**/node_modules/**": true,
    "**/dist/**": true,
    "**/.git/objects/**": true,
    "**/.git/subtree-cache/**": true
  },
  "files.associations": {
    "*.css": "tailwindcss"
  },

  // Search Configuration
  "search.exclude": {
    "**/node_modules": true,
    "**/dist": true,
    "**/coverage": true,
    "**/.git": true
  },

  // TypeScript Configuration
  "typescript.updateImportsOnFileMove.enabled": "always",
  "typescript.preferences.importModuleSpecifier": "relative",
  "typescript.suggest.autoImports": true,
  "typescript.tsdk": "frontend/node_modules/typescript/lib",

  // JavaScript Configuration
  "javascript.updateImportsOnFileMove.enabled": "always",
  "javascript.preferences.importModuleSpecifier": "relative",

  // ESLint Configuration
  "eslint.workingDirectories": ["./frontend"],
  "eslint.validate": [
    "javascript",
    "javascriptreact",
    "typescript",
    "typescriptreact"
  ],
  "eslint.format.enable": true,

  // Prettier Configuration
  "prettier.singleQuote": true,
  "prettier.trailingComma": "es5",
  "prettier.tabWidth": 2,
  "prettier.semi": true,
  "prettier.printWidth": 100,
  "prettier.arrowParens": "avoid",
  "prettier.endOfLine": "lf",

  // Tailwind CSS Configuration
  "tailwindCSS.includeLanguages": {
    "html": "html",
    "javascript": "javascript",
    "css": "css"
  },
  "tailwindCSS.emmetCompletions": true,
  "tailwindCSS.experimental.classRegex": [
    ["cva\\(([^)]*)\\)", "[\"'`]([^\"'`]*).*?["'`]"],
    ["cx\\(([^)]*)\\)", "(?!'|\"`)([^\"'`]*)(?!'|\"`)"]
  ],

  // Git Configuration
  "git.enableSmartCommit": true,
  "git.confirmSync": false,
  "git.autofetch": true,
  "git.openRepositoryInParentFolders": "always",

  // Terminal Configuration
  "terminal.integrated.defaultProfile.linux": "bash",
  "terminal.integrated.profiles.linux": {
    "bash": {
      "path": "/bin/bash",
      "icon": "terminal-bash"
    }
  },

  // Extensions Configuration
  "extensions.ignoreRecommendations": false,

  // Workbench Configuration
  "workbench.iconTheme": "material-icon-theme",
  "workbench.colorTheme": "One Dark Pro",
  "workbench.tree.indent": 20,
  "workbench.editor.enablePreview": false,

  // Debug Configuration
  "debug.console.fontSize": 13,

  // Specific Language Settings
  "[python]": {
    "editor.defaultFormatter": "ms-python.black-formatter",
    "editor.tabSize": 4,
    "editor.formatOnSave": true
  },
  "[json]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "[jsonc]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "[yaml]": {
    "editor.defaultFormatter": "redhat.vscode-yaml"
  },
  "[markdown]": {
    "editor.defaultFormatter": "DavidAnson.vscode-markdownlint",
    "editor.wordWrap": "on"
  },
  "[dockerfile]": {
    "editor.defaultFormatter": "ms-azuretools.vscode-docker"
  },

  // Emojisense
  "emojisense.unicodeCompletionsEnabled": true,
  "emojisense.markupCompletionsEnabled": true,

  // Import Cost
  "importCost.smallPackageColor": "#4CAF50",
  "importCost.mediumPackageColor": "#FF9800",
  "importCost.largePackageColor": "#F44336",

  // TODO Highlight
  "todohighlight.isCaseSensitive": false,
  "todohighlight.keywords": [
    {
      "text": "TODO:",
      "color": "#fff",
      "backgroundColor": "#ffbd2a",
      "overviewRulerColor": "rgba(255, 189, 42, 0.8)"
    },
    {
      "text": "FIXME:",
      "color": "#fff",
      "backgroundColor": "#f06292",
      "overviewRulerColor": "rgba(240, 98, 146, 0.8)"
    },
    {
      "text": "HACK:",
      "color": "#fff",
      "backgroundColor": "#7e57c2",
      "overviewRulerColor": "rgba(126, 87, 194, 0.8)"
    },
    {
      "text": "NOTE:",
      "color": "#fff",
      "backgroundColor": "#26c6da",
      "overviewRulerColor": "rgba(38, 198, 218, 0.8)"
    }
  ],

  // Version Lens
  "versionlens.suggestions.showOnStartup": true,

  // Error Lens
  "errorLens.enabled": true,
  "errorLens.highlightStyle": "box",
  "errorLens.fontStyleItalic": true,

  // Peacock (for workspace identification)
  "peacock.affectActivityBar": true,
  "peacock.affectStatusBar": true,
  "peacock.affectTitleBar": true,

  // Thunder Client (API testing)
  "thunder-client.saveToWorkspace": true,

  // REST Client
  "rest-client.fontSize": 13,
  "rest-client.fontFamily": "JetBrains Mono",

  // Settings for Odoo/Python Development
  "python.defaultInterpreterPath": "./backend/.venv/bin/python",
  "python.analysis.typeCheckingMode": "basic",
  "python.analysis.autoImportCompletions": true,
  "python.formatting.provider": "black",
  "python.linting.enabled": true,
  "python.linting.pylintEnabled": true,
  "python.linting.flake8Enabled": true,
  "python.linting.mypyEnabled": true,

  // Odoo XML Support
  "xml.format.splitAttributes": true,
  "xml.format.closingBracketNewLine": true
}
```

**VS Code Extensions Configuration:**

```json
// .vscode/extensions.json
{
  "recommendations": [
    // === Core Development ===
    "ms-vscode.vscode-typescript-next",
    "esbenp.prettier-vscode",
    "dbaeumer.vscode-eslint",
    "bradlc.vscode-tailwindcss",
    "formulahendry.auto-rename-tag",
    "christian-kohler.path-intellisense",

    // === React/Frontend ===
    "dsznajder.es7-react-js-snippets",
    "ms-vscode.vscode-json",
    "vincaslt.highlight-matching-tag",
    "VisualStudioExptTeam.vscodeintellicode",

    // === Python/Odoo ===
    "ms-python.python",
    "ms-python.black-formatter",
    "ms-python.isort",
    "ms-python.mypy-type-checker",
    "ms-python.flake8",
    "redhat.vscode-xml",
    "trinm1709.odoo-snippets",

    // === Git & Version Control ===
    "eamodio.gitlens",
    "mhutchie.git-graph",
    "github.vscode-pull-request-github",
    "github.vscode-github-actions",

    // === Docker & DevOps ===
    "ms-azuretools.vscode-docker",
    "ms-vscode-remote.remote-containers",
    "redhat.vscode-yaml",
    "hashicorp.terraform",

    // === Database ===
    "ckolkman.vscode-postgres",
    "cweijan.vscode-postgresql-client2",

    // === API Development ===
    "rangav.vscode-thunder-client",
    "humao.rest-client",
    "42crunch.vscode-openapi",

    // === Productivity ===
    "pkief.material-icon-theme",
    "zhuangtongfa.material-theme",
    "usernamehw.errorlens",
    "wayou.vscode-todo-highlight",
    "wix.vscode-import-cost",
    "pflannery.vscode-versionlens",
    "sburg.vscode-javascript-booster",

    // === Testing ===
    "orta.vscode-jest",
    "ms-vscode.test-adapter-converter",
    "hbenl.vscode-test-explorer",

    // === Debugging ===
    "firefox-devtools.vscode-firefox-debug",
    "ms-edgedevtools.vscode-edge-devtools",

    // === Markdown & Documentation ===
    "yzhang.markdown-all-in-one",
    "davidanson.vscode-markdownlint",
    "shd101wyy.markdown-preview-enhanced",

    // === Editor Enhancements ===
    "aaron-bond.better-comments",
    "steoates.autoimport",
    "chrmarti.regex",
    "naumovs.color-highlight",
    "mikestead.dotenv",
    "streetsidesoftware.code-spell-checker",
    "johnpapa.vscode-peacock",

    // === Terminal ===
    "fabianlauer.vs-code-xml-format",
    "matt-meyers.vscode-dbml",

    // === Live Share & Collaboration ===
    "ms-vsliveshare.vsliveshare",
    "ms-vsliveshare.vsliveshare-audio",
    "ms-vsliveshare.vsliveshare-pack"
  ],
  "unwantedRecommendations": [
    "hookyqr.beautify",
    "ms-vscode.vscode-typescript-tslint-plugin"
  ]
}
```



### 3.2 Day 2: Environment Validation and Access Provisioning

#### 3.2.1 Morning Session: Development Environment Validation

**Dev-Lead Tasks:**
- Validate repository structure across all developer machines
- Verify Git configuration consistency
- Test initial Docker Compose setup
- Review and approve architecture documentation

**Environment Validation Script:**

```bash
#!/bin/bash
# Script: validate-environment.sh
# Description: Validate development environment setup

set -e

echo "=========================================="
echo "Development Environment Validation"
echo "=========================================="

ERRORS=0
WARNINGS=0

# Function to check command exists
check_command() {
    if command -v "$1" &> /dev/null; then
        echo "✓ $1 is installed: $($1 --version 2>/dev/null | head -n1)"
        return 0
    else
        echo "✗ $1 is NOT installed"
        ((ERRORS++))
        return 1
    fi
}

# Function to check version
check_version() {
    local cmd=$1
    local required=$2
    local current=$($cmd --version 2>/dev/null | grep -oE '[0-9]+\.[0-9]+' | head -n1)
    
    if [ "$(printf '%s\n' "$required" "$current" | sort -V | head -n1)" = "$required" ]; then
        echo "✓ $cmd version $current meets requirement (>= $required)"
    else
        echo "✗ $cmd version $current is below required $required"
        ((ERRORS++))
    fi
}

# Function to check port availability
check_port() {
    local port=$1
    local service=$2
    
    if netstat -tuln 2>/dev/null | grep -q ":$port " || ss -tuln 2>/dev/null | grep -q ":$port "; then
        echo "✓ Port $port is available for $service"
    else
        echo "⚠ Port $port is not listening - $service may not be running"
        ((WARNINGS++))
    fi
}

# Function to check directory
check_directory() {
    if [ -d "$1" ]; then
        echo "✓ Directory exists: $1"
    else
        echo "✗ Directory missing: $1"
        ((ERRORS++))
    fi
}

# Function to check file
check_file() {
    if [ -f "$1" ]; then
        echo "✓ File exists: $1"
    else
        echo "✗ File missing: $1"
        ((ERRORS++))
    fi
}

echo ""
echo "=== System Requirements ==="
echo "------------------------------"

# Check operating system
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    echo "✓ Operating System: Linux"
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        echo "  Distribution: $NAME $VERSION"
    fi
else
    echo "⚠ Operating System: $OSTYPE (Linux recommended)"
    ((WARNINGS++))
fi

# Check memory
MEMORY_GB=$(free -g 2>/dev/null | awk '/^Mem:/{print $2}' || echo "0")
if [ "$MEMORY_GB" -ge 8 ]; then
    echo "✓ Memory: ${MEMORY_GB}GB (Recommended: 8GB+)"
else
    echo "⚠ Memory: ${MEMORY_GB}GB (Recommended: 8GB+)"
    ((WARNINGS++))
fi

# Check disk space
DISK_GB=$(df -BG . 2>/dev/null | awk 'NR==2 {print $4}' | sed 's/G//' || echo "0")
if [ "$DISK_GB" -ge 50 ]; then
    echo "✓ Available Disk: ${DISK_GB}GB (Recommended: 50GB+)"
else
    echo "⚠ Available Disk: ${DISK_GB}GB (Recommended: 50GB+)"
    ((WARNINGS++))
fi

echo ""
echo "=== Required Tools ==="
echo "------------------------------"

# Check Git
check_command "git"
check_version "git" "2.30"

# Check Docker
check_command "docker"
check_version "docker" "24.0"

# Check Docker Compose
check_command "docker-compose"
check_version "docker-compose" "2.20"

# Check Node.js
check_command "node"
check_version "node" "18.0"

# Check npm
check_command "npm"
check_version "npm" "9.0"

# Check Python (for backend developers)
check_command "python3"
check_version "python3" "3.11"

# Check pip
check_command "pip3"

# Check PostgreSQL client
check_command "psql"
check_version "psql" "16.0"

echo ""
echo "=== Optional Tools ==="
echo "------------------------------"

# Optional tools - don't fail if missing
OPTIONAL_TOOLS=("yarn" "pnpm" "code" "pgadmin4")
for tool in "${OPTIONAL_TOOLS[@]}"; do
    if command -v "$tool" &> /dev/null; then
        echo "✓ $tool is installed"
    else
        echo "⚠ $tool is not installed (optional)"
    fi
done

echo ""
echo "=== Required Ports ==="
echo "------------------------------"

# Check required ports
check_port 3000 "Frontend Development Server"
check_port 8000 "Backend API Server"
check_port 5432 "PostgreSQL Database"
check_port 6379 "Redis Cache"
check_port 8069 "Odoo ERP"
check_port 8080 "Nginx Proxy"

echo ""
echo "=== Project Structure ==="
echo "------------------------------"

# Check project directory structure
REQUIRED_DIRS=(
    ".github/workflows"
    ".vscode"
    "docker"
    "docs"
    "scripts"
    "frontend/src"
    "backend/app"
    "odoo-addons"
)

for dir in "${REQUIRED_DIRS[@]}"; do
    check_directory "$dir"
done

# Check required files
REQUIRED_FILES=(
    "README.md"
    ".gitignore"
    ".dockerignore"
    ".editorconfig"
    "Makefile"
    "docker/docker-compose.yml"
    "frontend/package.json"
    "backend/requirements/base.txt"
)

for file in "${REQUIRED_FILES[@]}"; do
    check_file "$file"
done

echo ""
echo "=== Git Configuration ==="
echo "------------------------------"

# Check Git configuration
if [ -d ".git" ]; then
    echo "✓ Git repository initialized"
    
    # Check remote
    REMOTE=$(git remote -v 2>/dev/null | head -n1)
    if [ -n "$REMOTE" ]; then
        echo "✓ Git remote configured: $REMOTE"
    else
        echo "⚠ No Git remote configured"
        ((WARNINGS++))
    fi
    
    # Check branches
    MAIN_BRANCH=$(git branch -l main master 2>/dev/null | head -n1 | sed 's/^[* ]*//')
    if [ -n "$MAIN_BRANCH" ]; then
        echo "✓ Main branch exists: $MAIN_BRANCH"
    else
        echo "⚠ No main or master branch found"
        ((WARNINGS++))
    fi
    
    # Check hooks
    if [ -f ".git/hooks/pre-commit" ]; then
        echo "✓ Pre-commit hook installed"
    else
        echo "⚠ Pre-commit hook not installed"
        ((WARNINGS++))
    fi
else
    echo "✗ Not a Git repository"
    ((ERRORS++))
fi

echo ""
echo "=== Docker Environment ==="
echo "------------------------------"

# Check Docker daemon
if docker info &> /dev/null; then
    echo "✓ Docker daemon is running"
    
    # Check Docker Compose
    if docker-compose --version &> /dev/null; then
        echo "✓ Docker Compose is available"
    fi
    
    # Check for required images
    echo "Checking for base images..."
    REQUIRED_IMAGES=("postgres:16" "redis:7" "node:20-alpine" "python:3.11-slim")
    for image in "${REQUIRED_IMAGES[@]}"; do
        if docker image inspect "$image" &> /dev/null; then
            echo "  ✓ Image available: $image"
        else
            echo "  ⚠ Image not found: $image (will be pulled on first run)"
        fi
    done
else
    echo "✗ Docker daemon is not running"
    ((ERRORS++))
fi

echo ""
echo "=== Environment Files ==="
echo "------------------------------"

# Check environment files
if [ -f "frontend/.env" ]; then
    echo "✓ Frontend environment file exists"
else
    echo "⚠ Frontend environment file missing (copy from .env.example)"
    ((WARNINGS++))
fi

if [ -f "backend/.env" ]; then
    echo "✓ Backend environment file exists"
else
    echo "⚠ Backend environment file missing (copy from .env.example)"
    ((WARNINGS++))
fi

echo ""
echo "=========================================="
echo "Validation Summary"
echo "=========================================="
echo "Errors:   $ERRORS"
echo "Warnings: $WARNINGS"
echo ""

if [ $ERRORS -eq 0 ]; then
    echo "✓ Environment validation PASSED"
    if [ $WARNINGS -gt 0 ]; then
        echo "⚠ Please address $WARNINGS warning(s) for optimal setup"
    fi
    exit 0
else
    echo "✗ Environment validation FAILED"
    echo "Please fix $ERRORS error(s) before proceeding"
    exit 1
fi
EOF
chmod +x validate-environment.sh
```

#### 3.2.2 Afternoon Session: Access Configuration and Permissions

**Team Access Configuration Script:**

```bash
#!/bin/bash
# Script: configure-team-access.sh
# Description: Configure team access and permissions

set -e

echo "=========================================="
echo "Team Access Configuration"
echo "=========================================="

# Team member configuration
TEAM_MEMBERS=(
    "dev-lead:Dev-Lead:senior"
    "dev-1:Dev-1:backend"
    "dev-2:Dev-2:frontend"
)

# Create SSH keys directory
mkdir -p ~/.ssh/team-keys
chmod 700 ~/.ssh/team-keys

# Generate SSH keys for team members (if not exists)
for member in "${TEAM_MEMBERS[@]}"; do
    IFS=':' read -r username name role <<< "$member"
    keyfile="~/.ssh/team-keys/${username}_ed25519"
    
    if [ ! -f "$keyfile" ]; then
        echo "Generating SSH key for $name ($username)..."
        ssh-keygen -t ed25519 -C "$username@smartdairy.local" -f "$keyfile" -N ""
        echo "Key generated: $keyfile"
    else
        echo "SSH key exists for $username"
    fi
done

# Display public keys
echo ""
echo "Public Keys (add to GitHub/Server authorized_keys):"
echo "---------------------------------------------------"
for member in "${TEAM_MEMBERS[@]}"; do
    IFS=':' read -r username name role <<< "$member"
    pubfile="~/.ssh/team-keys/${username}_ed25519.pub"
    echo ""
    echo "$name ($username - $role):"
    cat "$pubfile"
done

# Create Git configuration for team
cat > .gitconfig.team << 'GITCONFIG'
[core]
    autocrlf = input
    safecrlf = true
    editor = code --wait
[color]
    ui = auto
[color "branch"]
    current = yellow reverse
    local = yellow
    remote = green
[color "diff"]
    meta = yellow bold
    frag = magenta bold
    old = red bold
    new = green bold
[color "status"]
    added = yellow
    changed = green
    untracked = cyan
[alias]
    # Shortcuts
    st = status
    co = checkout
    br = branch
    ci = commit
    cp = cherry-pick
    rb = rebase
    lg = log --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit --date=relative
    lga = log --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit --date=relative --all
    # Feature workflow
    feature-start = "!f() { git checkout -b feature/SD-$1-${2// /-}; }; f"
    feature-finish = "!f() { git checkout develop && git merge --no-ff feature/SD-$1; }; f"
    # Safe operations
    undo = reset --soft HEAD^
    unstage = reset HEAD --
    discard = checkout --
[push]
    default = current
[branch]
    autosetuprebase = always
[pull]
    rebase = true
[merge]
    ff = false
    tool = vscode
[mergetool "vscode"]
    cmd = code --wait $MERGED
[diff]
    tool = vscode
[difftool "vscode"]
    cmd = code --wait --diff $LOCAL $REMOTE
[init]
    defaultBranch = main
[commit]
    template = .gitmessage
[fetch]
    prune = true
GITCONFIG

echo ""
echo "Git team configuration created: .gitconfig.team"
echo "Apply with: git config --local include.path ../.gitconfig.team"

# Create commit message template
cat > .gitmessage << 'GITMESSAGE'
# Type: (feat|fix|docs|style|refactor|test|chore|ci|build)
# Scope: (auth|api|ui|db|ci|deps)
# Subject: Start with verb, max 50 chars
# Body: Explain WHAT and WHY (not HOW), wrap at 72 chars
# Footer: Reference issues, breaking changes

# Example:
# feat(auth): implement JWT authentication
#
# Add JWT token generation and validation for API endpoints.
# Implements stateless authentication with 24h token expiry.
#
# Closes #123
# BREAKING CHANGE: API now requires Authorization header
GITMESSAGE

echo ""
echo "Commit message template created: .gitmessage"

# Create team development guidelines
cat > docs/development/TEAM_GUIDELINES.md << 'GUIDELINES'
# Smart Dairy Development Team Guidelines

## Code Review Requirements

### Pull Request Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Code is commented where necessary
- [ ] Documentation updated
- [ ] Tests added/updated and passing
- [ ] No console errors or warnings
- [ ] No security vulnerabilities introduced

### Review Process
1. Create feature branch from `develop`
2. Make changes and commit with descriptive messages
3. Push branch and create Pull Request to `develop`
4. Request review from at least 1 team member
5. Address review comments
6. Merge only after approval and CI passes

## Communication Protocol

### Daily Standups (9:00 AM)
- What did you work on yesterday?
- What are you working on today?
- Any blockers?

### Slack Channels
- `#smart-dairy-dev`: Development discussions
- `#smart-dairy-ci`: CI/CD notifications
- `#smart-dairy-alerts`: Production alerts

### Escalation Path
1. Try to resolve independently (30 min limit)
2. Ask in team channel
3. Direct message to Dev-Lead
4. Schedule pair programming session

## Branching Strategy

```
main ─────●─────────────────●─────────────────●
           \               / \               /
develop ────●───●───●────●───●───●───●────●
                 \     /         \     /
feature/SD-123 ───●───●           ●───●
```

## Code Quality Standards

### Frontend (React/TypeScript)
- Use TypeScript for all new code
- Follow ESLint and Prettier configurations
- Component files: PascalCase (e.g., `UserProfile.tsx`)
- Utility files: camelCase (e.g., `formatDate.ts`)
- Maximum file length: 300 lines
- Maximum function length: 50 lines

### Backend (Python)
- Follow PEP 8 style guide
- Use type hints where applicable
- Maximum line length: 100 characters
- Use docstrings for all public functions

### Database
- Use migrations for all schema changes
- Never modify existing migrations
- Include both `upgrade` and `downgrade` scripts
- Test migrations on copy of production data

## Security Guidelines

### Never Commit
- Passwords or API keys
- Private keys or certificates
- `.env` files with real values
- Database dumps with production data

### Always Use
- Environment variables for configuration
- HTTPS for all external communication
- Parameterized queries (prevent SQL injection)
- Input validation on all API endpoints

## Performance Guidelines

### Frontend
- Lazy load routes and heavy components
- Optimize images before commit
- Use React.memo for expensive renders
- Implement proper error boundaries

### Backend
- Use database indexes for frequent queries
- Implement caching with Redis
- Use pagination for list endpoints
- Profile slow queries regularly

## Testing Requirements

### Unit Tests
- Minimum 80% code coverage
- Test all API endpoints
- Test critical business logic
- Mock external dependencies

### Integration Tests
- Test database interactions
- Test authentication flows
- Test critical user journeys

### E2E Tests
- Cover main user flows
- Run on staging before release
- Use realistic test data

## Documentation

### Code Documentation
- JSDoc for TypeScript functions
- Docstrings for Python functions
- README for each major directory
- Inline comments for complex logic

### API Documentation
- Document all endpoints
- Include request/response examples
- Document error responses
- Keep in sync with implementation

## Environment Management

### Development
- Use Docker Compose for consistency
- Never connect to production databases
- Use seed data for testing
- Keep local environment clean

### Staging
- Mirror production configuration
- Use realistic data (anonymized)
- Test deployments before production
- Monitor performance metrics

### Production
- Never debug in production
- Use proper logging
- Monitor error rates
- Have rollback plan ready
GUIDELINES

echo ""
echo "Team guidelines created: docs/development/TEAM_GUIDELINES.md"

echo ""
echo "=========================================="
echo "Team Access Configuration Complete!"
echo "=========================================="
EOF
chmod +x configure-team-access.sh
```

---

## 4. Development Environment Setup (Day 3-5)

### 4.1 Day 3: Core Infrastructure Components

#### 4.1.1 Docker Compose Configuration

**Main Docker Compose File:**

```yaml
# docker/docker-compose.yml
version: "3.8"

services:
  # PostgreSQL Database
  postgres:
    build:
      context: ../.
      dockerfile: docker/postgres/Dockerfile
    container_name: smart-dairy-postgres
    environment:
      POSTGRES_USER: ${POSTGRES_USER:-smartdairy}
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-smartdairy_secret}
      POSTGRES_DB: ${POSTGRES_DB:-smartdairy}
      PGDATA: /var/lib/postgresql/data/pgdata
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./postgres/init:/docker-entrypoint-initdb.d
      - ./postgres/config/postgresql.conf:/etc/postgresql/postgresql.conf
    ports:
      - "5432:5432"
    networks:
      - smartdairy_network
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${POSTGRES_USER:-smartdairy} -d ${POSTGRES_DB:-smartdairy}"]
      interval: 10s
      timeout: 5s
      retries: 5
      start_period: 10s
    restart: unless-stopped
    command: postgres -c config_file=/etc/postgresql/postgresql.conf

  # Redis Cache
  redis:
    build:
      context: ../.
      dockerfile: docker/redis/Dockerfile
    container_name: smart-dairy-redis
    environment:
      REDIS_PASSWORD: ${REDIS_PASSWORD:-redis_secret}
    volumes:
      - redis_data:/data
      - ./redis/redis.conf:/usr/local/etc/redis/redis.conf
    ports:
      - "6379:6379"
    networks:
      - smartdairy_network
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 3s
      retries: 5
    restart: unless-stopped
    command: redis-server /usr/local/etc/redis/redis.conf --requirepass ${REDIS_PASSWORD:-redis_secret}

  # MinIO Object Storage (S3-compatible)
  minio:
    image: minio/minio:latest
    container_name: smart-dairy-minio
    environment:
      MINIO_ROOT_USER: ${MINIO_ROOT_USER:-minioadmin}
      MINIO_ROOT_PASSWORD: ${MINIO_ROOT_PASSWORD:-minioadmin}
    volumes:
      - minio_data:/data
    ports:
      - "9000:9000"
      - "9001:9001"
    networks:
      - smartdairy_network
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:9000/minio/health/live"]
      interval: 30s
      timeout: 20s
      retries: 3
    restart: unless-stopped
    command: server /data --console-address ":9001"

  # Backend API (FastAPI)
  backend:
    build:
      context: ../backend
      dockerfile: ../docker/backend/Dockerfile
    container_name: smart-dairy-backend
    environment:
      - ENVIRONMENT=${ENVIRONMENT:-development}
      - DEBUG=${DEBUG:-true}
      - DATABASE_URL=postgresql://${POSTGRES_USER:-smartdairy}:${POSTGRES_PASSWORD:-smartdairy_secret}@postgres:5432/${POSTGRES_DB:-smartdairy}
      - REDIS_URL=redis://:${REDIS_PASSWORD:-redis_secret}@redis:6379/0
      - SECRET_KEY=${SECRET_KEY:-your-secret-key-change-in-production}
      - MINIO_ENDPOINT=minio:9000
      - MINIO_ACCESS_KEY=${MINIO_ROOT_USER:-minioadmin}
      - MINIO_SECRET_KEY=${MINIO_ROOT_PASSWORD:-minioadmin}
      - ODOO_URL=http://odoo:8069
      - ODOO_DB=${ODOO_DB:-smartdairy}
      - ODOO_USER=${ODOO_USER:-admin}
      - ODOO_PASSWORD=${ODOO_PASSWORD:-admin}
    volumes:
      - ../backend:/app
      - backend_logs:/app/logs
    ports:
      - "8000:8000"
    networks:
      - smartdairy_network
    depends_on:
      postgres:
        condition: service_healthy
      redis:
        condition: service_healthy
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8000/health"]
      interval: 30s
      timeout: 10s
      retries: 3
    restart: unless-stopped
    command: uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload

  # Frontend (React/Vite)
  frontend:
    build:
      context: ../frontend
      dockerfile: ../docker/frontend/Dockerfile
    container_name: smart-dairy-frontend
    environment:
      - VITE_API_URL=http://localhost:8000
      - VITE_WS_URL=ws://localhost:8000
      - NODE_ENV=development
    volumes:
      - ../frontend:/app
      - /app/node_modules
    ports:
      - "3000:3000"
    networks:
      - smartdairy_network
    depends_on:
      - backend
    restart: unless-stopped
    command: npm run dev -- --host 0.0.0.0

  # Odoo ERP
  odoo:
    build:
      context: ../.
      dockerfile: docker/odoo/Dockerfile
    container_name: smart-dairy-odoo
    environment:
      - HOST=postgres
      - USER=${POSTGRES_USER:-smartdairy}
      - PASSWORD=${POSTGRES_PASSWORD:-smartdairy_secret}
      - PORT=5432
    volumes:
      - odoo_data:/var/lib/odoo
      - ../odoo-addons:/mnt/extra-addons
      - ./odoo/config:/etc/odoo
    ports:
      - "8069:8069"
      - "8071:8071"
    networks:
      - smartdairy_network
    depends_on:
      postgres:
        condition: service_healthy
    restart: unless-stopped

  # Nginx Reverse Proxy
  nginx:
    build:
      context: ../.
      dockerfile: docker/nginx/Dockerfile
    container_name: smart-dairy-nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./nginx/conf.d:/etc/nginx/conf.d
      - ./nginx/ssl:/etc/nginx/ssl
      - frontend_build:/usr/share/nginx/html
    networks:
      - smartdairy_network
    depends_on:
      - backend
      - frontend
      - odoo
    restart: unless-stopped

volumes:
  postgres_data:
    driver: local
  redis_data:
    driver: local
  minio_data:
    driver: local
  odoo_data:
    driver: local
  backend_logs:
    driver: local
  frontend_build:
    driver: local

networks:
  smartdairy_network:
    driver: bridge
    ipam:
      config:
        - subnet: 172.20.0.0/16
```

**Development Override:**

```yaml
# docker/docker-compose.dev.yml
version: "3.8"

services:
  postgres:
    environment:
      POSTGRES_DB: smartdairy_dev
    ports:
      - "5432:5432"
    volumes:
      - postgres_dev_data:/var/lib/postgresql/data

  redis:
    ports:
      - "6379:6379"
    command: redis-server /usr/local/etc/redis/redis.conf --requirepass redis_dev

  backend:
    environment:
      - ENVIRONMENT=development
      - DEBUG=true
      - DATABASE_URL=postgresql://smartdairy:smartdairy_secret@postgres:5432/smartdairy_dev
      - REDIS_URL=redis://:redis_dev@redis:6379/0
      - LOG_LEVEL=debug
    volumes:
      - ../backend:/app
      - ../docs:/app/docs
    command: uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload --reload-dir /app
    # Enable debugging
    stdin_open: true
    tty: true

  frontend:
    environment:
      - VITE_API_URL=http://localhost:8000
      - VITE_WS_URL=ws://localhost:8000
      - CHOKIDAR_USEPOLLING=true
    volumes:
      - ../frontend:/app
      - /app/node_modules
    # Enable hot reload
    stdin_open: true
    tty: true

  # MailHog for email testing in development
  mailhog:
    image: mailhog/mailhog:latest
    container_name: smart-dairy-mailhog
    ports:
      - "1025:1025"  # SMTP server
      - "8025:8025"  # Web UI
    networks:
      - smartdairy_network

  # Adminer for database management
  adminer:
    image: adminer:latest
    container_name: smart-dairy-adminer
    ports:
      - "8080:8080"
    networks:
      - smartdairy_network
    depends_on:
      - postgres

  # Redis Commander for Redis management
  redis-commander:
    image: rediscommander/redis-commander:latest
    container_name: smart-dairy-redis-commander
    environment:
      - REDIS_HOSTS=local:redis:6379:0:redis_dev
    ports:
      - "8081:8081"
    networks:
      - smartdairy_network
    depends_on:
      - redis

volumes:
  postgres_dev_data:
    driver: local
```

**Production Override:**

```yaml
# docker/docker-compose.prod.yml
version: "3.8"

services:
  postgres:
    environment:
      POSTGRES_DB: smartdairy_prod
      POSTGRES_USER: ${POSTGRES_USER}
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
    volumes:
      - postgres_prod_data:/var/lib/postgresql/data
    deploy:
      resources:
        limits:
          cpus: '2.0'
          memory: 2G
    restart: always

  redis:
    environment:
      REDIS_PASSWORD: ${REDIS_PASSWORD}
    volumes:
      - redis_prod_data:/data
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
    restart: always

  backend:
    environment:
      - ENVIRONMENT=production
      - DEBUG=false
      - DATABASE_URL=${DATABASE_URL}
      - REDIS_URL=${REDIS_URL}
      - SECRET_KEY=${SECRET_KEY}
      - LOG_LEVEL=info
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '1.0'
          memory: 1G
    restart: always
    command: uvicorn app.main:app --host 0.0.0.0 --port 8000 --workers 4
    # Health check
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8000/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

  frontend:
    environment:
      - NODE_ENV=production
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
    restart: always
    command: serve -s dist -l 3000

  odoo:
    environment:
      - USER=${ODOO_DB_USER}
      - PASSWORD=${ODOO_DB_PASSWORD}
    volumes:
      - odoo_prod_data:/var/lib/odoo
      - odoo_prod_addons:/mnt/extra-addons
    deploy:
      resources:
        limits:
          cpus: '1.0'
          memory: 1G
    restart: always

  nginx:
    volumes:
      - ./nginx/ssl:/etc/nginx/ssl:ro
      - ./nginx/nginx.prod.conf:/etc/nginx/nginx.conf:ro
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 256M
    restart: always

  # Production monitoring
  prometheus:
    image: prom/prometheus:latest
    container_name: smart-dairy-prometheus
    volumes:
      - ./monitoring/prometheus.yml:/etc/prometheus/prometheus.yml
      - prometheus_data:/prometheus
    ports:
      - "9090:9090"
    networks:
      - smartdairy_network
    restart: always

  grafana:
    image: grafana/grafana:latest
    container_name: smart-dairy-grafana
    environment:
      - GF_SECURITY_ADMIN_PASSWORD=${GRAFANA_ADMIN_PASSWORD}
    volumes:
      - grafana_data:/var/lib/grafana
      - ./monitoring/grafana/dashboards:/etc/grafana/provisioning/dashboards
      - ./monitoring/grafana/datasources:/etc/grafana/provisioning/datasources
    ports:
      - "3001:3000"
    networks:
      - smartdairy_network
    restart: always

volumes:
  postgres_prod_data:
    driver: local
  redis_prod_data:
    driver: local
  odoo_prod_data:
    driver: local
  odoo_prod_addons:
    driver: local
  prometheus_data:
    driver: local
  grafana_data:
    driver: local
```

#### 4.1.2 Individual Dockerfiles

**PostgreSQL Dockerfile:**

```dockerfile
# docker/postgres/Dockerfile
FROM postgres:16-alpine

# Install additional extensions
RUN apk add --no-cache \
    postgresql16-contrib \
    pg_cron

# Copy custom configuration
COPY docker/postgres/config/postgresql.conf /etc/postgresql/postgresql.conf
COPY docker/postgres/config/pg_hba.conf /etc/postgresql/pg_hba.conf

# Copy initialization scripts
COPY docker/postgres/init/ /docker-entrypoint-initdb.d/

# Set proper permissions
RUN chown -R postgres:postgres /docker-entrypoint-initdb.d/ \
    && chmod +x /docker-entrypoint-initdb.d/*.sh

# Health check
HEALTHCHECK --interval=10s --timeout=5s --start-period=10s --retries=5 \
    CMD pg_isready -U ${POSTGRES_USER} -d ${POSTGRES_DB}

EXPOSE 5432
```

**Redis Dockerfile:**

```dockerfile
# docker/redis/Dockerfile
FROM redis:7-alpine

# Install redis extensions
RUN apk add --no-cache \
    curl

# Copy custom configuration
COPY docker/redis/redis.conf /usr/local/etc/redis/redis.conf

# Create data directory
RUN mkdir -p /data && chown redis:redis /data

# Health check
HEALTHCHECK --interval=10s --timeout=3s --start-period=5s --retries=5 \
    CMD redis-cli ping || exit 1

EXPOSE 6379

CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
```

**Backend Dockerfile:**

```dockerfile
# docker/backend/Dockerfile
FROM python:3.11-slim as builder

# Set environment variables
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1
ENV PIP_NO_CACHE_DIR=1
ENV PIP_DISABLE_PIP_VERSION_CHECK=1

# Install build dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Create virtual environment
RUN python -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Install Python dependencies
COPY backend/requirements/base.txt /tmp/requirements.txt
RUN pip install --upgrade pip && \
    pip install -r /tmp/requirements.txt

# Production stage
FROM python:3.11-slim as production

# Set environment variables
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1
ENV PATH="/opt/venv/bin:$PATH"

# Install runtime dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq5 \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Copy virtual environment from builder
COPY --from=builder /opt/venv /opt/venv

# Create app user
RUN useradd -m -u 1000 appuser && mkdir -p /app && chown appuser:appuser /app
WORKDIR /app

# Copy application code
COPY --chown=appuser:appuser backend/ /app/

# Switch to non-root user
USER appuser

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

EXPOSE 8000

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
```

**Frontend Dockerfile:**

```dockerfile
# docker/frontend/Dockerfile
FROM node:20-alpine as builder

# Set working directory
WORKDIR /app

# Copy package files
COPY frontend/package*.json ./

# Install dependencies
RUN npm ci --only=production

# Copy source code
COPY frontend/ .

# Build application
RUN npm run build

# Production stage
FROM node:20-alpine as production

# Install serve
RUN npm install -g serve

# Set working directory
WORKDIR /app

# Copy built files
COPY --from=builder /app/dist ./dist

# Create non-root user
RUN addgroup -g 1001 -S nodejs && \
    adduser -S nextjs -u 1001

# Change ownership
RUN chown -R nextjs:nodejs /app/dist
USER nextjs

EXPOSE 3000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://localhost:3000/ || exit 1

CMD ["serve", "-s", "dist", "-l", "3000"]
```

**Odoo Dockerfile:**

```dockerfile
# docker/odoo/Dockerfile
FROM odoo:17.0

USER root

# Install additional dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    python3-pip \
    python3-dev \
    build-essential \
    libxml2-dev \
    libxslt1-dev \
    libevent-dev \
    libsasl2-dev \
    libldap2-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Python packages
RUN pip3 install --no-cache-dir \
    pandas \
    openpyxl \
    xlrd \
    xlwt \
    numpy

# Copy custom configuration
COPY docker/odoo/config/odoo.conf /etc/odoo/odoo.conf

# Copy custom addons
COPY odoo-addons/ /mnt/extra-addons/

# Set permissions
RUN chown -R odoo:odoo /mnt/extra-addons/

USER odoo

EXPOSE 8069 8071 8072
```

**Nginx Dockerfile:**

```dockerfile
# docker/nginx/Dockerfile
FROM nginx:1.24-alpine

# Install additional modules
RUN apk add --no-cache \
    curl \
    ca-certificates

# Remove default configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy custom configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/conf.d/ /etc/nginx/conf.d/

# Create directories for SSL and logs
RUN mkdir -p /etc/nginx/ssl /var/log/nginx

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
```

