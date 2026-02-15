# PHASE 13: PERFORMANCE & AI OPTIMIZATION (Days 601-650)

## Phase Overview
**Duration:** 50 days (Days 601-650)
**Team Size:** 3 developers
**Focus Areas:** Performance optimization, AI/ML integration, scalability improvements
**Prerequisites:** Phases 1-12 completed, production environment ready

## Success Criteria
- Page load time < 2 seconds (95th percentile)
- API response time < 200ms (95th percentile)
- Support 1000+ concurrent users
- Database query optimization (90% queries < 50ms)
- ML models deployed with 85%+ accuracy
- Lighthouse score > 90
- Time to Interactive (TTI) < 3 seconds

---

## MILESTONE 13.1: Performance Baseline (Days 601-605)

### Objective
Establish comprehensive performance baseline and identify optimization opportunities.

### Day 601: APM Setup & Infrastructure
**Assigned to:** Dev 1 (Lead), Dev 2, Dev 3

#### Tasks
**Dev 1: New Relic/Datadog Setup**
```yaml
# docker-compose.monitoring.yml
version: '3.8'

services:
  newrelic-infrastructure:
    image: newrelic/infrastructure:latest
    container_name: newrelic-infra
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
      - NRIA_DISPLAY_NAME=dairy-platform-prod
    restart: unless-stopped

  apm-agent:
    image: newrelic/newrelic-node-agent:latest
    container_name: newrelic-apm
    environment:
      - NEW_RELIC_LICENSE_KEY=${NEW_RELIC_LICENSE_KEY}
      - NEW_RELIC_APP_NAME=Smart-Dairy-Platform
      - NEW_RELIC_LOG_LEVEL=info
    restart: unless-stopped
```

**Dev 2: Application Instrumentation**
```javascript
// backend/src/config/monitoring.js
const newrelic = require('newrelic');
const prometheus = require('prom-client');

// Prometheus metrics
const register = new prometheus.Registry();

// Custom metrics
const httpRequestDuration = new prometheus.Histogram({
  name: 'http_request_duration_seconds',
  help: 'Duration of HTTP requests in seconds',
  labelNames: ['method', 'route', 'status_code'],
  buckets: [0.1, 0.3, 0.5, 0.7, 1, 3, 5, 7, 10]
});

const databaseQueryDuration = new prometheus.Histogram({
  name: 'database_query_duration_seconds',
  help: 'Duration of database queries in seconds',
  labelNames: ['operation', 'table'],
  buckets: [0.01, 0.05, 0.1, 0.3, 0.5, 1, 3, 5]
});

const activeUsers = new prometheus.Gauge({
  name: 'active_users_total',
  help: 'Number of currently active users'
});

const cacheHitRate = new prometheus.Counter({
  name: 'cache_hits_total',
  help: 'Total number of cache hits',
  labelNames: ['cache_type']
});

register.registerMetric(httpRequestDuration);
register.registerMetric(databaseQueryDuration);
register.registerMetric(activeUsers);
register.registerMetric(cacheHitRate);

// Middleware for request tracking
const requestTracker = (req, res, next) => {
  const start = Date.now();

  res.on('finish', () => {
    const duration = (Date.now() - start) / 1000;
    httpRequestDuration
      .labels(req.method, req.route?.path || req.path, res.statusCode)
      .observe(duration);

    // New Relic custom attributes
    newrelic.addCustomAttributes({
      userId: req.user?.id,
      userRole: req.user?.role,
      endpoint: req.path,
      responseTime: duration
    });
  });

  next();
};

// Database query tracker
const trackDatabaseQuery = (operation, table, duration) => {
  databaseQueryDuration.labels(operation, table).observe(duration);

  newrelic.recordMetric('Database/Query/' + table, duration);
};

module.exports = {
  register,
  requestTracker,
  trackDatabaseQuery,
  metrics: {
    httpRequestDuration,
    databaseQueryDuration,
    activeUsers,
    cacheHitRate
  }
};
```

**Dev 3: Frontend Performance Monitoring**
```javascript
// frontend/src/utils/performanceMonitoring.js
import { onCLS, onFID, onLCP, onFCP, onTTFB } from 'web-vitals';

class PerformanceMonitor {
  constructor() {
    this.metrics = {};
    this.initWebVitals();
    this.initCustomMetrics();
  }

  initWebVitals() {
    // Core Web Vitals
    onCLS((metric) => this.sendMetric('CLS', metric));
    onFID((metric) => this.sendMetric('FID', metric));
    onLCP((metric) => this.sendMetric('LCP', metric));
    onFCP((metric) => this.sendMetric('FCP', metric));
    onTTFB((metric) => this.sendMetric('TTFB', metric));
  }

  initCustomMetrics() {
    // Resource timing
    if (window.performance && window.performance.getEntriesByType) {
      const resources = window.performance.getEntriesByType('resource');
      this.analyzeResources(resources);
    }

    // Navigation timing
    if (window.performance && window.performance.timing) {
      this.analyzeNavigation();
    }

    // Long tasks detection
    if ('PerformanceObserver' in window) {
      const observer = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
          this.sendMetric('LongTask', {
            duration: entry.duration,
            startTime: entry.startTime
          });
        }
      });
      observer.observe({ entryTypes: ['longtask'] });
    }
  }

  analyzeResources(resources) {
    const resourceMetrics = {
      scripts: [],
      styles: [],
      images: [],
      api: []
    };

    resources.forEach(resource => {
      const metric = {
        name: resource.name,
        duration: resource.duration,
        size: resource.transferSize,
        type: resource.initiatorType
      };

      if (resource.name.includes('.js')) {
        resourceMetrics.scripts.push(metric);
      } else if (resource.name.includes('.css')) {
        resourceMetrics.styles.push(metric);
      } else if (resource.initiatorType === 'img') {
        resourceMetrics.images.push(metric);
      } else if (resource.initiatorType === 'fetch' || resource.initiatorType === 'xmlhttprequest') {
        resourceMetrics.api.push(metric);
      }
    });

    this.sendMetric('ResourceTiming', resourceMetrics);
  }

  analyzeNavigation() {
    const timing = window.performance.timing;
    const metrics = {
      dns: timing.domainLookupEnd - timing.domainLookupStart,
      tcp: timing.connectEnd - timing.connectStart,
      request: timing.responseStart - timing.requestStart,
      response: timing.responseEnd - timing.responseStart,
      dom: timing.domComplete - timing.domLoading,
      load: timing.loadEventEnd - timing.loadEventStart,
      total: timing.loadEventEnd - timing.navigationStart
    };

    this.sendMetric('NavigationTiming', metrics);
  }

  sendMetric(name, metric) {
    // Send to backend
    fetch('/api/metrics/performance', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name,
        value: metric.value || metric,
        id: metric.id,
        timestamp: Date.now(),
        page: window.location.pathname,
        userAgent: navigator.userAgent
      })
    }).catch(err => console.error('Failed to send metric:', err));

    // Send to New Relic
    if (window.newrelic) {
      window.newrelic.addPageAction(name, {
        value: metric.value || metric,
        page: window.location.pathname
      });
    }
  }

  // Component-level performance tracking
  trackComponentRender(componentName, renderTime) {
    this.sendMetric('ComponentRender', {
      component: componentName,
      duration: renderTime
    });
  }

  // API call tracking
  trackApiCall(endpoint, duration, status) {
    this.sendMetric('ApiCall', {
      endpoint,
      duration,
      status,
      timestamp: Date.now()
    });
  }
}

export default new PerformanceMonitor();
```

#### Deliverables
- [ ] APM tools configured (New Relic/Datadog)
- [ ] Backend instrumentation complete
- [ ] Frontend monitoring active
- [ ] Prometheus metrics exported

---

### Day 602: Application Profiling
**Assigned to:** Dev 1, Dev 2, Dev 3

#### Tasks
**Dev 1: Backend Profiling**
```javascript
// backend/src/utils/profiler.js
const v8Profiler = require('v8-profiler-next');
const fs = require('fs');
const path = require('path');

class ApplicationProfiler {
  constructor() {
    this.profiles = new Map();
    this.cpuProfileActive = false;
  }

  // CPU Profiling
  startCpuProfiling(name = 'cpu-profile') {
    if (this.cpuProfileActive) {
      console.warn('CPU profiling already active');
      return;
    }

    v8Profiler.startProfiling(name, true);
    this.cpuProfileActive = true;
    console.log(`CPU profiling started: ${name}`);
  }

  async stopCpuProfiling(name = 'cpu-profile') {
    if (!this.cpuProfileActive) {
      console.warn('No active CPU profiling');
      return;
    }

    const profile = v8Profiler.stopProfiling(name);
    this.cpuProfileActive = false;

    return new Promise((resolve, reject) => {
      const filePath = path.join(__dirname, `../../profiles/${name}-${Date.now()}.cpuprofile`);

      profile.export((error, result) => {
        if (error) {
          reject(error);
          return;
        }

        fs.writeFileSync(filePath, result);
        profile.delete();
        console.log(`CPU profile saved: ${filePath}`);
        resolve(filePath);
      });
    });
  }

  // Memory Profiling
  takeHeapSnapshot(name = 'heap-snapshot') {
    const snapshot = v8Profiler.takeSnapshot(name);

    return new Promise((resolve, reject) => {
      const filePath = path.join(__dirname, `../../profiles/${name}-${Date.now()}.heapsnapshot`);

      snapshot.export((error, result) => {
        if (error) {
          reject(error);
          return;
        }

        fs.writeFileSync(filePath, result);
        snapshot.delete();
        console.log(`Heap snapshot saved: ${filePath}`);
        resolve(filePath);
      });
    });
  }

  // Memory usage tracking
  getMemoryUsage() {
    const usage = process.memoryUsage();
    return {
      rss: (usage.rss / 1024 / 1024).toFixed(2) + ' MB',
      heapTotal: (usage.heapTotal / 1024 / 1024).toFixed(2) + ' MB',
      heapUsed: (usage.heapUsed / 1024 / 1024).toFixed(2) + ' MB',
      external: (usage.external / 1024 / 1024).toFixed(2) + ' MB',
      arrayBuffers: (usage.arrayBuffers / 1024 / 1024).toFixed(2) + ' MB'
    };
  }

  // Event loop lag detection
  monitorEventLoop() {
    let lastCheck = Date.now();

    setInterval(() => {
      const now = Date.now();
      const lag = now - lastCheck - 1000; // Expected 1000ms interval

      if (lag > 100) {
        console.warn(`Event loop lag detected: ${lag}ms`);

        // Send alert
        this.sendAlert('EventLoopLag', { lag, timestamp: now });
      }

      lastCheck = now;
    }, 1000);
  }

  // Profile specific route
  async profileRoute(req, res, next) {
    const profileName = `route-${req.method}-${req.path.replace(/\//g, '-')}`;

    this.startCpuProfiling(profileName);

    res.on('finish', async () => {
      try {
        await this.stopCpuProfiling(profileName);
      } catch (error) {
        console.error('Error stopping profile:', error);
      }
    });

    next();
  }

  sendAlert(type, data) {
    // Implementation for sending alerts
    console.log(`Alert: ${type}`, data);
  }
}

