# Milestone 38: SEO & Performance Optimization

## Document Control
| Field | Value |
|-------|-------|
| **Milestone Number** | 38 |
| **Title** | SEO & Performance Optimization |
| **Phase** | 4 - Public Website & CMS |
| **Timeline** | Days 221-230 (10 working days) |
| **Version** | 1.0 |
| **Status** | Planning |
| **Last Updated** | 2026-02-03 |

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
Implement comprehensive SEO optimization and performance enhancements to achieve Lighthouse scores >90 and ensure top search rankings for dairy-related keywords in Bangladesh.

### 1.2 Objectives
1. Implement meta tags and Open Graph tags for all pages
2. Add Schema.org structured data markup
3. Generate and submit XML sitemaps
4. Optimize Core Web Vitals (LCP, FID, CLS)
5. Implement image optimization pipeline
6. Configure CDN caching strategies
7. Achieve page load times under 2 seconds

### 1.3 Key Deliverables
- SEO meta tag management system
- Schema.org structured data for products, organization, articles
- XML sitemap with automatic updates
- Image optimization with WebP conversion
- CDN configuration for static assets
- Performance monitoring dashboard
- Lighthouse score >90 on all pages

### 1.4 Prerequisites
- Milestone 37 completed (all content pages live)
- All product and blog content published
- Google Analytics 4 configured
- CloudFlare CDN account active

### 1.5 Success Criteria
| Criteria | Target | Measurement |
|----------|--------|-------------|
| Lighthouse Performance | >90 | Google Lighthouse |
| Lighthouse SEO | >95 | Google Lighthouse |
| Page Load Time | <2 seconds | WebPageTest |
| Core Web Vitals | All Green | Google Search Console |
| Sitemap Submission | Indexed | Search Console |

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Requirement | Implementation |
|--------|--------|-------------|----------------|
| RFP-WEB-001 | RFP 4.2 | <2 second page load | CDN, caching, image optimization |
| RFP-WEB-002 | RFP 4.2 | 50,000+ monthly visitors | SEO optimization |
| BRD-MKT-001 | BRD 3.4 | Search visibility | Meta tags, structured data |
| BRD-MKT-002 | BRD 3.4 | Brand discoverability | Schema.org markup |
| SRS-SEO-001 | SRS 5.3 | Meta tag management | SEO mixin module |
| SRS-SEO-002 | SRS 5.3 | XML sitemap | Auto-generated sitemap |
| SRS-PERF-001 | SRS 6.1 | Performance optimization | Lazy loading, compression |

---

## 3. Day-by-Day Breakdown

### Day 221: SEO Foundation & Meta Tag System

**Sprint Focus**: Build core SEO infrastructure and meta tag management

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create SEO mixin model for meta tags | 3 | `seo.mixin` model |
| Implement meta tag inheritance hierarchy | 2 | Page-level overrides |
| Build SEO settings configuration | 2 | Global SEO defaults |
| Unit tests for SEO models | 1 | Test coverage |

**Code Sample - SEO Mixin Model**:
```python
# smart_website/models/seo_mixin.py
from odoo import models, fields, api

class SEOMixin(models.AbstractModel):
    _name = 'seo.mixin'
    _description = 'SEO Mixin for Website Pages'

    seo_title = fields.Char(
        string='SEO Title',
        translate=True,
        help='Page title for search engines (50-60 chars)'
    )
    seo_description = fields.Text(
        string='Meta Description',
        translate=True,
        help='Page description for search results (150-160 chars)'
    )
    seo_keywords = fields.Char(
        string='Meta Keywords',
        translate=True
    )
    seo_canonical_url = fields.Char(
        string='Canonical URL'
    )
    seo_no_index = fields.Boolean(
        string='No Index',
        default=False
    )
    seo_no_follow = fields.Boolean(
        string='No Follow',
        default=False
    )

    og_title = fields.Char(string='OG Title', translate=True)
    og_description = fields.Text(string='OG Description', translate=True)
    og_image = fields.Binary(string='OG Image')
    og_type = fields.Selection([
        ('website', 'Website'),
        ('article', 'Article'),
        ('product', 'Product'),
    ], default='website')

    twitter_card = fields.Selection([
        ('summary', 'Summary'),
        ('summary_large_image', 'Summary Large Image'),
    ], default='summary_large_image')

    @api.depends('name', 'seo_title')
    def _compute_display_seo_title(self):
        for record in self:
            record.display_seo_title = record.seo_title or record.name

    def get_meta_tags(self):
        """Generate meta tags dictionary for templates"""
        return {
            'title': self.seo_title or self.name,
            'description': self.seo_description or '',
            'keywords': self.seo_keywords or '',
            'canonical': self.seo_canonical_url,
            'robots': self._compute_robots_tag(),
            'og': {
                'title': self.og_title or self.seo_title or self.name,
                'description': self.og_description or self.seo_description,
                'type': self.og_type,
                'image': self._get_og_image_url(),
            },
            'twitter': {
                'card': self.twitter_card,
            }
        }

    def _compute_robots_tag(self):
        parts = []
        parts.append('noindex' if self.seo_no_index else 'index')
        parts.append('nofollow' if self.seo_no_follow else 'follow')
        return ', '.join(parts)
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create robots.txt controller | 2 | Dynamic robots.txt |
| Implement sitemap generator base | 3 | Sitemap framework |
| Configure Google Search Console | 2 | Verified property |
| Setup Bing Webmaster Tools | 1 | Verified property |

**Code Sample - Robots.txt Controller**:
```python
# smart_website/controllers/seo.py
from odoo import http
from odoo.http import request

