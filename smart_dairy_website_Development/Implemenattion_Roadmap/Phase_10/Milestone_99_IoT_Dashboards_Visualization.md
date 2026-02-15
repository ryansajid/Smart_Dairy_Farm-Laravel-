# Milestone 99: IoT Dashboards & Visualization

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P10-M99-v1.0 |
| **Milestone** | 99 of 100 |
| **Phase** | Phase 10: Commerce - IoT Integration Core |
| **Days** | 731-740 (10 working days) |
| **Duration** | 2 weeks |
| **Developers** | 3 Full Stack Developers |
| **Total Effort** | 240 person-hours |
| **Status** | Planned |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-04 |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 99 implements comprehensive real-time IoT visualization dashboards for the Smart Dairy platform. This milestone creates the visual layer that transforms raw IoT data into actionable insights through interactive dashboards, live data streams, and customizable widgets for farm operations management.

### 1.2 Scope

This milestone covers:
- Real-time dashboard framework (OWL + WebSocket)
- Milk production live view
- Environmental monitoring dashboard
- Animal health overview
- Feed efficiency dashboard
- Device status board
- Custom widget builder
- Report generator (PDF/Excel)
- Mobile dashboard (Flutter)
- Grafana integration

### 1.3 Key Outcomes

| Outcome | Success Measure |
|---------|-----------------|
| Dashboard Load | <2 seconds |
| Real-time Refresh | <5 second data lag |
| Widget Library | 20+ widget types |
| Mobile Support | Full feature parity |
| Report Generation | <10 seconds for daily |
| Customization | Drag-and-drop layout |

---

## 2. Objectives

| # | Objective | Priority | Measurable Target |
|---|-----------|----------|-------------------|
| O1 | Build real-time dashboard framework | Critical | <5 second data refresh |
| O2 | Create milk production live view | Critical | Real-time milking display |
| O3 | Develop environmental monitoring dashboard | High | Multi-zone visualization |
| O4 | Implement animal health overview | High | Health scores + alerts |
| O5 | Build feed efficiency dashboard | High | FCR and cost metrics |
| O6 | Create device status board | High | All device types |
| O7 | Develop custom widget builder | Medium | Drag-and-drop |
| O8 | Implement report generator | High | PDF/Excel export |

---

## 3. Key Deliverables

| # | Deliverable | Description | Owner | Priority |
|---|-------------|-------------|-------|----------|
| D1 | Dashboard Framework | OWL real-time base | Dev 3 | Critical |
| D2 | Milk Production Dashboard | Live milking view | Dev 3 | Critical |
| D3 | Environmental Dashboard | Temperature/humidity | Dev 3 | High |
| D4 | Animal Health Dashboard | Health metrics | Dev 3 | High |
| D5 | Feed Dashboard | Consumption/efficiency | Dev 3 | High |
| D6 | Device Status Board | Device health | Dev 3 | High |
| D7 | Widget Builder | Custom widgets | Dev 3 | Medium |
| D8 | Dashboard Data APIs | Backend APIs | Dev 1 | Critical |
| D9 | WebSocket Data Service | Real-time streaming | Dev 2 | Critical |
| D10 | Report Generator | PDF/Excel export | Dev 2 | High |
| D11 | Mobile Dashboard | Flutter dashboards | Dev 3 | High |
| D12 | Grafana Integration | External dashboards | Dev 2 | Medium |

---

## 4. Day-by-Day Development Plan

### Day 731: Dashboard Framework & Architecture

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design dashboard data API architecture | API design |
| 3-6 | Implement dashboard configuration model | Config model |
| 6-8 | Create widget definition model | Widget model |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design WebSocket data streaming | Stream architecture |
| 3-6 | Implement dashboard data aggregator | Aggregator service |
| 6-8 | Create real-time data cache layer | Cache layer |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design dashboard component architecture | Component design |
| 2-5 | Create base dashboard OWL component | Base component |
| 5-8 | Implement widget container system | Widget container |

---

### Day 732: Widget Library Foundation

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement widget data endpoints | Widget APIs |
| 3-5 | Create widget configuration schema | Config schema |
| 5-8 | Build widget permission system | Permissions |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement widget data providers | Data providers |
| 3-5 | Create widget refresh scheduler | Refresh scheduler |
| 5-8 | Build widget caching service | Widget cache |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create gauge widget component | Gauge widget |
| 3-5 | Implement line chart widget | Line chart |
| 5-8 | Build stat card widget | Stat card |

