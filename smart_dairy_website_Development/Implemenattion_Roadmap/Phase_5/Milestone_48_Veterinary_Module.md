# Milestone 48: Veterinary Module
## Smart Dairy Digital Smart Portal + ERP - Phase 5

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P5-M48-001 |
| **Milestone** | 48 of 50 (8 of 10 in Phase 5) |
| **Title** | Veterinary Module |
| **Phase** | Phase 5 - Farm Management Foundation |
| **Days** | Days 321-330 (of 350) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Implement a comprehensive veterinary management module with appointment scheduling, visit records, treatment plans, prescription management, quarantine workflows, emergency protocols, and external vet portal access.

### 1.2 Objectives

| # | Objective |
|---|-----------|
| 1 | Create FarmVetAppointment model with scheduling |
| 2 | Build vet visit recording system |
| 3 | Implement treatment plan templates |
| 4 | Develop prescription management with medicine dispensing |
| 5 | Create quarantine workflow management |
| 6 | Implement emergency alert protocols |
| 7 | Build external vet portal access |
| 8 | Integrate lab result import |
| 9 | Create vet activity reports |
| 10 | Complete testing and documentation |

### 1.3 Key Deliverables

| Deliverable | Type | Description |
|-------------|------|-------------|
| FarmVetAppointment | Backend | Appointment scheduling model |
| FarmVetVisit | Backend | Visit records with findings |
| FarmTreatmentPlan | Backend | Treatment protocol system |
| FarmPrescription | Backend | Prescription management |
| Quarantine Workflow | Backend | Quarantine zone management |
| Emergency Alert System | Backend | Emergency protocols |
| Vet Portal | Frontend | External vet access UI |
| Mobile Vet Module | Mobile | Mobile vet functions |

### 1.4 Success Criteria

| Criteria | Target |
|----------|--------|
| Appointment scheduling efficiency | <2 minutes |
| Treatment plan template coverage | 50+ templates |
| Emergency response time | <1 minute alert |
| External vet portal response | <3 seconds |
| Quarantine compliance tracking | 100% |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Description | Day(s) | Status |
|--------|--------|-------------|--------|--------|
| RFP-FARM-018 | RFP | Veterinary scheduling | 321 | Planned |
| BRD-FARM-065 | BRD | Treatment protocol library | 323 | Planned |
| BRD-FARM-066 | BRD | Quarantine management | 325 | Planned |
| BRD-FARM-067 | BRD | Emergency alert system | 326 | Planned |
| SRS-FARM-110 | SRS | External vet portal | 327 | Planned |
| SRS-FARM-111 | SRS | Lab result integration | 328 | Planned |

---

## 3. Day-by-Day Implementation

### Day 321: Veterinary Appointment System

**Theme:** Appointment Scheduling Foundation

#### Dev 1 - Backend Lead (8h)

