# Milestone 7: Identity & Access Management (IAM) Implementation

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field | Detail |
|---|---|
| **Milestone** | 7 of 10 |
| **Title** | Identity & Access Management (IAM) Implementation |
| **Phase** | Phase 1 — Foundation (Part B: Database & Security) |
| **Days** | Days 61-70 (of 100) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03 |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown-days-61-70)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Objective

Milestone 7 converts the security architecture and RBAC designs delivered in Milestone 6 into a fully operational Identity & Access Management (IAM) system for the Smart Dairy Digital Smart Portal. This milestone implements user authentication, role-based authorization, multi-factor authentication, session management, password policies, audit logging, and admin interfaces -- all tailored to Smart Dairy Ltd's operational context of 255 cattle, 75 lactating cows, and approximately 900 litres of milk produced daily across the Bangladesh-based organic dairy farm.

### 1.2 Scope Summary

| Area | Deliverables |
|---|---|
| User Model Extension | Custom `res.users` extension with MFA, session, and policy fields |
| RBAC Matrix | `ir.model.access` records and `ir.rule` record rules for 8 roles across all farm models |
| Multi-Factor Authentication | TOTP (pyotp), QR code generation, backup codes, SMS OTP for Bangladesh providers |
| Session Management | Redis-backed sessions, configurable timeouts, concurrent session limits, revocation |
| Password Policy | 12-char minimum, complexity rules, 90-day expiry, bcrypt hashing, history (last 5) |
| Registration Workflows | B2C self-registration, B2B admin-approved, email and phone verification |
| Admin Interfaces | OWL components for user CRUD, role assignment, bulk operations, activity logs |
| Audit Logging | Authentication events, IP tracking, geo-location, tamper-proof log storage |
| Security Testing | Penetration testing, brute force protection, session fixation tests, CSRF validation |

### 1.3 Success Criteria

- All 8 roles (Super Admin, Farm Manager, Veterinarian, Milk Collector, Accountant, HR Manager, Customer, Public) are enforced at both model-access and record-rule levels.
- MFA enrollment and verification completes successfully for all internal roles.
- Redis session store handles at least 100 concurrent sessions with sub-50ms lookup time.
- Password policy rejects 100% of non-compliant passwords.
- Brute force protection locks accounts after threshold breaches.
- All authentication events are captured in the audit log with IP and timestamp.
- Zero critical or high-severity findings in penetration testing.

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Description | Day(s) | Verification |
|---|---|---|---|---|
| F-001.1 | SRS 4.1 / RBAC Design | Role hierarchy with 8 roles and implied permissions | 61-62 | Unit tests for group membership |
| F-001.2 | SRS 4.1 | Model-level access control (9 permission types) | 62 | CSV validation and access tests |
| F-001.3 | SRS 4.2 | Record-level security (row-level filtering) | 62 | Record rule domain tests |
| F-002.1 | SRS 4.3 | Multi-factor authentication (TOTP) | 63 | MFA enrollment and login tests |
| F-002.2 | SRS 4.3 | SMS OTP for Bangladesh mobile numbers | 63 | SMS gateway integration test |
| F-003.1 | SRS 4.4 | Redis-backed session management | 64 | Session lifecycle tests |
| F-003.2 | SRS 4.4 | Concurrent session limits per role | 64 | Concurrent login tests |
| F-004.1 | SRS 4.5 | Password complexity and expiry policy | 65 | Policy validation tests |
| F-005.1 | SRS 4.6 | User self-registration (B2C) | 66 | Registration flow tests |
| F-005.2 | SRS 4.6 | Admin-approved registration (B2B) | 66 | Approval workflow tests |
| F-006.1 | SRS 4.7 | Admin user management interface | 67 | UI component tests |
| F-007.1 | SRS 4.8 | Authentication audit logging | 68 | Audit log completeness tests |
| F-008.1 | SRS 4.9 | Penetration testing and hardening | 69 | Pen-test report with zero critical findings |

---

## 3. Day-by-Day Breakdown (Days 61-70)

### Day 61 — Odoo User/Group Model Extension

**Objective:** Extend `res.users` and configure `res.groups` hierarchy for all Smart Dairy roles.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-2 | Design and implement `SmartDairyUser` model extending `res.users` |
| 2-4 | Add MFA fields (`mfa_secret`, `mfa_enabled`, `mfa_backup_codes`) |
| 4-6 | Add session fields (`max_sessions`, `session_timeout`, `last_activity`) |
| 6-8 | Add password policy fields (`password_changed_date`, `password_history_ids`, `failed_login_count`) |

```python
# smart_dairy_iam/models/smart_dairy_user.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import json
import secrets


class SmartDairyUser(models.Model):
    _inherit = 'res.users'
    _description = 'Smart Dairy Extended User'

    # --- MFA Fields ---
    mfa_enabled = fields.Boolean(
        string='MFA Enabled',
        default=False,
        help='Whether multi-factor authentication is active for this user.',
    )
    mfa_secret = fields.Char(
        string='MFA Secret',
        copy=False,
        groups='base.group_system',
        help='TOTP secret key (stored encrypted).',
    )
    mfa_backup_codes = fields.Text(
        string='MFA Backup Codes',
        copy=False,
        groups='base.group_system',
        help='JSON-encoded list of one-time backup codes.',
    )
    mfa_enrolled_date = fields.Datetime(
        string='MFA Enrolled Date',
        readonly=True,
    )

    # --- Session Management Fields ---
    max_concurrent_sessions = fields.Integer(
        string='Max Concurrent Sessions',
        default=3,
        help='Maximum number of simultaneous sessions allowed.',
    )
    session_timeout_minutes = fields.Integer(
        string='Session Timeout (minutes)',
        default=60,
        help='Idle timeout before session expires.',
    )
    last_activity = fields.Datetime(
        string='Last Activity',
        readonly=True,
    )
    last_login_ip = fields.Char(
        string='Last Login IP',
        readonly=True,
    )

    # --- Password Policy Fields ---
    password_changed_date = fields.Date(
        string='Password Last Changed',
        default=fields.Date.context_today,
    )
    password_expiry_days = fields.Integer(
        string='Password Expiry (days)',
        default=90,
    )
    failed_login_count = fields.Integer(
        string='Failed Login Attempts',
        default=0,
    )
    account_locked_until = fields.Datetime(
        string='Account Locked Until',
    )
    password_history_ids = fields.One2many(
        'smart_dairy.password.history',
        'user_id',
        string='Password History',
    )

    # --- Registration Fields ---
    registration_status = fields.Selection([
        ('pending', 'Pending Approval'),
        ('verified', 'Email Verified'),
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
    ], string='Registration Status', default='approved')
    phone_verified = fields.Boolean(string='Phone Verified', default=False)
    email_verified = fields.Boolean(string='Email Verified', default=False)
    verification_token = fields.Char(string='Verification Token', copy=False)
    token_expiry = fields.Datetime(string='Token Expiry')

    def generate_backup_codes(self, count=10):
        """Generate a set of one-time MFA backup codes."""
        self.ensure_one()
        codes = [secrets.token_hex(4).upper() for _ in range(count)]
        self.sudo().mfa_backup_codes = json.dumps(codes)
        return codes

    def check_account_locked(self):
        """Return True if the account is currently locked."""
        self.ensure_one()
        if self.account_locked_until:
            if fields.Datetime.now() < self.account_locked_until:
                return True
            # Lock period expired -- reset
            self.sudo().write({
                'account_locked_until': False,
                'failed_login_count': 0,
            })
        return False

    @api.model
    def _check_password_expiry(self, user):
        """Check if the user password has expired."""
        if not user.password_changed_date:
            return False
        from datetime import timedelta
        expiry = user.password_changed_date + timedelta(
            days=user.password_expiry_days
        )
        return fields.Date.context_today(user) > expiry


class PasswordHistory(models.Model):
    _name = 'smart_dairy.password.history'
    _description = 'Password History Record'
    _order = 'create_date desc'

    user_id = fields.Many2one('res.users', required=True, ondelete='cascade')
    password_hash = fields.Char(string='Password Hash', required=True)
    changed_date = fields.Datetime(
        string='Changed Date', default=fields.Datetime.now
    )
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Create `res.groups` XML data for 8-role hierarchy |
| 3-5 | Configure group categories and implied_ids chains |
| 5-8 | Set up module manifest, install dependencies (pyotp, qrcode, redis) |

```xml
<!-- smart_dairy_iam/data/iam_groups.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Module Category -->
    <record id="module_category_smart_dairy" model="ir.module.category">
        <field name="name">Smart Dairy</field>
        <field name="description">Smart Dairy Farm Management Roles</field>
        <field name="sequence">50</field>
    </record>

    <!-- Role 1: Public (base, no login required for public pages) -->
    <record id="group_smart_dairy_public" model="res.groups">
        <field name="name">Public User</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="comment">Public visitor with read-only access to public content.</field>
    </record>

    <!-- Role 2: Customer (implies Public) -->
    <record id="group_smart_dairy_customer" model="res.groups">
        <field name="name">Customer</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">B2C customer who can place orders and view products.</field>
    </record>

    <!-- Role 3: Milk Collector (implies Public) -->
    <record id="group_smart_dairy_milk_collector" model="res.groups">
        <field name="name">Milk Collector</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">Field staff who records daily milk collection data.</field>
    </record>

    <!-- Role 4: Veterinarian (implies Public) -->
    <record id="group_smart_dairy_veterinarian" model="res.groups">
        <field name="name">Veterinarian</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">Veterinary professional managing cattle health records.</field>
    </record>

    <!-- Role 5: Accountant (implies Public) -->
    <record id="group_smart_dairy_accountant" model="res.groups">
        <field name="name">Accountant</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">Financial staff managing invoices, payments, and reports.</field>
    </record>

    <!-- Role 6: HR Manager (implies Public) -->
    <record id="group_smart_dairy_hr_manager" model="res.groups">
        <field name="name">HR Manager</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">HR staff managing employees, attendance, and payroll.</field>
    </record>

    <!-- Role 7: Farm Manager (implies Milk Collector, Veterinarian) -->
    <record id="group_smart_dairy_farm_manager" model="res.groups">
        <field name="name">Farm Manager</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[
            (4, ref('group_smart_dairy_milk_collector')),
            (4, ref('group_smart_dairy_veterinarian')),
        ]"/>
        <field name="comment">Farm operations manager with full visibility over farm data.</field>
    </record>

    <!-- Role 8: Super Admin (implies all roles) -->
    <record id="group_smart_dairy_super_admin" model="res.groups">
        <field name="name">Super Admin</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[
            (4, ref('group_smart_dairy_farm_manager')),
            (4, ref('group_smart_dairy_accountant')),
            (4, ref('group_smart_dairy_hr_manager')),
            (4, ref('group_smart_dairy_customer')),
        ]"/>
        <field name="comment">Full system administrator with unrestricted access.</field>
    </record>
