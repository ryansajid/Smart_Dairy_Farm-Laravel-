# DEVELOPMENT ENVIRONMENT SETUP GUIDE
## Smart Dairy Website Development Project
### Complete Setup for Linux Desktop (Ubuntu/Debian-based)

**Document Version:** 1.0  
**Date:** December 2, 2025  
**Target:** Solo Full-Stack Developer  
**OS:** Linux (Ubuntu 22.04 LTS or later recommended)

---

## TABLE OF CONTENTS

1. [System Requirements](#1-system-requirements)
2. [Initial System Setup](#2-initial-system-setup)
3. [Installing Core Dependencies](#3-installing-core-dependencies)
4. [Database Setup](#4-database-setup)
5. [Project Initialization](#5-project-initialization)
6. [IDE Configuration](#6-ide-configuration)
7. [Development Tools](#7-development-tools)
8. [Running the Application](#8-running-the-application)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. SYSTEM REQUIREMENTS

### 1.1 Hardware Requirements

**Minimum:**
- CPU: 4 cores (2.5 GHz or higher)
- RAM: 8 GB
- Storage: 50 GB free space (SSD recommended)
- Internet: Stable broadband connection

**Recommended:**
- CPU: 6-8 cores (3.0 GHz or higher)
- RAM: 16 GB
- Storage: 100 GB+ SSD
- Internet: High-speed broadband

### 1.2 Software Requirements

- **OS:** Ubuntu 22.04 LTS, Linux Mint 21, or similar Debian-based distribution
- **Terminal:** bash or zsh
- **Browser:** Chrome/Chromium (for development and testing)

---

## 2. INITIAL SYSTEM SETUP

### 2.1 Update System

```bash
# Update package list
sudo apt update

# Upgrade installed packages
sudo apt upgrade -y

# Install essential build tools
sudo apt install -y build-essential curl wget git
```

### 2.2 Install Git

```bash
# Install Git
sudo apt install -y git

# Verify installation
git --version

# Configure Git
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# Generate SSH key for GitHub/GitLab
ssh-keygen -t ed25519 -C "your.email@example.com"

# Add SSH key to ssh-agent
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_ed25519

# Display public key (add this to GitHub/GitLab)
cat ~/.ssh/id_ed25519.pub
```

---

## 3. INSTALLING CORE DEPENDENCIES

### 3.1 Node.js (via nvm)

**Why nvm?** Easily switch between Node versions if needed.

```bash
# Install nvm
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Reload shell configuration
source ~/.bashrc
# or for zsh: source ~/.zshrc

# Verify nvm installation
nvm --version

# Install Node.js 18 LTS
nvm install 18

# Set as default
nvm alias default 18

# Verify installation
node --version  # Should show v18.x.x
npm --version   # Should show 9.x.x or higher

# Update npm to latest
npm install -g npm@latest
```

**Alternative: Install via NodeSource**
```bash
# If you prefer not to use nvm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Verify
node --version
npm --version
```

### 3.2 PostgreSQL

```bash
# Install PostgreSQL
sudo apt install -y postgresql postgresql-contrib

# Verify installation
psql --version  # Should show 14.x or higher

# Start PostgreSQL service
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Check status
sudo systemctl status postgresql
```

**Create Database and User:**
```bash
# Switch to postgres user
sudo -u postgres psql

# Inside psql prompt, run:
CREATE DATABASE smart_dairy_dev;
CREATE USER smart_dairy_user WITH PASSWORD 'dev_password_123';
GRANT ALL PRIVILEGES ON DATABASE smart_dairy_dev TO smart_dairy_user;

# For Prisma to work properly
GRANT ALL ON SCHEMA public TO smart_dairy_user;
ALTER DATABASE smart_dairy_dev OWNER TO smart_dairy_user;

# Exit psql
\q
```

**Optional: Install pgAdmin (GUI tool)**
```bash
# Add repository
curl -fsS https://www.pgadmin.org/static/packages_pgadmin_org.pub | sudo gpg --dearmor -o /usr/share/keyrings/packages-pgadmin-org.gpg
sudo sh -c 'echo "deb [signed-by=/usr/share/keyrings/packages-pgadmin-org.gpg] https://ftp.postgresql.org/pub/pgadmin/pgadmin4/apt/$(lsb_release -cs) pgadmin4 main" > /etc/apt/sources.list.d/pgadmin4.list'

# Install
sudo apt update
sudo apt install -y pgadmin4-desktop

# Launch
pgadmin4
```

**Or use DBeaver (alternative)**
```bash
# Download and install DBeaver
wget -O - https://dbeaver.io/debs/dbeaver.gpg.key | sudo apt-key add -
echo "deb https://dbeaver.io/debs/dbeaver-ce /" | sudo tee /etc/apt/sources.list.d/dbeaver.list
sudo apt update
sudo apt install -y dbeaver-ce
```

### 3.3 Redis

```bash
# Install Redis
sudo apt install -y redis-server

# Start Redis service
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Test Redis
redis-cli ping  # Should return "PONG"

# Optional: Configure Redis
sudo nano /etc/redis/redis.conf
# Set password: requirepass your_redis_password

# Restart after config changes
sudo systemctl restart redis-server
```

### 3.4 Nginx (for local development proxy)

```bash
# Install Nginx
sudo apt install -y nginx

# Start Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Test
curl http://localhost  # Should show Nginx welcome page

# Stop for now (we'll configure later)
sudo systemctl stop nginx
```

---

## 4. DATABASE SETUP

### 4.1 Test Database Connection

```bash
# Test connection with psql
psql -h localhost -U smart_dairy_user -d smart_dairy_dev

# You should see the PostgreSQL prompt
# Type \q to exit
```

### 4.2 Create .env File for Database

```bash
# Create .env file in backend directory
cat > ~/smart-dairy/backend/.env << 'EOF'
# Database
DATABASE_URL="postgresql://smart_dairy_user:dev_password_123@localhost:5432/smart_dairy_dev?schema=public"

# Server
PORT=3001
NODE_ENV=development

# JWT
JWT_SECRET=your_super_secret_jwt_key_change_in_production
JWT_EXPIRES_IN=1h
JWT_REFRESH_SECRET=your_refresh_token_secret
JWT_REFRESH_EXPIRES_IN=30d

# Redis
REDIS_URL=redis://localhost:6379
REDIS_PASSWORD=

# Frontend URL
FRONTEND_URL=http://localhost:3000

# Email (configure later)
SMTP_HOST=
SMTP_PORT=
SMTP_USER=
SMTP_PASS=

# SMS (configure later)
SMS_API_KEY=

# Payment Gateways (configure later)
BKASH_APP_KEY=
BKASH_APP_SECRET=
SSLCOMMERZ_STORE_ID=
SSLCOMMERZ_STORE_PASSWORD=
EOF
```

---

## 5. PROJECT INITIALIZATION

### 5.1 Create Project Structure

```bash
# Create project root
mkdir -p ~/smart-dairy
cd ~/smart-dairy

# Initialize Git repository
git init

# Create .gitignore
cat > .gitignore << 'EOF'
# Dependencies
node_modules/
package-lock.json
yarn.lock

# Environment variables
.env
.env.local
.env.*.local

# Build outputs
dist/
build/
.next/

# Logs
logs/
*.log
npm-debug.log*

# IDE
.vscode/
.idea/
*.swp
*.swo

# OS
.DS_Store
Thumbs.db

# Uploads
uploads/
*.tmp

# Database
*.sql
*.sqlite
EOF
```

### 5.2 Initialize Frontend (Next.js)

```bash
cd ~/smart-dairy

# Create Next.js app with TypeScript and Tailwind
npx create-next-app@latest frontend --typescript --tailwind --app --src-dir --import-alias "@/*"

# Navigate to frontend
cd frontend

# Install additional dependencies
npm install zustand axios react-hook-form @hookform/resolvers zod
npm install @tailwindcss/forms @tailwindcss/typography

# Install dev dependencies
npm install -D @types/node
```

**Create frontend .env.local:**
```bash
cat > .env.local << 'EOF'
NEXT_PUBLIC_API_URL=http://localhost:3001/api
NEXT_PUBLIC_APP_URL=http://localhost:3000
EOF
```

### 5.3 Initialize Backend (Express.js)

```bash
cd ~/smart-dairy

# Create backend directory
mkdir backend
cd backend

# Initialize npm project
npm init -y

# Install production dependencies
npm install express cors dotenv bcryptjs jsonwebtoken
npm install express-validator express-rate-limit helmet morgan
npm install pg redis
npm install @prisma/client

# Install dev dependencies
npm install -D typescript @types/node @types/express @types/cors
npm install -D @types/bcryptjs @types/jsonwebtoken
npm install -D @types/pg
npm install -D nodemon ts-node
npm install -D prisma

# Initialize TypeScript
npx tsc --init
```

**Update tsconfig.json:**
```bash
cat > tsconfig.json << 'EOF'
{
  "compilerOptions": {
    "target": "ES2020",
    "module": "commonjs",
    "lib": ["ES2020"],
    "outDir": "./dist",
    "rootDir": "./src",
    "strict": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "forceConsistentCasingInFileNames": true,
    "resolveJsonModule": true,
    "moduleResolution": "node"
  },
  "include": ["src/**/*"],
  "exclude": ["node_modules", "dist"]
}
EOF
```

**Update package.json scripts:**
```bash
# Add scripts to package.json
npm pkg set scripts.dev="nodemon --exec ts-node src/server.ts"
npm pkg set scripts.build="tsc"
npm pkg set scripts.start="node dist/server.js"
npm pkg set scripts.prisma:generate="prisma generate"
npm pkg set scripts.prisma:migrate="prisma migrate dev"
npm pkg set scripts.prisma:studio="prisma studio"
```

### 5.4 Initialize Prisma

```bash
# Initialize Prisma
npx prisma init

# This creates:
# - prisma/schema.prisma
# - .env file (if not exists)
```

**Create basic Prisma schema:**
```bash
cat > prisma/schema.prisma << 'EOF'
generator client {
  provider = "prisma-client-js"
}

datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
}

model User {
  id            Int       @id @default(autoincrement())
  name          String    @db.VarChar(100)
  email         String    @unique @db.VarChar(255)
  phone         String    @unique @db.VarChar(20)
  passwordHash  String    @map("password_hash") @db.VarChar(255)
  status        String    @default("pending") @db.VarChar(20)
  emailVerified Boolean   @default(false) @map("email_verified")
  phoneVerified Boolean   @default(false) @map("phone_verified")
  role          String    @default("customer") @db.VarChar(20)
  createdAt     DateTime  @default(now()) @map("created_at")
  updatedAt     DateTime  @updatedAt @map("updated_at")
  
  @@map("users")
}

model Category {
  id           Int       @id @default(autoincrement())
  name         String    @db.VarChar(100)
  slug         String    @unique @db.VarChar(100)
  description  String?   @db.Text
  imageUrl     String?   @map("image_url") @db.VarChar(255)
  parentId     Int?      @map("parent_id")
  displayOrder Int       @default(0) @map("display_order")
  active       Boolean   @default(true)
  createdAt    DateTime  @default(now()) @map("created_at")
  
  parent       Category?  @relation("CategoryToCategory", fields: [parentId], references: [id])
  children     Category[] @relation("CategoryToCategory")
  
  @@map("categories")
}

model Product {
  id               Int       @id @default(autoincrement())
  name             String    @db.VarChar(255)
  slug             String    @unique @db.VarChar(255)
  sku              String    @unique @db.VarChar(50)
  description      String?   @db.Text
  shortDescription String?   @map("short_description") @db.VarChar(500)
  categoryId       Int       @map("category_id")
  price            Decimal   @db.Decimal(10, 2)
  salePrice        Decimal?  @map("sale_price") @db.Decimal(10, 2)
  stockQuantity    Int       @default(0) @map("stock_quantity")
  status           String    @default("draft") @db.VarChar(20)
  featured         Boolean   @default(false)
  averageRating    Decimal   @default(0) @map("average_rating") @db.Decimal(3, 2)
  reviewCount      Int       @default(0) @map("review_count")
  createdAt        DateTime  @default(now()) @map("created_at")
  updatedAt        DateTime  @updatedAt @map("updated_at")
  
  @@map("products")
}
EOF
```

**Run migration:**
```bash
npx prisma migrate dev --name init

# Generate Prisma Client
npx prisma generate
```

### 5.5 Create Basic Backend Structure

```bash
cd ~/smart-dairy/backend

# Create directory structure
mkdir -p src/{routes,controllers,services,middleware,config,utils,types}
mkdir -p uploads/{products,documents}

# Create basic server file
cat > src/server.ts << 'EOF'
import express, { Express, Request, Response } from 'express';
import cors from 'cors';
import helmet from 'helmet';
import morgan from 'morgan';
import dotenv from 'dotenv';

dotenv.config();

const app: Express = express();
const PORT = process.env.PORT || 3001;

// Middleware
app.use(helmet());
app.use(cors({
  origin: process.env.FRONTEND_URL || 'http://localhost:3000',
  credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(morgan('dev'));

// Health check
app.get('/api/health', (req: Request, res: Response) => {
  res.json({ 
    status: 'OK', 
    timestamp: new Date().toISOString(),
    environment: process.env.NODE_ENV
  });
});

// Error handling
app.use((err: any, req: Request, res: Response, next: any) => {
  console.error(err.stack);
  res.status(err.status || 500).json({
    message: err.message || 'Internal Server Error',
    ...(process.env.NODE_ENV === 'development' && { stack: err.stack })
  });
});

// Start server
app.listen(PORT, () => {
  console.log(`🚀 Server running on http://localhost:${PORT}`);
  console.log(`📝 Environment: ${process.env.NODE_ENV}`);
});
EOF

# Create Prisma client instance
cat > src/config/prisma.ts << 'EOF'
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient({
  log: process.env.NODE_ENV === 'development' 
    ? ['query', 'error', 'warn'] 
    : ['error'],
});

export default prisma;
EOF

# Create Redis client
cat > src/config/redis.ts << 'EOF'
import { createClient } from 'redis';

const redisClient = createClient({
  url: process.env.REDIS_URL || 'redis://localhost:6379',
});

redisClient.on('error', (err) => {
  console.error('Redis Client Error:', err);
});

redisClient.on('connect', () => {
  console.log('✅ Connected to Redis');
});

// Connect to Redis
(async () => {
  await redisClient.connect();
})();

export default redisClient;
EOF
```

---

## 6. IDE CONFIGURATION

### 6.1 Install VSCode

```bash
# Download and install VSCode
sudo snap install code --classic

# Or using apt
wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > packages.microsoft.gpg
sudo install -D -o root -g root -m 644 packages.microsoft.gpg /etc/apt/keyrings/packages.microsoft.gpg
sudo sh -c 'echo "deb [arch=amd64,arm64,armhf signed-by=/etc/apt/keyrings/packages.microsoft.gpg] https://packages.microsoft.com/repos/code stable main" > /etc/apt/sources.list.d/vscode.list'
sudo apt update
sudo apt install -y code
```

### 6.2 Install VSCode Extensions

```bash
# Launch VSCode
code ~/smart-dairy

# Install extensions via command line
code --install-extension dbaeumer.vscode-eslint
code --install-extension esbenp.prettier-vscode
code --install-extension bradlc.vscode-tailwindcss
code --install-extension Prisma.prisma
code --install-extension ms-azuretools.vscode-docker
code --install-extension eamodio.gitlens
code --install-extension rangav.vscode-thunder-client
code --install-extension formulahendry.auto-rename-tag
code --install-extension christian-kohler.path-intellisense
code --install-extension ms-vscode.vscode-typescript-next
```

**Or install manually:**
1. Open VSCode
2. Press `Ctrl+Shift+X` (Extensions)
3. Search and install:
   - ESLint
   - Prettier
   - Tailwind CSS IntelliSense
   - Prisma
   - GitLens
   - Thunder Client (for API testing)
   - Auto Rename Tag
   - Path Intellisense
   - TypeScript

### 6.3 VSCode Settings

Create workspace settings:

```bash
mkdir -p ~/smart-dairy/.vscode

cat > ~/smart-dairy/.vscode/settings.json << 'EOF'
{
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true
  },
  "typescript.tsdk": "node_modules/typescript/lib",
  "typescript.enablePromptUseWorkspaceTsdk": true,
  "files.associations": {
    "*.css": "tailwindcss"
  },
  "tailwindCSS.experimental.classRegex": [
    ["cva\\(([^)]*)\\)", "[\"'`]([^\"'`]*).*?[\"'`]"],
    ["cn\\(([^)]*)\\)", "(?:'|\"|`)([^']*)(?:'|\"|`)"]
  ],
  "[typescript]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "[javascript]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "[typescriptreact]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "[json]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  }
}
EOF
```

### 6.4 Configure Prettier

```bash
# Frontend
cat > ~/smart-dairy/frontend/.prettierrc << 'EOF'
{
  "semi": true,
  "singleQuote": true,
  "tabWidth": 2,
  "trailingComma": "es5",
  "printWidth": 100,
  "arrowParens": "always"
}
EOF

# Backend
cat > ~/smart-dairy/backend/.prettierrc << 'EOF'
{
  "semi": true,
  "singleQuote": true,
  "tabWidth": 2,
  "trailingComma": "es5",
  "printWidth": 100,
  "arrowParens": "always"
}
EOF
```

### 6.5 Configure ESLint

```bash
# Frontend (Next.js already has ESLint configured)
cd ~/smart-dairy/frontend

# Install additional ESLint plugins
npm install -D eslint-config-prettier eslint-plugin-prettier

# Update .eslintrc.json
cat > .eslintrc.json << 'EOF'
{
  "extends": [
    "next/core-web-vitals",
    "plugin:prettier/recommended"
  ],
  "rules": {
    "prettier/prettier": "error"
  }
}
EOF

# Backend
cd ~/smart-dairy/backend

# Initialize ESLint
npm install -D eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin
npm install -D eslint-config-prettier eslint-plugin-prettier

# Create .eslintrc.js
cat > .eslintrc.js << 'EOF'
module.exports = {
  parser: '@typescript-eslint/parser',
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:prettier/recommended'
  ],
  parserOptions: {
    ecmaVersion: 2020,
    sourceType: 'module',
  },
  env: {
    node: true,
    es6: true,
  },
  rules: {
    '@typescript-eslint/explicit-module-boundary-types': 'off',
    '@typescript-eslint/no-explicit-any': 'warn',
    'prettier/prettier': 'error'
  },
};
EOF
```

---

## 7. DEVELOPMENT TOOLS

### 7.1 Install Thunder Client (or Postman alternative)

Thunder Client is already included in VSCode extensions. To use:

1. Click Thunder Client icon in sidebar
2. Create new request
3. Test your APIs

**Alternative: Postman**
```bash
# Install Postman
sudo snap install postman
```

### 7.2 Install Database GUI Tools

**Option 1: Prisma Studio (included with Prisma)**
```bash
cd ~/smart-dairy/backend
npm run prisma:studio

# Opens in browser at http://localhost:5555
```

**Option 2: DBeaver (already installed)**
```bash
# Launch DBeaver
dbeaver

# Connect to PostgreSQL:
# Host: localhost
# Port: 5432
# Database: smart_dairy_dev
# Username: smart_dairy_user
# Password: dev_password_123
```

### 7.3 Install Git GUI (optional)

```bash
# GitKraken (visual Git client)
sudo snap install gitkraken --classic

# Or use VSCode's built-in Git + GitLens extension
```

### 7.4 Browser Developer Tools

**Install Chrome/Chromium:**
```bash
sudo apt install -y chromium-browser

# Or Google Chrome
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
sudo apt -f install
```

**Install React Developer Tools:**
1. Open Chrome/Chromium
2. Go to Chrome Web Store
3. Search for "React Developer Tools"
4. Click "Add to Chrome"

---

## 8. RUNNING THE APPLICATION

### 8.1 Start Backend Server

```bash
# Terminal 1: Backend
cd ~/smart-dairy/backend

# Install dependencies (if not done)
npm install

# Run Prisma migrations
npm run prisma:migrate

# Start development server
npm run dev

# You should see:
# 🚀 Server running on http://localhost:3001
# ✅ Connected to Redis
```

### 8.2 Start Frontend Server

```bash
# Terminal 2: Frontend
cd ~/smart-dairy/frontend

# Install dependencies (if not done)
npm install

# Start development server
npm run dev

# You should see:
# ▲ Next.js 14.x.x
# - Local: http://localhost:3000
```

### 8.3 Access the Application

**Frontend:** http://localhost:3000  
**Backend API:** http://localhost:3001/api  
**Backend Health:** http://localhost:3001/api/health

### 8.4 Development Workflow

**Typical Day:**

```bash
# Morning - Start all services

# Terminal 1: Backend
cd ~/smart-dairy/backend && npm run dev

# Terminal 2: Frontend
cd ~/smart-dairy/frontend && npm run dev

# Terminal 3: Prisma Studio (optional)
cd ~/smart-dairy/backend && npm run prisma:studio

# Terminal 4: Available for commands
```

**Using tmux (recommended for multiple terminals):**
```bash
# Install tmux
sudo apt install -y tmux

# Create tmux session
tmux new -s smartdairy

# Split panes
Ctrl+b "  # Split horizontally
Ctrl+b %  # Split vertically

# Navigate panes
Ctrl+b arrow keys

# Detach from session
Ctrl+b d

# Reattach to session
tmux attach -t smartdairy
```

**Sample tmux setup script:**
```bash
#!/bin/bash
# Save as ~/smart-dairy/dev-start.sh

SESSION="smartdairy"

# Create new session
tmux new-session -d -s $SESSION

# Window 1: Backend
tmux rename-window -t $SESSION:1 'backend'
tmux send-keys -t $SESSION:1 'cd ~/smart-dairy/backend && npm run dev' C-m

# Window 2: Frontend
tmux new-window -t $SESSION:2 -n 'frontend'
tmux send-keys -t $SESSION:2 'cd ~/smart-dairy/frontend && npm run dev' C-m

# Window 3: Commands
tmux new-window -t $SESSION:3 -n 'commands'
tmux send-keys -t $SESSION:3 'cd ~/smart-dairy' C-m

# Attach to session
tmux attach-session -t $SESSION
```

Make executable and run:
```bash
chmod +x ~/smart-dairy/dev-start.sh
~/smart-dairy/dev-start.sh
```

---

## 9. TROUBLESHOOTING

### 9.1 Common Issues

**Issue: Port already in use**
```bash
# Find process using port 3000 or 3001
sudo lsof -i :3000
sudo lsof -i :3001

# Kill process
kill -9 <PID>

# Or kill all Node processes
pkill -f node
```

**Issue: PostgreSQL connection refused**
```bash
# Check if PostgreSQL is running
sudo systemctl status postgresql

# Start if not running
sudo systemctl start postgresql

# Check port
sudo netstat -plunt | grep postgres

# Test connection
psql -h localhost -U smart_dairy_user -d smart_dairy_dev
```

**Issue: Redis connection error**
```bash
# Check Redis status
sudo systemctl status redis-server

# Start Redis
sudo systemctl start redis-server

# Test Redis
redis-cli ping
```

**Issue: Permission denied on uploads folder**
```bash
cd ~/smart-dairy/backend
chmod -R 755 uploads/
```

**Issue: Prisma Client not generated**
```bash
cd ~/smart-dairy/backend
npx prisma generate
```

**Issue: Module not found errors**
```bash
# Clear node_modules and reinstall
cd ~/smart-dairy/frontend
rm -rf node_modules package-lock.json
npm install

cd ~/smart-dairy/backend
rm -rf node_modules package-lock.json
npm install
```

### 9.2 Reset Database

```bash
cd ~/smart-dairy/backend

# Drop all tables and recreate
npx prisma migrate reset

# Or manually
sudo -u postgres psql
DROP DATABASE smart_dairy_dev;
CREATE DATABASE smart_dairy_dev;
GRANT ALL PRIVILEGES ON DATABASE smart_dairy_dev TO smart_dairy_user;
\q

# Run migrations again
npx prisma migrate dev
```

### 9.3 Clear Redis Cache

```bash
# Connect to Redis CLI
redis-cli

# Clear all keys
FLUSHALL

# Or specific pattern
KEYS product:*
DEL product:1 product:2

# Exit
exit
```

### 9.4 Check Logs

**Backend logs:**
```bash
# If using PM2 in production
pm2 logs

# Check system logs
journalctl -u nginx
journalctl -u postgresql
```

**Frontend logs:**
```bash
# Next.js logs are in terminal
# Check .next/ folder for build issues
```

---

## 10. PRODUCTION DEPLOYMENT PREP

### 10.1 Environment Variables

Create production .env files:

```bash
# Backend production .env
DATABASE_URL="postgresql://user:password@production-db-host:5432/smart_dairy_prod"
NODE_ENV=production
JWT_SECRET=<strong-random-secret>
REDIS_URL=redis://production-redis:6379
```

### 10.2 Build for Production

**Frontend:**
```bash
cd ~/smart-dairy/frontend
npm run build

# Test production build locally
npm run start
```

**Backend:**
```bash
cd ~/smart-dairy/backend
npm run build

# Test
node dist/server.js
```

---

## 11. HELPFUL COMMANDS CHEAT SHEET

```bash
# Git
git status
git add .
git commit -m "message"
git push origin main

# npm
npm install <package>
npm uninstall <package>
npm update
npm outdated
npm run dev

# PostgreSQL
sudo -u postgres psql
\l  # List databases
\c database_name  # Connect to database
\dt  # List tables
\d table_name  # Describe table
\q  # Quit

# Redis
redis-cli
KEYS *  # List all keys
GET key_name
SET key_name value
DEL key_name

# Prisma
npx prisma studio  # GUI
npx prisma migrate dev  # Create migration
npx prisma generate  # Generate client
npx prisma db push  # Push schema changes

# System
systemctl status service_name
systemctl start service_name
systemctl stop service_name
systemctl restart service_name

# Disk space
df -h
du -sh ~/smart-dairy

# Memory usage
free -h
htop
```

---

## 12. NEXT STEPS

After completing this setup:

1. ✅ All dependencies installed
2. ✅ Databases configured
3. ✅ Project initialized
4. ✅ IDE configured
5. ✅ Development servers running

**Next:**
- Start implementing features (refer to SRS documents)
- Follow the development timeline (9-month roadmap)
- Regular commits to Git
- Document as you build

**Development Order:**
1. **Week 1-2:** Authentication system
2. **Week 3-4:** Product catalog
3. **Week 5-6:** Shopping cart
4. **Week 7-8:** Checkout process
5. **Week 9-12:** Admin panel

---

**Document Status:** Development Setup Guide - Complete  
**Last Updated:** December 2, 2025  
**Tested On:** Ubuntu 22.04 LTS

**Support:** Refer to SRS and Technical Architecture documents for implementation details.