**Task 1: FarmVetAppointment Model (5h)**
```python
# farm_veterinary/models/vet_appointment.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta

class FarmVetAppointment(models.Model):
    _name = 'farm.vet.appointment'
    _description = 'Veterinary Appointment'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'scheduled_date, scheduled_time'

    name = fields.Char(string='Appointment Reference', required=True, copy=False,
                       readonly=True, default='New')
    scheduled_date = fields.Date(string='Scheduled Date', required=True, tracking=True)
    scheduled_time = fields.Float(string='Scheduled Time', required=True)
    duration_hours = fields.Float(string='Duration (hours)', default=1.0)

    # Participants
    veterinarian_id = fields.Many2one('res.partner', string='Veterinarian',
                                       domain=[('is_veterinarian', '=', True)], required=True)
    farm_id = fields.Many2one('farm.location', string='Farm', required=True)
    requested_by = fields.Many2one('res.users', default=lambda self: self.env.user)

    # Animals
    animal_ids = fields.Many2many('farm.animal', string='Animals')
    animal_count = fields.Integer(compute='_compute_animal_count')

    # Appointment Details
    appointment_type = fields.Selection([
        ('routine', 'Routine Checkup'),
        ('vaccination', 'Vaccination'),
        ('treatment', 'Treatment'),
        ('pregnancy_check', 'Pregnancy Check'),
        ('emergency', 'Emergency'),
        ('surgery', 'Surgery'),
        ('ai', 'Artificial Insemination'),
        ('follow_up', 'Follow-up'),
    ], string='Type', required=True, tracking=True)

    reason = fields.Text(string='Reason/Symptoms')
    priority = fields.Selection([
        ('low', 'Low'),
        ('normal', 'Normal'),
        ('high', 'High'),
        ('urgent', 'Urgent'),
    ], default='normal', tracking=True)

    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('confirmed', 'Confirmed'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled'),
        ('no_show', 'No Show'),
    ], default='draft', tracking=True)

    # Reminders
    reminder_sent = fields.Boolean(default=False)
    reminder_date = fields.Datetime(compute='_compute_reminder_date')

    # Related Records
    visit_id = fields.Many2one('farm.vet.visit', string='Visit Record')
    health_record_ids = fields.One2many('farm.health.record', 'appointment_id', string='Health Records')

    notes = fields.Text(string='Notes')

    @api.model
    def create(self, vals):
        if vals.get('name', 'New') == 'New':
            vals['name'] = self.env['ir.sequence'].next_by_code('farm.vet.appointment') or 'New'
        return super().create(vals)

    @api.depends('animal_ids')
    def _compute_animal_count(self):
        for record in self:
            record.animal_count = len(record.animal_ids)

    @api.depends('scheduled_date')
    def _compute_reminder_date(self):
        for record in self:
            if record.scheduled_date:
                record.reminder_date = datetime.combine(
                    record.scheduled_date - timedelta(days=1),
                    datetime.min.time()
                ).replace(hour=9)
            else:
                record.reminder_date = False

    @api.constrains('scheduled_date', 'scheduled_time', 'veterinarian_id')
    def _check_appointment_conflict(self):
        for record in self:
            if record.state in ['cancelled', 'no_show']:
                continue

            conflicts = self.search([
                ('id', '!=', record.id),
                ('veterinarian_id', '=', record.veterinarian_id.id),
                ('scheduled_date', '=', record.scheduled_date),
                ('state', 'not in', ['cancelled', 'no_show']),
            ])

            for conflict in conflicts:
                if self._times_overlap(
                    record.scheduled_time, record.duration_hours,
                    conflict.scheduled_time, conflict.duration_hours
                ):
                    raise ValidationError(
                        f'Appointment conflicts with {conflict.name} at this time slot'
                    )

    def _times_overlap(self, start1, duration1, start2, duration2):
        end1 = start1 + duration1
        end2 = start2 + duration2
        return start1 < end2 and start2 < end1

    def action_confirm(self):
        self.write({'state': 'confirmed'})
        self._send_confirmation_notification()

    def action_start(self):
        self.write({'state': 'in_progress'})

    def action_complete(self):
        self.write({'state': 'completed'})

    def action_cancel(self):
        self.write({'state': 'cancelled'})

    def _send_confirmation_notification(self):
        """Send appointment confirmation"""
        template = self.env.ref('farm_veterinary.email_appointment_confirmation')
        for record in self:
            template.send_mail(record.id, force_send=True)

    @api.model
    def _cron_send_reminders(self):
        """Scheduled job to send appointment reminders"""
        tomorrow = fields.Date.today() + timedelta(days=1)
        appointments = self.search([
            ('scheduled_date', '=', tomorrow),
            ('state', '=', 'confirmed'),
            ('reminder_sent', '=', False),
        ])

        for appointment in appointments:
            appointment._send_reminder()
            appointment.reminder_sent = True

    def _send_reminder(self):
        """Send appointment reminder"""
        template = self.env.ref('farm_veterinary.email_appointment_reminder')
        template.send_mail(self.id, force_send=True)
```

