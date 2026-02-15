# Milestone 32 - Advanced B2B Features Implementation

## Smart Dairy Smart Portal + ERP System

**Phase:** 4 - Enterprise Integration & B2B Enablement  
**Duration:** Days 311-320 (10 Days)  
**Milestone Owner:** Senior Technical Architect  
**Status:** Implementation Guide  
**Version:** 1.0  
**Last Updated:** 2024  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Technical Architecture](#2-technical-architecture)
3. [Daily Task Allocation](#3-daily-task-allocation)
4. [Database Design](#4-database-design)
5. [Implementation Logic](#5-implementation-logic)
6. [API Specifications](#6-api-specifications)
7. [Frontend Implementation](#7-frontend-implementation)
8. [Testing & QA](#8-testing--qa)
9. [Deliverables & Metrics](#9-deliverables--metrics)
10. [Risk Management](#10-risk-management)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 Milestone Objectives

Milestone 32 represents a critical advancement in the Smart Dairy B2B e-commerce capabilities, focusing on enterprise-grade features that automate complex ordering processes, enforce business rules through sophisticated approval workflows, and provide comprehensive credit management functionality.

**Primary Objectives:**

1. **Standing Orders System Implementation**
   - Develop a robust recurring order generation engine capable of handling daily, weekly, bi-weekly, and monthly frequencies
   - Implement intelligent exception handling for stockouts, price changes, and delivery constraints
   - Create pause/resume functionality with automatic notification systems
   - Build conflict resolution mechanisms for overlapping standing orders

2. **Order Templates & Reorder Functionality**
   - Enable customers to save frequently ordered product combinations as templates
   - Implement one-click reorder from historical orders with automatic quantity adjustments
   - Support template sharing across organizational units within B2B accounts
   - Create template analytics to suggest optimization opportunities

3. **Multi-Level Approval Workflows**
   - Design and implement configurable approval chains with role-based hierarchies
   - Support conditional approval based on order value, product categories, and credit exposure
   - Implement escalation timers and delegation mechanisms
   - Create mobile-responsive approval interfaces for on-the-go authorization

4. **Advanced Credit Management**
   - Build real-time credit checking integrated at multiple order touchpoints
   - Develop comprehensive aging report generation with configurable aging buckets
   - Implement collection workflow automation with trigger-based actions
   - Create credit limit adjustment workflows with risk assessment integration

5. **B2B Partner Self-Service Portal Enhancements**
   - Expand self-service capabilities with order modification, tracking, and claim submission
   - Implement document management for invoices, delivery receipts, and compliance certificates
   - Create collaborative forecasting interfaces for demand planning
   - Build notification preference management and communication history

6. **Contract Pricing & Special Agreements**
   - Develop contract price engine supporting complex pricing structures
   - Implement volume commitment tracking and compliance reporting
   - Support promotional pricing with automatic eligibility verification
   - Create rebate calculation and accrual management

7. **Volume-Based Incentive Calculations**
   - Build tiered incentive calculation engine with multiple attainment metrics
   - Support retroactive adjustments and true-up calculations
   - Implement forecast-based incentive projections
   - Create detailed incentive statements and achievement tracking

8. **B2B Reporting & Analytics Dashboard**
   - Develop executive-level dashboards with KPI visualization
   - Implement self-service report builder with drag-and-drop interface
   - Create automated report scheduling and distribution
   - Build comparative analytics for benchmarking and trend analysis

### 1.2 Business Value

**Quantifiable Benefits:**

| Metric | Baseline | Target | Impact |
|--------|----------|--------|--------|
| Order Processing Time | 45 minutes | 8 minutes | 82% reduction |
| Standing Order Accuracy | 94% | 99.5% | 5.5% improvement |
| Credit Check Latency | 3 seconds | 0.5 seconds | 83% faster |
| Approval Cycle Time | 48 hours | 4 hours | 91% reduction |
| Customer Service Tickets | 250/month | 75/month | 70% reduction |
| Revenue from Automation | $0 | $2.5M/year | New revenue stream |
| Order Template Usage | 0% | 65% of repeat orders | Efficiency gain |
| Credit Collection Days | 45 days | 32 days | 29% improvement |

**Strategic Value:**

1. **Customer Retention & Loyalty**
   - Standing orders create switching costs and increase customer stickiness
   - Self-service capabilities improve customer satisfaction scores
   - Transparent pricing and incentive visibility build trust
   - Faster approval cycles improve customer experience

2. **Operational Efficiency**
   - Automated order generation reduces manual intervention by 85%
   - Approval workflow digitization eliminates paper-based processes
   - Credit automation reduces collections staff workload by 40%
   - Template-based ordering accelerates the order-to-cash cycle

3. **Revenue Growth**
   - Contract pricing visibility encourages volume commitments
   - Incentive transparency drives higher attainment rates
   - Standing orders provide predictable revenue forecasting
   - Advanced credit terms attract larger enterprise accounts

4. **Risk Mitigation**
   - Real-time credit monitoring prevents bad debt exposure
   - Approval workflows enforce segregation of duties
   - Comprehensive audit trails support compliance requirements
   - Aging report automation enables proactive collection management

### 1.3 Success Metrics

**Technical Metrics:**

1. **Performance Benchmarks**
   - Standing order generation: Process 10,000 orders in < 5 minutes
   - Credit check API response time: < 500ms at 95th percentile
   - Approval workflow state transitions: < 200ms
   - Report generation: Complex reports in < 30 seconds
   - Dashboard load time: < 3 seconds for all widgets

2. **Reliability Targets**
   - Standing order success rate: > 99.9%
   - Credit calculation accuracy: 100% (zero tolerance for errors)
   - Approval notification delivery: > 99.5%
   - System uptime for B2B features: 99.95%

3. **Scalability Indicators**
   - Support for 50,000+ active standing orders
   - Handle 1,000+ concurrent approval workflows
   - Process 100,000 credit checks per hour
   - Generate 10,000 reports simultaneously

**Business Metrics:**

1. **Adoption Rates (90 days post-launch)**
   - Standing order adoption: 40% of eligible B2B customers
   - Template creation: 25 templates per active B2B account
   - Approval workflow usage: 100% of orders requiring approval
   - Self-service portal utilization: 75% of account interactions
   - Report dashboard access: 60% of B2B users weekly

2. **Efficiency Gains**
   - Average order entry time reduction: 70%
   - Approval cycle time reduction: 85%
   - Credit decision speed improvement: 80%
   - Customer service call reduction: 60%

3. **Financial Impact (12 months)**
   - Cost savings from automation: $450,000
   - Revenue increase from faster processing: $1.2M
   - Bad debt reduction from improved credit management: $200,000
   - Customer lifetime value increase: 25%

---

## 2. Technical Architecture

### 2.1 Advanced B2B Features Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                           ADVANCED B2B FEATURES ARCHITECTURE                            │
└─────────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                                    PRESENTATION LAYER                                    │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐               │
│  │  B2B Customer       │  │  Approval Manager   │  │  Credit Manager     │               │
│  │  Self-Service       │  │  Dashboard          │  │  Workbench          │               │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────┘               │
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐               │
│  │  Standing Order     │  │  Report Builder     │  │  Analytics          │               │
│  │  Configuration UI   │  │  & Viewer           │  │  Dashboard          │               │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────┘               │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                          │
                                          ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                                     API GATEWAY LAYER                                    │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │
│  │ Standing    │  │ Approval    │  │ Credit      │  │ Reporting   │  │ Template    │     │
│  │ Order API   │  │ Workflow API│  │ Management  │  │ API         │  │ API         │     │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                       │
│  │ Contract    │  │ Incentive   │  │ Self-Service│  │ Notification│                       │
│  │ Pricing API │  │ Calc API    │  │ Portal API  │  │ API         │                       │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘                       │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                          │
                                          ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                                   BUSINESS LOGIC LAYER                                   │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         STANDING ORDER ENGINE                                    │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Frequency    │  │ Generation   │  │ Exception    │  │ Conflict     │         │    │
│  │  │ Calculator   │  │ Scheduler    │  │ Handler      │  │ Resolver     │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                        WORKFLOW ENGINE                                           │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ State Machine│  │ Rules Engine │  │ Escalation   │  │ Delegation   │         │    │
│  │  │ Manager      │  │              │  │ Manager      │  │ Handler      │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                        CREDIT MANAGEMENT ENGINE                                  │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Real-time    │  │ Aging        │  │ Limit        │  │ Collection   │         │    │
│  │  │ Checker      │  │ Calculator   │  │ Adjuster     │  │ Workflow     │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                        PRICING & INCENTIVE ENGINE                                │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Contract     │  │ Volume Tier  │  │ Rebate       │  │ Incentive    │         │    │
│  │  │ Resolver     │  │ Calculator   │  │ Engine       │  │ Projector    │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                        REPORTING & ANALYTICS ENGINE                              │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Data Mart    │  │ Query        │  │ Visualization│  │ Scheduler    │         │    │
│  │  │ Manager      │  │ Builder      │  │ Engine       │  │              │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                          │
                                          ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                                      DATA LAYER                                          │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐               │
│  │  PostgreSQL         │  │  Redis              │  │  Elasticsearch      │               │
│  │  (Primary Database) │  │  (Cache & Sessions) │  │  (Search & Analytics)│               │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────┘               │
│  ┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐               │
│  │  Message Queue      │  │  Data Warehouse     │  │  File Storage       │               │
│  │  (RabbitMQ/Celery)  │  │  (Reporting)        │  │  (Documents)        │               │
│  └─────────────────────┘  └─────────────────────┘  └─────────────────────┘               │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Workflow Engine Design

The workflow engine is the core orchestration component for approval processes. It implements a flexible state machine pattern with configurable rules and actions.

**State Machine Architecture:**

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                           APPROVAL WORKFLOW STATE MACHINE                               │
└─────────────────────────────────────────────────────────────────────────────────────────┘

                                      ┌─────────────┐
                                      │    START    │
                                      └──────┬──────┘
                                             │
                                             ▼
                              ┌──────────────────────────────┐
                              │  EVALUATE APPROVAL RULES     │
                              │  - Check order value         │
                              │  - Check product categories  │
                              │  - Check credit exposure     │
                              │  - Check user permissions    │
                              └──────────────┬───────────────┘
                                             │
                    ┌────────────────────────┼────────────────────────┐
                    │                        │                        │
                    ▼                        ▼                        ▼
           ┌─────────────┐          ┌─────────────┐          ┌─────────────┐
           │  AUTO-      │          │  APPROVAL   │          │  AUTO-      │
           │  APPROVED   │          │  REQUIRED   │          │  REJECTED   │
           └─────────────┘          └──────┬──────┘          └─────────────┘
                                           │
                                           ▼
                              ┌──────────────────────────────┐
                              │  IDENTIFY APPROVERS          │
                              │  - Resolve approval chain    │
                              │  - Check availability        │
                              │  - Apply delegation rules    │
                              └──────────────┬───────────────┘
                                             │
                                             ▼
                                   ┌─────────────────┐
                                   │  PENDING LEVEL 1│
                                   │  APPROVAL       │
                                   └────────┬────────┘
                                            │
              ┌─────────────────────────────┼─────────────────────────────┐
              │                             │                             │
              ▼                             ▼                             ▼
       ┌─────────────┐              ┌─────────────┐              ┌─────────────┐
       │  APPROVED   │              │  ESCALATED  │              │  REJECTED   │
       └──────┬──────┘              └──────┬──────┘              └──────┬──────┘
              │                            │                            │
              ▼                            ▼                            ▼
    ┌─────────────────┐          ┌─────────────────┐          ┌─────────────────┐
    │  CHECK ADDITIONAL│         │  PENDING LEVEL N│          │  NOTIFY         │
    │  LEVELS REQUIRED │         │  APPROVAL       │          │  SUBMITTER      │
    └────────┬────────┘          └────────┬────────┘          └─────────────────┘
             │                            │
    ┌────────┴────────┐                   │
    │                 │                   │
    ▼                 ▼                   ▼
┌─────────┐    ┌─────────────┐    ┌─────────────┐
│   YES   │    │   NO        │    │  COMPLETED  │
└────┬────┘    └──────┬──────┘    └─────────────┘
     │                │
     ▼                ▼
┌─────────────┐  ┌─────────────┐
│PENDING LEVEL│  │  FINAL      │
│    N+1      │  │  APPROVAL   │
└─────────────┘  └──────┬──────┘
                        │
                        ▼
               ┌─────────────────┐
               │  CREATE ORDER   │
               │  NOTIFY PARTIES │
               └─────────────────┘
```

**Workflow Configuration Schema:**


```python
# Odoo Workflow Definition XML for B2B Approval Workflow
# File: b2b_approval_workflow.xml

<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <data noupdate="0">
        
        <!-- Workflow Definition for B2B Order Approval -->
        <record id="b2b_order_approval_workflow" model="workflow">
            <field name="name">B2B Order Approval Workflow</field>
            <field name="osv">sale.order</field>
            <field name="on_create">True</field>
        </record>
        
        <!-- Workflow Activities -->
        <record id="act_draft" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="flow_start">True</field>
            <field name="name">draft</field>
            <field name="kind">function</field>
            <field name="action">write({'state': 'draft'})</field>
        </record>
        
        <record id="act_evaluation" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="name">evaluation</field>
            <field name="kind">function</field>
            <field name="action">
                action_evaluate_approval_requirements()
            </field>
            <field name="split_mode">XOR</field>
        </record>
        
        <record id="act_pending_approval" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="name">pending_approval</field>
            <field name="kind">function</field>
            <field name="action">
                write({'state': 'pending_approval'})
                action_notify_approvers()
            </field>
        </record>
        
        <record id="act_auto_approved" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="name">auto_approved</field>
            <field name="kind">function</field>
            <field name="action">
                write({'state': 'approved'})
                action_auto_confirm_order()
            </field>
        </record>
        
        <record id="act_approved" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="name">approved</field>
            <field name="kind">function</field>
            <field name="action">
                write({'state': 'approved'})
                action_confirm_order()
                action_notify_confirmation()
            </field>
            <field name="join_mode">AND</field>
        </record>
        
        <record id="act_rejected" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="name">rejected</field>
            <field name="kind">function</field>
            <field name="flow_stop">True</field>
            <field name="action">
                write({'state': 'rejected'})
                action_notify_rejection()
                action_create_rejection_log()
            </field>
        </record>
        
        <record id="act_escalated" model="workflow.activity">
            <field name="wkf_id" ref="b2b_order_approval_workflow"/>
            <field name="name">escalated</field>
            <field name="kind">function</field>
            <field name="action">
                action_escalate_to_next_level()
                action_notify_escalation()
            </field>
        </record>
        
        <!-- Workflow Transitions -->
        <record id="trans_draft_to_evaluation" model="workflow.transition">
            <field name="act_from" ref="act_draft"/>
            <field name="act_to" ref="act_evaluation"/>
            <field name="signal">submit_for_approval</field>
            <field name="condition">is_b2b_order() and requires_approval()</field>
        </record>
        
        <record id="trans_evaluation_to_auto_approved" model="workflow.transition">
            <field name="act_from" ref="act_evaluation"/>
            <field name="act_to" ref="act_auto_approved"/>
            <field name="condition">is_auto_approval_eligible()</field>
        </record>
        
        <record id="trans_evaluation_to_pending" model="workflow.transition">
            <field name="act_from" ref="act_evaluation"/>
            <field name="act_to" ref="act_pending_approval"/>
            <field name="condition">requires_manual_approval()</field>
        </record>
        
        <record id="trans_pending_to_approved" model="workflow.transition">
            <field name="act_from" ref="act_pending_approval"/>
            <field name="act_to" ref="act_approved"/>
            <field name="signal">approve</field>
            <field name="condition">can_approve(user_id) and is_final_approval_level()</field>
        </record>
        
        <record id="trans_pending_to_escalated" model="workflow.transition">
            <field name="act_from" ref="act_pending_approval"/>
            <field name="act_to" ref="act_escalated"/>
            <field name="signal">approve</field>
            <field name="condition">can_approve(user_id) and not is_final_approval_level()</field>
        </record>
        
        <record id="trans_pending_to_rejected" model="workflow.transition">
            <field name="act_from" ref="act_pending_approval"/>
            <field name="act_to" ref="act_rejected"/>
            <field name="signal">reject</field>
            <field name="condition">can_approve(user_id)</field>
        </record>
        
        <record id="trans_escalated_to_pending" model="workflow.transition">
            <field name="act_from" ref="act_escalated"/>
            <field name="act_to" ref="act_pending_approval"/>
            <field name="condition">True</field>
        </record>
        
        <record id="trans_pending_to_rejected_timeout" model="workflow.transition">
            <field name="act_from" ref="act_pending_approval"/>
            <field name="act_to" ref="act_rejected"/>
            <field name="trigger_model">sale.order</field>
            <field name="trigger_expr">is_escalation_timeout_reached()</field>
        </record>
        
    </data>
</odoo>
```

### 2.3 Reporting Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              REPORTING ARCHITECTURE                                      │
└─────────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                               DATA SOURCES LAYER                                         │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐     │
│  │  ERP System     │  │  E-commerce     │  │  CRM System     │  │  External APIs  │     │
│  │  (Odoo)         │  │  (Smart Portal) │  │                 │  │                 │     │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘     │
│           │                    │                    │                    │              │
│           └────────────────────┴────────────────────┴────────────────────┘              │
│                                          │                                              │
│                                          ▼                                              │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         ETL PIPELINE (Apache Airflow)                            │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Extract      │  │ Transform    │  │ Load         │  │ Schedule     │         │    │
│  │  │ - CDC        │  │ - Clean      │  │ - Data Mart  │  │ - Hourly     │         │    │
│  │  │ - Batch      │  │ - Aggregate  │  │ - Warehouse  │  │ - Daily      │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                          │
                                          ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                              DATA STORAGE LAYER                                          │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         DATA WAREHOUSE                                           │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Raw Data     │  │ Staging      │  │ Dimensional  │  │ Aggregate    │         │    │
│  │  │ Layer        │  │ Area         │  │ Models       │  │ Tables       │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  │                                                                                  │    │
│  │  Fact Tables:                                                                    │    │
│  │  - fct_orders, fct_payments, fct_shipments, fct_credit_usage                   │    │
│  │                                                                                  │    │
│  │  Dimension Tables:                                                               │    │
│  │  - dim_customer, dim_product, dim_time, dim_geography                          │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         DATA MART (B2B Specific)                                 │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Standing     │  │ Approval     │  │ Credit       │  │ Incentive    │         │    │
│  │  │ Orders Mart  │  │ Workflow Mart│  │ Analysis Mart│  │ Tracking Mart│         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                          │
                                          ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                            REPORTING SERVICES LAYER                                      │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         QUERY ENGINE                                             │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ SQL Engine   │  │ MDX Engine   │  │ REST API     │  │ GraphQL      │         │    │
│  │  │              │  │              │  │ Layer        │  │ Endpoint     │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         REPORT BUILDER                                           │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Drag & Drop  │  │ Chart        │  │ Filter       │  │ Export       │         │    │
│  │  │ Designer     │  │ Library      │  │ Builder      │  │ Manager      │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────────────────────────────┐    │
│  │                         ANALYTICS ENGINE                                         │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │    │
│  │  │ Trend        │  │ Predictive   │  │ Anomaly      │  │ Benchmark    │         │    │
│  │  │ Analysis     │  │ Modeling     │  │ Detection    │  │ Engine       │         │    │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘         │    │
│  └─────────────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────────┘
                                          │
                                          ▼
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                           PRESENTATION LAYER                                             │
├─────────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │
│  │ Dashboard   │  │ Scheduled   │  │ Ad-hoc      │  │ Mobile      │  │ Embedded    │     │
│  │ Widgets     │  │ Reports     │  │ Queries     │  │ Reports     │  │ Analytics   │     │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘     │
└─────────────────────────────────────────────────────────────────────────────────────────┘
```

### 2.4 Integration Patterns

**Event-Driven Architecture for B2B Features:**

```python
# Event-Driven Integration Pattern for Advanced B2B Features
# File: b2b_events_integration.py

from odoo import models, fields, api
from odoo.addons.bus.models.bus import dispatch
import json
from datetime import datetime
import logging

_logger = logging.getLogger(__name__)

class B2BEventPublisher(models.AbstractModel):
    """
    Abstract model for publishing B2B-related events.
    Implements the Event-Driven Architecture pattern for loose coupling
    between B2B modules and external systems.
    """
    _name = 'b2b.event.publisher'
    _description = 'B2B Event Publisher'
    
    EVENT_TYPES = [
        ('standing_order.created', 'Standing Order Created'),
        ('standing_order.executed', 'Standing Order Executed'),
        ('standing_order.failed', 'Standing Order Execution Failed'),
        ('standing_order.paused', 'Standing Order Paused'),
        ('standing_order.resumed', 'Standing Order Resumed'),
        ('standing_order.cancelled', 'Standing Order Cancelled'),
        ('approval.submitted', 'Approval Submitted'),
        ('approval.approved', 'Order Approved'),
        ('approval.rejected', 'Order Rejected'),
        ('approval.escalated', 'Approval Escalated'),
        ('approval.delegated', 'Approval Delegated'),
        ('approval.timeout', 'Approval Timeout'),
        ('credit.limit_exceeded', 'Credit Limit Exceeded'),
        ('credit.aging_alert', 'Credit Aging Alert'),
        ('credit.payment_received', 'Payment Received'),
        ('credit.hold_applied', 'Credit Hold Applied'),
        ('credit.hold_released', 'Credit Hold Released'),
        ('contract.price_activated', 'Contract Price Activated'),
        ('contract.price_expired', 'Contract Price Expired'),
        ('contract.volume_threshold', 'Volume Threshold Reached'),
        ('incentive.calculated', 'Incentive Calculated'),
        ('incentive.paid', 'Incentive Paid'),
        ('template.created', 'Order Template Created'),
        ('template.used', 'Order Template Used'),
        ('report.generated', 'Report Generated'),
    ]
    
    def publish_event(self, event_type, payload, priority='normal'):
        """
        Publish a B2B event to the event bus.
        
        Args:
            event_type: Type of event from EVENT_TYPES
            payload: Dictionary containing event data
            priority: Event priority (low, normal, high, critical)
        
        Returns:
            event_id: Unique identifier for the published event
        """
        self.ensure_one()
        
        # Validate event type
        if event_type not in [e[0] for e in self.EVENT_TYPES]:
            raise ValueError(f"Invalid event type: {event_type}")
        
        # Enrich payload with metadata
        enriched_payload = {
            'event_id': self.env['ir.sequence'].next_by_code('b2b.event'),
            'event_type': event_type,
            'timestamp': datetime.utcnow().isoformat(),
            'source': self._name,
            'source_id': self.id,
            'priority': priority,
            'payload': payload,
            'metadata': {
                'user_id': self.env.uid,
                'company_id': self.env.company.id,
                'version': '1.0'
            }
        }
        
        # Publish to internal bus for real-time notifications
        self._publish_to_bus(event_type, enriched_payload)
        
        # Persist event for audit and replay capabilities
        event_record = self._persist_event(enriched_payload)
        
        # Publish to external message queue for integration
        self._publish_to_message_queue(event_type, enriched_payload)
        
        # Trigger webhooks if configured
        self._trigger_webhooks(event_type, enriched_payload)
        
        _logger.info(f"B2B Event Published: {event_type} - ID: {enriched_payload['event_id']}")
        
        return enriched_payload['event_id']
    
    def _publish_to_bus(self, event_type, payload):
        """Publish event to Odoo's internal bus for real-time UI updates."""
        channel = f'b2b_events_{self.env.company.id}'
        notifications = [[channel, payload]]
        dispatch(notifications)
    
    def _persist_event(self, payload):
        """Persist event to database for audit trail and replay."""
        return self.env['b2b.event.log'].create({
            'event_id': payload['event_id'],
            'event_type': payload['event_type'],
            'timestamp': payload['timestamp'],
            'source_model': payload['source'],
            'source_id': payload['source_id'],
            'priority': payload['priority'],
            'payload_json': json.dumps(payload['payload']),
            'user_id': payload['metadata']['user_id'],
            'company_id': payload['metadata']['company_id'],
            'status': 'pending_processing'
        })
    
    def _publish_to_message_queue(self, event_type, payload):
        """Publish event to RabbitMQ for external system integration."""
        try:
            # Implementation using pika or Celery
            self.env['b2b.message.queue'].sudo().publish(
                exchange='b2b_events',
                routing_key=event_type.replace('.', '_'),
                body=json.dumps(payload)
            )
        except Exception as e:
            _logger.error(f"Failed to publish to message queue: {str(e)}")
            # Store for retry
            self._schedule_retry(event_type, payload)
    
    def _trigger_webhooks(self, event_type, payload):
        """Trigger configured webhooks for the event type."""
        webhooks = self.env['b2b.webhook.config'].sudo().search([
            ('event_type', '=', event_type),
            ('active', '=', True),
            ('company_id', '=', self.env.company.id)
        ])
        
        for webhook in webhooks:
            self.env['b2b.webhook.queue'].sudo().create({
                'webhook_id': webhook.id,
                'payload': json.dumps(payload),
                'status': 'pending',
                'retry_count': 0
            })
    
    def _schedule_retry(self, event_type, payload):
        """Schedule retry for failed event publishing."""
        self.env['b2b.event.retry.queue'].sudo().create({
            'event_type': event_type,
            'payload': json.dumps(payload),
            'retry_count': 0,
            'next_retry': datetime.utcnow()
        })


class B2BEventSubscriber(models.AbstractModel):
    """
    Abstract model for subscribing to B2B events.
    Implement event handlers in concrete models.
    """
    _name = 'b2b.event.subscriber'
    _description = 'B2B Event Subscriber'
    
    def subscribe_to_event(self, event_type, handler_method):
        """Register an event subscription."""
        # Implementation for dynamic subscription management
        pass
    
    def handle_standing_order_executed(self, payload):
        """Handle standing order execution events."""
        raise NotImplementedError()
    
    def handle_approval_submitted(self, payload):
        """Handle approval submission events."""
        raise NotImplementedError()
    
    def handle_credit_limit_exceeded(self, payload):
        """Handle credit limit exceeded events."""
        raise NotImplementedError()


# SAGA Pattern Implementation for Distributed B2B Transactions
class B2BOrderSaga(models.Model):
    """
    Implements the Saga pattern for managing long-running B2B order processes.
    Ensures consistency across multiple microservices/database operations.
    """
    _name = 'b2b.order.saga'
    _description = 'B2B Order Processing Saga'
    
    name = fields.Char('Saga ID', required=True, copy=False, readonly=True)
    order_id = fields.Many2one('sale.order', 'Source Order')
    state = fields.Selection([
        ('started', 'Started'),
        ('validating', 'Validating'),
        ('checking_credit', 'Checking Credit'),
        ('awaiting_approval', 'Awaiting Approval'),
        ('reserving_inventory', 'Reserving Inventory'),
        ('pricing', 'Calculating Pricing'),
        ('completed', 'Completed'),
        ('compensating', 'Compensating'),
        ('failed', 'Failed')
    ], default='started', tracking=True)
    
    step_results = fields.Json('Step Results', default=dict)
    compensation_log = fields.Json('Compensation Log', default=list)
    error_message = fields.Text('Error Message')
    
    def execute_saga(self):
        """Execute the order processing saga with compensation support."""
        try:
            # Step 1: Validate Order
            self._transition_to('validating')
            validation_result = self._execute_step('validate_order')
            if not validation_result['success']:
                return self._fail_saga(validation_result['error'])
            
            # Step 2: Check Credit
            self._transition_to('checking_credit')
            credit_result = self._execute_step('check_credit')
            if not credit_result['success']:
                return self._fail_saga(credit_result['error'])
            
            if credit_result['requires_approval']:
                self._transition_to('awaiting_approval')
                # Saga pauses here, will be resumed by approval event
                return self._pause_saga('pending_approval')
            
            # Step 3: Reserve Inventory
            self._transition_to('reserving_inventory')
            inventory_result = self._execute_step('reserve_inventory')
            if not inventory_result['success']:
                self._compensate_credit_hold()
                return self._fail_saga(inventory_result['error'])
            
            # Step 4: Calculate Final Pricing
            self._transition_to('pricing')
            pricing_result = self._execute_step('calculate_pricing')
            if not pricing_result['success']:
                self._compensate_inventory_reservation()
                self._compensate_credit_hold()
                return self._fail_saga(pricing_result['error'])
            
            # Saga completed successfully
            self._transition_to('completed')
            self._notify_completion()
            return True
            
        except Exception as e:
            self._compensate_all_steps()
            return self._fail_saga(str(e))
    
    def _execute_step(self, step_name):
        """Execute a saga step with proper error handling."""
        try:
            method = getattr(self, f'_step_{step_name}')
            result = method()
            self.step_results[step_name] = result
            return result
        except Exception as e:
            return {'success': False, 'error': str(e)}
    
    def _compensate_all_steps(self):
        """Execute compensation for all completed steps in reverse order."""
        steps = list(self.step_results.keys())
        steps.reverse()
        
        for step in steps:
            compensate_method = getattr(self, f'_compensate_{step}', None)
            if compensate_method:
                try:
                    compensate_method()
                    self.compensation_log.append({
                        'step': step,
                        'status': 'compensated',
                        'timestamp': datetime.utcnow().isoformat()
                    })
                except Exception as e:
                    self.compensation_log.append({
                        'step': step,
                        'status': 'compensation_failed',
                        'error': str(e),
                        'timestamp': datetime.utcnow().isoformat()
                    })
                    _logger.error(f"Compensation failed for step {step}: {str(e)}")
    
    def _step_validate_order(self):
        """Validate order data and business rules."""
        order = self.order_id
        errors = []
        
        # Check required fields
        if not order.partner_id:
            errors.append("Customer is required")
        if not order.order_line:
            errors.append("Order must have at least one line")
        
        # Check product availability for B2B
        for line in order.order_line:
            if line.product_id.type == 'product':
                qty_available = line.product_id.qty_available
                if qty_available < line.product_uom_qty:
                    errors.append(f"Insufficient stock for {line.product_id.name}")
        
        # Check minimum order value for B2B
        min_order_value = order.partner_id.b2b_min_order_value or 0
        if order.amount_untaxed < min_order_value:
            errors.append(f"Order value below minimum of {min_order_value}")
        
        return {
            'success': len(errors) == 0,
            'error': '; '.join(errors) if errors else None,
            'validation_time': datetime.utcnow().isoformat()
        }
    
    def _step_check_credit(self):
        """Check customer credit and apply holds if needed."""
        partner = self.order_id.partner_id
        credit_engine = self.env['b2b.credit.engine']
        
        credit_status = credit_engine.check_credit(
            partner_id=partner.id,
            order_amount=self.order_id.amount_total
        )
        
        if credit_status['status'] == 'approved':
            # Apply credit hold
            hold_id = credit_engine.apply_credit_hold(
                partner_id=partner.id,
                order_id=self.order_id.id,
                amount=self.order_id.amount_total
            )
            return {
                'success': True,
                'credit_hold_id': hold_id,
                'requires_approval': False
            }
        elif credit_status['status'] == 'approval_required':
            return {
                'success': True,
                'requires_approval': True,
                'approval_reason': credit_status['reason']
            }
        else:
            return {
                'success': False,
                'error': f"Credit check failed: {credit_status['reason']}"
            }
    
    def _step_reserve_inventory(self):
        """Reserve inventory for the order."""
        reservation_ids = []
        try:
            for line in self.order_id.order_line:
                if line.product_id.type == 'product':
                    reservation = self.env['stock.reservation'].create({
                        'product_id': line.product_id.id,
                        'product_uom_qty': line.product_uom_qty,
                        'product_uom': line.product_uom.id,
                        'sale_line_id': line.id,
                        'state': 'confirmed'
                    })
                    reservation_ids.append(reservation.id)
            
            return {
                'success': True,
                'reservation_ids': reservation_ids
            }
        except Exception as e:
            # Cleanup partial reservations
            if reservation_ids:
                self.env['stock.reservation'].browse(reservation_ids).unlink()
            return {'success': False, 'error': str(e)}
    
    def _step_calculate_pricing(self):
        """Calculate final pricing including contract and volume discounts."""
        pricing_engine = self.env['b2b.pricing.engine']
        
        result = pricing_engine.calculate_order_pricing(
            order_id=self.order_id.id
        )
        
        return {
            'success': result['success'],
            'pricing_details': result,
            'error': result.get('error')
        }
    
    def _compensate_credit_hold(self):
        """Release credit hold as compensation."""
        hold_id = self.step_results.get('check_credit', {}).get('credit_hold_id')
        if hold_id:
            self.env['b2b.credit.hold'].browse(hold_id).release()
    
    def _compensate_inventory_reservation(self):
        """Release inventory reservations as compensation."""
        reservation_ids = self.step_results.get('reserve_inventory', {}).get('reservation_ids', [])
        if reservation_ids:
            self.env['stock.reservation'].browse(reservation_ids).unlink()


# CQRS Pattern Implementation for Reporting
class B2BReportQueryHandler(models.Model):
    """
    Implements CQRS pattern separating read and write operations
    for B2B reporting queries.
    """
    _name = 'b2b.report.query.handler'
    _description = 'B2B Report Query Handler'
    _auto = False  # This is a read-only query handler
    
    @api.model
    def execute_report_query(self, report_type, filters, sort=None, pagination=None):
        """
        Execute optimized read queries for reports.
        Uses denormalized data marts for fast retrieval.
        """
        query_builder = self._get_query_builder(report_type)
        return query_builder.build_and_execute(filters, sort, pagination)
    
    def _get_query_builder(self, report_type):
        """Get appropriate query builder for report type."""
        builders = {
            'standing_order_summary': StandingOrderQueryBuilder,
            'approval_workflow_status': ApprovalWorkflowQueryBuilder,
            'credit_aging_analysis': CreditAgingQueryBuilder,
            'incentive_calculation': IncentiveQueryBuilder,
            'b2b_sales_performance': B2BSalesQueryBuilder,
        }
        return builders.get(report_type, DefaultQueryBuilder)(self.env)


class StandingOrderQueryBuilder:
    """Query builder optimized for standing order reports."""
    
    def __init__(self, env):
        self.env = env
        self.base_query = """
            SELECT 
                so.id,
                so.name as standing_order_number,
                so.customer_id,
                rp.name as customer_name,
                so.frequency,
                so.next_execution_date,
                so.status,
                so.total_value,
                so.success_rate,
                so.last_execution_status,
                COUNT(sol.id) as line_count,
                STRING_AGG(DISTINCT pt.name, ', ') as products
            FROM b2b_standing_orders so
            JOIN res_partner rp ON rp.id = so.customer_id
            LEFT JOIN b2b_standing_order_lines sol ON sol.standing_order_id = so.id
            LEFT JOIN product_product pp ON pp.id = sol.product_id
            LEFT JOIN product_template pt ON pt.id = pp.product_tmpl_id
            WHERE 1=1
        """
        self.params = []
    
    def build_and_execute(self, filters, sort, pagination):
        """Build and execute the standing order query."""
        query = self.base_query
        
        # Apply filters
        if filters.get('customer_id'):
            query += " AND so.customer_id = %s"
            self.params.append(filters['customer_id'])
        
        if filters.get('frequency'):
            query += " AND so.frequency = %s"
            self.params.append(filters['frequency'])
        
        if filters.get('status'):
            query += " AND so.status = %s"
            self.params.append(filters['status'])
        
        if filters.get('date_from'):
            query += " AND so.next_execution_date >= %s"
            self.params.append(filters['date_from'])
        
        if filters.get('date_to'):
            query += " AND so.next_execution_date <= %s"
            self.params.append(filters['date_to'])
        
        # Group by
        query += """
            GROUP BY so.id, so.name, so.customer_id, rp.name, 
                     so.frequency, so.next_execution_date, so.status,
                     so.total_value, so.success_rate, so.last_execution_status
        """
        
        # Apply sorting
        if sort:
            query += f" ORDER BY {sort['field']} {sort['direction']}"
        
        # Apply pagination
        if pagination:
            offset = pagination.get('offset', 0)
            limit = pagination.get('limit', 50)
            query += f" LIMIT {limit} OFFSET {offset}"
        
        self.env.cr.execute(query, self.params)
        return self.env.cr.dictfetchall()

```

---

## 3. Daily Task Allocation

### 3.1 Day 311 - Foundation & Architecture Setup

#### Developer 1 (Lead Dev) - Standing Order Engine Architecture

**Morning Tasks (4 hours):**

1. **Architecture Design Session (2 hours)**
   - Review existing B2B catalog and cart system from Milestone 31
   - Design standing order engine architecture document
   - Define service boundaries and interfaces
   - Create component interaction diagrams
   - Document data flow for standing order lifecycle

2. **Technical Specification Development (2 hours)**
   - Write detailed technical specification for standing order engine
   - Define API contracts for standing order operations
   - Document error handling and recovery strategies
   - Specify performance requirements and scalability targets
   - Create sequence diagrams for key operations

**Afternoon Tasks (4 hours):**

3. **Core Engine Skeleton Implementation (2 hours)**
   ```python
   # Create base structure for standing order engine
   # File: models/b2b_standing_order_engine.py
   
   class B2BStandingOrderEngine(models.Model):
       _name = 'b2b.standing.order.engine'
       _description = 'Standing Order Processing Engine'
       
       def __init__(self, pool, cr):
           super().__init__(pool, cr)
           self._load_configuration()
       
       def _load_configuration(self):
           """Load standing order engine configuration."""
           self.config = {
               'max_retry_attempts': 3,
               'default_execution_time': '06:00',
               'batch_size': 100,
               'notification_enabled': True,
               'conflict_resolution_strategy': 'newest_priority'
           }
   ```

4. **Database Migration Setup (2 hours)**
   - Create migration scripts for standing order tables
   - Define indexes for performance optimization
   - Set up foreign key constraints
   - Create initial seed data for testing

**Deliverables by End of Day:**
- Architecture document (1,500+ lines)
- Technical specification document
- Base engine skeleton with configuration loading
- Database migration files ready

#### Developer 2 (Backend Dev) - Data Models & Standing Order Foundation

**Morning Tasks (4 hours):**

1. **Standing Order Model Definition (2 hours)**
   ```python
   # Complete Standing Order Model
   # File: models/b2b_standing_order.py
   
   class B2BStandingOrder(models.Model):
       _name = 'b2b.standing.order'
       _description = 'B2B Standing Order'
       _inherit = ['mail.thread', 'mail.activity.mixin']
       _order = 'next_execution_date asc, priority desc'
       
       # Identification
       name = fields.Char(
           'Standing Order Number',
           required=True,
           copy=False,
           readonly=True,
           index=True,
           default=lambda self: self.env['ir.sequence'].next_by_code('b2b.standing.order')
       )
       
       # Customer Information
       partner_id = fields.Many2one(
           'res.partner',
           'Customer',
           required=True,
           index=True,
           domain=[('is_company', '=', True), ('customer_rank', '>', 0)],
           tracking=True
       )
       
       contact_id = fields.Many2one(
           'res.partner',
           'Primary Contact',
           domain="[('parent_id', '=', partner_id), ('type', '=', 'contact')]"
       )
       
       # Schedule Configuration
       frequency = fields.Selection([
           ('daily', 'Daily'),
           ('weekly', 'Weekly'),
           ('bi_weekly', 'Bi-Weekly'),
           ('monthly', 'Monthly'),
           ('quarterly', 'Quarterly'),
           ('custom', 'Custom')
       ], string='Frequency', required=True, default='weekly', tracking=True)
       
       execution_day = fields.Integer(
           'Execution Day',
           help='Day of week (1-7) for weekly, day of month (1-31) for monthly'
       )
       
       execution_time = fields.Float(
           'Execution Time',
           default=6.0,
           help='Time of day in 24-hour format'
       )
       
       next_execution_date = fields.Datetime(
           'Next Execution',
           compute='_compute_next_execution',
           store=True,
           index=True
       )
       
       last_execution_date = fields.Datetime(
           'Last Execution',
           readonly=True
       )
       
       # Status and Control
       state = fields.Selection([
           ('draft', 'Draft'),
           ('active', 'Active'),
           ('paused', 'Paused'),
           ('exception', 'Exception'),
           ('completed', 'Completed'),
           ('cancelled', 'Cancelled')
       ], string='Status', default='draft', tracking=True)
       
       priority = fields.Selection([
           ('0', 'Low'),
           ('1', 'Normal'),
           ('2', 'High'),
           ('3', 'Critical')
       ], string='Priority', default='1')
       
       # Order Configuration
       pricelist_id = fields.Many2one(
           'product.pricelist',
           'Pricelist',
           required=True
       )
       
       payment_term_id = fields.Many2one(
           'account.payment.term',
           'Payment Terms'
       )
       
       incoterm_id = fields.Many2one(
           'account.incoterm',
           'Incoterm'
       )
       
       # Delivery Information
       warehouse_id = fields.Many2one(
           'stock.warehouse',
           'Warehouse',
           required=True
       )
       
       delivery_address_id = fields.Many2one(
           'res.partner',
           'Delivery Address',
           domain="[('parent_id', '=', partner_id), ('type', '=', 'delivery')]"
       )
       
       # Financial Summary
       total_amount = fields.Monetary(
           'Total Amount',
           compute='_compute_totals',
           store=True,
           currency_field='currency_id'
       )
       
       currency_id = fields.Many2one(
           'res.currency',
           related='pricelist_id.currency_id',
           store=True
       )
       
       # Execution Statistics
       execution_count = fields.Integer(
           'Times Executed',
           readonly=True,
           default=0
       )
       
       success_count = fields.Integer(
           'Successful Executions',
           readonly=True,
       )
       
       failure_count = fields.Integer(
           'Failed Executions',
           readonly=True
       )
       
       success_rate = fields.Float(
           'Success Rate %',
           compute='_compute_success_rate'
       )
       
       # Exception Handling
       exception_type = fields.Selection([
           ('stockout', 'Stock Out'),
           ('price_change', 'Price Change'),
           ('credit_hold', 'Credit Hold'),
           ('delivery_issue', 'Delivery Issue'),
           ('system_error', 'System Error'),
           ('manual_hold', 'Manual Hold')
       ], string='Exception Type')
       
       exception_message = fields.Text('Exception Details')
       
       exception_resolution = fields.Selection([
           ('auto_retry', 'Auto Retry'),
           ('manual_intervention', 'Manual Intervention'),
           ('skip_once', 'Skip Once'),
           ('cancel_order', 'Cancel Order')
       ], string='Resolution Strategy', default='auto_retry')
       
       # Notification Settings
       notify_on_execution = fields.Boolean(
           'Notify on Execution',
           default=True
       )
       
       notify_on_exception = fields.Boolean(
           'Notify on Exception',
           default=True
       )
       
       notify_emails = fields.Char(
           'Notification Emails',
           help='Comma-separated email addresses for notifications'
       )
       
       # Validity Period
       start_date = fields.Date(
           'Start Date',
           required=True,
           default=fields.Date.context_today
       )
       
       end_date = fields.Date(
           'End Date',
           help='Leave empty for ongoing standing order'
       )
       
       # Line Items
       line_ids = fields.One2many(
           'b2b.standing.order.line',
           'standing_order_id',
           'Order Lines'
       )
       
       # Metadata
       created_by = fields.Many2one(
           'res.users',
           'Created By',
           default=lambda self: self.env.user
       )
       
       approved_by = fields.Many2one(
           'res.users',
           'Approved By',
           readonly=True
       )
       
       approval_date = fields.Datetime('Approval Date')
       
       notes = fields.Text('Internal Notes')
       
       # Computed Methods
       @api.depends('line_ids', 'line_ids.price_subtotal')
       def _compute_totals(self):
           for order in self:
               order.total_amount = sum(line.price_subtotal for line in order.line_ids)
       
       @api.depends('success_count', 'execution_count')
       def _compute_success_rate(self):
           for order in self:
               if order.execution_count > 0:
                   order.success_rate = (order.success_count / order.execution_count) * 100
               else:
                   order.success_rate = 0.0
       
       @api.depends('frequency', 'execution_day', 'execution_time', 'last_execution_date')
       def _compute_next_execution(self):
           for order in self:
               order.next_execution_date = order._calculate_next_execution()
       
       def _calculate_next_execution(self):
           """Calculate the next execution datetime based on frequency."""
           if self.state != 'active':
               return False
           
           now = fields.Datetime.now()
           last_exec = self.last_execution_date or self.start_date
           
           if self.frequency == 'daily':
               next_date = last_exec + timedelta(days=1)
           elif self.frequency == 'weekly':
               days_ahead = self.execution_day - last_exec.weekday()
               if days_ahead <= 0:
                   days_ahead += 7
               next_date = last_exec + timedelta(days=days_ahead)
           elif self.frequency == 'bi_weekly':
               days_ahead = self.execution_day - last_exec.weekday()
               if days_ahead <= 0:
                   days_ahead += 14
               else:
                   days_ahead += 7
               next_date = last_exec + timedelta(days=days_ahead)
           elif self.frequency == 'monthly':
               next_date = last_exec + relativedelta(months=1)
               next_date = next_date.replace(day=min(self.execution_day, 
                   monthrange(next_date.year, next_date.month)[1]))
           else:
               return False
           
           # Combine with execution time
           hour = int(self.execution_time)
           minute = int((self.execution_time - hour) * 60)
           return next_date.replace(hour=hour, minute=minute, second=0)
   ```

2. **Standing Order Line Model (2 hours)**
   ```python
   # Standing Order Line Model
   # File: models/b2b_standing_order_line.py
   
   class B2BStandingOrderLine(models.Model):
       _name = 'b2b.standing.order.line'
       _description = 'B2B Standing Order Line'
       
       standing_order_id = fields.Many2one(
           'b2b.standing.order',
           'Standing Order',
           required=True,
           ondelete='cascade'
       )
       
       sequence = fields.Integer('Sequence', default=10)
       
       product_id = fields.Many2one(
           'product.product',
           'Product',
           required=True,
           domain=[('sale_ok', '=', True), ('type', 'in', ['product', 'consu'])]
       )
       
       product_uom_qty = fields.Float(
           'Quantity',
           required=True,
           default=1.0
       )
       
       product_uom = fields.Many2one(
           'uom.uom',
           'Unit of Measure',
           required=True,
           related='product_id.uom_id',
           store=True,
           readonly=True
       )
       
       price_unit = fields.Float(
           'Unit Price',
           digits='Product Price'
       )
       
       price_subtotal = fields.Float(
           'Subtotal',
           compute='_compute_price_subtotal',
           store=True
       )
       
       discount = fields.Float(
           'Discount (%)',
           digits='Discount',
       )
       
       tax_ids = fields.Many2many(
           'account.tax',
           string='Taxes',
           domain=['|', ('active', '=', False), ('active', '=', True)]
       )
       
       # Advanced Line Configuration
       allow_substitute = fields.Boolean(
           'Allow Substitute',
           help='Allow substitution if product is out of stock',
           default=False
       )
       
       substitute_product_id = fields.Many2one(
           'product.product',
           'Substitute Product',
           domain=[('sale_ok', '=', True)]
       )
       
       min_qty_threshold = fields.Float(
           'Minimum Quantity Threshold',
           help='Alert if available stock falls below this quantity'
       )
       
       notes = fields.Char('Line Notes')
       
       # Execution Tracking
       last_executed_qty = fields.Float('Last Executed Qty')
       last_execution_date = fields.Datetime('Last Execution Date')
       
       @api.depends('product_uom_qty', 'price_unit', 'discount')
       def _compute_price_subtotal(self):
           for line in self:
               subtotal = line.product_uom_qty * line.price_unit
               if line.discount:
                   subtotal *= (1 - line.discount / 100)
               line.price_subtotal = subtotal
       
       @api.onchange('product_id')
       def _onchange_product_id(self):
           if self.product_id:
               self.price_unit = self.product_id.lst_price
   ```

**Afternoon Tasks (4 hours):**

3. **Frequency Calculation Engine (2 hours)**
   ```python
   # File: models/b2b_frequency_calculator.py
   
   class B2BFrequencyCalculator(models.Model):
       _name = 'b2b.frequency.calculator'
       _description = 'Standing Order Frequency Calculator'
       
       def calculate_next_occurrences(self, standing_order, count=10):
           """
           Calculate next N occurrences for a standing order.
           Used for forecasting and calendar views.
           """
           occurrences = []
           current_date = standing_order.next_execution_date or fields.Datetime.now()
           
           for _ in range(count):
               next_date = self._get_next_date(
                   current_date,
                   standing_order.frequency,
                   standing_order.execution_day
               )
               
               if standing_order.end_date and next_date.date() > standing_order.end_date:
                   break
               
               # Apply execution time
               hour = int(standing_order.execution_time)
               minute = int((standing_order.execution_time - hour) * 60)
               next_date = next_date.replace(hour=hour, minute=minute)
               
               occurrences.append({
                   'date': next_date,
                   'estimated_amount': standing_order.total_amount,
                   'products': [line.product_id.name for line in standing_order.line_ids]
               })
               
               current_date = next_date
           
           return occurrences
       
       def _get_next_date(self, from_date, frequency, execution_day):
           """Calculate next date based on frequency."""
           if frequency == 'daily':
               return from_date + timedelta(days=1)
           elif frequency == 'weekly':
               return self._get_next_weekly_date(from_date, execution_day)
           elif frequency == 'bi_weekly':
               return self._get_next_biweekly_date(from_date, execution_day)
           elif frequency == 'monthly':
               return self._get_next_monthly_date(from_date, execution_day)
           elif frequency == 'quarterly':
               return from_date + relativedelta(months=3)
           return from_date
       
       def _get_next_weekly_date(self, from_date, target_day):
           """Calculate next occurrence for weekly frequency."""
           days_ahead = target_day - from_date.weekday()
           if days_ahead <= 0:
               days_ahead += 7
           return from_date + timedelta(days=days_ahead)
       
       def _get_next_biweekly_date(self, from_date, target_day):
           """Calculate next occurrence for bi-weekly frequency."""
           days_ahead = target_day - from_date.weekday()
           if days_ahead <= 0:
               days_ahead += 14
           else:
               days_ahead += 7
           return from_date + timedelta(days=days_ahead)
       
       def _get_next_monthly_date(self, from_date, target_day):
           """Calculate next occurrence for monthly frequency."""
           next_month = from_date + relativedelta(months=1)
           last_day = monthrange(next_month.year, next_month.month)[1]
           day = min(target_day, last_day)
           return next_month.replace(day=day)
   ```

4. **Exception Handler Framework (2 hours)**
   - Design exception categories and handling strategies
   - Create exception logging mechanism
   - Implement notification triggers for exceptions

**Deliverables by End of Day:**
- Complete standing order model (1,000+ lines)
- Standing order line model
- Frequency calculation engine
- Exception handler base class

#### Developer 3 (Frontend Dev) - UI Component Library & Foundation

**Morning Tasks (4 hours):**

1. **Standing Order UI Component Design (2 hours)**
   - Create wireframes for standing order configuration
   - Design calendar view for execution preview
   - Define form layouts for standing order creation
   - Create component hierarchy diagram

2. **Base Vue Components Setup (2 hours)**
   ```javascript
   // Base components for B2B features
   // File: src/components/b2b/common/B2BBaseCard.vue
   
   <template>
     <div class="b2b-base-card" :class="cardClasses">
       <div class="card-header" v-if="$slots.header || title">
         <div class="header-content">
           <slot name="header">
             <h3 class="card-title">{{ title }}</h3>
             <span v-if="subtitle" class="card-subtitle">{{ subtitle }}</span>
           </slot>
         </div>
         <div class="header-actions" v-if="$slots.actions">
           <slot name="actions"></slot>
         </div>
       </div>
       
       <div class="card-body" :class="bodyClasses">
         <slot></slot>
       </div>
       
       <div class="card-footer" v-if="$slots.footer">
         <slot name="footer"></slot>
       </div>
       
       <div v-if="loading" class="card-overlay">
         <B2BLoadingSpinner :size="'large'" />
       </div>
     </div>
   </template>
   
   <script>
   export default {
     name: 'B2BBaseCard',
     props: {
       title: String,
       subtitle: String,
       loading: Boolean,
       variant: {
         type: String,
         default: 'default',
         validator: (v) => ['default', 'elevated', 'outlined'].includes(v)
       },
       padding: {
         type: String,
         default: 'medium',
         validator: (v) => ['none', 'small', 'medium', 'large'].includes(v)
       }
     },
     computed: {
       cardClasses() {
         return [
           `card-variant-${this.variant}`,
           { 'card-loading': this.loading }
         ];
       },
       bodyClasses() {
         return `padding-${this.padding}`;
       }
     }
   };
   </script>
   
   <style scoped>
   .b2b-base-card {
     background: #ffffff;
     border-radius: 8px;
     position: relative;
   }
   .card-variant-elevated {
     box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
   }
   .card-variant-outlined {
     border: 1px solid #e0e0e0;
   }
   .card-header {
     display: flex;
     justify-content: space-between;
     align-items: center;
     padding: 16px 20px;
     border-bottom: 1px solid #f0f0f0;
   }
   .card-title {
     margin: 0;
     font-size: 18px;
     font-weight: 600;
     color: #1a1a1a;
   }
   .card-overlay {
     position: absolute;
     inset: 0;
     background: rgba(255, 255, 255, 0.8);
     display: flex;
     align-items: center;
     justify-content: center;
     border-radius: 8px;
   }
   </style>
   ```

**Afternoon Tasks (4 hours):**

3. **Frequency Selector Component (2 hours)**
   ```javascript
   // Frequency selector with advanced options
   // File: src/components/b2b/standing-orders/FrequencySelector.vue
   
   <template>
     <div class="frequency-selector">
       <B2BSegmentedControl
         v-model="localFrequency"
         :options="frequencyOptions"
         @change="onFrequencyChange"
       />
       
       <div class="frequency-details" v-if="showDetails">
         <!-- Daily options -->
         <div v-if="localFrequency === 'daily'" class="detail-section">
           <B2BTimePicker
             v-model="executionTime"
             label="Execution Time"
             :min-time="'00:00'"
             :max-time="'23:59'"
           />
           <B2BCheckbox
             v-model="excludeWeekends"
             label="Exclude Weekends"
           />
         </div>
         
         <!-- Weekly options -->
         <div v-if="localFrequency === 'weekly'" class="detail-section">
           <B2BDaySelector
             v-model="executionDay"
             :mode="'single'"
             label="Day of Week"
           />
           <B2BTimePicker
             v-model="executionTime"
             label="Execution Time"
           />
         </div>
         
         <!-- Monthly options -->
         <div v-if="localFrequency === 'monthly'" class="detail-section">
           <B2BDayOfMonthSelector
             v-model="executionDay"
             label="Day of Month"
           />
           <B2BTimePicker
             v-model="executionTime"
             label="Execution Time"
           />
         </div>
         
         <!-- Bi-weekly options -->
         <div v-if="localFrequency === 'bi_weekly'" class="detail-section">
           <B2BDaySelector
             v-model="executionDay"
             :mode="'single'"
             label="Day of Week"
           />
           <B2BSelect
             v-model="startWeek"
             :options="weekOptions"
             label="Start Week"
           />
           <B2BTimePicker
             v-model="executionTime"
             label="Execution Time"
           />
         </div>
         
         <!-- Preview calendar -->
         <div class="execution-preview">
           <h4>Next 5 Executions</h4>
           <B2BMiniCalendar
             :events="executionPreview"
             :highlight-dates="previewDates"
           />
         </div>
       </div>
     </div>
   </template>
   
   <script>
   export default {
     name: 'FrequencySelector',
     props: {
       value: {
         type: Object,
         default: () => ({
           frequency: 'weekly',
           executionDay: 1,
           executionTime: '06:00',
           excludeWeekends: false
         })
       }
     },
     data() {
       return {
         frequencyOptions: [
           { value: 'daily', label: 'Daily', icon: 'Calendar' },
           { value: 'weekly', label: 'Weekly', icon: 'CalendarWeek' },
           { value: 'bi_weekly', label: 'Bi-Weekly', icon: 'Calendar2Week' },
           { value: 'monthly', label: 'Monthly', icon: 'CalendarMonth' }
         ],
         weekOptions: [
           { value: 'odd', label: 'Odd Weeks' },
           { value: 'even', label: 'Even Weeks' }
         ]
       };
     },
     computed: {
       localFrequency: {
         get() { return this.value.frequency; },
         set(val) { this.updateValue('frequency', val); }
       },
       executionDay: {
         get() { return this.value.executionDay; },
         set(val) { this.updateValue('executionDay', val); }
       },
       executionTime: {
         get() { return this.value.executionTime; },
         set(val) { this.updateValue('executionTime', val); }
       },
       excludeWeekends: {
         get() { return this.value.excludeWeekends; },
         set(val) { this.updateValue('excludeWeekends', val); }
       },
       showDetails() {
         return ['daily', 'weekly', 'bi_weekly', 'monthly'].includes(this.localFrequency);
       },
       executionPreview() {
         // Calculate preview dates based on configuration
         return this.calculatePreviewDates();
       },
       previewDates() {
         return this.executionPreview.map(e => e.date);
       }
     },
     methods: {
       updateValue(key, value) {
         this.$emit('input', { ...this.value, [key]: value });
       },
       onFrequencyChange(newFreq) {
         // Reset dependent fields
         if (newFreq === 'daily') {
           this.updateValue('executionDay', null);
         } else if (newFreq === 'monthly') {
           this.updateValue('executionDay', 1);
         }
       },
       calculatePreviewDates() {
         // Implementation for preview calculation
         const dates = [];
         // ... calculation logic
         return dates;
       }
     }
   };
   </script>
   ```

4. **Form Validation Utilities (2 hours)**
   ```javascript
   // Validation utilities for B2B forms
   // File: src/utils/b2bValidation.js
   
   export const standingOrderValidators = {
     validateStandingOrder(formData) {
       const errors = {};
       
       // Required fields
       if (!formData.partner_id) {
         errors.partner_id = 'Customer is required';
       }
       
       if (!formData.line_ids || formData.line_ids.length === 0) {
         errors.line_ids = 'At least one product is required';
       }
       
       // Line item validation
       if (formData.line_ids) {
         const lineErrors = formData.line_ids.map((line, index) => {
           const lineErr = {};
           if (!line.product_id) {
             lineErr.product_id = 'Product is required';
           }
           if (!line.product_uom_qty || line.product_uom_qty <= 0) {
             lineErr.product_uom_qty = 'Quantity must be greater than 0';
           }
           return Object.keys(lineErr).length > 0 ? { index, errors: lineErr } : null;
         }).filter(Boolean);
         
         if (lineErrors.length > 0) {
           errors.line_details = lineErrors;
         }
       }
       
       // Date validation
       if (formData.end_date && formData.start_date) {
         if (new Date(formData.end_date) <= new Date(formData.start_date)) {
           errors.end_date = 'End date must be after start date';
         }
       }
       
       // Frequency validation
       if (formData.frequency === 'monthly') {
         if (!formData.execution_day || formData.execution_day < 1 || formData.execution_day > 31) {
           errors.execution_day = 'Valid day of month (1-31) is required';
         }
       }
       
       return {
         isValid: Object.keys(errors).length === 0,
         errors
       };
     }
   };
   ```

**Deliverables by End of Day:**
- UI component library foundation
- Frequency selector component
- Form validation utilities
- Wireframe documents

### 3.2 Day 312 - Standing Order Engine Implementation

#### Developer 1 (Lead Dev) - Core Engine Implementation

**Morning Tasks (4 hours):**

1. **Standing Order Generation Engine (4 hours)**
   ```python
   # Standing Order Generation Engine
   # File: models/b2b_standing_order_generator.py
   
   class B2BStandingOrderGenerator(models.Model):
       _name = 'b2b.standing.order.generator'
       _description = 'Standing Order Generation Engine'
       
       @api.model
       def generate_orders(self, batch_size=100):
           """
           Main entry point for standing order generation.
           Processes all active standing orders scheduled for today.
           """
           execution_start = fields.Datetime.now()
           _logger.info(f"Starting standing order generation at {execution_start}")
           
           # Get orders scheduled for execution
           orders_to_process = self._get_orders_for_execution(batch_size)
           
           results = {
               'processed': 0,
               'created': 0,
               'failed': 0,
               'skipped': 0,
               'errors': []
           }
           
           for standing_order in orders_to_process:
               try:
                   result = self._process_single_order(standing_order)
                   results['processed'] += 1
                   
                   if result['status'] == 'created':
                       results['created'] += 1
                   elif result['status'] == 'skipped':
                       results['skipped'] += 1
                   elif result['status'] == 'failed':
                       results['failed'] += 1
                       results['errors'].append({
                           'order': standing_order.name,
                           'error': result['error']
                       })
               
               except Exception as e:
                   results['failed'] += 1
                   results['errors'].append({
                       'order': standing_order.name,
                       'error': str(e)
                   })
                   _logger.error(f"Critical error processing {standing_order.name}: {str(e)}")
           
           execution_end = fields.Datetime.now()
           duration = (execution_end - execution_start).total_seconds()
           
           _logger.info(
               f"Standing order generation completed in {duration}s: "
               f"{results['created']} created, {results['failed']} failed, "
               f"{results['skipped']} skipped"
           )
           
           return results
       
       def _get_orders_for_execution(self, batch_size):
           """Get standing orders scheduled for execution."""
           now = fields.Datetime.now()
           today_start = now.replace(hour=0, minute=0, second=0)
           today_end = now.replace(hour=23, minute=59, second=59)
           
           domain = [
               ('state', '=', 'active'),
               ('next_execution_date', '>=', today_start),
               ('next_execution_date', '<=', today_end),
               '|', ('end_date', '=', False), ('end_date', '>=', fields.Date.today())
           ]
           
           return self.env['b2b.standing.order'].search(domain, limit=batch_size)
       
       def _process_single_order(self, standing_order):
           """Process a single standing order."""
           self.ensure_one()
           
           # Check for conflicts
           conflict_check = self._check_conflicts(standing_order)
           if conflict_check['has_conflict']:
               return self._handle_conflict(standing_order, conflict_check)
           
           # Validate order can be created
           validation = self._validate_order_creation(standing_order)
           if not validation['valid']:
               return {
                   'status': 'failed',
                   'error': validation['error'],
                   'error_type': validation.get('error_type', 'validation')
               }
           
           # Create the order
           try:
               sale_order = self._create_sale_order(standing_order)
               
               # Update standing order tracking
               standing_order.write({
                   'last_execution_date': fields.Datetime.now(),
                   'execution_count': standing_order.execution_count + 1
               })
               
               # Send notification
               if standing_order.notify_on_execution:
                   self._send_execution_notification(standing_order, sale_order)
               
               return {'status': 'created', 'order_id': sale_order.id}
               
           except Exception as e:
               # Handle exception with retry logic
               return self._handle_execution_exception(standing_order, e)
       
       def _validate_order_creation(self, standing_order):
           """Validate that standing order can be converted to sale order."""
           errors = []
           
           # Check customer is still active
           if standing_order.partner_id.active == False:
               errors.append("Customer account is inactive")
           
           # Check credit status
           credit_engine = self.env['b2b.credit.engine']
           credit_status = credit_engine.check_credit(
               partner_id=standing_order.partner_id.id,
               order_amount=standing_order.total_amount
           )
           
           if credit_status['status'] == 'blocked':
               errors.append(f"Credit hold: {credit_status['reason']}")
           
           # Check product availability
           for line in standing_order.line_ids:
               product = line.product_id
               if product.type == 'product':
                   qty_available = product.with_context(
                       warehouse=standing_order.warehouse_id.id
                   ).qty_available
                   
                   if qty_available < line.product_uom_qty and not line.allow_substitute:
                       errors.append(f"Insufficient stock for {product.name}")
           
           # Check for duplicate order (already created today)
           existing_order = self.env['sale.order'].search([
               ('origin', '=', f'Standing Order: {standing_order.name}'),
               ('date_order', '>=', fields.Date.today())
           ], limit=1)
           
           if existing_order:
               return {
                   'valid': False,
                   'error': 'Order already created today',
                   'error_type': 'duplicate'
               }
           
           return {
               'valid': len(errors) == 0,
               'error': '; '.join(errors) if errors else None
           }
       
       def _create_sale_order(self, standing_order):
           """Create sale order from standing order template."""
           # Prepare order values
           order_vals = {
               'partner_id': standing_order.partner_id.id,
               'partner_invoice_id': standing_order.partner_id.id,
               'partner_shipping_id': standing_order.delivery_address_id.id or standing_order.partner_id.id,
               'warehouse_id': standing_order.warehouse_id.id,
               'pricelist_id': standing_order.pricelist_id.id,
               'payment_term_id': standing_order.payment_term_id.id,
               'incoterm': standing_order.incoterm_id.id,
               'origin': f'Standing Order: {standing_order.name}',
               'client_order_ref': standing_order.name,
               'b2b_standing_order_id': standing_order.id,
               'order_line': []
           }
           
           # Build order lines
           for line in standing_order.line_ids:
               product = line.product_id
               
               # Check for substitution if needed
               if product.type == 'product':
                   qty_available = product.with_context(
                       warehouse=standing_order.warehouse_id.id
                   ).qty_available
                   
                   if qty_available < line.product_uom_qty and line.allow_substitute and line.substitute_product_id:
                       product = line.substitute_product_id
               
               # Get current price
               price = self._get_product_price(
                   product,
                   line.product_uom_qty,
                   standing_order.partner_id,
                   standing_order.pricelist_id
               )
               
               line_vals = {
                   'product_id': product.id,
                   'product_uom_qty': line.product_uom_qty,
                   'product_uom': line.product_uom.id,
                   'price_unit': price,
                   'discount': line.discount,
                   'tax_ids': [(6, 0, line.tax_ids.ids)],
                   'name': line.notes or product.name
               }
               
               order_vals['order_line'].append((0, 0, line_vals))
           
           # Create the order
           sale_order = self.env['sale.order'].create(order_vals)
           
           # Confirm the order if auto-approval is enabled
           if standing_order.partner_id.b2b_auto_confirm_standing_orders:
               sale_order.action_confirm()
           
           return sale_order
       
       def _get_product_price(self, product, qty, partner, pricelist):
           """Get current product price considering contract pricing."""
           pricing_engine = self.env['b2b.pricing.engine']
           
           return pricing_engine.get_price(
               product_id=product.id,
               partner_id=partner.id,
               pricelist_id=pricelist.id,
               quantity=qty
           )
       
       def _check_conflicts(self, standing_order):
           """Check for conflicts with other standing orders."""
           conflicts = []
           
           # Check for overlapping product lines from same customer
           other_orders = self.env['b2b.standing.order'].search([
               ('id', '!=', standing_order.id),
               ('partner_id', '=', standing_order.partner_id.id),
               ('state', '=', 'active'),
               ('next_execution_date', '>=', fields.Datetime.now().replace(hour=0, minute=0))
           ])
           
           for other in other_orders:
               overlap_products = set()
               for line1 in standing_order.line_ids:
                   for line2 in other.line_ids:
                       if line1.product_id == line2.product_id:
                           overlap_products.add(line1.product_id.name)
               
               if overlap_products:
                   conflicts.append({
                       'order': other.name,
                       'products': list(overlap_products)
                   })
           
           return {
               'has_conflict': len(conflicts) > 0,
               'conflicts': conflicts
           }
       
       def _handle_conflict(self, standing_order, conflict_info):
           """Handle standing order conflict based on resolution strategy."""
           strategy = standing_order.exception_resolution or 'auto_retry'
           
           if strategy == 'auto_retry':
               # Defer execution by 1 hour
               standing_order.write({
                   'next_execution_date': fields.Datetime.now() + timedelta(hours=1)
               })
               return {'status': 'skipped', 'reason': 'deferred_due_to_conflict'}
           
           elif strategy == 'cancel_order':
               standing_order.write({
                   'state': 'exception',
                   'exception_type': 'conflict',
                   'exception_message': f"Conflict with orders: {conflict_info['conflicts']}"
               })
               return {'status': 'failed', 'error': 'Cancelled due to conflict'}
           
           else:
               # Proceed anyway with warning
               _logger.warning(f"Proceeding with conflict for {standing_order.name}")
               return self._process_single_order(standing_order)
       
       def _handle_execution_exception(self, standing_order, exception):
           """Handle exceptions during order creation."""
           error_message = str(exception)
           error_type = 'system_error'
           
           # Categorize error
           if 'insufficient' in error_message.lower():
               error_type = 'stockout'
           elif 'credit' in error_message.lower():
               error_type = 'credit_hold'
           elif 'price' in error_message.lower():
               error_type = 'price_change'
           
           # Update standing order with exception
           standing_order.write({
               'state': 'exception',
               'exception_type': error_type,
               'exception_message': error_message,
               'failure_count': standing_order.failure_count + 1
           })
           
           # Send notification
           if standing_order.notify_on_exception:
               self._send_exception_notification(standing_order, error_message)
           
           return {'status': 'failed', 'error': error_message, 'error_type': error_type}
       
       def _send_execution_notification(self, standing_order, sale_order):
           """Send notification of successful execution."""
           template = self.env.ref('b2b_features.standing_order_execution_email')
           
           if template:
               template.send_mail(
                   standing_order.id,
                   force_send=True,
                   email_values={
                       'body_html': template.body_html.replace(
                           '${sale_order_number}', sale_order.name
                       ).replace(
                           '${sale_order_amount}', str(sale_order.amount_total)
                       )
                   }
               )
       
       def _send_exception_notification(self, standing_order, error_message):
           """Send notification of execution failure."""
           template = self.env.ref('b2b_features.standing_order_exception_email')
           
           if template:
               template.send_mail(
                   standing_order.id,
                   force_send=True,
                   email_values={
                       'body_html': template.body_html.replace(
                           '${error_message}', error_message
                       )
                   }
               )
   ```

**Afternoon Tasks (4 hours):**

2. **Cron Job Configuration (2 hours)**
   ```xml
   <!-- Standing Order Execution Cron Jobs -->
   <!-- File: data/b2b_standing_order_cron.xml -->
   
   <?xml version="1.0" encoding="UTF-8"?>
   <odoo>
       <data noupdate="1">
           
           <!-- Main Standing Order Generation Cron - Runs every hour -->
           <record id="ir_cron_standing_order_generation" model="ir.cron">
               <field name="name">B2B: Generate Standing Orders</field>
               <field name="model_id" ref="model_b2b_standing_order_generator"/>
               <field name="state">code</field>
               <field name="code">
                   model.generate_orders(batch_size=100)
               </field>
               <field name="interval_number">1</field>
               <field name="interval_type">hours</field>
               <field name="numbercall">-1</field>
               <field name="doall" eval="False"/>
               <field name="active" eval="True"/>
           </record>
           
           <!-- Standing Order Exception Monitor - Runs every 30 minutes -->
           <record id="ir_cron_standing_order_exception_monitor" model="ir.cron">
               <field name="name">B2B: Monitor Standing Order Exceptions</field>
               <field name="model_id" ref="model_b2b_standing_order"/>
               <field name="state">code</field>
               <field name="code">
                   model.process_pending_exceptions()
               </field>
               <field name="interval_number">30</field>
               <field name="interval_type">minutes</field>
               <field name="numbercall">-1</field>
               <field name="doall" eval="False"/>
               <field name="active" eval="True"/>
           </record>
           
           <!-- Standing Order Next Execution Update - Runs daily at midnight -->
           <record id="ir_cron_standing_order_next_execution" model="ir.cron">
               <field name="name">B2B: Update Standing Order Next Execution Dates</field>
               <field name="model_id" ref="model_b2b_standing_order"/>
               <field name="state">code</field>
               <field name="code">
                   model._cron_update_next_executions()
               </field>
               <field name="interval_number">1</field>
               <field name="interval_type">days</field>
               <field name="nextcall" eval="(DateTime.now().replace(hour=0, minute=5)).strftime('%Y-%m-%d %H:%M:%S')"/>
               <field name="numbercall">-1</field>
               <field name="doall" eval="False"/>
               <field name="active" eval="True"/>
           </record>
           
           <!-- Standing Order Expiration Check - Runs daily -->
           <record id="ir_cron_standing_order_expiration" model="ir.cron">
               <field name="name">B2B: Check Standing Order Expirations</field>
               <field name="model_id" ref="model_b2b_standing_order"/>
               <field name="state">code</field>
               <field name="code">
                   model._cron_check_expirations()
               </field>
               <field name="interval_number">1</field>
               <field name="interval_type">days</field>
               <field name="nextcall" eval="(DateTime.now().replace(hour=1, minute=0)).strftime('%Y-%m-%d %H:%M:%S')"/>
               <field name="numbercall">-1</field>
               <field name="doall" eval="False"/>
               <field name="active" eval="True"/>
           </record>
           
           <!-- Daily Summary Report - Runs once daily at 8 AM -->
           <record id="ir_cron_standing_order_daily_summary" model="ir.cron">
               <field name="name">B2B: Standing Order Daily Summary Report</field>
               <field name="model_id" ref="model_b2b_standing_order_generator"/>
               <field name="state">code</field>
               <field name="code">
                   model.send_daily_summary()
               </field>
               <field name="interval_number">1</field>
               <field name="interval_type">days</field>
               <field name="nextcall" eval="(DateTime.now().replace(hour=8, minute=0)).strftime('%Y-%m-%d %H:%M:%S')"/>
               <field name="numbercall">-1</field>
               <field name="doall" eval="False"/>
               <field name="active" eval="True"/>
           </record>
           
       </data>
   </odoo>
   ```

3. **Pause/Resume Functionality (2 hours)**
   ```python
   # Pause/Resume functionality for standing orders
   # File: models/b2b_standing_order.py (additional methods)
   
   class B2BStandingOrder(models.Model):
       _inherit = 'b2b.standing.order'
       
       # Pause/Resume tracking fields
       is_paused = fields.Boolean('Is Paused', compute='_compute_is_paused', store=True)
       pause_count = fields.Integer('Pause Count', default=0)
       total_pause_duration = fields.Float('Total Pause Duration (Hours)')
       
       pause_history_ids = fields.One2many(
           'b2b.standing.order.pause.history',
           'standing_order_id',
           'Pause History'
       )
       
       @api.depends('state')
       def _compute_is_paused(self):
           for order in self:
               order.is_paused = order.state == 'paused'
       
       def action_pause(self, reason=None, resume_date=None):
           """Pause standing order execution."""
           self.ensure_one()
           
           if self.state not in ['active', 'draft']:
               raise UserError(_("Only active or draft standing orders can be paused."))
           
           # Create pause history record
           pause_record = self.env['b2b.standing.order.pause.history'].create({
               'standing_order_id': self.id,
               'paused_by': self.env.uid,
               'pause_reason': reason,
               'paused_date': fields.Datetime.now(),
               'scheduled_resume_date': resume_date
           })
           
           # Update standing order
           self.write({
               'state': 'paused',
               'pause_count': self.pause_count + 1
           })
           
           # Schedule automatic resume if specified
           if resume_date:
               self.env['b2b.scheduled.action'].create({
                   'model': 'b2b.standing.order',
                   'res_id': self.id,
                   'method': 'action_resume',
                   'scheduled_date': resume_date
               })
           
           # Send notification
           self._send_pause_notification(reason)
           
           return pause_record
       
       def action_resume(self, reason=None):
           """Resume standing order execution."""
           self.ensure_one()
           
           if self.state != 'paused':
               raise UserError(_("Standing order is not paused."))
           
           # Find and close active pause record
           active_pause = self.pause_history_ids.filtered(
               lambda p: not p.resumed_date
           )
           
           if active_pause:
               pause_duration = (fields.Datetime.now() - active_pause.paused_date).total_seconds() / 3600
               active_pause.write({
                   'resumed_date': fields.Datetime.now(),
                   'resumed_by': self.env.uid,
                   'resume_reason': reason,
                   'actual_duration_hours': pause_duration
               })
               
               self.total_pause_duration += pause_duration
           
           # Update standing order
           self.write({
               'state': 'active'
           })
           
           # Recalculate next execution date
           self._compute_next_execution()
           
           # Send notification
           self._send_resume_notification(reason)
           
           return True
       
       def action_cancel(self, reason=None):
           """Cancel standing order permanently."""
           self.ensure_one()
           
           if self.state == 'cancelled':
               return True
           
           if self.state == 'paused':
               # Close any active pause record
               active_pause = self.pause_history_ids.filtered(lambda p: not p.resumed_date)
               if active_pause:
                   active_pause.write({
                       'resumed_date': fields.Datetime.now(),
                       'resume_reason': 'Order cancelled: ' + (reason or 'No reason provided')
                   })
           
           self.write({
               'state': 'cancelled'
           })
           
           # Send cancellation notification
           self._send_cancellation_notification(reason)
           
           return True
       
       def _send_pause_notification(self, reason):
           """Send notification when order is paused."""
           template = self.env.ref('b2b_features.standing_order_paused_email', raise_if_not_found=False)
           if template:
               template.send_mail(self.id, force_send=True)
       
       def _send_resume_notification(self, reason):
           """Send notification when order is resumed."""
           template = self.env.ref('b2b_features.standing_order_resumed_email', raise_if_not_found=False)
           if template:
               template.send_mail(self.id, force_send=True)
       
       def _send_cancellation_notification(self, reason):
           """Send notification when order is cancelled."""
           template = self.env.ref('b2b_features.standing_order_cancelled_email', raise_if_not_found=False)
           if template:
               template.send_mail(self.id, force_send=True)
   
   
   class B2BStandingOrderPauseHistory(models.Model):
       _name = 'b2b.standing.order.pause.history'
       _description = 'Standing Order Pause History'
       _order = 'paused_date desc'
       
       standing_order_id = fields.Many2one(
           'b2b.standing.order',
           'Standing Order',
           required=True,
           ondelete='cascade'
       )
       
       paused_date = fields.Datetime('Paused Date', required=True)
       resumed_date = fields.Datetime('Resumed Date')
       
       paused_by = fields.Many2one('res.users', 'Paused By')
       resumed_by = fields.Many2one('res.users', 'Resumed By')
       
       pause_reason = fields.Text('Pause Reason')
       resume_reason = fields.Text('Resume Reason')
       
       scheduled_resume_date = fields.Datetime('Scheduled Resume Date')
       actual_duration_hours = fields.Float('Actual Duration (Hours)')
       
       is_auto_resumed = fields.Boolean('Auto Resumed', compute='_compute_is_auto_resumed')
       
       @api.depends('resumed_date', 'scheduled_resume_date')
       def _compute_is_auto_resumed(self):
           for record in self:
               if record.scheduled_resume_date and record.resumed_date:
                   record.is_auto_resumed = abs(
                       (record.resumed_date - record.scheduled_resume_date).total_seconds()
                   ) < 60  # Within 1 minute
               else:
                   record.is_auto_resumed = False
   ```

**Deliverables by End of Day:**
- Complete standing order generation engine
- Cron job configurations
- Pause/resume functionality with history tracking
- Notification system integration


        orders = self.env['sale.order'].search([
            ('partner_id', '=', partner_id),
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', period_start),
            ('date_order', '<=', period_end)
        ])
        
        total = Decimal('0')
        for order in orders:
            total += Decimal(str(order.amount_total))
        
        return total
    
    def _get_sales_for_volume(self, volume: Decimal, program) -> Decimal:
        """Estimate sales amount for a given volume."""
        # Simplified - actual implementation would use average price
        return volume * Decimal('10')  # Assumed average price
    
    def _build_breakdown(self, program, actual_volume: Decimal,
                         target_volume: Decimal, incentive: Decimal) -> Dict:
        """Build detailed calculation breakdown."""
        return {
            'program_name': program.name,
            'calculation_method': program.calculation_method,
            'target_volume': float(target_volume),
            'actual_volume': float(actual_volume),
            'attainment_pct': float((actual_volume / target_volume * 100) if target_volume > 0 else 0),
            'calculated_incentive': float(incentive),
            'tier_configuration': program.tier_configuration
        }


class IncentiveProjectionEngine:
    """
    Projects future incentive earnings based on current performance trends.
    """
    
    def project_annual_incentive(self, partner_id: int, program_id: int) -> Dict:
        """
        Project annual incentive based on YTD performance.
        """
        calculator = VolumeIncentiveCalculator(self.env)
        today = date.today()
        
        # Get YTD performance
        ytd_start = date(today.year, 1, 1)
        ytd_result = calculator.calculate_incentive(
            partner_id, program_id, ytd_start, today
        )
        
        # Calculate daily run rate
        days_elapsed = (today - ytd_start).days
        if days_elapsed == 0:
            days_elapsed = 1
        
        daily_volume = ytd_result.actual_volume / days_elapsed
        daily_incentive = ytd_result.base_incentive / days_elapsed
        
        # Project for full year
        days_in_year = 365
        projected_volume = daily_volume * days_in_year
        projected_incentive = daily_incentive * days_in_year
        
        # Calculate attainment scenarios
        target = ytd_result.target_volume * (days_in_year / days_elapsed)
        
        return {
            'ytd_actual_volume': float(ytd_result.actual_volume),
            'ytd_incentive_earned': float(ytd_result.base_incentive),
            'ytd_attainment': float(ytd_result.attainment_percentage),
            'projected_annual_volume': float(projected_volume),
            'projected_annual_incentive': float(projected_incentive),
            'projected_attainment': float((projected_volume / target * 100) if target > 0 else 0),
            'scenarios': {
                'conservative_80pct': {
                    'projected_volume': float(projected_volume * Decimal('0.8')),
                    'projected_incentive': float(projected_incentive * Decimal('0.8'))
                },
                'optimistic_120pct': {
                    'projected_volume': float(projected_volume * Decimal('1.2')),
                    'projected_incentive': float(projected_incentive * Decimal('1.2'))
                }
            }
        }
```

### 5.5 Contract Price Resolver

```python
"""
Contract Price Resolution Engine
Resolves pricing from multiple contract types with priority rules
"""

from datetime import date
from typing import Optional, List, Dict, Tuple
from decimal import Decimal
import logging

_logger = logging.getLogger(__name__)

class ContractPriceResolver:
    """
    Resolves product pricing considering:
    - Active contract pricing agreements
    - Volume tier pricing
    - Promotional pricing
    - Standard pricelist pricing
    - Customer-specific special agreements
    
    Priority (highest to lowest):
    1. Special promotional pricing (limited time)
    2. Volume commitment contract pricing
    3. Standard contract pricing
    4. Customer-specific pricelist
    5. Standard pricelist
    """
    
    PRIORITY_ORDER = [
        'promotional',
        'volume_commitment',
        'contract',
        'customer_pricelist',
        'standard_pricelist'
    ]
    
    def __init__(self, env):
        self.env = env
    
    def get_price(self, product_id: int, partner_id: int,
                  quantity: float = 1.0, date: Optional[date] = None,
                  uom_id: Optional[int] = None) -> Dict:
        """
        Get the best applicable price for a product.
        
        Returns:
            Dictionary containing:
            - price: The resolved price
            - price_origin: Source of the price
            - contract_id: Associated contract if applicable
            - original_price: List price before discounts
            - discount: Applied discount percentage
            - expires: Price expiration date if applicable
        """
        date = date or date.today()
        product = self.env['product.product'].browse(product_id)
        partner = self.env['res.partner'].browse(partner_id)
        
        result = {
            'price': product.lst_price,
            'price_origin': 'standard',
            'contract_id': None,
            'original_price': product.lst_price,
            'discount': 0.0,
            'expires': None,
            'applied_rules': []
        }
        
        # Try each price source in priority order
        for source in self.PRIORITY_ORDER:
            price_info = self._get_price_from_source(
                source, product_id, partner_id, quantity, date, uom_id
            )
            
            if price_info and price_info['price'] is not None:
                result.update(price_info)
                result['price_origin'] = source
                break
        
        return result
    
    def _get_price_from_source(self, source: str, product_id: int,
                                partner_id: int, quantity: float,
                                date: date, uom_id: Optional[int]) -> Optional[Dict]:
        """Get price from a specific source."""
        method = getattr(self, f'_get_{source}_price', None)
        if method:
            return method(product_id, partner_id, quantity, date, uom_id)
        return None
    
    def _get_promotional_price(self, product_id: int, partner_id: int,
                                quantity: float, date: date, uom_id: Optional[int]) -> Optional[Dict]:
        """Get promotional pricing if available."""
        # Search for active promotions
        promotions = self.env['b2b.promotional.pricing'].search([
            ('product_id', '=', product_id),
            ('partner_id', 'in', [partner_id, False]),  # Partner-specific or global
            ('start_date', '<=', date),
            ('end_date', '>=', date),
            ('state', '=', 'active')
        ], order='priority desc', limit=1)
        
        if promotions:
            promo = promotions[0]
            return {
                'price': promo.promo_price,
                'original_price': promo.original_price,
                'discount': promo.discount_percent,
                'expires': promo.end_date,
                'applied_rules': [f"Promotional: {promo.name}"]
            }
        return None
    
    def _get_contract_price(self, product_id: int, partner_id: int,
                             quantity: float, date: date, uom_id: Optional[int]) -> Optional[Dict]:
        """Get contract pricing if available."""
        # Find active contracts for customer
        contracts = self.env['b2b.contract.pricing'].search([
            ('partner_id', '=', partner_id),
            ('state', '=', 'active'),
            ('start_date', '<=', date),
            '|', ('end_date', '>=', date), ('end_date', '=', False)
        ])
        
        for contract in contracts:
            # Find matching contract line
            contract_lines = self._find_contract_lines(contract, product_id, quantity)
            
            if contract_lines:
                line = contract_lines[0]  # Best matching line
                
                # Check volume commitment progress
                progress = self._check_volume_commitment(contract, line)
                
                price = self._calculate_contract_price(line, quantity, progress)
                
                return {
                    'price': price,
                    'contract_id': contract.id,
                    'original_price': self.env['product.product'].browse(product_id).lst_price,
                    'discount': line.discount_percent,
                    'expires': contract.end_date,
                    'applied_rules': [
                        f"Contract: {contract.name}",
                        f"Volume progress: {progress['percent']:.1f}%"
                    ]
                }
        
        return None
    
    def _find_contract_lines(self, contract, product_id: int,
                              quantity: float) -> List:
        """Find contract lines matching product and quantity."""
        lines = contract.line_ids.filtered(lambda l:
            (l.product_id.id == product_id or
             l.product_category_id.id == self.env['product.product'].browse(product_id).categ_id.id or
             l.apply_to_all_products) and
            (not l.valid_from or l.valid_from <= date.today()) and
            (not l.valid_to or l.valid_to >= date.today())
        )
        
        # Sort by specificity (product > category > all)
        lines = lines.sorted(key=lambda l: (
            0 if l.product_id else 1 if l.product_category_id else 2
        ))
        
        return lines
    
    def _check_volume_commitment(self, contract, contract_line) -> Dict:
        """Check progress against volume commitment."""
        if not contract.minimum_commitment:
            return {'percent': 100, 'on_track': True}
        
        # Calculate volume used in contract period
        orders = self.env['sale.order'].search([
            ('partner_id', '=', contract.partner_id.id),
            ('b2b_contract_pricing_id', '=', contract.id),
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', contract.start_date)
        ])
        
        volume_used = sum(
            sum(line.product_uom_qty for line in order.order_line
                if self._line_matches_contract(line, contract_line))
            for order in orders
        )
        
        percent = (volume_used / contract.minimum_commitment) * 100
        
        # Check if on track based on time elapsed
        days_elapsed = (date.today() - contract.start_date).days
        days_total = (contract.end_date - contract.start_date).days if contract.end_date else 365
        expected_percent = (days_elapsed / days_total) * 100 if days_total > 0 else 100
        
        return {
            'volume_used': volume_used,
            'volume_commitment': contract.minimum_commitment,
            'percent': percent,
            'expected_percent': expected_percent,
            'on_track': percent >= expected_percent * 0.9  # 10% tolerance
        }
    
    def _line_matches_contract(self, order_line, contract_line) -> bool:
        """Check if order line matches contract line criteria."""
        if contract_line.product_id:
            return order_line.product_id == contract_line.product_id
        elif contract_line.product_category_id:
            return order_line.product_id.categ_id == contract_line.product_category_id
        return contract_line.apply_to_all_products
    
    def _calculate_contract_price(self, contract_line, quantity: float,
                                   progress: Dict) -> float:
        """Calculate price considering volume tiers."""
        # Check for volume tier pricing
        if quantity >= (contract_line.tier_3_min_qty or float('inf')):
            return contract_line.tier_3_price or contract_line.price_unit
        elif quantity >= (contract_line.tier_2_min_qty or float('inf')):
            return contract_line.tier_2_price or contract_line.price_unit
        elif quantity >= (contract_line.tier_1_min_qty or 0):
            return contract_line.tier_1_price or contract_line.price_unit
        
        return contract_line.price_unit
    
    def _get_customer_pricelist_price(self, product_id: int, partner_id: int,
                                       quantity: float, date: date, uom_id: Optional[int]) -> Optional[Dict]:
        """Get price from customer's pricelist."""
        partner = self.env['res.partner'].browse(partner_id)
        
        if not partner.property_product_pricelist:
            return None
        
        pricelist = partner.property_product_pricelist
        product = self.env['product.product'].browse(product_id)
        
        # Get price from pricelist
        price = pricelist.get_product_price(
            product, quantity, partner, date, uom_id
        )
        
        if price:
            return {
                'price': price,
                'original_price': product.lst_price,
                'discount': ((product.lst_price - price) / product.lst_price * 100) if product.lst_price else 0,
                'applied_rules': [f"Pricelist: {pricelist.name}"]
            }
        
        return None
    
    def _get_standard_pricelist_price(self, product_id: int, partner_id: int,
                                       quantity: float, date: date, uom_id: Optional[int]) -> Optional[Dict]:
        """Get price from default pricelist."""
        # Get company's default pricelist
        default_pricelist = self.env['product.pricelist'].search([
            '|', ('company_id', '=', self.env.company.id),
                 ('company_id', '=', False)
        ], limit=1)
        
        if default_pricelist:
            product = self.env['product.product'].browse(product_id)
            price = default_pricelist.get_product_price(
                product, quantity, None, date, uom_id
            )
            
            return {
                'price': price,
                'original_price': product.lst_price,
                'discount': 0,
                'applied_rules': [f"Default: {default_pricelist.name}"]
            }
        
        return None


class PriceChangeMonitor:
    """
    Monitors price changes and notifies affected standing orders and contracts.
    """
    
    def __init__(self, env):
        self.env = env
    
    def check_price_changes(self, product_ids: Optional[List[int]] = None):
        """
        Check for recent price changes and identify affected records.
        """
        # Get products with recent price changes
        domain = [('write_date', '>=', __import__('datetime').datetime.now() - __import__('datetime').timedelta(days=1))]
        if product_ids:
            domain.append(('id', 'in', product_ids))
        
        products = self.env['product.product'].search(domain)
        
        for product in products:
            # Find affected standing orders
            affected_standing = self.env['b2b.standing.order.line'].search([
                ('product_id', '=', product.id),
                ('standing_order_id.state', '=', 'active')
            ]).mapped('standing_order_id')
            
            # Find affected contracts
            affected_contracts = self.env['b2b.contract.pricing.line'].search([
                ('product_id', '=', product.id),
                ('contract_id.state', '=', 'active')
            ]).mapped('contract_id')
            
            if affected_standing or affected_contracts:
                self._notify_price_change(product, affected_standing, affected_contracts)
    
    def _notify_price_change(self, product, standing_orders, contracts):
        """Send notifications about price change."""
        # Implementation would send emails/notifications
        _logger.info(
            f"Price change for {product.name} affects "
            f"{len(standing_orders)} standing orders and "
            f"{len(contracts)} contracts"
        )
```

---

## 6. API Specifications

### 6.1 Standing Order Management APIs

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy B2B Standing Order API
  version: 1.0.0
  description: API for managing standing orders in the Smart Dairy B2B Portal

paths:
  /api/v1/b2b/standing-orders:
    get:
      summary: List standing orders
      parameters:
        - name: partner_id
          in: query
          schema:
            type: integer
        - name: state
          in: query
          schema:
            type: string
            enum: [draft, active, paused, exception, completed, cancelled]
        - name: frequency
          in: query
          schema:
            type: string
            enum: [daily, weekly, bi_weekly, monthly, quarterly]
        - name: next_execution_from
          in: query
          schema:
            type: string
            format: date-time
        - name: next_execution_to
          in: query
          schema:
            type: string
            format: date-time
        - name: page
          in: query
          schema:
            type: integer
            default: 1
        - name: per_page
          in: query
          schema:
            type: integer
            default: 25
      responses:
        200:
          description: List of standing orders
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/StandingOrder'
                  pagination:
                    $ref: '#/components/schemas/Pagination'
    
    post:
      summary: Create new standing order
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/StandingOrderCreate'
      responses:
        201:
          description: Standing order created
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/StandingOrder'
        400:
          description: Validation error
        403:
          description: Permission denied

  /api/v1/b2b/standing-orders/{id}:
    get:
      summary: Get standing order details
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: Standing order details
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/StandingOrderDetail'
        404:
          description: Standing order not found
    
    put:
      summary: Update standing order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/StandingOrderUpdate'
      responses:
        200:
          description: Standing order updated
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/StandingOrder'
    
    delete:
      summary: Cancel standing order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                reason:
                  type: string
      responses:
        200:
          description: Standing order cancelled

  /api/v1/b2b/standing-orders/{id}/pause:
    post:
      summary: Pause standing order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                reason:
                  type: string
                resume_date:
                  type: string
                  format: date-time
      responses:
        200:
          description: Standing order paused

  /api/v1/b2b/standing-orders/{id}/resume:
    post:
      summary: Resume standing order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                reason:
                  type: string
      responses:
        200:
          description: Standing order resumed

  /api/v1/b2b/standing-orders/{id}/preview:
    get:
      summary: Get execution preview for standing order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
        - name: count
          in: query
          schema:
            type: integer
            default: 5
      responses:
        200:
          description: Execution preview
          content:
            application/json:
              schema:
                type: object
                properties:
                  executions:
                    type: array
                    items:
                      type: object
                      properties:
                        date:
                          type: string
                          format: date-time
                        estimated_amount:
                          type: number
                        products:
                          type: array
                          items:
                            type: string

  /api/v1/b2b/standing-orders/{id}/history:
    get:
      summary: Get execution history
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
        - name: limit
          in: query
          schema:
            type: integer
            default: 50
      responses:
        200:
          description: Execution history

  /api/v1/b2b/standing-orders/{id}/duplicate:
    post:
      summary: Duplicate standing order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        201:
          description: Standing order duplicated
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/StandingOrder'

components:
  schemas:
    StandingOrder:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        partner_id:
          type: integer
        partner_name:
          type: string
        frequency:
          type: string
        execution_day:
          type: integer
        execution_time:
          type: number
        next_execution_date:
          type: string
          format: date-time
        state:
          type: string
        priority:
          type: string
        total_amount:
          type: number
        line_count:
          type: integer
        success_rate:
          type: number

    StandingOrderCreate:
      type: object
      required:
        - partner_id
        - frequency
        - line_ids
      properties:
        partner_id:
          type: integer
        contact_id:
          type: integer
        frequency:
          type: string
          enum: [daily, weekly, bi_weekly, monthly, quarterly, custom]
        execution_day:
          type: integer
        execution_time:
          type: number
          default: 6.0
        pricelist_id:
          type: integer
        warehouse_id:
          type: integer
        delivery_address_id:
          type: integer
        start_date:
          type: string
          format: date
        end_date:
          type: string
          format: date
        line_ids:
          type: array
          items:
            type: object
            properties:
              product_id:
                type: integer
              product_uom_qty:
                type: number
              price_unit:
                type: number
              discount:
                type: number
              allow_substitute:
                type: boolean

    StandingOrderDetail:
      allOf:
        - $ref: '#/components/schemas/StandingOrder'
        - type: object
          properties:
            line_ids:
              type: array
              items:
                $ref: '#/components/schemas/StandingOrderLine'
            execution_count:
              type: integer
            success_count:
              type: integer
            failure_count:
              type: integer
            exception_type:
              type: string
            exception_message:
              type: string
            notes:
              type: string

    StandingOrderLine:
      type: object
      properties:
        id:
          type: integer
        sequence:
          type: integer
        product_id:
          type: integer
        product_name:
          type: string
        product_uom_qty:
          type: number
        product_uom:
          type: string
        price_unit:
          type: number
        discount:
          type: number
        price_subtotal:
          type: number
        allow_substitute:
          type: boolean
        substitute_product_id:
          type: integer

    Pagination:
      type: object
      properties:
        page:
          type: integer
        per_page:
          type: integer
        total:
          type: integer
        total_pages:
          type: integer
```

### 6.2 Approval Workflow APIs

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy B2B Approval Workflow API
  version: 1.0.0
  description: API for managing approval workflows

paths:
  /api/v1/b2b/approvals/pending:
    get:
      summary: Get pending approvals for current user
      parameters:
        - name: status
          in: query
          schema:
            type: string
            enum: [pending, in_progress, escalated]
        - name: priority
          in: query
          schema:
            type: string
        - name: page
          in: query
          schema:
            type: integer
            default: 1
      responses:
        200:
          description: List of pending approvals
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/ApprovalInstance'
                  statistics:
                    type: object
                    properties:
                      total_pending:
                        type: integer
                      urgent:
                        type: integer
                      average_wait_time:
                        type: number

  /api/v1/b2b/approvals/{id}:
    get:
      summary: Get approval instance details
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: Approval instance details
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ApprovalInstanceDetail'

  /api/v1/b2b/approvals/{id}/approve:
    post:
      summary: Approve order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                notes:
                  type: string
      responses:
        200:
          description: Order approved
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                  new_state:
                    type: string
                  requires_additional_approval:
                    type: boolean
                  next_approvers:
                    type: array
                    items:
                      type: object

  /api/v1/b2b/approvals/{id}/reject:
    post:
      summary: Reject order
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required:
                - reason
              properties:
                reason:
                  type: string
                  minLength: 10
                notes:
                  type: string
      responses:
        200:
          description: Order rejected
        400:
          description: Validation error - reason required

  /api/v1/b2b/approvals/{id}/delegate:
    post:
      summary: Delegate approval to another user
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required:
                - delegated_to
              properties:
                delegated_to:
                  type: integer
                  description: User ID to delegate to
                reason:
                  type: string
      responses:
        200:
          description: Approval delegated
        403:
          description: Delegation not allowed

  /api/v1/b2b/approvals/{id}/history:
    get:
      summary: Get approval history
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
      responses:
        200:
          description: Approval history
          content:
            application/json:
              schema:
                type: array
                items:
                  $ref: '#/components/schemas/ApprovalAction'

  /api/v1/b2b/approvals/statistics:
    get:
      summary: Get approval statistics
      parameters:
        - name: period
          in: query
          schema:
            type: string
            enum: [today, week, month, quarter]
            default: month
      responses:
        200:
          description: Approval statistics
          content:
            application/json:
              schema:
                type: object
                properties:
                  submitted:
                    type: integer
                  approved:
                    type: integer
                  rejected:
                    type: integer
                  average_approval_time_hours:
                    type: number
                  approval_rate_percent:
                    type: number

components:
  schemas:
    ApprovalInstance:
      type: object
      properties:
        id:
          type: integer
        name:
          type: string
        order_id:
          type: integer
        order_number:
          type: string
        customer_name:
          type: string
        amount:
          type: number
        currency:
          type: string
        state:
          type: string
        current_level:
          type: integer
        total_levels:
          type: integer
        submitted_date:
          type: string
          format: date-time
        waiting_hours:
          type: number
        priority:
          type: string
        can_approve:
          type: boolean
        can_reject:
          type: boolean
        can_delegate:
          type: boolean

    ApprovalInstanceDetail:
      allOf:
        - $ref: '#/components/schemas/ApprovalInstance'
        - type: object
          properties:
            order_details:
              type: object
            approval_chain:
              type: array
              items:
                type: object
                properties:
                  level:
                    type: integer
                  name:
                    type: string
                  approvers:
                    type: array
                    items:
                      type: object
                  status:
                    type: string
            current_approvers:
              type: array
              items:
                type: object
            comments:
              type: array
              items:
                type: object

    ApprovalAction:
      type: object
      properties:
        id:
          type: integer
        user_id:
          type: integer
        user_name:
          type: string
        action:
          type: string
        action_date:
          type: string
          format: date-time
        notes:
          type: string
        delegated_to_id:
          type: integer
        delegated_to_name:
          type: string
```

### 6.3 Reporting APIs

```yaml
openapi: 3.0.0
info:
  title: Smart Dairy B2B Reporting API
  version: 1.0.0
  description: API for B2B reporting and analytics

paths:
  /api/v1/b2b/reports/aging:
    get:
      summary: Get AR aging report
      parameters:
        - name: as_of_date
          in: query
          schema:
            type: string
            format: date
        - name: partner_ids
          in: query
          schema:
            type: string
            description: Comma-separated list of partner IDs
        - name: bucket_type
          in: query
          schema:
            type: string
            enum: [standard, extended, custom]
            default: standard
      responses:
        200:
          description: Aging report
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgingReport'

  /api/v1/b2b/reports/standing-order-summary:
    get:
      summary: Get standing order summary report
      parameters:
        - name: date_from
          in: query
          schema:
            type: string
            format: date
        - name: date_to
          in: query
          schema:
            type: string
            format: date
        - name: group_by
          in: query
          schema:
            type: string
            enum: [customer, frequency, product, month]
      responses:
        200:
          description: Standing order summary

  /api/v1/b2b/reports/b2b-sales:
    get:
      summary: Get B2B sales report
      parameters:
        - name: date_from
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: date_to
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: partner_id
          in: query
          schema:
            type: integer
        - name: product_id
          in: query
          schema:
            type: integer
        - name: sales_rep_id
          in: query
          schema:
            type: integer
      responses:
        200:
          description: B2B sales report

  /api/v1/b2b/reports/incentive-statement:
    get:
      summary: Get incentive statement
      parameters:
        - name: partner_id
          in: query
          required: true
          schema:
            type: integer
        - name: program_id
          in: query
          schema:
            type: integer
        - name: period_start
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: period_end
          in: query
          required: true
          schema:
            type: string
            format: date
      responses:
        200:
          description: Incentive statement

  /api/v1/b2b/dashboard/kpis:
    get:
      summary: Get dashboard KPIs
      parameters:
        - name: period
          in: query
          schema:
            type: string
            enum: [today, yesterday, week, month, quarter, year]
            default: month
      responses:
        200:
          description: Dashboard KPIs
          content:
            application/json:
              schema:
                type: object
                properties:
                  revenue:
                    type: object
                    properties:
                      value:
                        type: number
                      change_percent:
                        type: number
                      trend:
                        type: string
                  orders:
                    type: object
                  customers:
                    type: object
                  average_order_value:
                    type: object

  /api/v1/b2b/dashboard/charts:
    get:
      summary: Get dashboard chart data
      parameters:
        - name: chart_type
          in: query
          required: true
          schema:
            type: string
            enum: [sales_trend, top_customers, product_mix, aging_distribution]
        - name: date_from
          in: query
          schema:
            type: string
            format: date
        - name: date_to
          in: query
          schema:
            type: string
            format: date
      responses:
        200:
          description: Chart data

components:
  schemas:
    AgingReport:
      type: object
      properties:
        report_date:
          type: string
          format: date
        total_customers:
          type: integer
        grand_totals:
          type: object
          properties:
            current:
              type: number
            1_30:
              type: number
            31_60:
              type: number
            61_90:
              type: number
            90_plus:
              type: number
            total:
              type: number
        customers:
          type: array
          items:
            type: object
            properties:
              partner_id:
                type: integer
              partner_name:
                type: string
              current:
                type: number
              overdue_1_30:
                type: number
              overdue_31_60:
                type: number
              overdue_61_90:
                type: number
              overdue_90_plus:
                type: number
              total:
                type: number
              credit_limit:
                type: number
              available_credit:
                type: number
```

### 6.4 Dashboard Data APIs

```yaml
# Dashboard Widget Data APIs
paths:
  /api/v1/b2b/dashboard/widgets/standing-orders:
    get:
      summary: Standing orders widget data
      responses:
        200:
          content:
            application/json:
              schema:
                type: object
                properties:
                  active_count:
                    type: integer
                  paused_count:
                    type: integer
                  exception_count:
                    type: integer
                  today_executions:
                    type: integer
                  success_rate:
                    type: number

  /api/v1/b2b/dashboard/widgets/approval-queue:
    get:
      summary: Approval queue widget data
      responses:
        200:
          content:
            application/json:
              schema:
                type: object
                properties:
                  pending_count:
                    type: integer
                  urgent_count:
                    type: integer
                  avg_wait_time_hours:
                    type: number
                  my_pending_count:
                    type: integer

  /api/v1/b2b/dashboard/widgets/credit-overview:
    get:
      summary: Credit overview widget data
      responses:
        200:
          content:
            application/json:
              schema:
                type: object
                properties:
                  total_exposure:
                    type: number
                  available_credit:
                    type: number
                  overdue_60_plus:
                    type: number
                  customers_on_hold:
                    type: integer

  /api/v1/b2b/dashboard/widgets/top-customers:
    get:
      summary: Top customers widget data
      parameters:
        - name: limit
          in: query
          schema:
            type: integer
            default: 10
      responses:
        200:
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    partner_id:
                      type: integer
                    partner_name:
                      type: string
                    ytd_revenue:
                      type: number
                    growth_percent:
                      type: number
```

---

## 7. Frontend Implementation

### 7.1 Standing Order Configuration UI

```vue
<!-- StandingOrderForm.vue -->
<template>
  <B2BFormContainer :title="formTitle" :loading="saving">
    <form @submit.prevent="saveStandingOrder">
      <!-- Customer Selection -->
      <B2BFormSection title="Customer Information" icon="User">
        <B2BFormRow>
          <B2BFormField label="Customer" required :error="errors.partner_id">
            <B2BPartnerSearch
              v-model="form.partner_id"
              :filters="{ is_company: true, customer_rank: '>0' }"
              @select="onCustomerSelect"
            />
          </B2BFormField>
          
          <B2BFormField label="Primary Contact">
            <B2BPartnerSearch
              v-model="form.contact_id"
              :parent-id="form.partner_id"
              :filters="{ type: 'contact' }"
            />
          </B2BFormField>
        </B2BFormRow>
        
        <B2BFormRow>
          <B2BFormField label="Delivery Address">
            <B2BPartnerSearch
              v-model="form.delivery_address_id"
              :parent-id="form.partner_id"
              :filters="{ type: 'delivery' }"
            />
          </B2BFormField>
        </B2BFormRow>
      </B2BFormSection>
      
      <!-- Schedule Configuration -->
      <B2BFormSection title="Schedule Configuration" icon="Clock">
        <FrequencySelector v-model="frequencyConfig" />
        
        <B2BFormRow>
          <B2BFormField label="Start Date" required :error="errors.start_date">
            <B2BDatePicker v-model="form.start_date" :min="today" />
          </B2BFormField>
          
          <B2BFormField label="End Date (Optional)">
            <B2BDatePicker v-model="form.end_date" :min="form.start_date" />
          </B2BFormField>
        </B2BFormRow>
        
        <B2BFormRow>
          <B2BFormField label="Priority">
            <B2BSelect
              v-model="form.priority"
              :options="priorityOptions"
            />
          </B2BFormField>
        </B2BFormRow>
      </B2BFormSection>
      
      <!-- Order Lines -->
      <B2BFormSection title="Order Products" icon="Package" required>
        <StandingOrderLines
          v-model="form.line_ids"
          :pricelist-id="form.pricelist_id"
          :errors="errors.line_ids"
          @update-total="onTotalUpdate"
        />
        
        <div class="order-total">
          <span>Estimated Order Total:</span>
          <strong>{{ formatCurrency(calculatedTotal) }}</strong>
        </div>
      </B2BFormSection>
      
      <!-- Pricing & Terms -->
      <B2BFormSection title="Pricing & Terms" icon="DollarSign">
        <B2BFormRow>
          <B2BFormField label="Pricelist" required>
            <B2BPricelistSelector
              v-model="form.pricelist_id"
              :partner-id="form.partner_id"
            />
          </B2BFormField>
          
          <B2BFormField label="Payment Terms">
            <B2BSelect
              v-model="form.payment_term_id"
              :options="paymentTermOptions"
            />
          </B2BFormField>
        </B2BFormRow>
      </B2BFormSection>
      
      <!-- Notification Settings -->
      <B2BFormSection title="Notifications" icon="Bell">
        <B2BCheckbox
          v-model="form.notify_on_execution"
          label="Notify on successful execution"
        />
        <B2BCheckbox
          v-model="form.notify_on_exception"
          label="Notify on execution failure"
        />
        <B2BFormField label="Notification Emails">
          <B2BInput
            v-model="form.notify_emails"
            placeholder="email1@example.com, email2@example.com"
          />
        </B2BFormField>
      </B2BFormSection>
      
      <!-- Execution Preview -->
      <B2BFormSection title="Execution Preview" icon="Calendar">
        <ExecutionPreviewCalendar
          :frequency="frequencyConfig"
          :start-date="form.start_date"
          :end-date="form.end_date"
          :lines="form.line_ids"
        />
      </B2BFormSection>
      
      <!-- Form Actions -->
      <B2BFormActions>
        <B2BButton
          type="button"
          variant="secondary"
          @click="$emit('cancel')"
        >
          Cancel
        </B2BButton>
        <B2BButton
          type="button"
          variant="secondary"
          :disabled="!isValid"
          @click="saveAsDraft"
        >
          Save as Draft
        </B2BButton>
        <B2BButton
          type="submit"
          variant="primary"
          :loading="saving"
          :disabled="!isValid"
        >
          {{ isEditing ? 'Update' : 'Create' }} Standing Order
        </B2BButton>
      </B2BFormActions>
    </form>
  </B2BFormContainer>
</template>

<script>
export default {
  name: 'StandingOrderForm',
  props: {
    initialData: Object,
    isEditing: Boolean
  },
  data() {
    return {
      form: {
        partner_id: null,
        contact_id: null,
        delivery_address_id: null,
        frequency: 'weekly',
        execution_day: 1,
        execution_time: 6.0,
        start_date: null,
        end_date: null,
        priority: '1',
        pricelist_id: null,
        payment_term_id: null,
        warehouse_id: null,
        line_ids: [],
        notify_on_execution: true,
        notify_on_exception: true,
        notify_emails: ''
      },
      frequencyConfig: {
        frequency: 'weekly',
        executionDay: 1,
        executionTime: '06:00'
      },
      errors: {},
      saving: false,
      calculatedTotal: 0,
      priorityOptions: [
        { value: '0', label: 'Low' },
        { value: '1', label: 'Normal' },
        { value: '2', label: 'High' },
        { value: '3', label: 'Critical' }
      ]
    };
  },
  computed: {
    formTitle() {
      return this.isEditing ? 'Edit Standing Order' : 'Create Standing Order';
    },
    today() {
      return new Date().toISOString().split('T')[0];
    },
    isValid() {
      return this.form.partner_id && 
             this.form.line_ids.length > 0 &&
             this.form.start_date;
    }
  },
  mounted() {
    if (this.initialData) {
      this.form = { ...this.form, ...this.initialData };
    }
  },
  methods: {
    onCustomerSelect(customer) {
      this.form.pricelist_id = customer.property_product_pricelist?.id;
      this.form.payment_term_id = customer.property_payment_term?.id;
      this.form.warehouse_id = customer.default_warehouse?.id;
    },
    onTotalUpdate(total) {
      this.calculatedTotal = total;
    },
    async saveStandingOrder() {
      this.saving = true;
      try {
        const payload = this.preparePayload();
        
        if (this.isEditing) {
          await this.$api.standingOrders.update(this.form.id, payload);
        } else {
          await this.$api.standingOrders.create(payload);
        }
        
        this.$emit('save', this.form);
        this.$toast.success('Standing order saved successfully');
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
        this.$toast.error('Failed to save standing order');
      } finally {
        this.saving = false;
      }
    },
    preparePayload() {
      return {
        ...this.form,
        frequency: this.frequencyConfig.frequency,
        execution_day: this.frequencyConfig.executionDay,
        execution_time: this.parseTime(this.frequencyConfig.executionTime)
      };
    },
    parseTime(timeStr) {
      const [hours, minutes] = timeStr.split(':').map(Number);
      return hours + minutes / 60;
    },
    saveAsDraft() {
      this.form.state = 'draft';
      this.saveStandingOrder();
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount);
    }
  }
};
</script>
```

### 7.2 Report Builder Components

```vue
<!-- ReportBuilder.vue -->
<template>
  <div class="report-builder">
    <B2BSidebarLayout>
      <template #sidebar>
        <ReportFieldPanel
          :available-fields="availableFields"
          @add-field="addField"
        />
      </template>
      
      <template #content>
        <div class="report-canvas">
          <!-- Report Configuration -->
          <B2BCard class="report-config">
            <B2BFormRow>
              <B2BInput
                v-model="reportConfig.name"
                label="Report Name"
                placeholder="My Custom Report"
              />
              <B2BSelect
                v-model="reportConfig.base_model"
                label="Data Source"
                :options="dataSourceOptions"
                @change="onDataSourceChange"
              />
            </B2BFormRow>
          </B2BCard>
          
          <!-- Field Configuration Area -->
          <B2BCard class="fields-area">
            <h4>Selected Fields</h4>
            <draggable
              v-model="selectedFields"
              class="fields-list"
              handle=".drag-handle"
            >
              <ReportFieldConfig
                v-for="(field, index) in selectedFields"
                :key="field.id"
                :field="field"
                :index="index"
                @remove="removeField(index)"
                @update="updateField(index, $event)"
              />
            </draggable>
          </B2BCard>
          
          <!-- Filters -->
          <B2BCard class="filters-area">
            <div class="section-header">
              <h4>Filters</h4>
              <B2BButton
                size="sm"
                icon="Plus"
                @click="addFilter"
              >
                Add Filter
              </B2BButton>
            </div>
            <ReportFilterBuilder
              v-model="reportConfig.filters"
              :available-fields="availableFields"
            />
          </B2BCard>
          
          <!-- Grouping & Aggregation -->
          <B2BCard class="grouping-area">
            <h4>Grouping</h4>
            <B2BSelect
              v-model="reportConfig.group_by"
              :options="groupableFields"
              multiple
              placeholder="Select fields to group by"
            />
          </B2BCard>
          
          <!-- Chart Configuration -->
          <B2BCard v-if="reportConfig.include_chart" class="chart-config">
            <h4>Chart Configuration</h4>
            <B2BFormRow>
              <B2BSelect
                v-model="reportConfig.chart_type"
                label="Chart Type"
                :options="chartTypeOptions"
              />
              <B2BSelect
                v-model="reportConfig.chart_x_axis"
                label="X-Axis"
                :options="selectedFields"
              />
              <B2BSelect
                v-model="reportConfig.chart_y_axis"
                label="Y-Axis"
                :options="numericFields"
              />
            </B2BFormRow>
          </B2BCard>
          
          <!-- Preview & Actions -->
          <div class="report-actions">
            <B2BButton
              variant="secondary"
              icon="Eye"
              :loading="previewLoading"
              @click="previewReport"
            >
              Preview
            </B2BButton>
            <B2BButton
              variant="primary"
              icon="Save"
              @click="saveReport"
            >
              Save Report
            </B2BButton>
            <B2BButton
              variant="primary"
              icon="Download"
              @click="exportReport"
            >
              Export
            </B2BButton>
            <B2BButton
              variant="secondary"
              icon="Clock"
              @click="scheduleReport"
            >
              Schedule
            </B2BButton>
          </div>
        </div>
        
        <!-- Preview Panel -->
        <B2BSlidePanel v-model="showPreview">
          <ReportPreview
            :data="previewData"
            :columns="previewColumns"
            :chart-config="reportConfig.chart_type ? reportConfig : null"
          />
        </B2BSlidePanel>
      </template>
    </B2BSidebarLayout>
  </div>
</template>

<script>
import draggable from 'vuedraggable';

export default {
  name: 'ReportBuilder',
  components: { draggable },
  data() {
    return {
      reportConfig: {
        name: '',
        base_model: 'sale.order',
        fields: [],
        filters: [],
        group_by: [],
        sort_by: [],
        include_chart: false,
        chart_type: null,
        chart_x_axis: null,
        chart_y_axis: null
      },
      selectedFields: [],
      availableFields: [],
      showPreview: false,
      previewData: [],
      previewLoading: false,
      dataSourceOptions: [
        { value: 'sale.order', label: 'Sales Orders' },
        { value: 'b2b.standing.order', label: 'Standing Orders' },
        { value: 'account.move', label: 'Invoices' },
        { value: 'b2b.approval.instance', label: 'Approval History' }
      ],
      chartTypeOptions: [
        { value: 'bar', label: 'Bar Chart' },
        { value: 'line', label: 'Line Chart' },
        { value: 'pie', label: 'Pie Chart' },
        { value: 'table', label: 'Data Table' }
      ]
    };
  },
  computed: {
    groupableFields() {
      return this.selectedFields.filter(f => f.groupable);
    },
    numericFields() {
      return this.selectedFields.filter(f => ['integer', 'float', 'monetary'].includes(f.type));
    },
    previewColumns() {
      return this.selectedFields.map(f => ({
        key: f.name,
        label: f.label,
        type: f.type,
        aggregate: f.aggregate
      }));
    }
  },
  methods: {
    async onDataSourceChange(model) {
      // Load available fields for selected model
      const response = await this.$api.reports.getFields(model);
      this.availableFields = response.data;
    },
    addField(field) {
      this.selectedFields.push({
        ...field,
        id: Date.now(),
        aggregate: null,
        sort: null,
        visible: true
      });
    },
    removeField(index) {
      this.selectedFields.splice(index, 1);
    },
    updateField(index, updates) {
      this.$set(this.selectedFields, index, {
        ...this.selectedFields[index],
        ...updates
      });
    },
    async previewReport() {
      this.previewLoading = true;
      try {
        const response = await this.$api.reports.preview({
          config: this.reportConfig,
          fields: this.selectedFields,
          limit: 100
        });
        this.previewData = response.data;
        this.showPreview = true;
      } catch (error) {
        this.$toast.error('Failed to generate preview');
      } finally {
        this.previewLoading = false;
      }
    },
    async saveReport() {
      try {
        await this.$api.reports.save({
          ...this.reportConfig,
          fields: this.selectedFields
        });
        this.$toast.success('Report saved successfully');
      } catch (error) {
        this.$toast.error('Failed to save report');
      }
    },
    exportReport() {
      this.$api.reports.export({
        config: this.reportConfig,
        fields: this.selectedFields,
        format: 'xlsx'
      });
    },
    scheduleReport() {
      this.$modal.open(ReportScheduleModal, {
        reportConfig: this.reportConfig
      });
    }
  }
};
</script>
```

### 7.3 Analytics Dashboard

```vue
<!-- B2BAnalyticsDashboard.vue -->
<template>
  <div class="b2b-analytics-dashboard">
    <B2BPageHeader
      title="B2B Analytics"
      subtitle="Insights and performance metrics"
    >
      <template #actions>
        <B2BDateRangePicker
          v-model="dateRange"
          @change="refreshDashboard"
        />
        <B2BButton icon="Download" @click="exportDashboard">
          Export
        </B2BButton>
      </template>
    </B2BPageHeader>
    
    <!-- KPI Cards -->
    <div class="kpi-grid">
      <KpiCard
        v-for="kpi in kpis"
        :key="kpi.id"
        :title="kpi.title"
        :value="kpi.value"
        :change="kpi.change"
        :trend="kpi.trend"
        :icon="kpi.icon"
        :color="kpi.color"
        :sparkline-data="kpi.sparkline"
      />
    </div>
    
    <!-- Charts Grid -->
    <div class="charts-grid">
      <!-- Sales Trend Chart -->
      <B2BCard class="chart-card large">
        <template #header>
          <h3>Sales Trend</h3>
          <B2BSegmentControl
            v-model="salesView"
            :options="['Revenue', 'Volume', 'Orders']"
          />
        </template>
        <LineChart
          :data="salesTrendData"
          :x-axis="dateAxis"
          :series="salesSeries"
        />
      </B2BCard>
      
      <!-- Top Customers -->
      <B2BCard class="chart-card">
        <template #header>
          <h3>Top Customers</h3>
        </template>
        <HorizontalBarChart
          :data="topCustomersData"
          value-label="Revenue"
        />
      </B2BCard>
      
      <!-- Product Mix -->
      <B2BCard class="chart-card">
        <template #header>
          <h3>Product Mix</h3>
        </template>
        <DonutChart
          :data="productMixData"
          :show-legend="true"
        />
      </B2BCard>
      
      <!-- Aging Distribution -->
      <B2BCard class="chart-card">
        <template #header>
          <h3>AR Aging</h3>
        </template>
        <StackedBarChart
          :data="agingData"
          :categories="['Current', '1-30', '31-60', '61-90', '90+']"
        />
      </B2BCard>
      
      <!-- Standing Order Performance -->
      <B2BCard class="chart-card">
        <template #header>
          <h3>Standing Orders</h3>
        </template>
        <GaugeChart
          :value="standingOrderSuccessRate"
          :min="0"
          :max="100"
          label="Success Rate"
        />
      </B2BCard>
      
      <!-- Approval Metrics -->
      <B2BCard class="chart-card">
        <template #header>
          <h3>Approval Metrics</h3>
        </template>
        <div class="approval-metrics">
          <MetricRow
            label="Avg. Approval Time"
            :value="approvalMetrics.avgTime"
            unit="hours"
          />
          <MetricRow
            label="Approval Rate"
            :value="approvalMetrics.approvalRate"
            unit="%"
          />
          <MetricRow
            label="Pending Approvals"
            :value="approvalMetrics.pendingCount"
          />
        </div>
      </B2BCard>
    </div>
    
    <!-- Detailed Tables -->
    <div class="detail-section">
      <B2BTabs v-model="activeTab">
        <B2BTab label="Recent Orders" value="orders">
          <RecentOrdersTable :date-range="dateRange" />
        </B2BTab>
        <B2BTab label="Credit Alerts" value="credit">
          <CreditAlertsTable />
        </B2BTab>
        <B2BTab label="Incentive Tracking" value="incentives">
          <IncentiveTrackingTable :date-range="dateRange" />
        </B2BTab>
      </B2BTabs>
    </div>
  </div>
</template>

<script>
export default {
  name: 'B2BAnalyticsDashboard',
  data() {
    return {
      dateRange: {
        start: this.getStartOfMonth(),
        end: new Date()
      },
      kpis: [],
      salesView: 'Revenue',
      salesTrendData: [],
      topCustomersData: [],
      productMixData: [],
      agingData: [],
      standingOrderSuccessRate: 0,
      approvalMetrics: {},
      activeTab: 'orders'
    };
  },
  mounted() {
    this.refreshDashboard();
  },
  methods: {
    async refreshDashboard() {
      await Promise.all([
        this.loadKPIs(),
        this.loadSalesTrend(),
        this.loadTopCustomers(),
        this.loadProductMix(),
        this.loadAgingData(),
        this.loadStandingOrderMetrics(),
        this.loadApprovalMetrics()
      ]);
    },
    async loadKPIs() {
      const response = await this.$api.dashboard.kpis({
        date_from: this.dateRange.start,
        date_to: this.dateRange.end
      });
      this.kpis = response.data;
    },
    async loadSalesTrend() {
      const response = await this.$api.dashboard.charts({
        chart_type: 'sales_trend',
        date_from: this.dateRange.start,
        date_to: this.dateRange.end,
        metric: this.salesView.toLowerCase()
      });
      this.salesTrendData = response.data;
    },
    async loadTopCustomers() {
      const response = await this.$api.dashboard.charts({
        chart_type: 'top_customers',
        date_from: this.dateRange.start,
        date_to: this.dateRange.end,
        limit: 10
      });
      this.topCustomersData = response.data;
    },
    async loadProductMix() {
      const response = await this.$api.dashboard.charts({
        chart_type: 'product_mix',
        date_from: this.dateRange.start,
        date_to: this.dateRange.end
      });
      this.productMixData = response.data;
    },
    async loadAgingData() {
      const response = await this.$api.reports.aging({
        as_of_date: this.dateRange.end
      });
      this.agingData = this.transformAgingData(response.data);
    },
    async loadStandingOrderMetrics() {
      const response = await this.$api.dashboard.widgets('standing-orders');
      this.standingOrderSuccessRate = response.data.success_rate;
    },
    async loadApprovalMetrics() {
      const response = await this.$api.dashboard.widgets('approval-queue');
      this.approvalMetrics = {
        avgTime: response.data.avg_wait_time_hours,
        approvalRate: 85, // Calculate from data
        pendingCount: response.data.pending_count
      };
    },
    getStartOfMonth() {
      const date = new Date();
      date.setDate(1);
      return date;
    },
    exportDashboard() {
      this.$api.dashboard.export({
        date_from: this.dateRange.start,
        date_to: this.dateRange.end
      });
    }
  }
};
</script>

<style scoped>
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.charts-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

.chart-card.large {
  grid-column: span 2;
  grid-row: span 2;
}

.approval-metrics {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.detail-section {
  margin-top: 2rem;
}
</style>
```

---

## 8. Testing & QA

### 8.1 Test Scenarios for Standing Orders

```python
"""
Comprehensive Test Suite for Standing Order Functionality
"""

import unittest
from datetime import datetime, timedelta, date
from freezegun import freeze_time
from odoo.tests import common, Form
from odoo.exceptions import UserError, ValidationError

class TestStandingOrders(common.TransactionCase):
    """Test cases for standing order functionality."""
    
    def setUp(self):
        super().setUp()
        # Setup test data
        self.partner = self.env['res.partner'].create({
            'name': 'Test B2B Customer',
            'is_company': True,
            'b2b_credit_limit': 50000,
        })
        
        self.product = self.env['product.product'].create({
            'name': 'Test Dairy Product',
            'type': 'product',
            'lst_price': 100.0,
            'standard_price': 50.0
        })
        
        self.warehouse = self.env['stock.warehouse'].search([], limit=1)
        
        self.pricelist = self.env['product.pricelist'].create({
            'name': 'Test Pricelist'
        })
    
    def test_create_standing_order_basic(self):
        """Test basic standing order creation."""
        standing_order = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'weekly',
            'execution_day': 1,  # Monday
            'execution_time': 6.0,
            'start_date': date.today(),
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 10,
                'price_unit': 100.0
            })]
        })
        
        self.assertTrue(standing_order.name.startswith('SO/'))
        self.assertEqual(standing_order.state, 'draft')
        self.assertEqual(standing_order.total_amount, 1000.0)
    
    @freeze_time("2024-01-15 05:00:00")  # Monday
    def test_weekly_execution_schedule(self):
        """Test weekly execution date calculation."""
        standing_order = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'weekly',
            'execution_day': 3,  # Wednesday
            'execution_time': 6.0,
            'start_date': date(2024, 1, 15),
            'state': 'active'
        })
        
        # Next execution should be Wednesday, Jan 17 at 6:00 AM
        next_exec = standing_order.next_execution_date
        self.assertEqual(next_exec.weekday(), 2)  # Wednesday
        self.assertEqual(next_exec.day, 17)
        self.assertEqual(next_exec.hour, 6)
    
    @freeze_time("2024-01-15 06:00:00")
    def test_standing_order_generation(self):
        """Test actual standing order to sale order generation."""
        # Create standing order with inventory
        self.env['stock.quant']._update_available_quantity(
            self.product, self.warehouse.lot_stock_id, 100
        )
        
        standing_order = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'daily',
            'execution_time': 6.0,
            'start_date': date(2024, 1, 15),
            'state': 'active',
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 10,
                'price_unit': 100.0
            })]
        })
        
        # Run generator
        generator = self.env['b2b.standing.order.generator']
        results = generator.generate_orders(batch_size=100)
        
        self.assertEqual(results['created'], 1)
        
        # Verify sale order created
        sale_order = self.env['sale.order'].search([
            ('b2b_standing_order_id', '=', standing_order.id)
        ])
        self.assertEqual(len(sale_order), 1)
        self.assertEqual(sale_order.amount_total, 1000.0)
        
        # Verify standing order updated
        self.assertEqual(standing_order.execution_count, 1)
        self.assertEqual(standing_order.success_count, 1)
    
    def test_conflict_detection(self):
        """Test conflict detection for overlapping standing orders."""
        # Create first standing order
        so1 = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'weekly',
            'execution_day': 1,
            'execution_time': 6.0,
            'start_date': date.today(),
            'state': 'active',
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 50,
            })]
        })
        
        # Create second standing order with same product
        so2 = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'weekly',
            'execution_day': 1,
            'execution_time': 7.0,
            'start_date': date.today(),
            'state': 'active',
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 30,
            })]
        })
        
        # Add limited stock
        self.env['stock.quant']._update_available_quantity(
            self.product, self.warehouse.lot_stock_id, 60
        )
        
        # Check for conflicts
        generator = self.env['b2b.standing.order.generator']
        conflicts = generator._detect_conflicts(so1 | so2)
        
        self.assertTrue(len(conflicts) > 0)
        self.assertEqual(conflicts[0]['type'], 'stock_conflict')
    
    def test_pause_resume_functionality(self):
        """Test pause and resume functionality."""
        standing_order = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'weekly',
            'execution_day': 1,
            'execution_time': 6.0,
            'start_date': date.today(),
            'state': 'active'
        })
        
        # Pause the order
        standing_order.action_pause(reason="Inventory shortage")
        self.assertEqual(standing_order.state, 'paused')
        self.assertEqual(standing_order.pause_count, 1)
        
        # Verify pause history created
        self.assertEqual(len(standing_order.pause_history_ids), 1)
        
        # Resume the order
        standing_order.action_resume(reason="Inventory replenished")
        self.assertEqual(standing_order.state, 'active')
        self.assertTrue(standing_order.pause_history_ids[0].resumed_date)
    
    def test_exception_handling_stockout(self):
        """Test exception handling when product is out of stock."""
        # No inventory
        standing_order = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'daily',
            'execution_time': 6.0,
            'start_date': date.today(),
            'state': 'active',
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 10,
            })]
        })
        
        # Run generator
        generator = self.env['b2b.standing.order.generator']
        results = generator.generate_orders()
        
        # Should fail due to stockout
        self.assertEqual(results['failed'], 1)
        
        # Verify exception recorded
        self.assertEqual(standing_order.state, 'exception')
        self.assertEqual(standing_order.exception_type, 'stockout')
    
    def test_substitution_logic(self):
        """Test product substitution when primary is unavailable."""
        substitute_product = self.env['product.product'].create({
            'name': 'Substitute Product',
            'type': 'product',
            'lst_price': 95.0
        })
        
        # Add stock to substitute only
        self.env['stock.quant']._update_available_quantity(
            substitute_product, self.warehouse.lot_stock_id, 100
        )
        
        standing_order = self.env['b2b.standing.order'].create({
            'partner_id': self.partner.id,
            'pricelist_id': self.pricelist.id,
            'warehouse_id': self.warehouse.id,
            'frequency': 'daily',
            'execution_time': 6.0,
            'start_date': date.today(),
            'state': 'active',
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 10,
                'allow_substitute': True,
                'substitute_product_id': substitute_product.id
            })]
        })
        
        # Run generator
        generator = self.env['b2b.standing.order.generator']
        results = generator.generate_orders()
        
        self.assertEqual(results['created'], 1)
        
        # Verify substitute was used
        sale_order = self.env['sale.order'].search([
            ('b2b_standing_order_id', '=', standing_order.id)
        ])
        self.assertEqual(sale_order.order_line[0].product_id, substitute_product)