---

### Day 733: Milk Production Dashboard

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create milk production summary API | Summary API |
| 3-5 | Implement live session tracking API | Session API |
| 5-8 | Build production comparison API | Comparison API |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement WebSocket milk data stream | Milk stream |
| 3-5 | Create session aggregation service | Aggregation |
| 5-8 | Build production trend calculator | Trends |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create milk production dashboard | Main dashboard |
| 3-5 | Implement live milking monitor | Live monitor |
| 5-8 | Build production charts | Production charts |

---

### Day 734: Environmental Monitoring Dashboard

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create environmental data APIs | Environment APIs |
| 3-5 | Implement zone summary API | Zone API |
| 5-8 | Build compliance status API | Compliance API |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement temperature stream | Temp stream |
| 3-5 | Create alert overlay service | Alert overlay |
| 5-8 | Build zone map data provider | Zone map data |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create environmental dashboard | Main dashboard |
| 3-5 | Implement zone heat map | Heat map |
| 5-8 | Build temperature trend charts | Temp charts |

---

### Day 735: Animal Health Dashboard

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create animal health APIs | Health APIs |
| 3-5 | Implement health score summary | Score summary |
| 5-8 | Build alert summary API | Alert API |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement health data aggregator | Health aggregator |
| 3-5 | Create heat detection stream | Heat stream |
| 5-8 | Build activity comparison service | Activity compare |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create animal health dashboard | Health dashboard |
| 3-5 | Implement health score cards | Score cards |
| 5-8 | Build heat calendar view | Heat calendar |

---

### Day 736: Feed Efficiency Dashboard

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create feed efficiency APIs | Efficiency APIs |
| 3-5 | Implement FCR calculation API | FCR API |
| 5-8 | Build cost analysis API | Cost API |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement consumption aggregator | Consumption agg |
| 3-5 | Create efficiency trend service | Efficiency trends |
| 5-8 | Build cost breakdown calculator | Cost calculator |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create feed efficiency dashboard | Feed dashboard |
| 3-5 | Implement FCR comparison chart | FCR chart |
| 5-8 | Build cost breakdown view | Cost view |

---

### Day 737: Device Status & Custom Widgets

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create device status APIs | Device APIs |
| 3-5 | Implement device health aggregator | Health aggregator |
| 5-8 | Build custom widget data API | Widget data API |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement device status stream | Device stream |
| 3-5 | Create widget configuration service | Widget config |
| 5-8 | Build widget layout service | Layout service |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create device status board | Device board |
| 3-5 | Implement widget builder UI | Widget builder |
| 5-8 | Build drag-and-drop layout | DnD layout |

---

### Day 738: Report Generator & Mobile

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create report generation service | Report service |
| 3-5 | Implement PDF template engine | PDF templates |
| 5-8 | Build Excel export service | Excel export |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement scheduled reports | Scheduled reports |
| 3-5 | Create report storage service | Report storage |
| 5-8 | Build Grafana data source | Grafana DS |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create Flutter dashboard screens | Mobile dashboards |
| 3-5 | Implement mobile widget components | Mobile widgets |
| 5-8 | Build report download UI | Download UI |

---

### Day 739: Testing & Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API unit tests | API tests |
| 3-5 | Create integration tests | Integration tests |
| 5-8 | Performance testing | Performance tests |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Test WebSocket streams | Stream tests |
| 3-5 | Test report generation | Report tests |
| 5-8 | Load testing | Load tests |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Component testing | Component tests |
| 3-5 | Dashboard load testing | Dashboard tests |
| 5-8 | Mobile testing | Mobile tests |

---

### Day 740: Documentation & Demo

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API documentation | API docs |
| 3-5 | Create widget development guide | Widget guide |
| 5-8 | Bug fixes | Stable release |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write integration guide | Integration guide |
| 3-5 | Create Grafana setup guide | Grafana guide |
| 5-8 | Prepare demo environment | Demo setup |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write dashboard user guide | User guide |
| 3-5 | Create demo scenarios | Demo materials |
| 5-8 | Conduct milestone demo | Demo presentation |

