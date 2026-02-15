# SMART DAIRY LTD.
## DATA DICTIONARY
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-003 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Business Analyst |

---

## DATA ENTITY DICTIONARY

### CORE ENTITIES

#### res_partner (Customers/Partners)

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Unique identifier | Auto-generated |
| name | VARCHAR(255) | Y | Full name | Min 2 characters |
| email | VARCHAR(255) | N | Email address | Unique, valid format |
| phone | VARCHAR(50) | N | Phone number | Valid BD format |
| mobile | VARCHAR(50) | N | Mobile number | Unique for customers |
| customer_type | VARCHAR(50) | Y | Type | b2c, b2b, farmer, supplier |
| is_company | BOOLEAN | N | Company flag | Default FALSE |
| company_name | VARCHAR(255) | N | Company name | Required if is_company=TRUE |
| vat_number | VARCHAR(50) | N | BIN/TIN | For B2B customers |
| credit_limit | DECIMAL(15,2) | N | Credit limit | Default 0 |
| credit_used | DECIMAL(15,2) | N | Credit used | Calculated field |
| payment_terms | INTEGER | N | Payment terms | FK to payment_terms |
| street | VARCHAR(255) | N | Street address | |
| city | VARCHAR(100) | N | City | |
| state_id | INTEGER | N | State/Division | FK to res_state |
| zip | VARCHAR(20) | N | Postal code | |
| country_id | INTEGER | N | Country | Default Bangladesh |
| partner_latitude | DECIMAL(10,7) | N | GPS Latitude | |
| partner_longitude | DECIMAL(10,7) | N | GPS Longitude | |
| active | BOOLEAN | N | Active status | Default TRUE |
| created_at | TIMESTAMP | Y | Creation date | Auto-generated |
| updated_at | TIMESTAMP | Y | Last update | Auto-updated |

#### product_template (Products)

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Unique identifier | Auto-generated |
| name | VARCHAR(255) | Y | Product name | Unique |
| default_code | VARCHAR(50) | N | SKU | Unique, auto-generated |
| categ_id | BIGINT | Y | Category | FK to product_category |
| type | VARCHAR(50) | Y | Product type | product, service, consumable |
| list_price | DECIMAL(15,2) | Y | Sale price | Must be >= 0 |
| standard_price | DECIMAL(15,2) | N | Cost price | |
| uom_id | BIGINT | Y | Unit of measure | FK to uom_uom |
| uom_po_id | BIGINT | Y | Purchase UOM | |
| description | TEXT | N | Product description | |
| description_sale | TEXT | N | Sales description | |
| active | BOOLEAN | N | Active | Default TRUE |
| sale_ok | BOOLEAN | N | Can be sold | Default TRUE |
| purchase_ok | BOOLEAN | N | Can be purchased | |
| is_subscription | BOOLEAN | N | Subscription product | Default FALSE |
| subscription_period | VARCHAR(50) | N | Period | daily, weekly, monthly |
| tracking | VARCHAR(50) | N | Lot tracking | none, lot, serial |
| barcode | VARCHAR(50) | N | Barcode | Unique |
| weight | DECIMAL(8,3) | N | Weight in kg | |
| volume | DECIMAL(8,3) | N | Volume in m³ | |
| shelf_life_days | INTEGER | N | Shelf life | For perishables |
| organic_certified | BOOLEAN | N | Organic certification | Default FALSE |

---

### FARM MANAGEMENT ENTITIES

#### farm_animal

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Animal ID | Auto-generated |
| name | VARCHAR(100) | Y | Animal tag/ID | Unique, uppercase |
| rfid_tag | VARCHAR(100) | N | RFID identifier | Unique if provided |
| ear_tag | VARCHAR(100) | N | Visible ear tag | |
| species | VARCHAR(50) | Y | Species | cattle, buffalo |
| breed_id | BIGINT | Y | Breed | FK to farm_breed |
| gender | VARCHAR(10) | Y | Gender | male, female |
| birth_date | DATE | N | Date of birth | Not in future |
| age_months | INTEGER | N | Age in months | Calculated |
| status | VARCHAR(50) | Y | Lifecycle status | calf, heifer, lactating, dry, pregnant, sold, deceased |
| barn_id | BIGINT | N | Current location | FK to farm_barn |
| pen_number | VARCHAR(50) | N | Pen within barn | |
| current_lactation | INTEGER | N | Lactation number | Default 0 |
| daily_milk_avg | DECIMAL(8,2) | N | Average daily milk | Calculated |
| mother_id | BIGINT | N | Mother | FK to farm_animal |
| father_id | BIGINT | N | Father/Sire | FK to farm_animal |
| health_status | VARCHAR(50) | N | Health status | healthy, sick, treatment, quarantine |
| purchase_date | DATE | N | Purchase date | |
| purchase_price | DECIMAL(12,2) | N | Purchase price | |
| supplier_id | BIGINT | N | Supplier | FK to res_partner |
| sale_date | DATE | N | Sale date | If status=sold |
| sale_price | DECIMAL(12,2) | N | Sale price | If status=sold |
| notes | TEXT | N | Additional notes | |
| image | VARCHAR(255) | N | Animal photo | URL to file |
| active | BOOLEAN | N | Active record | Default TRUE |
| created_at | TIMESTAMP | Y | Creation date | |
| updated_at | TIMESTAMP | Y | Last update | |

