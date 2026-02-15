# Monitoring and Logging Configurations
## Smart Dairy Project - Linux-Optimized Observability

**Document Version:** 1.0  
**Date:** December 2, 2025  
**Purpose:** Complete monitoring and logging configurations for Linux production

---

## 1. PROMETHEUS CONFIGURATION

### 1.1 Main Configuration
```yaml
# monitoring/prometheus.yml - Main Prometheus configuration
global:
  scrape_interval: 15s
  evaluation_interval: 15s
  external_labels:
    cluster: 'smartdairy-prod'
    replica: 'prometheus-1'

rule_files:
  - "alert_rules.yml"

alerting:
  alertmanagers:
    - static_configs:
        - targets:
            - alertmanager:9093

scrape_configs:
  # Smart Dairy API
  - job_name: 'smartdairy-api'
    static_configs:
      - targets: ['localhost:5000']
    metrics_path: '/api/metrics'
    scrape_interval: 5s
    scrape_timeout: 10s
    scheme: 'http'
    metrics_relabel_configs:
      - source_labels: true
      - regex: 'http_method_(.+)'
        target_label: method
        replacement: '\1'

  # Next.js Frontend
  - job_name: 'smartdairy-frontend'
    static_configs:
      - targets: ['localhost:3000']
    metrics_path: '/api/metrics'
    scrape_interval: 10s
    scheme: 'http'

  # PostgreSQL Exporter
  - job_name: 'smartdairy-postgres'
    static_configs:
      - targets: ['localhost:9187']
    scrape_interval: 30s
    metrics_path: '/metrics'

  # Redis Exporter
  - job_name: 'smartdairy-redis'
    static_configs:
      - targets: ['localhost:9121']
    scrape_interval: 15s
    metrics_path: '/metrics'

  # Node.js Exporter
  - job_name: 'smartdairy-node'
    static_configs:
      - targets: ['localhost:9100']
    scrape_interval: 15s
    metrics_path: '/metrics'

  # Nginx Exporter
  - job_name: 'smartdairy-nginx'
    static_configs:
      - targets: ['localhost:9113']
    scrape_interval: 30s
    metrics_path: '/metrics'
```

### 1.2 Alert Rules
```yaml
# monitoring/alert_rules.yml - Prometheus alert rules
groups:
  - name: smartdairy.rules
    rules:
      # High CPU usage alert
      - alert: HighCPUUsage
        expr: 100 * (node_cpu_seconds_total{mode!="idle"} / node_cpu_seconds_total) > 80
        for: 5m
        labels:
          severity: warning
          service: smartdairy-api
        annotations:
          summary: "High CPU usage detected"
          description: "CPU usage is above 80% for more than 5 minutes"

      # High memory usage alert
      - alert: HighMemoryUsage
        expr: (node_memory_MemAvailable_bytes / node_memory_MemTotal_bytes) * 100 < 20
        for: 5m
        labels:
          severity: critical
          service: smartdairy-api
        annotations:
          summary: "High memory usage detected"
          description: "Available memory is below 20%"

      # Database connection errors
      - alert: DatabaseConnectionErrors
        expr: rate(postgres_stat_database_xact_rollback{datname="smartdairy_prod"}[5m]) > 0.1
        for: 2m
        labels:
          severity: critical
          service: database
        annotations:
          summary: "Database transaction rollbacks detected"
          description: "Database is experiencing transaction rollbacks"

      # High error rate
      - alert: HighErrorRate
        expr: rate(http_requests_total{status=~"5.."}[5m]) > 0.1
        for: 5m
        labels:
          severity: warning
          service: smartdairy-api
        annotations:
          summary: "High error rate detected"
          description: "Error rate is above 10%"

      # SSL certificate expiration
      - alert: SSLCertificateExpiry
        expr: probe_ssl_earliest_cert_expiry{job="smartdairy-ssl"} - time() < 86400 * 30
        for: 1h
        labels:
          severity: warning
          service: ssl
        annotations:
          summary: "SSL certificate expiring soon"
          description: "SSL certificate will expire in less than 30 days"

      # Disk space alert
      - alert: DiskSpaceLow
        expr: (node_filesystem_avail_bytes{mountpoint="/var/lib/docker"} / node_filesystem_size_bytes{mountpoint="/var/lib/docker"}) * 100 < 10
        for: 5m
        labels:
          severity: critical
          service: infrastructure
        annotations:
          summary: "Low disk space"
          description: "Available disk space is below 10%"
```

