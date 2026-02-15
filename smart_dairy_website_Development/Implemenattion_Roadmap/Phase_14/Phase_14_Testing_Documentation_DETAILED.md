# PHASE 14: TESTING & DOCUMENTATION (Days 651-700)

## Phase Overview
**Duration:** 50 days (Days 651-700)
**Team Size:** 3 developers
**Focus Areas:** Comprehensive testing, documentation, quality assurance
**Prerequisites:** Phase 13 completed, all features implemented

## Success Criteria
- Test coverage > 80%
- All UAT test cases passed
- Zero critical/high severity bugs
- OWASP Top 10 vulnerabilities addressed
- WCAG 2.1 AA compliance achieved
- Complete technical and user documentation
- All stakeholder sign-offs obtained

---

## MILESTONE 14.1: Comprehensive Test Plan (Days 651-655)

### Objective
Develop comprehensive test strategy, establish coverage targets, and set up automation framework.

### Day 651: Test Strategy Development
**Assigned to:** Dev 1 (Lead), Dev 2, Dev 3

#### Tasks
**Dev 1: Test Strategy Document**

```markdown
# Test Strategy Document

## 1. Testing Scope

### In Scope
- All application features (functional testing)
- API endpoints (integration testing)
- Database operations
- User interface (UI/UX testing)
- Security features
- Performance under load
- Cross-browser compatibility
- Mobile responsiveness
- Accessibility features
- Third-party integrations

### Out of Scope
- Infrastructure testing (handled by DevOps)
- Third-party service internals
- Legacy system integrations

## 2. Test Types

### Unit Testing (Target: 70% coverage)
- All business logic functions
- Utility functions
- Model validations
- Service layer methods

### Integration Testing (Target: 60% coverage)
- API endpoint testing
- Database integration
- Third-party API integration
- Module interaction testing

### End-to-End Testing (Target: 50% critical paths)
- User workflows
- Multi-step processes
- Cross-module interactions

### Performance Testing
- Load testing (1000+ concurrent users)
- Stress testing (2x expected load)
- Endurance testing (24-hour run)
- Spike testing

### Security Testing
- OWASP Top 10 vulnerabilities
- Authentication/Authorization
- Data encryption
- SQL injection prevention
- XSS prevention

### Accessibility Testing
- WCAG 2.1 Level AA compliance
- Screen reader compatibility
- Keyboard navigation
- Color contrast

## 3. Testing Tools

### Unit/Integration Testing
- Jest (JavaScript)
- pytest (Python)
- Supertest (API testing)

### E2E Testing
- Cypress
- Playwright

### Performance Testing
- K6
- Apache JMeter

### Security Testing
- OWASP ZAP
- Burp Suite
- npm audit / Snyk

### Accessibility Testing
- axe-core
- WAVE
- Screen readers (NVDA, JAWS)

## 4. Test Environment

### Development
- Local developer machines
- Docker containers
- Mock external services

### Staging
- Production-like environment
- Real database (anonymized data)
- Integrated third-party services

### Production
- Smoke tests only
- Monitoring and alerting

## 5. Test Data Management

### Test Data Strategy
- Synthetic data generation
- Anonymized production data
- Boundary value data
- Edge case scenarios

### Data Reset
- Automated data cleanup after each test run
- Snapshot and restore capabilities
- Isolated test databases

## 6. Defect Management

### Severity Levels
- **Critical**: System crash, data loss, security breach
- **High**: Major feature broken, no workaround
- **Medium**: Feature partially working, workaround available
- **Low**: Minor issue, cosmetic problem

### Bug Lifecycle
1. Reported
2. Confirmed
3. Assigned
4. Fixed
5. Verified
6. Closed

## 7. Entry/Exit Criteria

### Entry Criteria
- All features code-complete
- Development environment stable
- Test data prepared
- Test cases documented

### Exit Criteria
- All test cases executed
- > 80% test coverage achieved
- Zero critical/high bugs
- Performance benchmarks met
- Security scan passed
- Documentation complete

## 8. Risk Assessment

### High Risk Areas
- Payment processing
- User authentication
- Data privacy compliance
- Third-party integrations

### Mitigation Strategies
- Extra test coverage for high-risk areas
- Security-focused testing
- Compliance audit
- Regular code reviews
```

