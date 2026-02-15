# Milestone 53: Customer App — Product Catalog

## Smart Dairy Digital Smart Portal + ERP — Phase 6: Mobile App Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 53 of 150 (3 of 10 in Phase 6)                                         |
| **Title**        | Customer App — Product Catalog                                         |
| **Phase**        | Phase 6 — Mobile App Foundation                                        |
| **Days**         | Days 271-280 (10 working days)                                         |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Mobile Lead)          |
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

Build a comprehensive product browsing experience for the Customer App with category navigation, full-text search powered by Elasticsearch, advanced filtering, offline-first caching using Hive, and a beautiful UI that showcases Smart Dairy's dairy products effectively.

### 1.2 Objectives

1. Create product listing API with pagination and sorting
2. Implement category hierarchy navigation
3. Integrate Elasticsearch for full-text product search
4. Build advanced filter system (price, category, availability)
5. Design product detail view with image gallery
6. Implement offline product caching with Hive
7. Create wishlist functionality with sync
8. Build product review and rating system
9. Design recently viewed products tracking
10. Implement promotional banners and featured products

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| Product List API                | Dev 1  | REST endpoint     | 271     |
| Category API                    | Dev 1  | REST endpoint     | 272     |
| Elasticsearch Integration       | Dev 1  | Search service    | 274     |
| Product Detail API              | Dev 1  | REST endpoint     | 273     |
| CDN Image Configuration         | Dev 2  | Infrastructure    | 271     |
| Cache Headers Setup             | Dev 2  | Nginx config      | 275     |
| Search Suggestions API          | Dev 2  | REST endpoint     | 274     |
| Product List Screen             | Dev 3  | Flutter widget    | 272     |
| Category Navigation             | Dev 3  | Flutter widget    | 272     |
| Product Detail Screen           | Dev 3  | Flutter widget    | 273     |
| Search UI with Filters          | Dev 3  | Flutter widget    | 274     |
| Hive Offline Cache              | Dev 3  | Dart service      | 275     |
| Wishlist Feature                | Dev 3  | Flutter + API     | 277     |
| Reviews Display                 | Dev 3  | Flutter widget    | 278     |
| Promotional Banners             | Dev 3  | Flutter widget    | 279     |
| Product Catalog Tests           | Dev 2  | Test suite        | 280     |

### 1.4 Prerequisites

- Milestone 52 complete (Authentication)
- Product data migrated in Odoo
- Product images uploaded to storage
- Elasticsearch cluster running
- CDN configured for images

### 1.5 Success Criteria

- [ ] Product list loads <2 seconds on 3G
- [ ] Search results return <500ms
- [ ] Offline mode shows cached products
- [ ] 50+ products displayed with smooth scrolling
- [ ] Image thumbnails load progressively
- [ ] Wishlist syncs across devices
- [ ] Reviews display with ratings
- [ ] Filter combinations work correctly
- [ ] Unit test coverage >80%

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-MOB-003  | RFP    | Product catalog browsing with offline    | 271-280 | Complete milestone    |
| SRS-MOB-006  | SRS    | Elasticsearch product search             | 274     | Search integration    |
| BRD-MOB-007  | BRD    | Real-time inventory visibility           | 273     | Stock status API      |
| H-005        | Impl   | Offline-First Architecture               | 275     | Hive caching          |

---

## 3. Day-by-Day Breakdown

### Day 271 — Product List API & CDN Setup

**Objective:** Create product listing endpoint with pagination and configure CDN for product images.

#### Dev 1 — Backend Lead (8h)

**Task 1: Product API Controller (5h)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/product_controller.py
from odoo import http
from odoo.http import request
from .base_controller import MobileAPIController, api_response, jwt_required, rate_limit
import logging

_logger = logging.getLogger(__name__)


