# SMART DAIRY LTD.
## HISTORICAL DATA MIGRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-006 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Data Migration Specialist |
| **Owner** | Data Architect |
| **Reviewer** | Project Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Migration Scope](#2-migration-scope)
3. [Source System Analysis](#3-source-system-analysis)
4. [Data Extraction Procedures](#4-data-extraction-procedures)
5. [Data Transformation Rules](#5-data-transformation-rules)
6. [Data Loading Procedures](#6-data-loading-procedures)
7. [Special Migration Scenarios](#7-special-migration-scenarios)
8. [Validation & Reconciliation](#8-validation--reconciliation)
9. [Troubleshooting](#9-troubleshooting)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide provides detailed procedures for migrating historical data from legacy systems into the Smart Dairy ERP. It covers extraction, transformation, and loading (ETL) processes specific to historical data migration.

### 1.2 Historical Data Scope

```
┌─────────────────────────────────────────────────────────────────┐
│                    HISTORICAL DATA SCOPE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DATA RETENTION REQUIREMENTS                                     │
│  ├── Financial Data: 7 years (Bangladesh Tax Law)               │
│  ├── Sales Records: 5 years (Business Policy)                   │
│  ├── Farm Production: 3 years (Operational Analysis)            │
│  ├── IoT Sensor Data: 2 years (TimescaleDB retention)           │
│  └── Audit Logs: 7 years (Compliance)                           │
│                                                                  │
│  ESTIMATED VOLUMES                                               │
│  ├── Sales Orders: ~50,000 records (2 years)                    │
│  ├── Financial Transactions: ~100,000 records (2 years)         │
│  ├── Farm Records: ~50,000 records (2 years)                    │
│  └── Total Size: ~10 GB                                         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Migration Principles

| Principle | Description | Implementation |
|-----------|-------------|----------------|
| **Audit Trail** | Maintain complete migration log | Migration ID per record |
| **Reversibility** | Ability to rollback changes | Pre-migration backup |
| **Validation** | Verify data integrity at each step | Checksum comparison |
| **Minimal Disruption** | Migrate during low-activity windows | Scheduled batches |

---

## 2. MIGRATION SCOPE

### 2.1 Data Categories

| Category | Source Systems | Target Tables | Volume | Priority |
|----------|----------------|---------------|--------|----------|
| **Master Data** | Excel, MySQL | core.res_partner, product_template | Medium | Critical |
| **Sales History** | MySQL, Excel | sales.order, sales.order_line | High | High |
| **Financial Data** | Accounting Software | accounting.move, accounting.move_line | High | Critical |
| **Farm Records** | Mobile App, Excel | farm.animal, farm.milk_production | Medium | High |
| **Inventory** | Excel, Manual | inventory.quant, stock_move | Low | Medium |

### 2.2 Migration Waves

```
┌─────────────────────────────────────────────────────────────────┐
│                    MIGRATION WAVE SCHEDULE                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  WAVE 1: REFERENCE DATA (Week 1-2)                              │
│  ├── Units of Measure                                           │
│  ├── Product Categories                                         │
│  ├── Chart of Accounts                                          │
│  ├── Tax Configuration                                          │
│  └── Payment Terms                                              │
│                                                                  │
│  WAVE 2: MASTER DATA (Week 3-4)                                 │
│  ├── Partners (Customers, Suppliers)                            │
│  ├── Products                                                   │
│  ├── Warehouses & Locations                                     │
│  └── Animal Breeds & Barns                                      │
│                                                                  │
│  WAVE 3: OPEN TRANSACTIONS (Week 5-6)                           │
│  ├── Open Sales Orders                                          │
│  ├── Draft Invoices                                             │
│  ├── Pending Deliveries                                         │
│  └── Active Animals                                             │
│                                                                  │
│  WAVE 4: HISTORICAL DATA (Week 7-10)                            │
│  ├── Historical Sales (2 years)                                 │
│  ├── Historical Financial Data                                  │
│  ├── Farm Production History                                    │
│  └── Inventory Movements                                        │
│                                                                  │
│  WAVE 5: AUDIT & RECONCILIATION (Week 11-12)                    │
│  ├── Data Validation                                            │
│  ├── Reconciliation Reports                                     │
│  └── Exception Resolution                                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. SOURCE SYSTEM ANALYSIS

### 3.1 Source System Inventory

| System | Type | Format | Access Method | Data Quality |
|--------|------|--------|---------------|--------------|
| **Legacy Website DB** | MySQL 5.7 | SQL | Direct SQL | Good (85%) |
| **Accounting Software** | Desktop | Export files | CSV/Excel | Good (90%) |
| **Farm Management App** | SQLite | SQL | File export | Medium (75%) |
| **Excel Files** | Spreadsheets | XLSX | File read | Poor (60%) |
| **Paper Records** | Physical | Scanned PDF | OCR/Manual | Poor (50%) |

### 3.2 Source Schema Mapping

#### 3.2.1 Legacy Customers (MySQL) → res_partner

```sql
-- Legacy customer table structure
CREATE TABLE legacy.customers (
    customer_id INT PRIMARY KEY,
    customer_name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    mobile VARCHAR(50),
    address TEXT,
    city VARCHAR(100),
    customer_type ENUM('retail', 'wholesale'),
    credit_limit DECIMAL(15,2),
    is_active BOOLEAN,
    created_date DATETIME,
    modified_date DATETIME
);
```

| Source Field | Target Field | Transformation |
|--------------|--------------|----------------|
| customer_id | legacy_id | Store for reference |
| customer_name | name | Trim, proper case |
| email | email | Lowercase, validate format |
| phone | phone | Format to BD standard |
| mobile | mobile | Format: +880XXXXXXXXXX |
| address | street, street2 | Parse multiline |
| city | city | Validate against list |
| customer_type | customer_type | 'retail'→'b2c', 'wholesale'→'b2b' |
| credit_limit | credit_limit | Direct mapping |
| is_active | active | Direct mapping |
| created_date | created_at | Timezone conversion |

#### 3.2.2 Legacy Products (MySQL) → product_template

```sql
-- Legacy product table structure
CREATE TABLE legacy.products (
    product_id INT PRIMARY KEY,
    product_code VARCHAR(50),
    product_name VARCHAR(255),
    description TEXT,
    category_id INT,
    unit_price DECIMAL(15,2),
    cost_price DECIMAL(15,2),
    unit VARCHAR(50),
    is_active BOOLEAN,
    stock_quantity DECIMAL(10,2)
);
```

---

## 4. DATA EXTRACTION PROCEDURES

### 4.1 Extraction Strategy

```python
# Historical data extraction framework
from datetime import datetime, timedelta
import pandas as pd
from sqlalchemy import create_engine

class HistoricalDataExtractor:
    """Extract data from legacy systems"""
    
    def __init__(self, source_config):
        self.source_config = source_config
        self.extraction_log = []
    
    def extract_mysql_data(self, query, date_range):
        """Extract data from MySQL source"""
        engine = create_engine(
            f"mysql+pymysql://{self.source_config['user']}:{self.source_config['password']}"
            f"@{self.source_config['host']}/{self.source_config['database']}"
        )
        
        # Add date filter to query
        filtered_query = f"""
            {query}
            WHERE created_date BETWEEN '{date_range['start']}' AND '{date_range['end']}'
        """
        
        df = pd.read_sql(filtered_query, engine)
        
        # Generate extraction checksum
        checksum = self.calculate_checksum(df)
        
        self.extraction_log.append({
            'timestamp': datetime.now(),
            'source': 'mysql',
            'records': len(df),
            'checksum': checksum,
            'date_range': date_range
        })
        
        return df
    
    def extract_excel_data(self, file_path, sheet_name=0):
        """Extract data from Excel files"""
        df = pd.read_excel(file_path, sheet_name=sheet_name)
        
        # Clean column names
        df.columns = df.columns.str.lower().str.replace(' ', '_')
        
        # Remove empty rows
        df = df.dropna(how='all')
        
        return df
    
    def extract_batch(self, table, batch_size=10000, date_column='created_date'):
        """Extract data in batches for large tables"""
        offset = 0
        total_extracted = 0
        
        while True:
            query = f"""
                SELECT * FROM {table}
                ORDER BY {date_column}
                LIMIT {batch_size} OFFSET {offset}
            """
            
            batch = self.extract_mysql_data(query, {})
            
            if batch.empty:
                break
            
            yield batch
            
            offset += batch_size
            total_extracted += len(batch)
            
            print(f"Extracted {total_extracted} records...")
    
    def calculate_checksum(self, df):
        """Calculate MD5 checksum of dataframe"""
        import hashlib
        return hashlib.md5(
            pd.util.hash_pandas_object(df).values.tobytes()
        ).hexdigest()
```

### 4.2 MySQL Extraction Scripts

```sql
-- Extract customers with data quality flags
SELECT 
    c.customer_id,
    TRIM(c.customer_name) as customer_name,
    LOWER(TRIM(c.email)) as email,
    CASE 
        WHEN c.mobile LIKE '01%' THEN CONCAT('+880', SUBSTRING(c.mobile, 2))
        WHEN c.mobile LIKE '880%' THEN CONCAT('+', c.mobile)
        WHEN c.mobile LIKE '+880%' THEN c.mobile
        ELSE NULL
    END as mobile_formatted,
    c.mobile as mobile_original,
    CASE 
        WHEN c.email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$' THEN 'VALID'
        WHEN c.email IS NULL THEN 'NULL'
        ELSE 'INVALID'
    END as email_quality,
    CASE 
        WHEN c.mobile IS NULL THEN 'NULL'
        WHEN c.mobile REGEXP '^(\+880|880|01)[0-9]{9,10}$' THEN 'VALID'
        ELSE 'INVALID'
    END as mobile_quality,
    c.customer_type,
    c.credit_limit,
    c.is_active,
    c.created_date,
    c.modified_date,
    'legacy_mysql' as source_system,
    CONCAT('CUST-', c.customer_id) as migration_reference
FROM legacy.customers c
WHERE c.is_active = 1
ORDER BY c.customer_id;
```

### 4.3 Excel Data Extraction

```python
# Excel data extraction with validation
def extract_farm_records(excel_path):
    """Extract farm records from Excel with validation"""
    
    df = pd.read_excel(excel_path)
    
    # Standardize column names
    column_mapping = {
        'Tag No': 'rfid_tag',
        'Animal ID': 'ear_tag',
        'Breed': 'breed_name',
        'Gender': 'gender',
        'Date of Birth': 'birth_date',
        'Purchase Date': 'purchase_date',
        'Purchase Price': 'purchase_price',
        'Current Status': 'status'
    }
    
    df = df.rename(columns=column_mapping)
    
    # Data cleaning
    df['rfid_tag'] = df['rfid_tag'].astype(str).str.strip().str.upper()
    df['ear_tag'] = df['ear_tag'].astype(str).str.strip().str.upper()
    df['gender'] = df['gender'].str.lower().str.strip()
    df['breed_name'] = df['breed_name'].str.strip().str.title()
    
    # Validation flags
    df['validation_status'] = 'VALID'
    df.loc[df['rfid_tag'].duplicated(), 'validation_status'] = 'DUPLICATE_RFID'
    df.loc[~df['gender'].isin(['male', 'female', 'm', 'f']), 'validation_status'] = 'INVALID_GENDER'
    df.loc[df['birth_date'] > datetime.now(), 'validation_status'] = 'FUTURE_DATE'
    
    return df
```

---

## 5. DATA TRANSFORMATION RULES

### 5.1 Transformation Framework

```python
class DataTransformer:
    """Transform legacy data to target format"""
    
    def __init__(self, mapping_rules):
        self.mapping_rules = mapping_rules
        self.transformation_log = []
    
    def transform_customers(self, df):
        """Transform customer data"""
        
        transformed = pd.DataFrame()
        
        # Direct mappings
        transformed['name'] = df['customer_name'].str.title().str.strip()
        transformed['email'] = df['email']
        transformed['mobile'] = df['mobile_formatted']
        transformed['phone'] = df['phone']
        
        # Value mappings
        type_mapping = {
            'retail': 'b2c',
            'wholesale': 'b2b',
            'farmer': 'farmer',
            'supplier': 'supplier'
        }
        transformed['customer_type'] = df['customer_type'].map(type_mapping)
        
        # Default values
        transformed['is_company'] = transformed['customer_type'] == 'b2b'
        transformed['is_customer'] = transformed['customer_type'].isin(['b2c', 'b2b'])
        transformed['is_supplier'] = transformed['customer_type'] == 'supplier'
        transformed['active'] = df['is_active']
        
        # Financial
        transformed['credit_limit'] = df['credit_limit'].fillna(0)
        transformed['currency_id'] = 1  # BDT
        
        # Address parsing
        address_parts = df['address'].str.split('\n', expand=True)
        transformed['street'] = address_parts[0]
        transformed['street2'] = address_parts[1] if len(address_parts.columns) > 1 else None
        
        # Metadata
        transformed['country_id'] = 19  # Bangladesh
        transformed['created_at'] = pd.to_datetime(df['created_date'])
        transformed['legacy_id'] = df['customer_id']
        transformed['migration_reference'] = df['migration_reference']
        transformed['source_system'] = df['source_system']
        
        return transformed
    
    def transform_sales_orders(self, df):
        """Transform sales order data"""
        
        transformed = pd.DataFrame()
        
        # Generate order name (legacy format preserved)
        transformed['name'] = df['order_number'].apply(
            lambda x: f"SO-LEGACY-{x}"
        )
        
        # Partner lookup (requires mapping table)
        transformed['partner_id'] = df['customer_id'].map(self.customer_mapping)
        
        # Dates
        transformed['order_date'] = pd.to_datetime(df['order_date']).dt.date
        transformed['confirmation_date'] = pd.to_datetime(df['confirmed_date'])
        
        # Status mapping
        status_mapping = {
            'pending': 'draft',
            'confirmed': 'sale',
            'shipped': 'done',
            'cancelled': 'cancel'
        }
        transformed['state'] = df['status'].map(status_mapping)
        
        # Order type detection
        transformed['order_type'] = df.apply(
            lambda row: 'subscription' if row.get('is_recurring') else 
                       ('b2b' if row.get('customer_type') == 'wholesale' else 'b2c'),
            axis=1
        )
        
        # Financial amounts
        transformed['amount_untaxed'] = df['subtotal']
        transformed['amount_tax'] = df['tax_amount']
        transformed['amount_total'] = df['total_amount']
        transformed['currency_id'] = 1  # BDT
        
        # Metadata
        transformed['legacy_order_id'] = df['order_id']
        transformed['source_system'] = 'legacy_mysql'
        
        return transformed
```

### 5.2 Field-Level Transformations

| Field Type | Transformation Rule | Example |
|------------|---------------------|---------|
| **Name** | Title case, trim whitespace | "john doe" → "John Doe" |
| **Phone** | Standardize to +880 format | "01712345678" → "+8801712345678" |
| **Email** | Lowercase, validate format | "John@Email.COM" → "john@email.com" |
| **Date** | Convert to ISO format, set timezone | "01/31/2024" → "2024-01-31" |
| **Amount** | Decimal with 2 places, BDT currency | "1500.5" → "1500.50" |
| **Status** | Map to standard values | "Pending" → "draft" |
| **Boolean** | Standardize to TRUE/FALSE | "yes"/"1"/"true" → TRUE |

### 5.3 Data Cleansing Rules

```python
# Data cleansing functions
def clean_phone_number(phone):
    """Standardize Bangladesh phone numbers"""
    if pd.isna(phone) or phone == '':
        return None
    
    # Remove all non-digit characters except +
    cleaned = re.sub(r'[^\d+]', '', str(phone))
    
    # Handle various formats
    if cleaned.startswith('+880'):
        return cleaned
    elif cleaned.startswith('880'):
        return '+' + cleaned
    elif cleaned.startswith('01'):
        return '+88' + cleaned
    elif cleaned.startswith('1'):
        return '+880' + cleaned
    else:
        return None  # Invalid format

def clean_email(email):
    """Validate and clean email addresses"""
    if pd.isna(email) or email == '':
        return None
    
    email = str(email).lower().strip()
    
    # Basic validation
    pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    if re.match(pattern, email):
        return email
    else:
        return None

def parse_address(address_text):
    """Parse address into structured components"""
    if pd.isna(address_text):
        return {'street': None, 'street2': None, 'city': None, 'zip': None}
    
    lines = [line.strip() for line in str(address_text).split('\n') if line.strip()]
    
    result = {
        'street': lines[0] if len(lines) > 0 else None,
        'street2': lines[1] if len(lines) > 1 else None,
        'city': None,
        'zip': None
    }
    
    # Try to extract city from last line
    if lines:
        last_line = lines[-1]
        # Common Bangladesh cities
        cities = ['Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 
                  'Barisal', 'Rangpur', 'Comilla', 'Narayanganj', 'Gazipur']
        for city in cities:
            if city.lower() in last_line.lower():
                result['city'] = city
                break
    
    return result
```

---

## 6. DATA LOADING PROCEDURES

### 6.1 Loading Strategy

```python
class DataLoader:
    """Load transformed data into target system"""
    
    def __init__(self, odoo_connection):
        self.odoo = odoo_connection
        self.batch_size = 1000
    
    def load_partners_batch(self, df, batch_size=1000):
        """Load partners in batches"""
        
        total_records = len(df)
        loaded_count = 0
        error_records = []
        
        for i in range(0, total_records, batch_size):
            batch = df.iloc[i:i+batch_size]
            
            for _, record in batch.iterrows():
                try:
                    # Check for duplicates before loading
                    if self.check_duplicate_partner(record):
                        error_records.append({
                            'record': record.to_dict(),
                            'error': 'Duplicate partner detected',
                            'action': 'skipped'
                        })
                        continue
                    
                    # Create partner via Odoo API
                    partner_id = self.odoo.create('res.partner', {
                        'name': record['name'],
                        'email': record['email'],
                        'mobile': record['mobile'],
                        'phone': record['phone'],
                        'customer_type': record['customer_type'],
                        'is_company': record['is_company'],
                        'street': record['street'],
                        'city': record['city'],
                        'country_id': record['country_id'],
                        'credit_limit': record['credit_limit'],
                        'active': record['active'],
                        'x_legacy_id': record['legacy_id'],
                        'x_migration_reference': record['migration_reference'],
                        'x_source_system': record['source_system']
                    })
                    
                    # Update mapping table
                    self.update_mapping('partner', record['legacy_id'], partner_id)
                    
                    loaded_count += 1
                    
                except Exception as e:
                    error_records.append({
                        'record': record.to_dict(),
                        'error': str(e),
                        'action': 'failed'
                    })
            
            print(f"Progress: {loaded_count}/{total_records} records loaded")
        
        # Save error log
        if error_records:
            pd.DataFrame(error_records).to_csv(
                'partner_migration_errors.csv', index=False
            )
        
        return {
            'total': total_records,
            'loaded': loaded_count,
            'errors': len(error_records),
            'success_rate': loaded_count / total_records * 100
        }
    
    def load_historical_orders(self, df):
        """Load historical sales orders with state preservation"""
        
        # Sort by date to maintain chronological order
        df = df.sort_values('order_date')
        
        for _, order in df.iterrows():
            # Create order in draft state first
            order_id = self.odoo.create('sale.order', {
                'name': order['name'],
                'partner_id': order['partner_id'],
                'order_date': order['order_date'],
                'order_type': order['order_type'],
                'amount_untaxed': order['amount_untaxed'],
                'amount_tax': order['amount_tax'],
                'amount_total': order['amount_total'],
                'state': 'draft',  # Start as draft
                'x_legacy_order_id': order['legacy_order_id'],
                'x_source_system': order['source_system']
            })
            
            # Transition to historical state
            if order['state'] == 'sale':
                self.odoo.execute('sale.order', 'action_confirm', [order_id])
            elif order['state'] == 'done':
                self.odoo.execute('sale.order', 'action_confirm', [order_id])
                self.odoo.execute('sale.order', 'action_done', [order_id])
            elif order['state'] == 'cancel':
                self.odoo.execute('sale.order', 'action_cancel', [order_id])
```

### 6.2 Direct SQL Loading (for bulk operations)

```sql
-- Bulk insert partners with conflict handling
INSERT INTO core.res_partner (
    name, email, mobile, phone, customer_type,
    is_company, is_customer, is_supplier, active,
    street, city, country_id, credit_limit,
    created_at, x_legacy_id, x_source_system
)
SELECT 
    t.name,
    t.email,
    t.mobile,
    t.phone,
    t.customer_type,
    t.is_company,
    t.is_customer,
    t.is_supplier,
    t.active,
    t.street,
    t.city,
    t.country_id,
    t.credit_limit,
    t.created_at,
    t.legacy_id,
    t.source_system
FROM _temp_partners t
WHERE NOT EXISTS (
    SELECT 1 FROM core.res_partner p 
    WHERE (t.email IS NOT NULL AND p.email = t.email)
       OR (t.mobile IS NOT NULL AND p.mobile = t.mobile)
)
ON CONFLICT (email) WHERE email IS NOT NULL DO NOTHING
RETURNING id, x_legacy_id;

-- Create mapping table for loaded records
INSERT INTO _migration_mapping (source_system, legacy_id, new_id, entity_type, migrated_at)
SELECT 
    'legacy_mysql',
    legacy_id,
    id,
    'res.partner',
    NOW()
FROM core.res_partner
WHERE x_source_system = 'legacy_mysql'
  AND created_at > NOW() - INTERVAL '1 hour';
```

---

## 7. SPECIAL MIGRATION SCENARIOS

### 7.1 Animal Pedigree Migration

```python
def migrate_animal_pedigree(self, animals_df, pedigree_df):
    """Migrate animals with parent-child relationships"""
    
    # First pass: Create all animals without parent references
    animal_mapping = {}
    
    for _, animal in animals_df.iterrows():
        animal_id = self.create_animal({
            'name': animal['ear_tag'],
            'rfid_tag': animal['rfid_tag'],
            'breed_id': self.get_breed_id(animal['breed']),
            'gender': animal['gender'],
            'birth_date': animal['birth_date'],
            'status': self.map_status(animal['current_status']),
            # Parent IDs set to NULL initially
        })
        
        animal_mapping[animal['legacy_id']] = animal_id
    
    # Second pass: Update parent references
    for _, pedigree in pedigree_df.iterrows():
        animal_id = animal_mapping.get(pedigree['animal_id'])
        mother_id = animal_mapping.get(pedigree['mother_id'])
        father_id = animal_mapping.get(pedigree['father_id'])
        
        if animal_id:
            self.update_animal(animal_id, {
                'mother_id': mother_id,
                'father_id': father_id
            })
```

### 7.2 Financial Data with Audit Trail

```python
def migrate_financial_transactions(self, transactions_df):
    """Migrate financial data with complete audit trail"""
    
    for _, txn in transactions_df.iterrows():
        # Create move with original dates preserved
        move_id = self.odoo.create('account.move', {
            'name': f"MIGRATED-{txn['voucher_number']}",
            'ref': txn['description'],
            'move_type': self.map_move_type(txn['transaction_type']),
            'date': txn['transaction_date'],
            'invoice_date': txn['invoice_date'],
            'state': 'posted',  # Post immediately for historical data
            'amount_total': txn['amount'],
            'x_legacy_voucher_id': txn['voucher_id'],
            'x_migration_batch': self.batch_id
        })
        
        # Create move lines
        for line in txn['lines']:
            self.odoo.create('account.move.line', {
                'move_id': move_id,
                'account_id': self.get_account_mapping(line['account_code']),
                'partner_id': self.get_partner_mapping(line['party_id']),
                'name': line['description'],
                'debit': line['debit'],
                'credit': line['credit'],
                'date': txn['transaction_date']
            })
        
        # Post the move (bypass normal validation for historical data)
        self.odoo.execute('account.move', 'action_post', [[move_id]])
```

### 7.3 IoT Historical Data Migration

```python
def migrate_iot_sensor_data(self, sensor_files):
    """Migrate IoT sensor data to TimescaleDB"""
    
    import psycopg2
    
    conn = psycopg2.connect(self.timescale_db_url)
    cursor = conn.cursor()
    
    # Use COPY for efficient bulk loading
    for file_path in sensor_files:
        with open(file_path, 'r') as f:
            cursor.copy_expert(
                """
                COPY iot.sensor_data 
                (time, device_id, sensor_type, value, unit, barn_id, metadata)
                FROM STDIN WITH CSV HEADER
                """,
                f
            )
        
        conn.commit()
        print(f"Loaded {file_path}")
    
    # Refresh continuous aggregates
    cursor.execute(
        "CALL refresh_continuous_aggregate('sensor_data_hourly', NULL, NULL)"
    )
    
    conn.close()
```

---

## 8. VALIDATION & RECONCILIATION

### 8.1 Record Count Validation

```sql
-- Reconciliation report
WITH source_counts AS (
    SELECT 
        'customers' as entity,
        COUNT(*) as source_count
    FROM legacy.customers
    WHERE is_active = 1
),
target_counts AS (
    SELECT 
        'customers' as entity,
        COUNT(*) as target_count
    FROM core.res_partner
    WHERE x_source_system = 'legacy_mysql'
)
SELECT 
    s.entity,
    s.source_count,
    t.target_count,
    s.source_count - t.target_count as variance,
    CASE 
        WHEN s.source_count = t.target_count THEN 'MATCHED'
        WHEN ABS(s.source_count - t.target_count) <= (s.source_count * 0.01) THEN 'ACCEPTABLE'
        ELSE 'INVESTIGATE'
    END as status
FROM source_counts s
JOIN target_counts t ON s.entity = t.entity;
```

### 8.2 Financial Reconciliation

```sql
-- Financial data reconciliation by period
WITH legacy_summary AS (
    SELECT 
        DATE_TRUNC('month', transaction_date) as period,
        SUM(CASE WHEN transaction_type = 'sales' THEN amount ELSE 0 END) as legacy_sales,
        SUM(CASE WHEN transaction_type = 'purchase' THEN amount ELSE 0 END) as legacy_purchases
    FROM legacy.financial_transactions
    GROUP BY DATE_TRUNC('month', transaction_date)
),
odoo_summary AS (
    SELECT 
        DATE_TRUNC('month', date) as period,
        SUM(CASE WHEN move_type = 'out_invoice' THEN amount_total ELSE 0 END) as odoo_sales,
        SUM(CASE WHEN move_type = 'in_invoice' THEN amount_total ELSE 0 END) as odoo_purchases
    FROM accounting.move
    WHERE x_source_system = 'legacy_mysql'
    GROUP BY DATE_TRUNC('month', date)
)
SELECT 
    l.period,
    l.legacy_sales,
    o.odoo_sales,
    ABS(l.legacy_sales - o.odoo_sales) as sales_variance,
    CASE 
        WHEN ABS(l.legacy_sales - o.odoo_sales) < 0.01 THEN 'MATCHED'
        ELSE 'MISMATCH'
    END as reconciliation_status
FROM legacy_summary l
FULL OUTER JOIN odoo_summary o ON l.period = o.period
ORDER BY l.period;
```

### 8.3 Data Quality Validation

```python
def run_post_migration_validation(self):
    """Run comprehensive validation after migration"""
    
    validation_results = []
    
    # 1. Check for orphaned records
    orphaned = self.check_orphaned_records()
    validation_results.append({
        'check': 'orphaned_records',
        'status': 'PASS' if orphaned == 0 else 'FAIL',
        'details': f"{orphaned} orphaned records found"
    })
    
    # 2. Verify referential integrity
    integrity_issues = self.check_referential_integrity()
    validation_results.append({
        'check': 'referential_integrity',
        'status': 'PASS' if integrity_issues == 0 else 'FAIL',
        'details': f"{integrity_issues} integrity issues found"
    })
    
    # 3. Check for missing required fields
    missing_required = self.check_required_fields()
    validation_results.append({
        'check': 'required_fields',
        'status': 'PASS' if missing_required == 0 else 'WARNING',
        'details': f"{missing_required} records with missing required fields"
    })
    
    # 4. Validate totals match
    totals_match = self.validate_financial_totals()
    validation_results.append({
        'check': 'financial_totals',
        'status': 'PASS' if totals_match else 'FAIL',
        'details': 'Financial totals match' if totals_match else 'Financial totals do not match'
    })
    
    return validation_results
```

---

## 9. TROUBLESHOOTING

### 9.1 Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| **Duplicate Key Violation** | Existing records in target | Use UPSERT or skip duplicates |
| **Foreign Key Constraint** | Referenced record not migrated | Migrate in dependency order |
| **Data Truncation** | Source data too long | Truncate with warning log |
| **Invalid Date Format** | Inconsistent date formats | Use date parsing with multiple formats |
| **Encoding Issues** | Special characters in text | UTF-8 encoding throughout |
| **Timeout Errors** | Large batch operations | Reduce batch size |

### 9.2 Rollback Procedures

```sql
-- Rollback migrated data by batch
BEGIN;

-- Delete child records first
DELETE FROM accounting.move_line 
WHERE move_id IN (
    SELECT id FROM accounting.move 
    WHERE x_migration_batch = 'BATCH_2024_001'
);

-- Delete parent records
DELETE FROM accounting.move 
WHERE x_migration_batch = 'BATCH_2024_001';

-- Delete partners
DELETE FROM core.res_partner 
WHERE x_migration_batch = 'BATCH_2024_001';

-- Clean up mapping table
DELETE FROM _migration_mapping 
WHERE batch_id = 'BATCH_2024_001';

COMMIT;
```

### 9.3 Error Handling Log

```python
# Comprehensive error logging
import logging
from datetime import datetime

logging.basicConfig(
    filename='migration.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

class MigrationLogger:
    def log_record_error(self, record, error, stage):
        """Log individual record error"""
        error_entry = {
            'timestamp': datetime.now().isoformat(),
            'stage': stage,
            'record_id': record.get('id'),
            'record_type': record.get('type'),
            'error_message': str(error),
            'record_data': record
        }
        
        # Save to error log file
        with open('migration_errors.jsonl', 'a') as f:
            f.write(json.dumps(error_entry) + '\n')
        
        # Also log to standard logger
        logging.error(f"Migration error in {stage}: {error}")
```

---

## 10. APPENDICES

### Appendix A: Migration Runbook Template

```
┌─────────────────────────────────────────────────────────────────┐
│                    MIGRATION RUNBOOK                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Migration: _______________________________________________     │
│  Date: ___________________  Time Window: __________________     │
│  Lead: ____________________________________________________     │
│                                                                  │
│  PRE-MIGRATION CHECKLIST                                         │
│  [ ] Source system backup completed                             │
│  [ ] Target system backup completed                             │
│  [ ] Migration scripts tested in staging                        │
│  [ ] Rollback procedure documented                              │
│  [ ] Stakeholders notified                                      │
│  [ ] Monitoring alerts configured                               │
│                                                                  │
│  MIGRATION STEPS                                                 │
│  1. __________________________________________________________  │
│  2. __________________________________________________________  │
│  3. __________________________________________________________  │
│                                                                  │
│  VALIDATION CHECKLIST                                            │
│  [ ] Record counts match                                        │
│  [ ] Financial totals reconcile                                 │
│  [ ] Sample data verified                                       │
│  [ ] No critical errors in log                                  │
│                                                                  │
│  SIGN-OFF                                                        │
│  Data Owner: _____________  Date: _________  Status: ________   │
│  IT Lead: ________________  Date: _________  Status: ________   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix B: Sample Migration SQL Scripts

```sql
-- Complete partner migration script
-- 1. Create temp table
CREATE TEMP TABLE _temp_partners AS SELECT * FROM core.res_partner WHERE FALSE;

-- 2. Load data from CSV
COPY _temp_partners (name, email, mobile, legacy_id)
FROM '/migration/partners.csv' CSV HEADER;

-- 3. Transform and insert
INSERT INTO core.res_partner (name, email, mobile, x_legacy_id, active, created_at)
SELECT 
    TRIM(name),
    LOWER(TRIM(email)),
    CASE 
        WHEN mobile LIKE '01%' THEN CONCAT('+880', SUBSTRING(mobile, 2))
        ELSE mobile
    END,
    legacy_id,
    TRUE,
    NOW()
FROM _temp_partners
ON CONFLICT DO NOTHING;

-- 4. Verify counts
SELECT 'Source' as source, COUNT(*) FROM _temp_partners
UNION ALL
SELECT 'Target' as source, COUNT(*) FROM core.res_partner WHERE x_legacy_id IS NOT NULL;
```

### Appendix C: Migration Timeline Template

| Week | Activity | Owner | Deliverable |
|------|----------|-------|-------------|
| 1 | Source system analysis | Data Team | Source mapping document |
| 2 | Extraction scripts | Data Team | Extracted data files |
| 3 | Transformation rules | Data Architect | Transformation specs |
| 4 | Test migration | QA Team | Test results report |
| 5 | Production migration | Data Team | Live data in Odoo |
| 6 | Validation & reconciliation | Finance Team | Reconciliation report |
| 7 | Exception handling | Data Team | Resolved issues |
| 8 | Sign-off | Data Owners | Migration complete |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Data Migration Specialist | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
