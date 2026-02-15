# Phase 15: Milestone 149 - Performance Monitoring & Optimization

## Document Control

| Attribute | Value |
|-----------|-------|
| **Milestone ID** | MS-149 |
| **Phase** | 15 - Deployment & Handover |
| **Sprint Duration** | Days 741-745 (5 working days) |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-05 |
| **Authors** | Dev 1 (Lead), Dev 2, Dev 3 |
| **Status** | Draft |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Establish production performance baselines, optimize identified bottlenecks, configure comprehensive monitoring, and create capacity planning documentation for future growth.

### 1.2 Key Deliverables

| Deliverable | Description | Owner | Day |
|-------------|-------------|-------|-----|
| Performance Baseline | Current metrics documentation | Dev 1 | 741 |
| Query Optimization | Database performance tuning | Dev 1 | 742 |
| Caching Verification | Redis cache effectiveness | Dev 2 | 743 |
| Auto-scaling Config | HPA validation | Dev 2 | 744 |
| Capacity Plan | Growth projections | All | 745 |

### 1.3 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Page load time (P95) | <3s | RUM metrics |
| API response (P95) | <500ms | APM |
| Database query time (P95) | <100ms | Query logs |
| Cache hit ratio | >90% | Redis metrics |
| Auto-scaling response | <2 min | HPA metrics |

---

## 2. Day-by-Day Implementation

### Day 741: Performance Baselining

#### Dev 1 Tasks (8 hours)

**Task 1: Collect Performance Baseline Metrics (8h)**

