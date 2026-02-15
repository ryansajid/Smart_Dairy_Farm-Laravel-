# Milestone 46: Farm Analytics Dashboard
## Smart Dairy Digital Smart Portal + ERP - Phase 5

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P5-M46-001 |
| **Milestone** | 46 of 50 (6 of 10 in Phase 5) |
| **Title** | Farm Analytics Dashboard |
| **Phase** | Phase 5 - Farm Management Foundation |
| **Days** | Days 301-310 (of 350) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build a comprehensive farm analytics dashboard providing real-time KPIs, production trends, health analytics, breeding performance metrics, and feed efficiency tracking with customizable visualization widgets.

### 1.2 Objectives

| # | Objective |
|---|-----------|
| 1 | Design KPI calculation framework with caching |
| 2 | Implement production KPIs (milk yield, quality trends) |
| 3 | Build health analytics (disease incidence, vaccination compliance) |
| 4 | Create breeding performance dashboards |
| 5 | Implement feed efficiency metrics |
| 6 | Build herd composition analytics |
| 7 | Develop comparison and benchmarking tools |
| 8 | Create alert summary dashboard |
| 9 | Implement custom dashboard builder |
| 10 | Complete testing and documentation |

### 1.3 Key Deliverables

| Deliverable | Type | Description |
|-------------|------|-------------|
| KPI Engine | Backend | Calculation framework with Redis caching |
| Production Dashboard | Frontend | Milk yield and quality analytics |
| Health Dashboard | Frontend | Disease and vaccination tracking |
| Breeding Dashboard | Frontend | Reproduction performance metrics |
| Feed Efficiency Module | Backend | FCR and cost calculations |
| Dashboard Builder | Frontend | Customizable widget system |
| Mobile Dashboard | Mobile | Key metrics on Flutter app |

### 1.4 Success Criteria

| Criteria | Target |
|----------|--------|
| Dashboard load time | <3 seconds |
| KPI calculation accuracy | 99.9% |
| Data refresh frequency | Real-time (WebSocket) |
| Custom dashboard widgets | 15+ available |
| Mobile dashboard responsiveness | <2 seconds |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Description | Day(s) | Status |
|--------|--------|-------------|--------|--------|
| RFP-FARM-015 | RFP | Farm KPI dashboard | 301-302 | Planned |
| BRD-FARM-050 | BRD | Production trend analysis | 302 | Planned |
| BRD-FARM-051 | BRD | Health analytics dashboard | 303 | Planned |
| BRD-FARM-052 | BRD | Breeding performance metrics | 304 | Planned |
| SRS-FARM-095 | SRS | Real-time data visualization | 301 | Planned |
| SRS-FARM-096 | SRS | Custom dashboard configuration | 309 | Planned |

---

## 3. Day-by-Day Implementation

### Day 301: KPI Framework & Infrastructure

**Theme:** Foundation for Analytics Engine

#### Dev 1 - Backend Lead (8h)

