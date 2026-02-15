# Milestone 121: Performance Baseline & Monitoring

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 121 of 150 (1 of 10 in Phase 13)                                       |
| **Title**        | Performance Baseline & Monitoring                                      |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 601-605 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead)        |

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

Establish comprehensive performance monitoring infrastructure and create a detailed baseline of current system performance across all components (backend, frontend, database, APIs). This baseline will serve as the foundation for all optimization efforts in Phase 13.

### 1.2 Objectives

1. Set up Application Performance Monitoring (APM) tools
2. Implement custom Prometheus metrics for business-critical operations
3. Configure frontend performance monitoring with Web Vitals
4. Implement backend profiling for CPU and memory analysis
5. Set up database query profiling and slow query logging
6. Create load testing scenarios for baseline measurement
7. Generate comprehensive performance baseline report
8. Identify top optimization opportunities

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| APM Configuration (New Relic)     | Dev 2  | Docker/Config     | 601     |
| Prometheus Custom Metrics         | Dev 2  | Python module     | 601     |
| Web Vitals Integration            | Dev 3  | JavaScript module | 601     |
| Backend Profiler Module           | Dev 1  | Python module     | 602     |
| Database Query Profiler           | Dev 1  | Python/SQL        | 602     |
| React Performance Profiler        | Dev 3  | React HOC         | 602     |
| Performance Analysis Scripts      | Dev 1  | Python scripts    | 603     |
| k6 Load Testing Scenarios         | Dev 2  | JavaScript        | 603     |
| Lighthouse CI Configuration       | Dev 3  | JSON/Shell        | 603     |
| Performance Baseline Report       | All    | Markdown/PDF      | 605     |
| Optimization Roadmap              | All    | Markdown          | 605     |

### 1.4 Prerequisites

- Phase 12 complete and stable
- Production-like environment available for testing
- Access to monitoring tool accounts (New Relic/Datadog)
- Historical application logs available
- Database access with superuser privileges for profiling

### 1.5 Success Criteria

- [ ] APM dashboard showing real-time application metrics
- [ ] Custom Prometheus metrics exposed at /metrics endpoint
- [ ] Web Vitals data being collected and reported
- [ ] Backend profiling capable of generating CPU/memory reports
- [ ] Database slow query log capturing queries >100ms
- [ ] Load testing scenarios covering all critical user journeys
- [ ] Baseline report documenting current P95 metrics
- [ ] Top 10 optimization opportunities identified and prioritized

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-PERF-001 | RFP    | Page load time <2 seconds                | 601-605 | Baseline measurement        |
| RFP-PERF-002 | RFP    | Support 1000+ concurrent users           | 603     | Load test scenarios         |
| BRD-PERF-001 | BRD    | Real-time dashboard <3s refresh          | 601     | APM monitoring              |
| SRS-PERF-001 | SRS    | API response time <200ms (P95)           | 601-605 | Baseline measurement        |
| SRS-PERF-002 | SRS    | Database query time <50ms (P90)          | 602     | Query profiler              |
| SRS-PERF-003 | SRS    | Lighthouse score >90                     | 603     | Lighthouse CI               |
| IMPL-PERF-01 | Guide  | APM tool integration                     | 601     | New Relic setup             |
| IMPL-PERF-02 | Guide  | Custom metrics collection                | 601     | Prometheus metrics          |

---

## 3. Day-by-Day Breakdown

### Day 601 - APM Setup & Monitoring Infrastructure

**Objective:** Deploy APM tools and establish core monitoring infrastructure for application-wide performance visibility.

#### Dev 1 - Backend Lead (8h)

**Task 1: Odoo Performance Instrumentation (4h)**

```python
# odoo/addons/smart_dairy_performance/models/performance_tracker.py
from odoo import models, api
import time
import functools
import logging
from prometheus_client import Counter, Histogram, Gauge

_logger = logging.getLogger(__name__)

# Prometheus metrics for Odoo
ODOO_REQUEST_COUNT = Counter(
    'odoo_request_total',
    'Total Odoo requests',
    ['model', 'method', 'status']
)

ODOO_REQUEST_DURATION = Histogram(
    'odoo_request_duration_seconds',
    'Odoo request duration',
    ['model', 'method'],
    buckets=[0.01, 0.05, 0.1, 0.25, 0.5, 1.0, 2.5, 5.0, 10.0]
)

ODOO_ACTIVE_USERS = Gauge(
    'odoo_active_users',
    'Number of active Odoo users'
)

ODOO_DB_QUERY_DURATION = Histogram(
    'odoo_db_query_duration_seconds',
    'Database query duration',
    ['query_type'],
    buckets=[0.001, 0.005, 0.01, 0.05, 0.1, 0.5, 1.0]
)


def track_performance(func):
    """Decorator to track method performance"""
    @functools.wraps(func)
    def wrapper(self, *args, **kwargs):
        model_name = self._name if hasattr(self, '_name') else 'unknown'
        method_name = func.__name__
        start_time = time.time()
        status = 'success'

        try:
            result = func(self, *args, **kwargs)
            return result
        except Exception as e:
            status = 'error'
            raise
        finally:
            duration = time.time() - start_time
            ODOO_REQUEST_COUNT.labels(
                model=model_name,
                method=method_name,
                status=status
            ).inc()
            ODOO_REQUEST_DURATION.labels(
                model=model_name,
                method=method_name
            ).observe(duration)

            if duration > 1.0:
                _logger.warning(
                    f"Slow method: {model_name}.{method_name} took {duration:.2f}s"
                )

    return wrapper


class PerformanceTracker(models.AbstractModel):
    _name = 'performance.tracker'
    _description = 'Performance Tracking Mixin'

    @api.model
    def track_query(self, query_type, duration):
        """Track database query performance"""
        ODOO_DB_QUERY_DURATION.labels(query_type=query_type).observe(duration)

    @api.model
    def update_active_users(self, count):
        """Update active user gauge"""
        ODOO_ACTIVE_USERS.set(count)
```

**Task 2: Create Performance Monitoring API Endpoints (4h)**

```python
# odoo/addons/smart_dairy_performance/controllers/metrics.py
from odoo import http
from odoo.http import request
from prometheus_client import generate_latest, CONTENT_TYPE_LATEST
import json
import psutil
import time

class PerformanceMetricsController(http.Controller):

    @http.route('/metrics', type='http', auth='none', csrf=False)
    def prometheus_metrics(self):
        """Expose Prometheus metrics endpoint"""
        return request.make_response(
            generate_latest(),
            headers=[('Content-Type', CONTENT_TYPE_LATEST)]
        )

    @http.route('/api/v1/performance/health', type='json', auth='none')
    def health_check(self):
        """Comprehensive health check endpoint"""
        start_time = time.time()

        # Check database connectivity
        db_status = self._check_database()

        # Check Redis connectivity
        redis_status = self._check_redis()

        # System metrics
        system_metrics = {
            'cpu_percent': psutil.cpu_percent(interval=0.1),
            'memory_percent': psutil.virtual_memory().percent,
            'disk_percent': psutil.disk_usage('/').percent,
        }

        response_time = time.time() - start_time

        return {
            'status': 'healthy' if db_status and redis_status else 'degraded',
            'timestamp': time.time(),
            'response_time_ms': round(response_time * 1000, 2),
            'checks': {
                'database': db_status,
                'redis': redis_status,
            },
            'system': system_metrics
        }

    def _check_database(self):
        try:
            request.env.cr.execute("SELECT 1")
            return True
        except Exception:
            return False

    def _check_redis(self):
        try:
            from odoo.tools import config
            import redis
            redis_url = config.get('redis_url', 'redis://localhost:6379')
            r = redis.from_url(redis_url)
            r.ping()
            return True
        except Exception:
            return False

    @http.route('/api/v1/performance/stats', type='json', auth='user')
    def performance_stats(self):
        """Get current performance statistics"""
        return {
            'database': self._get_db_stats(),
            'cache': self._get_cache_stats(),
            'api': self._get_api_stats(),
        }

    def _get_db_stats(self):
        request.env.cr.execute("""
            SELECT
                count(*) as total_connections,
                sum(case when state = 'active' then 1 else 0 end) as active,
                sum(case when state = 'idle' then 1 else 0 end) as idle
            FROM pg_stat_activity
            WHERE datname = current_database()
        """)
        row = request.env.cr.fetchone()
        return {
            'total_connections': row[0],
            'active_connections': row[1],
            'idle_connections': row[2]
        }

    def _get_cache_stats(self):
        try:
            from odoo.tools import config
            import redis
            r = redis.from_url(config.get('redis_url', 'redis://localhost:6379'))
            info = r.info('memory')
            return {
                'used_memory_mb': round(info['used_memory'] / 1024 / 1024, 2),
                'hit_rate': r.info('stats').get('keyspace_hits', 0)
            }
        except Exception:
            return {'status': 'unavailable'}

    def _get_api_stats(self):
        # Return aggregated API stats from Prometheus metrics
        return {'status': 'see /metrics endpoint'}
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: New Relic APM Setup (4h)**

```yaml
# docker-compose.monitoring.yml
version: '3.8'

