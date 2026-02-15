# Smart Dairy Technology Stack Architecture
## Digital Ecosystem Full-Stack Development for Solo Developer

**Document Version:** 2.0
**Date:** January 31, 2026
**Target Environment:** Linux/Docker with Solo Developer Workflow
**Focus:** Modular Monolith, Multi-Platform Ecosystem, Performance, Security
**Scope:** Website + Marketplace + Livestock + ERP + Mobile App

---

## EXECUTIVE SUMMARY

This document provides a comprehensive technology stack architecture for the Smart Dairy project, specifically optimized for Linux development environments and solo developer efficiency. The stack builds upon the existing requirements analysis and technical specifications, adding Linux-specific optimizations, detailed configurations, and workflow enhancements to ensure maximum productivity and performance.

### Key Design Principles
1. **Linux-Native Optimization:** Leverage Linux strengths (filesystem, process management, terminal efficiency)
2. **Solo Developer Efficiency:** Minimize cognitive load, automate repetitive tasks
3. **Performance First:** Optimize for both development and production performance
4. **Security by Design:** PCI DSS compliance built into architecture
5. **Scalability Foundation:** Architecture that grows with project complexity

---

## 1. FRONTEND ARCHITECTURE

### 1.1 Next.js 14+ Linux Optimization

#### Core Configuration
```javascript
// next.config.js - Linux-optimized configuration
/** @type {import('next').NextConfig} */
const nextConfig = {
  // Linux filesystem optimization
  webpack: (config, { isServer }) => {
    // Optimize for Linux filesystem
    config.watchOptions = {
      poll: 1000, // Reduce CPU usage on Linux
      aggregateTimeout: 300,
      ignored: ['**/node_modules/**', '**/.git/**']
    };
    
    // Optimize builds for Linux
    if (process.env.NODE_ENV === 'production') {
      config.optimization.splitChunks = {
        chunks: 'all',
        cacheGroups: {
          vendor: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendors',
            chunks: 'all',
          },
        },
      };
    }
    
    return config;
  },
  
  // Linux-specific performance optimizations
  experimental: {
    // Enable Linux-native optimizations
    appDir: true,
    serverComponentsExternalPackages: ['@prisma/client'],
    // Optimize for Linux file system
    isrMemoryCacheSize: 50, // Reduced for Linux memory efficiency
  },
  
  // Image optimization for Linux
  images: {
    formats: ['image/webp', 'image/avif'],
    deviceSizes: [640, 750, 828, 1080, 1200, 1920, 2048, 3840],
    imageSizes: [16, 32, 48, 64, 96, 128, 256, 384],
    minimumCacheTTL: 60 * 60 * 24 * 30, // 30 days
  },
  
  // Linux development server optimization
  devIndicators: {
    buildActivity: true,
    buildActivityPosition: 'bottom-right',
  },
  
  // Enable compression for Linux
  compress: true,
  
  // Security headers
  async headers() {
    return [
      {
        source: '/(.*)',
        headers: [
          {
            key: 'X-Frame-Options',
            value: 'DENY',
          },
          {
            key: 'X-Content-Type-Options',
            value: 'nosniff',
          },
          {
            key: 'Referrer-Policy',
            value: 'origin-when-cross-origin',
          },
        ],
      },
    ];
  },
};

module.exports = nextConfig;
```

#### Linux Development Server Setup
```bash
# Create optimized development script
#!/bin/bash
# scripts/dev-frontend.sh

echo "🚀 Starting Next.js Development Server (Linux Optimized)"

# Set Linux-specific environment variables
export NODE_OPTIONS="--max-old-space-size=4096"
export NEXT_TELEMETRY_DISABLED=1
export WATCHPACK_POLLING=true

# Enable Linux-specific optimizations
export NODE_ENV=development
export NEXT_DEV_PLATFORM=linux

# Start with optimized flags
npm run dev -- --hostname 0.0.0.0 --port 3000
```

#### Package.json Scripts for Linux
```json
{
  "scripts": {
    "dev": "next dev --hostname 0.0.0.0 --port 3000",
    "dev:linux": "WATCHPACK_POLLING=true next dev --hostname 0.0.0.0 --port 3000",
    "build": "next build",
    "build:analyze": "ANALYZE=true next build",
    "start": "next start",
    "lint": "next lint --fix",
    "lint:check": "next lint",
    "type-check": "tsc --noEmit",
    "test": "jest",
    "test:watch": "jest --watch",
    "test:coverage": "jest --coverage"
  }
}
```

### 1.2 Tailwind CSS Linux Configuration

#### Optimized Configuration
```javascript
// tailwind.config.js - Linux-optimized
const colors = require('tailwindcss/colors');
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    './src/pages/**/*.{js,ts,jsx,tsx,mdx}',
    './src/components/**/*.{js,ts,jsx,tsx,mdx}',
    './src/app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  
  // Linux-specific optimizations
  purge: {
    enabled: process.env.NODE_ENV === 'production',
    content: [
      './src/**/*.{js,ts,jsx,tsx}',
    ],
    options: {
      safelist: [/^bg-/, /^text-/, /^border-/],
    },
  },
  
  theme: {
    extend: {
      colors: {
        primary: {
          // Smart Dairy brand colors
          50: '#e8f5e9',
          100: '#c8e6c9',
          200: '#a5d6a7',
          300: '#81c784',
          400: '#66bb6a',
          500: '#2E7D32', // Main green
          600: '#2c6b2f',
          700: '#29592c',
          800: '#1e4620',
          900: '#0d3214',
        },
        secondary: {
          50: '#e3f2fd',
          100: '#bbdefb',
          200: '#90caf9',
          300: '#64b5f6',
          400: '#42a5f5',
          500: '#1976D2', // Tech blue
          600: '#1565c0',
          700: '#0d47a1',
          800: '#0a3d8f',
          900: '#002171',
        },
        accent: {
          500: '#FF6F00', // Orange
        },
      },
      fontFamily: {
        sans: ['Inter', 'Poppins', 'Roboto', ...defaultTheme.fontFamily.sans],
        mono: ['JetBrains Mono', 'Fira Code', 'Consolas', ...defaultTheme.fontFamily.mono],
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
      },
    },
  },
  
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
  
  // Linux build optimization
  corePlugins: {
    preflight: true,
  },
}
```

### 1.3 State Management with Zustand (Linux Optimized)

#### Persistent Store Configuration
```typescript
// src/stores/cartStore.ts - Linux-optimized Zustand store
import { create } from 'zustand';
import { devtools, persist } from 'zustand/middleware';

interface CartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
  image?: string;
}

interface CartStore {
  items: CartItem[];
  addItem: (item: CartItem) => void;
  removeItem: (id: string) => void;
  updateQuantity: (id: string, quantity: number) => void;
  clearCart: () => void;
  total: () => number;
  itemCount: () => number;
}

export const useCartStore = create<CartStore>()(
  devtools(
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
              i.id === id ? { ...i, quantity: Math.max(1, quantity) } : i
            ),
          })),
        
        clearCart: () => set({ items: [] }),
        
        total: () =>
          get().items.reduce((sum, item) => sum + item.price * item.quantity, 0),
        
        itemCount: () =>
          get().items.reduce((sum, item) => sum + item.quantity, 0),
      }),
      {
        name: 'smart-dairy-cart',
        // Linux-specific storage optimization
        storage: {
          getItem: (name) => {
            const item = localStorage.getItem(name);
            return item ? JSON.parse(item) : null;
          },
          setItem: (name, value) => {
            localStorage.setItem(name, JSON.stringify(value));
          },
          removeItem: (name) => {
            localStorage.removeItem(name);
          },
        },
        // Optimize for Linux filesystem
        serialize: (state) => JSON.stringify(state),
        deserialize: (str) => JSON.parse(str),
      }
    ),
    {
      name: 'cart-store',
      // Linux-specific devtools configuration
      trace: process.env.NODE_ENV === 'development',
    }
  )
);
```

---

## 2. BACKEND ARCHITECTURE

### 2.1 Node.js + Express.js Linux Optimization

#### Server Configuration
```typescript
// src/app.ts - Linux-optimized Express setup
import express, { Express, Request, Response, NextFunction } from 'express';
import cors from 'cors';
import helmet from 'helmet';
import compression from 'compression';
import rateLimit from 'express-rate-limit';
import morgan from 'morgan';
import cluster from 'cluster';
import os from 'os';

// Linux-specific cluster setup
const numCPUs = os.cpus().length;

if (cluster.isPrimary && process.env.NODE_ENV === 'production') {
  console.log(`Master ${process.pid} is running`);
  
  // Fork workers for Linux multi-core optimization
  for (let i = 0; i < numCPUs; i++) {
    cluster.fork();
  }
  
  cluster.on('exit', (worker, code, signal) => {
    console.log(`Worker ${worker.process.pid} died with code ${code} and signal ${signal}`);
    cluster.fork(); // Restart worker
  });
} else {
  // Worker process
  const app: Express = express();
  
  // Linux-specific middleware configuration
  app.use(helmet({
    contentSecurityPolicy: {
      directives: {
        defaultSrc: ["'self'"],
        styleSrc: ["'self'", "'unsafe-inline'"],
        scriptSrc: ["'self'"],
        imgSrc: ["'self'", "data:", "https:"],
      },
    },
    hsts: {
      maxAge: 31536000,
      includeSubDomains: true,
      preload: true,
    },
  }));
  
  // Linux compression optimization
  app.use(compression({
    filter: (req, res) => {
      if (req.headers['x-no-compression']) {
        return false;
      }
      return compression.filter(req, res);
    },
    threshold: 1024,
    level: 6, // Linux-optimized compression level
  }));
  
  // CORS configuration for Linux development
  app.use(cors({
    origin: process.env.NODE_ENV === 'production' 
      ? ['https://smartdairy.com', 'https://www.smartdairy.com']
      : ['http://localhost:3000', 'http://127.0.0.1:3000'],
    credentials: true,
    methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    allowedHeaders: ['Content-Type', 'Authorization'],
  }));
  
  // Linux-specific request logging
  if (process.env.NODE_ENV === 'production') {
    app.use(morgan('combined', {
      stream: {
        write: (message: string) => {
          // Linux syslog integration
          require('fs').appendFileSync('/var/log/smartdairy/access.log', message);
        },
      },
    }));
  } else {
    app.use(morgan('dev'));
  }
  
  // Rate limiting for Linux
  const limiter = rateLimit({
    windowMs: 15 * 60 * 1000, // 15 minutes
    max: process.env.NODE_ENV === 'production' ? 100 : 1000,
    message: {
      error: 'Too many requests from this IP, please try again later.',
    },
    standardHeaders: true,
    legacyHeaders: false,
  });
  
  app.use('/api/', limiter);
  
  // Body parsing optimization for Linux
  app.use(express.json({ 
    limit: '10mb',
    verify: (req: any, res: Response, buf: Buffer) => {
      req.rawBody = buf;
    },
  }));
  
  app.use(express.urlencoded({ extended: true, limit: '10mb' }));
  
  // Linux-specific trust proxy setup
  app.set('trust proxy', 1);
  
  // Routes will be added here
  // app.use('/api/v1', routes);
  
  // Error handling for Linux
  app.use((err: Error, req: Request, res: Response, next: NextFunction) => {
    console.error(`Linux Error: ${err.message}`, err.stack);
    
    if (res.headersSent) {
      return next(err);
    }
    
    res.status(500).json({
      error: process.env.NODE_ENV === 'production' 
        ? 'Internal Server Error' 
        : err.message,
      ...(process.env.NODE_ENV === 'development' && { stack: err.stack }),
    });
  });
  
  const PORT = process.env.PORT || 5000;
  
  app.listen(PORT, () => {
    console.log(`Worker ${process.pid} started on port ${PORT}`);
    console.log(`Linux optimizations enabled: ${cluster.isWorker ? 'Clustered' : 'Single'}`);
  });
}
```

