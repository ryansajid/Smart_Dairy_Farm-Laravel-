# Phase 4: Agro Marketplace
## Months 15-18 (March - June 2027)

**Phase Goal:** Launch multi-vendor agro marketplace supporting dairy products, farm equipment, machinery, seeds, fertilizers, and other agricultural goods.
**Key Deliverable:** Vendors listing products, customers buying, payment splitting with vendor payouts.
**Revenue Impact:** Commission revenue (3-12% per transaction).

---

## Sprint Breakdown

### Sprint 4.1-4.2 (Weeks 1-4): Vendor Onboarding & Management

**Objectives:**
- Build vendor application flow (NID verification, trade license, bank details)
- Admin approval/rejection workflow
- Vendor dashboard with sales summary
- Vendor profile management
- Deploy Meilisearch for marketplace search

**Deliverables:**
- [ ] Vendor application form with document upload
- [ ] Admin vendor review and approval panel
- [ ] Vendor dashboard: sales summary, pending orders, payout balance
- [ ] Vendor profile page (public-facing)
- [ ] Vendor settings: bank account, bKash number, business info
- [ ] Meilisearch deployed and indexed
- [ ] Commission rate configuration per vendor/category

### Sprint 4.3-4.4 (Weeks 5-8): Product Listings & Search

**Objectives:**
- Build marketplace product listing (CRUD for vendors)
- Category tree with subcategories
- Full-text search with Meilisearch (typo-tolerant, faceted)
- Product filtering (price, category, vendor, location, rating)
- Bulk product upload via CSV

**Deliverables:**
- [ ] Vendor product creation form with rich media
- [ ] Product variant support (size, weight)
- [ ] Category tree management (admin)
- [ ] Meilisearch-powered search with instant results
- [ ] Faceted filtering: price range, category, vendor location, rating
- [ ] Product sorting: price, newest, rating, bestselling
- [ ] CSV bulk upload for vendors
- [ ] Product moderation queue (admin)

### Sprint 4.5-4.6 (Weeks 9-12): Multi-Vendor Ordering & Payments

**Objectives:**
- Multi-vendor cart (items from different vendors in one cart)
- Order splitting into sub-orders per vendor
- Payment processing with escrow model
- Vendor fulfillment workflow (confirm, ship, deliver)
- Shipping tracking integration

**Deliverables:**
- [ ] Unified cart supporting website + marketplace items
- [ ] Order creation splitting into vendor sub-orders
- [ ] Payment to escrow (full amount from buyer)
- [ ] Vendor order notification (email + SMS)
- [ ] Vendor order management: confirm, mark shipped, tracking number
- [ ] Buyer order tracking per sub-order
- [ ] Delivery confirmation flow
- [ ] Shipping fee calculation per vendor

### Sprint 4.7-4.8 (Weeks 13-16): Reviews, Disputes & Payouts

**Objectives:**
- Product and vendor review system
- Dispute resolution workflow
- Return request handling
- Automated weekly vendor payouts (bKash Business / BEFTN)
- Vendor analytics dashboard
- Marketplace admin panel

**Deliverables:**
- [ ] Product reviews with verified purchase badge
- [ ] Vendor rating aggregation
- [ ] Dispute creation with evidence upload
- [ ] Admin dispute arbitration panel
- [ ] Return request flow
- [ ] Automated payout calculation (sales - commission - returns)
- [ ] Weekly payout processing via bKash Business
- [ ] Payout history for vendors
- [ ] Vendor analytics: sales trends, top products, conversion
- [ ] Admin: marketplace overview, vendor management, dispute queue
- [ ] Buyer-vendor messaging (basic chat)

---

## Phase 4 Verification Checklist

- [ ] Vendor registers -> admin approves -> vendor lists product -> customer buys
- [ ] Multi-vendor cart: items from 2 vendors -> single checkout -> 2 sub-orders created
- [ ] Vendor sees order -> marks shipped with tracking -> customer sees status update
- [ ] Customer confirms delivery -> payout appears in vendor dashboard after 7-day hold
- [ ] Weekly payout runs -> vendor receives bKash payment
- [ ] Dispute raised -> vendor responds -> admin arbitrates
- [ ] Search "rice" returns relevant results with <50ms latency
- [ ] CSV upload creates 50+ products correctly

---

**Phase 4 Duration:** 16 weeks (8 sprints)
**Dependencies:** Phase 1 (website e-commerce infrastructure, payment integration)
**Next Phase:** Phase 5 (Livestock Trading)