class SEOController(http.Controller):

    @http.route('/robots.txt', type='http', auth='public')
    def robots_txt(self):
        """Generate dynamic robots.txt"""
        base_url = request.env['ir.config_parameter'].sudo().get_param('web.base.url')

        content = f"""User-agent: *
Allow: /
Disallow: /web/
Disallow: /my/
Disallow: /admin/
Disallow: /*?
Disallow: /shop/cart

# Sitemaps
Sitemap: {base_url}/sitemap.xml

# Crawl-delay for responsible crawling
Crawl-delay: 1
"""
        return request.make_response(
            content,
            headers=[('Content-Type', 'text/plain')]
        )
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create SEO meta tag template | 3 | Head section template |
| Implement Open Graph tags | 2 | OG meta tags |
| Add Twitter Card tags | 2 | Twitter meta tags |
| Create SEO preview component | 1 | SERP preview widget |

**Code Sample - Meta Tags Template**:
```xml
<!-- smart_website/views/templates/seo_head.xml -->
<template id="seo_meta_tags" name="SEO Meta Tags">
    <t t-set="meta" t-value="page.get_meta_tags() if page else {}"/>

    <!-- Primary Meta Tags -->
    <title t-esc="meta.get('title', 'Smart Dairy')"/>
    <meta name="description" t-att-content="meta.get('description')"/>
    <meta name="keywords" t-att-content="meta.get('keywords')"/>
    <meta name="robots" t-att-content="meta.get('robots', 'index, follow')"/>
    <t t-if="meta.get('canonical')">
        <link rel="canonical" t-att-href="meta.get('canonical')"/>
    </t>

    <!-- Open Graph -->
    <meta property="og:type" t-att-content="meta.get('og', {}).get('type', 'website')"/>
    <meta property="og:title" t-att-content="meta.get('og', {}).get('title')"/>
    <meta property="og:description" t-att-content="meta.get('og', {}).get('description')"/>
    <meta property="og:url" t-att-content="request.httprequest.url"/>
    <meta property="og:site_name" content="Smart Dairy"/>
    <t t-if="meta.get('og', {}).get('image')">
        <meta property="og:image" t-att-content="meta.get('og', {}).get('image')"/>
    </t>

    <!-- Twitter Card -->
    <meta name="twitter:card" t-att-content="meta.get('twitter', {}).get('card', 'summary_large_image')"/>
    <meta name="twitter:title" t-att-content="meta.get('og', {}).get('title')"/>
    <meta name="twitter:description" t-att-content="meta.get('og', {}).get('description')"/>
</template>
```

**Day 221 Deliverables**:
- [x] SEO mixin model with meta tag fields
- [x] Robots.txt dynamic generation
- [x] Meta tags template integrated
- [x] Search Console verified

---

### Day 222: XML Sitemap Generation

**Sprint Focus**: Automated XML sitemap with all content types

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement sitemap generator model | 3 | `sitemap.generator` |
| Create sitemap entry models | 2 | Sitemap URLs |
| Add sitemap index for large sites | 2 | Sitemap index |
| Configure automatic regeneration | 1 | Cron job |

**Code Sample - Sitemap Generator**:
```python
# smart_website/models/sitemap.py
from odoo import models, fields, api
from datetime import datetime
import xml.etree.ElementTree as ET

class SitemapGenerator(models.Model):
    _name = 'sitemap.generator'
    _description = 'XML Sitemap Generator'

    name = fields.Char(default='Main Sitemap')
    last_generated = fields.Datetime()
    sitemap_content = fields.Text()

    def generate_sitemap(self):
        """Generate XML sitemap for all public pages"""
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')

        urlset = ET.Element('urlset')
        urlset.set('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9')
        urlset.set('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1')

        # Static pages
        static_pages = [
            ('/', 1.0, 'weekly'),
            ('/about', 0.8, 'monthly'),
            ('/products', 0.9, 'daily'),
            ('/blog', 0.8, 'daily'),
            ('/contact', 0.7, 'monthly'),
            ('/services', 0.8, 'monthly'),
        ]

        for path, priority, changefreq in static_pages:
            self._add_url(urlset, base_url + path, priority, changefreq)

        # Product pages
        products = self.env['product.template'].sudo().search([
            ('website_published', '=', True)
        ])
        for product in products:
            self._add_url(
                urlset,
                f"{base_url}/product/{product.website_slug}",
                0.7,
                'weekly',
                product.write_date
            )

        # Blog posts
        posts = self.env['blog.post'].sudo().search([
            ('website_published', '=', True)
        ])
        for post in posts:
            self._add_url(
                urlset,
                f"{base_url}/blog/{post.blog_id.name}/{post.website_slug}",
                0.6,
                'monthly',
                post.write_date
            )

        # Category pages
        categories = self.env['product.public.category'].sudo().search([])
        for cat in categories:
            self._add_url(
                urlset,
                f"{base_url}/products/category/{cat.website_slug}",
                0.7,
                'weekly'
            )

        xml_content = ET.tostring(urlset, encoding='unicode', method='xml')
        xml_declaration = '<?xml version="1.0" encoding="UTF-8"?>\n'

        self.write({
            'sitemap_content': xml_declaration + xml_content,
            'last_generated': datetime.now()
        })

        return True

    def _add_url(self, urlset, loc, priority, changefreq, lastmod=None):
        url = ET.SubElement(urlset, 'url')
        ET.SubElement(url, 'loc').text = loc
        ET.SubElement(url, 'priority').text = str(priority)
        ET.SubElement(url, 'changefreq').text = changefreq
        if lastmod:
            ET.SubElement(url, 'lastmod').text = lastmod.strftime('%Y-%m-%d')
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create sitemap.xml controller | 2 | HTTP endpoint |
| Implement sitemap index controller | 2 | Index for large sitemaps |
| Submit sitemap to Search Console | 2 | Verified submission |
| Add sitemap monitoring | 2 | Error tracking |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Add hreflang tags for multilingual | 3 | Language alternates |
| Implement breadcrumb schema | 3 | BreadcrumbList JSON-LD |
| Create sitemap admin UI | 2 | Regenerate button |

**Day 222 Deliverables**:
- [x] XML sitemap generator
- [x] Sitemap controller endpoint
- [x] Sitemap submitted to Search Console
- [x] Hreflang tags for EN/BN

---

### Day 223: Schema.org Structured Data

**Sprint Focus**: Rich snippets with Schema.org markup

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement Organization schema | 2 | Company JSON-LD |
| Create Product schema generator | 3 | Product JSON-LD |
| Build Article schema for blog | 2 | BlogPosting JSON-LD |
| Add LocalBusiness schema | 1 | Location data |

**Code Sample - Schema.org Generator**:
```python
# smart_website/models/schema_org.py
from odoo import models, api
import json

