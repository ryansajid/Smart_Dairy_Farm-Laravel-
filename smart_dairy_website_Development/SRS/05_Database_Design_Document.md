# DATABASE DESIGN DOCUMENT
## Smart Dairy Digital Ecosystem
### Complete PostgreSQL Multi-Schema Design

**Document Version:** 2.0
**Date:** January 31, 2026
**Supersedes:** Version 1.0 (December 2, 2025)
**Database:** PostgreSQL 16
**ORM:** Prisma 5.x (multiSchema preview feature)
**Total Tables:** ~105 across 5 schemas

---

## TABLE OF CONTENTS

1. [Database Overview](#1-database-overview)
2. [Schema Organization](#2-schema-organization)
3. [Common Schema (~12 tables)](#3-common-schema)
4. [Web Schema (~25 tables)](#4-web-schema)
5. [Marketplace Schema (~18 tables)](#5-marketplace-schema)
6. [Livestock Schema (~15 tables)](#6-livestock-schema)
7. [ERP Schema (~35 tables)](#7-erp-schema)
8. [Cross-Schema Relationships](#8-cross-schema-relationships)
9. [Indexing Strategy](#9-indexing-strategy)
10. [Partitioning Strategy](#10-partitioning-strategy)
11. [Backup & Recovery](#11-backup--recovery)
12. [Sample Queries](#12-sample-queries)

---

## 1. DATABASE OVERVIEW

### 1.1 Database Information

```
Database Name:   smart_dairy (single database, multiple schemas)
Character Set:   UTF-8
Collation:       en_US.UTF-8
Timezone:        UTC (application converts to Asia/Dhaka for display)
PostgreSQL:      16.x
Connection Pool: PgBouncer (max 100 connections)
```

### 1.2 Design Principles

1. **Schema-Per-Module Isolation:** Each application module owns its schema. No direct cross-schema queries between modules (data shared via application layer).
2. **Naming Convention:**
   - Schemas: `common`, `web`, `mkt`, `lvs`, `erp`
   - Tables: plural, snake_case (e.g., `erp.animals`, `mkt.vendors`)
   - Columns: snake_case (e.g., `created_at`, `tag_number`)
   - Primary Keys: `id` (auto-increment `SERIAL` or `BIGSERIAL` for high-volume tables)
   - Foreign Keys: `{referenced_table}_id` (e.g., `user_id`, `animal_id`)
3. **Standard Columns:** All tables include `created_at TIMESTAMPTZ DEFAULT NOW()` and `updated_at TIMESTAMPTZ DEFAULT NOW()`. Soft deletes use `deleted_at TIMESTAMPTZ` where appropriate.
4. **Data Types:**
   - Text: `VARCHAR(n)` with limits | `TEXT` for long content
   - Money: `DECIMAL(12, 2)` (supports values up to 9,999,999,999.99 BDT)
   - Dates: `TIMESTAMPTZ` (timezone-aware) | `DATE` for date-only fields
   - Flexible data: `JSONB`
   - Large volume IDs: `BIGSERIAL` for tables exceeding 100K rows/year
5. **Referential Integrity:** Foreign keys enforced with appropriate `ON DELETE` actions (`CASCADE` for child records, `SET NULL` for optional references, `RESTRICT` for protected references).

### 1.3 Scale Projections

| Metric | Year 1 | Year 2 | Year 3 |
|--------|--------|--------|--------|
| Herd size | 260 | 600 | 1,200 |
| Milk records/year | 150K | 400K | 730K |
| Feed consumption/year | 100K | 300K | 547K |
| Health + breeding/year | 3K | 5K | 8K |
| Website orders/year | 5K | 15K | 30K |
| Marketplace listings | 500 | 3K | 10K |
| Livestock listings | 100 | 500 | 2K |
| Total DB rows/year growth | ~300K | ~800K | ~1.5M |
| Estimated DB size | 200MB | 800MB | 2GB |

---

## 2. SCHEMA ORGANIZATION

```sql
-- Schema creation
CREATE SCHEMA IF NOT EXISTS common;   -- Shared: auth, users, payments, notifications
CREATE SCHEMA IF NOT EXISTS web;      -- Module A: Website & E-Commerce
CREATE SCHEMA IF NOT EXISTS mkt;      -- Module B: Agro Marketplace
CREATE SCHEMA IF NOT EXISTS lvs;      -- Module C: Livestock Trading
CREATE SCHEMA IF NOT EXISTS erp;      -- Module D: Dairy Farm ERP

-- Set search path for application
SET search_path TO common, web, mkt, lvs, erp, public;
```

### Schema Summary

| Schema | Tables | Purpose | Key Entities |
|--------|--------|---------|-------------|
| `common` | 12 | Shared services across all modules | users, roles, payments, notifications, files, audit logs |
| `web` | 25 | Smart Dairy e-commerce + B2B + content | products, orders, cart, reviews, B2B, blog, pages |
| `mkt` | 18 | Multi-vendor marketplace | vendors, marketplace products, marketplace orders, payouts, disputes |
| `lvs` | 15 | Livestock trading platform | animal listings, auctions, bids, transport, verifications |
| `erp` | 35 | Dairy farm management | animals, milk records, feed, health, breeding, finance, HR, inventory, equipment |
| **Total** | **105** | | |

---

## 3. COMMON SCHEMA (~12 tables)

Shared entities used by all modules.

### 3.1 common.users

```sql
CREATE TABLE common.users (
  id SERIAL PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(30) DEFAULT 'customer'
    CHECK (role IN ('super_admin', 'admin', 'farm_manager', 'farm_worker',
      'veterinarian', 'marketplace_vendor', 'b2b_customer', 'customer',
      'livestock_buyer', 'livestock_seller', 'content_manager', 'support_agent')),
  status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive', 'suspended', 'pending')),
  language VARCHAR(5) DEFAULT 'en' CHECK (language IN ('en', 'bn')),
  email_verified BOOLEAN DEFAULT FALSE,
  phone_verified BOOLEAN DEFAULT FALSE,
  avatar_url VARCHAR(500),
  last_login_at TIMESTAMPTZ,
  last_login_ip VARCHAR(45),
  failed_login_attempts INTEGER DEFAULT 0,
  locked_until TIMESTAMPTZ,
  marketing_emails BOOLEAN DEFAULT TRUE,
  order_sms BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);
```

### 3.2 common.roles

```sql
CREATE TABLE common.roles (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL,
  display_name VARCHAR(100) NOT NULL,
  description TEXT,
  is_system BOOLEAN DEFAULT FALSE, -- System roles cannot be deleted
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.3 common.permissions

```sql
CREATE TABLE common.permissions (
  id SERIAL PRIMARY KEY,
  code VARCHAR(100) UNIQUE NOT NULL, -- e.g., 'erp:herd:write'
  module VARCHAR(30) NOT NULL,       -- e.g., 'erp'
  resource VARCHAR(50) NOT NULL,     -- e.g., 'herd'
  action VARCHAR(20) NOT NULL,       -- e.g., 'write'
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.4 common.role_permissions

```sql
CREATE TABLE common.role_permissions (
  role_id INTEGER NOT NULL REFERENCES common.roles(id) ON DELETE CASCADE,
  permission_id INTEGER NOT NULL REFERENCES common.permissions(id) ON DELETE CASCADE,
  PRIMARY KEY (role_id, permission_id)
);
```

### 3.5 common.user_roles

```sql
CREATE TABLE common.user_roles (
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  role_id INTEGER NOT NULL REFERENCES common.roles(id) ON DELETE CASCADE,
  granted_at TIMESTAMPTZ DEFAULT NOW(),
  granted_by INTEGER REFERENCES common.users(id),
  PRIMARY KEY (user_id, role_id)
);
```

### 3.6 common.sessions

```sql
CREATE TABLE common.sessions (
  id VARCHAR(255) PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  refresh_token_hash VARCHAR(255) NOT NULL,
  user_agent TEXT,
  ip_address VARCHAR(45),
  expires_at TIMESTAMPTZ NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.7 common.verification_tokens

```sql
CREATE TABLE common.verification_tokens (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  token VARCHAR(255) UNIQUE NOT NULL,
  type VARCHAR(20) NOT NULL CHECK (type IN ('email', 'phone', 'password_reset', '2fa')),
  expires_at TIMESTAMPTZ NOT NULL,
  used BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.8 common.payment_transactions

```sql
CREATE TABLE common.payment_transactions (
  id SERIAL PRIMARY KEY,
  transaction_ref VARCHAR(100) UNIQUE NOT NULL, -- Internal reference
  module VARCHAR(20) NOT NULL,                  -- 'web', 'mkt', 'lvs'
  module_order_id INTEGER NOT NULL,             -- FK resolved at application layer
  amount DECIMAL(12, 2) NOT NULL,
  currency VARCHAR(3) DEFAULT 'BDT',
  payment_method VARCHAR(30) NOT NULL
    CHECK (payment_method IN ('sslcommerz', 'bkash', 'nagad', 'rocket', 'cod', 'bank_transfer')),
  gateway_transaction_id VARCHAR(255),          -- Payment gateway's transaction ID
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'processing', 'completed', 'failed', 'refunded', 'partially_refunded')),
  gateway_response JSONB,                       -- Raw gateway response
  paid_at TIMESTAMPTZ,
  refunded_at TIMESTAMPTZ,
  refund_amount DECIMAL(12, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.9 common.notifications

```sql
CREATE TABLE common.notifications (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  type VARCHAR(50) NOT NULL,       -- Template name: 'vaccination_due', 'order_placed', etc.
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  data JSONB,                      -- Additional structured data
  channel VARCHAR(20) NOT NULL CHECK (channel IN ('in_app', 'sms', 'email', 'push')),
  read BOOLEAN DEFAULT FALSE,
  read_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.10 common.files

```sql
CREATE TABLE common.files (
  id SERIAL PRIMARY KEY,
  original_name VARCHAR(255) NOT NULL,
  stored_name VARCHAR(255) NOT NULL,     -- UUID-based name on storage
  mime_type VARCHAR(100) NOT NULL,
  size_bytes BIGINT NOT NULL,
  storage_path VARCHAR(500) NOT NULL,    -- Full path on DO Spaces
  thumbnail_path VARCHAR(500),
  medium_path VARCHAR(500),
  module VARCHAR(20),                    -- 'web', 'mkt', 'lvs', 'erp'
  entity_type VARCHAR(50),              -- 'product', 'animal', 'listing', etc.
  entity_id INTEGER,
  uploaded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.11 common.audit_logs

```sql
CREATE TABLE common.audit_logs (
  id BIGSERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES common.users(id),
  action VARCHAR(50) NOT NULL,          -- 'create', 'update', 'delete', 'login', 'export'
  module VARCHAR(20) NOT NULL,
  entity_type VARCHAR(50) NOT NULL,     -- 'animal', 'milk_record', 'order', etc.
  entity_id INTEGER,
  old_values JSONB,
  new_values JSONB,
  ip_address VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 3.12 common.settings

```sql
CREATE TABLE common.settings (
  id SERIAL PRIMARY KEY,
  key VARCHAR(100) UNIQUE NOT NULL,     -- 'farm.name', 'marketplace.commission_rate', etc.
  value TEXT NOT NULL,
  type VARCHAR(20) DEFAULT 'string' CHECK (type IN ('string', 'number', 'boolean', 'json')),
  module VARCHAR(20),                   -- NULL = global
  description TEXT,
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

---

## 4. WEB SCHEMA (~25 tables)

Smart Dairy's own e-commerce, B2B portal, and content management.

### 4.1 web.categories

```sql
CREATE TABLE web.categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  name_bn VARCHAR(100),                 -- Bengali name
  slug VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  image_url VARCHAR(500),
  parent_id INTEGER REFERENCES web.categories(id) ON DELETE SET NULL,
  sort_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.2 web.products

```sql
CREATE TABLE web.products (
  id SERIAL PRIMARY KEY,
  category_id INTEGER NOT NULL REFERENCES web.categories(id),
  name VARCHAR(255) NOT NULL,
  name_bn VARCHAR(255),
  slug VARCHAR(255) UNIQUE NOT NULL,
  sku VARCHAR(50) UNIQUE NOT NULL,
  description TEXT,
  description_bn TEXT,
  short_description VARCHAR(500),
  price DECIMAL(10, 2) NOT NULL,
  sale_price DECIMAL(10, 2),
  cost_price DECIMAL(10, 2),
  stock_quantity INTEGER DEFAULT 0,
  low_stock_threshold INTEGER DEFAULT 10,
  unit VARCHAR(20) DEFAULT 'piece',     -- piece, liter, kg, pack
  weight_grams INTEGER,
  status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'active', 'inactive', 'out_of_stock')),
  featured BOOLEAN DEFAULT FALSE,
  is_subscription BOOLEAN DEFAULT FALSE,
  subscription_interval_days INTEGER,
  nutritional_info JSONB,               -- For dairy products
  batch_number VARCHAR(50),
  expiry_date DATE,
  meta_title VARCHAR(255),
  meta_description VARCHAR(500),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);
```

### 4.3 web.product_images

```sql
CREATE TABLE web.product_images (
  id SERIAL PRIMARY KEY,
  product_id INTEGER NOT NULL REFERENCES web.products(id) ON DELETE CASCADE,
  image_url VARCHAR(500) NOT NULL,
  thumbnail_url VARCHAR(500),
  alt_text VARCHAR(255),
  sort_order INTEGER DEFAULT 0,
  is_primary BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.4 web.product_variants

```sql
CREATE TABLE web.product_variants (
  id SERIAL PRIMARY KEY,
  product_id INTEGER NOT NULL REFERENCES web.products(id) ON DELETE CASCADE,
  name VARCHAR(100) NOT NULL,           -- e.g., '500ml', '1 Liter', '2 Liter'
  sku VARCHAR(50) UNIQUE NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  stock_quantity INTEGER DEFAULT 0,
  weight_grams INTEGER,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.5 web.addresses

```sql
CREATE TABLE web.addresses (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  label VARCHAR(50) DEFAULT 'Home',
  recipient_name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  street_address TEXT NOT NULL,
  area VARCHAR(100),
  city VARCHAR(50) NOT NULL,
  district VARCHAR(50),
  postal_code VARCHAR(10),
  country VARCHAR(50) DEFAULT 'Bangladesh',
  is_default BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.6 web.orders

```sql
CREATE TABLE web.orders (
  id SERIAL PRIMARY KEY,
  order_number VARCHAR(20) UNIQUE NOT NULL,  -- SD-2026-00001 format
  user_id INTEGER NOT NULL REFERENCES common.users(id),
  shipping_address_id INTEGER REFERENCES web.addresses(id),
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded')),
  subtotal DECIMAL(12, 2) NOT NULL,
  discount_amount DECIMAL(12, 2) DEFAULT 0,
  tax_amount DECIMAL(12, 2) DEFAULT 0,
  shipping_amount DECIMAL(12, 2) DEFAULT 0,
  total_amount DECIMAL(12, 2) NOT NULL,
  payment_method VARCHAR(30),
  payment_status VARCHAR(20) DEFAULT 'pending'
    CHECK (payment_status IN ('pending', 'paid', 'failed', 'refunded')),
  payment_transaction_id INTEGER REFERENCES common.payment_transactions(id),
  coupon_id INTEGER REFERENCES web.coupons(id),
  notes TEXT,
  shipping_tracking_number VARCHAR(100),
  shipped_at TIMESTAMPTZ,
  delivered_at TIMESTAMPTZ,
  cancelled_at TIMESTAMPTZ,
  cancellation_reason TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.7 web.order_items

```sql
CREATE TABLE web.order_items (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL REFERENCES web.orders(id) ON DELETE CASCADE,
  product_id INTEGER NOT NULL REFERENCES web.products(id),
  variant_id INTEGER REFERENCES web.product_variants(id),
  quantity INTEGER NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,    -- Price at time of purchase
  discount_amount DECIMAL(10, 2) DEFAULT 0,
  total_price DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.8 web.cart_items

```sql
CREATE TABLE web.cart_items (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  product_id INTEGER NOT NULL REFERENCES web.products(id) ON DELETE CASCADE,
  variant_id INTEGER REFERENCES web.product_variants(id),
  quantity INTEGER NOT NULL DEFAULT 1,
  added_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, product_id, variant_id)
);
```

### 4.9 web.reviews

```sql
CREATE TABLE web.reviews (
  id SERIAL PRIMARY KEY,
  product_id INTEGER NOT NULL REFERENCES web.products(id) ON DELETE CASCADE,
  user_id INTEGER NOT NULL REFERENCES common.users(id),
  order_id INTEGER REFERENCES web.orders(id),
  rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
  title VARCHAR(255),
  content TEXT,
  is_verified_purchase BOOLEAN DEFAULT FALSE,
  helpful_count INTEGER DEFAULT 0,
  status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.10 web.coupons

```sql
CREATE TABLE web.coupons (
  id SERIAL PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  description TEXT,
  discount_type VARCHAR(20) NOT NULL CHECK (discount_type IN ('percentage', 'fixed')),
  discount_value DECIMAL(10, 2) NOT NULL,
  minimum_order_amount DECIMAL(10, 2),
  maximum_discount DECIMAL(10, 2),
  usage_limit INTEGER,
  used_count INTEGER DEFAULT 0,
  valid_from TIMESTAMPTZ NOT NULL,
  valid_until TIMESTAMPTZ NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.11 web.wishlists

```sql
CREATE TABLE web.wishlists (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  product_id INTEGER NOT NULL REFERENCES web.products(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, product_id)
);
```

### 4.12 web.subscriptions

```sql
CREATE TABLE web.subscriptions (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id),
  product_id INTEGER NOT NULL REFERENCES web.products(id),
  variant_id INTEGER REFERENCES web.product_variants(id),
  quantity INTEGER NOT NULL DEFAULT 1,
  interval_days INTEGER NOT NULL,        -- 7 (weekly), 14 (biweekly), 30 (monthly)
  delivery_address_id INTEGER NOT NULL REFERENCES web.addresses(id),
  status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'paused', 'cancelled')),
  next_delivery_date DATE NOT NULL,
  discount_percent DECIMAL(4, 2) DEFAULT 5.00, -- 5-10% subscription discount
  total_deliveries INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.13 - 4.17 B2B Tables

```sql
CREATE TABLE web.b2b_applications (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id),
  company_name VARCHAR(200) NOT NULL,
  registration_number VARCHAR(50),
  contact_person VARCHAR(100) NOT NULL,
  business_type VARCHAR(50),             -- hotel, restaurant, retailer, manufacturer
  annual_requirement TEXT,
  trade_license_url VARCHAR(500),
  status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
  reviewed_by INTEGER REFERENCES common.users(id),
  reviewed_at TIMESTAMPTZ,
  rejection_reason TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.b2b_customers (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id),
  application_id INTEGER NOT NULL REFERENCES web.b2b_applications(id),
  company_name VARCHAR(200) NOT NULL,
  credit_limit DECIMAL(12, 2) DEFAULT 0,
  payment_terms INTEGER DEFAULT 0,       -- Days (0 = prepaid, 30 = net 30)
  discount_percent DECIMAL(4, 2) DEFAULT 0,
  dedicated_account_manager INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.b2b_delivery_locations (
  id SERIAL PRIMARY KEY,
  b2b_customer_id INTEGER NOT NULL REFERENCES web.b2b_customers(id) ON DELETE CASCADE,
  name VARCHAR(100) NOT NULL,
  address TEXT NOT NULL,
  city VARCHAR(50) NOT NULL,
  contact_name VARCHAR(100),
  contact_phone VARCHAR(20),
  delivery_instructions TEXT,
  is_default BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.b2b_orders (
  id SERIAL PRIMARY KEY,
  order_number VARCHAR(20) UNIQUE NOT NULL,
  b2b_customer_id INTEGER NOT NULL REFERENCES web.b2b_customers(id),
  delivery_location_id INTEGER REFERENCES web.b2b_delivery_locations(id),
  status VARCHAR(20) DEFAULT 'pending',
  subtotal DECIMAL(12, 2) NOT NULL,
  discount_amount DECIMAL(12, 2) DEFAULT 0,
  total_amount DECIMAL(12, 2) NOT NULL,
  payment_status VARCHAR(20) DEFAULT 'pending',
  requested_delivery_date DATE,
  delivered_at TIMESTAMPTZ,
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.b2b_quotes (
  id SERIAL PRIMARY KEY,
  b2b_customer_id INTEGER NOT NULL REFERENCES web.b2b_customers(id),
  quote_number VARCHAR(20) UNIQUE NOT NULL,
  items JSONB NOT NULL,                  -- [{productId, quantity, unitPrice}]
  total_amount DECIMAL(12, 2) NOT NULL,
  valid_until DATE NOT NULL,
  status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'sent', 'accepted', 'rejected', 'expired')),
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 4.18 - 4.25 Content Management Tables

```sql
CREATE TABLE web.blog_categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  name_bn VARCHAR(100),
  slug VARCHAR(100) UNIQUE NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.blog_posts (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  title_bn VARCHAR(255),
  slug VARCHAR(255) UNIQUE NOT NULL,
  content TEXT NOT NULL,
  content_bn TEXT,
  excerpt VARCHAR(500),
  featured_image VARCHAR(500),
  category_id INTEGER REFERENCES web.blog_categories(id),
  author_id INTEGER NOT NULL REFERENCES common.users(id),
  status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'published', 'archived')),
  published_at TIMESTAMPTZ,
  views_count INTEGER DEFAULT 0,
  meta_title VARCHAR(255),
  meta_description VARCHAR(500),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.pages (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  content TEXT NOT NULL,
  content_bn TEXT,
  template VARCHAR(50) DEFAULT 'default',
  status VARCHAR(20) DEFAULT 'published',
  meta_title VARCHAR(255),
  meta_description VARCHAR(500),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.media (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255),
  file_id INTEGER REFERENCES common.files(id),
  type VARCHAR(20) CHECK (type IN ('image', 'video', 'document')),
  category VARCHAR(50),
  alt_text VARCHAR(255),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.newsletter_subscribers (
  id SERIAL PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  name VARCHAR(100),
  status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'unsubscribed')),
  subscribed_at TIMESTAMPTZ DEFAULT NOW(),
  unsubscribed_at TIMESTAMPTZ
);

CREATE TABLE web.contact_messages (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  subject VARCHAR(255),
  message TEXT NOT NULL,
  status VARCHAR(20) DEFAULT 'new' CHECK (status IN ('new', 'read', 'replied', 'closed')),
  replied_by INTEGER REFERENCES common.users(id),
  replied_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.virtual_tours (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  scene_data JSONB NOT NULL,             -- Panorama scene configuration
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE web.faq (
  id SERIAL PRIMARY KEY,
  question VARCHAR(500) NOT NULL,
  question_bn VARCHAR(500),
  answer TEXT NOT NULL,
  answer_bn TEXT,
  category VARCHAR(50),
  sort_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

---

## 5. MARKETPLACE SCHEMA (~18 tables)

Multi-vendor agro marketplace.

### 5.1 mkt.vendors

```sql
CREATE TABLE mkt.vendors (
  id SERIAL PRIMARY KEY,
  user_id INTEGER UNIQUE NOT NULL REFERENCES common.users(id),
  company_name VARCHAR(200) NOT NULL,
  slug VARCHAR(200) UNIQUE NOT NULL,
  description TEXT,
  logo_url VARCHAR(500),
  banner_url VARCHAR(500),
  business_type VARCHAR(50),             -- farm, supplier, manufacturer, distributor
  nid_number_encrypted VARCHAR(500),     -- Encrypted National ID
  trade_license_number VARCHAR(100),
  tin_number VARCHAR(50),                -- Tax Identification Number
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'approved', 'suspended', 'rejected')),
  commission_rate DECIMAL(4, 2) DEFAULT 10.00, -- Percentage
  rating_avg DECIMAL(3, 2) DEFAULT 0,
  rating_count INTEGER DEFAULT 0,
  total_sales DECIMAL(12, 2) DEFAULT 0,
  verified BOOLEAN DEFAULT FALSE,
  approved_by INTEGER REFERENCES common.users(id),
  approved_at TIMESTAMPTZ,
  location_district VARCHAR(50),
  location_upazila VARCHAR(50),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.2 mkt.vendor_documents

```sql
CREATE TABLE mkt.vendor_documents (
  id SERIAL PRIMARY KEY,
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id) ON DELETE CASCADE,
  document_type VARCHAR(30) NOT NULL
    CHECK (document_type IN ('nid', 'trade_license', 'tin_certificate', 'bank_statement', 'other')),
  file_id INTEGER NOT NULL REFERENCES common.files(id),
  verified BOOLEAN DEFAULT FALSE,
  verified_by INTEGER REFERENCES common.users(id),
  verified_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.3 mkt.vendor_bank_accounts

```sql
CREATE TABLE mkt.vendor_bank_accounts (
  id SERIAL PRIMARY KEY,
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id) ON DELETE CASCADE,
  account_type VARCHAR(20) NOT NULL CHECK (account_type IN ('bank', 'bkash', 'nagad', 'rocket')),
  account_name VARCHAR(100) NOT NULL,
  account_number_encrypted VARCHAR(500) NOT NULL, -- Encrypted
  bank_name VARCHAR(100),
  branch_name VARCHAR(100),
  routing_number VARCHAR(20),
  is_primary BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.4 mkt.mkt_categories

```sql
CREATE TABLE mkt.mkt_categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  name_bn VARCHAR(100),
  slug VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  image_url VARCHAR(500),
  parent_id INTEGER REFERENCES mkt.mkt_categories(id),
  commission_rate DECIMAL(4, 2),         -- Category-specific commission override
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.5 mkt.mkt_products

```sql
CREATE TABLE mkt.mkt_products (
  id SERIAL PRIMARY KEY,
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id),
  category_id INTEGER NOT NULL REFERENCES mkt.mkt_categories(id),
  name VARCHAR(255) NOT NULL,
  name_bn VARCHAR(255),
  slug VARCHAR(255) UNIQUE NOT NULL,
  description TEXT,
  price DECIMAL(12, 2) NOT NULL,
  compare_at_price DECIMAL(12, 2),
  stock_quantity INTEGER DEFAULT 0,
  unit VARCHAR(20) DEFAULT 'piece',
  sku VARCHAR(50),
  weight_grams INTEGER,
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'active', 'inactive', 'rejected', 'out_of_stock')),
  condition VARCHAR(20) DEFAULT 'new' CHECK (condition IN ('new', 'used', 'refurbished')),
  location_district VARCHAR(50),
  rating_avg DECIMAL(3, 2) DEFAULT 0,
  rating_count INTEGER DEFAULT 0,
  views_count INTEGER DEFAULT 0,
  sold_count INTEGER DEFAULT 0,
  specifications JSONB,                  -- Flexible product specs
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW(),
  deleted_at TIMESTAMPTZ
);
```

### 5.6 mkt.mkt_product_images

```sql
CREATE TABLE mkt.mkt_product_images (
  id SERIAL PRIMARY KEY,
  product_id INTEGER NOT NULL REFERENCES mkt.mkt_products(id) ON DELETE CASCADE,
  file_id INTEGER NOT NULL REFERENCES common.files(id),
  sort_order INTEGER DEFAULT 0,
  is_primary BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.7 mkt.mkt_orders

```sql
CREATE TABLE mkt.mkt_orders (
  id SERIAL PRIMARY KEY,
  order_number VARCHAR(20) UNIQUE NOT NULL, -- MKT-2026-00001
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id),
  shipping_address JSONB NOT NULL,       -- Snapshot of address at order time
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'disputed')),
  subtotal DECIMAL(12, 2) NOT NULL,
  shipping_amount DECIMAL(12, 2) DEFAULT 0,
  total_amount DECIMAL(12, 2) NOT NULL,
  commission_rate DECIMAL(4, 2) NOT NULL,
  commission_amount DECIMAL(12, 2) NOT NULL,
  vendor_payout_amount DECIMAL(12, 2) NOT NULL,
  payment_status VARCHAR(20) DEFAULT 'pending',
  payment_transaction_id INTEGER REFERENCES common.payment_transactions(id),
  vendor_confirmed_at TIMESTAMPTZ,
  shipped_at TIMESTAMPTZ,
  delivered_at TIMESTAMPTZ,
  auto_complete_at TIMESTAMPTZ,          -- Auto-complete 7 days after delivery
  buyer_notes TEXT,
  vendor_notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.8 mkt.mkt_order_items

```sql
CREATE TABLE mkt.mkt_order_items (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL REFERENCES mkt.mkt_orders(id) ON DELETE CASCADE,
  product_id INTEGER NOT NULL REFERENCES mkt.mkt_products(id),
  product_name VARCHAR(255) NOT NULL,    -- Snapshot
  quantity INTEGER NOT NULL,
  unit_price DECIMAL(12, 2) NOT NULL,
  total_price DECIMAL(12, 2) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 5.9 - 5.18 Remaining Marketplace Tables

```sql
CREATE TABLE mkt.mkt_shipments (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL REFERENCES mkt.mkt_orders(id),
  tracking_number VARCHAR(100),
  carrier VARCHAR(100),
  shipped_at TIMESTAMPTZ,
  estimated_delivery DATE,
  delivered_at TIMESTAMPTZ,
  delivery_proof_url VARCHAR(500),
  status VARCHAR(20) DEFAULT 'pending',
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_reviews (
  id SERIAL PRIMARY KEY,
  product_id INTEGER NOT NULL REFERENCES mkt.mkt_products(id),
  order_id INTEGER NOT NULL REFERENCES mkt.mkt_orders(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
  title VARCHAR(255),
  content TEXT,
  vendor_reply TEXT,
  vendor_replied_at TIMESTAMPTZ,
  is_verified BOOLEAN DEFAULT TRUE,
  status VARCHAR(20) DEFAULT 'published',
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_disputes (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL REFERENCES mkt.mkt_orders(id),
  raised_by INTEGER NOT NULL REFERENCES common.users(id),
  reason VARCHAR(50) NOT NULL,
  description TEXT NOT NULL,
  evidence_urls JSONB,                   -- Array of file URLs
  status VARCHAR(20) DEFAULT 'open'
    CHECK (status IN ('open', 'vendor_response', 'admin_review', 'resolved', 'closed')),
  resolution TEXT,
  resolved_by INTEGER REFERENCES common.users(id),
  resolved_at TIMESTAMPTZ,
  refund_amount DECIMAL(12, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_commissions (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL REFERENCES mkt.mkt_orders(id),
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id),
  order_amount DECIMAL(12, 2) NOT NULL,
  commission_rate DECIMAL(4, 2) NOT NULL,
  commission_amount DECIMAL(12, 2) NOT NULL,
  status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'settled')),
  settled_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_payouts (
  id SERIAL PRIMARY KEY,
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id),
  payout_ref VARCHAR(50) UNIQUE NOT NULL,
  amount DECIMAL(12, 2) NOT NULL,
  method VARCHAR(20) NOT NULL,           -- 'bank', 'bkash', 'nagad'
  bank_account_id INTEGER REFERENCES mkt.vendor_bank_accounts(id),
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'processing', 'completed', 'failed')),
  period_start DATE NOT NULL,
  period_end DATE NOT NULL,
  orders_count INTEGER NOT NULL,
  gateway_reference VARCHAR(255),
  processed_at TIMESTAMPTZ,
  failed_reason TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_return_requests (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL REFERENCES mkt.mkt_orders(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  reason VARCHAR(100) NOT NULL,
  description TEXT,
  evidence_urls JSONB,
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'approved', 'rejected', 'completed')),
  refund_amount DECIMAL(12, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_conversations (
  id SERIAL PRIMARY KEY,
  order_id INTEGER REFERENCES mkt.mkt_orders(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id),
  subject VARCHAR(255),
  status VARCHAR(20) DEFAULT 'open',
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_messages (
  id SERIAL PRIMARY KEY,
  conversation_id INTEGER NOT NULL REFERENCES mkt.mkt_conversations(id) ON DELETE CASCADE,
  sender_id INTEGER NOT NULL REFERENCES common.users(id),
  message TEXT NOT NULL,
  attachment_url VARCHAR(500),
  read_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE mkt.mkt_promotions (
  id SERIAL PRIMARY KEY,
  vendor_id INTEGER NOT NULL REFERENCES mkt.vendors(id),
  name VARCHAR(255) NOT NULL,
  discount_type VARCHAR(20) CHECK (discount_type IN ('percentage', 'fixed')),
  discount_value DECIMAL(10, 2) NOT NULL,
  min_order_amount DECIMAL(10, 2),
  valid_from TIMESTAMPTZ NOT NULL,
  valid_until TIMESTAMPTZ NOT NULL,
  usage_limit INTEGER,
  used_count INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

---

## 6. LIVESTOCK SCHEMA (~15 tables)

Cattle trading platform with health records, pedigree, and auctions.

### 6.1 lvs.livestock_categories

```sql
CREATE TABLE lvs.livestock_categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,            -- Dairy Cow, Bull, Heifer, Calf, Goat, etc.
  name_bn VARCHAR(100),
  slug VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 6.2 lvs.animal_listings

```sql
CREATE TABLE lvs.animal_listings (
  id SERIAL PRIMARY KEY,
  seller_id INTEGER NOT NULL REFERENCES common.users(id),
  source_animal_id INTEGER,              -- FK to erp.animals if from Smart Dairy's own herd
  category_id INTEGER NOT NULL REFERENCES lvs.livestock_categories(id),
  listing_number VARCHAR(20) UNIQUE NOT NULL, -- LVS-2026-00001
  title VARCHAR(255) NOT NULL,
  description TEXT,
  breed VARCHAR(50) NOT NULL,
  sex VARCHAR(10) NOT NULL CHECK (sex IN ('male', 'female')),
  date_of_birth DATE,
  age_months INTEGER,
  weight_kg DECIMAL(6, 2),
  color VARCHAR(50),
  breed_composition JSONB,               -- e.g., {"Holstein": 75, "Sahiwal": 25}
  -- Production data (for dairy cows)
  daily_milk_yield DECIMAL(6, 2),
  lactation_number INTEGER,
  -- Genealogy
  sire_info JSONB,                       -- {name, breed, tag, owner}
  dam_info JSONB,
  -- Pricing
  price_type VARCHAR(20) NOT NULL CHECK (price_type IN ('fixed', 'auction', 'negotiation')),
  asking_price DECIMAL(12, 2),
  minimum_bid DECIMAL(12, 2),
  -- Location
  location_district VARCHAR(50) NOT NULL,
  location_upazila VARCHAR(50),
  location_detail TEXT,
  gps_lat DECIMAL(10, 7),
  gps_lng DECIMAL(10, 7),
  -- Verification
  is_verified BOOLEAN DEFAULT FALSE,
  verified_by INTEGER REFERENCES common.users(id),
  verified_at TIMESTAMPTZ,
  verification_notes TEXT,
  sd_inspected BOOLEAN DEFAULT FALSE,    -- Smart Dairy team inspected
  -- Status
  status VARCHAR(20) DEFAULT 'draft'
    CHECK (status IN ('draft', 'pending_review', 'active', 'sold', 'expired', 'cancelled')),
  views_count INTEGER DEFAULT 0,
  inquiries_count INTEGER DEFAULT 0,
  expires_at TIMESTAMPTZ,
  sold_at TIMESTAMPTZ,
  sold_to INTEGER REFERENCES common.users(id),
  sold_price DECIMAL(12, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 6.3 lvs.listing_images

```sql
CREATE TABLE lvs.listing_images (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id) ON DELETE CASCADE,
  file_id INTEGER NOT NULL REFERENCES common.files(id),
  angle VARCHAR(20),                     -- 'front', 'left_side', 'right_side', 'rear', 'close_up'
  sort_order INTEGER DEFAULT 0,
  is_primary BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 6.4 lvs.listing_health_records

```sql
CREATE TABLE lvs.listing_health_records (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id) ON DELETE CASCADE,
  record_type VARCHAR(30) NOT NULL
    CHECK (record_type IN ('vaccination', 'deworming', 'disease_history', 'surgery', 'test_result')),
  description VARCHAR(500) NOT NULL,
  date DATE NOT NULL,
  veterinarian VARCHAR(100),
  certificate_file_id INTEGER REFERENCES common.files(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 6.5 lvs.auctions

```sql
CREATE TABLE lvs.auctions (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER UNIQUE NOT NULL REFERENCES lvs.animal_listings(id),
  start_time TIMESTAMPTZ NOT NULL,
  end_time TIMESTAMPTZ NOT NULL,
  starting_price DECIMAL(12, 2) NOT NULL,
  reserve_price DECIMAL(12, 2),          -- Minimum price for sale to occur
  bid_increment DECIMAL(10, 2) DEFAULT 500, -- Minimum bid increment
  current_price DECIMAL(12, 2),
  top_bidder_id INTEGER REFERENCES common.users(id),
  bid_count INTEGER DEFAULT 0,
  anti_snipe_extension_minutes INTEGER DEFAULT 2,
  status VARCHAR(20) DEFAULT 'scheduled'
    CHECK (status IN ('scheduled', 'active', 'ended', 'sold', 'no_sale', 'cancelled')),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 6.6 lvs.auction_bids

```sql
CREATE TABLE lvs.auction_bids (
  id SERIAL PRIMARY KEY,
  auction_id INTEGER NOT NULL REFERENCES lvs.auctions(id),
  bidder_id INTEGER NOT NULL REFERENCES common.users(id),
  amount DECIMAL(12, 2) NOT NULL,
  is_winning BOOLEAN DEFAULT FALSE,
  bid_time TIMESTAMPTZ DEFAULT NOW()
);
```

### 6.7 - 6.15 Remaining Livestock Tables

```sql
CREATE TABLE lvs.listing_offers (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  offer_amount DECIMAL(12, 2) NOT NULL,
  message TEXT,
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'accepted', 'rejected', 'countered', 'expired')),
  counter_amount DECIMAL(12, 2),
  counter_message TEXT,
  expires_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE lvs.transport_providers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  contact_person VARCHAR(100),
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(255),
  location_district VARCHAR(50),
  vehicle_types JSONB,                   -- ['truck', 'pickup', 'trailer']
  service_areas JSONB,                   -- Array of districts served
  price_per_km DECIMAL(8, 2),
  rating_avg DECIMAL(3, 2) DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE lvs.transport_bookings (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id),
  provider_id INTEGER NOT NULL REFERENCES lvs.transport_providers(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  pickup_address TEXT NOT NULL,
  delivery_address TEXT NOT NULL,
  distance_km DECIMAL(8, 2),
  estimated_cost DECIMAL(10, 2),
  actual_cost DECIMAL(10, 2),
  vehicle_type VARCHAR(50),
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'confirmed', 'in_transit', 'delivered', 'cancelled')),
  pickup_date TIMESTAMPTZ,
  delivered_at TIMESTAMPTZ,
  tracking_url VARCHAR(500),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE lvs.listing_verifications (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id),
  verified_by INTEGER NOT NULL REFERENCES common.users(id),
  verification_type VARCHAR(30) NOT NULL
    CHECK (verification_type IN ('physical_inspection', 'document_review', 'vet_checkup')),
  result VARCHAR(20) NOT NULL CHECK (result IN ('passed', 'failed', 'conditional')),
  notes TEXT,
  certificate_file_id INTEGER REFERENCES common.files(id),
  verified_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE lvs.favorites (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, listing_id)
);

CREATE TABLE lvs.price_alerts (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES common.users(id) ON DELETE CASCADE,
  category_id INTEGER REFERENCES lvs.livestock_categories(id),
  breed VARCHAR(50),
  max_price DECIMAL(12, 2),
  district VARCHAR(50),
  is_active BOOLEAN DEFAULT TRUE,
  last_notified_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE lvs.livestock_transactions (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id),
  seller_id INTEGER NOT NULL REFERENCES common.users(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  sale_type VARCHAR(20) NOT NULL,        -- 'fixed', 'auction', 'negotiation'
  sale_price DECIMAL(12, 2) NOT NULL,
  platform_fee DECIMAL(12, 2) NOT NULL,
  seller_payout DECIMAL(12, 2) NOT NULL,
  payment_transaction_id INTEGER REFERENCES common.payment_transactions(id),
  transport_booking_id INTEGER REFERENCES lvs.transport_bookings(id),
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'payment_received', 'animal_handed_over', 'completed', 'disputed', 'cancelled')),
  completed_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE lvs.inquiries (
  id SERIAL PRIMARY KEY,
  listing_id INTEGER NOT NULL REFERENCES lvs.animal_listings(id),
  buyer_id INTEGER NOT NULL REFERENCES common.users(id),
  message TEXT NOT NULL,
  seller_reply TEXT,
  replied_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

---

## 7. ERP SCHEMA (~35 tables)

Complete dairy farm management for 260 cattle, scalable to 1,200.

### 7.1 erp.animals (Core Entity)

```sql
CREATE TABLE erp.animals (
  id SERIAL PRIMARY KEY,
  tag_number VARCHAR(20) UNIQUE NOT NULL,  -- e.g., SD-001
  name VARCHAR(50),
  breed VARCHAR(50) NOT NULL,              -- Holstein Friesian, Sahiwal, Cross, etc.
  sex VARCHAR(10) NOT NULL CHECK (sex IN ('male', 'female')),
  date_of_birth DATE NOT NULL,
  acquisition_date DATE NOT NULL,
  acquisition_type VARCHAR(20) NOT NULL CHECK (acquisition_type IN ('born', 'purchased', 'donated')),
  acquisition_cost DECIMAL(12, 2),
  status VARCHAR(20) DEFAULT 'active'
    CHECK (status IN ('active', 'sold', 'dead', 'culled', 'transferred', 'for_sale')),
  current_group VARCHAR(30) NOT NULL
    CHECK (current_group IN ('lactating', 'dry', 'heifer', 'bull', 'calf', 'sick_pen', 'quarantine')),
  current_pen VARCHAR(30),
  current_weight DECIMAL(6, 2),
  body_condition_score DECIMAL(3, 1),     -- 1.0 to 5.0 scale
  sire_id INTEGER REFERENCES erp.animals(id),
  dam_id INTEGER REFERENCES erp.animals(id),
  breed_composition JSONB,                -- {"Holstein": 75, "Sahiwal": 25}
  rfid_tag VARCHAR(50),
  photo_url VARCHAR(500),
  notes TEXT,
  disposal_date DATE,
  disposal_reason TEXT,
  disposal_revenue DECIMAL(12, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.2 erp.animal_groups

```sql
CREATE TABLE erp.animal_groups (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL,      -- 'lactating', 'dry', 'heifer', etc.
  display_name VARCHAR(100) NOT NULL,
  display_name_bn VARCHAR(100),
  description TEXT,
  capacity INTEGER,                       -- Max animals in this group
  current_count INTEGER DEFAULT 0,
  pen_location VARCHAR(50),
  feed_ration_id INTEGER REFERENCES erp.feed_rations(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.3 erp.animal_movements

```sql
CREATE TABLE erp.animal_movements (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  from_group VARCHAR(30) NOT NULL,
  to_group VARCHAR(30) NOT NULL,
  reason VARCHAR(100),                   -- 'calving', 'dry_off', 'weaning', 'treatment', etc.
  moved_by INTEGER REFERENCES common.users(id),
  moved_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.4 erp.animal_weights

```sql
CREATE TABLE erp.animal_weights (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  weight_kg DECIMAL(6, 2) NOT NULL,
  method VARCHAR(20) DEFAULT 'scale' CHECK (method IN ('scale', 'tape_measure', 'estimated')),
  recorded_by INTEGER REFERENCES common.users(id),
  recorded_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.5 erp.milk_records (High-Volume, Partitioned)

```sql
CREATE TABLE erp.milk_records (
  id BIGSERIAL,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  record_date DATE NOT NULL,
  session VARCHAR(10) NOT NULL CHECK (session IN ('morning', 'evening')),
  quantity_liters DECIMAL(6, 2) NOT NULL,
  fat_percent DECIMAL(4, 2),
  snf_percent DECIMAL(4, 2),
  protein_percent DECIMAL(4, 2),
  somatic_cell_count INTEGER,
  conductivity DECIMAL(6, 2),            -- mS/cm (mastitis indicator)
  recorded_by INTEGER NOT NULL REFERENCES common.users(id),
  method VARCHAR(20) DEFAULT 'manual' CHECK (method IN ('manual', 'automated', 'estimated')),
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  PRIMARY KEY (id, record_date)
) PARTITION BY RANGE (record_date);

-- Create yearly partitions
CREATE TABLE erp.milk_records_2026 PARTITION OF erp.milk_records
  FOR VALUES FROM ('2026-01-01') TO ('2027-01-01');
CREATE TABLE erp.milk_records_2027 PARTITION OF erp.milk_records
  FOR VALUES FROM ('2027-01-01') TO ('2028-01-01');
```

### 7.6 erp.bulk_tank_records

```sql
CREATE TABLE erp.bulk_tank_records (
  id SERIAL PRIMARY KEY,
  record_date DATE NOT NULL,
  session VARCHAR(10) NOT NULL CHECK (session IN ('morning', 'evening')),
  total_liters DECIMAL(8, 2) NOT NULL,
  temperature DECIMAL(4, 1),             -- °C
  fat_avg DECIMAL(4, 2),
  snf_avg DECIMAL(4, 2),
  protein_avg DECIMAL(4, 2),
  bacteria_count INTEGER,                -- CFU/ml
  somatic_cell_avg INTEGER,
  chilling_time_minutes INTEGER,
  accepted_by_collector BOOLEAN,
  collector_name VARCHAR(100),
  collector_vehicle VARCHAR(50),
  rejection_reason TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.7 erp.milk_quality_tests

```sql
CREATE TABLE erp.milk_quality_tests (
  id SERIAL PRIMARY KEY,
  sample_id VARCHAR(50) UNIQUE NOT NULL,
  test_date DATE NOT NULL,
  sample_source VARCHAR(20) CHECK (sample_source IN ('bulk_tank', 'individual_cow', 'batch')),
  animal_id INTEGER REFERENCES erp.animals(id),
  test_type VARCHAR(30) NOT NULL,        -- 'fat', 'snf', 'protein', 'bacteria', 'adulteration', 'antibiotic'
  result_value VARCHAR(50) NOT NULL,
  unit VARCHAR(20),
  pass_fail VARCHAR(10) CHECK (pass_fail IN ('pass', 'fail', 'borderline')),
  tested_by VARCHAR(100),
  lab_name VARCHAR(100),
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.8 - 7.10 Feed Management Tables

```sql
CREATE TABLE erp.feed_items (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  name_bn VARCHAR(100),
  type VARCHAR(30) NOT NULL
    CHECK (type IN ('roughage', 'concentrate', 'supplement', 'mineral', 'additive')),
  unit VARCHAR(20) NOT NULL DEFAULT 'kg',
  cost_per_unit DECIMAL(10, 2),
  nutritional_composition JSONB,         -- {dm: 88, cp: 16, tdn: 72, me: 2.5, ca: 0.4, p: 0.3}
  source VARCHAR(100),                   -- Supplier or local name
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.feed_rations (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,            -- e.g., 'Lactating Cow Ration A'
  target_group VARCHAR(30) NOT NULL,     -- 'lactating', 'dry', 'heifer', etc.
  components JSONB NOT NULL,             -- [{feedItemId, quantityPerAnimal, unit}]
  total_dm DECIMAL(6, 2),               -- Total dry matter (kg)
  total_cp DECIMAL(6, 2),               -- Total crude protein (kg)
  total_tdn DECIMAL(6, 2),              -- Total digestible nutrients (kg)
  total_cost_per_animal DECIMAL(8, 2),
  notes TEXT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.feed_inventory (
  id SERIAL PRIMARY KEY,
  feed_item_id INTEGER NOT NULL REFERENCES erp.feed_items(id),
  quantity_in_stock DECIMAL(10, 2) NOT NULL DEFAULT 0,
  reorder_level DECIMAL(10, 2),
  last_purchase_date DATE,
  last_purchase_price DECIMAL(10, 2),
  last_purchase_quantity DECIMAL(10, 2),
  storage_location VARCHAR(50),
  expiry_date DATE,
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.feed_consumption (
  id BIGSERIAL PRIMARY KEY,
  record_date DATE NOT NULL,
  group_id INTEGER NOT NULL REFERENCES erp.animal_groups(id),
  ration_id INTEGER NOT NULL REFERENCES erp.feed_rations(id),
  animals_fed_count INTEGER NOT NULL,
  total_quantity_kg DECIMAL(8, 2) NOT NULL,
  total_cost DECIMAL(10, 2) NOT NULL,
  recorded_by INTEGER REFERENCES common.users(id),
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.11 - 7.14 Health Management Tables

```sql
CREATE TABLE erp.health_events (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  event_date DATE NOT NULL,
  event_type VARCHAR(20) NOT NULL
    CHECK (event_type IN ('illness', 'injury', 'treatment', 'surgery', 'checkup', 'emergency')),
  diagnosis VARCHAR(255),
  symptoms JSONB,                        -- ['fever', 'reduced_appetite', 'limping']
  severity VARCHAR(20) CHECK (severity IN ('mild', 'moderate', 'severe', 'critical')),
  treated_by VARCHAR(100),               -- Veterinarian name
  treatment_details TEXT,
  medications JSONB,                     -- [{name, dose, frequency, duration}]
  medication_cost DECIMAL(10, 2),
  outcome VARCHAR(20) CHECK (outcome IN ('recovered', 'improving', 'chronic', 'died', 'ongoing')),
  follow_up_date DATE,
  notes TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.vaccination_schedules (
  id SERIAL PRIMARY KEY,
  vaccine_name VARCHAR(100) NOT NULL,
  vaccine_name_bn VARCHAR(100),
  target_group VARCHAR(30),              -- 'all', 'lactating', 'calves', etc.
  frequency_days INTEGER NOT NULL,       -- 180 for biannual, 365 for annual
  regulatory_required BOOLEAN DEFAULT FALSE,
  notes TEXT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.vaccination_records (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  schedule_id INTEGER REFERENCES erp.vaccination_schedules(id),
  vaccine_name VARCHAR(100) NOT NULL,
  vaccination_date DATE NOT NULL,
  batch_number VARCHAR(50),
  administered_by VARCHAR(100),
  dose VARCHAR(50),
  route VARCHAR(30),                     -- 'intramuscular', 'subcutaneous', 'oral'
  next_due_date DATE,
  reaction_observed TEXT,
  cost DECIMAL(8, 2),
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.disease_incidents (
  id SERIAL PRIMARY KEY,
  incident_date DATE NOT NULL,
  disease_name VARCHAR(100) NOT NULL,
  affected_animals JSONB NOT NULL,       -- Array of animal IDs
  affected_count INTEGER NOT NULL,
  severity VARCHAR(20),
  quarantine_actions TEXT,
  treatment_protocol TEXT,
  resolved BOOLEAN DEFAULT FALSE,
  resolved_date DATE,
  total_treatment_cost DECIMAL(10, 2),
  reported_to_dls BOOLEAN DEFAULT FALSE, -- Reported to Department of Livestock Services
  notes TEXT,
  reported_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.15 - 7.18 Breeding Management Tables

```sql
CREATE TABLE erp.breeding_records (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  breeding_date DATE NOT NULL,
  breeding_type VARCHAR(20) NOT NULL CHECK (breeding_type IN ('natural', 'ai', 'embryo_transfer')),
  sire_id INTEGER REFERENCES erp.animals(id),          -- If natural, local bull
  semen_straw_id VARCHAR(50),                           -- AI semen straw identifier
  semen_breed VARCHAR(50),
  semen_source VARCHAR(100),                            -- AI center/company
  technician VARCHAR(100),
  heat_detection_method VARCHAR(30),     -- 'visual', 'activity_monitor', 'hormone_test'
  cost DECIMAL(8, 2),
  notes TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.pregnancy_checks (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  breeding_record_id INTEGER REFERENCES erp.breeding_records(id),
  check_date DATE NOT NULL,
  days_post_breeding INTEGER,
  method VARCHAR(20) NOT NULL CHECK (method IN ('rectal', 'ultrasound', 'blood_test')),
  result VARCHAR(20) NOT NULL CHECK (result IN ('positive', 'negative', 'recheck', 'inconclusive')),
  estimated_calving_date DATE,
  fetal_count INTEGER DEFAULT 1,
  checked_by VARCHAR(100),
  notes TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.calving_records (
  id SERIAL PRIMARY KEY,
  dam_id INTEGER NOT NULL REFERENCES erp.animals(id),
  calf_id INTEGER REFERENCES erp.animals(id),           -- NULL until calf registered
  breeding_record_id INTEGER REFERENCES erp.breeding_records(id),
  calving_date DATE NOT NULL,
  gestation_days INTEGER,
  difficulty_score INTEGER CHECK (difficulty_score BETWEEN 1 AND 5), -- 1=easy, 5=surgical
  presentation VARCHAR(30),              -- 'normal', 'breech', 'posterior'
  calf_sex VARCHAR(10),
  calf_weight DECIMAL(5, 2),
  calf_status VARCHAR(20) CHECK (calf_status IN ('alive', 'stillborn', 'died_within_24h')),
  complications TEXT,                    -- 'retained_placenta', 'prolapse', etc.
  assisted_by VARCHAR(100),
  colostrum_given BOOLEAN,
  notes TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.lactation_records (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL REFERENCES erp.animals(id),
  lactation_number INTEGER NOT NULL,
  start_date DATE NOT NULL,              -- Date of calving
  end_date DATE,                         -- Date of dry-off
  peak_yield DECIMAL(6, 2),             -- Peak daily yield (liters)
  peak_yield_date DATE,
  total_yield DECIMAL(10, 2),            -- Total liters in this lactation
  days_in_milk INTEGER,
  yield_305_day DECIMAL(10, 2),          -- Standardized 305-day yield
  dry_off_reason VARCHAR(50),            -- 'planned', 'low_yield', 'health', 'breeding'
  calving_record_id INTEGER REFERENCES erp.calving_records(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.19 - 7.21 Financial Management Tables

```sql
CREATE TABLE erp.financial_accounts (
  id SERIAL PRIMARY KEY,
  account_code VARCHAR(20) UNIQUE NOT NULL,  -- e.g., '4001' for milk revenue
  name VARCHAR(100) NOT NULL,
  type VARCHAR(20) NOT NULL CHECK (type IN ('income', 'expense', 'asset', 'liability')),
  parent_id INTEGER REFERENCES erp.financial_accounts(id),
  description TEXT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.financial_transactions (
  id SERIAL PRIMARY KEY,
  transaction_date DATE NOT NULL,
  account_id INTEGER NOT NULL REFERENCES erp.financial_accounts(id),
  type VARCHAR(10) NOT NULL CHECK (type IN ('debit', 'credit')),
  amount DECIMAL(12, 2) NOT NULL,
  description VARCHAR(500) NOT NULL,
  reference VARCHAR(100),                -- Invoice number, receipt number, etc.
  category VARCHAR(50),                  -- 'milk_sale', 'feed_purchase', 'medicine', 'salary', etc.
  module_source VARCHAR(20),             -- 'erp', 'web', 'mkt'
  source_entity_id INTEGER,              -- ID of related record
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.milk_sales (
  id SERIAL PRIMARY KEY,
  sale_date DATE NOT NULL,
  buyer_name VARCHAR(100) NOT NULL,
  buyer_type VARCHAR(20) CHECK (buyer_type IN ('collector', 'processor', 'direct', 'b2b')),
  quantity_liters DECIMAL(8, 2) NOT NULL,
  rate_per_liter DECIMAL(6, 2) NOT NULL,
  total_amount DECIMAL(12, 2) NOT NULL,
  fat_percent DECIMAL(4, 2),
  snf_percent DECIMAL(4, 2),
  quality_premium DECIMAL(8, 2) DEFAULT 0,
  deductions DECIMAL(8, 2) DEFAULT 0,
  net_amount DECIMAL(12, 2) NOT NULL,
  payment_status VARCHAR(20) DEFAULT 'pending'
    CHECK (payment_status IN ('pending', 'received', 'overdue')),
  payment_method VARCHAR(20),
  payment_date DATE,
  transaction_id INTEGER REFERENCES erp.financial_transactions(id),
  notes TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.22 - 7.24 HR Management Tables

```sql
CREATE TABLE erp.employees (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES common.users(id),  -- Linked if employee has app access
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  nid_number_encrypted VARCHAR(500),
  designation VARCHAR(50) NOT NULL,       -- 'milker', 'feeder', 'cleaner', 'supervisor', 'vet_assistant'
  department VARCHAR(50),                 -- 'milking', 'feeding', 'health', 'general', 'management'
  hire_date DATE NOT NULL,
  salary DECIMAL(10, 2) NOT NULL,
  bank_account_encrypted VARCHAR(500),
  emergency_contact_name VARCHAR(100),
  emergency_contact_phone VARCHAR(20),
  photo_url VARCHAR(500),
  status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'on_leave', 'terminated')),
  termination_date DATE,
  termination_reason TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.attendance (
  id SERIAL PRIMARY KEY,
  employee_id INTEGER NOT NULL REFERENCES erp.employees(id),
  attendance_date DATE NOT NULL,
  check_in TIMESTAMPTZ,
  check_out TIMESTAMPTZ,
  status VARCHAR(20) DEFAULT 'present'
    CHECK (status IN ('present', 'absent', 'late', 'half_day', 'leave', 'holiday')),
  overtime_hours DECIMAL(4, 2) DEFAULT 0,
  check_in_method VARCHAR(20) DEFAULT 'manual',  -- 'manual', 'mobile_gps', 'biometric'
  gps_lat DECIMAL(10, 7),
  gps_lng DECIMAL(10, 7),
  notes TEXT,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(employee_id, attendance_date)
);

CREATE TABLE erp.payroll (
  id SERIAL PRIMARY KEY,
  employee_id INTEGER NOT NULL REFERENCES erp.employees(id),
  pay_period_month INTEGER NOT NULL,     -- 1-12
  pay_period_year INTEGER NOT NULL,
  base_salary DECIMAL(10, 2) NOT NULL,
  overtime_amount DECIMAL(10, 2) DEFAULT 0,
  bonus DECIMAL(10, 2) DEFAULT 0,
  deductions JSONB,                      -- {advance: 500, tax: 0, other: 0}
  total_deductions DECIMAL(10, 2) DEFAULT 0,
  net_pay DECIMAL(10, 2) NOT NULL,
  payment_date DATE,
  payment_method VARCHAR(20),
  status VARCHAR(20) DEFAULT 'pending'
    CHECK (status IN ('pending', 'paid', 'hold')),
  transaction_id INTEGER REFERENCES erp.financial_transactions(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(employee_id, pay_period_month, pay_period_year)
);
```

### 7.25 - 7.29 Inventory & Equipment Tables

```sql
CREATE TABLE erp.suppliers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  contact_person VARCHAR(100),
  phone VARCHAR(20),
  email VARCHAR(255),
  address TEXT,
  category VARCHAR(50),                  -- 'feed', 'medicine', 'equipment', 'general'
  payment_terms VARCHAR(50),
  rating INTEGER CHECK (rating BETWEEN 1 AND 5),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.inventory_items (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  category VARCHAR(50) NOT NULL
    CHECK (category IN ('feed', 'medicine', 'vaccine', 'equipment_parts', 'cleaning', 'office', 'other')),
  unit VARCHAR(20) NOT NULL,
  current_stock DECIMAL(10, 2) DEFAULT 0,
  reorder_level DECIMAL(10, 2),
  storage_location VARCHAR(50),
  expiry_tracking BOOLEAN DEFAULT FALSE,
  nearest_expiry DATE,
  preferred_supplier_id INTEGER REFERENCES erp.suppliers(id),
  last_unit_cost DECIMAL(10, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.inventory_transactions (
  id SERIAL PRIMARY KEY,
  item_id INTEGER NOT NULL REFERENCES erp.inventory_items(id),
  transaction_date DATE NOT NULL,
  type VARCHAR(20) NOT NULL CHECK (type IN ('purchase', 'usage', 'adjustment', 'wastage', 'return')),
  quantity DECIMAL(10, 2) NOT NULL,      -- Positive for in, negative for out
  unit_cost DECIMAL(10, 2),
  total_cost DECIMAL(10, 2),
  reference VARCHAR(100),                -- PO number, usage reference
  batch_number VARCHAR(50),
  expiry_date DATE,
  recorded_by INTEGER REFERENCES common.users(id),
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.purchase_orders (
  id SERIAL PRIMARY KEY,
  po_number VARCHAR(20) UNIQUE NOT NULL,
  supplier_id INTEGER NOT NULL REFERENCES erp.suppliers(id),
  items JSONB NOT NULL,                  -- [{itemId, quantity, unitCost, totalCost}]
  total_amount DECIMAL(12, 2) NOT NULL,
  status VARCHAR(20) DEFAULT 'draft'
    CHECK (status IN ('draft', 'submitted', 'approved', 'ordered', 'received', 'cancelled')),
  expected_delivery DATE,
  received_date DATE,
  received_by INTEGER REFERENCES common.users(id),
  notes TEXT,
  created_by INTEGER REFERENCES common.users(id),
  approved_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.equipment (
  id SERIAL PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  type VARCHAR(50) NOT NULL,             -- 'milking_machine', 'cooling_tank', 'generator', 'tractor', 'pump'
  model VARCHAR(100),
  serial_number VARCHAR(100),
  manufacturer VARCHAR(100),
  purchase_date DATE,
  purchase_cost DECIMAL(12, 2),
  current_value DECIMAL(12, 2),
  depreciation_method VARCHAR(20) DEFAULT 'straight_line',
  useful_life_years INTEGER,
  salvage_value DECIMAL(12, 2),
  location VARCHAR(50),
  status VARCHAR(20) DEFAULT 'operational'
    CHECK (status IN ('operational', 'maintenance', 'broken', 'retired')),
  warranty_expiry DATE,
  photo_url VARCHAR(500),
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.maintenance_schedules (
  id SERIAL PRIMARY KEY,
  equipment_id INTEGER NOT NULL REFERENCES erp.equipment(id),
  maintenance_type VARCHAR(50) NOT NULL,  -- 'oil_change', 'filter_replace', 'calibration', 'general_service'
  frequency_days INTEGER NOT NULL,
  last_performed DATE,
  next_due DATE NOT NULL,
  estimated_cost DECIMAL(8, 2),
  instructions TEXT,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.maintenance_logs (
  id SERIAL PRIMARY KEY,
  equipment_id INTEGER NOT NULL REFERENCES erp.equipment(id),
  schedule_id INTEGER REFERENCES erp.maintenance_schedules(id),
  maintenance_date DATE NOT NULL,
  type VARCHAR(20) NOT NULL CHECK (type IN ('preventive', 'corrective', 'emergency')),
  description TEXT NOT NULL,
  cost DECIMAL(10, 2),
  parts_used JSONB,                      -- [{part, quantity, cost}]
  performed_by VARCHAR(100),
  downtime_hours DECIMAL(5, 2),
  next_scheduled DATE,
  recorded_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

### 7.30 - 7.35 Reporting & Configuration Tables

```sql
CREATE TABLE erp.cost_analysis (
  id SERIAL PRIMARY KEY,
  analysis_period VARCHAR(7) NOT NULL,   -- 'YYYY-MM' format
  total_milk_produced DECIMAL(10, 2),
  total_milk_revenue DECIMAL(12, 2),
  feed_cost DECIMAL(12, 2),
  medicine_cost DECIMAL(12, 2),
  labor_cost DECIMAL(12, 2),
  overhead_cost DECIMAL(12, 2),
  total_cost DECIMAL(12, 2),
  cost_per_liter DECIMAL(6, 2),
  revenue_per_liter DECIMAL(6, 2),
  profit_per_liter DECIMAL(6, 2),
  feed_cost_ratio DECIMAL(5, 2),         -- Feed cost as % of revenue
  active_cow_count INTEGER,
  avg_yield_per_cow DECIMAL(6, 2),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(analysis_period)
);

CREATE TABLE erp.report_templates (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  type VARCHAR(30) NOT NULL,             -- 'production', 'financial', 'herd', 'health', 'breeding'
  template_config JSONB NOT NULL,        -- Report configuration (columns, filters, format)
  is_system BOOLEAN DEFAULT FALSE,
  created_by INTEGER REFERENCES common.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.generated_reports (
  id SERIAL PRIMARY KEY,
  template_id INTEGER REFERENCES erp.report_templates(id),
  report_type VARCHAR(30) NOT NULL,
  period VARCHAR(20) NOT NULL,           -- '2026-01', '2026-Q1', '2026'
  file_url VARCHAR(500),
  format VARCHAR(10) DEFAULT 'pdf',
  generated_by INTEGER REFERENCES common.users(id),
  generated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE erp.erp_settings (
  id SERIAL PRIMARY KEY,
  key VARCHAR(100) UNIQUE NOT NULL,
  value TEXT NOT NULL,
  type VARCHAR(20) DEFAULT 'string',
  category VARCHAR(50),                  -- 'milk', 'feed', 'health', 'finance', 'general'
  description TEXT,
  updated_by INTEGER REFERENCES common.users(id),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Pre-populated settings
-- INSERT INTO erp.erp_settings (key, value, category) VALUES
-- ('milk.target_daily_liters', '900', 'milk'),
-- ('milk.morning_session_start', '05:00', 'milk'),
-- ('milk.evening_session_start', '16:00', 'milk'),
-- ('feed.cost_alert_threshold', '55', 'feed'),  -- % of milk revenue
-- ('health.vaccination_reminder_days', '7', 'health'),
-- ('breeding.voluntary_waiting_period', '45', 'breeding'),
-- ('finance.fiscal_year_start_month', '7', 'finance'),  -- July (Bangladesh fiscal year)

CREATE TABLE erp.daily_summaries (
  id SERIAL PRIMARY KEY,
  summary_date DATE UNIQUE NOT NULL,
  total_milk_liters DECIMAL(8, 2),
  morning_milk_liters DECIMAL(8, 2),
  evening_milk_liters DECIMAL(8, 2),
  cows_milked INTEGER,
  avg_yield_per_cow DECIMAL(6, 2),
  bulk_tank_temp DECIMAL(4, 1),
  total_feed_cost DECIMAL(10, 2),
  total_medicine_cost DECIMAL(10, 2),
  milk_revenue DECIMAL(12, 2),
  health_events_count INTEGER DEFAULT 0,
  births_count INTEGER DEFAULT 0,
  deaths_count INTEGER DEFAULT 0,
  herd_total INTEGER,
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);
```

---

## 8. CROSS-SCHEMA RELATIONSHIPS

### 8.1 Relationship Map

```
common.users (Central Entity)
  ├── web.orders.user_id
  ├── web.addresses.user_id
  ├── web.cart_items.user_id
  ├── web.reviews.user_id
  ├── web.wishlists.user_id
  ├── web.b2b_applications.user_id
  ├── mkt.vendors.user_id (1:1)
  ├── lvs.animal_listings.seller_id
  ├── lvs.auction_bids.bidder_id
  ├── lvs.listing_offers.buyer_id
  ├── erp.employees.user_id (1:1, nullable)
  ├── erp.milk_records.recorded_by
  ├── erp.health_events.recorded_by
  └── common.audit_logs.user_id

common.payment_transactions (Shared Payment)
  ├── web.orders.payment_transaction_id
  ├── mkt.mkt_orders.payment_transaction_id
  └── lvs.livestock_transactions.payment_transaction_id

common.files (Shared File Storage)
  ├── mkt.mkt_product_images.file_id
  ├── mkt.vendor_documents.file_id
  ├── lvs.listing_images.file_id
  ├── lvs.listing_health_records.certificate_file_id
  └── web.media.file_id

erp.animals → lvs.animal_listings.source_animal_id
  (When Smart Dairy lists own animal for sale,
   livestock module references ERP animal record)
```

### 8.2 Data Integrity Rules

1. **common.users** deletion: `CASCADE` to all dependent tables (user removes all their data)
2. **erp.animals** deletion: `RESTRICT` (animal records are historical and should never be hard-deleted; use status = 'dead'/'culled'/'sold')
3. **mkt.vendors** suspension: Set status to 'suspended', all active listings become inactive
4. **Cross-schema FKs**: Enforced at database level for `common.*` references; application-level for `erp.animals → lvs.animal_listings` (since cross-schema FK behavior varies)

---

## 9. INDEXING STRATEGY

### 9.1 Common Schema Indexes

```sql
CREATE INDEX idx_users_email ON common.users(email);
CREATE INDEX idx_users_phone ON common.users(phone);
CREATE INDEX idx_users_role ON common.users(role) WHERE deleted_at IS NULL;
CREATE INDEX idx_sessions_user ON common.sessions(user_id);
CREATE INDEX idx_sessions_expires ON common.sessions(expires_at);
CREATE INDEX idx_payment_tx_module ON common.payment_transactions(module, module_order_id);
CREATE INDEX idx_payment_tx_status ON common.payment_transactions(status);
CREATE INDEX idx_notifications_user ON common.notifications(user_id, read, created_at DESC);
CREATE INDEX idx_audit_logs_user_date ON common.audit_logs(user_id, created_at DESC);
CREATE INDEX idx_audit_logs_entity ON common.audit_logs(entity_type, entity_id);
```

### 9.2 Web Schema Indexes

```sql
CREATE INDEX idx_web_products_slug ON web.products(slug);
CREATE INDEX idx_web_products_category ON web.products(category_id) WHERE status = 'active';
CREATE INDEX idx_web_products_featured ON web.products(featured) WHERE featured = TRUE AND status = 'active';
CREATE INDEX idx_web_products_fts ON web.products
  USING GIN (to_tsvector('english', name || ' ' || COALESCE(description, '')));
CREATE INDEX idx_web_orders_user ON web.orders(user_id, created_at DESC);
CREATE INDEX idx_web_orders_status ON web.orders(status);
CREATE INDEX idx_web_orders_number ON web.orders(order_number);
CREATE INDEX idx_web_blog_published ON web.blog_posts(published_at DESC) WHERE status = 'published';
```

### 9.3 Marketplace Schema Indexes

```sql
CREATE INDEX idx_mkt_vendors_status ON mkt.vendors(status);
CREATE INDEX idx_mkt_products_vendor ON mkt.mkt_products(vendor_id, status);
CREATE INDEX idx_mkt_products_category ON mkt.mkt_products(category_id) WHERE status = 'active';
CREATE INDEX idx_mkt_products_fts ON mkt.mkt_products
  USING GIN (to_tsvector('english', name || ' ' || COALESCE(description, '')));
CREATE INDEX idx_mkt_orders_buyer ON mkt.mkt_orders(buyer_id, created_at DESC);
CREATE INDEX idx_mkt_orders_vendor ON mkt.mkt_orders(vendor_id, status);
CREATE INDEX idx_mkt_payouts_vendor ON mkt.mkt_payouts(vendor_id, status);
```

### 9.4 Livestock Schema Indexes

```sql
CREATE INDEX idx_lvs_listings_status ON lvs.animal_listings(status, created_at DESC);
CREATE INDEX idx_lvs_listings_seller ON lvs.animal_listings(seller_id);
CREATE INDEX idx_lvs_listings_breed ON lvs.animal_listings(breed, status);
CREATE INDEX idx_lvs_listings_district ON lvs.animal_listings(location_district) WHERE status = 'active';
CREATE INDEX idx_lvs_listings_price ON lvs.animal_listings(asking_price) WHERE status = 'active';
CREATE INDEX idx_lvs_auctions_status ON lvs.auctions(status, end_time);
CREATE INDEX idx_lvs_auction_bids ON lvs.auction_bids(auction_id, amount DESC);
```

### 9.5 ERP Schema Indexes (Critical for Performance)

```sql
-- Animals (most joined table)
CREATE INDEX idx_erp_animals_tag ON erp.animals(tag_number);
CREATE INDEX idx_erp_animals_group ON erp.animals(current_group) WHERE status = 'active';
CREATE INDEX idx_erp_animals_status ON erp.animals(status);
CREATE INDEX idx_erp_animals_sire ON erp.animals(sire_id) WHERE sire_id IS NOT NULL;
CREATE INDEX idx_erp_animals_dam ON erp.animals(dam_id) WHERE dam_id IS NOT NULL;

-- Milk records (highest volume table)
CREATE INDEX idx_erp_milk_animal_date ON erp.milk_records(animal_id, record_date DESC);
CREATE INDEX idx_erp_milk_date ON erp.milk_records(record_date);
CREATE INDEX idx_erp_milk_session ON erp.milk_records(record_date, session);

-- Health & vaccination
CREATE INDEX idx_erp_health_animal ON erp.health_events(animal_id, event_date DESC);
CREATE INDEX idx_erp_vax_due ON erp.vaccination_records(next_due_date)
  WHERE next_due_date IS NOT NULL;
CREATE INDEX idx_erp_vax_animal ON erp.vaccination_records(animal_id, vaccination_date DESC);

-- Breeding
CREATE INDEX idx_erp_breeding_animal ON erp.breeding_records(animal_id, breeding_date DESC);
CREATE INDEX idx_erp_pregnancy_animal ON erp.pregnancy_checks(animal_id, check_date DESC);
CREATE INDEX idx_erp_calving_dam ON erp.calving_records(dam_id, calving_date DESC);
CREATE INDEX idx_erp_lactation_animal ON erp.lactation_records(animal_id, lactation_number DESC);

-- Financial
CREATE INDEX idx_erp_transactions_date ON erp.financial_transactions(transaction_date, account_id);
CREATE INDEX idx_erp_milk_sales_date ON erp.milk_sales(sale_date DESC);

-- Feed
CREATE INDEX idx_erp_feed_consumption_date ON erp.feed_consumption(record_date, group_id);

-- HR
CREATE INDEX idx_erp_attendance_emp_date ON erp.attendance(employee_id, attendance_date);

-- Daily summaries
CREATE INDEX idx_erp_daily_summaries ON erp.daily_summaries(summary_date DESC);
```

---

## 10. PARTITIONING STRATEGY

### 10.1 Partitioned Tables

Tables partitioned by year (for those exceeding 100K rows/year):

```sql
-- erp.milk_records: ~730K rows/year at 1,200 cattle
-- Already defined with PARTITION BY RANGE (record_date) in Section 7.5

-- erp.feed_consumption: ~547K rows/year at 1,200 cattle
-- Partition similarly

-- common.audit_logs: High volume, partition by month
CREATE TABLE common.audit_logs (...) PARTITION BY RANGE (created_at);
```

### 10.2 Partition Maintenance

```sql
-- Annual cron job to create next year's partitions
-- Run in December for next year's milk_records and feed_consumption

-- Example for 2028:
CREATE TABLE erp.milk_records_2028 PARTITION OF erp.milk_records
  FOR VALUES FROM ('2028-01-01') TO ('2029-01-01');
```

---

## 11. BACKUP & RECOVERY

| Frequency | Method | Retention | Target |
|-----------|--------|-----------|--------|
| Continuous | WAL streaming | RPO <5 min | DO Spaces (separate region) |
| Daily 2:00 AM | `pg_dump --format=custom` | 30 days | DO Spaces |
| Weekly | Full backup + WAL checkpoint | 12 weeks | DO Spaces |
| Monthly | Full archive | 12 months | DO Spaces |

**Recovery Priority:** `erp.*` (irreplaceable farm records) > `common.*` (user accounts) > `web.*` (orders) > `mkt.*` > `lvs.*`

---

## 12. SAMPLE QUERIES

### 12.1 ERP: Daily Milk Production Summary

```sql
SELECT
  mr.record_date,
  mr.session,
  COUNT(DISTINCT mr.animal_id) as cows_milked,
  SUM(mr.quantity_liters) as total_liters,
  AVG(mr.quantity_liters) as avg_per_cow,
  MIN(mr.quantity_liters) as min_yield,
  MAX(mr.quantity_liters) as max_yield
FROM erp.milk_records mr
JOIN erp.animals a ON mr.animal_id = a.id
WHERE mr.record_date = CURRENT_DATE
  AND a.status = 'active'
GROUP BY mr.record_date, mr.session
ORDER BY mr.session;
```

### 12.2 ERP: Animals Due for Vaccination

```sql
SELECT
  a.tag_number,
  a.name,
  a.breed,
  vs.vaccine_name,
  vr.next_due_date,
  (vr.next_due_date - CURRENT_DATE) as days_until_due
FROM erp.vaccination_records vr
JOIN erp.animals a ON vr.animal_id = a.id
JOIN erp.vaccination_schedules vs ON vr.schedule_id = vs.id
WHERE vr.next_due_date <= CURRENT_DATE + INTERVAL '7 days'
  AND a.status = 'active'
ORDER BY vr.next_due_date;
```

### 12.3 ERP: Cost Per Liter Analysis

```sql
SELECT
  TO_CHAR(ft.transaction_date, 'YYYY-MM') as month,
  SUM(CASE WHEN ft.category = 'milk_sale' AND ft.type = 'credit' THEN ft.amount ELSE 0 END) as revenue,
  SUM(CASE WHEN ft.category = 'feed_purchase' THEN ft.amount ELSE 0 END) as feed_cost,
  SUM(CASE WHEN ft.category = 'medicine' THEN ft.amount ELSE 0 END) as medicine_cost,
  SUM(CASE WHEN ft.category = 'salary' THEN ft.amount ELSE 0 END) as labor_cost,
  (SELECT SUM(quantity_liters) FROM erp.milk_records
   WHERE TO_CHAR(record_date, 'YYYY-MM') = TO_CHAR(ft.transaction_date, 'YYYY-MM')) as total_milk,
  SUM(CASE WHEN ft.type = 'debit' THEN ft.amount ELSE 0 END) /
    NULLIF((SELECT SUM(quantity_liters) FROM erp.milk_records
     WHERE TO_CHAR(record_date, 'YYYY-MM') = TO_CHAR(ft.transaction_date, 'YYYY-MM')), 0) as cost_per_liter
FROM erp.financial_transactions ft
GROUP BY TO_CHAR(ft.transaction_date, 'YYYY-MM')
ORDER BY month DESC;
```

### 12.4 Marketplace: Vendor Payout Calculation

```sql
SELECT
  v.id as vendor_id,
  v.company_name,
  COUNT(o.id) as orders_count,
  SUM(o.total_amount) as total_sales,
  SUM(o.commission_amount) as total_commission,
  SUM(o.vendor_payout_amount) as payout_amount
FROM mkt.vendors v
JOIN mkt.mkt_orders o ON v.id = o.vendor_id
WHERE o.status = 'delivered'
  AND o.delivered_at BETWEEN '2026-01-01' AND '2026-01-31'
  AND o.id NOT IN (SELECT order_id FROM mkt.mkt_commissions WHERE status = 'settled')
GROUP BY v.id, v.company_name;
```

### 12.5 Livestock: Active Auctions with Current Bid

```sql
SELECT
  al.listing_number,
  al.title,
  al.breed,
  a.start_time,
  a.end_time,
  a.current_price,
  a.bid_count,
  u.name as top_bidder_name,
  EXTRACT(EPOCH FROM (a.end_time - NOW())) / 60 as minutes_remaining
FROM lvs.auctions a
JOIN lvs.animal_listings al ON a.listing_id = al.id
LEFT JOIN common.users u ON a.top_bidder_id = u.id
WHERE a.status = 'active'
ORDER BY a.end_time ASC;
```

### 12.6 ERP: 3-Generation Pedigree

```sql
WITH RECURSIVE pedigree AS (
  -- Base: the animal itself
  SELECT id, tag_number, name, breed, sire_id, dam_id, 0 as generation
  FROM erp.animals WHERE id = $1

  UNION ALL

  -- Parents (generation 1)
  SELECT a.id, a.tag_number, a.name, a.breed, a.sire_id, a.dam_id, p.generation + 1
  FROM erp.animals a
  JOIN pedigree p ON a.id = p.sire_id OR a.id = p.dam_id
  WHERE p.generation < 3
)
SELECT * FROM pedigree ORDER BY generation;
```

---

**Document Status:** Complete
**Related Documents:**
- [Technical Architecture](03_Technical_Architecture_Document.md) - System architecture & module design
- [API Specification](06_API_Specification.md) - Endpoint documentation
- [Testing Plan](07_Testing_QA_Plan.md) - Database testing strategy
