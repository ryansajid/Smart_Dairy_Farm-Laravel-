# Milestone 103: Sales Analytics

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field                | Detail                                                        |
|----------------------|---------------------------------------------------------------|
| **Document ID**      | SD-IMPL-P11-M103-v1.0                                         |
| **Milestone**        | 103 of 110                                                    |
| **Title**            | Sales Analytics                                               |
| **Phase**            | Phase 11: Commerce — Advanced Analytics                       |
| **Days**             | Days 521–530 (10 working days)                                |
| **Duration**         | 2 weeks                                                       |
| **Developers**       | 3 Full Stack Developers                                       |
| **Total Effort**     | 240 person-hours (3 devs × 10 days × 8 hours)                 |
| **Status**           | Planned                                                       |
| **Version**          | 1.0                                                           |
| **Last Updated**     | 2026-02-04                                                    |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 103 develops comprehensive sales analytics dashboards that provide multi-dimensional visibility into sales performance across channels, products, customers, and territories. The analytics enable sales teams and management to identify trends, optimize strategies, and forecast future performance.

### 1.2 Scope

- Channel performance comparison (B2C, B2B, Wholesale, Retail)
- Product mix and contribution margin analysis
- Geographic sales distribution with map visualization
- Sales trend analysis with seasonal decomposition
- Customer segmentation by purchase behavior
- Sales funnel conversion analytics
- Promotional campaign effectiveness tracking
- Sales team and territory performance scorecards
- Sales forecasting with time-series models

### 1.3 Key Outcomes

By the end of Milestone 103:
- 5 sales analytics dashboards deployed
- Channel performance comparison operational
- Geographic sales mapping functional
- Sales forecasting model producing predictions
- Sales team can access and use dashboards

---

## 2. Objectives

| # | Objective                                      | Priority   | Measurable Target                        |
|---|------------------------------------------------|------------|------------------------------------------|
| 1 | Create Channel Performance Dashboard           | Critical   | All 4 channels compared                  |
| 2 | Implement Product Analytics                    | High       | ABC analysis, contribution margins       |
| 3 | Build Geographic Sales Map                     | High       | District-level visualization             |
| 4 | Create Sales Trend Analysis                    | High       | 12-month trend with seasonality          |
| 5 | Implement Customer Segmentation View           | High       | Segment-based sales breakdown            |
| 6 | Build Sales Funnel Analytics                   | Medium     | Conversion rates at each stage           |
| 7 | Create Campaign Effectiveness Analysis         | Medium     | ROI tracking for promotions              |
| 8 | Implement Sales Team Scorecards                | Medium     | Rep-level performance metrics            |
| 9 | Develop Sales Forecasting Model                | High       | 3-month forward predictions              |
| 10| Integrate with existing executive dashboards   | Medium     | Drill-down from CEO dashboard            |

---

## 3. Key Deliverables

| # | Deliverable                              | Description                                                | Owner  | Priority |
|---|------------------------------------------|------------------------------------------------------------|--------|----------|
| 1 | Channel Performance Dashboard            | B2C/B2B/Wholesale/Retail comparison                        | Dev 1  | Critical |
| 2 | Product Analytics Dashboard              | ABC analysis, product contribution, margins                | Dev 1  | High     |
| 3 | Geographic Sales Map                     | District and division level sales distribution             | Dev 3  | High     |
| 4 | Sales Trend Dashboard                    | Time-series analysis, seasonality, trends                  | Dev 1  | High     |
| 5 | Customer Segment Analysis                | Sales by customer tier and segment                         | Dev 2  | High     |
| 6 | Sales Funnel Dashboard                   | Lead to order conversion analytics                         | Dev 2  | Medium   |
| 7 | Campaign ROI Dashboard                   | Promotion effectiveness, discount impact                   | Dev 2  | Medium   |
| 8 | Sales Team Scorecards                    | Rep performance, quota attainment                          | Dev 1  | Medium   |
| 9 | Sales Forecast Charts                    | Predictive analytics with confidence intervals             | Dev 1  | High     |
| 10| Sales Analytics Documentation            | User guide and methodology docs                            | All    | Medium   |

---

## 4. Requirement Traceability Matrix

| Req ID       | Source     | Requirement Description                          | Day(s) | Task Reference              |
|--------------|------------|--------------------------------------------------|--------|------------------------------|
| ANAL-003     | RFP        | Sales Performance Analytics                      | 1-10   | Entire milestone             |
| FR-BI-002    | BRD        | Sales performance tracking                       | 1-4    | Channel and product analytics|
| SRS-BI-002   | SRS        | Query response < 500ms                           | 9-10   | Query optimization           |
| B-004 §2     | Impl Guide | Sales reporting requirements                     | 1-6    | Dashboard development        |

