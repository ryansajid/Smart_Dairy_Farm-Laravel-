# Milestone 129: Reporting Automation

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Attribute | Details |
|-----------|---------|
| **Document ID** | SD-P12-M129-REPORTING-v1.0 |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Number** | 129 of 130 (Phase 12: 9 of 10) |
| **Timeline** | Days 681-690 |
| **Team Size** | 3 Full-Stack Developers |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 129 implements a comprehensive **Reporting Automation Platform** that automates report generation, scheduling, and distribution across the Smart Dairy ecosystem. This milestone builds upon the analytics foundation from Milestone 125 and workflow automation from Milestone 127 to deliver a complete automated reporting solution.

The platform enables stakeholders to receive timely, accurate reports without manual intervention—from daily delivery summaries to monthly executive dashboards. Report automation reduces manual effort by 80% while ensuring consistent, reliable access to business intelligence.

### 1.2 Business Value Proposition

| Value Driver | Impact | Measurement |
|--------------|--------|-------------|
| Manual Effort Reduction | 80% decrease in report preparation time | Hours saved/week |
| Decision Speed | Real-time alerts for critical metrics | Alert response time |
| Data Accessibility | Self-service report access for all roles | Report adoption rate |
| Compliance | Automated audit trail and data governance | Compliance score |
| Operational Efficiency | Scheduled reports replace ad-hoc requests | IT ticket reduction |

### 1.3 Strategic Alignment

**RFP Alignment:**
- REQ-RPT-001: "Automated report generation and distribution system"
- REQ-RPT-002: "Scheduled reporting with email delivery"
- REQ-RPT-003: "Alert-based reporting for critical thresholds"
- REQ-RPT-004: "Multi-format export (PDF, Excel, CSV)"

**BRD Alignment:**
- BR-OPS-001: "40% reduction in manual operational tasks"
- BR-RPT-001: "Executive dashboards with automated refresh"
- BR-INT-001: "Unified reporting across all business verticals"

**SRS Alignment:**
- SRS-RPT-001: "Report generation within 30 seconds"
- SRS-RPT-002: "Support for 50+ concurrent report generations"
- SRS-RPT-003: "99.9% scheduled report delivery reliability"

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

#### 2.1.1 Report Scheduling Engine (P0 - Critical)
- Cron-based schedule configuration
- Time zone support for regional offices
- Business calendar integration (holidays, weekends)
- Retry logic for failed generations
- Schedule conflict detection
- Dependency chain management

#### 2.1.2 Alert-Based Report Triggers (P0 - Critical)
- Threshold breach detection
- Anomaly-based triggers
- Event-driven report generation
- Multi-condition trigger rules
- Trigger cooldown management
- Priority queue processing

#### 2.1.3 Report Generation Engine (P0 - Critical)
- Template-based report generation
- Dynamic data binding
- Parameter substitution
- Multi-format output (PDF, Excel, CSV, HTML)
- Chart and visualization embedding
- Large dataset handling (pagination, streaming)

#### 2.1.4 Distribution System (P0 - Critical)
- Email delivery with attachments
- Report portal access links
- Role-based distribution lists
- Subscription management for recipients
- Delivery confirmation tracking
- Bounce and failure handling

#### 2.1.5 Report Template Library (P1 - High)
- Pre-built report templates by vertical
- Template versioning
- Template cloning and customization
- Variable and parameter definitions
- Layout designer integration
- Template categories and tagging

#### 2.1.6 Custom Report Builder (P1 - High)
- Drag-and-drop report designer
- Data source selection
- Field and measure configuration
- Filtering and grouping options
- Visualization widgets
- Preview and validation

#### 2.1.7 Access Control & Security (P0 - Critical)
- Role-based report access
- Data-level security (row/column filtering)
- Report sharing permissions
- Audit logging for report access
- Sensitive data masking
- Export restrictions

### 2.2 Out-of-Scope Items

| Item | Reason | Future Phase |
|------|--------|--------------|
| BI tool integration (Power BI, Tableau) | Requires enterprise licensing | Phase 14 |
| Natural language report queries | AI/ML complexity | Phase 15 |
| Real-time streaming reports | Infrastructure changes needed | Phase 14 |
| White-label report branding | Customer-specific customization | Phase 14 |

### 2.3 Assumptions

1. Apache Superset dashboards from Phase 11 are operational
2. Email infrastructure (SendGrid/SES) is configured and tested
3. TimescaleDB analytics data is available from Milestone 125
4. Redis queue system is operational for job processing
5. Storage capacity available for report archive (50GB minimum)

### 2.4 Constraints

1. Report generation must complete within 30 seconds for standard reports
2. Maximum 50 concurrent report generation jobs
3. Email attachment size limit: 25MB
4. Report retention: 90 days for generated reports
5. Schedule granularity: minimum 15-minute intervals

---

## 3. Technical Architecture

### 3.1 System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        REPORTING AUTOMATION PLATFORM                      │
├─────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐          │
│  │  Report Portal  │  │  Admin Console  │  │   Mobile App    │          │
│  │    (React)      │  │   (Odoo OWL)    │  │   (Flutter)     │          │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘          │
│           │                    │                    │                    │
│           └────────────────────┼────────────────────┘                    │
│                                │                                         │
│  ┌─────────────────────────────▼─────────────────────────────────────┐  │
│  │                      API GATEWAY (REST)                            │  │
│  │  /reports  /schedules  /templates  /distributions  /alerts         │  │
│  └─────────────────────────────┬─────────────────────────────────────┘  │
│                                │                                         │
│  ┌─────────────────────────────▼─────────────────────────────────────┐  │
│  │                    REPORTING SERVICE LAYER                         │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                │  │
│  │  │  Schedule   │  │   Report    │  │   Alert     │                │  │
│  │  │   Engine    │  │   Engine    │  │   Engine    │                │  │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘                │  │
│  │         │                │                │                        │  │
│  │  ┌──────▼────────────────▼────────────────▼──────┐                │  │
│  │  │              JOB QUEUE (Celery + Redis)        │                │  │
│  │  └──────┬────────────────┬────────────────┬──────┘                │  │
│  │         │                │                │                        │  │
│  │  ┌──────▼──────┐  ┌──────▼──────┐  ┌──────▼──────┐                │  │
│  │  │  Generator  │  │  Exporter   │  │ Distributor │                │  │
│  │  │   Worker    │  │   Worker    │  │   Worker    │                │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘                │  │
│  └───────────────────────────────────────────────────────────────────┘  │
│                                                                          │
│  ┌───────────────────────────────────────────────────────────────────┐  │
│  │                        DATA LAYER                                  │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                │  │
│  │  │ PostgreSQL  │  │ TimescaleDB │  │    Redis    │                │  │
│  │  │  (Reports)  │  │ (Analytics) │  │   (Cache)   │                │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘                │  │
│  │                                                                    │  │
│  │  ┌─────────────┐  ┌─────────────┐                                 │  │
│  │  │    MinIO    │  │   Superset  │                                 │  │
│  │  │  (Storage)  │  │ (Dashboards)│                                 │  │
│  │  └─────────────┘  └─────────────┘                                 │  │
│  └───────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Data Models

#### 3.2.1 Report Template Model

```sql
-- Report Template Definition
CREATE TABLE report_template (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category_id INTEGER REFERENCES report_category(id),
    template_type VARCHAR(20) NOT NULL DEFAULT 'standard',
    -- Types: standard, dashboard, alert, custom

    -- Template Configuration
    data_source_type VARCHAR(30) NOT NULL,
    -- Types: sql_query, odoo_model, superset_chart, api_endpoint
    data_source_config JSONB NOT NULL,
    -- {query: "SELECT...", model: "sale.order", params: [...]}

    -- Layout Configuration
    layout_type VARCHAR(20) NOT NULL DEFAULT 'tabular',
    -- Types: tabular, chart, mixed, dashboard
    layout_config JSONB NOT NULL DEFAULT '{}',
    -- {columns: [...], grouping: [...], totals: [...]}

    -- Output Configuration
    supported_formats TEXT[] DEFAULT ARRAY['pdf', 'excel', 'csv'],
    default_format VARCHAR(10) DEFAULT 'pdf',
    page_orientation VARCHAR(10) DEFAULT 'portrait',
    page_size VARCHAR(10) DEFAULT 'A4',

    -- Parameters
    parameters JSONB DEFAULT '[]',
    -- [{name: "date_from", type: "date", required: true, default: "today-30"}]

    -- Versioning
    version INTEGER DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    is_system BOOLEAN DEFAULT FALSE,

    -- Access Control
    visibility VARCHAR(20) DEFAULT 'private',
    -- Types: private, team, organization, public
    owner_id INTEGER REFERENCES res_users(id),
    allowed_roles TEXT[],

    -- Metadata
    tags TEXT[],
    created_by INTEGER REFERENCES res_users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Template Categories
CREATE TABLE report_category (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    parent_id INTEGER REFERENCES report_category(id),
    icon VARCHAR(50),
    sequence INTEGER DEFAULT 10,
    is_active BOOLEAN DEFAULT TRUE
);

-- Template Version History
CREATE TABLE report_template_version (
    id SERIAL PRIMARY KEY,
    template_id INTEGER REFERENCES report_template(id) ON DELETE CASCADE,
    version INTEGER NOT NULL,
    data_source_config JSONB NOT NULL,
    layout_config JSONB NOT NULL,
    parameters JSONB,
    change_notes TEXT,
    created_by INTEGER REFERENCES res_users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(template_id, version)
);

-- Indexes
CREATE INDEX idx_report_template_category ON report_template(category_id);
CREATE INDEX idx_report_template_owner ON report_template(owner_id);
CREATE INDEX idx_report_template_active ON report_template(is_active) WHERE is_active = TRUE;
CREATE INDEX idx_report_template_tags ON report_template USING GIN(tags);
```

#### 3.2.2 Report Schedule Model

```sql
-- Report Schedule Configuration
CREATE TABLE report_schedule (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    template_id INTEGER REFERENCES report_template(id) ON DELETE CASCADE,

    -- Schedule Configuration
    schedule_type VARCHAR(20) NOT NULL,
    -- Types: once, recurring, trigger
    cron_expression VARCHAR(100),
    -- Standard cron: "0 8 * * 1-5" (8 AM weekdays)
    timezone VARCHAR(50) DEFAULT 'Asia/Dhaka',

    -- Business Calendar
    respect_holidays BOOLEAN DEFAULT TRUE,
    holiday_calendar_id INTEGER REFERENCES hr_public_holiday_calendar(id),
    skip_weekends BOOLEAN DEFAULT FALSE,

    -- Execution Window
    start_date DATE,
    end_date DATE,
    next_run_at TIMESTAMP,
    last_run_at TIMESTAMP,

    -- Parameters Override
    parameter_values JSONB DEFAULT '{}',
    -- {date_from: "{{today-7}}", date_to: "{{today}}"}

    -- Output Configuration
    output_format VARCHAR(10) DEFAULT 'pdf',
    filename_pattern VARCHAR(200),
    -- Pattern: "sales_report_{{date}}_{{region}}.pdf"

    -- Retry Configuration
    max_retries INTEGER DEFAULT 3,
    retry_delay_minutes INTEGER DEFAULT 15,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    status VARCHAR(20) DEFAULT 'active',
    -- Status: active, paused, completed, failed

    -- Ownership
    created_by INTEGER REFERENCES res_users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Schedule Execution Log
CREATE TABLE report_schedule_execution (
    id SERIAL PRIMARY KEY,
    schedule_id INTEGER REFERENCES report_schedule(id) ON DELETE CASCADE,

    -- Execution Details
    scheduled_at TIMESTAMP NOT NULL,
    started_at TIMESTAMP,
    completed_at TIMESTAMP,

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    -- Status: pending, running, completed, failed, cancelled
    attempt_number INTEGER DEFAULT 1,

    -- Results
    report_generation_id INTEGER REFERENCES report_generation(id),
    error_message TEXT,

    -- Metrics
    generation_time_ms INTEGER,
    file_size_bytes BIGINT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_schedule_template ON report_schedule(template_id);
CREATE INDEX idx_schedule_next_run ON report_schedule(next_run_at)
    WHERE is_active = TRUE;
CREATE INDEX idx_schedule_execution_status ON report_schedule_execution(status);
CREATE INDEX idx_schedule_execution_scheduled ON report_schedule_execution(scheduled_at);
```

#### 3.2.3 Report Generation Model

