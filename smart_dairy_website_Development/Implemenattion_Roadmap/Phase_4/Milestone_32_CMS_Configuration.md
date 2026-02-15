# Milestone 32: CMS Configuration & Content Structure

## Smart Dairy Digital Smart Portal + ERP — Phase 4: Foundation - Public Website & CMS

| Field                | Detail                                                           |
| -------------------- | ---------------------------------------------------------------- |
| **Milestone**        | 32 of 40 (2 of 10 in Phase 4)                                    |
| **Title**            | CMS Configuration & Content Structure                            |
| **Phase**            | Phase 4 — Foundation (Public Website & CMS)                      |
| **Days**             | Days 161–170 (of 250)                                            |
| **Version**          | 1.0                                                              |
| **Status**           | Draft                                                            |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend Lead) |
| **Last Updated**     | 2026-02-03                                                       |

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

Establish a fully functional Content Management System (CMS) with role-based access, content approval workflows, media library, page templates, reusable snippets, menu management, and content scheduling. By Day 170, non-technical content editors will be able to create, edit, review, and publish website content through the Odoo CMS interface without developer intervention.

### 1.2 Objectives

1. Configure CMS user roles (Content Editor, Content Manager, SEO Specialist, Administrator)
2. Implement multi-stage content approval workflow with state transitions
3. Build media library with automatic image optimization and WebP conversion
4. Create responsive image variant generation pipeline
5. Design and implement 10+ page template layouts
6. Create 30+ reusable content snippet components
7. Configure menu management system with drag-and-drop ordering
8. Implement content scheduling with automated publish/unpublish
9. Create CMS user training documentation
10. Conduct Milestone 32 review, demo, and retrospective

### 1.3 Key Deliverables

| # | Deliverable                           | Owner  | Format                    |
|---|---------------------------------------|--------|---------------------------|
| 1 | CMS user roles and permissions        | Dev 1  | Odoo access rights        |
| 2 | Content approval workflow             | Dev 1  | Odoo workflow definition  |
| 3 | Media library configuration           | Dev 2  | Odoo + custom module      |
| 4 | Image optimization pipeline           | Dev 2  | Python service             |
| 5 | Page template library (10+ layouts)   | Dev 3  | QWeb templates            |
| 6 | Content snippet library (30+)         | Dev 3  | QWeb snippets             |
| 7 | Menu management interface             | Dev 1  | Odoo backend              |
| 8 | Content scheduling system             | Dev 1  | Cron + state machine      |
| 9 | CMS training documentation            | All    | Markdown + screenshots    |
| 10| Milestone 32 review report            | All    | Markdown document         |

### 1.4 Prerequisites

- Milestone 31 completed — design system and component library operational
- Odoo Website module installed and configured
- Smart Dairy theme active with design tokens
- CDN integration operational

### 1.5 Success Criteria

- [ ] CMS roles enforcing permissions correctly (tested with 4 role types)
- [ ] Content workflow transitioning through Draft > Review > Approved > Published states
- [ ] Media library accepting uploads, optimizing images, generating variants
- [ ] 10+ page templates available in Odoo page builder
- [ ] 30+ snippets draggable in Odoo snippet panel
- [ ] Content scheduling publishing/unpublishing pages on time
- [ ] CMS training documentation complete for non-technical users

---

## 2. Requirement Traceability Matrix

| Req ID        | Source     | Requirement Description                           | Day(s)  | Task Reference              |
| ------------- | ---------- | ------------------------------------------------- | ------- | --------------------------- |
| RFP-CMS-001   | RFP §3     | Content management for non-technical users        | 161-163 | Roles, workflow, training   |
| BRD-CMS-001   | BRD §4     | Self-service content editing                      | 161-162 | CMS roles, permissions      |
| BRD-CMS-002   | BRD §4     | Content approval before publishing                | 162     | Approval workflow           |
| SRS-CMS-001   | SRS §4.2   | Media asset management                            | 163-164 | Media library, optimization |
| SRS-CMS-002   | SRS §4.2   | Page templates and reusable components            | 165-166 | Templates, snippets         |
| SRS-CMS-003   | SRS §4.2   | Menu management with hierarchy                    | 167     | Menu management system      |
| SRS-CMS-004   | SRS §4.2   | Content scheduling                                | 168     | Cron-based scheduling       |
| NFR-PERF-01   | BRD §3     | Image optimization for page load <3s              | 163-164 | Image pipeline              |
| D-001 §5      | Impl Guide | CMS access control                                | 161     | Odoo access rights          |
| B-014 §3      | Impl Guide | Content workflow standards                        | 162     | State machine               |

---

## 3. Day-by-Day Breakdown

---

### Day 161 — CMS User Roles & Access Control

**Objective:** Configure four CMS user roles with granular permissions for content management within Odoo.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Design CMS role hierarchy and permission matrix — 2h
2. Create Odoo user groups for CMS roles — 3h
3. Configure record rules for content isolation — 2h
4. Test role-based access with sample users — 1h

```python
# CMS Role Configuration - Security Groups
# smart_dairy_cms/security/cms_groups.xml

# Python model for CMS access control
from odoo import models, fields, api

class CMSAccessControl(models.Model):
    _name = 'cms.access.control'
    _description = 'CMS Access Control Configuration'

    name = fields.Char('Role Name', required=True)
    group_id = fields.Many2one('res.groups', string='Security Group')
    can_create = fields.Boolean('Can Create Content', default=False)
    can_edit = fields.Boolean('Can Edit Content', default=False)
    can_publish = fields.Boolean('Can Publish Content', default=False)
    can_delete = fields.Boolean('Can Delete Content', default=False)
    can_manage_media = fields.Boolean('Can Manage Media', default=False)
    can_manage_menus = fields.Boolean('Can Manage Menus', default=False)
    can_manage_seo = fields.Boolean('Can Manage SEO', default=False)
    can_schedule = fields.Boolean('Can Schedule Content', default=False)
    allowed_page_ids = fields.Many2many('website.page', string='Allowed Pages')
```

