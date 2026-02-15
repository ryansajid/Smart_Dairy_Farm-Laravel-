# Document E-015: API Rate Limiting & Throttling Guide

## Smart Dairy Ltd. - Smart Web Portal System

---

**Document Control**

| Field | Value |
|-------|-------|
| **Document ID** | E-015 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Approved |
| **Classification** | Internal Use |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Rate Limiting Strategies](#2-rate-limiting-strategies)
3. [Redis-Based Implementation](#3-redis-based-implementation)
4. [Per-Endpoint Limits](#4-per-endpoint-limits)
5. [Per-User Limits](#5-per-user-limits)
6. [Response Headers](#6-response-headers)
7. [Throttling Implementation](#7-throttling-implementation)
8. [Burst Handling](#8-burst-handling)
9. [Whitelisting](#9-whitelisting)
10. [Rate Limit Tiers](#10-rate-limit-tiers)
11. [Monitoring & Alerting](#11-monitoring--alerting)
12. [Client Best Practices](#12-client-best-practices)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the API rate limiting and throttling policies for the Smart Dairy Web Portal System. Rate limiting is a critical component of our API infrastructure that ensures system stability, security, and fair resource allocation.

### 1.2 Objectives

- **DDoS Protection**: Prevent distributed denial-of-service attacks by limiting request rates
- **Fair Usage**: Ensure equitable access to API resources among all consumers
- **System Stability**: Protect backend services from being overwhelmed by excessive traffic
- **Cost Control**: Manage infrastructure costs by preventing resource abuse
- **Quality of Service**: Maintain consistent API performance for legitimate users

### 1.3 Scope

This guide applies to:
- All external API endpoints
- Internal microservice communications
- WebSocket connections
- File upload/download operations
- Authentication and authorization endpoints

### 1.4 Definitions

| Term | Definition |
|------|------------|
| **Rate Limit** | Maximum number of requests allowed within a specific time window |
| **Throttling** | Process of limiting request rate by delaying or rejecting requests |
| **Burst** | Short period of high request rate allowed within limits |
| **Quota** | Total allowed requests for a longer period (daily/monthly) |
| **429 Error** | HTTP status code indicating "Too Many Requests" |

---

## 2. Rate Limiting Strategies

### 2.1 Fixed Window Counter

The simplest approach where the time window resets at fixed intervals.

```
Time Window: 1 minute
Limit: 100 requests

12:00:00 ━━━━━━━━━━━━━━━━━━━ 12:01:00 ━━━━━━━━━━━━━━━━━━━ 12:02:00
   │                            │                            │
   └─ 100 requests allowed ───┬┘── 100 requests allowed ───┬┘
                              │                            │
                        Counter resets               Counter resets
```

**Pros:** Simple to implement, memory efficient
**Cons:** Boundary effect - burst of 200 requests at window edge possible

### 2.2 Sliding Window Log

Maintains a log of request timestamps and counts only requests within the current window.

```
Current Time: 12:00:45
Window: 60 seconds

Requests: [11:59:50, 11:59:55, 12:00:10, 12:00:30, 12:00:40]
            ✗ (expired)  ✗ (expired)  ✓           ✓           ✓
            
Valid requests in window: 3
Remaining: 97
```

**Pros:** Accurate, no boundary effect
**Cons:** Higher memory usage, O(n) lookup time

### 2.3 Token Bucket

Tokens are added to a bucket at a fixed rate. Each request consumes a token.

```
Bucket Capacity: 100 tokens
Refill Rate: 10 tokens/second

     ┌─────────────┐
     │   ○ ○ ○     │  ← 85 tokens available
     │  ○ ○ ○ ○ ○  │
     │ ○ ○ ○ ○ ○ ○ │
     └──────┬──────┘
            │
     Request comes in
            ↓
     Consume 1 token
            ↓
     ┌─────────────┐
     │   ○ ○ ○     │  ← 84 tokens remaining
     │  ○ ○ ○ ○ ○  │
     │ ○ ○ ○ ○ ○   │
     └─────────────┘
```

**Pros:** Allows bursts, smooth rate limiting
**Cons:** Slightly complex to implement distributed

### 2.4 Leaky Bucket

Requests enter a queue (bucket) and are processed at a constant rate.

```
Incoming Requests → [Bucket/Queue] → Processed at fixed rate → Backend
                          │
                    ┌─────┴─────┐
                    │  ○  ○  ○  │  ← Queue holds pending requests
                    │  ○  ○     │
                    └───────────┘
                    
Leak Rate: 10 requests/second
```

**Pros:** Smooth output rate, predictable load on backend
**Cons:** Can discard requests if bucket overflows

### 2.5 Strategy Selection Matrix

| Strategy | Accuracy | Memory | Burst Support | Distributed | Use Case |
|----------|----------|--------|---------------|-------------|----------|
| Fixed Window | Medium | Low | No | Easy | Simple APIs |
| Sliding Window | High | High | No | Medium | Critical endpoints |
| Token Bucket | High | Medium | Yes | Medium | General purpose |
| Leaky Bucket | Medium | Medium | No | Hard | Stream processing |

**Smart Dairy Recommendation**: Use **Token Bucket** for general APIs and **Sliding Window** for authentication endpoints.

---

## 3. Redis-Based Implementation

### 3.1 Architecture Overview

```
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│  API Client │──────▶│  FastAPI    │──────▶│    Redis    │
│             │      │   Server    │      │   Cluster   │
└─────────────┘      └─────────────┘      └─────────────┘
                              │
                              ▼
                       ┌─────────────┐
                       │  Rate Limit │
                       │    Check    │
                       └─────────────┘
```

### 3.2 Redis Configuration

```yaml
# redis-rate-limit.yml
version: '3.8'
services:
  redis-rate-limit:
    image: redis:7-alpine
    container_name: smart-dairy-redis-rate-limit
    ports:
      - "6380:6379"
    command: >
      redis-server
      --maxmemory 256mb
      --maxmemory-policy allkeys-lru
      --appendonly yes
      --appendfsync everysec
    volumes:
      - redis-rate-limit-data:/data
    networks:
      - smart-dairy-network

volumes:
  redis-rate-limit-data:

networks:
  smart-dairy-network:
    external: true
```

### 3.3 Redis Rate Limiter Class

```python
# rate_limiter/redis_rate_limiter.py
"""
Redis-based distributed rate limiter for Smart Dairy API.
Implements sliding window and token bucket algorithms.
"""

import asyncio
import time
from typing import Optional, Tuple
from dataclasses import dataclass
from enum import Enum

import redis.asyncio as redis
from redis.exceptions import RedisError


class RateLimitAlgorithm(Enum):
    """Supported rate limiting algorithms."""
    SLIDING_WINDOW = "sliding_window"
    TOKEN_BUCKET = "token_bucket"
    FIXED_WINDOW = "fixed_window"


@dataclass
class RateLimitResult:
    """Result of a rate limit check."""
    allowed: bool
    limit: int
    remaining: int
    reset_time: int
    retry_after: Optional[int] = None
    current_usage: int = 0


class RedisRateLimiter:
    """
    Distributed rate limiter using Redis.
    Supports multiple algorithms for different use cases.
    """
    
    def __init__(
        self,
        redis_client: redis.Redis,
        algorithm: RateLimitAlgorithm = RateLimitAlgorithm.SLIDING_WINDOW
    ):
        self.redis = redis_client
        self.algorithm = algorithm
        self._lua_scripts = {}
        
    async def initialize(self):
        """Load Lua scripts into Redis for atomic operations."""
        # Sliding window script
        self._lua_scripts['sliding_window'] = await self.redis.script_load("""
            local key = KEYS[1]
            local window = tonumber(ARGV[1])
            local limit = tonumber(ARGV[2])
            local now = tonumber(ARGV[3])
            
            -- Remove expired entries
            redis.call('ZREMRANGEBYSCORE', key, 0, now - window)
            
            -- Count current requests
            local current = redis.call('ZCARD', key)
            
            -- Get oldest entry for reset time
            local oldest = redis.call('ZRANGE', key, 0, 0, 'WITHSCORES')
            local reset_time = now + window
            if #oldest >= 2 then
                reset_time = tonumber(oldest[2]) + window
            end
            
            if current < limit then
                -- Add current request
                redis.call('ZADD', key, now, now .. ':' .. redis.call('INCR', key .. ':counter'))
                redis.call('EXPIRE', key, math.ceil(window / 1000) + 1)
                return {1, limit, limit - current - 1, reset_time, current + 1}
            else
                return {0, limit, 0, reset_time, current}
            end
        """)
        
        # Token bucket script
        self._lua_scripts['token_bucket'] = await self.redis.script_load("""
            local key = KEYS[1]
            local capacity = tonumber(ARGV[1])
            local refill_rate = tonumber(ARGV[2])
            local tokens = tonumber(ARGV[3])
            local now = tonumber(ARGV[4])
            
            local bucket = redis.call('HMGET', key, 'tokens', 'last_refill')
            local current_tokens = tonumber(bucket[1]) or capacity
            local last_refill = tonumber(bucket[2]) or now
            
            -- Calculate tokens to add
            local delta = math.min(capacity, current_tokens + (now - last_refill) * refill_rate / 1000)
            
            if delta >= tokens then
                -- Consume tokens
                redis.call('HMSET', key, 'tokens', delta - tokens, 'last_refill', now)
                redis.call('EXPIRE', key, 3600)
                return {1, capacity, math.floor(delta - tokens), now + 1000, math.floor(delta)}
            else
                -- Not enough tokens
                redis.call('HMSET', key, 'tokens', delta, 'last_refill', now)
                redis.call('EXPIRE', key, 3600)
                local retry_after = math.ceil((tokens - delta) * 1000 / refill_rate)
                return {0, capacity, 0, now + retry_after, math.floor(delta), retry_after}
            end
        """)
        
        # Fixed window script
        self._lua_scripts['fixed_window'] = await self.redis.script_load("""
            local key = KEYS[1]
            local window = tonumber(ARGV[1])
            local limit = tonumber(ARGV[2])
            
            local current = tonumber(redis.call('GET', key) or 0)
            local ttl = redis.call('TTL', key)
            
            if current < limit then
                redis.call('INCR', key)
                if ttl == -1 then
                    redis.call('EXPIRE', key, window)
                end
                return {1, limit, limit - current - 1, ttl > 0 and ttl or window, current + 1}
            else
                return {0, limit, 0, ttl > 0 and ttl or window, current}
            end
        """)
    
    async def check_rate_limit(
        self,
        key: str,
        limit: int,
        window_seconds: int,
        tokens: int = 1,
        refill_rate: Optional[int] = None
    ) -> RateLimitResult:
        """
        Check if request is within rate limit.
        
        Args:
            key: Unique identifier (user_id:endpoint)
            limit: Maximum allowed requests/tokens
            window_seconds: Time window in seconds
            tokens: Tokens to consume (for token bucket)
            refill_rate: Tokens per second (for token bucket)
        
        Returns:
            RateLimitResult with allowed status and metadata
        """
        now = int(time.time() * 1000)  # milliseconds
        
        try:
            if self.algorithm == RateLimitAlgorithm.SLIDING_WINDOW:
                result = await self.redis.evalsha(
                    self._lua_scripts['sliding_window'],
                    1, key, window_seconds * 1000, limit, now
                )
                return RateLimitResult(
                    allowed=bool(result[0]),
                    limit=result[1],
                    remaining=result[2],
                    reset_time=result[3],
                    current_usage=result[4]
                )
                
            elif self.algorithm == RateLimitAlgorithm.TOKEN_BUCKET:
                rate = refill_rate or (limit / window_seconds)
                result = await self.redis.evalsha(
                    self._lua_scripts['token_bucket'],
                    1, key, limit, int(rate * 1000), tokens, now
                )
                return RateLimitResult(
                    allowed=bool(result[0]),
                    limit=result[1],
                    remaining=result[2],
                    reset_time=result[3],
                    current_usage=result[4],
                    retry_after=result[5] if len(result) > 5 else None
                )
                
            elif self.algorithm == RateLimitAlgorithm.FIXED_WINDOW:
                result = await self.redis.evalsha(
                    self._lua_scripts['fixed_window'],
                    1, key, window_seconds, limit
                )
                return RateLimitResult(
                    allowed=bool(result[0]),
                    limit=result[1],
                    remaining=result[2],
                    reset_time=int(time.time()) + result[3],
                    current_usage=result[4]
                )
                
        except RedisError as e:
            # Fail open - allow request if Redis is unavailable
            # Log error for monitoring
            print(f"Redis rate limit error: {e}")
            return RateLimitResult(
                allowed=True,
                limit=limit,
                remaining=1,
                reset_time=int(time.time()) + window_seconds
            )
    
    async def get_current_usage(self, key: str, window_seconds: int) -> int:
        """Get current request count without incrementing."""
        now = int(time.time() * 1000)
        
        if self.algorithm == RateLimitAlgorithm.SLIDING_WINDOW:
            await self.redis.zremrangebyscore(key, 0, now - window_seconds * 1000)
            return await self.redis.zcard(key)
        elif self.algorithm == RateLimitAlgorithm.TOKEN_BUCKET:
            bucket = await self.redis.hmget(key, 'tokens', 'last_refill')
            if bucket[0] is None:
                return 0
            current = float(bucket[0])
            last_refill = int(bucket[1]) if bucket[1] else now
            refill_rate = 1.0  # Default
            delta = min(current + (now - last_refill) * refill_rate / 1000, current)
            return int(current - delta)
        else:
            count = await self.redis.get(key)
            return int(count) if count else 0
    
    async def reset_limit(self, key: str) -> bool:
        """Reset rate limit for a key (useful for testing or admin actions)."""
        return await self.redis.delete(key) > 0


class RateLimiterFactory:
    """Factory for creating rate limiter instances."""
    
    _instances = {}
    
    @classmethod
    async def get_limiter(
        cls,
        redis_url: str = "redis://localhost:6380",
        algorithm: RateLimitAlgorithm = RateLimitAlgorithm.SLIDING_WINDOW
    ) -> RedisRateLimiter:
        """Get or create rate limiter instance."""
        cache_key = f"{redis_url}:{algorithm.value}"
        
        if cache_key not in cls._instances:
            redis_client = redis.from_url(redis_url, decode_responses=True)
            limiter = RedisRateLimiter(redis_client, algorithm)
            await limiter.initialize()
            cls._instances[cache_key] = limiter
            
        return cls._instances[cache_key]
    
    @classmethod
    async def close_all(cls):
        """Close all rate limiter connections."""
        for limiter in cls._instances.values():
            await limiter.redis.close()
        cls._instances.clear()
```

---

## 4. Per-Endpoint Limits

### 4.1 Endpoint Categories

| Category | Endpoints | Default Limit | Burst Allowance |
|----------|-----------|---------------|-----------------|
| **Authentication** | /auth/*, /login, /register | 5/min | No |
| **General API** | /api/v1/* | 100/min | Yes |
| **Search** | /api/v1/search/* | 30/min | Yes |
| **Bulk Operations** | /api/v1/bulk/* | 10/min | No |
| **Admin** | /admin/* | 200/min | Yes |
| **Webhooks** | /webhooks/* | 60/min | Yes |
| **File Upload** | /api/v1/files/upload | 20/min | No |
| **Health Check** | /health, /ping | 1000/min | Yes |

### 4.2 Endpoint-Specific Configuration

```python
# config/rate_limits.py
"""
Rate limit configurations for Smart Dairy API endpoints.
"""

from dataclasses import dataclass
from typing import Dict, Optional


@dataclass
class EndpointLimit:
    """Configuration for endpoint rate limiting."""
    requests: int
    window_seconds: int
    burst_size: Optional[int] = None
    algorithm: str = "sliding_window"  # sliding_window, token_bucket, fixed_window
    per_user: bool = True  # If False, limit is global
    
    @property
    def burst_allowed(self) -> bool:
        return self.burst_size is not None and self.burst_size > self.requests


# Endpoint-specific rate limits
ENDPOINT_LIMITS: Dict[str, EndpointLimit] = {
    # Authentication endpoints - strict limits
    "auth:login": EndpointLimit(
        requests=5,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "auth:register": EndpointLimit(
        requests=3,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "auth:refresh": EndpointLimit(
        requests=10,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "auth:password_reset": EndpointLimit(
        requests=3,
        window_seconds=3600,  # 1 hour
        algorithm="sliding_window",
        per_user=True
    ),
    "auth:mfa_verify": EndpointLimit(
        requests=5,
        window_seconds=300,  # 5 minutes
        algorithm="sliding_window",
        per_user=True
    ),
    
    # Search endpoints - moderate limits
    "search:general": EndpointLimit(
        requests=30,
        window_seconds=60,
        burst_size=50,
        algorithm="token_bucket",
        per_user=True
    ),
    "search:advanced": EndpointLimit(
        requests=20,
        window_seconds=60,
        burst_size=30,
        algorithm="token_bucket",
        per_user=True
    ),
    "search:export": EndpointLimit(
        requests=5,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    
    # Bulk operations - strict limits
    "bulk:import": EndpointLimit(
        requests=10,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "bulk:export": EndpointLimit(
        requests=10,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "bulk:update": EndpointLimit(
        requests=10,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "bulk:delete": EndpointLimit(
        requests=5,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    
    # Data endpoints - standard limits
    "data:cows": EndpointLimit(
        requests=100,
        window_seconds=60,
        burst_size=150,
        algorithm="token_bucket",
        per_user=True
    ),
    "data:milk_production": EndpointLimit(
        requests=100,
        window_seconds=60,
        burst_size=150,
        algorithm="token_bucket",
        per_user=True
    ),
    "data:health_records": EndpointLimit(
        requests=100,
        window_seconds=60,
        burst_size=150,
        algorithm="token_bucket",
        per_user=True
    ),
    "data:breeding": EndpointLimit(
        requests=100,
        window_seconds=60,
        burst_size=150,
        algorithm="token_bucket",
        per_user=True
    ),
    
    # File operations
    "file:upload": EndpointLimit(
        requests=20,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "file:download": EndpointLimit(
        requests=50,
        window_seconds=60,
        burst_size=75,
        algorithm="token_bucket",
        per_user=True
    ),
    
    # Webhook endpoints
    "webhook:register": EndpointLimit(
        requests=10,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    "webhook:delivery": EndpointLimit(
        requests=60,
        window_seconds=60,
        burst_size=100,
        algorithm="token_bucket",
        per_user=True
    ),
    
    # Admin endpoints
    "admin:users": EndpointLimit(
        requests=200,
        window_seconds=60,
        burst_size=300,
        algorithm="token_bucket",
        per_user=True
    ),
    "admin:system": EndpointLimit(
        requests=100,
        window_seconds=60,
        burst_size=150,
        algorithm="token_bucket",
        per_user=True
    ),
    "admin:audit": EndpointLimit(
        requests=50,
        window_seconds=60,
        algorithm="sliding_window",
        per_user=True
    ),
    
    # Health check - high limit
    "health:check": EndpointLimit(
        requests=1000,
        window_seconds=60,
        algorithm="fixed_window",
        per_user=False  # Global limit
    ),
}


def get_endpoint_limit(endpoint: str, method: str = "GET") -> EndpointLimit:
    """
    Get rate limit configuration for an endpoint.
    
    Args:
        endpoint: Endpoint identifier (e.g., "auth:login")
        method: HTTP method
    
    Returns:
        EndpointLimit configuration
    """
    # Try specific endpoint first
    if endpoint in ENDPOINT_LIMITS:
        return ENDPOINT_LIMITS[endpoint]
    
    # Try pattern matching
    for pattern, limit in ENDPOINT_LIMITS.items():
        if endpoint.startswith(pattern.split(':')[0]):
            return limit
    
    # Default limit
    return EndpointLimit(
        requests=100,
        window_seconds=60,
        burst_size=150,
        algorithm="token_bucket",
        per_user=True
    )


def get_limit_key(endpoint: str, user_id: Optional[str] = None, ip: Optional[str] = None) -> str:
    """Generate Redis key for rate limiting."""
    if user_id:
        return f"rate_limit:user:{user_id}:{endpoint}"
    elif ip:
        return f"rate_limit:ip:{ip}:{endpoint}"
    else:
        return f"rate_limit:global:{endpoint}"
```

---

## 5. Per-User Limits

### 5.1 User Type Classifications

```python
# config/user_tiers.py
"""
User tier configurations for differentiated rate limiting.
"""

from enum import Enum
from dataclasses import dataclass
from typing import Dict


class UserTier(Enum):
    """API access tiers for Smart Dairy."""
    ANONYMOUS = "anonymous"
    FREE = "free"
    BASIC = "basic"
    PREMIUM = "premium"
    ENTERPRISE = "enterprise"
    INTERNAL = "internal"
    ADMIN = "admin"


@dataclass
class TierConfig:
    """Configuration for a user tier."""
    multiplier: float  # Multiplier for base rate limits
    daily_quota: int   # Daily request quota
    concurrent_limit: int  # Maximum concurrent requests
    burst_multiplier: float  # Burst allowance multiplier
    description: str


TIER_CONFIGS: Dict[UserTier, TierConfig] = {
    UserTier.ANONYMOUS: TierConfig(
        multiplier=0.5,
        daily_quota=1000,
        concurrent_limit=5,
        burst_multiplier=0.5,
        description="Unauthenticated users - most restrictive"
    ),
    UserTier.FREE: TierConfig(
        multiplier=1.0,
        daily_quota=10000,
        concurrent_limit=10,
        burst_multiplier=1.0,
        description="Free tier users - standard limits"
    ),
    UserTier.BASIC: TierConfig(
        multiplier=2.0,
        daily_quota=50000,
        concurrent_limit=25,
        burst_multiplier=1.5,
        description="Basic paid tier - 2x limits"
    ),
    UserTier.PREMIUM: TierConfig(
        multiplier=5.0,
        daily_quota=200000,
        concurrent_limit=50,
        burst_multiplier=2.0,
        description="Premium tier - 5x limits"
    ),
    UserTier.ENTERPRISE: TierConfig(
        multiplier=10.0,
        daily_quota=1000000,
        concurrent_limit=100,
        burst_multiplier=3.0,
        description="Enterprise tier - 10x limits"
    ),
    UserTier.INTERNAL: TierConfig(
        multiplier=20.0,
        daily_quota=10000000,
        concurrent_limit=200,
        burst_multiplier=5.0,
        description="Internal services - high limits"
    ),
    UserTier.ADMIN: TierConfig(
        multiplier=50.0,
        daily_quota=-1,  # Unlimited
        concurrent_limit=500,
        burst_multiplier=10.0,
        description="Administrators - very high limits"
    ),
}


def get_user_tier(user: dict) -> UserTier:
    """
    Determine user tier from user data.
    
    Args:
        user: User dictionary with tier/subscription info
    
    Returns:
        UserTier enum value
    """
    if not user:
        return UserTier.ANONYMOUS
    
    if user.get("is_admin") or user.get("role") == "admin":
        return UserTier.ADMIN
    
    if user.get("is_internal"):
        return UserTier.INTERNAL
    
    subscription = user.get("subscription", {})
    tier = subscription.get("tier", "free").lower()
    
    tier_map = {
        "free": UserTier.FREE,
        "basic": UserTier.BASIC,
        "premium": UserTier.PREMIUM,
        "enterprise": UserTier.ENTERPRISE,
    }
    
    return tier_map.get(tier, UserTier.FREE)


def calculate_tiered_limits(
    base_limit: int,
    base_burst: int,
    tier: UserTier
) -> tuple[int, int]:
    """
    Calculate rate limits adjusted for user tier.
    
    Args:
        base_limit: Base rate limit
        base_burst: Base burst allowance
        tier: User tier
    
    Returns:
        Tuple of (adjusted_limit, adjusted_burst)
    """
    config = TIER_CONFIGS[tier]
    
    adjusted_limit = int(base_limit * config.multiplier)
    adjusted_burst = int(base_burst * config.burst_multiplier) if base_burst else adjusted_limit
    
    return adjusted_limit, adjusted_burst


def get_daily_quota(tier: UserTier) -> int:
    """Get daily quota for a tier (-1 means unlimited)."""
    return TIER_CONFIGS[tier].daily_quota


def get_concurrent_limit(tier: UserTier) -> int:
    """Get concurrent request limit for a tier."""
    return TIER_CONFIGS[tier].concurrent_limit
```

### 5.2 User-Specific Rate Limit Application

```python
# middleware/user_rate_limiter.py
"""
User-specific rate limit calculations and application.
"""

from typing import Optional
from config.rate_limits import get_endpoint_limit, get_limit_key, EndpointLimit
from config.user_tiers import get_user_tier, calculate_tiered_limits, UserTier


class UserRateLimiter:
    """Applies tier-specific rate limits to users."""
    
    def __init__(self, base_limiter):
        self.base_limiter = base_limiter
    
    def get_user_limits(
        self,
        endpoint: str,
        user: Optional[dict] = None,
        ip_address: Optional[str] = None
    ) -> tuple[EndpointLimit, str]:
        """
        Get adjusted rate limits for a user/endpoint combination.
        
        Args:
            endpoint: Endpoint identifier
            user: User data dictionary (None for anonymous)
            ip_address: Client IP address
        
        Returns:
            Tuple of (adjusted_limit, redis_key)
        """
        base_limit = get_endpoint_limit(endpoint)
        tier = get_user_tier(user)
        
        # Calculate tiered limits
        adjusted_requests, adjusted_burst = calculate_tiered_limits(
            base_limit.requests,
            base_limit.burst_size or base_limit.requests,
            tier
        )
        
        # Create adjusted limit config
        adjusted_limit = EndpointLimit(
            requests=adjusted_requests,
            window_seconds=base_limit.window_seconds,
            burst_size=adjusted_burst if adjusted_burst > adjusted_requests else None,
            algorithm=base_limit.algorithm,
            per_user=base_limit.per_user
        )
        
        # Generate appropriate key
        if user and base_limit.per_user:
            user_id = user.get("id") or user.get("user_id")
            key = get_limit_key(endpoint, user_id=user_id)
        else:
            key = get_limit_key(endpoint, ip=ip_address)
        
        return adjusted_limit, key
    
    def should_apply_limits(self, user: Optional[dict]) -> bool:
        """
        Check if rate limits should be applied to user.
        
        Args:
            user: User data dictionary
        
        Returns:
            False if user is exempt, True otherwise
        """
        if not user:
            return True
        
        # Check for exemptions
        if user.get("rate_limit_exempt"):
            return False
        
        if user.get("role") == "super_admin":
            return False
        
        # Internal service accounts might be exempt
        if user.get("is_service_account") and user.get("service_exempt"):
            return False
        
        return True


# Special user categories with different limit behaviors
SPECIAL_USER_HANDLERS = {
    "load_tester": {
        "description": "Load testing accounts with elevated limits",
        "tier_override": UserTier.ENTERPRISE,
        "daily_quota_override": 10000000,
    },
    "integration_partner": {
        "description": "Third-party integration partners",
        "tier_override": UserTier.PREMIUM,
        "custom_limits": {
            "webhook:delivery": {"requests": 120, "window_seconds": 60},
        }
    },
    "mobile_app": {
        "description": "Official mobile application",
        "tier_override": UserTier.BASIC,
        "burst_bonus": 1.5,  # Extra burst allowance
    },
    "iot_device": {
        "description": "IoT devices (sensors, automated equipment)",
        "tier_override": UserTier.BASIC,
        "endpoint_limits": {
            "data:telemetry": {"requests": 1000, "window_seconds": 60},
        }
    }
}


def apply_special_user_overrides(user: dict, base_config: dict) -> dict:
    """
    Apply special user category overrides to rate limit config.
    
    Args:
        user: User data with special_category field
        base_config: Base rate limit configuration
    
    Returns:
        Modified configuration with overrides applied
    """
    category = user.get("special_category")
    if not category or category not in SPECIAL_USER_HANDLERS:
        return base_config
    
    overrides = SPECIAL_USER_HANDLERS[category]
    config = base_config.copy()
    
    if "tier_override" in overrides:
        config["tier"] = overrides["tier_override"]
    
    if "daily_quota_override" in overrides:
        config["daily_quota"] = overrides["daily_quota_override"]
    
    if "burst_bonus" in overrides:
        config["burst_multiplier"] = config.get("burst_multiplier", 1.0) * overrides["burst_bonus"]
    
    return config
```

---

## 6. Response Headers

### 6.1 Standard Rate Limit Headers

Smart Dairy API implements the following rate limit headers according to IETF draft standards:

| Header | Description | Example |
|--------|-------------|---------|
| `X-RateLimit-Limit` | Maximum requests allowed in window | `100` |
| `X-RateLimit-Remaining` | Requests remaining in current window | `87` |
| `X-RateLimit-Reset` | Unix timestamp when limit resets | `1706707200` |
| `X-RateLimit-Policy` | Rate limit policy applied | `100;w=60` |
| `Retry-After` | Seconds to wait before retry (429 only) | `45` |

### 6.2 Header Implementation

```python
# middleware/rate_limit_headers.py
"""
Rate limit header management for API responses.
"""

from typing import Optional
from dataclasses import dataclass
from fastapi import Response


@dataclass
class RateLimitHeaders:
    """Container for rate limit header values."""
    limit: int
    remaining: int
    reset_time: int
    policy: str
    retry_after: Optional[int] = None
    
    def to_dict(self) -> dict:
        """Convert to dictionary for header injection."""
        headers = {
            "X-RateLimit-Limit": str(self.limit),
            "X-RateLimit-Remaining": str(max(0, self.remaining)),
            "X-RateLimit-Reset": str(self.reset_time),
            "X-RateLimit-Policy": self.policy,
        }
        
        if self.retry_after is not None:
            headers["Retry-After"] = str(self.retry_after)
        
        return headers


class RateLimitHeaderManager:
    """Manages rate limit headers for API responses."""
    
    @staticmethod
    def create_headers(
        limit: int,
        remaining: int,
        reset_time: int,
        window_seconds: int,
        retry_after: Optional[int] = None
    ) -> RateLimitHeaders:
        """
        Create rate limit headers.
        
        Args:
            limit: Request limit
            remaining: Remaining requests
            reset_time: Reset timestamp
            window_seconds: Window size in seconds
            retry_after: Retry after seconds (for 429)
        
        Returns:
            RateLimitHeaders object
        """
        policy = f"{limit};w={window_seconds}"
        
        return RateLimitHeaders(
            limit=limit,
            remaining=remaining,
            reset_time=reset_time,
            policy=policy,
            retry_after=retry_after
        )
    
    @staticmethod
    def inject_headers(response: Response, headers: RateLimitHeaders) -> None:
        """Inject rate limit headers into response."""
        for key, value in headers.to_dict().items():
            response.headers[key] = value
    
    @staticmethod
    def create_draft_standard_headers(
        limit: int,
        remaining: int,
        reset_time: int,
        window_seconds: int
    ) -> dict:
        """
        Create headers following IETF RateLimit draft standard.
        
        Format: RateLimit: limit=100, remaining=87, reset=1706707200
        """
        return {
            "RateLimit": f"limit={limit}, remaining={max(0, remaining)}, reset={reset_time}",
            "RateLimit-Policy": f"{limit};w={window_seconds}",
        }


def add_rate_limit_headers(
    response: Response,
    result,
    window_seconds: int,
    is_rate_limited: bool = False
) -> None:
    """
    Helper function to add rate limit headers to response.
    
    Args:
        response: FastAPI Response object
        result: RateLimitResult from limiter
        window_seconds: Time window in seconds
        is_rate_limited: Whether request was rate limited
    """
    headers = RateLimitHeaderManager.create_headers(
        limit=result.limit,
        remaining=result.remaining,
        reset_time=result.reset_time,
        window_seconds=window_seconds,
        retry_after=result.retry_after if is_rate_limited else None
    )
    
    RateLimitHeaderManager.inject_headers(response, headers)
```

### 6.3 Header Examples by Scenario

**Normal Request (Within Limit):**
```http
HTTP/1.1 200 OK
Content-Type: application/json
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 87
X-RateLimit-Reset: 1706707200
X-RateLimit-Policy: 100;w=60

{ "data": "..." }
```

**Rate Limited Request:**
```http
HTTP/1.1 429 Too Many Requests
Content-Type: application/json
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 0
X-RateLimit-Reset: 1706707200
X-RateLimit-Policy: 100;w=60
Retry-After: 45

{
  "error": "Rate limit exceeded",
  "message": "Too many requests. Please try again later.",
  "retry_after": 45,
  "limit": 100,
  "window": 60
}
```

**Token Bucket (Burst Available):**
```http
HTTP/1.1 200 OK
Content-Type: application/json
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 150  # Burst capacity
X-RateLimit-Reset: 1706707200
X-RateLimit-Policy: 100;w=60;b=150
```

---

## 7. Throttling Implementation

### 7.1 FastAPI Middleware

```python
# middleware/rate_limit_middleware.py
"""
FastAPI middleware for API rate limiting.
"""

import time
import asyncio
from typing import Optional, Callable
from fastapi import Request, Response, HTTPException
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.types import ASGIApp

from rate_limiter.redis_rate_limiter import RedisRateLimiter, RateLimiterFactory
from middleware.rate_limit_headers import add_rate_limit_headers
from config.rate_limits import get_endpoint_limit, get_limit_key
from config.user_tiers import get_user_tier, calculate_tiered_limits


class RateLimitMiddleware(BaseHTTPMiddleware):
    """
    FastAPI middleware for distributed rate limiting.
    
    Features:
    - Per-endpoint limits
    - Per-user tier adjustments
    - IP-based fallback for anonymous users
    - Header injection
    - Configurable exemption paths
    """
    
    def __init__(
        self,
        app: ASGIApp,
        redis_url: str = "redis://localhost:6380",
        exempt_paths: Optional[list] = None,
        exempt_ips: Optional[list] = None,
        skip_on_redis_error: bool = True
    ):
        super().__init__(app)
        self.redis_url = redis_url
        self.exempt_paths = exempt_paths or [
            "/health",
            "/ping",
            "/docs",
            "/openapi.json",
            "/redoc",
        ]
        self.exempt_ips = set(exempt_ips or [])
        self.skip_on_redis_error = skip_on_redis_error
        self._limiter: Optional[RedisRateLimiter] = None
    
    async def startup(self):
        """Initialize rate limiter connection."""
        self._limiter = await RateLimiterFactory.get_limiter(self.redis_url)
    
    async def shutdown(self):
        """Close rate limiter connection."""
        await RateLimiterFactory.close_all()
    
    def _get_endpoint_id(self, request: Request) -> str:
        """Generate endpoint identifier from request."""
        path = request.url.path.strip('/')
        
        # Map paths to endpoint IDs
        path_mappings = {
            "api/v1/auth/login": "auth:login",
            "api/v1/auth/register": "auth:register",
            "api/v1/auth/refresh": "auth:refresh",
            "api/v1/search": "search:general",
            "api/v1/bulk/import": "bulk:import",
            "api/v1/bulk/export": "bulk:export",
            "api/v1/cows": "data:cows",
            "api/v1/milk-production": "data:milk_production",
            "api/v1/health-records": "data:health_records",
            "api/v1/files/upload": "file:upload",
        }
        
        # Exact match
        if path in path_mappings:
            return path_mappings[path]
        
        # Prefix match
        for prefix, endpoint_id in path_mappings.items():
            if path.startswith(prefix):
                return endpoint_id
        
        # Default
        return f"api:{path.replace('/', ':')}"
    
    def _is_exempt(self, request: Request) -> bool:
        """Check if request path is exempt from rate limiting."""
        path = request.url.path
        
        # Check path exemptions
        for exempt in self.exempt_paths:
            if path.startswith(exempt):
                return True
        
        # Check IP exemptions
        client_ip = self._get_client_ip(request)
        if client_ip in self.exempt_ips:
            return True
        
        return False
    
    def _get_client_ip(self, request: Request) -> str:
        """Extract client IP from request."""
        # Check X-Forwarded-For header
        forwarded = request.headers.get("X-Forwarded-For")
        if forwarded:
            return forwarded.split(',')[0].strip()
        
        # Check X-Real-IP header
        real_ip = request.headers.get("X-Real-IP")
        if real_ip:
            return real_ip
        
        # Fall back to direct connection
        if request.client:
            return request.client.host
        
        return "unknown"
    
    def _get_user_from_request(self, request: Request) -> Optional[dict]:
        """Extract user info from request state."""
        # User should be set by authentication middleware
        return getattr(request.state, "user", None)
    
    async def dispatch(self, request: Request, call_next: Callable) -> Response:
        """Process request with rate limiting."""
        
        # Skip if exempt
        if self._is_exempt(request):
            return await call_next(request)
        
        # Skip if limiter not initialized
        if not self._limiter:
            if self.skip_on_redis_error:
                return await call_next(request)
            raise HTTPException(status_code=503, detail="Rate limiter unavailable")
        
        # Get endpoint and user info
        endpoint_id = self._get_endpoint_id(request)
        user = self._get_user_from_request(request)
        client_ip = self._get_client_ip(request)
        
        # Get rate limit configuration
        base_limit = get_endpoint_limit(endpoint_id, request.method)
        
        # Apply tier adjustments
        if user:
            tier = get_user_tier(user)
            limit_value, burst_value = calculate_tiered_limits(
                base_limit.requests,
                base_limit.burst_size or base_limit.requests,
                tier
            )
        else:
            # Anonymous users get reduced limits
            limit_value = int(base_limit.requests * 0.5)
            burst_value = int((base_limit.burst_size or base_limit.requests) * 0.5)
        
        # Generate rate limit key
        if user and base_limit.per_user:
            user_id = user.get("id") or user.get("user_id")
            key = get_limit_key(endpoint_id, user_id=user_id)
        else:
            key = get_limit_key(endpoint_id, ip=client_ip)
        
        # Check rate limit
        try:
            result = await self._limiter.check_rate_limit(
                key=key,
                limit=limit_value,
                window_seconds=base_limit.window_seconds
            )
        except Exception as e:
            if self.skip_on_redis_error:
                # Log error and allow request
                print(f"Rate limit check failed: {e}")
                return await call_next(request)
            raise HTTPException(status_code=503, detail="Rate limit check failed")
        
        # Process request
        response = await call_next(request)
        
        # Add rate limit headers
        add_rate_limit_headers(
            response=response,
            result=result,
            window_seconds=base_limit.window_seconds,
            is_rate_limited=not result.allowed
        )
        
        # Handle rate limit exceeded
        if not result.allowed:
            raise HTTPException(
                status_code=429,
                detail={
                    "error": "Rate limit exceeded",
                    "message": "Too many requests. Please try again later.",
                    "retry_after": result.retry_after or base_limit.window_seconds,
                    "limit": limit_value,
                    "window": base_limit.window_seconds,
                    "endpoint": endpoint_id
                }
            )
        
        return response
```

### 7.2 FastAPI Dependency

```python
# dependencies/rate_limit.py
"""
FastAPI dependencies for endpoint-specific rate limiting.
"""

from typing import Optional
from fastapi import Request, HTTPException, Depends
from functools import wraps

from rate_limiter.redis_rate_limiter import RateLimiterFactory
from middleware.rate_limit_headers import add_rate_limit_headers
from config.rate_limits import get_endpoint_limit, EndpointLimit
from config.user_tiers import get_user_tier, calculate_tiered_limits


class RateLimitDependency:
    """
    FastAPI dependency for flexible rate limiting.
    Use for endpoint-specific limits that differ from defaults.
    """
    
    def __init__(
        self,
        requests: int = 100,
        window_seconds: int = 60,
        burst_size: Optional[int] = None,
        per_user: bool = True,
        key_prefix: str = "custom"
    ):
        self.requests = requests
        self.window_seconds = window_seconds
        self.burst_size = burst_size
        self.per_user = per_user
        self.key_prefix = key_prefix
    
    async def __call__(self, request: Request):
        """Execute rate limit check."""
        limiter = await RateLimiterFactory.get_limiter()
        
        # Get user info
        user = getattr(request.state, "user", None)
        client_ip = self._get_client_ip(request)
        
        # Apply tier adjustments
        if user:
            tier = get_user_tier(user)
            limit_value, burst_value = calculate_tiered_limits(
                self.requests,
                self.burst_size or self.requests,
                tier
            )
        else:
            limit_value = int(self.requests * 0.5)
            burst_value = int((self.burst_size or self.requests) * 0.5)
        
        # Generate key
        if user and self.per_user:
            user_id = user.get("id") or user.get("user_id")
            key = f"{self.key_prefix}:user:{user_id}"
        else:
            key = f"{self.key_prefix}:ip:{client_ip}"
        
        # Check limit
        result = await limiter.check_rate_limit(
            key=key,
            limit=limit_value,
            window_seconds=self.window_seconds
        )
        
        # Store result for header injection
        request.state.rate_limit_result = result
        request.state.rate_limit_window = self.window_seconds
        
        if not result.allowed:
            raise HTTPException(
                status_code=429,
                detail={
                    "error": "Rate limit exceeded",
                    "retry_after": result.retry_after or self.window_seconds,
                    "limit": limit_value,
                    "window": self.window_seconds
                }
            )
    
    def _get_client_ip(self, request: Request) -> str:
        """Extract client IP."""
        forwarded = request.headers.get("X-Forwarded-For")
        if forwarded:
            return forwarded.split(',')[0].strip()
        
        real_ip = request.headers.get("X-Real-IP")
        if real_ip:
            return real_ip
        
        if request.client:
            return request.client.host
        
        return "unknown"


# Predefined rate limit dependencies
auth_rate_limit = RateLimitDependency(
    requests=5,
    window_seconds=60,
    key_prefix="auth"
)

search_rate_limit = RateLimitDependency(
    requests=30,
    window_seconds=60,
    burst_size=50,
    key_prefix="search"
)

bulk_rate_limit = RateLimitDependency(
    requests=10,
    window_seconds=60,
    key_prefix="bulk"
)

upload_rate_limit = RateLimitDependency(
    requests=20,
    window_seconds=60,
    key_prefix="upload"
)

# Usage in FastAPI routes:
# @router.post("/login", dependencies=[Depends(auth_rate_limit)])
# async def login(...)
```

### 7.3 Application Integration

```python
# main.py - FastAPI Application Setup
"""
Smart Dairy API main application with rate limiting.
"""

from fastapi import FastAPI
from contextlib import asynccontextmanager

from middleware.rate_limit_middleware import RateLimitMiddleware


@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan manager."""
    # Startup
    rate_limit_middleware = None
    for middleware in app.user_middleware:
        if isinstance(middleware.cls, type) and middleware.cls.__name__ == "RateLimitMiddleware":
            rate_limit_middleware = middleware.cls(app, **middleware.options)
            await rate_limit_middleware.startup()
            break
    
    yield
    
    # Shutdown
    if rate_limit_middleware:
        await rate_limit_middleware.shutdown()


def create_application() -> FastAPI:
    """Create and configure FastAPI application."""
    
    app = FastAPI(
        title="Smart Dairy API",
        description="API for Smart Dairy Web Portal System",
        version="1.0.0",
        lifespan=lifespan
    )
    
    # Add rate limiting middleware
    app.add_middleware(
        RateLimitMiddleware,
        redis_url="redis://localhost:6380",
        exempt_paths=[
            "/health",
            "/ping",
            "/docs",
            "/openapi.json",
            "/redoc",
        ],
        exempt_ips=["127.0.0.1", "10.0.0.0/8"],  # Internal networks
        skip_on_redis_error=True
    )
    
    # Include routers
    from routers import auth, cows, search, bulk, files, admin
    
    app.include_router(auth.router, prefix="/api/v1/auth", tags=["Authentication"])
    app.include_router(cows.router, prefix="/api/v1/cows", tags=["Cows"])
    app.include_router(search.router, prefix="/api/v1/search", tags=["Search"])
    app.include_router(bulk.router, prefix="/api/v1/bulk", tags=["Bulk Operations"])
    app.include_router(files.router, prefix="/api/v1/files", tags=["Files"])
    app.include_router(admin.router, prefix="/admin", tags=["Admin"])
    
    @app.get("/health")
    async def health_check():
        """Health check endpoint (exempt from rate limiting)."""
        return {"status": "healthy"}
    
    return app


app = create_application()
```

---

## 8. Burst Handling

### 8.1 Token Bucket Implementation for Bursts

```python
# rate_limiter/token_bucket.py
"""
Token bucket rate limiter with burst support.
"""

import time
import asyncio
from typing import Optional, Callable
from dataclasses import dataclass

import redis.asyncio as redis


@dataclass
class TokenBucketConfig:
    """Configuration for token bucket."""
    capacity: int          # Maximum tokens in bucket
    refill_rate: float     # Tokens per second
    initial_tokens: Optional[int] = None  # Initial token count


class TokenBucketRateLimiter:
    """
    Token bucket rate limiter with Redis backend.
    Supports burst traffic while maintaining long-term rate limits.
    """
    
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
        self._lua_script = None
    
    async def initialize(self):
        """Load Lua script for atomic token bucket operations."""
        self._lua_script = await self.redis.script_load("""
            local key = KEYS[1]
            local capacity = tonumber(ARGV[1])
            local refill_rate = tonumber(ARGV[2])
            local tokens_needed = tonumber(ARGV[3])
            local now = tonumber(ARGV[4])
            local ttl = tonumber(ARGV[5])
            
            -- Get current bucket state
            local state = redis.call('HMGET', key, 'tokens', 'last_update')
            local current_tokens = tonumber(state[1])
            local last_update = tonumber(state[2])
            
            -- Initialize if new bucket
            if current_tokens == nil then
                current_tokens = capacity
                last_update = now
            end
            
            -- Calculate tokens to add since last update
            local time_delta = math.max(0, now - last_update)
            local tokens_to_add = time_delta * refill_rate / 1000
            local new_tokens = math.min(capacity, current_tokens + tokens_to_add)
            
            -- Check if request can be satisfied
            if new_tokens >= tokens_needed then
                -- Consume tokens
                new_tokens = new_tokens - tokens_needed
                redis.call('HMSET', key, 'tokens', new_tokens, 'last_update', now)
                redis.call('EXPIRE', key, ttl)
                
                -- Return: allowed, remaining, reset_time_ms, current_tokens
                local reset_time = now + math.ceil((capacity - new_tokens) * 1000 / refill_rate)
                return {1, math.floor(new_tokens), reset_time, math.floor(new_tokens)}
            else
                -- Not enough tokens
                redis.call('HMSET', key, 'tokens', new_tokens, 'last_update', now)
                redis.call('EXPIRE', key, ttl)
                
                -- Calculate retry after
                local tokens_needed_after = tokens_needed - new_tokens
                local retry_after = math.ceil(tokens_needed_after * 1000 / refill_rate)
                
                return {0, 0, now + retry_after, math.floor(new_tokens), retry_after}
            end
        """)
    
    async def consume(
        self,
        key: str,
        config: TokenBucketConfig,
        tokens: int = 1
    ) -> dict:
        """
        Consume tokens from bucket.
        
        Args:
            key: Bucket identifier
            config: Token bucket configuration
            tokens: Number of tokens to consume
        
        Returns:
            Dictionary with allowed status and metadata
        """
        now = int(time.time() * 1000)  # Current time in milliseconds
        ttl = 3600  # 1 hour TTL
        
        result = await self.redis.evalsha(
            self._lua_script,
            1, key,
            config.capacity,
            int(config.refill_rate * 1000),  # Convert to per-millisecond
            tokens,
            now,
            ttl
        )
        
        return {
            "allowed": bool(result[0]),
            "remaining": result[1],
            "reset_time": result[2],
            "current_tokens": result[3],
            "retry_after": result[4] if len(result) > 4 else None
        }
    
    async def get_bucket_state(self, key: str) -> dict:
        """Get current bucket state without consuming tokens."""
        state = await self.redis.hmget(key, 'tokens', 'last_update')
        return {
            "tokens": float(state[0]) if state[0] else None,
            "last_update": int(state[1]) if state[1] else None
        }
    
    async def reset_bucket(self, key: str) -> bool:
        """Reset bucket to initial state."""
        return await self.redis.delete(key) > 0


class BurstManager:
    """
    Manages burst capacity for different endpoint types.
    """
    
    # Burst configurations by endpoint category
    BURST_CONFIGS = {
        "search": {"multiplier": 2.0, "duration_seconds": 10},
        "data": {"multiplier": 1.5, "duration_seconds": 5},
        "webhook": {"multiplier": 2.5, "duration_seconds": 15},
        "file_download": {"multiplier": 3.0, "duration_seconds": 20},
    }
    
    def __init__(self, token_limiter: TokenBucketRateLimiter):
        self.limiter = token_limiter
    
    def get_burst_config(
        self,
        base_limit: int,
        category: str
    ) -> TokenBucketConfig:
        """
        Get token bucket config with burst support.
        
        Args:
            base_limit: Base requests per minute
            category: Endpoint category
        
        Returns:
            TokenBucketConfig with burst capacity
        """
        burst_config = self.BURST_CONFIGS.get(category, {"multiplier": 1.0})
        
        capacity = int(base_limit * burst_config["multiplier"])
        refill_rate = base_limit / 60.0  # per second
        
        return TokenBucketConfig(
            capacity=capacity,
            refill_rate=refill_rate
        )
    
    async def check_with_burst(
        self,
        key: str,
        base_limit: int,
        category: str
    ) -> dict:
        """
        Check rate limit with burst allowance.
        
        Args:
            key: Rate limit key
            base_limit: Base rate limit
            category: Endpoint category
        
        Returns:
            Rate limit check result
        """
        config = self.get_burst_config(base_limit, category)
        return await self.limiter.consume(key, config)
```

### 8.2 Burst Handling Example

```python
# Example: Search endpoint with burst support
"""
Example implementation of burst-capable search endpoint.
"""

from fastapi import APIRouter, Request, Depends, HTTPException
from rate_limiter.token_bucket import TokenBucketRateLimiter, BurstManager

token_limiter = TokenBucketRateLimiter(redis_client)
burst_manager = BurstManager(token_limiter)

@router.get("/search")
async def search(
    request: Request,
    query: str,
    user: User = Depends(get_current_user)
):
    """
    Search endpoint with burst support.
    
    - Base limit: 30 requests/minute
    - Burst capacity: 60 requests (2x multiplier)
    - Refill rate: 0.5 tokens/second
    """
    user_id = user.id if user else request.client.host
    key = f"burst:search:{user_id}"
    
    # Check rate limit with burst
    result = await burst_manager.check_with_burst(
        key=key,
        base_limit=30,  # per minute
        category="search"
    )
    
    if not result["allowed"]:
        raise HTTPException(
            status_code=429,
            headers={"Retry-After": str(result.get("retry_after", 60))},
            detail="Rate limit exceeded. Please slow down."
        )
    
    # Add rate limit headers
    headers = {
        "X-RateLimit-Limit": "30",
        "X-RateLimit-Remaining": str(result["remaining"]),
        "X-RateLimit-Reset": str(result["reset_time"]),
        "X-RateLimit-Burst": "60",
    }
    
    # Perform search
    results = await perform_search(query)
    
    return JSONResponse(
        content=results,
        headers=headers
    )
```

---

## 9. Whitelisting

### 9.1 Whitelist Configuration

```python
# config/whitelist.py
"""
Rate limit whitelist configuration for Smart Dairy API.
"""

from typing import Set, Dict, List, Optional
from dataclasses import dataclass
from ipaddress import ip_network, ip_address
import re


@dataclass
class WhitelistEntry:
    """Single whitelist entry."""
    type: str  # 'ip', 'user', 'service', 'api_key', 'path'
    value: str
    description: str
    expires_at: Optional[int] = None  # Unix timestamp
    created_by: str = "system"


class RateLimitWhitelist:
    """
    Manages whitelist entries for rate limiting exemptions.
    """
    
    def __init__(self):
        self._ips: Set[str] = set()
        self._ip_networks: List[ip_network] = []
        self._users: Set[str] = set()
        self._services: Set[str] = set()
        self._api_keys: Set[str] = set()
        self._paths: List[re.Pattern] = []
        self._entries: Dict[str, WhitelistEntry] = {}
    
    def add_ip(self, ip: str, description: str = "", created_by: str = "system") -> bool:
        """Add IP address to whitelist."""
        try:
            # Check if it's a network
            if '/' in ip:
                network = ip_network(ip, strict=False)
                self._ip_networks.append(network)
            else:
                # Validate single IP
                ip_address(ip)
                self._ips.add(ip)
            
            self._entries[f"ip:{ip}"] = WhitelistEntry(
                type="ip",
                value=ip,
                description=description,
                created_by=created_by
            )
            return True
        except ValueError:
            return False
    
    def add_user(self, user_id: str, description: str = "", created_by: str = "system") -> None:
        """Add user to whitelist."""
        self._users.add(user_id)
        self._entries[f"user:{user_id}"] = WhitelistEntry(
            type="user",
            value=user_id,
            description=description,
            created_by=created_by
        )
    
    def add_service(self, service_name: str, description: str = "", created_by: str = "system") -> None:
        """Add internal service to whitelist."""
        self._services.add(service_name)
        self._entries[f"service:{service_name}"] = WhitelistEntry(
            type="service",
            value=service_name,
            description=description,
            created_by=created_by
        )
    
    def add_api_key(self, api_key: str, description: str = "", created_by: str = "system") -> None:
        """Add API key to whitelist."""
        self._api_keys.add(api_key)
        self._entries[f"api_key:{api_key}"] = WhitelistEntry(
            type="api_key",
            value=api_key,
            description=description,
            created_by=created_by
        )
    
    def add_path_pattern(self, pattern: str, description: str = "", created_by: str = "system") -> None:
        """Add path pattern to whitelist."""
        compiled = re.compile(pattern)
        self._paths.append(compiled)
        self._entries[f"path:{pattern}"] = WhitelistEntry(
            type="path",
            value=pattern,
            description=description,
            created_by=created_by
        )
    
    def is_ip_whitelisted(self, ip: str) -> bool:
        """Check if IP is whitelisted."""
        # Check exact match
        if ip in self._ips:
            return True
        
        # Check network membership
        try:
            client_ip = ip_address(ip)
            for network in self._ip_networks:
                if client_ip in network:
                    return True
        except ValueError:
            pass
        
        return False
    
    def is_user_whitelisted(self, user_id: str) -> bool:
        """Check if user is whitelisted."""
        return user_id in self._users
    
    def is_service_whitelisted(self, service_name: str) -> bool:
        """Check if service is whitelisted."""
        return service_name in self._services
    
    def is_api_key_whitelisted(self, api_key: str) -> bool:
        """Check if API key is whitelisted."""
        return api_key in self._api_keys
    
    def is_path_whitelisted(self, path: str) -> bool:
        """Check if path matches whitelist pattern."""
        for pattern in self._paths:
            if pattern.match(path):
                return True
        return False
    
    def remove_entry(self, key: str) -> bool:
        """Remove whitelist entry by key."""
        if key not in self._entries:
            return False
        
        entry = self._entries[key]
        
        if entry.type == "ip":
            self._ips.discard(entry.value)
            # Remove from networks if present
            self._ip_networks = [n for n in self._ip_networks if str(n) != entry.value]
        elif entry.type == "user":
            self._users.discard(entry.value)
        elif entry.type == "service":
            self._services.discard(entry.value)
        elif entry.type == "api_key":
            self._api_keys.discard(entry.value)
        elif entry.type == "path":
            self._paths = [p for p in self._paths if p.pattern != entry.value]
        
        del self._entries[key]
        return True
    
    def get_all_entries(self) -> Dict[str, WhitelistEntry]:
        """Get all whitelist entries."""
        return self._entries.copy()
    
    def clear(self) -> None:
        """Clear all whitelist entries."""
        self._ips.clear()
        self._ip_networks.clear()
        self._users.clear()
        self._services.clear()
        self._api_keys.clear()
        self._paths.clear()
        self._entries.clear()


# Global whitelist instance
whitelist = RateLimitWhitelist()


def initialize_default_whitelist():
    """Initialize whitelist with default entries."""
    # Internal networks
    whitelist.add_ip("127.0.0.1", "Localhost", "system")
    whitelist.add_ip("10.0.0.0/8", "Private network 10.x", "system")
    whitelist.add_ip("172.16.0.0/12", "Private network 172.x", "system")
    whitelist.add_ip("192.168.0.0/16", "Private network 192.x", "system")
    
    # Internal services
    whitelist.add_service("metrics-collector", "Prometheus metrics", "system")
    whitelist.add_service("health-checker", "Health check service", "system")
    whitelist.add_service("backup-service", "Database backup service", "system")
    
    # Health and docs paths
    whitelist.add_path_pattern(r"^/health$", "Health check endpoint", "system")
    whitelist.add_path_pattern(r"^/ping$", "Ping endpoint", "system")
    whitelist.add_path_pattern(r"^/docs(/.*)?$", "API documentation", "system")
    whitelist.add_path_pattern(r"^/openapi\.json$", "OpenAPI schema", "system")
    whitelist.add_path_pattern(r"^/redoc$", "ReDoc documentation", "system")
```

### 9.2 Whitelist Middleware Integration

```python
# middleware/whitelist_middleware.py
"""
Middleware for applying whitelist exemptions.
"""

from fastapi import Request
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.types import ASGIApp

from config.whitelist import whitelist


class WhitelistMiddleware(BaseHTTPMiddleware):
    """
    Middleware to mark requests as exempt from rate limiting.
    Must run before RateLimitMiddleware.
    """
    
    def __init__(
        self,
        app: ASGIApp,
        check_api_key_header: str = "X-API-Key",
        check_service_header: str = "X-Service-Name"
    ):
        super().__init__(app)
        self.api_key_header = check_api_key_header
        self.service_header = check_service_header
    
    async def dispatch(self, request: Request, call_next):
        """Check whitelist and mark request if exempt."""
        
        # Check if already marked exempt (e.g., by auth middleware)
        if getattr(request.state, "rate_limit_exempt", False):
            return await call_next(request)
        
        # Check path
        path = request.url.path
        if whitelist.is_path_whitelisted(path):
            request.state.rate_limit_exempt = True
            request.state.exemption_reason = "path_whitelist"
            return await call_next(request)
        
        # Check IP
        client_ip = self._get_client_ip(request)
        if whitelist.is_ip_whitelisted(client_ip):
            request.state.rate_limit_exempt = True
            request.state.exemption_reason = "ip_whitelist"
            return await call_next(request)
        
        # Check API key
        api_key = request.headers.get(self.api_key_header)
        if api_key and whitelist.is_api_key_whitelisted(api_key):
            request.state.rate_limit_exempt = True
            request.state.exemption_reason = "api_key_whitelist"
            return await call_next(request)
        
        # Check service name
        service_name = request.headers.get(self.service_header)
        if service_name and whitelist.is_service_whitelisted(service_name):
            request.state.rate_limit_exempt = True
            request.state.exemption_reason = "service_whitelist"
            return await call_next(request)
        
        # Check user (set by auth middleware)
        user = getattr(request.state, "user", None)
        if user:
            user_id = user.get("id") or user.get("user_id")
            if user_id and whitelist.is_user_whitelisted(user_id):
                request.state.rate_limit_exempt = True
                request.state.exemption_reason = "user_whitelist"
                return await call_next(request)
            
            # Check admin role
            if user.get("role") == "super_admin":
                request.state.rate_limit_exempt = True
                request.state.exemption_reason = "super_admin"
                return await call_next(request)
        
        return await call_next(request)
    
    def _get_client_ip(self, request: Request) -> str:
        """Extract client IP from request."""
        forwarded = request.headers.get("X-Forwarded-For")
        if forwarded:
            return forwarded.split(',')[0].strip()
        
        real_ip = request.headers.get("X-Real-IP")
        if real_ip:
            return real_ip
        
        if request.client:
            return request.client.host
        
        return "unknown"
```

---

## 10. Rate Limit Tiers

### 10.1 Tier Definitions

| Tier | Monthly Price | Requests/Min | Daily Quota | Concurrent | Burst |
|------|--------------|--------------|-------------|------------|-------|
| **Anonymous** | Free | 50 | 1,000 | 5 | 50% |
| **Free** | Free | 100 | 10,000 | 10 | 100% |
| **Basic** | $29/mo | 200 | 50,000 | 25 | 150% |
| **Premium** | $99/mo | 500 | 200,000 | 50 | 200% |
| **Enterprise** | Custom | 1,000 | 1,000,000 | 100 | 300% |
| **Internal** | N/A | 2,000 | 10,000,000 | 200 | 500% |

### 10.2 Tier Implementation

```python
# tiers/tier_manager.py
"""
API tier management and enforcement.
"""

import time
from typing import Dict, Optional
from dataclasses import dataclass
from enum import Enum

import redis.asyncio as redis


class SubscriptionTier(Enum):
    """API subscription tiers."""
    ANONYMOUS = "anonymous"
    FREE = "free"
    BASIC = "basic"
    PREMIUM = "premium"
    ENTERPRISE = "enterprise"


@dataclass
class TierLimits:
    """Rate limits for a subscription tier."""
    requests_per_minute: int
    daily_quota: int
    concurrent_requests: int
    burst_multiplier: float
    max_file_size_mb: int
    max_bulk_records: int
    support_priority: str


TIER_CONFIGURATIONS: Dict[SubscriptionTier, TierLimits] = {
    SubscriptionTier.ANONYMOUS: TierLimits(
        requests_per_minute=50,
        daily_quota=1000,
        concurrent_requests=5,
        burst_multiplier=0.5,
        max_file_size_mb=10,
        max_bulk_records=100,
        support_priority="none"
    ),
    SubscriptionTier.FREE: TierLimits(
        requests_per_minute=100,
        daily_quota=10000,
        concurrent_requests=10,
        burst_multiplier=1.0,
        max_file_size_mb=50,
        max_bulk_records=500,
        support_priority="community"
    ),
    SubscriptionTier.BASIC: TierLimits(
        requests_per_minute=200,
        daily_quota=50000,
        concurrent_requests=25,
        burst_multiplier=1.5,
        max_file_size_mb=100,
        max_bulk_records=2000,
        support_priority="email"
    ),
    SubscriptionTier.PREMIUM: TierLimits(
        requests_per_minute=500,
        daily_quota=200000,
        concurrent_requests=50,
        burst_multiplier=2.0,
        max_file_size_mb=500,
        max_bulk_records=10000,
        support_priority="priority"
    ),
    SubscriptionTier.ENTERPRISE: TierLimits(
        requests_per_minute=1000,
        daily_quota=1000000,
        concurrent_requests=100,
        burst_multiplier=3.0,
        max_file_size_mb=2000,
        max_bulk_records=50000,
        support_priority="dedicated"
    ),
}


class DailyQuotaManager:
    """
    Manages daily quota tracking and enforcement.
    """
    
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
    
    async def check_and_consume(
        self,
        user_id: str,
        tier: SubscriptionTier,
        tokens: int = 1
    ) -> tuple[bool, dict]:
        """
        Check daily quota and consume tokens if available.
        
        Returns:
            Tuple of (allowed, metadata)
        """
        limits = TIER_CONFIGURATIONS[tier]
        key = f"daily_quota:{user_id}:{self._get_day_key()}"
        
        # Use Redis INCR for atomic increment
        current = await self.redis.incrby(key, tokens)
        
        # Set expiry if new key
        if current == tokens:
            await self.redis.expire(key, 86400)  # 24 hours
        
        allowed = current <= limits.daily_quota
        remaining = max(0, limits.daily_quota - current)
        reset_time = self._get_next_day_timestamp()
        
        return allowed, {
            "allowed": allowed,
            "current_usage": current,
            "daily_quota": limits.daily_quota,
            "remaining": remaining,
            "reset_time": reset_time
        }
    
    async def get_usage(self, user_id: str) -> dict:
        """Get current daily quota usage."""
        key = f"daily_quota:{user_id}:{self._get_day_key()}"
        current = int(await self.redis.get(key) or 0)
        
        return {
            "current": current,
            "reset_time": self._get_next_day_timestamp()
        }
    
    def _get_day_key(self) -> str:
        """Get current day identifier."""
        return time.strftime("%Y-%m-%d")
    
    def _get_next_day_timestamp(self) -> int:
        """Get Unix timestamp for next day start."""
        now = time.time()
        return int(now + (86400 - (now % 86400)))


class TierEnforcer:
    """
    Enforces tier-based limits across all dimensions.
    """
    
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
        self.quota_manager = DailyQuotaManager(redis_client)
    
    async def check_all_limits(
        self,
        user_id: str,
        tier: SubscriptionTier,
        endpoint_cost: int = 1
    ) -> dict:
        """
        Check all applicable limits for user.
        
        Returns:
            Comprehensive limit status
        """
        limits = TIER_CONFIGURATIONS[tier]
        
        # Check daily quota
        quota_allowed, quota_info = await self.quota_manager.check_and_consume(
            user_id, tier, endpoint_cost
        )
        
        # Check concurrent requests
        concurrent_key = f"concurrent:{user_id}"
        concurrent_count = int(await self.redis.get(concurrent_key) or 0)
        concurrent_allowed = concurrent_count < limits.concurrent_requests
        
        return {
            "tier": tier.value,
            "allowed": quota_allowed and concurrent_allowed,
            "quota": quota_info,
            "concurrent": {
                "current": concurrent_count,
                "limit": limits.concurrent_requests,
                "available": limits.concurrent_requests - concurrent_count
            },
            "limits": {
                "rpm": limits.requests_per_minute,
                "daily": limits.daily_quota,
                "concurrent": limits.concurrent_requests,
                "burst_multiplier": limits.burst_multiplier
            }
        }
    
    async def acquire_concurrent_slot(self, user_id: str, tier: SubscriptionTier) -> bool:
        """Acquire concurrent request slot."""
        limits = TIER_CONFIGURATIONS[tier]
        key = f"concurrent:{user_id}"
        
        current = await self.redis.incr(key)
        
        if current > limits.concurrent_requests:
            await self.redis.decr(key)
            return False
        
        return True
    
    async def release_concurrent_slot(self, user_id: str) -> None:
        """Release concurrent request slot."""
        key = f"concurrent:{user_id}"
        await self.redis.decr(key)
```

---

## 11. Monitoring & Alerting

### 11.1 Metrics Collection

```python
# monitoring/rate_limit_metrics.py
"""
Metrics collection for rate limiting monitoring.
"""

import time
from typing import Dict, Optional
from dataclasses import dataclass, asdict
from collections import defaultdict
import json

from prometheus_client import Counter, Histogram, Gauge, Info


# Prometheus metrics
RATE_LIMIT_HITS = Counter(
    'smart_dairy_rate_limit_hits_total',
    'Total rate limit hits',
    ['endpoint', 'user_tier', 'limit_type']
)

RATE_LIMIT_CURRENT = Gauge(
    'smart_dairy_rate_limit_current',
    'Current rate limit usage',
    ['user_id', 'endpoint']
)

RATE_LIMIT_VIOLATIONS = Counter(
    'smart_dairy_rate_limit_violations_total',
    'Total rate limit violations (429s)',
    ['endpoint', 'user_tier', 'ip_range']
)

RATE_LIMIT_LATENCY = Histogram(
    'smart_dairy_rate_limit_check_duration_seconds',
    'Rate limit check latency',
    buckets=[.001, .005, .01, .025, .05, .1, .25, .5, 1]
)

API_REQUESTS = Counter(
    'smart_dairy_api_requests_total',
    'Total API requests',
    ['endpoint', 'method', 'status', 'user_tier']
)

ABUSE_SCORE = Gauge(
    'smart_dairy_abuse_score',
    'Calculated abuse score for users',
    ['user_id', 'ip_address']
)


@dataclass
class RateLimitEvent:
    """Rate limit event for logging and analysis."""
    timestamp: float
    user_id: Optional[str]
    ip_address: str
    endpoint: str
    action: str  # 'hit', 'violation', 'reset'
    current_usage: int
    limit: int
    tier: str
    user_agent: Optional[str] = None


class RateLimitMonitor:
    """
    Monitor rate limiting activity and detect abuse.
    """
    
    def __init__(self, redis_client=None):
        self.redis = redis_client
        self.recent_events = []
        self.abuse_threshold = 0.8  # 80% of violations indicate abuse
        self._event_buffer = []
    
    def record_hit(
        self,
        user_id: Optional[str],
        ip_address: str,
        endpoint: str,
        current_usage: int,
        limit: int,
        tier: str = "unknown",
        user_agent: Optional[str] = None
    ) -> None:
        """Record a rate limit hit (within limit)."""
        event = RateLimitEvent(
            timestamp=time.time(),
            user_id=user_id,
            ip_address=ip_address,
            endpoint=endpoint,
            action='hit',
            current_usage=current_usage,
            limit=limit,
            tier=tier,
            user_agent=user_agent
        )
        
        # Update Prometheus metrics
        RATE_LIMIT_HITS.labels(
            endpoint=endpoint,
            user_tier=tier,
            limit_type='soft'
        ).inc()
        
        if user_id:
            RATE_LIMIT_CURRENT.labels(
                user_id=user_id,
                endpoint=endpoint
            ).set(current_usage)
        
        self._buffer_event(event)
    
    def record_violation(
        self,
        user_id: Optional[str],
        ip_address: str,
        endpoint: str,
        limit: int,
        tier: str = "unknown",
        user_agent: Optional[str] = None
    ) -> None:
        """Record a rate limit violation (429 response)."""
        event = RateLimitEvent(
            timestamp=time.time(),
            user_id=user_id,
            ip_address=ip_address,
            endpoint=endpoint,
            action='violation',
            current_usage=limit,
            limit=limit,
            tier=tier,
            user_agent=user_agent
        )
        
        # Update Prometheus metrics
        ip_range = self._get_ip_range(ip_address)
        RATE_LIMIT_VIOLATIONS.labels(
            endpoint=endpoint,
            user_tier=tier,
            ip_range=ip_range
        ).inc()
        
        # Calculate and update abuse score
        abuse_score = self._calculate_abuse_score(user_id, ip_address)
        ABUSE_SCORE.labels(
            user_id=user_id or 'anonymous',
            ip_address=ip_address
        ).set(abuse_score)
        
        self._buffer_event(event)
        
        # Check if this should trigger an alert
        if abuse_score > self.abuse_threshold:
            self._trigger_abuse_alert(user_id, ip_address, abuse_score)
    
    def record_api_request(
        self,
        endpoint: str,
        method: str,
        status_code: int,
        tier: str = "unknown"
    ) -> None:
        """Record general API request metrics."""
        API_REQUESTS.labels(
            endpoint=endpoint,
            method=method,
            status=str(status_code),
            user_tier=tier
        ).inc()
    
    def _calculate_abuse_score(
        self,
        user_id: Optional[str],
        ip_address: str
    ) -> float:
        """
        Calculate abuse score based on recent activity.
        
        Factors:
        - Frequency of violations
        - Time between requests
        - Diversity of endpoints
        - Pattern analysis
        """
        # Simple implementation - count recent violations
        recent_violations = sum(
            1 for e in self._event_buffer
            if e.action == 'violation'
            and (e.user_id == user_id or e.ip_address == ip_address)
            and time.time() - e.timestamp < 3600  # Last hour
        )
        
        # Normalize to 0-1 scale (10 violations = 100% abuse)
        return min(1.0, recent_violations / 10.0)
    
    def _get_ip_range(self, ip_address: str) -> str:
        """Get IP range for aggregation."""
        parts = ip_address.split('.')
        if len(parts) == 4:
            return f"{parts[0]}.{parts[1]}.0.0/16"
        return "unknown"
    
    def _buffer_event(self, event: RateLimitEvent) -> None:
        """Add event to buffer with cleanup."""
        self._event_buffer.append(event)
        
        # Clean old events (keep last hour)
        cutoff = time.time() - 3600
        self._event_buffer = [e for e in self._event_buffer if e.timestamp > cutoff]
    
    def _trigger_abuse_alert(
        self,
        user_id: Optional[str],
        ip_address: str,
        score: float
    ) -> None:
        """Trigger abuse detection alert."""
        alert_data = {
            "alert_type": "rate_limit_abuse",
            "severity": "high" if score > 0.9 else "medium",
            "user_id": user_id,
            "ip_address": ip_address,
            "abuse_score": score,
            "timestamp": time.time(),
            "recommendation": "Consider temporary block or CAPTCHA"
        }
        
        # Log alert
        print(f"[ALERT] Rate limit abuse detected: {json.dumps(alert_data)}")
        
        # Store in Redis for alerting system
        if self.redis:
            alert_key = f"alerts:rate_limit:{int(time.time())}"
            self.redis.setex(alert_key, 86400, json.dumps(alert_data))
    
    def get_statistics(self, hours: int = 24) -> dict:
        """Get rate limiting statistics."""
        cutoff = time.time() - (hours * 3600)
        
        events = [e for e in self._event_buffer if e.timestamp > cutoff]
        
        total_hits = sum(1 for e in events if e.action == 'hit')
        total_violations = sum(1 for e in events if e.action == 'violation')
        
        # Top violated endpoints
        endpoint_violations = defaultdict(int)
        for e in events:
            if e.action == 'violation':
                endpoint_violations[e.endpoint] += 1
        
        # Top offending IPs
        ip_violations = defaultdict(int)
        for e in events:
            if e.action == 'violation':
                ip_violations[e.ip_address] += 1
        
        return {
            "period_hours": hours,
            "total_hits": total_hits,
            "total_violations": total_violations,
            "violation_rate": total_violations / (total_hits + total_violations) if (total_hits + total_violations) > 0 else 0,
            "top_violated_endpoints": dict(sorted(
                endpoint_violations.items(),
                key=lambda x: x[1],
                reverse=True
            )[:10]),
            "top_offending_ips": dict(sorted(
                ip_violations.items(),
                key=lambda x: x[1],
                reverse=True
            )[:10])
        }
```

### 11.2 Alerting Configuration

```yaml
# monitoring/alerting_rules.yml
"""
Prometheus alerting rules for rate limiting.
"""

groups:
  - name: rate_limit_alerts
    interval: 30s
    rules:
      # High rate of 429 responses
      - alert: HighRateLimitViolations
        expr: |
          sum(rate(smart_dairy_rate_limit_violations_total[5m])) by (endpoint) > 10
        for: 2m
        labels:
          severity: warning
          team: platform
        annotations:
          summary: "High rate limit violations on {{ $labels.endpoint }}"
          description: "Endpoint {{ $labels.endpoint }} has {{ $value }} violations per second"

      # Potential DDoS attack
      - alert: PotentialDDoSAttack
        expr: |
          sum(rate(smart_dairy_rate_limit_violations_total[1m])) > 100
        for: 1m
        labels:
          severity: critical
          team: security
        annotations:
          summary: "Potential DDoS attack detected"
          description: "Total violation rate is {{ $value }} per second"

      # Abuse score threshold exceeded
      - alert: HighAbuseScore
        expr: |
          smart_dairy_abuse_score > 0.8
        for: 5m
        labels:
          severity: warning
          team: security
        annotations:
          summary: "High abuse score for user/IP"
          description: "Abuse score of {{ $value }} detected"

      # Redis rate limiter unavailable
      - alert: RateLimiterUnavailable
        expr: |
          rate_limit_check_duration_seconds_count == 0
        for: 1m
        labels:
          severity: critical
          team: platform
        annotations:
          summary: "Rate limiter appears unavailable"
          description: "No rate limit checks recorded in the last minute"

      # Elevated latency in rate limit checks
      - alert: RateLimitHighLatency
        expr: |
          histogram_quantile(0.95, rate(smart_dairy_rate_limit_check_duration_seconds_bucket[5m])) > 0.1
        for: 3m
        labels:
          severity: warning
          team: platform
        annotations:
          summary: "High latency in rate limit checks"
          description: "95th percentile latency is {{ $value }}s"
```

### 11.3 Monitoring Dashboard

```python
# monitoring/dashboard.py
"""
Monitoring dashboard for rate limiting visualization.
"""

from fastapi import APIRouter, Depends
from typing import Dict, List, Optional
from datetime import datetime, timedelta
import json

router = APIRouter(prefix="/admin/monitoring", tags=["Monitoring"])


@router.get("/rate-limits/dashboard")
async def get_rate_limit_dashboard(
    hours: int = 24,
    admin_user: User = Depends(get_admin_user)
) -> dict:
    """
    Get comprehensive rate limiting dashboard data.
    
    Returns:
        Dashboard metrics and statistics
    """
    from monitoring.rate_limit_metrics import rate_limit_monitor
    
    stats = rate_limit_monitor.get_statistics(hours)
    
    return {
        "summary": {
            "total_requests": stats["total_hits"] + stats["total_violations"],
            "allowed_requests": stats["total_hits"],
            "blocked_requests": stats["total_violations"],
            "block_rate": f"{stats['violation_rate'] * 100:.2f}%"
        },
        "top_violations": {
            "endpoints": stats["top_violated_endpoints"],
            "ip_addresses": stats["top_offending_ips"]
        },
        "alerts": await get_active_alerts(),
        "tier_usage": await get_tier_usage_stats(),
        "real_time": {
            "current_rpm": await get_current_rpm(),
            "active_concurrent": await get_active_concurrent()
        }
    }


@router.get("/rate-limits/users/{user_id}")
async def get_user_rate_limit_status(
    user_id: str,
    admin_user: User = Depends(get_admin_user)
) -> dict:
    """Get detailed rate limit status for a specific user."""
    
    # Get user's current tier
    user = await get_user_by_id(user_id)
    tier = get_user_tier(user)
    
    # Get daily quota usage
    quota_usage = await daily_quota_manager.get_usage(user_id)
    
    # Get recent violations
    recent_violations = await get_user_violations(user_id, hours=24)
    
    # Calculate abuse score
    abuse_score = rate_limit_monitor._calculate_abuse_score(user_id, "")
    
    return {
        "user_id": user_id,
        "tier": tier.value,
        "daily_quota": {
            "limit": TIER_CONFIGURATIONS[tier].daily_quota,
            "used": quota_usage["current"],
            "remaining": TIER_CONFIGURATIONS[tier].daily_quota - quota_usage["current"],
            "reset_at": quota_usage["reset_time"]
        },
        "rate_limits": {
            "requests_per_minute": TIER_CONFIGURATIONS[tier].requests_per_minute,
            "concurrent_limit": TIER_CONFIGURATIONS[tier].concurrent_requests,
            "burst_multiplier": TIER_CONFIGURATIONS[tier].burst_multiplier
        },
        "violations_24h": len(recent_violations),
        "abuse_score": abuse_score,
        "recent_violations": recent_violations[:10]
    }


@router.post("/rate-limits/users/{user_id}/reset")
async def reset_user_rate_limits(
    user_id: str,
    admin_user: User = Depends(get_admin_user)
) -> dict:
    """Reset all rate limits for a user."""
    
    # Reset daily quota
    await daily_quota_manager.reset_quota(user_id)
    
    # Reset all endpoint-specific limits
    pattern = f"rate_limit:user:{user_id}:*"
    keys = await redis_client.keys(pattern)
    for key in keys:
        await redis_client.delete(key)
    
    # Log admin action
    await log_admin_action(
        admin_user.id,
        "reset_rate_limits",
        {"user_id": user_id}
    )
    
    return {
        "status": "success",
        "message": f"Rate limits reset for user {user_id}",
        "keys_cleared": len(keys)
    }


async def get_current_rpm() -> int:
    """Get current requests per minute."""
    # Implementation would query metrics
    return 0  # Placeholder


async def get_active_concurrent() -> int:
    """Get number of active concurrent requests."""
    # Implementation would query Redis
    return 0  # Placeholder


async def get_active_alerts() -> List[dict]:
    """Get active rate limit alerts."""
    # Implementation would query alert store
    return []  # Placeholder


async def get_tier_usage_stats() -> dict:
    """Get usage statistics by tier."""
    # Implementation would aggregate metrics
    return {}  # Placeholder


async def get_user_violations(user_id: str, hours: int = 24) -> List[dict]:
    """Get recent violations for a user."""
    # Implementation would query event store
    return []  # Placeholder
```

---

## 12. Client Best Practices

### 12.1 Exponential Backoff Implementation

```python
# client/backoff_handler.py
"""
Client-side exponential backoff for handling rate limits.
Example implementation for Smart Dairy API clients.
"""

import time
import random
from typing import Callable, Optional
from dataclasses import dataclass
import requests


@dataclass
class RetryConfig:
    """Configuration for retry behavior."""
    max_retries: int = 5
    base_delay: float = 1.0
    max_delay: float = 60.0
    exponential_base: float = 2.0
    jitter: bool = True
    retry_on_429: bool = True
    retry_on_5xx: bool = True


class ExponentialBackoff:
    """
    Implements exponential backoff with jitter for API requests.
    """
    
    def __init__(self, config: Optional[RetryConfig] = None):
        self.config = config or RetryConfig()
    
    def calculate_delay(self, attempt: int, retry_after: Optional[int] = None) -> float:
        """
        Calculate delay for retry attempt.
        
        Args:
            attempt: Current retry attempt (0-indexed)
            retry_after: Server suggested retry delay (from Retry-After header)
        
        Returns:
            Delay in seconds
        """
        # Use server-provided delay if available
        if retry_after is not None:
            base_delay = retry_after
        else:
            # Calculate exponential delay
            base_delay = self.config.base_delay * (
                self.config.exponential_base ** attempt
            )
        
        # Cap at max delay
        base_delay = min(base_delay, self.config.max_delay)
        
        # Add jitter to avoid thundering herd
        if self.config.jitter:
            # Full jitter: random between 0 and base_delay
            delay = random.uniform(0, base_delay)
        else:
            delay = base_delay
        
        return delay
    
    def should_retry(self, status_code: int, attempt: int) -> bool:
        """Determine if request should be retried."""
        if attempt >= self.config.max_retries:
            return False
        
        if status_code == 429 and self.config.retry_on_429:
            return True
        
        if 500 <= status_code < 600 and self.config.retry_on_5xx:
            return True
        
        return False


class SmartDairyClient:
    """
    Example API client with built-in rate limit handling.
    """
    
    def __init__(
        self,
        api_key: str,
        base_url: str = "https://api.smartdairy.com",
        retry_config: Optional[RetryConfig] = None
    ):
        self.api_key = api_key
        self.base_url = base_url.rstrip('/')
        self.backoff = ExponentialBackoff(retry_config)
        self.session = requests.Session()
        self.session.headers.update({
            "Authorization": f"Bearer {api_key}",
            "Content-Type": "application/json"
        })
    
    def _make_request(
        self,
        method: str,
        endpoint: str,
        **kwargs
    ) -> requests.Response:
        """
        Make HTTP request with automatic retry on rate limit.
        """
        url = f"{self.base_url}{endpoint}"
        attempt = 0
        
        while True:
            response = self.session.request(method, url, **kwargs)
            
            # Check rate limit headers
            limit = response.headers.get('X-RateLimit-Limit')
            remaining = response.headers.get('X-RateLimit-Remaining')
            reset_time = response.headers.get('X-RateLimit-Reset')
            
            if limit and remaining:
                print(f"Rate limit: {remaining}/{limit} remaining")
            
            # Check if we should retry
            if self.backoff.should_retry(response.status_code, attempt):
                # Get retry delay from header or calculate
                retry_after = response.headers.get('Retry-After')
                retry_after_int = int(retry_after) if retry_after else None
                
                delay = self.backoff.calculate_delay(attempt, retry_after_int)
                
                print(f"Rate limited (429). Retrying in {delay:.2f}s...")
                time.sleep(delay)
                attempt += 1
                continue
            
            # Return response (success or non-retryable error)
            return response
    
    def get(self, endpoint: str, **kwargs) -> requests.Response:
        """Make GET request."""
        return self._make_request('GET', endpoint, **kwargs)
    
    def post(self, endpoint: str, **kwargs) -> requests.Response:
        """Make POST request."""
        return self._make_request('POST', endpoint, **kwargs)
    
    def put(self, endpoint: str, **kwargs) -> requests.Response:
        """Make PUT request."""
        return self._make_request('PUT', endpoint, **kwargs)
    
    def delete(self, endpoint: str, **kwargs) -> requests.Response:
        """Make DELETE request."""
        return self._make_request('DELETE', endpoint, **kwargs)


# Example usage
if __name__ == "__main__":
    client = SmartDairyClient(
        api_key="your_api_key_here",
        retry_config=RetryConfig(
            max_retries=5,
            base_delay=2.0,
            max_delay=300.0
        )
    )
    
    # This request will automatically retry on 429
    response = client.get("/api/v1/cows")
    print(response.json())
```

### 12.2 JavaScript/TypeScript Client Example

```typescript
// client/smart-dairy-client.ts
/**
 * TypeScript client for Smart Dairy API with rate limit handling.
 */

interface RateLimitHeaders {
  limit: number;
  remaining: number;
  resetTime: number;
  retryAfter?: number;
}

interface RetryConfig {
  maxRetries: number;
  baseDelay: number;
  maxDelay: number;
  exponentialBase: number;
  jitter: boolean;
}

const DEFAULT_RETRY_CONFIG: RetryConfig = {
  maxRetries: 5,
  baseDelay: 1000,
  maxDelay: 60000,
  exponentialBase: 2,
  jitter: true,
};

export class SmartDairyClient {
  private apiKey: string;
  private baseUrl: string;
  private retryConfig: RetryConfig;

  constructor(
    apiKey: string,
    baseUrl: string = "https://api.smartdairy.com",
    retryConfig: Partial<RetryConfig> = {}
  ) {
    this.apiKey = apiKey;
    this.baseUrl = baseUrl.replace(/\/$/, '');
    this.retryConfig = { ...DEFAULT_RETRY_CONFIG, ...retryConfig };
  }

  private calculateDelay(attempt: number, retryAfter?: number): number {
    // Use server-provided delay if available
    let baseDelay = retryAfter !== undefined 
      ? retryAfter * 1000 
      : this.retryConfig.baseDelay * Math.pow(this.retryConfig.exponentialBase, attempt);
    
    // Cap at max delay
    baseDelay = Math.min(baseDelay, this.retryConfig.maxDelay);
    
    // Add jitter
    if (this.retryConfig.jitter) {
      baseDelay = Math.random() * baseDelay;
    }
    
    return baseDelay;
  }

  private parseRateLimitHeaders(headers: Headers): RateLimitHeaders | null {
    const limit = headers.get('X-RateLimit-Limit');
    const remaining = headers.get('X-RateLimit-Remaining');
    const resetTime = headers.get('X-RateLimit-Reset');
    const retryAfter = headers.get('Retry-After');

    if (!limit || !remaining || !resetTime) {
      return null;
    }

    return {
      limit: parseInt(limit, 10),
      remaining: parseInt(remaining, 10),
      resetTime: parseInt(resetTime, 10),
      retryAfter: retryAfter ? parseInt(retryAfter, 10) : undefined,
    };
  }

  async request(
    endpoint: string,
    options: RequestInit = {}
  ): Promise<Response> {
    const url = `${this.baseUrl}${endpoint}`;
    const headers = new Headers(options.headers);
    headers.set('Authorization', `Bearer ${this.apiKey}`);
    headers.set('Content-Type', 'application/json');

    let attempt = 0;

    while (true) {
      const response = await fetch(url, { ...options, headers });

      // Parse rate limit headers
      const rateLimitInfo = this.parseRateLimitHeaders(response.headers);
      if (rateLimitInfo) {
        console.log(`Rate limit: ${rateLimitInfo.remaining}/${rateLimitInfo.limit} remaining`);
        
        // Warn if running low
        if (rateLimitInfo.remaining < rateLimitInfo.limit * 0.1) {
          console.warn('Rate limit running low!');
        }
      }

      // Check if we should retry
      if (response.status === 429 && attempt < this.retryConfig.maxRetries) {
        const delay = this.calculateDelay(attempt, rateLimitInfo?.retryAfter);
        console.log(`Rate limited. Retrying in ${delay}ms...`);
        await this.sleep(delay);
        attempt++;
        continue;
      }

      return response;
    }
  }

  private sleep(ms: number): Promise<void> {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  // Convenience methods
  async get(endpoint: string): Promise<Response> {
    return this.request(endpoint, { method: 'GET' });
  }

  async post(endpoint: string, body: unknown): Promise<Response> {
    return this.request(endpoint, { 
      method: 'POST', 
      body: JSON.stringify(body) 
    });
  }
}
```

### 12.3 Rate Limit Handling Best Practices

```markdown
## Client Best Practices Summary

### 1. Always Check Rate Limit Headers
Read and respect the following headers:
- `X-RateLimit-Remaining`: Stop making requests when approaching 0
- `X-RateLimit-Reset`: Schedule requests after reset time
- `Retry-After`: Wait at least this many seconds before retrying

### 2. Implement Exponential Backoff
When receiving 429 responses:
1. Wait for `Retry-After` seconds if provided
2. Otherwise, use exponential backoff starting at 1 second
3. Add random jitter (0-100% of calculated delay)
4. Cap maximum delay at 60 seconds
5. Maximum 5 retry attempts

### 3. Cache Responses When Possible
- Cache read-heavy endpoints (cattle data, reports)
- Respect Cache-Control headers
- Use ETags for conditional requests

### 4. Batch Requests
- Use bulk endpoints for multiple operations
- Combine related queries when possible
- Avoid polling; use webhooks instead

### 5. Handle Different Error Codes
| Code | Action |
|------|--------|
| 429 | Back off and retry with delay |
| 503 | Temporary outage, retry with backoff |
| 500 | Server error, contact support |

### 6. Monitor Your Usage
Track your request patterns:
- Log rate limit hits
- Alert when approaching limits
- Adjust tier if consistently hitting limits
```

---

## 13. Appendices

### Appendix A: Complete Code Examples

#### A.1 Full FastAPI Application with Rate Limiting

```python
# app.py - Complete example
"""
Complete Smart Dairy API with rate limiting.
"""

from fastapi import FastAPI, Request, Depends, HTTPException
from fastapi.responses import JSONResponse
from contextlib import asynccontextmanager
import redis.asyncio as redis

from rate_limiter.redis_rate_limiter import RedisRateLimiter, RateLimiterFactory
from middleware.rate_limit_middleware import RateLimitMiddleware
from middleware.whitelist_middleware import WhitelistMiddleware
from dependencies.rate_limit import RateLimitDependency
from config.rate_limits import get_endpoint_limit
from config.whitelist import whitelist, initialize_default_whitelist


# Initialize whitelist
initialize_default_whitelist()


@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan."""
    # Startup
    await RateLimiterFactory.get_limiter()
    yield
    # Shutdown
    await RateLimiterFactory.close_all()


app = FastAPI(
    title="Smart Dairy API",
    description="API with comprehensive rate limiting",
    version="1.0.0",
    lifespan=lifespan
)

# Add whitelist middleware (must be before rate limit)
app.add_middleware(WhitelistMiddleware)

# Add rate limiting middleware
app.add_middleware(
    RateLimitMiddleware,
    redis_url="redis://localhost:6380"
)


# Auth endpoints - 5/minute
@app.post("/api/v1/auth/login")
async def login(
    request: Request,
    credentials: dict,
    _: None = Depends(RateLimitDependency(5, 60, key_prefix="auth:login"))
):
    """Login endpoint with strict rate limiting."""
    # Implementation
    return {"token": "jwt_token_here"}


# General API - 100/minute
@app.get("/api/v1/cows")
async def list_cows(
    request: Request,
    _: None = Depends(RateLimitDependency(100, 60, burst_size=150, key_prefix="cows"))
):
    """List cows endpoint with burst support."""
    return {"cows": []}


# Search - 30/minute
@app.get("/api/v1/search")
async def search(
    request: Request,
    q: str,
    _: None = Depends(RateLimitDependency(30, 60, burst_size=50, key_prefix="search"))
):
    """Search endpoint with moderate limits."""
    return {"results": []}


# Bulk operations - 10/minute
@app.post("/api/v1/bulk/import")
async def bulk_import(
    request: Request,
    data: list,
    _: None = Depends(RateLimitDependency(10, 60, key_prefix="bulk:import"))
):
    """Bulk import with strict limits."""
    return {"imported": len(data)}


@app.get("/health")
async def health_check():
    """Health check (exempt from rate limiting)."""
    return {"status": "healthy"}


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
```

#### A.2 Docker Compose Setup

```yaml
# docker-compose.yml
version: '3.8'

services:
  api:
    build: .
    ports:
      - "8000:8000"
    environment:
      - REDIS_URL=redis://redis:6379
      - RATE_LIMIT_ENABLED=true
    depends_on:
      - redis
    networks:
      - smart-dairy

  redis:
    image: redis:7-alpine
    command: >
      redis-server
      --maxmemory 256mb
      --maxmemory-policy allkeys-lru
      --appendonly yes
    volumes:
      - redis-data:/data
    networks:
      - smart-dairy

  prometheus:
    image: prom/prometheus:latest
    volumes:
      - ./monitoring/prometheus.yml:/etc/prometheus/prometheus.yml
    ports:
      - "9090:9090"
    networks:
      - smart-dairy

  grafana:
    image: grafana/grafana:latest
    ports:
      - "3000:3000"
    volumes:
      - grafana-data:/var/lib/grafana
    networks:
      - smart-dairy

volumes:
  redis-data:
  grafana-data:

networks:
  smart-dairy:
    driver: bridge
```

### Appendix B: Configuration Reference

```yaml
# config/rate_limit_config.yml
rate_limiting:
  enabled: true
  redis:
    url: "redis://localhost:6380"
    connection_pool_size: 20
    socket_timeout: 5
    socket_connect_timeout: 5
  
  defaults:
    algorithm: "sliding_window"
    window_seconds: 60
    skip_on_redis_error: true
  
  endpoints:
    auth:
      login:
        requests: 5
        window_seconds: 60
        algorithm: "sliding_window"
      register:
        requests: 3
        window_seconds: 60
      refresh:
        requests: 10
        window_seconds: 60
    
    general:
      requests: 100
      window_seconds: 60
      burst_size: 150
      algorithm: "token_bucket"
    
    search:
      requests: 30
      window_seconds: 60
      burst_size: 50
    
    bulk:
      requests: 10
      window_seconds: 60
      algorithm: "sliding_window"
  
  tiers:
    anonymous:
      multiplier: 0.5
      daily_quota: 1000
    free:
      multiplier: 1.0
      daily_quota: 10000
    basic:
      multiplier: 2.0
      daily_quota: 50000
    premium:
      multiplier: 5.0
      daily_quota: 200000
    enterprise:
      multiplier: 10.0
      daily_quota: 1000000
  
  whitelist:
    ips:
      - "127.0.0.1"
      - "10.0.0.0/8"
      - "172.16.0.0/12"
      - "192.168.0.0/16"
    paths:
      - "^/health$"
      - "^/ping$"
      - "^/docs"
      - "^/openapi"
    services:
      - "metrics-collector"
      - "health-checker"
  
  monitoring:
    enabled: true
    metrics_port: 9090
    alert_thresholds:
      abuse_score: 0.8
      violation_rate: 10  # per minute
      ddos_threshold: 100  # violations per minute
```

### Appendix C: Error Response Schema

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "RateLimitError",
  "type": "object",
  "required": ["error", "message", "status_code"],
  "properties": {
    "error": {
      "type": "string",
      "enum": ["Rate limit exceeded", "Quota exceeded", "Concurrent limit exceeded"]
    },
    "message": {
      "type": "string",
      "description": "Human-readable error message"
    },
    "status_code": {
      "type": "integer",
      "enum": [429, 503]
    },
    "retry_after": {
      "type": "integer",
      "description": "Seconds to wait before retrying"
    },
    "limit": {
      "type": "integer",
      "description": "Request limit for this endpoint"
    },
    "window": {
      "type": "integer",
      "description": "Rate limit window in seconds"
    },
    "current_usage": {
      "type": "integer",
      "description": "Current request count in window"
    },
    "endpoint": {
      "type": "string",
      "description": "Endpoint identifier"
    },
    "tier": {
      "type": "string",
      "description": "User's API tier"
    },
    "daily_quota": {
      "type": "object",
      "properties": {
        "limit": {"type": "integer"},
        "used": {"type": "integer"},
        "remaining": {"type": "integer"},
        "reset_at": {"type": "integer"}
      }
    }
  }
}
```

### Appendix D: Testing Rate Limits

```python
# tests/test_rate_limits.py
"""
Tests for rate limiting functionality.
"""

import pytest
import asyncio
from httpx import AsyncClient


@pytest.mark.asyncio
async def test_auth_rate_limit(client: AsyncClient):
    """Test auth endpoint rate limiting (5/min)."""
    endpoint = "/api/v1/auth/login"
    
    # Make 5 requests (should succeed)
    for i in range(5):
        response = await client.post(endpoint, json={
            "username": f"user{i}",
            "password": "wrong"
        })
        assert response.status_code in [200, 401]  # Success or auth error
    
    # 6th request should be rate limited
    response = await client.post(endpoint, json={
        "username": "blocked",
        "password": "wrong"
    })
    assert response.status_code == 429
    assert "Retry-After" in response.headers


@pytest.mark.asyncio
async def test_rate_limit_headers(client: AsyncClient):
    """Test rate limit headers are present."""
    response = await client.get("/api/v1/cows")
    
    assert "X-RateLimit-Limit" in response.headers
    assert "X-RateLimit-Remaining" in response.headers
    assert "X-RateLimit-Reset" in response.headers


@pytest.mark.asyncio
async def test_tier_multipliers(client: AsyncClient, premium_user):
    """Test that premium users get higher limits."""
    # Premium users have 5x limits
    endpoint = "/api/v1/cows"
    
    # Make 100 requests as premium user (should succeed)
    for _ in range(100):
        response = await client.get(
            endpoint,
            headers={"Authorization": f"Bearer {premium_user.token}"}
        )
        assert response.status_code == 200


@pytest.mark.asyncio
async def test_whitelist_exemption(client: AsyncClient):
    """Test whitelisted IPs are exempt."""
    # Request from whitelisted IP
    response = await client.get(
        "/api/v1/cows",
        headers={"X-Forwarded-For": "127.0.0.1"}
    )
    
    # Should not have rate limit headers (exempt)
    assert "X-RateLimit-Limit" not in response.headers


@pytest.mark.asyncio
async def test_burst_allowance(client: AsyncClient):
    """Test token bucket burst capacity."""
    endpoint = "/api/v1/search"
    
    # Make 50 requests rapidly (burst capacity)
    responses = await asyncio.gather(*[
        client.get(f"{endpoint}?q=test{i}")
        for i in range(50)
    ])
    
    # All should succeed (within burst capacity of 50)
    success_count = sum(1 for r in responses if r.status_code == 200)
    assert success_count == 50
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | _______ |
| Reviewer | CTO | _________________ | _______ |
| Owner | Tech Lead | _________________ | _______ |

---

## Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Integration Engineer | Initial release |

---

**END OF DOCUMENT**

*This document contains proprietary and confidential information of Smart Dairy Ltd. Unauthorized distribution is prohibited.*