```sql
-- Generated Report Instance
CREATE TABLE report_generation (
    id SERIAL PRIMARY KEY,
    reference VARCHAR(50) UNIQUE NOT NULL,
    -- Format: RPT-YYYYMMDD-XXXXXX

    template_id INTEGER REFERENCES report_template(id),
    schedule_id INTEGER REFERENCES report_schedule(id),

    -- Generation Context
    generation_type VARCHAR(20) NOT NULL,
    -- Types: scheduled, manual, triggered, api
    trigger_id INTEGER REFERENCES report_alert_trigger(id),

    -- Parameters Used
    parameter_values JSONB DEFAULT '{}',

    -- Output
    output_format VARCHAR(10) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500),
    file_size_bytes BIGINT,
    storage_type VARCHAR(20) DEFAULT 'minio',
    -- Types: minio, local, s3

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'queued',
    -- Status: queued, generating, completed, failed, expired

    -- Timing
    queued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    started_at TIMESTAMP,
    completed_at TIMESTAMP,
    expires_at TIMESTAMP,

    -- Metrics
    data_rows_count INTEGER,
    generation_time_ms INTEGER,

    -- Error Handling
    error_message TEXT,
    error_details JSONB,
    retry_count INTEGER DEFAULT 0,

    -- Access Control
    generated_by INTEGER REFERENCES res_users(id),
    access_token VARCHAR(100),
    download_count INTEGER DEFAULT 0,
    last_downloaded_at TIMESTAMP,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Report Generation Access Log
CREATE TABLE report_generation_access (
    id SERIAL PRIMARY KEY,
    generation_id INTEGER REFERENCES report_generation(id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES res_users(id),
    access_type VARCHAR(20) NOT NULL,
    -- Types: view, download, share, email
    ip_address INET,
    user_agent TEXT,
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_generation_template ON report_generation(template_id);
CREATE INDEX idx_generation_schedule ON report_generation(schedule_id);
CREATE INDEX idx_generation_status ON report_generation(status);
CREATE INDEX idx_generation_created ON report_generation(created_at);
CREATE INDEX idx_generation_token ON report_generation(access_token) WHERE access_token IS NOT NULL;
```

#### 3.2.4 Alert Trigger Model

```sql
-- Alert-Based Report Triggers
CREATE TABLE report_alert_trigger (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,

    -- Associated Template
    template_id INTEGER REFERENCES report_template(id) ON DELETE CASCADE,

    -- Trigger Configuration
    trigger_type VARCHAR(30) NOT NULL,
    -- Types: threshold, anomaly, event, schedule_condition

    -- Condition Definition
    metric_source VARCHAR(30) NOT NULL,
    -- Sources: sql_query, odoo_field, api_endpoint, analytics_metric
    metric_config JSONB NOT NULL,
    -- {query: "SELECT...", field: "sale.order.total", metric: "mrr"}

    -- Threshold Configuration
    condition_operator VARCHAR(20),
    -- Operators: gt, gte, lt, lte, eq, neq, between, outside
    threshold_value NUMERIC,
    threshold_value_upper NUMERIC,
    -- For 'between' and 'outside' operators

    -- Evaluation
    evaluation_interval_minutes INTEGER DEFAULT 60,
    last_evaluated_at TIMESTAMP,
    last_triggered_at TIMESTAMP,
    current_value NUMERIC,

    -- Cooldown
    cooldown_minutes INTEGER DEFAULT 60,
    -- Minimum time between triggers

    -- Priority
    priority VARCHAR(10) DEFAULT 'normal',
    -- Priority: low, normal, high, critical

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    consecutive_triggers INTEGER DEFAULT 0,

    created_by INTEGER REFERENCES res_users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trigger Evaluation History
CREATE TABLE report_trigger_evaluation (
    id SERIAL PRIMARY KEY,
    trigger_id INTEGER REFERENCES report_alert_trigger(id) ON DELETE CASCADE,
    evaluated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    metric_value NUMERIC,
    threshold_value NUMERIC,
    condition_met BOOLEAN NOT NULL,
    triggered_report BOOLEAN DEFAULT FALSE,
    generation_id INTEGER REFERENCES report_generation(id),
    evaluation_time_ms INTEGER
);

-- Indexes
CREATE INDEX idx_trigger_template ON report_alert_trigger(template_id);
CREATE INDEX idx_trigger_active ON report_alert_trigger(is_active) WHERE is_active = TRUE;
CREATE INDEX idx_trigger_next_eval ON report_alert_trigger(last_evaluated_at);
CREATE INDEX idx_trigger_eval_time ON report_trigger_evaluation(evaluated_at);
```

#### 3.2.5 Distribution Model

```sql
-- Report Distribution Configuration
CREATE TABLE report_distribution (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,

    -- Source
    schedule_id INTEGER REFERENCES report_schedule(id),
    trigger_id INTEGER REFERENCES report_alert_trigger(id),

    -- Distribution Method
    distribution_type VARCHAR(20) NOT NULL,
    -- Types: email, portal, webhook, sftp

    -- Email Configuration
    email_to TEXT[],
    email_cc TEXT[],
    email_bcc TEXT[],
    email_subject_template VARCHAR(500),
    email_body_template TEXT,
    attach_report BOOLEAN DEFAULT TRUE,
    include_link BOOLEAN DEFAULT TRUE,

    -- Portal Configuration
    portal_publish BOOLEAN DEFAULT FALSE,
    portal_category_id INTEGER,
    portal_visibility VARCHAR(20) DEFAULT 'private',

    -- Webhook Configuration
    webhook_url VARCHAR(500),
    webhook_method VARCHAR(10) DEFAULT 'POST',
    webhook_headers JSONB DEFAULT '{}',

    -- SFTP Configuration
    sftp_host VARCHAR(200),
    sftp_port INTEGER DEFAULT 22,
    sftp_username VARCHAR(100),
    sftp_password_encrypted VARCHAR(500),
    sftp_path VARCHAR(500),

    -- Retry Configuration
    max_retries INTEGER DEFAULT 3,
    retry_delay_minutes INTEGER DEFAULT 5,

    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Distribution Recipients (Dynamic)
CREATE TABLE report_distribution_recipient (
    id SERIAL PRIMARY KEY,
    distribution_id INTEGER REFERENCES report_distribution(id) ON DELETE CASCADE,

    recipient_type VARCHAR(20) NOT NULL,
    -- Types: user, role, team, external_email

    user_id INTEGER REFERENCES res_users(id),
    role_id INTEGER REFERENCES res_groups(id),
    team_id INTEGER REFERENCES crm_team(id),
    external_email VARCHAR(200),

    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP
);

-- Distribution Execution Log
CREATE TABLE report_distribution_log (
    id SERIAL PRIMARY KEY,
    distribution_id INTEGER REFERENCES report_distribution(id),
    generation_id INTEGER REFERENCES report_generation(id),

    -- Delivery Details
    recipient_email VARCHAR(200),
    recipient_user_id INTEGER REFERENCES res_users(id),

    -- Status
    status VARCHAR(20) NOT NULL,
    -- Status: pending, sent, delivered, bounced, failed

    sent_at TIMESTAMP,
    delivered_at TIMESTAMP,
    opened_at TIMESTAMP,

    -- Error Details
    error_message TEXT,
    retry_count INTEGER DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_distribution_schedule ON report_distribution(schedule_id);
CREATE INDEX idx_distribution_trigger ON report_distribution(trigger_id);
CREATE INDEX idx_distribution_recipient ON report_distribution_recipient(distribution_id);
CREATE INDEX idx_distribution_log_status ON report_distribution_log(status);
CREATE INDEX idx_distribution_log_gen ON report_distribution_log(generation_id);
```

### 3.3 Service Layer Implementation

#### 3.3.1 Report Schedule Engine

```python
# smart_dairy_reporting/services/schedule_engine.py
from datetime import datetime, timedelta
from typing import Optional, List, Dict, Any
from croniter import croniter
import pytz
from celery import shared_task
import logging

from odoo import models, fields, api
from odoo.exceptions import ValidationError

_logger = logging.getLogger(__name__)


class ReportScheduleEngine(models.AbstractModel):
    _name = 'report.schedule.engine'
    _description = 'Report Schedule Engine'

    def get_next_run_time(
        self,
        schedule: 'ReportSchedule',
        from_time: Optional[datetime] = None
    ) -> Optional[datetime]:
        """
        Calculate next run time based on cron expression and business rules.
        """
        if not schedule.cron_expression:
            return None

        tz = pytz.timezone(schedule.timezone or 'Asia/Dhaka')
        base_time = from_time or datetime.now(tz)

        # Parse cron expression
        cron = croniter(schedule.cron_expression, base_time)
        next_run = cron.get_next(datetime)

        # Apply business calendar rules
        if schedule.respect_holidays or schedule.skip_weekends:
            next_run = self._apply_business_rules(
                next_run,
                schedule.holiday_calendar_id,
                schedule.skip_weekends,
                tz
            )

        # Check if within schedule window
        if schedule.end_date and next_run.date() > schedule.end_date:
            return None

        return next_run

    def _apply_business_rules(
        self,
        run_time: datetime,
        holiday_calendar_id: int,
        skip_weekends: bool,
        tz: pytz.timezone
    ) -> datetime:
        """
        Adjust run time based on holidays and weekends.
        """
        max_iterations = 30  # Prevent infinite loops
        iteration = 0

        while iteration < max_iterations:
            is_valid = True

            # Check weekend
            if skip_weekends and run_time.weekday() >= 5:
                is_valid = False

            # Check holiday
            if holiday_calendar_id:
                holiday = self.env['hr.public.holiday'].search([
                    ('calendar_id', '=', holiday_calendar_id),
                    ('date', '=', run_time.date())
                ], limit=1)
                if holiday:
                    is_valid = False

            if is_valid:
                return run_time

            # Move to next day, same time
            run_time = run_time + timedelta(days=1)
            iteration += 1

        return run_time

    def process_due_schedules(self) -> Dict[str, int]:
        """
        Find and queue all schedules due for execution.
        Called by cron job every minute.
        """
        now = datetime.now(pytz.UTC)
        stats = {'queued': 0, 'skipped': 0, 'failed': 0}

        # Find due schedules
        due_schedules = self.env['report.schedule'].search([
            ('is_active', '=', True),
            ('status', '=', 'active'),
            ('next_run_at', '<=', now),
            '|',
            ('end_date', '=', False),
            ('end_date', '>=', fields.Date.today())
        ])

        for schedule in due_schedules:
            try:
                # Check for running execution
                running = self.env['report.schedule.execution'].search([
                    ('schedule_id', '=', schedule.id),
                    ('status', 'in', ['pending', 'running'])
                ], limit=1)

                if running:
                    stats['skipped'] += 1
                    continue

                # Create execution record
                execution = self.env['report.schedule.execution'].create({
                    'schedule_id': schedule.id,
                    'scheduled_at': schedule.next_run_at,
                    'status': 'pending'
                })

                # Queue Celery task
                execute_scheduled_report.delay(execution.id)
                stats['queued'] += 1

                # Calculate next run
                next_run = self.get_next_run_time(schedule, now)
                schedule.write({
                    'last_run_at': now,
                    'next_run_at': next_run
                })

            except Exception as e:
                _logger.error(f"Failed to queue schedule {schedule.id}: {e}")
                stats['failed'] += 1

        return stats


@shared_task(bind=True, max_retries=3)
def execute_scheduled_report(self, execution_id: int):
    """
    Celery task to execute a scheduled report.
    """
    from odoo import registry, SUPERUSER_ID

    db_name = self.app.conf.get('odoo_database')
    with registry(db_name).cursor() as cr:
        env = api.Environment(cr, SUPERUSER_ID, {})

        execution = env['report.schedule.execution'].browse(execution_id)
        if not execution.exists():
            return {'status': 'error', 'message': 'Execution not found'}

        try:
            execution.write({
                'status': 'running',
                'started_at': datetime.now()
            })
            cr.commit()

            # Generate the report
            schedule = execution.schedule_id
            generator = env['report.generation.engine']

            generation = generator.generate_report(
                template_id=schedule.template_id.id,
                parameters=schedule.parameter_values or {},
                output_format=schedule.output_format,
                filename_pattern=schedule.filename_pattern,
                generation_type='scheduled',
                schedule_id=schedule.id
            )

            execution.write({
                'status': 'completed',
                'completed_at': datetime.now(),
                'report_generation_id': generation.id,
                'generation_time_ms': generation.generation_time_ms,
                'file_size_bytes': generation.file_size_bytes
            })

            # Trigger distribution
            env['report.distribution.engine'].distribute_report(
                generation_id=generation.id,
                schedule_id=schedule.id
            )

            return {'status': 'success', 'generation_id': generation.id}

        except Exception as e:
            _logger.error(f"Report execution failed: {e}")
            execution.write({
                'status': 'failed',
                'completed_at': datetime.now(),
                'error_message': str(e),
                'attempt_number': execution.attempt_number + 1
            })

            # Retry if under limit
            if execution.attempt_number < schedule.max_retries:
                raise self.retry(
                    countdown=schedule.retry_delay_minutes * 60,
                    exc=e
                )

            return {'status': 'failed', 'error': str(e)}
```

#### 3.3.2 Report Generation Engine