```xml
<!-- smart_dairy_cms/security/cms_groups.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- CMS Module Category -->
    <record id="module_category_cms" model="ir.module.category">
        <field name="name">Smart Dairy CMS</field>
        <field name="sequence">50</field>
    </record>

    <!-- Content Editor: Can create and edit drafts -->
    <record id="group_cms_content_editor" model="res.groups">
        <field name="name">Content Editor</field>
        <field name="category_id" ref="module_category_cms"/>
        <field name="comment">
            Can create and edit content drafts.
            Cannot publish or delete content.
            Can upload media to library.
        </field>
    </record>

    <!-- Content Manager: Can review and publish -->
    <record id="group_cms_content_manager" model="res.groups">
        <field name="name">Content Manager</field>
        <field name="category_id" ref="module_category_cms"/>
        <field name="implied_ids" eval="[(4, ref('group_cms_content_editor'))]"/>
        <field name="comment">
            Can review, approve, and publish content.
            Can manage menus and page structure.
            Can schedule content publication.
        </field>
    </record>

    <!-- SEO Specialist: Can manage SEO metadata -->
    <record id="group_cms_seo_specialist" model="res.groups">
        <field name="name">SEO Specialist</field>
        <field name="category_id" ref="module_category_cms"/>
        <field name="implied_ids" eval="[(4, ref('group_cms_content_editor'))]"/>
        <field name="comment">
            Can edit meta titles, descriptions, and keywords.
            Can manage structured data and sitemaps.
            Can view analytics data.
        </field>
    </record>

    <!-- CMS Administrator: Full access -->
    <record id="group_cms_administrator" model="res.groups">
        <field name="name">CMS Administrator</field>
        <field name="category_id" ref="module_category_cms"/>
        <field name="implied_ids" eval="[
            (4, ref('group_cms_content_manager')),
            (4, ref('group_cms_seo_specialist'))
        ]"/>
        <field name="comment">
            Full CMS access including role management,
            template configuration, and system settings.
        </field>
    </record>
</odoo>
```

```csv
# smart_dairy_cms/security/ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_cms_page_editor,cms.page.editor,model_website_page,group_cms_content_editor,1,1,1,0
access_cms_page_manager,cms.page.manager,model_website_page,group_cms_content_manager,1,1,1,1
access_cms_media_editor,cms.media.editor,model_cms_media_library,group_cms_content_editor,1,1,1,0
access_cms_media_manager,cms.media.manager,model_cms_media_library,group_cms_content_manager,1,1,1,1
access_cms_seo_editor,cms.seo.editor,model_cms_seo_metadata,group_cms_seo_specialist,1,1,1,0
access_cms_menu_manager,cms.menu.manager,model_website_menu,group_cms_content_manager,1,1,1,1
access_cms_schedule,cms.schedule.manager,model_cms_content_schedule,group_cms_content_manager,1,1,1,1
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create CMS audit logging system — 3h
2. Implement content versioning (revision history) — 3h
3. Build role assignment management interface — 2h

```python
# CMS Audit Logging
from odoo import models, fields, api
import json

class CMSAuditLog(models.Model):
    _name = 'cms.audit.log'
    _description = 'CMS Content Audit Log'
    _order = 'create_date desc'

    content_type = fields.Selection([
        ('page', 'Page'),
        ('blog_post', 'Blog Post'),
        ('media', 'Media Asset'),
        ('menu', 'Menu Item'),
        ('snippet', 'Snippet'),
    ], string='Content Type', required=True)

    content_id = fields.Integer('Content ID', required=True)
    content_name = fields.Char('Content Name')
    action = fields.Selection([
        ('create', 'Created'),
        ('edit', 'Edited'),
        ('publish', 'Published'),
        ('unpublish', 'Unpublished'),
        ('delete', 'Deleted'),
        ('review', 'Submitted for Review'),
        ('approve', 'Approved'),
        ('reject', 'Rejected'),
        ('schedule', 'Scheduled'),
        ('restore', 'Restored'),
    ], string='Action', required=True)

    user_id = fields.Many2one('res.users', string='User', default=lambda self: self.env.user)
    previous_state = fields.Char('Previous State')
    new_state = fields.Char('New State')
    changes_json = fields.Text('Changes (JSON)')
    ip_address = fields.Char('IP Address')
    note = fields.Text('Note')

    @api.model
    def log_action(self, content_type, content_id, action, **kwargs):
        """Log a CMS action"""
        from odoo.http import request
        ip = request.httprequest.remote_addr if request else 'system'

        return self.sudo().create({
            'content_type': content_type,
            'content_id': content_id,
            'content_name': kwargs.get('content_name', ''),
            'action': action,
            'previous_state': kwargs.get('previous_state', ''),
            'new_state': kwargs.get('new_state', ''),
            'changes_json': json.dumps(kwargs.get('changes', {})),
            'ip_address': ip,
            'note': kwargs.get('note', ''),
        })


# Content Versioning
class CMSContentVersion(models.Model):
    _name = 'cms.content.version'
    _description = 'CMS Content Version History'
    _order = 'version_number desc'

    page_id = fields.Many2one('website.page', string='Page', ondelete='cascade')
    version_number = fields.Integer('Version', required=True)
    content_html = fields.Html('Content HTML', sanitize=False)
    arch_xml = fields.Text('Architecture XML')
    meta_title = fields.Char('Meta Title')
    meta_description = fields.Text('Meta Description')
    created_by = fields.Many2one('res.users', string='Created By', default=lambda self: self.env.user)
    note = fields.Text('Version Note')
    is_current = fields.Boolean('Is Current Version', default=False)

    @api.model
    def create_version(self, page_id, note=''):
        """Create a new version snapshot of a page"""
        page = self.env['website.page'].browse(page_id)

        # Get current max version
        max_version = self.search([
            ('page_id', '=', page_id)
        ], order='version_number desc', limit=1)

        new_version_num = (max_version.version_number + 1) if max_version else 1

        # Mark all existing as not current
        self.search([('page_id', '=', page_id)]).write({'is_current': False})

        return self.create({
            'page_id': page_id,
            'version_number': new_version_num,
            'content_html': page.arch,
            'arch_xml': page.view_id.arch if page.view_id else '',
            'meta_title': page.website_meta_title,
            'meta_description': page.website_meta_description,
            'note': note,
            'is_current': True,
        })

    def restore_version(self):
        """Restore this version to the page"""
        self.ensure_one()
        page = self.page_id

        # Create a backup of current before restoring
        self.create_version(page.id, note=f'Auto-backup before restoring v{self.version_number}')

        # Restore content
        page.write({
            'arch': self.content_html,
            'website_meta_title': self.meta_title,
            'website_meta_description': self.meta_description,
        })

        # Log the restoration
        self.env['cms.audit.log'].log_action(
            'page', page.id, 'restore',
            content_name=page.name,
            note=f'Restored to version {self.version_number}'
        )
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design CMS editor UI customizations — 3h
2. Create content editor toolbar enhancements — 3h
3. Build visual role indicators in editor — 2h

