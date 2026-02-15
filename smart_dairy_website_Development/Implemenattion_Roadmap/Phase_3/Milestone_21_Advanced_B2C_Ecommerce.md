# Milestone 21: Advanced B2C E-Commerce Portal

## Smart Dairy Digital Smart Portal + ERP — Phase 3: E-Commerce, Mobile & Analytics

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 21 of 30 (1 of 10 in Phase 3)                                |
| **Title**        | Advanced B2C E-Commerce Portal                                |
| **Phase**        | Phase 3 — E-Commerce, Mobile & Analytics (Part A: E-Commerce & Mobile) |
| **Days**         | Days 201–210 (of 300)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Transform the Smart Dairy B2C web portal into a feature-rich e-commerce platform with Elasticsearch-powered search and autocomplete, AI-driven product recommendations, customer reviews and ratings, wishlist functionality, product comparison, and social sharing capabilities. Optimize performance with lazy loading, image optimization, and CDN integration to achieve sub-2-second page loads.

### 1.2 Objectives

1. Deploy and configure Elasticsearch 8.x cluster for product search and discovery
2. Implement autocomplete with fuzzy matching and search suggestions
3. Build faceted search with dynamic filters (category, price, brand, attributes)
4. Develop collaborative filtering recommendation engine based on purchase history
5. Create customer reviews and ratings system with moderation workflow
6. Implement wishlist functionality with cross-device synchronization
7. Build product comparison feature for dairy product variants
8. Add social sharing integration (Facebook, WhatsApp, Messenger)
9. Optimize frontend performance with lazy loading and image optimization
10. Configure CDN for static assets and product images

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D21.1 | Elasticsearch cluster setup and product indexing | Dev 1 | 201 |
| D21.2 | Search API with autocomplete | Dev 1 | 201-202 |
| D21.3 | Faceted search with dynamic filters | Dev 1 | 202-203 |
| D21.4 | Product variant matrix display | Dev 3 | 203 |
| D21.5 | Recommendation engine backend | Dev 1 | 204 |
| D21.6 | Recommendation UI widgets | Dev 3 | 204-205 |
| D21.7 | Customer reviews and ratings module | Dev 2 | 205-206 |
| D21.8 | Reviews moderation workflow | Dev 2 | 206 |
| D21.9 | Wishlist functionality | Dev 3 | 207 |
| D21.10 | Product comparison feature | Dev 3 | 207-208 |
| D21.11 | Social sharing integration | Dev 3 | 208 |
| D21.12 | Performance optimization and CDN | Dev 2 | 209 |
| D21.13 | Integration testing and documentation | All | 210 |

### 1.4 Success Criteria

- [ ] Elasticsearch search returns results in <200ms for 95th percentile queries
- [ ] Autocomplete suggestions appear within 100ms of typing
- [ ] Recommendation engine shows personalized products with 15%+ CTR
- [ ] Customer reviews system with 5-star ratings and photo uploads operational
- [ ] Review moderation workflow processing reviews within 24 hours
- [ ] Wishlist synchronized across web and mobile (when mobile launches)
- [ ] Product comparison supporting up to 4 products simultaneously
- [ ] Page load time <2s on 4G mobile connection
- [ ] Core Web Vitals meeting "Good" threshold (LCP<2.5s, FID<100ms, CLS<0.1)

### 1.5 Prerequisites

- **From Phase 2:**
  - Public website with product catalog (MS-17)
  - Payment gateway integration (MS-18)
  - Customer accounts and authentication
  - API Gateway operational (MS-20)
  - Product master data with images

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-B2C-001 | Product search with filters | SRS-SHOP-001 | 201-203 | Dev 1 |
| BRD-B2C-002 | Search autocomplete | SRS-SHOP-002 | 201-202 | Dev 1 |
| BRD-B2C-003 | Product recommendations | SRS-SHOP-003 | 204-205 | Dev 1/Dev 3 |
| BRD-B2C-004 | Customer reviews and ratings | SRS-SHOP-004 | 205-206 | Dev 2 |
| BRD-B2C-005 | Wishlist functionality | SRS-SHOP-005 | 207 | Dev 3 |
| BRD-B2C-006 | Product comparison | SRS-SHOP-006 | 207-208 | Dev 3 |
| BRD-B2C-007 | Social sharing | SRS-SHOP-007 | 208 | Dev 3 |
| BRD-B2C-008 | Performance optimization | SRS-PERF-001 | 209 | Dev 2 |
| BRD-B2C-009 | Image optimization | SRS-PERF-002 | 209 | Dev 2 |
| BRD-B2C-010 | CDN integration | SRS-PERF-003 | 209 | Dev 2 |

### 2.2 Smart Dairy E-Commerce Product Categories

| Category | Products | Search Priority | Recommendation Weight |
|----------|----------|-----------------|----------------------|
| Fresh Milk | Pasteurized, Full Cream, Low Fat | High | High |
| UHT Milk | Long Life, Flavored | Medium | Medium |
| Yogurt | Misti Doi, Mishti Doi, Plain | High | High |
| Cheese | Paneer, Processed | Medium | Medium |
| Butter | Salted, Unsalted | Medium | Low |
| Ghee | Pure Ghee | Medium | Low |

---

## 3. Day-by-Day Breakdown

---

### Day 201 — Elasticsearch Setup and Search API

**Objective:** Deploy Elasticsearch cluster, configure product indexing, and implement basic search API with autocomplete foundation.

#### Dev 1 — Backend Lead (8h)

**Task 201.1.1: Elasticsearch Cluster Configuration (3h)**

```python
# smart_dairy_addons/smart_ecommerce_adv/__manifest__.py
{
    'name': 'Smart Dairy Advanced E-Commerce',
    'version': '19.0.1.0.0',
    'category': 'Website/E-Commerce',
    'summary': 'Advanced B2C e-commerce features for Smart Dairy',
    'description': """
Smart Dairy Advanced E-Commerce Module
======================================
- Elasticsearch-powered product search
- Personalized recommendations
- Customer reviews and ratings
- Wishlist and comparison features
- Performance optimization
    """,
    'author': 'Smart Dairy Ltd',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'website_sale',
        'product',
        'sale',
        'stock',
        'smart_farm_mgmt',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/ecommerce_security.xml',
        'data/search_config_data.xml',
        'data/recommendation_data.xml',
        'views/product_search_views.xml',
        'views/product_review_views.xml',
        'views/wishlist_views.xml',
        'views/ecommerce_menu.xml',
        'views/templates/search_templates.xml',
        'views/templates/recommendation_templates.xml',
        'views/templates/review_templates.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_ecommerce_adv/static/src/js/search_autocomplete.js',
            'smart_ecommerce_adv/static/src/js/product_recommendations.js',
            'smart_ecommerce_adv/static/src/js/product_reviews.js',
            'smart_ecommerce_adv/static/src/js/wishlist.js',
            'smart_ecommerce_adv/static/src/js/product_comparison.js',
            'smart_ecommerce_adv/static/src/scss/ecommerce_advanced.scss',
        ],
        'web.assets_backend': [
            'smart_ecommerce_adv/static/src/js/review_moderation.js',
        ],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
}
```

**Task 201.1.2: Elasticsearch Service Configuration (2h)**