**Dev 2: Test Coverage Analysis Tool**
```javascript
// scripts/test-coverage-analyzer.js
const fs = require('fs');
const path = require('path');
const istanbul = require('istanbul-lib-coverage');

class TestCoverageAnalyzer {
  constructor(coverageDir = './coverage') {
    this.coverageDir = coverageDir;
    this.coverageData = null;
  }

  async loadCoverage() {
    const coverageFile = path.join(this.coverageDir, 'coverage-final.json');
    this.coverageData = JSON.parse(fs.readFileSync(coverageFile, 'utf8'));
  }

  analyzeCoverage() {
    const map = istanbul.createCoverageMap(this.coverageData);
    const summary = istanbul.createCoverageSummary();

    map.files().forEach(file => {
      const fileCoverage = map.fileCoverageFor(file);
      summary.merge(fileCoverage.toSummary());
    });

    return {
      lines: this.formatMetric(summary.lines),
      statements: this.formatMetric(summary.statements),
      functions: this.formatMetric(summary.functions),
      branches: this.formatMetric(summary.branches)
    };
  }

  formatMetric(metric) {
    return {
      total: metric.total,
      covered: metric.covered,
      skipped: metric.skipped,
      percentage: metric.pct
    };
  }

  getUncoveredFiles(threshold = 80) {
    const map = istanbul.createCoverageMap(this.coverageData);
    const uncovered = [];

    map.files().forEach(file => {
      const fileCoverage = map.fileCoverageFor(file);
      const summary = fileCoverage.toSummary();

      if (summary.lines.pct < threshold) {
        uncovered.push({
          file: file.replace(process.cwd(), ''),
          coverage: summary.lines.pct.toFixed(2),
          lines: {
            total: summary.lines.total,
            covered: summary.lines.covered,
            uncovered: summary.lines.total - summary.lines.covered
          }
        });
      }
    });

    return uncovered.sort((a, b) => a.coverage - b.coverage);
  }

  generateReport() {
    const overall = this.analyzeCoverage();
    const uncovered = this.getUncoveredFiles(80);

    const report = {
      timestamp: new Date().toISOString(),
      overall,
      summary: {
        totalFiles: Object.keys(this.coverageData).length,
        filesAboveThreshold: Object.keys(this.coverageData).length - uncovered.length,
        filesBelowThreshold: uncovered.length,
        threshold: 80
      },
      uncoveredFiles: uncovered,
      recommendations: this.generateRecommendations(overall, uncovered)
    };

    // Save report
    const reportPath = path.join(this.coverageDir, 'coverage-analysis.json');
    fs.writeFileSync(reportPath, JSON.stringify(report, null, 2));

    // Generate HTML report
    this.generateHtmlReport(report);

    return report;
  }

  generateRecommendations(overall, uncovered) {
    const recommendations = [];

    if (overall.lines.percentage < 80) {
      recommendations.push({
        priority: 'HIGH',
        area: 'Overall Coverage',
        issue: `Line coverage is ${overall.lines.percentage.toFixed(2)}%, below 80% target`,
        action: 'Add unit tests for uncovered code paths'
      });
    }

    if (overall.branches.percentage < 70) {
      recommendations.push({
        priority: 'HIGH',
        area: 'Branch Coverage',
        issue: `Branch coverage is ${overall.branches.percentage.toFixed(2)}%, below 70% target`,
        action: 'Add tests for conditional logic and edge cases'
      });
    }

    if (uncovered.length > 10) {
      recommendations.push({
        priority: 'MEDIUM',
        area: 'File Coverage',
        issue: `${uncovered.length} files below 80% coverage threshold`,
        action: 'Focus on files with lowest coverage first'
      });
    }

    return recommendations;
  }

  generateHtmlReport(report) {
    const html = `
