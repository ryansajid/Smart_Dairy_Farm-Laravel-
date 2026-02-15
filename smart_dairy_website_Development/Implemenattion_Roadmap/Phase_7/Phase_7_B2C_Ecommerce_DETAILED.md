# PHASE 7: OPERATIONS - B2C E-commerce Core
## DETAILED IMPLEMENTATION PLAN

---

**Document Control**

| Attribute | Value |
|-----------|-------|
| **Phase Number** | 7 of 15 |
| **Phase Name** | Operations - B2C E-commerce Core |
| **Duration** | Days 301-350 (50 working days) |
| **Milestones** | 10 milestones × 5 days each |
| **Document Version** | 1.0 |
| **Release Date** | February 3, 2026 |
| **Dependencies** | Phase 1-6 Complete |
| **Team Size** | 3 Full-Stack Developers + 1 UX Designer |

---

## TABLE OF CONTENTS

1. [Phase Overview](#phase-overview)
2. [Phase Objectives](#phase-objectives)
3. [Key Deliverables](#key-deliverables)
4. [Milestone 1: Product Catalog Enhancement (Days 301-305)](#milestone-1-product-catalog-enhancement-days-301-305)
5. [Milestone 2: Customer Account Management (Days 306-310)](#milestone-2-customer-account-management-days-306-310)
6. [Milestone 3: Shopping Experience (Days 311-315)](#milestone-3-shopping-experience-days-311-315)
7. [Milestone 4: Checkout Optimization (Days 316-320)](#milestone-4-checkout-optimization-days-316-320)
8. [Milestone 5: Order Management (Days 321-325)](#milestone-5-order-management-days-321-325)
9. [Milestone 6: Wishlist & Favorites (Days 326-330)](#milestone-6-wishlist--favorites-days-326-330)
10. [Milestone 7: Product Reviews & Ratings (Days 331-335)](#milestone-7-product-reviews--ratings-days-331-335)
11. [Milestone 8: Email Marketing Integration (Days 336-340)](#milestone-8-email-marketing-integration-days-336-340)
12. [Milestone 9: Customer Support Chat (Days 341-345)](#milestone-9-customer-support-chat-days-341-345)
13. [Milestone 10: E-commerce Testing (Days 346-350)](#milestone-10-e-commerce-testing-days-346-350)
14. [Phase Success Criteria](#phase-success-criteria)

---

## PHASE OVERVIEW

Phase 7 transforms Smart Dairy's website into a complete B2C e-commerce platform with advanced features for online shopping, customer engagement, and conversion optimization.

### Phase Context

Building on the public website (Phase 4) and mobile apps (Phase 6), Phase 7 creates:
- Advanced product discovery and search
- Personalized shopping experiences
- Optimized checkout flow (multi-step, one-click)
- Customer loyalty and rewards program
- Review and rating system
- Automated email marketing
- Live customer support
- Comprehensive order management

---

## PHASE OBJECTIVES

### Primary Objectives

1. **Conversion Optimization**: Increase conversion rate from 2% to 5%
2. **Personalization**: Product recommendations based on browsing and purchase history
3. **Customer Retention**: Loyalty program with points, rewards, referrals
4. **Social Proof**: Reviews, ratings, testimonials from verified customers
5. **Cart Recovery**: Abandoned cart emails with incentives
6. **Customer Support**: Real-time chat, FAQ, self-service portal
7. **Mobile-First**: Responsive design optimized for mobile shopping
8. **Performance**: Page load time < 2 seconds, Lighthouse score > 90

### Secondary Objectives

- A/B testing framework for optimization
- Inventory alerts (back-in-stock notifications)
- Gift cards and promotional codes
- Subscription products (milk delivery)
- Customer segmentation and targeting
- Multi-language shopping experience

---

## KEY DELIVERABLES

### Product Catalog Deliverables
1. ✓ Advanced search with filters (10+ attributes)
2. ✓ Product recommendations (AI-powered)
3. ✓ Recently viewed products
4. ✓ Related products/cross-sell
5. ✓ Product comparison tool
6. ✓ Virtual product tours (360° images)

### Customer Account Deliverables
7. ✓ Customer dashboard (orders, points, preferences)
8. ✓ Address book (multiple addresses)
9. ✓ Payment method management
10. ✓ Order history with reorder
11. ✓ Loyalty points and rewards
12. ✓ Referral program

### Shopping Experience Deliverables
13. ✓ Quick add to cart
14. ✓ Product bundles and combos
15. ✓ Wishlist/save for later
16. ✓ Product notifications
17. ✓ Size/quantity guides
18. ✓ Stock level indicators

### Checkout Deliverables
19. ✓ Multi-step checkout (4 steps)
20. ✓ Guest checkout option
21. ✓ Saved addresses auto-fill
22. ✓ One-click checkout
23. ✓ Promo code application
24. ✓ Order summary preview

### Order Management Deliverables
25. ✓ Real-time order tracking
26. ✓ Order modification (before dispatch)
27. ✓ Order cancellation workflow
28. ✓ Return/refund request
29. ✓ Delivery reschedule
30. ✓ Order invoice download

---

## MILESTONE 1: Product Catalog Enhancement (Days 301-305)

**Objective**: Create advanced product discovery features including smart search, filtering, recommendations, and product comparison tools.

**Duration**: 5 working days

---

### Day 301: Advanced Search & Filtering

**[Dev 1] - Elasticsearch Integration (8h)**
- Set up Elasticsearch for product search:
  ```python
  # odoo/addons/smart_dairy_ecommerce/models/product_search.py
  from odoo import models, fields, api
  from elasticsearch import Elasticsearch
  import json

  class ProductProduct(models.Model):
      _inherit = 'product.product'

      search_keywords = fields.Char(
          string='Search Keywords',
          help='Additional keywords for search optimization'
      )

      search_vector = fields.Text(
          string='Search Vector',
          compute='_compute_search_vector',
          store=True
      )

      @api.depends('name', 'description_sale', 'categ_id', 'search_keywords')
      def _compute_search_vector(self):
          """Generate search vector for Elasticsearch"""
          for product in self:
              keywords = []
              if product.name:
                  keywords.append(product.name)
              if product.description_sale:
                  keywords.append(product.description_sale)
              if product.categ_id:
                  keywords.append(product.categ_id.name)
              if product.search_keywords:
                  keywords.append(product.search_keywords)

              product.search_vector = ' '.join(keywords).lower()

      def _get_elasticsearch_client(self):
          """Get Elasticsearch client"""
          config = self.env['ir.config_parameter'].sudo()
          es_host = config.get_param('elasticsearch.host', 'localhost')
          es_port = int(config.get_param('elasticsearch.port', 9200))

          return Elasticsearch([{'host': es_host, 'port': es_port}])

      def index_product_elasticsearch(self):
          """Index product in Elasticsearch"""
          self.ensure_one()
          es = self._get_elasticsearch_client()

          doc = {
              'id': self.id,
              'name': self.name,
              'description': self.description_sale or '',
              'category': self.categ_id.name,
              'price': self.list_price,
              'in_stock': self.qty_available > 0,
              'image_url': f'/web/image/product.product/{self.id}/image_1920',
              'tags': self.search_keywords.split(',') if self.search_keywords else [],
              'search_vector': self.search_vector,
          }

          es.index(index='products', id=self.id, body=doc)

      @api.model
      def search_products_elasticsearch(self, query, filters=None, page=1, limit=20):
          """
          Search products using Elasticsearch

          Args:
              query: Search query string
              filters: Dict of filters (category, price_range, in_stock, etc.)
              page: Page number
              limit: Results per page

          Returns:
              Dict with products and metadata
          """
          es = self._get_elasticsearch_client()

          # Build Elasticsearch query
          must = []
          should = []
          filter_clauses = []

          # Text search
          if query:
              should.append({
                  'match': {
                      'name': {
                          'query': query,
                          'boost': 3,
                          'fuzziness': 'AUTO'
                      }
                  }
              })
              should.append({
                  'match': {
                      'description': {
                          'query': query,
                          'boost': 1
                      }
                  }
              })
              should.append({
                  'match': {
                      'search_vector': {
                          'query': query,
                          'boost': 2
                      }
                  }
              })

          # Filters
          if filters:
              # Category filter
              if filters.get('category'):
                  filter_clauses.append({
                      'term': {'category.keyword': filters['category']}
                  })

              # Price range filter
              if filters.get('price_min') or filters.get('price_max'):
                  price_filter = {'range': {'price': {}}}
                  if filters.get('price_min'):
                      price_filter['range']['price']['gte'] = filters['price_min']
                  if filters.get('price_max'):
                      price_filter['range']['price']['lte'] = filters['price_max']
                  filter_clauses.append(price_filter)

              # In stock filter
              if filters.get('in_stock'):
                  filter_clauses.append({
                      'term': {'in_stock': True}
                  })

          # Build final query
          es_query = {
              'bool': {
                  'must': must if must else {'match_all': {}},
                  'should': should,
                  'filter': filter_clauses,
                  'minimum_should_match': 1 if should else 0,
              }
          }

          # Execute search
          from_offset = (page - 1) * limit
          result = es.search(
              index='products',
              body={
                  'query': es_query,
                  'from': from_offset,
                  'size': limit,
                  'sort': [
                      {'_score': 'desc'},
                      {'name.keyword': 'asc'}
                  ]
              }
          )

          # Format results
          hits = result['hits']['hits']
          products = []
          for hit in hits:
              source = hit['_source']
              products.append({
                  'id': source['id'],
                  'name': source['name'],
                  'description': source['description'],
                  'category': source['category'],
                  'price': source['price'],
                  'in_stock': source['in_stock'],
                  'image_url': source['image_url'],
                  'relevance_score': hit['_score'],
              })

          return {
              'products': products,
              'total': result['hits']['total']['value'],
              'page': page,
              'limit': limit,
              'total_pages': (result['hits']['total']['value'] + limit - 1) // limit,
          }

      @api.model
      def reindex_all_products(self):
          """Reindex all products in Elasticsearch"""
          products = self.search([('sale_ok', '=', True)])
          for product in products:
              product.index_product_elasticsearch()
  ```
  (5h)

- Create search API endpoint:
  ```python
  # odoo/addons/smart_dairy_ecommerce/controllers/product_search.py
  from odoo import http
  from odoo.http import request
  import json

  class ProductSearchController(http.Controller):

      @http.route('/shop/search', type='json', auth='public', methods=['POST'], csrf=False)
      def search_products(self, **kwargs):
          """
          Product search endpoint

          Request:
          {
              "query": "milk",
              "filters": {
                  "category": "Dairy Products",
                  "price_min": 50,
                  "price_max": 500,
                  "in_stock": true
              },
              "page": 1,
              "limit": 20,
              "sort": "relevance"
          }
          """
          query = kwargs.get('query', '')
          filters = kwargs.get('filters', {})
          page = kwargs.get('page', 1)
          limit = kwargs.get('limit', 20)

          # Perform search
          Product = request.env['product.product'].sudo()
          results = Product.search_products_elasticsearch(
              query=query,
              filters=filters,
              page=page,
              limit=limit
          )

          return {
              'status': 'success',
              'data': results
          }

      @http.route('/shop/autocomplete', type='json', auth='public', methods=['POST'], csrf=False)
      def autocomplete_search(self, **kwargs):
          """
          Autocomplete suggestions

          Request:
          {
              "query": "mil"
          }
          """
          query = kwargs.get('query', '')
          if len(query) < 2:
              return {'status': 'success', 'data': {'suggestions': []}}

          # Get suggestions
          Product = request.env['product.product'].sudo()
          products = Product.search([
              ('name', 'ilike', query),
              ('sale_ok', '=', True)
          ], limit=10, order='name asc')

          suggestions = []
          for product in products:
              suggestions.append({
                  'id': product.id,
                  'name': product.name,
                  'category': product.categ_id.name,
                  'price': product.list_price,
                  'image_url': f'/web/image/product.product/{product.id}/image_128',
              })

          return {
              'status': 'success',
              'data': {'suggestions': suggestions}
          }
  ```
  (3h)

**[Dev 2] - Advanced Filtering UI (8h)**
- Create filter sidebar component:
  ```xml
  <!-- odoo/addons/smart_dairy_ecommerce/views/shop_templates.xml -->
  <template id="product_filter_sidebar" name="Product Filter Sidebar">
      <div class="filter-sidebar">
          <!-- Category Filter -->
          <div class="filter-group">
              <h4>Category</h4>
              <div class="category-tree">
                  <t t-foreach="categories" t-as="category">
                      <div class="category-item">
                          <label>
                              <input type="checkbox"
                                     name="category"
                                     t-att-value="category.id"
                                     t-att-data-name="category.name"/>
                              <span t-esc="category.name"/>
                              <span class="count" t-esc="'(%s)' % category.product_count"/>
                          </label>
                          <!-- Subcategories -->
                          <t t-if="category.child_ids">
                              <div class="subcategories ml-3">
                                  <t t-foreach="category.child_ids" t-as="subcategory">
                                      <label>
                                          <input type="checkbox"
                                                 name="category"
                                                 t-att-value="subcategory.id"
                                                 t-att-data-name="subcategory.name"/>
                                          <span t-esc="subcategory.name"/>
                                      </label>
                                  </t>
                              </div>
                          </t>
                      </div>
                  </t>
              </div>
          </div>

          <!-- Price Range Filter -->
          <div class="filter-group">
              <h4>Price Range</h4>
              <div class="price-slider">
                  <input type="range"
                         id="price-min"
                         name="price_min"
                         min="0"
                         t-att-max="max_price"
                         value="0"/>
                  <input type="range"
                         id="price-max"
                         name="price_max"
                         min="0"
                         t-att-max="max_price"
                         t-att-value="max_price"/>
                  <div class="price-labels">
                      <span id="price-min-label">৳0</span>
                      <span> - </span>
                      <span id="price-max-label" t-esc="'৳%s' % max_price"/>
                  </div>
              </div>
          </div>

          <!-- Availability Filter -->
          <div class="filter-group">
              <h4>Availability</h4>
              <label>
                  <input type="checkbox" name="in_stock" value="1"/>
                  <span>In Stock Only</span>
              </label>
          </div>

          <!-- Brand Filter -->
          <div class="filter-group">
              <h4>Brand</h4>
              <t t-foreach="brands" t-as="brand">
                  <label>
                      <input type="checkbox" name="brand" t-att-value="brand.id"/>
                      <span t-esc="brand.name"/>
                  </label>
              </t>
          </div>

          <!-- Rating Filter -->
          <div class="filter-group">
              <h4>Customer Rating</h4>
              <t t-foreach="[5, 4, 3, 2, 1]" t-as="rating">
                  <label>
                      <input type="checkbox" name="rating" t-att-value="rating"/>
                      <span class="stars">
                          <t t-foreach="range(rating)" t-as="star">
                              <i class="fa fa-star"/>
                          </t>
                          <span t-esc="'and up'"/>
                      </span>
                  </label>
              </t>
          </div>

          <!-- Clear Filters -->
          <button class="btn btn-secondary btn-block mt-3" id="clear-filters">
              Clear All Filters
          </button>
      </div>
  </template>
  ```
  (4h)

- Create JavaScript for filter interactions:
  ```javascript
  // odoo/addons/smart_dairy_ecommerce/static/src/js/product_filters.js
  odoo.define('smart_dairy_ecommerce.ProductFilters', function (require) {
      'use strict';

      const publicWidget = require('web.public.widget');

      publicWidget.registry.ProductFilters = publicWidget.Widget.extend({
          selector: '.filter-sidebar',
          events: {
              'change input[type="checkbox"]': '_onFilterChange',
              'change input[type="range"]': '_onPriceRangeChange',
              'click #clear-filters': '_onClearFilters',
          },

          start: function () {
              this._super.apply(this, arguments);
              this._initializePriceSlider();
          },

          _initializePriceSlider: function () {
              const $minSlider = this.$('#price-min');
              const $maxSlider = this.$('#price-max');
              const $minLabel = this.$('#price-min-label');
              const $maxLabel = this.$('#price-max-label');

              const updateLabels = () => {
                  const minVal = parseInt($minSlider.val());
                  const maxVal = parseInt($maxSlider.val());

                  // Prevent min from exceeding max
                  if (minVal > maxVal) {
                      $minSlider.val(maxVal);
                  }

                  $minLabel.text(`৳${$minSlider.val()}`);
                  $maxLabel.text(`৳${$maxSlider.val()}`);
              };

              $minSlider.on('input', updateLabels);
              $maxSlider.on('input', updateLabels);
          },

          _getActiveFilters: function () {
              const filters = {
                  categories: [],
                  brands: [],
                  ratings: [],
                  price_min: null,
                  price_max: null,
                  in_stock: false,
              };

              // Category filters
              this.$('input[name="category"]:checked').each(function () {
                  filters.categories.push($(this).data('name'));
              });

              // Brand filters
              this.$('input[name="brand"]:checked').each(function () {
                  filters.brands.push(parseInt($(this).val()));
              });

              // Rating filters
              this.$('input[name="rating"]:checked').each(function () {
                  filters.ratings.push(parseInt($(this).val()));
              });

              // Price range
              filters.price_min = parseInt(this.$('#price-min').val());
              filters.price_max = parseInt(this.$('#price-max').val());

              // In stock
              filters.in_stock = this.$('input[name="in_stock"]').is(':checked');

              return filters;
          },

          _onFilterChange: function (ev) {
              const filters = this._getActiveFilters();
              this._applyFilters(filters);
          },

          _onPriceRangeChange: function (ev) {
              // Debounce price range changes
              clearTimeout(this._priceChangeTimeout);
              this._priceChangeTimeout = setTimeout(() => {
                  const filters = this._getActiveFilters();
                  this._applyFilters(filters);
              }, 500);
          },

          _onClearFilters: function (ev) {
              ev.preventDefault();
              this.$('input[type="checkbox"]').prop('checked', false);
              this.$('#price-min').val(0);
              this.$('#price-max').val(this.$('#price-max').attr('max'));
              this._applyFilters({});
          },

          _applyFilters: function (filters) {
              // Show loading state
              this.trigger_up('show_loading');

              // Make RPC call to filter products
              this._rpc({
                  route: '/shop/search',
                  params: {
                      filters: filters,
                      page: 1,
                      limit: 20,
                  },
              }).then((result) => {
                  this.trigger_up('products_filtered', {
                      products: result.data.products,
                      total: result.data.total,
                  });
              });
          },
      });

      return publicWidget.registry.ProductFilters;
  });
  ```
  (4h)

**[Dev 3] - Product Comparison Tool (8h)**
- Create product comparison model:
  ```python
  # odoo/addons/smart_dairy_ecommerce/models/product_comparison.py
  from odoo import models, fields, api

  class ProductComparison(models.Model):
      _name = 'product.comparison'
      _description = 'Product Comparison Session'

      session_id = fields.Char(string='Session ID', required=True, index=True)
      partner_id = fields.Many2one('res.partner', string='Customer')
      product_ids = fields.Many2many(
          'product.product',
          string='Products to Compare',
          domain=[('sale_ok', '=', True)]
      )
      create_date = fields.Datetime(string='Created On', readonly=True)

      _sql_constraints = [
          ('session_unique', 'unique(session_id)', 'Session ID must be unique!'),
      ]

      @api.constrains('product_ids')
      def _check_product_limit(self):
          """Limit to maximum 4 products for comparison"""
          for record in self:
              if len(record.product_ids) > 4:
                  raise ValidationError('You can compare maximum 4 products at a time.')

      def get_comparison_data(self):
          """Get detailed comparison data for products"""
          self.ensure_one()

          comparison = []
          attributes = set()

          for product in self.product_ids:
              product_data = {
                  'id': product.id,
                  'name': product.name,
                  'price': product.list_price,
                  'image': f'/web/image/product.product/{product.id}/image_1920',
                  'in_stock': product.qty_available > 0,
                  'rating': product.rating_avg,
                  'reviews': product.rating_count,
                  'attributes': {},
              }

              # Get product attributes
              for attr_line in product.product_template_attribute_value_ids:
                  attr_name = attr_line.attribute_id.name
                  attr_value = attr_line.name
                  product_data['attributes'][attr_name] = attr_value
                  attributes.add(attr_name)

              comparison.append(product_data)

          return {
              'products': comparison,
              'attributes': list(attributes),
          }
  ```
  (4h)

- Create comparison UI template (4h)

**[UX Designer] - Filter UX Design (8h)**
- Design filter sidebar layout (2h)
- Create filter interaction mockups (2h)
- Design mobile filter drawer (2h)
- Create comparison table design (2h)

---

### Day 302-305: Product Recommendations, Recently Viewed, Related Products

*(Continues with AI-powered recommendations, browsing history, cross-sell/upsell implementation)*

---

## MILESTONE 2-10: [Remaining Milestones]

*(Each milestone includes detailed 5-day breakdowns)*

---

## PHASE SUCCESS CRITERIA

### Conversion Metrics
- ✓ Conversion rate > 5%
- ✓ Average order value > ৳1,500
- ✓ Cart abandonment rate < 60%
- ✓ Checkout completion rate > 80%

### Performance Metrics
- ✓ Page load time < 2 seconds
- ✓ Time to interactive < 3 seconds
- ✓ Lighthouse score > 90
- ✓ Mobile performance score > 85

### Customer Engagement
- ✓ Average session duration > 5 minutes
- ✓ Pages per session > 4
- ✓ Repeat purchase rate > 30%
- ✓ Customer satisfaction score > 4.5/5

### Technical Metrics
- ✓ API response time < 300ms (p95)
- ✓ Zero downtime during peak hours
- ✓ Successful payment rate > 98%
- ✓ Search relevance > 90%

---

**End of Phase 7 Overview**

*Complete detailed daily breakdowns for all 10 milestones available in full document*