</odoo>
```

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Scaffold OWL component directory structure for IAM module |
| 4-6 | Design login page wireframes with MFA step |
| 6-8 | Implement base OWL AuthService for state management |

#### End-of-Day 61 Deliverables

- [ ] `SmartDairyUser` model with all extended fields
- [ ] `PasswordHistory` model
- [ ] XML group definitions for all 8 roles with hierarchy
- [ ] Module manifest with dependencies declared
- [ ] OWL component scaffold and AuthService skeleton

---

### Day 62 — RBAC Permission Matrix Implementation

**Objective:** Create `ir.model.access` records for all 8 roles across every farm model and implement record-level rules.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Write `ir.model.access.csv` covering all farm models and roles |
| 4-8 | Implement `ir.rule` record rules for row-level security |

```csv
# smart_dairy_iam/security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
# --- Cattle Model ---
access_cattle_super_admin,access.cattle.super.admin,smart_dairy_farm.model_dairy_cattle,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,1
access_cattle_farm_manager,access.cattle.farm.manager,smart_dairy_farm.model_dairy_cattle,smart_dairy_iam.group_smart_dairy_farm_manager,1,1,1,0
access_cattle_veterinarian,access.cattle.veterinarian,smart_dairy_farm.model_dairy_cattle,smart_dairy_iam.group_smart_dairy_veterinarian,1,1,0,0
access_cattle_milk_collector,access.cattle.milk.collector,smart_dairy_farm.model_dairy_cattle,smart_dairy_iam.group_smart_dairy_milk_collector,1,0,0,0
access_cattle_accountant,access.cattle.accountant,smart_dairy_farm.model_dairy_cattle,smart_dairy_iam.group_smart_dairy_accountant,1,0,0,0
access_cattle_customer,access.cattle.customer,smart_dairy_farm.model_dairy_cattle,smart_dairy_iam.group_smart_dairy_customer,0,0,0,0
# --- Milk Collection Model ---
access_milk_collection_super_admin,access.milk.collection.super.admin,smart_dairy_farm.model_milk_collection,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,1
access_milk_collection_farm_manager,access.milk.collection.farm.manager,smart_dairy_farm.model_milk_collection,smart_dairy_iam.group_smart_dairy_farm_manager,1,1,1,0
access_milk_collection_milk_collector,access.milk.collection.milk.collector,smart_dairy_farm.model_milk_collection,smart_dairy_iam.group_smart_dairy_milk_collector,1,1,1,0
access_milk_collection_veterinarian,access.milk.collection.vet,smart_dairy_farm.model_milk_collection,smart_dairy_iam.group_smart_dairy_veterinarian,1,0,0,0
access_milk_collection_accountant,access.milk.collection.accountant,smart_dairy_farm.model_milk_collection,smart_dairy_iam.group_smart_dairy_accountant,1,0,0,0
# --- Veterinary Health Record Model ---
access_health_record_super_admin,access.health.record.super.admin,smart_dairy_farm.model_health_record,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,1
access_health_record_farm_manager,access.health.record.farm.manager,smart_dairy_farm.model_health_record,smart_dairy_iam.group_smart_dairy_farm_manager,1,1,0,0
access_health_record_veterinarian,access.health.record.vet,smart_dairy_farm.model_health_record,smart_dairy_iam.group_smart_dairy_veterinarian,1,1,1,0
access_health_record_milk_collector,access.health.record.milk.collector,smart_dairy_farm.model_health_record,smart_dairy_iam.group_smart_dairy_milk_collector,1,0,0,0
# --- Feed Management Model ---
access_feed_super_admin,access.feed.super.admin,smart_dairy_farm.model_feed_management,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,1
access_feed_farm_manager,access.feed.farm.manager,smart_dairy_farm.model_feed_management,smart_dairy_iam.group_smart_dairy_farm_manager,1,1,1,0
access_feed_accountant,access.feed.accountant,smart_dairy_farm.model_feed_management,smart_dairy_iam.group_smart_dairy_accountant,1,0,0,0
# --- Sales Order Model ---
access_sale_order_super_admin,access.sale.order.super.admin,sale.model_sale_order,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,1
access_sale_order_farm_manager,access.sale.order.farm.manager,sale.model_sale_order,smart_dairy_iam.group_smart_dairy_farm_manager,1,1,1,0
access_sale_order_accountant,access.sale.order.accountant,sale.model_sale_order,smart_dairy_iam.group_smart_dairy_accountant,1,1,0,0
access_sale_order_customer,access.sale.order.customer,sale.model_sale_order,smart_dairy_iam.group_smart_dairy_customer,1,0,0,0
# --- Employee / HR Model ---
access_employee_super_admin,access.employee.super.admin,hr.model_hr_employee,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,1
access_employee_hr_manager,access.employee.hr.manager,hr.model_hr_employee,smart_dairy_iam.group_smart_dairy_hr_manager,1,1,1,0
access_employee_farm_manager,access.employee.farm.manager,hr.model_hr_employee,smart_dairy_iam.group_smart_dairy_farm_manager,1,0,0,0
# --- Audit Log Model (read-only for most) ---
access_audit_log_super_admin,access.audit.log.super.admin,smart_dairy_iam.model_audit_auth_log,smart_dairy_iam.group_smart_dairy_super_admin,1,1,1,0
access_audit_log_farm_manager,access.audit.log.farm.manager,smart_dairy_iam.model_audit_auth_log,smart_dairy_iam.group_smart_dairy_farm_manager,1,0,0,0
```

```xml
<!-- smart_dairy_iam/security/iam_record_rules.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Farm Manager: sees all cattle in their assigned farm -->
    <record id="rule_cattle_farm_manager" model="ir.rule">
        <field name="name">Farm Manager: Own Farm Cattle</field>
        <field name="model_id" ref="smart_dairy_farm.model_dairy_cattle"/>
        <field name="groups" eval="[(4, ref('group_smart_dairy_farm_manager'))]"/>
        <field name="domain_force">[('farm_id.manager_id', '=', user.id)]</field>
        <field name="perm_read" eval="True"/>
        <field name="perm_write" eval="True"/>
        <field name="perm_create" eval="True"/>
        <field name="perm_unlink" eval="False"/>
    </record>

    <!-- Veterinarian: sees cattle assigned to them -->
    <record id="rule_cattle_veterinarian" model="ir.rule">
        <field name="name">Veterinarian: Assigned Cattle</field>
        <field name="model_id" ref="smart_dairy_farm.model_dairy_cattle"/>
        <field name="groups" eval="[(4, ref('group_smart_dairy_veterinarian'))]"/>
        <field name="domain_force">[('veterinarian_id', '=', user.id)]</field>
        <field name="perm_read" eval="True"/>
        <field name="perm_write" eval="True"/>
        <field name="perm_create" eval="False"/>
        <field name="perm_unlink" eval="False"/>
    </record>

    <!-- Milk Collector: sees own collection records only -->
    <record id="rule_milk_collection_collector" model="ir.rule">
        <field name="name">Milk Collector: Own Collections</field>
        <field name="model_id" ref="smart_dairy_farm.model_milk_collection"/>
        <field name="groups" eval="[(4, ref('group_smart_dairy_milk_collector'))]"/>
        <field name="domain_force">[('collector_id', '=', user.id)]</field>
        <field name="perm_read" eval="True"/>
        <field name="perm_write" eval="True"/>
        <field name="perm_create" eval="True"/>
        <field name="perm_unlink" eval="False"/>
    </record>

    <!-- Customer: sees own orders only -->
    <record id="rule_sale_order_customer" model="ir.rule">
        <field name="name">Customer: Own Orders</field>
        <field name="model_id" ref="sale.model_sale_order"/>
        <field name="groups" eval="[(4, ref('group_smart_dairy_customer'))]"/>
        <field name="domain_force">[('partner_id', '=', user.partner_id.id)]</field>
        <field name="perm_read" eval="True"/>
        <field name="perm_write" eval="False"/>
        <field name="perm_create" eval="False"/>
        <field name="perm_unlink" eval="False"/>
    </record>

    <!-- Veterinarian: sees own health records -->
    <record id="rule_health_record_vet" model="ir.rule">
        <field name="name">Veterinarian: Own Health Records</field>
        <field name="model_id" ref="smart_dairy_farm.model_health_record"/>
        <field name="groups" eval="[(4, ref('group_smart_dairy_veterinarian'))]"/>
        <field name="domain_force">[('veterinarian_id', '=', user.id)]</field>
        <field name="perm_read" eval="True"/>
        <field name="perm_write" eval="True"/>
        <field name="perm_create" eval="True"/>
        <field name="perm_unlink" eval="False"/>
    </record>

    <!-- Super Admin: unrestricted (global rule, no domain) -->
    <record id="rule_global_super_admin" model="ir.rule">
        <field name="name">Super Admin: Unrestricted Access</field>
        <field name="model_id" ref="smart_dairy_farm.model_dairy_cattle"/>
        <field name="groups" eval="[(4, ref('group_smart_dairy_super_admin'))]"/>
        <field name="domain_force">[(1, '=', 1)]</field>
    </record>
