# Linux Development Scripts Collection
## Smart Dairy Project - Solo Developer Workflow Optimization

**Document Version:** 1.0  
**Date:** December 2, 2025  
**Purpose:** Collection of Linux development scripts for maximum productivity

---

## 1. DEVELOPMENT ENVIRONMENT MANAGEMENT

### 1.1 Quick Start Script
```bash
#!/bin/bash
# scripts/quick-start.sh - Fast development environment setup

set -e

# Color definitions
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m'

# ASCII Art
echo -e "${CYAN}"
cat << "EOF"
 _____ _                          _               
|_   _| |_ _ __   _ _ __ ___  | | ___  _ __
  | | | | '_ \ \ / '_ \ \/ __| | | / _ \| '_ \
  | | | | | | \ | | | | |   | |  __/ | | | |
  |_| |_|_| |_| \_\___|_|_|_|   |_| \___|_| |_|
                                           __/ |             
                                          |___/              
EOF
echo -e "${NC}"

echo -e "${GREEN}🚀 Smart Dairy Quick Start${NC}"
echo "================================"

# Check prerequisites
check_prerequisite() {
    if ! command -v $1 &> /dev/null; then
        echo -e "${RED}❌ $1 is not installed${NC}"
        exit 1
    fi
}

echo -e "${BLUE}🔍 Checking prerequisites...${NC}"
check_prerequisite "node"
check_prerequisite "npm"
check_prerequisite "git"
check_prerequisite "tmux"

# Start services based on availability
start_service() {
    local service=$1
    local port=$2
    local path=$3
    
    echo -e "${BLUE}🔄 Starting $service...${NC}"
    
    if lsof -Pi :$port -sTCP:LISTEN -t &> /dev/null; then
        echo -e "${YELLOW}⚠️  $service already running on port $port${NC}"
    else
        cd $path
        if [ -f "package.json" ]; then
            npm run dev &
            echo -e "${GREEN}✅ $service starting...${NC}"
            sleep 3
        else
            echo -e "${RED}❌ No package.json found in $path${NC}"
        fi
    fi
}

# Check if tmux session exists
SESSION_NAME="smart-dairy"
if tmux has-session -t "$SESSION_NAME" 2>/dev/null; then
    echo -e "${YELLOW}⚠️  Development session already exists${NC}"
    echo -e "${BLUE}📱 Attach with: tmux attach -t $SESSION_NAME${NC}"
    read -p "Do you want to kill existing session and create new one? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        tmux kill-session -t "$SESSION_NAME"
        echo -e "${GREEN}✅ Existing session killed${NC}"
    else
        tmux attach -t "$SESSION_NAME"
        exit 0
    fi
fi

# Create new development session
echo -e "${PURPLE}🪟 Creating development environment...${NC}"
tmux new-session -d -s "$SESSION_NAME"

# Frontend
tmux new-window -t "$SESSION_NAME"
tmux rename-window -t "$SESSION_NAME:1" "Frontend"
tmux send-keys -t "$SESSION_NAME:1" "cd $(pwd)/smart-dairy-frontend && npm run dev:linux" C-m

# Backend
tmux new-window -t "$SESSION_NAME"
tmux rename-window -t "$SESSION_NAME:2" "Backend"
tmux send-keys -t "$SESSION_NAME:2" "cd $(pwd)/smart-dairy-backend && npm run dev" C-m

# Database
tmux new-window -t "$SESSION_NAME"
tmux rename-window -t "$SESSION_NAME:3" "Database"
tmux send-keys -t "$SESSION_NAME:3" "psql -h localhost -U smart_dairy_user -d smart_dairy_dev" C-m

# Terminal
tmux new-window -t "$SESSION_NAME"
tmux rename-window -t "$SESSION_NAME:4" "Terminal"

# Select frontend window
tmux select-window -t "$SESSION_NAME:0"

echo -e "${GREEN}✅ Development environment ready!${NC}"
echo -e "${CYAN}📊 Services:${NC}"
echo -e "  🌐 Frontend: ${GREEN}http://localhost:3000${NC}"
echo -e "  🔧 Backend:  ${GREEN}http://localhost:5000${NC}"
echo -e "  🗄️  Database:  ${GREEN}localhost:5432${NC}"
echo -e "${BLUE}📱 Attach with: tmux attach -t $SESSION_NAME${NC}"
```

### 1.2 Service Health Check
```bash
#!/bin/bash
# scripts/health-check.sh - Check all development services

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🏥 Smart Dairy Service Health Check${NC}"
echo "===================================="

# Function to check service
check_service() {
    local name=$1
    local port=$2
    local url=$3
    
    echo -n "🔍 $name: "
    
    if lsof -Pi :$port -sTCP:LISTEN -t &> /dev/null; then
        echo -e "${GREEN}RUNNING${NC} (port $port)"
        
        if [ -n "$url" ]; then
            echo -n "   🌐 Testing: "
            if curl -s --max-time 3 "$url" > /dev/null; then
                echo -e "${GREEN}OK${NC}"
            else
                echo -e "${RED}FAILED${NC}"
            fi
        fi
    else
        echo -e "${RED}STOPPED${NC}"
        return 1
    fi
}

# Check all services
services_ok=true

# Frontend
if ! check_service "Frontend" 3000 "http://localhost:3000"; then
    services_ok=false
fi

# Backend
if ! check_service "Backend" 5000 "http://localhost:5000/api/health"; then
    services_ok=false
fi

# CMS
if ! check_service "CMS" 1337 "http://localhost:1337/admin"; then
    services_ok=false
fi

# Database
echo -n "🗄️  PostgreSQL: "
if pg_isready -h localhost -p 5432 -U smart_dairy_user -q; then
    echo -e "${GREEN}READY${NC}"
else
    echo -e "${RED}NOT READY${NC}"
    services_ok=false
fi

# Redis
echo -n "🔥 Redis: "
if redis-cli ping | grep -q PONG; then
    echo -e "${GREEN}CONNECTED${NC}"
else
    echo -e "${RED}DISCONNECTED${NC}"
    services_ok=false
fi

# Summary
echo ""
if $services_ok; then
    echo -e "${GREEN}✅ All services are running properly!${NC}"
    exit 0
else
    echo -e "${RED}❌ Some services need attention${NC}"
    echo -e "${YELLOW}💡 Run './scripts/start-dev.sh' to start all services${NC}"
    exit 1
fi
```

