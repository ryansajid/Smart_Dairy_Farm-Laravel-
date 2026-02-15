# Milestone 19: Reporting & BI Foundations

## Smart Dairy Digital Portal + ERP Implementation
### Phase 2, Part B: Commerce Foundation
### Days 181-190 (10-Day Sprint)

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | SD-P2-MS19-RPT-v1.0 |
| **Version** | 1.0 |
| **Last Updated** | 2025-01-15 |
| **Status** | Draft |
| **Owner** | Dev 2 (Full-Stack Developer) |
| **Reviewers** | Technical Architect, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build comprehensive reporting and business intelligence foundations including executive KPI dashboards, operational analytics (Sales, Inventory, Production, Financial), farm performance dashboards, scheduled report generation, and multi-format data export capabilities.

### 1.2 Objectives

| # | Objective | Priority | BRD Ref |
|---|-----------|----------|---------|
| 1 | Build Executive KPI Dashboard | Critical | BRD §8 |
| 2 | Create Sales Analytics Reports | Critical | FR-RPT-001 |
| 3 | Implement Inventory Analytics | High | FR-RPT-002 |
| 4 | Build Production Reports | High | FR-RPT-003 |
| 5 | Create Financial Dashboards | Critical | FR-RPT-004 |
| 6 | Implement Farm Performance Analytics | High | FR-RPT-005 |
| 7 | Build Scheduled Report Generation | Medium | FR-RPT-006 |
| 8 | Create Data Export (Excel, PDF, CSV) | High | FR-RPT-007 |
| 9 | Implement Real-time KPI Updates | Medium | FR-RPT-008 |
| 10 | Build Custom Report Builder | Medium | FR-RPT-009 |

### 1.3 Key Deliverables

| Deliverable | Owner | Day |
|-------------|-------|-----|
| Executive dashboard framework | Dev 2 | 181-182 |
| Sales analytics module | Dev 2 | 183 |
| Inventory analytics | Dev 2 | 184 |
| Production reports | Dev 1 | 185 |
| Financial dashboards | Dev 1 | 186 |
| Farm performance analytics | Dev 1 | 187 |
| Scheduled reports engine | Dev 2 | 188 |
| Export functionality | Dev 3 | 189 |
| Testing & optimization | All | 190 |

### 1.4 Prerequisites

- [x] Milestone 18: Payment Gateway completed
- [x] All transactional modules operational
- [x] Data accumulated for reporting
- [x] Chart libraries installed

### 1.5 Success Criteria

| Criteria | Target | Measurement |
|----------|--------|-------------|
| Dashboard load time | <3 seconds | Performance test |
| Report generation time | <10 seconds | Benchmark test |
| Data accuracy | 100% | Audit verification |
| Export formats supported | 3+ | Feature test |
| Scheduled reports reliability | 99.9% | Execution logs |

---

## 2. Day-by-Day Breakdown

---

### Day 181-182: Executive Dashboard Framework

#### Dev 2 Tasks (16h) - Dashboard Infrastructure

**Task 2.1: Dashboard Configuration Model (8h)**

