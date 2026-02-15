# Milestone 89: Partner Portal Integration

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M89-v1.0                                          |
| **Milestone**        | 89 - Partner Portal Integration                              |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 631-640                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 89 implements comprehensive partner portal integration enabling supplier self-service, collaborative demand planning, forecast sharing, performance scorecards, and bi-directional communication.

### 1.2 Scope

- Partner Portal Framework
- Supplier Self-Service Portal
- Collaborative Planning Interface
- Forecast Sharing System
- Partner Performance Dashboard
- Document Sharing & Repository
- Communication Hub
- Integration APIs & Webhooks
- OWL Partner Portal UI
- Mobile Partner Portal

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | Partner Portal Framework             | Dev 1  | Critical |
| 2  | Partner Model                        | Dev 1  | Critical |
| 3  | Supplier Self-Service                | Dev 2  | High     |
| 4  | Collaborative Planning               | Dev 1  | High     |
| 5  | Forecast Sharing System              | Dev 2  | High     |
| 6  | Partner Performance Scorecard        | Dev 1  | High     |
| 7  | Document Repository                  | Dev 2  | Medium   |
| 8  | Communication Hub                    | Dev 2  | Medium   |
| 9  | OWL Partner Portal UI                | Dev 3  | Critical |
| 10 | Partner Portal API & Webhooks        | Dev 2  | High     |

---

## 3. Day-by-Day Development Plan

### Day 631 (Day 1): Partner Portal Framework

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_partner_portal.py
from odoo import models, fields, api
from odoo.exceptions import AccessDenied
import secrets
import hashlib

class B2BPartner(models.Model):
    _name = 'b2b.partner'
    _description = 'B2B Partner'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'name'

    name = fields.Char(string='Partner Name', required=True, tracking=True)
    partner_id = fields.Many2one('res.partner', string='Contact', required=True)
    code = fields.Char(string='Partner Code', readonly=True, copy=False)

    # Partner Type
    partner_type = fields.Selection([
        ('supplier', 'Supplier'),
        ('distributor', 'Distributor'),
        ('manufacturer', 'Manufacturer'),
        ('logistics', 'Logistics Provider'),
    ], string='Partner Type', required=True, tracking=True)

    # Status
    state = fields.Selection([
        ('pending', 'Pending Activation'),
        ('active', 'Active'),
        ('suspended', 'Suspended'),
        ('terminated', 'Terminated'),
    ], string='Status', default='pending', tracking=True)

    # Portal Access
    portal_access = fields.Boolean(string='Portal Access', default=True)
    portal_user_ids = fields.One2many('b2b.partner.user', 'partner_id', string='Portal Users')

    # Contact Information
    email = fields.Char(related='partner_id.email', store=True)
    phone = fields.Char(related='partner_id.phone', store=True)

    # Business Information
    tax_id = fields.Char(string='Tax ID / BIN')
    business_license = fields.Char(string='Business License')

    # Contract & Terms
    contract_start = fields.Date(string='Contract Start')
    contract_end = fields.Date(string='Contract End')
    payment_term_id = fields.Many2one('account.payment.term', string='Payment Terms')

    # Performance
    performance_score = fields.Float(string='Performance Score', compute='_compute_performance_score', store=True)
    on_time_delivery_rate = fields.Float(string='On-Time Delivery %')
    quality_score = fields.Float(string='Quality Score')

    # Capabilities
    product_category_ids = fields.Many2many('product.category', string='Product Categories')
    service_region_ids = fields.Many2many('res.country.state', string='Service Regions')

    # Documents
    document_ids = fields.One2many('b2b.partner.document', 'partner_id', string='Documents')

    # Timestamps
    last_login = fields.Datetime(string='Last Portal Login')
    activated_date = fields.Datetime(string='Activated Date')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if not vals.get('code'):
                vals['code'] = self.env['ir.sequence'].next_by_code('b2b.partner') or 'NEW'
        return super().create(vals_list)

    @api.depends('on_time_delivery_rate', 'quality_score')
    def _compute_performance_score(self):
        for partner in self:
            otd = partner.on_time_delivery_rate or 0
            quality = partner.quality_score or 0
            # Weighted average: 60% OTD, 40% Quality
            partner.performance_score = (otd * 0.6) + (quality * 0.4)

    def action_activate(self):
        """Activate partner portal access"""
        self.ensure_one()
        self.write({
            'state': 'active',
            'activated_date': fields.Datetime.now(),
        })
        self._send_activation_notification()

    def action_suspend(self, reason=None):
        """Suspend partner"""
        self.ensure_one()
        self.write({'state': 'suspended'})
        self.message_post(body=f'Partner suspended. Reason: {reason or "Not specified"}')

    def _send_activation_notification(self):
        """Send activation email to partner"""
        template = self.env.ref('smart_dairy_b2b.email_template_partner_activation', raise_if_not_found=False)
        if template:
            template.send_mail(self.id, force_send=True)


class B2BPartnerUser(models.Model):
    _name = 'b2b.partner.user'
    _description = 'B2B Partner Portal User'

    name = fields.Char(string='Full Name', required=True)
    partner_id = fields.Many2one('b2b.partner', string='Partner', required=True, ondelete='cascade')
    user_id = fields.Many2one('res.users', string='Portal User')
    email = fields.Char(string='Email', required=True)
    phone = fields.Char(string='Phone')

    # Role
    role = fields.Selection([
        ('admin', 'Portal Admin'),
        ('manager', 'Manager'),
        ('viewer', 'Viewer'),
    ], string='Role', default='viewer')

    # Access Permissions
    can_view_orders = fields.Boolean(string='View Orders', default=True)
    can_manage_forecasts = fields.Boolean(string='Manage Forecasts', default=False)
    can_upload_documents = fields.Boolean(string='Upload Documents', default=False)
    can_view_performance = fields.Boolean(string='View Performance', default=True)
    can_communicate = fields.Boolean(string='Send Messages', default=True)

    # Status
    active = fields.Boolean(default=True)
    last_login = fields.Datetime(string='Last Login')

    # Security
    api_key = fields.Char(string='API Key', copy=False)
    api_key_expiry = fields.Datetime(string='API Key Expiry')

    def generate_api_key(self):
        """Generate API key for partner user"""
        self.ensure_one()
        key = secrets.token_urlsafe(32)
        self.write({
            'api_key': hashlib.sha256(key.encode()).hexdigest(),
            'api_key_expiry': fields.Datetime.add(fields.Datetime.now(), days=365),
        })
        return key  # Return plain key once, stored as hash


class B2BPartnerDocument(models.Model):
    _name = 'b2b.partner.document'
    _description = 'B2B Partner Document'
    _order = 'create_date desc'

    name = fields.Char(string='Document Name', required=True)
    partner_id = fields.Many2one('b2b.partner', string='Partner', required=True, ondelete='cascade')

    document_type = fields.Selection([
        ('contract', 'Contract'),
        ('certificate', 'Certificate'),
        ('license', 'License'),
        ('insurance', 'Insurance'),
        ('compliance', 'Compliance Document'),
        ('other', 'Other'),
    ], string='Document Type', required=True)

    file = fields.Binary(string='File', required=True, attachment=True)
    filename = fields.Char(string='Filename')
    file_size = fields.Integer(string='File Size')

    # Validity
    issue_date = fields.Date(string='Issue Date')
    expiry_date = fields.Date(string='Expiry Date')
    is_expired = fields.Boolean(string='Expired', compute='_compute_is_expired')

    # Sharing
    shared_by_partner = fields.Boolean(string='Shared by Partner', default=False)
    shared_with_partner = fields.Boolean(string='Shared with Partner', default=False)

    @api.depends('expiry_date')
    def _compute_is_expired(self):
        today = fields.Date.today()
        for doc in self:
            doc.is_expired = doc.expiry_date and doc.expiry_date < today
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/controllers/partner_portal.py
from odoo import http
from odoo.http import request
import json