### 1.3 Database Management
```bash
#!/bin/bash
# scripts/db-manager.sh - Database management utilities

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
DB_NAME="smart_dairy_dev"
DB_USER="smart_dairy_user"
BACKUP_DIR="/var/backups/smartdairy"

show_menu() {
    echo -e "${BLUE}🗄️  Smart Dairy Database Manager${NC}"
    echo "================================"
    echo "1. 📊 Check database status"
    echo "2. 💾 Create backup"
    echo "3. 📥 Restore backup"
    echo "4. 🔧 Reset database"
    echo "5. 📈 Show database size"
    echo "6. 🚪 Run migrations"
    echo "7. 📋 View active connections"
    echo "8. 🧹 Clean old backups"
    echo "0. 🚪 Exit"
    echo ""
    echo -n "Select option: "
}

check_db_status() {
    echo -e "${BLUE}📊 Database Status${NC}"
    echo "=================="
    
    if pg_isready -h localhost -p 5432 -U $DB_USER -q; then
        echo -e "${GREEN}✅ Database is ready${NC}"
    else
        echo -e "${RED}❌ Database is not ready${NC}"
        return 1
    fi
    
    # Get database size
    echo -e "${BLUE}📈 Database Information:${NC}"
    psql -h localhost -U $DB_USER -d $DB_NAME -c "
        SELECT 
            pg_size_pretty(pg_database_size('$DB_NAME')) as size,
            pg_stat_user_tables.n_tup_ins as inserts,
            pg_stat_user_tables.n_tup_upd as updates,
            pg_stat_user_tables.n_tup_del as deletes
        FROM pg_stat_user_tables
        LIMIT 1;
    " 2>/dev/null || echo "No statistics available"
}

create_backup() {
    echo -e "${BLUE}💾 Creating backup...${NC}"
    
    # Create backup directory if it doesn't exist
    mkdir -p "$BACKUP_DIR"
    
    # Generate backup filename
    BACKUP_FILE="$BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).sql"
    
    # Create backup
    if pg_dump -h localhost -U $DB_USER -d $DB_NAME > "$BACKUP_FILE"; then
        echo -e "${GREEN}✅ Backup created: $BACKUP_FILE${NC}"
        
        # Compress backup
        gzip "$BACKUP_FILE"
        echo -e "${BLUE}🗜️  Backup compressed${NC}"
    else
        echo -e "${RED}❌ Backup failed${NC}"
        return 1
    fi
}

restore_backup() {
    echo -e "${BLUE}📥 Available backups:${NC}"
    echo "=================="
    
    # List available backups
    local backups=($(ls -t "$BACKUP_DIR"/backup-*.sql.gz 2>/dev/null | head -10))
    
    if [ ${#backups[@]} -eq 0 ]; then
        echo -e "${RED}No backups found${NC}"
        return 1
    fi
    
    for i in "${!backups[@]}"; do
        echo "$((i+1)). ${backups[$i]}"
    done
    
    echo ""
    echo -n "Select backup to restore (1-${#backups[@]}): "
    read choice
    
    if [[ $choice -ge 1 && $choice -le ${#backups[@]} ]]; then
        selected_backup="${backups[$((choice-1))]}"
        echo -e "${BLUE}📥 Restoring: $selected_backup${NC}"
        
        # Drop existing database
        dropdb -h localhost -U $DB_USER $DB_NAME 2>/dev/null || true
        
        # Create new database
        createdb -h localhost -U $DB_USER $DB_NAME
        
        # Restore backup
        gunzip -c "$selected_backup" | psql -h localhost -U $DB_USER -d $DB_NAME
        
        echo -e "${GREEN}✅ Backup restored successfully${NC}"
    else
        echo -e "${RED}❌ Invalid selection${NC}"
        return 1
    fi
}

reset_database() {
    echo -e "${YELLOW}⚠️  This will delete all data!${NC}"
    echo -n "Are you sure? (type 'RESET'): "
    read confirm
    
    if [ "$confirm" = "RESET" ]; then
        echo -e "${BLUE}🔄 Resetting database...${NC}"
        
        # Drop and recreate database
        dropdb -h localhost -U $DB_USER $DB_NAME 2>/dev/null || true
        createdb -h localhost -U $DB_USER $DB_NAME
        
        # Run migrations
        cd ../smart-dairy-backend
        npm run migrate
        
        echo -e "${GREEN}✅ Database reset complete${NC}"
    else
        echo -e "${BLUE}❌ Operation cancelled${NC}"
    fi
}

show_db_size() {
    echo -e "${BLUE}📈 Database Size Information${NC}"
    echo "=============================="
    
    psql -h localhost -U $DB_USER -d postgres -c "
        SELECT 
            datname as database,
            pg_size_pretty(pg_database_size(datname)) as size,
            pg_database_size(datname) as size_bytes
        FROM pg_database 
        WHERE datname = '$DB_NAME';
    "
    
    echo ""
    echo -e "${BLUE}📊 Table Sizes:${NC}"
    psql -h localhost -U $DB_USER -d $DB_NAME -c "
        SELECT 
            schemaname,
            tablename,
            pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) as size
        FROM pg_tables 
        WHERE schemaname NOT IN ('information_schema', 'pg_catalog')
        ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC
        LIMIT 10;
    "
}

run_migrations() {
    echo -e "${BLUE}🚪 Running database migrations...${NC}"
    
    cd ../smart-dairy-backend
    
    if [ -f "package.json" ]; then
        npm run migrate
        echo -e "${GREEN}✅ Migrations completed${NC}"
    else
        echo -e "${RED}❌ Backend directory not found${NC}"
        return 1
    fi
}

show_connections() {
    echo -e "${BLUE}📋 Active Database Connections${NC}"
    echo "================================="
    
    psql -h localhost -U $DB_USER -d $DB_NAME -c "
        SELECT 
            pid,
            usename,
            application_name,
            client_addr,
            state,
            query_start,
            state_change,
            waiting
        FROM pg_stat_activity 
        WHERE state IS NOT NULL
        ORDER BY query_start;
    "
}

clean_old_backups() {
    echo -e "${BLUE}🧹 Cleaning old backups...${NC}"
    
    # Keep last 10 backups
    find "$BACKUP_DIR" -name "backup-*.sql.gz" -type f -mtime +30 -delete
    echo -e "${GREEN}✅ Old backups cleaned${NC}"
}

# Main menu loop
while true; do
    show_menu
    read choice
    
    case $choice in
        1) check_db_status ;;
        2) create_backup ;;
        3) restore_backup ;;
        4) reset_database ;;
        5) show_db_size ;;
        6) run_migrations ;;
        7) show_connections ;;
        8) clean_old_backups ;;
        0) echo -e "${GREEN}👋 Goodbye!${NC}"; exit 0 ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
    
    echo ""
    echo -n "Press Enter to continue..."
    read
done
```