**Task 1: KPI Calculation Engine (5h)**
```python
# farm_analytics/models/kpi_engine.py
from odoo import models, fields, api
from datetime import datetime, timedelta
import json
import redis

class FarmKPIEngine(models.AbstractModel):
    _name = 'farm.kpi.engine'
    _description = 'Farm KPI Calculation Engine'

    def _get_redis_client(self):
        """Get Redis connection for KPI caching"""
        config = self.env['ir.config_parameter'].sudo()
        return redis.Redis(
            host=config.get_param('farm.redis.host', 'localhost'),
            port=int(config.get_param('farm.redis.port', 6379)),
            db=int(config.get_param('farm.redis.kpi_db', 1)),
            decode_responses=True
        )

    def get_kpi(self, kpi_name, farm_id=None, date_range='today', force_refresh=False):
        """Get KPI value with caching"""
        cache_key = f"kpi:{kpi_name}:{farm_id or 'all'}:{date_range}"
        redis_client = self._get_redis_client()

        # Check cache first
        if not force_refresh:
            cached = redis_client.get(cache_key)
            if cached:
                return json.loads(cached)

        # Calculate KPI
        calculator = getattr(self, f'_calc_{kpi_name}', None)
        if not calculator:
            raise ValueError(f'Unknown KPI: {kpi_name}')

        result = calculator(farm_id, date_range)

        # Cache result (TTL based on KPI type)
        ttl = self._get_kpi_ttl(kpi_name)
        redis_client.setex(cache_key, ttl, json.dumps(result))

        return result

    def _get_kpi_ttl(self, kpi_name):
        """Get cache TTL for KPI type"""
        realtime_kpis = ['total_animals', 'animals_in_milk', 'alerts_count']
        if kpi_name in realtime_kpis:
            return 60  # 1 minute
        return 300  # 5 minutes

    def _get_date_range(self, date_range):
        """Parse date range string to dates"""
        today = fields.Date.today()
        if date_range == 'today':
            return today, today
        elif date_range == 'week':
            return today - timedelta(days=7), today
        elif date_range == 'month':
            return today - timedelta(days=30), today
        elif date_range == 'quarter':
            return today - timedelta(days=90), today
        elif date_range == 'year':
            return today - timedelta(days=365), today
        return today, today

    # Production KPIs
    def _calc_total_milk_yield(self, farm_id, date_range):
        start_date, end_date = self._get_date_range(date_range)
        domain = [('production_date', '>=', start_date), ('production_date', '<=', end_date)]
        if farm_id:
            domain.append(('animal_id.farm_id', '=', farm_id))

        total = self.env['farm.milk.production'].search(domain)
        return {
            'value': sum(p.yield_liters for p in total),
            'unit': 'liters',
            'trend': self._calc_trend('milk_yield', farm_id, date_range),
            'calculated_at': datetime.now().isoformat(),
        }

    def _calc_average_milk_yield(self, farm_id, date_range):
        start_date, end_date = self._get_date_range(date_range)
        domain = [('production_date', '>=', start_date), ('production_date', '<=', end_date)]
        if farm_id:
            domain.append(('animal_id.farm_id', '=', farm_id))

        records = self.env['farm.milk.production'].search(domain)
        animals = len(set(r.animal_id.id for r in records))

        return {
            'value': sum(r.yield_liters for r in records) / animals if animals else 0,
            'unit': 'liters/animal/day',
            'animal_count': animals,
        }

    # Health KPIs
    def _calc_disease_incidence_rate(self, farm_id, date_range):
        start_date, end_date = self._get_date_range(date_range)
        domain = [('diagnosis_date', '>=', start_date), ('diagnosis_date', '<=', end_date)]
        if farm_id:
            domain.append(('animal_id.farm_id', '=', farm_id))

        cases = self.env['farm.health.record'].search_count(domain)
        total_animals = self.env['farm.animal'].search_count([
            ('status', 'in', ['active', 'pregnant', 'lactating']),
            ('farm_id', '=', farm_id) if farm_id else (1, '=', 1)
        ])

        return {
            'value': (cases / total_animals * 100) if total_animals else 0,
            'unit': '%',
            'cases': cases,
            'total_animals': total_animals,
        }

    def _calc_vaccination_compliance(self, farm_id, date_range):
        # Calculate vaccination schedule compliance
        domain = [('status', 'in', ['active', 'pregnant', 'lactating'])]
        if farm_id:
            domain.append(('farm_id', '=', farm_id))

        animals = self.env['farm.animal'].search(domain)
        compliant = 0

        for animal in animals:
            due_vaccinations = self.env['farm.vaccination.schedule'].search([
                ('animal_id', '=', animal.id),
                ('due_date', '<=', fields.Date.today()),
                ('status', '=', 'pending'),
            ])
            if not due_vaccinations:
                compliant += 1

        return {
            'value': (compliant / len(animals) * 100) if animals else 100,
            'unit': '%',
            'compliant_count': compliant,
            'total_count': len(animals),
        }

    def _calc_trend(self, metric, farm_id, date_range):
        """Calculate trend (percentage change vs previous period)"""
        # Implementation for trend calculation
        return {'direction': 'up', 'percent': 5.2}
```

**Task 2: KPI Registry Model (3h)**
```python
# farm_analytics/models/kpi_registry.py
class FarmKPIDefinition(models.Model):
    _name = 'farm.kpi.definition'
    _description = 'KPI Definition Registry'

    name = fields.Char(string='KPI Name', required=True)
    code = fields.Char(string='Technical Code', required=True)
    category = fields.Selection([
        ('production', 'Production'),
        ('health', 'Health'),
        ('breeding', 'Breeding'),
        ('feed', 'Feed & Nutrition'),
        ('financial', 'Financial'),
        ('herd', 'Herd Composition'),
    ], required=True)
    description = fields.Text(string='Description')
    unit = fields.Char(string='Unit of Measure')
    formula = fields.Text(string='Calculation Formula')

    # Visualization
    widget_type = fields.Selection([
        ('number', 'Single Number'),
        ('gauge', 'Gauge/Meter'),
        ('trend', 'Trend Line'),
        ('bar', 'Bar Chart'),
        ('pie', 'Pie Chart'),
    ], default='number')
    color_good = fields.Char(default='#28a745')
    color_warning = fields.Char(default='#ffc107')
    color_bad = fields.Char(default='#dc3545')
    threshold_good = fields.Float(string='Good Threshold')
    threshold_warning = fields.Float(string='Warning Threshold')

    # Refresh settings
    refresh_interval = fields.Integer(string='Refresh Interval (seconds)', default=300)
    is_realtime = fields.Boolean(string='Real-time Updates')

    # Permissions
    group_ids = fields.Many2many('res.groups', string='Visible to Groups')
    active = fields.Boolean(default=True)

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'KPI code must be unique!'),
    ]
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Redis Setup & Caching Infrastructure (4h)**
```python
# farm_analytics/services/cache_service.py
import redis
import json
from datetime import datetime