#### farm_milk_production

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Record ID | Auto-generated |
| animal_id | BIGINT | Y | Animal | FK to farm_animal |
| production_date | DATE | Y | Date of production | Not in future |
| session | VARCHAR(20) | Y | Milking session | morning, evening |
| quantity_liters | DECIMAL(8,2) | Y | Milk volume | > 0, <= 50 |
| fat_percentage | DECIMAL(5,2) | N | Fat % | 0-10 |
| snf_percentage | DECIMAL(5,2) | N | SNF % | 0-15 |
| protein_percentage | DECIMAL(5,2) | N | Protein % | 0-5 |
| lactose_percentage | DECIMAL(5,2) | N | Lactose % | 0-6 |
| density | DECIMAL(6,3) | N | Milk density | |
| temperature | DECIMAL(5,2) | N | Temperature | In Celsius |
| total_solids | DECIMAL(5,2) | N | Total solids | Calculated (fat + snf) |
| device_id | VARCHAR(100) | N | IoT device ID | |
| recorded_by | BIGINT | N | User who recorded | FK to res_users |
| notes | TEXT | N | Notes | |
| created_at | TIMESTAMP | Y | Recording time | |

#### farm_breeding_record

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Record ID | Auto-generated |
| animal_id | BIGINT | Y | Animal | FK to farm_animal (female) |
| date | DATE | Y | Breeding date | |
| heat_detected | BOOLEAN | N | Heat detected | Default FALSE |
| heat_signs | VARCHAR(100) | N | Heat signs observed | |
| breeding_type | VARCHAR(50) | Y | Type | ai, natural, et |
| semen_code | VARCHAR(100) | N | Semen code | If AI |
| bull_name | VARCHAR(255) | N | Bull name | |
| bull_breed_id | BIGINT | N | Bull breed | FK to farm_breed |
| ai_technician_id | BIGINT | N | AI technician | FK to res_partner |
| ai_company_id | BIGINT | N | Semen company | FK to res_partner |
| pregnancy_check_date | DATE | N | Pregnancy check date | |
| pregnancy_status | VARCHAR(50) | N | Pregnancy status | pending, pregnant, open |
| expected_calving_date | DATE | N | Expected calving | Calculated (+283 days) |
| calving_date | DATE | N | Actual calving date | |
| calving_ease | VARCHAR(50) | N | Calving ease | easy, assisted, difficult, c_section |
| calf_ids | ARRAY[INTEGER] | N | Born calves | FK to farm_animal |
| notes | TEXT | N | Notes | |
| created_at | TIMESTAMP | Y | Created | |

#### farm_animal_health

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Record ID | |
| animal_id | BIGINT | Y | Animal | FK to farm_animal |
| date | DATE | Y | Record date | |
| record_type | VARCHAR(50) | Y | Type | symptom, diagnosis, treatment, vaccination, deworming, checkup |
| symptoms | TEXT | N | Symptoms observed | |
| diagnosis | TEXT | N | Diagnosis | |
| treatment | TEXT | N | Treatment given | |
| medications | ARRAY[INTEGER] | N | Medications | FK to farm_medication |
| veterinarian_id | BIGINT | N | Veterinarian | FK to res_partner |
| cost | DECIMAL(12,2) | N | Treatment cost | |
| follow_up_date | DATE | N | Follow-up date | |
| status | VARCHAR(50) | N | Status | ongoing, recovered, chronic |
| withdrawal_days_milk | INTEGER | N | Milk withdrawal days | |
| withdrawal_days_meat | INTEGER | N | Meat withdrawal days | |
| created_at | TIMESTAMP | Y | Created | |

---

### SALES ENTITIES

