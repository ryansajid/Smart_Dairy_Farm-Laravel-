# Milestone 134: Security Testing

## Smart Dairy Digital Smart Portal + ERP - Phase 14: Testing & Documentation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 134 of 150 (4 of 10 in Phase 14)                                       |
| **Title**        | Security Testing                                                       |
| **Phase**        | Phase 14 - Testing & Documentation                                     |
| **Days**         | Days 666-670 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead)        |

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

Execute comprehensive security testing against OWASP Top 10 vulnerabilities, perform penetration testing on all application entry points, validate authentication/authorization mechanisms, and generate a security audit report with remediation recommendations.

### 1.2 Objectives

1. Configure and execute OWASP ZAP automated security scans
2. Test for OWASP Top 10 2021 vulnerabilities
3. Perform SQL injection and NoSQL injection testing
4. Execute XSS (Cross-Site Scripting) vulnerability testing
5. Validate CSRF protection mechanisms
6. Test authentication and session management
7. Perform API security testing
8. Generate security audit report with remediation plan

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| OWASP ZAP Configuration           | Dev 1  | YAML/Config       | 666     |
| OWASP Top 10 Scan Report          | Dev 1  | HTML/PDF          | 667     |
| SQL Injection Test Results        | Dev 1  | Markdown          | 667     |
| XSS/CSRF Test Results             | Dev 3  | Markdown          | 668     |
| Authentication Security Report    | Dev 2  | Markdown          | 668     |
| API Security Test Results         | Dev 2  | Markdown          | 669     |
| Penetration Test Report           | All    | PDF               | 669     |
| Security Audit Report             | All    | PDF               | 670     |
| Remediation Tracking Sheet        | All    | Excel             | 670     |

### 1.4 Prerequisites

- Milestone 133 complete (UAT preparation)
- Staging environment with production-like configuration
- OWASP ZAP installed and configured
- Burp Suite license (if using)
- Security testing credentials

### 1.5 Success Criteria

- [ ] Zero critical vulnerabilities in final report
- [ ] Zero high severity vulnerabilities (or documented waiver)
- [ ] All OWASP Top 10 categories tested
- [ ] Penetration test passed
- [ ] All authentication flows validated
- [ ] API security validated
- [ ] Security audit report approved

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-SEC-001  | RFP    | OWASP Top 10 compliance                  | 666-670 | Security testing            |
| SRS-SEC-001  | SRS    | Penetration test required                | 669     | Penetration testing         |
| BRD-SEC-001  | BRD    | Security audit completion                | 670     | Security audit              |
| TECH-SEC-001 | Tech   | Authentication/Authorization tests       | 668     | Auth security tests         |
| TECH-SEC-002 | Tech   | Data encryption validation               | 668     | Encryption tests            |
| IMPL-SEC-01  | Guide  | SQL injection prevention                 | 667     | Injection tests             |

---

## 3. Day-by-Day Breakdown

### Day 666 - Security Tool Setup & Configuration

**Objective:** Configure OWASP ZAP and security testing tools for comprehensive scanning.

#### Dev 1 - Backend Lead (8h)

**Task 1: OWASP ZAP Configuration (4h)**

```yaml
# config/zap-config.yaml
# OWASP ZAP Configuration for Smart Dairy Security Testing

env:
  contexts:
    - name: "Smart Dairy Web App"
      urls:
        - "https://staging.smartdairy.com"
      includePaths:
        - "https://staging.smartdairy.com.*"
      excludePaths:
        - "https://staging.smartdairy.com/static/.*"
        - "https://staging.smartdairy.com/web/image/.*"
      authentication:
        method: "form"
        parameters:
          loginUrl: "https://staging.smartdairy.com/web/login"
          loginRequestData: "login={%username%}&password={%password%}&csrf_token={%csrf%}"
        verification:
          method: "response"
          loggedInRegex: "\\Qo_main_navbar\\E"
          loggedOutRegex: "\\QLog in\\E"
      users:
        - name: "admin"
          credentials:
            username: "admin@smartdairy.com"
            password: "${ADMIN_PASSWORD}"
        - name: "customer"
          credentials:
            username: "test.customer@smartdairy.com"
            password: "${CUSTOMER_PASSWORD}"

jobs:
  - type: "spider"
    parameters:
      context: "Smart Dairy Web App"
      user: "admin"
      maxDuration: 60
      maxDepth: 10
      maxChildren: 0
      acceptCookies: true
      handleODataParametersVisited: false
      handleParameters: "USE_ALL"
      parseComments: true
      parseGit: false
      parseSitemapXml: true

  - type: "spiderAjax"
    parameters:
      context: "Smart Dairy Web App"
      user: "admin"
      maxDuration: 60
      maxCrawlDepth: 10
      numberOfBrowsers: 2
      browserId: "firefox-headless"
      clickDefaultElems: true
      clickElemsOnce: true

  - type: "passiveScan-config"
    parameters:
      maxAlertsPerRule: 10
      scanOnlyInScope: true

  - type: "activeScan"
    parameters:
      context: "Smart Dairy Web App"
      user: "admin"
      policy: "Default Policy"
      maxRuleDurationInMins: 5
      maxScanDurationInMins: 120

  - type: "report"
    parameters:
      template: "traditional-html"
      reportDir: "/zap/reports"
      reportFile: "smart-dairy-security-report"
      reportTitle: "Smart Dairy Security Assessment"
      reportDescription: "OWASP ZAP Security Scan Results"
    risks:
      - "high"
      - "medium"
      - "low"
      - "informational"
```