class KPICacheService:
    """Redis-based caching service for KPIs"""

    def __init__(self, env):
        self.env = env
        self._client = None

    @property
    def client(self):
        if not self._client:
            config = self.env['ir.config_parameter'].sudo()
            self._client = redis.Redis(
                host=config.get_param('farm.redis.host', 'localhost'),
                port=int(config.get_param('farm.redis.port', 6379)),
                db=1,
                decode_responses=True
            )
        return self._client

    def get_dashboard_data(self, dashboard_id, user_id):
        """Get cached dashboard data"""
        key = f"dashboard:{dashboard_id}:{user_id}"
        data = self.client.get(key)
        return json.loads(data) if data else None

    def set_dashboard_data(self, dashboard_id, user_id, data, ttl=60):
        """Cache dashboard data"""
        key = f"dashboard:{dashboard_id}:{user_id}"
        self.client.setex(key, ttl, json.dumps(data, default=str))

    def invalidate_kpi(self, kpi_code, farm_id=None):
        """Invalidate cached KPI"""
        pattern = f"kpi:{kpi_code}:{farm_id or '*'}:*"
        keys = self.client.keys(pattern)
        if keys:
            self.client.delete(*keys)

    def get_realtime_metrics(self, farm_id):
        """Get real-time metrics from Redis pub/sub"""
        key = f"realtime:{farm_id}"
        return json.loads(self.client.get(key) or '{}')

    def publish_metric_update(self, farm_id, metric_name, value):
        """Publish real-time metric update"""
        channel = f"metrics:{farm_id}"
        message = json.dumps({
            'metric': metric_name,
            'value': value,
            'timestamp': datetime.now().isoformat()
        })
        self.client.publish(channel, message)
```

**Task 2: WebSocket Service for Real-time Updates (4h)**
```python
# farm_analytics/controllers/websocket_controller.py
from odoo import http
from odoo.http import request
import json

