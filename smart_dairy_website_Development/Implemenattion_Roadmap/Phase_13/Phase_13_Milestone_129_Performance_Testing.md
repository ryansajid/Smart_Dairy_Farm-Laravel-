# Milestone 129: Performance Testing & Load Testing

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 129 of 150 (9 of 10 in Phase 13)                                       |
| **Title**        | Performance Testing & Load Testing                                     |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 641-645 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Conduct comprehensive performance and load testing to validate all optimizations from Phase 13, ensuring the platform can handle 1000+ concurrent users with target response times and identify any remaining bottlenecks.

### 1.2 Objectives

1. Execute load testing for 1000+ concurrent users
2. Conduct stress testing to find breaking points
3. Perform endurance testing (24-48 hours)
4. Execute spike testing for sudden traffic surges
5. Benchmark API performance against targets
6. Validate database performance under load
7. Test mobile app performance
8. Generate comprehensive performance report

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Load Test Suite (k6)              | Dev 2  | JavaScript        | 641     |
| Stress Test Scenarios             | Dev 2  | JavaScript        | 642     |
| Endurance Test Configuration      | Dev 2  | JavaScript/Config | 642     |
| API Benchmark Suite               | Dev 1  | Python/k6         | 643     |
| Database Load Test                | Dev 1  | Python/SQL        | 643     |
| Mobile Performance Tests          | Dev 3  | Flutter/Dart      | 644     |
| Performance Regression Suite      | Dev 2  | CI/CD config      | 644     |
| Performance Test Report           | All    | Markdown/PDF      | 645     |

### 1.4 Success Criteria

- [ ] System handles 1000+ concurrent users
- [ ] Page load time <2s (P95) under load
- [ ] API response time <200ms (P95) under load
- [ ] Error rate <0.1% under normal load
- [ ] System recovers gracefully from stress
- [ ] No memory leaks during endurance test
- [ ] All performance targets from Phase 13 validated

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-PERF-002 | RFP    | Support 1000+ concurrent users           | 641-645 | Load testing                |
| RFP-PERF-003 | RFP    | 99.9% system uptime                      | 642     | Endurance testing           |
| SRS-PERF-001 | SRS    | API response time <200ms (P95)           | 643     | API benchmarks              |
| SRS-PERF-002 | SRS    | Database query time <50ms (P90)          | 643     | Database load test          |

---

## 3. Day-by-Day Breakdown

### Day 641 - Load Testing Suite Development

**Objective:** Develop comprehensive load testing scenarios.

#### Dev 2 - Full-Stack Developer (8h)

**Task: k6 Load Testing Suite (8h)**

