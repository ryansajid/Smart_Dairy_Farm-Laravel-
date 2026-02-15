# Milestone 61: Product Catalog Enhancement

## Smart Dairy Digital Smart Portal + ERP — Phase 7: B2C E-Commerce Core

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 61 of 150 (1 of 10 in Phase 7)                                         |
| **Title**        | Product Catalog Enhancement                                            |
| **Phase**        | Phase 7 — B2C E-Commerce Core                                          |
| **Days**         | Days 351-360 (10 working days)                                         |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead)        |
| **Last Updated** | 2026-02-03                                                             |

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

Implement advanced product discovery features with Elasticsearch-powered search, AI-driven recommendations, faceted filtering, and product comparison tools to increase product findability and conversion rates.

### 1.2 Objectives

1. Configure Elasticsearch 8 cluster for product indexing
2. Implement full-text search with fuzzy matching and autocomplete
3. Create faceted filtering (10+ attributes)
4. Build AI-powered recommendation engine using collaborative filtering
5. Implement product comparison tool (up to 4 products)
6. Create recently viewed products tracking
7. Build related products and cross-sell suggestions
8. Implement search analytics and trending queries
9. Create zero-results page with suggestions
10. Build search synonyms and boost rules management

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| Elasticsearch Configuration     | Dev 1  | Python module     | 351     |
| Product Indexer Service         | Dev 1  | Odoo module       | 352     |
| Search API Endpoints            | Dev 1  | REST API          | 353     |
| Filter Aggregations             | Dev 1  | Python service    | 354     |
| Recommendation Engine           | Dev 1  | Python service    | 355-356 |
| Search Bar OWL Component        | Dev 3  | JavaScript/XML    | 352     |
| Filter Sidebar Component        | Dev 3  | OWL component     | 353-354 |
| Product Comparison UI           | Dev 3  | OWL component     | 357-358 |
| Search Analytics Dashboard      | Dev 2  | Python/OWL        | 359     |
| Integration Tests               | Dev 2  | Test suite        | 360     |

### 1.4 Prerequisites

- Phase 6 APIs stable and documented
- Elasticsearch 8.11+ cluster provisioned
- Redis 7.2+ cluster available for caching
- Product catalog with 50+ products for testing
- PostgreSQL 16 with product data indexed
- Odoo 19 CE development environment operational

### 1.5 Success Criteria

- [ ] Elasticsearch indexes all products successfully
- [ ] Search response time <200ms (p95)
- [ ] Autocomplete returns results in <100ms
- [ ] Filters update results without page reload
- [ ] Recommendations show on 80%+ product pages
- [ ] Comparison tool handles 4 products correctly
- [ ] Recently viewed persists across sessions
- [ ] Search analytics captures all queries
- [ ] >80% unit test coverage for new code

---

## 2. Requirement Traceability Matrix

| Req ID           | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ---------------- | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-B2C-4.1.2-001| RFP    | Full-text product search                 | 351-353 | Elasticsearch setup         |
| RFP-B2C-4.1.2-002| RFP    | Autocomplete suggestions                 | 352     | Autocomplete API            |
| RFP-B2C-4.1.2-003| RFP    | Advanced filters (10+ attributes)        | 353-354 | Faceted search              |
| RFP-B2C-4.1.3-001| RFP    | AI-powered recommendations               | 355-356 | Recommendation engine       |
| RFP-B2C-4.1.4-001| RFP    | Product comparison tool                  | 357-358 | Comparison feature          |
| BRD-FR-B2C-001   | BRD    | Product discovery optimization           | 351-360 | All search features         |
| SRS-REQ-B2C-001  | SRS    | Elasticsearch 8+ integration             | 351     | ES configuration            |
| SRS-REQ-B2C-002  | SRS    | <200ms search response time              | 351-360 | Performance optimization    |
| H-002            | Impl   | Elasticsearch Integration Guide          | 351-354 | ES implementation           |

---

## 3. Day-by-Day Breakdown

### Day 351 — Elasticsearch Configuration & Index Setup

**Objective:** Configure Elasticsearch cluster, create product index with optimized mappings, and establish connection from Odoo.

#### Dev 1 — Backend Lead (8h)

**Task 1: Elasticsearch Service Configuration (4h)**

```python
# odoo/addons/smart_dairy_ecommerce/models/elasticsearch_service.py
from elasticsearch import Elasticsearch, helpers
from odoo import models, api, fields
import logging
import json

_logger = logging.getLogger(__name__)


class ElasticsearchService(models.AbstractModel):
    _name = 'elasticsearch.service'
    _description = 'Elasticsearch Integration Service'

    @api.model
    def get_client(self):
        """
        Get Elasticsearch client instance with connection pooling.

        Returns:
            Elasticsearch: Configured client instance
        """
        config = self.env['ir.config_parameter'].sudo()

        hosts = config.get_param('elasticsearch.hosts', 'http://localhost:9200')
        username = config.get_param('elasticsearch.username', 'elastic')
        password = config.get_param('elasticsearch.password', '')
        verify_certs = config.get_param('elasticsearch.verify_certs', 'false') == 'true'

        # Parse multiple hosts
        host_list = [h.strip() for h in hosts.split(',')]

        client = Elasticsearch(
            hosts=host_list,
            basic_auth=(username, password) if password else None,
            verify_certs=verify_certs,
            request_timeout=30,
            max_retries=3,
            retry_on_timeout=True,
        )

        # Verify connection
        if not client.ping():
            _logger.error('Cannot connect to Elasticsearch')
            raise ConnectionError('Elasticsearch connection failed')

        return client

    @api.model
    def get_index_name(self):
        """Get the product index name based on environment"""
        config = self.env['ir.config_parameter'].sudo()
        env_prefix = config.get_param('elasticsearch.env_prefix', 'dev')
        return f'{env_prefix}_smart_dairy_products'

    @api.model
    def create_product_index(self):
        """
        Create product index with optimized mappings for dairy products.

        Returns:
            bool: True if successful
        """
        es = self.get_client()
        index_name = self.get_index_name()

        # Delete existing index if exists
        if es.indices.exists(index=index_name):
            es.indices.delete(index=index_name)
            _logger.info(f'Deleted existing index: {index_name}')

        # Index settings optimized for search
        index_settings = {
            "settings": {
                "number_of_shards": 2,
                "number_of_replicas": 1,
                "refresh_interval": "1s",
                "analysis": {
                    "analyzer": {
                        "product_analyzer": {
                            "type": "custom",
                            "tokenizer": "standard",
                            "filter": ["lowercase", "asciifolding", "product_stemmer"]
                        },
                        "bangla_analyzer": {
                            "type": "custom",
                            "tokenizer": "standard",
                            "filter": ["lowercase"]
                        },
                        "autocomplete_analyzer": {
                            "type": "custom",
                            "tokenizer": "autocomplete_tokenizer",
                            "filter": ["lowercase", "asciifolding"]
                        },
                        "autocomplete_search_analyzer": {
                            "type": "custom",
                            "tokenizer": "standard",
                            "filter": ["lowercase", "asciifolding"]
                        }
                    },
                    "tokenizer": {
                        "autocomplete_tokenizer": {
                            "type": "edge_ngram",
                            "min_gram": 2,
                            "max_gram": 20,
                            "token_chars": ["letter", "digit"]
                        }
                    },
                    "filter": {
                        "product_stemmer": {
                            "type": "stemmer",
                            "language": "english"
                        }
                    }
                }
            },
            "mappings": {
                "properties": {
                    # Core fields
                    "id": {"type": "integer"},
                    "name": {
                        "type": "text",
                        "analyzer": "product_analyzer",
                        "fields": {
                            "autocomplete": {
                                "type": "text",
                                "analyzer": "autocomplete_analyzer",
                                "search_analyzer": "autocomplete_search_analyzer"
                            },
                            "keyword": {"type": "keyword"},
                            "exact": {
                                "type": "text",
                                "analyzer": "keyword"
                            }
                        }
                    },
                    "name_bn": {
                        "type": "text",
                        "analyzer": "bangla_analyzer",
                        "fields": {
                            "keyword": {"type": "keyword"}
                        }
                    },
                    "description": {
                        "type": "text",
                        "analyzer": "product_analyzer"
                    },
                    "description_bn": {
                        "type": "text",
                        "analyzer": "bangla_analyzer"
                    },
                    "sku": {"type": "keyword"},
                    "barcode": {"type": "keyword"},

                    # Category hierarchy
                    "category_id": {"type": "integer"},
                    "category_name": {"type": "keyword"},
                    "category_path": {"type": "keyword"},
                    "category_ids": {"type": "integer"},

                    # Pricing
                    "price": {"type": "float"},
                    "list_price": {"type": "float"},
                    "compare_price": {"type": "float"},
                    "discount_percentage": {"type": "float"},
                    "price_range": {"type": "keyword"},

                    # Availability
                    "in_stock": {"type": "boolean"},
                    "qty_available": {"type": "float"},
                    "stock_status": {"type": "keyword"},

                    # Ratings
                    "rating_avg": {"type": "float"},
                    "rating_count": {"type": "integer"},
                    "review_count": {"type": "integer"},

                    # Media
                    "image_url": {"type": "keyword"},
                    "image_urls": {"type": "keyword"},
                    "has_video": {"type": "boolean"},

                    # Attributes
                    "brand": {"type": "keyword"},
                    "brand_id": {"type": "integer"},
                    "tags": {"type": "keyword"},
                    "attributes": {
                        "type": "nested",
                        "properties": {
                            "name": {"type": "keyword"},
                            "value": {"type": "keyword"}
                        }
                    },

                    # Dairy-specific
                    "fat_percentage": {"type": "float"},
                    "shelf_life_days": {"type": "integer"},
                    "storage_temp": {"type": "keyword"},
                    "is_organic": {"type": "boolean"},
                    "certifications": {"type": "keyword"},
                    "nutritional_info": {"type": "object", "enabled": False},

                    # E-commerce
                    "is_featured": {"type": "boolean"},
                    "is_bestseller": {"type": "boolean"},
                    "is_new": {"type": "boolean"},
                    "subscription_eligible": {"type": "boolean"},

                    # Search optimization
                    "search_keywords": {"type": "text", "analyzer": "product_analyzer"},
                    "popularity_score": {"type": "float"},
                    "sales_count": {"type": "integer"},
                    "view_count": {"type": "integer"},

                    # Timestamps
                    "created_date": {"type": "date"},
                    "updated_date": {"type": "date"},

                    # Suggest field for autocomplete
                    "suggest": {
                        "type": "completion",
                        "analyzer": "autocomplete_analyzer",
                        "preserve_separators": True,
                        "preserve_position_increments": True,
                        "max_input_length": 50
                    }
                }
            }
        }

        # Create index
        es.indices.create(index=index_name, body=index_settings)
        _logger.info(f'Created Elasticsearch index: {index_name}')

        return True

    @api.model
    def check_index_health(self):
        """Check the health of the product index"""
        es = self.get_client()
        index_name = self.get_index_name()

        if not es.indices.exists(index=index_name):
            return {'status': 'missing', 'message': 'Index does not exist'}

        health = es.cluster.health(index=index_name)
        stats = es.indices.stats(index=index_name)

        return {
            'status': health['status'],
            'number_of_docs': stats['indices'][index_name]['primaries']['docs']['count'],
            'size_in_bytes': stats['indices'][index_name]['primaries']['store']['size_in_bytes'],
        }
```