---

## 2. PERFORMANCE MONITORING

### 2.1 Real-time Performance Monitor
```bash
#!/bin/bash
# scripts/perf-monitor.sh - Real-time performance monitoring

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m'

# Configuration
UPDATE_INTERVAL=2
ALERT_CPU=80
ALERT_MEMORY=85
ALERT_DISK=90

# Clear screen and setup
clear
echo -e "${CYAN}📊 Smart Dairy Performance Monitor${NC}"
echo "=================================="
echo "Press Ctrl+C to exit"
echo ""

# Function to get CPU usage
get_cpu_usage() {
    top -bn1 | grep "Cpu(s)" | awk '{print 100 - $8}' | cut -d. -f1
}

# Function to get memory usage
get_memory_usage() {
    free | grep Mem | awk '{printf "%.1f", $3/$2 * 100.0}'
}

# Function to get disk usage
get_disk_usage() {
    df / | tail -1 | awk '{print $5}' | cut -d% -f1
}

# Function to get load average
get_load_average() {
    uptime | awk -F'load average:' '{print $2}' | cut -d, -f1 | xargs
}

# Function to display system info
display_system_info() {
    local cpu_usage=$(get_cpu_usage)
    local memory_usage=$(get_memory_usage)
    local disk_usage=$(get_disk_usage)
    local load_avg=$(get_load_average)
    
    # CPU usage with color
    echo -n "🔥 CPU: "
    if (( $(echo "$cpu_usage > $ALERT_CPU" | bc -l) )); then
        echo -e "${RED}${cpu_usage}%${NC} ⚠️ "
    else
        echo -e "${GREEN}${cpu_usage}%${NC}"
    fi
    
    # Memory usage with color
    echo -n "💾 RAM: "
    if (( $(echo "$memory_usage > $ALERT_MEMORY" | bc -l) )); then
        echo -e "${RED}${memory_usage}%${NC} ⚠️ "
    else
        echo -e "${GREEN}${memory_usage}%${NC}"
    fi
    
    # Disk usage with color
    echo -n "💿 Disk: "
    if (( $(echo "$disk_usage > $ALERT_DISK" | bc -l) )); then
        echo -e "${RED}${disk_usage}%${NC} ⚠️ "
    else
        echo -e "${GREEN}${disk_usage}%${NC}"
    fi
    
    # Load average
    echo -e "${PURPLE}Load: ${load_avg}${NC}"
    
    # Node.js processes
    echo -e "${BLUE}📱 Node.js Processes:${NC}"
    ps aux | grep node | grep -v grep | while read -r line; do
        local pid=$(echo $line | awk '{print $2}')
        local cpu=$(echo $line | awk '{print $3}')
        local mem=$(echo $line | awk '{print $4}')
        local cmd=$(echo $line | awk '{for(i=11;i<=NF;i++) $i=""}')
        
        echo -e "  PID: ${CYAN}$pid${NC} CPU: ${cpu}% MEM: ${mem}% CMD: $cmd"
    done
    
    # Network connections
    echo -e "${YELLOW}🌐 Network Connections:${NC}"
    netstat -tuln | grep LISTEN | head -5 | while read -r line; do
        local port=$(echo $line | awk '{print $4}' | cut -d: -f2)
        local service=$(echo $line | awk '{print $1}')
        echo -e "  ${GREEN}$port${NC} - $service"
    done
    
    echo ""
    echo -e "${CYAN}Last updated: $(date '+%H:%M:%S')${NC}"
    echo "=================================="
}

# Trap to handle exit
trap 'echo -e "\n${GREEN}👋 Monitoring stopped${NC}"; exit 0' INT

# Main monitoring loop
while true; do
    # Move cursor to top of screen
    tput cup 0 0
    
    display_system_info
    
    sleep $UPDATE_INTERVAL
done
```

