# Milestone 82: B2B Product Catalog

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field                | Details                                                      |
| -------------------- | ------------------------------------------------------------ |
| **Document ID**      | SD-IMPL-P9-M82-v1.0                                          |
| **Milestone**        | 82 - B2B Product Catalog                                     |
| **Phase**            | Phase 9: Commerce - B2B Portal Foundation                    |
| **Days**             | 561-570                                                      |
| **Duration**         | 10 Working Days                                              |
| **Status**           | Draft                                                        |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 82 implements the B2B product catalog system with tier-based pricing, volume discounts, MOQ enforcement, and personalized catalog views. This milestone establishes the pricing engine that will support all B2B commerce operations.

### 1.2 Scope

- B2B Price List Model with tier-based pricing
- Volume Discount Engine with quantity breaks
- MOQ (Minimum Order Quantity) Validation
- Case/Pallet/Bulk Unit Pricing
- Elasticsearch integration for catalog search
- Price calculation caching (Redis)
- OWL Catalog Browse components
- Quick Order Form for repeat purchases

---

## 2. Objectives

1. **Create B2B Price List Models** - Multi-tier pricing structure with customer-specific overrides
2. **Implement Volume Discount Engine** - Automatic discounts based on order quantity
3. **Build MOQ Validation** - Enforce minimum quantities for B2B orders
4. **Design Catalog UI** - OWL-based browsing with search and filters
5. **Integrate Elasticsearch** - Fast product search with faceted filtering
6. **Implement Price Caching** - Redis-based price calculation cache

---

## 3. Key Deliverables

| #  | Deliverable                          | Owner  | Priority |
| -- | ------------------------------------ | ------ | -------- |
| 1  | B2B Price List Model                 | Dev 1  | Critical |
| 2  | Price List Line with Tier Pricing    | Dev 1  | Critical |
| 3  | Volume Discount Calculator           | Dev 1  | High     |
| 4  | MOQ Validation Service               | Dev 1  | High     |
| 5  | Elasticsearch Product Index          | Dev 2  | High     |
| 6  | Price Cache Service (Redis)          | Dev 2  | High     |
| 7  | Catalog API Endpoints                | Dev 2  | High     |
| 8  | OWL Catalog Browse Component         | Dev 3  | Critical |
| 9  | Product Card B2B Component           | Dev 3  | High     |
| 10 | Quick Order Form                     | Dev 3  | High     |

---

## 4. Requirement Traceability

| Req ID       | Description                              | Source | Priority |
| ------------ | ---------------------------------------- | ------ | -------- |
| FR-B2B-005   | Tiered Pricing Structure                 | BRD    | Must     |
| FR-B2B-006   | Private Catalog Views                    | BRD    | Must     |
| SRS-B2B-002  | Pricing calculation <100ms               | SRS    | Must     |
| SRS-B2B-005  | Elasticsearch catalog search <500ms      | SRS    | Must     |

---

## 5. Day-by-Day Development Plan

### Day 561 (Day 1): Price List Models

#### Dev 1 (Backend Lead) - 8 hours

**Task 1.1: Create B2B Price List Model (4h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_pricelist.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)