**Task 2: Automated Security Scan Script (4h)**

```python
# scripts/security/run_security_scan.py
"""Automated security scanning with OWASP ZAP."""
import os
import time
import json
from zapv2 import ZAPv2
from datetime import datetime

class SecurityScanner:
    """OWASP ZAP Security Scanner for Smart Dairy."""

    def __init__(self, zap_address: str = 'localhost', zap_port: int = 8080):
        self.zap = ZAPv2(
            proxies={'http': f'http://{zap_address}:{zap_port}',
                     'https': f'http://{zap_address}:{zap_port}'}
        )
        self.target = os.getenv('TARGET_URL', 'https://staging.smartdairy.com')
        self.results = {
            'scan_date': datetime.now().isoformat(),
            'target': self.target,
            'alerts': [],
            'summary': {}
        }

    def authenticate(self, username: str, password: str):
        """Authenticate to the application."""
        # Set up authentication
        context_id = self.zap.context.new_context('Smart Dairy')
        self.zap.context.include_in_context('Smart Dairy', f'{self.target}.*')

        # Configure form-based authentication
        auth_method = 'formBasedAuthentication'
        login_url = f'{self.target}/web/login'
        login_data = f'login={username}&password={password}'

        self.zap.authentication.set_authentication_method(
            context_id, auth_method,
            f'loginUrl={login_url}&loginRequestData={login_data}'
        )

        return context_id

    def spider_scan(self, context_id: str = None):
        """Run spider scan."""
        print(f"Starting spider scan on {self.target}")
        scan_id = self.zap.spider.scan(self.target, contextname='Smart Dairy')

        while int(self.zap.spider.status(scan_id)) < 100:
            print(f"Spider progress: {self.zap.spider.status(scan_id)}%")
            time.sleep(5)

        print(f"Spider scan complete. URLs found: {len(self.zap.spider.results(scan_id))}")
        return self.zap.spider.results(scan_id)

    def ajax_spider_scan(self):
        """Run AJAX spider for JavaScript-heavy pages."""
        print("Starting AJAX spider scan")
        self.zap.ajaxSpider.scan(self.target)

        while self.zap.ajaxSpider.status == 'running':
            print(f"AJAX Spider status: {self.zap.ajaxSpider.status}")
            time.sleep(5)

        print("AJAX spider complete")

    def active_scan(self):
        """Run active security scan."""
        print("Starting active scan")
        scan_id = self.zap.ascan.scan(self.target)

        while int(self.zap.ascan.status(scan_id)) < 100:
            print(f"Active scan progress: {self.zap.ascan.status(scan_id)}%")
            time.sleep(10)

        print("Active scan complete")

    def get_alerts(self):
        """Get all security alerts."""
        alerts = self.zap.core.alerts(baseurl=self.target)
        self.results['alerts'] = alerts

        # Summarize by risk level
        risk_summary = {'High': 0, 'Medium': 0, 'Low': 0, 'Informational': 0}
        for alert in alerts:
            risk_summary[alert['risk']] = risk_summary.get(alert['risk'], 0) + 1

        self.results['summary'] = risk_summary
        return alerts

    def generate_report(self, output_dir: str = './reports'):
        """Generate security report."""
        os.makedirs(output_dir, exist_ok=True)

        # HTML Report
        html_report = self.zap.core.htmlreport()
        with open(f'{output_dir}/security_report.html', 'w') as f:
            f.write(html_report)

        # JSON Results
        with open(f'{output_dir}/security_results.json', 'w') as f:
            json.dump(self.results, f, indent=2)

        # Summary Report
        summary_report = self._generate_summary()
        with open(f'{output_dir}/security_summary.md', 'w') as f:
            f.write(summary_report)

        print(f"Reports generated in {output_dir}")

    def _generate_summary(self) -> str:
        """Generate markdown summary report."""
        summary = f"""# Smart Dairy Security Scan Summary

## Scan Information
- **Target:** {self.results['target']}
- **Scan Date:** {self.results['scan_date']}

## Risk Summary

| Risk Level | Count |
|------------|-------|
| High | {self.results['summary'].get('High', 0)} |
| Medium | {self.results['summary'].get('Medium', 0)} |
| Low | {self.results['summary'].get('Low', 0)} |
| Informational | {self.results['summary'].get('Informational', 0)} |

## High Risk Findings

"""
        high_alerts = [a for a in self.results['alerts'] if a['risk'] == 'High']
        for alert in high_alerts:
            summary += f"""### {alert['name']}
- **URL:** {alert['url']}
- **Description:** {alert['description'][:200]}...
- **Solution:** {alert['solution'][:200]}...

"""
        return summary

    def run_full_scan(self, username: str, password: str):
        """Run complete security scan."""
        print("=" * 50)
        print("SMART DAIRY SECURITY SCAN")
        print("=" * 50)

        # Authenticate
        context_id = self.authenticate(username, password)

        # Spider
        self.spider_scan(context_id)

        # AJAX Spider
        self.ajax_spider_scan()

        # Active Scan
        self.active_scan()

        # Get Results
        alerts = self.get_alerts()

        # Generate Reports
        self.generate_report()

        # Print Summary
        print("\n" + "=" * 50)
        print("SCAN COMPLETE")
        print(f"High: {self.results['summary'].get('High', 0)}")
        print(f"Medium: {self.results['summary'].get('Medium', 0)}")
        print(f"Low: {self.results['summary'].get('Low', 0)}")
        print("=" * 50)

        return self.results


if __name__ == '__main__':
    scanner = SecurityScanner()
    scanner.run_full_scan(
        username=os.getenv('ADMIN_USER', 'admin@smartdairy.com'),
        password=os.getenv('ADMIN_PASSWORD')
    )
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Security CI/CD Pipeline (4h)**

```yaml
# .github/workflows/security-scan.yml
name: Security Scan

