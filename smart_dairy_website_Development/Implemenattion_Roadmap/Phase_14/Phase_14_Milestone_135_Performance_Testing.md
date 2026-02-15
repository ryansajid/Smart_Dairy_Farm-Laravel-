# Milestone 135: Performance Testing

## Smart Dairy Digital Smart Portal + ERP - Phase 14: Testing & Documentation

| Field | Detail |
|-------|--------|
| Milestone | 135 of 150 (5 of 10 in Phase 14) |
| Title | Performance Testing |
| Phase | Phase 14 - Testing & Documentation |
| Days | Days 671-675 (of 750 total) |
| Duration | 5 working days |
| Predecessor | Milestone 134 - Security Testing |
| Successor | Milestone 136 - Accessibility Testing |
| Version | 1.0 |
| Status | Draft |
| Last Updated | 2026-02-05 |
| Authors | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead) |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)
8. [Appendices](#8-appendices)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Execute comprehensive performance testing across all system layers to validate that the Smart Dairy Digital Portal meets specified performance requirements including API response times under 200ms, support for 1000+ concurrent users, and optimal database query performance.

### 1.2 Objectives

| # | Objective | Priority | Success Indicator |
|---|-----------|----------|-------------------|
| 1 | Configure and deploy k6 load testing framework | Critical | k6 scripts cover 100% critical APIs |
| 2 | Implement Apache JMeter test plans | High | JMeter plans for all user workflows |
| 3 | Execute API endpoint load testing | Critical | All APIs respond < 200ms at P95 |
| 4 | Perform concurrent user simulation | Critical | System stable at 1000+ users |
| 5 | Conduct database performance analysis | High | Query optimization achieved |
| 6 | Execute stress testing scenarios | High | System graceful degradation verified |
| 7 | Implement endurance testing | Medium | 72-hour stability confirmed |
| 8 | Configure Lighthouse CI for frontend | High | All pages score > 90 |
| 9 | Perform mobile app performance testing | High | Flutter app meets benchmarks |
| 10 | Generate comprehensive performance report | Critical | Actionable recommendations documented |

### 1.3 Key Deliverables

| # | Deliverable | Format | Owner | Day |
|---|-------------|--------|-------|-----|
| 1 | k6 Load Test Scripts | JavaScript | Dev 1 | 671 |
| 2 | JMeter Test Plans | JMX | Dev 2 | 671 |
| 3 | Lighthouse CI Configuration | YAML | Dev 3 | 671 |
| 4 | API Load Test Results | HTML/JSON | Dev 1 | 672 |
| 5 | Database Performance Report | MD | Dev 2 | 672 |
| 6 | Frontend Performance Metrics | HTML | Dev 3 | 672 |
| 7 | Concurrent User Test Results | JSON | Dev 1 | 673 |
| 8 | Connection Pool Analysis | MD | Dev 2 | 673 |
| 9 | Bundle Analysis Report | HTML | Dev 3 | 673 |
| 10 | Stress Test Report | PDF | Dev 1 | 674 |
| 11 | Memory Profiling Results | JSON | Dev 2 | 674 |
| 12 | Mobile Performance Report | MD | Dev 3 | 674 |
| 13 | Final Performance Report | PDF | Dev 1 | 675 |
| 14 | SLA Validation Document | MD | Dev 2 | 675 |
| 15 | Core Web Vitals Report | HTML | Dev 3 | 675 |

### 1.4 Prerequisites

| # | Prerequisite | Source | Validation |
|---|--------------|--------|------------|
| 1 | Security testing completed | Milestone 134 | Sign-off document |
| 2 | Staging environment stable | DevOps | Health check passing |
| 3 | Production-like data loaded | UAT Prep | Data volume verified |
| 4 | Load testing tools installed | Dev Environment | Version check |
| 5 | Monitoring infrastructure ready | DevOps | Grafana dashboards active |
| 6 | Database indices optimized | DBA Review | Index analysis complete |

### 1.5 Success Criteria

| # | Criterion | Target | Measurement |
|---|-----------|--------|-------------|
| 1 | API Response Time (P95) | < 200ms | k6 metrics |
| 2 | API Response Time (P99) | < 500ms | k6 metrics |
| 3 | Page Load Time | < 2 seconds | Lighthouse |
| 4 | Time to First Byte | < 400ms | k6/JMeter |
| 5 | Concurrent Users Support | 1000+ | JMeter results |
| 6 | Database Query Time (P95) | < 100ms | PostgreSQL logs |
| 7 | Memory Usage Under Load | < 80% | Grafana metrics |
| 8 | CPU Usage Under Load | < 70% | Grafana metrics |
| 9 | Error Rate Under Load | < 1% | k6 thresholds |
| 10 | Lighthouse Performance Score | > 90 | Lighthouse CI |
| 11 | First Contentful Paint | < 1.8s | Core Web Vitals |
| 12 | Largest Contentful Paint | < 2.5s | Core Web Vitals |
| 13 | Cumulative Layout Shift | < 0.1 | Core Web Vitals |
| 14 | First Input Delay | < 100ms | Core Web Vitals |
| 15 | Mobile App Cold Start | < 3 seconds | Flutter DevTools |

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| RFP-PERF-001 | Support 1000+ concurrent users | JMeter concurrent simulation | 673 | Load test results |
| RFP-PERF-002 | API response < 200ms | k6 API load testing | 672 | P95 metrics |
| RFP-PERF-003 | Page load < 2 seconds | Lighthouse CI testing | 672 | Performance score |
| RFP-PERF-004 | Mobile app responsive | Flutter performance testing | 674 | DevTools metrics |
| RFP-PERF-005 | Real-time data streaming | WebSocket load testing | 673 | Latency metrics |
| RFP-PERF-006 | High availability (99.9%) | Endurance testing | 674 | Uptime metrics |
| RFP-PERF-007 | Database scalability | PostgreSQL load testing | 672 | Query performance |

### 2.2 BRD Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| BRD-PERF-001 | Fast order processing | Order workflow load test | 673 | Transaction/second |
| BRD-PERF-002 | Real-time inventory updates | Inventory API load test | 672 | Update latency |
| BRD-PERF-003 | Quick payment processing | Payment gateway perf test | 673 | Response time |
| BRD-PERF-004 | Responsive dashboards | Dashboard load testing | 672 | Render time |
| BRD-PERF-005 | IoT data ingestion | TimescaleDB write perf | 673 | Insert rate |
| BRD-PERF-006 | Report generation speed | Report API testing | 672 | Generation time |

### 2.3 SRS Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| SRS-PERF-001 | RESTful API < 200ms P95 | k6 scenario testing | 672 | Threshold pass |
| SRS-PERF-002 | GraphQL queries < 300ms | GraphQL performance test | 672 | Query metrics |
| SRS-PERF-003 | Batch operations < 5s | Batch processing test | 673 | Completion time |
| SRS-PERF-004 | File uploads < 10s (50MB) | Upload performance test | 673 | Transfer time |
| SRS-PERF-005 | Search results < 500ms | Elasticsearch load test | 672 | Query latency |
| SRS-PERF-006 | Redis cache hit > 95% | Cache performance test | 673 | Hit ratio |
| SRS-PERF-007 | Connection pool efficiency | Pool monitoring | 673 | Utilization metrics |

### 2.4 Technology Stack Requirements

| Req ID | Requirement Description | Implementation | Day | Verification |
|--------|------------------------|----------------|-----|--------------|
| TECH-PERF-001 | Odoo 19 CE optimization | Odoo-specific load tests | 672 | RPC performance |
| TECH-PERF-002 | PostgreSQL 16 tuning | Database benchmarks | 672 | pgbench results |
| TECH-PERF-003 | Redis 7 performance | Redis benchmarks | 673 | redis-benchmark |
| TECH-PERF-004 | TimescaleDB write speed | Time-series insert tests | 673 | Rows/second |
| TECH-PERF-005 | Python 3.11+ async perf | Async endpoint testing | 672 | Concurrent handling |
| TECH-PERF-006 | OWL Framework rendering | Component render tests | 672 | Frame rate |
| TECH-PERF-007 | Flutter performance | Widget build times | 674 | DevTools profile |

---

## 3. Day-by-Day Breakdown

### Day 671 - Performance Test Framework Setup
**Date Reference:** Day 671 of 750 | Phase 14, Day 21 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Install and configure k6 load testing framework | k6 installation verified |
| 11:00 | 2h | Create k6 scenario scripts for API endpoints | api-load-tests.js |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Configure k6 thresholds and options | k6-config.json |
| 16:00 | 1h | Set up InfluxDB for k6 metrics storage | InfluxDB configured |
| 17:00 | 1h | Document k6 test execution procedures | k6-guide.md |

**Key Deliverables:**
- k6 framework configured and ready
- API load test scripts for 50+ endpoints
- Threshold configuration for SLA validation

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Install and configure Apache JMeter | JMeter 5.6+ installed |
| 11:00 | 2h | Create JMeter test plans for user workflows | smart-dairy-load.jmx |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Configure JMeter thread groups and samplers | Workflow test plans |
| 16:00 | 1h | Set up JMeter distributed testing | Remote engine config |
| 17:00 | 1h | Create JMeter result dashboards | Dashboard templates |

**Key Deliverables:**
- JMeter test plans for 10 critical workflows
- Distributed testing infrastructure
- Custom dashboard configurations

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Configure Lighthouse CI in GitHub Actions | lighthouserc.js |
| 11:00 | 2h | Set up Web Vitals monitoring | web-vitals-config.js |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Configure webpack-bundle-analyzer | Bundle analysis setup |
| 16:00 | 1h | Set up Flutter performance profiling | DevTools configuration |
| 17:00 | 1h | Create performance testing checklist | Frontend perf guide |

**Key Deliverables:**
- Lighthouse CI pipeline configured
- Bundle analysis tooling ready
- Mobile performance profiling setup

---

### Day 672 - API and Database Performance Testing
**Date Reference:** Day 672 of 750 | Phase 14, Day 22 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Execute k6 load tests on authentication APIs | auth-results.json |
| 11:00 | 2h | Test animal management API endpoints | animals-results.json |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Test milk collection API performance | collection-results.json |
| 16:00 | 1h | Test inventory management APIs | inventory-results.json |
| 17:00 | 1h | Compile API load test summary | api-summary.md |

**Key Deliverables:**
- Load test results for 50+ API endpoints
- Performance bottleneck identification
- Initial optimization recommendations

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Run pgbench on PostgreSQL 16 | pgbench-results.txt |
| 11:00 | 2h | Analyze slow query logs | slow-queries.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Test database connection pooling | pool-analysis.md |
| 16:00 | 1h | Benchmark TimescaleDB write performance | timescale-bench.json |
| 17:00 | 1h | Document database optimization needs | db-recommendations.md |

**Key Deliverables:**
- PostgreSQL benchmark results
- Slow query analysis and fixes
- Connection pool configuration recommendations

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Run Lighthouse CI on all major pages | lighthouse-results/ |
| 11:00 | 2h | Test OWL component render performance | owl-perf-report.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Analyze dashboard rendering performance | dashboard-perf.md |
| 16:00 | 1h | Test React component performance | react-profiler-results/ |
| 17:00 | 1h | Document frontend performance findings | frontend-perf.md |

**Key Deliverables:**
- Lighthouse scores for all pages
- Component rendering analysis
- Frontend optimization roadmap

---

### Day 673 - Concurrent User and Stress Testing
**Date Reference:** Day 673 of 750 | Phase 14, Day 23 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Execute 100 concurrent user baseline test | baseline-100.json |
| 11:00 | 2h | Scale to 500 concurrent user test | scale-500.json |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Execute 1000+ concurrent user test | scale-1000.json |
| 16:00 | 1h | Analyze system behavior under load | load-analysis.md |
| 17:00 | 1h | Document scaling observations | scaling-report.md |

**Key Deliverables:**
- Concurrent user test results (100, 500, 1000+)
- System scaling behavior analysis
- Resource utilization metrics

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Test Redis cache performance under load | redis-load-results.json |
| 11:00 | 2h | Benchmark WebSocket connections | websocket-bench.json |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Test batch processing performance | batch-perf-results.json |
| 16:00 | 1h | Analyze connection pool efficiency | pool-efficiency.md |
| 17:00 | 1h | Document infrastructure findings | infra-perf.md |

**Key Deliverables:**
- Redis performance metrics
- WebSocket scalability analysis
- Batch processing benchmarks

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Execute bundle size analysis | bundle-analysis.html |
| 11:00 | 2h | Test lazy loading performance | lazy-load-metrics.json |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Analyze JavaScript execution time | js-profiling.md |
| 16:00 | 1h | Test image optimization effectiveness | image-perf.md |
| 17:00 | 1h | Document frontend optimization needs | frontend-opt.md |

**Key Deliverables:**
- Bundle size optimization recommendations
- Lazy loading effectiveness report
- JavaScript profiling results

---

### Day 674 - Stress Testing and Mobile Performance
**Date Reference:** Day 674 of 750 | Phase 14, Day 24 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Execute system stress test (1.5x capacity) | stress-150.json |
| 11:00 | 2h | Test system breaking point | breaking-point.json |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Analyze graceful degradation behavior | degradation.md |
| 16:00 | 1h | Test auto-scaling triggers | autoscale-test.json |
| 17:00 | 1h | Document stress test findings | stress-report.md |

**Key Deliverables:**
- Stress test results at 150% capacity
- System breaking point analysis
- Auto-scaling validation

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Profile memory usage under load | memory-profile.json |
| 11:00 | 2h | Analyze memory leaks | leak-analysis.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Test garbage collection behavior | gc-metrics.json |
| 16:00 | 1h | Profile CPU usage patterns | cpu-profile.json |
| 17:00 | 1h | Document resource optimization needs | resource-opt.md |

**Key Deliverables:**
- Memory profiling results
- Memory leak identification
- CPU optimization recommendations

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Execute Flutter performance profiling | flutter-profile.json |
| 11:00 | 2h | Test mobile app cold start time | cold-start.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Analyze widget build performance | widget-perf.md |
| 16:00 | 1h | Test offline mode performance | offline-perf.md |
| 17:00 | 1h | Document mobile optimization needs | mobile-opt.md |

**Key Deliverables:**
- Flutter performance profile
- Mobile app cold start optimization
- Widget rendering benchmarks

---

### Day 675 - Performance Report and SLA Validation
**Date Reference:** Day 675 of 750 | Phase 14, Day 25 of 50

#### Dev 1 - Backend Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Compile all performance test results | results-compilation.json |
| 11:00 | 2h | Validate against SLA requirements | sla-validation.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Create comprehensive performance report | performance-report.pdf |
| 16:00 | 1h | Generate executive summary | exec-summary.md |
| 17:00 | 1h | Handoff documentation for Milestone 136 | handoff-notes.md |

**Key Deliverables:**
- Comprehensive performance report (PDF)
- SLA validation documentation
- Executive summary for stakeholders

#### Dev 2 - Full-Stack (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Validate infrastructure SLAs | infra-sla.md |
| 11:00 | 2h | Create performance baseline document | baseline-doc.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Document optimization implementation plan | opt-plan.md |
| 16:00 | 1h | Update Grafana dashboards | Dashboard updates |
| 17:00 | 1h | Prepare Milestone 136 prerequisites | prereq-checklist.md |

**Key Deliverables:**
- Infrastructure SLA validation
- Performance baseline documentation
- Optimization implementation plan

#### Dev 3 - Frontend/Mobile Lead (8 hours)

| Time | Duration | Task | Output |
|------|----------|------|--------|
| 09:00 | 2h | Compile Core Web Vitals report | web-vitals-report.html |
| 11:00 | 2h | Validate frontend performance targets | frontend-sla.md |
| 13:00 | 1h | Lunch Break | - |
| 14:00 | 2h | Create mobile performance summary | mobile-summary.md |
| 16:00 | 1h | Document frontend optimization roadmap | frontend-roadmap.md |
| 17:00 | 1h | Team performance review meeting | Meeting notes |

**Key Deliverables:**
- Core Web Vitals compliance report
- Frontend SLA validation
- Mobile performance documentation

---

## 4. Technical Specifications

### 4.1 k6 Load Testing Configuration

#### k6 Main Configuration File

```javascript
// tests/performance/k6/config.js
// Smart Dairy k6 Load Testing Configuration

export const options = {
  // Test scenarios
  scenarios: {
    // Smoke test - quick validation
    smoke_test: {
      executor: 'constant-vus',
      vus: 5,
      duration: '1m',
      gracefulStop: '30s',
      tags: { test_type: 'smoke' },
      exec: 'smokeTest',
    },

    // Load test - normal conditions
    load_test: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '5m', target: 100 },   // Ramp up to 100 users
        { duration: '10m', target: 100 },  // Stay at 100 users
        { duration: '5m', target: 200 },   // Ramp up to 200 users
        { duration: '10m', target: 200 },  // Stay at 200 users
        { duration: '5m', target: 0 },     // Ramp down
      ],
      gracefulStop: '2m',
      tags: { test_type: 'load' },
      exec: 'loadTest',
    },

    // Stress test - beyond normal capacity
    stress_test: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 100 },
        { duration: '5m', target: 500 },
        { duration: '5m', target: 1000 },
        { duration: '5m', target: 1500 },
        { duration: '5m', target: 1000 },
        { duration: '5m', target: 500 },
        { duration: '2m', target: 0 },
      ],
      gracefulStop: '2m',
      tags: { test_type: 'stress' },
      exec: 'stressTest',
    },

    // Spike test - sudden traffic bursts
    spike_test: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '10s', target: 100 },  // Normal load
        { duration: '1m', target: 100 },
        { duration: '10s', target: 1000 }, // Spike!
        { duration: '3m', target: 1000 },  // Stay at spike
        { duration: '10s', target: 100 },  // Scale down
        { duration: '3m', target: 100 },
        { duration: '10s', target: 0 },
      ],
      gracefulStop: '2m',
      tags: { test_type: 'spike' },
      exec: 'spikeTest',
    },

    // Endurance test - sustained load
    endurance_test: {
      executor: 'constant-vus',
      vus: 200,
      duration: '4h',
      gracefulStop: '5m',
      tags: { test_type: 'endurance' },
      exec: 'enduranceTest',
    },
  },

  // Performance thresholds (SLA requirements)
  thresholds: {
    // HTTP request duration thresholds
    'http_req_duration': [
      'p(50)<100',   // 50% of requests under 100ms
      'p(95)<200',   // 95% of requests under 200ms (SLA)
      'p(99)<500',   // 99% of requests under 500ms
    ],
    'http_req_duration{endpoint:auth}': ['p(95)<150'],
    'http_req_duration{endpoint:api}': ['p(95)<200'],
    'http_req_duration{endpoint:dashboard}': ['p(95)<300'],

    // Error rate thresholds
    'http_req_failed': ['rate<0.01'],  // Less than 1% error rate

    // Request rate thresholds
    'http_reqs': ['rate>100'],  // Minimum 100 req/s

    // Custom metrics
    'transaction_success_rate': ['rate>0.99'],
    'order_processing_time': ['p(95)<1000'],
    'payment_processing_time': ['p(95)<2000'],
  },

  // Output configuration
  summaryTrendStats: ['avg', 'min', 'med', 'max', 'p(90)', 'p(95)', 'p(99)'],

  // System tags
  systemTags: ['status', 'method', 'url', 'name', 'group', 'check', 'error', 'scenario'],
};

// Environment configuration
export const config = {
  baseURL: __ENV.BASE_URL || 'https://staging.smartdairy.com',
  apiVersion: 'v1',

  // Authentication
  auth: {
    adminUser: __ENV.ADMIN_USER || 'admin@smartdairy.com',
    adminPass: __ENV.ADMIN_PASS,
    farmUser: __ENV.FARM_USER || 'farm@smartdairy.com',
    farmPass: __ENV.FARM_PASS,
  },

  // Rate limiting
  rateLimit: {
    requestsPerSecond: 100,
    maxConcurrent: 500,
  },

  // Timeouts
  timeouts: {
    http: '30s',
    tcp: '10s',
  },
};
```

#### k6 API Load Test Suite

```javascript
// tests/performance/k6/api-tests.js
// Smart Dairy API Performance Tests

import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { Counter, Rate, Trend } from 'k6/metrics';
import { options, config } from './config.js';

// Custom metrics
const transactionSuccess = new Rate('transaction_success_rate');
const orderProcessingTime = new Trend('order_processing_time');
const paymentProcessingTime = new Trend('payment_processing_time');
const apiErrors = new Counter('api_errors');

// Auth token cache
let authToken = null;

// Setup function - runs once per VU
export function setup() {
  // Login and get auth token
  const loginRes = http.post(`${config.baseURL}/api/v1/auth/login`, JSON.stringify({
    email: config.auth.adminUser,
    password: config.auth.adminPass,
  }), {
    headers: { 'Content-Type': 'application/json' },
  });

  if (loginRes.status !== 200) {
    throw new Error('Failed to authenticate');
  }

  return {
    token: loginRes.json('access_token'),
    refreshToken: loginRes.json('refresh_token'),
  };
}

// Authentication test group
export function authenticationTests(data) {
  group('Authentication APIs', () => {
    // Login endpoint
    const loginRes = http.post(`${config.baseURL}/api/v1/auth/login`, JSON.stringify({
      email: 'test@smartdairy.com',
      password: 'TestPassword123!',
    }), {
      headers: { 'Content-Type': 'application/json' },
      tags: { endpoint: 'auth', operation: 'login' },
    });

    check(loginRes, {
      'login status is 200': (r) => r.status === 200,
      'login response has token': (r) => r.json('access_token') !== undefined,
      'login time < 150ms': (r) => r.timings.duration < 150,
    });

    // Token refresh endpoint
    const refreshRes = http.post(`${config.baseURL}/api/v1/auth/refresh`, JSON.stringify({
      refresh_token: data.refreshToken,
    }), {
      headers: { 'Content-Type': 'application/json' },
      tags: { endpoint: 'auth', operation: 'refresh' },
    });

    check(refreshRes, {
      'refresh status is 200': (r) => r.status === 200,
      'refresh time < 100ms': (r) => r.timings.duration < 100,
    });

    sleep(1);
  });
}

// Animal Management API tests
export function animalManagementTests(data) {
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${data.token}`,
  };

  group('Animal Management APIs', () => {
    // List animals with pagination
    const listRes = http.get(`${config.baseURL}/api/v1/animals?page=1&limit=50`, {
      headers,
      tags: { endpoint: 'api', operation: 'list_animals' },
    });

    check(listRes, {
      'list animals status is 200': (r) => r.status === 200,
      'list animals has data': (r) => r.json('data') !== undefined,
      'list animals time < 200ms': (r) => r.timings.duration < 200,
    });
    transactionSuccess.add(listRes.status === 200);

    // Get single animal
    const animalId = listRes.json('data.0.id') || 1;
    const getRes = http.get(`${config.baseURL}/api/v1/animals/${animalId}`, {
      headers,
      tags: { endpoint: 'api', operation: 'get_animal' },
    });

    check(getRes, {
      'get animal status is 200': (r) => r.status === 200,
      'get animal time < 100ms': (r) => r.timings.duration < 100,
    });

    // Create animal (POST)
    const createRes = http.post(`${config.baseURL}/api/v1/animals`, JSON.stringify({
      tag_number: `TEST-${Date.now()}`,
      name: 'Performance Test Animal',
      breed: 'Holstein',
      date_of_birth: '2022-01-15',
      gender: 'female',
      status: 'active',
    }), {
      headers,
      tags: { endpoint: 'api', operation: 'create_animal' },
    });

    check(createRes, {
      'create animal status is 201': (r) => r.status === 201,
      'create animal time < 300ms': (r) => r.timings.duration < 300,
    });

    sleep(0.5);
  });
}