class B2BPartnerPortalController(http.Controller):

    def _check_partner_access(self):
        """Verify partner portal access"""
        user = request.env.user
        partner_user = request.env['b2b.partner.user'].sudo().search([
            ('user_id', '=', user.id),
            ('active', '=', True),
        ], limit=1)

        if not partner_user or partner_user.partner_id.state != 'active':
            return None

        # Update last login
        partner_user.sudo().write({'last_login': fields.Datetime.now()})
        partner_user.partner_id.sudo().write({'last_login': fields.Datetime.now()})

        return partner_user

    @http.route('/partner/portal', type='http', auth='user', website=True)
    def partner_portal_home(self, **kw):
        """Partner portal home page"""
        partner_user = self._check_partner_access()
        if not partner_user:
            return request.redirect('/web/login?error=no_partner_access')

        partner = partner_user.partner_id

        # Dashboard data
        values = {
            'partner': partner,
            'partner_user': partner_user,
            'pending_orders': self._get_pending_orders(partner),
            'recent_forecasts': self._get_recent_forecasts(partner),
            'performance_data': self._get_performance_data(partner),
            'unread_messages': self._get_unread_messages(partner),
        }

        return request.render('smart_dairy_b2b.partner_portal_home', values)

    @http.route('/partner/portal/orders', type='http', auth='user', website=True)
    def partner_portal_orders(self, **kw):
        """Partner order management"""
        partner_user = self._check_partner_access()
        if not partner_user:
            return request.redirect('/web/login')

        partner = partner_user.partner_id

        orders = request.env['purchase.order'].sudo().search([
            ('partner_id', '=', partner.partner_id.id),
        ], order='date_order desc', limit=50)

        values = {
            'partner': partner,
            'orders': orders,
        }

        return request.render('smart_dairy_b2b.partner_portal_orders', values)

    @http.route('/partner/portal/performance', type='http', auth='user', website=True)
    def partner_portal_performance(self, **kw):
        """Partner performance scorecard"""
        partner_user = self._check_partner_access()
        if not partner_user or not partner_user.can_view_performance:
            return request.redirect('/partner/portal')

        partner = partner_user.partner_id

        values = {
            'partner': partner,
            'scorecard': self._build_scorecard(partner),
            'trend_data': self._get_performance_trend(partner),
        }

        return request.render('smart_dairy_b2b.partner_portal_performance', values)

    def _get_pending_orders(self, partner):
        """Get pending purchase orders for partner"""
        return request.env['purchase.order'].sudo().search_count([
            ('partner_id', '=', partner.partner_id.id),
            ('state', 'in', ['draft', 'sent']),
        ])

    def _get_recent_forecasts(self, partner):
        """Get recent forecast requests"""
        return request.env['b2b.forecast'].sudo().search([
            ('partner_id', '=', partner.id),
        ], order='create_date desc', limit=5)

    def _get_performance_data(self, partner):
        """Get performance summary"""
        return {
            'score': partner.performance_score,
            'on_time_delivery': partner.on_time_delivery_rate,
            'quality_score': partner.quality_score,
        }

    def _get_unread_messages(self, partner):
        """Get unread message count"""
        return request.env['b2b.partner.message'].sudo().search_count([
            ('partner_id', '=', partner.id),
            ('read', '=', False),
        ])

    def _build_scorecard(self, partner):
        """Build detailed performance scorecard"""
        return {
            'overall_score': partner.performance_score,
            'categories': [
                {'name': 'On-Time Delivery', 'score': partner.on_time_delivery_rate, 'weight': 60},
                {'name': 'Quality', 'score': partner.quality_score, 'weight': 40},
            ],
        }

    def _get_performance_trend(self, partner):
        """Get 12-month performance trend"""
        # Query performance history
        history = request.env['b2b.partner.performance.history'].sudo().search([
            ('partner_id', '=', partner.id),
        ], order='date desc', limit=12)

        return [{'date': h.date.strftime('%Y-%m'), 'score': h.score} for h in history]
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// Partner Portal Home Component
// odoo/addons/smart_dairy_b2b/static/src/js/partner_portal_home.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class PartnerPortalHome extends Component {
    static template = "smart_dairy_b2b.PartnerPortalHome";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            partner: null,
            pendingOrders: 0,
            recentForecasts: [],
            performanceScore: 0,
            unreadMessages: 0,
            loading: true,
        });

        onWillStart(async () => {
            await this.loadDashboardData();
        });
    }

    async loadDashboardData() {
        try {
            const data = await this.orm.call(
                "b2b.partner.portal.service",
                "get_dashboard_data",
                [this.props.partnerId]
            );

            this.state.partner = data.partner;
            this.state.pendingOrders = data.pending_orders;
            this.state.recentForecasts = data.recent_forecasts;
            this.state.performanceScore = data.performance_score;
            this.state.unreadMessages = data.unread_messages;
        } finally {
            this.state.loading = false;
        }
    }

    get performanceClass() {
        const score = this.state.performanceScore;
        if (score >= 90) return "excellent";
        if (score >= 75) return "good";
        if (score >= 60) return "fair";
        return "needs-improvement";
    }

    navigateToOrders() {
        window.location.href = "/partner/portal/orders";
    }

    navigateToForecasts() {
        window.location.href = "/partner/portal/forecasts";
    }

    navigateToPerformance() {
        window.location.href = "/partner/portal/performance";
    }

    navigateToMessages() {
        window.location.href = "/partner/portal/messages";
    }
}
```

```xml
<!-- Partner Portal Home Template -->
<!-- odoo/addons/smart_dairy_b2b/static/src/xml/partner_portal_home.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_dairy_b2b.PartnerPortalHome" owl="1">
        <div class="partner-portal-home">
            <t t-if="state.loading">
                <div class="loading-spinner">Loading...</div>
            </t>
            <t t-else="">
                <!-- Welcome Header -->
                <div class="portal-header">
                    <h1>Welcome, <t t-esc="state.partner.name"/></h1>
                    <span class="partner-code">Partner ID: <t t-esc="state.partner.code"/></span>
                </div>

                <!-- Dashboard Cards -->
                <div class="dashboard-grid">
                    <!-- Pending Orders Card -->
                    <div class="dashboard-card orders" t-on-click="navigateToOrders">
                        <div class="card-icon">📦</div>
                        <div class="card-content">
                            <span class="card-value"><t t-esc="state.pendingOrders"/></span>
                            <span class="card-label">Pending Orders</span>
                        </div>
                    </div>

                    <!-- Forecasts Card -->
                    <div class="dashboard-card forecasts" t-on-click="navigateToForecasts">
                        <div class="card-icon">📊</div>
                        <div class="card-content">
                            <span class="card-value"><t t-esc="state.recentForecasts.length"/></span>
                            <span class="card-label">Active Forecasts</span>
                        </div>
                    </div>

                    <!-- Performance Card -->
                    <div class="dashboard-card performance" t-att-class="performanceClass" t-on-click="navigateToPerformance">
                        <div class="card-icon">⭐</div>
                        <div class="card-content">
                            <span class="card-value"><t t-esc="state.performanceScore.toFixed(1)"/>%</span>
                            <span class="card-label">Performance Score</span>
                        </div>
                    </div>

                    <!-- Messages Card -->
                    <div class="dashboard-card messages" t-on-click="navigateToMessages">
                        <div class="card-icon">💬</div>
                        <div class="card-content">
                            <span class="card-value"><t t-esc="state.unreadMessages"/></span>
                            <span class="card-label">Unread Messages</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Forecasts List -->
                <div class="recent-forecasts-section" t-if="state.recentForecasts.length > 0">
                    <h3>Recent Forecast Requests</h3>
                    <ul class="forecast-list">
                        <t t-foreach="state.recentForecasts" t-as="forecast" t-key="forecast.id">
                            <li class="forecast-item">
                                <span class="forecast-name"><t t-esc="forecast.name"/></span>
                                <span class="forecast-period"><t t-esc="forecast.period"/></span>
                                <span t-att-class="'forecast-status ' + forecast.state">
                                    <t t-esc="forecast.state"/>
                                </span>
                            </li>
                        </t>
                    </ul>
                </div>
            </t>
        </div>
    </t>