class SchemaOrgGenerator(models.AbstractModel):
    _name = 'schema.org.generator'
    _description = 'Schema.org JSON-LD Generator'

    def get_organization_schema(self):
        """Generate Organization schema for Smart Dairy"""
        company = self.env.company
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')

        return {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Smart Dairy",
            "url": base_url,
            "logo": f"{base_url}/smart_website/static/img/logo.png",
            "description": "Bangladesh's leading integrated dairy company",
            "foundingDate": "2010",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Dhaka-Aricha Highway, Savar",
                "addressLocality": "Dhaka",
                "addressRegion": "Dhaka Division",
                "postalCode": "1340",
                "addressCountry": "BD"
            },
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+880-2-XXXXXXXX",
                "contactType": "customer service",
                "availableLanguage": ["English", "Bengali"]
            },
            "sameAs": [
                "https://www.facebook.com/smartdairybd",
                "https://www.linkedin.com/company/smartdairy"
            ]
        }

    def get_product_schema(self, product):
        """Generate Product schema for dairy products"""
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')

        schema = {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": product.name,
            "description": product.description_sale or product.name,
            "sku": product.default_code,
            "brand": {
                "@type": "Brand",
                "name": "Smart Dairy"
            },
            "manufacturer": {
                "@type": "Organization",
                "name": "Smart Dairy"
            },
            "url": f"{base_url}/product/{product.website_slug}",
            "category": product.categ_id.name if product.categ_id else "Dairy Products"
        }

        if product.image_1920:
            schema["image"] = f"{base_url}/web/image/product.template/{product.id}/image_1920"

        # Add offers if price available
        if product.list_price:
            schema["offers"] = {
                "@type": "Offer",
                "price": product.list_price,
                "priceCurrency": "BDT",
                "availability": "https://schema.org/InStock",
                "seller": {
                    "@type": "Organization",
                    "name": "Smart Dairy"
                }
            }

        # Add nutritional information if available
        if hasattr(product, 'nutrition_info') and product.nutrition_info:
            schema["nutrition"] = {
                "@type": "NutritionInformation",
                "calories": product.nutrition_info.get('calories'),
                "proteinContent": product.nutrition_info.get('protein'),
                "fatContent": product.nutrition_info.get('fat')
            }

        return schema

    def get_article_schema(self, post):
        """Generate Article schema for blog posts"""
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')

        return {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "headline": post.name,
            "description": post.subtitle or post.name,
            "datePublished": post.post_date.isoformat() if post.post_date else None,
            "dateModified": post.write_date.isoformat(),
            "author": {
                "@type": "Person",
                "name": post.author_id.name if post.author_id else "Smart Dairy"
            },
            "publisher": {
                "@type": "Organization",
                "name": "Smart Dairy",
                "logo": {
                    "@type": "ImageObject",
                    "url": f"{base_url}/smart_website/static/img/logo.png"
                }
            },
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": f"{base_url}/blog/{post.blog_id.name}/{post.website_slug}"
            }
        }

    def get_faq_schema(self, faqs):
        """Generate FAQPage schema"""
        return {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": faq.question,
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": faq.answer
                    }
                } for faq in faqs
            ]
        }
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement FAQ schema | 2 | FAQPage JSON-LD |
| Add BreadcrumbList schema | 2 | Navigation schema |
| Create WebSite schema | 2 | Site search box |
| Test with Rich Results Test | 2 | Validation report |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create JSON-LD injection template | 3 | Script tag template |
| Add schema to product pages | 2 | Product rich snippets |
| Add schema to blog pages | 2 | Article rich snippets |
| Add schema to FAQ page | 1 | FAQ rich snippets |

**Code Sample - JSON-LD Template**:
```xml
<!-- smart_website/views/templates/schema_jsonld.xml -->
<template id="schema_organization" name="Organization Schema">
    <script type="application/ld+json">
        <t t-esc="json.dumps(env['schema.org.generator'].get_organization_schema())"/>
    </script>
</template>

<template id="schema_product" name="Product Schema">
    <script type="application/ld+json">
        <t t-esc="json.dumps(env['schema.org.generator'].get_product_schema(product))"/>
    </script>
</template>

<template id="schema_article" name="Article Schema">
    <script type="application/ld+json">
        <t t-esc="json.dumps(env['schema.org.generator'].get_article_schema(post))"/>
    </script>
</template>
```

**Day 223 Deliverables**:
- [x] Organization JSON-LD on all pages
- [x] Product JSON-LD on product pages
- [x] BlogPosting JSON-LD on articles
- [x] FAQPage JSON-LD on FAQ section

---

### Day 224: Image Optimization Pipeline

**Sprint Focus**: Automated image optimization and WebP conversion

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create image optimization model | 3 | `image.optimizer` |
| Implement WebP conversion | 2 | Format conversion |
| Add responsive image generation | 2 | Multiple sizes |
| Configure quality settings | 1 | Compression levels |