// Milk Collection API tests
export function milkCollectionTests(data) {
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${data.token}`,
  };

  group('Milk Collection APIs', () => {
    // Record milk collection
    const recordRes = http.post(`${config.baseURL}/api/v1/collections`, JSON.stringify({
      animal_id: 1,
      farm_id: 1,
      collection_date: new Date().toISOString().split('T')[0],
      shift: 'morning',
      quantity_liters: 15.5,
      fat_percentage: 3.8,
      snf_percentage: 8.5,
      temperature: 37.2,
    }), {
      headers,
      tags: { endpoint: 'api', operation: 'record_collection' },
    });

    check(recordRes, {
      'record collection status is 201': (r) => r.status === 201,
      'record collection time < 200ms': (r) => r.timings.duration < 200,
    });

    // Get collection summary
    const summaryRes = http.get(`${config.baseURL}/api/v1/collections/summary?date=${new Date().toISOString().split('T')[0]}`, {
      headers,
      tags: { endpoint: 'api', operation: 'collection_summary' },
    });

    check(summaryRes, {
      'summary status is 200': (r) => r.status === 200,
      'summary time < 300ms': (r) => r.timings.duration < 300,
    });

    sleep(0.5);
  });
}

// Order Processing tests
export function orderProcessingTests(data) {
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${data.token}`,
  };

  group('Order Processing APIs', () => {
    const startTime = Date.now();

    // Create order
    const orderRes = http.post(`${config.baseURL}/api/v1/orders`, JSON.stringify({
      customer_id: 1,
      items: [
        { product_id: 1, quantity: 10 },
        { product_id: 2, quantity: 5 },
      ],
      delivery_address_id: 1,
      payment_method: 'cash_on_delivery',
    }), {
      headers,
      tags: { endpoint: 'api', operation: 'create_order' },
    });

    const processingTime = Date.now() - startTime;
    orderProcessingTime.add(processingTime);

    check(orderRes, {
      'create order status is 201': (r) => r.status === 201,
      'create order time < 500ms': (r) => r.timings.duration < 500,
      'order has id': (r) => r.json('id') !== undefined,
    });

    if (orderRes.status !== 201) {
      apiErrors.add(1);
    }
    transactionSuccess.add(orderRes.status === 201);

    // Get order status
    const orderId = orderRes.json('id') || 1;
    const statusRes = http.get(`${config.baseURL}/api/v1/orders/${orderId}`, {
      headers,
      tags: { endpoint: 'api', operation: 'order_status' },
    });

    check(statusRes, {
      'order status is 200': (r) => r.status === 200,
      'order status time < 100ms': (r) => r.timings.duration < 100,
    });

    sleep(1);
  });
}