on:
  push:
    branches: [develop, main]
  pull_request:
    branches: [develop, main]
  schedule:
    - cron: '0 2 * * *'  # Daily at 2 AM

jobs:
  dependency-scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Run Snyk to check for vulnerabilities (Python)
        uses: snyk/actions/python@master
        env:
          SNYK_TOKEN: ${{ secrets.SNYK_TOKEN }}
        with:
          args: --severity-threshold=high

      - name: Run npm audit
        run: |
          npm audit --audit-level=high

      - name: Run Bandit (Python SAST)
        run: |
          pip install bandit
          bandit -r odoo/addons -f json -o bandit-report.json || true

      - name: Upload Bandit report
        uses: actions/upload-artifact@v4
        with:
          name: bandit-report
          path: bandit-report.json

  owasp-zap-scan:
    runs-on: ubuntu-latest
    needs: dependency-scan
    steps:
      - uses: actions/checkout@v4

      - name: Start application
        run: |
          docker-compose -f docker-compose.test.yml up -d
          sleep 60

      - name: Run OWASP ZAP Baseline Scan
        uses: zaproxy/action-baseline@v0.10.0
        with:
          target: 'http://localhost:8069'
          rules_file_name: '.zap/rules.tsv'
          cmd_options: '-a'

      - name: Run OWASP ZAP Full Scan
        uses: zaproxy/action-full-scan@v0.9.0
        with:
          target: 'http://localhost:8069'
          rules_file_name: '.zap/rules.tsv'

      - name: Upload ZAP Report
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: zap-report
          path: report_html.html

  secret-scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 0

      - name: Run Gitleaks
        uses: gitleaks/gitleaks-action@v2
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

**Task 2: Bandit Configuration (4h)**

```ini
# .bandit
[bandit]
exclude: /tests,/venv,/node_modules
skips: B101,B601
targets: odoo/addons

# Severity levels to check
severity: medium,high

# Confidence levels
confidence: medium,high
```

```python
# scripts/security/run_bandit_scan.py
"""Run Bandit static analysis on Python code."""
import subprocess
import json
import sys

def run_bandit_scan():
    """Execute Bandit security scan."""
    result = subprocess.run([
        'bandit',
        '-r', 'odoo/addons',
        '-f', 'json',
        '-ll',  # Only medium and high severity
        '--exclude', 'tests,venv'
    ], capture_output=True, text=True)

    if result.stdout:
        report = json.loads(result.stdout)

        print("=" * 50)
        print("BANDIT SECURITY SCAN RESULTS")
        print("=" * 50)

        high_issues = [r for r in report.get('results', []) if r['issue_severity'] == 'HIGH']
        medium_issues = [r for r in report.get('results', []) if r['issue_severity'] == 'MEDIUM']

        print(f"\nHigh Severity Issues: {len(high_issues)}")
        print(f"Medium Severity Issues: {len(medium_issues)}")

        if high_issues:
            print("\n--- HIGH SEVERITY ISSUES ---")
            for issue in high_issues:
                print(f"\n[{issue['test_id']}] {issue['issue_text']}")
                print(f"  File: {issue['filename']}:{issue['line_number']}")
                print(f"  Code: {issue['code'].strip()}")

        # Fail if high severity issues found
        if high_issues:
            sys.exit(1)

    return result.returncode

if __name__ == '__main__':
    sys.exit(run_bandit_scan())
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Frontend Security Configuration (4h)**

```javascript
// scripts/security/eslint-security-config.js
module.exports = {
  plugins: ['security'],
  extends: ['plugin:security/recommended'],
  rules: {
    'security/detect-object-injection': 'error',
    'security/detect-non-literal-regexp': 'error',
    'security/detect-unsafe-regex': 'error',
    'security/detect-buffer-noassert': 'error',
    'security/detect-child-process': 'error',
    'security/detect-disable-mustache-escape': 'error',
    'security/detect-eval-with-expression': 'error',
    'security/detect-no-csrf-before-method-override': 'error',
    'security/detect-non-literal-fs-filename': 'error',
    'security/detect-non-literal-require': 'error',
    'security/detect-possible-timing-attacks': 'error',
    'security/detect-pseudoRandomBytes': 'error',
  }
};
```

**Task 2: Mobile App Security Scan (4h)**

```yaml
# .github/workflows/mobile-security.yml
name: Mobile Security Scan