module.exports = new ApplicationProfiler();
```

**Dev 2: Database Query Profiling**
```javascript
// backend/src/database/queryProfiler.js
const { performance } = require('perf_hooks');

class DatabaseQueryProfiler {
  constructor(sequelize) {
    this.sequelize = sequelize;
    this.slowQueries = [];
    this.queryStats = new Map();
    this.enableProfiling();
  }

  enableProfiling() {
    // Log all queries with timing
    this.sequelize.addHook('beforeQuery', (options) => {
      options._startTime = performance.now();
    });

    this.sequelize.addHook('afterQuery', (options, query) => {
      const duration = performance.now() - options._startTime;

      this.recordQuery({
        sql: query.sql,
        duration,
        timestamp: new Date(),
        parameters: query.bind
      });

      // Flag slow queries (> 100ms)
      if (duration > 100) {
        this.recordSlowQuery({
          sql: query.sql,
          duration,
          timestamp: new Date(),
          stack: new Error().stack
        });
      }
    });
  }

  recordQuery(query) {
    const queryKey = this.normalizeQuery(query.sql);

    if (!this.queryStats.has(queryKey)) {
      this.queryStats.set(queryKey, {
        sql: query.sql,
        count: 0,
        totalDuration: 0,
        minDuration: Infinity,
        maxDuration: 0,
        avgDuration: 0
      });
    }

    const stats = this.queryStats.get(queryKey);
    stats.count++;
    stats.totalDuration += query.duration;
    stats.minDuration = Math.min(stats.minDuration, query.duration);
    stats.maxDuration = Math.max(stats.maxDuration, query.duration);
    stats.avgDuration = stats.totalDuration / stats.count;
  }

  recordSlowQuery(query) {
    this.slowQueries.push(query);

    // Keep only last 100 slow queries
    if (this.slowQueries.length > 100) {
      this.slowQueries.shift();
    }

    console.warn(`Slow query detected (${query.duration.toFixed(2)}ms):`, query.sql);
  }

  normalizeQuery(sql) {
    // Remove parameter values for grouping
    return sql
      .replace(/\$\d+/g, '$?')
      .replace(/'\w+'/g, "'?'")
      .replace(/\d+/g, '?');
  }

  getTopSlowQueries(limit = 10) {
    const sorted = Array.from(this.queryStats.values())
      .sort((a, b) => b.avgDuration - a.avgDuration);

    return sorted.slice(0, limit);
  }

  getMostFrequentQueries(limit = 10) {
    const sorted = Array.from(this.queryStats.values())
      .sort((a, b) => b.count - a.count);

    return sorted.slice(0, limit);
  }

  getStats() {
    const totalQueries = Array.from(this.queryStats.values())
      .reduce((sum, stats) => sum + stats.count, 0);

    const avgDuration = Array.from(this.queryStats.values())
      .reduce((sum, stats) => sum + stats.avgDuration, 0) / this.queryStats.size;

    return {
      totalQueries,
      uniqueQueries: this.queryStats.size,
      avgDuration: avgDuration.toFixed(2),
      slowQueries: this.slowQueries.length,
      topSlowQueries: this.getTopSlowQueries(5),
      mostFrequentQueries: this.getMostFrequentQueries(5)
    };
  }

  generateReport() {
    const stats = this.getStats();
    const report = {
      timestamp: new Date(),
      summary: stats,
      topSlowQueries: this.getTopSlowQueries(20),
      mostFrequentQueries: this.getMostFrequentQueries(20),
      recentSlowQueries: this.slowQueries.slice(-20)
    };

    return report;
  }

  reset() {
    this.slowQueries = [];
    this.queryStats.clear();
  }
}

module.exports = DatabaseQueryProfiler;
```

**Dev 3: Frontend Performance Profiling**
```javascript
// frontend/src/utils/reactProfiler.js
import React, { Profiler } from 'react';

class ReactPerformanceProfiler {
  constructor() {
    this.measurements = [];
    this.componentMetrics = new Map();
  }

  // Profiler callback
  onRenderCallback = (
    id, // Component identifier
    phase, // "mount" or "update"
    actualDuration, // Time spent rendering
    baseDuration, // Estimated time without memoization
    startTime, // When React began rendering
    commitTime, // When React committed the update
    interactions // Set of interactions that caused this render
  ) => {
    const metric = {
      id,
      phase,
      actualDuration,
      baseDuration,
      startTime,
      commitTime,
      interactions: Array.from(interactions)
    };

    this.recordComponentMetric(metric);

    // Flag slow renders (> 16ms for 60fps)
    if (actualDuration > 16) {
      console.warn(`Slow render detected: ${id} (${actualDuration.toFixed(2)}ms)`);
      this.measurements.push({
        ...metric,
        timestamp: Date.now(),
        slow: true
      });
    }
  };

  recordComponentMetric(metric) {
    if (!this.componentMetrics.has(metric.id)) {
      this.componentMetrics.set(metric.id, {
        id: metric.id,
        mounts: 0,
        updates: 0,
        totalDuration: 0,
        avgDuration: 0,
        maxDuration: 0,
        slowRenders: 0
      });
    }

    const stats = this.componentMetrics.get(metric.id);

    if (metric.phase === 'mount') {
      stats.mounts++;
    } else {
      stats.updates++;
    }

    stats.totalDuration += metric.actualDuration;
    stats.maxDuration = Math.max(stats.maxDuration, metric.actualDuration);
    stats.avgDuration = stats.totalDuration / (stats.mounts + stats.updates);

    if (metric.actualDuration > 16) {
      stats.slowRenders++;
    }
  }

  // HOC to wrap components with profiler
  withProfiler(Component, id) {
    return (props) => (
      <Profiler id={id} onRender={this.onRenderCallback}>
        <Component {...props} />
      </Profiler>
    );
  }

  getComponentStats(componentId) {
    return this.componentMetrics.get(componentId);
  }

  getSlowComponents(threshold = 16) {
    return Array.from(this.componentMetrics.values())
      .filter(stats => stats.avgDuration > threshold)
      .sort((a, b) => b.avgDuration - a.avgDuration);
  }

  getMostRenderedComponents(limit = 10) {
    return Array.from(this.componentMetrics.values())
      .sort((a, b) => (b.mounts + b.updates) - (a.mounts + a.updates))
      .slice(0, limit);
  }

  generateReport() {
    return {
      timestamp: Date.now(),
      totalComponents: this.componentMetrics.size,
      slowComponents: this.getSlowComponents(),
      mostRendered: this.getMostRenderedComponents(),
      recentSlowRenders: this.measurements.slice(-20)
    };
  }

  reset() {
    this.measurements = [];
    this.componentMetrics.clear();
  }
}

export default new ReactPerformanceProfiler();
```

#### Deliverables
- [ ] Backend profiling tools configured
- [ ] Database query profiler active
- [ ] React profiler implemented
- [ ] Initial profiling data collected

---

### Day 603: Bottleneck Identification
**Assigned to:** All Developers

#### Tasks
**Dev 1: Backend Bottleneck Analysis**
```python
# scripts/analyze_performance.py
import json
import pandas as pd
import matplotlib.pyplot as plt
from datetime import datetime, timedelta

