# Smart Dairy Smart Portal + ERP System

## Project Overview

This is the **Smart Dairy Smart Web Portal System & Integrated ERP** documentation repository for **Smart Dairy Ltd.**, a progressive, technology-driven dairy farming enterprise based in Bangladesh and a subsidiary of Smart Group.

### Company Context

| Attribute | Details |
|-----------|---------|
| **Company** | Smart Dairy Ltd. |
| **Parent Organization** | Smart Group (Smart Technologies BD Ltd.) |
| **Industry** | Dairy Farming, Livestock & Organic Food Production |
| **Head Office** | Jahir Smart Tower, Dhaka-1207, Bangladesh |
| **Farm Location** | Islambag Kali, Vulta, Rupgonj, Narayanganj, Bangladesh |
| **Current Operations** | 255 cattle, 75 lactating cows, 900L/day milk production |
| **2-Year Target** | 800 cattle, ~250 lactating cows, 3,000L/day production |

### Project Vision

Transform Smart Dairy from a traditional 255-cattle farm operation into a digitally-enabled BDT 100+ Crore integrated dairy ecosystem with:
- Seamless online B2C and B2B commerce
- Real-time farm management with IoT integration
- Mobile-first field operations
- Data-driven decision making
- Scalable, cloud-native architecture

## Technology Stack

### Core Platform

| Layer | Technology | Version/Details |
|-------|------------|-----------------|
| **ERP Core** | Odoo 19 Community Edition | Python 3.11+, LGPL-3 License |
| **Backend Services** | Python FastAPI | ASGI server with Uvicorn |
| **Mobile Apps** | Flutter | Dart 3.2+, iOS 14+, Android 8+ |
| **Frontend Web** | OWL Framework (Odoo) | Bootstrap 5.3, JavaScript ES2022+ |

### Database & Storage

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Primary Database** | PostgreSQL 16 | Transactional data, multi-schema |
| **Cache Layer** | Redis 7 | Sessions, caching, Celery broker |
| **Time-Series Data** | TimescaleDB | IoT sensor data, metrics |
| **Search Engine** | Elasticsearch 8 | Product/catalog search |
| **Object Storage** | MinIO/S3 | Files, images, backups |

### Infrastructure & DevOps

| Component | Technology |
|-----------|------------|
| **Containerization** | Docker 24.0+, Docker Compose |
| **Orchestration** | Kubernetes (production) |
| **Web Server** | Nginx 1.24+ (reverse proxy) |
| **WSGI Server** | Gunicorn with Gevent workers |
| **CI/CD** | GitHub Actions / GitLab CI |
| **IaC** | Terraform, Ansible, Helm |
| **Monitoring** | Prometheus, Grafana, ELK Stack |

### Integration Technologies