```python
# scripts/performance/baseline_collector.py
"""
Production Performance Baseline Collector
"""

import requests
import psycopg2
import redis
import json
from datetime import datetime, timedelta
from dataclasses import dataclass, asdict
from typing import Dict, List

@dataclass
class PerformanceBaseline:
    collection_date: str
    period_days: int
    web_metrics: Dict
    api_metrics: Dict
    database_metrics: Dict
    cache_metrics: Dict
    infrastructure_metrics: Dict

class BaselineCollector:
    def __init__(self, config: Dict):
        self.config = config
        self.prometheus_url = config['prometheus_url']

    def query_prometheus(self, query: str, time_range: str = "7d") -> Dict:
        """Query Prometheus for metrics"""
        response = requests.get(
            f"{self.prometheus_url}/api/v1/query",
            params={'query': query}
        )
        if response.status_code == 200:
            return response.json()['data']['result']
        return {}

    def collect_web_metrics(self) -> Dict:
        """Collect web application metrics"""
        metrics = {}

        # Page load time percentiles
        for p in [50, 90, 95, 99]:
            query = f'histogram_quantile(0.{p}, sum(rate(http_request_duration_seconds_bucket{{job="odoo"}}[7d])) by (le))'
            result = self.query_prometheus(query)
            if result:
                metrics[f'page_load_p{p}_ms'] = float(result[0]['value'][1]) * 1000

        # Request rate
        query = 'sum(rate(http_requests_total{job="odoo"}[7d]))'
        result = self.query_prometheus(query)
        if result:
            metrics['requests_per_second'] = float(result[0]['value'][1])

        # Error rate
        query = 'sum(rate(http_requests_total{job="odoo",status=~"5.."}[7d])) / sum(rate(http_requests_total{job="odoo"}[7d]))'
        result = self.query_prometheus(query)
        if result:
            metrics['error_rate_percent'] = float(result[0]['value'][1]) * 100

        return metrics

    def collect_api_metrics(self) -> Dict:
        """Collect API performance metrics"""
        metrics = {}

        # API response time percentiles
        for p in [50, 90, 95, 99]:
            query = f'histogram_quantile(0.{p}, sum(rate(api_request_duration_seconds_bucket[7d])) by (le))'
            result = self.query_prometheus(query)
            if result:
                metrics[f'response_time_p{p}_ms'] = float(result[0]['value'][1]) * 1000

        # API request rate by endpoint
        query = 'sum by (endpoint) (rate(api_requests_total[7d]))'
        result = self.query_prometheus(query)
        if result:
            metrics['top_endpoints'] = {
                r['metric']['endpoint']: float(r['value'][1])
                for r in sorted(result, key=lambda x: float(x['value'][1]), reverse=True)[:10]
            }

        return metrics

    def collect_database_metrics(self) -> Dict:
        """Collect database performance metrics"""
        conn = psycopg2.connect(
            host=self.config['db_host'],
            database=self.config['db_name'],
            user=self.config['db_user'],
            password=self.config['db_password']
        )
        cursor = conn.cursor()

        metrics = {}

        # Connection stats
        cursor.execute("""
            SELECT
                count(*) FILTER (WHERE state = 'active') as active,
                count(*) FILTER (WHERE state = 'idle') as idle,
                count(*) as total
            FROM pg_stat_activity
            WHERE datname = current_database()
        """)
        row = cursor.fetchone()
        metrics['connections'] = {
            'active': row[0],
            'idle': row[1],
            'total': row[2]
        }

        # Table sizes
        cursor.execute("""
            SELECT
                relname as table_name,
                pg_size_pretty(pg_total_relation_size(relid)) as total_size,
                n_live_tup as row_count
            FROM pg_stat_user_tables
            ORDER BY pg_total_relation_size(relid) DESC
            LIMIT 10
        """)
        metrics['top_tables'] = [
            {'table': row[0], 'size': row[1], 'rows': row[2]}
            for row in cursor.fetchall()
        ]

        # Slow queries
        cursor.execute("""
            SELECT
                calls,
                mean_exec_time as avg_time_ms,
                query
            FROM pg_stat_statements
            ORDER BY mean_exec_time DESC
            LIMIT 10
        """)
        metrics['slow_queries'] = [
            {'calls': row[0], 'avg_time_ms': row[1], 'query': row[2][:200]}
            for row in cursor.fetchall()
        ]

        conn.close()
        return metrics

    def collect_cache_metrics(self) -> Dict:
        """Collect Redis cache metrics"""
        r = redis.Redis(
            host=self.config['redis_host'],
            port=self.config.get('redis_port', 6379),
            password=self.config.get('redis_password')
        )

        info = r.info()

        return {
            'hit_rate': info.get('keyspace_hits', 0) / (
                info.get('keyspace_hits', 0) + info.get('keyspace_misses', 1)) * 100,
            'used_memory_mb': info.get('used_memory', 0) / (1024 * 1024),
            'connected_clients': info.get('connected_clients', 0),
            'total_keys': sum(
                int(v.get('keys', 0)) for k, v in info.items()
                if k.startswith('db') and isinstance(v, dict)
            ),
            'evicted_keys': info.get('evicted_keys', 0),
            'expired_keys': info.get('expired_keys', 0)
        }

    def collect_infrastructure_metrics(self) -> Dict:
        """Collect infrastructure metrics"""
        metrics = {}

        # CPU usage
        query = 'avg(rate(container_cpu_usage_seconds_total{namespace="smart-dairy"}[7d])) by (pod)'
        result = self.query_prometheus(query)
        if result:
            metrics['cpu_usage_by_pod'] = {
                r['metric']['pod']: round(float(r['value'][1]) * 100, 2)
                for r in result
            }

        # Memory usage
        query = 'avg(container_memory_usage_bytes{namespace="smart-dairy"}) by (pod)'
        result = self.query_prometheus(query)
        if result:
            metrics['memory_mb_by_pod'] = {
                r['metric']['pod']: round(float(r['value'][1]) / (1024 * 1024), 2)
                for r in result
            }

        # Network I/O
        query = 'sum(rate(container_network_receive_bytes_total{namespace="smart-dairy"}[7d]))'
        result = self.query_prometheus(query)
        if result:
            metrics['network_receive_mbps'] = round(float(result[0]['value'][1]) / (1024 * 1024), 2)

        return metrics

    def generate_baseline_report(self) -> PerformanceBaseline:
        """Generate complete baseline report"""
        return PerformanceBaseline(
            collection_date=datetime.utcnow().isoformat(),
            period_days=7,
            web_metrics=self.collect_web_metrics(),
            api_metrics=self.collect_api_metrics(),
            database_metrics=self.collect_database_metrics(),
            cache_metrics=self.collect_cache_metrics(),
            infrastructure_metrics=self.collect_infrastructure_metrics()
        )


if __name__ == "__main__":
    import os

    config = {
        'prometheus_url': os.environ.get('PROMETHEUS_URL', 'http://prometheus:9090'),
        'db_host': os.environ['DB_HOST'],
        'db_name': 'smartdairy',
        'db_user': 'admin',
        'db_password': os.environ['DB_PASSWORD'],
        'redis_host': os.environ['REDIS_HOST'],
    }

    collector = BaselineCollector(config)
    baseline = collector.generate_baseline_report()

    with open('performance_baseline.json', 'w') as f:
        json.dump(asdict(baseline), f, indent=2)

    print("Performance baseline collected and saved.")
```

