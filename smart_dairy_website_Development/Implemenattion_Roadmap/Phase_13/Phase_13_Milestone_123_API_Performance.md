# Milestone 123: API Performance Optimization

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 123 of 150 (3 of 10 in Phase 13)                                       |
| **Title**        | API Performance Optimization                                           |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 611-615 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Optimize all API endpoints to achieve <200ms response time (P95) through response compression, payload optimization, batching, pagination improvements, and API gateway optimization.

### 1.2 Objectives

1. Implement response compression (gzip, brotli) for all API responses
2. Optimize API payload structures to reduce transfer size by 40%
3. Implement request batching for bulk operations
4. Optimize pagination with cursor-based approach for large datasets
5. Configure API rate limiting with intelligent throttling
6. Implement response caching headers (ETag, Cache-Control)
7. Optimize Odoo JSON-RPC endpoints
8. Achieve <200ms P95 response time for all critical endpoints

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Response Compression Module       | Dev 1  | Python middleware | 611     |
| Payload Optimization Guide        | Dev 1  | Markdown + Code   | 611     |
| Request Batching API              | Dev 1  | REST endpoints    | 612     |
| Cursor-based Pagination           | Dev 1  | Python module     | 612     |
| Rate Limiting Configuration       | Dev 2  | Nginx/Config      | 613     |
| Cache Headers Implementation      | Dev 2  | Python middleware | 613     |
| GraphQL Optimization (optional)   | Dev 1  | Python module     | 614     |
| API Performance Test Suite        | Dev 2  | k6 scripts        | 614     |
| API Documentation Update          | Dev 3  | OpenAPI/Swagger   | 615     |
| API Optimization Report           | All    | Markdown/PDF      | 615     |

### 1.4 Success Criteria

- [ ] 95% of API requests complete in <200ms
- [ ] Response payload size reduced by 40%+
- [ ] Compression ratio >70% for JSON responses
- [ ] Batch API supports up to 100 operations per request
- [ ] Rate limiting protecting against abuse
- [ ] Cache hit rate >30% for GET requests
- [ ] Zero performance regression on existing endpoints

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-PERF-001 | RFP    | Page load time <2 seconds                | 611-615 | API optimization            |
| SRS-PERF-001 | SRS    | API response time <200ms (P95)           | 611-615 | All tasks                   |
| BRD-PERF-001 | BRD    | Real-time dashboard <3s refresh          | 611-612 | Payload optimization        |
| IMPL-API-01  | Guide  | Response compression standards           | 611     | Compression module          |
| IMPL-API-02  | Guide  | API batching for mobile apps             | 612     | Batch API                   |

---

## 3. Day-by-Day Breakdown

### Day 611 - Response Compression & Payload Optimization

**Objective:** Implement response compression and optimize API payload structures.

#### Dev 1 - Backend Lead (8h)

**Task 1: Response Compression Middleware (4h)**

```python
# odoo/addons/smart_dairy_api/controllers/compression.py
import gzip
import brotli
import json
from functools import wraps
from odoo import http
from odoo.http import request, Response
import logging

_logger = logging.getLogger(__name__)

class CompressionMiddleware:
    """Middleware for API response compression"""

    COMPRESSIBLE_TYPES = [
        'application/json',
        'application/xml',
        'text/html',
        'text/plain',
        'text/css',
        'application/javascript'
    ]

    MIN_COMPRESS_SIZE = 1024  # Don't compress responses < 1KB

    @classmethod
    def compress_response(cls, response_data: bytes, accept_encoding: str) -> tuple:
        """Compress response data based on Accept-Encoding header"""
        if len(response_data) < cls.MIN_COMPRESS_SIZE:
            return response_data, None

        # Prefer brotli over gzip
        if 'br' in accept_encoding:
            compressed = brotli.compress(response_data, quality=4)
            return compressed, 'br'

        if 'gzip' in accept_encoding:
            compressed = gzip.compress(response_data, compresslevel=6)
            return compressed, 'gzip'

        return response_data, None


def compressed_json_response(func):
    """Decorator for compressed JSON API responses"""
    @wraps(func)
    def wrapper(*args, **kwargs):
        result = func(*args, **kwargs)

        if isinstance(result, dict):
            json_data = json.dumps(result, separators=(',', ':'))  # Minified
            response_bytes = json_data.encode('utf-8')

            accept_encoding = request.httprequest.headers.get('Accept-Encoding', '')
            compressed_data, encoding = CompressionMiddleware.compress_response(
                response_bytes, accept_encoding
            )

            headers = {
                'Content-Type': 'application/json',
                'Vary': 'Accept-Encoding'
            }

            if encoding:
                headers['Content-Encoding'] = encoding
                _logger.debug(
                    f"Compressed response: {len(response_bytes)} -> {len(compressed_data)} "
                    f"({100 - len(compressed_data) * 100 // len(response_bytes)}% reduction)"
                )

            return Response(
                compressed_data,
                status=200,
                headers=headers
            )

        return result

    return wrapper
```