class B2BPriceList(models.Model):
    _name = 'b2b.pricelist'
    _description = 'B2B Price List'
    _order = 'sequence, name'

    name = fields.Char(string='Price List Name', required=True)
    code = fields.Char(string='Code', required=True)
    sequence = fields.Integer(string='Sequence', default=10)

    # Applicability
    tier_id = fields.Many2one('b2b.customer.tier', string='Customer Tier')
    customer_ids = fields.Many2many('b2b.customer', string='Specific Customers')

    # Validity
    date_start = fields.Date(string='Start Date')
    date_end = fields.Date(string='End Date')
    is_active = fields.Boolean(string='Active', compute='_compute_is_active', store=True)

    # Settings
    currency_id = fields.Many2one('res.currency', string='Currency',
                                   default=lambda self: self.env.company.currency_id)
    include_tax = fields.Boolean(string='Prices Include Tax', default=False)
    rounding_method = fields.Selection([
        ('none', 'No Rounding'),
        ('ceil', 'Round Up'),
        ('floor', 'Round Down'),
        ('half_up', 'Round Half Up'),
    ], string='Rounding Method', default='half_up')
    rounding_precision = fields.Float(string='Rounding Precision', default=0.01)

    # Price List Lines
    line_ids = fields.One2many('b2b.pricelist.line', 'pricelist_id', string='Price Rules')

    # Statistics
    product_count = fields.Integer(string='Products', compute='_compute_product_count')

    active = fields.Boolean(default=True)

    @api.depends('date_start', 'date_end')
    def _compute_is_active(self):
        today = fields.Date.today()
        for record in self:
            start_ok = not record.date_start or record.date_start <= today
            end_ok = not record.date_end or record.date_end >= today
            record.is_active = record.active and start_ok and end_ok

    @api.depends('line_ids')
    def _compute_product_count(self):
        for record in self:
            record.product_count = len(record.line_ids.mapped('product_id'))

    def get_product_price(self, product_id, quantity=1.0, customer_id=None):
        """Calculate product price for given quantity and customer"""
        self.ensure_one()

        # Find applicable price line
        line = self.line_ids.filtered(
            lambda l: l.product_id.id == product_id and l.min_quantity <= quantity
        ).sorted('min_quantity', reverse=True)[:1]

        if not line:
            # Fallback to product list price
            product = self.env['product.product'].browse(product_id)
            return product.lst_price

        price = line.compute_price(quantity)
        return self._apply_rounding(price)

    def _apply_rounding(self, price):
        """Apply rounding based on settings"""
        if self.rounding_method == 'none':
            return price
        precision = self.rounding_precision or 0.01
        if self.rounding_method == 'ceil':
            import math
            return math.ceil(price / precision) * precision
        elif self.rounding_method == 'floor':
            import math
            return math.floor(price / precision) * precision
        else:  # half_up
            return round(price / precision) * precision


class B2BPriceListLine(models.Model):
    _name = 'b2b.pricelist.line'
    _description = 'B2B Price List Line'
    _order = 'product_id, min_quantity'

    pricelist_id = fields.Many2one('b2b.pricelist', string='Price List',
                                    required=True, ondelete='cascade')
    product_id = fields.Many2one('product.product', string='Product', required=True)
    product_tmpl_id = fields.Many2one('product.template', string='Product Template',
                                       related='product_id.product_tmpl_id', store=True)

    # Quantity Breaks
    min_quantity = fields.Float(string='Min Quantity', default=1.0)

    # Pricing
    compute_price = fields.Selection([
        ('fixed', 'Fixed Price'),
        ('percentage', 'Percentage Discount'),
        ('formula', 'Formula'),
    ], string='Compute Price', default='fixed', required=True)

    fixed_price = fields.Monetary(string='Fixed Price')
    percent_discount = fields.Float(string='Discount %')
    formula_base = fields.Selection([
        ('list_price', 'Product List Price'),
        ('cost', 'Product Cost'),
    ], string='Formula Base', default='list_price')
    formula_surcharge = fields.Monetary(string='Surcharge')
    formula_discount = fields.Float(string='Formula Discount %')

    # Tier Pricing (for multi-tier display)
    tier_1_price = fields.Monetary(string='Bronze Price')
    tier_2_price = fields.Monetary(string='Silver Price')
    tier_3_price = fields.Monetary(string='Gold Price')
    tier_4_price = fields.Monetary(string='Platinum Price')

    currency_id = fields.Many2one(related='pricelist_id.currency_id')

    @api.constrains('min_quantity')
    def _check_min_quantity(self):
        for record in self:
            if record.min_quantity < 0:
                raise ValidationError('Minimum quantity cannot be negative!')

    def compute_price(self, quantity=1.0, tier_code=None):
        """Compute price based on configuration"""
        self.ensure_one()

        # Check tier-specific pricing first
        if tier_code:
            tier_prices = {
                'bronze': self.tier_1_price,
                'silver': self.tier_2_price,
                'gold': self.tier_3_price,
                'platinum': self.tier_4_price,
            }
            tier_price = tier_prices.get(tier_code)
            if tier_price:
                return tier_price

        if self.compute_price == 'fixed':
            return self.fixed_price

        # Get base price
        if self.formula_base == 'cost':
            base_price = self.product_id.standard_price
        else:
            base_price = self.product_id.lst_price

        if self.compute_price == 'percentage':
            return base_price * (1 - self.percent_discount / 100)

        # Formula
        price = base_price
        if self.formula_discount:
            price = price * (1 - self.formula_discount / 100)
        if self.formula_surcharge:
            price += self.formula_surcharge

        return max(price, 0)