#### Dev 2 Tasks (4h)

**Task: Application Performance Monitoring Review**

Review APM data and identify optimization opportunities.

#### Day 741 Deliverables

- [x] Performance baseline collected
- [x] Current metrics documented
- [x] Bottlenecks identified
- [x] Optimization priorities set

---

### Day 742: Database Query Optimization

#### Dev 1 Tasks (8 hours)

**Task 1: Query Analysis and Optimization (8h)**

```sql
-- scripts/performance/query_optimization.sql

-- 1. Identify slow queries
SELECT
    calls,
    mean_exec_time AS avg_ms,
    total_exec_time AS total_ms,
    rows,
    query
FROM pg_stat_statements
WHERE mean_exec_time > 100  -- queries averaging over 100ms
ORDER BY mean_exec_time DESC
LIMIT 20;

-- 2. Find missing indexes
SELECT
    schemaname,
    relname AS table_name,
    seq_scan,
    seq_tup_read,
    idx_scan,
    CASE WHEN seq_scan > 0
        THEN round((seq_tup_read::numeric / seq_scan), 2)
        ELSE 0
    END AS avg_rows_per_seq_scan
FROM pg_stat_user_tables
WHERE seq_scan > 100
AND idx_scan < seq_scan
ORDER BY seq_tup_read DESC
LIMIT 20;

-- 3. Identify index bloat
SELECT
    schemaname,
    tablename,
    indexname,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size,
    idx_scan AS index_scans
FROM pg_stat_user_indexes
WHERE idx_scan < 50
AND pg_relation_size(indexrelid) > 10485760  -- > 10MB
ORDER BY pg_relation_size(indexrelid) DESC;

-- 4. Create recommended indexes
-- Collection queries
CREATE INDEX CONCURRENTLY IF NOT EXISTS
    idx_dairy_collection_farmer_date_shift
    ON dairy_collection(farmer_id, date, shift);

-- Payment queries
CREATE INDEX CONCURRENTLY IF NOT EXISTS
    idx_dairy_payment_farmer_period
    ON dairy_payment(farmer_id, period_start, period_end);

-- Reporting queries
CREATE INDEX CONCURRENTLY IF NOT EXISTS
    idx_dairy_collection_date_center
    ON dairy_collection(date, collection_center_id)
    INCLUDE (quantity, fat, snf, amount);

-- 5. Optimize frequently used views
CREATE MATERIALIZED VIEW IF NOT EXISTS mv_daily_collection_summary AS
SELECT
    date,
    collection_center_id,
    shift,
    COUNT(*) AS collections,
    SUM(quantity) AS total_quantity,
    AVG(fat) AS avg_fat,
    AVG(snf) AS avg_snf,
    SUM(amount) AS total_amount
FROM dairy_collection
GROUP BY date, collection_center_id, shift;

CREATE UNIQUE INDEX ON mv_daily_collection_summary(date, collection_center_id, shift);

-- 6. Set up materialized view refresh
COMMENT ON MATERIALIZED VIEW mv_daily_collection_summary IS
'Refresh: REFRESH MATERIALIZED VIEW CONCURRENTLY mv_daily_collection_summary;
Schedule: Every 15 minutes during collection hours';

-- 7. Update table statistics
ANALYZE dairy_collection;
ANALYZE dairy_payment;
ANALYZE dairy_farmer;
ANALYZE account_move;
ANALYZE account_move_line;

-- 8. Configure query planner
ALTER SYSTEM SET random_page_cost = 1.1;  -- SSD storage
ALTER SYSTEM SET effective_cache_size = '12GB';  -- 75% of RAM
ALTER SYSTEM SET work_mem = '256MB';
ALTER SYSTEM SET maintenance_work_mem = '1GB';
SELECT pg_reload_conf();
```

