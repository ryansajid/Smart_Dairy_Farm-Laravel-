# Request for Proposal (RFP)

# Smart Dairy Smart Web Portal System & Integrated ERP

---

**Document Version:** 1.0  
**Issue Date:** January 31, 2026  
**Response Deadline:** [To be determined]  
**Proposed Project Start Date:** [To be determined]  
**Expected Go-Live Date:** [To be determined]

---

## 1. Introduction & Executive Summary

### 1.1 About Smart Dairy Ltd.

Smart Dairy Ltd. is a progressive, technology-driven dairy farming enterprise and a proud subsidiary of Smart Group, one of Bangladesh's leading business conglomerates. Established as part of Smart Group (founded in 1998), Smart Dairy represents the best in sustainable and safe farming, serving as an exclusive source of Bangladeshi organic food and nutrients in the local market.

**Company at a Glance:**
- **Company Name:** Smart Dairy Ltd.
- **Type:** Private Limited Company
- **Industry:** Dairy Farming, Livestock & Organic Food Production
- **Parent Organization:** Smart Group (Smart Technologies BD Ltd.)
- **Head Office:** Jahir Smart Tower, 205/1 & 205/1/A, West Kafrul, Begum Rokeya Sharani, Taltola, Dhaka-1207, Bangladesh
- **Farm Location:** Islambag Kali, Vulta, Rupgonj, Narayanganj, Bangladesh
- **Website:** https://smartdairybd.com

### 1.2 Current Operations & Growth Strategy

**Current Farm Status:**
| Parameter | Current Status |
|-----------|---------------|
| Total Cattle | 255 head |
| Lactating Cows | 75 cows |
| Daily Milk Production | 900 liters/day |
| Average Yield per Lactating Cow | ~12 liters/day |

**2-Year Growth Targets:**
| Parameter | Current | Target (Within 2 Years) | Growth |
|-----------|---------|------------------------|--------|
| Total Cattle | 255 | 800 | 213% increase |
| Daily Milk Production | 900 liters | 3,000 liters | 233% increase |
| Lactating Cows | 75 | ~250 (projected) | ~233% increase |

### 1.3 Product Portfolio (Saffron Brand)

**Dairy Products:**
- Saffron Organic Milk (1000 ml)
- Saffron Organic Mattha/Buttermilk (1000 ml)
- Saffron Sweet Yogurt (500g)
- Butter
- Cheese

**Meat Products:**
- Organic Beef (2 KG unit)

**Quality Certifications:** All products carry the "Dairy Farmers of Bangladesh Quality Milk" logo, guaranteeing 100% pure, antibiotic-free, preservative-free, formalin-free, and starch & detergent-free dairy products.

### 1.4 Purpose of this RFP

This Request for Proposal (RFP) is issued to solicit proposals from qualified vendors for the design, development, implementation, and deployment of a **comprehensive Smart Web Portal System** integrated with a **modern Enterprise Resource Planning (ERP) system** for Smart Dairy Ltd.

The solution must support:
1. **Public Website** - Corporate presence and brand showcase
2. **B2C E-commerce Platform** - Direct-to-consumer dairy and agro product sales
3. **B2B Marketplace Portal** - Wholesale distribution and business partner management
4. **Smart Farm Management Portal** - Internal operations and dairy farm management
5. **Integrated ERP System** - Comprehensive business process automation

---

## 2. Project Objectives & Business Goals

### 2.1 Primary Objectives

1. **Digital Transformation**
   - Establish a unified digital platform connecting all business operations
   - Enable real-time data-driven decision making
   - Automate manual processes across procurement, production, and distribution

2. **Customer Experience Enhancement**
   - Provide seamless B2C shopping experience for end consumers
   - Enable B2B customers with self-service portals and ordering capabilities
   - Build customer loyalty through personalized experiences

3. **Operational Excellence**
   - Streamline farm-to-consumer supply chain
   - Optimize inventory management and reduce wastage
   - Ensure food safety and regulatory compliance
   - Enable end-to-end traceability from farm to consumer

