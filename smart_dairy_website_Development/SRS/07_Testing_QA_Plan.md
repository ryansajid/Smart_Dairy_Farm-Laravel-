# Smart Dairy Limited - Testing & Quality Assurance Plan
## Comprehensive Testing Strategy

**Version:** 1.0  
**Date:** December 2, 2025  
**Testing Approach:** Test-Driven Development (TDD) where practical

---

## TABLE OF CONTENTS

1. [Testing Strategy Overview](#1-testing-strategy-overview)
2. [Testing Environment Setup](#2-testing-environment-setup)
3. [Unit Testing](#3-unit-testing)
4. [Integration Testing](#4-integration-testing)
5. [End-to-End Testing](#5-end-to-end-testing)
6. [API Testing](#6-api-testing)
7. [Performance Testing](#7-performance-testing)
8. [Security Testing](#8-security-testing)
9. [Browser & Device Testing](#9-browser--device-testing)
10. [Accessibility Testing](#10-accessibility-testing)
11. [Test Coverage & Reporting](#11-test-coverage--reporting)
12. [Bug Tracking & Management](#12-bug-tracking--management)

---

## 1. TESTING STRATEGY OVERVIEW

### 1.1 Testing Pyramid

```
                    ╱╲
                   ╱  ╲
                  ╱ E2E ╲         ~10% (UI, Critical Flows)
                 ╱________╲
                ╱          ╲
               ╱ Integration╲      ~20% (API, Database)
              ╱______________╲
             ╱                ╲
            ╱   Unit Tests     ╲   ~70% (Functions, Components)
           ╱____________________╲
```

### 1.2 Testing Principles

**For Solo Development:**
1. **Focus on Critical Paths:** Test what matters most to business
2. **Automate Where Possible:** Reduce manual testing burden
3. **Test Early, Test Often:** Catch bugs during development
4. **Balance Coverage vs. Time:** Aim for 70% coverage, not 100%
5. **Integration Over Unit:** For solo dev, integration tests give more value
6. **Use Real Data:** Test with production-like data

### 1.3 Testing Goals

**Coverage Targets:**
- Unit Tests: 70% code coverage
- Integration Tests: All critical APIs
- E2E Tests: Top 10 user flows
- Manual Testing: 100% of new features

**Quality Metrics:**
- Zero critical bugs in production
- < 5% bug rate per feature
- All tests pass before deployment
- < 10 minutes full test suite execution

---

## 2. TESTING ENVIRONMENT SETUP

### 2.1 Development Environment

```bash
# Install testing dependencies
npm install --save-dev \
  jest \
  @testing-library/react \
  @testing-library/jest-dom \
  @testing-library/user-event \
  supertest \
  @playwright/test
```

### 2.2 Test Database

**Setup Test PostgreSQL:**
```bash
# Create test database
createdb smartdairy_test

# Environment variable
export DATABASE_URL="postgresql://localhost:5432/smartdairy_test"
export NODE_ENV="test"
```

**Database Seeding:**
```javascript
// tests/setup/seed.js
const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function seedTestData() {
  // Clean database
  await prisma.order.deleteMany();
  await prisma.product.deleteMany();
  await prisma.user.deleteMany();
  
  // Seed test data
  await prisma.user.create({
    data: {
      email: 'test@example.com',
      name: 'Test User',
      password: 'hashed_password',
      role: 'customer'
    }
  });
  
  await prisma.product.createMany({
    data: [
      {
        name: 'Test Milk',
        sku: 'TEST-001',
        price: 95.00,
        stock: 100
      }
    ]
  });
}

module.exports = { seedTestData };
```

### 2.3 Jest Configuration

```javascript
// jest.config.js
module.exports = {
  testEnvironment: 'node',
  coverageDirectory: 'coverage',
  collectCoverageFrom: [
    'src/**/*.{js,jsx}',
    '!src/**/*.test.{js,jsx}',
    '!src/index.js'
  ],
  coverageThreshold: {
    global: {
      statements: 70,
      branches: 70,
      functions: 70,
      lines: 70
    }
  },
  setupFilesAfterEnv: ['<rootDir>/tests/setup/jest.setup.js'],
  testMatch: [
    '**/__tests__/**/*.test.js',
    '**/?(*.)+(spec|test).js'
  ]
};
```

---

## 3. UNIT TESTING

### 3.1 Backend Unit Tests

**Example: User Service Tests**

```javascript
// src/services/__tests__/userService.test.js
const { createUser, authenticateUser } = require('../userService');
const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

describe('UserService', () => {
  beforeAll(async () => {
    // Setup test database
    await seedTestData();
  });

  afterAll(async () => {
    // Cleanup
    await prisma.$disconnect();
  });

  describe('createUser', () => {
    it('should create a new user with hashed password', async () => {
      const userData = {
        name: 'John Doe',
        email: 'john@example.com',
        password: 'SecurePass123!',
        phone: '+8801712345678'
      };

      const user = await createUser(userData);

      expect(user).toHaveProperty('id');
      expect(user.email).toBe(userData.email);
      expect(user.password).not.toBe(userData.password); // Should be hashed
      expect(user.password).toMatch(/^\$2[aby]\$/); // bcrypt hash format
    });

    it('should throw error if email already exists', async () => {
      const userData = {
        name: 'Jane Doe',
        email: 'test@example.com', // Already exists
        password: 'Pass123!',
        phone: '+8801712345679'
      };

      await expect(createUser(userData)).rejects.toThrow('Email already exists');
    });

    it('should validate email format', async () => {
      const userData = {
        name: 'Invalid User',
        email: 'invalid-email',
        password: 'Pass123!',
        phone: '+8801712345679'
      };

      await expect(createUser(userData)).rejects.toThrow('Invalid email format');
    });
  });

  describe('authenticateUser', () => {
    it('should return user and token for valid credentials', async () => {
      const result = await authenticateUser('test@example.com', 'TestPass123!');

      expect(result).toHaveProperty('user');
      expect(result).toHaveProperty('accessToken');
      expect(result).toHaveProperty('refreshToken');
      expect(result.user.email).toBe('test@example.com');
    });

    it('should throw error for invalid password', async () => {
      await expect(
        authenticateUser('test@example.com', 'WrongPassword')
      ).rejects.toThrow('Invalid credentials');
    });

    it('should throw error for non-existent user', async () => {
      await expect(
        authenticateUser('nonexistent@example.com', 'Pass123!')
      ).rejects.toThrow('Invalid credentials');
    });
  });
});
```

**Example: Product Service Tests**

```javascript
// src/services/__tests__/productService.test.js
const { getProducts, getProductById, updateStock } = require('../productService');

describe('ProductService', () => {
  describe('getProducts', () => {
    it('should return paginated products', async () => {
      const result = await getProducts({ page: 1, limit: 10 });

      expect(result).toHaveProperty('products');
      expect(result).toHaveProperty('pagination');
      expect(Array.isArray(result.products)).toBe(true);
      expect(result.pagination.currentPage).toBe(1);
    });

    it('should filter products by category', async () => {
      const result = await getProducts({ category: 'milk' });

      result.products.forEach(product => {
        expect(product.category.slug).toBe('milk');
      });
    });

    it('should filter products by price range', async () => {
      const result = await getProducts({ minPrice: 50, maxPrice: 100 });

      result.products.forEach(product => {
        expect(product.price).toBeGreaterThanOrEqual(50);
        expect(product.price).toBeLessThanOrEqual(100);
      });
    });
  });

  describe('updateStock', () => {
    it('should reduce stock when order placed', async () => {
      const productId = 'prod_001';
      const initialStock = 100;
      const orderQuantity = 5;

      await updateStock(productId, -orderQuantity);
      const product = await getProductById(productId);

      expect(product.stock).toBe(initialStock - orderQuantity);
    });

    it('should throw error if insufficient stock', async () => {
      const productId = 'prod_001';
      const largeQuantity = 1000;

      await expect(
        updateStock(productId, -largeQuantity)
      ).rejects.toThrow('Insufficient stock');
    });
  });
});
```

### 3.2 Frontend Unit Tests

**Example: Component Tests**

```javascript
// src/components/__tests__/ProductCard.test.jsx
import { render, screen, fireEvent } from '@testing-library/react';
import ProductCard from '../ProductCard';

describe('ProductCard', () => {
  const mockProduct = {
    id: 'prod_001',
    name: 'Fresh Whole Milk',
    price: 95.00,
    image: 'https://example.com/milk.jpg',
    inStock: true
  };

  const mockAddToCart = jest.fn();

  it('should render product information', () => {
    render(<ProductCard product={mockProduct} onAddToCart={mockAddToCart} />);

    expect(screen.getByText('Fresh Whole Milk')).toBeInTheDocument();
    expect(screen.getByText('৳95.00')).toBeInTheDocument();
  });

  it('should call onAddToCart when button clicked', () => {
    render(<ProductCard product={mockProduct} onAddToCart={mockAddToCart} />);

    const addButton = screen.getByText('Add to Cart');
    fireEvent.click(addButton);

    expect(mockAddToCart).toHaveBeenCalledWith(mockProduct);
  });

  it('should disable button when out of stock', () => {
    const outOfStockProduct = { ...mockProduct, inStock: false };
    render(<ProductCard product={outOfStockProduct} onAddToCart={mockAddToCart} />);

    const addButton = screen.getByText('Out of Stock');
    expect(addButton).toBeDisabled();
  });

  it('should display sale badge when on sale', () => {
    const saleProduct = { ...mockProduct, salePrice: 85.00 };
    render(<ProductCard product={saleProduct} onAddToCart={mockAddToCart} />);

    expect(screen.getByText(/sale/i)).toBeInTheDocument();
    expect(screen.getByText('৳85.00')).toBeInTheDocument();
  });
});
```

**Example: Hook Tests**

```javascript
// src/hooks/__tests__/useCart.test.js
import { renderHook, act } from '@testing-library/react';
import useCart from '../useCart';

describe('useCart', () => {
  it('should initialize with empty cart', () => {
    const { result } = renderHook(() => useCart());

    expect(result.current.items).toEqual([]);
    expect(result.current.total).toBe(0);
  });

  it('should add item to cart', () => {
    const { result } = renderHook(() => useCart());
    const product = { id: '1', name: 'Milk', price: 95 };

    act(() => {
      result.current.addItem(product, 2);
    });

    expect(result.current.items).toHaveLength(1);
    expect(result.current.items[0].quantity).toBe(2);
    expect(result.current.total).toBe(190);
  });

  it('should remove item from cart', () => {
    const { result } = renderHook(() => useCart());
    const product = { id: '1', name: 'Milk', price: 95 };

    act(() => {
      result.current.addItem(product, 1);
      result.current.removeItem('1');
    });

    expect(result.current.items).toHaveLength(0);
    expect(result.current.total).toBe(0);
  });
});
```

### 3.3 Utility Function Tests

```javascript
// src/utils/__tests__/validation.test.js
const { validateEmail, validatePhone, validatePassword } = require('../validation');

describe('Validation Utils', () => {
  describe('validateEmail', () => {
    it('should accept valid email addresses', () => {
      expect(validateEmail('user@example.com')).toBe(true);
      expect(validateEmail('test.user+tag@example.co.uk')).toBe(true);
    });

    it('should reject invalid email addresses', () => {
      expect(validateEmail('invalid')).toBe(false);
      expect(validateEmail('user@')).toBe(false);
      expect(validateEmail('@example.com')).toBe(false);
    });
  });

  describe('validatePhone', () => {
    it('should accept valid Bangladesh phone numbers', () => {
      expect(validatePhone('+8801712345678')).toBe(true);
      expect(validatePhone('01712345678')).toBe(true);
    });

    it('should reject invalid phone numbers', () => {
      expect(validatePhone('123')).toBe(false);
      expect(validatePhone('+1234567890')).toBe(false);
    });
  });

  describe('validatePassword', () => {
    it('should accept strong passwords', () => {
      expect(validatePassword('SecurePass123!')).toBe(true);
    });

    it('should reject weak passwords', () => {
      expect(validatePassword('weak')).toBe(false);
      expect(validatePassword('noupppercase1!')).toBe(false);
      expect(validatePassword('NOLOWERCASE1!')).toBe(false);
      expect(validatePassword('NoNumber!')).toBe(false);
    });
  });
});
```

---

## 4. INTEGRATION TESTING

### 4.1 API Integration Tests

```javascript
// tests/integration/api/auth.test.js
const request = require('supertest');
const app = require('../../../src/app');
const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

describe('Auth API Integration', () => {
  beforeAll(async () => {
    await seedTestData();
  });

  afterAll(async () => {
    await prisma.$disconnect();
  });

  describe('POST /api/v1/auth/register', () => {
    it('should register new user successfully', async () => {
      const response = await request(app)
        .post('/api/v1/auth/register')
        .send({
          name: 'New User',
          email: 'newuser@example.com',
          phone: '+8801712345678',
          password: 'SecurePass123!',
          acceptTerms: true
        })
        .expect(201);

      expect(response.body.success).toBe(true);
      expect(response.body.data.user).toHaveProperty('id');
      expect(response.body.data.user.email).toBe('newuser@example.com');
    });

    it('should return 400 for invalid data', async () => {
      const response = await request(app)
        .post('/api/v1/auth/register')
        .send({
          name: 'Test',
          email: 'invalid-email',
          password: 'weak'
        })
        .expect(400);

      expect(response.body.success).toBe(false);
      expect(response.body.error.code).toBe('VALIDATION_ERROR');
    });

    it('should return 409 for duplicate email', async () => {
      await request(app)
        .post('/api/v1/auth/register')
        .send({
          name: 'Test',
          email: 'test@example.com', // Already exists
          phone: '+8801712345678',
          password: 'SecurePass123!',
          acceptTerms: true
        })
        .expect(409);
    });
  });

  describe('POST /api/v1/auth/login', () => {
    it('should login with valid credentials', async () => {
      const response = await request(app)
        .post('/api/v1/auth/login')
        .send({
          email: 'test@example.com',
          password: 'TestPass123!'
        })
        .expect(200);

      expect(response.body.success).toBe(true);
      expect(response.body.data).toHaveProperty('accessToken');
      expect(response.body.data).toHaveProperty('refreshToken');
    });

    it('should reject invalid credentials', async () => {
      await request(app)
        .post('/api/v1/auth/login')
        .send({
          email: 'test@example.com',
          password: 'WrongPassword'
        })
        .expect(401);
    });
  });
});
```

### 4.2 Database Integration Tests

```javascript
// tests/integration/database/order.test.js
const { PrismaClient } = require('@prisma/client');
const { createOrder, getOrdersByUser } = require('../../../src/services/orderService');

const prisma = new PrismaClient();

describe('Order Database Integration', () => {
  let testUser;
  let testProduct;

  beforeAll(async () => {
    testUser = await prisma.user.create({
      data: {
        name: 'Test User',
        email: 'ordertest@example.com',
        password: 'hashed',
        phone: '+8801712345678'
      }
    });

    testProduct = await prisma.product.create({
      data: {
        name: 'Test Product',
        sku: 'TEST-001',
        price: 100,
        stock: 50
      }
    });
  });

  afterAll(async () => {
    await prisma.order.deleteMany({
      where: { userId: testUser.id }
    });
    await prisma.user.delete({ where: { id: testUser.id } });
    await prisma.product.delete({ where: { id: testProduct.id } });
    await prisma.$disconnect();
  });

  it('should create order with items and reduce stock', async () => {
    const orderData = {
      userId: testUser.id,
      items: [
        { productId: testProduct.id, quantity: 2, price: 100 }
      ],
      deliveryAddress: {
        fullName: 'Test User',
        phone: '+8801712345678',
        addressLine1: '123 Test St',
        city: 'Dhaka',
        area: 'Gulshan'
      },
      paymentMethod: 'cod',
      total: 200
    };

    const order = await createOrder(orderData);

    expect(order).toHaveProperty('id');
    expect(order.orderNumber).toMatch(/^SD-\d{4}-\d+$/);
    expect(order.total).toBe(200);

    // Check stock reduced
    const updatedProduct = await prisma.product.findUnique({
      where: { id: testProduct.id }
    });
    expect(updatedProduct.stock).toBe(48); // 50 - 2
  });

  it('should retrieve user orders with items', async () => {
    const orders = await getOrdersByUser(testUser.id);

    expect(Array.isArray(orders)).toBe(true);
    expect(orders.length).toBeGreaterThan(0);
    expect(orders[0]).toHaveProperty('items');
    expect(orders[0].items.length).toBeGreaterThan(0);
  });
});
```

---

## 5. END-TO-END TESTING

### 5.1 Playwright Setup

```javascript
// playwright.config.js
const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests/e2e',
  timeout: 30000,
  retries: 1,
  use: {
    baseURL: 'http://localhost:3000',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure'
  },
  projects: [
    {
      name: 'chromium',
      use: { browserName: 'chromium' }
    },
    {
      name: 'firefox',
      use: { browserName: 'firefox' }
    },
    {
      name: 'webkit',
      use: { browserName: 'webkit' }
    }
  ]
});
```

### 5.2 Critical User Flow Tests

**Test 1: Complete Purchase Flow**

```javascript
// tests/e2e/purchaseFlow.test.js
const { test, expect } = require('@playwright/test');

test.describe('Complete Purchase Flow', () => {
  test('guest user can browse, add to cart, and checkout', async ({ page }) => {
    // 1. Visit homepage
    await page.goto('/');
    await expect(page.locator('h1')).toContainText('Smart Dairy');

    // 2. Browse products
    await page.click('text=Products');
    await expect(page).toHaveURL(/.*products/);
    await expect(page.locator('.product-card')).toHaveCount(12);

    // 3. View product details
    await page.click('.product-card:first-child');
    await expect(page).toHaveURL(/.*products\/.+/);
    await expect(page.locator('h1.product-name')).toBeVisible();

    // 4. Add to cart
    await page.click('button:has-text("Add to Cart")');
    await expect(page.locator('.cart-count')).toHaveText('1');

    // 5. View cart
    await page.click('.cart-icon');
    await expect(page).toHaveURL(/.*cart/);
    await expect(page.locator('.cart-item')).toHaveCount(1);

    // 6. Proceed to checkout
    await page.click('button:has-text("Checkout")');
    await expect(page).toHaveURL(/.*checkout/);

    // 7. Fill delivery information
    await page.fill('input[name="fullName"]', 'Test User');
    await page.fill('input[name="phone"]', '+8801712345678');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="addressLine1"]', '123 Test Street');
    await page.selectOption('select[name="city"]', 'Dhaka');
    await page.selectOption('select[name="area"]', 'Gulshan');

    // 8. Select payment method
    await page.click('input[value="cod"]');

    // 9. Accept terms
    await page.check('input[name="acceptTerms"]');

    // 10. Place order
    await page.click('button:has-text("Place Order")');

    // 11. Verify order confirmation
    await expect(page).toHaveURL(/.*order-confirmation/);
    await expect(page.locator('.order-number')).toBeVisible();
    await expect(page.locator('text=Thank you')).toBeVisible();
  });
});
```

**Test 2: User Registration and Login**

```javascript
// tests/e2e/userAuth.test.js
const { test, expect } = require('@playwright/test');

test.describe('User Authentication', () => {
  test('new user can register and login', async ({ page }) => {
    const timestamp = Date.now();
    const testEmail = `test${timestamp}@example.com`;

    // 1. Navigate to registration
    await page.goto('/');
    await page.click('text=Sign Up');
    await expect(page).toHaveURL(/.*register/);

    // 2. Fill registration form
    await page.fill('input[name="name"]', 'Test User');
    await page.fill('input[name="email"]', testEmail);
    await page.fill('input[name="phone"]', '+8801712345678');
    await page.fill('input[name="password"]', 'TestPass123!');
    await page.fill('input[name="confirmPassword"]', 'TestPass123!');
    await page.check('input[name="acceptTerms"]');

    // 3. Submit registration
    await page.click('button[type="submit"]');

    // 4. Verify success message
    await expect(page.locator('.success-message')).toBeVisible();
    await expect(page.locator('text=Verification email sent')).toBeVisible();

    // 5. Navigate to login (skip email verification in test)
    await page.goto('/login');

    // 6. Login with new credentials
    await page.fill('input[name="email"]', testEmail);
    await page.fill('input[name="password"]', 'TestPass123!');
    await page.click('button[type="submit"]');

    // 7. Verify logged in
    await expect(page).toHaveURL('/');
    await expect(page.locator('text=Test User')).toBeVisible();
  });
});
```

**Test 3: Product Search**

```javascript
// tests/e2e/search.test.js
const { test, expect } = require('@playwright/test');

test.describe('Product Search', () => {
  test('user can search and find products', async ({ page }) => {
    await page.goto('/');

    // 1. Enter search query
    await page.fill('input[placeholder="Search products"]', 'milk');

    // 2. Verify autocomplete suggestions
    await expect(page.locator('.search-suggestions')).toBeVisible();
    await expect(page.locator('.search-suggestions li')).toHaveCount.greaterThan(0);

    // 3. Submit search
    await page.press('input[placeholder="Search products"]', 'Enter');

    // 4. Verify search results
    await expect(page).toHaveURL(/.*search\?q=milk/);
    await expect(page.locator('.search-results')).toBeVisible();
    await expect(page.locator('.product-card')).toHaveCount.greaterThan(0);

    // 5. Verify result relevance
    const firstProduct = page.locator('.product-card:first-child .product-name');
    await expect(firstProduct).toContainText(/milk/i);
  });
});
```

---

## 6. API TESTING

### 6.1 Postman Collection

Create comprehensive Postman collection covering:
- All API endpoints
- Happy path scenarios
- Error scenarios
- Authentication flows
- Data validation

**Collection Structure:**
```
Smart Dairy API
├── Authentication
│   ├── Register
│   ├── Login
│   ├── Refresh Token
│   └── Logout
├── Products
│   ├── Get Products
│   ├── Get Product by ID
│   ├── Search Products
│   └── Filter Products
├── Cart
│   ├── Get Cart
│   ├── Add to Cart
│   ├── Update Quantity
│   └── Remove from Cart
└── Orders
    ├── Create Order
    ├── Get Orders
    ├── Get Order Details
    └── Cancel Order
```

### 6.2 Automated API Tests

```javascript
// tests/api/products.test.js
const request = require('supertest');
const app = require('../../src/app');

describe('Products API', () => {
  describe('GET /api/v1/products', () => {
    it('should return paginated products', async () => {
      const response = await request(app)
        .get('/api/v1/products?page=1&limit=12')
        .expect(200);

      expect(response.body.success).toBe(true);
      expect(response.body.data.products).toBeInstanceOf(Array);
      expect(response.body.data.pagination).toHaveProperty('currentPage', 1);
    });

    it('should filter by category', async () => {
      const response = await request(app)
        .get('/api/v1/products?category=milk')
        .expect(200);

      const products = response.body.data.products;
      products.forEach(product => {
        expect(product.category.slug).toBe('milk');
      });
    });
  });
});
```

---

## 7. PERFORMANCE TESTING

### 7.1 Load Testing with Artillery

```yaml
# artillery.yml
config:
  target: 'http://localhost:5000'
  phases:
    - duration: 60
      arrivalRate: 10
      name: "Warm up"
    - duration: 300
      arrivalRate: 50
      name: "Sustained load"
    - duration: 60
      arrivalRate: 100
      name: "Peak load"
  
scenarios:
  - name: "Browse products"
    flow:
      - get:
          url: "/api/v1/products"
      - think: 2
      - get:
          url: "/api/v1/products/{{ $randomString() }}"
      - think: 3
  
  - name: "Add to cart"
    flow:
      - post:
          url: "/api/v1/auth/login"
          json:
            email: "test@example.com"
            password: "TestPass123!"
      - post:
          url: "/api/v1/cart/items"
          json:
            productId: "prod_001"
            quantity: 2
```

**Run Load Test:**
```bash
artillery run artillery.yml --output report.json
artillery report report.json
```

### 7.2 Lighthouse Performance Testing

```javascript
// tests/performance/lighthouse.js
const lighthouse = require('lighthouse');
const chromeLauncher = require('chrome-launcher');

async function runLighthouse(url) {
  const chrome = await chromeLauncher.launch({ chromeFlags: ['--headless'] });
  const options = {
    logLevel: 'info',
    output: 'json',
    port: chrome.port
  };

  const runnerResult = await lighthouse(url, options);
  const { lhr } = runnerResult;

  console.log('Performance score:', lhr.categories.performance.score * 100);
  console.log('Accessibility score:', lhr.categories.accessibility.score * 100);
  console.log('Best Practices score:', lhr.categories['best-practices'].score * 100);
  console.log('SEO score:', lhr.categories.seo.score * 100);

  // Assert performance thresholds
  expect(lhr.categories.performance.score).toBeGreaterThan(0.9);
  expect(lhr.categories.accessibility.score).toBeGreaterThan(0.9);

  await chrome.kill();
}

test('Homepage Lighthouse score', async () => {
  await runLighthouse('http://localhost:3000');
});
```

---

## 8. SECURITY TESTING

### 8.1 Security Checklist

- [ ] SQL Injection prevention (parameterized queries)
- [ ] XSS prevention (input sanitization, output encoding)
- [ ] CSRF protection (CSRF tokens)
- [ ] Authentication security (password hashing, JWT)
- [ ] Authorization checks (role-based access)
- [ ] Rate limiting (prevent brute force)
- [ ] Input validation (server-side)
- [ ] Secure headers (Helmet.js)
- [ ] HTTPS enforcement
- [ ] Dependency vulnerabilities (npm audit)

### 8.2 Automated Security Tests

```javascript
// tests/security/auth.test.js
describe('Authentication Security', () => {
  it('should rate limit login attempts', async () => {
    const loginData = { email: 'test@example.com', password: 'wrong' };

    // Attempt 6 logins (limit is 5)
    for (let i = 0; i < 6; i++) {
      const response = await request(app)
        .post('/api/v1/auth/login')
        .send(loginData);

      if (i < 5) {
        expect(response.status).toBe(401);
      } else {
        expect(response.status).toBe(429); // Too many requests
      }
    }
  });

  it('should prevent SQL injection in login', async () => {
    const response = await request(app)
      .post('/api/v1/auth/login')
      .send({
        email: "' OR '1'='1",
        password: "' OR '1'='1"
      });

    expect(response.status).toBe(401);
    expect(response.body.success).toBe(false);
  });
});
```

---

## 9. BROWSER & DEVICE TESTING

### 9.1 Browser Testing Matrix

| Browser | Versions | Priority |
|---------|----------|----------|
| Chrome | Last 2 | High |
| Firefox | Last 2 | High |
| Safari | Last 2 | Medium |
| Edge | Last 2 | Medium |
| Mobile Chrome | Latest | High |
| Mobile Safari | Latest | High |

### 9.2 Device Testing

**Physical Devices (if available):**
- Android phone (mid-range)
- iPhone (any model)
- Tablet (iPad or Android)

**Browser DevTools:**
- Responsive mode testing
- Network throttling (3G, 4G)
- Different screen sizes

---

## 10. ACCESSIBILITY TESTING

### 10.1 Automated A11y Testing

```javascript
// tests/accessibility/a11y.test.js
const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

test.describe('Accessibility Tests', () => {
  test('homepage should have no accessibility violations', async ({ page }) => {
    await page.goto('/');

    const accessibilityScanResults = await new AxeBuilder({ page }).analyze();

    expect(accessibilityScanResults.violations).toEqual([]);
  });

  test('product page should be keyboard navigable', async ({ page }) => {
    await page.goto('/products/fresh-whole-milk');

    // Tab through elements
    await page.keyboard.press('Tab');
    await expect(page.locator(':focus')).toBeVisible();
  });
});
```

### 10.2 Manual A11y Checks

- [ ] Screen reader compatibility (NVDA/JAWS)
- [ ] Keyboard navigation (all functions accessible)
- [ ] Color contrast (WCAG AA compliance)
- [ ] Focus indicators (visible and clear)
- [ ] Alt text for images
- [ ] Form labels and error messages
- [ ] Heading hierarchy (logical structure)

---

## 11. TEST COVERAGE & REPORTING

### 11.1 Generate Coverage Report

```bash
# Run tests with coverage
npm test -- --coverage

# View coverage in browser
open coverage/lcov-report/index.html
```

### 11.2 Coverage Requirements

**Minimum Coverage:**
- Statements: 70%
- Branches: 70%
- Functions: 70%
- Lines: 70%

**Critical Modules (90%+ coverage):**
- Authentication
- Payment processing
- Order creation
- Inventory management

---

## 12. BUG TRACKING & MANAGEMENT

### 12.1 Bug Priority Classification

| Priority | Description | Response Time |
|----------|-------------|---------------|
| P0 - Critical | System down, data loss | Immediate |
| P1 - High | Major feature broken | < 4 hours |
| P2 - Medium | Minor feature issue | < 24 hours |
| P3 - Low | Cosmetic, minor UX | Next sprint |

### 12.2 Bug Report Template

```markdown
## Bug Description
[Clear description of the issue]

## Steps to Reproduce
1. Go to...
2. Click on...
3. Expected: ...
4. Actual: ...

## Environment
- OS: [e.g., Windows 11]
- Browser: [e.g., Chrome 120]
- Device: [Desktop/Mobile]
- URL: [Page where bug occurs]

## Screenshots/Videos
[Attach if applicable]

## Priority
[P0/P1/P2/P3]

## Additional Context
[Any other relevant information]
```

---

## TESTING SCHEDULE

### Pre-Launch Testing Timeline

**Week 1-2:** Unit tests for core modules  
**Week 3-4:** Integration tests for APIs  
**Week 5:** E2E tests for critical flows  
**Week 6:** Performance testing  
**Week 7:** Security testing  
**Week 8:** Cross-browser and device testing  
**Week 9:** Accessibility testing  
**Week 10:** User acceptance testing (UAT)

### Post-Launch Testing

- **Daily:** Automated test suite (CI/CD)
- **Weekly:** Performance monitoring review
- **Monthly:** Security audit
- **Quarterly:** Full regression testing

---

**Document Status:** Final  
**Last Updated:** December 2, 2025  
**Next Review:** Monthly during development

---

*This testing plan ensures comprehensive quality assurance while being practical for solo development. Adjust priorities based on project phase and available time.*