**Task 2: Appointment Scheduling Wizard (3h)**
```python
# farm_veterinary/wizard/schedule_appointment.py
class ScheduleAppointmentWizard(models.TransientModel):
    _name = 'farm.vet.appointment.wizard'
    _description = 'Schedule Appointment Wizard'

    animal_ids = fields.Many2many('farm.animal', string='Animals')
    appointment_type = fields.Selection([
        ('routine', 'Routine Checkup'),
        ('vaccination', 'Vaccination'),
        ('treatment', 'Treatment'),
        ('pregnancy_check', 'Pregnancy Check'),
        ('emergency', 'Emergency'),
    ], required=True)
    preferred_date = fields.Date(required=True, default=fields.Date.today)
    preferred_time = fields.Float(string='Preferred Time')
    veterinarian_id = fields.Many2one('res.partner', domain=[('is_veterinarian', '=', True)])
    reason = fields.Text()

    available_slots = fields.Text(compute='_compute_available_slots')

    @api.depends('preferred_date', 'veterinarian_id')
    def _compute_available_slots(self):
        for wizard in self:
            if wizard.preferred_date and wizard.veterinarian_id:
                slots = self._get_available_slots(
                    wizard.veterinarian_id.id,
                    wizard.preferred_date
                )
                wizard.available_slots = ', '.join([f'{s:.2f}' for s in slots])
            else:
                wizard.available_slots = ''

    def _get_available_slots(self, vet_id, date):
        """Get available time slots for veterinarian"""
        # Working hours: 8:00 - 17:00, 1-hour slots
        all_slots = [8.0, 9.0, 10.0, 11.0, 13.0, 14.0, 15.0, 16.0]

        # Get booked slots
        appointments = self.env['farm.vet.appointment'].search([
            ('veterinarian_id', '=', vet_id),
            ('scheduled_date', '=', date),
            ('state', 'not in', ['cancelled', 'no_show']),
        ])

        booked = set()
        for apt in appointments:
            booked.add(apt.scheduled_time)

        return [s for s in all_slots if s not in booked]

    def action_schedule(self):
        """Create appointment"""
        appointment = self.env['farm.vet.appointment'].create({
            'animal_ids': [(6, 0, self.animal_ids.ids)],
            'appointment_type': self.appointment_type,
            'scheduled_date': self.preferred_date,
            'scheduled_time': self.preferred_time,
            'veterinarian_id': self.veterinarian_id.id,
            'reason': self.reason,
            'farm_id': self.animal_ids[0].farm_id.id if self.animal_ids else False,
        })

        appointment.action_confirm()

        return {
            'type': 'ir.actions.act_window',
            'res_model': 'farm.vet.appointment',
            'res_id': appointment.id,
            'view_mode': 'form',
        }
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Appointment Calendar API (4h)**
```python
# farm_veterinary/controllers/appointment_api.py
class AppointmentAPI(http.Controller):

    @http.route('/api/v1/vet/appointments', type='json', auth='user', methods=['GET'])
    def get_appointments(self, start_date=None, end_date=None, vet_id=None, state=None):
        domain = []
        if start_date:
            domain.append(('scheduled_date', '>=', start_date))
        if end_date:
            domain.append(('scheduled_date', '<=', end_date))
        if vet_id:
            domain.append(('veterinarian_id', '=', int(vet_id)))
        if state:
            domain.append(('state', '=', state))

        appointments = request.env['farm.vet.appointment'].search(domain)

        return [{
            'id': a.id,
            'name': a.name,
            'scheduled_date': a.scheduled_date.isoformat(),
            'scheduled_time': a.scheduled_time,
            'duration': a.duration_hours,
            'type': a.appointment_type,
            'state': a.state,
            'veterinarian': a.veterinarian_id.name,
            'farm': a.farm_id.name,
            'animal_count': a.animal_count,
            'priority': a.priority,
        } for a in appointments]

    @http.route('/api/v1/vet/appointments/<int:id>/confirm', type='json', auth='user', methods=['POST'])
    def confirm_appointment(self, id):
        appointment = request.env['farm.vet.appointment'].browse(id)
        if appointment.exists():
            appointment.action_confirm()
            return {'success': True}
        return {'success': False, 'error': 'Appointment not found'}

    @http.route('/api/v1/vet/available-slots', type='json', auth='user', methods=['GET'])
    def get_available_slots(self, vet_id, date):
        wizard = request.env['farm.vet.appointment.wizard']
        slots = wizard._get_available_slots(int(vet_id), date)
        return {'slots': slots}