class ProductController(MobileAPIController):
    """Product API endpoints for Customer App"""

    @http.route('/api/v1/products', type='json', auth='public', methods=['GET'], csrf=False)
    @rate_limit(max_requests=100, window_seconds=60)
    def get_products(self, **kwargs):
        """
        Get product list with pagination, filters, and sorting.

        Query Parameters:
            page: int (default 1)
            limit: int (default 20, max 100)
            category_id: int (optional)
            search: str (optional)
            min_price: float (optional)
            max_price: float (optional)
            in_stock: bool (optional)
            sort: str (name_asc, name_desc, price_asc, price_desc, newest)
            featured: bool (optional)

        Response:
            {
                "success": true,
                "data": {
                    "items": [...],
                    "pagination": {
                        "page": 1,
                        "limit": 20,
                        "total": 150,
                        "total_pages": 8,
                        "has_next": true,
                        "has_prev": false
                    }
                }
            }
        """
        page, limit, offset = self._get_pagination_params(kwargs)

        # Build domain
        domain = [
            ('sale_ok', '=', True),
            ('active', '=', True),
            ('is_published', '=', True),
        ]

        # Category filter
        category_id = kwargs.get('category_id')
        if category_id:
            # Include child categories
            category = request.env['product.category'].sudo().browse(int(category_id))
            category_ids = category.search([
                ('id', 'child_of', int(category_id))
            ]).ids
            domain.append(('categ_id', 'in', category_ids))

        # Price filters
        min_price = kwargs.get('min_price')
        max_price = kwargs.get('max_price')
        if min_price:
            domain.append(('list_price', '>=', float(min_price)))
        if max_price:
            domain.append(('list_price', '<=', float(max_price)))

        # Stock filter
        in_stock = kwargs.get('in_stock')
        if in_stock:
            domain.append(('qty_available', '>', 0))

        # Featured filter
        featured = kwargs.get('featured')
        if featured:
            domain.append(('is_featured', '=', True))

        # Search (basic - for full-text use Elasticsearch)
        search = kwargs.get('search', '').strip()
        if search:
            domain.append('|')
            domain.append(('name', 'ilike', search))
            domain.append(('description_sale', 'ilike', search))

        # Sorting
        sort_options = {
            'name_asc': 'name asc',
            'name_desc': 'name desc',
            'price_asc': 'list_price asc',
            'price_desc': 'list_price desc',
            'newest': 'create_date desc',
            'popular': 'sales_count desc',
        }
        sort = kwargs.get('sort', 'name_asc')
        order = sort_options.get(sort, 'name asc')

        # Query products
        Product = request.env['product.product'].sudo()
        total = Product.search_count(domain)
        products = Product.search(domain, limit=limit, offset=offset, order=order)

        # Serialize
        items = [self._serialize_product_list(p) for p in products]

        return self._paginated_response(items, total, page, limit)

    @http.route('/api/v1/products/<int:product_id>', type='json', auth='public', methods=['GET'], csrf=False)
    def get_product_detail(self, product_id, **kwargs):
        """
        Get product detail.

        Response:
            {
                "success": true,
                "data": {
                    "id": 1,
                    "name": "Fresh Organic Milk",
                    "description": "...",
                    "price": 85.00,
                    "compare_price": 95.00,
                    "discount_percent": 10,
                    "images": [...],
                    "category": {...},
                    "attributes": [...],
                    "variants": [...],
                    "stock_status": "in_stock",
                    "quantity_available": 100,
                    "unit": "Liter",
                    "nutritional_info": {...},
                    "related_products": [...],
                    "reviews_summary": {...}
                }
            }
        """
        product = request.env['product.product'].sudo().browse(product_id)

        if not product.exists() or not product.active or not product.sale_ok:
            return api_response(
                success=False,
                message='Product not found',
                error_code='PRODUCT_NOT_FOUND'
            )

        return api_response(
            success=True,
            data=self._serialize_product_detail(product)
        )

    def _serialize_product_list(self, product):
        """Serialize product for list view"""
        return {
            'id': product.id,
            'name': product.name,
            'slug': product.website_slug or f'product-{product.id}',
            'price': product.list_price,
            'compare_price': product.compare_list_price or None,
            'discount_percent': self._calculate_discount(product),
            'image_url': self._get_image_url(product),
            'image_thumb_url': self._get_image_url(product, size='256x256'),
            'category_id': product.categ_id.id,
            'category_name': product.categ_id.name,
            'stock_status': self._get_stock_status(product),
            'rating': product.rating_avg or 0,
            'review_count': product.rating_count or 0,
            'unit': product.uom_id.name,
            'is_featured': product.is_featured,
            'is_new': self._is_new_product(product),
        }

    def _serialize_product_detail(self, product):
        """Serialize product for detail view"""
        base = self._serialize_product_list(product)
        base.update({
            'description': product.description_sale or '',
            'description_html': product.website_description or '',
            'images': self._get_all_images(product),
            'category': {
                'id': product.categ_id.id,
                'name': product.categ_id.name,
                'parent_id': product.categ_id.parent_id.id if product.categ_id.parent_id else None,
            },
            'attributes': self._get_attributes(product),
            'variants': self._get_variants(product),
            'quantity_available': max(0, int(product.qty_available)),
            'min_quantity': product.sale_min_qty or 1,
            'max_quantity': product.sale_max_qty or 99,
            'nutritional_info': self._get_nutritional_info(product),
            'related_products': self._get_related_products(product),
            'reviews_summary': self._get_reviews_summary(product),
            'farm_info': self._get_farm_info(product),
        })
        return base

    def _get_image_url(self, product, size='1920x1080'):
        """Get CDN image URL"""
        if product.image_1920:
            return f'/web/image/product.product/{product.id}/image_1920/{size}'
        return '/smart_dairy_mobile_api/static/images/placeholder.png'

    def _get_all_images(self, product):
        """Get all product images"""
        images = [{
            'id': 0,
            'url': self._get_image_url(product),
            'thumb_url': self._get_image_url(product, '256x256'),
            'is_primary': True,
        }]

        # Additional images from product.image
        for idx, img in enumerate(product.product_template_image_ids):
            images.append({
                'id': img.id,
                'url': f'/web/image/product.image/{img.id}/image_1920',
                'thumb_url': f'/web/image/product.image/{img.id}/image_128',
                'is_primary': False,
            })

        return images

    def _get_stock_status(self, product):
        """Get stock status"""
        if product.qty_available > 10:
            return 'in_stock'
        elif product.qty_available > 0:
            return 'low_stock'
        else:
            return 'out_of_stock'

    def _calculate_discount(self, product):
        """Calculate discount percentage"""
        if product.compare_list_price and product.compare_list_price > product.list_price:
            discount = ((product.compare_list_price - product.list_price) / product.compare_list_price) * 100
            return round(discount)
        return 0

    def _is_new_product(self, product):
        """Check if product is new (created within 30 days)"""
        from datetime import datetime, timedelta
        threshold = datetime.now() - timedelta(days=30)
        return product.create_date > threshold

    def _get_nutritional_info(self, product):
        """Get nutritional information for dairy products"""
        # Custom field on product.template
        if hasattr(product, 'nutritional_info') and product.nutritional_info:
            return {
                'calories': product.nutritional_info.get('calories'),
                'protein': product.nutritional_info.get('protein'),
                'fat': product.nutritional_info.get('fat'),
                'carbohydrates': product.nutritional_info.get('carbohydrates'),
                'calcium': product.nutritional_info.get('calcium'),
            }
        return None

    def _get_related_products(self, product, limit=6):
        """Get related products"""
        # Same category products
        related = request.env['product.product'].sudo().search([
            ('categ_id', '=', product.categ_id.id),
            ('id', '!=', product.id),
            ('sale_ok', '=', True),
            ('active', '=', True),
        ], limit=limit)

        return [self._serialize_product_list(p) for p in related]

    def _get_reviews_summary(self, product):
        """Get reviews summary"""
        return {
            'average_rating': product.rating_avg or 0,
            'total_reviews': product.rating_count or 0,
            'rating_distribution': {
                '5': 0,  # Would calculate from actual reviews
                '4': 0,
                '3': 0,
                '2': 0,
                '1': 0,
            }
        }

    def _get_farm_info(self, product):
        """Get farm origin information"""
        if hasattr(product, 'farm_id') and product.farm_id:
            return {
                'farm_name': product.farm_id.name,
                'location': product.farm_id.location,
                'certifications': product.farm_id.certifications,
            }
        return None
