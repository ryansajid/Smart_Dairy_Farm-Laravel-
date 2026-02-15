# Docker Configurations for Linux Deployment
## Smart Dairy Project - Containerized Development and Production

**Document Version:** 1.0  
**Date:** December 2, 2025  
**Purpose:** Complete Docker configuration for Linux-based development and deployment

---

## 1. DEVELOPMENT ENVIRONMENT

### 1.1 Docker Compose for Development
```yaml
# docker-compose.dev.yml - Development environment
version: '3.8'

services:
  # Frontend (Next.js)
  frontend:
    build:
      context: ./smart-dairy-frontend
      dockerfile: Dockerfile.dev
    image: smart-dairy-frontend:dev
    container_name: smart-dairy-frontend
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
      - NEXT_PUBLIC_CMS_URL=http://localhost:1337/api
      - CHOKIDAR_USEPOLLING=true
    depends_on:
      - backend
      - postgres
      - redis
    networks:
      - smartdairy-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:3000"]
      interval: 30s
      timeout: 10s
      retries: 3

  # Backend (Express.js)
  backend:
    build:
      context: ./smart-dairy-backend
      dockerfile: Dockerfile.dev
    image: smart-dairy-backend:dev
    container_name: smart-dairy-backend
    ports:
      - "5000:5000"
      - "9229:9229"  # Debug port
    volumes:
      - ./smart-dairy-backend:/app
      - /app/node_modules
      - uploads:/app/uploads
      - ./logs/backend:/var/log/smartdairy
    environment:
      - NODE_ENV=development
      - PORT=5000
      - DB_HOST=postgres
      - DB_PORT=5432
      - DB_NAME=smart_dairy_dev
      - DB_USER=smart_dairy_user
      - DB_PASSWORD=password
      - REDIS_HOST=redis
      - REDIS_PORT=6379
      - JWT_SECRET=dev_jwt_secret_change_in_production
      - LOG_LEVEL=debug
    depends_on:
      - postgres
      - redis
    networks:
      - smartdairy-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:5000/api/health"]
      interval: 30s
      timeout: 10s
      retries: 3

  # CMS (Strapi)
  cms:
    build:
      context: ./smart-dairy-cms
      dockerfile: Dockerfile.dev
    image: smart-dairy-cms:dev
    container_name: smart-dairy-cms
    ports:
      - "1337:1337"
    volumes:
      - ./smart-dairy-cms:/app
      - /app/node_modules
      - cms-uploads:/app/public/uploads
      - ./logs/cms:/var/log/smartdairy
    environment:
      - NODE_ENV=development
      - DATABASE_CLIENT=postgres
      - DATABASE_HOST=postgres
      - DATABASE_PORT=5432
      - DATABASE_NAME=smart_dairy_cms
      - DATABASE_USERNAME=smart_dairy_user
      - DATABASE_PASSWORD=password
      - APP_KEYS=app_key_1,app_key_2
      - API_TOKEN_SALT=token_salt
      - ADMIN_JWT_SECRET=admin_jwt_secret
      - JWT_SECRET=jwt_secret
      - TRANSFER_TOKEN_SALT=transfer_salt
    depends_on:
      - postgres
    networks:
      - smartdairy-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:1337/admin"]
      interval: 30s
      timeout: 10s
      retries: 3

  # PostgreSQL
  postgres:
    image: postgres:15-alpine
    container_name: smart-dairy-postgres
    ports:
      - "5432:5432"
    volumes:
      - postgres-data:/var/lib/postgresql/data
      - ./scripts/init-db.sql:/docker-entrypoint-initdb.d/init-db.sql
      - ./logs/postgres:/var/log/postgresql
    environment:
      - POSTGRES_DB=smart_dairy_dev
      - POSTGRES_USER=smart_dairy_user
      - POSTGRES_PASSWORD=password
      - POSTGRES_INITDB_ARGS=--encoding=UTF-8
      - PGDATA=/var/lib/postgresql/data/pgdata
    networks:
      - smartdairy-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD-SHELL", "pg_isready", "-h", "localhost", "-p", "5432", "-U", "smart_dairy_user"]
      interval: 10s
      timeout: 5s
      retries: 5

  # Redis
  redis:
    image: redis:7-alpine
    container_name: smart-dairy-redis
    ports:
      - "6379:6379"
    volumes:
      - redis-data:/data
      - ./config/redis.conf:/usr/local/etc/redis/redis.conf
      - ./logs/redis:/var/log/redis
    command: redis-server /usr/local/etc/redis/redis.conf
    networks:
      - smartdairy-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 3s
      retries: 3

  # Nginx (reverse proxy for development)
  nginx:
    image: nginx:alpine
    container_name: smart-dairy-nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./config/nginx/dev.conf:/etc/nginx/nginx.conf:ro
      - ./ssl:/etc/nginx/ssl:ro
      - ./logs/nginx:/var/log/nginx
    depends_on:
      - frontend
      - backend
    networks:
      - smartdairy-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/health"]
      interval: 30s
      timeout: 10s
      retries: 3

volumes:
  postgres-data:
    driver: local
  redis-data:
    driver: local
  cms-uploads:
    driver: local
  uploads:
    driver: local

networks:
  smartdairy-network:
    driver: bridge
    ipam:
      config:
        - subnet: 172.20.0.0/16
```