#### Linux Process Management with PM2
```javascript
// ecosystem.config.js - PM2 configuration for Linux
module.exports = {
  apps: [
    {
      name: 'smart-dairy-api',
      script: 'dist/server.js',
      instances: 'max', // Use all CPU cores on Linux
      exec_mode: 'cluster',
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'production',
        PORT: 5000,
      },
      env_development: {
        NODE_ENV: 'development',
        PORT: 5000,
        WATCH: true,
      },
      // Linux-specific optimizations
      node_args: '--max-old-space-size=1024',
      error_file: '/var/log/smartdairy/err.log',
      out_file: '/var/log/smartdairy/out.log',
      log_file: '/var/log/smartdairy/combined.log',
      time: true,
      // Linux monitoring
      monitoring: false,
      pmx: true,
    },
  ],
  
  deploy: {
    production: {
      user: 'deploy',
      host: 'smartdairy-server',
      ref: 'origin/main',
      repo: 'git@github.com:smartdairy/backend.git',
      path: '/var/www/smartdairy',
      'pre-deploy-local': '',
      'post-deploy': 'npm install && npm run build && pm2 reload ecosystem.config.js --env production',
      'pre-setup': '',
    },
  },
};
```

### 2.2 Database Architecture (PostgreSQL + Redis)

#### PostgreSQL Linux Configuration
```sql
-- PostgreSQL Linux optimization settings
-- postgresql.conf optimizations for Smart Dairy

-- Memory settings (adjust based on available RAM)
shared_buffers = 256MB                  -- 25% of RAM on Linux
effective_cache_size = 1GB               -- 75% of RAM on Linux
work_mem = 4MB                         -- Per connection
maintenance_work_mem = 64MB               -- Maintenance operations
checkpoint_completion_target = 0.9         -- Linux I/O optimization
wal_buffers = 16MB                      -- Linux default
default_statistics_target = 100            -- Query planning

-- Connection settings
max_connections = 100                     -- Adjust based on application needs
listen_addresses = '*'                    -- Allow connections
port = 5432

-- Linux-specific performance settings
random_page_cost = 1.1                  -- SSD optimization
effective_io_concurrency = 200           -- Concurrent I/O
checkpoint_segments = 32                  -- Checkpoint tuning
wal_writer_delay = 200ms                 -- Write-ahead log

-- Logging for Linux
log_destination = 'stderr'                 -- System log
logging_collector = 'on'
log_directory = 'pg_log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = '1d'
log_rotation_size = '100MB'
log_min_duration_statement = 1000          -- Log slow queries
log_checkpoints = 'on'
log_connections = 'on'
log_disconnections = 'on'
log_lock_waits = 'on'

-- Autovacuum tuning for Linux
autovacuum = 'on'
autovacuum_max_workers = 3               -- CPU cores - 1
autovacuum_naptime = 1min
```

#### Redis Linux Configuration
```conf
# redis.conf - Linux-optimized Redis configuration

# Memory management
maxmemory 512mb
maxmemory-policy allkeys-lru

# Persistence settings
save 900 1
save 300 10
save 60 10000

# Linux I/O optimization
appendonly yes
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb

# Network optimization
tcp-keepalive 300
timeout 0
tcp-backlog 511

# Linux-specific settings
hash-max-ziplist-entries 512
hash-max-ziplist-value 64
list-max-ziplist-size -2
list-compress-depth 0
set-max-intset-entries 512
zset-max-ziplist-entries 128
zset-max-ziplist-value 64

# Security
requirepass your_redis_password
rename-command FLUSHDB ""
rename-command FLUSHALL ""
rename-command DEBUG ""
rename-command CONFIG ""

# Logging
loglevel notice
logfile /var/log/redis/redis-server.log
syslog-enabled yes

# Performance monitoring
slowlog-log-slower-than 10000
slowlog-max-len 128
```

---

## 3. DEVELOPMENT TOOLS & WORKFLOW

### 3.1 VS Code Linux Configuration

#### Workspace Settings
```json
// .vscode/settings.json - Linux-optimized VS Code configuration
{
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true,
    "source.organizeImports": true
  },
  "editor.rulers": [80, 120],
  "editor.tabSize": 2,
  "editor.insertSpaces": true,
  "editor.wordWrap": "on",
  "editor.minimap.enabled": false,
  "editor.suggest.snippetsPreventQuickSuggestions": false,
  
  // Linux-specific file handling
  "files.watcherExclude": {
    "**/.git/objects/**": true,
    "**/.git/subtree-cache/**": true,
    "**/node_modules/**/**": true,
    "**/.hg/store/**": true,
    "**/.svn/**": true
  },
  "files.exclude": {
    "**/.git": true,
    "**/.svn": true,
    "**/.hg": true,
    "**/CVS": true,
    "**/.DS_Store": true,
    "**/Thumbs.db": true,
    "**/node_modules": true
  },
  
  // TypeScript optimizations
  "typescript.preferences.includePackageJsonAutoImports": "on",
  "typescript.suggest.autoImports": true,
  "typescript.updateImportsOnFileMove.enabled": "always",
  "typescript.preferences.importModuleSpecifier": "relative",
  
  // Linux terminal integration
  "terminal.integrated.shell.linux": "/bin/bash",
  "terminal.integrated.env.linux": {
    "EDITOR": "code --wait"
  },
  
  // Performance settings
  "search.exclude": {
    "**/node_modules": true,
    "**/bower_components": true,
    "**/*.code-search": true,
    "**/.git": true,
    "**/.svn": true,
    "**/.hg": true,
    "**/CVS": true
  },
  "files.trimTrailingWhitespace": true,
  "files.insertFinalNewline": true,
  "files.trimFinalNewlines": true,
  
  // Linux-specific extensions
  "extensions.autoUpdate": false,
  "extensions.ignoreRecommendations": false,
  
  // Debugging
  "debug.node.autoAttach": "on",
  "debug.terminal.clearBeforeReusing": true,
  
  // Git integration
  "git.enableSmartCommit": true,
  "git.autofetch": true,
  "git.confirmSync": false,
  "git.postCommitCommand": "none",
  
  // Tailwind CSS
  "tailwindCSS.includeLanguages": {
    "typescript": "javascript",
    "typescriptreact": "javascript"
  },
  "tailwindCSS.experimental.classRegex": [
    ["cva\\(([^)]*)\\)", "[\"'`]([^\"'`]*).*?[\"'`]"]
  ],
  
  // Emmet
  "emmet.includeLanguages": {
    "javascript": "javascriptreact",
    "typescript": "typescriptreact"
  },
  "emmet.triggerExpansionOnTab": true
}
```

#### Essential Extensions for Linux Development
```bash
# Install VS Code extensions for Linux development
code --install-extension dbaeumer.vscode-eslint
code --install-extension esbenp.prettier-vscode
code --install-extension bradlc.vscode-tailwindcss
code --install-extension ms-vscode.vscode-typescript-next
code --install-extension eamodio.gitlens
code --install-extension ms-vscode.vscode-json
code --install-extension humao.rest-client
code --install-extension ckolkman.vscode-postgres
code --install-extension ms-vscode.vscode-docker
code --install-extension ms-vscode-remote.remote-containers
code --install-extension ms-vscode-remote.remote-ssh
code --install-extension ms-vscode.remote-explorer
code --install-extension ms-vscode.remote-wsl
code --install-extension ms-vscode.cpptools
code --install-extension ms-vscode.cmake-tools
code --install-extension ms-vscode.makefile-tools
```

### 3.2 MCP Integration for Linux

#### MCP Server Configuration
```json
// .mcp/settings.json - Linux MCP configuration
{
  "servers": {
    "smart-dairy-development": {
      "command": "node",
      "args": ["./scripts/mcp-server.js"],
      "env": {
        "NODE_ENV": "development",
        "LOG_LEVEL": "debug",
        "LINUX_OPTIMIZATIONS": "true"
      },
      "cwd": "${workspaceFolder}",
      "timeout": 30000
    }
  },
  "tools": {
    "database-operations": {
      "description": "Database query and management operations",
      "enabled": true,
      "cache": true,
      "timeout": 5000
    },
    "api-testing": {
      "description": "API endpoint testing utilities",
      "enabled": true,
      "cache": false,
      "timeout": 10000
    },
    "deployment-helpers": {
      "description": "Linux deployment automation",
      "enabled": true,
      "cache": true,
      "timeout": 15000
    },
    "performance-monitoring": {
      "description": "Linux performance monitoring tools",
      "enabled": true,
      "cache": false,
      "timeout": 3000
    }
  }
}
```

### 3.3 Linux Development Scripts

#### Development Environment Starter
```bash
#!/bin/bash
# scripts/start-dev.sh - Linux development environment starter

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Starting Smart Dairy Linux Development Environment${NC}"