| Integration | Technology |
|-------------|------------|
| **Payment Gateways** | bKash, Nagad, Rocket, SSLCommerz REST APIs |
| **SMS Gateway** | Bulk SMS providers (REST/SMPP) |
| **IoT Protocol** | MQTT (Mosquitto/EMQX), LoRaWAN |
| **Authentication** | OAuth 2.0 + JWT |
| **Push Notifications** | Firebase Cloud Messaging |

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                               │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Public       │  │ B2C          │  │ B2B          │  │ Mobile       │  │
│  │ Website      │  │ E-commerce   │  │ Portal       │  │ Applications │  │
│  │ (Odoo CMS)   │  │ (Odoo+Custom)│  │ (Custom)     │  │ (Flutter)    │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      APPLICATION LAYER                                   │
│  ┌─────────────────────────┐      ┌─────────────────────────────────┐   │
│  │   ERP CORE (Odoo 19 CE) │      │   CUSTOM MODULES (Python/Odoo)  │   │
│  │   - Standard Modules    │◄────►│   - smart_farm_mgmt             │   │
│  │   - Inventory, Sales    │      │   - smart_b2b_portal            │   │
│  │   - Accounting, HR      │      │   - smart_iot                   │   │
│  │   - Manufacturing       │      │   - smart_bd_localization       │   │
│  └─────────────────────────┘      │   - smart_mobile_api            │   │
│                                   └─────────────────────────────────┘   │
│  ┌─────────────────────────┐      ┌─────────────────────────────────┐   │
│  │   API Gateway (FastAPI) │      │   BACKGROUND PROCESSING (Celery)│   │
│  │   - REST APIs           │      │   - Email notifications         │   │
│  │   - GraphQL             │      │   - Report generation           │   │
│  │   - Webhooks            │      │   - IoT data processing         │   │
│  └─────────────────────────┘      └─────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                         DATA LAYER                                       │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐              │
│  │ PostgreSQL 16  │  │ Redis 7        │  │ Elasticsearch  │              │
│  │ (Primary DB)   │  │ (Cache/Queue)  │  │ (Search/Logs)  │              │
│  └────────────────┘  └────────────────┘  └────────────────┘              │
└─────────────────────────────────────────────────────────────────────────┘
```

### Architecture Patterns

1. **Modular Monolith + Microservices Hybrid**
   - Odoo modular monolith for standard ERP (70% of functionality)
   - Custom microservices for dairy-specific requirements (30%)
   - FastAPI microservices for IoT, mobile APIs, payment gateway

2. **Layered Architecture**
   - Presentation Layer: Web, Mobile, External APIs
   - Business Logic Layer: Domain models, workflows, service classes
   - Data Access Layer: Odoo ORM, SQLAlchemy, repository pattern
   - Infrastructure Layer: Database, external services, caching

3. **Event-Driven Architecture**
   - Redis Pub/Sub for internal events
   - Celery for background task processing
   - Webhooks for third-party integrations

## Project Structure

```
smart_dairy_website_Development/
├── 01_Smart_Dairy_Company_Profile.md          # Company overview
├── 02_Smart_Dairy_RFP_Smart_Web_Portal_System.md  # RFP document
├── Smart_Dairy_Implementation_Strategy_Comprehensive_Analysis.md
│
├── docs/                                      # Documentation
│   ├── BRD/                                  # Business Requirements
│   │   ├── 01_Smart_Dairy_BRD_Part_1_Executive_Strategic_Overview.md
│   │   ├── 02_Smart_Dairy_BRD_Part_2_Functional_Requirements.md
│   │   ├── 03_Smart_Dairy_BRD_Part_3_Technical_Architecture.md
│   │   └── 04_Smart_Dairy_BRD_Part_4_Implementation_Roadmap.md
│   │
│   ├── design/                               # UI/UX Design Documents
│   │   ├── README.md
│   │   ├── A-001_UI-UX-Design-System.md
│   │   ├── A-002_Brand-Digital-Guidelines.md
│   │   ├── A-011_Component-Library-Documentation.md
│   │   └── A-013_Responsive-Design-Specifications.md
│   │
│   ├── RFP/                                  # Portal-specific RFPs
│   ├── Smart_Dairy_Software_Requirements_Specification_SRS.md
│   ├── Smart_Dairy_Technology_Stack_Document.md
│   ├── Smart_Dairy_User_Requirements_Document_URD.md
│   │
│   └── Implementation_document_list/         # Implementation Docs
│       ├── FOUNDATION/
│       │   └── core architechture/
│       │       ├── B-001_Technical_Design_Document_TDD.md
│       │       ├── B-002_Odoo_Development_Guide.md
│       │       ├── C-001_Database_Design_Document.md
│       │       ├── C-003_Data_Dictionary.md
│       │       ├── D-001_Infrastructure_Architecture.md
│       │       ├── E-001_API_Specification_Document.md
│       │       └── F-001_Security_Architecture.md
│       ├── BACKEND DEVELOPMENT DOCUMENTATION/
│       ├── DATABASE & DATA MANAGEMENT DOCUMENTATION/
│       ├── DEVOPS & INFRASTRUCTURE DOCUMENTATION/
│       ├── COMMERCE/
│       └── ... (other categories)
│
├── Implemenattion_Roadmap/                   # 15-Phase Roadmap
│   ├── MASTER_Implementation_Roadmap.md
│   ├── Phase_1/ through Phase_15/
│   └── PROJECT_TIMELINE_VISUAL.md
│
├── Smart-dairy_business/                     # Business Portfolio
│   └── 10_Smart_Dairy_Business_Portfolio_*.md
│
└── AGENTS.md                                 # This file
```

## Implementation Approach

### Recommended Option: Odoo 19 CE + Strategic Custom Development

**Distribution of Work:**
- **70%** - Odoo 19 CE Standard Modules (Configuration)
- **10%** - Community Apps (Extension)
- **20%** - Custom Development (Dairy-specific)

### Custom Modules

| Module Name | Purpose | Technology |
|-------------|---------|------------|
| `smart_farm_mgmt` | Herd management, breeding, health, milk production | Python/Odoo |
| `smart_b2b_portal` | B2B ordering, pricing engine, credit management | Python/Odoo |
| `smart_iot` | IoT data ingestion, sensor management | FastAPI |
| `smart_bd_local` | Bangladesh VAT, payroll, compliance | Python/Odoo |
| `smart_mobile_api` | Mobile app backend APIs | FastAPI |
| `smart_subscription` | Subscription management engine | Python/Odoo |
| `smart_analytics` | Advanced analytics and BI | Python/Odoo |

## Development Environment

### System Requirements

#### Development (Minimum)
| Resource | Specification |
|----------|---------------|
| CPU | 2 cores |
| RAM | 4 GB |
| Storage | 20 GB SSD |
| OS | Ubuntu 22.04 LTS / Debian 12 |

#### Production (Recommended)
| Resource | Specification |
|----------|---------------|
| CPU | 8 cores (Intel Xeon / AMD EPYC) |
| RAM | 32 GB ECC |
| Storage | 500 GB NVMe SSD |
| OS | Ubuntu 24.04 LTS |

### Required Software

```bash
# Core Dependencies
Python 3.11+
PostgreSQL 16+
Node.js 18+ LTS
Redis 7+