```

**Task 2: Product Serialization Helpers (2h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/product_extensions.py
from odoo import models, fields, api


class ProductProductExtension(models.Model):
    _inherit = 'product.product'

    # Mobile-specific fields
    is_featured = fields.Boolean(
        string='Featured Product',
        default=False,
        help='Show in featured section of mobile app'
    )
    is_published = fields.Boolean(
        string='Published on Mobile',
        default=True,
        help='Visible on mobile app'
    )
    mobile_sort_order = fields.Integer(
        string='Mobile Sort Order',
        default=100
    )
    sales_count = fields.Integer(
        string='Sales Count',
        compute='_compute_sales_count',
        store=True
    )
    compare_list_price = fields.Float(
        string='Compare Price',
        help='Original price before discount'
    )
    website_slug = fields.Char(
        string='URL Slug',
        compute='_compute_slug',
        store=True
    )

    @api.depends('name')
    def _compute_slug(self):
        for product in self:
            import re
            slug = product.name.lower()
            slug = re.sub(r'[^a-z0-9\s-]', '', slug)
            slug = re.sub(r'[\s_]+', '-', slug)
            product.website_slug = f'{slug}-{product.id}'

    @api.depends('sale_order_line_ids.order_id.state')
    def _compute_sales_count(self):
        for product in self:
            sales = self.env['sale.order.line'].search_count([
                ('product_id', '=', product.id),
                ('order_id.state', 'in', ['sale', 'done'])
            ])
            product.sales_count = sales


class ProductCategoryExtension(models.Model):
    _inherit = 'product.category'

    mobile_image = fields.Image(
        string='Mobile Category Image',
        max_width=512,
        max_height=512
    )
    mobile_icon = fields.Char(
        string='Mobile Icon',
        help='Icon name from app icon set'
    )
    mobile_sort_order = fields.Integer(
        string='Mobile Sort Order',
        default=100
    )
    is_visible_mobile = fields.Boolean(
        string='Visible on Mobile',
        default=True
    )
```

