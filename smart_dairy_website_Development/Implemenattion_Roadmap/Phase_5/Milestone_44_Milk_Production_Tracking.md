# Milestone 44: Milk Production Tracking

## Smart Dairy Digital Smart Portal + ERP - Phase 5: Farm Management Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 44 of 150 (4 of 10 in Phase 5)                                         |
| **Title**        | Milk Production Tracking                                               |
| **Phase**        | Phase 5 - Farm Management Foundation                                   |
| **Days**         | Days 281-290 (of 350 total)                                            |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Mobile Lead)          |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement comprehensive milk production tracking using TimescaleDB for time-series data, enabling Smart Dairy to record daily yields, monitor quality parameters, analyze lactation curves, and optimize individual animal and herd production performance.

### 1.2 Objectives

1. Create FarmMilkProduction model with TimescaleDB hypertable
2. Implement batch/individual yield recording system
3. Build quality parameter tracking (fat, protein, SCC, SNF)
4. Create lactation record and period management
5. Implement lactation curve analysis with standard curves
6. Build production target setting and comparison
7. Create yield analytics and trend visualization
8. Implement quality alert triggers
9. Build historical trend analysis
10. Develop mobile production entry interface

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| FarmMilkProduction Model        | Dev 1  | TimescaleDB       | 281     |
| Batch Entry System              | Dev 1  | Python module     | 282     |
| Quality Parameters Model        | Dev 1  | Python module     | 283     |
| Lactation Record Model          | Dev 1  | Python module     | 284     |
| Production Target System        | Dev 1  | Python module     | 285     |
| Yield Analytics Service         | Dev 2  | Python service    | 286     |
| Quality Alert Engine            | Dev 2  | Python service    | 287     |
| Historical Trends               | Dev 2  | Python service    | 288     |
| IoT Integration Prep            | Dev 2  | API endpoints     | 289     |
| Flutter Production Entry UI     | Dev 3  | Dart/Flutter      | 290     |

### 1.4 Prerequisites

- Milestone 41 complete (FarmAnimal model)
- Milestone 43 complete (Breeding/lactation status)
- TimescaleDB extension configured
- PostgreSQL 16 with time-series optimization

### 1.5 Success Criteria

- [ ] Production data stored in TimescaleDB hypertable
- [ ] Batch entry supports 100+ animals in <10 seconds
- [ ] Quality parameters validated against thresholds
- [ ] Lactation curves calculated for all lactating cows
- [ ] Production alerts triggered within 5 minutes
- [ ] Mobile entry works offline with sync
- [ ] Historical queries execute in <2 seconds
- [ ] Unit test coverage >80%

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-FARM-010 | RFP    | Daily milk yield recording               | 281-282 | FarmMilkProduction    |
| RFP-FARM-011 | RFP    | Quality parameter tracking               | 283     | Quality parameters    |
| RFP-FARM-012 | RFP    | Lactation curve analysis                 | 284     | Lactation curves      |
| BRD-FM-004   | BRD    | 15% increase in milk production          | 281-290 | Complete milestone    |
| SRS-FM-008   | SRS    | TimescaleDB production data              | 281     | Hypertable setup      |
| SRS-FM-009   | SRS    | Lactation curve calculations             | 284     | Curve analysis        |

---

## 3. Day-by-Day Breakdown

### Day 281 - Production Model with TimescaleDB

**Objective:** Create TimescaleDB-optimized milk production model for high-frequency time-series data.

#### Dev 1 - Backend Lead (8h)

**Task 1: Create FarmMilkProduction Model (6h)**

