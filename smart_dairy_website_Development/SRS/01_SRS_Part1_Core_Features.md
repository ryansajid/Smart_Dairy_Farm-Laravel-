# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)
## Smart Dairy Limited - Website Development Project
### Part 1: Introduction & Core Features

**Document Version:** 1.0  
**Date:** December 2, 2025  
**Development Context:** Solo Full-Stack Developer, Linux Environment  
**Target Timeline:** 9 months (MVP in 6 months)

---

## DEVELOPMENT CONTEXT

**Solo Developer Setup:**
- **Hardware:** Desktop PC (recommended: 16GB RAM, 4+ cores, SSD)
- **OS:** Linux (Ubuntu 22.04 LTS or similar)
- **IDE:** VSCode with extensions
- **Local Stack:** PostgreSQL, Redis, Node.js, nginx
- **Version Control:** Git + GitHub/GitLab
- **Working Hours:** Estimated 40-50 hours/week
- **Project Duration:** 9 months (3 phases)

---

## 1. INTRODUCTION

### 1.1 Purpose

This SRS document defines all functional and technical requirements for Smart Dairy's website, specifically optimized for solo development on a Linux desktop environment. It prioritizes:

- **Phased development** (MVP first, then enhancements)
- **Open-source technologies** (cost-effective, well-documented)
- **Manageable scope** for single developer
- **Local development workflow** optimized for Linux

### 1.2 Project Scope

**Smart Dairy Website Platform** - A comprehensive e-commerce and technology showcase platform featuring:

**Phase 1 (Months 1-3) - Foundation:**
- Public website with product catalog
- Shopping cart and checkout
- Payment integration (bKash, Nagad, cards)
- Basic user accounts
- Admin panel for content/orders

**Phase 2 (Months 4-6) - Advanced Features:**
- Virtual farm tour (360°)
- Technology showcase pages
- B2B portal with bulk ordering
- Subscription system
- Blog/content platform

**Phase 3 (Months 7-9) - Polish & Launch:**
- Testing and optimization
- Content population
- Security hardening
- Performance tuning
- Soft launch and public launch

### 1.3 Technology Stack (Solo Developer Optimized)

**Frontend:**
```
- Framework: Next.js 14+ (React with SSR/SSG)
- Styling: Tailwind CSS
- State: Zustand or Redux Toolkit
- Forms: React Hook Form + Zod
- HTTP: Axios
```

**Backend:**
```
- Runtime: Node.js 18 LTS+
- Framework: Express.js with TypeScript
- Authentication: JWT + Passport.js
- API: RESTful (GraphQL optional for Phase 2)
```

**Database:**
```
- Primary: PostgreSQL 14+
- Cache: Redis 6+
- Search: PostgreSQL Full-Text (or Meilisearch later)
- Storage: Local filesystem (S3 for production)
```

**Development Tools:**
```
- IDE: VSCode with extensions:
  - ESLint, Prettier
  - Tailwind CSS IntelliSense
  - PostgreSQL
  - Git Graph
  - Thunder Client (API testing)
- Version Control: Git
- API Testing: Thunder Client / Postman
- Database UI: DBeaver or pgAdmin
```

**Reasons for Stack Choice:**
- **Next.js:** SEO-friendly, fast development, great docs
- **TypeScript:** Type safety reduces bugs (crucial for solo dev)
- **PostgreSQL:** Robust, supports complex queries, JSON storage
- **Express:** Simple, flexible, huge community
- **Tailwind:** Rapid UI development, no context switching
- All are free, open-source, and well-documented

### 1.4 Project Phases Timeline