**Task 3: Category API (1h)**

```python
# Add to product_controller.py

@http.route('/api/v1/categories', type='json', auth='public', methods=['GET'], csrf=False)
def get_categories(self, **kwargs):
    """
    Get product categories.

    Query Parameters:
        parent_id: int (optional, for child categories)
        include_products: bool (optional, include product count)

    Response:
        {
            "success": true,
            "data": [
                {
                    "id": 1,
                    "name": "Dairy Products",
                    "slug": "dairy-products",
                    "image_url": "...",
                    "icon": "milk",
                    "parent_id": null,
                    "child_count": 5,
                    "product_count": 25
                }
            ]
        }
    """
    domain = [('is_visible_mobile', '=', True)]

    parent_id = kwargs.get('parent_id')
    if parent_id:
        domain.append(('parent_id', '=', int(parent_id)))
    else:
        domain.append(('parent_id', '=', False))

    categories = request.env['product.category'].sudo().search(
        domain, order='mobile_sort_order, name'
    )

    include_products = kwargs.get('include_products', False)

    items = []
    for cat in categories:
        item = {
            'id': cat.id,
            'name': cat.name,
            'slug': f'category-{cat.id}',
            'image_url': f'/web/image/product.category/{cat.id}/mobile_image' if cat.mobile_image else None,
            'icon': cat.mobile_icon or 'category',
            'parent_id': cat.parent_id.id if cat.parent_id else None,
            'child_count': len(cat.child_id),
        }

        if include_products:
            item['product_count'] = request.env['product.product'].sudo().search_count([
                ('categ_id', 'child_of', cat.id),
                ('sale_ok', '=', True),
                ('active', '=', True),
            ])

        items.append(item)

    return api_response(success=True, data=items)

@http.route('/api/v1/categories/<int:category_id>', type='json', auth='public', methods=['GET'], csrf=False)
def get_category_detail(self, category_id, **kwargs):
    """Get category detail with children"""
    category = request.env['product.category'].sudo().browse(category_id)

    if not category.exists() or not category.is_visible_mobile:
        return api_response(
            success=False,
            message='Category not found',
            error_code='CATEGORY_NOT_FOUND'
        )

    return api_response(
        success=True,
        data={
            'id': category.id,
            'name': category.name,
            'description': category.complete_name,
            'image_url': f'/web/image/product.category/{category.id}/mobile_image' if category.mobile_image else None,
            'parent': {
                'id': category.parent_id.id,
                'name': category.parent_id.name,
            } if category.parent_id else None,
            'children': [{
                'id': c.id,
                'name': c.name,
                'image_url': f'/web/image/product.category/{c.id}/mobile_image' if c.mobile_image else None,
            } for c in category.child_id if c.is_visible_mobile],
            'breadcrumb': self._get_breadcrumb(category),
        }
    )

def _get_breadcrumb(self, category):
    """Get category breadcrumb path"""
    breadcrumb = []
    current = category
    while current:
        breadcrumb.insert(0, {'id': current.id, 'name': current.name})
        current = current.parent_id
    return breadcrumb
```

#### Dev 2 — Full-Stack (8h)

**Task 1: CDN Configuration for Images (4h)**