```scss
// CMS Editor Customizations
// _cms_editor.scss

// Editor Toolbar
.sd-cms-toolbar {
    background: var(--color-bg-primary);
    border-bottom: 2px solid var(--color-primary);
    padding: var(--spacing-2) var(--spacing-4);
    display: flex;
    align-items: center;
    gap: var(--spacing-4);
    position: sticky;
    top: 0;
    z-index: var(--z-sticky);

    .toolbar-section {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
        padding-right: var(--spacing-4);
        border-right: 1px solid var(--color-border-light);

        &:last-child {
            border-right: none;
        }
    }

    .toolbar-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-2);
        background: none;
        border: none;
        border-radius: var(--radius-sm);
        color: var(--color-text-secondary);
        cursor: pointer;
        transition: all var(--transition-fast);

        &:hover {
            background: var(--color-bg-secondary);
            color: var(--color-text-primary);
        }

        &.active {
            background: var(--color-primary);
            color: white;
        }
    }
}

// Content Status Indicator
.sd-content-status {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-1) var(--spacing-3);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;

    &.status-draft {
        background: rgba(108, 117, 125, 0.15);
        color: #6c757d;
    }

    &.status-review {
        background: rgba(255, 193, 7, 0.15);
        color: #856404;
    }

    &.status-approved {
        background: rgba(40, 167, 69, 0.15);
        color: #155724;
    }

    &.status-published {
        background: rgba(0, 102, 204, 0.15);
        color: #0066CC;
    }

    &.status-scheduled {
        background: rgba(111, 66, 193, 0.15);
        color: #6f42c1;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }
}

// Version History Panel
.sd-version-panel {
    position: fixed;
    right: 0;
    top: 60px;
    bottom: 0;
    width: 350px;
    background: var(--color-bg-primary);
    border-left: 1px solid var(--color-border);
    box-shadow: var(--shadow-lg);
    transform: translateX(100%);
    transition: transform var(--transition-normal);
    z-index: var(--z-modal);
    overflow-y: auto;

    &.active {
        transform: translateX(0);
    }

    .version-header {
        padding: var(--spacing-6);
        border-bottom: 1px solid var(--color-border-light);

        h3 {
            font-size: var(--font-size-lg);
            margin-bottom: var(--spacing-2);
        }
    }

    .version-list {
        padding: var(--spacing-4);
    }

    .version-item {
        padding: var(--spacing-4);
        border: 1px solid var(--color-border-light);
        border-radius: var(--radius-md);
        margin-bottom: var(--spacing-3);
        cursor: pointer;
        transition: all var(--transition-fast);

        &:hover {
            border-color: var(--color-primary);
            box-shadow: var(--shadow-sm);
        }

        &.current {
            border-color: var(--color-success);
            background: rgba(40, 167, 69, 0.05);
        }

        .version-number {
            font-weight: 600;
            color: var(--color-primary);
        }

        .version-meta {
            font-size: var(--font-size-sm);
            color: var(--color-text-secondary);
            margin-top: var(--spacing-1);
        }

        .version-actions {
            margin-top: var(--spacing-3);
            display: flex;
            gap: var(--spacing-2);
        }
    }
}
```

**End-of-Day 161 Deliverables:**

- [ ] Four CMS roles configured with correct permissions
- [ ] Record rules enforcing content isolation
- [ ] Audit logging system operational
- [ ] Content versioning system storing snapshots
- [ ] CMS editor UI customizations applied

---

### Day 162 — Content Approval Workflow

**Objective:** Implement a multi-stage content approval workflow with state transitions, notifications, and review capabilities.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Design workflow state machine — 2h
2. Implement content state model with transitions — 3h
3. Create notification system for workflow events — 2h
4. Write workflow validation rules — 1h

```python
# Content Workflow State Machine
from odoo import models, fields, api
from odoo.exceptions import UserError

class CMSContentWorkflow(models.Model):
    _name = 'cms.content.workflow'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _description = 'CMS Content Workflow'

    name = fields.Char('Content Title', required=True, tracking=True)
    content_type = fields.Selection([
        ('page', 'Website Page'),
        ('blog_post', 'Blog Post'),
        ('product_page', 'Product Page'),
    ], string='Content Type', required=True)

    page_id = fields.Many2one('website.page', string='Website Page')
    blog_post_id = fields.Many2one('blog.post', string='Blog Post')

    state = fields.Selection([
        ('draft', 'Draft'),
        ('review', 'Under Review'),
        ('revision', 'Needs Revision'),
        ('approved', 'Approved'),
        ('published', 'Published'),
        ('unpublished', 'Unpublished'),
        ('archived', 'Archived'),
    ], string='Status', default='draft', required=True, tracking=True)

    author_id = fields.Many2one('res.users', string='Author',
                                default=lambda self: self.env.user)
    reviewer_id = fields.Many2one('res.users', string='Reviewer')
    approver_id = fields.Many2one('res.users', string='Approver')
    publish_date = fields.Datetime('Publish Date')
    unpublish_date = fields.Datetime('Unpublish Date')
    review_note = fields.Text('Review Notes')
    revision_note = fields.Text('Revision Notes')
    priority = fields.Selection([
        ('low', 'Low'),
        ('normal', 'Normal'),
        ('high', 'High'),
        ('urgent', 'Urgent'),
    ], default='normal', string='Priority')

    # Tracking fields
    submitted_date = fields.Datetime('Submitted for Review')
    reviewed_date = fields.Datetime('Reviewed Date')
    approved_date = fields.Datetime('Approved Date')
    published_date = fields.Datetime('Published Date')

    def action_submit_review(self):
        """Submit content for review"""
        self.ensure_one()
        if self.state != 'draft' and self.state != 'revision':
            raise UserError('Only draft or revision content can be submitted for review.')

        self.write({
            'state': 'review',
            'submitted_date': fields.Datetime.now(),
        })

        # Log audit
        self.env['cms.audit.log'].log_action(
            self.content_type, self._get_content_id(), 'review',
            content_name=self.name,
            previous_state='draft',
            new_state='review'
        )

        # Send notification to content managers
        self._notify_reviewers()

    def action_approve(self):
        """Approve content for publishing"""
        self.ensure_one()
        if self.state != 'review':
            raise UserError('Only content under review can be approved.')

        # Check user has approval permission
        if not self.env.user.has_group('smart_dairy_cms.group_cms_content_manager'):
            raise UserError('You do not have permission to approve content.')

        self.write({
            'state': 'approved',
            'approver_id': self.env.user.id,
            'approved_date': fields.Datetime.now(),
        })

        self.env['cms.audit.log'].log_action(
            self.content_type, self._get_content_id(), 'approve',
            content_name=self.name,
            previous_state='review',
            new_state='approved'
        )

        self._notify_author('approved')

    def action_request_revision(self):
        """Request content revision"""
        self.ensure_one()
        if self.state != 'review':
            raise UserError('Only content under review can be sent back for revision.')

        self.write({
            'state': 'revision',
            'reviewer_id': self.env.user.id,
            'reviewed_date': fields.Datetime.now(),
        })

        self.env['cms.audit.log'].log_action(
            self.content_type, self._get_content_id(), 'reject',
            content_name=self.name,
            previous_state='review',
            new_state='revision',
            note=self.revision_note
        )

        self._notify_author('revision')

    def action_publish(self):
        """Publish approved content"""
        self.ensure_one()
        if self.state != 'approved':
            raise UserError('Only approved content can be published.')

        self.write({
            'state': 'published',
            'published_date': fields.Datetime.now(),
        })

        # Publish the actual content
        self._publish_content()

        self.env['cms.audit.log'].log_action(
            self.content_type, self._get_content_id(), 'publish',
            content_name=self.name,
            previous_state='approved',
            new_state='published'
        )

    def action_unpublish(self):
        """Unpublish content"""
        self.ensure_one()
        if self.state != 'published':
            raise UserError('Only published content can be unpublished.')

        self.write({'state': 'unpublished'})
        self._unpublish_content()

        self.env['cms.audit.log'].log_action(
            self.content_type, self._get_content_id(), 'unpublish',
            content_name=self.name,
            previous_state='published',
            new_state='unpublished'
        )

    def _get_content_id(self):
        if self.page_id:
            return self.page_id.id
        elif self.blog_post_id:
            return self.blog_post_id.id
        return self.id

    def _publish_content(self):
        if self.page_id:
            self.page_id.write({'website_published': True})
        elif self.blog_post_id:
            self.blog_post_id.write({'website_published': True})

    def _unpublish_content(self):
        if self.page_id:
            self.page_id.write({'website_published': False})
        elif self.blog_post_id:
            self.blog_post_id.write({'website_published': False})

    def _notify_reviewers(self):
        """Notify content managers about pending review"""
        managers = self.env['res.users'].search([
            ('groups_id', 'in', self.env.ref('smart_dairy_cms.group_cms_content_manager').id)
        ])
        for manager in managers:
            self.activity_schedule(
                'mail.mail_activity_data_todo',
                user_id=manager.id,
                summary=f'Review content: {self.name}',
                note=f'Content "{self.name}" submitted for review by {self.author_id.name}.'
            )

    def _notify_author(self, action):
        """Notify author about review result"""
        summary = 'Content Approved' if action == 'approved' else 'Revision Requested'
        note = f'Your content "{self.name}" has been {action}.'
        if action == 'revision' and self.revision_note:
            note += f'\n\nRevision notes: {self.revision_note}'

        self.activity_schedule(
            'mail.mail_activity_data_todo',
            user_id=self.author_id.id,
            summary=summary,
            note=note
        )
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create workflow dashboard view — 3h
2. Implement email notification templates — 2.5h
3. Build content preview functionality — 2.5h

```python
# Workflow Dashboard Controller
from odoo import http
from odoo.http import request