# Check if running on Linux
if [[ "$OSTYPE" != "linux-gnu"* ]]; then
    echo -e "${RED}❌ This script is optimized for Linux systems${NC}"
    exit 1
fi

# Start PostgreSQL
echo -e "${BLUE}📦 Starting PostgreSQL...${NC}"
if ! systemctl is-active --quiet postgresql; then
    sudo systemctl start postgresql
    echo -e "${GREEN}✅ PostgreSQL started${NC}"
else
    echo -e "${YELLOW}⚠️  PostgreSQL already running${NC}"
fi

# Start Redis
echo -e "${BLUE}🔥 Starting Redis...${NC}"
if ! systemctl is-active --quiet redis-server; then
    sudo systemctl start redis-server
    echo -e "${GREEN}✅ Redis started${NC}"
else
    echo -e "${YELLOW}⚠️  Redis already running${NC}"
fi

# Check Node.js version
NODE_VERSION=$(node --version)
echo -e "${BLUE}📌 Node.js version: ${NODE_VERSION}${NC}"

# Set Linux-specific environment variables
export NODE_OPTIONS="--max-old-space-size=4096"
export WATCHPACK_POLLING=true
export NODE_ENV=development

# Create tmux session for development services
SESSION_NAME="smart-dairy-dev"

# Check if tmux session exists
if tmux has-session -t "$SESSION_NAME" 2>/dev/null; then
    echo -e "${YELLOW}⚠️  Development session already exists. Attaching...${NC}"
    tmux attach -t "$SESSION_NAME"
else
    echo -e "${BLUE}🪟 Creating new development session...${NC}"
    
    # Create new tmux session with multiple windows
    tmux new-session -d -s "$SESSION_NAME"
    
    # Backend window
    tmux rename-window -t "$SESSION_NAME:0" "Backend"
    tmux send-keys -t "$SESSION_NAME:0" "cd $(pwd)/smart-dairy-backend && npm run dev" C-m
    
    # Frontend window
    tmux new-window -t "$SESSION_NAME"
    tmux rename-window -t "$SESSION_NAME:1" "Frontend"
    tmux send-keys -t "$SESSION_NAME:1" "cd $(pwd)/smart-dairy-frontend && npm run dev:linux" C-m
    
    # CMS window
    tmux new-window -t "$SESSION_NAME"
    tmux rename-window -t "$SESSION_NAME:2" "CMS"
    tmux send-keys -t "$SESSION_NAME:2" "cd $(pwd)/smart-dairy-cms && npm run develop" C-m
    
    # Database window
    tmux new-window -t "$SESSION_NAME"
    tmux rename-window -t "$SESSION_NAME:3" "Database"
    tmux send-keys -t "$SESSION_NAME:3" "psql -h localhost -U smart_dairy_user -d smart_dairy_dev" C-m
    
    # Select first window
    tmux select-window -t "$SESSION_NAME:0"
    
    echo -e "${GREEN}✅ Development session created!${NC}"
    echo -e "${BLUE}📱 Attach with: tmux attach -t $SESSION_NAME${NC}"
    echo -e "${BLUE}🔍 Services:${NC}"
    echo -e "  Frontend: ${GREEN}http://localhost:3000${NC}"
    echo -e "  Backend:  ${GREEN}http://localhost:5000${NC}"
    echo -e "  CMS:       ${GREEN}http://localhost:1337/admin${NC}"
fi
```

#### Linux Performance Monitor
```bash
#!/bin/bash
# scripts/perf-monitor.sh - Linux performance monitoring

set -e

echo "📊 Smart Dairy Performance Monitor (Linux)"
echo "====================================="

# System information
echo "🖥️  System Information:"
echo "  OS: $(uname -s) $(uname -r)"
echo "  Uptime: $(uptime -p)"
echo "  Load: $(uptime | grep -o 'load average:.*' | cut -d: -f2)"
echo ""

# Memory usage
echo "💾 Memory Usage:"
free -h | grep -E "(Mem|Swap)" | awk '
{
    printf "  %s: %s/%s (%.1f%%)\n", 
    $1, $3, $2, $3*100/$2 
}'
echo ""

# Disk usage
echo "💿 Disk Usage:"
df -h | grep -E "(Filesystem|/dev/)" | awk '
NR==1 {print "  " $0}
NR>1 {printf "  %s: %s/%s (%.1f%%)\n", $1, $3, $2, $3*100/$2}'
echo ""

# CPU usage
echo "🔥 CPU Usage:"
top -bn1 | grep "Cpu(s)" | awk '{printf "  Usage: %.1f%%\n", 100 - $8}'
echo ""

# Process monitoring
echo "⚙️  Node.js Processes:"
ps aux | grep node | grep -v grep | awk '
{
    printf "  PID: %s, CPU: %.1f%%, MEM: %.1f%%, CMD: %s\n", 
    $2, $3, $4, substr($0, index($0,$11))
}'
echo ""

# Network connections
echo "🌐 Network Connections:"
netstat -tuln | grep LISTEN | awk '
{
    printf "  %s:%s (%s)\n", $4, $6, $1
}' | sort
echo ""

# Service status
echo "🛠️  Service Status:"
for service in postgresql redis-server nginx; do
    if systemctl is-active --quiet $service; then
        echo "  ✅ $service: running"
    else
        echo "  ❌ $service: stopped"
    fi
done
```

---

## 4. SECURITY ARCHITECTURE

### 4.1 PCI DSS Compliance on Linux

#### Security Headers Implementation
```typescript
// src/middleware/security.ts - Linux security middleware
import helmet from 'helmet';
import rateLimit from 'express-rate-limit';
import { Request, Response, NextFunction } from 'express';

// PCI DSS compliant security headers
export const securityHeaders = helmet({
  // Content Security Policy
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      styleSrc: ["'self'", "'unsafe-inline'", "https://fonts.googleapis.com"],
      scriptSrc: ["'self'", "https://www.googletagmanager.com"],
      imgSrc: ["'self'", "data:", "https:", "https://www.googletagmanager.com"],
      connectSrc: ["'self'", "https://api.sslcommerz.com", "https://bkash.com"],
      fontSrc: ["'self'", "https://fonts.gstatic.com"],
      objectSrc: ["'none'"],
      mediaSrc: ["'self'"],
      frameSrc: ["'none'"],
    },
  },
  
  // HSTS for PCI compliance
  hsts: {
    maxAge: 31536000, // 1 year
    includeSubDomains: true,
    preload: true,
  },
  
  // Other security headers
  noSniff: true,
  ieNoOpen: true,
  xssFilter: true,
  referrerPolicy: { policy: 'strict-origin-when-cross-origin' },
});

// PCI DSS compliant rate limiting
export const pciRateLimit = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100, // Limit requests per window
  message: {
    error: 'Too many requests. Please try again later.',
  },
  standardHeaders: true,
  legacyHeaders: false,
  // Store attempts in Redis for distributed systems
  store: new RedisStore({
    client: redisClient,
    prefix: 'rl:',
  }),
});

// Payment processing security
export const paymentSecurity = (req: Request, res: Response, next: NextFunction) => {
  // Validate payment request origin
  const allowedOrigins = [
    'https://smartdairy.com',
    'https://www.smartdairy.com',
    'https://secure.sslcommerz.com',
  ];
  
  const origin = req.headers.origin;
  if (!allowedOrigins.includes(origin)) {
    return res.status(403).json({ error: 'Invalid origin for payment processing' });
  }
  
  // Validate content type for payment endpoints
  if (req.path.includes('/payment') && !req.is('application/json')) {
    return res.status(400).json({ error: 'Invalid content type' });
  }
  
  // Log payment attempts for audit trail
  console.log(`Payment attempt from ${req.ip} at ${new Date().toISOString()}`);
  
  next();
};
```

#### Linux Firewall Configuration
```bash
#!/bin/bash
# scripts/setup-firewall.sh - Linux UFW configuration for PCI DSS

set -e

echo "🔒 Configuring Linux Firewall for PCI DSS Compliance"

# Reset UFW to default
sudo ufw --force reset

# Default policies (deny incoming, allow outgoing)
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allow SSH (restricted IP if possible)
echo "🔑 Configuring SSH access..."
if [ -n "$ADMIN_IP" ]; then
    sudo ufw allow from $ADMIN_IP to any port 22 proto tcp
    echo "✅ SSH restricted to $ADMIN_IP"
else
    sudo ufw allow 22/tcp
    echo "⚠️  SSH allowed from any IP (restrict in production)"
fi

# Web server ports
echo "🌐 Configuring web services..."
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw allow from 127.0.0.1 to any port 3000  # Next.js dev
sudo ufw allow from 127.0.0.1 to any port 5000  # API
sudo ufw allow from 127.0.0.1 to any port 1337  # CMS

# Database ports (local only)
echo "🗄️  Securing database access..."
sudo ufw allow from 127.0.0.1 to any port 5432  # PostgreSQL
sudo ufw allow from 127.0.0.1 to any port 6379  # Redis

# Payment gateway ports (if needed)
echo "💳 Configuring payment gateways..."
sudo ufw allow out 443/tcp   # HTTPS for payment gateways
sudo ufw allow out 80/tcp    # HTTP fallback

# Rate limiting for DDoS protection
echo "🛡️  Configuring rate limiting..."
sudo ufw limit ssh/tcp 6/minute
sudo ufw limit 80/tcp 25/minute
sudo ufw limit 443/tcp 25/minute

