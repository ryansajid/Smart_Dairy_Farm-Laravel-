# Milestone 36: Blog & News Management

## Smart Dairy Digital Smart Portal + ERP — Phase 4: Foundation - Public Website & CMS

| Field                | Detail                                                           |
| -------------------- | ---------------------------------------------------------------- |
| **Milestone**        | 36 of 40 (6 of 10 in Phase 4)                                    |
| **Title**            | Blog & News Management                                           |
| **Phase**            | Phase 4 — Foundation (Public Website & CMS)                      |
| **Days**             | Days 201–210 (of 250)                                            |
| **Version**          | 1.0                                                              |
| **Status**           | Draft                                                            |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend Lead) |
| **Last Updated**     | 2026-02-03                                                       |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build a comprehensive blog and news platform for content marketing, SEO, and customer engagement. By Day 210, the blog system will be fully functional with categories, tags, author profiles, social sharing, RSS feeds, and 30+ initial articles imported and published.

### 1.2 Objectives

1. Configure Odoo Blog module with Smart Dairy branding
2. Create blog post template with optimal reading experience
3. Implement category and tag taxonomy system
4. Build author profiles with bio and social links
5. Create featured images and media embedding support
6. Implement social sharing buttons with analytics
7. Build related posts recommendation component
8. Generate RSS feeds for syndication
9. Import and publish 30+ initial articles
10. Conduct Milestone 36 review, demo, and retrospective

### 1.3 Key Deliverables

| # | Deliverable                           | Owner  | Format                    |
|---|---------------------------------------|--------|---------------------------|
| 1 | Blog module configuration             | Dev 1  | Odoo configuration        |
| 2 | Blog post template                    | Dev 3  | QWeb + CSS                |
| 3 | Category/tag system                   | Dev 1  | Odoo models               |
| 4 | Author profiles                       | Dev 1  | Model + QWeb              |
| 5 | Featured image handling               | Dev 2  | Image optimization        |
| 6 | Social sharing buttons                | Dev 2  | JS + APIs                 |
| 7 | Related posts component               | Dev 1  | Algorithm + QWeb          |
| 8 | RSS feed generation                   | Dev 2  | XML generation            |
| 9 | Blog listing page                     | Dev 3  | QWeb + CSS                |
| 10| Content import (30+ posts)            | Dev 1  | Python script             |
| 11| Blog search functionality             | Dev 2  | Search integration        |
| 12| Milestone 36 review report            | All    | Markdown                  |

### 1.4 Success Criteria

- [ ] Blog system fully operational
- [ ] 30+ articles published across categories
- [ ] Author profiles displaying on posts
- [ ] Social sharing functional (FB, Twitter, LinkedIn, WhatsApp)
- [ ] RSS feed valid and accessible
- [ ] Related posts showing relevant content
- [ ] Blog pages loading <2 seconds

---

## 2. Requirement Traceability Matrix

| Req ID        | Source     | Requirement Description                           | Day(s)  | Task Reference              |
| ------------- | ---------- | ------------------------------------------------- | ------- | --------------------------- |
| RFP-WEB-008   | RFP §4     | Blog platform with 30+ articles                   | 201-209 | Blog system, content        |
| BRD-CUST-001  | BRD §4     | Customer engagement through content               | 201-210 | Blog features               |
| SRS-WEB-007   | SRS §4.7   | Blog with categories and tags                     | 202-203 | Taxonomy system             |
| SRS-WEB-008   | SRS §4.8   | Social sharing integration                        | 206     | Social buttons              |
| SRS-SEO-001   | SRS §5.2   | Blog SEO optimization                             | 201-210 | Meta tags, structure        |

---

## 3. Day-by-Day Breakdown

---

### Day 201 — Blog Module Configuration

**Objective:** Configure Odoo Blog module with Smart Dairy customizations and branding.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Install and configure Odoo Blog module — 2h
2. Extend blog post model with custom fields — 3h
3. Create blog categories (topics) — 2h
4. Configure blog SEO fields — 1h