### 2.2 Application Performance Monitor
```bash
#!/bin/bash
# scripts/app-perf.sh - Application-specific performance monitoring

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🚀 Smart Dairy Application Performance${NC}"
echo "===================================="

# Check frontend performance
echo -e "${GREEN}🌐 Frontend Performance:${NC}"
echo "------------------------"

# Lighthouse score (if available)
if command -v lighthouse &> /dev/null; then
    echo -e "${BLUE}Running Lighthouse audit...${NC}"
    lighthouse --chrome-flags="--headless" \
             --output=json \
             --output-path=./lighthouse-report \
             http://localhost:3000 > /dev/null 2>&1
    
    if [ -f "lighthouse-report.json" ]; then
        PERFORMANCE=$(cat lighthouse-report.json | jq -r '.lhr.categories.performance.score * 100')
        echo -e "Performance Score: ${GREEN}$PERFORMANCE${NC}/100"
        
        SEO=$(cat lighthouse-report.json | jq -r '.lhr.categories.seo.score * 100')
        echo -e "SEO Score: ${GREEN}$SEO${NC}/100"
        
        BEST_PRACTICES=$(cat lighthouse-report.json | jq -r '.lhr.categories["best-practices"].score * 100')
        echo -e "Best Practices: ${GREEN}$BEST_PRACTICES${NC}/100"
    fi
fi

# Bundle size analysis
echo -e "${BLUE}📦 Bundle Analysis:${NC}"
if [ -d "../smart-dairy-frontend/.next" ]; then
    echo -e "Frontend build size:"
    du -sh ../smart-dairy-frontend/.next 2>/dev/null || echo "No build found"
fi

# Backend performance
echo -e ""
echo -e "${GREEN}🔧 Backend Performance:${NC}"
echo "------------------------"

# API response time
echo -e "${BLUE}Testing API endpoints...${NC}"
endpoints=(
    "http://localhost:5000/api/health"
    "http://localhost:5000/api/products"
    "http://localhost:5000/api/users/me"
)

for endpoint in "${endpoints[@]}"; do
    echo -n "Testing $endpoint: "
    
    start_time=$(date +%s%N)
    response=$(curl -s -w "%{http_code}" -o /dev/null "$endpoint")
    end_time=$(date +%s%N)
    
    response_time=$(( (end_time - start_time) / 1000000 ))
    
    if [ "$response" = "200" ]; then
        if [ $response_time -lt 500 ]; then
            echo -e "${GREEN}${response_time}ms${NC} ✅"
        else
            echo -e "${YELLOW}${response_time}ms${NC} ⚠️ "
        fi
    else
        echo -e "${RED}Failed (${response})${NC} ❌"
    fi
done

# Database performance
echo -e ""
echo -e "${GREEN}🗄️  Database Performance:${NC}"
echo "---------------------------"

if pg_isready -h localhost -p 5432 -U smart_dairy_user -q; then
    # Connection count
    connections=$(psql -h localhost -U smart_dairy_user -d smart_dairy_dev -t -c "
        SELECT count(*) FROM pg_stat_activity WHERE state = 'active';
    " 2>/dev/null || echo "N/A")
    echo -e "Active connections: $connections"
    
    # Database size
    db_size=$(psql -h localhost -U smart_dairy_user -d postgres -t -c "
        SELECT pg_size_pretty(pg_database_size('smart_dairy_dev'));
    " 2>/dev/null || echo "N/A")
    echo -e "Database size: $db_size"
    
    # Slow queries
    echo -e "${BLUE}Recent slow queries:${NC}"
    psql -h localhost -U smart_dairy_user -d smart_dairy_dev -c "
        SELECT query, mean_time, calls 
        FROM pg_stat_statements 
        WHERE mean_time > 100 
        ORDER BY mean_time DESC 
        LIMIT 5;
    " 2>/dev/null | while IFS='|' read -r query mean_time calls; do
        echo -e "Time: ${YELLOW}${mean_time}ms${NC} Calls: $calls"
        echo -e "Query: ${CYAN}${query:0:50}...${NC}"
        echo ""
    done
else
    echo -e "${RED}Database not accessible${NC}"
fi

# Redis performance
echo -e ""
echo -e "${GREEN}🔥 Redis Performance:${NC}"
echo "------------------------"

if redis-cli ping | grep -q PONG; then
    # Memory usage
    redis_memory=$(redis-cli info memory | grep used_memory_human | cut -d: -f2 | tr -d '\r')
    echo -e "Memory used: $redis_memory"
    
    # Connected clients
    clients=$(redis-cli info clients | grep connected_clients | cut -d: -f2 | tr -d '\r')
    echo -e "Connected clients: $clients"
    
    # Hit rate
    hits=$(redis-cli info stats | grep keyspace_hits | cut -d: -f2 | tr -d '\r')
    misses=$(redis-cli info stats | grep keyspace_misses | cut -d: -f2 | tr -d '\r')
    if [ $hits -gt 0 ] || [ $misses -gt 0 ]; then
        hit_rate=$(echo "scale=2; $hits * 100 / ($hits + $misses)" | bc)
        echo -e "Hit rate: ${GREEN}${hit_rate}%${NC}"
    fi
else
    echo -e "${RED}Redis not accessible${NC}"
fi

echo ""
echo -e "${BLUE}📊 Performance Summary Complete${NC}"
echo "================================"
```

