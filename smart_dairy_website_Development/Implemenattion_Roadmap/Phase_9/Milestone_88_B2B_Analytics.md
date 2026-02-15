# Milestone 88: B2B Analytics

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M88-v1.0                                          |
| **Milestone**        | 88 - B2B Analytics                                           |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 621-630                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 88 implements comprehensive B2B analytics including purchase history analysis, customer spend analytics, product performance, trend analysis, CLV calculation, and executive dashboards.

### 1.2 Scope

- B2B Analytics Data Models
- Purchase History Analysis
- Customer Spend Analytics
- Product Performance Reports
- Trend Analysis & Forecasting
- Customer Lifetime Value (CLV)
- Executive Dashboards
- Report Export (PDF/Excel)
- TimescaleDB Integration

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | Analytics Data Models                | Dev 1  | High     |
| 2  | Purchase History Queries             | Dev 1  | High     |
| 3  | TimescaleDB Setup                    | Dev 2  | High     |
| 4  | Spend Analytics Service              | Dev 1  | High     |
| 5  | CLV Calculation Engine               | Dev 1  | High     |
| 6  | Trend Analysis Service               | Dev 2  | High     |
| 7  | Report Export Service                | Dev 2  | Medium   |
| 8  | OWL Dashboard Component              | Dev 3  | Critical |
| 9  | Chart Components                     | Dev 3  | High     |
| 10 | Analytics API Endpoints              | Dev 2  | High     |

---

## 3. Day-by-Day Development Plan

### Day 621 (Day 1): Analytics Data Models

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_analytics.py
from odoo import models, fields, api
from datetime import date, timedelta
from dateutil.relativedelta import relativedelta

class B2BAnalytics(models.Model):
    _name = 'b2b.analytics'
    _description = 'B2B Analytics'
    _auto = False  # Database view

    customer_id = fields.Many2one('b2b.customer', string='Customer', readonly=True)
    tier_id = fields.Many2one('b2b.customer.tier', string='Tier', readonly=True)
    date = fields.Date(string='Date', readonly=True)
    month = fields.Char(string='Month', readonly=True)
    year = fields.Integer(string='Year', readonly=True)

    # Metrics
    order_count = fields.Integer(string='Orders', readonly=True)
    total_revenue = fields.Float(string='Revenue', readonly=True)
    total_quantity = fields.Float(string='Quantity', readonly=True)
    avg_order_value = fields.Float(string='Avg Order Value', readonly=True)
    product_count = fields.Integer(string='Products Ordered', readonly=True)

    def init(self):
        tools.drop_view_if_exists(self.env.cr, self._table)
        self.env.cr.execute("""
            CREATE OR REPLACE VIEW %s AS (
                SELECT
                    row_number() OVER () AS id,
                    bc.id AS customer_id,
                    bc.tier_id,
                    so.date_order::date AS date,
                    to_char(so.date_order, 'YYYY-MM') AS month,
                    EXTRACT(year FROM so.date_order)::integer AS year,
                    COUNT(DISTINCT so.id) AS order_count,
                    SUM(so.amount_total) AS total_revenue,
                    SUM(sol.product_uom_qty) AS total_quantity,
                    AVG(so.amount_total) AS avg_order_value,
                    COUNT(DISTINCT sol.product_id) AS product_count
                FROM b2b_customer bc
                JOIN res_partner rp ON bc.partner_id = rp.id
                JOIN sale_order so ON so.partner_id = rp.id
                JOIN sale_order_line sol ON sol.order_id = so.id
                WHERE so.state IN ('sale', 'done')
                GROUP BY bc.id, bc.tier_id, so.date_order::date,
                         to_char(so.date_order, 'YYYY-MM'),
                         EXTRACT(year FROM so.date_order)
            )
        """ % self._table)