### 1.2 Development Dockerfiles

#### Frontend Dockerfile.dev
```dockerfile
# smart-dairy-frontend/Dockerfile.dev
FROM node:20-alpine AS base

# Install dependencies only when needed
FROM base AS deps
RUN apk add --no-cache libc6-compat curl
WORKDIR /app

# Install dependencies
COPY package.json package-lock.json* ./
RUN npm ci

# Rebuild the source code only when needed
FROM base AS builder
WORKDIR /app

COPY --from=deps /app/node_modules ./node_modules
COPY . .

# Enable Linux-specific optimizations
ENV NODE_OPTIONS="--max-old-space-size=4096"
ENV WATCHPACK_POLLING=true
ENV NODE_ENV=development

# Expose port
EXPOSE 3000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:3000 || exit 1

# Start the app
CMD ["npm", "run", "dev"]
```

#### Backend Dockerfile.dev
```dockerfile
# smart-dairy-backend/Dockerfile.dev
FROM node:20-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    bash \
    postgresql-client

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source code
COPY . .

# Create necessary directories
RUN mkdir -p logs uploads

# Linux-specific optimizations
ENV NODE_OPTIONS="--max-old-space-size=4096"
ENV NODE_ENV=development

# Expose port
EXPOSE 5000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:5000/api/health || exit 1

# Start the app
CMD ["npm", "run", "dev"]
```

#### CMS Dockerfile.dev
```dockerfile
# smart-dairy-cms/Dockerfile.dev
FROM node:20-alpine

# Install dependencies
RUN apk add --no-cache \
    curl \
    python3 \
    make \
    g++

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source code
COPY . .

# Create necessary directories
RUN mkdir -p public/uploads logs

# Environment variables
ENV NODE_ENV=development
ENV APP_KEYS=app_key_1,app_key_2
ENV API_TOKEN_SALT=token_salt
ENV ADMIN_JWT_SECRET=admin_jwt_secret
ENV JWT_SECRET=jwt_secret
ENV TRANSFER_TOKEN_SALT=transfer_salt

# Expose port
EXPOSE 1337

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:1337/admin || exit 1

# Start the app
CMD ["npm", "run", "develop"]
```

---

## 2. PRODUCTION ENVIRONMENT