```python
# smart_dairy_reporting/services/generation_engine.py
from datetime import datetime, timedelta
from typing import Optional, Dict, Any, List
import uuid
import time
import io
import logging

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError

_logger = logging.getLogger(__name__)


class ReportGenerationEngine(models.AbstractModel):
    _name = 'report.generation.engine'
    _description = 'Report Generation Engine'

    def generate_report(
        self,
        template_id: int,
        parameters: Dict[str, Any] = None,
        output_format: str = 'pdf',
        filename_pattern: str = None,
        generation_type: str = 'manual',
        schedule_id: int = None,
        trigger_id: int = None,
        user_id: int = None
    ) -> 'ReportGeneration':
        """
        Generate a report from template with given parameters.
        """
        start_time = time.time()

        template = self.env['report.template'].browse(template_id)
        if not template.exists():
            raise ValidationError(f"Template {template_id} not found")

        if output_format not in template.supported_formats:
            raise ValidationError(
                f"Format {output_format} not supported. "
                f"Supported: {template.supported_formats}"
            )

        # Resolve parameters
        resolved_params = self._resolve_parameters(
            template.parameters or [],
            parameters or {}
        )

        # Create generation record
        reference = self._generate_reference()
        filename = self._resolve_filename(
            filename_pattern or f"{template.code}_{{date}}.{output_format}",
            resolved_params,
            output_format
        )

        generation = self.env['report.generation'].create({
            'reference': reference,
            'template_id': template_id,
            'schedule_id': schedule_id,
            'trigger_id': trigger_id,
            'generation_type': generation_type,
            'parameter_values': resolved_params,
            'output_format': output_format,
            'filename': filename,
            'status': 'generating',
            'started_at': datetime.now(),
            'generated_by': user_id or self.env.uid,
            'access_token': uuid.uuid4().hex,
            'expires_at': datetime.now() + timedelta(days=90)
        })

        try:
            # Fetch data
            data = self._fetch_report_data(template, resolved_params)

            # Generate output
            file_content, file_size = self._generate_output(
                template,
                data,
                output_format,
                resolved_params
            )

            # Store file
            file_path = self._store_report_file(
                generation.reference,
                filename,
                file_content
            )

            generation_time = int((time.time() - start_time) * 1000)

            generation.write({
                'status': 'completed',
                'completed_at': datetime.now(),
                'file_path': file_path,
                'file_size_bytes': file_size,
                'data_rows_count': len(data) if isinstance(data, list) else 1,
                'generation_time_ms': generation_time
            })

            _logger.info(
                f"Report {reference} generated in {generation_time}ms, "
                f"size: {file_size} bytes"
            )

            return generation

        except Exception as e:
            _logger.error(f"Report generation failed: {e}")
            generation.write({
                'status': 'failed',
                'completed_at': datetime.now(),
                'error_message': str(e),
                'error_details': {'traceback': str(e.__traceback__)}
            })
            raise

    def _resolve_parameters(
        self,
        param_definitions: List[Dict],
        provided_params: Dict[str, Any]
    ) -> Dict[str, Any]:
        """
        Resolve parameter values including dynamic expressions.
        """
        resolved = {}
        today = fields.Date.today()

        for param_def in param_definitions:
            name = param_def.get('name')
            param_type = param_def.get('type', 'string')
            required = param_def.get('required', False)
            default = param_def.get('default')

            # Get value from provided or default
            value = provided_params.get(name, default)

            if value is None and required:
                raise ValidationError(f"Required parameter '{name}' not provided")

            # Resolve dynamic expressions
            if isinstance(value, str):
                value = self._resolve_expression(value, today)

            # Type conversion
            value = self._convert_param_type(value, param_type)

            resolved[name] = value

        return resolved

    def _resolve_expression(self, expr: str, today) -> Any:
        """
        Resolve dynamic expressions like {{today}}, {{today-7}}.
        """
        import re

        # Match {{expression}}
        pattern = r'\{\{([^}]+)\}\}'

        def replace_expr(match):
            expression = match.group(1).strip()

            if expression == 'today':
                return str(today)
            elif expression.startswith('today'):
                # Parse offset: today-7, today+30
                offset_match = re.match(r'today([+-])(\d+)', expression)
                if offset_match:
                    op = offset_match.group(1)
                    days = int(offset_match.group(2))
                    if op == '-':
                        return str(today - timedelta(days=days))
                    else:
                        return str(today + timedelta(days=days))
            elif expression == 'month_start':
                return str(today.replace(day=1))
            elif expression == 'month_end':
                next_month = today.replace(day=28) + timedelta(days=4)
                return str(next_month - timedelta(days=next_month.day))
            elif expression == 'year_start':
                return str(today.replace(month=1, day=1))
            elif expression == 'date':
                return today.strftime('%Y%m%d')
            elif expression == 'datetime':
                return datetime.now().strftime('%Y%m%d_%H%M%S')

            return match.group(0)  # Return original if not matched

        return re.sub(pattern, replace_expr, expr)

    def _convert_param_type(self, value: Any, param_type: str) -> Any:
        """
        Convert parameter value to appropriate type.
        """
        if value is None:
            return None

        if param_type == 'integer':
            return int(value)
        elif param_type == 'float':
            return float(value)
        elif param_type == 'boolean':
            return value in (True, 'true', 'True', '1', 1)
        elif param_type == 'date':
            if isinstance(value, str):
                return fields.Date.from_string(value)
            return value
        elif param_type == 'datetime':
            if isinstance(value, str):
                return fields.Datetime.from_string(value)
            return value
        elif param_type == 'list':
            if isinstance(value, str):
                return [v.strip() for v in value.split(',')]
            return list(value)

        return str(value)

    def _fetch_report_data(
        self,
        template: 'ReportTemplate',
        parameters: Dict[str, Any]
    ) -> Any:
        """
        Fetch data based on template's data source configuration.
        """
        source_type = template.data_source_type
        config = template.data_source_config

        if source_type == 'sql_query':
            return self._execute_sql_query(config, parameters)
        elif source_type == 'odoo_model':
            return self._fetch_odoo_data(config, parameters)
        elif source_type == 'superset_chart':
            return self._fetch_superset_data(config, parameters)
        elif source_type == 'api_endpoint':
            return self._fetch_api_data(config, parameters)
        else:
            raise ValidationError(f"Unknown data source type: {source_type}")

    def _execute_sql_query(
        self,
        config: Dict,
        parameters: Dict[str, Any]
    ) -> List[Dict]:
        """
        Execute parameterized SQL query.
        """
        query = config.get('query')
        if not query:
            raise ValidationError("SQL query not configured")

        # Use named parameters for safety
        self.env.cr.execute(query, parameters)
        columns = [desc[0] for desc in self.env.cr.description]
        rows = self.env.cr.fetchall()

        return [dict(zip(columns, row)) for row in rows]

    def _fetch_odoo_data(
        self,
        config: Dict,
        parameters: Dict[str, Any]
    ) -> List[Dict]:
        """
        Fetch data from Odoo model.
        """
        model_name = config.get('model')
        domain = config.get('domain', [])
        fields_list = config.get('fields', [])
        order = config.get('order', 'id desc')
        limit = config.get('limit')

        # Resolve domain parameters
        resolved_domain = self._resolve_domain(domain, parameters)

        Model = self.env[model_name]
        records = Model.search(resolved_domain, order=order, limit=limit)

        if fields_list:
            return records.read(fields_list)
        return records.read()

    def _resolve_domain(
        self,
        domain: List,
        parameters: Dict[str, Any]
    ) -> List:
        """
        Replace parameter placeholders in domain.
        """
        resolved = []
        for item in domain:
            if isinstance(item, (list, tuple)) and len(item) == 3:
                field, op, value = item
                if isinstance(value, str) and value.startswith('${'):
                    param_name = value[2:-1]
                    value = parameters.get(param_name, value)
                resolved.append((field, op, value))
            else:
                resolved.append(item)
        return resolved

    def _generate_output(
        self,
        template: 'ReportTemplate',
        data: Any,
        output_format: str,
        parameters: Dict[str, Any]
    ) -> tuple:
        """
        Generate output file in requested format.
        """
        if output_format == 'pdf':
            return self._generate_pdf(template, data, parameters)
        elif output_format == 'excel':
            return self._generate_excel(template, data, parameters)
        elif output_format == 'csv':
            return self._generate_csv(template, data, parameters)
        elif output_format == 'html':
            return self._generate_html(template, data, parameters)
        else:
            raise ValidationError(f"Unsupported format: {output_format}")

    def _generate_pdf(
        self,
        template: 'ReportTemplate',
        data: Any,
        parameters: Dict[str, Any]
    ) -> tuple:
        """
        Generate PDF report using WeasyPrint.
        """
        from weasyprint import HTML, CSS

        # Render HTML template
        html_content = self._render_html_template(template, data, parameters)

        # Convert to PDF
        buffer = io.BytesIO()
        HTML(string=html_content).write_pdf(
            buffer,
            stylesheets=[CSS(string=self._get_pdf_styles(template))]
        )

        content = buffer.getvalue()
        return content, len(content)

    def _generate_excel(
        self,
        template: 'ReportTemplate',
        data: List[Dict],
        parameters: Dict[str, Any]
    ) -> tuple:
        """
        Generate Excel report using openpyxl.
        """
        from openpyxl import Workbook
        from openpyxl.styles import Font, Alignment, PatternFill, Border, Side

        wb = Workbook()
        ws = wb.active
        ws.title = template.name[:31]  # Excel limit

        layout = template.layout_config or {}
        columns = layout.get('columns', [])

        # Header row
        header_font = Font(bold=True, color='FFFFFF')
        header_fill = PatternFill(start_color='4472C4', fill_type='solid')

        for col_idx, col_def in enumerate(columns, 1):
            cell = ws.cell(row=1, column=col_idx, value=col_def.get('label', ''))
            cell.font = header_font
            cell.fill = header_fill
            cell.alignment = Alignment(horizontal='center')

        # Data rows
        for row_idx, row_data in enumerate(data, 2):
            for col_idx, col_def in enumerate(columns, 1):
                field = col_def.get('field')
                value = row_data.get(field, '')
                ws.cell(row=row_idx, column=col_idx, value=value)

        # Auto-fit columns
        for column in ws.columns:
            max_length = max(len(str(cell.value or '')) for cell in column)
            ws.column_dimensions[column[0].column_letter].width = min(max_length + 2, 50)

        buffer = io.BytesIO()
        wb.save(buffer)
        content = buffer.getvalue()
        return content, len(content)

    def _generate_csv(
        self,
        template: 'ReportTemplate',
        data: List[Dict],
        parameters: Dict[str, Any]
    ) -> tuple:
        """
        Generate CSV report.
        """
        import csv

        layout = template.layout_config or {}
        columns = layout.get('columns', [])

        buffer = io.StringIO()
        writer = csv.writer(buffer)

        # Header row
        headers = [col.get('label', col.get('field', '')) for col in columns]
        writer.writerow(headers)

        # Data rows
        for row_data in data:
            row = [row_data.get(col.get('field'), '') for col in columns]
            writer.writerow(row)

        content = buffer.getvalue().encode('utf-8-sig')  # BOM for Excel compatibility
        return content, len(content)

    def _generate_reference(self) -> str:
        """Generate unique report reference."""
        today = datetime.now().strftime('%Y%m%d')
        seq = self.env['ir.sequence'].next_by_code('report.generation') or '000001'
        return f"RPT-{today}-{seq}"

    def _resolve_filename(
        self,
        pattern: str,
        parameters: Dict[str, Any],
        output_format: str
    ) -> str:
        """
        Resolve filename pattern with parameters.
        """
        today = fields.Date.today()
        filename = self._resolve_expression(pattern, today)

        # Replace parameter placeholders
        for key, value in parameters.items():
            filename = filename.replace(f"${{{key}}}", str(value))

        # Ensure correct extension
        if not filename.endswith(f".{output_format}"):
            filename = f"{filename}.{output_format}"

        # Sanitize filename
        import re
        filename = re.sub(r'[<>:"/\\|?*]', '_', filename)

        return filename

    def _store_report_file(
        self,
        reference: str,
        filename: str,
        content: bytes
    ) -> str:
        """
        Store generated report file in MinIO.
        """
        from minio import Minio

        client = Minio(
            self.env['ir.config_parameter'].get_param('minio.endpoint'),
            access_key=self.env['ir.config_parameter'].get_param('minio.access_key'),
            secret_key=self.env['ir.config_parameter'].get_param('minio.secret_key'),
            secure=True
        )

        bucket = 'smart-dairy-reports'
        today = datetime.now()
        object_path = f"reports/{today.year}/{today.month:02d}/{reference}/{filename}"

        client.put_object(
            bucket,
            object_path,
            io.BytesIO(content),
            len(content),
            content_type=self._get_content_type(filename)
        )

        return f"{bucket}/{object_path}"

    def _get_content_type(self, filename: str) -> str:
        """Get MIME type for file."""
        ext = filename.rsplit('.', 1)[-1].lower()
        return {
            'pdf': 'application/pdf',
            'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv': 'text/csv',
            'html': 'text/html'
        }.get(ext, 'application/octet-stream')
```

#### 3.3.3 Alert Trigger Engine