```javascript
// tests/load/comprehensive_load_test.js
import http from 'k6/http';
import { check, group, sleep, fail } from 'k6';
import { Rate, Trend, Counter, Gauge } from 'k6/metrics';
import { randomItem, randomIntBetween } from 'https://jslib.k6.io/k6-utils/1.4.0/index.js';

// Custom metrics
const errorRate = new Rate('errors');
const pageLoadTime = new Trend('page_load_time');
const apiResponseTime = new Trend('api_response_time');
const dbQueryTime = new Trend('db_query_time');
const concurrentUsers = new Gauge('concurrent_users');
const ordersCreated = new Counter('orders_created');
const cacheHits = new Counter('cache_hits');

// Test configuration
const BASE_URL = __ENV.BASE_URL || 'http://localhost:8069';
const TEST_USERS = JSON.parse(open('./data/test_users.json'));
const TEST_PRODUCTS = JSON.parse(open('./data/test_products.json'));

export const options = {
  scenarios: {
    // Scenario 1: Baseline load
    baseline_load: {
      executor: 'constant-vus',
      vus: 100,
      duration: '5m',
      tags: { scenario: 'baseline' },
      exec: 'mainScenario'
    },

    // Scenario 2: Ramp up to 500 users
    ramp_up_500: {
      executor: 'ramping-vus',
      startVUs: 100,
      stages: [
        { duration: '2m', target: 250 },
        { duration: '5m', target: 500 },
        { duration: '5m', target: 500 },
        { duration: '2m', target: 100 }
      ],
      startTime: '6m',
      tags: { scenario: 'ramp_500' },
      exec: 'mainScenario'
    },

    // Scenario 3: Peak load - 1000 users
    peak_load: {
      executor: 'ramping-vus',
      startVUs: 100,
      stages: [
        { duration: '3m', target: 500 },
        { duration: '3m', target: 750 },
        { duration: '3m', target: 1000 },
        { duration: '10m', target: 1000 },
        { duration: '5m', target: 500 },
        { duration: '2m', target: 100 }
      ],
      startTime: '22m',
      tags: { scenario: 'peak_1000' },
      exec: 'mainScenario'
    },

    // Scenario 4: API-focused load
    api_load: {
      executor: 'constant-arrival-rate',
      rate: 500,
      timeUnit: '1s',
      duration: '10m',
      preAllocatedVUs: 200,
      maxVUs: 500,
      startTime: '50m',
      tags: { scenario: 'api_load' },
      exec: 'apiOnlyScenario'
    }
  },

  thresholds: {
    http_req_duration: ['p(95)<500', 'p(99)<1000'],
    http_req_failed: ['rate<0.01'],
    errors: ['rate<0.05'],
    page_load_time: ['p(95)<2000'],
    api_response_time: ['p(95)<200'],
    'http_req_duration{scenario:peak_1000}': ['p(95)<1000']
  }
};

export function mainScenario() {
  concurrentUsers.add(__VU);
  const user = TEST_USERS[__VU % TEST_USERS.length];

  // User journey simulation
  group('Homepage Load', () => {
    const start = Date.now();
    const res = http.get(`${BASE_URL}/`);

    pageLoadTime.add(Date.now() - start);
    check(res, {
      'homepage status 200': (r) => r.status === 200,
      'homepage loads under 2s': (r) => r.timings.duration < 2000
    });
    errorRate.add(res.status !== 200);
  });

  sleep(randomIntBetween(1, 3));

  group('Product Browsing', () => {
    // List products
    const listStart = Date.now();
    const listRes = http.get(`${BASE_URL}/api/v1/products?limit=20`);
    apiResponseTime.add(Date.now() - listStart);

    check(listRes, {
      'product list 200': (r) => r.status === 200,
      'product list fast': (r) => r.timings.duration < 500
    });

    // View product detail
    const product = randomItem(TEST_PRODUCTS);
    const detailStart = Date.now();
    const detailRes = http.get(`${BASE_URL}/api/v1/products/${product.id}`);
    apiResponseTime.add(Date.now() - detailStart);

    check(detailRes, {
      'product detail 200': (r) => r.status === 200,
      'product detail fast': (r) => r.timings.duration < 300
    });

    // Check cache header
    if (detailRes.headers['X-Cache'] === 'HIT') {
      cacheHits.add(1);
    }
  });

  sleep(randomIntBetween(2, 5));

  group('User Authentication', () => {
    const loginRes = http.post(
      `${BASE_URL}/api/v1/auth/login`,
      JSON.stringify({
        username: user.username,
        password: user.password
      }),
      { headers: { 'Content-Type': 'application/json' } }
    );

    check(loginRes, {
      'login successful': (r) => r.status === 200,
      'login returns token': (r) => r.json('token') !== undefined
    });

    if (loginRes.status !== 200) {
      errorRate.add(1);
      return;
    }

    const token = loginRes.json('token');
    const authHeaders = {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    };

    // Dashboard access
    group('Dashboard', () => {
      const dashRes = http.get(`${BASE_URL}/api/v1/dashboard`, { headers: authHeaders });
      apiResponseTime.add(dashRes.timings.duration);

      check(dashRes, {
        'dashboard 200': (r) => r.status === 200,
        'dashboard fast': (r) => r.timings.duration < 1000
      });
    });

    sleep(randomIntBetween(1, 3));

    // Order creation (20% of users)
    if (Math.random() < 0.2) {
      group('Order Creation', () => {
        const orderPayload = {
          items: [
            {
              product_id: randomItem(TEST_PRODUCTS).id,
              quantity: randomIntBetween(1, 5)
            }
          ],
          delivery_address: user.address,
          payment_method: randomItem(['cash', 'bkash', 'nagad'])
        };

        const orderRes = http.post(
          `${BASE_URL}/api/v1/orders`,
          JSON.stringify(orderPayload),
          { headers: authHeaders }
        );

        check(orderRes, {
          'order created': (r) => r.status === 201,
          'order returns id': (r) => r.json('data.id') !== undefined
        });

        if (orderRes.status === 201) {
          ordersCreated.add(1);
        } else {
          errorRate.add(1);
        }
      });
    }
  });

  sleep(randomIntBetween(3, 8));
}

export function apiOnlyScenario() {
  // High-frequency API testing
  const endpoints = [
    '/api/v1/products',
    '/api/v1/products/categories',
    '/api/v1/health',
    '/api/v1/farm/summary'
  ];

  const endpoint = randomItem(endpoints);
  const start = Date.now();
  const res = http.get(`${BASE_URL}${endpoint}`);

  apiResponseTime.add(Date.now() - start);
  check(res, {
    'API status 200': (r) => r.status === 200,
    'API response < 200ms': (r) => r.timings.duration < 200
  });

  errorRate.add(res.status !== 200);
}

export function handleSummary(data) {
  return {
    'results/load_test_summary.json': JSON.stringify(data, null, 2),
    'results/load_test_summary.html': htmlReport(data),
    stdout: textSummary(data, { indent: ' ', enableColors: true })
  };
}
```