services:
  newrelic-infrastructure:
    image: newrelic/infrastructure:latest
    container_name: smart-dairy-newrelic-infra
    cap_add:
      - SYS_PTRACE
    network_mode: host
    pid: host
    privileged: true
    volumes:
      - "/:/host:ro"
      - "/var/run/docker.sock:/var/run/docker.sock"
    environment:
      - NRIA_LICENSE_KEY=${NEW_RELIC_LICENSE_KEY}
      - NRIA_DISPLAY_NAME=smart-dairy-platform
      - NRIA_VERBOSE=0
    restart: unless-stopped

  prometheus:
    image: prom/prometheus:v2.45.0
    container_name: smart-dairy-prometheus
    volumes:
      - ./config/prometheus.yml:/etc/prometheus/prometheus.yml
      - prometheus_data:/prometheus
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.path=/prometheus'
      - '--storage.tsdb.retention.time=30d'
      - '--web.enable-lifecycle'
    ports:
      - "9090:9090"
    restart: unless-stopped

  grafana:
    image: grafana/grafana:10.0.0
    container_name: smart-dairy-grafana
    volumes:
      - grafana_data:/var/lib/grafana
      - ./config/grafana/provisioning:/etc/grafana/provisioning
      - ./config/grafana/dashboards:/var/lib/grafana/dashboards
    environment:
      - GF_SECURITY_ADMIN_USER=${GRAFANA_ADMIN_USER:-admin}
      - GF_SECURITY_ADMIN_PASSWORD=${GRAFANA_ADMIN_PASSWORD:-admin}
      - GF_INSTALL_PLUGINS=grafana-clock-panel,grafana-piechart-panel
    ports:
      - "3001:3000"
    restart: unless-stopped

volumes:
  prometheus_data:
  grafana_data:
```

```yaml
# config/prometheus.yml
global:
  scrape_interval: 15s
  evaluation_interval: 15s

alerting:
  alertmanagers:
    - static_configs:
        - targets: []

rule_files:
  - "alert_rules.yml"

scrape_configs:
  - job_name: 'prometheus'
    static_configs:
      - targets: ['localhost:9090']

  - job_name: 'odoo'
    static_configs:
      - targets: ['odoo:8069']
    metrics_path: '/metrics'
    scrape_interval: 10s

  - job_name: 'node-exporter'
    static_configs:
      - targets: ['node-exporter:9100']

  - job_name: 'postgres'
    static_configs:
      - targets: ['postgres-exporter:9187']

  - job_name: 'redis'
    static_configs:
      - targets: ['redis-exporter:9121']

  - job_name: 'nginx'
    static_configs:
      - targets: ['nginx-exporter:9113']
```

**Task 2: Custom Prometheus Metrics Module (4h)**

```python
# backend/src/monitoring/metrics.py
from prometheus_client import Counter, Histogram, Gauge, Summary
import time
import functools

# Business metrics
ORDER_COUNTER = Counter(
    'smart_dairy_orders_total',
    'Total orders placed',
    ['order_type', 'payment_method', 'status']
)

ORDER_VALUE = Histogram(
    'smart_dairy_order_value_bdt',
    'Order value distribution in BDT',
    buckets=[100, 500, 1000, 2500, 5000, 10000, 25000, 50000]
)

MILK_PRODUCTION = Gauge(
    'smart_dairy_milk_production_liters',
    'Current daily milk production in liters'
)

ACTIVE_SUBSCRIPTIONS = Gauge(
    'smart_dairy_active_subscriptions',
    'Number of active subscriptions',
    ['plan_type']
)

# API Performance metrics
API_REQUEST_DURATION = Histogram(
    'smart_dairy_api_request_duration_seconds',
    'API request duration',
    ['endpoint', 'method', 'status_code'],
    buckets=[0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1.0, 2.5, 5.0]
)

API_REQUEST_SIZE = Summary(
    'smart_dairy_api_request_size_bytes',
    'API request size',
    ['endpoint', 'method']
)

API_RESPONSE_SIZE = Summary(
    'smart_dairy_api_response_size_bytes',
    'API response size',
    ['endpoint', 'method']
)

# Database metrics
DB_QUERY_DURATION = Histogram(
    'smart_dairy_db_query_duration_seconds',
    'Database query duration',
    ['operation', 'table'],
    buckets=[0.001, 0.005, 0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1.0]
)

DB_CONNECTION_POOL = Gauge(
    'smart_dairy_db_pool_connections',
    'Database connection pool status',
    ['state']
)

# Cache metrics
CACHE_OPERATIONS = Counter(
    'smart_dairy_cache_operations_total',
    'Cache operations',
    ['operation', 'result']
)

CACHE_LATENCY = Histogram(
    'smart_dairy_cache_latency_seconds',
    'Cache operation latency',
    ['operation'],
    buckets=[0.0001, 0.0005, 0.001, 0.005, 0.01, 0.05, 0.1]
)


def track_api_request(endpoint: str):
    """Decorator to track API request metrics"""
    def decorator(func):
        @functools.wraps(func)
        async def wrapper(*args, **kwargs):
            start_time = time.time()
            status_code = 200

            try:
                result = await func(*args, **kwargs)
                return result
            except Exception as e:
                status_code = 500
                raise
            finally:
                duration = time.time() - start_time
                API_REQUEST_DURATION.labels(
                    endpoint=endpoint,
                    method=kwargs.get('method', 'GET'),
                    status_code=status_code
                ).observe(duration)

        return wrapper
    return decorator


def track_db_query(operation: str, table: str):
    """Context manager for tracking database queries"""
    class QueryTracker:
        def __init__(self):
            self.start_time = None

        def __enter__(self):
            self.start_time = time.time()
            return self

        def __exit__(self, exc_type, exc_val, exc_tb):
            duration = time.time() - self.start_time
            DB_QUERY_DURATION.labels(
                operation=operation,
                table=table
            ).observe(duration)
            return False

    return QueryTracker()
```

#### Dev 3 - Frontend Lead (8h)

**Task 1: Web Vitals Integration (4h)**

```typescript
// frontend/src/monitoring/webVitals.ts
import { onCLS, onFCP, onFID, onLCP, onTTFB, onINP, Metric } from 'web-vitals';

interface PerformanceMetric {
  name: string;
  value: number;
  rating: 'good' | 'needs-improvement' | 'poor';
  delta: number;
  id: string;
  navigationType: string;
  page: string;
  timestamp: number;
}

class WebVitalsMonitor {
  private metrics: Map<string, PerformanceMetric[]> = new Map();
  private apiEndpoint: string;
  private batchSize: number = 10;
  private flushInterval: number = 30000; // 30 seconds

  constructor(apiEndpoint: string = '/api/v1/metrics/web-vitals') {
    this.apiEndpoint = apiEndpoint;
    this.initializeMonitoring();
    this.startPeriodicFlush();
  }

  private initializeMonitoring(): void {
    // Core Web Vitals
    onCLS(this.handleMetric.bind(this));
    onFID(this.handleMetric.bind(this));
    onLCP(this.handleMetric.bind(this));

    // Additional metrics
    onFCP(this.handleMetric.bind(this));
    onTTFB(this.handleMetric.bind(this));
    onINP(this.handleMetric.bind(this));

    // Custom performance marks
    this.observeLongTasks();
    this.observeResourceTiming();
  }

  private handleMetric(metric: Metric): void {
    const performanceMetric: PerformanceMetric = {
      name: metric.name,
      value: metric.value,
      rating: metric.rating,
      delta: metric.delta,
      id: metric.id,
      navigationType: metric.navigationType,
      page: window.location.pathname,
      timestamp: Date.now()
    };

    const existing = this.metrics.get(metric.name) || [];
    existing.push(performanceMetric);
    this.metrics.set(metric.name, existing);

    // Log to console in development
    if (process.env.NODE_ENV === 'development') {
      console.log(`[WebVitals] ${metric.name}:`, metric.value, metric.rating);
    }

    // Send immediately for poor ratings
    if (metric.rating === 'poor') {
      this.sendMetric(performanceMetric);
    }

    // Batch send when threshold reached
    if (existing.length >= this.batchSize) {
      this.flushMetrics(metric.name);
    }
  }

  private observeLongTasks(): void {
    if ('PerformanceObserver' in window) {
      const observer = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
          const metric: PerformanceMetric = {
            name: 'LongTask',
            value: entry.duration,
            rating: entry.duration > 100 ? 'poor' : 'needs-improvement',
            delta: entry.duration,
            id: `lt-${Date.now()}`,
            navigationType: 'long-task',
            page: window.location.pathname,
            timestamp: Date.now()
          };

          this.sendMetric(metric);
        }
      });

      observer.observe({ entryTypes: ['longtask'] });
    }
  }

  private observeResourceTiming(): void {
    if ('PerformanceObserver' in window) {
      const observer = new PerformanceObserver((list) => {
        const entries = list.getEntries() as PerformanceResourceTiming[];

        entries.forEach(entry => {
          // Track slow resources (>500ms)
          if (entry.duration > 500) {
            console.warn(`[Performance] Slow resource: ${entry.name} (${entry.duration.toFixed(0)}ms)`);
          }
        });
      });

      observer.observe({ entryTypes: ['resource'] });
    }
  }

  private async sendMetric(metric: PerformanceMetric): Promise<void> {
    try {
      await fetch(this.apiEndpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(metric),
        keepalive: true
      });
    } catch (error) {
      console.error('[WebVitals] Failed to send metric:', error);
    }
  }

  private async flushMetrics(metricName?: string): Promise<void> {
    const metricsToSend: PerformanceMetric[] = [];

    if (metricName) {
      const metrics = this.metrics.get(metricName) || [];
      metricsToSend.push(...metrics);
      this.metrics.set(metricName, []);
    } else {
      this.metrics.forEach((metrics, name) => {
        metricsToSend.push(...metrics);
        this.metrics.set(name, []);
      });
    }

    if (metricsToSend.length > 0) {
      try {
        await fetch(`${this.apiEndpoint}/batch`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ metrics: metricsToSend }),
          keepalive: true
        });
      } catch (error) {
        console.error('[WebVitals] Failed to flush metrics:', error);
      }
    }
  }

  private startPeriodicFlush(): void {
    setInterval(() => this.flushMetrics(), this.flushInterval);

    // Flush on page unload
    window.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'hidden') {
        this.flushMetrics();
      }
    });
  }

  public getMetricsSummary(): Record<string, any> {
    const summary: Record<string, any> = {};

    this.metrics.forEach((metrics, name) => {
      if (metrics.length > 0) {
        const values = metrics.map(m => m.value);
        summary[name] = {
          count: values.length,
          avg: values.reduce((a, b) => a + b, 0) / values.length,
          min: Math.min(...values),
          max: Math.max(...values),
          p75: this.percentile(values, 75),
          p95: this.percentile(values, 95)
        };
      }
    });

    return summary;
  }

  private percentile(arr: number[], p: number): number {
    const sorted = arr.slice().sort((a, b) => a - b);
    const index = Math.ceil((p / 100) * sorted.length) - 1;
    return sorted[index];
  }
}

