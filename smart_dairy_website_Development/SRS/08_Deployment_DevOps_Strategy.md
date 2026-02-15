# Smart Dairy Limited - Deployment & DevOps Strategy
## Production Deployment Guide for Solo Developer

**Version:** 1.0  
**Date:** December 2, 2025  
**Target Environment:** Linux Cloud Server

---

## TABLE OF CONTENTS

1. [Deployment Architecture](#1-deployment-architecture)
2. [Infrastructure Setup](#2-infrastructure-setup)
3. [CI/CD Pipeline](#3-cicd-pipeline)
4. [Deployment Process](#4-deployment-process)
5. [Environment Configuration](#5-environment-configuration)
6. [Monitoring & Logging](#6-monitoring--logging)
7. [Backup & Disaster Recovery](#7-backup--disaster-recovery)
8. [Performance Optimization](#8-performance-optimization)
9. [Security Hardening](#9-security-hardening)
10. [Maintenance Procedures](#10-maintenance-procedures)

---

## 1. DEPLOYMENT ARCHITECTURE

### 1.1 Production Architecture

```
Internet
   │
   ▼
┌─────────────────┐
│   Cloudflare    │ ◄── CDN, DDoS Protection, SSL
│   (CDN + WAF)   │
└────────┬────────┘
         │
         ▼
┌──────────────────────────────────────┐
│    DigitalOcean Droplet (VM)        │
│    Ubuntu 22.04 LTS                  │
│                                      │
│  ┌────────────────────────────────┐ │
│  │         Nginx                   │ │ ◄── Reverse Proxy
│  │  (Port 80/443)                  │ │
│  └──────┬──────────────────────────┘ │
│         │                            │
│    ┌────┴────┬──────────────────┐   │
│    │         │                   │   │
│    ▼         ▼                   ▼   │
│  ┌────┐  ┌────────┐         ┌──────┐│
│  │Next│  │Express │         │Static││
│  │.js │  │Backend │         │Assets││
│  │:3000  │:5000   │         │      ││
│  └────┘  └───┬────┘         └──────┘│
│              │                       │
│              ▼                       │
│     ┌─────────────────┐             │
│     │  PostgreSQL     │             │
│     │  (Port 5432)    │             │
│     └─────────────────┘             │
│              │                       │
│     ┌────────▼────────┐             │
│     │     Redis       │             │
│     │   (Port 6379)   │             │
│     └─────────────────┘             │
└──────────────────────────────────────┘
         │
         ▼
┌──────────────────┐
│  DigitalOcean    │ ◄── Object Storage
│     Spaces       │     (Images, Files)
└──────────────────┘
```

### 1.2 Deployment Models

**Option 1: Single Server (Recommended for Start)**
- All services on one droplet
- Cost-effective ($20-50/month)
- Easy to manage
- Suitable for < 10,000 concurrent users

**Option 2: Scaled Architecture (Future)**
- Separate application and database servers
- Load balancer
- Multiple app instances
- Higher cost but better performance

---

## 2. INFRASTRUCTURE SETUP

### 2.1 DigitalOcean Droplet Setup

**Recommended Droplet:**
- **Plan:** Basic Droplet
- **Size:** 2 vCPUs, 4GB RAM, 80GB SSD ($24/month)
- **Region:** Singapore (closest to Bangladesh)
- **OS:** Ubuntu 22.04 LTS
- **Additional:** Backups enabled (+20% cost)

**Initial Server Setup:**

```bash
# 1. Update system
sudo apt update && sudo apt upgrade -y

# 2. Create deployment user
sudo adduser deployer
sudo usermod -aG sudo deployer

# 3. Setup SSH key authentication
mkdir -p /home/deployer/.ssh
chmod 700 /home/deployer/.ssh
# Copy your public key to authorized_keys

# 4. Disable root SSH login
sudo nano /etc/ssh/sshd_config
# Set: PermitRootLogin no
# Set: PasswordAuthentication no
sudo systemctl restart sshd

# 5. Setup firewall
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable

# 6. Install fail2ban (brute force protection)
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
```

### 2.2 Install Required Software

```bash
# 1. Install Node.js 20 LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node --version  # Should be v20.x.x
npm --version

# 2. Install PostgreSQL 15
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sudo apt update
sudo apt install postgresql-15 postgresql-contrib-15 -y

# 3. Install Redis
sudo apt install redis-server -y

# Configure Redis
sudo nano /etc/redis/redis.conf
# Set: supervised systemd
# Set: maxmemory 256mb
# Set: maxmemory-policy allkeys-lru

sudo systemctl restart redis-server

# 4. Install Nginx
sudo apt install nginx -y

# 5. Install PM2 (Process Manager for Node.js)
sudo npm install -g pm2

# 6. Install Docker (optional, for containers)
sudo apt install docker.io docker-compose -y
sudo usermod -aG docker deployer
```

### 2.3 PostgreSQL Setup

```bash
# Switch to postgres user
sudo -u postgres psql

# Create database and user
CREATE DATABASE smartdairy_prod;
CREATE USER smartdairy WITH PASSWORD 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON DATABASE smartdairy_prod TO smartdairy;

# Enable required extensions
\c smartdairy_prod
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

# Exit
\q

# Configure PostgreSQL for remote access (if needed)
sudo nano /etc/postgresql/15/main/postgresql.conf
# Uncomment: listen_addresses = 'localhost'

sudo nano /etc/postgresql/15/main/pg_hba.conf
# Add: host smartdairy_prod smartdairy 127.0.0.1/32 md5

sudo systemctl restart postgresql
```

### 2.4 Nginx Configuration

```nginx
# /etc/nginx/sites-available/smartdairy

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name smartdairy.com www.smartdairy.com;
    
    return 301 https://$server_name$request_uri;
}

# HTTPS Server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name smartdairy.com www.smartdairy.com;

    # SSL Configuration (Cloudflare or Let's Encrypt)
    ssl_certificate /etc/ssl/certs/smartdairy.crt;
    ssl_certificate_key /etc/ssl/private/smartdairy.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Logging
    access_log /var/log/nginx/smartdairy_access.log;
    error_log /var/log/nginx/smartdairy_error.log;

    # Client body size (for file uploads)
    client_max_body_size 10M;

    # Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss;

    # Frontend (Next.js)
    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Backend API
    location /api {
        proxy_pass http://localhost:5000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Static files (served by Nginx directly)
    location /uploads {
        alias /var/www/smartdairy/uploads;
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Enable Site:**
```bash
sudo ln -s /etc/nginx/sites-available/smartdairy /etc/nginx/sites-enabled/
sudo nginx -t  # Test configuration
sudo systemctl restart nginx
```

### 2.5 SSL Certificate Setup

**Option 1: Cloudflare SSL (Recommended)**
- Free, automatic
- DDoS protection included
- Setup: Point DNS to Cloudflare, enable SSL/TLS

**Option 2: Let's Encrypt**
```bash
sudo apt install certbot python3-certbot-nginx -y

# Obtain certificate
sudo certbot --nginx -d smartdairy.com -d www.smartdairy.com

# Auto-renewal (certbot creates cron job automatically)
sudo certbot renew --dry-run
```

### 2.6 DigitalOcean Spaces Setup

```bash
# Install AWS CLI (for S3-compatible API)
sudo apt install awscli -y

# Configure credentials
aws configure
# Access Key: [Your Spaces Key]
# Secret Key: [Your Spaces Secret]
# Region: sgp1 (Singapore)

# Test upload
aws s3 cp test.jpg s3://smartdairy-media/test.jpg --endpoint-url=https://sgp1.digitaloceanspaces.com
```

---

## 3. CI/CD PIPELINE

### 3.1 GitHub Actions Workflow

```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      postgres:
        image: postgres:15
        env:
          POSTGRES_PASSWORD: postgres
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
      
      redis:
        image: redis:7
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '20'
          cache: 'npm'
      
      - name: Install dependencies
        run: |
          npm ci
          cd frontend && npm ci && cd ..
          cd backend && npm ci && cd ..
      
      - name: Run linter
        run: npm run lint
      
      - name: Run tests
        run: npm test -- --coverage
        env:
          DATABASE_URL: postgresql://postgres:postgres@localhost:5432/test
          REDIS_URL: redis://localhost:6379
      
      - name: Build frontend
        run: cd frontend && npm run build
      
      - name: Build backend
        run: cd backend && npm run build

  deploy:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Deploy to production
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.PROD_HOST }}
          username: ${{ secrets.PROD_USER }}
          key: ${{ secrets.PROD_SSH_KEY }}
          script: |
            cd /var/www/smartdairy
            git pull origin main
            npm install --production
            cd frontend && npm install --production && npm run build && cd ..
            cd backend && npm install --production && npm run build && cd ..
            pm2 restart ecosystem.config.js
            pm2 save
```

### 3.2 PM2 Ecosystem Configuration

```javascript
// ecosystem.config.js
module.exports = {
  apps: [
    {
      name: 'smartdairy-frontend',
      script: 'npm',
      args: 'start',
      cwd: '/var/www/smartdairy/frontend',
      instances: 2,
      exec_mode: 'cluster',
      env: {
        NODE_ENV: 'production',
        PORT: 3000
      },
      error_file: '/var/log/pm2/smartdairy-frontend-error.log',
      out_file: '/var/log/pm2/smartdairy-frontend-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z'
    },
    {
      name: 'smartdairy-backend',
      script: 'npm',
      args: 'start',
      cwd: '/var/www/smartdairy/backend',
      instances: 2,
      exec_mode: 'cluster',
      env: {
        NODE_ENV: 'production',
        PORT: 5000
      },
      error_file: '/var/log/pm2/smartdairy-backend-error.log',
      out_file: '/var/log/pm2/smartdairy-backend-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z'
    }
  ]
};
```

**PM2 Commands:**
```bash
# Start applications
pm2 start ecosystem.config.js

# Monitor
pm2 monit

# View logs
pm2 logs

# Restart
pm2 restart all

# Stop
pm2 stop all

# Save configuration
pm2 save

# Setup startup script
pm2 startup
sudo env PATH=$PATH:/usr/bin pm2 startup systemd -u deployer --hp /home/deployer
```

---

## 4. DEPLOYMENT PROCESS

### 4.1 Initial Deployment

```bash
# 1. SSH into server
ssh deployer@your-server-ip

# 2. Create application directory
sudo mkdir -p /var/www/smartdairy
sudo chown deployer:deployer /var/www/smartdairy

# 3. Clone repository
cd /var/www
git clone https://github.com/yourorg/smartdairy.git smartdairy
cd smartdairy

# 4. Create .env files
nano backend/.env.production
# Add all environment variables

nano frontend/.env.production
# Add all environment variables

# 5. Install dependencies
npm install --production
cd frontend && npm install --production && cd ..
cd backend && npm install --production && cd ..

# 6. Build applications
cd frontend && npm run build && cd ..
cd backend && npm run build && cd ..

# 7. Run database migrations
cd backend
npx prisma migrate deploy
npx prisma generate
cd ..

# 8. Start with PM2
pm2 start ecosystem.config.js
pm2 save
pm2 startup

# 9. Configure Nginx
sudo cp deployment/nginx.conf /etc/nginx/sites-available/smartdairy
sudo ln -s /etc/nginx/sites-available/smartdairy /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

# 10. Test deployment
curl http://localhost:3000
curl http://localhost:5000/api/v1/health
```

### 4.2 Subsequent Deployments

```bash
# Automated via GitHub Actions or manual:

cd /var/www/smartdairy

# 1. Pull latest code
git pull origin main

# 2. Install dependencies
npm install --production
cd frontend && npm install --production && cd ..
cd backend && npm install --production && cd ..

# 3. Build
cd frontend && npm run build && cd ..
cd backend && npm run build && cd ..

# 4. Run migrations
cd backend && npx prisma migrate deploy && cd ..

# 5. Restart PM2
pm2 restart ecosystem.config.js

# 6. Verify
pm2 status
curl https://smartdairy.com/api/v1/health
```

### 4.3 Rollback Procedure

```bash
# 1. Check Git history
git log --oneline -10

# 2. Rollback to previous commit
git reset --hard <previous-commit-hash>

# 3. Rebuild and restart
cd frontend && npm run build && cd ..
cd backend && npm run build && cd ..
pm2 restart all

# 4. Verify
pm2 logs
curl https://smartdairy.com
```

---

## 5. ENVIRONMENT CONFIGURATION

### 5.1 Backend Environment Variables

```bash
# backend/.env.production

# Application
NODE_ENV=production
PORT=5000
APP_URL=https://smartdairy.com
FRONTEND_URL=https://smartdairy.com

# Database
DATABASE_URL=postgresql://smartdairy:PASSWORD@localhost:5432/smartdairy_prod

# Redis
REDIS_URL=redis://localhost:6379

# JWT Secrets
JWT_SECRET=RANDOM_64_CHAR_STRING
JWT_REFRESH_SECRET=ANOTHER_RANDOM_64_CHAR_STRING
JWT_EXPIRES_IN=1h
JWT_REFRESH_EXPIRES_IN=7d

# Session
SESSION_SECRET=RANDOM_64_CHAR_STRING

# Email (SendGrid)
SENDGRID_API_KEY=SG.xxxxx
EMAIL_FROM=noreply@smartdairy.com

# SMS (Bangladesh SMS Gateway)
SMS_API_KEY=xxxxx
SMS_API_URL=https://sms-gateway.com/api

# Payment Gateways
SSL_COMMERZ_STORE_ID=xxxxx
SSL_COMMERZ_STORE_PASSWORD=xxxxx
SSL_COMMERZ_SANDBOX=false

BKASH_APP_KEY=xxxxx
BKASH_APP_SECRET=xxxxx
BKASH_USERNAME=xxxxx
BKASH_PASSWORD=xxxxx
BKASH_SANDBOX=false

# Storage (DigitalOcean Spaces)
SPACES_KEY=xxxxx
SPACES_SECRET=xxxxx
SPACES_ENDPOINT=https://sgp1.digitaloceanspaces.com
SPACES_BUCKET=smartdairy-media
SPACES_REGION=sgp1

# Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX

# Security
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100
```

### 5.2 Frontend Environment Variables

```bash
# frontend/.env.production

# API
NEXT_PUBLIC_API_URL=https://smartdairy.com/api/v1

# Site
NEXT_PUBLIC_SITE_URL=https://smartdairy.com
NEXT_PUBLIC_SITE_NAME=Smart Dairy Limited

# Google Maps
NEXT_PUBLIC_GOOGLE_MAPS_API_KEY=xxxxx

# Analytics
NEXT_PUBLIC_GA_ID=G-XXXXXXXXXX
NEXT_PUBLIC_FB_PIXEL_ID=xxxxx

# Payment (Public Keys)
NEXT_PUBLIC_BKASH_SCRIPT_URL=https://scripts.pay.bka.sh/...

# Feature Flags
NEXT_PUBLIC_ENABLE_B2B=true
NEXT_PUBLIC_ENABLE_SUBSCRIPTION=false
```

---

## 6. MONITORING & LOGGING

### 6.1 Application Monitoring

**PM2 Monitoring:**
```bash
# Real-time monitoring
pm2 monit

# Application status
pm2 status

# View logs
pm2 logs
pm2 logs smartdairy-backend --lines 100

# CPU and memory stats
pm2 describe smartdairy-backend
```

**PM2 Plus (Optional - Advanced Monitoring):**
```bash
pm2 plus
# Follow prompts to connect to PM2 Plus dashboard
```

### 6.2 Server Monitoring

**Install monitoring tools:**
```bash
# htop - Interactive process viewer
sudo apt install htop -y

# netdata - Real-time performance monitoring
bash <(curl -Ss https://my-netdata.io/kickstart.sh)

# Access netdata at: http://your-server-ip:19999
```

**Basic monitoring commands:**
```bash
# Disk space
df -h

# Memory usage
free -h

# CPU usage
top

# Network connections
netstat -tulpn

# System load
uptime

# Disk I/O
iostat

# Process list
ps aux
```

### 6.3 Log Management

**Log Locations:**
```
/var/log/nginx/smartdairy_access.log
/var/log/nginx/smartdairy_error.log
/var/log/pm2/smartdairy-frontend-out.log
/var/log/pm2/smartdairy-frontend-error.log
/var/log/pm2/smartdairy-backend-out.log
/var/log/pm2/smartdairy-backend-error.log
/var/log/postgresql/postgresql-15-main.log
```

**Log Rotation:**
```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/smartdairy

# Configuration:
/var/log/pm2/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 deployer deployer
    sharedscripts
    postrotate
        pm2 reloadLogs
    endscript
}

# Test
sudo logrotate -f /etc/logrotate.d/smartdairy
```

### 6.4 Error Tracking with Sentry (Optional)

```bash
# Install Sentry SDK
npm install @sentry/node @sentry/nextjs

# Configure in backend
# src/config/sentry.js
const Sentry = require('@sentry/node');

Sentry.init({
  dsn: process.env.SENTRY_DSN,
  environment: process.env.NODE_ENV,
  tracesSampleRate: 0.1,
});

# Configure in frontend
# next.config.js with @sentry/nextjs
```

---

## 7. BACKUP & DISASTER RECOVERY

### 7.1 Database Backups

**Automated Daily Backup Script:**

```bash
#!/bin/bash
# /home/deployer/scripts/backup-db.sh

BACKUP_DIR="/home/deployer/backups/postgresql"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
DATABASE="smartdairy_prod"
BACKUP_FILE="$BACKUP_DIR/${DATABASE}_${TIMESTAMP}.sql.gz"

# Create backup directory if not exists
mkdir -p $BACKUP_DIR

# Backup database
pg_dump $DATABASE | gzip > $BACKUP_FILE

# Keep only last 30 days of backups
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete

# Upload to DigitalOcean Spaces (optional)
aws s3 cp $BACKUP_FILE s3://smartdairy-backups/ --endpoint-url=https://sgp1.digitaloceanspaces.com

echo "Backup completed: $BACKUP_FILE"
```

**Make executable and schedule:**
```bash
chmod +x /home/deployer/scripts/backup-db.sh

# Add to crontab
crontab -e

# Run daily at 2 AM
0 2 * * * /home/deployer/scripts/backup-db.sh >> /home/deployer/logs/backup.log 2>&1
```

### 7.2 File System Backups

```bash
#!/bin/bash
# /home/deployer/scripts/backup-files.sh

BACKUP_DIR="/home/deployer/backups/files"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
SOURCE_DIR="/var/www/smartdairy/uploads"
BACKUP_FILE="$BACKUP_DIR/uploads_${TIMESTAMP}.tar.gz"

mkdir -p $BACKUP_DIR

# Backup uploads
tar -czf $BACKUP_FILE $SOURCE_DIR

# Keep only last 7 days
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

# Upload to Spaces
aws s3 cp $BACKUP_FILE s3://smartdairy-backups/ --endpoint-url=https://sgp1.digitaloceanspaces.com

echo "File backup completed: $BACKUP_FILE"
```

### 7.3 Restore Procedures

**Database Restore:**
```bash
# Restore from local backup
gunzip -c /home/deployer/backups/postgresql/smartdairy_prod_20251202_020000.sql.gz | psql smartdairy_prod

# Restore from Spaces
aws s3 cp s3://smartdairy-backups/smartdairy_prod_20251202_020000.sql.gz . --endpoint-url=https://sgp1.digitaloceanspaces.com
gunzip -c smartdairy_prod_20251202_020000.sql.gz | psql smartdairy_prod
```

**File Restore:**
```bash
# Restore uploads
tar -xzf /home/deployer/backups/files/uploads_20251202_020000.tar.gz -C /
```

### 7.4 DigitalOcean Droplet Backups

- Enable automated backups in DigitalOcean dashboard (+20% cost)
- Backups run weekly
- Keep 4 most recent backups
- Can restore entire droplet from backup

---

## 8. PERFORMANCE OPTIMIZATION

### 8.1 Database Optimization

```sql
-- Create indexes for frequently queried fields
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_product ON order_items(product_id);

-- Analyze query performance
EXPLAIN ANALYZE SELECT * FROM products WHERE category_id = 'cat_001';

-- Vacuum and analyze regularly
VACUUM ANALYZE;
```

**Automated maintenance:**
```bash
# Add to crontab
# Weekly vacuum
0 3 * * 0 psql smartdairy_prod -c "VACUUM ANALYZE;"
```

### 8.2 Redis Caching Strategy

```javascript
// Backend caching example
const redis = require('redis');
const client = redis.createClient({ url: process.env.REDIS_URL });

async function getProducts(filters) {
  const cacheKey = `products:${JSON.stringify(filters)}`;
  
  // Try cache first
  const cached = await client.get(cacheKey);
  if (cached) return JSON.parse(cached);
  
  // Query database
  const products = await prisma.product.findMany({ where: filters });
  
  // Cache for 5 minutes
  await client.setEx(cacheKey, 300, JSON.stringify(products));
  
  return products;
}
```

### 8.3 CDN Configuration

**Cloudflare Caching Rules:**
- Cache static assets (images, CSS, JS): 1 month
- Cache product pages: 1 hour
- Bypass cache for: cart, checkout, admin
- Page Rules: Always Use HTTPS
- Auto-minify: JavaScript, CSS, HTML

### 8.4 Image Optimization

```bash
# Install sharp for image processing
npm install sharp

# Automatic optimization on upload
const sharp = require('sharp');

async function optimizeImage(inputPath, outputPath) {
  await sharp(inputPath)
    .resize(1200, 1200, { fit: 'inside', withoutEnlargement: true })
    .jpeg({ quality: 85 })
    .toFile(outputPath);
  
  // Generate thumbnail
  await sharp(inputPath)
    .resize(400, 400, { fit: 'cover' })
    .jpeg({ quality: 80 })
    .toFile(outputPath.replace('.jpg', '-thumb.jpg'));
}
```

---

## 9. SECURITY HARDENING

### 9.1 Server Security Checklist

- [ ] SSH key-only authentication (no passwords)
- [ ] Disable root login
- [ ] Firewall (UFW) configured
- [ ] Fail2ban installed and configured
- [ ] Automatic security updates enabled
- [ ] Strong passwords for all services
- [ ] Non-root user for application
- [ ] Secure file permissions (chmod 644 for files, 755 for directories)
- [ ] HTTPS enforced
- [ ] Security headers in Nginx
- [ ] Regular security audits (npm audit, OWASP ZAP)

### 9.2 Application Security

```javascript
// Backend security middleware
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');
const mongoSanitize = require('express-mongo-sanitize');

app.use(helmet()); // Security headers
app.use(mongoSanitize()); // Prevent NoSQL injection
app.use(rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100 // limit each IP to 100 requests per windowMs
}));
```

### 9.3 SSL/TLS Configuration

**Nginx SSL Best Practices:**
```nginx
# Use modern TLS protocols only
ssl_protocols TLSv1.2 TLSv1.3;

# Strong ciphers
ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;

# Prefer server ciphers
ssl_prefer_server_ciphers off;

# HSTS
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

# OCSP Stapling
ssl_stapling on;
ssl_stapling_verify on;
```

**Test SSL:**
```bash
# Test SSL configuration
openssl s_client -connect smartdairy.com:443

# Check certificate
curl -vI https://smartdairy.com
```

---

## 10. MAINTENANCE PROCEDURES

### 10.1 Regular Maintenance Schedule

**Daily:**
- Monitor application logs for errors
- Check PM2 status
- Review server resources (CPU, memory, disk)

**Weekly:**
- Review backup success
- Database vacuum and analyze
- Check for security updates
- Review error rate and performance metrics

**Monthly:**
- Update Node.js packages (npm update)
- Review and rotate logs
- Database optimization (REINDEX if needed)
- Security audit (npm audit)
- Review Cloudflare analytics

**Quarterly:**
- Update system packages (apt upgrade)
- Review and update dependencies
- Full security scan
- Disaster recovery test
- Performance audit

### 10.2 Update Procedures

**Node.js Packages:**
```bash
# Check for updates
npm outdated

# Update dependencies
npm update

# Test
npm test

# Deploy
git add package-lock.json
git commit -m "Update dependencies"
git push
```

**System Packages:**
```bash
# Update package list
sudo apt update

# Upgrade packages
sudo apt upgrade -y

# Reboot if kernel updated
sudo reboot
```

### 10.3 Troubleshooting Guide

**Application Not Responding:**
```bash
# Check PM2 status
pm2 status

# View logs
pm2 logs

# Restart application
pm2 restart all

# Check Nginx
sudo systemctl status nginx
sudo nginx -t
```

**High CPU Usage:**
```bash
# Check processes
top
htop

# Check PM2 apps
pm2 monit

# Restart if needed
pm2 restart all
```

**Database Connection Issues:**
```bash
# Check PostgreSQL status
sudo systemctl status postgresql

# Check connections
sudo -u postgres psql -c "SELECT count(*) FROM pg_stat_activity;"

# Restart if needed
sudo systemctl restart postgresql
```

**Out of Disk Space:**
```bash
# Check disk usage
df -h

# Find large files
sudo du -h --max-depth=1 /var/log | sort -hr

# Clean logs
sudo journalctl --vacuum-time=7d

# Clean old backups
find /home/deployer/backups -mtime +30 -delete
```

---

## DEPLOYMENT CHECKLIST

### Pre-Deployment
- [ ] All tests passing
- [ ] Code reviewed
- [ ] Environment variables configured
- [ ] Database migrations ready
- [ ] Backup taken
- [ ] Monitoring configured
- [ ] Rollback plan ready

### During Deployment
- [ ] Inform stakeholders
- [ ] Pull latest code
- [ ] Install dependencies
- [ ] Run migrations
- [ ] Build applications
- [ ] Restart services
- [ ] Verify health checks
- [ ] Monitor logs

### Post-Deployment
- [ ] Smoke tests passed
- [ ] Critical user flows verified
- [ ] Performance acceptable
- [ ] Error rate normal
- [ ] Backups successful
- [ ] Documentation updated

---

**Document Status:** Final  
**Last Updated:** December 2, 2025  
**Next Review:** After Phase 1 Deployment

---

*This deployment guide provides a complete DevOps strategy for solo development. Adjust configurations based on actual server specifications and requirements.*