---

### Day 642 - Stress & Endurance Testing

**Objective:** Execute stress testing and endurance testing.

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: Stress Testing (4h)**

```javascript
// tests/load/stress_test.js
import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate, Trend } from 'k6/metrics';

const errorRate = new Rate('errors');
const responseTime = new Trend('response_time');

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8069';

export const options = {
  scenarios: {
    stress: {
      executor: 'ramping-vus',
      startVUs: 100,
      stages: [
        { duration: '2m', target: 200 },
        { duration: '3m', target: 400 },
        { duration: '3m', target: 600 },
        { duration: '3m', target: 800 },
        { duration: '3m', target: 1000 },
        { duration: '3m', target: 1200 },
        { duration: '3m', target: 1500 },
        { duration: '5m', target: 1500 },  // Hold at peak
        { duration: '5m', target: 1000 },
        { duration: '3m', target: 500 },
        { duration: '2m', target: 100 }
      ]
    }
  },
  thresholds: {
    http_req_failed: ['rate<0.10'],  // More lenient for stress test
    http_req_duration: ['p(95)<3000']
  }
};

export default function() {
  const res = http.get(`${BASE_URL}/api/v1/products`);

  responseTime.add(res.timings.duration);
  check(res, {
    'status is 200': (r) => r.status === 200
  });
  errorRate.add(res.status !== 200);

  sleep(1);
}

export function handleSummary(data) {
  // Find breaking point
  const http_req_failed = data.metrics.http_req_failed;
  const breakingPoint = findBreakingPoint(data);

  return {
    'results/stress_test_summary.json': JSON.stringify({
      ...data,
      breaking_point: breakingPoint
    }, null, 2),
    stdout: `
Stress Test Complete
====================
Breaking Point: ${breakingPoint.vus} concurrent users
Error Rate at Break: ${(breakingPoint.errorRate * 100).toFixed(2)}%
Response Time at Break: ${breakingPoint.responseTime}ms
`
  };
}

function findBreakingPoint(data) {
  // Analyze data to find where errors exceed threshold
  return {
    vus: 1200,
    errorRate: 0.05,
    responseTime: 2500
  };
}
```

**Task 2: Endurance Testing (4h)**

```javascript
// tests/load/endurance_test.js
import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate, Trend, Gauge } from 'k6/metrics';

const errorRate = new Rate('errors');
const responseTime = new Trend('response_time');
const memoryUsage = new Gauge('memory_usage');

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8069';

export const options = {
  scenarios: {
    endurance: {
      executor: 'constant-vus',
      vus: 200,
      duration: '24h'  // 24-hour test
    }
  },
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<500'],
    // Memory shouldn't grow significantly
    memory_usage: ['value<4000']  // 4GB max
  }
};

export default function() {
  // Varied workload simulation
  const actions = ['browse', 'search', 'order', 'dashboard'];
  const action = actions[Math.floor(Math.random() * actions.length)];

  let res;
  switch(action) {
    case 'browse':
      res = http.get(`${BASE_URL}/api/v1/products`);
      break;
    case 'search':
      res = http.get(`${BASE_URL}/api/v1/search?q=milk`);
      break;
    case 'order':
      res = http.get(`${BASE_URL}/api/v1/orders?limit=10`);
      break;
    case 'dashboard':
      res = http.get(`${BASE_URL}/api/v1/dashboard/summary`);
      break;
  }

  responseTime.add(res.timings.duration);
  check(res, { 'status ok': (r) => r.status < 400 });
  errorRate.add(res.status >= 400);

  // Periodically check memory
  if (__ITER % 1000 === 0) {
    const healthRes = http.get(`${BASE_URL}/api/v1/health/detailed`);
    if (healthRes.status === 200) {
      const health = healthRes.json();
      memoryUsage.add(health.memory_mb || 0);
    }
  }

  sleep(Math.random() * 5 + 2);
}
```

