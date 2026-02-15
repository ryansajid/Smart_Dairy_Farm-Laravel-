# SMART DAIRY LTD.
## ERROR HANDLING & LOGGING STANDARDS
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-014 |
| **Version** | 1.0 |
| **Date** | February 17, 2026 |
| **Author** | Tech Lead |
| **Owner** | Backend Team |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Exception Hierarchy](#2-exception-hierarchy)
3. [Error Handling Patterns](#3-error-handling-patterns)
4. [Logging Standards](#4-logging-standards)
5. [Structured Logging](#5-structured-logging)
6. [Error Monitoring](#6-error-monitoring)
7. [Alerting](#7-alerting)
8. [Appendices](#8-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines standards for error handling and logging across the Smart Dairy ERP system to ensure consistent error management, effective debugging, and proactive issue detection.

### 1.2 Scope

- Exception hierarchy and handling patterns
- Logging levels and formats
- Structured logging implementation
- Error tracking and monitoring
- Alerting thresholds

---

## 2. EXCEPTION HIERARCHY

### 2.1 Base Exception Classes

```python
# core/exceptions.py

class SmartDairyException(Exception):
    """Base exception for all Smart Dairy errors."""
    
    status_code = 500
    error_code = 'INTERNAL_ERROR'
    severity = 'error'
    
    def __init__(
        self,
        message: str,
        details: dict = None,
        original_error: Exception = None
    ):
        super().__init__(message)
        self.message = message
        self.details = details or {}
        self.original_error = original_error
        self.timestamp = datetime.utcnow()
    
    def to_dict(self) -> dict:
        return {
            'error_code': self.error_code,
            'message': self.message,
            'details': self.details,
            'severity': self.severity,
            'timestamp': self.timestamp.isoformat()
        }


# Validation Errors (4xx)
class ValidationError(SmartDairyException):
    """Input validation error."""
    status_code = 422
    error_code = 'VALIDATION_ERROR'
    severity = 'warning'


class BadRequestError(SmartDairyException):
    """Malformed request."""
    status_code = 400
    error_code = 'BAD_REQUEST'
    severity = 'warning'


class AuthenticationError(SmartDairyException):
    """Authentication failure."""
    status_code = 401
    error_code = 'AUTHENTICATION_ERROR'
    severity = 'warning'


class AuthorizationError(SmartDairyException):
    """Permission denied."""
    status_code = 403
    error_code = 'AUTHORIZATION_ERROR'
    severity = 'warning'


class NotFoundError(SmartDairyException):
    """Resource not found."""
    status_code = 404
    error_code = 'NOT_FOUND'
    severity = 'warning'


class ConflictError(SmartDairyException):
    """Resource conflict."""
    status_code = 409
    error_code = 'CONFLICT'
    severity = 'warning'


class RateLimitError(SmartDairyException):
    """Rate limit exceeded."""
    status_code = 429
    error_code = 'RATE_LIMIT_EXCEEDED'
    severity = 'warning'


# Server Errors (5xx)
class DatabaseError(SmartDairyException):
    """Database operation error."""
    status_code = 500
    error_code = 'DATABASE_ERROR'
    severity = 'error'


class ExternalServiceError(SmartDairyException):
    """External API/service error."""
    status_code = 502
    error_code = 'EXTERNAL_SERVICE_ERROR'
    severity = 'error'


class TimeoutError(SmartDairyException):
    """Operation timeout."""
    status_code = 504
    error_code = 'TIMEOUT_ERROR'
    severity = 'error'


class ConfigurationError(SmartDairyException):
    """System configuration error."""
    status_code = 500
    error_code = 'CONFIGURATION_ERROR'
    severity = 'critical'


# Business Logic Errors
class BusinessRuleError(SmartDairyException):
    """Business rule violation."""
    status_code = 422
    error_code = 'BUSINESS_RULE_ERROR'
    severity = 'warning'


class InsufficientStockError(BusinessRuleError):
    """Insufficient stock for operation."""
    error_code = 'INSUFFICIENT_STOCK'


class CreditLimitExceededError(BusinessRuleError):
    """Customer credit limit exceeded."""
    error_code = 'CREDIT_LIMIT_EXCEEDED'
```

---

## 3. ERROR HANDLING PATTERNS

### 3.1 Global Exception Handler (FastAPI)

```python
# api/error_handlers.py
from fastapi import Request
from fastapi.responses import JSONResponse
import traceback
import logging

logger = logging.getLogger(__name__)

async def global_exception_handler(request: Request, exc: Exception):
    """Global exception handler for all unhandled exceptions."""
    
    # Get request ID for correlation
    request_id = getattr(request.state, 'request_id', 'unknown')
    
    if isinstance(exc, SmartDairyException):
        # Known exception - log with appropriate level
        log_method = getattr(logger, exc.severity)
        log_method(
            f"Application error: {exc.message}",
            extra={
                'error_code': exc.error_code,
                'request_id': request_id,
                'user_id': getattr(request.state, 'user_id', None),
                'details': exc.details
            }
        )
        
        return JSONResponse(
            status_code=exc.status_code,
            content={
                'success': False,
                'error': exc.to_dict(),
                'request_id': request_id
            }
        )
    
    # Unknown exception - log full stack trace
    logger.critical(
        f"Unhandled exception: {str(exc)}",
        extra={
            'request_id': request_id,
            'traceback': traceback.format_exc(),
            'path': request.url.path,
            'method': request.method
        }
    )
    
    # Return generic error to client (don't expose internals)
    return JSONResponse(
        status_code=500,
        content={
            'success': False,
            'error': {
                'error_code': 'INTERNAL_ERROR',
                'message': 'An unexpected error occurred',
                'request_id': request_id
            }
        }
    )


# Register handlers
app.add_exception_handler(SmartDairyException, global_exception_handler)
app.add_exception_handler(Exception, global_exception_handler)
```

### 3.2 Service Layer Error Handling

```python
# services/base_service.py
from contextlib import contextmanager
from typing import Type, Tuple

@contextmanager
def handle_service_errors(
    operation: str,
    expected_errors: Tuple[Type[Exception], ...] = (),
    reraise_as: Type[SmartDairyException] = ExternalServiceError
):
    """
    Context manager for consistent service error handling.
    
    Usage:
        with handle_service_errors('payment processing'):
            result = payment_gateway.charge()
    """
    try:
        yield
    except SmartDairyException:
        # Already handled, re-raise
        raise
    except expected_errors as e:
        # Expected error - convert to our exception
        raise reraise_as(
            message=f"Error during {operation}: {str(e)}",
            details={'operation': operation, 'original_error': str(e)}
        ) from e
    except Exception as e:
        # Unexpected error - log and convert
        logger.exception(f"Unexpected error during {operation}")
        raise reraise_as(
            message=f"Unexpected error during {operation}",
            details={'operation': operation}
        ) from e


# Usage example
class PaymentService:
    def process_payment(self, order_id: int, payment_data: dict):
        with handle_service_errors(
            operation='payment processing',
            expected_errors=(PaymentGatewayError, NetworkError)
        ):
            # Payment logic here
            result = self.gateway.process(payment_data)
            return result
```

### 3.3 Retry with Exponential Backoff

```python
# utils/retry.py
import time
import random
from functools import wraps
from typing import Callable, Type, Tuple

def retry_with_backoff(
    max_retries: int = 3,
    base_delay: float = 1.0,
    max_delay: float = 60.0,
    exceptions: Tuple[Type[Exception], ...] = (Exception,),
    on_retry: Callable = None
):
    """
    Decorator for retrying operations with exponential backoff.
    
    Args:
        max_retries: Maximum number of retry attempts
        base_delay: Initial delay in seconds
        max_delay: Maximum delay in seconds
        exceptions: Tuple of exceptions to catch and retry
        on_retry: Callback function called on each retry
    """
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            last_exception = None
            
            for attempt in range(max_retries + 1):
                try:
                    return func(*args, **kwargs)
                except exceptions as e:
                    last_exception = e
                    
                    if attempt == max_retries:
                        raise
                    
                    # Calculate delay with jitter
                    delay = min(
                        base_delay * (2 ** attempt) + random.uniform(0, 1),
                        max_delay
                    )
                    
                    logger.warning(
                        f"Retry {attempt + 1}/{max_retries} for {func.__name__} "
                        f"after {delay:.2f}s: {str(e)}"
                    )
                    
                    if on_retry:
                        on_retry(attempt, e, delay)
                    
                    time.sleep(delay)
            
            raise last_exception
        
        return wrapper
    return decorator


# Usage
@retry_with_backoff(
    max_retries=3,
    base_delay=2.0,
    exceptions=(ExternalServiceError, TimeoutError)
)
def call_external_api(endpoint: str, data: dict):
    response = requests.post(endpoint, json=data, timeout=30)
    response.raise_for_status()
    return response.json()
```

---

## 4. LOGGING STANDARDS

### 4.1 Log Levels

| Level | Usage | Examples |
|-------|-------|----------|
| **DEBUG** | Detailed diagnostic info | Function entry/exit, variable values |
| **INFO** | General operational info | Request processed, job completed |
| **WARNING** | Unexpected but handled | Deprecated API use, retry attempt |
| **ERROR** | Operation failed | Database error, API failure |
| **CRITICAL** | System failure | Service down, data corruption |

### 4.2 Logger Configuration

```python
# core/logging_config.py
import logging
import logging.handlers
import json
from datetime import datetime
from pythonjsonlogger import jsonlogger

class CustomJsonFormatter(jsonlogger.JsonFormatter):
    """Custom JSON formatter for structured logging."""
    
    def add_fields(self, log_record, record, message_dict):
        super(CustomJsonFormatter, self).add_fields(log_record, record, message_dict)
        
        # Add timestamp
        log_record['timestamp'] = datetime.utcnow().isoformat()
        log_record['level'] = record.levelname
        log_record['logger'] = record.name
        
        # Add source location
        log_record['source'] = {
            'file': record.filename,
            'line': record.lineno,
            'function': record.funcName
        }
        
        # Add correlation IDs if available
        from contextvars import ContextVar
        request_id = ContextVar('request_id', default=None).get()
        if request_id:
            log_record['request_id'] = request_id


def setup_logging():
    """Configure application logging."""
    
    # Root logger
    root_logger = logging.getLogger()
    root_logger.setLevel(logging.INFO)
    
    # Console handler (JSON for production, text for development)
    console_handler = logging.StreamHandler()
    
    if os.getenv('ENV') == 'production':
        formatter = CustomJsonFormatter(
            '%(timestamp)s %(level)s %(name)s %(message)s'
        )
    else:
        formatter = logging.Formatter(
            '%(asctime)s - %(name)s - %(levelname)s - %(message)s'
        )
    
    console_handler.setFormatter(formatter)
    root_logger.addHandler(console_handler)
    
    # File handler for errors
    error_handler = logging.handlers.RotatingFileHandler(
        'logs/errors.log',
        maxBytes=10*1024*1024,  # 10MB
        backupCount=5
    )
    error_handler.setLevel(logging.ERROR)
    error_handler.setFormatter(CustomJsonFormatter())
    root_logger.addHandler(error_handler)
    
    # Separate audit log for security events
    audit_handler = logging.handlers.RotatingFileHandler(
        'logs/audit.log',
        maxBytes=100*1024*1024,  # 100MB
        backupCount=10
    )
    audit_handler.setLevel(logging.INFO)
    audit_handler.addFilter(lambda r: r.name.startswith('audit'))
    audit_handler.setFormatter(CustomJsonFormatter())
    
    audit_logger = logging.getLogger('audit')
    audit_logger.addHandler(audit_handler)
    audit_logger.setLevel(logging.INFO)
```

### 4.3 Logging Best Practices

```python
# Good logging examples

# 1. Use structured logging
logger.info(
    "Order processed",
    extra={
        'order_id': order.id,
        'customer_id': order.customer_id,
        'amount': order.total,
        'duration_ms': processing_time
    }
)

# 2. Log at appropriate levels
logger.debug("Processing item %s", item_id)  # Detailed
logger.info("Batch processed: %d items", count)  # General
logger.warning("Retry attempt %d for %s", attempt, operation)  # Warning
logger.error("Failed to process order %s: %s", order_id, error)  # Error

# 3. Don't log sensitive data
# BAD:
logger.info("User login: %s, password: %s", username, password)

# GOOD:
logger.info("User login attempt", extra={'username': username})

# 4. Include context for errors
try:
    result = process_data(data)
except Exception as e:
    logger.exception(
        "Data processing failed",
        extra={
            'data_id': data.id,
            'data_type': data.type,
            'processing_step': current_step
        }
    )
    raise

# 5. Use audit logger for security events
audit_logger = logging.getLogger('audit')
audit_logger.info(
    "User login",
    extra={
        'event_type': 'user_login',
        'user_id': user.id,
        'ip_address': request.client_ip,
        'success': True
    }
)
```

---

## 5. STRUCTURED LOGGING

### 5.1 Log Entry Schema

```json
{
  "timestamp": "2026-02-17T10:30:00.000Z",
  "level": "ERROR",
  "logger": "smart_dairy.payment",
  "message": "Payment processing failed",
  "request_id": "req_abc123",
  "trace_id": "trace_xyz789",
  "user_id": 12345,
  "source": {
    "file": "payment_service.py",
    "line": 156,
    "function": "process_payment"
  },
  "context": {
    "order_id": 98765,
    "payment_method": "bkash",
    "amount": 1500.00,
    "currency": "BDT"
  },
  "error": {
    "type": "PaymentGatewayError",
    "code": "INSUFFICIENT_BALANCE",
    "message": "Customer has insufficient balance",
    "stack_trace": "..."
  },
  "duration_ms": 2450,
  "environment": "production",
  "service": "payment-service",
  "version": "1.2.3"
}
```

---

## 6. ERROR MONITORING

### 6.1 Sentry Integration

```python
# core/sentry.py
import sentry_sdk
from sentry_sdk.integrations.fastapi import FastApiIntegration
from sentry_sdk.integrations.redis import RedisIntegration
from sentry_sdk.integrations.sqlalchemy import SqlalchemyIntegration

def init_sentry():
    """Initialize Sentry for error tracking."""
    sentry_sdk.init(
        dsn=os.getenv('SENTRY_DSN'),
        environment=os.getenv('ENV', 'development'),
        release=os.getenv('VERSION', 'unknown'),
        integrations=[
            FastApiIntegration(),
            RedisIntegration(),
            SqlalchemyIntegration(),
        ],
        traces_sample_rate=0.1,  # 10% performance tracing
        before_send=before_send_event,
    )

def before_send_event(event, hint):
    """Filter or modify events before sending to Sentry."""
    # Don't send certain error types
    if 'exception' in event:
        exception = event['exception']['values'][0]
        if exception['type'] == 'ValidationError':
            return None  # Don't send validation errors
    
    # Scrub sensitive data
    if 'request' in event and 'data' in event['request']:
        scrub_sensitive_data(event['request']['data'])
    
    return event
```

---

## 7. ALERTING

### 7.1 Alert Rules

| Condition | Severity | Channel | Threshold |
|-----------|----------|---------|-----------|
| Error rate > 5% | Critical | PagerDuty + Slack | 5 minutes |
| Error rate > 1% | Warning | Slack | 10 minutes |
| Critical exception | Critical | PagerDuty + Email | Immediate |
| DB connection errors | Critical | PagerDuty | 3 errors / 5 min |
| Response time > 5s | Warning | Slack | 10 minutes |
| Disk usage > 90% | Critical | PagerDuty + Email | Immediate |

### 7.2 Alert Configuration

```yaml
# alerting/rules.yml
alerts:
  - name: high_error_rate
    condition: |
      sum(rate(http_requests_total{status=~"5.."}[5m])) 
      / 
      sum(rate(http_requests_total[5m])) > 0.05
    for: 5m
    severity: critical
    channels: [pagerduty, slack]
    
  - name: payment_failures
    condition: |
      rate(payment_failures_total[5m]) > 0.1
    for: 2m
    severity: critical
    channels: [pagerduty]
    
  - name: slow_responses
    condition: |
      histogram_quantile(0.95, 
        rate(http_request_duration_seconds_bucket[5m])
      ) > 5
    for: 10m
    severity: warning
    channels: [slack]
```

---

## 8. APPENDICES

### Appendix A: Log Retention Policy

| Log Type | Retention | Storage |
|----------|-----------|---------|
| Application logs | 30 days | CloudWatch/Splunk |
| Error logs | 90 days | S3 Glacier |
| Audit logs | 7 years | S3 Glacier Deep |
| Access logs | 30 days | S3 Standard-IA |

### Appendix B: Error Codes Reference

| Code | Description | HTTP Status |
|------|-------------|-------------|
| VALIDATION_ERROR | Input validation failed | 422 |
| AUTHENTICATION_ERROR | Invalid credentials | 401 |
| AUTHORIZATION_ERROR | Permission denied | 403 |
| NOT_FOUND | Resource not found | 404 |
| CONFLICT | Resource conflict | 409 |
| RATE_LIMIT_EXCEEDED | Too many requests | 429 |
| DATABASE_ERROR | Database operation failed | 500 |
| EXTERNAL_SERVICE_ERROR | External API failed | 502 |
| TIMEOUT_ERROR | Operation timed out | 504 |

---

**END OF ERROR HANDLING & LOGGING STANDARDS**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 17, 2026 | Tech Lead | Initial version |