#### sale_order

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Order ID | Auto-generated |
| name | VARCHAR(50) | Y | Order number | Unique, auto-generated |
| partner_id | BIGINT | Y | Customer | FK to res_partner |
| partner_invoice_id | BIGINT | N | Invoice address | |
| partner_shipping_id | BIGINT | N | Delivery address | |
| order_date | DATE | Y | Order date | Default today |
| confirmation_date | TIMESTAMP | N | Confirmation date | |
| validity_date | DATE | N | Valid until | |
| state | VARCHAR(50) | Y | Status | draft, sent, sale, done, cancel |
| order_type | VARCHAR(50) | Y | Order type | b2c, b2b, subscription, wholesale |
| payment_term_id | BIGINT | N | Payment terms | FK to account_payment_term |
| pricelist_id | BIGINT | Y | Price list | |
| currency_id | BIGINT | Y | Currency | |
| user_id | BIGINT | N | Salesperson | FK to res_users |
| team_id | BIGINT | N | Sales team | |
| origin | VARCHAR(255) | N | Source document | |
| client_order_ref | VARCHAR(255) | N | Customer reference | |
| amount_untaxed | DECIMAL(15,2) | Y | Untaxed amount | Calculated |
| amount_tax | DECIMAL(15,2) | Y | Tax amount | Calculated |
| amount_total | DECIMAL(15,2) | Y | Total amount | Calculated |
| invoice_status | VARCHAR(50) | N | Invoice status | no, to invoice, invoiced, upselling |
| delivery_status | VARCHAR(50) | N | Delivery status | pending, partial, done |
| note | TEXT | N | Terms and conditions | |
| subscription_id | BIGINT | N | Subscription | FK to sale_subscription |
| created_at | TIMESTAMP | Y | Created | |
| updated_at | TIMESTAMP | Y | Updated | |

#### sale_order_line

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Line ID | |
| order_id | BIGINT | Y | Order | FK to sale_order |
| sequence | INTEGER | N | Line sequence | |
| product_id | BIGINT | Y | Product | FK to product_product |
| name | TEXT | Y | Description | |
| product_uom_qty | DECIMAL(12,3) | Y | Quantity | > 0 |
| product_uom | BIGINT | Y | Unit of measure | FK to uom_uom |
| price_unit | DECIMAL(15,2) | Y | Unit price | >= 0 |
| discount | DECIMAL(5,2) | N | Discount % | 0-100 |
| price_subtotal | DECIMAL(15,2) | Y | Subtotal | Calculated |
| price_tax | DECIMAL(15,2) | Y | Tax amount | Calculated |
| price_total | DECIMAL(15,2) | Y | Total | Calculated |
| qty_delivered | DECIMAL(12,3) | N | Delivered qty | |
| qty_invoiced | DECIMAL(12,3) | N | Invoiced qty | |
| subscription_line_id | BIGINT | N | Subscription line | |

---

### INVENTORY ENTITIES

#### stock_picking (Inventory Moves)

| Field | Data Type | Required | Description | Business Rules |
|-------|-----------|----------|-------------|----------------|
| id | BIGINT | PK | Picking ID | |
| name | VARCHAR(50) | Y | Reference | Unique |
| picking_type_id | BIGINT | Y | Operation type | FK to stock_picking_type |
| state | VARCHAR(50) | Y | Status | draft, confirmed, assigned, done, cancel |
| move_type | VARCHAR(50) | N | Move type | direct, one |
| partner_id | BIGINT | N | Partner | |
| scheduled_date | TIMESTAMP | N | Scheduled date | |
| date_done | TIMESTAMP | N | Date done | |
| origin | VARCHAR(255) | N | Source document | |
| sale_id | BIGINT | N | Sale order | FK to sale_order |
| purchase_id | BIGINT | N | Purchase order | |
| location_id | BIGINT | Y | Source location | |
| location_dest_id | BIGINT | Y | Destination | |
| note | TEXT | N | Notes | |
| created_at | TIMESTAMP | Y | Created | |

---

## REFERENCE DATA

### Status Codes

#### Animal Status
| Code | Description |
|------|-------------|
| calf | Young animal, not yet weaned |
| heifer | Female, not yet had calf |
| lactating | Producing milk |
| dry | Not producing milk, between lactations |
| pregnant | Confirmed pregnant |
| sold | Sold to external party |
| deceased | Animal has died |

#### Order States
| Code | Description |
|------|-------------|
| draft | Quotation created |
| sent | Quotation sent to customer |
| sale | Confirmed order |
| done | Fully delivered and invoiced |
| cancel | Cancelled |

---

**END OF DATA DICTIONARY**