### 2.1 Docker Compose for Production
```yaml
# docker-compose.prod.yml - Production environment
version: '3.8'

services:
  # Frontend (Next.js)
  frontend:
    build:
      context: ./smart-dairy-frontend
      dockerfile: Dockerfile.prod
    image: smart-dairy-frontend:latest
    container_name: smart-dairy-frontend-prod
    restart: always
    environment:
      - NODE_ENV=production
      - PORT=3000
    networks:
      - smartdairy-network
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
        reservations:
          cpus: '0.25'
          memory: 256M
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:3000"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

  # Backend (Express.js)
  backend:
    build:
      context: ./smart-dairy-backend
      dockerfile: Dockerfile.prod
    image: smart-dairy-backend:latest
    container_name: smart-dairy-backend-prod
    restart: always
    environment:
      - NODE_ENV=production
      - PORT=5000
      - DB_HOST=postgres
      - DB_PORT=5432
      - DB_NAME=smart_dairy_prod
      - DB_USER=${DB_USER}
      - DB_PASSWORD=${DB_PASSWORD}
      - REDIS_HOST=redis
      - REDIS_PORT=6379
      - JWT_SECRET=${JWT_SECRET}
    volumes:
      - uploads:/app/uploads
      - ./logs/backend:/var/log/smartdairy
    networks:
      - smartdairy-network
    depends_on:
      - postgres
      - redis
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '1.0'
          memory: 1G
        reservations:
          cpus: '0.5'
          memory: 512M
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:5000/api/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

  # CMS (Strapi)
  cms:
    build:
      context: ./smart-dairy-cms
      dockerfile: Dockerfile.prod
    image: smart-dairy-cms:latest
    container_name: smart-dairy-cms-prod
    restart: always
    environment:
      - NODE_ENV=production
      - DATABASE_CLIENT=postgres
      - DATABASE_HOST=postgres
      - DATABASE_PORT=5432
      - DATABASE_NAME=smart_dairy_cms
      - DATABASE_USERNAME=${CMS_DB_USER}
      - DATABASE_PASSWORD=${CMS_DB_PASSWORD}
      - APP_KEYS=${APP_KEYS}
      - API_TOKEN_SALT=${API_TOKEN_SALT}
      - ADMIN_JWT_SECRET=${ADMIN_JWT_SECRET}
      - JWT_SECRET=${JWT_SECRET}
      - TRANSFER_TOKEN_SALT=${TRANSFER_TOKEN_SALT}
    volumes:
      - cms-uploads:/app/public/uploads
      - ./logs/cms:/var/log/smartdairy
    networks:
      - smartdairy-network
    depends_on:
      - postgres
    deploy:
      replicas: 1
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
        reservations:
          cpus: '0.25'
          memory: 256M
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:1337/admin"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

  # PostgreSQL
  postgres:
    image: postgres:15-alpine
    container_name: smart-dairy-postgres-prod
    restart: always
    volumes:
      - postgres-data:/var/lib/postgresql/data
      - ./backups/postgres:/backups
      - ./logs/postgres:/var/log/postgresql
    environment:
      - POSTGRES_DB=${DB_NAME}
      - POSTGRES_USER=${DB_USER}
      - POSTGRES_PASSWORD=${DB_PASSWORD}
      - POSTGRES_INITDB_ARGS=--encoding=UTF-8
      - PGDATA=/var/lib/postgresql/data/pgdata
    networks:
      - smartdairy-network
    deploy:
      resources:
        limits:
          cpus: '2.0'
          memory: 2G
        reservations:
          cpus: '1.0'
          memory: 1G
    healthcheck:
      test: ["CMD-SHELL", "pg_isready", "-h", "localhost", "-p", "5432", "-U", "${DB_USER}"]
      interval: 10s
      timeout: 5s
      retries: 5

  # Redis
  redis:
    image: redis:7-alpine
    container_name: smart-dairy-redis-prod
    restart: always
    volumes:
      - redis-data:/data
      - ./config/redis.prod.conf:/usr/local/etc/redis/redis.conf
      - ./logs/redis:/var/log/redis
    command: redis-server /usr/local/etc/redis/redis.conf
    networks:
      - smartdairy-network
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
        reservations:
          cpus: '0.25'
          memory: 256M
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 3s
      retries: 3

  # Nginx (reverse proxy)
  nginx:
    image: nginx:alpine
    container_name: smart-dairy-nginx-prod
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./config/nginx/prod.conf:/etc/nginx/nginx.conf:ro
      - ./ssl:/etc/nginx/ssl:ro
      - ./logs/nginx:/var/log/nginx
      - static-content:/var/www/static:ro
    depends_on:
      - frontend
      - backend
    networks:
      - smartdairy-network
    restart: always
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 256M
        reservations:
          cpus: '0.25'
          memory: 128M
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/health"]
      interval: 30s
      timeout: 10s
      retries: 3

volumes:
  postgres-data:
    driver: local
  redis-data:
    driver: local
  cms-uploads:
    driver: local
  uploads:
    driver: local
  static-content:
    driver: local

networks:
  smartdairy-network:
    driver: bridge
    ipam:
      config:
        - subnet: 172.21.0.0/16
```

### 2.2 Production Dockerfiles

#### Frontend Dockerfile.prod
```dockerfile
# smart-dairy-frontend/Dockerfile.prod
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
ENV NEXT_TELEMETRY_DISABLED 1

RUN addgroup --system --gid 1001 nodejs
RUN adduser --system --uid 1001 nextjs

# Copy built application
COPY --from=builder /app/public ./public
COPY --from=builder --chown=nextjs:nodejs /app/.next/standalone ./
COPY --from=builder --chown=nextjs:nodejs /app/.next/static ./.next/static

# Set the correct permission for prerender cache
RUN mkdir .next
RUN chown nextjs:nodejs .next

USER nextjs

EXPOSE 3000

ENV PORT 3000
ENV HOSTNAME "0.0.0.0"

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:3000 || exit 1

CMD ["node", "server.js"]
```

#### Backend Dockerfile.prod
```dockerfile
# smart-dairy-backend/Dockerfile.prod
FROM node:20-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    dumb-init \
    postgresql-client

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production && npm cache clean --force

# Copy source code
COPY --chown=node:node . .

# Create necessary directories
RUN mkdir -p logs uploads

# Switch to non-root user
RUN addgroup -g node && adduser -g -s -G node node

USER node

# Linux-specific optimizations
ENV NODE_OPTIONS="--max-old-space-size=1024"
ENV NODE_ENV=production

# Expose port
EXPOSE 5000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:5000/api/health || exit 1

# Use dumb-init to properly handle signals
ENTRYPOINT ["dumb-init", "--"]
CMD ["node", "dist/server.js"]
```