```python
# Blog Post Extension
from odoo import models, fields, api

class BlogPost(models.Model):
    _inherit = 'blog.post'

    # Reading experience
    reading_time = fields.Integer('Reading Time (min)', compute='_compute_reading_time', store=True)
    word_count = fields.Integer('Word Count', compute='_compute_reading_time', store=True)

    # Featured content
    is_featured = fields.Boolean('Featured Post')
    featured_order = fields.Integer('Featured Order', default=10)

    # Enhanced SEO
    focus_keyword = fields.Char('Focus Keyword')
    meta_robots = fields.Selection([
        ('index,follow', 'Index, Follow'),
        ('noindex,follow', 'No Index, Follow'),
        ('index,nofollow', 'Index, No Follow'),
        ('noindex,nofollow', 'No Index, No Follow'),
    ], default='index,follow', string='Meta Robots')

    # Content organization
    content_type = fields.Selection([
        ('article', 'Article'),
        ('recipe', 'Recipe'),
        ('news', 'News'),
        ('guide', 'Guide'),
        ('case_study', 'Case Study'),
    ], default='article', string='Content Type')

    # Engagement
    view_count = fields.Integer('Views', default=0)
    share_count = fields.Integer('Shares', default=0)

    # Related content
    related_product_ids = fields.Many2many('product.template', string='Related Products')

    @api.depends('content')
    def _compute_reading_time(self):
        for post in self:
            if post.content:
                # Strip HTML and count words
                import re
                text = re.sub('<[^<]+?>', '', post.content or '')
                words = len(text.split())
                post.word_count = words
                post.reading_time = max(1, words // 200)  # ~200 words per minute
            else:
                post.word_count = 0
                post.reading_time = 0

    def action_increment_view(self):
        """Increment view count (called from controller)"""
        self.sudo().write({'view_count': self.view_count + 1})


# Blog Categories (extend blog.blog as topics/categories)
class BlogBlog(models.Model):
    _inherit = 'blog.blog'

    # Category enhancements
    description = fields.Text('Description', translate=True)
    icon = fields.Char('Icon Class')
    color = fields.Char('Color', default='#0066CC')
    image = fields.Binary('Category Image', attachment=True)

    # SEO
    meta_title = fields.Char('Meta Title', translate=True)
    meta_description = fields.Text('Meta Description', translate=True)

    # Display
    posts_per_page = fields.Integer('Posts Per Page', default=12)
    featured_post_ids = fields.Many2many('blog.post', string='Featured Posts')
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Configure blog URL structure — 2h
2. Set up blog image optimization — 3h
3. Create blog analytics tracking — 3h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design blog listing page layout — 4h
2. Create blog sidebar components — 4h

```scss
// _blog.scss - Blog Styling

.sd-blog-listing {
    padding: var(--spacing-12) 0;
}

.sd-blog-header {
    text-align: center;
    margin-bottom: var(--spacing-12);

    .blog-title {
        font-size: var(--font-size-4xl);
        font-weight: 700;
        margin-bottom: var(--spacing-4);
    }

    .blog-description {
        font-size: var(--font-size-lg);
        color: var(--color-text-secondary);
        max-width: 600px;
        margin: 0 auto;
    }
}

.sd-blog-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-8);

    @include respond-to('md') {
        grid-template-columns: repeat(2, 1fr);
    }

    @include respond-to('lg') {
        grid-template-columns: repeat(3, 1fr);
    }
}

.sd-blog-card {
    background: var(--color-bg-primary);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: transform var(--transition-normal), box-shadow var(--transition-normal);

    &:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);

        .blog-card-image img {
            transform: scale(1.05);
        }
    }

    .blog-card-image {
        height: 200px;
        overflow: hidden;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }
    }

    .blog-card-content {
        padding: var(--spacing-6);
    }

    .blog-card-meta {
        display: flex;
        align-items: center;
        gap: var(--spacing-4);
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-3);

        .meta-category {
            color: var(--color-primary);
            font-weight: 500;
        }

        .meta-date {
            display: flex;
            align-items: center;
            gap: var(--spacing-1);
        }
    }

    .blog-card-title {
        font-size: var(--font-size-xl);
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: var(--spacing-3);

        a {
            color: var(--color-text-primary);
            text-decoration: none;

            &:hover {
                color: var(--color-primary);
            }
        }
    }

    .blog-card-excerpt {
        color: var(--color-text-secondary);
        line-height: var(--line-height-relaxed);
        margin-bottom: var(--spacing-4);

        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: var(--spacing-4);
        border-top: 1px solid var(--color-border-light);
    }

    .blog-card-author {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);

        .author-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .author-name {
            font-size: var(--font-size-sm);
            font-weight: 500;
        }
    }

    .blog-card-read-time {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
    }
}