```python
# smart_reporting/models/dashboard.py
from odoo import models, fields, api
from datetime import date, timedelta
import json
import logging

_logger = logging.getLogger(__name__)

class Dashboard(models.Model):
    _name = 'smart.dashboard'
    _description = 'Dashboard Configuration'
    _order = 'sequence, name'

    name = fields.Char(string='Dashboard Name', required=True, translate=True)
    code = fields.Char(string='Code', required=True)
    sequence = fields.Integer(default=10)
    active = fields.Boolean(default=True)

    dashboard_type = fields.Selection([
        ('executive', 'Executive'),
        ('sales', 'Sales'),
        ('inventory', 'Inventory'),
        ('production', 'Production'),
        ('finance', 'Finance'),
        ('farm', 'Farm Performance'),
        ('custom', 'Custom'),
    ], string='Type', required=True)

    # Access Control
    group_ids = fields.Many2many(
        'res.groups',
        string='Allowed Groups'
    )
    user_ids = fields.Many2many(
        'res.users',
        string='Specific Users'
    )

    # Layout
    layout = fields.Selection([
        ('grid', 'Grid Layout'),
        ('flow', 'Flow Layout'),
        ('tabs', 'Tabbed Layout'),
    ], string='Layout', default='grid')

    # Widgets
    widget_ids = fields.One2many(
        'smart.dashboard.widget',
        'dashboard_id',
        string='Widgets'
    )

    # Refresh
    auto_refresh = fields.Boolean(string='Auto Refresh', default=True)
    refresh_interval = fields.Integer(
        string='Refresh Interval (seconds)',
        default=300
    )

    # Default Filters
    default_date_range = fields.Selection([
        ('today', 'Today'),
        ('yesterday', 'Yesterday'),
        ('this_week', 'This Week'),
        ('last_week', 'Last Week'),
        ('this_month', 'This Month'),
        ('last_month', 'Last Month'),
        ('this_quarter', 'This Quarter'),
        ('this_year', 'This Year'),
        ('custom', 'Custom Range'),
    ], string='Default Date Range', default='this_month')

    def get_dashboard_data(self, date_from=None, date_to=None):
        """Get all widget data for dashboard"""
        self.ensure_one()

        # Apply default date range if not specified
        if not date_from or not date_to:
            date_from, date_to = self._get_date_range()

        data = {
            'dashboard': {
                'id': self.id,
                'name': self.name,
                'type': self.dashboard_type,
                'layout': self.layout,
            },
            'date_range': {
                'from': str(date_from),
                'to': str(date_to),
            },
            'widgets': [],
        }

        for widget in self.widget_ids:
            widget_data = widget.get_widget_data(date_from, date_to)
            data['widgets'].append(widget_data)

        return data

    def _get_date_range(self):
        """Get date range based on default setting"""
        today = date.today()

        ranges = {
            'today': (today, today),
            'yesterday': (today - timedelta(days=1), today - timedelta(days=1)),
            'this_week': (today - timedelta(days=today.weekday()), today),
            'last_week': (
                today - timedelta(days=today.weekday() + 7),
                today - timedelta(days=today.weekday() + 1)
            ),
            'this_month': (today.replace(day=1), today),
            'last_month': (
                (today.replace(day=1) - timedelta(days=1)).replace(day=1),
                today.replace(day=1) - timedelta(days=1)
            ),
            'this_quarter': self._get_quarter_dates(today),
            'this_year': (today.replace(month=1, day=1), today),
        }

        return ranges.get(self.default_date_range, (today.replace(day=1), today))

    def _get_quarter_dates(self, d):
        """Get current quarter start and end dates"""
        quarter = (d.month - 1) // 3
        start = date(d.year, quarter * 3 + 1, 1)
        if quarter == 3:
            end = date(d.year, 12, 31)
        else:
            end = date(d.year, (quarter + 1) * 3 + 1, 1) - timedelta(days=1)
        return (start, min(end, d))


class DashboardWidget(models.Model):
    _name = 'smart.dashboard.widget'
    _description = 'Dashboard Widget'
    _order = 'sequence, id'

    dashboard_id = fields.Many2one(
        'smart.dashboard',
        required=True,
        ondelete='cascade'
    )

    name = fields.Char(string='Widget Title', required=True, translate=True)
    sequence = fields.Integer(default=10)

    widget_type = fields.Selection([
        ('kpi', 'KPI Card'),
        ('chart_line', 'Line Chart'),
        ('chart_bar', 'Bar Chart'),
        ('chart_pie', 'Pie Chart'),
        ('chart_donut', 'Donut Chart'),
        ('table', 'Data Table'),
        ('list', 'List'),
        ('map', 'Map'),
        ('gauge', 'Gauge'),
    ], string='Widget Type', required=True)

    # Size
    width = fields.Integer(string='Width (columns)', default=3)
    height = fields.Integer(string='Height (rows)', default=2)

    # Data Source
    data_source = fields.Selection([
        ('sales', 'Sales Data'),
        ('inventory', 'Inventory Data'),
        ('production', 'Production Data'),
        ('finance', 'Financial Data'),
        ('farm', 'Farm Data'),
        ('crm', 'CRM Data'),
        ('custom', 'Custom Query'),
    ], string='Data Source', required=True)

    # For KPI cards
    kpi_metric = fields.Selection([
        ('total_revenue', 'Total Revenue'),
        ('total_orders', 'Total Orders'),
        ('avg_order_value', 'Average Order Value'),
        ('new_customers', 'New Customers'),
        ('inventory_value', 'Inventory Value'),
        ('production_volume', 'Production Volume'),
        ('gross_profit', 'Gross Profit'),
        ('collection_rate', 'Collection Rate'),
        ('on_time_delivery', 'On-Time Delivery %'),
        ('farm_yield', 'Farm Yield'),
    ], string='KPI Metric')

    # Comparison
    show_comparison = fields.Boolean(string='Show Comparison', default=True)
    comparison_period = fields.Selection([
        ('previous_period', 'Previous Period'),
        ('same_period_last_year', 'Same Period Last Year'),
    ], string='Compare To', default='previous_period')

    # Styling
    color = fields.Char(string='Color', default='#2c5530')
    icon = fields.Char(string='Icon Class', default='fa-chart-line')

    # Custom Query
    custom_domain = fields.Text(string='Custom Domain (JSON)')
    custom_query = fields.Text(string='Custom SQL Query')

    def get_widget_data(self, date_from, date_to):
        """Get widget data for the date range"""
        self.ensure_one()

        data = {
            'id': self.id,
            'name': self.name,
            'type': self.widget_type,
            'width': self.width,
            'height': self.height,
            'color': self.color,
            'icon': self.icon,
        }

        if self.widget_type == 'kpi':
            data['value'] = self._get_kpi_value(date_from, date_to)
            if self.show_comparison:
                data['comparison'] = self._get_comparison(date_from, date_to)

        elif self.widget_type in ['chart_line', 'chart_bar']:
            data['chart_data'] = self._get_chart_data(date_from, date_to)

        elif self.widget_type in ['chart_pie', 'chart_donut']:
            data['chart_data'] = self._get_pie_data(date_from, date_to)

        elif self.widget_type == 'table':
            data['table_data'] = self._get_table_data(date_from, date_to)

        return data

    def _get_kpi_value(self, date_from, date_to):
        """Calculate KPI value"""
        if self.data_source == 'sales' and self.kpi_metric == 'total_revenue':
            orders = self.env['sale.order'].search([
                ('state', 'in', ['sale', 'done']),
                ('date_order', '>=', date_from),
                ('date_order', '<=', date_to),
            ])
            return sum(orders.mapped('amount_total'))

        elif self.data_source == 'sales' and self.kpi_metric == 'total_orders':
            return self.env['sale.order'].search_count([
                ('state', 'in', ['sale', 'done']),
                ('date_order', '>=', date_from),
                ('date_order', '<=', date_to),
            ])

        elif self.data_source == 'sales' and self.kpi_metric == 'new_customers':
            return self.env['res.partner'].search_count([
                ('customer_rank', '>', 0),
                ('create_date', '>=', date_from),
                ('create_date', '<=', date_to),
            ])

        elif self.data_source == 'inventory' and self.kpi_metric == 'inventory_value':
            quants = self.env['stock.quant'].search([
                ('location_id.usage', '=', 'internal'),
            ])
            return sum(q.quantity * q.product_id.standard_price for q in quants)

        elif self.data_source == 'production' and self.kpi_metric == 'production_volume':
            productions = self.env['mrp.production'].search([
                ('state', '=', 'done'),
                ('date_finished', '>=', date_from),
                ('date_finished', '<=', date_to),
            ])
            return sum(productions.mapped('product_qty'))

        # Add more KPI calculations...
        return 0

    def _get_comparison(self, date_from, date_to):
        """Get comparison value"""
        # Calculate previous period
        period_days = (date_to - date_from).days + 1

        if self.comparison_period == 'previous_period':
            prev_from = date_from - timedelta(days=period_days)
            prev_to = date_from - timedelta(days=1)
        else:  # same_period_last_year
            prev_from = date_from.replace(year=date_from.year - 1)
            prev_to = date_to.replace(year=date_to.year - 1)

        current_value = self._get_kpi_value(date_from, date_to)
        previous_value = self._get_kpi_value(prev_from, prev_to)

        if previous_value and previous_value != 0:
            change_pct = ((current_value - previous_value) / previous_value) * 100
        else:
            change_pct = 100 if current_value > 0 else 0

        return {
            'previous_value': previous_value,
            'change_percentage': round(change_pct, 1),
            'trend': 'up' if change_pct > 0 else ('down' if change_pct < 0 else 'flat'),
        }

    def _get_chart_data(self, date_from, date_to):
        """Get time-series chart data"""
        if self.data_source == 'sales':
            # Daily sales trend
            self.env.cr.execute("""
                SELECT DATE(date_order) as order_date,
                       SUM(amount_total) as total
                FROM sale_order
                WHERE state IN ('sale', 'done')
                  AND date_order >= %s
                  AND date_order <= %s
                GROUP BY DATE(date_order)
                ORDER BY order_date
            """, (date_from, date_to))

            results = self.env.cr.fetchall()
            return {
                'labels': [r[0].strftime('%Y-%m-%d') for r in results],
                'datasets': [{
                    'label': 'Sales',
                    'data': [float(r[1]) for r in results],
                }]
            }

        return {'labels': [], 'datasets': []}

    def _get_pie_data(self, date_from, date_to):
        """Get pie/donut chart data"""
        if self.data_source == 'sales':
            # Sales by product category
            self.env.cr.execute("""
                SELECT pc.name, SUM(sol.price_subtotal) as total
                FROM sale_order_line sol
                JOIN sale_order so ON sol.order_id = so.id
                JOIN product_product pp ON sol.product_id = pp.id
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                JOIN product_category pc ON pt.categ_id = pc.id
                WHERE so.state IN ('sale', 'done')
                  AND so.date_order >= %s
                  AND so.date_order <= %s
                GROUP BY pc.name
                ORDER BY total DESC
                LIMIT 5
            """, (date_from, date_to))

            results = self.env.cr.fetchall()
            return {
                'labels': [r[0] for r in results],
                'data': [float(r[1]) for r in results],
            }

        return {'labels': [], 'data': []}

    def _get_table_data(self, date_from, date_to):
        """Get table data"""
        if self.data_source == 'sales':
            # Top products
            self.env.cr.execute("""
                SELECT pt.name, SUM(sol.product_uom_qty) as qty,
                       SUM(sol.price_subtotal) as total
                FROM sale_order_line sol
                JOIN sale_order so ON sol.order_id = so.id
                JOIN product_product pp ON sol.product_id = pp.id
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                WHERE so.state IN ('sale', 'done')
                  AND so.date_order >= %s
                  AND so.date_order <= %s
                GROUP BY pt.name
                ORDER BY total DESC
                LIMIT 10
            """, (date_from, date_to))

            results = self.env.cr.fetchall()
            return {
                'headers': ['Product', 'Quantity', 'Revenue'],
                'rows': [
                    [r[0], f"{r[1]:,.0f}", f"BDT {r[2]:,.0f}"]
                    for r in results
                ]
            }

        return {'headers': [], 'rows': []}
```