```python
# odoo/addons/smart_dairy_farm/models/farm_milk_production.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import date, datetime, timedelta

class FarmMilkProduction(models.Model):
    _name = 'farm.milk.production'
    _description = 'Farm Milk Production Record'
    _order = 'production_date desc, milking_session, animal_id'
    _rec_name = 'display_name'

    # Note: This table will be converted to TimescaleDB hypertable
    # using post-init SQL migration

    # ===== IDENTIFICATION =====
    display_name = fields.Char(compute='_compute_display_name', store=True)

    # ===== ANIMAL & FARM =====
    animal_id = fields.Many2one(
        'farm.animal',
        string='Animal',
        required=True,
        index=True,
        domain="[('gender', '=', 'female'), ('status', 'in', ['active', 'lactating'])]"
    )
    animal_ear_tag = fields.Char(related='animal_id.ear_tag', store=True)
    farm_id = fields.Many2one(related='animal_id.farm_id', store=True, index=True)
    breed_id = fields.Many2one(related='animal_id.breed_id', store=True)

    # ===== PRODUCTION DATE & TIME =====
    production_date = fields.Date(
        string='Date',
        required=True,
        default=fields.Date.today,
        index=True
    )
    production_datetime = fields.Datetime(
        string='Date/Time',
        compute='_compute_datetime',
        store=True,
        index=True
    )
    milking_session = fields.Selection([
        ('morning', 'Morning'),
        ('evening', 'Evening'),
        ('noon', 'Noon'),
    ], string='Session', required=True, default='morning')
    milking_time = fields.Float(string='Milking Time (24h)')

    # ===== PRODUCTION DATA =====
    yield_liters = fields.Float(
        string='Yield (Liters)',
        required=True,
        digits=(8, 2)
    )
    yield_kg = fields.Float(
        compute='_compute_yield_kg',
        store=True,
        digits=(8, 2)
    )
    milking_duration_minutes = fields.Integer(string='Milking Duration (min)')

    # ===== QUALITY PARAMETERS =====
    fat_percentage = fields.Float(
        string='Fat %',
        digits=(4, 2),
        help='Normal range: 3.0-5.5%'
    )
    protein_percentage = fields.Float(
        string='Protein %',
        digits=(4, 2),
        help='Normal range: 2.8-4.0%'
    )
    snf_percentage = fields.Float(
        string='SNF %',
        digits=(4, 2),
        help='Solids-Not-Fat, Normal: 8.0-9.5%'
    )
    lactose_percentage = fields.Float(
        string='Lactose %',
        digits=(4, 2),
        help='Normal range: 4.5-5.0%'
    )
    somatic_cell_count = fields.Integer(
        string='SCC (cells/ml)',
        help='Somatic Cell Count. Healthy: <200,000'
    )
    scc_category = fields.Selection([
        ('excellent', 'Excellent (<100,000)'),
        ('good', 'Good (100,000-200,000)'),
        ('acceptable', 'Acceptable (200,000-400,000)'),
        ('high', 'High (400,000-750,000)'),
        ('very_high', 'Very High (>750,000)'),
    ], compute='_compute_scc_category', store=True)
    total_solids = fields.Float(
        compute='_compute_total_solids',
        store=True,
        digits=(4, 2)
    )
    bacteria_count = fields.Integer(string='Bacteria Count (CFU/ml)')
    temperature_celsius = fields.Float(string='Milk Temperature (°C)', digits=(4, 1))
    conductivity = fields.Float(string='Conductivity (mS/cm)', digits=(5, 2))
    ph_value = fields.Float(string='pH Value', digits=(3, 2))

    # ===== QUALITY GRADES =====
    quality_grade = fields.Selection([
        ('premium', 'Premium'),
        ('grade_a', 'Grade A'),
        ('grade_b', 'Grade B'),
        ('grade_c', 'Grade C'),
        ('rejected', 'Rejected'),
    ], compute='_compute_quality_grade', store=True)

    # ===== LACTATION REFERENCE =====
    lactation_id = fields.Many2one(
        'farm.lactation.record',
        string='Lactation Period',
        index=True
    )
    lactation_number = fields.Integer(
        related='lactation_id.lactation_number',
        store=True
    )
    days_in_milk = fields.Integer(
        string='DIM',
        compute='_compute_days_in_milk',
        store=True,
        help='Days in Milk since calving'
    )

    # ===== COLLECTION INFO =====
    collection_batch_id = fields.Many2one(
        'farm.milk.collection.batch',
        string='Collection Batch'
    )
    milk_destination = fields.Selection([
        ('tank', 'Bulk Tank'),
        ('calf', 'Calf Feeding'),
        ('discarded', 'Discarded'),
        ('testing', 'Lab Testing'),
        ('home_use', 'Home Use'),
    ], string='Destination', default='tank')
    discard_reason = fields.Selection([
        ('antibiotic', 'Antibiotic Treatment'),
        ('mastitis', 'Mastitis'),
        ('colostrum', 'Colostrum'),
        ('contaminated', 'Contaminated'),
        ('other', 'Other'),
    ])

    # ===== RECORDING INFO =====
    recorded_by = fields.Many2one(
        'res.users',
        string='Recorded By',
        default=lambda self: self.env.user
    )
    recording_method = fields.Selection([
        ('manual', 'Manual Entry'),
        ('meter', 'Milk Meter'),
        ('iot', 'IoT Sensor'),
        ('import', 'Bulk Import'),
    ], default='manual')
    meter_reading = fields.Float(string='Meter Reading')
    iot_device_id = fields.Char(string='IoT Device ID')

    # ===== COMPARISON =====
    previous_yield = fields.Float(
        compute='_compute_yield_comparison',
        string='Previous Yield'
    )
    yield_change_percent = fields.Float(
        compute='_compute_yield_comparison',
        string='Change %'
    )
    expected_yield = fields.Float(
        compute='_compute_expected_yield',
        string='Expected Yield'
    )
    yield_deviation_percent = fields.Float(
        compute='_compute_expected_yield',
        string='Deviation %'
    )

    # ===== ALERTS =====
    alert_ids = fields.One2many(
        'farm.production.alert',
        'production_id',
        string='Alerts'
    )
    has_alerts = fields.Boolean(compute='_compute_has_alerts')

    # ===== NOTES =====
    notes = fields.Text(string='Notes')

    # ===== COMPANY =====
    company_id = fields.Many2one(
        'res.company',
        default=lambda self: self.env.company
    )

    # ===== SQL CONSTRAINTS =====
    _sql_constraints = [
        ('unique_animal_date_session',
         'unique(animal_id, production_date, milking_session)',
         'Only one record per animal per session per day!'),
        ('positive_yield',
         'CHECK(yield_liters >= 0)',
         'Yield cannot be negative!'),
    ]

    # ===== COMPUTED METHODS =====
    @api.depends('animal_id', 'production_date', 'milking_session')
    def _compute_display_name(self):
        for record in self:
            session_short = {'morning': 'AM', 'evening': 'PM', 'noon': 'NOON'}.get(record.milking_session, '')
            record.display_name = f"{record.animal_ear_tag} - {record.production_date} {session_short}"

    @api.depends('production_date', 'milking_session')
    def _compute_datetime(self):
        session_hours = {'morning': 6, 'noon': 12, 'evening': 18}
        for record in self:
            hour = session_hours.get(record.milking_session, 12)
            record.production_datetime = datetime.combine(
                record.production_date,
                datetime.min.time().replace(hour=hour)
            )

    @api.depends('yield_liters')
    def _compute_yield_kg(self):
        """Convert liters to kg (milk density ~1.03 kg/L)"""
        for record in self:
            record.yield_kg = record.yield_liters * 1.03

    @api.depends('somatic_cell_count')
    def _compute_scc_category(self):
        for record in self:
            scc = record.somatic_cell_count or 0
            if scc < 100000:
                record.scc_category = 'excellent'
            elif scc < 200000:
                record.scc_category = 'good'
            elif scc < 400000:
                record.scc_category = 'acceptable'
            elif scc < 750000:
                record.scc_category = 'high'
            else:
                record.scc_category = 'very_high'

    @api.depends('fat_percentage', 'snf_percentage')
    def _compute_total_solids(self):
        for record in self:
            record.total_solids = (record.fat_percentage or 0) + (record.snf_percentage or 0)

    @api.depends('fat_percentage', 'protein_percentage', 'somatic_cell_count', 'bacteria_count')
    def _compute_quality_grade(self):
        for record in self:
            fat = record.fat_percentage or 0
            protein = record.protein_percentage or 0
            scc = record.somatic_cell_count or 0
            bacteria = record.bacteria_count or 0

            # Premium: High fat/protein, low SCC
            if fat >= 4.0 and protein >= 3.2 and scc < 200000:
                record.quality_grade = 'premium'
            elif fat >= 3.5 and protein >= 3.0 and scc < 400000:
                record.quality_grade = 'grade_a'
            elif fat >= 3.0 and scc < 750000:
                record.quality_grade = 'grade_b'
            elif scc < 1000000:
                record.quality_grade = 'grade_c'
            else:
                record.quality_grade = 'rejected'

    @api.depends('lactation_id', 'production_date')
    def _compute_days_in_milk(self):
        for record in self:
            if record.lactation_id and record.lactation_id.start_date:
                record.days_in_milk = (record.production_date - record.lactation_id.start_date).days
            else:
                record.days_in_milk = 0

    def _compute_yield_comparison(self):
        """Compare to previous milking"""
        for record in self:
            previous = self.search([
                ('animal_id', '=', record.animal_id.id),
                ('production_date', '<', record.production_date),
            ], order='production_date desc, milking_session desc', limit=1)

            if previous:
                record.previous_yield = previous.yield_liters
                if previous.yield_liters:
                    change = ((record.yield_liters - previous.yield_liters) /
                              previous.yield_liters * 100)
                    record.yield_change_percent = round(change, 1)
                else:
                    record.yield_change_percent = 0
            else:
                record.previous_yield = 0
                record.yield_change_percent = 0

    def _compute_expected_yield(self):
        """Calculate expected yield based on lactation curve"""
        for record in self:
            if record.lactation_id and record.days_in_milk:
                # Get expected yield from lactation curve
                expected = record.lactation_id.get_expected_yield(record.days_in_milk)
                record.expected_yield = expected
                if expected:
                    deviation = ((record.yield_liters - expected) / expected * 100)
                    record.yield_deviation_percent = round(deviation, 1)
                else:
                    record.yield_deviation_percent = 0
            else:
                record.expected_yield = 0
                record.yield_deviation_percent = 0

    def _compute_has_alerts(self):
        for record in self:
            record.has_alerts = bool(record.alert_ids)

    # ===== CONSTRAINT METHODS =====
    @api.constrains('yield_liters')
    def _check_yield(self):
        for record in self:
            if record.yield_liters < 0:
                raise ValidationError('Yield cannot be negative!')
            if record.yield_liters > 100:
                raise ValidationError('Yield exceeds maximum expected (100L). Please verify.')

    @api.constrains('fat_percentage')
    def _check_fat(self):
        for record in self:
            if record.fat_percentage and (record.fat_percentage < 1 or record.fat_percentage > 10):
                raise ValidationError('Fat percentage must be between 1% and 10%')

    @api.constrains('production_date')
    def _check_date(self):
        for record in self:
            if record.production_date > date.today():
                raise ValidationError('Production date cannot be in the future!')

    # ===== CRUD METHODS =====
    @api.model
    def create(self, vals):
        record = super().create(vals)
        # Auto-assign lactation if not set
        if not record.lactation_id:
            lactation = self.env['farm.lactation.record'].search([
                ('animal_id', '=', record.animal_id.id),
                ('state', '=', 'active'),
            ], limit=1)
            if lactation:
                record.lactation_id = lactation.id

        # Check for alerts
        record._check_production_alerts()
        return record

    def _check_production_alerts(self):
        """Check and create production alerts"""
        Alert = self.env['farm.production.alert']
        for record in self:
            alerts = []

            # Low yield alert (>20% below expected)
            if record.expected_yield and record.yield_deviation_percent < -20:
                alerts.append({
                    'production_id': record.id,
                    'alert_type': 'low_yield',
                    'severity': 'high' if record.yield_deviation_percent < -40 else 'medium',
                    'message': f'Yield {abs(record.yield_deviation_percent):.1f}% below expected',
                })

            # High SCC alert
            if record.somatic_cell_count and record.somatic_cell_count > 400000:
                alerts.append({
                    'production_id': record.id,
                    'alert_type': 'high_scc',
                    'severity': 'high' if record.somatic_cell_count > 750000 else 'medium',
                    'message': f'SCC: {record.somatic_cell_count:,} cells/ml',
                })

            # Quality rejected
            if record.quality_grade == 'rejected':
                alerts.append({
                    'production_id': record.id,
                    'alert_type': 'quality_rejected',
                    'severity': 'critical',
                    'message': 'Milk quality rejected',
                })

            # Create alerts
            for alert_vals in alerts:
                Alert.create(alert_vals)


class FarmProductionAlert(models.Model):
    _name = 'farm.production.alert'
    _description = 'Production Alert'
    _order = 'create_date desc'

    production_id = fields.Many2one('farm.milk.production', required=True, ondelete='cascade')
    animal_id = fields.Many2one(related='production_id.animal_id', store=True)
    alert_type = fields.Selection([
        ('low_yield', 'Low Yield'),
        ('high_scc', 'High SCC'),
        ('quality_rejected', 'Quality Rejected'),
        ('sudden_drop', 'Sudden Production Drop'),
        ('mastitis_suspected', 'Mastitis Suspected'),
    ], required=True)
    severity = fields.Selection([
        ('low', 'Low'),
        ('medium', 'Medium'),
        ('high', 'High'),
        ('critical', 'Critical'),
    ], required=True, default='medium')
    message = fields.Text()
    acknowledged = fields.Boolean(default=False)
    acknowledged_by = fields.Many2one('res.users')
    acknowledged_date = fields.Datetime()
    action_taken = fields.Text()


class FarmLactationRecord(models.Model):
    _name = 'farm.lactation.record'
    _description = 'Lactation Period Record'
    _order = 'animal_id, lactation_number desc'

    animal_id = fields.Many2one('farm.animal', required=True, index=True)
    lactation_number = fields.Integer(required=True)
    calving_record_id = fields.Many2one('farm.calving.record')
    start_date = fields.Date(required=True)
    end_date = fields.Date()
    dry_off_date = fields.Date()
    expected_dry_off = fields.Date(compute='_compute_expected_dry_off')
    days_in_milk = fields.Integer(compute='_compute_days_in_milk', store=True)
    state = fields.Selection([
        ('active', 'Active (Lactating)'),
        ('dry', 'Dry'),
        ('completed', 'Completed'),
    ], default='active')

    # Production Summary
    total_yield_liters = fields.Float(compute='_compute_production_summary', store=True)
    peak_yield_liters = fields.Float(compute='_compute_production_summary', store=True)
    peak_yield_dim = fields.Integer(compute='_compute_production_summary', store=True)
    average_daily_yield = fields.Float(compute='_compute_production_summary', store=True)
    production_ids = fields.One2many('farm.milk.production', 'lactation_id')

    # Quality Summary
    average_fat_percent = fields.Float(compute='_compute_quality_summary')
    average_protein_percent = fields.Float(compute='_compute_quality_summary')
    average_scc = fields.Float(compute='_compute_quality_summary')

    # Lactation Curve
    curve_type = fields.Selection([
        ('wood', 'Wood Model'),
        ('wilmink', 'Wilmink Model'),
        ('actual', 'Actual Data'),
    ], default='wood')
    curve_parameter_a = fields.Float(help='Initial yield parameter')
    curve_parameter_b = fields.Float(help='Incline parameter')
    curve_parameter_c = fields.Float(help='Decline parameter')

    @api.depends('start_date')
    def _compute_expected_dry_off(self):
        for record in self:
            if record.start_date:
                # Standard dry off at 305 days, with 60-day dry period before next calving
                record.expected_dry_off = record.start_date + timedelta(days=305)
            else:
                record.expected_dry_off = False

    @api.depends('start_date', 'end_date', 'state')
    def _compute_days_in_milk(self):
        today = date.today()
        for record in self:
            if record.start_date:
                end = record.end_date or (today if record.state == 'active' else record.start_date)
                record.days_in_milk = (end - record.start_date).days
            else:
                record.days_in_milk = 0

    @api.depends('production_ids', 'production_ids.yield_liters')
    def _compute_production_summary(self):
        for record in self:
            productions = record.production_ids
            if productions:
                yields = productions.mapped('yield_liters')
                record.total_yield_liters = sum(yields)
                record.peak_yield_liters = max(yields) if yields else 0
                # Find DIM of peak
                peak_record = productions.sorted(key=lambda r: r.yield_liters, reverse=True)[:1]
                record.peak_yield_dim = peak_record.days_in_milk if peak_record else 0
                # Average daily (considering AM+PM)
                days = len(set(productions.mapped('production_date')))
                record.average_daily_yield = record.total_yield_liters / days if days else 0
            else:
                record.total_yield_liters = 0
                record.peak_yield_liters = 0
                record.peak_yield_dim = 0
                record.average_daily_yield = 0

    def _compute_quality_summary(self):
        for record in self:
            productions = record.production_ids.filtered(lambda p: p.fat_percentage)
            if productions:
                record.average_fat_percent = sum(productions.mapped('fat_percentage')) / len(productions)
                record.average_protein_percent = sum(productions.mapped('protein_percentage') or [0]) / len(productions)
                scc_records = productions.filtered(lambda p: p.somatic_cell_count)
                record.average_scc = sum(scc_records.mapped('somatic_cell_count')) / len(scc_records) if scc_records else 0
            else:
                record.average_fat_percent = 0
                record.average_protein_percent = 0
                record.average_scc = 0

    def get_expected_yield(self, dim):
        """Calculate expected yield using Wood's lactation curve model"""
        self.ensure_one()
        if not all([self.curve_parameter_a, self.curve_parameter_b, self.curve_parameter_c]):
            # Use breed defaults if curve not fitted
            breed = self.animal_id.breed_id
            if breed:
                return breed.avg_milk_yield_liters
            return 0

        import math
        # Wood's model: Y(t) = a * t^b * e^(-c*t)
        a, b, c = self.curve_parameter_a, self.curve_parameter_b, self.curve_parameter_c
        try:
            expected = a * (dim ** b) * math.exp(-c * dim)
            return round(expected, 2)
        except (ValueError, OverflowError):
            return 0

    def action_fit_lactation_curve(self):
        """Fit lactation curve to actual production data"""
        self.ensure_one()
        # Implementation of curve fitting using scipy (via external service)
        # This would typically call a Python service that uses scipy.optimize
        pass

    def action_dry_off(self):
        """Mark cow as dry"""
        self.write({
            'state': 'dry',
            'dry_off_date': date.today(),
            'end_date': date.today()
        })
        self.animal_id.action_mark_dry()
```