class PerformanceAnalyzer:
    def __init__(self, data_file):
        self.data = self.load_data(data_file)
        self.reports = []

    def load_data(self, file_path):
        with open(file_path, 'r') as f:
            return json.load(f)

    def analyze_api_endpoints(self):
        """Analyze API endpoint performance"""
        df = pd.DataFrame(self.data['api_calls'])

        # Group by endpoint
        endpoint_stats = df.groupby('endpoint').agg({
            'duration': ['count', 'mean', 'median', 'max', 'std'],
            'status': lambda x: (x == 200).sum() / len(x) * 100
        }).round(2)

        # Identify slow endpoints (p95 > 200ms)
        slow_endpoints = df.groupby('endpoint')['duration'].quantile(0.95)
        slow_endpoints = slow_endpoints[slow_endpoints > 200].sort_values(ascending=False)

        report = {
            'title': 'API Endpoint Performance Analysis',
            'timestamp': datetime.now().isoformat(),
            'summary': {
                'total_requests': len(df),
                'unique_endpoints': df['endpoint'].nunique(),
                'avg_response_time': df['duration'].mean(),
                'p95_response_time': df['duration'].quantile(0.95),
                'error_rate': ((df['status'] != 200).sum() / len(df) * 100)
            },
            'slow_endpoints': slow_endpoints.to_dict(),
            'endpoint_stats': endpoint_stats.to_dict()
        }

        self.reports.append(report)
        return report

    def analyze_database_queries(self):
        """Analyze database query performance"""
        df = pd.DataFrame(self.data['database_queries'])

        # Group by normalized query
        query_stats = df.groupby('normalized_query').agg({
            'duration': ['count', 'mean', 'median', 'max', 'min'],
            'table': 'first'
        }).round(2)

        # Identify N+1 query patterns
        potential_n_plus_1 = df.groupby(['endpoint', 'normalized_query']).size()
        potential_n_plus_1 = potential_n_plus_1[potential_n_plus_1 > 10].sort_values(ascending=False)

        # Find missing indexes (queries with table scans)
        table_scans = df[df['explain_plan'].str.contains('Seq Scan', na=False)]

        report = {
            'title': 'Database Query Performance Analysis',
            'timestamp': datetime.now().isoformat(),
            'summary': {
                'total_queries': len(df),
                'unique_queries': df['normalized_query'].nunique(),
                'avg_query_time': df['duration'].mean(),
                'slow_queries': len(df[df['duration'] > 100]),
                'table_scans': len(table_scans)
            },
            'slowest_queries': df.nlargest(20, 'duration')[['normalized_query', 'duration', 'table']].to_dict('records'),
            'potential_n_plus_1': potential_n_plus_1.to_dict(),
            'tables_needing_indexes': table_scans['table'].value_counts().to_dict()
        }

        self.reports.append(report)
        return report

    def analyze_memory_usage(self):
        """Analyze memory usage patterns"""
        df = pd.DataFrame(self.data['memory_snapshots'])

        # Convert to MB
        df['heap_used_mb'] = df['heap_used'] / 1024 / 1024
        df['heap_total_mb'] = df['heap_total'] / 1024 / 1024
        df['rss_mb'] = df['rss'] / 1024 / 1024

        # Detect memory leaks (continuously increasing memory)
        df['timestamp'] = pd.to_datetime(df['timestamp'])
        df = df.sort_values('timestamp')
        df['heap_used_diff'] = df['heap_used_mb'].diff()

        # Calculate trend
        from scipy import stats
        x = range(len(df))
        slope, _, _, _, _ = stats.linregress(x, df['heap_used_mb'])

        report = {
            'title': 'Memory Usage Analysis',
            'timestamp': datetime.now().isoformat(),
            'summary': {
                'avg_heap_used_mb': df['heap_used_mb'].mean(),
                'max_heap_used_mb': df['heap_used_mb'].max(),
                'avg_rss_mb': df['rss_mb'].mean(),
                'max_rss_mb': df['rss_mb'].max(),
                'memory_trend_mb_per_hour': slope * 3600,
                'potential_leak': slope > 0.1
            },
            'peak_usage_times': df.nlargest(10, 'heap_used_mb')[['timestamp', 'heap_used_mb']].to_dict('records')
        }

        self.reports.append(report)
        return report

    def analyze_frontend_metrics(self):
        """Analyze frontend performance metrics"""
        df = pd.DataFrame(self.data['web_vitals'])

        # Core Web Vitals thresholds
        thresholds = {
            'LCP': 2500,  # Largest Contentful Paint
            'FID': 100,   # First Input Delay
            'CLS': 0.1    # Cumulative Layout Shift
        }

        metrics_summary = {}
        for metric in ['LCP', 'FID', 'CLS', 'FCP', 'TTFB']:
            metric_data = df[df['metric'] == metric]['value']
            if len(metric_data) > 0:
                metrics_summary[metric] = {
                    'p50': metric_data.quantile(0.50),
                    'p75': metric_data.quantile(0.75),
                    'p95': metric_data.quantile(0.95),
                    'avg': metric_data.mean(),
                    'passes_threshold': metric_data.quantile(0.75) < thresholds.get(metric, float('inf'))
                }

        report = {
            'title': 'Frontend Performance Analysis',
            'timestamp': datetime.now().isoformat(),
            'summary': metrics_summary,
            'slow_pages': df.groupby('page')['value'].quantile(0.95).sort_values(ascending=False).head(10).to_dict()
        }

        self.reports.append(report)
        return report

    def generate_visualizations(self, output_dir='./performance_reports'):
        """Generate performance visualization charts"""
        import os
        os.makedirs(output_dir, exist_ok=True)

        # API Response Time Distribution
        if 'api_calls' in self.data:
            df = pd.DataFrame(self.data['api_calls'])
            plt.figure(figsize=(12, 6))
            plt.hist(df['duration'], bins=50, edgecolor='black')
            plt.xlabel('Response Time (ms)')
            plt.ylabel('Frequency')
            plt.title('API Response Time Distribution')
            plt.axvline(df['duration'].quantile(0.95), color='r', linestyle='--', label='P95')
            plt.legend()
            plt.savefig(f'{output_dir}/api_response_time_distribution.png')
            plt.close()

        # Database Query Performance
        if 'database_queries' in self.data:
            df = pd.DataFrame(self.data['database_queries'])
            top_tables = df.groupby('table')['duration'].agg(['count', 'mean']).nlargest(10, 'count')

            fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15, 6))
            top_tables['count'].plot(kind='bar', ax=ax1)
            ax1.set_title('Most Queried Tables')
            ax1.set_ylabel('Query Count')

            top_tables['mean'].plot(kind='bar', ax=ax2)
            ax2.set_title('Average Query Time by Table')
            ax2.set_ylabel('Avg Duration (ms)')

            plt.tight_layout()
            plt.savefig(f'{output_dir}/database_query_performance.png')
            plt.close()

        # Memory Usage Over Time
        if 'memory_snapshots' in self.data:
            df = pd.DataFrame(self.data['memory_snapshots'])
            df['timestamp'] = pd.to_datetime(df['timestamp'])
            df = df.sort_values('timestamp')

            plt.figure(figsize=(12, 6))
            plt.plot(df['timestamp'], df['heap_used'] / 1024 / 1024, label='Heap Used')
            plt.plot(df['timestamp'], df['heap_total'] / 1024 / 1024, label='Heap Total')
            plt.xlabel('Time')
            plt.ylabel('Memory (MB)')
            plt.title('Memory Usage Over Time')
            plt.legend()
            plt.xticks(rotation=45)
            plt.tight_layout()
            plt.savefig(f'{output_dir}/memory_usage_timeline.png')
            plt.close()

    def generate_comprehensive_report(self):
        """Generate comprehensive performance report"""
        self.analyze_api_endpoints()
        self.analyze_database_queries()
        self.analyze_memory_usage()
        self.analyze_frontend_metrics()
        self.generate_visualizations()

        comprehensive_report = {
            'title': 'Comprehensive Performance Analysis Report',
            'generated_at': datetime.now().isoformat(),
            'reports': self.reports,
            'recommendations': self.generate_recommendations()
        }

        # Save report
        with open('./performance_reports/comprehensive_report.json', 'w') as f:
            json.dump(comprehensive_report, f, indent=2)

        return comprehensive_report

    def generate_recommendations(self):
        """Generate optimization recommendations based on analysis"""
        recommendations = []

        # Check API performance
        api_report = next((r for r in self.reports if 'API Endpoint' in r['title']), None)
        if api_report and api_report['summary']['p95_response_time'] > 200:
            recommendations.append({
                'priority': 'HIGH',
                'area': 'API Performance',
                'issue': 'High P95 response time',
                'recommendation': 'Implement caching for frequently accessed endpoints',
                'expected_impact': '30-50% reduction in response time'
            })

        # Check database performance
        db_report = next((r for r in self.reports if 'Database' in r['title']), None)
        if db_report:
            if db_report['summary']['table_scans'] > 100:
                recommendations.append({
                    'priority': 'HIGH',
                    'area': 'Database',
                    'issue': 'Excessive table scans detected',
                    'recommendation': 'Add indexes to frequently queried columns',
                    'expected_impact': '60-80% reduction in query time'
                })

            if len(db_report.get('potential_n_plus_1', {})) > 0:
                recommendations.append({
                    'priority': 'MEDIUM',
                    'area': 'Database',
                    'issue': 'Potential N+1 query patterns detected',
                    'recommendation': 'Implement eager loading for related data',
                    'expected_impact': '40-60% reduction in query count'
                })

        # Check memory usage
        mem_report = next((r for r in self.reports if 'Memory' in r['title']), None)
        if mem_report and mem_report['summary']['potential_leak']:
            recommendations.append({
                'priority': 'HIGH',
                'area': 'Memory Management',
                'issue': 'Potential memory leak detected',
                'recommendation': 'Review event listeners and long-lived object references',
                'expected_impact': 'Prevent memory exhaustion'
            })

        # Check frontend performance
        fe_report = next((r for r in self.reports if 'Frontend' in r['title']), None)
        if fe_report:
            lcp = fe_report['summary'].get('LCP', {})
            if lcp.get('p75', 0) > 2500:
                recommendations.append({
                    'priority': 'HIGH',
                    'area': 'Frontend Performance',
                    'issue': 'Poor Largest Contentful Paint',
                    'recommendation': 'Optimize image loading and implement lazy loading',
                    'expected_impact': '30-40% improvement in LCP'
                })

        return recommendations

if __name__ == '__main__':
    analyzer = PerformanceAnalyzer('./performance_data.json')
    report = analyzer.generate_comprehensive_report()
    print(json.dumps(report, indent=2))
```

**Dev 2: Load Testing Scenario Creation**
```javascript
// tests/load/k6-scenarios.js
import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { Rate, Trend, Counter } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const apiResponseTime = new Trend('api_response_time');
const apiCalls = new Counter('api_calls');

// Test configuration
export const options = {
  stages: [
    { duration: '2m', target: 100 },  // Ramp up to 100 users
    { duration: '5m', target: 100 },  // Stay at 100 users
    { duration: '2m', target: 200 },  // Ramp up to 200 users
    { duration: '5m', target: 200 },  // Stay at 200 users
    { duration: '2m', target: 500 },  // Ramp up to 500 users
    { duration: '5m', target: 500 },  // Stay at 500 users
    { duration: '5m', target: 0 },    // Ramp down to 0 users
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'], // 95% of requests should be below 500ms
    http_req_failed: ['rate<0.01'],   // Error rate should be less than 1%
    errors: ['rate<0.1'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:3000';
const API_KEY = __ENV.API_KEY || 'test-api-key';

// Test data
const testUsers = JSON.parse(open('./test-users.json'));
const testProducts = JSON.parse(open('./test-products.json'));

export default function () {
  // Authentication
  group('Authentication', () => {
    const loginPayload = JSON.stringify({
      username: testUsers[__VU % testUsers.length].username,
      password: 'TestPassword123!'
    });

    const loginRes = http.post(`${BASE_URL}/api/auth/login`, loginPayload, {
      headers: { 'Content-Type': 'application/json' },
    });

    check(loginRes, {
      'login status is 200': (r) => r.status === 200,
      'login has token': (r) => r.json('token') !== '',
    });

    errorRate.add(loginRes.status !== 200);
    apiResponseTime.add(loginRes.timings.duration);
    apiCalls.add(1);

    if (loginRes.status === 200) {
      const authToken = loginRes.json('token');

      // Dashboard load
      group('Dashboard', () => {
        const dashboardRes = http.get(`${BASE_URL}/api/dashboard`, {
          headers: {
            'Authorization': `Bearer ${authToken}`,
            'Content-Type': 'application/json'
          },
        });

        check(dashboardRes, {
          'dashboard status is 200': (r) => r.status === 200,
          'dashboard has data': (r) => r.json('data') !== null,
        });

        errorRate.add(dashboardRes.status !== 200);
        apiResponseTime.add(dashboardRes.timings.duration);
        apiCalls.add(1);
      });

      // Product browsing
      group('Product Browsing', () => {
        const productsRes = http.get(`${BASE_URL}/api/products?page=1&limit=20`, {
          headers: {
            'Authorization': `Bearer ${authToken}`,
            'Content-Type': 'application/json'
          },
        });

        check(productsRes, {
          'products status is 200': (r) => r.status === 200,
          'products list not empty': (r) => r.json('data.length') > 0,
        });

        errorRate.add(productsRes.status !== 200);
        apiResponseTime.add(productsRes.timings.duration);
        apiCalls.add(1);

        // View random product
        if (productsRes.status === 200) {
          const products = productsRes.json('data');
          const randomProduct = products[Math.floor(Math.random() * products.length)];

          const productDetailRes = http.get(
            `${BASE_URL}/api/products/${randomProduct.id}`,
            {
              headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
              },
            }
          );

          check(productDetailRes, {
            'product detail status is 200': (r) => r.status === 200,
          });

          errorRate.add(productDetailRes.status !== 200);
          apiResponseTime.add(productDetailRes.timings.duration);
          apiCalls.add(1);
        }
      });

      // Order creation (30% of users)
      if (Math.random() < 0.3) {
        group('Order Creation', () => {
          const orderPayload = JSON.stringify({
            items: [
              {
                productId: testProducts[0].id,
                quantity: Math.floor(Math.random() * 10) + 1
              }
            ],
            deliveryAddress: 'Test Address',
            paymentMethod: 'cash'
          });

          const orderRes = http.post(
            `${BASE_URL}/api/orders`,
            orderPayload,
            {
              headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
              },
            }
          );

          check(orderRes, {
            'order creation status is 201': (r) => r.status === 201,
            'order has id': (r) => r.json('data.id') !== '',
          });

          errorRate.add(orderRes.status !== 201);
          apiResponseTime.add(orderRes.timings.duration);
          apiCalls.add(1);
        });
      }

      // Analytics (20% of users)
      if (Math.random() < 0.2) {
        group('Analytics', () => {
          const analyticsRes = http.get(
            `${BASE_URL}/api/analytics/sales?period=month`,
            {
              headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
              },
            }
          );

          check(analyticsRes, {
            'analytics status is 200': (r) => r.status === 200,
          });

          errorRate.add(analyticsRes.status !== 200);
          apiResponseTime.add(analyticsRes.timings.duration);
          apiCalls.add(1);
        });
      }
    }
  });

  sleep(Math.random() * 3 + 1); // Random think time between 1-4 seconds
}