export const webVitalsMonitor = new WebVitalsMonitor();
export default webVitalsMonitor;
```

**Task 2: Performance Dashboard Component (4h)**

```tsx
// frontend/src/components/admin/PerformanceDashboard.tsx
import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Statistic, Table, Alert, Progress } from 'antd';
import { Line, Gauge } from '@ant-design/plots';
import { useQuery } from '@tanstack/react-query';

interface PerformanceMetrics {
  api: {
    avgResponseTime: number;
    p95ResponseTime: number;
    requestsPerSecond: number;
    errorRate: number;
  };
  database: {
    avgQueryTime: number;
    slowQueries: number;
    activeConnections: number;
  };
  frontend: {
    lcp: number;
    fid: number;
    cls: number;
    lighthouseScore: number;
  };
  system: {
    cpuUsage: number;
    memoryUsage: number;
    diskUsage: number;
  };
}

const fetchPerformanceMetrics = async (): Promise<PerformanceMetrics> => {
  const response = await fetch('/api/v1/performance/dashboard');
  if (!response.ok) throw new Error('Failed to fetch metrics');
  return response.json();
};

const PerformanceDashboard: React.FC = () => {
  const { data: metrics, isLoading, error } = useQuery({
    queryKey: ['performance-metrics'],
    queryFn: fetchPerformanceMetrics,
    refetchInterval: 10000, // Refresh every 10 seconds
  });

  const getStatusColor = (value: number, thresholds: [number, number]): string => {
    if (value <= thresholds[0]) return '#52c41a'; // Green
    if (value <= thresholds[1]) return '#faad14'; // Yellow
    return '#f5222d'; // Red
  };

  const getWebVitalStatus = (name: string, value: number): 'good' | 'needs-improvement' | 'poor' => {
    const thresholds: Record<string, [number, number]> = {
      LCP: [2500, 4000],
      FID: [100, 300],
      CLS: [0.1, 0.25],
    };

    const [good, poor] = thresholds[name] || [0, 0];
    if (value <= good) return 'good';
    if (value <= poor) return 'needs-improvement';
    return 'poor';
  };

  if (isLoading) return <div>Loading performance metrics...</div>;
  if (error) return <Alert type="error" message="Failed to load metrics" />;
  if (!metrics) return null;

  return (
    <div className="performance-dashboard">
      <h1>Performance Dashboard</h1>

      {/* API Performance */}
      <Row gutter={[16, 16]}>
        <Col span={24}>
          <Card title="API Performance" className="metrics-card">
            <Row gutter={16}>
              <Col span={6}>
                <Statistic
                  title="Avg Response Time"
                  value={metrics.api.avgResponseTime}
                  suffix="ms"
                  valueStyle={{
                    color: getStatusColor(metrics.api.avgResponseTime, [100, 200])
                  }}
                />
              </Col>
              <Col span={6}>
                <Statistic
                  title="P95 Response Time"
                  value={metrics.api.p95ResponseTime}
                  suffix="ms"
                  valueStyle={{
                    color: getStatusColor(metrics.api.p95ResponseTime, [200, 500])
                  }}
                />
              </Col>
              <Col span={6}>
                <Statistic
                  title="Requests/sec"
                  value={metrics.api.requestsPerSecond}
                  precision={1}
                />
              </Col>
              <Col span={6}>
                <Statistic
                  title="Error Rate"
                  value={metrics.api.errorRate}
                  suffix="%"
                  precision={2}
                  valueStyle={{
                    color: getStatusColor(metrics.api.errorRate, [0.1, 1])
                  }}
                />
              </Col>
            </Row>
          </Card>
        </Col>
      </Row>

      {/* Core Web Vitals */}
      <Row gutter={[16, 16]} style={{ marginTop: 16 }}>
        <Col span={24}>
          <Card title="Core Web Vitals" className="metrics-card">
            <Row gutter={16}>
              <Col span={8}>
                <div className="web-vital">
                  <h4>LCP (Largest Contentful Paint)</h4>
                  <Progress
                    type="dashboard"
                    percent={(2500 - Math.min(metrics.frontend.lcp, 2500)) / 25}
                    format={() => `${metrics.frontend.lcp}ms`}
                    status={getWebVitalStatus('LCP', metrics.frontend.lcp) === 'good' ? 'success' : 'exception'}
                  />
                  <p>Target: &lt; 2500ms</p>
                </div>
              </Col>
              <Col span={8}>
                <div className="web-vital">
                  <h4>FID (First Input Delay)</h4>
                  <Progress
                    type="dashboard"
                    percent={(100 - Math.min(metrics.frontend.fid, 100))}
                    format={() => `${metrics.frontend.fid}ms`}
                    status={getWebVitalStatus('FID', metrics.frontend.fid) === 'good' ? 'success' : 'exception'}
                  />
                  <p>Target: &lt; 100ms</p>
                </div>
              </Col>
              <Col span={8}>
                <div className="web-vital">
                  <h4>CLS (Cumulative Layout Shift)</h4>
                  <Progress
                    type="dashboard"
                    percent={(0.1 - Math.min(metrics.frontend.cls, 0.1)) * 1000}
                    format={() => metrics.frontend.cls.toFixed(3)}
                    status={getWebVitalStatus('CLS', metrics.frontend.cls) === 'good' ? 'success' : 'exception'}
                  />
                  <p>Target: &lt; 0.1</p>
                </div>
              </Col>
            </Row>
          </Card>
        </Col>
      </Row>

      {/* System Resources */}
      <Row gutter={[16, 16]} style={{ marginTop: 16 }}>
        <Col span={8}>
          <Card title="CPU Usage">
            <Progress
              type="circle"
              percent={metrics.system.cpuUsage}
              strokeColor={getStatusColor(metrics.system.cpuUsage, [60, 80])}
            />
          </Card>
        </Col>
        <Col span={8}>
          <Card title="Memory Usage">
            <Progress
              type="circle"
              percent={metrics.system.memoryUsage}
              strokeColor={getStatusColor(metrics.system.memoryUsage, [70, 85])}
            />
          </Card>
        </Col>
        <Col span={8}>
          <Card title="Disk Usage">
            <Progress
              type="circle"
              percent={metrics.system.diskUsage}
              strokeColor={getStatusColor(metrics.system.diskUsage, [70, 85])}
            />
          </Card>
        </Col>
      </Row>
    </div>
  );
};

export default PerformanceDashboard;
```

#### Day 601 Deliverables

- [ ] Odoo performance instrumentation module deployed
- [ ] Performance metrics API endpoints operational
- [ ] Docker monitoring stack configured
- [ ] Prometheus scraping Odoo metrics
- [ ] Web Vitals monitoring active on frontend
- [ ] Performance dashboard component implemented

---

### Day 602 - Backend & Database Profiling

**Objective:** Implement comprehensive profiling tools for backend application and database performance analysis.

#### Dev 1 - Backend Lead (8h)

**Task 1: Python Application Profiler (4h)**

```python
# backend/src/profiling/application_profiler.py
import cProfile
import pstats
import io
import time
import tracemalloc
import functools
import threading
from typing import Callable, Dict, Any, Optional
from dataclasses import dataclass
from datetime import datetime
import json
import os

@dataclass
class ProfileResult:
    function_name: str
    total_time: float
    cumulative_time: float
    call_count: int
    memory_before: int
    memory_after: int
    memory_peak: int
    timestamp: datetime

    def to_dict(self) -> Dict[str, Any]:
        return {
            'function_name': self.function_name,
            'total_time_ms': round(self.total_time * 1000, 2),
            'cumulative_time_ms': round(self.cumulative_time * 1000, 2),
            'call_count': self.call_count,
            'memory_before_mb': round(self.memory_before / 1024 / 1024, 2),
            'memory_after_mb': round(self.memory_after / 1024 / 1024, 2),
            'memory_peak_mb': round(self.memory_peak / 1024 / 1024, 2),
            'timestamp': self.timestamp.isoformat()
        }