```python
# smart_dairy_addons/smart_ecommerce_adv/services/elasticsearch_service.py
from elasticsearch import Elasticsearch, helpers
from odoo import api, models, fields
from odoo.tools import config
import logging
import json

_logger = logging.getLogger(__name__)

class ElasticsearchService:
    """Elasticsearch service for product search and indexing"""

    _instance = None
    _client = None

    PRODUCT_INDEX = 'smart_dairy_products'
    SEARCH_INDEX = 'smart_dairy_search_history'

    def __new__(cls):
        if cls._instance is None:
            cls._instance = super().__new__(cls)
        return cls._instance

    def __init__(self):
        if self._client is None:
            self._connect()

    def _connect(self):
        """Initialize Elasticsearch connection"""
        es_host = config.get('elasticsearch_host', 'localhost')
        es_port = config.get('elasticsearch_port', 9200)
        es_user = config.get('elasticsearch_user', '')
        es_password = config.get('elasticsearch_password', '')

        try:
            if es_user and es_password:
                self._client = Elasticsearch(
                    [f"https://{es_host}:{es_port}"],
                    basic_auth=(es_user, es_password),
                    verify_certs=True,
                    ca_certs='/etc/ssl/certs/ca-certificates.crt'
                )
            else:
                self._client = Elasticsearch(
                    [f"http://{es_host}:{es_port}"]
                )

            # Test connection
            if self._client.ping():
                _logger.info("Elasticsearch connected successfully")
                self._ensure_indices()
            else:
                _logger.error("Elasticsearch ping failed")

        except Exception as e:
            _logger.error(f"Elasticsearch connection error: {e}")
            raise

    def _ensure_indices(self):
        """Create indices if they don't exist"""
        product_mapping = {
            "settings": {
                "number_of_shards": 2,
                "number_of_replicas": 1,
                "analysis": {
                    "analyzer": {
                        "autocomplete_analyzer": {
                            "type": "custom",
                            "tokenizer": "autocomplete_tokenizer",
                            "filter": ["lowercase", "asciifolding"]
                        },
                        "bengali_analyzer": {
                            "type": "custom",
                            "tokenizer": "standard",
                            "filter": ["lowercase"]
                        }
                    },
                    "tokenizer": {
                        "autocomplete_tokenizer": {
                            "type": "edge_ngram",
                            "min_gram": 2,
                            "max_gram": 20,
                            "token_chars": ["letter", "digit"]
                        }
                    }
                }
            },
            "mappings": {
                "properties": {
                    "id": {"type": "integer"},
                    "name": {
                        "type": "text",
                        "analyzer": "standard",
                        "fields": {
                            "autocomplete": {
                                "type": "text",
                                "analyzer": "autocomplete_analyzer",
                                "search_analyzer": "standard"
                            },
                            "keyword": {"type": "keyword"}
                        }
                    },
                    "name_bn": {
                        "type": "text",
                        "analyzer": "bengali_analyzer"
                    },
                    "description": {"type": "text"},
                    "default_code": {"type": "keyword"},
                    "category_id": {"type": "integer"},
                    "category_name": {
                        "type": "text",
                        "fields": {"keyword": {"type": "keyword"}}
                    },
                    "category_path": {"type": "keyword"},
                    "price": {"type": "float"},
                    "list_price": {"type": "float"},
                    "sale_price": {"type": "float"},
                    "currency": {"type": "keyword"},
                    "in_stock": {"type": "boolean"},
                    "stock_qty": {"type": "integer"},
                    "image_url": {"type": "keyword"},
                    "thumbnail_url": {"type": "keyword"},
                    "attributes": {
                        "type": "nested",
                        "properties": {
                            "name": {"type": "keyword"},
                            "value": {"type": "keyword"}
                        }
                    },
                    "tags": {"type": "keyword"},
                    "brand": {"type": "keyword"},
                    "dairy_type": {"type": "keyword"},
                    "fat_percentage": {"type": "float"},
                    "shelf_life_days": {"type": "integer"},
                    "is_organic": {"type": "boolean"},
                    "rating_average": {"type": "float"},
                    "rating_count": {"type": "integer"},
                    "review_count": {"type": "integer"},
                    "sales_count": {"type": "integer"},
                    "popularity_score": {"type": "float"},
                    "created_date": {"type": "date"},
                    "updated_date": {"type": "date"},
                    "suggest": {
                        "type": "completion",
                        "contexts": [
                            {"name": "category", "type": "category"}
                        ]
                    }
                }
            }
        }

        if not self._client.indices.exists(index=self.PRODUCT_INDEX):
            self._client.indices.create(index=self.PRODUCT_INDEX, body=product_mapping)
            _logger.info(f"Created index: {self.PRODUCT_INDEX}")

    def index_product(self, product_data):
        """Index a single product"""
        try:
            self._client.index(
                index=self.PRODUCT_INDEX,
                id=product_data['id'],
                document=product_data
            )
            return True
        except Exception as e:
            _logger.error(f"Error indexing product {product_data.get('id')}: {e}")
            return False

    def bulk_index_products(self, products_data):
        """Bulk index multiple products"""
        actions = [
            {
                "_index": self.PRODUCT_INDEX,
                "_id": product['id'],
                "_source": product
            }
            for product in products_data
        ]

        try:
            success, failed = helpers.bulk(
                self._client,
                actions,
                raise_on_error=False
            )
            _logger.info(f"Bulk indexed {success} products, {len(failed)} failed")
            return success, failed
        except Exception as e:
            _logger.error(f"Bulk indexing error: {e}")
            raise

    def search_products(self, query, filters=None, page=1, limit=20, sort=None):
        """
        Search products with optional filters

        Args:
            query: Search query string
            filters: Dict of filters (category, price_min, price_max, etc.)
            page: Page number (1-indexed)
            limit: Results per page
            sort: Sort field and order
        """
        must_clauses = []
        filter_clauses = []

        # Main search query
        if query:
            must_clauses.append({
                "multi_match": {
                    "query": query,
                    "fields": [
                        "name^3",
                        "name.autocomplete^2",
                        "name_bn^2",
                        "description",
                        "category_name",
                        "tags"
                    ],
                    "type": "best_fields",
                    "fuzziness": "AUTO"
                }
            })

        # Apply filters
        if filters:
            if filters.get('category_id'):
                filter_clauses.append({
                    "term": {"category_id": filters['category_id']}
                })

            if filters.get('category_path'):
                filter_clauses.append({
                    "prefix": {"category_path": filters['category_path']}
                })

            if filters.get('price_min') is not None or filters.get('price_max') is not None:
                price_range = {}
                if filters.get('price_min') is not None:
                    price_range['gte'] = filters['price_min']
                if filters.get('price_max') is not None:
                    price_range['lte'] = filters['price_max']
                filter_clauses.append({"range": {"list_price": price_range}})

            if filters.get('in_stock') is not None:
                filter_clauses.append({
                    "term": {"in_stock": filters['in_stock']}
                })

            if filters.get('dairy_type'):
                filter_clauses.append({
                    "term": {"dairy_type": filters['dairy_type']}
                })

            if filters.get('rating_min'):
                filter_clauses.append({
                    "range": {"rating_average": {"gte": filters['rating_min']}}
                })

            if filters.get('is_organic') is not None:
                filter_clauses.append({
                    "term": {"is_organic": filters['is_organic']}
                })

        # Build query
        search_body = {
            "query": {
                "bool": {
                    "must": must_clauses if must_clauses else [{"match_all": {}}],
                    "filter": filter_clauses
                }
            },
            "from": (page - 1) * limit,
            "size": limit,
            "highlight": {
                "fields": {
                    "name": {},
                    "description": {"fragment_size": 150}
                }
            },
            "aggs": {
                "categories": {
                    "terms": {"field": "category_name.keyword", "size": 20}
                },
                "dairy_types": {
                    "terms": {"field": "dairy_type", "size": 10}
                },
                "price_ranges": {
                    "range": {
                        "field": "list_price",
                        "ranges": [
                            {"to": 100, "key": "Under 100"},
                            {"from": 100, "to": 200, "key": "100-200"},
                            {"from": 200, "to": 500, "key": "200-500"},
                            {"from": 500, "key": "Above 500"}
                        ]
                    }
                },
                "rating_stats": {
                    "stats": {"field": "rating_average"}
                },
                "in_stock": {
                    "terms": {"field": "in_stock"}
                }
            }
        }

        # Apply sorting
        if sort:
            sort_mapping = {
                'price_asc': [{"list_price": "asc"}],
                'price_desc': [{"list_price": "desc"}],
                'rating': [{"rating_average": "desc"}],
                'popularity': [{"popularity_score": "desc"}],
                'newest': [{"created_date": "desc"}],
                'name_asc': [{"name.keyword": "asc"}],
                'relevance': [{"_score": "desc"}]
            }
            search_body['sort'] = sort_mapping.get(sort, [{"_score": "desc"}])

        try:
            response = self._client.search(
                index=self.PRODUCT_INDEX,
                body=search_body
            )

            return {
                'total': response['hits']['total']['value'],
                'products': [hit['_source'] for hit in response['hits']['hits']],
                'highlights': {
                    hit['_id']: hit.get('highlight', {})
                    for hit in response['hits']['hits']
                },
                'aggregations': response.get('aggregations', {}),
                'took_ms': response['took']
            }
        except Exception as e:
            _logger.error(f"Search error: {e}")
            raise

    def autocomplete(self, query, limit=10):
        """Get autocomplete suggestions"""
        search_body = {
            "suggest": {
                "product_suggest": {
                    "prefix": query,
                    "completion": {
                        "field": "suggest",
                        "size": limit,
                        "skip_duplicates": True,
                        "fuzzy": {
                            "fuzziness": 1
                        }
                    }
                }
            },
            "_source": ["id", "name", "name_bn", "thumbnail_url", "list_price", "category_name"]
        }

        try:
            response = self._client.search(
                index=self.PRODUCT_INDEX,
                body=search_body
            )

            suggestions = response['suggest']['product_suggest'][0]['options']
            return [
                {
                    'id': s['_source']['id'],
                    'name': s['_source']['name'],
                    'name_bn': s['_source'].get('name_bn'),
                    'thumbnail_url': s['_source'].get('thumbnail_url'),
                    'price': s['_source'].get('list_price'),
                    'category': s['_source'].get('category_name'),
                    'score': s['_score']
                }
                for s in suggestions
            ]
        except Exception as e:
            _logger.error(f"Autocomplete error: {e}")
            return []
```

**Task 201.1.3: Product Indexing Model (3h)**