// Payment Gateway tests
export function paymentTests(data) {
  const headers = {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${data.token}`,
  };

  group('Payment Gateway APIs', () => {
    const startTime = Date.now();

    // Initialize payment (bKash)
    const initRes = http.post(`${config.baseURL}/api/v1/payments/initialize`, JSON.stringify({
      order_id: 1,
      amount: 1500.00,
      currency: 'BDT',
      gateway: 'bkash',
      callback_url: 'https://smartdairy.com/payment/callback',
    }), {
      headers,
      tags: { endpoint: 'api', operation: 'payment_init' },
    });

    const paymentTime = Date.now() - startTime;
    paymentProcessingTime.add(paymentTime);

    check(initRes, {
      'payment init status is 200': (r) => r.status === 200,
      'payment init time < 2000ms': (r) => r.timings.duration < 2000,
      'payment has reference': (r) => r.json('payment_reference') !== undefined,
    });

    // Verify payment status
    const verifyRes = http.get(`${config.baseURL}/api/v1/payments/verify?reference=${initRes.json('payment_reference') || 'test'}`, {
      headers,
      tags: { endpoint: 'api', operation: 'payment_verify' },
    });

    check(verifyRes, {
      'payment verify status is 200': (r) => r.status === 200,
      'payment verify time < 1000ms': (r) => r.timings.duration < 1000,
    });

    sleep(1);
  });
}

// Dashboard API tests
export function dashboardTests(data) {
  const headers = {
    'Authorization': `Bearer ${data.token}`,
  };

  group('Dashboard APIs', () => {
    // Main dashboard data
    const dashRes = http.get(`${config.baseURL}/api/v1/dashboard/overview`, {
      headers,
      tags: { endpoint: 'dashboard', operation: 'overview' },
    });

    check(dashRes, {
      'dashboard status is 200': (r) => r.status === 200,
      'dashboard time < 300ms': (r) => r.timings.duration < 300,
    });

    // Analytics data
    const analyticsRes = http.get(`${config.baseURL}/api/v1/dashboard/analytics?period=30d`, {
      headers,
      tags: { endpoint: 'dashboard', operation: 'analytics' },
    });

    check(analyticsRes, {
      'analytics status is 200': (r) => r.status === 200,
      'analytics time < 500ms': (r) => r.timings.duration < 500,
    });

    // Real-time metrics
    const metricsRes = http.get(`${config.baseURL}/api/v1/dashboard/realtime`, {
      headers,
      tags: { endpoint: 'dashboard', operation: 'realtime' },
    });

    check(metricsRes, {
      'realtime metrics status is 200': (r) => r.status === 200,
      'realtime metrics time < 200ms': (r) => r.timings.duration < 200,
    });

    sleep(1);
  });
}

// Main test scenarios
export function smokeTest(data) {
  authenticationTests(data);
  animalManagementTests(data);
}

export function loadTest(data) {
  authenticationTests(data);
  animalManagementTests(data);
  milkCollectionTests(data);
  orderProcessingTests(data);
  dashboardTests(data);
}

export function stressTest(data) {
  authenticationTests(data);
  animalManagementTests(data);
  milkCollectionTests(data);
  orderProcessingTests(data);
  paymentTests(data);
  dashboardTests(data);
}

export function spikeTest(data) {
  orderProcessingTests(data);
  paymentTests(data);
}

export function enduranceTest(data) {
  loadTest(data);
}

// Default function
export default function(data) {
  loadTest(data);
}

// Teardown
export function teardown(data) {
  // Cleanup test data if needed
  console.log('Performance tests completed');
}
```

### 4.2 JMeter Test Plan Configuration

#### JMeter Test Plan XML

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- tests/performance/jmeter/smart-dairy-load.jmx -->
<jmeterTestPlan version="1.2" properties="5.0" jmeter="5.6">
  <hashTree>
    <TestPlan guiclass="TestPlanGui" testclass="TestPlan" testname="Smart Dairy Load Test Plan">
      <elementProp name="TestPlan.user_defined_variables" elementType="Arguments">
        <collectionProp name="Arguments.arguments">
          <elementProp name="BASE_URL" elementType="Argument">
            <stringProp name="Argument.name">BASE_URL</stringProp>
            <stringProp name="Argument.value">staging.smartdairy.com</stringProp>
          </elementProp>
          <elementProp name="PROTOCOL" elementType="Argument">
            <stringProp name="Argument.name">PROTOCOL</stringProp>
            <stringProp name="Argument.value">https</stringProp>
          </elementProp>
          <elementProp name="THINK_TIME" elementType="Argument">
            <stringProp name="Argument.name">THINK_TIME</stringProp>
            <stringProp name="Argument.value">1000</stringProp>
          </elementProp>
        </collectionProp>
      </elementProp>
      <boolProp name="TestPlan.functional_mode">false</boolProp>
      <stringProp name="TestPlan.comments">Smart Dairy Digital Portal Performance Test Suite</stringProp>
    </TestPlan>
    <hashTree>
      <!-- HTTP Request Defaults -->
      <ConfigTestElement guiclass="HttpDefaultsGui" testclass="ConfigTestElement" testname="HTTP Request Defaults">
        <stringProp name="HTTPSampler.domain">${BASE_URL}</stringProp>
        <stringProp name="HTTPSampler.port">443</stringProp>
        <stringProp name="HTTPSampler.protocol">${PROTOCOL}</stringProp>
        <stringProp name="HTTPSampler.contentEncoding">UTF-8</stringProp>
        <boolProp name="HTTPSampler.follow_redirects">true</boolProp>
        <boolProp name="HTTPSampler.use_keepalive">true</boolProp>
      </ConfigTestElement>
      <hashTree/>

      <!-- HTTP Header Manager -->
      <HeaderManager guiclass="HeaderPanel" testclass="HeaderManager" testname="HTTP Header Manager">
        <collectionProp name="HeaderManager.headers">
          <elementProp name="" elementType="Header">
            <stringProp name="Header.name">Content-Type</stringProp>
            <stringProp name="Header.value">application/json</stringProp>
          </elementProp>
          <elementProp name="" elementType="Header">
            <stringProp name="Header.name">Accept</stringProp>
            <stringProp name="Header.value">application/json</stringProp>
          </elementProp>
        </collectionProp>
      </HeaderManager>
      <hashTree/>

      <!-- User Data CSV -->
      <CSVDataSet guiclass="TestBeanGUI" testclass="CSVDataSet" testname="User Test Data">
        <stringProp name="filename">test-users.csv</stringProp>
        <stringProp name="fileEncoding">UTF-8</stringProp>
        <stringProp name="variableNames">username,password,role</stringProp>
        <stringProp name="delimiter">,</stringProp>
        <boolProp name="recycle">true</boolProp>
        <boolProp name="stopThread">false</boolProp>
        <stringProp name="shareMode">shareMode.all</stringProp>
      </CSVDataSet>
      <hashTree/>

      <!-- Load Test Thread Group -->
      <ThreadGroup guiclass="ThreadGroupGui" testclass="ThreadGroup" testname="Load Test - 1000 Users">
        <intProp name="ThreadGroup.num_threads">1000</intProp>
        <intProp name="ThreadGroup.ramp_time">300</intProp>
        <longProp name="ThreadGroup.duration">1800</longProp>
        <boolProp name="ThreadGroup.scheduler">true</boolProp>
        <stringProp name="ThreadGroup.on_sample_error">continue</stringProp>
        <elementProp name="ThreadGroup.main_controller" elementType="LoopController">
          <boolProp name="LoopController.continue_forever">true</boolProp>
          <intProp name="LoopController.loops">-1</intProp>
        </elementProp>
      </ThreadGroup>
      <hashTree>
        <!-- Authentication Transaction -->
        <TransactionController guiclass="TransactionControllerGui" testclass="TransactionController" testname="TC01_Authentication">
          <boolProp name="TransactionController.parent">true</boolProp>
          <boolProp name="TransactionController.includeTimers">false</boolProp>
        </TransactionController>
        <hashTree>
          <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="Login API">
            <stringProp name="HTTPSampler.path">/api/v1/auth/login</stringProp>
            <stringProp name="HTTPSampler.method">POST</stringProp>
            <boolProp name="HTTPSampler.postBodyRaw">true</boolProp>
            <elementProp name="HTTPsampler.Arguments" elementType="Arguments">
              <collectionProp name="Arguments.arguments">
                <elementProp name="" elementType="HTTPArgument">
                  <stringProp name="Argument.value">{"email":"${username}","password":"${password}"}</stringProp>
                </elementProp>
              </collectionProp>
            </elementProp>
          </HTTPSamplerProxy>
          <hashTree>
            <JSONPostProcessor guiclass="JSONPostProcessorGui" testclass="JSONPostProcessor" testname="Extract Token">
              <stringProp name="JSONPostProcessor.referenceNames">access_token</stringProp>
              <stringProp name="JSONPostProcessor.jsonPathExprs">$.access_token</stringProp>
            </JSONPostProcessor>
            <hashTree/>
            <ResponseAssertion guiclass="AssertionGui" testclass="ResponseAssertion" testname="Assert Login Success">
              <collectionProp name="Asserion.test_strings">
                <stringProp name="49586">200</stringProp>
              </collectionProp>
              <stringProp name="Assertion.test_field">Assertion.response_code</stringProp>
            </ResponseAssertion>
            <hashTree/>
          </hashTree>
        </hashTree>

        <!-- Dashboard Transaction -->
        <TransactionController guiclass="TransactionControllerGui" testclass="TransactionController" testname="TC02_Dashboard">
          <boolProp name="TransactionController.parent">true</boolProp>
        </TransactionController>
        <hashTree>
          <HeaderManager guiclass="HeaderPanel" testclass="HeaderManager" testname="Auth Header">
            <collectionProp name="HeaderManager.headers">
              <elementProp name="" elementType="Header">
                <stringProp name="Header.name">Authorization</stringProp>
                <stringProp name="Header.value">Bearer ${access_token}</stringProp>
              </elementProp>
            </collectionProp>
          </HeaderManager>
          <hashTree/>
          <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="Dashboard Overview">
            <stringProp name="HTTPSampler.path">/api/v1/dashboard/overview</stringProp>
            <stringProp name="HTTPSampler.method">GET</stringProp>
          </HTTPSamplerProxy>
          <hashTree>
            <DurationAssertion guiclass="DurationAssertionGui" testclass="DurationAssertion" testname="Duration Assertion">
              <stringProp name="DurationAssertion.duration">300</stringProp>
            </DurationAssertion>
            <hashTree/>
          </hashTree>
        </hashTree>

        <!-- Think Time -->
        <ConstantTimer guiclass="ConstantTimerGui" testclass="ConstantTimer" testname="Think Time">
          <stringProp name="ConstantTimer.delay">${THINK_TIME}</stringProp>
        </ConstantTimer>
        <hashTree/>
      </hashTree>

      <!-- View Results Tree (Debug) -->
      <ResultCollector guiclass="ViewResultsFullVisualizer" testclass="ResultCollector" testname="View Results Tree" enabled="false">
        <boolProp name="ResultCollector.error_logging">true</boolProp>
        <objProp>
          <name>saveConfig</name>
          <value class="SampleSaveConfiguration">
            <time>true</time>
            <latency>true</latency>
            <timestamp>true</timestamp>
            <success>true</success>
            <label>true</label>
            <code>true</code>
            <message>true</message>
            <threadName>true</threadName>
          </value>
        </objProp>
      </ResultCollector>
      <hashTree/>

      <!-- Summary Report -->
      <ResultCollector guiclass="SummaryReport" testclass="ResultCollector" testname="Summary Report">
        <stringProp name="filename">results/summary-report.csv</stringProp>
      </ResultCollector>
      <hashTree/>

      <!-- Aggregate Report -->
      <ResultCollector guiclass="StatVisualizer" testclass="ResultCollector" testname="Aggregate Report">
        <stringProp name="filename">results/aggregate-report.csv</stringProp>
      </ResultCollector>
      <hashTree/>
    </hashTree>
  </hashTree>
</jmeterTestPlan>
```

### 4.3 Lighthouse CI Configuration

```javascript
// lighthouserc.js
// Smart Dairy Lighthouse CI Configuration

module.exports = {
  ci: {
    collect: {
      // URLs to test
      url: [
        'https://staging.smartdairy.com/',
        'https://staging.smartdairy.com/login',
        'https://staging.smartdairy.com/dashboard',
        'https://staging.smartdairy.com/farm/animals',
        'https://staging.smartdairy.com/collections',
        'https://staging.smartdairy.com/inventory',
        'https://staging.smartdairy.com/orders',
        'https://staging.smartdairy.com/reports',
        'https://staging.smartdairy.com/settings',
      ],

      // Number of runs per URL
      numberOfRuns: 3,

      // Lighthouse settings
      settings: {
        preset: 'desktop',
        chromeFlags: '--no-sandbox --headless --disable-gpu',
        onlyCategories: ['performance', 'accessibility', 'best-practices', 'seo'],
        skipAudits: ['uses-http2'],
      },

      // Puppeteer settings for authenticated pages
      puppeteerScript: './lighthouse-auth.js',
      puppeteerLaunchOptions: {
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
      },
    },

    assert: {
      preset: 'lighthouse:recommended',
      assertions: {
        // Performance assertions
        'categories:performance': ['error', { minScore: 0.9 }],
        'first-contentful-paint': ['error', { maxNumericValue: 1800 }],
        'largest-contentful-paint': ['error', { maxNumericValue: 2500 }],
        'cumulative-layout-shift': ['error', { maxNumericValue: 0.1 }],
        'total-blocking-time': ['error', { maxNumericValue: 200 }],
        'speed-index': ['error', { maxNumericValue: 3000 }],
        'interactive': ['error', { maxNumericValue: 3500 }],

        // Accessibility assertions
        'categories:accessibility': ['error', { minScore: 0.9 }],

        // Best practices
        'categories:best-practices': ['error', { minScore: 0.9 }],

        // SEO
        'categories:seo': ['warn', { minScore: 0.85 }],

        // Resource optimization
        'unused-javascript': ['warn', { maxNumericValue: 100000 }],
        'unused-css-rules': ['warn', { maxNumericValue: 50000 }],
        'render-blocking-resources': ['warn', { maxLength: 3 }],
        'efficient-animated-content': 'off',

        // Image optimization
        'uses-webp-images': 'warn',
        'uses-optimized-images': 'warn',
        'uses-responsive-images': 'warn',
        'offscreen-images': 'warn',
      },
    },

    upload: {
      target: 'lhci',
      serverBaseUrl: 'https://lhci.smartdairy.com',
      token: process.env.LHCI_TOKEN,
      basicAuth: {
        username: process.env.LHCI_USERNAME,
        password: process.env.LHCI_PASSWORD,
      },
    },
  },
};

// lighthouse-auth.js - Authentication script for Lighthouse
module.exports = async (browser) => {
  const page = await browser.newPage();

  // Navigate to login page
  await page.goto('https://staging.smartdairy.com/login');

  // Fill login form
  await page.type('#email', process.env.TEST_USER_EMAIL);
  await page.type('#password', process.env.TEST_USER_PASSWORD);

  // Submit form
  await page.click('button[type="submit"]');

  // Wait for navigation to dashboard
  await page.waitForNavigation({ waitUntil: 'networkidle0' });

  // Close page (browser will be reused with cookies)
  await page.close();
};
```

### 4.4 GitHub Actions Performance Testing Workflow

```yaml
# .github/workflows/performance-tests.yml
# Smart Dairy Performance Testing Pipeline

name: Performance Tests

on:
  schedule:
    - cron: '0 2 * * *'  # Run daily at 2 AM
  workflow_dispatch:
    inputs:
      test_type:
        description: 'Test type to run'
        required: true
        default: 'load'
        type: choice
        options:
          - smoke
          - load
          - stress
          - spike
          - endurance
      environment:
        description: 'Target environment'
        required: true
        default: 'staging'
        type: choice
        options:
          - staging
          - production
  pull_request:
    branches: [main, develop]
    paths:
      - 'src/**'
      - 'tests/performance/**'

env:
  K6_VERSION: '0.47.0'
  JMETER_VERSION: '5.6.3'

jobs:
  # Smoke tests for PRs
  smoke-test:
    if: github.event_name == 'pull_request'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup k6
        uses: grafana/setup-k6-action@v1
        with:
          k6-version: ${{ env.K6_VERSION }}

      - name: Run smoke tests
        run: |
          k6 run \
            --env BASE_URL=${{ secrets.STAGING_URL }} \
            --env ADMIN_USER=${{ secrets.TEST_ADMIN_USER }} \
            --env ADMIN_PASS=${{ secrets.TEST_ADMIN_PASS }} \
            --out json=results/smoke-results.json \
            tests/performance/k6/api-tests.js \
            --scenario smoke_test

      - name: Upload smoke test results
        uses: actions/upload-artifact@v4
        with:
          name: smoke-test-results
          path: results/

      - name: Check thresholds
        run: |
          if grep -q '"status":"failed"' results/smoke-results.json; then
            echo "Smoke test failed! Performance thresholds not met."
            exit 1
          fi

  # Full k6 load tests
  k6-load-test:
    if: github.event_name == 'schedule' || github.event_name == 'workflow_dispatch'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup k6
        uses: grafana/setup-k6-action@v1
        with:
          k6-version: ${{ env.K6_VERSION }}

      - name: Set test type
        id: test-type
        run: |
          if [ "${{ github.event_name }}" == "workflow_dispatch" ]; then
            echo "type=${{ github.event.inputs.test_type }}" >> $GITHUB_OUTPUT
          else
            echo "type=load" >> $GITHUB_OUTPUT
          fi

      - name: Run k6 tests
        run: |
          k6 run \
            --env BASE_URL=${{ secrets.STAGING_URL }} \
            --env ADMIN_USER=${{ secrets.TEST_ADMIN_USER }} \
            --env ADMIN_PASS=${{ secrets.TEST_ADMIN_PASS }} \
            --out influxdb=${{ secrets.INFLUXDB_URL }} \
            --out json=results/k6-results.json \
            tests/performance/k6/api-tests.js \
            --scenario ${{ steps.test-type.outputs.type }}_test
        continue-on-error: true

      - name: Generate HTML report
        run: |
          npm install -g k6-html-reporter
          k6-html-reporter -i results/k6-results.json -o results/k6-report.html

      - name: Upload k6 results
        uses: actions/upload-artifact@v4
        with:
          name: k6-load-test-results
          path: results/

  # JMeter load tests
  jmeter-load-test:
    if: github.event_name == 'schedule' || github.event_name == 'workflow_dispatch'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup JMeter
        run: |
          wget https://archive.apache.org/dist/jmeter/binaries/apache-jmeter-${{ env.JMETER_VERSION }}.tgz
          tar -xzf apache-jmeter-${{ env.JMETER_VERSION }}.tgz
          export PATH=$PATH:$(pwd)/apache-jmeter-${{ env.JMETER_VERSION }}/bin

      - name: Run JMeter tests
        run: |
          jmeter -n -t tests/performance/jmeter/smart-dairy-load.jmx \
            -JBASE_URL=${{ secrets.STAGING_URL }} \
            -Jresults_dir=results \
            -l results/jmeter-results.jtl \
            -e -o results/jmeter-report

      - name: Upload JMeter results
        uses: actions/upload-artifact@v4
        with:
          name: jmeter-load-test-results
          path: results/

  # Lighthouse CI tests
  lighthouse-ci:
    if: github.event_name == 'pull_request' || github.event_name == 'schedule'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'

      - name: Install Lighthouse CI
        run: npm install -g @lhci/cli@0.13.x

      - name: Run Lighthouse CI
        run: |
          lhci autorun \
            --config=lighthouserc.js \
            --upload.token=${{ secrets.LHCI_TOKEN }}
        env:
          TEST_USER_EMAIL: ${{ secrets.TEST_USER_EMAIL }}
          TEST_USER_PASSWORD: ${{ secrets.TEST_USER_PASSWORD }}
          LHCI_TOKEN: ${{ secrets.LHCI_TOKEN }}

      - name: Upload Lighthouse results
        uses: actions/upload-artifact@v4
        with:
          name: lighthouse-results
          path: .lighthouseci/

  # Database performance tests
  database-perf-test:
    if: github.event_name == 'schedule'
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_PASSWORD: postgres
          POSTGRES_DB: smartdairy_test
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    steps:
      - uses: actions/checkout@v4

      - name: Run pgbench
        run: |
          PGPASSWORD=postgres pgbench -i -s 100 -h localhost -U postgres smartdairy_test
          PGPASSWORD=postgres pgbench -c 50 -j 4 -T 300 \
            -h localhost -U postgres smartdairy_test \
            > results/pgbench-results.txt 2>&1

      - name: Analyze slow queries
        run: |
          PGPASSWORD=postgres psql -h localhost -U postgres smartdairy_test \
            -c "SELECT * FROM pg_stat_statements ORDER BY total_time DESC LIMIT 20;" \
            > results/slow-queries.txt 2>&1 || true

      - name: Upload database results
        uses: actions/upload-artifact@v4
        with:
          name: database-perf-results
          path: results/

  # Generate combined report
  performance-report:
    needs: [k6-load-test, jmeter-load-test, lighthouse-ci]
    if: always()
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Download all artifacts
        uses: actions/download-artifact@v4
        with:
          path: all-results

      - name: Generate combined report
        run: |
          python scripts/generate_perf_report.py \
            --k6 all-results/k6-load-test-results \
            --jmeter all-results/jmeter-load-test-results \
            --lighthouse all-results/lighthouse-results \
            --output performance-report.pdf

      - name: Upload combined report
        uses: actions/upload-artifact@v4
        with:
          name: performance-report
          path: performance-report.pdf

      - name: Post to Slack
        if: github.event_name == 'schedule'
        uses: slackapi/slack-github-action@v1.24.0
        with:
          channel-id: 'C123456789'
          payload: |
            {
              "text": "Daily Performance Test Results",
              "attachments": [
                {
                  "color": "${{ needs.k6-load-test.result == 'success' && '#36a64f' || '#ff0000' }}",
                  "title": "Performance Test Report",
                  "fields": [
                    {"title": "k6 Tests", "value": "${{ needs.k6-load-test.result }}", "short": true},
                    {"title": "JMeter Tests", "value": "${{ needs.jmeter-load-test.result }}", "short": true},
                    {"title": "Lighthouse", "value": "${{ needs.lighthouse-ci.result }}", "short": true}
                  ]
                }
              ]
            }
        env:
          SLACK_BOT_TOKEN: ${{ secrets.SLACK_BOT_TOKEN }}
```

### 4.5 Database Performance Testing Scripts

```python
# tests/performance/database/db_perf_tests.py
"""
Smart Dairy Database Performance Testing Suite
PostgreSQL 16 + TimescaleDB Performance Benchmarks
"""

import asyncio
import time
import statistics
from datetime import datetime, timedelta
from typing import List, Dict, Any
import asyncpg
import psycopg2
from psycopg2 import pool
from dataclasses import dataclass
import json


@dataclass
class QueryResult:
    """Query execution result."""
    query_name: str
    execution_time_ms: float
    rows_affected: int
    success: bool
    error: str = None


class DatabasePerformanceTester:
    """Database performance testing suite."""

    def __init__(self, config: Dict[str, Any]):
        self.config = config
        self.connection_pool = None
        self.results: List[QueryResult] = []

    async def setup(self):
        """Initialize connection pool."""
        self.connection_pool = await asyncpg.create_pool(
            host=self.config['host'],
            port=self.config['port'],
            user=self.config['user'],
            password=self.config['password'],
            database=self.config['database'],
            min_size=10,
            max_size=100,
            command_timeout=30,
        )

    async def cleanup(self):
        """Close connection pool."""
        if self.connection_pool:
            await self.connection_pool.close()

    async def execute_query(
        self,
        query: str,
        query_name: str,
        params: tuple = None,
    ) -> QueryResult:
        """Execute a query and measure performance."""
        start_time = time.perf_counter()

        try:
            async with self.connection_pool.acquire() as conn:
                if params:
                    result = await conn.fetch(query, *params)
                else:
                    result = await conn.fetch(query)

                execution_time = (time.perf_counter() - start_time) * 1000

                return QueryResult(
                    query_name=query_name,
                    execution_time_ms=execution_time,
                    rows_affected=len(result),
                    success=True,
                )
        except Exception as e:
            execution_time = (time.perf_counter() - start_time) * 1000
            return QueryResult(
                query_name=query_name,
                execution_time_ms=execution_time,
                rows_affected=0,
                success=False,
                error=str(e),
            )

    async def benchmark_animal_queries(self) -> List[QueryResult]:
        """Benchmark animal management queries."""
        queries = [
            (
                "list_animals_paginated",
                """
                SELECT a.*, f.name as farm_name, b.name as breed_name
                FROM animals a
                LEFT JOIN farms f ON a.farm_id = f.id
                LEFT JOIN breeds b ON a.breed_id = b.id
                WHERE a.status = 'active'
                ORDER BY a.created_at DESC
                LIMIT 50 OFFSET 0
                """,
            ),
            (
                "animal_with_health_records",
                """
                SELECT a.*,
                       json_agg(hr.*) as health_records
                FROM animals a
                LEFT JOIN health_records hr ON a.id = hr.animal_id
                WHERE a.id = $1
                GROUP BY a.id
                """,
            ),
            (
                "animals_by_farm_aggregated",
                """
                SELECT f.id, f.name,
                       COUNT(a.id) as total_animals,
                       COUNT(CASE WHEN a.status = 'active' THEN 1 END) as active_animals,
                       AVG(EXTRACT(YEAR FROM age(a.date_of_birth))) as avg_age
                FROM farms f
                LEFT JOIN animals a ON f.id = a.farm_id
                GROUP BY f.id, f.name
                ORDER BY total_animals DESC
                """,
            ),
            (
                "search_animals_fulltext",
                """
                SELECT a.*, ts_rank(search_vector, query) as rank
                FROM animals a,
                     to_tsquery('english', $1) query
                WHERE search_vector @@ query
                ORDER BY rank DESC
                LIMIT 20
                """,
            ),
        ]

        results = []
        for name, query in queries:
            params = (1,) if '$1' in query else None
            if 'to_tsquery' in query:
                params = ('holstein:*',)

            # Run each query 10 times and average
            query_results = []
            for _ in range(10):
                result = await self.execute_query(query, name, params)
                query_results.append(result)

            avg_time = statistics.mean([r.execution_time_ms for r in query_results])
            results.append(QueryResult(
                query_name=name,
                execution_time_ms=avg_time,
                rows_affected=query_results[0].rows_affected,
                success=all(r.success for r in query_results),
            ))

        return results

    async def benchmark_milk_collection_queries(self) -> List[QueryResult]:
        """Benchmark milk collection queries."""
        queries = [
            (
                "daily_collection_summary",
                """
                SELECT
                    DATE(collection_date) as date,
                    COUNT(*) as total_collections,
                    SUM(quantity_liters) as total_liters,
                    AVG(fat_percentage) as avg_fat,
                    AVG(snf_percentage) as avg_snf
                FROM milk_collections
                WHERE collection_date >= CURRENT_DATE - INTERVAL '30 days'
                GROUP BY DATE(collection_date)
                ORDER BY date DESC
                """,
            ),
            (
                "collection_by_animal_last_week",
                """
                SELECT
                    a.tag_number,
                    a.name,
                    COUNT(mc.id) as collection_count,
                    SUM(mc.quantity_liters) as total_liters,
                    AVG(mc.quantity_liters) as avg_per_collection
                FROM animals a
                LEFT JOIN milk_collections mc ON a.id = mc.animal_id
                    AND mc.collection_date >= CURRENT_DATE - INTERVAL '7 days'
                GROUP BY a.id, a.tag_number, a.name
                HAVING COUNT(mc.id) > 0
                ORDER BY total_liters DESC
                LIMIT 100
                """,
            ),
            (
                "quality_metrics_timeseries",
                """
                SELECT
                    time_bucket('1 hour', collection_timestamp) as bucket,
                    AVG(fat_percentage) as avg_fat,
                    AVG(snf_percentage) as avg_snf,
                    AVG(temperature) as avg_temp,
                    COUNT(*) as sample_count
                FROM milk_quality_readings
                WHERE collection_timestamp >= NOW() - INTERVAL '24 hours'
                GROUP BY bucket
                ORDER BY bucket
                """,
            ),
        ]

        results = []
        for name, query in queries:
            query_results = []
            for _ in range(10):
                result = await self.execute_query(query, name)
                query_results.append(result)

            avg_time = statistics.mean([r.execution_time_ms for r in query_results])
            results.append(QueryResult(
                query_name=name,
                execution_time_ms=avg_time,
                rows_affected=query_results[0].rows_affected,
                success=all(r.success for r in query_results),
            ))

        return results

    async def benchmark_order_queries(self) -> List[QueryResult]:
        """Benchmark order processing queries."""
        queries = [
            (
                "active_orders_with_items",
                """
                SELECT
                    o.*,
                    c.name as customer_name,
                    json_agg(json_build_object(
                        'product_id', oi.product_id,
                        'product_name', p.name,
                        'quantity', oi.quantity,
                        'unit_price', oi.unit_price
                    )) as items
                FROM orders o
                JOIN customers c ON o.customer_id = c.id
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE o.status IN ('pending', 'processing', 'ready')
                GROUP BY o.id, c.name
                ORDER BY o.created_at DESC
                LIMIT 50
                """,
            ),
            (
                "order_statistics_dashboard",
                """
                SELECT
                    COUNT(*) FILTER (WHERE status = 'pending') as pending_orders,
                    COUNT(*) FILTER (WHERE status = 'processing') as processing_orders,
                    COUNT(*) FILTER (WHERE status = 'delivered') as delivered_orders,
                    SUM(total_amount) FILTER (WHERE created_at >= CURRENT_DATE) as today_revenue,
                    AVG(EXTRACT(EPOCH FROM (delivered_at - created_at))/3600)
                        FILTER (WHERE status = 'delivered') as avg_delivery_hours
                FROM orders
                WHERE created_at >= CURRENT_DATE - INTERVAL '30 days'
                """,
            ),
        ]

        results = []
        for name, query in queries:
            query_results = []
            for _ in range(10):
                result = await self.execute_query(query, name)
                query_results.append(result)

            avg_time = statistics.mean([r.execution_time_ms for r in query_results])
            results.append(QueryResult(
                query_name=name,
                execution_time_ms=avg_time,
                rows_affected=query_results[0].rows_affected,
                success=all(r.success for r in query_results),
            ))

        return results

    async def benchmark_concurrent_connections(
        self,
        num_connections: int = 100,
    ) -> Dict[str, Any]:
        """Benchmark concurrent database connections."""
        simple_query = "SELECT 1"

        async def run_query():
            start = time.perf_counter()
            async with self.connection_pool.acquire() as conn:
                await conn.fetchval(simple_query)
            return (time.perf_counter() - start) * 1000

        start_time = time.perf_counter()
        tasks = [run_query() for _ in range(num_connections)]
        results = await asyncio.gather(*tasks, return_exceptions=True)
        total_time = (time.perf_counter() - start_time) * 1000

        successful = [r for r in results if isinstance(r, float)]
        failed = [r for r in results if isinstance(r, Exception)]

        return {
            'total_connections': num_connections,
            'successful': len(successful),
            'failed': len(failed),
            'total_time_ms': total_time,
            'avg_connection_time_ms': statistics.mean(successful) if successful else 0,
            'p95_connection_time_ms': sorted(successful)[int(len(successful) * 0.95)] if successful else 0,
            'connections_per_second': (len(successful) / total_time) * 1000,
        }

    async def benchmark_write_performance(
        self,
        num_records: int = 1000,
    ) -> Dict[str, Any]:
        """Benchmark write performance."""
        insert_query = """
            INSERT INTO milk_collections
            (animal_id, farm_id, collection_date, shift, quantity_liters,
             fat_percentage, snf_percentage, temperature)
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING id
        """

        start_time = time.perf_counter()

        async with self.connection_pool.acquire() as conn:
            async with conn.transaction():
                for i in range(num_records):
                    await conn.fetchval(
                        insert_query,
                        (i % 100) + 1,  # animal_id
                        (i % 10) + 1,   # farm_id
                        datetime.now().date(),
                        'morning' if i % 2 == 0 else 'evening',
                        15.5 + (i % 10) * 0.5,
                        3.5 + (i % 5) * 0.1,
                        8.5 + (i % 3) * 0.1,
                        37.0 + (i % 4) * 0.2,
                    )

        total_time = (time.perf_counter() - start_time) * 1000

        return {
            'records_inserted': num_records,
            'total_time_ms': total_time,
            'avg_insert_time_ms': total_time / num_records,
            'inserts_per_second': (num_records / total_time) * 1000,
        }

    async def run_full_benchmark(self) -> Dict[str, Any]:
        """Run complete benchmark suite."""
        await self.setup()

        try:
            results = {
                'timestamp': datetime.now().isoformat(),
                'animal_queries': [],
                'collection_queries': [],
                'order_queries': [],
                'concurrent_connections': {},
                'write_performance': {},
            }

            # Run query benchmarks
            animal_results = await self.benchmark_animal_queries()
            results['animal_queries'] = [
                {
                    'name': r.query_name,
                    'avg_time_ms': r.execution_time_ms,
                    'rows': r.rows_affected,
                    'success': r.success,
                }
                for r in animal_results
            ]

            collection_results = await self.benchmark_milk_collection_queries()
            results['collection_queries'] = [
                {
                    'name': r.query_name,
                    'avg_time_ms': r.execution_time_ms,
                    'rows': r.rows_affected,
                    'success': r.success,
                }
                for r in collection_results
            ]

            order_results = await self.benchmark_order_queries()
            results['order_queries'] = [
                {
                    'name': r.query_name,
                    'avg_time_ms': r.execution_time_ms,
                    'rows': r.rows_affected,
                    'success': r.success,
                }
                for r in order_results
            ]

            # Run connection benchmark
            results['concurrent_connections'] = await self.benchmark_concurrent_connections(100)

            # Run write benchmark
            results['write_performance'] = await self.benchmark_write_performance(1000)

            # Calculate summary
            all_query_times = [
                r['avg_time_ms']
                for category in ['animal_queries', 'collection_queries', 'order_queries']
                for r in results[category]
            ]

            results['summary'] = {
                'total_queries_tested': len(all_query_times),
                'avg_query_time_ms': statistics.mean(all_query_times),
                'max_query_time_ms': max(all_query_times),
                'min_query_time_ms': min(all_query_times),
                'p95_query_time_ms': sorted(all_query_times)[int(len(all_query_times) * 0.95)],
                'all_queries_under_100ms': all(t < 100 for t in all_query_times),
            }

            return results

        finally:
            await self.cleanup()


# CLI entry point
async def main():
    config = {
        'host': 'localhost',
        'port': 5432,
        'user': 'smartdairy',
        'password': 'password',
        'database': 'smartdairy_staging',
    }

    tester = DatabasePerformanceTester(config)
    results = await tester.run_full_benchmark()

    print(json.dumps(results, indent=2))

    # Save results
    with open('db-perf-results.json', 'w') as f:
        json.dump(results, f, indent=2)


if __name__ == '__main__':
    asyncio.run(main())
```

### 4.6 Flutter Performance Testing

```dart
// test/performance/flutter_performance_test.dart
// Smart Dairy Flutter Mobile App Performance Tests

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:smart_dairy_mobile/main.dart' as app;
import 'package:flutter/foundation.dart';
import 'dart:developer' as developer;

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();
  final binding = IntegrationTestWidgetsFlutterBinding.instance;

  group('App Launch Performance', () {
    testWidgets('Cold start time should be under 3 seconds', (tester) async {
      final stopwatch = Stopwatch()..start();

      app.main();
      await tester.pumpAndSettle(const Duration(seconds: 5));

      stopwatch.stop();

      final coldStartTime = stopwatch.elapsedMilliseconds;
      debugPrint('Cold start time: ${coldStartTime}ms');

      expect(coldStartTime, lessThan(3000),
        reason: 'Cold start should complete in under 3 seconds');
    });

    testWidgets('First frame render time', (tester) async {
      await binding.traceAction(() async {
        app.main();
        await tester.pump();
      }, reportKey: 'first_frame_render');
    });
  });

  group('Navigation Performance', () {
    testWidgets('Dashboard navigation should be smooth', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Login first
      await tester.enterText(
        find.byKey(const Key('email_field')),
        'test@smartdairy.com',
      );
      await tester.enterText(
        find.byKey(const Key('password_field')),
        'TestPassword123!',
      );
      await tester.tap(find.byKey(const Key('login_button')));
      await tester.pumpAndSettle();

      // Measure dashboard load
      final stopwatch = Stopwatch()..start();
      await tester.tap(find.byKey(const Key('dashboard_tab')));
      await tester.pumpAndSettle();
      stopwatch.stop();

      debugPrint('Dashboard load time: ${stopwatch.elapsedMilliseconds}ms');
      expect(stopwatch.elapsedMilliseconds, lessThan(500));
    });

    testWidgets('List scroll performance', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Navigate to animals list
      await tester.tap(find.byKey(const Key('animals_tab')));
      await tester.pumpAndSettle();

      // Measure scroll performance with timeline
      await binding.traceAction(() async {
        // Scroll down rapidly
        for (var i = 0; i < 10; i++) {
          await tester.drag(
            find.byKey(const Key('animals_list')),
            const Offset(0, -500),
          );
          await tester.pump();
        }

        // Scroll back up
        for (var i = 0; i < 10; i++) {
          await tester.drag(
            find.byKey(const Key('animals_list')),
            const Offset(0, 500),
          );
          await tester.pump();
        }
      }, reportKey: 'list_scroll_performance');
    });
  });

  group('Widget Build Performance', () {
    testWidgets('Animal card build time', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Navigate to animals
      await tester.tap(find.byKey(const Key('animals_tab')));

      final stopwatch = Stopwatch()..start();
      await tester.pumpAndSettle();
      stopwatch.stop();

      debugPrint('Animals list build time: ${stopwatch.elapsedMilliseconds}ms');
      expect(stopwatch.elapsedMilliseconds, lessThan(1000));
    });

    testWidgets('Dashboard widgets build time', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Measure dashboard widget build
      final stopwatch = Stopwatch()..start();
      await tester.tap(find.byKey(const Key('dashboard_tab')));
      await tester.pumpAndSettle();
      stopwatch.stop();

      debugPrint('Dashboard build time: ${stopwatch.elapsedMilliseconds}ms');
      expect(stopwatch.elapsedMilliseconds, lessThan(500));
    });
  });

  group('Memory Performance', () {
    testWidgets('Memory usage during list operations', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Navigate to animals list
      await tester.tap(find.byKey(const Key('animals_tab')));
      await tester.pumpAndSettle();

      // Record initial memory
      developer.log('Starting memory test', name: 'performance');

      // Perform many operations
      for (var i = 0; i < 50; i++) {
        await tester.drag(
          find.byKey(const Key('animals_list')),
          const Offset(0, -300),
        );
        await tester.pump();
      }

      // Scroll back to trigger garbage collection
      for (var i = 0; i < 50; i++) {
        await tester.drag(
          find.byKey(const Key('animals_list')),
          const Offset(0, 300),
        );
        await tester.pump();
      }

      await tester.pumpAndSettle();

      // Memory check happens in CI via Flutter DevTools
      developer.log('Memory test complete', name: 'performance');
    });
  });

  group('Network Request Performance', () {
    testWidgets('API response handling time', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Login
      await tester.enterText(
        find.byKey(const Key('email_field')),
        'test@smartdairy.com',
      );
      await tester.enterText(
        find.byKey(const Key('password_field')),
        'TestPassword123!',
      );

      final stopwatch = Stopwatch()..start();
      await tester.tap(find.byKey(const Key('login_button')));
      await tester.pumpAndSettle(const Duration(seconds: 5));
      stopwatch.stop();

      debugPrint('Login request time: ${stopwatch.elapsedMilliseconds}ms');
      expect(stopwatch.elapsedMilliseconds, lessThan(3000));
    });

    testWidgets('Data refresh performance', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Pull to refresh
      await tester.tap(find.byKey(const Key('animals_tab')));
      await tester.pumpAndSettle();

      final stopwatch = Stopwatch()..start();
      await tester.drag(
        find.byKey(const Key('animals_list')),
        const Offset(0, 300),
      );
      await tester.pumpAndSettle(const Duration(seconds: 5));
      stopwatch.stop();

      debugPrint('Refresh time: ${stopwatch.elapsedMilliseconds}ms');
      expect(stopwatch.elapsedMilliseconds, lessThan(2000));
    });
  });

  group('Offline Mode Performance', () {
    testWidgets('Offline data access speed', (tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Enable offline mode
      await tester.tap(find.byKey(const Key('settings_tab')));
      await tester.pumpAndSettle();
      await tester.tap(find.byKey(const Key('offline_mode_switch')));
      await tester.pumpAndSettle();

      // Navigate to cached data
      final stopwatch = Stopwatch()..start();
      await tester.tap(find.byKey(const Key('animals_tab')));
      await tester.pumpAndSettle();
      stopwatch.stop();

      debugPrint('Offline data load time: ${stopwatch.elapsedMilliseconds}ms');
      expect(stopwatch.elapsedMilliseconds, lessThan(500),
        reason: 'Offline data should load quickly from cache');
    });
  });
}