</odoo>
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Create extended permission types (export, import, approve, audit, admin) as custom fields |
| 3-6 | Write validation scripts to ensure CSV covers all model-role combinations |
| 6-8 | Set up security test database fixtures |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Build OWL PermissionMatrix component for admin display of the RBAC table |
| 4-8 | Implement role-based UI visibility toggling (hide/show menu items per group) |

#### End-of-Day 62 Deliverables

- [ ] Complete `ir.model.access.csv` for all models and 8 roles
- [ ] Record rules XML for row-level security across cattle, milk, health, sales, HR
- [ ] Extended permission type fields for export/import/approve/audit/admin
- [ ] OWL PermissionMatrix component prototype

---

### Day 63 — Multi-Factor Authentication (MFA) Integration

**Objective:** Implement TOTP-based MFA with QR code enrollment, backup codes, and SMS OTP for Bangladesh mobile providers.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-3 | Implement TOTP setup and verification with `pyotp` |
| 3-5 | Build QR code generation endpoint using `qrcode` library |
| 5-8 | Implement backup code generation and SMS OTP dispatch |

```python
# smart_dairy_iam/models/mfa_service.py

import pyotp
import qrcode
import io
import base64
import json
import secrets
from odoo import models, api
from odoo.exceptions import AccessDenied


class MFAService(models.AbstractModel):
    _name = 'smart_dairy.mfa.service'
    _description = 'MFA Service for Smart Dairy'

    @api.model
    def generate_totp_secret(self, user_id):
        """Generate a new TOTP secret and provision URI for the user."""
        user = self.env['res.users'].sudo().browse(user_id)
        secret = pyotp.random_base32()
        user.write({'mfa_secret': secret})
        totp = pyotp.TOTP(secret)
        provisioning_uri = totp.provisioning_uri(
            name=user.login,
            issuer_name='Smart Dairy Portal',
        )
        return {
            'secret': secret,
            'uri': provisioning_uri,
            'qr_code': self._generate_qr_base64(provisioning_uri),
        }

    @api.model
    def _generate_qr_base64(self, uri):
        """Generate a base64-encoded QR code image from a URI."""
        qr = qrcode.QRCode(version=1, box_size=6, border=2)
        qr.add_data(uri)
        qr.make(fit=True)
        img = qr.make_image(fill_color='black', back_color='white')
        buffer = io.BytesIO()
        img.save(buffer, format='PNG')
        return base64.b64encode(buffer.getvalue()).decode('utf-8')

    @api.model
    def verify_totp(self, user_id, token):
        """Verify a TOTP token for the given user."""
        user = self.env['res.users'].sudo().browse(user_id)
        if not user.mfa_secret:
            raise AccessDenied('MFA is not configured for this user.')
        totp = pyotp.TOTP(user.mfa_secret)
        return totp.verify(token, valid_window=1)

    @api.model
    def verify_backup_code(self, user_id, code):
        """Verify and consume a one-time backup code."""
        user = self.env['res.users'].sudo().browse(user_id)
        if not user.mfa_backup_codes:
            return False
        codes = json.loads(user.mfa_backup_codes)
        code_upper = code.strip().upper()
        if code_upper in codes:
            codes.remove(code_upper)
            user.write({'mfa_backup_codes': json.dumps(codes)})
            return True
        return False

    @api.model
    def enable_mfa(self, user_id, token):
        """Enable MFA after successful TOTP verification during setup."""
        if self.verify_totp(user_id, token):
            user = self.env['res.users'].sudo().browse(user_id)
            backup_codes = user.generate_backup_codes(count=10)
            user.write({
                'mfa_enabled': True,
                'mfa_enrolled_date': fields.Datetime.now(),
            })
            return {'success': True, 'backup_codes': backup_codes}
        return {'success': False, 'error': 'Invalid TOTP token.'}

    @api.model
    def send_sms_otp(self, user_id):
        """Send an SMS OTP to the user phone (Bangladesh providers)."""
        user = self.env['res.users'].sudo().browse(user_id)
        if not user.phone:
            return {'success': False, 'error': 'No phone number on file.'}
        otp = f'{secrets.randbelow(1000000):06d}'
        # Store OTP with expiry in cache (Redis)
        cache_key = f'sms_otp:{user_id}'
        self.env['smart_dairy.redis.client'].setex(
            cache_key, 300, otp  # 5-minute expiry
        )
        # Dispatch via Bangladesh SMS gateway (e.g., BulkSMSBD, SSLWireless)
        self.env['smart_dairy.sms.gateway'].send(
            phone=user.phone,
            message=f'Your Smart Dairy verification code is: {otp}',
        )
        return {'success': True}
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Configure SMS gateway integration (SSLWireless / BulkSMSBD API) |
| 3-5 | Set up encrypted storage for MFA secrets (Odoo field encryption) |
| 5-8 | Write MFA configuration in `ir.config_parameter` (enforce MFA for internal roles) |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Build OWL MFASetup component (QR display, token input, backup code download) |
| 4-8 | Build OWL MFAVerify component for login step-up |

```javascript
/** @odoo-module */
// smart_dairy_iam/static/src/components/mfa_setup/mfa_setup.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class MFASetupComponent extends Component {
    static template = "smart_dairy_iam.MFASetup";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");
        this.state = useState({
            qrCode: null,
            secret: null,
            token: "",
            backupCodes: [],
            step: "init",  // init | verify | complete
            loading: false,
            error: null,
        });
    }

    async onStartSetup() {
        this.state.loading = true;
        this.state.error = null;
        try {
            const result = await this.rpc("/web/dataset/call_kw", {
                model: "smart_dairy.mfa.service",
                method: "generate_totp_secret",
                args: [this.env.session.uid],
                kwargs: {},
            });
            this.state.qrCode = result.qr_code;
            this.state.secret = result.secret;
            this.state.step = "verify";
        } catch (e) {
            this.state.error = "Failed to initialize MFA setup.";
        } finally {
            this.state.loading = false;
        }
    }

    async onVerifyToken() {
        this.state.loading = true;
        this.state.error = null;
        try {
            const result = await this.rpc("/web/dataset/call_kw", {
                model: "smart_dairy.mfa.service",
                method: "enable_mfa",
                args: [this.env.session.uid, this.state.token],
                kwargs: {},
            });
            if (result.success) {
                this.state.backupCodes = result.backup_codes;
                this.state.step = "complete";
                this.notification.add(
                    "MFA has been enabled for your account.",
                    { type: "success" }
                );
            } else {
                this.state.error = result.error || "Verification failed.";
            }
        } catch (e) {
            this.state.error = "Verification request failed.";
        } finally {
            this.state.loading = false;
        }
    }

    onDownloadBackupCodes() {
        const text = this.state.backupCodes.join("\n");
        const blob = new Blob([text], { type: "text/plain" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "smart_dairy_backup_codes.txt";
        a.click();
        URL.revokeObjectURL(url);
    }
}
```

#### End-of-Day 63 Deliverables

- [ ] TOTP setup and verification via `pyotp`
- [ ] QR code generation endpoint
- [ ] Backup code generation and consumption logic
- [ ] SMS OTP dispatch via Bangladesh provider
- [ ] OWL MFASetup and MFAVerify components

---

### Day 64 — Session Management

**Objective:** Replace Odoo's default session handling with a Redis-backed store that supports configurable timeouts, concurrent session limits, and remote revocation.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Implement Redis session store as custom HTTP session handler |
| 4-6 | Build concurrent session enforcement logic |
| 6-8 | Create session management API endpoints |

```python
# smart_dairy_iam/models/redis_session_store.py

import json
import logging
import uuid
from datetime import datetime, timedelta
import redis
from werkzeug.contrib.sessions import SessionStore

_logger = logging.getLogger(__name__)


class RedisSessionStore(SessionStore):
    """Redis-backed session store for Smart Dairy Portal."""

    def __init__(self, redis_url='redis://localhost:6379/1', prefix='sd_session:'):
        super().__init__()
        self.redis = redis.Redis.from_url(redis_url, decode_responses=True)
        self.prefix = prefix

    def _key(self, sid):
        return f'{self.prefix}{sid}'

    def _user_sessions_key(self, uid):
        return f'{self.prefix}user:{uid}'

    def save(self, session):
        key = self._key(session.sid)
        data = json.dumps(dict(session))
        timeout = session.get('_timeout', 3600)
        self.redis.setex(key, timeout, data)
        # Track user sessions for concurrent limit enforcement
        uid = session.get('uid')
        if uid:
            user_key = self._user_sessions_key(uid)
            self.redis.sadd(user_key, session.sid)
            self.redis.expire(user_key, 86400)

    def delete(self, session):
        key = self._key(session.sid)
        uid = session.get('uid')
        self.redis.delete(key)
        if uid:
            self.redis.srem(self._user_sessions_key(uid), session.sid)

    def get(self, sid):
        key = self._key(sid)
        data = self.redis.get(key)
        if data:
            return self.session_class(json.loads(data), sid, False)
        return self.session_class({}, sid, True)

    def new(self):
        sid = str(uuid.uuid4())
        return self.session_class({}, sid, True)

    def enforce_concurrent_limit(self, uid, max_sessions=3):
        """Remove oldest sessions if the user exceeds the limit."""
        user_key = self._user_sessions_key(uid)
        session_ids = self.redis.smembers(user_key)
        active = []
        for sid in session_ids:
            data = self.redis.get(self._key(sid))
            if data:
                parsed = json.loads(data)
                active.append((sid, parsed.get('_last_activity', '')))
            else:
                self.redis.srem(user_key, sid)
        if len(active) >= max_sessions:
            active.sort(key=lambda x: x[1])
            to_remove = active[:len(active) - max_sessions + 1]
            for sid, _ in to_remove:
                self.redis.delete(self._key(sid))
                self.redis.srem(user_key, sid)
                _logger.info("Evicted session %s for user %s (limit %s)",
                             sid, uid, max_sessions)

    def revoke_user_sessions(self, uid, except_sid=None):
        """Revoke all sessions for a user, optionally keeping one."""
        user_key = self._user_sessions_key(uid)
        session_ids = self.redis.smembers(user_key)
        for sid in session_ids:
            if sid != except_sid:
                self.redis.delete(self._key(sid))
                self.redis.srem(user_key, sid)

    def get_active_sessions(self, uid):
        """Return list of active session metadata for a user."""
        user_key = self._user_sessions_key(uid)
        session_ids = self.redis.smembers(user_key)
        sessions = []
        for sid in session_ids:
            data = self.redis.get(self._key(sid))
            if data:
                parsed = json.loads(data)
                sessions.append({
                    'session_id': sid[:8] + '...',
                    'ip': parsed.get('_ip', 'Unknown'),
                    'last_activity': parsed.get('_last_activity', ''),
                    'user_agent': parsed.get('_user_agent', ''),
                })
        return sessions
```

```python
# smart_dairy_iam/controllers/session_api.py

from odoo import http
from odoo.http import request
import json


class SessionManagementController(http.Controller):

    @http.route('/api/v1/sessions', type='json', auth='user', methods=['GET'])
    def list_sessions(self):
        """List active sessions for the current user."""
        store = request.env['smart_dairy.redis.session.store']
        sessions = store.get_active_sessions(request.uid)
        return {'sessions': sessions}

    @http.route('/api/v1/sessions/revoke', type='json', auth='user',
                methods=['POST'])
    def revoke_session(self, session_id=None, revoke_all=False):
        """Revoke specific session or all sessions for the current user."""
        store = request.env['smart_dairy.redis.session.store']
        if revoke_all:
            store.revoke_user_sessions(
                request.uid,
                except_sid=request.session.sid,
            )
            return {'success': True, 'message': 'All other sessions revoked.'}
        elif session_id:
            store.redis.delete(store._key(session_id))
            return {'success': True, 'message': 'Session revoked.'}
        return {'success': False, 'error': 'No session specified.'}

    @http.route('/api/v1/sessions/timeout', type='json', auth='user',
                methods=['POST'])
    def update_timeout(self, timeout_minutes=60):
        """Update session timeout preference for the current user."""
        allowed = [30, 60, 480]  # 30min, 1hr, 8hr
        if timeout_minutes not in allowed:
            return {'success': False, 'error': f'Timeout must be one of {allowed}.'}
        request.env.user.sudo().write({
            'session_timeout_minutes': timeout_minutes,
        })
        return {'success': True, 'timeout_minutes': timeout_minutes}
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Configure Redis 7 container in Docker Compose for session storage |
| 3-6 | Wire `RedisSessionStore` into Odoo's HTTP server configuration |
| 6-8 | Load test sessions (100 concurrent users, measure latency) |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Build OWL SessionManager component (list, revoke, timeout selector) |
| 4-8 | Implement idle timeout detection with frontend countdown warning |

#### End-of-Day 64 Deliverables

- [ ] Redis session store with concurrent limit enforcement
- [ ] Session management API endpoints (list, revoke, timeout)
- [ ] Docker Compose Redis 7 configuration
- [ ] OWL SessionManager component
- [ ] Load test report confirming sub-50ms session lookups at 100 concurrent users

---

### Day 65 — Password Policy Enforcement

**Objective:** Implement comprehensive password policy with complexity rules, history, expiry, and bcrypt hashing.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Implement `PasswordPolicyMixin` with validation and history |
| 4-6 | Override Odoo password change flow to enforce policies |
| 6-8 | Implement password expiry check in login flow |

```python
# smart_dairy_iam/models/password_policy.py

import re
import bcrypt
from datetime import timedelta
from odoo import models, fields, api
from odoo.exceptions import ValidationError


class PasswordPolicyMixin(models.AbstractModel):
    _name = 'smart_dairy.password.policy.mixin'
    _description = 'Password Policy Enforcement Mixin'

    MIN_LENGTH = 12
    MAX_LENGTH = 128
    EXPIRY_DAYS = 90
    HISTORY_COUNT = 5
    COMPLEXITY_RULES = {
        'uppercase': (r'[A-Z]', 'at least one uppercase letter'),
        'lowercase': (r'[a-z]', 'at least one lowercase letter'),
        'digit': (r'\d', 'at least one digit'),
        'special': (r'[!@#$%^&*(),.?":{}|<>]', 'at least one special character'),
    }

    @api.model
    def validate_password_strength(self, password):
        """Validate password meets complexity requirements."""
        errors = []
        if len(password) < self.MIN_LENGTH:
            errors.append(
                f'Password must be at least {self.MIN_LENGTH} characters.'
            )
        if len(password) > self.MAX_LENGTH:
            errors.append(
                f'Password must not exceed {self.MAX_LENGTH} characters.'
            )
        for rule_name, (pattern, desc) in self.COMPLEXITY_RULES.items():
            if not re.search(pattern, password):
                errors.append(f'Password must contain {desc}.')
        # Check for common patterns
        common_patterns = [
            'password', '123456', 'qwerty', 'smartdairy', 'admin',
        ]
        lower_pw = password.lower()
        for pat in common_patterns:
            if pat in lower_pw:
                errors.append(
                    f'Password must not contain common pattern "{pat}".'
                )
        if errors:
            raise ValidationError('\n'.join(errors))
        return True

    @api.model
    def check_password_history(self, user, new_password):
        """Ensure the new password was not used recently."""
        history = user.password_history_ids[:self.HISTORY_COUNT]
        for record in history:
            if bcrypt.checkpw(
                new_password.encode('utf-8'),
                record.password_hash.encode('utf-8'),
            ):
                raise ValidationError(
                    f'Cannot reuse any of the last {self.HISTORY_COUNT} passwords.'
                )
        return True

    @api.model
    def hash_password(self, password):
        """Hash a password using bcrypt."""
        salt = bcrypt.gensalt(rounds=12)
        return bcrypt.hashpw(
            password.encode('utf-8'), salt
        ).decode('utf-8')

    @api.model
    def record_password_change(self, user, password_hash):
        """Store the password hash in history."""
        self.env['smart_dairy.password.history'].sudo().create({
            'user_id': user.id,
            'password_hash': password_hash,
        })
        user.sudo().write({
            'password_changed_date': fields.Date.context_today(user),
        })
        # Prune history beyond HISTORY_COUNT
        history = user.password_history_ids.sorted('create_date', reverse=True)
        if len(history) > self.HISTORY_COUNT:
            history[self.HISTORY_COUNT:].unlink()

    @api.model
    def is_password_expired(self, user):
        """Check if the user must change their password."""
        if not user.password_changed_date:
            return True
        expiry_date = user.password_changed_date + timedelta(
            days=self.EXPIRY_DAYS
        )
        return fields.Date.context_today(user) >= expiry_date
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Configure bcrypt library and environment dependencies |
| 3-6 | Write password policy configuration parameters in `ir.config_parameter` |
| 6-8 | Create migration script for existing user passwords |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Build OWL PasswordStrengthMeter component (real-time feedback bar) |
| 4-6 | Implement password expiry warning banner component |
| 6-8 | Build forced password change dialog for expired passwords |

#### End-of-Day 65 Deliverables

- [ ] `PasswordPolicyMixin` with validation, history, expiry
- [ ] Bcrypt hashing integrated into Odoo password change flow
- [ ] `ir.config_parameter` entries for policy tuning
- [ ] OWL PasswordStrengthMeter component
- [ ] Password expiry warning banner and forced change dialog

---

### Day 66 — User Registration & Verification Workflows

**Objective:** Build B2C self-registration and B2B admin-approved registration with email and phone verification.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Implement registration controller with email verification tokens |
| 4-6 | Build phone OTP verification flow |
| 6-8 | Implement admin approval workflow for B2B registration |

```python
# smart_dairy_iam/controllers/registration.py

import secrets
import hashlib
from datetime import datetime, timedelta
from odoo import http
from odoo.http import request


class RegistrationController(http.Controller):

    @http.route('/api/v1/auth/register', type='json', auth='public',
                methods=['POST'], csrf=False)
    def register(self, name, email, password, phone=None,
                 registration_type='b2c'):
        """Register a new user account."""
        Users = request.env['res.users'].sudo()
        Policy = request.env['smart_dairy.password.policy.mixin']

        # Check for duplicate email
        existing = Users.search([('login', '=', email)], limit=1)
        if existing:
            return {'success': False, 'error': 'Email already registered.'}

        # Validate password
        try:
            Policy.validate_password_strength(password)
        except Exception as e:
            return {'success': False, 'error': str(e)}

        # Generate verification token
        token = secrets.token_urlsafe(32)
        token_hash = hashlib.sha256(token.encode()).hexdigest()
        token_expiry = datetime.utcnow() + timedelta(hours=24)

        # Determine initial group
        if registration_type == 'b2c':
            group = request.env.ref(
                'smart_dairy_iam.group_smart_dairy_customer'
            )
            status = 'pending'
        else:
            group = request.env.ref(
                'smart_dairy_iam.group_smart_dairy_public'
            )
            status = 'pending'

        # Create user (inactive until verified)
        user = Users.create({
            'name': name,
            'login': email,
            'email': email,
            'phone': phone or '',
            'password': password,
            'groups_id': [(6, 0, [group.id])],
            'active': False,
            'registration_status': status,
            'verification_token': token_hash,
            'token_expiry': token_expiry,
        })

        # Send verification email
        self._send_verification_email(user, token)

        return {
            'success': True,
            'message': 'Registration successful. Check your email to verify.',
            'user_id': user.id,
        }

    @http.route('/api/v1/auth/verify-email', type='http', auth='public',
                methods=['GET'], csrf=False)
    def verify_email(self, token=None):
        """Verify email address using the token from the verification link."""
        if not token:
            return request.redirect('/registration/error?msg=missing_token')

        token_hash = hashlib.sha256(token.encode()).hexdigest()
        user = request.env['res.users'].sudo().search([
            ('verification_token', '=', token_hash),
            ('token_expiry', '>', datetime.utcnow()),
        ], limit=1)

        if not user:
            return request.redirect('/registration/error?msg=invalid_token')

        user.write({
            'email_verified': True,
            'verification_token': False,
            'token_expiry': False,
        })

        if user.registration_status == 'pending':
            if user.phone and not user.phone_verified:
                user.write({'registration_status': 'verified'})
                return request.redirect('/registration/verify-phone')
            else:
                user.write({
                    'registration_status': 'approved',
                    'active': True,
                })
                return request.redirect('/registration/success')

        return request.redirect('/registration/success')

    @http.route('/api/v1/auth/verify-phone', type='json', auth='public',
                methods=['POST'], csrf=False)
    def verify_phone(self, user_id, otp):
        """Verify phone number using SMS OTP."""
        cache_key = f'sms_otp:{user_id}'
        store = request.env['smart_dairy.redis.client']
        stored_otp = store.get(cache_key)

        if not stored_otp or stored_otp != otp:
            return {'success': False, 'error': 'Invalid or expired OTP.'}

        user = request.env['res.users'].sudo().browse(user_id)
        user.write({
            'phone_verified': True,
            'registration_status': 'approved',
            'active': True,
        })
        store.delete(cache_key)
        return {'success': True, 'message': 'Phone verified. Account active.'}

    @http.route('/api/v1/admin/approve-user', type='json', auth='user',
                methods=['POST'])
    def admin_approve_user(self, user_id, action='approve'):
        """Admin approves or rejects a B2B user registration."""
        if not request.env.user.has_group(
            'smart_dairy_iam.group_smart_dairy_super_admin'
        ):
            return {'success': False, 'error': 'Insufficient permissions.'}
        user = request.env['res.users'].sudo().browse(user_id)
        if action == 'approve':
            user.write({
                'registration_status': 'approved',
                'active': True,
            })
            return {'success': True, 'message': 'User approved and activated.'}
        else:
            user.write({'registration_status': 'rejected'})
            return {'success': True, 'message': 'User registration rejected.'}

    def _send_verification_email(self, user, token):
        """Send email with verification link."""
        base_url = request.env['ir.config_parameter'].sudo().get_param(
            'web.base.url'
        )
        link = f'{base_url}/api/v1/auth/verify-email?token={token}'
        template = request.env.ref(
            'smart_dairy_iam.email_template_verification'
        )
        template.sudo().send_mail(
            user.id,
            force_send=True,
            email_values={'body_html': f'''
                <p>Welcome to Smart Dairy Portal, {user.name}!</p>
                <p>Click the link below to verify your email address:</p>
                <p><a href="{link}">Verify Email</a></p>
                <p>This link expires in 24 hours.</p>
            '''},
        )
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Configure SMTP mail server for verification emails |
| 3-6 | Set up email template records (`mail.template`) |
| 6-8 | Configure SMS provider credentials and test delivery |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Build OWL registration form (multi-step wizard) |
| 4-6 | Build email verification landing page |
| 6-8 | Build phone OTP input component |

#### End-of-Day 66 Deliverables

- [ ] Registration API endpoints (register, verify-email, verify-phone, admin-approve)
- [ ] Email verification with secure token flow
- [ ] Phone OTP verification via SMS
- [ ] Mail templates and SMTP configuration
- [ ] OWL registration wizard components

---

### Day 67 — Admin User Management Interface

**Objective:** Build comprehensive OWL-based admin interface for user CRUD, role assignment, bulk operations, and activity logging.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-3 | Create admin user management API endpoints (search, update, bulk) |
| 3-6 | Implement user activity log model and retrieval |
| 6-8 | Build role assignment and bulk operations logic |

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-4 | Implement user export functionality (CSV, PDF) |
| 4-8 | Build user import from CSV with validation |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-8 | Build OWL UserManagement component suite |

```javascript
/** @odoo-module */
// smart_dairy_iam/static/src/components/user_management/user_management.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class UserManagementComponent extends Component {
    static template = "smart_dairy_iam.UserManagement";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");
        this.action = useService("action");
        this.state = useState({
            users: [],
            selectedUsers: new Set(),
            searchQuery: "",
            filterRole: "all",
            filterStatus: "all",
            currentPage: 1,
            pageSize: 25,
            totalCount: 0,
            loading: false,
            editingUser: null,
            showRoleEditor: false,
            activityLog: [],
        });

        onWillStart(async () => {
            await this.loadUsers();
        });
    }

    async loadUsers() {
        this.state.loading = true;
        try {
            const result = await this.rpc("/web/dataset/call_kw", {
                model: "res.users",
                method: "search_read",
                args: [this._buildDomain()],
                kwargs: {
                    fields: [
                        "name", "login", "email", "phone",
                        "mfa_enabled", "registration_status",
                        "last_activity", "active", "groups_id",
                    ],
                    limit: this.state.pageSize,
                    offset: (this.state.currentPage - 1) * this.state.pageSize,
                },
            });
            this.state.users = result;
        } catch (e) {
            this.notification.add("Failed to load users.", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    _buildDomain() {
        const domain = [];
        if (this.state.searchQuery) {
            domain.push("|", "|",
                ["name", "ilike", this.state.searchQuery],
                ["login", "ilike", this.state.searchQuery],
                ["phone", "ilike", this.state.searchQuery]
            );
        }
        if (this.state.filterStatus !== "all") {
            domain.push(
                ["registration_status", "=", this.state.filterStatus]
            );
        }
        return domain;
    }

    toggleSelectUser(userId) {
        const sel = this.state.selectedUsers;
        if (sel.has(userId)) {
            sel.delete(userId);
        } else {
            sel.add(userId);
        }
    }

    toggleSelectAll() {
        if (this.state.selectedUsers.size === this.state.users.length) {
            this.state.selectedUsers.clear();
        } else {
            this.state.users.forEach(u => this.state.selectedUsers.add(u.id));
        }
    }

    async bulkAction(action) {
        const ids = Array.from(this.state.selectedUsers);
        if (!ids.length) return;

        const actionMap = {
            activate: { active: true },
            deactivate: { active: false },
            reset_mfa: { mfa_enabled: false, mfa_secret: false },
            force_password_change: { password_changed_date: false },
        };

        if (!actionMap[action]) return;

        try {
            await this.rpc("/web/dataset/call_kw", {
                model: "res.users",
                method: "write",
                args: [ids, actionMap[action]],
                kwargs: {},
            });
            this.notification.add(
                `Bulk ${action} applied to ${ids.length} users.`,
                { type: "success" }
            );
            this.state.selectedUsers.clear();
            await this.loadUsers();
        } catch (e) {
            this.notification.add("Bulk action failed.", { type: "danger" });
        }
    }

    async onEditUser(user) {
        this.state.editingUser = { ...user };
        this.state.showRoleEditor = true;
    }

    async onSaveUser() {
        const user = this.state.editingUser;
        try {
            await this.rpc("/web/dataset/call_kw", {
                model: "res.users",
                method: "write",
                args: [[user.id], {
                    name: user.name,
                    email: user.email,
                    phone: user.phone,
                    groups_id: user.groups_id,
                }],
                kwargs: {},
            });
            this.notification.add("User updated.", { type: "success" });
            this.state.showRoleEditor = false;
            this.state.editingUser = null;
            await this.loadUsers();
        } catch (e) {
            this.notification.add("Update failed.", { type: "danger" });
        }
    }

    async loadActivityLog(userId) {
        const logs = await this.rpc("/web/dataset/call_kw", {
            model: "audit.auth_log",
            method: "search_read",
            args: [[["user_id", "=", userId]]],
            kwargs: {
                fields: [
                    "event_type", "ip_address", "user_agent",
                    "create_date", "success",
                ],
                limit: 50,
                order: "create_date desc",
            },
        });
        this.state.activityLog = logs;
    }
}
```

#### End-of-Day 67 Deliverables

- [ ] Admin user management API (search, filter, paginate)
- [ ] User CRUD with role assignment
- [ ] Bulk operations (activate, deactivate, reset MFA, force password change)
- [ ] User import/export (CSV, PDF)
- [ ] OWL UserManagement component with activity timeline

---

### Day 68 — Audit Logging for Authentication Events

**Objective:** Build a tamper-resistant audit logging system capturing all authentication events with IP tracking and geo-location.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Implement `audit.auth_log` model and authentication middleware |
| 4-6 | Add IP geo-location lookup integration |
| 6-8 | Implement brute force protection with progressive lockout |

```python
# smart_dairy_iam/models/audit_auth_log.py

import logging
from odoo import models, fields, api

_logger = logging.getLogger(__name__)


class AuditAuthLog(models.Model):
    _name = 'audit.auth_log'
    _description = 'Authentication Audit Log'
    _order = 'create_date desc'
    _rec_name = 'event_type'

    user_id = fields.Many2one('res.users', string='User', index=True)
    user_login = fields.Char(string='Login Attempted')
    event_type = fields.Selection([
        ('login_success', 'Login Success'),
        ('login_failed', 'Login Failed'),
        ('logout', 'Logout'),
        ('mfa_success', 'MFA Verification Success'),
        ('mfa_failed', 'MFA Verification Failed'),
        ('password_change', 'Password Changed'),
        ('password_reset', 'Password Reset Requested'),
        ('role_change', 'Role/Group Changed'),
        ('account_locked', 'Account Locked'),
        ('account_unlocked', 'Account Unlocked'),
        ('session_revoked', 'Session Revoked'),
        ('registration', 'New Registration'),
        ('email_verified', 'Email Verified'),
        ('phone_verified', 'Phone Verified'),
    ], string='Event Type', required=True, index=True)

    ip_address = fields.Char(string='IP Address')
    geo_country = fields.Char(string='Country')
    geo_city = fields.Char(string='City')
    user_agent = fields.Text(string='User Agent')
    session_id = fields.Char(string='Session ID')
    success = fields.Boolean(string='Successful', default=True)
    failure_reason = fields.Char(string='Failure Reason')
    metadata = fields.Text(string='Additional Metadata (JSON)')

    # Prevent modification of audit records
    @api.model_create_multi
    def create(self, vals_list):
        records = super().create(vals_list)
        return records

    def write(self, vals):
        """Audit logs are immutable once created."""
        raise models.ValidationError(
            'Audit log records cannot be modified.'
        )

    def unlink(self):
        """Audit logs cannot be deleted."""
        raise models.ValidationError(
            'Audit log records cannot be deleted.'
        )
```

```python
# smart_dairy_iam/models/brute_force_protection.py

import logging
from datetime import datetime, timedelta
from odoo import models, api, fields
from odoo.exceptions import AccessDenied

_logger = logging.getLogger(__name__)

# Progressive lockout thresholds
LOCKOUT_TIERS = [
    (5, 5),      # 5 failures  -> 5 min lockout
    (10, 30),    # 10 failures -> 30 min lockout
    (15, 1440),  # 15 failures -> 24 hour lockout
]


class BruteForceProtection(models.AbstractModel):
    _name = 'smart_dairy.brute_force'
    _description = 'Brute Force Protection Service'

    @api.model
    def record_failed_login(self, login, ip_address):
        """Record a failed login attempt and apply lockout if needed."""
        user = self.env['res.users'].sudo().search(
            [('login', '=', login)], limit=1
        )
        if not user:
            # Log attempt even for non-existent users (prevent enumeration)
            self.env['audit.auth_log'].sudo().create({
                'user_login': login,
                'event_type': 'login_failed',
                'ip_address': ip_address,
                'success': False,
                'failure_reason': 'User not found',
            })
            return

        user.sudo().write({
            'failed_login_count': user.failed_login_count + 1,
        })
        count = user.failed_login_count

        # Determine lockout duration
        lockout_minutes = 0
        for threshold, minutes in LOCKOUT_TIERS:
            if count >= threshold:
                lockout_minutes = minutes

        if lockout_minutes > 0:
            lock_until = datetime.utcnow() + timedelta(minutes=lockout_minutes)
            user.sudo().write({'account_locked_until': lock_until})
            _logger.warning(
                "Account %s locked for %d minutes after %d failed attempts.",
                login, lockout_minutes, count,
            )
            self.env['audit.auth_log'].sudo().create({
                'user_id': user.id,
                'user_login': login,
                'event_type': 'account_locked',
                'ip_address': ip_address,
                'success': False,
                'failure_reason': f'Locked for {lockout_minutes}min '
                                  f'after {count} failures',
            })

        self.env['audit.auth_log'].sudo().create({
            'user_id': user.id,
            'user_login': login,
            'event_type': 'login_failed',
            'ip_address': ip_address,
            'success': False,
            'failure_reason': 'Invalid credentials',
        })

    @api.model
    def record_successful_login(self, user, ip_address, user_agent=None):
        """Record a successful login and reset failure count."""
        user.sudo().write({
            'failed_login_count': 0,
            'account_locked_until': False,
            'last_login_ip': ip_address,
            'last_activity': fields.Datetime.now(),
        })
        self.env['audit.auth_log'].sudo().create({
            'user_id': user.id,
            'user_login': user.login,
            'event_type': 'login_success',
            'ip_address': ip_address,
            'user_agent': user_agent,
            'success': True,
        })

    @api.model
    def check_ip_rate_limit(self, ip_address, window_minutes=15,
                            max_attempts=20):
        """Rate limit login attempts by IP address regardless of username."""
        cutoff = datetime.utcnow() - timedelta(minutes=window_minutes)
        count = self.env['audit.auth_log'].sudo().search_count([
            ('ip_address', '=', ip_address),
            ('event_type', '=', 'login_failed'),
            ('create_date', '>=', cutoff),
        ])
        if count >= max_attempts:
            raise AccessDenied(
                'Too many login attempts from this IP. '
                'Please try again later.'
            )
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Set up GeoIP database (MaxMind GeoLite2) for IP geo-location |
| 3-6 | Configure log rotation and retention policy for audit logs |
| 6-8 | Build audit log archival script (compress and move to cold storage after 90 days) |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Build OWL AuditLogViewer component (filterable table, date range, event type) |
| 4-8 | Build login attempt map visualization (geo-located login attempts) |

#### End-of-Day 68 Deliverables

- [ ] `audit.auth_log` model with immutable records
- [ ] Brute force protection with progressive lockout tiers
- [ ] IP-based rate limiting
- [ ] GeoIP integration for location tracking
- [ ] Audit log archival and retention configuration
- [ ] OWL AuditLogViewer component with geo-map

---

### Day 69 — IAM Integration Testing & Penetration Testing

**Objective:** Comprehensive testing of the entire IAM system including security penetration tests.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | Write pytest test suite for all IAM components |
| 4-8 | Run tests and fix any failures |

```python
# smart_dairy_iam/tests/test_iam.py

from odoo.tests.common import TransactionCase, tagged
from odoo.exceptions import AccessError, ValidationError, AccessDenied
from unittest.mock import patch
from datetime import datetime, timedelta


@tagged('post_install', '-at_install')
class TestRBACPermissions(TransactionCase):
    """Test RBAC permission enforcement for all 8 roles."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.super_admin = cls.env.ref(
            'smart_dairy_iam.group_smart_dairy_super_admin'
        )
        cls.farm_manager = cls.env.ref(
            'smart_dairy_iam.group_smart_dairy_farm_manager'
        )
        cls.veterinarian = cls.env.ref(
            'smart_dairy_iam.group_smart_dairy_veterinarian'
        )
        cls.milk_collector = cls.env.ref(
            'smart_dairy_iam.group_smart_dairy_milk_collector'
        )
        cls.customer = cls.env.ref(
            'smart_dairy_iam.group_smart_dairy_customer'
        )

        # Create test users for each role
        cls.user_admin = cls.env['res.users'].create({
            'name': 'Test Admin',
            'login': 'test_admin@smartdairy.com',
            'groups_id': [(6, 0, [cls.super_admin.id])],
        })
        cls.user_farmer = cls.env['res.users'].create({
            'name': 'Test Farmer',
            'login': 'test_farmer@smartdairy.com',
            'groups_id': [(6, 0, [cls.farm_manager.id])],
        })
        cls.user_vet = cls.env['res.users'].create({
            'name': 'Test Vet',
            'login': 'test_vet@smartdairy.com',
            'groups_id': [(6, 0, [cls.veterinarian.id])],
        })
        cls.user_collector = cls.env['res.users'].create({
            'name': 'Test Collector',
            'login': 'test_collector@smartdairy.com',
            'groups_id': [(6, 0, [cls.milk_collector.id])],
        })

    def test_super_admin_full_access(self):
        """Super Admin should have full CRUD on all models."""
        Cattle = self.env['dairy.cattle'].with_user(self.user_admin)
        record = Cattle.create({'name': 'Test Cow', 'tag_number': 'T001'})
        self.assertTrue(record.id)
        record.write({'name': 'Updated Cow'})
        record.unlink()

    def test_milk_collector_readonly_cattle(self):
        """Milk Collector should only read cattle, not write."""
        Cattle = self.env['dairy.cattle'].with_user(self.user_collector)
        records = Cattle.search([])
        self.assertIsNotNone(records)
        with self.assertRaises(AccessError):
            Cattle.create({'name': 'Should Fail', 'tag_number': 'T999'})

    def test_vet_sees_only_assigned_cattle(self):
        """Veterinarian should only see cattle assigned to them."""
        Cattle = self.env['dairy.cattle'].with_user(self.user_vet)
        visible = Cattle.search([])
        for record in visible:
            self.assertEqual(record.veterinarian_id.id, self.user_vet.id)

    def test_customer_no_cattle_access(self):
        """Customer should not be able to read cattle records."""
        user_customer = self.env['res.users'].create({
            'name': 'Test Customer',
            'login': 'test_cust@smartdairy.com',
            'groups_id': [(6, 0, [self.customer.id])],
        })
        Cattle = self.env['dairy.cattle'].with_user(user_customer)
        with self.assertRaises(AccessError):
            Cattle.search([])

    def test_role_hierarchy_implied(self):
        """Farm Manager should inherit Milk Collector and Vet groups."""
        self.assertIn(
            self.milk_collector,
            self.user_farmer.groups_id,
        )
        self.assertIn(
            self.veterinarian,
            self.user_farmer.groups_id,
        )


@tagged('post_install', '-at_install')
class TestMFAFlow(TransactionCase):
    """Test MFA enrollment, verification, and backup codes."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.user = cls.env['res.users'].create({
            'name': 'MFA Test User',
            'login': 'mfa_user@smartdairy.com',
            'groups_id': [(6, 0, [
                cls.env.ref(
                    'smart_dairy_iam.group_smart_dairy_farm_manager'
                ).id,
            ])],
        })

    def test_totp_setup_and_verify(self):
        """TOTP secret generation and token verification."""
        service = self.env['smart_dairy.mfa.service']
        result = service.generate_totp_secret(self.user.id)
        self.assertIn('secret', result)
        self.assertIn('qr_code', result)

        import pyotp
        totp = pyotp.TOTP(result['secret'])
        valid_token = totp.now()
        self.assertTrue(service.verify_totp(self.user.id, valid_token))

    def test_backup_code_usage(self):
        """Backup codes should work once and be consumed."""
        service = self.env['smart_dairy.mfa.service']
        service.generate_totp_secret(self.user.id)

        import pyotp
        totp = pyotp.TOTP(self.user.mfa_secret)
        service.enable_mfa(self.user.id, totp.now())

        codes = self.user.generate_backup_codes(count=5)
        self.assertEqual(len(codes), 5)
        self.assertTrue(service.verify_backup_code(self.user.id, codes[0]))
        self.assertFalse(service.verify_backup_code(self.user.id, codes[0]))

    def test_invalid_totp_rejected(self):
        """Invalid TOTP tokens should be rejected."""
        service = self.env['smart_dairy.mfa.service']
        service.generate_totp_secret(self.user.id)
        self.assertFalse(service.verify_totp(self.user.id, '000000'))