4. **Business Growth Enablement**
   - Support 3x production growth over 2 years
   - Enable multi-channel sales expansion
   - Facilitate new product launches and category expansions
   - Support potential franchise and partnership models

5. **Technology-Driven Agriculture**
   - Integrate IoT sensors for real-time farm monitoring
   - Implement AI/ML for demand forecasting and yield optimization
   - Enable mobile access for field staff and management

### 2.2 Key Performance Indicators (KPIs)

| KPI Category | Target Metrics |
|-------------|----------------|
| **Operational Efficiency** | Reduce manual data entry by 70% |
| **Inventory Management** | Achieve 99.5% inventory accuracy |
| **Order Fulfillment** | Process orders 3x faster than current manual process |
| **Customer Satisfaction** | Achieve 95%+ customer satisfaction rating |
| **System Uptime** | 99.9% system availability |
| **Traceability** | 100% product traceability from farm to consumer |
| **Financial Reporting** | Real-time financial dashboards and reports |

---

## 3. Scope of Work

### 3.1 Portal Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SMART DAIRY SMART PORTAL SYSTEM                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐              │
│  │   PUBLIC WEB    │  │   B2C PORTAL    │  │   B2B PORTAL    │              │
│  │                 │  │                 │  │                 │              │
│  │ • Corporate     │  │ • Online Store  │  │ • Wholesale     │              │
│  │   Website       │  │ • Subscription  │  │   Ordering      │              │
│  │ • About Us      │  │ • Home Delivery │  │ • Bulk Pricing  │              │
│  │ • Products      │  │ • Customer      │  │ • Credit Terms  │              │
│  │ • Sustainability│  │   Accounts      │  │ • B2B Dashboard │              │
│  │ • Contact       │  │ • Mobile App    │  │ • Partner Portal│              │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘              │
│           │                    │                    │                        │
│           └────────────────────┼────────────────────┘                        │
│                                │                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    UNIFIED API & INTEGRATION LAYER                   │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                │                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    INTEGRATED ERP SYSTEM                             │    │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐   │    │
│  │  │Inventory │ │  Sales   │ │Purchase│ │Accounting│ │  HR/Pay  │   │    │
│  │  │  & MRP   │ │  & CRM   │ │  & SCM   │ │ & Finance│ │   roll   │   │    │
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘   │    │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐   │    │
│  │  │   Farm   │ │ Quality  │ │Manufactur│ │  Point   │ │ Business │   │    │
│  │  │Management│ │ Control  │ │  ing MRP │ │ of Sale  │ │Intelligen│   │    │
│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘   │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                │                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    IoT & SMART FARMING LAYER                         │    │
│  │  • Milk Production Sensors    • Environmental Monitoring            │    │
│  │  • Cattle Health Tracking     • Cold Chain Monitoring               │    │
│  │  • Feed Management            • Biogas Plant Monitoring             │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Component 1: Public Website

**Objective:** Establish a professional corporate web presence that showcases Smart Dairy's brand, values, and product offerings.

**Required Features:**

| Feature Category | Requirements |
|-----------------|--------------|
| **Corporate Information** | Company overview, mission, vision, leadership profiles |
| **Product Showcase** | Interactive product catalog with detailed specifications |
| **Sustainability Portal** | Biogas initiatives, organic farming practices, environmental impact |
| **Farm Virtual Tour** | Photo/video gallery of farm operations and facilities |
| **News & Media** | Press releases, blog, industry news, media kit |
| **Career Portal** | Job listings, online applications, candidate management |
| **Contact & Support** | Multi-channel contact forms, FAQ, chatbot integration |
| **Multi-language Support** | English and Bengali language support |
| **SEO Optimization** | Search engine optimized structure, meta tags, sitemap |
| **Mobile Responsive** | Fully responsive design for all devices |

### 3.3 Component 2: B2C E-commerce Platform

**Objective:** Enable direct-to-consumer sales of dairy and agro products with subscription capabilities and home delivery.

**Required Features:**