class TestStandingOrderPerformance(common.TransactionCase):
    """Performance tests for standing order generation."""
    
    def test_batch_processing_performance(self):
        """Test that 1000 standing orders can be processed within 5 minutes."""
        import time
        
        # Create 1000 test standing orders
        orders = []
        for i in range(1000):
            order = self.env['b2b.standing.order'].create({
                'partner_id': self.partner.id,
                'pricelist_id': self.pricelist.id,
                'warehouse_id': self.warehouse.id,
                'frequency': 'weekly',
                'execution_day': 1,
                'execution_time': 6.0,
                'start_date': date.today(),
                'state': 'active'
            })
            orders.append(order)
        
        # Measure execution time
        start_time = time.time()
        generator = self.env['b2b.standing.order.generator']
        results = generator.generate_orders(batch_size=1000)
        elapsed_time = time.time() - start_time
        
        # Should complete within 300 seconds (5 minutes)
        self.assertLess(elapsed_time, 300)
        self.assertEqual(results['processed'], 1000)
```

### 8.2 Approval Workflow Tests

```python
"""
Approval Workflow Test Suite
"""

class TestApprovalWorkflow(common.TransactionCase):
    """Test cases for approval workflow functionality."""
    
    def setUp(self):
        super().setUp()
        self.approver1 = self.env['res.users'].create({
            'name': 'Approver 1',
            'login': 'approver1@test.com'
        })
        
        self.approver2 = self.env['res.users'].create({
            'name': 'Approver 2',
            'login': 'approver2@test.com'
        })
        
        self.requester = self.env['res.users'].create({
            'name': 'Requester',
            'login': 'requester@test.com'
        })
        
        # Create workflow configuration
        self.workflow = self.env['b2b.approval.workflow.config'].create({
            'name': 'Test Workflow',
            'minimum_order_value': 1000,
            'level_ids': [
                (0, 0, {
                    'name': 'Manager Approval',
                    'sequence': 1,
                    'approval_type': 'user',
                    'user_id': self.approver1.id
                }),
                (0, 0, {
                    'name': 'Director Approval',
                    'sequence': 2,
                    'approval_type': 'user',
                    'user_id': self.approver2.id
                })
            ]
        })
    
    def test_workflow_eligibility(self):
        """Test that workflow applies to correct orders."""
        # Create order below threshold
        small_order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 5,
                'price_unit': 100
            })]
        })
        
        self.assertFalse(self.workflow.is_applicable(small_order))
        
        # Create order above threshold
        large_order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 20,
                'price_unit': 100
            })]
        })
        
        self.assertTrue(self.workflow.is_applicable(large_order))
    
    def test_multi_level_approval(self):
        """Test multi-level approval flow."""
        order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 20,
                'price_unit': 100
            })]
        })
        
        # Submit for approval
        instance = self.env['b2b.approval.instance'].create({
            'order_id': order.id,
            'workflow_config_id': self.workflow.id
        })
        instance.start_approval_process()
        
        # Should be pending level 1 approval
        self.assertEqual(instance.state, 'in_progress')
        self.assertEqual(instance.current_level, 1)
        self.assertIn(self.approver1.id, instance.current_approver_ids.ids)
        
        # First approval
        instance.approve(self.approver1.id, notes="Looks good")
        
        # Should advance to level 2
        self.assertEqual(instance.current_level, 2)
        self.assertIn(self.approver2.id, instance.current_approver_ids.ids)
        
        # Second approval
        instance.approve(self.approver2.id)
        
        # Should be fully approved
        self.assertEqual(instance.state, 'approved')
        self.assertEqual(order.state, 'sale')
    
    def test_rejection_flow(self):
        """Test rejection at first level."""
        order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 20,
                'price_unit': 100
            })]
        })
        
        instance = self.env['b2b.approval.instance'].create({
            'order_id': order.id,
            'workflow_config_id': self.workflow.id
        })
        instance.start_approval_process()
        
        # Reject at first level
        instance.reject(self.approver1.id, reason="Budget exceeded")
        
        self.assertEqual(instance.state, 'rejected')
        self.assertEqual(order.state, 'rejected')
    
    def test_delegation(self):
        """Test approval delegation."""
        delegate_user = self.env['res.users'].create({
            'name': 'Delegate',
            'login': 'delegate@test.com'
        })
        
        order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 20,
                'price_unit': 100
            })]
        })
        
        instance = self.env['b2b.approval.instance'].create({
            'order_id': order.id,
            'workflow_config_id': self.workflow.id
        })
        instance.start_approval_process()
        
        # Delegate to another user
        # ... delegation logic test
        
        self.assertTrue(instance.is_delegated)
        self.assertEqual(instance.delegation_depth, 1)