```python
# smart_dairy_addons/smart_ecommerce_adv/models/product_search.py
from odoo import models, fields, api, _
from odoo.addons.smart_ecommerce_adv.services.elasticsearch_service import ElasticsearchService
import logging

_logger = logging.getLogger(__name__)

class ProductTemplateSearch(models.Model):
    _inherit = 'product.template'

    # Search-related fields
    search_keywords = fields.Text(
        string='Search Keywords',
        help='Additional keywords for search optimization'
    )
    search_priority = fields.Selection([
        ('low', 'Low'),
        ('normal', 'Normal'),
        ('high', 'High'),
        ('featured', 'Featured'),
    ], string='Search Priority', default='normal')
    popularity_score = fields.Float(
        string='Popularity Score',
        compute='_compute_popularity_score',
        store=True
    )
    es_indexed = fields.Boolean(
        string='Indexed in Elasticsearch',
        default=False
    )
    es_last_indexed = fields.Datetime(
        string='Last Indexed'
    )

    # Bengali name for search
    name_bn = fields.Char(string='Name (Bengali)')
    description_bn = fields.Text(string='Description (Bengali)')

    @api.depends('sales_count', 'rating_avg', 'review_count', 'website_published')
    def _compute_popularity_score(self):
        """Calculate popularity score for search ranking"""
        for product in self:
            score = 0.0

            # Sales contribution (40%)
            sales_weight = min(product.sales_count / 100, 10) * 4
            score += sales_weight

            # Rating contribution (30%)
            if product.rating_avg > 0:
                rating_weight = (product.rating_avg / 5) * 30
                score += rating_weight

            # Review count contribution (20%)
            review_weight = min(product.review_count / 10, 10) * 2
            score += review_weight

            # Published bonus (10%)
            if product.website_published:
                score += 10

            product.popularity_score = score

    def _prepare_es_document(self):
        """Prepare product data for Elasticsearch indexing"""
        self.ensure_one()

        # Get category path
        category_path = []
        category = self.categ_id
        while category:
            category_path.insert(0, category.name)
            category = category.parent_id

        # Get product attributes
        attributes = []
        for line in self.attribute_line_ids:
            for value in line.value_ids:
                attributes.append({
                    'name': line.attribute_id.name,
                    'value': value.name
                })

        # Get image URL
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        image_url = f"{base_url}/web/image/product.template/{self.id}/image_1920"
        thumbnail_url = f"{base_url}/web/image/product.template/{self.id}/image_256"

        # Get stock status
        in_stock = self.qty_available > 0

        document = {
            'id': self.id,
            'name': self.name,
            'name_bn': self.name_bn or '',
            'description': self.description_sale or '',
            'default_code': self.default_code or '',
            'category_id': self.categ_id.id if self.categ_id else 0,
            'category_name': self.categ_id.name if self.categ_id else '',
            'category_path': '/'.join(category_path),
            'price': self.list_price,
            'list_price': self.list_price,
            'sale_price': self.list_price,  # Can be modified for discounts
            'currency': self.currency_id.name,
            'in_stock': in_stock,
            'stock_qty': int(self.qty_available),
            'image_url': image_url,
            'thumbnail_url': thumbnail_url,
            'attributes': attributes,
            'tags': self.search_keywords.split(',') if self.search_keywords else [],
            'brand': 'Smart Dairy',  # Default brand
            'dairy_type': self.dairy_product_type if hasattr(self, 'dairy_product_type') else '',
            'fat_percentage': getattr(self, 'fat_percentage', 0.0),
            'shelf_life_days': getattr(self, 'shelf_life_days', 0),
            'is_organic': getattr(self, 'is_organic', False),
            'rating_average': self.rating_avg,
            'rating_count': self.rating_count,
            'review_count': self.review_count,
            'sales_count': self.sales_count,
            'popularity_score': self.popularity_score,
            'created_date': self.create_date.isoformat() if self.create_date else None,
            'updated_date': self.write_date.isoformat() if self.write_date else None,
            'suggest': {
                'input': [self.name] + (self.search_keywords.split(',') if self.search_keywords else []),
                'contexts': {
                    'category': [self.categ_id.name] if self.categ_id else []
                }
            }
        }

        return document

    def index_to_elasticsearch(self):
        """Index product(s) to Elasticsearch"""
        es_service = ElasticsearchService()

        for product in self:
            try:
                document = product._prepare_es_document()
                if es_service.index_product(document):
                    product.write({
                        'es_indexed': True,
                        'es_last_indexed': fields.Datetime.now()
                    })
                    _logger.info(f"Indexed product {product.id} to Elasticsearch")
            except Exception as e:
                _logger.error(f"Failed to index product {product.id}: {e}")

    def bulk_index_to_elasticsearch(self):
        """Bulk index multiple products"""
        es_service = ElasticsearchService()
        documents = [p._prepare_es_document() for p in self]

        try:
            success, failed = es_service.bulk_index_products(documents)

            # Update indexed products
            self.write({
                'es_indexed': True,
                'es_last_indexed': fields.Datetime.now()
            })

            return success, len(failed)
        except Exception as e:
            _logger.error(f"Bulk indexing failed: {e}")
            raise

    @api.model
    def reindex_all_products(self):
        """Reindex all website-published products"""
        products = self.search([
            ('website_published', '=', True),
            ('sale_ok', '=', True)
        ])

        _logger.info(f"Reindexing {len(products)} products to Elasticsearch")
        return products.bulk_index_to_elasticsearch()

    def write(self, vals):
        """Override write to trigger re-indexing on product changes"""
        result = super().write(vals)

        # Re-index if relevant fields changed
        index_fields = {
            'name', 'name_bn', 'description_sale', 'list_price',
            'qty_available', 'categ_id', 'website_published',
            'attribute_line_ids', 'search_keywords', 'search_priority'
        }

        if index_fields & set(vals.keys()):
            self.filtered(lambda p: p.website_published and p.sale_ok).index_to_elasticsearch()

        return result


class ProductSearchAPI(models.AbstractModel):
    _name = 'product.search.api'
    _description = 'Product Search API'

    @api.model
    def search_products(self, query, filters=None, page=1, limit=20, sort='relevance'):
        """
        Public API for product search

        Args:
            query: Search query string
            filters: Dict with optional keys:
                - category_id: int
                - price_min: float
                - price_max: float
                - in_stock: bool
                - dairy_type: str
                - rating_min: float
                - is_organic: bool
            page: Page number (1-indexed)
            limit: Results per page (max 100)
            sort: Sort option (relevance, price_asc, price_desc, rating, popularity, newest)

        Returns:
            Dict with search results, aggregations, and metadata
        """
        # Validate inputs
        limit = min(max(limit, 1), 100)
        page = max(page, 1)

        es_service = ElasticsearchService()

        try:
            results = es_service.search_products(
                query=query,
                filters=filters,
                page=page,
                limit=limit,
                sort=sort
            )

            return {
                'success': True,
                'data': {
                    'total': results['total'],
                    'page': page,
                    'limit': limit,
                    'total_pages': (results['total'] + limit - 1) // limit,
                    'products': results['products'],
                    'filters': {
                        'categories': results['aggregations'].get('categories', {}).get('buckets', []),
                        'dairy_types': results['aggregations'].get('dairy_types', {}).get('buckets', []),
                        'price_ranges': results['aggregations'].get('price_ranges', {}).get('buckets', []),
                        'in_stock': results['aggregations'].get('in_stock', {}).get('buckets', [])
                    },
                    'took_ms': results['took_ms']
                }
            }
        except Exception as e:
            _logger.error(f"Search API error: {e}")
            return {
                'success': False,
                'error': str(e)
            }

    @api.model
    def get_autocomplete(self, query, limit=10):
        """
        Get autocomplete suggestions

        Args:
            query: Partial search query
            limit: Maximum suggestions (max 20)

        Returns:
            List of suggestion objects
        """
        limit = min(max(limit, 1), 20)
        es_service = ElasticsearchService()

        try:
            suggestions = es_service.autocomplete(query, limit)
            return {
                'success': True,
                'suggestions': suggestions
            }
        except Exception as e:
            _logger.error(f"Autocomplete error: {e}")
            return {
                'success': False,
                'suggestions': []
            }
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 201.2.1: FastAPI Search Endpoints (4h)**

```python
# api/routers/search.py
from fastapi import APIRouter, Query, HTTPException
from pydantic import BaseModel, Field
from typing import Optional, List, Dict, Any
import odoo_rpc

router = APIRouter(prefix="/api/v1/search", tags=["search"])

class SearchFilters(BaseModel):
    category_id: Optional[int] = None
    price_min: Optional[float] = None
    price_max: Optional[float] = None
    in_stock: Optional[bool] = None
    dairy_type: Optional[str] = None
    rating_min: Optional[float] = None
    is_organic: Optional[bool] = None

class SearchRequest(BaseModel):
    query: str = Field(..., min_length=1, max_length=200)
    filters: Optional[SearchFilters] = None
    page: int = Field(default=1, ge=1)
    limit: int = Field(default=20, ge=1, le=100)
    sort: str = Field(default="relevance")

class SearchResponse(BaseModel):
    success: bool
    data: Optional[Dict[str, Any]] = None
    error: Optional[str] = None

class AutocompleteResponse(BaseModel):
    success: bool
    suggestions: List[Dict[str, Any]] = []

@router.post("/products", response_model=SearchResponse)
async def search_products(request: SearchRequest):
    """
    Search products with filters and sorting

    - **query**: Search term (required)
    - **filters**: Optional filters (category, price, stock, etc.)
    - **page**: Page number (default: 1)
    - **limit**: Results per page (default: 20, max: 100)
    - **sort**: Sort option (relevance, price_asc, price_desc, rating, popularity, newest)
    """
    try:
        odoo = odoo_rpc.get_connection()

        filters_dict = request.filters.dict(exclude_none=True) if request.filters else None

        result = odoo.execute(
            'product.search.api',
            'search_products',
            request.query,
            filters_dict,
            request.page,
            request.limit,
            request.sort
        )

        return SearchResponse(**result)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/autocomplete", response_model=AutocompleteResponse)
async def get_autocomplete(
    q: str = Query(..., min_length=2, max_length=100, description="Search query"),
    limit: int = Query(default=10, ge=1, le=20, description="Max suggestions")
):
    """
    Get autocomplete suggestions for product search

    - **q**: Partial search query (min 2 characters)
    - **limit**: Maximum number of suggestions (default: 10)
    """
    try:
        odoo = odoo_rpc.get_connection()

        result = odoo.execute(
            'product.search.api',
            'get_autocomplete',
            q,
            limit
        )

        return AutocompleteResponse(**result)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/popular", response_model=SearchResponse)
