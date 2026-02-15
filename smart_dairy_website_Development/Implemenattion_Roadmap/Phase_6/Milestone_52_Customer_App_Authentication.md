# Milestone 52: Customer App — Authentication Module

## Smart Dairy Digital Smart Portal + ERP — Phase 6: Mobile App Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 52 of 150 (2 of 10 in Phase 6)                                         |
| **Title**        | Customer App — Authentication Module                                   |
| **Phase**        | Phase 6 — Mobile App Foundation                                        |
| **Days**         | Days 261-270 (10 working days)                                         |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Mobile Lead)          |
| **Last Updated** | 2026-02-03                                                             |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement a complete authentication system for the Customer App supporting multiple authentication methods: phone OTP (primary for Bangladesh market), email/password, social login (Google, Facebook), and biometric authentication. The system must securely manage JWT tokens with refresh capability and support offline token validation.

### 1.2 Objectives

1. Implement phone OTP authentication with Bangladesh format (+880)
2. Create email/password authentication flow
3. Integrate Google Sign-In
4. Integrate Facebook Login
5. Implement biometric authentication (fingerprint/Face ID)
6. Build JWT token management with secure storage
7. Create refresh token flow with automatic renewal
8. Design and implement authentication UI screens
9. Handle session management across devices
10. Implement password reset and account recovery

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| OTP Send/Verify API             | Dev 1  | REST endpoints    | 262     |
| Email Auth API                  | Dev 1  | REST endpoints    | 263     |
| Social Auth Backend             | Dev 1  | OAuth handlers    | 264     |
| SMS Gateway Integration         | Dev 2  | Service config    | 261     |
| Firebase Auth Setup             | Dev 2  | Firebase config   | 264     |
| Auth BLoC/Providers             | Dev 3  | Dart code         | 265     |
| OTP Screens UI                  | Dev 3  | Flutter widgets   | 262     |
| Login/Register Screens          | Dev 3  | Flutter widgets   | 263     |
| Social Login Buttons            | Dev 3  | Flutter widgets   | 264     |
| Biometric Auth                  | Dev 3  | Flutter plugin    | 266     |
| Secure Token Storage            | Dev 3  | Encrypted storage | 265     |
| Profile Setup Flow              | Dev 3  | Flutter screens   | 267     |
| Session Management              | Dev 3  | Dart service      | 268     |
| Password Reset Flow             | Dev 1  | API + UI          | 269     |
| Auth Integration Tests          | Dev 2  | Test suite        | 270     |

### 1.4 Prerequisites

- Milestone 51 complete (Flutter foundation)
- Shared packages available (core, api_client, auth)
- Firebase project configured
- SSL Wireless SMS account active
- Google OAuth credentials created
- Facebook App created and configured
- Odoo mobile API module deployed

### 1.5 Success Criteria

- [ ] OTP sent and verified within 30 seconds
- [ ] OTP delivery rate >95%
- [ ] Token refresh works without user intervention
- [ ] Social login completes in <5 seconds
- [ ] Biometric auth works on supported devices
- [ ] Secure storage encrypted with device keychain
- [ ] Session persists across app restarts
- [ ] Password reset email delivered within 1 minute
- [ ] Auth flow E2E tests passing
- [ ] Unit test coverage >85%

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-MOB-002  | RFP    | Customer authentication (OTP, social)    | 261-270 | Complete milestone    |
| SRS-MOB-004  | SRS    | JWT authentication with refresh tokens   | 265     | Token management      |
| SRS-MOB-005  | SRS    | OTP via SSL Wireless SMS gateway         | 261     | SMS integration       |
| BRD-MOB-001  | BRD    | 50,000+ registered customers via app     | 261-270 | Scalable auth design  |
| H-008        | Impl   | Mobile Security Implementation           | 265-266 | Secure storage, bio   |

---

## 3. Day-by-Day Breakdown

### Day 261 — OTP Infrastructure & SMS Gateway

**Objective:** Set up SMS gateway integration and create the foundation for OTP-based authentication.

#### Dev 1 — Backend Lead (8h)