**Code Sample - Image Optimizer**:
```python
# smart_website/models/image_optimizer.py
from odoo import models, fields, api
from PIL import Image
import io
import base64

class ImageOptimizer(models.Model):
    _name = 'image.optimizer'
    _description = 'Image Optimization Service'

    SIZES = {
        'thumbnail': (150, 150),
        'small': (300, 300),
        'medium': (600, 600),
        'large': (1200, 1200),
        'hero': (1920, 1080),
    }

    def optimize_image(self, image_data, quality=85, max_width=1920):
        """Optimize image: resize, compress, convert to WebP"""
        if not image_data:
            return None

        # Decode base64 image
        image_bytes = base64.b64decode(image_data)
        img = Image.open(io.BytesIO(image_bytes))

        # Convert RGBA to RGB if needed
        if img.mode == 'RGBA':
            background = Image.new('RGB', img.size, (255, 255, 255))
            background.paste(img, mask=img.split()[3])
            img = background

        # Resize if too large
        if img.width > max_width:
            ratio = max_width / img.width
            new_height = int(img.height * ratio)
            img = img.resize((max_width, new_height), Image.LANCZOS)

        # Save as WebP
        output = io.BytesIO()
        img.save(output, format='WebP', quality=quality, method=6)

        return base64.b64encode(output.getvalue()).decode()

    def generate_responsive_images(self, image_data):
        """Generate multiple sizes for responsive images"""
        if not image_data:
            return {}

        results = {}
        image_bytes = base64.b64decode(image_data)
        img = Image.open(io.BytesIO(image_bytes))

        for size_name, dimensions in self.SIZES.items():
            resized = img.copy()
            resized.thumbnail(dimensions, Image.LANCZOS)

            output = io.BytesIO()

            # Save as WebP
            if resized.mode == 'RGBA':
                background = Image.new('RGB', resized.size, (255, 255, 255))
                background.paste(resized, mask=resized.split()[3])
                resized = background

            resized.save(output, format='WebP', quality=85)
            results[size_name] = base64.b64encode(output.getvalue()).decode()

            # Also save original format
            output_orig = io.BytesIO()
            resized.save(output_orig, format='JPEG', quality=85)
            results[f"{size_name}_jpg"] = base64.b64encode(output_orig.getvalue()).decode()

        return results

    @api.model
    def optimize_all_product_images(self):
        """Batch optimize all product images"""
        products = self.env['product.template'].search([
            ('image_1920', '!=', False)
        ])

        for product in products:
            optimized = self.optimize_image(product.image_1920)
            if optimized:
                product.write({'image_1920_webp': optimized})

        return True
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Setup image processing cron | 2 | Scheduled optimization |
| Implement lazy loading controller | 3 | Intersection Observer |
| Configure CDN image URLs | 2 | CloudFlare integration |
| Add image format negotiation | 1 | Accept header check |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create responsive image component | 3 | Picture element |
| Implement lazy loading JS | 2 | IntersectionObserver |
| Add loading placeholder | 2 | Blur-up effect |
| Create image srcset template | 1 | Responsive srcset |

**Code Sample - Responsive Image Template**:
```xml
<!-- smart_website/views/templates/responsive_image.xml -->
<template id="responsive_image" name="Responsive Image">
    <picture class="responsive-image">
        <!-- WebP sources -->
        <source
            type="image/webp"
            t-att-srcset="'/web/image/%s/%s/image_1920_webp?width=300 300w, /web/image/%s/%s/image_1920_webp?width=600 600w, /web/image/%s/%s/image_1920_webp?width=1200 1200w' % (model, record_id, model, record_id, model, record_id)"
            sizes="(max-width: 600px) 300px, (max-width: 1200px) 600px, 1200px"
        />
        <!-- Fallback JPEG -->
        <source
            type="image/jpeg"
            t-att-srcset="'/web/image/%s/%s/image_1920?width=300 300w, /web/image/%s/%s/image_1920?width=600 600w, /web/image/%s/%s/image_1920?width=1200 1200w' % (model, record_id, model, record_id, model, record_id)"
            sizes="(max-width: 600px) 300px, (max-width: 1200px) 600px, 1200px"
        />
        <img
            t-att-src="'/web/image/%s/%s/image_1920?width=600' % (model, record_id)"
            t-att-alt="alt_text"
            loading="lazy"
            decoding="async"
            class="img-fluid"
        />
    </picture>
</template>
```

**Day 224 Deliverables**:
- [x] Image optimization service
- [x] WebP conversion pipeline
- [x] Responsive image component
- [x] Lazy loading implementation

---

### Day 225: Core Web Vitals - LCP Optimization

**Sprint Focus**: Optimize Largest Contentful Paint

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement server-side caching | 3 | Redis page cache |
| Optimize database queries | 3 | Query optimization |
| Add preload hints | 2 | Link preload headers |

**Code Sample - Page Cache**:
```python
# smart_website/models/page_cache.py
from odoo import models, api
import redis
import hashlib
import json

