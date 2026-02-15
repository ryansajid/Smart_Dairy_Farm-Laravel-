# Milestone 109: Custom Reports and Self-Service Analytics

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M109-CUSTOM-REPORTS |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 581-590 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 109 delivers a comprehensive Custom Reports and Self-Service Analytics module that empowers business users to create, schedule, and distribute reports without IT dependency. This module includes a visual report builder, template library, automated scheduling, multi-format exports, and a self-service analytics portal integrated with Apache Superset.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| IT Efficiency | 70% reduction in ad-hoc report requests | Support ticket reduction |
| User Empowerment | 50+ business users creating own reports | Active report creators |
| Time Savings | 80% faster report generation | Report delivery time |
| Compliance | 100% audit trail for reports | Compliance score |
| Accessibility | 24/7 self-service access | User satisfaction |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-RPT-001**: Self-service reporting capabilities
- **RFP-RPT-002**: Automated report scheduling and distribution
- **BRD-DATA-001**: Data democratization initiative
- **SRS-ANALYTICS-040**: Custom report builder
- **SRS-ANALYTICS-041**: Report scheduling engine
- **SRS-ANALYTICS-042**: Multi-format export support

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| Report Builder | Visual report designer interface | P0 |
| Report Builder | Drag-and-drop field selection | P0 |
| Report Builder | Filter and parameter configuration | P0 |
| Report Builder | Chart and visualization options | P0 |
| Templates | Pre-built report templates library | P0 |
| Templates | Template customization capability | P1 |
| Templates | Template sharing and governance | P1 |
| Scheduling | Automated report scheduling | P0 |
| Scheduling | Frequency options (daily/weekly/monthly) | P0 |
| Scheduling | Email distribution lists | P0 |
| Scheduling | Conditional delivery (threshold-based) | P1 |
| Export | PDF export with formatting | P0 |
| Export | Excel export with formulas | P0 |
| Export | CSV export for data analysis | P0 |
| Export | PowerPoint integration | P1 |
| Self-Service | Ad-hoc query interface | P0 |
| Self-Service | Saved query library | P0 |
| Self-Service | Personal dashboard creation | P1 |
| Governance | Report access control | P0 |
| Governance | Audit logging | P0 |
| Governance | Data masking for sensitive fields | P1 |

### 2.2 Out-of-Scope Items

- Natural language query processing
- AI-powered report recommendations
- External data source connections (beyond Odoo/DW)
- Advanced statistical analysis tools
- Collaborative report editing

---

## 3. Technical Architecture

### 3.1 Report Configuration Data Model