**Task 2: Payload Optimization Utilities (4h)**

```python
# odoo/addons/smart_dairy_api/utils/payload_optimizer.py
from typing import Dict, List, Any, Optional
from datetime import datetime, date
from decimal import Decimal
import re

class PayloadOptimizer:
    """Optimize API response payloads for minimal size"""

    # Fields to always exclude from responses
    EXCLUDED_FIELDS = {
        'create_uid', 'write_uid', '__last_update',
        'message_ids', 'activity_ids', 'message_follower_ids'
    }

    # Field name shortening map
    FIELD_SHORTCUTS = {
        'display_name': 'dn',
        'create_date': 'cd',
        'write_date': 'wd',
        'company_id': 'cid',
        'currency_id': 'cur',
        'partner_id': 'pid',
        'product_id': 'prd',
        'quantity': 'qty',
        'unit_price': 'up',
        'total_amount': 'amt',
        'description': 'desc',
    }

    @classmethod
    def optimize_record(cls, record: Dict, fields: Optional[List[str]] = None,
                        shorten_fields: bool = False) -> Dict:
        """Optimize a single record"""
        optimized = {}

        for key, value in record.items():
            # Skip excluded fields
            if key in cls.EXCLUDED_FIELDS:
                continue

            # Skip if fields filter specified and not in list
            if fields and key not in fields:
                continue

            # Optimize the value
            optimized_value = cls._optimize_value(value)

            # Optionally shorten field names
            if shorten_fields and key in cls.FIELD_SHORTCUTS:
                key = cls.FIELD_SHORTCUTS[key]

            optimized[key] = optimized_value

        return optimized

    @classmethod
    def _optimize_value(cls, value: Any) -> Any:
        """Optimize individual values"""
        if value is None:
            return None

        if isinstance(value, (datetime, date)):
            return value.isoformat()

        if isinstance(value, Decimal):
            # Remove trailing zeros
            return float(value.normalize())

        if isinstance(value, float):
            # Round to reasonable precision
            return round(value, 4)

        if isinstance(value, dict):
            return cls.optimize_record(value)

        if isinstance(value, (list, tuple)):
            return [cls._optimize_value(item) for item in value]

        if isinstance(value, str):
            # Trim whitespace
            return value.strip()

        return value

    @classmethod
    def create_summary_response(cls, records: List[Dict],
                                 summary_fields: List[str]) -> Dict:
        """Create optimized summary response with metadata"""
        return {
            'count': len(records),
            'items': [
                {k: v for k, v in r.items() if k in summary_fields}
                for r in records
            ]
        }

    @classmethod
    def paginated_response(cls, records: List[Dict], total: int,
                           page: int, page_size: int,
                           fields: Optional[List[str]] = None) -> Dict:
        """Create optimized paginated response"""
        return {
            'meta': {
                'total': total,
                'page': page,
                'size': page_size,
                'pages': (total + page_size - 1) // page_size
            },
            'data': [
                cls.optimize_record(r, fields) for r in records
            ]
        }
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: Nginx Compression Configuration (4h)**

```nginx
# /etc/nginx/conf.d/compression.conf