on:
  push:
    paths:
      - 'mobile/**'

jobs:
  flutter-security:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Set up Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'

      - name: Install dependencies
        run: |
          cd mobile
          flutter pub get

      - name: Run Flutter analyze
        run: |
          cd mobile
          flutter analyze --fatal-warnings

      - name: Check for hardcoded secrets
        run: |
          cd mobile
          grep -r "password\|secret\|api_key\|token" --include="*.dart" lib/ || true
```

**Deliverables Day 666:**
- OWASP ZAP configuration
- Automated security scan script
- Security CI/CD pipeline
- Bandit configuration
- Frontend security linting
- Mobile security scan setup

---

### Day 667 - OWASP Top 10 Testing (A01-A05)

**Objective:** Test for OWASP Top 10 vulnerabilities A01-A05.

#### Dev 1 - Backend Lead (8h)

**Task 1: A01 Broken Access Control Testing (4h)**

```python
# tests/security/test_access_control.py
"""OWASP A01: Broken Access Control Tests."""
import pytest
import requests

class TestBrokenAccessControl:
    """Test for broken access control vulnerabilities."""

    @pytest.mark.security
    def test_horizontal_privilege_escalation(self, api_client, test_users):
        """Test user cannot access another user's data."""
        # Login as user A
        user_a_session = api_client.login(test_users['customer_a'])

        # Try to access user B's orders
        response = user_a_session.get('/api/v1/orders/user_b_order_id')

        # Should be forbidden
        assert response.status_code in [403, 404]

    @pytest.mark.security
    def test_vertical_privilege_escalation(self, api_client, test_users):
        """Test regular user cannot access admin functions."""
        # Login as regular customer
        customer_session = api_client.login(test_users['customer'])

        # Try to access admin endpoint
        admin_endpoints = [
            '/api/v1/admin/users',
            '/api/v1/admin/settings',
            '/api/v1/admin/reports/sales',
            '/web/database/manager'
        ]

        for endpoint in admin_endpoints:
            response = customer_session.get(endpoint)
            assert response.status_code in [401, 403, 404], \
                f"Admin endpoint {endpoint} accessible to customer"

    @pytest.mark.security
    def test_insecure_direct_object_reference(self, api_client, test_users):
        """Test IDOR vulnerabilities."""
        customer_session = api_client.login(test_users['customer'])

        # Try to access sequential IDs
        for order_id in range(1, 100):
            response = customer_session.get(f'/api/v1/orders/{order_id}')
            if response.status_code == 200:
                # Verify order belongs to logged-in user
                order_data = response.json()
                assert order_data.get('user_id') == test_users['customer']['id'], \
                    f"IDOR vulnerability: Order {order_id} accessible"

    @pytest.mark.security
    def test_path_traversal(self, api_client):
        """Test for path traversal vulnerabilities."""
        malicious_paths = [
            '/api/v1/files/../../../etc/passwd',
            '/api/v1/download?file=../../../etc/passwd',
            '/web/image/../../../etc/passwd',
        ]

        for path in malicious_paths:
            response = api_client.get(path)
            assert 'root:' not in response.text, \
                f"Path traversal vulnerability: {path}"

    @pytest.mark.security
    def test_api_rate_limiting(self, api_client):
        """Test API rate limiting is enforced."""
        # Make rapid requests
        responses = []
        for _ in range(200):
            response = api_client.get('/api/v1/products')
            responses.append(response.status_code)

        # Should eventually get rate limited
        assert 429 in responses or all(r == 200 for r in responses), \
            "Rate limiting not properly configured"
```

**Task 2: A02 Cryptographic Failures Testing (4h)**

```python
# tests/security/test_cryptography.py
"""OWASP A02: Cryptographic Failures Tests."""
import pytest
import ssl
import socket
import requests

