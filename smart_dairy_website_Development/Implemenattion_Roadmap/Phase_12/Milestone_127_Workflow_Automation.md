# Milestone 127: Workflow Automation

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Attribute | Details |
|-----------|---------|
| **Document ID** | SD-P12-M127-v1.0 |
| **Version** | 1.0 |
| **Author** | Technical Architecture Team |
| **Created Date** | 2026-02-04 |
| **Last Modified** | 2026-02-04 |
| **Status** | Draft |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Duration** | Days 661-670 (10 working days) |
| **Development Team** | 3 Full-Stack Developers |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 127 delivers a comprehensive workflow automation platform that streamlines business processes across Smart Dairy operations. The platform includes a visual workflow designer, multi-level approval systems, inventory alerts, price change notifications, SLA monitoring, and complete audit trail capabilities.

This milestone transforms manual, error-prone business processes into automated, consistent, and auditable workflows, reducing operational overhead and ensuring compliance with business rules.

### 1.2 Business Value Proposition

| Value Driver | Business Impact | Quantified Benefit |
|--------------|-----------------|-------------------|
| **Process Automation** | Eliminate manual handoffs | 60% faster processing |
| **Approval Workflows** | Consistent decision-making | 90% compliance rate |
| **Inventory Alerts** | Prevent stockouts | 95% product availability |
| **SLA Monitoring** | Service level compliance | 98% SLA adherence |
| **Audit Trail** | Complete traceability | 100% audit compliance |

### 1.3 Strategic Alignment

**RFP Requirements Addressed:**

- REQ-WRK-001: Business process automation platform
- REQ-WRK-002: Multi-level approval workflows
- REQ-WRK-003: Real-time alert system
- REQ-WRK-004: Audit trail and compliance

**BRD Requirements Addressed:**

- BR-WRK-01: 40% reduction in manual work
- BR-WRK-02: 50+ automated business workflows
- BR-WRK-03: Complete audit trail for compliance

**SRS Technical Requirements:**

- SRS-WRK-01: Workflow execution <5 seconds
- SRS-WRK-02: Alert delivery <30 seconds
- SRS-WRK-03: Support 1000+ concurrent workflows

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

#### Priority 0 (Must Have - Days 661-665)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D127-01 | Workflow Engine Core | State machine execution engine |
| D127-02 | Workflow Definition Models | BPMN-compatible workflow storage |
| D127-03 | Order Approval Workflows | Multi-level order approval system |
| D127-04 | Inventory Alert System | Stock level monitoring and alerts |
| D127-05 | Alert Notification Service | Multi-channel alert delivery |

#### Priority 1 (Should Have - Days 666-668)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D127-06 | Visual Workflow Designer | Drag-drop workflow builder |
| D127-07 | Price Change Workflow | Price approval automation |
| D127-08 | SLA Monitoring Engine | SLA tracking and escalation |
| D127-09 | Task Automation Rules | Automated task assignment |
| D127-10 | Escalation Management | Multi-tier escalation |

#### Priority 2 (Nice to Have - Days 669-670)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D127-11 | Audit Trail System | Complete action logging |
| D127-12 | Workflow Analytics | Performance metrics |
| D127-13 | Workflow Templates | Pre-built workflow library |
| D127-14 | Integration Webhooks | External system integration |

### 2.2 Out-of-Scope Items

| Item | Reason | Future Phase |
|------|--------|--------------|
| Document workflow | Requires DMS integration | Phase 14 |
| HR workflows | Different module | Phase 15 |
| Complex BPMN notation | Beyond current scope | Post-MVP |

### 2.3 Assumptions

1. Odoo base workflow features are available
2. Email/SMS notification services from M126 are operational
3. User role hierarchy is defined in Phase 3
4. Redis is available for workflow state caching

### 2.4 Constraints

1. Workflow execution must complete within 30 seconds
2. Maximum 10 approval levels per workflow
3. Alert delivery within 30 seconds of trigger
4. Audit logs must be immutable

---

## 3. Technical Architecture

### 3.1 Workflow Automation Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                   WORKFLOW AUTOMATION PLATFORM                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    WORKFLOW DESIGNER                          │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │  Visual    │  │  Template  │  │  Rule      │  │ Version │ │   │
│  │  │  Builder   │  │  Library   │  │  Editor    │  │ Control │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    WORKFLOW ENGINE                            │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │   State    │  │ Transition │  │  Action    │  │ Timer   │ │   │
│  │  │  Machine   │  │  Handler   │  │  Executor  │  │ Service │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   APPROVAL SYSTEM                             │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │ Approval   │  │  Delegate  │  │ Escalation │  │ Auto-   │ │   │
│  │  │  Matrix    │  │  Manager   │  │  Engine    │  │ Approve │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   ALERT & MONITORING                          │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │ Inventory  │  │   SLA      │  │   Alert    │  │ Notifi- │ │   │
│  │  │  Monitor   │  │  Tracker   │  │  Manager   │  │ cation  │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   AUDIT & COMPLIANCE                          │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │  Audit     │  │  History   │  │ Compliance │  │ Report  │ │   │
│  │  │  Logger    │  │  Tracker   │  │  Checker   │  │ Generator│ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema

