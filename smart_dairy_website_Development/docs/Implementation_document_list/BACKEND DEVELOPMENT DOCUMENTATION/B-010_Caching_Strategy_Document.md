# SMART DAIRY LTD.
## CACHING STRATEGY DOCUMENT
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-010 |
| **Version** | 1.0 |
| **Date** | February 14, 2026 |
| **Author** | Tech Lead |
| **Owner** | DevOps Lead |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Cache Architecture](#2-cache-architecture)
3. [Caching Layers](#3-caching-layers)
4. [Cache Patterns](#4-cache-patterns)
5. [Data Classification](#5-data-classification)
6. [Invalidation Strategies](#6-invalidation-strategies)
7. [Session Management](#7-session-management)
8. [Performance Optimization](#8-performance-optimization)
9. [Monitoring & Metrics](#9-monitoring--metrics)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the comprehensive caching strategy for Smart Dairy's ERP system, covering multi-layer caching, invalidation strategies, and performance optimization techniques.

### 1.2 Scope

- Application-level caching (Redis)
- Database query caching
- HTTP response caching
- Session storage
- CDN caching strategy
- Cache invalidation patterns

### 1.3 Cache Technology Stack

| Layer | Technology | Purpose |
|-------|------------|---------|
| L1 - In-Memory | Python LRU Cache | Function-level caching |
| L2 - Distributed | Redis 7 Cluster | Application caching |
| L3 - HTTP | Nginx Cache | Response caching |
| L4 - CDN | CloudFlare/AWS CloudFront | Static asset caching |
| Session | Redis | User session storage |

---

## 2. CACHE ARCHITECTURE

### 2.1 Multi-Layer Cache Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        CACHING LAYER ARCHITECTURE                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  CLIENT REQUEST                                                             │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  L4: CDN (CloudFlare/AWS)                                           │   │
│  │  - Static assets: images, CSS, JS                                   │   │
│  │  - Cache TTL: 1 day - 1 year                                        │   │
│  │  - Edge locations: Global                                           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  L3: Nginx Reverse Proxy                                            │   │
│  │  - API responses: 5 minutes                                         │   │
│  │  - Static files: 1 hour                                             │   │
│  │  - Compression: gzip/brotli                                         │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  L2: Redis Cluster (Application Cache)                              │   │
│  │  - Query results: 10 minutes                                        │   │
│  │  - User sessions: 2 hours                                           │   │
│  │  - Computed data: 1 hour                                            │   │
│  │  - Rate limiting counters: sliding window                           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  L1: In-Memory Cache (Python LRU)                                   │   │
│  │  - Config data: 1000 entries                                        │   │
│  │  - Reference data: 500 entries                                      │   │
│  │  - Computed values: 200 entries                                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│       │                                                                     │
│       ▼                                                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  APPLICATION / DATABASE                                             │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Redis Cluster Configuration

```python
# cache_config.py
import redis.sentinel
from rediscluster import RedisCluster

# Production: Redis Cluster
REDIS_CLUSTER_CONFIG = {
    'startup_nodes': [
        {'host': 'redis-node-1', 'port': 6379},
        {'host': 'redis-node-2', 'port': 6379},
        {'host': 'redis-node-3', 'port': 6379},
    ],
    'decode_responses': True,
    'skip_full_coverage_check': True,
    'max_connections': 100,
    'socket_connect_timeout': 5,
    'socket_timeout': 5,
    'retry_on_timeout': True,
}

# Development: Single Redis instance
REDIS_SINGLE_CONFIG = {
    'host': 'localhost',
    'port': 6379,
    'db': 0,
    'decode_responses': True,
    'max_connections': 50,
}

# Database mapping for different cache types
REDIS_DATABASES = {
    'default': 0,      # General application cache
    'sessions': 1,     # User sessions
    'queries': 2,      # Query results
    'rate_limit': 3,   # Rate limiting
    'locks': 4,        # Distributed locks
    'pubsub': 5,       # Pub/Sub messages
    'temp': 6,         # Temporary data
}
```

---

## 3. CACHING LAYERS

### 3.1 L1: In-Memory LRU Cache

```python
# core/lru_cache.py
from functools import lru_cache
from functools import wraps
from datetime import datetime, timedelta
import threading

class TimedLRUCache:
    """LRU Cache with TTL support."""
    
    def __init__(self, maxsize=128, ttl=300):
        self.maxsize = maxsize
        self.ttl = ttl
        self.cache = {}
        self.timestamps = {}
        self.lock = threading.Lock()
    
    def __call__(self, func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            # Create cache key
            key = str(args) + str(sorted(kwargs.items()))
            
            with self.lock:
                now = datetime.now()
                
                # Check if cached and not expired
                if key in self.cache:
                    if now - self.timestamps[key] < timedelta(seconds=self.ttl):
                        return self.cache[key]
                    else:
                        # Expired, remove
                        del self.cache[key]
                        del self.timestamps[key]
                
                # Compute and cache
                result = func(*args, **kwargs)
                
                # Evict oldest if at capacity
                if len(self.cache) >= self.maxsize:
                    oldest_key = min(self.timestamps, key=self.timestamps.get)
                    del self.cache[oldest_key]
                    del self.timestamps[oldest_key]
                
                self.cache[key] = result
                self.timestamps[key] = now
                
                return result
        
        wrapper.cache_clear = self._clear
        return wrapper
    
    def _clear(self):
        with self.lock:
            self.cache.clear()
            self.timestamps.clear()


# Usage in Odoo models
from odoo import models, api

class ResPartner(models.Model):
    _inherit = 'res.partner'
    
    @TimedLRUCache(maxsize=1000, ttl=600)
    def get_customer_stats(self, customer_id):
        """Get cached customer statistics."""
        # Expensive computation
        return {
            'total_orders': self._count_orders(customer_id),
            'total_revenue': self._sum_revenue(customer_id),
            'last_order_date': self._get_last_order(customer_id),
        }
```

### 3.2 L2: Redis Application Cache

```python
# services/cache_service.py
import json
import pickle
from typing import Optional, Any, Union
from datetime import timedelta
import redis

class CacheService:
    """Redis-based cache service."""
    
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
        self.default_ttl = 300  # 5 minutes
    
    def _serialize(self, value: Any) -> bytes:
        """Serialize value for Redis storage."""
        if isinstance(value, (str, int, float, bool)):
            return json.dumps(value).encode()
        return pickle.dumps(value)
    
    def _deserialize(self, value: bytes) -> Any:
        """Deserialize value from Redis."""
        try:
            return json.loads(value.decode())
        except (json.JSONDecodeError, UnicodeDecodeError):
            return pickle.loads(value)
    
    def get(self, key: str) -> Optional[Any]:
        """Get value from cache."""
        value = self.redis.get(key)
        if value is None:
            return None
        return self._deserialize(value)
    
    def set(
        self,
        key: str,
        value: Any,
        ttl: Optional[int] = None,
        nx: bool = False  # Only set if not exists
    ) -> bool:
        """Set value in cache."""
        ttl = ttl or self.default_ttl
        serialized = self._serialize(value)
        
        if nx:
            return self.redis.set(key, serialized, nx=True, ex=ttl)
        return self.redis.set(key, serialized, ex=ttl)
    
    def get_or_set(
        self,
        key: str,
        factory,
        ttl: Optional[int] = None
    ) -> Any:
        """Get from cache or compute and store."""
        value = self.get(key)
        if value is None:
            value = factory()
            self.set(key, value, ttl)
        return value
    
    def delete(self, key: str) -> int:
        """Delete key from cache."""
        return self.redis.delete(key)
    
    def delete_pattern(self, pattern: str) -> int:
        """Delete keys matching pattern."""
        keys = self.redis.scan_iter(match=pattern)
        deleted = 0
        for key in keys:
            deleted += self.redis.delete(key)
        return deleted
    
    def increment(self, key: str, amount: int = 1) -> int:
        """Atomically increment counter."""
        return self.redis.incr(key, amount)
    
    def expire(self, key: str, ttl: int) -> bool:
        """Set expiration on key."""
        return self.redis.expire(key, ttl)
    
    def ttl(self, key: str) -> int:
        """Get remaining TTL for key."""
        return self.redis.ttl(key)
    
    # Hash operations for structured data
    def hget(self, key: str, field: str) -> Optional[Any]:
        """Get hash field."""
        value = self.redis.hget(key, field)
        return self._deserialize(value) if value else None
    
    def hset(self, key: str, field: str, value: Any) -> int:
        """Set hash field."""
        return self.redis.hset(key, field, self._serialize(value))
    
    def hgetall(self, key: str) -> dict:
        """Get all hash fields."""
        data = self.redis.hgetall(key)
        return {k.decode(): self._deserialize(v) for k, v in data.items()}
    
    # List operations for queues
    def lpush(self, key: str, value: Any) -> int:
        """Push to list head."""
        return self.redis.lpush(key, self._serialize(value))
    
    def rpop(self, key: str) -> Optional[Any]:
        """Pop from list tail."""
        value = self.redis.rpop(key)
        return self._deserialize(value) if value else None
    
    # Set operations for unique collections
    def sadd(self, key: str, value: Any) -> int:
        """Add to set."""
        return self.redis.sadd(key, self._serialize(value))
    
    def smembers(self, key: str) -> set:
        """Get all set members."""
        members = self.redis.smembers(key)
        return {self._deserialize(m) for m in members}


# Cache decorator
from functools import wraps

def cached(
    key_prefix: str,
    ttl: int = 300,
    cache_key_fn=None,
    skip_cache_fn=None
):
    """Decorator for caching function results."""
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            # Check if cache should be skipped
            if skip_cache_fn and skip_cache_fn(*args, **kwargs):
                return func(*args, **kwargs)
            
            # Generate cache key
            if cache_key_fn:
                cache_key = cache_key_fn(*args, **kwargs)
            else:
                cache_key = f"{key_prefix}:{args}:{sorted(kwargs.items())}"
            
            # Try to get from cache
            cache = get_cache_service()
            result = cache.get(cache_key)
            
            if result is not None:
                return result
            
            # Compute and cache
            result = func(*args, **kwargs)
            cache.set(cache_key, result, ttl)
            
            return result
        
        # Add cache invalidation helper
        wrapper.invalidate = lambda *args, **kwargs: get_cache_service().delete(
            cache_key_fn(*args, **kwargs) if cache_key_fn 
            else f"{key_prefix}:{args}:{sorted(kwargs.items())}"
        )
        
        return wrapper
    return decorator
```

### 3.3 L3: Nginx HTTP Cache

```nginx
# nginx.conf
proxy_cache_path /var/cache/nginx levels=1:2 keys_zone=api_cache:100m 
                 max_size=1g inactive=60m use_temp_path=off;

server {
    listen 80;
    server_name api.smartdairy.com;
    
    # API response caching
    location /api/v1/public/ {
        proxy_pass http://backend;
        
        # Cache configuration
        proxy_cache api_cache;
        proxy_cache_valid 200 5m;
        proxy_cache_valid 404 1m;
        proxy_cache_key "$scheme$request_method$host$request_uri";
        proxy_cache_bypass $http_cache_control;
        
        # Add cache status header
        add_header X-Cache-Status $upstream_cache_status;
        
        proxy_hide_header X-Accel-Expires;
        proxy_hide_header Cache-Control;
        proxy_hide_header Expires;
    }
    
    # Static files caching
    location /static/ {
        alias /var/www/static/;
        expires 1d;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    # Media files caching
    location /media/ {
        alias /var/www/media/;
        expires 7d;
        add_header Cache-Control "public, must-revalidate";
        access_log off;
    }
}
```

---

## 4. CACHE PATTERNS

### 4.1 Cache-Aside Pattern

```python
# patterns/cache_aside.py
class CacheAsideRepository:
    """Repository implementing cache-aside pattern."""
    
    def __init__(self, cache: CacheService, repository):
        self.cache = cache
        self.repository = repository
    
    def get(self, id: int) -> Optional[Any]:
        """Get entity with caching."""
        cache_key = f"{self.repository.model_name}:{id}"
        
        # Try cache first
        entity = self.cache.get(cache_key)
        if entity is not None:
            return entity
        
        # Load from database
        entity = self.repository.get(id)
        if entity:
            # Store in cache
            self.cache.set(cache_key, entity, ttl=600)
        
        return entity
    
    def update(self, id: int, data: dict) -> Any:
        """Update entity and invalidate cache."""
        # Update database
        entity = self.repository.update(id, data)
        
        # Invalidate cache
        cache_key = f"{self.repository.model_name}:{id}"
        self.cache.delete(cache_key)
        
        return entity
    
    def delete(self, id: int) -> bool:
        """Delete entity and invalidate cache."""
        # Delete from database
        result = self.repository.delete(id)
        
        # Invalidate cache
        cache_key = f"{self.repository.model_name}:{id}"
        self.cache.delete(cache_key)
        
        return result
```

### 4.2 Write-Through Pattern

```python
# patterns/write_through.py
class WriteThroughCache:
    """Cache implementing write-through pattern."""
    
    def __init__(self, cache: CacheService, repository):
        self.cache = cache
        self.repository = repository
    
    def create(self, data: dict) -> Any:
        """Create entity in both cache and database."""
        # Create in database first
        entity = self.repository.create(data)
        
        # Update cache
        cache_key = f"{self.repository.model_name}:{entity.id}"
        self.cache.set(cache_key, entity, ttl=600)
        
        return entity
    
    def update(self, id: int, data: dict) -> Any:
        """Update entity in both cache and database."""
        # Update database
        entity = self.repository.update(id, data)
        
        # Update cache
        cache_key = f"{self.repository.model_name}:{id}"
        self.cache.set(cache_key, entity, ttl=600)
        
        return entity
```

### 4.3 Read-Through Pattern

```python
# patterns/read_through.py
class ReadThroughCache:
    """Cache implementing read-through pattern."""
    
    def __init__(self, cache: CacheService, loader_fn):
        self.cache = cache
        self.loader_fn = loader_fn
    
    def get(self, key: str) -> Any:
        """Get value, loading from source if not cached."""
        value = self.cache.get(key)
        if value is None:
            # Load from source
            value = self.loader_fn(key)
            if value is not None:
                self.cache.set(key, value)
        return value
```

---

## 5. DATA CLASSIFICATION

### 5.1 Cache TTL Matrix

| Data Type | TTL | Invalidation | Storage |
|-----------|-----|--------------|---------|
| User Sessions | 2 hours | On logout | Redis DB 1 |
| User Profile | 30 minutes | On update | Redis DB 0 |
| Product Catalog | 1 hour | On change | Redis DB 0 |
| Product Details | 10 minutes | On update | Redis DB 0 |
| Inventory Levels | 5 minutes | On stock move | Redis DB 0 |
| Pricing | 15 minutes | On price change | Redis DB 0 |
| Customer Data | 20 minutes | On update | Redis DB 0 |
| Reports | 1 day | Manual | Redis DB 0 |
| API Rate Limits | Sliding window | Auto | Redis DB 3 |
| Query Results | 10 minutes | Time-based | Redis DB 2 |
| Configuration | 1 hour | On restart | L1 + Redis |
| Static Assets | 1 day | Version change | CDN |

### 5.2 Cache Key Naming Convention

```python
# Cache key patterns
CACHE_KEYS = {
    # User data
    'user_profile': 'user:{user_id}:profile',
    'user_permissions': 'user:{user_id}:permissions',
    'user_session': 'session:{session_id}',
    
    # Product data
    'product_detail': 'product:{product_id}',
    'product_list': 'products:list:{filters}:{pagination}',
    'product_price': 'product:{product_id}:price:{pricelist_id}',
    'product_stock': 'product:{product_id}:stock:{warehouse_id}',
    
    # Customer data
    'customer_profile': 'customer:{customer_id}',
    'customer_orders': 'customer:{customer_id}:orders:{limit}',
    
    # Farm data
    'animal_detail': 'animal:{animal_id}',
    'animal_milk_stats': 'animal:{animal_id}:milk_stats:{period}',
    'herd_summary': 'herd:{herd_id}:summary',
    
    # System data
    'config': 'config:{key}',
    'exchange_rate': 'exchange:{from_currency}:{to_currency}',
    'tax_rates': 'tax:{country_code}:{date}',
    
    # Query cache
    'query': 'query:{query_hash}',
    'report': 'report:{report_type}:{params_hash}',
}
```

---

## 6. INVALIDATION STRATEGIES

### 6.1 Time-Based Invalidation

```python
# Automatic TTL-based expiration (handled by Redis)
# Set appropriate TTL when storing

def cache_product(product_id: int, product_data: dict):
    """Cache product with appropriate TTL."""
    cache_key = f"product:{product_id}"
    
    # Product details change infrequently - longer TTL
    cache.set(cache_key, product_data, ttl=600)  # 10 minutes


def cache_inventory(product_id: int, stock_level: int):
    """Cache inventory with short TTL."""
    cache_key = f"product:{product_id}:stock"
    
    # Stock changes frequently - short TTL
    cache.set(cache_key, stock_level, ttl=60)  # 1 minute
```

### 6.2 Event-Based Invalidation

```python
# core/cache_invalidation.py
from odoo import models, api

class CacheInvalidationMixin(models.AbstractModel):
    """Mixin for automatic cache invalidation."""
    _name = 'cache.invalidation.mixin'
    
    def write(self, vals):
        """Override write to invalidate cache."""
        result = super().write(vals)
        self._invalidate_cache()
        return result
    
    def unlink(self):
        """Override unlink to invalidate cache."""
        self._invalidate_cache()
        return super().unlink()
    
    def _invalidate_cache(self):
        """Invalidate relevant cache entries."""
        cache = get_cache_service()
        
        # Invalidate specific entity
        cache_key = f"{self._name}:{self.id}"
        cache.delete(cache_key)
        
        # Invalidate list caches
        cache.delete_pattern(f"{self._name}:list:*")
        
        # Model-specific invalidation
        self._invalidate_related_cache()
    
    def _invalidate_related_cache(self):
        """Override to invalidate related caches."""
        pass


# Usage in models
class ProductProduct(models.Model):
    _inherit = 'product.product'
    _inherit = ['cache.invalidation.mixin']
    
    def _invalidate_related_cache(self):
        """Invalidate related caches on product change."""
        cache = get_cache_service()
        
        # Invalidate pricing caches
        cache.delete_pattern(f"product:{self.id}:price:*")
        
        # Invalidate stock caches
        cache.delete_pattern(f"product:{self.id}:stock:*")
```

### 6.3 Manual Invalidation API

```python
# api/cache_management.py
from fastapi import APIRouter, Depends

router = APIRouter()

@router.post("/cache/invalidate")
async def invalidate_cache(
    pattern: str,
    current_user: dict = Depends(get_admin_user)
):
    """Manually invalidate cache by pattern."""
    cache = get_cache_service()
    deleted = cache.delete_pattern(pattern)
    
    return {
        'success': True,
        'pattern': pattern,
        'keys_deleted': deleted
    }


@router.post("/cache/clear")
async def clear_cache(
    cache_type: str = 'all',
    current_user: dict = Depends(get_admin_user)
):
    """Clear cache by type."""
    cache = get_cache_service()
    
    if cache_type == 'all':
        cache.redis.flushdb()
    elif cache_type == 'query':
        cache.delete_pattern('query:*')
    elif cache_type == 'sessions':
        cache.redis.select(1)
        cache.redis.flushdb()
    
    return {
        'success': True,
        'cache_type': cache_type
    }


@router.get("/cache/stats")
async def cache_stats(
    current_user: dict = Depends(get_admin_user)
):
    """Get cache statistics."""
    cache = get_cache_service()
    
    info = cache.redis.info()
    
    return {
        'used_memory': info['used_memory_human'],
        'connected_clients': info['connected_clients'],
        'total_keys': cache.redis.dbsize(),
        'hit_rate': info.get('keyspace_hits', 0) / max(
            info.get('keyspace_hits', 0) + info.get('keyspace_misses', 1), 1
        ),
        'uptime_days': info['uptime_in_days']
    }
```

---

## 7. SESSION MANAGEMENT

### 7.1 Redis Session Store

```python
# services/session_service.py
import secrets
from datetime import datetime, timedelta
from typing import Optional, Dict, Any

class SessionService:
    """Redis-based session management."""
    
    SESSION_PREFIX = 'session:'
    DEFAULT_TTL = 7200  # 2 hours
    
    def __init__(self, cache: CacheService):
        self.cache = cache
    
    def create_session(
        self,
        user_id: int,
        user_data: Dict[str, Any],
        ttl: int = None
    ) -> str:
        """Create new session."""
        session_id = secrets.token_urlsafe(32)
        
        session_data = {
            'user_id': user_id,
            'created_at': datetime.utcnow().isoformat(),
            'last_accessed': datetime.utcnow().isoformat(),
            'data': user_data,
        }
        
        cache_key = f"{self.SESSION_PREFIX}{session_id}"
        self.cache.set(cache_key, session_data, ttl or self.DEFAULT_TTL)
        
        return session_id
    
    def get_session(self, session_id: str) -> Optional[Dict]:
        """Get session data."""
        cache_key = f"{self.SESSION_PREFIX}{session_id}"
        session = self.cache.get(cache_key)
        
        if session:
            # Update last accessed
            session['last_accessed'] = datetime.utcnow().isoformat()
            self.cache.set(cache_key, session, self.DEFAULT_TTL)
        
        return session
    
    def update_session(
        self,
        session_id: str,
        data: Dict[str, Any]
    ) -> bool:
        """Update session data."""
        cache_key = f"{self.SESSION_PREFIX}{session_id}"
        session = self.cache.get(cache_key)
        
        if session:
            session['data'].update(data)
            session['last_accessed'] = datetime.utcnow().isoformat()
            self.cache.set(cache_key, session, self.DEFAULT_TTL)
            return True
        
        return False
    
    def delete_session(self, session_id: str) -> bool:
        """Delete session (logout)."""
        cache_key = f"{self.SESSION_PREFIX}{session_id}"
        return self.cache.delete(cache_key) > 0
    
    def extend_session(self, session_id: str, ttl: int = None) -> bool:
        """Extend session TTL."""
        cache_key = f"{self.SESSION_PREFIX}{session_id}"
        return self.cache.expire(cache_key, ttl or self.DEFAULT_TTL)
```

---

## 8. PERFORMANCE OPTIMIZATION

### 8.1 Cache Warming

```python
# services/cache_warmer.py
class CacheWarmer:
    """Pre-populate cache with frequently accessed data."""
    
    WARM_ENTITIES = [
        {'model': 'product.product', 'limit': 1000},
        {'model': 'res.partner', 'domain': [('customer_rank', '>', 0)], 'limit': 500},
        {'model': 'farm.animal', 'limit': 500},
    ]
    
    def warm_cache(self):
        """Warm cache on startup."""
        for config in self.WARM_ENTITIES:
            self._warm_entity(config)
    
    def _warm_entity(self, config: dict):
        """Warm cache for specific entity."""
        model = config['model']
        domain = config.get('domain', [])
        limit = config.get('limit', 100)
        
        # Fetch entities
        entities = self.env[model].search(domain, limit=limit)
        
        # Preload into cache
        for entity in entities:
            cache_key = f"{model}:{entity.id}"
            get_cache_service().set(cache_key, entity.read()[0], ttl=3600)
```

### 8.2 Batch Operations

```python
def cache_products_batch(product_ids: list) -> dict:
    """Cache multiple products efficiently."""
    cache = get_cache_service()
    results = {}
    
    # Pipeline get operations
    pipe = cache.redis.pipeline()
    keys = [f"product:{pid}" for pid in product_ids]
    for key in keys:
        pipe.get(key)
    
    cached_values = pipe.execute()
    
    # Find missing
    missing_ids = []
    for pid, value in zip(product_ids, cached_values):
        if value:
            results[pid] = pickle.loads(value)
        else:
            missing_ids.append(pid)
    
    # Batch fetch missing
    if missing_ids:
        products = fetch_products_from_db(missing_ids)
        
        # Pipeline set operations
        pipe = cache.redis.pipeline()
        for product in products:
            key = f"product:{product['id']}"
            pipe.set(key, pickle.dumps(product), ex=600)
            results[product['id']] = product
        
        pipe.execute()
    
    return results
```

---

## 9. MONITORING & METRICS

### 9.1 Cache Metrics

```python
# monitoring/cache_metrics.py
from prometheus_client import Counter, Histogram, Gauge

# Cache operations
cache_hits = Counter(
    'cache_hits_total',
    'Cache hits',
    ['cache_name', 'key_pattern']
)

cache_misses = Counter(
    'cache_misses_total',
    'Cache misses',
    ['cache_name', 'key_pattern']
)

cache_operations = Counter(
    'cache_operations_total',
    'Cache operations',
    ['cache_name', 'operation']
)

cache_latency = Histogram(
    'cache_operation_duration_seconds',
    'Cache operation latency',
    ['cache_name', 'operation'],
    buckets=[.001, .0025, .005, .01, .025, .05, .1]
)

cache_size = Gauge(
    'cache_entries',
    'Number of cache entries',
    ['cache_name']
)
```

---

## 10. APPENDICES

### Appendix A: Redis Commands Reference

| Command | Use Case |
|---------|----------|
| `GET/SET` | Simple key-value caching |
| `HGET/HSET` | Structured data caching |
| `EXPIRE/TTL` | Time-based invalidation |
| `INCR/DECR` | Counters (rate limiting) |
| `SCAN` | Pattern-based key finding |
| `PIPELINE` | Batch operations |
| `PUB/SUB` | Cache invalidation events |

### Appendix B: Cache Configuration Summary

| Component | Max Memory | Eviction Policy | Persistence |
|-----------|------------|-----------------|-------------|
| Redis Cache | 4GB | allkeys-lru | AOF every sec |
| Redis Sessions | 1GB | volatile-ttl | AOF every sec |
| Nginx Cache | 10GB | LRU | None |
| CDN | Unlimited | TTL-based | Global |

---

**END OF CACHING STRATEGY DOCUMENT**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 14, 2026 | Tech Lead | Initial version |