```python
# smart_dairy_reporting/services/alert_engine.py
from datetime import datetime, timedelta
from typing import Optional, Dict, Any, List
import logging

from odoo import models, fields, api
from celery import shared_task

_logger = logging.getLogger(__name__)


class ReportAlertEngine(models.AbstractModel):
    _name = 'report.alert.engine'
    _description = 'Report Alert Trigger Engine'

    def evaluate_triggers(self) -> Dict[str, int]:
        """
        Evaluate all active triggers and generate reports for breached thresholds.
        Called by scheduled cron job.
        """
        stats = {'evaluated': 0, 'triggered': 0, 'failed': 0}
        now = datetime.now()

        # Find triggers due for evaluation
        triggers = self.env['report.alert.trigger'].search([
            ('is_active', '=', True),
            '|',
            ('last_evaluated_at', '=', False),
            ('last_evaluated_at', '<=', now - timedelta(
                minutes=self.env.context.get('min_interval', 15)
            ))
        ])

        for trigger in triggers:
            try:
                result = self._evaluate_single_trigger(trigger, now)
                stats['evaluated'] += 1
                if result.get('triggered'):
                    stats['triggered'] += 1
            except Exception as e:
                _logger.error(f"Trigger evaluation failed for {trigger.id}: {e}")
                stats['failed'] += 1

        return stats

    def _evaluate_single_trigger(
        self,
        trigger: 'ReportAlertTrigger',
        evaluation_time: datetime
    ) -> Dict[str, Any]:
        """
        Evaluate a single trigger and generate report if conditions met.
        """
        import time
        start_time = time.time()

        # Fetch current metric value
        current_value = self._fetch_metric_value(trigger)

        # Check condition
        condition_met = self._check_condition(
            trigger.condition_operator,
            current_value,
            trigger.threshold_value,
            trigger.threshold_value_upper
        )

        # Check cooldown
        in_cooldown = False
        if trigger.last_triggered_at:
            cooldown_end = trigger.last_triggered_at + timedelta(
                minutes=trigger.cooldown_minutes
            )
            in_cooldown = datetime.now() < cooldown_end

        # Create evaluation record
        should_trigger = condition_met and not in_cooldown

        evaluation = self.env['report.trigger.evaluation'].create({
            'trigger_id': trigger.id,
            'evaluated_at': evaluation_time,
            'metric_value': current_value,
            'threshold_value': trigger.threshold_value,
            'condition_met': condition_met,
            'triggered_report': should_trigger,
            'evaluation_time_ms': int((time.time() - start_time) * 1000)
        })

        # Update trigger state
        trigger.write({
            'last_evaluated_at': evaluation_time,
            'current_value': current_value,
            'consecutive_triggers': (
                trigger.consecutive_triggers + 1 if condition_met
                else 0
            )
        })

        result = {
            'trigger_id': trigger.id,
            'current_value': current_value,
            'threshold': trigger.threshold_value,
            'condition_met': condition_met,
            'triggered': False
        }

        # Generate report if triggered
        if should_trigger:
            generation = self._trigger_report_generation(trigger, current_value)
            evaluation.write({'generation_id': generation.id})
            trigger.write({'last_triggered_at': datetime.now()})
            result['triggered'] = True
            result['generation_id'] = generation.id

            # Send alert notification
            self._send_alert_notification(trigger, current_value, generation)

        return result

    def _fetch_metric_value(self, trigger: 'ReportAlertTrigger') -> float:
        """
        Fetch current value of the monitored metric.
        """
        config = trigger.metric_config
        source_type = trigger.metric_source

        if source_type == 'sql_query':
            query = config.get('query')
            self.env.cr.execute(query)
            result = self.env.cr.fetchone()
            return float(result[0]) if result else 0.0

        elif source_type == 'odoo_field':
            model = config.get('model')
            field = config.get('field')
            domain = config.get('domain', [])
            aggregation = config.get('aggregation', 'sum')

            Model = self.env[model]
            if aggregation == 'count':
                return Model.search_count(domain)
            else:
                records = Model.search(domain)
                values = records.mapped(field)
                if aggregation == 'sum':
                    return sum(values)
                elif aggregation == 'avg':
                    return sum(values) / len(values) if values else 0
                elif aggregation == 'min':
                    return min(values) if values else 0
                elif aggregation == 'max':
                    return max(values) if values else 0

        elif source_type == 'analytics_metric':
            metric_name = config.get('metric')
            return self._fetch_analytics_metric(metric_name)

        raise ValueError(f"Unknown metric source: {source_type}")

    def _fetch_analytics_metric(self, metric_name: str) -> float:
        """
        Fetch metric from analytics/TimescaleDB.
        """
        metric_queries = {
            'mrr': """
                SELECT mrr_amount FROM subscription_mrr_snapshot
                WHERE snapshot_type = 'daily'
                ORDER BY snapshot_date DESC LIMIT 1
            """,
            'churn_rate': """
                SELECT churn_rate FROM subscription_churn_metrics
                WHERE period_type = 'monthly'
                ORDER BY period_end DESC LIMIT 1
            """,
            'active_subscriptions': """
                SELECT COUNT(*) FROM subscription_subscription
                WHERE status = 'active'
            """,
            'pending_orders': """
                SELECT COUNT(*) FROM sale_order
                WHERE state = 'draft'
                AND create_date >= NOW() - INTERVAL '24 hours'
            """,
            'low_stock_items': """
                SELECT COUNT(*) FROM product_product pp
                JOIN stock_quant sq ON pp.id = sq.product_id
                WHERE sq.quantity < pp.reorder_min_qty
            """
        }

        query = metric_queries.get(metric_name)
        if not query:
            raise ValueError(f"Unknown analytics metric: {metric_name}")

        self.env.cr.execute(query)
        result = self.env.cr.fetchone()
        return float(result[0]) if result else 0.0

    def _check_condition(
        self,
        operator: str,
        current_value: float,
        threshold: float,
        threshold_upper: float = None
    ) -> bool:
        """
        Check if condition is met.
        """
        if operator == 'gt':
            return current_value > threshold
        elif operator == 'gte':
            return current_value >= threshold
        elif operator == 'lt':
            return current_value < threshold
        elif operator == 'lte':
            return current_value <= threshold
        elif operator == 'eq':
            return current_value == threshold
        elif operator == 'neq':
            return current_value != threshold
        elif operator == 'between':
            return threshold <= current_value <= threshold_upper
        elif operator == 'outside':
            return current_value < threshold or current_value > threshold_upper
        else:
            raise ValueError(f"Unknown operator: {operator}")

    def _trigger_report_generation(
        self,
        trigger: 'ReportAlertTrigger',
        current_value: float
    ) -> 'ReportGeneration':
        """
        Generate report for triggered alert.
        """
        generator = self.env['report.generation.engine']

        # Prepare parameters with alert context
        parameters = {
            'alert_trigger_id': trigger.id,
            'alert_value': current_value,
            'alert_threshold': trigger.threshold_value,
            'alert_time': datetime.now().isoformat()
        }

        return generator.generate_report(
            template_id=trigger.template_id.id,
            parameters=parameters,
            output_format=trigger.template_id.default_format,
            generation_type='triggered',
            trigger_id=trigger.id
        )

    def _send_alert_notification(
        self,
        trigger: 'ReportAlertTrigger',
        current_value: float,
        generation: 'ReportGeneration'
    ):
        """
        Send notification for triggered alert.
        """
        # Find distribution config for this trigger
        distributions = self.env['report.distribution'].search([
            ('trigger_id', '=', trigger.id),
            ('is_active', '=', True)
        ])

        if distributions:
            distributor = self.env['report.distribution.engine']
            for dist in distributions:
                distributor.distribute_report(
                    generation_id=generation.id,
                    distribution_id=dist.id,
                    alert_context={
                        'trigger_name': trigger.name,
                        'current_value': current_value,
                        'threshold': trigger.threshold_value,
                        'priority': trigger.priority
                    }
                )
```

#### 3.3.4 Distribution Engine

```python
# smart_dairy_reporting/services/distribution_engine.py
from datetime import datetime
from typing import Optional, Dict, Any, List
import logging

from odoo import models, fields, api
from celery import shared_task

_logger = logging.getLogger(__name__)


class ReportDistributionEngine(models.AbstractModel):
    _name = 'report.distribution.engine'
    _description = 'Report Distribution Engine'

    def distribute_report(
        self,
        generation_id: int,
        schedule_id: int = None,
        distribution_id: int = None,
        alert_context: Dict = None
    ):
        """
        Distribute generated report to configured recipients.
        """
        generation = self.env['report.generation'].browse(generation_id)
        if not generation.exists() or generation.status != 'completed':
            raise ValueError(f"Invalid or incomplete generation: {generation_id}")

        # Find applicable distributions
        if distribution_id:
            distributions = self.env['report.distribution'].browse(distribution_id)
        elif schedule_id:
            distributions = self.env['report.distribution'].search([
                ('schedule_id', '=', schedule_id),
                ('is_active', '=', True)
            ])
        else:
            distributions = self.env['report.distribution'].search([
                ('trigger_id', '=', generation.trigger_id.id),
                ('is_active', '=', True)
            ])

        for dist in distributions:
            try:
                if dist.distribution_type == 'email':
                    self._distribute_via_email(generation, dist, alert_context)
                elif dist.distribution_type == 'portal':
                    self._publish_to_portal(generation, dist)
                elif dist.distribution_type == 'webhook':
                    self._send_to_webhook(generation, dist)
                elif dist.distribution_type == 'sftp':
                    self._upload_to_sftp(generation, dist)
            except Exception as e:
                _logger.error(f"Distribution failed for {dist.id}: {e}")
                self._log_distribution_failure(generation, dist, str(e))

    def _distribute_via_email(
        self,
        generation: 'ReportGeneration',
        distribution: 'ReportDistribution',
        alert_context: Dict = None
    ):
        """
        Send report via email to all recipients.
        """
        # Collect all recipients
        recipients = self._get_email_recipients(distribution)

        if not recipients:
            _logger.warning(f"No recipients for distribution {distribution.id}")
            return

        # Prepare email content
        subject = self._render_template(
            distribution.email_subject_template or
            f"Report: {generation.template_id.name}",
            generation,
            alert_context
        )

        body = self._render_template(
            distribution.email_body_template or self._get_default_email_body(),
            generation,
            alert_context
        )

        # Get report file
        attachments = []
        if distribution.attach_report:
            file_content = self._get_report_file(generation)
            if file_content:
                attachments.append((
                    generation.filename,
                    file_content
                ))

        # Add download link
        if distribution.include_link:
            download_url = self._get_download_url(generation)
            body += f"\n\nDownload Report: {download_url}"

        # Send to each recipient
        for recipient_email in recipients:
            try:
                self._send_email(
                    to=recipient_email,
                    cc=distribution.email_cc or [],
                    bcc=distribution.email_bcc or [],
                    subject=subject,
                    body=body,
                    attachments=attachments
                )

                self._log_distribution_success(
                    generation, distribution, recipient_email
                )

            except Exception as e:
                _logger.error(f"Email send failed to {recipient_email}: {e}")
                self._log_distribution_failure(
                    generation, distribution, str(e), recipient_email
                )

    def _get_email_recipients(
        self,
        distribution: 'ReportDistribution'
    ) -> List[str]:
        """
        Collect all email recipients from distribution config.
        """
        recipients = set()

        # Direct email addresses
        if distribution.email_to:
            recipients.update(distribution.email_to)

        # Dynamic recipients
        for recipient in distribution.recipient_ids:
            if not recipient.is_active:
                continue

            if recipient.recipient_type == 'user':
                if recipient.user_id and recipient.user_id.email:
                    recipients.add(recipient.user_id.email)

            elif recipient.recipient_type == 'role':
                users = self.env['res.users'].search([
                    ('groups_id', 'in', [recipient.role_id.id])
                ])
                recipients.update(u.email for u in users if u.email)

            elif recipient.recipient_type == 'team':
                team = recipient.team_id
                if team.user_id and team.user_id.email:
                    recipients.add(team.user_id.email)
                for member in team.member_ids:
                    if member.email:
                        recipients.add(member.email)

            elif recipient.recipient_type == 'external_email':
                if recipient.external_email:
                    recipients.add(recipient.external_email)

        return list(recipients)

    def _render_template(
        self,
        template: str,
        generation: 'ReportGeneration',
        alert_context: Dict = None
    ) -> str:
        """
        Render email template with report context.
        """
        from jinja2 import Template

        context = {
            'report_name': generation.template_id.name,
            'report_date': generation.created_at.strftime('%Y-%m-%d'),
            'report_time': generation.created_at.strftime('%H:%M'),
            'filename': generation.filename,
            'reference': generation.reference,
            'generation_type': generation.generation_type,
            'company_name': self.env.company.name
        }

        if alert_context:
            context.update({
                'is_alert': True,
                'alert_name': alert_context.get('trigger_name'),
                'alert_value': alert_context.get('current_value'),
                'alert_threshold': alert_context.get('threshold'),
                'alert_priority': alert_context.get('priority')
            })

        return Template(template).render(**context)

    def _get_default_email_body(self) -> str:
        """
        Default email body template.
        """
        return """
Dear Recipient,

Your scheduled report "{{ report_name }}" is now ready.

Report Details:
- Generated: {{ report_date }} at {{ report_time }}
- Reference: {{ reference }}
- File: {{ filename }}

{% if is_alert %}
ALERT NOTIFICATION:
This report was triggered because {{ alert_name }} reached {{ alert_value }}
(threshold: {{ alert_threshold }}).
Priority: {{ alert_priority|upper }}
{% endif %}

Please find the report attached or use the download link below.

Best regards,
{{ company_name }} Reporting System
        """

    def _send_email(
        self,
        to: str,
        cc: List[str],
        bcc: List[str],
        subject: str,
        body: str,
        attachments: List[tuple]
    ):
        """
        Send email using Odoo mail system.
        """
        mail_values = {
            'subject': subject,
            'body_html': body.replace('\n', '<br/>'),
            'email_to': to,
            'email_cc': ','.join(cc) if cc else False,
            'email_bcc': ','.join(bcc) if bcc else False,
        }

        if attachments:
            attachment_ids = []
            for filename, content in attachments:
                attachment = self.env['ir.attachment'].create({
                    'name': filename,
                    'datas': content,
                    'type': 'binary'
                })
                attachment_ids.append(attachment.id)
            mail_values['attachment_ids'] = [(6, 0, attachment_ids)]

        mail = self.env['mail.mail'].create(mail_values)
        mail.send()

    def _get_report_file(self, generation: 'ReportGeneration') -> bytes:
        """
        Retrieve report file content from storage.
        """
        from minio import Minio
        import io

        if not generation.file_path:
            return None

        client = Minio(
            self.env['ir.config_parameter'].get_param('minio.endpoint'),
            access_key=self.env['ir.config_parameter'].get_param('minio.access_key'),
            secret_key=self.env['ir.config_parameter'].get_param('minio.secret_key'),
            secure=True
        )

        bucket, object_path = generation.file_path.split('/', 1)
        response = client.get_object(bucket, object_path)

        return response.read()

    def _get_download_url(self, generation: 'ReportGeneration') -> str:
        """
        Generate secure download URL for report.
        """
        base_url = self.env['ir.config_parameter'].get_param('web.base.url')
        return f"{base_url}/report/download/{generation.access_token}"

    def _publish_to_portal(
        self,
        generation: 'ReportGeneration',
        distribution: 'ReportDistribution'
    ):
        """
        Publish report to customer portal.
        """
        self.env['report.portal.publication'].create({
            'generation_id': generation.id,
            'category_id': distribution.portal_category_id.id,
            'visibility': distribution.portal_visibility,
            'published_at': datetime.now()
        })

    def _send_to_webhook(
        self,
        generation: 'ReportGeneration',
        distribution: 'ReportDistribution'
    ):
        """
        Send report notification to webhook endpoint.
        """
        import requests

        payload = {
            'event': 'report.generated',
            'report': {
                'reference': generation.reference,
                'name': generation.template_id.name,
                'filename': generation.filename,
                'download_url': self._get_download_url(generation),
                'generated_at': generation.created_at.isoformat()
            }
        }

        response = requests.request(
            method=distribution.webhook_method,
            url=distribution.webhook_url,
            json=payload,
            headers=distribution.webhook_headers or {},
            timeout=30
        )

        if response.status_code >= 400:
            raise Exception(f"Webhook failed: {response.status_code}")

    def _upload_to_sftp(
        self,
        generation: 'ReportGeneration',
        distribution: 'ReportDistribution'
    ):
        """
        Upload report file to SFTP server.
        """
        import paramiko
        from cryptography.fernet import Fernet

        # Decrypt password
        key = self.env['ir.config_parameter'].get_param('encryption.key')
        fernet = Fernet(key.encode())
        password = fernet.decrypt(
            distribution.sftp_password_encrypted.encode()
        ).decode()

        # Connect to SFTP
        transport = paramiko.Transport((
            distribution.sftp_host,
            distribution.sftp_port
        ))
        transport.connect(
            username=distribution.sftp_username,
            password=password
        )

        sftp = paramiko.SFTPClient.from_transport(transport)

        # Upload file
        file_content = self._get_report_file(generation)
        remote_path = f"{distribution.sftp_path}/{generation.filename}"

        with sftp.file(remote_path, 'wb') as f:
            f.write(file_content)

        sftp.close()
        transport.close()

    def _log_distribution_success(
        self,
        generation: 'ReportGeneration',
        distribution: 'ReportDistribution',
        recipient: str
    ):
        """
        Log successful distribution.
        """
        self.env['report.distribution.log'].create({
            'distribution_id': distribution.id,
            'generation_id': generation.id,
            'recipient_email': recipient,
            'status': 'sent',
            'sent_at': datetime.now()
        })

    def _log_distribution_failure(
        self,
        generation: 'ReportGeneration',
        distribution: 'ReportDistribution',
        error: str,
        recipient: str = None
    ):
        """
        Log failed distribution.
        """
        self.env['report.distribution.log'].create({
            'distribution_id': distribution.id,
            'generation_id': generation.id,
            'recipient_email': recipient,
            'status': 'failed',
            'error_message': error
        })
```