**Task 2: TimescaleDB Migration (2h)**

```sql
-- Post-install migration to create TimescaleDB hypertable
-- File: odoo/addons/smart_dairy_farm/data/timescaledb_init.sql

-- Create hypertable for milk production
SELECT create_hypertable(
    'farm_milk_production',
    'production_datetime',
    chunk_time_interval => INTERVAL '1 month',
    if_not_exists => TRUE,
    migrate_data => TRUE
);

-- Create continuous aggregate for daily totals
CREATE MATERIALIZED VIEW farm_milk_daily_summary
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', production_datetime) AS day,
    animal_id,
    farm_id,
    SUM(yield_liters) AS total_yield,
    AVG(fat_percentage) AS avg_fat,
    AVG(protein_percentage) AS avg_protein,
    MAX(somatic_cell_count) AS max_scc,
    COUNT(*) AS milking_count
FROM farm_milk_production
GROUP BY day, animal_id, farm_id
WITH NO DATA;

-- Add refresh policy
SELECT add_continuous_aggregate_policy('farm_milk_daily_summary',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour');

-- Create compression policy for older data
ALTER TABLE farm_milk_production SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'animal_id',
    timescaledb.compress_orderby = 'production_datetime DESC'
);

SELECT add_compression_policy('farm_milk_production', INTERVAL '90 days');

-- Create indexes for common queries
CREATE INDEX idx_production_animal_date ON farm_milk_production (animal_id, production_datetime DESC);
CREATE INDEX idx_production_farm_date ON farm_milk_production (farm_id, production_datetime DESC);
CREATE INDEX idx_production_lactation ON farm_milk_production (lactation_id, production_datetime);
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Batch Entry System (4h)**

```python
# odoo/addons/smart_dairy_farm/wizard/milk_batch_entry.py
from odoo import models, fields, api
from odoo.exceptions import UserError
from datetime import date

