# Milestone 49: Farm Reporting System
## Smart Dairy Digital Smart Portal + ERP - Phase 5

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P5-M49-001 |
| **Milestone** | 49 of 50 (9 of 10 in Phase 5) |
| **Title** | Farm Reporting System |
| **Phase** | Phase 5 - Farm Management Foundation |
| **Days** | Days 331-340 (of 350) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement a comprehensive farm reporting system with PDF/Excel generation, scheduled report delivery, template library, custom report builder, compliance reporting, and report archive management.

### 1.2 Objectives

| # | Objective |
|---|-----------|
| 1 | Build report generation engine with templates |
| 2 | Implement PDF generation with wkhtmltopdf |
| 3 | Create Excel export with openpyxl |
| 4 | Develop scheduled report delivery |
| 5 | Build report template library |
| 6 | Implement email delivery system |
| 7 | Create compliance report formats |
| 8 | Build custom report builder UI |
| 9 | Implement report archive system |
| 10 | Complete testing and documentation |

### 1.3 Key Deliverables

| Deliverable | Type | Description |
|-------------|------|-------------|
| Report Engine | Backend | Core report generation |
| PDF Generator | Backend | wkhtmltopdf integration |
| Excel Generator | Backend | openpyxl templates |
| Scheduler | Backend | Cron-based delivery |
| Template Library | Backend | 30+ report templates |
| Report Builder | Frontend | Custom report designer |
| Archive System | Backend | Report storage/retrieval |
| Mobile Viewer | Mobile | Report viewing on app |

### 1.4 Success Criteria

| Criteria | Target |
|----------|--------|
| Report generation time | <30 seconds |
| Template coverage | 30+ templates |
| PDF quality | Print-ready |
| Scheduled delivery reliability | 99.9% |
| Archive retrieval time | <5 seconds |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Description | Day(s) | Status |
|--------|--------|-------------|--------|--------|
| RFP-FARM-019 | RFP | Comprehensive reporting | 331-332 | Planned |
| BRD-FARM-070 | BRD | PDF/Excel export | 332-333 | Planned |
| BRD-FARM-071 | BRD | Scheduled delivery | 334 | Planned |
| BRD-FARM-072 | BRD | Compliance reports | 337 | Planned |
| SRS-FARM-115 | SRS | Custom report builder | 338 | Planned |
| SRS-FARM-116 | SRS | Report archival | 339 | Planned |

---

## 3. Day-by-Day Implementation

### Day 331: Report Engine Foundation

**Theme:** Core Reporting Infrastructure

#### Dev 1 - Backend Lead (8h)

