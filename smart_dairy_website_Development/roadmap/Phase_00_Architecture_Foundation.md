# Phase 0: Architecture Foundation
## Months 1-2 (January - February 2026)

**Phase Goal:** Establish modular monolith project structure, common services layer, CI/CD pipeline, and development environment.
**Key Deliverable:** Running dev environment with auth working, empty module shells, database schemas created.

---

## Sprint Breakdown

### Sprint 0.1 (Weeks 1-2): Project Scaffolding

**Objectives:**
- Initialize monorepo with TypeScript configuration
- Set up Docker development environment (PostgreSQL 16, Redis 7, Nginx, Mosquitto)
- Configure Prisma ORM with multiSchema support (common, web, mkt, lvs, erp)
- Create modular monolith project structure with module boundaries
- Set up GitHub repository with branch protection

**Deliverables:**
- [ ] `docker-compose.yml` with all services starting cleanly
- [ ] Prisma schema with all 5 schemas defined (tables as empty shells)
- [ ] Express.js API gateway with module routing (`/api/v1/{module}/*`)
- [ ] Next.js 15 project with Tailwind CSS + shadcn/ui initialized
- [ ] TypeScript compilation passing across all modules
- [ ] `.env.example` with all required environment variables

**Tasks:**
1. Initialize Node.js project with TypeScript strict mode
2. Create directory structure: `src/modules/{website,marketplace,livestock,erp,common}/`
3. Configure Docker: PostgreSQL 16, Redis 7, Mosquitto, Nginx
4. Write Prisma schema with `CREATE SCHEMA` for all 5 schemas
5. Run `prisma migrate dev` to create all 105 tables
6. Set up Express.js with module router registration
7. Initialize Next.js 15 with App Router
8. Configure Tailwind CSS + shadcn/ui component library
9. Set up ESLint + Prettier with shared config
10. Create GitHub repo with `.gitignore`, `README.md`

### Sprint 0.2 (Weeks 3-4): Common Services Layer

**Objectives:**
- Implement JWT authentication with RBAC (10 roles, module:resource:action permissions)
- Build file upload service (DigitalOcean Spaces / local storage)
- Create notification service abstraction (email, SMS, push, in-app)
- Set up BullMQ job queues
- Configure CI/CD pipeline (GitHub Actions)

**Deliverables:**
- [ ] User registration and login endpoints working
- [ ] JWT access/refresh token flow complete
- [ ] RBAC middleware enforcing `module:resource:action` permissions
- [ ] File upload to local storage (Spaces integration in Phase 1)
- [ ] Email queue processing (SendGrid integration)
- [ ] SMS queue processing (BulkSMSBD integration)
- [ ] BullMQ dashboard accessible for queue monitoring
- [ ] GitHub Actions: lint, type-check, test on every push
- [ ] Docker build and deploy script

**Tasks:**
1. Implement `common.users`, `common.roles`, `common.permissions` table operations
2. Build Passport.js + JWT auth strategy
3. Create auth middleware with role and permission checking
4. Implement refresh token rotation
5. Build file upload service with Multer + Sharp (thumbnail generation)
6. Create notification service with channel abstraction
7. Set up BullMQ queues: email, sms, push-notification
8. Write email worker (SendGrid)
9. Write SMS worker (BulkSMSBD)
10. Set up node-cron scheduler (empty jobs, ready for Phase 2+)
11. Configure GitHub Actions CI pipeline
12. Write seed script for development data (admin user, test roles)

### Sprint 0.3 (Weeks 5-6): Module Shell Registration

**Objectives:**
- Register all 5 module route handlers (empty, returning 501 Not Implemented)
- Create shared TypeScript interfaces for cross-module communication
- Set up internal event bus
- Implement audit logging
- Write integration tests for auth flow

**Deliverables:**
- [ ] All API route prefixes responding: `/api/v1/auth`, `/api/v1/common`, `/api/v1/website`, `/api/v1/marketplace`, `/api/v1/livestock`, `/api/v1/erp`, `/api/v1/mobile`
- [ ] Typed event bus with publish/subscribe working
- [ ] Audit log table recording all mutations
- [ ] Integration tests for: register, login, refresh, RBAC enforcement
- [ ] API documentation (auto-generated or Postman collection)
- [ ] Development seed data: admin user, sample roles, test permissions

**Tasks:**
1. Create module registration pattern (each module exports routes)
2. Build typed event bus (in-process pub/sub)
3. Implement audit logging middleware
4. Create health check endpoint (`/api/v1/health`)
5. Write integration tests for complete auth flow
6. Generate Postman collection for all auth endpoints
7. Create development seed script
8. Document module boundary rules and conventions

### Sprint 0.4 (Weeks 7-8): Polish & Handoff

**Objectives:**
- Performance baseline testing
- Security hardening (rate limiting, CORS, helmet)
- Documentation for Phase 1 handoff
- Deployment to staging environment (DigitalOcean)

**Deliverables:**
- [ ] Rate limiting configured per endpoint type
- [ ] CORS, Helmet, and security headers in place
- [ ] Staging deployment on DigitalOcean with SSL
- [ ] Architecture documentation updated with any changes
- [ ] Phase 0 retrospective completed

---

## Phase 0 Verification Checklist

- [ ] `docker-compose up` starts all services without errors
- [ ] `POST /api/v1/auth/register` creates user with correct role
- [ ] `POST /api/v1/auth/login` returns JWT tokens
- [ ] Protected endpoint returns 401 without token
- [ ] RBAC returns 403 when role lacks required permission
- [ ] File upload stores file and returns URL
- [ ] Email queue processes and delivers test email
- [ ] CI/CD pipeline runs lint, type-check, tests on push
- [ ] All 7 module route prefixes respond (even if 501)
- [ ] Prisma migrations create all 105 tables across 5 schemas

---

## Risk Assessment

| Risk | Mitigation |
|------|------------|
| Prisma multiSchema complexity | Design all schemas upfront; test migrations early |
| Docker networking issues | Use docker-compose networks; document port mappings |
| Over-engineering common services | Keep services minimal; add features as modules need them |

---

**Phase 0 Duration:** 8 weeks (4 sprints)
**Dependencies:** None (this is the foundation)
**Next Phase:** Phase 1 (Public Website + E-commerce)