---

## 3. DEPLOYMENT SCRIPTS

### 3.1 Docker Development Script
```bash
#!/bin/bash
# scripts/docker-dev.sh - Docker development environment

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}🐳 Starting Smart Dairy Docker Development${NC}"
echo "========================================"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running${NC}"
    echo -e "${BLUE}Please start Docker and try again${NC}"
    exit 1
fi

# Stop existing containers
echo -e "${BLUE}🔄 Stopping existing containers...${NC}"
docker-compose -f docker-compose.dev.yml down --remove-orphans

# Build and start containers
echo -e "${BLUE}🔨 Building and starting containers...${NC}"
docker-compose -f docker-compose.dev.yml up --build -d

# Wait for services to be ready
echo -e "${BLUE}⏳ Waiting for services to be ready...${NC}"

# Function to check service health
wait_for_service() {
    local service_name=$1
    local health_url=$2
    local max_attempts=30
    local attempt=1
    
    echo -e "${BLUE}Waiting for $service_name...${NC}"
    
    while [ $attempt -le $max_attempts ]; do
        if curl -f -s "$health_url" > /dev/null 2>&1; then
            echo -e "${GREEN}✅ $service_name is ready!${NC}"
            return 0
        fi
        
        echo -n "."
        sleep 2
        ((attempt++))
    done
    
    echo -e "${RED}❌ $service_name failed to start${NC}"
    return 1
}

# Wait for all services
wait_for_service "Frontend" "http://localhost:3000"
wait_for_service "Backend" "http://localhost:5000/api/health"
wait_for_service "CMS" "http://localhost:1337/admin"
wait_for_service "Database" "http://localhost:5432" # This will need custom check

echo ""
echo -e "${GREEN}🎉 All services are running!${NC}"
echo "=================================="
echo -e "${CYAN}📊 Services:${NC}"
echo -e "  🌐 Frontend: ${GREEN}http://localhost:3000${NC}"
echo -e "  🔧 Backend:  ${GREEN}http://localhost:5000${NC}"
echo -e "  📋 CMS:       ${GREEN}http://localhost:1337/admin${NC}"
echo -e "  🗄️  Database:  ${GREEN}localhost:5432${NC}"
echo -e "  🔥 Redis:    ${GREEN}localhost:6379${NC}"
echo -e "  🌐 Nginx:    ${GREEN}http://localhost${NC}"
echo ""
echo -e "${BLUE}📱 View logs: docker-compose -f docker-compose.dev.yml logs -f${NC}"
echo -e "${BLUE}🛑 Stop: docker-compose -f docker-compose.dev.yml down${NC}"
```

### 3.2 Docker Production Script
```bash
#!/bin/bash
# scripts/docker-prod.sh - Docker production deployment

set -e

# Configuration
COMPOSE_FILE="docker-compose.prod.yml"
BACKUP_DIR="/var/backups/smartdairy"
HEALTH_CHECK_URL="https://smartdairy.com/api/health"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}🚀 Smart Dairy Docker Production Deployment${NC}"
echo "============================================"

# Check if running on production server
if [ "$NODE_ENV" != "production" ]; then
    echo -e "${RED}❌ This script should only be run in production${NC}"
    echo -e "${YELLOW}Set NODE_ENV=production${NC}"
    exit 1
fi

# Create backup
echo -e "${BLUE}💾 Creating backup...${NC}"
mkdir -p "$BACKUP_DIR"
BACKUP_NAME="pre-deploy-$(date +%Y%m%d-%H%M%S)"

# Backup current containers
docker-compose -f $COMPOSE_FILE ps -q | awk 'NR>1 {print $1}' | while read container; do
    echo -e "${BLUE}📦 Backing up $container...${NC}"
    docker commit "$container" "smart-dairy-backup-$container-$(date +%s)"
done

# Pull latest images
echo -e "${BLUE}📥 Pulling latest images...${NC}"
docker-compose -f $COMPOSE_FILE pull

# Deploy new containers
echo -e "${BLUE}🔄 Deploying new containers...${NC}"
docker-compose -f $COMPOSE_FILE up -d --force-recreate

# Wait for services to be ready
echo -e "${BLUE}⏳ Waiting for services to be ready...${NC}"

# Function to check service health
wait_for_service() {
    local service_name=$1
    local health_url=$2
    local max_attempts=60
    local attempt=1
    
    echo -e "${BLUE}Waiting for $service_name...${NC}"
    
    while [ $attempt -le $max_attempts ]; do
        if curl -f -s "$health_url" > /dev/null 2>&1; then
            echo -e "${GREEN}✅ $service_name is ready!${NC}"
            return 0
        fi
        
        echo -n "."
        sleep 2
        ((attempt++))
    done
    
    echo -e "${RED}❌ $service_name failed to start${NC}"
    return 1
}

# Wait for critical services
wait_for_service "Frontend" "$HEALTH_CHECK_URL"
wait_for_service "Backend" "$HEALTH_CHECK_URL/api/health"

# Health check with rollback
if curl -f -s "$HEALTH_CHECK_URL" > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Deployment successful!${NC}"
    
    # Clean up old containers
    echo -e "${BLUE}🧹 Cleaning up old containers...${NC}"
    docker image prune -f
    
    # Clean up old backups (keep last 10)
    echo -e "${BLUE}🗑 Cleaning old backups...${NC}"
    find "$BACKUP_DIR" -name "backup-*.tar" -type f -mtime +30 -delete
else
    echo -e "${RED}❌ Health check failed! Rolling back...${NC}"
    
    # Rollback to previous containers
    echo -e "${BLUE}🔄 Rolling back...${NC}"
    
    # Stop new containers
    docker-compose -f $COMPOSE_FILE down
    
    # Restore from backup (simplified rollback)
    echo -e "${YELLOW}⚠️  Manual rollback required${NC}"
    echo -e "${BLUE}Check backup directory: $BACKUP_DIR${NC}"
    
    exit 1
fi

echo ""
echo -e "${GREEN}🎉 Deployment complete!${NC}"
echo "=================================="
echo -e "${BLUE}📊 Container status:${NC}"
docker-compose -f $COMPOSE_FILE ps
echo ""
echo -e "${BLUE}📱 View logs: docker-compose -f $COMPOSE_FILE logs -f${NC}"
```