| Feature Category | Requirements |
|-----------------|--------------|
| **Product Catalog** | Category browsing, product filtering, detailed product pages |
| **Shopping Cart** | Persistent cart, save for later, wishlist functionality |
| **Checkout Process** | Guest checkout, registered user checkout, multiple payment options |
| **Payment Integration** | bKash, Nagad, Rocket, credit/debit cards, cash on delivery |
| **Subscription Management** | Daily/weekly/monthly milk subscriptions, pause/resume/modify |
| **Delivery Management** | Delivery scheduling, route optimization, delivery tracking |
| **Customer Accounts** | Order history, invoice download, address management, preferences |
| **Loyalty Program** | Points system, rewards, referral program |
| **Reviews & Ratings** | Product reviews, customer testimonials |
| **Mobile App** | Native Android and iOS applications |
| **Promotions** | Coupon codes, flash sales, bundle offers |

**B2C Product Categories:**
- Fresh Milk (Daily Subscription)
- Yogurt & Fermented Products
- Butter & Ghee
- Cheese Varieties
- Organic Beef
- Agro Products (future expansion)

### 3.4 Component 3: B2B Marketplace Portal

**Objective:** Facilitate wholesale distribution, manage business partnerships, and enable bulk ordering for retailers, restaurants, hotels, and institutional buyers.

**Required Features:**

| Feature Category | Requirements |
|-----------------|--------------|
| **Partner Registration** | Self-service registration with approval workflow |
| **Partner Tiers** | Distributor, Retailer, HORECA (Hotel/Restaurant/Café), Institutional |
| **Tier-based Pricing** | Volume discounts, contract pricing, special terms |
| **Bulk Ordering** | Quick order forms, CSV upload, reorder templates |
| **Credit Management** | Credit limit setup, payment terms, aging reports |
| **B2B Dashboard** | Order history, outstanding payments, delivery schedules |
| **Invoice Management** | Electronic invoices, credit notes, statement of accounts |
| **Vendor Portal** | For suppliers to view purchase orders, submit invoices, track payments |
| **API Access** | REST APIs for integration with partner systems |
| **RFQ Management** | Request for quotation workflow for custom orders |

### 3.5 Component 4: Smart Farm Management Portal

**Objective:** Digitize and optimize farm operations, herd management, and production processes.

**Required Features:**

| Feature Category | Requirements |
|-----------------|--------------|
| **Herd Management** | Individual cattle profiles, lineage tracking, identification (RFID/ear tags) |
| **Health Management** | Vaccination schedules, treatment records, veterinary visits |
| **Reproduction Management** | Breeding records, pregnancy tracking, calving alerts, embryo transfer logs |
| **Milk Production Tracking** | Daily yield per cow, quality parameters (Fat/SNF), trend analysis |
| **Feed Management** | Ration planning, feed inventory, consumption tracking, cost analysis |
| **Inventory Management** | Medicine, equipment, supplies tracking with expiry alerts |
| **Task Management** | Daily task lists, work assignment, completion tracking |
| **Biogas Plant Monitoring** | Production tracking, maintenance schedules |
| **Reporting & Analytics** | Production reports, health summaries, profitability analysis |
| **Mobile Access** | Field staff mobile app for data entry and task management |

**Smart Farming IoT Integration:**

| IoT Sensor Type | Data Points |
|----------------|-------------|
| **Milk Meters** | Volume, temperature, conductivity |
| **Milk Quality Analyzers** | Fat %, SNF %, protein %, lactose %, density |
| **Environmental Sensors** | Barn temperature, humidity, air quality |
| **Cattle Wearables** | Activity monitoring, heat detection, rumination |
| **Cold Storage Monitoring** | Temperature, humidity alerts |
| **Feed Management** | Automated feeding systems integration |

### 3.6 Component 5: Integrated ERP System

**Objective:** Unify all business processes under a single comprehensive ERP platform.

#### 3.6.1 Core ERP Modules Required