class ApplicationProfiler:
    """Comprehensive application profiler for CPU and memory analysis"""

    def __init__(self, output_dir: str = './profiles'):
        self.output_dir = output_dir
        self.profiles: Dict[str, ProfileResult] = {}
        self._lock = threading.Lock()
        os.makedirs(output_dir, exist_ok=True)

    def profile_function(self, func: Callable) -> Callable:
        """Decorator to profile a function"""
        @functools.wraps(func)
        def wrapper(*args, **kwargs):
            # Start memory tracking
            tracemalloc.start()
            memory_before = tracemalloc.get_traced_memory()[0]

            # CPU profiling
            profiler = cProfile.Profile()
            profiler.enable()
            start_time = time.perf_counter()

            try:
                result = func(*args, **kwargs)
                return result
            finally:
                end_time = time.perf_counter()
                profiler.disable()

                # Get memory stats
                memory_after, memory_peak = tracemalloc.get_traced_memory()
                tracemalloc.stop()

                # Parse profiler stats
                stream = io.StringIO()
                stats = pstats.Stats(profiler, stream=stream)
                stats.sort_stats('cumulative')

                # Get function-specific stats
                func_stats = stats.stats.get(
                    (func.__code__.co_filename,
                     func.__code__.co_firstlineno,
                     func.__name__),
                    (0, 0, 0, 0, {})
                )

                profile_result = ProfileResult(
                    function_name=func.__name__,
                    total_time=end_time - start_time,
                    cumulative_time=func_stats[3] if func_stats else 0,
                    call_count=func_stats[0] if func_stats else 1,
                    memory_before=memory_before,
                    memory_after=memory_after,
                    memory_peak=memory_peak,
                    timestamp=datetime.now()
                )

                with self._lock:
                    self.profiles[func.__name__] = profile_result

                # Log slow functions
                if profile_result.total_time > 1.0:
                    self._log_slow_function(profile_result, stats)

        return wrapper

    def _log_slow_function(self, result: ProfileResult, stats: pstats.Stats):
        """Log details for slow functions"""
        filename = f"{self.output_dir}/slow_{result.function_name}_{datetime.now().strftime('%Y%m%d_%H%M%S')}.txt"

        with open(filename, 'w') as f:
            f.write(f"Slow Function Profile: {result.function_name}\n")
            f.write(f"Total Time: {result.total_time:.4f}s\n")
            f.write(f"Memory Peak: {result.memory_peak / 1024 / 1024:.2f}MB\n\n")

            stream = io.StringIO()
            stats.stream = stream
            stats.print_stats(30)
            f.write(stream.getvalue())

    def start_continuous_profiling(self, interval: float = 60.0):
        """Start continuous profiling in background"""
        def profile_loop():
            while True:
                self.capture_snapshot()
                time.sleep(interval)

        thread = threading.Thread(target=profile_loop, daemon=True)
        thread.start()

    def capture_snapshot(self) -> Dict[str, Any]:
        """Capture current memory snapshot"""
        tracemalloc.start()
        snapshot = tracemalloc.take_snapshot()
        tracemalloc.stop()

        top_stats = snapshot.statistics('lineno')[:20]

        return {
            'timestamp': datetime.now().isoformat(),
            'top_allocations': [
                {
                    'file': str(stat.traceback),
                    'size_mb': stat.size / 1024 / 1024,
                    'count': stat.count
                }
                for stat in top_stats
            ]
        }

    def get_profile_summary(self) -> Dict[str, Any]:
        """Get summary of all profiled functions"""
        with self._lock:
            return {
                name: result.to_dict()
                for name, result in self.profiles.items()
            }

    def export_profiles(self, filename: str = 'profile_summary.json'):
        """Export all profiles to JSON file"""
        filepath = f"{self.output_dir}/{filename}"
        with open(filepath, 'w') as f:
            json.dump(self.get_profile_summary(), f, indent=2)
        return filepath


# Global profiler instance
profiler = ApplicationProfiler()
```

**Task 2: Database Query Profiler (4h)**

```python
# backend/src/profiling/database_profiler.py
import time
import re
import logging
from typing import Dict, List, Any, Optional
from dataclasses import dataclass, field
from datetime import datetime
from collections import defaultdict
import threading
import json

_logger = logging.getLogger(__name__)

@dataclass
class QueryProfile:
    sql: str
    normalized_sql: str
    duration_ms: float
    rows_affected: int
    timestamp: datetime
    explain_plan: Optional[str] = None
    stack_trace: Optional[str] = None


@dataclass
class QueryStats:
    sql: str
    count: int = 0
    total_duration_ms: float = 0.0
    min_duration_ms: float = float('inf')
    max_duration_ms: float = 0.0
    avg_duration_ms: float = 0.0

    def update(self, duration_ms: float):
        self.count += 1
        self.total_duration_ms += duration_ms
        self.min_duration_ms = min(self.min_duration_ms, duration_ms)
        self.max_duration_ms = max(self.max_duration_ms, duration_ms)
        self.avg_duration_ms = self.total_duration_ms / self.count


class DatabaseQueryProfiler:
    """Profile and analyze database query performance"""

    SLOW_QUERY_THRESHOLD_MS = 100

    def __init__(self):
        self.query_stats: Dict[str, QueryStats] = defaultdict(
            lambda: QueryStats(sql='')
        )
        self.slow_queries: List[QueryProfile] = []
        self.n_plus_one_candidates: Dict[str, List[QueryProfile]] = defaultdict(list)
        self._lock = threading.Lock()

    def normalize_query(self, sql: str) -> str:
        """Normalize SQL query for grouping similar queries"""
        # Remove specific values
        normalized = re.sub(r"'[^']*'", "'?'", sql)
        normalized = re.sub(r'\b\d+\b', '?', normalized)
        normalized = re.sub(r'\$\d+', '$?', normalized)
        # Remove extra whitespace
        normalized = ' '.join(normalized.split())
        return normalized

    def extract_table_name(self, sql: str) -> Optional[str]:
        """Extract main table name from SQL query"""
        patterns = [
            r'FROM\s+(["\w]+)',
            r'INTO\s+(["\w]+)',
            r'UPDATE\s+(["\w]+)',
            r'DELETE\s+FROM\s+(["\w]+)'
        ]

        for pattern in patterns:
            match = re.search(pattern, sql, re.IGNORECASE)
            if match:
                return match.group(1).strip('"')
        return None

    def record_query(self, sql: str, duration_ms: float,
                     rows_affected: int = 0,
                     explain_plan: str = None,
                     stack_trace: str = None):
        """Record a query execution"""
        normalized = self.normalize_query(sql)

        profile = QueryProfile(
            sql=sql,
            normalized_sql=normalized,
            duration_ms=duration_ms,
            rows_affected=rows_affected,
            timestamp=datetime.now(),
            explain_plan=explain_plan,
            stack_trace=stack_trace
        )

        with self._lock:
            # Update stats
            if normalized not in self.query_stats:
                self.query_stats[normalized] = QueryStats(sql=normalized)
            self.query_stats[normalized].update(duration_ms)

            # Track slow queries
            if duration_ms > self.SLOW_QUERY_THRESHOLD_MS:
                self.slow_queries.append(profile)
                if len(self.slow_queries) > 1000:
                    self.slow_queries = self.slow_queries[-500:]

                _logger.warning(
                    f"Slow query ({duration_ms:.1f}ms): {sql[:200]}..."
                )

            # Detect N+1 patterns
            self._detect_n_plus_one(profile)

    def _detect_n_plus_one(self, profile: QueryProfile):
        """Detect potential N+1 query patterns"""
        table = self.extract_table_name(profile.sql)
        if not table:
            return

        key = f"{table}:{profile.normalized_sql[:100]}"
        self.n_plus_one_candidates[key].append(profile)

        # Clean old entries (keep last 100 per pattern)
        if len(self.n_plus_one_candidates[key]) > 100:
            self.n_plus_one_candidates[key] = self.n_plus_one_candidates[key][-50:]

    def get_n_plus_one_patterns(self, threshold: int = 10) -> List[Dict[str, Any]]:
        """Get queries that might be N+1 patterns"""
        patterns = []

        with self._lock:
            for key, queries in self.n_plus_one_candidates.items():
                if len(queries) >= threshold:
                    # Check if queries happened within short time window
                    time_window = (
                        queries[-1].timestamp - queries[0].timestamp
                    ).total_seconds()

                    if time_window < 5:  # Within 5 seconds
                        patterns.append({
                            'pattern': key,
                            'count': len(queries),
                            'time_window_seconds': time_window,
                            'total_duration_ms': sum(q.duration_ms for q in queries),
                            'sample_query': queries[0].sql[:200]
                        })

        return sorted(patterns, key=lambda x: x['count'], reverse=True)

    def get_slow_queries(self, limit: int = 50) -> List[Dict[str, Any]]:
        """Get recent slow queries"""
        with self._lock:
            return [
                {
                    'sql': q.sql[:500],
                    'duration_ms': q.duration_ms,
                    'timestamp': q.timestamp.isoformat(),
                    'explain_plan': q.explain_plan
                }
                for q in sorted(
                    self.slow_queries,
                    key=lambda x: x.duration_ms,
                    reverse=True
                )[:limit]
            ]

    def get_query_stats(self, limit: int = 50) -> List[Dict[str, Any]]:
        """Get query statistics sorted by total time"""
        with self._lock:
            stats = sorted(
                self.query_stats.values(),
                key=lambda x: x.total_duration_ms,
                reverse=True
            )[:limit]

            return [
                {
                    'sql': s.sql[:300],
                    'count': s.count,
                    'total_duration_ms': round(s.total_duration_ms, 2),
                    'avg_duration_ms': round(s.avg_duration_ms, 2),
                    'min_duration_ms': round(s.min_duration_ms, 2),
                    'max_duration_ms': round(s.max_duration_ms, 2)
                }
                for s in stats
            ]

    def generate_report(self) -> Dict[str, Any]:
        """Generate comprehensive query profiling report"""
        return {
            'generated_at': datetime.now().isoformat(),
            'summary': {
                'total_unique_queries': len(self.query_stats),
                'total_slow_queries': len(self.slow_queries),
                'n_plus_one_patterns': len(self.get_n_plus_one_patterns())
            },
            'top_slow_queries': self.get_slow_queries(20),
            'query_statistics': self.get_query_stats(30),
            'n_plus_one_patterns': self.get_n_plus_one_patterns(5)
        }