class TestApprovalSecurity(common.TransactionCase):
    """Security tests for approval workflows."""
    
    def test_unauthorized_approval_rejection(self):
        """Test that unauthorized users cannot approve."""
        unauthorized_user = self.env['res.users'].create({
            'name': 'Unauthorized',
            'login': 'unauthorized@test.com'
        })
        
        instance = self.env['b2b.approval.instance'].create({
            'order_id': self.order.id,
            'workflow_config_id': self.workflow.id,
            'current_approver_ids': [(6, 0, [self.approver1.id])]
        })
        
        with self.assertRaises(UserError):
            instance.approve(unauthorized_user.id)
```

### 8.3 Credit Calculation Validation

```python
"""
Credit Management and Aging Calculation Tests
"""

class TestCreditAgingCalculator(common.TransactionCase):
    """Test cases for credit aging calculations."""
    
    def setUp(self):
        super().setUp()
        self.calculator = self.env['b2b.credit.aging.calculator']
        
        # Create test invoices at different aging stages
        self.create_test_invoices()
    
    def create_test_invoices(self):
        """Create invoices with various due dates for aging testing."""
        today = date.today()
        
        # Current invoice
        self.invoice_current = self.create_invoice(
            amount=1000,
            invoice_date=today - timedelta(days=15),
            due_date=today + timedelta(days=15)
        )
        
        # 15 days overdue (1-30 bucket)
        self.invoice_15_overdue = self.create_invoice(
            amount=2000,
            invoice_date=today - timedelta(days=45),
            due_date=today - timedelta(days=15)
        )
        
        # 45 days overdue (31-60 bucket)
        self.invoice_45_overdue = self.create_invoice(
            amount=3000,
            invoice_date=today - timedelta(days=75),
            due_date=today - timedelta(days=45)
        )
        
        # 75 days overdue (61-90 bucket)
        self.invoice_75_overdue = self.create_invoice(
            amount=4000,
            invoice_date=today - timedelta(days=105),
            due_date=today - timedelta(days=75)
        )
        
        # 100 days overdue (90+ bucket)
        self.invoice_100_overdue = self.create_invoice(
            amount=5000,
            invoice_date=today - timedelta(days=130),
            due_date=today - timedelta(days=100)
        )
    
    def create_invoice(self, amount, invoice_date, due_date):
        invoice = self.env['account.move'].create({
            'move_type': 'out_invoice',
            'partner_id': self.partner.id,
            'invoice_date': invoice_date,
            'invoice_date_due': due_date,
            'line_ids': [(0, 0, {
                'product_id': self.product.id,
                'quantity': 1,
                'price_unit': amount,
                'account_id': self.product.property_account_income_id.id
            })]
        })
        invoice.action_post()
        return invoice
    
    def test_aging_bucket_calculation(self):
        """Test that invoices are correctly assigned to aging buckets."""
        summary = self.calculator.calculate_customer_aging(self.partner.id)
        
        # Verify bucket totals
        self.assertEqual(summary.current, 1000)
        self.assertEqual(summary.bucket_1_30, 2000)
        self.assertEqual(summary.bucket_31_60, 3000)
        self.assertEqual(summary.bucket_61_90, 4000)
        self.assertEqual(summary.bucket_90_plus, 5000)
        self.assertEqual(summary.current_balance, 15000)
    
    def test_weighted_average_days_overdue(self):
        """Test weighted average days overdue calculation."""
        summary = self.calculator.calculate_customer_aging(self.partner.id)
        
        # Weighted average = (15*2000 + 45*3000 + 75*4000 + 100*5000) / 14000
        expected_wad = (15*2000 + 45*3000 + 75*4000 + 100*5000) / 14000
        
        self.assertAlmostEqual(
            summary.weighted_average_days_overdue,
            expected_wad,
            places=2
        )
    
    def test_percent_overdue_calculation(self):
        """Test percentage of total that is overdue."""
        summary = self.calculator.calculate_customer_aging(self.partner.id)
        
        # Total overdue = 2000 + 3000 + 4000 + 5000 = 14000
        # Total = 15000
        # Percent overdue = 14000 / 15000 * 100 = 93.33%
        
        expected_percent = 14000 / 15000 * 100
        self.assertAlmostEqual(
            summary.percent_overdue,
            expected_percent,
            places=2
        )
    
    def test_credit_limit_utilization(self):
        """Test available credit calculation."""
        self.partner.b2b_credit_limit = 20000
        
        summary = self.calculator.calculate_customer_aging(self.partner.id)
        
        self.assertEqual(summary.credit_limit, 20000)
        self.assertEqual(summary.available_credit, 5000)  # 20000 - 15000