```

**Task 2: Notification Service (4h)**
```python
# farm_veterinary/services/notification_service.py
class VetNotificationService:

    def __init__(self, env):
        self.env = env

    def send_appointment_reminder(self, appointment):
        """Send multi-channel reminder"""
        # Email
        template = self.env.ref('farm_veterinary.email_appointment_reminder')
        template.send_mail(appointment.id)

        # Push notification (mobile)
        self._send_push_notification(
            user_ids=[appointment.requested_by.id],
            title='Appointment Reminder',
            body=f'Appointment {appointment.name} tomorrow at {appointment.scheduled_time:.2f}',
            data={'appointment_id': appointment.id}
        )

        # SMS (optional)
        if appointment.requested_by.phone:
            self._send_sms(
                phone=appointment.requested_by.phone,
                message=f'Smart Dairy: Reminder - Vet appointment tomorrow at {appointment.scheduled_time:.2f}'
            )

    def send_emergency_alert(self, animal, alert_type, message):
        """Send emergency alert to all relevant users"""
        # Get users to notify
        farm_managers = self.env['res.users'].search([
            ('groups_id', 'in', self.env.ref('farm_base.group_farm_manager').id)
        ])

        # Get available veterinarians
        vets = self.env['res.partner'].search([
            ('is_veterinarian', '=', True),
            ('availability_status', '=', 'available'),
        ])

        # Send alerts
        for user in farm_managers:
            self._send_push_notification(
                user_ids=[user.id],
                title=f'EMERGENCY: {alert_type}',
                body=f'{animal.name} ({animal.ear_tag}): {message}',
                data={'animal_id': animal.id, 'emergency': True},
                priority='high'
            )

        return True

    def _send_push_notification(self, user_ids, title, body, data=None, priority='normal'):
        """Send push notification via FCM"""
        sessions = self.env['farm.user.session'].search([
            ('user_id', 'in', user_ids),
            ('push_token', '!=', False),
            ('is_active', '=', True),
        ])

        # Implementation would use Firebase Admin SDK
        pass

    def _send_sms(self, phone, message):
        """Send SMS via configured gateway"""
        # Implementation would use SMS gateway API
        pass
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Appointment Calendar UI (5h)**
```xml
<!-- farm_veterinary/views/appointment_views.xml -->
<odoo>
    <record id="view_vet_appointment_calendar" model="ir.ui.view">
        <field name="name">farm.vet.appointment.calendar</field>
        <field name="model">farm.vet.appointment</field>
        <field name="arch" type="xml">
            <calendar string="Appointments" date_start="scheduled_date" date_delay="duration_hours"
                      color="veterinarian_id" mode="week" event_open_popup="true">
                <field name="name"/>
                <field name="appointment_type"/>
                <field name="veterinarian_id"/>
                <field name="animal_count"/>
                <field name="state"/>
            </calendar>
        </field>
    </record>

    <record id="view_vet_appointment_form" model="ir.ui.view">
        <field name="name">farm.vet.appointment.form</field>
        <field name="model">farm.vet.appointment</field>
        <field name="arch" type="xml">
            <form>
                <header>
                    <button name="action_confirm" string="Confirm" type="object"
                            class="btn-primary" states="draft"/>
                    <button name="action_start" string="Start Visit" type="object"
                            class="btn-primary" states="confirmed"/>
                    <button name="action_complete" string="Complete" type="object"
                            class="btn-success" states="in_progress"/>
                    <button name="action_cancel" string="Cancel" type="object"
                            states="draft,confirmed"/>
                    <field name="state" widget="statusbar"
                           statusbar_visible="draft,confirmed,in_progress,completed"/>
                </header>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="action_view_health_records" type="object"
                                class="oe_stat_button" icon="fa-heartbeat">
                            <field name="health_record_count" widget="statinfo" string="Records"/>
                        </button>
                    </div>
                    <div class="oe_title">
                        <h1><field name="name"/></h1>
                    </div>
                    <group>
                        <group string="Schedule">
                            <field name="scheduled_date"/>
                            <field name="scheduled_time" widget="float_time"/>
                            <field name="duration_hours"/>
                            <field name="veterinarian_id"/>
                        </group>
                        <group string="Details">
                            <field name="appointment_type"/>
                            <field name="priority"/>
                            <field name="farm_id"/>
                            <field name="requested_by"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Animals" name="animals">
                            <field name="animal_ids">
                                <tree>
                                    <field name="ear_tag"/>
                                    <field name="name"/>
                                    <field name="species"/>
                                    <field name="status"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Reason/Notes" name="notes">
                            <field name="reason" placeholder="Describe symptoms or reason for appointment..."/>
                            <field name="notes" placeholder="Additional notes..."/>
                        </page>
                    </notebook>
                </sheet>
                <div class="oe_chatter">
                    <field name="message_follower_ids"/>
                    <field name="activity_ids"/>
                    <field name="message_ids"/>
                </div>
            </form>
        </field>
    </record>
</odoo>
```