```sql
-- =====================================================
-- CUSTOM REPORTS CONFIGURATION SCHEMA
-- Milestone 109 - Days 581-590
-- =====================================================

-- Report Definition Table
CREATE TABLE report_definition (
    report_id SERIAL PRIMARY KEY,
    report_code VARCHAR(50) UNIQUE NOT NULL,
    report_name VARCHAR(200) NOT NULL,
    report_description TEXT,
    report_category VARCHAR(50),
    report_type VARCHAR(30), -- 'standard', 'ad_hoc', 'template', 'dashboard'

    -- Data Source Configuration
    data_source_type VARCHAR(30), -- 'sql', 'view', 'dataset', 'api'
    data_source_name VARCHAR(200),
    base_query TEXT,
    query_parameters JSONB, -- Parameter definitions

    -- Layout Configuration
    layout_config JSONB, -- Column definitions, groupings, totals
    chart_config JSONB, -- Visualization settings
    formatting_config JSONB, -- Styles, colors, fonts

    -- Access Control
    owner_user_id INTEGER NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    allowed_roles JSONB, -- Role-based access
    allowed_users JSONB, -- User-specific access

    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER,
    updated_by INTEGER,
    version INTEGER DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    is_template BOOLEAN DEFAULT FALSE
);

CREATE INDEX idx_report_def_category ON report_definition(report_category);
CREATE INDEX idx_report_def_owner ON report_definition(owner_user_id);
CREATE INDEX idx_report_def_type ON report_definition(report_type);

-- Report Parameters Table
CREATE TABLE report_parameter (
    parameter_id SERIAL PRIMARY KEY,
    report_id INTEGER NOT NULL REFERENCES report_definition(report_id),
    parameter_name VARCHAR(50) NOT NULL,
    parameter_label VARCHAR(100),
    parameter_type VARCHAR(30), -- 'date', 'daterange', 'select', 'multiselect', 'text', 'number'
    is_required BOOLEAN DEFAULT FALSE,
    default_value TEXT,
    validation_rule TEXT,

    -- For select/multiselect
    options_source VARCHAR(200), -- SQL or static list
    options_static JSONB,

    -- Display
    display_order INTEGER DEFAULT 0,
    help_text TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(report_id, parameter_name)
);

CREATE INDEX idx_report_param_report ON report_parameter(report_id);

-- Report Schedule Table
CREATE TABLE report_schedule (
    schedule_id SERIAL PRIMARY KEY,
    report_id INTEGER NOT NULL REFERENCES report_definition(report_id),
    schedule_name VARCHAR(100),

    -- Schedule Configuration
    is_active BOOLEAN DEFAULT TRUE,
    frequency VARCHAR(20), -- 'once', 'daily', 'weekly', 'monthly', 'quarterly'
    schedule_time TIME,
    schedule_day_of_week INTEGER, -- 0-6 for weekly
    schedule_day_of_month INTEGER, -- 1-31 for monthly
    timezone VARCHAR(50) DEFAULT 'UTC',

    -- Next Execution
    next_run_at TIMESTAMP,
    last_run_at TIMESTAMP,
    last_run_status VARCHAR(20),
    last_run_message TEXT,

    -- Parameters for Scheduled Run
    parameter_values JSONB,

    -- Output Configuration
    output_format VARCHAR(20), -- 'pdf', 'excel', 'csv', 'html'
    output_options JSONB, -- Format-specific options

    -- Distribution
    distribution_type VARCHAR(20), -- 'email', 'sftp', 'webhook', 'storage'
    distribution_config JSONB, -- Recipients, paths, etc.

    -- Conditional Delivery
    conditional_delivery BOOLEAN DEFAULT FALSE,
    condition_expression TEXT, -- SQL expression returning boolean
    condition_description TEXT,

    -- Metadata
    created_by INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_schedule_report ON report_schedule(report_id);
CREATE INDEX idx_schedule_next_run ON report_schedule(next_run_at) WHERE is_active = TRUE;

-- Report Execution Log
CREATE TABLE report_execution_log (
    execution_id SERIAL PRIMARY KEY,
    report_id INTEGER NOT NULL REFERENCES report_definition(report_id),
    schedule_id INTEGER REFERENCES report_schedule(schedule_id),

    -- Execution Details
    execution_type VARCHAR(20), -- 'manual', 'scheduled', 'api'
    execution_start TIMESTAMP NOT NULL,
    execution_end TIMESTAMP,
    execution_status VARCHAR(20), -- 'running', 'completed', 'failed', 'cancelled'
    error_message TEXT,

    -- Parameters Used
    parameter_values JSONB,

    -- Output
    output_format VARCHAR(20),
    output_path TEXT,
    output_size_bytes BIGINT,
    row_count INTEGER,

    -- Performance
    query_time_ms INTEGER,
    render_time_ms INTEGER,
    total_time_ms INTEGER,

    -- User Info
    executed_by INTEGER,
    executed_for VARCHAR(200), -- Recipients if scheduled

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_execution_report ON report_execution_log(report_id);
CREATE INDEX idx_execution_date ON report_execution_log(execution_start);
CREATE INDEX idx_execution_status ON report_execution_log(execution_status);

-- Report Template Library
CREATE TABLE report_template (
    template_id SERIAL PRIMARY KEY,
    template_code VARCHAR(50) UNIQUE NOT NULL,
    template_name VARCHAR(200) NOT NULL,
    template_description TEXT,
    template_category VARCHAR(50),
    template_thumbnail TEXT, -- Base64 or URL

    -- Template Configuration
    report_config JSONB NOT NULL, -- Full report definition
    sample_data JSONB, -- Sample for preview

    -- Usage
    usage_count INTEGER DEFAULT 0,
    rating_avg DECIMAL(3,2),
    rating_count INTEGER DEFAULT 0,

    -- Access
    is_public BOOLEAN DEFAULT TRUE,
    is_system BOOLEAN DEFAULT FALSE, -- System templates can't be deleted

    created_by INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_template_category ON report_template(template_category);

-- User Report Preferences
CREATE TABLE user_report_preferences (
    preference_id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    report_id INTEGER REFERENCES report_definition(report_id),

    -- Favorites
    is_favorite BOOLEAN DEFAULT FALSE,

    -- Default Parameters
    default_parameters JSONB,

    -- Display Preferences
    default_format VARCHAR(20),
    custom_layout JSONB,

    -- Recent Access
    last_accessed_at TIMESTAMP,
    access_count INTEGER DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(user_id, report_id)
);

CREATE INDEX idx_user_prefs_user ON user_report_preferences(user_id);

-- Report Sharing/Collaboration
CREATE TABLE report_share (
    share_id SERIAL PRIMARY KEY,
    report_id INTEGER NOT NULL REFERENCES report_definition(report_id),
    shared_by INTEGER NOT NULL,
    shared_with_user INTEGER,
    shared_with_role VARCHAR(50),
    shared_with_email VARCHAR(200), -- For external sharing

    -- Permissions
    can_view BOOLEAN DEFAULT TRUE,
    can_edit BOOLEAN DEFAULT FALSE,
    can_schedule BOOLEAN DEFAULT FALSE,
    can_export BOOLEAN DEFAULT TRUE,

    -- Expiration
    expires_at TIMESTAMP,
    share_token VARCHAR(100), -- For link sharing

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CHECK (shared_with_user IS NOT NULL OR shared_with_role IS NOT NULL OR shared_with_email IS NOT NULL)
);

CREATE INDEX idx_share_report ON report_share(report_id);
CREATE INDEX idx_share_user ON report_share(shared_with_user);
```