```

**Task 1.2: Create Volume Discount Engine (4h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_volume_discount.py
from odoo import models, fields, api

class B2BVolumeDiscount(models.Model):
    _name = 'b2b.volume.discount'
    _description = 'B2B Volume Discount'
    _order = 'min_quantity'

    name = fields.Char(string='Rule Name', required=True)
    pricelist_id = fields.Many2one('b2b.pricelist', string='Price List')
    product_id = fields.Many2one('product.product', string='Product')
    product_category_id = fields.Many2one('product.category', string='Product Category')

    # Quantity Breaks
    min_quantity = fields.Float(string='Min Quantity', required=True)
    max_quantity = fields.Float(string='Max Quantity')

    # Discount
    discount_type = fields.Selection([
        ('percentage', 'Percentage'),
        ('fixed', 'Fixed Amount'),
    ], string='Discount Type', default='percentage', required=True)
    discount_value = fields.Float(string='Discount Value', required=True)

    # Unit Configuration
    uom_id = fields.Many2one('uom.uom', string='Unit of Measure')

    active = fields.Boolean(default=True)

    @api.model
    def get_applicable_discount(self, product_id, quantity, pricelist_id=None):
        """Find applicable volume discount for product and quantity"""
        product = self.env['product.product'].browse(product_id)

        domain = [
            ('active', '=', True),
            ('min_quantity', '<=', quantity),
            '|',
            ('max_quantity', '=', False),
            ('max_quantity', '>=', quantity),
            '|',
            ('product_id', '=', product_id),
            ('product_category_id', '=', product.categ_id.id),
        ]

        if pricelist_id:
            domain.append(('pricelist_id', '=', pricelist_id))

        discounts = self.search(domain, order='min_quantity desc', limit=1)

        if discounts:
            return {
                'type': discounts.discount_type,
                'value': discounts.discount_value,
                'rule_name': discounts.name,
            }
        return None

    @api.model
    def apply_discount(self, base_price, product_id, quantity, pricelist_id=None):
        """Apply volume discount to base price"""
        discount = self.get_applicable_discount(product_id, quantity, pricelist_id)

        if not discount:
            return base_price

        if discount['type'] == 'percentage':
            return base_price * (1 - discount['value'] / 100)
        else:
            return max(base_price - discount['value'], 0)
```

#### Dev 2 (Full-Stack) - 8 hours

**Task 2.1: Create Catalog Assignment Model (4h)**