# Python Packages (requirements.txt)
Babel==2.12.1
cryptography==41.0.7
Jinja2==3.1.2
lxml==4.9.3
Pillow==10.0.1
psycopg2==2.9.9
reportlab==3.6.13
Werkzeug==2.3.7

# Custom Dependencies
paho-mqtt==1.6.1          # MQTT for IoT
fastapi==0.104.1          # Custom APIs
uvicorn==0.24.0           # ASGI server
celery==5.3.4             # Background tasks
pandas==2.1.3             # Data processing
numpy==1.26.2             # Numerical computing
```

## Build and Development Commands

### Odoo Development

```bash
# Setup virtual environment
python3.11 -m venv venv
source venv/bin/activate

# Install Odoo dependencies
pip install -r requirements.txt

# Run Odoo in development mode
./odoo-bin --config=odoo.conf --dev=all

# Update specific module
./odoo-bin -u smart_farm_mgmt -d smart_dairy --stop-after-init

# Install new module
./odoo-bin -i smart_b2b_portal -d smart_dairy --stop-after-init
```

### Docker Development

```bash
# Build and start all services
docker-compose up -d

# View logs
docker-compose logs -f web

# Run database migrations
docker-compose exec web odoo -u all -d smart_dairy --stop-after-init

# Shell access
docker-compose exec web bash
```

### Flutter Mobile Development

```bash
# Install dependencies
flutter pub get

# Run in debug mode
flutter run

# Build APK
flutter build apk --release

# Build iOS
flutter build ios --release
```

### Testing Commands

```bash
# Run Python unit tests
pytest --cov=.

# Run Odoo tests
./odoo-bin -u smart_farm_mgmt --test-enable --stop-after-init

# Run Flutter tests
flutter test