```nginx
# nginx/conf.d/cdn.conf
# CDN configuration for product images

# Image caching and optimization
location ~* ^/web/image/ {
    # Enable caching
    proxy_cache product_images;
    proxy_cache_valid 200 7d;
    proxy_cache_valid 404 1m;
    proxy_cache_key $uri$is_args$args;
    proxy_cache_use_stale error timeout updating;

    # Cache control headers
    add_header Cache-Control "public, max-age=604800, immutable";
    add_header X-Cache-Status $upstream_cache_status;

    # CORS for mobile apps
    add_header Access-Control-Allow-Origin "*";
    add_header Access-Control-Allow-Methods "GET, OPTIONS";

    # Image optimization headers
    add_header Vary "Accept-Encoding";

    # Compression
    gzip on;
    gzip_types image/svg+xml;

    # WebP support
    set $webp_suffix "";
    if ($http_accept ~* "webp") {
        set $webp_suffix ".webp";
    }

    # Proxy to Odoo
    proxy_pass http://odoo_backend;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;

    # Timeouts
    proxy_connect_timeout 30s;
    proxy_read_timeout 60s;
}

# Define cache zone
proxy_cache_path /var/cache/nginx/images
    levels=1:2
    keys_zone=product_images:100m
    max_size=10g
    inactive=7d
    use_temp_path=off;
```