# Global profiler instance
db_profiler = DatabaseQueryProfiler()
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: PostgreSQL Query Analysis Setup (4h)**

```sql
-- database/scripts/setup_query_profiling.sql

-- Enable pg_stat_statements extension
CREATE EXTENSION IF NOT EXISTS pg_stat_statements;

-- Create performance analysis views
CREATE OR REPLACE VIEW v_slow_queries AS
SELECT
    calls,
    round(total_exec_time::numeric, 2) as total_time_ms,
    round(mean_exec_time::numeric, 2) as avg_time_ms,
    round(max_exec_time::numeric, 2) as max_time_ms,
    round(stddev_exec_time::numeric, 2) as stddev_ms,
    rows,
    round(100.0 * shared_blks_hit / nullif(shared_blks_hit + shared_blks_read, 0), 2) as cache_hit_pct,
    query
FROM pg_stat_statements
WHERE calls > 10
ORDER BY total_exec_time DESC
LIMIT 100;

-- Create view for query patterns by table
CREATE OR REPLACE VIEW v_query_patterns AS
SELECT
    regexp_replace(
        regexp_replace(query, '''[^'']*''', '''?''', 'g'),
        '\d+', '?', 'g'
    ) as normalized_query,
    sum(calls) as total_calls,
    round(sum(total_exec_time)::numeric, 2) as total_time_ms,
    round(avg(mean_exec_time)::numeric, 2) as avg_time_ms
FROM pg_stat_statements
GROUP BY 1
HAVING sum(calls) > 5
ORDER BY total_time_ms DESC
LIMIT 50;

-- Create function to analyze table statistics
CREATE OR REPLACE FUNCTION analyze_table_performance(table_name text)
RETURNS TABLE (
    metric text,
    value text
) AS $$
BEGIN
    RETURN QUERY
    SELECT 'Total Rows'::text,
           (SELECT reltuples::bigint::text FROM pg_class WHERE relname = table_name);

    RETURN QUERY
    SELECT 'Table Size'::text,
           pg_size_pretty(pg_table_size(table_name::regclass));

    RETURN QUERY
    SELECT 'Index Size'::text,
           pg_size_pretty(pg_indexes_size(table_name::regclass));

    RETURN QUERY
    SELECT 'Sequential Scans'::text,
           seq_scan::text
    FROM pg_stat_user_tables
    WHERE relname = table_name;

    RETURN QUERY
    SELECT 'Index Scans'::text,
           idx_scan::text
    FROM pg_stat_user_tables
    WHERE relname = table_name;
END;
$$ LANGUAGE plpgsql;

-- Create function to identify missing indexes
CREATE OR REPLACE FUNCTION identify_missing_indexes()
RETURNS TABLE (
    table_name text,
    seq_scan_count bigint,
    idx_scan_count bigint,
    seq_to_idx_ratio numeric,
    table_size text,
    recommendation text
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        t.relname::text,
        s.seq_scan,
        s.idx_scan,
        round(
            CASE WHEN s.idx_scan = 0 THEN s.seq_scan::numeric
            ELSE s.seq_scan::numeric / s.idx_scan
            END, 2
        ),
        pg_size_pretty(pg_table_size(t.relname::regclass)),
        CASE
            WHEN s.seq_scan > 1000 AND s.idx_scan < 100
            THEN 'HIGH: Consider adding index'
            WHEN s.seq_scan > 100 AND s.idx_scan < 10
            THEN 'MEDIUM: Evaluate query patterns'
            ELSE 'LOW: Monitor'
        END::text
    FROM pg_stat_user_tables s
    JOIN pg_class t ON s.relid = t.oid
    WHERE s.seq_scan > 50
    ORDER BY s.seq_scan DESC
    LIMIT 20;
END;
$$ LANGUAGE plpgsql;

-- Create function to analyze index usage
CREATE OR REPLACE FUNCTION analyze_index_usage()
RETURNS TABLE (
    index_name text,
    table_name text,
    index_size text,
    index_scans bigint,
    tuples_read bigint,
    tuples_fetched bigint,
    usage_status text
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        i.indexrelname::text,
        i.relname::text,
        pg_size_pretty(pg_relation_size(i.indexrelid)),
        i.idx_scan,
        i.idx_tup_read,
        i.idx_tup_fetch,
        CASE
            WHEN i.idx_scan = 0 THEN 'UNUSED - Consider dropping'
            WHEN i.idx_scan < 50 THEN 'LOW USAGE - Evaluate'
            ELSE 'ACTIVE'
        END::text
    FROM pg_stat_user_indexes i
    ORDER BY pg_relation_size(i.indexrelid) DESC
    LIMIT 50;
END;
$$ LANGUAGE plpgsql;
```

**Task 2: Database Monitoring Integration (4h)**

```python
# backend/src/monitoring/database_monitor.py
import psycopg2
from psycopg2.extras import RealDictCursor
import time
from typing import Dict, List, Any
import logging
from dataclasses import dataclass
from prometheus_client import Gauge, Histogram

_logger = logging.getLogger(__name__)

# Prometheus metrics for database
DB_CONNECTIONS = Gauge(
    'smart_dairy_db_connections',
    'Database connections by state',
    ['state']
)

DB_TABLE_SIZE = Gauge(
    'smart_dairy_db_table_size_bytes',
    'Database table size',
    ['table_name']
)

DB_QUERY_TIME = Histogram(
    'smart_dairy_db_monitored_query_seconds',
    'Monitored query execution time',
    ['query_type']
)


class DatabaseMonitor:
    """Monitor database health and performance"""

    def __init__(self, connection_string: str):
        self.connection_string = connection_string

    def get_connection(self):
        return psycopg2.connect(self.connection_string)

    def get_connection_stats(self) -> Dict[str, int]:
        """Get current connection statistics"""
        with self.get_connection() as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute("""
                    SELECT state, count(*) as count
                    FROM pg_stat_activity
                    WHERE datname = current_database()
                    GROUP BY state
                """)

                stats = {row['state'] or 'null': row['count'] for row in cur.fetchall()}

                # Update Prometheus metrics
                for state, count in stats.items():
                    DB_CONNECTIONS.labels(state=state).set(count)

                return stats

    def get_slow_queries(self, min_duration_ms: float = 100) -> List[Dict[str, Any]]:
        """Get slow queries from pg_stat_statements"""
        with self.get_connection() as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute("""
                    SELECT
                        query,
                        calls,
                        round(total_exec_time::numeric, 2) as total_time_ms,
                        round(mean_exec_time::numeric, 2) as avg_time_ms,
                        round(max_exec_time::numeric, 2) as max_time_ms,
                        rows
                    FROM pg_stat_statements
                    WHERE mean_exec_time > %s
                    ORDER BY total_exec_time DESC
                    LIMIT 50
                """, (min_duration_ms,))

                return [dict(row) for row in cur.fetchall()]

    def get_table_stats(self) -> List[Dict[str, Any]]:
        """Get statistics for all user tables"""
        with self.get_connection() as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute("""
                    SELECT
                        relname as table_name,
                        n_live_tup as row_count,
                        n_dead_tup as dead_rows,
                        pg_size_pretty(pg_table_size(relid)) as table_size,
                        pg_table_size(relid) as table_size_bytes,
                        seq_scan,
                        idx_scan,
                        last_vacuum,
                        last_autovacuum,
                        last_analyze
                    FROM pg_stat_user_tables
                    ORDER BY pg_table_size(relid) DESC
                    LIMIT 50
                """)

                results = [dict(row) for row in cur.fetchall()]

                # Update Prometheus metrics
                for table in results:
                    DB_TABLE_SIZE.labels(
                        table_name=table['table_name']
                    ).set(table['table_size_bytes'])

                return results

    def get_index_stats(self) -> List[Dict[str, Any]]:
        """Get index usage statistics"""
        with self.get_connection() as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute("""
                    SELECT * FROM analyze_index_usage()
                """)
                return [dict(row) for row in cur.fetchall()]

    def get_missing_index_recommendations(self) -> List[Dict[str, Any]]:
        """Get recommendations for missing indexes"""
        with self.get_connection() as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute("""
                    SELECT * FROM identify_missing_indexes()
                """)
                return [dict(row) for row in cur.fetchall()]

    def get_lock_info(self) -> List[Dict[str, Any]]:
        """Get current lock information"""
        with self.get_connection() as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute("""
                    SELECT
                        pg_class.relname,
                        pg_locks.mode,
                        pg_locks.granted,
                        pg_stat_activity.query,
                        pg_stat_activity.query_start,
                        age(now(), pg_stat_activity.query_start) as duration
                    FROM pg_locks
                    JOIN pg_class ON pg_locks.relation = pg_class.oid
                    JOIN pg_stat_activity ON pg_locks.pid = pg_stat_activity.pid
                    WHERE pg_class.relnamespace = 'public'::regnamespace
                    AND NOT pg_locks.granted
                    ORDER BY pg_stat_activity.query_start
                """)
                return [dict(row) for row in cur.fetchall()]

    def generate_health_report(self) -> Dict[str, Any]:
        """Generate comprehensive database health report"""
        return {
            'timestamp': time.time(),
            'connections': self.get_connection_stats(),
            'table_stats': self.get_table_stats(),
            'index_stats': self.get_index_stats(),
            'slow_queries': self.get_slow_queries(),
            'missing_indexes': self.get_missing_index_recommendations(),
            'locks': self.get_lock_info()
        }
```