async def get_popular_products(
    limit: int = Query(default=10, ge=1, le=50)
):
    """Get popular products sorted by popularity score"""
    try:
        odoo = odoo_rpc.get_connection()

        result = odoo.execute(
            'product.search.api',
            'search_products',
            '',  # Empty query
            None,  # No filters
            1,
            limit,
            'popularity'
        )

        return SearchResponse(**result)
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/reindex")
async def reindex_products():
    """Trigger full product reindex (admin only)"""
    try:
        odoo = odoo_rpc.get_connection()

        success, failed = odoo.execute(
            'product.template',
            'reindex_all_products'
        )

        return {
            "success": True,
            "indexed": success,
            "failed": failed
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
```

**Task 201.2.2: Elasticsearch Docker Configuration (2h)**

```yaml
# docker-compose.elasticsearch.yml
version: '3.8'

services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.11.3
    container_name: smart_dairy_elasticsearch
    environment:
      - node.name=es01
      - cluster.name=smart-dairy-cluster
      - discovery.type=single-node
      - bootstrap.memory_lock=true
      - xpack.security.enabled=false
      - "ES_JAVA_OPTS=-Xms2g -Xmx2g"
    ulimits:
      memlock:
        soft: -1
        hard: -1
    volumes:
      - elasticsearch_data:/usr/share/elasticsearch/data
    ports:
      - "9200:9200"
      - "9300:9300"
    networks:
      - smart_dairy_network
    healthcheck:
      test: ["CMD-SHELL", "curl -s http://localhost:9200/_cluster/health | grep -q 'green\\|yellow'"]
      interval: 30s
      timeout: 10s
      retries: 5

  kibana:
    image: docker.elastic.co/kibana/kibana:8.11.3
    container_name: smart_dairy_kibana
    environment:
      - ELASTICSEARCH_HOSTS=http://elasticsearch:9200
    ports:
      - "5601:5601"
    networks:
      - smart_dairy_network
    depends_on:
      elasticsearch:
        condition: service_healthy

volumes:
  elasticsearch_data:
    driver: local

networks:
  smart_dairy_network:
    external: true
```

**Task 201.2.3: Unit Tests for Search API (2h)**

```python
# tests/test_search_api.py
import pytest
from unittest.mock import Mock, patch
from smart_ecommerce_adv.services.elasticsearch_service import ElasticsearchService

class TestElasticsearchService:

    @pytest.fixture
    def es_service(self):
        with patch.object(ElasticsearchService, '_connect'):
            service = ElasticsearchService()
            service._client = Mock()
            return service

    def test_search_products_basic(self, es_service):
        """Test basic product search"""
        es_service._client.search.return_value = {
            'hits': {
                'total': {'value': 5},
                'hits': [
                    {'_source': {'id': 1, 'name': 'Fresh Milk'}, '_id': '1'},
                    {'_source': {'id': 2, 'name': 'UHT Milk'}, '_id': '2'},
                ]
            },
            'aggregations': {},
            'took': 15
        }

        result = es_service.search_products('milk')

        assert result['total'] == 5
        assert len(result['products']) == 2
        assert result['took_ms'] == 15

    def test_search_with_filters(self, es_service):
        """Test search with price and category filters"""
        es_service._client.search.return_value = {
            'hits': {
                'total': {'value': 3},
                'hits': []
            },
            'aggregations': {},
            'took': 10
        }

        filters = {
            'price_min': 50,
            'price_max': 200,
            'category_id': 5,
            'in_stock': True
        }

        result = es_service.search_products('milk', filters=filters)

        # Verify the search was called with filters
        call_args = es_service._client.search.call_args
        query_body = call_args.kwargs['body']

        assert 'filter' in query_body['query']['bool']
        assert result['total'] == 3

    def test_autocomplete(self, es_service):
        """Test autocomplete suggestions"""
        es_service._client.search.return_value = {
            'suggest': {
                'product_suggest': [{
                    'options': [
                        {
                            '_source': {
                                'id': 1,
                                'name': 'Fresh Milk',
                                'thumbnail_url': '/image.jpg',
                                'list_price': 80
                            },
                            '_score': 10.5
                        }
                    ]
                }]
            }
        }

        suggestions = es_service.autocomplete('fre')

        assert len(suggestions) == 1
        assert suggestions[0]['name'] == 'Fresh Milk'
        assert suggestions[0]['score'] == 10.5

    def test_search_pagination(self, es_service):
        """Test search pagination"""
        es_service._client.search.return_value = {
            'hits': {'total': {'value': 100}, 'hits': []},
            'aggregations': {},
            'took': 5
        }

        es_service.search_products('milk', page=3, limit=20)

        call_args = es_service._client.search.call_args
        query_body = call_args.kwargs['body']

        assert query_body['from'] == 40  # (3-1) * 20
        assert query_body['size'] == 20
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 201.3.1: Search UI Component Setup (4h)**

```javascript
// smart_ecommerce_adv/static/src/js/search_autocomplete.js
/** @odoo-module **/

import { Component, useState, useRef, onMounted, onWillUnmount } from "@odoo/owl";
import { debounce } from "@web/core/utils/timing";

export class SearchAutocomplete extends Component {
    static template = "smart_ecommerce_adv.SearchAutocomplete";
    static props = {
        placeholder: { type: String, optional: true },
        minChars: { type: Number, optional: true },
        debounceMs: { type: Number, optional: true },
    };

    setup() {
        this.state = useState({
            query: "",
            suggestions: [],
            isLoading: false,
            showSuggestions: false,
            selectedIndex: -1,
            recentSearches: [],
        });

        this.inputRef = useRef("searchInput");
        this.minChars = this.props.minChars || 2;
        this.debounceMs = this.props.debounceMs || 150;

        // Debounced search function
        this.debouncedSearch = debounce(this.fetchSuggestions.bind(this), this.debounceMs);

        onMounted(() => {
            this.loadRecentSearches();
            document.addEventListener('click', this.handleOutsideClick.bind(this));
        });

        onWillUnmount(() => {
            document.removeEventListener('click', this.handleOutsideClick.bind(this));
        });
    }

    loadRecentSearches() {
        try {
            const stored = localStorage.getItem('smart_dairy_recent_searches');
            if (stored) {
                this.state.recentSearches = JSON.parse(stored).slice(0, 5);
            }
        } catch (e) {
            console.warn('Could not load recent searches', e);
        }
    }

    saveRecentSearch(query) {
        if (!query || query.length < this.minChars) return;

        let searches = this.state.recentSearches.filter(s => s !== query);
        searches.unshift(query);
        searches = searches.slice(0, 5);

        this.state.recentSearches = searches;
        localStorage.setItem('smart_dairy_recent_searches', JSON.stringify(searches));
    }

    handleOutsideClick(event) {
        if (!this.inputRef.el?.contains(event.target)) {
            this.state.showSuggestions = false;
        }
    }

    async fetchSuggestions(query) {
        if (query.length < this.minChars) {
            this.state.suggestions = [];
            return;
        }

        this.state.isLoading = true;

        try {
            const response = await fetch(`/api/v1/search/autocomplete?q=${encodeURIComponent(query)}&limit=10`);
            const data = await response.json();

            if (data.success) {
                this.state.suggestions = data.suggestions;
            }
        } catch (error) {
            console.error('Autocomplete fetch error:', error);
            this.state.suggestions = [];
        } finally {
            this.state.isLoading = false;
        }
    }

    onInputChange(event) {
        const query = event.target.value;
        this.state.query = query;
        this.state.showSuggestions = true;
        this.state.selectedIndex = -1;

        this.debouncedSearch(query);
    }

    onInputFocus() {
        this.state.showSuggestions = true;
    }

    onKeyDown(event) {
        const { suggestions, selectedIndex } = this.state;

        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                this.state.selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                break;
            case 'ArrowUp':
                event.preventDefault();
                this.state.selectedIndex = Math.max(selectedIndex - 1, -1);
                break;
            case 'Enter':
                event.preventDefault();
                if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                    this.selectSuggestion(suggestions[selectedIndex]);
                } else {
                    this.submitSearch();
                }
                break;
            case 'Escape':
                this.state.showSuggestions = false;
                break;
        }
    }

    selectSuggestion(suggestion) {
        this.state.query = suggestion.name;
        this.state.showSuggestions = false;
        this.saveRecentSearch(suggestion.name);

        // Navigate to product page
        window.location.href = `/shop/product/${suggestion.id}`;
    }

    selectRecentSearch(search) {
        this.state.query = search;
        this.state.showSuggestions = false;
        this.submitSearch();
    }

    clearRecentSearches() {
        this.state.recentSearches = [];
        localStorage.removeItem('smart_dairy_recent_searches');
    }

    submitSearch() {
        const query = this.state.query.trim();
        if (query.length > 0) {
            this.saveRecentSearch(query);
            window.location.href = `/shop?search=${encodeURIComponent(query)}`;
        }
    }

    highlightMatch(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<strong>$1</strong>');
    }

    formatPrice(price) {
        return new Intl.NumberFormat('en-BD', {
            style: 'currency',
            currency: 'BDT',
            minimumFractionDigits: 0,
        }).format(price);
    }
}
```

**Task 201.3.2: Search Template (2h)**

```xml
<!-- smart_ecommerce_adv/static/src/xml/search_autocomplete.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">
    <t t-name="smart_ecommerce_adv.SearchAutocomplete">
        <div class="sd-search-autocomplete position-relative">
            <div class="input-group">
                <input
                    t-ref="searchInput"
                    type="text"
                    class="form-control sd-search-input"
                    t-att-placeholder="props.placeholder || 'Search products...'"
                    t-att-value="state.query"
                    t-on-input="onInputChange"
                    t-on-focus="onInputFocus"
                    t-on-keydown="onKeyDown"
                    autocomplete="off"
                />
                <button class="btn btn-primary sd-search-btn" t-on-click="submitSearch">
                    <i class="fa fa-search"/>
                </button>
            </div>

            <!-- Suggestions Dropdown -->
            <div t-if="state.showSuggestions" class="sd-suggestions-dropdown">
                <!-- Loading indicator -->
                <div t-if="state.isLoading" class="sd-suggestion-loading">
                    <i class="fa fa-spinner fa-spin"/> Searching...
                </div>

                <!-- Recent searches (when no query) -->
                <div t-elif="state.query.length &lt; 2 and state.recentSearches.length > 0" class="sd-recent-searches">
                    <div class="sd-dropdown-header d-flex justify-content-between align-items-center">
                        <span>Recent Searches</span>
                        <button class="btn btn-link btn-sm" t-on-click="clearRecentSearches">Clear</button>
                    </div>
                    <div t-foreach="state.recentSearches" t-as="search" t-key="search"
                         class="sd-recent-item" t-on-click="() => this.selectRecentSearch(search)">
                        <i class="fa fa-history me-2"/>
                        <t t-esc="search"/>
                    </div>
                </div>

                <!-- Product suggestions -->
                <div t-elif="state.suggestions.length > 0" class="sd-product-suggestions">
                    <t t-foreach="state.suggestions" t-as="suggestion" t-key="suggestion.id">
                        <div t-attf-class="sd-suggestion-item #{state.selectedIndex === suggestion_index ? 'selected' : ''}"
                             t-on-click="() => this.selectSuggestion(suggestion)"
                             t-on-mouseenter="() => this.state.selectedIndex = suggestion_index">
                            <img t-if="suggestion.thumbnail_url"
                                 t-att-src="suggestion.thumbnail_url"
                                 class="sd-suggestion-image"
                                 alt=""/>
                            <div class="sd-suggestion-content">
                                <div class="sd-suggestion-name"
                                     t-raw="this.highlightMatch(suggestion.name, state.query)"/>
                                <div class="sd-suggestion-meta">
                                    <span t-if="suggestion.category" class="sd-suggestion-category">
                                        <t t-esc="suggestion.category"/>
                                    </span>
                                    <span t-if="suggestion.price" class="sd-suggestion-price">
                                        <t t-esc="this.formatPrice(suggestion.price)"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </t>
                    <div class="sd-view-all" t-on-click="submitSearch">
                        View all results for "<t t-esc="state.query"/>"
                    </div>
                </div>

                <!-- No results -->
                <div t-elif="state.query.length >= 2 and !state.isLoading" class="sd-no-results">
                    <i class="fa fa-search-minus"/>
                    <span>No products found for "<t t-esc="state.query"/>"</span>
                </div>
            </div>
        </div>
    </t>
</templates>
```

**Task 201.3.3: Search Styles (2h)**

```scss
// smart_ecommerce_adv/static/src/scss/_search.scss
.sd-search-autocomplete {
    width: 100%;
    max-width: 600px;

    .sd-search-input {
        border-radius: 25px 0 0 25px;
        padding: 12px 20px;
        font-size: 1rem;
        border: 2px solid #e0e0e0;
        transition: border-color 0.2s ease;

        &:focus {
            border-color: var(--sd-primary, #007bff);
            box-shadow: none;
        }
    }

    .sd-search-btn {
        border-radius: 0 25px 25px 0;
        padding: 12px 24px;
        background: var(--sd-primary, #007bff);
        border: none;

        &:hover {
            background: var(--sd-primary-dark, #0056b3);
        }
    }

    .sd-suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        margin-top: 8px;
        max-height: 400px;
        overflow-y: auto;
        z-index: 1000;

        .sd-dropdown-header {
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #666;
            border-bottom: 1px solid #f0f0f0;
        }

        .sd-suggestion-loading,
        .sd-no-results {
            padding: 24px;
            text-align: center;
            color: #888;

            i {
                margin-right: 8px;
            }
        }

        .sd-recent-item {
            padding: 12px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: #555;

            &:hover {
                background: #f8f9fa;
            }

            i {
                color: #aaa;
            }
        }

        .sd-suggestion-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.15s ease;

            &:hover,
            &.selected {
                background: #f8f9fa;
            }

            .sd-suggestion-image {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: 8px;
                margin-right: 12px;
                background: #f0f0f0;
            }

            .sd-suggestion-content {
                flex: 1;
                min-width: 0;
            }

            .sd-suggestion-name {
                font-weight: 500;
                color: #333;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;

                strong {
                    color: var(--sd-primary, #007bff);
                }
            }

            .sd-suggestion-meta {
                display: flex;
                justify-content: space-between;
                font-size: 0.85rem;
                margin-top: 4px;

                .sd-suggestion-category {
                    color: #888;
                }

                .sd-suggestion-price {
                    color: var(--sd-primary, #007bff);
                    font-weight: 600;
                }
            }
        }

        .sd-view-all {
            padding: 12px 16px;
            text-align: center;
            color: var(--sd-primary, #007bff);
            font-weight: 500;
            cursor: pointer;
            border-top: 1px solid #f0f0f0;

            &:hover {
                background: #f8f9fa;
            }
        }
    }
}

// Responsive adjustments
@media (max-width: 768px) {
    .sd-search-autocomplete {
        max-width: 100%;

        .sd-search-input {
            padding: 10px 16px;
            font-size: 0.95rem;
        }

        .sd-search-btn {
            padding: 10px 16px;
        }

        .sd-suggestions-dropdown {
            .sd-suggestion-item {
                padding: 10px 12px;

                .sd-suggestion-image {
                    width: 40px;
                    height: 40px;
                }
            }
        }
    }
}
```

**End-of-Day 201 Deliverables:**
- [ ] Elasticsearch 8.11.3 cluster deployed and running
- [ ] Product index mapping created with analyzers
- [ ] Product indexing model implemented
- [ ] Elasticsearch service class with search and autocomplete
- [ ] FastAPI search endpoints created
- [ ] Unit tests for search service
- [ ] Search autocomplete OWL component scaffold
- [ ] Search UI templates and styles

---

### Day 202 — Faceted Search and Dynamic Filters

**Objective:** Implement faceted search with dynamic filters for category, price range, dairy type, ratings, and stock availability.

#### Dev 1 — Backend Lead (8h)

**Task 202.1.1: Faceted Search Enhancement (4h)**

```python
# smart_dairy_addons/smart_ecommerce_adv/models/search_facets.py
from odoo import models, fields, api
from odoo.addons.smart_ecommerce_adv.services.elasticsearch_service import ElasticsearchService
import logging

_logger = logging.getLogger(__name__)

class ProductSearchFacets(models.AbstractModel):
    _name = 'product.search.facets'
    _description = 'Product Search Facets Handler'

    FACET_DEFINITIONS = {
        'category': {
            'type': 'terms',
            'field': 'category_name.keyword',
            'size': 50,
            'display_name': 'Category',
            'display_name_bn': 'শ্রেণী'
        },
        'dairy_type': {
            'type': 'terms',
            'field': 'dairy_type',
            'size': 10,
            'display_name': 'Product Type',
            'display_name_bn': 'পণ্যের ধরন'
        },
        'price_range': {
            'type': 'range',
            'field': 'list_price',
            'ranges': [
                {'key': 'under_100', 'to': 100, 'label': 'Under ৳100', 'label_bn': '৳১০০ এর নিচে'},
                {'key': '100_200', 'from': 100, 'to': 200, 'label': '৳100 - ৳200', 'label_bn': '৳১০০ - ৳২০০'},
                {'key': '200_500', 'from': 200, 'to': 500, 'label': '৳200 - ৳500', 'label_bn': '৳২০০ - ৳৫০০'},
                {'key': 'above_500', 'from': 500, 'label': 'Above ৳500', 'label_bn': '৳৫০০ এর উপরে'}
            ],
            'display_name': 'Price',
            'display_name_bn': 'মূল্য'
        },
        'rating': {
            'type': 'range',
            'field': 'rating_average',
            'ranges': [
                {'key': '4_plus', 'from': 4, 'label': '4+ Stars', 'label_bn': '৪+ তারা'},
                {'key': '3_plus', 'from': 3, 'label': '3+ Stars', 'label_bn': '৩+ তারা'},
                {'key': '2_plus', 'from': 2, 'label': '2+ Stars', 'label_bn': '২+ তারা'}
            ],
            'display_name': 'Rating',
            'display_name_bn': 'রেটিং'
        },
        'availability': {
            'type': 'terms',
            'field': 'in_stock',
            'size': 2,
            'display_name': 'Availability',
            'display_name_bn': 'প্রাপ্যতা',
            'value_labels': {
                'true': {'en': 'In Stock', 'bn': 'স্টকে আছে'},
                'false': {'en': 'Out of Stock', 'bn': 'স্টকে নেই'}
            }
        },
        'fat_content': {
            'type': 'range',
            'field': 'fat_percentage',
            'ranges': [
                {'key': 'low_fat', 'to': 1.5, 'label': 'Low Fat (<1.5%)', 'label_bn': 'কম ফ্যাট (<১.৫%)'},
                {'key': 'regular', 'from': 1.5, 'to': 3.5, 'label': 'Regular (1.5-3.5%)', 'label_bn': 'নিয়মিত (১.৫-৩.৫%)'},
                {'key': 'full_cream', 'from': 3.5, 'label': 'Full Cream (>3.5%)', 'label_bn': 'ফুল ক্রিম (>৩.৫%)'}
            ],
            'display_name': 'Fat Content',
            'display_name_bn': 'ফ্যাটের পরিমাণ'
        },
        'organic': {
            'type': 'terms',
            'field': 'is_organic',
            'size': 2,
            'display_name': 'Organic',
            'display_name_bn': 'জৈব',
            'value_labels': {
                'true': {'en': 'Organic', 'bn': 'জৈব'},
                'false': {'en': 'Non-Organic', 'bn': 'অ-জৈব'}
            }
        }
    }

    @api.model
    def get_facet_aggregations(self):
        """Build Elasticsearch aggregation query for all facets"""
        aggs = {}

        for facet_key, facet_config in self.FACET_DEFINITIONS.items():
            if facet_config['type'] == 'terms':
                aggs[facet_key] = {
                    'terms': {
                        'field': facet_config['field'],
                        'size': facet_config.get('size', 20)
                    }
                }
            elif facet_config['type'] == 'range':
                ranges = [
                    {k: v for k, v in r.items() if k in ['from', 'to', 'key']}
                    for r in facet_config['ranges']
                ]
                aggs[facet_key] = {
                    'range': {
                        'field': facet_config['field'],
                        'ranges': ranges
                    }
                }

        return aggs

    @api.model
    def process_facet_results(self, es_aggregations, lang='en'):
        """Process Elasticsearch aggregation results into user-friendly format"""
        facets = []

        for facet_key, facet_config in self.FACET_DEFINITIONS.items():
            if facet_key not in es_aggregations:
                continue

            es_facet = es_aggregations[facet_key]
            buckets = es_facet.get('buckets', [])

            # Skip empty facets
            if not buckets or all(b.get('doc_count', 0) == 0 for b in buckets):
                continue

            facet_data = {
                'key': facet_key,
                'display_name': facet_config.get(f'display_name_{lang[:2]}', facet_config['display_name']),
                'type': facet_config['type'],
                'values': []
            }

            for bucket in buckets:
                if bucket.get('doc_count', 0) == 0:
                    continue

                value_data = {
                    'key': str(bucket.get('key', bucket.get('key'))),
                    'count': bucket['doc_count']
                }

                # Add labels
                if facet_config['type'] == 'range':
                    # Find matching range for label
                    for range_def in facet_config.get('ranges', []):
                        if range_def.get('key') == bucket.get('key'):
                            value_data['label'] = range_def.get(f'label_{lang[:2]}', range_def.get('label'))
                            break
                elif 'value_labels' in facet_config:
                    labels = facet_config['value_labels'].get(str(bucket['key']).lower(), {})
                    value_data['label'] = labels.get(lang[:2], str(bucket['key']))
                else:
                    value_data['label'] = str(bucket['key'])

                facet_data['values'].append(value_data)

            if facet_data['values']:
                facets.append(facet_data)

        return facets

    @api.model
    def apply_facet_filters(self, selected_facets):
        """Convert selected facets to Elasticsearch filter clauses"""
        filters = []

        for facet_key, values in selected_facets.items():
            if facet_key not in self.FACET_DEFINITIONS:
                continue

            facet_config = self.FACET_DEFINITIONS[facet_key]

            if facet_config['type'] == 'terms':
                if len(values) == 1:
                    filters.append({
                        'term': {facet_config['field']: values[0]}
                    })
                else:
                    filters.append({
                        'terms': {facet_config['field']: values}
                    })

            elif facet_config['type'] == 'range':
                range_filters = []
                for value in values:
                    for range_def in facet_config.get('ranges', []):
                        if range_def.get('key') == value:
                            range_clause = {}
                            if 'from' in range_def:
                                range_clause['gte'] = range_def['from']
                            if 'to' in range_def:
                                range_clause['lt'] = range_def['to']
                            if range_clause:
                                range_filters.append({
                                    'range': {facet_config['field']: range_clause}
                                })
                            break

                if len(range_filters) == 1:
                    filters.extend(range_filters)
                elif len(range_filters) > 1:
                    filters.append({'bool': {'should': range_filters}})

        return filters
```

**Task 202.1.2: Enhanced Search with Facets (2h)**

```python
# Update smart_dairy_addons/smart_ecommerce_adv/services/elasticsearch_service.py
# Add to ElasticsearchService class:

def search_with_facets(self, query, filters=None, selected_facets=None,
                       page=1, limit=20, sort=None, lang='en'):
    """
    Search products with faceted filtering

    Args:
        query: Search query
        filters: Basic filters (price_min, price_max, etc.)
        selected_facets: Dict of selected facet values {facet_key: [values]}
        page: Page number
        limit: Results per page
        sort: Sort option
        lang: Language for facet labels
    """
    facet_model = self.env['product.search.facets']

    must_clauses = []
    filter_clauses = []

    # Main search query
    if query:
        must_clauses.append({
            "multi_match": {
                "query": query,
                "fields": ["name^3", "name.autocomplete^2", "name_bn^2",
                          "description", "category_name", "tags"],
                "type": "best_fields",
                "fuzziness": "AUTO"
            }
        })

    # Apply basic filters
    if filters:
        if filters.get('price_min') is not None:
            filter_clauses.append({
                'range': {'list_price': {'gte': filters['price_min']}}
            })
        if filters.get('price_max') is not None:
            filter_clauses.append({
                'range': {'list_price': {'lte': filters['price_max']}}
            })

    # Apply facet filters
    if selected_facets:
        facet_filters = facet_model.apply_facet_filters(selected_facets)
        filter_clauses.extend(facet_filters)

    # Get facet aggregations
    aggregations = facet_model.get_facet_aggregations()

    # Build search body
    search_body = {
        "query": {
            "bool": {
                "must": must_clauses if must_clauses else [{"match_all": {}}],
                "filter": filter_clauses
            }
        },
        "from": (page - 1) * limit,
        "size": limit,
        "aggs": aggregations,
        "highlight": {
            "fields": {
                "name": {},
                "description": {"fragment_size": 150}
            }
        }
    }

    # Apply sorting
    sort_options = {
        'price_asc': [{"list_price": "asc"}],
        'price_desc': [{"list_price": "desc"}],
        'rating': [{"rating_average": "desc"}],
        'popularity': [{"popularity_score": "desc"}],
        'newest': [{"created_date": "desc"}],
        'relevance': [{"_score": "desc"}]
    }
    search_body['sort'] = sort_options.get(sort, [{"_score": "desc"}])

    try:
        response = self._client.search(
            index=self.PRODUCT_INDEX,
            body=search_body
        )

        # Process facets
        facets = facet_model.process_facet_results(
            response.get('aggregations', {}),
            lang=lang
        )

        return {
            'total': response['hits']['total']['value'],
            'products': [hit['_source'] for hit in response['hits']['hits']],
            'facets': facets,
            'took_ms': response['took']
        }
    except Exception as e:
        _logger.error(f"Faceted search error: {e}")
        raise
```

**Task 202.1.3: Faceted Search API Endpoint (2h)**

```python
# api/routers/search.py - Add to existing router

class FacetedSearchRequest(BaseModel):
    query: str = Field(default="", max_length=200)
    filters: Optional[SearchFilters] = None
    facets: Optional[Dict[str, List[str]]] = None
    page: int = Field(default=1, ge=1)
    limit: int = Field(default=20, ge=1, le=100)
    sort: str = Field(default="relevance")
    lang: str = Field(default="en", pattern="^(en|bn)$")

@router.post("/products/faceted", response_model=SearchResponse)
async def faceted_search(request: FacetedSearchRequest):
    """
    Search products with faceted filtering

    - **query**: Search term (optional)
    - **filters**: Basic filters
    - **facets**: Selected facet values {facet_key: [value1, value2]}
    - **page**: Page number
    - **limit**: Results per page
    - **sort**: Sort option
    - **lang**: Language for facet labels (en/bn)
    """
    try:
        odoo = odoo_rpc.get_connection()
        es_service = ElasticsearchService()

        result = es_service.search_with_facets(
            query=request.query,
            filters=request.filters.dict(exclude_none=True) if request.filters else None,
            selected_facets=request.facets,
            page=request.page,
            limit=request.limit,
            sort=request.sort,
            lang=request.lang
        )

        return SearchResponse(
            success=True,
            data={
                'total': result['total'],
                'page': request.page,
                'limit': request.limit,
                'products': result['products'],
                'facets': result['facets'],
                'took_ms': result['took_ms']
            }
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 202.2.1: Search Results Controller (4h)**

```python
# smart_dairy_addons/smart_ecommerce_adv/controllers/search_controller.py
from odoo import http
from odoo.http import request
import json
import logging

_logger = logging.getLogger(__name__)

class SearchController(http.Controller):

    @http.route('/shop/search', type='http', auth='public', website=True)
    def search_page(self, **kwargs):
        """Render search results page"""
        query = kwargs.get('search', '')
        page = int(kwargs.get('page', 1))
        limit = int(kwargs.get('limit', 20))
        sort = kwargs.get('sort', 'relevance')

        # Parse facet filters from URL
        selected_facets = {}
        for key, value in kwargs.items():
            if key.startswith('facet_'):
                facet_key = key.replace('facet_', '')
                selected_facets[facet_key] = value.split(',') if isinstance(value, str) else [value]

        # Get search results
        search_api = request.env['product.search.api']
        lang = request.env.lang[:2] if request.env.lang else 'en'

        try:
            es_service = request.env['elasticsearch.service'].sudo()
            results = es_service.search_with_facets(
                query=query,
                selected_facets=selected_facets,
                page=page,
                limit=limit,
                sort=sort,
                lang=lang
            )
        except Exception as e:
            _logger.error(f"Search error: {e}")
            results = {'total': 0, 'products': [], 'facets': []}

        # Calculate pagination
        total_pages = (results['total'] + limit - 1) // limit

        return request.render('smart_ecommerce_adv.search_results_page', {
            'query': query,
            'products': results.get('products', []),
            'facets': results.get('facets', []),
            'selected_facets': selected_facets,
            'total': results.get('total', 0),
            'page': page,
            'limit': limit,
            'total_pages': total_pages,
            'sort': sort,
            'sort_options': [
                {'key': 'relevance', 'label': 'Relevance'},
                {'key': 'price_asc', 'label': 'Price: Low to High'},
                {'key': 'price_desc', 'label': 'Price: High to Low'},
                {'key': 'rating', 'label': 'Highest Rated'},
                {'key': 'popularity', 'label': 'Most Popular'},
                {'key': 'newest', 'label': 'Newest'},
            ]
        })

    @http.route('/shop/search/ajax', type='json', auth='public', website=True)
    def search_ajax(self, **kwargs):
        """AJAX endpoint for search results"""
        query = kwargs.get('query', '')
        page = int(kwargs.get('page', 1))
        limit = int(kwargs.get('limit', 20))
        sort = kwargs.get('sort', 'relevance')
        selected_facets = kwargs.get('facets', {})
        lang = request.env.lang[:2] if request.env.lang else 'en'

        try:
            es_service = request.env['elasticsearch.service'].sudo()
            results = es_service.search_with_facets(
                query=query,
                selected_facets=selected_facets,
                page=page,
                limit=limit,
                sort=sort,
                lang=lang
            )

            # Render product cards HTML
            products_html = request.env['ir.ui.view']._render_template(
                'smart_ecommerce_adv.search_product_cards',
                {'products': results.get('products', [])}
            )

            # Render facets HTML
            facets_html = request.env['ir.ui.view']._render_template(
                'smart_ecommerce_adv.search_facets',
                {
                    'facets': results.get('facets', []),
                    'selected_facets': selected_facets
                }
            )

            return {
                'success': True,
                'products_html': products_html,
                'facets_html': facets_html,
                'total': results.get('total', 0),
                'page': page,
                'total_pages': (results['total'] + limit - 1) // limit,
                'took_ms': results.get('took_ms', 0)
            }
        except Exception as e:
            _logger.error(f"AJAX search error: {e}")
            return {'success': False, 'error': str(e)}
```

**Task 202.2.2: Search Results Template (2h)**

```xml
<!-- smart_dairy_addons/smart_ecommerce_adv/views/templates/search_templates.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <template id="search_results_page" name="Search Results">
        <t t-call="website.layout">
            <div id="wrap" class="o_shop_search_page">
                <div class="container py-4">
                    <!-- Search Header -->
                    <div class="search-header mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h4 mb-0">
                                    <t t-if="query">
                                        Search results for "<t t-esc="query"/>"
                                    </t>
                                    <t t-else="">
                                        All Products
                                    </t>
                                </h1>
                                <p class="text-muted mb-0">
                                    <t t-esc="total"/> products found
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="d-inline-flex align-items-center">
                                    <label class="me-2 mb-0">Sort by:</label>
                                    <select id="sortSelect" class="form-select form-select-sm w-auto">
                                        <t t-foreach="sort_options" t-as="option">
                                            <option t-att-value="option['key']"
                                                    t-att-selected="option['key'] == sort">
                                                <t t-esc="option['label']"/>
                                            </option>
                                        </t>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Facets Sidebar -->
                        <div class="col-lg-3">
                            <div id="searchFacets" class="search-facets">
                                <t t-call="smart_ecommerce_adv.search_facets">
                                    <t t-set="facets" t-value="facets"/>
                                    <t t-set="selected_facets" t-value="selected_facets"/>
                                </t>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="col-lg-9">
                            <div id="searchResults" class="search-results">
                                <t t-call="smart_ecommerce_adv.search_product_cards">
                                    <t t-set="products" t-value="products"/>
                                </t>
                            </div>

                            <!-- Pagination -->
                            <t t-if="total_pages > 1">
                                <nav class="pagination-wrapper mt-4">
                                    <ul class="pagination justify-content-center">
                                        <li t-attf-class="page-item #{'disabled' if page == 1 else ''}">
                                            <a class="page-link" t-attf-href="?search=#{query}&amp;page=#{page - 1}">&laquo;</a>
                                        </li>
                                        <t t-foreach="range(1, total_pages + 1)" t-as="p">
                                            <t t-if="p &lt;= 3 or p &gt; total_pages - 2 or abs(p - page) &lt;= 1">
                                                <li t-attf-class="page-item #{'active' if p == page else ''}">
                                                    <a class="page-link" t-attf-href="?search=#{query}&amp;page=#{p}">
                                                        <t t-esc="p"/>
                                                    </a>
                                                </li>
                                            </t>
                                            <t t-elif="p == 4 or p == total_pages - 2">
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            </t>
                                        </t>
                                        <li t-attf-class="page-item #{'disabled' if page == total_pages else ''}">
                                            <a class="page-link" t-attf-href="?search=#{query}&amp;page=#{page + 1}">&raquo;</a>
                                        </li>
                                    </ul>
                                </nav>
                            </t>
                        </div>
                    </div>
                </div>
            </div>
        </t>
    </template>

    <template id="search_facets" name="Search Facets">
        <div class="facets-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Filters</h5>
                <button id="clearFilters" class="btn btn-link btn-sm p-0"
                        t-att-style="'display: none;' if not selected_facets else ''">
                    Clear all
                </button>
            </div>

            <t t-foreach="facets" t-as="facet">
                <div class="facet-group mb-3">
                    <div class="facet-header" data-bs-toggle="collapse"
                         t-attf-data-bs-target="#facet_#{facet['key']}"
                         aria-expanded="true">
                        <span class="facet-title">
                            <t t-esc="facet['display_name']"/>
                        </span>
                        <i class="fa fa-chevron-down facet-toggle"/>
                    </div>
                    <div t-attf-id="facet_#{facet['key']}" class="collapse show">
                        <div class="facet-values">
                            <t t-foreach="facet['values']" t-as="value">
                                <div class="facet-value form-check">
                                    <input type="checkbox"
                                           class="form-check-input facet-checkbox"
                                           t-attf-id="facet_#{facet['key']}_#{value['key']}"
                                           t-att-data-facet="facet['key']"
                                           t-att-data-value="value['key']"
                                           t-att-checked="selected_facets.get(facet['key']) and value['key'] in selected_facets.get(facet['key'], [])"/>
                                    <label class="form-check-label"
                                           t-attf-for="facet_#{facet['key']}_#{value['key']}">
                                        <t t-esc="value['label']"/>
                                        <span class="facet-count">(<t t-esc="value['count']"/>)</span>
                                    </label>
                                </div>
                            </t>
                        </div>
                    </div>
                </div>
            </t>
        </div>
    </template>

    <template id="search_product_cards" name="Search Product Cards">
        <div class="products-grid row">
            <t t-foreach="products" t-as="product">
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="product-card card h-100">
                        <a t-attf-href="/shop/product/#{product['id']}" class="product-image-link">
                            <img t-att-src="product.get('thumbnail_url', '/web/image/product.template/0/image_256')"
                                 t-att-alt="product['name']"
                                 class="card-img-top product-image"
                                 loading="lazy"/>
                            <t t-if="not product.get('in_stock')">
                                <span class="badge bg-secondary out-of-stock-badge">Out of Stock</span>
                            </t>
                        </a>
                        <div class="card-body">
                            <h5 class="card-title product-name">
                                <a t-attf-href="/shop/product/#{product['id']}">
                                    <t t-esc="product['name']"/>
                                </a>
                            </h5>
                            <div class="product-meta">
                                <t t-if="product.get('rating_average', 0) > 0">
                                    <div class="product-rating">
                                        <t t-foreach="range(5)" t-as="star">
                                            <i t-attf-class="fa fa-star #{'text-warning' if star &lt; product['rating_average'] else 'text-muted'}"/>
                                        </t>
                                        <span class="rating-count">
                                            (<t t-esc="product.get('rating_count', 0)"/>)
                                        </span>
                                    </div>
                                </t>
                                <div class="product-price">
                                    <span class="price">৳<t t-esc="'{:,.0f}'.format(product['list_price'])"/></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <button class="btn btn-primary btn-sm w-100 add-to-cart-btn"
                                    t-att-data-product-id="product['id']"
                                    t-att-disabled="not product.get('in_stock')">
                                <i class="fa fa-shopping-cart me-1"/>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </t>

            <t t-if="not products">
                <div class="col-12 text-center py-5">
                    <i class="fa fa-search fa-3x text-muted mb-3"/>
                    <h4>No products found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                </div>
            </t>
        </div>
    </template>
</odoo>
```

**Task 202.2.3: Faceted Search JavaScript (2h)**

```javascript
// smart_ecommerce_adv/static/src/js/faceted_search.js
document.addEventListener('DOMContentLoaded', function() {
    const searchResults = document.getElementById('searchResults');
    const searchFacets = document.getElementById('searchFacets');
    const sortSelect = document.getElementById('sortSelect');
    const clearFilters = document.getElementById('clearFilters');

    let currentState = {
        query: new URLSearchParams(window.location.search).get('search') || '',
        facets: {},
        page: 1,
        sort: sortSelect?.value || 'relevance'
    };

    // Initialize facets from URL
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.forEach((value, key) => {
        if (key.startsWith('facet_')) {
            currentState.facets[key.replace('facet_', '')] = value.split(',');
        }
    });

    // Facet checkbox change handler
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('facet-checkbox')) {
            const facetKey = e.target.dataset.facet;
            const facetValue = e.target.dataset.value;

            if (!currentState.facets[facetKey]) {
                currentState.facets[facetKey] = [];
            }

            if (e.target.checked) {
                if (!currentState.facets[facetKey].includes(facetValue)) {
                    currentState.facets[facetKey].push(facetValue);
                }
            } else {
                currentState.facets[facetKey] = currentState.facets[facetKey].filter(v => v !== facetValue);
                if (currentState.facets[facetKey].length === 0) {
                    delete currentState.facets[facetKey];
                }
            }

            currentState.page = 1;
            performSearch();
        }
    });

    // Sort change handler
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentState.sort = this.value;
            currentState.page = 1;
            performSearch();
        });
    }

    // Clear filters handler
    if (clearFilters) {
        clearFilters.addEventListener('click', function(e) {
            e.preventDefault();
            currentState.facets = {};
            currentState.page = 1;
            performSearch();
        });
    }

    async function performSearch() {
        searchResults.classList.add('loading');

        try {
            const response = await fetch('/shop/search/ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    jsonrpc: '2.0',
                    method: 'call',
                    params: {
                        query: currentState.query,
                        facets: currentState.facets,
                        page: currentState.page,
                        sort: currentState.sort
                    }
                })
            });

            const data = await response.json();

            if (data.result && data.result.success) {
                searchResults.innerHTML = data.result.products_html;
                searchFacets.innerHTML = data.result.facets_html;

                // Update URL without reload
                updateURL();

                // Update clear filters visibility
                const hasFilters = Object.keys(currentState.facets).length > 0;
                if (clearFilters) {
                    clearFilters.style.display = hasFilters ? 'block' : 'none';
                }

                // Scroll to top of results
                searchResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            searchResults.classList.remove('loading');
        }
    }

    function updateURL() {
        const params = new URLSearchParams();

        if (currentState.query) {
            params.set('search', currentState.query);
        }
        if (currentState.page > 1) {
            params.set('page', currentState.page);
        }
        if (currentState.sort !== 'relevance') {
            params.set('sort', currentState.sort);
        }

        Object.entries(currentState.facets).forEach(([key, values]) => {
            if (values.length > 0) {
                params.set(`facet_${key}`, values.join(','));
            }
        });

        const newURL = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newURL);
    }
});
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 202.3.1: Facets UI Styling (4h)**