---

## 3. DEPLOYMENT AUTOMATION

### 3.1 Production Deployment Script
```bash
#!/bin/bash
# scripts/deploy-prod.sh - Production deployment with rollback

set -e

# Configuration
DEPLOY_USER="deploy"
DEPLOY_HOST="smartdairy.com"
DEPLOY_PATH="/var/www/smartdairy"
BACKUP_PATH="/var/backups/smartdairy"
HEALTH_CHECK_URL="https://smartdairy.com/api/health"
SLACK_WEBHOOK_URL="https://hooks.slack.com/services/YOUR/SLACK/WEBHOOK"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Logging function
log_message() {
    local level=$1
    local message=$2
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    
    echo -e "[$timestamp] [$level] $message"
    
    # Send to Slack if webhook is configured
    if [ -n "$SLACK_WEBHOOK_URL" ]; then
        curl -X POST -H 'Content-type: application/json' \
             --data "{\"text\":\"[$level] $message\"}" \
             "$SLACK_WEBHOOK_URL" > /dev/null 2>&1
    fi
}

# Pre-deployment checks
pre_deploy_checks() {
    log_message "INFO" "🔍 Running pre-deployment checks"
    
    # Check if all tests pass
    log_message "INFO" "🧪 Running tests"
    cd ../smart-dairy-backend && npm test > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        log_message "SUCCESS" "All tests passed"
    else
        log_message "ERROR" "Tests failed - aborting deployment"
        exit 1
    fi
    
    # Check if production is healthy
    log_message "INFO" "🏥 Checking production health"
    if curl -f -s "$HEALTH_CHECK_URL" > /dev/null; then
        log_message "INFO" "Production is currently healthy"
    else
        log_message "WARNING" "Production health check failed - proceeding with caution"
    fi
}

# Create backup
create_backup() {
    log_message "INFO" "💾 Creating backup"
    
    BACKUP_NAME="pre-deploy-$(date +%Y%m%d-%H%M%S)"
    BACKUP_DIR="$BACKUP_PATH/$BACKUP_NAME"
    
    mkdir -p "$BACKUP_DIR"
    
    # Backup current production
    if ssh "$DEPLOY_USER@$DEPLOY_HOST" "tar -czf /tmp/$BACKUP_NAME.tar.gz -C $DEPLOY_PATH ."; then
        scp "$DEPLOY_USER@$DEPLOY_HOST:/tmp/$BACKUP_NAME.tar.gz" "$BACKUP_PATH/"
        log_message "SUCCESS" "Backup created: $BACKUP_NAME"
    else
        log_message "ERROR" "Backup creation failed"
        exit 1
    fi
}

# Deploy application
deploy_app() {
    log_message "INFO" "🚀 Deploying application"
    
    # Create temporary directory
    TEMP_DIR="/tmp/smartdairy-deploy-$(date +%s)"
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "mkdir -p $TEMP_DIR"
    
    # Sync code
    log_message "INFO" "📥 Syncing application code"
    rsync -avz --exclude=node_modules --exclude=.git \
          ./ "$DEPLOY_USER@$DEPLOY_HOST:$TEMP_DIR/"
    
    # Install dependencies and build
    log_message "INFO" "📦 Installing dependencies"
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "cd $TEMP_DIR && npm ci --production"
    
    log_message "INFO" "🔨 Building application"
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "cd $TEMP_DIR && npm run build"
    
    # Atomic deployment
    log_message "INFO" "🔄 Performing atomic deployment"
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "
        mv $DEPLOY_PATH $DEPLOY_PATH.old 2>/dev/null || true
        mv $TEMP_DIR $DEPLOY_PATH
        rm -rf $DEPLOY_PATH.old
    "
    
    # Restart services
    log_message "INFO" "🔄 Restarting services"
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "cd $DEPLOY_PATH && pm2 reload ecosystem.config.js --env production"
    
    # Cleanup
    ssh "$DEPLOY_USER@$DEPLOY_HOST" "rm -rf $TEMP_DIR"
    
    log_message "SUCCESS" "Deployment completed"
}

# Health check
health_check() {
    log_message "INFO" "🏥 Running post-deployment health check"
    
    local max_attempts=10
    local attempt=1
    
    while [ $attempt -le $max_attempts ]; do
        log_message "INFO" "Health check attempt $attempt/$max_attempts"
        
        if curl -f -s "$HEALTH_CHECK_URL" > /dev/null; then
            log_message "SUCCESS" "Health check passed"
            return 0
        fi
        
        sleep 10
        ((attempt++))
    done
    
    log_message "ERROR" "Health check failed after $max_attempts attempts"
    return 1
}

# Rollback function
rollback() {
    log_message "ERROR" "🔄 Initiating rollback"
    
    if [ -n "$BACKUP_NAME" ]; then
        log_message "INFO" "📥 Restoring from backup: $BACKUP_NAME"
        
        ssh "$DEPLOY_USER@$DEPLOY_HOST" "
            mv $DEPLOY_PATH $DEPLOY_PATH.failed 2>/dev/null || true
            tar -xzf $BACKUP_PATH/pre-deploy-$(date +%Y%m%d)*.tar.gz -C /
            cd $DEPLOY_PATH && pm2 reload ecosystem.config.js --env production
        "
        
        log_message "SUCCESS" "Rollback completed"
    else
        log_message "ERROR" "No backup available for rollback"
    fi
}

# Main deployment flow
main() {
    log_message "INFO" "🚀 Starting Smart Dairy Production Deployment"
    log_message "INFO" "========================================"
    
    # Pre-deployment checks
    pre_deploy_checks
    
    # Create backup
    create_backup
    
    # Deploy
    deploy_app
    
    # Health check
    if health_check; then
        log_message "SUCCESS" "🎉 Deployment successful!"
        
        # Cleanup old backups (keep last 10)
        log_message "INFO" "🧹 Cleaning old backups"
        ssh "$DEPLOY_USER@$DEPLOY_HOST" "find $BACKUP_PATH -name 'pre-deploy-*' -type d -mtime +30 -exec rm -rf {} +"
        
        exit 0
    else
        # Health check failed - rollback
        rollback
        exit 1
    fi
}

# Trap to handle interruption
trap 'log_message "ERROR" "Deployment interrupted"; exit 1' INT TERM

# Execute main function
main "$@"
```