</templates>
```

---

### Day 632 (Day 2): Supplier Self-Service

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_supplier_self_service.py
from odoo import models, fields, api

class B2BSupplierProfile(models.Model):
    _name = 'b2b.supplier.profile'
    _description = 'Supplier Self-Service Profile'
    _inherits = {'b2b.partner': 'partner_ref_id'}

    partner_ref_id = fields.Many2one('b2b.partner', string='Partner Reference',
                                      required=True, ondelete='cascade', auto_join=True)

    # Company Details
    company_description = fields.Html(string='Company Description')
    year_established = fields.Integer(string='Year Established')
    employee_count = fields.Selection([
        ('1-10', '1-10'),
        ('11-50', '11-50'),
        ('51-200', '51-200'),
        ('201-500', '201-500'),
        ('500+', '500+'),
    ], string='Employee Count')

    # Capabilities
    production_capacity = fields.Char(string='Production Capacity')
    min_order_value = fields.Monetary(string='Minimum Order Value')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    lead_time_days = fields.Integer(string='Standard Lead Time (Days)')

    # Certifications
    certification_ids = fields.One2many('b2b.supplier.certification', 'profile_id', string='Certifications')

    # Products
    product_template_ids = fields.Many2many('product.template', string='Products Supplied')

    # Bank Information (for payments)
    bank_name = fields.Char(string='Bank Name')
    bank_account = fields.Char(string='Bank Account')
    bank_branch = fields.Char(string='Branch')

    # Preferences
    preferred_communication = fields.Selection([
        ('email', 'Email'),
        ('phone', 'Phone'),
        ('portal', 'Portal Messages'),
    ], string='Preferred Communication', default='email')

    notification_frequency = fields.Selection([
        ('realtime', 'Real-time'),
        ('daily', 'Daily Digest'),
        ('weekly', 'Weekly Summary'),
    ], string='Notification Frequency', default='daily')

    def action_update_profile(self, values):
        """Update supplier profile from portal"""
        allowed_fields = [
            'company_description', 'production_capacity', 'min_order_value',
            'lead_time_days', 'preferred_communication', 'notification_frequency',
            'bank_name', 'bank_account', 'bank_branch',
        ]

        update_vals = {k: v for k, v in values.items() if k in allowed_fields}
        self.write(update_vals)
        self.message_post(body='Profile updated via Partner Portal')
        return True


class B2BSupplierCertification(models.Model):
    _name = 'b2b.supplier.certification'
    _description = 'Supplier Certification'

    profile_id = fields.Many2one('b2b.supplier.profile', string='Supplier Profile',
                                  required=True, ondelete='cascade')
    name = fields.Char(string='Certification Name', required=True)
    certifying_body = fields.Char(string='Certifying Body')
    certificate_number = fields.Char(string='Certificate Number')
    issue_date = fields.Date(string='Issue Date')
    expiry_date = fields.Date(string='Expiry Date')
    certificate_file = fields.Binary(string='Certificate File')
    certificate_filename = fields.Char(string='Filename')

    is_valid = fields.Boolean(string='Valid', compute='_compute_is_valid')

    @api.depends('expiry_date')
    def _compute_is_valid(self):
        today = fields.Date.today()
        for cert in self:
            cert.is_valid = not cert.expiry_date or cert.expiry_date >= today


class B2BSupplierService(models.AbstractModel):
    _name = 'b2b.supplier.service'
    _description = 'Supplier Self-Service'

    @api.model
    def get_profile(self, partner_id):
        """Get supplier profile for portal display"""
        profile = self.env['b2b.supplier.profile'].search([
            ('partner_ref_id', '=', partner_id)
        ], limit=1)

        if not profile:
            return {'error': 'Profile not found'}

        return {
            'id': profile.id,
            'name': profile.name,
            'code': profile.code,
            'company_description': profile.company_description,
            'production_capacity': profile.production_capacity,
            'min_order_value': profile.min_order_value,
            'lead_time_days': profile.lead_time_days,
            'certifications': [{
                'id': c.id,
                'name': c.name,
                'certifying_body': c.certifying_body,
                'expiry_date': c.expiry_date.isoformat() if c.expiry_date else None,
                'is_valid': c.is_valid,
            } for c in profile.certification_ids],
            'products': [{
                'id': p.id,
                'name': p.name,
            } for p in profile.product_template_ids[:20]],
        }

    @api.model
    def get_pending_orders(self, partner_id):
        """Get pending orders for supplier"""
        partner = self.env['b2b.partner'].browse(partner_id)
        orders = self.env['purchase.order'].search([
            ('partner_id', '=', partner.partner_id.id),
            ('state', 'in', ['draft', 'sent', 'to approve']),
        ])

        return [{
            'id': o.id,
            'name': o.name,
            'date_order': o.date_order.isoformat() if o.date_order else None,
            'amount_total': o.amount_total,
            'state': o.state,
            'line_count': len(o.order_line),
        } for o in orders]

    @api.model
    def confirm_order(self, order_id):
        """Supplier confirms purchase order"""
        order = self.env['purchase.order'].browse(order_id)
        if order.state == 'sent':
            order.button_confirm()
            return {'success': True, 'message': 'Order confirmed'}
        return {'success': False, 'error': 'Order cannot be confirmed'}
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/services/supplier_portal_api.py
from odoo import models, api, fields
from odoo.http import request
import json

class SupplierPortalAPI(models.AbstractModel):
    _name = 'b2b.supplier.portal.api'
    _description = 'Supplier Portal API Service'

    @api.model
    def update_delivery_status(self, order_id, status, tracking_info=None):
        """Update delivery status from supplier portal"""
        order = self.env['purchase.order'].browse(order_id)

        if not order.exists():
            return {'success': False, 'error': 'Order not found'}

        # Create delivery status update
        picking = order.picking_ids.filtered(lambda p: p.state not in ['done', 'cancel'])[:1]

        if picking:
            if status == 'shipped':
                if tracking_info:
                    picking.write({
                        'carrier_tracking_ref': tracking_info.get('tracking_number'),
                    })
                picking.message_post(body=f"Supplier updated: Shipped. Tracking: {tracking_info}")
            elif status == 'delivered':
                picking.button_validate()

        return {'success': True}

    @api.model
    def upload_invoice(self, order_id, invoice_data):
        """Upload supplier invoice"""
        order = self.env['purchase.order'].browse(order_id)

        if not order.exists():
            return {'success': False, 'error': 'Order not found'}

        # Create vendor bill attachment
        attachment = self.env['ir.attachment'].create({
            'name': invoice_data.get('filename', 'Supplier Invoice.pdf'),
            'datas': invoice_data.get('file_data'),
            'res_model': 'purchase.order',
            'res_id': order.id,
        })

        order.message_post(
            body=f"Supplier uploaded invoice: {attachment.name}",
            attachment_ids=[attachment.id]
        )

        return {'success': True, 'attachment_id': attachment.id}

    @api.model
    def get_payment_status(self, partner_id):
        """Get payment status for supplier"""
        partner = self.env['b2b.partner'].browse(partner_id)

        # Get unpaid bills
        bills = self.env['account.move'].search([
            ('partner_id', '=', partner.partner_id.id),
            ('move_type', '=', 'in_invoice'),
            ('state', '=', 'posted'),
            ('payment_state', 'in', ['not_paid', 'partial']),
        ])

        # Get recent payments
        payments = self.env['account.payment'].search([
            ('partner_id', '=', partner.partner_id.id),
            ('payment_type', '=', 'outbound'),
            ('state', '=', 'posted'),
        ], order='date desc', limit=10)

        return {
            'pending_bills': [{
                'id': b.id,
                'name': b.name,
                'date': b.invoice_date.isoformat() if b.invoice_date else None,
                'due_date': b.invoice_date_due.isoformat() if b.invoice_date_due else None,
                'amount_total': b.amount_total,
                'amount_residual': b.amount_residual,
            } for b in bills],
            'recent_payments': [{
                'id': p.id,
                'name': p.name,
                'date': p.date.isoformat() if p.date else None,
                'amount': p.amount,
            } for p in payments],
            'total_outstanding': sum(bills.mapped('amount_residual')),
        }
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// Supplier Self-Service Dashboard
// odoo/addons/smart_dairy_b2b/static/src/js/supplier_self_service.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class SupplierSelfService extends Component {
    static template = "smart_dairy_b2b.SupplierSelfService";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            profile: null,
            pendingOrders: [],
            paymentStatus: null,
            activeTab: "orders",
            loading: true,
            editMode: false,
        });

        onWillStart(async () => {
            await this.loadData();
        });
    }

    async loadData() {
        try {
            // Load profile
            this.state.profile = await this.orm.call(
                "b2b.supplier.service",
                "get_profile",
                [this.props.partnerId]
            );

            // Load pending orders
            this.state.pendingOrders = await this.orm.call(
                "b2b.supplier.service",
                "get_pending_orders",
                [this.props.partnerId]
            );

            // Load payment status
            this.state.paymentStatus = await this.orm.call(
                "b2b.supplier.portal.api",
                "get_payment_status",
                [this.props.partnerId]
            );
        } finally {
            this.state.loading = false;
        }
    }

    setActiveTab(tab) {
        this.state.activeTab = tab;
    }

    toggleEditMode() {
        this.state.editMode = !this.state.editMode;
    }

    async saveProfile() {
        try {
            await this.orm.call(
                "b2b.supplier.profile",
                "action_update_profile",
                [this.state.profile.id, this.state.profile]
            );
            this.notification.add("Profile updated successfully", { type: "success" });
            this.state.editMode = false;
        } catch (error) {
            this.notification.add("Failed to update profile", { type: "danger" });
        }
    }

    async confirmOrder(orderId) {
        const result = await this.orm.call(
            "b2b.supplier.service",
            "confirm_order",
            [orderId]
        );

        if (result.success) {
            this.notification.add("Order confirmed", { type: "success" });
            await this.loadData();
        } else {
            this.notification.add(result.error, { type: "danger" });
        }
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat("en-BD", {
            style: "currency",
            currency: "BDT",
        }).format(amount);
    }
}
```