**Task 2.2: Executive Dashboard Data (8h)**

```python
# smart_reporting/models/executive_kpis.py
from odoo import models, fields, api
from datetime import date, timedelta

class ExecutiveKPIs(models.Model):
    _name = 'smart.executive.kpi'
    _description = 'Executive KPI Snapshot'
    _order = 'date desc'

    date = fields.Date(string='Date', required=True, default=fields.Date.today)

    # Revenue KPIs
    daily_revenue = fields.Float(string='Daily Revenue')
    mtd_revenue = fields.Float(string='MTD Revenue')
    ytd_revenue = fields.Float(string='YTD Revenue')
    revenue_target = fields.Float(string='Revenue Target')
    revenue_achievement = fields.Float(
        string='Achievement %',
        compute='_compute_achievements'
    )

    # Order KPIs
    daily_orders = fields.Integer(string='Daily Orders')
    mtd_orders = fields.Integer(string='MTD Orders')
    avg_order_value = fields.Float(string='Avg Order Value')

    # Customer KPIs
    total_customers = fields.Integer(string='Total Customers')
    new_customers_mtd = fields.Integer(string='New Customers (MTD)')
    active_customers = fields.Integer(string='Active Customers (30d)')
    customer_retention = fields.Float(string='Retention Rate %')

    # Inventory KPIs
    total_inventory_value = fields.Float(string='Inventory Value')
    inventory_turnover = fields.Float(string='Inventory Turnover')
    stockout_count = fields.Integer(string='Stockout Items')
    expiring_soon = fields.Integer(string='Expiring in 7 Days')

    # Production KPIs
    daily_production_liters = fields.Float(string='Daily Production (L)')
    production_efficiency = fields.Float(string='Production Efficiency %')
    quality_pass_rate = fields.Float(string='Quality Pass Rate %')

    # Financial KPIs
    gross_margin = fields.Float(string='Gross Margin %')
    operating_margin = fields.Float(string='Operating Margin %')
    collection_rate = fields.Float(string='Collection Rate %')
    days_sales_outstanding = fields.Float(string='DSO (Days)')

    # Farm KPIs
    total_cattle = fields.Integer(string='Total Cattle')
    avg_yield_per_cow = fields.Float(string='Avg Yield/Cow (L)')
    farm_health_score = fields.Float(string='Herd Health Score')

    @api.depends('mtd_revenue', 'revenue_target')
    def _compute_achievements(self):
        for record in self:
            if record.revenue_target:
                record.revenue_achievement = (
                    record.mtd_revenue / record.revenue_target * 100
                )
            else:
                record.revenue_achievement = 0

    @api.model
    def calculate_daily_kpis(self):
        """Scheduled job to calculate daily KPIs"""
        today = date.today()
        month_start = today.replace(day=1)
        year_start = today.replace(month=1, day=1)

        # Check if today's record exists
        existing = self.search([('date', '=', today)], limit=1)
        if existing:
            record = existing
        else:
            record = self.create({'date': today})

        # Calculate Revenue KPIs
        Order = self.env['sale.order']

        daily_orders = Order.search([
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', today),
            ('date_order', '<', today + timedelta(days=1)),
        ])
        record.daily_revenue = sum(daily_orders.mapped('amount_total'))
        record.daily_orders = len(daily_orders)

        mtd_orders = Order.search([
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', month_start),
            ('date_order', '<=', today),
        ])
        record.mtd_revenue = sum(mtd_orders.mapped('amount_total'))
        record.mtd_orders = len(mtd_orders)

        if record.mtd_orders:
            record.avg_order_value = record.mtd_revenue / record.mtd_orders

        ytd_orders = Order.search([
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', year_start),
            ('date_order', '<=', today),
        ])
        record.ytd_revenue = sum(ytd_orders.mapped('amount_total'))

        # Calculate Customer KPIs
        Partner = self.env['res.partner']
        record.total_customers = Partner.search_count([
            ('customer_rank', '>', 0)
        ])
        record.new_customers_mtd = Partner.search_count([
            ('customer_rank', '>', 0),
            ('create_date', '>=', month_start),
        ])

        thirty_days_ago = today - timedelta(days=30)
        active_customers = Order.search([
            ('state', 'in', ['sale', 'done']),
            ('date_order', '>=', thirty_days_ago),
        ]).mapped('partner_id')
        record.active_customers = len(set(active_customers.ids))

        # Calculate Inventory KPIs
        Quant = self.env['stock.quant']
        internal_quants = Quant.search([
            ('location_id.usage', '=', 'internal')
        ])
        record.total_inventory_value = sum(
            q.quantity * q.product_id.standard_price
            for q in internal_quants
        )

        # Stockouts
        products_with_stock = Quant.search([
            ('location_id.usage', '=', 'internal'),
            ('quantity', '<=', 0),
        ]).mapped('product_id')
        record.stockout_count = len(products_with_stock)

        # Expiring soon
        seven_days = today + timedelta(days=7)
        Lot = self.env['stock.lot']
        expiring = Lot.search_count([
            ('expiration_date', '<=', seven_days),
            ('expiration_date', '>=', today),
        ])
        record.expiring_soon = expiring

        # Calculate Production KPIs
        Production = self.env['mrp.production']
        daily_production = Production.search([
            ('state', '=', 'done'),
            ('date_finished', '>=', today),
            ('date_finished', '<', today + timedelta(days=1)),
        ])
        record.daily_production_liters = sum(
            daily_production.mapped('product_qty')
        )

        # Calculate Collection Rate
        Invoice = self.env['account.move']
        total_invoiced = Invoice.search([
            ('move_type', '=', 'out_invoice'),
            ('state', '=', 'posted'),
            ('invoice_date', '>=', month_start),
            ('invoice_date', '<=', today),
        ])
        total_invoiced_amount = sum(total_invoiced.mapped('amount_total'))
        total_collected = sum(
            total_invoiced.mapped('amount_total')
        ) - sum(total_invoiced.mapped('amount_residual'))

        if total_invoiced_amount:
            record.collection_rate = (
                total_collected / total_invoiced_amount * 100
            )

        return record
```

