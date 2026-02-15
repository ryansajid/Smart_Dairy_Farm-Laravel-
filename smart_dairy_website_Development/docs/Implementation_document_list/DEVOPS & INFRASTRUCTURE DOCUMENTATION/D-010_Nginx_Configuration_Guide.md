# Document: D-010 - Nginx Configuration Guide

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | D-010 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |
| **Status** | Approved |
| **Classification** | Internal Use |

---

## Revision History

| Version | Date | Author | Description of Changes |
|---------|------|--------|------------------------|
| 1.0 | 2026-01-31 | DevOps Engineer | Initial release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Architecture](#2-architecture)
3. [Core Configuration](#3-core-configuration)
4. [Reverse Proxy Setup](#4-reverse-proxy-setup)
5. [SSL/TLS Configuration](#5-ssltls-configuration)
6. [Load Balancing](#6-load-balancing)
7. [Caching Configuration](#7-caching-configuration)
8. [Security Headers](#8-security-headers)
9. [Performance Tuning](#9-performance-tuning)
10. [Logging Format](#10-logging-format)
11. [Troubleshooting](#11-troubleshooting)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive configuration guidelines for Nginx as the primary web server, reverse proxy, and load balancer for the Smart Dairy Smart Web Portal System. Nginx serves as the critical entry point for all incoming traffic, handling:

- **Reverse Proxy**: Routing requests to appropriate backend services (Odoo ERP, FastAPI microservices)
- **Load Balancing**: Distributing traffic across multiple backend instances
- **SSL/TLS Termination**: Managing encrypted connections and offloading TLS processing
- **Static Content Delivery**: Serving cached static assets efficiently
- **Security Enforcement**: Implementing security headers and rate limiting

### 1.2 Scope

This guide covers Nginx configurations for:
- Kubernetes Ingress Controller deployment on Amazon EKS
- Nginx as sidecar proxy for service mesh patterns
- Standalone Nginx deployments for on-premise installations
- Configuration for up to 10,000 concurrent users

### 1.3 Technology Context

| Component | Technology | Port | Description |
|-----------|------------|------|-------------|
| Ingress Controller | Nginx Ingress Controller | 80/443 | Kubernetes Ingress |
| ERP Backend | Odoo 19 CE | 8069 | Primary ERP system |
| API Gateway | FastAPI | 8000 | Microservices API |
| Static Assets | S3/CloudFront or Nginx | 80/443 | Static file delivery |
| SSL Certificates | cert-manager | - | Automated certificate management |

---

## 2. Architecture

### 2.1 High-Level Architecture

```
                                    ┌─────────────────────────────────────┐
                                    │         Internet / Users            │
                                    └─────────────┬───────────────────────┘
                                                  │
                                                  ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                              AWS Cloud (EKS Cluster)                             │
│                                                                                  │
│  ┌───────────────────────────────────────────────────────────────────────────┐  │
│  │                        Route 53 (DNS)                                      │  │
│  │              smartdairybd.com, *.smartdairybd.com                         │  │
│  └─────────────────────────┬─────────────────────────────────────────────────┘  │
│                            │                                                    │
│                            ▼                                                    │
│  ┌───────────────────────────────────────────────────────────────────────────┐  │
│  │                    AWS Application Load Balancer (ALB)                     │  │
│  │                    SSL Termination (Optional)                              │  │
│  └─────────────────────────┬─────────────────────────────────────────────────┘  │
│                            │                                                    │
│                            ▼                                                    │
│  ┌───────────────────────────────────────────────────────────────────────────┐  │
│  │              Nginx Ingress Controller (Kubernetes)                         │  │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐           │  │
│  │  │   Ingress       │  │   Ingress       │  │   Ingress       │           │  │
│  │  │   Public Web    │  │   B2C Portal    │  │   B2B Portal    │           │  │
│  │  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘           │  │
│  │           │                    │                    │                      │  │
│  │           └────────────────────┼────────────────────┘                      │  │
│  │                                │                                           │  │
│  │  ┌─────────────────────────────────────────────────────────────────────┐  │  │
│  │  │                    Rate Limiting / WAF Rules                         │  │  │
│  │  └─────────────────────────────────────────────────────────────────────┘  │  │
│  └─────────────────────────┬─────────────────────────────────────────────────┘  │
│                            │                                                    │
│         ┌──────────────────┼──────────────────┐                                 │
│         │                  │                  │                                 │
│         ▼                  ▼                  ▼                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐                         │
│  │  Odoo Pods  │    │ FastAPI Pods│    │  S3/Static  │                         │
│  │   :8069     │    │   :8000     │    │   Assets    │                         │
│  └─────────────┘    └─────────────┘    └─────────────┘                         │
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Nginx as Kubernetes Ingress Controller

The Nginx Ingress Controller is deployed as a DaemonSet or Deployment in the EKS cluster:

```yaml
# nginx-ingress-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx-ingress-controller
  namespace: ingress-nginx
spec:
  replicas: 3
  selector:
    matchLabels:
      app.kubernetes.io/name: ingress-nginx
  template:
    metadata:
      labels:
        app.kubernetes.io/name: ingress-nginx
    spec:
      containers:
        - name: controller
          image: k8s.gcr.io/ingress-nginx/controller:v1.9.6
          args:
            - /nginx-ingress-controller
            - --ingress-class=nginx
            - --configmap=$(POD_NAMESPACE)/ingress-nginx-controller
            - --validating-webhook=:8443
            - --validating-webhook-certificate=/usr/local/certificates/cert
            - --validating-webhook-key=/usr/local/certificates/key
          securityContext:
            allowPrivilegeEscalation: false
            readOnlyRootFilesystem: true
            runAsNonRoot: true
          resources:
            requests:
              cpu: 500m
              memory: 512Mi
            limits:
              cpu: 2000m
              memory: 2Gi
          ports:
            - name: http
              containerPort: 80
              protocol: TCP
            - name: https
              containerPort: 443
              protocol: TCP
```

### 2.3 Nginx as Sidecar Pattern

For microservices requiring dedicated proxy capabilities:

```yaml
# sidecar-nginx.yaml
apiVersion: v1
kind: Pod
metadata:
  name: odoo-with-nginx-sidecar
spec:
  containers:
    - name: odoo
      image: smart-dairy/odoo:19.0
      ports:
        - containerPort: 8069
    - name: nginx-sidecar
      image: nginx:1.25-alpine
      volumeMounts:
        - name: nginx-config
          mountPath: /etc/nginx/nginx.conf
          subPath: nginx.conf
      ports:
        - containerPort: 8080
  volumes:
    - name: nginx-config
      configMap:
        name: nginx-sidecar-config
```

---

## 3. Core Configuration

### 3.1 nginx.conf Structure

The main Nginx configuration file is organized into logical sections:

```nginx
# /etc/nginx/nginx.conf

#===================================
# 1. Main Context (Global Settings)
#===================================
user nginx;
worker_processes auto;
worker_rlimit_nofile 65535;
pid /var/run/nginx.pid;
error_log /var/log/nginx/error.log warn;

#===================================
# 2. Events Context
#===================================
events {
    worker_connections 16384;
    use epoll;
    multi_accept on;
}

#===================================
# 3. HTTP Context
#===================================
http {
    # Basic Settings
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    
    # Performance Settings
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    
    # Connection Settings
    keepalive_timeout 65;
    keepalive_requests 1000;
    
    # Compression
    gzip on;
    
    # SSL Settings
    ssl_protocols TLSv1.2 TLSv1.3;
    
    # Logging
    access_log /var/log/nginx/access.log;
    
    # Include additional configurations
    include /etc/nginx/conf.d/*.conf;
}
```

### 3.2 Worker Process Configuration

For Smart Dairy's load (up to 10,000 concurrent users):

```nginx
# Worker Process Optimization
user nginx;

# Auto-detect number of CPU cores
worker_processes auto;

# Bind workers to CPU cores (optional, for dedicated servers)
# worker_cpu_affinity auto;

# Maximum open files per worker (should match ulimit -n)
worker_rlimit_nofile 65535;

# Error log level: debug, info, notice, warn, error, crit, alert, emerg
error_log /var/log/nginx/error.log warn;

pid /var/run/nginx.pid;
```

**Calculation for 10,000 concurrent users:**
- Connections per user: ~2 (keepalive)
- Total connections: 20,000
- Worker connections: 16,384 × number of workers
- Recommended: 4-8 workers on production instances

### 3.3 Events Block Configuration

```nginx
events {
    # Maximum connections per worker process
    worker_connections 16384;
    
    # Use efficient event method for Linux
    use epoll;
    
    # Accept multiple connections per cycle
    multi_accept on;
    
    # Accept mutex for load balancing between workers
    accept_mutex on;
    accept_mutex_delay 1ms;
}
```

---

## 4. Reverse Proxy Setup

### 4.1 Upstream Definitions

```nginx
# /etc/nginx/conf.d/upstreams.conf

# Odoo ERP Backend - Multiple Instances
upstream odoo_backend {
    least_conn;
    
    server odoo-1.smart-dairy.svc.cluster.local:8069 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-2.smart-dairy.svc.cluster.local:8069 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-3.smart-dairy.svc.cluster.local:8069 weight=5 max_fails=3 fail_timeout=30s;
    
    # Keepalive connections to backend
    keepalive 32;
    keepalive_requests 100;
    keepalive_timeout 60s;
}

# Odoo Long Polling (for real-time features)
upstream odoo_im {
    least_conn;
    
    server odoo-1.smart-dairy.svc.cluster.local:8072 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-2.smart-dairy.svc.cluster.local:8072 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-3.smart-dairy.svc.cluster.local:8072 weight=5 max_fails=3 fail_timeout=30s;
    
    keepalive 32;
}

# FastAPI Microservices Backend
upstream fastapi_backend {
    least_conn;
    
    server fastapi-1.smart-dairy.svc.cluster.local:8000 weight=5 max_fails=3 fail_timeout=30s;
    server fastapi-2.smart-dairy.svc.cluster.local:8000 weight=5 max_fails=3 fail_timeout=30s;
    server fastapi-3.smart-dairy.svc.cluster.local:8000 weight=5 max_fails=3 fail_timeout=30s;
    
    keepalive 64;
    keepalive_requests 1000;
    keepalive_timeout 60s;
}

# Static Assets (S3 via Nginx cache)
upstream static_backend {
    server smart-dairy-static.s3.amazonaws.com:443;
    
    keepalive 32;
}
```

### 4.2 Odoo Reverse Proxy Configuration

```nginx
# /etc/nginx/conf.d/odoo.conf

server {
    listen 80;
    server_name erp.smartdairybd.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name erp.smartdairybd.com;
    
    # SSL Configuration (see Section 5)
    ssl_certificate /etc/ssl/certs/smartdairybd.com.crt;
    ssl_certificate_key /etc/ssl/private/smartdairybd.com.key;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # Client Body Size (for file uploads)
    client_max_body_size 500M;
    client_body_buffer_size 16K;
    
    # Proxy Timeouts
    proxy_connect_timeout 600s;
    proxy_send_timeout 600s;
    proxy_read_timeout 600s;
    
    # Proxy Buffer Settings
    proxy_buffering on;
    proxy_buffer_size 4k;
    proxy_buffers 8 4k;
    proxy_busy_buffers_size 8k;
    
    # Proxy Headers
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Port $server_port;
    
    # WebSocket Support (for Odoo long polling)
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    
    # Static Files - Direct serve or cache
    location ~* /web/static/ {
        proxy_pass http://odoo_backend;
        proxy_cache_valid 200 1d;
        expires 1d;
        add_header Cache-Control "public, immutable";
    }
    
    # Long Polling Endpoint
    location /longpolling {
        proxy_pass http://odoo_im;
        proxy_read_timeout 3600s;
        proxy_send_timeout 3600s;
    }
    
    # Main Application
    location / {
        proxy_pass http://odoo_backend;
        proxy_redirect off;
    }
    
    # Health Check Endpoint
    location /nginx-health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }
}
```

### 4.3 FastAPI Reverse Proxy Configuration

```nginx
# /etc/nginx/conf.d/fastapi.conf

server {
    listen 80;
    server_name api.smartdairybd.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.smartdairybd.com;
    
    # SSL Configuration
    ssl_certificate /etc/ssl/certs/smartdairybd.com.crt;
    ssl_certificate_key /etc/ssl/private/smartdairybd.com.key;
    
    # API-Specific Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "DENY" always;
    
    # CORS Headers (if not handled by FastAPI)
    add_header 'Access-Control-Allow-Origin' '*' always;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;
    add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization' always;
    
    # Request Size Limits
    client_max_body_size 50M;
    
    # Proxy Headers
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    
    # API Rate Limiting Zone
    limit_req_zone $binary_remote_addr zone=api_limit:10m rate=100r/m;
    limit_req zone=api_limit burst=150 nodelay;
    
    # API Documentation (Swagger UI)
    location /docs {
        proxy_pass http://fastapi_backend;
        proxy_cache_bypass $http_upgrade;
    }
    
    # API Endpoints
    location /api/ {
        proxy_pass http://fastapi_backend;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
        
        # API Caching (for GET requests)
        proxy_cache api_cache;
        proxy_cache_valid 200 5m;
        proxy_cache_use_stale error timeout updating http_500 http_502 http_503 http_504;
        proxy_cache_key "$scheme$request_method$host$request_uri";
    }
    
    # WebSocket Support
    location /ws/ {
        proxy_pass http://fastapi_backend;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_read_timeout 86400;
    }
    
    # Health Check
    location /health {
        access_log off;
        proxy_pass http://fastapi_backend/health;
    }
}
```

---

## 5. SSL/TLS Configuration

### 5.1 SSL Certificate Paths

```nginx
# Certificate Configuration for Smart Dairy

# Primary Domain Certificates (managed by cert-manager in K8s)
ssl_certificate /etc/letsencrypt/live/smartdairybd.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/smartdairybd.com/privkey.pem;

# Alternative: AWS ACM with ALB (pass-through to Nginx)
# When using ACM, ALB terminates SSL and forwards HTTP to Nginx

# Certificate Chain (for manually managed certs)
# ssl_trusted_certificate /etc/ssl/certs/ca-chain.crt;
```

### 5.2 TLS Protocol and Cipher Configuration

```nginx
# /etc/nginx/conf.d/ssl.conf

# SSL Protocols - TLS 1.2 and above only
ssl_protocols TLSv1.2 TLSv1.3;

# SSL Ciphers - Strong cipher suite selection
ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;

# Prefer server ciphers
ssl_prefer_server_ciphers off;

# SSL Session Settings
ssl_session_timeout 1d;
ssl_session_cache shared:SSL:50m;
ssl_session_tickets off;

# OCSP Stapling
ssl_stapling on;
ssl_stapling_verify on;

# DNS resolver for OCSP
resolver 8.8.8.8 8.8.4.4 valid=300s;
resolver_timeout 5s;

# Diffie-Hellman Parameters (for DHE ciphers)
ssl_dhparam /etc/ssl/certs/dhparam.pem;
```

### 5.3 Generate DH Parameters

```bash
# Generate 2048-bit DH parameters (run once)
openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048

# For higher security (slower), use 4096-bit
# openssl dhparam -out /etc/ssl/certs/dhparam.pem 4096
```

### 5.4 Kubernetes Ingress with cert-manager

```yaml
# certificate.yaml - cert-manager Certificate resource
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: smartdairy-tls
  namespace: smart-dairy
spec:
  secretName: smartdairy-tls-secret
  issuerRef:
    name: letsencrypt-prod
    kind: ClusterIssuer
  dnsNames:
    - smartdairybd.com
    - www.smartdairybd.com
    - erp.smartdairybd.com
    - api.smartdairybd.com
    - shop.smartdairybd.com
    - b2b.smartdairybd.com
```

```yaml
# ingress-tls.yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: smart-dairy-ingress
  namespace: smart-dairy
  annotations:
    kubernetes.io/ingress.class: nginx
    cert-manager.io/cluster-issuer: letsencrypt-prod
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
    nginx.ingress.kubernetes.io/force-ssl-redirect: "true"
spec:
  tls:
    - hosts:
        - smartdairybd.com
        - www.smartdairybd.com
        - erp.smartdairybd.com
        - api.smartdairybd.com
      secretName: smartdairy-tls-secret
  rules:
    - host: erp.smartdairybd.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: odoo-service
                port:
                  number: 8069
    - host: api.smartdairybd.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: fastapi-service
                port:
                  number: 8000
```

---

## 6. Load Balancing

### 6.1 Load Balancing Algorithms

```nginx
# /etc/nginx/conf.d/load-balancing.conf

# 1. Round Robin (Default)
upstream odoo_round_robin {
    server odoo-1:8069;
    server odoo-2:8069;
    server odoo-3:8069;
}

# 2. Least Connections (Recommended for varying request times)
upstream odoo_least_conn {
    least_conn;
    server odoo-1:8069;
    server odoo-2:8069;
    server odoo-3:8069;
}

# 3. IP Hash (Session persistence)
upstream odoo_ip_hash {
    ip_hash;
    server odoo-1:8069;
    server odoo-2:8069;
    server odoo-3:8069;
}

# 4. Weighted Load Balancing
upstream odoo_weighted {
    server odoo-1:8069 weight=5;  # 50% traffic
    server odoo-2:8069 weight=3;  # 30% traffic
    server odoo-3:8069 weight=2;  # 20% traffic
}

# 5. Hash (for consistent routing based on key)
upstream odoo_hash {
    hash $request_uri consistent;
    server odoo-1:8069;
    server odoo-2:8069;
    server odoo-3:8069;
}
```

### 6.2 Health Checks

```nginx
# Active Health Checks (Nginx Plus) or Passive (Open Source)

upstream odoo_backend {
    least_conn;
    
    # Server definitions with health check parameters
    server odoo-1:8069 weight=5 max_fails=3 fail_timeout=30s slow_start=30s;
    server odoo-2:8069 weight=5 max_fails=3 fail_timeout=30s slow_start=30s;
    server odoo-3:8069 weight=5 max_fails=3 fail_timeout=30s slow_start=30s backup;
    
    keepalive 32;
}
```

**Health Check Parameters:**
- `max_fails=3`: Mark server down after 3 failed attempts
- `fail_timeout=30s`: Consider failed for 30 seconds
- `slow_start=30s`: Gradually increase weight after recovery
- `backup`: Use only when other servers are down

### 6.3 Sticky Sessions (Session Persistence)

```nginx
# Method 1: IP Hash (Built-in)
upstream odoo_sticky {
    ip_hash;
    server odoo-1:8069;
    server odoo-2:8069;
    server odoo-3:8069;
}

# Method 2: Cookie-Based Sticky Sessions (Nginx Plus or Third-party)
upstream odoo_cookie_sticky {
    server odoo-1:8069;
    server odoo-2:8069;
    server odoo-3:8069;
    
    sticky cookie srv_id expires=1h domain=.smartdairybd.com path=/;
}

# Method 3: Route-based Sticky Sessions
upstream odoo_route {
    server odoo-1:8069 route=server1;
    server odoo-2:8069 route=server2;
    server odoo-3:8069 route=server3;
}

server {
    location / {
        set $route "";
        if ($cookie_serverid = "1") { set $route "server1"; }
        if ($cookie_serverid = "2") { set $route "server2"; }
        if ($cookie_serverid = "3") { set $route "server3"; }
        
        proxy_pass http://odoo_route;
    }
}
```

### 6.4 Kubernetes Service Health Checks

```yaml
# odoo-service.yaml with health checks
apiVersion: v1
kind: Service
metadata:
  name: odoo-service
  namespace: smart-dairy
  annotations:
    # Nginx Ingress health check annotations
    nginx.ingress.kubernetes.io/healthcheck-uri: /web/health
    nginx.ingress.kubernetes.io/healthcheck-interval: "10"
    nginx.ingress.kubernetes.io/healthcheck-timeout: "5"
    nginx.ingress.kubernetes.io/healthcheck-fails: "3"
    nginx.ingress.kubernetes.io/healthcheck-passes: "2"
spec:
  selector:
    app: odoo
  ports:
    - port: 8069
      targetPort: 8069
  type: ClusterIP
```

---

## 7. Caching Configuration

### 7.1 Static File Caching

```nginx
# /etc/nginx/conf.d/caching.conf

# Define cache zones
proxy_cache_path /var/cache/nginx/static 
    levels=1:2 
    keys_zone=static_cache:100m 
    max_size=10g 
    inactive=60d 
    use_temp_path=off;

proxy_cache_path /var/cache/nginx/api 
    levels=1:2 
    keys_zone=api_cache:50m 
    max_size=5g 
    inactive=5m 
    use_temp_path=off;

# Static Files Location Block
location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
    proxy_pass http://backend;
    proxy_cache static_cache;
    
    # Cache Settings
    proxy_cache_valid 200 1y;
    proxy_cache_valid 404 1m;
    proxy_cache_use_stale error timeout invalid_header updating http_500 http_502 http_503 http_504;
    
    # Cache Key
    proxy_cache_key "$scheme$request_method$host$request_uri";
    
    # Add cache status header for debugging
    add_header X-Cache-Status $upstream_cache_status;
    
    # Client-side caching
    expires 1y;
    add_header Cache-Control "public, immutable";
    add_header Vary Accept-Encoding;
    
    # Compression
    gzip_static on;
}

# Odoo Static Assets
location ~* ^/web/static/ {
    proxy_pass http://odoo_backend;
    proxy_cache static_cache;
    proxy_cache_valid 200 1d;
    proxy_cache_use_stale error timeout;
    
    expires 1d;
    add_header Cache-Control "public";
    add_header X-Cache-Status $upstream_cache_status;
}
```

### 7.2 API Response Caching

```nginx
# API Cache Configuration
proxy_cache_path /var/cache/nginx/api 
    levels=1:2 
    keys_zone=api_cache:100m 
    max_size=5g 
    inactive=10m 
    use_temp_path=off;

# API Cache Purge (requires ngx_cache_purge module)
# location ~ /purge(/.*) {
#     allow 127.0.0.1;
#     allow 10.0.0.0/8;
#     deny all;
#     proxy_cache_purge api_cache $scheme$request_method$host$1;
# }

map $request_method $cache_key {
    default $scheme$request_method$host$request_uri;
    POST $scheme$request_method$host$request_uri$request_body;
}

server {
    location /api/v1/products {
        proxy_pass http://fastapi_backend;
        proxy_cache api_cache;
        
        # Cache GET requests for 5 minutes
        proxy_cache_valid 200 5m;
        proxy_cache_valid 404 1m;
        
        # Bypass cache for authenticated requests
        proxy_cache_bypass $http_authorization;
        proxy_no_cache $http_authorization;
        
        # Use stale cache on backend errors
        proxy_cache_use_stale error timeout http_500 http_502 http_503 http_504;
        
        # Lock concurrent requests for same resource
        proxy_cache_lock on;
        proxy_cache_lock_timeout 5s;
        
        add_header X-Cache-Status $upstream_cache_status;
    }
    
    # Don't cache user-specific endpoints
    location /api/v1/user {
        proxy_pass http://fastapi_backend;
        proxy_cache off;
    }
    
    # Don't cache cart/checkout
    location /api/v1/cart {
        proxy_pass http://fastapi_backend;
        proxy_cache off;
    }
}
```

### 7.3 Cache Invalidation Strategies

```nginx
# Cache bypass headers
map $http_cache_control $bypass_cache {
    default 0;
    "no-cache" 1;
    "no-store" 1;
    "max-age=0" 1;
}

# Bypass cache for specific query parameters
map $args $cache_bypass_qp {
    default 0;
    "~*nocache=1" 1;
    "~*refresh=1" 1;
}

server {
    location / {
        proxy_pass http://backend;
        proxy_cache api_cache;
        
        proxy_cache_bypass $bypass_cache $cache_bypass_qp;
        proxy_no_cache $bypass_cache $cache_bypass_qp;
    }
}
```

---

## 8. Security Headers

### 8.1 Security Headers Configuration

```nginx
# /etc/nginx/conf.d/security-headers.conf

# Add to server blocks or http context

# Strict Transport Security (HSTS)
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

# Content Security Policy
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https: blob:; connect-src 'self' https://api.smartdairybd.com wss://api.smartdairybd.com; frame-ancestors 'self'; base-uri 'self'; form-action 'self';" always;

# X-Frame-Options (Clickjacking protection)
add_header X-Frame-Options "SAMEORIGIN" always;

# X-Content-Type-Options (MIME sniffing protection)
add_header X-Content-Type-Options "nosniff" always;

# X-XSS-Protection (Legacy browser XSS protection)
add_header X-XSS-Protection "1; mode=block" always;

# Referrer Policy
add_header Referrer-Policy "strict-origin-when-cross-origin" always;

# Permissions Policy (Feature Policy)
add_header Permissions-Policy "geolocation=(self 'https://smartdairybd.com'), microphone=(), camera=(), payment=(self 'https://smartdairybd.com')" always;

# Remove server version information
server_tokens off;

# Hide upstream server headers
proxy_hide_header X-Powered-By;
proxy_hide_header Server;
```

### 8.2 Rate Limiting

```nginx
# /etc/nginx/conf.d/rate-limiting.conf

# Define rate limit zones
limit_req_zone $binary_remote_addr zone=general:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=login:10m rate=1r/s;
limit_req_zone $binary_remote_addr zone=api:10m rate=100r/m;
limit_req_zone $binary_remote_addr zone=webhook:10m rate=10r/m;

# Connection limiting
limit_conn_zone $binary_remote_addr zone=addr:10m;
limit_conn_zone $server_name zone=servers:10m;

server {
    # General rate limiting for all requests
    limit_req zone=general burst=20 nodelay;
    
    # Connection limiting per IP
    limit_conn addr 10;
    
    # Login endpoint - strict rate limiting
    location /web/login {
        limit_req zone=login burst=5 nodelay;
        proxy_pass http://odoo_backend;
    }
    
    # API endpoints
    location /api/ {
        limit_req zone=api burst=150 nodelay;
        proxy_pass http://fastapi_backend;
    }
    
    # Webhook endpoints
    location /webhook/ {
        limit_req zone=webhook burst=20 nodelay;
        proxy_pass http://fastapi_backend;
    }
    
    # Rate limit error page
    error_page 503 @rate_limit;
    location @rate_limit {
        return 429 '{"error": "Too Many Requests", "message": "Please slow down your requests"}';
        add_header Content-Type application/json;
    }
}
```

### 8.3 IP Whitelisting/Blacklisting

```nginx
# /etc/nginx/conf.d/ip-restrictions.conf

# Admin panel access restriction
geo $admin_allowed {
    default 0;
    10.0.0.0/8 1;      # Internal network
    172.16.0.0/12 1;   # Internal network
    192.168.0.0/16 1;  # Internal network
    # Add specific admin IPs
    203.112.x.x 1;     # Office IP
}

# Blocked IPs
geo $blocked_ip {
    default 0;
    1.2.3.4 1;
    5.6.7.8 1;
}

server {
    # Deny blocked IPs
    if ($blocked_ip) {
        return 403;
    }
    
    # Admin access control
    location /web/database {
        if ($admin_allowed = 0) {
            return 403;
        }
        proxy_pass http://odoo_backend;
    }
    
    # Prometheus metrics - internal only
    location /metrics {
        allow 10.0.0.0/8;
        allow 172.16.0.0/12;
        allow 127.0.0.1;
        deny all;
        proxy_pass http://backend;
    }
}
```

### 8.4 Request Size and Timeout Limits

```nginx
# Request limits
client_max_body_size 500M;          # Maximum upload size
client_body_buffer_size 16k;        # Buffer size for request body
client_header_buffer_size 4k;       # Buffer size for headers
large_client_header_buffers 4 8k;   # Buffers for large headers

# Timeout settings
client_body_timeout 60s;            # Read client body timeout
client_header_timeout 60s;          # Read client header timeout
keepalive_timeout 65s;              # Keep-alive timeout
send_timeout 60s;                   # Response send timeout

# Connection limits for slowloris protection
limit_conn_zone $binary_remote_addr zone=conn_limit:10m;
limit_conn conn_limit 50;
```

---

## 9. Performance Tuning

### 9.1 Gzip Compression

```nginx
# /etc/nginx/conf.d/gzip.conf

# Enable gzip compression
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_min_length 1000;
gzip_buffers 16 8k;

# Compressible MIME types
gzip_types
    application/atom+xml
    application/javascript
    application/json
    application/ld+json
    application/manifest+json
    application/rss+xml
    application/vnd.geo+json
    application/vnd.ms-fontobject
    application/x-font-ttf
    application/x-web-app-manifest+json
    application/xhtml+xml
    application/xml
    font/opentype
    image/bmp
    image/svg+xml
    image/x-icon
    text/cache-manifest
    text/css
    text/plain
    text/vcard
    text/vnd.rim.location.xloc
    text/vtt
    text/x-component
    text/x-cross-domain-policy;

# Disable gzip for IE6
gzip_disable "msie6";

# Pre-compressed files (nginx will serve .gz files if they exist)
gzip_static on;
```

### 9.2 Brotli Compression (with ngx_brotli module)

```nginx
# Brotli compression (if module is installed)
brotli on;
brotli_comp_level 6;
brotli_types
    application/atom+xml
    application/javascript
    application/json
    application/ld+json
    application/manifest+json
    application/rss+xml
    application/vnd.geo+json
    application/vnd.ms-fontobject
    application/x-font-ttf
    application/x-web-app-manifest+json
    application/xhtml+xml
    application/xml
    font/opentype
    image/bmp
    image/svg+xml
    image/x-icon
    text/cache-manifest
    text/css
    text/plain
    text/vcard
    text/vnd.rim.location.xloc
    text/vtt
    text/x-component
    text/x-cross-domain-policy;
```

### 9.3 Keepalive and Connection Tuning

```nginx
# /etc/nginx/conf.d/performance.conf

# Connection handling
use epoll;
worker_connections 16384;
multi_accept on;

# Keepalive settings
keepalive_timeout 65;
keepalive_requests 1000;
keepalive_disable msie6;

# Upstream keepalive
upstream backend {
    server backend1:8080;
    server backend2:8080;
    keepalive 64;
    keepalive_requests 100;
    keepalive_timeout 60s;
}

# File handling
sendfile on;
tcp_nopush on;
tcp_nodelay on;

# File cache (open file descriptors)
open_file_cache max=10000 inactive=30s;
open_file_cache_valid 60s;
open_file_cache_min_uses 2;
open_file_cache_errors on;
```

### 9.4 Buffer Optimization

```nginx
# Proxy buffer settings for high-traffic sites
proxy_buffering on;
proxy_buffer_size 4k;
proxy_buffers 8 4k;
proxy_busy_buffers_size 8k;
proxy_temp_file_write_size 64k;

# Request/response buffering
client_body_buffer_size 16k;
client_header_buffer_size 4k;
client_max_body_size 500m;
large_client_header_buffers 4 8k;

# FastCGI buffers (if using PHP-FPM)
fastcgi_buffering on;
fastcgi_buffer_size 4k;
fastcgi_buffers 8 4k;
fastcgi_busy_buffers_size 8k;
```

---

## 10. Logging Format

### 10.1 JSON Structured Logging for ELK Stack

```nginx
# /etc/nginx/conf.d/logging.conf

# JSON log format for ELK/EFK stack
log_format json_analytics escape=json '{'
    '"time":"$time_iso8601",'
    '"remote_addr":"$remote_addr",'
    '"remote_user":"$remote_user",'
    '"request":"$request",'
    '"status": "$status",'
    '"body_bytes_sent":"$body_bytes_sent",'
    '"request_time":"$request_time",'
    '"upstream_response_time":"$upstream_response_time",'
    '"upstream_connect_time":"$upstream_connect_time",'
    '"upstream_header_time":"$upstream_header_time",'
    '"http_referrer":"$http_referer",'
    '"http_user_agent":"$http_user_agent",'
    '"http_x_forwarded_for":"$http_x_forwarded_for",'
    '"http_host":"$http_host",'
    '"server_name":"$server_name",'
    '"request_method":"$request_method",'
    '"request_uri":"$request_uri",'
    '"uri":"$uri",'
    '"query_string":"$query_string",'
    '"protocol":"$server_protocol",'
    '"https":"$https",'
    '"gzip_ratio":"$gzip_ratio",'
    '"cache_status":"$upstream_cache_status",'
    '"connection":"$connection",'
    '"connection_requests":"$connection_requests",'
    '"request_id":"$request_id"'
'}';

# Alternative: Extended JSON format with more fields
log_format json_extended escape=json '{'
    '"@timestamp":"$time_iso8601",'
    '"@version":"1",'
    '"host":"$hostname",'
    '"short_message":"$request",'
    '"severity":"$status",'
    '"nginx": {'
        '"remote_addr":"$remote_addr",'
        '"remote_user":"$remote_user",'
        '"body_bytes_sent":$body_bytes_sent,'
        '"request_time":$request_time,'
        '"status":$status,'
        '"request":"$request",'
        '"request_method":"$request_method",'
        '"request_uri":"$request_uri",'
        '"uri":"$uri",'
        '"query_string":"$query_string",'
        '"http_referrer":"$http_referer",'
        '"http_user_agent":"$http_user_agent",'
        '"http_x_forwarded_for":"$http_x_forwarded_for",'
        '"http_host":"$host",'
        '"server_name":"$server_name",'
        '"server_port":"$server_port",'
        '"server_protocol":"$server_protocol",'
        '"ssl_protocol":"$ssl_protocol",'
        '"ssl_cipher":"$ssl_cipher",'
        '"scheme":"$scheme",'
        '"gzip_ratio":"$gzip_ratio",'
        '"http_cf_ray":"$http_cf_ray",'
        '"http_cf_connecting_ip":"$http_cf_connecting_ip",'
        '"upstream_addr":"$upstream_addr",'
        '"upstream_response_length":"$upstream_response_length",'
        '"upstream_response_time":"$upstream_response_time",'
        '"upstream_status":"$upstream_status",'
        '"cache_status":"$upstream_cache_status",'
        '"request_id":"$request_id",'
        '"connection":"$connection",'
        '"connection_requests":"$connection_requests"'
    '}'
'}';

# Generate unique request ID
map $http_x_request_id $request_id {
    default $http_x_request_id;
    "" $request_id_base;
}

# Request ID generation
split_clients "${msec}PID${pid}" $request_id_base {
    * ${msec}${pid};
}

# Apply log format
access_log /var/log/nginx/access.log json_extended;
error_log /var/log/nginx/error.log warn;
```

### 10.2 Separate Log Files by Type

```nginx
# Separate access logs for different purposes

# Main access log
access_log /var/log/nginx/access.log json_extended;

# Error log
error_log /var/log/nginx/error.log warn;

# API access log
map $uri $api_log {
    ~^/api/ 1;
    default 0;
}

access_log /var/log/nginx/api-access.log json_extended if=$api_log;

# Static asset log (optional, can be disabled in production)
map $uri $static_log {
    ~*\.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ 1;
    default 0;
}

access_log /var/log/nginx/static-access.log json_extended if=$static_log;

# Conditional logging - skip health checks
map $uri $loggable {
    /nginx-health 0;
    /health 0;
    default 1;
}

access_log /var/log/nginx/access.log json_extended if=$loggable;
```

### 10.3 Kubernetes Ingress Logging

```yaml
# ConfigMap for Nginx Ingress Controller logging
apiVersion: v1
kind: ConfigMap
metadata:
  name: ingress-nginx-controller
  namespace: ingress-nginx
data:
  # Enable custom log format
  log-format-escape-json: "true"
  log-format-upstream: >
    {
    "time": "$time_iso8601",
    "remote_addr": "$remote_addr",
    "x-forwarded-for": "$http_x_forwarded_for",
    "request_id": "$req_id",
    "remote_user": "$remote_user",
    "bytes_sent": $bytes_sent,
    "request_time": $request_time,
    "status": $status,
    "vhost": "$host",
    "request_proto": "$server_protocol",
    "path": "$uri",
    "request_query": "$args",
    "request_length": $request_length,
    "duration": $request_time,
    "method": "$request_method",
    "http_referrer": "$http_referer",
    "http_user_agent": "$http_user_agent",
    "upstream_addr": "$upstream_addr",
    "upstream_response_length": $upstream_response_length,
    "upstream_response_time": $upstream_response_time,
    "upstream_status": $upstream_status
    }
```

---

## 11. Troubleshooting

### 11.1 Common Errors and Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| `502 Bad Gateway` | Backend service down | Check upstream service health; verify network connectivity |
| `504 Gateway Timeout` | Backend slow/unresponsive | Increase proxy timeouts; check backend performance |
| `413 Request Entity Too Large` | File upload too large | Increase `client_max_body_size` |
| `404 Not Found` | Wrong path or missing file | Check location blocks; verify file existence |
| `403 Forbidden` | Permission/IP restriction | Check file permissions; review IP restrictions |
| `429 Too Many Requests` | Rate limit exceeded | Adjust rate limiting; implement backoff strategy |
| `499 Client Closed Request` | Client disconnected | Normal for long-running requests; ignore if infrequent |

### 11.2 Debugging Configuration

```nginx
# Debug configuration (enable temporarily only)
error_log /var/log/nginx/error.log debug;

# Debug-specific location
server {
    location /nginx-debug {
        # Show current configuration
        return 200 
            "Worker Processes: $worker_processes\n"
            "Connections per Worker: $worker_connections\n"
            "Server Name: $server_name\n"
            "Document Root: $document_root\n"
            "Request URI: $request_uri\n"
            "Upstream Addr: $upstream_addr\n"
            "Cache Status: $upstream_cache_status\n";
        add_header Content-Type text/plain;
    }
    
    # Echo headers (for debugging)
    location /echo {
        default_type text/plain;
        return 200 "$request_headers";
    }
}

# Stub status for monitoring
server {
    location /nginx_status {
        stub_status on;
        access_log off;
        allow 127.0.0.1;
        allow 10.0.0.0/8;
        deny all;
    }
}
```

### 11.3 Testing and Validation

```bash
# Test configuration syntax
nginx -t

# Test and reload if valid
nginx -t && nginx -s reload

# Check open files and connections
ss -tuln | grep nginx
lsof -i :80
lsof -i :443

# Check worker processes
ps aux | grep nginx

# Test upstream connectivity
curl -v http://localhost:8069/web/health

# Test with specific headers
curl -H "Host: erp.smartdairybd.com" http://localhost/

# Test SSL configuration
curl -vI https://erp.smartdairybd.com
openssl s_client -connect erp.smartdairybd.com:443 -servername erp.smartdairybd.com

# Test from specific IP (bypass CDN)
curl --resolve erp.smartdairybd.com:443:<IP> https://erp.smartdairybd.com
```

### 11.4 Log Analysis Commands

```bash
# Find most frequent IP addresses
awk '{print $1}' /var/log/nginx/access.log | sort | uniq -c | sort -rn | head -20

# Find slow requests (taking more than 5 seconds)
awk '($NF > 5) {print $0}' /var/log/nginx/access.log | tail -20

# Find 5xx errors
grep '"status": "5' /var/log/nginx/access.log | jq .

# Find 404 errors with referrer
grep '"status": "404' /var/log/nginx/access.log | jq '{status: .status, uri: .uri, referrer: .http_referrer}'

# Real-time log monitoring
tail -f /var/log/nginx/access.log | jq .

# Count requests per minute
awk -F: '{print $2":"$3}' /var/log/nginx/access.log | sort | uniq -c
```

### 11.5 Kubernetes Ingress Debugging

```bash
# Check Ingress Controller logs
kubectl logs -n ingress-nginx -l app.kubernetes.io/name=ingress-nginx --tail=100

# Check Ingress resources
kubectl get ingress -n smart-dairy
kubectl describe ingress smart-dairy-ingress -n smart-dairy

# Check backend services
kubectl get svc -n smart-dairy
kubectl get endpoints odoo-service -n smart-dairy

# Port forward for direct testing
kubectl port-forward svc/odoo-service 8069:8069 -n smart-dairy

# Test from inside the cluster
kubectl run -it --rm debug --image=curlimages/curl --restart=Never -- http://odoo-service:8069/web/health
```

---

## 12. Appendices

### Appendix A: Complete nginx.conf Example

```nginx
# /etc/nginx/nginx.conf
# Complete Nginx Configuration for Smart Dairy Ltd.
# Optimized for 10,000 concurrent users

#===================================
# User and Process Configuration
#===================================
user nginx;
worker_processes auto;
worker_rlimit_nofile 65535;
pid /var/run/nginx.pid;

#===================================
# Error Log Configuration
#===================================
error_log /var/log/nginx/error.log warn;

#===================================
# Events Module Configuration
#===================================
events {
    worker_connections 16384;
    use epoll;
    multi_accept on;
    accept_mutex on;
}

#===================================
# HTTP Module Configuration
#===================================
http {
    # Basic Settings
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    charset utf-8;
    
    # Performance Settings
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    
    # Connection Settings
    keepalive_timeout 65;
    keepalive_requests 1000;
    keepalive_disable msie6;
    
    # Reset timed out connections
    reset_timedout_connection on;
    
    # File Cache
    open_file_cache max=10000 inactive=30s;
    open_file_cache_valid 60s;
    open_file_cache_min_uses 2;
    open_file_cache_errors on;
    
    # Request Settings
    client_max_body_size 500m;
    client_body_buffer_size 16k;
    client_header_buffer_size 4k;
    large_client_header_buffers 4 8k;
    client_body_timeout 60s;
    client_header_timeout 60s;
    send_timeout 60s;
    
    # Server Tokens
    server_tokens off;
    
    # Request ID Generation
    map $http_x_request_id $request_id {
        default $http_x_request_id;
        "" $request_id_base;
    }
    
    split_clients "${msec}PID${pid}" $request_id_base {
        * ${msec}${pid};
    }
    
    #===================================
    # SSL/TLS Configuration
    #===================================
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;
    ssl_stapling on;
    ssl_stapling_verify on;
    resolver 8.8.8.8 8.8.4.4 valid=300s;
    resolver_timeout 5s;
    
    #===================================
    # Gzip Configuration
    #===================================
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_min_length 1000;
    gzip_buffers 16 8k;
    gzip_types
        application/atom+xml
        application/javascript
        application/json
        application/ld+json
        application/manifest+json
        application/rss+xml
        application/vnd.geo+json
        application/vnd.ms-fontobject
        application/x-font-ttf
        application/x-web-app-manifest+json
        application/xhtml+xml
        application/xml
        font/opentype
        image/bmp
        image/svg+xml
        image/x-icon
        text/cache-manifest
        text/css
        text/plain
        text/vcard
        text/vnd.rim.location.xloc
        text/vtt
        text/x-component
        text/x-cross-domain-policy;
    gzip_disable "msie6";
    gzip_static on;
    
    #===================================
    # Rate Limiting Zones
    #===================================
    limit_req_zone $binary_remote_addr zone=general:10m rate=10r/s;
    limit_req_zone $binary_remote_addr zone=login:10m rate=1r/s;
    limit_req_zone $binary_remote_addr zone=api:10m rate=100r/m;
    limit_req_zone $binary_remote_addr zone=webhook:10m rate=10r/m;
    limit_conn_zone $binary_remote_addr zone=addr:10m;
    limit_conn_zone $server_name zone=servers:10m;
    
    #===================================
    # Cache Paths
    #===================================
    proxy_cache_path /var/cache/nginx/static 
        levels=1:2 
        keys_zone=static_cache:100m 
        max_size=10g 
        inactive=60d 
        use_temp_path=off;
    
    proxy_cache_path /var/cache/nginx/api 
        levels=1:2 
        keys_zone=api_cache:50m 
        max_size=5g 
        inactive=5m 
        use_temp_path=off;
    
    #===================================
    # Log Format
    #===================================
    log_format json_extended escape=json '{'
        '"@timestamp":"$time_iso8601",'
        '"host":"$hostname",'
        '"remote_addr":"$remote_addr",'
        '"remote_user":"$remote_user",'
        '"body_bytes_sent":$body_bytes_sent,'
        '"request_time":$request_time,'
        '"status":$status,'
        '"request":"$request",'
        '"request_method":"$request_method",'
        '"request_uri":"$request_uri",'
        '"uri":"$uri",'
        '"http_referrer":"$http_referer",'
        '"http_user_agent":"$http_user_agent",'
        '"http_x_forwarded_for":"$http_x_forwarded_for",'
        '"http_host":"$host",'
        '"server_name":"$server_name",'
        '"scheme":"$scheme",'
        '"gzip_ratio":"$gzip_ratio",'
        '"upstream_addr":"$upstream_addr",'
        '"upstream_response_time":"$upstream_response_time",'
        '"upstream_status":"$upstream_status",'
        '"cache_status":"$upstream_cache_status",'
        '"request_id":"$request_id",'
        '"connection":"$connection",'
        '"connection_requests":"$connection_requests"'
    '}';
    
    # Conditional logging map
    map $uri $loggable {
        /nginx-health 0;
        /health 0;
        default 1;
    }
    
    access_log /var/log/nginx/access.log json_extended if=$loggable;
    error_log /var/log/nginx/error.log warn;
    
    #===================================
    # Include Additional Configurations
    #===================================
    include /etc/nginx/conf.d/*.conf;
    include /etc/nginx/sites-enabled/*;
}
```

### Appendix B: Complete Site Configuration (smartdairy.conf)

```nginx
# /etc/nginx/sites-available/smartdairy
# Main site configuration for Smart Dairy

#===================================
# Upstream Definitions
#===================================
upstream odoo_backend {
    least_conn;
    server odoo-1.smart-dairy.svc.cluster.local:8069 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-2.smart-dairy.svc.cluster.local:8069 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-3.smart-dairy.svc.cluster.local:8069 weight=5 max_fails=3 fail_timeout=30s;
    keepalive 32;
}

upstream odoo_im {
    least_conn;
    server odoo-1.smart-dairy.svc.cluster.local:8072 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-2.smart-dairy.svc.cluster.local:8072 weight=5 max_fails=3 fail_timeout=30s;
    server odoo-3.smart-dairy.svc.cluster.local:8072 weight=5 max_fails=3 fail_timeout=30s;
    keepalive 32;
}

upstream fastapi_backend {
    least_conn;
    server fastapi-1.smart-dairy.svc.cluster.local:8000 weight=5 max_fails=3 fail_timeout=30s;
    server fastapi-2.smart-dairy.svc.cluster.local:8000 weight=5 max_fails=3 fail_timeout=30s;
    server fastapi-3.smart-dairy.svc.cluster.local:8000 weight=5 max_fails=3 fail_timeout=30s;
    keepalive 64;
}

#===================================
# HTTP to HTTPS Redirect
#===================================
server {
    listen 80;
    listen [::]:80;
    server_name smartdairybd.com www.smartdairybd.com erp.smartdairybd.com api.smartdairybd.com shop.smartdairybd.com b2b.smartdairybd.com;
    return 301 https://$host$request_uri;
}

#===================================
# ERP Server Block
#===================================
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name erp.smartdairybd.com;
    
    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/smartdairybd.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smartdairybd.com/privkey.pem;
    ssl_trusted_certificate /etc/letsencrypt/live/smartdairybd.com/chain.pem;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(self), microphone=(), camera=(), payment=(self)" always;
    
    # Proxy Headers
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Port $server_port;
    proxy_set_header X-Request-ID $request_id;
    
    # Proxy Timeouts
    proxy_connect_timeout 600s;
    proxy_send_timeout 600s;
    proxy_read_timeout 600s;
    
    # Proxy Buffer Settings
    proxy_buffering on;
    proxy_buffer_size 4k;
    proxy_buffers 8 4k;
    proxy_busy_buffers_size 8k;
    
    # WebSocket Support
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    
    # Rate Limiting
    limit_req zone=general burst=20 nodelay;
    limit_conn addr 10;
    
    # Static Files
    location ~* /web/static/ {
        proxy_pass http://odoo_backend;
        proxy_cache static_cache;
        proxy_cache_valid 200 1d;
        proxy_cache_use_stale error timeout;
        expires 1d;
        add_header Cache-Control "public";
        add_header X-Cache-Status $upstream_cache_status;
    }
    
    # Long Polling
    location /longpolling {
        proxy_pass http://odoo_im;
        proxy_read_timeout 3600s;
        proxy_send_timeout 3600s;
    }
    
    # Database Manager - Restrict Access
    location /web/database {
        allow 10.0.0.0/8;
        allow 172.16.0.0/12;
        allow 192.168.0.0/16;
        deny all;
        proxy_pass http://odoo_backend;
    }
    
    # Main Application
    location / {
        proxy_pass http://odoo_backend;
        proxy_redirect off;
    }
    
    # Health Check
    location /nginx-health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }
    
    # Nginx Status
    location /nginx_status {
        stub_status on;
        access_log off;
        allow 10.0.0.0/8;
        allow 127.0.0.1;
        deny all;
    }
}

#===================================
# API Server Block
#===================================
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name api.smartdairybd.com;
    
    ssl_certificate /etc/letsencrypt/live/smartdairybd.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smartdairybd.com/privkey.pem;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "DENY" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    
    # CORS Headers
    add_header 'Access-Control-Allow-Origin' '*' always;
    add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;
    add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization' always;
    
    # Proxy Settings
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Request-ID $request_id;
    
    # Pre-flight requests
    if ($request_method = 'OPTIONS') {
        return 204;
    }
    
    # Rate Limiting
    limit_req zone=api burst=150 nodelay;
    limit_conn addr 20;
    
    # API Endpoints
    location /api/ {
        proxy_pass http://fastapi_backend;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
        
        # API Caching
        proxy_cache api_cache;
        proxy_cache_valid 200 5m;
        proxy_cache_use_stale error timeout updating http_500 http_502 http_503 http_504;
        proxy_cache_key "$scheme$request_method$host$request_uri";
        proxy_cache_lock on;
        add_header X-Cache-Status $upstream_cache_status;
    }
    
    # WebSocket Support
    location /ws/ {
        proxy_pass http://fastapi_backend;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_read_timeout 86400;
    }
    
    # Health Check
    location /health {
        access_log off;
        proxy_pass http://fastapi_backend/health;
    }
}

#===================================
# Main Website Server Block
#===================================
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name smartdairybd.com www.smartdairybd.com;
    
    ssl_certificate /etc/letsencrypt/live/smartdairybd.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smartdairybd.com/privkey.pem;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    
    # Rate Limiting
    limit_req zone=general burst=20 nodelay;
    
    # Static Files Location
    root /var/www/smartdairy;
    index index.html index.htm;
    
    # Cache Static Assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        add_header Vary Accept-Encoding;
        access_log off;
    }
    
    # Main Site
    location / {
        try_files $uri $uri/ /index.html;
    }
    
    # API Proxy
    location /api/ {
        proxy_pass http://fastapi_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
    
    # Health Check
    location /nginx-health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }
}
```

### Appendix C: Kubernetes Ingress Annotations Reference

```yaml
# Complete Kubernetes Ingress with all Nginx annotations
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: smart-dairy-complete
  namespace: smart-dairy
  annotations:
    # Ingress Class
    kubernetes.io/ingress.class: nginx
    
    # SSL/TLS
    cert-manager.io/cluster-issuer: letsencrypt-prod
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
    nginx.ingress.kubernetes.io/force-ssl-redirect: "true"
    nginx.ingress.kubernetes.io/ssl-passthrough: "false"
    
    # Rate Limiting
    nginx.ingress.kubernetes.io/limit-rps: "10"
    nginx.ingress.kubernetes.io/limit-connections: "10"
    nginx.ingress.kubernetes.io/limit-rpm: "100"
    
    # Proxy Settings
    nginx.ingress.kubernetes.io/proxy-body-size: "500m"
    nginx.ingress.kubernetes.io/proxy-connect-timeout: "600"
    nginx.ingress.kubernetes.io/proxy-send-timeout: "600"
    nginx.ingress.kubernetes.io/proxy-read-timeout: "600"
    nginx.ingress.kubernetes.io/proxy-buffering: "on"
    nginx.ingress.kubernetes.io/proxy-buffer-size: "4k"
    nginx.ingress.kubernetes.io/proxy-buffers: "8 4k"
    
    # CORS
    nginx.ingress.kubernetes.io/enable-cors: "true"
    nginx.ingress.kubernetes.io/cors-allow-headers: "DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization"
    nginx.ingress.kubernetes.io/cors-allow-methods: "GET, POST, PUT, DELETE, OPTIONS"
    nginx.ingress.kubernetes.io/cors-allow-origin: "*"
    
    # Session Affinity (Sticky Sessions)
    nginx.ingress.kubernetes.io/affinity: "cookie"
    nginx.ingress.kubernetes.io/session-cookie-name: "route"
    nginx.ingress.kubernetes.io/session-cookie-expires: "172800"
    nginx.ingress.kubernetes.io/session-cookie-max-age: "172800"
    
    # Rewrite
    nginx.ingress.kubernetes.io/rewrite-target: /
    nginx.ingress.kubernetes.io/use-regex: "true"
    
    # Authentication
    nginx.ingress.kubernetes.io/auth-type: basic
    nginx.ingress.kubernetes.io/auth-secret: basic-auth
    nginx.ingress.kubernetes.io/auth-realm: "Authentication Required"
    
    # Whitelist
    nginx.ingress.kubernetes.io/whitelist-source-range: "10.0.0.0/8,172.16.0.0/12,192.168.0.0/16"
    
    # Custom Headers
    nginx.ingress.kubernetes.io/configuration-snippet: |
      add_header X-Request-ID $req_id;
      add_header X-Cache-Status $upstream_cache_status;
    
    # ModSecurity (WAF)
    nginx.ingress.kubernetes.io/enable-modsecurity: "true"
    nginx.ingress.kubernetes.io/enable-owasp-core-rules: "true"
    nginx.ingress.kubernetes.io/modsecurity-snippet: |
      SecRuleEngine On
      SecDebugLog /dev/stdout
    
    # Monitoring
    prometheus.io/scrape: "true"
    prometheus.io/port: "10254"
spec:
  tls:
    - hosts:
        - smartdairybd.com
        - erp.smartdairybd.com
        - api.smartdairybd.com
      secretName: smartdairy-tls-secret
  rules:
    - host: erp.smartdairybd.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: odoo-service
                port:
                  number: 8069
    - host: api.smartdairybd.com
      http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: fastapi-service
                port:
                  number: 8000
```

### Appendix D: Environment-Specific Configurations

```nginx
# Development Environment (dev.nginx.conf)
# Reduced caching, verbose logging

error_log /var/log/nginx/error.log debug;
gzip off;
proxy_cache off;
add_header X-Debug-Environment "development" always;

# Staging Environment (staging.nginx.conf)
# Similar to production with test certificates

ssl_certificate /etc/ssl/certs/staging.crt;
ssl_certificate_key /etc/ssl/private/staging.key;
add_header X-Debug-Environment "staging" always;

# Production Environment (production.nginx.conf)
# Optimized for performance and security

error_log /var/log/nginx/error.log warn;
gzip on;
proxy_cache on;
server_tokens off;
add_header X-Debug-Environment "production" always;
```

### Appendix E: Monitoring and Alerting Rules

```yaml
# Prometheus rules for Nginx monitoring
groups:
  - name: nginx-alerts
    rules:
      - alert: NginxHighErrorRate
        expr: |
          (
            sum(rate(nginx_ingress_controller_requests{status=~"5.."}[5m]))
            /
            sum(rate(nginx_ingress_controller_requests[5m]))
          ) > 0.05
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "High Nginx error rate"
          description: "Error rate is above 5% for 5 minutes"
      
      - alert: NginxHighLatency
        expr: |
          histogram_quantile(0.99, 
            sum(rate(nginx_ingress_controller_request_duration_seconds_bucket[5m])) by (le)
          ) > 1
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "High Nginx latency"
          description: "99th percentile latency is above 1 second"
      
      - alert: NginxTooManyConnections
        expr: nginx_ingress_controller_nginx_process_connections{state="active"} > 10000
        for: 5m
        labels:
          severity: critical
        annotations:
          summary: "Nginx high connection count"
          description: "Active connections exceed 10000"
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | DevOps Engineer | _________________ | 2026-01-31 |
| Owner | DevOps Lead | _________________ | 2026-01-31 |
| Reviewer | CTO | _________________ | 2026-01-31 |

---

**Document Control Information**

| Field | Value |
|-------|-------|
| Document ID | D-010 |
| Version | 1.0 |
| Classification | Internal Use |
| Next Review Date | 2026-04-30 |
| Distribution List | DevOps Team, Development Team, Security Team |

---

*This document is proprietary to Smart Dairy Ltd. Unauthorized distribution is prohibited.*

*End of Document D-010*