// Custom performance metrics reporter
class PerformanceMetrics {
  static final Map<String, List<int>> _metrics = {};

  static void record(String name, int milliseconds) {
    _metrics.putIfAbsent(name, () => []);
    _metrics[name]!.add(milliseconds);
  }

  static Map<String, dynamic> getSummary() {
    final summary = <String, dynamic>{};

    for (final entry in _metrics.entries) {
      final values = entry.value;
      values.sort();

      summary[entry.key] = {
        'count': values.length,
        'min': values.first,
        'max': values.last,
        'avg': (values.reduce((a, b) => a + b) / values.length).round(),
        'p95': values[(values.length * 0.95).floor()],
      };
    }

    return summary;
  }

  static void printReport() {
    debugPrint('=== Performance Metrics Report ===');
    for (final entry in getSummary().entries) {
      debugPrint('${entry.key}:');
      final data = entry.value as Map<String, dynamic>;
      debugPrint('  Min: ${data['min']}ms');
      debugPrint('  Max: ${data['max']}ms');
      debugPrint('  Avg: ${data['avg']}ms');
      debugPrint('  P95: ${data['p95']}ms');
    }
  }
}
```

---

## 5. Testing & Validation

### 5.1 Performance Test Acceptance Criteria

| Test Category | Metric | Target | Priority |
|--------------|--------|--------|----------|
| API Load Test | P95 Response Time | < 200ms | Critical |
| API Load Test | P99 Response Time | < 500ms | High |
| API Load Test | Error Rate | < 1% | Critical |
| API Load Test | Throughput | > 100 req/s | High |
| Database | Query P95 | < 100ms | Critical |
| Database | Connection Time | < 50ms | High |
| Database | Write Throughput | > 500 inserts/s | High |
| Frontend | Lighthouse Score | > 90 | Critical |
| Frontend | FCP | < 1.8s | Critical |
| Frontend | LCP | < 2.5s | Critical |
| Frontend | CLS | < 0.1 | High |
| Mobile | Cold Start | < 3s | Critical |
| Mobile | Frame Rate | > 55 FPS | High |
| Concurrent | 1000 Users | Stable | Critical |
| Stress | 150% Capacity | Graceful Degradation | High |

### 5.2 Test Execution Checklist

#### Day 671 - Framework Setup
- [ ] k6 installed and configured
- [ ] JMeter installed and configured
- [ ] Lighthouse CI configured
- [ ] InfluxDB/Grafana metrics pipeline ready
- [ ] Test data populated
- [ ] Baseline metrics captured

#### Day 672 - API/Database Testing
- [ ] All API endpoints tested
- [ ] Database queries benchmarked
- [ ] Slow queries identified
- [ ] Frontend Lighthouse scores recorded
- [ ] Component render times measured

#### Day 673 - Concurrent/Stress Testing
- [ ] 100 concurrent user baseline passed
- [ ] 500 concurrent user test passed
- [ ] 1000 concurrent user test passed
- [ ] WebSocket load test completed
- [ ] Bundle analysis completed

#### Day 674 - Stress/Mobile Testing
- [ ] 150% capacity stress test completed
- [ ] Breaking point identified
- [ ] Memory profiling completed
- [ ] Flutter performance tests passed
- [ ] Mobile cold start under 3 seconds

#### Day 675 - Reporting
- [ ] All test results compiled
- [ ] SLA validation document created
- [ ] Performance report generated
- [ ] Optimization recommendations documented
- [ ] Handoff to Milestone 136 completed

### 5.3 Performance Baseline Template

```markdown
# Performance Baseline Report