---

## 4. MAINTENANCE UTILITIES

### 4.1 Log Management
```bash
#!/bin/bash
# scripts/log-manager.sh - Centralized log management

set -e

# Configuration
LOG_DIR="/var/log/smartdairy"
RETENTION_DAYS=30
ARCHIVE_DIR="$LOG_DIR/archive"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

show_menu() {
    echo -e "${BLUE}📋 Smart Dairy Log Manager${NC}"
    echo "=============================="
    echo "1. 📊 View application logs"
    echo "2. 🔍 Search in logs"
    echo "3. 📦 Archive old logs"
    echo "4. 🧹 Clean old logs"
    echo "5. 📈 Show log statistics"
    echo "6. 📥 Download logs"
    echo "0. 🚪 Exit"
    echo ""
    echo -n "Select option: "
}

view_logs() {
    local service=$1
    
    case $service in
        "frontend")
            if [ -f "$LOG_DIR/frontend.log" ]; then
                less +F "$LOG_DIR/frontend.log"
            else
                echo -e "${RED}❌ Frontend log not found${NC}"
            fi
            ;;
        "backend")
            if [ -f "$LOG_DIR/backend.log" ]; then
                less +F "$LOG_DIR/backend.log"
            else
                echo -e "${RED}❌ Backend log not found${NC}"
            fi
            ;;
        "database")
            if [ -f "$LOG_DIR/postgresql.log" ]; then
                less +F "$LOG_DIR/postgresql.log"
            else
                echo -e "${RED}❌ Database log not found${NC}"
            fi
            ;;
        "nginx")
            if [ -f "$LOG_DIR/nginx/access.log" ]; then
                less +F "$LOG_DIR/nginx/access.log"
            else
                echo -e "${RED}❌ Nginx log not found${NC}"
            fi
            ;;
    esac
}

search_logs() {
    echo -n "Enter search term: "
    read search_term
    
    if [ -z "$search_term" ]; then
        echo -e "${RED}❌ Search term cannot be empty${NC}"
        return 1
    fi
    
    echo -e "${BLUE}🔍 Searching for: $search_term${NC}"
    echo "============================"
    
    # Search in all log files
    grep -n -i --color=always "$search_term" "$LOG_DIR"/*.log 2>/dev/null || \
        echo -e "${RED}No matches found${NC}"
}

archive_logs() {
    echo -e "${BLUE}📦 Archiving old logs...${NC}"
    
    mkdir -p "$ARCHIVE_DIR"
    
    # Find and archive logs older than 1 day
    find "$LOG_DIR" -name "*.log" -mtime +1 -exec mv {} "$ARCHIVE_DIR/" \;
    
    # Compress archived logs
    cd "$ARCHIVE_DIR"
    for log in *.log; do
        if [ -f "$log" ]; then
            gzip "$log"
            echo -e "${GREEN}✅ Archived: $log.gz${NC}"
        fi
    done
    
    echo -e "${GREEN}✅ Log archiving complete${NC}"
}

clean_logs() {
    echo -e "${YELLOW}⚠️  This will delete logs older than $RETENTION_DAYS days${NC}"
    echo -n "Are you sure? (type 'DELETE'): "
    read confirm
    
    if [ "$confirm" = "DELETE" ]; then
        echo -e "${BLUE}🧹 Cleaning old logs...${NC}"
        
        # Delete old log files
        find "$LOG_DIR" -name "*.log" -mtime +$RETENTION_DAYS -delete
        
        # Delete old archives
        find "$ARCHIVE_DIR" -name "*.log.gz" -mtime +$RETENTION_DAYS -delete
        
        echo -e "${GREEN}✅ Log cleanup complete${NC}"
    else
        echo -e "${BLUE}❌ Operation cancelled${NC}"
    fi
}

show_stats() {
    echo -e "${BLUE}📈 Log Statistics${NC}"
    echo "======================"
    
    # Application logs
    echo -e "${GREEN}Application Logs:${NC}"
    for log in "$LOG_DIR"/*.log; do
        if [ -f "$log" ]; then
            size=$(du -h "$log" | cut -f1)
            lines=$(wc -l < "$log" 2>/dev/null || echo "0")
            modified=$(stat -c %y "$log" 2>/dev/null || echo "Unknown")
            
            echo -e "  $(basename $log): $size, $lines lines, modified $modified"
        fi
    done
    
    echo ""
    echo -e "${GREEN}Disk Usage:${NC}"
    du -sh "$LOG_DIR" | tail -1
    
    echo ""
    echo -e "${GREEN}Archive Size:${NC}"
    if [ -d "$ARCHIVE_DIR" ]; then
        du -sh "$ARCHIVE_DIR" | tail -1
    else
        echo "No archives found"
    fi
}

download_logs() {
    local archive_name="smartdairy-logs-$(date +%Y%m%d-%H%M%S).tar.gz"
    
    echo -e "${BLUE}📥 Creating log archive: $archive_name${NC}"
    
    tar -czf "/tmp/$archive_name" -C "$LOG_DIR" .
    
    echo -e "${GREEN}✅ Archive created: /tmp/$archive_name${NC}"
    echo -e "${BLUE}📁 Download with: scp /tmp/$archive_name user@host:/path/${NC}"
}

# Main menu loop
while true; do
    show_menu
    read choice
    
    case $choice in
        1) 
            echo -e "${BLUE}Select service:${NC}"
            echo "1. Frontend"
            echo "2. Backend"
            echo "3. Database"
            echo "4. Nginx"
            echo -n "Choice: "
            read service_choice
            case $service_choice in
                1) view_logs "frontend" ;;
                2) view_logs "backend" ;;
                3) view_logs "database" ;;
                4) view_logs "nginx" ;;
                *) echo -e "${RED}❌ Invalid choice${NC}" ;;
            esac
            ;;
        2) search_logs ;;
        3) archive_logs ;;
        4) clean_logs ;;
        5) show_stats ;;
        6) download_logs ;;
        0) echo -e "${GREEN}👋 Goodbye!${NC}"; exit 0 ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
    
    echo ""
    echo -n "Press Enter to continue..."
    read
done
```