```
Phase 1: Foundation (Months 1-3)
├── Month 1: Setup + Frontend Structure
│   ├── Week 1-2: Environment setup, design system
│   └── Week 3-4: Homepage, product pages
├── Month 2: Backend + Database
│   ├── Week 5-6: API structure, database schema
│   └── Week 7-8: Authentication, product management
└── Month 3: E-commerce
    ├── Week 9-10: Cart, checkout flow
    └── Week 11-12: Payment integration, testing

Phase 2: Advanced Features (Months 4-6)
├── Month 4: Technology Showcase
│   ├── Week 13-14: Virtual tour (360°)
│   └── Week 15-16: Dashboard, farm data
├── Month 5: B2B Portal
│   ├── Week 17-18: B2B registration, dashboard
│   └── Week 19-20: Bulk ordering, quotes
└── Month 6: Content & Engagement
    ├── Week 21-22: Blog, CMS integration
    └── Week 23-24: Subscriptions, reviews

Phase 3: Launch Preparation (Months 7-9)
├── Month 7: Testing & Optimization
│   ├── Week 25-26: Bug fixes, performance
│   └── Week 27-28: Security audit, content
├── Month 8: Pre-Launch
│   ├── Week 29-30: Beta testing
│   └── Week 31-32: Final polish
└── Month 9: Launch
    ├── Week 33-34: Soft launch
    └── Week 35-36: Public launch, monitoring
```

---

## 2. FUNCTIONAL REQUIREMENTS

### 2.1 USER AUTHENTICATION & AUTHORIZATION

#### 2.1.1 User Registration

**Priority:** High | **Phase:** 1 | **Effort:** 3 days

**User Story:**
```
As a customer
I want to create an account
So that I can place orders and track them
```

**Requirements:**

```typescript
// Registration Data Model
interface UserRegistration {
  name: string;              // min 2, max 100 chars
  email: string;             // valid email format
  phone: string;             // Bangladesh format: +8801XXXXXXXXX
  password: string;          // min 8 chars, 1 uppercase, 1 number
  confirmPassword: string;   // must match password
  agreedToTerms: boolean;    // must be true
}
```

**Functional Steps:**
1. User fills registration form
2. System validates all fields client-side
3. System checks if email/phone already exists
4. System hashes password (bcrypt, 10 rounds)
5. System creates user record (status: pending)
6. System sends verification email with token
7. System sends OTP to phone via SMS
8. User verifies email (click link)
9. User enters OTP to verify phone
10. System activates account (status: active)

**API Endpoint:**
```
POST /api/auth/register
Body: { name, email, phone, password }
Response: { message, userId, requiresVerification }
```

**Database Tables:**
```sql
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  status VARCHAR(20) DEFAULT 'pending',
  email_verified BOOLEAN DEFAULT FALSE,
  phone_verified BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE verification_tokens (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id),
  token VARCHAR(255) UNIQUE NOT NULL,
  type VARCHAR(20) NOT NULL, -- 'email' or 'phone'
  expires_at TIMESTAMP NOT NULL,
  used BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT NOW()
);
```

**Validation Rules:**
- Email: RFC 5322 compliant
- Phone: Regex `/^(\+8801|01)[3-9]\d{8}$/`
- Password strength: zxcvbn library (min score 2)
- All fields sanitized to prevent XSS

**Error Handling:**
- Email exists: "Email already registered"
- Phone exists: "Phone number already registered"
- Weak password: "Password too weak, try adding more characters"
- Network error: "Please check your connection and try again"

**Acceptance Criteria:**
- ✓ User can register with valid details
- ✓ Duplicate email/phone rejected
- ✓ Verification email and SMS sent
- ✓ Account activated after verification
- ✓ Error messages are clear

#### 2.1.2 User Login

**Priority:** High | **Phase:** 1 | **Effort:** 2 days

**Requirements:**

```typescript
interface LoginCredentials {
  identifier: string;  // email or phone
  password: string;
  rememberMe?: boolean;
}

interface LoginResponse {
  success: boolean;
  token: string;  // JWT
  refreshToken: string;
  user: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
}
```

**Functional Steps:**
1. User enters email/phone + password
2. System identifies if input is email or phone
3. System retrieves user from database
4. System verifies password (bcrypt.compare)
5. System checks account status (must be active)
6. System checks if email/phone verified
7. System generates JWT token (expires 1 hour)
8. System generates refresh token (expires 30 days)
9. System records login timestamp
10. System returns tokens and user data

**JWT Payload:**
```typescript
interface JWTPayload {
  userId: number;
  email: string;
  role: string;  // 'customer', 'b2b', 'admin'
  iat: number;   // issued at
  exp: number;   // expiry
}
```