### 3.2 Report Generation Service

```python
# services/report_generator.py
"""
Custom Report Generation Service
Milestone 109 - Days 581-590
"""

import os
import io
import json
from datetime import datetime, timedelta
from typing import Dict, List, Optional, Any, BinaryIO
from dataclasses import dataclass
import pandas as pd
import numpy as np
from jinja2 import Environment, FileSystemLoader
from weasyprint import HTML, CSS
from openpyxl import Workbook
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.utils.dataframe import dataframe_to_rows
from openpyxl.chart import BarChart, LineChart, PieChart, Reference
import logging

logger = logging.getLogger(__name__)


@dataclass
class ReportDefinition:
    """Report configuration container."""
    report_id: int
    report_code: str
    report_name: str
    data_source_type: str
    base_query: str
    query_parameters: Dict
    layout_config: Dict
    chart_config: Dict
    formatting_config: Dict


@dataclass
class ReportOutput:
    """Report generation result."""
    report_id: int
    execution_id: int
    format: str
    content: bytes
    filename: str
    row_count: int
    generation_time_ms: int
    metadata: Dict


class ReportDataService:
    """Handles report data retrieval and processing."""

    def __init__(self, db_connection):
        self.db = db_connection

    def execute_query(
        self,
        query: str,
        parameters: Dict[str, Any]
    ) -> pd.DataFrame:
        """Execute report query with parameters."""
        # Sanitize and validate parameters
        sanitized_params = self._sanitize_parameters(parameters)

        # Execute query
        start_time = datetime.now()
        df = pd.read_sql(query, self.db, params=sanitized_params)
        execution_time = (datetime.now() - start_time).total_seconds() * 1000

        logger.info(f"Query executed in {execution_time:.0f}ms, returned {len(df)} rows")

        return df

    def _sanitize_parameters(self, parameters: Dict) -> Dict:
        """Sanitize query parameters to prevent injection."""
        sanitized = {}
        for key, value in parameters.items():
            if isinstance(value, str):
                # Basic sanitization - in production use parameterized queries
                sanitized[key] = value.replace("'", "''")
            elif isinstance(value, (list, tuple)):
                sanitized[key] = tuple(
                    v.replace("'", "''") if isinstance(v, str) else v
                    for v in value
                )
            else:
                sanitized[key] = value
        return sanitized

    def apply_aggregations(
        self,
        df: pd.DataFrame,
        config: Dict
    ) -> pd.DataFrame:
        """Apply grouping and aggregations based on config."""
        if not config.get('groupby'):
            return df

        group_cols = config['groupby']
        agg_config = config.get('aggregations', {})

        if agg_config:
            result = df.groupby(group_cols).agg(agg_config).reset_index()
        else:
            result = df.groupby(group_cols).sum().reset_index()

        return result

    def calculate_totals(
        self,
        df: pd.DataFrame,
        total_columns: List[str]
    ) -> pd.DataFrame:
        """Add total row to dataframe."""
        if not total_columns or df.empty:
            return df

        totals = {'_row_type': 'total'}
        for col in df.columns:
            if col in total_columns:
                totals[col] = df[col].sum()
            elif df[col].dtype in ['int64', 'float64']:
                totals[col] = ''
            else:
                totals[col] = 'TOTAL' if col == df.columns[0] else ''

        df['_row_type'] = 'data'
        df = pd.concat([df, pd.DataFrame([totals])], ignore_index=True)

        return df


class PDFReportGenerator:
    """Generates PDF reports using WeasyPrint."""

    def __init__(self, template_dir: str = 'templates/reports'):
        self.env = Environment(loader=FileSystemLoader(template_dir))
        self.default_css = self._load_default_css()

    def _load_default_css(self) -> str:
        return """
            @page {
                size: A4;
                margin: 2cm;
                @top-center { content: string(report-title); }
                @bottom-center { content: "Page " counter(page) " of " counter(pages); }
            }
            body {
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 10pt;
                line-height: 1.4;
            }
            h1 { string-set: report-title content(); font-size: 16pt; color: #2c3e50; }
            h2 { font-size: 14pt; color: #34495e; border-bottom: 1px solid #bdc3c7; }
            table { width: 100%; border-collapse: collapse; margin: 1em 0; }
            th { background-color: #3498db; color: white; padding: 8px; text-align: left; }
            td { padding: 6px 8px; border-bottom: 1px solid #ecf0f1; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            tr.total { font-weight: bold; background-color: #ecf0f1; }
            .number { text-align: right; }
            .chart-container { page-break-inside: avoid; margin: 1em 0; }
            .header { margin-bottom: 20px; }
            .footer { margin-top: 20px; font-size: 8pt; color: #7f8c8d; }
        """

    def generate(
        self,
        report_def: ReportDefinition,
        data: pd.DataFrame,
        parameters: Dict
    ) -> bytes:
        """Generate PDF report."""

        # Prepare template context
        context = {
            'report_name': report_def.report_name,
            'generated_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'parameters': parameters,
            'data': data.to_dict('records'),
            'columns': self._prepare_columns(report_def.layout_config, data),
            'totals': report_def.layout_config.get('totals', []),
            'charts': report_def.chart_config,
            'formatting': report_def.formatting_config
        }

        # Render HTML template
        template = self.env.get_template('report_template.html')
        html_content = template.render(**context)

        # Generate PDF
        pdf_bytes = HTML(string=html_content).write_pdf(
            stylesheets=[CSS(string=self.default_css)]
        )

        return pdf_bytes

    def _prepare_columns(
        self,
        layout_config: Dict,
        data: pd.DataFrame
    ) -> List[Dict]:
        """Prepare column definitions for template."""
        columns = []
        column_config = layout_config.get('columns', {})

        for col in data.columns:
            if col.startswith('_'):  # Skip internal columns
                continue

            config = column_config.get(col, {})
            columns.append({
                'name': col,
                'label': config.get('label', col.replace('_', ' ').title()),
                'type': config.get('type', 'text'),
                'format': config.get('format'),
                'width': config.get('width'),
                'alignment': config.get('alignment', 'left')
            })

        return columns


class ExcelReportGenerator:
    """Generates Excel reports with formatting and charts."""

    def __init__(self):
        self.header_font = Font(bold=True, color='FFFFFF')
        self.header_fill = PatternFill(start_color='3498DB', end_color='3498DB', fill_type='solid')
        self.total_fill = PatternFill(start_color='ECF0F1', end_color='ECF0F1', fill_type='solid')
        self.border = Border(
            bottom=Side(style='thin', color='BDC3C7')
        )

    def generate(
        self,
        report_def: ReportDefinition,
        data: pd.DataFrame,
        parameters: Dict
    ) -> bytes:
        """Generate Excel report."""

        wb = Workbook()
        ws = wb.active
        ws.title = report_def.report_name[:31]  # Excel sheet name limit

        # Add header section
        self._add_header(ws, report_def, parameters)

        # Add data
        start_row = self._add_data(ws, data, report_def.layout_config, start_row=5)

        # Add charts if configured
        if report_def.chart_config:
            self._add_charts(ws, data, report_def.chart_config, start_row + 2)

        # Auto-adjust column widths
        self._auto_adjust_columns(ws)

        # Save to bytes
        output = io.BytesIO()
        wb.save(output)
        output.seek(0)

        return output.read()

    def _add_header(
        self,
        ws,
        report_def: ReportDefinition,
        parameters: Dict
    ):
        """Add report header section."""
        ws['A1'] = report_def.report_name
        ws['A1'].font = Font(bold=True, size=14)

        ws['A2'] = f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}"
        ws['A2'].font = Font(italic=True, color='7F8C8D')

        # Parameters
        if parameters:
            param_str = ', '.join(f"{k}: {v}" for k, v in parameters.items())
            ws['A3'] = f"Parameters: {param_str}"

    def _add_data(
        self,
        ws,
        data: pd.DataFrame,
        layout_config: Dict,
        start_row: int
    ) -> int:
        """Add data table to worksheet."""
        column_config = layout_config.get('columns', {})

        # Headers
        for col_idx, col_name in enumerate(data.columns, 1):
            if col_name.startswith('_'):
                continue

            config = column_config.get(col_name, {})
            cell = ws.cell(row=start_row, column=col_idx)
            cell.value = config.get('label', col_name.replace('_', ' ').title())
            cell.font = self.header_font
            cell.fill = self.header_fill
            cell.alignment = Alignment(horizontal='center')

        # Data rows
        current_row = start_row + 1
        for _, row in data.iterrows():
            is_total = row.get('_row_type') == 'total'

            for col_idx, col_name in enumerate(data.columns, 1):
                if col_name.startswith('_'):
                    continue

                cell = ws.cell(row=current_row, column=col_idx)
                cell.value = row[col_name]
                cell.border = self.border

                if is_total:
                    cell.fill = self.total_fill
                    cell.font = Font(bold=True)

                # Apply number format
                config = column_config.get(col_name, {})
                if config.get('format') == 'currency':
                    cell.number_format = '$#,##0.00'
                elif config.get('format') == 'percentage':
                    cell.number_format = '0.00%'
                elif config.get('format') == 'number':
                    cell.number_format = '#,##0'

            current_row += 1

        return current_row

    def _add_charts(
        self,
        ws,
        data: pd.DataFrame,
        chart_config: Dict,
        start_row: int
    ):
        """Add charts to worksheet."""
        chart_type = chart_config.get('type', 'bar')
        chart_title = chart_config.get('title', '')

        if chart_type == 'bar':
            chart = BarChart()
        elif chart_type == 'line':
            chart = LineChart()
        elif chart_type == 'pie':
            chart = PieChart()
        else:
            return

        chart.title = chart_title
        chart.width = 15
        chart.height = 10

        # Add to worksheet
        ws.add_chart(chart, f"A{start_row}")

    def _auto_adjust_columns(self, ws):
        """Auto-adjust column widths based on content."""
        for column_cells in ws.columns:
            max_length = 0
            column = column_cells[0].column_letter

            for cell in column_cells:
                try:
                    if cell.value:
                        max_length = max(max_length, len(str(cell.value)))
                except:
                    pass

            adjusted_width = min(max_length + 2, 50)
            ws.column_dimensions[column].width = adjusted_width


class CSVReportGenerator:
    """Generates CSV exports."""

    def generate(
        self,
        report_def: ReportDefinition,
        data: pd.DataFrame,
        parameters: Dict
    ) -> bytes:
        """Generate CSV export."""

        # Remove internal columns
        export_data = data[[col for col in data.columns if not col.startswith('_')]]

        # Apply column labels
        column_config = report_def.layout_config.get('columns', {})
        rename_map = {
            col: column_config.get(col, {}).get('label', col)
            for col in export_data.columns
        }
        export_data = export_data.rename(columns=rename_map)

        # Generate CSV
        output = io.StringIO()
        export_data.to_csv(output, index=False)

        return output.getvalue().encode('utf-8')


class ReportScheduler:
    """Handles report scheduling and execution."""

    def __init__(self, db_connection, report_service):
        self.db = db_connection
        self.report_service = report_service

    def get_due_schedules(self) -> List[Dict]:
        """Get schedules that are due for execution."""
        query = """
            SELECT
                s.*,
                r.report_code,
                r.report_name,
                r.base_query,
                r.layout_config,
                r.chart_config,
                r.formatting_config
            FROM report_schedule s
            JOIN report_definition r ON s.report_id = r.report_id
            WHERE s.is_active = TRUE
              AND s.next_run_at <= NOW()
            ORDER BY s.next_run_at
        """
        return pd.read_sql(query, self.db).to_dict('records')

    def execute_schedule(self, schedule: Dict) -> bool:
        """Execute a scheduled report."""
        try:
            logger.info(f"Executing schedule {schedule['schedule_id']} for report {schedule['report_code']}")

            # Generate report
            report_def = ReportDefinition(
                report_id=schedule['report_id'],
                report_code=schedule['report_code'],
                report_name=schedule['report_name'],
                data_source_type='sql',
                base_query=schedule['base_query'],
                query_parameters=schedule.get('query_parameters', {}),
                layout_config=schedule.get('layout_config', {}),
                chart_config=schedule.get('chart_config', {}),
                formatting_config=schedule.get('formatting_config', {})
            )

            output = self.report_service.generate_report(
                report_def=report_def,
                parameters=schedule.get('parameter_values', {}),
                output_format=schedule['output_format']
            )

            # Check conditional delivery
            if schedule.get('conditional_delivery'):
                if not self._evaluate_condition(schedule['condition_expression'], output):
                    logger.info("Conditional delivery not met, skipping distribution")
                    return True

            # Distribute report
            self._distribute_report(schedule, output)

            # Update schedule
            self._update_schedule_status(schedule['schedule_id'], 'completed')

            return True

        except Exception as e:
            logger.error(f"Schedule execution failed: {e}")
            self._update_schedule_status(schedule['schedule_id'], 'failed', str(e))
            return False

    def _distribute_report(self, schedule: Dict, output: ReportOutput):
        """Distribute generated report."""
        dist_type = schedule['distribution_type']
        dist_config = schedule.get('distribution_config', {})

        if dist_type == 'email':
            self._send_email(
                recipients=dist_config.get('recipients', []),
                subject=f"Report: {schedule['report_name']}",
                body=dist_config.get('body', 'Please find the attached report.'),
                attachment=output.content,
                attachment_name=output.filename
            )
        elif dist_type == 'sftp':
            self._upload_sftp(
                host=dist_config['host'],
                path=dist_config['path'],
                content=output.content,
                filename=output.filename
            )
        elif dist_type == 'storage':
            self._save_to_storage(
                path=dist_config['path'],
                content=output.content,
                filename=output.filename
            )

    def _send_email(self, recipients: List[str], subject: str, body: str,
                    attachment: bytes, attachment_name: str):
        """Send report via email."""
        # Email sending implementation
        logger.info(f"Sending report to {len(recipients)} recipients")
        pass

    def _calculate_next_run(self, schedule: Dict) -> datetime:
        """Calculate next run time based on frequency."""
        frequency = schedule['frequency']
        current_time = datetime.now()

        if frequency == 'daily':
            next_run = current_time + timedelta(days=1)
        elif frequency == 'weekly':
            next_run = current_time + timedelta(weeks=1)
        elif frequency == 'monthly':
            next_run = current_time + timedelta(days=30)
        else:
            next_run = None

        return next_run

    def _update_schedule_status(self, schedule_id: int, status: str, message: str = None):
        """Update schedule execution status."""
        # Database update implementation
        pass


class ReportService:
    """Main report service orchestrating report generation."""

    def __init__(self, db_connection):
        self.db = db_connection
        self.data_service = ReportDataService(db_connection)
        self.pdf_generator = PDFReportGenerator()
        self.excel_generator = ExcelReportGenerator()
        self.csv_generator = CSVReportGenerator()

    def generate_report(
        self,
        report_def: ReportDefinition,
        parameters: Dict,
        output_format: str = 'pdf'
    ) -> ReportOutput:
        """Generate report in specified format."""
        start_time = datetime.now()

        # Execute query
        data = self.data_service.execute_query(
            report_def.base_query,
            parameters
        )

        # Apply transformations
        if report_def.layout_config.get('groupby'):
            data = self.data_service.apply_aggregations(data, report_def.layout_config)

        if report_def.layout_config.get('totals'):
            data = self.data_service.calculate_totals(
                data,
                report_def.layout_config['totals']
            )

        # Generate output
        if output_format == 'pdf':
            content = self.pdf_generator.generate(report_def, data, parameters)
            ext = 'pdf'
        elif output_format == 'excel':
            content = self.excel_generator.generate(report_def, data, parameters)
            ext = 'xlsx'
        elif output_format == 'csv':
            content = self.csv_generator.generate(report_def, data, parameters)
            ext = 'csv'
        else:
            raise ValueError(f"Unsupported format: {output_format}")

        generation_time = int((datetime.now() - start_time).total_seconds() * 1000)

        return ReportOutput(
            report_id=report_def.report_id,
            execution_id=0,  # Set by caller
            format=output_format,
            content=content,
            filename=f"{report_def.report_code}_{datetime.now().strftime('%Y%m%d_%H%M%S')}.{ext}",
            row_count=len(data),
            generation_time_ms=generation_time,
            metadata={'parameters': parameters}
        )
```