---

### Day 633 (Day 3): Forecast Sharing System

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_forecast.py
from odoo import models, fields, api
from datetime import timedelta

class B2BForecast(models.Model):
    _name = 'b2b.forecast'
    _description = 'B2B Demand Forecast'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char(string='Forecast Reference', required=True, copy=False,
                       default=lambda self: 'New')
    partner_id = fields.Many2one('b2b.partner', string='Partner', required=True)

    # Forecast Period
    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)
    period_type = fields.Selection([
        ('weekly', 'Weekly'),
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
    ], string='Period Type', default='monthly')

    # Forecast Lines
    line_ids = fields.One2many('b2b.forecast.line', 'forecast_id', string='Forecast Lines')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('shared', 'Shared with Partner'),
        ('acknowledged', 'Acknowledged'),
        ('confirmed', 'Confirmed'),
        ('revised', 'Revised'),
    ], string='Status', default='draft', tracking=True)

    # Collaboration
    partner_notes = fields.Text(string='Partner Notes')
    partner_acknowledged_by = fields.Char(string='Acknowledged By')
    partner_acknowledged_date = fields.Datetime(string='Acknowledged Date')

    # Accuracy Tracking
    forecast_accuracy = fields.Float(string='Forecast Accuracy %', compute='_compute_accuracy')

    # Timestamps
    shared_date = fields.Datetime(string='Shared Date')
    confirmed_date = fields.Datetime(string='Confirmed Date')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', 'New') == 'New':
                vals['name'] = self.env['ir.sequence'].next_by_code('b2b.forecast') or 'New'
        return super().create(vals_list)

    @api.depends('line_ids.forecasted_qty', 'line_ids.actual_qty')
    def _compute_accuracy(self):
        for forecast in self:
            lines_with_actual = forecast.line_ids.filtered(lambda l: l.actual_qty > 0)
            if not lines_with_actual:
                forecast.forecast_accuracy = 0
                continue

            total_accuracy = 0
            for line in lines_with_actual:
                if line.forecasted_qty > 0:
                    accuracy = min(line.actual_qty / line.forecasted_qty, 1) * 100
                    total_accuracy += accuracy

            forecast.forecast_accuracy = total_accuracy / len(lines_with_actual)

    def action_share_with_partner(self):
        """Share forecast with partner"""
        self.ensure_one()
        self.write({
            'state': 'shared',
            'shared_date': fields.Datetime.now(),
        })
        self._send_forecast_notification()

    def action_partner_acknowledge(self, notes=None, acknowledged_by=None):
        """Partner acknowledges forecast"""
        self.ensure_one()
        self.write({
            'state': 'acknowledged',
            'partner_notes': notes,
            'partner_acknowledged_by': acknowledged_by,
            'partner_acknowledged_date': fields.Datetime.now(),
        })

    def action_confirm(self):
        """Confirm forecast for planning"""
        self.ensure_one()
        self.write({
            'state': 'confirmed',
            'confirmed_date': fields.Datetime.now(),
        })

    def _send_forecast_notification(self):
        """Send notification to partner about shared forecast"""
        template = self.env.ref('smart_dairy_b2b.email_template_forecast_shared', raise_if_not_found=False)
        if template:
            template.send_mail(self.id, force_send=True)


class B2BForecastLine(models.Model):
    _name = 'b2b.forecast.line'
    _description = 'B2B Forecast Line'

    forecast_id = fields.Many2one('b2b.forecast', string='Forecast', required=True, ondelete='cascade')
    product_id = fields.Many2one('product.product', string='Product', required=True)

    # Quantities
    forecasted_qty = fields.Float(string='Forecasted Quantity')
    partner_suggested_qty = fields.Float(string='Partner Suggested Qty')
    confirmed_qty = fields.Float(string='Confirmed Quantity')
    actual_qty = fields.Float(string='Actual Quantity', compute='_compute_actual_qty', store=True)

    # Unit
    uom_id = fields.Many2one('uom.uom', string='Unit of Measure', related='product_id.uom_id')

    # Notes
    notes = fields.Text(string='Notes')
    partner_comment = fields.Text(string='Partner Comment')

    # Variance
    variance = fields.Float(string='Variance %', compute='_compute_variance')

    @api.depends('forecast_id.period_end', 'product_id')
    def _compute_actual_qty(self):
        """Compute actual quantity from orders after period ends"""
        today = fields.Date.today()
        for line in self:
            if line.forecast_id.period_end and line.forecast_id.period_end < today:
                # Calculate actual from purchase orders
                orders = self.env['purchase.order'].search([
                    ('partner_id', '=', line.forecast_id.partner_id.partner_id.id),
                    ('date_order', '>=', line.forecast_id.period_start),
                    ('date_order', '<=', line.forecast_id.period_end),
                    ('state', 'in', ['purchase', 'done']),
                ])

                order_lines = orders.mapped('order_line').filtered(
                    lambda l: l.product_id.id == line.product_id.id
                )
                line.actual_qty = sum(order_lines.mapped('product_qty'))
            else:
                line.actual_qty = 0

    @api.depends('forecasted_qty', 'actual_qty')
    def _compute_variance(self):
        for line in self:
            if line.forecasted_qty and line.actual_qty:
                line.variance = ((line.actual_qty - line.forecasted_qty) / line.forecasted_qty) * 100
            else:
                line.variance = 0
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/services/forecast_service.py
from odoo import models, api, fields