# Enable logging
sudo ufw logging on

# Enable firewall
echo "🔥 Enabling firewall..."
sudo ufw --force enable

# Show status
echo ""
echo "📊 Firewall Status:"
sudo ufw status verbose
```

### 4.2 SSL/TLS Configuration for Linux

#### Nginx SSL Configuration
```nginx
# /etc/nginx/sites-available/smartdairy - SSL configuration
server {
    listen 80;
    server_name smartdairy.com www.smartdairy.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name smartdairy.com www.smartdairy.com;
    
    # SSL certificates (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/smartdairy.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smartdairy.com/privkey.pem;
    
    # SSL configuration for PCI DSS
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    ssl_stapling on;
    ssl_stapling_verify on;
    
    # HSTS for PCI compliance
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    
    # Security headers
    add_header X-Frame-Options DENY always;
    add_header X-Content-Type-Options nosniff always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://www.googletagmanager.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' https://fonts.gstatic.com; connect-src 'self' https://api.sslcommerz.com;" always;
    
    # Proxy to Next.js
    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
    
    # API proxy
    location /api/ {
        proxy_pass http://127.0.0.1:5000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        
        # Rate limiting for API
        limit_req zone=api burst=20 nodelay;
    }
    
    # Static files caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        add_header X-Cache-Status "STATIC";
    }
    
    # Security for sensitive paths
    location ~ /\. {
        deny all;
    }
    
    location ~ /(admin|api/admin|\.env) {
        deny all;
        return 404;
    }
}

# Rate limiting zones
http {
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    limit_req_zone $binary_remote_addr zone=login:10m rate=1r/s;
}
```

---

## 5. PERFORMANCE OPTIMIZATION

### 5.1 Linux-Specific Performance Tuning

#### System-Level Optimizations
```bash
#!/bin/bash
# scripts/system-tune.sh - Linux performance tuning for web development

set -e

echo "⚡ Linux Performance Tuning for Smart Dairy"
echo "====================================="

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 
   exit 1
fi

# Network optimization
echo "🌐 Optimizing network settings..."
echo 'net.core.rmem_max = 16777216' >> /etc/sysctl.conf
echo 'net.core.wmem_max = 16777216' >> /etc/sysctl.conf
echo 'net.ipv4.tcp_rmem = 4096 87380 16777216' >> /etc/sysctl.conf
echo 'net.ipv4.tcp_wmem = 4096 65536 16777216' >> /etc/sysctl.conf
echo 'net.ipv4.tcp_congestion_control = bbr' >> /etc/sysctl.conf
echo 'net.core.netdev_max_backlog = 5000' >> /etc/sysctl.conf

# File system optimization
echo "💿 Optimizing file system..."
echo 'vm.swappiness = 10' >> /etc/sysctl.conf
echo 'vm.dirty_ratio = 15' >> /etc/sysctl.conf
echo 'vm.dirty_background_ratio = 5' >> /etc/sysctl.conf

# Process limits
echo "⚙️  Optimizing process limits..."
echo '* soft nofile 65536' >> /etc/security/limits.conf
echo '* hard nofile 65536' >> /etc/security/limits.conf
echo '* soft nproc 32768' >> /etc/security/limits.conf
echo '* hard nproc 32768' >> /etc/security/limits.conf

# Apply settings
echo "🔄 Applying settings..."
sysctl -p

# Optimize SSD (if applicable)
if lsblk -d -o name,rota | grep -q '0$'; then
    echo "💾 SSD detected, optimizing..."
    echo 'deadline' > /sys/block/sda/queue/scheduler
    echo '1' > /sys/block/sda/queue/iosched/fifo_batch
fi

# CPU governor for performance
if command -v cpupower &> /dev/null; then
    echo "🔥 Setting CPU governor to performance..."
    cpupower frequency-set -g performance
fi

echo "✅ Performance tuning complete!"
echo "🔄 Reboot required for all changes to take effect"
```

#### Node.js Performance Monitoring
```typescript
// src/utils/performance-monitor.ts - Linux performance monitoring
import os from 'os';
import { performance } from 'perf_hooks';
import fs from 'fs';
import path from 'path';

interface PerformanceMetrics {
  timestamp: Date;
  cpu: {
    usage: number;
    loadAverage: number[];
  };
  memory: {
    used: number;
    total: number;
    percentage: number;
  };
  eventLoop: {
    delay: number;
    utilization: number;
  };
  gc: {
    collections: number;
    duration: number;
  };
}

class LinuxPerformanceMonitor {
  private logFile: string;
  private metrics: PerformanceMetrics[] = [];
  
  constructor() {
    this.logFile = path.join(process.cwd(), 'logs', 'performance.log');
    this.ensureLogDirectory();
  }
  
  private ensureLogDirectory() {
    const logDir = path.dirname(this.logFile);
    if (!fs.existsSync(logDir)) {
      fs.mkdirSync(logDir, { recursive: true });
    }
  }
  
  public collectMetrics(): PerformanceMetrics {
    const startUsage = process.cpuUsage();
    const startMemory = process.memoryUsage();
    
    // CPU metrics (Linux-specific)
    const cpus = os.cpus();
    const totalIdle = cpus.reduce((acc, cpu) => acc + cpu.times.idle, 0);
    const totalTick = cpus.reduce((acc, cpu) => 
      acc + cpu.times.user + cpu.times.nice + cpu.times.sys + cpu.times.idle + cpu.times.irq, 0
    );
    const idle = totalIdle / cpus.length;
    const total = totalTick / cpus.length;
    const cpuUsage = 100 - (idle / total) * 100;
    
    // Memory metrics
    const totalMemory = os.totalmem();
    const freeMemory = os.freemem();
    const usedMemory = totalMemory - freeMemory;
    
    // Event loop metrics
    const eventLoopDelay = this.measureEventLoopDelay();
    
    const metrics: PerformanceMetrics = {
      timestamp: new Date(),
      cpu: {
        usage: cpuUsage,
        loadAverage: os.loadavg()
      },
      memory: {
        used: usedMemory,
        total: totalMemory,
        percentage: (usedMemory / totalMemory) * 100
      },
      eventLoop: {
        delay: eventLoopDelay,
        utilization: this.calculateEventLoopUtilization()
      },
      gc: {
        collections: 0, // Would need v8 hooks for accurate measurement
        duration: 0
      }
    };
    
    this.metrics.push(metrics);
    this.logMetrics(metrics);
    
    return metrics;
  }
  
  private measureEventLoopDelay(): number {
    const start = performance.now();
    const target = performance.now() + 1;
    
    while (performance.now() < target) {
      // Busy wait to measure event loop delay
    }
    
    return performance.now() - start - 1;
  }
  
  private calculateEventLoopUtilization(): number {
    // Simplified event loop utilization calculation
    const start = performance.now();
    setImmediate(() => {
      const duration = performance.now() - start;
      return Math.min(100, (duration / 10) * 100);
    });
    return 0;
  }
  
  private logMetrics(metrics: PerformanceMetrics) {
    const logEntry = JSON.stringify(metrics) + '\n';
    fs.appendFileSync(this.logFile, logEntry);
    
    // Alert on high resource usage
    if (metrics.cpu.usage > 80) {
      console.warn(`⚠️  High CPU usage: ${metrics.cpu.usage.toFixed(2)}%`);
    }
    
    if (metrics.memory.percentage > 85) {
      console.warn(`⚠️  High memory usage: ${metrics.memory.percentage.toFixed(2)}%`);
    }
    
    if (metrics.eventLoop.delay > 10) {
      console.warn(`⚠️  High event loop delay: ${metrics.eventLoop.delay.toFixed(2)}ms`);
    }
  }
  
  public getMetricsSummary(): PerformanceMetrics {
    if (this.metrics.length === 0) {
      return this.collectMetrics();
    }
    
    return this.metrics[this.metrics.length - 1];
  }
  
  public startMonitoring(intervalMs: number = 5000) {
    console.log(`📊 Starting performance monitoring (interval: ${intervalMs}ms)`);
    
    setInterval(() => {
      this.collectMetrics();
    }, intervalMs);
  }
}

export default LinuxPerformanceMonitor;
```

---

## 6. DEPLOYMENT ARCHITECTURE

### 6.1 Linux Deployment Strategy

#### Docker Configuration for Production
```dockerfile
# Dockerfile - Production deployment
FROM node:20-alpine AS base

# Install dependencies only when needed
FROM base AS deps
RUN apk add --no-cache libc6-compat
WORKDIR /app

# Install dependencies based on the preferred package manager
COPY package.json package-lock.json* ./
RUN npm ci --only=production

# Rebuild the source code only when needed
FROM base AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY . .

# Build the application
RUN npm run build

# Production image, copy all the files and run next
FROM base AS runner
WORKDIR /app

ENV NODE_ENV production

RUN addgroup --system --gid 1001 nodejs
RUN adduser --system --uid 1001 nextjs

COPY --from=builder /app/public ./public

# Set the correct permission for prerender cache
RUN mkdir .next
RUN chown nextjs:nodejs .next

# Automatically leverage output traces to reduce image size
COPY --from=builder --chown=nextjs:nodejs /app/.next/standalone ./
COPY --from=builder --chown=nextjs:nodejs /app/.next/static ./.next/static

USER nextjs

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

CMD ["node", "server.js"]
```

#### Docker Compose for Development
```yaml
# docker-compose.yml - Linux development environment
version: '3.8'

