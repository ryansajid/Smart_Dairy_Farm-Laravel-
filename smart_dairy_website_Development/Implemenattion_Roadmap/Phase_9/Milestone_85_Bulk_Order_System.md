# Milestone 85: Bulk Order System

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M85-v1.0                                          |
| **Milestone**        | 85 - Bulk Order System                                       |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 591-600                                                      |
| **Duration**         | 10 Working Days                                              |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 85 implements the bulk order system enabling B2B customers to place large orders efficiently through quick order forms, CSV bulk uploads, saved templates, and reorder functionality.

### 1.2 Scope

- Quick Order Form (SKU + Quantity grid)
- CSV Bulk Order Upload with validation
- Order Template Model for saved orders
- Reorder from History
- Split Shipment Management
- Partial Order Fulfillment tracking

---

## 2. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | Order Template Model                 | Dev 1  | High     |
| 2  | CSV Parser Service                   | Dev 2  | Critical |
| 3  | Bulk Order Processor                 | Dev 1  | Critical |
| 4  | SKU Lookup Service                   | Dev 1  | High     |
| 5  | Split Shipment Model                 | Dev 2  | High     |
| 6  | OWL Quick Order Form                 | Dev 3  | Critical |
| 7  | CSV Upload Widget                    | Dev 3  | High     |
| 8  | Template Manager UI                  | Dev 3  | High     |
| 9  | Bulk Order API Endpoints             | Dev 2  | High     |
| 10 | Unit & Integration Tests             | All    | High     |

---

## 3. Day-by-Day Development Plan

### Day 591 (Day 1): Order Template & CSV Parser

#### Dev 1 (Backend Lead) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/models/b2b_order_template.py
from odoo import models, fields, api

class B2BOrderTemplate(models.Model):
    _name = 'b2b.order.template'
    _description = 'B2B Order Template'
    _order = 'name'

    name = fields.Char(string='Template Name', required=True)
    customer_id = fields.Many2one('b2b.customer', string='Customer', required=True, ondelete='cascade')
    description = fields.Text(string='Description')

    # Template Lines
    line_ids = fields.One2many('b2b.order.template.line', 'template_id', string='Products')

    # Usage Statistics
    use_count = fields.Integer(string='Times Used', default=0)
    last_used = fields.Datetime(string='Last Used')

    # Settings
    is_default = fields.Boolean(string='Default Template', default=False)
    active = fields.Boolean(default=True)

    def action_create_order(self):
        """Create order from template"""
        self.ensure_one()
        self.write({
            'use_count': self.use_count + 1,
            'last_used': fields.Datetime.now(),
        })

        return {
            'type': 'ir.actions.act_window',
            'name': 'Create Order from Template',
            'res_model': 'b2b.order.from.template.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_template_id': self.id},
        }


class B2BOrderTemplateLine(models.Model):
    _name = 'b2b.order.template.line'
    _description = 'B2B Order Template Line'

    template_id = fields.Many2one('b2b.order.template', string='Template', required=True, ondelete='cascade')
    product_id = fields.Many2one('product.product', string='Product', required=True)
    quantity = fields.Float(string='Default Quantity', default=1.0)
    uom_id = fields.Many2one('uom.uom', string='Unit of Measure')
    notes = fields.Char(string='Notes')