class B2BForecastService(models.AbstractModel):
    _name = 'b2b.forecast.service'
    _description = 'Forecast Service'

    @api.model
    def get_partner_forecasts(self, partner_id):
        """Get forecasts for partner portal"""
        forecasts = self.env['b2b.forecast'].search([
            ('partner_id', '=', partner_id),
        ], order='period_start desc', limit=20)

        return [{
            'id': f.id,
            'name': f.name,
            'period_start': f.period_start.isoformat() if f.period_start else None,
            'period_end': f.period_end.isoformat() if f.period_end else None,
            'period_type': f.period_type,
            'state': f.state,
            'line_count': len(f.line_ids),
            'accuracy': f.forecast_accuracy,
        } for f in forecasts]

    @api.model
    def get_forecast_details(self, forecast_id):
        """Get detailed forecast for editing"""
        forecast = self.env['b2b.forecast'].browse(forecast_id)

        if not forecast.exists():
            return {'error': 'Forecast not found'}

        return {
            'id': forecast.id,
            'name': forecast.name,
            'period_start': forecast.period_start.isoformat() if forecast.period_start else None,
            'period_end': forecast.period_end.isoformat() if forecast.period_end else None,
            'state': forecast.state,
            'partner_notes': forecast.partner_notes,
            'lines': [{
                'id': l.id,
                'product_id': l.product_id.id,
                'product_name': l.product_id.name,
                'forecasted_qty': l.forecasted_qty,
                'partner_suggested_qty': l.partner_suggested_qty,
                'confirmed_qty': l.confirmed_qty,
                'actual_qty': l.actual_qty,
                'uom': l.uom_id.name,
                'notes': l.notes,
                'partner_comment': l.partner_comment,
                'variance': l.variance,
            } for l in forecast.line_ids],
        }

    @api.model
    def submit_partner_response(self, forecast_id, response_data):
        """Submit partner response to forecast"""
        forecast = self.env['b2b.forecast'].browse(forecast_id)

        if not forecast.exists():
            return {'success': False, 'error': 'Forecast not found'}

        if forecast.state not in ['shared', 'acknowledged']:
            return {'success': False, 'error': 'Forecast cannot be modified'}

        # Update notes
        forecast.write({'partner_notes': response_data.get('notes', '')})

        # Update line suggestions
        for line_data in response_data.get('lines', []):
            line = self.env['b2b.forecast.line'].browse(line_data.get('id'))
            if line.exists() and line.forecast_id.id == forecast_id:
                line.write({
                    'partner_suggested_qty': line_data.get('suggested_qty', 0),
                    'partner_comment': line_data.get('comment', ''),
                })

        # Acknowledge
        forecast.action_partner_acknowledge(
            notes=response_data.get('notes'),
            acknowledged_by=self.env.user.name
        )

        return {'success': True}

    @api.model
    def generate_forecast_from_history(self, partner_id, period_start, period_end):
        """Generate forecast based on historical data"""
        partner = self.env['b2b.partner'].browse(partner_id)

        # Get historical order data
        history_start = fields.Date.from_string(period_start) - timedelta(days=365)
        orders = self.env['purchase.order'].search([
            ('partner_id', '=', partner.partner_id.id),
            ('date_order', '>=', history_start),
            ('state', 'in', ['purchase', 'done']),
        ])

        # Aggregate by product
        product_totals = {}
        for order in orders:
            for line in order.order_line:
                if line.product_id.id not in product_totals:
                    product_totals[line.product_id.id] = 0
                product_totals[line.product_id.id] += line.product_qty

        # Create forecast
        forecast = self.env['b2b.forecast'].create({
            'partner_id': partner_id,
            'period_start': period_start,
            'period_end': period_end,
            'period_type': 'monthly',
        })

        # Create forecast lines (average monthly * forecast period months)
        months_of_history = 12
        period_months = ((fields.Date.from_string(period_end) -
                          fields.Date.from_string(period_start)).days / 30)

        for product_id, total_qty in product_totals.items():
            avg_monthly = total_qty / months_of_history
            forecasted = avg_monthly * period_months

            self.env['b2b.forecast.line'].create({
                'forecast_id': forecast.id,
                'product_id': product_id,
                'forecasted_qty': forecasted,
            })

        return {'success': True, 'forecast_id': forecast.id, 'forecast_name': forecast.name}
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// Forecast Management Component
// odoo/addons/smart_dairy_b2b/static/src/js/forecast_management.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class ForecastManagement extends Component {
    static template = "smart_dairy_b2b.ForecastManagement";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            forecasts: [],
            selectedForecast: null,
            loading: true,
            submitting: false,
        });

        onWillStart(async () => {
            await this.loadForecasts();
        });
    }

    async loadForecasts() {
        try {
            this.state.forecasts = await this.orm.call(
                "b2b.forecast.service",
                "get_partner_forecasts",
                [this.props.partnerId]
            );
        } finally {
            this.state.loading = false;
        }
    }

    async selectForecast(forecastId) {
        this.state.loading = true;
        try {
            this.state.selectedForecast = await this.orm.call(
                "b2b.forecast.service",
                "get_forecast_details",
                [forecastId]
            );
        } finally {
            this.state.loading = false;
        }
    }

    updateLineSuggestion(lineId, value) {
        const line = this.state.selectedForecast.lines.find(l => l.id === lineId);
        if (line) {
            line.partner_suggested_qty = parseFloat(value) || 0;
        }
    }

    updateLineComment(lineId, comment) {
        const line = this.state.selectedForecast.lines.find(l => l.id === lineId);
        if (line) {
            line.partner_comment = comment;
        }
    }

    updateNotes(notes) {
        this.state.selectedForecast.partner_notes = notes;
    }

    async submitResponse() {
        if (!this.state.selectedForecast) return;

        this.state.submitting = true;
        try {
            const result = await this.orm.call(
                "b2b.forecast.service",
                "submit_partner_response",
                [
                    this.state.selectedForecast.id,
                    {
                        notes: this.state.selectedForecast.partner_notes,
                        lines: this.state.selectedForecast.lines.map(l => ({
                            id: l.id,
                            suggested_qty: l.partner_suggested_qty,
                            comment: l.partner_comment,
                        })),
                    },
                ]
            );

            if (result.success) {
                this.notification.add("Forecast response submitted", { type: "success" });
                await this.loadForecasts();
                this.state.selectedForecast = null;
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } finally {
            this.state.submitting = false;
        }
    }

    getStatusClass(state) {
        const classes = {
            draft: "secondary",
            shared: "info",
            acknowledged: "warning",
            confirmed: "success",
            revised: "primary",
        };
        return classes[state] || "secondary";
    }

    closeForecastDetail() {
        this.state.selectedForecast = null;
    }
}
```

---

### Day 634 (Day 4): Collaborative Planning Interface

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_collaborative_planning.py
from odoo import models, fields, api

class B2BCollaborativePlan(models.Model):
    _name = 'b2b.collaborative.plan'
    _description = 'Collaborative Planning'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Plan Name', required=True)
    partner_ids = fields.Many2many('b2b.partner', string='Partners')

    # Planning Period
    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)

    # Plan Type
    plan_type = fields.Selection([
        ('production', 'Production Planning'),
        ('inventory', 'Inventory Planning'),
        ('promotion', 'Promotional Planning'),
        ('new_product', 'New Product Launch'),
    ], string='Plan Type', required=True)

    # Plan Details
    description = fields.Html(string='Description')
    objectives = fields.Text(string='Objectives')

    # Items
    item_ids = fields.One2many('b2b.collaborative.plan.item', 'plan_id', string='Plan Items')

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('in_review', 'In Review'),
        ('partner_input', 'Awaiting Partner Input'),
        ('finalized', 'Finalized'),
        ('active', 'Active'),
        ('completed', 'Completed'),
    ], string='Status', default='draft', tracking=True)

    # Collaboration
    comment_ids = fields.One2many('b2b.plan.comment', 'plan_id', string='Comments')
    revision_count = fields.Integer(string='Revisions', default=0)

    def action_request_partner_input(self):
        """Request input from partners"""
        self.ensure_one()
        self.write({'state': 'partner_input'})
        self._notify_partners()

    def action_finalize(self):
        """Finalize plan"""
        self.ensure_one()
        self.write({
            'state': 'finalized',
            'revision_count': self.revision_count + 1,
        })

    def _notify_partners(self):
        """Notify partners about plan update"""
        for partner in self.partner_ids:
            # Create activity for partner users
            for user in partner.portal_user_ids.filtered(lambda u: u.can_manage_forecasts):
                self.env['mail.activity'].create({
                    'res_model_id': self.env['ir.model']._get(self._name).id,
                    'res_id': self.id,
                    'activity_type_id': self.env.ref('mail.mail_activity_data_todo').id,
                    'summary': f'Review collaborative plan: {self.name}',
                    'user_id': user.user_id.id if user.user_id else self.env.user.id,
                })


class B2BCollaborativePlanItem(models.Model):
    _name = 'b2b.collaborative.plan.item'
    _description = 'Collaborative Plan Item'

    plan_id = fields.Many2one('b2b.collaborative.plan', string='Plan', required=True, ondelete='cascade')
    sequence = fields.Integer(string='Sequence', default=10)

    # Item Details
    product_id = fields.Many2one('product.product', string='Product')
    description = fields.Text(string='Description')

    # Quantities
    planned_qty = fields.Float(string='Planned Quantity')
    partner_committed_qty = fields.Float(string='Partner Committed')

    # Timeline
    target_date = fields.Date(string='Target Date')

    # Assignment
    assigned_partner_id = fields.Many2one('b2b.partner', string='Assigned Partner')

    # Status
    item_state = fields.Selection([
        ('pending', 'Pending'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('delayed', 'Delayed'),
    ], string='Status', default='pending')


class B2BPlanComment(models.Model):
    _name = 'b2b.plan.comment'
    _description = 'Plan Comment'
    _order = 'create_date desc'

    plan_id = fields.Many2one('b2b.collaborative.plan', string='Plan', required=True, ondelete='cascade')
    partner_id = fields.Many2one('b2b.partner', string='Partner')
    user_id = fields.Many2one('res.users', string='User', default=lambda self: self.env.user)

    comment = fields.Text(string='Comment', required=True)
    comment_type = fields.Selection([
        ('general', 'General'),
        ('question', 'Question'),
        ('suggestion', 'Suggestion'),
        ('concern', 'Concern'),
    ], string='Type', default='general')

    # Reply
    parent_id = fields.Many2one('b2b.plan.comment', string='Parent Comment')
    reply_ids = fields.One2many('b2b.plan.comment', 'parent_id', string='Replies')
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/services/collaborative_planning_service.py
from odoo import models, api, fields

class CollaborativePlanningService(models.AbstractModel):
    _name = 'b2b.collaborative.planning.service'
    _description = 'Collaborative Planning Service'

    @api.model
    def get_partner_plans(self, partner_id):
        """Get plans for partner"""
        plans = self.env['b2b.collaborative.plan'].search([
            ('partner_ids', 'in', [partner_id]),
            ('state', 'not in', ['completed']),
        ], order='period_start')

        return [{
            'id': p.id,
            'name': p.name,
            'plan_type': p.plan_type,
            'period_start': p.period_start.isoformat() if p.period_start else None,
            'period_end': p.period_end.isoformat() if p.period_end else None,
            'state': p.state,
            'item_count': len(p.item_ids),
            'my_items': len(p.item_ids.filtered(lambda i: i.assigned_partner_id.id == partner_id)),
        } for p in plans]

    @api.model
    def get_plan_details(self, plan_id, partner_id):
        """Get plan details for partner view"""
        plan = self.env['b2b.collaborative.plan'].browse(plan_id)

        if not plan.exists():
            return {'error': 'Plan not found'}

        if partner_id not in plan.partner_ids.ids:
            return {'error': 'Access denied'}

        return {
            'id': plan.id,
            'name': plan.name,
            'plan_type': plan.plan_type,
            'description': plan.description,
            'objectives': plan.objectives,
            'period_start': plan.period_start.isoformat() if plan.period_start else None,
            'period_end': plan.period_end.isoformat() if plan.period_end else None,
            'state': plan.state,
            'items': [{
                'id': i.id,
                'sequence': i.sequence,
                'product_name': i.product_id.name if i.product_id else None,
                'description': i.description,
                'planned_qty': i.planned_qty,
                'partner_committed_qty': i.partner_committed_qty,
                'target_date': i.target_date.isoformat() if i.target_date else None,
                'assigned_to_me': i.assigned_partner_id.id == partner_id,
                'item_state': i.item_state,
            } for i in plan.item_ids],
            'comments': [{
                'id': c.id,
                'author': c.partner_id.name if c.partner_id else c.user_id.name,
                'is_mine': c.partner_id.id == partner_id,
                'comment': c.comment,
                'comment_type': c.comment_type,
                'date': c.create_date.isoformat() if c.create_date else None,
                'replies': [{
                    'id': r.id,
                    'author': r.partner_id.name if r.partner_id else r.user_id.name,
                    'comment': r.comment,
                    'date': r.create_date.isoformat() if r.create_date else None,
                } for r in c.reply_ids],
            } for c in plan.comment_ids.filtered(lambda c: not c.parent_id)],
        }

    @api.model
    def commit_to_item(self, item_id, committed_qty, partner_id):
        """Partner commits to plan item"""
        item = self.env['b2b.collaborative.plan.item'].browse(item_id)

        if not item.exists():
            return {'success': False, 'error': 'Item not found'}

        if item.assigned_partner_id.id != partner_id:
            return {'success': False, 'error': 'Not assigned to this partner'}

        item.write({
            'partner_committed_qty': committed_qty,
            'item_state': 'in_progress' if committed_qty > 0 else 'pending',
        })

        return {'success': True}

    @api.model
    def add_comment(self, plan_id, partner_id, comment_text, comment_type='general', parent_id=None):
        """Add comment to plan"""
        plan = self.env['b2b.collaborative.plan'].browse(plan_id)

        if not plan.exists() or partner_id not in plan.partner_ids.ids:
            return {'success': False, 'error': 'Access denied'}

        comment = self.env['b2b.plan.comment'].create({
            'plan_id': plan_id,
            'partner_id': partner_id,
            'comment': comment_text,
            'comment_type': comment_type,
            'parent_id': parent_id,
        })

        return {'success': True, 'comment_id': comment.id}
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// Collaborative Planning Interface
// odoo/addons/smart_dairy_b2b/static/src/js/collaborative_planning.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class CollaborativePlanning extends Component {
    static template = "smart_dairy_b2b.CollaborativePlanning";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            plans: [],
            selectedPlan: null,
            newComment: "",
            commentType: "general",
            loading: true,
        });

        onWillStart(async () => {
            await this.loadPlans();
        });
    }

    async loadPlans() {
        try {
            this.state.plans = await this.orm.call(
                "b2b.collaborative.planning.service",
                "get_partner_plans",
                [this.props.partnerId]
            );
        } finally {
            this.state.loading = false;
        }
    }

    async selectPlan(planId) {
        this.state.loading = true;
        try {
            this.state.selectedPlan = await this.orm.call(
                "b2b.collaborative.planning.service",
                "get_plan_details",
                [planId, this.props.partnerId]
            );
        } finally {
            this.state.loading = false;
        }
    }

    async commitToItem(itemId, event) {
        const committedQty = parseFloat(event.target.value) || 0;

        const result = await this.orm.call(
            "b2b.collaborative.planning.service",
            "commit_to_item",
            [itemId, committedQty, this.props.partnerId]
        );

        if (result.success) {
            this.notification.add("Commitment updated", { type: "success" });
            // Update local state
            const item = this.state.selectedPlan.items.find(i => i.id === itemId);
            if (item) {
                item.partner_committed_qty = committedQty;
            }
        } else {
            this.notification.add(result.error, { type: "danger" });
        }
    }

    async addComment() {
        if (!this.state.newComment.trim()) return;

        const result = await this.orm.call(
            "b2b.collaborative.planning.service",
            "add_comment",
            [
                this.state.selectedPlan.id,
                this.props.partnerId,
                this.state.newComment,
                this.state.commentType,
            ]
        );

        if (result.success) {
            this.state.newComment = "";
            await this.selectPlan(this.state.selectedPlan.id);
            this.notification.add("Comment added", { type: "success" });
        }
    }

    getPlanTypeLabel(type) {
        const labels = {
            production: "Production Planning",
            inventory: "Inventory Planning",
            promotion: "Promotional Planning",
            new_product: "New Product Launch",
        };
        return labels[type] || type;
    }

    closePlanDetail() {
        this.state.selectedPlan = null;
    }
}
```