```scss
// smart_ecommerce_adv/static/src/scss/_facets.scss
.search-facets {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);

    @media (max-width: 991px) {
        position: fixed;
        top: 0;
        left: -100%;
        width: 80%;
        max-width: 320px;
        height: 100vh;
        z-index: 1050;
        overflow-y: auto;
        transition: left 0.3s ease;
        border-radius: 0;

        &.show {
            left: 0;
        }
    }

    .facet-group {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 16px;

        &:last-child {
            border-bottom: none;
        }

        .facet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 8px 0;

            .facet-title {
                font-weight: 600;
                color: #333;
            }

            .facet-toggle {
                transition: transform 0.2s ease;
                color: #888;
            }

            &[aria-expanded="false"] .facet-toggle {
                transform: rotate(-90deg);
            }
        }

        .facet-values {
            max-height: 200px;
            overflow-y: auto;

            &::-webkit-scrollbar {
                width: 4px;
            }

            &::-webkit-scrollbar-track {
                background: #f0f0f0;
                border-radius: 2px;
            }

            &::-webkit-scrollbar-thumb {
                background: #ccc;
                border-radius: 2px;
            }
        }

        .facet-value {
            padding: 6px 0;

            .form-check-label {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 0.9rem;
                color: #555;
                cursor: pointer;

                &:hover {
                    color: var(--sd-primary, #007bff);
                }
            }

            .facet-count {
                color: #999;
                font-size: 0.85rem;
            }

            .form-check-input:checked + .form-check-label {
                color: var(--sd-primary, #007bff);
                font-weight: 500;
            }
        }
    }

    #clearFilters {
        color: var(--sd-primary, #007bff);
        text-decoration: none;
        font-size: 0.85rem;

        &:hover {
            text-decoration: underline;
        }
    }
}

// Products grid
.products-grid {
    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;

        &:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .product-image-link {
            position: relative;
            display: block;

            .product-image {
                aspect-ratio: 1;
                object-fit: cover;
            }

            .out-of-stock-badge {
                position: absolute;
                top: 10px;
                right: 10px;
            }
        }

        .product-name {
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.3;
            height: 2.6em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            a {
                color: #333;
                text-decoration: none;

                &:hover {
                    color: var(--sd-primary, #007bff);
                }
            }
        }

        .product-rating {
            font-size: 0.8rem;
            margin-bottom: 6px;

            .fa-star {
                font-size: 0.7rem;
            }

            .rating-count {
                color: #888;
                font-size: 0.75rem;
            }
        }

        .product-price {
            .price {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--sd-primary, #007bff);
            }
        }

        .add-to-cart-btn {
            border-radius: 20px;
            font-size: 0.85rem;
            padding: 8px 16px;
        }
    }
}

// Loading state
.search-results.loading {
    opacity: 0.5;
    pointer-events: none;
}

// Mobile filter toggle
.mobile-filter-toggle {
    @media (min-width: 992px) {
        display: none;
    }

    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1040;
    background: var(--sd-primary, #007bff);
    color: white;
    border: none;
    border-radius: 25px;
    padding: 12px 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);

    i {
        margin-right: 8px;
    }
}

// Facets overlay for mobile
.facets-overlay {
    display: none;

    @media (max-width: 991px) {
        &.show {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
    }
}
```