### 3.3 Docker Utility Script
```bash
#!/bin/bash
# scripts/docker-utils.sh - Docker utility functions

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

show_menu() {
    echo -e "${BLUE}🐳 Smart Dairy Docker Utilities${NC}"
    echo "==============================="
    echo "1. 📊 Show container status"
    echo "2. 📋 View container logs"
    echo "3. 🔧 Execute command in container"
    echo "4. 📦 Build images"
    echo "5. 🧹 Clean up unused resources"
    echo "6. 💾 Backup containers"
    echo "7. 📥 Restore containers"
    echo "8. 🔍 Inspect container"
    echo "9. 🔄 Restart services"
    echo "0. 🚪 Exit"
    echo ""
    echo -n "Select option: "
}

show_status() {
    echo -e "${BLUE}📊 Container Status${NC}"
    echo "======================="
    docker-compose ps
    
    echo ""
    echo -e "${BLUE}📈 Resource Usage:${NC}"
    docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.NetIO}}"
}

show_logs() {
    echo -e "${BLUE}📋 Container Logs${NC}"
    echo "===================="
    
    echo "Available containers:"
    docker-compose ps --services
    
    echo ""
    echo -n "Enter container name (or 'all'): "
    read container
    
    if [ "$container" = "all" ]; then
        docker-compose logs --tail=100
    else
        docker-compose logs --tail=100 "$container"
    fi
}

execute_command() {
    echo -e "${BLUE}🔧 Execute Command${NC}"
    echo "==================="
    
    echo "Available containers:"
    docker-compose ps --services
    
    echo ""
    echo -n "Enter container name: "
    read container
    
    echo -n "Enter command: "
    read command
    
    docker-compose exec "$container" $command
}

build_images() {
    echo -e "${BLUE}📦 Building Images${NC}"
    echo "======================"
    
    echo "1. Build development images"
    echo "2. Build production images"
    echo ""
    echo -n "Select environment: "
    read env
    
    case $env in
        1)
            docker-compose -f docker-compose.dev.yml build
            echo -e "${GREEN}✅ Development images built${NC}"
            ;;
        2)
            docker-compose -f docker-compose.prod.yml build
            echo -e "${GREEN}✅ Production images built${NC}"
            ;;
        *)
            echo -e "${RED}❌ Invalid option${NC}"
            ;;
    esac
}

cleanup_resources() {
    echo -e "${BLUE}🧹 Cleaning Up Resources${NC}"
    echo "=========================="
    
    echo "1. Remove stopped containers"
    echo "2. Remove unused images"
    echo "3. Remove unused volumes"
    echo "4. Remove unused networks"
    echo "5. Full cleanup"
    echo ""
    echo -n "Select option: "
    read option
    
    case $option in
        1)
            docker container prune -f
            echo -e "${GREEN}✅ Stopped containers removed${NC}"
            ;;
        2)
            docker image prune -f
            echo -e "${GREEN}✅ Unused images removed${NC}"
            ;;
        3)
            docker volume prune -f
            echo -e "${GREEN}✅ Unused volumes removed${NC}"
            ;;
        4)
            docker network prune -f
            echo -e "${GREEN}✅ Unused networks removed${NC}"
            ;;
        5)
            docker system prune -f --volumes
            echo -e "${GREEN}✅ Full cleanup completed${NC}"
            ;;
        *)
            echo -e "${RED}❌ Invalid option${NC}"
            ;;
    esac
}

backup_containers() {
    echo -e "${BLUE}💾 Backing Up Containers${NC}"
    echo "==========================="
    
    # Create backup directory
    BACKUP_DIR="/var/backups/smartdairy/containers-$(date +%Y%m%d-%H%M%S)"
    mkdir -p "$BACKUP_DIR"
    
    # Backup running containers
    docker ps --format "table {{.Names}}" | tail -n +2 | while read container; do
        echo -e "${BLUE}📦 Backing up $container...${NC}"
        docker commit "$container" "smartdairy-backup-$container-$(date +%s)"
        docker save "$container" > "$BACKUP_DIR/$container.tar"
    done
    
    # Backup volumes
    docker volume ls --format "table {{.Name}}" | tail -n +2 | while read volume; do
        echo -e "${BLUE}💾 Backing up volume $volume...${NC}"
        docker run --rm -v "$volume":/volume -v "$BACKUP_DIR":/backup alpine tar -czf /backup/$volume.tar.gz -C /volume .
    done
    
    echo -e "${GREEN}✅ Backup completed: $BACKUP_DIR${NC}"
}

restore_containers() {
    echo -e "${BLUE}📥 Restoring Containers${NC}"
    echo "============================"
    
    # List available backups
    BACKUP_DIR="/var/backups/smartdairy"
    echo -e "${BLUE}Available backups:${NC}"
    ls -1 "$BACKUP_DIR" | grep containers
    
    echo ""
    echo -n "Enter backup directory name: "
    read backup_name
    
    if [ -d "$BACKUP_DIR/$backup_name" ]; then
        echo -e "${BLUE}📥 Restoring from $backup_name...${NC}"
        
        # Load images from backup
        find "$BACKUP_DIR/$backup_name" -name "*.tar" | while read backup; do
            docker load -i "$backup"
        done
        
        echo -e "${GREEN}✅ Restore completed${NC}"
    else
        echo -e "${RED}❌ Backup not found${NC}"
    fi
}

inspect_container() {
    echo -e "${BLUE}🔍 Inspect Container${NC}"
    echo "========================="
    
    echo "Available containers:"
    docker-compose ps --services
    
    echo ""
    echo -n "Enter container name: "
    read container
    
    docker inspect "$container"
}

restart_services() {
    echo -e "${BLUE}🔄 Restarting Services${NC}"
    echo "=========================="
    
    echo "1. Restart development services"
    echo "2. Restart production services"
    echo ""
    echo -n "Select environment: "
    read env
    
    case $env in
        1)
            docker-compose -f docker-compose.dev.yml restart
            echo -e "${GREEN}✅ Development services restarted${NC}"
            ;;
        2)
            docker-compose -f docker-compose.prod.yml restart
            echo -e "${GREEN}✅ Production services restarted${NC}"
            ;;
        *)
            echo -e "${RED}❌ Invalid option${NC}"
            ;;
    esac
}

# Main menu loop
while true; do
    show_menu
    read choice
    
    case $choice in
        1) show_status ;;
        2) show_logs ;;
        3) execute_command ;;
        4) build_images ;;
        5) cleanup_resources ;;
        6) backup_containers ;;
        7) restore_containers ;;
        8) inspect_container ;;
        9) restart_services ;;
        0) echo -e "${GREEN}👋 Goodbye!${NC}"; exit 0 ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
    
    echo ""
    echo -n "Press Enter to continue..."
    read
done
```