#### Dev 3 - Frontend Lead (8h)

**Task 1: React Performance Profiler HOC (4h)**

```tsx
// frontend/src/profiling/ReactProfiler.tsx
import React, { Profiler, ProfilerOnRenderCallback, useEffect, useRef } from 'react';

interface ComponentMetrics {
  id: string;
  phase: 'mount' | 'update';
  actualDuration: number;
  baseDuration: number;
  startTime: number;
  commitTime: number;
  renderCount: number;
  totalDuration: number;
  avgDuration: number;
  maxDuration: number;
  slowRenders: number;
}

class ReactProfilerService {
  private metrics: Map<string, ComponentMetrics> = new Map();
  private renderThreshold: number = 16; // 16ms for 60fps
  private apiEndpoint: string;

  constructor(apiEndpoint: string = '/api/v1/metrics/react') {
    this.apiEndpoint = apiEndpoint;
  }

  handleRender: ProfilerOnRenderCallback = (
    id,
    phase,
    actualDuration,
    baseDuration,
    startTime,
    commitTime
  ) => {
    const existing = this.metrics.get(id) || {
      id,
      phase: 'mount',
      actualDuration: 0,
      baseDuration: 0,
      startTime: 0,
      commitTime: 0,
      renderCount: 0,
      totalDuration: 0,
      avgDuration: 0,
      maxDuration: 0,
      slowRenders: 0
    };

    const updated: ComponentMetrics = {
      ...existing,
      phase,
      actualDuration,
      baseDuration,
      startTime,
      commitTime,
      renderCount: existing.renderCount + 1,
      totalDuration: existing.totalDuration + actualDuration,
      avgDuration: (existing.totalDuration + actualDuration) / (existing.renderCount + 1),
      maxDuration: Math.max(existing.maxDuration, actualDuration),
      slowRenders: existing.slowRenders + (actualDuration > this.renderThreshold ? 1 : 0)
    };

    this.metrics.set(id, updated);

    // Log slow renders
    if (actualDuration > this.renderThreshold) {
      console.warn(
        `[ReactProfiler] Slow render: ${id} (${phase}) took ${actualDuration.toFixed(2)}ms`
      );
    }

    // Send metrics periodically
    if (updated.renderCount % 10 === 0) {
      this.sendMetrics(id, updated);
    }
  };

  private async sendMetrics(id: string, metrics: ComponentMetrics): Promise<void> {
    try {
      await fetch(this.apiEndpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ componentId: id, metrics })
      });
    } catch (error) {
      // Silently fail - don't impact user experience
    }
  }

  getMetrics(): Map<string, ComponentMetrics> {
    return new Map(this.metrics);
  }

  getSlowComponents(threshold: number = 16): ComponentMetrics[] {
    return Array.from(this.metrics.values())
      .filter(m => m.avgDuration > threshold)
      .sort((a, b) => b.avgDuration - a.avgDuration);
  }

  getMostRenderedComponents(limit: number = 10): ComponentMetrics[] {
    return Array.from(this.metrics.values())
      .sort((a, b) => b.renderCount - a.renderCount)
      .slice(0, limit);
  }

  generateReport(): object {
    return {
      timestamp: Date.now(),
      totalComponents: this.metrics.size,
      slowComponents: this.getSlowComponents(),
      mostRendered: this.getMostRenderedComponents(),
      allMetrics: Array.from(this.metrics.values())
    };
  }

  reset(): void {
    this.metrics.clear();
  }
}

export const profilerService = new ReactProfilerService();

// HOC to wrap components with profiler
export function withProfiler<P extends object>(
  Component: React.ComponentType<P>,
  id?: string
): React.FC<P> {
  const componentId = id || Component.displayName || Component.name || 'Unknown';

  const ProfiledComponent: React.FC<P> = (props) => {
    return (
      <Profiler id={componentId} onRender={profilerService.handleRender}>
        <Component {...props} />
      </Profiler>
    );
  };

  ProfiledComponent.displayName = `Profiled(${componentId})`;
  return ProfiledComponent;
}

// Hook for manual profiling
export function useRenderProfiler(componentName: string): void {
  const renderCount = useRef(0);
  const startTime = useRef(performance.now());

  useEffect(() => {
    renderCount.current += 1;
    const duration = performance.now() - startTime.current;

    if (duration > 16) {
      console.warn(
        `[useRenderProfiler] ${componentName} render #${renderCount.current} took ${duration.toFixed(2)}ms`
      );
    }

    startTime.current = performance.now();
  });
}

export default profilerService;
```

**Task 2: Bundle Analysis Setup (4h)**

```javascript
// frontend/vite.config.ts
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { visualizer } from 'rollup-plugin-visualizer';
import { compression } from 'vite-plugin-compression2';

export default defineConfig(({ mode }) => ({
  plugins: [
    react(),
    // Bundle visualization
    visualizer({
      filename: 'dist/stats.html',
      open: mode === 'analyze',
      gzipSize: true,
      brotliSize: true,
      template: 'treemap'
    }),
    // Compression
    compression({
      algorithm: 'gzip',
      exclude: [/\.(br)$/, /\.(gz)$/]
    }),
    compression({
      algorithm: 'brotliCompress',
      exclude: [/\.(br)$/, /\.(gz)$/]
    })
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          // Vendor chunks
          'vendor-react': ['react', 'react-dom', 'react-router-dom'],
          'vendor-ui': ['antd', '@ant-design/icons'],
          'vendor-charts': ['@ant-design/plots', 'echarts'],
          'vendor-utils': ['lodash-es', 'dayjs', 'axios']
        }
      }
    },
    // Build reporting
    reportCompressedSize: true,
    chunkSizeWarningLimit: 500
  }
}));
```

```json
// frontend/package.json (add scripts)
{
  "scripts": {
    "analyze": "vite build --mode analyze",
    "bundle-report": "npx source-map-explorer 'dist/assets/*.js' --html bundle-report.html",
    "lighthouse": "lighthouse http://localhost:3000 --output html --output-path ./lighthouse-report.html"
  }
}
```

#### Day 602 Deliverables

- [ ] Python application profiler module complete
- [ ] Database query profiler operational
- [ ] PostgreSQL query analysis views created
- [ ] Database monitoring integration complete
- [ ] React performance profiler HOC implemented
- [ ] Bundle analysis configuration complete

---

### Day 603 - Analysis Scripts & Load Testing Setup

**Objective:** Create comprehensive performance analysis scripts and establish load testing infrastructure.

#### Dev 1 - Backend Lead (8h)

**Task 1: Performance Analysis Scripts (8h)**

```python
# scripts/performance_analyzer.py
import json
import pandas as pd
import matplotlib.pyplot as plt
from datetime import datetime, timedelta
from typing import Dict, List, Any
import numpy as np
from dataclasses import dataclass
import os

@dataclass
class PerformanceReport:
    timestamp: datetime
    api_metrics: Dict[str, Any]
    database_metrics: Dict[str, Any]
    frontend_metrics: Dict[str, Any]
    recommendations: List[Dict[str, Any]]