**Task 1: OTP Model and Storage (3h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/mobile_otp.py
from odoo import models, fields, api
from datetime import datetime, timedelta
import random
import hashlib
import logging

_logger = logging.getLogger(__name__)


class MobileOTP(models.Model):
    _name = 'mobile.otp'
    _description = 'Mobile OTP Records'
    _order = 'create_date desc'

    phone = fields.Char(
        string='Phone Number',
        required=True,
        index=True,
    )
    otp_hash = fields.Char(
        string='OTP Hash',
        required=True,
    )
    purpose = fields.Selection([
        ('login', 'Login'),
        ('register', 'Registration'),
        ('reset_password', 'Password Reset'),
        ('verify_phone', 'Phone Verification'),
    ], string='Purpose', required=True, default='login')
    expires_at = fields.Datetime(
        string='Expires At',
        required=True,
    )
    verified = fields.Boolean(
        string='Verified',
        default=False,
    )
    attempts = fields.Integer(
        string='Verification Attempts',
        default=0,
    )
    ip_address = fields.Char(string='IP Address')
    user_agent = fields.Char(string='User Agent')

    _sql_constraints = [
        ('phone_purpose_unique',
         'unique(phone, purpose, verified)',
         'Only one active OTP per phone and purpose!')
    ]

    @api.model
    def generate_otp(self, phone, purpose='login', ip_address=None, user_agent=None):
        """
        Generate a new OTP for the given phone number.

        Args:
            phone: Phone number in +880 format
            purpose: OTP purpose (login, register, etc.)
            ip_address: Client IP address
            user_agent: Client user agent

        Returns:
            str: The generated OTP (plain text for SMS sending)
        """
        # Invalidate existing OTPs for this phone and purpose
        existing = self.search([
            ('phone', '=', phone),
            ('purpose', '=', purpose),
            ('verified', '=', False),
        ])
        existing.unlink()

        # Generate 6-digit OTP
        otp = ''.join([str(random.randint(0, 9)) for _ in range(6)])

        # Hash OTP for storage
        otp_hash = self._hash_otp(otp)

        # Create OTP record
        self.create({
            'phone': phone,
            'otp_hash': otp_hash,
            'purpose': purpose,
            'expires_at': datetime.now() + timedelta(minutes=5),
            'ip_address': ip_address,
            'user_agent': user_agent,
        })

        _logger.info(f'OTP generated for {phone}, purpose: {purpose}')

        return otp

    @api.model
    def verify_otp(self, phone, otp, purpose='login'):
        """
        Verify an OTP.

        Args:
            phone: Phone number
            otp: OTP to verify
            purpose: Expected purpose

        Returns:
            bool: True if valid, False otherwise

        Raises:
            ValidationError: If OTP is invalid, expired, or max attempts reached
        """
        otp_hash = self._hash_otp(otp)

        record = self.search([
            ('phone', '=', phone),
            ('purpose', '=', purpose),
            ('verified', '=', False),
            ('expires_at', '>', datetime.now()),
        ], limit=1)

        if not record:
            _logger.warning(f'No valid OTP found for {phone}')
            return False

        # Check max attempts (5 attempts allowed)
        if record.attempts >= 5:
            _logger.warning(f'Max OTP attempts reached for {phone}')
            record.unlink()
            return False

        # Increment attempts
        record.attempts += 1

        # Verify hash
        if record.otp_hash != otp_hash:
            _logger.warning(f'Invalid OTP for {phone}, attempt {record.attempts}')
            return False

        # Mark as verified
        record.verified = True
        _logger.info(f'OTP verified for {phone}')

        return True

    def _hash_otp(self, otp):
        """Hash OTP using SHA-256 with salt"""
        salt = self.env['ir.config_parameter'].sudo().get_param(
            'mobile.otp.salt',
            default='smart_dairy_otp_salt'
        )
        return hashlib.sha256(f'{otp}{salt}'.encode()).hexdigest()

    @api.model
    def cleanup_expired(self):
        """Cron job to clean up expired OTPs"""
        expired = self.search([
            ('expires_at', '<', datetime.now()),
        ])
        count = len(expired)
        expired.unlink()
        _logger.info(f'Cleaned up {count} expired OTPs')


class MobileDevice(models.Model):
    _name = 'mobile.device'
    _description = 'Mobile Device Registration'
    _order = 'last_active desc'

    user_id = fields.Many2one(
        'res.users',
        string='User',
        required=True,
        ondelete='cascade',
        index=True,
    )
    device_id = fields.Char(
        string='Device ID',
        required=True,
        index=True,
    )
    device_name = fields.Char(string='Device Name')
    platform = fields.Selection([
        ('ios', 'iOS'),
        ('android', 'Android'),
    ], string='Platform')
    fcm_token = fields.Char(string='FCM Token')
    app_version = fields.Char(string='App Version')
    os_version = fields.Char(string='OS Version')
    last_active = fields.Datetime(
        string='Last Active',
        default=fields.Datetime.now,
    )
    is_active = fields.Boolean(string='Is Active', default=True)

    _sql_constraints = [
        ('user_device_unique',
         'unique(user_id, device_id)',
         'Device already registered for this user!')
    ]

    @api.model
    def register_device(self, user_id, device_id, **kwargs):
        """Register or update device"""
        existing = self.search([
            ('user_id', '=', user_id),
            ('device_id', '=', device_id),
        ], limit=1)

        values = {
            'user_id': user_id,
            'device_id': device_id,
            'last_active': datetime.now(),
            **kwargs,
        }

        if existing:
            existing.write(values)
            return existing
        else:
            return self.create(values)
```

**Task 2: OTP API Endpoints Enhancement (3h)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/auth_controller.py
# (Add to existing AuthController)

@http.route('/api/v1/auth/otp/resend', type='json', auth='none', methods=['POST'], csrf=False)
@rate_limit(max_requests=3, window_seconds=300)  # 3 resends per 5 minutes
def resend_otp(self, **kwargs):
    """
    Resend OTP to phone number.

    Request:
        {
            "phone": "+8801712345678",
            "purpose": "login"
        }
    """
    phone = kwargs.get('phone', '').strip()
    purpose = kwargs.get('purpose', 'login')

    if not self._validate_bangladesh_phone(phone):
        return api_response(
            success=False,
            message='Invalid phone number format',
            error_code='INVALID_PHONE'
        )

    # Check cooldown (60 seconds between resends)
    last_otp = self.env['mobile.otp'].sudo().search([
        ('phone', '=', phone),
        ('purpose', '=', purpose),
        ('create_date', '>', datetime.now() - timedelta(seconds=60)),
    ], limit=1)

    if last_otp:
        remaining = 60 - (datetime.now() - last_otp.create_date).seconds
        return api_response(
            success=False,
            message=f'Please wait {remaining} seconds before requesting new OTP',
            error_code='COOLDOWN_ACTIVE',
            data={'resend_in': remaining}
        )

    # Generate and send new OTP
    otp = self.env['mobile.otp'].sudo().generate_otp(phone, purpose)

    # Send SMS
    sms_service = self.env['sms.service'].sudo()
    sms_sent = sms_service.send_otp_sms(phone, otp)

    if not sms_sent:
        return api_response(
            success=False,
            message='Failed to send OTP',
            error_code='SMS_FAILED'
        )

    return api_response(
        success=True,
        data={
            'phone': phone,
            'expires_in': 300,
            'resend_in': 60,
        },
        message='OTP resent successfully'
    )
```

**Task 3: Rate Limiting Enhancement (2h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/rate_limiter.py
import redis
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class RateLimiter(models.AbstractModel):
    _name = 'mobile.rate.limiter'
    _description = 'Rate Limiter Service'

    @api.model
    def get_redis_client(self):
        """Get Redis client for rate limiting"""
        redis_url = self.env['ir.config_parameter'].sudo().get_param(
            'mobile.redis.url',
            default='redis://localhost:6379/1'
        )
        try:
            return redis.from_url(redis_url, decode_responses=True)
        except Exception as e:
            _logger.error(f'Redis connection failed: {e}')
            return None

    @api.model
    def check_rate_limit(self, key, max_requests, window_seconds):
        """
        Check if request is within rate limits.

        Args:
            key: Unique identifier for the rate limit (e.g., 'otp:+8801712345678')
            max_requests: Maximum requests allowed
            window_seconds: Time window in seconds

        Returns:
            tuple: (allowed: bool, remaining: int, reset_in: int)
        """
        client = self.get_redis_client()

        if not client:
            # Allow if Redis unavailable (fail open)
            return True, max_requests, 0

        full_key = f'rate_limit:{key}'

        try:
            current = client.get(full_key)

            if current is None:
                # First request
                client.setex(full_key, window_seconds, 1)
                return True, max_requests - 1, window_seconds

            current = int(current)

            if current >= max_requests:
                ttl = client.ttl(full_key)
                return False, 0, ttl

            # Increment
            client.incr(full_key)
            return True, max_requests - current - 1, client.ttl(full_key)

        except Exception as e:
            _logger.error(f'Rate limit check failed: {e}')
            return True, max_requests, 0

    @api.model
    def reset_rate_limit(self, key):
        """Reset rate limit for a key"""
        client = self.get_redis_client()
        if client:
            client.delete(f'rate_limit:{key}')
```

#### Dev 2 — Full-Stack (8h)

**Task 1: SSL Wireless SMS Gateway Integration (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/sms_service.py
import requests
import logging
from odoo import models, api

_logger = logging.getLogger(__name__)


class SMSService(models.AbstractModel):
    _name = 'sms.service'
    _description = 'SMS Gateway Service'

    @api.model
    def get_config(self):
        """Get SMS gateway configuration"""
        ICP = self.env['ir.config_parameter'].sudo()
        return {
            'api_url': ICP.get_param('sms.gateway.url', 'https://smsplus.sslwireless.com/api/v3/send-sms'),
            'api_token': ICP.get_param('sms.gateway.token'),
            'sid': ICP.get_param('sms.gateway.sid', 'SMARTDAIRYBRAND'),
            'enabled': ICP.get_param('sms.gateway.enabled', 'true') == 'true',
        }

    @api.model
    def send_sms(self, phone, message, sms_type='text'):
        """
        Send SMS via SSL Wireless.

        Args:
            phone: Phone number (with or without country code)
            message: SMS content
            sms_type: 'text' or 'unicode'

        Returns:
            bool: Success status
        """
        config = self.get_config()

        if not config['enabled']:
            _logger.info(f'SMS disabled. Would send to {phone}: {message}')
            return True

        if not config['api_token']:
            _logger.error('SMS gateway token not configured')
            return False

        # Normalize phone number
        phone = self._normalize_phone(phone)

        payload = {
            'api_token': config['api_token'],
            'sid': config['sid'],
            'msisdn': phone,
            'sms': message,
            'csms_id': self._generate_csms_id(),
        }

        try:
            response = requests.post(
                config['api_url'],
                json=payload,
                timeout=30
            )
            response.raise_for_status()

            result = response.json()

            if result.get('status') == 'SUCCESS':
                _logger.info(f'SMS sent to {phone}')
                self._log_sms(phone, message, 'sent', result)
                return True
            else:
                _logger.error(f'SMS failed: {result}')
                self._log_sms(phone, message, 'failed', result)
                return False

        except requests.RequestException as e:
            _logger.error(f'SMS request failed: {e}')
            self._log_sms(phone, message, 'error', {'error': str(e)})
            return False

    @api.model
    def send_otp_sms(self, phone, otp):
        """Send OTP SMS with template"""
        message = f'আপনার Smart Dairy OTP কোড: {otp}\nএই কোডটি ৫ মিনিটের মধ্যে মেয়াদ শেষ হবে।\n\nYour Smart Dairy OTP is: {otp}\nValid for 5 minutes.'
        return self.send_sms(phone, message, sms_type='unicode')

    def _normalize_phone(self, phone):
        """Normalize phone to local format for SMS gateway"""
        import re
        phone = re.sub(r'[^\d]', '', phone)
        if phone.startswith('880'):
            return phone
        if phone.startswith('0'):
            return '880' + phone[1:]
        return phone

    def _generate_csms_id(self):
        """Generate unique CSMS ID"""
        import uuid
        return str(uuid.uuid4())[:20]

    def _log_sms(self, phone, message, status, response):
        """Log SMS for tracking"""
        self.env['sms.log'].sudo().create({
            'phone': phone,
            'message': message[:500],
            'status': status,
            'response': str(response)[:1000],
        })


class SMSLog(models.Model):
    _name = 'sms.log'
    _description = 'SMS Log'
    _order = 'create_date desc'

    phone = fields.Char(string='Phone Number', required=True, index=True)
    message = fields.Text(string='Message')
    status = fields.Selection([
        ('sent', 'Sent'),
        ('failed', 'Failed'),
        ('error', 'Error'),
    ], string='Status', required=True)
    response = fields.Text(string='Gateway Response')
```

**Task 2: Firebase Phone Auth Configuration (2h)**

```javascript
// Firebase Cloud Functions for phone auth
// functions/index.js
const functions = require('firebase-functions');
const admin = require('firebase-admin');

admin.initializeApp();

// Verify custom token and link with phone
exports.linkPhoneWithOdoo = functions.https.onCall(async (data, context) => {
  const { phone, odooUserId, accessToken } = data;

  if (!context.auth) {
    throw new functions.https.HttpsError('unauthenticated', 'User must be authenticated');
  }

  try {
    // Verify the phone matches the authenticated user
    const user = await admin.auth().getUser(context.auth.uid);

    if (user.phoneNumber !== phone) {
      throw new functions.https.HttpsError('permission-denied', 'Phone mismatch');
    }

    // Store mapping in Firestore
    await admin.firestore().collection('user_mappings').doc(context.auth.uid).set({
      odooUserId,
      phone,
      linkedAt: admin.firestore.FieldValue.serverTimestamp(),
    });

    return { success: true };
  } catch (error) {
    console.error('Link phone error:', error);
    throw new functions.https.HttpsError('internal', error.message);
  }
});
```

**Task 3: Authentication Monitoring Dashboard (2h)**

```python
# odoo/addons/smart_dairy_mobile_api/views/auth_dashboard_views.xml
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- OTP Statistics Dashboard -->
    <record id="view_otp_statistics_dashboard" model="ir.ui.view">
        <field name="name">OTP Statistics Dashboard</field>
        <field name="model">mobile.otp</field>
        <field name="arch" type="xml">
            <dashboard>
                <view type="graph"/>
                <group>
                    <aggregate name="total_otps" string="Total OTPs"
                               field="id" group_operator="count"/>
                    <aggregate name="verified_otps" string="Verified OTPs"
                               field="id" group_operator="count"
                               domain="[('verified', '=', True)]"/>
                    <formula name="verification_rate" string="Verification Rate"
                             value="verified_otps / total_otps * 100"
                             widget="percentage"/>
                </group>
            </dashboard>
        </field>
    </record>

    <!-- OTP List View -->
    <record id="view_mobile_otp_tree" model="ir.ui.view">
        <field name="name">mobile.otp.tree</field>
        <field name="model">mobile.otp</field>
        <field name="arch" type="xml">
            <tree string="OTP Records">
                <field name="phone"/>
                <field name="purpose"/>
                <field name="verified" widget="boolean"/>
                <field name="attempts"/>
                <field name="expires_at"/>
                <field name="create_date"/>
            </tree>
        </field>
    </record>

    <!-- Menu Items -->
    <menuitem id="menu_mobile_auth" name="Mobile Authentication"
              parent="base.menu_administration" sequence="50"/>

    <menuitem id="menu_otp_records" name="OTP Records"
              parent="menu_mobile_auth" action="action_mobile_otp"/>

    <menuitem id="menu_device_registrations" name="Device Registrations"
              parent="menu_mobile_auth" action="action_mobile_device"/>

    <menuitem id="menu_sms_logs" name="SMS Logs"
              parent="menu_mobile_auth" action="action_sms_log"/>
</odoo>
```

#### Dev 3 — Mobile Lead (8h)

**Task 1: Auth Feature Package Setup (2h)**

```dart
// packages/auth/pubspec.yaml
name: smart_dairy_auth
description: Authentication package for Smart Dairy mobile apps
version: 1.0.0
publish_to: none

environment:
  sdk: '>=3.2.0 <4.0.0'
  flutter: '>=3.16.0'

dependencies:
  flutter:
    sdk: flutter

  smart_dairy_core:
    path: ../core
  smart_dairy_api_client:
    path: ../api_client

  # State Management
  flutter_riverpod: ^2.4.9
  riverpod_annotation: ^2.3.3

  # Firebase Auth
  firebase_auth: ^4.16.0
  google_sign_in: ^6.1.6
  flutter_facebook_auth: ^6.0.4

  # Biometrics
  local_auth: ^2.1.8

  # Secure Storage
  flutter_secure_storage: ^9.0.0

  # UI
  pin_code_fields: ^8.0.1

  # Utilities
  phone_numbers_parser: ^8.2.0

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  build_runner: ^2.4.7
  riverpod_generator: ^2.3.9
  mockito: ^5.4.4
  mocktail: ^1.0.1
```

**Task 2: OTP Screen UI (4h)**

```dart
// packages/auth/lib/src/presentation/screens/otp_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:pin_code_fields/pin_code_fields.dart';
import 'dart:async';

class OtpScreen extends ConsumerStatefulWidget {
  final String phoneNumber;
  final String? purpose;

  const OtpScreen({
    super.key,
    required this.phoneNumber,
    this.purpose,
  });

  @override
  ConsumerState<OtpScreen> createState() => _OtpScreenState();
}

class _OtpScreenState extends ConsumerState<OtpScreen> {
  final _otpController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  Timer? _timer;
  int _remainingSeconds = 60;
  bool _canResend = false;
  bool _isVerifying = false;

  @override
  void initState() {
    super.initState();
    _startTimer();
  }

  @override
  void dispose() {
    _timer?.cancel();
    _otpController.dispose();
    super.dispose();
  }

  void _startTimer() {
    _remainingSeconds = 60;
    _canResend = false;
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_remainingSeconds > 0) {
        setState(() => _remainingSeconds--);
      } else {
        setState(() => _canResend = true);
        timer.cancel();
      }
    });
  }

  Future<void> _verifyOtp(String otp) async {
    if (otp.length != 6) return;

    setState(() => _isVerifying = true);

    try {
      final authNotifier = ref.read(authStateProvider.notifier);
      final result = await authNotifier.verifyOtp(
        phone: widget.phoneNumber,
        otp: otp,
      );

      result.when(
        success: (data) {
          // Navigate to home or profile setup
          if (data.user.isNewUser) {
            context.go('/auth/profile-setup');
          } else {
            context.go('/home');
          }
        },
        error: (message, code) {
          _showError(message);
          _otpController.clear();
        },
      );
    } finally {
      if (mounted) {
        setState(() => _isVerifying = false);
      }
    }
  }

  Future<void> _resendOtp() async {
    if (!_canResend) return;

    try {
      final authNotifier = ref.read(authStateProvider.notifier);
      await authNotifier.sendOtp(widget.phoneNumber);

      _startTimer();

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('OTP resent successfully')),
      );
    } catch (e) {
      _showError('Failed to resend OTP');
    }
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Verify OTP'),
        centerTitle: true,
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const SizedBox(height: 40),

                // Icon
                Container(
                  width: 80,
                  height: 80,
                  decoration: BoxDecoration(
                    color: theme.primaryColor.withOpacity(0.1),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(
                    Icons.sms_outlined,
                    size: 40,
                    color: theme.primaryColor,
                  ),
                ),

                const SizedBox(height: 32),

                // Title
                Text(
                  'Enter Verification Code',
                  style: theme.textTheme.headlineSmall?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),

                const SizedBox(height: 8),

                // Subtitle
                Text(
                  'We sent a 6-digit code to\n${widget.phoneNumber}',
                  textAlign: TextAlign.center,
                  style: theme.textTheme.bodyMedium?.copyWith(
                    color: Colors.grey[600],
                  ),
                ),

                const SizedBox(height: 40),

                // OTP Input
                PinCodeTextField(
                  appContext: context,
                  controller: _otpController,
                  length: 6,
                  obscureText: false,
                  animationType: AnimationType.fade,
                  keyboardType: TextInputType.number,
                  autoFocus: true,
                  pinTheme: PinTheme(
                    shape: PinCodeFieldShape.box,
                    borderRadius: BorderRadius.circular(12),
                    fieldHeight: 56,
                    fieldWidth: 48,
                    activeFillColor: Colors.white,
                    inactiveFillColor: Colors.grey[100],
                    selectedFillColor: theme.primaryColor.withOpacity(0.1),
                    activeColor: theme.primaryColor,
                    inactiveColor: Colors.grey[300],
                    selectedColor: theme.primaryColor,
                  ),
                  animationDuration: const Duration(milliseconds: 300),
                  enableActiveFill: true,
                  onCompleted: _verifyOtp,
                  onChanged: (value) {},
                ),

                const SizedBox(height: 24),

                // Resend Timer
                if (!_canResend)
                  Text(
                    'Resend code in ${_remainingSeconds}s',
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: Colors.grey[600],
                    ),
                  )
                else
                  TextButton(
                    onPressed: _resendOtp,
                    child: const Text('Resend OTP'),
                  ),

                const Spacer(),

                // Verify Button
                SizedBox(
                  width: double.infinity,
                  height: 56,
                  child: ElevatedButton(
                    onPressed: _isVerifying
                        ? null
                        : () => _verifyOtp(_otpController.text),
                    child: _isVerifying
                        ? const SizedBox(
                            width: 24,
                            height: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : const Text(
                            'Verify',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                  ),
                ),

                const SizedBox(height: 16),

                // Change Number
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Change Phone Number'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
```

**Task 3: Phone Input Screen (2h)**

```dart
// packages/auth/lib/src/presentation/screens/phone_input_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:phone_numbers_parser/phone_numbers_parser.dart';

class PhoneInputScreen extends ConsumerStatefulWidget {
  const PhoneInputScreen({super.key});

  @override
  ConsumerState<PhoneInputScreen> createState() => _PhoneInputScreenState();
}

class _PhoneInputScreenState extends ConsumerState<PhoneInputScreen> {
  final _phoneController = TextEditingController();
  final _formKey = GlobalKey<FormState>();
  bool _isLoading = false;

  String _selectedCountryCode = '+880';
  final List<Map<String, String>> _countryCodes = [
    {'code': '+880', 'name': 'Bangladesh', 'flag': '🇧🇩'},
    {'code': '+91', 'name': 'India', 'flag': '🇮🇳'},
  ];

  @override
  void dispose() {
    _phoneController.dispose();
    super.dispose();
  }

  String? _validatePhone(String? value) {
    if (value == null || value.isEmpty) {
      return 'Phone number is required';
    }

    final fullNumber = '$_selectedCountryCode$value';

    try {
      final phone = PhoneNumber.parse(fullNumber);
      if (!phone.isValid()) {
        return 'Invalid phone number';
      }
    } catch (e) {
      return 'Invalid phone number format';
    }

    // Bangladesh specific validation
    if (_selectedCountryCode == '+880') {
      if (!RegExp(r'^1[3-9]\d{8}$').hasMatch(value)) {
        return 'Enter valid Bangladeshi number (e.g., 1712345678)';
      }
    }

    return null;
  }

  Future<void> _sendOtp() async {
    if (!_formKey.currentState!.validate()) return;

    final phone = '$_selectedCountryCode${_phoneController.text}';

    setState(() => _isLoading = true);

    try {
      final authNotifier = ref.read(authStateProvider.notifier);
      final result = await authNotifier.sendOtp(phone);

      result.when(
        success: (_) {
          context.push('/auth/otp', extra: phone);
        },
        error: (message, code) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(message), backgroundColor: Colors.red),
          );
        },
      );
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 40),

                // Logo
                Center(
                  child: Image.asset(
                    'assets/images/logo.png',
                    height: 80,
                  ),
                ),

                const SizedBox(height: 40),

                // Title
                Text(
                  'Welcome to Smart Dairy',
                  style: theme.textTheme.headlineMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),

                const SizedBox(height: 8),

                Text(
                  'Enter your phone number to continue',
                  style: theme.textTheme.bodyLarge?.copyWith(
                    color: Colors.grey[600],
                  ),
                ),

                const SizedBox(height: 40),

                // Phone Input
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Country Code Dropdown
                    Container(
                      height: 56,
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey[300]!),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: DropdownButtonHideUnderline(
                        child: DropdownButton<String>(
                          value: _selectedCountryCode,
                          items: _countryCodes.map((country) {
                            return DropdownMenuItem(
                              value: country['code'],
                              child: Row(
                                children: [
                                  Text(country['flag']!),
                                  const SizedBox(width: 8),
                                  Text(country['code']!),
                                ],
                              ),
                            );
                          }).toList(),
                          onChanged: (value) {
                            if (value != null) {
                              setState(() => _selectedCountryCode = value);
                            }
                          },
                        ),
                      ),
                    ),

                    const SizedBox(width: 12),

                    // Phone Number Field
                    Expanded(
                      child: TextFormField(
                        controller: _phoneController,
                        keyboardType: TextInputType.phone,
                        validator: _validatePhone,
                        decoration: InputDecoration(
                          hintText: '1712345678',
                          prefixIcon: const Icon(Icons.phone_outlined),
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),

                const SizedBox(height: 24),

                // Continue Button
                SizedBox(
                  width: double.infinity,
                  height: 56,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _sendOtp,
                    child: _isLoading
                        ? const SizedBox(
                            width: 24,
                            height: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                        : const Text(
                            'Continue',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                  ),
                ),

                const Spacer(),

                // Social Login Options
                const _SocialLoginSection(),

                const SizedBox(height: 24),

                // Terms
                Center(
                  child: Text(
                    'By continuing, you agree to our Terms of Service\nand Privacy Policy',
                    textAlign: TextAlign.center,
                    style: theme.textTheme.bodySmall?.copyWith(
                      color: Colors.grey[600],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class _SocialLoginSection extends StatelessWidget {
  const _SocialLoginSection();

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          children: [
            const Expanded(child: Divider()),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                'Or continue with',
                style: TextStyle(color: Colors.grey[600]),
              ),
            ),
            const Expanded(child: Divider()),
          ],
        ),

        const SizedBox(height: 24),

        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Google
            _SocialButton(
              onPressed: () {
                // TODO: Implement Google Sign-In
              },
              icon: 'assets/icons/google.svg',
              label: 'Google',
            ),

            const SizedBox(width: 16),

            // Facebook
            _SocialButton(
              onPressed: () {
                // TODO: Implement Facebook Login
              },
              icon: 'assets/icons/facebook.svg',
              label: 'Facebook',
            ),

            const SizedBox(width: 16),

            // Email
            _SocialButton(
              onPressed: () {
                context.push('/auth/email-login');
              },
              icon: 'assets/icons/email.svg',
              label: 'Email',
            ),
          ],
        ),
      ],
    );
  }
}

class _SocialButton extends StatelessWidget {
  final VoidCallback onPressed;
  final String icon;
  final String label;

  const _SocialButton({
    required this.onPressed,
    required this.icon,
    required this.label,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onPressed,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        width: 80,
        height: 56,
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey[300]!),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SvgPicture.asset(icon, width: 24, height: 24),
            const SizedBox(height: 4),
            Text(
              label,
              style: const TextStyle(fontSize: 10),
            ),
          ],
        ),
      ),
    );
  }
}
```

**End-of-Day 261 Deliverables:**

- [ ] OTP model and storage implemented
- [ ] SMS gateway integration complete
- [ ] Rate limiting service operational
- [ ] Phone input screen UI complete
- [ ] OTP verification screen UI complete
- [ ] Firebase phone auth configured

---

### Days 262-270 Breakdown Summary

Due to document size constraints, Days 262-270 follow the same detailed pattern:

**Day 262: OTP Verification & Error Handling**
- Dev 1: OTP verification edge cases, error codes
- Dev 2: SMS delivery monitoring, fallback gateways
- Dev 3: OTP auto-fill, error states UI, retry logic

**Day 263: Email Authentication**
- Dev 1: Email login/register API endpoints
- Dev 2: Email verification flow, templates
- Dev 3: Email login screen, form validation, forgot password

**Day 264: Social Login Integration**
- Dev 1: Google/Facebook OAuth callback handlers
- Dev 2: Firebase Social Auth configuration
- Dev 3: Google Sign-In button, Facebook Login button, account linking

**Day 265: Token Management & Secure Storage**
- Dev 1: Token refresh endpoint optimization
- Dev 2: Token blacklist Redis storage
- Dev 3: flutter_secure_storage setup, token auto-refresh, interceptors

**Day 266: Biometric Authentication**
- Dev 1: Biometric settings API endpoints
- Dev 2: Device capability detection
- Dev 3: local_auth integration, biometric prompt, fallback to PIN

**Day 267: Profile Setup Flow**
- Dev 1: Profile update API, avatar upload
- Dev 2: Image processing, CDN upload
- Dev 3: Profile setup screens, avatar picker, name input

**Day 268: Session Management**
- Dev 1: Multi-device session API
- Dev 2: Session tracking, device management
- Dev 3: Active sessions list, logout other devices, session UI

**Day 269: Password Reset Flow**
- Dev 1: Password reset email API
- Dev 2: Reset token generation, email templates
- Dev 3: Forgot password screen, reset password screen, success state

**Day 270: Integration Testing & Documentation**
- Dev 1: API documentation (OpenAPI/Swagger)
- Dev 2: E2E test scenarios, CI integration
- Dev 3: Widget tests, integration tests, auth flow E2E

---

## 4. Technical Specifications

### 4.1 Authentication State Machine

```dart
enum AuthStatus {
  initial,          // App just launched
  unauthenticated,  // No valid session
  authenticating,   // Login in progress
  authenticated,    // Valid session
  sessionExpired,   // Needs refresh
  error,            // Auth error
}

@freezed
class AuthState with _$AuthState {
  const factory AuthState({
    required AuthStatus status,
    User? user,
    String? accessToken,
    String? refreshToken,
    DateTime? tokenExpiry,
    String? errorMessage,
  }) = _AuthState;

  factory AuthState.initial() => const AuthState(status: AuthStatus.initial);
}
```

### 4.2 Token Storage Schema

```dart
// Secure storage keys
class AuthStorageKeys {
  static const accessToken = 'auth_access_token';
  static const refreshToken = 'auth_refresh_token';
  static const tokenExpiry = 'auth_token_expiry';
  static const userId = 'auth_user_id';
  static const biometricEnabled = 'auth_biometric_enabled';
  static const lastLoginMethod = 'auth_last_login_method';
}
```

### 4.3 API Error Codes

| Code | Description | User Action |
|------|-------------|-------------|
| `AUTH_MISSING` | No auth header | Re-login |
| `AUTH_FAILED` | Invalid token | Re-login |
| `INVALID_OTP` | Wrong OTP entered | Retry or resend |
| `OTP_EXPIRED` | OTP has expired | Resend OTP |
| `INVALID_PHONE` | Bad phone format | Correct number |
| `RATE_LIMIT_EXCEEDED` | Too many requests | Wait and retry |
| `ACCOUNT_DISABLED` | Account suspended | Contact support |
| `DEVICE_NOT_REGISTERED` | Unknown device | Re-register |

---

## 5. Testing & Validation

### 5.1 Test Scenarios

| Scenario | Type | Priority |
|----------|------|----------|
| OTP sent to valid BD number | Integration | P0 |
| OTP verification success | Integration | P0 |
| OTP verification failure (wrong code) | Integration | P0 |
| OTP expiry handling | Integration | P1 |
| Rate limiting triggers | Integration | P1 |
| Google Sign-In flow | E2E | P0 |
| Facebook Login flow | E2E | P0 |
| Token refresh on 401 | Integration | P0 |
| Biometric auth on supported device | Device | P1 |
| Offline token validation | Unit | P1 |

### 5.2 Test Coverage Requirements

| Component | Required Coverage |
|-----------|------------------|
| Auth providers | >90% |
| OTP service | >85% |
| Token management | >85% |
| Auth screens | >70% |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| SMS delivery failure | Medium | High | Fallback gateway, retry logic |
| Social login rejection | Low | Medium | Clear error messages |
| Token theft | Low | High | Secure storage, short expiry |
| Biometric unavailable | Medium | Low | Graceful fallback to PIN |
| Rate limit bypass | Low | Medium | Server-side enforcement |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Milestone 51 packages | Phase 6 | Day 261 |
| SMS gateway account | IT Admin | Day 261 |
| Google OAuth credentials | Google Cloud | Day 264 |
| Facebook App | Facebook Dev | Day 264 |

### 7.2 Output Handoffs

| Deliverable | Recipient | Handoff Day |
|-------------|-----------|-------------|
| Auth package | All apps | 270 |
| Auth screens | Customer App | 270 |
| API documentation | QA Team | 270 |

---

## Milestone Sign-off Checklist

- [ ] OTP authentication working end-to-end
- [ ] Email login functional
- [ ] Google Sign-In integrated
- [ ] Facebook Login integrated
- [ ] Biometric auth working
- [ ] Token refresh automatic
- [ ] All screens implemented
- [ ] Tests passing (>85% coverage)
- [ ] Documentation complete
- [ ] Security review passed
- [ ] Ready for Milestone 53

---

**Document Prepared By:** Smart Dairy Development Team
**Review Status:** Pending Technical Review
**Next Milestone:** Milestone 53 — Customer App Product Catalog