# Static analysis
pylint custom_addons/
flutter analyze
```

## Code Style Guidelines

### Python (Odoo/Backend)

- Follow PEP 8 style guide
- Use 4 spaces for indentation
- Maximum line length: 100 characters
- Docstrings: Google style
- Type hints where applicable

```python
# Example Odoo Model
class FarmAnimal(models.Model):
    """Farm Animal Record Model.
    
    This model tracks individual animals in the herd including
    their lifecycle, health status, and production data.
    
    Attributes:
        name: Unique animal identifier
        rfid_tag: RFID tag number for scanning
        status: Current lifecycle status
    """
    
    _name = 'farm.animal'
    _description = 'Farm Animal Record'
    
    name = fields.Char('Animal ID', required=True)
    rfid_tag = fields.Char('RFID Tag')
    status = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating'),
        ('dry', 'Dry'),
    ], default='calf')
    
    @api.depends('birth_date')
    def _compute_age(self):
        """Compute animal age in months."""
        for animal in self:
            if animal.birth_date:
                animal.age = (fields.Date.today() - animal.birth_date).days / 30
```

### JavaScript (Frontend)

- Use ES2022+ features
- 2 spaces for indentation
- camelCase for variables/functions
- PascalCase for classes
- Single quotes for strings

### Dart (Flutter)

- Follow Effective Dart guidelines
- 2 spaces for indentation
- Use `const` constructors where possible
- Prefer single quotes for strings

## Testing Strategy

### Testing Levels

| Level | Framework | Coverage Target |
|-------|-----------|-----------------|
| **Unit Tests** | pytest (Python), flutter_test (Dart) | >80% |
| **Integration Tests** | pytest-django, Postman | Critical paths |
| **E2E Tests** | Selenium, Appium | Key user flows |
| **Performance Tests** | Locust, k6 | Load, stress |

### Test Requirements by Module

- **Farm Management**: Animal lifecycle, breeding workflows, health alerts
- **B2C E-commerce**: Checkout flow, payment processing, subscription logic
- **B2B Portal**: Credit limit validation, tier pricing, bulk ordering
- **IoT Integration**: Data ingestion, alert generation, offline sync

### Key Test Scenarios

```python
# Example: Farm Animal Registration Test
def test_animal_registration():
    """Test creating a new farm animal record."""
    animal = env['farm.animal'].create({
        'name': 'SD-001',
        'rfid_tag': 'E200341502001080',
        'birth_date': '2024-01-15',
        'gender': 'female'
    })
    assert animal.age > 0
    assert animal.status == 'calf'