```python
# odoo/addons/smart_dairy_b2b/models/b2b_product_catalog.py
from odoo import models, fields, api

class B2BProductCatalog(models.Model):
    _name = 'b2b.product.catalog'
    _description = 'B2B Product Catalog'

    name = fields.Char(string='Catalog Name', required=True)
    code = fields.Char(string='Catalog Code')

    # Assignment
    tier_ids = fields.Many2many('b2b.customer.tier', string='Available Tiers')
    customer_ids = fields.Many2many('b2b.customer', string='Specific Customers')

    # Products
    product_ids = fields.Many2many('product.product', string='Products')
    category_ids = fields.Many2many('product.category', string='Product Categories')

    # Settings
    is_default = fields.Boolean(string='Default Catalog', default=False)
    show_out_of_stock = fields.Boolean(string='Show Out of Stock', default=True)
    show_prices = fields.Boolean(string='Show Prices', default=True)

    active = fields.Boolean(default=True)

    @api.model
    def get_customer_catalog(self, customer_id):
        """Get applicable catalog for customer"""
        customer = self.env['b2b.customer'].browse(customer_id)

        # Check customer-specific catalogs first
        catalog = self.search([
            ('customer_ids', 'in', customer_id),
            ('active', '=', True),
        ], limit=1)

        if catalog:
            return catalog

        # Check tier-based catalogs
        if customer.tier_id:
            catalog = self.search([
                ('tier_ids', 'in', customer.tier_id.id),
                ('active', '=', True),
            ], limit=1)
            if catalog:
                return catalog

        # Return default catalog
        return self.search([('is_default', '=', True)], limit=1)

    def get_products(self):
        """Get all products in this catalog"""
        self.ensure_one()
        products = self.product_ids

        # Add products from categories
        if self.category_ids:
            category_products = self.env['product.product'].search([
                ('categ_id', 'in', self.category_ids.ids),
                ('sale_ok', '=', True),
            ])
            products |= category_products

        return products


class B2BProductMOQ(models.Model):
    _name = 'b2b.product.moq'
    _description = 'B2B Product MOQ'

    product_id = fields.Many2one('product.product', string='Product', required=True)
    tier_id = fields.Many2one('b2b.customer.tier', string='Customer Tier')
    customer_id = fields.Many2one('b2b.customer', string='Specific Customer')

    # MOQ Settings
    min_quantity = fields.Float(string='Minimum Order Quantity', required=True, default=1.0)
    max_quantity = fields.Float(string='Maximum Order Quantity')
    quantity_multiple = fields.Float(string='Quantity Multiple', default=1.0,
                                      help='Quantity must be multiple of this value')

    # Unit Configuration
    uom_id = fields.Many2one('uom.uom', string='Unit of Measure')

    active = fields.Boolean(default=True)

    @api.model
    def get_moq(self, product_id, customer_id=None, tier_id=None):
        """Get MOQ for product and customer/tier"""
        domain = [
            ('product_id', '=', product_id),
            ('active', '=', True),
        ]

        # Check customer-specific MOQ
        if customer_id:
            moq = self.search(domain + [('customer_id', '=', customer_id)], limit=1)
            if moq:
                return moq

        # Check tier-specific MOQ
        if tier_id:
            moq = self.search(domain + [('tier_id', '=', tier_id)], limit=1)
            if moq:
                return moq

        # Return default MOQ
        return self.search(domain + [
            ('customer_id', '=', False),
            ('tier_id', '=', False),
        ], limit=1)

    @api.model
    def validate_quantity(self, product_id, quantity, customer_id=None, tier_id=None):
        """Validate quantity against MOQ rules"""
        moq = self.get_moq(product_id, customer_id, tier_id)

        if not moq:
            return {'valid': True}

        errors = []

        if quantity < moq.min_quantity:
            errors.append(f'Minimum order quantity is {moq.min_quantity}')

        if moq.max_quantity and quantity > moq.max_quantity:
            errors.append(f'Maximum order quantity is {moq.max_quantity}')

        if moq.quantity_multiple and quantity % moq.quantity_multiple != 0:
            errors.append(f'Quantity must be multiple of {moq.quantity_multiple}')

        return {
            'valid': len(errors) == 0,
            'errors': errors,
            'moq': {
                'min': moq.min_quantity,
                'max': moq.max_quantity,
                'multiple': moq.quantity_multiple,
            }
        }
```

**Task 2.2: Elasticsearch Integration Setup (4h)**

