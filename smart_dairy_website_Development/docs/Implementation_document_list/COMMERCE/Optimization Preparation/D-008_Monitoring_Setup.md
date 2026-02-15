# Monitoring Setup Guide
**Document ID:** D-008  
**Project:** Smart Dairy Web Portal System  
**Version:** 1.0  
**Date:** January 31, 2026  
**Owner:** DevOps Engineer  
**Reviewer:** DevOps Lead  
**Status:** Draft  
**Due Date:** Week 34

---

## Document Control

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 31-Jan-2026 | DevOps Engineer | Initial Draft |
| 1.1 | TBD | DevOps Lead | Review Comments |
| 2.0 | TBD | DevOps Engineer | Final Version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Monitoring Architecture](#2-monitoring-architecture)
3. [Infrastructure Monitoring](#3-infrastructure-monitoring)
4. [Application Monitoring](#4-application-monitoring)
5. [Database Monitoring](#5-database-monitoring)
6. [Business Metrics Monitoring](#6-business-metrics-monitoring)
7. [Alert Configuration](#7-alert-configuration)
8. [Dashboard Setup](#8-dashboard-setup)
9. [Log Management](#9-log-management)
10. [Distributed Tracing](#10-distributed-tracing)
11. [Security Monitoring](#11-security-monitoring)
12. [Monitoring Automation](#12-monitoring-automation)
13. [Retention & Storage](#13-retention--storage)
14. [Maintenance & Updates](#14-maintenance--updates)
15. [Appendix](#15-appendix)

---

## 1. Introduction

### 1.1 Purpose

This Monitoring Setup Guide defines the comprehensive monitoring infrastructure for the Smart Dairy Web Portal System. It ensures visibility into all system components, enables proactive issue detection, and provides actionable insights for optimization.

### 1.2 Scope

This guide covers:
- Infrastructure monitoring (servers, containers, cloud resources)
- Application performance monitoring (APM)
- Database monitoring (PostgreSQL, Redis)
- Business metrics monitoring (orders, payments, conversions)
- Alert configuration and escalation
- Log aggregation and analysis
- Real-time dashboards for stakeholders
- Distributed tracing for request flow analysis
- Security monitoring and compliance

### 1.3 Monitoring Objectives

| Objective | Description | Priority |
|-----------|-------------|----------|
| **Availability** | Ensure 99.9% system uptime | Critical |
| **Performance** | Maintain <500ms API response times | Critical |
| **Capacity Planning** | Predict resource needs proactively | High |
| **Issue Detection** | Detect incidents within 1 minute | Critical |
| **Business Intelligence** | Provide real-time business insights | High |
| **Compliance** | Meet audit and security requirements | Medium |

---

## 2. Monitoring Architecture

### 2.1 Overall Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Smart Dairy Monitoring System                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                │
│  ┌────────────────────────────────────────────────────────┐    │
│  │                   Data Collection Layer                 │    │
│  ├────────────────────────────────────────────────────────┤    │
│  │                                                        │    │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐         │    │
│  │  │Prometheus │  │  Node.js  │  │   APM    │         │    │
│  │  │ Exporter  │  │  Agent   │  │  Agent   │         │    │
│  │  └─────┬────┘  └─────┬────┘  └─────┬────┘         │    │
│  │        │             │              │                │    │
│  │        └─────────────┴──────────────┘                │    │
│  │                      │                                │    │
│  └──────────────────────┼────────────────────────────────┘    │
│                         │                                     │
│                         ▼                                     │
│  ┌────────────────────────────────────────────────────────┐    │
│  │                   Processing Layer                    │    │
│  ├────────────────────────────────────────────────────────┤    │
│  │  ┌──────────────┐  ┌──────────────┐               │    │
│  │  │  Prometheus   │  │  Alertmanager│               │    │
│  │  │   Server      │  │              │               │    │
│  │  └──────┬───────┘  └──────┬───────┘               │    │
│  └─────────┼──────────────────┼───────────────────────┘    │
│            │                  │                             │
│            ▼                  ▼                             │
│  ┌────────────────────────────────────────────────────────┐    │
│  │                  Visualization Layer                  │    │
│  ├────────────────────────────────────────────────────────┤    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌─────────┐ │    │
│  │  │   Grafana    │  │   Kibana     │  │   Sentry │ │    │
│  │  │  Dashboards  │  │   Logs       │  │  Errors  │ │    │
│  │  └──────────────┘  └──────────────┘  └─────────┘ │    │
│  └────────────────────────────────────────────────────────┘    │
│                         │                                     │
│                         ▼                                     │
│  ┌────────────────────────────────────────────────────────┐    │
│  │                  Storage Layer                       │    │
│  ├────────────────────────────────────────────────────────┤    │
│  │  ┌──────────────┐  ┌──────────────┐               │    │
│  │  │   S3/Bucket  │  │  PostgreSQL  │               │    │
│  │  │   Metrics     │  │   Long-term  │               │    │
│  │  │   Archive     │  │   Storage    │               │    │
│  │  └──────────────┘  └──────────────┘               │    │
│  └────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Component Overview

#### 2.2.1 Data Collection Layer

| Component | Purpose | Technology |
|-----------|---------|-------------|
| **Node Exporter** | Server metrics (CPU, memory, disk, network) | Prometheus |
| **cAdvisor** | Container metrics | Prometheus |
| **Application Agent** | Application performance metrics | APM Agent |
| **PostgreSQL Exporter** | Database metrics | Prometheus |
| **Redis Exporter** | Redis cache metrics | Prometheus |
| **CloudWatch Exporter** | AWS cloud metrics | Prometheus |

#### 2.2.2 Processing Layer

| Component | Purpose | Technology |
|-----------|---------|-------------|
| **Prometheus** | Metrics collection, storage, querying | Open Source |
| **Alertmanager** | Alert routing, deduplication, grouping | Prometheus |
| **Loki** | Log aggregation and querying | Grafana Stack |

#### 2.2.3 Visualization Layer

| Component | Purpose | Technology |
|-----------|---------|-------------|
| **Grafana** | Real-time dashboards, alerting | Open Source |
| **Kibana** | Log visualization and analysis | ELK Stack |
| **Sentry** | Error tracking and alerting | Commercial |

#### 2.2.4 Storage Layer

| Component | Purpose | Technology |
|-----------|---------|-------------|
| **S3** | Long-term metrics archive | AWS |
| **PostgreSQL** | Metrics long-term storage | Open Source |
| **Elasticsearch** | Log storage | ELK Stack |

---

## 3. Infrastructure Monitoring

### 3.1 Server Monitoring

#### 3.1.1 Node Exporter Installation

**Prerequisites:**
- Ubuntu 20.04+ or Amazon Linux 2
- SSH access to servers
- User with sudo privileges

**Installation Steps:**

```bash
# Download Node Exporter
wget https://github.com/prometheus/node_exporter/releases/download/v1.7.0/node_exporter-1.7.0.linux-amd64.tar.gz

# Extract
tar xvfz node_exporter-1.7.0.linux-amd64.tar.gz
cd node_exporter-1.7.0.linux-amd64

# Move to /usr/local/bin
sudo cp node_exporter /usr/local/bin/

# Create user
sudo useradd --no-create-home --shell /bin/false node_exporter

# Create systemd service
sudo tee /etc/systemd/system/node_exporter.service > /dev/null <<EOF
[Unit]
Description=Node Exporter
After=network.target

[Service]
User=node_exporter
Group=node_exporter
Type=simple
ExecStart=/usr/local/bin/node_exporter \
    --collector.filesystem.mount-points-exclude=^/(sys|proc|dev|host|etc)($|/)*

[Install]
WantedBy=multi-user.target
EOF

# Enable and start service
sudo systemctl daemon-reload
sudo systemctl enable node_exporter
sudo systemctl start node_exporter

# Verify
curl http://localhost:9100/metrics | head
```

#### 3.1.2 Prometheus Configuration

**Add scrape config to prometheus.yml:**

```yaml
global:
  scrape_interval: 15s
  evaluation_interval: 15s
  external_labels:
    cluster: 'smart-dairy-production'
    environment: 'production'

# Alertmanager configuration
alerting:
  alertmanagers:
    - static_configs:
        - targets: ['alertmanager:9093']

# Rule files
rule_files:
  - '/etc/prometheus/rules/*.yml'

# Scrape configurations
scrape_configs:
  # Node Exporter - Application Servers
  - job_name: 'node-exporter'
    static_configs:
      - targets: 
        - 'web-01.smart-dairy.com.bd:9100'
        - 'web-02.smart-dairy.com.bd:9100'
        - 'web-03.smart-dairy.com.bd:9100'
        - 'app-01.smart-dairy.com.bd:9100'
        - 'app-02.smart-dairy.com.bd:9100'
    relabel_configs:
      - source_labels: [__address__]
        target_label: instance
        regex: '(.*):9100'
        replacement: '${1}'

  # Node Exporter - Database Servers
  - job_name: 'postgres-node'
    static_configs:
      - targets:
        - 'postgres-01.smart-dairy.com.bd:9100'
        - 'postgres-02.smart-dairy.com.bd:9100'
        - 'postgres-03.smart-dairy.com.bd:9100'
  
  # Node Exporter - Redis Server
  - job_name: 'redis-node'
    static_configs:
      - targets:
        - 'redis-01.smart-dairy.com.bd:9100'
```

#### 3.1.3 Key Infrastructure Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **CPU Utilization** | CPU usage percentage | Warning: 70%, Critical: 90% |
| **Memory Utilization** | RAM usage percentage | Warning: 75%, Critical: 90% |
| **Disk Usage** | Storage space usage | Warning: 80%, Critical: 90% |
| **Disk I/O** | Read/write operations per second | Warning: >5000 IOPS |
| **Network Traffic** | Inbound/outbound bandwidth | Warning: >800 Mbps |
| **Load Average** | System load over time | Warning: >10, Critical: >20 |
| **Swap Usage** | Swap space utilization | Warning: >50%, Critical: >80% |
| **File Descriptors** | Open file descriptors | Warning: >80% of limit |

### 3.2 Container Monitoring

#### 3.2.1 cAdvisor Setup

**Docker Compose Configuration:**

```yaml
version: '3.8'

services:
  cadvisor:
    image: gcr.io/cadvisor/cadvisor:v0.47.0
    container_name: cadvisor
    privileged: true
    ports:
      - "8080:8080"
    volumes:
      - /:/rootfs:ro
      - /var/run:/var/run:ro
      - /sys:/sys:ro
      - /var/lib/docker:/var/lib/docker:ro
      - /dev/disk/:/dev/disk:ro
    devices:
      - /dev/kmsg
    restart: unless-stopped
```

**Prometheus Configuration:**

```yaml
scrape_configs:
  - job_name: 'cadvisor'
    static_configs:
      - targets: ['cadvisor:8080']
    metrics_path: '/metrics'
    scrape_interval: 10s
```

#### 3.2.2 Container Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **Container CPU** | CPU usage per container | Warning: 80%, Critical: 95% |
| **Container Memory** | Memory usage per container | Warning: 80%, Critical: 95% |
| **Container Network** | Network I/O per container | Warning: >100 Mbps |
| **Container Uptime** | Container running time | Alert: <1 minute |
| **Container Restarts** | Restart count | Warning: >3 in 1 hour |

### 3.3 Kubernetes Monitoring

#### 3.3.1 Kubernetes Metrics Server

**Installation:**

```bash
# Install metrics-server
kubectl apply -f https://github.com/kubernetes-sigs/metrics-server/releases/latest/download/components.yaml

# Verify
kubectl top nodes
kubectl top pods -n smart-dairy
```

#### 3.3.2 Prometheus Operator Setup

**Installation:**

```bash
# Add Helm repository
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo update

# Install Prometheus Operator
helm install prometheus prometheus-community/kube-prometheus-stack \
  --namespace monitoring \
  --create-namespace \
  --set prometheus.prometheusSpec.serviceMonitorSelectorNilUsesHelmValues=false \
  --set grafana.adminPassword=admin123
```

**Custom Resource Example - ServiceMonitor:**

```yaml
apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: smart-dairy-api
  namespace: smart-dairy
  labels:
    app: smart-dairy-api
spec:
  selector:
    matchLabels:
      app: smart-dairy-api
  endpoints:
    - port: metrics
      interval: 15s
      path: /metrics
```

#### 3.3.3 Kubernetes Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **Pod CPU Usage** | CPU usage per pod | Warning: 80%, Critical: 95% |
| **Pod Memory Usage** | Memory usage per pod | Warning: 80%, Critical: 95% |
| **Node Capacity** | Available resources on nodes | Warning: <20% available |
| **Pod Restarts** | Pod restart count | Warning: >3 in 1 hour |
| **Pod Pending** | Pods stuck in pending state | Critical: Any |
| **Node NotReady** | Nodes not ready | Critical: Any |

---

## 4. Application Monitoring

### 4.1 APM Instrumentation

#### 4.1.1 New Relic Setup

**Installation:**

```bash
# Install New Relic agent
npm install newrelic

# Initialize in application
const newrelic = require('newrelic');
newrelic.initialize({
  app_name: ['Smart Dairy Production'],
  license_key: process.env.NEW_RELIC_LICENSE_KEY,
  logging: {
    level: 'info'
  }
});
```

**Configuration (newrelic.js):**

```javascript
exports.config = {
  app_name: ['Smart Dairy Production'],
  license_key: 'NR_LICENSE_KEY',
  logging: {
    level: 'info'
  },
  application_logging: {
    enabled: true,
    forwarding: {
      enabled: true
    }
  },
  distributed_tracing: {
    enabled: true
  },
  browser_monitoring: {
    enabled: true
  }
};
```

#### 4.1.2 Datadog Setup

**Installation:**

```bash
# Install Datadog tracer
npm install dd-trace

# Initialize in application
const tracer = require('dd-trace').init({
  service: 'smart-dairy-api',
  env: 'production',
  logInjection: true,
  analytics: true
});
```

**Dockerfile Integration:**

```dockerfile
FROM node:18-alpine

# Install Datadog Agent
RUN apk add --no-cache curl && \
    curl -L https://github.com/DataDog/datadog-agent/releases/download/v7.45.0/datadog-agent-7.45.0.apk -o /tmp/datadog-agent.apk && \
    apk add --allow-untrusted /tmp/datadog-agent.apk

ENV DD_SERVICE=smart-dairy-api
ENV DD_ENV=production
ENV DD_SITE=datadoghq.com
ENV DD_API_KEY=YOUR_API_KEY

COPY . /app
WORKDIR /app

RUN npm ci --only=production

CMD ["node", "server.js"]
```

### 4.2 Application Metrics

#### 4.2.1 Custom Metrics Collection

**Prometheus Client for Node.js:**

```javascript
const promClient = require('prom-client');

// Create metrics registry
const register = new promClient.Registry();

// HTTP request duration
const httpRequestDuration = new promClient.Histogram({
  name: 'http_request_duration_seconds',
  help: 'Duration of HTTP requests in seconds',
  labelNames: ['method', 'route', 'status_code'],
  buckets: [0.1, 0.5, 1, 2, 5, 10],
  registers: [register]
});

// HTTP requests total
const httpRequestsTotal = new promClient.Counter({
  name: 'http_requests_total',
  help: 'Total number of HTTP requests',
  labelNames: ['method', 'route', 'status_code'],
  registers: [register]
});

// Active users gauge
const activeUsersGauge = new promClient.Gauge({
  name: 'active_users',
  help: 'Number of currently active users',
  registers: [register]
});

// Business metrics
const ordersCreated = new promClient.Counter({
  name: 'orders_created_total',
  help: 'Total number of orders created',
  labelNames: ['order_type', 'payment_method'],
  registers: [register]
});

const paymentTransactions = new promClient.Counter({
  name: 'payment_transactions_total',
  help: 'Total number of payment transactions',
  labelNames: ['gateway', 'status'],
  registers: [register]
});

// Middleware for request tracking
const metricsMiddleware = (req, res, next) => {
  const start = Date.now();
  
  res.on('finish', () => {
    const duration = Date.now() - start;
    const durationInSeconds = duration / 1000;
    
    httpRequestDuration
      .labels(req.method, req.route?.path || req.path, res.statusCode)
      .observe(durationInSeconds);
    
    httpRequestsTotal
      .labels(req.method, req.route?.path || req.path, res.statusCode)
      .inc();
  });
  
  next();
};

// Export metrics endpoint
app.get('/metrics', async (req, res) => {
  res.set('Content-Type', register.contentType);
  res.end(await register.metrics());
});

module.exports = {
  register,
  httpRequestDuration,
  httpRequestsTotal,
  activeUsersGauge,
  ordersCreated,
  paymentTransactions,
  metricsMiddleware
};
```

#### 4.2.2 Key Application Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **Request Duration** | API response time | Warning: p95>500ms, Critical: p95>2000ms |
| **Request Rate** | Requests per second | Warning: <50, Critical: <10 |
| **Error Rate** | Percentage of failed requests | Warning: >1%, Critical: >5% |
| **Active Users** | Concurrent active users | Warning: <1000 |
| **Queue Length** | Pending requests in queue | Warning: >100, Critical: >500 |
| **Session Duration** | Average user session time | Info only |
| **Conversion Rate** | Orders per session | Info only |

### 4.3 Distributed Tracing

#### 4.3.1 OpenTelemetry Setup

**Installation:**

```bash
npm install @opentelemetry/api @opentelemetry/sdk-node \
  @opentelemetry/auto-instrumentations-node \
  @opentelemetry/exporter-trace-otlp-grpc
```

**Configuration:**

```javascript
const { NodeSDK } = require('@opentelemetry/sdk-node');
const { Resource } = require('@opentelemetry/resources');
const { SemanticResourceAttributes } = require('@opentelemetry/semantic-conventions');
const { OTLPTraceExporter } = require('@opentelemetry/exporter-trace-otlp-grpc');
const { getNodeAutoInstrumentations } = require('@opentelemetry/auto-instrumentations-node');

const sdk = new NodeSDK({
  resource: new Resource({
    [SemanticResourceAttributes.SERVICE_NAME]: 'smart-dairy-api',
    [SemanticResourceAttributes.SERVICE_VERSION]: '1.0.0',
    [SemanticResourceAttributes.DEPLOYMENT_ENVIRONMENT]: 'production',
  }),
  traceExporter: new OTLPTraceExporter({
    url: 'http://jaeger:4317',
  }),
  instrumentations: [getNodeAutoInstrumentations()],
});

sdk.start();

// Graceful shutdown
process.on('SIGTERM', () => {
  sdk.shutdown()
    .then(() => console.log('Tracing terminated'))
    .catch((error) => console.error('Error terminating tracing', error))
    .finally(() => process.exit(0));
});
```

#### 4.3.2 Custom Spans

```javascript
const tracer = require('@opentelemetry/api').trace.getTracer('smart-dairy');

async function processOrder(orderData) {
  const span = tracer.startSpan('processOrder', {
    attributes: {
      'order.id': orderData.id,
      'order.type': orderData.type,
      'order.value': orderData.total
    }
  });

  try {
    // Validate order
    const validateSpan = tracer.startSpan('validateOrder', { parent: span });
    await validateOrder(orderData);
    validateSpan.end();

    // Check inventory
    const inventorySpan = tracer.startSpan('checkInventory', { parent: span });
    await checkInventory(orderData.items);
    inventorySpan.end();

    // Process payment
    const paymentSpan = tracer.startSpan('processPayment', { parent: span });
    await processPayment(orderData.payment);
    paymentSpan.setAttributes({
      'payment.gateway': orderData.payment.gateway,
      'payment.amount': orderData.payment.amount
    });
    paymentSpan.end();

    // Create order
    const createSpan = tracer.startSpan('createOrder', { parent: span });
    const order = await createOrder(orderData);
    createSpan.end();

    span.setStatus({ code: SpanStatusCode.OK });
    return order;
  } catch (error) {
    span.recordException(error);
    span.setStatus({ code: SpanStatusCode.ERROR, message: error.message });
    throw error;
  } finally {
    span.end();
  }
}
```

---

## 5. Database Monitoring

### 5.1 PostgreSQL Monitoring

#### 5.1.1 PostgreSQL Exporter Setup

**Installation:**

```bash
# Download and install postgres_exporter
wget https://github.com/prometheus-community/postgres_exporter/releases/download/v0.11.1/postgres_exporter-0.11.1.linux-amd64.tar.gz
tar xvfz postgres_exporter-0.11.1.linux-amd64.tar.gz
sudo cp postgres_exporter-0.11.1.linux-amd64/postgres_exporter /usr/local/bin/

# Create configuration file
cat > /etc/postgres_exporter.yml <<EOF
datasources:
  - name: smart_dairy
    host: postgres-01.smart-dairy.com.bd
    port: 5432
    user: postgres_exporter
    password: 'EXPORTER_PASSWORD'
    database: smart_dairy
    sslmode: disable

metrics:
  pg_stat_database:
    databases:
      - smart_dairy
  pg_stat_statements:
    databases:
      - smart_dairy
  pg_settings:
  pg_locks:
EOF

# Create systemd service
cat > /etc/systemd/system/postgres_exporter.service <<EOF
[Unit]
Description=PostgreSQL Exporter
After=network.target

[Service]
User=postgres_exporter
Group=postgres_exporter
Type=simple
ExecStart=/usr/local/bin/postgres_exporter --config.file=/etc/postgres_exporter.yml

[Install]
WantedBy=multi-user.target
EOF

# Enable and start
sudo systemctl daemon-reload
sudo systemctl enable postgres_exporter
sudo systemctl start postgres_exporter
```

**Prometheus Configuration:**

```yaml
scrape_configs:
  - job_name: 'postgres'
    static_configs:
      - targets:
        - 'postgres-01.smart-dairy.com.bd:9187'
        - 'postgres-02.smart-dairy.com.bd:9187'
        - 'postgres-03.smart-dairy.com.bd:9187'
```

#### 5.1.2 Key PostgreSQL Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **Active Connections** | Currently active database connections | Warning: >400, Critical: >450 |
| **Connection Usage** | Percentage of max connections used | Warning: 80%, Critical: 90% |
| **Query Duration** | Average query execution time | Warning: >100ms, Critical: >500ms |
| **Slow Queries** | Queries taking >1 second | Warning: >10/min |
| **Cache Hit Ratio** | Buffer cache hit percentage | Warning: <95%, Critical: <90% |
| **Database Size** | Total database size | Warning: >800GB |
| **Transaction Rate** | Transactions per second | Info only |
| **Lock Wait Time** | Time spent waiting for locks | Warning: >100ms, Critical: >1000ms |
| **Replication Lag** | Replication delay in seconds | Warning: >5s, Critical: >30s |

#### 5.1.3 PostgreSQL Query Monitoring

**Enable pg_stat_statements:**

```sql
-- Enable extension
CREATE EXTENSION IF NOT EXISTS pg_stat_statements;

-- Configure settings
ALTER SYSTEM SET shared_preload_libraries = 'pg_stat_statements';
ALTER SYSTEM SET pg_stat_statements.track = all;
ALTER SYSTEM SET pg_stat_statements.max = 10000;

-- Reload configuration
SELECT pg_reload_conf();

-- Verify extension is loaded
SELECT * FROM pg_extension WHERE extname = 'pg_stat_statements';
```

**Monitor slow queries:**

```sql
-- Top 10 slowest queries
SELECT 
  query,
  calls,
  total_exec_time,
  mean_exec_time,
  stddev_exec_time,
  max_exec_time
FROM pg_stat_statements
ORDER BY mean_exec_time DESC
LIMIT 10;

-- Most frequent queries
SELECT 
  query,
  calls,
  total_exec_time,
  mean_exec_time
FROM pg_stat_statements
ORDER BY calls DESC
LIMIT 10;

-- Queries with highest total execution time
SELECT 
  query,
  calls,
  total_exec_time,
  mean_exec_time
FROM pg_stat_statements
ORDER BY total_exec_time DESC
LIMIT 10;
```

### 5.2 Redis Monitoring

#### 5.2.1 Redis Exporter Setup

**Installation:**

```bash
# Download redis_exporter
wget https://github.com/oliver006/redis_exporter/releases/download/v1.54.0/redis_exporter-v1.54.0.linux-amd64.tar.gz
tar xvfz redis_exporter-v1.54.0.linux-amd64.tar.gz
sudo cp redis_exporter-v1.54.0.linux-amd64/redis_exporter /usr/local/bin/

# Create systemd service
cat > /etc/systemd/system/redis_exporter.service <<EOF
[Unit]
Description=Redis Exporter
After=network.target

[Service]
User=redis_exporter
Group=redis_exporter
Type=simple
ExecStart=/usr/local/bin/redis_exporter \
    -redis.addr=redis://localhost:6379 \
    -redis.password='REDIS_PASSWORD'

[Install]
WantedBy=multi-user.target
EOF

# Enable and start
sudo systemctl daemon-reload
sudo systemctl enable redis_exporter
sudo systemctl start redis_exporter
```

**Prometheus Configuration:**

```yaml
scrape_configs:
  - job_name: 'redis'
    static_configs:
      - targets:
        - 'redis-01.smart-dairy.com.bd:9121'
```

#### 5.2.2 Key Redis Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **Memory Usage** | Redis memory used | Warning: >13GB, Critical: >15GB |
| **Memory Fragmentation** | Memory fragmentation ratio | Warning: >1.5 |
| **Keyspace Hits/Misses** | Cache hit ratio | Warning: <80%, Critical: <70% |
| **Evicted Keys** | Keys evicted due to memory | Warning: >100/min |
| **Connected Clients** | Number of connected clients | Warning: >5000 |
| **Commands/sec** | Commands processed per second | Info only |
| **Expired Keys** | Keys expired per second | Info only |
| **Persistence** | RDB/AOF status | Critical: Any failure |

---

## 6. Business Metrics Monitoring

### 6.1 Business Metrics Collection

#### 6.1.1 Metrics Pipeline

**Custom Metrics Collector:**

```javascript
const promClient = require('prom-client');

// Business metrics registry
const businessRegistry = new promClient.Registry();

// Orders metrics
const ordersTotal = new promClient.Counter({
  name: 'smart_dairy_orders_total',
  help: 'Total number of orders',
  labelNames: ['order_type', 'payment_method', 'status'],
  registers: [businessRegistry]
});

const orderValue = new promClient.Histogram({
  name: 'smart_dairy_order_value_bdt',
  help: 'Order value in BDT',
  labelNames: ['order_type'],
  buckets: [100, 500, 1000, 5000, 10000, 50000, 100000],
  registers: [businessRegistry]
});

// User metrics
const usersRegistered = new promClient.Counter({
  name: 'smart_dairy_users_registered_total',
  help: 'Total number of registered users',
  labelNames: ['user_type'],
  registers: [businessRegistry]
});

const activeUsers = new promClient.Gauge({
  name: 'smart_dairy_active_users',
  help: 'Number of active users',
  labelNames: ['user_type', 'time_window'],
  registers: [businessRegistry]
});

// Payment metrics
const paymentsTotal = new promClient.Counter({
  name: 'smart_dairy_payments_total',
  help: 'Total number of payment transactions',
  labelNames: ['gateway', 'status', 'amount_range'],
  registers: [businessRegistry]
});

const paymentValue = new promClient.Histogram({
  name: 'smart_dairy_payment_value_bdt',
  help: 'Payment value in BDT',
  labelNames: ['gateway', 'status'],
  buckets: [100, 500, 1000, 5000, 10000, 50000],
  registers: [businessRegistry]
});

// Product metrics
const productViews = new promClient.Counter({
  name: 'smart_dairy_product_views_total',
  help: 'Total number of product views',
  labelNames: ['product_category', 'product_id'],
  registers: [businessRegistry]
});

const addToCart = new promClient.Counter({
  name: 'smart_dairy_add_to_cart_total',
  help: 'Total number of add to cart events',
  labelNames: ['product_category'],
  registers: [businessRegistry]
});

// Conversion funnel metrics
const conversionFunnel = new promClient.Gauge({
  name: 'smart_dairy_conversion_funnel',
  help: 'Users at each stage of conversion funnel',
  labelNames: ['stage'],
  registers: [businessRegistry]
});

// Export metrics
app.get('/business-metrics', async (req, res) => {
  res.set('Content-Type', businessRegistry.contentType);
  res.end(await businessRegistry.metrics());
});

module.exports = {
  ordersTotal,
  orderValue,
  usersRegistered,
  activeUsers,
  paymentsTotal,
  paymentValue,
  productViews,
  addToCart,
  conversionFunnel
};
```

#### 6.1.2 Business Metric Examples

**Order Creation:**

```javascript
// When order is created
ordersTotal
  .labels(
    order.type, // 'b2b' or 'b2c'
    order.payment.gateway, // 'bkash', 'nagad', 'sslcommerz'
    'created'
  )
  .inc();

orderValue
  .labels(order.type)
  .observe(order.total);
```

**Payment Processing:**

```javascript
// When payment is processed
paymentsTotal
  .labels(
    payment.gateway,
    payment.status, // 'success', 'failed', 'pending'
    getAmountRange(payment.amount)
  )
  .inc();

if (payment.status === 'success') {
  paymentValue
    .labels(payment.gateway, 'success')
    .observe(payment.amount);
}
```

**Conversion Funnel Tracking:**

```javascript
// Track users through funnel
conversionFunnel.labels('homepage').inc();
conversionFunnel.labels('product_view').inc();
conversionFunnel.labels('add_to_cart').inc();
conversionFunnel.labels('checkout').inc();
conversionFunnel.labels('payment').inc();
conversionFunnel.labels('order_complete').inc();
```

### 6.2 Key Business Metrics

| Metric | Description | Alert Threshold |
|---------|-------------|-----------------|
| **Orders per Minute** | Order creation rate | Warning: <10/min |
| **Order Value Average** | Average order value | Info only |
| **Payment Success Rate** | Successful payment percentage | Warning: <98%, Critical: <95% |
| **Conversion Rate** | Orders / sessions | Warning: <2% |
| **Cart Abandonment** | Abandoned carts / started carts | Warning: >70% |
| **Active Users (24h)** | Users active in last 24 hours | Warning: <1000 |
| **New Registrations** | New user registrations per day | Warning: <50/day |
| **Product Views** | Product views per hour | Info only |
| **Revenue per Hour** | Revenue generated per hour | Info only |

### 6.3 Business Dashboards

#### 6.3.1 Executive Dashboard

**Grafana Panel Configuration:**

```json
{
  "title": "Executive Overview",
  "panels": [
    {
      "title": "Total Revenue (24h)",
      "type": "stat",
      "targets": [
        {
          "expr": "sum(increase(smart_dairy_order_value_bdt_sum[24h]))"
        }
      ]
    },
    {
      "title": "Orders (24h)",
      "type": "stat",
      "targets": [
        {
          "expr": "sum(increase(smart_dairy_orders_total{status='created'}[24h]))"
        }
      ]
    },
    {
      "title": "Active Users (24h)",
      "type": "stat",
      "targets": [
        {
          "expr": "smart_dairy_active_users{time_window='24h'}"
        }
      ]
    },
    {
      "title": "Payment Success Rate",
      "type": "stat",
      "targets": [
        {
          "expr": "sum(increase(smart_dairy_payments_total{status='success'}[1h])) / sum(increase(smart_dairy_payments_total[1h])) * 100"
        }
      ],
      "fieldConfig": {
        "unit": "percent"
      }
    },
    {
      "title": "Revenue Trend (7 days)",
      "type": "timeseries",
      "targets": [
        {
          "expr": "sum(rate(smart_dairy_order_value_bdt_sum[5m])) by (order_type)"
        }
      ]
    },
    {
      "title": "Orders by Payment Method",
      "type": "piechart",
      "targets": [
        {
          "expr": "sum(increase(smart_dairy_payments_total[24h])) by (gateway)"
        }
      ]
    }
  ]
}
```

#### 6.3.2 Sales Dashboard

```json
{
  "title": "Sales Analytics",
  "panels": [
    {
      "title": "Orders by Type",
      "type": "timeseries",
      "targets": [
        {
          "expr": "sum(rate(smart_dairy_orders_total[5m])) by (order_type)"
        }
      ]
    },
    {
      "title": "Revenue by Type",
      "type": "timeseries",
      "targets": [
        {
          "expr": "sum(rate(smart_dairy_order_value_bdt_sum[5m])) by (order_type)"
        }
      ]
    },
    {
      "title": "Top Selling Products",
      "type": "table",
      "targets": [
        {
          "expr": "topk(10, sum(increase(smart_dairy_product_views_total[24h])) by (product_id))"
        }
      ]
    },
    {
      "title": "Conversion Funnel",
      "type": "bargauge",
      "targets": [
        {
          "expr": "smart_dairy_conversion_funnel"
        }
      ]
    }
  ]
}
```

---

## 7. Alert Configuration

### 7.1 Alert Rules

#### 7.1.1 Infrastructure Alerts

**File: /etc/prometheus/rules/infrastructure.yml**

```yaml
groups:
  - name: infrastructure_alerts
    interval: 30s
    rules:
      # High CPU usage
      - alert: HighCPUUsage
        expr: 100 - (avg by(instance) (rate(node_cpu_seconds_total{mode="idle"}[5m])) * 100) > 90
        for: 5m
        labels:
          severity: critical
          team: devops
        annotations:
          summary: "High CPU usage on {{ $labels.instance }}"
          description: "CPU usage is {{ $value }}% on {{ $labels.instance }}"
      
      # High memory usage
      - alert: HighMemoryUsage
        expr: (1 - (node_memory_MemAvailable_bytes / node_memory_MemTotal_bytes)) * 100 > 90
        for: 5m
        labels:
          severity: critical
          team: devops
        annotations:
          summary: "High memory usage on {{ $labels.instance }}"
          description: "Memory usage is {{ $value }}% on {{ $labels.instance }}"
      
      # Disk space low
      - alert: DiskSpaceLow
        expr: (node_filesystem_avail_bytes / node_filesystem_size_bytes) * 100 < 10
        for: 5m
        labels:
          severity: critical
          team: devops
        annotations:
          summary: "Disk space low on {{ $labels.instance }}"
          description: "Disk {{ $labels.mountpoint }} has only {{ $value }}% available"
      
      # High disk I/O
      - alert: HighDiskIO
        expr: rate(node_disk_io_time_seconds_total[5m]) > 0.8
        for: 5m
        labels:
          severity: warning
          team: devops
        annotations:
          summary: "High disk I/O on {{ $labels.instance }}"
          description: "Disk I/O utilization is {{ $value }}%"
      
      # Node down
      - alert: NodeDown
        expr: up{job="node-exporter"} == 0
        for: 1m
        labels:
          severity: critical
          team: devops
        annotations:
          summary: "Node {{ $labels.instance }} is down"
          description: "Node {{ $labels.instance }} has been down for more than 1 minute"
```

#### 7.1.2 Application Alerts

**File: /etc/prometheus/rules/application.yml**

```yaml
groups:
  - name: application_alerts
    interval: 30s
    rules:
      # High error rate
      - alert: HighErrorRate
        expr: sum(rate(http_requests_total{status=~"5.."}[5m])) by (service) / sum(rate(http_requests_total[5m])) by (service) * 100 > 5
        for: 5m
        labels:
          severity: critical
          team: backend
        annotations:
          summary: "High error rate for {{ $labels.service }}"
          description: "Error rate is {{ $value }}% for {{ $labels.service }}"
      
      # High response time
      - alert: HighResponseTime
        expr: histogram_quantile(0.95, rate(http_request_duration_seconds_bucket[5m])) > 2
        for: 5m
        labels:
          severity: warning
          team: backend
        annotations:
          summary: "High response time for {{ $labels.service }}"
          description: "95th percentile response time is {{ $value }}s for {{ $labels.service }}"
      
      # Low request rate
      - alert: LowRequestRate
        expr: sum(rate(http_requests_total[5m])) by (service) < 10
        for: 10m
        labels:
          severity: warning
          team: backend
        annotations:
          summary: "Low request rate for {{ $labels.service }}"
          description: "Request rate is {{ $value }} req/s for {{ $labels.service }}"
      
      # Service down
      - alert: ServiceDown
        expr: up{job="smart-dairy-api"} == 0
        for: 1m
        labels:
          severity: critical
          team: backend
        annotations:
          summary: "Service {{ $labels.service }} is down"
          description: "Service {{ $labels.service }} has been down for more than 1 minute"
```

#### 7.1.3 Database Alerts

**File: /etc/prometheus/rules/database.yml**

```yaml
groups:
  - name: database_alerts
    interval: 30s
    rules:
      # High connection usage
      - alert: HighConnectionUsage
        expr: (pg_stat_database_numbackends / pg_settings_max_connections) * 100 > 90
        for: 5m
        labels:
          severity: critical
          team: database
        annotations:
          summary: "High database connection usage"
          description: "Connection usage is {{ $value }}% on {{ $labels.datname }}"
      
      # Slow queries
      - alert: SlowQueries
        expr: rate(pg_stat_statements_calls_total[5m]) > 100 and pg_stat_statements_mean_exec_time_ms > 500
        for: 5m
        labels:
          severity: warning
          team: database
        annotations:
          summary: "High number of slow queries"
          description: "{{ $value }} queries with mean execution time >500ms"
      
      # Low cache hit ratio
      - alert: LowCacheHitRatio
        expr: pg_stat_database_blks_hit / (pg_stat_database_blks_hit + pg_stat_database_blks_read) * 100 < 90
        for: 10m
        labels:
          severity: warning
          team: database
        annotations:
          summary: "Low cache hit ratio"
          description: "Cache hit ratio is {{ $value }}% on {{ $labels.datname }}"
      
      # Replication lag
      - alert: ReplicationLag
        expr: pg_replication_lag_seconds > 30
        for: 5m
        labels:
          severity: critical
          team: database
        annotations:
          summary: "High replication lag"
          description: "Replication lag is {{ $value }}s"
```

#### 7.1.4 Business Alerts

**File: /etc/prometheus/rules/business.yml**

```yaml
groups:
  - name: business_alerts
    interval: 60s
    rules:
      # Low order rate
      - alert: LowOrderRate
        expr: rate(smart_dairy_orders_total[10m]) < 0.5
        for: 30m
        labels:
          severity: warning
          team: product
        annotations:
          summary: "Low order rate detected"
          description: "Order rate is {{ $value }} orders/minute, below expected baseline"
      
      # Low payment success rate
      - alert: LowPaymentSuccessRate
        expr: sum(increase(smart_dairy_payments_total{status='success'}[10m])) / sum(increase(smart_dairy_payments_total[10m])) * 100 < 95
        for: 10m
        labels:
          severity: critical
          team: product
        annotations:
          summary: "Low payment success rate"
          description: "Payment success rate is {{ $value }}%, below 95% threshold"
      
      # Payment gateway issues
      - alert: PaymentGatewayHighFailureRate
        expr: sum(increase(smart_dairy_payments_total{status='failed'}[5m])) by (gateway) / sum(increase(smart_dairy_payments_total[5m])) by (gateway) * 100 > 10
        for: 5m
        labels:
          severity: critical
          team: product
        annotations:
          summary: "High failure rate for {{ $labels.gateway }}"
          description: "Failure rate is {{ $value }}% for {{ $labels.gateway }} payment gateway"
```

### 7.2 Alertmanager Configuration

**File: /etc/prometheus/alertmanager.yml**

```yaml
global:
  resolve_timeout: 5m
  smtp_smarthost: 'smtp.sendgrid.net:587'
  smtp_from: 'alerts@smartdairy.com.bd'
  smtp_auth_username: 'apikey'
  smtp_auth_password: 'SENDGRID_API_KEY'

# Route configuration
route:
  group_by: ['alertname', 'severity', 'team']
  group_wait: 30s
  group_interval: 5m
  repeat_interval: 12h
  receiver: 'default'
  
  routes:
    # Critical alerts to PagerDuty
    - match:
        severity: critical
      receiver: 'pagerduty'
      
    # DevOps alerts to Slack
    - match:
        team: devops
      receiver: 'devops-slack'
      
    # Database alerts to Slack
    - match:
        team: database
      receiver: 'database-slack'
      
    # Backend alerts to Slack
    - match:
        team: backend
      receiver: 'backend-slack'

# Receivers
receivers:
  - name: 'default'
    email_configs:
      - to: 'alerts@smartdairy.com.bd'
        headers:
          Subject: '[Alert] {{ .GroupLabels.alertname }}'
  
  - name: 'pagerduty'
    pagerduty_configs:
      - service_key: 'PAGERDUTY_SERVICE_KEY'
        severity: 'critical'
  
  - name: 'devops-slack'
    slack_configs:
      - api_url: 'https://hooks.slack.com/services/SLACK_WEBHOOK_URL'
        channel: '#devops-alerts'
        title: '{{ .GroupLabels.alertname }}'
        text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'
        color: '{{ if eq .Status "firing" }}danger{{ else }}good{{ end }}'
  
  - name: 'database-slack'
    slack_configs:
      - api_url: 'https://hooks.slack.com/services/SLACK_WEBHOOK_URL'
        channel: '#database-alerts'
        title: '{{ .GroupLabels.alertname }}'
        text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'
  
  - name: 'backend-slack'
    slack_configs:
      - api_url: 'https://hooks.slack.com/services/SLACK_WEBHOOK_URL'
        channel: '#backend-alerts'
        title: '{{ .GroupLabels.alertname }}'
        text: '{{ range .Alerts }}{{ .Annotations.description }}{{ end }}'

# Inhibit rules
inhibit_rules:
  # If critical alert is firing, inhibit warning for same alert
  - source_match:
      severity: 'critical'
    target_match:
      severity: 'warning'
    equal: ['alertname', 'instance']
```

### 7.3 Alert Escalation Policy

| Severity | Response Time | Escalation | Notification Channels |
|----------|----------------|-------------|---------------------|
| **Critical** | < 5 minutes | 15 min → 30 min → 1 hour | PagerDuty, Slack, Email, SMS |
| **Warning** | < 30 minutes | 1 hour → 2 hours | Slack, Email |
| **Info** | < 1 hour | No escalation | Email |

**On-Call Schedule:**

| Role | Contact | Schedule |
|------|---------|----------|
| **DevOps Lead** | +880-1XXX-XXXXXX | Primary (Mon-Thu) |
| **Backend Lead** | +880-1XXX-XXXXXX | Primary (Fri-Sun) |
| **Database Admin** | +880-1XXX-XXXXXX | Backup (24/7) |

---

## 8. Dashboard Setup

### 8.1 Grafana Installation

**Docker Installation:**

```bash
# Create data directory
mkdir -p /opt/grafana/data
chown -R 472:472 /opt/grafana/data

# Run Grafana
docker run -d \
  --name=grafana \
  -p 3000:3000 \
  -v /opt/grafana/data:/var/lib/grafana \
  -e "GF_SECURITY_ADMIN_PASSWORD=admin123" \
  -e "GF_INSTALL_PLUGINS=grafana-piechart-panel" \
  -e "GF_SERVER_ROOT_URL=https://grafana.smartdairy.com.bd" \
  grafana/grafana:latest
```

**Systemd Service:**

```bash
cat > /etc/systemd/system/grafana.service <<EOF
[Unit]
Description=Grafana
After=docker.service
Requires=docker.service

[Service]
Type=simple
ExecStart=/usr/bin/docker start grafana
ExecStop=/usr/bin/docker stop grafana
Restart=always

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable grafana
systemctl start grafana
```

### 8.2 Dashboard Templates

#### 8.2.1 Executive Dashboard

```json
{
  "dashboard": {
    "title": "Smart Dairy Executive Dashboard",
    "tags": ["executive", "business"],
    "timezone": "Asia/Dhaka",
    "refresh": "1m",
    "panels": [
      {
        "id": 1,
        "title": "Total Revenue (24h)",
        "type": "stat",
        "gridPos": {"x": 0, "y": 0, "w": 6, "h": 8},
        "targets": [
          {
            "expr": "sum(increase(smart_dairy_order_value_bdt_sum[24h]))"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "currencyBDT",
            "color": {"mode": "thresholds"},
            "thresholds": {
              "steps": [
                {"color": "green", "value": null},
                {"color": "yellow", "value": 100000},
                {"color": "red", "value": 500000}
              ]
            }
          }
        }
      },
      {
        "id": 2,
        "title": "Orders (24h)",
        "type": "stat",
        "gridPos": {"x": 6, "y": 0, "w": 6, "h": 8},
        "targets": [
          {
            "expr": "sum(increase(smart_dairy_orders_total{status='created'}[24h]))"
          }
        ]
      },
      {
        "id": 3,
        "title": "Active Users (24h)",
        "type": "stat",
        "gridPos": {"x": 12, "y": 0, "w": 6, "h": 8},
        "targets": [
          {
            "expr": "smart_dairy_active_users{time_window='24h'}"
          }
        ]
      },
      {
        "id": 4,
        "title": "Payment Success Rate",
        "type": "stat",
        "gridPos": {"x": 18, "y": 0, "w": 6, "h": 8},
        "targets": [
          {
            "expr": "sum(increase(smart_dairy_payments_total{status='success'}[1h])) / sum(increase(smart_dairy_payments_total[1h])) * 100"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "percent",
            "max": 100,
            "min": 0
          }
        }
      },
      {
        "id": 5,
        "title": "Revenue Trend (7 days)",
        "type": "timeseries",
        "gridPos": {"x": 0, "y": 8, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "sum(rate(smart_dairy_order_value_bdt_sum[5m])) by (order_type)"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "currencyBDT"
          }
        }
      },
      {
        "id": 6,
        "title": "Orders Trend (7 days)",
        "type": "timeseries",
        "gridPos": {"x": 12, "y": 8, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "sum(rate(smart_dairy_orders_total{status='created'}[5m])) by (order_type)"
          }
        ]
      },
      {
        "id": 7,
        "title": "Orders by Payment Method",
        "type": "piechart",
        "gridPos": {"x": 0, "y": 16, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "sum(increase(smart_dairy_payments_total[24h])) by (gateway)"
          }
        ]
      },
      {
        "id": 8,
        "title": "Conversion Funnel",
        "type": "bargauge",
        "gridPos": {"x": 12, "y": 16, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "smart_dairy_conversion_funnel"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "thresholds": {
              "steps": [
                {"color": "green", "value": null},
                {"color": "yellow", "value": 1000},
                {"color": "red", "value": 5000}
              ]
            }
          }
        }
      }
    ],
    "time": {
      "from": "now-7d",
      "to": "now"
    }
  }
}
```

#### 8.2.2 Technical Dashboard

```json
{
  "dashboard": {
    "title": "Smart Dairy Technical Dashboard",
    "tags": ["technical", "infrastructure"],
    "timezone": "Asia/Dhaka",
    "refresh": "30s",
    "panels": [
      {
        "id": 1,
        "title": "Request Rate (RPS)",
        "type": "timeseries",
        "gridPos": {"x": 0, "y": 0, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "sum(rate(http_requests_total[5m])) by (service)"
          }
        ]
      },
      {
        "id": 2,
        "title": "Response Time (95th percentile)",
        "type": "timeseries",
        "gridPos": {"x": 12, "y": 0, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "histogram_quantile(0.95, rate(http_request_duration_seconds_bucket[5m]))"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "s",
            "thresholds": {
              "steps": [
                {"color": "green", "value": null},
                {"color": "yellow", "value": 0.5},
                {"color": "red", "value": 2}
              ]
            }
          }
        }
      },
      {
        "id": 3,
        "title": "Error Rate",
        "type": "timeseries",
        "gridPos": {"x": 0, "y": 8, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "sum(rate(http_requests_total{status=~'5..'}[5m])) by (service) / sum(rate(http_requests_total[5m])) by (service) * 100"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "percent",
            "max": 100
          }
        }
      },
      {
        "id": 4,
        "title": "CPU Utilization",
        "type": "timeseries",
        "gridPos": {"x": 12, "y": 8, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "100 - (avg by(instance) (rate(node_cpu_seconds_total{mode='idle'}[5m])) * 100)"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "percent",
            "max": 100
          }
        }
      },
      {
        "id": 5,
        "title": "Memory Utilization",
        "type": "timeseries",
        "gridPos": {"x": 0, "y": 16, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "(1 - (node_memory_MemAvailable_bytes / node_memory_MemTotal_bytes)) * 100"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "percent",
            "max": 100
          }
        }
      },
      {
        "id": 6,
        "title": "Database Connections",
        "type": "timeseries",
        "gridPos": {"x": 12, "y": 16, "w": 12, "h": 8},
        "targets": [
          {
            "expr": "pg_stat_database_numbackends"
          }
        ]
      }
    ],
    "time": {
      "from": "now-1h",
      "to": "now"
    }
  }
}
```

### 8.3 Dashboard Access Control

**Grafana Roles:**

| Role | Permissions | Users |
|------|-------------|-------|
| **Admin** | Full access, create dashboards, users | DevOps Lead, Tech Lead |
| **Editor** | Create and edit dashboards | DevOps Engineers, Backend Engineers |
| **Viewer** | View dashboards only | All stakeholders |

**Row-Level Security:**

```yaml
# Grafana configuration
{
  "users": {
    "executive@smartdairy.com.bd": {
      "role": "viewer",
      "dashboards": ["executive-dashboard"]
    },
    "devops@smartdairy.com.bd": {
      "role": "admin",
      "dashboards": ["*"]
    },
    "backend@smartdairy.com.bd": {
      "role": "editor",
      "dashboards": ["technical-dashboard", "application-dashboard"]
    }
  }
}
```

---

## 9. Log Management

### 9.1 ELK Stack Setup

#### 9.1.1 Elasticsearch Installation

**Docker Compose:**

```yaml
version: '3.8'

services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.11.0
    container_name: elasticsearch
    environment:
      - discovery.type=single-node
      - "ES_JAVA_OPTS=-Xms2g -Xmx2g"
      - xpack.security.enabled=false
    ports:
      - "9200:9200"
      - "9300:9300"
    volumes:
      - es-data:/usr/share/elasticsearch/data
    networks:
      - elk

  logstash:
    image: docker.elastic.co/logstash/logstash:8.11.0
    container_name: logstash
    ports:
      - "5044:5044"
      - "9600:9600"
    volumes:
      - ./logstash/pipeline:/usr/share/logstash/pipeline
    networks:
      - elk
    depends_on:
      - elasticsearch

  kibana:
    image: docker.elastic.co/kibana/kibana:8.11.0
    container_name: kibana
    ports:
      - "5601:5601"
    environment:
      - ELASTICSEARCH_HOSTS=http://elasticsearch:9200
    networks:
      - elk
    depends_on:
      - elasticsearch

volumes:
  es-data:

networks:
  elk:
    driver: bridge
```

#### 9.1.2 Logstash Configuration

**File: logstash/pipeline/logstash.conf**

```conf
input {
  beats {
    port => 5044
  }
}

filter {
  # Parse JSON logs
  if [format] == "json" {
    json {
      source => "message"
    }
  }

  # Add timestamp
  date {
    match => ["timestamp", "ISO8601"]
  }

  # Add environment tag
  mutate {
    add_field => { "environment" => "production" }
  }
}

output {
  elasticsearch {
    hosts => ["elasticsearch:9200"]
    index => "smart-dairy-logs-%{+YYYY.MM.dd}"
  }
  
  # Debug output (remove in production)
  stdout {
    codec => rubydebug
  }
}
```

### 9.2 Application Logging

#### 9.2.1 Winston Logger Configuration

```javascript
const winston = require('winston');
const { ElasticsearchTransport } = require('winston-elasticsearch');

// Define log levels
const levels = {
  error: 0,
  warn: 1,
  info: 2,
  http: 3,
  verbose: 4,
  debug: 5,
  silly: 6
};

// Create logger
const logger = winston.createLogger({
  levels: levels,
  format: winston.format.combine(
    winston.format.timestamp(),
    winston.format.errors({ stack: true }),
    winston.format.splat(),
    winston.format.json()
  ),
  defaultMeta: { 
    service: 'smart-dairy-api',
    environment: process.env.NODE_ENV || 'development'
  },
  transports: [
    // Console transport
    new winston.transports.Console({
      format: winston.format.combine(
        winston.format.colorize(),
        winston.format.simple()
      )
    }),
    
    // File transport - errors
    new winston.transports.File({
      filename: 'logs/error.log',
      level: 'error',
      maxsize: 10485760, // 10MB
      maxFiles: 5
    }),
    
    // File transport - combined
    new winston.transports.File({
      filename: 'logs/combined.log',
      maxsize: 10485760, // 10MB
      maxFiles: 10
    }),
    
    // Elasticsearch transport
    new ElasticsearchTransport({
      level: 'info',
      clientOpts: {
        node: 'http://elasticsearch:9200'
      },
      index: 'smart-dairy-logs'
    })
  ]
});

// Request logging middleware
const requestLogger = (req, res, next) => {
  const start = Date.now();
  
  res.on('finish', () => {
    const duration = Date.now() - start;
    
    logger.info('HTTP Request', {
      method: req.method,
      url: req.url,
      statusCode: res.statusCode,
      duration: duration,
      userId: req.user?.id,
      userAgent: req.get('user-agent'),
      ip: req.ip
    });
  });
  
  next();
};

// Error logging middleware
const errorLogger = (err, req, res, next) => {
  logger.error('Application Error', {
    error: err.message,
    stack: err.stack,
    url: req.url,
    method: req.method,
    userId: req.user?.id
  });
  
  next(err);
};

module.exports = { logger, requestLogger, errorLogger };
```

#### 9.2.2 Log Format Standards

**Standard Log Fields:**

```javascript
{
  "timestamp": "2026-01-31T12:00:00.000Z",
  "level": "info",
  "message": "Order created successfully",
  "service": "smart-dairy-api",
  "environment": "production",
  "userId": "12345",
  "orderId": "ORD-2026-001",
  "orderType": "b2c",
  "orderValue": 1250.00,
  "paymentMethod": "bkash",
  "duration": 345
}
```

**Log Levels:**

| Level | Usage | Examples |
|-------|--------|-----------|
| **error** | Error conditions | Failed database queries, payment failures |
| **warn** | Warning conditions | High latency, deprecated usage |
| **info** | Informational | Order created, user login |
| **debug** | Debug information | Function entry/exit, variable values |
| **trace** | Very detailed | API calls, database queries |

### 9.3 Log Querying

#### 9.3.1 Kibana Query Examples

**Search for errors:**

```
level: "error" AND service: "smart-dairy-api"
```

**Search for specific user:**

```
userId: "12345"
```

**Search for payment failures:**

```
message: "payment" AND level: "error"
```

**Time range queries:**

```
@timestamp: [2026-01-31T00:00:00 TO 2026-01-31T23:59:59]
```

**Aggregate queries:**

```json
GET /smart-dairy-logs-*/_search
{
  "size": 0,
  "aggs": {
    "errors_by_service": {
      "terms": {
        "field": "service.keyword",
        "size": 10
      }
    },
    "errors_over_time": {
      "date_histogram": {
        "field": "@timestamp",
        "calendar_interval": "1h"
      }
    }
  }
}
```

---

## 10. Distributed Tracing

### 10.1 Jaeger Setup

**Docker Installation:**

```yaml
version: '3.8'

services:
  jaeger:
    image: jaegertracing/all-in-one:latest
    container_name: jaeger
    ports:
      - "5775:5775/udp"
      - "6831:6831/udp"
      - "6832:6832/udp"
      - "5778:5778"
      - "16686:16686"
      - "14268:14268"
      - "14250:14250"
      - "9411:9411"
    environment:
      - COLLECTOR_ZIPKIN_HOST_PORT=:9411
      - COLLECTOR_OTLP_ENABLED=true
    networks:
      - tracing

networks:
  tracing:
    driver: bridge
```

### 10.2 Trace Visualization

**Access Jaeger UI:**
- URL: http://jaeger.smartdairy.com.bd:16686
- Default service: smart-dairy-api

**Trace Search:**

| Field | Description | Example |
|-------|-------------|---------|
| **Service** | Service name | smart-dairy-api |
| **Operation** | Operation name | POST /api/orders |
| **Tags** | Key-value pairs | order.type=b2b |
| **Duration** | Time range | 1s - 5s |
| **Lookback** | Time window | 1h |

**Trace Analysis:**

```javascript
// View trace in Jaeger
// 1. Go to http://jaeger.smartdairy.com.bd:16686
// 2. Select service: smart-dairy-api
// 3. Search for traces
// 4. Click on trace ID to view details
// 5. Analyze spans, timings, and dependencies
```

---

## 11. Security Monitoring

### 11.1 Security Metrics

#### 11.1.1 Authentication Metrics

```javascript
const authMetrics = {
  loginAttempts: new promClient.Counter({
    name: 'auth_login_attempts_total',
    help: 'Total login attempts',
    labelNames: ['status'], // 'success', 'failed', 'blocked'
  }),

  loginFailures: new promClient.Counter({
    name: 'auth_login_failures_total',
    help: 'Total failed login attempts',
    labelNames: ['reason'] // 'invalid_credentials', 'account_locked', 'rate_limited'
  }),

  passwordResets: new promClient.Counter({
    name: 'auth_password_resets_total',
    help: 'Total password reset requests',
    labelNames: ['status']
  }),

  accountLockouts: new promClient.Counter({
    name: 'auth_account_lockouts_total',
    help: 'Total account lockouts',
    registers: [register]
  })
};
```

#### 11.1.2 Authorization Metrics

```javascript
const authzMetrics = {
  accessDenied: new promClient.Counter({
    name: 'authz_access_denied_total',
    help: 'Total access denied events',
    labelNames: ['resource', 'action', 'reason']
  }),

  privilegeEscalation: new promClient.Counter({
    name: 'authz_privilege_escalation_total',
    help: 'Total privilege escalation attempts',
    labelNames: ['from_role', 'to_role']
  })
};
```

### 11.2 Security Alerts

**File: /etc/prometheus/rules/security.yml**

```yaml
groups:
  - name: security_alerts
    interval: 30s
    rules:
      # Brute force attack detection
      - alert: BruteForceAttack
        expr: rate(auth_login_failures_total[5m]) > 10
        for: 5m
        labels:
          severity: critical
          team: security
        annotations:
          summary: "Possible brute force attack detected"
          description: "High rate of failed login attempts: {{ $value }} failures/min"
      
      # Account lockout spike
      - alert: AccountLockoutSpike
        expr: rate(auth_account_lockouts_total[5m]) > 5
        for: 5m
        labels:
          severity: critical
          team: security
        annotations:
          summary: "High rate of account lockouts"
          description: "{{ $value }} account lockouts/min detected"
      
      # Access denied spike
      - alert: AccessDeniedSpike
        expr: rate(authz_access_denied_total[5m]) > 20
        for: 5m
        labels:
          severity: warning
          team: security
        annotations:
          summary: "High rate of access denied events"
          description: "{{ $value }} access denied events/min detected"
```

---

## 12. Monitoring Automation

### 12.1 Automated Health Checks

**Health Check Endpoints:**

```javascript
// Health check endpoint
app.get('/health', async (req, res) => {
  const checks = {
    uptime: process.uptime(),
    timestamp: new Date().toISOString(),
    status: 'healthy',
    checks: {}
  };

  // Check database
  try {
    await db.query('SELECT 1');
    checks.checks.database = { status: 'healthy', latency: '5ms' };
  } catch (error) {
    checks.checks.database = { status: 'unhealthy', error: error.message };
    checks.status = 'degraded';
  }

  // Check Redis
  try {
    await redis.ping();
    checks.checks.redis = { status: 'healthy', latency: '2ms' };
  } catch (error) {
    checks.checks.redis = { status: 'unhealthy', error: error.message };
    checks.status = 'degraded';
  }

  // Check external APIs
  try {
    const response = await axios.get('https://api.bkash.com.bd/health', {
      timeout: 5000
    });
    checks.checks.bkash = { status: 'healthy', latency: '150ms' };
  } catch (error) {
    checks.checks.bkash = { status: 'unhealthy', error: error.message };
    checks.status = 'degraded';
  }

  const statusCode = checks.status === 'healthy' ? 200 : 503;
  res.status(statusCode).json(checks);
});

// Readiness probe
app.get('/ready', async (req, res) => {
  const checks = await runReadinessChecks();
  const isReady = Object.values(checks).every(check => check.status === 'healthy');
  
  res.status(isReady ? 200 : 503).json(checks);
});
```

**Kubernetes Probe Configuration:**

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: smart-dairy-api
spec:
  containers:
    - name: api
      image: smart-dairy/api:latest
      ports:
        - containerPort: 3000
      livenessProbe:
        httpGet:
          path: /health
          port: 3000
        initialDelaySeconds: 30
        periodSeconds: 10
        timeoutSeconds: 5
        failureThreshold: 3
      readinessProbe:
        httpGet:
          path: /ready
          port: 3000
        initialDelaySeconds: 10
        periodSeconds: 5
        timeoutSeconds: 3
        failureThreshold: 3
```

### 12.2 Automated Scaling

**Horizontal Pod Autoscaler:**

```yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: smart-dairy-api-hpa
  namespace: smart-dairy
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: smart-dairy-api
  minReplicas: 3
  maxReplicas: 10
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
    - type: Pods
      pods:
        metric:
          name: http_requests_per_second
        target:
          type: AverageValue
          averageValue: "500"
```

---

## 13. Retention & Storage

### 13.1 Metrics Retention

**Prometheus Configuration:**

```yaml
global:
  scrape_interval: 15s
  evaluation_interval: 15s

# Storage configuration
storage:
  tsdb:
    retention:
      time: 30d
      size: 10GB
```

**Long-term Storage Strategy:**

| Data Type | Short-term | Long-term | Archive |
|-----------|-----------|------------|---------|
| **Infrastructure Metrics** | 30 days | 1 year | S3 Glacier |
| **Application Metrics** | 30 days | 1 year | S3 Glacier |
| **Business Metrics** | 1 year | 5 years | S3 Standard |
| **Logs** | 30 days | 1 year | S3 Glacier |
| **Traces** | 7 days | 30 days | Deleted |

### 13.2 Log Retention

**Elasticsearch Index Lifecycle Management:**

```json
PUT _ilm/policy/smart-dairy-logs-policy
{
  "policy": {
    "phases": {
      "hot": {
        "min_age": "0ms",
        "actions": {
          "rollover": {
            "max_size": "50GB",
            "max_age": "1d"
          }
        }
      },
      "warm": {
        "min_age": "7d",
        "actions": {
          "forcemerge": {
            "max_num_segments": 1
          }
        }
      },
      "cold": {
        "min_age": "30d",
        "actions": {
          "freeze": {}
        }
      },
      "delete": {
        "min_age": "365d",
        "actions": {
          "delete": {}
        }
      }
    }
  }
}
```

---

## 14. Maintenance & Updates

### 14.1 Maintenance Schedule

| Task | Frequency | Owner | Duration |
|------|------------|-------|----------|
| **Review alert rules** | Weekly | DevOps Lead | 1 hour |
| **Update dashboard queries** | Monthly | DevOps Engineer | 2 hours |
| **Archive old metrics** | Monthly | DevOps Engineer | 1 hour |
| **Review log retention** | Quarterly | DevOps Lead | 1 hour |
| **Performance tuning** | Quarterly | DevOps Lead | 4 hours |
| **Security audit** | Annually | Security Team | 8 hours |

### 14.2 Monitoring Updates

**Version Management:**

```bash
# Monitor component versions
prometheus --version
grafana-cli --version
logstash --version

# Check for updates
apt list --upgradable | grep prometheus
apt list --upgradable | grep grafana

# Update components (with testing)
apt update prometheus
systemctl restart prometheus

# Verify after update
curl http://localhost:9090/-/healthy
```

---

## 15. Appendix

### Appendix A: Monitoring Checklist

#### A.1 Setup Checklist

- [ ] Prometheus installed and configured
- [ ] Node Exporter installed on all servers
- [ ] PostgreSQL Exporter installed and configured
- [ ] Redis Exporter installed and configured
- [ ] Alertmanager configured and running
- [ ] Grafana installed and configured
- [ ] Dashboard templates created
- [ ] Alert rules defined and tested
- [ ] Log aggregation (ELK) configured
- [ ] Application instrumentation complete
- [ ] Business metrics collection enabled
- [ ] Distributed tracing configured
- [ ] Security monitoring enabled
- [ ] Automated health checks implemented
- [ ] Retention policies configured

#### A.2 Daily Monitoring Checklist

- [ ] Review critical alerts
- [ ] Check dashboard anomalies
- [ ] Verify system health endpoints
- [ ] Review error logs
- [ ] Monitor resource utilization
- [ ] Check backup status

### Appendix B: Troubleshooting

#### B.1 Common Issues

**Issue: High CPU usage**
```
1. Check Prometheus: http://localhost:9090
2. Query: 100 - (avg by(instance) (rate(node_cpu_seconds_total{mode="idle"}[5m])) * 100)
3. Identify top CPU processes: top -p $(pgrep -f "node.*api")
4. Check application logs for errors
5. Consider scaling or optimization
```

**Issue: High memory usage**
```
1. Check Prometheus: http://localhost:9090
2. Query: (1 - (node_memory_MemAvailable_bytes / node_memory_MemTotal_bytes)) * 100
3. Identify memory leaks: monitor memory trends over time
4. Check application logs for memory warnings
5. Review heap dumps if necessary
```

**Issue: Database slow queries**
```
1. Check pg_stat_statements: SELECT * FROM pg_stat_statements ORDER BY mean_exec_time DESC LIMIT 10
2. Analyze query execution plans: EXPLAIN ANALYZE
3. Check for missing indexes: SELECT * FROM pg_stat_user_indexes WHERE seq_scan > idx_scan
4. Consider query optimization or indexing
```

---

**Document End**

*This Monitoring Setup Guide provides comprehensive instructions for implementing monitoring infrastructure for the Smart Dairy Web Portal System. It should be reviewed and updated regularly to reflect system changes and evolving requirements.*