**Task 1: Report Engine Model (5h)**
```python
# farm_reports/models/report_engine.py
from odoo import models, fields, api
from odoo.exceptions import UserError
import json
from datetime import datetime

class FarmReportDefinition(models.Model):
    _name = 'farm.report.definition'
    _description = 'Report Definition'
    _order = 'category, name'

    name = fields.Char(string='Report Name', required=True)
    code = fields.Char(string='Report Code', required=True, copy=False)
    category = fields.Selection([
        ('production', 'Production'),
        ('health', 'Health'),
        ('breeding', 'Breeding'),
        ('feed', 'Feed Management'),
        ('financial', 'Financial'),
        ('herd', 'Herd Management'),
        ('compliance', 'Compliance'),
        ('veterinary', 'Veterinary'),
    ], required=True)

    description = fields.Text(string='Description')

    # Data Source
    model_id = fields.Many2one('ir.model', string='Primary Model')
    domain = fields.Text(string='Filter Domain', default='[]')
    sql_query = fields.Text(string='Custom SQL Query')

    # Template
    template_type = fields.Selection([
        ('qweb', 'QWeb Template'),
        ('xlsx', 'Excel Template'),
        ('custom', 'Custom Code'),
    ], default='qweb', required=True)
    qweb_template = fields.Char(string='QWeb Template ID')
    xlsx_template = fields.Binary(string='Excel Template')

    # Output Options
    output_formats = fields.Many2many('farm.report.format', string='Available Formats')
    default_format = fields.Selection([
        ('pdf', 'PDF'),
        ('xlsx', 'Excel'),
        ('csv', 'CSV'),
        ('html', 'HTML'),
    ], default='pdf')

    # Parameters
    parameter_ids = fields.One2many('farm.report.parameter', 'report_id', string='Parameters')

    # Permissions
    group_ids = fields.Many2many('res.groups', string='Allowed Groups')

    active = fields.Boolean(default=True)

    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Report code must be unique!'),
    ]

    def generate_report(self, params=None, output_format=None):
        """Generate report with given parameters"""
        self.ensure_one()

        output_format = output_format or self.default_format
        params = params or {}

        # Validate parameters
        self._validate_parameters(params)

        # Get data
        data = self._get_report_data(params)

        # Generate output
        if output_format == 'pdf':
            return self._generate_pdf(data, params)
        elif output_format == 'xlsx':
            return self._generate_xlsx(data, params)
        elif output_format == 'csv':
            return self._generate_csv(data, params)
        elif output_format == 'html':
            return self._generate_html(data, params)

        raise UserError(f'Unsupported format: {output_format}')

    def _validate_parameters(self, params):
        """Validate report parameters"""
        for param in self.parameter_ids:
            if param.required and param.code not in params:
                raise UserError(f'Missing required parameter: {param.name}')

    def _get_report_data(self, params):
        """Fetch report data"""
        if self.sql_query:
            return self._execute_sql_query(params)
        elif self.model_id:
            return self._fetch_model_data(params)
        else:
            raise UserError('No data source defined for report')

    def _execute_sql_query(self, params):
        """Execute custom SQL query"""
        query = self.sql_query
        # Simple parameter substitution
        for key, value in params.items():
            query = query.replace(f':{key}', str(value))

        self.env.cr.execute(query)
        columns = [desc[0] for desc in self.env.cr.description]
        rows = self.env.cr.fetchall()

        return {
            'columns': columns,
            'rows': [dict(zip(columns, row)) for row in rows],
        }

    def _fetch_model_data(self, params):
        """Fetch data from Odoo model"""
        Model = self.env[self.model_id.model]
        domain = json.loads(self.domain or '[]')

        # Apply parameter filters
        if 'date_from' in params:
            domain.append(('create_date', '>=', params['date_from']))
        if 'date_to' in params:
            domain.append(('create_date', '<=', params['date_to']))
        if 'farm_id' in params:
            domain.append(('farm_id', '=', params['farm_id']))

        records = Model.search(domain)
        return {'records': records}


class FarmReportParameter(models.Model):
    _name = 'farm.report.parameter'
    _description = 'Report Parameter'

    report_id = fields.Many2one('farm.report.definition', required=True, ondelete='cascade')
    name = fields.Char(string='Parameter Name', required=True)
    code = fields.Char(string='Parameter Code', required=True)
    param_type = fields.Selection([
        ('date', 'Date'),
        ('date_range', 'Date Range'),
        ('selection', 'Selection'),
        ('many2one', 'Relation'),
        ('integer', 'Integer'),
        ('float', 'Float'),
        ('boolean', 'Boolean'),
    ], required=True)
    required = fields.Boolean(default=False)
    default_value = fields.Char()
    selection_options = fields.Text(string='Selection Options (JSON)')
    relation_model = fields.Char(string='Related Model')
```