---

### Day 183-187: Operational Analytics

*(Condensed - key report models)*

```python
# smart_reporting/models/sales_analytics.py
class SalesAnalytics(models.Model):
    _name = 'smart.sales.analytics'
    _description = 'Sales Analytics'
    _auto = False  # Database view

    date = fields.Date(string='Date')
    partner_id = fields.Many2one('res.partner', string='Customer')
    product_id = fields.Many2one('product.product', string='Product')
    category_id = fields.Many2one('product.category', string='Category')
    territory_id = fields.Many2one('smart.sales.territory', string='Territory')
    salesperson_id = fields.Many2one('res.users', string='Salesperson')

    quantity = fields.Float(string='Quantity')
    revenue = fields.Float(string='Revenue')
    cost = fields.Float(string='Cost')
    margin = fields.Float(string='Margin')
    margin_pct = fields.Float(string='Margin %')

    order_count = fields.Integer(string='Order Count')
    avg_order_value = fields.Float(string='Avg Order Value')

    @api.model
    def init(self):
        tools.drop_view_if_exists(self.env.cr, 'smart_sales_analytics')
        self.env.cr.execute("""
            CREATE OR REPLACE VIEW smart_sales_analytics AS (
                SELECT
                    row_number() OVER () AS id,
                    DATE(so.date_order) AS date,
                    so.partner_id,
                    sol.product_id,
                    pt.categ_id AS category_id,
                    rp.territory_id,
                    so.user_id AS salesperson_id,
                    SUM(sol.product_uom_qty) AS quantity,
                    SUM(sol.price_subtotal) AS revenue,
                    SUM(sol.product_uom_qty * pp.standard_price) AS cost,
                    SUM(sol.price_subtotal) -
                        SUM(sol.product_uom_qty * pp.standard_price) AS margin,
                    CASE WHEN SUM(sol.price_subtotal) > 0
                         THEN (SUM(sol.price_subtotal) -
                               SUM(sol.product_uom_qty * pp.standard_price)) /
                               SUM(sol.price_subtotal) * 100
                         ELSE 0
                    END AS margin_pct,
                    COUNT(DISTINCT so.id) AS order_count,
                    SUM(sol.price_subtotal) / NULLIF(COUNT(DISTINCT so.id), 0)
                        AS avg_order_value
                FROM sale_order_line sol
                JOIN sale_order so ON sol.order_id = so.id
                JOIN product_product pp ON sol.product_id = pp.id
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                LEFT JOIN res_partner rp ON so.partner_id = rp.id
                WHERE so.state IN ('sale', 'done')
                GROUP BY DATE(so.date_order), so.partner_id, sol.product_id,
                         pt.categ_id, rp.territory_id, so.user_id
            )
        """)


# smart_reporting/models/inventory_analytics.py
class InventoryAnalytics(models.Model):
    _name = 'smart.inventory.analytics'
    _description = 'Inventory Analytics'

    @api.model
    def get_inventory_summary(self):
        """Get inventory summary by category"""
        self.env.cr.execute("""
            SELECT
                pc.name AS category,
                COUNT(DISTINCT pp.id) AS product_count,
                SUM(sq.quantity) AS total_quantity,
                SUM(sq.quantity * pp.standard_price) AS total_value,
                SUM(CASE WHEN sq.quantity <= 0 THEN 1 ELSE 0 END) AS stockout_count
            FROM stock_quant sq
            JOIN product_product pp ON sq.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            JOIN product_category pc ON pt.categ_id = pc.id
            JOIN stock_location sl ON sq.location_id = sl.id
            WHERE sl.usage = 'internal'
            GROUP BY pc.name
            ORDER BY total_value DESC
        """)
        return self.env.cr.dictfetchall()

    @api.model
    def get_expiry_report(self, days=30):
        """Get products expiring within days"""
        expiry_date = date.today() + timedelta(days=days)

        return self.env['stock.lot'].search_read([
            ('expiration_date', '<=', expiry_date),
            ('expiration_date', '>=', date.today()),
            ('product_qty', '>', 0),
        ], ['name', 'product_id', 'expiration_date', 'product_qty'],
        order='expiration_date asc')
```