class MilkBatchEntryWizard(models.TransientModel):
    _name = 'farm.milk.batch.entry.wizard'
    _description = 'Batch Milk Entry Wizard'

    production_date = fields.Date(
        required=True,
        default=fields.Date.today
    )
    milking_session = fields.Selection([
        ('morning', 'Morning'),
        ('evening', 'Evening'),
    ], required=True, default='morning')
    farm_id = fields.Many2one(
        'farm.location',
        required=True,
        domain="[('location_type', '=', 'farm')]"
    )
    line_ids = fields.One2many(
        'farm.milk.batch.entry.line',
        'wizard_id',
        string='Animals'
    )

    @api.onchange('farm_id', 'production_date', 'milking_session')
    def _onchange_farm(self):
        """Load all lactating animals in the farm"""
        if self.farm_id:
            animals = self.env['farm.animal'].search([
                ('farm_id', '=', self.farm_id.id),
                ('gender', '=', 'female'),
                ('status', 'in', ['active', 'lactating']),
            ])

            # Check for existing records
            existing = self.env['farm.milk.production'].search([
                ('production_date', '=', self.production_date),
                ('milking_session', '=', self.milking_session),
                ('animal_id', 'in', animals.ids),
            ])
            existing_animal_ids = existing.mapped('animal_id').ids

            lines = []
            for animal in animals:
                if animal.id not in existing_animal_ids:
                    lines.append((0, 0, {
                        'animal_id': animal.id,
                        'yield_liters': 0,
                    }))

            self.line_ids = lines

    def action_create_records(self):
        """Create milk production records for all lines"""
        self.ensure_one()
        Production = self.env['farm.milk.production']
        created_count = 0
        errors = []

        for line in self.line_ids:
            if line.yield_liters > 0:
                try:
                    Production.create({
                        'animal_id': line.animal_id.id,
                        'production_date': self.production_date,
                        'milking_session': self.milking_session,
                        'yield_liters': line.yield_liters,
                        'fat_percentage': line.fat_percentage,
                        'protein_percentage': line.protein_percentage,
                        'somatic_cell_count': line.somatic_cell_count,
                        'notes': line.notes,
                    })
                    created_count += 1
                except Exception as e:
                    errors.append(f"{line.animal_id.ear_tag}: {str(e)}")

        message = f"Created {created_count} production records."
        if errors:
            message += f"\n\nErrors:\n" + "\n".join(errors)

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Batch Entry Complete',
                'message': message,
                'type': 'success' if not errors else 'warning',
                'sticky': bool(errors),
            }
        }