### 3.4 REST API Specifications

#### 3.4.1 Report Template APIs

```yaml
# Report Template Endpoints
/api/v1/reports/templates:
  GET:
    summary: List report templates
    parameters:
      - name: category_id
        type: integer
      - name: search
        type: string
      - name: visibility
        type: string
        enum: [private, team, organization, public]
    responses:
      200:
        schema:
          type: array
          items:
            $ref: '#/definitions/ReportTemplate'

  POST:
    summary: Create new template
    body:
      $ref: '#/definitions/ReportTemplateCreate'
    responses:
      201:
        schema:
          $ref: '#/definitions/ReportTemplate'

/api/v1/reports/templates/{id}:
  GET:
    summary: Get template details
    responses:
      200:
        schema:
          $ref: '#/definitions/ReportTemplate'

  PUT:
    summary: Update template
    responses:
      200:
        schema:
          $ref: '#/definitions/ReportTemplate'

  DELETE:
    summary: Delete template
    responses:
      204:
        description: Template deleted

/api/v1/reports/templates/{id}/preview:
  POST:
    summary: Preview report with sample data
    body:
      parameters:
        type: object
    responses:
      200:
        schema:
          type: object
          properties:
            preview_html:
              type: string
            row_count:
              type: integer
```

#### 3.4.2 Report Schedule APIs

```yaml
/api/v1/reports/schedules:
  GET:
    summary: List report schedules
    parameters:
      - name: template_id
        type: integer
      - name: status
        type: string
        enum: [active, paused, completed]
    responses:
      200:
        schema:
          type: array
          items:
            $ref: '#/definitions/ReportSchedule'

  POST:
    summary: Create schedule
    body:
      $ref: '#/definitions/ReportScheduleCreate'
    responses:
      201:
        schema:
          $ref: '#/definitions/ReportSchedule'

/api/v1/reports/schedules/{id}:
  PUT:
    summary: Update schedule
    responses:
      200:
        schema:
          $ref: '#/definitions/ReportSchedule'

  DELETE:
    summary: Delete schedule
    responses:
      204:
        description: Schedule deleted

/api/v1/reports/schedules/{id}/pause:
  POST:
    summary: Pause schedule
    responses:
      200:
        schema:
          $ref: '#/definitions/ReportSchedule'

/api/v1/reports/schedules/{id}/resume:
  POST:
    summary: Resume schedule
    responses:
      200:
        schema:
          $ref: '#/definitions/ReportSchedule'

/api/v1/reports/schedules/{id}/run-now:
  POST:
    summary: Trigger immediate execution
    responses:
      202:
        schema:
          type: object
          properties:
            execution_id:
              type: integer
            message:
              type: string
```

#### 3.4.3 Report Generation APIs

```yaml
/api/v1/reports/generate:
  POST:
    summary: Generate report on-demand
    body:
      type: object
      required:
        - template_id
      properties:
        template_id:
          type: integer
        parameters:
          type: object
        output_format:
          type: string
          enum: [pdf, excel, csv, html]
    responses:
      202:
        schema:
          type: object
          properties:
            generation_id:
              type: integer
            reference:
              type: string
            status:
              type: string

/api/v1/reports/generations:
  GET:
    summary: List generated reports
    parameters:
      - name: template_id
        type: integer
      - name: status
        type: string
      - name: date_from
        type: date
      - name: date_to
        type: date
    responses:
      200:
        schema:
          type: array
          items:
            $ref: '#/definitions/ReportGeneration'

/api/v1/reports/generations/{id}:
  GET:
    summary: Get generation details
    responses:
      200:
        schema:
          $ref: '#/definitions/ReportGeneration'

/api/v1/reports/generations/{id}/download:
  GET:
    summary: Download generated report
    responses:
      200:
        content:
          application/octet-stream:
            schema:
              type: file

/api/v1/reports/download/{token}:
  GET:
    summary: Download via access token
    responses:
      200:
        content:
          application/octet-stream:
            schema:
              type: file
```

### 3.5 Frontend Components

#### 3.5.1 Report Dashboard Component

```typescript
// src/components/reports/ReportDashboard.tsx
import React, { useState, useEffect } from 'react';
import { Card, Table, Button, Tag, Space, Tabs, Badge, Statistic, Row, Col } from 'antd';
import {
  FileTextOutlined,
  ClockCircleOutlined,
  AlertOutlined,
  DownloadOutlined,
  PlayCircleOutlined,
  PauseCircleOutlined
} from '@ant-design/icons';
import { reportService } from '../../services/reportService';

interface DashboardStats {
  totalTemplates: number;
  activeSchedules: number;
  pendingReports: number;
  alertsTriggered: number;
  reportsGenerated24h: number;
  averageGenerationTime: number;
}

interface RecentReport {
  id: number;
  reference: string;
  templateName: string;
  generationType: string;
  status: string;
  createdAt: string;
  fileSize: number;
}

export const ReportDashboard: React.FC = () => {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [recentReports, setRecentReports] = useState<RecentReport[]>([]);
  const [activeSchedules, setActiveSchedules] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    setLoading(true);
    try {
      const [statsData, reportsData, schedulesData] = await Promise.all([
        reportService.getDashboardStats(),
        reportService.getRecentReports(10),
        reportService.getActiveSchedules()
      ]);
      setStats(statsData);
      setRecentReports(reportsData);
      setActiveSchedules(schedulesData);
    } catch (error) {
      console.error('Failed to load dashboard:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = async (reportId: number) => {
    try {
      await reportService.downloadReport(reportId);
    } catch (error) {
      console.error('Download failed:', error);
    }
  };

  const handleRunNow = async (scheduleId: number) => {
    try {
      await reportService.runScheduleNow(scheduleId);
      loadDashboardData();
    } catch (error) {
      console.error('Run now failed:', error);
    }
  };

  const getStatusTag = (status: string) => {
    const statusConfig: Record<string, { color: string; text: string }> = {
      completed: { color: 'success', text: 'Completed' },
      generating: { color: 'processing', text: 'Generating' },
      queued: { color: 'default', text: 'Queued' },
      failed: { color: 'error', text: 'Failed' }
    };
    const config = statusConfig[status] || { color: 'default', text: status };
    return <Tag color={config.color}>{config.text}</Tag>;
  };

  const reportsColumns = [
    {
      title: 'Reference',
      dataIndex: 'reference',
      key: 'reference',
      render: (ref: string) => <a>{ref}</a>
    },
    {
      title: 'Report',
      dataIndex: 'templateName',
      key: 'templateName'
    },
    {
      title: 'Type',
      dataIndex: 'generationType',
      key: 'generationType',
      render: (type: string) => {
        const icons: Record<string, React.ReactNode> = {
          scheduled: <ClockCircleOutlined />,
          triggered: <AlertOutlined />,
          manual: <FileTextOutlined />
        };
        return <Space>{icons[type]} {type}</Space>;
      }
    },
    {
      title: 'Status',
      dataIndex: 'status',
      key: 'status',
      render: (status: string) => getStatusTag(status)
    },
    {
      title: 'Generated',
      dataIndex: 'createdAt',
      key: 'createdAt',
      render: (date: string) => new Date(date).toLocaleString()
    },
    {
      title: 'Actions',
      key: 'actions',
      render: (_: any, record: RecentReport) => (
        <Space>
          {record.status === 'completed' && (
            <Button
              type="link"
              icon={<DownloadOutlined />}
              onClick={() => handleDownload(record.id)}
            >
              Download
            </Button>
          )}
        </Space>
      )
    }
  ];

  const schedulesColumns = [
    {
      title: 'Schedule',
      dataIndex: 'name',
      key: 'name'
    },
    {
      title: 'Template',
      dataIndex: ['template', 'name'],
      key: 'template'
    },
    {
      title: 'Frequency',
      dataIndex: 'cronExpression',
      key: 'frequency',
      render: (cron: string) => formatCronExpression(cron)
    },
    {
      title: 'Next Run',
      dataIndex: 'nextRunAt',
      key: 'nextRunAt',
      render: (date: string) => date ? new Date(date).toLocaleString() : '-'
    },
    {
      title: 'Status',
      dataIndex: 'status',
      key: 'status',
      render: (status: string) => (
        <Badge
          status={status === 'active' ? 'success' : 'default'}
          text={status}
        />
      )
    },
    {
      title: 'Actions',
      key: 'actions',
      render: (_: any, record: any) => (
        <Space>
          <Button
            type="link"
            icon={<PlayCircleOutlined />}
            onClick={() => handleRunNow(record.id)}
          >
            Run Now
          </Button>
        </Space>
      )
    }
  ];

  return (
    <div className="report-dashboard">
      {/* Statistics Cards */}
      <Row gutter={16} style={{ marginBottom: 24 }}>
        <Col span={4}>
          <Card>
            <Statistic
              title="Report Templates"
              value={stats?.totalTemplates || 0}
              prefix={<FileTextOutlined />}
            />
          </Card>
        </Col>
        <Col span={4}>
          <Card>
            <Statistic
              title="Active Schedules"
              value={stats?.activeSchedules || 0}
              prefix={<ClockCircleOutlined />}
            />
          </Card>
        </Col>
        <Col span={4}>
          <Card>
            <Statistic
              title="Pending Reports"
              value={stats?.pendingReports || 0}
              valueStyle={{ color: stats?.pendingReports ? '#faad14' : undefined }}
            />
          </Card>
        </Col>
        <Col span={4}>
          <Card>
            <Statistic
              title="Alerts Triggered"
              value={stats?.alertsTriggered || 0}
              prefix={<AlertOutlined />}
              valueStyle={{ color: stats?.alertsTriggered ? '#ff4d4f' : undefined }}
            />
          </Card>
        </Col>
        <Col span={4}>
          <Card>
            <Statistic
              title="Reports (24h)"
              value={stats?.reportsGenerated24h || 0}
            />
          </Card>
        </Col>
        <Col span={4}>
          <Card>
            <Statistic
              title="Avg Generation"
              value={stats?.averageGenerationTime || 0}
              suffix="ms"
            />
          </Card>
        </Col>
      </Row>

      {/* Main Content Tabs */}
      <Card>
        <Tabs defaultActiveKey="recent">
          <Tabs.TabPane tab="Recent Reports" key="recent">
            <Table
              columns={reportsColumns}
              dataSource={recentReports}
              rowKey="id"
              loading={loading}
              pagination={{ pageSize: 10 }}
            />
          </Tabs.TabPane>
          <Tabs.TabPane tab="Active Schedules" key="schedules">
            <Table
              columns={schedulesColumns}
              dataSource={activeSchedules}
              rowKey="id"
              loading={loading}
              pagination={{ pageSize: 10 }}
            />
          </Tabs.TabPane>
        </Tabs>
      </Card>
    </div>
  );
};

function formatCronExpression(cron: string): string {
  // Simple cron to human-readable conversion
  const parts = cron.split(' ');
  if (parts.length < 5) return cron;

  const [minute, hour, dayOfMonth, month, dayOfWeek] = parts;

  if (dayOfMonth === '*' && month === '*') {
    if (dayOfWeek === '*') {
      return `Daily at ${hour}:${minute.padStart(2, '0')}`;
    } else if (dayOfWeek === '1-5') {
      return `Weekdays at ${hour}:${minute.padStart(2, '0')}`;
    }
  }

  return cron;
}
```