**Task 202.3.2: Search Results Styling (2h)**

```scss
// smart_ecommerce_adv/static/src/scss/_search_results.scss
.o_shop_search_page {
    .search-header {
        h1 {
            color: #333;
        }
    }

    // Active filters pills
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;

        .filter-pill {
            display: inline-flex;
            align-items: center;
            background: #f0f0f0;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.85rem;

            .filter-label {
                color: #666;
                margin-right: 4px;
            }

            .filter-value {
                color: #333;
                font-weight: 500;
            }

            .remove-filter {
                margin-left: 8px;
                color: #999;
                cursor: pointer;

                &:hover {
                    color: #dc3545;
                }
            }
        }
    }

    // Pagination
    .pagination-wrapper {
        .pagination {
            .page-item {
                margin: 0 2px;

                .page-link {
                    border-radius: 8px;
                    border: none;
                    color: #555;
                    min-width: 40px;
                    text-align: center;

                    &:hover {
                        background: #f0f0f0;
                    }
                }

                &.active .page-link {
                    background: var(--sd-primary, #007bff);
                    color: white;
                }

                &.disabled .page-link {
                    color: #ccc;
                }
            }
        }
    }
}
```

**Task 202.3.3: Mobile Filter Toggle (2h)**

```javascript
// smart_ecommerce_adv/static/src/js/mobile_filters.js
document.addEventListener('DOMContentLoaded', function() {
    // Create mobile filter elements
    const filterContainer = document.querySelector('.search-facets');

    if (filterContainer && window.innerWidth < 992) {
        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'mobile-filter-toggle btn';
        toggleBtn.innerHTML = '<i class="fa fa-filter"></i> Filters';
        document.body.appendChild(toggleBtn);

        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'facets-overlay';
        document.body.appendChild(overlay);

        // Create close button inside facets
        const closeBtn = document.createElement('button');
        closeBtn.className = 'btn btn-link position-absolute top-0 end-0 m-2';
        closeBtn.innerHTML = '<i class="fa fa-times fa-lg"></i>';
        filterContainer.insertBefore(closeBtn, filterContainer.firstChild);

        // Toggle handlers
        toggleBtn.addEventListener('click', function() {
            filterContainer.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });

        function closeFacets() {
            filterContainer.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        closeBtn.addEventListener('click', closeFacets);
        overlay.addEventListener('click', closeFacets);
    }
});
```