class TestCryptographicFailures:
    """Test for cryptographic vulnerabilities."""

    @pytest.mark.security
    def test_https_enforcement(self, base_url):
        """Test HTTPS is enforced."""
        http_url = base_url.replace('https://', 'http://')

        response = requests.get(http_url, allow_redirects=False)

        # Should redirect to HTTPS
        assert response.status_code in [301, 302, 307, 308]
        assert 'https://' in response.headers.get('Location', '')

    @pytest.mark.security
    def test_tls_version(self, hostname):
        """Test minimum TLS version is 1.2."""
        context = ssl.create_default_context()

        # Try to connect with TLS 1.0 (should fail)
        try:
            context.minimum_version = ssl.TLSVersion.TLSv1
            context.maximum_version = ssl.TLSVersion.TLSv1
            with socket.create_connection((hostname, 443)) as sock:
                with context.wrap_socket(sock, server_hostname=hostname):
                    pytest.fail("TLS 1.0 connection should be rejected")
        except ssl.SSLError:
            pass  # Expected - TLS 1.0 should be rejected

    @pytest.mark.security
    def test_secure_cookies(self, api_client, base_url):
        """Test cookies have secure attributes."""
        response = api_client.get(base_url)

        for cookie in response.cookies:
            assert cookie.secure, f"Cookie {cookie.name} missing Secure flag"
            assert cookie.has_nonstandard_attr('HttpOnly'), \
                f"Cookie {cookie.name} missing HttpOnly flag"

    @pytest.mark.security
    def test_password_hashing(self, db_connection):
        """Test passwords are properly hashed."""
        cursor = db_connection.cursor()
        cursor.execute("""
            SELECT password FROM res_users
            WHERE password IS NOT NULL
            LIMIT 10
        """)

        for row in cursor.fetchall():
            password = row[0]
            # Odoo uses PBKDF2 or bcrypt
            assert password.startswith('$pbkdf2') or password.startswith('$2'), \
                "Password not properly hashed"

    @pytest.mark.security
    def test_sensitive_data_exposure_in_logs(self, log_file_path):
        """Test sensitive data not exposed in logs."""
        sensitive_patterns = [
            r'password["\']?\s*[:=]\s*["\'][^"\']+["\']',
            r'api_key["\']?\s*[:=]\s*["\'][^"\']+["\']',
            r'secret["\']?\s*[:=]\s*["\'][^"\']+["\']',
            r'\b\d{16}\b',  # Credit card numbers
        ]

        with open(log_file_path, 'r') as f:
            log_content = f.read()

        import re
        for pattern in sensitive_patterns:
            matches = re.findall(pattern, log_content, re.IGNORECASE)
            assert not matches, f"Sensitive data found in logs: {pattern}"
```

#### Dev 2 - Full-Stack (8h)

**Task 1: A03 Injection Testing (4h)**

```python
# tests/security/test_injection.py
"""OWASP A03: Injection Tests."""
import pytest

class TestSQLInjection:
    """SQL Injection vulnerability tests."""

    SQL_INJECTION_PAYLOADS = [
        "' OR '1'='1",
        "'; DROP TABLE users;--",
        "' UNION SELECT * FROM res_users--",
        "1; SELECT * FROM res_users",
        "' OR 1=1--",
        "admin'--",
        "1' AND '1'='1",
        "' OR ''='",
    ]

    @pytest.mark.security
    @pytest.mark.parametrize("payload", SQL_INJECTION_PAYLOADS)
    def test_login_sql_injection(self, api_client, payload):
        """Test login form for SQL injection."""
        response = api_client.post('/web/login', data={
            'login': payload,
            'password': 'test'
        })

        # Should not return successful login or error details
        assert 'logged in' not in response.text.lower()
        assert 'sql' not in response.text.lower()
        assert 'syntax error' not in response.text.lower()

    @pytest.mark.security
    @pytest.mark.parametrize("payload", SQL_INJECTION_PAYLOADS)
    def test_search_sql_injection(self, api_client, payload):
        """Test search functionality for SQL injection."""
        response = api_client.get(f'/shop?search={payload}')

        assert response.status_code == 200
        assert 'error' not in response.text.lower()
        assert 'syntax' not in response.text.lower()

    @pytest.mark.security
    def test_order_id_injection(self, api_client, auth_session):
        """Test order ID parameter for injection."""
        payloads = [
            "1 OR 1=1",
            "1; DROP TABLE sale_order;--",
            "1 UNION SELECT password FROM res_users--"
        ]

        for payload in payloads:
            response = auth_session.get(f'/api/v1/orders/{payload}')
            assert response.status_code in [400, 404], \
                f"Potential SQL injection vulnerability with payload: {payload}"


class TestNoSQLInjection:
    """NoSQL Injection vulnerability tests."""

    @pytest.mark.security
    def test_mongodb_injection(self, api_client):
        """Test for MongoDB injection (if applicable)."""
        payloads = [
            '{"$gt": ""}',
            '{"$ne": null}',
            '{"$where": "this.password.length > 0"}',
        ]

        for payload in payloads:
            response = api_client.post('/api/v1/search', json={
                'query': payload
            })
            assert response.status_code in [200, 400]
            # Should not return all records
            if response.status_code == 200:
                data = response.json()
                assert len(data.get('results', [])) < 1000


class TestCommandInjection:
    """Command Injection vulnerability tests."""

    COMMAND_INJECTION_PAYLOADS = [
        "; ls -la",
        "| cat /etc/passwd",
        "& whoami",
        "`id`",
        "$(cat /etc/passwd)",
    ]

    @pytest.mark.security
    @pytest.mark.parametrize("payload", COMMAND_INJECTION_PAYLOADS)
    def test_file_upload_command_injection(self, api_client, payload):
        """Test file upload for command injection in filename."""
        files = {
            'file': (f'test{payload}.txt', b'test content', 'text/plain')
        }
        response = api_client.post('/api/v1/upload', files=files)

        # Should sanitize filename or reject
        assert response.status_code in [200, 400]
        if response.status_code == 200:
            assert payload not in response.json().get('filename', '')
```

**Task 2: A04 & A05 Testing (4h)**

```python
# tests/security/test_design_misconfig.py
"""OWASP A04: Insecure Design & A05: Security Misconfiguration Tests."""
import pytest
import requests