**Security Measures:**
- Rate limiting: 5 attempts per 15 minutes per IP
- Account lockout: After 5 failed attempts for 30 minutes
- CAPTCHA: After 3 failed attempts
- Password not logged anywhere
- Use HTTPS only

**API Endpoint:**
```
POST /api/auth/login
Body: { identifier, password, rememberMe }
Response: { token, refreshToken, user }
```

**Acceptance Criteria:**
- ✓ User can login with email or phone
- ✓ JWT token generated and valid
- ✓ Wrong password rejected
- ✓ Unverified accounts cannot login
- ✓ Rate limiting prevents brute force

---

### 2.2 PRODUCT CATALOG

#### 2.2.1 Product Display

**Priority:** High | **Phase:** 1 | **Effort:** 5 days

**Database Schema:**

```sql
CREATE TABLE categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  image_url VARCHAR(255),
  parent_id INTEGER REFERENCES categories(id),
  display_order INTEGER DEFAULT 0,
  active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  sku VARCHAR(50) UNIQUE NOT NULL,
  description TEXT,
  short_description VARCHAR(500),
  category_id INTEGER REFERENCES categories(id),
  
  -- Pricing
  price DECIMAL(10, 2) NOT NULL,
  sale_price DECIMAL(10, 2),
  cost_price DECIMAL(10, 2),
  
  -- Inventory
  stock_quantity INTEGER DEFAULT 0,
  low_stock_threshold INTEGER DEFAULT 10,
  allow_backorder BOOLEAN DEFAULT FALSE,
  
  -- Product Details
  weight DECIMAL(8, 2),  -- in kg
  unit VARCHAR(20),      -- 'ml', 'L', 'kg', 'pieces'
  package_size VARCHAR(50),  -- '500ml', '1L', etc.
  
  -- Nutritional Info (JSON)
  nutritional_info JSONB,
  ingredients TEXT,
  allergens VARCHAR(255),
  
  -- Farm Data
  farm_origin VARCHAR(100),
  batch_number VARCHAR(50),
  production_date DATE,
  expiry_days INTEGER,
  
  -- SEO
  meta_title VARCHAR(255),
  meta_description TEXT,
  meta_keywords VARCHAR(255),
  
  -- Status
  status VARCHAR(20) DEFAULT 'draft',  -- draft, active, inactive
  featured BOOLEAN DEFAULT FALSE,
  new_arrival BOOLEAN DEFAULT FALSE,
  
  -- Ratings
  average_rating DECIMAL(3, 2) DEFAULT 0,
  review_count INTEGER DEFAULT 0,
  
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE product_images (
  id SERIAL PRIMARY KEY,
  product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
  image_url VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255),
  display_order INTEGER DEFAULT 0,
  is_primary BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE product_variants (
  id SERIAL PRIMARY KEY,
  product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
  name VARCHAR(100) NOT NULL,  -- e.g., "1 Liter"
  sku VARCHAR(50) UNIQUE NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  stock_quantity INTEGER DEFAULT 0,
  weight DECIMAL(8, 2),
  active BOOLEAN DEFAULT TRUE
);
```

**Product List API:**
```
GET /api/products
Query Parameters:
  - page: number (default: 1)
  - limit: number (default: 20, max: 100)
  - category: string (slug)
  - priceMin: number
  - priceMax: number
  - inStock: boolean
  - featured: boolean
  - sort: string (price_asc, price_desc, name, rating, newest)
  - search: string

Response: {
  products: Product[],
  pagination: {
    page, limit, total, totalPages
  },
  filters: {
    categories, priceRange
  }
}
```

**Product Detail API:**
```
GET /api/products/:slug

Response: {
  product: {
    id, name, slug, sku,
    description, shortDescription,
    price, salePrice,
    images: Image[],
    variants: Variant[],
    category: Category,
    stock_quantity,
    nutritionalInfo,
    ingredients,
    allergens,
    farmOrigin,
    averageRating,
    reviewCount,
    relatedProducts: Product[]
  }
}
```

**Acceptance Criteria:**
- ✓ Products display in grid layout (responsive)
- ✓ Filtering by category, price works
- ✓ Sorting works correctly
- ✓ Search returns relevant results
- ✓ Product detail shows all information
- ✓ Images load with lazy loading
- ✓ Out of stock products marked clearly
- ✓ Sale badges show discount percentage