---

## 4. MONITORING CONFIGURATIONS

### 4.1 Docker Monitoring with Prometheus
```yaml
# monitoring/docker-prometheus.yml - Docker monitoring
version: '3.8'

services:
  # Prometheus
  prometheus:
    image: prom/prometheus:latest
    container_name: smart-dairy-prometheus
    ports:
      - "9090:9090"
    volumes:
      - ./monitoring/prometheus.yml:/etc/prometheus/prometheus.yml:ro
      - prometheus-data:/prometheus
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.path=/prometheus'
      - '--web.console.libraries=/usr/share/prometheus/web/ui/libs'
      - '--web.console.templates=/usr/share/prometheus/consoles'
    networks:
      - monitoring
    restart: unless-stopped

  # Node Exporter
  node-exporter:
    image: prom/node-exporter:latest
    container_name: smart-dairy-node-exporter
    ports:
      - "9100:9100"
    volumes:
      - /proc:/host/proc:ro
      - /sys:/host/sys:ro
      - /:/rootfs:ro
    networks:
      - monitoring
    restart: unless-stopped

  # cAdvisor (Container Advisor)
  cadvisor:
    image: gcr.io/cadvisor/cadvisor:latest
    container_name: smart-dairy-cadvisor
    ports:
      - "8080:8080"
    volumes:
      - /:/rootfs:ro
      - /var/run:/var/run:ro
      - /sys:/sys:ro
      - /var/lib/docker/:/var/lib/docker:ro
      - /dev/disk/:/dev/disk:ro
    privileged: true
    devices:
      - /dev/kmsg
    networks:
      - monitoring
    restart: unless-stopped

  # PostgreSQL Exporter
  postgres-exporter:
    image: prometheuscommunity/postgres-exporter:latest
    container_name: smart-dairy-postgres-exporter
    ports:
      - "9187:9187"
    environment:
      DATA_SOURCE_NAME: "smart-dairy-postgres"
      POSTGRES_DB: "smart_dairy_prod"
      POSTGRES_USER: "smart_dairy_user"
      POSTGRES_PASSWORD: "${DB_PASSWORD}"
      POSTGRES_HOST: "postgres"
      POSTGRES_PORT: "5432"
    networks:
      - monitoring
      - smartdairy-network
    restart: unless-stopped

  # Redis Exporter
  redis-exporter:
    image: oliver006/redis_exporter:latest
    container_name: smart-dairy-redis-exporter
    ports:
      - "9121:9121"
    environment:
      REDIS_ADDR: "redis://redis:6379"
    networks:
      - monitoring
      - smartdairy-network
    restart: unless-stopped

volumes:
  prometheus-data:
    driver: local

networks:
  monitoring:
    driver: bridge
  smartdairy-network:
    external: true
```