---

## 5. Day-by-Day Development Plan

---

### Day 521: Channel Performance Dashboard - Part 1

**Objective:** Create channel performance comparison analytics with revenue breakdown.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Create channel revenue queries                    | Channel revenue SQL                            |
| 2-4   | Create channel growth comparison queries          | Growth analysis queries                        |
| 4-6   | Create channel customer metrics queries           | Customer per channel queries                   |
| 6-8   | Create channel profitability queries              | Margin by channel queries                      |

**Channel Analytics Queries:**
```sql
-- Channel Performance Comparison
SELECT
    ch.channel_name,
    ch.channel_type,
    COUNT(DISTINCT f.order_id) AS order_count,
    COUNT(DISTINCT f.customer_key) AS customer_count,
    SUM(f.quantity) AS units_sold,
    SUM(f.gross_amount) AS gross_revenue,
    SUM(f.discount_amount) AS total_discounts,
    SUM(f.net_amount) AS net_revenue,
    SUM(f.profit_amount) AS gross_profit,
    AVG(f.profit_margin_percent) AS avg_margin,
    SUM(f.net_amount) / NULLIF(COUNT(DISTINCT f.order_id), 0) AS avg_order_value,
    SUM(f.net_amount) / NULLIF(COUNT(DISTINCT f.customer_key), 0) AS revenue_per_customer
FROM fact_sales f
JOIN dim_channel ch ON f.channel_key = ch.channel_key
JOIN dim_date d ON f.date_key = d.date_key
WHERE d.full_date >= CURRENT_DATE - INTERVAL '30 days'
  AND f.is_returned = FALSE
GROUP BY ch.channel_name, ch.channel_type
ORDER BY net_revenue DESC;

-- Channel Growth Trend
WITH monthly_channel AS (
    SELECT
        ch.channel_name,
        d.year,
        d.month,
        SUM(f.net_amount) AS monthly_revenue
    FROM fact_sales f
    JOIN dim_channel ch ON f.channel_key = ch.channel_key
    JOIN dim_date d ON f.date_key = d.date_key
    WHERE d.full_date >= CURRENT_DATE - INTERVAL '12 months'
      AND f.is_returned = FALSE
    GROUP BY ch.channel_name, d.year, d.month
)
SELECT
    channel_name,
    year,
    month,
    monthly_revenue,
    LAG(monthly_revenue) OVER (PARTITION BY channel_name ORDER BY year, month) AS prev_month_revenue,
    (monthly_revenue - LAG(monthly_revenue) OVER (PARTITION BY channel_name ORDER BY year, month)) /
        NULLIF(LAG(monthly_revenue) OVER (PARTITION BY channel_name ORDER BY year, month), 0) * 100 AS mom_growth
FROM monthly_channel
ORDER BY channel_name, year, month;
```

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create order volume queries                       | Order metrics queries                          |
| 4-8   | Create basket analysis queries                    | Basket size and composition                    |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Design channel dashboard layout                   | Dashboard wireframe                            |
| 2-4   | Create channel comparison bar chart               | Revenue comparison chart                       |
| 4-6   | Create channel trend line chart                   | Trend visualization                            |
| 6-8   | Add channel KPI cards                             | Performance indicators                         |

---

### Day 522: Channel Performance Dashboard - Part 2

**Objective:** Complete channel analytics with detailed breakdowns.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create channel product mix queries                | Product by channel analysis                    |
| 4-8   | Create channel customer acquisition queries       | New vs returning by channel                    |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create channel time analysis queries              | Hourly/daily patterns by channel               |
| 4-8   | Set up channel dashboard filters                  | Interactive filters                            |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Create channel pie chart                          | Revenue share visualization                    |
| 2-4   | Create channel heatmap                            | Time-based heatmap                             |
| 4-6   | Add cross-channel filters                         | Filter components                              |
| 6-8   | Test channel dashboard                            | Testing results                                |

---

### Day 523: Product Analytics Dashboard

**Objective:** Create product performance analytics with ABC classification and margin analysis.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Create ABC classification queries                 | ABC analysis SQL                               |
| 2-4   | Create product contribution margin queries        | Margin calculation queries                     |
| 4-6   | Create product trend queries                      | Product performance trends                     |
| 6-8   | Create product comparison queries                 | Product vs product analysis                    |