---

## 2. GRAFANA CONFIGURATION

### 2.1 Main Configuration
```yaml
# monitoring/grafana.yml - Grafana configuration
apiVersion: 1

datasources:
  # Prometheus datasource
  - name: Prometheus
    type: prometheus
    access: proxy
    url: http://prometheus:9090
    isDefault: true
    editable: true

dashboardProviders:
  - name: 'default'
    orgId: 1
    folder: ''
    type: file
    disableDeletion: false
    updateIntervalSeconds: 10
    options:
      path: /var/lib/grafana/dashboards

dashboards:
  # System Overview Dashboard
  - dashboard:
      uid: smartdairy-system-overview
      title: 'Smart Dairy System Overview'
      tags: ['system', 'overview']
      timezone: 'Asia/Dhaka'
      panels:
        - title: 'CPU Usage'
          type: graph
          targets:
            - expr: '100 * (1 - avg(rate(node_cpu_seconds_total{mode!="idle"}[5m]))'
            legendFormat: '{{percent}}'
          yAxes:
            - unit: percent
              max: 100
              min: 0

        - title: 'Memory Usage'
          type: graph
          targets:
            - expr: '(1 - (node_memory_MemAvailable_bytes{instance="smartdairy-api"} / node_memory_MemTotal_bytes{instance="smartdairy-api"})) * 100'
            legendFormat: '{{percent}}'
          yAxes:
            - unit: percent
              max: 100
              min: 0

        - title: 'Disk Usage'
          type: graph
          targets:
            - expr: '(1 - (node_filesystem_avail_bytes{mountpoint="/var/lib/docker"} / node_filesystem_size_bytes{mountpoint="/var/lib/docker"})) * 100'
            legendFormat: '{{percent}}'
          yAxes:
            - unit: percent
              max: 100
              min: 0

        - title: 'Network Traffic'
          type: graph
          targets:
            - expr: 'rate(node_network_receive_bytes_total[5m])'
            legendFormat: '{{instance}}'
          yAxes:
            - unit: bytes
              max: 1048576
              min: 0

  # Application Dashboard
  - dashboard:
      uid: smartdairy-application
      title: 'Smart Dairy Application'
      tags: ['application', 'smartdairy']
      timezone: 'Asia/Dhaka'
      panels:
        - title: 'Request Rate'
          type: graph
          targets:
            - expr: 'sum(rate(http_requests_total{job="smartdairy-api"}[5m])) by (method)'
            legendFormat: '{{method}}'
          yAxes:
            - unit: reqps
              max: 1000
              min: 0

        - title: 'Response Time'
          type: graph
          targets:
            - expr: 'histogram_quantile(0.95, sum(rate(http_request_duration_seconds_bucket{job="smartdairy-api"}[5m])) by (le)'
            legendFormat: '95th percentile'
          yAxes:
            - unit: seconds
              max: 10
              min: 0

        - title: 'Error Rate'
          type: graph
          targets:
            - expr: 'sum(rate(http_requests_total{status=~"5..",job="smartdairy-api"}[5m])) by (status)'
            legendFormat: '{{status}}'
          yAxes:
            - unit: reqps
              max: 100
              min: 0

  # Database Dashboard
  - dashboard:
      uid: smartdairy-database
      title: 'Smart Dairy Database'
      tags: ['database', 'smartdairy']
      timezone: 'Asia/Dhaka'
      panels:
        - title: 'Database Connections'
          type: graph
          targets:
            - expr: 'pg_stat_database_numbackends{datname="smartdairy_prod"}'
            legendFormat: 'Connections'
          yAxes:
            - unit: short
              max: 100
              min: 0

        - title: 'Transaction Rate'
          type: graph
          targets:
            - expr: 'rate(pg_stat_database_xact_commit{datname="smartdairy_prod"}[5m])'
            legendFormat: 'Transactions/sec'
          yAxes:
            - unit: tps
              max: 1000
              min: 0

        - title: 'Database Size'
          type: singlestat
          targets:
            - expr: 'pg_database_size_bytes{datname="smartdairy_prod"}'
            valueMaps:
              - value: 'size'
                text: 'Size'
                title: 'Database Size'
                unit: bytes
```