class MilkBatchEntryLine(models.TransientModel):
    _name = 'farm.milk.batch.entry.line'
    _description = 'Batch Milk Entry Line'

    wizard_id = fields.Many2one('farm.milk.batch.entry.wizard', required=True, ondelete='cascade')
    animal_id = fields.Many2one('farm.animal', required=True)
    animal_ear_tag = fields.Char(related='animal_id.ear_tag')
    animal_breed = fields.Char(related='animal_id.breed_id.name')
    last_yield = fields.Float(compute='_compute_last_yield')
    yield_liters = fields.Float(string='Yield (L)')
    fat_percentage = fields.Float(string='Fat %')
    protein_percentage = fields.Float(string='Protein %')
    somatic_cell_count = fields.Integer(string='SCC')
    notes = fields.Char()

    def _compute_last_yield(self):
        for line in self:
            last = self.env['farm.milk.production'].search([
                ('animal_id', '=', line.animal_id.id),
            ], order='production_date desc', limit=1)
            line.last_yield = last.yield_liters if last else 0
```

**Task 2: Production Analytics Service (4h)**

```python
# odoo/addons/smart_dairy_farm/services/production_analytics.py
from odoo import models, api
from datetime import date, timedelta

class ProductionAnalyticsService(models.AbstractModel):
    _name = 'farm.production.analytics'
    _description = 'Production Analytics Service'

    @api.model
    def get_farm_production_summary(self, farm_id, start_date=None, end_date=None):
        """Get production summary for a farm"""
        if not start_date:
            start_date = date.today() - timedelta(days=30)
        if not end_date:
            end_date = date.today()

        productions = self.env['farm.milk.production'].search([
            ('farm_id', '=', farm_id),
            ('production_date', '>=', start_date),
            ('production_date', '<=', end_date),
        ])

        if not productions:
            return {}

        total_yield = sum(productions.mapped('yield_liters'))
        days = (end_date - start_date).days + 1
        animals = len(set(productions.mapped('animal_id').ids))

        return {
            'total_yield': total_yield,
            'daily_average': total_yield / days,
            'animal_count': animals,
            'yield_per_animal': total_yield / animals if animals else 0,
            'avg_fat': sum(p.fat_percentage for p in productions if p.fat_percentage) / len([p for p in productions if p.fat_percentage]) if any(p.fat_percentage for p in productions) else 0,
            'avg_scc': sum(p.somatic_cell_count for p in productions if p.somatic_cell_count) / len([p for p in productions if p.somatic_cell_count]) if any(p.somatic_cell_count for p in productions) else 0,
        }

    @api.model
    def get_production_trend(self, farm_id=None, animal_id=None, days=30):
        """Get daily production trend"""
        domain = [
            ('production_date', '>=', date.today() - timedelta(days=days)),
        ]
        if farm_id:
            domain.append(('farm_id', '=', farm_id))
        if animal_id:
            domain.append(('animal_id', '=', animal_id))

        productions = self.env['farm.milk.production'].search(domain)

        # Group by date
        daily_data = {}
        for prod in productions:
            day = str(prod.production_date)
            if day not in daily_data:
                daily_data[day] = {'yield': 0, 'count': 0}
            daily_data[day]['yield'] += prod.yield_liters
            daily_data[day]['count'] += 1

        return [
            {'date': day, 'total_yield': data['yield'], 'milkings': data['count']}
            for day, data in sorted(daily_data.items())
        ]

    @api.model
    def get_top_producers(self, farm_id, days=30, limit=10):
        """Get top producing animals"""
        self.env.cr.execute("""
            SELECT
                animal_id,
                SUM(yield_liters) as total_yield,
                AVG(yield_liters) as avg_yield,
                COUNT(*) as milking_count
            FROM farm_milk_production
            WHERE farm_id = %s
                AND production_date >= %s
            GROUP BY animal_id
            ORDER BY total_yield DESC
            LIMIT %s
        """, (farm_id, date.today() - timedelta(days=days), limit))

        results = []
        for row in self.env.cr.fetchall():
            animal = self.env['farm.animal'].browse(row[0])
            results.append({
                'animal_id': row[0],
                'ear_tag': animal.ear_tag,
                'name': animal.name,
                'total_yield': row[1],
                'avg_yield': row[2],
                'milking_count': row[3],
            })

        return results

    @api.model
    def get_quality_distribution(self, farm_id, days=30):
        """Get quality grade distribution"""
        self.env.cr.execute("""
            SELECT
                quality_grade,
                COUNT(*) as count,
                SUM(yield_liters) as total_yield
            FROM farm_milk_production
            WHERE farm_id = %s
                AND production_date >= %s
                AND quality_grade IS NOT NULL
            GROUP BY quality_grade
        """, (farm_id, date.today() - timedelta(days=days)))

        return [
            {'grade': row[0], 'count': row[1], 'total_yield': row[2]}
            for row in self.env.cr.fetchall()
        ]
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Production Views (4h)**