services:
  # Frontend (Next.js)
  frontend:
    build:
      context: ./smart-dairy-frontend
      dockerfile: Dockerfile.dev
    ports:
      - "3000:3000"
    volumes:
      - ./smart-dairy-frontend:/app
      - /app/node_modules
      - /app/.next
    environment:
      - NODE_ENV=development
      - WATCHPACK_POLLING=true
      - NEXT_PUBLIC_API_URL=http://localhost:5000/api
    depends_on:
      - backend
      - postgres
      - redis
    networks:
      - smartdairy-network

  # Backend (Express.js)
  backend:
    build:
      context: ./smart-dairy-backend
      dockerfile: Dockerfile.dev
    ports:
      - "5000:5000"
    volumes:
      - ./smart-dairy-backend:/app
      - /app/node_modules
      - uploads:/app/uploads
    environment:
      - NODE_ENV=development
      - DB_HOST=postgres
      - DB_PORT=5432
      - DB_NAME=smart_dairy_dev
      - DB_USER=smart_dairy_user
      - DB_PASSWORD=password
      - REDIS_HOST=redis
      - REDIS_PORT=6379
    depends_on:
      - postgres
      - redis
    networks:
      - smartdairy-network

  # CMS (Strapi)
  cms:
    build:
      context: ./smart-dairy-cms
      dockerfile: Dockerfile.dev
    ports:
      - "1337:1337"
    volumes:
      - ./smart-dairy-cms:/app
      - /app/node_modules
      - cms-uploads:/app/public/uploads
    environment:
      - NODE_ENV=development
      - DATABASE_CLIENT=postgres
      - DATABASE_HOST=postgres
      - DATABASE_PORT=5432
      - DATABASE_NAME=smart_dairy_cms
      - DATABASE_USERNAME=smart_dairy_user
      - DATABASE_PASSWORD=password
    depends_on:
      - postgres
    networks:
      - smartdairy-network

  # PostgreSQL
  postgres:
    image: postgres:15-alpine
    ports:
      - "5432:5432"
    volumes:
      - postgres-data:/var/lib/postgresql/data
      - ./scripts/init-db.sql:/docker-entrypoint-initdb.d/init-db.sql
    environment:
      - POSTGRES_DB=smart_dairy_dev
      - POSTGRES_USER=smart_dairy_user
      - POSTGRES_PASSWORD=password
    networks:
      - smartdairy-network

  # Redis
  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis-data:/data
      - ./config/redis.conf:/usr/local/etc/redis/redis.conf
    command: redis-server /usr/local/etc/redis/redis.conf
    networks:
      - smartdairy-network

  # Nginx (reverse proxy)
  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./config/nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
      - ./logs/nginx:/var/log/nginx
    depends_on:
      - frontend
      - backend
    networks:
      - smartdairy-network

volumes:
  postgres-data:
  redis-data:
  cms-uploads:
  uploads:

networks:
  smartdairy-network:
    driver: bridge
```

#### Deployment Script for Linux
```bash
#!/bin/bash
# scripts/deploy.sh - Linux deployment automation

set -e

# Configuration
DEPLOY_USER="deploy"
DEPLOY_HOST="smartdairy.com"
DEPLOY_PATH="/var/www/smartdairy"
BACKUP_PATH="/var/backups/smartdairy"
REPO_URL="git@github.com:smartdairy/smart-dairy.git"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}🚀 Smart Dairy Linux Deployment${NC}"
echo "=================================="

# Create backup
echo -e "${BLUE}💾 Creating backup...${NC}"
BACKUP_NAME="backup-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_PATH/$BACKUP_NAME"

if [ -d "$DEPLOY_PATH" ]; then
    rsync -av --exclude=node_modules --exclude=.git "$DEPLOY_PATH/" "$BACKUP_PATH/$BACKUP_NAME/"
    echo -e "${GREEN}✅ Backup created: $BACKUP_NAME${NC}"
fi

# Clone or update repository
echo -e "${BLUE}📥 Updating repository...${NC}"
TEMP_DEPLOY="/tmp/smartdairy-deploy-$(date +%s)"

if [ -d "$TEMP_DEPLOY" ]; then
    rm -rf "$TEMP_DEPLOY"
fi

git clone "$REPO_URL" "$TEMP_DEPLOY"
cd "$TEMP_DEPLOY"

# Install dependencies
echo -e "${BLUE}📦 Installing dependencies...${NC}"
npm ci --production

# Build applications
echo -e "${BLUE}🔨 Building applications...${NC}"
npm run build

# Run tests
echo -e "${BLUE}🧪 Running tests...${NC}"
npm test

# Deploy to production
echo -e "${BLUE}🔄 Deploying to production...${NC}"
rsync -av --delete --exclude=node_modules --exclude=.git "$TEMP_DEPLOY/" "$DEPLOY_USER@$DEPLOY_HOST:$DEPLOY_PATH/"

# Restart services
echo -e "${BLUE}🔄 Restarting services...${NC}"
ssh "$DEPLOY_USER@$DEPLOY_HOST" "cd $DEPLOY_PATH && pm2 reload ecosystem.config.js --env production"

# Health check
echo -e "${BLUE}🏥 Running health check...${NC}"
sleep 10

if curl -f -s "https://$DEPLOY_HOST/api/health" > /dev/null; then
    echo -e "${GREEN}✅ Deployment successful!${NC}"
else
    echo -e "${RED}❌ Health check failed! Rolling back...${NC}"
    
    # Rollback
    rsync -av --delete "$BACKUP_PATH/$BACKUP_NAME/" "$DEPLOY_USER@$DEPLOY_HOST:$DEPLOY_PATH/"
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "cd $DEPLOY_PATH && pm2 reload ecosystem.config.js --env production"
    
    echo -e "${YELLOW}⚠️  Rollback completed${NC}"
    exit 1
fi

# Cleanup
echo -e "${BLUE}🧹 Cleaning up...${NC}"
rm -rf "$TEMP_DEPLOY"

# Keep only last 5 backups
echo -e "${BLUE}🗑  Cleaning old backups...${NC}"
cd "$BACKUP_PATH"
ls -t | tail -n +6 | xargs rm -rf

echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
```

---

## 7. MONITORING & LOGGING

### 7.1 Linux Monitoring Setup

#### System Monitoring with Prometheus + Grafana
```yaml
# monitoring/prometheus.yml - Prometheus configuration
global:
  scrape_interval: 15s
  evaluation_interval: 15s

rule_files:
  - "alert_rules.yml"

scrape_configs:
  - job_name: 'smartdairy-api'
    static_configs:
      - targets: ['localhost:5000']
    metrics_path: '/metrics'
    scrape_interval: 5s

  - job_name: 'smartdairy-frontend'
    static_configs:
      - targets: ['localhost:3000']
    metrics_path: '/api/metrics'
    scrape_interval: 5s

  - job_name: 'node-exporter'
    static_configs:
      - targets: ['localhost:9100']

  - job_name: 'postgres-exporter'
    static_configs:
      - targets: ['localhost:9187']

  - job_name: 'redis-exporter'
    static_configs:
      - targets: ['localhost:9121']

alerting:
  alertmanagers:
    - static_configs:
        - targets:
          - alertmanager:9093
```

#### Log Management Configuration
```typescript
// src/utils/logger.ts - Linux-optimized logging
import winston from 'winston';
import path from 'path';
import fs from 'fs';

// Create logs directory if it doesn't exist
const logDir = '/var/log/smartdairy';
if (!fs.existsSync(logDir)) {
    fs.mkdirSync(logDir, { recursive: true });
}

// Custom format for Linux syslog integration
const customFormat = winston.format.combine(
    winston.format.timestamp({
        format: 'YYYY-MM-DD HH:mm:ss'
    }),
    winston.format.errors({ stack: true }),
    winston.format.json(),
    winston.format.printf(({ timestamp, level, message, stack, ...meta }) => {
        return JSON.stringify({
            timestamp,
            level,
            message,
            stack,
            ...meta,
            pid: process.pid,
            hostname: require('os').hostname()
        });
    })
);

// Create logger
const logger = winston.createLogger({
    level: process.env.LOG_LEVEL || 'info',
    format: customFormat,
    defaultMeta: { service: 'smart-dairy-api' },
    transports: [
        // Console for development
        new winston.transports.Console({
            format: winston.format.combine(
                winston.format.colorize(),
                winston.format.simple()
            )
        }),
        
        // File for production
        new winston.transports.File({
            filename: path.join(logDir, 'error.log'),
            level: 'error',
            maxsize: 10485760, // 10MB
            maxFiles: 5,
        }),
        
        new winston.transports.File({
            filename: path.join(logDir, 'combined.log'),
            maxsize: 10485760, // 10MB
            maxFiles: 10,
        }),
        
        // Syslog for Linux integration
        new winston.transports.Syslog({
            host: 'localhost',
            port: 514,
            protocol: 'udp4',
            facility: 'local0',
            localhost: 'smartdairy-api',
            format: winston.format.json()
        })
    ]
});

// Add request logging middleware
export const requestLogger = (req: any, res: any, next: any) => {
    const start = Date.now();
    
    res.on('finish', () => {
        const duration = Date.now() - start;
        const logData = {
            method: req.method,
            url: req.url,
            status: res.statusCode,
            duration: `${duration}ms`,
            userAgent: req.get('User-Agent'),
            ip: req.ip || req.connection.remoteAddress
        };
        
        logger.info('HTTP Request', logData);
    });
    
    next();
};

export default logger;
```

---

---

## 8. ECOSYSTEM EXPANSION: NEW TECHNOLOGY ADDITIONS (v2.0)

> **Note:** Sections 1-7 above cover the core stack (Next.js, Express.js, PostgreSQL, Redis, Prisma, Docker, Nginx) which remain unchanged. This section documents all new technologies added for the expanded digital ecosystem (Marketplace, Livestock, ERP, Mobile).

### 8.1 Stack Changes Summary

| Existing Choice | Verdict | Notes |
|----------------|---------|-------|
| Next.js 14+ | **KEEP** (upgrade to 15) | Excellent for website + marketplace frontend |
| Express.js | **KEEP** | Proven, lightweight, unified API gateway |
| PostgreSQL 15+ | **KEEP** (target 16) | Critical for ERP ACID transactions, JSONB |
| Redis 7+ | **KEEP + EXPAND** | Add BullMQ job queues, pub/sub for IoT |
| Prisma ORM | **KEEP** | Type-safe DB access, multiSchema support |
| TypeScript | **KEEP** | Essential for system of this size |
| Tailwind CSS | **KEEP** | Rapid UI development |
| Zustand | **KEEP** | Lightweight state management |
| Docker | **KEEP** | Single docker-compose stack |
| Nginx | **KEEP** | Reverse proxy, SSL, static files |
| DigitalOcean | **KEEP** | Upgrade to 4 vCPU/8GB ($48/mo) |
| Strapi CMS | **REPLACE** | Build lightweight custom CMS in Next.js admin |

### 8.2 Complete Revised Stack

```
FRONTEND:  Next.js 15 + React 18 + TypeScript + Tailwind CSS + shadcn/ui
           Zustand + React Hook Form + Zod + Recharts + Socket.IO Client + next-intl