export function handleSummary(data) {
  return {
    'summary.json': JSON.stringify(data),
    stdout: textSummary(data, { indent: ' ', enableColors: true }),
  };
}
```

**Dev 3: Frontend Performance Audit**
```bash
# scripts/lighthouse-ci.sh
#!/bin/bash

# Lighthouse CI Configuration
echo "Running Lighthouse CI audit..."

# Install dependencies
npm install -g @lhci/cli

# Create Lighthouse CI config
cat > lighthouserc.json <<EOF
{
  "ci": {
    "collect": {
      "url": [
        "http://localhost:3000/",
        "http://localhost:3000/dashboard",
        "http://localhost:3000/products",
        "http://localhost:3000/orders",
        "http://localhost:3000/analytics"
      ],
      "numberOfRuns": 3,
      "settings": {
        "preset": "desktop",
        "throttling": {
          "rttMs": 40,
          "throughputKbps": 10240,
          "cpuSlowdownMultiplier": 1
        }
      }
    },
    "assert": {
      "assertions": {
        "categories:performance": ["error", {"minScore": 0.9}],
        "categories:accessibility": ["error", {"minScore": 0.9}],
        "categories:best-practices": ["error", {"minScore": 0.9}],
        "categories:seo": ["error", {"minScore": 0.9}],
        "first-contentful-paint": ["error", {"maxNumericValue": 2000}],
        "largest-contentful-paint": ["error", {"maxNumericValue": 2500}],
        "cumulative-layout-shift": ["error", {"maxNumericValue": 0.1}],
        "total-blocking-time": ["error", {"maxNumericValue": 300}],
        "speed-index": ["error", {"maxNumericValue": 3000}]
      }
    },
    "upload": {
      "target": "filesystem",
      "outputDir": "./lighthouse-reports"
    }
  }
}
EOF

# Run Lighthouse CI
lhci autorun

# Generate comparison report
lhci assert

echo "Lighthouse audit complete. Reports saved to ./lighthouse-reports"
```

#### Deliverables
- [ ] Performance analysis scripts complete
- [ ] Load testing scenarios created
- [ ] Bottleneck identification report generated
- [ ] Lighthouse audit completed

---

### Day 604-605: Performance Baseline Documentation
**Assigned to:** All Developers

#### Tasks
**Create Comprehensive Baseline Report**

```markdown
# Performance Baseline Report

**Date:** [Current Date]
**Version:** 1.0
**Environment:** Production

## Executive Summary

### Current Performance Metrics

| Metric | Current Value | Target | Status |
|--------|--------------|--------|--------|
| Page Load Time (P95) | 3.2s | < 2s | ❌ Needs Improvement |
| API Response Time (P95) | 450ms | < 200ms | ❌ Needs Improvement |
| Database Query Time (P95) | 180ms | < 50ms | ❌ Needs Improvement |
| Lighthouse Score | 75 | > 90 | ❌ Needs Improvement |
| Concurrent Users Supported | 200 | 1000+ | ❌ Needs Improvement |
| Error Rate | 0.5% | < 0.1% | ❌ Needs Improvement |

### Key Findings

1. **Database Performance**: 45% of slow requests due to unoptimized queries
2. **API Bottlenecks**: Top 10 endpoints account for 70% of slow responses
3. **Frontend Issues**: Large bundle sizes (2.5MB) causing slow initial load
4. **Memory Leaks**: Heap usage increases by 50MB/hour during peak times
5. **N+1 Queries**: Detected in 12 critical endpoints

## Detailed Analysis

[Include detailed analysis from all profiling activities]

## Optimization Priorities

### High Priority
1. Add database indexes (Expected: 60-80% improvement)
2. Implement Redis caching (Expected: 40-50% improvement)
3. Fix N+1 queries (Expected: 30-40% improvement)

### Medium Priority
4. Code splitting (Expected: 30% reduction in initial load)
5. Image optimization (Expected: 40% reduction in image size)
6. API response compression (Expected: 60% reduction in transfer size)

### Low Priority
7. CDN implementation
8. Service worker caching
9. Database connection pooling optimization

## Next Steps
1. Proceed with database optimization (Milestone 13.2)
2. Implement caching strategy (Milestone 13.5)
3. Frontend optimization (Milestone 13.4)
```

#### Deliverables
- [ ] Performance baseline report complete
- [ ] Optimization roadmap created
- [ ] Stakeholder presentation prepared
- [ ] Baseline metrics documented in monitoring system

---

## MILESTONE 13.2: Database Optimization (Days 606-610)

### Objective
Optimize database performance through query optimization, indexing, and partitioning.

### Day 606-607: Query Optimization & Indexing
**Assigned to:** Dev 1 (Lead), Dev 2

#### Tasks
**Dev 1: Index Strategy Implementation**
```sql
-- database/migrations/20260203_001_performance_indexes.sql

-- ============================================
-- PERFORMANCE INDEXES
-- ============================================

-- Users table indexes
CREATE INDEX CONCURRENTLY idx_users_email_active
ON users(email) WHERE status = 'active';

CREATE INDEX CONCURRENTLY idx_users_role_status
ON users(role, status) INCLUDE (id, name, email);

CREATE INDEX CONCURRENTLY idx_users_created_at_desc
ON users(created_at DESC);

-- Products table indexes
CREATE INDEX CONCURRENTLY idx_products_category_active
ON products(category_id) WHERE status = 'active';

CREATE INDEX CONCURRENTLY idx_products_farmer_status
ON products(farmer_id, status) INCLUDE (name, price, quantity);

CREATE INDEX CONCURRENTLY idx_products_search
ON products USING gin(to_tsvector('english', name || ' ' || description));

CREATE INDEX CONCURRENTLY idx_products_price_range
ON products(price) WHERE status = 'active';

-- Orders table indexes
CREATE INDEX CONCURRENTLY idx_orders_customer_status
ON orders(customer_id, status) INCLUDE (created_at, total_amount);

CREATE INDEX CONCURRENTLY idx_orders_farmer_date
ON orders(farmer_id, created_at DESC) WHERE status != 'cancelled';

CREATE INDEX CONCURRENTLY idx_orders_status_date
ON orders(status, created_at DESC);

CREATE INDEX CONCURRENTLY idx_orders_delivery_date
ON orders(delivery_date) WHERE status IN ('pending', 'confirmed', 'in_transit');

-- Order Items table indexes
CREATE INDEX CONCURRENTLY idx_order_items_order_id
ON order_items(order_id) INCLUDE (product_id, quantity, price);

CREATE INDEX CONCURRENTLY idx_order_items_product_id
ON order_items(product_id, created_at DESC);

-- Payments table indexes
CREATE INDEX CONCURRENTLY idx_payments_order_status
ON payments(order_id, status);

CREATE INDEX CONCURRENTLY idx_payments_created_date
ON payments(created_at DESC) WHERE status = 'completed';

CREATE INDEX CONCURRENTLY idx_payments_method_date
ON payments(payment_method, created_at DESC);

-- Reviews table indexes
CREATE INDEX CONCURRENTLY idx_reviews_product_approved
ON reviews(product_id) WHERE status = 'approved';

CREATE INDEX CONCURRENTLY idx_reviews_rating_date
ON reviews(rating, created_at DESC);

-- Notifications table indexes
CREATE INDEX CONCURRENTLY idx_notifications_user_read
ON notifications(user_id, is_read, created_at DESC);

CREATE INDEX CONCURRENTLY idx_notifications_type_created
ON notifications(type, created_at DESC);

-- Analytics tables indexes
CREATE INDEX CONCURRENTLY idx_page_views_date_page
ON page_views(created_at DESC, page_url);

CREATE INDEX CONCURRENTLY idx_sales_analytics_date
ON sales_analytics(date DESC) INCLUDE (total_sales, order_count);

-- Inventory table indexes
CREATE INDEX CONCURRENTLY idx_inventory_product_date
ON inventory(product_id, created_at DESC);

CREATE INDEX CONCURRENTLY idx_inventory_low_stock
ON inventory(product_id) WHERE quantity < reorder_level;

-- ============================================
-- COMPOSITE INDEXES FOR COMMON QUERIES
-- ============================================

-- Dashboard queries
CREATE INDEX CONCURRENTLY idx_orders_dashboard
ON orders(farmer_id, created_at DESC, status)
INCLUDE (total_amount, customer_id);

-- Product search and filtering
CREATE INDEX CONCURRENTLY idx_products_filter
ON products(category_id, price, status)
INCLUDE (name, image_url, farmer_id);

-- Order tracking
CREATE INDEX CONCURRENTLY idx_orders_tracking
ON orders(customer_id, status, created_at DESC)
INCLUDE (order_number, delivery_date);

-- ============================================
-- PARTIAL INDEXES FOR SPECIFIC CONDITIONS
-- ============================================

-- Active listings only
CREATE INDEX CONCURRENTLY idx_products_active_listings
ON products(created_at DESC)
WHERE status = 'active' AND quantity > 0;

-- Pending orders
CREATE INDEX CONCURRENTLY idx_orders_pending
ON orders(created_at)
WHERE status = 'pending';

-- Failed payments for retry
CREATE INDEX CONCURRENTLY idx_payments_failed
ON payments(created_at DESC)
WHERE status = 'failed';

-- ============================================
-- INDEX MAINTENANCE
-- ============================================

