# SMART DAIRY LTD.
## TROUBLESHOOTING GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | K-015 |
| **Version** | 1.0 |
| **Date** | November 5, 2026 |
| **Author** | Tech Writer |
| **Owner** | Support Lead |
| **Reviewer** | IT Director |
| **Classification** | Internal Use |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Troubleshooting Methodology](#2-troubleshooting-methodology)
3. [Login & Access Issues](#3-login--access-issues)
4. [Farm Management Issues](#4-farm-management-issues)
5. [E-Commerce & Sales Issues](#5-e-commerce--sales-issues)
6. [Mobile App Issues](#6-mobile-app-issues)
7. [Payment & Billing Issues](#7-payment--billing-issues)
8. [IoT & Hardware Issues](#8-iot--hardware-issues)
9. [Performance Issues](#9-performance-issues)
10. [Data Synchronization Issues](#10-data-synchronization-issues)
11. [Emergency Procedures](#11-emergency-procedures)
12. [Escalation Matrix](#12-escalation-matrix)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Troubleshooting Guide provides systematic procedures for diagnosing and resolving common issues encountered in the Smart Dairy Web Portal System and Integrated ERP. It serves as the primary reference for IT support staff, system administrators, and power users.

### 1.2 Scope

This guide covers troubleshooting for:
- User access and authentication problems
- Farm management module issues
- E-commerce and sales processing errors
- Mobile application problems
- Payment gateway failures
- IoT device connectivity issues
- System performance degradation
- Data synchronization errors

### 1.3 Document Conventions

| Icon | Meaning |
|------|---------|
| 🔴 Critical | System-down or data-loss risk |
| 🟠 High | Significant business impact |
| 🟡 Medium | Workaround available |
| 🟢 Low | Cosmetic or minor issue |

---

## 2. TROUBLESHOOTING METHODOLOGY

### 2.1 Systematic Approach

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    TROUBLESHOOTING WORKFLOW                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  1. IDENTIFY                                                                  │
│     └── Gather information: What happened? When? Who? Error message?        │
│                                                                              │
│  2. REPRODUCE                                                                 │
│     └── Can the issue be reproduced consistently? Under what conditions?    │
│                                                                              │
│  3. ISOLATE                                                                   │
│     └── Determine scope: Single user? Specific location? All users?         │
│                                                                              │
│  4. DIAGNOSE                                                                  │
│     └── Check logs, system status, network connectivity, recent changes     │
│                                                                              │
│  5. RESOLVE                                                                   │
│     └── Apply fix based on priority and business impact                     │
│                                                                              │
│  6. VERIFY                                                                    │
│     └── Confirm issue is resolved and no new issues introduced              │
│                                                                              │
│  7. DOCUMENT                                                                  │
│     └── Record resolution in ticketing system, update knowledge base        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Information Gathering Checklist

**Always collect:**
- [ ] User ID and role
- [ ] Exact time issue occurred
- [ ] URL/page where issue occurred
- [ ] Full error message (screenshot preferred)
- [ ] Browser/device information
- [ ] Steps to reproduce
- [ ] Impact scope (single user/department/all)

**For technical issues:**
- [ ] Request ID from error message
- [ ] Browser console logs (F12)
- [ ] Network tab status codes
- [ ] Mobile app version
- [ ] Device OS version

---

## 3. LOGIN & ACCESS ISSUES

### 3.1 Cannot Log In 🔴

**Symptoms:**
- "Invalid username or password" error
- Login page not loading
- Redirect loop after login

**Troubleshooting Steps:**

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Verify URL is correct (https://erp.smartdairy.com) | Page loads with SSL |
| 2 | Check Caps Lock is off | Password entered correctly |
| 3 | Try incognito/private browsing window | Rules out browser cache issue |
| 4 | Verify account is active in admin panel | Account status = Active |
| 5 | Check for account lockout (failed attempts) | Unlock if necessary |
| 6 | Test with different browser/device | Identifies client-side issue |

**Resolution Commands:**
```python
# Odoo shell - Reset user password
env['res.users'].search([('login', '=', 'user@example.com')]).write({
    'password': 'TempPass123!'
})

# Check account status
user = env['res.users'].search([('login', '=', 'user@example.com')])
print(f"Active: {user.active}, Login Date: {user.login_date}")

# Unlock account (if login attempts exceeded)
env['res.users'].search([('login', '=', 'user@example.com')]).write({
    'login_attempts': 0
})
```

**If login page not loading:**
```bash
# Check Odoo service status
kubectl get pods -n production | grep odoo
kubectl logs -n production deployment/odoo-web --tail=50

# Check database connectivity
kubectl exec -it -n deployment/postgres-client -- \
  pg_isready -h smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com
```

### 3.2 Two-Factor Authentication Issues 🟠

**Problem:** User lost access to 2FA device

**Resolution:**
1. Verify user identity through alternate method (phone/email)
2. Access Odoo as administrator
3. Navigate to Settings → Users → [User]
4. Click "Reset 2FA" button
5. User must re-enroll 2FA on next login

### 3.3 Permission Denied Errors 🟡

**Symptoms:**
- "Access Denied" when opening records
- Cannot see expected menu items
- Cannot perform specific actions

**Troubleshooting:**
```python
# Check user groups
user = env['res.users'].browse(user_id)
print("Groups:", user.groups_id.mapped('name'))

# Check record rules
domain = env['farm.animal']._compute_domain('read', False)
print("Domain restriction:", domain)

# Temporarily add user to group (if approved)
group = env.ref('smart_farm_mgmt.group_farm_manager')
user.write({'groups_id': [(4, group.id)]})
```

---

## 4. FARM MANAGEMENT ISSUES

### 4.1 RFID Tag Not Scanning 🔴

**Symptoms:**
- RFID reader not detecting tags
- "Tag not found" errors
- Duplicate tag warnings

**Hardware Troubleshooting:**

| Step | Check | Resolution |
|------|-------|------------|
| 1 | Reader power LED | Ensure reader is powered on |
| 2 | Bluetooth connection | Re-pair device if needed |
| 3 | Tag position | Tag should be within 10cm of reader |
| 4 | Tag damage | Visually inspect for damage |
| 5 | Reader battery | Charge above 20% |

**Software Troubleshooting:**
```python
# Check if tag exists in system
tag = env['farm.animal'].search([('rfid_tag', '=', 'E200341502001080')])
if tag:
    print(f"Found: {tag.name}, Status: {tag.status}")
else:
    print("Tag not registered in system")

# Check for duplicate tags
self.env.cr.execute("""
    SELECT rfid_tag, COUNT(*) 
    FROM farm_animal 
    WHERE rfid_tag IS NOT NULL 
    GROUP BY rfid_tag 
    HAVING COUNT(*) > 1
""")
duplicates = self.env.cr.fetchall()
```

**RFID Tag Registration:**
```python
# Manually assign RFID tag to animal
animal = env['farm.animal'].browse(animal_id)
animal.write({
    'rfid_tag': 'E200341502001080',
    'rfid_registration_date': fields.Date.today()
})
```

### 4.2 Milk Production Recording Failed 🟠

**Common Causes:**

| Error Message | Cause | Solution |
|--------------|-------|----------|
| "Animal not found" | Invalid RFID or animal ID | Scan tag again, verify animal exists |
| "Not in lactating status" | Animal status is wrong | Update animal status first |
| "Duplicate record" | Already recorded for session | Check existing records |
| "Quantity exceeds maximum" | Entry > 50 liters | Verify quantity is correct |

**Data Recovery:**
```python
# Find orphaned milk records (created but not linked)
orphaned = env['farm.milk.production'].search([
    ('animal_id', '=', False),
    ('create_date', '>', '2026-01-01')
])

# Reassign to correct animal if known
for record in orphaned:
    if record.notes and 'RFID:' in record.notes:
        rfid = record.notes.split('RFID:')[1].strip()
        animal = env['farm.animal'].search([('rfid_tag', '=', rfid)])
        if animal:
            record.write({'animal_id': animal.id})
```

### 4.3 Animal Status Not Updating 🟡

**Problem:** Animal status doesn't reflect actual condition

**Manual Status Update:**
```python
# Force status update
animal = env['farm.animal'].browse(animal_id)
animal.write({'status': 'lactating'})

# Trigger recalculation
animal._compute_age()
animal._compute_milk_average()
```

**Bulk Status Update:**
```python
# Update multiple animals
animals = env['farm.animal'].search([
    ('birth_date', '<', '2025-01-01'),
    ('status', '=', 'calf')
])
animals.write({'status': 'heifer'})
```

---

## 5. E-COMMERCE & SALES ISSUES

### 5.1 Order Cannot Be Confirmed 🔴

**Symptoms:**
- "Insufficient stock" error
- Pricing calculation error
- Credit limit exceeded (B2B)

**Stock Issues:**
```python
# Check product availability
product = env['product.product'].browse(product_id)
available = product.qty_available
virtual = product.virtual_available
reserved = product.outgoing_qty

print(f"On hand: {available}, Reserved: {reserved}, Virtual: {virtual}")

# Force stock check refresh
product._compute_quantities()
```

**Credit Limit Check:**
```python
# Check B2B customer credit
partner = env['res.partner'].browse(partner_id)
credit_used = partner.credit
credit_limit = partner.credit_limit
available = credit_limit - credit_used

print(f"Limit: {credit_limit}, Used: {credit_used}, Available: {available}")

# Temporary credit increase (manager approval required)
partner.write({'credit_limit': credit_limit + 50000})
```

### 5.2 Payment Gateway Failures 🔴

**bKash Specific Issues:**

| Error Code | Meaning | Resolution |
|-----------|---------|------------|
| 2001 | Invalid merchant | Check merchant ID configuration |
| 2002 | Insufficient balance | Customer needs to add funds |
| 2006 | Transaction timeout | Retry transaction |
| 2014 | Duplicate transaction | Check if payment already processed |

**Payment Status Check:**
```python
# Verify payment status
payment = env['payment.transaction'].search([
    ('reference', '=', 'ORDER-12345')
], limit=1, order='id desc')

print(f"State: {payment.state}, Amount: {payment.amount}")

# Force payment confirmation (if verified externally)
payment.write({'state': 'done'})

# Create invoice
order = env['sale.order'].search([('name', '=', 'ORDER-12345')])
order.action_confirm()
```

### 5.3 Subscription Issues 🟠

**Problems:**
- Subscription not renewing
- Wrong delivery quantity
- Pause/resume not working

**Subscription Debugging:**
```python
# Find subscription
subscription = env['sale.subscription'].search([
    ('partner_id', '=', customer_id),
    ('state', '=', 'open')
])

# Check next invoice date
print(f"Next invoice: {subscription.recurring_next_date}")

# Force invoice generation
subscription._cron_recurring_create_invoice()

# Update subscription lines
subscription.write({
    'recurring_invoice_line_ids': [(1, line_id, {'quantity': 10})]
})
```

---

## 6. MOBILE APP ISSUES

### 6.1 App Crashing or Freezing 🟠

**Troubleshooting Steps:**

| Step | Action |
|------|--------|
| 1 | Force close app and reopen |
| 2 | Clear app cache (Android: Settings → Apps → Smart Dairy → Clear Cache) |
| 3 | Check for app updates in Play Store/App Store |
| 4 | Restart device |
| 5 | Uninstall and reinstall app |

**Data Recovery After Reinstall:**
- Login with same credentials
- All synced data will be restored
- Unsynced offline data may be lost

### 6.2 Offline Data Not Syncing 🟡

**Symptoms:**
- Records shown as "Pending"
- "Sync failed" error messages
- Data visible on device but not in web system

**Resolution:**
```python
# Check sync queue (admin view)
pending_syncs = env['mobile.sync.queue'].search([
    ('status', '=', 'pending'),
    ('user_id', '=', user_id)
])

# Force retry
for sync in pending_syncs:
    sync.action_retry()

# Check sync errors
errors = env['mobile.sync.queue'].search([
    ('status', '=', 'error'),
    ('retry_count', '>', 3)
])
```

**Mobile Device Troubleshooting:**
1. Verify internet connection (WiFi or mobile data)
2. Check app has internet permissions
3. Try switching between WiFi and mobile data
4. Check if firewall/VPN is blocking connection

### 6.3 GPS/Location Issues 🟡

**Problems:**
- Location not detected
- Wrong location showing
- "Location services disabled" error

**Resolution Steps:**
1. Enable location services in device settings
2. Grant location permission to Smart Dairy app
3. Ensure GPS signal is strong (outdoor/open area)
4. Restart location services:
   - Android: Settings → Location → Turn off and on
   - iOS: Settings → Privacy → Location Services → Toggle

---

## 7. PAYMENT & BILLING ISSUES

### 7.1 Invoice Generation Failed 🔴

**Symptoms:**
- Invoice stuck in draft
- Wrong amounts calculated
- Tax calculation errors

**Manual Invoice Creation:**
```python
# Create invoice from order
order = env['sale.order'].browse(order_id)
invoice = order._create_invoices(final=True)

# Verify invoice totals
print(f"Untaxed: {invoice.amount_untaxed}")
print(f"Tax: {invoice.amount_tax}")
print(f"Total: {invoice.amount_total}")

# Post invoice
invoice.action_post()
```

### 7.2 Refund Processing 🟠

**Full Order Refund:**
```python
# Create credit note
invoice = env['account.move'].browse(invoice_id)
credit_note = invoice._reverse_moves(
    default_values_list=[{
        'ref': f'Refund for {invoice.name}'
    }]
)
```

**Partial Refund:**
```python
# Create partial credit note
refund = env['account.move'].create({
    'move_type': 'out_refund',
    'partner_id': invoice.partner_id.id,
    'invoice_line_ids': [(0, 0, {
        'product_id': product_id,
        'quantity': 1,
        'price_unit': refund_amount
    })]
})
refund.action_post()
```

---

## 8. IOT & HARDWARE ISSUES

### 8.1 Milk Meter Not Connecting 🔴

**Troubleshooting:**

| Check | Action |
|-------|--------|
| Power | Verify meter is powered on (LED indicator) |
| Network | Check WiFi/Ethernet connection |
| MQTT | Verify MQTT broker connection |
| Calibration | Check if meter needs recalibration |

**MQTT Connection Test:**
```bash
# Test MQTT broker connection
mosquitto_sub -h mqtt.smartdairy.com -p 1883 -t "test" -u "username" -P "password"

# Check device registration
curl https://api.smartdairy.com/iot/devices/DEVICE-ID/status
```

### 8.2 Temperature Sensor Alerts 🔴

**Cold Chain Monitoring:**

| Alert Type | Threshold | Action |
|-----------|-----------|--------|
| Warning | Temp > 5°C for 5 min | Check door seal, compressor |
| Critical | Temp > 8°C for 5 min | Move products to backup unit |
| Emergency | Temp > 12°C | Immediate product transfer |

**Sensor Calibration:**
```python
# Calibrate temperature sensor
sensor = env['iot.sensor'].browse(sensor_id)
sensor.action_calibrate(reference_temp=4.0)
```

---

## 9. PERFORMANCE ISSUES

### 9.1 System Slow/Unresponsive 🔴

**Immediate Checks:**
```bash
# Check system resources
kubectl top nodes
kubectl top pods -n production

# Check database connections
kubectl exec -it deployment/postgres-client -- psql -c \
  "SELECT count(*) FROM pg_stat_activity;"

# Check Redis memory
redis-cli INFO memory | grep used_memory_human
```

**Database Query Analysis:**
```sql
-- Find slow queries
SELECT query, calls, mean_time, total_time 
FROM pg_stat_statements 
ORDER BY mean_time DESC 
LIMIT 10;

-- Kill blocking queries
SELECT pg_terminate_backend(pid) 
FROM pg_stat_activity 
WHERE state = 'idle in transaction' 
  AND state_change < NOW() - INTERVAL '5 minutes';
```

### 9.2 Report Generation Timeout 🟠

**Resolution:**
```python
# Run report asynchronously
report = env['ir.actions.report'].browse(report_id)
report.with_delay().render_qweb_pdf(res_ids=[order_id])

# Generate in smaller batches
for batch in range(0, total_records, 1000):
    records = all_records[batch:batch+1000]
    process_batch(records)
```

---

## 10. DATA SYNCHRONIZATION ISSUES

### 10.1 Mobile to Web Sync Failures 🟠

**Diagnostic Steps:**
1. Check last sync timestamp on mobile
2. Verify user has internet connectivity
3. Check server API status
4. Review sync queue on server

**Force Full Sync:**
```python
# Clear sync checkpoints
env['mobile.sync.checkpoint'].search([
    ('user_id', '=', user_id)
]).unlink()

# Request full resync from mobile app
```

### 10.2 External Integration Failures 🔴

**bKash/Nagad Sync Issues:**
```python
# Check pending transactions
pending = env['payment.transaction'].search([
    ('state', '=', 'pending'),
    ('create_date', '<', fields.Datetime.now() - timedelta(hours=1))
])

# Reconcile status
for tx in pending:
    tx._reconcile_transaction()
```

---

## 11. EMERGENCY PROCEDURES

### 11.1 System Down Emergency 🔴

**Immediate Actions (First 5 minutes):**

| Time | Action | Owner |
|------|--------|-------|
| 0:00 | Acknowledge incident | Support Lead |
| 0:02 | Check system status dashboard | DevOps |
| 0:03 | Notify stakeholders | PMO |
| 0:05 | Begin diagnostic procedures | Technical Team |

**Emergency Contacts:**
- IT Director: +880-XXX-XXXX-001
- DevOps Lead: +880-XXX-XXXX-002
- Support Lead: +880-XXX-XXXX-003
- Cloud Provider Support: AWS Enterprise Support

### 11.2 Data Loss Recovery 🔴

**Recovery Priority:**
1. Customer order data (last 24 hours)
2. Financial transactions (last 24 hours)
3. Farm production data (last 48 hours)
4. Historical data (from backups)

**Recovery Commands:**
```bash
# Restore from backup
aws s3 cp s3://smart-dairy-backups/postgresql/full/latest.dump /tmp/
pg_restore --host=$DB_HOST --username=$DB_USER --dbname=smart_dairy_prod /tmp/latest.dump

# Point-in-time recovery
aws rds restore-db-instance-to-point-in-time \
  --source-db-instance-identifier smart-dairy-prod \
  --target-db-instance-identifier smart-dairy-recovery \
  --restore-time "2026-11-05T10:00:00Z"
```

---

## 12. ESCALATION MATRIX

### 12.1 Support Levels

| Level | Scope | Response Time | Escalation To |
|-------|-------|---------------|---------------|
| L1 - Help Desk | Password resets, basic queries | 15 min | L2 |
| L2 - Technical | Configuration issues, bugs | 1 hour | L3 |
| L3 - Engineering | Complex bugs, performance | 4 hours | Vendor |
| L4 - Vendor | Third-party issues | 8 hours | - |

### 12.2 Escalation Criteria

**Escalate immediately if:**
- Production system down > 15 minutes
- Data loss suspected
- Security breach detected
- Payment processing failures affecting > 10 customers
- Farm IoT systems offline affecting animal welfare

**Escalation Process:**
1. Document issue in ticketing system
2. Notify next level via phone (not just ticket)
3. Provide summary: What, When, Impact, Actions taken
4. Join bridge call if requested

---

## 13. APPENDICES

### Appendix A: Quick Command Reference

```bash
# Check Odoo logs
kubectl logs -n production deployment/odoo-web -f

# Restart Odoo pods
kubectl rollout restart deployment/odoo-web -n production

# Database backup
pg_dump --host=$DB_HOST --username=$DB_USER smart_dairy_prod > backup.sql

# Clear Redis cache
redis-cli FLUSHDB

# Check queue status
kubectl exec -n production deployment/celery-worker -- celery -A app inspect active

# Mobile app sync status
curl https://api.smartdairy.com/health/sync
```

### Appendix B: Error Code Reference

| Code | Description | Section |
|------|-------------|---------|
| AUTH-001 | Invalid credentials | 3.1 |
| AUTH-002 | Account locked | 3.1 |
| FARM-001 | RFID not found | 4.1 |
| FARM-002 | Duplicate milk record | 4.2 |
| SALE-001 | Insufficient stock | 5.1 |
| SALE-002 | Credit limit exceeded | 5.1 |
| PAY-001 | Gateway timeout | 5.2 |
| PAY-002 | Insufficient funds | 5.2 |
| MOB-001 | Sync failed | 6.2 |
| MOB-002 | GPS unavailable | 6.3 |
| IOT-001 | Device offline | 8.1 |
| PERF-001 | Query timeout | 9.1 |

### Appendix C: Support Ticket Template

```
**Issue Summary:** [Brief description]
**Priority:** [Critical/High/Medium/Low]
**Affected User(s):** [User ID(s)]
**System Area:** [Farm/Sales/Payments/Mobile/etc]
**Error Message:** [Exact error text]
**Steps to Reproduce:**
1. 
2. 
3. 

**Expected Result:**
**Actual Result:**
**Screenshots:** [Attach if applicable]
**Environment:** [Browser/Device/OS]
**Time of Issue:** [YYYY-MM-DD HH:MM]
**Business Impact:** [Description]
```

---

**END OF TROUBLESHOOTING GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | November 5, 2026 | Tech Writer | Initial version |