class CMSWorkflowDashboard(http.Controller):

    @http.route('/cms/dashboard', type='http', auth='user', website=True)
    def cms_dashboard(self, **kwargs):
        """CMS content management dashboard"""
        user = request.env.user
        workflow_model = request.env['cms.content.workflow'].sudo()

        # Counts by state
        counts = {}
        for state in ['draft', 'review', 'revision', 'approved', 'published']:
            domain = [('state', '=', state)]
            if not user.has_group('smart_dairy_cms.group_cms_content_manager'):
                domain.append(('author_id', '=', user.id))
            counts[state] = workflow_model.search_count(domain)

        # Recent activity
        recent_logs = request.env['cms.audit.log'].sudo().search([], limit=20)

        # Pending reviews (for managers)
        pending_reviews = workflow_model.search([
            ('state', '=', 'review')
        ], limit=10, order='submitted_date desc')

        # My drafts (for editors)
        my_drafts = workflow_model.search([
            ('author_id', '=', user.id),
            ('state', 'in', ['draft', 'revision'])
        ], limit=10, order='write_date desc')

        values = {
            'counts': counts,
            'recent_logs': recent_logs,
            'pending_reviews': pending_reviews,
            'my_drafts': my_drafts,
        }

        return request.render('smart_dairy_cms.cms_dashboard_template', values)
```

```xml
<!-- Email notification template for content review -->
<odoo>
    <record id="email_template_content_review" model="mail.template">
        <field name="name">CMS: Content Review Request</field>
        <field name="model_id" ref="smart_dairy_cms.model_cms_content_workflow"/>
        <field name="subject">Review Request: {{object.name}}</field>
        <field name="email_from">{{(object.company_id.email or 'noreply@smartdairy.com.bd')}}</field>
        <field name="body_html"><![CDATA[
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <div style="background: #0066CC; color: white; padding: 20px; text-align: center;">
                    <h2 style="margin: 0;">Content Review Request</h2>
                </div>
                <div style="padding: 30px; background: #f8f9fa;">
                    <p>Hello,</p>
                    <p>A new content item requires your review:</p>
                    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Title:</td>
                            <td style="padding: 8px;">{{object.name}}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Type:</td>
                            <td style="padding: 8px;">{{object.content_type}}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Author:</td>
                            <td style="padding: 8px;">{{object.author_id.name}}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-weight: bold;">Priority:</td>
                            <td style="padding: 8px;">{{object.priority}}</td>
                        </tr>
                    </table>
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="/cms/dashboard"
                           style="background: #0066CC; color: white; padding: 12px 30px;
                                  text-decoration: none; border-radius: 5px;">
                            Review Content
                        </a>
                    </div>
                </div>
            </div>
        ]]></field>
    </record>