# Enable gzip compression
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_buffers 16 8k;
gzip_http_version 1.1;
gzip_min_length 1024;
gzip_types
    application/json
    application/javascript
    application/xml
    application/xml+rss
    text/css
    text/javascript
    text/plain
    text/xml;

# Enable brotli compression (requires ngx_brotli module)
brotli on;
brotli_comp_level 4;
brotli_static on;
brotli_types
    application/json
    application/javascript
    application/xml
    text/css
    text/javascript
    text/plain;
```

**Task 2: API Response Monitoring (4h)**

```python
# backend/src/monitoring/api_monitor.py
from prometheus_client import Histogram, Counter, Gauge
import time
from functools import wraps

API_RESPONSE_SIZE = Histogram(
    'api_response_size_bytes',
    'API response size in bytes',
    ['endpoint', 'method', 'compressed'],
    buckets=[100, 500, 1000, 5000, 10000, 50000, 100000, 500000]
)

API_COMPRESSION_RATIO = Histogram(
    'api_compression_ratio',
    'Compression ratio (original/compressed)',
    ['encoding'],
    buckets=[1.0, 1.5, 2.0, 3.0, 5.0, 10.0, 20.0]
)

def track_api_response(endpoint: str):
    """Decorator to track API response metrics"""
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            result = func(*args, **kwargs)

            # Track response size
            if hasattr(result, 'data'):
                original_size = len(result.data)
                compressed = result.headers.get('Content-Encoding')

                API_RESPONSE_SIZE.labels(
                    endpoint=endpoint,
                    method='GET',
                    compressed=compressed or 'none'
                ).observe(original_size)

            return result
        return wrapper
    return decorator
```

#### Dev 3 - Frontend Lead (8h)

**Task 1: Frontend API Client Optimization (4h)**

```typescript
// frontend/src/api/optimizedClient.ts
import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';

interface ApiClientConfig {
  baseURL: string;
  enableCompression: boolean;
  enableBatching: boolean;
  maxBatchSize: number;
  batchDelayMs: number;
}

class OptimizedApiClient {
  private client: AxiosInstance;
  private batchQueue: Map<string, Array<{
    resolve: Function;
    reject: Function;
    request: AxiosRequestConfig;
  }>> = new Map();
  private batchTimer: NodeJS.Timeout | null = null;
  private config: ApiClientConfig;

  constructor(config: ApiClientConfig) {
    this.config = config;
    this.client = axios.create({
      baseURL: config.baseURL,
      headers: {
        'Accept-Encoding': 'br, gzip',
        'Content-Type': 'application/json'
      }
    });

    // Response decompression is handled automatically by browser
    this.setupInterceptors();
  }

  private setupInterceptors(): void {
    // Request interceptor for compression
    this.client.interceptors.request.use((config) => {
      // Compress request body if large
      if (config.data && JSON.stringify(config.data).length > 1024) {
        config.headers['Content-Encoding'] = 'gzip';
        // Note: Actual compression would be done here or by a service worker
      }
      return config;
    });

    // Response interceptor for metrics
    this.client.interceptors.response.use((response) => {
      const contentEncoding = response.headers['content-encoding'];
      const contentLength = response.headers['content-length'];

      if (process.env.NODE_ENV === 'development') {
        console.log(`[API] ${response.config.url}: ${contentLength} bytes (${contentEncoding || 'uncompressed'})`);
      }

      return response;
    });
  }

  async get<T>(url: string, config?: AxiosRequestConfig): Promise<T> {
    const response = await this.client.get<T>(url, config);
    return response.data;
  }

  async post<T>(url: string, data?: any, config?: AxiosRequestConfig): Promise<T> {
    const response = await this.client.post<T>(url, data, config);
    return response.data;
  }