BACKEND:   Node.js 22 LTS + Express.js + TypeScript + Prisma ORM (multiSchema)
           BullMQ + Socket.IO + Passport.js/JWT + Multer + Sharp
           node-cron + Puppeteer/PDFKit

DATA:      PostgreSQL 16 + Redis 7+ + Meilisearch (Phase 4+) + Mosquitto MQTT

MOBILE:    React Native (Expo SDK 51+) + AsyncStorage + expo-sqlite (offline)
           Expo Camera, Location, Notifications APIs

INFRA:     Docker + docker-compose + Nginx + DigitalOcean (4vCPU/8GB)
           DigitalOcean Spaces (S3) + Cloudflare CDN/WAF + GitHub Actions CI/CD
           Prometheus + Grafana (monitoring)
```

---

### 8.3 BullMQ (Redis-Based Job Queues)

**Purpose:** Asynchronous task processing for email, SMS, payment settlements, report generation, and IoT data ingestion.

**Why BullMQ:** No new infrastructure required -- uses existing Redis. Mature, TypeScript-native, supports priorities, retries, rate limiting, and delayed jobs.

```typescript
// src/modules/common/queues/queue-definitions.ts
import { Queue, Worker } from 'bullmq';
import { redisConnection } from '../config/redis';

// Queue definitions per concern
export const emailQueue = new Queue('email', { connection: redisConnection });
export const smsQueue = new Queue('sms', { connection: redisConnection });
export const paymentQueue = new Queue('payment-settlement', { connection: redisConnection });
export const reportQueue = new Queue('report-generation', { connection: redisConnection });
export const iotQueue = new Queue('iot-ingestion', { connection: redisConnection });
export const notificationQueue = new Queue('push-notification', { connection: redisConnection });
export const payoutQueue = new Queue('vendor-payout', { connection: redisConnection });

// Example worker: email processing
const emailWorker = new Worker('email', async (job) => {
  const { to, template, data, language } = job.data;
  // SendGrid/SES integration
  await sendEmail({ to, template, data, language });
}, {
  connection: redisConnection,
  concurrency: 5,
  limiter: { max: 100, duration: 60000 } // 100 emails/minute
});
```

**Queue Usage by Module:**

| Queue | Module | Use Case | Priority |
|-------|--------|----------|----------|
| email | Common | Order confirmations, password resets, vendor approvals | High |
| sms | Common | OTP, vaccination reminders, health alerts, delivery updates | Critical |
| payment-settlement | Marketplace | Weekly vendor payouts after 7-day hold | Medium |
| report-generation | ERP | Monthly P&L, production reports, PDF generation | Low |
| iot-ingestion | ERP | Milk meter readings, temperature sensor data via MQTT | High |
| push-notification | Mobile | Farm alerts, order updates, auction notifications | High |
| vendor-payout | Marketplace | bKash Business / BEFTN bank transfers | Medium |

---

### 8.4 Socket.IO (Real-Time Communication)

**Purpose:** Real-time bidding for livestock auctions, IoT dashboard updates, live order tracking, marketplace chat.

**Why Socket.IO:** Built-in room management, automatic reconnection, fallback to polling (important for Bangladesh network conditions), TypeScript support.

```typescript
// src/modules/common/services/socket.service.ts
import { Server } from 'socket.io';
import { verifyJWT } from '../auth/jwt';

export function initializeSocketIO(httpServer: any) {
  const io = new Server(httpServer, {
    cors: { origin: process.env.FRONTEND_URL, credentials: true },
    transports: ['websocket', 'polling']
  });

  // Authentication middleware
  io.use(async (socket, next) => {
    const token = socket.handshake.auth.token?.replace('Bearer ', '');
    if (!token) return next(new Error('Authentication required'));
    try {
      const user = await verifyJWT(token);
      socket.data.user = user;
      next();
    } catch { next(new Error('Invalid token')); }
  });

  // Namespace: Livestock auctions
  const livestockNs = io.of('/livestock');
  livestockNs.on('connection', (socket) => {
    socket.on('auction:join', ({ auctionId }) => {
      socket.join(`auction:${auctionId}`);
    });
    socket.on('auction:bid', async ({ auctionId, amount }) => {
      // Validate bid, save to DB, broadcast
      const bid = await processBid(auctionId, amount, socket.data.user.id);
      livestockNs.to(`auction:${auctionId}`).emit('auction:bid_placed', {
        auctionId, amount: bid.amount, bidder: bid.maskedUserId, timestamp: new Date()
      });
    });
  });

  // Namespace: ERP IoT dashboard
  const erpNs = io.of('/erp');
  erpNs.on('connection', (socket) => {
    socket.join('farm-dashboard');
  });

  // Namespace: Marketplace
  const marketplaceNs = io.of('/marketplace');

  // Namespace: Notifications
  const notifyNs = io.of('/notifications');
  notifyNs.on('connection', (socket) => {
    socket.join(`user:${socket.data.user.id}`);
  });

  return io;
}
```

**Namespace Configuration:**

| Namespace | Events | Users | Data Volume |
|-----------|--------|-------|-------------|
| `/livestock` | auction:join, auction:bid, auction:bid_placed, auction:ended | Buyers, sellers | Low (bids per auction) |
| `/erp` | iot:milk_reading, iot:temperature, iot:tank_level, iot:alert | Farm staff (20-50) | Medium (sensor readings) |
| `/marketplace` | order:status_updated, chat:message | Buyers, vendors | Low |
| `/notifications` | notification:new, notification:count | All authenticated | Low |

---

### 8.5 MQTT / Mosquitto (IoT Sensor Integration)

**Purpose:** Ingest data from farm IoT sensors (milk flow meters, temperature probes, bulk tank monitors).

**Why MQTT:** Industry standard for IoT. Lightweight protocol suitable for ESP32 microcontrollers. Mosquitto broker is open-source and runs in Docker.

```typescript
// src/modules/erp/services/mqtt-subscriber.ts
import mqtt from 'mqtt';
import { prisma } from '../../common/config/database';
import { iotQueue } from '../../common/queues/queue-definitions';

const client = mqtt.connect(process.env.MQTT_BROKER_URL || 'mqtt://localhost:1883');

client.on('connect', () => {
  // Subscribe to farm sensor topics
  client.subscribe('farm/milk/+/+');      // farm/milk/{animalTag}/{session}
  client.subscribe('farm/environment/+/temp');
  client.subscribe('farm/tank/+/level');
  client.subscribe('farm/tank/+/temp');
  console.log('[MQTT] Connected and subscribed to farm topics');
});

client.on('message', async (topic, payload) => {
  const data = JSON.parse(payload.toString());
  const parts = topic.split('/');

  if (parts[1] === 'milk') {
    // Enqueue for processing (don't block MQTT thread)
    await iotQueue.add('milk-reading', {
      animalTag: parts[2],
      session: parts[3],
      quantity: data.quantity,
      timestamp: data.timestamp || new Date().toISOString()
    });
  } else if (parts[1] === 'tank') {
    await iotQueue.add('tank-reading', {
      tankId: parts[2],
      metric: parts[3], // 'level' or 'temp'
      value: data.value,
      timestamp: data.timestamp || new Date().toISOString()
    });
  }
});
```

**MQTT Topics:**

| Topic Pattern | Source | Data | Frequency |
|---------------|--------|------|-----------|
| `farm/milk/{tag}/{session}` | Inline flow meters | Milk quantity (liters) | Per milking session |
| `farm/environment/{location}/temp` | DS18B20 + ESP32 | Temperature (°C) | Every 5 minutes |
| `farm/tank/{tankId}/level` | Ultrasonic sensor | Tank fill level (%) | Every 10 minutes |
| `farm/tank/{tankId}/temp` | DS18B20 | Milk temperature (°C) | Every 5 minutes |

**Sensor Hardware (Bangladesh-available):**
- Milk flow meters: Inline flow meters (Alibaba, $50-100 each)
- Temperature: DS18B20 + ESP32 ($5-10 per node)
- Gateway: Raspberry Pi 4 as local MQTT broker + relay to cloud

**Docker Configuration:**
```yaml
# docker-compose.yml (addition)
mosquitto:
  image: eclipse-mosquitto:2
  ports:
    - "1883:1883"
    - "9001:9001"
  volumes:
    - ./docker/mosquitto/mosquitto.conf:/mosquitto/config/mosquitto.conf
    - mosquitto_data:/mosquitto/data
    - mosquitto_log:/mosquitto/log
```

---

### 8.6 Meilisearch (Full-Text Search)

**Purpose:** Fast, typo-tolerant search across marketplace products, livestock listings, and website content. Deployed in Phase 4 when marketplace has 1,000+ products.

**Why Meilisearch:** Sub-50ms search, typo tolerance, faceted filtering, easy to deploy (single binary), excellent for e-commerce. Better than PostgreSQL FTS for marketplace scale.

```typescript
// src/modules/common/services/search.service.ts
import { MeiliSearch } from 'meilisearch';

const meili = new MeiliSearch({
  host: process.env.MEILISEARCH_URL || 'http://localhost:7700',
  apiKey: process.env.MEILISEARCH_MASTER_KEY
});