**Task 2: System Parameter Configuration (2h)**

```xml
<!-- odoo/addons/smart_dairy_ecommerce/data/ir_config_parameter.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <!-- Elasticsearch Configuration -->
        <record id="config_elasticsearch_hosts" model="ir.config_parameter">
            <field name="key">elasticsearch.hosts</field>
            <field name="value">http://localhost:9200</field>
        </record>

        <record id="config_elasticsearch_username" model="ir.config_parameter">
            <field name="key">elasticsearch.username</field>
            <field name="value">elastic</field>
        </record>

        <record id="config_elasticsearch_password" model="ir.config_parameter">
            <field name="key">elasticsearch.password</field>
            <field name="value"></field>
        </record>

        <record id="config_elasticsearch_verify_certs" model="ir.config_parameter">
            <field name="key">elasticsearch.verify_certs</field>
            <field name="value">false</field>
        </record>

        <record id="config_elasticsearch_env_prefix" model="ir.config_parameter">
            <field name="key">elasticsearch.env_prefix</field>
            <field name="value">dev</field>
        </record>

        <!-- Search Configuration -->
        <record id="config_search_min_score" model="ir.config_parameter">
            <field name="key">search.min_score</field>
            <field name="value">0.1</field>
        </record>

        <record id="config_search_fuzziness" model="ir.config_parameter">
            <field name="key">search.fuzziness</field>
            <field name="value">AUTO</field>
        </record>

        <record id="config_autocomplete_limit" model="ir.config_parameter">
            <field name="key">autocomplete.limit</field>
            <field name="value">10</field>
        </record>
    </data>
</odoo>
```

**Task 3: Module Manifest Update (2h)**

```python
# odoo/addons/smart_dairy_ecommerce/__manifest__.py
{
    'name': 'Smart Dairy E-Commerce',
    'version': '19.0.1.0.0',
    'category': 'Website/Website',
    'summary': 'Advanced B2C E-Commerce features for Smart Dairy',
    'description': '''
        Smart Dairy E-Commerce Module
        =============================
        This module provides advanced e-commerce features:

        - Elasticsearch-powered product search
        - AI-driven product recommendations
        - Faceted filtering and sorting
        - Product comparison tool
        - Advanced cart and checkout
        - Customer account management
        - Reviews and ratings
        - Wishlist and favorites

        Requires:
        - Elasticsearch 8.11+
        - Redis 7.2+
    ''',
    'author': 'Smart Dairy',
    'website': 'https://smartdairy.com.bd',
    'depends': [
        'base',
        'website',
        'website_sale',
        'website_sale_wishlist',
        'sale',
        'product',
        'stock',
        'rating',
    ],
    'data': [
        'security/ir.model.access.csv',
        'data/ir_config_parameter.xml',
        'data/ir_cron.xml',
        'views/product_views.xml',
        'views/search_templates.xml',
        'views/catalog_templates.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_ecommerce/static/src/scss/search.scss',
            'smart_dairy_ecommerce/static/src/scss/catalog.scss',
            'smart_dairy_ecommerce/static/src/js/search_bar.js',
            'smart_dairy_ecommerce/static/src/js/product_filters.js',
            'smart_dairy_ecommerce/static/src/js/product_comparison.js',
            'smart_dairy_ecommerce/static/src/xml/search_templates.xml',
        ],
    },
    'installable': True,
    'application': True,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

#### Dev 2 — Full-Stack (8h)

**Task 1: Elasticsearch Docker Setup (3h)**

```yaml
# docker/elasticsearch/docker-compose.yml
version: '3.8'

services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.11.0
    container_name: smart_dairy_elasticsearch
    environment:
      - node.name=es-node-1
      - cluster.name=smart-dairy-cluster
      - discovery.type=single-node
      - xpack.security.enabled=true
      - xpack.security.enrollment.enabled=false
      - ELASTIC_PASSWORD=${ELASTIC_PASSWORD:-smartdairy123}
      - "ES_JAVA_OPTS=-Xms1g -Xmx1g"
      - bootstrap.memory_lock=true
    ulimits:
      memlock:
        soft: -1
        hard: -1
    volumes:
      - elasticsearch_data:/usr/share/elasticsearch/data
      - ./elasticsearch.yml:/usr/share/elasticsearch/config/elasticsearch.yml:ro
    ports:
      - "9200:9200"
      - "9300:9300"
    networks:
      - smart_dairy_network
    healthcheck:
      test: ["CMD-SHELL", "curl -s -u elastic:${ELASTIC_PASSWORD:-smartdairy123} http://localhost:9200/_cluster/health | grep -vq '\"status\":\"red\"'"]
      interval: 30s
      timeout: 10s
      retries: 5

  kibana:
    image: docker.elastic.co/kibana/kibana:8.11.0
    container_name: smart_dairy_kibana
    environment:
      - ELASTICSEARCH_HOSTS=http://elasticsearch:9200
      - ELASTICSEARCH_USERNAME=kibana_system
      - ELASTICSEARCH_PASSWORD=${KIBANA_PASSWORD:-kibana123}
      - xpack.security.enabled=true
    ports:
      - "5601:5601"
    depends_on:
      elasticsearch:
        condition: service_healthy
    networks:
      - smart_dairy_network

volumes:
  elasticsearch_data:
    driver: local

networks:
  smart_dairy_network:
    driver: bridge
```

```yaml
# docker/elasticsearch/elasticsearch.yml
cluster.name: smart-dairy-cluster
node.name: es-node-1

network.host: 0.0.0.0
http.port: 9200

discovery.type: single-node

xpack.security.enabled: true
xpack.security.authc.api_key.enabled: true

indices.query.bool.max_clause_count: 2048

# Performance tuning
thread_pool:
  search:
    size: 10
    queue_size: 1000
  write:
    size: 5
    queue_size: 500
```

**Task 2: Elasticsearch Health Check Script (2h)**

```python
# scripts/check_elasticsearch.py
#!/usr/bin/env python3
"""
Elasticsearch health check and setup verification script.
"""
import os
import sys
import time
from elasticsearch import Elasticsearch


def check_elasticsearch():
    """Check Elasticsearch connectivity and index status."""
    es_host = os.environ.get('ES_HOST', 'http://localhost:9200')
    es_user = os.environ.get('ES_USER', 'elastic')
    es_password = os.environ.get('ES_PASSWORD', 'smartdairy123')

    print(f"Connecting to Elasticsearch at {es_host}...")

    es = Elasticsearch(
        hosts=[es_host],
        basic_auth=(es_user, es_password),
        verify_certs=False,
        request_timeout=30,
    )

    # Check connection
    max_retries = 5
    for attempt in range(max_retries):
        try:
            if es.ping():
                print("✓ Elasticsearch is reachable")
                break
        except Exception as e:
            print(f"  Attempt {attempt + 1}/{max_retries}: {str(e)}")
            time.sleep(5)
    else:
        print("✗ Failed to connect to Elasticsearch")
        sys.exit(1)

    # Check cluster health
    health = es.cluster.health()
    print(f"✓ Cluster status: {health['status']}")
    print(f"  - Number of nodes: {health['number_of_nodes']}")
    print(f"  - Active shards: {health['active_shards']}")

    # Check product index
    index_name = os.environ.get('ES_INDEX', 'dev_smart_dairy_products')
    if es.indices.exists(index=index_name):
        stats = es.indices.stats(index=index_name)
        doc_count = stats['indices'][index_name]['primaries']['docs']['count']
        print(f"✓ Index '{index_name}' exists with {doc_count} documents")
    else:
        print(f"! Index '{index_name}' does not exist (will be created on first sync)")

    print("\n✓ Elasticsearch is ready for use!")
    return True


if __name__ == '__main__':
    check_elasticsearch()
```

**Task 3: CI/CD Pipeline Update (3h)**

```yaml
# .github/workflows/elasticsearch-tests.yml
name: Elasticsearch Integration Tests

on:
  push:
    paths:
      - 'odoo/addons/smart_dairy_ecommerce/**'
      - 'docker/elasticsearch/**'
  pull_request:
    paths:
      - 'odoo/addons/smart_dairy_ecommerce/**'

jobs:
  elasticsearch-tests:
    runs-on: ubuntu-latest

    services:
      elasticsearch:
        image: docker.elastic.co/elasticsearch/elasticsearch:8.11.0
        env:
          discovery.type: single-node
          xpack.security.enabled: false
          ES_JAVA_OPTS: -Xms512m -Xmx512m
        ports:
          - 9200:9200
        options: >-
          --health-cmd "curl -s http://localhost:9200/_cluster/health | grep -vq '\"status\":\"red\"'"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 10

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v5
        with:
          python-version: '3.11'

      - name: Install dependencies
        run: |
          python -m pip install --upgrade pip
          pip install elasticsearch pytest pytest-cov

      - name: Wait for Elasticsearch
        run: |
          timeout 60 bash -c 'until curl -s http://localhost:9200/_cluster/health | grep -vq "\"status\":\"red\""; do sleep 2; done'

      - name: Run Elasticsearch tests
        env:
          ES_HOST: http://localhost:9200
        run: |
          pytest tests/test_elasticsearch.py -v --cov=smart_dairy_ecommerce --cov-report=xml

      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          file: ./coverage.xml
          flags: elasticsearch