class PageCache(models.AbstractModel):
    _name = 'page.cache'
    _description = 'Page Cache Manager'

    @api.model
    def get_redis_client(self):
        return redis.Redis(
            host='localhost',
            port=6379,
            db=1,
            decode_responses=True
        )

    def cache_key(self, path, lang='en'):
        """Generate cache key for page"""
        key_string = f"page:{path}:{lang}"
        return hashlib.md5(key_string.encode()).hexdigest()

    def get_cached_page(self, path, lang='en'):
        """Retrieve cached page content"""
        client = self.get_redis_client()
        key = self.cache_key(path, lang)

        cached = client.get(key)
        if cached:
            return json.loads(cached)
        return None

    def cache_page(self, path, content, lang='en', ttl=3600):
        """Cache page content"""
        client = self.get_redis_client()
        key = self.cache_key(path, lang)

        client.setex(
            key,
            ttl,
            json.dumps({
                'content': content,
                'cached_at': fields.Datetime.now().isoformat()
            })
        )

    def invalidate_cache(self, path=None):
        """Invalidate page cache"""
        client = self.get_redis_client()

        if path:
            for lang in ['en', 'bn']:
                key = self.cache_key(path, lang)
                client.delete(key)
        else:
            # Invalidate all page cache
            keys = client.keys('page:*')
            if keys:
                client.delete(*keys)
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Configure CDN caching rules | 3 | CloudFlare page rules |
| Implement asset versioning | 2 | Cache busting |
| Add resource hints | 2 | Preconnect, prefetch |
| Setup edge caching | 1 | CDN configuration |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Optimize hero image loading | 3 | Priority hints |
| Implement critical CSS | 3 | Above-fold styles |
| Add font preloading | 2 | Font display swap |

**Code Sample - Critical CSS**:
```scss
/* smart_website/static/src/scss/critical.scss */
/* Critical CSS - Inline in head for above-the-fold content */

:root {
    --color-primary: #0066CC;
    --color-secondary: #2ECC71;
    --font-family-primary: 'Inter', sans-serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-family-primary);
    line-height: 1.6;
    color: #333;
}

.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding-top: 80px;
}

.hero__image {
    width: 100%;
    height: auto;
    object-fit: cover;
}

/* Font display optimization */
@font-face {
    font-family: 'Inter';
    font-display: swap;
    src: local('Inter'), url('/fonts/inter.woff2') format('woff2');
}
```

**Day 225 Deliverables**:
- [x] Redis page caching
- [x] CDN caching rules configured
- [x] Critical CSS extracted
- [x] Font preloading implemented

---

### Day 226: Core Web Vitals - FID & CLS Optimization

**Sprint Focus**: Optimize First Input Delay and Cumulative Layout Shift

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Optimize API response times | 3 | Endpoint optimization |
| Implement async data loading | 3 | Deferred content |
| Add server timing headers | 2 | Performance metrics |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement code splitting | 3 | Dynamic imports |
| Defer non-critical JS | 3 | Async/defer loading |
| Add Web Worker for heavy tasks | 2 | Background processing |

**Code Sample - Deferred Loading**:
```javascript
// smart_website/static/src/js/deferred_loader.js
class DeferredLoader {
    constructor() {
        this.loadedModules = new Set();
    }

    async loadModule(modulePath) {
        if (this.loadedModules.has(modulePath)) {
            return;
        }

        const module = await import(modulePath);
        this.loadedModules.add(modulePath);
        return module;
    }

    loadOnInteraction(selector, modulePath) {
        const elements = document.querySelectorAll(selector);

        elements.forEach(el => {
            const loadHandler = async () => {
                const module = await this.loadModule(modulePath);
                if (module.init) {
                    module.init(el);
                }
                el.removeEventListener('mouseenter', loadHandler);
                el.removeEventListener('focus', loadHandler);
            };

            el.addEventListener('mouseenter', loadHandler, { once: true });
            el.addEventListener('focus', loadHandler, { once: true });
        });
    }

    loadOnVisible(selector, modulePath) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(async entry => {
                if (entry.isIntersecting) {
                    const module = await this.loadModule(modulePath);
                    if (module.init) {
                        module.init(entry.target);
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '100px' });

        document.querySelectorAll(selector).forEach(el => {
            observer.observe(el);
        });
    }
}

// Initialize deferred loading
const deferredLoader = new DeferredLoader();

// Load heavy modules only when needed
deferredLoader.loadOnVisible('.product-gallery', '/smart_website/static/src/js/gallery.js');
deferredLoader.loadOnInteraction('.contact-form', '/smart_website/static/src/js/form_validation.js');
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Add explicit dimensions to images | 2 | Width/height attributes |
| Reserve space for dynamic content | 3 | Skeleton loaders |
| Optimize font loading strategy | 2 | Font display swap |
| Fix layout shift issues | 1 | CLS debugging |

**Code Sample - Skeleton Loader**:
```scss
/* smart_website/static/src/scss/components/_skeleton.scss */
.skeleton {
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e0e0e0 50%,
        #f0f0f0 75%
    );
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 4px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.skeleton-text {
    height: 1em;
    margin-bottom: 0.5em;

    &--title { width: 60%; height: 1.5em; }
    &--body { width: 100%; }
    &--short { width: 40%; }
}

.skeleton-image {
    aspect-ratio: 16/9;
    width: 100%;
}

.skeleton-card {
    padding: 1rem;

    .skeleton-image { margin-bottom: 1rem; }
}

/* Product card skeleton */
.product-card-skeleton {
    .skeleton-image { aspect-ratio: 1/1; }
    .skeleton-text--title { margin-top: 0.5rem; }
    .skeleton-text--price { width: 30%; }
}
```

**Day 226 Deliverables**:
- [x] Code splitting implemented
- [x] Skeleton loaders for dynamic content
- [x] CLS issues fixed
- [x] Deferred JS loading

---

### Day 227: CDN & Caching Strategy

**Sprint Focus**: Global content delivery optimization

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Configure cache headers | 3 | HTTP cache control |
| Implement ETag support | 2 | Conditional requests |
| Add Vary headers | 2 | Content negotiation |
| Setup stale-while-revalidate | 1 | SWR caching |

**Code Sample - Cache Headers Middleware**:
```python
# smart_website/controllers/cache_middleware.py
from odoo import http
from odoo.http import request
import hashlib