### 3.3 Report Builder API

```python
# api/report_builder_api.py
"""
Report Builder REST API
Milestone 109 - Days 581-590
"""

from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks
from fastapi.responses import StreamingResponse
from pydantic import BaseModel
from typing import List, Optional, Dict, Any
from datetime import datetime
import io

router = APIRouter(prefix="/api/v1/reports", tags=["reports"])


class ReportCreateRequest(BaseModel):
    report_code: str
    report_name: str
    report_description: Optional[str]
    report_category: str
    data_source_type: str = "sql"
    base_query: str
    layout_config: Dict[str, Any]
    chart_config: Optional[Dict[str, Any]]


class ReportParameterRequest(BaseModel):
    parameter_name: str
    parameter_label: str
    parameter_type: str
    is_required: bool = False
    default_value: Optional[str]
    options_static: Optional[List[Dict]]


class ScheduleCreateRequest(BaseModel):
    report_id: int
    schedule_name: str
    frequency: str
    schedule_time: str
    output_format: str
    distribution_type: str
    distribution_config: Dict[str, Any]
    parameter_values: Optional[Dict[str, Any]]


class ReportExecuteRequest(BaseModel):
    parameters: Dict[str, Any]
    output_format: str = "pdf"


@router.post("/")
async def create_report(
    request: ReportCreateRequest,
    current_user: dict = Depends(get_current_user)
):
    """Create a new report definition."""
    # Validate query (basic syntax check)
    if not validate_query(request.base_query):
        raise HTTPException(400, "Invalid SQL query")

    report_id = await report_repository.create(
        report_code=request.report_code,
        report_name=request.report_name,
        report_description=request.report_description,
        report_category=request.report_category,
        data_source_type=request.data_source_type,
        base_query=request.base_query,
        layout_config=request.layout_config,
        chart_config=request.chart_config,
        owner_user_id=current_user['id']
    )

    return {"report_id": report_id, "message": "Report created successfully"}


@router.get("/")
async def list_reports(
    category: Optional[str] = None,
    search: Optional[str] = None,
    current_user: dict = Depends(get_current_user)
):
    """List reports accessible to current user."""
    reports = await report_repository.list_accessible(
        user_id=current_user['id'],
        roles=current_user['roles'],
        category=category,
        search=search
    )
    return {"reports": reports}


@router.get("/{report_id}")
async def get_report(
    report_id: int,
    current_user: dict = Depends(get_current_user)
):
    """Get report definition."""
    report = await report_repository.get(report_id)
    if not report:
        raise HTTPException(404, "Report not found")

    # Check access
    if not has_report_access(report, current_user):
        raise HTTPException(403, "Access denied")

    return report


@router.post("/{report_id}/execute")
async def execute_report(
    report_id: int,
    request: ReportExecuteRequest,
    current_user: dict = Depends(get_current_user)
):
    """Execute report and return output."""
    report = await report_repository.get(report_id)
    if not report:
        raise HTTPException(404, "Report not found")

    if not has_report_access(report, current_user):
        raise HTTPException(403, "Access denied")

    # Generate report
    output = await report_service.generate_report(
        report_def=report,
        parameters=request.parameters,
        output_format=request.output_format
    )

    # Log execution
    await execution_log_repository.create(
        report_id=report_id,
        execution_type='manual',
        parameter_values=request.parameters,
        output_format=request.output_format,
        row_count=output.row_count,
        executed_by=current_user['id']
    )

    # Return file
    content_types = {
        'pdf': 'application/pdf',
        'excel': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'csv': 'text/csv'
    }

    return StreamingResponse(
        io.BytesIO(output.content),
        media_type=content_types.get(request.output_format, 'application/octet-stream'),
        headers={
            'Content-Disposition': f'attachment; filename="{output.filename}"'
        }
    )


@router.post("/{report_id}/preview")
async def preview_report(
    report_id: int,
    request: ReportExecuteRequest,
    current_user: dict = Depends(get_current_user)
):
    """Preview report with limited data."""
    report = await report_repository.get(report_id)
    if not report:
        raise HTTPException(404, "Report not found")

    # Execute with row limit
    preview_data = await report_service.preview_report(
        report_def=report,
        parameters=request.parameters,
        max_rows=100
    )

    return {
        "columns": preview_data['columns'],
        "data": preview_data['data'][:100],
        "total_rows": preview_data['total_rows'],
        "preview_limited": preview_data['total_rows'] > 100
    }


@router.post("/{report_id}/schedule")
async def create_schedule(
    report_id: int,
    request: ScheduleCreateRequest,
    current_user: dict = Depends(get_current_user)
):
    """Create report schedule."""
    report = await report_repository.get(report_id)
    if not report:
        raise HTTPException(404, "Report not found")

    if not can_schedule_report(report, current_user):
        raise HTTPException(403, "Not authorized to schedule this report")

    schedule_id = await schedule_repository.create(
        report_id=report_id,
        schedule_name=request.schedule_name,
        frequency=request.frequency,
        schedule_time=request.schedule_time,
        output_format=request.output_format,
        distribution_type=request.distribution_type,
        distribution_config=request.distribution_config,
        parameter_values=request.parameter_values,
        created_by=current_user['id']
    )

    return {"schedule_id": schedule_id, "message": "Schedule created successfully"}


@router.get("/templates")
async def list_templates(
    category: Optional[str] = None
):
    """List available report templates."""
    templates = await template_repository.list(category=category)
    return {"templates": templates}


@router.post("/templates/{template_id}/create-report")
async def create_from_template(
    template_id: int,
    report_name: str,
    current_user: dict = Depends(get_current_user)
):
    """Create a new report from template."""
    template = await template_repository.get(template_id)
    if not template:
        raise HTTPException(404, "Template not found")

    # Create report from template config
    report_id = await report_repository.create_from_template(
        template=template,
        report_name=report_name,
        owner_user_id=current_user['id']
    )

    # Increment template usage
    await template_repository.increment_usage(template_id)

    return {"report_id": report_id, "message": "Report created from template"}
```