---

### Day 188: Scheduled Report Generation

```python
# smart_reporting/models/scheduled_report.py
from odoo import models, fields, api
from datetime import datetime, timedelta
import base64
import logging

_logger = logging.getLogger(__name__)

class ScheduledReport(models.Model):
    _name = 'smart.scheduled.report'
    _description = 'Scheduled Report'
    _inherit = ['mail.thread']

    name = fields.Char(string='Report Name', required=True)
    active = fields.Boolean(default=True)

    report_type = fields.Selection([
        ('sales_summary', 'Sales Summary'),
        ('inventory_status', 'Inventory Status'),
        ('production_report', 'Production Report'),
        ('financial_summary', 'Financial Summary'),
        ('customer_report', 'Customer Report'),
        ('custom', 'Custom Report'),
    ], string='Report Type', required=True)

    # Schedule
    frequency = fields.Selection([
        ('daily', 'Daily'),
        ('weekly', 'Weekly'),
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
    ], string='Frequency', required=True, default='daily')

    day_of_week = fields.Selection([
        ('0', 'Monday'), ('1', 'Tuesday'), ('2', 'Wednesday'),
        ('3', 'Thursday'), ('4', 'Friday'), ('5', 'Saturday'), ('6', 'Sunday'),
    ], string='Day of Week')
    day_of_month = fields.Integer(string='Day of Month', default=1)
    run_time = fields.Float(string='Run Time', default=6.0)  # 6 AM

    last_run = fields.Datetime(string='Last Run')
    next_run = fields.Datetime(string='Next Run', compute='_compute_next_run')

    # Output
    output_format = fields.Selection([
        ('pdf', 'PDF'),
        ('xlsx', 'Excel'),
        ('csv', 'CSV'),
    ], string='Output Format', default='xlsx')

    # Recipients
    recipient_ids = fields.Many2many(
        'res.users',
        string='Email Recipients'
    )
    recipient_emails = fields.Text(
        string='Additional Emails',
        help='Comma-separated email addresses'
    )

    # Generated Reports
    report_history_ids = fields.One2many(
        'smart.report.history',
        'scheduled_report_id',
        string='Report History'
    )

    @api.depends('frequency', 'last_run')
    def _compute_next_run(self):
        for report in self:
            if report.last_run:
                if report.frequency == 'daily':
                    report.next_run = report.last_run + timedelta(days=1)
                elif report.frequency == 'weekly':
                    report.next_run = report.last_run + timedelta(weeks=1)
                elif report.frequency == 'monthly':
                    report.next_run = report.last_run + timedelta(days=30)
                else:
                    report.next_run = report.last_run + timedelta(days=90)
            else:
                report.next_run = datetime.now()

    @api.model
    def _cron_generate_reports(self):
        """Scheduled job to generate reports"""
        now = datetime.now()

        reports = self.search([
            ('active', '=', True),
            '|',
            ('next_run', '<=', now),
            ('last_run', '=', False),
        ])

        for report in reports:
            try:
                report.generate_and_send()
            except Exception as e:
                _logger.error(f"Error generating report {report.name}: {e}")

    def generate_and_send(self):
        """Generate report and send to recipients"""
        self.ensure_one()

        # Generate report data
        report_data = self._get_report_data()

        # Create file
        if self.output_format == 'xlsx':
            file_content = self._generate_excel(report_data)
            filename = f"{self.name}_{datetime.now().strftime('%Y%m%d')}.xlsx"
            mimetype = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        elif self.output_format == 'csv':
            file_content = self._generate_csv(report_data)
            filename = f"{self.name}_{datetime.now().strftime('%Y%m%d')}.csv"
            mimetype = 'text/csv'
        else:
            file_content = self._generate_pdf(report_data)
            filename = f"{self.name}_{datetime.now().strftime('%Y%m%d')}.pdf"
            mimetype = 'application/pdf'

        # Save to history
        history = self.env['smart.report.history'].create({
            'scheduled_report_id': self.id,
            'generated_at': datetime.now(),
            'file_name': filename,
            'file_content': base64.b64encode(file_content),
        })

        # Send email
        self._send_report_email(history, filename, file_content, mimetype)

        # Update last run
        self.last_run = datetime.now()

    def _get_report_data(self):
        """Get report data based on type"""
        if self.report_type == 'sales_summary':
            return self.env['smart.sales.analytics'].get_sales_summary()
        elif self.report_type == 'inventory_status':
            return self.env['smart.inventory.analytics'].get_inventory_summary()
        # Add more report types...
        return {}

    def _generate_excel(self, data):
        """Generate Excel file"""
        import io
        import xlsxwriter

        output = io.BytesIO()
        workbook = xlsxwriter.Workbook(output)
        worksheet = workbook.add_worksheet('Report')

        # Write headers
        headers = list(data[0].keys()) if data else []
        for col, header in enumerate(headers):
            worksheet.write(0, col, header)

        # Write data
        for row, record in enumerate(data, 1):
            for col, key in enumerate(headers):
                worksheet.write(row, col, record.get(key, ''))

        workbook.close()
        return output.getvalue()

    def _send_report_email(self, history, filename, content, mimetype):
        """Send report via email"""
        recipients = list(self.recipient_ids.mapped('email'))
        if self.recipient_emails:
            recipients.extend([
                e.strip() for e in self.recipient_emails.split(',')
            ])

        if not recipients:
            return

        # Create attachment
        attachment = self.env['ir.attachment'].create({
            'name': filename,
            'datas': base64.b64encode(content),
            'mimetype': mimetype,
        })

        # Send email
        template = self.env.ref(
            'smart_reporting.email_template_scheduled_report'
        )
        template.send_mail(
            history.id,
            email_values={
                'email_to': ', '.join(recipients),
                'attachment_ids': [(6, 0, [attachment.id])],
            }
        )


class ReportHistory(models.Model):
    _name = 'smart.report.history'
    _description = 'Report Generation History'
    _order = 'generated_at desc'

    scheduled_report_id = fields.Many2one(
        'smart.scheduled.report',
        required=True,
        ondelete='cascade'
    )
    generated_at = fields.Datetime(string='Generated At')
    file_name = fields.Char(string='File Name')
    file_content = fields.Binary(string='File')
    sent_to = fields.Text(string='Sent To')
```