**Query Optimization Report Template**

```markdown
# Query Optimization Report

## Executive Summary
- Queries optimized: X
- Performance improvement: X%
- New indexes created: X

## Optimizations Applied

### 1. Collection Queries
| Query | Before (ms) | After (ms) | Improvement |
|-------|-------------|------------|-------------|
| Daily summary | 850 | 120 | 85% |
| Farmer history | 1200 | 180 | 85% |

### 2. New Indexes Created
| Index | Table | Purpose |
|-------|-------|---------|
| idx_collection_farmer_date | dairy_collection | Farmer lookup |
| idx_payment_period | dairy_payment | Payment period queries |

### 3. Materialized Views
| View | Refresh Schedule | Purpose |
|------|------------------|---------|
| mv_daily_summary | Every 15 min | Daily reports |

## Recommendations
1. Monitor slow query log weekly
2. Refresh materialized views during off-peak
3. Review index usage monthly
```

#### Day 742 Deliverables

- [x] Slow queries identified and optimized
- [x] Missing indexes created
- [x] Materialized views configured
- [x] Database statistics updated
- [x] Query optimization report generated

---

### Day 743: Caching Strategy Verification

#### Dev 2 Tasks (8 hours)

**Task 1: Redis Cache Optimization (8h)**

```python
# scripts/performance/cache_optimization.py
"""
Redis Cache Optimization and Verification
"""

import redis
import json
from datetime import datetime
from typing import Dict, List

class CacheOptimizer:
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client

    def analyze_cache_usage(self) -> Dict:
        """Analyze current cache usage patterns"""
        info = self.redis.info()

        # Calculate hit rate
        hits = info.get('keyspace_hits', 0)
        misses = info.get('keyspace_misses', 0)
        total = hits + misses
        hit_rate = (hits / total * 100) if total > 0 else 0

        analysis = {
            'hit_rate_percent': round(hit_rate, 2),
            'memory_used_mb': round(info.get('used_memory', 0) / (1024 * 1024), 2),
            'memory_peak_mb': round(info.get('used_memory_peak', 0) / (1024 * 1024), 2),
            'fragmentation_ratio': info.get('mem_fragmentation_ratio', 0),
            'connected_clients': info.get('connected_clients', 0),
            'evicted_keys': info.get('evicted_keys', 0),
            'expired_keys': info.get('expired_keys', 0),
        }

        # Analyze key patterns
        key_analysis = self._analyze_key_patterns()
        analysis['key_patterns'] = key_analysis

        return analysis

    def _analyze_key_patterns(self) -> Dict:
        """Analyze key patterns and sizes"""
        patterns = {}

        # Sample keys (be careful in production)
        cursor = 0
        sample_size = 1000
        sampled = 0

        while sampled < sample_size:
            cursor, keys = self.redis.scan(cursor=cursor, count=100)
            for key in keys:
                key_str = key.decode() if isinstance(key, bytes) else key
                # Extract pattern (first part before colon)
                pattern = key_str.split(':')[0] if ':' in key_str else key_str

                if pattern not in patterns:
                    patterns[pattern] = {'count': 0, 'total_size': 0}

                patterns[pattern]['count'] += 1
                try:
                    patterns[pattern]['total_size'] += self.redis.memory_usage(key) or 0
                except:
                    pass

                sampled += 1

            if cursor == 0:
                break

        return {
            k: {
                'count': v['count'],
                'size_mb': round(v['total_size'] / (1024 * 1024), 2)
            }
            for k, v in sorted(patterns.items(), key=lambda x: x[1]['total_size'], reverse=True)[:10]
        }

    def optimize_cache_config(self) -> Dict:
        """Generate optimized cache configuration"""
        info = self.redis.info()
        max_memory = info.get('maxmemory', 0)
        used_memory = info.get('used_memory', 0)

        recommendations = []

        # Check memory utilization
        if max_memory > 0:
            utilization = used_memory / max_memory
            if utilization > 0.9:
                recommendations.append({
                    'issue': 'High memory utilization',
                    'current': f'{utilization*100:.1f}%',
                    'action': 'Increase maxmemory or review TTL policies'
                })

        # Check eviction policy
        eviction_policy = info.get('maxmemory_policy', 'noeviction')
        if eviction_policy == 'noeviction':
            recommendations.append({
                'issue': 'No eviction policy set',
                'current': eviction_policy,
                'action': 'Set to allkeys-lru for better cache behavior'
            })

        # Check fragmentation
        frag_ratio = info.get('mem_fragmentation_ratio', 1)
        if frag_ratio > 1.5:
            recommendations.append({
                'issue': 'High memory fragmentation',
                'current': f'{frag_ratio:.2f}',
                'action': 'Consider MEMORY DOCTOR or restart during maintenance'
            })

        return {
            'current_config': {
                'maxmemory': f'{max_memory / (1024*1024):.0f}MB' if max_memory else 'unlimited',
                'eviction_policy': eviction_policy,
            },
            'recommendations': recommendations,
            'suggested_config': {
                'maxmemory': '4gb',
                'maxmemory-policy': 'allkeys-lru',
                'activedefrag': 'yes',
                'lazyfree-lazy-eviction': 'yes',
            }
        }

    def verify_session_caching(self) -> Dict:
        """Verify Odoo session caching is working"""
        session_keys = []
        cursor = 0

        while True:
            cursor, keys = self.redis.scan(cursor=cursor, match='session:*', count=100)
            session_keys.extend(keys)
            if cursor == 0:
                break

        return {
            'active_sessions': len(session_keys),
            'session_cache_enabled': len(session_keys) > 0,
            'avg_session_size_kb': self._get_avg_size(session_keys) / 1024 if session_keys else 0
        }

    def _get_avg_size(self, keys: List) -> float:
        """Get average size of keys"""
        if not keys:
            return 0

        total_size = 0
        for key in keys[:100]:  # Sample first 100
            try:
                total_size += self.redis.memory_usage(key) or 0
            except:
                pass

        return total_size / min(len(keys), 100)


if __name__ == "__main__":
    import os

    r = redis.Redis(
        host=os.environ['REDIS_HOST'],
        port=int(os.environ.get('REDIS_PORT', 6379)),
        password=os.environ.get('REDIS_PASSWORD'),
        decode_responses=False
    )

    optimizer = CacheOptimizer(r)

    print("=== Cache Analysis ===")
    analysis = optimizer.analyze_cache_usage()
    print(json.dumps(analysis, indent=2))

    print("\n=== Configuration Recommendations ===")
    config = optimizer.optimize_cache_config()
    print(json.dumps(config, indent=2))

    print("\n=== Session Caching ===")
    sessions = optimizer.verify_session_caching()
    print(json.dumps(sessions, indent=2))
```