**End-of-Day 202 Deliverables:**
- [ ] Faceted search model with 7 facet definitions
- [ ] Facet aggregation and filtering logic
- [ ] Faceted search API endpoint
- [ ] Search results page with facets sidebar
- [ ] AJAX-based facet filtering without page reload
- [ ] URL state management for filters
- [ ] Responsive mobile filter panel
- [ ] Complete facets and product grid styling

---

*(Days 203-210 continue with similar detail for remaining deliverables: Product variants, Recommendations, Reviews, Wishlist, Comparison, Social sharing, Performance optimization, and Integration testing)*

---

## 4. Technical Specifications

### 4.1 Data Models Summary

| Model | Description | Key Fields |
|-------|-------------|------------|
| `product.template` (extended) | Product with search fields | search_keywords, popularity_score, es_indexed |
| `product.review` | Customer reviews | rating, comment, images, status, helpful_votes |
| `product.wishlist` | Customer wishlist | user_id, product_ids, shared_link |
| `product.comparison` | Comparison session | user_id, product_ids, attributes |
| `product.recommendation.log` | Recommendation tracking | user_id, product_id, source, clicked |

### 4.2 Elasticsearch Index Schema

```json
{
  "smart_dairy_products": {
    "mappings": {
      "properties": {
        "id": {"type": "integer"},
        "name": {"type": "text", "fields": {"autocomplete": {...}, "keyword": {...}}},
        "name_bn": {"type": "text"},
        "category_id": {"type": "integer"},
        "category_name": {"type": "text", "fields": {"keyword": {...}}},
        "list_price": {"type": "float"},
        "in_stock": {"type": "boolean"},
        "rating_average": {"type": "float"},
        "popularity_score": {"type": "float"},
        "dairy_type": {"type": "keyword"},
        "suggest": {"type": "completion"}
      }
    }
  }
}
```