---

### Day 189: Export Functionality

```python
# smart_reporting/models/report_export.py
from odoo import models, api
import io
import base64
import csv
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4, landscape
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph
from reportlab.lib.styles import getSampleStyleSheet

class ReportExport(models.AbstractModel):
    _name = 'smart.report.export'
    _description = 'Report Export Utilities'

    @api.model
    def export_to_excel(self, data, headers, title='Report'):
        """Export data to Excel format"""
        import xlsxwriter

        output = io.BytesIO()
        workbook = xlsxwriter.Workbook(output)
        worksheet = workbook.add_worksheet(title[:31])  # Excel limit

        # Formats
        header_format = workbook.add_format({
            'bold': True,
            'bg_color': '#2c5530',
            'font_color': 'white',
            'border': 1,
        })
        cell_format = workbook.add_format({'border': 1})
        number_format = workbook.add_format({
            'border': 1,
            'num_format': '#,##0.00',
        })

        # Write title
        worksheet.merge_range('A1:' + chr(65 + len(headers) - 1) + '1', title)

        # Write headers
        for col, header in enumerate(headers):
            worksheet.write(2, col, header, header_format)

        # Write data
        for row, record in enumerate(data, 3):
            for col, value in enumerate(record):
                if isinstance(value, (int, float)):
                    worksheet.write(row, col, value, number_format)
                else:
                    worksheet.write(row, col, value, cell_format)

        # Auto-fit columns
        for col, header in enumerate(headers):
            max_width = max(len(str(header)), max(
                len(str(r[col])) for r in data
            ) if data else 0)
            worksheet.set_column(col, col, min(max_width + 2, 50))

        workbook.close()
        return output.getvalue()

    @api.model
    def export_to_csv(self, data, headers):
        """Export data to CSV format"""
        output = io.StringIO()
        writer = csv.writer(output)

        writer.writerow(headers)
        for row in data:
            writer.writerow(row)

        return output.getvalue().encode('utf-8-sig')  # BOM for Excel

    @api.model
    def export_to_pdf(self, data, headers, title='Report'):
        """Export data to PDF format"""
        output = io.BytesIO()
        doc = SimpleDocTemplate(output, pagesize=landscape(A4))
        elements = []

        styles = getSampleStyleSheet()

        # Title
        elements.append(Paragraph(title, styles['Title']))
        elements.append(Paragraph('<br/><br/>', styles['Normal']))

        # Table
        table_data = [headers] + data

        table = Table(table_data)
        table.setStyle(TableStyle([
            ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#2c5530')),
            ('TEXTCOLOR', (0, 0), (-1, 0), colors.white),
            ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
            ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
            ('FONTSIZE', (0, 0), (-1, 0), 10),
            ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
            ('BACKGROUND', (0, 1), (-1, -1), colors.white),
            ('TEXTCOLOR', (0, 1), (-1, -1), colors.black),
            ('FONTNAME', (0, 1), (-1, -1), 'Helvetica'),
            ('FONTSIZE', (0, 1), (-1, -1), 8),
            ('GRID', (0, 0), (-1, -1), 1, colors.black),
            ('ROWBACKGROUNDS', (0, 1), (-1, -1), [colors.white, colors.lightgrey]),
        ]))

        elements.append(table)
        doc.build(elements)

        return output.getvalue()
```