class TestInsecureDesign:
    """A04: Insecure Design tests."""

    @pytest.mark.security
    def test_account_enumeration(self, api_client):
        """Test application doesn't reveal valid usernames."""
        # Try login with valid username, wrong password
        response1 = api_client.post('/web/login', data={
            'login': 'admin@smartdairy.com',
            'password': 'wrongpassword'
        })

        # Try login with invalid username
        response2 = api_client.post('/web/login', data={
            'login': 'nonexistent@smartdairy.com',
            'password': 'wrongpassword'
        })

        # Error messages should be identical
        assert response1.text == response2.text, \
            "Different error messages allow account enumeration"

    @pytest.mark.security
    def test_password_reset_enumeration(self, api_client):
        """Test password reset doesn't reveal valid emails."""
        # Try with valid email
        response1 = api_client.post('/web/reset_password', data={
            'login': 'admin@smartdairy.com'
        })

        # Try with invalid email
        response2 = api_client.post('/web/reset_password', data={
            'login': 'nonexistent@smartdairy.com'
        })

        # Should show same message
        # "If this email exists, you will receive a reset link"

    @pytest.mark.security
    def test_brute_force_protection(self, api_client):
        """Test brute force protection on login."""
        for i in range(10):
            response = api_client.post('/web/login', data={
                'login': 'admin@smartdairy.com',
                'password': f'wrongpassword{i}'
            })

        # After multiple failed attempts, should be locked or rate limited
        response = api_client.post('/web/login', data={
            'login': 'admin@smartdairy.com',
            'password': 'correctpassword'
        })

        # Should be locked or require CAPTCHA
        assert response.status_code in [429, 403] or 'captcha' in response.text.lower()


class TestSecurityMisconfiguration:
    """A05: Security Misconfiguration tests."""

    @pytest.mark.security
    def test_debug_mode_disabled(self, api_client, base_url):
        """Test debug mode is disabled in production."""
        response = api_client.get(base_url)

        # Should not expose debug information
        assert 'Traceback' not in response.text
        assert 'DEBUG' not in response.headers.get('X-Debug', '')

    @pytest.mark.security
    def test_default_credentials(self, api_client):
        """Test default credentials are changed."""
        default_creds = [
            ('admin', 'admin'),
            ('admin', 'password'),
            ('root', 'root'),
            ('admin@smartdairy.com', 'admin'),
        ]

        for username, password in default_creds:
            response = api_client.post('/web/login', data={
                'login': username,
                'password': password
            })
            assert 'o_main_navbar' not in response.text, \
                f"Default credentials work: {username}/{password}"

    @pytest.mark.security
    def test_directory_listing_disabled(self, api_client, base_url):
        """Test directory listing is disabled."""
        directories = [
            '/static/',
            '/web/static/',
            '/web/content/',
        ]

        for directory in directories:
            response = api_client.get(f'{base_url}{directory}')
            assert 'Index of' not in response.text
            assert 'Directory listing' not in response.text

    @pytest.mark.security
    def test_security_headers(self, api_client, base_url):
        """Test security headers are present."""
        response = api_client.get(base_url)
        headers = response.headers

        required_headers = {
            'X-Content-Type-Options': 'nosniff',
            'X-Frame-Options': ['DENY', 'SAMEORIGIN'],
            'X-XSS-Protection': '1; mode=block',
            'Strict-Transport-Security': None,  # Just check presence
            'Content-Security-Policy': None,
        }

        for header, expected_value in required_headers.items():
            assert header in headers, f"Missing security header: {header}"
            if expected_value:
                if isinstance(expected_value, list):
                    assert headers[header] in expected_value
                else:
                    assert expected_value in headers[header]

    @pytest.mark.security
    def test_error_handling(self, api_client):
        """Test error pages don't expose sensitive information."""
        # Trigger various errors
        error_urls = [
            '/nonexistent-page',
            '/api/v1/error-test',
            '/web/database/manager',  # Should be blocked
        ]

        for url in error_urls:
            response = api_client.get(url)
            # Should not expose stack traces or internal paths
            assert 'Traceback' not in response.text
            assert '/opt/odoo' not in response.text
            assert 'File "/' not in response.text
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: XSS Testing (4h)**