  // Batched request method
  async batchRequest<T>(requests: Array<{
    method: 'GET' | 'POST' | 'PUT' | 'DELETE';
    url: string;
    data?: any;
  }>): Promise<T[]> {
    const response = await this.client.post<{ results: T[] }>('/api/v1/batch', {
      requests
    });
    return response.data.results;
  }

  // Auto-batching for individual requests
  queueBatchedGet<T>(url: string): Promise<T> {
    return new Promise((resolve, reject) => {
      const batchKey = 'GET';

      if (!this.batchQueue.has(batchKey)) {
        this.batchQueue.set(batchKey, []);
      }

      this.batchQueue.get(batchKey)!.push({
        resolve,
        reject,
        request: { method: 'GET', url }
      });

      // Schedule batch execution
      if (!this.batchTimer) {
        this.batchTimer = setTimeout(() => this.executeBatch(), this.config.batchDelayMs);
      }

      // Execute immediately if batch is full
      if (this.batchQueue.get(batchKey)!.length >= this.config.maxBatchSize) {
        this.executeBatch();
      }
    });
  }

  private async executeBatch(): Promise<void> {
    if (this.batchTimer) {
      clearTimeout(this.batchTimer);
      this.batchTimer = null;
    }

    for (const [batchKey, items] of this.batchQueue.entries()) {
      if (items.length === 0) continue;

      const requests = items.map(item => item.request);

      try {
        const results = await this.batchRequest(requests);
        items.forEach((item, index) => {
          item.resolve(results[index]);
        });
      } catch (error) {
        items.forEach(item => item.reject(error));
      }
    }

    this.batchQueue.clear();
  }
}

export const apiClient = new OptimizedApiClient({
  baseURL: process.env.REACT_APP_API_URL || '/api',
  enableCompression: true,
  enableBatching: true,
  maxBatchSize: 20,
  batchDelayMs: 50
});

export default apiClient;
```

**Task 2: Response Handling Optimization (4h)**

```typescript
// frontend/src/api/responseOptimizer.ts

interface OptimizedResponse<T> {
  data: T;
  meta?: {
    total: number;
    page: number;
    size: number;
    pages: number;
  };
}

// Field expansion map (reverse of server-side shortcuts)
const FIELD_EXPANSIONS: Record<string, string> = {
  'dn': 'display_name',
  'cd': 'create_date',
  'wd': 'write_date',
  'cid': 'company_id',
  'cur': 'currency_id',
  'pid': 'partner_id',
  'prd': 'product_id',
  'qty': 'quantity',
  'up': 'unit_price',
  'amt': 'total_amount',
  'desc': 'description',
};

export function expandFieldNames<T extends Record<string, any>>(data: T): T {
  if (Array.isArray(data)) {
    return data.map(item => expandFieldNames(item)) as unknown as T;
  }

  if (typeof data !== 'object' || data === null) {
    return data;
  }

  const expanded: Record<string, any> = {};
  for (const [key, value] of Object.entries(data)) {
    const expandedKey = FIELD_EXPANSIONS[key] || key;
    expanded[expandedKey] = expandFieldNames(value);
  }

  return expanded as T;
}

// Efficient data transformation
export function transformApiResponse<T>(
  response: OptimizedResponse<T>,
  expand: boolean = true
): T {
  if (expand) {
    return expandFieldNames(response.data);
  }
  return response.data;
}
```

---

### Day 612 - Request Batching & Pagination

**Objective:** Implement request batching API and cursor-based pagination.

#### Dev 1 - Backend Lead (8h)

**Task 1: Batch API Controller (4h)**

```python
# odoo/addons/smart_dairy_api/controllers/batch.py
from odoo import http
from odoo.http import request, Response
import json
import concurrent.futures
import logging

_logger = logging.getLogger(__name__)