---

## 4. Pre-Built Report Templates

### 4.1 Sales Report Templates

```sql
-- Template: Daily Sales Summary
INSERT INTO report_template (template_code, template_name, template_description, template_category, report_config) VALUES
('TMPL_DAILY_SALES', 'Daily Sales Summary', 'Summary of daily sales by product and channel', 'Sales', '{
    "base_query": "SELECT d.date_actual, p.product_name, c.channel_name, SUM(fs.quantity) as qty_sold, SUM(fs.revenue) as revenue, SUM(fs.margin) as margin FROM fact_sales fs JOIN dim_date d ON fs.date_sk = d.date_sk JOIN dim_product p ON fs.product_sk = p.product_sk JOIN dim_channel c ON fs.channel_sk = c.channel_sk WHERE d.date_actual = :report_date GROUP BY d.date_actual, p.product_name, c.channel_name ORDER BY revenue DESC",
    "parameters": [{"name": "report_date", "type": "date", "required": true}],
    "layout_config": {
        "columns": {
            "revenue": {"label": "Revenue", "format": "currency", "alignment": "right"},
            "margin": {"label": "Margin", "format": "currency", "alignment": "right"},
            "qty_sold": {"label": "Quantity", "format": "number", "alignment": "right"}
        },
        "totals": ["qty_sold", "revenue", "margin"]
    }
}');

-- Template: Monthly Sales Trend
INSERT INTO report_template (template_code, template_name, template_description, template_category, report_config) VALUES
('TMPL_MONTHLY_SALES_TREND', 'Monthly Sales Trend', '12-month sales trend analysis', 'Sales', '{
    "base_query": "SELECT d.month_name, d.year_actual, SUM(fs.revenue) as revenue, SUM(fs.quantity) as quantity, COUNT(DISTINCT fs.customer_sk) as customers FROM fact_sales fs JOIN dim_date d ON fs.date_sk = d.date_sk WHERE d.date_actual >= CURRENT_DATE - INTERVAL ''12 months'' GROUP BY d.year_actual, d.month_of_year, d.month_name ORDER BY d.year_actual, d.month_of_year",
    "chart_config": {"type": "line", "x_axis": "month_name", "y_axis": "revenue", "title": "Monthly Revenue Trend"}
}');
```