---

### Day 635 (Day 5): Partner Performance Dashboard

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_partner_performance.py
from odoo import models, fields, api
from datetime import date, timedelta

class B2BPartnerPerformance(models.Model):
    _name = 'b2b.partner.performance'
    _description = 'Partner Performance Metrics'

    partner_id = fields.Many2one('b2b.partner', string='Partner', required=True, ondelete='cascade')

    # Period
    period_start = fields.Date(string='Period Start', required=True)
    period_end = fields.Date(string='Period End', required=True)
    period_type = fields.Selection([
        ('weekly', 'Weekly'),
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
        ('yearly', 'Yearly'),
    ], string='Period Type', default='monthly')

    # Delivery Metrics
    total_deliveries = fields.Integer(string='Total Deliveries')
    on_time_deliveries = fields.Integer(string='On-Time Deliveries')
    late_deliveries = fields.Integer(string='Late Deliveries')
    on_time_rate = fields.Float(string='On-Time Rate %', compute='_compute_on_time_rate', store=True)

    # Quality Metrics
    total_items_received = fields.Integer(string='Items Received')
    defective_items = fields.Integer(string='Defective Items')
    quality_rate = fields.Float(string='Quality Rate %', compute='_compute_quality_rate', store=True)

    # Order Metrics
    total_orders = fields.Integer(string='Total Orders')
    total_order_value = fields.Monetary(string='Total Order Value')
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    average_order_value = fields.Monetary(string='Average Order Value', compute='_compute_avg_order')

    # Response Metrics
    avg_quote_response_hours = fields.Float(string='Avg Quote Response (Hours)')
    avg_order_fulfillment_days = fields.Float(string='Avg Fulfillment (Days)')

    # Overall Score
    overall_score = fields.Float(string='Overall Score', compute='_compute_overall_score', store=True)

    @api.depends('total_deliveries', 'on_time_deliveries')
    def _compute_on_time_rate(self):
        for rec in self:
            if rec.total_deliveries > 0:
                rec.on_time_rate = (rec.on_time_deliveries / rec.total_deliveries) * 100
            else:
                rec.on_time_rate = 0

    @api.depends('total_items_received', 'defective_items')
    def _compute_quality_rate(self):
        for rec in self:
            if rec.total_items_received > 0:
                rec.quality_rate = ((rec.total_items_received - rec.defective_items) / rec.total_items_received) * 100
            else:
                rec.quality_rate = 0

    @api.depends('total_orders', 'total_order_value')
    def _compute_avg_order(self):
        for rec in self:
            if rec.total_orders > 0:
                rec.average_order_value = rec.total_order_value / rec.total_orders
            else:
                rec.average_order_value = 0

    @api.depends('on_time_rate', 'quality_rate')
    def _compute_overall_score(self):
        for rec in self:
            # Weighted score: 50% on-time, 50% quality
            rec.overall_score = (rec.on_time_rate * 0.5) + (rec.quality_rate * 0.5)