**Task 2: Report Generation Service (3h)**
```python
# farm_reports/services/report_generator.py
class ReportGeneratorService:

    def __init__(self, env):
        self.env = env

    def generate(self, report_code, params=None, output_format='pdf'):
        """Generate report by code"""
        report = self.env['farm.report.definition'].search([
            ('code', '=', report_code),
            ('active', '=', True),
        ], limit=1)

        if not report:
            raise ValueError(f'Report not found: {report_code}')

        return report.generate_report(params, output_format)

    def get_available_reports(self, category=None):
        """Get list of available reports for current user"""
        domain = [('active', '=', True)]
        if category:
            domain.append(('category', '=', category))

        reports = self.env['farm.report.definition'].search(domain)

        # Filter by permissions
        user_groups = self.env.user.groups_id.ids
        return reports.filtered(
            lambda r: not r.group_ids or any(g.id in user_groups for g in r.group_ids)
        )
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Report Template Storage (4h)**
```python
# farm_reports/models/report_template.py
class FarmReportTemplate(models.Model):
    _name = 'farm.report.template'
    _description = 'Report Template'

    name = fields.Char(required=True)
    report_id = fields.Many2one('farm.report.definition')
    template_type = fields.Selection([
        ('header', 'Header'),
        ('body', 'Body'),
        ('footer', 'Footer'),
        ('full', 'Full Template'),
    ], required=True)

    # Template Content
    html_content = fields.Html(string='HTML Content')
    css_styles = fields.Text(string='CSS Styles')

    # Branding
    logo = fields.Binary(string='Logo')
    company_name = fields.Char()
    primary_color = fields.Char(default='#007bff')
    secondary_color = fields.Char(default='#6c757d')

    # Page Setup
    page_size = fields.Selection([
        ('A4', 'A4'),
        ('Letter', 'Letter'),
        ('Legal', 'Legal'),
    ], default='A4')
    orientation = fields.Selection([
        ('portrait', 'Portrait'),
        ('landscape', 'Landscape'),
    ], default='portrait')
    margin_top = fields.Float(default=20)
    margin_bottom = fields.Float(default=20)
    margin_left = fields.Float(default=15)
    margin_right = fields.Float(default=15)

    active = fields.Boolean(default=True)