// Featured Post Card (larger)
.sd-blog-featured {
    grid-column: 1 / -1;

    @include respond-to('lg') {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .blog-card-image {
        height: 300px;

        @include respond-to('lg') {
            height: 100%;
            min-height: 400px;
        }
    }

    .blog-card-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: var(--spacing-10);
    }

    .blog-card-title {
        font-size: var(--font-size-3xl);
    }
}
```

**End-of-Day 201 Deliverables:**

- [ ] Blog module installed and configured
- [ ] Blog post model extended
- [ ] Blog listing page designed
- [ ] Blog URL structure set up

---

### Day 202 — Blog Post Template

**Objective:** Create the individual blog post page template with optimal reading experience.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create blog post controller enhancements — 3h
2. Build table of contents generation — 2.5h
3. Implement reading progress indicator — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create reading progress bar JavaScript — 2.5h
2. Build sticky table of contents — 3h
3. Implement smooth scroll to headings — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design blog post layout — 3h
2. Create typography for long-form content — 3h
3. Style code blocks and quotes — 2h

```scss
// Blog Post Page Styling

.sd-blog-post {
    padding: var(--spacing-12) 0;
}

.sd-post-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto var(--spacing-10);

    .post-category {
        display: inline-block;
        padding: var(--spacing-1) var(--spacing-4);
        background: var(--color-primary);
        color: white;
        border-radius: var(--radius-full);
        font-size: var(--font-size-sm);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: var(--spacing-4);
    }

    .post-title {
        font-size: var(--font-size-4xl);
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: var(--spacing-6);

        @include respond-to('md') {
            font-size: var(--font-size-5xl);
        }
    }

    .post-meta {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--spacing-6);
        color: var(--color-text-secondary);

        .meta-author {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);

            img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }
        }
    }
}

.sd-post-featured-image {
    max-width: 1000px;
    margin: 0 auto var(--spacing-10);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);

    img {
        width: 100%;
        height: auto;
    }
}

.sd-post-content {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-10);
    max-width: 1200px;
    margin: 0 auto;

    @include respond-to('lg') {
        grid-template-columns: 200px 1fr 200px;
    }
}

.sd-post-toc {
    position: sticky;
    top: 100px;
    align-self: start;

    @media (max-width: 991px) {
        display: none;
    }

    .toc-title {
        font-size: var(--font-size-sm);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-4);
    }

    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
        border-left: 2px solid var(--color-border-light);

        li {
            padding-left: var(--spacing-4);
            margin-bottom: var(--spacing-2);

            a {
                font-size: var(--font-size-sm);
                color: var(--color-text-secondary);
                text-decoration: none;
                transition: color var(--transition-fast);

                &:hover, &.active {
                    color: var(--color-primary);
                }
            }

            &.active {
                border-left: 2px solid var(--color-primary);
                margin-left: -2px;
            }
        }
    }
}

.sd-post-article {
    max-width: 700px;

    // Typography for articles
    h2 {
        font-size: var(--font-size-2xl);
        font-weight: 700;
        margin-top: var(--spacing-10);
        margin-bottom: var(--spacing-4);
    }

    h3 {
        font-size: var(--font-size-xl);
        font-weight: 600;
        margin-top: var(--spacing-8);
        margin-bottom: var(--spacing-3);
    }

    p {
        font-size: var(--font-size-lg);
        line-height: 1.8;
        margin-bottom: var(--spacing-6);
    }

    ul, ol {
        margin-bottom: var(--spacing-6);
        padding-left: var(--spacing-6);

        li {
            font-size: var(--font-size-lg);
            line-height: 1.8;
            margin-bottom: var(--spacing-2);
        }
    }

    blockquote {
        border-left: 4px solid var(--color-primary);
        padding-left: var(--spacing-6);
        margin: var(--spacing-8) 0;
        font-style: italic;
        font-size: var(--font-size-xl);
        color: var(--color-text-secondary);
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: var(--radius-lg);
        margin: var(--spacing-8) 0;
    }

    pre {
        background: var(--color-bg-dark);
        color: white;
        padding: var(--spacing-6);
        border-radius: var(--radius-lg);
        overflow-x: auto;
        margin: var(--spacing-8) 0;

        code {
            font-family: var(--font-family-mono);
            font-size: var(--font-size-sm);
        }
    }
}