### 4.2 Grafana Dashboard
```yaml
# monitoring/grafana.yml - Grafana configuration
version: '3.8'

services:
  grafana:
    image: grafana/grafana:latest
    container_name: smart-dairy-grafana
    ports:
      - "3001:3000"
    volumes:
      - grafana-data:/var/lib/grafana
      - ./monitoring/grafana/provisioning:/etc/grafana/provisioning
    environment:
      - GF_SECURITY_ADMIN_PASSWORD=${GRAFANA_PASSWORD}
      - GF_USERS_ALLOW_SIGN_UP=false
      - GF_INSTALL_PLUGINS=grafana-clock-panel,grafana-simple-json-datasource
      - GF_SERVER_DOMAIN=smartdairy.com
      - GF_SERVER_ROOT_URL=https://smartdairy.com/grafana
    networks:
      - monitoring
    restart: unless-stopped

volumes:
  grafana-data:
    driver: local

networks:
  monitoring:
    driver: bridge
```

---

## 5. SECURITY CONFIGURATIONS

### 5.1 Docker Security Hardening
```bash
#!/bin/bash
# scripts/docker-security.sh - Docker security hardening

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🔒 Docker Security Hardening${NC}"
echo "==============================="

# Function to check Docker daemon configuration
check_docker_config() {
    echo -e "${BLUE}🔍 Checking Docker daemon configuration...${NC}"
    
    # Check if daemon.json exists
    if [ ! -f "/etc/docker/daemon.json" ]; then
        echo -e "${YELLOW}⚠️  Docker daemon.json not found${NC}"
        return 1
    fi
    
    # Check security settings
    if grep -q '"live-restore": true' /etc/docker/daemon.json; then
        echo -e "${GREEN}✅ Live restore is enabled${NC}"
    else
        echo -e "${RED}❌ Live restore is disabled${NC}"
    fi
    
    if grep -q '"userland-proxy": false' /etc/docker/daemon.json; then
        echo -e "${GREEN}✅ Userland proxy is disabled${NC}"
    else
        echo -e "${RED}❌ Userland proxy is enabled${NC}"
    fi
    
    if grep -q '"no-new-privileges": true' /etc/docker/daemon.json; then
        echo -e "${GREEN}✅ No new privileges is enabled${NC}"
    else
        echo -e "${RED}❌ No new privileges is disabled${NC}"
    fi
}

# Function to secure Docker daemon
secure_docker_daemon() {
    echo -e "${BLUE}🔧 Securing Docker daemon...${NC}"
    
    # Create backup of existing config
    if [ -f "/etc/docker/daemon.json" ]; then
        cp /etc/docker/daemon.json /etc/docker/daemon.json.backup
    fi
    
    # Create secure configuration
    cat > /etc/docker/daemon.json << EOF
{
    "live-restore": true,
    "userland-proxy": false,
    "no-new-privileges": true,
    "seccomp-profile": "default",
    "storage-driver": "overlay2",
    "log-driver": "json-file",
    "log-opts": {
        "max-size": "10m",
        "max-file": "5",
        "compress": "true"
    },
    "default-ulimits": {
        "nofile": {
            "Name": "nofile",
            "Hard": 64000,
            "Soft": 64000
        }
    }
}
EOF
    
    # Restart Docker daemon
    echo -e "${BLUE}🔄 Restarting Docker daemon...${NC}"
    systemctl restart docker
    
    echo -e "${GREEN}✅ Docker daemon secured${NC}"
}

# Function to check container security
check_container_security() {
    echo -e "${BLUE}🔍 Checking container security...${NC}"
    
    # Get running containers
    docker ps --format "table {{.Names}}" | tail -n +2 | while read container; do
        echo -e "${BLUE}Checking $container...${NC}"
        
        # Check if running as root
        user=$(docker inspect "$container" --format '{{.Config.User}}')
        if [ "$user" = "" ] || [ "$user" = "root" ]; then
            echo -e "${RED}❌ Container running as root${NC}"
        else
            echo -e "${GREEN}✅ Container running as non-root user${NC}"
        fi
        
        # Check for privileged containers
        privileged=$(docker inspect "$container" --format '{{.HostConfig.Privileged}}')
        if [ "$privileged" = "true" ]; then
            echo -e "${RED}❌ Container is privileged${NC}"
        else
            echo -e "${GREEN}✅ Container is not privileged${NC}"
        fi
        
        # Check for capabilities
        caps=$(docker inspect "$container" --format '{{.HostConfig.CapDrop}}')
        if [ -n "$caps" ]; then
            echo -e "${GREEN}✅ Capabilities dropped: $caps${NC}"
        else
            echo -e "${YELLOW}⚠️  No capabilities dropped${NC}"
        fi
        
        # Check for read-only filesystem
        ro=$(docker inspect "$container" --format '{{.HostConfig.ReadonlyRootfs}}')
        if [ "$ro" = "true" ]; then
            echo -e "${GREEN}✅ Read-only filesystem enabled${NC}"
        else
            echo -e "${YELLOW}⚠️  Read-only filesystem not enabled${NC}"
        fi
    done
}

# Function to create secure Docker networks
create_secure_networks() {
    echo -e "${BLUE}🌐 Creating secure networks...${NC}"
    
    # Create application network
    docker network create \
        --driver bridge \
        --subnet=172.22.0.0/16 \
        --gateway=172.22.0.1 \
        --opt com.docker.network.bridge.enable_icc=false \
        --opt com.docker.network.bridge.enable_ip_masquerade=true \
        --opt com.docker.network.driver.mtu=1500 \
        smartdairy-app-network 2>/dev/null || echo "Network already exists"
    
    # Create monitoring network
    docker network create \
        --driver bridge \
        --subnet=172.23.0.0/16 \
        --gateway=172.23.0.1 \
        --opt com.docker.network.bridge.enable_icc=false \
        smartdairy-monitor-network 2>/dev/null || echo "Network already exists"
    
    echo -e "${GREEN}✅ Secure networks created${NC}"
}

# Main execution
echo -e "${BLUE}🔒 Starting Docker security hardening...${NC}"
echo ""

# Check current configuration
check_docker_config

# Check container security
check_container_security

# Create secure networks
create_secure_networks

echo ""
echo -e "${GREEN}✅ Docker security hardening complete${NC}"
echo "=================================="
```