<!DOCTYPE html>
<html>
<head>
    <title>Test Coverage Analysis</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .metric { display: inline-block; margin: 10px 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; }
        .metric-label { font-size: 12px; color: #666; text-transform: uppercase; }
        .metric-value { font-size: 32px; font-weight: bold; margin: 5px 0; }
        .good { color: #28a745; }
        .warning { color: #ffc107; }
        .bad { color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f8f9fa; }
        .progress-bar { width: 100%; height: 20px; background: #e9ecef; border-radius: 10px; overflow: hidden; }
        .progress-fill { height: 100%; background: #007bff; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Coverage Analysis</h1>
        <p>Generated: ${report.timestamp}</p>

        <h2>Overall Coverage</h2>
        <div>
            <div class="metric">
                <div class="metric-label">Lines</div>
                <div class="metric-value ${this.getColorClass(report.overall.lines.percentage)}">
                    ${report.overall.lines.percentage.toFixed(2)}%
                </div>
                <div>${report.overall.lines.covered} / ${report.overall.lines.total}</div>
            </div>
            <div class="metric">
                <div class="metric-label">Statements</div>
                <div class="metric-value ${this.getColorClass(report.overall.statements.percentage)}">
                    ${report.overall.statements.percentage.toFixed(2)}%
                </div>
                <div>${report.overall.statements.covered} / ${report.overall.statements.total}</div>
            </div>
            <div class="metric">
                <div class="metric-label">Functions</div>
                <div class="metric-value ${this.getColorClass(report.overall.functions.percentage)}">
                    ${report.overall.functions.percentage.toFixed(2)}%
                </div>
                <div>${report.overall.functions.covered} / ${report.overall.functions.total}</div>
            </div>
            <div class="metric">
                <div class="metric-label">Branches</div>
                <div class="metric-value ${this.getColorClass(report.overall.branches.percentage)}">
                    ${report.overall.branches.percentage.toFixed(2)}%
                </div>
                <div>${report.overall.branches.covered} / ${report.overall.branches.total}</div>
            </div>
        </div>

        <h2>Files Below Threshold (${report.uncoveredFiles.length})</h2>
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Coverage</th>
                    <th>Lines</th>
                    <th>Progress</th>
                </tr>
            </thead>
            <tbody>
                ${report.uncoveredFiles.map(file => `
                    <tr>
                        <td>${file.file}</td>
                        <td class="${this.getColorClass(file.coverage)}">${file.coverage}%</td>
                        <td>${file.lines.covered} / ${file.lines.total}</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${file.coverage}%;"></div>
                            </div>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>

        <h2>Recommendations</h2>
        <table>
            <thead>
                <tr>
                    <th>Priority</th>
                    <th>Area</th>
                    <th>Issue</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                ${report.recommendations.map(rec => `
                    <tr>
                        <td><strong>${rec.priority}</strong></td>
                        <td>${rec.area}</td>
                        <td>${rec.issue}</td>
                        <td>${rec.action}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    </div>
</body>
</html>
    `;

    const htmlPath = path.join(this.coverageDir, 'coverage-analysis.html');
    fs.writeFileSync(htmlPath, html);
  }

  getColorClass(percentage) {
    if (percentage >= 80) return 'good';
    if (percentage >= 60) return 'warning';
    return 'bad';
  }
}

module.exports = TestCoverageAnalyzer;
```

**Dev 3: Automated Test Suite Setup**
```javascript
// tests/setup/jest.config.js
module.exports = {
  testEnvironment: 'node',
  coverageDirectory: './coverage',
  collectCoverageFrom: [
    'src/**/*.js',
    '!src/**/*.test.js',
    '!src/**/*.spec.js',
    '!src/config/**',
    '!src/migrations/**',
    '!src/seeders/**'
  ],
  coverageThreshold: {
    global: {
      branches: 70,
      functions: 75,
      lines: 80,
      statements: 80
    },
    './src/services/': {
      branches: 80,
      functions: 85,
      lines: 90,
      statements: 90
    },
    './src/controllers/': {
      branches: 75,
      functions: 80,
      lines: 85,
      statements: 85
    }
  },
  testMatch: [
    '**/__tests__/**/*.js',
    '**/?(*.)+(spec|test).js'
  ],
  setupFilesAfterEnv: ['./tests/setup/testSetup.js'],
  testTimeout: 30000,
  verbose: true,
  collectCoverage: true,
  coverageReporters: ['text', 'lcov', 'html', 'json'],
  reporters: [
    'default',
    ['jest-junit', {
      outputDirectory: './test-results',
      outputName: 'junit.xml',
      classNameTemplate: '{classname}',
      titleTemplate: '{title}'
    }],
    ['jest-html-reporter', {
      pageTitle: 'Test Report',
      outputPath: './test-results/test-report.html',
      includeFailureMsg: true,
      includeConsoleLog: true
    }]
  ]
};
```

```javascript
// tests/setup/testSetup.js
const { sequelize } = require('../../src/config/database');
const redis = require('../../src/config/redis');

// Global test setup
beforeAll(async () => {
  // Connect to test database
  await sequelize.authenticate();

  // Run migrations
  await sequelize.sync({ force: true });

  // Seed test data
  await seedTestData();

  // Connect to Redis
  await redis.connect();
});

// Clean up after each test
afterEach(async () => {
  // Clear Redis cache
  await redis.flushAll();

  // Truncate tables (keep schema)
  await clearTestData();
});

// Global test teardown
afterAll(async () => {
  await sequelize.close();
  await redis.quit();
});

async function seedTestData() {
  // Create test users
  await sequelize.models.User.bulkCreate([
    {
      id: 1,
      name: 'Test Admin',
      email: 'admin@test.com',
      role: 'admin',
      password: 'hashedpassword'
    },
    {
      id: 2,
      name: 'Test Farmer',
      email: 'farmer@test.com',
      role: 'farmer',
      password: 'hashedpassword'
    },
    {
      id: 3,
      name: 'Test Customer',
      email: 'customer@test.com',
      role: 'customer',
      password: 'hashedpassword'
    }
  ]);

  // Create test products
  await sequelize.models.Product.bulkCreate([
    {
      id: 1,
      name: 'Test Milk',
      description: 'Test milk product',
      price: 50.00,
      quantity: 100,
      farmer_id: 2,
      category_id: 1,
      status: 'active'
    },
    {
      id: 2,
      name: 'Test Cheese',
      description: 'Test cheese product',
      price: 200.00,
      quantity: 50,
      farmer_id: 2,
      category_id: 2,
      status: 'active'
    }
  ]);
}

async function clearTestData() {
  const models = Object.keys(sequelize.models);

  for (const modelName of models) {
    await sequelize.models[modelName].destroy({ where: {}, truncate: true });
  }
}

// Global test utilities
global.testUtils = {
  createAuthToken: (userId) => {
    const jwt = require('jsonwebtoken');
    return jwt.sign({ id: userId }, process.env.JWT_SECRET, { expiresIn: '1h' });
  },

  mockRequest: (overrides = {}) => ({
    body: {},
    params: {},
    query: {},
    headers: {},
    user: null,
    ...overrides
  }),

  mockResponse: () => {
    const res = {};
    res.status = jest.fn().mockReturnValue(res);
    res.json = jest.fn().mockReturnValue(res);
    res.send = jest.fn().mockReturnValue(res);
    res.set = jest.fn().mockReturnValue(res);
    return res;
  },

  wait: (ms) => new Promise(resolve => setTimeout(resolve, ms))
};
```

#### Deliverables
- [ ] Test strategy document complete
- [ ] Coverage analysis tool created
- [ ] Test automation framework configured
- [ ] Test data management setup

---

### Day 652-653: Test Case Development
**Assigned to:** All Developers

#### Tasks
**Create comprehensive test cases for all modules**

```javascript
// tests/unit/services/orderService.test.js
const OrderService = require('../../../src/services/orderService');
const { Order, OrderItem, Product, User } = require('../../../src/models');

describe('OrderService', () => {
  let orderService;

  beforeEach(() => {
    orderService = new OrderService();
  });

  describe('createOrder', () => {
    test('should create order with valid data', async () => {
      const orderData = {
        customer_id: 3,
        farmer_id: 2,
        items: [
          { product_id: 1, quantity: 2, price: 50.00 },
          { product_id: 2, quantity: 1, price: 200.00 }
        ],
        delivery_address: '123 Test Street',
        payment_method: 'cash'
      };

      const order = await orderService.createOrder(orderData);

      expect(order).toHaveProperty('id');
      expect(order).toHaveProperty('order_number');
      expect(order.total_amount).toBe(300.00);
      expect(order.status).toBe('pending');
      expect(order.items).toHaveLength(2);
    });

    test('should throw error for insufficient inventory', async () => {
      const orderData = {
        customer_id: 3,
        farmer_id: 2,
        items: [
          { product_id: 1, quantity: 1000, price: 50.00 } // More than available
        ]
      };

      await expect(orderService.createOrder(orderData))
        .rejects
        .toThrow('Insufficient inventory');
    });

    test('should throw error for invalid product', async () => {
      const orderData = {
        customer_id: 3,
        farmer_id: 2,
        items: [
          { product_id: 999, quantity: 1, price: 50.00 } // Non-existent product
        ]
      };

      await expect(orderService.createOrder(orderData))
        .rejects
        .toThrow('Product not found');
    });

    test('should calculate correct total with multiple items', async () => {
      const orderData = {
        customer_id: 3,
        farmer_id: 2,
        items: [
          { product_id: 1, quantity: 5, price: 50.00 },
          { product_id: 2, quantity: 2, price: 200.00 }
        ]
      };

      const order = await orderService.createOrder(orderData);
      expect(order.total_amount).toBe(650.00); // (5 * 50) + (2 * 200)
    });
  });

  describe('updateOrderStatus', () => {
    let testOrder;

    beforeEach(async () => {
      testOrder = await Order.create({
        customer_id: 3,
        farmer_id: 2,
        total_amount: 100.00,
        status: 'pending'
      });
    });

    test('should update order status to confirmed', async () => {
      const updated = await orderService.updateOrderStatus(testOrder.id, 'confirmed');
      expect(updated.status).toBe('confirmed');
    });

    test('should not allow invalid status transition', async () => {
      await expect(orderService.updateOrderStatus(testOrder.id, 'delivered'))
        .rejects
        .toThrow('Invalid status transition');
    });

    test('should send notification on status change', async () => {
      const notificationSpy = jest.spyOn(orderService, 'sendNotification');

      await orderService.updateOrderStatus(testOrder.id, 'confirmed');

      expect(notificationSpy).toHaveBeenCalledWith(
        testOrder.customer_id,
        expect.objectContaining({
          type: 'order_status_change',
          orderId: testOrder.id,
          newStatus: 'confirmed'
        })
      );
    });
  });

  describe('getOrdersByCustomer', () => {
    beforeEach(async () => {
      // Create test orders
      await Order.bulkCreate([
        { customer_id: 3, farmer_id: 2, total_amount: 100.00, status: 'pending' },
        { customer_id: 3, farmer_id: 2, total_amount: 200.00, status: 'confirmed' },
        { customer_id: 3, farmer_id: 2, total_amount: 150.00, status: 'delivered' }
      ]);
    });

    test('should return all orders for customer', async () => {
      const orders = await orderService.getOrdersByCustomer(3);
      expect(orders).toHaveLength(3);
    });

    test('should filter orders by status', async () => {
      const orders = await orderService.getOrdersByCustomer(3, { status: 'pending' });
      expect(orders).toHaveLength(1);
      expect(orders[0].status).toBe('pending');
    });

    test('should paginate results', async () => {
      const orders = await orderService.getOrdersByCustomer(3, { limit: 2, offset: 1 });
      expect(orders).toHaveLength(2);
    });

    test('should include related data when requested', async () => {
      const orders = await orderService.getOrdersByCustomer(3, { include: ['items', 'farmer'] });
      expect(orders[0]).toHaveProperty('items');
      expect(orders[0]).toHaveProperty('farmer');
    });
  });

  describe('cancelOrder', () => {
    let testOrder;

    beforeEach(async () => {
      testOrder = await Order.create({
        customer_id: 3,
        farmer_id: 2,
        total_amount: 100.00,
        status: 'pending'
      });
    });

    test('should cancel pending order', async () => {
      const cancelled = await orderService.cancelOrder(testOrder.id, 3);
      expect(cancelled.status).toBe('cancelled');
    });

    test('should not allow cancellation of delivered order', async () => {
      await testOrder.update({ status: 'delivered' });

      await expect(orderService.cancelOrder(testOrder.id, 3))
        .rejects
        .toThrow('Cannot cancel delivered order');
    });

    test('should refund payment on cancellation', async () => {
      const refundSpy = jest.spyOn(orderService.paymentService, 'processRefund');

      await orderService.cancelOrder(testOrder.id, 3);

      expect(refundSpy).toHaveBeenCalledWith(testOrder.id);
    });

    test('should restore inventory on cancellation', async () => {
      // Add order items
      await OrderItem.create({
        order_id: testOrder.id,
        product_id: 1,
        quantity: 5,
        price: 50.00
      });

      const productBefore = await Product.findByPk(1);
      const initialQuantity = productBefore.quantity;

      await orderService.cancelOrder(testOrder.id, 3);

      const productAfter = await Product.findByPk(1);
      expect(productAfter.quantity).toBe(initialQuantity + 5);
    });
  });
});
```

```javascript
// tests/integration/api/orders.test.js
const request = require('supertest');
const app = require('../../../src/app');
const { Order, Product } = require('../../../src/models');

describe('Orders API', () => {
  let authToken;

  beforeEach(async () => {
    authToken = global.testUtils.createAuthToken(3); // Customer user
  });

  describe('POST /api/orders', () => {
    test('should create new order with authentication', async () => {
      const orderData = {
        items: [
          { product_id: 1, quantity: 2 }
        ],
        delivery_address: '123 Test Street',
        payment_method: 'cash'
      };

      const response = await request(app)
        .post('/api/orders')
        .set('Authorization', `Bearer ${authToken}`)
        .send(orderData)
        .expect(201);

      expect(response.body).toHaveProperty('data');
      expect(response.body.data).toHaveProperty('id');
      expect(response.body.data).toHaveProperty('order_number');
      expect(response.body.data.status).toBe('pending');
    });

    test('should return 401 without authentication', async () => {
      const response = await request(app)
        .post('/api/orders')
        .send({})
        .expect(401);

      expect(response.body).toHaveProperty('error');
    });

    test('should validate required fields', async () => {
      const response = await request(app)
        .post('/api/orders')
        .set('Authorization', `Bearer ${authToken}`)
        .send({})
        .expect(400);

      expect(response.body).toHaveProperty('errors');
      expect(response.body.errors).toContain('items is required');
    });

    test('should handle concurrent orders correctly', async () => {
      // Update product quantity to limited stock
      await Product.update({ quantity: 5 }, { where: { id: 1 } });

      const orderData = {
        items: [{ product_id: 1, quantity: 3 }],
        delivery_address: '123 Test Street',
        payment_method: 'cash'
      };

      // Create two orders simultaneously
      const [response1, response2] = await Promise.all([
        request(app)
          .post('/api/orders')
          .set('Authorization', `Bearer ${authToken}`)
          .send(orderData),
        request(app)
          .post('/api/orders')
          .set('Authorization', `Bearer ${authToken}`)
          .send(orderData)
      ]);

      // One should succeed, one should fail due to insufficient inventory
      const succeeded = [response1, response2].filter(r => r.status === 201);
      const failed = [response1, response2].filter(r => r.status !== 201);

      expect(succeeded).toHaveLength(1);
      expect(failed).toHaveLength(1);
    });
  });

  describe('GET /api/orders', () => {
    beforeEach(async () => {
      // Create test orders
      await Order.bulkCreate([
        { customer_id: 3, farmer_id: 2, total_amount: 100, status: 'pending' },
        { customer_id: 3, farmer_id: 2, total_amount: 200, status: 'confirmed' },
        { customer_id: 3, farmer_id: 2, total_amount: 150, status: 'delivered' }
      ]);
    });

    test('should return user orders', async () => {
      const response = await request(app)
        .get('/api/orders')
        .set('Authorization', `Bearer ${authToken}`)
        .expect(200);

      expect(response.body.data).toHaveLength(3);
    });

    test('should filter by status', async () => {
      const response = await request(app)
        .get('/api/orders?status=pending')
        .set('Authorization', `Bearer ${authToken}`)
        .expect(200);

      expect(response.body.data).toHaveLength(1);
      expect(response.body.data[0].status).toBe('pending');
    });

    test('should support pagination', async () => {
      const response = await request(app)
        .get('/api/orders?page=1&limit=2')
        .set('Authorization', `Bearer ${authToken}`)
        .expect(200);

      expect(response.body.data).toHaveLength(2);
      expect(response.body.pagination).toMatchObject({
        page: 1,
        limit: 2,
        total: 3
      });
    });
  });

  describe('PATCH /api/orders/:id/status', () => {
    let testOrder;

    beforeEach(async () => {
      testOrder = await Order.create({
        customer_id: 3,
        farmer_id: 2,
        total_amount: 100,
        status: 'pending'
      });
    });

    test('should update order status', async () => {
      const response = await request(app)
        .patch(`/api/orders/${testOrder.id}/status`)
        .set('Authorization', `Bearer ${authToken}`)
        .send({ status: 'cancelled' })
        .expect(200);

      expect(response.body.data.status).toBe('cancelled');
    });

    test('should not allow invalid status', async () => {
      const response = await request(app)
        .patch(`/api/orders/${testOrder.id}/status`)
        .set('Authorization', `Bearer ${authToken}`)
        .send({ status: 'invalid_status' })
        .expect(400);

      expect(response.body).toHaveProperty('error');
    });
  });
});
```

```javascript
// tests/e2e/orderFlow.test.js
describe('Complete Order Flow', () => {
  test('Customer can browse, order, and track delivery', async () => {
    // 1. Login as customer
    const loginRes = await request(app)
      .post('/api/auth/login')
      .send({ email: 'customer@test.com', password: 'TestPass123!' })
      .expect(200);

    const token = loginRes.body.token;

    // 2. Browse products
    const productsRes = await request(app)
      .get('/api/products')
      .set('Authorization', `Bearer ${token}`)
      .expect(200);

    expect(productsRes.body.data.length).toBeGreaterThan(0);
    const product = productsRes.body.data[0];

    // 3. Add to cart
    const cartRes = await request(app)
      .post('/api/cart/items')
      .set('Authorization', `Bearer ${token}`)
      .send({ product_id: product.id, quantity: 2 })
      .expect(201);

    // 4. Create order from cart
    const orderRes = await request(app)
      .post('/api/orders/from-cart')
      .set('Authorization', `Bearer ${token}`)
      .send({
        delivery_address: '123 Test Street',
        payment_method: 'cash'
      })
      .expect(201);

    const orderId = orderRes.body.data.id;

    // 5. Track order status
    const trackRes = await request(app)
      .get(`/api/orders/${orderId}`)
      .set('Authorization', `Bearer ${token}`)
      .expect(200);

    expect(trackRes.body.data.status).toBe('pending');

    // 6. Farmer confirms order
    const farmerToken = global.testUtils.createAuthToken(2);

    await request(app)
      .patch(`/api/orders/${orderId}/status`)
      .set('Authorization', `Bearer ${farmerToken}`)
      .send({ status: 'confirmed' })
      .expect(200);

    // 7. Check updated status
    const updatedRes = await request(app)
      .get(`/api/orders/${orderId}`)
      .set('Authorization', `Bearer ${token}`)
      .expect(200);

    expect(updatedRes.body.data.status).toBe('confirmed');
  });
});
```

#### Deliverables
- [ ] 200+ unit test cases created
- [ ] 100+ integration test cases created
- [ ] 50+ E2E test scenarios created
- [ ] Test coverage > 80%

---

### Day 654-655: Test Automation Setup
**Assigned to:** Dev 1, Dev 2, Dev 3

#### Tasks
**Dev 1: CI/CD Test Integration**
```yaml
# .github/workflows/test.yml
name: Test Suite

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  unit-tests:
    runs-on: ubuntu-latest

    services:
      postgres:
        image: postgres:14
        env:
          POSTGRES_DB: dairy_test
          POSTGRES_USER: test
          POSTGRES_PASSWORD: test
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
        ports:
          - 5432:5432

      redis:
        image: redis:7
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
        ports:
          - 6379:6379

    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
          cache: 'npm'

      - name: Install dependencies
        run: npm ci

      - name: Run database migrations
        run: npm run migrate:test
        env:
          DATABASE_URL: postgresql://test:test@localhost:5432/dairy_test

      - name: Run unit tests
        run: npm run test:unit -- --coverage
        env:
          NODE_ENV: test
          DATABASE_URL: postgresql://test:test@localhost:5432/dairy_test
          REDIS_URL: redis://localhost:6379

      - name: Upload coverage to Codecov
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage/lcov.info
          flags: unittests
          name: unit-coverage

      - name: Generate coverage badge
        uses: cicirello/jacoco-badge-generator@v2
        with:
          badges-directory: ./badges

  integration-tests:
    runs-on: ubuntu-latest
    needs: unit-tests

    services:
      postgres:
        image: postgres:14
        env:
          POSTGRES_DB: dairy_test
          POSTGRES_USER: test
          POSTGRES_PASSWORD: test
        ports:
          - 5432:5432

      redis:
        image: redis:7
        ports:
          - 6379:6379

    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'

      - name: Install dependencies
        run: npm ci

      - name: Run integration tests
        run: npm run test:integration
        env:
          NODE_ENV: test
          DATABASE_URL: postgresql://test:test@localhost:5432/dairy_test
          REDIS_URL: redis://localhost:6379

      - name: Upload test results
        if: always()
        uses: actions/upload-artifact@v3
        with:
          name: integration-test-results
          path: ./test-results

  e2e-tests:
    runs-on: ubuntu-latest
    needs: integration-tests

    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'

      - name: Install dependencies
        run: npm ci

      - name: Install Playwright
        run: npx playwright install --with-deps

      - name: Build application
        run: npm run build

      - name: Start application
        run: npm start &
        env:
          NODE_ENV: test

      - name: Wait for application
        run: npx wait-on http://localhost:3000 --timeout 60000

      - name: Run E2E tests
        run: npm run test:e2e

      - name: Upload Playwright report
        if: always()
        uses: actions/upload-artifact@v3
        with:
          name: playwright-report
          path: playwright-report/

  test-summary:
    runs-on: ubuntu-latest
    needs: [unit-tests, integration-tests, e2e-tests]
    if: always()

    steps:
      - name: Test Summary
        uses: test-summary/action@v2
        with:
          paths: |
            ./test-results/**/*.xml
            ./playwright-report/**/*.xml
```

**Dev 2: Cypress E2E Tests**
```javascript
// cypress/e2e/orderWorkflow.cy.js
describe('Order Workflow', () => {
  beforeEach(() => {
    // Reset database state
    cy.task('db:reset');
    cy.task('db:seed');
  });

  it('Complete order flow - Customer perspective', () => {
    // Login
    cy.visit('/login');
    cy.get('[data-cy=email]').type('customer@test.com');
    cy.get('[data-cy=password]').type('TestPass123!');
    cy.get('[data-cy=login-button]').click();

    // Verify dashboard
    cy.url().should('include', '/dashboard');
    cy.get('[data-cy=welcome-message]').should('contain', 'Welcome');

    // Browse products
    cy.get('[data-cy=nav-products]').click();
    cy.url().should('include', '/products');
    cy.get('[data-cy=product-card]').should('have.length.greaterThan', 0);

    // View product details
    cy.get('[data-cy=product-card]').first().click();
    cy.get('[data-cy=product-name]').should('be.visible');
    cy.get('[data-cy=product-price]').should('be.visible');

    // Add to cart
    cy.get('[data-cy=quantity-input]').clear().type('2');
    cy.get('[data-cy=add-to-cart]').click();
    cy.get('[data-cy=cart-badge]').should('contain', '1');

    // Go to cart
    cy.get('[data-cy=cart-icon]').click();
    cy.url().should('include', '/cart');
    cy.get('[data-cy=cart-item]').should('have.length', 1);

    // Proceed to checkout
    cy.get('[data-cy=checkout-button]').click();
    cy.url().should('include', '/checkout');

    // Fill delivery details
    cy.get('[data-cy=delivery-address]').type('123 Test Street, Dhaka');
    cy.get('[data-cy=phone]').type('01712345678');
    cy.get('[data-cy=payment-method]').select('cash');

    // Place order
    cy.get('[data-cy=place-order]').click();

    // Verify order confirmation
    cy.get('[data-cy=order-success]').should('be.visible');
    cy.get('[data-cy=order-number]').should('exist');

    // Go to orders page
    cy.get('[data-cy=view-order]').click();
    cy.url().should('include', '/orders/');
    cy.get('[data-cy=order-status]').should('contain', 'Pending');
  });

  it('Order management - Farmer perspective', () => {
    // Login as farmer
    cy.visit('/login');
    cy.get('[data-cy=email]').type('farmer@test.com');
    cy.get('[data-cy=password]').type('TestPass123!');
    cy.get('[data-cy=login-button]').click();

    // Navigate to orders
    cy.get('[data-cy=nav-orders]').click();
    cy.url().should('include', '/farmer/orders');

    // View pending orders
    cy.get('[data-cy=filter-status]').select('pending');
    cy.get('[data-cy=order-card]').should('have.length.greaterThan', 0);

    // View order details
    cy.get('[data-cy=order-card]').first().click();
    cy.get('[data-cy=order-items]').should('be.visible');
    cy.get('[data-cy=customer-details]').should('be.visible');

    // Confirm order
    cy.get('[data-cy=confirm-order]').click();
    cy.get('[data-cy=confirmation-modal]').should('be.visible');
    cy.get('[data-cy=confirm-button]').click();

    // Verify status update
    cy.get('[data-cy=order-status]').should('contain', 'Confirmed');
    cy.get('[data-cy=success-message]').should('be.visible');
  });

  it('Search and filter products', () => {
    cy.login('customer@test.com', 'TestPass123!');

    cy.visit('/products');

    // Test search
    cy.get('[data-cy=search-input]').type('milk');
    cy.get('[data-cy=product-card]').each(($el) => {
      cy.wrap($el).should('contain.text', 'milk');
    });

    // Test category filter
    cy.get('[data-cy=category-filter]').select('Dairy');
    cy.wait(500);
    cy.get('[data-cy=product-card]').should('have.length.greaterThan', 0);

    // Test price range filter
    cy.get('[data-cy=min-price]').type('50');
    cy.get('[data-cy=max-price]').type('200');
    cy.get('[data-cy=apply-filters]').click();
    cy.wait(500);

    // Test sorting
    cy.get('[data-cy=sort-select]').select('price-asc');
    cy.wait(500);

    // Verify sorted order
    let prices = [];
    cy.get('[data-cy=product-price]').each(($el) => {
      prices.push(parseFloat($el.text().replace('৳', '')));
    }).then(() => {
      expect(prices).to.deep.equal([...prices].sort((a, b) => a - b));
    });
  });

  it('Responsive design - Mobile view', () => {
    cy.viewport('iphone-x');
    cy.login('customer@test.com', 'TestPass123!');

    // Test mobile menu
    cy.get('[data-cy=mobile-menu-button]').click();
    cy.get('[data-cy=mobile-nav]').should('be.visible');

    // Navigate to products
    cy.get('[data-cy=mobile-nav-products]').click();
    cy.url().should('include', '/products');

    // Test mobile product grid
    cy.get('[data-cy=product-card]').should('have.css', 'width');
  });

  it('Handles errors gracefully', () => {
    cy.login('customer@test.com', 'TestPass123!');

    // Intercept API call to simulate error
    cy.intercept('POST', '/api/orders', {
      statusCode: 500,
      body: { error: 'Internal server error' }
    }).as('createOrder');

    // Try to create order
    cy.visit('/cart');
    cy.get('[data-cy=checkout-button]').click();
    cy.get('[data-cy=delivery-address]').type('123 Test Street');
    cy.get('[data-cy=place-order]').click();

    // Verify error message
    cy.wait('@createOrder');
    cy.get('[data-cy=error-message]').should('be.visible');
    cy.get('[data-cy=error-message]').should('contain', 'error');
  });
});
```

```javascript
// cypress/support/commands.js
Cypress.Commands.add('login', (email, password) => {
  cy.visit('/login');
  cy.get('[data-cy=email]').type(email);
  cy.get('[data-cy=password]').type(password);
  cy.get('[data-cy=login-button]').click();
  cy.url().should('not.include', '/login');
});

Cypress.Commands.add('logout', () => {
  cy.get('[data-cy=user-menu]').click();
  cy.get('[data-cy=logout-button]').click();
  cy.url().should('include', '/login');
});

// Custom command for database operations
Cypress.Commands.add('resetDB', () => {
  cy.task('db:reset');
});

Cypress.Commands.add('seedDB', () => {
  cy.task('db:seed');
});
```

```javascript
// cypress/plugins/index.js
const { defineConfig } = require('cypress');
const { sequelize } = require('../../src/config/database');

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://localhost:3000',
    setupNodeEvents(on, config) {
      // Database tasks
      on('task', {
        async 'db:reset'() {
          await sequelize.sync({ force: true });
          return null;
        },

        async 'db:seed'() {
          // Seed test data
          const seeders = require('../../database/seeders');
          await seeders.run();
          return null;
        }
      });

      return config;
    },
    video: true,
    screenshotOnRunFailure: true,
    viewportWidth: 1280,
    viewportHeight: 720,
    defaultCommandTimeout: 10000
  }
});
```

**Dev 3: Performance Testing Scripts**
```javascript
// tests/load/performance-suite.js
import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { htmlReport } from 'https://raw.githubusercontent.com/benc-uk/k6-reporter/main/dist/bundle.js';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.1/index.js';

export const options = {
  stages: [
    { duration: '1m', target: 50 },
    { duration: '3m', target: 100 },
    { duration: '2m', target: 200 },
    { duration: '3m', target: 500 },
    { duration: '2m', target: 1000 },
    { duration: '5m', target: 1000 },
    { duration: '3m', target: 0 }
  ],
  thresholds: {
    'http_req_duration': ['p(95)<500', 'p(99)<1000'],
    'http_req_failed': ['rate<0.01'],
    'http_reqs': ['rate>100'],
    'checks': ['rate>0.95']
  },
  ext: {
    loadimpact: {
      name: 'Dairy Platform Performance Test',
      projectID: 3478905
    }
  }
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:3000';

export default function() {
  // Homepage
  group('Homepage Load', () => {
    const res = http.get(`${BASE_URL}/`);
    check(res, {
      'homepage status 200': (r) => r.status === 200,
      'homepage load < 2s': (r) => r.timings.duration < 2000
    });
  });

  // API Performance
  group('API Endpoints', () => {
    // Products list
    const productsRes = http.get(`${BASE_URL}/api/products?page=1&limit=20`);
    check(productsRes, {
      'products API status 200': (r) => r.status === 200,
      'products API < 200ms': (r) => r.timings.duration < 200,
      'products returned data': (r) => r.json('data').length > 0
    });

    // Product detail
    const productId = productsRes.json('data.0.id');
    if (productId) {
      const detailRes = http.get(`${BASE_URL}/api/products/${productId}`);
      check(detailRes, {
        'product detail status 200': (r) => r.status === 200,
        'product detail < 150ms': (r) => r.timings.duration < 150
      });
    }
  });

  sleep(Math.random() * 2 + 1);
}

export function handleSummary(data) {
  return {
    'performance-report.html': htmlReport(data),
    'performance-summary.json': JSON.stringify(data),
    stdout: textSummary(data, { indent: ' ', enableColors: true })
  };
}
```

#### Deliverables
- [ ] CI/CD test pipeline configured
- [ ] E2E test suite created (50+ scenarios)
- [ ] Performance test scripts ready
- [ ] Test automation complete

---

## MILESTONE 14.2-14.10: [Remaining Milestones]

**Due to length constraints, the document continues with:**

- Milestone 14.2: Integration Testing (Days 656-660)
- Milestone 14.3: UAT Preparation (Days 661-665)
- Milestone 14.4: UAT Execution (Days 666-670)
- Milestone 14.5: Security Testing (Days 671-675)
- Milestone 14.6: Performance Testing (Days 676-680)
- Milestone 14.7: Accessibility Testing (Days 681-685)
- Milestone 14.8: Technical Documentation (Days 686-690)
- Milestone 14.9: User Documentation (Days 691-695)
- Milestone 14.10: Documentation Review (Days 696-700)

---

## Phase 14 Success Metrics

### Testing Metrics
- [ ] Test coverage > 80%
- [ ] Zero critical/high severity bugs
- [ ] All UAT test cases passed
- [ ] OWASP Top 10 addressed
- [ ] WCAG 2.1 AA compliant
- [ ] Load test passed (1000+ users)

### Documentation Metrics
- [ ] Technical documentation complete
- [ ] User manuals (English & Bangla)
- [ ] API documentation generated
- [ ] Video tutorials created
- [ ] FAQs documented
- [ ] All stakeholder approvals obtained

---

**End of Phase 14 Document** (Partial - Full document includes all 10 milestones with detailed test cases, documentation templates, and validation procedures)