#### Day 743 Deliverables

- [x] Cache hit ratio verified (>90%)
- [x] Cache configuration optimized
- [x] Session caching validated
- [x] Cache monitoring alerts configured

---

### Day 744: Auto-Scaling Validation

#### Dev 2 Tasks (8 hours)

**Task 1: HPA Testing and Optimization (8h)**

```yaml
# kubernetes/production/hpa/optimized-hpa.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-hpa
  namespace: smart-dairy
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo
  minReplicas: 3
  maxReplicas: 10
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
      - type: Pods
        value: 2
        periodSeconds: 60
      - type: Percent
        value: 50
        periodSeconds: 60
      selectPolicy: Max
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
      - type: Pods
        value: 1
        periodSeconds: 120
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
        averageValue: "100"
---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: api-hpa
  namespace: smart-dairy
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: api
  minReplicas: 2
  maxReplicas: 8
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 30
      policies:
      - type: Pods
        value: 2
        periodSeconds: 30
    scaleDown:
      stabilizationWindowSeconds: 300
  metrics:
  - type: Resource
    resource:
      name: cpu
      target:
        type: Utilization
        averageUtilization: 70
```

**Auto-scaling Test Script**

```bash
#!/bin/bash
# scripts/performance/test_autoscaling.sh

echo "=== Auto-Scaling Test ==="

NAMESPACE="smart-dairy"

# Current state
echo "Current HPA status:"
kubectl get hpa -n $NAMESPACE

echo ""
echo "Current pod count:"
kubectl get pods -n $NAMESPACE -l app=odoo | grep -c Running

# Generate load
echo ""
echo "Generating load for 5 minutes..."
kubectl run load-generator --image=busybox -n $NAMESPACE --restart=Never --rm -i --tty -- /bin/sh -c "
while true; do
    wget -q -O- http://odoo:8069/web/health
    sleep 0.01
done
" &

LOAD_PID=$!

# Monitor scaling
for i in {1..10}; do
    echo ""
    echo "=== Check $i (after ${i}0 seconds) ==="
    kubectl get hpa -n $NAMESPACE
    kubectl get pods -n $NAMESPACE -l app=odoo | grep -c Running
    sleep 30
done

# Stop load
kill $LOAD_PID 2>/dev/null
kubectl delete pod load-generator -n $NAMESPACE 2>/dev/null

# Wait for scale down
echo ""
echo "Waiting for scale down (5 minutes)..."
sleep 300

echo ""
echo "Final state:"
kubectl get hpa -n $NAMESPACE
kubectl get pods -n $NAMESPACE -l app=odoo

echo "=== Test Complete ==="
```