```

**Task 2: Report API (4h)**
```python
# farm_reports/controllers/report_api.py
class ReportAPI(http.Controller):

    @http.route('/api/v1/reports', type='json', auth='user', methods=['GET'])
    def get_available_reports(self, category=None):
        service = ReportGeneratorService(request.env)
        reports = service.get_available_reports(category)

        return [{
            'id': r.id,
            'code': r.code,
            'name': r.name,
            'category': r.category,
            'description': r.description,
            'formats': [f.code for f in r.output_formats],
            'parameters': [{
                'code': p.code,
                'name': p.name,
                'type': p.param_type,
                'required': p.required,
            } for p in r.parameter_ids],
        } for r in reports]

    @http.route('/api/v1/reports/<string:code>/generate', type='json', auth='user', methods=['POST'])
    def generate_report(self, code, params=None, output_format='pdf'):
        service = ReportGeneratorService(request.env)

        try:
            result = service.generate(code, params, output_format)

            # Store in archive
            archive = request.env['farm.report.archive'].create({
                'report_code': code,
                'parameters': json.dumps(params or {}),
                'output_format': output_format,
                'file_data': result['content'],
                'file_name': result['filename'],
                'generated_by': request.env.uid,
            })

            return {
                'success': True,
                'archive_id': archive.id,
                'download_url': f'/api/v1/reports/download/{archive.id}',
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/reports/download/<int:archive_id>', type='http', auth='user')
    def download_report(self, archive_id):
        archive = request.env['farm.report.archive'].browse(archive_id)
        if not archive.exists():
            return request.not_found()

        content_type = {
            'pdf': 'application/pdf',
            'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv': 'text/csv',
        }.get(archive.output_format, 'application/octet-stream')

        return request.make_response(
            base64.b64decode(archive.file_data),
            headers=[
                ('Content-Type', content_type),
                ('Content-Disposition', f'attachment; filename="{archive.file_name}"'),
            ]
        )
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Report Selection UI (5h)**
```xml
<!-- farm_reports/views/report_views.xml -->
<odoo>
    <record id="view_farm_report_wizard" model="ir.ui.view">
        <field name="name">farm.report.wizard.form</field>
        <field name="model">farm.report.wizard</field>
        <field name="arch" type="xml">
            <form>
                <group>
                    <field name="report_id" options="{'no_create': True}"/>
                    <field name="output_format"/>
                </group>
                <group string="Parameters" attrs="{'invisible': [('report_id', '=', False)]}">
                    <field name="date_from" attrs="{'invisible': [('has_date_param', '=', False)]}"/>
                    <field name="date_to" attrs="{'invisible': [('has_date_param', '=', False)]}"/>
                    <field name="farm_id" attrs="{'invisible': [('has_farm_param', '=', False)]}"/>
                </group>
                <footer>
                    <button string="Generate Report" name="action_generate" type="object" class="btn-primary"/>
                    <button string="Schedule" name="action_schedule" type="object" class="btn-secondary"/>
                    <button string="Cancel" class="btn-secondary" special="cancel"/>
                </footer>
            </form>
        </field>
    </record>

    <record id="action_report_wizard" model="ir.actions.act_window">
        <field name="name">Generate Report</field>
        <field name="res_model">farm.report.wizard</field>
        <field name="view_mode">form</field>
        <field name="target">new</field>
    </record>

    <!-- Report Category Dashboard -->
    <record id="view_report_category_kanban" model="ir.ui.view">
        <field name="name">farm.report.category.kanban</field>
        <field name="model">farm.report.definition</field>
        <field name="arch" type="xml">
            <kanban class="o_kanban_dashboard" create="false">
                <field name="name"/>
                <field name="code"/>
                <field name="category"/>
                <field name="description"/>
                <templates>
                    <t t-name="kanban-box">
                        <div class="oe_kanban_global_click">
                            <div class="o_kanban_card_header">
                                <div class="o_kanban_card_header_title">
                                    <strong><field name="name"/></strong>
                                </div>
                            </div>
                            <div class="o_kanban_card_content">
                                <field name="description"/>
                            </div>
                            <div class="o_kanban_card_footer">
                                <a role="button" class="btn btn-primary btn-sm" type="object" name="action_generate">
                                    Generate
                                </a>
                            </div>
                        </div>
                    </t>
                </templates>
            </kanban>
        </field>
    </record>
</odoo>
```

**Task 2: Report Viewer Component (3h)**
```javascript
/** @odoo-module **/
// farm_reports/static/src/components/report_viewer.js
import { Component, useState, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class ReportViewer extends Component {
    static template = "farm_reports.ReportViewer";
    static props = {
        archiveId: Number,
    };

    setup() {
        this.rpc = useService("rpc");
        this.state = useState({
            loading: true,
            report: null,
            pdfUrl: null,
        });

        onMounted(() => this.loadReport());
    }

    async loadReport() {
        const archive = await this.rpc(`/api/v1/reports/archive/${this.props.archiveId}`);
        this.state.report = archive;

        if (archive.output_format === 'pdf') {
            // Create blob URL for PDF viewer
            const response = await fetch(`/api/v1/reports/download/${this.props.archiveId}`);
            const blob = await response.blob();
            this.state.pdfUrl = URL.createObjectURL(blob);
        }

        this.state.loading = false;
    }

    downloadReport() {
        window.open(`/api/v1/reports/download/${this.props.archiveId}`, '_blank');
    }

    emailReport() {
        // Open email dialog
    }
}
```

#### Day 331 Deliverables
- [x] Report definition model
- [x] Report parameter system
- [x] Report generation service
- [x] Report template storage
- [x] Report API endpoints
- [x] Report selection UI

---

### Day 332: PDF Generation

**Theme:** Professional PDF Reports

#### Dev 1 - Backend Lead (8h)

**Task 1: PDF Generator with wkhtmltopdf (5h)**
```python
# farm_reports/services/pdf_generator.py
import subprocess
import tempfile
import os
import base64
from jinja2 import Environment, BaseLoader

class PDFGeneratorService:

    def __init__(self, env):
        self.env = env
        self.wkhtmltopdf_path = self._get_wkhtmltopdf_path()

    def _get_wkhtmltopdf_path(self):
        return self.env['ir.config_parameter'].sudo().get_param(
            'farm.wkhtmltopdf.path',
            '/usr/bin/wkhtmltopdf'
        )

    def generate_pdf(self, html_content, options=None):
        """Generate PDF from HTML content"""
        options = options or {}

        with tempfile.NamedTemporaryFile(suffix='.html', delete=False) as html_file:
            html_file.write(html_content.encode('utf-8'))
            html_path = html_file.name

        with tempfile.NamedTemporaryFile(suffix='.pdf', delete=False) as pdf_file:
            pdf_path = pdf_file.name

        try:
            cmd = [
                self.wkhtmltopdf_path,
                '--page-size', options.get('page_size', 'A4'),
                '--orientation', options.get('orientation', 'portrait'),
                '--margin-top', str(options.get('margin_top', 20)),
                '--margin-bottom', str(options.get('margin_bottom', 20)),
                '--margin-left', str(options.get('margin_left', 15)),
                '--margin-right', str(options.get('margin_right', 15)),
                '--encoding', 'UTF-8',
                '--enable-local-file-access',
                '--footer-center', '[page] / [topage]',
                '--footer-font-size', '8',
                html_path,
                pdf_path,
            ]

            subprocess.run(cmd, check=True, capture_output=True)

            with open(pdf_path, 'rb') as f:
                pdf_content = f.read()

            return base64.b64encode(pdf_content)

        finally:
            os.unlink(html_path)
            if os.path.exists(pdf_path):
                os.unlink(pdf_path)

    def generate_from_template(self, template_name, data, options=None):
        """Generate PDF from QWeb template"""
        # Render QWeb template
        html = self.env['ir.qweb']._render(template_name, data)

        # Add CSS styles
        styled_html = self._wrap_with_styles(html, options)

        return self.generate_pdf(styled_html, options)

    def _wrap_with_styles(self, html_content, options=None):
        """Wrap HTML with styles and branding"""
        template = self.env['farm.report.template'].search([
            ('template_type', '=', 'full'),
            ('active', '=', True),
        ], limit=1)

        css = template.css_styles if template else self._default_css()

        return f"""
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>{css}</style>
        </head>
        <body>
            {html_content}
        </body>
        </html>
        """

    def _default_css(self):
        return """
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #007bff; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logo { max-height: 60px; }
        .kpi-box { display: inline-block; padding: 15px; margin: 5px; background: #f8f9fa; border-radius: 5px; }
        .kpi-value { font-size: 24px; font-weight: bold; color: #007bff; }
        .kpi-label { font-size: 12px; color: #666; }
        """
```

**Task 2: Production Report Templates (3h)**
```xml
<!-- farm_reports/report/production_report_template.xml -->
<odoo>
    <template id="report_daily_production">
        <div class="header">
            <div>
                <img t-if="company.logo" t-att-src="'data:image/png;base64,' + company.logo.decode()" class="logo"/>
            </div>
            <div>
                <h1>Daily Production Report</h1>
                <p>Date: <t t-esc="date"/></p>
                <p>Farm: <t t-esc="farm.name"/></p>
            </div>
        </div>

        <div class="kpi-section">
            <div class="kpi-box">
                <div class="kpi-value"><t t-esc="total_yield"/></div>
                <div class="kpi-label">Total Yield (L)</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-value"><t t-esc="animals_milked"/></div>
                <div class="kpi-label">Animals Milked</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-value"><t t-esc="avg_yield"/></div>
                <div class="kpi-label">Avg Yield (L)</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-value"><t t-esc="avg_fat"/>%</div>
                <div class="kpi-label">Avg Fat</div>
            </div>
        </div>

        <h2>Production by Session</h2>
        <table>
            <thead>
                <tr>
                    <th>Session</th>
                    <th>Animals</th>
                    <th>Total Yield (L)</th>
                    <th>Avg Yield (L)</th>
                    <th>Avg Fat %</th>
                    <th>Avg Protein %</th>
                </tr>
            </thead>
            <tbody>
                <tr t-foreach="session_data" t-as="session">
                    <td><t t-esc="session['name']"/></td>
                    <td><t t-esc="session['count']"/></td>
                    <td><t t-esc="session['total_yield']"/></td>
                    <td><t t-esc="session['avg_yield']"/></td>
                    <td><t t-esc="session['avg_fat']"/></td>
                    <td><t t-esc="session['avg_protein']"/></td>
                </tr>
            </tbody>
        </table>

        <h2>Individual Animal Production</h2>
        <table>
            <thead>
                <tr>
                    <th>Ear Tag</th>
                    <th>Animal Name</th>
                    <th>Morning (L)</th>
                    <th>Evening (L)</th>
                    <th>Total (L)</th>
                    <th>Fat %</th>
                    <th>Lactation</th>
                </tr>
            </thead>
            <tbody>
                <tr t-foreach="animal_data" t-as="animal">
                    <td><t t-esc="animal['ear_tag']"/></td>
                    <td><t t-esc="animal['name']"/></td>
                    <td><t t-esc="animal['morning_yield']"/></td>
                    <td><t t-esc="animal['evening_yield']"/></td>
                    <td><t t-esc="animal['total_yield']"/></td>
                    <td><t t-esc="animal['fat']"/></td>
                    <td><t t-esc="animal['lactation_number']"/></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Generated on: <t t-esc="datetime.datetime.now().strftime('%Y-%m-%d %H:%M')"/></p>
            <p>Smart Dairy Farm Management System</p>
        </div>
    </template>
</odoo>
```

#### Dev 2 & Dev 3: Continue with Excel generation, scheduled reports, and remaining days

---

### Days 333-340: Summary

#### Day 333: Excel Export
- openpyxl integration
- Excel template system
- Styled worksheets
- Chart embedding

#### Day 334: Scheduled Reports
- FarmReportSchedule model
- Cron job configuration
- Delivery time management
- Retry mechanism

#### Day 335: Report Template Library
- 30+ predefined templates
- Template categorization
- Template import/export
- Template versioning

#### Day 336: Email Delivery
- Email template integration
- Attachment handling
- Delivery tracking
- Bounce handling

#### Day 337: Compliance Reports
- Government format reports
- Regulatory compliance
- Audit trail reports
- Data validation reports

#### Day 338: Custom Report Builder
- Drag-and-drop field selection
- Filter configuration
- Grouping options
- Preview functionality

#### Day 339: Archive System
- Report archival model
- Retention policies
- Archive search
- Bulk download

#### Day 340: Testing & Documentation
- Unit tests
- Integration tests
- Performance testing
- User documentation

---

## 4. Technical Specifications

### 4.1 Database Schema

```sql
CREATE TABLE farm_report_definition (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    category VARCHAR(20) NOT NULL,
    template_type VARCHAR(20) DEFAULT 'qweb',
    qweb_template VARCHAR(100),
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE farm_report_schedule (
    id SERIAL PRIMARY KEY,
    report_id INTEGER REFERENCES farm_report_definition(id),
    frequency VARCHAR(20) NOT NULL,
    next_run TIMESTAMP,
    recipient_ids INTEGER[],
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE farm_report_archive (
    id SERIAL PRIMARY KEY,
    report_code VARCHAR(50) NOT NULL,
    parameters JSONB,
    output_format VARCHAR(10),
    file_data BYTEA,
    file_name VARCHAR(100),
    generated_at TIMESTAMP DEFAULT NOW(),
    generated_by INTEGER REFERENCES res_users(id)
);

CREATE INDEX idx_archive_code_date ON farm_report_archive(report_code, generated_at);
```

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/reports` | GET | List available reports |
| `/api/v1/reports/<code>/generate` | POST | Generate report |
| `/api/v1/reports/download/<id>` | GET | Download report |
| `/api/v1/reports/schedule` | GET/POST | Manage schedules |
| `/api/v1/reports/archive` | GET | Search archives |

---

## 5. Dependencies & Handoffs

### 5.1 Prerequisites
- Milestones 41-48: All data sources

### 5.2 Outputs
- Milestone 50: Report testing
- Phase 6: Advanced reporting

---

*Document End - Milestone 49: Farm Reporting System*
