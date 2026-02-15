# SMART DAIRY LTD.
## SEARCH IMPLEMENTATION GUIDE (Elasticsearch)
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-011 |
| **Version** | 1.0 |
| **Date** | February 20, 2026 |
| **Author** | Tech Lead |
| **Owner** | Backend Team |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Elasticsearch Architecture](#2-elasticsearch-architecture)
3. [Index Design](#3-index-design)
4. [Document Mapping](#4-document-mapping)
5. [Search Implementation](#5-search-implementation)
6. [Query Patterns](#6-query-patterns)
7. [Relevance Tuning](#7-relevance-tuning)
8. [Data Synchronization](#8-data-synchronization)
9. [Monitoring & Performance](#9-monitoring--performance)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the Elasticsearch implementation for Smart Dairy's search functionality, covering product search, animal search, customer search, and global search capabilities.

### 1.2 Scope

- Full-text search for products, customers, animals
- Autocomplete/suggestions
- Faceted search and filtering
- Multi-language support (English, Bengali)
- Real-time data synchronization
- Search analytics

---

## 2. ELASTICSEARCH ARCHITECTURE

### 2.1 Cluster Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      ELASTICSEARCH CLUSTER ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    ELASTICSEARCH CLUSTER                             │   │
│  │                                                                       │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                 │   │
│  │  │ Master Node │  │ Master Node │  │ Master Node │                 │   │
│  │  │  (eligible) │  │  (eligible) │  │  (eligible) │                 │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘                 │   │
│  │                                                                       │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                 │   │
│  │  │ Data Node 1 │  │ Data Node 2 │  │ Data Node 3 │                 │   │
│  │  │  (hot)      │  │  (hot)      │  │  (warm)     │                 │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘                 │   │
│  │                                                                       │   │
│  │  ┌─────────────────────────────────────────────────────────────┐   │   │
│  │  │                    INDICES                                   │   │   │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐       │   │   │
│  │  │  │ products │ │ customers│ │ animals  │ │ orders   │       │   │   │
│  │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘       │   │   │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐                   │   │   │
│  │  │  │suggestions│ │ logs     │ │ analytics│                   │   │   │
│  │  │  └──────────┘ └──────────┘ └──────────┘                   │   │   │
│  │  └─────────────────────────────────────────────────────────────┘   │   │
│  │                                                                       │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                   │                                         │
│                                   │ HTTP REST API                           │
│                                   │                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    APPLICATION SERVERS                               │   │
│  │  - Odoo ERP         - FastAPI Gateway       - Mobile API            │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Configuration

```yaml
# elasticsearch.yml
cluster:
  name: smart-dairy-search
  routing.allocation.awareness.attributes: zone

node:
  name: ${HOSTNAME}
  roles: [master, data]

network:
  host: 0.0.0.0
  bind_host: 0.0.0.0

http:
  port: 9200
  compression: true

transport:
  port: 9300

discovery:
  seed_hosts: ["es-node-1", "es-node-2", "es-node-3"]
  cluster_initial_master_nodes: ["es-node-1"]

# Memory settings
bootstrap.memory_lock: true
indices.memory.index_buffer_size: 30%

# Search settings
search.max_buckets: 100000
index.max_result_window: 10000
```

---

## 3. INDEX DESIGN

### 3.1 Index Strategy

| Index | Shards | Replicas | Lifecycle |
|-------|--------|----------|-----------|
| products | 3 | 1 | Hot (365d) |
| customers | 2 | 1 | Hot (forever) |
| animals | 2 | 1 | Hot (forever) |
| orders | 5 | 1 | Hot (90d) → Warm (365d) → Delete |
| suggestions | 1 | 1 | Hot (30d) |
| search_logs | 3 | 0 | Hot (7d) → Warm (30d) → Delete |

### 3.2 Index Templates

```json
// products index template
{
  "index_patterns": ["products-*"],
  "template": {
    "settings": {
      "number_of_shards": 3,
      "number_of_replicas": 1,
      "analysis": {
        "analyzer": {
          "standard_bilingual": {
            "type": "custom",
            "tokenizer": "standard",
            "filter": [
              "lowercase",
              "asciifolding",
              "english_stop",
              "english_stemmer"
            ]
          },
          "autocomplete": {
            "type": "custom",
            "tokenizer": "autocomplete_tokenizer",
            "filter": ["lowercase"]
          },
          "autocomplete_search": {
            "type": "custom",
            "tokenizer": "lowercase"
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
          "english_stop": {
            "type": "stop",
            "stopwords": "_english_"
          },
          "english_stemmer": {
            "type": "stemmer",
            "language": "english"
          }
        }
      }
    },
    "mappings": {
      "properties": {
        "id": { "type": "integer" },
        "name": {
          "type": "text",
          "analyzer": "standard_bilingual",
          "fields": {
            "keyword": { "type": "keyword" },
            "autocomplete": {
              "type": "text",
              "analyzer": "autocomplete",
              "search_analyzer": "autocomplete_search"
            }
          }
        },
        "description": {
          "type": "text",
          "analyzer": "standard_bilingual"
        },
        "category": {
          "type": "keyword",
          "fields": {
            "text": { "type": "text" }
          }
        },
        "tags": { "type": "keyword" },
        "price": { "type": "float" },
        "currency": { "type": "keyword" },
        "stock_quantity": { "type": "integer" },
        "is_available": { "type": "boolean" },
        "popularity_score": { "type": "float" },
        "created_at": { "type": "date" },
        "updated_at": { "type": "date" },
        "suggest": {
          "type": "completion"
        }
      }
    }
  }
}
```

---

## 4. DOCUMENT MAPPING

### 4.1 Product Document

```python
# search/documents/product.py
from elasticsearch_dsl import Document, Text, Keyword, Float, Integer, Date, Completion

class ProductDocument(Document):
    """Elasticsearch document for products."""
    
    id = Integer()
    name = Text(
        analyzer='standard_bilingual',
        fields={
            'keyword': Keyword(),
            'autocomplete': Text(analyzer='autocomplete')
        }
    )
    description = Text(analyzer='standard_bilingual')
    category = Keyword(fields={'text': Text()})
    subcategory = Keyword()
    tags = Keyword()
    
    # Pricing
    price = Float()
    original_price = Float()
    currency = Keyword()
    price_tier = Keyword()  # budget, standard, premium
    
    # Inventory
    stock_quantity = Integer()
    stock_status = Keyword()  # in_stock, low_stock, out_of_stock
    warehouse_ids = Keyword()
    
    # Attributes
    fat_percentage = Float()
    volume_ml = Integer()
    shelf_life_days = Integer()
    is_organic = Boolean()
    is_halal = Boolean()
    
    # Search boost fields
    popularity_score = Float()
    search_count_30d = Integer()
    order_count_30d = Integer()
    
    # Suggestion
    suggest = Completion()
    
    # Timestamps
    created_at = Date()
    updated_at = Date()
    
    class Index:
        name = 'products'
        settings = {
            'number_of_shards': 3,
            'number_of_replicas': 1
        }
    
    @classmethod
    def from_odoo_record(cls, product):
        """Create ES document from Odoo record."""
        return cls(
            meta={'id': product.id},
            id=product.id,
            name=product.name,
            description=product.description or '',
            category=product.categ_id.name if product.categ_id else '',
            tags=[tag.name for tag in product.tag_ids],
            price=product.list_price,
            currency=product.currency_id.name,
            stock_quantity=product.qty_available,
            is_available=product.qty_available > 0,
            popularity_score=calculate_popularity(product),
            suggest={'input': [product.name, product.default_code or '']},
            created_at=product.create_date,
            updated_at=product.write_date
        )
```

### 4.2 Animal Document

```python
# search/documents/animal.py
from elasticsearch_dsl import Document, Text, Keyword, Float, Integer, Date

class AnimalDocument(Document):
    """Elasticsearch document for farm animals."""
    
    id = Integer()
    name = Text(fields={'keyword': Keyword()})
    rfid_tag = Keyword()
    ear_tag = Keyword()
    
    species = Keyword()
    breed = Text(fields={'keyword': Keyword()})
    gender = Keyword()
    
    status = Keyword()  # calf, heifer, lactating, dry, etc.
    health_status = Keyword()
    
    birth_date = Date()
    age_months = Integer()
    
    current_lactation = Integer()
    daily_milk_avg = Float()
    total_lifetime_milk = Float()
    
    barn_name = Text(fields={'keyword': Keyword()})
    pen_number = Keyword()
    
    last_health_check = Date()
    last_breeding_date = Date()
    expected_calving_date = Date()
    
    created_at = Date()
    updated_at = Date()
    
    class Index:
        name = 'animals'
```

---

## 5. SEARCH IMPLEMENTATION

### 5.1 Search Service

```python
# services/search_service.py
from elasticsearch import Elasticsearch
from elasticsearch_dsl import Search, Q
from typing import List, Optional, Dict, Any

class SearchService:
    """Elasticsearch search service."""
    
    def __init__(self, hosts: List[str]):
        self.client = Elasticsearch(hosts)
    
    def search_products(
        self,
        query: str,
        filters: Optional[Dict] = None,
        sort: Optional[str] = None,
        page: int = 1,
        per_page: int = 20
    ) -> Dict[str, Any]:
        """
        Search products with full-text and filters.
        
        Args:
            query: Search query string
            filters: Category, price range, etc.
            sort: Sort field
            page: Page number
            per_page: Items per page
        """
        s = Search(using=self.client, index='products')
        
        # Build query
        if query:
            q = Q('multi_match',
                  query=query,
                  fields=['name^3', 'description', 'category^2', 'tags'],
                  type='best_fields',
                  fuzziness='AUTO')
            s = s.query(q)
        else:
            s = s.query('match_all')
        
        # Apply filters
        if filters:
            if 'category' in filters:
                s = s.filter('term', category=filters['category'])
            
            if 'min_price' in filters or 'max_price' in filters:
                price_range = {}
                if 'min_price' in filters:
                    price_range['gte'] = filters['min_price']
                if 'max_price' in filters:
                    price_range['lte'] = filters['max_price']
                s = s.filter('range', price=price_range)
            
            if 'in_stock' in filters and filters['in_stock']:
                s = s.filter('term', is_available=True)
            
            if 'tags' in filters:
                s = s.filter('terms', tags=filters['tags'])
        
        # Add aggregations for facets
        s.aggs.bucket('categories', 'terms', field='category')
        s.aggs.bucket('price_ranges', 'range', field='price', ranges=[
            {'to': 50, 'key': 'under_50'},
            {'from': 50, 'to': 100, 'key': '50_to_100'},
            {'from': 100, 'key': 'over_100'}
        ])
        
        # Sorting
        if sort:
            if sort == 'price_asc':
                s = s.sort('price')
            elif sort == 'price_desc':
                s = s.sort('-price')
            elif sort == 'popularity':
                s = s.sort('-popularity_score')
            elif sort == 'newest':
                s = s.sort('-created_at')
        else:
            # Default: relevance + popularity boost
            s = s.sort('_score', '-popularity_score')
        
        # Pagination
        start = (page - 1) * per_page
        s = s[start:start + per_page]
        
        # Execute
        response = s.execute()
        
        return {
            'total': response.hits.total.value,
            'page': page,
            'per_page': per_page,
            'results': [hit.to_dict() for hit in response],
            'facets': {
                'categories': [
                    {'key': b.key, 'count': b.doc_count}
                    for b in response.aggregations.categories.buckets
                ],
                'price_ranges': [
                    {'key': b.key, 'count': b.doc_count}
                    for b in response.aggregations.price_ranges.buckets
                ]
            },
            'suggestions': self._get_suggestions(query)
        }
    
    def autocomplete(self, query: str, index: str = 'products') -> List[str]:
        """Get autocomplete suggestions."""
        s = Search(using=self.client, index=index)
        
        s = s.suggest('suggestions', query, completion={
            'field': 'suggest',
            'fuzzy': {'fuzziness': 'AUTO'}
        })
        
        response = s.execute()
        
        suggestions = []
        for option in response.suggest.suggestions[0].options:
            suggestions.append(option.text)
        
        return suggestions[:10]
    
    def search_animals(
        self,
        query: Optional[str] = None,
        rfid_tag: Optional[str] = None,
        status: Optional[str] = None,
        barn_id: Optional[int] = None
    ) -> List[Dict]:
        """Search farm animals."""
        s = Search(using=self.client, index='animals')
        
        if rfid_tag:
            # Exact match for RFID
            s = s.filter('term', rfid_tag=rfid_tag)
        elif query:
            # Full-text search
            q = Q('multi_match',
                  query=query,
                  fields=['name^2', 'rfid_tag', 'ear_tag', 'breed'])
            s = s.query(q)
        
        if status:
            s = s.filter('term', status=status)
        
        response = s.execute()
        return [hit.to_dict() for hit in response]
```

### 5.2 API Endpoints

```python
# api/search.py
from fastapi import APIRouter, Query, Depends
from typing import List, Optional

router = APIRouter()

@router.get("/search/products")
async def search_products(
    q: str = Query(..., min_length=1, description="Search query"),
    category: Optional[str] = None,
    min_price: Optional[float] = None,
    max_price: Optional[float] = None,
    in_stock: bool = False,
    sort: Optional[str] = Query(None, enum=['price_asc', 'price_desc', 'popularity', 'newest']),
    page: int = Query(1, ge=1),
    per_page: int = Query(20, ge=1, le=100),
    search_service: SearchService = Depends(get_search_service)
):
    """Search products with filters and sorting."""
    filters = {
        'category': category,
        'min_price': min_price,
        'max_price': max_price,
        'in_stock': in_stock
    }
    filters = {k: v for k, v in filters.items() if v is not None}
    
    results = search_service.search_products(
        query=q,
        filters=filters if filters else None,
        sort=sort,
        page=page,
        per_page=per_page
    )
    
    return results


@router.get("/search/autocomplete")
async def autocomplete(
    q: str = Query(..., min_length=2),
    index: str = Query('products', enum=['products', 'customers', 'animals']),
    search_service: SearchService = Depends(get_search_service)
):
    """Get autocomplete suggestions."""
    suggestions = search_service.autocomplete(q, index)
    return {'query': q, 'suggestions': suggestions}


@router.get("/search/animals")
async def search_animals(
    q: Optional[str] = None,
    rfid_tag: Optional[str] = None,
    status: Optional[str] = None,
    search_service: SearchService = Depends(get_search_service)
):
    """Search farm animals."""
    results = search_service.search_animals(
        query=q,
        rfid_tag=rfid_tag,
        status=status
    )
    return {'results': results}
```

---

## 6. QUERY PATTERNS

### 6.1 Multi-Match Query

```python
# Multi-field search with boosting
{
    "query": {
        "multi_match": {
            "query": "fresh milk",
            "fields": ["name^3", "description", "category^2", "tags"],
            "type": "best_fields",
            "operator": "and",
            "fuzziness": "AUTO"
        }
    }
}
```

### 6.2 Filtered Query

```python
# Search with post-filters
{
    "query": {
        "bool": {
            "must": [
                {"multi_match": {"query": "milk", "fields": ["name^2", "description"]}}
            ],
            "filter": [
                {"term": {"category": "dairy"}},
                {"range": {"price": {"gte": 10, "lte": 100}}},
                {"term": {"is_available": True}}
            ]
        }
    }
}
```

### 6.3 Function Score Query

```python
# Boost by popularity and recency
{
    "query": {
        "function_score": {
            "query": {"match": {"name": "milk"}},
            "functions": [
                {
                    "field_value_factor": {
                        "field": "popularity_score",
                        "factor": 1.2,
                        "modifier": "log1p"
                    }
                },
                {
                    "gauss": {
                        "created_at": {
                            "origin": "now",
                            "scale": "30d",
                            "decay": 0.5
                        }
                    }
                }
            ],
            "score_mode": "multiply"
        }
    }
}
```

---

## 7. RELEVANCE TUNING

### 7.1 Scoring Formula

```python
# Custom scoring function
def calculate_relevance_score(hit, query_terms):
    """Calculate custom relevance score."""
    score = hit.meta.score
    
    # Boost exact name matches
    if hit.name.lower() == query_terms.lower():
        score *= 2.0
    
    # Boost high-stock items
    if hit.stock_quantity > 100:
        score *= 1.1
    
    # Boost popular items
    if hit.popularity_score > 0.8:
        score *= 1.2
    
    # Penalize out-of-stock
    if not hit.is_available:
        score *= 0.5
    
    return score
```

### 7.2 Synonyms

```json
// synonyms.txt
// Dairy products
milk, dairy, liquid milk
yogurt, yoghurt, curd, doi
butter, ghee, clarified butter

// Common misspellings
fresh, frehs, fersh
organic, organik, orgnic

// Bengali transliterations
doi, doi, দই
misti, mishti, মিষ্টি
```

---

## 8. DATA SYNCHRONIZATION

### 8.1 Odoo to Elasticsearch Sync

```python
# sync/odoo_to_es.py
from odoo import models, api
from elasticsearch import Elasticsearch

class ElasticsearchSync(models.AbstractModel):
    _name = 'elasticsearch.sync.mixin'
    
    def write(self, vals):
        """Sync to ES on update."""
        result = super().write(vals)
        
        for record in self:
            self.env['elasticsearch.sync.queue'].create({
                'model': self._name,
                'record_id': record.id,
                'operation': 'update'
            })
        
        return result
    
    def unlink(self):
        """Delete from ES on unlink."""
        for record in self:
            self.env['elasticsearch.sync.queue'].create({
                'model': self._name,
                'record_id': record.id,
                'operation': 'delete'
            })
        
        return super().unlink()


class ElasticsearchSyncQueue(models.Model):
    _name = 'elasticsearch.sync.queue'
    
    model = fields.Char(required=True)
    record_id = fields.Integer(required=True)
    operation = fields.Selection([
        ('create', 'Create'),
        ('update', 'Update'),
        ('delete', 'Delete')
    ], required=True)
    status = fields.Selection([
        ('pending', 'Pending'),
        ('processing', 'Processing'),
        ('done', 'Done'),
        ('error', 'Error')
    ], default='pending')
    error_message = fields.Text()
    
    def process_sync(self):
        """Process pending sync items."""
        es = Elasticsearch(['es-node-1:9200'])
        
        for item in self.search([('status', 'in', ['pending', 'error'])]):
            try:
                item.status = 'processing'
                
                if item.operation == 'delete':
                    es.delete(index=item.model, id=item.record_id)
                else:
                    record = self.env[item.model].browse(item.record_id)
                    doc = self._create_document(item.model, record)
                    
                    es.index(
                        index=item.model,
                        id=item.record_id,
                        body=doc
                    )
                
                item.status = 'done'
                
            except Exception as e:
                item.status = 'error'
                item.error_message = str(e)
```

---

## 9. MONITORING & PERFORMANCE

### 9.1 Key Metrics

| Metric | Target | Alert |
|--------|--------|-------|
| Query Latency (p99) | < 50ms | > 100ms |
| Indexing Rate | - | Errors > 1% |
| Cluster Health | Green | Yellow/Red |
| Disk Usage | < 70% | > 85% |
| JVM Heap | < 75% | > 90% |

### 9.2 Monitoring Setup

```python
# monitoring/es_metrics.py
from prometheus_client import Counter, Histogram, Gauge

es_search_duration = Histogram(
    'elasticsearch_search_duration_seconds',
    'Search query duration',
    ['index'],
    buckets=[.001, .005, .01, .025, .05, .1, .25]
)

es_indexing_total = Counter(
    'elasticsearch_indexing_total',
    'Documents indexed',
    ['index', 'status']
)

es_cluster_health = Gauge(
    'elasticsearch_cluster_health',
    'Cluster health status (0=green, 1=yellow, 2=red)'
)
```

---

## 10. APPENDICES

### Appendix A: Index Management Commands

```bash
# Create index
curl -X PUT "localhost:9200/products-000001"

# Reindex
curl -X POST "localhost:9200/_reindex" -H 'Content-Type: application/json' -d'
{
  "source": {"index": "products-old"},
  "dest": {"index": "products-new"}
}'

# Force merge
curl -X POST "localhost:9200/products/_forcemerge?max_num_segments=1"
```

---

**END OF SEARCH IMPLEMENTATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 20, 2026 | Tech Lead | Initial version |