| Module | Key Functionalities |
|--------|---------------------|
| **Inventory & Warehouse** | Multi-location inventory, batch/lot tracking, FEFO/FIFO, barcode/RFID, stock valuation |
| **Sales & CRM** | Lead management, opportunity tracking, quotation, sales orders, customer portal |
| **Purchase & SCM** | Supplier management, RFQ, purchase orders, goods receipt, supplier evaluation |
| **Manufacturing (MRP)** | Bill of Materials, production planning, work orders, yield tracking, quality control |
| **Accounting & Finance** | General ledger, accounts payable/receivable, bank reconciliation, financial reporting, GST/VAT compliance |
| **Point of Sale (POS)** | Retail store POS, offline capability, integrated payments |
| **Quality Management** | Quality checks at receiving, in-process, and final stages, certificate of analysis |
| **HR & Payroll** | Employee management, attendance, leave, payroll processing, compliance |
| **Asset Management** | Fixed asset tracking, depreciation, maintenance schedules |
| **Business Intelligence** | Dashboards, KPIs, custom reports, data analytics |

#### 3.6.2 Dairy-Specific ERP Requirements

| Process Area | Specific Requirements |
|-------------|----------------------|
| **Milk Procurement** | Farmer registration, collection center management, Fat/SNF-based pricing, automated payments |
| **Cold Chain Management** | Temperature monitoring integration, cold storage tracking, logistics optimization |
| **Production Planning** | Shelf-life based planning, batch sizing, yield optimization |
| **Traceability** | Farm-to-fork tracking, recall management, compliance reporting |
| **Compliance** | FSSAI/BFSA compliance, organic certification tracking, audit trails |

---

## 4. Technical Requirements

### 4.1 Platform Architecture

| Requirement | Specification |
|-------------|---------------|
| **Architecture** | Microservices-based, cloud-native architecture |
| **Deployment** | Cloud-hosted (AWS/Azure/GCP) or on-premise with cloud option |
| **Scalability** | Horizontal scaling to support 3x business growth |
| **High Availability** | 99.9% uptime SLA with disaster recovery |
| **Security** | SSL/TLS encryption, role-based access control, data encryption at rest |
| **APIs** | RESTful APIs for all modules, webhook support |
| **Integration** | Open architecture supporting third-party integrations |

### 4.2 Integration Requirements

| System Category | Integration Needs |
|----------------|-------------------|
| **Payment Gateways** | bKash, Nagad, Rocket, SSLCommerz, Stripe, PayPal |
| **SMS Gateway** | Bulk SMS providers for notifications and OTPs |
| **Email Service** | Transactional email service integration |
| **Shipping/Logistics** | Local courier services, delivery tracking APIs |
| **IoT Platforms** | MQTT/LoRaWAN for sensor data collection |
| **Accounting Software** | Integration with existing accounting if needed |
| **Biometric/Access Control** | Farm access control integration |
| **Social Media** | Facebook, Instagram for marketing integration |

### 4.3 Compliance & Security

| Category | Requirements |
|----------|--------------|
| **Data Protection** | GDPR compliance, data localization options |
| **Financial Compliance** | Bangladesh VAT/GST compliance, audit trails |
| **Food Safety** | FSSAI/BFSA compliance documentation |
| **Security Standards** | ISO 27001 compliance, regular security audits |
| **Backup & Recovery** | Daily automated backups, point-in-time recovery |

### 4.4 Performance Requirements

| Metric | Requirement |
|--------|-------------|
| **Page Load Time** | < 3 seconds for all portal pages |
| **API Response Time** | < 500ms for standard operations |
| **Concurrent Users** | Support minimum 1000 concurrent users |
| **Transaction Processing** | Process 10,000+ orders per day |
| **Data Storage** | Unlimited storage with archiving policies |

---

## 5. ERP Platform Evaluation: Odoo 19 CE vs ERPNext CE

Based on extensive research, Smart Dairy is considering two primary open-source ERP platforms:

### 5.1 Odoo 19 Community Edition

**Overview:** Odoo is the world's leading open-source ERP platform with comprehensive business management applications.

**Key Strengths:**