-- Create function to monitor index usage
CREATE OR REPLACE FUNCTION check_unused_indexes()
RETURNS TABLE (
    schemaname text,
    tablename text,
    indexname text,
    index_size text,
    index_scans bigint
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        s.schemaname::text,
        s.tablename::text,
        s.indexname::text,
        pg_size_pretty(pg_relation_size(s.indexrelid))::text,
        s.idx_scan
    FROM pg_stat_user_indexes s
    JOIN pg_index i ON s.indexrelid = i.indexrelid
    WHERE s.idx_scan < 100
        AND i.indisunique IS FALSE
        AND s.schemaname = 'public'
    ORDER BY pg_relation_size(s.indexrelid) DESC;
END;
$$ LANGUAGE plpgsql;

-- Create function to analyze index bloat
CREATE OR REPLACE FUNCTION check_index_bloat()
RETURNS TABLE (
    schemaname text,
    tablename text,
    indexname text,
    bloat_ratio numeric
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        nspname::text AS schemaname,
        tblname::text AS tablename,
        idxname::text AS indexname,
        ROUND((pgsi.idxpages::numeric - est_pages)/pgsi.idxpages::numeric * 100, 2) AS bloat_ratio
    FROM (
        SELECT
            schemaname,
            tablename AS tblname,
            indexname AS idxname,
            i.reltuples,
            i.relpages AS idxpages,
            coalesce(
                ceil((i.reltuples * (24 + 8)) / (8192 * 0.7)),
                0
            ) AS est_pages
        FROM pg_stat_user_indexes s
        JOIN pg_class i ON s.indexrelid = i.oid
    ) AS pgsi
    JOIN pg_namespace n ON n.nspname = pgsi.schemaname
    WHERE pgsi.idxpages > 10
        AND (pgsi.idxpages::numeric - est_pages)/pgsi.idxpages::numeric > 0.2
    ORDER BY bloat_ratio DESC;
END;
$$ LANGUAGE plpgsql;
```

**Dev 2: Query Optimization**
```javascript
// backend/src/database/queryOptimizer.js
const { QueryTypes } = require('sequelize');

class QueryOptimizer {
  constructor(sequelize) {
    this.sequelize = sequelize;
  }

  // Optimize eager loading to prevent N+1 queries
  async getOrdersWithDetails(userId, options = {}) {
    // BAD: N+1 Query Pattern
    // const orders = await Order.findAll({ where: { customer_id: userId } });
    // for (let order of orders) {
    //   order.items = await OrderItem.findAll({ where: { order_id: order.id } });
    // }

    // GOOD: Eager Loading
    const orders = await this.sequelize.models.Order.findAll({
      where: { customer_id: userId },
      include: [
        {
          model: this.sequelize.models.OrderItem,
          as: 'items',
          include: [
            {
              model: this.sequelize.models.Product,
              as: 'product',
              attributes: ['id', 'name', 'price', 'image_url']
            }
          ]
        },
        {
          model: this.sequelize.models.Payment,
          as: 'payment',
          attributes: ['id', 'status', 'amount', 'payment_method']
        },
        {
          model: this.sequelize.models.User,
          as: 'farmer',
          attributes: ['id', 'name', 'email', 'phone']
        }
      ],
      order: [['created_at', 'DESC']],
      limit: options.limit || 20,
      offset: options.offset || 0
    });

    return orders;
  }

  // Use raw queries for complex aggregations
  async getDashboardStats(farmerId) {
    // Use single optimized query instead of multiple queries
    const [stats] = await this.sequelize.query(`
      WITH order_stats AS (
        SELECT
          COUNT(*) as total_orders,
          COUNT(*) FILTER (WHERE status = 'pending') as pending_orders,
          COUNT(*) FILTER (WHERE status = 'completed') as completed_orders,
          COALESCE(SUM(total_amount), 0) as total_revenue,
          COALESCE(SUM(total_amount) FILTER (
            WHERE created_at >= CURRENT_DATE - INTERVAL '30 days'
          ), 0) as monthly_revenue
        FROM orders
        WHERE farmer_id = :farmerId
      ),
      product_stats AS (
        SELECT
          COUNT(*) as total_products,
          COUNT(*) FILTER (WHERE status = 'active') as active_products,
          COUNT(*) FILTER (WHERE quantity < 10) as low_stock_products
        FROM products
        WHERE farmer_id = :farmerId
      ),
      review_stats AS (
        SELECT
          COALESCE(AVG(r.rating), 0) as avg_rating,
          COUNT(*) as total_reviews
        FROM reviews r
        JOIN products p ON r.product_id = p.id
        WHERE p.farmer_id = :farmerId AND r.status = 'approved'
      )
      SELECT
        os.*,
        ps.*,
        rs.*
      FROM order_stats os
      CROSS JOIN product_stats ps
      CROSS JOIN review_stats rs
    `, {
      replacements: { farmerId },
      type: QueryTypes.SELECT
    });

    return stats;
  }

  // Optimize pagination with cursor-based approach
  async getProductsPaginated(options = {}) {
    const { cursor, limit = 20, category, minPrice, maxPrice } = options;

    let whereClause = { status: 'active' };

    if (cursor) {
      whereClause.id = { [Op.lt]: cursor };
    }

    if (category) {
      whereClause.category_id = category;
    }

    if (minPrice || maxPrice) {
      whereClause.price = {};
      if (minPrice) whereClause.price[Op.gte] = minPrice;
      if (maxPrice) whereClause.price[Op.lte] = maxPrice;
    }

    const products = await this.sequelize.models.Product.findAll({
      where: whereClause,
      include: [
        {
          model: this.sequelize.models.User,
          as: 'farmer',
          attributes: ['id', 'name', 'location']
        },
        {
          model: this.sequelize.models.Review,
          as: 'reviews',
          attributes: [],
          required: false
        }
      ],
      attributes: {
        include: [
          [
            this.sequelize.fn('AVG', this.sequelize.col('reviews.rating')),
            'avg_rating'
          ],
          [
            this.sequelize.fn('COUNT', this.sequelize.col('reviews.id')),
            'review_count'
          ]
        ]
      },
      group: ['Product.id', 'farmer.id'],
      order: [['created_at', 'DESC']],
      limit: limit + 1 // Fetch one extra to determine if there are more
    });

    const hasMore = products.length > limit;
    const items = hasMore ? products.slice(0, limit) : products;
    const nextCursor = hasMore ? items[items.length - 1].id : null;

    return {
      items,
      nextCursor,
      hasMore
    };
  }

  // Use materialized views for complex reports
  async createMaterializedViews() {
    // Sales summary view
    await this.sequelize.query(`
      CREATE MATERIALIZED VIEW IF NOT EXISTS mv_sales_summary AS
      SELECT
        DATE(o.created_at) as date,
        o.farmer_id,
        COUNT(*) as order_count,
        SUM(o.total_amount) as total_sales,
        AVG(o.total_amount) as avg_order_value,
        COUNT(DISTINCT o.customer_id) as unique_customers
      FROM orders o
      WHERE o.status = 'completed'
      GROUP BY DATE(o.created_at), o.farmer_id;

      CREATE UNIQUE INDEX ON mv_sales_summary (date, farmer_id);
    `);

    // Product performance view
    await this.sequelize.query(`
      CREATE MATERIALIZED VIEW IF NOT EXISTS mv_product_performance AS
      SELECT
        p.id as product_id,
        p.name,
        p.farmer_id,
        COUNT(oi.id) as times_ordered,
        SUM(oi.quantity) as total_quantity_sold,
        SUM(oi.quantity * oi.price) as total_revenue,
        AVG(r.rating) as avg_rating,
        COUNT(r.id) as review_count
      FROM products p
      LEFT JOIN order_items oi ON p.id = oi.product_id
      LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
      GROUP BY p.id, p.name, p.farmer_id;

      CREATE UNIQUE INDEX ON mv_product_performance (product_id);
    `);

    // Create refresh function
    await this.sequelize.query(`
      CREATE OR REPLACE FUNCTION refresh_materialized_views()
      RETURNS void AS $$
      BEGIN
        REFRESH MATERIALIZED VIEW CONCURRENTLY mv_sales_summary;
        REFRESH MATERIALIZED VIEW CONCURRENTLY mv_product_performance;
      END;
      $$ LANGUAGE plpgsql;
    `);

    // Schedule automatic refresh (using pg_cron if available)
    await this.sequelize.query(`
      SELECT cron.schedule(
        'refresh-materialized-views',
        '0 2 * * *',  -- Daily at 2 AM
        'SELECT refresh_materialized_views();'
      );
    `);
  }

  // Batch operations for better performance
  async bulkUpdateInventory(updates) {
    // Use batch update instead of individual updates
    const cases = updates.map(u =>
      `WHEN id = ${u.productId} THEN quantity + ${u.quantityChange}`
    ).join(' ');

    const ids = updates.map(u => u.productId).join(',');

    await this.sequelize.query(`
      UPDATE inventory
      SET
        quantity = CASE ${cases} END,
        updated_at = CURRENT_TIMESTAMP
      WHERE product_id IN (${ids})
    `);
  }

  // Query plan analysis
  async analyzeQuery(sql, params = {}) {
    const [analysis] = await this.sequelize.query(
      `EXPLAIN (ANALYZE, BUFFERS, FORMAT JSON) ${sql}`,
      { replacements: params }
    );

    const plan = analysis[0]['QUERY PLAN'][0];

    return {
      executionTime: plan['Execution Time'],
      planningTime: plan['Planning Time'],
      totalTime: plan['Execution Time'] + plan['Planning Time'],
      plan: plan.Plan,
      recommendations: this.generateRecommendations(plan)
    };
  }

  generateRecommendations(plan) {
    const recommendations = [];

    // Check for sequential scans
    if (JSON.stringify(plan).includes('Seq Scan')) {
      recommendations.push({
        type: 'INDEX',
        priority: 'HIGH',
        message: 'Sequential scan detected. Consider adding an index.'
      });
    }

    // Check for high execution time
    if (plan['Execution Time'] > 100) {
      recommendations.push({
        type: 'PERFORMANCE',
        priority: 'HIGH',
        message: 'High execution time. Review query complexity.'
      });
    }

    // Check for nested loops with high row counts
    const checkNestedLoops = (node) => {
      if (node['Node Type'] === 'Nested Loop' && node['Actual Rows'] > 1000) {
        recommendations.push({
          type: 'JOIN',
          priority: 'MEDIUM',
          message: 'Consider using hash join instead of nested loop for large datasets.'
        });
      }
      if (node.Plans) {
        node.Plans.forEach(checkNestedLoops);
      }
    };
    checkNestedLoops(plan.Plan);

    return recommendations;
  }
}

module.exports = QueryOptimizer;
```

#### Deliverables
- [ ] Database indexes created
- [ ] N+1 queries eliminated
- [ ] Query optimization complete
- [ ] Query performance improved by 60%+

---

### Day 608-609: Connection Pooling & Partitioning
**Assigned to:** Dev 1, Dev 2, Dev 3

#### Tasks
**Dev 1: Advanced Connection Pool Configuration**
```javascript
// backend/src/config/database.js
const { Sequelize } = require('sequelize');
const { Pool } = require('pg');

class DatabaseManager {
  constructor() {
    this.readPool = null;
    this.writePool = null;
    this.sequelize = null;
  }

  initializePools() {
    // Write pool configuration (smaller, optimized for writes)
    this.writePool = new Pool({
      host: process.env.DB_WRITE_HOST,
      port: process.env.DB_PORT || 5432,
      database: process.env.DB_NAME,
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      max: 20,                    // Maximum pool size
      min: 5,                     // Minimum pool size
      idleTimeoutMillis: 30000,   // Close idle clients after 30s
      connectionTimeoutMillis: 5000,
      maxUses: 7500,              // Close connection after 7500 uses
      application_name: 'dairy_platform_write'
    });

    // Read pool configuration (larger, optimized for reads)
    this.readPool = new Pool({
      host: process.env.DB_READ_HOST || process.env.DB_WRITE_HOST,
      port: process.env.DB_PORT || 5432,
      database: process.env.DB_NAME,
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      max: 50,                    // Larger pool for reads
      min: 10,
      idleTimeoutMillis: 30000,
      connectionTimeoutMillis: 5000,
      maxUses: 7500,
      application_name: 'dairy_platform_read'
    });

    // Sequelize with replication
    this.sequelize = new Sequelize({
      dialect: 'postgres',
      replication: {
        read: [
          {
            host: process.env.DB_READ_HOST_1,
            username: process.env.DB_USER,
            password: process.env.DB_PASSWORD
          },
          {
            host: process.env.DB_READ_HOST_2,
            username: process.env.DB_USER,
            password: process.env.DB_PASSWORD
          }
        ],
        write: {
          host: process.env.DB_WRITE_HOST,
          username: process.env.DB_USER,
          password: process.env.DB_PASSWORD
        }
      },
      pool: {
        max: 100,
        min: 20,
        acquire: 30000,
        idle: 10000,
        evict: 1000
      },
      logging: process.env.NODE_ENV === 'development' ? console.log : false,
      benchmark: true,
      dialectOptions: {
        statement_timeout: 10000, // 10 second timeout
        idle_in_transaction_session_timeout: 30000
      }
    });

    // Connection pool monitoring
    this.setupPoolMonitoring();
  }

  setupPoolMonitoring() {
    const monitor = setInterval(() => {
      console.log('Write Pool Stats:', {
        total: this.writePool.totalCount,
        idle: this.writePool.idleCount,
        waiting: this.writePool.waitingCount
      });

      console.log('Read Pool Stats:', {
        total: this.readPool.totalCount,
        idle: this.readPool.idleCount,
        waiting: this.readPool.waitingCount
      });

      // Alert if pool is exhausted
      if (this.writePool.waitingCount > 5) {
        console.warn('Write pool under pressure!');
      }
      if (this.readPool.waitingCount > 10) {
        console.warn('Read pool under pressure!');
      }
    }, 60000); // Check every minute

    // Cleanup on shutdown
    process.on('SIGTERM', () => {
      clearInterval(monitor);
      this.cleanup();
    });
  }

  async cleanup() {
    await this.writePool.end();
    await this.readPool.end();
    await this.sequelize.close();
  }

  getReadConnection() {
    return this.readPool.connect();
  }

  getWriteConnection() {
    return this.writePool.connect();
  }

  getSequelize() {
    return this.sequelize;
  }
}

module.exports = new DatabaseManager();
```

**Dev 2: Table Partitioning**
```sql
-- database/migrations/20260203_002_table_partitioning.sql

-- ============================================
-- PARTITION ORDERS TABLE BY DATE
-- ============================================

-- Create new partitioned orders table
CREATE TABLE orders_new (
    id BIGSERIAL,
    order_number VARCHAR(50) NOT NULL,
    customer_id INTEGER NOT NULL,
    farmer_id INTEGER NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL,
    delivery_date DATE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, created_at)
) PARTITION BY RANGE (created_at);

-- Create partitions for each month
CREATE TABLE orders_2024_01 PARTITION OF orders_new
    FOR VALUES FROM ('2024-01-01') TO ('2024-02-01');

CREATE TABLE orders_2024_02 PARTITION OF orders_new
    FOR VALUES FROM ('2024-02-01') TO ('2024-03-01');

CREATE TABLE orders_2024_03 PARTITION OF orders_new
    FOR VALUES FROM ('2024-03-01') TO ('2024-04-01');

CREATE TABLE orders_2024_04 PARTITION OF orders_new
    FOR VALUES FROM ('2024-04-01') TO ('2024-05-01');

CREATE TABLE orders_2024_05 PARTITION OF orders_new
    FOR VALUES FROM ('2024-05-01') TO ('2024-06-01');

CREATE TABLE orders_2024_06 PARTITION OF orders_new
    FOR VALUES FROM ('2024-06-01') TO ('2024-07-01');

CREATE TABLE orders_2024_07 PARTITION OF orders_new
    FOR VALUES FROM ('2024-07-01') TO ('2024-08-01');

CREATE TABLE orders_2024_08 PARTITION OF orders_new
    FOR VALUES FROM ('2024-08-01') TO ('2024-09-01');

CREATE TABLE orders_2024_09 PARTITION OF orders_new
    FOR VALUES FROM ('2024-09-01') TO ('2024-10-01');

CREATE TABLE orders_2024_10 PARTITION OF orders_new
    FOR VALUES FROM ('2024-10-01') TO ('2024-11-01');

CREATE TABLE orders_2024_11 PARTITION OF orders_new
    FOR VALUES FROM ('2024-11-01') TO ('2024-12-01');

CREATE TABLE orders_2024_12 PARTITION OF orders_new
    FOR VALUES FROM ('2024-12-01') TO ('2025-01-01');

CREATE TABLE orders_2025_01 PARTITION OF orders_new
    FOR VALUES FROM ('2025-01-01') TO ('2025-02-01');

CREATE TABLE orders_2025_02 PARTITION OF orders_new
    FOR VALUES FROM ('2025-02-01') TO ('2025-03-01');

-- Create default partition for future data
CREATE TABLE orders_default PARTITION OF orders_new DEFAULT;

-- Copy data from old table
INSERT INTO orders_new SELECT * FROM orders;

-- Rename tables
ALTER TABLE orders RENAME TO orders_old;
ALTER TABLE orders_new RENAME TO orders;

-- Create indexes on partitioned table
CREATE INDEX idx_orders_customer_part ON orders(customer_id, created_at DESC);
CREATE INDEX idx_orders_farmer_part ON orders(farmer_id, created_at DESC);
CREATE INDEX idx_orders_status_part ON orders(status, created_at DESC);

-- ============================================
-- AUTO-CREATE FUTURE PARTITIONS
-- ============================================

CREATE OR REPLACE FUNCTION create_monthly_partition()
RETURNS void AS $$
DECLARE
    partition_date DATE;
    partition_name TEXT;
    start_date TEXT;
    end_date TEXT;
BEGIN
    -- Get first day of next month
    partition_date := DATE_TRUNC('month', CURRENT_DATE + INTERVAL '1 month');
    partition_name := 'orders_' || TO_CHAR(partition_date, 'YYYY_MM');
    start_date := TO_CHAR(partition_date, 'YYYY-MM-DD');
    end_date := TO_CHAR(partition_date + INTERVAL '1 month', 'YYYY-MM-DD');

    -- Create partition if it doesn't exist
    IF NOT EXISTS (
        SELECT 1 FROM pg_class WHERE relname = partition_name
    ) THEN
        EXECUTE format(
            'CREATE TABLE %I PARTITION OF orders FOR VALUES FROM (%L) TO (%L)',
            partition_name,
            start_date,
            end_date
        );

        RAISE NOTICE 'Created partition: %', partition_name;
    END IF;
END;
$$ LANGUAGE plpgsql;

-- Schedule monthly partition creation
SELECT cron.schedule(
    'create-monthly-orders-partition',
    '0 0 1 * *',  -- First day of every month
    'SELECT create_monthly_partition();'
);

-- ============================================
-- PARTITION PAGE_VIEWS TABLE
-- ============================================

CREATE TABLE page_views_new (
    id BIGSERIAL,
    user_id INTEGER,
    page_url VARCHAR(500) NOT NULL,
    referrer VARCHAR(500),
    user_agent TEXT,
    ip_address INET,
    session_id VARCHAR(100),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, created_at)
) PARTITION BY RANGE (created_at);

