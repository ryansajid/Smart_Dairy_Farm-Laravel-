# SMART DAIRY LTD.
## DATA QUALITY STANDARDS & VALIDATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-012 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Data Quality Manager |
| **Owner** | Chief Data Officer |
| **Reviewer** | Data Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Data Quality Framework](#2-data-quality-framework)
3. [Data Quality Dimensions](#3-data-quality-dimensions)
4. [Domain-Specific Standards](#4-domain-specific-standards)
5. [Validation Rules](#5-validation-rules)
6. [Data Quality Processes](#6-data-quality-processes)
7. [Error Handling & Correction](#7-error-handling--correction)
8. [Monitoring & Reporting](#8-monitoring--reporting)
9. [Tools & Implementation](#9-tools--implementation)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document establishes the data quality standards and validation procedures for the Smart Dairy ERP system. It defines quality criteria, validation rules, and processes to ensure data accuracy, completeness, and consistency across all data domains.

### 1.2 Data Quality Vision

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA QUALITY VISION                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  "Data is a strategic asset that drives business decisions,     │
│   operational efficiency, and customer trust. Every data point  │
│   in our system meets the highest standards of accuracy,        │
│   completeness, and timeliness."                                │
│                                                                  │
│  GOALS:                                                         │
│  • 99.5% accuracy for all critical business data                │
│  • < 0.5% duplicate rate across all master data                 │
│  • 98% completeness for mandatory fields                        │
│  • Real-time data quality monitoring and alerting               │
│  • Automated data quality enforcement at point of entry         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Scope

| Domain | Data Types | Criticality |
|--------|------------|-------------|
| **Partner** | Customer, Supplier, Farmer data | Critical |
| **Product** | Product catalog, pricing | Critical |
| **Farm** | Animal records, production data | Critical |
| **Sales** | Orders, invoices | Critical |
| **Financial** | Transactions, accounting | Critical |
| **IoT** | Sensor readings, device data | High |
| **Inventory** | Stock levels, movements | High |

---

## 2. DATA QUALITY FRAMEWORK

### 2.1 Framework Components

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA QUALITY FRAMEWORK                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    GOVERNANCE LAYER                        │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │   │
│  │  │Data Quality  │  │Data Stewards │  │Issue Escal.  │    │   │
│  │  │Committee     │  │              │  │Process       │    │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘    │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  ┌───────────────────────────┴──────────────────────────────┐   │
│  │                    PROCESS LAYER                           │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │   │
│  │  │Validation    │  │Cleansing     │  │Enrichment    │    │   │
│  │  │Rules         │  │Procedures    │  │Services      │    │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘    │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  ┌───────────────────────────┴──────────────────────────────┐   │
│  │                    TECHNOLOGY LAYER                        │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │   │
│  │  │Constraints   │  │Great         │  │DQ Dashboard  │    │   │
│  │  │& Triggers    │  │Expectations  │  │& Alerts      │    │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘    │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Quality Levels

| Level | Definition | Data Examples | Tolerance |
|-------|------------|---------------|-----------|
| **Platinum** | 100% accuracy required | Financial transactions, animal IDs | 0 errors |
| **Gold** | 99.9% accuracy | Customer contact info, product data | < 0.1% errors |
| **Silver** | 99% accuracy | Sales orders, inventory records | < 1% errors |
| **Bronze** | 95% accuracy | Marketing data, analytics | < 5% errors |

---

## 3. DATA QUALITY DIMENSIONS

### 3.1 Dimension Definitions

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA QUALITY DIMENSIONS                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │  ACCURACY   │  │COMPLETENESS │  │ CONSISTENCY │             │
│  │             │  │             │  │             │             │
│  │ Data is     │  │ All required│  │ Data is     │             │
│  │ correct and │  │ fields are  │  │ uniform     │             │
│  │ represents  │  │ populated   │  │ across      │             │
│  │ reality     │  │             │  │ systems     │             │
│  │             │  │             │  │             │             │
│  │ Example:    │  │ Example:    │  │ Example:    │             │
│  │ Email       │  │ Mobile      │  │ Customer    │             │
│  │ validation  │  │ number      │  │ name same   │             │
│  │             │  │ present     │  │ in all apps │             │
│  └─────────────┘  └─────────────┘  └─────────────┘             │
│                                                                  │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐             │
│  │  VALIDITY   │  │ TIMELINESS  │  │ UNIQUENESS  │             │
│  │             │  │             │  │             │             │
│  │ Data follows│  │ Data is up- │  │ No duplicate│             │
│  │ business    │  │ to-date and │  │ records     │             │
│  │ rules       │  │ available   │  │ exist       │             │
│  │             │  │ when needed │  │             │             │
│  │             │  │             │  │             │             │
│  │ Example:    │  │ Example:    │  │ Example:    │             │
│  │ Credit limit│  │ Sensor data │  │ One record  │             │
│  │ > 0         │  │ < 5 min old │  │ per customer│             │
│  └─────────────┘  └─────────────┘  └─────────────┘             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Measuring Data Quality

```sql
-- Comprehensive data quality metrics view
CREATE VIEW data_quality.metrics_summary AS
WITH partner_metrics AS (
    SELECT
        'res_partner' as entity,
        COUNT(*) as total_records,
        
        -- Accuracy
        COUNT(*) FILTER (WHERE email ~ '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$') 
            / COUNT(*)::DECIMAL * 100 as email_accuracy,
        COUNT(*) FILTER (WHERE mobile ~ '^\+880[0-9]{10}$') 
            / COUNT(*)::DECIMAL * 100 as mobile_accuracy,
        
        -- Completeness
        COUNT(*) FILTER (WHERE name IS NOT NULL AND LENGTH(name) >= 2) 
            / COUNT(*)::DECIMAL * 100 as name_completeness,
        COUNT(*) FILTER (WHERE mobile IS NOT NULL) 
            / COUNT(*)::DECIMAL * 100 as mobile_completeness,
        
        -- Uniqueness
        (COUNT(*) - COUNT(DISTINCT mobile))::DECIMAL / COUNT(*) * 100 as mobile_duplicate_rate
        
    FROM core.res_partner
    WHERE active = TRUE
),
order_metrics AS (
    SELECT
        'sale_order' as entity,
        COUNT(*) as total_records,
        100.0 as accuracy,  -- Assume accurate if stored
        COUNT(*) FILTER (WHERE partner_id IS NOT NULL) 
            / COUNT(*)::DECIMAL * 100 as completeness,
        0.0 as duplicate_rate  -- Orders are unique by ID
    FROM sales.order
)
SELECT * FROM partner_metrics
UNION ALL
SELECT * FROM order_metrics;
```

---

## 4. DOMAIN-SPECIFIC STANDARDS

### 4.1 Partner Data Standards

| Attribute | Quality Level | Validation Rules | DQ Target |
|-----------|---------------|------------------|-----------|
| **Name** | Gold | Required, 2-100 chars, no special chars | 99.9% valid |
| **Email** | Gold | Valid format, unique, lowercase | 99% valid |
| **Mobile** | Platinum | BD format (+8801XXXXXXXXX), unique | 100% valid |
| **Customer Type** | Platinum | Enum: b2c, b2b, farmer, supplier | 100% valid |
| **Credit Limit** | Gold | Numeric, >= 0 | 99.9% valid |
| **Address** | Silver | Required for B2B | 95% valid |

### 4.2 Animal Data Standards

| Attribute | Quality Level | Validation Rules | DQ Target |
|-----------|---------------|------------------|-----------|
| **RFID Tag** | Platinum | Unique, valid EPC format | 100% unique |
| **Ear Tag** | Gold | Unique if provided | 99.5% valid |
| **Birth Date** | Gold | Not in future, < 25 years old | 99% valid |
| **Breed** | Gold | Valid reference to breed table | 100% valid |
| **Status** | Platinum | Enum: calf, heifer, lactating, etc. | 100% valid |
| **Gender** | Platinum | Enum: male, female | 100% valid |

### 4.3 Product Data Standards

| Attribute | Quality Level | Validation Rules | DQ Target |
|-----------|---------------|------------------|-----------|
| **SKU** | Platinum | Unique, format: XXX-XXX-XXX-NNN | 100% valid |
| **Name** | Gold | Required, 3-255 chars | 99.9% valid |
| **Category** | Platinum | Valid reference | 100% valid |
| **Price** | Platinum | Numeric, > 0 | 100% valid |
| **UOM** | Gold | Valid unit of measure | 99.9% valid |
| **Shelf Life** | Silver | Required for perishables | 95% valid |

---

## 5. VALIDATION RULES

### 5.1 Database Constraints

```sql
-- Core validation constraints

-- Partner constraints
ALTER TABLE core.res_partner ADD CONSTRAINT chk_partner_name 
    CHECK (name IS NOT NULL AND LENGTH(name) >= 2);

ALTER TABLE core.res_partner ADD CONSTRAINT chk_partner_email_format 
    CHECK (email IS NULL OR email ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$');

ALTER TABLE core.res_partner ADD CONSTRAINT chk_partner_mobile_format 
    CHECK (mobile IS NULL OR mobile ~ '^\+880[0-9]{10}$');

ALTER TABLE core.res_partner ADD CONSTRAINT chk_partner_credit_limit 
    CHECK (credit_limit IS NULL OR credit_limit >= 0);

ALTER TABLE core.res_partner ADD CONSTRAINT chk_partner_credit_usage 
    CHECK (credit_used <= credit_limit);

-- Animal constraints
ALTER TABLE farm.animal ADD CONSTRAINT chk_animal_rfid_unique 
    UNIQUE (rfid_tag);

ALTER TABLE farm.animal ADD CONSTRAINT chk_animal_birth_date 
    CHECK (birth_date IS NULL OR birth_date <= CURRENT_DATE);

ALTER TABLE farm.animal ADD CONSTRAINT chk_animal_age_reasonable 
    CHECK (birth_date IS NULL OR birth_date > CURRENT_DATE - INTERVAL '25 years');

ALTER TABLE farm.animal ADD CONSTRAINT chk_animal_status 
    CHECK (status IN ('calf', 'heifer', 'lactating', 'dry', 'pregnant', 'sold', 'deceased'));

-- Product constraints
ALTER TABLE product_template ADD CONSTRAINT chk_product_price 
    CHECK (list_price > 0);

ALTER TABLE product_template ADD CONSTRAINT chk_product_name 
    CHECK (name IS NOT NULL AND LENGTH(name) >= 3);
```

### 5.2 Application-Level Validation

```python
# Python validation using pydantic
from pydantic import BaseModel, validator, Field, EmailStr
from typing import Optional
import re

class PartnerValidator(BaseModel):
    """Validation model for partner data"""
    
    name: str = Field(..., min_length=2, max_length=255)
    email: Optional[EmailStr] = None
    mobile: Optional[str] = None
    customer_type: str = Field(..., regex='^(b2c|b2b|farmer|supplier)$')
    credit_limit: Optional[float] = Field(None, ge=0)
    
    @validator('mobile')
    def validate_bangladesh_mobile(cls, v):
        if v is None:
            return v
        
        # Remove any spaces or dashes
        v = v.replace(' ', '').replace('-', '')
        
        # Validate Bangladesh format
        pattern = r'^(\+880|880|01)[0-9]{9,10}$'
        if not re.match(pattern, v):
            raise ValueError('Invalid Bangladesh mobile number format')
        
        # Standardize to +880 format
        if v.startswith('01'):
            v = '+88' + v
        elif v.startswith('880'):
            v = '+' + v
        
        return v
    
    @validator('name')
    def validate_name_chars(cls, v):
        # No special characters except spaces, dots, hyphens
        if not re.match(r'^[\w\s\.\-]+$', v):
            raise ValueError('Name contains invalid characters')
        return v.strip()

class AnimalValidator(BaseModel):
    """Validation model for animal data"""
    
    rfid_tag: Optional[str] = Field(None, regex=r'^EPC-[0-9A-F]{24}$')
    ear_tag: str = Field(..., min_length=3, max_length=20)
    birth_date: Optional[str] = None
    gender: str = Field(..., regex='^(male|female)$')
    breed_id: int = Field(..., gt=0)
    status: str = Field(..., regex='^(calf|heifer|lactating|dry|pregnant|sold|deceased)$')
    
    @validator('birth_date')
    def validate_birth_date(cls, v):
        if v is None:
            return v
        
        from datetime import datetime, date
        birth = datetime.strptime(v, '%Y-%m-%d').date()
        
        # Not in future
        if birth > date.today():
            raise ValueError('Birth date cannot be in the future')
        
        # Not more than 25 years ago
        if birth < date.today().replace(year=date.today().year - 25):
            raise ValueError('Animal cannot be more than 25 years old')
        
        return v
```

### 5.3 Great Expectations Implementation

```python
# Great Expectations data quality suite
import great_expectations as gx
from great_expectations.expectations import ExpectationSuite

class SmartDairyExpectations:
    """Data quality expectations for Smart Dairy"""
    
    def create_partner_expectations(self):
        """Define expectations for partner data"""
        
        suite = ExpectationSuite(name="partner_expectations")
        
        # Completeness expectations
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToNotBeNull(
                column="name"
            )
        )
        
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToNotBeNull(
                column="customer_type"
            )
        )
        
        # Uniqueness expectations
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToBeUnique(
                column="email",
                mostly=0.99  # 99% unique (allowing for nulls)
            )
        )
        
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToBeUnique(
                column="mobile",
                mostly=0.99
            )
        )
        
        # Validity expectations
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToMatchRegex(
                column="mobile",
                regex=r"^\+880[0-9]{10}$",
                mostly=0.95
            )
        )
        
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToBeInSet(
                column="customer_type",
                value_set=["b2c", "b2b", "farmer", "supplier"]
            )
        )
        
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToBeBetween(
                column="credit_limit",
                min_value=0,
                mostly=0.99
            )
        )
        
        # Consistency expectations
        suite.add_expectation(
            gx.expectations.ExpectColumnPairValuesToBeEqual(
                column_A="credit_used",
                column_B="credit_limit",
                or_equal=True,
                condition_parser="pandas",
                row_condition='credit_used <= credit_limit'
            )
        )
        
        return suite
    
    def create_milk_production_expectations(self):
        """Define expectations for milk production data"""
        
        suite = ExpectationSuite(name="milk_production_expectations")
        
        # Range validations
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToBeBetween(
                column="quantity_liters",
                min_value=0.1,
                max_value=50.0
            )
        )
        
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToBeBetween(
                column="fat_percentage",
                min_value=0.0,
                max_value=15.0
            )
        )
        
        # Referential integrity
        suite.add_expectation(
            gx.expectations.ExpectColumnValuesToExistInSet(
                column="animal_id",
                set_=[1, 2, 3, 4, 5]  # Valid animal IDs
            )
        )
        
        return suite

# Run validation
def validate_data_quality(context, suite_name, batch_request):
    """Execute data quality validation"""
    
    validator = context.get_validator(
        batch_request=batch_request,
        expectation_suite_name=suite_name
    )
    
    results = validator.validate()
    
    # Generate report
    if not results.success:
        print(f"Data quality issues found in {suite_name}:")
        for result in results.results:
            if not result.success:
                print(f"  - {result.expectation_config.expectation_type}: FAILED")
    
    return results
```

---

## 6. DATA QUALITY PROCESSES

### 6.1 Data Entry Validation Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA ENTRY VALIDATION FLOW                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  USER INPUT                                                      │
│       │                                                          │
│       ▼                                                          │
│  ┌─────────────────┐                                             │
│  │ Client-Side     │  Format check, required fields              │
│  │ Validation      │  Immediate feedback                         │
│  └────────┬────────┘                                             │
│           │ Valid                                                 │
│           ▼                                                       │
│  ┌─────────────────┐                                             │
│  │ Application     │  Business rules, referential integrity      │
│  │ Validation      │  (Pydantic validators)                      │
│  └────────┬────────┘                                             │
│           │ Valid                                                 │
│           ▼                                                       │
│  ┌─────────────────┐                                             │
│  │ Database        │  Constraints, triggers                      │
│  │ Validation      │  Final enforcement                          │
│  └────────┬────────┘                                             │
│           │ Valid                                                 │
│           ▼                                                       │
│  DATA STORED                                                     │
│                                                                  │
│  If any validation fails:                                        │
│  ├── Return error message to user                               │
│  ├── Log validation failure                                     │
│  └── Suggest corrections                                        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 6.2 Data Quality Monitoring Process

```python
class DataQualityMonitor:
    """Continuous data quality monitoring"""
    
    def __init__(self, db_connection):
        self.db = db_connection
        self.alert_thresholds = {
            'completeness': 95.0,
            'accuracy': 99.0,
            'uniqueness': 99.5
        }
    
    def run_daily_checks(self):
        """Execute daily data quality checks"""
        
        checks = [
            self.check_partner_completeness(),
            self.check_partner_uniqueness(),
            self.check_animal_data_accuracy(),
            self.check_product_data_integrity(),
            self.check_orphaned_records()
        ]
        
        results = []
        for check in checks:
            result = check()
            results.append(result)
            
            # Alert if threshold breached
            if result['score'] < self.alert_thresholds.get(result['dimension'], 100):
                self.send_alert(result)
        
        # Generate daily report
        self.generate_report(results)
        
        return results
    
    def check_partner_completeness(self):
        """Check partner data completeness"""
        
        query = """
            SELECT 
                COUNT(*) as total,
                COUNT(*) FILTER (WHERE name IS NULL OR LENGTH(name) < 2) as missing_name,
                COUNT(*) FILTER (WHERE mobile IS NULL) as missing_mobile,
                COUNT(*) FILTER (WHERE customer_type IS NULL) as missing_type
            FROM core.res_partner
            WHERE active = TRUE
        """
        
        result = self.db.execute(query).fetchone()
        
        completeness = (
            (result.total - result.missing_name - result.missing_mobile - result.missing_type) 
            / (result.total * 3) * 100
        )
        
        return {
            'check': 'partner_completeness',
            'dimension': 'completeness',
            'score': completeness,
            'details': {
                'total_records': result.total,
                'missing_name': result.missing_name,
                'missing_mobile': result.missing_mobile
            }
        }
    
    def check_partner_uniqueness(self):
        """Check for duplicate partners"""
        
        query = """
            WITH duplicates AS (
                SELECT mobile, COUNT(*) as cnt
                FROM core.res_partner
                WHERE mobile IS NOT NULL AND active = TRUE
                GROUP BY mobile
                HAVING COUNT(*) > 1
            )
            SELECT 
                COUNT(*) as duplicate_count,
                SUM(cnt) as affected_records
            FROM duplicates
        """
        
        result = self.db.execute(query).fetchone()
        
        total_records = self.db.execute(
            "SELECT COUNT(*) FROM core.res_partner WHERE active = TRUE"
        ).scalar()
        
        uniqueness = (1 - result.affected_records / total_records) * 100
        
        return {
            'check': 'partner_uniqueness',
            'dimension': 'uniqueness',
            'score': uniqueness,
            'details': {
                'duplicate_mobiles': result.duplicate_count,
                'affected_records': result.affected_records
            }
        }
    
    def send_alert(self, check_result):
        """Send data quality alert"""
        
        alert_message = f"""
        Data Quality Alert: {check_result['check']}
        
        Dimension: {check_result['dimension']}
        Current Score: {check_result['score']:.2f}%
        Threshold: {self.alert_thresholds[check_result['dimension']]:.2f}%
        
        Details: {check_result['details']}
        
        Action Required: Review and remediate data quality issues.
        """
        
        # Send to Slack/Email
        self.notification_service.send(alert_message)
```

---

## 7. ERROR HANDLING & CORRECTION

### 7.1 Data Quality Exception Handling

```python
class DataQualityException(Exception):
    """Custom exception for data quality violations"""
    
    def __init__(self, field, value, rule, suggestion=None):
        self.field = field
        self.value = value
        self.rule = rule
        self.suggestion = suggestion
        super().__init__(f"Data quality violation in {field}: {rule}")

class DataQualityHandler:
    """Handle data quality errors"""
    
    def __init__(self):
        self.error_queue = []
    
    def validate_and_correct(self, data, corrections=None):
        """Validate data with auto-correction options"""
        
        errors = []
        corrected_data = data.copy()
        
        # Check each field
        for field, value in data.items():
            try:
                corrected_value = self.validate_field(field, value)
                corrected_data[field] = corrected_value
            except DataQualityException as e:
                if corrections and field in corrections:
                    # Apply suggested correction
                    corrected_data[field] = corrections[field]
                else:
                    errors.append(e)
        
        if errors:
            # Queue for manual review
            self.queue_for_review(data, errors)
            raise ValidationError(errors)
        
        return corrected_data
    
    def auto_correct(self, field, value):
        """Attempt automatic correction of common issues"""
        
        corrections = {
            'mobile': self.correct_mobile,
            'email': self.correct_email,
            'name': self.correct_name
        }
        
        corrector = corrections.get(field)
        if corrector:
            return corrector(value)
        
        return value
    
    def correct_mobile(self, value):
        """Auto-correct mobile number format"""
        
        # Remove spaces and dashes
        value = str(value).replace(' ', '').replace('-', '')
        
        # Add country code if missing
        if value.startswith('01'):
            return '+88' + value
        elif value.startswith('880'):
            return '+' + value
        
        return value
    
    def correct_email(self, value):
        """Auto-correct common email issues"""
        
        value = str(value).lower().strip()
        
        # Fix common typos
        corrections = {
            '@gmail.com': ['@gmail', '@gmail.co', '@gmial.com'],
            '@yahoo.com': ['@yahoo', '@yahoo.co'],
        }
        
        for correct, typos in corrections.items():
            for typo in typos:
                if value.endswith(typo):
                    return value.replace(typo, correct)
        
        return value
    
    def queue_for_review(self, data, errors):
        """Queue record for manual review"""
        
        review_record = {
            'data': data,
            'errors': [str(e) for e in errors],
            'queued_at': datetime.now(),
            'status': 'pending_review'
        }
        
        self.error_queue.append(review_record)
        
        # Save to database
        self.save_to_review_queue(review_record)
```

### 7.2 Data Cleansing Procedures

```sql
-- Partner data cleansing
CREATE OR REPLACE FUNCTION cleanse_partner_data()
RETURNS TABLE (
    partner_id BIGINT,
    field_name TEXT,
    old_value TEXT,
    new_value TEXT,
    correction_type TEXT
) AS $$
BEGIN
    -- Trim whitespace from names
    RETURN QUERY
    UPDATE core.res_partner
    SET name = TRIM(name)
    WHERE name != TRIM(name)
    RETURNING id, 'name'::TEXT, name, TRIM(name), 'trim_whitespace'::TEXT;
    
    -- Standardize email to lowercase
    RETURN QUERY
    UPDATE core.res_partner
    SET email = LOWER(TRIM(email))
    WHERE email != LOWER(TRIM(email))
    RETURNING id, 'email'::TEXT, email, LOWER(TRIM(email)), 'standardize_case'::TEXT;
    
    -- Correct mobile format
    RETURN QUERY
    UPDATE core.res_partner
    SET mobile = CASE 
        WHEN mobile ~ '^01' THEN REPLACE(mobile, ' ', '')
        WHEN mobile ~ '^880' THEN '+' || REPLACE(mobile, ' ', '')
        ELSE mobile
    END
    WHERE mobile ~ '^01' OR mobile ~ '^880'
    RETURNING id, 'mobile'::TEXT, mobile, 
        CASE 
            WHEN mobile ~ '^01' THEN REPLACE(mobile, ' ', '')
            WHEN mobile ~ '^880' THEN '+' || REPLACE(mobile, ' ', '')
            ELSE mobile
        END, 
        'format_correction'::TEXT;
END;
$$ LANGUAGE plpgsql;
```

---

## 8. MONITORING & REPORTING

### 8.1 Data Quality Dashboard

```sql
-- Data quality summary for dashboard
CREATE VIEW data_quality.dashboard_summary AS
WITH daily_scores AS (
    SELECT 
        CURRENT_DATE as date,
        'partner_accuracy' as metric,
        (
            SELECT COUNT(*) FILTER (WHERE email ~ '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$')
            / COUNT(*)::DECIMAL * 100
            FROM core.res_partner WHERE active = TRUE
        ) as score
    UNION ALL
    SELECT 
        CURRENT_DATE,
        'partner_completeness',
        (
            SELECT COUNT(*) FILTER (WHERE mobile IS NOT NULL AND name IS NOT NULL)
            / COUNT(*)::DECIMAL * 100
            FROM core.res_partner WHERE active = TRUE
        )
    UNION ALL
    SELECT 
        CURRENT_DATE,
        'animal_accuracy',
        (
            SELECT COUNT(*) FILTER (WHERE birth_date IS NOT NULL AND birth_date <= CURRENT_DATE)
            / COUNT(*)::DECIMAL * 100
            FROM farm.animal WHERE active = TRUE
        )
)
SELECT * FROM daily_scores;
```

### 8.2 Data Quality Scorecard

```python
def generate_monthly_scorecard():
    """Generate monthly data quality scorecard"""
    
    scorecard = {
        'period': 'January 2026',
        'overall_score': 0,
        'domains': []
    }
    
    domains = [
        {'name': 'Partner Data', 'weight': 30},
        {'name': 'Product Data', 'weight': 20},
        {'name': 'Farm Data', 'weight': 25},
        {'name': 'Sales Data', 'weight': 15},
        {'name': 'Financial Data', 'weight': 10}
    ]
    
    total_weighted_score = 0
    
    for domain in domains:
        metrics = calculate_domain_metrics(domain['name'])
        
        domain_score = (
            metrics['accuracy'] * 0.4 +
            metrics['completeness'] * 0.3 +
            metrics['consistency'] * 0.2 +
            metrics['timeliness'] * 0.1
        )
        
        scorecard['domains'].append({
            'name': domain['name'],
            'score': domain_score,
            'weight': domain['weight'],
            'metrics': metrics
        })
        
        total_weighted_score += domain_score * domain['weight']
    
    scorecard['overall_score'] = total_weighted_score / 100
    
    return scorecard
```

---

## 9. TOOLS & IMPLEMENTATION

### 9.1 Data Quality Stack

| Tool | Purpose | Implementation |
|------|---------|----------------|
| **PostgreSQL Constraints** | Database-level validation | CHECK, UNIQUE, NOT NULL |
| **Pydantic** | Application validation | Python models |
| **Great Expectations** | Data quality testing | Automated validation suites |
| **dbt tests** | Data transformation testing | SQL-based tests |
| **Grafana** | DQ monitoring | Dashboards and alerts |
| **Custom DQ Engine** | Business rules | Python service |

### 9.2 Implementation Checklist

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA QUALITY IMPLEMENTATION CHECKLIST         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  FOUNDATION                                                      │
│  [ ] Define data quality dimensions and metrics                 │
│  [ ] Establish quality levels (Platinum, Gold, Silver, Bronze)  │
│  [ ] Assign data stewards per domain                            │
│  [ ] Set up data quality monitoring infrastructure              │
│                                                                  │
│  DATABASE                                                        │
│  [ ] Implement constraints on all critical tables               │
│  [ ] Create validation triggers                                 │
│  [ ] Set up audit tables                                        │
│  [ ] Implement referential integrity checks                     │
│                                                                  │
│  APPLICATION                                                     │
│  [ ] Implement Pydantic validators                              │
│  [ ] Add client-side validation                                 │
│  [ ] Create error handling framework                            │
│  [ ] Build data correction UI                                   │
│                                                                  │
│  MONITORING                                                      │
│  [ ] Deploy Great Expectations                                  │
│  [ ] Create data quality dashboards                             │
│  [ ] Configure alerting rules                                   │
│  [ ] Set up automated reporting                                 │
│                                                                  │
│  PROCESS                                                         │
│  [ ] Define data entry procedures                               │
│  [ ] Establish exception handling workflow                      │
│  [ ] Create data cleansing schedules                            │
│  [ ] Train users on data quality standards                      │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 10. APPENDICES

### Appendix A: Data Quality Glossary

| Term | Definition |
|------|------------|
| **Completeness** | The proportion of stored data against the potential of "100% complete" |
| **Uniqueness** | Nothing will be recorded more than once based upon how that thing is identified |
| **Timeliness** | The degree to which data represent reality from the required point in time |
| **Validity** | Data are valid if it conforms to the syntax (format, type, range) of its definition |
| **Accuracy** | The degree to which data correctly describe the "real world" object |
| **Consistency** | The absence of difference when comparing two or more representations of a thing |

### Appendix B: Quick Reference - Validation Patterns

| Data Type | Pattern | Example |
|-----------|---------|---------|
| **BD Mobile** | `^\+8801[3-9][0-9]{8}$` | +8801712345678 |
| **Email** | `^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$` | user@domain.com |
| **Date (ISO)** | `^\d{4}-\d{2}-\d{2}$` | 2024-01-31 |
| **RFID (EPC)** | `^EPC-[0-9A-F]{24}$` | EPC-1234567890ABCDEF12345678 |
| **Currency** | `^\d+\.\d{2}$` | 1500.50 |
| **Postal Code** | `^\d{4}$` | 1200 |

### Appendix C: Data Quality Incident Template

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA QUALITY INCIDENT REPORT                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Incident ID: DQ-YYYY-NNNN      Date: _______________           │
│  Reported By: _____________________________________________     │
│  Domain: ___________________________________________________    │
│                                                                  │
│  ISSUE DESCRIPTION                                               │
│  ____________________________________________________________   │
│  ____________________________________________________________   │
│                                                                  │
│  AFFECTED DATA                                                   │
│  • Records: _______________________________________________     │
│  • Tables: ________________________________________________     │
│  • Time Period: ___________________________________________     │
│                                                                  │
│  ROOT CAUSE                                                      │
│  ____________________________________________________________   │
│                                                                  │
│  IMPACT ASSESSMENT                                               │
│  • Business Impact: High / Medium / Low                         │
│  • Data Integrity: Affected / Potentially Affected / None       │
│                                                                  │
│  CORRECTIVE ACTIONS                                              │
│  ____________________________________________________________   │
│                                                                  │
│  PREVENTIVE ACTIONS                                              │
│  ____________________________________________________________   │
│                                                                  │
│  STATUS: Open / In Progress / Resolved / Closed                 │
│                                                                  │
│  Closed By: ________________  Date: _____________               │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Data Quality Manager | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