---

## 5. Technical Specifications

### 5.1 Dashboard Configuration Model

```python
# smart_dairy_iot/models/iot_dashboard.py

from odoo import models, fields, api
import json

class IoTDashboard(models.Model):
    _name = 'iot.dashboard'
    _description = 'IoT Dashboard Configuration'
    _order = 'sequence, name'

    name = fields.Char(string='Dashboard Name', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(default=True)

    # Access Control
    farm_id = fields.Many2one(
        'res.partner',
        string='Farm',
        domain=[('is_farm', '=', True)]
    )
    user_ids = fields.Many2many(
        'res.users',
        string='Allowed Users'
    )
    is_public = fields.Boolean(
        string='Public Dashboard',
        default=False
    )

    # Layout
    dashboard_type = fields.Selection([
        ('grid', 'Grid Layout'),
        ('freeform', 'Freeform Layout'),
        ('tabs', 'Tabbed Layout'),
    ], string='Layout Type', default='grid')
    columns = fields.Integer(string='Grid Columns', default=12)
    row_height = fields.Integer(string='Row Height (px)', default=100)

    # Widgets
    widget_ids = fields.One2many(
        'iot.dashboard.widget',
        'dashboard_id',
        string='Widgets'
    )

    # Refresh Settings
    auto_refresh = fields.Boolean(string='Auto Refresh', default=True)
    refresh_interval = fields.Integer(
        string='Refresh Interval (seconds)',
        default=30
    )

    # Theme
    theme = fields.Selection([
        ('light', 'Light'),
        ('dark', 'Dark'),
        ('auto', 'Auto (System)'),
    ], string='Theme', default='light')
    primary_color = fields.Char(string='Primary Color', default='#4472C4')

    # Statistics
    view_count = fields.Integer(string='View Count', readonly=True)
    last_viewed = fields.Datetime(string='Last Viewed', readonly=True)

    def get_dashboard_data(self):
        """Get all data for dashboard rendering"""
        self.ensure_one()

        return {
            'id': self.id,
            'name': self.name,
            'dashboard_type': self.dashboard_type,
            'columns': self.columns,
            'row_height': self.row_height,
            'auto_refresh': self.auto_refresh,
            'refresh_interval': self.refresh_interval,
            'theme': self.theme,
            'primary_color': self.primary_color,
            'widgets': [w.get_widget_data() for w in self.widget_ids],
        }


class IoTDashboardWidget(models.Model):
    _name = 'iot.dashboard.widget'
    _description = 'Dashboard Widget'
    _order = 'sequence'

    dashboard_id = fields.Many2one(
        'iot.dashboard',
        string='Dashboard',
        required=True,
        ondelete='cascade'
    )
    name = fields.Char(string='Widget Title')
    sequence = fields.Integer(string='Sequence', default=10)

    # Widget Type
    widget_type = fields.Selection([
        ('gauge', 'Gauge'),
        ('line_chart', 'Line Chart'),
        ('bar_chart', 'Bar Chart'),
        ('pie_chart', 'Pie Chart'),
        ('stat_card', 'Stat Card'),
        ('table', 'Data Table'),
        ('map', 'Map View'),
        ('heatmap', 'Heat Map'),
        ('alert_list', 'Alert List'),
        ('device_status', 'Device Status'),
        ('animal_list', 'Animal List'),
        ('production_summary', 'Production Summary'),
        ('custom', 'Custom Widget'),
    ], string='Widget Type', required=True)

    # Position (Grid Layout)
    grid_x = fields.Integer(string='Grid X', default=0)
    grid_y = fields.Integer(string='Grid Y', default=0)
    grid_width = fields.Integer(string='Width (columns)', default=4)
    grid_height = fields.Integer(string='Height (rows)', default=3)

    # Data Source
    data_source = fields.Selection([
        ('milk_production', 'Milk Production'),
        ('temperature', 'Temperature'),
        ('activity', 'Animal Activity'),
        ('feed', 'Feed Consumption'),
        ('device_status', 'Device Status'),
        ('alerts', 'Alerts'),
        ('custom', 'Custom Query'),
    ], string='Data Source')
    query_config = fields.Text(
        string='Query Configuration (JSON)',
        help='Custom query parameters'
    )

    # Filters
    farm_id = fields.Many2one('res.partner', string='Farm Filter')
    device_type = fields.Char(string='Device Type Filter')
    metric_name = fields.Char(string='Metric Filter')
    time_range = fields.Selection([
        ('1h', 'Last 1 Hour'),
        ('6h', 'Last 6 Hours'),
        ('24h', 'Last 24 Hours'),
        ('7d', 'Last 7 Days'),
        ('30d', 'Last 30 Days'),
        ('custom', 'Custom Range'),
    ], string='Time Range', default='24h')

    # Display Options
    show_title = fields.Boolean(string='Show Title', default=True)
    show_legend = fields.Boolean(string='Show Legend', default=True)
    show_toolbar = fields.Boolean(string='Show Toolbar', default=True)
    color_scheme = fields.Char(string='Color Scheme', default='default')

    # Thresholds
    warning_threshold = fields.Float(string='Warning Threshold')
    critical_threshold = fields.Float(string='Critical Threshold')
    show_thresholds = fields.Boolean(string='Show Thresholds', default=True)

    def get_widget_data(self):
        """Get widget configuration and data"""
        self.ensure_one()

        config = {
            'id': self.id,
            'name': self.name,
            'widget_type': self.widget_type,
            'grid_x': self.grid_x,
            'grid_y': self.grid_y,
            'grid_width': self.grid_width,
            'grid_height': self.grid_height,
            'data_source': self.data_source,
            'time_range': self.time_range,
            'show_title': self.show_title,
            'show_legend': self.show_legend,
            'warning_threshold': self.warning_threshold,
            'critical_threshold': self.critical_threshold,
        }

        # Fetch data based on data source
        data = self._fetch_widget_data()
        config['data'] = data

        return config

    def _fetch_widget_data(self):
        """Fetch data for widget based on configuration"""
        if self.data_source == 'milk_production':
            return self._fetch_milk_data()
        elif self.data_source == 'temperature':
            return self._fetch_temperature_data()
        elif self.data_source == 'activity':
            return self._fetch_activity_data()
        elif self.data_source == 'feed':
            return self._fetch_feed_data()
        elif self.data_source == 'device_status':
            return self._fetch_device_status()
        elif self.data_source == 'alerts':
            return self._fetch_alerts()
        elif self.data_source == 'custom':
            return self._fetch_custom_data()
        return {}

    def _fetch_milk_data(self):
        """Fetch milk production data"""
        time_filter = self._get_time_filter()

        self.env.cr.execute("""
            SELECT
                time_bucket('1 hour', time) as bucket,
                SUM(yield_kg) as total_yield,
                COUNT(DISTINCT animal_id) as animals,
                AVG(conductivity_ms) as avg_conductivity
            FROM iot.milk_production_realtime
            WHERE time >= %s
              AND (%s IS NULL OR farm_id = %s)
            GROUP BY bucket
            ORDER BY bucket
        """, (time_filter, self.farm_id.id if self.farm_id else None,
              self.farm_id.id if self.farm_id else None))

        rows = self.env.cr.fetchall()
        return {
            'timestamps': [r[0].isoformat() for r in rows],
            'values': [r[1] for r in rows],
            'animals': [r[2] for r in rows],
            'conductivity': [r[3] for r in rows],
        }

    def _fetch_temperature_data(self):
        """Fetch temperature data"""
        time_filter = self._get_time_filter()

        self.env.cr.execute("""
            SELECT
                time_bucket('15 minutes', time) as bucket,
                AVG(metric_value) as avg_temp,
                MIN(metric_value) as min_temp,
                MAX(metric_value) as max_temp
            FROM iot.sensor_data
            WHERE metric_name = 'temperature'
              AND time >= %s
              AND (%s IS NULL OR farm_id = %s)
            GROUP BY bucket
            ORDER BY bucket
        """, (time_filter, self.farm_id.id if self.farm_id else None,
              self.farm_id.id if self.farm_id else None))

        rows = self.env.cr.fetchall()
        return {
            'timestamps': [r[0].isoformat() for r in rows],
            'avg': [r[1] for r in rows],
            'min': [r[2] for r in rows],
            'max': [r[3] for r in rows],
        }

    def _get_time_filter(self):
        """Get SQL time filter based on time_range"""
        from datetime import datetime, timedelta

        ranges = {
            '1h': timedelta(hours=1),
            '6h': timedelta(hours=6),
            '24h': timedelta(hours=24),
            '7d': timedelta(days=7),
            '30d': timedelta(days=30),
        }
        delta = ranges.get(self.time_range, timedelta(hours=24))
        return datetime.now() - delta
```