```python
# tests/security/test_xss.py
"""OWASP A07: Cross-Site Scripting (XSS) Tests."""
import pytest

class TestXSSVulnerabilities:
    """XSS vulnerability tests."""

    XSS_PAYLOADS = [
        '<script>alert("XSS")</script>',
        '<img src=x onerror=alert("XSS")>',
        '<svg onload=alert("XSS")>',
        '"><script>alert("XSS")</script>',
        "'-alert('XSS')-'",
        '<body onload=alert("XSS")>',
        '<iframe src="javascript:alert(\'XSS\')">',
        '{{constructor.constructor("alert(1)")()}}',  # Angular template injection
        '${alert("XSS")}',  # Template literal injection
    ]

    @pytest.mark.security
    @pytest.mark.parametrize("payload", XSS_PAYLOADS)
    def test_reflected_xss_search(self, api_client, payload):
        """Test search for reflected XSS."""
        response = api_client.get(f'/shop?search={payload}')

        # Payload should be escaped in response
        assert payload not in response.text, \
            f"Reflected XSS vulnerability with payload: {payload}"

    @pytest.mark.security
    @pytest.mark.parametrize("payload", XSS_PAYLOADS)
    def test_stored_xss_comment(self, auth_session, payload):
        """Test comment field for stored XSS."""
        # Post comment with XSS payload
        response = auth_session.post('/api/v1/products/1/review', json={
            'rating': 5,
            'comment': payload
        })

        # Get product page
        product_response = auth_session.get('/shop/product/1')

        # Payload should be escaped
        assert payload not in product_response.text

    @pytest.mark.security
    def test_dom_xss(self, selenium_driver, base_url):
        """Test for DOM-based XSS using Selenium."""
        # Test URL hash-based XSS
        selenium_driver.get(f'{base_url}/shop#<script>alert("XSS")</script>')

        # Check no alert was triggered
        try:
            alert = selenium_driver.switch_to.alert
            pytest.fail("DOM XSS vulnerability - alert triggered")
        except:
            pass  # No alert - good

    @pytest.mark.security
    def test_xss_in_error_messages(self, api_client):
        """Test error messages for XSS."""
        payload = '<script>alert("XSS")</script>'
        response = api_client.post('/web/login', data={
            'login': payload,
            'password': 'test'
        })

        assert payload not in response.text


class TestCSRF:
    """CSRF vulnerability tests."""

    @pytest.mark.security
    def test_csrf_token_present(self, auth_session, base_url):
        """Test CSRF tokens are present on forms."""
        response = auth_session.get(f'{base_url}/my/account')

        assert 'csrf_token' in response.text or 'csrfmiddlewaretoken' in response.text

    @pytest.mark.security
    def test_csrf_token_validation(self, auth_session):
        """Test CSRF token is validated."""
        # Try to submit form without CSRF token
        response = auth_session.post('/my/account', data={
            'name': 'Test',
            'email': 'test@test.com'
        })

        assert response.status_code in [400, 403]

    @pytest.mark.security
    def test_csrf_token_not_reusable(self, auth_session):
        """Test CSRF tokens are not reusable."""
        # Get form with token
        form_response = auth_session.get('/my/account')
        # Extract CSRF token
        import re
        token_match = re.search(r'csrf_token["\s]*value="([^"]+)"', form_response.text)
        if token_match:
            old_token = token_match.group(1)

            # Submit form
            auth_session.post('/my/account', data={
                'csrf_token': old_token,
                'name': 'Test'
            })

            # Try to reuse token
            response = auth_session.post('/my/account', data={
                'csrf_token': old_token,
                'name': 'Test2'
            })

            # Should fail with reused token
            # (depends on implementation)

    @pytest.mark.security
    def test_state_changing_get_requests(self, auth_session):
        """Test state-changing operations require POST."""
        # These operations should not work with GET
        state_changing_urls = [
            '/api/v1/cart/clear',
            '/api/v1/orders/1/cancel',
            '/api/v1/account/delete',
        ]

        for url in state_changing_urls:
            response = auth_session.get(url)
            assert response.status_code in [405, 404], \
                f"State-changing operation allowed via GET: {url}"
```

**Task 2: Frontend Security Tests (4h)**

```javascript
// tests/security/frontend-security.test.js
/**
 * Frontend security tests
 */
describe('Frontend Security Tests', () => {
  describe('Content Security Policy', () => {
    it('should have CSP header', async () => {
      const response = await fetch('/');
      const csp = response.headers.get('Content-Security-Policy');

      expect(csp).toBeTruthy();
      expect(csp).toContain("default-src");
    });

    it('should block inline scripts', async () => {
      // CSP should block inline scripts
      const response = await fetch('/');
      const csp = response.headers.get('Content-Security-Policy');

      expect(csp).not.toContain("'unsafe-inline'");
    });
  });

  describe('Input Sanitization', () => {
    it('should sanitize HTML in user inputs', () => {
      const sanitizer = new DOMPurify();
      const maliciousInput = '<script>alert("xss")</script>';
      const sanitized = sanitizer.sanitize(maliciousInput);

      expect(sanitized).not.toContain('<script>');
    });

    it('should encode special characters in URLs', () => {
      const input = '<script>alert("xss")</script>';
      const encoded = encodeURIComponent(input);

      expect(encoded).not.toContain('<');
      expect(encoded).not.toContain('>');
    });
  });

  describe('Secure Storage', () => {
    it('should not store sensitive data in localStorage', () => {
      const sensitiveKeys = ['password', 'token', 'secret', 'credit_card'];

      for (const key of Object.keys(localStorage)) {
        for (const sensitiveKey of sensitiveKeys) {
          expect(key.toLowerCase()).not.toContain(sensitiveKey);
        }
      }
    });

    it('should use HttpOnly cookies for session', () => {
      // Session cookies should not be accessible via JavaScript
      const cookies = document.cookie;
      expect(cookies).not.toContain('session_id');
    });
  });
});
```

**Deliverables Day 667:**
- A01 Broken Access Control test results
- A02 Cryptographic Failures test results
- A03 Injection test results
- A04 Insecure Design test results
- A05 Security Misconfiguration test results
- XSS/CSRF test results

---

### Day 668 - OWASP Top 10 Testing (A06-A10)

*(Continued testing for remaining OWASP categories)*

#### All Developers (8h each)