class CacheMiddleware:

    STATIC_CACHE_AGE = 31536000  # 1 year
    DYNAMIC_CACHE_AGE = 3600     # 1 hour
    API_CACHE_AGE = 300          # 5 minutes

    @staticmethod
    def add_cache_headers(response, cache_type='dynamic', etag_data=None):
        """Add appropriate cache headers to response"""

        if cache_type == 'static':
            response.headers['Cache-Control'] = f'public, max-age={CacheMiddleware.STATIC_CACHE_AGE}, immutable'
        elif cache_type == 'dynamic':
            response.headers['Cache-Control'] = f'public, max-age={CacheMiddleware.DYNAMIC_CACHE_AGE}, stale-while-revalidate=86400'
        elif cache_type == 'api':
            response.headers['Cache-Control'] = f'public, max-age={CacheMiddleware.API_CACHE_AGE}'
        elif cache_type == 'private':
            response.headers['Cache-Control'] = 'private, no-cache'

        # Add ETag if data provided
        if etag_data:
            etag = hashlib.md5(str(etag_data).encode()).hexdigest()
            response.headers['ETag'] = f'"{etag}"'

        # Add Vary header for content negotiation
        response.headers['Vary'] = 'Accept-Encoding, Accept-Language'

        return response


class WebsiteController(http.Controller):

    @http.route('/page/<path:page>', type='http', auth='public', website=True)
    def render_page(self, page, **kwargs):
        # Check for conditional request
        if_none_match = request.httprequest.headers.get('If-None-Match')

        # Get page content
        page_content = self._get_page_content(page)
        etag = hashlib.md5(page_content.encode()).hexdigest()

        # Return 304 if content unchanged
        if if_none_match and if_none_match.strip('"') == etag:
            return request.make_response('', status=304)

        response = request.render('smart_website.page_template', {
            'content': page_content
        })

        return CacheMiddleware.add_cache_headers(
            response,
            cache_type='dynamic',
            etag_data=page_content
        )
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Configure CloudFlare page rules | 3 | CDN optimization |
| Setup origin shield | 2 | Edge caching |
| Implement cache purge API | 2 | Invalidation system |
| Add cache warming script | 1 | Pre-population |

**Code Sample - CloudFlare Integration**:
```python
# smart_website/services/cloudflare.py
import requests
from odoo import models, api

class CloudFlareService(models.AbstractModel):
    _name = 'cloudflare.service'
    _description = 'CloudFlare CDN Service'

    @api.model
    def get_config(self):
        params = self.env['ir.config_parameter'].sudo()
        return {
            'zone_id': params.get_param('cloudflare.zone_id'),
            'api_token': params.get_param('cloudflare.api_token'),
            'api_url': 'https://api.cloudflare.com/client/v4'
        }

    def purge_cache(self, urls=None, tags=None, purge_all=False):
        """Purge CloudFlare cache"""
        config = self.get_config()

        headers = {
            'Authorization': f'Bearer {config["api_token"]}',
            'Content-Type': 'application/json'
        }

        endpoint = f'{config["api_url"]}/zones/{config["zone_id"]}/purge_cache'

        if purge_all:
            data = {'purge_everything': True}
        elif urls:
            data = {'files': urls}
        elif tags:
            data = {'tags': tags}
        else:
            return False

        response = requests.post(endpoint, headers=headers, json=data)
        return response.json().get('success', False)

    def warm_cache(self, urls):
        """Pre-warm cache by requesting URLs"""
        for url in urls:
            try:
                requests.get(url, timeout=10)
            except Exception:
                pass
        return True
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement Service Worker | 4 | Offline caching |
| Add asset precaching | 2 | Critical assets |
| Configure runtime caching | 2 | API responses |

**Code Sample - Service Worker**:
```javascript
// smart_website/static/src/js/sw.js
const CACHE_VERSION = 'v1.0.0';
const STATIC_CACHE = `smart-dairy-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `smart-dairy-dynamic-${CACHE_VERSION}`;

const STATIC_ASSETS = [
    '/',
    '/smart_website/static/src/css/main.css',
    '/smart_website/static/src/js/main.js',
    '/smart_website/static/img/logo.png',
    '/offline.html'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Activate event - cleanup old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(key => key !== STATIC_CACHE && key !== DYNAMIC_CACHE)
                    .map(key => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Static assets - cache first
    if (STATIC_ASSETS.includes(url.pathname) || url.pathname.match(/\.(css|js|png|jpg|webp|woff2)$/)) {
        event.respondWith(
            caches.match(request)
                .then(cached => cached || fetchAndCache(request, STATIC_CACHE))
        );
        return;
    }

    // HTML pages - network first with cache fallback
    if (request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const clone = response.clone();
                    caches.open(DYNAMIC_CACHE)
                        .then(cache => cache.put(request, clone));
                    return response;
                })
                .catch(() => caches.match(request) || caches.match('/offline.html'))
        );
        return;
    }

    // Default - stale while revalidate
    event.respondWith(
        caches.match(request)
            .then(cached => {
                const fetchPromise = fetch(request)
                    .then(response => {
                        caches.open(DYNAMIC_CACHE)
                            .then(cache => cache.put(request, response.clone()));
                        return response;
                    });
                return cached || fetchPromise;
            })
    );
});

async function fetchAndCache(request, cacheName) {
    const response = await fetch(request);
    const cache = await caches.open(cacheName);
    cache.put(request, response.clone());
    return response;
}
```

**Day 227 Deliverables**:
- [x] Cache headers configured
- [x] CloudFlare integration
- [x] Service Worker implemented
- [x] Cache purge API

---

### Day 228: Performance Monitoring & Analytics

**Sprint Focus**: Real-time performance tracking

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Create performance metrics model | 3 | Data storage |
| Implement metrics collection API | 3 | Endpoint for RUM |
| Add server timing tracking | 2 | Backend metrics |

