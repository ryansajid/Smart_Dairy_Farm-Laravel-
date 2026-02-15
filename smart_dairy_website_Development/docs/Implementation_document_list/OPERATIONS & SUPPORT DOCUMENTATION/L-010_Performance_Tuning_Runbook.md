# Document L-010: Performance Tuning Runbook

## Smart Dairy Ltd. Smart Web Portal System

---

**Document ID:** L-010  
**Version:** 1.0  
**Date:** January 31, 2026  
**Author:** Performance Engineer  
**Owner:** Performance Engineer  
**Reviewer:** IT Director  
**Classification:** Internal Use - Operations  

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Performance Baselines](#2-performance-baselines)
3. [Monitoring Tools](#3-monitoring-tools)
4. [Performance Diagnostics](#4-performance-diagnostics)
5. [Application Tuning](#5-application-tuning)
6. [Database Tuning](#6-database-tuning)
7. [Infrastructure Tuning](#7-infrastructure-tuning)
8. [Load Testing Procedures](#8-load-testing-procedures)
9. [Capacity Planning Integration](#9-capacity-planning-integration)
10. [Performance Incident Response](#10-performance-incident-response)
11. [Appendices](#11-appendices)

---

## Revision History

| Version | Date | Author | Changes | Approver |
|---------|------|--------|---------|----------|
| 1.0 | 2026-01-31 | Performance Engineer | Initial version | IT Director |

---

## Distribution List

- IT Director
- DevOps Team Lead
- Database Administrator
- Application Developers
- Infrastructure Engineers
- Support Team Lead

---

## 1. Introduction

### 1.1 Purpose

This Performance Tuning Runbook provides comprehensive guidance for identifying, diagnosing, and resolving performance issues within the Smart Dairy Smart Web Portal System. It serves as the primary reference for performance engineers, system administrators, and development teams responsible for maintaining optimal system performance.

### 1.2 Scope

This runbook covers:
- Performance monitoring and baseline establishment
- Diagnostic procedures for common performance issues
- Tuning procedures for application, database, and infrastructure components
- Load testing methodologies
- Capacity planning integration
- Performance incident response workflows

### 1.3 Performance Objectives

#### 1.3.1 Core Performance Targets

| Metric | Target | Acceptable | Critical Threshold |
|--------|--------|------------|-------------------|
| API Response Time (p95) | < 200ms | < 500ms | > 1000ms |
| Page Load Time | < 2s | < 3s | > 5s |
| Database Query Time (p95) | < 50ms | < 100ms | > 500ms |
| Mobile App Response | < 1s | < 2s | > 4s |
| IoT Data Ingestion | < 100ms | < 200ms | > 1000ms |
| Report Generation | < 5s | < 10s | > 30s |

#### 1.3.2 Availability Objectives

| Component | Target Uptime | Max Downtime/Month |
|-----------|--------------|-------------------|
| Web Portal | 99.9% | 43 minutes |
| Mobile APIs | 99.95% | 21 minutes |
| IoT Platform | 99.9% | 43 minutes |
| Payment Gateway | 99.99% | 4 minutes |
| Admin ERP | 99.5% | 3.6 hours |

#### 1.3.3 Throughput Requirements

| Component | Normal Load | Peak Load | Burst Capacity |
|-----------|-------------|-----------|----------------|
| Web Requests/sec | 1,000 | 5,000 | 10,000 |
| API Calls/sec | 2,000 | 10,000 | 20,000 |
| IoT Messages/min | 10,000 | 50,000 | 100,000 |
| Concurrent Users | 5,000 | 25,000 | 50,000 |
| Database TPS | 5,000 | 25,000 | 50,000 |

### 1.4 Key Performance Indicators (KPIs)

#### 1.4.1 Application KPIs

```
+------------------------------------------------------------------+
|                   APPLICATION KPI DASHBOARD                       |
+------------------------------------------------------------------+
| Response Time          | Error Rate          | Throughput        |
| 250ms                  | 0.1%                | 2,100 req/s       |
| Target: <200ms         | Target: <0.5%       | Target: 2K        |
| Status: WARNING        | Status: OK          | Status: OK        |
+------------------------+---------------------+-------------------+
| Apdex Score           | Saturation          | Latency P99       |
| 0.92                  | 45%                 | 450ms             |
| Target: >0.9          | Target: <70%        | Target: <500ms    |
| Status: OK            | Status: OK          | Status: OK        |
+------------------------------------------------------------------+
```

#### 1.4.2 Infrastructure KPIs

| Resource | Metric | Normal | Warning | Critical |
|----------|--------|--------|---------|----------|
| CPU | Utilization | < 60% | 60-80% | > 80% |
| Memory | Utilization | < 70% | 70-85% | > 85% |
| Disk | I/O Wait | < 10% | 10-20% | > 20% |
| Disk | Usage | < 70% | 70-85% | > 85% |
| Network | Latency | < 10ms | 10-50ms | > 50ms |
| Network | Throughput | < 70% | 70-90% | > 90% |

### 1.5 Performance Testing Philosophy

> Performance is not a feature to be added later - it is a fundamental quality attribute that must be designed in, measured continuously, and optimized proactively.

### 1.6 Related Documents

- D-013: Auto-scaling Configuration
- D-009: Log Aggregation Analysis (ELK)
- B-010: Caching Strategy Document
- G-005: Test Plan Phase 4 - Optimization
- C-008: TimescaleDB Implementation Guide

---

## 2. Performance Baselines

### 2.1 Baseline Establishment Methodology

Performance baselines are established through:
1. **Steady-State Monitoring**: 30 days of continuous production monitoring
2. **Load Test Validation**: Synthetic load testing at known capacity points
3. **Seasonal Analysis**: Accounting for business cycle variations
4. **Quarterly Review**: Baseline updates based on system changes

### 2.2 Normal Operation Metrics

#### 2.2.1 Web Portal Baselines

| Metric | Business Hours | Off-Hours | Weekend |
|--------|---------------|-----------|---------|
| Avg Response Time | 180ms | 120ms | 150ms |
| Requests/minute | 3,000 | 500 | 800 |
| Concurrent Sessions | 1,200 | 200 | 400 |
| Page Views/hour | 15,000 | 2,000 | 4,000 |
| Error Rate | 0.05% | 0.02% | 0.03% |

#### 2.2.2 API Gateway Baselines

| Endpoint Category | Avg Response | p95 Response | Calls/min |
|------------------|--------------|--------------|-----------|
| Authentication | 45ms | 80ms | 1,500 |
| Product Catalog | 120ms | 250ms | 2,000 |
| Order Management | 180ms | 350ms | 800 |
| Payment Processing | 200ms | 400ms | 300 |
| IoT Data Ingest | 80ms | 150ms | 5,000 |
| Reporting | 2,000ms | 5,000ms | 50 |

#### 2.2.3 Database Performance Baselines

| Database | Metric | Normal Value |
|----------|--------|--------------|
| PostgreSQL | Queries/sec | 3,500 |
| PostgreSQL | Avg Query Time | 12ms |
| PostgreSQL | Cache Hit Ratio | 98.5% |
| PostgreSQL | Connection Pool | 85/200 |
| TimescaleDB | Inserts/sec | 8,000 |
| TimescaleDB | Compression Ratio | 7:1 |
| Redis | Operations/sec | 25,000 |
| Redis | Memory Usage | 4.2GB/8GB |
| Redis | Hit Rate | 94% |

#### 2.2.4 Mobile Application Baselines

| Metric | Android | iOS | Overall |
|--------|---------|-----|---------|
| App Launch Time | 1.2s | 1.0s | 1.1s |
| API Response Time | 280ms | 260ms | 270ms |
| Screen Render Time | 120ms | 100ms | 110ms |
| Battery Impact/hour | 8% | 7% | 7.5% |
| Crash Rate | 0.1% | 0.08% | 0.09% |

### 2.3 Peak Load Metrics

#### 2.3.1 Peak Load Scenarios

```
+------------------------------------------------------------------+
|                    PEAK LOAD SCENARIOS                           |
+------------------------------------------------------------------+
|                                                                  |
| 1. EID FESTIVAL SALE (Shopping Peak)                            |
|    - Concurrent Users: 25,000                                   |
|    - Orders/Hour: 5,000                                         |
|    - Duration: 3 days                                           |
|    - Expected Growth: 400%                                      |
|                                                                  |
| 2. MORNING MILK COLLECTION (IoT Peak)                           |
|    - IoT Devices: 5,000 active                                  |
|    - Messages/Min: 50,000                                       |
|    - Duration: 2 hours (6AM-8AM)                                |
|    - Expected Growth: 500%                                      |
|                                                                  |
| 3. MONTH-END REPORTING (Analytics Peak)                         |
|    - Concurrent Reports: 200                                    |
|    - Database Load: +150%                                       |
|    - Duration: 4 hours                                          |
|    - Expected Growth: 300%                                      |
|                                                                  |
+------------------------------------------------------------------+
```

#### 2.3.2 Peak Load Performance Thresholds

| Metric | Normal | Peak | Max Acceptable |
|--------|--------|------|----------------|
| CPU Utilization | 40% | 70% | 85% |
| Memory Utilization | 50% | 75% | 90% |
| Response Time | 200ms | 500ms | 1000ms |
| Error Rate | 0.1% | 0.5% | 2% |
| Queue Depth | 10 | 100 | 500 |
| DB Connections | 50 | 150 | 180 |

### 2.4 Threshold Definitions

#### 2.4.1 Alert Threshold Matrix

```
+----------------+-------------+-------------+----------------+--------------+
| Metric         | Info        | Warning     | Critical       | Emergency    |
+----------------+-------------+-------------+----------------+--------------+
| CPU %          | > 50%       | > 70%       | > 85%          | > 95%        |
| Memory %       | > 60%       | > 75%       | > 90%          | > 95%        |
| Disk I/O Wait  | > 5%        | > 10%       | > 20%          | > 40%        |
| Disk Usage     | > 70%       | > 80%       | > 90%          | > 95%        |
| Response Time  | > 300ms     | > 500ms     | > 1000ms       | > 3000ms     |
| Error Rate     | > 0.1%      | > 0.5%      | > 1%           | > 5%         |
| DB Connections | > 120       | > 150       | > 170          | > 190        |
| Queue Size     | > 50        | > 100       | > 500          | > 1000       |
| Apdex Score    | < 0.95      | < 0.90      | < 0.75         | < 0.50       |
+----------------+-------------+-------------+----------------+--------------+
```

#### 2.4.2 Threshold Response Actions

| Severity | Response Time | Action Required |
|----------|---------------|-----------------|
| Info | Within 24 hours | Monitor trend, no immediate action |
| Warning | Within 2 hours | Investigate, prepare mitigation |
| Critical | Within 15 minutes | Active investigation, implement fixes |
| Emergency | Immediate | All-hands response, emergency procedures |

### 2.5 Baseline Monitoring Checklist

- [ ] All baseline metrics documented and stored in monitoring system
- [ ] Alert thresholds configured in Prometheus/Grafana
- [ ] Team trained on baseline interpretation
- [ ] Weekly baseline comparison reports automated
- [ ] Quarterly baseline review scheduled
- [ ] Baseline drift detection enabled

---

## 3. Monitoring Tools

### 3.1 Prometheus and Grafana

#### 3.1.1 Prometheus Configuration

```yaml
# prometheus.yml - Smart Dairy Performance Monitoring
global:
  scrape_interval: 15s
  evaluation_interval: 15s
  external_labels:
    cluster: 'smart-dairy-prod'
    replica: '{{.ExternalURL}}'

rule_files:
  - /etc/prometheus/rules/*.yml

alerting:
  alertmanagers:
    - static_configs:
        - targets: ['alertmanager:9093']

scrape_configs:
  - job_name: 'smart-dairy-api'
    static_configs:
      - targets: 
        - 'api-1:8080'
        - 'api-2:8080'
        - 'api-3:8080'
    metrics_path: '/actuator/prometheus'
    scrape_interval: 10s
    
  - job_name: 'node-exporter'
    static_configs:
      - targets: 
        - 'node-1:9100'
        - 'node-2:9100'
        - 'node-3:9100'
    scrape_interval: 15s
    
  - job_name: 'postgres-exporter'
    static_configs:
      - targets: ['postgres-exporter:9187']
    scrape_interval: 15s
    
  - job_name: 'redis-exporter'
    static_configs:
      - targets: ['redis-exporter:9121']
    scrape_interval: 15s
```

#### 3.1.2 Key Prometheus Queries (PromQL)

**Response Time Analysis:**
```promql
# Average response time by endpoint
histogram_quantile(0.95, 
  sum(rate(http_request_duration_seconds_bucket[5m])) by (le, endpoint)
)

# Request rate per minute
sum(rate(http_requests_total[1m])) by (service)

# Error rate percentage
(
  sum(rate(http_requests_total{status=~"5.."}[5m])) 
  / 
  sum(rate(http_requests_total[5m]))
) * 100
```

**Resource Utilization:**
```promql
# CPU utilization per pod
avg(rate(container_cpu_usage_seconds_total{pod=~"smart-dairy-.*"}[5m])) 
by (pod) * 100

# Memory utilization percentage
(
  container_memory_working_set_bytes{pod=~"smart-dairy-.*"} 
  / 
  container_spec_memory_limit_bytes{pod=~"smart-dairy-.*"}
) * 100
```

**Database Performance:**
```promql
# Database query duration (p95)
histogram_quantile(0.95, 
  sum(rate(postgresql_query_duration_seconds_bucket[5m])) by (le)
)

# Active connections
postgresql_stat_activity_count{state="active"}

# Cache hit ratio
(
  rate(postgresql_stat_database_blks_hit[5m]) 
  / 
  (rate(postgresql_stat_database_blks_hit[5m]) 
   + rate(postgresql_stat_database_blks_read[5m]))
) * 100
```

### 3.2 Application Performance Monitoring (APM)

#### 3.2.1 APM Tool Configuration

```yaml
# apm-config.yml
apm:
  enabled: true
  application_name: "smart-dairy-api"
  license_key: ${APM_LICENSE_KEY}
  
  distributed_tracing:
    enabled: true
    
  transaction_tracer:
    enabled: true
    transaction_threshold: 500ms
    record_sql: obfuscated
    log_sql: true
    
  error_collector:
    enabled: true
    ignore_errors:
      - "org.springframework.web.servlet.NoHandlerFoundException"
```

#### 3.2.2 Custom Performance Metrics

```java
@Configuration
public class PerformanceMetricsConfig {
    
    @Bean
    public MeterRegistryCustomizer<MeterRegistry> metricsCommonTags() {
        return registry -> registry.config()
            .commonTags("application", "smart-dairy-api")
            .commonTags("environment", "production")
            .commonTags("version", "1.0.0");
    }
    
    @Bean
    public Timer orderProcessingTimer(MeterRegistry registry) {
        return Timer.builder("business.order.processing")
            .description("Time taken to process orders")
            .tags("region", "bangladesh")
            .register(registry);
    }
    
    @Bean
    public Counter paymentFailureCounter(MeterRegistry registry) {
        return Counter.builder("business.payment.failures")
            .description("Payment processing failures")
            .register(registry);
    }
}
```

### 3.3 Database Monitoring

#### 3.3.1 PostgreSQL Monitoring Queries

```sql
-- Active connections and their states
SELECT 
    state,
    COUNT(*),
    MAX(EXTRACT(EPOCH FROM (NOW() - query_start))) AS max_duration_sec
FROM pg_stat_activity
WHERE state IS NOT NULL
GROUP BY state;

-- Slow queries (running > 5 seconds)
SELECT 
    pid,
    usename,
    application_name,
    client_addr,
    query_start,
    EXTRACT(EPOCH FROM (NOW() - query_start)) AS duration_sec,
    LEFT(query, 100) AS query_preview
FROM pg_stat_activity
WHERE state = 'active'
  AND query_start < NOW() - INTERVAL '5 seconds'
ORDER BY query_start;

-- Table bloat estimation
SELECT 
    schemaname,
    tablename,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS total_size,
    n_dead_tup,
    n_live_tup,
    ROUND(n_dead_tup * 100.0 / NULLIF(n_live_tup + n_dead_tup, 0), 2) AS dead_tuple_ratio
FROM pg_stat_user_tables
WHERE n_dead_tup > 1000
ORDER BY n_dead_tup DESC
LIMIT 20;

-- Index usage statistics
SELECT 
    schemaname,
    tablename,
    indexrelname,
    idx_scan,
    idx_tup_read,
    idx_tup_fetch,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size
FROM pg_stat_user_indexes
WHERE idx_scan < 50
  AND pg_relation_size(indexrelid) > 1000000
ORDER BY pg_relation_size(indexrelid) DESC;

-- Cache hit ratio by database
SELECT 
    datname,
    ROUND(
        blks_hit * 100.0 / NULLIF(blks_hit + blks_read, 0), 
        2
    ) AS cache_hit_ratio
FROM pg_stat_database
WHERE datname NOT IN ('template0', 'template1');
```

#### 3.3.2 TimescaleDB-Specific Monitoring

```sql
-- Hypertable chunk statistics
SELECT 
    hypertable_name,
    chunk_name,
    range_start,
    range_end,
    pg_size_pretty(disk_size) AS disk_size,
    compression_ratio
FROM timescaledb_information.chunks
WHERE hypertable_name = 'iot_sensor_data'
ORDER BY range_start DESC
LIMIT 10;

-- Continuous aggregate refresh statistics
SELECT 
    view_name,
    completed_threshold,
    invalidation_threshold,
    last_run_started,
    last_run_status
FROM timescaledb_information.continuous_aggregates;

-- Compression statistics
SELECT 
    hypertable_name,
    count(*) AS total_chunks,
    sum(CASE WHEN is_compressed THEN 1 ELSE 0 END) AS compressed_chunks,
    pg_size_pretty(sum(disk_size)) AS total_size
FROM timescaledb_information.chunks
GROUP BY hypertable_name;
```

### 3.4 Log Analysis

#### 3.4.1 ELK Stack Log Aggregation

```yaml
# Filebeat Configuration
filebeat.inputs:
- type: log
  enabled: true
  paths:
    - /var/log/smart-dairy/application/*.log
  fields:
    service: smart-dairy-api
    environment: production
  multiline.pattern: '^\d{4}-\d{2}-\d{2}'
  multiline.negate: true
  multiline.match: after
    
- type: log
  enabled: true
  paths:
    - /var/log/nginx/access.log
  fields:
    service: nginx
    log_type: access
    
- type: log
  enabled: true
  paths:
    - /var/log/postgresql/postgresql-*.log
  fields:
    service: postgresql
    log_type: database

output.elasticsearch:
  hosts: ["elasticsearch:9200"]
  index: "smart-dairy-logs-%{+yyyy.MM.dd}"
```

### 3.5 Monitoring Alert Configuration

#### 3.5.1 Prometheus Alert Rules

```yaml
# alert-rules.yml
groups:
  - name: smart-dairy-performance
    interval: 30s
    rules:
      - alert: HighResponseTime
        expr: |
          histogram_quantile(0.95, 
            sum(rate(http_request_duration_seconds_bucket[5m])) by (le, endpoint)
          ) > 0.5
        for: 5m
        labels:
          severity: warning
          team: backend
        annotations:
          summary: "High response time on {{ $labels.endpoint }}"
          description: "95th percentile response time is {{ $value }}s"
          
      - alert: HighErrorRate
        expr: |
          (
            sum(rate(http_requests_total{status=~"5.."}[5m])) 
            / 
            sum(rate(http_requests_total[5m]))
          ) > 0.01
        for: 2m
        labels:
          severity: critical
          team: backend
        annotations:
          summary: "High error rate detected"
          description: "Error rate is {{ $value | humanizePercentage }}"
          
      - alert: DatabaseConnectionsHigh
        expr: postgresql_stat_activity_count > 150
        for: 5m
        labels:
          severity: warning
          team: database
        annotations:
          summary: "High database connection count"
          description: "{{ $value }} active connections"
          
      - alert: HighCPUUsage
        expr: |
          100 - (avg by (instance) (irate(node_cpu_seconds_total{mode="idle"}[5m])) * 100) > 80
        for: 10m
        labels:
          severity: warning
          team: infrastructure
        annotations:
          summary: "High CPU usage on {{ $labels.instance }}"
          description: "CPU usage is {{ $value }}%"
          
      - alert: HighMemoryUsage
        expr: |
          (
            node_memory_MemTotal_bytes - node_memory_MemAvailable_bytes
          ) / node_memory_MemTotal_bytes * 100 > 85
        for: 5m
        labels:
          severity: critical
          team: infrastructure
        annotations:
          summary: "High memory usage on {{ $labels.instance }}"
          description: "Memory usage is {{ $value }}%"
```

---

## 4. Performance Diagnostics

### 4.1 Diagnostic Decision Tree

```
+-----------------------------------------------------------------------------+
|                    PERFORMANCE DIAGNOSTIC DECISION TREE                      |
+-----------------------------------------------------------------------------+
|                                                                             |
|  START: Performance Degradation Detected                                    |
|                          |                                                  |
|                          v                                                  |
|              +-----------------------+                                      |
|              | Check Alert Severity  |                                      |
|              +-----------+-----------+                                      |
|              High /      |      \ Low                                       |
|                         /|\                                                  |
|                        / | \                                                 |
|                       v  v  v                                               |
|              +----------+ +----------+                                       |
|              | CRITICAL | | WARNING  |                                       |
|              +-----+-----+ +-----+----+                                       |
|                    |            |                                            |
|                    v            v                                            |
|              Immediate     Deep Analysis                                     |
|              Response           |                                            |
|                                 v                                            |
|                     +-----------------------+                               |
|                     | Identify Resource     |                               |
|                     | Bottleneck Category   |                               |
|                     +-----------+-----------+                               |
|                    CPU /        |        \ I/O                               |
|                              MEMORY                                          |
|                                                                             |
|  CPU Diagnostics:      Memory Diagnostics:      I/O Diagnostics:            |
|  - top, htop           - free, vmstat           - iostat                    |
|  - pidstat             - ps aux                 - df -h                     |
|  - perf                - jmap                   - iotop                     |
|  - async-profiler      - heap dump              - pg_stat_user_tables       |
|                                                                             |
|  If still degraded:                                                         |
|  -> Network Diagnostics (Section 4.5)                                       |
|  -> Database Diagnostics (Section 4.6)                                      |
|                                                                             |
+-----------------------------------------------------------------------------+
```

### 4.2 CPU Issues

#### 4.2.1 CPU Diagnostic Workflow

```
+-----------------------------------------------------------------------------+
|                         CPU DIAGNOSTIC WORKFLOW                              |
+-----------------------------------------------------------------------------+
|                                                                             |
|  STEP 1: VERIFY HIGH CPU                                                     |
|  Command: top, htop, or vmstat 1                                             |
|  Check: Overall CPU %, Load Average, User vs System vs IOWait                |
|  Threshold: >80% sustained for 5+ minutes                                    |
|                                  |                                          |
|                                  v                                          |
|  STEP 2: IDENTIFY CPU CONSUMER                                               |
|  # Top CPU processes                                                         |
|  ps aux --sort=-%cpu | head -20                                              |
|                                                                              |
|  # Per-thread CPU usage                                                      |
|  ps -eLo pid,tid,%cpu,cmd | grep <PID> | sort -k3 -rn                        |
|                                                                              |
|  # Java process thread dump analysis                                         |
|  jstack <PID> > threaddump.txt                                               |
|                                  |                                           |
|                    +-------------+-------------+                             |
|                    |                           |                             |
|                    v                           v                             |
|          +-----------------+         +-----------------+                     |
|          |  APPLICATION    |         |   SYSTEM        |                     |
|          |    PROCESS      |         |   PROCESS       |                     |
|          +--------+--------+         +--------+--------+                     |
|                   |                           |                              |
|                   v                           v                              |
|         Profile with async-profiler    Check cron jobs                       |
|         Analyze thread dumps           Check system services                 |
|         Review GC logs                 Check for malware                     |
|         Check for infinite loops       Review kernel logs                    |
|         Review recent deployments      Check hardware health                 |
|                                                                             |
+-----------------------------------------------------------------------------+
```

#### 4.2.2 CPU Profiling Commands

```bash
#!/bin/bash
# cpu-diagnostics.sh - Smart Dairy CPU Diagnostics Script

echo "=== CPU DIAGNOSTICS REPORT ==="
echo "Generated: $(date)"
echo "Hostname: $(hostname)"
echo ""

# Overall CPU usage
echo "--- Overall CPU Statistics ---"
mpstat -P ALL 1 3

# Load average
echo ""
echo "--- Load Average (1m, 5m, 15m) ---"
cat /proc/loadavg

# Top CPU consumers
echo ""
echo "--- Top 20 CPU Consuming Processes ---"
ps aux --sort=-%cpu | head -21

# CPU usage by user/system/iowait
echo ""
echo "--- CPU Time Distribution ---"
iostat -c 1 3

# If Java process is consuming CPU
JAVA_PID=$(pgrep -f "smart-dairy-api" | head -1)
if [ -n "$JAVA_PID" ]; then
    echo ""
    echo "--- Java Process CPU Analysis (PID: $JAVA_PID) ---"
    
    # Top threads in Java process
    ps -eLo pid,tid,%cpu,cmd | grep "$JAVA_PID" | sort -k3 -rn | head -10
    
    # Generate thread dump
    echo ""
    echo "--- Generating Thread Dump ---"
    jstack "$JAVA_PID" > /tmp/threaddump_$(date +%Y%m%d_%H%M%S).txt
    echo "Thread dump saved to /tmp/"
fi
```

### 4.3 Memory Issues

#### 4.3.1 Memory Diagnostic Workflow

```
+-----------------------------------------------------------------------------+
|                       MEMORY DIAGNOSTIC WORKFLOW                             |
+-----------------------------------------------------------------------------+
|                                                                             |
|  STEP 1: CHECK MEMORY USAGE                                                  |
|  Commands: free -h, vmstat -s, cat /proc/meminfo                             |
|  Check: Used, Free, Buffers, Cache, Available                                |
|  Threshold: >85% usage or <10% available                                     |
|                                  |                                          |
|                                  v                                          |
|  STEP 2: IDENTIFY MEMORY CONSUMERS                                           |
|  # Process memory usage                                                      |
|  ps aux --sort=-%mem | head -20                                              |
|                                                                              |
|  # Memory by process tree                                                    |
|  smem -t -k                                                                  |
|                                                                              |
|  # Java heap details                                                         |
|  jmap -heap <PID>                                                            |
|  jmap -histo <PID> | head -30                                                |
|                                  |                                           |
|                    +-------------+-------------+                             |
|                    |             |             |                             |
|                    v             v             v                             |
|              +---------+  +---------+  +-------------+                       |
|              |  HEAP   |  |  OFF    |  |   NATIVE    |                       |
|              |MEMORY   |  |  HEAP   |  |   MEMORY    |                       |
|              +----+----+  +----+----+  +------+------+                       |
|                   |            |             |                               |
|                   v            v             v                               |
|         Heap dump        Direct buffers   JNI memory                         |
|         GC analysis      Mapped files      Thread stack                      |
|         OOM analysis                        Native libs                      |
|         Leak suspect                        Metaspace                        |
|                                                                             |
+-----------------------------------------------------------------------------+
```

#### 4.3.2 Memory Leak Detection Script

```bash
#!/bin/bash
# memory-diagnostics.sh - Smart Dairy Memory Diagnostics

echo "=== MEMORY DIAGNOSTICS REPORT ==="
echo "Generated: $(date)"
echo ""

# System memory overview
echo "--- System Memory Overview ---"
free -h
echo ""
echo "--- Memory Statistics ---"
vmstat -s | head -20

# Top memory consumers
echo ""
echo "--- Top 20 Memory Consuming Processes ---"
ps aux --sort=-%mem | head -21

# Memory usage by user
echo ""
echo "--- Memory Usage by User ---"
smem -u -t

# Java heap analysis
JAVA_PID=$(pgrep -f "smart-dairy-api" | head -1)
if [ -n "$JAVA_PID" ]; then
    echo ""
    echo "--- Java Heap Summary (PID: $JAVA_PID) ---"
    jmap -heap "$JAVA_PID"
    
    echo ""
    echo "--- Top 30 Heap Objects ---"
    jmap -histo "$JAVA_PID" | head -35
    
    echo ""
    echo "--- Generating Heap Dump ---"
    jmap -dump:format=b,file=/tmp/heapdump_$(date +%Y%m%d_%H%M%S).hprof "$JAVA_PID"
    echo "Heap dump saved to /tmp/"
    
    # GC statistics
    echo ""
    echo "--- GC Statistics ---"
    jstat -gcutil "$JAVA_PID" 1 5
fi

# OOM killer history
echo ""
echo "--- OOM Killer History ---"
dmesg | grep -i "out of memory" | tail -10
```

### 4.4 Disk I/O Issues

#### 4.4.1 I/O Diagnostic Commands

```bash
#!/bin/bash
# io-diagnostics.sh - Smart Dairy I/O Diagnostics

echo "=== I/O DIAGNOSTICS REPORT ==="
echo "Generated: $(date)"
echo ""

# Overall I/O statistics
echo "--- I/O Statistics ---"
iostat -x 1 3

# Disk usage
echo ""
echo "--- Disk Usage ---"
df -h

# I/O by process
echo ""
echo "--- Top I/O Consuming Processes ---"
iotop -b -n 3 -o 2>/dev/null || echo "iotop requires root privileges"

# Filesystem cache statistics
echo ""
echo "--- Filesystem Cache Stats ---"
cat /proc/sys/vm/drop_caches 2>/dev/null || echo "Cache info unavailable"

# PostgreSQL specific I/O
if systemctl is-active --quiet postgresql 2>/dev/null; then
    echo ""
    echo "--- PostgreSQL I/O Stats ---"
    sudo -u postgres psql -c "
        SELECT 
            schemaname,
            tablename,
            pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) as size,
            seq_scan,
            seq_tup_read,
            idx_scan,
            idx_tup_fetch
        FROM pg_stat_user_tables
        ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC
        LIMIT 10;
    "
fi

# Check for swap usage
echo ""
echo "--- Swap Usage ---"
swapon -s
vmstat 1 3 | tail -n +2
```

### 4.5 Network Issues

#### 4.5.1 Network Diagnostic Commands

```bash
#!/bin/bash
# network-diagnostics.sh - Smart Dairy Network Diagnostics

echo "=== NETWORK DIAGNOSTICS REPORT ==="
echo "Generated: $(date)"
echo ""

# Network interface statistics
echo "--- Network Interface Statistics ---"
netstat -i

# Connection statistics
echo ""
echo "--- Connection Statistics ---"
netstat -s | head -50

# Active connections by state
echo ""
echo "--- Connections by State ---"
netstat -ant | awk '{print $6}' | sort | uniq -c | sort -rn

# Connection count by service
echo ""
echo "--- Connections by Local Port ---"
netstat -ant | awk '{print $4}' | cut -d: -f2 | sort | uniq -c | sort -rn | head -20

# Latency tests to critical services
echo ""
echo "--- Latency to Critical Services ---"
ping -c 3 postgres.internal 2>/dev/null || echo "PostgreSQL: Cannot reach"
ping -c 3 redis.internal 2>/dev/null || echo "Redis: Cannot reach"
ping -c 3 sslcommerz.com 2>/dev/null || echo "SSLCommerz: Cannot reach"

# DNS resolution time
echo ""
echo "--- DNS Resolution Time ---"
dig +stats smartdairy.com.bd 2>/dev/null | grep "Query time" || echo "dig not available"

# TCP retransmissions (packet loss indicator)
echo ""
echo "--- TCP Retransmissions ---"
cat /proc/net/snmp | grep Tcp | tail -1
```

#### 4.5.2 Network Optimization Configuration

```nginx
# nginx.conf - Network Optimization Settings
http {
    # Connection handling
    worker_processes auto;
    worker_connections 4096;
    use epoll;
    multi_accept on;
    
    # TCP optimization
    tcp_nopush on;
    tcp_nodelay on;
    
    # Keepalive settings
    keepalive_timeout 30;
    keepalive_requests 1000;
    
    # File upload optimization
    client_body_buffer_size 128k;
    client_max_body_size 50m;
    
    # Gzip compression for text content
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json 
               application/javascript application/rss+xml 
               application/atom+xml image/svg+xml;
    
    # Upstream connection pooling
    upstream api_backend {
        least_conn;
        server api-1:8080 max_fails=3 fail_timeout=30s;
        server api-2:8080 max_fails=3 fail_timeout=30s;
        server api-3:8080 max_fails=3 fail_timeout=30s;
        keepalive 32;
    }
}
```

### 4.6 Database Issues

#### 4.6.1 Database Performance Diagnostic Tree

```
+-----------------------------------------------------------------------------+
|                   DATABASE PERFORMANCE DIAGNOSTIC TREE                       |
+-----------------------------------------------------------------------------+
|                                                                             |
|  HIGH QUERY LATENCY DETECTED                                                 |
|                          |                                                  |
|                          v                                                  |
|              +-----------------------+                                      |
|              | Check Active          |                                      |
|              | Connections           |                                      |
|              +-----------+-----------+                                      |
|              High /      |      \ Low                                       |
|                         /|\                                                  |
|                        / | \                                                 |
|                       v  v  v                                               |
|              +----------+ +----------+                                       |
|              |Connection| |Check Lock|                                       |
|              |Pool Sat. | |Waits     |                                       |
|              +-----+-----+ +-----+----+                                       |
|                    |            |                                            |
|                    v            v                                            |
|         Increase pool      Row locks, Table locks, Deadlocks                 |
|         size/optimize                                                        |
|         connections                                                          |
|                          v                                                   |
|              +-----------------------+                                       |
|              | Check Query Plans     |                                       |
|              +-----------+-----------+                                       |
|              Bad /       |       \ Good                                      |
|                         /|\                                                  |
|                        / | \                                                 |
|                       v  v  v                                               |
|              +----------+ +----------+                                       |
|              |Missing or| |Statistics|                                       |
|              |Bad Index | |Outdated  |                                       |
|              +-----+-----+ +-----+----+                                       |
|                    |            |                                            |
|                    v            v                                            |
|              Add/Optimize   Analyze, Vacuum                                  |
|              Indexes                                                         |
|                          v                                                   |
|              +-----------------------+                                       |
|              | Check Resource Usage  |                                       |
|              +-----------+-----------+                                       |
|              High /      |       \ Normal                                    |
|                         /|\                                                  |
|                        / | \                                                 |
|                       v  v  v                                               |
|              +----------+ +----------+                                       |
|              |I/O Bound | |CPU Bound |                                       |
|              +-----+-----+ +-----+----+                                       |
|                    |            |                                            |
|                    v            v                                            |
|              Optimize      Optimize                                          |
|              storage/      queries/                                          |
|              queries       add CPU                                           |
|                                                                             |
+-----------------------------------------------------------------------------+
```

#### 4.6.2 Lock and Wait Event Analysis

```sql
-- Current lock waits
SELECT 
    blocked_locks.pid AS blocked_pid,
    blocked_activity.usename AS blocked_user,
    blocking_locks.pid AS blocking_pid,
    blocking_activity.usename AS blocking_user,
    blocked_activity.query AS blocked_statement,
    blocking_activity.query AS blocking_statement
FROM pg_catalog.pg_locks blocked_locks
JOIN pg_catalog.pg_stat_activity blocked_activity ON blocked_activity.pid = blocked_locks.pid
JOIN pg_catalog.pg_locks blocking_locks 
    ON blocking_locks.locktype = blocked_locks.locktype
    AND blocking_locks.relation = blocked_locks.relation
    AND blocking_locks.pid != blocked_locks.pid
JOIN pg_catalog.pg_stat_activity blocking_activity ON blocking_activity.pid = blocking_locks.pid
WHERE NOT blocked_locks.granted;

-- Long-running queries
SELECT 
    pid,
    now() - query_start AS duration,
    state,
    LEFT(query, 100) AS query_snippet
FROM pg_stat_activity
WHERE state = 'active'
  AND query_start < now() - interval '30 seconds'
ORDER BY query_start;

-- Wait events analysis (PostgreSQL 9.6+)
SELECT 
    wait_event_type,
    wait_event,
    COUNT(*)
FROM pg_stat_activity
WHERE wait_event IS NOT NULL
GROUP BY wait_event_type, wait_event
ORDER BY COUNT(*) DESC;
```

---

## 5. Application Tuning

### 5.1 Code Optimization Guidelines

#### 5.1.1 Java Performance Best Practices

```java
/**
 * SMART DAIRY APPLICATION PERFORMANCE GUIDELINES
 * 
 * 1. AVOID N+1 QUERY PROBLEMS
 */

// BAD: N+1 Query Problem
@Service
public class BadOrderService {
    public List<OrderDTO> getOrdersWithItems() {
        List<Order> orders = orderRepository.findAll();
        return orders.stream()
            .map(order -> {
                // This executes a query for EACH order!
                List<OrderItem> items = orderItemRepository.findByOrderId(order.getId());
                return new OrderDTO(order, items);
            })
            .collect(Collectors.toList());
    }
}

// GOOD: Join Fetch or Batch Loading
@Service
public class GoodOrderService {
    
    // Option 1: JPQL Join Fetch
    @Query("SELECT o FROM Order o LEFT JOIN FETCH o.items WHERE o.status = :status")
    List<Order> findOrdersWithItems(@Param("status") OrderStatus status);
    
    // Option 2: Entity Graph
    @EntityGraph(attributePaths = {"items", "items.product", "customer"})
    List<Order> findByStatus(OrderStatus status);
    
    // Option 3: Batch Loading
    @BatchSize(size = 50)
    @OneToMany(mappedBy = "order")
    private List<OrderItem> items;
}

/**
 * 2. STRING CONCATENATION IN LOOPS
 */

// BAD: Creates many intermediate String objects
public String buildReportBad(List<Transaction> transactions) {
    String result = "";
    for (Transaction t : transactions) {
        result += t.getId() + ":" + t.getAmount() + ";";
    }
    return result;
}

// GOOD: Use StringBuilder
public String buildReportGood(List<Transaction> transactions) {
    StringBuilder sb = new StringBuilder(transactions.size() * 50);
    for (Transaction t : transactions) {
        sb.append(t.getId()).append(':').append(t.getAmount()).append(';');
    }
    return sb.toString();
}

/**
 * 3. STREAM USAGE OPTIMIZATION
 */

// BAD: Multiple stream operations
public List<Order> filterAndSortBad(List<Order> orders) {
    return orders.stream()
        .filter(o -> o.getStatus() == OrderStatus.PENDING)
        .collect(Collectors.toList())
        .stream()
        .sorted(Comparator.comparing(Order::getCreatedAt))
        .collect(Collectors.toList());
}

// GOOD: Single stream pipeline
public List<Order> filterAndSortGood(List<Order> orders) {
    return orders.stream()
        .filter(o -> o.getStatus() == OrderStatus.PENDING)
        .sorted(Comparator.comparing(Order::getCreatedAt))
        .collect(Collectors.toList());
}

/**
 * 4. AVOID EXCESSIVE OBJECT CREATION
 */

// BAD: Creates new DateTimeFormatter on every call
public String formatDateBad(LocalDate date) {
    return DateTimeFormatter.ofPattern("yyyy-MM-dd").format(date);
}

// GOOD: Reuse static formatter
private static final DateTimeFormatter DATE_FORMATTER = 
    DateTimeFormatter.ofPattern("yyyy-MM-dd");

public String formatDateGood(LocalDate date) {
    return DATE_FORMATTER.format(date);
}
```

### 5.2 Caching Strategies

#### 5.2.1 Multi-Layer Caching Architecture

```
+------------------------------------------------------------------+
|                    CACHING LAYER ARCHITECTURE                     |
+------------------------------------------------------------------+
|                                                                  |
| +-------------------------------------------------------------+ |
| |                      CLIENT-SIDE CACHE                       | |
| |  - Browser Cache (HTTP Cache-Control)                        | |
| |  - Mobile App Local Storage                                  | |
| |  - CDN Edge Cache                                            | |
| |  TTL: 1 hour - 1 day                                         | |
| +---------------------------+----------------------------------+ |
|                             |                                    |
|                             v                                    |
| +-------------------------------------------------------------+ |
| |                     CDN / EDGE CACHE                         | |
| |  - Static assets (images, CSS, JS)                           | |
| |  - Public API responses                                      | |
| |  TTL: 5 min - 24 hours                                       | |
| +---------------------------+----------------------------------+ |
|                             |                                    |
|                             v                                    |
| +-------------------------------------------------------------+ |
| |                   APPLICATION CACHE                          | |
| |  - Redis Cluster                                             | |
| |  - Caffeine Local Cache                                      | |
| |  - Hibernate Second-Level Cache                              | |
| |  TTL: 30 sec - 10 minutes                                    | |
| +---------------------------+----------------------------------+ |
|                             |                                    |
|                             v                                    |
| +-------------------------------------------------------------+ |
| |                    DATABASE CACHE                            | |
| |  - PostgreSQL Shared Buffers                                 | |
| |  - Query Result Cache                                        | |
| |  - TimescaleDB Chunk Cache                                   | |
| +-------------------------------------------------------------+ |
|                                                                  |
+------------------------------------------------------------------+
```

#### 5.2.2 Redis Caching Implementation

```java
@Configuration
@EnableCaching
public class RedisCacheConfig {
    
    @Bean
    public RedisCacheManager cacheManager(RedisConnectionFactory connectionFactory) {
        
        // Product cache - short TTL, high hit rate
        RedisCacheConfiguration productCache = RedisCacheConfiguration.defaultCacheConfig()
            .entryTtl(Duration.ofMinutes(10))
            .serializeKeysWith(RedisSerializationContext.SerializationPair
                .fromSerializer(new StringRedisSerializer()))
            .serializeValuesWith(RedisSerializationContext.SerializationPair
                .fromSerializer(new GenericJackson2JsonRedisSerializer()));
        
        // User session cache - longer TTL
        RedisCacheConfiguration sessionCache = RedisCacheConfiguration.defaultCacheConfig()
            .entryTtl(Duration.ofHours(2));
        
        // Reference data - long TTL, rarely changes
        RedisCacheConfiguration referenceCache = RedisCacheConfiguration.defaultCacheConfig()
            .entryTtl(Duration.ofHours(24));
        
        return RedisCacheManager.builder(connectionFactory)
            .cacheDefaults(RedisCacheConfiguration.defaultCacheConfig())
            .withCacheConfiguration("products", productCache)
            .withCacheConfiguration("categories", referenceCache)
            .withCacheConfiguration("sessions", sessionCache)
            .transactionAware()
            .build();
    }
}

@Service
public class ProductService {
    
    @Cacheable(value = "products", key = "#id")
    public Product getProduct(Long id) {
        return productRepository.findById(id)
            .orElseThrow(() -> new ProductNotFoundException(id));
    }
    
    @CacheEvict(value = "products", key = "#product.id")
    @CachePut(value = "products", key = "#product.id")
    public Product updateProduct(Product product) {
        return productRepository.save(product);
    }
    
    @CacheEvict(value = "products", allEntries = true)
    public void refreshProductCache() {
        // Method to clear all product cache when bulk updates occur
    }
}
```

### 5.3 Connection Pooling

#### 5.3.1 Database Connection Pool Configuration

```yaml
# application.yml - Database Connection Pool Settings
spring:
  datasource:
    url: jdbc:postgresql://postgres:5432/smart_dairy
    username: ${DB_USERNAME}
    password: ${DB_PASSWORD}
    driver-class-name: org.postgresql.Driver
    
    hikari:
      # Pool sizing (see formula below)
      minimum-idle: 10
      maximum-pool-size: 50
      
      # Connection timeout
      connection-timeout: 30000
      
      # Idle timeout
      idle-timeout: 600000
      
      # Max lifetime
      max-lifetime: 1800000
      
      # Leak detection
      leak-detection-threshold: 60000
      
      # Connection test
      connection-test-query: SELECT 1
      
      # Performance settings
      data-source-properties:
        cachePrepStmts: true
        prepStmtCacheSize: 250
        prepStmtCacheSqlLimit: 2048
        useServerPrepStmts: true
```

#### 5.3.2 Connection Pool Sizing Formula

```
+------------------------------------------------------------------+
|               CONNECTION POOL SIZING CALCULATION                  |
+------------------------------------------------------------------+
|                                                                  |
|  Formula: connections = ((core_count * 2) + effective_spindle)   |
|                                                                  |
|  Smart Dairy Production Environment:                             |
|  - Database Server Specs:                                        |
|    * CPU Cores: 16                                               |
|    * Effective Spindle Count (SSD): 1                            |
|    * Application Servers: 3                                      |
|                                                                  |
|  Calculation:                                                    |
|  Pool Size per App = ((16 * 2) + 1) / 3 = 11 connections         |
|                                                                  |
|  With Headroom:                                                  |
|  - minimum-idle: 10                                              |
|  - maximum-pool-size: 50 (for burst handling)                    |
|                                                                  |
|  Peak Load Consideration:                                        |
|  - Normal: 10-20 connections per app server                     |
|  - Peak (Eid): 40-50 connections per app server                 |
|  - Maximum theoretical: 150 total (3 apps x 50)                 |
|  - PostgreSQL max_connections: 200 (leave headroom for admin)   |
|                                                                  |
+------------------------------------------------------------------+
```

---

## 6. Database Tuning

### 6.1 Query Optimization

#### 6.1.1 Query Performance Analysis

```sql
-- Enable query logging for analysis
ALTER SYSTEM SET log_min_duration_statement = '1000';
SELECT pg_reload_conf();

-- Find slow queries from pg_stat_statements
SELECT 
    queryid,
    LEFT(query, 100) AS query_preview,
    calls,
    ROUND(total_exec_time::numeric, 2) AS total_time_ms,
    ROUND(mean_exec_time::numeric, 2) AS mean_time_ms,
    ROUND(stddev_exec_time::numeric, 2) AS stddev_time_ms,
    rows
FROM pg_stat_statements
WHERE query NOT LIKE '%pg_stat%'
ORDER BY mean_exec_time DESC
LIMIT 20;

-- Queries with high frequency but moderate time
SELECT 
    queryid,
    LEFT(query, 100) AS query_preview,
    calls,
    ROUND(total_exec_time::numeric, 2) AS total_time_ms,
    ROUND((total_exec_time / calls)::numeric, 2) AS avg_time_ms
FROM pg_stat_statements
WHERE calls > 1000
ORDER BY total_exec_time DESC
LIMIT 20;
```

#### 6.1.2 Query Optimization Techniques

```sql
-- 1. LIMIT EARLY (use CTEs to limit before joins)
WITH relevant_products AS (
    SELECT id, name, sku, price, category_id
    FROM products
    WHERE status = 'ACTIVE'
      AND stock_quantity > 0
      AND name ILIKE '%milk%'
    LIMIT 100
)
SELECT 
    p.id, p.name, p.sku, p.price,
    c.name AS category_name
FROM relevant_products p
JOIN categories c ON p.category_id = c.id;

-- 2. AVOID SELECT *
-- BAD
-- SELECT * FROM orders WHERE customer_id = 123;

-- GOOD
SELECT id, order_number, status, total_amount, created_at 
FROM orders 
WHERE customer_id = 123;

-- 3. USE APPROPRIATE JOIN TYPES
-- BAD: Using LEFT JOIN when INNER JOIN is sufficient
-- SELECT c.name, o.order_number FROM customers c
-- LEFT JOIN orders o ON c.id = o.customer_id
-- WHERE o.status = 'COMPLETED';

-- GOOD
SELECT c.name, o.order_number
FROM customers c
JOIN orders o ON c.id = o.customer_id
WHERE o.status = 'COMPLETED';

-- 4. BATCH OPERATIONS
INSERT INTO order_items (order_id, product_id, quantity, price)
VALUES 
    (1, 101, 2, 50.00),
    (1, 102, 1, 75.00),
    (1, 103, 3, 25.00);

-- 5. USE COVERING INDEXES
-- Create index that includes all needed columns
CREATE INDEX CONCURRENTLY idx_orders_customer_covering 
ON orders (customer_id, status) 
INCLUDE (order_number, total_amount, created_at);
```

### 6.2 Index Optimization

#### 6.2.1 Index Analysis Queries

```sql
-- Find unused indexes
SELECT 
    schemaname,
    tablename,
    indexrelname,
    idx_scan,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size,
    indexdef
FROM pg_stat_user_indexes
WHERE idx_scan = 0
  AND indexrelname NOT LIKE 'pg_toast%'
  AND indexrelname NOT LIKE '%pkey%'
  AND pg_relation_size(indexrelid) > 1000000
ORDER BY pg_relation_size(indexrelid) DESC;

-- Find missing indexes (high seq_scan tables)
SELECT 
    schemaname,
    tablename,
    seq_scan,
    seq_tup_read,
    idx_scan,
    n_live_tup,
    ROUND(
        seq_scan::numeric / NULLIF(seq_scan + idx_scan, 0) * 100, 2
    ) AS seq_scan_pct
FROM pg_stat_user_tables
WHERE seq_scan > 100
  AND n_live_tup > 10000
ORDER BY seq_tup_read DESC
LIMIT 20;
```

#### 6.2.2 Index Creation Best Practices

```sql
-- 1. CREATE INDEXES CONCURRENTLY (no table lock)
CREATE INDEX CONCURRENTLY idx_orders_customer_created 
ON orders (customer_id, created_at DESC);

-- 2. PARTIAL INDEXES for filtered queries
CREATE INDEX CONCURRENTLY idx_orders_pending_date 
ON orders (created_at) 
WHERE status = 'PENDING';

-- 3. EXPRESSION INDEXES for case-insensitive searches
CREATE INDEX CONCURRENTLY idx_products_name_lower 
ON products (LOWER(name));

-- Usage:
-- SELECT * FROM products WHERE LOWER(name) = LOWER('Milk');

-- 4. COMPOSITE INDEXES with proper column order
CREATE INDEX CONCURRENTLY idx_orders_status_customer_created 
ON orders (status, customer_id, created_at DESC);

-- 5. INCLUDE INDEXES (covering indexes)
CREATE INDEX CONCURRENTLY idx_orders_customer_covering 
ON orders (customer_id) 
INCLUDE (order_number, total_amount, status, created_at);
```

### 6.3 Configuration Tuning

#### 6.3.1 PostgreSQL Performance Settings

```conf
# postgresql.conf - Smart Dairy Production Settings

# CONNECTIONS
max_connections = 200
superuser_reserved_connections = 5

# MEMORY SETTINGS
shared_buffers = 4GB
effective_cache_size = 12GB
work_mem = 32MB
maintenance_work_mem = 512MB

# WRITE AHEAD LOGGING
wal_buffers = 16MB
max_wal_size = 4GB
min_wal_size = 1GB
checkpoint_completion_target = 0.9
checkpoint_timeout = 10min

# QUERY PLANNER
default_statistics_target = 200
random_page_cost = 1.1
effective_io_concurrency = 200

# PARALLEL QUERY
max_parallel_workers_per_gather = 4
max_parallel_workers = 8
max_parallel_maintenance_workers = 4

# AUTOVACUUM
autovacuum = on
autovacuum_max_workers = 4
autovacuum_naptime = 1min
autovacuum_vacuum_scale_factor = 0.05
autovacuum_analyze_scale_factor = 0.025

# LOGGING
log_min_duration_statement = 1000
log_checkpoints = on
log_connections = on
log_disconnections = on
log_lock_waits = on
log_temp_files = 10MB
```

#### 6.3.2 TimescaleDB-Specific Tuning

```sql
-- TimescaleDB configuration for IoT data

-- Set chunk time interval based on data volume
SELECT set_chunk_time_interval('iot_sensor_data', INTERVAL '1 day');

-- Enable compression
ALTER TABLE iot_sensor_data SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id,sensor_type',
    timescaledb.compress_orderby = 'timestamp DESC'
);

-- Compression policy
SELECT add_compression_policy('iot_sensor_data', INTERVAL '7 days');

-- Data retention policy
SELECT add_retention_policy('iot_sensor_data', INTERVAL '2 years');

-- Continuous aggregate for real-time analytics
CREATE MATERIALIZED VIEW iot_hourly_summary
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 hour', timestamp) AS bucket,
    device_id,
    sensor_type,
    AVG(value) AS avg_value,
    MIN(value) AS min_value,
    MAX(value) AS max_value,
    COUNT(*) AS sample_count
FROM iot_sensor_data
GROUP BY bucket, device_id, sensor_type;

-- Refresh policy for continuous aggregate
SELECT add_continuous_aggregate_policy('iot_hourly_summary',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour');
```

---

## 7. Infrastructure Tuning

### 7.1 Server Optimization

#### 7.1.1 Linux Kernel Parameters

```bash
# /etc/sysctl.conf - Smart Dairy Server Optimization

# NETWORK SETTINGS
net.netfilter.nf_conntrack_max = 2000000
net.netfilter.nf_conntrack_tcp_timeout_established = 600

# TCP performance tuning
net.core.somaxconn = 65535
net.core.netdev_max_backlog = 5000
net.ipv4.tcp_max_syn_backlog = 65535
net.ipv4.tcp_fin_timeout = 30
net.ipv4.tcp_keepalive_time = 1200
net.ipv4.tcp_max_tw_buckets = 5000
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_mem = 50576 64768 98152
net.ipv4.tcp_rmem = 4096 87380 16777216
net.ipv4.tcp_wmem = 4096 65536 16777216
net.ipv4.tcp_congestion_control = bbr

# Virtual memory settings
vm.swappiness = 10
vm.dirty_ratio = 15
vm.dirty_background_ratio = 5
vm.vfs_cache_pressure = 50

# File system settings
fs.file-max = 2097152
fs.nr_open = 2097152

# Apply settings
sysctl -p
```

#### 7.1.2 File Descriptors and Limits

```bash
# /etc/security/limits.conf - Smart Dairy Limits
# Database server
postgres    soft    nofile    65535
postgres    hard    nofile    65535
postgres    soft    nproc     65535
postgres    hard    nproc     65535

# Application server
appuser     soft    nofile    65535
appuser     hard    nofile    65535
appuser     soft    nproc     65535
appuser     hard    nproc     65535

# Nginx
nginx       soft    nofile    65535
nginx       hard    nofile    65535

# System-wide limits
*           soft    nofile    65535
*           hard    nofile    65535
```

### 7.2 Network Optimization

#### 7.2.1 CDN Configuration

```yaml
# CDN Configuration for Smart Dairy Static Assets
cdn:
  provider: cloudflare
  zones:
    - name: static.smartdairy.com.bd
      origin: s3.smartdairy.internal
      
      caching_rules:
        - pattern: "*.jpg,*.jpeg,*.png,*.gif,*.webp"
          ttl: 2592000
          browser_ttl: 2592000
          
        - pattern: "*.v*.css,*.v*.js,*.bundle.*"
          ttl: 31536000
          browser_ttl: 31536000
          
        - pattern: "/api/v1/public/*"
          ttl: 300
          browser_ttl: 60
          
      optimization:
        minify_css: true
        minify_js: true
        brotli_compression: true
        image_optimization: true
```

#### 7.2.2 Load Balancer Configuration

```nginx
# nginx-upstream.conf - Load Balancer Configuration

upstream smart_dairy_api {
    least_conn;
    
    server api-1.internal:8080 weight=5 max_fails=3 fail_timeout=30s;
    server api-2.internal:8080 weight=5 max_fails=3 fail_timeout=30s;
    server api-3.internal:8080 weight=5 max_fails=3 fail_timeout=30s;
    
    server api-backup.internal:8080 backup;
    
    keepalive 100;
    keepalive_requests 1000;
    keepalive_timeout 60s;
}

# Rate limiting zones
limit_req_zone $binary_remote_addr zone=api_limit:10m rate=100r/s;
limit_req_zone $binary_remote_addr zone=auth_limit:10m rate=10r/m;

server {
    listen 80;
    server_name api.smartdairy.com.bd;
    
    proxy_connect_timeout 5s;
    proxy_send_timeout 60s;
    proxy_read_timeout 60s;
    
    limit_req zone=api_limit burst=200 nodelay;
    
    location /health {
        proxy_pass http://smart_dairy_api;
        limit_req off;
        access_log off;
    }
    
    location /api/v1/auth/ {
        proxy_pass http://smart_dairy_api;
        limit_req zone=auth_limit burst=20 nodelay;
    }
    
    location /api/ {
        proxy_pass http://smart_dairy_api;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### 7.3 Storage Optimization

#### 7.3.1 Storage Class Strategy

| Component | Storage Class | Size | IOPS | Purpose |
|-----------|--------------|------|------|---------|
| PostgreSQL Primary | fast-ssd | 500GB | 10,000 | Transaction data |
| PostgreSQL Replica | fast-ssd | 500GB | 10,000 | Read replicas |
| TimescaleDB | fast-ssd | 2TB | 16,000 | IoT time-series data |
| Redis | fast-ssd | 50GB | 3,000 | Session/cache store |
| File Uploads | standard-ssd | 1TB | 3,000 | User uploads |
| Backup Storage | archive | 5TB | 250 | Backups, logs |

---

## 8. Load Testing Procedures

### 8.1 Load Testing Strategy

#### 8.1.1 Test Types and Objectives

```
+------------------------------------------------------------------+
|                    LOAD TESTING PYRAMID                           |
+------------------------------------------------------------------+
|                            /\                                     |
|                           /  \                                    |
|                          /SPIKE\        Sudden 10x traffic        |
|                         /--------\       Test auto-scaling        |
|                        /          \                               |
|                       / STRESS TEST \   Find breaking point       |
|                      /----------------\  Gradually increase       |
|                     /                  \   until failure          |
|                    /    SOAK TEST       \ Extended duration test  |
|                   /------------------------\ Memory leaks, GC     |
|                  /                          \   issues            |
|                 /         LOAD TEST          \ Expected peak test |
|                /--------------------------------\                 |
|               /              SMOKE TEST          \ Basic sanity  |
|              /--------------------------------------\             |
|             /                                        \            |
+------------------------------------------------------------------+
```

#### 8.1.2 Load Testing Scenarios

| Test Type | Duration | Users | Ramp-up | Objective |
|-----------|----------|-------|---------|-----------|
| Smoke | 5 min | 10 | 1 min | Basic functionality |
| Load | 30 min | 5,000 | 10 min | Normal peak validation |
| Stress | 2 hours | 10,000-25,000 | 30 min | Breaking point discovery |
| Soak | 24 hours | 5,000 | 10 min | Stability verification |
| Spike | 15 min | 25,000 | 2 min | Auto-scaling test |

### 8.2 K6 Load Testing Scripts

#### 8.2.1 E-commerce Load Test Script

```javascript
// e-commerce-load-test.js
import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { Rate, Trend, Counter } from 'k6/metrics';
import { randomIntBetween } from 'https://jslib.k6.io/k6-utils/1.2.0/index.js';

const errorRate = new Rate('errors');
const apiLatency = new Trend('api_latency');
const checkoutSuccess = new Counter('checkout_success');

export const options = {
  stages: [
    { duration: '5m', target: 100 },
    { duration: '10m', target: 100 },
    { duration: '5m', target: 500 },
    { duration: '20m', target: 500 },
    { duration: '5m', target: 1000 },
    { duration: '10m', target: 1000 },
    { duration: '5m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],
    http_req_failed: ['rate<0.01'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'https://api.smartdairy.com.bd';

export default function () {
  group('Browse Products', () => {
    const productsRes = http.get(`${BASE_URL}/api/v1/products?page=0&size=20`);
    check(productsRes, {
      'products status is 200': (r) => r.status === 200,
      'products response time < 200ms': (r) => r.timings.duration < 200,
    });
    errorRate.add(productsRes.status !== 200);
    apiLatency.add(productsRes.timings.duration);
    sleep(randomIntBetween(1, 3));
  });
  
  group('Add to Cart', () => {
    const cartPayload = JSON.stringify({
      productId: randomIntBetween(1, 1000),
      quantity: randomIntBetween(1, 5),
    });
    
    const cartRes = http.post(`${BASE_URL}/api/v1/cart/items`, cartPayload, {
      headers: { 'Content-Type': 'application/json' },
    });
    
    check(cartRes, {
      'add to cart status is 200': (r) => r.status === 200,
    });
    errorRate.add(cartRes.status !== 200);
    sleep(randomIntBetween(3, 8));
  });
}
```

### 8.3 Test Execution and Analysis

#### 8.3.1 Test Execution Checklist

- [ ] Pre-test environment validated (staging mirrors production)
- [ ] Database seeded with realistic data volumes
- [ ] Monitoring dashboards confirmed operational
- [ ] Alert notifications verified
- [ ] Rollback procedure documented
- [ ] Team on standby for incident response
- [ ] Test scripts validated with smoke test
- [ ] Baseline metrics captured

---

## 9. Capacity Planning Integration

### 9.1 Capacity Planning Framework

### 9.2 Growth Metrics and Headroom

| Metric | Current | 6-Month Forecast | 12-Month Forecast | Headroom Required |
|--------|---------|------------------|-------------------|-------------------|
| Daily Active Users | 10,000 | 25,000 | 50,000 | 100,000 |
| API Requests/Day | 5M | 15M | 40M | 100M |
| Database Size | 100GB | 250GB | 600GB | 1.5TB |
| IoT Messages/Day | 1M | 5M | 15M | 50M |
| Storage (Files) | 500GB | 1.5TB | 4TB | 10TB |
| Peak Concurrent Users | 2,000 | 8,000 | 20,000 | 50,000 |

### 9.3 Scaling Triggers

| Component | Current Capacity | Scale Trigger | Action |
|-----------|-----------------|---------------|--------|
| Web Servers | 3 pods | CPU > 60% for 5 min | +1 pod |
| API Servers | 3 pods | Response time > 300ms | +2 pods |
| Database | 16 vCPU, 64GB | Connection > 150 | Read replica +1 |
| Redis | 2GB | Memory > 70% | Scale to 4GB |
| Storage | 1TB | Usage > 70% | Add 1TB |

---

## 10. Performance Incident Response

### 10.1 Incident Severity Matrix

| Severity | Response Time | Example Scenarios | Team Response |
|----------|---------------|-------------------|---------------|
| P1 (Critical) | 5 minutes | Complete outage, >50% error rate, payment failure | Full team mobilized |
| P2 (High) | 15 minutes | >30% performance degradation, >10% error rate | Primary on-call + backup |
| P3 (Medium) | 1 hour | >20% degradation, some features slow | Primary on-call |
| P4 (Low) | 4 hours | Minor performance issues, warnings only | Next business day |

### 10.2 Incident Response Workflow

```
+-----------------------------------------------------------------------------+
|                     PERFORMANCE INCIDENT RESPONSE FLOW                       |
+-----------------------------------------------------------------------------+
|                                                                             |
|  ALERT TRIGGERED                                                             |
|       |                                                                      |
|       v                                                                      |
|  +-------------------------+                                                |
|  | Acknowledge Alert       | <- 5 min for P1, 15 min for P2                 |
|  | (Slack/PagerDuty)       |                                                |
|  +------------+------------+                                                |
|               |                                                             |
|               v                                                             |
|  +-------------------------+                                                |
|  | Initial Assessment      |                                                |
|  | - Check dashboards      |                                                |
|  | - Verify alert validity |                                                |
|  | - Identify scope        |                                                |
|  +------------+------------+                                                |
|               |                                                             |
|      +--------+--------+                                                     |
|      |                |                                                     |
|      v                v                                                     |
|  +--------+     +-----------------+                                         |
|  | FALSE  |     | CONFIRMED ISSUE |                                         |
|  | ALERT  |     +--------+--------+                                         |
|  +---+----+              |                                                  |
|      |                   v                                                  |
|      |          +-------------------------+                                 |
|      |          | Classify Severity       |                                 |
|      |          | P1/P2/P3/P4             |                                 |
|      |          +------------+------------+                                 |
|      |                       |                                              |
|      |          +------------+------------+                                 |
|      |          |            |            |                                 |
|      |          v            v            v                                 |
|      |       +-----+    +--------+   +--------+                            |
|      |       |  P1 |    |  P2    |   | P3/P4  |                            |
|      |       +--+--+    +---+----+   +---+----+                            |
|      |          |           |            |                                  |
|      |          v           v            v                                  |
|      |    +----------+ +--------+  +--------+                             |
|      |    |Escalate  | |Page    |  |Log and |                             |
|      |    |War room  | |on-call |  |schedule|                             |
|      |    +----------+ +--------+  +--------+                             |
|      |                                                                      |
|      +--------------------------------------------------+                   |
|                                                         |                   |
|                                                         v                   |
|  +--------------------------------------------------------------+          |
|  | DIAGNOSIS & MITIGATION                                        |          |
|  | - Follow diagnostic decision tree (Section 4)                 |          |
|  | - Apply quick fixes (cache clear, restart, scale up)          |          |
|  | - Implement circuit breakers if needed                        |          |
|  +--------------------------------------------------------------+          |
|                               |                                             |
|                               v                                             |
|  +--------------------------------------------------------------+          |
|  | RESOLUTION & POST-INCIDENT                                    |          |
|  | - Verify service recovery                                     |          |
|  | - Document timeline and actions                               |          |
|  | - Schedule post-mortem within 48 hours                        |          |
|  | - Update runbook if needed                                    |          |
|  +--------------------------------------------------------------+          |
|                                                                             |
+-----------------------------------------------------------------------------+
```

### 10.3 Quick Mitigation Playbook

| Symptom | Quick Fix | Permanent Solution |
|---------|-----------|-------------------|
| High CPU | Scale up pods, restart service | Profile and optimize code |
| High Memory | Restart service, clear cache | Fix memory leak |
| Slow queries | Kill long queries, add hint | Add/optimize index |
| DB connections exhausted | Increase pool size temporarily | Optimize connection usage |
| Cache miss storm | Warm cache manually | Implement cache warming |
| API overload | Enable rate limiting, circuit breaker | Scale infrastructure |

---

## 11. Appendices

### Appendix A: Tuning Scripts

#### A.1 Performance Monitoring Script

```bash
#!/bin/bash
# performance-monitor.sh - Continuous performance monitoring

LOG_DIR="/var/log/smart-dairy/performance"
mkdir -p "$LOG_DIR"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
LOG_FILE="$LOG_DIR/perf_${TIMESTAMP}.log"

echo "=== Smart Dairy Performance Snapshot ===" >> "$LOG_FILE"
echo "Timestamp: $(date)" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"

# System metrics
echo "--- CPU Usage ---" >> "$LOG_FILE"
top -bn1 | grep "Cpu(s)" >> "$LOG_FILE"

echo "" >> "$LOG_FILE"
echo "--- Memory Usage ---" >> "$LOG_FILE"
free -h >> "$LOG_FILE"

echo "" >> "$LOG_FILE"
echo "--- Disk I/O ---" >> "$LOG_FILE"
iostat -x 1 1 >> "$LOG_FILE"

# PostgreSQL metrics
if command -v psql &> /dev/null; then
    echo "" >> "$LOG_FILE"
    echo "--- PostgreSQL Active Connections ---" >> "$LOG_FILE"
    sudo -u postgres psql -c "SELECT count(*) FROM pg_stat_activity WHERE state = 'active';" >> "$LOG_FILE"
fi

# Java application metrics
JAVA_PID=$(pgrep -f "smart-dairy-api" | head -1)
if [ -n "$JAVA_PID" ]; then
    echo "" >> "$LOG_FILE"
    echo "--- Java Heap Usage ---" >> "$LOG_FILE"
    jstat -gc "$JAVA_PID" | tail -1 >> "$LOG_FILE"
fi

# Cleanup old logs (keep 7 days)
find "$LOG_DIR" -name "perf_*.log" -mtime +7 -delete

echo "Performance snapshot saved to $LOG_FILE"
```

#### A.2 Database Performance Check

```sql
-- db-health-check.sql
-- Comprehensive database health check

-- 1. Connection health
SELECT 
    'Connections' AS check_name,
    count(*) AS total,
    count(*) FILTER (WHERE state = 'active') AS active,
    count(*) FILTER (WHERE state = 'idle') AS idle,
    CASE 
        WHEN count(*) > 150 THEN 'WARNING'
        WHEN count(*) > 180 THEN 'CRITICAL'
        ELSE 'OK'
    END AS status
FROM pg_stat_activity;

-- 2. Cache health
SELECT 
    'Cache Hit Ratio' AS check_name,
    datname,
    ROUND(blks_hit * 100.0 / NULLIF(blks_hit + blks_read, 0), 2) AS hit_ratio,
    CASE 
        WHEN blks_hit * 100.0 / NULLIF(blks_hit + blks_read, 0) < 95 
        THEN 'WARNING'
        ELSE 'OK'
    END AS status
FROM pg_stat_database
WHERE datname NOT IN ('template0', 'template1');

-- 3. Database bloat
SELECT 
    'Table Bloat' AS check_name,
    schemaname,
    tablename,
    n_dead_tup,
    ROUND(n_dead_tup * 100.0 / NULLIF(n_live_tup + n_dead_tup, 0), 2) AS bloat_pct
FROM pg_stat_user_tables
WHERE n_dead_tup > 1000
ORDER BY n_dead_tup DESC
LIMIT 5;

-- 4. Slow query detection
SELECT 
    'Slow Queries' AS check_name,
    queryid,
    LEFT(query, 60) AS query_preview,
    calls,
    ROUND(mean_exec_time::numeric, 2) AS avg_ms,
    CASE 
        WHEN mean_exec_time > 1000 THEN 'CRITICAL'
        WHEN mean_exec_time > 500 THEN 'WARNING'
        ELSE 'OK'
    END AS status
FROM pg_stat_statements
WHERE query NOT LIKE '%pg_stat%'
ORDER BY mean_exec_time DESC
LIMIT 5;
```

#### A.3 Redis Performance Script

```bash
#!/bin/bash
# redis-check.sh - Redis health and performance check

echo "=== REDIS PERFORMANCE CHECK ==="
echo "Timestamp: $(date)"
echo ""

# Server info
echo "--- Server Info ---"
redis-cli INFO server | grep -E "redis_version|uptime_in_seconds|tcp_port"

# Memory usage
echo ""
echo "--- Memory Usage ---"
redis-cli INFO memory | grep -E "used_memory:|used_memory_human:|mem_fragmentation_ratio:"

# Client connections
echo ""
echo "--- Client Connections ---"
redis-cli INFO clients | grep -E "connected_clients|blocked_clients"

# Key statistics
echo ""
echo "--- Key Statistics ---"
redis-cli INFO keyspace
redis-cli DBSIZE

# Slow log
echo ""
echo "--- Slow Log (Last 5) ---"
redis-cli SLOWLOG GET 5

# Persistence status
echo ""
echo "--- Persistence ---"
redis-cli INFO persistence | grep -E "rdb_last_save_status|aof_last_write_status"
```

### Appendix B: Quick Reference Tables

#### B.1 JVM Memory Settings Reference

| Heap Size | Xms | Xmx | Metaspace | Use Case |
|-----------|-----|-----|-----------|----------|
| 2GB | 2g | 2g | 256m | Development |
| 4GB | 4g | 4g | 256m | Small production |
| 8GB | 6g | 7g | 512m | Standard production |
| 16GB | 8g | 12g | 512m | Large production |
| 32GB | 16g | 24g | 1g | High-throughput |

#### B.2 PostgreSQL Configuration Quick Reference

| Memory | shared_buffers | effective_cache_size | work_mem | maintenance_work_mem |
|--------|---------------|---------------------|----------|---------------------|
| 8GB | 2GB | 6GB | 16MB | 256MB |
| 16GB | 4GB | 12GB | 32MB | 512MB |
| 32GB | 8GB | 24GB | 64MB | 1GB |
| 64GB | 16GB | 48GB | 128MB | 2GB |

#### B.3 Alert Threshold Quick Reference

| Metric | Info | Warning | Critical |
|--------|------|---------|----------|
| CPU | >50% | >70% | >85% |
| Memory | >60% | >75% | >90% |
| Disk Usage | >70% | >80% | >90% |
| Response Time (p95) | >300ms | >500ms | >1000ms |
| Error Rate | >0.1% | >0.5% | >1% |
| DB Connections | >120 | >150 | >170 |
| Cache Hit Ratio | <98% | <95% | <90% |

### Appendix C: Troubleshooting Workflows

#### C.1 Slow API Response Troubleshooting

```
1. CHECK MONITORING DASHBOARD
   - Is it affecting all endpoints or specific ones?
     + ALL ENDPOINTS -> Check infrastructure (CPU, Memory, DB)
     + SPECIFIC -> Check endpoint code and dependencies

2. CHECK APPLICATION LOGS
   - Look for errors, warnings, slow query logs
     + ERRORS -> Fix error condition
     + SLOW QUERIES -> Go to DB troubleshooting

3. CHECK INFRASTRUCTURE METRICS
   - CPU/Memory/Disk/Network
     + HIGH CPU -> Profile application, check GC
     + HIGH MEMORY -> Check for leaks, heap dump
     + HIGH DISK I/O -> Check DB queries, add indexes
     + NETWORK ISSUES -> Check connectivity, latency

4. CHECK EXTERNAL DEPENDENCIES
   - Payment gateway, SMS, third-party APIs
     + SLOW EXTERNAL -> Implement circuit breaker, timeout

5. CHECK CACHE EFFECTIVENESS
   - Cache hit rate < 90%?
     + WARM CACHE -> Preload hot data
     + SIZE ISSUE -> Increase cache size
     + INVALIDATION -> Review TTL settings
```

#### C.2 Database Performance Troubleshooting

```
1. CHECK ACTIVE CONNECTIONS
   - > 150 connections?
     + YES -> Check connection pool, kill idle, scale up
     + NO -> Continue

2. CHECK FOR BLOCKING QUERIES
   - Any locks/blocking?
     + YES -> Identify blocker, kill if necessary
     + NO -> Continue

3. CHECK SLOW QUERY LOG
   - Queries > 1 second?
     + YES -> Analyze execution plan, optimize
     + NO -> Continue

4. CHECK RESOURCE USAGE
   - High CPU/I/O/Memory?
     + HIGH CPU -> Optimize queries, add indexes
     + HIGH I/O -> Check for missing indexes, vacuum
     + HIGH MEMORY -> Check work_mem, shared_buffers

5. CHECK CACHE HIT RATIO
   - < 95%?
     + YES -> Increase shared_buffers, analyze tables
     + NO -> Continue
```

### Appendix D: Performance Baseline Template

```yaml
# baseline-config.yaml
baseline:
  established_date: "2026-01-15"
  review_schedule: "quarterly"
  
  metrics:
    api_response_time:
      p50: 120ms
      p95: 200ms
      p99: 350ms
      
    throughput:
      requests_per_second: 2000
      peak_rps: 10000
      
    database:
      avg_query_time: 12ms
      cache_hit_ratio: 98.5
      connections_normal: 50
      connections_peak: 150
      
    infrastructure:
      cpu_avg: 35%
      cpu_peak: 65%
      memory_avg: 55%
      memory_peak: 75%
      disk_io_avg: 15%
      disk_io_peak: 40%
      
    mobile_app:
      launch_time: 1100ms
      api_response: 270ms
      crash_rate: 0.09%
      
    iot_platform:
      ingestion_latency: 85ms
      messages_per_minute: 10000
      device_success_rate: 99.8%

  seasonal_adjustments:
    eid_season:
      multiplier: 4.0
      duration: "10 days"
      
    ramadan:
      multiplier: 1.5
      duration: "30 days"
      
    morning_collection:
      time: "06:00-08:00"
      multiplier: 5.0
      
    month_end:
      day: "last 3 days"
      multiplier: 2.0
```

---

## Document Control

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Performance Engineer | _________________ | _______ |
| Reviewer | IT Director | _________________ | _______ |
| Approver | CTO | _________________ | _______ |

### Distribution

This document is distributed to:
- DevOps Team
- Database Administration Team
- Application Development Teams
- Infrastructure Team
- IT Support Team
- Management Team

### Related Documents

- D-013: Auto-scaling Configuration
- D-009: Log Aggregation Analysis (ELK)
- B-010: Caching Strategy Document
- G-005: Test Plan Phase 4 - Optimization
- C-008: TimescaleDB Implementation Guide
- L-002: IT Support Procedures
- L-003: Incident Management Process

---

*End of Document L-010: Performance Tuning Runbook*

**Smart Dairy Ltd. - Smart Web Portal System**
**Document Version: 1.0 | Date: January 31, 2026**
