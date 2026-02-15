# SMART DAIRY LTD.
## BACKGROUND JOB PROCESSING SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-009 |
| **Version** | 1.0 |
| **Date** | February 12, 2026 |
| **Author** | Tech Lead |
| **Owner** | DevOps Lead |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Celery Architecture](#2-celery-architecture)
3. [Task Definitions](#3-task-definitions)
4. [Queue Configuration](#4-queue-configuration)
5. [Scheduling & Periodic Tasks](#5-scheduling--periodic-tasks)
6. [Error Handling & Retries](#6-error-handling--retries)
7. [Monitoring & Management](#7-monitoring--management)
8. [Performance Optimization](#8-performance-optimization)
9. [Appendices](#9-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document specifies the background job processing architecture using Celery for Smart Dairy's ERP system. It covers task definitions, queue management, scheduling, and monitoring of asynchronous operations.

### 1.2 Scope

- Asynchronous task processing
- Scheduled/periodic jobs
- Distributed task execution
- Task retry and error handling
- Queue management and prioritization

### 1.3 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| Task Queue | Celery | 5.3+ |
| Message Broker | Redis | 7.0+ |
| Result Backend | Redis | 7.0+ |
| Scheduler | Celery Beat | 5.3+ |
| Monitoring | Flower | 2.0+ |

---

## 2. CELERY ARCHITECTURE

### 2.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        CELERY TASK PROCESSING ARCHITECTURE                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PRODUCERS                                                                  │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │
│  │  Odoo ERP   │  │  API Gateway│  │  IoT Service│  │  Mobile App │        │
│  │             │  │             │  │             │  │  Backend    │        │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘        │
│         │                │                │                │               │
│         └────────────────┴────────────────┴────────────────┘               │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    REDIS MESSAGE BROKER                              │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │  celery      │  │  celery:low  │  │  celery:high │  Queues      │   │
│  │  │  (default)   │  │  (low prio)  │  │  (high prio) │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │  celery:iot  │  │celery:email  │  │celery:report │              │   │
│  │  │  (IoT data)  │  │ (notifications)│ (generation) │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    CELERY WORKERS                                    │   │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐     │   │
│  │  │  Worker Pool 1  │  │  Worker Pool 2  │  │  Worker Pool 3  │     │   │
│  │  │  (General Tasks)│  │  (IoT Tasks)    │  │  (Email Tasks)  │     │   │
│  │  │  - 8 workers    │  │  - 4 workers    │  │  - 2 workers    │     │   │
│  │  │  - prefetch=4   │  │  - prefetch=1   │  │  - prefetch=1   │     │   │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    RESULT BACKEND (Redis)                            │   │
│  │  - Task results    - Task states    - Rate limits                  │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    CELERY BEAT (Scheduler)                           │   │
│  │  - Periodic tasks  - Cron jobs      - Interval tasks               │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Celery Configuration

```python
# celery_config.py
from celery import Celery
from kombu import Queue, Exchange

app = Celery('smart_dairy')

# Broker configuration
app.conf.broker_url = 'redis://localhost:6379/0'
app.conf.result_backend = 'redis://localhost:6379/1'

# Serialization
app.conf.task_serializer = 'json'
app.conf.result_serializer = 'json'
app.conf.accept_content = ['json']

# Timezone
app.conf.timezone = 'Asia/Dhaka'
app.conf.enable_utc = True

# Task settings
app.conf.task_track_started = True
app.conf.task_time_limit = 3600  # 1 hour hard limit
app.conf.task_soft_time_limit = 3300  # 55 minutes soft limit
app.conf.worker_prefetch_multiplier = 4
app.conf.worker_max_tasks_per_child = 1000

# Result settings
app.conf.result_expires = 86400  # 24 hours
app.conf.result_extended = True

# Queue configuration
app.conf.task_default_queue = 'celery'
app.conf.task_queues = (
    Queue('celery', Exchange('celery'), routing_key='celery'),
    Queue('celery:high', Exchange('celery:high'), routing_key='celery.high'),
    Queue('celery:low', Exchange('celery:low'), routing_key='celery.low'),
    Queue('celery:iot', Exchange('celery:iot'), routing_key='celery.iot'),
    Queue('celery:email', Exchange('celery:email'), routing_key='celery.email'),
    Queue('celery:report', Exchange('celery:report'), routing_key='celery.report'),
    Queue('celery:payment', Exchange('celery:payment'), routing_key='celery.payment'),
)

# Routing
app.conf.task_routes = {
    'tasks.iot.*': {'queue': 'celery:iot'},
    'tasks.email.*': {'queue': 'celery:email'},
    'tasks.report.*': {'queue': 'celery:report'},
    'tasks.payment.*': {'queue': 'celery:payment'},
    'tasks.*.high_priority': {'queue': 'celery:high'},
    'tasks.*.low_priority': {'queue': 'celery:low'},
}

# Retry settings
app.conf.task_default_retry_delay = 60  # 1 minute
app.conf.task_max_retries = 3
app.conf.task_retry_backoff = True
app.conf.task_retry_backoff_max = 600  # 10 minutes
app.conf.task_retry_jitter = True

# Broker connection
app.conf.broker_connection_retry_on_startup = True
app.conf.broker_connection_max_retries = 10
app.conf.broker_transport_options = {
    'visibility_timeout': 43200,  # 12 hours
}
```

---

## 3. TASK DEFINITIONS

### 3.1 Task Categories

| Category | Queue | Priority | Timeout | Max Retries |
|----------|-------|----------|---------|-------------|
| IoT Processing | celery:iot | High | 30s | 5 |
| Email Notifications | celery:email | Normal | 60s | 3 |
| SMS Notifications | celery:email | Normal | 30s | 3 |
| Report Generation | celery:report | Low | 1h | 2 |
| Payment Processing | celery:payment | Critical | 2m | 10 |
| Data Sync | celery:low | Low | 30m | 3 |
| Image Processing | celery:low | Low | 5m | 2 |
| Daily Aggregation | celery | Normal | 10m | 3 |

### 3.2 Task Implementation

```python
# tasks/__init__.py
from .iot_tasks import process_sensor_data, sync_device_status
from .email_tasks import send_email, send_bulk_emails
from .report_tasks import generate_report, export_data
from .payment_tasks import process_payment, verify_payment
from .sync_tasks import sync_odoo_data, cleanup_old_data

# tasks/iot_tasks.py
import logging
from celery import shared_task
from celery.exceptions import MaxRetriesExceededError, SoftTimeLimitExceeded

logger = logging.getLogger(__name__)


@shared_task(
    bind=True,
    queue='celery:iot',
    max_retries=5,
    default_retry_delay=10,
    time_limit=30,
    soft_time_limit=25
)
def process_sensor_data(self, device_id: str, readings: list):
    """
    Process IoT sensor data from devices.
    
    Args:
        device_id: Unique device identifier
        readings: List of sensor readings
    """
    try:
        logger.info(f"Processing sensor data from device {device_id}")
        
        # Validate readings
        validated_readings = validate_sensor_readings(readings)
        
        # Store in TimescaleDB
        store_readings_batch(device_id, validated_readings)
        
        # Check thresholds and trigger alerts
        check_thresholds(device_id, validated_readings)
        
        # Update device last seen
        update_device_status(device_id, 'online')
        
        return {
            'status': 'success',
            'device_id': device_id,
            'processed_count': len(validated_readings)
        }
        
    except SoftTimeLimitExceeded:
        logger.warning(f"Soft time limit exceeded for device {device_id}")
        # Process partial data
        return {'status': 'partial', 'device_id': device_id}
        
    except Exception as exc:
        logger.exception(f"Error processing sensor data from {device_id}")
        
        # Retry with exponential backoff
        try:
            self.retry(countdown=2 ** self.request.retries * 10)
        except MaxRetriesExceededError:
            logger.error(f"Max retries exceeded for device {device_id}")
            # Move to DLQ for manual review
            move_to_dlq('iot', device_id, readings, str(exc))
            raise


@shared_task(
    queue='celery:iot',
    time_limit=10
)
def sync_device_status():
    """Sync device status and mark offline devices."""
    offline_threshold = datetime.now() - timedelta(minutes=5)
    
    devices = get_stale_devices(offline_threshold)
    for device in devices:
        mark_device_offline(device.id)
        send_alert.delay(
            'device_offline',
            {'device_id': device.id, 'last_seen': device.last_seen}
        )


# tasks/email_tasks.py
@shared_task(
    bind=True,
    queue='celery:email',
    max_retries=3,
    default_retry_delay=60,
    rate_limit='100/m'  # 100 emails per minute
)
def send_email(
    self,
    template_id: str,
    recipient_email: str,
    context: dict,
    attachments: list = None
):
    """
    Send transactional email.
    
    Args:
        template_id: Email template identifier
        recipient_email: Recipient email address
        context: Template context variables
        attachments: List of attachment file paths
    """
    try:
        template = get_email_template(template_id)
        
        # Render template
        subject = render_template(template.subject, context)
        body_html = render_template(template.body_html, context)
        body_text = render_template(template.body_text, context)
        
        # Send via SMTP
        send_smtp_email(
            to=recipient_email,
            subject=subject,
            body_html=body_html,
            body_text=body_text,
            attachments=attachments
        )
        
        # Log sent email
        log_email_sent(template_id, recipient_email, 'success')
        
        return {'status': 'sent', 'recipient': recipient_email}
        
    except SMTPException as exc:
        logger.warning(f"SMTP error sending to {recipient_email}: {exc}")
        self.retry(exc=exc, countdown=60)
        
    except Exception as exc:
        logger.exception(f"Failed to send email to {recipient_email}")
        log_email_sent(template_id, recipient_email, 'failed', str(exc))
        raise


@shared_task(
    queue='celery:email',
    time_limit=300
)
def send_bulk_emails(template_id: str, recipient_list: list, context: dict):
    """Send emails to multiple recipients in batches."""
    batch_size = 100
    
    for i in range(0, len(recipient_list), batch_size):
        batch = recipient_list[i:i + batch_size]
        
        # Create group task
        job = group(
            send_email.s(template_id, recipient, context)
            for recipient in batch
        )
        
        # Execute batch
        result = job.apply_async()
        
        # Wait for completion before next batch
        result.get(timeout=300)


# tasks/report_tasks.py
@shared_task(
    bind=True,
    queue='celery:report',
    time_limit=3600,
    soft_time_limit=3300
)
def generate_report(
    self,
    report_type: str,
    date_from: str,
    date_to: str,
    user_id: int,
    format: str = 'pdf'
):
    """
    Generate and deliver business reports.
    
    Args:
        report_type: Type of report (sales, milk_production, etc.)
        date_from: Start date (ISO format)
        date_to: End date (ISO format)
        user_id: User to notify on completion
        format: Output format (pdf, excel, csv)
    """
    try:
        # Update task state
        self.update_state(
            state='PROGRESS',
            meta={'progress': 10, 'status': 'Fetching data...'}
        )
        
        # Fetch data from Odoo
        data = fetch_report_data(report_type, date_from, date_to)
        
        self.update_state(
            state='PROGRESS',
            meta={'progress': 50, 'status': 'Generating report...'}
        )
        
        # Generate report
        if format == 'pdf':
            output_path = generate_pdf_report(report_type, data)
        elif format == 'excel':
            output_path = generate_excel_report(report_type, data)
        else:
            output_path = generate_csv_report(report_type, data)
        
        self.update_state(
            state='PROGRESS',
            meta={'progress': 90, 'status': 'Uploading...'}
        )
        
        # Upload to storage
        file_url = upload_to_s3(output_path)
        
        # Notify user
        send_email.delay(
            'report_ready',
            get_user_email(user_id),
            {
                'report_type': report_type,
                'date_from': date_from,
                'date_to': date_to,
                'download_url': file_url
            }
        )
        
        return {
            'status': 'completed',
            'file_url': file_url,
            'expires_at': (datetime.now() + timedelta(days=7)).isoformat()
        }
        
    except SoftTimeLimitExceeded:
        logger.error("Report generation timed out")
        notify_user.delay(user_id, 'report_timeout', {'report_type': report_type})
        raise


# tasks/payment_tasks.py
@shared_task(
    bind=True,
    queue='celery:payment',
    max_retries=10,
    default_retry_delay=30,
    time_limit=120,
    autoretry_for=(PaymentGatewayError,),
    retry_backoff=True,
    retry_backoff_max=600,
    retry_jitter=True
)
def process_payment(self, order_id: int, payment_method: str, payment_data: dict):
    """
    Process payment with retry logic for gateway failures.
    
    Args:
        order_id: Order ID
        payment_method: Payment gateway (bkash, nagad, etc.)
        payment_data: Payment details
    """
    order = get_order(order_id)
    
    # Idempotency check
    if order.payment_status == 'paid':
        logger.info(f"Order {order_id} already paid")
        return {'status': 'already_paid', 'order_id': order_id}
    
    # Process based on gateway
    gateway = get_payment_gateway(payment_method)
    
    try:
        result = gateway.process_payment(
            amount=order.amount_total,
            currency=order.currency,
            **payment_data
        )
        
        if result.status == 'success':
            # Update order
            mark_order_paid(order_id, result.transaction_id)
            
            # Send confirmation
            send_email.delay(
                'payment_confirmation',
                order.customer_email,
                {'order': order, 'transaction': result}
            )
            
            return {
                'status': 'success',
                'order_id': order_id,
                'transaction_id': result.transaction_id
            }
        else:
            raise PaymentGatewayError(result.error_message)
            
    except PaymentGatewayError as exc:
        # Check if should retry
        if self.request.retries < self.max_retries:
            logger.warning(f"Payment failed for order {order_id}, retrying...")
            raise self.retry(exc=exc)
        else:
            # Final failure
            mark_payment_failed(order_id, str(exc))
            send_email.delay(
                'payment_failed',
                order.customer_email,
                {'order': order, 'error': str(exc)}
            )
            raise
```

---

## 4. QUEUE CONFIGURATION

### 4.1 Worker Configuration

```yaml
# docker-compose.workers.yml
version: '3.8'

services:
  # General purpose workers
  worker-general:
    image: smart-dairy/celery-worker:latest
    command: >
      celery -A celery_config worker
      -Q celery
      -n worker-general@%h
      -c 8
      --prefetch-multiplier=4
      -l INFO
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '2.0'
          memory: 2G
    environment:
      - CELERY_BROKER_URL=redis://redis:6379/0
      - CELERY_RESULT_BACKEND=redis://redis:6379/1

  # IoT dedicated workers
  worker-iot:
    image: smart-dairy/celery-worker:latest
    command: >
      celery -A celery_config worker
      -Q celery:iot
      -n worker-iot@%h
      -c 4
      --prefetch-multiplier=1
      -l INFO
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '1.0'
          memory: 1G

  # Email workers
  worker-email:
    image: smart-dairy/celery-worker:latest
    command: >
      celery -A celery_config worker
      -Q celery:email
      -n worker-email@%h
      -c 2
      --prefetch-multiplier=1
      -l INFO
    deploy:
      replicas: 1
      resources:
        limits:
          cpus: '0.5'
          memory: 512M

  # Report generation workers
  worker-report:
    image: smart-dairy/celery-worker:latest
    command: >
      celery -A celery_config worker
      -Q celery:report
      -n worker-report@%h
      -c 2
      --prefetch-multiplier=1
      -l INFO
    deploy:
      replicas: 1
      resources:
        limits:
          cpus: '2.0'
          memory: 4G

  # Scheduler
  beat:
    image: smart-dairy/celery-worker:latest
    command: >
      celery -A celery_config beat
      -l INFO
      --scheduler django_celery_beat.schedulers:DatabaseScheduler
    deploy:
      replicas: 1

  # Monitoring
  flower:
    image: smart-dairy/celery-worker:latest
    command: >
      celery -A celery_config flower
      --port=5555
      --basic_auth=admin:secret
    ports:
      - "5555:5555"
```

---

## 5. SCHEDULING & PERIODIC TASKS

### 5.1 Periodic Task Schedule

| Task | Frequency | Queue | Description |
|------|-----------|-------|-------------|
| `daily_milk_aggregation` | Daily 00:00 | celery | Aggregate milk production |
| `send_daily_reports` | Daily 08:00 | celery:report | Email daily reports |
| `sync_inventory` | Every 4 hours | celery | Sync inventory levels |
| `cleanup_old_data` | Daily 02:00 | celery:low | Archive old data |
| `backup_database` | Daily 03:00 | celery:low | Database backup |
| `check_subscription_renewals` | Daily 06:00 | celery | Subscription reminders |
| `generate_monthly_invoices` | Monthly 1st | celery:report | Monthly billing |
| `health_check_notifications` | Every 5 min | celery:iot | Critical alerts |

### 5.2 Celery Beat Configuration

```python
# beat_schedule.py
from celery.schedules import crontab
from datetime import timedelta

beat_schedule = {
    # Daily milk production aggregation
    'daily-milk-aggregation': {
        'task': 'tasks.report_tasks.aggregate_milk_production',
        'schedule': crontab(hour=0, minute=0),
        'kwargs': {'period': 'daily'},
    },
    
    # Send daily reports to managers
    'send-daily-reports': {
        'task': 'tasks.report_tasks.send_scheduled_reports',
        'schedule': crontab(hour=8, minute=0),
        'kwargs': {'report_type': 'daily_summary'},
    },
    
    # Inventory synchronization
    'sync-inventory': {
        'task': 'tasks.sync_tasks.sync_inventory_levels',
        'schedule': timedelta(hours=4),
    },
    
    # Data cleanup
    'cleanup-old-data': {
        'task': 'tasks.sync_tasks.cleanup_old_data',
        'schedule': crontab(hour=2, minute=0),
        'kwargs': {'days_to_keep': 90},
    },
    
    # Database backup
    'backup-database': {
        'task': 'tasks.sync_tasks.backup_database',
        'schedule': crontab(hour=3, minute=0),
        'options': {'queue': 'celery:low'},
    },
    
    # Subscription renewal checks
    'check-subscription-renewals': {
        'task': 'tasks.payment_tasks.check_upcoming_renewals',
        'schedule': crontab(hour=6, minute=0),
    },
    
    # Monthly invoicing
    'generate-monthly-invoices': {
        'task': 'tasks.payment_tasks.generate_monthly_invoices',
        'schedule': crontab(day_of_month=1, hour=0, minute=0),
        'options': {'queue': 'celery:report'},
    },
    
    # Health check for critical devices
    'health-check-notifications': {
        'task': 'tasks.iot_tasks.check_critical_devices',
        'schedule': timedelta(minutes=5),
        'options': {'queue': 'celery:iot'},
    },
}
```

---

## 6. ERROR HANDLING & RETRIES

### 6.1 Retry Strategy

| Error Type | Retry Behavior | Max Retries |
|------------|----------------|-------------|
| Network Timeout | Exponential backoff | 5 |
| Database Lock | Immediate retry | 3 |
| Rate Limited | Delayed retry | 10 |
| Validation Error | No retry | 0 |
| Permission Error | No retry | 0 |
| Resource Not Found | No retry | 0 |

### 6.2 Dead Letter Queue

```python
# tasks/error_handlers.py
from celery.signals import task_failure

@task_failure.connect
def handle_task_failure(sender, task_id, exception, args, kwargs, traceback, einfo, **kw):
    """Handle task failures and route to DLQ if needed."""
    
    error_info = {
        'task_name': sender.name,
        'task_id': task_id,
        'exception': str(exception),
        'args': args,
        'kwargs': {k: v for k, v in kwargs.items() if k not in ['password', 'secret']},
        'timestamp': datetime.utcnow().isoformat(),
    }
    
    # Log failure
    logger.error(f"Task {sender.name} failed: {exception}", extra=error_info)
    
    # Store in DLQ for manual review
    store_in_dlq.delay(error_info)
    
    # Send alert for critical tasks
    if sender.name.startswith('tasks.payment'):
        send_alert.delay(
            'critical_task_failed',
            error_info
        )
```

---

## 7. MONITORING & MANAGEMENT

### 7.1 Flower Configuration

```python
# flower_config.py
FLOWER_BASIC_AUTH = ['admin:secure_password']
FLOWER_URL_PREFIX = 'flower'
FLOWER_MAX_TASKS = 10000
FLOWER_PURGE_OFFLINE_WORKERS = 10  # seconds
```

### 7.2 Prometheus Metrics

```python
# metrics.py
from prometheus_client import Counter, Histogram, Gauge

# Task metrics
tasks_total = Counter(
    'celery_tasks_total',
    'Total tasks',
    ['task_name', 'queue', 'state']
)

task_duration = Histogram(
    'celery_task_duration_seconds',
    'Task duration',
    ['task_name'],
    buckets=[.01, .025, .05, .1, .25, .5, 1, 2.5, 5, 10, 30, 60]
)

task_retries = Counter(
    'celery_task_retries_total',
    'Task retries',
    ['task_name']
)

# Queue metrics
queue_length = Gauge(
    'celery_queue_length',
    'Current queue length',
    ['queue_name']
)

workers_active = Gauge(
    'celery_workers_active',
    'Number of active workers'
)
```

---

## 8. PERFORMANCE OPTIMIZATION

### 8.1 Optimization Strategies

| Strategy | Implementation | Benefit |
|----------|---------------|---------|
| **Chunking** | Process data in batches | Reduced memory |
**Acks Late** | acks_late=True | No lost tasks on crash |
| **Prefetch** | Reduced prefetch for I/O tasks | Better distribution |
| **Result Ignore** | ignore_result=True for fire-and-forget | Reduced Redis load |
| **Compression** | task_compression=gzip | Reduced bandwidth |

### 8.2 Task Chunking Example

```python
@shared_task
def process_large_dataset(dataset_id: int):
    """Process large dataset in chunks."""
    CHUNK_SIZE = 1000
    
    total_records = get_record_count(dataset_id)
    
    # Create chunked tasks
    for offset in range(0, total_records, CHUNK_SIZE):
        process_chunk.delay(dataset_id, offset, CHUNK_SIZE)


@shared_task(
    bind=True,
    max_retries=3,
    default_retry_delay=5
)
def process_chunk(self, dataset_id: int, offset: int, limit: int):
    """Process a chunk of data."""
    try:
        records = fetch_records(dataset_id, offset, limit)
        
        for record in records:
            process_record(record)
            
    except DatabaseError as exc:
        # Retry specific database errors
        self.retry(exc=exc)
```

---

## 9. APPENDICES

### Appendix A: Task State Machine

```
PENDING -> STARTED -> SUCCESS
                    -> FAILURE
                    -> RETRY -> PENDING
```

### Appendix B: Celery Commands

```bash
# Start worker
celery -A celery_config worker -l INFO

# Start with specific queue
celery -A celery_config worker -Q celery:high -n high-worker@%h

# Start beat scheduler
celery -A celery_config beat -l INFO

# Monitor with Flower
celery -A celery_config flower --port=5555

# Purge queue
celery -A celery_config purge -Q celery

# Inspect workers
celery -A celery_config inspect active
celery -A celery_config inspect scheduled
celery -A celery_config inspect reserved
```

---

**END OF BACKGROUND JOB PROCESSING SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 12, 2026 | Tech Lead | Initial version |