#### 3.5.2 Schedule Builder Component

```typescript
// src/components/reports/ScheduleBuilder.tsx
import React, { useState, useEffect } from 'react';
import {
  Form, Input, Select, DatePicker, TimePicker, Switch, Button,
  Card, Row, Col, Divider, Space, Alert, Collapse
} from 'antd';
import { InfoCircleOutlined } from '@ant-design/icons';
import { reportService } from '../../services/reportService';
import { RecipientSelector } from './RecipientSelector';

interface ScheduleBuilderProps {
  templateId?: number;
  scheduleId?: number;
  onSuccess?: (schedule: any) => void;
  onCancel?: () => void;
}

interface ScheduleFormValues {
  name: string;
  templateId: number;
  scheduleType: 'once' | 'recurring';
  frequency: string;
  customCron?: string;
  timezone: string;
  startDate?: Date;
  endDate?: Date;
  runTime: Date;
  respectHolidays: boolean;
  skipWeekends: boolean;
  outputFormat: string;
  filenamePattern?: string;
  distributionType: string;
  recipients: any[];
  emailSubject?: string;
  emailBody?: string;
  attachReport: boolean;
  includeLink: boolean;
}

const frequencyOptions = [
  { value: 'daily', label: 'Daily', cron: '0 {hour} * * *' },
  { value: 'weekdays', label: 'Weekdays (Mon-Fri)', cron: '0 {hour} * * 1-5' },
  { value: 'weekly', label: 'Weekly', cron: '0 {hour} * * {dow}' },
  { value: 'biweekly', label: 'Bi-weekly', cron: '0 {hour} 1,15 * *' },
  { value: 'monthly', label: 'Monthly', cron: '0 {hour} {dom} * *' },
  { value: 'quarterly', label: 'Quarterly', cron: '0 {hour} 1 1,4,7,10 *' },
  { value: 'custom', label: 'Custom (Cron)', cron: '' }
];

const timezones = [
  { value: 'Asia/Dhaka', label: 'Bangladesh (GMT+6)' },
  { value: 'Asia/Kolkata', label: 'India (GMT+5:30)' },
  { value: 'UTC', label: 'UTC' }
];

export const ScheduleBuilder: React.FC<ScheduleBuilderProps> = ({
  templateId,
  scheduleId,
  onSuccess,
  onCancel
}) => {
  const [form] = Form.useForm<ScheduleFormValues>();
  const [templates, setTemplates] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [previewCron, setPreviewCron] = useState('');
  const [selectedTemplate, setSelectedTemplate] = useState<any>(null);

  useEffect(() => {
    loadTemplates();
    if (scheduleId) {
      loadSchedule(scheduleId);
    }
  }, [scheduleId]);

  useEffect(() => {
    if (templateId) {
      form.setFieldValue('templateId', templateId);
      loadTemplateDetails(templateId);
    }
  }, [templateId]);

  const loadTemplates = async () => {
    try {
      const data = await reportService.getTemplates();
      setTemplates(data);
    } catch (error) {
      console.error('Failed to load templates:', error);
    }
  };

  const loadTemplateDetails = async (id: number) => {
    try {
      const template = await reportService.getTemplate(id);
      setSelectedTemplate(template);
    } catch (error) {
      console.error('Failed to load template:', error);
    }
  };

  const loadSchedule = async (id: number) => {
    try {
      const schedule = await reportService.getSchedule(id);
      form.setFieldsValue({
        name: schedule.name,
        templateId: schedule.templateId,
        // ... map other fields
      });
    } catch (error) {
      console.error('Failed to load schedule:', error);
    }
  };

  const handleFrequencyChange = (frequency: string) => {
    const option = frequencyOptions.find(f => f.value === frequency);
    if (option && option.cron) {
      const runTime = form.getFieldValue('runTime');
      const hour = runTime ? runTime.hour() : 8;
      let cron = option.cron.replace('{hour}', hour.toString());
      cron = cron.replace('{dow}', '1'); // Monday
      cron = cron.replace('{dom}', '1'); // First of month
      setPreviewCron(cron);
    }
  };

  const handleSubmit = async (values: ScheduleFormValues) => {
    setLoading(true);
    try {
      const scheduleData = {
        name: values.name,
        template_id: values.templateId,
        schedule_type: values.scheduleType,
        cron_expression: values.frequency === 'custom'
          ? values.customCron
          : buildCronExpression(values),
        timezone: values.timezone,
        start_date: values.startDate?.toISOString().split('T')[0],
        end_date: values.endDate?.toISOString().split('T')[0],
        respect_holidays: values.respectHolidays,
        skip_weekends: values.skipWeekends,
        output_format: values.outputFormat,
        filename_pattern: values.filenamePattern,
        distribution: {
          type: values.distributionType,
          recipients: values.recipients,
          email_subject: values.emailSubject,
          email_body: values.emailBody,
          attach_report: values.attachReport,
          include_link: values.includeLink
        }
      };

      let result;
      if (scheduleId) {
        result = await reportService.updateSchedule(scheduleId, scheduleData);
      } else {
        result = await reportService.createSchedule(scheduleData);
      }

      onSuccess?.(result);
    } catch (error) {
      console.error('Failed to save schedule:', error);
    } finally {
      setLoading(false);
    }
  };

  const buildCronExpression = (values: ScheduleFormValues): string => {
    const hour = values.runTime?.hour() || 8;
    const minute = values.runTime?.minute() || 0;

    const option = frequencyOptions.find(f => f.value === values.frequency);
    if (!option) return `${minute} ${hour} * * *`;

    let cron = option.cron
      .replace('{hour}', hour.toString())
      .replace('{minute}', minute.toString());

    return cron;
  };

  return (
    <Form
      form={form}
      layout="vertical"
      onFinish={handleSubmit}
      initialValues={{
        scheduleType: 'recurring',
        frequency: 'daily',
        timezone: 'Asia/Dhaka',
        respectHolidays: true,
        skipWeekends: false,
        outputFormat: 'pdf',
        distributionType: 'email',
        attachReport: true,
        includeLink: true
      }}
    >
      <Card title="Schedule Configuration" style={{ marginBottom: 16 }}>
        <Row gutter={16}>
          <Col span={12}>
            <Form.Item
              name="name"
              label="Schedule Name"
              rules={[{ required: true, message: 'Please enter schedule name' }]}
            >
              <Input placeholder="Daily Sales Report" />
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item
              name="templateId"
              label="Report Template"
              rules={[{ required: true, message: 'Please select a template' }]}
            >
              <Select
                placeholder="Select template"
                onChange={(id) => loadTemplateDetails(id)}
                options={templates.map(t => ({
                  value: t.id,
                  label: t.name
                }))}
              />
            </Form.Item>
          </Col>
        </Row>

        <Divider>Timing</Divider>

        <Row gutter={16}>
          <Col span={8}>
            <Form.Item
              name="frequency"
              label="Frequency"
              rules={[{ required: true }]}
            >
              <Select
                options={frequencyOptions}
                onChange={handleFrequencyChange}
              />
            </Form.Item>
          </Col>
          <Col span={8}>
            <Form.Item
              name="runTime"
              label="Run Time"
              rules={[{ required: true, message: 'Please select run time' }]}
            >
              <TimePicker format="HH:mm" style={{ width: '100%' }} />
            </Form.Item>
          </Col>
          <Col span={8}>
            <Form.Item name="timezone" label="Timezone">
              <Select options={timezones} />
            </Form.Item>
          </Col>
        </Row>

        <Form.Item
          noStyle
          shouldUpdate={(prev, curr) => prev.frequency !== curr.frequency}
        >
          {({ getFieldValue }) =>
            getFieldValue('frequency') === 'custom' && (
              <Form.Item
                name="customCron"
                label="Cron Expression"
                rules={[{ required: true, message: 'Please enter cron expression' }]}
                extra="Format: minute hour day month weekday"
              >
                <Input placeholder="0 8 * * 1-5" />
              </Form.Item>
            )
          }
        </Form.Item>

        {previewCron && (
          <Alert
            message={`Schedule Preview: ${previewCron}`}
            type="info"
            showIcon
            icon={<InfoCircleOutlined />}
            style={{ marginBottom: 16 }}
          />
        )}

        <Row gutter={16}>
          <Col span={12}>
            <Form.Item name="startDate" label="Start Date">
              <DatePicker style={{ width: '100%' }} />
            </Form.Item>
          </Col>
          <Col span={12}>
            <Form.Item name="endDate" label="End Date (Optional)">
              <DatePicker style={{ width: '100%' }} />
            </Form.Item>
          </Col>
        </Row>

        <Row gutter={16}>
          <Col span={8}>
            <Form.Item name="respectHolidays" valuePropName="checked">
              <Switch /> Skip Holidays
            </Form.Item>
          </Col>
          <Col span={8}>
            <Form.Item name="skipWeekends" valuePropName="checked">
              <Switch /> Skip Weekends
            </Form.Item>
          </Col>
        </Row>
      </Card>

      <Card title="Output Configuration" style={{ marginBottom: 16 }}>
        <Row gutter={16}>
          <Col span={8}>
            <Form.Item name="outputFormat" label="Output Format">
              <Select
                options={
                  selectedTemplate?.supportedFormats?.map((f: string) => ({
                    value: f,
                    label: f.toUpperCase()
                  })) || [
                    { value: 'pdf', label: 'PDF' },
                    { value: 'excel', label: 'Excel' },
                    { value: 'csv', label: 'CSV' }
                  ]
                }
              />
            </Form.Item>
          </Col>
          <Col span={16}>
            <Form.Item
              name="filenamePattern"
              label="Filename Pattern"
              extra="Use {{date}}, {{datetime}}, parameters like ${param_name}"
            >
              <Input placeholder="sales_report_{{date}}.pdf" />
            </Form.Item>
          </Col>
        </Row>
      </Card>

      <Card title="Distribution" style={{ marginBottom: 16 }}>
        <Form.Item name="distributionType" label="Distribution Method">
          <Select
            options={[
              { value: 'email', label: 'Email' },
              { value: 'portal', label: 'Report Portal' },
              { value: 'webhook', label: 'Webhook' },
              { value: 'sftp', label: 'SFTP' }
            ]}
          />
        </Form.Item>

        <Form.Item
          noStyle
          shouldUpdate={(prev, curr) =>
            prev.distributionType !== curr.distributionType
          }
        >
          {({ getFieldValue }) =>
            getFieldValue('distributionType') === 'email' && (
              <>
                <Form.Item
                  name="recipients"
                  label="Recipients"
                  rules={[{ required: true, message: 'Add at least one recipient' }]}
                >
                  <RecipientSelector />
                </Form.Item>

                <Collapse ghost>
                  <Collapse.Panel header="Email Customization" key="email">
                    <Form.Item name="emailSubject" label="Email Subject">
                      <Input placeholder="Your {{report_name}} is ready" />
                    </Form.Item>
                    <Form.Item name="emailBody" label="Email Body">
                      <Input.TextArea
                        rows={4}
                        placeholder="Email body template..."
                      />
                    </Form.Item>
                  </Collapse.Panel>
                </Collapse>

                <Row gutter={16}>
                  <Col span={12}>
                    <Form.Item name="attachReport" valuePropName="checked">
                      <Switch /> Attach Report File
                    </Form.Item>
                  </Col>
                  <Col span={12}>
                    <Form.Item name="includeLink" valuePropName="checked">
                      <Switch /> Include Download Link
                    </Form.Item>
                  </Col>
                </Row>
              </>
            )
          }
        </Form.Item>
      </Card>

      <Space>
        <Button type="primary" htmlType="submit" loading={loading}>
          {scheduleId ? 'Update Schedule' : 'Create Schedule'}
        </Button>
        <Button onClick={onCancel}>Cancel</Button>
      </Space>
    </Form>
  );
};
```