| Aspect | Details |
|--------|---------|
| **Module Coverage** | 30+ core apps covering all business needs |
| **Website & E-commerce** | Built-in website builder with e-commerce capabilities |
| **Manufacturing MRP** | Advanced production planning, BOM, work orders |
| **Inventory Management** | Multi-location, barcode, lot/serial tracking, FEFO |
| **Customization** | Highly customizable with Studio (no-code) and Python development |
| **Mobile Apps** | Dedicated mobile apps for Android and iOS |
| **Bangladesh Localization** | Full support for Bangladesh accounting and taxes |
| **Scalability** | Enterprise-grade scalability supporting thousands of users |

**Odoo 19 New Features (2025):**
- AI-powered automation and field suggestions
- Enhanced e-commerce with improved SEO and Google Merchant Center integration
- Mobile-friendly accounting with bank reconciliation
- Revamped POS with dark mode and faster operations
- Advanced inventory with pack-in-pack and smart replenishment
- Improved manufacturing shop floor UI
- Enhanced cold chain management capabilities

**Considerations:**
- Community Edition has some feature limitations compared to Enterprise
- May require more development resources for dairy-specific customizations
- Self-hosting or Odoo.sh hosting required

### 5.2 ERPNext (Latest Community Edition)

**Overview:** ERPNext is a 100% open-source ERP built on the Frappe Framework, known for its simplicity and modern UI.

**Key Strengths:**

| Aspect | Details |
|--------|---------|
| **Agriculture Module** | Dedicated agriculture module for farm management |
| **Simplicity** | Clean, intuitive user interface |
| **Open Source** | Fully open-source with no proprietary restrictions |
| **Frappe Framework** | Modern low-code framework for rapid customization |
| **Integrated Website** | Built-in website and e-commerce capabilities |
| **Manufacturing** | Complete manufacturing and BOM management |
| **Healthcare Module** | Can be adapted for veterinary management |

**Considerations:**
- Smaller ecosystem compared to Odoo
- Agriculture module may need enhancement for dairy-specific needs
- Less extensive third-party app marketplace
- May require more custom development for B2B marketplace features

### 5.3 Recommendation

**Preferred Platform: Odoo 19 Community Edition**

**Rationale:**
1. **Comprehensive Feature Set:** Odoo 19 CE provides the most complete out-of-the-box functionality for all five required portal components
2. **Bangladesh Localization:** Strong support for Bangladesh accounting standards and tax requirements
3. **Growth Support:** Proven scalability to support Smart Dairy's 3x growth target
4. **Ecosystem:** Large community, extensive documentation, and availability of local implementation partners in Bangladesh
5. **Integrated E-commerce:** Built-in e-commerce capabilities reduce integration complexity
6. **Mobile-First:** Excellent mobile applications for field staff and management

**Alternative: ERPNext** can be considered if there is a strong preference for a fully open-source solution with simpler architecture.

---

## 6. Implementation Approach

### 6.1 Proposed Implementation Phases

```
Phase 1: Foundation (Months 1-3)
├── ERP Core Setup
│   ├── Accounting & Finance
│   ├── Inventory Management
│   ├── Purchase & Sales
│   └── User training
├── Public Website Launch
└── Infrastructure Setup

Phase 2: Operations (Months 4-6)
├── Farm Management Portal
│   ├── Herd management
│   ├── Milk production tracking
│   └── Feed management
├── Manufacturing MRP
├── Quality Management
└── B2B Portal (Basic)

Phase 3: Commerce (Months 7-9)
├── B2C E-commerce Launch
│   ├── Product catalog
│   ├── Payment integration
│   └── Delivery management
├── B2B Portal (Advanced)
├── Mobile Apps
└── IoT Integration

Phase 4: Optimization (Months 10-12)
├── Advanced Analytics & BI
├── AI/ML Features
├── Performance Optimization
├── Integration Refinement
└── Go-Live Support
```

### 6.2 Data Migration Strategy

| Data Category | Migration Approach |
|--------------|-------------------|
| **Master Data** | Products, customers, suppliers, chart of accounts |
| **Opening Balances** | Inventory, financial balances as of cut-off date |
| **Historical Data** | 2 years of transactional data for reporting |
| **Farm Records** | Cattle profiles, health records, production history |

### 6.3 Training & Change Management