class BatchAPIController(http.Controller):
    """Handle batched API requests"""

    MAX_BATCH_SIZE = 100
    MAX_PARALLEL = 10

    @http.route('/api/v1/batch', type='json', auth='user', methods=['POST'])
    def batch_request(self, requests, parallel=False, **kwargs):
        """
        Execute multiple API requests in a single HTTP call

        Args:
            requests: List of {method, url, data} objects
            parallel: Whether to execute requests in parallel

        Returns:
            {results: [...], errors: [...]}
        """
        if len(requests) > self.MAX_BATCH_SIZE:
            return {
                'error': f'Maximum batch size is {self.MAX_BATCH_SIZE}',
                'code': 'BATCH_SIZE_EXCEEDED'
            }

        results = []
        errors = []

        if parallel:
            results, errors = self._execute_parallel(requests)
        else:
            results, errors = self._execute_sequential(requests)

        return {
            'results': results,
            'errors': errors,
            'meta': {
                'total': len(requests),
                'successful': len(results),
                'failed': len(errors)
            }
        }

    def _execute_sequential(self, requests):
        """Execute requests sequentially"""
        results = []
        errors = []

        for i, req in enumerate(requests):
            try:
                result = self._execute_single(req)
                results.append({'index': i, 'data': result})
            except Exception as e:
                errors.append({
                    'index': i,
                    'error': str(e),
                    'url': req.get('url')
                })

        return results, errors

    def _execute_parallel(self, requests):
        """Execute requests in parallel with thread pool"""
        results = []
        errors = []

        with concurrent.futures.ThreadPoolExecutor(max_workers=self.MAX_PARALLEL) as executor:
            future_to_index = {
                executor.submit(self._execute_single, req): i
                for i, req in enumerate(requests)
            }

            for future in concurrent.futures.as_completed(future_to_index):
                index = future_to_index[future]
                try:
                    result = future.result()
                    results.append({'index': index, 'data': result})
                except Exception as e:
                    errors.append({
                        'index': index,
                        'error': str(e)
                    })

        # Sort by original index
        results.sort(key=lambda x: x['index'])
        return results, errors

    def _execute_single(self, req):
        """Execute a single API request"""
        method = req.get('method', 'GET').upper()
        url = req.get('url', '')
        data = req.get('data', {})

        # Route to appropriate handler
        if url.startswith('/api/v1/products'):
            return self._handle_products(method, url, data)
        elif url.startswith('/api/v1/orders'):
            return self._handle_orders(method, url, data)
        elif url.startswith('/api/v1/farm'):
            return self._handle_farm(method, url, data)
        else:
            raise ValueError(f'Unknown API endpoint: {url}')

    def _handle_products(self, method, url, data):
        """Handle product API requests"""
        if method == 'GET':
            # Parse URL to get product ID or list
            if '/api/v1/products/' in url:
                product_id = int(url.split('/')[-1])
                product = request.env['product.product'].browse(product_id)
                return product.read(['name', 'list_price', 'qty_available'])[0]
            else:
                products = request.env['product.product'].search_read(
                    [('sale_ok', '=', True)],
                    ['name', 'list_price'],
                    limit=data.get('limit', 20)
                )
                return products
        return {}

    def _handle_orders(self, method, url, data):
        """Handle order API requests"""
        # Implementation similar to products
        return {}

    def _handle_farm(self, method, url, data):
        """Handle farm API requests"""
        # Implementation similar to products
        return {}
```

**Task 2: Cursor-Based Pagination (4h)**

```python
# odoo/addons/smart_dairy_api/utils/pagination.py
from odoo import api
from typing import Dict, List, Any, Optional, Tuple
import base64
import json
import hashlib