class FarmAnalyticsWebSocket(http.Controller):

    @http.route('/farm/analytics/subscribe', type='http', auth='user', csrf=False)
    def subscribe_analytics(self, **kwargs):
        """Server-Sent Events endpoint for real-time KPI updates"""
        farm_id = kwargs.get('farm_id')

        def event_stream():
            import redis
            client = redis.Redis(host='localhost', port=6379, db=1)
            pubsub = client.pubsub()
            pubsub.subscribe(f"metrics:{farm_id or 'all'}")

            for message in pubsub.listen():
                if message['type'] == 'message':
                    yield f"data: {message['data']}\n\n"

        return request.make_response(
            event_stream(),
            headers={
                'Content-Type': 'text/event-stream',
                'Cache-Control': 'no-cache',
                'Connection': 'keep-alive',
            }
        )

    @http.route('/api/v1/analytics/kpis', type='json', auth='user', methods=['GET'])
    def get_kpis(self, category=None, farm_id=None, date_range='today'):
        """Get all KPIs for a category"""
        KPIEngine = request.env['farm.kpi.engine']

        definitions = request.env['farm.kpi.definition'].search([
            ('active', '=', True),
            ('category', '=', category) if category else (1, '=', 1),
        ])

        results = []
        for kpi in definitions:
            try:
                value = KPIEngine.get_kpi(kpi.code, farm_id, date_range)
                results.append({
                    'code': kpi.code,
                    'name': kpi.name,
                    'category': kpi.category,
                    'value': value.get('value'),
                    'unit': kpi.unit,
                    'trend': value.get('trend'),
                    'widget_type': kpi.widget_type,
                    'thresholds': {
                        'good': kpi.threshold_good,
                        'warning': kpi.threshold_warning,
                    }
                })
            except Exception as e:
                results.append({
                    'code': kpi.code,
                    'name': kpi.name,
                    'error': str(e),
                })

        return results
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: KPI Card OWL Components (5h)**
```javascript
/** @odoo-module **/
// farm_analytics/static/src/components/kpi_card.js
import { Component, useState, onMounted, onWillUnmount } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class KPICard extends Component {
    static template = "farm_analytics.KPICard";
    static props = {
        kpi: Object,
        farmId: { type: Number, optional: true },
        dateRange: { type: String, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.state = useState({
            value: null,
            trend: null,
            loading: true,
            error: null,
        });

        this.refreshInterval = null;

        onMounted(() => {
            this.loadKPI();
            if (this.props.kpi.is_realtime) {
                this.setupRealtimeUpdates();
            } else {
                this.refreshInterval = setInterval(
                    () => this.loadKPI(),
                    (this.props.kpi.refresh_interval || 300) * 1000
                );
            }
        });

        onWillUnmount(() => {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
            if (this.eventSource) {
                this.eventSource.close();
            }
        });
    }

    async loadKPI() {
        try {
            this.state.loading = true;
            const result = await this.rpc("/api/v1/analytics/kpi", {
                kpi_code: this.props.kpi.code,
                farm_id: this.props.farmId,
                date_range: this.props.dateRange || 'today',
            });
            this.state.value = result.value;
            this.state.trend = result.trend;
            this.state.error = null;
        } catch (e) {
            this.state.error = e.message;
        } finally {
            this.state.loading = false;
        }
    }

    setupRealtimeUpdates() {
        const farmId = this.props.farmId || 'all';
        this.eventSource = new EventSource(`/farm/analytics/subscribe?farm_id=${farmId}`);
        this.eventSource.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.metric === this.props.kpi.code) {
                this.state.value = data.value;
            }
        };
    }

    get statusClass() {
        const kpi = this.props.kpi;
        const value = this.state.value;
        if (value >= kpi.threshold_good) return 'kpi-good';
        if (value >= kpi.threshold_warning) return 'kpi-warning';
        return 'kpi-bad';
    }

    get trendIcon() {
        if (!this.state.trend) return '';
        return this.state.trend.direction === 'up' ? 'fa-arrow-up' : 'fa-arrow-down';
    }

    get trendClass() {
        if (!this.state.trend) return '';
        // For most KPIs, up is good. For some (disease rate), down is good
        const isGoodDirection = this.props.kpi.code.includes('disease')
            ? this.state.trend.direction === 'down'
            : this.state.trend.direction === 'up';
        return isGoodDirection ? 'text-success' : 'text-danger';
    }
}

registry.category("components").add("KPICard", KPICard);
```

**Task 2: Dashboard Layout Component (3h)**
```javascript
/** @odoo-module **/
// farm_analytics/static/src/components/dashboard_layout.js
import { Component, useState } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { KPICard } from "./kpi_card";

export class FarmDashboardLayout extends Component {
    static template = "farm_analytics.DashboardLayout";
    static components = { KPICard };
    static props = {
        dashboardId: { type: Number, optional: true },
    };

    setup() {
        this.state = useState({
            widgets: [],
            dateRange: 'today',
            farmId: null,
            isCustomizing: false,
        });
    }

    onDateRangeChange(range) {
        this.state.dateRange = range;
    }

    onFarmChange(farmId) {
        this.state.farmId = farmId;
    }

    toggleCustomize() {
        this.state.isCustomizing = !this.state.isCustomizing;
    }

    onWidgetDrop(event, targetIndex) {
        // Handle drag-and-drop widget reordering
        const sourceIndex = event.dataTransfer.getData('widgetIndex');
        const widgets = [...this.state.widgets];
        const [moved] = widgets.splice(sourceIndex, 1);
        widgets.splice(targetIndex, 0, moved);
        this.state.widgets = widgets;
    }
}

registry.category("actions").add("farm_analytics.dashboard", FarmDashboardLayout);
```

```xml
<!-- farm_analytics/static/src/components/kpi_card.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="farm_analytics.KPICard">
        <div class="kpi-card card h-100" t-att-class="statusClass">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="card-subtitle text-muted mb-2">
                        <t t-esc="props.kpi.name"/>
                    </h6>
                    <span t-if="state.trend" t-att-class="'badge ' + trendClass">
                        <i t-att-class="'fa ' + trendIcon"/>
                        <t t-esc="state.trend.percent"/>%
                    </span>
                </div>
                <div t-if="state.loading" class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"/>
                </div>
                <div t-elif="state.error" class="text-danger">
                    <i class="fa fa-exclamation-triangle"/> Error loading KPI
                </div>
                <div t-else="">
                    <h2 class="card-title mb-0">
                        <t t-esc="state.value"/>
                        <small class="text-muted">
                            <t t-esc="props.kpi.unit"/>
                        </small>
                    </h2>
                </div>
            </div>
        </div>
    </t>
</templates>
```