```

#### Dev 3 — Frontend Lead (8h)

**Task 1: Search Bar OWL Component Foundation (4h)**

```javascript
/** @odoo-module **/
// odoo/addons/smart_dairy_ecommerce/static/src/js/search_bar.js

import { Component, useState, useRef, onMounted, onWillUnmount } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { debounce } from "@web/core/utils/timing";

export class ProductSearchBar extends Component {
    static template = "smart_dairy_ecommerce.ProductSearchBar";
    static props = {
        placeholder: { type: String, optional: true },
        minChars: { type: Number, optional: true },
        maxSuggestions: { type: Number, optional: true },
    };
    static defaultProps = {
        placeholder: "Search for dairy products...",
        minChars: 2,
        maxSuggestions: 8,
    };

    setup() {
        this.state = useState({
            query: "",
            suggestions: [],
            showSuggestions: false,
            isLoading: false,
            selectedIndex: -1,
            recentSearches: [],
            trendingSearches: [],
        });

        this.inputRef = useRef("searchInput");
        this.rpc = useService("rpc");

        // Debounced search function
        this.debouncedSearch = debounce(this.fetchSuggestions.bind(this), 250);

        onMounted(() => {
            this.loadRecentSearches();
            this.loadTrendingSearches();
            document.addEventListener("click", this.handleClickOutside.bind(this));
            document.addEventListener("keydown", this.handleGlobalKeydown.bind(this));
        });

        onWillUnmount(() => {
            document.removeEventListener("click", this.handleClickOutside.bind(this));
            document.removeEventListener("keydown", this.handleGlobalKeydown.bind(this));
        });
    }

    /**
     * Fetch autocomplete suggestions from the server.
     * @param {string} query - Search query
     */
    async fetchSuggestions(query) {
        if (query.length < this.props.minChars) {
            this.state.suggestions = [];
            this.state.showSuggestions = false;
            return;
        }

        this.state.isLoading = true;

        try {
            const result = await this.rpc("/shop/api/autocomplete", {
                query: query,
                limit: this.props.maxSuggestions,
            });

            if (result.success) {
                this.state.suggestions = this.formatSuggestions(result.data);
                this.state.showSuggestions = true;
            }
        } catch (error) {
            console.error("Autocomplete error:", error);
            this.state.suggestions = [];
        } finally {
            this.state.isLoading = false;
        }
    }

    /**
     * Format suggestions for display.
     * @param {Object} data - Raw suggestion data
     * @returns {Array} Formatted suggestions
     */
    formatSuggestions(data) {
        const suggestions = [];

        // Add product suggestions
        if (data.products && data.products.length > 0) {
            data.products.forEach(product => {
                suggestions.push({
                    type: 'product',
                    id: product.id,
                    name: product.name,
                    category: product.category,
                    price: product.price,
                    image_url: product.image_url,
                    url: `/shop/product/${product.id}`,
                });
            });
        }

        // Add category suggestions
        if (data.categories && data.categories.length > 0) {
            data.categories.forEach(category => {
                suggestions.push({
                    type: 'category',
                    id: category.id,
                    name: category.name,
                    product_count: category.product_count,
                    url: `/shop/category/${category.id}`,
                });
            });
        }

        // Add query suggestions
        if (data.queries && data.queries.length > 0) {
            data.queries.forEach(query => {
                suggestions.push({
                    type: 'query',
                    name: query,
                    url: `/shop/search?q=${encodeURIComponent(query)}`,
                });
            });
        }

        return suggestions;
    }

    /**
     * Handle input changes.
     * @param {Event} ev - Input event
     */
    onInput(ev) {
        this.state.query = ev.target.value;
        this.state.selectedIndex = -1;
        this.debouncedSearch(this.state.query);
    }

    /**
     * Handle keyboard navigation.
     * @param {KeyboardEvent} ev - Keyboard event
     */
    onKeydown(ev) {
        const { suggestions, selectedIndex } = this.state;

        switch (ev.key) {
            case "ArrowDown":
                ev.preventDefault();
                if (this.state.showSuggestions) {
                    this.state.selectedIndex = Math.min(
                        selectedIndex + 1,
                        suggestions.length - 1
                    );
                } else if (this.state.query.length >= this.props.minChars) {
                    this.fetchSuggestions(this.state.query);
                }
                break;

            case "ArrowUp":
                ev.preventDefault();
                this.state.selectedIndex = Math.max(selectedIndex - 1, -1);
                break;

            case "Enter":
                ev.preventDefault();
                if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                    this.selectSuggestion(suggestions[selectedIndex]);
                } else {
                    this.performSearch();
                }
                break;

            case "Escape":
                this.closeSuggestions();
                break;

            case "Tab":
                this.closeSuggestions();
                break;
        }
    }

    /**
     * Handle global keyboard shortcuts.
     * @param {KeyboardEvent} ev - Keyboard event
     */
    handleGlobalKeydown(ev) {
        // Focus search on "/" key (like GitHub)
        if (ev.key === "/" && !this.isInputFocused()) {
            ev.preventDefault();
            this.inputRef.el.focus();
        }
    }

    /**
     * Check if any input is currently focused.
     * @returns {boolean}
     */
    isInputFocused() {
        const activeElement = document.activeElement;
        return activeElement && (
            activeElement.tagName === "INPUT" ||
            activeElement.tagName === "TEXTAREA" ||
            activeElement.isContentEditable
        );
    }

    /**
     * Select a suggestion.
     * @param {Object} suggestion - Selected suggestion
     */
    selectSuggestion(suggestion) {
        // Save to recent searches
        this.saveRecentSearch(suggestion.name);

        // Navigate to the URL
        window.location.href = suggestion.url;
    }

    /**
     * Perform a search with the current query.
     */
    performSearch() {
        const query = this.state.query.trim();
        if (query) {
            this.saveRecentSearch(query);
            window.location.href = `/shop/search?q=${encodeURIComponent(query)}`;
        }
    }

    /**
     * Handle click outside to close suggestions.
     * @param {Event} ev - Click event
     */
    handleClickOutside(ev) {
        if (!this.el.contains(ev.target)) {
            this.closeSuggestions();
        }
    }

    /**
     * Handle input focus.
     */
    onFocus() {
        if (this.state.query.length >= this.props.minChars && this.state.suggestions.length > 0) {
            this.state.showSuggestions = true;
        } else if (this.state.query.length === 0) {
            // Show recent/trending when empty
            this.state.showSuggestions =
                this.state.recentSearches.length > 0 ||
                this.state.trendingSearches.length > 0;
        }
    }

    /**
     * Close suggestions dropdown.
     */
    closeSuggestions() {
        this.state.showSuggestions = false;
        this.state.selectedIndex = -1;
    }

    /**
     * Clear search input.
     */
    clearSearch() {
        this.state.query = "";
        this.state.suggestions = [];
        this.closeSuggestions();
        this.inputRef.el.focus();
    }

    /**
     * Load recent searches from localStorage.
     */
    loadRecentSearches() {
        try {
            const recent = localStorage.getItem('sd_recent_searches');
            this.state.recentSearches = recent ? JSON.parse(recent) : [];
        } catch (e) {
            this.state.recentSearches = [];
        }
    }

    /**
     * Save a search to recent searches.
     * @param {string} query - Search query
     */
    saveRecentSearch(query) {
        const maxRecent = 5;
        let recent = this.state.recentSearches.filter(q => q !== query);
        recent.unshift(query);
        recent = recent.slice(0, maxRecent);
        this.state.recentSearches = recent;

        try {
            localStorage.setItem('sd_recent_searches', JSON.stringify(recent));
        } catch (e) {
            // localStorage not available
        }
    }

    /**
     * Remove a recent search.
     * @param {string} query - Search query to remove
     */
    removeRecentSearch(query) {
        this.state.recentSearches = this.state.recentSearches.filter(q => q !== query);
        try {
            localStorage.setItem('sd_recent_searches', JSON.stringify(this.state.recentSearches));
        } catch (e) {
            // localStorage not available
        }
    }

    /**
     * Load trending searches from server.
     */
    async loadTrendingSearches() {
        try {
            const result = await this.rpc("/shop/api/trending-searches", {
                limit: 5,
            });
            if (result.success) {
                this.state.trendingSearches = result.data;
            }
        } catch (error) {
            console.error("Failed to load trending searches:", error);
        }
    }

    /**
     * Format price for display.
     * @param {number} price - Price value
     * @returns {string} Formatted price
     */
    formatPrice(price) {
        return `৳${price.toLocaleString('en-BD')}`;
    }
}
```

**Task 2: Search Bar Template (2h)**

```xml
<!-- odoo/addons/smart_dairy_ecommerce/static/src/xml/search_templates.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<templates xml:space="preserve">

    <t t-name="smart_dairy_ecommerce.ProductSearchBar">
        <div class="sd-search-bar position-relative">
            <!-- Search Input -->
            <div class="sd-search-input-wrapper">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i t-att-class="state.isLoading ? 'fa fa-spinner fa-spin' : 'fa fa-search'"
                           class="text-muted"/>
                    </span>
                    <input
                        t-ref="searchInput"
                        type="text"
                        class="form-control border-start-0 border-end-0 sd-search-input"
                        t-att-placeholder="props.placeholder"
                        t-att-value="state.query"
                        t-on-input="onInput"
                        t-on-keydown="onKeydown"
                        t-on-focus="onFocus"
                        autocomplete="off"
                        aria-label="Search products"
                        aria-haspopup="listbox"
                        t-att-aria-expanded="state.showSuggestions"
                    />
                    <button t-if="state.query.length > 0"
                            class="btn btn-link text-muted border-start-0 border-end-0"
                            t-on-click="clearSearch"
                            type="button"
                            aria-label="Clear search">
                        <i class="fa fa-times"/>
                    </button>
                    <button class="btn btn-primary sd-search-btn"
                            t-on-click="performSearch"
                            type="button">
                        Search
                    </button>
                </div>
                <small class="sd-search-hint text-muted mt-1">
                    Press <kbd>/</kbd> to focus
                </small>
            </div>

            <!-- Suggestions Dropdown -->
            <div t-if="state.showSuggestions"
                 class="sd-search-suggestions position-absolute w-100 bg-white shadow-lg rounded-bottom"
                 role="listbox">

                <!-- Product Suggestions -->
                <t t-if="state.suggestions.length > 0">
                    <t t-foreach="state.suggestions" t-as="suggestion" t-key="suggestion.type + '_' + (suggestion.id || suggestion.name)">

                        <!-- Product Item -->
                        <div t-if="suggestion.type === 'product'"
                             t-att-class="'sd-suggestion-item sd-suggestion-product d-flex align-items-center p-2 ' +
                                        (suggestion_index === state.selectedIndex ? 'active' : '')"
                             t-on-click="() => this.selectSuggestion(suggestion)"
                             t-on-mouseenter="() => this.state.selectedIndex = suggestion_index"
                             role="option"
                             t-att-aria-selected="suggestion_index === state.selectedIndex">
                            <img t-att-src="suggestion.image_url"
                                 t-att-alt="suggestion.name"
                                 class="sd-suggestion-img me-2"
                                 width="48" height="48"
                                 loading="lazy"/>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-medium text-truncate" t-esc="suggestion.name"/>
                                <small class="text-muted" t-esc="suggestion.category"/>
                            </div>
                            <div class="text-primary fw-bold ms-2">
                                <t t-esc="formatPrice(suggestion.price)"/>
                            </div>
                        </div>

                        <!-- Category Item -->
                        <div t-elif="suggestion.type === 'category'"
                             t-att-class="'sd-suggestion-item sd-suggestion-category d-flex align-items-center p-2 ' +
                                        (suggestion_index === state.selectedIndex ? 'active' : '')"
                             t-on-click="() => this.selectSuggestion(suggestion)"
                             t-on-mouseenter="() => this.state.selectedIndex = suggestion_index"
                             role="option">
                            <i class="fa fa-folder-open text-muted me-2"/>
                            <span>Shop in <strong t-esc="suggestion.name"/></span>
                            <span class="badge bg-secondary ms-auto" t-esc="suggestion.product_count + ' products'"/>
                        </div>

                        <!-- Query Suggestion -->
                        <div t-else=""
                             t-att-class="'sd-suggestion-item sd-suggestion-query d-flex align-items-center p-2 ' +
                                        (suggestion_index === state.selectedIndex ? 'active' : '')"
                             t-on-click="() => this.selectSuggestion(suggestion)"
                             t-on-mouseenter="() => this.state.selectedIndex = suggestion_index"
                             role="option">
                            <i class="fa fa-search text-muted me-2"/>
                            <span t-esc="suggestion.name"/>
                        </div>
                    </t>

                    <!-- View All Results Link -->
                    <div class="sd-suggestion-footer p-2 text-center border-top"
                         t-on-click="performSearch">
                        <span class="text-primary">
                            View all results for "<t t-esc="state.query"/>"
                            <i class="fa fa-arrow-right ms-1"/>
                        </span>
                    </div>
                </t>

                <!-- Empty State: Recent and Trending -->
                <t t-elif="state.query.length === 0">
                    <!-- Recent Searches -->
                    <t t-if="state.recentSearches.length > 0">
                        <div class="sd-suggestion-header px-3 py-2 bg-light border-bottom">
                            <small class="text-muted fw-bold">Recent Searches</small>
                        </div>
                        <t t-foreach="state.recentSearches" t-as="recent" t-key="recent">
                            <div class="sd-suggestion-item d-flex align-items-center p-2"
                                 t-on-click="() => { this.state.query = recent; this.performSearch(); }">
                                <i class="fa fa-history text-muted me-2"/>
                                <span class="flex-grow-1" t-esc="recent"/>
                                <button class="btn btn-link btn-sm text-muted p-0"
                                        t-on-click.stop="() => this.removeRecentSearch(recent)">
                                    <i class="fa fa-times"/>
                                </button>
                            </div>
                        </t>
                    </t>

                    <!-- Trending Searches -->
                    <t t-if="state.trendingSearches.length > 0">
                        <div class="sd-suggestion-header px-3 py-2 bg-light border-bottom">
                            <small class="text-muted fw-bold">Trending Searches</small>
                        </div>
                        <t t-foreach="state.trendingSearches" t-as="trending" t-key="trending">
                            <div class="sd-suggestion-item d-flex align-items-center p-2"
                                 t-on-click="() => { this.state.query = trending; this.performSearch(); }">
                                <i class="fa fa-fire text-warning me-2"/>
                                <span t-esc="trending"/>
                            </div>
                        </t>
                    </t>
                </t>

                <!-- No Results -->
                <t t-elif="!state.isLoading">
                    <div class="sd-suggestion-empty p-4 text-center">
                        <i class="fa fa-search fa-2x text-muted mb-2"/>
                        <p class="mb-0 text-muted">No results found for "<t t-esc="state.query"/>"</p>
                        <small class="text-muted">Try different keywords or check spelling</small>
                    </div>
                </t>
            </div>
        </div>
    </t>