---

### 2.3 SHOPPING CART

**Priority:** High | **Phase:** 1 | **Effort:** 4 days

**Cart State Management:**
```typescript
// Frontend: Zustand Store
interface CartItem {
  productId: number;
  variantId?: number;
  name: string;
  image: string;
  price: number;
  quantity: number;
  maxQuantity: number;  // stock available
}

interface CartState {
  items: CartItem[];
  subtotal: number;
  total: number;
  discountAmount: number;
  deliveryFee: number;
  promoCode: string | null;
  
  // Actions
  addItem: (product, quantity) => void;
  removeItem: (productId) => void;
  updateQuantity: (productId, quantity) => void;
  applyPromo: (code) => Promise<void>;
  clearCart: () => void;
  syncWithServer: () => Promise<void>;
}
```

**Database Schema:**
```sql
CREATE TABLE cart (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
  session_id VARCHAR(255),  -- for guest carts
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW(),
  expires_at TIMESTAMP  -- 7 days for guest, null for users
);

CREATE TABLE cart_items (
  id SERIAL PRIMARY KEY,
  cart_id INTEGER REFERENCES cart(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id),
  variant_id INTEGER REFERENCES product_variants(id),
  quantity INTEGER NOT NULL CHECK (quantity > 0),
  price DECIMAL(10, 2) NOT NULL,  -- price at time of adding
  created_at TIMESTAMP DEFAULT NOW(),
  UNIQUE(cart_id, product_id, variant_id)
);

CREATE TABLE promo_codes (
  id SERIAL PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  description TEXT,
  discount_type VARCHAR(20) NOT NULL,  -- 'percentage', 'fixed'
  discount_value DECIMAL(10, 2) NOT NULL,
  min_order_value DECIMAL(10, 2),
  max_discount DECIMAL(10, 2),
  valid_from TIMESTAMP NOT NULL,
  valid_until TIMESTAMP NOT NULL,
  usage_limit INTEGER,
  used_count INTEGER DEFAULT 0,
  active BOOLEAN DEFAULT TRUE
);
```

**Cart APIs:**
```
POST /api/cart/add
Body: { productId, variantId?, quantity }
Response: { cart, message }

PUT /api/cart/update/:itemId
Body: { quantity }
Response: { cart }

DELETE /api/cart/remove/:itemId
Response: { cart }

GET /api/cart
Response: { cart: { items, subtotal, total } }

POST /api/cart/apply-promo
Body: { code }
Response: { cart, discount, message }
```

**Business Logic:**
```typescript
// Calculate Cart Total
function calculateCart(items, promoCode?, deliveryFee?) {
  const subtotal = items.reduce((sum, item) => {
    return sum + (item.price * item.quantity);
  }, 0);
  
  let discountAmount = 0;
  if (promoCode && promoCode.active) {
    if (subtotal >= promoCode.minOrderValue) {
      if (promoCode.discountType === 'percentage') {
        discountAmount = (subtotal * promoCode.discountValue) / 100;
        if (promoCode.maxDiscount) {
          discountAmount = Math.min(discountAmount, promoCode.maxDiscount);
        }
      } else {
        discountAmount = promoCode.discountValue;
      }
    }
  }
  
  const total = subtotal - discountAmount + (deliveryFee || 0);
  
  return { subtotal, discountAmount, total };
}
```

**Cart Persistence:**
- Guest users: localStorage + session_id cookie
- Logged-in users: Database (synced on every action)
- Guest cart merged with user cart on login
- Cart expires after 7 days for guests

**Acceptance Criteria:**
- ✓ Add product to cart (with quantity selector)
- ✓ Cart persists across page refreshes
- ✓ Quantity can be updated
- ✓ Items can be removed
- ✓ Stock validation prevents over-ordering
- ✓ Promo code applies discount correctly
- ✓ Cart syncs on login (merge guest + user carts)
- ✓ Mini cart in header shows item count

---

### 2.4 CHECKOUT PROCESS

**Priority:** High | **Phase:** 1 | **Effort:** 7 days