**Product Analytics Queries:**
```sql
-- ABC Classification
WITH product_revenue AS (
    SELECT
        p.product_key,
        p.product_name,
        p.product_category,
        SUM(f.net_amount) AS revenue,
        SUM(f.quantity) AS units_sold,
        SUM(f.profit_amount) AS profit
    FROM fact_sales f
    JOIN dim_product p ON f.product_key = p.product_key
    JOIN dim_date d ON f.date_key = d.date_key
    WHERE d.full_date >= CURRENT_DATE - INTERVAL '12 months'
      AND f.is_returned = FALSE
    GROUP BY p.product_key, p.product_name, p.product_category
),
ranked_products AS (
    SELECT
        *,
        SUM(revenue) OVER () AS total_revenue,
        SUM(revenue) OVER (ORDER BY revenue DESC ROWS UNBOUNDED PRECEDING) AS cumulative_revenue
    FROM product_revenue
)
SELECT
    *,
    cumulative_revenue / total_revenue * 100 AS cumulative_pct,
    CASE
        WHEN cumulative_revenue / total_revenue <= 0.80 THEN 'A'
        WHEN cumulative_revenue / total_revenue <= 0.95 THEN 'B'
        ELSE 'C'
    END AS abc_class
FROM ranked_products
ORDER BY revenue DESC;

-- Product Contribution Margin
SELECT
    p.product_name,
    p.product_category,
    SUM(f.net_amount) AS revenue,
    SUM(f.cost_amount) AS cost,
    SUM(f.profit_amount) AS contribution_margin,
    SUM(f.profit_amount) / NULLIF(SUM(f.net_amount), 0) * 100 AS margin_pct,
    SUM(f.net_amount) / NULLIF((SELECT SUM(net_amount) FROM fact_sales WHERE is_returned = FALSE), 0) * 100 AS revenue_share
FROM fact_sales f
JOIN dim_product p ON f.product_key = p.product_key
JOIN dim_date d ON f.date_key = d.date_key
WHERE d.full_date >= CURRENT_DATE - INTERVAL '30 days'
  AND f.is_returned = FALSE
GROUP BY p.product_name, p.product_category
ORDER BY contribution_margin DESC;
```

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create cross-sell analysis queries                | Product affinity queries                       |
| 4-8   | Create product lifecycle queries                  | New/growth/mature/decline                      |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Design product dashboard layout                   | Dashboard wireframe                            |
| 2-4   | Create ABC classification chart                   | Pareto visualization                           |
| 4-6   | Create product margin scatter plot                | Margin vs volume plot                          |
| 6-8   | Create product ranking table                      | Top products table                             |

---

### Day 524: Geographic Sales Analytics

**Objective:** Create geographic sales distribution with map visualization.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create geographic aggregation queries             | District/division sales                        |
| 4-8   | Create geographic growth queries                  | Regional growth trends                         |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Configure Bangladesh GeoJSON                      | Map data configuration                         |
| 4-8   | Create regional comparison queries                | Region vs region analysis                      |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-3   | Create choropleth map                             | Geographic heat map                            |
| 3-6   | Create regional breakdown charts                  | Division/district charts                       |
| 6-8   | Add map interactivity                             | Click-through to details                       |

---

### Day 525: Sales Trend Analysis

**Objective:** Create time-series sales analysis with seasonality detection.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create seasonal decomposition queries             | Trend/seasonal/residual                        |
| 4-8   | Create year-over-year comparison queries          | YoY analysis                                   |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create moving average queries                     | Rolling averages                               |
| 4-8   | Create anomaly detection queries                  | Outlier identification                         |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-3   | Create multi-line trend chart                     | Trend visualization                            |
| 3-6   | Create seasonal pattern chart                     | Seasonality chart                              |
| 6-8   | Add trend indicators                              | Trend direction arrows                         |

---

### Day 526: Sales Funnel Analytics

**Objective:** Create sales funnel analytics with conversion tracking.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create funnel stage queries                       | Stage-by-stage metrics                         |
| 4-8   | Create conversion rate queries                    | Stage conversion rates                         |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create funnel velocity queries                    | Time in each stage                             |
| 4-8   | Create drop-off analysis queries                  | Loss point analysis                            |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create funnel visualization                       | Funnel chart                                   |
| 4-8   | Create conversion waterfall                       | Waterfall chart                                |

---

### Day 527: Campaign Effectiveness Analytics

**Objective:** Create promotional campaign effectiveness tracking.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create campaign ROI queries                       | Campaign return analysis                       |
| 4-8   | Create discount effectiveness queries             | Discount impact analysis                       |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create campaign comparison queries                | Campaign vs campaign                           |
| 4-8   | Create A/B test analysis queries                  | Test result queries                            |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create campaign dashboard                         | Campaign performance view                      |
| 4-8   | Create ROI visualization                          | ROI charts                                     |

