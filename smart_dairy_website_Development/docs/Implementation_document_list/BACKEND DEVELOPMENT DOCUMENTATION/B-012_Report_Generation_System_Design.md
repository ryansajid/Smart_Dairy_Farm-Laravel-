# SMART DAIRY LTD.
## REPORT GENERATION SYSTEM DESIGN
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-012 |
| **Version** | 1.0 |
| **Date** | February 22, 2026 |
| **Author** | Tech Lead |
| **Owner** | Backend Team |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Architecture Overview](#2-architecture-overview)
3. [Report Types & Templates](#3-report-types--templates)
4. [Data Sources](#4-data-sources)
5. [PDF Generation](#5-pdf-generation)
6. [Excel/CSV Generation](#6-excelcsv-generation)
7. [Scheduled Reports](#7-scheduled-reports)
8. [Report Delivery](#8-report-delivery)
9. [Performance Optimization](#9-performance-optimization)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the report generation system for Smart Dairy ERP, covering report templates, data aggregation, multiple output formats, and scheduled report delivery.

### 1.2 Scope

- Standard business reports (Sales, Inventory, Production)
- Custom report builder
- Multiple output formats (PDF, Excel, CSV)
- Scheduled/automated reports
- Interactive dashboards

---

## 2. ARCHITECTURE OVERVIEW

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        REPORT GENERATION ARCHITECTURE                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  REQUEST                                                                    │
│     │                                                                       │
│     ▼                                                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    REPORT SERVICE                                    │   │
│  │  - Queue report generation                                          │   │
│  │  - Track job status                                                 │   │
│  │  - Manage templates                                                 │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│     │                                                                       │
│     ▼                                                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    CELERY WORKER                                     │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ PDF Worker   │  │ Excel Worker │  │ CSV Worker   │              │   │
│  │  │ (wkhtmltopdf)│  │ (openpyxl)   │  │ (pandas)     │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│     │                                                                       │
│     ▼                                                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    DATA AGGREGATION                                  │   │
│  │  - Odoo ORM queries                                                  │   │
│  │  - Direct SQL for complex reports                                    │   │
│  │  - TimescaleDB for time-series                                       │   │
│  │  - Cache layer for repeated queries                                  │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│     │                                                                       │
│     ▼                                                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    OUTPUT STORAGE                                    │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │ S3/MinIO     │  │ File System  │  │ Email Attach │              │   │
│  │  │ (Persistent) │  │ (Temp)       │  │ (Delivery)   │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. REPORT TYPES & TEMPLATES

### 3.1 Report Categories

| Category | Report Name | Frequency | Format |
|----------|-------------|-----------|--------|
| **Sales** | Daily Sales Summary | Daily | PDF, Excel |
| **Sales** | Monthly Revenue Report | Monthly | PDF, Excel |
| **Sales** | Customer Sales Analysis | Monthly | Excel |
| **Sales** | Product Performance | Weekly | PDF, Excel |
| **Inventory** | Stock Levels | Daily | PDF, Excel |
| **Inventory** | Stock Movement | Weekly | Excel |
| **Farm** | Daily Milk Production | Daily | PDF, Excel |
| **Farm** | Animal Health Summary | Monthly | PDF |
| **Farm** | Herd Performance | Monthly | PDF, Excel |
| **Financial** | VAT Return (Mushak) | Monthly | PDF |
| **Financial** | TDS Summary | Quarterly | Excel |

### 3.2 Report Template Structure

```python
# reports/base_report.py
from abc import ABC, abstractmethod
from typing import Dict, Any, List
from datetime import datetime, date

class ReportTemplate(ABC):
    """Base class for all reports."""
    
    name: str = ""
    description: str = ""
    category: str = ""
    
    # Output formats supported
    supported_formats: List[str] = ['pdf', 'excel', 'csv']
    
    # Default parameters
    default_parameters: Dict[str, Any] = {}
    
    @abstractmethod
    def get_data(self, **params) -> Dict[str, Any]:
        """Fetch and aggregate report data."""
        pass
    
    @abstractmethod
    def generate_pdf(self, data: Dict, output_path: str):
        """Generate PDF report."""
        pass
    
    @abstractmethod
    def generate_excel(self, data: Dict, output_path: str):
        """Generate Excel report."""
        pass
    
    @abstractmethod
    def generate_csv(self, data: Dict, output_path: str):
        """Generate CSV report."""
        pass


# reports/milk_production_report.py
from reports.base_report import ReportTemplate
from odoo import models

class MilkProductionReport(ReportTemplate):
    """Daily Milk Production Report."""
    
    name = "Daily Milk Production Report"
    description = "Summary of milk production by session and animal"
    category = "Farm"
    
    default_parameters = {
        'date_from': date.today(),
        'date_to': date.today(),
        'barn_id': None,
        'session': None,  # morning, evening, or all
    }
    
    def get_data(self, **params) -> Dict[str, Any]:
        """Fetch milk production data."""
        date_from = params.get('date_from')
        date_to = params.get('date_to')
        barn_id = params.get('barn_id')
        session = params.get('session')
        
        # Build domain
        domain = [
            ('production_date', '>=', date_from),
            ('production_date', '<=', date_to),
        ]
        
        if barn_id:
            domain.append(('animal_id.barn_id', '=', barn_id))
        
        if session:
            domain.append(('session', '=', session))
        
        # Fetch records
        records = self.env['farm.milk.production'].search(domain)
        
        # Aggregate data
        summary = {
            'total_volume': sum(r.quantity for r in records),
            'avg_fat': sum(r.fat_percentage or 0 for r in records) / len(records) if records else 0,
            'avg_snf': sum(r.snf_percentage or 0 for r in records) / len(records) if records else 0,
            'session_breakdown': self._get_session_breakdown(records),
            'top_producers': self._get_top_producers(records),
            'daily_trend': self._get_daily_trend(date_from, date_to, domain),
        }
        
        return {
            'parameters': params,
            'generated_at': datetime.now(),
            'summary': summary,
            'records': [r.read() for r in records],
            'record_count': len(records),
        }
    
    def _get_session_breakdown(self, records):
        """Breakdown by session."""
        sessions = {}
        for r in records:
            session = r.session
            if session not in sessions:
                sessions[session] = {'count': 0, 'volume': 0}
            sessions[session]['count'] += 1
            sessions[session]['volume'] += r.quantity
        return sessions
    
    def _get_top_producers(self, records, limit=10):
        """Get top producing animals."""
        animal_totals = {}
        for r in records:
            animal_id = r.animal_id.id
            if animal_id not in animal_totals:
                animal_totals[animal_id] = {
                    'animal': r.animal_id.read(['name', 'breed_id'])[0],
                    'total': 0,
                }
            animal_totals[animal_id]['total'] += r.quantity
        
        sorted_animals = sorted(
            animal_totals.values(),
            key=lambda x: x['total'],
            reverse=True
        )
        return sorted_animals[:limit]
    
    def _get_daily_trend(self, date_from, date_to, domain):
        """Get daily production trend."""
        self.env.cr.execute("""
            SELECT 
                production_date,
                SUM(quantity) as total_volume,
                COUNT(*) as record_count
            FROM farm_milk_production
            WHERE production_date BETWEEN %s AND %s
            GROUP BY production_date
            ORDER BY production_date
        """, (date_from, date_to))
        
        return [
            {'date': row[0], 'volume': row[1], 'count': row[2]}
            for row in self.env.cr.fetchall()
        ]
    
    def generate_pdf(self, data: Dict, output_path: str):
        """Generate PDF report using Jinja2 + wkhtmltopdf."""
        from jinja2 import Environment, FileSystemLoader
        import pdfkit
        
        # Render HTML template
        env = Environment(loader=FileSystemLoader('reports/templates'))
        template = env.get_template('milk_production_report.html')
        
        html_content = template.render(
            report_name=self.name,
            generated_at=data['generated_at'],
            parameters=data['parameters'],
            summary=data['summary'],
            records=data['records']
        )
        
        # Convert to PDF
        pdfkit.from_string(
            html_content,
            output_path,
            options={
                'page-size': 'A4',
                'margin-top': '20mm',
                'margin-bottom': '20mm',
                'header-html': 'reports/templates/header.html',
                'footer-html': 'reports/templates/footer.html',
            }
        )
    
    def generate_excel(self, data: Dict, output_path: str):
        """Generate Excel report using openpyxl."""
        from openpyxl import Workbook
        from openpyxl.styles import Font, PatternFill, Alignment
        
        wb = Workbook()
        ws = wb.active
        ws.title = "Milk Production"
        
        # Header
        ws['A1'] = self.name
        ws['A1'].font = Font(size=16, bold=True)
        ws.merge_cells('A1:G1')
        
        # Summary section
        ws['A3'] = 'Summary'
        ws['A3'].font = Font(bold=True, size=12)
        
        ws['A4'] = 'Total Volume:'
        ws['B4'] = data['summary']['total_volume']
        
        ws['A5'] = 'Avg Fat %:'
        ws['B5'] = f"{data['summary']['avg_fat']:.2f}"
        
        ws['A6'] = 'Avg SNF %:'
        ws['B6'] = f"{data['summary']['avg_snf']:.2f}"
        
        # Detail records
        start_row = 9
        headers = ['Date', 'Animal', 'Session', 'Volume (L)', 'Fat %', 'SNF %', 'Quality']
        
        for col, header in enumerate(headers, 1):
            cell = ws.cell(row=start_row, column=col, value=header)
            cell.font = Font(bold=True)
            cell.fill = PatternFill(start_color='CCCCCC', end_color='CCCCCC', fill_type='solid')
        
        for idx, record in enumerate(data['records'], start_row + 1):
            ws.cell(row=idx, column=1, value=record['production_date'])
            ws.cell(row=idx, column=2, value=record['animal_id'][1] if record['animal_id'] else '')
            ws.cell(row=idx, column=3, value=record['session'])
            ws.cell(row=idx, column=4, value=record['quantity'])
            ws.cell(row=idx, column=5, value=record['fat_percentage'])
            ws.cell(row=idx, column=6, value=record['snf_percentage'])
            ws.cell(row=idx, column=7, value=record['quality_grade'])
        
        # Auto-adjust column widths
        for column in ws.columns:
            max_length = 0
            column_letter = column[0].column_letter
            for cell in column:
                try:
                    if len(str(cell.value)) > max_length:
                        max_length = len(str(cell.value))
                except:
                    pass
            adjusted_width = min(max_length + 2, 50)
            ws.column_dimensions[column_letter].width = adjusted_width
        
        wb.save(output_path)
    
    def generate_csv(self, data: Dict, output_path: str):
        """Generate CSV report using pandas."""
        import pandas as pd
        
        df = pd.DataFrame(data['records'])
        
        # Select and rename columns
        df = df[['production_date', 'animal_id', 'session', 'quantity', 
                 'fat_percentage', 'snf_percentage', 'quality_grade']]
        
        df.columns = ['Date', 'Animal', 'Session', 'Volume (L)', 
                      'Fat %', 'SNF %', 'Quality']
        
        df.to_csv(output_path, index=False)
```

---

## 4. DATA SOURCES

### 4.1 Odoo ORM Queries

```python
# Optimized queries for reports
def get_sales_data_optimized(date_from, date_to, partner_id=None):
    """Get sales data with optimized queries."""
    
    # Use read_group for aggregation
    domain = [
        ('date_order', '>=', date_from),
        ('date_order', '<=', date_to),
        ('state', 'in', ['sale', 'done'])
    ]
    
    if partner_id:
        domain.append(('partner_id', '=', partner_id))
    
    # Aggregate by partner
    result = self.env['sale.order'].read_group(
        domain=domain,
        fields=['partner_id', 'amount_total:sum', 'id:count'],
        groupby=['partner_id']
    )
    
    return result
```

### 4.2 Direct SQL for Complex Reports

```python
def get_inventory_valuation_report(date):
    """Get inventory valuation using direct SQL for performance."""
    
    self.env.cr.execute("""
        SELECT 
            pt.name as product_name,
            pc.name as category,
            sq.quantity as quantity,
            COALESCE(svl.unit_cost, pt.standard_price) as unit_cost,
            sq.quantity * COALESCE(svl.unit_cost, pt.standard_price) as total_value,
            sl.name as location
        FROM stock_quant sq
        JOIN product_product pp ON sq.product_id = pp.id
        JOIN product_template pt ON pp.product_tmpl_id = pt.id
        JOIN product_category pc ON pt.categ_id = pc.id
        JOIN stock_location sl ON sq.location_id = sl.id
        LEFT JOIN LATERAL (
            SELECT unit_cost
            FROM stock_valuation_layer
            WHERE product_id = pp.id
            ORDER BY create_date DESC
            LIMIT 1
        ) svl ON true
        WHERE sq.quantity > 0
        ORDER BY pc.name, pt.name
    """)
    
    return self.env.cr.fetchall()
```

---

## 5. PDF GENERATION

### 5.1 HTML Template Structure

```html
<!-- reports/templates/milk_production_report.html -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
        }
        .header .company {
            font-size: 14pt;
            font-weight: bold;
        }
        .summary-box {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: flex;
            flex-wrap: wrap;
        }
        .summary-item {
            width: 33%;
            padding: 10px;
            box-sizing: border-box;
        }
        .summary-item .label {
            font-size: 9pt;
            color: #666;
        }
        .summary-item .value {
            font-size: 14pt;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">Smart Dairy Ltd.</div>
        <h1>{{ report_name }}</h1>
        <p>Generated: {{ generated_at.strftime('%Y-%m-%d %H:%M') }}</p>
        <p>Period: {{ parameters.date_from }} to {{ parameters.date_to }}</p>
    </div>
    
    <div class="summary-box">
        <h3>Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Volume</div>
                <div class="value">{{ "%.2f"|format(summary.total_volume) }} L</div>
            </div>
            <div class="summary-item">
                <div class="label">Average Fat</div>
                <div class="value">{{ "%.2f"|format(summary.avg_fat) }}%</div>
            </div>
            <div class="summary-item">
                <div class="label">Average SNF</div>
                <div class="value">{{ "%.2f"|format(summary.avg_snf) }}%</div>
            </div>
        </div>
    </div>
    
    <h3>Detailed Records</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Animal</th>
                <th>Session</th>
                <th>Volume (L)</th>
                <th>Fat %</th>
                <th>SNF %</th>
            </tr>
        </thead>
        <tbody>
            {% for record in records %}
            <tr>
                <td>{{ record.production_date }}</td>
                <td>{{ record.animal_id[1] if record.animal_id else '-' }}</td>
                <td>{{ record.session }}</td>
                <td>{{ "%.2f"|format(record.quantity) }}</td>
                <td>{{ "%.2f"|format(record.fat_percentage or 0) }}</td>
                <td>{{ "%.2f"|format(record.snf_percentage or 0) }}</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</body>
</html>
```

---

## 6. EXCEL/CSV GENERATION

### 6.1 Advanced Excel Features

```python
def generate_excel_with_charts(data, output_path):
    """Generate Excel with charts and formatting."""
    from openpyxl import Workbook
    from openpyxl.chart import LineChart, Reference
    from openpyxl.styles import Font, PatternFill, Border, Side
    from openpyxl.utils.dataframe import dataframe_to_rows
    import pandas as pd
    
    wb = Workbook()
    
    # Data sheet
    ws_data = wb.active
    ws_data.title = "Data"
    
    # Add data
    df = pd.DataFrame(data['summary']['daily_trend'])
    for r_idx, row in enumerate(dataframe_to_rows(df, index=False, header=True), 1):
        for c_idx, value in enumerate(row, 1):
            cell = ws_data.cell(row=r_idx, column=c_idx, value=value)
            if r_idx == 1:
                cell.font = Font(bold=True)
                cell.fill = PatternFill(start_color='366092', fill_type='solid')
    
    # Chart sheet
    ws_chart = wb.create_sheet("Chart")
    
    chart = LineChart()
    chart.title = "Daily Production Trend"
    chart.style = 10
    chart.y_axis.title = 'Volume (L)'
    chart.x_axis.title = 'Date'
    
    data_ref = Reference(ws_data, min_col=2, min_row=1, max_row=len(df)+1)
    cats_ref = Reference(ws_data, min_col=1, min_row=2, max_row=len(df)+1)
    
    chart.add_data(data_ref, titles_from_data=True)
    chart.set_categories(cats_ref)
    
    ws_chart.add_chart(chart, "A1")
    
    wb.save(output_path)
```

---

## 7. SCHEDULED REPORTS

### 7.1 Celery Beat Schedule

```python
# Scheduled report configuration
SCHEDULED_REPORTS = {
    'daily_milk_production': {
        'task': 'reports.tasks.generate_scheduled_report',
        'schedule': crontab(hour=7, minute=0),
        'kwargs': {
            'report_type': 'milk_production',
            'format': 'pdf',
            'recipients': ['manager@smartdairy.com'],
            'date_offset': -1  # Previous day
        }
    },
    'daily_sales_summary': {
        'task': 'reports.tasks.generate_scheduled_report',
        'schedule': crontab(hour=8, minute=0),
        'kwargs': {
            'report_type': 'sales_summary',
            'format': 'excel',
            'recipients': ['sales@smartdairy.com'],
        }
    },
    'weekly_inventory': {
        'task': 'reports.tasks.generate_scheduled_report',
        'schedule': crontab(day_of_week='monday', hour=6, minute=0),
        'kwargs': {
            'report_type': 'inventory_status',
            'format': 'pdf',
            'recipients': ['warehouse@smartdairy.com'],
        }
    }
}
```

---

## 8. REPORT DELIVERY

### 8.1 Delivery Methods

| Method | Use Case | Configuration |
|--------|----------|---------------|
| Email | Scheduled reports | SMTP configuration |
| Download | On-demand reports | Temporary URL |
| Dashboard | Real-time metrics | Embedded view |
| API | Third-party integration | REST endpoint |
| SFTP | Bulk exports | Scheduled upload |

### 8.2 Email Delivery

```python
def deliver_report_email(report_path, recipients, subject, body):
    """Deliver report via email."""
    from email.mime.multipart import MIMEMultipart
    from email.mime.base import MIMEBase
    from email.mime.text import MIMEText
    from email import encoders
    import smtplib
    
    msg = MIMEMultipart()
    msg['From'] = settings.REPORT_EMAIL_FROM
    msg['To'] = ', '.join(recipients)
    msg['Subject'] = subject
    
    msg.attach(MIMEText(body, 'html'))
    
    # Attach file
    with open(report_path, 'rb') as f:
        attachment = MIMEBase('application', 'octet-stream')
        attachment.set_payload(f.read())
        encoders.encode_base64(attachment)
        attachment.add_header(
            'Content-Disposition',
            f'attachment; filename={os.path.basename(report_path)}'
        )
        msg.attach(attachment)
    
    # Send
    with smtplib.SMTP(settings.SMTP_HOST, settings.SMTP_PORT) as server:
        server.starttls()
        server.login(settings.SMTP_USER, settings.SMTP_PASS)
        server.send_message(msg)
```

---

## 9. PERFORMANCE OPTIMIZATION

### 9.1 Optimization Strategies

| Strategy | Implementation | Benefit |
|----------|---------------|---------|
| Async Generation | Celery tasks | Non-blocking |
| Query Optimization | Indexes, limits | Faster data fetch |
| Caching | Redis for repeated reports | Reduced DB load |
| Pagination | Stream large datasets | Memory efficiency |
| Connection Pooling | Reuse DB connections | Reduced overhead |

---

## 10. APPENDICES

### Appendix A: Report Template Registry

```python
REPORT_REGISTRY = {
    'milk_production': MilkProductionReport,
    'sales_summary': SalesSummaryReport,
    'inventory_status': InventoryStatusReport,
    'customer_sales': CustomerSalesReport,
    'vat_return': VatReturnReport,
}
```

---

**END OF REPORT GENERATION SYSTEM DESIGN**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 22, 2026 | Tech Lead | Initial version |