class PerformanceAnalyzer:
    """Analyze performance data and generate optimization recommendations"""

    def __init__(self, data_dir: str = './performance_data'):
        self.data_dir = data_dir
        self.reports: List[PerformanceReport] = []

    def load_prometheus_data(self, metric_name: str,
                              start_time: datetime,
                              end_time: datetime) -> pd.DataFrame:
        """Load data from Prometheus"""
        # Implementation would query Prometheus API
        pass

    def analyze_api_performance(self, data: pd.DataFrame) -> Dict[str, Any]:
        """Analyze API endpoint performance"""
        analysis = {
            'total_requests': len(data),
            'avg_response_time_ms': data['duration_ms'].mean(),
            'p50_response_time_ms': data['duration_ms'].quantile(0.50),
            'p95_response_time_ms': data['duration_ms'].quantile(0.95),
            'p99_response_time_ms': data['duration_ms'].quantile(0.99),
            'error_rate': (data['status_code'] >= 400).mean() * 100,
            'requests_per_second': len(data) / (data['timestamp'].max() - data['timestamp'].min()).total_seconds()
        }

        # Identify slow endpoints
        endpoint_stats = data.groupby('endpoint').agg({
            'duration_ms': ['count', 'mean', 'median', lambda x: x.quantile(0.95)],
            'status_code': lambda x: (x >= 400).mean() * 100
        }).round(2)

        endpoint_stats.columns = ['count', 'avg_ms', 'median_ms', 'p95_ms', 'error_rate']

        analysis['slow_endpoints'] = endpoint_stats[
            endpoint_stats['p95_ms'] > 200
        ].sort_values('p95_ms', ascending=False).head(10).to_dict('index')

        analysis['high_error_endpoints'] = endpoint_stats[
            endpoint_stats['error_rate'] > 1
        ].sort_values('error_rate', ascending=False).head(10).to_dict('index')

        return analysis

    def analyze_database_performance(self, data: pd.DataFrame) -> Dict[str, Any]:
        """Analyze database query performance"""
        analysis = {
            'total_queries': len(data),
            'avg_query_time_ms': data['duration_ms'].mean(),
            'p50_query_time_ms': data['duration_ms'].quantile(0.50),
            'p90_query_time_ms': data['duration_ms'].quantile(0.90),
            'p99_query_time_ms': data['duration_ms'].quantile(0.99),
            'slow_queries_count': len(data[data['duration_ms'] > 100])
        }

        # Group by normalized query
        query_stats = data.groupby('normalized_query').agg({
            'duration_ms': ['count', 'sum', 'mean', 'max'],
        })
        query_stats.columns = ['count', 'total_ms', 'avg_ms', 'max_ms']

        analysis['top_time_consumers'] = query_stats.nlargest(10, 'total_ms').to_dict('index')
        analysis['slowest_queries'] = query_stats.nlargest(10, 'max_ms').to_dict('index')
        analysis['most_frequent'] = query_stats.nlargest(10, 'count').to_dict('index')

        # Detect N+1 patterns
        analysis['n_plus_one_candidates'] = self._detect_n_plus_one(data)

        return analysis

    def _detect_n_plus_one(self, data: pd.DataFrame) -> List[Dict[str, Any]]:
        """Detect potential N+1 query patterns"""
        patterns = []

        # Group by request_id and query pattern
        if 'request_id' in data.columns:
            grouped = data.groupby(['request_id', 'normalized_query']).size().reset_index(name='count')
            suspicious = grouped[grouped['count'] > 5].groupby('normalized_query').agg({
                'request_id': 'count',
                'count': 'mean'
            })

            for query, stats in suspicious.iterrows():
                if stats['request_id'] > 10:  # Appears in many requests
                    patterns.append({
                        'query_pattern': query[:200],
                        'affected_requests': int(stats['request_id']),
                        'avg_repetitions': round(stats['count'], 1)
                    })

        return patterns

    def analyze_frontend_metrics(self, data: pd.DataFrame) -> Dict[str, Any]:
        """Analyze frontend Web Vitals data"""
        metrics = {}

        for metric_name in ['LCP', 'FID', 'CLS', 'FCP', 'TTFB']:
            metric_data = data[data['metric'] == metric_name]['value']
            if len(metric_data) > 0:
                metrics[metric_name] = {
                    'p50': metric_data.quantile(0.50),
                    'p75': metric_data.quantile(0.75),
                    'p95': metric_data.quantile(0.95),
                    'avg': metric_data.mean()
                }

        # Assess Core Web Vitals pass rate
        thresholds = {'LCP': 2500, 'FID': 100, 'CLS': 0.1}

        for metric, threshold in thresholds.items():
            if metric in metrics:
                metrics[metric]['pass_rate'] = (
                    data[data['metric'] == metric]['value'] <= threshold
                ).mean() * 100

        # Analyze by page
        page_stats = data.groupby(['page', 'metric']).agg({
            'value': ['mean', lambda x: x.quantile(0.75)]
        })
        metrics['page_breakdown'] = page_stats.to_dict()

        return metrics

    def generate_recommendations(self,
                                   api_analysis: Dict,
                                   db_analysis: Dict,
                                   frontend_analysis: Dict) -> List[Dict[str, Any]]:
        """Generate prioritized optimization recommendations"""
        recommendations = []

        # API recommendations
        if api_analysis['p95_response_time_ms'] > 200:
            recommendations.append({
                'priority': 'HIGH',
                'area': 'API Performance',
                'issue': f"P95 response time ({api_analysis['p95_response_time_ms']:.0f}ms) exceeds 200ms target",
                'recommendation': 'Implement caching for slow endpoints, optimize database queries',
                'expected_impact': '30-50% reduction in response time',
                'effort': 'Medium'
            })

        if api_analysis['error_rate'] > 0.1:
            recommendations.append({
                'priority': 'HIGH',
                'area': 'API Reliability',
                'issue': f"Error rate ({api_analysis['error_rate']:.2f}%) exceeds 0.1% threshold",
                'recommendation': 'Investigate high-error endpoints, add error handling',
                'expected_impact': 'Improved reliability',
                'effort': 'Medium'
            })

        # Database recommendations
        if db_analysis['p90_query_time_ms'] > 50:
            recommendations.append({
                'priority': 'HIGH',
                'area': 'Database',
                'issue': f"P90 query time ({db_analysis['p90_query_time_ms']:.0f}ms) exceeds 50ms target",
                'recommendation': 'Add indexes for slow queries, optimize query patterns',
                'expected_impact': '60-80% reduction in query time',
                'effort': 'Medium'
            })

        if db_analysis.get('n_plus_one_candidates'):
            recommendations.append({
                'priority': 'MEDIUM',
                'area': 'Database',
                'issue': f"Detected {len(db_analysis['n_plus_one_candidates'])} potential N+1 query patterns",
                'recommendation': 'Implement eager loading, batch queries',
                'expected_impact': '40-60% reduction in query count',
                'effort': 'Medium'
            })

        # Frontend recommendations
        lcp = frontend_analysis.get('LCP', {})
        if lcp.get('p75', 0) > 2500:
            recommendations.append({
                'priority': 'HIGH',
                'area': 'Frontend',
                'issue': f"LCP P75 ({lcp['p75']:.0f}ms) exceeds 2.5s threshold",
                'recommendation': 'Optimize largest content element, implement lazy loading',
                'expected_impact': 'Better Core Web Vitals score',
                'effort': 'Medium'
            })

        cls = frontend_analysis.get('CLS', {})
        if cls.get('p75', 0) > 0.1:
            recommendations.append({
                'priority': 'MEDIUM',
                'area': 'Frontend',
                'issue': f"CLS P75 ({cls['p75']:.3f}) exceeds 0.1 threshold",
                'recommendation': 'Reserve space for dynamic content, avoid layout shifts',
                'expected_impact': 'Better user experience',
                'effort': 'Low'
            })

        # Sort by priority
        priority_order = {'HIGH': 0, 'MEDIUM': 1, 'LOW': 2}
        recommendations.sort(key=lambda x: priority_order.get(x['priority'], 3))

        return recommendations

    def generate_report(self, output_file: str = 'performance_baseline_report.md'):
        """Generate comprehensive performance baseline report"""
        # This would combine all analysis into a markdown report
        pass

    def create_visualizations(self, output_dir: str = './visualizations'):
        """Create performance visualization charts"""
        os.makedirs(output_dir, exist_ok=True)
        # Implementation would create various charts
        pass


if __name__ == '__main__':
    analyzer = PerformanceAnalyzer()
    # Run analysis
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: k6 Load Testing Scenarios (8h)**

```javascript
// tests/load/scenarios/baseline.js
import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { Rate, Trend, Counter } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const apiLatency = new Trend('api_latency');
const dbLatency = new Trend('db_latency');
const pageLoadTime = new Trend('page_load_time');

// Configuration
const BASE_URL = __ENV.BASE_URL || 'http://localhost:8069';

export const options = {
  scenarios: {
    // Baseline measurement
    baseline: {
      executor: 'constant-vus',
      vus: 10,
      duration: '5m',
      tags: { scenario: 'baseline' }
    },
    // Load test
    load_test: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 50 },
        { duration: '5m', target: 50 },
        { duration: '2m', target: 100 },
        { duration: '5m', target: 100 },
        { duration: '2m', target: 0 }
      ],
      startTime: '6m',
      tags: { scenario: 'load_test' }
    },
    // Stress test
    stress_test: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 100 },
        { duration: '5m', target: 200 },
        { duration: '2m', target: 300 },
        { duration: '5m', target: 400 },
        { duration: '2m', target: 500 },
        { duration: '5m', target: 500 },
        { duration: '5m', target: 0 }
      ],
      startTime: '25m',
      tags: { scenario: 'stress_test' }
    }
  },
  thresholds: {
    http_req_duration: ['p(95)<500', 'p(99)<1000'],
    http_req_failed: ['rate<0.01'],
    errors: ['rate<0.05'],
    api_latency: ['p(95)<200'],
  }
};

// Test data
const testData = {
  customers: JSON.parse(open('./data/customers.json')),
  products: JSON.parse(open('./data/products.json'))
};

export default function() {
  const customerId = testData.customers[__VU % testData.customers.length].id;

  // Homepage
  group('Homepage', () => {
    const res = http.get(`${BASE_URL}/`);
    check(res, {
      'homepage status 200': (r) => r.status === 200,
      'homepage loads fast': (r) => r.timings.duration < 2000
    });
    pageLoadTime.add(res.timings.duration);
    errorRate.add(res.status !== 200);
  });

  sleep(1);

  // Product listing
  group('Product Catalog', () => {
    const res = http.get(`${BASE_URL}/api/v1/products?limit=20`);
    check(res, {
      'products status 200': (r) => r.status === 200,
      'products response fast': (r) => r.timings.duration < 500
    });
    apiLatency.add(res.timings.duration);
    errorRate.add(res.status !== 200);
  });

  sleep(0.5);

  // Product detail
  group('Product Detail', () => {
    const productId = testData.products[Math.floor(Math.random() * testData.products.length)].id;
    const res = http.get(`${BASE_URL}/api/v1/products/${productId}`);
    check(res, {
      'product detail status 200': (r) => r.status === 200,
      'product detail fast': (r) => r.timings.duration < 300
    });
    apiLatency.add(res.timings.duration);
    errorRate.add(res.status !== 200);
  });

  sleep(0.5);

  // Search
  group('Search', () => {
    const searchTerms = ['milk', 'yogurt', 'cheese', 'butter', 'ghee'];
    const term = searchTerms[Math.floor(Math.random() * searchTerms.length)];
    const res = http.get(`${BASE_URL}/api/v1/products/search?q=${term}`);
    check(res, {
      'search status 200': (r) => r.status === 200,
      'search fast': (r) => r.timings.duration < 500
    });
    apiLatency.add(res.timings.duration);
    errorRate.add(res.status !== 200);
  });

  sleep(1);

  // Dashboard (authenticated)
  group('Dashboard', () => {
    const loginRes = http.post(`${BASE_URL}/api/v1/auth/login`, JSON.stringify({
      username: `test_user_${__VU}`,
      password: 'TestPass123!'
    }), { headers: { 'Content-Type': 'application/json' } });

    if (loginRes.status === 200) {
      const token = loginRes.json('token');
      const dashRes = http.get(`${BASE_URL}/api/v1/dashboard`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      check(dashRes, {
        'dashboard status 200': (r) => r.status === 200,
        'dashboard fast': (r) => r.timings.duration < 1000
      });
      apiLatency.add(dashRes.timings.duration);
      errorRate.add(dashRes.status !== 200);
    }
  });

  sleep(Math.random() * 2 + 1);
}

export function handleSummary(data) {
  return {
    'results/baseline_summary.json': JSON.stringify(data, null, 2),
    stdout: textSummary(data, { indent: ' ', enableColors: true })
  };
}
```