class TestCreditRiskScorer(common.TransactionCase):
    """Test cases for credit risk scoring."""
    
    def test_low_risk_score(self):
        """Test low risk customer scoring."""
        # Customer with current invoices only
        scorer = self.env['b2b.credit.risk.scorer']
        
        summary = self.calculator.calculate_customer_aging(self.partner.id)
        # Modify to have all current
        summary.current_balance = 10000
        summary.current = 10000
        summary.percent_overdue = 0
        summary.days_sales_outstanding = 25
        summary.credit_limit = 50000
        
        score = scorer.calculate_risk_score(summary)
        
        self.assertEqual(score['risk_classification'], 'Low')
        self.assertLess(score['total_score'], 25)
    
    def test_critical_risk_score(self):
        """Test critical risk customer scoring."""
        scorer = self.env['b2b.credit.risk.scorer']
        
        summary = self.calculator.calculate_customer_aging(self.partner.id)
        # Modify to be high risk
        summary.current = 1000
        summary.bucket_90_plus = 15000
        summary.percent_overdue = 95
        summary.days_sales_outstanding = 120
        summary.credit_limit = 10000
        
        score = scorer.calculate_risk_score(summary)
        
        self.assertEqual(score['risk_classification'], 'Critical')
        self.assertGreater(score['total_score'], 80)
        self.assertTrue(len(score['recommendations']) > 0)