.sd-post-share {
    position: sticky;
    top: 100px;
    align-self: start;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);

    @media (max-width: 991px) {
        position: static;
        flex-direction: row;
        justify-content: center;
        margin-top: var(--spacing-8);
    }

    .share-btn {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        transition: transform var(--transition-fast);

        &:hover {
            transform: scale(1.1);
        }

        &.share-facebook { background: #1877f2; }
        &.share-twitter { background: #1da1f2; }
        &.share-linkedin { background: #0077b5; }
        &.share-whatsapp { background: #25d366; }
        &.share-copy { background: var(--color-text-secondary); }
    }
}

// Reading Progress Bar
.sd-reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--color-bg-secondary);
    z-index: var(--z-fixed);

    .progress-bar {
        height: 100%;
        background: var(--color-primary);
        width: 0%;
        transition: width 0.1s ease-out;
    }
}
```

**End-of-Day 202 Deliverables:**

- [ ] Blog post template complete
- [ ] Reading progress bar functional
- [ ] Table of contents with smooth scroll
- [ ] Typography optimized for reading

---

### Day 203 — Category & Tag System

**Objective:** Implement comprehensive category and tag taxonomy for content organization.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create blog categories structure — 3h
2. Implement tag model and management — 2.5h
3. Build category/tag filtering — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create category landing pages — 3h
2. Build tag cloud component — 2.5h
3. Implement tag-based filtering — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design category page layout — 3h
2. Style tag cloud and badges — 2.5h
3. Create filter UI components — 2.5h

**End-of-Day 203 Deliverables:**

- [ ] Blog categories configured (5+)
- [ ] Tag system operational
- [ ] Category landing pages live
- [ ] Tag filtering working

---

### Day 204 — Author Profiles

**Objective:** Build author profile pages with bio, social links, and authored posts.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Extend author model with profile fields — 3h
2. Create author page controller — 2.5h
3. Build author posts query — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create author bio card component — 3h
2. Build author posts listing — 2.5h
3. Implement author schema.org markup — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design author profile page — 3h
2. Create author card for posts — 2.5h
3. Style author social links — 2.5h

**End-of-Day 204 Deliverables:**

- [ ] Author profiles with bios
- [ ] Author pages listing posts
- [ ] Author cards on blog posts
- [ ] Social links displayed

---

### Day 205 — Featured Images & Media

**Objective:** Implement featured image handling with optimization and media embedding.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Configure featured image processing — 3h
2. Build video embed support — 2.5h
3. Create gallery shortcode — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement responsive featured images — 3h
2. Create video embed component — 2.5h
3. Build image CDN integration — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design featured image display — 3h
2. Style embedded media — 2.5h
3. Create image caption styles — 2.5h

**End-of-Day 205 Deliverables:**

- [ ] Featured images optimized and serving
- [ ] Video embeds (YouTube, Vimeo)
- [ ] Image galleries in posts
- [ ] Responsive images with srcset

---

### Day 206 — Social Sharing Integration

**Objective:** Implement social sharing buttons with tracking and native share API support.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create share count tracking — 3h
2. Build share API endpoints — 2.5h
3. Implement Open Graph optimization — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Build social share buttons JavaScript — 3h
2. Implement Web Share API — 2h
3. Create share tracking analytics — 3h

```javascript
// Social Sharing Component
class SmartDairySocialShare {
    constructor(container) {
        this.container = container;
        this.url = container.dataset.url || window.location.href;
        this.title = container.dataset.title || document.title;
        this.description = container.dataset.description || '';
        this.init();
    }

    init() {
        this.bindEvents();
        this.checkNativeShare();
    }

    bindEvents() {
        this.container.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const platform = btn.dataset.platform;
                this.share(platform);
            });
        });
    }

    share(platform) {
        const shareUrls = {
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.url)}`,
            twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(this.url)}&text=${encodeURIComponent(this.title)}`,
            linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(this.url)}`,
            whatsapp: `https://wa.me/?text=${encodeURIComponent(this.title + ' ' + this.url)}`,
        };

        if (platform === 'copy') {
            this.copyToClipboard();
            return;
        }

        if (platform === 'native' && navigator.share) {
            this.nativeShare();
            return;
        }

        if (shareUrls[platform]) {
            window.open(shareUrls[platform], 'share', 'width=600,height=400');
            this.trackShare(platform);
        }
    }

    async nativeShare() {
        try {
            await navigator.share({
                title: this.title,
                text: this.description,
                url: this.url,
            });
            this.trackShare('native');
        } catch (err) {
            console.log('Share cancelled');
        }
    }

    copyToClipboard() {
        navigator.clipboard.writeText(this.url).then(() => {
            this.showToast('Link copied to clipboard!');
            this.trackShare('copy');
        });
    }

    checkNativeShare() {
        if (navigator.share) {
            const nativeBtn = this.container.querySelector('.share-native');
            if (nativeBtn) nativeBtn.style.display = 'flex';
        }
    }

    trackShare(platform) {
        // Track in Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'share', {
                method: platform,
                content_type: 'article',
                item_id: this.container.dataset.postId,
            });
        }

        // Track in backend
        fetch('/api/blog/track-share', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                post_id: this.container.dataset.postId,
                platform: platform,
            }),
        });
    }

    showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'sd-toast';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
}
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design share button styles — 3h
2. Create share modal/dropdown — 2.5h
3. Style toast notifications — 2.5h