```python
# odoo/addons/smart_dairy_b2b/services/elasticsearch_service.py
from odoo import models, api
from elasticsearch import Elasticsearch
import logging
import json

_logger = logging.getLogger(__name__)

class ElasticsearchService(models.AbstractModel):
    _name = 'b2b.elasticsearch.service'
    _description = 'Elasticsearch Service'

    @api.model
    def get_client(self):
        """Get Elasticsearch client"""
        config = self.env['ir.config_parameter'].sudo()
        host = config.get_param('b2b.elasticsearch.host', 'localhost')
        port = config.get_param('b2b.elasticsearch.port', '9200')
        return Elasticsearch([f'http://{host}:{port}'])

    @api.model
    def create_product_index(self):
        """Create product index with mappings"""
        client = self.get_client()
        index_name = 'b2b_products'

        mappings = {
            'mappings': {
                'properties': {
                    'id': {'type': 'integer'},
                    'name': {'type': 'text', 'analyzer': 'standard'},
                    'name_bn': {'type': 'text', 'analyzer': 'standard'},
                    'sku': {'type': 'keyword'},
                    'barcode': {'type': 'keyword'},
                    'description': {'type': 'text'},
                    'category_id': {'type': 'integer'},
                    'category_name': {'type': 'keyword'},
                    'list_price': {'type': 'float'},
                    'qty_available': {'type': 'float'},
                    'is_available': {'type': 'boolean'},
                    'tags': {'type': 'keyword'},
                    'updated_at': {'type': 'date'},
                }
            },
            'settings': {
                'number_of_shards': 1,
                'number_of_replicas': 1,
            }
        }

        if not client.indices.exists(index=index_name):
            client.indices.create(index=index_name, body=mappings)
            _logger.info(f'Created Elasticsearch index: {index_name}')

    @api.model
    def index_product(self, product):
        """Index a single product"""
        client = self.get_client()

        doc = {
            'id': product.id,
            'name': product.name,
            'name_bn': product.with_context(lang='bn_BD').name,
            'sku': product.default_code or '',
            'barcode': product.barcode or '',
            'description': product.description_sale or '',
            'category_id': product.categ_id.id,
            'category_name': product.categ_id.name,
            'list_price': product.lst_price,
            'qty_available': product.qty_available,
            'is_available': product.qty_available > 0,
            'tags': product.product_tag_ids.mapped('name'),
            'updated_at': fields.Datetime.now().isoformat(),
        }

        client.index(index='b2b_products', id=product.id, body=doc)

    @api.model
    def index_all_products(self):
        """Index all B2B products"""
        products = self.env['product.product'].search([
            ('sale_ok', '=', True),
            ('active', '=', True),
        ])

        for product in products:
            try:
                self.index_product(product)
            except Exception as e:
                _logger.error(f'Failed to index product {product.id}: {e}')

        _logger.info(f'Indexed {len(products)} products')

    @api.model
    def search_products(self, query, filters=None, page=1, page_size=20):
        """Search products with Elasticsearch"""
        client = self.get_client()

        body = {
            'from': (page - 1) * page_size,
            'size': page_size,
            'query': {
                'bool': {
                    'must': [],
                    'filter': [],
                }
            }
        }

        # Add text search
        if query:
            body['query']['bool']['must'].append({
                'multi_match': {
                    'query': query,
                    'fields': ['name^3', 'name_bn^3', 'sku^2', 'description', 'category_name'],
                    'fuzziness': 'AUTO',
                }
            })
        else:
            body['query']['bool']['must'].append({'match_all': {}})

        # Add filters
        if filters:
            if filters.get('category_id'):
                body['query']['bool']['filter'].append({
                    'term': {'category_id': filters['category_id']}
                })
            if filters.get('in_stock'):
                body['query']['bool']['filter'].append({
                    'term': {'is_available': True}
                })
            if filters.get('price_min'):
                body['query']['bool']['filter'].append({
                    'range': {'list_price': {'gte': filters['price_min']}}
                })
            if filters.get('price_max'):
                body['query']['bool']['filter'].append({
                    'range': {'list_price': {'lte': filters['price_max']}}
                })

        result = client.search(index='b2b_products', body=body)

        return {
            'total': result['hits']['total']['value'],
            'products': [hit['_source'] for hit in result['hits']['hits']],
            'page': page,
            'page_size': page_size,
        }
```

#### Dev 3 (Frontend Lead) - 8 hours

**Task 3.1: Create OWL Catalog Browse Component (8h)**