#### Day 301 Deliverables
- [x] KPI calculation engine with Redis caching
- [x] KPI definition registry model
- [x] Cache service implementation
- [x] WebSocket/SSE real-time updates
- [x] KPI card OWL component
- [x] Dashboard layout framework

---

### Day 302: Production Analytics Dashboard

**Theme:** Milk Production KPIs and Visualizations

#### Dev 1 - Backend Lead (8h)

**Task 1: Production KPI Calculations (5h)**
```python
# farm_analytics/models/production_kpis.py
class FarmProductionKPIs(models.AbstractModel):
    _name = 'farm.production.kpis'
    _inherit = 'farm.kpi.engine'
    _description = 'Production KPI Calculations'

    def _calc_total_daily_production(self, farm_id, date_range):
        today = fields.Date.today()
        domain = [('production_date', '=', today)]
        if farm_id:
            domain.append(('animal_id.farm_id', '=', farm_id))

        records = self.env['farm.milk.production'].search(domain)
        return {
            'value': sum(r.yield_liters for r in records),
            'unit': 'liters',
            'record_count': len(records),
        }

    def _calc_avg_yield_per_animal(self, farm_id, date_range):
        start_date, end_date = self._get_date_range(date_range)
        query = """
            SELECT
                AVG(daily_yield) as avg_yield
            FROM (
                SELECT
                    animal_id,
                    production_date,
                    SUM(yield_liters) as daily_yield
                FROM farm_milk_production
                WHERE production_date BETWEEN %s AND %s
                GROUP BY animal_id, production_date
            ) daily_totals
        """
        params = [start_date, end_date]
        if farm_id:
            query = query.replace(
                "WHERE production_date",
                "WHERE animal_id IN (SELECT id FROM farm_animal WHERE farm_id = %s) AND production_date"
            )
            params = [farm_id] + params

        self.env.cr.execute(query, params)
        result = self.env.cr.fetchone()

        return {
            'value': round(result[0] or 0, 2),
            'unit': 'L/animal/day',
        }

    def _calc_milk_quality_avg(self, farm_id, date_range):
        start_date, end_date = self._get_date_range(date_range)
        domain = [
            ('production_date', '>=', start_date),
            ('production_date', '<=', end_date),
        ]
        if farm_id:
            domain.append(('animal_id.farm_id', '=', farm_id))

        records = self.env['farm.milk.production'].search(domain)
        fat_values = [r.fat_percentage for r in records if r.fat_percentage]
        protein_values = [r.protein_percentage for r in records if r.protein_percentage]
        scc_values = [r.somatic_cell_count for r in records if r.somatic_cell_count]

        return {
            'avg_fat': round(sum(fat_values) / len(fat_values), 2) if fat_values else 0,
            'avg_protein': round(sum(protein_values) / len(protein_values), 2) if protein_values else 0,
            'avg_scc': round(sum(scc_values) / len(scc_values)) if scc_values else 0,
        }

    def _calc_lactation_performance(self, farm_id, date_range):
        """Calculate lactation curve performance metrics"""
        domain = [('status', '=', 'active')]
        if farm_id:
            domain.append(('animal_id.farm_id', '=', farm_id))

        lactations = self.env['farm.lactation.record'].search(domain)

        peak_yields = []
        days_to_peak = []
        persistency_values = []

        for lac in lactations:
            if lac.peak_yield:
                peak_yields.append(lac.peak_yield)
            if lac.days_to_peak:
                days_to_peak.append(lac.days_to_peak)
            if lac.persistency:
                persistency_values.append(lac.persistency)

        return {
            'avg_peak_yield': round(sum(peak_yields) / len(peak_yields), 2) if peak_yields else 0,
            'avg_days_to_peak': round(sum(days_to_peak) / len(days_to_peak)) if days_to_peak else 0,
            'avg_persistency': round(sum(persistency_values) / len(persistency_values), 2) if persistency_values else 0,
            'active_lactations': len(lactations),
        }

    def get_production_trends(self, farm_id, date_range='month'):
        """Get production trend data for charts"""
        start_date, end_date = self._get_date_range(date_range)

        query = """
            SELECT
                production_date,
                SUM(yield_liters) as total_yield,
                AVG(fat_percentage) as avg_fat,
                AVG(protein_percentage) as avg_protein,
                COUNT(DISTINCT animal_id) as animals_milked
            FROM farm_milk_production
            WHERE production_date BETWEEN %s AND %s
        """
        params = [start_date, end_date]

        if farm_id:
            query += " AND animal_id IN (SELECT id FROM farm_animal WHERE farm_id = %s)"
            params.append(farm_id)

        query += " GROUP BY production_date ORDER BY production_date"

        self.env.cr.execute(query, params)
        results = self.env.cr.dictfetchall()

        return {
            'dates': [r['production_date'].isoformat() for r in results],
            'total_yield': [float(r['total_yield'] or 0) for r in results],
            'avg_fat': [float(r['avg_fat'] or 0) for r in results],
            'avg_protein': [float(r['avg_protein'] or 0) for r in results],
            'animals_milked': [r['animals_milked'] for r in results],
        }
```