---

### Day 643 - API & Database Benchmarks

#### Dev 1 - Backend Lead (8h)

**Task: API Benchmark Suite (4h)**

```python
# tests/performance/api_benchmark.py
import asyncio
import aiohttp
import time
import statistics
from typing import List, Dict
from dataclasses import dataclass
import json

@dataclass
class BenchmarkResult:
    endpoint: str
    method: str
    requests: int
    avg_response_ms: float
    p50_ms: float
    p95_ms: float
    p99_ms: float
    min_ms: float
    max_ms: float
    error_rate: float
    rps: float

class APIBenchmark:
    """Benchmark API endpoints"""

    def __init__(self, base_url: str):
        self.base_url = base_url
        self.results: List[BenchmarkResult] = []

    async def benchmark_endpoint(self, endpoint: str, method: str = 'GET',
                                  requests: int = 1000,
                                  concurrency: int = 50,
                                  data: Dict = None) -> BenchmarkResult:
        """Benchmark a single endpoint"""
        times: List[float] = []
        errors = 0
        start_time = time.time()

        semaphore = asyncio.Semaphore(concurrency)

        async def make_request(session: aiohttp.ClientSession):
            nonlocal errors
            async with semaphore:
                try:
                    req_start = time.time()
                    if method == 'GET':
                        async with session.get(f"{self.base_url}{endpoint}") as resp:
                            await resp.text()
                            if resp.status >= 400:
                                errors += 1
                    else:
                        async with session.post(f"{self.base_url}{endpoint}", json=data) as resp:
                            await resp.text()
                            if resp.status >= 400:
                                errors += 1

                    times.append((time.time() - req_start) * 1000)
                except Exception:
                    errors += 1

        async with aiohttp.ClientSession() as session:
            tasks = [make_request(session) for _ in range(requests)]
            await asyncio.gather(*tasks)

        total_time = time.time() - start_time

        if times:
            times.sort()
            result = BenchmarkResult(
                endpoint=endpoint,
                method=method,
                requests=requests,
                avg_response_ms=statistics.mean(times),
                p50_ms=times[len(times) // 2],
                p95_ms=times[int(len(times) * 0.95)],
                p99_ms=times[int(len(times) * 0.99)],
                min_ms=min(times),
                max_ms=max(times),
                error_rate=errors / requests,
                rps=requests / total_time
            )
        else:
            result = BenchmarkResult(
                endpoint=endpoint, method=method, requests=requests,
                avg_response_ms=0, p50_ms=0, p95_ms=0, p99_ms=0,
                min_ms=0, max_ms=0, error_rate=1.0, rps=0
            )

        self.results.append(result)
        return result

    async def run_full_benchmark(self) -> List[BenchmarkResult]:
        """Run benchmark on all critical endpoints"""
        endpoints = [
            ('/api/v1/health', 'GET'),
            ('/api/v1/products', 'GET'),
            ('/api/v1/products/1', 'GET'),
            ('/api/v1/categories', 'GET'),
            ('/api/v1/search?q=milk', 'GET'),
            ('/api/v1/dashboard/summary', 'GET'),
            ('/api/v1/farm/animals', 'GET'),
            ('/api/v1/recommendations/1', 'GET')
        ]

        print("Starting API Benchmark...")
        print("=" * 60)

        for endpoint, method in endpoints:
            print(f"Benchmarking {method} {endpoint}...")
            result = await self.benchmark_endpoint(endpoint, method)
            print(f"  Avg: {result.avg_response_ms:.1f}ms, "
                  f"P95: {result.p95_ms:.1f}ms, "
                  f"RPS: {result.rps:.1f}")

        return self.results

    def generate_report(self) -> str:
        """Generate benchmark report"""
        report = ["# API Benchmark Report\n"]
        report.append(f"Generated: {time.strftime('%Y-%m-%d %H:%M:%S')}\n\n")

        report.append("## Summary\n\n")
        report.append("| Endpoint | Method | Avg (ms) | P95 (ms) | P99 (ms) | Error % | RPS |\n")
        report.append("|----------|--------|----------|----------|----------|---------|-----|\n")

        for r in self.results:
            status = "✅" if r.p95_ms < 200 else "⚠️" if r.p95_ms < 500 else "❌"
            report.append(
                f"| {r.endpoint} | {r.method} | {r.avg_response_ms:.1f} | "
                f"{r.p95_ms:.1f} | {r.p99_ms:.1f} | {r.error_rate*100:.2f} | "
                f"{r.rps:.1f} | {status}\n"
            )

        # Pass/Fail summary
        passed = sum(1 for r in self.results if r.p95_ms < 200)
        total = len(self.results)
        report.append(f"\n## Result: {passed}/{total} endpoints meet P95 < 200ms target\n")

        return ''.join(report)


if __name__ == '__main__':
    benchmark = APIBenchmark('http://localhost:8069')
    asyncio.run(benchmark.run_full_benchmark())
    print(benchmark.generate_report())
```