### 5.2 Real-time Dashboard Component (OWL)

```javascript
/** @odoo-module **/
// smart_dairy_iot/static/src/components/dashboard/iot_dashboard.js

import { Component, useState, onWillStart, onMounted, onWillUnmount, xml } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { loadJS } from "@web/core/assets";

// Widget Components
import { GaugeWidget } from "./widgets/gauge_widget";
import { LineChartWidget } from "./widgets/line_chart_widget";
import { StatCardWidget } from "./widgets/stat_card_widget";
import { AlertListWidget } from "./widgets/alert_list_widget";
import { DeviceStatusWidget } from "./widgets/device_status_widget";

export class IoTDashboard extends Component {
    static template = "smart_dairy_iot.IoTDashboard";
    static components = {
        GaugeWidget,
        LineChartWidget,
        StatCardWidget,
        AlertListWidget,
        DeviceStatusWidget,
    };
    static props = {
        dashboardId: { type: Number },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            dashboard: null,
            widgets: [],
            isLoading: true,
            isFullscreen: false,
            lastUpdate: null,
            error: null,
        });

        this.websocket = null;
        this.refreshTimer = null;

        onWillStart(async () => {
            await loadJS("/smart_dairy_iot/static/lib/chart.js/chart.min.js");
            await loadJS("/smart_dairy_iot/static/lib/gridstack/gridstack.min.js");
            await this.loadDashboard();
        });

        onMounted(() => {
            this.initializeGrid();
            this.connectWebSocket();
            this.startAutoRefresh();
        });

        onWillUnmount(() => {
            this.disconnectWebSocket();
            this.stopAutoRefresh();
        });
    }

    async loadDashboard() {
        this.state.isLoading = true;
        try {
            const dashboardData = await this.orm.call(
                "iot.dashboard",
                "get_dashboard_data",
                [this.props.dashboardId]
            );
            this.state.dashboard = dashboardData;
            this.state.widgets = dashboardData.widgets;
            this.state.lastUpdate = new Date();
        } catch (error) {
            this.state.error = error.message;
            this.notification.add("Failed to load dashboard", { type: "danger" });
        }
        this.state.isLoading = false;
    }

    initializeGrid() {
        if (this.state.dashboard?.dashboard_type === 'grid') {
            this.grid = GridStack.init({
                column: this.state.dashboard.columns,
                cellHeight: this.state.dashboard.row_height,
                animate: true,
                float: true,
                disableResize: false,
                disableDrag: false,
            });

            this.grid.on('change', (event, items) => {
                this.saveWidgetPositions(items);
            });
        }
    }

    connectWebSocket() {
        const protocol = window.location.protocol === "https:" ? "wss:" : "ws:";
        const wsUrl = `${protocol}//${window.location.host}/ws/dashboard/${this.props.dashboardId}`;

        this.websocket = new WebSocket(wsUrl);

        this.websocket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleRealtimeUpdate(data);
        };

        this.websocket.onerror = (error) => {
            console.error("Dashboard WebSocket error:", error);
        };

        this.websocket.onclose = () => {
            setTimeout(() => this.connectWebSocket(), 5000);
        };
    }

    disconnectWebSocket() {
        if (this.websocket) {
            this.websocket.close();
            this.websocket = null;
        }
    }

    handleRealtimeUpdate(data) {
        if (data.type === 'widget_update') {
            const widget = this.state.widgets.find(w => w.id === data.widget_id);
            if (widget) {
                widget.data = data.data;
                this.state.lastUpdate = new Date();
            }
        } else if (data.type === 'alert') {
            this.showAlertNotification(data.alert);
        }
    }

    showAlertNotification(alert) {
        this.notification.add(alert.message, {
            type: alert.priority === '1' ? 'danger' : 'warning',
            sticky: alert.priority === '1',
        });
    }

    startAutoRefresh() {
        if (this.state.dashboard?.auto_refresh) {
            const interval = (this.state.dashboard.refresh_interval || 30) * 1000;
            this.refreshTimer = setInterval(() => {
                this.refreshWidgets();
            }, interval);
        }
    }

    stopAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
            this.refreshTimer = null;
        }
    }

    async refreshWidgets() {
        for (const widget of this.state.widgets) {
            try {
                const newData = await this.orm.call(
                    "iot.dashboard.widget",
                    "get_widget_data",
                    [widget.id]
                );
                widget.data = newData.data;
            } catch (error) {
                console.error(`Widget ${widget.id} refresh error:`, error);
            }
        }
        this.state.lastUpdate = new Date();
    }

    async saveWidgetPositions(items) {
        for (const item of items) {
            const widgetId = parseInt(item.el.dataset.widgetId);
            await this.orm.write("iot.dashboard.widget", [widgetId], {
                grid_x: item.x,
                grid_y: item.y,
                grid_width: item.w,
                grid_height: item.h,
            });
        }
    }

    toggleFullscreen() {
        this.state.isFullscreen = !this.state.isFullscreen;
        if (this.state.isFullscreen) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    getWidgetComponent(widgetType) {
        const componentMap = {
            'gauge': 'GaugeWidget',
            'line_chart': 'LineChartWidget',
            'stat_card': 'StatCardWidget',
            'alert_list': 'AlertListWidget',
            'device_status': 'DeviceStatusWidget',
        };
        return componentMap[widgetType] || 'StatCardWidget';
    }

    formatLastUpdate() {
        if (!this.state.lastUpdate) return '';
        return this.state.lastUpdate.toLocaleTimeString();
    }
}