**End-of-Day 206 Deliverables:**

- [ ] Social sharing buttons functional
- [ ] Share tracking integrated
- [ ] Native share API support
- [ ] Copy link feature

---

### Day 207 — Related Posts Component

**Objective:** Build related posts recommendation system based on categories, tags, and content similarity.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create related posts algorithm — 4h
2. Build related posts API — 2h
3. Implement caching for recommendations — 2h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create related posts carousel — 3h
2. Build "More from this author" section — 2.5h
3. Implement lazy loading for related — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design related posts section — 3h
2. Style related post cards — 2.5h
3. Create section transitions — 2.5h

**End-of-Day 207 Deliverables:**

- [ ] Related posts showing on articles
- [ ] Algorithm considering tags/categories
- [ ] "More from author" section
- [ ] Lazy loading implemented

---

### Day 208 — RSS Feed Generation

**Objective:** Generate valid RSS feeds for blog syndication and reader subscriptions.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create RSS feed generator — 3h
2. Build category-specific feeds — 2.5h
3. Implement feed caching — 2.5h

```python
# RSS Feed Controller
from odoo import http
from odoo.http import request
import datetime

class BlogRSSController(http.Controller):

    @http.route('/blog/feed.xml', type='http', auth='public', website=True)
    def blog_rss_feed(self, **kwargs):
        """Generate main blog RSS feed"""
        posts = request.env['blog.post'].sudo().search([
            ('website_published', '=', True),
        ], order='published_date desc', limit=20)

        values = {
            'posts': posts,
            'website': request.website,
            'build_date': datetime.datetime.utcnow().strftime('%a, %d %b %Y %H:%M:%S GMT'),
        }

        response = request.render('smart_dairy_blog.rss_feed', values, mimetype='application/rss+xml')
        response.headers['Content-Type'] = 'application/rss+xml; charset=utf-8'
        return response

    @http.route('/blog/<model("blog.blog"):blog>/feed.xml', type='http', auth='public', website=True)
    def blog_category_feed(self, blog, **kwargs):
        """Generate category-specific RSS feed"""
        posts = request.env['blog.post'].sudo().search([
            ('blog_id', '=', blog.id),
            ('website_published', '=', True),
        ], order='published_date desc', limit=20)

        values = {
            'posts': posts,
            'blog': blog,
            'website': request.website,
            'build_date': datetime.datetime.utcnow().strftime('%a, %d %b %Y %H:%M:%S GMT'),
        }

        return request.render('smart_dairy_blog.rss_feed', values, mimetype='application/rss+xml')
```