```

### 8.4 Load Testing Specifications

```yaml
# Load Testing Specifications for B2B Features

Test Suite: B2B Load Testing
Environment: Staging
Duration: 2 hours
Concurrent Users: 500

Test Scenarios:

1. Standing Order Generation Load Test
   Description: Test batch processing of standing orders under load
   Concurrent Executions: 10
   Orders per Batch: 1000
   Expected Throughput: > 200 orders/second
   Max Response Time: < 5 minutes for 1000 orders
   Success Rate: > 99.9%

2. Approval Workflow Concurrent Access
   Description: Test multiple approvers accessing approval queue
   Concurrent Users: 100 approvers
   Operations: View queue, approve, reject, comment
   Think Time: 5-15 seconds between actions
   Expected Response Time: < 2 seconds for queue load
   Expected Response Time: < 500ms for approval action

3. Credit Check API Load Test
   Description: Test real-time credit checking under load
   Concurrent Requests: 200/second
   Test Duration: 10 minutes
   Expected Response Time: < 500ms at 95th percentile
   Error Rate: < 0.1%

4. Report Generation Load Test
   Description: Test concurrent report generation
   Concurrent Reports: 50
   Report Types: Aging, Sales, Standing Orders, Incentives
   Expected Completion Time: < 30 seconds for standard reports
   Expected Completion Time: < 2 minutes for complex reports