class B2BAnalyticsService(models.AbstractModel):
    _name = 'b2b.analytics.service'
    _description = 'B2B Analytics Service'

    @api.model
    def get_customer_summary(self, customer_id, date_from=None, date_to=None):
        """Get comprehensive customer analytics summary"""
        customer = self.env['b2b.customer'].browse(customer_id)
        if not customer.exists():
            return {}

        if not date_from:
            date_from = date.today() - relativedelta(months=12)
        if not date_to:
            date_to = date.today()

        # Get orders
        orders = self.env['sale.order'].search([
            ('partner_id', '=', customer.partner_id.id),
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', date_from),
            ('date_order', '<=', date_to),
        ])

        # Calculate metrics
        total_revenue = sum(orders.mapped('amount_total'))
        order_count = len(orders)
        avg_order_value = total_revenue / order_count if order_count else 0

        # Top products
        order_lines = orders.mapped('order_line')
        product_sales = {}
        for line in order_lines:
            pid = line.product_id.id
            if pid not in product_sales:
                product_sales[pid] = {'name': line.product_id.name, 'quantity': 0, 'revenue': 0}
            product_sales[pid]['quantity'] += line.product_uom_qty
            product_sales[pid]['revenue'] += line.price_subtotal

        top_products = sorted(product_sales.values(), key=lambda x: x['revenue'], reverse=True)[:10]

        # Monthly trend
        monthly_data = self._get_monthly_trend(customer.partner_id.id, date_from, date_to)

        return {
            'customer': {
                'id': customer.id,
                'name': customer.name,
                'tier': customer.tier_id.name if customer.tier_id else 'None',
            },
            'summary': {
                'total_revenue': total_revenue,
                'order_count': order_count,
                'avg_order_value': avg_order_value,
                'first_order_date': min(orders.mapped('date_order')).date() if orders else None,
                'last_order_date': max(orders.mapped('date_order')).date() if orders else None,
            },
            'top_products': top_products,
            'monthly_trend': monthly_data,
            'clv': self.calculate_clv(customer_id),
        }

    @api.model
    def calculate_clv(self, customer_id):
        """Calculate Customer Lifetime Value"""
        customer = self.env['b2b.customer'].browse(customer_id)

        # Get historical data
        orders = self.env['sale.order'].search([
            ('partner_id', '=', customer.partner_id.id),
            ('state', 'in', ['sale', 'done']),
        ])

        if not orders:
            return {'clv': 0, 'method': 'no_data'}

        # Calculate metrics
        total_revenue = sum(orders.mapped('amount_total'))
        order_count = len(orders)
        first_order = min(orders.mapped('date_order'))
        last_order = max(orders.mapped('date_order'))

        # Customer lifespan in months
        lifespan_days = (last_order - first_order).days or 1
        lifespan_months = max(lifespan_days / 30, 1)

        # Average monthly revenue
        avg_monthly_revenue = total_revenue / lifespan_months

        # Purchase frequency (orders per month)
        purchase_frequency = order_count / lifespan_months

        # Average order value
        aov = total_revenue / order_count

        # Simple CLV projection (36 months)
        projected_clv = avg_monthly_revenue * 36

        # Churn-adjusted CLV
        retention_rate = 0.85  # Assume 85% retention
        discount_rate = 0.10  # 10% discount rate
        adjusted_clv = (aov * purchase_frequency * 12) / (1 + discount_rate - retention_rate)

        return {
            'clv': adjusted_clv,
            'projected_clv_36m': projected_clv,
            'avg_monthly_revenue': avg_monthly_revenue,
            'purchase_frequency': purchase_frequency,
            'aov': aov,
            'lifespan_months': lifespan_months,
            'method': 'historical',
        }

    @api.model
    def _get_monthly_trend(self, partner_id, date_from, date_to):
        """Get monthly revenue trend"""
        self.env.cr.execute("""
            SELECT
                to_char(date_order, 'YYYY-MM') as month,
                COUNT(*) as orders,
                SUM(amount_total) as revenue
            FROM sale_order
            WHERE partner_id = %s
                AND state IN ('sale', 'done')
                AND date_order >= %s
                AND date_order <= %s
            GROUP BY to_char(date_order, 'YYYY-MM')
            ORDER BY month
        """, (partner_id, date_from, date_to))

        return [{'month': r[0], 'orders': r[1], 'revenue': r[2]} for r in self.env.cr.fetchall()]

    @api.model
    def get_executive_dashboard(self):
        """Get executive B2B dashboard data"""
        today = date.today()
        month_start = today.replace(day=1)
        last_month_start = month_start - relativedelta(months=1)

        # Current month metrics
        current_orders = self.env['sale.order'].search([
            ('date_order', '>=', month_start),
            ('state', 'in', ['sale', 'done']),
            ('b2b_customer_id', '!=', False),
        ])

        # Last month metrics
        last_orders = self.env['sale.order'].search([
            ('date_order', '>=', last_month_start),
            ('date_order', '<', month_start),
            ('state', 'in', ['sale', 'done']),
            ('b2b_customer_id', '!=', False),
        ])

        current_revenue = sum(current_orders.mapped('amount_total'))
        last_revenue = sum(last_orders.mapped('amount_total'))

        # Top customers
        customer_revenue = {}
        for order in current_orders:
            cid = order.b2b_customer_id.id
            if cid not in customer_revenue:
                customer_revenue[cid] = {
                    'name': order.b2b_customer_id.name,
                    'revenue': 0,
                    'orders': 0,
                }
            customer_revenue[cid]['revenue'] += order.amount_total
            customer_revenue[cid]['orders'] += 1

        top_customers = sorted(customer_revenue.values(), key=lambda x: x['revenue'], reverse=True)[:10]

        # Tier distribution
        tier_data = self.env['b2b.customer'].read_group(
            [('state', '=', 'approved')],
            ['tier_id'],
            ['tier_id']
        )

        return {
            'current_month': {
                'revenue': current_revenue,
                'orders': len(current_orders),
                'customers': len(set(current_orders.mapped('b2b_customer_id.id'))),
            },
            'growth': {
                'revenue_pct': ((current_revenue - last_revenue) / last_revenue * 100) if last_revenue else 0,
            },
            'top_customers': top_customers,
            'tier_distribution': tier_data,
        }
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# TimescaleDB setup and Report Export Service
# odoo/addons/smart_dairy_b2b/services/report_export_service.py
from odoo import models, api
import xlsxwriter
import io
import base64
from datetime import date