**Code Sample - Performance Metrics**:
```python
# smart_website/models/performance_metrics.py
from odoo import models, fields, api
from datetime import datetime, timedelta

class PerformanceMetric(models.Model):
    _name = 'performance.metric'
    _description = 'Website Performance Metrics'
    _order = 'create_date desc'

    page_url = fields.Char(required=True, index=True)
    metric_type = fields.Selection([
        ('lcp', 'Largest Contentful Paint'),
        ('fid', 'First Input Delay'),
        ('cls', 'Cumulative Layout Shift'),
        ('ttfb', 'Time to First Byte'),
        ('fcp', 'First Contentful Paint'),
    ], required=True)
    value = fields.Float(required=True)
    rating = fields.Selection([
        ('good', 'Good'),
        ('needs_improvement', 'Needs Improvement'),
        ('poor', 'Poor'),
    ])

    # Context
    device_type = fields.Selection([
        ('mobile', 'Mobile'),
        ('tablet', 'Tablet'),
        ('desktop', 'Desktop'),
    ])
    connection_type = fields.Char()
    user_agent = fields.Char()
    country = fields.Char()

    @api.model
    def record_metric(self, data):
        """Record performance metric from frontend"""
        rating = self._calculate_rating(data['metric_type'], data['value'])

        return self.create({
            'page_url': data.get('url'),
            'metric_type': data.get('metric_type'),
            'value': data.get('value'),
            'rating': rating,
            'device_type': data.get('device_type'),
            'connection_type': data.get('connection'),
            'user_agent': data.get('user_agent'),
            'country': data.get('country'),
        })

    def _calculate_rating(self, metric_type, value):
        """Calculate rating based on Core Web Vitals thresholds"""
        thresholds = {
            'lcp': {'good': 2500, 'poor': 4000},
            'fid': {'good': 100, 'poor': 300},
            'cls': {'good': 0.1, 'poor': 0.25},
            'ttfb': {'good': 800, 'poor': 1800},
            'fcp': {'good': 1800, 'poor': 3000},
        }

        if metric_type not in thresholds:
            return 'good'

        thresh = thresholds[metric_type]
        if value <= thresh['good']:
            return 'good'
        elif value <= thresh['poor']:
            return 'needs_improvement'
        return 'poor'

    @api.model
    def get_dashboard_stats(self, days=7):
        """Get aggregated stats for dashboard"""
        date_from = datetime.now() - timedelta(days=days)

        metrics = self.search([
            ('create_date', '>=', date_from)
        ])

        stats = {}
        for metric_type in ['lcp', 'fid', 'cls', 'ttfb', 'fcp']:
            type_metrics = metrics.filtered(lambda m: m.metric_type == metric_type)
            if type_metrics:
                values = type_metrics.mapped('value')
                stats[metric_type] = {
                    'avg': sum(values) / len(values),
                    'p75': sorted(values)[int(len(values) * 0.75)] if values else 0,
                    'good_pct': len(type_metrics.filtered(lambda m: m.rating == 'good')) / len(type_metrics) * 100,
                }

        return stats
```

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Setup Google Analytics 4 events | 3 | Custom events |
| Configure Search Console API | 2 | Performance data |
| Create performance dashboard | 3 | Admin reporting |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Implement Web Vitals tracking | 3 | Frontend metrics |
| Add Real User Monitoring | 3 | RUM collection |
| Create performance overlay | 2 | Debug mode |

**Code Sample - Web Vitals Tracking**:
```javascript
// smart_website/static/src/js/web_vitals.js
import { onLCP, onFID, onCLS, onTTFB, onFCP } from 'web-vitals';

class PerformanceTracker {
    constructor() {
        this.metrics = {};
        this.endpoint = '/api/v1/performance/metrics';
        this.init();
    }

    init() {
        // Track Core Web Vitals
        onLCP(metric => this.recordMetric('lcp', metric));
        onFID(metric => this.recordMetric('fid', metric));
        onCLS(metric => this.recordMetric('cls', metric));
        onTTFB(metric => this.recordMetric('ttfb', metric));
        onFCP(metric => this.recordMetric('fcp', metric));

        // Send metrics before page unload
        window.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                this.sendMetrics();
            }
        });
    }

    recordMetric(name, metric) {
        this.metrics[name] = {
            metric_type: name,
            value: metric.value,
            url: window.location.pathname,
            device_type: this.getDeviceType(),
            connection: this.getConnectionType(),
            user_agent: navigator.userAgent,
        };
    }

    getDeviceType() {
        const width = window.innerWidth;
        if (width < 768) return 'mobile';
        if (width < 1024) return 'tablet';
        return 'desktop';
    }

    getConnectionType() {
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        return connection ? connection.effectiveType : 'unknown';
    }

    async sendMetrics() {
        const metricsArray = Object.values(this.metrics);
        if (metricsArray.length === 0) return;

        try {
            // Use sendBeacon for reliable delivery
            const blob = new Blob([JSON.stringify(metricsArray)], { type: 'application/json' });
            navigator.sendBeacon(this.endpoint, blob);
        } catch (error) {
            console.error('Failed to send performance metrics:', error);
        }
    }
}

// Initialize tracker
new PerformanceTracker();
```

**Day 228 Deliverables**:
- [x] Performance metrics model
- [x] Web Vitals tracking
- [x] Performance dashboard
- [x] GA4 custom events

---

### Day 229: Lighthouse Optimization & Testing

**Sprint Focus**: Achieve >90 Lighthouse scores

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Run Lighthouse audits | 2 | Baseline scores |
| Fix server-side issues | 4 | Backend optimizations |
| Implement HTTP/2 push | 2 | Resource hints |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Setup Lighthouse CI | 3 | Automated testing |
| Fix accessibility issues | 3 | A11y improvements |
| Optimize third-party scripts | 2 | External resource loading |