## Test Environment
- Date: [DATE]
- Environment: Staging
- Data Volume: [X] animals, [Y] orders, [Z] collections

## API Performance Baseline

| Endpoint | Method | P50 | P95 | P99 | Throughput |
|----------|--------|-----|-----|-----|------------|
| /api/v1/auth/login | POST | [X]ms | [X]ms | [X]ms | [X]/s |
| /api/v1/animals | GET | [X]ms | [X]ms | [X]ms | [X]/s |
| /api/v1/collections | POST | [X]ms | [X]ms | [X]ms | [X]/s |
| /api/v1/orders | POST | [X]ms | [X]ms | [X]ms | [X]/s |
| /api/v1/dashboard | GET | [X]ms | [X]ms | [X]ms | [X]/s |

## Database Performance Baseline

| Query Type | P50 | P95 | P99 |
|------------|-----|-----|-----|
| Simple SELECT | [X]ms | [X]ms | [X]ms |
| JOIN queries | [X]ms | [X]ms | [X]ms |
| Aggregation | [X]ms | [X]ms | [X]ms |
| Full-text search | [X]ms | [X]ms | [X]ms |

## Frontend Performance Baseline

| Page | Lighthouse | FCP | LCP | CLS | TBT |
|------|------------|-----|-----|-----|-----|
| Home | [X] | [X]s | [X]s | [X] | [X]ms |
| Dashboard | [X] | [X]s | [X]s | [X] | [X]ms |
| Animals List | [X] | [X]s | [X]s | [X] | [X]ms |