```

## Security Considerations

### Defense in Depth

```
┌─────────────────────────────────────────────────────────┐
│  PERIMETER                                              │
│  - DDoS Protection (Cloudflare/AWS Shield)             │
│  - Web Application Firewall (WAF)                      │
│  - Rate Limiting                                       │
├─────────────────────────────────────────────────────────┤
│  NETWORK                                                │
│  - VPC Isolation                                       │
│  - Security Groups                                     │
│  - VPN for Admin Access                                │
├─────────────────────────────────────────────────────────┤
│  APPLICATION                                            │
│  - TLS 1.3 Encryption                                  │
│  - Input Validation                                    │
│  - CSRF/XSS Protection                                 │
│  - Security Headers (CSP, HSTS)                        │
├─────────────────────────────────────────────────────────┤
│  DATA                                                   │
│  - AES-256 Encryption at Rest                          │
│  - Field-level Encryption for PII                      │
│  - Encrypted Backups                                   │
├─────────────────────────────────────────────────────────┤
│  ACCESS CONTROL                                         │
│  - OAuth 2.0 + JWT Authentication                      │
│  - Role-Based Access Control (RBAC)                    │
│  - Multi-Factor Authentication (MFA)                   │
│  - Comprehensive Audit Logging                         │
└─────────────────────────────────────────────────────────┘
```

### Security Requirements

| Category | Requirement |
|----------|-------------|
| **Authentication** | OAuth 2.0 + OpenID Connect, JWT tokens |
| **Password Policy** | Argon2/bcrypt hashing, min 8 chars, complexity |
| **Session Management** | Redis-backed, 30-min timeout, secure cookies |
| **API Security** | Rate limiting, API keys, request signing |
| **Data Protection** | GDPR compliance, data localization options |
| **Compliance** | ISO 27001, PCI DSS for payments |

### Secure Coding Checklist

- [ ] Input validation on all user inputs
- [ ] Parameterized queries (prevent SQL injection)
- [ ] Output encoding (prevent XSS)
- [ ] CSRF tokens on state-changing operations
- [ ] Secure file upload validation
- [ ] Secrets management (Vault/environment variables)
- [ ] Dependency vulnerability scanning (Snyk, Trivy)

## Deployment Process

### Environment Strategy

| Environment | Purpose | Deployment Trigger |
|-------------|---------|-------------------|
| **Development** | Local development | Manual |
| **Staging** | Integration testing | Auto on PR merge |
| **Production** | Live system | Manual approval |

### CI/CD Pipeline

```
Code Commit → Build Image → Test + Scan → Deploy to Staging → UAT → Deploy to Prod
```

| Stage | Tools | Purpose |
|-------|-------|---------|
| **Source Control** | Git/GitHub | Version control |
| **Build** | Docker, Kaniko | Container builds |
| **Test** | pytest, unittest | Automated testing |
| **Security Scan** | Trivy, SonarQube | Vulnerability scanning |
| **Deploy** | ArgoCD, Helm | GitOps deployment |
| **Monitor** | Prometheus, Grafana | Observability |

### Production Deployment Checklist

- [ ] All tests passing
- [ ] Security scan clean
- [ ] Database backups verified
- [ ] Environment variables configured
- [ ] SSL certificates valid
- [ ] Health checks configured
- [ ] Monitoring dashboards active
- [ ] Rollback plan documented

## Localization (Bangladesh)

### Language Support

| Language | Priority | Usage |
|----------|----------|-------|
| **Bengali (Bangla)** | Primary | Farm workers, B2C customers |
| **English** | Secondary | Management, B2B partners |

### Localization Requirements

| Feature | Implementation |
|---------|----------------|
| **Chart of Accounts** | Bangladesh accounting standards |
| **VAT/GST** | Bangladesh VAT structure, Mushak forms |
| **Currency** | BDT (Bangladeshi Taka) native support |
| **Date Format** | DD/MM/YYYY |
| **Number Format** | Indian numbering system (lakhs, crores) |
| **Mobile Payments** | bKash, Nagad, Rocket integration |

## Performance Requirements

| Metric | Requirement | Measurement |
|--------|-------------|-------------|
| Page Load Time | < 3 seconds | Google Lighthouse |
| API Response Time | < 500ms (95th percentile) | APM monitoring |
| Database Query Time | < 100ms average | PostgreSQL logs |
| Concurrent Users | 1,000+ | Load testing |
| System Uptime | 99.9% SLA | Monitoring |
| Mobile App Launch | < 2 seconds | App telemetry |

## Documentation Reference

### Key Documents

| Document | Location | Purpose |
|----------|----------|---------|
| Company Profile | `01_Smart_Dairy_Company_Profile.md` | Business overview |
| RFP | `02_Smart_Dairy_RFP_Smart_Web_Portal_System.md` | Requirements |
| Technology Stack | `docs/Smart_Dairy_Technology_Stack_Document.md` | Technical stack |
| SRS | `docs/Smart_Dairy_Software_Requirements_Specification_SRS.md` | Software requirements |
| URD | `docs/Smart_Dairy_User_Requirements_Document_URD.md` | User requirements |
| TDD | `docs/Implementation_document_list/.../B-001_Technical_Design_Document_TDD.md` | Technical design |
| Master Roadmap | `Implemenattion_Roadmap/MASTER_Implementation_Roadmap.md` | Implementation plan |

### Documentation Categories

- **A Series**: Frontend/UI/UX Design (15 documents)
- **B Series**: Backend Development (15 documents)
- **C Series**: Database & Data Management (12 documents)
- **D Series**: DevOps & Infrastructure (15 documents)
- **E Series**: Integration & APIs (18 documents)
- **F Series**: Security & Compliance (14 documents)
- **G Series**: Testing & QA (22 documents)
- **H Series**: Mobile Development (18 documents)
- **I Series**: IoT & Smart Farming

**Total: 160+ implementation documents**

## Contact & Support

For questions about this project:
- **Design Team**: design@smartdairy.com.bd
- **Development Team**: dev@smartdairy.com.bd

---

*Last Updated: February 2026*
*Document Version: 1.0*