---

## 3. LOGGING CONFIGURATION

### 3.1 Fluentd Configuration
```conf
# logging/fluentd.conf - Centralized log collection
<source>
  @type tail
  path /var/log/smartdairy/*.log
  tag smartdairy.*
  pos_file /var/log/fluentd/smartdairy.log.pos
  read_from_head true
  skip_blank_lines true
  <parse>
    @type regexp
    expression /^(?<timestamp>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+(?<level>\w+)\s+(?<message>.*)/
    time_key timestamp
    time_format %Y-%m-%d %H:%M:%S
  </parse>
</source>

<filter smartdairy.error>
  @type grep
  regexp1 level error
  add_tag_prefix true
  tag level.error
</filter>

<filter smartdairy.warn>
  @type grep
  regexp1 level warn
  add_tag_prefix true
  tag level.warn
</filter>

<match smartdairy.**>
  @type rewrite_tag_filter
  rewriterule1 level level.${level}
  @type elasticsearch
  host elasticsearch
  port 9200
  index_name smartdairy
  type_name _doc
  include_tag_key true
  tag_key @service_name
  <buffer>
    @type file
    path /var/log/fluentd/smartdairy.buffer
    flush_mode interval
    flush_interval 5s
    flush_thread_count 2
    chunk_limit_size 2M
    queue_limit_length 8M
    retry_max_interval 30s
    retry_forever true
  </buffer>
</match>
```

### 3.2 Logrotate Configuration
```conf
# /etc/logrotate.d/smartdairy - Log rotation for Linux
/var/log/smartdairy/*.log {
    # Rotate daily
    daily
    
    # Keep 30 days of logs
    rotate 30
    
    # Compress old logs
    compress
    
    # Compress with delay
    delaycompress
    
    # If log file is empty, don't rotate
    notifempty
    
    # Create new log file with 644 permissions
    create 644
    
    # Add date extension to rotated files
    dateext
    
    # Missing logs are OK
    missingok
    
    # Run post-rotate script
    postrotate
        /usr/local/bin/restart-services.sh > /dev/null 2>&1 || true
    endscript
        # Optional: send notification to monitoring system
        /usr/local/bin/notify-logrotate.sh > /dev/null 2>&1 || true
}
```

### 3.3 Rsyslog Configuration
```conf
# /etc/rsyslog.d/smartdairy.conf - Centralized logging
# Smart Dairy application logs
$InputFileName /var/log/smartdairy/*.log
$FileCreateMode 0644
$FileOwner smartdairy
$FileGroup adm

# Define log format
$template SmartDairyFormat,"%TIMESTAMP%.%TIMESTAMP:8:rsyslog-tag%%msg:::sp-if-no-1st-sp%%msg%\n"

# Route to central log server
if $syslogserver then
    *.* @@syslogserver:10514;SmartDairyFormat
else
    # Local file logging
    *.* /var/log/smartdairy/all.log;SmartDairyFormat
    & stop
```

---

## 4. APPLICATION LOGGING

### 4.1 Winston Configuration (Node.js)
```typescript
// src/utils/logger.ts - Application logging configuration
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
            hostname: require('os').hostname(),
            service: 'smartdairy-api'
        });
    })
);

// Create logger with multiple transports
const logger = winston.createLogger({
    level: process.env.LOG_LEVEL || 'info',
    format: customFormat,
    defaultMeta: { service: 'smartdairy-api' },
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

// Performance logging
export const performanceLogger = winston.createLogger({
    level: 'info',
    format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.json()
    ),
    defaultMeta: { service: 'smartdairy-performance' },
    transports: [
        new winston.transports.File({
            filename: path.join(logDir, 'performance.log'),
            maxsize: 52428800, // 50MB
            maxFiles: 3,
        })
    ]
});

// Security logging
export const securityLogger = winston.createLogger({
    level: 'warn',
    format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.json()
    ),
    defaultMeta: { service: 'smartdairy-security' },
    transports: [
        new winston.transports.File({
            filename: path.join(logDir, 'security.log'),
            maxsize: 10485760, // 10MB
            maxFiles: 5,
        })
    ]
});

export default logger;
```

