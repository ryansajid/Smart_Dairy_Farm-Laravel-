# Phase 15: Milestone 143 - Security Hardening

## Document Control

| Attribute | Value |
|-----------|-------|
| **Milestone ID** | MS-143 |
| **Phase** | 15 - Deployment & Handover |
| **Sprint Duration** | Days 711-715 (5 working days) |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-05 |
| **Authors** | Dev 2 (Lead), Dev 1, Dev 3 |
| **Status** | Draft |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Implementation](#3-day-by-day-implementation)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement comprehensive security hardening measures including WAF deployment, secrets management, API security enhancements, and penetration testing to ensure production environment meets OWASP Top 10 compliance and organizational security standards.

### 1.2 Objectives

| # | Objective | Owner | Priority |
|---|-----------|-------|----------|
| 1 | Deploy and configure AWS WAF with OWASP Core Rule Set | Dev 2 | Critical |
| 2 | Implement HashiCorp Vault for secrets management | Dev 2 | Critical |
| 3 | Configure KMS encryption for data at rest | Dev 1 | Critical |
| 4 | Harden API security (OAuth 2.0, JWT, rate limiting) | Dev 1 | High |
| 5 | Execute penetration testing and remediate findings | Dev 2 | Critical |
| 6 | Implement security headers and CSP policies | Dev 3 | High |
| 7 | Configure audit logging and SIEM integration | Dev 1 | High |
| 8 | Complete security compliance verification | All | Critical |

### 1.3 Key Deliverables

| Deliverable | Description | Owner | Day |
|-------------|-------------|-------|-----|
| WAF Configuration | AWS WAF with OWASP managed rules | Dev 2 | 711 |
| Vault Deployment | HashiCorp Vault cluster with policies | Dev 2 | 712 |
| KMS Configuration | AWS KMS keys for encryption | Dev 1 | 712 |
| API Security | OAuth 2.0 + JWT hardening | Dev 1 | 713 |
| Rate Limiting | API rate limiting configuration | Dev 1 | 713 |
| Security Headers | CSP, HSTS, X-Frame-Options | Dev 3 | 713 |
| Penetration Test Report | OWASP Top 10 assessment | Dev 2 | 714 |
| Remediation Evidence | Fixed vulnerabilities with proof | All | 714 |
| Security Audit Report | Compliance verification document | Dev 2 | 715 |
| Sign-off Document | Security acceptance certificate | All | 715 |

### 1.4 Prerequisites

- [x] Production environment deployed (MS-141)
- [x] Data migration completed (MS-142)
- [x] Network infrastructure operational
- [x] SSL/TLS certificates provisioned
- [x] IAM roles and policies defined

### 1.5 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| WAF blocking rate | >99% malicious requests | WAF metrics |
| SSL Labs grade | A+ | SSL Labs scan |
| OWASP compliance | 100% Top 10 addressed | Pen test report |
| Secrets in Vault | 100% | Audit trail |
| Security headers | All implemented | Security scanner |
| Penetration test | No critical/high findings | Final report |

---

## 2. Requirement Traceability Matrix

| Req ID | Requirement | BRD/SRS Ref | Implementation | Verification |
|--------|-------------|-------------|----------------|--------------|
| SEC-001 | TLS 1.3 encryption | NFR-SEC-01 | ALB + CloudFront TLS config | SSL Labs A+ |
| SEC-002 | OWASP Top 10 compliance | NFR-SEC-02 | WAF rules + secure coding | Pen test |
| SEC-003 | Data encryption at rest | NFR-SEC-03 | KMS + RDS encryption | Security audit |
| SEC-004 | Secrets management | NFR-SEC-04 | HashiCorp Vault | Vault audit |
| SEC-005 | API authentication | FR-AUTH-01 | OAuth 2.0 + JWT | API testing |
| SEC-006 | Rate limiting | NFR-SEC-05 | API Gateway throttling | Load test |
| SEC-007 | Audit logging | NFR-SEC-06 | CloudTrail + ELK | Log review |
| SEC-008 | Session management | NFR-SEC-07 | Redis sessions + timeout | Security scan |

---

## 3. Day-by-Day Implementation

### Day 711: WAF Deployment & Configuration

#### Objectives
- Deploy AWS WAF with OWASP Core Rule Set
- Configure custom rules for Smart Dairy application
- Implement geo-blocking and IP reputation filtering

#### Dev 1 Tasks (8 hours)

**Task 1: WAF Infrastructure with Terraform (4h)**

```hcl
# terraform/modules/security/waf/main.tf

resource "aws_wafv2_web_acl" "smart_dairy_waf" {
  name        = "smart-dairy-production-waf"
  description = "WAF for Smart Dairy Production Environment"
  scope       = "REGIONAL"

  default_action {
    allow {}
  }

  # AWS Managed Rules - Core Rule Set
  rule {
    name     = "AWSManagedRulesCommonRuleSet"
    priority = 1

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesCommonRuleSet"
        vendor_name = "AWS"

        rule_action_override {
          action_to_use {
            count {}
          }
          name = "SizeRestrictions_BODY"
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "AWSManagedRulesCommonRuleSetMetric"
      sampled_requests_enabled  = true
    }
  }

  # SQL Injection Protection
  rule {
    name     = "AWSManagedRulesSQLiRuleSet"
    priority = 2

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesSQLiRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "AWSManagedRulesSQLiRuleSetMetric"
      sampled_requests_enabled  = true
    }
  }

  # Known Bad Inputs
  rule {
    name     = "AWSManagedRulesKnownBadInputsRuleSet"
    priority = 3

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesKnownBadInputsRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "AWSManagedRulesKnownBadInputsRuleSetMetric"
      sampled_requests_enabled  = true
    }
  }

  # Linux OS Protection
  rule {
    name     = "AWSManagedRulesLinuxRuleSet"
    priority = 4

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesLinuxRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "AWSManagedRulesLinuxRuleSetMetric"
      sampled_requests_enabled  = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name               = "SmartDairyWAFMetric"
    sampled_requests_enabled  = true
  }

  tags = {
    Environment = "production"
    Project     = "smart-dairy"
    ManagedBy   = "terraform"
  }
}

# Rate Limiting Rule
resource "aws_wafv2_web_acl" "rate_limit_rule" {
  name        = "smart-dairy-rate-limit"
  description = "Rate limiting for API endpoints"
  scope       = "REGIONAL"

  default_action {
    allow {}
  }

  rule {
    name     = "RateLimitRule"
    priority = 1

    action {
      block {}
    }

    statement {
      rate_based_statement {
        limit              = 2000
        aggregate_key_type = "IP"

        scope_down_statement {
          byte_match_statement {
            positional_constraint = "STARTS_WITH"
            search_string         = "/api/"

            field_to_match {
              uri_path {}
            }

            text_transformation {
              priority = 0
              type     = "LOWERCASE"
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "RateLimitRuleMetric"
      sampled_requests_enabled  = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name               = "SmartDairyRateLimitMetric"
    sampled_requests_enabled  = true
  }

  tags = {
    Environment = "production"
    Project     = "smart-dairy"
  }
}

# WAF Association with ALB
resource "aws_wafv2_web_acl_association" "alb_association" {
  resource_arn = var.alb_arn
  web_acl_arn  = aws_wafv2_web_acl.smart_dairy_waf.arn
}

# WAF Logging Configuration
resource "aws_wafv2_web_acl_logging_configuration" "waf_logging" {
  log_destination_configs = [aws_kinesis_firehose_delivery_stream.waf_logs.arn]
  resource_arn           = aws_wafv2_web_acl.smart_dairy_waf.arn

  logging_filter {
    default_behavior = "KEEP"

    filter {
      behavior = "KEEP"

      condition {
        action_condition {
          action = "BLOCK"
        }
      }

      requirement = "MEETS_ANY"
    }
  }
}

# Kinesis Firehose for WAF Logs
resource "aws_kinesis_firehose_delivery_stream" "waf_logs" {
  name        = "aws-waf-logs-smart-dairy"
  destination = "extended_s3"

  extended_s3_configuration {
    role_arn   = aws_iam_role.firehose_role.arn
    bucket_arn = aws_s3_bucket.waf_logs.arn
    prefix     = "waf-logs/year=!{timestamp:yyyy}/month=!{timestamp:MM}/day=!{timestamp:dd}/"

    buffering_size     = 5
    buffering_interval = 300
    compression_format = "GZIP"
  }
}
```

**Task 2: Custom WAF Rules for Odoo (4h)**

```hcl
# terraform/modules/security/waf/custom_rules.tf

# Block suspicious Odoo-specific attack patterns
resource "aws_wafv2_rule_group" "odoo_protection" {
  name        = "smart-dairy-odoo-protection"
  description = "Custom rules for Odoo application protection"
  scope       = "REGIONAL"
  capacity    = 100

  # Block direct model access attempts
  rule {
    name     = "BlockDirectModelAccess"
    priority = 1

    action {
      block {}
    }

    statement {
      regex_pattern_set_reference_statement {
        arn = aws_wafv2_regex_pattern_set.odoo_model_patterns.arn

        field_to_match {
          uri_path {}
        }

        text_transformation {
          priority = 0
          type     = "URL_DECODE"
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "BlockDirectModelAccessMetric"
      sampled_requests_enabled  = true
    }
  }

  # Block XML-RPC abuse
  rule {
    name     = "RateLimitXMLRPC"
    priority = 2

    action {
      block {}
    }

    statement {
      rate_based_statement {
        limit              = 100
        aggregate_key_type = "IP"

        scope_down_statement {
          byte_match_statement {
            positional_constraint = "STARTS_WITH"
            search_string         = "/xmlrpc"

            field_to_match {
              uri_path {}
            }

            text_transformation {
              priority = 0
              type     = "LOWERCASE"
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "RateLimitXMLRPCMetric"
      sampled_requests_enabled  = true
    }
  }

  # Block admin panel brute force
  rule {
    name     = "RateLimitAdminLogin"
    priority = 3

    action {
      block {}
    }

    statement {
      rate_based_statement {
        limit              = 10
        aggregate_key_type = "IP"

        scope_down_statement {
          byte_match_statement {
            positional_constraint = "EXACTLY"
            search_string         = "/web/login"

            field_to_match {
              uri_path {}
            }

            text_transformation {
              priority = 0
              type     = "LOWERCASE"
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "RateLimitAdminLoginMetric"
      sampled_requests_enabled  = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name               = "OdooProtectionRuleGroupMetric"
    sampled_requests_enabled  = true
  }

  tags = {
    Environment = "production"
    Project     = "smart-dairy"
  }
}

# Regex patterns for Odoo model injection
resource "aws_wafv2_regex_pattern_set" "odoo_model_patterns" {
  name        = "odoo-model-injection-patterns"
  description = "Patterns to detect Odoo model injection attempts"
  scope       = "REGIONAL"

  regular_expression {
    regex_string = ".*model=ir\\..*"
  }

  regular_expression {
    regex_string = ".*model=base\\..*"
  }

  regular_expression {
    regex_string = ".*\\?.*model=.*&.*method=.*"
  }

  tags = {
    Environment = "production"
    Project     = "smart-dairy"
  }
}

# Geo-blocking rule (optional - block specific countries)
resource "aws_wafv2_rule_group" "geo_blocking" {
  name        = "smart-dairy-geo-blocking"
  description = "Geographic restrictions"
  scope       = "REGIONAL"
  capacity    = 10

  rule {
    name     = "BlockHighRiskCountries"
    priority = 1

    action {
      block {}
    }

    statement {
      geo_match_statement {
        country_codes = var.blocked_countries
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name               = "GeoBlockingMetric"
      sampled_requests_enabled  = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name               = "GeoBlockingRuleGroupMetric"
    sampled_requests_enabled  = true
  }

  tags = {
    Environment = "production"
    Project     = "smart-dairy"
  }
}
```

#### Dev 2 Tasks (8 hours)

**Task 1: WAF Testing and Validation (4h)**

```python
# scripts/security/waf_test.py
"""
WAF Testing Script for Smart Dairy Production Environment
Tests various attack vectors to verify WAF rules are working
"""

import requests
import json
import time
from typing import Dict, List, Tuple
from dataclasses import dataclass
from concurrent.futures import ThreadPoolExecutor

@dataclass
class WAFTestResult:
    test_name: str
    attack_type: str
    expected_blocked: bool
    actual_blocked: bool
    response_code: int
    response_time: float

class WAFTester:
    def __init__(self, base_url: str, api_key: str = None):
        self.base_url = base_url.rstrip('/')
        self.api_key = api_key
        self.results: List[WAFTestResult] = []

    def _make_request(
        self,
        method: str,
        path: str,
        headers: Dict = None,
        data: str = None,
        params: Dict = None
    ) -> Tuple[int, float]:
        """Make HTTP request and return status code and response time"""
        url = f"{self.base_url}{path}"
        headers = headers or {}

        if self.api_key:
            headers['X-API-Key'] = self.api_key

        start_time = time.time()
        try:
            response = requests.request(
                method=method,
                url=url,
                headers=headers,
                data=data,
                params=params,
                timeout=10,
                allow_redirects=False
            )
            response_time = time.time() - start_time
            return response.status_code, response_time
        except requests.exceptions.RequestException as e:
            return 0, time.time() - start_time

    def test_sql_injection(self) -> WAFTestResult:
        """Test SQL injection protection"""
        payloads = [
            "' OR '1'='1",
            "1; DROP TABLE users--",
            "' UNION SELECT * FROM users--",
            "1' AND SLEEP(5)--"
        ]

        for payload in payloads:
            status, response_time = self._make_request(
                'GET',
                '/api/v1/products',
                params={'search': payload}
            )

            result = WAFTestResult(
                test_name=f"SQL Injection: {payload[:20]}...",
                attack_type="SQLi",
                expected_blocked=True,
                actual_blocked=status == 403,
                response_code=status,
                response_time=response_time
            )
            self.results.append(result)

        return self.results[-1]

    def test_xss_attacks(self) -> WAFTestResult:
        """Test XSS protection"""
        payloads = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "javascript:alert('XSS')",
            "<svg onload=alert('XSS')>"
        ]

        for payload in payloads:
            status, response_time = self._make_request(
                'POST',
                '/api/v1/feedback',
                headers={'Content-Type': 'application/json'},
                data=json.dumps({'comment': payload})
            )

            result = WAFTestResult(
                test_name=f"XSS: {payload[:20]}...",
                attack_type="XSS",
                expected_blocked=True,
                actual_blocked=status == 403,
                response_code=status,
                response_time=response_time
            )
            self.results.append(result)

        return self.results[-1]

    def test_path_traversal(self) -> WAFTestResult:
        """Test path traversal protection"""
        payloads = [
            "../../etc/passwd",
            "..\\..\\windows\\system32\\config\\sam",
            "%2e%2e%2f%2e%2e%2fetc%2fpasswd",
            "....//....//etc/passwd"
        ]

        for payload in payloads:
            status, response_time = self._make_request(
                'GET',
                f'/api/v1/files/{payload}'
            )

            result = WAFTestResult(
                test_name=f"Path Traversal: {payload[:20]}...",
                attack_type="LFI",
                expected_blocked=True,
                actual_blocked=status == 403,
                response_code=status,
                response_time=response_time
            )
            self.results.append(result)

        return self.results[-1]

    def test_rate_limiting(self) -> WAFTestResult:
        """Test rate limiting protection"""
        blocked_count = 0
        total_requests = 100

        def make_rapid_request(_):
            status, _ = self._make_request('GET', '/api/v1/health')
            return status == 429

        with ThreadPoolExecutor(max_workers=20) as executor:
            results = list(executor.map(make_rapid_request, range(total_requests)))
            blocked_count = sum(results)

        result = WAFTestResult(
            test_name="Rate Limiting Test",
            attack_type="DDoS",
            expected_blocked=True,
            actual_blocked=blocked_count > 0,
            response_code=429 if blocked_count > 0 else 200,
            response_time=0
        )
        self.results.append(result)
        return result

    def test_command_injection(self) -> WAFTestResult:
        """Test command injection protection"""
        payloads = [
            "; cat /etc/passwd",
            "| ls -la",
            "$(whoami)",
            "`id`"
        ]

        for payload in payloads:
            status, response_time = self._make_request(
                'POST',
                '/api/v1/reports/generate',
                headers={'Content-Type': 'application/json'},
                data=json.dumps({'filename': payload})
            )

            result = WAFTestResult(
                test_name=f"Command Injection: {payload[:15]}...",
                attack_type="RCE",
                expected_blocked=True,
                actual_blocked=status == 403,
                response_code=status,
                response_time=response_time
            )
            self.results.append(result)

        return self.results[-1]

    def generate_report(self) -> Dict:
        """Generate test report"""
        passed = sum(1 for r in self.results if r.expected_blocked == r.actual_blocked)
        failed = len(self.results) - passed

        report = {
            "summary": {
                "total_tests": len(self.results),
                "passed": passed,
                "failed": failed,
                "pass_rate": f"{(passed/len(self.results))*100:.1f}%"
            },
            "results": [
                {
                    "test": r.test_name,
                    "type": r.attack_type,
                    "status": "PASS" if r.expected_blocked == r.actual_blocked else "FAIL",
                    "response_code": r.response_code,
                    "response_time_ms": f"{r.response_time*1000:.2f}"
                }
                for r in self.results
            ],
            "failed_tests": [
                r.test_name for r in self.results
                if r.expected_blocked != r.actual_blocked
            ]
        }

        return report

    def run_all_tests(self) -> Dict:
        """Run complete WAF test suite"""
        print("Starting WAF Test Suite...")

        print("  Testing SQL Injection protection...")
        self.test_sql_injection()

        print("  Testing XSS protection...")
        self.test_xss_attacks()

        print("  Testing Path Traversal protection...")
        self.test_path_traversal()

        print("  Testing Rate Limiting...")
        self.test_rate_limiting()

        print("  Testing Command Injection protection...")
        self.test_command_injection()

        print("\nGenerating report...")
        return self.generate_report()


if __name__ == "__main__":
    import argparse

    parser = argparse.ArgumentParser(description="WAF Testing Tool")
    parser.add_argument("--url", required=True, help="Target URL")
    parser.add_argument("--api-key", help="API key for authentication")
    parser.add_argument("--output", default="waf_test_report.json", help="Output file")

    args = parser.parse_args()

    tester = WAFTester(args.url, args.api_key)
    report = tester.run_all_tests()

    with open(args.output, 'w') as f:
        json.dump(report, f, indent=2)

    print(f"\nReport saved to {args.output}")
    print(f"Pass Rate: {report['summary']['pass_rate']}")
```

**Task 2: CloudFront Security Configuration (4h)**

```hcl
# terraform/modules/security/cloudfront/security.tf

resource "aws_cloudfront_response_headers_policy" "security_headers" {
  name    = "smart-dairy-security-headers"
  comment = "Security headers for Smart Dairy"

  security_headers_config {
    content_security_policy {
      content_security_policy = join("; ", [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "font-src 'self' https://fonts.gstatic.com",
        "img-src 'self' data: https:",
        "connect-src 'self' https://api.smartdairy.com wss://ws.smartdairy.com",
        "frame-ancestors 'none'",
        "base-uri 'self'",
        "form-action 'self'"
      ])
      override = true
    }

    content_type_options {
      override = true
    }

    frame_options {
      frame_option = "DENY"
      override     = true
    }

    referrer_policy {
      referrer_policy = "strict-origin-when-cross-origin"
      override        = true
    }

    strict_transport_security {
      access_control_max_age_sec = 31536000
      include_subdomains         = true
      preload                    = true
      override                   = true
    }

    xss_protection {
      mode_block = true
      protection = true
      override   = true
    }
  }

  custom_headers_config {
    items {
      header   = "Permissions-Policy"
      value    = "geolocation=(self), microphone=(), camera=()"
      override = true
    }

    items {
      header   = "X-Permitted-Cross-Domain-Policies"
      value    = "none"
      override = true
    }
  }
}

# Origin Access Control for S3
resource "aws_cloudfront_origin_access_control" "s3_oac" {
  name                              = "smart-dairy-s3-oac"
  description                       = "OAC for Smart Dairy S3 buckets"
  origin_access_control_origin_type = "s3"
  signing_behavior                  = "always"
  signing_protocol                  = "sigv4"
}
```

#### Dev 3 Tasks (8 hours)

**Task 1: Security Documentation (4h)**

Create WAF configuration documentation and runbooks for security operations.

**Task 2: Security Headers Validation Script (4h)**

```python
# scripts/security/validate_security_headers.py
"""
Security Headers Validation Script
Validates that all required security headers are present
"""

import requests
import json
from dataclasses import dataclass
from typing import Dict, List, Optional

@dataclass
class HeaderCheck:
    name: str
    expected_value: Optional[str]
    present: bool
    actual_value: Optional[str]
    passed: bool

class SecurityHeadersValidator:
    REQUIRED_HEADERS = {
        'Strict-Transport-Security': 'max-age=31536000; includeSubDomains; preload',
        'X-Content-Type-Options': 'nosniff',
        'X-Frame-Options': 'DENY',
        'X-XSS-Protection': '1; mode=block',
        'Referrer-Policy': 'strict-origin-when-cross-origin',
        'Content-Security-Policy': None,  # Just check presence
        'Permissions-Policy': None,
    }

    def __init__(self, url: str):
        self.url = url
        self.results: List[HeaderCheck] = []

    def validate(self) -> Dict:
        """Validate all security headers"""
        try:
            response = requests.get(self.url, timeout=10)
            headers = response.headers
        except requests.RequestException as e:
            return {"error": str(e), "passed": False}

        for header_name, expected_value in self.REQUIRED_HEADERS.items():
            actual_value = headers.get(header_name)
            present = actual_value is not None

            if expected_value is None:
                passed = present
            else:
                passed = present and expected_value in actual_value

            self.results.append(HeaderCheck(
                name=header_name,
                expected_value=expected_value,
                present=present,
                actual_value=actual_value,
                passed=passed
            ))

        passed_count = sum(1 for r in self.results if r.passed)

        return {
            "url": self.url,
            "total_headers": len(self.results),
            "passed": passed_count,
            "failed": len(self.results) - passed_count,
            "pass_rate": f"{(passed_count/len(self.results))*100:.1f}%",
            "results": [
                {
                    "header": r.name,
                    "status": "PASS" if r.passed else "FAIL",
                    "expected": r.expected_value or "Present",
                    "actual": r.actual_value or "Missing"
                }
                for r in self.results
            ]
        }


if __name__ == "__main__":
    import argparse

    parser = argparse.ArgumentParser()
    parser.add_argument("--url", required=True)
    parser.add_argument("--output", default="headers_report.json")
    args = parser.parse_args()

    validator = SecurityHeadersValidator(args.url)
    report = validator.validate()

    with open(args.output, 'w') as f:
        json.dump(report, f, indent=2)

    print(json.dumps(report, indent=2))
```

#### Day 711 Deliverables

- [x] AWS WAF deployed with OWASP Core Rule Set
- [x] Custom WAF rules for Odoo protection
- [x] Rate limiting configured for API endpoints
- [x] WAF logging to S3 via Kinesis Firehose
- [x] CloudFront security headers configured
- [x] WAF testing script created
- [x] Security headers validation script

---

### Day 712: Secrets Management & Encryption

#### Objectives
- Deploy HashiCorp Vault for centralized secrets management
- Configure AWS KMS for encryption at rest
- Migrate all secrets from environment variables to Vault

#### Dev 1 Tasks (8 hours)

**Task 1: AWS KMS Configuration (4h)**

```hcl
# terraform/modules/security/kms/main.tf

# Master encryption key for RDS
resource "aws_kms_key" "rds_encryption" {
  description             = "KMS key for RDS encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid    = "Enable IAM User Permissions"
        Effect = "Allow"
        Principal = {
          AWS = "arn:aws:iam::${data.aws_caller_identity.current.account_id}:root"
        }
        Action   = "kms:*"
        Resource = "*"
      },
      {
        Sid    = "Allow RDS to use the key"
        Effect = "Allow"
        Principal = {
          Service = "rds.amazonaws.com"
        }
        Action = [
          "kms:Encrypt",
          "kms:Decrypt",
          "kms:ReEncrypt*",
          "kms:GenerateDataKey*",
          "kms:DescribeKey"
        ]
        Resource = "*"
      }
    ]
  })

  tags = {
    Name        = "smart-dairy-rds-key"
    Environment = "production"
    Project     = "smart-dairy"
  }
}

resource "aws_kms_alias" "rds_encryption" {
  name          = "alias/smart-dairy-rds"
  target_key_id = aws_kms_key.rds_encryption.key_id
}

# Master encryption key for S3
resource "aws_kms_key" "s3_encryption" {
  description             = "KMS key for S3 bucket encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  tags = {
    Name        = "smart-dairy-s3-key"
    Environment = "production"
    Project     = "smart-dairy"
  }
}

resource "aws_kms_alias" "s3_encryption" {
  name          = "alias/smart-dairy-s3"
  target_key_id = aws_kms_key.s3_encryption.key_id
}

# Master encryption key for Secrets Manager
resource "aws_kms_key" "secrets_encryption" {
  description             = "KMS key for Secrets Manager"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  tags = {
    Name        = "smart-dairy-secrets-key"
    Environment = "production"
    Project     = "smart-dairy"
  }
}

resource "aws_kms_alias" "secrets_encryption" {
  name          = "alias/smart-dairy-secrets"
  target_key_id = aws_kms_key.secrets_encryption.key_id
}

# Application-level encryption key
resource "aws_kms_key" "application_encryption" {
  description             = "KMS key for application-level encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid    = "Enable IAM User Permissions"
        Effect = "Allow"
        Principal = {
          AWS = "arn:aws:iam::${data.aws_caller_identity.current.account_id}:root"
        }
        Action   = "kms:*"
        Resource = "*"
      },
      {
        Sid    = "Allow EKS pods to use the key"
        Effect = "Allow"
        Principal = {
          AWS = var.eks_node_role_arn
        }
        Action = [
          "kms:Encrypt",
          "kms:Decrypt",
          "kms:GenerateDataKey"
        ]
        Resource = "*"
      }
    ]
  })

  tags = {
    Name        = "smart-dairy-app-key"
    Environment = "production"
    Project     = "smart-dairy"
  }
}

resource "aws_kms_alias" "application_encryption" {
  name          = "alias/smart-dairy-app"
  target_key_id = aws_kms_key.application_encryption.key_id
}
```

**Task 2: EKS Secrets Encryption (4h)**

```hcl
# terraform/modules/security/kms/eks_secrets.tf

# KMS key for EKS secrets encryption
resource "aws_kms_key" "eks_secrets" {
  description             = "KMS key for EKS secrets encryption"
  deletion_window_in_days = 30
  enable_key_rotation     = true

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid    = "Enable IAM User Permissions"
        Effect = "Allow"
        Principal = {
          AWS = "arn:aws:iam::${data.aws_caller_identity.current.account_id}:root"
        }
        Action   = "kms:*"
        Resource = "*"
      },
      {
        Sid    = "Allow EKS to use the key"
        Effect = "Allow"
        Principal = {
          Service = "eks.amazonaws.com"
        }
        Action = [
          "kms:Encrypt",
          "kms:Decrypt",
          "kms:ReEncrypt*",
          "kms:GenerateDataKey*",
          "kms:DescribeKey"
        ]
        Resource = "*"
        Condition = {
          StringEquals = {
            "kms:CallerAccount" = data.aws_caller_identity.current.account_id
          }
        }
      }
    ]
  })

  tags = {
    Name        = "smart-dairy-eks-secrets-key"
    Environment = "production"
    Project     = "smart-dairy"
  }
}

# Update EKS cluster to use secrets encryption
resource "aws_eks_cluster" "main" {
  # ... other configuration ...

  encryption_config {
    provider {
      key_arn = aws_kms_key.eks_secrets.arn
    }
    resources = ["secrets"]
  }
}
```

#### Dev 2 Tasks (8 hours)

**Task 1: HashiCorp Vault Deployment (4h)**

```yaml
# kubernetes/vault/vault-deployment.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: vault
  labels:
    name: vault
---
apiVersion: v1
kind: ServiceAccount
metadata:
  name: vault
  namespace: vault
---
apiVersion: v1
kind: ConfigMap
metadata:
  name: vault-config
  namespace: vault
data:
  vault.hcl: |
    ui = true

    listener "tcp" {
      address = "[::]:8200"
      cluster_address = "[::]:8201"
      tls_cert_file = "/vault/tls/tls.crt"
      tls_key_file = "/vault/tls/tls.key"
    }

    storage "postgresql" {
      connection_url = "postgresql://vault:${VAULT_PG_PASSWORD}@vault-postgres:5432/vault?sslmode=require"
      table = "vault_kv_store"
      ha_enabled = true
      ha_table = "vault_ha_locks"
    }

    seal "awskms" {
      region     = "ap-south-1"
      kms_key_id = "${KMS_KEY_ID}"
    }

    telemetry {
      prometheus_retention_time = "30s"
      disable_hostname = true
    }

    api_addr = "https://vault.smartdairy.internal:8200"
    cluster_addr = "https://vault.smartdairy.internal:8201"
---
apiVersion: apps/v1
kind: StatefulSet
metadata:
  name: vault
  namespace: vault
spec:
  serviceName: vault
  replicas: 3
  selector:
    matchLabels:
      app: vault
  template:
    metadata:
      labels:
        app: vault
    spec:
      serviceAccountName: vault
      affinity:
        podAntiAffinity:
          requiredDuringSchedulingIgnoredDuringExecution:
          - labelSelector:
              matchLabels:
                app: vault
            topologyKey: kubernetes.io/hostname
      containers:
      - name: vault
        image: hashicorp/vault:1.15
        ports:
        - containerPort: 8200
          name: api
        - containerPort: 8201
          name: cluster
        env:
        - name: VAULT_ADDR
          value: "https://127.0.0.1:8200"
        - name: VAULT_API_ADDR
          value: "https://$(POD_IP):8200"
        - name: VAULT_CLUSTER_ADDR
          value: "https://$(POD_IP):8201"
        - name: SKIP_CHOWN
          value: "true"
        - name: POD_IP
          valueFrom:
            fieldRef:
              fieldPath: status.podIP
        volumeMounts:
        - name: vault-config
          mountPath: /vault/config
        - name: vault-tls
          mountPath: /vault/tls
        resources:
          requests:
            memory: "256Mi"
            cpu: "250m"
          limits:
            memory: "512Mi"
            cpu: "500m"
        readinessProbe:
          exec:
            command:
            - /bin/sh
            - -c
            - vault status -tls-skip-verify
          initialDelaySeconds: 5
          periodSeconds: 10
        livenessProbe:
          exec:
            command:
            - /bin/sh
            - -c
            - vault status -tls-skip-verify
          initialDelaySeconds: 30
          periodSeconds: 30
      volumes:
      - name: vault-config
        configMap:
          name: vault-config
      - name: vault-tls
        secret:
          secretName: vault-tls
---
apiVersion: v1
kind: Service
metadata:
  name: vault
  namespace: vault
spec:
  selector:
    app: vault
  ports:
  - name: api
    port: 8200
    targetPort: 8200
  - name: cluster
    port: 8201
    targetPort: 8201
  clusterIP: None
---
apiVersion: v1
kind: Service
metadata:
  name: vault-active
  namespace: vault
spec:
  selector:
    app: vault
    vault-active: "true"
  ports:
  - name: api
    port: 8200
    targetPort: 8200
```

**Task 2: Vault Policies and Secret Engines (4h)**

```hcl
# vault/policies/odoo-app-policy.hcl
# Policy for Odoo application pods

path "secret/data/smart-dairy/odoo/*" {
  capabilities = ["read", "list"]
}

path "secret/data/smart-dairy/database/*" {
  capabilities = ["read"]
}

path "secret/data/smart-dairy/redis/*" {
  capabilities = ["read"]
}

path "secret/data/smart-dairy/smtp/*" {
  capabilities = ["read"]
}

path "database/creds/odoo-readonly" {
  capabilities = ["read"]
}

path "database/creds/odoo-readwrite" {
  capabilities = ["read"]
}

# Deny access to sensitive paths
path "secret/data/smart-dairy/admin/*" {
  capabilities = ["deny"]
}

path "sys/*" {
  capabilities = ["deny"]
}
```

```bash
#!/bin/bash
# scripts/vault/setup_vault.sh
# Initialize and configure HashiCorp Vault

set -e

VAULT_ADDR="https://vault.smartdairy.internal:8200"
export VAULT_ADDR

echo "=== Smart Dairy Vault Setup ==="

# Wait for Vault to be ready
echo "Waiting for Vault to be ready..."
until vault status 2>/dev/null; do
    sleep 5
done

# Initialize Vault (only run once)
if ! vault status | grep -q "Initialized.*true"; then
    echo "Initializing Vault..."
    vault operator init -key-shares=5 -key-threshold=3 > /tmp/vault-init.txt

    echo "IMPORTANT: Store these keys securely!"
    cat /tmp/vault-init.txt
fi

# Enable secret engines
echo "Enabling secret engines..."

# KV v2 secrets engine
vault secrets enable -path=secret kv-v2

# Database secrets engine
vault secrets enable database

# Transit engine for encryption as a service
vault secrets enable transit

# PKI for certificate management
vault secrets enable pki
vault secrets tune -max-lease-ttl=87600h pki

# Configure database secrets engine
echo "Configuring database secrets engine..."
vault write database/config/postgresql \
    plugin_name=postgresql-database-plugin \
    allowed_roles="odoo-readonly,odoo-readwrite" \
    connection_url="postgresql://{{username}}:{{password}}@smart-dairy-db.cluster.ap-south-1.rds.amazonaws.com:5432/smartdairy?sslmode=require" \
    username="vault_admin" \
    password="${VAULT_DB_PASSWORD}"

# Create database roles
vault write database/roles/odoo-readonly \
    db_name=postgresql \
    creation_statements="CREATE ROLE \"{{name}}\" WITH LOGIN PASSWORD '{{password}}' VALID UNTIL '{{expiration}}'; GRANT SELECT ON ALL TABLES IN SCHEMA public TO \"{{name}}\";" \
    default_ttl="1h" \
    max_ttl="24h"

vault write database/roles/odoo-readwrite \
    db_name=postgresql \
    creation_statements="CREATE ROLE \"{{name}}\" WITH LOGIN PASSWORD '{{password}}' VALID UNTIL '{{expiration}}'; GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO \"{{name}}\";" \
    default_ttl="1h" \
    max_ttl="24h"

# Create transit encryption key
vault write -f transit/keys/smartdairy-encryption \
    type=aes256-gcm96

# Store application secrets
echo "Storing application secrets..."

vault kv put secret/smart-dairy/odoo/config \
    admin_password="${ODOO_ADMIN_PASSWORD}" \
    master_password="${ODOO_MASTER_PASSWORD}" \
    session_secret="${SESSION_SECRET}"

vault kv put secret/smart-dairy/database/config \
    host="smart-dairy-db.cluster.ap-south-1.rds.amazonaws.com" \
    port="5432" \
    database="smartdairy" \
    sslmode="require"

vault kv put secret/smart-dairy/redis/config \
    host="smart-dairy-redis.cache.amazonaws.com" \
    port="6379" \
    password="${REDIS_AUTH_TOKEN}"

vault kv put secret/smart-dairy/smtp/config \
    host="email-smtp.ap-south-1.amazonaws.com" \
    port="587" \
    username="${SES_SMTP_USER}" \
    password="${SES_SMTP_PASSWORD}"

# Create policies
echo "Creating policies..."
vault policy write odoo-app /vault/policies/odoo-app-policy.hcl
vault policy write api-service /vault/policies/api-service-policy.hcl
vault policy write admin /vault/policies/admin-policy.hcl

# Enable Kubernetes auth
echo "Enabling Kubernetes authentication..."
vault auth enable kubernetes

vault write auth/kubernetes/config \
    kubernetes_host="https://kubernetes.default.svc" \
    kubernetes_ca_cert=@/var/run/secrets/kubernetes.io/serviceaccount/ca.crt \
    token_reviewer_jwt=@/var/run/secrets/kubernetes.io/serviceaccount/token

# Create Kubernetes auth roles
vault write auth/kubernetes/role/odoo-app \
    bound_service_account_names=odoo-app \
    bound_service_account_namespaces=smart-dairy \
    policies=odoo-app \
    ttl=1h

vault write auth/kubernetes/role/api-service \
    bound_service_account_names=api-service \
    bound_service_account_namespaces=smart-dairy \
    policies=api-service \
    ttl=1h

echo "=== Vault Setup Complete ==="
```

#### Dev 3 Tasks (8 hours)

**Task 1: Vault Integration Documentation (4h)**

Create documentation for developers on how to access secrets from Vault.

**Task 2: Secret Rotation Scripts (4h)**

```python
# scripts/vault/secret_rotation.py
"""
Automated Secret Rotation Script
Rotates database credentials and API keys on schedule
"""

import hvac
import boto3
import logging
from datetime import datetime, timedelta
from typing import Dict, Optional

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class SecretRotator:
    def __init__(self, vault_addr: str, vault_token: str):
        self.client = hvac.Client(url=vault_addr, token=vault_token)
        self.secrets_manager = boto3.client('secretsmanager')

    def rotate_database_credentials(self, role: str) -> Dict:
        """Rotate database credentials using Vault's database engine"""
        try:
            # Revoke existing leases
            self.client.sys.revoke_prefix(f'database/creds/{role}')

            # Generate new credentials
            creds = self.client.secrets.database.generate_credentials(role)

            logger.info(f"Rotated credentials for role: {role}")

            return {
                "username": creds['data']['username'],
                "password": creds['data']['password'],
                "lease_id": creds['lease_id'],
                "lease_duration": creds['lease_duration']
            }
        except Exception as e:
            logger.error(f"Failed to rotate credentials: {e}")
            raise

    def rotate_api_key(self, service: str) -> str:
        """Rotate API key for a service"""
        import secrets

        new_key = secrets.token_urlsafe(32)

        # Update in Vault
        self.client.secrets.kv.v2.create_or_update_secret(
            path=f'smart-dairy/{service}/api-key',
            secret={'key': new_key, 'rotated_at': datetime.utcnow().isoformat()}
        )

        logger.info(f"Rotated API key for service: {service}")
        return new_key

    def rotate_encryption_key(self) -> None:
        """Rotate transit encryption key"""
        self.client.secrets.transit.rotate_key('smartdairy-encryption')
        logger.info("Rotated transit encryption key")

    def audit_secrets(self) -> Dict:
        """Audit all secrets and their ages"""
        audit_results = {
            "timestamp": datetime.utcnow().isoformat(),
            "secrets": []
        }

        # List all secrets
        secrets_list = self.client.secrets.kv.v2.list_secrets(path='smart-dairy')

        for secret_path in secrets_list['data']['keys']:
            metadata = self.client.secrets.kv.v2.read_secret_metadata(
                path=f'smart-dairy/{secret_path}'
            )

            created_time = metadata['data']['created_time']
            age_days = (datetime.utcnow() - datetime.fromisoformat(
                created_time.replace('Z', '+00:00')
            )).days

            audit_results["secrets"].append({
                "path": secret_path,
                "created": created_time,
                "age_days": age_days,
                "needs_rotation": age_days > 90
            })

        return audit_results


if __name__ == "__main__":
    import os

    rotator = SecretRotator(
        vault_addr=os.environ['VAULT_ADDR'],
        vault_token=os.environ['VAULT_TOKEN']
    )

    # Run audit
    audit = rotator.audit_secrets()
    print(f"Audit complete. {len(audit['secrets'])} secrets checked.")

    # Rotate any secrets older than 90 days
    for secret in audit['secrets']:
        if secret['needs_rotation']:
            print(f"Secret {secret['path']} needs rotation (age: {secret['age_days']} days)")
```

#### Day 712 Deliverables

- [x] AWS KMS keys created for RDS, S3, Secrets Manager, and EKS
- [x] HashiCorp Vault deployed on Kubernetes (3-node HA)
- [x] Vault policies configured for application access
- [x] Kubernetes authentication enabled in Vault
- [x] Database secrets engine configured
- [x] Transit encryption engine enabled
- [x] Secret rotation automation scripts

---

### Day 713: API Security Hardening

#### Objectives
- Implement OAuth 2.0 and JWT security enhancements
- Configure API rate limiting at application level
- Deploy API security middleware

#### Dev 1 Tasks (8 hours)

**Task 1: OAuth 2.0 Security Configuration (4h)**

```python
# addons/smart_dairy_api/security/oauth2_config.py
"""
OAuth 2.0 Security Configuration for Smart Dairy API
"""

from datetime import timedelta
from typing import Dict, List, Optional
import jwt
from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.backends import default_backend
import secrets
import hashlib
import base64

class OAuth2Config:
    """OAuth 2.0 configuration with security best practices"""

    # Token settings
    ACCESS_TOKEN_EXPIRE_MINUTES = 15
    REFRESH_TOKEN_EXPIRE_DAYS = 7

    # JWT settings
    ALGORITHM = "RS256"
    ISSUER = "https://auth.smartdairy.com"
    AUDIENCE = ["smart-dairy-api", "smart-dairy-mobile"]

    # Security settings
    REQUIRE_PKCE = True
    ALLOWED_GRANT_TYPES = ["authorization_code", "refresh_token", "client_credentials"]

    @staticmethod
    def generate_pkce_verifier() -> str:
        """Generate PKCE code verifier"""
        return secrets.token_urlsafe(64)

    @staticmethod
    def generate_pkce_challenge(verifier: str) -> str:
        """Generate PKCE code challenge from verifier"""
        digest = hashlib.sha256(verifier.encode()).digest()
        return base64.urlsafe_b64encode(digest).rstrip(b'=').decode()

    @staticmethod
    def validate_pkce(verifier: str, challenge: str) -> bool:
        """Validate PKCE code verifier against challenge"""
        computed_challenge = OAuth2Config.generate_pkce_challenge(verifier)
        return secrets.compare_digest(computed_challenge, challenge)


class JWTSecurityManager:
    """Secure JWT token management"""

    def __init__(self, private_key_path: str, public_key_path: str):
        with open(private_key_path, 'rb') as f:
            self.private_key = serialization.load_pem_private_key(
                f.read(),
                password=None,
                backend=default_backend()
            )

        with open(public_key_path, 'rb') as f:
            self.public_key = serialization.load_pem_public_key(
                f.read(),
                backend=default_backend()
            )

    def create_access_token(
        self,
        user_id: int,
        scopes: List[str],
        client_id: str,
        additional_claims: Optional[Dict] = None
    ) -> str:
        """Create a secure access token"""
        import time

        now = int(time.time())
        expires = now + (OAuth2Config.ACCESS_TOKEN_EXPIRE_MINUTES * 60)

        payload = {
            "iss": OAuth2Config.ISSUER,
            "sub": str(user_id),
            "aud": OAuth2Config.AUDIENCE,
            "exp": expires,
            "iat": now,
            "nbf": now,
            "jti": secrets.token_urlsafe(16),
            "client_id": client_id,
            "scope": " ".join(scopes),
            "token_type": "access"
        }

        if additional_claims:
            payload.update(additional_claims)

        return jwt.encode(
            payload,
            self.private_key,
            algorithm=OAuth2Config.ALGORITHM
        )

    def create_refresh_token(
        self,
        user_id: int,
        client_id: str,
        token_family: str
    ) -> str:
        """Create a secure refresh token with rotation support"""
        import time

        now = int(time.time())
        expires = now + (OAuth2Config.REFRESH_TOKEN_EXPIRE_DAYS * 86400)

        payload = {
            "iss": OAuth2Config.ISSUER,
            "sub": str(user_id),
            "exp": expires,
            "iat": now,
            "jti": secrets.token_urlsafe(16),
            "client_id": client_id,
            "token_family": token_family,
            "token_type": "refresh"
        }

        return jwt.encode(
            payload,
            self.private_key,
            algorithm=OAuth2Config.ALGORITHM
        )

    def validate_token(self, token: str, token_type: str = "access") -> Dict:
        """Validate and decode a JWT token"""
        try:
            payload = jwt.decode(
                token,
                self.public_key,
                algorithms=[OAuth2Config.ALGORITHM],
                issuer=OAuth2Config.ISSUER,
                audience=OAuth2Config.AUDIENCE,
                options={
                    "require": ["exp", "iat", "sub", "jti"],
                    "verify_exp": True,
                    "verify_iat": True,
                    "verify_nbf": True
                }
            )

            if payload.get("token_type") != token_type:
                raise jwt.InvalidTokenError("Invalid token type")

            return payload

        except jwt.ExpiredSignatureError:
            raise ValueError("Token has expired")
        except jwt.InvalidTokenError as e:
            raise ValueError(f"Invalid token: {str(e)}")
```

**Task 2: Rate Limiting Implementation (4h)**

```python
# addons/smart_dairy_api/security/rate_limiter.py
"""
API Rate Limiting with Redis Backend
"""

import time
import redis
from typing import Tuple, Optional
from dataclasses import dataclass
from functools import wraps
import logging

logger = logging.getLogger(__name__)

@dataclass
class RateLimitConfig:
    """Rate limit configuration"""
    requests_per_minute: int = 60
    requests_per_hour: int = 1000
    requests_per_day: int = 10000
    burst_limit: int = 10

    # Endpoint-specific limits
    ENDPOINT_LIMITS = {
        "/api/v1/auth/login": {"per_minute": 5, "per_hour": 20},
        "/api/v1/auth/register": {"per_minute": 3, "per_hour": 10},
        "/api/v1/auth/password-reset": {"per_minute": 3, "per_hour": 10},
        "/api/v1/reports/generate": {"per_minute": 5, "per_hour": 50},
        "/api/v1/bulk/*": {"per_minute": 10, "per_hour": 100},
    }


class SlidingWindowRateLimiter:
    """Sliding window rate limiter with Redis"""

    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
        self.config = RateLimitConfig()

    def _get_window_key(self, identifier: str, window: str) -> str:
        """Generate Redis key for rate limit window"""
        return f"ratelimit:{identifier}:{window}"

    def check_rate_limit(
        self,
        identifier: str,
        endpoint: str = None
    ) -> Tuple[bool, dict]:
        """
        Check if request is within rate limits
        Returns: (allowed: bool, headers: dict)
        """
        now = time.time()
        pipe = self.redis.pipeline()

        # Get endpoint-specific limits or use defaults
        limits = self.config.ENDPOINT_LIMITS.get(endpoint, {})
        per_minute = limits.get('per_minute', self.config.requests_per_minute)
        per_hour = limits.get('per_hour', self.config.requests_per_hour)

        # Check minute window
        minute_key = self._get_window_key(identifier, f"minute:{int(now // 60)}")
        pipe.incr(minute_key)
        pipe.expire(minute_key, 60)

        # Check hour window
        hour_key = self._get_window_key(identifier, f"hour:{int(now // 3600)}")
        pipe.incr(hour_key)
        pipe.expire(hour_key, 3600)

        results = pipe.execute()
        minute_count = results[0]
        hour_count = results[2]

        # Build response headers
        headers = {
            "X-RateLimit-Limit-Minute": str(per_minute),
            "X-RateLimit-Remaining-Minute": str(max(0, per_minute - minute_count)),
            "X-RateLimit-Limit-Hour": str(per_hour),
            "X-RateLimit-Remaining-Hour": str(max(0, per_hour - hour_count)),
            "X-RateLimit-Reset": str(int((now // 60 + 1) * 60))
        }

        # Check limits
        if minute_count > per_minute:
            headers["Retry-After"] = str(int(60 - (now % 60)))
            return False, headers

        if hour_count > per_hour:
            headers["Retry-After"] = str(int(3600 - (now % 3600)))
            return False, headers

        return True, headers

    def get_client_identifier(self, request) -> str:
        """Get unique identifier for rate limiting"""
        # Use API key if available
        api_key = request.headers.get('X-API-Key')
        if api_key:
            return f"apikey:{api_key[:16]}"

        # Use authenticated user ID
        user_id = getattr(request, 'user_id', None)
        if user_id:
            return f"user:{user_id}"

        # Fall back to IP address
        forwarded_for = request.headers.get('X-Forwarded-For')
        if forwarded_for:
            ip = forwarded_for.split(',')[0].strip()
        else:
            ip = request.remote_addr

        return f"ip:{ip}"


def rate_limit(endpoint: str = None):
    """Decorator for rate limiting API endpoints"""
    def decorator(func):
        @wraps(func)
        def wrapper(self, *args, **kwargs):
            limiter = self.env['smart.dairy.rate.limiter']

            identifier = limiter.get_client_identifier(self.request)
            allowed, headers = limiter.check_rate_limit(identifier, endpoint)

            # Add rate limit headers to response
            for key, value in headers.items():
                self.response.headers[key] = value

            if not allowed:
                return self._rate_limit_exceeded_response(headers)

            return func(self, *args, **kwargs)
        return wrapper
    return decorator
```

#### Dev 2 Tasks (8 hours)

**Task 1: API Security Middleware (4h)**

```python
# addons/smart_dairy_api/security/middleware.py
"""
API Security Middleware
"""

import re
import time
import hashlib
import logging
from typing import Optional, List
from functools import wraps

logger = logging.getLogger(__name__)

class SecurityMiddleware:
    """Comprehensive security middleware for API requests"""

    # Blocked patterns (potential attacks)
    BLOCKED_PATTERNS = [
        r'\.\./',  # Path traversal
        r'<script',  # XSS
        r'javascript:',  # XSS
        r'on\w+\s*=',  # Event handlers
        r'union\s+select',  # SQL injection
        r';\s*drop\s+table',  # SQL injection
        r'\$\{.*\}',  # Template injection
        r'{{.*}}',  # Template injection
    ]

    # Required headers for API requests
    REQUIRED_HEADERS = ['X-Request-ID', 'X-Client-Version']

    def __init__(self):
        self.compiled_patterns = [
            re.compile(p, re.IGNORECASE) for p in self.BLOCKED_PATTERNS
        ]

    def validate_request(self, request) -> tuple[bool, Optional[str]]:
        """Validate incoming request for security issues"""

        # Check for blocked patterns in URL
        url = request.full_path
        for pattern in self.compiled_patterns:
            if pattern.search(url):
                logger.warning(f"Blocked pattern detected in URL: {url}")
                return False, "Invalid request URL"

        # Check request body for blocked patterns
        if request.data:
            body = request.data.decode('utf-8', errors='ignore')
            for pattern in self.compiled_patterns:
                if pattern.search(body):
                    logger.warning(f"Blocked pattern detected in body")
                    return False, "Invalid request body"

        # Validate Content-Type for POST/PUT requests
        if request.method in ['POST', 'PUT', 'PATCH']:
            content_type = request.headers.get('Content-Type', '')
            if not content_type.startswith(('application/json', 'multipart/form-data')):
                return False, "Invalid Content-Type"

        # Check request size
        content_length = request.headers.get('Content-Length', 0)
        if int(content_length) > 10 * 1024 * 1024:  # 10MB limit
            return False, "Request too large"

        return True, None

    def add_security_headers(self, response):
        """Add security headers to response"""
        response.headers['X-Content-Type-Options'] = 'nosniff'
        response.headers['X-Frame-Options'] = 'DENY'
        response.headers['X-XSS-Protection'] = '1; mode=block'
        response.headers['Cache-Control'] = 'no-store, no-cache, must-revalidate'
        response.headers['Pragma'] = 'no-cache'

        # Remove potentially sensitive headers
        response.headers.pop('Server', None)
        response.headers.pop('X-Powered-By', None)

        return response

    def validate_api_key(self, api_key: str) -> bool:
        """Validate API key format and checksum"""
        if not api_key or len(api_key) != 64:
            return False

        # Validate format (hex characters only)
        if not re.match(r'^[a-f0-9]+$', api_key.lower()):
            return False

        return True

    def sanitize_input(self, data: dict) -> dict:
        """Sanitize input data"""
        import html

        def sanitize_value(value):
            if isinstance(value, str):
                # HTML escape
                value = html.escape(value)
                # Remove null bytes
                value = value.replace('\x00', '')
                # Trim excessive whitespace
                value = ' '.join(value.split())
            elif isinstance(value, dict):
                value = {k: sanitize_value(v) for k, v in value.items()}
            elif isinstance(value, list):
                value = [sanitize_value(v) for v in value]
            return value

        return sanitize_value(data)


class RequestSignatureValidator:
    """Validate request signatures for API integrity"""

    def __init__(self, secret_key: str):
        self.secret_key = secret_key

    def generate_signature(
        self,
        method: str,
        path: str,
        timestamp: str,
        body: str = ""
    ) -> str:
        """Generate HMAC signature for request"""
        message = f"{method}\n{path}\n{timestamp}\n{body}"
        signature = hashlib.hmac(
            self.secret_key.encode(),
            message.encode(),
            hashlib.sha256
        ).hexdigest()
        return signature

    def validate_signature(
        self,
        request,
        signature: str,
        timestamp: str
    ) -> bool:
        """Validate request signature"""
        # Check timestamp freshness (5 minute window)
        request_time = int(timestamp)
        current_time = int(time.time())

        if abs(current_time - request_time) > 300:
            logger.warning("Request signature timestamp expired")
            return False

        # Generate expected signature
        body = request.data.decode('utf-8') if request.data else ""
        expected = self.generate_signature(
            request.method,
            request.path,
            timestamp,
            body
        )

        # Constant-time comparison
        return hashlib.compare_digest(expected, signature)
```

**Task 2: Security Audit Logging (4h)**

```python
# addons/smart_dairy_api/security/audit_logger.py
"""
Security Audit Logging
"""

import json
import logging
from datetime import datetime
from typing import Dict, Optional
import hashlib

logger = logging.getLogger('security.audit')

class SecurityAuditLogger:
    """Centralized security audit logging"""

    # Event types
    AUTH_SUCCESS = "auth.success"
    AUTH_FAILURE = "auth.failure"
    AUTH_LOGOUT = "auth.logout"
    ACCESS_DENIED = "access.denied"
    API_KEY_CREATED = "apikey.created"
    API_KEY_REVOKED = "apikey.revoked"
    PERMISSION_CHANGED = "permission.changed"
    DATA_EXPORT = "data.export"
    ADMIN_ACTION = "admin.action"
    SUSPICIOUS_ACTIVITY = "suspicious.activity"
    RATE_LIMIT_EXCEEDED = "ratelimit.exceeded"

    def __init__(self, env):
        self.env = env

    def _get_request_context(self, request) -> Dict:
        """Extract request context for logging"""
        return {
            "ip_address": self._get_client_ip(request),
            "user_agent": request.headers.get('User-Agent', ''),
            "request_id": request.headers.get('X-Request-ID', ''),
            "path": request.path,
            "method": request.method,
            "referer": request.headers.get('Referer', ''),
        }

    def _get_client_ip(self, request) -> str:
        """Get client IP from request"""
        forwarded = request.headers.get('X-Forwarded-For')
        if forwarded:
            return forwarded.split(',')[0].strip()
        return request.remote_addr

    def _hash_sensitive(self, value: str) -> str:
        """Hash sensitive values for logging"""
        return hashlib.sha256(value.encode()).hexdigest()[:16]

    def log_event(
        self,
        event_type: str,
        user_id: Optional[int],
        request,
        details: Dict = None,
        severity: str = "INFO"
    ):
        """Log a security event"""
        event = {
            "timestamp": datetime.utcnow().isoformat(),
            "event_type": event_type,
            "severity": severity,
            "user_id": user_id,
            "context": self._get_request_context(request),
            "details": details or {}
        }

        # Log to Python logger
        log_method = getattr(logger, severity.lower(), logger.info)
        log_method(json.dumps(event))

        # Store in database for audit trail
        self.env['smart.dairy.audit.log'].sudo().create({
            'event_type': event_type,
            'user_id': user_id,
            'ip_address': event['context']['ip_address'],
            'user_agent': event['context']['user_agent'][:255],
            'request_path': event['context']['path'],
            'request_method': event['context']['method'],
            'details': json.dumps(details or {}),
            'severity': severity,
        })

    def log_auth_success(self, user_id: int, request, method: str = "password"):
        """Log successful authentication"""
        self.log_event(
            self.AUTH_SUCCESS,
            user_id,
            request,
            {"auth_method": method}
        )

    def log_auth_failure(self, username: str, request, reason: str):
        """Log failed authentication"""
        self.log_event(
            self.AUTH_FAILURE,
            None,
            request,
            {
                "username_hash": self._hash_sensitive(username),
                "reason": reason
            },
            severity="WARNING"
        )

    def log_suspicious_activity(
        self,
        user_id: Optional[int],
        request,
        activity_type: str,
        details: Dict
    ):
        """Log suspicious activity"""
        self.log_event(
            self.SUSPICIOUS_ACTIVITY,
            user_id,
            request,
            {
                "activity_type": activity_type,
                **details
            },
            severity="WARNING"
        )

    def log_admin_action(
        self,
        user_id: int,
        request,
        action: str,
        target: str,
        details: Dict = None
    ):
        """Log administrative action"""
        self.log_event(
            self.ADMIN_ACTION,
            user_id,
            request,
            {
                "action": action,
                "target": target,
                **(details or {})
            }
        )
```

#### Dev 3 Tasks (8 hours)

**Task 1: Security Headers for Frontend (4h)**

```javascript
// frontend/src/security/cspConfig.js
/**
 * Content Security Policy Configuration for Smart Dairy Frontend
 */

export const CSP_CONFIG = {
  // Default source for all content
  'default-src': ["'self'"],

  // Script sources
  'script-src': [
    "'self'",
    "'unsafe-inline'",  // Required for Odoo/OWL
    "'unsafe-eval'",    // Required for Odoo templates
    "https://cdn.jsdelivr.net",
  ],

  // Style sources
  'style-src': [
    "'self'",
    "'unsafe-inline'",  // Required for dynamic styles
    "https://fonts.googleapis.com",
  ],

  // Font sources
  'font-src': [
    "'self'",
    "https://fonts.gstatic.com",
    "data:",
  ],

  // Image sources
  'img-src': [
    "'self'",
    "data:",
    "https:",
    "blob:",
  ],

  // API connections
  'connect-src': [
    "'self'",
    "https://api.smartdairy.com",
    "wss://ws.smartdairy.com",
    "https://sentry.smartdairy.com",
  ],

  // Frame restrictions
  'frame-ancestors': ["'none'"],

  // Form submissions
  'form-action': ["'self'"],

  // Base URI restriction
  'base-uri': ["'self'"],

  // Object sources (Flash, etc.)
  'object-src': ["'none'"],
};

export function generateCSPHeader() {
  return Object.entries(CSP_CONFIG)
    .map(([directive, sources]) => `${directive} ${sources.join(' ')}`)
    .join('; ');
}
```

**Task 2: Input Validation Library (4h)**

```javascript
// frontend/src/security/inputValidation.js
/**
 * Input Validation and Sanitization Library
 */

import DOMPurify from 'dompurify';

export class InputValidator {
  /**
   * Sanitize HTML input
   */
  static sanitizeHTML(input) {
    return DOMPurify.sanitize(input, {
      ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'p', 'br'],
      ALLOWED_ATTR: [],
    });
  }

  /**
   * Validate email format
   */
  static isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
  }

  /**
   * Validate phone number (Indian format)
   */
  static isValidPhone(phone) {
    const phoneRegex = /^(\+91|91)?[6-9]\d{9}$/;
    return phoneRegex.test(phone.replace(/[\s-]/g, ''));
  }

  /**
   * Validate password strength
   */
  static validatePasswordStrength(password) {
    const checks = {
      minLength: password.length >= 12,
      hasUppercase: /[A-Z]/.test(password),
      hasLowercase: /[a-z]/.test(password),
      hasNumber: /\d/.test(password),
      hasSpecial: /[!@#$%^&*(),.?":{}|<>]/.test(password),
      noCommonPatterns: !/(password|123456|qwerty)/i.test(password),
    };

    const score = Object.values(checks).filter(Boolean).length;

    return {
      valid: score >= 5,
      score,
      checks,
      message: score >= 5 ? 'Strong password' : 'Password does not meet requirements',
    };
  }

  /**
   * Sanitize filename
   */
  static sanitizeFilename(filename) {
    return filename
      .replace(/[^a-zA-Z0-9._-]/g, '_')
      .replace(/\.{2,}/g, '.')
      .substring(0, 255);
  }

  /**
   * Validate and sanitize SQL-like input
   */
  static sanitizeForQuery(input) {
    if (typeof input !== 'string') return input;

    // Remove SQL injection patterns
    const dangerousPatterns = [
      /--/g,
      /;/g,
      /\/\*/g,
      /\*\//g,
      /xp_/gi,
      /union\s+select/gi,
      /insert\s+into/gi,
      /drop\s+table/gi,
    ];

    let sanitized = input;
    for (const pattern of dangerousPatterns) {
      sanitized = sanitized.replace(pattern, '');
    }

    return sanitized;
  }
}
```

#### Day 713 Deliverables

- [x] OAuth 2.0 with PKCE implemented
- [x] JWT security hardening (RS256, proper claims)
- [x] Sliding window rate limiting with Redis
- [x] API security middleware deployed
- [x] Request signature validation
- [x] Security audit logging
- [x] Frontend CSP configuration
- [x] Input validation library

---

### Day 714: Penetration Testing & Remediation

#### Objectives
- Execute comprehensive penetration testing
- Remediate identified vulnerabilities
- Document security findings and fixes

#### Dev 1 Tasks (8 hours)

**Task 1: Automated Security Scanning (4h)**

```bash
#!/bin/bash
# scripts/security/automated_security_scan.sh
# Automated security scanning for Smart Dairy

set -e

SCAN_DIR="/tmp/security-scans/$(date +%Y%m%d-%H%M%S)"
mkdir -p "$SCAN_DIR"

echo "=== Smart Dairy Automated Security Scan ==="
echo "Output directory: $SCAN_DIR"

# Run OWASP ZAP baseline scan
echo "Running OWASP ZAP baseline scan..."
docker run -v "$SCAN_DIR:/zap/wrk:rw" \
    -t owasp/zap2docker-stable zap-baseline.py \
    -t https://staging.smartdairy.com \
    -r zap-baseline-report.html \
    -J zap-baseline-report.json \
    -I

# Run Nuclei vulnerability scanner
echo "Running Nuclei vulnerability scanner..."
docker run -v "$SCAN_DIR:/output" \
    projectdiscovery/nuclei:latest \
    -u https://staging.smartdairy.com \
    -t cves,vulnerabilities,exposures \
    -o /output/nuclei-results.txt \
    -je /output/nuclei-results.json

# Run SSL/TLS scan
echo "Running SSL/TLS scan..."
docker run -v "$SCAN_DIR:/output" \
    nablac0d3/sslyze:latest \
    staging.smartdairy.com:443 \
    --json_out /output/sslyze-report.json

# Run security headers check
echo "Checking security headers..."
curl -sI https://staging.smartdairy.com | tee "$SCAN_DIR/headers.txt"

# Run dependency vulnerability scan
echo "Scanning Python dependencies..."
docker run -v "$(pwd):/src" \
    pyupio/safety check \
    -r /src/requirements.txt \
    --json > "$SCAN_DIR/python-deps.json" || true

echo "Scanning npm dependencies..."
cd frontend && npm audit --json > "$SCAN_DIR/npm-audit.json" || true

echo "=== Scan Complete ==="
echo "Reports saved to: $SCAN_DIR"
```

**Task 2: Vulnerability Remediation Scripts (4h)**

```python
# scripts/security/remediate_vulnerabilities.py
"""
Automated Vulnerability Remediation
"""

import json
import subprocess
import logging
from pathlib import Path
from typing import List, Dict

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class VulnerabilityRemediator:
    """Automated vulnerability remediation"""

    def __init__(self, scan_results_dir: str):
        self.scan_dir = Path(scan_results_dir)
        self.remediations: List[Dict] = []

    def process_npm_audit(self) -> List[Dict]:
        """Process and remediate npm vulnerabilities"""
        audit_file = self.scan_dir / 'npm-audit.json'
        if not audit_file.exists():
            return []

        with open(audit_file) as f:
            audit_data = json.load(f)

        vulnerabilities = audit_data.get('vulnerabilities', {})
        remediations = []

        for pkg_name, vuln_info in vulnerabilities.items():
            severity = vuln_info.get('severity', 'unknown')

            if severity in ['critical', 'high']:
                # Attempt automatic fix
                fix_available = vuln_info.get('fixAvailable', False)

                if fix_available:
                    logger.info(f"Fixing {pkg_name} ({severity})")
                    result = subprocess.run(
                        ['npm', 'audit', 'fix', '--force'],
                        capture_output=True,
                        text=True
                    )

                    remediations.append({
                        'package': pkg_name,
                        'severity': severity,
                        'action': 'auto-fixed',
                        'success': result.returncode == 0
                    })
                else:
                    remediations.append({
                        'package': pkg_name,
                        'severity': severity,
                        'action': 'manual-review-required',
                        'recommendation': vuln_info.get('recommendation', '')
                    })

        return remediations

    def process_python_deps(self) -> List[Dict]:
        """Process Python dependency vulnerabilities"""
        deps_file = self.scan_dir / 'python-deps.json'
        if not deps_file.exists():
            return []

        with open(deps_file) as f:
            safety_data = json.load(f)

        remediations = []

        for vuln in safety_data.get('vulnerabilities', []):
            pkg_name = vuln.get('package_name')
            current_version = vuln.get('analyzed_version')
            fixed_version = vuln.get('more_info', {}).get('patched_versions', [])

            if fixed_version:
                # Update to fixed version
                target_version = fixed_version[0] if isinstance(fixed_version, list) else fixed_version

                logger.info(f"Updating {pkg_name} from {current_version} to {target_version}")
                result = subprocess.run(
                    ['pip', 'install', f'{pkg_name}>={target_version}'],
                    capture_output=True,
                    text=True
                )

                remediations.append({
                    'package': pkg_name,
                    'from_version': current_version,
                    'to_version': target_version,
                    'action': 'upgraded',
                    'success': result.returncode == 0
                })
            else:
                remediations.append({
                    'package': pkg_name,
                    'version': current_version,
                    'action': 'no-fix-available',
                    'recommendation': 'Consider alternative package'
                })

        return remediations

    def generate_report(self) -> Dict:
        """Generate remediation report"""
        npm_remediations = self.process_npm_audit()
        python_remediations = self.process_python_deps()

        report = {
            'timestamp': datetime.utcnow().isoformat(),
            'npm': {
                'total': len(npm_remediations),
                'auto_fixed': sum(1 for r in npm_remediations if r.get('action') == 'auto-fixed'),
                'details': npm_remediations
            },
            'python': {
                'total': len(python_remediations),
                'upgraded': sum(1 for r in python_remediations if r.get('action') == 'upgraded'),
                'details': python_remediations
            }
        }

        return report


if __name__ == "__main__":
    import sys
    from datetime import datetime

    scan_dir = sys.argv[1] if len(sys.argv) > 1 else '/tmp/security-scans/latest'

    remediator = VulnerabilityRemediator(scan_dir)
    report = remediator.generate_report()

    output_file = Path(scan_dir) / 'remediation-report.json'
    with open(output_file, 'w') as f:
        json.dump(report, f, indent=2)

    print(f"Remediation report saved to: {output_file}")
```

#### Dev 2 Tasks (8 hours)

**Task 1: Manual Penetration Testing Checklist (4h)**

Execute manual penetration testing following OWASP Testing Guide.

**Task 2: Penetration Test Report Template (4h)**

```markdown
# Smart Dairy Penetration Test Report

## Executive Summary

| Attribute | Value |
|-----------|-------|
| Test Date | [DATE] |
| Tester | Dev 2 |
| Scope | Production-equivalent staging environment |
| Methodology | OWASP Testing Guide v4.2 |

### Findings Summary

| Severity | Count | Status |
|----------|-------|--------|
| Critical | 0 | - |
| High | 0 | - |
| Medium | 2 | Remediated |
| Low | 3 | Accepted |
| Informational | 5 | Documented |

## Detailed Findings

### [FINDING-001] Example Vulnerability

**Severity:** Medium

**Location:** /api/v1/users/{id}

**Description:**
[Description of the vulnerability]

**Evidence:**
```
[Request/response showing the vulnerability]
```

**Impact:**
[Business impact of the vulnerability]

**Remediation:**
[Steps taken to fix]

**Verification:**
[Evidence that fix is working]

---

## Testing Coverage

### Authentication Testing
- [x] Password brute force protection
- [x] Session management
- [x] Multi-factor authentication
- [x] Password reset flow

### Authorization Testing
- [x] Horizontal privilege escalation
- [x] Vertical privilege escalation
- [x] IDOR vulnerabilities
- [x] API authorization

### Input Validation Testing
- [x] SQL injection
- [x] XSS (Reflected, Stored, DOM)
- [x] Command injection
- [x] SSRF

### Business Logic Testing
- [x] Workflow bypass
- [x] Rate limiting
- [x] Data validation

## Recommendations

1. [Recommendation 1]
2. [Recommendation 2]

## Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Security Lead | | | |
| Project Manager | | | |
```

#### Dev 3 Tasks (8 hours)

**Task 1: Security Testing Documentation (4h)**

Document all security tests performed and results.

**Task 2: Security Fix Verification (4h)**

Create verification scripts for all security fixes.

#### Day 714 Deliverables

- [x] Automated security scans executed
- [x] Vulnerability remediation completed
- [x] Penetration test report generated
- [x] Security fixes verified
- [x] Documentation updated

---

### Day 715: Security Audit & Compliance Sign-off

#### Objectives
- Complete security audit documentation
- Obtain compliance verification
- Security sign-off from stakeholders

#### Dev 1 Tasks (8 hours)

**Task 1: Security Audit Checklist (4h)**

```markdown
# Smart Dairy Security Audit Checklist

## 1. Infrastructure Security

### Network Security
- [x] VPC with private subnets for databases
- [x] Security groups with least privilege
- [x] Network ACLs configured
- [x] VPN for administrative access
- [x] No public IP on backend services

### Encryption
- [x] TLS 1.3 for all connections
- [x] SSL Labs Grade: A+
- [x] RDS encryption at rest (KMS)
- [x] S3 bucket encryption (SSE-KMS)
- [x] EBS volume encryption

### Access Control
- [x] IAM roles with least privilege
- [x] No root account usage
- [x] MFA enabled for all IAM users
- [x] Service accounts with limited scope
- [x] Regular access reviews

## 2. Application Security

### Authentication
- [x] OAuth 2.0 with PKCE
- [x] JWT with RS256 signing
- [x] Secure session management
- [x] Brute force protection
- [x] Account lockout policy

### Authorization
- [x] Role-based access control (RBAC)
- [x] API endpoint authorization
- [x] Resource-level permissions
- [x] No privilege escalation vulnerabilities

### Input Validation
- [x] Server-side validation
- [x] SQL injection prevention
- [x] XSS prevention
- [x] CSRF protection
- [x] File upload validation

### Data Protection
- [x] PII encryption
- [x] Secure data transmission
- [x] Data masking in logs
- [x] Secure password storage (bcrypt)

## 3. Operational Security

### Monitoring & Logging
- [x] Centralized logging (ELK)
- [x] Security event monitoring
- [x] Alerting for security events
- [x] Log retention policy (90 days)

### Incident Response
- [x] Incident response plan
- [x] Escalation procedures
- [x] Contact information updated
- [x] Runbooks available

### Backup & Recovery
- [x] Automated backups
- [x] Backup encryption
- [x] Recovery testing performed
- [x] RTO/RPO requirements met

## 4. Compliance

### OWASP Top 10 (2021)
- [x] A01: Broken Access Control - Addressed
- [x] A02: Cryptographic Failures - Addressed
- [x] A03: Injection - Addressed
- [x] A04: Insecure Design - Addressed
- [x] A05: Security Misconfiguration - Addressed
- [x] A06: Vulnerable Components - Addressed
- [x] A07: Authentication Failures - Addressed
- [x] A08: Data Integrity Failures - Addressed
- [x] A09: Logging Failures - Addressed
- [x] A10: SSRF - Addressed
```

**Task 2: Compliance Evidence Collection (4h)**

Collect and organize all compliance evidence.

#### Dev 2 Tasks (8 hours)

**Task 1: Final Security Review (4h)**

```python
# scripts/security/final_security_review.py
"""
Final Security Review Script
Validates all security controls are in place
"""

import json
import requests
import subprocess
from typing import Dict, List
from dataclasses import dataclass

@dataclass
class SecurityCheck:
    name: str
    category: str
    passed: bool
    details: str

class FinalSecurityReview:
    def __init__(self, target_url: str):
        self.target_url = target_url
        self.checks: List[SecurityCheck] = []

    def check_ssl_configuration(self) -> SecurityCheck:
        """Verify SSL/TLS configuration"""
        result = subprocess.run(
            ['curl', '-sI', self.target_url],
            capture_output=True,
            text=True
        )

        has_hsts = 'strict-transport-security' in result.stdout.lower()

        return SecurityCheck(
            name="SSL/TLS Configuration",
            category="Encryption",
            passed=has_hsts,
            details="HSTS header present" if has_hsts else "HSTS header missing"
        )

    def check_security_headers(self) -> SecurityCheck:
        """Verify security headers"""
        response = requests.head(self.target_url)
        headers = response.headers

        required_headers = [
            'X-Content-Type-Options',
            'X-Frame-Options',
            'X-XSS-Protection',
            'Content-Security-Policy'
        ]

        missing = [h for h in required_headers if h not in headers]
        passed = len(missing) == 0

        return SecurityCheck(
            name="Security Headers",
            category="Application",
            passed=passed,
            details=f"Missing headers: {missing}" if missing else "All required headers present"
        )

    def check_rate_limiting(self) -> SecurityCheck:
        """Verify rate limiting is active"""
        responses = []
        for _ in range(10):
            r = requests.get(f"{self.target_url}/api/v1/health")
            responses.append(r.status_code)

        has_rate_limit_header = 'X-RateLimit-Limit' in r.headers

        return SecurityCheck(
            name="Rate Limiting",
            category="Application",
            passed=has_rate_limit_header,
            details="Rate limiting headers present" if has_rate_limit_header else "Rate limiting not detected"
        )

    def run_all_checks(self) -> Dict:
        """Run all security checks"""
        self.checks.append(self.check_ssl_configuration())
        self.checks.append(self.check_security_headers())
        self.checks.append(self.check_rate_limiting())

        passed = sum(1 for c in self.checks if c.passed)

        return {
            "total_checks": len(self.checks),
            "passed": passed,
            "failed": len(self.checks) - passed,
            "pass_rate": f"{(passed/len(self.checks))*100:.1f}%",
            "checks": [
                {
                    "name": c.name,
                    "category": c.category,
                    "status": "PASS" if c.passed else "FAIL",
                    "details": c.details
                }
                for c in self.checks
            ]
        }


if __name__ == "__main__":
    import sys

    target = sys.argv[1] if len(sys.argv) > 1 else "https://staging.smartdairy.com"

    review = FinalSecurityReview(target)
    results = review.run_all_checks()

    print(json.dumps(results, indent=2))
```

**Task 2: Security Sign-off Documentation (4h)**

Prepare security sign-off documents.

#### Dev 3 Tasks (8 hours)

**Task 1: Security Training Materials Update (4h)**

Update security awareness materials with new controls.

**Task 2: Security Documentation Finalization (4h)**

Finalize all security documentation for handover.

#### Day 715 Deliverables

- [x] Security audit checklist completed
- [x] Compliance evidence collected
- [x] Final security review passed
- [x] Security sign-off obtained
- [x] Security documentation finalized

---

## 4. Technical Specifications

### 4.1 Security Tools

| Tool | Purpose | Version |
|------|---------|---------|
| AWS WAF | Web Application Firewall | v2 |
| HashiCorp Vault | Secrets Management | 1.15 |
| AWS KMS | Key Management | - |
| OWASP ZAP | Security Scanning | 2.14 |
| Nuclei | Vulnerability Scanner | 3.0 |

### 4.2 Compliance Standards

| Standard | Coverage | Status |
|----------|----------|--------|
| OWASP Top 10 (2021) | 100% | Compliant |
| PCI DSS (if applicable) | Scoped | Review |
| ISO 27001 | Aligned | Documented |

---

## 5. Testing & Validation

### 5.1 Security Testing Matrix

| Test Type | Tool | Frequency | Owner |
|-----------|------|-----------|-------|
| SAST | SonarQube | Per commit | CI/CD |
| DAST | OWASP ZAP | Weekly | Dev 2 |
| Dependency Scan | Safety/npm audit | Daily | CI/CD |
| Penetration Test | Manual | Quarterly | External |

### 5.2 Acceptance Criteria

| Criteria | Target | Actual | Status |
|----------|--------|--------|--------|
| WAF blocking rate | >99% | TBD | Pending |
| SSL Labs grade | A+ | TBD | Pending |
| OWASP compliance | 100% | TBD | Pending |
| Pen test findings (Critical/High) | 0 | TBD | Pending |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| WAF false positives | Medium | Medium | Tune rules in monitor mode first |
| Vault unavailability | Low | High | HA deployment with auto-unseal |
| Key rotation failure | Low | High | Automated rotation with monitoring |
| Zero-day vulnerability | Low | Critical | WAF + monitoring + rapid response |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from MS-142

- [x] Database migration completed
- [x] Data validation passed
- [x] Production data ready

### 7.2 Handoffs to MS-144

- [ ] Security hardening complete
- [ ] WAF rules validated
- [ ] Vault operational
- [ ] Penetration test passed
- [ ] Security sign-off obtained

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Security Lead | Dev 2 | | |
| Backend Lead | Dev 1 | | |
| Project Manager | | | |
| Client Representative | | | |

---

*Document Version: 1.0*
*Last Updated: 2026-02-05*
*Next Review: Upon completion of security testing*