class B2BReportExportService(models.AbstractModel):
    _name = 'b2b.report.export.service'
    _description = 'B2B Report Export Service'

    @api.model
    def export_customer_report(self, customer_id, date_from, date_to, format='xlsx'):
        """Export customer analytics report"""
        analytics = self.env['b2b.analytics.service'].get_customer_summary(
            customer_id, date_from, date_to
        )

        if format == 'xlsx':
            return self._generate_xlsx(analytics)
        elif format == 'pdf':
            return self._generate_pdf(analytics)

    def _generate_xlsx(self, data):
        """Generate Excel report"""
        output = io.BytesIO()
        workbook = xlsxwriter.Workbook(output)

        # Formats
        header_format = workbook.add_format({'bold': True, 'bg_color': '#2c5282', 'font_color': 'white'})
        currency_format = workbook.add_format({'num_format': '৳#,##0.00'})

        # Summary sheet
        summary_sheet = workbook.add_worksheet('Summary')
        summary_sheet.write('A1', 'Customer', header_format)
        summary_sheet.write('B1', data['customer']['name'])
        summary_sheet.write('A2', 'Tier', header_format)
        summary_sheet.write('B2', data['customer']['tier'])
        summary_sheet.write('A3', 'Total Revenue', header_format)
        summary_sheet.write('B3', data['summary']['total_revenue'], currency_format)
        summary_sheet.write('A4', 'Total Orders', header_format)
        summary_sheet.write('B4', data['summary']['order_count'])
        summary_sheet.write('A5', 'Avg Order Value', header_format)
        summary_sheet.write('B5', data['summary']['avg_order_value'], currency_format)
        summary_sheet.write('A6', 'CLV', header_format)
        summary_sheet.write('B6', data['clv']['clv'], currency_format)

        # Top Products sheet
        products_sheet = workbook.add_worksheet('Top Products')
        products_sheet.write_row(0, 0, ['Product', 'Quantity', 'Revenue'], header_format)
        for row, product in enumerate(data['top_products'], 1):
            products_sheet.write(row, 0, product['name'])
            products_sheet.write(row, 1, product['quantity'])
            products_sheet.write(row, 2, product['revenue'], currency_format)

        # Monthly Trend sheet
        trend_sheet = workbook.add_worksheet('Monthly Trend')
        trend_sheet.write_row(0, 0, ['Month', 'Orders', 'Revenue'], header_format)
        for row, month in enumerate(data['monthly_trend'], 1):
            trend_sheet.write(row, 0, month['month'])
            trend_sheet.write(row, 1, month['orders'])
            trend_sheet.write(row, 2, month['revenue'], currency_format)

        workbook.close()
        output.seek(0)
        return base64.b64encode(output.read())
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// B2B Analytics Dashboard Component
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_analytics_dashboard.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class B2BAnalyticsDashboard extends Component {
    static template = "smart_dairy_b2b.B2BAnalyticsDashboard";

    setup() {
        this.orm = useService("orm");
        this.state = useState({
            data: null,
            loading: true,
            dateRange: "12m",
            selectedCustomer: null,
        });

        onWillStart(async () => {
            await this.loadDashboardData();
        });

        onMounted(() => {
            this.renderCharts();
        });
    }

    async loadDashboardData() {
        this.state.loading = true;
        try {
            const data = await this.orm.call(
                "b2b.analytics.service",
                "get_executive_dashboard",
                []
            );
            this.state.data = data;
        } finally {
            this.state.loading = false;
        }
    }

    renderCharts() {
        if (!this.state.data) return;

        // Revenue trend chart
        this.renderRevenueChart();
        // Tier distribution chart
        this.renderTierChart();
    }

    renderRevenueChart() {
        // Implementation with Chart.js
    }

    renderTierChart() {
        // Implementation with Chart.js
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat("en-BD", {
            style: "currency",
            currency: "BDT",
        }).format(amount);
    }

    formatPercent(value) {
        return `${value >= 0 ? "+" : ""}${value.toFixed(1)}%`;
    }

    async exportReport(format) {
        const result = await this.orm.call(
            "b2b.report.export.service",
            "export_executive_report",
            [format]
        );
        // Trigger download
    }
}
```

---

### Days 622-630: Summary

**Day 622**: Purchase history queries, aggregation engine
**Day 623**: Spend analytics, customer metrics
**Day 624**: Product performance analysis, tier analysis
**Day 625**: Trend analysis, time series queries
**Day 626**: CLV calculation refinement, predictive model
**Day 627**: Executive dashboard, KPI aggregation
**Day 628**: Report export (PDF/Excel), export buttons
**Day 629**: Unit tests, integration tests
**Day 630**: Documentation, UAT preparation

---

## 4. Technical Specifications

### 4.1 Analytics KPIs

| KPI | Formula | Purpose |
|-----|---------|---------|
| CLV | (AOV * Frequency * Lifespan) / Churn | Customer value |
| AOV | Total Revenue / Order Count | Order size |
| Retention | Repeat Customers / Total | Loyalty |
| Growth | (Current - Previous) / Previous | Trend |

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/b2b/analytics/dashboard` | GET | Executive dashboard |
| `/api/v1/b2b/analytics/customer/{id}` | GET | Customer analytics |
| `/api/v1/b2b/analytics/export` | POST | Export report |

---

## 5. Sign-off Checklist

- [ ] Analytics data models created
- [ ] Purchase history analysis working
- [ ] CLV calculation accurate
- [ ] Executive dashboard functional
- [ ] Report export (PDF/Excel)
- [ ] Charts rendering correctly
- [ ] TimescaleDB integrated
- [ ] API endpoints tested

---

**Document End**

*Milestone 88: B2B Analytics*
*Days 621-630 | Phase 9: Commerce - B2B Portal Foundation*