5. Dashboard Widget Load Test
   Description: Test dashboard data loading
   Concurrent Users: 500
   Widgets per Dashboard: 8
   Auto-refresh Interval: 30 seconds
   Expected Load Time: < 3 seconds for all widgets
   Expected Cache Hit Rate: > 80%

Performance Thresholds:
  API Response Time:
    p50: < 200ms
    p95: < 500ms
    p99: < 1000ms
  
  Database Query Time:
    Simple Queries: < 50ms
    Complex Queries: < 500ms
    Report Queries: < 5000ms
  
  Resource Utilization:
    CPU: < 70% average
    Memory: < 80% of available
    Database Connections: < 80% of pool

Monitoring Metrics:
  - Request throughput (requests/second)
  - Response time percentiles
  - Error rates by endpoint
  - Database query performance
  - Queue depths (Celery/RabbitMQ)
  - Memory and CPU utilization
  - Cache hit/miss ratios
```

---

## 9. Deliverables & Metrics

### 9.1 Feature Checklist

| Feature | Status | Priority | Owner | Estimated Hours | Actual Hours |
|---------|--------|----------|-------|-----------------|--------------|
| **Standing Orders** | | | | | |
| Standing order model and CRUD | | P0 | Dev 2 | 8 | |
| Frequency calculation engine | | P0 | Dev 2 | 8 | |
| Standing order generation | | P0 | Dev 1 | 16 | |
| Exception handling framework | | P0 | Dev 1 | 8 | |
| Pause/resume functionality | | P0 | Dev 1 | 6 | |
| Conflict resolution | | P1 | Dev 1 | 8 | |
| Substitution logic | | P1 | Dev 1 | 6 | |
| Standing order UI | | P0 | Dev 3 | 16 | |
| Execution preview | | P1 | Dev 3 | 6 | |
| **Approval Workflows** | | | | | |
| Workflow configuration models | | P0 | Dev 2 | 10 | |
| Approval instance tracking | | P0 | Dev 2 | 8 | |
| State machine implementation | | P0 | Dev 1 | 12 | |
| Multi-level approval logic | | P0 | Dev 1 | 10 | |
| Delegation functionality | | P1 | Dev 1 | 6 | |
| Escalation management | | P1 | Dev 1 | 6 | |
| Approval dashboard | | P0 | Dev 3 | 12 | |
| Mobile approval interface | | P2 | Dev 3 | 8 | |
| **Credit Management** | | | | | |
| Credit engine | | P0 | Dev 2 | 10 | |
| Real-time credit checking | | P0 | Dev 2 | 6 | |
| Aging calculation engine | | P0 | Dev 2 | 8 | |
| Credit hold management | | P0 | Dev 2 | 6 | |
| Aging reports | | P0 | Dev 2 | 6 | |
| Credit risk scoring | | P1 | Dev 2 | 8 | |
| **Contract Pricing** | | | | | |
| Contract pricing models | | P0 | Dev 2 | 8 | |
| Price resolver engine | | P0 | Dev 2 | 10 | |
| Volume commitment tracking | | P1 | Dev 2 | 6 | |
| Price change monitoring | | P1 | Dev 2 | 4 | |
| **Incentives** | | | | | |
| Incentive program models | | P1 | Dev 2 | 6 | |
| Incentive calculator | | P1 | Dev 2 | 10 | |
| Incentive statements | | P1 | Dev 3 | 6 | |
| **Reporting & Analytics** | | | | | |
| Report builder framework | | P0 | Dev 1 | 12 | |
| Data mart schema | | P0 | Dev 2 | 8 | |
| Report APIs | | P0 | Dev 1 | 8 | |
| Analytics dashboard | | P0 | Dev 3 | 16 | |
| Scheduled reports | | P1 | Dev 1 | 6 | |

### 9.2 Performance Benchmarks

| Metric | Target | Acceptable | Critical |
|--------|--------|------------|----------|
| Standing order generation (1000 orders) | < 5 min | < 10 min | > 10 min |
| Credit check API response | < 500ms | < 1000ms | > 1000ms |
| Approval queue load | < 2s | < 5s | > 5s |
| Report generation (simple) | < 10s | < 30s | > 30s |
| Report generation (complex) | < 30s | < 2min | > 2min |
| Dashboard widget load | < 3s | < 5s | > 5s |
| Standing order execution success rate | 99.9% | 99.5% | < 99.5% |
| Approval notification delivery | 99.9% | 99% | < 99% |

### 9.3 Acceptance Criteria

**Standing Orders:**
- [ ] Can create standing order with all frequency types
- [ ] Automatic order generation executes on schedule
- [ ] Pause/resume functionality works correctly
- [ ] Exception handling catches and logs errors
- [ ] Notifications sent on execution and failure
- [ ] Conflict detection prevents double-execution
- [ ] Product substitution works when enabled
- [ ] Execution history tracked accurately
- [ ] Can preview next 10 executions

**Approval Workflows:**
- [ ] Multi-level approval chains execute correctly
- [ ] Approval limits enforced per level
- [ ] Delegation works within configured limits
- [ ] Escalation occurs after timeout period
- [ ] Rejection stops workflow and notifies
- [ ] Audit trail captures all actions
- [ ] Mobile interface supports approval actions
- [ ] Dashboard shows real-time queue status

**Credit Management:**
- [ ] Real-time credit check blocks over-limit orders
- [ ] Aging report calculates correct buckets
- [ ] Credit holds reserve available credit
- [ ] Risk scores calculate accurately
- [ ] Collection workflow triggers appropriately
- [ ] Credit limit history tracked

**Reporting:**
- [ ] Report builder creates valid reports
- [ ] Scheduled reports execute on time
- [ ] Dashboard widgets load within SLA
- [ ] Export functions generate correct files

---

## 10. Risk Management

### 10.1 Technical Risks

| Risk | Probability | Impact | Mitigation Strategy |
|------|-------------|--------|---------------------|
| Standing order generation conflicts | Medium | High | Implement robust conflict detection and resolution strategy with clear business rules |
| Performance degradation with large data | Medium | High | Implement pagination, caching, and database indexing; plan for horizontal scaling |
| Credit calculation errors | Low | Critical | Extensive unit testing, data validation, audit trails, and reconciliation processes |
| Workflow state inconsistencies | Low | High | Use database transactions, state machine validation, and periodic consistency checks |
| Integration failures with external systems | Medium | Medium | Implement circuit breakers, retry logic, and fallback mechanisms |
| Data migration issues | Medium | High | Thorough testing in staging, backup/rollback procedures, phased rollout |

### 10.2 Business Risks

| Risk | Probability | Impact | Mitigation Strategy |
|------|-------------|--------|---------------------|
| Customer resistance to approval workflows | Medium | Medium | Communicate benefits, provide training, ensure mobile accessibility |
| Incorrect pricing on standing orders | Low | Critical | Price validation, contract verification, approval gates for price changes |
| Credit limit errors cause order blocks | Medium | High | Graceful error handling, manual override capabilities, clear error messages |
| Report data perceived as inaccurate | Medium | Medium | Data validation, reconciliation with source systems, transparent calculation logic |

### 10.3 Mitigation Plans

**Risk Monitoring:**
- Daily automated test suite execution
- Real-time error alerting and dashboards
- Weekly risk review meetings
- Monthly performance trend analysis

**Contingency Plans:**
- Feature flags for rapid disabling of problematic features
- Rollback procedures for each component
- Manual workarounds documented for critical failures
- Emergency contact procedures

---

## 11. Appendices

### Appendix A: Complete Code Examples

[Full source code for key components would be included here in a real implementation]

### Appendix B: Configuration Files

```xml
<!-- Full module manifest -->
<?xml version="1.0" encoding="UTF-8"?>
<odoo>
    <data>
        <!-- Security Rules -->
        <record id="b2b_standing_order_rule_company" model="ir.rule">
            <field name="name">Standing Order Multi-Company</field>
            <field name="model_id" ref="model_b2b_standing_order"/>
            <field name="domain_force">['|', ('company_id', '=', False), ('company_id', 'in', company_ids)]</field>
        </record>
        
        <!-- Email Templates -->
        <record id="standing_order_execution_email" model="mail.template">
            <field name="name">Standing Order Executed</field>
            <field name="model_id" ref="model_b2b_standing_order"/>
            <field name="subject">Standing Order Executed: ${object.name}</field>
            <field name="body_html">
                <![CDATA[
                <p>Dear ${object.partner_id.name},</p>
                <p>Your standing order <strong>${object.name}</strong> has been executed successfully.</p>
                <p>Order details:</p>
                <ul>
                    <li>Order Number: ${ctx.get('sale_order_number', 'N/A')}</li>
                    <li>Amount: ${ctx.get('sale_order_amount', 'N/A')}</li>
                </ul>
                ]]>
            </field>
        </record>
    </data>