### 4.2 Inventory Report Templates

```sql
-- Template: Stock Status Report
INSERT INTO report_template (template_code, template_name, template_description, template_category, report_config) VALUES
('TMPL_STOCK_STATUS', 'Stock Status Report', 'Current inventory levels with reorder alerts', 'Inventory', '{
    "base_query": "SELECT p.product_code, p.product_name, w.warehouse_name, fis.quantity_on_hand, fis.quantity_available, fis.reorder_point, fis.days_of_supply, CASE WHEN fis.is_below_reorder_point THEN ''REORDER'' WHEN fis.is_below_safety_stock THEN ''CRITICAL'' ELSE ''OK'' END as status FROM fact_daily_inventory_snapshot fis JOIN dim_product p ON fis.product_sk = p.product_sk JOIN dim_warehouse w ON fis.warehouse_sk = w.warehouse_sk JOIN dim_date d ON fis.date_sk = d.date_sk WHERE d.date_actual = CURRENT_DATE ORDER BY status DESC, days_of_supply",
    "layout_config": {
        "columns": {
            "quantity_on_hand": {"label": "On Hand", "format": "number"},
            "reorder_point": {"label": "Reorder Point", "format": "number"},
            "days_of_supply": {"label": "Days Supply", "format": "number"}
        },
        "conditional_formatting": [
            {"column": "status", "value": "CRITICAL", "style": {"background": "#e74c3c", "color": "white"}},
            {"column": "status", "value": "REORDER", "style": {"background": "#f39c12"}}
        ]
    }
}');
```

