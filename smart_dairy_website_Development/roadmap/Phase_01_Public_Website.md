# Phase 1: Public Website + E-Commerce
## Months 3-6 (March - June 2026)

**Phase Goal:** Launch functional B2C e-commerce website, B2B portal, virtual farm tours, blog/content platform, and admin panel.
**Key Deliverable:** Live website selling Smart Dairy products with bKash/Nagad/SSL Commerz payment integration.
**Revenue Impact:** Direct dairy sales begin.

---

## Sprint Breakdown

### Sprint 1.1-1.2 (Weeks 1-4): Product Catalog & Storefront

**Objectives:**
- Build product catalog with categories, filtering, sorting
- Create responsive product detail pages with nutritional info
- Implement image optimization pipeline (Sharp + CDN)
- Build homepage with hero section, featured products, technology showcase CTA
- Bengali/English bilingual setup with next-intl

**Deliverables:**
- [ ] Product listing page with category filter, price range, sort
- [ ] Product detail page with images, nutritional info, related products
- [ ] Category management (admin)
- [ ] Homepage with responsive design (mobile-first)
- [ ] Language toggle (EN/BN) working on all pages
- [ ] SEO meta tags, Open Graph, structured data

### Sprint 1.3-1.4 (Weeks 5-8): Cart, Checkout & Payments

**Objectives:**
- Build shopping cart (persistent for logged-in users, session for guests)
- Implement checkout flow with address management
- Integrate payment gateways: bKash, Nagad, SSL Commerz, COD
- Build order management with status tracking
- Email/SMS order notifications

**Deliverables:**
- [ ] Add to cart, update quantity, remove items
- [ ] Checkout with delivery address selection
- [ ] bKash payment integration (test + live)
- [ ] Nagad payment integration
- [ ] SSL Commerz integration (cards)
- [ ] Cash on Delivery option
- [ ] Order confirmation email + SMS
- [ ] Order status page with timeline
- [ ] Promo code / coupon system

### Sprint 1.5-1.6 (Weeks 9-12): User Accounts & Reviews

**Objectives:**
- Build customer account area (profile, orders, addresses, wishlist)
- Implement product review system with ratings
- Build subscription service for recurring orders
- Loyalty points system

**Deliverables:**
- [ ] User dashboard with order history
- [ ] Saved addresses management
- [ ] Wishlist functionality
- [ ] Product reviews with star ratings and images
- [ ] Subscription creation (daily/weekly milk delivery)
- [ ] Loyalty points earning and display

### Sprint 1.7-1.8 (Weeks 13-16): B2B Portal

**Objectives:**
- Build B2B application and approval workflow
- Implement custom B2B pricing engine
- Build bulk ordering (CSV upload)
- Quote request system
- B2B invoice management

**Deliverables:**
- [ ] B2B registration form with trade license upload
- [ ] Admin approval workflow for B2B applications
- [ ] Custom pricing visible to approved B2B customers
- [ ] Bulk order via CSV upload
- [ ] Quote request and response system
- [ ] B2B invoice PDF generation
- [ ] Multi-location delivery support

### Sprint 1.9-1.10 (Weeks 17-20): Virtual Tour, Blog & Content

**Objectives:**
- Build 360-degree virtual farm tour experience
- Integrate live camera feeds
- Create public farm metrics dashboard (placeholder data until ERP Phase 2)
- Build blog/content platform with CMS
- Build contact page, FAQ, static pages

**Deliverables:**
- [ ] Interactive virtual tour with hotspots
- [ ] Live camera feed integration
- [ ] Farm dashboard showing metrics (initially static/manual)
- [ ] Blog with categories, tags, bilingual posts
- [ ] Static pages: About, Contact, Privacy, Terms
- [ ] FAQ section
- [ ] Newsletter subscription
- [ ] Contact form with email notification

### Sprint 1.11-1.12 (Weeks 21-24): Admin Panel & Launch

**Objectives:**
- Build comprehensive admin panel
- Performance optimization and testing
- Security audit
- Staging and production deployment
- Public launch

**Deliverables:**
- [ ] Admin: Product CRUD, inventory management
- [ ] Admin: Order management, status updates
- [ ] Admin: Customer management, B2B approvals
- [ ] Admin: Content management (blog, pages, FAQ)
- [ ] Admin: Basic analytics dashboard
- [ ] Lighthouse score >90 on all key pages
- [ ] Load testing (target: 100 concurrent users)
- [ ] Security audit passed
- [ ] Production deployment with SSL, CDN
- [ ] Public launch

---

## Phase 1 Verification Checklist

- [ ] Browse products -> add to cart -> checkout -> pay via test bKash -> receive order confirmation email + SMS
- [ ] B2B: Register business -> get approved -> see custom pricing -> place bulk order
- [ ] Virtual tour loads and navigates correctly
- [ ] Blog posts display in both EN and BN
- [ ] Lighthouse score >90, page load <3s on 4G
- [ ] Bengali toggle works on all pages
- [ ] Admin can manage products, orders, customers, content
- [ ] Mobile responsive on all pages

---

**Phase 1 Duration:** 24 weeks (12 sprints)
**Dependencies:** Phase 0 complete
**Next Phase:** Phase 2 (ERP Core)