```xml
<!-- RSS Feed Template -->
<template id="rss_feed" name="Blog RSS Feed">
    <rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
        <channel>
            <title>Smart Dairy Blog</title>
            <link><t t-esc="website.domain"/>/blog</link>
            <description>Fresh insights from Smart Dairy - dairy farming, nutrition, and recipes</description>
            <language>en</language>
            <lastBuildDate><t t-esc="build_date"/></lastBuildDate>
            <atom:link t-att-href="website.domain + '/blog/feed.xml'" rel="self" type="application/rss+xml"/>

            <t t-foreach="posts" t-as="post">
                <item>
                    <title><t t-esc="post.name"/></title>
                    <link><t t-esc="website.domain + '/blog/' + str(post.blog_id.id) + '/post/' + str(post.id)"/></link>
                    <description><![CDATA[<t t-raw="post.teaser or post.content[:500]"/>]]></description>
                    <pubDate><t t-esc="post.published_date.strftime('%a, %d %b %Y %H:%M:%S GMT') if post.published_date else ''"/></pubDate>
                    <guid><t t-esc="website.domain + '/blog/' + str(post.blog_id.id) + '/post/' + str(post.id)"/></guid>
                    <author><t t-esc="post.author_id.email"/> (<t t-esc="post.author_id.name"/>)</author>
                    <category><t t-esc="post.blog_id.name"/></category>
                </item>
            </t>
        </channel>
    </rss>
</template>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create JSON feed alternative — 2.5h
2. Build feed autodiscovery tags — 2h
3. Implement feed validation — 3.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create RSS subscribe button — 2.5h
2. Design feed subscription modal — 2.5h
3. Build email subscription option — 3h

**End-of-Day 208 Deliverables:**

- [ ] Main RSS feed valid
- [ ] Category feeds available
- [ ] Feed autodiscovery in HTML head
- [ ] Subscribe options displayed

---

### Day 209 — Content Import & Search

**Objective:** Import 30+ blog articles and implement blog search functionality.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create content import script — 3h
2. Import 30+ blog articles — 3h
3. Verify content formatting — 2h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Implement blog search functionality — 4h
2. Create search results page — 2h
3. Build search suggestions — 2h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design search results layout — 3h
2. Style search input and suggestions — 2.5h
3. Test all blog pages — 2.5h

**End-of-Day 209 Deliverables:**

- [ ] 30+ articles imported
- [ ] Blog search operational
- [ ] Search results page styled
- [ ] All content verified

---

### Day 210 — Milestone 36 Review & Testing

**Objective:** Comprehensive testing and milestone review.

#### All Developers (8h each)

**Combined Tasks:**

1. Test all blog features — 3h
2. Validate RSS feeds — 1.5h
3. Test social sharing — 1.5h
4. Demo to stakeholders — 1.5h
5. Retrospective and MS-37 planning — 0.5h

**End-of-Day 210 Deliverables:**

- [ ] 30+ articles published
- [ ] All blog features working
- [ ] RSS feeds valid
- [ ] Social sharing tracked
- [ ] Milestone 36 sign-off

---

## 4. Technical Specifications

### 4.1 Blog Categories

| Category | Description | Target Posts |
| -------- | ----------- | ------------ |
| Dairy Nutrition | Health benefits, nutrition info | 8+ |
| Recipes | Dairy-based recipes | 6+ |
| Farm Life | Behind the scenes | 5+ |
| Industry News | Dairy industry updates | 5+ |
| Tips & Guides | How-to content | 6+ |

### 4.2 RSS Feed URLs

| Feed | URL |
| ---- | --- |
| Main Feed | /blog/feed.xml |
| Category Feeds | /blog/{category}/feed.xml |
| JSON Feed | /blog/feed.json |

---

## 5. Testing & Validation

- [ ] 30+ posts display correctly
- [ ] Categories and tags filter content
- [ ] Author profiles complete
- [ ] Social sharing works all platforms
- [ ] RSS feeds validate (W3C validator)
- [ ] Related posts show relevant content

---

**END OF MILESTONE 36**

**Next Milestone**: Milestone_37_Contact_Support.md