class B2BPartnerPerformanceHistory(models.Model):
    _name = 'b2b.partner.performance.history'
    _description = 'Partner Performance History'
    _order = 'date desc'

    partner_id = fields.Many2one('b2b.partner', string='Partner', required=True, ondelete='cascade')
    date = fields.Date(string='Date', required=True)
    score = fields.Float(string='Score')
    on_time_rate = fields.Float(string='On-Time Rate')
    quality_rate = fields.Float(string='Quality Rate')


class B2BPartnerScorecard(models.AbstractModel):
    _name = 'b2b.partner.scorecard'
    _description = 'Partner Scorecard Service'

    @api.model
    def calculate_scorecard(self, partner_id, period_start=None, period_end=None):
        """Calculate comprehensive scorecard for partner"""
        partner = self.env['b2b.partner'].browse(partner_id)

        if not period_end:
            period_end = date.today()
        if not period_start:
            period_start = period_end - timedelta(days=90)

        # Get purchase orders in period
        orders = self.env['purchase.order'].search([
            ('partner_id', '=', partner.partner_id.id),
            ('date_order', '>=', period_start),
            ('date_order', '<=', period_end),
            ('state', 'in', ['purchase', 'done']),
        ])

        # Get deliveries (stock pickings)
        pickings = self.env['stock.picking'].search([
            ('partner_id', '=', partner.partner_id.id),
            ('picking_type_code', '=', 'incoming'),
            ('date_done', '>=', period_start),
            ('date_done', '<=', period_end),
            ('state', '=', 'done'),
        ])

        # Calculate metrics
        total_deliveries = len(pickings)
        on_time_deliveries = 0

        for picking in pickings:
            scheduled = picking.scheduled_date
            actual = picking.date_done
            if scheduled and actual and actual <= scheduled:
                on_time_deliveries += 1

        # Quality (from quality checks if available)
        quality_rate = 95.0  # Default if no quality module

        scorecard = {
            'partner_id': partner_id,
            'partner_name': partner.name,
            'period_start': period_start.isoformat(),
            'period_end': period_end.isoformat(),
            'metrics': {
                'delivery': {
                    'total': total_deliveries,
                    'on_time': on_time_deliveries,
                    'on_time_rate': (on_time_deliveries / total_deliveries * 100) if total_deliveries > 0 else 0,
                },
                'quality': {
                    'rate': quality_rate,
                },
                'orders': {
                    'total': len(orders),
                    'total_value': sum(orders.mapped('amount_total')),
                },
            },
            'overall_score': 0,
        }

        # Calculate overall score
        delivery_score = scorecard['metrics']['delivery']['on_time_rate']
        scorecard['overall_score'] = (delivery_score * 0.6) + (quality_rate * 0.4)

        return scorecard

    @api.model
    def get_performance_trend(self, partner_id, months=12):
        """Get monthly performance trend"""
        history = self.env['b2b.partner.performance.history'].search([
            ('partner_id', '=', partner_id),
        ], order='date desc', limit=months)

        return [{
            'date': h.date.strftime('%Y-%m'),
            'score': h.score,
            'on_time_rate': h.on_time_rate,
            'quality_rate': h.quality_rate,
        } for h in reversed(history)]
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/services/performance_api.py
from odoo import models, api