```xml
<!-- Production record views similar to other modules -->
```

**Task 2: Flutter Production Entry (4h)**

```dart
// lib/features/production/presentation/pages/production_entry_screen.dart
// Quick grid entry for milk production
```

*[Days 282-290 continue with detailed implementation]*

---

## 4. Technical Specifications

### 4.1 Database Schema (TimescaleDB)

```sql
-- Core production table (converted to hypertable)
CREATE TABLE farm_milk_production (
    id BIGSERIAL,
    animal_id INTEGER NOT NULL,
    production_datetime TIMESTAMPTZ NOT NULL,
    production_date DATE NOT NULL,
    milking_session VARCHAR(20),
    yield_liters DECIMAL(8,2) NOT NULL,
    fat_percentage DECIMAL(4,2),
    protein_percentage DECIMAL(4,2),
    snf_percentage DECIMAL(4,2),
    somatic_cell_count INTEGER,
    quality_grade VARCHAR(20),
    lactation_id INTEGER,
    days_in_milk INTEGER,
    company_id INTEGER NOT NULL,
    PRIMARY KEY (id, production_datetime)
);

-- Convert to hypertable
SELECT create_hypertable('farm_milk_production', 'production_datetime');
```

### 4.2 API Endpoints

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/api/v1/production/records` | List production records |
| POST | `/api/v1/production/records` | Create single record |
| POST | `/api/v1/production/batch` | Batch entry |
| GET | `/api/v1/production/summary` | Farm/animal summary |
| GET | `/api/v1/production/trends` | Production trends |
| GET | `/api/v1/lactation/{id}/curve` | Lactation curve data |

---

## 5. Testing & Validation

### 5.1 Acceptance Criteria

- [ ] TimescaleDB hypertable operational
- [ ] Batch entry processes 100+ animals in <10s
- [ ] Quality grades calculated correctly
- [ ] Lactation curves generated
- [ ] Production alerts triggered
- [ ] Mobile entry syncs offline data

---

## 6. Dependencies & Handoffs

### 6.1 Prerequisites
- FarmAnimal with lactation status
- TimescaleDB extension installed
- Breeding records for calving dates

### 6.2 Handoffs to Milestone 45
- Production data for feed efficiency
- Quality data for feed correlation

---

**Milestone 44 Total Effort:** 240 person-hours

**Document Version:** 1.0
**Last Updated:** 2026-02-03