**Checkout Flow:**
```
Step 1: Authentication
  ├── Logged in? → Step 2
  ├── Guest? → Guest form → Step 2
  └── New user? → Register → Step 2

Step 2: Delivery Address
  ├── Use saved address
  └── Add new address

Step 3: Delivery Time
  ├── Same day (if before cutoff)
  ├── Next day
  └── Scheduled

Step 4: Payment Method
  ├── bKash
  ├── Nagad
  ├── Rocket
  ├── Credit/Debit Card
  └── Cash on Delivery

Step 5: Review & Confirm
  └── Place Order
```

**Database Schema:**
```sql
CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  order_number VARCHAR(50) UNIQUE NOT NULL,  -- SD-2025-001234
  user_id INTEGER REFERENCES users(id),
  guest_email VARCHAR(255),
  guest_phone VARCHAR(20),
  guest_name VARCHAR(100),
  
  -- Order Details
  status VARCHAR(50) DEFAULT 'pending',
  -- Status flow: pending → confirmed → processing → packed → 
  --              shipped → delivered / cancelled / returned
  
  -- Amounts
  subtotal DECIMAL(10, 2) NOT NULL,
  discount_amount DECIMAL(10, 2) DEFAULT 0,
  delivery_fee DECIMAL(10, 2) DEFAULT 0,
  total_amount DECIMAL(10, 2) NOT NULL,
  
  -- Delivery
  delivery_name VARCHAR(100) NOT NULL,
  delivery_phone VARCHAR(20) NOT NULL,
  delivery_email VARCHAR(255),
  delivery_address TEXT NOT NULL,
  delivery_city VARCHAR(100),
  delivery_area VARCHAR(100),
  delivery_postal_code VARCHAR(20),
  delivery_landmark VARCHAR(255),
  delivery_instructions TEXT,
  delivery_date DATE,
  delivery_time_slot VARCHAR(50),  -- 'morning', 'afternoon', 'evening'
  
  -- Payment
  payment_method VARCHAR(50) NOT NULL,
  payment_status VARCHAR(50) DEFAULT 'pending',
  -- Payment status: pending, completed, failed, refunded
  payment_transaction_id VARCHAR(255),
  payment_details JSONB,  -- store gateway response
  
  -- Tracking
  tracking_number VARCHAR(100),
  delivery_person_name VARCHAR(100),
  delivery_person_phone VARCHAR(20),
  
  -- Timestamps
  order_date TIMESTAMP DEFAULT NOW(),
  confirmed_at TIMESTAMP,
  shipped_at TIMESTAMP,
  delivered_at TIMESTAMP,
  cancelled_at TIMESTAMP,
  
  -- Additional
  promo_code VARCHAR(50),
  notes TEXT,
  admin_notes TEXT,
  
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE order_items (
  id SERIAL PRIMARY KEY,
  order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
  product_id INTEGER REFERENCES products(id),
  variant_id INTEGER REFERENCES product_variants(id),
  product_name VARCHAR(255) NOT NULL,
  product_sku VARCHAR(50) NOT NULL,
  quantity INTEGER NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE order_status_history (
  id SERIAL PRIMARY KEY,
  order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
  status VARCHAR(50) NOT NULL,
  notes TEXT,
  created_by INTEGER REFERENCES users(id),
  created_at TIMESTAMP DEFAULT NOW()
);
```

**Checkout API:**
```
POST /api/checkout/init
Body: { cartId }
Response: { checkoutSession, deliveryOptions }

POST /api/checkout/delivery
Body: { name, phone, email, address, city, area, postalCode, 
        landmark, instructions, deliveryDate, timeSlot }
Response: { session, deliveryFee }

POST /api/checkout/payment-method
Body: { method }
Response: { session }

POST /api/checkout/place-order
Body: { sessionId, agreedToTerms }
Response: { 
  orderId, 
  orderNumber, 
  paymentUrl (if online payment),
  message 
}
```

**Payment Integration:**

