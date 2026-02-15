# DEVELOPMENT ENVIRONMENT SETUP GUIDE
## Smart Dairy Website - Linux Desktop Configuration

**Document:** 04 - Development Environment Setup  
**Version:** 1.0  
**Date:** December 2, 2025  
**OS:** Linux (Ubuntu 22.04+ or similar)

---

## TABLE OF CONTENTS

1. [System Requirements](#1-system-requirements)
2. [Essential Software Installation](#2-essential-software-installation)
3. [Frontend Development Setup](#3-frontend-development-setup)
4. [Backend Development Setup](#4-backend-development-setup)
5. [Database Setup](#5-database-setup)
6. [CMS Setup](#6-cms-setup)
7. [Development Tools Configuration](#7-development-tools-configuration)
8. [Project Initialization](#8-project-initialization)
9. [Running the Development Environment](#9-running-the-development-environment)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. SYSTEM REQUIREMENTS

### 1.1 Hardware Requirements

**Minimum:**
- CPU: 4 cores (Intel i5 or AMD equivalent)
- RAM: 16GB
- Storage: 256GB SSD
- Internet: Broadband connection

**Recommended:**
- CPU: 6+ cores (Intel i7/i9 or AMD Ryzen 7/9)
- RAM: 32GB
- Storage: 512GB+ NVMe SSD
- Internet: High-speed broadband

### 1.2 Operating System

**Supported Distributions:**
- Ubuntu 22.04 LTS or newer (Recommended)
- Debian 11+ (Bullseye)
- Fedora 36+
- Arch Linux (latest)
- Pop!_OS 22.04+
- Linux Mint 21+

**Note:** This guide uses Ubuntu commands. Adjust package manager commands for other distros:
- Ubuntu/Debian: `apt` or `apt-get`
- Fedora: `dnf`
- Arch: `pacman`

---

## 2. ESSENTIAL SOFTWARE INSTALLATION

### 2.1 Update System

```bash
# Update package lists
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
# Expected output: git version 2.x.x

# Configure Git
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
git config --global init.defaultBranch main
```

### 2.3 Install Node.js and npm

**Option 1: Using NodeSource Repository (Recommended)**

```bash
# Install Node.js 20 LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node --version
# Expected: v20.x.x

npm --version
# Expected: 10.x.x
```

**Option 2: Using NVM (Node Version Manager)**

```bash
# Install NVM
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Reload shell configuration
source ~/.bashrc
# or for zsh: source ~/.zshrc

# Install Node.js LTS
nvm install --lts

# Set default version
nvm alias default node

# Verify
node --version
npm --version
```

### 2.4 Install Visual Studio Code

**Option 1: Using Snap**

```bash
sudo snap install --classic code
```

**Option 2: Using APT Repository**

```bash
# Add Microsoft GPG key
wget -qO- https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > packages.microsoft.gpg
sudo install -o root -g root -m 644 packages.microsoft.gpg /etc/apt/trusted.gpg.d/
sudo sh -c 'echo "deb [arch=amd64] https://packages.microsoft.com/repos/code stable main" > /etc/apt/sources.list.d/vscode.list'

# Install VS Code
sudo apt update
sudo apt install -y code

# Launch VS Code
code
```

**Essential VS Code Extensions:**

```bash
# Install extensions via command line
code --install-extension dbaeumer.vscode-eslint
code --install-extension esbenp.prettier-vscode
code --install-extension ms-vscode.vscode-typescript-next
code --install-extension bradlc.vscode-tailwindcss
code --install-extension dsznajder.es7-react-js-snippets
code --install-extension eamodio.gitlens
code --install-extension humao.rest-client
code --install-extension ckolkman.vscode-postgres
code --install-extension ms-azuretools.vscode-docker
```

---

## 3. FRONTEND DEVELOPMENT SETUP

### 3.1 Create Next.js Project

```bash
# Create project directory
mkdir ~/projects/smart-dairy
cd ~/projects/smart-dairy

# Create Next.js app with TypeScript
npx create-next-app@latest smart-dairy-frontend --typescript --tailwind --app --src-dir --import-alias "@/*"

# Navigate to project
cd smart-dairy-frontend
```

**Project Creation Options Selected:**
- ✅ TypeScript
- ✅ ESLint
- ✅ Tailwind CSS
- ✅ `src/` directory
- ✅ App Router
- ✅ Import alias (@/*)

### 3.2 Install Frontend Dependencies

```bash
# Core dependencies
npm install axios zustand
npm install react-hook-form yup
npm install @hookform/resolvers

# UI Components (choose one)
# Option 1: Headless UI (with Tailwind)
npm install @headlessui/react @heroicons/react

# Option 2: Material-UI (alternative)
# npm install @mui/material @emotion/react @emotion/styled

# Additional utilities
npm install clsx
npm install date-fns
npm install react-toastify

# Charts and visualization
npm install recharts
# or: npm install chart.js react-chartjs-2

# 360° Virtual Tour
npm install pannellum-react
# or: npm install @photo-sphere-viewer/core

# Video player
npm install react-player

# Icons
npm install react-icons

# Dev dependencies
npm install -D @types/node
npm install -D typescript
```

### 3.3 Configure TypeScript

**tsconfig.json** (Auto-generated, verify these settings):

```json
{
  "compilerOptions": {
    "target": "ES2020",
    "lib": ["ES2020", "DOM", "DOM.Iterable"],
    "jsx": "preserve",
    "module": "ESNext",
    "moduleResolution": "bundler",
    "resolveJsonModule": true,
    "allowJs": true,
    "strict": true,
    "noEmit": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "forceConsistentCasingInFileNames": true,
    "incremental": true,
    "plugins": [
      {
        "name": "next"
      }
    ],
    "paths": {
      "@/*": ["./src/*"]
    }
  },
  "include": ["next-env.d.ts", "**/*.ts", "**/*.tsx", ".next/types/**/*.ts"],
  "exclude": ["node_modules"]
}
```

### 3.4 Configure Tailwind CSS

**tailwind.config.js:**

```javascript
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/pages/**/*.{js,ts,jsx,tsx,mdx}',
    './src/components/**/*.{js,ts,jsx,tsx,mdx}',
    './src/app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
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
        sans: ['var(--font-poppins)', 'sans-serif'],
        body: ['var(--font-open-sans)', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
```

### 3.5 Setup Environment Variables

```bash
# Create .env.local file
touch .env.local
```

**Content of .env.local:**

```env
# API Configuration
NEXT_PUBLIC_API_URL=http://localhost:5000/api/v1
NEXT_PUBLIC_CMS_URL=http://localhost:1337/api

# Google Maps (get from Google Cloud Console)
NEXT_PUBLIC_GOOGLE_MAPS_KEY=your_google_maps_api_key

# Google Analytics
NEXT_PUBLIC_GA_ID=your_ga_measurement_id

# Payment Gateways (get from respective providers)
NEXT_PUBLIC_SSLCOMMERZ_STORE_ID=your_store_id

# Other
NEXT_PUBLIC_APP_URL=http://localhost:3000
NEXT_PUBLIC_UPLOAD_URL=http://localhost:5000/uploads
```

---

## 4. BACKEND DEVELOPMENT SETUP

### 4.1 Create Express.js Project

```bash
# Navigate to project root
cd ~/projects/smart-dairy

# Create backend directory
mkdir smart-dairy-backend
cd smart-dairy-backend

# Initialize npm project
npm init -y

# Install TypeScript
npm install -D typescript @types/node @types/express ts-node nodemon

# Initialize TypeScript config
npx tsc --init
```

### 4.2 Configure TypeScript for Backend

**tsconfig.json:**

```json
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
    "moduleResolution": "node",
    "declaration": true,
    "declarationMap": true,
    "sourceMap": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "noImplicitReturns": true,
    "noFallthroughCasesInSwitch": true
  },
  "include": ["src/**/*"],
  "exclude": ["node_modules", "dist"]
}
```

### 4.3 Install Backend Dependencies

```bash
# Core framework
npm install express
npm install -D @types/express

# Database
npm install pg          # PostgreSQL client
npm install typeorm     # ORM (or sequelize, prisma)
npm install reflect-metadata

# Redis
npm install redis
npm install -D @types/redis

# Authentication & Security
npm install jsonwebtoken bcryptjs
npm install -D @types/jsonwebtoken @types/bcryptjs
npm install passport passport-jwt passport-local
npm install helmet cors express-rate-limit

# Validation
npm install joi
# or: npm install zod

# Environment variables
npm install dotenv

# File upload
npm install multer
npm install -D @types/multer

# Email
npm install nodemailer
npm install -D @types/nodemailer

# Utilities
npm install uuid
npm install -D @types/uuid
npm install morgan          # HTTP logger
npm install winston         # Application logger

# Dev tools
npm install -D nodemon ts-node
```

### 4.4 Setup Project Structure

```bash
# Create directory structure
mkdir -p src/{config,controllers,middleware,models,routes,services,utils,types,database/{migrations,seeds}}

# Create main files
touch src/app.ts
touch src/server.ts
touch .env
touch .env.example
touch .gitignore
```

### 4.5 Configure package.json Scripts

**package.json (add to scripts section):**

```json
{
  "scripts": {
    "dev": "nodemon --exec ts-node src/server.ts",
    "build": "tsc",
    "start": "node dist/server.js",
    "typeorm": "typeorm-ts-node-commonjs",
    "migration:generate": "npm run typeorm migration:generate",
    "migration:run": "npm run typeorm migration:run",
    "migration:revert": "npm run typeorm migration:revert"
  }
}
```

### 4.6 Configure Nodemon

**nodemon.json:**

```json
{
  "watch": ["src"],
  "ext": "ts,json",
  "ignore": ["src/**/*.spec.ts"],
  "exec": "ts-node src/server.ts",
  "env": {
    "NODE_ENV": "development"
  }
}
```

### 4.7 Setup Environment Variables

**.env:**

```env
# Server Configuration
NODE_ENV=development
PORT=5000
API_URL=http://localhost:5000
FRONTEND_URL=http://localhost:3000

# Database Configuration
DB_HOST=localhost
DB_PORT=5432
DB_NAME=smart_dairy_dev
DB_USER=postgres
DB_PASSWORD=your_password

# Redis Configuration
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=

# JWT Configuration
JWT_SECRET=your_super_secret_jwt_key_change_this_in_production
JWT_EXPIRE=7d
JWT_REFRESH_SECRET=your_refresh_token_secret
JWT_REFRESH_EXPIRE=30d

# Email Configuration (Local testing with Ethereal)
SMTP_HOST=smtp.ethereal.email
SMTP_PORT=587
SMTP_USER=your_ethereal_username
SMTP_PASS=your_ethereal_password
FROM_EMAIL=noreply@smartdairy.com
FROM_NAME=Smart Dairy

# SMS Gateway (Bangladesh provider)
SMS_GATEWAY_URL=
SMS_GATEWAY_API_KEY=
SMS_GATEWAY_SENDER_ID=SmartDairy

# Payment Gateways
SSLCOMMERZ_STORE_ID=
SSLCOMMERZ_STORE_PASSWORD=
SSLCOMMERZ_IS_LIVE=false

BKASH_APP_KEY=
BKASH_APP_SECRET=
BKASH_USERNAME=
BKASH_PASSWORD=

NAGAD_MERCHANT_ID=
NAGAD_MERCHANT_KEY=

# File Upload
UPLOAD_PATH=./public/uploads
MAX_FILE_SIZE=5242880  # 5MB in bytes

# Google Maps
GOOGLE_MAPS_API_KEY=

# Others
RATE_LIMIT_WINDOW_MS=900000  # 15 minutes
RATE_LIMIT_MAX_REQUESTS=100
```

**.env.example:** (Same as above but with placeholder values)

### 4.8 Create .gitignore

**.gitignore:**

```gitignore
# Dependencies
node_modules/

# Build output
dist/
build/

# Environment files
.env
.env.local
.env.*.local

# Logs
logs/
*.log
npm-debug.log*
yarn-debug.log*
yarn-error.log*

# OS files
.DS_Store
Thumbs.db

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# Uploads
public/uploads/*
!public/uploads/.gitkeep

# Testing
coverage/
.nyc_output/

# Misc
.cache/
temp/
tmp/
```

---

## 5. DATABASE SETUP

### 5.1 Install PostgreSQL

```bash
# Install PostgreSQL and contrib package
sudo apt install -y postgresql postgresql-contrib

# Start PostgreSQL service
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Check status
sudo systemctl status postgresql
```

### 5.2 Configure PostgreSQL

```bash
# Switch to postgres user
sudo -i -u postgres

# Open PostgreSQL prompt
psql

# In psql prompt, create user and database
CREATE USER smart_dairy_user WITH PASSWORD 'your_secure_password';
CREATE DATABASE smart_dairy_dev OWNER smart_dairy_user;
GRANT ALL PRIVILEGES ON DATABASE smart_dairy_dev TO smart_dairy_user;

# Exit psql
\q

# Exit postgres user
exit
```

### 5.3 Configure PostgreSQL for Local Access

```bash
# Edit PostgreSQL configuration
sudo nano /etc/postgresql/14/main/pg_hba.conf

# Find the line (usually near the bottom):
# local   all             all                                     peer

# Change to:
# local   all             all                                     md5

# Save and exit (Ctrl+X, Y, Enter)

# Restart PostgreSQL
sudo systemctl restart postgresql
```

### 5.4 Test Database Connection

```bash
# Connect to database
psql -h localhost -U smart_dairy_user -d smart_dairy_dev

# If successful, you'll see:
# smart_dairy_dev=>

# Exit
\q
```

### 5.5 Install Database Client (DBeaver)

```bash
# Download and install DBeaver Community
sudo snap install dbeaver-ce

# Launch
dbeaver-ce
```

**Connect to Database in DBeaver:**
1. Click "New Database Connection"
2. Select PostgreSQL
3. Enter connection details:
   - Host: localhost
   - Port: 5432
   - Database: smart_dairy_dev
   - Username: smart_dairy_user
   - Password: your_secure_password
4. Test Connection
5. Finish

---

## 6. CACHE SETUP (REDIS)

### 6.1 Install Redis

```bash
# Install Redis
sudo apt install -y redis-server

# Start Redis service
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Check status
sudo systemctl status redis-server
```

### 6.2 Configure Redis

```bash
# Edit Redis configuration
sudo nano /etc/redis/redis.conf

# Find and set:
# supervised systemd
# maxmemory 256mb
# maxmemory-policy allkeys-lru

# Save and exit
# Restart Redis
sudo systemctl restart redis-server
```

### 6.3 Test Redis Connection

```bash
# Connect to Redis CLI
redis-cli

# Test with ping command
127.0.0.1:6379> ping
# Expected: PONG

# Set a test key
127.0.0.1:6379> set test "Hello Redis"
# Expected: OK

# Get the key
127.0.0.1:6379> get test
# Expected: "Hello Redis"

# Exit
127.0.0.1:6379> exit
```

---

## 7. CMS SETUP (STRAPI)

### 7.1 Create Strapi Project

```bash
# Navigate to project root
cd ~/projects/smart-dairy

# Create Strapi project
npx create-strapi-app@latest smart-dairy-cms --quickstart

# This will:
# 1. Create the project
# 2. Install dependencies
# 3. Build the admin panel
# 4. Start the development server
# 5. Open browser to http://localhost:1337/admin
```

### 7.2 Initial Strapi Setup

When browser opens to `http://localhost:1337/admin`:

1. **Create First Administrator:**
   - Firstname: Your first name
   - Lastname: Your last name
   - Email: your.email@example.com
   - Password: Strong password
   - Click "Let's start"

2. **You'll see the Strapi admin dashboard**

### 7.3 Configure Strapi for Development

**Stop the Strapi server** (Ctrl+C in terminal), then:

```bash
cd smart-dairy-cms

# Install PostgreSQL dependency (if not using SQLite)
npm install pg

# Configure database
nano config/database.ts
```

**config/database.ts:**

```typescript
export default ({ env }) => ({
  connection: {
    client: 'postgres',
    connection: {
      host: env('DATABASE_HOST', '127.0.0.1'),
      port: env.int('DATABASE_PORT', 5432),
      database: env('DATABASE_NAME', 'smart_dairy_cms'),
      user: env('DATABASE_USERNAME', 'smart_dairy_user'),
      password: env('DATABASE_PASSWORD', 'your_secure_password'),
      ssl: env.bool('DATABASE_SSL', false),
    },
    debug: false,
  },
});
```

### 7.4 Create CMS Database

```bash
# Connect to PostgreSQL
psql -h localhost -U smart_dairy_user -d postgres

# Create CMS database
CREATE DATABASE smart_dairy_cms OWNER smart_dairy_user;

# Exit
\q
```

### 7.5 Configure Strapi Environment

```bash
# Edit .env file
nano .env
```

**.env:**

```env
HOST=0.0.0.0
PORT=1337
APP_KEYS=your_app_keys_here
API_TOKEN_SALT=your_api_token_salt
ADMIN_JWT_SECRET=your_admin_jwt_secret
TRANSFER_TOKEN_SALT=your_transfer_token_salt
JWT_SECRET=your_jwt_secret

# Database
DATABASE_CLIENT=postgres
DATABASE_HOST=127.0.0.1
DATABASE_PORT=5432
DATABASE_NAME=smart_dairy_cms
DATABASE_USERNAME=smart_dairy_user
DATABASE_PASSWORD=your_secure_password
DATABASE_SSL=false

NODE_ENV=development
```

### 7.6 Build and Start Strapi

```bash
# Build admin panel
npm run build

# Start development server
npm run develop
```

---

## 8. DEVELOPMENT TOOLS CONFIGURATION

### 8.1 Install and Configure ESLint

**For Frontend (Next.js):**

```bash
cd ~/projects/smart-dairy/smart-dairy-frontend

# ESLint is already included with create-next-app
# Install additional plugins
npm install -D @typescript-eslint/eslint-plugin @typescript-eslint/parser

# Install Prettier
npm install -D prettier eslint-config-prettier eslint-plugin-prettier
```

**Create .prettierrc:**

```json
{
  "semi": true,
  "trailingComma": "es5",
  "singleQuote": true,
  "printWidth": 80,
  "tabWidth": 2,
  "useTabs": false
}
```

**Update .eslintrc.json:**

```json
{
  "extends": [
    "next/core-web-vitals",
    "plugin:@typescript-eslint/recommended",
    "prettier"
  ],
  "plugins": ["@typescript-eslint"],
  "rules": {
    "@typescript-eslint/no-unused-vars": "warn",
    "@typescript-eslint/no-explicit-any": "warn"
  }
}
```

**For Backend:**

```bash
cd ~/projects/smart-dairy/smart-dairy-backend

# Install ESLint
npm install -D eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin

# Initialize ESLint config
npx eslint --init
```

Select options:
- How would you like to use ESLint? **To check syntax and find problems**
- What type of modules? **JavaScript modules (import/export)**
- Which framework? **None**
- Does your project use TypeScript? **Yes**
- Where does your code run? **Node**
- What format config file? **JSON**
- Install dependencies? **Yes**

### 8.2 Configure VS Code

**Create workspace settings:**

```bash
cd ~/projects/smart-dairy

# Create .vscode directory
mkdir .vscode

# Create settings.json
touch .vscode/settings.json
```

**.vscode/settings.json:**

```json
{
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true
  },
  "[typescript]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "[typescriptreact]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode"
  },
  "typescript.tsdk": "node_modules/typescript/lib",
  "tailwindCSS.includeLanguages": {
    "typescript": "javascript",
    "typescriptreact": "javascript"
  },
  "files.associations": {
    "*.css": "tailwindcss"
  }
}
```

### 8.3 Install API Testing Tool

**Option 1: Postman**

```bash
# Download and install Postman
wget https://dl.pstmn.io/download/latest/linux64 -O postman-linux-x64.tar.gz
sudo tar -xzf postman-linux-x64.tar.gz -C /opt
sudo ln -s /opt/Postman/Postman /usr/bin/postman

# Create desktop entry
cat > ~/.local/share/applications/postman.desktop <<EOF
[Desktop Entry]
Type=Application
Name=Postman
Icon=/opt/Postman/app/resources/app/assets/icon.png
Exec="/opt/Postman/Postman" %U
Terminal=false
Categories=Development;
MimeType=x-scheme-handler/postman;
EOF

# Launch Postman
postman
```

**Option 2: Insomnia**

```bash
# Add repository
echo "deb [trusted=yes arch=amd64] https://download.konghq.com/insomnia-ubuntu/ default all" \
    | sudo tee -a /etc/apt/sources.list.d/insomnia.list

# Install
sudo apt update
sudo apt install insomnia
```

---

## 9. PROJECT INITIALIZATION

### 9.1 Initialize Git Repository

```bash
cd ~/projects/smart-dairy

# Initialize Git for entire project
git init

# Create .gitignore at root
cat > .gitignore <<EOF
# Node modules
node_modules/

# Environment files
.env
.env.local
.env.*.local

# Build outputs
dist/
build/
.next/

# Logs
*.log

# OS files
.DS_Store
Thumbs.db

# IDE
.vscode/
.idea/
EOF

# Initial commit
git add .
git commit -m "Initial project setup"
```

### 9.2 Create Development Scripts

**Create a start-all script:**

```bash
# Create scripts directory
mkdir ~/projects/smart-dairy/scripts

# Create start script
nano ~/projects/smart-dairy/scripts/start-dev.sh
```

**start-dev.sh:**

```bash
#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}Starting Smart Dairy Development Environment${NC}"

# Start PostgreSQL
echo -e "${BLUE}Starting PostgreSQL...${NC}"
sudo systemctl start postgresql

# Start Redis
echo -e "${BLUE}Starting Redis...${NC}"
sudo systemctl start redis-server

# Check if services are running
if systemctl is-active --quiet postgresql; then
    echo -e "${GREEN}✓ PostgreSQL is running${NC}"
else
    echo -e "${RED}✗ PostgreSQL failed to start${NC}"
    exit 1
fi

if systemctl is-active --quiet redis-server; then
    echo -e "${GREEN}✓ Redis is running${NC}"
else
    echo -e "${RED}✗ Redis failed to start${NC}"
    exit 1
fi

# Open terminals for each service
echo -e "${BLUE}Opening development terminals...${NC}"

# Backend
gnome-terminal --tab --title="Backend API" -- bash -c \
    "cd ~/projects/smart-dairy/smart-dairy-backend && npm run dev; exec bash"

# Frontend
gnome-terminal --tab --title="Frontend (Next.js)" -- bash -c \
    "cd ~/projects/smart-dairy/smart-dairy-frontend && npm run dev; exec bash"

# Strapi CMS
gnome-terminal --tab --title="Strapi CMS" -- bash -c \
    "cd ~/projects/smart-dairy/smart-dairy-cms && npm run develop; exec bash"

echo -e "${GREEN}Development environment started!${NC}"
echo -e "Frontend: ${BLUE}http://localhost:3000${NC}"
echo -e "Backend API: ${BLUE}http://localhost:5000${NC}"
echo -e "Strapi CMS: ${BLUE}http://localhost:1337/admin${NC}"
```

```bash
# Make script executable
chmod +x ~/projects/smart-dairy/scripts/start-dev.sh
```

**Create stop script:**

```bash
nano ~/projects/smart-dairy/scripts/stop-dev.sh
```

**stop-dev.sh:**

```bash
#!/bin/bash

echo "Stopping development services..."

# Kill node processes (be careful!)
# pkill -f "node"

# Or stop specific processes
# lsof -ti:3000 | xargs kill -9  # Frontend
# lsof -ti:5000 | xargs kill -9  # Backend
# lsof -ti:1337 | xargs kill -9  # Strapi

echo "Services stopped"
```

```bash
# Make executable
chmod +x ~/projects/smart-dairy/scripts/stop-dev.sh
```

---

## 10. RUNNING THE DEVELOPMENT ENVIRONMENT

### 10.1 Start All Services

**Option 1: Using start script**

```bash
~/projects/smart-dairy/scripts/start-dev.sh
```

**Option 2: Manual start (separate terminals)**

**Terminal 1 - Backend:**
```bash
cd ~/projects/smart-dairy/smart-dairy-backend
npm run dev
```

**Terminal 2 - Frontend:**
```bash
cd ~/projects/smart-dairy/smart-dairy-frontend
npm run dev
```

**Terminal 3 - Strapi CMS:**
```bash
cd ~/projects/smart-dairy/smart-dairy-cms
npm run develop
```

### 10.2 Verify Services

1. **Frontend:** Open browser to http://localhost:3000
2. **Backend API:** http://localhost:5000/api/v1/health (create health endpoint)
3. **Strapi:** http://localhost:1337/admin

### 10.3 Development Workflow

1. **Write code** in VS Code
2. **See changes** immediately (hot reload)
3. **Test APIs** with Postman/Insomnia
4. **Check database** with DBeaver
5. **Commit changes** frequently with Git

---

## 11. TROUBLESHOOTING

### 11.1 Common Issues

#### Port Already in Use

```bash
# Find process using port 3000 (or 5000, 1337)
lsof -i :3000

# Kill process
kill -9 <PID>
```

#### PostgreSQL Connection Error

```bash
# Check if PostgreSQL is running
sudo systemctl status postgresql

# Check logs
sudo journalctl -u postgresql -n 50

# Restart PostgreSQL
sudo systemctl restart postgresql
```

#### Node Modules Issues

```bash
# Clear npm cache
npm cache clean --force

# Remove node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

#### TypeScript Errors

```bash
# Rebuild TypeScript
npm run build

# Check TypeScript version
npx tsc --version
```

---

**END OF DEVELOPMENT ENVIRONMENT SETUP GUIDE**

Your Linux development environment is now ready for Smart Dairy website development!

**Next Steps:**
1. Review the Technical Architecture document
2. Set up the database schema (see Database Design document)
3. Begin Phase 1 development
4. Refer to API Specifications document for endpoint implementation

**Need Help?**
- Check official documentation for each tool
- Search Stack Overflow for specific errors
- Refer to project GitHub issues (if applicable)