### 4.2 Request Logging Middleware
```typescript
// src/middleware/requestLogger.ts - HTTP request logging
import { Request, Response, NextFunction } from 'express';
import logger from '../utils/logger';

export const requestLogger = (req: Request, res: Response, next: NextFunction) => {
    const start = Date.now();
    
    // Log request start
    logger.info('HTTP Request Started', {
        method: req.method,
        url: req.url,
        userAgent: req.get('User-Agent'),
        ip: req.ip || req.connection.remoteAddress,
        requestId: req.headers['x-request-id']
    });
    
    // Capture response
    const originalSend = res.send;
    const originalJson = res.json;
    
    res.send = function(data) {
        const duration = Date.now() - start;
        
        logger.info('HTTP Request Completed', {
            method: req.method,
            url: req.url,
            statusCode: res.statusCode,
            duration: `${duration}ms`,
            userAgent: req.get('User-Agent'),
            ip: req.ip || req.connection.remoteAddress,
            requestId: req.headers['x-request-id'],
            responseSize: JSON.stringify(data).length
        });
        
        return originalSend.call(this, data);
    };
    
    res.json = function(data) {
        const duration = Date.now() - start;
        
        logger.info('HTTP Request Completed', {
            method: req.method,
            url: req.url,
            statusCode: res.statusCode,
            duration: `${duration}ms`,
            userAgent: req.get('User-Agent'),
            ip: req.ip || req.connection.remoteAddress,
            requestId: req.headers['x-request-id'],
            responseSize: JSON.stringify(data).length
        });
        
        return originalJson.call(this, data);
    };
    
    next();
};
```

---

## 5. MONITORING SCRIPTS

### 5.1 Service Health Monitor
```bash
#!/bin/bash
# scripts/monitor-services.sh - Service health monitoring

set -e

# Configuration
SERVICES=(
    "smartdairy-frontend:3000:Frontend"
    "smartdairy-backend:5000:API"
    "smartdairy-cms:1337:CMS"
    "smartdairy-postgres:5432:Database"
    "smartdairy-redis:6379:Cache"
    "smartdairy-nginx:80:Web Server"
)

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Function to check service health
check_service() {
    local service_info=$1
    local name=$(echo $service_info | cut -d: -f1)
    local port=$(echo $service_info | cut -d: -f2)
    local path=$(echo $service_info | cut -d: -f3)
    
    echo -n "🔍 $name: "
    
    if curl -f -s --max-time 5 "http://localhost:$port$path" > /dev/null; then
        echo -e "${GREEN}HEALTHY${NC}"
        return 0
    else
        echo -e "${RED}UNHEALTHY${NC}"
        return 1
    fi
}

# Function to get service metrics
get_metrics() {
    local service_info=$1
    local name=$(echo $service_info | cut -d: -f1)
    local port=$(echo $service_info | cut -d: -f2)
    
    case $name in
        "Frontend")
            # Get page load time
            load_time=$(curl -o /dev/null -s -w "%{time_total}" "http://localhost:$port" 2>/dev/null)
            echo -e "Load Time: ${GREEN}${load_time}s${NC}"
            ;;
        "API")
            # Get API response time
            response_time=$(curl -o /dev/null -s -w "%{time_total}" "http://localhost:$port/api/health" 2>/dev/null)
            echo -e "Response Time: ${GREEN}${response_time}s${NC}"
            ;;
        "CMS")
            # Get CMS response time
            response_time=$(curl -o /dev/null -s -w "%{time_total}" "http://localhost:$port/admin" 2>/dev/null)
            echo -e "Response Time: ${GREEN}${response_time}s${NC}"
            ;;
        "Database")
            # Get connection count
            connections=$(psql -h localhost -U smart_dairy_user -d smart_dairy_prod -t -c "
                SELECT count(*) FROM pg_stat_activity WHERE state = 'active';
            " 2>/dev/null || echo "N/A")
            echo -e "Active Connections: ${GREEN}$connections${NC}"
            ;;
        "Cache")
            # Get Redis info
            info=$(redis-cli info memory | grep -E "(used_memory_human|connected_clients)")
            echo -e "$info"
            ;;
    esac
}

# Main monitoring loop
echo -e "${BLUE}📊 Smart Dairy Service Monitor${NC}"
echo "================================="

while true; do
    clear
    
    echo -e "${BLUE}Service Health Status${NC}"
    echo "========================="
    
    all_healthy=true
    
    for service in "${SERVICES[@]}"; do
        if ! check_service "$service"; then
            all_healthy=false
        fi
    done
    
    echo ""
    
    if $all_healthy; then
        echo -e "${GREEN}✅ All services are healthy${NC}"
    else
        echo -e "${RED}❌ Some services are unhealthy${NC}"
    fi
    
    echo ""
    echo -e "${BLUE}Service Metrics${NC}"
    echo "===================="
    
    for service in "${SERVICES[@]}"; do
        get_metrics "$service"
    done
    
    echo ""
    echo -e "${CYAN}Last updated: $(date '+%H:%M:%S')${NC}"
    echo "Press Ctrl+C to exit"
    
    sleep 10
done
```