#### Day 744 Deliverables

- [x] HPA configuration optimized
- [x] Auto-scaling tested under load
- [x] Scale-up/down behavior verified
- [x] Custom metrics configured

---

### Day 745: Capacity Planning

#### All Team Tasks

**Task 1: Capacity Planning Document (8h each)**

```markdown
# Smart Dairy Capacity Planning Document

## 1. Current Baseline (Production)

### Infrastructure
| Component | Current Spec | Utilization | Headroom |
|-----------|-------------|-------------|----------|
| EKS Nodes | 3x m5.xlarge | 45% CPU, 60% Memory | 55% |
| RDS | db.r5.large | 35% CPU, 40% Memory | 60% |
| ElastiCache | cache.r5.large x2 | 50% Memory | 50% |
| S3 | 50GB | N/A | Unlimited |

### Application Metrics
| Metric | Current Value | Limit | Headroom |
|--------|---------------|-------|----------|
| Daily Collections | 5,000 | 15,000 | 200% |
| Concurrent Users | 150 | 500 | 233% |
| API Requests/sec | 50 | 200 | 300% |
| Database Connections | 50 | 100 | 100% |

## 2. Growth Projections

### Business Growth Assumptions
| Year | Farmers | Daily Collections | Users |
|------|---------|-------------------|-------|
| Y1 | 5,000 | 8,000 | 200 |
| Y2 | 8,000 | 12,000 | 300 |
| Y3 | 12,000 | 18,000 | 450 |

### Infrastructure Requirements

#### Year 1 (Current + 60% growth)
| Component | Recommended | Action |
|-----------|-------------|--------|
| EKS Nodes | 4x m5.xlarge | Add 1 node |
| RDS | db.r5.xlarge | Upgrade in Q3 |
| ElastiCache | cache.r5.large x2 | No change |

#### Year 2 (Current + 140% growth)
| Component | Recommended | Action |
|-----------|-------------|--------|
| EKS Nodes | 5x m5.xlarge | Add 2 nodes |
| RDS | db.r5.xlarge | Add read replica |
| ElastiCache | cache.r5.xlarge x2 | Upgrade nodes |

#### Year 3 (Current + 260% growth)
| Component | Recommended | Action |
|-----------|-------------|--------|
| EKS Nodes | 7x m5.xlarge | Add 4 nodes |
| RDS | db.r5.2xlarge | Upgrade + 2 read replicas |
| ElastiCache | cache.r5.xlarge x3 | Add shard |

## 3. Cost Projections

### Monthly Infrastructure Cost
| Year | Compute | Database | Cache | Storage | Total |
|------|---------|----------|-------|---------|-------|
| Current | $800 | $400 | $200 | $50 | $1,450 |
| Y1 | $1,000 | $600 | $200 | $75 | $1,875 |
| Y2 | $1,200 | $900 | $350 | $100 | $2,550 |
| Y3 | $1,600 | $1,500 | $500 | $150 | $3,750 |

## 4. Scaling Triggers

### Automatic Scaling
| Metric | Threshold | Action |
|--------|-----------|--------|
| CPU > 70% | 5 min sustained | Add pod |
| Memory > 80% | 5 min sustained | Add pod |
| Response time > 3s | 3 min sustained | Add pod |
| Requests/sec > 100/pod | Immediate | Add pod |

### Manual Scaling Triggers
| Condition | Action | Lead Time |
|-----------|--------|-----------|
| Farmers > 7,000 | Upgrade RDS | 2 weeks |
| Daily collections > 10,000 | Add EKS node | 1 week |
| Peak users > 300 | Add ElastiCache shard | 1 week |

## 5. Monitoring & Alerts

### Capacity Alerts
| Alert | Threshold | Severity | Action |
|-------|-----------|----------|--------|
| CPU sustained high | >85% for 1h | Warning | Review scaling |
| Memory pressure | >90% | Critical | Immediate scaling |
| DB connections | >80 | Warning | Connection pooling |
| Storage growth | >80% | Warning | Expand volume |

## 6. Recommendations

### Immediate Actions
1. Configure auto-scaling policies (done)
2. Set up capacity alerts (done)
3. Enable cost allocation tags

### Quarterly Reviews
1. Review growth metrics vs projections
2. Assess infrastructure utilization
3. Update capacity plan as needed

### Annual Planning
1. Budget for infrastructure growth
2. Plan major upgrades in advance
3. Review architectural decisions
```