### 4.3 API Endpoints Summary

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/search/products` | POST | Full-text product search | Public |
| `/api/v1/search/products/faceted` | POST | Faceted search with filters | Public |
| `/api/v1/search/autocomplete` | GET | Search suggestions | Public |
| `/api/v1/recommendations/{user_id}` | GET | Personalized recommendations | User |
| `/api/v1/reviews` | GET/POST | Product reviews | Public/User |
| `/api/v1/wishlist` | GET/POST/DELETE | Wishlist management | User |
| `/api/v1/compare` | POST | Product comparison | Public |

### 4.4 Performance Targets

| Metric | Target | Measurement |
|--------|--------|-------------|
| Search response | < 200ms P95 | Elasticsearch metrics |
| Autocomplete | < 100ms | Frontend timing |
| Page load (LCP) | < 2.5s | Lighthouse |
| First Input Delay | < 100ms | Lighthouse |
| Cumulative Layout Shift | < 0.1 | Lighthouse |
| Image optimization | < 100KB per image | CDN analytics |

---

## 5. Testing & Validation

### 5.1 Unit Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-21-001 | Search with empty query | Returns all products |
| TC-21-002 | Search with filters | Filters applied correctly |
| TC-21-003 | Autocomplete suggestions | Returns relevant products |
| TC-21-004 | Facet aggregation | Correct bucket counts |
| TC-21-005 | Review submission | Review created with pending status |
| TC-21-006 | Wishlist add/remove | Product added/removed |
| TC-21-007 | Product comparison | Comparison data generated |
| TC-21-008 | Recommendation scoring | Correct scores calculated |

### 5.2 Integration Test Scenarios

| Scenario | Steps | Expected Outcome |
|----------|-------|------------------|
| Full search flow | Type query → Filter → Sort → Paginate | Correct results displayed |
| Review workflow | Submit review → Moderate → Publish | Review visible on product |
| Wishlist sync | Add on web → Check mobile API | Same items returned |
| Recommendation | Browse products → Check recommendations | Personalized suggestions |

### 5.3 Performance Benchmarks

| Operation | Target | Test Tool |
|-----------|--------|-----------|
| Search API | < 200ms P95 | k6 load test |
| Page load | < 2s | Lighthouse |
| Image loading | < 500ms | WebPageTest |
| CDN cache hit | > 95% | CDN analytics |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R21-001 | Elasticsearch cluster failure | Low | High | Multi-node cluster, backups |
| R21-002 | Search relevance issues | Medium | Medium | Tune analyzers, A/B testing |
| R21-003 | CDN configuration errors | Low | Medium | Staged rollout, monitoring |
| R21-004 | Review spam | Medium | Medium | Moderation workflow, captcha |
| R21-005 | Recommendation cold start | Medium | Low | Popularity-based fallback |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Phase 2

- Public website with product catalog (MS-17)
- Payment gateway integration (MS-18)
- Customer authentication system
- API Gateway operational (MS-20)

### 7.2 Outputs for Subsequent Milestones

| Output | Consumer Milestone | Description |
|--------|-------------------|-------------|
| Search API | MS-22 (Mobile) | Mobile product search |
| Wishlist API | MS-22 (Mobile) | Mobile wishlist sync |
| Reviews API | MS-22 (Mobile) | Mobile reviews display |
| Recommendation engine | MS-24 (Loyalty) | Personalized rewards |
| Product data | MS-27 (Analytics) | Search analytics |

---

**Document End**

*Milestone 21: Advanced B2C E-Commerce Portal*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 3 — E-Commerce, Mobile & Analytics*
*Version 1.0 | February 2026*