#### Dev 3 - Frontend Lead (8h)

**Task 1: Lighthouse CI Setup (4h)**

```javascript
// lighthouserc.js
module.exports = {
  ci: {
    collect: {
      url: [
        'http://localhost:3000/',
        'http://localhost:3000/products',
        'http://localhost:3000/dashboard',
        'http://localhost:3000/orders',
        'http://localhost:3000/farm/animals'
      ],
      numberOfRuns: 3,
      settings: {
        preset: 'desktop',
        throttling: {
          rttMs: 40,
          throughputKbps: 10240,
          cpuSlowdownMultiplier: 1
        },
        screenEmulation: {
          mobile: false,
          width: 1350,
          height: 940,
          deviceScaleFactor: 1,
          disabled: false
        }
      }
    },
    assert: {
      assertions: {
        'categories:performance': ['error', { minScore: 0.9 }],
        'categories:accessibility': ['warn', { minScore: 0.9 }],
        'categories:best-practices': ['warn', { minScore: 0.9 }],
        'categories:seo': ['warn', { minScore: 0.9 }],
        'first-contentful-paint': ['error', { maxNumericValue: 1500 }],
        'largest-contentful-paint': ['error', { maxNumericValue: 2500 }],
        'cumulative-layout-shift': ['error', { maxNumericValue: 0.1 }],
        'total-blocking-time': ['error', { maxNumericValue: 300 }],
        'speed-index': ['warn', { maxNumericValue: 3000 }],
        'interactive': ['warn', { maxNumericValue: 3500 }]
      }
    },
    upload: {
      target: 'filesystem',
      outputDir: './lighthouse-results'
    }
  }
};
```

**Task 2: Performance Testing Automation (4h)**

```bash
#!/bin/bash
# scripts/run_performance_tests.sh

set -e

echo "=== Smart Dairy Performance Testing Suite ==="
echo "Started at: $(date)"

# Configuration
RESULTS_DIR="./performance_results/$(date +%Y%m%d_%H%M%S)"
mkdir -p "$RESULTS_DIR"

# 1. Run Lighthouse CI
echo ""
echo ">>> Running Lighthouse CI..."
npx lhci autorun --config=lighthouserc.js
cp -r ./lighthouse-results/* "$RESULTS_DIR/lighthouse/"

# 2. Run k6 baseline test
echo ""
echo ">>> Running k6 baseline load test..."
k6 run --out json="$RESULTS_DIR/k6_baseline.json" tests/load/scenarios/baseline.js

# 3. Generate bundle analysis
echo ""
echo ">>> Generating bundle analysis..."
npm run analyze
cp dist/stats.html "$RESULTS_DIR/bundle_analysis.html"

# 4. Collect database metrics
echo ""
echo ">>> Collecting database metrics..."
psql $DATABASE_URL -f scripts/collect_db_metrics.sql -o "$RESULTS_DIR/db_metrics.txt"

# 5. Generate summary report
echo ""
echo ">>> Generating summary report..."
python scripts/generate_performance_report.py --input "$RESULTS_DIR" --output "$RESULTS_DIR/summary_report.md"

echo ""
echo "=== Performance Testing Complete ==="
echo "Results saved to: $RESULTS_DIR"
echo "Completed at: $(date)"
```

#### Day 603 Deliverables

- [ ] Performance analysis scripts complete
- [ ] k6 load testing scenarios created
- [ ] Lighthouse CI configuration complete
- [ ] Performance testing automation script ready
- [ ] Test data files prepared

---

### Day 604-605 - Baseline Report Generation

**Objective:** Execute all performance tests and generate comprehensive baseline documentation.

#### All Developers (2 days)

**Task: Execute Tests and Generate Reports**

```markdown
# Performance Baseline Report Template

## Executive Summary

### Current State Assessment
- **Overall Health**: [Good/Needs Improvement/Critical]
- **Performance Score**: XX/100
- **Readiness for 1000+ Users**: [Yes/No]

### Key Metrics Summary

| Metric | Current | Target | Status |
|--------|---------|--------|--------|
| Page Load (P95) | X.Xs | <2s | [Met/Not Met] |
| API Response (P95) | XXXms | <200ms | [Met/Not Met] |
| DB Query (P90) | XXms | <50ms | [Met/Not Met] |
| Lighthouse Score | XX | >90 | [Met/Not Met] |
| Error Rate | X.X% | <0.1% | [Met/Not Met] |

### Critical Findings
1. [Finding 1]
2. [Finding 2]
3. [Finding 3]

### Optimization Roadmap
[Prioritized list of optimizations for Phase 13]

## Detailed Analysis

### API Performance
[Detailed API analysis]

### Database Performance
[Detailed database analysis]

### Frontend Performance
[Detailed frontend analysis]

### Infrastructure
[System resource utilization]

## Recommendations

### High Priority
1. [Recommendation with expected impact]

### Medium Priority
1. [Recommendation]

### Low Priority
1. [Recommendation]

## Appendices
- A: Raw Data
- B: Test Configurations
- C: Methodology
```

#### Day 604-605 Deliverables

- [ ] All performance tests executed
- [ ] Raw data collected and stored
- [ ] Performance baseline report generated
- [ ] Optimization roadmap created
- [ ] Stakeholder presentation prepared
- [ ] Milestone 121 complete

---

## 4. Technical Specifications

### 4.1 APM Configuration

| Component | Configuration |
|-----------|---------------|
| New Relic Agent | Python 3.11+, auto-instrumentation enabled |
| Prometheus | 15s scrape interval, 30-day retention |
| Grafana | Pre-configured dashboards for Odoo, PostgreSQL, Redis |

### 4.2 Metrics Collection

| Metric Type | Collection Method | Storage |
|-------------|-------------------|---------|
| API Latency | Prometheus Histogram | Prometheus TSDB |
| DB Queries | pg_stat_statements | PostgreSQL |
| Web Vitals | Browser API | Backend API → TimescaleDB |
| System Metrics | Node Exporter | Prometheus |

---

## 5. Testing & Validation

### 5.1 Test Coverage

| Test Type | Target | Tool |
|-----------|--------|------|
| APM Integration | All endpoints monitored | Manual verification |
| Metrics Export | /metrics returns valid data | curl + promtool |
| Load Testing | 100 concurrent users baseline | k6 |
| Lighthouse | All critical pages | Lighthouse CI |

### 5.2 Acceptance Criteria

- [ ] APM dashboard accessible and showing data
- [ ] Prometheus successfully scraping all targets
- [ ] Web Vitals data appearing in backend
- [ ] k6 tests execute without errors
- [ ] Lighthouse CI passing assertions

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| APM tool cost overrun | Medium | Medium | Monitor usage, set alerts |
| Performance impact of profiling | Low | Medium | Use sampling, disable in peak hours |
| Incomplete baseline data | Medium | High | Run extended test duration |
| Test environment differs from prod | Medium | High | Document differences, adjust metrics |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Phase 12 stability | Phase 12 | Day 601 |
| APM account credentials | Vendor | Day 601 |
| Production-like data | Data team | Day 603 |

### 7.2 Handoffs to Milestone 122

| Item | Description | Owner |
|------|-------------|-------|
| Slow query list | Top 20 queries needing optimization | Dev 1 |
| Missing index report | Tables needing indexes | Dev 2 |
| Frontend bottlenecks | Components needing optimization | Dev 3 |

---

**End of Milestone 121 Document**