---

## 5. Daily Development Schedule

### Day 581-590: Custom Reports Implementation

| Day | Focus Area | Key Deliverables |
|-----|------------|------------------|
| 581 | Schema Design | Report configuration tables, audit logging |
| 582 | Report Builder Core | Query execution, data transformation |
| 583 | PDF Generation | Template engine, PDF rendering |
| 584 | Excel Generation | Workbook creation, formatting, charts |
| 585 | CSV/Export | Multi-format export, download API |
| 586 | Scheduling Engine | Schedule management, execution engine |
| 587 | Distribution | Email, SFTP, storage distribution |
| 588 | Template Library | Pre-built templates, customization |
| 589 | Self-Service Portal | UI integration, Superset embedding |
| 590 | Testing & Documentation | Unit tests, user documentation |

---

## 6. Requirement Traceability Matrix

| Requirement ID | Requirement Description | Implementation | Test Case |
|----------------|------------------------|----------------|-----------|
| RFP-RPT-001 | Self-service reporting | Report Builder API, Template Library | TC-109-001 |
| RFP-RPT-002 | Automated scheduling | ReportScheduler, Schedule API | TC-109-002 |
| BRD-DATA-001 | Data democratization | Self-Service Portal, Template Library | TC-109-003 |
| SRS-ANALYTICS-040 | Custom report builder | ReportService, Visual Builder UI | TC-109-004 |
| SRS-ANALYTICS-041 | Report scheduling engine | Schedule tables, Execution engine | TC-109-005 |
| SRS-ANALYTICS-042 | Multi-format export | PDF/Excel/CSV generators | TC-109-006 |

---

## 7. Quality Assurance Checklist

- [ ] Report builder creates valid reports
- [ ] All export formats render correctly
- [ ] Scheduled reports execute on time
- [ ] Email distribution working
- [ ] Access control enforced
- [ ] Audit logging complete
- [ ] Template library accessible
- [ ] Performance benchmarks met

---

**Document End**

*Milestone 109: Custom Reports and Self-Service Analytics - Days 581-590*