class CursorPaginator:
    """Cursor-based pagination for efficient large dataset traversal"""

    def __init__(self, model, order_field='id', order_dir='ASC'):
        self.model = model
        self.order_field = order_field
        self.order_dir = order_dir

    def encode_cursor(self, record: Dict) -> str:
        """Encode cursor from record data"""
        cursor_data = {
            'f': self.order_field,
            'v': record.get(self.order_field),
            'd': self.order_dir
        }
        json_str = json.dumps(cursor_data, separators=(',', ':'))
        return base64.urlsafe_b64encode(json_str.encode()).decode()

    def decode_cursor(self, cursor: str) -> Dict:
        """Decode cursor to get pagination position"""
        try:
            json_str = base64.urlsafe_b64decode(cursor.encode()).decode()
            return json.loads(json_str)
        except Exception:
            return {}

    def paginate(self, domain: List, fields: List[str], limit: int = 20,
                 cursor: Optional[str] = None) -> Dict[str, Any]:
        """
        Execute cursor-based pagination query

        Returns:
            {
                'items': [...],
                'next_cursor': 'abc...',
                'has_more': True/False
            }
        """
        # Build domain with cursor
        query_domain = list(domain)
        if cursor:
            cursor_data = self.decode_cursor(cursor)
            if cursor_data:
                op = '>' if self.order_dir == 'ASC' else '<'
                query_domain.append((cursor_data['f'], op, cursor_data['v']))

        # Fetch one extra to check if there are more
        order = f"{self.order_field} {self.order_dir}"
        records = self.model.search_read(
            query_domain,
            fields + [self.order_field],
            limit=limit + 1,
            order=order
        )

        has_more = len(records) > limit
        if has_more:
            records = records[:limit]

        # Generate next cursor
        next_cursor = None
        if has_more and records:
            next_cursor = self.encode_cursor(records[-1])

        return {
            'items': records,
            'next_cursor': next_cursor,
            'has_more': has_more
        }


class KeysetPaginator:
    """Keyset pagination for composite ordering"""

    @staticmethod
    def paginate_by_keyset(model, domain: List, fields: List[str],
                          limit: int = 20, after_id: Optional[int] = None,
                          after_value: Optional[Any] = None,
                          order_field: str = 'create_date') -> Dict:
        """
        Keyset pagination using (order_field, id) as composite key
        """
        query_domain = list(domain)

        if after_id and after_value:
            # WHERE (order_field, id) > (after_value, after_id)
            query_domain.append('|')
            query_domain.append((order_field, '>', after_value))
            query_domain.append('&')
            query_domain.append((order_field, '=', after_value))
            query_domain.append(('id', '>', after_id))

        records = model.search_read(
            query_domain,
            fields + [order_field, 'id'],
            limit=limit + 1,
            order=f'{order_field} ASC, id ASC'
        )

        has_more = len(records) > limit
        if has_more:
            records = records[:limit]

        last_record = records[-1] if records else None

        return {
            'items': records,
            'has_more': has_more,
            'pagination': {
                'after_id': last_record['id'] if last_record else None,
                'after_value': last_record.get(order_field) if last_record else None
            }
        }
```

#### Dev 2 & Dev 3 - Supporting Tasks (8h each)

- Dev 2: Rate limiting configuration, API gateway setup
- Dev 3: Frontend pagination hooks, infinite scroll implementation

---

### Day 613-614 - Rate Limiting & Cache Headers

#### Dev 2 - Full-Stack Developer (Lead)

**Task: Rate Limiting & Cache Headers (Day 613, 8h)**

```python
# odoo/addons/smart_dairy_api/controllers/rate_limiter.py
import time
import redis
from functools import wraps
from odoo.http import request, Response
import json

redis_client = redis.Redis(host='localhost', port=6379, db=1)

class RateLimiter:
    """Token bucket rate limiter using Redis"""

    def __init__(self, requests_per_minute: int = 60, burst: int = 10):
        self.rate = requests_per_minute / 60.0  # requests per second
        self.burst = burst

    def is_allowed(self, key: str) -> tuple:
        """Check if request is allowed under rate limit"""
        now = time.time()
        pipe = redis_client.pipeline()

        # Get current bucket state
        bucket_key = f"rate_limit:{key}"
        pipe.hgetall(bucket_key)
        pipe.execute()

        bucket = redis_client.hgetall(bucket_key)

        if not bucket:
            # Initialize bucket
            redis_client.hmset(bucket_key, {
                'tokens': self.burst,
                'last_update': now
            })
            redis_client.expire(bucket_key, 120)
            return True, self.burst - 1, 0

        tokens = float(bucket.get(b'tokens', self.burst))
        last_update = float(bucket.get(b'last_update', now))

        # Refill tokens
        elapsed = now - last_update
        tokens = min(self.burst, tokens + elapsed * self.rate)

        if tokens >= 1:
            # Allow request
            redis_client.hmset(bucket_key, {
                'tokens': tokens - 1,
                'last_update': now
            })
            return True, int(tokens - 1), 0
        else:
            # Reject request
            retry_after = (1 - tokens) / self.rate
            return False, 0, int(retry_after)