-- Create weekly partitions (last 12 weeks)
DO $$
DECLARE
    i INTEGER;
    start_date DATE;
    end_date DATE;
    partition_name TEXT;
BEGIN
    FOR i IN 0..11 LOOP
        start_date := CURRENT_DATE - (i * 7);
        end_date := start_date + 7;
        partition_name := 'page_views_' || TO_CHAR(start_date, 'YYYY_WW');

        EXECUTE format(
            'CREATE TABLE %I PARTITION OF page_views_new FOR VALUES FROM (%L) TO (%L)',
            partition_name,
            start_date,
            end_date
        );
    END LOOP;
END $$;

-- Default partition
CREATE TABLE page_views_default PARTITION OF page_views_new DEFAULT;

-- ============================================
-- PARTITION AUDIT_LOGS TABLE
-- ============================================

CREATE TABLE audit_logs_new (
    id BIGSERIAL,
    user_id INTEGER,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id INTEGER,
    old_values JSONB,
    new_values JSONB,
    ip_address INET,
    user_agent TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, created_at)
) PARTITION BY RANGE (created_at);

-- Create monthly partitions
DO $$
DECLARE
    i INTEGER;
    start_date DATE;
    end_date DATE;
    partition_name TEXT;
BEGIN
    FOR i IN 0..11 LOOP
        start_date := DATE_TRUNC('month', CURRENT_DATE - (i || ' months')::INTERVAL);
        end_date := start_date + INTERVAL '1 month';
        partition_name := 'audit_logs_' || TO_CHAR(start_date, 'YYYY_MM');

        EXECUTE format(
            'CREATE TABLE %I PARTITION OF audit_logs_new FOR VALUES FROM (%L) TO (%L)',
            partition_name,
            start_date,
            end_date
        );
    END LOOP;
END $$;

-- Default partition
CREATE TABLE audit_logs_default PARTITION OF audit_logs_new DEFAULT;

-- ============================================
-- PARTITION MAINTENANCE FUNCTIONS
-- ============================================

-- Drop old partitions (keep last 12 months for orders)
CREATE OR REPLACE FUNCTION drop_old_partitions()
RETURNS void AS $$
DECLARE
    cutoff_date DATE;
    partition_rec RECORD;
BEGIN
    cutoff_date := CURRENT_DATE - INTERVAL '12 months';

    FOR partition_rec IN
        SELECT tablename
        FROM pg_tables
        WHERE schemaname = 'public'
        AND tablename LIKE 'orders_20%'
        AND tablename < 'orders_' || TO_CHAR(cutoff_date, 'YYYY_MM')
    LOOP
        EXECUTE 'DROP TABLE IF EXISTS ' || partition_rec.tablename;
        RAISE NOTICE 'Dropped old partition: %', partition_rec.tablename;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Schedule monthly cleanup