# Bulk Order Processor
class B2BBulkOrderProcessor(models.Model):
    _name = 'b2b.bulk.order.processor'
    _description = 'B2B Bulk Order Processor'

    @api.model
    def process_bulk_order(self, customer_id, lines, options=None):
        """Process bulk order from various sources"""
        options = options or {}
        customer = self.env['b2b.customer'].browse(customer_id)

        # Validate customer
        if customer.state != 'approved':
            return {'success': False, 'error': 'Customer not approved for ordering'}

        # Check credit
        credit = self.env['b2b.credit'].search([('customer_id', '=', customer_id)], limit=1)

        # Process each line
        validated_lines = []
        errors = []
        total_amount = 0

        for idx, line in enumerate(lines, 1):
            result = self._validate_line(line, customer, idx)
            if result.get('error'):
                errors.append(result['error'])
            else:
                validated_lines.append(result['line'])
                total_amount += result['line']['subtotal']

        if errors and not options.get('skip_errors'):
            return {'success': False, 'errors': errors}

        # Check credit availability
        if credit and not options.get('skip_credit_check'):
            available, msg = credit.check_credit_available(total_amount)
            if not available:
                return {'success': False, 'error': msg}

        # Create order
        order = self.env['sale.order'].create({
            'partner_id': customer.partner_id.id,
            'b2b_customer_id': customer_id,
            'order_line': [(0, 0, {
                'product_id': line['product_id'],
                'product_uom_qty': line['quantity'],
                'price_unit': line['unit_price'],
            }) for line in validated_lines],
        })

        return {
            'success': True,
            'order_id': order.id,
            'order_name': order.name,
            'total_lines': len(validated_lines),
            'total_amount': total_amount,
            'skipped_lines': len(errors),
        }

    def _validate_line(self, line, customer, line_num):
        """Validate a single order line"""
        product = None

        # Find product by SKU or ID
        if line.get('sku'):
            product = self.env['product.product'].search([
                ('default_code', '=', line['sku'])
            ], limit=1)
        elif line.get('product_id'):
            product = self.env['product.product'].browse(line['product_id'])

        if not product or not product.exists():
            return {'error': f'Line {line_num}: Product not found'}

        quantity = float(line.get('quantity', 0))
        if quantity <= 0:
            return {'error': f'Line {line_num}: Invalid quantity'}

        # MOQ validation
        moq_result = self.env['b2b.product.moq'].validate_quantity(
            product.id, quantity, customer.id, customer.tier_id.id
        )
        if not moq_result['valid']:
            return {'error': f'Line {line_num}: {moq_result["errors"][0]}'}

        # Get price
        pricing_service = self.env['b2b.pricing.service']
        price = pricing_service.get_product_price(product.id, quantity, customer.id)

        return {
            'line': {
                'product_id': product.id,
                'quantity': quantity,
                'unit_price': price,
                'subtotal': price * quantity,
            }
        }
```

#### Dev 2 (Full-Stack) - 8 hours

```python
# odoo/addons/smart_dairy_b2b/services/csv_parser_service.py
from odoo import models, api
import csv
import io
import base64
import logging

_logger = logging.getLogger(__name__)

class CSVParserService(models.AbstractModel):
    _name = 'b2b.csv.parser.service'
    _description = 'CSV Parser Service'

    REQUIRED_COLUMNS = ['sku', 'quantity']
    OPTIONAL_COLUMNS = ['notes', 'delivery_date']

    @api.model
    def parse_csv(self, file_data, file_name=None):
        """Parse CSV file and return structured data"""
        try:
            # Decode base64
            decoded = base64.b64decode(file_data)
            # Try different encodings
            for encoding in ['utf-8', 'utf-8-sig', 'latin-1']:
                try:
                    content = decoded.decode(encoding)
                    break
                except UnicodeDecodeError:
                    continue
            else:
                return {'success': False, 'error': 'Unable to decode file'}

            # Parse CSV
            reader = csv.DictReader(io.StringIO(content))
            headers = reader.fieldnames

            # Validate headers
            missing = [col for col in self.REQUIRED_COLUMNS if col.lower() not in [h.lower() for h in headers]]
            if missing:
                return {'success': False, 'error': f'Missing required columns: {", ".join(missing)}'}

            # Normalize headers
            header_map = {h.lower(): h for h in headers}

            lines = []
            errors = []

            for row_num, row in enumerate(reader, start=2):
                try:
                    line = {
                        'sku': row.get(header_map.get('sku', 'sku'), '').strip(),
                        'quantity': float(row.get(header_map.get('quantity', 'quantity'), 0)),
                        'notes': row.get(header_map.get('notes', 'notes'), ''),
                        'delivery_date': row.get(header_map.get('delivery_date', 'delivery_date'), ''),
                        'row_number': row_num,
                    }

                    if not line['sku']:
                        errors.append(f'Row {row_num}: SKU is required')
                        continue
                    if line['quantity'] <= 0:
                        errors.append(f'Row {row_num}: Invalid quantity')
                        continue

                    lines.append(line)
                except ValueError as e:
                    errors.append(f'Row {row_num}: {str(e)}')

            return {
                'success': True,
                'lines': lines,
                'total_rows': len(lines),
                'errors': errors,
            }

        except Exception as e:
            _logger.exception('CSV parsing failed')
            return {'success': False, 'error': str(e)}

    @api.model
    def generate_template(self):
        """Generate CSV template"""
        output = io.StringIO()
        writer = csv.writer(output)
        writer.writerow(['sku', 'quantity', 'notes', 'delivery_date'])
        writer.writerow(['SD-MILK-001', '100', 'Urgent delivery', '2026-03-15'])
        writer.writerow(['SD-YOGURT-001', '50', '', '2026-03-15'])
        return base64.b64encode(output.getvalue().encode()).decode()