```javascript
/** @odoo-module */
// odoo/addons/smart_dairy_b2b/static/src/js/b2b_catalog.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { registry } from "@web/core/registry";

export class B2BCatalogBrowser extends Component {
    static template = "smart_dairy_b2b.B2BCatalogBrowser";
    static props = {
        customerId: { type: Number, optional: true },
    };

    setup() {
        this.orm = useService("orm");
        this.notification = useService("notification");

        this.state = useState({
            products: [],
            categories: [],
            loading: true,
            searchQuery: "",
            selectedCategory: null,
            filters: {
                inStock: false,
                priceMin: null,
                priceMax: null,
            },
            sortBy: "name",
            sortOrder: "asc",
            page: 1,
            pageSize: 20,
            totalProducts: 0,
            viewMode: "grid", // grid or list
            cart: {},
        });

        onWillStart(async () => {
            await this.loadCategories();
            await this.loadProducts();
        });
    }

    async loadCategories() {
        try {
            const categories = await this.orm.searchRead(
                "product.category",
                [["parent_id", "=", false]],
                ["id", "name", "child_id"]
            );
            this.state.categories = categories;
        } catch (error) {
            console.error("Failed to load categories:", error);
        }
    }

    async loadProducts() {
        this.state.loading = true;
        try {
            const result = await this.orm.call(
                "b2b.catalog.service",
                "search_catalog",
                [],
                {
                    query: this.state.searchQuery,
                    category_id: this.state.selectedCategory,
                    filters: this.state.filters,
                    sort_by: this.state.sortBy,
                    sort_order: this.state.sortOrder,
                    page: this.state.page,
                    page_size: this.state.pageSize,
                    customer_id: this.props.customerId,
                }
            );

            this.state.products = result.products;
            this.state.totalProducts = result.total;
        } catch (error) {
            this.notification.add("Failed to load products", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    onSearch(event) {
        this.state.searchQuery = event.target.value;
        this.state.page = 1;
        this.debounceSearch();
    }

    debounceSearch = this.debounce(() => this.loadProducts(), 300);

    debounce(func, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    selectCategory(categoryId) {
        this.state.selectedCategory = categoryId;
        this.state.page = 1;
        this.loadProducts();
    }

    toggleFilter(filterName) {
        this.state.filters[filterName] = !this.state.filters[filterName];
        this.state.page = 1;
        this.loadProducts();
    }

    updatePriceFilter(field, value) {
        this.state.filters[field] = value ? parseFloat(value) : null;
        this.debounceSearch();
    }

    sortProducts(sortBy) {
        if (this.state.sortBy === sortBy) {
            this.state.sortOrder = this.state.sortOrder === "asc" ? "desc" : "asc";
        } else {
            this.state.sortBy = sortBy;
            this.state.sortOrder = "asc";
        }
        this.loadProducts();
    }

    setViewMode(mode) {
        this.state.viewMode = mode;
    }

    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.state.page = page;
            this.loadProducts();
        }
    }

    get totalPages() {
        return Math.ceil(this.state.totalProducts / this.state.pageSize);
    }

    updateCartQuantity(productId, quantity) {
        const qty = parseInt(quantity) || 0;
        if (qty > 0) {
            this.state.cart[productId] = qty;
        } else {
            delete this.state.cart[productId];
        }
    }

    getCartQuantity(productId) {
        return this.state.cart[productId] || 0;
    }

    async addToCart(product, quantity) {
        // Validate MOQ
        const validation = await this.orm.call(
            "b2b.product.moq",
            "validate_quantity",
            [product.id, quantity, this.props.customerId]
        );

        if (!validation.valid) {
            this.notification.add(validation.errors.join(", "), { type: "warning" });
            return;
        }

        this.state.cart[product.id] = quantity;
        this.notification.add(`Added ${quantity} x ${product.name} to cart`, { type: "success" });
    }

    get cartItemCount() {
        return Object.values(this.state.cart).reduce((sum, qty) => sum + qty, 0);
    }

    get cartTotal() {
        return Object.entries(this.state.cart).reduce((total, [productId, qty]) => {
            const product = this.state.products.find(p => p.id === parseInt(productId));
            return total + (product ? product.b2b_price * qty : 0);
        }, 0);
    }
}

registry.category("public_components").add("B2BCatalogBrowser", B2BCatalogBrowser);
```

**End of Day 561 Deliverables:**
- [ ] B2B Price List Model with tier support
- [ ] Price List Line with volume pricing
- [ ] Volume Discount Engine
- [ ] Product Catalog Assignment model
- [ ] MOQ Model and validation
- [ ] Elasticsearch service foundation
- [ ] OWL Catalog Browser component structure

---

### Day 562-570 Summary

#### Day 562: Tier Pricing Engine & Redis Cache
- **Dev 1**: Tier pricing calculation engine, price override logic
- **Dev 2**: Redis price cache service, cache invalidation
- **Dev 3**: Product Card B2B component with tier prices

#### Day 563: Volume Discount Logic & Search
- **Dev 1**: Volume discount calculation, tier-based discounts
- **Dev 2**: Elasticsearch SKU search, barcode lookup
- **Dev 3**: Bulk Unit Selector component (case/pallet)

#### Day 564: MOQ Enforcement & API
- **Dev 1**: MOQ validation in order creation, error handling
- **Dev 2**: Catalog API endpoints, pagination
- **Dev 3**: Price Display Widget with discount breakdown

#### Day 565: Elasticsearch Indexing & Filters
- **Dev 1**: Case/Pallet/Bulk pricing configuration
- **Dev 2**: Product indexing cron, faceted search filters
- **Dev 3**: Filter Components (category, price, stock)