SELECT cron.schedule(
    'drop-old-partitions',
    '0 3 1 * *',  -- First day of month at 3 AM
    'SELECT drop_old_partitions();'
);
```

**Dev 3: Database Monitoring Dashboard**
```javascript
// backend/src/routes/admin/databaseMonitoring.js
const express = require('express');
const router = express.Router();
const { QueryTypes } = require('sequelize');

router.get('/database/stats', async (req, res) => {
  try {
    // Database size
    const [dbSize] = await req.db.query(`
      SELECT
        pg_size_pretty(pg_database_size(current_database())) as size
    `, { type: QueryTypes.SELECT });

    // Table sizes
    const tableSizes = await req.db.query(`
      SELECT
        schemaname,
        tablename,
        pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) as size,
        pg_total_relation_size(schemaname||'.'||tablename) as bytes
      FROM pg_tables
      WHERE schemaname = 'public'
      ORDER BY bytes DESC
      LIMIT 20
    `, { type: QueryTypes.SELECT });

    // Index usage
    const indexUsage = await req.db.query(`
      SELECT
        schemaname,
        tablename,
        indexname,
        idx_scan,
        idx_tup_read,
        idx_tup_fetch,
        pg_size_pretty(pg_relation_size(indexrelid)) as size
      FROM pg_stat_user_indexes
      ORDER BY idx_scan DESC
      LIMIT 20
    `, { type: QueryTypes.SELECT });

    // Connection stats
    const [connectionStats] = await req.db.query(`
      SELECT
        count(*) as total_connections,
        count(*) FILTER (WHERE state = 'active') as active_connections,
        count(*) FILTER (WHERE state = 'idle') as idle_connections,
        count(*) FILTER (WHERE state = 'idle in transaction') as idle_in_transaction
      FROM pg_stat_activity
      WHERE datname = current_database()
    `, { type: QueryTypes.SELECT });

    // Cache hit ratio
    const [cacheHitRatio] = await req.db.query(`
      SELECT
        sum(heap_blks_read) as heap_read,
        sum(heap_blks_hit) as heap_hit,
        sum(heap_blks_hit) / (sum(heap_blks_hit) + sum(heap_blks_read)) * 100 as cache_hit_ratio
      FROM pg_statio_user_tables
    `, { type: QueryTypes.SELECT });

    // Long running queries
    const longRunningQueries = await req.db.query(`
      SELECT
        pid,
        now() - query_start as duration,
        query,
        state
      FROM pg_stat_activity
      WHERE state != 'idle'
        AND query NOT LIKE '%pg_stat_activity%'
      ORDER BY duration DESC
      LIMIT 10
    `, { type: QueryTypes.SELECT });

    res.json({
      database: dbSize,
      tables: tableSizes,
      indexes: indexUsage,
      connections: connectionStats,
      cacheHitRatio: cacheHitRatio.cache_hit_ratio,
      longRunningQueries
    });
  } catch (error) {
    console.error('Database stats error:', error);
    res.status(500).json({ error: error.message });
  }
});

module.exports = router;
```

#### Deliverables
- [ ] Connection pooling optimized
- [ ] Table partitioning implemented
- [ ] Database monitoring dashboard created
- [ ] Performance benchmarks documented

---

### Day 610: Database Optimization Validation
**Assigned to:** All Developers

#### Tasks
**Run comprehensive database performance tests**

```javascript
// tests/performance/database-benchmark.js
const { performance } = require('perf_hooks');

class DatabaseBenchmark {
  constructor(sequelize) {
    this.sequelize = sequelize;
    this.results = [];
  }

  async runBenchmark(name, queryFn, iterations = 100) {
    const times = [];

    for (let i = 0; i < iterations; i++) {
      const start = performance.now();
      await queryFn();
      const end = performance.now();
      times.push(end - start);
    }

    const sorted = times.sort((a, b) => a - b);
    const result = {
      name,
      iterations,
      min: sorted[0].toFixed(2),
      max: sorted[sorted.length - 1].toFixed(2),
      avg: (times.reduce((a, b) => a + b) / times.length).toFixed(2),
      p50: sorted[Math.floor(sorted.length * 0.5)].toFixed(2),
      p95: sorted[Math.floor(sorted.length * 0.95)].toFixed(2),
      p99: sorted[Math.floor(sorted.length * 0.99)].toFixed(2)
    };

    this.results.push(result);
    return result;
  }

  async runAllBenchmarks() {
    console.log('Starting database benchmarks...\n');

    // Simple SELECT
    await this.runBenchmark(
      'Simple SELECT by ID',
      () => this.sequelize.models.Product.findByPk(1)
    );

    // Complex JOIN
    await this.runBenchmark(
      'Complex JOIN with eager loading',
      () => this.sequelize.models.Order.findAll({
        include: ['items', 'customer', 'farmer'],
        limit: 20
      })
    );

    // Aggregation
    await this.runBenchmark(
      'Aggregation query',
      () => this.sequelize.models.Order.count({
        where: { status: 'completed' },
        group: ['farmer_id']
      })
    );

    // Full-text search
    await this.runBenchmark(
      'Full-text search',
      () => this.sequelize.query(
        'SELECT * FROM products WHERE to_tsvector(name) @@ to_tsquery(:search) LIMIT 20',
        { replacements: { search: 'milk' } }
      )
    );

    console.log('\nBenchmark Results:');
    console.table(this.results);

    return this.results;
  }
}

module.exports = DatabaseBenchmark;
```

#### Deliverables
- [ ] Database optimization validated
- [ ] Performance benchmarks completed
- [ ] Optimization report generated
- [ ] Database performance improved by 70%+

---

## MILESTONE 13.3: API Performance (Days 611-615)

[Continued in next section due to length...]

### Objective
Optimize API response times through caching, compression, and rate limiting.

### Day 611-612: Response Time Optimization
**Assigned to:** Dev 1, Dev 2, Dev 3

#### Tasks
**Dev 1: API Response Compression**
```javascript
// backend/src/middleware/compression.js
const compression = require('compression');
const zlib = require('zlib');

// Advanced compression middleware
const compressionMiddleware = compression({
  filter: (req, res) => {
    // Don't compress responses if client doesn't support it
    if (req.headers['x-no-compression']) {
      return false;
    }

    // Compress all text-based responses
    return compression.filter(req, res);
  },
  level: zlib.constants.Z_BEST_COMPRESSION,
  threshold: 1024, // Only compress responses larger than 1KB
  strategy: zlib.constants.Z_RLE
});

// Response optimization middleware
const optimizeResponse = (req, res, next) => {
  const originalJson = res.json.bind(res);

  res.json = function(data) {
    // Remove null/undefined values
    const cleaned = removeEmpty(data);

    // Add cache headers for cacheable responses
    if (req.method === 'GET' && !req.url.includes('/api/auth')) {
      res.set('Cache-Control', 'public, max-age=300'); // 5 minutes
    }

    // Add ETag for conditional requests
    const etag = generateETag(cleaned);
    res.set('ETag', etag);

    // Check if client has cached version
    if (req.headers['if-none-match'] === etag) {
      return res.status(304).end();
    }

    return originalJson(cleaned);
  };

  next();
};

function removeEmpty(obj) {
  if (Array.isArray(obj)) {
    return obj.map(removeEmpty);
  } else if (obj !== null && typeof obj === 'object') {
    return Object.entries(obj)
      .filter(([_, v]) => v !== null && v !== undefined)
      .reduce((acc, [k, v]) => ({ ...acc, [k]: removeEmpty(v) }), {});
  }
  return obj;
}

function generateETag(data) {
  const crypto = require('crypto');
  return crypto
    .createHash('md5')
    .update(JSON.stringify(data))
    .digest('hex');
}

module.exports = {
  compressionMiddleware,
  optimizeResponse
};
```

**Dev 2: API Response Caching**
```javascript
// backend/src/middleware/apiCache.js
const redis = require('redis');
const crypto = require('crypto');

class ApiCache {
  constructor() {
    this.client = redis.createClient({
      host: process.env.REDIS_HOST,
      port: process.env.REDIS_PORT,
      password: process.env.REDIS_PASSWORD
    });

    this.client.on('error', (err) => {
      console.error('Redis Client Error:', err);
    });

    this.client.connect();
  }

  generateKey(req) {
    const key = {
      path: req.path,
      query: req.query,
      user: req.user?.id
    };
    return `api_cache:${crypto.createHash('md5').update(JSON.stringify(key)).digest('hex')}`;
  }

  async get(key) {
    try {
      const cached = await this.client.get(key);
      return cached ? JSON.parse(cached) : null;
    } catch (error) {
      console.error('Cache get error:', error);
      return null;
    }
  }

  async set(key, value, ttl = 300) {
    try {
      await this.client.setEx(key, ttl, JSON.stringify(value));
    } catch (error) {
      console.error('Cache set error:', error);
    }
  }

  async invalidate(pattern) {
    try {
      const keys = await this.client.keys(pattern);
      if (keys.length > 0) {
        await this.client.del(keys);
      }
    } catch (error) {
      console.error('Cache invalidate error:', error);
    }
  }

  middleware(options = {}) {
    const { ttl = 300, private = false } = options;

    return async (req, res, next) => {
      // Only cache GET requests
      if (req.method !== 'GET') {
        return next();
      }

      const key = this.generateKey(req);

      // Try to get from cache
      const cached = await this.get(key);
      if (cached) {
        res.set('X-Cache', 'HIT');
        return res.json(cached);
      }

      // Override res.json to cache response
      const originalJson = res.json.bind(res);
      res.json = (data) => {
        this.set(key, data, ttl);
        res.set('X-Cache', 'MISS');
        return originalJson(data);
      };

      next();
    };
  }

  // Cache invalidation on data changes
  invalidateOnChange(pattern) {
    return async (req, res, next) => {
      const originalJson = res.json.bind(res);

      res.json = async (data) => {
        // Invalidate related caches after successful mutation
        if (res.statusCode < 400) {
          await this.invalidate(pattern);
        }
        return originalJson(data);
      };

      next();
    };
  }
}

module.exports = new ApiCache();
```

**Dev 3: Database Query Result Caching**
```javascript
// backend/src/utils/queryCache.js
class QueryCache {
  constructor(redis, sequelize) {
    this.redis = redis;
    this.sequelize = sequelize;
    this.defaultTTL = 300; // 5 minutes
  }

  generateKey(model, options) {
    const crypto = require('crypto');
    const key = {
      model: model.name,
      where: options.where,
      include: options.include,
      order: options.order,
      limit: options.limit,
      offset: options.offset
    };
    return `query_cache:${crypto.createHash('md5').update(JSON.stringify(key)).digest('hex')}`;
  }

  async cachedFind(model, options = {}, ttl = this.defaultTTL) {
    const key = this.generateKey(model, options);

    // Try cache first
    const cached = await this.redis.get(key);
    if (cached) {
      return JSON.parse(cached);
    }

    // Query database
    const result = await model.findAll(options);

    // Cache result
    await this.redis.setEx(key, ttl, JSON.stringify(result));

    return result;
  }

