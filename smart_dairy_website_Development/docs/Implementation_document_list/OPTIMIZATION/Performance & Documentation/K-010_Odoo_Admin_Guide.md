# SMART DAIRY LTD.
## ODOO ADMINISTRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | K-010 |
| **Version** | 1.0 |
| **Date** | October 25, 2026 |
| **Author** | Technical Writer |
| **Owner** | Odoo Lead |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Getting Started](#2-getting-started)
3. [System Configuration](#3-system-configuration)
4. [User & Access Management](#4-user--access-management)
5. [Module Management](#5-module-management)
6. [Farm Management Configuration](#6-farm-management-configuration)
7. [Sales & CRM Configuration](#7-sales--crm-configuration)
8. [Inventory & Purchase](#8-inventory--purchase)
9. [Accounting Configuration](#9-accounting-configuration)
10. [Website & E-commerce](#10-website--e-commerce)
11. [Maintenance & Troubleshooting](#11-maintenance--troubleshooting)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide provides comprehensive instructions for administrators managing the Smart Dairy Odoo ERP system. It covers system configuration, user management, module administration, and day-to-day operational tasks.

### 1.2 Odoo Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ODOO ARCHITECTURE                                    │
└─────────────────────────────────────────────────────────────────────────────┘

   WEB TIER                    APPLICATION TIER                  DATA TIER
      │                              │                              │
      ▼                              ▼                              ▼
┌──────────┐                 ┌──────────────┐               ┌──────────────┐
│  NGINX   │◄───────────────►│  Odoo Server │◄─────────────►│  PostgreSQL  │
│  (SSL)   │                 │  (Workers)   │               │  Database    │
└──────────┘                 └──────────────┘               └──────────────┘
                                     │
                                     ▼
                            ┌──────────────┐
                            │   Filestore  │
                            │  (S3/EFS)    │
                            └──────────────┘

WORKER TYPES:
├── Web Workers: Handle HTTP requests
├── Cron Workers: Background scheduled tasks
└── Longpolling: Real-time notifications
```

### 1.3 Environment Details

| Environment | URL | Odoo Version | Database |
|-------------|-----|--------------|----------|
| **Production** | https://erp.smartdairy.com | 19.0 CE | smart_dairy_prod |
| **Staging** | https://staging.smartdairy.com | 19.0 CE | smart_dairy_staging |
| **Development** | http://localhost:8069 | 19.0 CE | smart_dairy_dev |

---

## 2. GETTING STARTED

### 2.1 Initial Login

1. Navigate to https://erp.smartdairy.com
2. Enter your administrator credentials
3. Complete 2FA if enabled
4. You will be redirected to the Apps dashboard

### 2.2 Administrator Interface Overview

| Menu Section | Purpose | Key Features |
|--------------|---------|--------------|
| **Apps** | Module management | Install, update, uninstall modules |
| **Settings** | System configuration | General settings, company info, technical settings |
| **Users** | User management | Create users, assign groups, reset passwords |
| **Technical** | Advanced configuration | Database structure, scheduled actions, email templates |
| **Farm** | Dairy operations | Animal management, milk production, health records |
| **Sales** | Sales operations | Orders, quotations, products, pricing |
| **Website** | E-commerce | Store configuration, themes, SEO |

### 2.3 Activating Developer Mode

```python
# Method 1: Via URL
# Append ?debug=1 to any Odoo URL
# https://erp.smartdairy.com/web?debug=1

# Method 2: Via Settings
Settings → Activate the developer mode

# Method 3: Via Command Line (for automation)
./odoo-bin shell -d smart_dairy_prod << 'EOF'
from odoo import api, fields, models
env = api.Environment(cr, 1, {})
# Developer mode is now active
EOF
```

---

## 3. SYSTEM CONFIGURATION

### 3.1 Company Configuration

```python
# Access: Settings → Users & Companies → Companies

Company Settings:
├── General Information
│   ├── Company Name: Smart Dairy Ltd.
│   ├── Address: [Full Address]
│   ├── Phone: +91-xxx-xxx-xxxx
│   └── Email: info@smartdairy.com
│
├── Accounting
│   ├── Currency: INR (₹)
│   ├── Fiscal Year End: March 31
│   └── Tax ID: GST-XXXXXXXXXX
│
└── Multi-company
    ├── Allow multiple companies: [Yes/No]
    └── Company hierarchy configuration
```

### 3.2 General Settings

```python
# Critical Settings to Configure

# 1. Multi-company (if applicable)
Settings → General Settings → Multi-company → Manage Companies

# 2. Multi-currency
Settings → Accounting → Currencies → Multi-currency

# 3. Language and Localization
Settings → Translations → Languages
- Install: English (US), Hindi
- Load translation for all modules

# 4. Time Zone
Settings → General Settings → Time Zone
- Default: Asia/Kolkata (IST)

# 5. Email Configuration
Settings → Technical → Outgoing Mail Servers
- SMTP Server: email-smtp.ap-south-1.amazonaws.com
- Port: 587
- Security: TLS

# 6. Filestore Configuration (S3)
# Configuration via environment variable:
# ODOO_S3_BUCKET=smart-dairy-odoo-filestore
# ODOO_S3_REGION=ap-south-1
```

### 3.3 Scheduled Actions (Cron Jobs)

```python
# Access: Settings → Technical → Automation → Scheduled Actions

# Critical Scheduled Actions:
[
    {
        'name': 'Farm: Daily Milk Aggregation',
        'interval': 'days',
        'interval_number': 1,
        'nextcall': '02:00:00',
        'model': 'farm.milk.production',
        'function': 'cron_daily_aggregation'
    },
    {
        'name': 'Inventory: Auto Reorder Rules',
        'interval': 'hours',
        'interval_number': 4,
        'model': 'stock.warehouse.orderpoint',
        'function': 'cron_reorder'
    },
    {
        'name': 'Sales: Abandoned Cart Recovery',
        'interval': 'hours',
        'interval_number': 6,
        'model': 'sale.order',
        'function': 'cron_abandoned_cart'
    },
    {
        'name': 'Maintenance: Vacuum Database',
        'interval': 'days',
        'interval_number': 1,
        'nextcall': '03:00:00',
        'model': 'ir.autovacuum',
        'function': 'power_on'
    }
]

# Create Custom Scheduled Action
# Settings → Technical → Automation → Scheduled Actions → Create
{
    'Action Name': 'Custom Data Sync',
    'Model': 'res.partner',
    'Execute Every': '1 days',
    'Number of Calls': '-1',  # Unlimited
    'Repeat Missed': True,
    'Python Code': '''
# Custom sync logic
env['res.partner'].search([('sync_needed', '=', True)]).action_sync()
'''
}
```

### 3.4 System Parameters

```python
# Access: Settings → Technical → Parameters → System Parameters

# Important System Parameters:
{
    # Email
    'mail.catchall.domain': 'smartdairy.com',
    'mail.catchall.alias': 'catchall',
    'mail.bounce.alias': 'bounce',
    
    # Session
    'session.max_inactivity_seconds': '7200',  # 2 hours
    'session.max_sessions_per_user': '10',
    
    # Performance
    'limit_time_cpu': '60',
    'limit_time_real': '120',
    'limit_memory_hard': '2684354560',
    
    # Filestore
    'ir_attachment.location': 's3',
    'ir_attachment.url.storage': 'True',
    
    # SMS (Twilio)
    'sms.twilio_account_sid': 'xxxx',
    'sms.twilio_auth_token': 'xxxx',
    'sms.twilio_phone_number': '+91xxxxxxxxxx',
    
    # Payment Gateway (Razorpay)
    'payment_razorpay.key_id': 'rzp_test_xxxx',
    'payment_razorpay.key_secret': 'xxxx',
    
    # B2B Portal
    'b2b.minimum_order_amount': '5000',
    'b2b.credit_limit_default': '100000',
}
```

---

## 4. USER & ACCESS MANAGEMENT

### 4.1 User Creation

```python
# Access: Settings → Users & Companies → Users → Create

# Standard User Creation Process:
1. Name: [Full Name]
2. Email Address: [email@smartdairy.com]
3. Phone: [Mobile Number]
4. Company: Smart Dairy Ltd.
5. Access Rights (Groups):
   - Farm: Farm User / Farm Manager
   - Sales: Sales User / Sales Manager
   - Inventory: User / Manager
   - Accounting: Billing / Accountant
   - Website: Editor / Manager
   - Administration: Access Rights / Settings

# Automated User Creation via API
import xmlrpc.client

url = 'https://erp.smartdairy.com'
db = 'smart_dairy_prod'
username = 'admin'
password = 'admin_password'

common = xmlrpc.client.ServerProxy(f'{url}/xmlrpc/2/common')
uid = common.authenticate(db, username, password, {})

models = xmlrpc.client.ServerProxy(f'{url}/xmlrpc/2/object')

user_id = models.execute_kw(db, uid, password, 'res.users', 'create', [{
    'name': 'New Employee',
    'login': 'new.employee@smartdairy.com',
    'password': 'temporary_password',
    'groups_id': [(6, 0, [
        models.execute_kw(db, uid, password, 'res.groups', 'search', [[('name', '=', 'Farm User')]]),
        models.execute_kw(db, uid, password, 'res.groups', 'search', [[('name', '=', 'Sales User')]])
    ])]
}])
```

### 4.2 Group & Permission Management

```python
# Access: Settings → Users & Companies → Groups

# Smart Dairy Custom Groups Structure:
FARM_MANAGEMENT = {
    'Farm Worker': [
        'farm.animal: read',
        'farm.milk.production: create, read',
        'farm.health.record: create, read',
    ],
    'Farm Supervisor': [
        'farm.animal: create, read, write',
        'farm.milk.production: full',
        'farm.breeding: full',
        'farm.health.record: full',
    ],
    'Farm Manager': [
        'farm.*: full',
        'farm.configuration: full',
        'reporting.farm: full',
    ]
}

SALES_GROUPS = {
    'B2B Sales': ['sales orders', 'customer management', 'quotations'],
    'B2C Support': ['e-commerce orders', 'customer queries'],
    'Sales Manager': ['full sales access', 'pricing', 'reports'],
}

# Create Custom Group
# Settings → Users & Companies → Groups → Create
{
    'Application': 'Smart Dairy / Farm',
    'Name': 'Animal Health Officer',
    'Inherited': ['Farm Worker'],
    'Menus': ['Farm / Health', 'Farm / Animals'],
    'Access Rights': [
        {'Model': 'farm.animal', 'Read': True, 'Write': True, 'Create': False, 'Delete': False},
        {'Model': 'farm.health.record', 'Full': True},
    ]
}
```

### 4.3 Password Policies

```python
# Settings → General Settings → Access → Password Policy

{
    'Minimum Password Length': 12,
    'Minimum Uppercase Letters': 1,
    'Minimum Lowercase Letters': 1,
    'Minimum Numeric Characters': 1,
    'Minimum Special Characters': 1,
    'Password Expiration (days)': 90,
    'Lockout after failed attempts': 5,
    'Lockout duration (minutes)': 30,
}

# Password Reset Process:
# 1. User clicks "Forgot Password" on login page
# 2. Email sent with secure reset link (valid for 24 hours)
# 3. User sets new password meeting policy requirements
# 4. Old password invalidated immediately
```

### 4.4 API Keys Management

```python
# Access: User Profile → Account Security → API Keys

# Create API Key for Integration:
# 1. Click "New API Key"
# 2. Enter description: "IoT Device Integration"
# 3. Set expiration: 90 days
# 4. Copy and store key securely (shown only once)

# API Key Usage:
import requests

url = 'https://erp.smartdairy.com/api/v2/farm/animals'
headers = {
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
}

response = requests.get(url, headers=headers)
animals = response.json()

# Revoke API Key:
# User Profile → Account Security → API Keys → Revoke
```

---

## 5. MODULE MANAGEMENT

### 5.1 Module Installation

```python
# Access: Apps → Apps → Search/Browse

# Smart Dairy Core Modules:
REQUIRED_MODULES = [
    # Farm Management
    'smart_dairy_farm',
    'smart_dairy_milk_collection',
    'smart_dairy_animal_health',
    'smart_dairy_breeding',
    
    # Sales & Distribution
    'smart_dairy_sales',
    'smart_dairy_b2b_portal',
    'smart_dairy_b2c_store',
    
    # Inventory & Purchase
    'smart_dairy_inventory',
    'smart_dairy_procurement',
    
    # Accounting
    'smart_dairy_accounting',
    'smart_dairy_gst_india',
    
    # IoT Integration
    'smart_dairy_iot',
    'smart_dairy_mqtt',
    
    # Reporting
    'smart_dairy_reports',
    'smart_dairy_dashboard',
]

# Install Module via Command Line
./odoo-bin -d smart_dairy_prod -i smart_dairy_farm --stop-after-init

# Install Module via API
models.execute_kw(db, uid, password, 'ir.module.module', 'button_immediate_install', [module_id])
```

### 5.2 Module Updates

```python
# Update Single Module
./odoo-bin -d smart_dairy_prod -u smart_dairy_farm --stop-after-init

# Update All Custom Modules
./odoo-bin -d smart_dairy_prod -u all --stop-after-init

# Update via Web Interface
Apps → Apps → [Select Module] → Upgrade

# Scheduled Module Updates (Maintenance Window)
# See Scheduled Actions section
```

### 5.3 Module Development Mode

```python
# Enable auto-reload for development
./odoo-bin -d smart_dairy_dev --dev=reload,xml,qweb

# Dev mode options:
# - reload: Auto-reload Python code
# - xml: Auto-reload XML changes
# - qweb: Auto-reload QWeb templates
# - all: Enable all dev features
```

---

## 6. FARM MANAGEMENT CONFIGURATION

### 6.1 Farm Setup

```python
# Access: Farm → Configuration → Farms

FARM_CONFIGURATION = {
    'Farm Name': 'Smart Dairy Main Farm',
    'Location': {
        'Address': 'Village XYZ, District ABC',
        'GPS Coordinates': '19.0760° N, 72.8777° E',
    },
    'Barns': [
        {'Name': 'Barn A - Milking', 'Capacity': 100, 'Type': 'milking_parlor'},
        {'Name': 'Barn B - Dry Cows', 'Capacity': 80, 'Type': 'dry_cow_shed'},
        {'Name': 'Barn C - Calves', 'Capacity': 50, 'Type': 'calf_pen'},
        {'Name': 'Barn D - Hospital', 'Capacity': 20, 'Type': 'hospital_pen'},
    ],
    'Equipment': [
        {'Name': 'Milking Machine 1', 'Type': 'automatic', 'Capacity': '12 cows/hour'},
        {'Name': 'Cold Storage 1', 'Type': 'bulk_tank', 'Capacity': '5000 liters'},
    ]
}

# Create via Odoo Interface
Farm → Configuration → Farms → Create
```

### 6.2 Animal Master Data

```python
# Access: Farm → Animals → Animals

# Breed Configuration
BREEDS = [
    {'Name': 'Holstein Friesian', 'Origin': 'Netherlands', 'Avg Milk': '7500L/lactation'},
    {'Name': 'Gir', 'Origin': 'India', 'Avg Milk': '4500L/lactation'},
    {'Name': 'Sahiwal', 'Origin': 'India', 'Avg Milk': '4000L/lactation'},
    {'Name': 'Jersey', 'Origin': 'UK', 'Avg Milk': '5000L/lactation'},
]

# Create in Settings → Farm → Breeds

# Animal Status Workflow
ANIMAL_STATUS = {
    'calf': 'Calf (0-6 months)',
    'heifer': 'Heifer (6-24 months, not pregnant)',
    'pregnant_heifer': 'Pregnant Heifer',
    'lactating': 'Lactating (Milking)',
    'dry': 'Dry (Not milking, pregnant)',
    'transition': 'Transition (2 weeks before calving)',
    'sold': 'Sold',
    'deceased': 'Deceased',
}

# RFID Configuration
RFID_SETTINGS = {
    'Tag Format': 'ISO 11784/11785',
    'Reader Integration': 'MQTT',
    'Auto-sync': True,
    'Duplicate Check': True,
}
```

### 6.3 Milk Collection Configuration

```python
# Access: Farm → Configuration → Milk Collection

MILK_COLLECTION_CONFIG = {
    'Collection Sessions': [
        {'Name': 'Morning', 'Start': '04:00', 'End': '08:00'},
        {'Name': 'Evening', 'Start': '16:00', 'End': '20:00'},
    ],
    'Quality Parameters': [
        {'Name': 'Fat %', 'Min': 3.0, 'Max': 6.0, 'Unit': '%'},
        {'Name': 'SNF %', 'Min': 8.0, 'Max': 10.0, 'Unit': '%'},
        {'Name': 'Density', 'Min': 1.026, 'Max': 1.034, 'Unit': 'g/ml'},
        {'Name': 'Temperature', 'Min': 2, 'Max': 8, 'Unit': '°C'},
    ],
    'Pricing Tiers': {
        'Standard': 'Base price based on FAT & SNF',
        'Premium': '+10% for organic',
        'A-Grade': '+5% for somatic cell count < 200k',
    }
}

# Milk Collection Point Setup
# Farm → Configuration → Collection Points
{
    'Name': 'Main Collection Center',
    'Location': 'Barn A',
    'Equipment': ['Automatic Weigher', 'Milk Analyzer'],
    'Responsible': 'Milk Collection Officer',
}
```

### 6.4 Health & Breeding Configuration

```python
# Access: Farm → Configuration → Health / Breeding

HEALTH_CONFIG = {
    'Vaccination Schedule': [
        {'Vaccine': 'FMD', 'Frequency': '6 months', 'Age First': '3 months'},
        {'Vaccine': 'HS', 'Frequency': '1 year', 'Age First': '6 months'},
        {'Vaccine': 'Brucella', 'Frequency': 'One time', 'Age First': '4-8 months'},
    ],
    'Deworming Schedule': [
        {'Frequency': 'Every 3 months', 'Age': 'All animals > 3 months'},
    ],
    'Heat Detection': {
        'Method': 'Visual + Activity Monitor',
        'Automatic Alerts': True,
        'Ideal Time': '12-18 hours after onset',
    },
}

BREEDING_CONFIG = {
    'Semen Bank': [
        {'Bull ID': 'HF-001', 'Breed': 'Holstein Friesian', 'Straws Available': 50},
        {'Bull ID': 'GIR-005', 'Breed': 'Gir', 'Straws Available': 30},
    ],
    'Service Types': [
        {'Name': 'AI - Artificial Insemination', 'Cost': 500},
        {'Name': 'Natural Service', 'Cost': 0},
    ],
    'Pregnancy Check': {
        'Method': 'Ultrasound / Rectal Palpation',
        'First Check': '45-60 days post AI',
        'Confirmation': '90 days',
    },
}
```

---

## 7. SALES & CRM CONFIGURATION

### 7.1 Customer Management

```python
# Access: Sales → Customers

CUSTOMER_CATEGORIES = {
    'B2B': {
        'Distributor': 'Large volume buyers, resale to retailers',
        'Retailer': 'Shop owners, direct to consumer',
        'Institution': 'Hotels, restaurants, canteens',
        'Processor': 'Dairy processing companies',
    },
    'B2C': {
        'Individual': 'Direct consumers',
        'Subscription': 'Regular delivery customers',
    }
}

# Customer Credit Limits
# Sales → Configuration → Credit Limits
{
    'Distributor': {'Limit': 500000, 'Payment Terms': 'Net 30'},
    'Retailer': {'Limit': 100000, 'Payment Terms': 'Net 15'},
    'Institution': {'Limit': 200000, 'Payment Terms': 'Net 45'},
}
```

### 7.2 Product Catalog Setup

```python
# Access: Sales → Products

PRODUCT_CATEGORIES = [
    {
        'Category': 'Liquid Milk',
        'Products': [
            {'Name': 'Full Cream Milk', 'Fat': 6.0, 'SNF': 9.0, 'Pack': '500ml'},
            {'Name': 'Standard Milk', 'Fat': 4.5, 'SNF': 8.5, 'Pack': '500ml'},
            {'Name': 'Toned Milk', 'Fat': 3.0, 'SNF': 8.5, 'Pack': '500ml'},
            {'Name': 'Double Toned Milk', 'Fat': 1.5, 'SNF': 9.0, 'Pack': '500ml'},
        ]
    },
    {
        'Category': 'Fermented Products',
        'Products': [
            {'Name': 'Curd', 'Pack': '200g, 400g, 1kg'},
            {'Name': 'Buttermilk', 'Pack': '200ml, 500ml'},
            {'Name': 'Lassi', 'Pack': '200ml'},
        ]
    },
    {
        'Category': 'Value Added',
        'Products': [
            {'Name': 'Paneer', 'Pack': '200g'},
            {'Name': 'Ghee', 'Pack': '500ml, 1L'},
            {'Name': 'Butter', 'Pack': '100g, 500g'},
            {'Name': 'Cheese', 'Pack': '200g'},
        ]
    },
    {
        'Category': 'Frozen',
        'Products': [
            {'Name': 'Ice Cream', 'Variants': 'Vanilla, Chocolate, Strawberry'},
            {'Name': 'Kulfi', 'Variants': 'Malai, Kesar'},
        ]
    }
]

# Product Configuration
{
    'Route': 'Buy',  # or 'Manufacture' for processed products
    'Inventory Valuation': 'FIFO',
    'Control Policy': 'On ordered quantities',
    'Invoicing Policy': 'Ordered quantities',
}
```

### 7.3 Pricing & Promotions

```python
# Access: Sales → Products → [Product] → Sales Tab

# Price Lists
PRICE_LISTS = {
    'Default Public Price List': 'For B2C website',
    'Distributor Price List': '10% off retail',
    'Retailer Price List': '5% off retail',
    'Institutional Price List': 'Volume-based discount',
}

# Create Price List
Sales → Configuration → Price Lists → Create
{
    'Price List Name': 'Distributor Price List',
    'Currency': 'INR',
    'Country Groups': 'India',
    'Rules': [
        {
            'Applied On': 'All Products',
            'Compute Price': 'Formula',
            'Based On': 'Public Price',
            'Discount': 10,  # 10% discount
        }
    ]
}

# Promotions
Website → eCommerce → Promotions
{
    'Name': 'Summer Special',
    'Type': 'Percentage Discount',
    'Discount': 15,
    'Minimum Purchase': 500,
    'Valid From': '2026-04-01',
    'Valid Until': '2026-06-30',
}
```

### 7.4 B2B Portal Configuration

```python
# Access: Website → Configuration → B2B Settings

B2B_PORTAL_CONFIG = {
    'Registration': {
        'Require Approval': True,
        'Required Documents': ['GST Certificate', 'Trade License', 'ID Proof'],
        'Auto-assign Price List': 'Based on customer category',
    },
    'Ordering': {
        'Minimum Order Value': 5000,
        'Order Cutoff Time': '18:00',
        'Delivery Schedule': 'Next day delivery for orders before cutoff',
    },
    'Credit Management': {
        'Enable Credit Limit': True,
        'Credit Check': 'On order confirmation',
        'Overdue Blocking': True,
    },
    'Reports': [
        'Order History',
        'Invoice History',
        'Payment Status',
        'Statement of Account',
    ]
}
```

---

## 8. INVENTORY & PURCHASE

### 8.1 Warehouse Configuration

```python
# Access: Inventory → Configuration → Warehouses

WAREHOUSE_SETUP = {
    'Main Warehouse': {
        'Address': 'Factory Location',
        'Zones': [
            {'Name': 'Receiving', 'Type': 'Vendor'},
            {'Name': 'Raw Material', 'Type': 'Internal'},
            {'Name': 'Production', 'Type': 'Internal'},
            {'Name': 'Finished Goods', 'Type': 'Internal'},
            {'Name': 'Dispatch', 'Type': 'Customer'},
            {'Name': 'Cold Storage', 'Type': 'Internal', 'Temp': '2-4°C'},
            {'Name': 'Deep Freeze', 'Type': 'Internal', 'Temp': '-18°C'},
        ],
        'Operation Types': [
            'Receipt',
            'Internal Transfer',
            'Delivery Orders',
            'Manufacturing',
            'Returns',
        ]
    },
    'Retail Stores': {
        'Type': 'Retail Location',
        'Auto-replenishment': True,
        'Min Stock Days': 2,
    }
}

# Location Configuration
# Inventory → Configuration → Locations
{
    'Location Name': 'Cold Storage - Finished Goods',
    'Location Type': 'Internal Location',
    'Parent Location': 'WH/Stock',
    'Storage Category': 'Temperature Controlled',
    'Temperature': '2-4°C',
}
```

### 8.2 Reordering Rules

```python
# Access: Inventory → Configuration → Reordering Rules

REORDER_RULES = [
    {
        'Product': 'Full Cream Milk (500ml)',
        'Location': 'WH/Stock',
        'Minimum Quantity': 1000,  # units
        'Maximum Quantity': 5000,
        'Quantity Multiple': 100,
        'Lead Time': 1,  # days
    },
    {
        'Product': 'Paneer (200g)',
        'Location': 'WH/Cold Storage',
        'Minimum Quantity': 200,
        'Maximum Quantity': 1000,
        'Lead Time': 2,
    },
    {
        'Product': 'Ice Cream - Vanilla',
        'Location': 'WH/Deep Freeze',
        'Minimum Quantity': 100,
        'Maximum Quantity': 500,
        'Lead Time': 3,
    }
]

# Automated Reorder
# Inventory → Operations → Run Scheduler (or automated via cron)
```

### 8.3 Vendor Management

```python
# Access: Purchase → Vendors

VENDOR_CATEGORIES = {
    'Feed Suppliers': ['Cattle Feed', 'Mineral Mixture', 'Supplements'],
    'Medicine Suppliers': ['Vaccines', 'Medicines', 'Veterinary Equipment'],
    'Packaging Suppliers': ['Milk Pouches', 'Containers', 'Labels'],
    'Equipment Suppliers': ['Milking Equipment', 'Cold Storage Equipment'],
}

# Vendor Evaluation
{
    'On-Time Delivery': 40%,
    'Quality Rating': 30%,
    'Price Competitiveness': 20%,
    'Service Rating': 10%,
}
```

---

## 9. ACCOUNTING CONFIGURATION

### 9.1 Chart of Accounts

```python
# Access: Accounting → Configuration → Chart of Accounts

# India-specific Chart of Accounts (GST Compliant)
CHART_OF_ACCOUNTS = {
    # Assets
    'Current Assets': [
        {'Code': '100010', 'Name': 'Cash', 'Type': 'Bank and Cash'},
        {'Code': '100020', 'Name': 'Bank Account', 'Type': 'Bank and Cash'},
        {'Code': '110010', 'Name': 'Accounts Receivable', 'Type': 'Receivable'},
        {'Code': '120010', 'Name': 'Inventory - Raw Material', 'Type': 'Current Assets'},
        {'Code': '120020', 'Name': 'Inventory - Finished Goods', 'Type': 'Current Assets'},
    ],
    # Liabilities
    'Current Liabilities': [
        {'Code': '200010', 'Name': 'Accounts Payable', 'Type': 'Payable'},
        {'Code': '210010', 'Name': 'GST Output Tax', 'Type': 'Current Liabilities'},
        {'Code': '210020', 'Name': 'GST Input Tax', 'Type': 'Current Liabilities'},
        {'Code': '220010', 'Name': 'TDS Payable', 'Type': 'Current Liabilities'},
    ],
    # Income
    'Income': [
        {'Code': '400010', 'Name': 'Milk Sales - B2B', 'Type': 'Income'},
        {'Code': '400020', 'Name': 'Milk Sales - B2C', 'Type': 'Income'},
        {'Code': '400030', 'Name': 'Product Sales - Fermented', 'Type': 'Income'},
        {'Code': '400040', 'Name': 'Product Sales - Value Added', 'Type': 'Income'},
    ],
    # Expenses
    'Expenses': [
        {'Code': '500010', 'Name': 'Cattle Feed', 'Type': 'Expenses'},
        {'Code': '500020', 'Name': 'Veterinary Expenses', 'Type': 'Expenses'},
        {'Code': '500030', 'Name': 'Labor - Farm', 'Type': 'Expenses'},
        {'Code': '500040', 'Name': 'Labor - Processing', 'Type': 'Expenses'},
        {'Code': '500050', 'Name': 'Electricity', 'Type': 'Expenses'},
        {'Code': '500060', 'Name': 'Packaging Material', 'Type': 'Expenses'},
    ]
}
```

### 9.2 GST Configuration

```python
# Access: Accounting → Configuration → Settings → Taxes

GST_CONFIG = {
    'Company GSTIN': '27AABCU9603R1ZM',
    'GST Tax Rates': [
        {'Rate': '0%', 'Type': 'Exempt', 'Products': 'Fresh milk, Curd'},
        {'Rate': '5%', 'Type': 'CGST 2.5% + SGST 2.5%', 'Products': 'Skimmed milk, Cream'},
        {'Rate': '12%', 'Type': 'CGST 6% + SGST 6%', 'Products': 'Cheese, Butter, Ghee'},
        {'Rate': '18%', 'Type': 'CGST 9% + SGST 9%', 'Products': 'Ice cream, Flavored milk'},
    ],
    'HSN Codes': {
        '0401': 'Milk and cream, not concentrated',
        '0403': 'Buttermilk, curdled milk, cream, yogurt',
        '0405': 'Butter and other fats derived from milk',
        '0406': 'Cheese and curd',
    }
}

# GST Return Configuration
# Accounting → GST → GST Returns
{
    'GSTR-1 (Outward Supplies)': 'Monthly, Due 11th of next month',
    'GSTR-3B (Summary Return)': 'Monthly, Due 20th of next month',
    'GSTR-9 (Annual Return)': 'Yearly, Due 31st Dec of next FY',
}
```

### 9.3 Payment Terms

```python
# Access: Accounting → Configuration → Payment Terms

PAYMENT_TERMS = [
    {
        'Name': 'Immediate Payment',
        'Description': 'Payment due immediately',
    },
    {
        'Name': 'Net 15',
        'Description': 'Payment due within 15 days',
        'Terms': [{'Days': 15, 'Percent': 100}],
    },
    {
        'Name': 'Net 30',
        'Description': 'Payment due within 30 days',
        'Terms': [{'Days': 30, 'Percent': 100}],
    },
    {
        'Name': '50% Advance, 50% Net 15',
        'Description': '50% advance, balance in 15 days',
        'Terms': [
            {'Days': 0, 'Percent': 50},
            {'Days': 15, 'Percent': 50},
        ],
    },
]
```

---

## 10. WEBSITE & E-COMMERCE

### 10.1 Website Configuration

```python
# Access: Website → Configuration → Settings

WEBSITE_CONFIG = {
    'Website Name': 'Smart Dairy Store',
    'Domain': 'store.smartdairy.com',
    'Default Language': 'English',
    'Additional Languages': ['Hindi', 'Marathi'],
    
    'Company Information': {
        'Name': 'Smart Dairy Ltd.',
        'Address': 'Complete address',
        'Phone': '+91-xxx-xxx-xxxx',
        'Email': 'support@smartdairy.com',
    },
    
    'Social Media': {
        'Facebook': 'https://facebook.com/smartdairy',
        'Instagram': 'https://instagram.com/smartdairy',
        'Twitter': 'https://twitter.com/smartdairy',
    }
}
```

### 10.2 Delivery Configuration

```python
# Access: Website → eCommerce → Delivery Methods

DELIVERY_METHODS = [
    {
        'Name': 'Standard Delivery',
        'Provider': 'Internal Fleet',
        'Price': 30,
        'Free Over': 500,
        'Delivery Time': '1-2 days',
        'Coverage': 'Mumbai, Pune, Thane',
    },
    {
        'Name': 'Express Delivery',
        'Provider': 'Internal Fleet',
        'Price': 60,
        'Free Over': 1000,
        'Delivery Time': 'Same day (order before 12 PM)',
        'Coverage': 'Mumbai Only',
    },
    {
        'Name': 'Subscription Delivery',
        'Provider': 'Internal Fleet',
        'Price': 0,
        'Delivery Time': 'Daily fixed slot',
        'Coverage': 'All service areas',
    }
]
```

### 10.3 Payment Gateways

```python
# Access: Website → eCommerce → Payment Providers

PAYMENT_PROVIDERS = {
    'Razorpay': {
        'Mode': 'Live',
        'Key ID': 'rzp_live_xxxx',
        'Supported Methods': ['Credit Card', 'Debit Card', 'UPI', 'Net Banking', 'Wallets'],
        'Transaction Fee': '2% + GST',
        'Auto-capture': True,
    },
    'Cash on Delivery': {
        'Available': True,
        'Maximum Amount': 5000,
        'Extra Fee': 0,
    },
    'UPI (QR Code)': {
        'UPI ID': 'smartdairy@upi',
        'Auto-reconciliation': True,
    }
}
```

---

## 11. MAINTENANCE & TROUBLESHOOTING

### 11.1 Database Maintenance

```python
# Regular Maintenance Tasks

# 1. Vacuum Database
# Settings → Technical → Scheduled Actions → Run "AutoVacuum"

# 2. Clear Transient Data
./odoo-bin shell -d smart_dairy_prod << 'EOF'
# Clear old transient records
env['mail.mail'].search([('state', '=', 'sent'), ('date', '<', fields.Datetime.now() - timedelta(days=30))]).unlink()
env['ir.attachment'].search([('res_model', '=', 'mail.compose.message'), ('create_date', '<', fields.Datetime.now() - timedelta(days=7))]).unlink()
env.cr.commit()
EOF

# 3. Regenerate Asset Bundles
# Developer Mode → Debug menu → Regenerate Asset Bundles

# 4. Update Translations
Settings → Translations → Load a Translation → Update Terms

# 5. Check Filestore Consistency
./odoo-bin shell -d smart_dairy_prod << 'EOF'
# Check for orphaned attachments
orphaned = env['ir.attachment'].search([('res_model', '=', False), ('res_id', '=', 0), ('create_date', '<', fields.Datetime.now() - timedelta(days=7))])
print(f"Found {len(orphaned)} orphaned attachments")
# orphaned.unlink()  # Uncomment to delete
EOF
```

### 11.2 Performance Optimization

```python
# Performance Settings

# 1. Worker Configuration (odoo.conf)
{
    'workers': 8,  # (2 * CPU cores) + 1
    'max_cron_threads': 2,
    'limit_memory_soft': 2147483648,  # 2GB
    'limit_memory_hard': 2684354560,  # 2.5GB
    'limit_request': 8192,
    'limit_time_cpu': 60,
    'limit_time_real': 120,
    'limit_time_real_cron': 300,
}

# 2. Database Connection Pooling
# Use PgBouncer as documented in infrastructure guide

# 3. Enable CDN for Static Assets
# Settings → Configuration → CDN
{
    'CDN Enabled': True,
    'CDN Base URL': 'https://cdn.smartdairy.com',
}

# 4. Optimize Queries
# - Add appropriate indexes (see Database Performance Tuning Guide)
# - Use read-only cursors for reports
# - Implement materialized views for complex aggregations
```

### 11.3 Common Issues & Solutions

| Issue | Symptom | Solution |
|-------|---------|----------|
| **Slow Page Load** | > 5s response | Check asset bundles, enable CDN, review slow queries |
| **Session Timeout** | Frequent login prompts | Increase session timeout in system parameters |
| **Email Not Sending** | Mails stuck in queue | Check outgoing mail server settings, SMTP credentials |
| **Import Fail** | Large imports timeout | Use smaller batches, increase timeout limits |
| **Attachment Not Opening** | 404 errors | Verify S3 credentials, check filestore configuration |
| **Report Generation Fail** | Timeout on large reports | Use background processing, split date ranges |
| **PDF Rendering Issues** | Wkhtmltopdf errors | Verify wkhtmltopdf installation, check font paths |

### 11.4 Debug Mode Tools

```python
# Activate Developer Mode, then access:

# 1. View Fields
# Any form → Debug menu → View Fields
# Shows all fields with technical names and values

# 2. Manage Filters
# Any list view → Favorites → Manage Filters
# Create, edit, delete saved filters

# 3. Technical Menu
# Settings → Technical
# - Database Structure: Models, Fields, Views
# - Automation: Scheduled Actions, Automated Actions
# - Email: Templates, Server
# - User Interface: Views, Actions, Menu Items

# 4. Record Metadata
# Any record → Debug menu → View Metadata
# Shows: External ID, Created by, Modified by, XML ID

# 5. Edit View: Form
# Any form → Debug menu → Edit View: Form
# Direct XML editing of views
```

---

## 12. APPENDICES

### Appendix A: Quick Reference Commands

```bash
# Shell access to Odoo
./odoo-bin shell -d smart_dairy_prod

# Update module list
./odoo-bin -d smart_dairy_prod -u all --stop-after-init

# Create database backup (via Odoo)
./odoo-bin -d smart_dairy_prod --backup --backup-dir=/backups/

# List installed modules
./odoo-bin shell -d smart_dairy_prod -c "print(env['ir.module.module'].search([('state', '=', 'installed')]).mapped('name'))"

# Reset user password
./odoo-bin shell -d smart_dairy_prod << 'EOF'
user = env['res.users'].search([('login', '=', 'user@example.com')])
user.write({'password': 'newpassword123'})
env.cr.commit()
EOF
```

### Appendix B: Database Queries for Admins

```sql
-- Count records by model
SELECT model, count(*) FROM ir_model_data GROUP BY model ORDER BY count(*) DESC;

-- Find largest tables
SELECT schemaname, tablename, pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename))
FROM pg_tables
ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC;

-- List active sessions
SELECT usename, application_name, state, query_start, query
FROM pg_stat_activity
WHERE state = 'active';

-- Check Odoo cron job status
SELECT id, name, active, interval_number, interval_type, nextcall
FROM ir_cron
ORDER BY nextcall;
```

### Appendix C: Odoo Configuration File Template

```ini
; /etc/odoo/odoo.conf
[options]
addons_path = /opt/odoo/addons,/opt/odoo/custom-addons
data_dir = /var/lib/odoo

; Database
db_host = smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com
db_port = 5432
db_user = odoo
db_password = xxx
db_name = smart_dairy_prod
db_sslmode = require

; Performance
workers = 8
max_cron_threads = 2
limit_memory_soft = 2147483648
limit_memory_hard = 2684354560
limit_request = 8192
limit_time_cpu = 60
limit_time_real = 120
limit_time_real_cron = 300

; Logging
logfile = /var/log/odoo/odoo.log
log_level = info
log_rotate = True

; Security
admin_passwd = xxx
list_db = False

; S3 Filestore (via custom module)
; s3_bucket = smart-dairy-odoo-filestore
; s3_region = ap-south-1

; Email
smtp_server = email-smtp.ap-south-1.amazonaws.com
smtp_port = 587
smtp_ssl = True
smtp_user = xxx
smtp_password = xxx
```

---

**END OF ODOO ADMINISTRATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | October 25, 2026 | Technical Writer | Initial version |