---

## USAGE INSTRUCTIONS

### Quick Start Commands
```bash
# Development environment
docker-compose -f docker-compose.dev.yml up -d

# Production deployment
docker-compose -f docker-compose.prod.yml up -d

# View logs
docker-compose -f docker-compose.dev.yml logs -f

# Stop services
docker-compose -f docker-compose.dev.yml down

# Clean up
docker system prune -f
```

### Integration with CI/CD
1. **Development:** Use `docker-compose.dev.yml` for local development
2. **Production:** Use `docker-compose.prod.yml` for production deployment
3. **Monitoring:** Use monitoring configurations for observability
4. **Security:** Run security hardening scripts regularly

### Environment Variables
Create `.env` file with sensitive data:
```bash
# Production environment variables
DB_USER=smart_dairy_user
DB_PASSWORD=your_secure_password
DB_NAME=smart_dairy_prod
JWT_SECRET=your_jwt_secret_key
APP_KEYS=app_key_1,app_key_2
API_TOKEN_SALT=your_token_salt
ADMIN_JWT_SECRET=your_admin_jwt_secret
TRANSFER_TOKEN_SALT=your_transfer_salt
GRAFANA_PASSWORD=your_grafana_password
```

---

**Document Status:** Complete Docker Configuration Collection  
**Last Updated:** December 2, 2025  
**Next Review:** After implementation testing  
**Maintained By:** Development Team  

---

*This comprehensive Docker configuration provides complete containerization for Smart Dairy project, ensuring consistent development and production environments with proper security, monitoring, and deployment automation.*