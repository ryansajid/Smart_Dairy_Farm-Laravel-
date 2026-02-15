# TECHNICAL ARCHITECTURE & SPECIFICATIONS
## Smart Dairy Website Development Project

**Document:** 03 - Technical Architecture  
**Version:** 1.0  
**Date:** December 2, 2025  
**For:** Solo Full-Stack Developer on Linux

---

## TABLE OF CONTENTS

1. [Technology Stack](#1-technology-stack)
2. [System Architecture](#2-system-architecture)
3. [Frontend Architecture](#3-frontend-architecture)
4. [Backend Architecture](#4-backend-architecture)
5. [Database Architecture](#5-database-architecture)
6. [API Design](#6-api-design)
7. [Security Architecture](#7-security-architecture)
8. [File Storage & Media](#8-file-storage--media)
9. [Third-Party Integrations](#9-third-party-integrations)
10. [Performance Optimization](#10-performance-optimization)

---

## 1. TECHNOLOGY STACK

### 1.1 Frontend Stack

#### Core Framework
```
React.js: v18.2+
- Component-based architecture
- Virtual DOM for performance
- Large ecosystem and community
- Easy to learn and maintain for solo developer

Next.js: v14.2+ (React Framework)
- Server-side rendering (SSR) for SEO
- Static site generation (SSG)
- API routes (optional backend)
- File-based routing
- Image optimization built-in
- TypeScript support
```

#### UI Framework & Styling
```
Option 1: Tailwind CSS (Recommended)
- Utility-first CSS
- Rapid development
- Small production bundle
- Highly customizable
- PostCSS integration

Option 2: Material-UI (MUI)
- Pre-built components
- Faster initial development
- Professional look out of the box
- Larger bundle size

Recommendation: Tailwind CSS + Headless UI
- More control for custom designs
- Better performance
- Smaller footprint
```

#### State Management
```
Option 1: Zustand (Recommended for solo dev)
- Simple API
- Minimal boilerplate
- Small size (~1KB)
- TypeScript support
- Perfect for medium-scale apps

Option 2: Redux Toolkit
- Industry standard
- DevTools excellent
- More complex setup
- Overkill for this project

Recommendation: Zustand for simplicity
```

#### Form Handling
```
React Hook Form
- Performant (uncontrolled components)
- Simple validation (with Yup or Zod)
- TypeScript support
- Minimal re-renders
```

#### Data Fetching
```
Axios
- Promise-based
- Request/response interceptors
- Automatic JSON transforms
- Wide browser support

Alternative: Native Fetch API
- Built-in
- No additional dependency
- Requires more configuration

Recommendation: Axios for convenience
```

#### Additional Frontend Libraries
```
- React Router: v6+ (if not using Next.js routing)
- Framer Motion: Animations
- React Toastify: Toast notifications
- React Icons: Icon library
- date-fns: Date manipulation
- Chart.js or Recharts: Data visualization
- Pannellum or Marzipano: 360° virtual tour
- React Player: Video playback
```

### 1.2 Backend Stack

#### Runtime & Framework
```
Node.js: v18 LTS or v20 LTS
- JavaScript/TypeScript throughout
- Large ecosystem (npm)
- Excellent for I/O-heavy operations
- Single language for full stack

Express.js: v4.18+
- Minimal and flexible
- Large middleware ecosystem
- Well-documented
- Easy to learn

Alternative: Fastify (if performance critical)
- Faster than Express
- More opinionated
- Growing ecosystem

Recommendation: Express.js (tried and true)
```

#### Language
```
TypeScript: v5.0+
- Type safety
- Better IDE support
- Catch errors at compile time
- Self-documenting code
- Slight learning curve but worth it

Recommendation: Use TypeScript for both frontend and backend
```

#### Authentication
```
Passport.js
- Authentication middleware
- Multiple strategies (local, JWT, OAuth)
- Well-maintained

jsonwebtoken (JWT)
- Stateless authentication
- Scalable
- Standard implementation

bcrypt or argon2
- Password hashing
- Industry standard
```

#### Validation & Sanitization
```
Joi or Zod
- Schema-based validation
- TypeScript integration (Zod better for TS)
- Request validation middleware
```

#### File Upload
```
Multer
- Express middleware
- Multiple storage options (disk, memory)
- File filtering
```

#### Background Jobs (Optional)
```
node-cron or Bull
- Scheduled tasks
- Email campaigns
- Subscription processing
- Report generation

Recommendation: Start with node-cron, upgrade to Bull if needed
```

### 1.3 Database Stack

#### Primary Database
```
PostgreSQL: v14+ or v15+
- ACID compliant
- Robust and reliable
- JSON support (flexible schema)
- Excellent for relational data
- Open source
- Good performance

Why PostgreSQL over MySQL:
- Better JSON support
- More advanced features
- Stronger data integrity
- Superior handling of complex queries
```

#### Cache Layer
```
Redis: v7+
- In-memory data store
- Fast read/write
- Use cases:
  - Session storage
  - Cache frequently accessed data
  - Rate limiting
  - Temporary data (OTP codes)
  - Message queue (simple pub/sub)
```

### 1.4 Headless CMS

```
Strapi: v4.15+ (Open Source)
- Node.js based (same stack)
- RESTful and GraphQL APIs
- Customizable admin panel
- Content types builder
- Media library
- Role-based permissions
- Plugin system
- Free and self-hostable

Why Strapi:
- Perfect for solo developer
- Easy to set up
- Same language as backend
- Rich admin interface for non-technical users
- Active community
```

### 1.5 Development Tools

#### Version Control
```
Git: Latest version
- Distributed version control
- GitHub or GitLab for repository

Branching Strategy:
- main/master: Production-ready code
- develop: Development branch
- feature/xxx: Feature branches
- hotfix/xxx: Urgent fixes
```

#### Code Editor
```
Visual Studio Code
- Excellent for JavaScript/TypeScript
- Extensions ecosystem:
  - ESLint
  - Prettier
  - TypeScript
  - GitLens
  - Tailwind CSS IntelliSense
  - ES7+ React/Redux/React-Native snippets
  - REST Client (API testing)
  - Database client (PostgreSQL)
```

#### Linting & Formatting
```
ESLint
- JavaScript/TypeScript linting
- Enforce code standards
- Catch errors early

Prettier
- Code formatter
- Consistent style
- Auto-format on save
```

#### API Testing
```
Postman or Insomnia
- API endpoint testing
- Collections for organization
- Environment variables
- Pre-request scripts

Alternative: VS Code REST Client extension
- Test APIs within VS Code
- Save requests in .http files
```

#### Database Client
```
DBeaver (Free, Open Source)
- Universal database tool
- PostgreSQL support
- Visual query builder
- ER diagrams

pgAdmin (PostgreSQL specific)
- Official PostgreSQL tool
- Web-based interface
```

### 1.6 Deployment & Infrastructure

#### Containerization (Optional but Recommended)
```
Docker
- Containerize application
- Consistent environments
- Easy deployment
- Docker Compose for local development

Docker Compose:
- Multi-container setup
- Define services (app, database, cache)
- One command to start all services
```

#### Hosting Options (Production)
```
Option 1: DigitalOcean (Recommended for start)
- Droplets (VPS): Starting at $6/month
- Easy to set up
- Good documentation
- PostgreSQL managed database: $15/month
- Spaces (object storage): $5/month for 250GB

Option 2: AWS (More complex but scalable)
- EC2 for application
- RDS for PostgreSQL
- S3 for file storage
- More expensive, steeper learning curve

Option 3: Traditional VPS (Cheapest)
- Linode, Vultr, or local providers
- Full control
- Manual setup
- More maintenance

Recommendation for Solo Dev: DigitalOcean
- Balance of cost, ease, and features
```

#### CI/CD (Continuous Integration/Deployment)
```
GitHub Actions (Recommended)
- Free for public repos
- Integrated with GitHub
- Automated testing and deployment
- Simple YAML configuration

Manual Deployment (Phase 1)
- SSH into server
- Git pull
- npm install
- Restart services
- Upgrade to automated later
```

### 1.7 Monitoring & Logging

```
Development:
- Console logging
- VS Code debugger
- React Developer Tools
- Redux DevTools (if using Redux)

Production:
- PM2 (Process manager)
  - Keep app running
  - Restart on crash
  - Log management
  - Monitoring dashboard
- Winston or Pino (Logging libraries)
  - Structured logging
  - Log levels
  - File rotation
- Sentry (Error tracking) - Free tier available
  - Frontend and backend error tracking
  - Performance monitoring
```

---

## 2. SYSTEM ARCHITECTURE

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                         CLIENT SIDE                          │
│                                                               │
│  ┌────────────────────────────────────────────────────┐    │
│  │          Next.js Application (React)                │    │
│  │                                                      │    │
│  │  - Server-Side Rendering (SSR)                     │    │
│  │  - Static Site Generation (SSG)                    │    │
│  │  - Client-Side Rendering (CSR)                     │    │
│  │  - API Routes (optional)                           │    │
│  │                                                      │    │
│  │  Pages:                                             │    │
│  │   • Public Website                                  │    │
│  │   • Product Catalog & Details                      │    │
│  │   • Cart & Checkout                                │    │
│  │   • User Dashboard                                 │    │
│  │   • B2B Portal                                     │    │
│  │   • Admin Panel                                    │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
                            ↕ HTTPS/REST API
┌─────────────────────────────────────────────────────────────┐
│                        SERVER SIDE                           │
│                                                               │
│  ┌────────────────────────────────────────────────────┐    │
│  │          Express.js Backend API                     │    │
│  │                                                      │    │
│  │  Controllers:                                       │    │
│  │   • Auth Controller                                 │    │
│  │   • Product Controller                             │    │
│  │   • Order Controller                               │    │
│  │   • User Controller                                │    │
│  │   • B2B Controller                                 │    │
│  │                                                      │    │
│  │  Middleware:                                        │    │
│  │   • Authentication (JWT)                           │    │
│  │   • Authorization (RBAC)                           │    │
│  │   • Validation (Joi/Zod)                           │    │
│  │   • Error Handling                                 │    │
│  │   • Rate Limiting                                  │    │
│  │                                                      │    │
│  │  Services:                                          │    │
│  │   • Email Service                                  │    │
│  │   • SMS Service                                    │    │
│  │   • Payment Service                                │    │
│  │   • Storage Service                                │    │
│  └────────────────────────────────────────────────────┘    │
│                                                               │
│  ┌────────────────────────────────────────────────────┐    │
│  │            Strapi CMS (Headless)                    │    │
│  │                                                      │    │
│  │  - Content Management                               │    │
│  │  - Media Library                                    │    │
│  │  - REST & GraphQL APIs                             │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
                            ↕ SQL/Redis
┌─────────────────────────────────────────────────────────────┐
│                       DATA LAYER                             │
│                                                               │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │              │  │              │  │              │     │
│  │  PostgreSQL  │  │    Redis     │  │     Local    │     │
│  │   Database   │  │    Cache     │  │    Storage   │     │
│  │              │  │              │  │   (Images)   │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│                                                               │
│  Tables:                                                      │
│   • users, customers, b2b_customers                          │
│   • products, categories, inventory                          │
│   • orders, order_items, payments                            │
│   • subscriptions, reviews, wishlists                        │
│   • blog_posts, resources                                    │
└─────────────────────────────────────────────────────────────┘
                            ↕
┌─────────────────────────────────────────────────────────────┐
│                   EXTERNAL SERVICES                          │
│                                                               │
│  • Payment Gateways (SSL Commerz, bKash, Nagad)            │
│  • SMS Gateway (Bangladesh provider)                         │
│  • Email Service (SMTP / SendGrid alternative)              │
│  • Google Maps API                                           │
│  • Google Analytics                                          │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Folder Structure

#### Frontend (Next.js)
```
smart-dairy-frontend/
├── public/
│   ├── images/
│   ├── videos/
│   └── favicon.ico
├── src/
│   ├── app/                    # Next.js 13+ App Router
│   │   ├── (auth)/            # Auth route group
│   │   │   ├── login/
│   │   │   └── register/
│   │   ├── (shop)/            # Shop route group
│   │   │   ├── products/
│   │   │   ├── cart/
│   │   │   └── checkout/
│   │   ├── (dashboard)/       # User dashboard
│   │   ├── (b2b)/             # B2B portal
│   │   ├── (admin)/           # Admin panel
│   │   ├── layout.tsx         # Root layout
│   │   ├── page.tsx           # Homepage
│   │   └── api/               # API routes (optional)
│   ├── components/
│   │   ├── common/            # Reusable components
│   │   ├── layout/            # Layout components
│   │   ├── forms/             # Form components
│   │   └── ui/                # UI primitives
│   ├── lib/                   # Utilities
│   │   ├── api.ts             # API client
│   │   ├── auth.ts            # Auth helpers
│   │   └── utils.ts
│   ├── store/                 # State management (Zustand)
│   │   ├── authStore.ts
│   │   ├── cartStore.ts
│   │   └── userStore.ts
│   ├── styles/
│   │   └── globals.css
│   └── types/                 # TypeScript types
│       ├── api.ts
│       └── models.ts
├── .env.local
├── next.config.js
├── tailwind.config.js
├── tsconfig.json
└── package.json
```

#### Backend (Express.js)
```
smart-dairy-backend/
├── src/
│   ├── config/
│   │   ├── database.ts         # DB configuration
│   │   ├── redis.ts            # Redis configuration
│   │   └── constants.ts        # App constants
│   ├── controllers/
│   │   ├── authController.ts
│   │   ├── productController.ts
│   │   ├── orderController.ts
│   │   ├── userController.ts
│   │   ├── b2bController.ts
│   │   └── adminController.ts
│   ├── middleware/
│   │   ├── auth.ts             # JWT authentication
│   │   ├── authorize.ts        # RBAC authorization
│   │   ├── validate.ts         # Request validation
│   │   ├── errorHandler.ts
│   │   └── rateLimiter.ts
│   ├── models/
│   │   ├── User.ts
│   │   ├── Product.ts
│   │   ├── Order.ts
│   │   ├── Category.ts
│   │   └── ...
│   ├── routes/
│   │   ├── authRoutes.ts
│   │   ├── productRoutes.ts
│   │   ├── orderRoutes.ts
│   │   ├── userRoutes.ts
│   │   ├── b2bRoutes.ts
│   │   └── adminRoutes.ts
│   ├── services/
│   │   ├── emailService.ts
│   │   ├── smsService.ts
│   │   ├── paymentService.ts
│   │   ├── storageService.ts
│   │   └── analyticsService.ts
│   ├── utils/
│   │   ├── helpers.ts
│   │   ├── logger.ts
│   │   └── validators.ts
│   ├── types/
│   │   └── express.d.ts
│   ├── database/
│   │   ├── migrations/
│   │   └── seeds/
│   ├── app.ts                  # Express app setup
│   └── server.ts               # Server entry point
├── .env
├── .env.example
├── tsconfig.json
└── package.json
```

#### Strapi CMS
```
smart-dairy-cms/
├── config/
│   ├── database.ts
│   ├── server.ts
│   └── plugins.ts
├── src/
│   ├── api/                    # Content types
│   │   ├── blog-post/
│   │   ├── product/
│   │   ├── category/
│   │   └── resource/
│   ├── extensions/            # Extend default features
│   └── plugins/               # Custom plugins
├── public/
│   └── uploads/               # Uploaded media
├── .env
└── package.json
```

---

## 3. FRONTEND ARCHITECTURE

### 3.1 Component Structure

#### Component Hierarchy
```
App
├── Layout (Header, Footer)
│   ├── Header
│   │   ├── Logo
│   │   ├── Navigation
│   │   │   ├── NavItem
│   │   │   └── MegaMenu
│   │   ├── SearchBar
│   │   ├── UserMenu
│   │   └── CartIcon
│   └── Footer
│       ├── FooterLinks
│       ├── Newsletter
│       └── SocialMedia
├── Pages
│   ├── Home
│   │   ├── HeroSection
│   │   ├── FeaturedProducts
│   │   ├── TechnologyShowcase
│   │   ├── VirtualTourTeaser
│   │   ├── Testimonials
│   │   └── BlogPreview
│   ├── ProductList
│   │   ├── ProductGrid
│   │   │   └── ProductCard
│   │   ├── Filters
│   │   └── Pagination
│   ├── ProductDetail
│   │   ├── ImageGallery
│   │   ├── ProductInfo
│   │   ├── AddToCart
│   │   ├── Tabs (Description, Nutrition, Reviews)
│   │   └── RelatedProducts
│   ├── Cart
│   │   ├── CartItems
│   │   │   └── CartItem
│   │   ├── CartSummary
│   │   └── PromoCode
│   ├── Checkout
│   │   ├── ProgressIndicator
│   │   ├── DeliveryForm
│   │   ├── PaymentMethod
│   │   └── OrderReview
│   ├── Dashboard
│   │   ├── DashboardSidebar
│   │   ├── OrderHistory
│   │   ├── Profile
│   │   ├── Addresses
│   │   └── Subscriptions
│   ├── VirtualTour
│   │   ├── Tour360Viewer
│   │   ├── Hotspots
│   │   ├── Navigation
│   │   └── AudioNarration
│   └── Admin
│       ├── AdminSidebar
│       ├── Dashboard
│       ├── ProductManagement
│       ├── OrderManagement
│       └── CustomerManagement
└── Common Components
    ├── Button
    ├── Input
    ├── Modal
    ├── Dropdown
    ├── Card
    ├── Badge
    ├── Alert
    ├── Toast
    ├── Loader
    └── Pagination
```

### 3.2 State Management Strategy

#### Local State (useState)
```typescript
// Component-specific state
const [isOpen, setIsOpen] = useState(false);
const [quantity, setQuantity] = useState(1);
```

#### Global State (Zustand)
```typescript
// cartStore.ts
import create from 'zustand';
import { persist } from 'zustand/middleware';

interface CartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
}

interface CartStore {
  items: CartItem[];
  addItem: (item: CartItem) => void;
  removeItem: (id: string) => void;
  updateQuantity: (id: string, quantity: number) => void;
  clearCart: () => void;
  total: () => number;
}

export const useCartStore = create<CartStore>()(
  persist(
    (set, get) => ({
      items: [],
      addItem: (item) =>
        set((state) => {
          const existing = state.items.find((i) => i.id === item.id);
          if (existing) {
            return {
              items: state.items.map((i) =>
                i.id === item.id
                  ? { ...i, quantity: i.quantity + item.quantity }
                  : i
              ),
            };
          }
          return { items: [...state.items, item] };
        }),
      removeItem: (id) =>
        set((state) => ({
          items: state.items.filter((i) => i.id !== id),
        })),
      updateQuantity: (id, quantity) =>
        set((state) => ({
          items: state.items.map((i) =>
            i.id === id ? { ...i, quantity } : i
          ),
        })),
      clearCart: () => set({ items: [] }),
      total: () =>
        get().items.reduce((sum, item) => sum + item.price * item.quantity, 0),
    }),
    {
      name: 'cart-storage', // LocalStorage key
    }
  )
);
```

#### Server State (React Query - Optional)
```typescript
// For server data caching and synchronization
import { useQuery, useMutation } from '@tanstack/react-query';

// Fetch products
const { data, isLoading, error } = useQuery({
  queryKey: ['products'],
  queryFn: () => fetchProducts(),
});

// Create order mutation
const mutation = useMutation({
  mutationFn: createOrder,
  onSuccess: () => {
    // Invalidate and refetch
    queryClient.invalidateQueries({ queryKey: ['orders'] });
  },
});
```

### 3.3 Routing Strategy

#### Next.js App Router (Recommended)
```
app/
├── (auth)/
│   ├── login/page.tsx
│   └── register/page.tsx
├── (shop)/
│   ├── products/
│   │   ├── page.tsx             # /products
│   │   └── [id]/page.tsx        # /products/[id]
│   ├── cart/page.tsx
│   └── checkout/page.tsx
├── (dashboard)/
│   ├── layout.tsx               # Dashboard layout
│   ├── page.tsx                 # Dashboard home
│   ├── orders/page.tsx
│   └── profile/page.tsx
├── (b2b)/
│   └── portal/page.tsx
├── (admin)/
│   ├── layout.tsx
│   ├── dashboard/page.tsx
│   ├── products/page.tsx
│   └── orders/page.tsx
├── about/page.tsx
├── contact/page.tsx
├── layout.tsx                   # Root layout
└── page.tsx                     # Homepage
```

**Benefits:**
- File-based routing
- Nested layouts
- Route groups for logical organization
- Automatic code splitting

### 3.4 API Client

```typescript
// lib/api.ts
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api',
  timeout: 10000,
});

// Request interceptor (add auth token)
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor (handle errors globally)
api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    if (error.response?.status === 401) {
      // Unauthorized - redirect to login
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// API methods
export const authAPI = {
  login: (credentials) => api.post('/auth/login', credentials),
  register: (userData) => api.post('/auth/register', userData),
  logout: () => api.post('/auth/logout'),
};

export const productAPI = {
  getAll: (params) => api.get('/products', { params }),
  getById: (id) => api.get(`/products/${id}`),
  create: (data) => api.post('/products', data),
  update: (id, data) => api.put(`/products/${id}`, data),
  delete: (id) => api.delete(`/products/${id}`),
};

// ... more API methods

export default api;
```

---

## 4. BACKEND ARCHITECTURE

### 4.1 MVC Pattern (Modified)

```
Model (Database Layer)
    ↓
Controller (Business Logic)
    ↓
Service (External APIs, Complex Logic)
    ↓
Middleware (Auth, Validation, Error Handling)
    ↓
Routes (API Endpoints)
```

### 4.2 API Structure

#### RESTful API Design
```
Base URL: /api/v1

Authentication:
POST   /auth/register              # Register new user
POST   /auth/login                 # Login user
POST   /auth/logout                # Logout user
POST   /auth/refresh-token         # Refresh JWT token
POST   /auth/forgot-password       # Request password reset
POST   /auth/reset-password        # Reset password

Users:
GET    /users/me                   # Get current user
PUT    /users/me                   # Update current user
GET    /users/:id                  # Get user by ID (admin)
GET    /users                      # Get all users (admin)

Products:
GET    /products                   # Get all products (with filters)
GET    /products/:id               # Get single product
POST   /products                   # Create product (admin)
PUT    /products/:id               # Update product (admin)
DELETE /products/:id               # Delete product (admin)
GET    /products/:id/reviews       # Get product reviews

Categories:
GET    /categories                 # Get all categories
GET    /categories/:id             # Get category
POST   /categories                 # Create category (admin)
PUT    /categories/:id             # Update category (admin)
DELETE /categories/:id             # Delete category (admin)

Cart:
GET    /cart                       # Get user cart
POST   /cart/items                 # Add item to cart
PUT    /cart/items/:id             # Update cart item
DELETE /cart/items/:id             # Remove cart item
DELETE /cart                       # Clear cart

Orders:
GET    /orders                     # Get user orders
GET    /orders/:id                 # Get order details
POST   /orders                     # Create order
PUT    /orders/:id/status          # Update order status (admin)
POST   /orders/:id/cancel          # Cancel order

Payments:
POST   /payments/initiate          # Initiate payment
POST   /payments/callback          # Payment gateway callback
GET    /payments/:id/status        # Check payment status

B2B:
POST   /b2b/apply                  # B2B application
GET    /b2b/account                # Get B2B account details
POST   /b2b/orders                 # Place B2B order
POST   /b2b/quotes/request         # Request quote
GET    /b2b/quotes                 # Get quotes
GET    /b2b/invoices               # Get invoices

Admin:
GET    /admin/dashboard            # Admin dashboard data
GET    /admin/customers            # Get all customers
GET    /admin/orders               # Get all orders
GET    /admin/analytics            # Get analytics data
```

### 4.3 Controller Example

```typescript
// controllers/productController.ts
import { Request, Response, NextFunction } from 'express';
import Product from '../models/Product';
import { asyncHandler } from '../utils/asyncHandler';

// @desc    Get all products
// @route   GET /api/v1/products
// @access  Public
export const getProducts = asyncHandler(
  async (req: Request, res: Response) => {
    // Extract query parameters
    const {
      page = 1,
      limit = 12,
      sort = '-createdAt',
      category,
      minPrice,
      maxPrice,
      search,
    } = req.query;

    // Build query
    let query: any = {};

    if (category) {
      query.category = category;
    }

    if (minPrice || maxPrice) {
      query.price = {};
      if (minPrice) query.price.$gte = Number(minPrice);
      if (maxPrice) query.price.$lte = Number(maxPrice);
    }

    if (search) {
      query.$or = [
        { name: { $regex: search, $options: 'i' } },
        { description: { $regex: search, $options: 'i' } },
      ];
    }

    // Execute query
    const products = await Product.find(query)
      .sort(sort as string)
      .limit(Number(limit))
      .skip((Number(page) - 1) * Number(limit))
      .populate('category', 'name slug');

    // Get total count for pagination
    const total = await Product.countDocuments(query);

    res.status(200).json({
      success: true,
      count: products.length,
      total,
      page: Number(page),
      pages: Math.ceil(total / Number(limit)),
      data: products,
    });
  }
);

// @desc    Get single product
// @route   GET /api/v1/products/:id
// @access  Public
export const getProduct = asyncHandler(
  async (req: Request, res: Response) => {
    const product = await Product.findById(req.params.id)
      .populate('category', 'name slug')
      .populate('reviews');

    if (!product) {
      return res.status(404).json({
        success: false,
        message: 'Product not found',
      });
    }

    res.status(200).json({
      success: true,
      data: product,
    });
  }
);

// @desc    Create product
// @route   POST /api/v1/products
// @access  Private/Admin
export const createProduct = asyncHandler(
  async (req: Request, res: Response) => {
    // Validation happens in middleware
    const product = await Product.create(req.body);

    res.status(201).json({
      success: true,
      data: product,
    });
  }
);

// ... more controller methods
```

### 4.4 Middleware

#### Authentication Middleware
```typescript
// middleware/auth.ts
import { Request, Response, NextFunction } from 'express';
import jwt from 'jsonwebtoken';
import User from '../models/User';

interface JwtPayload {
  id: string;
  role: string;
}

// Extend Express Request type
declare global {
  namespace Express {
    interface Request {
      user?: any;
    }
  }
}

export const protect = async (
  req: Request,
  res: Response,
  next: NextFunction
) => {
  let token;

  // Check for token in header
  if (
    req.headers.authorization &&
    req.headers.authorization.startsWith('Bearer')
  ) {
    token = req.headers.authorization.split(' ')[1];
  }

  // Make sure token exists
  if (!token) {
    return res.status(401).json({
      success: false,
      message: 'Not authorized to access this route',
    });
  }

  try {
    // Verify token
    const decoded = jwt.verify(
      token,
      process.env.JWT_SECRET!
    ) as JwtPayload;

    // Attach user to request
    req.user = await User.findById(decoded.id).select('-password');

    next();
  } catch (error) {
    return res.status(401).json({
      success: false,
      message: 'Not authorized to access this route',
    });
  }
};
```

#### Authorization Middleware (RBAC)
```typescript
// middleware/authorize.ts
import { Request, Response, NextFunction } from 'express';

export const authorize = (...roles: string[]) => {
  return (req: Request, res: Response, next: NextFunction) => {
    if (!req.user) {
      return res.status(401).json({
        success: false,
        message: 'Not authorized',
      });
    }

    if (!roles.includes(req.user.role)) {
      return res.status(403).json({
        success: false,
        message: `User role '${req.user.role}' is not authorized to access this route`,
      });
    }

    next();
  };
};

// Usage in routes:
// router.get('/admin/users', protect, authorize('admin'), getUsers);
```

#### Validation Middleware
```typescript
// middleware/validate.ts
import { Request, Response, NextFunction } from 'express';
import Joi from 'joi';

export const validate = (schema: Joi.ObjectSchema) => {
  return (req: Request, res: Response, next: NextFunction) => {
    const { error } = schema.validate(req.body, {
      abortEarly: false, // Return all errors
    });

    if (error) {
      const errors = error.details.map((detail) => ({
        field: detail.path.join('.'),
        message: detail.message,
      }));

      return res.status(400).json({
        success: false,
        message: 'Validation error',
        errors,
      });
    }

    next();
  };
};

// Validation schema
export const productSchema = Joi.object({
  name: Joi.string().required().min(3).max(100),
  sku: Joi.string().required().alphanum(),
  price: Joi.number().required().min(0),
  description: Joi.string().required().min(10),
  category: Joi.string().required(),
  stock: Joi.number().integer().min(0).default(0),
  // ... more fields
});

// Usage:
// router.post('/products', validate(productSchema), createProduct);
```

---

## 5. DATABASE ARCHITECTURE

### 5.1 PostgreSQL Schema Design

#### Users Table
```sql
CREATE TABLE users (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  role VARCHAR(50) DEFAULT 'customer', -- customer, b2b_customer, admin, content_manager, support
  email_verified BOOLEAN DEFAULT FALSE,
  email_verification_token VARCHAR(255),
  password_reset_token VARCHAR(255),
  password_reset_expires TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
```

#### B2B Customers Table
```sql
CREATE TABLE b2b_customers (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  user_id UUID REFERENCES users(id) ON DELETE CASCADE,
  business_name VARCHAR(255) NOT NULL,
  business_reg_number VARCHAR(100),
  business_type VARCHAR(100), -- restaurant, hotel, manufacturer, etc.
  tax_number VARCHAR(100),
  pricing_tier VARCHAR(50) DEFAULT 'standard', -- standard, premium, vip
  credit_limit DECIMAL(10, 2),
  outstanding_balance DECIMAL(10, 2) DEFAULT 0,
  account_manager_id UUID REFERENCES users(id),
  status VARCHAR(50) DEFAULT 'pending', -- pending, approved, rejected, suspended
  applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  approved_at TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Products Table
```sql
CREATE TABLE products (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  sku VARCHAR(100) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  short_description TEXT,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  sale_price DECIMAL(10, 2),
  b2b_price DECIMAL(10, 2),
  stock_quantity INTEGER DEFAULT 0,
  low_stock_threshold INTEGER DEFAULT 10,
  weight DECIMAL(10, 2), -- in grams or kg
  category_id UUID REFERENCES categories(id),
  is_featured BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  allow_reviews BOOLEAN DEFAULT TRUE,
  nutritional_info JSONB, -- Flexible for different nutrition facts
  ingredients TEXT[],
  allergens TEXT[],
  shelf_life VARCHAR(100),
  storage_instructions TEXT,
  meta_title VARCHAR(255),
  meta_description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_is_active ON products(is_active);
```

#### Categories Table
```sql
CREATE TABLE categories (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  parent_id UUID REFERENCES categories(id),
  image_url VARCHAR(500),
  display_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_categories_parent ON categories(parent_id);
```

(Continue in next artifact due to length...)

---

**END OF PART 1 - TECHNICAL ARCHITECTURE**

This document continues in the next artifact with:
- Orders and Payments schema
- API Design details
- Security Architecture
- File Storage strategies
- Third-party integrations
- Performance optimization