## Mobile Performance Baseline

| Metric | Android | iOS |
|--------|---------|-----|
| Cold Start | [X]s | [X]s |
| Avg Frame Time | [X]ms | [X]ms |
| Memory (Peak) | [X]MB | [X]MB |
```

---

## 6. Risk & Mitigation

### 6.1 Risk Register

| Risk ID | Risk Description | Probability | Impact | Mitigation Strategy | Owner |
|---------|-----------------|-------------|--------|---------------------|-------|
| PERF-R001 | API response times exceed SLA under load | Medium | Critical | Implement caching, optimize queries, add indices | Dev 1 |
| PERF-R002 | Database connection pool exhaustion | Medium | High | Configure pool limits, implement connection monitoring | Dev 2 |
| PERF-R003 | Memory leaks under sustained load | Low | High | Memory profiling, code review, garbage collection tuning | Dev 2 |
| PERF-R004 | Frontend bundle size too large | Medium | Medium | Code splitting, lazy loading, tree shaking | Dev 3 |
| PERF-R005 | Mobile app slow on older devices | Medium | Medium | Performance profiling on target devices, optimization | Dev 3 |
| PERF-R006 | Third-party API latency affects performance | Medium | Medium | Implement timeouts, fallbacks, caching | Dev 1 |
| PERF-R007 | Test environment not representative | Low | High | Use production-like data volumes and configuration | Dev 2 |
| PERF-R008 | Inconsistent test results | Medium | Medium | Multiple test runs, statistical analysis, controlled environment | Dev 1 |

### 6.2 Contingency Plans

| Scenario | Trigger | Response Plan |
|----------|---------|---------------|
| API P95 > 200ms | Load test results | 1. Identify slow endpoints, 2. Add database indices, 3. Implement caching |
| Concurrent users < 1000 | JMeter test fails | 1. Scale infrastructure, 2. Optimize connection pools, 3. Add load balancer |
| Lighthouse score < 90 | CI check fails | 1. Analyze bundle, 2. Optimize images, 3. Implement lazy loading |
| Mobile cold start > 3s | Flutter test fails | 1. Analyze startup sequence, 2. Reduce init operations, 3. Implement splash screen |

---

## 7. Dependencies & Handoffs

### 7.1 Internal Dependencies

| Dependency | Source | Required By | Status |
|------------|--------|-------------|--------|
| Security testing completion | Milestone 134 | Day 671 | Pending |
| Staging environment access | DevOps | Day 671 | Ready |
| Production-like data set | UAT Preparation | Day 671 | Ready |
| Monitoring dashboards | DevOps | Day 672 | Ready |
| CI/CD pipeline access | DevOps | Day 671 | Ready |

### 7.2 External Dependencies

| Dependency | Provider | Required By | Status |
|------------|----------|-------------|--------|
| k6 Cloud account | Grafana Labs | Day 671 | Active |
| InfluxDB Cloud | InfluxData | Day 671 | Active |
| Lighthouse CI server | Self-hosted | Day 671 | Ready |
| bKash sandbox | bKash | Day 673 | Active |
| Nagad sandbox | Nagad | Day 673 | Active |

### 7.3 Milestone 136 Handoff Package

| Item | Description | Location |
|------|-------------|----------|
| Performance Report | Comprehensive PDF report | `/docs/reports/performance-report.pdf` |
| k6 Test Scripts | Load testing scripts | `/tests/performance/k6/` |
| JMeter Test Plans | JMeter configurations | `/tests/performance/jmeter/` |
| Baseline Metrics | Performance baselines | `/docs/baselines/` |
| Optimization Log | Issues and fixes | `/docs/reports/optimization-log.md` |
| CI/CD Configuration | Performance test pipeline | `.github/workflows/` |

---

## 8. Appendices

### 8.1 Tool Versions

| Tool | Version | Purpose |
|------|---------|---------|
| k6 | 0.47.0+ | Load testing |
| Apache JMeter | 5.6.3+ | Load testing |
| Lighthouse CI | 0.13.x | Frontend performance |
| InfluxDB | 2.7+ | Metrics storage |
| Grafana | 10.0+ | Metrics visualization |
| pgbench | 16.x | Database benchmarking |
| Flutter DevTools | Latest | Mobile profiling |

### 8.2 Performance Test Data Requirements

| Data Type | Volume | Purpose |
|-----------|--------|---------|
| Animals | 10,000+ | List pagination testing |
| Orders | 50,000+ | Order processing load |
| Milk Collections | 500,000+ | Time-series queries |
| Customers | 5,000+ | User lookup performance |
| Products | 500+ | Inventory queries |

### 8.3 Reference Documentation

| Document | Location | Purpose |
|----------|----------|---------|
| k6 Documentation | https://k6.io/docs/ | Test script reference |
| JMeter User Manual | https://jmeter.apache.org/usermanual/ | Test plan creation |
| Lighthouse CI Docs | https://github.com/GoogleChrome/lighthouse-ci | CI integration |
| PostgreSQL Performance | https://www.postgresql.org/docs/16/performance-tips.html | DB optimization |
| Flutter Performance | https://docs.flutter.dev/perf | Mobile optimization |

### 8.4 Glossary

| Term | Definition |
|------|------------|
| P50/P95/P99 | Percentile latency measurements |
| TPS | Transactions Per Second |
| VU | Virtual User (simulated user in load test) |
| FCP | First Contentful Paint |
| LCP | Largest Contentful Paint |
| CLS | Cumulative Layout Shift |
| TBT | Total Blocking Time |
| TTFB | Time to First Byte |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-05 | Dev Team | Initial document creation |

---

**End of Milestone 135: Performance Testing**