```

#### Dev 3 (Frontend Lead) - 8 hours

```javascript
/** @odoo-module */
// Quick Order Form Component
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_quick_order.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class B2BQuickOrderForm extends Component {
    static template = "smart_dairy_b2b.B2BQuickOrderForm";

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            lines: [{ sku: "", quantity: 1, product: null, price: 0 }],
            loading: false,
            validating: false,
        });
    }

    addLine() {
        this.state.lines.push({ sku: "", quantity: 1, product: null, price: 0 });
    }

    removeLine(index) {
        if (this.state.lines.length > 1) {
            this.state.lines.splice(index, 1);
        }
    }

    async onSkuChange(index, sku) {
        this.state.lines[index].sku = sku;
        if (sku.length >= 3) {
            await this.lookupProduct(index, sku);
        }
    }

    async lookupProduct(index, sku) {
        this.state.validating = true;
        try {
            const result = await this.orm.call(
                "b2b.sku.lookup.service",
                "lookup",
                [sku, this.props.customerId]
            );

            if (result.found) {
                this.state.lines[index].product = result.product;
                this.state.lines[index].price = result.price;
            } else {
                this.state.lines[index].product = null;
                this.state.lines[index].price = 0;
            }
        } finally {
            this.state.validating = false;
        }
    }

    get totalAmount() {
        return this.state.lines.reduce(
            (sum, line) => sum + (line.quantity * line.price),
            0
        );
    }

    get validLines() {
        return this.state.lines.filter(l => l.product && l.quantity > 0);
    }

    async submitOrder() {
        if (this.validLines.length === 0) {
            this.notification.add("Please add valid products", { type: "warning" });
            return;
        }

        this.state.loading = true;
        try {
            const result = await this.orm.call(
                "b2b.bulk.order.processor",
                "process_bulk_order",
                [
                    this.props.customerId,
                    this.validLines.map(l => ({
                        product_id: l.product.id,
                        quantity: l.quantity,
                    })),
                ]
            );

            if (result.success) {
                this.notification.add(`Order ${result.order_name} created!`, { type: "success" });
                window.location.href = `/b2b/order/${result.order_id}`;
            } else {
                this.notification.add(result.error || "Order failed", { type: "danger" });
            }
        } finally {
            this.state.loading = false;
        }
    }
}
```

---

### Days 592-600: Summary

**Day 592**: Bulk order processor, CSV validation rules
**Day 593**: SKU lookup service, stock check integration
**Day 594**: Template CRUD operations, import/export
**Day 595**: Reorder logic, order history query
**Day 596**: Split shipment model, shipment allocation
**Day 597**: Partial fulfillment tracking, fulfillment status
**Day 598**: Bulk pricing application, unit tests
**Day 599**: Performance optimization, integration tests
**Day 600**: Documentation, UAT preparation

---

## 4. Technical Specifications

### 4.1 CSV Template Format

```csv
sku,quantity,delivery_date,notes
SD-MILK-001,100,2026-03-15,Urgent delivery
SD-YOGURT-001,50,2026-03-15,
SD-CHEESE-001,25,2026-03-20,Cold storage required
```

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/b2b/order` | POST | Create order |
| `/api/v1/b2b/order/bulk-upload` | POST | Upload CSV |
| `/api/v1/b2b/order/templates` | GET | List templates |
| `/api/v1/b2b/order/templates` | POST | Create template |
| `/api/v1/b2b/order/{id}/reorder` | POST | Reorder |

---

## 5. Sign-off Checklist

- [ ] Order Template Model complete
- [ ] CSV Parser with validation
- [ ] Bulk Order Processor working
- [ ] Quick Order Form functional
- [ ] CSV Upload Widget working
- [ ] Reorder functionality
- [ ] Split shipment support
- [ ] API endpoints tested
- [ ] Performance: <30s for 1000 items

---

**Document End**

*Milestone 85: Bulk Order System*
*Days 591-600 | Phase 9: Commerce - B2B Portal Foundation*