**Task 2: Production Analytics API (3h)**
```python
# farm_analytics/controllers/production_api.py
class ProductionAnalyticsAPI(http.Controller):

    @http.route('/api/v1/analytics/production/summary', type='json', auth='user')
    def production_summary(self, farm_id=None, date_range='today'):
        KPIs = request.env['farm.production.kpis']

        return {
            'total_production': KPIs._calc_total_daily_production(farm_id, date_range),
            'avg_yield': KPIs._calc_avg_yield_per_animal(farm_id, date_range),
            'quality': KPIs._calc_milk_quality_avg(farm_id, date_range),
            'lactation': KPIs._calc_lactation_performance(farm_id, date_range),
        }

    @http.route('/api/v1/analytics/production/trends', type='json', auth='user')
    def production_trends(self, farm_id=None, date_range='month'):
        KPIs = request.env['farm.production.kpis']
        return KPIs.get_production_trends(farm_id, date_range)

    @http.route('/api/v1/analytics/production/top-performers', type='json', auth='user')
    def top_performers(self, farm_id=None, limit=10):
        domain = [('status', '=', 'lactating')]
        if farm_id:
            domain.append(('farm_id', '=', farm_id))

        animals = request.env['farm.animal'].search(domain)
        performance_data = []

        for animal in animals:
            # Get average daily yield for last 30 days
            productions = request.env['farm.milk.production'].search([
                ('animal_id', '=', animal.id),
                ('production_date', '>=', fields.Date.today() - timedelta(days=30)),
            ])
            if productions:
                avg_yield = sum(p.yield_liters for p in productions) / len(productions)
                performance_data.append({
                    'id': animal.id,
                    'name': animal.name,
                    'ear_tag': animal.ear_tag,
                    'avg_yield': round(avg_yield, 2),
                    'lactation_number': animal.current_lactation_id.lactation_number if animal.current_lactation_id else 0,
                })

        # Sort and return top performers
        performance_data.sort(key=lambda x: x['avg_yield'], reverse=True)
        return performance_data[:limit]
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Production Trend Charts (4h)**
```javascript
/** @odoo-module **/
// farm_analytics/static/src/components/production_chart.js
import { Component, onMounted, useRef, useState } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { loadJS } from "@web/core/assets";

export class ProductionTrendChart extends Component {
    static template = "farm_analytics.ProductionTrendChart";
    static props = {
        farmId: { type: Number, optional: true },
        dateRange: { type: String, optional: true },
    };

    setup() {
        this.chartRef = useRef("chart");
        this.rpc = useService("rpc");
        this.state = useState({ loading: true });
        this.chart = null;

        onMounted(async () => {
            await loadJS("/web/static/lib/Chart/Chart.js");
            await this.loadData();
        });
    }

    async loadData() {
        this.state.loading = true;
        try {
            const data = await this.rpc("/api/v1/analytics/production/trends", {
                farm_id: this.props.farmId,
                date_range: this.props.dateRange || 'month',
            });
            this.renderChart(data);
        } finally {
            this.state.loading = false;
        }
    }