  async cachedCount(model, options = {}, ttl = this.defaultTTL) {
    const key = `count:${this.generateKey(model, options)}`;

    const cached = await this.redis.get(key);
    if (cached) {
      return parseInt(cached);
    }

    const result = await model.count(options);
    await this.redis.setEx(key, ttl, result.toString());

    return result;
  }

  async invalidateModel(modelName) {
    const pattern = `query_cache:*${modelName}*`;
    const keys = await this.redis.keys(pattern);

    if (keys.length > 0) {
      await this.redis.del(keys);
    }
  }

  // Automatic cache invalidation hooks
  setupInvalidationHooks() {
    const models = Object.keys(this.sequelize.models);

    models.forEach(modelName => {
      const model = this.sequelize.models[modelName];

      ['afterCreate', 'afterUpdate', 'afterDestroy'].forEach(hookName => {
        model.addHook(hookName, async () => {
          await this.invalidateModel(modelName);
        });
      });
    });
  }
}

module.exports = QueryCache;
```

#### Deliverables
- [ ] Response compression implemented
- [ ] API caching configured
- [ ] Query result caching active
- [ ] Response times improved by 40%+

---

### Day 613-614: Rate Limiting & Throttling
**Assigned to:** Dev 1, Dev 2

#### Tasks
**Dev 1: Advanced Rate Limiting**
```javascript
// backend/src/middleware/rateLimiter.js
const rateLimit = require('express-rate-limit');
const RedisStore = require('rate-limit-redis');
const redis = require('redis');

const redisClient = redis.createClient({
  host: process.env.REDIS_HOST,
  port: process.env.REDIS_PORT,
  password: process.env.REDIS_PASSWORD
});

// General API rate limiter
const apiLimiter = rateLimit({
  store: new RedisStore({
    client: redisClient,
    prefix: 'rl:api:'
  }),
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100, // Max 100 requests per window
  message: 'Too many requests from this IP, please try again later.',
  standardHeaders: true,
  legacyHeaders: false,
  handler: (req, res) => {
    res.status(429).json({
      error: 'Too Many Requests',
      message: 'Rate limit exceeded. Please try again later.',
      retryAfter: res.getHeader('Retry-After')
    });
  }
});

// Stricter rate limit for auth endpoints
const authLimiter = rateLimit({
  store: new RedisStore({
    client: redisClient,
    prefix: 'rl:auth:'
  }),
  windowMs: 15 * 60 * 1000,
  max: 5, // Max 5 login attempts per 15 minutes
  skipSuccessfulRequests: true,
  message: 'Too many login attempts, please try again later.'
});

// Per-user rate limiting
const userLimiter = rateLimit({
  store: new RedisStore({
    client: redisClient,
    prefix: 'rl:user:'
  }),
  windowMs: 60 * 1000, // 1 minute
  max: 60, // 60 requests per minute per user
  keyGenerator: (req) => {
    return req.user?.id?.toString() || req.ip;
  }
});

// Endpoint-specific rate limiting
const createOrderLimiter = rateLimit({
  store: new RedisStore({
    client: redisClient,
    prefix: 'rl:orders:'
  }),
  windowMs: 60 * 60 * 1000, // 1 hour
  max: 20, // Max 20 orders per hour per user
  keyGenerator: (req) => req.user.id.toString()
});

// Adaptive rate limiting based on server load
class AdaptiveRateLimiter {
  constructor() {
    this.baseLimit = 100;
    this.currentLimit = 100;
    this.checkInterval = setInterval(() => this.adjustLimit(), 60000);
  }

  async adjustLimit() {
    const cpuUsage = process.cpuUsage();
    const memUsage = process.memoryUsage();

    // Reduce limit if CPU > 80% or Memory > 80%
    const cpuPercent = cpuUsage.user / 1000000; // Convert to percentage
    const memPercent = (memUsage.heapUsed / memUsage.heapTotal) * 100;

    if (cpuPercent > 80 || memPercent > 80) {
      this.currentLimit = Math.max(50, this.currentLimit * 0.8);
      console.log(`Rate limit reduced to ${this.currentLimit} due to high load`);
    } else if (cpuPercent < 50 && memPercent < 50) {
      this.currentLimit = Math.min(this.baseLimit, this.currentLimit * 1.1);
    }
  }

  middleware() {
    return rateLimit({
      store: new RedisStore({
        client: redisClient,
        prefix: 'rl:adaptive:'
      }),
      windowMs: 60 * 1000,
      max: () => this.currentLimit,
      keyGenerator: (req) => req.user?.id?.toString() || req.ip
    });
  }

  cleanup() {
    clearInterval(this.checkInterval);
  }
}

// Token bucket algorithm for more sophisticated rate limiting
class TokenBucket {
  constructor(capacity, refillRate) {
    this.capacity = capacity;
    this.tokens = capacity;
    this.refillRate = refillRate;
    this.lastRefill = Date.now();
  }

  refill() {
    const now = Date.now();
    const timePassed = (now - this.lastRefill) / 1000;
    const tokensToAdd = timePassed * this.refillRate;

    this.tokens = Math.min(this.capacity, this.tokens + tokensToAdd);
    this.lastRefill = now;
  }

  consume(tokens = 1) {
    this.refill();

    if (this.tokens >= tokens) {
      this.tokens -= tokens;
      return true;
    }

    return false;
  }

  middleware(tokensPerRequest = 1) {
    return (req, res, next) => {
      if (this.consume(tokensPerRequest)) {
        res.set('X-RateLimit-Remaining', Math.floor(this.tokens));
        next();
      } else {
        res.status(429).json({
          error: 'Too Many Requests',
          message: 'Rate limit exceeded'
        });
      }
    };
  }
}

module.exports = {
  apiLimiter,
  authLimiter,
  userLimiter,
  createOrderLimiter,
  AdaptiveRateLimiter,
  TokenBucket
};
```

**Dev 2: Request Throttling & Queuing**
```javascript
// backend/src/middleware/requestQueue.js
const Queue = require('bull');
const redis = require('redis');

class RequestQueueManager {
  constructor() {
    this.queues = new Map();
    this.redisClient = redis.createClient({
      host: process.env.REDIS_HOST,
      port: process.env.REDIS_PORT
    });
  }

  createQueue(name, options = {}) {
    const queue = new Queue(name, {
      redis: {
        host: process.env.REDIS_HOST,
        port: process.env.REDIS_PORT
      },
      defaultJobOptions: {
        attempts: options.attempts || 3,
        backoff: {
          type: 'exponential',
          delay: 2000
        },
        removeOnComplete: true,
        removeOnFail: false
      }
    });

    this.queues.set(name, queue);
    return queue;
  }

  async addJob(queueName, data, options = {}) {
    const queue = this.queues.get(queueName);
    if (!queue) {
      throw new Error(`Queue ${queueName} not found`);
    }

    return await queue.add(data, {
      priority: options.priority || 1,
      delay: options.delay || 0
    });
  }

  // Middleware to queue heavy operations
  queueMiddleware(queueName, processor) {
    return async (req, res, next) => {
      try {
        const job = await this.addJob(queueName, {
          path: req.path,
          method: req.method,
          body: req.body,
          query: req.query,
          user: req.user
        });

        res.json({
          message: 'Request queued for processing',
          jobId: job.id,
          statusUrl: `/api/jobs/${job.id}/status`
        });
      } catch (error) {
        next(error);
      }
    };
  }

  // Process queued jobs
  processQueue(queueName, processor, concurrency = 5) {
    const queue = this.queues.get(queueName);
    if (!queue) {
      throw new Error(`Queue ${queueName} not found`);
    }

    queue.process(concurrency, async (job) => {
      try {
        const result = await processor(job.data);
        return result;
      } catch (error) {
        console.error(`Job ${job.id} failed:`, error);
        throw error;
      }
    });

    // Event handlers
    queue.on('completed', (job, result) => {
      console.log(`Job ${job.id} completed:`, result);
    });

    queue.on('failed', (job, err) => {
      console.error(`Job ${job.id} failed:`, err);
    });

    queue.on('stalled', (job) => {
      console.warn(`Job ${job.id} stalled`);
    });
  }

  // Throttle expensive operations
  throttle(fn, limit, window) {
    const calls = new Map();

    return async function(...args) {
      const key = JSON.stringify(args);
      const now = Date.now();

      if (!calls.has(key)) {
        calls.set(key, []);
      }

      const timestamps = calls.get(key);
      const validTimestamps = timestamps.filter(t => now - t < window);

      if (validTimestamps.length >= limit) {
        throw new Error('Rate limit exceeded');
      }

      validTimestamps.push(now);
      calls.set(key, validTimestamps);

      return await fn.apply(this, args);
    };
  }
}

module.exports = new RequestQueueManager();
```

#### Deliverables
- [ ] Rate limiting implemented
- [ ] Request throttling configured
- [ ] Queue system for heavy operations
- [ ] API protected from abuse

---

### Day 615: API Performance Validation
**Assigned to:** All Developers

#### Tasks
**Comprehensive API performance testing**

```bash
# scripts/api-performance-test.sh
#!/bin/bash

echo "Starting API performance validation..."

# Test response compression
echo "\n=== Testing Response Compression ==="
curl -H "Accept-Encoding: gzip" -I http://localhost:3000/api/products | grep -i content-encoding

# Test caching headers
echo "\n=== Testing Cache Headers ==="
curl -I http://localhost:3000/api/products | grep -i cache-control

# Test rate limiting
echo "\n=== Testing Rate Limiting ==="
for i in {1..10}; do
  curl -s -o /dev/null -w "%{http_code}\n" http://localhost:3000/api/auth/login
  sleep 0.1
done

# Run load test
echo "\n=== Running Load Test ==="
k6 run tests/load/api-performance.js

echo "\nAPI performance validation complete!"
```

#### Deliverables
- [ ] API performance validated
- [ ] All optimization targets met
- [ ] Performance report generated
- [ ] API response time reduced by 50%+

---

## Success Metrics - Milestone 13.1-13.3

### Performance Improvements
- [ ] API response time (P95) < 200ms ✓
- [ ] Database query time (P95) < 50ms ✓
- [ ] Page load time < 2s ✓
- [ ] Error rate < 0.1% ✓

### Technical Deliverables
- [ ] APM tools configured
- [ ] Database optimized (indexes, partitioning)
- [ ] API caching implemented
- [ ] Rate limiting active
- [ ] Performance baseline documented
- [ ] Optimization report complete

[Document continues with Milestones 13.4-13.10 covering Frontend Optimization, Caching Strategy, ML Models, AI Features, Computer Vision, Performance Testing, and Final Validation...]

---

**Note:** This is Part 1 of Phase 13. The complete document continues with the remaining milestones (13.4-13.10) covering frontend optimization, caching strategies, ML model development, AI-powered features, computer vision implementation, comprehensive performance testing, and final validation. Would you like me to continue with the remaining milestones?