- A06: Vulnerable and Outdated Components testing
- A07: Identification and Authentication Failures testing
- A08: Software and Data Integrity Failures testing
- A09: Security Logging and Monitoring Failures testing
- A10: Server-Side Request Forgery (SSRF) testing
- Authentication flow security validation
- Session management testing

---

### Day 669 - Penetration Testing

**Objective:** Execute comprehensive penetration testing.

#### All Developers (8h each)

- Network penetration testing
- Application penetration testing
- API penetration testing
- Mobile app security testing
- Social engineering awareness

---

### Day 670 - Security Audit Report & Milestone Review

**Objective:** Generate security audit report and conduct milestone review.

#### Dev 1 - Backend Lead (8h)

**Task: Security Audit Report Generation (8h)**

```markdown
# Smart Dairy Security Audit Report

## Executive Summary

### Audit Information
| Field | Value |
|-------|-------|
| Audit Date | 2024-03-15 to 2024-03-19 |
| Application | Smart Dairy Digital Portal + ERP |
| Version | 1.0.0 |
| Environment | Staging |

### Overall Security Rating: [PASS/CONDITIONAL PASS/FAIL]

### Summary of Findings

| Severity | Count | Status |
|----------|-------|--------|
| Critical | 0 | Resolved |
| High | 2 | Resolved |
| Medium | 5 | 3 Resolved, 2 Accepted Risk |
| Low | 12 | Documented |
| Informational | 8 | Documented |

## Detailed Findings

### Finding 1: [Title]
| Field | Value |
|-------|-------|
| Severity | High |
| Category | OWASP A01 - Broken Access Control |
| Status | Resolved |
| CVSS Score | 7.5 |

**Description:**
[Detailed description of the vulnerability]

**Affected Components:**
- Component 1
- Component 2

**Steps to Reproduce:**
1. Step 1
2. Step 2
3. Step 3

**Evidence:**
[Screenshots or logs]

**Remediation:**
[How it was fixed]

**Verification:**
[How fix was verified]

---

## OWASP Top 10 Compliance Matrix

| Category | Status | Notes |
|----------|--------|-------|
| A01: Broken Access Control | PASS | RBAC implemented correctly |
| A02: Cryptographic Failures | PASS | TLS 1.3, AES-256 encryption |
| A03: Injection | PASS | ORM prevents SQL injection |
| A04: Insecure Design | PASS | Secure design patterns used |
| A05: Security Misconfiguration | PASS | Hardened configuration |
| A06: Vulnerable Components | PASS | All dependencies updated |
| A07: Authentication Failures | PASS | MFA available, strong passwords |
| A08: Integrity Failures | PASS | Code signing, dependency checks |
| A09: Logging Failures | PASS | Comprehensive audit logging |
| A10: SSRF | PASS | URL validation implemented |

## Recommendations

### Immediate Actions
1. [Action 1]
2. [Action 2]

### Short-term Improvements
1. [Improvement 1]
2. [Improvement 2]

### Long-term Enhancements
1. [Enhancement 1]
2. [Enhancement 2]

## Sign-off

**Security Auditor:**
Name: ___________________
Date: ___________________
Signature: ___________________

**Technical Lead:**
Name: ___________________
Date: ___________________
Signature: ___________________
```

**Deliverables Day 670:**
- Security audit report (PDF)
- Remediation tracking sheet
- OWASP compliance matrix
- Penetration test report
- Milestone sign-off

---

## 4. Technical Specifications

### 4.1 Security Testing Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Security Testing Stack                        │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐ │
│  │ OWASP ZAP   │  │ Burp Suite  │  │ Bandit (SAST)           │ │
│  │ (DAST)      │  │ (Manual)    │  │ ESLint Security         │ │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘ │
│                          ↓                                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐ │
│  │ Snyk        │  │ Gitleaks    │  │ Custom Security Tests   │ │
│  │ (SCA)       │  │ (Secrets)   │  │ (pytest)                │ │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Testing & Validation

| Test ID | Test Case | Expected Result | Status |
|---------|-----------|-----------------|--------|
| M134-01 | OWASP ZAP scan complete | No critical findings | Pending |
| M134-02 | SQL injection tests | All tests pass | Pending |
| M134-03 | XSS tests | All tests pass | Pending |
| M134-04 | Authentication tests | All tests pass | Pending |
| M134-05 | Penetration test | Report generated | Pending |
| M134-06 | Security audit | Report approved | Pending |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Critical vulnerability found | Low | High | Immediate remediation sprint |
| False positives in scan | Medium | Low | Manual verification |
| Time constraints | Medium | Medium | Prioritize critical tests |
| Tool licensing issues | Low | Medium | Use open-source alternatives |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies
- Milestone 133 complete
- Security tools configured
- Staging environment stable

### 7.2 Handoffs to Milestone 135
- Security audit report
- Remediation tracking sheet
- All critical/high issues resolved
- Security sign-off obtained

---

## Milestone Sign-off Checklist

- [ ] Zero critical vulnerabilities
- [ ] Zero high severity vulnerabilities (or waiver)
- [ ] All OWASP Top 10 categories tested
- [ ] Penetration test completed
- [ ] Authentication security validated
- [ ] API security validated
- [ ] Security audit report approved
- [ ] Remediation tracking in place
- [ ] Team sign-off obtained

---

**Document End**