    renderChart(data) {
        const ctx = this.chartRef.el.getContext('2d');

        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [
                    {
                        label: 'Total Yield (L)',
                        data: data.total_yield,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Fat %',
                        data: data.avg_fat,
                        borderColor: '#ffc107',
                        borderDash: [5, 5],
                        fill: false,
                        yAxisID: 'y1',
                    },
                    {
                        label: 'Protein %',
                        data: data.avg_protein,
                        borderColor: '#28a745',
                        borderDash: [5, 5],
                        fill: false,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    title: { display: true, text: 'Production Trends' },
                    legend: { position: 'bottom' },
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: { display: true, text: 'Yield (Liters)' },
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        title: { display: true, text: 'Quality (%)' },
                        min: 0,
                        max: 10,
                        grid: { drawOnChartArea: false },
                    },
                },
            },
        });
    }
}
```

**Task 2: Top Performers Widget (4h)**
```javascript
/** @odoo-module **/
// farm_analytics/static/src/components/top_performers.js
import { Component, useState, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class TopPerformersWidget extends Component {
    static template = "farm_analytics.TopPerformers";
    static props = {
        farmId: { type: Number, optional: true },
        limit: { type: Number, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.action = useService("action");
        this.state = useState({
            performers: [],
            loading: true,
        });

        onMounted(() => this.loadData());
    }

    async loadData() {
        this.state.loading = true;
        const data = await this.rpc("/api/v1/analytics/production/top-performers", {
            farm_id: this.props.farmId,
            limit: this.props.limit || 10,
        });
        this.state.performers = data;
        this.state.loading = false;
    }

    openAnimal(animalId) {
        this.action.doAction({
            type: 'ir.actions.act_window',
            res_model: 'farm.animal',
            res_id: animalId,
            views: [[false, 'form']],
        });
    }

    getRankClass(index) {
        if (index === 0) return 'bg-warning text-dark'; // Gold
        if (index === 1) return 'bg-secondary text-white'; // Silver
        if (index === 2) return 'bg-danger text-white'; // Bronze
        return 'bg-light';
    }
}
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Production Dashboard View (5h)**
```xml
<!-- farm_analytics/views/production_dashboard.xml -->
<odoo>
    <record id="view_farm_production_dashboard" model="ir.ui.view">
        <field name="name">farm.production.dashboard</field>
        <field name="model">farm.analytics.dashboard</field>
        <field name="arch" type="xml">
            <dashboard>
                <view type="graph"/>
                <group col="4">
                    <aggregate name="total_yield" field="total_yield" string="Total Yield Today"/>
                    <aggregate name="avg_yield" field="avg_yield" string="Avg Yield/Animal"/>
                    <aggregate name="animals_milked" field="animals_milked" string="Animals Milked"/>
                    <aggregate name="avg_fat" field="avg_fat" string="Avg Fat %"/>
                </group>
            </dashboard>
        </field>
    </record>

    <record id="action_production_dashboard" model="ir.actions.client">
        <field name="name">Production Dashboard</field>
        <field name="tag">farm_analytics.production_dashboard</field>
        <field name="context">{'dashboard_type': 'production'}</field>
    </record>

    <menuitem id="menu_production_dashboard"
              name="Production Analytics"
              parent="farm_base.menu_farm_analytics"
              action="action_production_dashboard"
              sequence="10"/>
</odoo>
```

**Task 2: Flutter Production Dashboard (3h)**
```dart
// lib/features/analytics/presentation/screens/production_dashboard_screen.dart
import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import '../bloc/production_analytics_bloc.dart';

class ProductionDashboardScreen extends StatelessWidget {
  const ProductionDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ProductionAnalyticsBloc, ProductionAnalyticsState>(
      builder: (context, state) {
        if (state is ProductionAnalyticsLoading) {
          return const Center(child: CircularProgressIndicator());
        }

        if (state is ProductionAnalyticsLoaded) {
          return RefreshIndicator(
            onRefresh: () async {
              context.read<ProductionAnalyticsBloc>().add(LoadProductionData());
            },
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // KPI Cards Row
                  Row(
                    children: [
                      Expanded(
                        child: _KPICard(
                          title: 'Today\'s Yield',
                          value: '${state.summary.totalYield.toStringAsFixed(0)} L',
                          icon: Icons.water_drop,
                          color: Colors.blue,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _KPICard(
                          title: 'Avg/Animal',
                          value: '${state.summary.avgYield.toStringAsFixed(1)} L',
                          icon: Icons.pets,
                          color: Colors.green,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: _KPICard(
                          title: 'Avg Fat %',
                          value: '${state.summary.avgFat.toStringAsFixed(2)}%',
                          icon: Icons.opacity,
                          color: Colors.orange,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _KPICard(
                          title: 'Avg Protein %',
                          value: '${state.summary.avgProtein.toStringAsFixed(2)}%',
                          icon: Icons.science,
                          color: Colors.purple,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),
                  // Trend Chart
                  const Text('Production Trend', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  SizedBox(
                    height: 200,
                    child: LineChart(
                      LineChartData(
                        lineBarsData: [
                          LineChartBarData(
                            spots: state.trends.asMap().entries.map((e) =>
                              FlSpot(e.key.toDouble(), e.value.yield)
                            ).toList(),
                            isCurved: true,
                            color: Colors.blue,
                            barWidth: 3,
                            belowBarData: BarAreaData(
                              show: true,
                              color: Colors.blue.withOpacity(0.2),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          );
        }

        return const Center(child: Text('Error loading analytics'));
      },
    );
  }
}

class _KPICard extends StatelessWidget {
  final String title;
  final String value;
  final IconData icon;
  final Color color;

  const _KPICard({
    required this.title,
    required this.value,
    required this.icon,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, color: color, size: 20),
                const SizedBox(width: 8),
                Text(title, style: TextStyle(color: Colors.grey[600], fontSize: 12)),
              ],
            ),
            const SizedBox(height: 8),
            Text(value, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}
```

#### Day 302 Deliverables
- [x] Production KPI calculations
- [x] Production analytics API
- [x] Production trend charts
- [x] Top performers widget
- [x] Production dashboard (web & mobile)

---

### Days 303-310: Summary

#### Day 303: Health Analytics Dashboard
- Disease incidence rate tracking
- Vaccination compliance metrics
- Treatment success rates
- Health alert summary

#### Day 304: Breeding Analytics Dashboard
- Conception rate calculations
- Calving interval metrics
- Heat detection efficiency
- Bull performance comparison

#### Day 305: Feed Efficiency Dashboard
- Feed conversion ratio (FCR)
- Cost per liter of milk
- Feed cost trends
- Ration performance comparison

#### Day 306: Herd Composition Analytics
- Age distribution charts
- Lactation stage breakdown
- Breed composition
- Status distribution

#### Day 307: Benchmarking & Comparison
- Farm-to-farm comparison
- Historical benchmarking
- Industry standard comparison
- Goal tracking

#### Day 308: Alert Dashboard & Notifications
- Alert aggregation dashboard
- Priority-based display
- Alert trends analysis
- Notification configuration

#### Day 309: Custom Dashboard Builder
- Widget library (15+ widgets)
- Drag-and-drop layout
- Dashboard save/load
- User preferences

#### Day 310: Testing & Documentation
- Unit tests for all KPI calculations
- Integration tests for APIs
- Performance testing
- User documentation

---

## 4. Technical Specifications

### 4.1 Database Views for Analytics

```sql
-- Materialized view for daily production summary
CREATE MATERIALIZED VIEW mv_daily_production_summary AS
SELECT
    production_date,
    farm_id,
    COUNT(DISTINCT animal_id) as animals_milked,
    SUM(yield_liters) as total_yield,
    AVG(yield_liters) as avg_yield,
    AVG(fat_percentage) as avg_fat,
    AVG(protein_percentage) as avg_protein,
    AVG(somatic_cell_count) as avg_scc
FROM farm_milk_production p
JOIN farm_animal a ON p.animal_id = a.id
GROUP BY production_date, farm_id;

CREATE UNIQUE INDEX idx_mv_production_date_farm
ON mv_daily_production_summary(production_date, farm_id);

-- Refresh function
CREATE OR REPLACE FUNCTION refresh_production_summary()
RETURNS void AS $$
BEGIN
    REFRESH MATERIALIZED VIEW CONCURRENTLY mv_daily_production_summary;
END;
$$ LANGUAGE plpgsql;
```

### 4.2 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/analytics/kpis` | GET | Get all KPIs |
| `/api/v1/analytics/production/summary` | GET | Production summary |
| `/api/v1/analytics/production/trends` | GET | Production trends |
| `/api/v1/analytics/health/summary` | GET | Health metrics |
| `/api/v1/analytics/breeding/summary` | GET | Breeding performance |
| `/api/v1/analytics/feed/efficiency` | GET | Feed efficiency |
| `/api/v1/analytics/dashboard/<id>` | GET | Custom dashboard |
| `/farm/analytics/subscribe` | SSE | Real-time updates |

---

## 5. Testing & Validation

### 5.1 KPI Accuracy Tests
```python
class TestKPICalculations(TransactionCase):

    def test_total_production_kpi(self):
        # Create test production records
        self.env['farm.milk.production'].create([
            {'animal_id': self.animal1.id, 'production_date': fields.Date.today(), 'yield_liters': 15.5},
            {'animal_id': self.animal2.id, 'production_date': fields.Date.today(), 'yield_liters': 12.3},
        ])

        KPI = self.env['farm.production.kpis']
        result = KPI._calc_total_daily_production(None, 'today')

        self.assertAlmostEqual(result['value'], 27.8, places=1)

    def test_vaccination_compliance(self):
        # Test compliance calculation
        pass
```

---

## 6. Dependencies & Handoffs

### 6.1 Prerequisites
- Milestones 41-45: Data sources for all analytics

### 6.2 Outputs
- Milestone 47: Mobile dashboard widgets
- Milestone 49: Report data sources

---

*Document End - Milestone 46: Farm Analytics Dashboard*