**Task 2: Mobile Appointment Screen (3h)**
```dart
// lib/features/veterinary/presentation/screens/appointments_screen.dart
import 'package:flutter/material.dart';
import 'package:table_calendar/table_calendar.dart';

class VetAppointmentsScreen extends StatefulWidget {
  const VetAppointmentsScreen({super.key});

  @override
  State<VetAppointmentsScreen> createState() => _VetAppointmentsScreenState();
}

class _VetAppointmentsScreenState extends State<VetAppointmentsScreen> {
  DateTime _focusedDay = DateTime.now();
  DateTime? _selectedDay;
  CalendarFormat _calendarFormat = CalendarFormat.week;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Vet Appointments')),
      body: Column(
        children: [
          TableCalendar(
            firstDay: DateTime.utc(2020, 1, 1),
            lastDay: DateTime.utc(2030, 12, 31),
            focusedDay: _focusedDay,
            calendarFormat: _calendarFormat,
            selectedDayPredicate: (day) => isSameDay(_selectedDay, day),
            onDaySelected: (selectedDay, focusedDay) {
              setState(() {
                _selectedDay = selectedDay;
                _focusedDay = focusedDay;
              });
            },
            onFormatChanged: (format) {
              setState(() => _calendarFormat = format);
            },
            eventLoader: (day) {
              // Return appointments for this day
              return [];
            },
          ),
          const Divider(),
          Expanded(
            child: BlocBuilder<AppointmentBloc, AppointmentState>(
              builder: (context, state) {
                if (state is AppointmentLoaded) {
                  final appointments = state.appointments
                      .where((a) => isSameDay(a.scheduledDate, _selectedDay ?? _focusedDay))
                      .toList();

                  if (appointments.isEmpty) {
                    return const Center(child: Text('No appointments'));
                  }

                  return ListView.builder(
                    itemCount: appointments.length,
                    itemBuilder: (context, index) {
                      final apt = appointments[index];
                      return AppointmentCard(appointment: apt);
                    },
                  );
                }
                return const Center(child: CircularProgressIndicator());
              },
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showScheduleDialog(context),
        icon: const Icon(Icons.add),
        label: const Text('Schedule'),
      ),
    );
  }

  void _showScheduleDialog(BuildContext context) {
    // Show appointment scheduling dialog
  }
}
```