// Index configuration
export async function setupSearchIndexes() {
  // Website products
  const webProducts = meili.index('web_products');
  await webProducts.updateSettings({
    searchableAttributes: ['name', 'description', 'category', 'tags'],
    filterableAttributes: ['categoryId', 'price', 'inStock', 'rating'],
    sortableAttributes: ['price', 'rating', 'createdAt']
  });

  // Marketplace products
  const mktProducts = meili.index('mkt_products');
  await mktProducts.updateSettings({
    searchableAttributes: ['name', 'description', 'category', 'vendor', 'tags'],
    filterableAttributes: ['categoryId', 'vendorId', 'price', 'inStock', 'location', 'rating'],
    sortableAttributes: ['price', 'rating', 'soldCount', 'createdAt']
  });

  // Livestock listings
  const livestock = meili.index('livestock_listings');
  await livestock.updateSettings({
    searchableAttributes: ['title', 'breed', 'description', 'location'],
    filterableAttributes: ['category', 'breed', 'listingType', 'price', 'location', 'isVerified'],
    sortableAttributes: ['price', 'createdAt']
  });
}

// Cross-module unified search
export async function unifiedSearch(query: string, modules: string[] = ['web', 'mkt', 'lvs']) {
  const results = await meili.multiSearch({
    queries: modules.map(mod => ({
      indexUid: mod === 'web' ? 'web_products' : mod === 'mkt' ? 'mkt_products' : 'livestock_listings',
      q: query,
      limit: 10
    }))
  });
  return results;
}
```

**Phase Strategy:**
- **Phase 1-3:** Use PostgreSQL full-text search (GIN indexes, `tsvector`) -- sufficient for Smart Dairy's own products
- **Phase 4+:** Deploy Meilisearch when marketplace exceeds 1,000 products
- **Sync strategy:** Prisma middleware triggers Meilisearch document updates on product create/update/delete

**Docker Configuration:**
```yaml
meilisearch:
  image: getmeili/meilisearch:v1.6
  ports:
    - "7700:7700"
  environment:
    MEILI_MASTER_KEY: ${MEILISEARCH_MASTER_KEY}
    MEILI_ENV: production
  volumes:
    - meilisearch_data:/meili_data
```

---

### 8.7 React Native / Expo (Mobile Application)

**Purpose:** Cross-platform mobile app for farm workers (ERP data entry), consumers (shopping), and livestock users (browsing, bidding).

**Why React Native + Expo:** Same TypeScript/React skill set as web frontend. Shared types and validation logic with backend. Expo handles iOS builds without a Mac (EAS Build cloud service). Expo SDK provides camera, location, notifications, and SQLite APIs.

**Project Structure:**
```
mobile/
├── app/                    # Expo Router (file-based routing)
│   ├── (auth)/            # Login, register screens
│   ├── (tabs)/            # Main tab navigator
│   │   ├── erp/           # Farm management screens
│   │   │   ├── milk-recording.tsx
│   │   │   ├── health-events.tsx
│   │   │   ├── herd.tsx
│   │   │   └── dashboard.tsx
│   │   ├── shop/          # Consumer shopping
│   │   ├── marketplace/   # Browse marketplace
│   │   ├── livestock/     # Browse/bid livestock
│   │   └── profile/       # User profile, settings
│   └── _layout.tsx
├── components/            # Shared UI components
├── services/              # API clients, sync service
├── stores/                # Zustand stores
├── db/                    # expo-sqlite schema and queries
├── utils/                 # Shared utilities
└── shared/                # Types shared with backend (symlink or package)
```

**Offline-First Architecture:**
```typescript
// mobile/db/sync.ts
import * as SQLite from 'expo-sqlite';

const db = SQLite.openDatabase('smartdairy.db');

// Create local tables for offline storage
export function initLocalDB() {
  db.transaction(tx => {
    // Pending records queue
    tx.executeSql(`CREATE TABLE IF NOT EXISTS sync_queue (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      entity TEXT NOT NULL,
      action TEXT NOT NULL,
      data TEXT NOT NULL,
      created_at TEXT NOT NULL,
      synced INTEGER DEFAULT 0
    )`);

    // Local animal cache
    tx.executeSql(`CREATE TABLE IF NOT EXISTS animals_cache (
      id TEXT PRIMARY KEY,
      tag_number TEXT,
      name TEXT,
      group_name TEXT,
      data TEXT NOT NULL,
      updated_at TEXT
    )`);

    // Local milk records (offline entry)
    tx.executeSql(`CREATE TABLE IF NOT EXISTS milk_records_local (
      local_id INTEGER PRIMARY KEY AUTOINCREMENT,
      animal_id TEXT NOT NULL,
      session TEXT NOT NULL,
      quantity REAL NOT NULL,
      record_date TEXT NOT NULL,
      synced INTEGER DEFAULT 0
    )`);
  });
}

// Add record to sync queue (works offline)
export function addToSyncQueue(entity: string, action: string, data: any) {
  db.transaction(tx => {
    tx.executeSql(
      'INSERT INTO sync_queue (entity, action, data, created_at) VALUES (?, ?, ?, ?)',
      [entity, action, JSON.stringify(data), new Date().toISOString()]
    );
  });
}

// Push pending records to server when online
export async function syncPendingRecords(apiClient: any) {
  return new Promise((resolve, reject) => {
    db.transaction(tx => {
      tx.executeSql(
        'SELECT * FROM sync_queue WHERE synced = 0 ORDER BY created_at ASC',
        [],
        async (_, { rows }) => {
          const records = rows._array;
          if (records.length === 0) { resolve(0); return; }

          try {
            const response = await apiClient.post('/api/v1/mobile/sync/push', {
              records: records.map(r => ({
                localId: r.id.toString(),
                entity: r.entity,
                action: r.action,
                data: JSON.parse(r.data),
                createdAt: r.created_at
              }))
            });

            // Mark synced
            const syncedIds = response.data.synced.map((s: any) => s.localId);
            tx.executeSql(
              `UPDATE sync_queue SET synced = 1 WHERE id IN (${syncedIds.join(',')})`,
              []
            );
            resolve(syncedIds.length);
          } catch (err) { reject(err); }
        }
      );
    });
  });
}
```

**Key Mobile Features by Role:**

| Role | Priority Features |
|------|-------------------|
| Farm Worker | Quick milk recording (tag scan + quantity), health event logging, attendance check-in |
| Farm Manager | Production dashboard, herd overview, vaccination schedule, financial summary |
| Consumer | Browse products, place orders, track delivery, reviews |
| Marketplace Buyer | Browse vendors, search products, place orders |
| Livestock Buyer | Browse listings, place auction bids (real-time), make offers |

---

### 8.8 Recharts (Data Visualization)

**Purpose:** Dashboard charts for ERP (production trends, financial summaries, herd composition) and marketplace analytics.

```typescript
// Example: ERP Milk Production Chart
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

function MilkProductionChart({ data }: { data: DailyProduction[] }) {
  return (
    <ResponsiveContainer width="100%" height={400}>
      <LineChart data={data}>
        <CartesianGrid strokeDasharray="3 3" />
        <XAxis dataKey="date" />
        <YAxis />
        <Tooltip />
        <Legend />
        <Line type="monotone" dataKey="totalLiters" stroke="#2563eb" name="Total (L)" />
        <Line type="monotone" dataKey="target" stroke="#dc2626" strokeDasharray="5 5" name="Target" />
        <Line type="monotone" dataKey="avgPerCow" stroke="#16a34a" name="Avg/Cow" yAxisId="right" />
      </LineChart>
    </ResponsiveContainer>
  );
}
```

---

### 8.9 node-cron (Scheduled Tasks)

**Purpose:** Time-based task scheduling for vaccination reminders, daily summary reports, feed alerts, marketplace payout processing.

```typescript
// src/modules/common/services/scheduler.ts
import cron from 'node-cron';
import { smsQueue, reportQueue, payoutQueue } from '../queues/queue-definitions';

export function initializeScheduler() {
  // Daily 6 AM: Generate daily summary for farm manager
  cron.schedule('0 6 * * *', async () => {
    await reportQueue.add('daily-summary', { type: 'daily', date: new Date() });
  });

  // Daily 7 AM: Check vaccination due dates, send SMS reminders
  cron.schedule('0 7 * * *', async () => {
    const dueVaccinations = await getVaccinationsDueToday();
    for (const vax of dueVaccinations) {
      await smsQueue.add('vaccination-reminder', {
        phone: vax.managerPhone,
        template: 'vaccination_due',
        data: { animalTag: vax.tagNumber, vaccineName: vax.vaccineName },
        language: 'bn'
      });
    }
  });

  // Daily 8 AM: Check feed inventory levels
  cron.schedule('0 8 * * *', async () => {
    const lowStock = await checkFeedInventoryLevels();
    if (lowStock.length > 0) {
      await smsQueue.add('feed-alert', {
        phone: process.env.FARM_MANAGER_PHONE,
        template: 'low_feed_stock',
        data: { items: lowStock }
      });
    }
  });

  // Every Monday 10 AM: Process marketplace vendor payouts
  cron.schedule('0 10 * * 1', async () => {
    await payoutQueue.add('weekly-payout', { settlementDate: new Date() });
  });

  // Every 1st of month: Generate monthly P&L report
  cron.schedule('0 9 1 * *', async () => {
    const lastMonth = new Date();
    lastMonth.setMonth(lastMonth.getMonth() - 1);
    await reportQueue.add('monthly-pnl', { month: lastMonth.toISOString().slice(0, 7) });
  });
}
```

---

### 8.10 PDFKit / Puppeteer (Report Generation)

**Purpose:** Generate PDF reports for ERP (production reports, financial statements, health certificates), B2B invoices, and marketplace payout summaries.

**Strategy:**
- **PDFKit:** Programmatic PDF generation for structured reports (invoices, certificates)
- **Puppeteer:** HTML-to-PDF for complex reports with charts (monthly P&L, production dashboards)

```typescript
// src/modules/erp/services/report-generator.ts
import PDFDocument from 'pdfkit';
import puppeteer from 'puppeteer';