| User Group | Training Program |
|-----------|-----------------|
| **Management** | Dashboards, reports, approval workflows |
| **Finance Team** | Accounting, financial reporting, compliance |
| **Farm Staff** | Farm management portal, mobile app usage |
| **Sales Team** | CRM, B2B portal, order management |
| **Warehouse** | Inventory operations, barcode scanning |
| **Customers** | Self-service portal user guides |

---

## 7. Vendor Requirements

### 7.1 Mandatory Requirements

| Requirement | Description |
|-------------|-------------|
| **ERP Experience** | Minimum 5 years experience implementing ERP systems |
| **Dairy/Agriculture Domain** | Demonstrated experience in dairy, agriculture, or food processing |
| **Local Presence** | Office or team in Bangladesh for on-site support |
| **Technical Expertise** | Certified developers for chosen ERP platform |
| **References** | Minimum 3 successful ERP implementations with references |

### 7.2 Evaluation Criteria

| Criteria | Weight |
|----------|--------|
| **Technical Solution Fit** | 25% |
| **Domain Expertise** | 20% |
| **Implementation Approach** | 15% |
| **Total Cost of Ownership** | 15% |
| **Support & Maintenance** | 15% |
| **Innovation & Future Roadmap** | 10% |

### 7.3 Deliverables Expected

| Deliverable | Timeline |
|------------|----------|
| **Detailed Project Plan** | Week 2 |
| **Solution Architecture Document** | Week 4 |
| **System Configuration** | Phase-wise |
| **Custom Development** | As per requirements |
| **Data Migration** | Before go-live |
| **User Training** | Throughout implementation |
| **Documentation** | Complete system documentation |
| **Post Go-Live Support** | Minimum 6 months |

---

## 8. Budget & Commercial Terms

### 8.1 Budget Framework

While Smart Dairy has allocated a budget for this initiative, we request vendors to provide:

| Cost Component | Details Required |
|---------------|------------------|
| **Software Licensing** | If any proprietary components are proposed |
| **Implementation Services** | Breakdown by phase and module |
| **Customization Development** | Hourly rates or fixed-price for custom modules |
| **Infrastructure/Hosting** | Cloud hosting costs or on-premise requirements |
| **Training** | User training and documentation costs |
| **Annual Maintenance** | Ongoing support and upgrade costs |
| **Total Cost of Ownership** | 5-year TCO projection |

### 8.2 Payment Terms

| Milestone | Payment % |
|-----------|-----------|
| Contract Signing | 20% |
| Phase 1 Completion | 20% |
| Phase 2 Completion | 20% |
| Phase 3 Completion | 20% |
| Final Acceptance | 15% |
| Post Go-Live (3 months) | 5% |

---

## 9. Proposal Submission Guidelines

### 9.1 Proposal Format

Proposals should be submitted in the following format:

1. **Executive Summary** (2 pages max)
2. **Company Profile** with relevant experience
3. **Understanding of Requirements**
4. **Proposed Solution Architecture**
5. **Implementation Methodology**
6. **Project Team Structure** with CVs of key personnel
7. **Detailed Work Plan** with timelines
8. **Investment Summary** with pricing breakdown
9. **Support & Maintenance Proposal**
10. **Client References** (minimum 3)

### 9.2 Submission Details

- **Format:** PDF document (maximum 50 pages) + separate Excel for pricing
- **Language:** English
- **Validity:** Proposals must remain valid for 90 days
- **Submission:** [To be provided]
- **Deadline:** [To be provided]

---

## 10. Appendices

### Appendix A: Smart Dairy Organization Structure

### Appendix B: Current Systems & Data Sources

### Appendix C: Sample Reports Required

### Appendix D: User Access Matrix

### Appendix E: Glossary of Terms

---

## 11. Contact Information

**Primary Contact:**
- Name: [To be provided]
- Title: [To be provided]
- Email: [To be provided]
- Phone: [To be provided]

**Technical Queries:**
- Email: [To be provided]

---

*This RFP is prepared by Smart Dairy Ltd. and is proprietary information. Distribution or sharing of this document without written permission is prohibited.*

---

**Document Control:**
| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Smart Dairy IT Team | Initial Release |