```sql
-- ============================================================
-- WORKFLOW AUTOMATION - DATABASE SCHEMA
-- Milestone 127: Days 661-670
-- ============================================================

-- ============================================================
-- 1. WORKFLOW DEFINITION TABLES
-- ============================================================

CREATE TABLE workflow_definition (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,

    workflow_type VARCHAR(50) NOT NULL,
    -- Types: 'approval', 'process', 'notification', 'escalation', 'scheduled'

    -- Target Model
    model_name VARCHAR(100) NOT NULL,  -- e.g., 'sale.order', 'purchase.order'

    -- Workflow Configuration (JSON)
    workflow_config JSONB NOT NULL,
    -- {
    --   "states": [...],
    --   "transitions": [...],
    --   "actions": [...]
    -- }

    -- Trigger Configuration
    trigger_type VARCHAR(50) NOT NULL,
    -- Types: 'on_create', 'on_write', 'on_condition', 'scheduled', 'manual'

    trigger_condition JSONB,
    -- {"field": "amount_total", "operator": ">", "value": 100000}

    -- Version Control
    version INTEGER DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,

    -- Settings
    timeout_hours INTEGER DEFAULT 72,
    allow_parallel BOOLEAN DEFAULT FALSE,
    require_comment BOOLEAN DEFAULT FALSE,

    -- Metadata
    created_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_workflow_def_model ON workflow_definition(model_name);
CREATE INDEX idx_workflow_def_type ON workflow_definition(workflow_type);
CREATE INDEX idx_workflow_def_active ON workflow_definition(is_active);

-- Workflow states
CREATE TABLE workflow_state (
    id SERIAL PRIMARY KEY,
    workflow_id INTEGER NOT NULL REFERENCES workflow_definition(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL,

    state_type VARCHAR(20) NOT NULL,
    -- Types: 'start', 'normal', 'approval', 'end', 'error'

    -- State Configuration
    sequence INTEGER NOT NULL DEFAULT 10,
    is_initial BOOLEAN DEFAULT FALSE,
    is_final BOOLEAN DEFAULT FALSE,

    -- Approval Configuration (for approval states)
    approval_config JSONB,
    -- {
    --   "approvers": [{"type": "user", "id": 1}, {"type": "role", "id": 5}],
    --   "approval_type": "any" | "all" | "sequential",
    --   "min_approvers": 1
    -- }

    -- Actions on Entry/Exit
    on_enter_actions JSONB DEFAULT '[]',
    on_exit_actions JSONB DEFAULT '[]',

    -- Timeout
    timeout_hours INTEGER,
    timeout_action VARCHAR(50),  -- 'escalate', 'auto_approve', 'auto_reject', 'notify'

    UNIQUE(workflow_id, code)
);

CREATE INDEX idx_workflow_state_workflow ON workflow_state(workflow_id);

-- Workflow transitions
CREATE TABLE workflow_transition (
    id SERIAL PRIMARY KEY,
    workflow_id INTEGER NOT NULL REFERENCES workflow_definition(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,

    from_state_id INTEGER NOT NULL REFERENCES workflow_state(id),
    to_state_id INTEGER NOT NULL REFERENCES workflow_state(id),

    -- Transition Trigger
    trigger_type VARCHAR(50) NOT NULL,
    -- Types: 'user_action', 'condition', 'timeout', 'approval', 'rejection'

    -- Conditions
    condition JSONB,
    -- {"field": "approved", "operator": "=", "value": true}

    -- Actions
    actions JSONB DEFAULT '[]',
    -- [{"type": "send_email", "template_id": 5}, {"type": "update_field", "field": "state", "value": "approved"}]

    -- UI Configuration
    button_label VARCHAR(50),
    button_class VARCHAR(50),  -- CSS class
    require_comment BOOLEAN DEFAULT FALSE,
    is_visible BOOLEAN DEFAULT TRUE,

    sequence INTEGER DEFAULT 10
);

CREATE INDEX idx_transition_workflow ON workflow_transition(workflow_id);
CREATE INDEX idx_transition_from ON workflow_transition(from_state_id);
CREATE INDEX idx_transition_to ON workflow_transition(to_state_id);

-- ============================================================
-- 2. WORKFLOW INSTANCE TABLES
-- ============================================================

CREATE TABLE workflow_instance (
    id SERIAL PRIMARY KEY,
    workflow_id INTEGER NOT NULL REFERENCES workflow_definition(id),

    -- Target Record
    model_name VARCHAR(100) NOT NULL,
    record_id INTEGER NOT NULL,

    -- Current State
    current_state_id INTEGER REFERENCES workflow_state(id),
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    -- Status: 'active', 'completed', 'cancelled', 'error', 'timeout'

    -- Timestamps
    started_at TIMESTAMPTZ DEFAULT NOW(),
    completed_at TIMESTAMPTZ,
    due_at TIMESTAMPTZ,

    -- Context Data
    context_data JSONB DEFAULT '{}',
    -- Stores workflow-specific data

    -- Error Handling
    error_message TEXT,
    retry_count INTEGER DEFAULT 0,

    -- Metadata
    started_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1
);

CREATE INDEX idx_instance_workflow ON workflow_instance(workflow_id);
CREATE INDEX idx_instance_model ON workflow_instance(model_name, record_id);
CREATE INDEX idx_instance_status ON workflow_instance(status);
CREATE INDEX idx_instance_state ON workflow_instance(current_state_id);

-- Workflow history/transitions
CREATE TABLE workflow_history (
    id SERIAL PRIMARY KEY,
    instance_id INTEGER NOT NULL REFERENCES workflow_instance(id) ON DELETE CASCADE,
    transition_id INTEGER REFERENCES workflow_transition(id),

    from_state_id INTEGER REFERENCES workflow_state(id),
    to_state_id INTEGER NOT NULL REFERENCES workflow_state(id),

    -- Action Details
    action_type VARCHAR(50) NOT NULL,
    -- Types: 'transition', 'approval', 'rejection', 'timeout', 'escalation', 'system'

    -- User Info
    performed_by INTEGER REFERENCES res_users(id),
    performed_at TIMESTAMPTZ DEFAULT NOW(),

    -- Comments
    comment TEXT,
    attachments JSONB DEFAULT '[]',

    -- Context
    context_data JSONB DEFAULT '{}',

    -- Duration
    duration_seconds INTEGER
);

CREATE INDEX idx_history_instance ON workflow_history(instance_id);
CREATE INDEX idx_history_time ON workflow_history(performed_at);

-- ============================================================
-- 3. APPROVAL TABLES
-- ============================================================

CREATE TABLE approval_request (
    id SERIAL PRIMARY KEY,
    instance_id INTEGER NOT NULL REFERENCES workflow_instance(id) ON DELETE CASCADE,
    state_id INTEGER NOT NULL REFERENCES workflow_state(id),

    -- Target Record
    model_name VARCHAR(100) NOT NULL,
    record_id INTEGER NOT NULL,
    record_name VARCHAR(200),

    -- Approval Configuration
    approval_type VARCHAR(20) NOT NULL,
    -- Types: 'any', 'all', 'sequential', 'majority'

    required_approvals INTEGER DEFAULT 1,
    current_approvals INTEGER DEFAULT 0,
    current_rejections INTEGER DEFAULT 0,

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    -- Status: 'pending', 'approved', 'rejected', 'cancelled', 'escalated'

    -- Timestamps
    requested_at TIMESTAMPTZ DEFAULT NOW(),
    due_at TIMESTAMPTZ,
    completed_at TIMESTAMPTZ,

    -- Escalation
    escalation_level INTEGER DEFAULT 0,
    escalated_to INTEGER REFERENCES res_users(id),

    -- Metadata
    requested_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1
);

CREATE INDEX idx_approval_instance ON approval_request(instance_id);
CREATE INDEX idx_approval_status ON approval_request(status);
CREATE INDEX idx_approval_due ON approval_request(due_at);

-- Individual approver assignments
CREATE TABLE approval_assignment (
    id SERIAL PRIMARY KEY,
    request_id INTEGER NOT NULL REFERENCES approval_request(id) ON DELETE CASCADE,

    -- Approver
    approver_id INTEGER NOT NULL REFERENCES res_users(id),
    approver_role_id INTEGER,  -- If assigned by role

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    -- Status: 'pending', 'approved', 'rejected', 'delegated', 'skipped'

    -- Decision
    decision_at TIMESTAMPTZ,
    comment TEXT,

    -- Sequence (for sequential approval)
    sequence INTEGER DEFAULT 1,
    is_current BOOLEAN DEFAULT FALSE,

    -- Delegation
    delegated_to INTEGER REFERENCES res_users(id),
    delegated_at TIMESTAMPTZ,

    -- Notification
    notified_at TIMESTAMPTZ,
    reminder_count INTEGER DEFAULT 0,
    last_reminder_at TIMESTAMPTZ
);

CREATE INDEX idx_assignment_request ON approval_assignment(request_id);
CREATE INDEX idx_assignment_approver ON approval_assignment(approver_id);
CREATE INDEX idx_assignment_status ON approval_assignment(status);

-- Approval delegation rules
CREATE TABLE approval_delegation (
    id SERIAL PRIMARY KEY,
    delegator_id INTEGER NOT NULL REFERENCES res_users(id),
    delegate_id INTEGER NOT NULL REFERENCES res_users(id),

    -- Delegation Scope
    workflow_ids INTEGER[],  -- NULL means all workflows
    model_names TEXT[],  -- NULL means all models

    -- Validity
    valid_from DATE NOT NULL,
    valid_until DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,

    -- Settings
    auto_delegate BOOLEAN DEFAULT TRUE,
    notify_delegator BOOLEAN DEFAULT TRUE,

    reason TEXT,

    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_delegation_delegator ON approval_delegation(delegator_id);
CREATE INDEX idx_delegation_delegate ON approval_delegation(delegate_id);
CREATE INDEX idx_delegation_active ON approval_delegation(is_active, valid_from, valid_until);

-- ============================================================
-- 4. ALERT & NOTIFICATION TABLES
-- ============================================================

CREATE TABLE alert_rule (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,

    alert_type VARCHAR(50) NOT NULL,
    -- Types: 'inventory', 'sla', 'threshold', 'schedule', 'event'

    -- Target
    model_name VARCHAR(100),
    condition JSONB NOT NULL,
    -- {
    --   "field": "qty_available",
    --   "operator": "<",
    --   "value": "{{reorder_point}}",
    --   "check_interval_minutes": 60
    -- }

    -- Alert Configuration
    severity VARCHAR(20) NOT NULL DEFAULT 'warning',
    -- Severity: 'info', 'warning', 'critical', 'urgent'

    -- Notification
    notification_channels TEXT[] DEFAULT ARRAY['email'],
    notification_template_id INTEGER,
    recipient_config JSONB,
    -- {"type": "role", "role_ids": [1, 2]} or {"type": "user", "user_ids": [1]}

    -- Cooldown (prevent alert spam)
    cooldown_minutes INTEGER DEFAULT 60,
    max_alerts_per_day INTEGER DEFAULT 10,

    -- Escalation
    escalation_config JSONB,
    -- [
    --   {"delay_minutes": 30, "severity": "critical", "recipients": [...]},
    --   {"delay_minutes": 60, "severity": "urgent", "recipients": [...]}
    -- ]

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    last_triggered_at TIMESTAMPTZ,
    trigger_count INTEGER DEFAULT 0,

    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_alert_rule_type ON alert_rule(alert_type);
CREATE INDEX idx_alert_rule_model ON alert_rule(model_name);
CREATE INDEX idx_alert_rule_active ON alert_rule(is_active);

-- Alert instances
CREATE TABLE alert_instance (
    id SERIAL PRIMARY KEY,
    rule_id INTEGER NOT NULL REFERENCES alert_rule(id),

    -- Target
    model_name VARCHAR(100),
    record_id INTEGER,
    record_name VARCHAR(200),

    -- Alert Details
    severity VARCHAR(20) NOT NULL,
    title VARCHAR(500) NOT NULL,
    message TEXT NOT NULL,
    context_data JSONB DEFAULT '{}',

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    -- Status: 'active', 'acknowledged', 'resolved', 'expired', 'escalated'

    -- Timestamps
    triggered_at TIMESTAMPTZ DEFAULT NOW(),
    acknowledged_at TIMESTAMPTZ,
    acknowledged_by INTEGER REFERENCES res_users(id),
    resolved_at TIMESTAMPTZ,
    resolved_by INTEGER REFERENCES res_users(id),

    -- Resolution
    resolution_notes TEXT,

    -- Escalation
    escalation_level INTEGER DEFAULT 0,
    next_escalation_at TIMESTAMPTZ,

    company_id INTEGER NOT NULL DEFAULT 1
);

CREATE INDEX idx_alert_instance_rule ON alert_instance(rule_id);
CREATE INDEX idx_alert_instance_status ON alert_instance(status);
CREATE INDEX idx_alert_instance_severity ON alert_instance(severity);
CREATE INDEX idx_alert_instance_time ON alert_instance(triggered_at);

-- Alert notifications sent
CREATE TABLE alert_notification (
    id SERIAL PRIMARY KEY,
    alert_id INTEGER NOT NULL REFERENCES alert_instance(id) ON DELETE CASCADE,

    channel VARCHAR(20) NOT NULL,  -- 'email', 'sms', 'push', 'in_app'
    recipient_id INTEGER REFERENCES res_users(id),
    recipient_email VARCHAR(200),
    recipient_phone VARCHAR(20),

    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    -- Status: 'pending', 'sent', 'delivered', 'failed'

    sent_at TIMESTAMPTZ,
    delivered_at TIMESTAMPTZ,
    error_message TEXT,

    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_alert_notif_alert ON alert_notification(alert_id);
CREATE INDEX idx_alert_notif_status ON alert_notification(status);

-- ============================================================
-- 5. SLA MONITORING TABLES
-- ============================================================

CREATE TABLE sla_definition (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,

    -- Target
    model_name VARCHAR(100) NOT NULL,

    -- SLA Metrics
    metric_type VARCHAR(50) NOT NULL,
    -- Types: 'response_time', 'resolution_time', 'delivery_time', 'custom'

    -- Thresholds (in hours or custom unit)
    warning_threshold DECIMAL(10, 2),
    critical_threshold DECIMAL(10, 2) NOT NULL,
    unit VARCHAR(20) DEFAULT 'hours',

    -- Time Calculation
    start_field VARCHAR(100) NOT NULL,  -- Field marking SLA start
    end_field VARCHAR(100),  -- Field marking SLA end (null if ongoing)
    pause_field VARCHAR(100),  -- Field indicating paused state

    -- Business Hours
    use_business_hours BOOLEAN DEFAULT TRUE,
    business_hours_config JSONB,
    -- {"mon": ["09:00", "17:00"], "tue": ["09:00", "17:00"], ...}

    -- Conditions
    applies_when JSONB,
    -- {"priority": ["high", "critical"]}

    -- Escalation
    escalation_config JSONB,

    is_active BOOLEAN DEFAULT TRUE,
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_sla_def_model ON sla_definition(model_name);
CREATE INDEX idx_sla_def_active ON sla_definition(is_active);

-- SLA tracking for individual records
CREATE TABLE sla_tracking (
    id SERIAL PRIMARY KEY,
    sla_id INTEGER NOT NULL REFERENCES sla_definition(id),

    -- Target Record
    model_name VARCHAR(100) NOT NULL,
    record_id INTEGER NOT NULL,
    record_name VARCHAR(200),

    -- SLA Timing
    started_at TIMESTAMPTZ NOT NULL,
    due_at TIMESTAMPTZ NOT NULL,
    completed_at TIMESTAMPTZ,

    -- Paused Time Tracking
    total_paused_hours DECIMAL(10, 2) DEFAULT 0,
    pause_history JSONB DEFAULT '[]',

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    -- Status: 'active', 'paused', 'met', 'breached', 'cancelled'

    -- Breach Info
    breached_at TIMESTAMPTZ,
    breach_duration_hours DECIMAL(10, 2),

    -- Actual Duration
    actual_hours DECIMAL(10, 2),

    company_id INTEGER NOT NULL DEFAULT 1,

    UNIQUE(sla_id, model_name, record_id)
);

CREATE INDEX idx_sla_tracking_sla ON sla_tracking(sla_id);
CREATE INDEX idx_sla_tracking_record ON sla_tracking(model_name, record_id);
CREATE INDEX idx_sla_tracking_status ON sla_tracking(status);
CREATE INDEX idx_sla_tracking_due ON sla_tracking(due_at);

-- ============================================================
-- 6. AUDIT TRAIL TABLES
-- ============================================================

CREATE TABLE audit_log (
    id BIGSERIAL PRIMARY KEY,

    -- Target
    model_name VARCHAR(100) NOT NULL,
    record_id INTEGER NOT NULL,
    record_name VARCHAR(200),

    -- Action
    action_type VARCHAR(50) NOT NULL,
    -- Types: 'create', 'update', 'delete', 'workflow', 'approval', 'access'

    -- Changes
    old_values JSONB,
    new_values JSONB,
    changed_fields TEXT[],

    -- User Info
    user_id INTEGER REFERENCES res_users(id),
    user_name VARCHAR(200),

    -- Context
    ip_address INET,
    user_agent TEXT,
    session_id VARCHAR(100),

    -- Workflow Context
    workflow_instance_id INTEGER,
    approval_request_id INTEGER,

    -- Timestamp
    created_at TIMESTAMPTZ DEFAULT NOW(),

    company_id INTEGER NOT NULL DEFAULT 1
) PARTITION BY RANGE (created_at);

-- Create monthly partitions
CREATE TABLE audit_log_2026_01 PARTITION OF audit_log
    FOR VALUES FROM ('2026-01-01') TO ('2026-02-01');
CREATE TABLE audit_log_2026_02 PARTITION OF audit_log
    FOR VALUES FROM ('2026-02-01') TO ('2026-03-01');
-- ... create more partitions as needed

CREATE INDEX idx_audit_model_record ON audit_log(model_name, record_id);
CREATE INDEX idx_audit_user ON audit_log(user_id);
CREATE INDEX idx_audit_action ON audit_log(action_type);
CREATE INDEX idx_audit_time ON audit_log(created_at);

-- ============================================================
-- 7. TASK AUTOMATION TABLES
-- ============================================================

CREATE TABLE task_automation_rule (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,

    -- Trigger
    trigger_type VARCHAR(50) NOT NULL,
    -- Types: 'workflow_state', 'event', 'schedule', 'condition'

    trigger_config JSONB NOT NULL,

    -- Task Creation Config
    task_config JSONB NOT NULL,
    -- {
    --   "title_template": "Review order {{record.name}}",
    --   "description_template": "...",
    --   "assignee_type": "role" | "user" | "field",
    --   "assignee_value": ...,
    --   "due_offset_hours": 24,
    --   "priority": "high"
    -- }

    is_active BOOLEAN DEFAULT TRUE,
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.3 Workflow Engine Implementation

```python
# -*- coding: utf-8 -*-
"""
Workflow Engine Core
Milestone 127: Workflow Automation
Smart Dairy Digital Portal + ERP System
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class WorkflowDefinition(models.Model):
    """Workflow Definition Model"""
    _name = 'workflow.definition'
    _description = 'Workflow Definition'
    _order = 'name'

    name = fields.Char(string='Workflow Name', required=True)
    code = fields.Char(string='Code', required=True, copy=False)
    description = fields.Text(string='Description')

    workflow_type = fields.Selection([
        ('approval', 'Approval Workflow'),
        ('process', 'Process Workflow'),
        ('notification', 'Notification Workflow'),
        ('escalation', 'Escalation Workflow'),
        ('scheduled', 'Scheduled Workflow')
    ], string='Workflow Type', required=True, default='approval')

    model_name = fields.Char(string='Target Model', required=True)
    model_id = fields.Many2one(
        'ir.model',
        string='Model',
        compute='_compute_model_id',
        store=True
    )

    # Trigger Configuration
    trigger_type = fields.Selection([
        ('on_create', 'On Create'),
        ('on_write', 'On Update'),
        ('on_condition', 'On Condition'),
        ('scheduled', 'Scheduled'),
        ('manual', 'Manual')
    ], string='Trigger Type', required=True, default='on_create')

    trigger_condition = fields.Text(string='Trigger Condition (JSON)')

    # States and Transitions
    state_ids = fields.One2many(
        'workflow.state',
        'workflow_id',
        string='States'
    )
    transition_ids = fields.One2many(
        'workflow.transition',
        'workflow_id',
        string='Transitions'
    )

    # Version Control
    version = fields.Integer(string='Version', default=1)
    is_active = fields.Boolean(string='Active', default=True)
    is_default = fields.Boolean(string='Default', default=False)

    # Settings
    timeout_hours = fields.Integer(string='Timeout (Hours)', default=72)
    allow_parallel = fields.Boolean(string='Allow Parallel Instances', default=False)
    require_comment = fields.Boolean(string='Require Comment', default=False)

    # Statistics
    instance_count = fields.Integer(
        string='Instance Count',
        compute='_compute_instance_count'
    )
    active_instance_count = fields.Integer(
        string='Active Instances',
        compute='_compute_instance_count'
    )

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )
    created_by = fields.Many2one(
        'res.users',
        string='Created By',
        default=lambda self: self.env.user
    )

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code, company_id)', 'Workflow code must be unique!')
    ]

    @api.depends('model_name')
    def _compute_model_id(self):
        for record in self:
            if record.model_name:
                model = self.env['ir.model'].search([
                    ('model', '=', record.model_name)
                ], limit=1)
                record.model_id = model.id if model else False
            else:
                record.model_id = False

    def _compute_instance_count(self):
        Instance = self.env['workflow.instance']
        for record in self:
            record.instance_count = Instance.search_count([
                ('workflow_id', '=', record.id)
            ])
            record.active_instance_count = Instance.search_count([
                ('workflow_id', '=', record.id),
                ('status', '=', 'active')
            ])

    def get_initial_state(self):
        """Get the initial state of the workflow"""
        self.ensure_one()
        initial = self.state_ids.filtered(lambda s: s.is_initial)
        if not initial:
            raise ValidationError(f"Workflow {self.name} has no initial state defined.")
        return initial[0]


class WorkflowState(models.Model):
    """Workflow State Model"""
    _name = 'workflow.state'
    _description = 'Workflow State'
    _order = 'sequence, id'

    workflow_id = fields.Many2one(
        'workflow.definition',
        string='Workflow',
        required=True,
        ondelete='cascade'
    )
    name = fields.Char(string='State Name', required=True)
    code = fields.Char(string='Code', required=True)

    state_type = fields.Selection([
        ('start', 'Start'),
        ('normal', 'Normal'),
        ('approval', 'Approval'),
        ('end', 'End'),
        ('error', 'Error')
    ], string='State Type', required=True, default='normal')

    sequence = fields.Integer(string='Sequence', default=10)
    is_initial = fields.Boolean(string='Is Initial State', default=False)
    is_final = fields.Boolean(string='Is Final State', default=False)

    # Approval Configuration
    approval_config = fields.Text(string='Approval Configuration (JSON)')
    approval_type = fields.Selection([
        ('any', 'Any Approver'),
        ('all', 'All Approvers'),
        ('sequential', 'Sequential'),
        ('majority', 'Majority')
    ], string='Approval Type', default='any')

    approver_ids = fields.Many2many(
        'res.users',
        string='Approvers'
    )
    approver_role_ids = fields.Many2many(
        'res.groups',
        string='Approver Roles'
    )
    min_approvers = fields.Integer(string='Minimum Approvers', default=1)

    # Actions
    on_enter_actions = fields.Text(string='On Enter Actions (JSON)')
    on_exit_actions = fields.Text(string='On Exit Actions (JSON)')

    # Timeout
    timeout_hours = fields.Integer(string='Timeout (Hours)')
    timeout_action = fields.Selection([
        ('escalate', 'Escalate'),
        ('auto_approve', 'Auto Approve'),
        ('auto_reject', 'Auto Reject'),
        ('notify', 'Notify Only')
    ], string='Timeout Action')

    # Outgoing Transitions
    outgoing_transition_ids = fields.One2many(
        'workflow.transition',
        'from_state_id',
        string='Outgoing Transitions'
    )

    _sql_constraints = [
        ('workflow_code_unique', 'UNIQUE(workflow_id, code)', 'State code must be unique within workflow!')
    ]

    def get_approvers(self, record=None):
        """Get list of approvers for this state"""
        approvers = self.approver_ids

        # Add users from roles
        for role in self.approver_role_ids:
            approvers |= role.users

        # Dynamic approvers from config
        if self.approval_config:
            config = json.loads(self.approval_config)
            # Handle dynamic approver resolution
            if config.get('dynamic_field') and record:
                dynamic_approver = getattr(record, config['dynamic_field'], None)
                if dynamic_approver:
                    approvers |= dynamic_approver

        return approvers


class WorkflowTransition(models.Model):
    """Workflow Transition Model"""
    _name = 'workflow.transition'
    _description = 'Workflow Transition'
    _order = 'sequence, id'

    workflow_id = fields.Many2one(
        'workflow.definition',
        string='Workflow',
        required=True,
        ondelete='cascade'
    )
    name = fields.Char(string='Transition Name', required=True)

    from_state_id = fields.Many2one(
        'workflow.state',
        string='From State',
        required=True,
        ondelete='cascade'
    )
    to_state_id = fields.Many2one(
        'workflow.state',
        string='To State',
        required=True,
        ondelete='cascade'
    )

    trigger_type = fields.Selection([
        ('user_action', 'User Action'),
        ('condition', 'Condition'),
        ('timeout', 'Timeout'),
        ('approval', 'Approval'),
        ('rejection', 'Rejection')
    ], string='Trigger Type', required=True, default='user_action')

    condition = fields.Text(string='Condition (JSON)')
    actions = fields.Text(string='Actions (JSON)')

    # UI Configuration
    button_label = fields.Char(string='Button Label')
    button_class = fields.Char(string='Button CSS Class', default='btn-primary')
    require_comment = fields.Boolean(string='Require Comment', default=False)
    is_visible = fields.Boolean(string='Visible', default=True)

    sequence = fields.Integer(string='Sequence', default=10)

    def check_condition(self, record):
        """Check if transition condition is met"""
        if not self.condition:
            return True

        condition = json.loads(self.condition)
        return self._evaluate_condition(condition, record)

    def _evaluate_condition(self, condition, record):
        """Evaluate a condition against a record"""
        field = condition.get('field')
        operator = condition.get('operator')
        value = condition.get('value')

        if not field:
            return True

        record_value = getattr(record, field, None)

        operators = {
            '=': lambda a, b: a == b,
            '!=': lambda a, b: a != b,
            '>': lambda a, b: a > b,
            '>=': lambda a, b: a >= b,
            '<': lambda a, b: a < b,
            '<=': lambda a, b: a <= b,
            'in': lambda a, b: a in b,
            'not in': lambda a, b: a not in b,
        }

        op_func = operators.get(operator)
        if op_func:
            return op_func(record_value, value)

        return True


class WorkflowInstance(models.Model):
    """Workflow Instance Model"""
    _name = 'workflow.instance'
    _description = 'Workflow Instance'
    _order = 'started_at desc'

    workflow_id = fields.Many2one(
        'workflow.definition',
        string='Workflow',
        required=True
    )
    model_name = fields.Char(string='Model', required=True)
    record_id = fields.Integer(string='Record ID', required=True)
    record_ref = fields.Reference(
        string='Record',
        selection='_get_record_selection',
        compute='_compute_record_ref'
    )

    current_state_id = fields.Many2one(
        'workflow.state',
        string='Current State'
    )
    status = fields.Selection([
        ('active', 'Active'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
        ('error', 'Error'),
        ('timeout', 'Timeout')
    ], string='Status', default='active')

    # Timestamps
    started_at = fields.Datetime(string='Started At', default=fields.Datetime.now)
    completed_at = fields.Datetime(string='Completed At')
    due_at = fields.Datetime(string='Due At')

    # Context
    context_data = fields.Text(string='Context Data')

    # Error Handling
    error_message = fields.Text(string='Error Message')
    retry_count = fields.Integer(string='Retry Count', default=0)

    # Relations
    history_ids = fields.One2many(
        'workflow.history',
        'instance_id',
        string='History'
    )
    approval_request_ids = fields.One2many(
        'approval.request',
        'instance_id',
        string='Approval Requests'
    )

    started_by = fields.Many2one(
        'res.users',
        string='Started By',
        default=lambda self: self.env.user
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    @api.model
    def _get_record_selection(self):
        models = self.env['ir.model'].search([])
        return [(m.model, m.name) for m in models]

    @api.depends('model_name', 'record_id')
    def _compute_record_ref(self):
        for record in self:
            if record.model_name and record.record_id:
                record.record_ref = f'{record.model_name},{record.record_id}'
            else:
                record.record_ref = False

    def get_record(self):
        """Get the target record"""
        self.ensure_one()
        if self.model_name and self.record_id:
            return self.env[self.model_name].browse(self.record_id)
        return None

    def get_available_transitions(self):
        """Get available transitions from current state"""
        self.ensure_one()
        if not self.current_state_id:
            return self.env['workflow.transition']

        record = self.get_record()
        transitions = self.current_state_id.outgoing_transition_ids

        # Filter by visibility and conditions
        available = transitions.filtered(
            lambda t: t.is_visible and t.check_condition(record)
        )

        return available


class WorkflowEngine(models.AbstractModel):
    """Workflow Engine Service"""
    _name = 'workflow.engine'
    _description = 'Workflow Engine'

    @api.model
    def start_workflow(self, workflow_code, model_name, record_id, context_data=None):
        """
        Start a new workflow instance

        Args:
            workflow_code: Code of the workflow definition
            model_name: Target model name
            record_id: Target record ID
            context_data: Optional context data

        Returns:
            workflow.instance record
        """
        # Find workflow definition
        Workflow = self.env['workflow.definition']
        workflow = Workflow.search([
            ('code', '=', workflow_code),
            ('is_active', '=', True),
            ('company_id', '=', self.env.company.id)
        ], limit=1)

        if not workflow:
            raise ValidationError(f"Workflow '{workflow_code}' not found or inactive.")

        # Check for existing active instance
        if not workflow.allow_parallel:
            Instance = self.env['workflow.instance']
            existing = Instance.search([
                ('workflow_id', '=', workflow.id),
                ('model_name', '=', model_name),
                ('record_id', '=', record_id),
                ('status', '=', 'active')
            ], limit=1)

            if existing:
                raise ValidationError("An active workflow instance already exists for this record.")

        # Get initial state
        initial_state = workflow.get_initial_state()

        # Calculate due date
        due_at = None
        if workflow.timeout_hours:
            due_at = datetime.now() + timedelta(hours=workflow.timeout_hours)

        # Create instance
        instance = self.env['workflow.instance'].create({
            'workflow_id': workflow.id,
            'model_name': model_name,
            'record_id': record_id,
            'current_state_id': initial_state.id,
            'status': 'active',
            'due_at': due_at,
            'context_data': json.dumps(context_data or {})
        })

        # Execute on_enter actions for initial state
        self._execute_state_actions(instance, initial_state, 'enter')

        # Create approval request if initial state is approval
        if initial_state.state_type == 'approval':
            self._create_approval_request(instance, initial_state)

        # Log history
        self._log_history(instance, None, initial_state, 'transition', 'Workflow started')

        # Audit log
        self._audit_log(instance, 'workflow', 'Workflow started')

        _logger.info(f"Started workflow {workflow.name} for {model_name}:{record_id}")

        return instance

    @api.model
    def execute_transition(self, instance_id, transition_id, comment=None):
        """
        Execute a workflow transition

        Args:
            instance_id: Workflow instance ID
            transition_id: Transition to execute
            comment: Optional comment

        Returns:
            Updated workflow instance
        """
        instance = self.env['workflow.instance'].browse(instance_id)
        transition = self.env['workflow.transition'].browse(transition_id)

        if not instance.exists() or not transition.exists():
            raise ValidationError("Invalid instance or transition.")

        if instance.status != 'active':
            raise ValidationError("Workflow is not active.")

        # Validate transition
        if transition.from_state_id != instance.current_state_id:
            raise ValidationError("Invalid transition from current state.")

        # Check condition
        record = instance.get_record()
        if not transition.check_condition(record):
            raise ValidationError("Transition condition not met.")

        # Check comment requirement
        if transition.require_comment and not comment:
            raise ValidationError("Comment is required for this transition.")

        from_state = instance.current_state_id
        to_state = transition.to_state_id

        # Execute exit actions from current state
        self._execute_state_actions(instance, from_state, 'exit')

        # Execute transition actions
        self._execute_transition_actions(instance, transition, record)

        # Update instance state
        instance.write({
            'current_state_id': to_state.id
        })

        # Execute enter actions for new state
        self._execute_state_actions(instance, to_state, 'enter')

        # Handle final state
        if to_state.is_final:
            instance.write({
                'status': 'completed',
                'completed_at': datetime.now()
            })

        # Create approval request if new state is approval
        elif to_state.state_type == 'approval':
            self._create_approval_request(instance, to_state)

        # Log history
        self._log_history(instance, from_state, to_state, 'transition', comment)

        # Audit log
        self._audit_log(instance, 'workflow', f"Transitioned from {from_state.name} to {to_state.name}")

        return instance

    def _execute_state_actions(self, instance, state, action_type):
        """Execute on_enter or on_exit actions for a state"""
        actions_field = f'on_{action_type}_actions'
        actions_json = getattr(state, actions_field, None)

        if not actions_json:
            return

        try:
            actions = json.loads(actions_json)
            record = instance.get_record()

            for action in actions:
                self._execute_action(action, instance, record)

        except Exception as e:
            _logger.error(f"Error executing {action_type} actions: {e}")

    def _execute_transition_actions(self, instance, transition, record):
        """Execute actions defined on a transition"""
        if not transition.actions:
            return

        try:
            actions = json.loads(transition.actions)
            for action in actions:
                self._execute_action(action, instance, record)
        except Exception as e:
            _logger.error(f"Error executing transition actions: {e}")

    def _execute_action(self, action, instance, record):
        """Execute a single action"""
        action_type = action.get('type')

        if action_type == 'send_email':
            self._action_send_email(action, instance, record)
        elif action_type == 'update_field':
            self._action_update_field(action, record)
        elif action_type == 'create_task':
            self._action_create_task(action, instance, record)
        elif action_type == 'call_method':
            self._action_call_method(action, record)
        elif action_type == 'webhook':
            self._action_webhook(action, instance, record)

    def _action_send_email(self, action, instance, record):
        """Send email action"""
        template_id = action.get('template_id')
        if template_id:
            template = self.env['email.template.marketing'].browse(template_id)
            if template.exists():
                # Prepare context
                context = {
                    'record': record,
                    'instance': instance,
                    'user': self.env.user
                }
                template.send_mail(record.id, force_send=True)

    def _action_update_field(self, action, record):
        """Update field action"""
        field = action.get('field')
        value = action.get('value')
        if field and hasattr(record, field):
            record.write({field: value})

    def _action_create_task(self, action, instance, record):
        """Create task action"""
        # Task creation logic
        pass

    def _action_call_method(self, action, record):
        """Call method action"""
        method_name = action.get('method')
        if method_name and hasattr(record, method_name):
            method = getattr(record, method_name)
            if callable(method):
                method()

    def _action_webhook(self, action, instance, record):
        """Webhook action"""
        import requests
        url = action.get('url')
        if url:
            payload = {
                'instance_id': instance.id,
                'model': instance.model_name,
                'record_id': instance.record_id,
                'state': instance.current_state_id.code
            }
            try:
                requests.post(url, json=payload, timeout=10)
            except Exception as e:
                _logger.error(f"Webhook error: {e}")

    def _create_approval_request(self, instance, state):
        """Create approval request for an approval state"""
        record = instance.get_record()
        approvers = state.get_approvers(record)

        if not approvers:
            raise ValidationError(f"No approvers defined for state {state.name}")

        # Create approval request
        request = self.env['approval.request'].create({
            'instance_id': instance.id,
            'state_id': state.id,
            'model_name': instance.model_name,
            'record_id': instance.record_id,
            'record_name': record.display_name if hasattr(record, 'display_name') else str(record.id),
            'approval_type': state.approval_type,
            'required_approvals': state.min_approvers,
            'status': 'pending',
            'due_at': datetime.now() + timedelta(hours=state.timeout_hours or 72)
        })

        # Create assignments for each approver
        for i, approver in enumerate(approvers):
            self.env['approval.assignment'].create({
                'request_id': request.id,
                'approver_id': approver.id,
                'sequence': i + 1,
                'is_current': (i == 0) if state.approval_type == 'sequential' else True
            })

        # Send notifications
        self._notify_approvers(request, approvers)

        return request

    def _notify_approvers(self, request, approvers):
        """Send approval notifications to approvers"""
        for approver in approvers:
            # Email notification
            self.env['alert.notification'].create({
                'alert_id': None,  # Not an alert, but using same notification system
                'channel': 'email',
                'recipient_id': approver.id,
                'recipient_email': approver.email,
                'status': 'pending'
            })

            # Send actual email
            # ... email sending logic

    def _log_history(self, instance, from_state, to_state, action_type, comment=None):
        """Log workflow history"""
        self.env['workflow.history'].create({
            'instance_id': instance.id,
            'from_state_id': from_state.id if from_state else None,
            'to_state_id': to_state.id,
            'action_type': action_type,
            'performed_by': self.env.user.id,
            'comment': comment
        })

    def _audit_log(self, instance, action_type, details):
        """Create audit log entry"""
        self.env['audit.log'].create({
            'model_name': instance.model_name,
            'record_id': instance.record_id,
            'action_type': action_type,
            'new_values': json.dumps({'details': details}),
            'user_id': self.env.user.id,
            'workflow_instance_id': instance.id
        })
```

### 3.4 Approval System

```python
# -*- coding: utf-8 -*-
"""
Approval System
Milestone 127: Workflow Automation
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class ApprovalRequest(models.Model):
    """Approval Request Model"""
    _name = 'approval.request'
    _description = 'Approval Request'
    _order = 'requested_at desc'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    instance_id = fields.Many2one(
        'workflow.instance',
        string='Workflow Instance',
        required=True,
        ondelete='cascade'
    )
    state_id = fields.Many2one(
        'workflow.state',
        string='Workflow State',
        required=True
    )

    # Target Record
    model_name = fields.Char(string='Model', required=True)
    record_id = fields.Integer(string='Record ID', required=True)
    record_name = fields.Char(string='Record Name')

    # Approval Configuration
    approval_type = fields.Selection([
        ('any', 'Any Approver'),
        ('all', 'All Approvers'),
        ('sequential', 'Sequential'),
        ('majority', 'Majority')
    ], string='Approval Type', required=True, default='any')

    required_approvals = fields.Integer(string='Required Approvals', default=1)
    current_approvals = fields.Integer(string='Current Approvals', default=0)
    current_rejections = fields.Integer(string='Current Rejections', default=0)

    # Status
    status = fields.Selection([
        ('pending', 'Pending'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('cancelled', 'Cancelled'),
        ('escalated', 'Escalated')
    ], string='Status', default='pending', tracking=True)

    # Timestamps
    requested_at = fields.Datetime(string='Requested At', default=fields.Datetime.now)
    due_at = fields.Datetime(string='Due At')
    completed_at = fields.Datetime(string='Completed At')

    # Escalation
    escalation_level = fields.Integer(string='Escalation Level', default=0)
    escalated_to = fields.Many2one('res.users', string='Escalated To')

    # Relations
    assignment_ids = fields.One2many(
        'approval.assignment',
        'request_id',
        string='Assignments'
    )

    requested_by = fields.Many2one(
        'res.users',
        string='Requested By',
        default=lambda self: self.env.user
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    def get_record(self):
        """Get the target record"""
        self.ensure_one()
        if self.model_name and self.record_id:
            return self.env[self.model_name].browse(self.record_id)
        return None

    def action_approve(self, comment=None):
        """Approve the request (by current user)"""
        self.ensure_one()
        return self._process_decision('approved', comment)

    def action_reject(self, comment=None):
        """Reject the request (by current user)"""
        self.ensure_one()
        return self._process_decision('rejected', comment)

    def _process_decision(self, decision, comment=None):
        """Process approval/rejection decision"""
        self.ensure_one()

        # Find current user's assignment
        assignment = self.assignment_ids.filtered(
            lambda a: a.approver_id == self.env.user and a.status == 'pending'
        )

        if not assignment:
            raise UserError("You are not authorized to approve/reject this request.")

        assignment = assignment[0]

        # Check if it's this user's turn (for sequential)
        if self.approval_type == 'sequential' and not assignment.is_current:
            raise UserError("It's not your turn to approve this request.")

        # Update assignment
        assignment.write({
            'status': decision,
            'decision_at': datetime.now(),
            'comment': comment
        })

        # Update counts
        if decision == 'approved':
            self.current_approvals += 1
        else:
            self.current_rejections += 1

        # Check if request is complete
        self._check_completion()

        # Move to next approver (for sequential)
        if self.approval_type == 'sequential' and self.status == 'pending':
            self._activate_next_approver()

        return True

    def _check_completion(self):
        """Check if approval request is complete"""
        if self.approval_type == 'any':
            if self.current_approvals >= 1:
                self._complete('approved')
            elif self.current_rejections >= 1:
                self._complete('rejected')

        elif self.approval_type == 'all':
            total_assignments = len(self.assignment_ids)
            if self.current_approvals >= total_assignments:
                self._complete('approved')
            elif self.current_rejections >= 1:
                self._complete('rejected')

        elif self.approval_type == 'sequential':
            total_assignments = len(self.assignment_ids)
            if self.current_approvals >= total_assignments:
                self._complete('approved')
            elif self.current_rejections >= 1:
                self._complete('rejected')

        elif self.approval_type == 'majority':
            total_assignments = len(self.assignment_ids)
            majority = (total_assignments // 2) + 1
            total_decisions = self.current_approvals + self.current_rejections

            if self.current_approvals >= majority:
                self._complete('approved')
            elif self.current_rejections >= majority:
                self._complete('rejected')
            elif total_decisions >= total_assignments:
                # All voted, check majority
                if self.current_approvals > self.current_rejections:
                    self._complete('approved')
                else:
                    self._complete('rejected')

    def _complete(self, result):
        """Complete the approval request"""
        self.write({
            'status': result,
            'completed_at': datetime.now()
        })

        # Trigger workflow transition
        engine = self.env['workflow.engine']
        instance = self.instance_id

        if result == 'approved':
            # Find approval transition
            transitions = instance.current_state_id.outgoing_transition_ids.filtered(
                lambda t: t.trigger_type == 'approval'
            )
        else:
            # Find rejection transition
            transitions = instance.current_state_id.outgoing_transition_ids.filtered(
                lambda t: t.trigger_type == 'rejection'
            )

        if transitions:
            engine.execute_transition(instance.id, transitions[0].id)

    def _activate_next_approver(self):
        """Activate the next approver in sequential approval"""
        current = self.assignment_ids.filtered(lambda a: a.is_current)
        if current:
            current.write({'is_current': False})

        next_pending = self.assignment_ids.filtered(
            lambda a: a.status == 'pending'
        ).sorted('sequence')

        if next_pending:
            next_pending[0].write({'is_current': True})
            # Notify next approver
            self._notify_approver(next_pending[0])

    def _notify_approver(self, assignment):
        """Send notification to approver"""
        # Send email notification
        template = self.env.ref('workflow_automation.email_template_approval_request', False)
        if template:
            template.send_mail(self.id, force_send=True)


class ApprovalAssignment(models.Model):
    """Approval Assignment Model"""
    _name = 'approval.assignment'
    _description = 'Approval Assignment'
    _order = 'sequence, id'

    request_id = fields.Many2one(
        'approval.request',
        string='Request',
        required=True,
        ondelete='cascade'
    )

    approver_id = fields.Many2one(
        'res.users',
        string='Approver',
        required=True
    )
    approver_role_id = fields.Many2one(
        'res.groups',
        string='Approver Role'
    )

    status = fields.Selection([
        ('pending', 'Pending'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('delegated', 'Delegated'),
        ('skipped', 'Skipped')
    ], string='Status', default='pending')

    decision_at = fields.Datetime(string='Decision At')
    comment = fields.Text(string='Comment')

    sequence = fields.Integer(string='Sequence', default=1)
    is_current = fields.Boolean(string='Is Current', default=False)

    # Delegation
    delegated_to = fields.Many2one('res.users', string='Delegated To')
    delegated_at = fields.Datetime(string='Delegated At')

    # Notification
    notified_at = fields.Datetime(string='Notified At')
    reminder_count = fields.Integer(string='Reminder Count', default=0)
    last_reminder_at = fields.Datetime(string='Last Reminder At')

    def action_delegate(self, delegate_user_id, comment=None):
        """Delegate approval to another user"""
        self.ensure_one()

        if self.status != 'pending':
            raise UserError("Cannot delegate a non-pending assignment.")

        delegate = self.env['res.users'].browse(delegate_user_id)
        if not delegate.exists():
            raise UserError("Invalid delegate user.")

        self.write({
            'status': 'delegated',
            'delegated_to': delegate.id,
            'delegated_at': datetime.now(),
            'comment': comment
        })

        # Create new assignment for delegate
        self.env['approval.assignment'].create({
            'request_id': self.request_id.id,
            'approver_id': delegate.id,
            'sequence': self.sequence,
            'is_current': self.is_current,
            'status': 'pending'
        })

        # Notify delegate
        # ... notification logic

        return True
```

### 3.5 Alert & Inventory Monitoring

```python
# -*- coding: utf-8 -*-
"""
Alert & Inventory Monitoring Service
Milestone 127: Workflow Automation
"""

from odoo import models, fields, api
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class AlertRule(models.Model):
    """Alert Rule Model"""
    _name = 'alert.rule'
    _description = 'Alert Rule'

    name = fields.Char(string='Rule Name', required=True)
    code = fields.Char(string='Code', required=True)
    description = fields.Text(string='Description')

    alert_type = fields.Selection([
        ('inventory', 'Inventory Alert'),
        ('sla', 'SLA Alert'),
        ('threshold', 'Threshold Alert'),
        ('schedule', 'Scheduled Alert'),
        ('event', 'Event Alert')
    ], string='Alert Type', required=True)

    model_name = fields.Char(string='Model')
    condition = fields.Text(string='Condition (JSON)', required=True)

    severity = fields.Selection([
        ('info', 'Info'),
        ('warning', 'Warning'),
        ('critical', 'Critical'),
        ('urgent', 'Urgent')
    ], string='Severity', default='warning', required=True)

    # Notification
    notification_channels = fields.Selection([
        ('email', 'Email'),
        ('sms', 'SMS'),
        ('push', 'Push Notification'),
        ('in_app', 'In-App')
    ], string='Notification Channel', default='email')

    notification_template_id = fields.Many2one(
        'email.template.marketing',
        string='Notification Template'
    )
    recipient_config = fields.Text(string='Recipient Configuration (JSON)')

    # Cooldown
    cooldown_minutes = fields.Integer(string='Cooldown (Minutes)', default=60)
    max_alerts_per_day = fields.Integer(string='Max Alerts Per Day', default=10)

    # Escalation
    escalation_config = fields.Text(string='Escalation Configuration (JSON)')

    # Status
    is_active = fields.Boolean(string='Active', default=True)
    last_triggered_at = fields.Datetime(string='Last Triggered')
    trigger_count = fields.Integer(string='Trigger Count', default=0)

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code, company_id)', 'Alert code must be unique!')
    ]


class AlertInstance(models.Model):
    """Alert Instance Model"""
    _name = 'alert.instance'
    _description = 'Alert Instance'
    _order = 'triggered_at desc'

    rule_id = fields.Many2one(
        'alert.rule',
        string='Alert Rule',
        required=True
    )

    model_name = fields.Char(string='Model')
    record_id = fields.Integer(string='Record ID')
    record_name = fields.Char(string='Record Name')

    severity = fields.Selection([
        ('info', 'Info'),
        ('warning', 'Warning'),
        ('critical', 'Critical'),
        ('urgent', 'Urgent')
    ], string='Severity', required=True)

    title = fields.Char(string='Title', required=True)
    message = fields.Text(string='Message', required=True)
    context_data = fields.Text(string='Context Data')

    status = fields.Selection([
        ('active', 'Active'),
        ('acknowledged', 'Acknowledged'),
        ('resolved', 'Resolved'),
        ('expired', 'Expired'),
        ('escalated', 'Escalated')
    ], string='Status', default='active')

    # Timestamps
    triggered_at = fields.Datetime(string='Triggered At', default=fields.Datetime.now)
    acknowledged_at = fields.Datetime(string='Acknowledged At')
    acknowledged_by = fields.Many2one('res.users', string='Acknowledged By')
    resolved_at = fields.Datetime(string='Resolved At')
    resolved_by = fields.Many2one('res.users', string='Resolved By')

    resolution_notes = fields.Text(string='Resolution Notes')

    # Escalation
    escalation_level = fields.Integer(string='Escalation Level', default=0)
    next_escalation_at = fields.Datetime(string='Next Escalation At')

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    def action_acknowledge(self):
        """Acknowledge the alert"""
        self.ensure_one()
        self.write({
            'status': 'acknowledged',
            'acknowledged_at': datetime.now(),
            'acknowledged_by': self.env.user.id
        })

    def action_resolve(self, notes=None):
        """Resolve the alert"""
        self.ensure_one()
        self.write({
            'status': 'resolved',
            'resolved_at': datetime.now(),
            'resolved_by': self.env.user.id,
            'resolution_notes': notes
        })


class InventoryAlertService(models.AbstractModel):
    """Inventory Alert Monitoring Service"""
    _name = 'inventory.alert.service'
    _description = 'Inventory Alert Service'

    @api.model
    def check_inventory_alerts(self):
        """
        Check inventory levels and trigger alerts

        Runs as scheduled job
        """
        AlertRule = self.env['alert.rule']
        rules = AlertRule.search([
            ('alert_type', '=', 'inventory'),
            ('is_active', '=', True)
        ])

        alerts_created = 0

        for rule in rules:
            try:
                alerts = self._check_rule(rule)
                alerts_created += len(alerts)
            except Exception as e:
                _logger.error(f"Error checking inventory rule {rule.code}: {e}")

        _logger.info(f"Inventory check completed. Created {alerts_created} alerts.")
        return alerts_created

    def _check_rule(self, rule):
        """Check a single inventory alert rule"""
        condition = json.loads(rule.condition)

        # Get products to check
        Product = self.env['product.product']
        domain = [('type', '=', 'product')]

        # Add category filter if specified
        if condition.get('category_ids'):
            domain.append(('categ_id', 'in', condition['category_ids']))

        products = Product.search(domain)
        alerts = []

        for product in products:
            # Check if alert should be triggered
            if self._should_trigger_alert(product, condition, rule):
                alert = self._create_inventory_alert(product, rule, condition)
                if alert:
                    alerts.append(alert)

        return alerts

    def _should_trigger_alert(self, product, condition, rule):
        """Determine if an inventory alert should be triggered"""
        check_type = condition.get('check_type', 'reorder_point')

        if check_type == 'reorder_point':
            # Check against reorder point
            threshold = product.reorder_point if hasattr(product, 'reorder_point') else 0
            return product.qty_available <= threshold

        elif check_type == 'fixed_threshold':
            # Check against fixed value
            threshold = condition.get('threshold', 0)
            return product.qty_available <= threshold

        elif check_type == 'days_of_stock':
            # Check days of stock remaining
            days_threshold = condition.get('days_threshold', 7)
            daily_usage = self._calculate_daily_usage(product)
            if daily_usage > 0:
                days_remaining = product.qty_available / daily_usage
                return days_remaining <= days_threshold

        elif check_type == 'percentage':
            # Check percentage of reorder point
            percentage = condition.get('percentage', 100)
            reorder_point = product.reorder_point if hasattr(product, 'reorder_point') else 0
            threshold = reorder_point * (percentage / 100)
            return product.qty_available <= threshold

        return False

    def _calculate_daily_usage(self, product, days=30):
        """Calculate average daily usage for a product"""
        cutoff = datetime.now() - timedelta(days=days)

        StockMove = self.env['stock.move']
        moves = StockMove.search([
            ('product_id', '=', product.id),
            ('state', '=', 'done'),
            ('date', '>=', cutoff),
            ('location_dest_id.usage', '=', 'customer')
        ])

        total_qty = sum(m.product_uom_qty for m in moves)
        return total_qty / days if days > 0 else 0

    def _create_inventory_alert(self, product, rule, condition):
        """Create an inventory alert instance"""
        # Check cooldown
        if not self._check_cooldown(rule, product):
            return None

        AlertInstance = self.env['alert.instance']

        alert = AlertInstance.create({
            'rule_id': rule.id,
            'model_name': 'product.product',
            'record_id': product.id,
            'record_name': product.display_name,
            'severity': rule.severity,
            'title': f"Low Inventory: {product.name}",
            'message': f"Product {product.name} has reached low inventory level. "
                      f"Current quantity: {product.qty_available}",
            'context_data': json.dumps({
                'product_id': product.id,
                'current_qty': product.qty_available,
                'reorder_point': getattr(product, 'reorder_point', 0)
            }),
            'status': 'active'
        })

        # Send notifications
        self._send_alert_notifications(alert, rule)

        # Update rule statistics
        rule.write({
            'last_triggered_at': datetime.now(),
            'trigger_count': rule.trigger_count + 1
        })

        return alert

    def _check_cooldown(self, rule, product):
        """Check if rule is in cooldown for this product"""
        if not rule.cooldown_minutes:
            return True

        cooldown_cutoff = datetime.now() - timedelta(minutes=rule.cooldown_minutes)

        AlertInstance = self.env['alert.instance']
        recent_alert = AlertInstance.search([
            ('rule_id', '=', rule.id),
            ('record_id', '=', product.id),
            ('triggered_at', '>=', cooldown_cutoff)
        ], limit=1)

        return not recent_alert.exists()

    def _send_alert_notifications(self, alert, rule):
        """Send alert notifications through configured channels"""
        recipients = self._get_recipients(rule)

        for recipient in recipients:
            self._send_notification(alert, rule, recipient)

    def _get_recipients(self, rule):
        """Get notification recipients from rule configuration"""
        if not rule.recipient_config:
            return []

        config = json.loads(rule.recipient_config)
        recipients = []

        if config.get('type') == 'user':
            user_ids = config.get('user_ids', [])
            recipients = self.env['res.users'].browse(user_ids)
        elif config.get('type') == 'role':
            role_ids = config.get('role_ids', [])
            roles = self.env['res.groups'].browse(role_ids)
            for role in roles:
                recipients |= role.users

        return recipients

    def _send_notification(self, alert, rule, recipient):
        """Send notification to a recipient"""
        AlertNotification = self.env['alert.notification']

        notification = AlertNotification.create({
            'alert_id': alert.id,
            'channel': rule.notification_channels or 'email',
            'recipient_id': recipient.id,
            'recipient_email': recipient.email,
            'status': 'pending'
        })

        # Send based on channel
        if rule.notification_channels == 'email':
            self._send_email_notification(notification, alert, rule, recipient)

        return notification

    def _send_email_notification(self, notification, alert, rule, recipient):
        """Send email notification"""
        try:
            if rule.notification_template_id:
                rule.notification_template_id.send_mail(
                    alert.id,
                    force_send=True,
                    email_values={'email_to': recipient.email}
                )

            notification.write({
                'status': 'sent',
                'sent_at': datetime.now()
            })
        except Exception as e:
            notification.write({
                'status': 'failed',
                'error_message': str(e)
            })
```

---

## 4. Day-by-Day Implementation Plan

### Day 661: Workflow Engine Core

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design workflow engine architecture | Architecture document |
| 2:30-5:00 | Implement workflow definition models | `workflow.definition`, `workflow.state` |
| 5:00-7:00 | Create workflow transition model | `workflow.transition` model |
| 7:00-8:00 | Database migration scripts | SQL migrations |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Set up workflow state machine | State machine core |
| 2:00-4:00 | Implement instance management | `workflow.instance` model |
| 4:00-6:00 | Create workflow history tracking | History model |
| 6:00-7:30 | Configure Celery for workflow tasks | Task configuration |
| 7:30-8:00 | Write unit tests | Test coverage |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design workflow management UI | Wireframes |
| 2:00-4:00 | Create workflow list component | Workflow list view |
| 4:00-6:00 | Build workflow detail view | Detail component |
| 6:00-7:30 | Implement workflow status indicators | Status badges |
| 7:30-8:00 | Set up API service | `workflowApi.js` |

**Daily Deliverables:**
- [ ] Workflow definition models complete
- [ ] State machine core implemented
- [ ] Workflow list UI rendering

---

### Day 662: Workflow Execution Engine

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement workflow engine service | `workflow.engine` |
| 2:30-5:00 | Build transition execution logic | Transition handling |
| 5:00-7:00 | Create action executor | Action execution |
| 7:00-8:00 | Implement trigger handling | Trigger processing |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create workflow REST API | CRUD endpoints |
| 2:00-4:00 | Implement start/stop workflow actions | Instance management |
| 4:00-6:00 | Build condition evaluation engine | Condition parser |
| 6:00-7:30 | Write integration tests | API tests |
| 7:30-8:00 | API documentation | OpenAPI spec |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build workflow instance viewer | Instance detail view |
| 3:00-5:30 | Create transition action buttons | Action UI |
| 5:30-7:30 | Implement workflow history timeline | History view |
| 7:30-8:00 | Add loading states | UX improvements |

**Daily Deliverables:**
- [ ] Workflow engine executing transitions
- [ ] API endpoints functional
- [ ] Instance viewer complete

---

### Day 663: Approval System

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement approval request model | `approval.request` model |
| 2:30-5:00 | Build approval assignment logic | Multi-approver support |
| 5:00-7:00 | Create sequential approval handling | Sequential approval |
| 7:00-8:00 | Implement majority voting | Majority logic |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create approval REST API | Approval endpoints |
| 2:00-4:00 | Build delegation service | Delegation handling |
| 4:00-6:00 | Implement approval notifications | Email notifications |
| 6:00-7:30 | Write approval tests | Test coverage |
| 7:30-8:00 | Performance optimization | Query optimization |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build approval inbox | Pending approvals list |
| 3:00-5:30 | Create approval action modal | Approve/reject UI |
| 5:30-7:30 | Implement delegation UI | Delegate feature |
| 7:30-8:00 | Add approval status badges | Status indicators |

**Daily Deliverables:**
- [ ] Approval system working
- [ ] Multi-level approval functional
- [ ] Approval inbox complete

---

### Day 664: Order Approval Workflow

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design order approval workflow | Workflow definition |
| 2:30-5:00 | Implement approval matrix | Amount-based routing |
| 5:00-7:00 | Create auto-approval rules | Threshold rules |
| 7:00-8:00 | Build approval triggers | Order triggers |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Integrate with sale.order model | Order integration |
| 2:00-4:00 | Build approval status sync | Status synchronization |
| 4:00-6:00 | Create approval audit trail | Audit logging |
| 6:00-7:30 | Write order workflow tests | E2E tests |
| 7:30-8:00 | Documentation | User documentation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build order approval widget | In-form approval |
| 3:00-5:30 | Create approval progress indicator | Progress display |
| 5:30-7:30 | Implement comment requirement UI | Comment modal |
| 7:30-8:00 | Add rejection reason selector | Reason dropdown |

**Daily Deliverables:**
- [ ] Order approval workflow complete
- [ ] Approval matrix working
- [ ] Order widget integrated

---

### Day 665: Alert System & Inventory Monitoring

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement alert rule model | `alert.rule` model |
| 2:30-5:00 | Build inventory monitoring service | `inventory.alert.service` |
| 5:00-7:00 | Create alert instance model | `alert.instance` model |
| 7:00-8:00 | Implement cooldown logic | Alert throttling |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create scheduled job for inventory check | Celery task |
| 2:00-4:00 | Build alert notification service | Multi-channel notifications |
| 4:00-6:00 | Implement alert escalation | Escalation engine |
| 6:00-7:30 | Write alert tests | Test coverage |
| 7:30-8:00 | Performance testing | Load testing |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build alert dashboard | Alert overview |
| 3:00-5:30 | Create alert rule builder | Rule configuration |
| 5:30-7:30 | Implement alert acknowledgment UI | Acknowledge actions |
| 7:30-8:00 | Add alert severity indicators | Severity badges |

**Daily Deliverables:**
- [ ] Alert system operational
- [ ] Inventory monitoring working
- [ ] Alert dashboard complete

---

### Day 666: Visual Workflow Designer

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design workflow JSON schema | Schema definition |
| 2:30-5:00 | Implement workflow validation | Validation rules |
| 5:00-7:00 | Create workflow import/export | Import/export feature |
| 7:00-8:00 | Build workflow versioning | Version control |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create workflow designer API | Designer endpoints |
| 2:00-4:00 | Build workflow preview service | Preview generation |
| 4:00-6:00 | Implement workflow testing mode | Test execution |
| 6:00-7:30 | Write designer tests | Test coverage |
| 7:30-8:00 | API documentation | OpenAPI update |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-4:00 | Build visual workflow canvas | React Flow integration |
| 4:00-6:30 | Create state node components | Node UI |
| 6:30-8:00 | Implement transition edges | Edge connections |

**Daily Deliverables:**
- [ ] Workflow designer canvas working
- [ ] State/transition editing functional
- [ ] Preview capability ready

---

### Day 667: SLA Monitoring

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement SLA definition model | `sla.definition` model |
| 2:30-5:00 | Build SLA tracking service | SLA calculation |
| 5:00-7:00 | Create business hours calculation | Working hours |
| 7:00-8:00 | Implement SLA breach detection | Breach alerts |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create SLA monitoring job | Scheduled check |
| 2:00-4:00 | Build SLA API endpoints | REST API |
| 4:00-6:00 | Implement SLA escalation | Escalation rules |
| 6:00-7:30 | Write SLA tests | Test coverage |
| 7:30-8:00 | Performance optimization | Query tuning |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build SLA dashboard | SLA overview |
| 3:00-5:30 | Create SLA configuration UI | SLA setup |
| 5:30-7:30 | Implement SLA progress indicators | Progress bars |
| 7:30-8:00 | Add SLA breach notifications | Notification UI |

**Daily Deliverables:**
- [ ] SLA tracking operational
- [ ] Business hours calculation working
- [ ] SLA dashboard complete

---

### Day 668: Task Automation & Price Workflow

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement task automation rules | Task rules model |
| 2:30-5:00 | Build automatic task creation | Task generator |
| 5:00-7:00 | Create price change workflow | Price approval |
| 7:00-8:00 | Implement price change triggers | Price triggers |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create task automation API | Task API |
| 2:00-4:00 | Build price workflow integration | Price integration |
| 4:00-6:00 | Implement assignee resolution | Dynamic assignment |
| 6:00-7:30 | Write automation tests | Test coverage |
| 7:30-8:00 | Documentation | Feature documentation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build task automation builder | Rule builder UI |
| 3:00-5:30 | Create price approval interface | Price approval UI |
| 5:30-7:30 | Implement task assignment view | Assignment display |
| 7:30-8:00 | Add automation toggle UI | Enable/disable |

**Daily Deliverables:**
- [ ] Task automation working
- [ ] Price workflow complete
- [ ] Builder UI functional

---

### Day 669: Audit Trail & Compliance

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement audit log model | Partitioned table |
| 2:30-5:00 | Build audit logging service | Auto-logging |
| 5:00-7:00 | Create audit search functionality | Search/filter |
| 7:00-8:00 | Implement data retention | Retention policy |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create audit API endpoints | Audit API |
| 2:00-4:00 | Build compliance report generator | Report generation |
| 4:00-6:00 | Implement audit export | CSV/PDF export |
| 6:00-7:30 | Write audit tests | Test coverage |
| 7:30-8:00 | Security review | Access control |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build audit log viewer | Log display |
| 3:00-5:30 | Create audit search interface | Search UI |
| 5:30-7:30 | Implement change diff viewer | Before/after view |
| 7:30-8:00 | Add audit export buttons | Export UI |

**Daily Deliverables:**
- [ ] Audit logging operational
- [ ] Compliance reports generating
- [ ] Audit viewer complete

---

### Day 670: Testing & Deployment

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | End-to-end workflow testing | E2E tests |
| 2:00-4:00 | Performance benchmarking | Performance report |
| 4:00-6:00 | Bug fixes and optimization | Issue resolution |
| 6:00-7:30 | Code review | Review completion |
| 7:30-8:00 | Documentation finalization | Technical docs |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Integration testing | API tests |
| 2:00-4:00 | Security testing | Security audit |
| 4:00-5:30 | Staging deployment | Staging environment |
| 5:30-7:00 | UAT support | User testing |
| 7:00-8:00 | Deployment runbook | Operations docs |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | UI testing | Visual tests |
| 2:00-4:00 | Cross-browser testing | Compatibility |
| 4:00-5:30 | Accessibility testing | WCAG compliance |
| 5:30-7:00 | User documentation | User guide |
| 7:00-8:00 | Demo preparation | Demo script |

**Daily Deliverables:**
- [ ] All tests passing
- [ ] Security audit complete
- [ ] Staging deployment successful
- [ ] Documentation finalized

---

## 5. Success Criteria

### Functional Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Workflow execution time | <5 seconds | Performance tests |
| Approval routing accuracy | 100% | Audit verification |
| Alert delivery time | <30 seconds | Timing tests |
| SLA calculation accuracy | 100% | Data validation |
| Audit log completeness | 100% | Compliance audit |

### Technical Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Concurrent workflows | 1000+ | Load testing |
| Workflow throughput | 100/minute | Performance tests |
| Alert processing | 1000/minute | Load testing |
| Audit log write speed | 10000/second | Benchmark |

### Business Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Process automation rate | 50+ workflows | Workflow count |
| Manual work reduction | 40% | Process audit |
| SLA adherence | 98% | SLA reports |
| Audit compliance | 100% | Compliance audit |

---

## 6. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R127-01 | Workflow deadlocks | High | Medium | Timeout handling, monitoring |
| R127-02 | Approval bottlenecks | Medium | Medium | Escalation rules, delegation |
| R127-03 | Alert storm | Medium | Medium | Cooldown, rate limiting |
| R127-04 | SLA calculation errors | Medium | Low | Business hours validation |
| R127-05 | Audit log growth | Low | High | Partitioning, retention policy |

---

## 7. Dependencies

### Internal Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| User roles | Phase 3 | Approval routing |
| Order data | Phase 8 | Order workflow |
| Product data | Phase 6 | Inventory alerts |
| Notification service | Milestone 126 | Alert delivery |

### External Dependencies

| Dependency | Provider | Purpose |
|------------|----------|---------|
| Redis | Redis Ltd | State caching |
| Celery | Celery Project | Scheduled tasks |
| Email service | SendGrid/SES | Notifications |

---

**Document Version**: 1.0
**Last Updated**: 2026-02-04
**Next Review**: 2026-02-14