**1. bKash Integration:**
```typescript
// bKash Tokenized Checkout
async function processBkashPayment(orderId, amount) {
  // 1. Get bKash Token
  const authResponse = await axios.post(
    'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant',
    {
      app_key: process.env.BKASH_APP_KEY,
      app_secret: process.env.BKASH_APP_SECRET
    }
  );
  
  const token = authResponse.data.id_token;
  
  // 2. Create Payment
  const createResponse = await axios.post(
    'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create',
    {
      mode: '0011',  // Tokenized checkout
      payerReference: orderId,
      callbackURL: `${process.env.APP_URL}/api/payment/bkash/callback`,
      amount: amount.toString(),
      currency: 'BDT',
      intent: 'sale',
      merchantInvoiceNumber: `SD-${orderId}`
    },
    {
      headers: {
        Authorization: `Bearer ${token}`,
        'X-APP-Key': process.env.BKASH_APP_KEY
      }
    }
  );
  
  return {
    paymentId: createResponse.data.paymentID,
    bkashURL: createResponse.data.bkashURL
  };
}

// Callback Handler
async function handleBkashCallback(paymentId, status) {
  if (status === 'success') {
    // Execute payment
    const executeResponse = await axios.post(
      'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/execute',
      { paymentID: paymentId },
      { headers: { Authorization: `Bearer ${token}` } }
    );
    
    if (executeResponse.data.statusCode === '0000') {
      // Payment successful
      await updateOrderPaymentStatus(orderId, 'completed', executeResponse.data);
    }
  } else {
    // Payment failed
    await updateOrderPaymentStatus(orderId, 'failed');
  }
}
```

**2. SSL Commerz Integration:**
```typescript
async function processSSLCommerzPayment(orderId, amount, customerInfo) {
  const initiateData = {
    store_id: process.env.SSLCOMMERZ_STORE_ID,
    store_passwd: process.env.SSLCOMMERZ_STORE_PASSWORD,
    total_amount: amount,
    currency: 'BDT',
    tran_id: `SD-${orderId}-${Date.now()}`,
    success_url: `${process.env.APP_URL}/api/payment/sslcommerz/success`,
    fail_url: `${process.env.APP_URL}/api/payment/sslcommerz/fail`,
    cancel_url: `${process.env.APP_URL}/api/payment/sslcommerz/cancel`,
    cus_name: customerInfo.name,
    cus_email: customerInfo.email,
    cus_phone: customerInfo.phone,
    cus_add1: customerInfo.address,
    cus_city: customerInfo.city,
    cus_country: 'Bangladesh',
    shipping_method: 'Courier',
    product_name: 'Smart Dairy Products',
    product_category: 'Dairy',
    product_profile: 'general'
  };
  
  const response = await axios.post(
    'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
    new URLSearchParams(initiateData)
  );
  
  if (response.data.status === 'SUCCESS') {
    return {
      gatewayUrl: response.data.GatewayPageURL
    };
  }
}
```

**Order Confirmation Email:**
```typescript
async function sendOrderConfirmation(order) {
  const emailTemplate = `
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="UTF-8">
        <title>Order Confirmation - Smart Dairy</title>
      </head>
      <body>
        <h1>Thank you for your order!</h1>
        <p>Hi ${order.delivery_name},</p>
        <p>Your order has been confirmed. Here are the details:</p>
        
        <h2>Order #${order.order_number}</h2>
        <p>Order Date: ${formatDate(order.order_date)}</p>
        <p>Estimated Delivery: ${formatDate(order.delivery_date)}</p>
        
        <h3>Order Summary</h3>
        <table>
          ${order.items.map(item => `
            <tr>
              <td>${item.product_name}</td>
              <td>${item.quantity}</td>
              <td>৳${item.total_price}</td>
            </tr>
          `).join('')}
        </table>
        
        <p><strong>Total: ৳${order.total_amount}</strong></p>
        
        <p>
          <a href="${process.env.APP_URL}/track-order/${order.order_number}">
            Track Your Order
          </a>
        </p>
        
        <p>Thank you for choosing Smart Dairy!</p>
      </body>
    </html>
  `;
  
  await sendEmail({
    to: order.guest_email || order.user.email,
    subject: `Order Confirmation - ${order.order_number}`,
    html: emailTemplate,
    attachments: [
      {
        filename: `invoice-${order.order_number}.pdf`,
        content: await generateInvoicePDF(order)
      }
    ]
  });
}
```