// Simple structured report (PDFKit)
export async function generateInvoicePDF(invoice: Invoice): Promise<Buffer> {
  return new Promise((resolve) => {
    const doc = new PDFDocument();
    const buffers: Buffer[] = [];
    doc.on('data', buffers.push.bind(buffers));
    doc.on('end', () => resolve(Buffer.concat(buffers)));

    doc.fontSize(20).text('Smart Dairy Limited', { align: 'center' });
    doc.fontSize(14).text(`Invoice #${invoice.number}`, { align: 'center' });
    // ... table rows, totals, etc.
    doc.end();
  });
}

// Complex report with charts (Puppeteer)
export async function generateProductionReport(month: string): Promise<Buffer> {
  const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox'] });
  const page = await browser.newPage();
  const html = await renderReportTemplate('production', { month });
  await page.setContent(html);
  const pdf = await page.pdf({ format: 'A4', printBackground: true });
  await browser.close();
  return Buffer.from(pdf);
}
```

---

### 8.11 next-intl (Internationalization)

**Purpose:** Bilingual support (English + Bengali) across all web pages.

```typescript
// src/i18n/messages/en.json
{
  "common": {
    "search": "Search",
    "addToCart": "Add to Cart",
    "checkout": "Checkout"
  },
  "erp": {
    "milkRecording": "Milk Recording",
    "vaccinationDue": "Vaccination Due",
    "dailySummary": "Daily Summary"
  }
}

// src/i18n/messages/bn.json
{
  "common": {
    "search": "অনুসন্ধান",
    "addToCart": "কার্টে যোগ করুন",
    "checkout": "চেকআউট"
  },
  "erp": {
    "milkRecording": "দুধ রেকর্ডিং",
    "vaccinationDue": "টিকা বকেয়া",
    "dailySummary": "দৈনিক সারাংশ"
  }
}
```

---

### 8.12 SMS Integration (Bangladesh Local Providers)

**Purpose:** Critical farm alerts, OTP verification, order notifications, vaccination reminders.

**Provider:** BulkSMSBD or SSL Wireless (~0.25 BDT/SMS, ~$0.0025/SMS)

```typescript
// src/modules/common/services/sms.service.ts
import axios from 'axios';

interface SMSPayload {
  phone: string;
  message: string;
}

export async function sendSMS({ phone, message }: SMSPayload): Promise<boolean> {
  try {
    const response = await axios.post(process.env.SMS_API_URL!, {
      api_key: process.env.SMS_API_KEY,
      type: 'text',
      number: phone.replace('+88', ''), // Bangladesh format
      senderid: process.env.SMS_SENDER_ID,
      message
    });
    return response.data.response_code === 202;
  } catch (error) {
    console.error('[SMS] Failed to send:', error);
    return false;
  }
}

// Bengali SMS templates
export const smsTemplates = {
  vaccination_due: (data: any) =>
    `[Smart Dairy] ${data.animalTag} গরুর ${data.vaccineName} টিকা আগামীকাল। অনুগ্রহ করে প্রস্তুত থাকুন।`,

  calving_expected: (data: any) =>
    `[Smart Dairy] ${data.animalTag} গরুর প্রসব আগামী ${data.daysRemaining} দিনের মধ্যে প্রত্যাশিত।`,

  health_emergency: (data: any) =>
    `[Smart Dairy] জরুরি! ${data.animalTag} গরুর স্বাস্থ্য সমস্যা: ${data.symptoms}। অবিলম্বে পশু চিকিৎসক ডাকুন।`,

  milk_anomaly: (data: any) =>
    `[Smart Dairy] ${data.animalTag} গরুর দুধ উৎপাদন ${data.dropPercent}% কমেছে। পরীক্ষা করুন।`,

  order_confirmed: (data: any) =>
    `[Smart Dairy] আপনার অর্ডার ${data.orderNumber} নিশ্চিত হয়েছে। ডেলিভারি: ${data.deliveryDate}`
};
```

**Critical Farm Alerts (always via SMS):**
- Animal health emergency
- Vaccination due (24h before)
- Calving expected (48h before)
- Bulk tank temperature out of range
- Low feed inventory
- Milking machine malfunction

---

### 8.13 shadcn/ui (Component Library)

**Purpose:** Pre-built, accessible, customizable UI components built on Radix UI primitives + Tailwind CSS. Used across all web module frontends.

**Key Components Used:**

| Component | Usage |
|-----------|-------|
| DataTable | ERP animal list, milk records, marketplace products, order management |
| Form + Input | All forms (product listing, animal registration, milk recording) |
| Dialog | Confirmations, quick actions, auction bid placement |
| Sheet | Mobile sidebar navigation, filter panels |
| Tabs | Module switching, dashboard views |
| Card | Product cards, animal cards, vendor profiles |
| Chart (recharts wrapper) | ERP dashboards, analytics |
| Calendar | Vaccination schedule, calving calendar, delivery scheduling |
| Command | Quick search (Ctrl+K) across all modules |
| Toast | Action feedback, error messages |

---

### 8.14 Docker Compose (Full Ecosystem)

```yaml
# docker-compose.yml
version: '3.8'

services:
  # Application
  app:
    build: .
    ports:
      - "3000:3000"   # Next.js
      - "5000:5000"   # Express API
    environment:
      DATABASE_URL: postgresql://smartdairy:${DB_PASSWORD}@postgres:5432/smartdairy
      REDIS_URL: redis://redis:6379
      MQTT_BROKER_URL: mqtt://mosquitto:1883
      MEILISEARCH_URL: http://meilisearch:7700
    depends_on:
      - postgres
      - redis
      - mosquitto
    volumes:
      - ./src:/app/src
      - ./prisma:/app/prisma

  # PostgreSQL 16
  postgres:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: smartdairy
      POSTGRES_USER: smartdairy
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    ports:
      - "5432:5432"
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./docker/postgres/init.sql:/docker-entrypoint-initdb.d/init.sql
    command: >
      postgres
      -c shared_buffers=256MB
      -c effective_cache_size=1GB
      -c work_mem=16MB
      -c maintenance_work_mem=128MB
      -c max_connections=100

  # Redis 7+
  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    command: redis-server --maxmemory 512mb --maxmemory-policy allkeys-lru

  # Mosquitto MQTT Broker
  mosquitto:
    image: eclipse-mosquitto:2
    ports:
      - "1883:1883"
      - "9001:9001"
    volumes:
      - ./docker/mosquitto/mosquitto.conf:/mosquitto/config/mosquitto.conf
      - mosquitto_data:/mosquitto/data

  # Meilisearch (deploy in Phase 4)
  meilisearch:
    image: getmeili/meilisearch:v1.6
    ports:
      - "7700:7700"
    environment:
      MEILI_MASTER_KEY: ${MEILISEARCH_MASTER_KEY}
      MEILI_ENV: production
    volumes:
      - meilisearch_data:/meili_data
    profiles:
      - marketplace  # Only start with: docker-compose --profile marketplace up

  # Nginx reverse proxy
  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./docker/nginx/ssl:/etc/nginx/ssl
    depends_on:
      - app

volumes:
  postgres_data:
  redis_data:
  mosquitto_data:
  meilisearch_data:
```

---

### 8.15 Infrastructure Costs (Updated)

| Item | Monthly Cost | Phase Required |
|------|-------------|----------------|
| DigitalOcean Droplet (4vCPU/8GB) | $48 | Phase 0 |
| DigitalOcean Spaces (250GB S3) | $5 | Phase 0 |
| Cloudflare Pro (CDN + WAF) | $20 | Phase 1 |
| SendGrid (40K emails/mo) | $15 | Phase 1 |
| BulkSMSBD (~5,000 SMS/mo) | ~$15 | Phase 1 |
| Apple Developer Account | $8.25 | Phase 2 |
| Google Play Developer | $2.08 (one-time) | Phase 2 |
| Domain renewal | ~$2 | Phase 0 |
| **Total** | **~$115/month** | |

**Scaling Path (Year 2+):**
- Add read replica for PostgreSQL if needed (~$24/mo)
- Upgrade to 8vCPU/16GB droplet if concurrent users exceed 500 (~$96/mo)
- Add dedicated Meilisearch instance if marketplace exceeds 10K products (~$24/mo)

---

## 9. CONCLUSION

This technology stack architecture provides a comprehensive foundation for the Smart Dairy Digital Ecosystem -- a five-module platform covering e-commerce, marketplace, livestock trading, dairy ERP, and mobile application. The architecture specifically optimizes for:

### Key Strengths
1. **Modular Monolith:** Single deployment, strict domain boundaries, future extraction path
2. **Solo Developer Efficiency:** TypeScript end-to-end, shared component library, comprehensive automation
3. **Bangladesh Optimization:** Offline-first mobile, local payments, Bengali language, SMS alerts
4. **Real-Time Capability:** Socket.IO for auctions, MQTT for IoT sensors, BullMQ for async processing
5. **Scalability:** Handles growth from 260 to 1,200 cattle, 100 to 500 concurrent users on $115/mo infra

### Implementation Priority
1. **Phase 0:** Core stack (Next.js 15, Express.js, PostgreSQL 16, Redis 7, Docker, Prisma)
2. **Phase 1:** Payment gateways, bilingual support, CDN, security headers
3. **Phase 2:** React Native/Expo, BullMQ queues, node-cron, expo-sqlite offline
4. **Phase 3:** MQTT/Mosquitto, Recharts dashboards, PDFKit reports
5. **Phase 4:** Meilisearch, Socket.IO (marketplace chat)
6. **Phase 5:** Socket.IO (auction bidding), transport integration

---

**Document Status:** Complete Technology Stack Architecture
**Last Updated:** January 31, 2026
**Version:** 2.0
**Next Review:** After Phase 1 implementation
**Maintained By:** Development Team

---

*This technology stack architecture covers the complete Smart Dairy Digital Ecosystem. Sections 1-7 document the core stack (Next.js, Express, PostgreSQL, Redis, Prisma). Section 8 documents all ecosystem expansion technologies (BullMQ, Socket.IO, MQTT, Meilisearch, React Native, and supporting libraries).*