**Code Sample - Lighthouse CI Config**:
```javascript
// lighthouserc.js
module.exports = {
    ci: {
        collect: {
            url: [
                'http://localhost:8069/',
                'http://localhost:8069/products',
                'http://localhost:8069/blog',
                'http://localhost:8069/contact',
                'http://localhost:8069/about',
            ],
            numberOfRuns: 3,
            settings: {
                preset: 'desktop',
            },
        },
        assert: {
            assertions: {
                'categories:performance': ['error', { minScore: 0.9 }],
                'categories:accessibility': ['error', { minScore: 0.9 }],
                'categories:best-practices': ['error', { minScore: 0.9 }],
                'categories:seo': ['error', { minScore: 0.95 }],
                'first-contentful-paint': ['warn', { maxNumericValue: 1800 }],
                'largest-contentful-paint': ['error', { maxNumericValue: 2500 }],
                'cumulative-layout-shift': ['error', { maxNumericValue: 0.1 }],
                'total-blocking-time': ['warn', { maxNumericValue: 300 }],
            },
        },
        upload: {
            target: 'temporary-public-storage',
        },
    },
};
```

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Fix render-blocking resources | 3 | CSS/JS optimization |
| Optimize animations | 2 | GPU acceleration |
| Fix color contrast issues | 2 | Accessibility |
| Final Lighthouse audit | 1 | Score verification |

**Day 229 Deliverables**:
- [x] Lighthouse CI pipeline
- [x] All pages >90 performance
- [x] All pages >95 SEO score
- [x] Accessibility fixes applied

---

### Day 230: Final Optimization & Documentation

**Sprint Focus**: Polish and handoff

#### Dev 1 - Backend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Performance regression testing | 3 | Test suite |
| Documentation - SEO setup | 3 | Admin guide |
| Knowledge transfer session | 2 | Team training |

#### Dev 2 - Full-Stack Developer (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| CDN configuration documentation | 2 | Operations guide |
| Cache invalidation procedures | 2 | Runbook |
| Monitoring alert setup | 2 | PagerDuty/Slack |
| Final testing | 2 | Verification |

#### Dev 3 - Frontend Lead (8 hours)
| Task | Hours | Deliverable |
|------|-------|-------------|
| Performance budget documentation | 2 | Guidelines |
| Component optimization guide | 3 | Best practices |
| Cross-browser testing | 2 | Compatibility |
| Milestone sign-off | 1 | Completion |

**Day 230 Deliverables**:
- [x] Performance test suite
- [x] SEO administration guide
- [x] CDN operations documentation
- [x] Milestone 38 complete

---

## 4. Technical Specifications

### 4.1 SEO Configuration
```yaml
# SEO Settings
meta_tags:
  title_suffix: " | Smart Dairy Bangladesh"
  title_max_length: 60
  description_max_length: 160

open_graph:
  default_type: website
  default_image: /smart_website/static/img/og-default.jpg
  image_dimensions: 1200x630

twitter:
  default_card: summary_large_image
  site_handle: "@smartdairybd"

structured_data:
  organization: true
  local_business: true
  product: true
  article: true
  faq: true
  breadcrumb: true
```

### 4.2 Performance Targets
| Metric | Target | Threshold |
|--------|--------|-----------|
| Lighthouse Performance | >90 | 85 minimum |
| Lighthouse SEO | >95 | 90 minimum |
| LCP | <2.5s | <4s |
| FID | <100ms | <300ms |
| CLS | <0.1 | <0.25 |
| TTFB | <600ms | <1.8s |
| Page Size | <1MB | <2MB |
| Requests | <50 | <100 |

### 4.3 Caching Strategy
| Asset Type | Cache Duration | Strategy |
|------------|----------------|----------|
| Static JS/CSS | 1 year | Immutable with hash |
| Images | 1 year | CDN with versioning |
| HTML Pages | 1 hour | SWR with CDN |
| API Responses | 5 minutes | Private cache |
| User Data | No cache | Private, no-store |

---

## 5. Testing & Validation

### 5.1 SEO Testing
- [ ] Meta tags present on all pages
- [ ] Open Graph tags validated
- [ ] Twitter Cards validated
- [ ] Schema.org validated (Rich Results Test)
- [ ] Sitemap accessible and valid
- [ ] Robots.txt correct
- [ ] Canonical URLs set
- [ ] Hreflang tags correct

### 5.2 Performance Testing
- [ ] Lighthouse score >90 (all pages)
- [ ] WebPageTest <2s load time
- [ ] Core Web Vitals all green
- [ ] Mobile performance verified
- [ ] 3G connection tested
- [ ] Image optimization verified

### 5.3 Tools
- Google Lighthouse
- Google PageSpeed Insights
- WebPageTest.org
- Google Rich Results Test
- Schema.org Validator
- GTmetrix

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Third-party scripts slow | Medium | High | Defer loading, self-host critical |
| CDN configuration issues | Low | High | Staged rollout, fallback origin |
| Schema markup errors | Medium | Medium | Automated validation |
| Cache invalidation failures | Low | Medium | Manual purge procedures |
| Mobile performance issues | Medium | High | Mobile-first testing |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites from Milestone 37
- All content pages published
- Contact forms functional
- FAQ content complete

### 7.2 Handoffs to Milestone 39
- SEO infrastructure ready for multilingual
- Performance baseline established
- CDN configured for language variants

### 7.3 External Dependencies
- CloudFlare CDN account
- Google Search Console access
- Google Analytics 4 property
- Bing Webmaster Tools access

---

## Milestone Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Dev 1 - Backend Lead | | | |
| Dev 2 - Full-Stack | | | |
| Dev 3 - Frontend Lead | | | |
| Project Manager | | | |
| Product Owner | | | |

---

*End of Milestone 38 Documentation*