**Task 2: Image Processing Service (2h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/image_service.py
from odoo import models, api
from PIL import Image
import io
import base64
import logging

_logger = logging.getLogger(__name__)


class ImageService(models.AbstractModel):
    _name = 'mobile.image.service'
    _description = 'Image Processing Service'

    @api.model
    def resize_image(self, image_data, width, height, quality=85):
        """
        Resize image to specified dimensions.

        Args:
            image_data: Base64 encoded image
            width: Target width
            height: Target height
            quality: JPEG quality (1-100)

        Returns:
            Base64 encoded resized image
        """
        try:
            # Decode base64
            image_bytes = base64.b64decode(image_data)
            image = Image.open(io.BytesIO(image_bytes))

            # Convert to RGB if necessary
            if image.mode in ('RGBA', 'P'):
                image = image.convert('RGB')

            # Resize with aspect ratio
            image.thumbnail((width, height), Image.LANCZOS)

            # Save to bytes
            output = io.BytesIO()
            image.save(output, format='JPEG', quality=quality, optimize=True)
            output.seek(0)

            return base64.b64encode(output.getvalue()).decode('utf-8')

        except Exception as e:
            _logger.error(f'Image resize error: {e}')
            return image_data

    @api.model
    def generate_thumbnails(self, product_id):
        """Generate all thumbnail sizes for a product"""
        product = self.env['product.product'].sudo().browse(product_id)

        if not product.image_1920:
            return

        sizes = [
            ('image_128', 128, 128),
            ('image_256', 256, 256),
            ('image_512', 512, 512),
        ]

        for field_name, width, height in sizes:
            resized = self.resize_image(product.image_1920, width, height)
            product.write({field_name: resized})
```

**Task 3: API Response Caching (2h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/cache_service.py
import redis
import json
import hashlib
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class CacheService(models.AbstractModel):
    _name = 'mobile.cache.service'
    _description = 'API Response Cache Service'

    @api.model
    def get_redis_client(self):
        """Get Redis client"""
        redis_url = self.env['ir.config_parameter'].sudo().get_param(
            'mobile.redis.url',
            default='redis://localhost:6379/2'
        )
        try:
            return redis.from_url(redis_url, decode_responses=True)
        except Exception as e:
            _logger.error(f'Redis connection failed: {e}')
            return None

    @api.model
    def get_cached(self, key):
        """Get cached value"""
        client = self.get_redis_client()
        if not client:
            return None

        try:
            data = client.get(key)
            return json.loads(data) if data else None
        except Exception as e:
            _logger.error(f'Cache get error: {e}')
            return None

    @api.model
    def set_cached(self, key, value, ttl=300):
        """Set cached value"""
        client = self.get_redis_client()
        if not client:
            return

        try:
            client.setex(key, ttl, json.dumps(value))
        except Exception as e:
            _logger.error(f'Cache set error: {e}')

    @api.model
    def invalidate_product(self, product_id):
        """Invalidate all caches for a product"""
        client = self.get_redis_client()
        if not client:
            return

        patterns = [
            f'product:{product_id}:*',
            'products:list:*',
            f'category:{self._get_product_category(product_id)}:*',
        ]

        for pattern in patterns:
            for key in client.scan_iter(match=pattern):
                client.delete(key)

    @api.model
    def generate_cache_key(self, prefix, params):
        """Generate cache key from parameters"""
        param_str = json.dumps(params, sort_keys=True)
        hash_str = hashlib.md5(param_str.encode()).hexdigest()[:8]
        return f'{prefix}:{hash_str}'
```

#### Dev 3 — Mobile Lead (8h)

**Task 1: Product List Screen (4h)**

```dart
// apps/customer/lib/features/products/presentation/screens/product_list_screen.dart
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:cached_network_image/cached_network_image.dart';

class ProductListScreen extends ConsumerStatefulWidget {
  final int? categoryId;
  final String? searchQuery;

  const ProductListScreen({
    super.key,
    this.categoryId,
    this.searchQuery,
  });

  @override
  ConsumerState<ProductListScreen> createState() => _ProductListScreenState();
}

class _ProductListScreenState extends ConsumerState<ProductListScreen> {
  final _scrollController = ScrollController();
  bool _isGridView = true;

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      // Load more products
      ref.read(productListProvider.notifier).loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final productsAsync = ref.watch(productListProvider);

    return Scaffold(
      appBar: AppBar(
        title: Text(widget.categoryId != null ? 'Products' : 'All Products'),
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () => _showSearch(context),
          ),
          IconButton(
            icon: Icon(_isGridView ? Icons.list : Icons.grid_view),
            onPressed: () => setState(() => _isGridView = !_isGridView),
          ),
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _showFilters(context),
          ),
        ],
      ),
      body: Column(
        children: [
          // Category chips
          if (widget.categoryId == null) const _CategoryChips(),

          // Active filters
          const _ActiveFilters(),

          // Product grid/list
          Expanded(
            child: productsAsync.when(
              data: (products) => _buildProductList(products),
              loading: () => const _ProductListSkeleton(),
              error: (e, _) => _ErrorWidget(
                message: e.toString(),
                onRetry: () => ref.refresh(productListProvider),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildProductList(List<Product> products) {
    if (products.isEmpty) {
      return const _EmptyProductList();
    }

    if (_isGridView) {
      return GridView.builder(
        controller: _scrollController,
        padding: const EdgeInsets.all(16),
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2,
          childAspectRatio: 0.7,
          crossAxisSpacing: 16,
          mainAxisSpacing: 16,
        ),
        itemCount: products.length + 1, // +1 for loading indicator
        itemBuilder: (context, index) {
          if (index == products.length) {
            return ref.watch(productListProvider.notifier).isLoadingMore
                ? const Center(child: CircularProgressIndicator())
                : const SizedBox.shrink();
          }
          return ProductCard(product: products[index]);
        },
      );
    } else {
      return ListView.builder(
        controller: _scrollController,
        padding: const EdgeInsets.all(16),
        itemCount: products.length,
        itemBuilder: (context, index) {
          return ProductListTile(product: products[index]);
        },
      );
    }
  }

  void _showSearch(BuildContext context) {
    showSearch(
      context: context,
      delegate: ProductSearchDelegate(),
    );
  }

  void _showFilters(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (context) => const ProductFilterSheet(),
    );
  }
}

class ProductCard extends StatelessWidget {
  final Product product;

  const ProductCard({super.key, required this.product});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return GestureDetector(
      onTap: () => context.push('/products/${product.id}'),
      child: Card(
        clipBehavior: Clip.antiAlias,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image
            Expanded(
              flex: 3,
              child: Stack(
                fit: StackFit.expand,
                children: [
                  CachedNetworkImage(
                    imageUrl: product.imageUrl,
                    fit: BoxFit.cover,
                    placeholder: (_, __) => const _ImagePlaceholder(),
                    errorWidget: (_, __, ___) => const _ImagePlaceholder(),
                  ),

                  // Discount badge
                  if (product.discountPercent > 0)
                    Positioned(
                      top: 8,
                      left: 8,
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 8,
                          vertical: 4,
                        ),
                        decoration: BoxDecoration(
                          color: Colors.red,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          '-${product.discountPercent}%',
                          style: const TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                            fontSize: 12,
                          ),
                        ),
                      ),
                    ),

                  // Wishlist button
                  Positioned(
                    top: 8,
                    right: 8,
                    child: _WishlistButton(product: product),
                  ),

                  // Out of stock overlay
                  if (product.stockStatus == StockStatus.outOfStock)
                    Container(
                      color: Colors.black54,
                      alignment: Alignment.center,
                      child: const Text(
                        'Out of Stock',
                        style: TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                ],
              ),
            ),

            // Details
            Expanded(
              flex: 2,
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Name
                    Text(
                      product.name,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: theme.textTheme.titleSmall,
                    ),

                    const Spacer(),

                    // Rating
                    if (product.rating > 0)
                      Row(
                        children: [
                          Icon(
                            Icons.star,
                            size: 16,
                            color: Colors.amber[700],
                          ),
                          const SizedBox(width: 4),
                          Text(
                            product.rating.toStringAsFixed(1),
                            style: theme.textTheme.bodySmall,
                          ),
                          Text(
                            ' (${product.reviewCount})',
                            style: theme.textTheme.bodySmall?.copyWith(
                              color: Colors.grey,
                            ),
                          ),
                        ],
                      ),

                    const SizedBox(height: 4),

                    // Price
                    Row(
                      children: [
                        Text(
                          '৳${product.price.toStringAsFixed(0)}',
                          style: theme.textTheme.titleMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                            color: theme.primaryColor,
                          ),
                        ),
                        if (product.comparePrice != null) ...[
                          const SizedBox(width: 8),
                          Text(
                            '৳${product.comparePrice!.toStringAsFixed(0)}',
                            style: theme.textTheme.bodySmall?.copyWith(
                              decoration: TextDecoration.lineThrough,
                              color: Colors.grey,
                            ),
                          ),
                        ],
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _WishlistButton extends ConsumerWidget {
  final Product product;

  const _WishlistButton({required this.product});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final isInWishlist = ref.watch(wishlistProvider).contains(product.id);

    return Material(
      color: Colors.white,
      shape: const CircleBorder(),
      child: InkWell(
        customBorder: const CircleBorder(),
        onTap: () {
          ref.read(wishlistProvider.notifier).toggle(product.id);
        },
        child: Padding(
          padding: const EdgeInsets.all(8),
          child: Icon(
            isInWishlist ? Icons.favorite : Icons.favorite_border,
            size: 20,
            color: isInWishlist ? Colors.red : Colors.grey,
          ),
        ),
      ),
    );
  }
}
```

**Task 2: Product Provider (2h)**

```dart
// apps/customer/lib/features/products/presentation/providers/product_providers.dart
import 'package:riverpod_annotation/riverpod_annotation.dart';
import 'package:smart_dairy_core/core.dart';

part 'product_providers.g.dart';

@riverpod
class ProductList extends _$ProductList {
  int _currentPage = 1;
  bool _hasMore = true;
  bool isLoadingMore = false;

  @override
  Future<List<Product>> build() async {
    final filters = ref.watch(productFiltersProvider);
    return _fetchProducts(page: 1, filters: filters);
  }

  Future<List<Product>> _fetchProducts({
    required int page,
    required ProductFilters filters,
  }) async {
    final repository = ref.read(productRepositoryProvider);
    final result = await repository.getProducts(
      page: page,
      limit: 20,
      categoryId: filters.categoryId,
      search: filters.search,
      minPrice: filters.minPrice,
      maxPrice: filters.maxPrice,
      inStock: filters.inStockOnly,
      sort: filters.sortBy,
    );

    _hasMore = result.pagination.hasNext;
    _currentPage = page;

    return result.items;
  }

  Future<void> loadMore() async {
    if (!_hasMore || isLoadingMore) return;

    isLoadingMore = true;
    state = AsyncValue.data([...state.value ?? []]);

    try {
      final filters = ref.read(productFiltersProvider);
      final moreProducts = await _fetchProducts(
        page: _currentPage + 1,
        filters: filters,
      );

      state = AsyncValue.data([...state.value ?? [], ...moreProducts]);
    } catch (e, st) {
      // Don't replace data on load more error
      state = AsyncValue.data(state.value ?? []);
    } finally {
      isLoadingMore = false;
    }
  }

  Future<void> refresh() async {
    _currentPage = 1;
    _hasMore = true;
    ref.invalidateSelf();
  }
}

@riverpod
class ProductFilters extends _$ProductFilters {
  @override
  ProductFilters build() => const ProductFilters();

  void setCategory(int? categoryId) {
    state = state.copyWith(categoryId: categoryId);
    ref.invalidate(productListProvider);
  }

  void setSearch(String? search) {
    state = state.copyWith(search: search);
    ref.invalidate(productListProvider);
  }

  void setPriceRange(double? min, double? max) {
    state = state.copyWith(minPrice: min, maxPrice: max);
    ref.invalidate(productListProvider);
  }

  void setSortBy(ProductSort sort) {
    state = state.copyWith(sortBy: sort);
    ref.invalidate(productListProvider);
  }

  void setInStockOnly(bool value) {
    state = state.copyWith(inStockOnly: value);
    ref.invalidate(productListProvider);
  }

  void clear() {
    state = const ProductFilters();
    ref.invalidate(productListProvider);
  }
}

@freezed
class ProductFilters with _$ProductFilters {
  const factory ProductFilters({
    int? categoryId,
    String? search,
    double? minPrice,
    double? maxPrice,
    @Default(false) bool inStockOnly,
    @Default(ProductSort.nameAsc) ProductSort sortBy,
  }) = _ProductFilters;
}

enum ProductSort {
  nameAsc,
  nameDesc,
  priceAsc,
  priceDesc,
  newest,
  popular,
}
```

**Task 3: Category Navigation (2h)**

```dart
// apps/customer/lib/features/products/presentation/widgets/category_chips.dart
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

class CategoryChips extends ConsumerWidget {
  const CategoryChips({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final categoriesAsync = ref.watch(categoriesProvider);
    final selectedCategory = ref.watch(productFiltersProvider).categoryId;

    return categoriesAsync.when(
      data: (categories) => SizedBox(
        height: 50,
        child: ListView.builder(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 16),
          itemCount: categories.length + 1, // +1 for "All"
          itemBuilder: (context, index) {
            if (index == 0) {
              return Padding(
                padding: const EdgeInsets.only(right: 8),
                child: FilterChip(
                  selected: selectedCategory == null,
                  label: const Text('All'),
                  onSelected: (_) {
                    ref.read(productFiltersProvider.notifier).setCategory(null);
                  },
                ),
              );
            }

            final category = categories[index - 1];
            return Padding(
              padding: const EdgeInsets.only(right: 8),
              child: FilterChip(
                selected: selectedCategory == category.id,
                label: Text(category.name),
                avatar: category.icon != null
                    ? Icon(IconData(int.parse(category.icon!), fontFamily: 'MaterialIcons'), size: 18)
                    : null,
                onSelected: (_) {
                  ref.read(productFiltersProvider.notifier).setCategory(category.id);
                },
              ),
            );
          },
        ),
      ),
      loading: () => const SizedBox(
        height: 50,
        child: Center(child: CircularProgressIndicator(strokeWidth: 2)),
      ),
      error: (_, __) => const SizedBox.shrink(),
    );
  }
}
```

**End-of-Day 271 Deliverables:**

- [ ] Product list API with pagination
- [ ] Category API endpoints
- [ ] CDN configuration for images
- [ ] Product list screen UI
- [ ] Category chips navigation
- [ ] Product card component

---

### Days 272-280 Summary

**Day 272:** Category navigation deep links, product list skeleton loading
**Day 273:** Product detail screen, image gallery, related products
**Day 274:** Elasticsearch integration, search UI, search suggestions
**Day 275:** Hive offline caching, cache invalidation, sync indicators
**Day 276:** Filter sheet UI, price range slider, sort options
**Day 277:** Wishlist feature, wishlist screen, sync across devices
**Day 278:** Reviews display, review submission, rating component
**Day 279:** Promotional banners, featured products carousel
**Day 280:** Integration testing, performance optimization, documentation

---

## 4. Technical Specifications

### 4.1 Offline Cache Schema (Hive)

```dart
@HiveType(typeId: 1)
class CachedProduct {
  @HiveField(0)
  final int id;
  @HiveField(1)
  final String name;
  @HiveField(2)
  final double price;
  @HiveField(3)
  final String? imageUrl;
  @HiveField(4)
  final DateTime cachedAt;
  @HiveField(5)
  final String jsonData; // Full product JSON
}
```

### 4.2 Search Configuration

```yaml
# Elasticsearch index for products
index: smart_dairy_products
settings:
  number_of_shards: 1
  number_of_replicas: 1
  analysis:
    analyzer:
      bangla_analyzer:
        tokenizer: standard
        filter: [lowercase, bangla_stemmer]
mappings:
  properties:
    name:
      type: text
      analyzer: bangla_analyzer
    description:
      type: text
    price:
      type: float
    category_id:
      type: integer
    in_stock:
      type: boolean
```

---

## 5. Testing & Validation

### 5.1 Test Coverage Requirements

| Component | Required Coverage |
|-----------|------------------|
| Product providers | >85% |
| Offline cache | >80% |
| Search logic | >80% |
| UI widgets | >70% |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Slow image loading | Medium | Medium | Progressive loading, CDN |
| Search accuracy | Medium | Medium | Elasticsearch tuning |
| Cache stale data | Low | Medium | TTL, manual refresh |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Milestone 52 Auth | Phase 6 | Day 271 |
| Product data in Odoo | Phase 3 | Day 271 |
| Elasticsearch cluster | Infrastructure | Day 274 |

---

**Document Prepared By:** Smart Dairy Development Team
**Next Milestone:** Milestone 54 — Customer App Cart & Checkout