</odoo>
```

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design workflow status bar UI — 3h
2. Create review interface with side-by-side comparison — 3h
3. Build revision request form with rich text notes — 2h

**End-of-Day 162 Deliverables:**

- [ ] Content workflow state machine operational
- [ ] State transitions working (Draft > Review > Approved > Published)
- [ ] Email notifications sending on state changes
- [ ] Workflow dashboard displaying content status
- [ ] Revision request form with notes functional

---

### Day 163 — Media Library Setup

**Objective:** Build a comprehensive media library with upload, organization, search, and metadata management.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create media library data model — 3h
2. Implement file upload with validation — 3h
3. Create media categorization and tagging — 2h

```python
# Media Library Model
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import os

ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']
ALLOWED_DOCUMENT_TYPES = ['application/pdf', 'application/msword',
                          'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
ALLOWED_VIDEO_TYPES = ['video/mp4', 'video/webm']
MAX_FILE_SIZE = 10 * 1024 * 1024  # 10MB

class CMSMediaLibrary(models.Model):
    _name = 'cms.media.library'
    _description = 'CMS Media Library'
    _order = 'create_date desc'

    name = fields.Char('File Name', required=True)
    file_data = fields.Binary('File', attachment=True, required=True)
    file_name = fields.Char('Original Filename')
    file_size = fields.Integer('File Size (bytes)', compute='_compute_file_info', store=True)
    file_type = fields.Char('MIME Type', compute='_compute_file_info', store=True)

    media_type = fields.Selection([
        ('image', 'Image'),
        ('document', 'Document'),
        ('video', 'Video'),
    ], string='Media Type', compute='_compute_media_type', store=True)

    # Organization
    folder_id = fields.Many2one('cms.media.folder', string='Folder')
    tag_ids = fields.Many2many('cms.media.tag', string='Tags')

    # Image variants
    image_thumbnail = fields.Binary('Thumbnail (150x150)', attachment=True)
    image_small = fields.Binary('Small (400x300)', attachment=True)
    image_medium = fields.Binary('Medium (800x600)', attachment=True)
    image_large = fields.Binary('Large (1200x900)', attachment=True)
    image_webp = fields.Binary('WebP Version', attachment=True)

    # Metadata
    alt_text = fields.Char('Alt Text (Accessibility)')
    title = fields.Char('Title Attribute')
    description = fields.Text('Description')
    credit = fields.Char('Credit / Attribution')
    width = fields.Integer('Width (px)')
    height = fields.Integer('Height (px)')

    # Usage tracking
    usage_count = fields.Integer('Usage Count', compute='_compute_usage_count')
    used_in_pages = fields.Text('Used In Pages', compute='_compute_usage_count')

    # Status
    active = fields.Boolean('Active', default=True)
    uploaded_by = fields.Many2one('res.users', string='Uploaded By',
                                  default=lambda self: self.env.user)

    @api.constrains('file_data', 'file_name')
    def _check_file(self):
        for record in self:
            if record.file_name:
                ext = os.path.splitext(record.file_name)[1].lower()
                allowed_exts = ['.jpg', '.jpeg', '.png', '.gif', '.webp',
                               '.svg', '.pdf', '.doc', '.docx', '.mp4', '.webm']
                if ext not in allowed_exts:
                    raise ValidationError(f'File type {ext} is not allowed.')

    @api.depends('file_name')
    def _compute_media_type(self):
        for record in self:
            if record.file_name:
                ext = os.path.splitext(record.file_name)[1].lower()
                if ext in ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg']:
                    record.media_type = 'image'
                elif ext in ['.pdf', '.doc', '.docx']:
                    record.media_type = 'document'
                elif ext in ['.mp4', '.webm']:
                    record.media_type = 'video'
                else:
                    record.media_type = 'document'
            else:
                record.media_type = 'document'

    @api.model
    def create(self, vals):
        record = super().create(vals)
        if record.media_type == 'image':
            record._generate_image_variants()
        return record

    def _generate_image_variants(self):
        """Generate responsive image variants"""
        self.ensure_one()
        if not self.file_data or self.media_type != 'image':
            return

        import base64
        from PIL import Image
        from io import BytesIO

        img_data = base64.b64decode(self.file_data)
        img = Image.open(BytesIO(img_data))

        # Store original dimensions
        self.width = img.width
        self.height = img.height

        variants = {
            'image_thumbnail': (150, 150),
            'image_small': (400, 300),
            'image_medium': (800, 600),
            'image_large': (1200, 900),
        }

        update_vals = {}
        for field_name, size in variants.items():
            resized = img.copy()
            resized.thumbnail(size, Image.Resampling.LANCZOS)
            buffer = BytesIO()
            resized.save(buffer, format='PNG', optimize=True)
            update_vals[field_name] = base64.b64encode(buffer.getvalue())

        # WebP version
        webp_buffer = BytesIO()
        img.save(webp_buffer, format='WebP', quality=85, optimize=True)
        update_vals['image_webp'] = base64.b64encode(webp_buffer.getvalue())

        self.write(update_vals)


class CMSMediaFolder(models.Model):
    _name = 'cms.media.folder'
    _description = 'Media Library Folder'
    _order = 'name'

    name = fields.Char('Folder Name', required=True)
    parent_id = fields.Many2one('cms.media.folder', string='Parent Folder')
    child_ids = fields.One2many('cms.media.folder', 'parent_id', string='Subfolders')
    media_count = fields.Integer('Media Count', compute='_compute_media_count')

    @api.depends('child_ids')
    def _compute_media_count(self):
        for folder in self:
            folder.media_count = self.env['cms.media.library'].search_count([
                ('folder_id', '=', folder.id)
            ])


class CMSMediaTag(models.Model):
    _name = 'cms.media.tag'
    _description = 'Media Tag'

    name = fields.Char('Tag', required=True)
    color = fields.Integer('Color')
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Build media library frontend grid view — 3h
2. Implement drag-and-drop upload — 2.5h
3. Create media search with filters — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design media library UI components — 3h
2. Create media detail/edit modal — 2.5h
3. Build media picker for page editor — 2.5h

**End-of-Day 163 Deliverables:**

- [ ] Media library model with folder/tag organization
- [ ] File upload with type/size validation
- [ ] Drag-and-drop upload interface
- [ ] Media grid and list views operational
- [ ] Media search and filtering working

---

### Day 164 — Image Optimization Pipeline

**Objective:** Implement automatic image optimization with responsive variants, WebP conversion, and CDN-optimized delivery.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create background image processing queue — 3h
2. Implement responsive image serving API — 2.5h
3. Build image CDN URL generation — 2.5h

```python
# Image optimization service
import base64
from io import BytesIO
from PIL import Image
from odoo import models, api

class ImageOptimizationService(models.AbstractModel):
    _name = 'cms.image.optimization'
    _description = 'Image Optimization Service'

    @api.model
    def optimize_image(self, image_data, max_width=1920, max_height=1080, quality=85):
        """Optimize image for web delivery"""
        img_bytes = base64.b64decode(image_data)
        img = Image.open(BytesIO(img_bytes))

        # Convert RGBA to RGB for JPEG
        if img.mode in ('RGBA', 'LA', 'P'):
            background = Image.new('RGB', img.size, (255, 255, 255))
            if img.mode == 'P':
                img = img.convert('RGBA')
            background.paste(img, mask=img.split()[-1] if img.mode == 'RGBA' else None)
            img = background

        # Resize if larger than max dimensions
        if img.width > max_width or img.height > max_height:
            img.thumbnail((max_width, max_height), Image.Resampling.LANCZOS)

        # Optimize
        buffer = BytesIO()
        img.save(buffer, format='JPEG', quality=quality, optimize=True, progressive=True)
        return base64.b64encode(buffer.getvalue())

    @api.model
    def generate_srcset_variants(self, image_data):
        """Generate srcset variants for responsive images"""
        widths = [320, 640, 768, 1024, 1280, 1920]
        variants = {}

        img_bytes = base64.b64decode(image_data)
        img = Image.open(BytesIO(img_bytes))

        for width in widths:
            if width >= img.width:
                continue

            ratio = width / img.width
            height = int(img.height * ratio)
            resized = img.copy()
            resized = resized.resize((width, height), Image.Resampling.LANCZOS)

            buffer = BytesIO()
            resized.save(buffer, format='JPEG', quality=80, optimize=True)
            variants[f'{width}w'] = base64.b64encode(buffer.getvalue())

        return variants

    @api.model
    def convert_to_webp(self, image_data, quality=85):
        """Convert image to WebP format"""
        img_bytes = base64.b64decode(image_data)
        img = Image.open(BytesIO(img_bytes))

        buffer = BytesIO()
        img.save(buffer, format='WebP', quality=quality, optimize=True)
        return base64.b64encode(buffer.getvalue())
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Configure Nginx image serving with srcset support — 2.5h
2. Implement browser-based WebP detection — 2h
3. Set up image CDN caching rules — 2h
4. Create image performance monitoring — 1.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create responsive image component with srcset — 3h
2. Implement progressive image loading (blur-up) — 2.5h
3. Build image compression settings UI — 2.5h

**End-of-Day 164 Deliverables:**

- [ ] Image optimization pipeline processing uploads
- [ ] Responsive variants auto-generated (6 sizes)
- [ ] WebP conversion working
- [ ] Progressive image loading with blur-up effect
- [ ] CDN serving optimized images

---

### Day 165 — Page Template Library (Part 1)

**Objective:** Create the first set of 5 page templates covering the most common page layouts.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create template registration system in Odoo — 3h
2. Implement dynamic content regions — 2.5h
3. Build template preview generation — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create "Landing Page" template — 2.5h
2. Create "Content Page" template — 2.5h
3. Create "Two Column" template — 2h
4. Template testing and responsive validation — 1h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create "Full Width Hero" template — 2.5h
2. Create "Sidebar Layout" template — 2.5h
3. Style all 5 templates with design tokens — 2h
4. Create template preview thumbnails — 1h

```xml
<!-- Page Template: Full Width Hero -->
<template id="template_full_width_hero" name="Full Width Hero">
    <t t-call="website.layout">
        <div class="sd-template-hero-full">
            <!-- Hero Section -->
            <section class="sd-hero-full-width">
                <div class="sd-hero-bg" data-snippet-id="hero_bg">
                    <div class="container">
                        <div class="row align-items-center min-vh-75">
                            <div class="col-lg-7">
                                <div class="sd-hero-content">
                                    <h1 class="sd-hero-title" data-editable="true">
                                        Your Headline Here
                                    </h1>
                                    <p class="sd-hero-subtitle" data-editable="true">
                                        Add your subtitle or description text here.
                                    </p>
                                    <div class="sd-hero-actions">
                                        <a href="#" class="btn btn-primary btn-lg">
                                            Primary CTA
                                        </a>
                                        <a href="#" class="btn btn-outline-primary btn-lg">
                                            Secondary CTA
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="sd-hero-image" data-editable="true">
                                    <img src="/smart_dairy_theme/static/src/img/placeholder-hero.jpg"
                                         alt="Hero Image" class="img-fluid rounded-lg"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Content Area -->
            <section class="sd-content-area py-5">
                <div class="container">
                    <div class="oe_structure" data-editable="true"/>
                </div>
            </section>
        </div>
    </t>
</template>
```

**End-of-Day 165 Deliverables:**

- [ ] Template registration system operational
- [ ] 5 page templates created and functional
- [ ] Templates available in Odoo page builder
- [ ] All templates responsive
- [ ] Template preview thumbnails generated

---

### Day 166 — Page Template Library (Part 2) & Snippet Library

**Objective:** Create remaining 5+ page templates and begin building the snippet library.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create "Product Showcase" template — 2.5h
2. Create "Contact Page" template — 2.5h
3. Begin snippet data models — 3h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create "Blog Listing" template — 2.5h
2. Create "Team/About" template — 2.5h
3. Begin snippet integration framework — 3h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Create "FAQ Page" template — 2.5h
2. Create "Gallery" template — 2.5h
3. Begin snippet visual design — 3h

**End-of-Day 166 Deliverables:**

- [ ] 10+ page templates available
- [ ] All templates tested across breakpoints
- [ ] Snippet framework initialized
- [ ] Template documentation started

---

### Day 167 — Content Snippet Library

**Objective:** Create 30+ reusable content snippets for the Odoo page builder.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Create snippet registration system — 2h
2. Build dynamic data snippets (product, blog feeds) — 4h
3. Implement snippet option configurations — 2h

```xml
<!-- Snippet Registration -->
<odoo>
    <template id="smart_dairy_snippets" inherit_id="website.snippets" name="Smart Dairy Snippets">
        <xpath expr="//div[@id='snippet_structure']" position="inside">
            <t t-snippet="smart_dairy_theme.snippet_hero_banner"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/hero_banner.png"/>
            <t t-snippet="smart_dairy_theme.snippet_feature_grid"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/feature_grid.png"/>
            <t t-snippet="smart_dairy_theme.snippet_product_carousel"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/product_carousel.png"/>
            <t t-snippet="smart_dairy_theme.snippet_testimonials"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/testimonials.png"/>
            <t t-snippet="smart_dairy_theme.snippet_newsletter"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/newsletter.png"/>
            <t t-snippet="smart_dairy_theme.snippet_stats_counter"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/stats_counter.png"/>
            <t t-snippet="smart_dairy_theme.snippet_cta_banner"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/cta_banner.png"/>
            <t t-snippet="smart_dairy_theme.snippet_team_grid"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/team_grid.png"/>
            <t t-snippet="smart_dairy_theme.snippet_faq_accordion"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/faq_accordion.png"/>
            <t t-snippet="smart_dairy_theme.snippet_blog_feed"
               t-thumbnail="/smart_dairy_theme/static/src/img/snippets/blog_feed.png"/>
        </xpath>
    </template>

    <!-- Hero Banner Snippet -->
    <template id="snippet_hero_banner" name="Hero Banner">
        <section class="sd-snippet-hero-banner py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <span class="sd-badge-sm">Smart Dairy</span>
                        <h2 class="display-4 fw-bold mt-3">Fresh From Farm to Your Table</h2>
                        <p class="lead text-muted mt-3">
                            Experience the finest organic dairy products crafted with care
                            from our sustainable farm in Narayanganj, Bangladesh.
                        </p>
                        <div class="mt-4">
                            <a href="/products" class="btn btn-primary btn-lg me-3">View Products</a>
                            <a href="/about" class="btn btn-outline-primary btn-lg">Our Story</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="/smart_dairy_theme/static/src/img/snippets/hero-placeholder.jpg"
                             class="img-fluid rounded-lg shadow-lg" alt="Smart Dairy Farm"/>
                    </div>
                </div>
            </div>
        </section>
    </template>

    <!-- Stats Counter Snippet -->
    <template id="snippet_stats_counter" name="Statistics Counter">
        <section class="sd-snippet-stats bg-primary text-white py-5">
            <div class="container">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-4 mb-md-0">
                        <div class="sd-stat-item">
                            <span class="sd-stat-number" data-count="255">0</span>
                            <span class="sd-stat-label">Head of Cattle</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-4 mb-md-0">
                        <div class="sd-stat-item">
                            <span class="sd-stat-number" data-count="900">0</span>
                            <span class="sd-stat-suffix">L</span>
                            <span class="sd-stat-label">Daily Milk Production</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="sd-stat-item">
                            <span class="sd-stat-number" data-count="50">0</span>
                            <span class="sd-stat-suffix">+</span>
                            <span class="sd-stat-label">Products</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="sd-stat-item">
                            <span class="sd-stat-number" data-count="1800">0</span>
                            <span class="sd-stat-suffix">+</span>
                            <span class="sd-stat-label">Happy Customers</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </template>

    <!-- Newsletter Signup Snippet -->
    <template id="snippet_newsletter" name="Newsletter Signup">
        <section class="sd-snippet-newsletter py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h3 class="mb-3">Stay Updated with Smart Dairy</h3>
                        <p class="text-muted mb-4">
                            Subscribe to our newsletter for fresh recipes, health tips, and exclusive offers.
                        </p>
                        <form class="sd-newsletter-form" action="/newsletter/subscribe" method="post">
                            <input type="hidden" name="csrf_token" t-att-value="request.csrf_token()"/>
                            <div class="input-group input-group-lg max-w-500 mx-auto">
                                <input type="email" name="email" class="form-control"
                                       placeholder="Enter your email address"
                                       data-validate="required,email"
                                       data-field-name="Email"
                                       aria-label="Email address" required="required"/>
                                <button type="submit" class="btn btn-primary">
                                    Subscribe
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                We respect your privacy. Unsubscribe at any time.
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </template>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Create interactive snippets (counter animation, accordion) — 4h
2. Build snippet configuration panel — 2h
3. Implement snippet lazy loading — 2h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Style 15 snippet components — 4h
2. Create snippet thumbnails for preview — 2h
3. Test all snippets in page builder — 2h

**End-of-Day 167 Deliverables:**

- [ ] 30+ snippets registered in Odoo page builder
- [ ] All snippets draggable and configurable
- [ ] Dynamic data snippets pulling live data
- [ ] Snippet animations and interactions working

---

### Day 168 — Menu Management & Content Scheduling

**Objective:** Configure advanced menu management with drag-and-drop ordering and implement automated content scheduling.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Implement content scheduling cron job — 3h
2. Create scheduling calendar view — 2.5h
3. Build scheduled publishing/unpublishing logic — 2.5h

```python
# Content Scheduling Cron
from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)

class CMSContentSchedule(models.Model):
    _name = 'cms.content.schedule'
    _description = 'CMS Content Schedule'
    _order = 'scheduled_datetime'

    name = fields.Char('Description', required=True)
    content_type = fields.Selection([
        ('page', 'Website Page'),
        ('blog_post', 'Blog Post'),
    ], string='Content Type', required=True)

    page_id = fields.Many2one('website.page', string='Page')
    blog_post_id = fields.Many2one('blog.post', string='Blog Post')

    action = fields.Selection([
        ('publish', 'Publish'),
        ('unpublish', 'Unpublish'),
    ], string='Action', required=True)

    scheduled_datetime = fields.Datetime('Scheduled Date/Time', required=True)
    executed = fields.Boolean('Executed', default=False)
    executed_datetime = fields.Datetime('Executed At')
    error_message = fields.Text('Error Message')

    state = fields.Selection([
        ('pending', 'Pending'),
        ('executed', 'Executed'),
        ('failed', 'Failed'),
        ('cancelled', 'Cancelled'),
    ], string='Status', default='pending')

    created_by = fields.Many2one('res.users', string='Created By',
                                 default=lambda self: self.env.user)

    @api.model
    def run_scheduled_actions(self):
        """Cron method to execute scheduled content actions"""
        now = fields.Datetime.now()
        pending_schedules = self.search([
            ('state', '=', 'pending'),
            ('scheduled_datetime', '<=', now),
        ])

        for schedule in pending_schedules:
            try:
                if schedule.action == 'publish':
                    schedule._execute_publish()
                elif schedule.action == 'unpublish':
                    schedule._execute_unpublish()

                schedule.write({
                    'state': 'executed',
                    'executed': True,
                    'executed_datetime': now,
                })

                _logger.info(
                    'Scheduled %s for %s "%s" executed successfully',
                    schedule.action, schedule.content_type, schedule.name
                )

                # Log audit
                content_id = schedule.page_id.id if schedule.page_id else schedule.blog_post_id.id
                self.env['cms.audit.log'].log_action(
                    schedule.content_type, content_id, schedule.action,
                    content_name=schedule.name,
                    note=f'Scheduled {schedule.action} executed'
                )

            except Exception as e:
                schedule.write({
                    'state': 'failed',
                    'error_message': str(e),
                })
                _logger.error(
                    'Scheduled %s failed for "%s": %s',
                    schedule.action, schedule.name, str(e)
                )

    def _execute_publish(self):
        self.ensure_one()
        if self.page_id:
            self.page_id.write({'website_published': True})
        elif self.blog_post_id:
            self.blog_post_id.write({
                'website_published': True,
                'published_date': fields.Datetime.now(),
            })

    def _execute_unpublish(self):
        self.ensure_one()
        if self.page_id:
            self.page_id.write({'website_published': False})
        elif self.blog_post_id:
            self.blog_post_id.write({'website_published': False})

    def action_cancel(self):
        """Cancel a scheduled action"""
        self.write({'state': 'cancelled'})
```

```xml
<!-- Cron job definition -->
<odoo>
    <record id="ir_cron_cms_schedule" model="ir.cron">
        <field name="name">CMS: Execute Scheduled Content Actions</field>
        <field name="model_id" ref="smart_dairy_cms.model_cms_content_schedule"/>
        <field name="state">code</field>
        <field name="code">model.run_scheduled_actions()</field>
        <field name="interval_number">5</field>
        <field name="interval_type">minutes</field>
        <field name="numbercall">-1</field>
        <field name="active">True</field>
    </record>
</odoo>
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Build content calendar UI component — 3h
2. Create menu drag-and-drop interface — 3h
3. Implement menu preview in navigation — 2h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Design scheduling form UI — 2.5h
2. Create calendar visualization styles — 2.5h
3. Build menu management visual editor — 3h

**End-of-Day 168 Deliverables:**

- [ ] Content scheduling cron executing every 5 minutes
- [ ] Scheduling calendar showing upcoming publications
- [ ] Menu drag-and-drop reordering functional
- [ ] Menu changes reflecting in navigation immediately

---

### Day 169 — CMS Training Documentation

**Objective:** Create comprehensive CMS training documentation for non-technical content editors and managers.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Write CMS administrator guide — 3h
2. Create content workflow documentation — 2.5h
3. Document API endpoints for CMS operations — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Write content editor user guide — 3h
2. Create media library usage documentation — 2.5h
3. Document troubleshooting and FAQ — 2.5h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Write page builder guide with visual walkthroughs — 3h
2. Create snippet usage documentation — 2.5h
3. Document template selection guide — 2.5h

**End-of-Day 169 Deliverables:**

- [ ] Content Editor Guide complete
- [ ] Content Manager Guide complete
- [ ] CMS Administrator Guide complete
- [ ] Media Library Usage Guide complete
- [ ] Page Builder Guide complete

---

### Day 170 — Milestone 32 Review & Testing

**Objective:** Conduct comprehensive CMS testing, review all deliverables, and prepare handoff for Milestone 33.

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. End-to-end workflow testing (all state transitions) — 3h
2. Permission testing with all 4 roles — 2.5h
3. Code review and security audit — 2.5h

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Performance testing of CMS operations — 2.5h
2. Media library stress testing (bulk upload) — 2.5h
3. Integration testing with CI/CD pipeline — 3h

#### Dev 3 — Frontend Lead (8h)

**Tasks:**

1. Template and snippet visual testing — 3h
2. Accessibility check on all CMS interfaces — 2.5h
3. Cross-browser testing of page builder — 2.5h

#### All Developers — Milestone Review (4h combined)

1. Demo CMS capabilities to stakeholders — 1.5h
2. Review deliverables against success criteria — 1h
3. Retrospective and lessons learned — 0.5h
4. Plan Milestone 33 (Homepage & Landing Pages) — 1h

**End-of-Day 170 Deliverables:**

- [ ] All CMS features tested and operational
- [ ] Permission matrix verified
- [ ] Performance benchmarks met
- [ ] Stakeholder demo completed
- [ ] Milestone 32 sign-off received

---

## 4. Technical Specifications

### 4.1 CMS Role Permission Matrix

| Permission            | Editor | Manager | SEO Specialist | Administrator |
| --------------------- | ------ | ------- | -------------- | ------------- |
| Create draft content  | Yes    | Yes     | Yes            | Yes           |
| Edit own content      | Yes    | Yes     | Yes            | Yes           |
| Edit all content      | No     | Yes     | No             | Yes           |
| Submit for review     | Yes    | Yes     | Yes            | Yes           |
| Approve content       | No     | Yes     | No             | Yes           |
| Publish content       | No     | Yes     | No             | Yes           |
| Delete content        | No     | Yes     | No             | Yes           |
| Manage media          | Upload | Full    | Upload         | Full          |
| Manage menus          | No     | Yes     | No             | Yes           |
| Edit SEO metadata     | No     | No      | Yes            | Yes           |
| Schedule content      | No     | Yes     | No             | Yes           |
| View audit logs       | Own    | All     | Own            | All           |
| Manage roles          | No     | No      | No             | Yes           |

### 4.2 Content Workflow States

```
Draft ──► Under Review ──► Approved ──► Published
  ▲            │                            │
  │            ▼                            ▼
  └─── Needs Revision              Unpublished ──► Archived
```

### 4.3 Media Library Specifications

| Specification          | Value                     |
| ---------------------- | ------------------------- |
| Max file size          | 10 MB                     |
| Allowed image types    | JPEG, PNG, GIF, WebP, SVG |
| Allowed document types | PDF, DOC, DOCX            |
| Allowed video types    | MP4, WebM                 |
| Image variants         | 150px, 400px, 800px, 1200px |
| WebP auto-conversion   | Yes                       |
| Folder nesting depth   | Unlimited                 |

---

## 5. Testing & Validation

### 5.1 Testing Checklist

- [ ] Role permission testing (4 roles × all permissions)
- [ ] Workflow state transitions (all valid paths)
- [ ] Invalid state transitions (rejection cases)
- [ ] Media upload (all file types)
- [ ] Media optimization (variant generation)
- [ ] Template rendering (all 10+ templates)
- [ ] Snippet drag-and-drop (all 30+ snippets)
- [ ] Content scheduling (publish + unpublish)
- [ ] Menu management (CRUD + reordering)
- [ ] Content versioning (create, view, restore)
- [ ] Audit logging (all actions logged)

---

## 6. Risk & Mitigation

| Risk                    | Probability | Impact | Mitigation                           |
| ----------------------- | ----------- | ------ | ------------------------------------ |
| Image processing slow   | Medium      | Medium | Background queue, caching            |
| Permission misconfigure | Low         | High   | Thorough testing, role documentation |
| Workflow deadlocks      | Low         | High   | Admin override, escalation path      |
| Snippet conflicts       | Medium      | Low    | Isolation, namespace scoping         |

---

## 7. Dependencies & Handoffs

### 7.1 Incoming

| Dependency              | Source      | Status    |
| ----------------------- | ----------- | --------- |
| Design system & tokens  | MS-31       | Required  |
| Odoo Website module     | MS-31       | Required  |
| CDN integration         | MS-31       | Required  |

### 7.2 Outgoing

| Deliverable             | Recipient   | Date      |
| ----------------------- | ----------- | --------- |
| CMS with roles/workflow | Content team| Day 170   |
| Page templates          | All devs    | Day 170   |
| Snippet library         | All devs    | Day 170   |
| Media library           | Content team| Day 170   |

---

**END OF MILESTONE 32**

**Next Milestone**: Milestone_33_Homepage_Landing.md