// Dashboard Template
IoTDashboard.template = xml`
    <div t-attf-class="iot-dashboard {{ state.isFullscreen ? 'fullscreen' : '' }} theme-{{ state.dashboard?.theme || 'light' }}">
        <t t-if="state.isLoading">
            <div class="dashboard-loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </t>
        <t t-elif="state.error">
            <div class="alert alert-danger m-4">
                <i class="fa fa-exclamation-triangle me-2"/>
                <t t-esc="state.error"/>
            </div>
        </t>
        <t t-else="">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0" t-esc="state.dashboard?.name"/>
                        <small class="text-muted">
                            Last updated: <t t-esc="formatLastUpdate()"/>
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" t-on-click="refreshWidgets">
                            <i class="fa fa-refresh"/>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" t-on-click="toggleFullscreen">
                            <i t-attf-class="fa {{ state.isFullscreen ? 'fa-compress' : 'fa-expand' }}"/>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Widget Grid -->
            <div class="grid-stack">
                <t t-foreach="state.widgets" t-as="widget" t-key="widget.id">
                    <div class="grid-stack-item"
                         t-att-data-widget-id="widget.id"
                         t-att-gs-x="widget.grid_x"
                         t-att-gs-y="widget.grid_y"
                         t-att-gs-w="widget.grid_width"
                         t-att-gs-h="widget.grid_height">
                        <div class="grid-stack-item-content">
                            <div class="widget-wrapper">
                                <t t-if="widget.show_title">
                                    <div class="widget-header">
                                        <span t-esc="widget.name"/>
                                    </div>
                                </t>
                                <div class="widget-body">
                                    <t t-component="getWidgetComponent(widget.widget_type)"
                                       widget="widget"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </t>
            </div>
        </t>
    </div>
`;