#### Day 745 Deliverables

- [x] Capacity planning document complete
- [x] Growth projections documented
- [x] Cost estimates provided
- [x] Scaling triggers defined
- [x] Monitoring alerts configured

---

## 4. Performance Optimization Summary

### 4.1 Optimizations Applied

| Category | Optimization | Impact |
|----------|--------------|--------|
| Database | Query indexing | 85% faster queries |
| Database | Materialized views | 90% faster reports |
| Caching | Session caching | 50% reduced DB load |
| Caching | Query result caching | 40% faster responses |
| Infrastructure | HPA configuration | Auto-scaling enabled |
| Infrastructure | Resource limits tuned | 20% better utilization |

### 4.2 Performance Metrics Comparison

| Metric | Before | After | Improvement |
|--------|--------|-------|-------------|
| Page Load (P95) | 4.2s | 2.1s | 50% |
| API Response (P95) | 800ms | 350ms | 56% |
| DB Query (P95) | 250ms | 85ms | 66% |
| Cache Hit Rate | 72% | 94% | 31% |

---

## 5. Dependencies & Handoffs

### 5.1 Prerequisites from MS-148

- [x] Knowledge transfer complete
- [x] Monitoring handover done

### 5.2 Handoffs to MS-150

- [ ] Performance baseline documented
- [ ] Optimizations applied
- [ ] Capacity plan delivered
- [ ] Monitoring verified

---

*Document Version: 1.0*
*Last Updated: 2026-02-05*