#### 3.5.3 Report Builder Component

```typescript
// src/components/reports/ReportBuilder.tsx
import React, { useState, useCallback } from 'react';
import {
  Card, Row, Col, Tree, Table, Button, Space, Tabs,
  Form, Input, Select, Drawer, Modal, message
} from 'antd';
import {
  PlusOutlined, DeleteOutlined, EyeOutlined,
  DragOutlined, SettingOutlined
} from '@ant-design/icons';
import { DndProvider, useDrag, useDrop } from 'react-dnd';
import { HTML5Backend } from 'react-dnd-html5-backend';
import { reportService } from '../../services/reportService';

interface DataSource {
  type: 'sql_query' | 'odoo_model';
  config: any;
}

interface Column {
  id: string;
  field: string;
  label: string;
  type: string;
  width?: number;
  format?: string;
  aggregation?: string;
}

interface ReportBuilderProps {
  templateId?: number;
  onSave?: (template: any) => void;
}

export const ReportBuilder: React.FC<ReportBuilderProps> = ({
  templateId,
  onSave
}) => {
  const [form] = Form.useForm();
  const [dataSource, setDataSource] = useState<DataSource | null>(null);
  const [availableFields, setAvailableFields] = useState<any[]>([]);
  const [selectedColumns, setSelectedColumns] = useState<Column[]>([]);
  const [previewData, setPreviewData] = useState<any[]>([]);
  const [previewVisible, setPreviewVisible] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleDataSourceChange = async (type: string, config: any) => {
    setLoading(true);
    try {
      // Fetch available fields from data source
      const fields = await reportService.getDataSourceFields(type, config);
      setAvailableFields(fields);
      setDataSource({ type: type as any, config });
    } catch (error) {
      message.error('Failed to load data source fields');
    } finally {
      setLoading(false);
    }
  };

  const handleAddColumn = (field: any) => {
    const newColumn: Column = {
      id: `col_${Date.now()}`,
      field: field.name,
      label: field.label || field.name,
      type: field.type,
      width: 150
    };
    setSelectedColumns([...selectedColumns, newColumn]);
  };

  const handleRemoveColumn = (columnId: string) => {
    setSelectedColumns(selectedColumns.filter(c => c.id !== columnId));
  };

  const handleMoveColumn = useCallback((dragIndex: number, hoverIndex: number) => {
    const newColumns = [...selectedColumns];
    const [removed] = newColumns.splice(dragIndex, 1);
    newColumns.splice(hoverIndex, 0, removed);
    setSelectedColumns(newColumns);
  }, [selectedColumns]);

  const handlePreview = async () => {
    setLoading(true);
    try {
      const data = await reportService.previewReport({
        dataSource,
        columns: selectedColumns,
        parameters: form.getFieldValue('parameters') || {}
      });
      setPreviewData(data.rows);
      setPreviewVisible(true);
    } catch (error) {
      message.error('Failed to generate preview');
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    try {
      const values = await form.validateFields();
      const templateData = {
        ...values,
        data_source_type: dataSource?.type,
        data_source_config: dataSource?.config,
        layout_config: {
          columns: selectedColumns,
          layout_type: 'tabular'
        }
      };

      let result;
      if (templateId) {
        result = await reportService.updateTemplate(templateId, templateData);
      } else {
        result = await reportService.createTemplate(templateData);
      }

      message.success('Template saved successfully');
      onSave?.(result);
    } catch (error) {
      message.error('Failed to save template');
    }
  };

  return (
    <DndProvider backend={HTML5Backend}>
      <div className="report-builder">
        <Row gutter={16}>
          {/* Left Panel - Data Source & Fields */}
          <Col span={6}>
            <Card title="Data Source" size="small">
              <Form.Item label="Source Type">
                <Select
                  placeholder="Select source"
                  onChange={(type) => {
                    if (type === 'odoo_model') {
                      // Show model selector
                    }
                  }}
                  options={[
                    { value: 'odoo_model', label: 'Odoo Model' },
                    { value: 'sql_query', label: 'SQL Query' }
                  ]}
                />
              </Form.Item>

              <div className="available-fields">
                <h4>Available Fields</h4>
                <Tree
                  treeData={availableFields.map(f => ({
                    key: f.name,
                    title: (
                      <Space>
                        <span>{f.label || f.name}</span>
                        <Button
                          type="link"
                          size="small"
                          icon={<PlusOutlined />}
                          onClick={() => handleAddColumn(f)}
                        />
                      </Space>
                    )
                  }))}
                />
              </div>
            </Card>
          </Col>

          {/* Middle Panel - Column Configuration */}
          <Col span={12}>
            <Card
              title="Report Columns"
              size="small"
              extra={
                <Space>
                  <Button
                    icon={<EyeOutlined />}
                    onClick={handlePreview}
                    loading={loading}
                  >
                    Preview
                  </Button>
                </Space>
              }
            >
              <Table
                dataSource={selectedColumns}
                rowKey="id"
                pagination={false}
                size="small"
                columns={[
                  {
                    title: '',
                    width: 30,
                    render: () => <DragOutlined style={{ cursor: 'grab' }} />
                  },
                  {
                    title: 'Field',
                    dataIndex: 'field',
                    width: 120
                  },
                  {
                    title: 'Label',
                    dataIndex: 'label',
                    render: (label, record, index) => (
                      <Input
                        value={label}
                        size="small"
                        onChange={(e) => {
                          const newColumns = [...selectedColumns];
                          newColumns[index].label = e.target.value;
                          setSelectedColumns(newColumns);
                        }}
                      />
                    )
                  },
                  {
                    title: 'Width',
                    dataIndex: 'width',
                    width: 80,
                    render: (width, record, index) => (
                      <Input
                        type="number"
                        value={width}
                        size="small"
                        onChange={(e) => {
                          const newColumns = [...selectedColumns];
                          newColumns[index].width = parseInt(e.target.value);
                          setSelectedColumns(newColumns);
                        }}
                      />
                    )
                  },
                  {
                    title: '',
                    width: 50,
                    render: (_, record) => (
                      <Button
                        type="link"
                        danger
                        size="small"
                        icon={<DeleteOutlined />}
                        onClick={() => handleRemoveColumn(record.id)}
                      />
                    )
                  }
                ]}
              />
            </Card>
          </Col>

          {/* Right Panel - Template Properties */}
          <Col span={6}>
            <Card title="Template Properties" size="small">
              <Form form={form} layout="vertical" size="small">
                <Form.Item
                  name="name"
                  label="Template Name"
                  rules={[{ required: true }]}
                >
                  <Input placeholder="Sales Report" />
                </Form.Item>

                <Form.Item name="code" label="Code" rules={[{ required: true }]}>
                  <Input placeholder="sales_report" />
                </Form.Item>

                <Form.Item name="category_id" label="Category">
                  <Select placeholder="Select category" />
                </Form.Item>

                <Form.Item name="description" label="Description">
                  <Input.TextArea rows={3} />
                </Form.Item>

                <Form.Item
                  name="supported_formats"
                  label="Supported Formats"
                  initialValue={['pdf', 'excel', 'csv']}
                >
                  <Select
                    mode="multiple"
                    options={[
                      { value: 'pdf', label: 'PDF' },
                      { value: 'excel', label: 'Excel' },
                      { value: 'csv', label: 'CSV' },
                      { value: 'html', label: 'HTML' }
                    ]}
                  />
                </Form.Item>

                <Button type="primary" block onClick={handleSave}>
                  Save Template
                </Button>
              </Form>
            </Card>
          </Col>
        </Row>

        {/* Preview Modal */}
        <Modal
          title="Report Preview"
          open={previewVisible}
          onCancel={() => setPreviewVisible(false)}
          width={1000}
          footer={null}
        >
          <Table
            dataSource={previewData}
            columns={selectedColumns.map(col => ({
              title: col.label,
              dataIndex: col.field,
              key: col.id,
              width: col.width
            }))}
            scroll={{ x: 'max-content' }}
            size="small"
            pagination={{ pageSize: 20 }}
          />
        </Modal>
      </div>
    </DndProvider>
  );
};
```

---

## 4. Day-by-Day Implementation Plan

### Day 681: Report Template Foundation

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design report template data models | `report_template`, `report_category` tables |
| 2-4h | Implement template versioning system | `report_template_version` table, version management |
| 4-6h | Create template CRUD service | `ReportTemplateService` class |
| 6-8h | Build parameter definition system | Parameter types, validation, defaults |

```python
# Day 681 - Dev 1 Deliverable: Template Service
class ReportTemplateService(models.AbstractModel):
    _name = 'report.template.service'

    def create_template(self, values: dict) -> 'ReportTemplate':
        """Create new report template with validation."""
        # Validate data source configuration
        self._validate_data_source(values.get('data_source_config', {}))

        # Validate parameters
        self._validate_parameters(values.get('parameters', []))

        # Create template
        template = self.env['report.template'].create(values)

        # Create initial version
        self.env['report.template.version'].create({
            'template_id': template.id,
            'version': 1,
            'data_source_config': values.get('data_source_config'),
            'layout_config': values.get('layout_config'),
            'parameters': values.get('parameters'),
            'change_notes': 'Initial version'
        })

        return template
```

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Set up reporting module structure | Module manifest, folder structure |
| 2-4h | Create database migrations | PostgreSQL schema setup |
| 4-6h | Implement REST API endpoints for templates | CRUD endpoints |
| 6-8h | Add authentication and authorization | Access control middleware |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create reporting module routing | React Router configuration |
| 2-4h | Build template list page | `TemplateList` component |
| 4-6h | Implement template detail view | `TemplateDetail` component |
| 6-8h | Create category navigation | Tree-based category browser |

**Day 681 Checkpoint:**
- [ ] Template data models created and migrated
- [ ] Template CRUD API operational
- [ ] Template list UI functional

---

### Day 682: Report Generation Engine

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement data source abstraction | SQL query, Odoo model handlers |
| 2-4h | Build parameter resolution engine | Dynamic expressions, type conversion |
| 4-6h | Create PDF generation with WeasyPrint | `_generate_pdf()` method |
| 6-8h | Implement Excel generation with openpyxl | `_generate_excel()` method |

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Set up MinIO storage integration | Storage client, bucket creation |
| 2-4h | Implement file storage service | Upload, download, expiry management |
| 4-6h | Create report generation API | `/api/v1/reports/generate` endpoint |
| 6-8h | Add generation status tracking | Status updates, progress events |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Build report generation form | Parameter input, format selection |
| 2-4h | Implement generation progress UI | Progress bar, status updates |
| 4-6h | Create report preview component | HTML preview rendering |
| 6-8h | Add download functionality | File download with progress |

**Day 682 Checkpoint:**
- [ ] PDF and Excel generation working
- [ ] Report files stored in MinIO
- [ ] Frontend can trigger and download reports

---

### Day 683: CSV/HTML Export & Data Sources

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement CSV generation | `_generate_csv()` with BOM support |
| 2-4h | Create HTML report generation | Template-based HTML rendering |
| 4-6h | Build Odoo model data source | Domain resolution, field mapping |
| 6-8h | Implement Superset integration | Fetch chart data via API |

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create large dataset pagination | Streaming for big reports |
| 2-4h | Implement report generation queue | Celery task for async generation |
| 4-6h | Add concurrent generation limiting | Semaphore-based throttling |
| 6-8h | Create generation monitoring | Queue depth, processing metrics |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Build data source configuration UI | SQL editor, model selector |
| 2-4h | Create field mapping interface | Drag-drop field selection |
| 4-6h | Implement parameter builder | Dynamic parameter forms |
| 6-8h | Add template testing interface | Test with sample data |

**Day 683 Checkpoint:**
- [ ] All export formats functional (PDF, Excel, CSV, HTML)
- [ ] Multiple data source types supported
- [ ] Async generation with queue management

---

### Day 684: Schedule Engine Foundation

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create schedule data models | `report_schedule`, `report_schedule_execution` |
| 2-4h | Implement cron expression parser | croniter integration |
| 4-6h | Build next-run calculation engine | Timezone-aware scheduling |
| 4-8h | Create business calendar integration | Holiday and weekend handling |

```python
# Day 684 - Dev 1 Deliverable: Schedule Model
class ReportSchedule(models.Model):
    _name = 'report.schedule'
    _description = 'Report Schedule'

    name = fields.Char(required=True)
    template_id = fields.Many2one('report.template', required=True)
    cron_expression = fields.Char()
    timezone = fields.Char(default='Asia/Dhaka')
    next_run_at = fields.Datetime()
    last_run_at = fields.Datetime()

    @api.model
    def _calculate_next_run(self):
        """Calculate next run time for all active schedules."""
        engine = self.env['report.schedule.engine']
        schedules = self.search([('is_active', '=', True)])
        for schedule in schedules:
            next_run = engine.get_next_run_time(schedule)
            schedule.write({'next_run_at': next_run})
```

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create schedule CRUD API | REST endpoints for schedules |
| 2-4h | Implement schedule cron job | Master scheduler (runs every minute) |
| 4-6h | Build execution tracking | Execution logs, metrics |
| 6-8h | Add retry mechanism | Exponential backoff, max retries |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create schedule list page | Schedule overview with status |
| 2-4h | Build schedule builder wizard | Step-by-step schedule creation |
| 4-6h | Implement cron expression builder | Visual cron configuration |
| 6-8h | Add schedule preview | Next 10 runs visualization |

