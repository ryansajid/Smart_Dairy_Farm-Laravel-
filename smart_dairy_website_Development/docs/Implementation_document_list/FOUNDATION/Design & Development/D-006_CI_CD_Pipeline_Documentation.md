# SMART DAIRY LTD.
## CI/CD PIPELINE DOCUMENTATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-006 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Engineer |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [CI/CD Architecture](#2-cicd-architecture)
3. [Git Workflow](#3-git-workflow)
4. [Pipeline Stages](#4-pipeline-stages)
5. [Testing Strategy](#5-testing-strategy)
6. [Security Scanning](#6-security-scanning)
7. [Deployment Strategies](#7-deployment-strategies)
8. [Environment Management](#8-environment-management)
9. [Monitoring & Notifications](#9-monitoring--notifications)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the Continuous Integration and Continuous Deployment (CI/CD) pipeline for the Smart Dairy Smart Web Portal System and Integrated ERP. It establishes automated build, test, and deployment processes to ensure code quality and reliable releases.

### 1.2 CI/CD Principles

```
┌─────────────────────────────────────────────────────────────────┐
│                    CI/CD PRINCIPLES                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  🔧 AUTOMATE     │  Automate everything that can be automated    │
│  🧪 TEST EARLY   │  Shift testing left, catch issues early       │
│  🚀 DEPLOY FAST  │  Enable frequent, low-risk deployments        │
│  🔒 SECURE       │  Security checks integrated into pipeline     │
│  📊 MONITOR      │  Visibility into every stage of the pipeline  │
│  🔄 FEEDBACK     │  Fast feedback loops for developers           │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Tool Stack

| Category | Tool | Purpose |
|----------|------|---------|
| **Source Control** | GitHub/GitLab | Version control, code review |
| **CI/CD Platform** | GitHub Actions/GitLab CI | Pipeline orchestration |
| **Container Registry** | AWS ECR/Docker Hub | Docker image storage |
| **Artifact Repository** | Nexus/Artifactory | Build artifacts |
| **Deployment** | ArgoCD/Helm | Kubernetes deployment |
| **Secrets Management** | HashiCorp Vault | Secret storage |
| **Monitoring** | Prometheus + Grafana | Pipeline metrics |

---

## 2. CI/CD ARCHITECTURE

### 2.1 Pipeline Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    CI/CD PIPELINE FLOW                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌─────────┐        │
│  │  CODE   │──▶│  BUILD  │──▶│   TEST  │──▶│  DEPLOY │        │
│  │ COMMIT  │   │   &     │   │   &     │   │   TO    │        │
│  │         │   │ PACKAGE │   │  SCAN   │   │  ENV    │        │
│  └─────────┘   └─────────┘   └─────────┘   └─────────┘        │
│       │            │             │             │                │
│       ▼            ▼             ▼             ▼                │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌─────────┐        │
│  │ Branch  │   │ Docker  │   │ Unit/   │   │ Staging │        │
│  │ Rules   │   │ Images  │   │ Int/E2E │   │Production│        │
│  │ Linting │   │ Helm    │   │ Security│   │ Rollback│        │
│  │         │   │ Charts  │   │         │   │         │        │
│  └─────────┘   └─────────┘   └─────────┘   └─────────┘        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Pipeline Triggers

| Trigger | Branch | Action |
|---------|--------|--------|
| **Push** | feature/* | Run tests, no deploy |
| **Push** | develop | Build, test, deploy to Dev |
| **PR Created** | any | Full test suite, security scan |
| **Merge** | main | Build, test, deploy to Staging |
| **Tag** | v*.*.* | Production deployment |
| **Manual** | any | Deploy to specific environment |
| **Schedule** | main | Nightly security scan |

---

## 3. GIT WORKFLOW

### 3.1 Branching Strategy (GitFlow)

```
┌─────────────────────────────────────────────────────────────────┐
│                    GIT BRANCHING STRATEGY                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  main (Production)                                              │
│    │                                                             │
│    ├── hotfix/fix-critical-bug ─────────────┐                   │
│    │                                        ▼                   │
│    └───▶ v1.0.1 ────────────────────────────▶ (Deploy)          │
│                                                                  │
│  develop (Integration)                                          │
│    │                                                             │
│    ├── feature/user-authentication ─────────▶                   │
│    │                                         │                   │
│    ├── feature/payment-integration ─────────▶ (Merge)           │
│    │                                         │                   │
│    └───▶ release/v1.1.0 ────────────────────▶                   │
│               │                                                 │
│               └── bugfix/login-error ───────▶ (Tag v1.1.0)      │
│                                                                  │
│  Branch Naming:                                                  │
│  - feature/SD-123-short-description                             │
│  - bugfix/SD-456-fix-description                                │
│  - hotfix/critical-issue-description                            │
│  - release/v{major}.{minor}.{patch}                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Commit Message Convention

```
<type>(<scope>): <subject>

<body>

<footer>

Types:
- feat: New feature
- fix: Bug fix
- docs: Documentation
- style: Code style (formatting)
- refactor: Code refactoring
- test: Tests
- chore: Build/CI changes

Example:
feat(auth): add bKash payment gateway integration

- Implement bKash checkout API
- Add webhook handling for payment confirmation
- Store transaction IDs for reconciliation

Closes: SD-123
```

---

## 4. PIPELINE STAGES

### 4.1 GitHub Actions Workflow

```yaml
# .github/workflows/main.yml
name: Smart Dairy CI/CD Pipeline

on:
  push:
    branches: [main, develop]
    tags: ['v*']
  pull_request:
    branches: [main, develop]
  schedule:
    - cron: '0 0 * * *'  # Daily at midnight

env:
  REGISTRY: ghcr.io
  IMAGE_NAME: smart-dairy

jobs:
  # Stage 1: Code Quality
  lint-and-validate:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          pip install flake8 black isort bandit

      - name: Lint with flake8
        run: |
          flake8 . --count --select=E9,F63,F7,F82 --show-source --statistics
          flake8 . --count --exit-zero --max-complexity=10 --max-line-length=120 --statistics

      - name: Check formatting with black
        run: |
          black --check --diff .

      - name: Check imports with isort
        run: |
          isort --check-only --diff .

      - name: Security scan with bandit
        run: |
          bandit -r . -f json -o bandit-report.json || true

      - name: Upload lint results
        uses: actions/upload-artifact@v3
        with:
          name: lint-results
          path: bandit-report.json

  # Stage 2: Build
  build:
    runs-on: ubuntu-latest
    needs: lint-and-validate
    strategy:
      matrix:
        service: [odoo, fastapi, celery]
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v3

      - name: Login to Container Registry
        uses: docker/login-action@v3
        with:
          registry: ${{ env.REGISTRY }}
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}

      - name: Extract metadata
        id: meta
        uses: docker/metadata-action@v5
        with:
          images: ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}/${{ matrix.service }}
          tags: |
            type=ref,event=branch
            type=ref,event=pr
            type=semver,pattern={{version}}
            type=semver,pattern={{major}}.{{minor}}
            type=sha,prefix=,suffix=,format=short

      - name: Build and push Docker image
        uses: docker/build-push-action@v5
        with:
          context: .
          file: ./docker/${{ matrix.service }}/Dockerfile
          push: ${{ github.event_name != 'pull_request' }}
          tags: ${{ steps.meta.outputs.tags }}
          labels: ${{ steps.meta.outputs.labels }}
          cache-from: type=gha
          cache-to: type=gha,mode=max

  # Stage 3: Unit Tests
  unit-tests:
    runs-on: ubuntu-latest
    needs: build
    services:
      postgres:
        image: postgres:16-alpine
        env:
          POSTGRES_PASSWORD: postgres
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
        ports:
          - 5432:5432
      redis:
        image: redis:7-alpine
        ports:
          - 6379:6379
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          pip install -r requirements.txt
          pip install pytest pytest-cov pytest-asyncio

      - name: Run unit tests
        run: |
          pytest tests/unit --cov=. --cov-report=xml --cov-report=html
        env:
          DATABASE_URL: postgresql://postgres:postgres@localhost:5432/test
          REDIS_URL: redis://localhost:6379/0

      - name: Upload coverage to Codecov
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage.xml
          fail_ci_if_error: true

      - name: Upload test results
        uses: actions/upload-artifact@v3
        with:
          name: unit-test-results
          path: htmlcov/

  # Stage 4: Integration Tests
  integration-tests:
    runs-on: ubuntu-latest
    needs: build
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Run integration tests with docker-compose
        run: |
          docker-compose -f docker-compose.test.yml up --abort-on-container-exit --exit-code-from test-runner
        env:
          COMPOSE_FILE: docker-compose.test.yml

      - name: Cleanup
        if: always()
        run: |
          docker-compose -f docker-compose.test.yml down -v

  # Stage 5: Security Scanning
  security-scan:
    runs-on: ubuntu-latest
    needs: build
    if: github.event_name != 'pull_request'
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Run Trivy vulnerability scanner
        uses: aquasecurity/trivy-action@master
        with:
          image-ref: ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}/odoo:${{ github.sha }}
          format: 'sarif'
          output: 'trivy-results.sarif'

      - name: Upload Trivy scan results
        uses: github/codeql-action/upload-sarif@v2
        with:
          sarif_file: 'trivy-results.sarif'

      - name: Run Snyk security scan
        uses: snyk/actions/docker@master
        env:
          SNYK_TOKEN: ${{ secrets.SNYK_TOKEN }}
        with:
          image: ${{ env.REGISTRY }}/${{ env.IMAGE_NAME }}/odoo:${{ github.sha }}
          args: --severity-threshold=high

  # Stage 6: Deploy to Staging
  deploy-staging:
    runs-on: ubuntu-latest
    needs: [unit-tests, integration-tests]
    if: github.ref == 'refs/heads/main'
    environment:
      name: staging
      url: https://staging.smartdairybd.com
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup kubectl
        uses: azure/setup-kubectl@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v4
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ap-south-1

      - name: Update kubeconfig
        run: |
          aws eks update-kubeconfig --name smart-dairy-staging

      - name: Deploy to Staging
        run: |
          helm upgrade --install smart-dairy ./helm-chart \
            --namespace staging \
            --set image.tag=${{ github.sha }} \
            --set environment=staging \
            --wait --timeout 10m

      - name: Run smoke tests
        run: |
          kubectl run smoke-test --rm -i --restart=Never \
            --image=curlimages/curl \
            -- curl -f http://smart-dairy-staging.smart-dairy.svc.cluster.local/health

  # Stage 7: Deploy to Production
  deploy-production:
    runs-on: ubuntu-latest
    needs: deploy-staging
    if: startsWith(github.ref, 'refs/tags/v')
    environment:
      name: production
      url: https://smartdairybd.com
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup kubectl
        uses: azure/setup-kubectl@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v4
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ap-south-1

      - name: Deploy to Production
        run: |
          helm upgrade --install smart-dairy ./helm-chart \
            --namespace production \
            --set image.tag=${{ github.ref_name }} \
            --set environment=production \
            --wait --timeout 10m

      - name: Notify deployment
        uses: slackapi/slack-github-action@v1.24.0
        with:
          payload: |
            {
              "text": "🚀 Smart Dairy ${{ github.ref_name }} deployed to production"
            }
        env:
          SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK_URL }}
```

### 4.2 Pipeline Performance Targets

| Metric | Target | Measurement |
|--------|--------|-------------|
| **Build Time** | < 10 minutes | From commit to artifact |
| **Test Execution** | < 15 minutes | All test stages |
| **Security Scan** | < 10 minutes | Trivy + Snyk |
| **Deploy to Staging** | < 5 minutes | After tests pass |
| **Deploy to Production** | < 10 minutes | Blue-green deployment |
| **Total Pipeline Time** | < 30 minutes | Feature branch to staging |

---

## 5. TESTING STRATEGY

### 5.1 Test Pyramid

```
┌─────────────────────────────────────────────────────────────────┐
│                    TESTING PYRAMID                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│                         ╱╲                                      │
│                        ╱  ╲     E2E Tests (10%)                 │
│                       ╱────╲    - User flows                    │
│                      ╱        - Critical paths                  │
│                     ╱          - Browser automation             │
│                    ╱                                            │
│                   ╱────────╲   Integration Tests (30%)          │
│                  ╱            - API contracts                   │
│                 ╱             - Database interactions           │
│                ╱              - Service integration             │
│               ╱                                                 │
│              ╱────────────────╲ Unit Tests (60%)                │
│             ╱                   - Functions                     │
│            ╱                    - Classes                       │
│           ╱                     - Business logic                │
│                                                                  │
│  Target Coverage: 80% overall, 100% for critical paths         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 Test Configuration

```yaml
# pytest.ini
[pytest]
minversion = 7.0
testpaths = tests
python_files = test_*.py
python_classes = Test*
python_functions = test_*
addopts = 
    -v
    --tb=short
    --strict-markers
    --cov=smart_dairy
    --cov-report=term-missing
    --cov-report=html
    --cov-report=xml
markers =
    unit: Unit tests
    integration: Integration tests
    e2e: End-to-end tests
    slow: Slow running tests
    smoke: Smoke tests
```

---

## 6. SECURITY SCANNING

### 6.1 Security Pipeline

```
┌─────────────────────────────────────────────────────────────────┐
│                    SECURITY SCANNING PIPELINE                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. SAST (Static Application Security Testing)                  │
│     └── Tools: Bandit, Semgrep, SonarQube                       │
│     └── Scans: Python code, JavaScript code                     │
│     └── Fail on: High/Critical vulnerabilities                  │
│                                                                  │
│  2. SCA (Software Composition Analysis)                         │
│     └── Tools: Snyk, OWASP Dependency Check                     │
│     └── Scans: requirements.txt, package.json                   │
│     └── Fail on: Known CVEs in dependencies                     │
│                                                                  │
│  3. Container Scanning                                          │
│     └── Tools: Trivy, Clair, Anchore                            │
│     └── Scans: Docker images                                    │
│     └── Fail on: OS and package vulnerabilities                 │
│                                                                  │
│  4. Secret Detection                                            │
│     └── Tools: GitLeaks, TruffleHog                            │
│     └── Scans: Commits for secrets/tokens                       │
│     └── Fail on: Any detected secrets                           │
│                                                                  │
│  5. DAST (Dynamic Application Security Testing)                 │
│     └── Tools: OWASP ZAP                                        │
│     └── Scans: Running application                              │
│     └── Schedule: Weekly on staging                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 6.2 Security Gates

| Gate | Criteria | Action on Failure |
|------|----------|-------------------|
| **Pre-commit** | No secrets in code | Block commit |
| **Build** | No critical vulnerabilities | Block build |
| **Staging Deploy** | All high issues resolved | Require exception |
| **Production Deploy** | All medium+ issues resolved | Require security sign-off |

---

## 7. DEPLOYMENT STRATEGIES

### 7.1 Deployment Patterns

```
┌─────────────────────────────────────────────────────────────────┐
│                    DEPLOYMENT STRATEGIES                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DEVELOPMENT ENVIRONMENT                                         │
│  ├── Strategy: Direct deployment                                │
│  ├── Trigger: Every push to develop branch                      │
│  └── Rollback: Previous container image                         │
│                                                                  │
│  STAGING ENVIRONMENT                                             │
│  ├── Strategy: Rolling update                                   │
│  ├── Trigger: Merge to main branch                              │
│  ├── Testing: Automated smoke tests                             │
│  └── Rollback: Automatic on failure                             │
│                                                                  │
│  PRODUCTION ENVIRONMENT                                          │
│  ├── Strategy: Blue-Green deployment                            │
│  ├── Trigger: Git tag (semantic versioning)                     │
│  ├── Approval: Required from Release Manager                    │
│  ├── Testing: Canary deployment (5% → 25% → 100%)               │
│  └── Rollback: Instant switch to previous version               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 7.2 Blue-Green Deployment

```yaml
# Helm values for blue-green deployment
strategy:
  type: BlueGreen
  activeService: smart-dairy-active
  previewService: smart-dairy-preview
  autoPromotionEnabled: false
  autoPromotionSeconds: 300
  scaleDownDelaySeconds: 600

# Deployment steps:
# 1. Deploy new version (green) alongside current (blue)
# 2. Run smoke tests on green
# 3. Switch traffic (promote green)
# 4. Monitor for 5 minutes
# 5. Scale down blue (keep for 10 minutes as backup)
```

---

## 8. ENVIRONMENT MANAGEMENT

### 8.1 Environment Matrix

| Environment | Purpose | Data | Access | Auto-Deploy |
|-------------|---------|------|--------|-------------|
| **Local** | Development | Mock/Local | Developers | Manual |
| **Dev** | Integration | Synthetic | Team | Yes (develop) |
| **QA** | Testing | Anonymized prod | QA Team | Manual |
| **Staging** | Pre-production | Prod-like | All | Yes (main) |
| **Production** | Live | Production | Limited | Manual (tag) |

### 8.2 Configuration Management

```
config/
├── base/
│   ├── odoo.conf
│   ├── nginx.conf
│   └── k8s/
│       ├── deployment.yaml
│       ├── service.yaml
│       └── ingress.yaml
├── environments/
│   ├── dev/
│   │   └── values.yaml
│   ├── staging/
│   │   └── values.yaml
│   └── production/
│       └── values.yaml
└── secrets/
    ├── dev-secrets.enc.yaml
    ├── staging-secrets.enc.yaml
    └── production-secrets.enc.yaml (SOPS encrypted)
```

---

## 9. MONITORING & NOTIFICATIONS

### 9.1 Pipeline Metrics

| Metric | Target | Alert Threshold |
|--------|--------|-----------------|
| **Pipeline Success Rate** | > 95% | < 90% |
| **Mean Time to Recovery** | < 30 min | > 1 hour |
| **Deployment Frequency** | Daily | < 2/week |
| **Lead Time for Changes** | < 1 day | > 3 days |
| **Change Failure Rate** | < 5% | > 10% |

### 9.2 Notification Channels

| Event | Channel | Recipients |
|-------|---------|------------|
| **Build Failure** | Slack + Email | Dev Team |
| **Security Alert** | Slack + PagerDuty | Security + Dev |
| **Production Deploy** | Slack | All Teams |
| **Pipeline Blocked** | Email | Tech Lead |
| **Recovery Needed** | PagerDuty | On-call Engineer |

---

## 10. TROUBLESHOOTING

### 10.1 Common Pipeline Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| **Build timeout** | Slow dependencies | Enable caching, use registry cache |
| **Test flakiness** | Race conditions | Fix test isolation, add retries |
| **Deploy failure** | Resource constraints | Check quotas, scale nodes |
| **Secret not found** | Missing in vault | Add secret, restart pipeline |
| **Image pull error** | Wrong tag/credentials | Check registry, credentials |

### 10.2 Debug Commands

```bash
# Check workflow status
gh run list --workflow=main.yml

# View logs
gh run view <run-id> --log

# Rerun failed jobs
gh run rerun <run-id> --failed

# Check Kubernetes deployment
kubectl describe deployment smart-dairy -n production

# View pod logs
kubectl logs -l app=smart-dairy -n production --tail=100

# Rollback deployment
kubectl rollout undo deployment/smart-dairy -n production
```

---

## 11. APPENDICES

### Appendix A: Pipeline Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `GITHUB_SHA` | Commit SHA | `abc123...` |
| `GITHUB_REF_NAME` | Branch or tag name | `main`, `v1.0.0` |
| `GITHUB_RUN_ID` | Unique run identifier | `123456789` |
| `REGISTRY` | Container registry | `ghcr.io` |
| `IMAGE_NAME` | Image repository name | `smart-dairy` |

### Appendix B: Emergency Procedures

```bash
# Rollback production manually
kubectl rollout undo deployment/smart-dairy -n production

# Scale down to zero (emergency stop)
kubectl scale deployment smart-dairy --replicas=0 -n production

# Check recent events
kubectl get events -n production --sort-by='.lastTimestamp' | tail -20

# Get pod resource usage
kubectl top pods -n production
```

---

**END OF CI/CD PIPELINE DOCUMENTATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | DevOps Engineer | Initial version |