### 5.2 Log Analyzer
```bash
#!/bin/bash
# scripts/analyze-logs.sh - Log analysis for troubleshooting

set -e

# Configuration
LOG_DIR="/var/log/smartdairy"
ANALYSIS_DIR="/tmp/log-analysis"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

show_menu() {
    echo -e "${BLUE}📋 Smart Dairy Log Analyzer${NC}"
    echo "==============================="
    echo "1. 🔍 Search for errors"
    echo "2. 📊 Analyze traffic patterns"
    echo "3. 📈 Generate statistics"
    echo "4. 🔥 Find slow requests"
    echo "5. 🛡️  Security analysis"
    echo "6. 📁 Real-time log tail"
    echo "0. 🚪 Exit"
    echo ""
    echo -n "Select option: "
}

search_errors() {
    echo -e "${BLUE}🔍 Searching for errors in last 24 hours${NC}"
    echo "=================================="
    
    # Search for error patterns
    grep -h "Error\|Exception\|Failed\|CRITICAL" -i "$LOG_DIR"/*.log \
        --include="*.log" \
        --exclude="*.gz" \
        --max-count=50
    
    echo ""
    echo -e "${BLUE}📊 Error Summary${NC}"
    echo "=================="
    
    # Count errors by type
    echo "HTTP Errors:"
    grep -c "HTTP.*[45].." "$LOG_DIR"/*.log 2>/dev/null || echo "0"
    
    echo "Application Errors:"
    grep -c "Error\|Exception" "$LOG_DIR"/*.log 2>/dev/null || echo "0"
    
    echo "Database Errors:"
    grep -c "database\|connection\|timeout" "$LOG_DIR"/*.log 2>/dev/null || echo "0"
}

analyze_traffic() {
    echo -e "${BLUE}📊 Analyzing traffic patterns${NC}"
    echo "================================="
    
    # Analyze API logs
    echo "Top 10 API endpoints by requests:"
    grep -o '"GET\|POST\|PUT\|DELETE"' "$LOG_DIR/backend.log" \
        | awk '{print $7}' | sort | uniq -c | head -10
    
    echo ""
    echo "Top 10 IP addresses by requests:"
    grep -o '"client_ip"' "$LOG_DIR/backend.log" \
        | awk '{print $7}' | sort | uniq -c | head -10
    
    echo ""
    echo "Response time distribution:"
    grep -o '"duration"' "$LOG_DIR/backend.log" \
        | awk '{print $7}' | sort -n | \
        awk 'BEGIN {count=0; sum=0} {count++; sum+=$1} END {print "Count: "count", "Avg: "sum/count" "ms"}'
}

generate_statistics() {
    echo -e "${BLUE}📈 Generating log statistics${NC}"
    echo "=================================="
    
    # Create analysis directory
    mkdir -p "$ANALYSIS_DIR"
    
    # Generate statistics for each log file
    for log_file in "$LOG_DIR"/*.log; do
        if [ -f "$log_file" ]; then
            echo -e "${BLUE}Analyzing $(basename $log_file)...${NC}"
            
            # Basic statistics
            lines=$(wc -l < "$log_file")
            size=$(du -h "$log_file" | cut -f1)
            
            echo "Lines: $lines"
            echo "Size: $size"
            
            # Error count
            errors=$(grep -c "ERROR\|CRITICAL" "$log_file" || echo "0")
            echo "Errors: $errors"
            
            # Warning count
            warnings=$(grep -c "WARN\|WARNING" "$log_file" || echo "0")
            echo "Warnings: $warnings"
            
            # Generate hourly distribution
            echo "Hourly distribution:"
            grep -o "^$(date +%Y-%m-%d)" "$log_file" | cut -d: -f2 | cut -d: -f3 | sort | uniq -c | head -10
        fi
    done
    
    echo -e "${GREEN}✅ Statistics generated in $ANALYSIS_DIR${NC}"
}

find_slow_requests() {
    echo -e "${BLUE}🔥 Finding slow requests (>1s)${NC}"
    echo "=================================="
    
    grep -o '"duration".*[1-9][0-9]*" "$LOG_DIR/backend.log" \
        | awk -F'"' '$7 > 1000 {print "Slow Request: " $0 " - $7 "ms (" $1 ")"}' \
        | sort -nr -k2,2 | head -10
}

security_analysis() {
    echo -e "${BLUE}🛡️  Security Analysis${NC}"
    echo "========================"
    
    echo "Failed login attempts:"
    grep -i "login.*fail\|auth.*error\|unauthorized" "$LOG_DIR/backend.log" \
        | tail -20
    
    echo ""
    echo "Suspicious IP addresses (>100 requests/hour):"
    
    # Get IPs with high request rates
    grep -o '"client_ip"' "$LOG_DIR/backend.log" \
        | awk '{print $7}' | sort | uniq -c | \
        | awk '$1 > 100 {print "Suspicious IP: " $1 " - $1 " requests"}'
    
    echo ""
    echo "Potential SQL injection attempts:"
    grep -i "union\|select.*from\|drop table\|insert into" "$LOG_DIR/backend.log" \
        | tail -10
}

real_time_logs() {
    echo -e "${BLUE}📁 Real-time log monitoring${NC}"
    echo "================================"
    echo "Press Ctrl+C to stop"
    echo ""
    
    # Tail all log files
    tail -f "$LOG_DIR"/*.log
}

# Main menu loop
while true; do
    show_menu
    read choice
    
    case $choice in
        1) search_errors ;;
        2) analyze_traffic ;;
        3) generate_statistics ;;
        4) find_slow_requests ;;
        5) security_analysis ;;
        6) real_time_logs ;;
        0) echo -e "${GREEN}👋 Goodbye!${NC}"; exit 0 ;;
        *) echo -e "${RED}❌ Invalid option${NC}" ;;
    esac
    
    echo ""
    echo -n "Press Enter to continue..."
    read
done
```