class PerformanceAPI(models.AbstractModel):
    _name = 'b2b.performance.api'
    _description = 'Performance API'

    @api.model
    def get_partner_scorecard(self, partner_id):
        """Get scorecard for partner portal"""
        scorecard = self.env['b2b.partner.scorecard'].calculate_scorecard(partner_id)
        trend = self.env['b2b.partner.scorecard'].get_performance_trend(partner_id)

        return {
            'scorecard': scorecard,
            'trend': trend,
            'benchmarks': self._get_benchmarks(),
        }

    def _get_benchmarks(self):
        """Get industry benchmarks"""
        return {
            'on_time_delivery': {
                'excellent': 95,
                'good': 90,
                'acceptable': 85,
                'poor': 80,
            },
            'quality': {
                'excellent': 99,
                'good': 97,
                'acceptable': 95,
                'poor': 90,
            },
        }

    @api.model
    def get_comparison_data(self, partner_id):
        """Get comparison with other suppliers"""
        partner = self.env['b2b.partner'].browse(partner_id)

        # Get all suppliers of same type
        peers = self.env['b2b.partner'].search([
            ('partner_type', '=', partner.partner_type),
            ('state', '=', 'active'),
        ])

        if len(peers) < 3:
            return {'available': False}

        # Calculate average scores
        scores = [p.performance_score for p in peers]

        return {
            'available': True,
            'your_score': partner.performance_score,
            'peer_average': sum(scores) / len(scores),
            'top_quartile': sorted(scores, reverse=True)[len(scores) // 4] if len(scores) >= 4 else max(scores),
            'percentile': sum(1 for s in scores if s < partner.performance_score) / len(scores) * 100,
        }
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// Partner Performance Dashboard
// odoo/addons/smart_dairy_b2b/static/src/js/partner_performance.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class PartnerPerformanceDashboard extends Component {
    static template = "smart_dairy_b2b.PartnerPerformanceDashboard";

    setup() {
        this.orm = useService("orm");

        this.state = useState({
            scorecard: null,
            trend: [],
            comparison: null,
            loading: true,
        });

        onWillStart(async () => {
            await this.loadPerformanceData();
        });

        onMounted(() => {
            this.renderTrendChart();
        });
    }

    async loadPerformanceData() {
        try {
            const data = await this.orm.call(
                "b2b.performance.api",
                "get_partner_scorecard",
                [this.props.partnerId]
            );

            this.state.scorecard = data.scorecard;
            this.state.trend = data.trend;

            // Load comparison data
            this.state.comparison = await this.orm.call(
                "b2b.performance.api",
                "get_comparison_data",
                [this.props.partnerId]
            );
        } finally {
            this.state.loading = false;
        }
    }

    renderTrendChart() {
        if (!this.state.trend.length) return;

        // Use Chart.js or similar library
        const ctx = document.getElementById("trendChart");
        if (ctx && window.Chart) {
            new window.Chart(ctx, {
                type: "line",
                data: {
                    labels: this.state.trend.map(t => t.date),
                    datasets: [
                        {
                            label: "Overall Score",
                            data: this.state.trend.map(t => t.score),
                            borderColor: "#4CAF50",
                            fill: false,
                        },
                        {
                            label: "On-Time Rate",
                            data: this.state.trend.map(t => t.on_time_rate),
                            borderColor: "#2196F3",
                            fill: false,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                        },
                    },
                },
            });
        }
    }

    getScoreClass(score) {
        if (score >= 95) return "excellent";
        if (score >= 90) return "good";
        if (score >= 85) return "acceptable";
        return "needs-improvement";
    }

    getScoreLabel(score) {
        if (score >= 95) return "Excellent";
        if (score >= 90) return "Good";
        if (score >= 85) return "Acceptable";
        return "Needs Improvement";
    }
}
```

---

### Days 636-640: Summary

**Day 636**: Document Repository - File upload service, document categorization, sharing permissions, version control
**Day 637**: Communication Hub - Message system, notification preferences, chat interface, message threading
**Day 638**: Integration APIs - Webhook handlers, external system connectors, API documentation, authentication
**Day 639**: Unit Tests - Model tests, API tests, service tests, integration tests
**Day 640**: Documentation - User guides, API documentation, UAT preparation, final polish

---

## 4. Technical Specifications

### 4.1 Partner Portal Access

```python
PARTNER_ROLES = {
    'admin': ['view_orders', 'manage_forecasts', 'upload_documents', 'view_performance', 'send_messages'],
    'manager': ['view_orders', 'manage_forecasts', 'view_performance', 'send_messages'],
    'viewer': ['view_orders', 'view_performance'],
}
```

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/partner/portal` | GET | Portal dashboard |
| `/api/v1/partner/profile` | GET/PUT | Profile management |
| `/api/v1/partner/orders` | GET | Order list |
| `/api/v1/partner/forecasts` | GET/POST | Forecast management |
| `/api/v1/partner/performance` | GET | Performance scorecard |
| `/api/v1/partner/documents` | GET/POST | Document management |
| `/api/v1/partner/messages` | GET/POST | Communication |

### 4.3 Webhook Events

| Event | Trigger | Payload |
|-------|---------|---------|
| `order.created` | New PO created | Order details |
| `forecast.shared` | Forecast shared | Forecast ID |
| `plan.updated` | Plan changed | Plan details |
| `payment.sent` | Payment processed | Payment details |

---

## 5. Requirements Traceability

| Req ID | Description | Implementation | Status |
|--------|-------------|----------------|--------|
| B2B-PARTNER-001 | Partner Portal | b2b.partner, portal controllers | Implemented |
| B2B-PARTNER-002 | Supplier Self-Service | b2b.supplier.profile | Implemented |
| B2B-PARTNER-003 | Forecast Sharing | b2b.forecast | Implemented |
| B2B-PARTNER-004 | Collaborative Planning | b2b.collaborative.plan | Implemented |
| B2B-PARTNER-005 | Performance Dashboard | b2b.partner.scorecard | Implemented |

---

## 6. Sign-off Checklist

- [ ] Partner Portal Framework complete
- [ ] Partner and User models working
- [ ] Supplier Self-Service functional
- [ ] Forecast Sharing system operational
- [ ] Collaborative Planning interface complete
- [ ] Performance Dashboard displaying metrics
- [ ] Document Repository working
- [ ] Communication Hub functional
- [ ] API endpoints tested
- [ ] Unit tests passing (>80%)

---

**Document End**

*Milestone 89: Partner Portal Integration*
*Days 631-640 | Phase 9: Commerce - B2B Portal Foundation*