registry.category("actions").add("iot_dashboard", IoTDashboard);
```

### 5.3 Report Generator Service

```python
# smart_dairy_iot/services/report_generator.py

from odoo import models, api, fields
from datetime import datetime, timedelta
import io
import xlsxwriter
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4, landscape
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer, Image
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import inch

class IoTReportGenerator(models.AbstractModel):
    _name = 'iot.report.generator'
    _description = 'IoT Report Generator'

    @api.model
    def generate_daily_production_report(self, farm_id, date=None):
        """Generate daily milk production report"""
        if not date:
            date = fields.Date.today() - timedelta(days=1)

        farm = self.env['res.partner'].browse(farm_id)

        # Fetch production data
        self.env.cr.execute("""
            SELECT
                animal_id,
                a.name as animal_name,
                SUM(yield_kg) as total_yield,
                COUNT(*) as milking_count,
                AVG(conductivity_ms) as avg_conductivity,
                AVG(somatic_cell_count) as avg_scc,
                MAX(mastitis_risk_score) as max_mastitis_risk
            FROM iot.milk_production_realtime m
            JOIN farm_animal a ON m.animal_id = a.id
            WHERE m.farm_id = %s
              AND DATE(m.time) = %s
            GROUP BY animal_id, a.name
            ORDER BY total_yield DESC
        """, (farm_id, date))

        production_data = self.env.cr.fetchall()

        # Calculate totals
        total_yield = sum(row[2] for row in production_data)
        total_animals = len(production_data)
        avg_yield = total_yield / total_animals if total_animals else 0

        report_data = {
            'farm_name': farm.name,
            'date': date,
            'generated_at': datetime.now(),
            'total_yield': total_yield,
            'total_animals': total_animals,
            'avg_yield_per_animal': avg_yield,
            'production_details': production_data,
        }

        return report_data

    @api.model
    def export_to_pdf(self, report_type, farm_id, date_from, date_to):
        """Export report to PDF"""
        buffer = io.BytesIO()
        doc = SimpleDocTemplate(
            buffer,
            pagesize=landscape(A4),
            rightMargin=30,
            leftMargin=30,
            topMargin=30,
            bottomMargin=30
        )

        styles = getSampleStyleSheet()
        elements = []

        # Title
        title_style = ParagraphStyle(
            'Title',
            parent=styles['Heading1'],
            fontSize=18,
            spaceAfter=20,
        )
        farm = self.env['res.partner'].browse(farm_id)
        elements.append(Paragraph(f"{farm.name} - IoT Report", title_style))
        elements.append(Paragraph(
            f"Period: {date_from} to {date_to}",
            styles['Normal']
        ))
        elements.append(Spacer(1, 20))

        if report_type == 'production':
            elements.extend(self._build_production_pdf(farm_id, date_from, date_to, styles))
        elif report_type == 'temperature':
            elements.extend(self._build_temperature_pdf(farm_id, date_from, date_to, styles))
        elif report_type == 'feed':
            elements.extend(self._build_feed_pdf(farm_id, date_from, date_to, styles))

        doc.build(elements)
        buffer.seek(0)
        return buffer.read()

    def _build_production_pdf(self, farm_id, date_from, date_to, styles):
        """Build production report PDF content"""
        elements = []

        # Summary section
        elements.append(Paragraph("Production Summary", styles['Heading2']))

        self.env.cr.execute("""
            SELECT
                DATE(time) as date,
                SUM(yield_kg) as total,
                COUNT(DISTINCT animal_id) as animals
            FROM iot.milk_production_realtime
            WHERE farm_id = %s
              AND DATE(time) BETWEEN %s AND %s
            GROUP BY DATE(time)
            ORDER BY date
        """, (farm_id, date_from, date_to))

        summary_data = [['Date', 'Total Yield (kg)', 'Animals']]
        for row in self.env.cr.fetchall():
            summary_data.append([
                str(row[0]),
                f"{row[1]:.2f}",
                str(row[2])
            ])

        summary_table = Table(summary_data)
        summary_table.setStyle(TableStyle([
            ('BACKGROUND', (0, 0), (-1, 0), colors.grey),
            ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
            ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
            ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
            ('FONTSIZE', (0, 0), (-1, 0), 12),
            ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
            ('BACKGROUND', (0, 1), (-1, -1), colors.beige),
            ('GRID', (0, 0), (-1, -1), 1, colors.black)
        ]))
        elements.append(summary_table)

        return elements

    @api.model
    def export_to_excel(self, report_type, farm_id, date_from, date_to):
        """Export report to Excel"""
        output = io.BytesIO()
        workbook = xlsxwriter.Workbook(output)

        # Formats
        header_format = workbook.add_format({
            'bold': True,
            'bg_color': '#4472C4',
            'font_color': 'white',
            'border': 1,
            'align': 'center',
        })
        number_format = workbook.add_format({
            'num_format': '#,##0.00',
            'border': 1,
        })
        date_format = workbook.add_format({
            'num_format': 'yyyy-mm-dd',
            'border': 1,
        })

        if report_type == 'production':
            self._build_production_excel(workbook, farm_id, date_from, date_to,
                                        header_format, number_format, date_format)
        elif report_type == 'temperature':
            self._build_temperature_excel(workbook, farm_id, date_from, date_to,
                                         header_format, number_format, date_format)

        workbook.close()
        output.seek(0)
        return output.read()

    def _build_production_excel(self, workbook, farm_id, date_from, date_to,
                                header_format, number_format, date_format):
        """Build production Excel sheets"""
        # Daily Summary Sheet
        summary_sheet = workbook.add_worksheet('Daily Summary')
        headers = ['Date', 'Total Yield (kg)', 'Animals', 'Avg per Animal']

        for col, header in enumerate(headers):
            summary_sheet.write(0, col, header, header_format)

        self.env.cr.execute("""
            SELECT
                DATE(time) as date,
                SUM(yield_kg) as total,
                COUNT(DISTINCT animal_id) as animals
            FROM iot.milk_production_realtime
            WHERE farm_id = %s
              AND DATE(time) BETWEEN %s AND %s
            GROUP BY DATE(time)
            ORDER BY date
        """, (farm_id, date_from, date_to))

        row = 1
        for data in self.env.cr.fetchall():
            summary_sheet.write(row, 0, str(data[0]), date_format)
            summary_sheet.write(row, 1, data[1], number_format)
            summary_sheet.write(row, 2, data[2])
            summary_sheet.write(row, 3, data[1]/data[2] if data[2] else 0, number_format)
            row += 1

        # Auto-fit columns
        summary_sheet.set_column(0, 0, 12)
        summary_sheet.set_column(1, 3, 15)

        # Animal Detail Sheet
        detail_sheet = workbook.add_worksheet('Animal Details')
        detail_headers = ['Animal', 'Date', 'Yield (kg)', 'Sessions', 'Conductivity', 'SCC']

        for col, header in enumerate(detail_headers):
            detail_sheet.write(0, col, header, header_format)

        self.env.cr.execute("""
            SELECT
                a.name,
                DATE(m.time),
                SUM(m.yield_kg),
                COUNT(*),
                AVG(m.conductivity_ms),
                AVG(m.somatic_cell_count)
            FROM iot.milk_production_realtime m
            JOIN farm_animal a ON m.animal_id = a.id
            WHERE m.farm_id = %s
              AND DATE(m.time) BETWEEN %s AND %s
            GROUP BY a.name, DATE(m.time)
            ORDER BY a.name, DATE(m.time)
        """, (farm_id, date_from, date_to))

        row = 1
        for data in self.env.cr.fetchall():
            detail_sheet.write(row, 0, data[0])
            detail_sheet.write(row, 1, str(data[1]), date_format)
            detail_sheet.write(row, 2, data[2], number_format)
            detail_sheet.write(row, 3, data[3])
            detail_sheet.write(row, 4, data[4] or 0, number_format)
            detail_sheet.write(row, 5, data[5] or 0, number_format)
            row += 1

        detail_sheet.set_column(0, 0, 20)
        detail_sheet.set_column(1, 5, 12)
```

---

## 6. Milestone Sign-off Checklist

| # | Criteria | Owner | Status |
|---|----------|-------|--------|
| 1 | Dashboard framework complete | Dev 3 | ☐ |
| 2 | Milk production dashboard working | Dev 3 | ☐ |
| 3 | Environmental dashboard functional | Dev 3 | ☐ |
| 4 | Animal health dashboard working | Dev 3 | ☐ |
| 5 | Feed efficiency dashboard functional | Dev 3 | ☐ |
| 6 | Device status board working | Dev 3 | ☐ |
| 7 | Widget builder implemented | Dev 3 | ☐ |
| 8 | Backend APIs complete | Dev 1 | ☐ |
| 9 | WebSocket streaming working | Dev 2 | ☐ |
| 10 | Report generator functional | Dev 2 | ☐ |
| 11 | Mobile dashboards working | Dev 3 | ☐ |
| 12 | Unit tests passing (>80%) | All | ☐ |
| 13 | Demo completed successfully | All | ☐ |

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Development Team | Initial creation |

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP System implementation roadmap.*