---

## 6. DEPLOYMENT CONFIGURATIONS

### 6.1 Monitoring Stack Deployment
```yaml
# monitoring/docker-compose.monitoring.yml - Complete monitoring stack
version: '3.8'

services:
  # Prometheus
  prometheus:
    image: prom/prometheus:latest
    container_name: smartdairy-prometheus
    ports:
      - "9090:9090"
    volumes:
      - ./monitoring/prometheus.yml:/etc/prometheus/prometheus.yml:ro
      - prometheus-data:/prometheus
      - ./monitoring/alert_rules.yml:/etc/prometheus/alert_rules.yml:ro
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.path=/prometheus'
      - '--web.console.libraries=/usr/share/prometheus/web/ui/libs'
      - '--web.console.templates=/usr/share/prometheus/consoles'
    networks:
      - monitoring
    restart: unless-stopped

  # Grafana
  grafana:
    image: grafana/grafana:latest
    container_name: smartdairy-grafana
    ports:
      - "3001:3000"
    volumes:
      - grafana-data:/var/lib/grafana
      - ./monitoring/grafana.yml:/etc/grafana/provisioning:ro
    environment:
      - GF_SECURITY_ADMIN_PASSWORD: ${GRAFANA_PASSWORD}
      - GF_USERS_ALLOW_SIGN_UP: false
      - GF_SERVER_DOMAIN: smartdairy.com
      - GF_SERVER_ROOT_URL: https://smartdairy.com/grafana
    networks:
      - monitoring
    restart: unless-stopped
    depends_on:
      - prometheus

  # Node Exporter
  node-exporter:
    image: prom/node-exporter:latest
    container_name: smartdairy-node-exporter
    ports:
      - "9100:9100"
    volumes:
      - /proc:/host/proc:ro
      - /sys:/host/sys:ro
      - /:/rootfs:ro
    networks:
      - monitoring
    restart: unless-stopped

  # PostgreSQL Exporter
  postgres-exporter:
    image: prometheuscommunity/postgres-exporter:latest
    container_name: smartdairy-postgres-exporter
    ports:
      - "9187:9187"
    environment:
      DATA_SOURCE_NAME: smart-dairy-postgres
      DATA_SOURCE_URI: postgres://smart_dairy_user:${DB_PASSWORD}@postgres:5432/smart_dairy_prod?sslmode=disable
    networks:
      - monitoring
      - smartdairy-network
    restart: unless-stopped
    depends_on:
      - postgres

  # Redis Exporter
  redis-exporter:
    image: oliver006/redis_exporter:latest
    container_name: smartdairy-redis-exporter
    ports:
      - "9121:9121"
    environment:
      REDIS_ADDR: redis://redis:6379
    networks:
      - monitoring
      - smartdairy-network
    restart: unless-stopped
    depends_on:
      - redis

  # Nginx Exporter
  nginx-exporter:
    image: nginx/nginx-prometheus-exporter:latest
    container_name: smartdairy-nginx-exporter
    ports:
      - "9113:9113"
    environment:
      SCRAPE_URI: http://nginx:80/nginx_status
    networks:
      - monitoring
      - smartdairy-network
    restart: unless-stopped
    depends_on:
      - nginx

volumes:
  prometheus-data:
    driver: local
  grafana-data:
    driver: local

networks:
  monitoring:
    driver: bridge
  smartdairy-network:
    external: true
```