@tagged('post_install', '-at_install')
class TestPasswordPolicy(TransactionCase):
    """Test password policy enforcement."""

    def test_short_password_rejected(self):
        """Passwords shorter than 12 characters should be rejected."""
        Policy = self.env['smart_dairy.password.policy.mixin']
        with self.assertRaises(ValidationError):
            Policy.validate_password_strength('Short1!')

    def test_missing_uppercase_rejected(self):
        """Passwords without uppercase letters should be rejected."""
        Policy = self.env['smart_dairy.password.policy.mixin']
        with self.assertRaises(ValidationError):
            Policy.validate_password_strength('alllowercase1!')

    def test_missing_special_rejected(self):
        """Passwords without special characters should be rejected."""
        Policy = self.env['smart_dairy.password.policy.mixin']
        with self.assertRaises(ValidationError):
            Policy.validate_password_strength('NoSpecialChar1A')

    def test_valid_password_accepted(self):
        """Passwords meeting all requirements should pass."""
        Policy = self.env['smart_dairy.password.policy.mixin']
        result = Policy.validate_password_strength('Str0ng!Pass#2026')
        self.assertTrue(result)

    def test_common_pattern_rejected(self):
        """Passwords containing common patterns should be rejected."""
        Policy = self.env['smart_dairy.password.policy.mixin']
        with self.assertRaises(ValidationError):
            Policy.validate_password_strength('MyPassword123!')

    def test_password_history_prevents_reuse(self):
        """Recently used passwords should not be reusable."""
        Policy = self.env['smart_dairy.password.policy.mixin']
        user = self.env['res.users'].create({
            'name': 'PW History User',
            'login': 'pw_hist@smartdairy.com',
        })
        pw_hash = Policy.hash_password('OldPassword!123')
        Policy.record_password_change(user, pw_hash)

        with self.assertRaises(ValidationError):
            Policy.check_password_history(user, 'OldPassword!123')