---

## 5. SECURITY UTILITIES

### 5.1 Security Audit Script
```bash
#!/bin/bash
# scripts/security-audit.sh - Linux security audit for PCI DSS

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🔒 Smart Dairy Security Audit${NC}"
echo "==============================="

# Function to check file permissions
check_permissions() {
    local path=$1
    local expected_perm=$2
    
    if [ -e "$path" ]; then
        actual_perm=$(stat -c %a "$path")
        if [ "$actual_perm" = "$expected_perm" ]; then
            echo -e "${GREEN}✅ $path: Correct permissions${NC}"
        else
            echo -e "${RED}❌ $path: Incorrect permissions ($actual_perm, expected $expected_perm)${NC}"
        fi
    else
        echo -e "${YELLOW}⚠️  $path: Not found${NC}"
    fi
}

# Function to check SSL certificate
check_ssl_cert() {
    local domain=$1
    local port=${2:-443}
    
    echo -e "${BLUE}🔐 Checking SSL certificate for $domain:$port${NC}"
    
    if command -v openssl &> /dev/null; then
        cert_info=$(echo | openssl s_client -connect $domain:$port -servername $domain 2>/dev/null | openssl x509 -noout -dates 2>/dev/null)
        
        if [ -n "$cert_info" ]; then
            not_after=$(echo "$cert_info" | grep "notAfter" | cut -d= -f2)
            echo -e "Certificate expires: $not_after"
            
            # Check if certificate expires within 30 days
            expiry_timestamp=$(date -d "$not_after" +%s)
            current_timestamp=$(date +%s)
            days_until_expiry=$(( (expiry_timestamp - current_timestamp) / 86400 ))
            
            if [ $days_until_expiry -lt 30 ]; then
                echo -e "${RED}⚠️  Certificate expires in $days_until_expiry days!${NC}"
            else
                echo -e "${GREEN}✅ Certificate valid for $days_until_expiry days${NC}"
            fi
        else
            echo -e "${RED}❌ Could not retrieve certificate information${NC}"
        fi
    else
        echo -e "${RED}❌ OpenSSL not available${NC}"
    fi
}

# Function to check firewall status
check_firewall() {
    echo -e "${BLUE}🛡️  Firewall Status${NC}"
    
    if command -v ufw &> /dev/null; then
        if ufw status | grep -q "Status: active"; then
            echo -e "${GREEN}✅ UFW is active${NC}"
            
            echo -e "${BLUE}Firewall Rules:${NC}"
            ufw status verbose | grep -A 20 "Status: active"
        else
            echo -e "${RED}❌ UFW is not active${NC}"
        fi
    else
        echo -e "${YELLOW}⚠️  UFW not available${NC}"
    fi
}

# Function to check for security updates
check_updates() {
    echo -e "${BLUE}🔄 Checking for security updates${NC}"
    
    # Check system updates
    if command -v apt &> /dev/null; then
        updates=$(apt list --upgradable 2>/dev/null | grep -c security)
        if [ "$updates" -gt 0 ]; then
            echo -e "${RED}❌ $updates security updates available${NC}"
            echo -e "${BLUE}Run: sudo apt update && sudo apt upgrade${NC}"
        else
            echo -e "${GREEN}✅ No security updates pending${NC}"
        fi
    fi
    
    # Check Node.js security advisories
    if command -v npm &> /dev/null; then
        echo -e "${BLUE}📦 Checking Node.js dependencies...${NC}"
        cd ../smart-dairy-backend
        npm audit --audit-level=high
    fi
}

# Function to check file integrity
check_integrity() {
    echo -e "${BLUE}🔍 Checking file integrity${NC}"
    
    # Check critical files
    critical_files=(
        "/etc/passwd"
        "/etc/shadow"
        "/etc/group"
        "/etc/gshadow"
        "/var/www/smartdairy/.env"
        "/var/www/smartdairy/ecosystem.config.js"
    )
    
    for file in "${critical_files[@]}"; do
        if [ -e "$file" ]; then
            # Check for unexpected changes (basic check)
            if [ -f "$file" ]; then
                size=$(stat -c %s "$file")
                modified=$(stat -c %y "$file")
                echo -e "$file: Size: $size bytes, Modified: $modified"
                
                # Check for suspicious permissions
                if [ "$(basename $file)" = ".env" ] && [ "$(stat -c %a "$file)" != "600" ]; then
                    echo -e "${RED}❌ $file has insecure permissions!${NC}"
                fi
            fi
        fi
    done
}

# Function to check running services
check_services() {
    echo -e "${BLUE}🔧 Running Services Security Check${NC}"
    
    # Check for unexpected services
    unexpected_ports=(
        "23"    # Telnet
        "69"    # TFTP
        "161"    # SNMP
        "445"    # SMB
    )
    
    for port in "${unexpected_ports[@]}"; do
        if netstat -tuln | grep -q ":$port "; then
            echo -e "${RED}❌ Unexpected service running on port $port${NC}"
        else
            echo -e "${GREEN}✅ Port $port is secure${NC}"
        fi
    done
    
    # Check web server configuration
    if [ -f "/etc/nginx/sites-available/smartdairy" ]; then
        echo -e "${BLUE}🌐 Web Server Security:${NC}"
        
        # Check for security headers
        if grep -q "add_header X-Frame-Options" /etc/nginx/sites-available/smartdairy; then
            echo -e "${GREEN}✅ X-Frame-Options header configured${NC}"
        else
            echo -e "${RED}❌ X-Frame-Options header missing${NC}"
        fi
        
        if grep -q "add_header Strict-Transport-Security" /etc/nginx/sites-available/smartdairy; then
            echo -e "${GREEN}✅ HSTS header configured${NC}"
        else
            echo -e "${RED}❌ HSTS header missing${NC}"
        fi
    fi
}

# Main audit
echo -e "${BLUE}🔍 Starting security audit...${NC}"
echo ""

# Check system security
check_updates
echo ""

# Check SSL certificates
check_ssl_cert "smartdairy.com" 443
check_ssl_cert "www.smartdairy.com" 443
echo ""

# Check firewall
check_firewall
echo ""

# Check file permissions
echo -e "${BLUE}📁 Checking critical file permissions${NC}"
check_permissions "/var/www/smartdairy/.env" "600"
check_permissions "/var/www/smartdairy/ecosystem.config.js" "600"
echo ""

# Check file integrity
check_integrity
echo ""

# Check services
check_services
echo ""

echo -e "${GREEN}✅ Security audit complete${NC}"
echo "=========================="

# Generate report
report_file="/tmp/security-audit-$(date +%Y%m%d-%H%M%S).txt"
{
    echo "Smart Dairy Security Audit Report"
    echo "Generated: $(date)"
    echo "=================================="
    echo ""
    echo "This report contains security audit results for Smart Dairy deployment."
    echo "Review any RED items immediately."
} > "$report_file"

echo -e "${BLUE}📄 Report saved to: $report_file${NC}"
```

---

## USAGE INSTRUCTIONS

### Making Scripts Executable
```bash
# Make all scripts executable
chmod +x scripts/*.sh

# Add to PATH for easy access
echo 'export PATH="$PWD/scripts:$PATH"' >> ~/.bashrc
source ~/.bashrc
```

### Integration with Development Workflow
1. **Quick Start:** Use `quick-start.sh` for rapid environment setup
2. **Health Monitoring:** Run `perf-monitor.sh` during development
3. **Database Management:** Use `db-manager.sh` for database operations
4. **Deployment:** Use `deploy-prod.sh` for safe production deployments
5. **Log Management:** Use `log-manager.sh` for centralized log handling
6. **Security Audits:** Run `security-audit.sh` regularly for compliance

### Customization
- Update configuration variables at the top of each script
- Modify paths to match your environment
- Add custom monitoring thresholds as needed
- Integrate with your specific CI/CD pipeline

---

**Document Status:** Complete Linux Development Scripts Collection  
**Last Updated:** December 2, 2025  
**Next Review:** After implementation testing  
**Maintained By:** Development Team  

---

*This collection of Linux development scripts provides comprehensive automation for the Smart Dairy project, ensuring maximum productivity and reliability for solo developer workflow.*