---

### Day 528: Sales Team Scorecards

**Objective:** Create sales rep and territory performance scorecards.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create rep performance queries                    | Individual rep metrics                         |
| 4-8   | Create quota attainment queries                   | Target vs actual                               |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create territory performance queries              | Territory comparison                           |
| 4-8   | Create leaderboard queries                        | Rankings                                       |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create scorecard dashboard                        | Rep performance view                           |
| 4-8   | Create leaderboard visualization                  | Rankings display                               |

---

### Day 529: Sales Forecasting

**Objective:** Implement sales forecasting with predictive models.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create time-series forecasting model              | ARIMA/Prophet model                            |
| 4-8   | Create forecast query integration                 | Forecast in SQL                                |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create forecast accuracy tracking                 | Accuracy metrics                               |
| 4-8   | Create scenario modeling                          | What-if analysis                               |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create forecast chart                             | Prediction visualization                       |
| 4-8   | Add confidence intervals                          | Uncertainty bands                              |

---

### Day 530: Testing & Documentation

**Objective:** Complete testing and documentation for sales analytics.

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Validate query accuracy                           | Accuracy report                                |
| 2-4   | Optimize slow queries                             | Performance tuning                             |
| 4-6   | Create technical documentation                    | SQL documentation                              |
| 6-8   | Review and sign-off                               | Milestone approval                             |

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Test dashboard interactions                       | Integration tests                              |
| 2-4   | Test filter functionality                         | Filter testing                                 |
| 4-6   | Create user documentation                         | User guide                                     |
| 6-8   | Demo to sales team                                | Demo completion                                |

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | UI polish and refinement                          | Final UI                                       |
| 2-4   | Mobile responsiveness testing                     | Mobile tests                                   |
| 4-6   | Create visualization guide                        | Chart documentation                            |
| 6-8   | Prepare handoff to Milestone 104                  | Handoff document                               |

---

## 6. Technical Specifications

### 6.1 Dashboard Architecture

| Dashboard                  | Charts                                       | Filters                    |
|----------------------------|----------------------------------------------|----------------------------|
| Channel Performance        | Bar, Line, Pie, Heatmap                      | Date, Channel, Product     |
| Product Analytics          | Pareto, Scatter, Table, Treemap              | Date, Category, Brand      |
| Geographic Sales           | Choropleth, Bar, Table                       | Date, Division, District   |
| Sales Trends               | Multi-line, Area, Decomposition              | Date range, Granularity    |
| Sales Funnel               | Funnel, Waterfall, Sankey                    | Date, Channel, Segment     |

### 6.2 Key Metrics Definitions

| Metric                     | Formula                                      | Unit         |
|----------------------------|----------------------------------------------|--------------|
| Net Revenue                | Gross - Discounts - Returns                  | BDT          |
| Contribution Margin        | Revenue - Variable Costs                     | BDT          |
| Conversion Rate            | Orders / Leads × 100                         | %            |
| Average Order Value        | Total Revenue / Order Count                  | BDT          |
| Customer Acquisition Cost  | Marketing Spend / New Customers              | BDT          |
| Revenue per Customer       | Total Revenue / Active Customers             | BDT          |

---

## 7. Testing & Validation

### 7.1 UAT Criteria

| Criterion                  | Acceptance Criteria                               |
|----------------------------|---------------------------------------------------|
| Channel data accuracy      | Revenue matches Odoo sales reports                |
| Geographic coverage        | All 64 districts represented                      |
| Forecast accuracy          | MAPE < 15% on historical data                     |
| Query performance          | All queries < 500ms                               |
| Sales team approval        | Sign-off from Sales Manager                       |

---

## 8. Dependencies & Handoffs

### 8.1 Prerequisites

| Dependency                        | Source      |
|-----------------------------------|-------------|
| BI Platform configured            | MS-101      |
| Executive dashboards              | MS-102      |
| Sales data in data warehouse      | MS-101 ETL  |
| Geographic data configured        | MS-101      |

### 8.2 Deliverables to Next Milestone

| Deliverable                       | Recipient   |
|-----------------------------------|-------------|
| Channel analysis patterns         | MS-104-108  |
| Forecasting methodology           | MS-104-108  |
| Geographic visualization          | MS-104-108  |

---

**END OF MILESTONE 103 DOCUMENT**

*Version 1.0 — 2026-02-04*
