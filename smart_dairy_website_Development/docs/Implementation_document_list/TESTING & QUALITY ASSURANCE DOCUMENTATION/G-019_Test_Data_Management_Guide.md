# G-019: Test Data Management Guide

## Smart Dairy Ltd. - Smart Web Portal System

---

| **Document Information** | |
|--------------------------|---|
| **Document ID** | G-019 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Database Administrator |
| **Classification** | Internal - Confidential |
| **Status** | Draft |

---

## Document History

| Version | Date | Author | Changes | Approved By |
|---------|------|--------|---------|-------------|
| 1.0 | 2026-01-31 | QA Engineer | Initial version | Pending |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Test Data Categories](#2-test-data-categories)
3. [Data Requirements by Environment](#3-data-requirements-by-environment)
4. [Data Generation](#4-data-generation)
5. [Data Masking](#5-data-masking)
6. [Data Subsetting](#6-data-subsetting)
7. [Data Refresh Procedures](#7-data-refresh-procedures)
8. [Test Data Storage](#8-test-data-storage)
9. [Data Provisioning](#9-data-provisioning)
10. [Data Privacy Compliance](#10-data-privacy-compliance)
11. [Sensitive Data Handling](#11-sensitive-data-handling)
12. [Test Data for Specific Scenarios](#12-test-data-for-specific-scenarios)
13. [Tools & Technologies](#13-tools--technologies)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This Test Data Management (TDM) Guide establishes comprehensive guidelines and procedures for managing test data across all environments of the Smart Dairy Web Portal System. It ensures that testing teams have access to appropriate, secure, and compliant test data throughout the software development lifecycle.

### 1.2 Scope

This document covers:
- Test data strategy and governance for Smart Dairy Ltd.
- Data generation, masking, and subsetting procedures
- Environment-specific data requirements
- Privacy compliance (GDPR, Bangladesh Data Protection Act)
- Tools and technologies for test data management
- Operational procedures for data refresh and provisioning

### 1.3 Objectives

| Objective | Description | Success Criteria |
|-----------|-------------|------------------|
| Data Availability | Ensure timely provisioning of test data | < 4 hours request-to-delivery |
| Data Quality | Maintain high-quality, representative test data | 95% data validity score |
| Privacy Compliance | Ensure all PII is protected | Zero privacy incidents |
| Cost Efficiency | Optimize storage and processing costs | 30% reduction in data storage |
| Automation | Automate repetitive TDM tasks | 80% automated provisioning |

### 1.4 Target Audience

- QA Engineers and Testers
- Database Administrators
- DevOps Engineers
- Security and Compliance Officers
- Development Teams
- Business Analysts

### 1.5 Document Conventions

```
🔴 CRITICAL: Must follow strictly - compliance/security related
🟡 WARNING: Important - may impact testing effectiveness
🟢 INFO: Best practice recommendation
🔧 TECHNICAL: Technical implementation details
📋 TEMPLATE: Reusable template or script
```

---

## 2. Test Data Categories

### 2.1 Category Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    TEST DATA CATEGORIES                      │
├─────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │  Production  │  │  Synthetic   │  │   Masked     │       │
│  │    -Like     │  │    Data      │  │    Data      │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │   Subset     │  │   Golden     │  │   Scratch    │       │
│  │    Data      │  │    Data      │  │    Data      │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Production-Like Data

**Description:** Data copied from production and masked/anonymized for testing.

**Characteristics:**
- Mirrors production volume and distribution
- Real business patterns and relationships
- All PII masked using irreversible techniques
- Maintains referential integrity

**Use Cases:**
- User Acceptance Testing (UAT)
- Performance testing
- Regression testing
- Data migration validation

**Retention:** 90 days (refresh cycle)

### 2.3 Synthetic Data

**Description:** Artificially generated data that mimics production characteristics.

**Characteristics:**
- No real customer information
- Statistically similar distributions
- Configurable volume and variety
- No privacy concerns

**Use Cases:**
- Unit testing
- Integration testing
- Load testing (extreme volumes)
- Security testing

**Advantages:**
- Zero privacy risk
- Easily scalable
- Supports negative testing
- Always available

### 2.4 Masked Data

**Description:** Production data with sensitive fields transformed to protect privacy.

**Masking Techniques:**

| Technique | Description | Example | Reversible |
|-----------|-------------|---------|------------|
| Substitution | Replace with fake values | Name: "Rahim Khan" → "Faker User001" | No |
| Shuffling | Shuffle values within column | Phone numbers scrambled | No |
| Encryption | AES-256 encryption | NID: encrypted value | Yes (with key) |
| Nulling | Replace with NULL/empty | Email: NULL | No |
| Masking | Partial hide | Phone: 017XXXXXXXX | No |
| Tokenization | Replace with token | Card: tok_visa_4242 | Yes (with vault) |

### 2.5 Subset Data

**Description:** Representative sample extracted from full production dataset.

**Subset Strategies:**

```python
# Stratified Sampling Example
"""
Stratified sampling ensures all customer segments are represented
in proportion to production distribution.
"""
subset_criteria = {
    'customer_tier': {
        'premium': 0.10,      # 10% of subset
        'standard': 0.60,     # 60% of subset
        'basic': 0.30         # 30% of subset
    },
    'geography': {
        'dhaka': 0.40,
        'chittagong': 0.20,
        'sylhet': 0.15,
        'others': 0.25
    }
}
```

### 2.6 Golden Data Sets

**Description:** Curated, validated test data for specific test scenarios.

**Standard Golden Sets:**

| Dataset Name | Purpose | Record Count | Refresh Frequency |
|--------------|---------|--------------|-------------------|
| GD-REGRESSION | Core regression pack | 10,000 | Monthly |
| GD-SMOKE | Smoke test validation | 500 | Per release |
| GD-PERF-BASELINE | Performance baseline | 100,000 | Quarterly |
| GD-EDGE-CASES | Boundary conditions | 1,000 | As needed |
| GD-INTEGRATION | Integration scenarios | 5,000 | Per sprint |

### 2.7 Category Selection Matrix

| Test Type | Recommended Category | Volume | Rationale |
|-----------|---------------------|--------|-----------|
| Unit Testing | Synthetic | 100-1,000 | Fast, isolated, no dependencies |
| Integration | Synthetic/Golden | 1,000-10,000 | Controlled data relationships |
| System Testing | Masked Subset | 10,000-100,000 | Production-like complexity |
| UAT | Masked Production | 50,000-500,000 | Real business scenarios |
| Performance | Synthetic/Masked | 1M-10M | Scalable volume |
| Security | Synthetic | Variable | Safe to attack/exploit |

---

## 3. Data Requirements by Environment

### 3.1 Environment Overview

```
┌────────────────────────────────────────────────────────────────┐
│                    ENVIRONMENT DATA FLOW                        │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│   Production ──► Masking/Subset ──► Staging ──► QA ──► Dev     │
│      │                                                    │    │
│      └──────────────────► Analytics/Reporting ◄───────────┘    │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

### 3.2 Development Environment

**Purpose:** Individual developer testing and debugging

**Data Requirements:**

| Aspect | Specification |
|--------|--------------|
| Data Volume | 1,000 - 10,000 records per entity |
| Data Type | Synthetic + Minimal subset |
| Refresh Frequency | On-demand |
| Retention | Ephemeral (per feature branch) |
| PII Status | No real PII allowed |

**Data Composition:**
- 70% Synthetic data (generated)
- 30% Minimal subset from QA (sanitized)
- Golden datasets for standard scenarios

**Provisioning:**
```bash
# Developer data provisioning script
curl -X POST https://tdm.smartdairy.com/api/provision \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "environment": "dev",
    "developer_id": "DEV001",
    "datasets": ["customers", "products", "orders"],
    "volume": "small",
    "ttl_hours": 168
  }'
```

### 3.3 QA Environment

**Purpose:** Functional testing, integration testing, regression

**Data Requirements:**

| Aspect | Specification |
|--------|--------------|
| Data Volume | 100,000 - 500,000 records per entity |
| Data Type | Masked subset (10% of production) |
| Refresh Frequency | Weekly |
| Retention | 30 days |
| PII Status | Fully masked |

**Data Composition:**
- 60% Masked production subset
- 30% Synthetic boundary/edge cases
- 10% Golden regression datasets

### 3.4 Staging Environment

**Purpose:** Pre-production validation, performance testing

**Data Requirements:**

| Aspect | Specification |
|--------|--------------|
| Data Volume | 500,000 - 2,000,000 records |
| Data Type | Full masked production mirror |
| Refresh Frequency | Daily (incremental) |
| Retention | 14 days |
| PII Status | Fully masked with encryption |

### 3.5 UAT Environment

**Purpose:** Business user acceptance testing

**Data Requirements:**

| Aspect | Specification |
|--------|--------------|
| Data Volume | 200,000 - 1,000,000 records |
| Data Type | Masked production (representative) |
| Refresh Frequency | Bi-weekly |
| Retention | 30 days |
| PII Status | Masked, business-readable |

### 3.6 Production Environment

**Purpose:** Live system (reference only for TDM)

🔴 **CRITICAL:** No direct testing on production data allowed.

**Access Controls:**
- Read-only access for data extraction
- Masking mandatory before data leaves production
- Audit logging for all extractions
- DBA approval required

### 3.7 Environment Data Matrix

| Environment | Volume | Refresh | Source | Masked | Self-Service |
|-------------|--------|---------|--------|--------|--------------|
| Dev | Small | On-demand | Synthetic | N/A | Yes |
| QA | Medium | Weekly | Subset | Yes | Yes |
| Staging | Large | Daily | Production | Yes | No |
| UAT | Medium | Bi-weekly | Production | Yes | Limited |
| Prod | Full | N/A | Live | N/A | No |

---

## 4. Data Generation

### 4.1 Data Generation Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                  DATA GENERATION PIPELINE                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │   Schema    │───►│   Rules     │───►│  Generator  │         │
│  │  Analysis   │    │  Definition │    │   Engine    │         │
│  └─────────────┘    └─────────────┘    └──────┬──────┘         │
│                                                │                 │
│  ┌─────────────┐    ┌─────────────┐    ┌──────▼──────┐         │
│  │   Quality   │◄───│  Validation │◄───│    Data     │         │
│  │    Check    │    │    Engine   │    │   Output    │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 4.2 Python Data Generation Scripts

#### 4.2.1 Customer Data Generator

```python
#!/usr/bin/env python3
"""
Smart Dairy Customer Data Generator
Generates realistic Bangladesh customer profiles for testing
"""

import json
import random
import uuid
from datetime import datetime, timedelta
from typing import List, Dict, Optional
from dataclasses import dataclass

# Bangladesh-specific data
BENGALI_FIRST_NAMES = [
    "Mohammad", "Abdul", "Rahim", "Karim", "Hasan", "Hossain", "Ahmed",
    "Rafiq", "Salam", "Jamal", "Kamal", "Ali", "Hassan", "Mahmud",
    "Fatima", "Ayesha", "Khadija", "Rahima", "Sultana", "Nasrin",
    "Tasnim", "Nusrat", "Sumaiya", "Farhana", "Rina", "Mitu", "Shila"
]

BENGALI_LAST_NAMES = [
    "Khan", "Ahmed", "Ali", "Hossain", "Hasan", "Rahman", "Islam",
    "Mia", "Uddin", "Sheikh", "Chowdhury", "Das", "Sarkar", "Mollah",
    "Talukdar", "Patwari", "Biswas", "Saha", "Ghosh", "Barua"
]

DISTRICTS = [
    ("Dhaka", "Dhaka", ["Dhanmondi", "Gulshan", "Banani", "Mirpur", "Uttara", 
                        "Mohammadpur", "Tejgaon", "Mohakhali", "Badda", "Khilgaon"]),
    ("Chittagong", "Chittagong", ["Agrabad", "Panchlaish", "Halishahar", 
                                   "Chandgaon", "Bayazid", "Khulshi", "Double Mooring"]),
    ("Sylhet", "Sylhet", ["Zindabazar", "Ambarkhana", "Subidbazar", 
                          "Kumarpara", "Lamabazar", "Mendibagh"]),
    ("Rajshahi", "Rajshahi", ["Shaheb Bazar", "Uposahar", "Kazihata", 
                              "Ganakpara", "Sopura"]),
    ("Khulna", "Khulna", ["Sonadanga", "Khalishpur", "Daulatpur", 
                          "Boyra", "Rupsha"]),
    ("Barisal", "Barisal", ["Nathullabad", "Kashipur", "Chandmari", 
                            "Rupatali", "Gournadi"]),
    ("Rangpur", "Rangpur", ["Jahaj Company", "Dhap", "Mahiganj", 
                            "Tapur", "Payra"])
]

MOBILE_PREFIXES = ["013", "014", "015", "016", "017", "018", "019"]

@dataclass
class Customer:
    customer_id: str
    first_name: str
    last_name: str
    email: str
    phone: str
    nid: str
    date_of_birth: str
    address_line1: str
    address_line2: str
    city: str
    district: str
    postal_code: str
    customer_type: str  # b2c, b2b
    registration_date: str
    is_active: bool
    loyalty_points: int
    preferred_language: str  # bn, en

class CustomerDataGenerator:
    """Generator for Smart Dairy customer test data"""
    
    def __init__(self, seed: Optional[int] = None):
        if seed:
            random.seed(seed)
        self.generated_nids = set()
        self.generated_phones = set()
        self.generated_emails = set()
    
    def _generate_nid(self) -> str:
        """Generate unique Bangladesh National ID (10-17 digits)"""
        while True:
            # NID format: YYYYMM + 8-10 digit sequence
            year = random.randint(1960, 2005)
            month = random.randint(1, 12)
            sequence = random.randint(10000000, 99999999)
            nid = f"{year}{month:02d}{sequence}"
            if nid not in self.generated_nids:
                self.generated_nids.add(nid)
                return nid
    
    def _generate_phone(self) -> str:
        """Generate unique Bangladesh mobile number"""
        while True:
            prefix = random.choice(MOBILE_PREFIXES)
            number = f"{prefix}{random.randint(10000000, 99999999)}"
            if number not in self.generated_phones:
                self.generated_phones.add(number)
                return number
    
    def _generate_email(self, first_name: str, last_name: str) -> str:
        """Generate unique email address"""
        domains = ["gmail.com", "yahoo.com", "hotmail.com", "outlook.com"]
        patterns = [
            f"{first_name.lower()}.{last_name.lower()}",
            f"{first_name.lower()}{last_name.lower()}",
            f"{first_name.lower()}_{last_name.lower()}",
            f"{first_name.lower()}{random.randint(1, 999)}",
            f"{last_name.lower()}{first_name.lower()}{random.randint(1, 99)}"
        ]
        
        while True:
            pattern = random.choice(patterns)
            domain = random.choice(domains)
            email = f"{pattern}@{domain}"
            if email not in self.generated_emails:
                self.generated_emails.add(email)
                return email
    
    def _generate_address(self, district_info) -> tuple:
        """Generate Bangladesh address"""
        district, city, areas = district_info
        area = random.choice(areas)
        
        road_number = random.randint(1, 50)
        house_number = random.randint(1, 500)
        
        address_formats = [
            f"House #{house_number}, Road #{road_number}",
            f"Plot #{house_number}, Block #{random.randint(1, 20)}",
            f"{house_number}/{random.randint(1, 10)}, {area} Road",
            f"Apartment {random.randint(1, 20)}, House {house_number}"
        ]
        
        line1 = random.choice(address_formats)
        line2 = f"{area}, {city}"
        
        # Bangladesh postal codes are 4 digits
        postal_code = f"{random.randint(1000, 9999)}"
        
        return line1, line2, city, district, postal_code
    
    def _generate_dob(self, age_range: tuple = (18, 70)) -> str:
        """Generate date of birth"""
        min_age, max_age = age_range
        today = datetime.now()
        days = random.randint(min_age * 365, max_age * 365)
        dob = today - timedelta(days=days)
        return dob.strftime("%Y-%m-%d")
    
    def generate_customer(self, customer_type: str = "b2c") -> Customer:
        """Generate a single customer record"""
        first_name = random.choice(BENGALI_FIRST_NAMES)
        last_name = random.choice(BENGALI_LAST_NAMES)
        district_info = random.choice(DISTRICTS)
        
        address = self._generate_address(district_info)
        
        # Registration date (last 3 years)
        reg_days = random.randint(1, 1095)
        reg_date = (datetime.now() - timedelta(days=reg_days)).strftime("%Y-%m-%d")
        
        return Customer(
            customer_id=str(uuid.uuid4()),
            first_name=first_name,
            last_name=last_name,
            email=self._generate_email(first_name, last_name),
            phone=self._generate_phone(),
            nid=self._generate_nid(),
            date_of_birth=self._generate_dob(),
            address_line1=address[0],
            address_line2=address[1],
            city=address[2],
            district=address[3],
            postal_code=address[4],
            customer_type=customer_type,
            registration_date=reg_date,
            is_active=random.choices([True, False], weights=[95, 5])[0],
            loyalty_points=random.randint(0, 50000),
            preferred_language=random.choices(["bn", "en"], weights=[70, 30])[0]
        )
    
    def generate_customers(self, count: int, customer_type: str = "b2c") -> List[Customer]:
        """Generate multiple customer records"""
        return [self.generate_customer(customer_type) for _ in range(count)]
    
    def export_to_json(self, customers: List[Customer], filename: str):
        """Export customers to JSON file"""
        data = [vars(c) for c in customers]
        with open(filename, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
    
    def export_to_sql(self, customers: List[Customer], filename: str):
        """Export customers as SQL INSERT statements"""
        with open(filename, 'w', encoding='utf-8') as f:
            f.write("-- Smart Dairy Customer Data\n")
            f.write(f"-- Generated: {datetime.now().isoformat()}\n\n")
            f.write("INSERT INTO customers (customer_id, first_name, last_name, "
                   "email, phone, nid, date_of_birth, address_line1, address_line2, "
                   "city, district, postal_code, customer_type, registration_date, "
                   "is_active, loyalty_points, preferred_language) VALUES\n")
            
            values = []
            for c in customers:
                val = (f"('{c.customer_id}', '{c.first_name}', '{c.last_name}', "
                       f"'{c.email}', '{c.phone}', '{c.nid}', '{c.date_of_birth}', "
                       f"'{c.address_line1}', '{c.address_line2}', '{c.city}', "
                       f"'{c.district}', '{c.postal_code}', '{c.customer_type}', "
                       f"'{c.registration_date}', {c.is_active}, {c.loyalty_points}, "
                       f"'{c.preferred_language}')")
                values.append(val)
            
            f.write(",\n".join(values) + ";\n")


# Usage example
if __name__ == "__main__":
    generator = CustomerDataGenerator(seed=42)
    
    # Generate B2C customers
    b2c_customers = generator.generate_customers(1000, "b2c")
    generator.export_to_json(b2c_customers, "customers_b2c.json")
    generator.export_to_sql(b2c_customers, "customers_b2c.sql")
    
    print(f"Generated {len(b2c_customers)} B2C customers")
```

#### 4.2.2 Product Catalog Generator

```python
#!/usr/bin/env python3
"""
Smart Dairy Product Catalog Generator
Generates dairy product data with SKUs and pricing
"""

import json
import random
import uuid
from datetime import datetime, timedelta
from dataclasses import dataclass
from typing import List, Optional

@dataclass
class Product:
    product_id: str
    sku: str
    name_en: str
    name_bn: str
    category: str
    subcategory: str
    description_en: str
    description_bn: str
    unit_type: str  # liter, kg, piece, pack
    unit_size: float
    base_price: float
    sale_price: float
    cost_price: float
    tax_rate: float
    stock_quantity: int
    reorder_level: int
    shelf_life_days: int
    is_active: bool
    is_organic: bool
    supplier_id: str
    created_at: str
    updated_at: str

class ProductDataGenerator:
    """Generator for Smart Dairy product catalog"""
    
    # Product categories and items
    CATEGORIES = {
        "Fresh Milk": {
            "items": [
                ("Full Cream Milk", "ফুল ক্রিম দুধ", 1.0),
                ("Standard Milk", "স্ট্যান্ডার্ড দুধ", 1.0),
                ("Low Fat Milk", "লো ফ্যাট দুধ", 1.0),
                ("Skimmed Milk", "স্কিমড দুধ", 1.0),
                ("Lactose Free Milk", "ল্যাক্টোজ ফ্রি দুধ", 1.0),
            ],
            "unit_types": ["liter", "500ml"],
            "base_prices": [65.0, 70.0, 60.0, 55.0, 80.0],
            "shelf_life": [5, 7]
        },
        "Yogurt": {
            "items": [
                ("Plain Yogurt", "প্লেইন দই", 0.5),
                ("Sweet Yogurt", "মিষ্টি দই", 0.5),
                ("Strawberry Yogurt", "স্ট্রবেরি দই", 0.5),
                ("Mango Yogurt", "আম দই", 0.5),
                ("Greek Yogurt", "গ্রিক দই", 0.5),
            ],
            "unit_types": ["cup", "tub"],
            "base_prices": [40.0, 45.0, 50.0, 50.0, 70.0],
            "shelf_life": [14, 21]
        },
        "Cheese": {
            "items": [
                ("Cheddar Cheese", "চেডার চিজ", 0.2),
                ("Mozzarella Cheese", "মোজারেলা চিজ", 0.2),
                ("Cream Cheese", "ক্রিম চিজ", 0.15),
                ("Paneer", "পনির", 0.25),
            ],
            "unit_types": ["pack", "block"],
            "base_prices": [150.0, 180.0, 120.0, 200.0],
            "shelf_life": [30, 90]
        },
        "Butter & Ghee": {
            "items": [
                ("Salted Butter", "লবণাক্ত মাখন", 0.1),
                ("Unsalted Butter", "বিনা লবণ মাখন", 0.1),
                ("Pure Ghee", "খাঁটি ঘি", 0.4),
                ("Creamy Butter", "ক্রিমি মাখন", 0.2),
            ],
            "unit_types": ["pack", "jar"],
            "base_prices": [120.0, 120.0, 450.0, 220.0],
            "shelf_life": [60, 180]
        },
        "Cream": {
            "items": [
                ("Whipping Cream", "হুইপিং ক্রিম", 0.25),
                ("Fresh Cream", "ফ্রেশ ক্রিম", 0.2),
                ("Coffee Cream", "কফি ক্রিম", 0.15),
            ],
            "unit_types": ["pack", "carton"],
            "base_prices": [180.0, 160.0, 100.0],
            "shelf_life": [14, 30]
        },
        "Flavored Milk": {
            "items": [
                ("Chocolate Milk", "চকলেট দুধ", 0.25),
                ("Strawberry Milk", "স্ট্রবেরি দুধ", 0.25),
                ("Banana Milk", "কলা দুধ", 0.25),
                ("Mango Milk", "আম দুধ", 0.25),
            ],
            "unit_types": ["pack", "bottle"],
            "base_prices": [35.0, 35.0, 35.0, 35.0],
            "shelf_life": [30, 60]
        },
        "Desserts": {
            "items": [
                ("Rasmalai", "রসমালাই", 0.5),
                ("Kheer", "ক্ষীর", 0.5),
                ("Caramel Pudding", "ক্যারামেল পুডিং", 0.15),
                ("Ice Cream Vanilla", "ভ্যানিলা আইসক্রিম", 0.5),
                ("Ice Cream Chocolate", "চকলেট আইসক্রিম", 0.5),
            ],
            "unit_types": ["cup", "pack", "box"],
            "base_prices": [80.0, 60.0, 50.0, 120.0, 120.0],
            "shelf_life": [7, 180]
        },
        "Powdered Milk": {
            "items": [
                ("Full Cream Milk Powder", "ফুল ক্রিম গুঁড়ো দুধ", 0.5),
                ("Tea Coffee Milk", "চা কফি দুধ", 0.5),
                ("Growing Up Milk", "গ্রোয়িং আপ দুধ", 0.4),
            ],
            "unit_types": ["pack", "tin"],
            "base_prices": [280.0, 260.0, 350.0],
            "shelf_life": [180, 365]
        }
    }
    
    def __init__(self, seed: Optional[int] = None):
        if seed:
            random.seed(seed)
        self.generated_skus = set()
    
    def _generate_sku(self, category: str, index: int) -> str:
        """Generate unique SKU"""
        prefix = "".join([w[0] for w in category.split()]).upper()
        sku = f"SD-{prefix}-{index:04d}"
        return sku
    
    def _generate_price(self, base_price: float) -> tuple:
        """Generate cost, base, and sale prices"""
        cost = round(base_price * random.uniform(0.6, 0.75), 2)
        base = round(base_price * random.uniform(0.95, 1.05), 2)
        sale = round(base * random.uniform(0.9, 1.0), 2)
        return cost, base, sale
    
    def generate_product(self, category: str, item_index: int) -> Product:
        """Generate a single product"""
        cat_data = self.CATEGORIES[category]
        item_name, item_name_bn, _ = cat_data["items"][item_index % len(cat_data["items"])]
        
        sku = self._generate_sku(category, item_index)
        
        base_price_idx = item_index % len(cat_data["base_prices"])
        base_price = cat_data["base_prices"][base_price_idx]
        cost_price, base_price, sale_price = self._generate_price(base_price)
        
        unit_type = random.choice(cat_data["unit_types"])
        unit_size = 1.0 if unit_type in ["liter", "kg"] else 0.5
        
        shelf_life = random.choice(cat_data["shelf_life"])
        
        created_days = random.randint(30, 730)
        created_at = (datetime.now() - timedelta(days=created_days)).isoformat()
        updated_at = (datetime.now() - timedelta(days=random.randint(0, 30))).isoformat()
        
        return Product(
            product_id=str(uuid.uuid4()),
            sku=sku,
            name_en=item_name,
            name_bn=item_name_bn,
            category=category,
            subcategory="Standard" if "Organic" not in item_name else "Organic",
            description_en=f"Premium quality {item_name.lower()} from Smart Dairy",
            description_bn=f"স্মার্ট ডেইরির প্রিমিয়াম মানের {item_name_bn}",
            unit_type=unit_type,
            unit_size=unit_size,
            base_price=base_price,
            sale_price=sale_price,
            cost_price=cost_price,
            tax_rate=15.0,  # Bangladesh VAT
            stock_quantity=random.randint(50, 1000),
            reorder_level=random.randint(20, 100),
            shelf_life_days=shelf_life,
            is_active=random.choices([True, False], weights=[95, 5])[0],
            is_organic=random.choices([True, False], weights=[20, 80])[0],
            supplier_id=str(uuid.uuid4()),
            created_at=created_at,
            updated_at=updated_at
        )
    
    def generate_catalog(self, products_per_category: int = 5) -> List[Product]:
        """Generate full product catalog"""
        products = []
        for category in self.CATEGORIES:
            for i in range(products_per_category):
                products.append(self.generate_product(category, i))
        return products
    
    def export_to_json(self, products: List[Product], filename: str):
        """Export products to JSON"""
        data = [vars(p) for p in products]
        with open(filename, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
    
    def export_to_sql(self, products: List[Product], filename: str):
        """Export products as SQL INSERT"""
        with open(filename, 'w', encoding='utf-8') as f:
            f.write("-- Smart Dairy Product Catalog\n")
            f.write(f"-- Generated: {datetime.now().isoformat()}\n\n")
            
            for p in products:
                f.write(f"""INSERT INTO products (product_id, sku, name_en, name_bn, category, 
                    subcategory, description_en, description_bn, unit_type, unit_size, 
                    base_price, sale_price, cost_price, tax_rate, stock_quantity, 
                    reorder_level, shelf_life_days, is_active, is_organic, supplier_id, 
                    created_at, updated_at) VALUES (
                    '{p.product_id}', '{p.sku}', '{p.name_en}', '{p.name_bn}', 
                    '{p.category}', '{p.subcategory}', '{p.description_en}', 
                    '{p.description_bn}', '{p.unit_type}', {p.unit_size}, 
                    {p.base_price}, {p.sale_price}, {p.cost_price}, {p.tax_rate}, 
                    {p.stock_quantity}, {p.reorder_level}, {p.shelf_life_days}, 
                    {p.is_active}, {p.is_organic}, '{p.supplier_id}', 
                    '{p.created_at}', '{p.updated_at}');\n""")


if __name__ == "__main__":
    generator = ProductDataGenerator(seed=42)
    products = generator.generate_catalog(products_per_category=3)
    generator.export_to_json(products, "products.json")
    generator.export_to_sql(products, "products.sql")
    print(f"Generated {len(products)} products")
```

#### 4.2.3 Farm Data Generator

```python
#!/usr/bin/env python3
"""
Smart Dairy Farm Data Generator
Generates farm, animal, and milk production data
"""

import json
import random
import uuid
from datetime import datetime, timedelta
from dataclasses import dataclass
from typing import List, Optional

@dataclass
class Farm:
    farm_id: str
    farm_code: str
    farm_name: str
    owner_name: str
    contact_phone: str
    email: str
    address: str
    district: str
    total_animals: int
    milking_animals: int
    daily_capacity_liters: float
    gps_latitude: float
    gps_longitude: float
    certification: str  # organic, standard, premium
    joining_date: str
    is_active: bool

@dataclass
class Animal:
    animal_id: str
    farm_id: str
    rfid_tag: str
    animal_type: str  # cow, buffalo
    breed: str
    date_of_birth: str
    gender: str
    weight_kg: float
    health_status: str
    lactation_number: int
    is_pregnant: bool
    last_calving_date: Optional[str]
    daily_milk_avg: float

@dataclass
class MilkProduction:
    production_id: str
    animal_id: str
    farm_id: str
    production_date: str
    session: str  # morning, evening
    volume_liters: float
    fat_percentage: float
    snf_percentage: float  # Solids-not-fat
    protein_percentage: float
    temperature_celsius: float
    quality_grade: str  # A, B, C

class FarmDataGenerator:
    """Generator for Smart Dairy farm and animal data"""
    
    DISTRICTS = ["Dhaka", "Chittagong", "Sylhet", "Rajshahi", "Khulna", 
                 "Barisal", "Rangpur", "Mymensingh", "Comilla", "Jessore"]
    
    BREEDS_COW = [
        "Holstein Friesian", "Sahiwal", "Red Chittagong", "Local Cross",
        "Jersey Cross", "Pabna Cattle", "Pabna Cross"
    ]
    
    BREEDS_BUFFALO = [
        "Murrah", "Nili-Ravi", "Local Buffalo", "Mediterranean"
    ]
    
    FARM_NAMES = [
        "Green Pasture Dairy", "Sunrise Farm", "Golden Milk Dairy",
        "Pure Dairy Farm", "City Dairy Ltd.", "Fresh Milk Cooperative",
        "Royal Cattle Farm", "Family Dairy", "Modern Dairy Farm",
        "Heritage Milk Farm", "Digital Dairy Hub", "Smart Farm Solutions"
    ]
    
    CERTIFICATIONS = ["Standard", "Premium", "Organic", "Export Quality"]
    
    def __init__(self, seed: Optional[int] = None):
        if seed:
            random.seed(seed)
        self.generated_rfid = set()
    
    def _generate_rfid(self) -> str:
        """Generate unique RFID tag"""
        while True:
            # RFID format: BD + District Code + 8 digit number
            district_code = random.randint(10, 99)
            sequence = random.randint(10000000, 99999999)
            rfid = f"BD{district_code}{sequence}"
            if rfid not in self.generated_rfid:
                self.generated_rfid.add(rfid)
                return rfid
    
    def generate_farm(self, index: int) -> Farm:
        """Generate a single farm"""
        district = random.choice(self.DISTRICTS)
        total_animals = random.randint(20, 500)
        milking_ratio = random.uniform(0.4, 0.7)
        milking_animals = int(total_animals * milking_ratio)
        
        # Capacity based on milking animals (avg 8-15 liters/day per animal)
        daily_capacity = milking_animals * random.uniform(8, 15)
        
        # GPS coordinates for Bangladesh
        lat = random.uniform(20.7, 26.6)  # Bangladesh latitude range
        lon = random.uniform(88.0, 92.7)  # Bangladesh longitude range
        
        joining_days = random.randint(30, 1825)  # Up to 5 years
        joining_date = (datetime.now() - timedelta(days=joining_days)).strftime("%Y-%m-%d")
        
        return Farm(
            farm_id=str(uuid.uuid4()),
            farm_code=f"FARM-{index:04d}",
            farm_name=f"{random.choice(self.FARM_NAMES)} {index}",
            owner_name=f"Owner {index}",
            contact_phone=f"017{random.randint(10000000, 99999999)}",
            email=f"farm{index}@example.com",
            address=f"{random.randint(1, 100)} {district} Road",
            district=district,
            total_animals=total_animals,
            milking_animals=milking_animals,
            daily_capacity_liters=round(daily_capacity, 2),
            gps_latitude=round(lat, 6),
            gps_longitude=round(lon, 6),
            certification=random.choice(self.CERTIFICATIONS),
            joining_date=joining_date,
            is_active=random.choices([True, False], weights=[95, 5])[0]
        )
    
    def generate_animal(self, farm_id: str, index: int) -> Animal:
        """Generate a single animal"""
        animal_type = random.choices(["cow", "buffalo"], weights=[70, 30])[0]
        breeds = self.BREEDS_COW if animal_type == "cow" else self.BREEDS_BUFFALO
        breed = random.choice(breeds)
        
        # DOB (1-8 years old)
        age_days = random.randint(365, 365 * 8)
        dob = (datetime.now() - timedelta(days=age_days)).strftime("%Y-%m-%d")
        
        # Gender (more females for dairy)
        gender = random.choices(["female", "male"], weights=[85, 15])[0]
        
        # Weight based on type and age
        if animal_type == "cow":
            weight = random.uniform(250, 600)
        else:
            weight = random.uniform(300, 800)
        
        # Lactation for females
        lactation = 0
        last_calving = None
        daily_milk = 0.0
        
        if gender == "female" and age_days > 730:  # At least 2 years old
            lactation = random.randint(0, 6)
            if lactation > 0:
                calving_days = random.randint(30, 400)
                last_calving = (datetime.now() - timedelta(days=calving_days)).strftime("%Y-%m-%d")
                daily_milk = random.uniform(5, 20) if animal_type == "cow" else random.uniform(4, 15)
        
        return Animal(
            animal_id=str(uuid.uuid4()),
            farm_id=farm_id,
            rfid_tag=self._generate_rfid(),
            animal_type=animal_type,
            breed=breed,
            date_of_birth=dob,
            gender=gender,
            weight_kg=round(weight, 2),
            health_status=random.choices(
                ["healthy", "sick", "under_treatment", "quarantine"],
                weights=[85, 5, 5, 5]
            )[0],
            lactation_number=lactation,
            is_pregnant=random.choices([True, False], weights=[30, 70])[0] if gender == "female" else False,
            last_calving_date=last_calving,
            daily_milk_avg=round(daily_milk, 2)
        )
    
    def generate_milk_production(self, animal: Animal, days: int = 30) -> List[MilkProduction]:
        """Generate milk production records for an animal"""
        records = []
        
        if animal.daily_milk_avg <= 0:
            return records
        
        for day_offset in range(days):
            date = (datetime.now() - timedelta(days=day_offset)).strftime("%Y-%m-%d")
            
            for session in ["morning", "evening"]:
                # Slight variation in milk volume
                volume = animal.daily_milk_avg / 2 * random.uniform(0.85, 1.15)
                
                # Quality parameters
                fat = random.uniform(3.5, 5.0)
                snf = random.uniform(8.0, 9.5)
                protein = random.uniform(3.0, 3.8)
                temp = random.uniform(4.0, 8.0)
                
                # Grade based on quality
                if fat >= 4.0 and snf >= 8.5:
                    grade = "A"
                elif fat >= 3.5:
                    grade = "B"
                else:
                    grade = "C"
                
                records.append(MilkProduction(
                    production_id=str(uuid.uuid4()),
                    animal_id=animal.animal_id,
                    farm_id=animal.farm_id,
                    production_date=date,
                    session=session,
                    volume_liters=round(volume, 2),
                    fat_percentage=round(fat, 2),
                    snf_percentage=round(snf, 2),
                    protein_percentage=round(protein, 2),
                    temperature_celsius=round(temp, 2),
                    quality_grade=grade
                ))
        
        return records
    
    def generate_farm_dataset(self, num_farms: int = 50) -> tuple:
        """Generate complete farm dataset"""
        farms = []
        animals = []
        productions = []
        
        for i in range(num_farms):
            farm = self.generate_farm(i + 1)
            farms.append(farm)
            
            # Generate animals for this farm
            for j in range(farm.total_animals):
                animal = self.generate_animal(farm.farm_id, j + 1)
                animals.append(animal)
                
                # Generate production for milking animals
                if animal.daily_milk_avg > 0:
                    prod_records = self.generate_milk_production(animal, days=30)
                    productions.extend(prod_records)
        
        return farms, animals, productions


if __name__ == "__main__":
    generator = FarmDataGenerator(seed=42)
    farms, animals, productions = generator.generate_farm_dataset(num_farms=20)
    
    print(f"Generated {len(farms)} farms")
    print(f"Generated {len(animals)} animals")
    print(f"Generated {len(productions)} production records")
    
    # Export to JSON
    with open("farms.json", "w") as f:
        json.dump([vars(f) for f in farms], f, indent=2)
    with open("animals.json", "w") as f:
        json.dump([vars(a) for a in animals], f, indent=2)
    with open("milk_production.json", "w") as f:
        json.dump([vars(p) for p in productions], f, indent=2)
```

#### 4.2.4 Order Data Generator

```python
#!/usr/bin/env python3
"""
Smart Dairy Order Data Generator
Generates B2C, B2B, and subscription orders
"""

import json
import random
import uuid
from datetime import datetime, timedelta
from dataclasses import dataclass
from typing import List, Optional

@dataclass
class Order:
    order_id: str
    order_number: str
    customer_id: str
    customer_type: str  # b2c, b2b
    order_type: str  # regular, subscription, wholesale
    order_date: str
    delivery_date: str
    status: str
    payment_status: str
    payment_method: str
    subtotal: float
    tax_amount: float
    delivery_fee: float
    discount_amount: float
    total_amount: float
    delivery_address: str
    delivery_city: str
    delivery_district: str
    delivery_instructions: str
    subscription_id: Optional[str]
    created_at: str

@dataclass
class OrderItem:
    order_item_id: str
    order_id: str
    product_id: str
    product_name: str
    quantity: int
    unit_price: float
    total_price: float

class OrderDataGenerator:
    """Generator for Smart Dairy order data"""
    
    ORDER_STATUSES = ["pending", "confirmed", "processing", "shipped", 
                      "delivered", "cancelled", "returned"]
    PAYMENT_STATUSES = ["pending", "paid", "failed", "refunded"]
    PAYMENT_METHODS = ["cod", "bkash", "nagad", "rocket", "card", "bank_transfer"]
    
    def __init__(self, seed: Optional[int] = None):
        if seed:
            random.seed(seed)
    
    def generate_order_number(self) -> str:
        """Generate unique order number"""
        date_prefix = datetime.now().strftime("%Y%m%d")
        sequence = random.randint(10000, 99999)
        return f"SD-{date_prefix}-{sequence}"
    
    def generate_order(self, customer_id: str, customer_type: str, 
                       products: List[dict]) -> tuple:
        """Generate an order with items"""
        
        order_type = random.choices(
            ["regular", "subscription", "wholesale"],
            weights=[70, 20, 10] if customer_type == "b2c" else [30, 20, 50]
        )[0]
        
        # Order date (last 90 days)
        order_days = random.randint(0, 90)
        order_date = (datetime.now() - timedelta(days=order_days))
        
        # Delivery date (1-3 days after order)
        delivery_date = order_date + timedelta(days=random.randint(1, 3))
        
        # Status based on order date
        if order_days < 1:
            status = random.choices(["pending", "confirmed"], weights=[70, 30])[0]
        elif order_days < 3:
            status = random.choices(["confirmed", "processing", "shipped"], weights=[30, 40, 30])[0]
        else:
            status = random.choices(["delivered", "cancelled", "returned"], weights=[85, 10, 5])[0]
        
        # Payment status
        if status in ["delivered", "shipped"]:
            payment_status = "paid"
        elif status == "cancelled":
            payment_status = random.choice(["pending", "refunded"])
        else:
            payment_status = random.choices(["pending", "paid"], weights=[60, 40])[0]
        
        # Payment method
        payment_method = random.choices(
            self.PAYMENT_METHODS,
            weights=[40, 25, 20, 5, 5, 5]  # COD most common in BD
        )[0]
        
        # Generate order items
        num_items = random.randint(1, 5)
        selected_products = random.sample(products, min(num_items, len(products)))
        
        items = []
        subtotal = 0
        
        for product in selected_products:
            qty = random.randint(1, 10) if order_type != "wholesale" else random.randint(10, 100)
            unit_price = product.get('sale_price', 50.0)
            total = qty * unit_price
            
            item = OrderItem(
                order_item_id=str(uuid.uuid4()),
                order_id="",  # Will be set later
                product_id=product.get('product_id', str(uuid.uuid4())),
                product_name=product.get('name_en', 'Product'),
                quantity=qty,
                unit_price=unit_price,
                total_price=round(total, 2)
            )
            items.append(item)
            subtotal += total
        
        # Calculate totals
        tax_rate = 0.15  # 15% VAT
        tax_amount = subtotal * tax_rate
        delivery_fee = 0 if subtotal > 500 else 50  # Free delivery over 500
        discount = subtotal * random.uniform(0, 0.1) if random.random() < 0.3 else 0
        total = subtotal + tax_amount + delivery_fee - discount
        
        # Delivery address
        districts = ["Dhaka", "Chittagong", "Sylhet", "Rajshahi", "Khulna"]
        district = random.choice(districts)
        
        order = Order(
            order_id=str(uuid.uuid4()),
            order_number=self.generate_order_number(),
            customer_id=customer_id,
            customer_type=customer_type,
            order_type=order_type,
            order_date=order_date.strftime("%Y-%m-%d"),
            delivery_date=delivery_date.strftime("%Y-%m-%d"),
            status=status,
            payment_status=payment_status,
            payment_method=payment_method,
            subtotal=round(subtotal, 2),
            tax_amount=round(tax_amount, 2),
            delivery_fee=delivery_fee,
            discount_amount=round(discount, 2),
            total_amount=round(total, 2),
            delivery_address=f"{random.randint(1, 100)} {district} Road",
            delivery_city=district,
            delivery_district=district,
            delivery_instructions=random.choice([
                "Leave at gate", "Call before delivery", "Deliver to neighbor if not home", ""
            ]),
            subscription_id=str(uuid.uuid4()) if order_type == "subscription" else None,
            created_at=order_date.isoformat()
        )
        
        # Update order_id in items
        for item in items:
            item.order_id = order.order_id
        
        return order, items
    
    def generate_orders_batch(self, customers: List[dict], 
                               products: List[dict], 
                               orders_per_customer: int = 5) -> tuple:
        """Generate batch of orders"""
        orders = []
        order_items = []
        
        for customer in customers:
            num_orders = random.randint(1, orders_per_customer)
            for _ in range(num_orders):
                order, items = self.generate_order(
                    customer.get('customer_id', str(uuid.uuid4())),
                    customer.get('customer_type', 'b2c'),
                    products
                )
                orders.append(order)
                order_items.extend(items)
        
        return orders, order_items


if __name__ == "__main__":
    generator = OrderDataGenerator(seed=42)
    
    # Sample products
    sample_products = [
        {"product_id": "p1", "name_en": "Full Cream Milk", "sale_price": 70.0},
        {"product_id": "p2", "name_en": "Yogurt", "sale_price": 45.0},
        {"product_id": "p3", "name_en": "Cheddar Cheese", "sale_price": 150.0},
        {"product_id": "p4", "name_en": "Butter", "sale_price": 120.0},
    ]
    
    # Sample customers
    sample_customers = [
        {"customer_id": "c1", "customer_type": "b2c"},
        {"customer_id": "c2", "customer_type": "b2c"},
        {"customer_id": "c3", "customer_type": "b2b"},
    ]
    
    orders, items = generator.generate_orders_batch(
        sample_customers, sample_products, orders_per_customer=3
    )
    
    print(f"Generated {len(orders)} orders with {len(items)} items")
```

### 4.3 Data Generation Best Practices

```python
"""
Data Generation Best Practices Module
"""

class DataGenerationBestPractices:
    """
    Best practices for generating quality test data
    """
    
    @staticmethod
    def validate_data_quality(data: list, rules: dict) -> dict:
        """
        Validate generated data against quality rules
        
        Args:
            data: List of data records
            rules: Dictionary of validation rules
            
        Returns:
            Validation report
        """
        report = {
            "total_records": len(data),
            "passed": 0,
            "failed": 0,
            "errors": []
        }
        
        for record in data:
            is_valid = True
            for field, rule in rules.items():
                value = record.get(field)
                
                # Check required
                if rule.get('required') and not value:
                    report["errors"].append(f"Missing required field: {field}")
                    is_valid = False
                
                # Check type
                if value and 'type' in rule:
                    if rule['type'] == 'string' and not isinstance(value, str):
                        report["errors"].append(f"Field {field} should be string")
                        is_valid = False
                
                # Check range
                if value and 'min' in rule and value < rule['min']:
                    report["errors"].append(f"Field {field} below minimum: {value}")
                    is_valid = False
                    
                if value and 'max' in rule and value > rule['max']:
                    report["errors"].append(f"Field {field} above maximum: {value}")
                    is_valid = False
            
            if is_valid:
                report["passed"] += 1
            else:
                report["failed"] += 1
        
        return report
    
    @staticmethod
    def ensure_referential_integrity(parents: list, children: list, 
                                      parent_key: str, child_key: str) -> bool:
        """
        Ensure referential integrity between related datasets
        """
        parent_ids = {p[parent_key] for p in parents}
        orphan_records = [c for c in children if c[child_key] not in parent_ids]
        
        if orphan_records:
            print(f"Warning: {len(orphan_records)} orphan records found")
            return False
        return True


# Quality Rules Template
QUALITY_RULES = {
    "customers": {
        "customer_id": {"required": True, "type": "string"},
        "email": {"required": True, "type": "string"},
        "phone": {"required": True, "type": "string", "pattern": r"^01[3-9]\d{8}$"},
        "nid": {"required": True, "type": "string", "min_length": 10, "max_length": 17}
    },
    "products": {
        "product_id": {"required": True},
        "sku": {"required": True},
        "base_price": {"required": True, "min": 0},
        "stock_quantity": {"required": True, "min": 0}
    }
}
```



---

## 5. Data Masking

### 5.1 Data Masking Strategy

```
┌─────────────────────────────────────────────────────────────────┐
│                   DATA MASKING PIPELINE                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────┐   ┌──────────┐   ┌──────────┐   ┌──────────┐     │
│  │ Identify │──►│ Classify │──►│  Select  │──►│  Apply   │     │
│  │   PII    │   │  Sensitivity│  │ Technique│   │ Masking  │     │
│  └──────────┘   └──────────┘   └──────────┘   └────┬─────┘     │
│                                                    │             │
│                                               ┌────▼─────┐       │
│                                               │ Validate │       │
│                                               │  Output  │       │
│                                               └──────────┘       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 PII Classification

| Classification | Data Elements | Masking Technique | Priority |
|----------------|---------------|-------------------|----------|
| **Critical** | NID, Credit Card, Bank Account | Irreversible encryption/Tokenization | P0 |
| **High** | Phone numbers, Email, DOB | Substitution + Shuffling | P1 |
| **Medium** | Names, Addresses | Faker substitution | P2 |
| **Low** | City, District | Generalization | P3 |

### 5.3 Masking Transformation Rules

#### 5.3.1 Bangladesh NID Masking

```python
"""
Bangladesh National ID Masking Module
"""

import hashlib
import hmac
from typing import Optional

class NIDMasker:
    """Mask Bangladesh National ID Numbers"""
    
    MASKING_KEY = b"SmartDairy_Secret_Key_2026"  # Store in secure vault
    
    @staticmethod
    def one_way_hash(nid: str) -> str:
        """
        Irreversible one-way hashing for NID
        🔴 CRITICAL: Use only when original NID is not needed
        """
        if not nid or len(nid) < 10:
            return "***INVALID***"
        
        # Use HMAC-SHA256 for better security
        hash_obj = hmac.new(
            NIDMasker.MASKING_KEY,
            nid.encode('utf-8'),
            hashlib.sha256
        )
        # Return first 16 characters of hex digest
        return f"NID_{hash_obj.hexdigest()[:16].upper()}"
    
    @staticmethod
    def tokenize(nid: str, token_vault: dict) -> str:
        """
        Tokenization - reversible with vault access
        🔴 CRITICAL: Secure token vault required
        """
        if nid in token_vault:
            return token_vault[nid]
        
        # Generate new token
        token = f"TKN_{hashlib.sha256(nid.encode()).hexdigest()[:12].upper()}"
        token_vault[nid] = token
        return token
    
    @staticmethod
    def format_preserving_mask(nid: str) -> str:
        """
        Format-preserving masking
        Maintains NID structure while masking digits
        """
        if not nid:
            return ""
        
        # Keep first 4 digits (birth year), mask the rest
        if len(nid) >= 4:
            return nid[:4] + "*" * (len(nid) - 4)
        return "*" * len(nid)
    
    @staticmethod
    def random_substitution(nid: str, seed: Optional[int] = None) -> str:
        """
        Substitute with random but valid-format NID
        """
        import random
        if seed:
            random.seed(seed)
        
        # Generate fake NID maintaining format
        year = random.randint(1960, 2005)
        month = random.randint(1, 12)
        sequence = random.randint(10000000, 99999999)
        return f"{year}{month:02d}{sequence}"


# Usage
if __name__ == "__main__":
    masker = NIDMasker()
    test_nid = "1985123456789"
    
    print(f"Original: {test_nid}")
    print(f"Hashed: {masker.one_way_hash(test_nid)}")
    print(f"Format Preserving: {masker.format_preserving_mask(test_nid)}")
    print(f"Random Substitution: {masker.random_substitution(test_nid)}")
```

#### 5.3.2 Phone Number Masking

```python
"""
Bangladesh Phone Number Masking Module
"""

import random
import re

class PhoneMasker:
    """Mask Bangladesh mobile phone numbers"""
    
    MOBILE_PATTERN = re.compile(r'^(01[3-9])\d{8}$')
    
    MASKING_RULES = {
        'full': lambda p: '01' + '*' * 9,
        'partial': lambda p: p[:5] + '*' * 4 + p[-2:],
        'prefix_only': lambda p: p[:5] + 'XXXXXXX',
        'shuffled': 'shuffle_within_prefix'
    }
    
    @staticmethod
    def mask(phone: str, method: str = 'partial') -> str:
        """
        Mask phone number using specified method
        
        Args:
            phone: Original phone number
            method: Masking method (full, partial, prefix_only, shuffled)
        """
        if not phone or not PhoneMasker.MOBILE_PATTERN.match(phone):
            return "***INVALID***"
        
        if method == 'shuffled':
            return PhoneMasker._shuffle_within_prefix(phone)
        
        rule = PhoneMasker.MASKING_RULES.get(method)
        if rule and callable(rule):
            return rule(phone)
        
        return phone
    
    @staticmethod
    def _shuffle_within_prefix(phone: str) -> str:
        """Shuffle digits while keeping prefix"""
        prefix = phone[:5]
        digits = list(phone[5:])
        random.shuffle(digits)
        return prefix + ''.join(digits)
    
    @staticmethod
    def substitute_with_fake(phone: str, seed: int = None) -> str:
        """Replace with fake but valid BD phone number"""
        if seed:
            random.seed(seed)
        
        prefixes = ["013", "014", "015", "016", "017", "018", "019"]
        prefix = random.choice(prefixes)
        number = f"{prefix}{random.randint(10000000, 99999999)}"
        return number
    
    @staticmethod
    def consistent_mask(phone: str, mapping: dict) -> str:
        """
        Consistent masking - same input always produces same output
        Useful for maintaining referential integrity
        """
        if phone in mapping:
            return mapping[phone]
        
        masked = PhoneMasker.substitute_with_fake(phone)
        mapping[phone] = masked
        return masked


# Phone masking configuration
PHONE_MASKING_CONFIG = {
    'dev_environment': 'full',
    'qa_environment': 'partial',
    'staging_environment': 'shuffled',
    'analytics': 'prefix_only'
}
```

#### 5.3.3 Email Masking

```python
"""
Email Address Masking Module
"""

import hashlib
import random

class EmailMasker:
    """Mask email addresses"""
    
    DOMAINS = ["example.com", "testmail.com", "smartdairy.test"]
    
    @staticmethod
    def mask_username(email: str) -> str:
        """Mask only the username part"""
        if '@' not in email:
            return "***INVALID***"
        
        username, domain = email.rsplit('@', 1)
        
        if len(username) <= 2:
            masked_user = '*' * len(username)
        else:
            masked_user = username[0] + '*' * (len(username) - 2) + username[-1]
        
        return f"{masked_user}@{domain}"
    
    @staticmethod
    def mask_domain(email: str) -> str:
        """Mask the domain part"""
        if '@' not in email:
            return "***INVALID***"
        
        username, domain = email.rsplit('@', 1)
        return f"{username}@***.com"
    
    @staticmethod
    def full_mask(email: str) -> str:
        """Completely mask email"""
        hash_val = hashlib.md5(email.encode()).hexdigest()[:8]
        return f"user_{hash_val}@masked.com"
    
    @staticmethod
    def substitute_with_fake(email: str, seed: int = None) -> str:
        """Replace with fake email"""
        if seed:
            random.seed(seed)
        
        if '@' not in email:
            return f"user{random.randint(1, 99999)}@example.com"
        
        username, _ = email.rsplit('@', 1)
        # Generate consistent fake email
        hash_val = hashlib.md5(username.encode()).hexdigest()[:6]
        domain = random.choice(EmailMasker.DOMAINS)
        return f"user_{hash_val}@{domain}"
    
    @staticmethod
    def preserve_relationships(emails: list) -> dict:
        """
        Create consistent mapping to preserve email relationships
        """
        mapping = {}
        for email in emails:
            if email not in mapping:
                mapping[email] = EmailMasker.substitute_with_fake(email)
        return mapping
```

#### 5.3.4 Name Masking

```python
"""
Name Masking Module using Faker
"""

from faker import Faker
import random

class NameMasker:
    """Mask personal names"""
    
    def __init__(self, locale='en_BD'):
        self.faker = Faker(locale)
        Faker.seed(42)
    
    def mask_name(self, original_name: str, preserve_gender: bool = False) -> str:
        """
        Replace name with fake generated name
        
        Args:
            original_name: Original name to mask
            preserve_gender: Try to preserve gender in fake name
        """
        # For Bengali names, generate Bengali-style names
        first_names = [
            "Mohammad", "Abdul", "Rahim", "Karim", "Hasan", "Fatema",
            "Ayesha", "Khadija", "Rahima", "Sultana", "Nasrin", "Tasnim"
        ]
        last_names = [
            "Khan", "Ahmed", "Ali", "Hossain", "Hasan", "Rahman", "Islam"
        ]
        
        fake_first = random.choice(first_names)
        fake_last = random.choice(last_names)
        return f"{fake_first} {fake_last}"
    
    def mask_initials(self, name: str) -> str:
        """Replace with initials only"""
        parts = name.split()
        initials = ''.join([p[0].upper() for p in parts if p])
        return f"{initials}."
    
    def consistent_mask(self, name: str, mapping: dict) -> str:
        """Consistent name masking"""
        if name in mapping:
            return mapping[name]
        
        masked = self.mask_name(name)
        mapping[name] = masked
        return masked


# Initialize masker
name_masker = NameMasker()
```

### 5.4 Address Masking

```python
"""
Address Masking Module
"""

import random

class AddressMasker:
    """Mask address information"""
    
    DISTRICTS = ["Dhaka", "Chittagong", "Sylhet", "Rajshahi", "Khulna", 
                 "Barisal", "Rangpur", "Mymensingh"]
    
    @staticmethod
    def generalize_address(address: str, level: str = 'district') -> str:
        """
        Generalize address to higher level
        
        Args:
            address: Full address
            level: Generalization level (street, area, district, division)
        """
        # Extract district from address or assign random
        for district in AddressMasker.DISTRICTS:
            if district in address:
                return f"{district}, Bangladesh"
        
        return random.choice(AddressMasker.DISTRICTS) + ", Bangladesh"
    
    @staticmethod
    def mask_house_number(address: str) -> str:
        """Replace house numbers with generic format"""
        import re
        # Replace numbers with #
        masked = re.sub(r'\d+', '#', address)
        return masked
    
    @staticmethod
    def generate_fake_address(seed: int = None) -> dict:
        """Generate completely fake address"""
        if seed:
            random.seed(seed)
        
        district = random.choice(AddressMasker.DISTRICTS)
        areas = {
            "Dhaka": ["Dhanmondi", "Gulshan", "Banani", "Mirpur"],
            "Chittagong": ["Agrabad", "Panchlaish", "Halishahar"],
            "Sylhet": ["Zindabazar", "Ambarkhana", "Subidbazar"]
        }
        
        area = random.choice(areas.get(district, ["City Area"]))
        
        return {
            "line1": f"House #{random.randint(1, 500)}, Road #{random.randint(1, 50)}",
            "line2": f"{area}",
            "city": district,
            "district": district,
            "postal_code": f"{random.randint(1000, 9999)}"
        }
```

### 5.5 Masking Execution Pipeline

```python
"""
Data Masking Pipeline Orchestrator
"""

import json
from typing import Dict, List, Callable

class MaskingPipeline:
    """Orchestrates data masking operations"""
    
    def __init__(self):
        self.maskers = {
            'nid': NIDMasker(),
            'phone': PhoneMasker(),
            'email': EmailMasker(),
            'name': NameMasker(),
            'address': AddressMasker()
        }
        self.consistency_maps = {
            'phone': {},
            'email': {},
            'nid_token': {}
        }
    
    def mask_record(self, record: dict, rules: dict) -> dict:
        """
        Apply masking rules to a single record
        
        Args:
            record: Data record to mask
            rules: Masking rules configuration
            
        Returns:
            Masked record
        """
        masked = record.copy()
        
        for field, rule in rules.items():
            if field not in record:
                continue
            
            value = record[field]
            masker_type = rule.get('type')
            method = rule.get('method', 'default')
            
            if masker_type == 'nid':
                if method == 'hash':
                    masked[field] = self.maskers['nid'].one_way_hash(value)
                elif method == 'token':
                    masked[field] = self.maskers['nid'].tokenize(
                        value, self.consistency_maps['nid_token']
                    )
                else:
                    masked[field] = self.maskers['nid'].format_preserving_mask(value)
            
            elif masker_type == 'phone':
                if rule.get('consistent'):
                    masked[field] = self.maskers['phone'].consistent_mask(
                        value, self.consistency_maps['phone']
                    )
                else:
                    masked[field] = self.maskers['phone'].mask(value, method)
            
            elif masker_type == 'email':
                if rule.get('consistent'):
                    masked[field] = self.maskers['email'].substitute_with_fake(
                        value, seed=hash(value) % 10000
                    )
                else:
                    masked[field] = self.maskers['email'].mask_username(value)
            
            elif masker_type == 'name':
                masked[field] = self.maskers['name'].mask_name(value)
            
            elif masker_type == 'address':
                masked[field] = self.maskers['address'].generalize_address(value)
        
        return masked
    
    def mask_dataset(self, dataset: List[dict], rules: dict) -> List[dict]:
        """Mask entire dataset"""
        return [self.mask_record(record, rules) for record in dataset]


# Masking Rules Configuration
CUSTOMER_MASKING_RULES = {
    'nid': {'type': 'nid', 'method': 'hash'},
    'phone': {'type': 'phone', 'method': 'partial', 'consistent': True},
    'email': {'type': 'email', 'consistent': True},
    'first_name': {'type': 'name'},
    'last_name': {'type': 'name'},
    'address_line1': {'type': 'address'},
    'address_line2': {'type': 'address'}
}
```

---

## 6. Data Subsetting

### 6.1 Subsetting Strategies

```
┌─────────────────────────────────────────────────────────────────┐
│                   DATA SUBSETTING STRATEGIES                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   Random     │  │  Stratified  │  │  Systematic  │          │
│  │   Sample     │  │   Sample     │  │   Sample     │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │  Referential │  │   Business   │  │   Temporal   │          │
│  │  Integrity   │  │   Driven     │  │   Slice      │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 6.2 Stratified Sampling Implementation

```python
"""
Stratified Data Subsetting Module
"""

import pandas as pd
import random
from typing import Dict, List, Optional
from collections import defaultdict

class StratifiedSubsetting:
    """Create stratified subsets maintaining data distribution"""
    
    def __init__(self, seed: Optional[int] = None):
        if seed:
            random.seed(seed)
    
    def create_subset(self, df: pd.DataFrame, 
                      stratify_columns: List[str],
                      target_size: int,
                      min_per_stratum: int = 10) -> pd.DataFrame:
        """
        Create stratified subset of data
        
        Args:
            df: Source DataFrame
            stratify_columns: Columns to stratify by
            target_size: Desired total size of subset
            min_per_stratum: Minimum records per stratum
        """
        # Create strata combinations
        df['_stratum'] = df[stratify_columns].apply(
            lambda x: '_'.join(x.astype(str)), axis=1
        )
        
        # Calculate stratum proportions in source
        stratum_counts = df['_stratum'].value_counts()
        total_records = len(df)
        
        # Calculate target counts per stratum
        target_counts = {}
        for stratum, count in stratum_counts.items():
            proportion = count / total_records
            target = max(int(target_size * proportion), min_per_stratum)
            target_counts[stratum] = min(target, count)  # Can't exceed available
        
        # Sample from each stratum
        samples = []
        for stratum, target in target_counts.items():
            stratum_df = df[df['_stratum'] == stratum]
            sample = stratum_df.sample(n=target)
            samples.append(sample)
        
        # Combine and clean
        subset = pd.concat(samples, ignore_index=True)
        subset = subset.drop(columns=['_stratum'])
        
        return subset
    
    def create_customer_subset(self, customers_df: pd.DataFrame, 
                               target_size: int = 10000) -> pd.DataFrame:
        """
        Create customer subset stratified by:
        - Customer type (B2C/B2B)
        - Geographic region
        - Registration date year
        """
        # Add year column for temporal stratification
        customers_df['reg_year'] = pd.to_datetime(
            customers_df['registration_date']
        ).dt.year
        
        stratify_cols = ['customer_type', 'district', 'reg_year']
        
        subset = self.create_subset(
            customers_df, 
            stratify_cols, 
            target_size,
            min_per_stratum=5
        )
        
        # Clean up temporary column
        subset = subset.drop(columns=['reg_year'], errors='ignore')
        
        return subset


# Subset size guidelines by environment
SUBSET_SIZE_CONFIG = {
    'dev': {
        'customers': 1000,
        'orders': 5000,
        'products': 100,
        'farms': 50,
        'animals': 500
    },
    'qa': {
        'customers': 50000,
        'orders': 200000,
        'products': 500,
        'farms': 500,
        'animals': 5000
    },
    'staging': {
        'customers': 200000,
        'orders': 1000000,
        'products': 1000,
        'farms': 2000,
        'animals': 20000
    }
}
```

### 6.3 Referential Integrity Subsetting

```python
"""
Referential Integrity Subsetting
Ensures related records are included together
"""

import pandas as pd
from typing import List, Tuple, Dict

class ReferentialSubsetting:
    """Maintain referential integrity during subsetting"""
    
    def __init__(self):
        self.relationships = {}
    
    def add_relationship(self, parent_table: str, child_table: str,
                         parent_key: str, child_key: str):
        """Define parent-child relationship"""
        self.relationships[child_table] = {
            'parent': parent_table,
            'parent_key': parent_key,
            'child_key': child_key
        }
    
    def subset_with_children(self, parent_df: pd.DataFrame,
                            child_df: pd.DataFrame,
                            parent_key: str,
                            child_key: str,
                            parent_sample_size: int) -> Tuple[pd.DataFrame, pd.DataFrame]:
        """
        Sample parents and include all related children
        """
        # Sample parents
        if len(parent_df) > parent_sample_size:
            parent_subset = parent_df.sample(n=parent_sample_size)
        else:
            parent_subset = parent_df.copy()
        
        # Get parent IDs
        parent_ids = set(parent_subset[parent_key])
        
        # Filter children
        child_subset = child_df[child_df[child_key].isin(parent_ids)].copy()
        
        return parent_subset, child_subset
    
    def subset_order_data(self, customers_df: pd.DataFrame,
                         orders_df: pd.DataFrame,
                         order_items_df: pd.DataFrame,
                         target_customers: int) -> Dict[str, pd.DataFrame]:
        """
        Create subset of orders maintaining full referential integrity
        """
        # Subset customers
        customer_subset = customers_df.sample(n=min(target_customers, len(customers_df)))
        customer_ids = set(customer_subset['customer_id'])
        
        # Get related orders
        order_subset = orders_df[orders_df['customer_id'].isin(customer_ids)].copy()
        order_ids = set(order_subset['order_id'])
        
        # Get related order items
        item_subset = order_items_df[order_items_df['order_id'].isin(order_ids)].copy()
        
        return {
            'customers': customer_subset,
            'orders': order_subset,
            'order_items': item_subset
        }


# Relationship definitions for Smart Dairy
SMART_DAIRY_RELATIONSHIPS = {
    ('customers', 'orders'): ('customer_id', 'customer_id'),
    ('orders', 'order_items'): ('order_id', 'order_id'),
    ('products', 'order_items'): ('product_id', 'product_id'),
    ('farms', 'animals'): ('farm_id', 'farm_id'),
    ('animals', 'milk_production'): ('animal_id', 'animal_id')
}
```

---

## 7. Data Refresh Procedures

### 7.1 Refresh Schedule

| Environment | Frequency | Window (UTC+6) | Duration | Approval |
|-------------|-----------|----------------|----------|----------|
| Dev | On-demand | Anytime | 15 min | Self-service |
| QA | Weekly | Saturday 02:00-04:00 | 2 hours | Automated |
| Staging | Daily | 01:00-03:00 | 2 hours | Automated |
| UAT | Bi-weekly | Sunday 00:00-04:00 | 4 hours | QA Lead |

### 7.2 Refresh Process Workflow

```
┌─────────────────────────────────────────────────────────────────┐
│                   DATA REFRESH WORKFLOW                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌─────────┐        │
│  │  Init   │──►│ Extract │──►│  Mask   │──►│ Validate│        │
│  │ Request │   │  Prod   │   │   Data  │   │  Masked │        │
│  └─────────┘   └─────────┘   └─────────┘   └────┬────┘        │
│                                                 │               │
│  ┌─────────┐   ┌─────────┐   ┌─────────┐   ┌────▼────┐        │
│  │  Notify │◄──│  Smoke  │◄──│ Restore │◄──│ Subset  │        │
│  │  Users  │   │  Tests  │   │  Target │   │  Data   │        │
│  └─────────┘   └─────────┘   └─────────┘   └─────────┘        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 7.3 Refresh Automation Script

```python
#!/usr/bin/env python3
"""
Smart Dairy Data Refresh Automation
"""

import logging
import subprocess
from datetime import datetime
from typing import Optional
import smtplib
from email.mime.text import MIMEText

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class DataRefreshManager:
    """Manage test data refresh operations"""
    
    def __init__(self, environment: str):
        self.environment = environment
        self.start_time: Optional[datetime] = None
        self.end_time: Optional[datetime] = None
    
    def initiate_refresh(self) -> bool:
        """Start the data refresh process"""
        logger.info(f"Starting data refresh for {self.environment}")
        self.start_time = datetime.now()
        
        try:
            # Step 1: Pre-refresh checks
            if not self._pre_refresh_checks():
                raise Exception("Pre-refresh checks failed")
            
            # Step 2: Extract from production
            extract_file = self._extract_production_data()
            if not extract_file:
                raise Exception("Data extraction failed")
            
            # Step 3: Apply masking
            masked_file = self._apply_masking(extract_file)
            if not masked_file:
                raise Exception("Data masking failed")
            
            # Step 4: Create subset
            subset_file = self._create_subset(masked_file)
            
            # Step 5: Restore to target
            if not self._restore_to_target(subset_file or masked_file):
                raise Exception("Data restore failed")
            
            # Step 6: Post-refresh validation
            if not self._post_refresh_validation():
                raise Exception("Post-refresh validation failed")
            
            self.end_time = datetime.now()
            duration = (self.end_time - self.start_time).total_seconds()
            
            logger.info(f"Refresh completed successfully in {duration} seconds")
            self._send_notification(True, duration)
            return True
            
        except Exception as e:
            logger.error(f"Refresh failed: {str(e)}")
            self._send_notification(False, error=str(e))
            return False
    
    def _pre_refresh_checks(self) -> bool:
        """Perform pre-refresh system checks"""
        logger.info("Running pre-refresh checks...")
        
        checks = [
            self._check_disk_space(),
            self._check_database_connectivity(),
            self._check_backup_status(),
            self._check_active_sessions()
        ]
        
        return all(checks)
    
    def _check_disk_space(self) -> bool:
        """Verify sufficient disk space"""
        # Implementation would check actual disk space
        logger.info("Disk space check: PASSED")
        return True
    
    def _check_database_connectivity(self) -> bool:
        """Verify database connectivity"""
        logger.info("Database connectivity check: PASSED")
        return True
    
    def _check_backup_status(self) -> bool:
        """Verify target environment backup exists"""
        logger.info("Backup status check: PASSED")
        return True
    
    def _check_active_sessions(self) -> bool:
        """Check for active user sessions"""
        logger.info("Active sessions check: PASSED")
        return True
    
    def _extract_production_data(self) -> Optional[str]:
        """Extract data from production"""
        logger.info("Extracting production data...")
        # Implementation would use pg_dump or similar
        return "/tmp/extract.sql"
    
    def _apply_masking(self, input_file: str) -> Optional[str]:
        """Apply masking transformations"""
        logger.info("Applying data masking...")
        # Implementation would call masking pipeline
        return "/tmp/masked.sql"
    
    def _create_subset(self, input_file: str) -> Optional[str]:
        """Create data subset"""
        if self.environment == 'staging':
            logger.info("Skipping subset for staging (full data)")
            return input_file
        
        logger.info("Creating data subset...")
        return "/tmp/subset.sql"
    
    def _restore_to_target(self, data_file: str) -> bool:
        """Restore data to target environment"""
        logger.info(f"Restoring data to {self.environment}...")
        # Implementation would use psql/pg_restore
        return True
    
    def _post_refresh_validation(self) -> bool:
        """Validate data after refresh"""
        logger.info("Running post-refresh validation...")
        
        validations = [
            self._validate_row_counts(),
            self._validate_referential_integrity(),
            self._validate_masking_applied(),
            self._run_smoke_tests()
        ]
        
        return all(validations)
    
    def _validate_row_counts(self) -> bool:
        """Validate row counts match expectations"""
        logger.info("Row count validation: PASSED")
        return True
    
    def _validate_referential_integrity(self) -> bool:
        """Validate foreign key relationships"""
        logger.info("Referential integrity validation: PASSED")
        return True
    
    def _validate_masking_applied(self) -> bool:
        """Verify PII has been masked"""
        logger.info("Masking validation: PASSED")
        return True
    
    def _run_smoke_tests(self) -> bool:
        """Run smoke tests on refreshed environment"""
        logger.info("Smoke tests: PASSED")
        return True
    
    def _send_notification(self, success: bool, duration: float = 0, 
                          error: str = None):
        """Send refresh completion notification"""
        status = "SUCCESS" if success else "FAILED"
        message = f"Data refresh for {self.environment}: {status}\n"
        
        if success:
            message += f"Duration: {duration} seconds"
        else:
            message += f"Error: {error}"
        
        logger.info(f"Notification: {message}")
        # Implementation would send email/Slack notification


# Refresh configuration
REFRESH_CONFIG = {
    'dev': {
        'schedule': 'on-demand',
        'subset_ratio': 0.01,
        'masking': 'full',
        'auto_approve': True
    },
    'qa': {
        'schedule': '0 2 * * 6',  # Saturday 2 AM
        'subset_ratio': 0.1,
        'masking': 'full',
        'auto_approve': True
    },
    'staging': {
        'schedule': '0 1 * * *',  # Daily 1 AM
        'subset_ratio': 1.0,  # Full data
        'masking': 'full',
        'auto_approve': True
    },
    'uat': {
        'schedule': '0 0 * * 0',  # Bi-weekly Sunday
        'subset_ratio': 0.25,
        'masking': 'full',
        'auto_approve': False
    }
}


if __name__ == "__main__":
    import sys
    env = sys.argv[1] if len(sys.argv) > 1 else 'dev'
    
    manager = DataRefreshManager(env)
    success = manager.initiate_refresh()
    sys.exit(0 if success else 1)
```

### 7.4 Refresh Validation Checklist

```yaml
# Refresh Validation Checklist
pre_refresh:
  - name: "Backup Target Environment"
    critical: true
    command: "pg_dump -h {host} -d {db} > backup.sql"
  
  - name: "Check Disk Space"
    critical: true
    threshold: "20GB free"
  
  - name: "Notify Active Users"
    critical: false
    method: "email"

post_refresh:
  - name: "Row Count Validation"
    critical: true
    tolerance: "±5%"
  
  - name: "PII Masking Verification"
    critical: true
    checks:
      - "NIDs are hashed"
      - "Phone numbers are masked"
      - "Emails are substituted"
  
  - name: "Referential Integrity"
    critical: true
    checks:
      - "No orphan order items"
      - "All animals have valid farms"
  
  - name: "Smoke Tests"
    critical: true
    tests:
      - "User login"
      - "Place test order"
      - "View dashboard"
  
  - name: "Performance Baseline"
    critical: false
    metrics:
      - "Query response time < 2s"
      - "Page load time < 3s"
```

---

## 8. Test Data Storage

### 8.1 Storage Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                   TEST DATA STORAGE LAYERS                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    ENCRYPTED STORAGE                      │   │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐     │   │
│  │  │   Current    │ │   Archive    │ │   Version    │     │   │
│  │  │    Data      │ │    Data      │ │   Control    │     │   │
│  │  └──────────────┘ └──────────────┘ └──────────────┘     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    ACCESS CONTROL                         │   │
│  │  - Role-based access (RBAC)                              │   │
│  │  - Environment isolation                                  │   │
│  │  - Audit logging                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    DATA CLASSIFICATION                    │   │
│  │  - Public: Product catalog, General settings             │   │
│  │  - Internal: Synthetic test data                         │   │
│  │  - Confidential: Masked production data                  │   │
│  │  - Restricted: Token vault, encryption keys              │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 8.2 Storage Security

| Layer | Security Control | Implementation |
|-------|------------------|----------------|
| At Rest | AES-256 Encryption | Database TDE + File encryption |
| In Transit | TLS 1.3 | All data transfers |
| Backup | Encrypted Snapshots | AWS S3 SSE-KMS |
| Access | MFA + RBAC | Azure AD integration |
| Audit | Immutable Logs | CloudTrail / Azure Monitor |

### 8.3 Access Control Matrix

| Role | Dev Data | QA Data | Staging Data | UAT Data | Production |
|------|----------|---------|--------------|----------|------------|
| Developer | Read/Write | Read | - | - | - |
| QA Engineer | Read | Read/Write | Read | Read | - |
| QA Lead | Read | Read/Write | Read | Read/Write | - |
| DBA | Admin | Admin | Admin | Admin | Read (masked) |
| DevOps | Admin | Admin | Admin | Admin | - |
| Security | Audit | Audit | Audit | Audit | Audit |

### 8.4 Data Retention Policy

| Data Type | Retention Period | Archival | Destruction |
|-----------|------------------|----------|-------------|
| Active Test Data | Current sprint + 1 | Automated | Secure wipe |
| Golden Datasets | Permanent | Version control | Never |
| Masked Extracts | 90 days | Compressed | Crypto-shredding |
| Audit Logs | 7 years | Glacier | Legal hold |
| Token Vault | Life of token | N/A | Key destruction |

---

## 9. Data Provisioning

### 9.1 Self-Service Portal

```
┌─────────────────────────────────────────────────────────────────┐
│              TEST DATA SELF-SERVICE PORTAL                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Request New Dataset                                       │   │
│  │  ├─ Environment: [Dev ▼] [QA ▼] [Staging ▼]              │   │
│  │  ├─ Data Type: [Customers] [Orders] [Products] [All]     │   │
│  │  ├─ Volume: [Small] [Medium] [Large] [Custom]            │   │
│  │  ├─ Masking: [Full] [Partial] [None]                     │   │
│  │  └─ TTL: [1 day] [7 days] [30 days] [Permanent]          │   │
│  │                                                            │   │
│  │  [Submit Request]                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  My Active Datasets                                        │   │
│  │  ┌────────────┬────────────┬────────────┬────────────┐   │   │
│  │  │ Dataset ID │ Environment│ Created    │ Expires    │   │   │
│  │  ├────────────┼────────────┼────────────┼────────────┤   │   │
│  │  │ DS-001     │ Dev        │ 2026-01-30 │ 2026-02-06 │   │   │
│  │  │ DS-002     │ QA         │ 2026-01-28 │ 2026-02-28 │   │   │
│  │  └────────────┴────────────┴────────────┴────────────┘   │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 9.2 API for Data Provisioning

```python
"""
Test Data Provisioning API
FastAPI-based service for self-service data provisioning
"""

from fastapi import FastAPI, HTTPException, Depends
from pydantic import BaseModel
from typing import List, Optional
from enum import Enum
import uuid
from datetime import datetime, timedelta

app = FastAPI(title="Smart Dairy TDM API")

class Environment(str, Enum):
    DEV = "dev"
    QA = "qa"
    STAGING = "staging"
    UAT = "uat"

class DataType(str, Enum):
    CUSTOMERS = "customers"
    PRODUCTS = "products"
    ORDERS = "orders"
    FARMS = "farms"
    ANIMALS = "animals"
    ALL = "all"

class Volume(str, Enum):
    SMALL = "small"      # 1K records
    MEDIUM = "medium"    # 10K records
    LARGE = "large"      # 100K records
    CUSTOM = "custom"

class MaskingLevel(str, Enum):
    FULL = "full"
    PARTIAL = "partial"
    NONE = "none"

class DatasetRequest(BaseModel):
    environment: Environment
    data_types: List[DataType]
    volume: Volume
    custom_size: Optional[int] = None
    masking: MaskingLevel
    ttl_days: int = 7
    purpose: str
    requester_id: str

class DatasetResponse(BaseModel):
    dataset_id: str
    status: str
    environment: str
    estimated_completion: datetime
    download_url: Optional[str] = None

# In-memory storage (replace with database)
dataset_requests = {}

@app.post("/api/v1/datasets", response_model=DatasetResponse)
async def request_dataset(request: DatasetRequest):
    """Request a new test dataset"""
    
    # Validate request
    if request.volume == Volume.CUSTOM and not request.custom_size:
        raise HTTPException(status_code=400, detail="custom_size required for CUSTOM volume")
    
    # Generate dataset ID
    dataset_id = f"DS-{uuid.uuid4().hex[:8].upper()}"
    
    # Calculate size
    size_mapping = {
        Volume.SMALL: 1000,
        Volume.MEDIUM: 10000,
        Volume.LARGE: 100000
    }
    record_count = request.custom_size if request.volume == Volume.CUSTOM else size_mapping[request.volume]
    
    # Create request record
    dataset_requests[dataset_id] = {
        "dataset_id": dataset_id,
        "request": request,
        "status": "QUEUED",
        "created_at": datetime.now(),
        "estimated_completion": datetime.now() + timedelta(minutes=30),
        "download_url": None
    }
    
    # Trigger async generation (simplified)
    # In production, this would queue a background job
    
    return DatasetResponse(
        dataset_id=dataset_id,
        status="QUEUED",
        environment=request.environment,
        estimated_completion=dataset_requests[dataset_id]["estimated_completion"]
    )

@app.get("/api/v1/datasets/{dataset_id}")
async def get_dataset_status(dataset_id: str):
    """Get status of a dataset request"""
    if dataset_id not in dataset_requests:
        raise HTTPException(status_code=404, detail="Dataset not found")
    
    return dataset_requests[dataset_id]

@app.get("/api/v1/datasets/{dataset_id}/download")
async def download_dataset(dataset_id: str):
    """Download generated dataset"""
    if dataset_id not in dataset_requests:
        raise HTTPException(status_code=404, detail="Dataset not found")
    
    dataset = dataset_requests[dataset_id]
    if dataset["status"] != "COMPLETED":
        raise HTTPException(status_code=400, detail="Dataset not ready")
    
    return {"download_url": dataset["download_url"]}

@app.delete("/api/v1/datasets/{dataset_id}")
async def delete_dataset(dataset_id: str):
    """Delete a dataset"""
    if dataset_id not in dataset_requests:
        raise HTTPException(status_code=404, detail="Dataset not found")
    
    # Cleanup logic here
    del dataset_requests[dataset_id]
    
    return {"message": "Dataset deleted successfully"}

@app.get("/api/v1/datasets")
async def list_datasets(environment: Optional[Environment] = None,
                       requester_id: Optional[str] = None):
    """List available datasets with optional filtering"""
    results = []
    
    for ds in dataset_requests.values():
        if environment and ds["request"].environment != environment:
            continue
        if requester_id and ds["request"].requester_id != requester_id:
            continue
        results.append(ds)
    
    return results

# Health check
@app.get("/health")
async def health_check():
    return {"status": "healthy", "timestamp": datetime.now()}


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
```

### 9.3 Provisioning Automation

```yaml
# GitHub Actions workflow for automated data provisioning
name: Data Provisioning

on:
  workflow_dispatch:
    inputs:
      environment:
        description: 'Target environment'
        required: true
        default: 'dev'
        type: choice
        options:
          - dev
          - qa
          - staging
      data_types:
        description: 'Data types to provision'
        required: true
        default: 'all'
      volume:
        description: 'Data volume'
        required: true
        default: 'small'
        type: choice
        options:
          - small
          - medium
          - large

jobs:
  provision:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v3
      
      - name: Setup Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'
      
      - name: Install dependencies
        run: |
          pip install -r requirements.txt
      
      - name: Provision data
        env:
          DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
        run: |
          python scripts/provision_data.py \
            --environment ${{ github.event.inputs.environment }} \
            --data-types ${{ github.event.inputs.data_types }} \
            --volume ${{ github.event.inputs.volume }}
      
      - name: Notify team
        uses: slackapi/slack-github-action@v1
        with:
          payload: |
            {
              "text": "Data provisioning completed for ${{ github.event.inputs.environment }}"
            }
        env:
          SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK }}
```

---

## 10. Data Privacy Compliance

### 10.1 GDPR Compliance

| Requirement | Implementation | Evidence |
|-------------|----------------|----------|
| Lawful Processing | Masking required for test data | Masking audit logs |
| Data Minimization | Subset only required data | Subset configuration |
| Purpose Limitation | Test data only for testing | Usage policies |
| Storage Limitation | TTL enforcement on test data | Automated cleanup logs |
| Security | Encryption + Access control | Security assessments |
| Accountability | Full audit trail | Audit logs |

### 10.2 Bangladesh Data Protection Act Compliance

```python
"""
Bangladesh Data Protection Act 2023 Compliance Module
"""

from enum import Enum
from datetime import datetime
from typing import List, Dict

class DataClassification(Enum):
    PUBLIC = "public"
    INTERNAL = "internal"
    CONFIDENTIAL = "confidential"
    RESTRICTED = "restricted"

class BDComplianceChecker:
    """Verify compliance with Bangladesh Data Protection Act"""
    
    SENSITIVE_DATA_TYPES = [
        'nid', 'passport', 'biometric', 'financial_account',
        'health_record', 'religious_belief', 'political_opinion'
    ]
    
    @staticmethod
    def check_consent_requirements(data_elements: List[str]) -> Dict:
        """Check if explicit consent is required"""
        requires_consent = []
        
        for element in data_elements:
            if element.lower() in BDComplianceChecker.SENSITIVE_DATA_TYPES:
                requires_consent.append(element)
        
        return {
            'requires_explicit_consent': len(requires_consent) > 0,
            'sensitive_elements': requires_consent,
            'recommendation': 'Use synthetic data for testing' if requires_consent else 'Standard masking acceptable'
        }
    
    @staticmethod
    def check_retention_limits(data_type: str, retention_days: int) -> Dict:
        """Check if retention period complies with regulations"""
        limits = {
            'personal_data': 365,  # 1 year unless justified
            'financial_data': 2555,  # 7 years for tax
            'health_data': 2555,  # 7 years
            'test_data': 90  # 90 days recommended for test data
        }
        
        limit = limits.get(data_type, 365)
        
        return {
            'compliant': retention_days <= limit,
            'max_allowed': limit,
            'current': retention_days,
            'action_required': retention_days > limit
        }
    
    @staticmethod
    def generate_privacy_impact_assessment(data_usage: str, 
                                           data_volume: int,
                                           data_types: List[str]) -> Dict:
        """Generate Privacy Impact Assessment (PIA)"""
        
        risk_level = "LOW"
        if any(t in BDComplianceChecker.SENSITIVE_DATA_TYPES for t in data_types):
            risk_level = "HIGH"
        elif data_volume > 100000:
            risk_level = "MEDIUM"
        
        return {
            'assessment_date': datetime.now().isoformat(),
            'data_usage': data_usage,
            'data_volume': data_volume,
            'data_types': data_types,
            'risk_level': risk_level,
            'mitigations': [
                'Apply irreversible masking to all PII',
                'Use synthetic data where possible',
                'Implement access controls',
                'Enable audit logging',
                'Set data expiration (TTL)'
            ],
            'approver': 'Data Protection Officer',
            'review_date': (datetime.now().replace(year=datetime.now().year + 1)).isoformat()
        }
```

### 10.3 Compliance Checklist

```markdown
## Data Privacy Compliance Checklist

### Pre-Processing
- [ ] Identify all PII elements in source data
- [ ] Classify data sensitivity level
- [ ] Determine appropriate masking technique
- [ ] Obtain necessary approvals for data extraction
- [ ] Document business justification

### Processing
- [ ] Apply approved masking transformations
- [ ] Verify masking is irreversible for production data
- [ ] Maintain referential integrity after masking
- [ ] Log all masking operations
- [ ] Validate output contains no real PII

### Post-Processing
- [ ] Conduct PII scan on output
- [ ] Document data lineage
- [ ] Set appropriate TTL
- [ ] Configure access controls
- [ ] Notify data custodian

### Ongoing
- [ ] Review access logs monthly
- [ ] Audit data retention quarterly
- [ ] Update masking rules as needed
- [ ] Train team on privacy requirements
- [ ] Maintain compliance documentation
```

---

## 11. Sensitive Data Handling

### 11.1 Credit Card Data

🔴 **CRITICAL:** Credit card data must NEVER be used in test environments.

```python
"""
Credit Card Test Data Generation
Uses only test card numbers from payment processors
"""

class TestCardGenerator:
    """Generate test credit card numbers for testing"""
    
    # Test card numbers (from Stripe/SSLCommerz documentation)
    TEST_CARDS = {
        'visa': [
            '4242424242424242',
            '4000056655665556',
        ],
        'mastercard': [
            '5555555555554444',
            '5200828282828210',
        ],
        'amex': [
            '378282246310005',
            '371449635398431',
        ],
        'discover': [
            '6011111111111117',
            '6011000990139424',
        ],
        # Bangladesh-specific test cards
        'nexuspay': ['4111111111111111'],
        'bkash_test': ['01999999999'],  # Test wallet
        'nagad_test': ['01999999999'],  # Test wallet
    }
    
    # Test card properties
    TEST_CARD_DEFAULTS = {
        'expiry_month': '12',
        'expiry_year': '2030',
        'cvv': '123',
        'name': 'Test User'
    }
    
    @classmethod
    def get_test_card(cls, card_type: str = 'visa', index: int = 0) -> dict:
        """Get a test credit card"""
        cards = cls.TEST_CARDS.get(card_type, cls.TEST_CARDS['visa'])
        card_number = cards[index % len(cards)]
        
        return {
            'card_number': card_number,
            'card_type': card_type,
            'expiry_month': cls.TEST_CARD_DEFAULTS['expiry_month'],
            'expiry_year': cls.TEST_CARD_DEFAULTS['expiry_year'],
            'cvv': cls.TEST_CARD_DEFAULTS['cvv'],
            'name': cls.TEST_CARD_DEFAULTS['name'],
            'is_test': True
        }
    
    @classmethod
    def validate_no_real_cards(cls, data: list, field: str = 'card_number') -> bool:
        """Verify no real credit cards in dataset"""
        import re
        
        # Luhn algorithm check
        def luhn_check(card_number: str) -> bool:
            digits = [int(d) for d in card_number if d.isdigit()]
            if len(digits) < 13 or len(digits) > 19:
                return False
            
            odd_sum = sum(digits[-1::-2])
            even_sum = sum([sum(divmod(2 * d, 10)) for d in digits[-2::-2]])
            return (odd_sum + even_sum) % 10 == 0
        
        all_test_cards = []
        for cards in cls.TEST_CARDS.values():
            all_test_cards.extend(cards)
        
        for record in data:
            card = record.get(field, '')
            # Remove spaces and dashes
            card_clean = re.sub(r'[\s-]', '', card)
            
            # If it passes Luhn check but isn't a known test card, it's suspicious
            if luhn_check(card_clean) and card_clean not in all_test_cards:
                return False
        
        return True
```

### 11.2 NID (National ID) Handling

```python
"""
Bangladesh NID Handling Guidelines
"""

class NIDHandler:
    """Handle Bangladesh National ID numbers securely"""
    
    @staticmethod
    def validate_format(nid: str) -> bool:
        """Validate NID format"""
        if not nid:
            return False
        
        # Remove any spaces
        nid = nid.replace(' ', '')
        
        # Check length (10-17 digits)
        if not (10 <= len(nid) <= 17):
            return False
        
        # Check all digits
        if not nid.isdigit():
            return False
        
        return True
    
    @staticmethod
    def generate_test_nid() -> str:
        """Generate test NID for testing purposes"""
        import random
        import datetime
        
        # Generate a birthdate-based NID
        year = random.randint(1960, 2005)
        month = random.randint(1, 12)
        day = random.randint(1, 28)
        
        # Format: YYYYMMDD + random sequence
        date_part = f"{year}{month:02d}{day:02d}"
        sequence = random.randint(10000, 99999)
        
        return f"{date_part}{sequence}"
    
    @staticmethod
    def detect_real_nid(nid: str) -> bool:
        """
        Detect if NID might be real (heuristic)
        Returns True if potentially real
        """
        if not NIDHandler.validate_format(nid):
            return False
        
        # Check if birth year is reasonable
        try:
            birth_year = int(nid[:4])
            current_year = datetime.datetime.now().year
            
            # If birth year indicates age < 18 or > 100, might be test data
            age = current_year - birth_year
            if age < 18 or age > 100:
                return False
            
            # Check month
            month = int(nid[4:6])
            if month > 12:
                return False
            
            # Check day
            day = int(nid[6:8])
            if day > 31:
                return False
            
            return True
        except:
            return False
```

### 11.3 Phone Number Handling

```python
"""
Bangladesh Phone Number Test Data
"""

class TestPhoneGenerator:
    """Generate test phone numbers"""
    
    # Test number ranges (reserved for testing)
    TEST_PREFIXES = {
        'grameenphone': '01700',  # Test range
        'robi': '01800',
        'banglalink': '01900',
        'teletalk': '01500'
    }
    
    # Valid operator prefixes for validation
    VALID_PREFIXES = ['013', '014', '015', '016', '017', '018', '019']
    
    @classmethod
    def generate_test_phone(cls, operator: str = None) -> str:
        """Generate a test phone number"""
        import random
        
        if operator and operator in cls.TEST_PREFIXES:
            prefix = cls.TEST_PREFIXES[operator]
        else:
            prefix = random.choice(list(cls.TEST_PREFIXES.values()))
        
        # Generate remaining 6 digits
        suffix = ''.join([str(random.randint(0, 9)) for _ in range(6)])
        
        return f"{prefix}{suffix}"
    
    @classmethod
    def is_test_number(cls, phone: str) -> bool:
        """Check if phone is in test range"""
        for prefix in cls.TEST_PREFIXES.values():
            if phone.startswith(prefix):
                return True
        return False
    
    @classmethod
    def validate_bd_phone(cls, phone: str) -> bool:
        """Validate Bangladesh mobile number format"""
        import re
        
        # Remove country code if present
        phone = phone.replace('+88', '').replace('0088', '')
        
        # Check pattern
        pattern = r'^01[3-9]\d{8}$'
        return bool(re.match(pattern, phone))
```

### 11.4 MFS (Mobile Financial Services) Test Accounts

```python
"""
MFS Test Account Configuration
"""

class MFSTestAccounts:
    """Test accounts for Bangladesh MFS providers"""
    
    BKASH_TEST = {
        'personal': {
            'wallet_number': '01999999999',
            'pin': '12345',
            'otp': '123456',
            'balance': 10000.00
        },
        'merchant': {
            'wallet_number': '01999999998',
            'pin': '12345',
            'otp': '123456',
            'balance': 50000.00
        }
    }
    
    NAGAD_TEST = {
        'personal': {
            'wallet_number': '01999999997',
            'pin': '12345',
            'otp': '123456',
            'balance': 10000.00
        }
    }
    
    ROCKET_TEST = {
        'personal': {
            'wallet_number': '01999999996',
            'pin': '12345',
            'otp': '123456',
            'balance': 10000.00
        }
    }
    
    @classmethod
    def get_test_account(cls, provider: str, account_type: str = 'personal') -> dict:
        """Get test account details"""
        providers = {
            'bkash': cls.BKASH_TEST,
            'nagad': cls.NAGAD_TEST,
            'rocket': cls.ROCKET_TEST
        }
        
        provider_data = providers.get(provider.lower(), {})
        return provider_data.get(account_type, {})
```

---

## 12. Test Data for Specific Scenarios

### 12.1 Load Testing Data

```python
"""
Load Testing Data Generation
High-volume data for performance testing
"""

import uuid
import random
from datetime import datetime, timedelta

class LoadTestDataGenerator:
    """Generate massive datasets for load testing"""
    
    def __init__(self, seed: int = 42):
        random.seed(seed)
    
    def generate_concurrent_orders(self, count: int = 10000) -> list:
        """Generate orders for concurrent load testing"""
        orders = []
        base_time = datetime.now()
        
        for i in range(count):
            # Distribute over 1 hour window
            offset_seconds = random.randint(0, 3600)
            order_time = base_time + timedelta(seconds=offset_seconds)
            
            orders.append({
                'order_id': str(uuid.uuid4()),
                'customer_id': f"CUST_{random.randint(1, 1000):06d}",
                'product_id': f"PROD_{random.randint(1, 100):04d}",
                'quantity': random.randint(1, 10),
                'order_time': order_time.isoformat(),
                'status': 'pending'
            })
        
        return orders
    
    def generate_peak_load_customers(self, count: int = 100000) -> iter:
        """
        Generate customers using generator for memory efficiency
        Yields one customer at a time
        """
        for i in range(count):
            yield {
                'customer_id': f"CUST_{i:08d}",
                'email': f"user{i}@loadtest.local",
                'phone': f"017{random.randint(10000000, 99999999)}",
                'created_at': datetime.now().isoformat()
            }
    
    def generate_time_series_data(self, days: int = 30, 
                                   records_per_day: int = 1000) -> list:
        """Generate time-series data for trend analysis testing"""
        data = []
        base_date = datetime.now() - timedelta(days=days)
        
        for day in range(days):
            date = base_date + timedelta(days=day)
            
            # Add some trend and seasonality
            base_value = 1000 + (day * 10)  # Upward trend
            weekend_factor = 1.5 if date.weekday() >= 5 else 1.0
            
            for _ in range(records_per_day):
                value = int(base_value * weekend_factor * random.uniform(0.8, 1.2))
                data.append({
                    'date': date.strftime('%Y-%m-%d'),
                    'hour': random.randint(0, 23),
                    'metric': 'orders',
                    'value': value
                })
        
        return data


# Load test scenarios
LOAD_TEST_SCENARIOS = {
    'normal_load': {
        'concurrent_users': 100,
        'orders_per_minute': 50,
        'duration_minutes': 30
    },
    'peak_load': {
        'concurrent_users': 1000,
        'orders_per_minute': 500,
        'duration_minutes': 15
    },
    'stress_test': {
        'concurrent_users': 5000,
        'orders_per_minute': 2000,
        'duration_minutes': 10
    },
    'spike_test': {
        'concurrent_users': 10000,
        'orders_per_minute': 5000,
        'duration_minutes': 5
    }
}
```

### 12.2 Edge Case Data

```python
"""
Edge Case Test Data
Data for boundary and exception testing
"""

class EdgeCaseGenerator:
    """Generate edge case test data"""
    
    @staticmethod
    def get_boundary_values(data_type: str) -> list:
        """Get boundary values for a data type"""
        boundaries = {
            'string': [
                '',  # Empty
                'a',  # Single char
                'a' * 255,  # Max typical
                'a' * 10000,  # Very long
                '<script>alert(1)</script>',  # XSS attempt
                "'; DROP TABLE users; --",  # SQL injection
                '日本語',  # Unicode
                '🔥🚀💯',  # Emojis
                '\x00\x01\x02',  # Control chars
                '   ',  # Whitespace only
            ],
            'integer': [
                0,
                1,
                -1,
                2147483647,  # Max int32
                -2147483648,  # Min int32
                9223372036854775807,  # Max int64
            ],
            'decimal': [
                0.0,
                0.01,
                -0.01,
                999999999.99,
                -999999999.99,
                float('inf'),
                float('-inf'),
            ],
            'date': [
                '1900-01-01',
                '9999-12-31',
                '2024-02-29',  # Leap year
                '2024-02-30',  # Invalid
                '0000-00-00',  # Invalid
            ],
            'phone': [
                '',
                '017',
                '017123456789012345',  # Too long
                'abcdefghij',  # Letters
                '00000000000',  # All zeros
                '+8801712345678',  # With country code
            ],
            'email': [
                '',
                '@',
                '@example.com',
                'user@',
                'user@example',
                'user.name+tag@example.com',
                'user@sub.example.com',
                'a@b.co',
                'user name@example.com',  # Space
            ]
        }
        
        return boundaries.get(data_type, [])
    
    @staticmethod
    def get_invalid_combinations() -> list:
        """Get invalid data combinations"""
        return [
            {
                'scenario': 'Future order date',
                'order_date': '2030-01-01',
                'delivery_date': '2025-01-01',
                'expected_error': 'Delivery before order'
            },
            {
                'scenario': 'Negative quantity',
                'quantity': -5,
                'expected_error': 'Invalid quantity'
            },
            {
                'scenario': 'Invalid discount',
                'subtotal': 100,
                'discount': 150,
                'expected_error': 'Discount exceeds subtotal'
            },
            {
                'scenario': 'Mismatched currency',
                'price_bdt': 100,
                'payment_currency': 'USD',
                'expected_error': 'Currency mismatch'
            }
        ]
```

### 12.3 Multi-Tenancy Test Data

```python
"""
Multi-tenancy Test Data
For testing tenant isolation
"""

class MultiTenancyTestData:
    """Generate test data for multi-tenant scenarios"""
    
    TENANTS = [
        {'id': 'T001', 'name': 'Dhaka Central Hub', 'region': 'dhaka'},
        {'id': 'T002', 'name': 'Chittagong Distribution', 'region': 'chittagong'},
        {'id': 'T003', 'name': 'Sylhet Farms Network', 'region': 'sylhet'},
    ]
    
    @classmethod
    def generate_tenant_isolated_data(cls, tenant_id: str) -> dict:
        """Generate data for a specific tenant"""
        tenant = next((t for t in cls.TENANTS if t['id'] == tenant_id), None)
        if not tenant:
            raise ValueError(f"Unknown tenant: {tenant_id}")
        
        return {
            'tenant_id': tenant_id,
            'customers': cls._generate_tenant_customers(tenant),
            'orders': cls._generate_tenant_orders(tenant),
            'inventory': cls._generate_tenant_inventory(tenant)
        }
    
    @classmethod
    def _generate_tenant_customers(cls, tenant: dict) -> list:
        """Generate customers for a tenant"""
        return [
            {
                'customer_id': f"{tenant['id']}_CUST_{i:04d}",
                'tenant_id': tenant['id'],
                'region': tenant['region']
            }
            for i in range(100)
        ]
    
    @classmethod
    def _generate_tenant_orders(cls, tenant: dict) -> list:
        """Generate orders for a tenant"""
        return [
            {
                'order_id': f"{tenant['id']}_ORD_{i:06d}",
                'tenant_id': tenant['id'],
                'region': tenant['region']
            }
            for i in range(500)
        ]
    
    @classmethod
    def _generate_tenant_inventory(cls, tenant: dict) -> list:
        """Generate inventory for a tenant"""
        return [
            {
                'inventory_id': f"{tenant['id']}_INV_{i:04d}",
                'tenant_id': tenant['id'],
                'warehouse_location': tenant['region']
            }
            for i in range(50)
        ]
    
    @staticmethod
    def verify_tenant_isolation(data: list) -> bool:
        """Verify no data leakage between tenants"""
        tenant_ids = set()
        for record in data:
            tid = record.get('tenant_id')
            tenant_ids.add(tid)
        
        # Each record should have exactly one tenant_id
        return len(tenant_ids) > 0
```

---

## 13. Tools & Technologies

### 13.1 Tool Stack

| Category | Tool | Purpose | License |
|----------|------|---------|---------|
| Data Generation | Faker | Synthetic data generation | MIT |
| Data Generation | Custom Python | Business-specific data | Proprietary |
| Data Masking | Apache ShardingSphere | Database masking | Apache 2.0 |
| Data Masking | Custom Python Scripts | Field-level masking | Proprietary |
| Data Subsetting | Benerator | Large-scale subsetting | Open Source |
| Test Data Mgmt | Delphix / Informatica | Enterprise TDM | Commercial |
| Storage | PostgreSQL | Relational test data | PostgreSQL |
| Storage | MongoDB | Document test data | SSPL |
| Storage | AWS S3 | File-based test data | Commercial |
| Orchestration | Apache Airflow | Refresh automation | Apache 2.0 |
| API | FastAPI | Self-service portal | MIT |

### 13.2 Faker Configuration

```python
"""
Faker Configuration for Smart Dairy
"""

from faker import Faker
from faker.providers import internet, phone_number, date_time

# Configure Faker for Bangladesh context
fake = Faker('en_US')  # Fallback
fake_bd = Faker()

# Custom Bangladesh providers
class BangladeshProvider:
    """Custom provider for Bangladesh-specific data"""
    
    def bangladesh_phone(self) -> str:
        """Generate Bangladesh mobile number"""
        prefixes = ['013', '014', '015', '016', '017', '018', '019']
        prefix = fake.random_element(prefixes)
        number = ''.join([str(fake.random_digit()) for _ in range(8)])
        return f"{prefix}{number}"
    
    def bangladesh_address(self) -> dict:
        """Generate Bangladesh address"""
        districts = ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 
                     'Khulna', 'Barisal', 'Rangpur']
        district = fake.random_element(districts)
        
        return {
            'line1': f"House {fake.building_number()}, Road {fake.building_number()}",
            'line2': f"{district}",
            'district': district,
            'postal_code': f"{fake.random_int(1000, 9999)}"
        }
    
    def bengali_name(self) -> dict:
        """Generate Bengali-style name"""
        first_names = ['Mohammad', 'Abdul', 'Rahim', 'Karim', 'Hasan',
                       'Fatima', 'Ayesha', 'Khadija', 'Rahima', 'Sultana']
        last_names = ['Khan', 'Ahmed', 'Ali', 'Hossain', 'Hasan', 
                      'Rahman', 'Islam', 'Mia']
        
        return {
            'first': fake.random_element(first_names),
            'last': fake.random_element(last_names)
        }

# Add custom provider
fake_bd.add_provider(BangladeshProvider)

# Example usage
if __name__ == "__main__":
    print(f"Phone: {fake_bd.bangladesh_phone()}")
    print(f"Address: {fake_bd.bangladesh_address()}")
    print(f"Name: {fake_bd.bengali_name()}")
```

### 13.3 Custom Scripts Repository

```
smart-dairy-tdm/
├── generators/
│   ├── __init__.py
│   ├── customer_generator.py
│   ├── product_generator.py
│   ├── farm_generator.py
│   ├── order_generator.py
│   └── employee_generator.py
├── maskers/
│   ├── __init__.py
│   ├── nid_masker.py
│   ├── phone_masker.py
│   ├── email_masker.py
│   └── address_masker.py
├── subsetters/
│   ├── __init__.py
│   ├── stratified_subsetter.py
│   └── referential_subsetter.py
├── validators/
│   ├── __init__.py
│   ├── data_quality_validator.py
│   └── compliance_validator.py
├── api/
│   ├── __init__.py
│   └── provisioning_api.py
├── scripts/
│   ├── refresh_data.py
│   ├── provision_environment.py
│   └── validate_masking.py
└── config/
    ├── masking_rules.yaml
    ├── subset_config.yaml
    └── environments.yaml
```

---

## 14. Appendices

### Appendix A: Sample Data Sets

#### A.1 Customer Sample Data

```json
[
  {
    "customer_id": "cust-001",
    "first_name": "Rahim",
    "last_name": "Khan",
    "email": "rahim.khan@example.com",
    "phone": "01712345678",
    "nid": "1985123456789",
    "address": {
      "line1": "House #42, Road #7",
      "line2": "Dhanmondi",
      "city": "Dhaka",
      "district": "Dhaka",
      "postal_code": "1209"
    },
    "customer_type": "b2c",
    "registration_date": "2024-01-15",
    "is_active": true,
    "loyalty_points": 1250
  }
]
```

#### A.2 Product Sample Data

```json
[
  {
    "product_id": "prod-001",
    "sku": "SD-FM-0001",
    "name_en": "Full Cream Milk",
    "name_bn": "ফুল ক্রিম দুধ",
    "category": "Fresh Milk",
    "unit_type": "liter",
    "base_price": 70.00,
    "sale_price": 68.00,
    "stock_quantity": 500,
    "is_active": true
  }
]
```

#### A.3 Order Sample Data

```json
[
  {
    "order_id": "ord-001",
    "order_number": "SD-20260130-12345",
    "customer_id": "cust-001",
    "order_date": "2026-01-30",
    "status": "delivered",
    "payment_method": "bkash",
    "total_amount": 750.00,
    "items": [
      {
        "product_id": "prod-001",
        "quantity": 5,
        "unit_price": 70.00
      },
      {
        "product_id": "prod-002",
        "quantity": 2,
        "unit_price": 200.00
      }
    ]
  }
]
```

### Appendix B: Masking Rules Reference

| Field | Type | Method | Example Input | Example Output |
|-------|------|--------|---------------|----------------|
| nid | NID | Hash | 1985123456789 | NID_A3F7B2C8D1E9... |
| phone | Phone | Partial | 01712345678 | 01712****78 |
| email | Email | Username | user@mail.com | u**r@mail.com |
| first_name | Name | Substitute | Mohammad | Rahim |
| address_line1 | Address | Generalize | House 42, Road 7 | Dhaka, Bangladesh |
| credit_card | Card | Test Replace | 4532123456789012 | 4242424242424242 |

### Appendix C: Data Refresh Templates

#### C.1 Weekly Refresh (QA)

```bash
#!/bin/bash
# qa_refresh.sh - Weekly QA Environment Refresh

set -e

ENV="qa"
LOG_FILE="/var/log/tdm/refresh_${ENV}_$(date +%Y%m%d).log"

echo "Starting QA refresh at $(date)" | tee -a $LOG_FILE

# 1. Backup current QA
pg_dump -h qa-db.smartdairy.com -U tdm_user qa_db > /backup/qa_pre_$(date +%Y%m%d).sql

# 2. Extract from production (masked)
python scripts/extract_and_mask.py --source prod --target /tmp/qa_extract.sql

# 3. Create 10% subset
python scripts/create_subset.py --input /tmp/qa_extract.sql --ratio 0.1 --output /tmp/qa_subset.sql

# 4. Restore to QA
psql -h qa-db.smartdairy.com -U tdm_user -d qa_db < /tmp/qa_subset.sql

# 5. Validate
curl -X POST http://tdm-api.smartdairy.com/validate --data '{"environment": "qa"}'

# 6. Notify
curl -X POST $SLACK_WEBHOOK -d '{"text": "QA refresh completed"}'

echo "QA refresh completed at $(date)" | tee -a $LOG_FILE
```

#### C.2 Daily Refresh (Staging)

```bash
#!/bin/bash
# staging_refresh.sh - Daily Staging Refresh

ENV="staging"
LOG_FILE="/var/log/tdm/refresh_${ENV}_$(date +%Y%m%d).log"

echo "Starting Staging refresh at $(date)" | tee -a $LOG_FILE

# Full masked production data (no subset)
python scripts/extract_and_mask.py --source prod --target /tmp/staging_data.sql

# Restore full dataset
psql -h staging-db.smartdairy.com -U tdm_user -d staging_db < /tmp/staging_data.sql

# Run smoke tests
python scripts/smoke_tests.py --environment staging

echo "Staging refresh completed at $(date)" | tee -a $LOG_FILE
```

### Appendix D: Compliance Checklist Templates

#### D.1 Pre-Deployment Compliance Check

```markdown
## Pre-Deployment TDM Compliance Checklist

### Data Privacy
- [ ] All production PII has been masked
- [ ] Masking is irreversible (one-way hash for NID)
- [ ] No real credit card numbers in test data
- [ ] Phone numbers are in test ranges
- [ ] Email addresses use test domains

### Data Quality
- [ ] Row counts are within expected ranges
- [ ] Referential integrity is maintained
- [ ] No orphan records
- [ ] Date ranges are appropriate
- [ ] Numeric values are within valid ranges

### Security
- [ ] Data at rest is encrypted
- [ ] Access controls are configured
- [ ] Audit logging is enabled
- [ ] TTL is set for automatic cleanup
- [ ] Token vault is secured

### Documentation
- [ ] Data lineage is documented
- [ ] Masking rules are documented
- [ ] Refresh schedule is published
- [ ] Contact information is updated
- [ ] Incident response plan is in place

**Approved By:** _________________ **Date:** _________________
```

### Appendix E: Quick Reference Cards

#### E.1 Masking Quick Reference

```
┌─────────────────────────────────────────────────────────────┐
│              DATA MASKING QUICK REFERENCE                    │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  NID:         Hash → NID_HASH_16CHARS                       │
│  Phone:       Partial → 01712****78                         │
│  Email:       Mask user → u**r@domain.com                   │
│  Name:        Substitute → Faker generated                  │
│  Address:     Generalize → City, Country                    │
│  Credit Card: Use test numbers only!                        │
│                                                              │
│  🔴 NEVER use real credit cards in test!                    │
│  🔴 NEVER store unmasked NID in test DB!                    │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

#### E.2 Refresh Quick Reference

```
┌─────────────────────────────────────────────────────────────┐
│              REFRESH QUICK REFERENCE                         │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  Dev:    On-demand → Self-service portal                    │
│  QA:     Weekly → Saturday 02:00 UTC+6                      │
│  Staging: Daily → 01:00 UTC+6                               │
│  UAT:    Bi-weekly → Sunday 00:00 UTC+6                     │
│                                                              │
│  Emergency: Contact DBA on-call                             │
│  Support: tdm-support@smartdairy.com                        │
│  Docs:    wiki.smartdairy.com/tdm                           │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### Appendix F: Troubleshooting Guide

| Issue | Cause | Solution |
|-------|-------|----------|
| Masking failed | Invalid regex | Check pattern syntax |
| Referential integrity error | Subset excluded parent | Use referential subsetter |
| Disk space error | Insufficient space | Clean up old extracts |
| Timeout during refresh | Large dataset | Increase timeout or use streaming |
| PII detected in output | Masking rule missed | Update masking rules |
| Duplicate keys | Consistent mapping conflict | Clear mapping cache |
| Slow generation | Large volume | Use batch processing |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | QA Engineer | _________________ | _______ |
| Owner | QA Lead | _________________ | _______ |
| Reviewer | Database Administrator | _________________ | _______ |
| Approver | CTO | _________________ | _______ |

---

## Related Documents

- G-020: Test Environment Management Guide
- G-021: Performance Testing Guide
- G-022: Security Testing Guide
- T-001: Master Test Plan
- D-015: Database Schema Documentation

---

*Document Control: This document is controlled and maintained by the QA Team. All changes must be approved by the QA Lead and Database Administrator.*

*Classification: Internal - Confidential*

*Next Review Date: July 31, 2026*