#### Day 566: Contract Price Override
- **Dev 1**: Contract price override logic
- **Dev 2**: Product import/export tools
- **Dev 3**: Category Navigation tree component

#### Day 567: Price Validity & Alerts
- **Dev 1**: Price validity periods, scheduled price changes
- **Dev 2**: Price change alerts, notification service
- **Dev 3**: Compare Products feature

#### Day 568: Price History & Quick Order
- **Dev 1**: Price history tracking, audit log
- **Dev 2**: Unit tests for pricing engine
- **Dev 3**: Quick Order Form (SKU grid entry)

#### Day 569: Performance & Mobile
- **Dev 1**: Query optimization, pricing reports
- **Dev 2**: Integration tests, load testing
- **Dev 3**: Mobile catalog responsive design

#### Day 570: Documentation & Handoff
- **Dev 1**: Technical documentation, caching strategy
- **Dev 2**: UAT test cases, API documentation
- **Dev 3**: Component documentation, final polish

---

## 6. Technical Specifications

### 6.1 Database Schema

```sql
-- B2B Price List
CREATE TABLE b2b_pricelist (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL,
    sequence INTEGER DEFAULT 10,
    tier_id INTEGER REFERENCES b2b_customer_tier(id),
    date_start DATE,
    date_end DATE,
    currency_id INTEGER REFERENCES res_currency(id),
    include_tax BOOLEAN DEFAULT FALSE,
    rounding_method VARCHAR(20) DEFAULT 'half_up',
    active BOOLEAN DEFAULT TRUE
);

-- B2B Price List Line
CREATE TABLE b2b_pricelist_line (
    id SERIAL PRIMARY KEY,
    pricelist_id INTEGER REFERENCES b2b_pricelist(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES product_product(id),
    min_quantity DECIMAL(10,2) DEFAULT 1.0,
    compute_price VARCHAR(20) DEFAULT 'fixed',
    fixed_price DECIMAL(10,2),
    percent_discount DECIMAL(5,2),
    tier_1_price DECIMAL(10,2),
    tier_2_price DECIMAL(10,2),
    tier_3_price DECIMAL(10,2),
    tier_4_price DECIMAL(10,2)
);

-- Indexes for performance
CREATE INDEX idx_pricelist_tier ON b2b_pricelist(tier_id);
CREATE INDEX idx_pricelist_line_product ON b2b_pricelist_line(product_id);
CREATE INDEX idx_pricelist_line_quantity ON b2b_pricelist_line(min_quantity);
```

### 6.2 API Endpoints

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/b2b/catalog` | GET | Search products | User |
| `/api/v1/b2b/catalog/product/{id}` | GET | Get product details | User |
| `/api/v1/b2b/pricing/{product_id}` | GET | Get product pricing | User |
| `/api/v1/b2b/pricing/tier/{tier}` | GET | Get tier pricing | User |
| `/api/v1/b2b/pricing/calculate` | POST | Calculate cart pricing | User |

### 6.3 Redis Cache Keys

```
b2b:price:{pricelist_id}:{product_id}:{quantity} -> price value (TTL: 1h)
b2b:catalog:{customer_id} -> catalog product IDs (TTL: 15m)
b2b:moq:{product_id}:{tier_id} -> MOQ config (TTL: 1h)
```

---

## 7. Testing Requirements

| Test Case | Description | Priority |
|-----------|-------------|----------|
| test_tier_pricing | Tier-based price calculation | Critical |
| test_volume_discount | Volume discount application | Critical |
| test_moq_validation | MOQ enforcement | High |
| test_price_cache | Redis cache hit/miss | High |
| test_elasticsearch_search | Product search accuracy | High |
| test_catalog_assignment | Customer catalog access | High |

---

## 8. Sign-off Checklist

- [ ] B2B Price List Model complete
- [ ] Tier-based pricing working
- [ ] Volume discounts calculating correctly
- [ ] MOQ validation enforcing rules
- [ ] Elasticsearch search functional
- [ ] Redis price cache operational
- [ ] OWL Catalog Browser complete
- [ ] Quick Order Form working
- [ ] API endpoints tested
- [ ] Unit tests passing (>80%)
- [ ] Performance benchmarks met (<100ms pricing)

---

**Document End**

*Milestone 82: B2B Product Catalog*
*Days 561-570 | Phase 9: Commerce - B2B Portal Foundation*