</odoo>
```

### Appendix C: API Postman Collection

[JSON export of Postman collection for all APIs]

### Appendix D: Database Migration Scripts

```python
# Migration script for new tables
def migrate_standing_orders(cr, version):
    """Migration for standing order feature."""
    # Create tables
    cr.execute("""
        CREATE TABLE IF NOT EXISTS b2b_standing_orders (
            -- table definition
        );
    """)
    
    # Create indexes
    cr.execute("""
        CREATE INDEX IF NOT EXISTS idx_standing_orders_partner 
        ON b2b_standing_orders(partner_id);
    """)
    
    # Migrate existing data if applicable
    # ...
```

### Appendix E: Environment Configuration

```yaml
# docker-compose.yml for B2B testing environment
version: '3.8'
services:
  odoo:
    image: odoo:16.0
    environment:
      - HOST=db
      - USER=odoo
      - PASSWORD=odoo
    volumes:
      - ./addons:/mnt/extra-addons
    depends_on:
      - db
  
  db:
    image: postgres:14
    environment:
      - POSTGRES_DB=postgres
      - POSTGRES_PASSWORD=odoo
      - POSTGRES_USER=odoo
```

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2024 | Technical Team | Initial document creation |

## Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Technical Lead | | | |
| Product Owner | | | |
| QA Lead | | | |

---

*End of Milestone 32 - Advanced B2B Features Implementation Document*