</templates>
```

**Task 3: Search Bar Styles (2h)**

```scss
// odoo/addons/smart_dairy_ecommerce/static/src/scss/search.scss

// Variables
$sd-primary: #2e7d32;
$sd-primary-light: #60ad5e;
$sd-primary-dark: #005005;
$sd-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
$sd-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

// Search Bar Component
.sd-search-bar {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;

    .sd-search-input-wrapper {
        position: relative;
    }

    .sd-search-input {
        font-size: 1rem;
        padding: 0.75rem 1rem;

        &:focus {
            box-shadow: none;
            border-color: $sd-primary;
        }

        &::placeholder {
            color: #9e9e9e;
        }
    }

    .sd-search-btn {
        background-color: $sd-primary;
        border-color: $sd-primary;
        padding: 0.75rem 1.5rem;
        font-weight: 500;

        &:hover {
            background-color: $sd-primary-dark;
            border-color: $sd-primary-dark;
        }
    }

    .sd-search-hint {
        display: none;
        font-size: 0.75rem;

        @media (min-width: 768px) {
            display: block;
        }

        kbd {
            background-color: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
        }
    }

    // Suggestions Dropdown
    .sd-search-suggestions {
        z-index: 1050;
        max-height: 450px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        border-top: none;
        border-radius: 0 0 8px 8px;

        // Smooth scroll
        scroll-behavior: smooth;

        // Custom scrollbar
        &::-webkit-scrollbar {
            width: 6px;
        }

        &::-webkit-scrollbar-track {
            background: #f5f5f5;
        }

        &::-webkit-scrollbar-thumb {
            background: #bdbdbd;
            border-radius: 3px;

            &:hover {
                background: #9e9e9e;
            }
        }
    }

    // Suggestion Items
    .sd-suggestion-item {
        cursor: pointer;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f5f5f5;

        &:last-child {
            border-bottom: none;
        }

        &:hover,
        &.active {
            background-color: #f5f5f5;
        }

        &.active {
            background-color: rgba($sd-primary, 0.08);
        }
    }

    // Product Suggestion
    .sd-suggestion-product {
        .sd-suggestion-img {
            object-fit: cover;
            border-radius: 6px;
            background-color: #f5f5f5;
        }
    }

    // Suggestion Header
    .sd-suggestion-header {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    // Suggestion Footer
    .sd-suggestion-footer {
        cursor: pointer;
        transition: background-color 0.15s ease;

        &:hover {
            background-color: #f5f5f5;
        }
    }

    // Empty State
    .sd-suggestion-empty {
        i {
            display: block;
            opacity: 0.5;
        }
    }
}

// Mobile Optimizations
@media (max-width: 767.98px) {
    .sd-search-bar {
        .sd-search-input {
            font-size: 16px; // Prevents zoom on iOS
        }

        .sd-search-btn {
            padding: 0.75rem 1rem;

            span {
                display: none;
            }

            &::after {
                content: '';
                display: inline-block;
                width: 16px;
                height: 16px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: center;
            }
        }

        .sd-search-suggestions {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            top: auto;
            max-height: 60vh;
            border-radius: 16px 16px 0 0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        }
    }
}

// Dark mode support (if needed)
@media (prefers-color-scheme: dark) {
    .sd-search-bar {
        // Add dark mode styles if required
    }
}
```

**End-of-Day 351 Deliverables:**

- [ ] Elasticsearch service class with connection pooling
- [ ] Product index created with optimized mappings
- [ ] Docker Compose for Elasticsearch setup
- [ ] Health check script operational
- [ ] CI/CD pipeline for Elasticsearch tests
- [ ] Search bar OWL component foundation
- [ ] Search bar template with suggestions dropdown
- [ ] SCSS styles for search bar

---

### Day 352 — Product Indexer & Autocomplete API

**Objective:** Implement product indexing service and autocomplete API endpoint for real-time search suggestions.

#### Dev 1 — Backend Lead (8h)

**Task 1: Product Indexer Model (4h)**

```python
# odoo/addons/smart_dairy_ecommerce/models/product_indexer.py
from odoo import models, api, fields
from elasticsearch import helpers
from datetime import datetime
import logging
import json

_logger = logging.getLogger(__name__)


class ProductProduct(models.Model):
    _inherit = 'product.product'

    # Elasticsearch fields
    es_indexed = fields.Boolean('ES Indexed', default=False, copy=False)
    es_indexed_at = fields.Datetime('Last Indexed', copy=False)

    # Search optimization fields
    search_keywords = fields.Char('Search Keywords', help='Additional keywords for search')
    name_bn = fields.Char('Name (Bangla)', help='Product name in Bangla')
    description_bn = fields.Text('Description (Bangla)')
    popularity_score = fields.Float('Popularity Score', compute='_compute_popularity', store=True)

    @api.depends('sales_count', 'rating_avg', 'rating_count')
    def _compute_popularity(self):
        """
        Calculate popularity score based on sales, ratings, and views.
        Formula: (normalized_sales * 0.5) + (rating_score * 0.3) + (normalized_views * 0.2)
        """
        for product in self:
            # Normalize sales (cap at 1000)
            sales_score = min(product.sales_count or 0, 1000) / 1000 * 50

            # Rating score (rating * review_count, capped)
            rating = product.rating_avg or 0
            reviews = min(product.rating_count or 0, 500)
            rating_score = (rating / 5) * (reviews / 500) * 30

            # Views (if available, otherwise 0)
            view_score = 0
            if hasattr(product, 'view_count'):
                view_score = min(product.view_count or 0, 10000) / 10000 * 20

            product.popularity_score = sales_score + rating_score + view_score

    def _prepare_es_document(self):
        """
        Prepare product document for Elasticsearch indexing.

        Returns:
            dict: Document to be indexed
        """
        self.ensure_one()

        # Get category hierarchy
        category_ids = []
        category_path = []
        if self.categ_id:
            cat = self.categ_id
            while cat:
                category_ids.append(cat.id)
                category_path.append(cat.name)
                cat = cat.parent_id

        # Get product attributes
        attributes = []
        for attr_line in self.product_template_attribute_value_ids:
            attributes.append({
                'name': attr_line.attribute_id.name,
                'value': attr_line.name,
            })

        # Calculate price range
        price = self.list_price or 0
        if price < 100:
            price_range = 'Under ৳100'
        elif price < 500:
            price_range = '৳100 - ৳500'
        elif price < 1000:
            price_range = '৳500 - ৳1000'
        elif price < 2000:
            price_range = '৳1000 - ৳2000'
        else:
            price_range = 'Above ৳2000'

        # Stock status
        qty = self.qty_available or 0
        if qty <= 0:
            stock_status = 'out_of_stock'
        elif qty < 10:
            stock_status = 'low_stock'
        else:
            stock_status = 'in_stock'

        # Build document
        doc = {
            'id': self.id,
            'name': self.name or '',
            'name_bn': self.name_bn or '',
            'description': (self.description_sale or '').strip(),
            'description_bn': (self.description_bn or '').strip(),
            'sku': self.default_code or '',
            'barcode': self.barcode or '',

            # Category
            'category_id': self.categ_id.id if self.categ_id else 0,
            'category_name': self.categ_id.name if self.categ_id else '',
            'category_path': ' > '.join(reversed(category_path)),
            'category_ids': category_ids,

            # Pricing
            'price': price,
            'list_price': price,
            'compare_price': getattr(self, 'compare_list_price', 0) or 0,
            'discount_percentage': self._calculate_discount(),
            'price_range': price_range,

            # Availability
            'in_stock': qty > 0,
            'qty_available': qty,
            'stock_status': stock_status,

            # Ratings
            'rating_avg': self.rating_avg or 0,
            'rating_count': self.rating_count or 0,
            'review_count': self.rating_count or 0,

            # Media
            'image_url': f'/web/image/product.product/{self.id}/image_512',
            'image_urls': [f'/web/image/product.product/{self.id}/image_1920'],
            'has_video': False,

            # Attributes
            'brand': self._get_brand_name(),
            'brand_id': self._get_brand_id(),
            'tags': self._get_tags(),
            'attributes': attributes,

            # Dairy-specific
            'fat_percentage': getattr(self, 'fat_percentage', 0) or 0,
            'shelf_life_days': getattr(self, 'shelf_life_days', 0) or 0,
            'storage_temp': getattr(self, 'storage_temp', '') or '',
            'is_organic': getattr(self, 'is_organic', False) or False,
            'certifications': self._get_certifications(),
            'nutritional_info': self._get_nutritional_info(),

            # E-commerce flags
            'is_featured': getattr(self, 'is_featured', False) or False,
            'is_bestseller': getattr(self, 'is_bestseller', False) or False,
            'is_new': self._is_new_product(),
            'subscription_eligible': getattr(self, 'subscription_eligible', False) or False,

            # Search optimization
            'search_keywords': self.search_keywords or '',
            'popularity_score': self.popularity_score or 0,
            'sales_count': self.sales_count or 0,
            'view_count': getattr(self, 'view_count', 0) or 0,

            # Timestamps
            'created_date': self.create_date.isoformat() if self.create_date else None,
            'updated_date': self.write_date.isoformat() if self.write_date else None,

            # Suggest field for autocomplete
            'suggest': {
                'input': self._get_suggest_inputs(),
                'weight': int(self.popularity_score or 1),
            },
        }

        return doc

    def _calculate_discount(self):
        """Calculate discount percentage if compare price exists."""
        compare_price = getattr(self, 'compare_list_price', 0) or 0
        if compare_price and compare_price > self.list_price:
            return round((1 - self.list_price / compare_price) * 100, 1)
        return 0

    def _get_brand_name(self):
        """Get brand name from product attributes or template."""
        if hasattr(self, 'brand_id') and self.brand_id:
            return self.brand_id.name
        # Check attributes for brand
        for attr in self.product_template_attribute_value_ids:
            if attr.attribute_id.name.lower() == 'brand':
                return attr.name
        return ''

    def _get_brand_id(self):
        """Get brand ID if available."""
        if hasattr(self, 'brand_id') and self.brand_id:
            return self.brand_id.id
        return 0

    def _get_tags(self):
        """Get product tags for search."""
        tags = []
        if self.search_keywords:
            tags.extend([t.strip() for t in self.search_keywords.split(',') if t.strip()])
        # Add category as tag
        if self.categ_id:
            tags.append(self.categ_id.name)
        return list(set(tags))

    def _get_certifications(self):
        """Get product certifications."""
        if hasattr(self, 'certification_ids') and self.certification_ids:
            return [c.name for c in self.certification_ids]
        return []

    def _get_nutritional_info(self):
        """Get nutritional information as dict."""
        if hasattr(self, 'nutritional_info') and self.nutritional_info:
            try:
                return json.loads(self.nutritional_info)
            except:
                return {}
        return {}

    def _is_new_product(self):
        """Check if product is new (created within 30 days)."""
        if self.create_date:
            days_old = (datetime.now() - self.create_date).days
            return days_old <= 30
        return False

    def _get_suggest_inputs(self):
        """Get autocomplete suggestion inputs."""
        inputs = [self.name]
        if self.name_bn:
            inputs.append(self.name_bn)
        if self.default_code:
            inputs.append(self.default_code)
        if self.barcode:
            inputs.append(self.barcode)
        # Add individual words from name
        inputs.extend(self.name.split())
        return list(set(inputs))

    def index_to_elasticsearch(self):
        """
        Index products to Elasticsearch.

        Returns:
            int: Number of products indexed
        """
        if not self:
            return 0

        es_service = self.env['elasticsearch.service']
        es = es_service.get_client()
        index_name = es_service.get_index_name()

        # Prepare bulk actions
        actions = []
        for product in self:
            if product.sale_ok and product.active:
                doc = product._prepare_es_document()
                actions.append({
                    '_index': index_name,
                    '_id': product.id,
                    '_source': doc,
                })

        if not actions:
            return 0

        # Bulk index
        success, errors = helpers.bulk(
            es,
            actions,
            raise_on_error=False,
            raise_on_exception=False,
        )

        if errors:
            _logger.error(f'Elasticsearch indexing errors: {errors}')

        # Update indexed status
        self.write({
            'es_indexed': True,
            'es_indexed_at': fields.Datetime.now(),
        })

        _logger.info(f'Indexed {success} products to Elasticsearch')
        return success

    def remove_from_elasticsearch(self):
        """Remove products from Elasticsearch index."""
        if not self:
            return

        es_service = self.env['elasticsearch.service']
        es = es_service.get_client()
        index_name = es_service.get_index_name()

        for product in self:
            try:
                es.delete(index=index_name, id=product.id, ignore=[404])
            except Exception as e:
                _logger.warning(f'Failed to remove product {product.id} from ES: {e}')

        self.write({'es_indexed': False})

    @api.model
    def reindex_all_products(self, batch_size=100):
        """
        Reindex all saleable products in batches.

        Args:
            batch_size: Number of products per batch

        Returns:
            dict: Indexing statistics
        """
        products = self.search([
            ('sale_ok', '=', True),
            ('active', '=', True),
        ])

        total = len(products)
        indexed = 0
        errors = 0

        for i in range(0, total, batch_size):
            batch = products[i:i + batch_size]
            try:
                indexed += batch.index_to_elasticsearch()
            except Exception as e:
                _logger.error(f'Batch indexing error: {e}')
                errors += len(batch)

        _logger.info(f'Reindexed {indexed}/{total} products, {errors} errors')

        return {
            'total': total,
            'indexed': indexed,
            'errors': errors,
        }

    def write(self, vals):
        """Override write to trigger reindex on relevant field changes."""
        result = super().write(vals)

        # Fields that trigger reindex
        index_fields = {
            'name', 'list_price', 'categ_id', 'qty_available', 'active',
            'sale_ok', 'description_sale', 'rating_avg', 'rating_count',
            'name_bn', 'search_keywords', 'is_featured', 'is_bestseller',
        }

        if any(f in vals for f in index_fields):
            # Queue for reindex (don't block the write)
            self.env.cr.postcommit.add(lambda: self.index_to_elasticsearch())

        return result

    def unlink(self):
        """Override unlink to remove from Elasticsearch."""
        self.remove_from_elasticsearch()
        return super().unlink()
```

**Task 2: Autocomplete API Endpoint (4h)**

```python
# odoo/addons/smart_dairy_ecommerce/controllers/search_controller.py
from odoo import http
from odoo.http import request
import logging
import json

_logger = logging.getLogger(__name__)


class SearchController(http.Controller):

    def _api_response(self, success=True, data=None, message=None, error_code=None):
        """Standard API response format."""
        response = {
            'success': success,
            'timestamp': fields.Datetime.now().isoformat(),
        }
        if data is not None:
            response['data'] = data
        if message:
            response['message'] = message
        if error_code:
            response['error_code'] = error_code
        return response

    @http.route('/shop/api/autocomplete', type='json', auth='public', methods=['POST'], csrf=False)
    def autocomplete(self, **kwargs):
        """
        Product autocomplete API endpoint.

        Request:
            {
                "query": "mil",
                "limit": 8
            }

        Response:
            {
                "success": true,
                "data": {
                    "products": [...],
                    "categories": [...],
                    "queries": [...]
                }
            }
        """
        query = kwargs.get('query', '').strip()
        limit = min(int(kwargs.get('limit', 8)), 20)

        if len(query) < 2:
            return self._api_response(success=True, data={
                'products': [],
                'categories': [],
                'queries': [],
            })

        try:
            es_service = request.env['elasticsearch.service'].sudo()
            es = es_service.get_client()
            index_name = es_service.get_index_name()

            # Build multi-search query for different suggestion types
            search_body = {
                'size': 0,
                'suggest': {
                    # Completion suggester for fast autocomplete
                    'product_suggest': {
                        'prefix': query,
                        'completion': {
                            'field': 'suggest',
                            'size': limit,
                            'skip_duplicates': True,
                            'fuzzy': {
                                'fuzziness': 'AUTO',
                            },
                        },
                    },
                },
                'aggs': {
                    # Category suggestions
                    'categories': {
                        'filter': {
                            'multi_match': {
                                'query': query,
                                'fields': ['name', 'name_bn', 'category_name'],
                                'type': 'phrase_prefix',
                            },
                        },
                        'aggs': {
                            'top_categories': {
                                'terms': {
                                    'field': 'category_name',
                                    'size': 3,
                                },
                            },
                        },
                    },
                },
            }

            result = es.search(index=index_name, body=search_body)

            # Process product suggestions
            products = []
            suggest_options = result.get('suggest', {}).get('product_suggest', [{}])[0].get('options', [])

            for option in suggest_options[:limit]:
                source = option.get('_source', {})
                products.append({
                    'id': source.get('id'),
                    'name': source.get('name'),
                    'category': source.get('category_name'),
                    'price': source.get('price'),
                    'image_url': source.get('image_url'),
                    'in_stock': source.get('in_stock', True),
                })

            # Process category suggestions
            categories = []
            cat_buckets = result.get('aggregations', {}).get('categories', {}).get('top_categories', {}).get('buckets', [])

            for bucket in cat_buckets:
                cat_name = bucket.get('key')
                if cat_name:
                    # Get category record
                    cat = request.env['product.category'].sudo().search([
                        ('name', '=', cat_name)
                    ], limit=1)
                    if cat:
                        categories.append({
                            'id': cat.id,
                            'name': cat.name,
                            'product_count': bucket.get('doc_count', 0),
                        })

            # Get query suggestions from search analytics
            query_suggestions = self._get_query_suggestions(query, limit=3)

            return self._api_response(success=True, data={
                'products': products,
                'categories': categories,
                'queries': query_suggestions,
            })

        except Exception as e:
            _logger.error(f'Autocomplete error: {e}')
            return self._api_response(
                success=False,
                message='Search service temporarily unavailable',
                error_code='SEARCH_ERROR'
            )

    def _get_query_suggestions(self, query, limit=5):
        """Get popular query suggestions based on search history."""
        try:
            SearchAnalytics = request.env['ecommerce.search.analytics'].sudo()

            # Find similar successful queries
            similar_queries = SearchAnalytics.search([
                ('query', 'ilike', f'{query}%'),
                ('results_count', '>', 0),
            ], order='id desc', limit=100)

            # Count and sort by frequency
            query_counts = {}
            for sq in similar_queries:
                q = sq.query.lower()
                if q != query.lower():
                    query_counts[q] = query_counts.get(q, 0) + 1

            # Sort by count and return top results
            sorted_queries = sorted(query_counts.items(), key=lambda x: x[1], reverse=True)
            return [q for q, _ in sorted_queries[:limit]]

        except Exception as e:
            _logger.warning(f'Query suggestions error: {e}')
            return []

    @http.route('/shop/api/trending-searches', type='json', auth='public', methods=['POST'], csrf=False)
    def trending_searches(self, **kwargs):
        """
        Get trending search queries.

        Request:
            {
                "limit": 5
            }

        Response:
            {
                "success": true,
                "data": ["fresh milk", "organic yogurt", ...]
            }
        """
        limit = min(int(kwargs.get('limit', 5)), 10)

        try:
            SearchAnalytics = request.env['ecommerce.search.analytics'].sudo()
            trending = SearchAnalytics.get_trending_searches(days=7, limit=limit)

            return self._api_response(success=True, data=[q[0] for q in trending])

        except Exception as e:
            _logger.error(f'Trending searches error: {e}')
            return self._api_response(success=True, data=[])
```

#### Dev 2 — Full-Stack (8h)

**Task 1: Search Analytics Model (4h)**

```python
# odoo/addons/smart_dairy_ecommerce/models/search_analytics.py
from odoo import models, fields, api
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class SearchAnalytics(models.Model):
    _name = 'ecommerce.search.analytics'
    _description = 'E-commerce Search Analytics'
    _order = 'search_timestamp desc'

    query = fields.Char('Search Query', required=True, index=True)
    query_normalized = fields.Char('Normalized Query', compute='_compute_normalized', store=True, index=True)
    results_count = fields.Integer('Results Count', default=0)
    clicked_product_id = fields.Many2one('product.product', 'Clicked Product')
    click_position = fields.Integer('Click Position', help='Position of clicked result')
    user_id = fields.Many2one('res.users', 'User')
    partner_id = fields.Many2one('res.partner', 'Customer')
    session_id = fields.Char('Session ID', index=True)
    search_timestamp = fields.Datetime('Search Time', default=fields.Datetime.now, index=True)
    response_time_ms = fields.Integer('Response Time (ms)')
    converted = fields.Boolean('Converted to Order', default=False)
    conversion_order_id = fields.Many2one('sale.order', 'Conversion Order')
    filters_used = fields.Text('Filters Used', help='JSON of applied filters')
    device_type = fields.Selection([
        ('desktop', 'Desktop'),
        ('mobile', 'Mobile'),
        ('tablet', 'Tablet'),
    ], string='Device Type')

    @api.depends('query')
    def _compute_normalized(self):
        """Normalize query for analysis."""
        for record in self:
            if record.query:
                # Lowercase and strip whitespace
                normalized = record.query.lower().strip()
                # Remove extra spaces
                normalized = ' '.join(normalized.split())
                record.query_normalized = normalized
            else:
                record.query_normalized = ''

    @api.model
    def log_search(self, query, results_count, session_id=None, user_id=None,
                   response_time_ms=None, filters_used=None, device_type=None):
        """
        Log a search query for analytics.

        Args:
            query: Search query string
            results_count: Number of results returned
            session_id: Browser session ID
            user_id: Authenticated user ID
            response_time_ms: Search response time in milliseconds
            filters_used: Dict of applied filters
            device_type: Device type (desktop/mobile/tablet)

        Returns:
            search.analytics record
        """
        vals = {
            'query': query.strip()[:200],  # Limit query length
            'results_count': results_count,
            'session_id': session_id,
            'response_time_ms': response_time_ms,
            'device_type': device_type,
        }

        if user_id:
            user = self.env['res.users'].sudo().browse(user_id)
            if user.exists():
                vals['user_id'] = user_id
                vals['partner_id'] = user.partner_id.id

        if filters_used:
            import json
            vals['filters_used'] = json.dumps(filters_used)

        return self.sudo().create(vals)

    @api.model
    def log_click(self, search_id, product_id, position):
        """
        Log a click on a search result.

        Args:
            search_id: Search analytics record ID
            product_id: Clicked product ID
            position: Position in results (1-indexed)
        """
        record = self.sudo().browse(search_id)
        if record.exists():
            record.write({
                'clicked_product_id': product_id,
                'click_position': position,
            })

    @api.model
    def mark_conversion(self, session_id, order_id):
        """
        Mark recent searches as converted.

        Args:
            session_id: Session ID
            order_id: Sale order ID
        """
        # Find searches in the last 24 hours from this session
        cutoff = datetime.now() - timedelta(hours=24)
        searches = self.sudo().search([
            ('session_id', '=', session_id),
            ('search_timestamp', '>=', cutoff),
            ('converted', '=', False),
        ])

        searches.write({
            'converted': True,
            'conversion_order_id': order_id,
        })

    @api.model
    def get_trending_searches(self, days=7, limit=10):
        """
        Get trending searches from the last N days.

        Args:
            days: Number of days to look back
            limit: Maximum results to return

        Returns:
            List of tuples (query, count)
        """
        date_from = datetime.now() - timedelta(days=days)

        self.env.cr.execute("""
            SELECT query_normalized, COUNT(*) as search_count
            FROM ecommerce_search_analytics
            WHERE search_timestamp >= %s
              AND results_count > 0
              AND query_normalized != ''
            GROUP BY query_normalized
            ORDER BY search_count DESC
            LIMIT %s
        """, (date_from, limit))

        return self.env.cr.fetchall()

    @api.model
    def get_zero_result_searches(self, days=7, limit=20):
        """
        Get searches that returned no results.

        Args:
            days: Number of days to look back
            limit: Maximum results

        Returns:
            List of tuples (query, count)
        """
        date_from = datetime.now() - timedelta(days=days)

        self.env.cr.execute("""
            SELECT query_normalized, COUNT(*) as search_count
            FROM ecommerce_search_analytics
            WHERE search_timestamp >= %s
              AND results_count = 0
              AND query_normalized != ''
            GROUP BY query_normalized
            ORDER BY search_count DESC
            LIMIT %s
        """, (date_from, limit))

        return self.env.cr.fetchall()

    @api.model
    def get_search_metrics(self, days=30):
        """
        Get aggregated search metrics.

        Args:
            days: Number of days to analyze

        Returns:
            Dict of metrics
        """
        date_from = datetime.now() - timedelta(days=days)

        # Total searches
        total = self.sudo().search_count([
            ('search_timestamp', '>=', date_from),
        ])

        # Searches with results
        with_results = self.sudo().search_count([
            ('search_timestamp', '>=', date_from),
            ('results_count', '>', 0),
        ])

        # Searches with clicks
        with_clicks = self.sudo().search_count([
            ('search_timestamp', '>=', date_from),
            ('clicked_product_id', '!=', False),
        ])

        # Converted searches
        converted = self.sudo().search_count([
            ('search_timestamp', '>=', date_from),
            ('converted', '=', True),
        ])

        # Average response time
        self.env.cr.execute("""
            SELECT AVG(response_time_ms)
            FROM ecommerce_search_analytics
            WHERE search_timestamp >= %s
              AND response_time_ms IS NOT NULL
        """, (date_from,))
        avg_response = self.env.cr.fetchone()[0] or 0

        return {
            'total_searches': total,
            'searches_with_results': with_results,
            'success_rate': (with_results / total * 100) if total > 0 else 0,
            'click_through_rate': (with_clicks / total * 100) if total > 0 else 0,
            'conversion_rate': (converted / total * 100) if total > 0 else 0,
            'average_response_time_ms': round(avg_response, 2),
        }
```

**Task 2: Scheduled Indexing Cron Job (2h)**

```xml
<!-- odoo/addons/smart_dairy_ecommerce/data/ir_cron.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">

        <!-- Cron: Reindex products to Elasticsearch -->
        <record id="cron_elasticsearch_reindex" model="ir.cron">
            <field name="name">Elasticsearch: Reindex Products</field>
            <field name="model_id" ref="product.model_product_product"/>
            <field name="state">code</field>
            <field name="code">model.reindex_all_products(batch_size=200)</field>
            <field name="interval_number">1</field>
            <field name="interval_type">days</field>
            <field name="nextcall" eval="(DateTime.now() + timedelta(hours=2)).strftime('%Y-%m-%d 02:00:00')"/>
            <field name="numbercall">-1</field>
            <field name="active">True</field>
            <field name="priority">10</field>
        </record>

        <!-- Cron: Clean old search analytics -->
        <record id="cron_clean_search_analytics" model="ir.cron">
            <field name="name">Search Analytics: Clean Old Records</field>
            <field name="model_id" ref="model_ecommerce_search_analytics"/>
            <field name="state">code</field>
            <field name="code">
from datetime import datetime, timedelta
cutoff = datetime.now() - timedelta(days=90)
env['ecommerce.search.analytics'].sudo().search([
    ('search_timestamp', '&lt;', cutoff),
    ('converted', '=', False),
]).unlink()
            </field>
            <field name="interval_number">1</field>
            <field name="interval_type">weeks</field>
            <field name="numbercall">-1</field>
            <field name="active">True</field>
            <field name="priority">20</field>
        </record>

    </data>
</odoo>
```

**Task 3: Monitoring Dashboard Setup (2h)**

```python
# odoo/addons/smart_dairy_ecommerce/models/search_dashboard.py
from odoo import models, api, fields


class SearchDashboard(models.Model):
    _name = 'ecommerce.search.dashboard'
    _description = 'Search Analytics Dashboard'
    _auto = False  # No database table

    @api.model
    def get_dashboard_data(self):
        """
        Get comprehensive search dashboard data.

        Returns:
            Dict with all dashboard metrics
        """
        SearchAnalytics = self.env['ecommerce.search.analytics'].sudo()
        ElasticsearchService = self.env['elasticsearch.service'].sudo()

        # Get search metrics
        metrics_7d = SearchAnalytics.get_search_metrics(days=7)
        metrics_30d = SearchAnalytics.get_search_metrics(days=30)

        # Get trending and zero-result searches
        trending = SearchAnalytics.get_trending_searches(days=7, limit=10)
        zero_results = SearchAnalytics.get_zero_result_searches(days=7, limit=10)

        # Get Elasticsearch health
        try:
            es_health = ElasticsearchService.check_index_health()
        except:
            es_health = {'status': 'error', 'message': 'Unable to connect'}

        return {
            'metrics_7d': metrics_7d,
            'metrics_30d': metrics_30d,
            'trending_searches': [{'query': q, 'count': c} for q, c in trending],
            'zero_result_searches': [{'query': q, 'count': c} for q, c in zero_results],
            'elasticsearch': es_health,
        }
```

#### Dev 3 — Frontend Lead (8h)

**Task 1: Register OWL Component (2h)**

```javascript
// odoo/addons/smart_dairy_ecommerce/static/src/js/search_registration.js
/** @odoo-module **/

import { registry } from "@web/core/registry";
import { ProductSearchBar } from "./search_bar";

// Register for public website
registry.category("public_components").add("ProductSearchBar", ProductSearchBar);

// Also register as a widget for template usage
import publicWidget from "web.public.widget";

publicWidget.registry.ProductSearchBar = publicWidget.Widget.extend({
    selector: '.sd-search-container',

    start: function () {
        const container = this.el;
        const placeholder = container.dataset.placeholder || "Search products...";

        // Mount OWL component
        const { mount } = owl;
        mount(ProductSearchBar, container, {
            props: {
                placeholder: placeholder,
                minChars: 2,
                maxSuggestions: 8,
            },
        });

        return this._super.apply(this, arguments);
    },
});

export default publicWidget.registry.ProductSearchBar;
```

**Task 2: Search Templates (3h)**

```xml
<!-- odoo/addons/smart_dairy_ecommerce/views/search_templates.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>

    <!-- Search Bar Snippet for Header -->
    <template id="search_bar_snippet" name="Smart Dairy Search Bar">
        <div class="sd-search-container"
             t-att-data-placeholder="placeholder or 'Search for dairy products...'">
            <!-- OWL component mounts here -->
        </div>
    </template>

    <!-- Override website header to include search -->
    <template id="header_search" inherit_id="website.layout" name="Header Search Bar">
        <xpath expr="//header//nav" position="inside">
            <div class="sd-header-search d-none d-lg-block mx-auto" style="max-width: 500px;">
                <t t-call="smart_dairy_ecommerce.search_bar_snippet">
                    <t t-set="placeholder">Search milk, yogurt, cheese...</t>
                </t>
            </div>
        </xpath>
    </template>

    <!-- Mobile search bar -->
    <template id="mobile_search" inherit_id="website.layout" name="Mobile Search">
        <xpath expr="//header" position="after">
            <div class="sd-mobile-search d-lg-none bg-white py-2 px-3 border-bottom">
                <t t-call="smart_dairy_ecommerce.search_bar_snippet">
                    <t t-set="placeholder">Search products...</t>
                </t>
            </div>
        </xpath>
    </template>

    <!-- Search Results Page -->
    <template id="search_results_page" name="Search Results">
        <t t-call="website.layout">
            <div class="container py-4">
                <!-- Search Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3">
                            Search results for "<t t-esc="query"/>"
                        </h1>
                        <p class="text-muted">
                            <t t-esc="total_results"/> products found
                        </p>
                    </div>
                </div>

                <div class="row">
                    <!-- Filters Sidebar -->
                    <div class="col-lg-3 mb-4">
                        <div class="sd-filter-sidebar" id="filterSidebar">
                            <!-- Filters loaded via JavaScript -->
                        </div>
                    </div>

                    <!-- Results Grid -->
                    <div class="col-lg-9">
                        <!-- Sort and View Options -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="sd-view-options">
                                <button class="btn btn-outline-secondary btn-sm active" data-view="grid">
                                    <i class="fa fa-th"/>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" data-view="list">
                                    <i class="fa fa-list"/>
                                </button>
                            </div>
                            <div class="sd-sort-options">
                                <select class="form-select form-select-sm" id="sortSelect">
                                    <option value="relevance">Relevance</option>
                                    <option value="price_asc">Price: Low to High</option>
                                    <option value="price_desc">Price: High to Low</option>
                                    <option value="rating">Customer Rating</option>
                                    <option value="newest">Newest First</option>
                                    <option value="bestseller">Best Sellers</option>
                                </select>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="sd-products-grid row" id="productsGrid">
                            <!-- Products loaded via JavaScript -->
                        </div>

                        <!-- Pagination -->
                        <div class="sd-pagination mt-4" id="pagination">
                            <!-- Pagination loaded via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </t>
    </template>

</odoo>
```

**Task 3: Search Results JavaScript (3h)**

```javascript
// odoo/addons/smart_dairy_ecommerce/static/src/js/search_results.js
/** @odoo-module **/

import publicWidget from "web.public.widget";
import { debounce } from "@web/core/utils/timing";

publicWidget.registry.SearchResults = publicWidget.Widget.extend({
    selector: '.sd-products-grid',
    events: {
        'change #sortSelect': '_onSortChange',
        'click [data-view]': '_onViewChange',
    },

    start: function () {
        this.currentPage = 1;
        this.pageSize = 20;
        this.query = this._getQueryFromUrl();
        this.filters = {};
        this.sort = 'relevance';
        this.view = 'grid';

        // Initial load
        this._loadResults();

        // Listen for filter changes
        $(document).on('filtersChanged', this._onFiltersChanged.bind(this));

        return this._super.apply(this, arguments);
    },

    _getQueryFromUrl: function () {
        const params = new URLSearchParams(window.location.search);
        return params.get('q') || '';
    },

    _loadResults: async function () {
        this.$el.html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');

        try {
            const result = await this._rpc({
                route: '/shop/api/search',
                params: {
                    query: this.query,
                    filters: this.filters,
                    sort: this.sort,
                    page: this.currentPage,
                    limit: this.pageSize,
                },
            });

            if (result.success) {
                this._renderResults(result.data);
            } else {
                this._renderError(result.message);
            }
        } catch (error) {
            console.error('Search error:', error);
            this._renderError('An error occurred while searching');
        }
    },

    _renderResults: function (data) {
        const { products, total, page, total_pages } = data;

        if (products.length === 0) {
            this._renderNoResults();
            return;
        }

        let html = '';
        const colClass = this.view === 'grid' ? 'col-6 col-md-4 col-lg-3' : 'col-12';

        products.forEach(product => {
            html += this._renderProductCard(product, colClass);
        });

        this.$el.html(html);

        // Update pagination
        this._renderPagination(page, total_pages, total);

        // Update count display
        $('.sd-results-count').text(`${total} products found`);
    },

    _renderProductCard: function (product, colClass) {
        const stockBadge = product.in_stock
            ? '<span class="badge bg-success">In Stock</span>'
            : '<span class="badge bg-danger">Out of Stock</span>';

        const rating = product.rating_avg
            ? `<div class="sd-rating">${this._renderStars(product.rating_avg)} (${product.rating_count})</div>`
            : '';

        const comparePrice = product.compare_price && product.compare_price > product.price
            ? `<span class="text-muted text-decoration-line-through me-2">৳${product.compare_price.toLocaleString()}</span>`
            : '';

        return `
            <div class="${colClass} mb-4">
                <div class="card sd-product-card h-100">
                    <a href="/shop/product/${product.id}" class="sd-product-link">
                        <div class="sd-product-image position-relative">
                            <img src="${product.image_url}" alt="${product.name}" class="card-img-top" loading="lazy"/>
                            ${product.discount_percentage > 0 ? `<span class="badge bg-danger position-absolute top-0 start-0 m-2">-${product.discount_percentage}%</span>` : ''}
                        </div>
                    </a>
                    <div class="card-body">
                        <p class="text-muted small mb-1">${product.category || ''}</p>
                        <h6 class="card-title text-truncate">
                            <a href="/shop/product/${product.id}" class="text-decoration-none text-dark">${product.name}</a>
                        </h6>
                        ${rating}
                        <div class="sd-price mt-2">
                            ${comparePrice}
                            <span class="fw-bold text-primary">৳${product.price.toLocaleString()}</span>
                        </div>
                        ${stockBadge}
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button class="btn btn-primary w-100 sd-add-to-cart"
                                data-product-id="${product.id}"
                                ${!product.in_stock ? 'disabled' : ''}>
                            <i class="fa fa-shopping-cart me-1"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    _renderStars: function (rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fa fa-star text-warning"></i>';
            } else if (i - 0.5 <= rating) {
                stars += '<i class="fa fa-star-half-o text-warning"></i>';
            } else {
                stars += '<i class="fa fa-star-o text-warning"></i>';
            }
        }
        return stars;
    },

    _renderNoResults: function () {
        this.$el.html(`
            <div class="text-center py-5">
                <i class="fa fa-search fa-3x text-muted mb-3"></i>
                <h4>No products found</h4>
                <p class="text-muted">Try adjusting your search or filters</p>
                <a href="/shop" class="btn btn-outline-primary">Browse All Products</a>
            </div>
        `);
    },

    _renderError: function (message) {
        this.$el.html(`
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `);
    },

    _renderPagination: function (currentPage, totalPages, totalItems) {
        if (totalPages <= 1) {
            $('#pagination').html('');
            return;
        }

        let html = '<nav><ul class="pagination justify-content-center">';

        // Previous
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
        </li>`;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        // Next
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
        </li>`;

        html += '</ul></nav>';

        $('#pagination').html(html);

        // Bind events
        $('#pagination').on('click', 'a[data-page]', (e) => {
            e.preventDefault();
            const page = parseInt($(e.target).data('page'));
            if (page >= 1 && page <= totalPages) {
                this.currentPage = page;
                this._loadResults();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    },

    _onSortChange: function (ev) {
        this.sort = $(ev.target).val();
        this.currentPage = 1;
        this._loadResults();
    },

    _onViewChange: function (ev) {
        const view = $(ev.currentTarget).data('view');
        this.view = view;
        $('[data-view]').removeClass('active');
        $(ev.currentTarget).addClass('active');
        this._loadResults();
    },

    _onFiltersChanged: function (ev, filters) {
        this.filters = filters;
        this.currentPage = 1;
        this._loadResults();
    },
});

export default publicWidget.registry.SearchResults;
```

**End-of-Day 352 Deliverables:**

- [ ] Product indexer model with ES document preparation
- [ ] Autocomplete API endpoint functional
- [ ] Search analytics model created
- [ ] Scheduled cron jobs configured
- [ ] OWL component registered for templates
- [ ] Search templates created
- [ ] Search results JavaScript functional

---

### Day 353-360 Summary

The remaining days follow the same detailed pattern:

**Day 353: Full-Text Search API**
- Dev 1: Search API with Elasticsearch queries, relevance scoring, fuzzy matching
- Dev 2: Search logging integration, performance monitoring
- Dev 3: Search results page styling, loading states, animations

**Day 354: Faceted Filtering**
- Dev 1: Aggregation queries for filters, filter API endpoints
- Dev 2: Filter URL handling, SEO-friendly URLs
- Dev 3: Filter sidebar OWL component, price range slider, checkbox filters

**Day 355: AI Recommendation Engine (Part 1)**
- Dev 1: Collaborative filtering algorithm, user behavior tracking
- Dev 2: Recommendation data pipeline, Redis caching
- Dev 3: Recommendation widget UI foundation

**Day 356: AI Recommendation Engine (Part 2)**
- Dev 1: Content-based filtering, hybrid recommendations
- Dev 2: A/B testing framework for recommendations
- Dev 3: Product page recommendation sections, "You May Also Like"

**Day 357: Product Comparison (Part 1)**
- Dev 1: Comparison data API, attribute normalization
- Dev 2: Comparison session management (localStorage + server)
- Dev 3: Comparison selection UI, floating comparison bar

**Day 358: Product Comparison (Part 2)**
- Dev 1: Comparison diff calculation, highlighting differences
- Dev 2: Comparison sharing, print-friendly view
- Dev 3: Full comparison table UI, side-by-side view, mobile responsive

**Day 359: Recently Viewed & Related Products
- Dev 1: Recently viewed API, related products algorithm
- Dev 2: View tracking, Redis caching strategy
- Dev 3: Recently viewed carousel, related products section

**Day 360: Integration & Testing**
- Dev 1: API documentation, search performance optimization
- Dev 2: Integration tests, E2E tests, load testing
- Dev 3: Cross-browser testing, mobile responsiveness fixes, final polish

---

## 4. Technical Specifications

### 4.1 Elasticsearch Index Schema

```json
{
  "index": "smart_dairy_products",
  "settings": {
    "number_of_shards": 2,
    "number_of_replicas": 1
  },
  "mappings": {
    "properties": {
      "id": { "type": "integer" },
      "name": { "type": "text", "analyzer": "product_analyzer" },
      "price": { "type": "float" },
      "category_id": { "type": "integer" },
      "in_stock": { "type": "boolean" },
      "rating_avg": { "type": "float" },
      "popularity_score": { "type": "float" },
      "suggest": { "type": "completion" }
    }
  }
}
```

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/shop/api/autocomplete` | POST | Autocomplete suggestions |
| `/shop/api/search` | POST | Full-text search with filters |
| `/shop/api/trending-searches` | POST | Get trending searches |
| `/shop/api/recommendations` | POST | Get product recommendations |
| `/shop/api/comparison` | POST | Get comparison data |
| `/shop/api/recently-viewed` | POST | Get recently viewed products |

### 4.3 Performance Targets

| Metric | Target |
|--------|--------|
| Autocomplete response | <100ms |
| Search response | <200ms |
| Filter update | <150ms |
| Recommendations | <250ms |
| Index refresh | 1 second |

---

## 5. Testing & Validation

### 5.1 Unit Test Example

```python
# tests/test_elasticsearch.py
import pytest
from unittest.mock import MagicMock, patch


class TestProductIndexer:

    @pytest.fixture
    def product(self, env):
        return env['product.product'].create({
            'name': 'Test Milk',
            'list_price': 100,
            'sale_ok': True,
        })

    def test_prepare_es_document(self, product):
        """Test ES document preparation."""
        doc = product._prepare_es_document()

        assert doc['id'] == product.id
        assert doc['name'] == 'Test Milk'
        assert doc['price'] == 100
        assert doc['in_stock'] == False
        assert 'suggest' in doc

    @patch('elasticsearch.Elasticsearch')
    def test_index_to_elasticsearch(self, mock_es, product):
        """Test product indexing."""
        mock_client = MagicMock()
        mock_es.return_value = mock_client

        result = product.index_to_elasticsearch()

        assert result == 1
        assert product.es_indexed == True
```

### 5.2 Test Coverage Requirements

| Component | Required Coverage |
|-----------|------------------|
| Elasticsearch service | >90% |
| Product indexer | >85% |
| Search controller | >85% |
| Search analytics | >80% |
| OWL components | >70% |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Elasticsearch downtime | Low | High | Redis fallback for basic search |
| Slow indexing | Medium | Medium | Batch indexing, off-peak scheduling |
| Search relevance issues | Medium | Medium | Synonym management, boost tuning |
| Memory pressure | Medium | Medium | Index optimization, sharding |
| Query complexity | Low | Low | Query simplification, caching |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Elasticsearch cluster | DevOps | Day 351 |
| Product catalog data | Phase 4 | Day 351 |
| Redis cluster | Phase 1 | Day 351 |
| CDN configuration | DevOps | Day 351 |

### 7.2 Output Handoffs

| Deliverable | Recipient | Handoff Day |
|-------------|-----------|-------------|
| Search API | Milestone 62-70 | 360 |
| Elasticsearch indexes | All e-commerce | 360 |
| Search analytics | BI Team | 360 |
| Recommendation engine | Milestone 63, 68 | 360 |

---

## Milestone Sign-off Checklist

- [ ] All code merged to develop branch
- [ ] Elasticsearch indexes operational
- [ ] Search response <200ms achieved
- [ ] Autocomplete functional
- [ ] Filters working correctly
- [ ] Recommendations generating
- [ ] Comparison tool functional
- [ ] Test coverage >80%
- [ ] Documentation complete
- [ ] Demo conducted
- [ ] No P0/P1 bugs
- [ ] Ready for Milestone 62

---

**Document Prepared By:** Smart Dairy Development Team
**Review Status:** Pending Technical Review
**Next Milestone:** Milestone 62 — Customer Account Management
