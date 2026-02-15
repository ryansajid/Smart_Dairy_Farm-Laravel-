# SMART DAIRY LTD.
## DOCKER CONFIGURATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | D-003 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Engineer |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Docker Architecture](#2-docker-architecture)
3. [Development Environment](#3-development-environment)
4. [Production Configuration](#4-production-configuration)
5. [Service Definitions](#5-service-definitions)
6. [Networking](#6-networking)
7. [Storage & Volumes](#7-storage--volumes)
8. [Security Configuration](#8-security-configuration)
9. [Monitoring & Logging](#9-monitoring--logging)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide provides comprehensive Docker containerization specifications for the Smart Dairy Smart Web Portal System and Integrated ERP. It covers development, staging, and production deployment configurations.

### 1.2 Scope

- Docker architecture and service decomposition
- Docker Compose configurations for all environments
- Container build specifications
- Networking and storage configurations
- Security hardening guidelines
- Operational procedures

### 1.3 Prerequisites

| Requirement | Version | Purpose |
|-------------|---------|---------|
| Docker Engine | 24.0+ | Container runtime |
| Docker Compose | 2.20+ | Multi-container orchestration |
| Git | 2.40+ | Version control |
| Make | 4.0+ | Build automation |

---

## 2. DOCKER ARCHITECTURE

### 2.1 Container Topology

```
┌─────────────────────────────────────────────────────────────────┐
│                    SMART DAIRY CONTAINER ARCHITECTURE            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                     REVERSE PROXY LAYER                    │  │
│  │  ┌─────────────┐                                          │  │
│  │  │   nginx     │  - SSL termination                        │  │
│  │  │   (web)     │  - Static file serving                    │  │
│  │  │             │  - Load balancing                         │  │
│  │  └──────┬──────┘                                          │  │
│  └─────────┼─────────────────────────────────────────────────┘  │
│            │                                                     │
│            ▼                                                     │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                   APPLICATION LAYER                        │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │  │
│  │  │   odoo      │  │   fastapi   │  │   celery    │        │  │
│  │  │   (app)     │  │   (api)     │  │  (worker)   │        │  │
│  │  │             │  │             │  │             │        │  │
│  │  │  - Odoo 19  │  │  - Mobile   │  │  - Background│        │  │
│  │  │  - Python   │  │    API      │  │    tasks    │        │  │
│  │  │  - Custom   │  │  - IoT      │  │  - Email    │        │  │
│  │  │    modules  │  │    Gateway  │  │  - Reports  │        │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘        │  │
│  └───────────────────────────────────────────────────────────┘  │
│                                                                  │
│            ▼                                                     │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                      DATA LAYER                            │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │  │
│  │  │  postgres   │  │    redis    │  │  minio/s3   │        │  │
│  │  │   (db)      │  │   (cache)   │  │  (storage)  │        │  │
│  │  │             │  │             │  │             │        │  │
│  │  │  - Primary  │  │  - Session  │  │  - Files    │        │  │
│  │  │  - Timescale│  │  - Cache    │  │  - Images   │        │  │
│  │  │  - Read rpl │  │  - Queue    │  │  - Backups  │        │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘        │  │
│  └───────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                    SUPPORT SERVICES                        │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │  │
│  │  │  prometheus │  │   grafana   │  │    elk      │        │  │
│  │  │ (metrics)   │  │ (dashboard) │  │   (logs)    │        │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘        │  │
│  └───────────────────────────────────────────────────────────┘  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Service Dependencies

```
┌─────────────────────────────────────────────────────────────────┐
│                    SERVICE DEPENDENCY GRAPH                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  nginx                                                          │
│    ├── odoo (depends on: db, redis)                             │
│    └── fastapi (depends on: db, redis)                          │
│                                                                  │
│  odoo                                                           │
│    ├── postgres (depends on: none)                              │
│    └── redis (depends on: none)                                 │
│                                                                  │
│  celery                                                         │
│    ├── redis (depends on: none)                                 │
│    └── odoo (depends on: db, redis)                             │
│                                                                  │
│  minio                                                          │
│    └── (depends on: none)                                       │
│                                                                  │
│  monitoring                                                     │
│    ├── prometheus (scrapes: all services)                       │
│    └── grafana (depends on: prometheus)                         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. DEVELOPMENT ENVIRONMENT

### 3.1 Development Docker Compose

```yaml
# docker-compose.dev.yml
version: '3.8'

services:
  # PostgreSQL Database
  db:
    image: postgres:16-alpine
    container_name: smart_dairy_db
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${POSTGRES_DB:-smart_dairy}
      POSTGRES_USER: ${POSTGRES_USER:-odoo}
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-odoo}
      PGDATA: /var/lib/postgresql/data/pgdata
    volumes:
      - postgres_data:/var/lib/postgresql/data/pgdata
      - ./init-scripts:/docker-entrypoint-initdb.d:ro
    ports:
      - "${POSTGRES_PORT:-5432}:5432"
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${POSTGRES_USER:-odoo} -d ${POSTGRES_DB:-smart_dairy}"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - smart_dairy_network

  # Redis Cache & Queue
  redis:
    image: redis:7-alpine
    container_name: smart_dairy_redis
    restart: unless-stopped
    command: redis-server --appendonly yes --maxmemory 256mb --maxmemory-policy allkeys-lru
    volumes:
      - redis_data:/data
    ports:
      - "${REDIS_PORT:-6379}:6379"
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - smart_dairy_network

  # Odoo Application
  odoo:
    build:
      context: .
      dockerfile: docker/odoo/Dockerfile.dev
    container_name: smart_dairy_odoo
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      HOST: db
      PORT: 5432
      USER: ${POSTGRES_USER:-odoo}
      PASSWORD: ${POSTGRES_PASSWORD:-odoo}
      REDIS_HOST: redis
      REDIS_PORT: 6379
      ODOO_RC: /etc/odoo/odoo.conf
    volumes:
      - ./custom_addons:/mnt/extra-addons:rw
      - ./config/odoo.conf:/etc/odoo/odoo.conf:ro
      - odoo_data:/var/lib/odoo
      - odoo_sessions:/var/lib/odoo/sessions
    ports:
      - "${ODOO_PORT:-8069}:8069"
      - "${ODOO_LONGPOLL:-8072}:8072"
    command: >
      odoo
      --config /etc/odoo/odoo.conf
      --dev=reload,qweb,werkzeug,xml
      --log-level=debug
    networks:
      - smart_dairy_network

  # FastAPI Gateway
  fastapi:
    build:
      context: ./services/fastapi
      dockerfile: Dockerfile.dev
    container_name: smart_dairy_fastapi
    restart: unless-stopped
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      DATABASE_URL: postgresql://${POSTGRES_USER:-odoo}:${POSTGRES_PASSWORD:-odoo}@db:5432/${POSTGRES_DB:-smart_dairy}
      REDIS_URL: redis://redis:6379/0
      ODOO_URL: http://odoo:8069
      ODOO_DB: ${POSTGRES_DB:-smart_dairy}
      DEBUG: "true"
    volumes:
      - ./services/fastapi:/app:rw
    ports:
      - "${FASTAPI_PORT:-8000}:8000"
    command: >
      uvicorn main:app
      --host 0.0.0.0
      --port 8000
      --reload
      --log-level debug
    networks:
      - smart_dairy_network

  # Celery Worker
  celery:
    build:
      context: ./services/fastapi
      dockerfile: Dockerfile.dev
    container_name: smart_dairy_celery
    restart: unless-stopped
    depends_on:
      - redis
      - odoo
    environment:
      DATABASE_URL: postgresql://${POSTGRES_USER:-odoo}:${POSTGRES_PASSWORD:-odoo}@db:5432/${POSTGRES_DB:-smart_dairy}
      REDIS_URL: redis://redis:6379/0
      ODOO_URL: http://odoo:8069
      ODOO_DB: ${POSTGRES_DB:-smart_dairy}
      C_FORCE_ROOT: "true"
    volumes:
      - ./services/fastapi:/app:rw
    command: >
      celery -A tasks worker
      --loglevel=info
      --concurrency=2
    networks:
      - smart_dairy_network

  # Celery Beat Scheduler
  celery-beat:
    build:
      context: ./services/fastapi
      dockerfile: Dockerfile.dev
    container_name: smart_dairy_celery_beat
    restart: unless-stopped
    depends_on:
      - redis
      - odoo
    environment:
      DATABASE_URL: postgresql://${POSTGRES_USER:-odoo}:${POSTGRES_PASSWORD:-odoo}@db:5432/${POSTGRES_DB:-smart_dairy}
      REDIS_URL: redis://redis:6379/0
      C_FORCE_ROOT: "true"
    volumes:
      - ./services/fastapi:/app:rw
      - celery_beat_schedule:/app/celerybeat-schedule
    command: >
      celery -A tasks beat
      --loglevel=info
      --scheduler celery.beat.PersistentScheduler
    networks:
      - smart_dairy_network

  # MinIO Object Storage
  minio:
    image: minio/minio:latest
    container_name: smart_dairy_minio
    restart: unless-stopped
    environment:
      MINIO_ROOT_USER: ${MINIO_ROOT_USER:-minioadmin}
      MINIO_ROOT_PASSWORD: ${MINIO_ROOT_PASSWORD:-minioadmin}
    volumes:
      - minio_data:/data
    ports:
      - "${MINIO_PORT:-9000}:9000"
      - "${MINIO_CONSOLE_PORT:-9001}:9001"
    command: server /data --console-address ":9001"
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:9000/minio/health/live"]
      interval: 30s
      timeout: 20s
      retries: 3
    networks:
      - smart_dairy_network

  # Nginx Reverse Proxy
  nginx:
    image: nginx:alpine
    container_name: smart_dairy_nginx
    restart: unless-stopped
    depends_on:
      - odoo
      - fastapi
    volumes:
      - ./config/nginx/nginx.dev.conf:/etc/nginx/nginx.conf:ro
      - ./static:/usr/share/nginx/html/static:ro
    ports:
      - "${NGINX_PORT:-80}:80"
    networks:
      - smart_dairy_network

  # Mailpit (Email Testing)
  mailpit:
    image: axllent/mailpit:latest
    container_name: smart_dairy_mailpit
    restart: unless-stopped
    volumes:
      - mailpit_data:/data
    ports:
      - "${MAILPIT_SMTP_PORT:-1025}:1025"
      - "${MAILPIT_WEB_PORT:-8025}:8025"
    environment:
      MP_MAX_MESSAGES: 5000
      MP_DATA_FILE: /data/mailpit.db
      MP_SMTP_AUTH_ACCEPT_ANY: 1
      MP_SMTP_AUTH_ALLOW_INSECURE: 1
    networks:
      - smart_dairy_network

volumes:
  postgres_data:
  redis_data:
  odoo_data:
  odoo_sessions:
  minio_data:
  celery_beat_schedule:
  mailpit_data:

networks:
  smart_dairy_network:
    driver: bridge
    ipam:
      config:
        - subnet: 172.20.0.0/16
```

### 3.2 Environment Variables (.env.dev)

```bash
# Database Configuration
POSTGRES_DB=smart_dairy_dev
POSTGRES_USER=odoo
POSTGRES_PASSWORD=dev_password_change_me
POSTGRES_PORT=5432

# Redis Configuration
REDIS_PORT=6379

# Odoo Configuration
ODOO_PORT=8069
ODOO_LONGPOLL=8072

# FastAPI Configuration
FASTAPI_PORT=8000

# MinIO Configuration
MINIO_ROOT_USER=minioadmin
MINIO_ROOT_PASSWORD=minioadmin
MINIO_PORT=9000
MINIO_CONSOLE_PORT=9001

# Nginx Configuration
NGINX_PORT=80

# Mailpit Configuration
MAILPIT_SMTP_PORT=1025
MAILPIT_WEB_PORT=8025

# Application Settings
DEBUG=true
LOG_LEVEL=debug
SECRET_KEY=dev-secret-key-change-in-production
```

---

## 4. PRODUCTION CONFIGURATION

### 4.1 Production Docker Compose

```yaml
# docker-compose.prod.yml
version: '3.8'

services:
  # PostgreSQL Primary
  db:
    image: postgres:16-alpine
    container_name: smart_dairy_db
    restart: always
    deploy:
      resources:
        limits:
          cpus: '2.0'
          memory: 4G
        reservations:
          cpus: '1.0'
          memory: 2G
    environment:
      POSTGRES_DB: ${POSTGRES_DB}
      POSTGRES_USER: ${POSTGRES_USER}
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
      PGDATA: /var/lib/postgresql/data/pgdata
    volumes:
      - postgres_data:/var/lib/postgresql/data/pgdata
      - ./backups:/backups
      - ./init-scripts:/docker-entrypoint-initdb.d:ro
    command: >
      postgres
      -c max_connections=200
      -c shared_buffers=2GB
      -c effective_cache_size=6GB
      -c maintenance_work_mem=512MB
      -c checkpoint_completion_target=0.9
      -c wal_buffers=16MB
      -c default_statistics_target=100
      -c random_page_cost=1.1
      -c effective_io_concurrency=200
      -c work_mem=5242kB
      -c min_wal_size=1GB
      -c max_wal_size=4GB
      -c max_worker_processes=4
      -c max_parallel_workers_per_gather=2
      -c max_parallel_workers=4
      -c max_parallel_maintenance_workers=2
    networks:
      - backend_network
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${POSTGRES_USER} -d ${POSTGRES_DB}"]
      interval: 30s
      timeout: 10s
      retries: 5

  # Redis Cluster
  redis:
    image: redis:7-alpine
    container_name: smart_dairy_redis
    restart: always
    deploy:
      resources:
        limits:
          cpus: '1.0'
          memory: 2G
    command: >
      redis-server
      --appendonly yes
      --appendfsync everysec
      --maxmemory 1536mb
      --maxmemory-policy allkeys-lru
      --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis_data:/data
    networks:
      - backend_network
    healthcheck:
      test: ["CMD", "redis-cli", "-a", "${REDIS_PASSWORD}", "ping"]
      interval: 30s
      timeout: 10s
      retries: 5

  # Odoo Application (Multiple instances for scaling)
  odoo:
    build:
      context: .
      dockerfile: docker/odoo/Dockerfile.prod
    image: smart-dairy/odoo:${VERSION:-latest}
    restart: always
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '2.0'
          memory: 4G
        reservations:
          cpus: '1.0'
          memory: 2G
      update_config:
        parallelism: 1
        delay: 10s
        failure_action: rollback
      rollback_config:
        parallelism: 1
        delay: 5s
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      HOST: db
      PORT: 5432
      USER: ${POSTGRES_USER}
      PASSWORD: ${POSTGRES_PASSWORD}
      REDIS_HOST: redis
      REDIS_PORT: 6379
      REDIS_PASSWORD: ${REDIS_PASSWORD}
      ODOO_RC: /etc/odoo/odoo.conf
      WORKERS: 4
      MAX_CRON_THREADS: 2
    volumes:
      - odoo_data:/var/lib/odoo
      - odoo_sessions:/var/lib/odoo/sessions
      - ./custom_addons:/mnt/extra-addons:ro
      - ./config/odoo.prod.conf:/etc/odoo/odoo.conf:ro
    networks:
      - backend_network
      - frontend_network
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8069/web/health"]
      interval: 30s
      timeout: 10s
      retries: 5

  # FastAPI Production
  fastapi:
    build:
      context: ./services/fastapi
      dockerfile: Dockerfile.prod
    image: smart-dairy/fastapi:${VERSION:-latest}
    restart: always
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '1.0'
          memory: 1G
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      DATABASE_URL: postgresql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@db:5432/${POSTGRES_DB}
      REDIS_URL: redis://:${REDIS_PASSWORD}@redis:6379/0
      ODOO_URL: http://odoo:8069
      ODOO_DB: ${POSTGRES_DB}
      WORKERS: 4
      DEBUG: "false"
      SECRET_KEY: ${SECRET_KEY}
    networks:
      - backend_network
      - frontend_network
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8000/health"]
      interval: 30s
      timeout: 10s
      retries: 5

  # Celery Worker Production
  celery:
    build:
      context: ./services/fastapi
      dockerfile: Dockerfile.prod
    image: smart-dairy/celery:${VERSION:-latest}
    restart: always
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '1.0'
          memory: 1G
    depends_on:
      - redis
      - odoo
    environment:
      DATABASE_URL: postgresql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@db:5432/${POSTGRES_DB}
      REDIS_URL: redis://:${REDIS_PASSWORD}@redis:6379/0
      ODOO_URL: http://odoo:8069
      ODOO_DB: ${POSTGRES_DB}
      C_FORCE_ROOT: "true"
    networks:
      - backend_network

  # Celery Beat
  celery-beat:
    build:
      context: ./services/fastapi
      dockerfile: Dockerfile.prod
    image: smart-dairy/celery-beat:${VERSION:-latest}
    restart: always
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
    depends_on:
      - redis
      - odoo
    environment:
      REDIS_URL: redis://:${REDIS_PASSWORD}@redis:6379/0
      C_FORCE_ROOT: "true"
    volumes:
      - celery_beat_schedule:/app/celerybeat-schedule
    networks:
      - backend_network

  # Nginx Load Balancer
  nginx:
    image: nginx:alpine
    container_name: smart_dairy_nginx
    restart: always
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 256M
    depends_on:
      - odoo
      - fastapi
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./config/nginx/nginx.prod.conf:/etc/nginx/nginx.conf:ro
      - ./config/nginx/ssl:/etc/nginx/ssl:ro
      - static_files:/usr/share/nginx/html/static:ro
      - ./logs/nginx:/var/log/nginx
    networks:
      - frontend_network
    healthcheck:
      test: ["CMD", "wget", "--quiet", "--tries=1", "--spider", "http://localhost/health"]
      interval: 30s
      timeout: 10s
      retries: 5

  # Prometheus Monitoring
  prometheus:
    image: prom/prometheus:latest
    container_name: smart_dairy_prometheus
    restart: always
    volumes:
      - ./config/prometheus:/etc/prometheus:ro
      - prometheus_data:/prometheus
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.path=/prometheus'
      - '--web.console.libraries=/usr/share/prometheus/console_libraries'
      - '--web.console.templates=/usr/share/prometheus/consoles'
    networks:
      - backend_network
      - monitoring_network

  # Grafana Dashboard
  grafana:
    image: grafana/grafana:latest
    container_name: smart_dairy_grafana
    restart: always
    environment:
      GF_SECURITY_ADMIN_PASSWORD: ${GRAFANA_PASSWORD}
      GF_USERS_ALLOW_SIGN_UP: "false"
    volumes:
      - grafana_data:/var/lib/grafana
      - ./config/grafana/dashboards:/etc/grafana/provisioning/dashboards:ro
      - ./config/grafana/datasources:/etc/grafana/provisioning/datasources:ro
    ports:
      - "${GRAFANA_PORT:-3000}:3000"
    networks:
      - monitoring_network

volumes:
  postgres_data:
    driver: local
  redis_data:
    driver: local
  odoo_data:
    driver: local
  odoo_sessions:
    driver: local
  static_files:
    driver: local
  celery_beat_schedule:
    driver: local
  prometheus_data:
    driver: local
  grafana_data:
    driver: local

networks:
  backend_network:
    driver: bridge
    internal: true
  frontend_network:
    driver: bridge
  monitoring_network:
    driver: bridge
```

---

## 5. SERVICE DEFINITIONS

### 5.1 Odoo Dockerfile

```dockerfile
# docker/odoo/Dockerfile.prod
FROM odoo:19.0

USER root

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc \
    python3-dev \
    libxml2-dev \
    libxslt1-dev \
    libldap2-dev \
    libsasl2-dev \
    libtiff5-dev \
    libjpeg62-turbo-dev \
    zlib1g-dev \
    libfreetype6-dev \
    liblcms2-dev \
    libwebp-dev \
    libharfbuzz-dev \
    libfribidi-dev \
    libxcb1-dev \
    libpq-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install Python packages
COPY requirements.txt /tmp/
RUN pip install --no-cache-dir -r /tmp/requirements.txt

# Create directories
RUN mkdir -p /mnt/extra-addons /var/lib/odoo/sessions /var/lib/odoo/filestore \
    && chown -R odoo:odoo /mnt/extra-addons /var/lib/odoo

# Copy custom modules
COPY --chown=odoo:odoo custom_addons /mnt/extra-addons

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=5 \
    CMD curl -f http://localhost:8069/web/health || exit 1

USER odoo

EXPOSE 8069 8072
```

### 5.2 FastAPI Dockerfile

```dockerfile
# services/fastapi/Dockerfile.prod
FROM python:3.11-slim

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc \
    libpq-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install Python dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY ./app ./app

# Create non-root user
RUN useradd -m -u 1000 appuser && chown -R appuser:appuser /app
USER appuser

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

EXPOSE 8000

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000", "--workers", "4"]
```

---

## 6. NETWORKING

### 6.1 Network Architecture

```yaml
# Network configuration
networks:
  # Internal network for database and backend services
  backend_network:
    driver: bridge
    internal: true  # No external access
    ipam:
      config:
        - subnet: 172.20.0.0/24
          gateway: 172.20.0.1

  # Frontend network for web services
  frontend_network:
    driver: bridge
    ipam:
      config:
        - subnet: 172.20.1.0/24
          gateway: 172.20.1.1

  # Monitoring network (isolated)
  monitoring_network:
    driver: bridge
    internal: true
    ipam:
      config:
        - subnet: 172.20.2.0/24
          gateway: 172.20.2.1
```

### 6.2 Nginx Configuration

```nginx
# config/nginx/nginx.prod.conf
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 4096;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    # Logging format
    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for" '
                    'rt=$request_time uct="$upstream_connect_time" '
                    'uht="$upstream_header_time" urt="$upstream_response_time"';

    access_log /var/log/nginx/access.log main;

    # Performance
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript application/rss+xml application/atom+xml image/svg+xml;

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=api_limit:10m rate=10r/s;
    limit_req_zone $binary_remote_addr zone=login_limit:10m rate=1r/s;

    # Upstream definitions
    upstream odoo_backend {
        least_conn;
        server odoo:8069 max_fails=3 fail_timeout=30s;
        keepalive 64;
    }

    upstream fastapi_backend {
        least_conn;
        server fastapi:8000 max_fails=3 fail_timeout=30s;
        keepalive 64;
    }

    # HTTP to HTTPS redirect
    server {
        listen 80;
        server_name _;
        return 301 https://$host$request_uri;
    }

    # Main HTTPS server
    server {
        listen 443 ssl http2;
        server_name smartdairybd.com www.smartdairybd.com;

        # SSL Configuration
        ssl_certificate /etc/nginx/ssl/cert.pem;
        ssl_certificate_key /etc/nginx/ssl/key.pem;
        ssl_session_timeout 1d;
        ssl_session_cache shared:SSL:50m;
        ssl_session_tickets off;
        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
        ssl_prefer_server_ciphers off;

        # Security headers
        add_header X-Frame-Options "SAMEORIGIN" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header X-XSS-Protection "1; mode=block" always;
        add_header Referrer-Policy "strict-origin-when-cross-origin" always;

        # Static files
        location /static/ {
            alias /usr/share/nginx/html/static/;
            expires 6M;
            access_log off;
            add_header Cache-Control "public, immutable";
        }

        # API routes
        location /api/ {
            limit_req zone=api_limit burst=20 nodelay;
            proxy_pass http://fastapi_backend/;
            proxy_http_version 1.1;
            proxy_set_header Connection "";
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
            proxy_buffering off;
            proxy_read_timeout 86400;
        }

        # Odoo long polling
        location /longpolling/ {
            proxy_pass http://odoo_backend/longpolling/;
            proxy_http_version 1.1;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection "upgrade";
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }

        # Odoo main
        location / {
            proxy_pass http://odoo_backend;
            proxy_http_version 1.1;
            proxy_set_header Connection "";
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
            proxy_buffering off;
        }

        # Health check
        location /health {
            access_log off;
            return 200 "healthy\n";
            add_header Content-Type text/plain;
        }
    }
}
```

---

## 7. STORAGE & VOLUMES

### 7.1 Volume Configuration

| Volume | Purpose | Backup Strategy | Retention |
|--------|---------|-----------------|-----------|
| `postgres_data` | Database files | Daily pg_dump | 30 days |
| `redis_data` | Cache & sessions | RDB snapshots | 7 days |
| `odoo_data` | Filestore | Daily rsync | 30 days |
| `odoo_sessions` | User sessions | None | N/A |
| `minio_data` | Object storage | Cross-region replication | 90 days |
| `prometheus_data` | Metrics | Remote storage | 15 days |
| `grafana_data` | Dashboards | Git backup | Infinite |

### 7.2 Backup Script

```bash
#!/bin/bash
# scripts/backup.sh

BACKUP_DIR="/backups/$(date +%Y%m%d_%H%M%S)"
mkdir -p $BACKUP_DIR

# Database backup
docker exec smart_dairy_db pg_dump -U odoo smart_dairy > $BACKUP_DIR/database.sql

# Odoo filestore backup
docker run --rm -v smart_dairy_odoo_data:/source -v $BACKUP_DIR:/backup alpine tar czf /backup/filestore.tar.gz -C /source .

# Redis backup (trigger BGSAVE)
docker exec smart_dairy_redis redis-cli BGSAVE

# Compress and upload to S3
tar czf $BACKUP_DIR.tar.gz $BACKUP_DIR
aws s3 cp $BACKUP_DIR.tar.gz s3://smart-dairy-backups/

# Cleanup old backups (keep 30 days)
find /backups -name "*.tar.gz" -mtime +30 -delete
```

---

## 8. SECURITY CONFIGURATION

### 8.1 Container Security

| Practice | Implementation |
|----------|----------------|
| **Non-root users** | All services run as non-root |
| **Read-only filesystem** | Application code mounted read-only |
| **No new privileges** | `no-new-privileges: true` |
| **Resource limits** | CPU and memory limits defined |
| **Secrets management** | Use Docker secrets or env files |
| **Image scanning** | Trivy scan in CI/CD pipeline |

### 8.2 Docker Secrets (Production)

```yaml
# docker-compose.secrets.yml
secrets:
  postgres_password:
    external: true
  redis_password:
    external: true
  secret_key:
    external: true

services:
  db:
    secrets:
      - postgres_password
    environment:
      POSTGRES_PASSWORD_FILE: /run/secrets/postgres_password
```

---

## 9. MONITORING & LOGGING

### 9.1 Health Checks

| Service | Endpoint | Interval |
|---------|----------|----------|
| Odoo | `/web/health` | 30s |
| FastAPI | `/health` | 30s |
| PostgreSQL | `pg_isready` | 10s |
| Redis | `redis-cli ping` | 10s |
| Nginx | `/health` | 30s |

### 9.2 Logging Configuration

```yaml
# Centralized logging with Fluentd
logging:
  driver: fluentd
  options:
    fluentd-address: localhost:24224
    tag: docker.{{.Name}}
```

---

## 10. TROUBLESHOOTING

### 10.1 Common Issues

| Issue | Cause | Solution |
|-------|-------|----------|
| Container won't start | Port conflict | Change host port mapping |
| Database connection failed | Wrong credentials | Check env variables |
| Permission denied | Wrong file ownership | `chown -R odoo:odoo` |
| Out of memory | No limits set | Add memory limits |
| Slow performance | No connection pooling | Enable pgbouncer |

### 10.2 Debug Commands

```bash
# View container logs
docker-compose logs -f odoo

# Execute command in container
docker-compose exec odoo bash

# Check resource usage
docker stats

# Network inspection
docker network inspect smart_dairy_network

# Volume inspection
docker volume inspect smart_dairy_postgres_data
```

---

## 11. APPENDICES

### Appendix A: Quick Start Commands

```bash
# Development
cp .env.example .env.dev
docker-compose -f docker-compose.dev.yml up -d

# Production
cp .env.example .env.prod
docker-compose -f docker-compose.prod.yml up -d

# Scale services
docker-compose -f docker-compose.prod.yml up -d --scale odoo=3

# Update deployment
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d

# View logs
docker-compose logs -f --tail=100

# Cleanup
docker system prune -a --volumes
```

---

**END OF DOCKER CONFIGURATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | DevOps Engineer | Initial version |