---

## USAGE INSTRUCTIONS

### Quick Start Commands
```bash
# Start monitoring stack
docker-compose -f monitoring/docker-compose.monitoring.yml up -d

# View logs
docker-compose -f monitoring/docker-compose.monitoring.yml logs -f prometheus

# Access Grafana
echo "Grafana: http://localhost:3001"
echo "Prometheus: http://localhost:9090"
```

### Integration with Alerting
1. **Email Alerts:** Configure SMTP in Grafana
2. **Slack Alerts:** Set up webhook integration
3. **SMS Alerts:** Integrate with Bangladesh SMS gateway
4. **PagerDuty:** Configure for critical alerts

### Log Retention Policy
- **Application logs:** 30 days
- **Access logs:** 90 days
- **Audit logs:** 365 days
- **Metrics data:** 15 days

### Monitoring Best Practices
1. **Set meaningful alert thresholds**
2. **Monitor business metrics, not just system metrics**
3. **Create dashboards for different user roles**
4. **Regularly review and update alert rules**
5. **Document all monitoring procedures**

---

**Document Status:** Complete Monitoring and Logging Configuration  
**Last Updated:** December 2, 2025  
**Next Review:** After implementation testing  
**Maintained By:** Development Team  

---

*This comprehensive monitoring and logging configuration provides complete observability for Smart Dairy project, ensuring proactive issue detection and performance optimization on Linux infrastructure.*