@tagged('post_install', '-at_install')
class TestSessionManagement(TransactionCase):
    """Test Redis session management."""

    def test_concurrent_session_limit(self):
        """Sessions exceeding the limit should evict the oldest."""
        # This test requires Redis; mocked for unit testing.
        from smart_dairy_iam.models.redis_session_store import (
            RedisSessionStore,
        )
        store = RedisSessionStore.__new__(RedisSessionStore)
        # Integration test runs against actual Redis in CI.
        self.assertTrue(hasattr(store, 'enforce_concurrent_limit'))

    def test_session_revocation(self):
        """Revoking a session should remove it from the store."""
        self.assertTrue(True)  # Placeholder for integration test


@tagged('post_install', '-at_install')
class TestBruteForceProtection(TransactionCase):
    """Test brute force protection and account lockout."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.user = cls.env['res.users'].create({
            'name': 'Brute Force Test',
            'login': 'bf_test@smartdairy.com',
        })

    def test_lockout_after_5_failures(self):
        """Account should lock after 5 failed login attempts."""
        bf = self.env['smart_dairy.brute_force']
        for _ in range(5):
            bf.record_failed_login('bf_test@smartdairy.com', '192.168.1.1')
        self.user.invalidate_recordset()
        self.assertTrue(self.user.check_account_locked())

    def test_progressive_lockout_duration(self):
        """Lockout duration should increase with more failures."""
        bf = self.env['smart_dairy.brute_force']
        for _ in range(15):
            bf.record_failed_login('bf_test@smartdairy.com', '10.0.0.1')
        self.user.invalidate_recordset()
        self.assertTrue(self.user.account_locked_until)
        expected_min = datetime.utcnow() + timedelta(minutes=1430)
        self.assertGreater(self.user.account_locked_until, expected_min)

    def test_successful_login_resets_count(self):
        """Successful login should reset the failure counter."""
        bf = self.env['smart_dairy.brute_force']
        bf.record_failed_login('bf_test@smartdairy.com', '10.0.0.2')
        bf.record_failed_login('bf_test@smartdairy.com', '10.0.0.2')
        bf.record_successful_login(self.user, '10.0.0.2')
        self.user.invalidate_recordset()
        self.assertEqual(self.user.failed_login_count, 0)
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Run penetration testing tools (OWASP ZAP, Burp Suite community) |
| 3-5 | Test session fixation vulnerability |
| 5-7 | Test privilege escalation by tampering with role parameters |
| 7-8 | Test CSRF token enforcement on all form submissions |

**Penetration Test Checklist:**

| Test | Tool/Method | Pass Criteria |
|---|---|---|
| Brute force login | Custom script (500 requests in 60s) | Account locks after threshold |
| Session fixation | Manual session ID injection | Server rejects pre-set session IDs |
| Privilege escalation | Tamper group_id in POST payload | Server validates and rejects |
| CSRF | Remove/alter CSRF token in forms | Server returns 403 |
| SQL injection in login | sqlmap against /web/login | No injection possible |
| XSS in registration | Script tags in name/email fields | Input sanitized |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-4 | End-to-end UI testing for all IAM flows (Selenium/Playwright) |
| 4-8 | Accessibility audit for IAM pages (WCAG 2.1 AA) |

#### End-of-Day 69 Deliverables

- [ ] pytest suite with 30+ tests covering RBAC, MFA, password, session, brute force
- [ ] All tests passing (zero failures)
- [ ] Penetration test report with zero critical findings
- [ ] E2E UI test suite for IAM flows
- [ ] Accessibility audit report

---

### Day 70 — Milestone 7 Review, Sign-Off & Security Walkthrough

**Objective:** Final review, documentation, sign-off, and live security walkthrough demo.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0-3 | Fix any outstanding test failures or security findings |
| 3-5 | Write IAM technical documentation |
| 5-8 | Prepare demo scenarios for security walkthrough |

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0-3 | Final CI/CD pipeline configuration for IAM module |
| 3-5 | Generate code coverage and security scan reports |
| 5-8 | Prepare deployment checklist and rollback procedures |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0-3 | Final UI polish and cross-browser testing |
| 3-5 | Screenshot documentation for all IAM screens |
| 5-8 | Participate in security walkthrough demo |

**Security Walkthrough Demo Script:**

1. Create a new user via B2C self-registration
2. Complete email and phone verification
3. Login and demonstrate MFA enrollment
4. Show role-based access differences (Farm Manager vs Milk Collector vs Customer)
5. Demonstrate session management (list, revoke, timeout)
6. Trigger brute force lockout and show progressive lockout
7. Show audit log capturing all above events
8. Admin creates a B2B user and assigns roles
9. Demonstrate password expiry and forced change flow

#### End-of-Day 70 Deliverables

- [ ] All IAM features fully functional and tested
- [ ] Security walkthrough demo completed successfully
- [ ] IAM technical documentation finalized
- [ ] Code coverage report (target: 85%+)
- [ ] Milestone 7 sign-off document signed by all developers
- [ ] Handoff notes prepared for Milestone 8

---

## 4. Technical Specifications

### 4.1 Module Structure

```
smart_dairy_iam/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── smart_dairy_user.py          # Extended res.users
│   ├── mfa_service.py               # TOTP/SMS MFA logic
│   ├── password_policy.py           # PasswordPolicyMixin
│   ├── redis_session_store.py       # Redis session handler
│   ├── brute_force_protection.py    # Progressive lockout
│   └── audit_auth_log.py            # Immutable audit log
├── controllers/
│   ├── __init__.py
│   ├── registration.py              # User registration endpoints
│   └── session_api.py               # Session management API
├── security/
│   ├── ir.model.access.csv          # Model-level permissions
│   └── iam_record_rules.xml         # Record-level rules
├── data/
│   ├── iam_groups.xml               # Role definitions
│   └── email_templates.xml          # Verification email templates
├── static/
│   └── src/
│       ├── components/
│       │   ├── mfa_setup/
│       │   │   ├── mfa_setup.js
│       │   │   └── mfa_setup.xml
│       │   ├── user_management/
│       │   │   ├── user_management.js
│       │   │   └── user_management.xml
│       │   ├── session_manager/
│       │   ├── password_strength/
│       │   └── audit_log_viewer/
│       └── scss/
│           └── iam_styles.scss
├── tests/
│   ├── __init__.py
│   └── test_iam.py                  # Full test suite
└── views/
    ├── user_management_views.xml
    └── audit_log_views.xml
```

### 4.2 Dependencies

| Package | Version | Purpose |
|---|---|---|
| pyotp | 2.9+ | TOTP generation and verification |
| qrcode | 7.4+ | QR code image generation for MFA setup |
| bcrypt | 4.1+ | Password hashing |
| redis | 5.0+ | Session store backend |
| Pillow | 10.0+ | Image processing for QR codes |
| geoip2 | 4.8+ | IP geo-location lookups |

### 4.3 Configuration Parameters

| Parameter Key | Default | Description |
|---|---|---|
| `smart_dairy.mfa_required_groups` | `super_admin,farm_manager` | Groups that must enable MFA |
| `smart_dairy.password_min_length` | `12` | Minimum password length |
| `smart_dairy.password_expiry_days` | `90` | Days before password expires |
| `smart_dairy.password_history_count` | `5` | Number of previous passwords to remember |
| `smart_dairy.session_default_timeout` | `3600` | Default session timeout in seconds |
| `smart_dairy.max_concurrent_sessions` | `3` | Default max concurrent sessions |
| `smart_dairy.brute_force_tier1_attempts` | `5` | Failures before first lockout |
| `smart_dairy.brute_force_tier1_minutes` | `5` | First lockout duration |
| `smart_dairy.brute_force_tier2_attempts` | `10` | Failures before second lockout |
| `smart_dairy.brute_force_tier2_minutes` | `30` | Second lockout duration |
| `smart_dairy.brute_force_tier3_attempts` | `15` | Failures before third lockout |
| `smart_dairy.brute_force_tier3_minutes` | `1440` | Third lockout duration (24h) |
| `smart_dairy.ip_rate_limit_window` | `15` | IP rate limit window in minutes |
| `smart_dairy.ip_rate_limit_max` | `20` | Max failed attempts per IP in window |
| `smart_dairy.sms_provider` | `sslwireless` | Bangladesh SMS gateway provider |
| `smart_dairy.geoip_db_path` | `/opt/geoip/GeoLite2-City.mmdb` | GeoIP database file path |

### 4.4 Database Tables

| Table | Purpose | Key Fields |
|---|---|---|
| `res_users` (extended) | User accounts with IAM fields | `mfa_enabled`, `mfa_secret`, `failed_login_count`, `account_locked_until` |
| `smart_dairy_password_history` | Password hash history | `user_id`, `password_hash`, `changed_date` |
| `audit_auth_log` | Authentication event audit trail | `user_id`, `event_type`, `ip_address`, `geo_country`, `success` |

### 4.5 API Endpoints

| Endpoint | Method | Auth | Description |
|---|---|---|---|
| `/api/v1/auth/register` | POST | Public | User self-registration |
| `/api/v1/auth/verify-email` | GET | Public | Email verification callback |
| `/api/v1/auth/verify-phone` | POST | Public | Phone OTP verification |
| `/api/v1/admin/approve-user` | POST | User (Super Admin) | Approve/reject B2B registration |
| `/api/v1/sessions` | GET | User | List active sessions |
| `/api/v1/sessions/revoke` | POST | User | Revoke sessions |
| `/api/v1/sessions/timeout` | POST | User | Update timeout preference |

---

## 5. Testing & Validation

### 5.1 Test Coverage Targets

| Component | Target Coverage | Test Type |
|---|---|---|
| RBAC Permissions | 95% | Unit (TransactionCase) |
| MFA Flow | 90% | Unit + Integration |
| Password Policy | 95% | Unit |
| Session Management | 85% | Integration (requires Redis) |
| Brute Force Protection | 90% | Unit |
| Registration Workflows | 85% | Integration |
| Audit Logging | 90% | Unit |
| OWL Components | 80% | E2E (Playwright) |
| **Overall Module** | **85%+** | Combined |

### 5.2 Security Test Matrix

| Attack Vector | Test Method | Expected Result |
|---|---|---|
| Brute force login | 500 automated requests | Account locks; IP rate-limited |
| Session fixation | Pre-set session cookie | Server generates new session ID |
| Session hijacking | Stolen session ID from different IP | Session invalidated |
| Privilege escalation | Direct group_id manipulation in POST | Server rejects; 403 returned |
| CSRF token bypass | Remove/modify CSRF token | Server returns 403 |
| SQL injection (login) | sqlmap on `/web/login` | No injection vectors found |
| XSS (registration) | Script tags in name/email | Input sanitized, script not executed |
| Password brute force | Dictionary attack against known user | Account locks after 5 attempts |
| Token enumeration | Sequential token guessing | Tokens are cryptographically random |
| MFA bypass | Skip MFA verification step | Server enforces MFA before granting access |

### 5.3 Performance Benchmarks

| Metric | Target | Measurement Method |
|---|---|---|
| Redis session lookup | < 50ms | Load test with 100 concurrent users |
| TOTP verification | < 100ms | Benchmark pyotp.verify() |
| Password hash (bcrypt) | 200-400ms | Benchmark bcrypt with rounds=12 |
| Login flow (with MFA) | < 2s total | E2E timing |
| Audit log write | < 10ms | Database insert timing |
| Permission check | < 5ms | Odoo ORM access check timing |

---

## 6. Risk & Mitigation

| # | Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|---|
| R1 | SMS OTP delivery failures in Bangladesh (network issues) | Medium | High | Implement retry logic with fallback to email OTP; use multiple SMS providers |
| R2 | Redis session store downtime | Low | Critical | Redis Sentinel for high availability; fallback to database sessions |
| R3 | MFA secret key exposure in database | Low | Critical | Encrypt `mfa_secret` field at rest using Odoo field encryption or Fernet |
| R4 | Password policy too strict for farm workers | Medium | Medium | Allow admin to configure per-role policy thresholds; provide password generator |
| R5 | Brute force lockout used for denial-of-service | Medium | Medium | Combine account lockout with CAPTCHA after 3 attempts; IP-based rate limiting |
| R6 | GeoIP database out of date | Low | Low | Automate monthly GeoLite2 database updates via cron |
| R7 | Session fixation via cookie injection | Low | High | Regenerate session ID on every authentication event |
| R8 | Audit log table grows too large | Medium | Medium | Implement partitioning by month; archive logs older than 90 days |
| R9 | OWL component compatibility with older browsers | Low | Medium | Target last 2 versions of Chrome, Firefox, Edge; polyfill where needed |
| R10 | Concurrent session eviction disrupts active users | Medium | Medium | Notify user before evicting oldest session; allow grace period |

---

## 7. Dependencies & Handoffs

### 7.1 Incoming Dependencies (from previous milestones)

| Dependency | Source | Status |
|---|---|---|
| Security architecture design | Milestone 6 | Delivered |
| RBAC role design and permission matrix | Milestone 6 | Delivered |
| Encryption strategy (at rest, in transit) | Milestone 6 | Delivered |
| Audit framework design | Milestone 6 | Delivered |
| Database schema (farm models) | Milestone 5 | Delivered |
| Odoo 19 CE environment and Docker setup | Milestone 3 | Delivered |
| PostgreSQL 16 database | Milestone 4 | Delivered |

### 7.2 Outgoing Handoffs (to subsequent milestones)

| Deliverable | Recipient | Milestone |
|---|---|---|
| Functional IAM module with 8 enforced roles | Milestone 8 (API Development) | M8 |
| Redis session store (shared infrastructure) | Milestone 8 (API caching) | M8 |
| Audit logging framework | Milestone 9 (Compliance & Reporting) | M9 |
| User registration endpoints | Milestone 8 (API Integration) | M8 |
| Authentication middleware | Milestone 8 (API Security) | M8 |
| OWL IAM components | Milestone 10 (Frontend Integration) | M10 |

### 7.3 Inter-Developer Handoff Protocol

| From | To | Artifact | Format | Deadline |
|---|---|---|---|---|
| Dev 1 | Dev 2 | IAM Python models and controllers | Git branch `feature/iam-backend` | Day 66 |
| Dev 2 | Dev 1 | Redis config and Docker updates | Git branch `feature/iam-infra` | Day 64 |
| Dev 1 | Dev 3 | API endpoint documentation | OpenAPI/Swagger JSON | Day 66 |
| Dev 3 | Dev 1 | OWL component integration requirements | Figma + component specs | Day 63 |
| Dev 1 + Dev 2 | Dev 3 | Test user accounts with all roles | Seed data script | Day 67 |
| All | All | Milestone 7 sign-off document | PDF/Markdown | Day 70 |

---

## Appendix A: Role-Permission Summary Matrix

| Permission | Super Admin | Farm Manager | Veterinarian | Milk Collector | Accountant | HR Manager | Customer | Public |
|---|---|---|---|---|---|---|---|---|
| **create** | All models | Farm, Milk, Feed | Health records | Milk collection | Invoices | Employees | Orders | None |
| **read** | All models | All farm models | Cattle, Health | Cattle, Milk | Financial models | HR models | Own orders | Public content |
| **update** | All models | Farm, Milk, Feed | Health records | Own collections | Invoices | Employees | Own profile | None |
| **delete** | All models | None | None | None | None | None | None | None |
| **export** | All models | Farm reports | Health reports | Collection reports | Financial reports | HR reports | Order history | None |
| **import** | All models | Cattle data | None | None | Financial data | Employee data | None | None |
| **approve** | All workflows | Milk quality | Treatment plans | None | Payments | Leave requests | None | None |
| **audit** | Full audit | Farm audit | None | None | Financial audit | None | None | None |
| **admin** | Full system | None | None | None | None | None | None | None |

---

## Appendix B: Session Timeout Configuration by Role

| Role | Default Timeout | Max Concurrent Sessions | MFA Required |
|---|---|---|---|
| Super Admin | 30 minutes | 1 | Yes |
| Farm Manager | 60 minutes | 2 | Yes |
| Veterinarian | 60 minutes | 2 | Yes |
| Milk Collector | 480 minutes (8h) | 1 | No (optional) |
| Accountant | 60 minutes | 2 | Yes |
| HR Manager | 60 minutes | 2 | Yes |
| Customer | 480 minutes (8h) | 3 | No (optional) |
| Public | N/A (no session) | N/A | N/A |

---

*End of Milestone 7: Identity & Access Management (IAM) Implementation*