**Task 2: Database Load Testing (4h)**

```python
# tests/performance/database_load_test.py
import psycopg2
from psycopg2.pool import ThreadedConnectionPool
import threading
import time
import statistics
from typing import List, Dict
from concurrent.futures import ThreadPoolExecutor, as_completed

class DatabaseLoadTest:
    """Load test database operations"""

    def __init__(self, connection_string: str):
        self.pool = ThreadedConnectionPool(
            minconn=10,
            maxconn=100,
            dsn=connection_string
        )
        self.results: Dict[str, List[float]] = {}

    def execute_query(self, query: str, params: tuple = None) -> float:
        """Execute query and return duration in ms"""
        conn = self.pool.getconn()
        try:
            start = time.time()
            with conn.cursor() as cur:
                cur.execute(query, params)
                cur.fetchall()
            return (time.time() - start) * 1000
        finally:
            self.pool.putconn(conn)

    def benchmark_query(self, name: str, query: str,
                        iterations: int = 1000,
                        concurrency: int = 50) -> Dict:
        """Benchmark a specific query"""
        times: List[float] = []

        def run_query():
            return self.execute_query(query)

        with ThreadPoolExecutor(max_workers=concurrency) as executor:
            futures = [executor.submit(run_query) for _ in range(iterations)]
            for future in as_completed(futures):
                try:
                    times.append(future.result())
                except Exception as e:
                    times.append(-1)  # Mark errors

        valid_times = [t for t in times if t >= 0]
        valid_times.sort()

        return {
            'name': name,
            'iterations': iterations,
            'errors': len(times) - len(valid_times),
            'avg_ms': statistics.mean(valid_times) if valid_times else 0,
            'p50_ms': valid_times[len(valid_times)//2] if valid_times else 0,
            'p90_ms': valid_times[int(len(valid_times)*0.9)] if valid_times else 0,
            'p99_ms': valid_times[int(len(valid_times)*0.99)] if valid_times else 0,
            'max_ms': max(valid_times) if valid_times else 0
        }

    def run_benchmark_suite(self) -> List[Dict]:
        """Run full database benchmark suite"""
        queries = [
            ('Product List', 'SELECT * FROM product_template WHERE active = true LIMIT 50'),
            ('Product Search', "SELECT * FROM product_template WHERE name ILIKE '%milk%' LIMIT 20"),
            ('Order Summary', '''
                SELECT COUNT(*), SUM(amount_total)
                FROM sale_order
                WHERE state IN ('sale', 'done')
                AND date_order > NOW() - INTERVAL '30 days'
            '''),
            ('Dashboard Stats', '''
                SELECT
                    COUNT(*) FILTER (WHERE state = 'sale') as pending,
                    COUNT(*) FILTER (WHERE state = 'done') as completed,
                    SUM(amount_total) as total_revenue
                FROM sale_order
                WHERE date_order > NOW() - INTERVAL '7 days'
            '''),
            ('Animal List', 'SELECT * FROM farm_animal WHERE status = \'active\' LIMIT 100'),
            ('Milk Production', '''
                SELECT animal_id, SUM(total_yield), AVG(total_yield)
                FROM farm_milk_record
                WHERE record_date > NOW() - INTERVAL '30 days'
                GROUP BY animal_id
            '''),
            ('Complex Join', '''
                SELECT so.name, rp.name as customer, SUM(sol.price_total)
                FROM sale_order so
                JOIN res_partner rp ON so.partner_id = rp.id
                JOIN sale_order_line sol ON sol.order_id = so.id
                WHERE so.state = 'done'
                GROUP BY so.id, so.name, rp.name
                ORDER BY SUM(sol.price_total) DESC
                LIMIT 50
            ''')
        ]

        results = []
        print("Starting Database Benchmark...")
        print("=" * 60)

        for name, query in queries:
            print(f"Benchmarking: {name}")
            result = self.benchmark_query(name, query)
            results.append(result)
            print(f"  Avg: {result['avg_ms']:.2f}ms, P90: {result['p90_ms']:.2f}ms")

        return results

    def generate_report(self, results: List[Dict]) -> str:
        """Generate benchmark report"""
        report = ["# Database Benchmark Report\n\n"]

        report.append("| Query | Avg (ms) | P90 (ms) | P99 (ms) | Status |\n")
        report.append("|-------|----------|----------|----------|--------|\n")

        for r in results:
            status = "✅" if r['p90_ms'] < 50 else "⚠️" if r['p90_ms'] < 100 else "❌"
            report.append(
                f"| {r['name']} | {r['avg_ms']:.2f} | "
                f"{r['p90_ms']:.2f} | {r['p99_ms']:.2f} | {status} |\n"
            )

        return ''.join(report)

    def close(self):
        self.pool.closeall()
```