def rate_limited(requests_per_minute: int = 60, burst: int = 10, key_func=None):
    """Decorator for rate-limited endpoints"""
    limiter = RateLimiter(requests_per_minute, burst)

    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            # Get rate limit key
            if key_func:
                key = key_func()
            else:
                key = f"{request.env.user.id}:{request.httprequest.path}"

            allowed, remaining, retry_after = limiter.is_allowed(key)

            if not allowed:
                return Response(
                    json.dumps({
                        'error': 'Rate limit exceeded',
                        'retry_after': retry_after
                    }),
                    status=429,
                    headers={
                        'Content-Type': 'application/json',
                        'Retry-After': str(retry_after),
                        'X-RateLimit-Remaining': '0'
                    }
                )

            response = func(*args, **kwargs)

            # Add rate limit headers
            if isinstance(response, Response):
                response.headers['X-RateLimit-Remaining'] = str(remaining)
                response.headers['X-RateLimit-Limit'] = str(requests_per_minute)

            return response

        return wrapper
    return decorator


# Cache control decorator
def cached_response(max_age: int = 300, private: bool = False,
                    etag_func=None):
    """Decorator to add cache control headers"""
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            # Check If-None-Match for ETag
            if etag_func:
                current_etag = etag_func(*args, **kwargs)
                if_none_match = request.httprequest.headers.get('If-None-Match')

                if if_none_match and if_none_match == current_etag:
                    return Response(status=304)

            result = func(*args, **kwargs)

            if isinstance(result, Response):
                cache_control = f"{'private' if private else 'public'}, max-age={max_age}"
                result.headers['Cache-Control'] = cache_control

                if etag_func:
                    result.headers['ETag'] = current_etag

            return result

        return wrapper
    return decorator
```

---

### Day 615 - Documentation & Validation

**Objective:** Complete API documentation and validate all optimizations.

#### All Developers

- Update OpenAPI/Swagger documentation
- Run performance benchmarks
- Generate API optimization report
- Validate all success criteria

---

## 4. Technical Specifications

### 4.1 Compression Settings

| Format | Level | Min Size | Use Case |
|--------|-------|----------|----------|
| Brotli | 4 | 1KB | Preferred for browsers |
| Gzip | 6 | 1KB | Fallback compression |

### 4.2 Rate Limits

| Endpoint Type | Requests/min | Burst |
|---------------|--------------|-------|
| Read (GET) | 120 | 20 |
| Write (POST) | 60 | 10 |
| Batch | 30 | 5 |
| Search | 60 | 10 |

---

## 5. Testing & Validation

- [ ] Compression working (verify Content-Encoding headers)
- [ ] Response size reduced by 40%+
- [ ] Batch API handling 100 requests
- [ ] Cursor pagination working correctly
- [ ] Rate limiting protecting endpoints
- [ ] Cache headers present and correct
- [ ] P95 response time <200ms

---

## 6. Risk & Mitigation

| Risk | Mitigation |
|------|------------|
| Compression CPU overhead | Use appropriate compression levels |
| Batch timeout | Implement request timeout per item |
| Rate limit false positives | Implement bypass for admin users |

---

## 7. Dependencies & Handoffs

**Handoffs to Milestone 124:**
- Optimized API endpoints ready
- Compression middleware available for frontend
- Rate limiting infrastructure ready

---

**End of Milestone 123 Document**