**Acceptance Criteria:**
- ✓ Checkout flow completes all steps
- ✓ Guest checkout works without registration
- ✓ Delivery address saved for logged-in users
- ✓ Delivery fee calculated correctly
- ✓ Payment gateways redirect correctly
- ✓ Order created after successful payment
- ✓ Order confirmation email sent
- ✓ Inventory updated after order
- ✓ COD orders created without payment

---

## 3. ADMIN PANEL

**Priority:** High | **Phase:** 1 | **Effort:** 8 days

### 3.1 Admin Dashboard

**URL:** `/admin/dashboard`

**Components:**
```typescript
interface DashboardMetrics {
  today: {
    revenue: number;
    orders: number;
    newCustomers: number;
    visitors: number;
  };
  thisMonth: {
    revenue: number;
    orders: number;
    newCustomers: number;
    averageOrderValue: number;
  };
  charts: {
    salesTrend: { date: string, revenue: number }[];
    topProducts: { product: string, quantity: number }[];
    ordersByStatus: { status: string, count: number }[];
  };
  recentOrders: Order[];
  lowStockProducts: Product[];
  pendingActions: {
    pendingOrders: number;
    pendingB2BApprovals: number;
    pendingReviews: number;
  };
}
```

**API:**
```
GET /api/admin/dashboard
Response: DashboardMetrics
```

### 3.2 Order Management

**Features:**
- View all orders (with filters: status, date range, payment method)
- Search orders by order number, customer name, phone
- View order details
- Update order status
- Assign tracking number
- Print invoice/packing slip
- Process refunds
- Add admin notes

**Order Status Workflow:**
```
pending → confirmed → processing → packed → shipped → delivered
         ↓
      cancelled (if payment failed or customer cancels)
```

**API Endpoints:**
```
GET /api/admin/orders
  Query: page, limit, status, dateFrom, dateTo, search
  Response: { orders, pagination }

GET /api/admin/orders/:id
  Response: { order with full details }

PATCH /api/admin/orders/:id/status
  Body: { status, notes }
  Response: { order }

PATCH /api/admin/orders/:id/tracking
  Body: { trackingNumber, deliveryPerson, deliveryPhone }
  Response: { order }

POST /api/admin/orders/:id/refund
  Body: { amount, reason }
  Response: { order, refundDetails }
```

### 3.3 Product Management

**Features:**
- Add new product
- Edit product details
- Upload/manage product images
- Set pricing (regular, sale)
- Manage inventory (stock quantity)
- Bulk actions (activate, deactivate, delete)
- Import products (CSV)
- Export products (CSV)

**Product Form Fields:**
- Basic Info: name, SKU, category
- Description: short, long (rich text editor)
- Pricing: price, sale price, cost
- Inventory: stock, low stock threshold, allow backorder
- Attributes: weight, unit, package size
- Nutritional Info: JSON editor or form
- Images: drag-drop upload, reorder, set primary
- SEO: meta title, description, keywords
- Status: draft, active, inactive

### 3.4 Customer Management

**Features:**
- View all customers
- Search by name, email, phone
- View customer details (profile, orders, wishlist)
- Edit customer information
- View order history
- Block/unblock customer
- Send email to customer

### 3.5 Content Management

**Blog Posts:**
- WYSIWYG editor (TinyMCE or similar)
- Category management
- Featured image
- SEO fields
- Publish/schedule
- Draft/published status

**Pages:**
- About Us, Contact, Terms, Privacy Policy
- Drag-drop page builder (or rich text editor)
- Custom URL slugs

### 3.6 Reports

**Available Reports:**
- Sales report (daily, weekly, monthly)
- Product performance report
- Customer report (new, repeat, churn)
- Inventory report (stock levels, low stock)
- Payment report (by method, success rate)

**Export:** Excel, PDF

---

*Document continues with remaining sections in Part 2...*

**Files to be created:**
1. ✅ SRS Part 1 (this file)
2. SRS Part 2 (Remaining features)
3. Technical Architecture
4. Database Design
5. API Specifications
6. Development Setup Guide
7. Testing Strategy
8. Deployment Guide

**Next Section Preview:**
- B2B Portal Features
- Virtual Farm Tour
- Content Management
- Integration Specifications
- Security Requirements