---

## 4. Module Manifest

```python
# smart_reporting/__manifest__.py
{
    'name': 'Smart Dairy Reporting & BI',
    'version': '19.0.1.0.0',
    'category': 'Reporting',
    'summary': 'Business Intelligence and Reporting for Smart Dairy',
    'depends': [
        'sale', 'stock', 'mrp', 'account',
        'smart_crm_dairy', 'smart_farm_mgmt',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/reporting_security.xml',
        'data/dashboard_data.xml',
        'data/scheduled_reports_cron.xml',
        'views/dashboard_views.xml',
        'views/report_views.xml',
        'views/scheduled_report_views.xml',
        'views/menus.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'smart_reporting/static/src/js/*.js',
            'smart_reporting/static/src/scss/*.scss',
            'smart_reporting/static/src/xml/*.xml',
        ],
    },
    'license': 'LGPL-3',
}
```

---

## 5. Milestone Completion Checklist

- [ ] Executive dashboard operational
- [ ] Sales analytics reports working
- [ ] Inventory analytics complete
- [ ] Production reports functional
- [ ] Financial dashboards active
- [ ] Farm performance analytics
- [ ] Scheduled reports generating
- [ ] Excel/PDF/CSV exports working
- [ ] Real-time KPI updates
- [ ] Dashboard load <3 seconds
- [ ] All tests passing

---

**End of Milestone 19 Documentation**