**Day 684 Checkpoint:**
- [ ] Schedule data models created
- [ ] Cron parsing and next-run calculation working
- [ ] Schedule builder UI functional

---

### Day 685: Schedule Execution & Monitoring

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement schedule execution engine | Process due schedules |
| 2-4h | Create execution worker (Celery task) | `execute_scheduled_report` task |
| 4-6h | Build execution state machine | Pending → Running → Completed/Failed |
| 6-8h | Add execution conflict detection | Prevent duplicate runs |

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create pause/resume functionality | Schedule lifecycle management |
| 2-4h | Implement run-now feature | Manual trigger of schedules |
| 4-6h | Build schedule health monitoring | Failed execution alerts |
| 6-8h | Add execution history API | Paginated execution logs |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create execution history view | Table with status, timing |
| 2-4h | Build schedule monitoring dashboard | Active schedules, next runs |
| 4-6h | Implement real-time status updates | WebSocket for live updates |
| 6-8h | Add schedule actions (pause, run now) | Action buttons, confirmations |

**Day 685 Checkpoint:**
- [ ] Scheduled reports execute automatically
- [ ] Execution history tracked and viewable
- [ ] Real-time monitoring operational

---

### Day 686: Alert Trigger System

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create alert trigger data models | `report_alert_trigger`, evaluation log |
| 2-4h | Implement metric source handlers | SQL, Odoo field, analytics metric |
| 4-6h | Build condition evaluation engine | Operators, threshold comparison |
| 6-8h | Create trigger cooldown management | Prevent alert fatigue |

```python
# Day 686 - Dev 1 Deliverable: Trigger Condition Check
def _check_condition(self, operator: str, current: float, threshold: float, upper: float = None) -> bool:
    """Evaluate trigger condition."""
    operators = {
        'gt': lambda c, t, u: c > t,
        'gte': lambda c, t, u: c >= t,
        'lt': lambda c, t, u: c < t,
        'lte': lambda c, t, u: c <= t,
        'eq': lambda c, t, u: c == t,
        'neq': lambda c, t, u: c != t,
        'between': lambda c, t, u: t <= c <= u,
        'outside': lambda c, t, u: c < t or c > u
    }
    return operators.get(operator, lambda c, t, u: False)(current, threshold, upper)
```

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create trigger evaluation cron | Periodic trigger check |
| 2-4h | Implement trigger-to-report linking | Auto-generate report on trigger |
| 4-6h | Build analytics metric integration | Connect to subscription analytics |
| 6-8h | Add trigger API endpoints | CRUD, evaluation history |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create trigger list page | Active triggers overview |
| 2-4h | Build trigger configuration form | Metric selection, conditions |
| 4-6h | Implement trigger history view | Evaluation results |
| 6-8h | Add trigger testing functionality | Manual evaluation trigger |

**Day 686 Checkpoint:**
- [ ] Alert triggers evaluate metrics automatically
- [ ] Reports generated on threshold breach
- [ ] Trigger management UI complete

---

### Day 687: Distribution Engine - Email

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create distribution data models | `report_distribution`, recipients, logs |
| 2-4h | Implement email distribution service | Send reports via email |
| 4-6h | Build recipient resolution | Users, roles, teams, external |
| 6-8h | Create email template engine | Jinja2 templating for emails |

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Integrate with Odoo mail system | Use existing mail infrastructure |
| 2-4h | Implement attachment handling | Size limits, compression |
| 4-6h | Build delivery tracking | Sent, delivered, opened |
| 6-8h | Add bounce handling | Process bounced emails |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create distribution configuration UI | Distribution type selection |
| 2-4h | Build recipient selector component | User/role/team picker |
| 4-6h | Implement email template editor | Subject and body customization |
| 6-8h | Add distribution preview | Preview email before save |

**Day 687 Checkpoint:**
- [ ] Email distribution functional
- [ ] Recipients resolved from various sources
- [ ] Delivery tracking operational

---

### Day 688: Distribution - Portal, Webhook, SFTP

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement portal publication | Publish to customer portal |
| 2-4h | Create webhook distribution | POST to external endpoints |
| 4-6h | Build SFTP upload service | Secure file transfer |
| 6-8h | Add distribution retry logic | Failed delivery handling |

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create secure download endpoint | Token-based access |
| 2-4h | Implement access logging | Track downloads, views |
| 4-6h | Build distribution queue | Async distribution processing |
| 6-8h | Add distribution monitoring | Success/failure metrics |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create webhook configuration UI | URL, headers, method |
| 2-4h | Build SFTP configuration form | Host, credentials, path |
| 4-6h | Implement distribution logs view | Delivery status history |
| 6-8h | Add distribution testing | Test delivery to recipients |

**Day 688 Checkpoint:**
- [ ] All distribution channels operational
- [ ] Secure download with token access
- [ ] Distribution monitoring complete

---

### Day 689: Report Builder & Access Control

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement field discovery service | Get available fields from source |
| 2-4h | Create layout configuration engine | Column definitions, grouping |
| 4-6h | Build access control system | Role-based template access |
| 6-8h | Implement data-level security | Row/column filtering |

```python
# Day 689 - Dev 1 Deliverable: Access Control
class ReportAccessControl(models.AbstractModel):
    _name = 'report.access.control'

    def check_template_access(self, template_id: int, user_id: int) -> bool:
        """Check if user can access template."""
        template = self.env['report.template'].browse(template_id)
        user = self.env['res.users'].browse(user_id)

        if template.visibility == 'public':
            return True
        if template.visibility == 'organization':
            return user.company_id == template.owner_id.company_id
        if template.visibility == 'team':
            return any(g.id in template.allowed_roles for g in user.groups_id)
        if template.visibility == 'private':
            return user.id == template.owner_id.id

        return False

    def apply_data_filter(self, template, user, data):
        """Apply row-level security filtering."""
        # Implementation for data-level access control
        pass
```

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create report preview API | Generate preview with sample data |
| 2-4h | Implement template sharing | Share with users/teams |
| 4-6h | Build audit logging | Track all report access |
| 6-8h | Add sensitive data masking | Mask PII in exports |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Build drag-drop report builder | Column selection, ordering |
| 3-6h | Create column configuration panel | Labels, widths, formats |
| 6-8h | Implement real-time preview | Live preview as columns change |

**Day 689 Checkpoint:**
- [ ] Visual report builder functional
- [ ] Access control enforced
- [ ] Audit logging operational

---

### Day 690: Integration, Testing & Documentation

#### Dev 1 - Backend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create pre-built report templates | Sales, inventory, subscription reports |
| 2-4h | Build report archive management | Retention policies, cleanup |
| 4-6h | Write unit tests for generation engine | 90%+ coverage |
| 6-8h | Performance optimization | Query optimization, caching |

#### Dev 2 - Full-Stack (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Integration testing | End-to-end workflow tests |
| 2-4h | Load testing | 50 concurrent generations |
| 4-6h | Security testing | Access control, injection prevention |
| 6-8h | API documentation | OpenAPI spec, examples |

#### Dev 3 - Frontend Lead (8 hours)

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Build unified report dashboard | Overview of all report features |
| 2-4h | Mobile responsive optimization | Mobile-friendly report access |
| 4-6h | UI/UX polish and fixes | Consistency, accessibility |
| 6-8h | User documentation | Admin and user guides |

**Day 690 Checkpoint:**
- [ ] All features integrated and tested
- [ ] Performance targets met (30s generation)
- [ ] Documentation complete

---

## 5. Deliverables Checklist

### 5.1 Technical Deliverables

| Deliverable | Status | Verification Method |
|-------------|--------|---------------------|
| Report Template System | ⬜ | Unit tests, CRUD operations |
| Report Generation Engine | ⬜ | Generate all formats successfully |
| Schedule Engine | ⬜ | Schedules execute on time |
| Alert Trigger System | ⬜ | Triggers fire on threshold breach |
| Email Distribution | ⬜ | Emails delivered with attachments |
| Portal Distribution | ⬜ | Reports accessible in portal |
| Webhook Distribution | ⬜ | Webhook calls successful |
| SFTP Distribution | ⬜ | Files uploaded to SFTP |
| Report Builder UI | ⬜ | Build reports visually |
| Access Control | ⬜ | Role-based access enforced |
| Audit Logging | ⬜ | All access logged |

### 5.2 API Endpoints

| Endpoint | Method | Status |
|----------|--------|--------|
| `/api/v1/reports/templates` | GET, POST | ⬜ |
| `/api/v1/reports/templates/{id}` | GET, PUT, DELETE | ⬜ |
| `/api/v1/reports/templates/{id}/preview` | POST | ⬜ |
| `/api/v1/reports/schedules` | GET, POST | ⬜ |
| `/api/v1/reports/schedules/{id}` | GET, PUT, DELETE | ⬜ |
| `/api/v1/reports/schedules/{id}/run-now` | POST | ⬜ |
| `/api/v1/reports/generate` | POST | ⬜ |
| `/api/v1/reports/generations` | GET | ⬜ |
| `/api/v1/reports/generations/{id}` | GET | ⬜ |
| `/api/v1/reports/generations/{id}/download` | GET | ⬜ |
| `/api/v1/reports/triggers` | GET, POST | ⬜ |
| `/api/v1/reports/distributions` | GET, POST | ⬜ |

### 5.3 Pre-Built Report Templates

| Template | Category | Data Source |
|----------|----------|-------------|
| Daily Sales Summary | Sales | sale.order |
| Weekly Inventory Report | Inventory | stock.quant |
| Monthly Subscription Report | Subscription | subscription.subscription |
| Customer Order History | Sales | sale.order |
| Delivery Performance | Operations | delivery.order |
| MRR Analysis | Finance | subscription_mrr_snapshot |
| Churn Analysis | Analytics | subscription_churn_metrics |
| Stock Low Alert | Inventory | product.product |

---

## 6. Success Criteria

### 6.1 Functional Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Report formats supported | 4 | PDF, Excel, CSV, HTML |
| Data source types | 3+ | SQL, Odoo, Superset |
| Schedule reliability | 99.9% | Executions on time / scheduled |
| Email delivery rate | 98%+ | Delivered / sent |
| Alert trigger accuracy | 100% | Correct threshold detection |

### 6.2 Technical Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Report generation time | <30s | Standard reports |
| Large report generation | <5min | Reports with 100K+ rows |
| Concurrent generations | 50+ | Simultaneous report jobs |
| API response time | <500ms | P95 latency |
| Storage efficiency | 90-day retention | Auto-cleanup |

### 6.3 Business Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Manual report reduction | 80% | Reports automated vs manual |
| Report consumption | 90% | Reports downloaded/viewed |
| Schedule adoption | 50+ | Active schedules |
| User satisfaction | 4.0/5.0 | Survey score |

---

## 7. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R129-01 | Large report memory issues | High | Medium | Streaming generation, pagination |
| R129-02 | Email deliverability problems | Medium | Medium | SPF/DKIM, sender reputation |
| R129-03 | Storage capacity exceeded | Medium | Low | Retention policies, archival |
| R129-04 | Schedule execution delays | High | Medium | Distributed workers, monitoring |
| R129-05 | Data source connection failures | Medium | Medium | Retry logic, fallback handling |
| R129-06 | Report format compatibility | Low | Medium | Extensive testing, user feedback |

---

## 8. Dependencies

### 8.1 Internal Dependencies

| Dependency | Source | Required For |
|------------|--------|--------------|
| Subscription Analytics | M125 | MRR/Churn metric triggers |
| Workflow Engine | M127 | Approval workflows for reports |
| Customer Lifecycle | M128 | Customer segment reporting |
| Superset Dashboards | P11-M117 | Dashboard data extraction |
| Email Infrastructure | P8 | Email distribution |

### 8.2 External Dependencies

| Dependency | Provider | Purpose |
|------------|----------|---------|
| WeasyPrint | Open Source | PDF generation |
| openpyxl | Open Source | Excel generation |
| croniter | Open Source | Cron expression parsing |
| MinIO | Open Source | Report file storage |
| SendGrid/SES | Third Party | Email delivery |

---

## 9. Quality Assurance

### 9.1 Testing Strategy

| Test Type | Coverage | Tools |
|-----------|----------|-------|
| Unit Tests | 90%+ | pytest, unittest |
| Integration Tests | All APIs | pytest, requests |
| E2E Tests | Critical flows | Playwright |
| Performance Tests | Generation, API | Locust |
| Security Tests | Access control | OWASP ZAP |

### 9.2 Code Quality Standards

- Python: PEP 8, type hints, docstrings
- TypeScript: ESLint, Prettier, strict mode
- SQL: Parameterized queries, indexes
- Git: Feature branches, PR reviews
- Coverage: Minimum 85% overall

### 9.3 Performance Benchmarks

| Benchmark | Target | Test Method |
|-----------|--------|-------------|
| Simple report (100 rows) | <5s | Automated test |
| Medium report (10K rows) | <15s | Automated test |
| Large report (100K rows) | <3min | Manual test |
| Concurrent load (50 users) | <60s avg | Load test |
| Schedule execution lag | <30s | Monitoring |

---

## 10. Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Technical Architect | Initial document |

---

*Document generated as part of Smart Dairy Digital Portal + ERP Implementation Phase 12*
```
```