#### Day 321 Deliverables
- [x] FarmVetAppointment model with scheduling
- [x] Appointment scheduling wizard
- [x] Appointment calendar API
- [x] Notification service
- [x] Calendar UI (web & mobile)

---

### Days 322-330: Summary

#### Day 322: Visit Records System
- FarmVetVisit model with findings
- Visit checklist templates
- Physical examination recording
- Visit summary generation

#### Day 323: Treatment Plans
- FarmTreatmentPlan model
- Treatment protocol library (50+ templates)
- Multi-day treatment schedules
- Treatment compliance tracking

#### Day 324: Prescription Management
- FarmPrescription model
- Medicine dispensing workflow
- Withdrawal period tracking
- Prescription printing

#### Day 325: Quarantine Management
- FarmQuarantineRecord model
- Quarantine zone definition
- Entry/exit protocols
- Quarantine status dashboard

#### Day 326: Emergency Protocols
- Emergency alert triggers
- Escalation workflows
- Emergency contact management
- Outbreak response procedures

#### Day 327: External Vet Portal
- External vet user access
- Limited permission set
- Visit report submission
- Secure document sharing

#### Day 328: Lab Integration
- Lab result import API
- Result parsing and storage
- Abnormal value alerts
- Historical result tracking

#### Day 329: Vet Reports
- Visit activity reports
- Treatment success rates
- Cost analysis reports
- Veterinarian performance metrics

#### Day 330: Testing & Documentation
- Unit tests for all models
- Integration tests
- User documentation
- API documentation

---

## 4. Technical Specifications

### 4.1 Database Schema

```sql
CREATE TABLE farm_vet_appointment (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time DECIMAL(4,2) NOT NULL,
    duration_hours DECIMAL(4,2) DEFAULT 1.0,
    veterinarian_id INTEGER REFERENCES res_partner(id),
    farm_id INTEGER REFERENCES farm_location(id),
    appointment_type VARCHAR(20) NOT NULL,
    priority VARCHAR(10) DEFAULT 'normal',
    state VARCHAR(20) DEFAULT 'draft',
    reason TEXT,
    notes TEXT
);

CREATE TABLE farm_vet_visit (
    id SERIAL PRIMARY KEY,
    appointment_id INTEGER REFERENCES farm_vet_appointment(id),
    visit_date TIMESTAMP NOT NULL,
    veterinarian_id INTEGER REFERENCES res_partner(id),
    findings TEXT,
    recommendations TEXT,
    follow_up_required BOOLEAN DEFAULT FALSE
);

CREATE TABLE farm_treatment_plan (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    animal_id INTEGER REFERENCES farm_animal(id),
    condition VARCHAR(100),
    start_date DATE NOT NULL,
    end_date DATE,
    status VARCHAR(20) DEFAULT 'active'
);

CREATE TABLE farm_quarantine_record (
    id SERIAL PRIMARY KEY,
    animal_id INTEGER REFERENCES farm_animal(id),
    reason VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    zone_id INTEGER REFERENCES farm_quarantine_zone(id),
    status VARCHAR(20) DEFAULT 'active'
);
```

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/vet/appointments` | GET/POST | Appointment management |
| `/api/v1/vet/visits` | GET/POST | Visit records |
| `/api/v1/vet/treatments` | GET/POST | Treatment plans |
| `/api/v1/vet/prescriptions` | GET/POST | Prescriptions |
| `/api/v1/vet/quarantine` | GET/POST | Quarantine records |
| `/api/v1/vet/emergency` | POST | Emergency alert |

---

## 5. Dependencies & Handoffs

### 5.1 Prerequisites
- Milestone 42: Health record system
- Milestone 47: Mobile app integration

### 5.2 Outputs
- Milestone 49: Vet reports for reporting system
- Milestone 50: Integration testing

---

*Document End - Milestone 48: Veterinary Module*