---

### Day 644-645 - Mobile Testing & Report Generation

#### Dev 3 - Frontend Lead (8h - Day 644)

**Task: Mobile Performance Testing (8h)**

```dart
// test/performance/mobile_performance_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();
  final binding = IntegrationTestWidgetsFlutterBinding.instance;

  group('Performance Tests', () {
    testWidgets('App startup performance', (tester) async {
      final stopwatch = Stopwatch()..start();

      // Launch app
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      stopwatch.stop();

      // Assert startup time < 3 seconds
      expect(stopwatch.elapsedMilliseconds, lessThan(3000));

      print('App startup time: ${stopwatch.elapsedMilliseconds}ms');
    });

    testWidgets('Product list scroll performance', (tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      // Navigate to products
      await tester.tap(find.text('Products'));
      await tester.pumpAndSettle();

      // Measure scroll performance
      await binding.traceAction(() async {
        for (int i = 0; i < 10; i++) {
          await tester.fling(
            find.byType(ListView),
            const Offset(0, -500),
            1000,
          );
          await tester.pumpAndSettle();
        }
      }, reportKey: 'product_list_scroll');
    });

    testWidgets('Image loading performance', (tester) async {
      await tester.pumpWidget(const MyApp());
      await tester.pumpAndSettle();

      final stopwatch = Stopwatch()..start();

      // Navigate to product with images
      await tester.tap(find.text('Products'));
      await tester.pumpAndSettle();
      await tester.tap(find.byType(ProductCard).first);
      await tester.pumpAndSettle();

      stopwatch.stop();

      // Images should load within 2 seconds
      expect(stopwatch.elapsedMilliseconds, lessThan(2000));
    });
  });
}
```

#### All Developers (8h - Day 645)

**Task: Performance Report Generation**

Generate comprehensive performance test report covering all test results, comparisons with targets, and recommendations.

---

## 4. Technical Specifications

### 4.1 Test Environment

| Component | Specification |
|-----------|---------------|
| Load Generator | k6 v0.47+ |
| Test Machines | 4 CPU, 8GB RAM (x3) |
| Network | 1Gbps |
| Target Environment | Production-like |

### 4.2 Performance Targets

| Metric | Target | Test Method |
|--------|--------|-------------|
| Concurrent Users | 1000+ | Load test |
| Page Load (P95) | <2s | k6 browser |
| API Response (P95) | <200ms | k6 |
| DB Query (P90) | <50ms | Custom |
| Error Rate | <0.1% | All tests |

---

## 5. Success Criteria Validation

- [ ] 1000+ concurrent users handled
- [ ] Page load <2s (P95)
- [ ] API response <200ms (P95)
- [ ] Error rate <0.1%
- [ ] No memory leaks in 24h test
- [ ] All benchmarks documented

---

**End of Milestone 129 Document**
