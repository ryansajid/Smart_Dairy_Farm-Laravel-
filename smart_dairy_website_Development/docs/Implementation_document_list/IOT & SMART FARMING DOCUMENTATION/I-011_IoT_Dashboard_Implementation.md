# I-011: IoT Dashboard Implementation

## Smart Dairy Ltd. - IoT & Smart Farming Documentation

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | I-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Frontend Developer |
| **Owner** | IoT Architect |
| **Reviewer** | Farm Operations Manager |
| **Status** | Approved |
| **Classification** | Internal Use |

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Frontend Developer | Initial Release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Dashboard Types](#2-dashboard-types)
3. [Technology Stack](#3-technology-stack)
4. [Real-time Data](#4-real-time-data)
5. [Visualization Components](#5-visualization-components)
6. [Dashboard Layout](#6-dashboard-layout)
7. [Data Sources](#7-data-sources)
8. [Alert Visualization](#8-alert-visualization)
9. [Historical Analysis](#9-historical-analysis)
10. [Export Features](#10-export-features)
11. [User Authentication](#11-user-authentication)
12. [Performance Optimization](#12-performance-optimization)
13. [Mobile App Integration](#13-mobile-app-integration)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the comprehensive IoT Dashboard Implementation for Smart Dairy Ltd., providing real-time visualization of farm operations, animal health, milk production, and environmental conditions. The dashboard serves as the primary interface for farm managers, veterinarians, and operations staff to monitor and manage dairy operations.

### 1.2 Dashboard Objectives

| Objective | Description | Success Metric |
|-----------|-------------|----------------|
| **Real-time Monitoring** | Display live sensor data with < 5 second latency | 99.9% uptime, < 3s avg latency |
| **Operational Visibility** | Complete overview of all farm operations | 100% critical metrics coverage |
| **Health Insights** | Early detection of animal health issues | < 15 min alert response time |
| **Production Tracking** | Monitor milk yields and quality metrics | ±2% accuracy vs actual |
| **Environmental Control** | Climate monitoring and automation status | Automated response < 2 min |
| **Decision Support** | Data-driven insights for farm management | 30-day trend analysis available |

### 1.3 User Personas

| Persona | Role | Primary Dashboard | Key Needs |
|---------|------|-------------------|-----------|
| **Farm Manager** | Operations oversight | Farm Operations Dashboard | Overall status, alerts, production summaries |
| **Veterinarian** | Animal health monitoring | Animal Health Dashboard | Individual animal metrics, health alerts |
| **Milking Supervisor** | Production oversight | Milk Production Dashboard | Yields per cow, quality metrics, parlor status |
| **Maintenance Staff** | Equipment monitoring | Environmental Dashboard | Sensor status, equipment health, climate control |
| **Field Worker** | Mobile operations | Mobile App Dashboard | Quick status checks, alert acknowledgment |
| **Executive** | Business oversight | Executive Summary Dashboard | KPIs, trends, comparative analytics |

### 1.4 Dashboard Requirements

| Requirement | Specification | Implementation |
|-------------|---------------|----------------|
| **Real-time Updates** | < 5 second latency | WebSocket + Redis pub/sub |
| **Historical Data** | 30-day minimum retention | TimescaleDB continuous aggregates |
| **Responsive Design** | Tablet-first for barn use | CSS Grid + Flexbox, touch-optimized |
| **Offline Indicator** | Visual status when disconnected | Connection health monitor |
| **Dark Mode** | Night viewing support | CSS variables + localStorage preference |
| **Mobile Support** | iOS/Android compatibility | React Native widgets, PWA support |

---

## 2. Dashboard Types

### 2.1 Farm Operations Dashboard

The primary dashboard for real-time barn status monitoring.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FARM OPERATIONS DASHBOARD                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐           │
│  │   COWS      │ │  MILK TODAY │ │   ALERTS    │ │  EFFICIENCY │           │
│  │  147/150    │ │  2,847 L    │ │   3 Active  │ │    94.2%    │           │
│  │   Online    │ │   +12.5%    │ │  1 Critical │ │   Target    │           │
│  └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘           │
│                                                                              │
│  ┌─────────────────────────────┐  ┌─────────────────────────────┐          │
│  │      BARN STATUS MAP        │  │    MILKING PARLOR STATUS    │          │
│  │                             │  │  ┌────┐┌────┐┌────┐┌────┐  │          │
│  │   [Zone A: 22°C 65% RH]     │  │  │ 🥛 ││ 🥛 ││ ⏸️ ││ 🥛 │  │          │
│  │   [Zone B: 23°C 62% RH]     │  │  │ #1 ││ #2 ││ #3 ││ #4 │  │          │
│  │   [Zone C: 21°C 68% RH]     │  │  └────┘└────┘└────┘└────┘  │          │
│  │   [Zone D: 24°C 70% RH]     │  │  ┌────┐┌────┐┌────┐┌────┐  │          │
│  │                             │  │  │ 🥛 ││ 🐄 ││ 🥛 ││ ⏸️ │  │          │
│  │  🟢 Normal  🟡 Warning  🔴 Alert                                          │
│  └─────────────────────────────┘  └─────────────────────────────┘          │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────┐            │
│  │                 LIVE SENSOR FEEDS                            │            │
│  │  Temperature  [████████████████████░░░░░░░░] 22.5°C          │            │
│  │  Humidity     [████████████████░░░░░░░░░░░░] 65.0%           │            │
│  │  NH3 Level    [████████░░░░░░░░░░░░░░░░░░░░] 8.2 ppm         │            │
│  │  Ventilation  [████████████████████████░░░░] 82% Speed       │            │
│  └─────────────────────────────────────────────────────────────┘            │
└─────────────────────────────────────────────────────────────────────────────┘
```

**Key Metrics:**
- Total cows online/total
- Daily milk production with trend
- Active alerts by severity
- Milking parlor utilization
- Environmental averages by zone

### 2.2 Animal Health Dashboard

Individual animal metrics for veterinary monitoring.

| Component | Description | Update Frequency |
|-----------|-------------|------------------|
| **Animal List** | Sortable/filterable list of all animals | Real-time |
| **Health Score** | Composite score (0-100) per animal | Every 5 minutes |
| **Activity Graph** | 24-hour activity timeline | Live updates |
| **Rumination Chart** | Minutes per 2-hour window | Hourly aggregation |
| **Temperature Trend** | Body temperature over time | Every reading |
| **Alert History** | Past health alerts for animal | On-demand |

**Health Status Indicators:**

```typescript
interface HealthStatus {
  cow_id: string;
  overall_score: number;        // 0-100 composite
  activity_level: 'low' | 'normal' | 'high';
  rumination_status: 'poor' | 'reduced' | 'normal' | 'excellent';
  body_temp_status: 'low' | 'normal' | 'elevated' | 'fever';
  last_update: Date;
  alerts: HealthAlert[];
}
```

### 2.3 Milk Production Dashboard

Comprehensive milk yield and quality tracking.

**Dashboard Sections:**

1. **Today's Production**
   - Current session volume
   - Cows milked / remaining
   - Average yield per cow
   - Fat/protein averages

2. **Historical Trends**
   - 7-day production chart
   - 30-day rolling average
   - Comparison to target
   - Session breakdown (morning/evening)

3. **Quality Metrics**
   - Fat content trend
   - Protein content trend
   - Somatic cell count (SCC)
   - Quality grade distribution

4. **Individual Cow Performance**
   - Top producers
   - Declining performers
   - First-lactation tracking

### 2.4 Environmental Dashboard

Climate control and barn environment monitoring.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ENVIRONMENTAL DASHBOARD                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                     BARN HEAT MAP                                    │   │
│  │                                                                      │   │
│  │   Zone A    Zone B    Zone C    Zone D    Zone E                    │   │
│  │  ┌─────┐  ┌─────┐  ┌─────┐  ┌─────┐  ┌─────┐                       │   │
│  │  │ 22° │  │ 23° │  │ 24° │  │ 21° │  │ 22° │  Temperature          │   │
│  │  │ 65% │  │ 62% │  │ 70% │  │ 68% │  │ 64% │  Humidity             │   │
│  │  │ 🟢  │  │ 🟢  │  │ 🟡  │  │ 🟢  │  │ 🟢  │  Status               │   │
│  │  └─────┘  └─────┘  └─────┘  └─────┘  └─────┘                       │   │
│  │                                                                      │   │
│  │  Legend: 🟢 Optimal (20-24°C, 50-70%)                               │   │
│  │          🟡 Warning (24-28°C, >70%)                                 │   │
│  │          🔴 Critical (>28°C, >80%)                                  │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌────────────────────────┐  ┌────────────────────────┐                   │
│  │   TEMPERATURE TREND    │  │    HUMIDITY TREND      │                   │
│  │                        │  │                        │                   │
│  │  25° ┤        ╭─╮      │  │  80% ┤    ╭──╮        │                   │
│  │  24° ┤   ╭──╮ │ │      │  │  70% ┤╭──╯  ╰──╮      │                   │
│  │  23° ┤╭──╯  ╰─╯ ╰──╮   │  │  60% ┤╯        ╰──╮    │                   │
│  │  22° ┤╯            ╰─  │  │  50% ┤            ╰─   │                   │
│  │      └───────────────  │  │      └───────────────  │                   │
│  │      00 06 12 18 24    │  │      00 06 12 18 24    │                   │
│  └────────────────────────┘  └────────────────────────┘                   │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │              ENVIRONMENTAL CONTROL SYSTEMS                           │   │
│  │                                                                      │   │
│  │  Ventilation  │  Sprinklers  │  Curtains  │  Feeders                │   │
│  │  [AUTO] 82%   │  [OFF]       │  [AUTO] 45%│  [ON] 12:30            │   │
│  │  Mode: Auto   │  Last: 2h ago│  Opening   │  Next: 18:00           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.5 Alert Management Dashboard

Centralized alert monitoring and management interface.

| Feature | Description |
|---------|-------------|
| **Alert Feed** | Chronological list of all alerts with filtering |
| **Severity Distribution** | Pie chart of alert types by severity |
| **Alert Trends** | Alert frequency over time |
| **Acknowledgment Queue** | Alerts awaiting response |
| **Resolution Tracking** | Time to resolve by alert type |
| **Escalation Status** | Alerts escalated to management |

---

## 3. Technology Stack

### 3.1 Overview

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Dashboard Framework** | Grafana | 10.x | Primary visualization platform |
| **Custom UI** | React | 18.x | Custom widgets and interactions |
| **State Management** | Zustand | 4.x | Client-side state |
| **Real-time Communication** | WebSocket | Native | Live data updates |
| **Charts** | Chart.js | 4.x | Time-series and statistical charts |
| **Maps** | Leaflet | 1.9.x | Barn zone visualization |
| **Styling** | Tailwind CSS | 3.x | Component styling |
| **Build Tool** | Vite | 5.x | Development and production builds |

### 3.2 Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         DASHBOARD ARCHITECTURE                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                        PRESENTATION LAYER                            │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │   Grafana    │  │ React Custom │  │  Mobile App  │              │   │
│  │  │ Dashboards   │  │   Widgets    │  │  Dashboard   │              │   │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘              │   │
│  │         │                 │                 │                       │   │
│  │         └─────────────────┼─────────────────┘                       │   │
│  │                           │                                          │   │
│  │  ┌────────────────────────┴────────────────────────┐                │   │
│  │  │              WEBSOCKET GATEWAY                   │                │   │
│  │  │         (Socket.io / Native WS)                  │                │   │
│  │  └────────────────────────┬────────────────────────┘                │   │
│  └───────────────────────────┼────────────────────────────────────────┘   │
│                              │                                              │
│  ┌───────────────────────────┼────────────────────────────────────────┐   │
│  │                         │                                           │   │
│  │  ┌──────────────────────▼──────────────────────┐                  │   │
│  │  │              API GATEWAY                     │                  │   │
│  │  │           (FastAPI / Node.js)                │                  │   │
│  │  └──────────────┬────────────────┬─────────────┘                  │   │
│  │                 │                │                                 │   │
│  │  ┌──────────────▼──┐  ┌──────────▼─────────┐  ┌──────────────┐   │   │
│  │  │   TimescaleDB   │  │      Redis         │  │    Kafka     │   │   │
│  │  │  (Time-series)  │  │    (Pub/Sub)       │  │  (Streaming) │   │   │
│  │  └─────────────────┘  └────────────────────┘  └──────────────┘   │   │
│  │                                                                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.3 Grafana Integration

**Grafana Configuration:**

```yaml
# docker-compose.grafana.yml
version: '3.8'

services:
  grafana:
    image: grafana/grafana:10.2.3
    container_name: smart-dairy-grafana
    ports:
      - "3000:3000"
    environment:
      - GF_SECURITY_ADMIN_USER=admin
      - GF_SECURITY_ADMIN_PASSWORD=${GRAFANA_ADMIN_PASSWORD}
      - GF_INSTALL_PLUGINS=grafana-clock-panel,grafana-simple-json-datasource
      - GF_AUTH_GENERIC_OAUTH_ENABLED=true
      - GF_AUTH_GENERIC_OAUTH_NAME=SmartDairy
      - GF_AUTH_GENERIC_OAUTH_ALLOW_SIGN_UP=true
      - GF_AUTH_GENERIC_OAUTH_CLIENT_ID=${OAUTH_CLIENT_ID}
      - GF_AUTH_GENERIC_OAUTH_CLIENT_SECRET=${OAUTH_CLIENT_SECRET}
      - GF_AUTH_GENERIC_OAUTH_SCOPES=openid email profile
      - GF_AUTH_GENERIC_OAUTH_AUTH_URL=https://auth.smartdairybd.com/oauth/authorize
      - GF_AUTH_GENERIC_OAUTH_TOKEN_URL=https://auth.smartdairybd.com/oauth/token
      - GF_AUTH_GENERIC_OAUTH_API_URL=https://auth.smartdairybd.com/oauth/userinfo
      - GF_DASHBOARDS_DEFAULT_HOME_DASHBOARD_PATH=/etc/grafana/dashboards/farm-operations.json
    volumes:
      - grafana-storage:/var/lib/grafana
      - ./grafana/dashboards:/etc/grafana/dashboards
      - ./grafana/datasources:/etc/grafana/provisioning/datasources
      - ./grafana/plugins:/var/lib/grafana/plugins
    networks:
      - iot-network

volumes:
  grafana-storage:

networks:
  iot-network:
    external: true
```

---

## 4. Real-time Data

### 4.1 WebSocket Implementation

**Server-side WebSocket Handler (Node.js/Socket.io):**

```typescript
// websocket-server.ts
import { Server } from 'socket.io';
import { createAdapter } from '@socket.io/redis-adapter';
import Redis from 'ioredis';
import { TimescaleDB } from './db/timescale';

const io = new Server({
  cors: {
    origin: ["https://dashboard.smartdairybd.com", "http://localhost:3000"],
    credentials: true
  },
  pingTimeout: 60000,
  pingInterval: 25000
});

// Redis adapter for multi-server scaling
const pubClient = new Redis(process.env.REDIS_URL);
const subClient = pubClient.duplicate();
io.adapter(createAdapter(pubClient, subClient));

// Farm isolation middleware
io.use(async (socket, next) => {
  const token = socket.handshake.auth.token;
  try {
    const user = await verifyToken(token);
    socket.data.user = user;
    socket.data.farmId = user.farm_id;
    next();
  } catch (err) {
    next(new Error('Authentication error'));
  }
});

io.on('connection', (socket) => {
  const farmId = socket.data.farmId;
  
  // Join farm-specific room for targeted updates
  socket.join(`farm:${farmId}`);
  
  console.log(`Client connected: ${socket.id} (Farm: ${farmId})`);
  
  // Send initial data snapshot
  sendInitialData(socket, farmId);
  
  // Handle subscription requests
  socket.on('subscribe:dashboard', (dashboardType) => {
    socket.join(`dashboard:${farmId}:${dashboardType}`);
  });
  
  socket.on('unsubscribe:dashboard', (dashboardType) => {
    socket.leave(`dashboard:${farmId}:${dashboardType}`);
  });
  
  // Handle alert acknowledgment
  socket.on('alert:acknowledge', async (alertId) => {
    await acknowledgeAlert(alertId, socket.data.user.id);
    io.to(`farm:${farmId}`).emit('alert:updated', { alertId, status: 'acknowledged' });
  });
  
  socket.on('disconnect', () => {
    console.log(`Client disconnected: ${socket.id}`);
  });
});

// Broadcast sensor updates from Kafka consumer
export function broadcastSensorUpdate(farmId: string, sensorData: SensorReading) {
  io.to(`farm:${farmId}`).emit('sensor:update', {
    timestamp: new Date().toISOString(),
    data: sensorData
  });
}

// Broadcast alert to specific farm
export function broadcastAlert(farmId: string, alert: Alert) {
  io.to(`farm:${farmId}`).emit('alert:new', alert);
}

async function sendInitialData(socket: Socket, farmId: string) {
  // Send current barn status
  const barnStatus = await TimescaleDB.getLatestBarnStatus(farmId);
  socket.emit('barn:status', barnStatus);
  
  // Send active alerts
  const activeAlerts = await TimescaleDB.getActiveAlerts(farmId);
  socket.emit('alerts:initial', activeAlerts);
  
  // Send today's production summary
  const productionSummary = await TimescaleDB.getTodayProduction(farmId);
  socket.emit('production:summary', productionSummary);
}

io.listen(3001);
```

**Client-side WebSocket Hook (React):**

```typescript
// hooks/useRealtimeData.ts
import { useEffect, useState, useCallback } from 'react';
import { io, Socket } from 'socket.io-client';
import { useAuth } from './useAuth';

interface UseRealtimeDataOptions {
  dashboardType?: 'operations' | 'health' | 'production' | 'environmental';
  autoConnect?: boolean;
}

export function useRealtimeData(options: UseRealtimeDataOptions = {}) {
  const { dashboardType = 'operations', autoConnect = true } = options;
  const { token, farmId } = useAuth();
  const [socket, setSocket] = useState<Socket | null>(null);
  const [isConnected, setIsConnected] = useState(false);
  const [connectionQuality, setConnectionQuality] = useState<'good' | 'poor' | 'offline'>('offline');
  const [lastUpdate, setLastUpdate] = useState<Date | null>(null);
  const [latency, setLatency] = useState<number>(0);

  useEffect(() => {
    if (!autoConnect || !token) return;

    const newSocket = io(process.env.REACT_APP_WS_URL || 'wss://ws.smartdairybd.com', {
      auth: { token },
      transports: ['websocket', 'polling'],
      reconnection: true,
      reconnectionDelay: 1000,
      reconnectionDelayMax: 5000,
      reconnectionAttempts: 10
    });

    newSocket.on('connect', () => {
      setIsConnected(true);
      setConnectionQuality('good');
      newSocket.emit('subscribe:dashboard', dashboardType);
    });

    newSocket.on('disconnect', () => {
      setIsConnected(false);
      setConnectionQuality('offline');
    });

    newSocket.on('connect_error', () => {
      setConnectionQuality('poor');
    });

    // Latency measurement
    const pingInterval = setInterval(() => {
      const start = Date.now();
      newSocket.emit('ping', () => {
        setLatency(Date.now() - start);
      });
    }, 5000);

    setSocket(newSocket);

    return () => {
      clearInterval(pingInterval);
      newSocket.close();
    };
  }, [token, dashboardType, autoConnect]);

  // Generic data subscription hook
  const useDataChannel = <T>(channel: string) => {
    const [data, setData] = useState<T | null>(null);

    useEffect(() => {
      if (!socket) return;

      const handler = (newData: T) => {
        setData(newData);
        setLastUpdate(new Date());
      };

      socket.on(channel, handler);
      return () => {
        socket.off(channel, handler);
      };
    }, [socket, channel]);

    return data;
  };

  const acknowledgeAlert = useCallback((alertId: string) => {
    socket?.emit('alert:acknowledge', alertId);
  }, [socket]);

  return {
    socket,
    isConnected,
    connectionQuality,
    lastUpdate,
    latency,
    useDataChannel,
    acknowledgeAlert
  };
}
```

### 4.2 Live Update Mechanism

**Data Flow for Real-time Updates:**

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Sensor    │────►│ MQTT Broker │────►│   Kafka     │────►│  Consumer   │
│  (Edge)     │     │   (EMQX)    │     │  (Topics)   │     │  (Node.js)  │
└─────────────┘     └─────────────┘     └─────────────┘     └──────┬──────┘
                                                                   │
                                                                   ▼
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   React     │◄────│  WebSocket  │◄────│  Socket.io  │◄────│   Redis     │
│  Dashboard  │     │   Server    │     │   Adapter   │     │   Pub/Sub   │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
```

**Update Rate Configuration:**

| Data Type | Source Frequency | Dashboard Update | Throttling |
|-----------|------------------|------------------|------------|
| Environmental | Every 30 seconds | Every 30 seconds | None |
| Milk Production | Per milking event | Real-time | None |
| Animal Health | Every 5 minutes | Every 5 minutes | None |
| RFID Events | Per scan | Real-time | None |
| Alerts | Immediate | Immediate | 1 second debounce |
| KPIs | Calculated | Every 60 seconds | Cache 30s |

---

## 5. Visualization Components

### 5.1 Gauges

**Temperature Gauge Component (React + Chart.js):**

```typescript
// components/gauges/TemperatureGauge.tsx
import React from 'react';
import { Doughnut } from 'react-chartjs-2';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

interface TemperatureGaugeProps {
  value: number;
  min?: number;
  max?: number;
  optimalRange?: [number, number];
  unit?: string;
  label?: string;
  size?: 'sm' | 'md' | 'lg';
}

export const TemperatureGauge: React.FC<TemperatureGaugeProps> = ({
  value,
  min = -10,
  max = 50,
  optimalRange = [20, 24],
  unit = '°C',
  label = 'Temperature',
  size = 'md'
}) => {
  const getColor = (val: number): string => {
    if (val < optimalRange[0] - 5 || val > optimalRange[1] + 5) return '#ef4444'; // Red
    if (val < optimalRange[0] || val > optimalRange[1]) return '#f59e0b'; // Yellow
    return '#22c55e'; // Green
  };

  const percentage = ((value - min) / (max - min)) * 100;
  const color = getColor(value);

  const data = {
    datasets: [
      {
        data: [percentage, 100 - percentage],
        backgroundColor: [color, '#e5e7eb'],
        borderWidth: 0,
        circumference: 180,
        rotation: 270,
      },
    ],
  };

  const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: { enabled: false },
    },
    cutout: '75%',
  };

  const sizeClasses = {
    sm: 'w-24 h-24',
    md: 'w-32 h-32',
    lg: 'w-48 h-48',
  };

  return (
    <div className={`relative ${sizeClasses[size]} flex flex-col items-center`}>
      <div className="absolute inset-0">
        <Doughnut data={data} options={options} />
      </div>
      <div className="absolute bottom-0 flex flex-col items-center">
        <span className="text-2xl font-bold text-gray-800 dark:text-gray-100">
          {value.toFixed(1)}{unit}
        </span>
        <span className="text-xs text-gray-500 dark:text-gray-400">{label}</span>
      </div>
      {/* Optimal range indicator */}
      <div className="absolute top-0 w-full flex justify-center">
        <div 
          className="w-1 h-2 bg-green-500 rounded-full opacity-50"
          style={{ 
            transform: `rotate(${((optimalRange[0] - min) / (max - min)) * 180 - 90}deg)`,
            transformOrigin: 'bottom center'
          }}
        />
      </div>
    </div>
  );
};
```

### 5.2 Time-Series Charts

**Milk Production Chart Component:**

```typescript
// components/charts/MilkProductionChart.tsx
import React from 'react';
import { Line } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  TimeScale,
  ChartOptions
} from 'chart.js';
import 'chartjs-adapter-date-fns';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  TimeScale
);

interface ProductionDataPoint {
  timestamp: string;
  volume: number;
  fatContent?: number;
  proteinContent?: number;
  session: 'morning' | 'evening';
}

interface MilkProductionChartProps {
  data: ProductionDataPoint[];
  showQuality?: boolean;
  timeRange?: '24h' | '7d' | '30d';
}

export const MilkProductionChart: React.FC<MilkProductionChartProps> = ({
  data,
  showQuality = false,
  timeRange = '7d'
}) => {
  const chartData = {
    datasets: [
      {
        label: 'Volume (L)',
        data: data.map(d => ({ x: d.timestamp, y: d.volume })),
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        fill: true,
        tension: 0.4,
        yAxisID: 'y',
      },
      ...(showQuality ? [
        {
          label: 'Fat %',
          data: data.map(d => ({ x: d.timestamp, y: d.fatContent })),
          borderColor: 'rgb(251, 191, 36)',
          backgroundColor: 'transparent',
          borderDash: [5, 5],
          tension: 0.4,
          yAxisID: 'y1',
        },
        {
          label: 'Protein %',
          data: data.map(d => ({ x: d.timestamp, y: d.proteinContent })),
          borderColor: 'rgb(239, 68, 68)',
          backgroundColor: 'transparent',
          borderDash: [5, 5],
          tension: 0.4,
          yAxisID: 'y1',
        }
      ] : []),
    ],
  };

  const options: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: 'index',
      intersect: false,
    },
    plugins: {
      legend: {
        position: 'top',
        labels: {
          usePointStyle: true,
          color: 'rgb(156, 163, 175)',
        },
      },
      tooltip: {
        backgroundColor: 'rgba(17, 24, 39, 0.9)',
        titleColor: 'rgb(243, 244, 246)',
        bodyColor: 'rgb(209, 213, 219)',
        borderColor: 'rgba(75, 85, 99, 0.5)',
        borderWidth: 1,
        callbacks: {
          label: (context) => {
            const label = context.dataset.label || '';
            const value = context.parsed.y;
            return `${label}: ${value?.toFixed(2) || 'N/A'}`;
          },
        },
      },
    },
    scales: {
      x: {
        type: 'time',
        time: {
          unit: timeRange === '24h' ? 'hour' : 'day',
          displayFormats: {
            hour: 'HH:mm',
            day: 'MMM d',
          },
        },
        grid: {
          color: 'rgba(75, 85, 99, 0.2)',
        },
        ticks: {
          color: 'rgb(156, 163, 175)',
        },
      },
      y: {
        type: 'linear',
        display: true,
        position: 'left',
        title: {
          display: true,
          text: 'Volume (Liters)',
          color: 'rgb(156, 163, 175)',
        },
        grid: {
          color: 'rgba(75, 85, 99, 0.2)',
        },
        ticks: {
          color: 'rgb(156, 163, 175)',
        },
      },
      y1: {
        type: 'linear',
        display: showQuality,
        position: 'right',
        title: {
          display: true,
          text: 'Percentage (%)',
          color: 'rgb(156, 163, 175)',
        },
        grid: {
          drawOnChartArea: false,
        },
        ticks: {
          color: 'rgb(156, 163, 175)',
        },
        min: 0,
        max: 10,
      },
    },
  };

  return (
    <div className="w-full h-80">
      <Line data={chartData} options={options} />
    </div>
  );
};
```

### 5.3 Heat Maps

**Barn Zone Heat Map Component:**

```typescript
// components/maps/BarnHeatMap.tsx
import React from 'react';

interface ZoneData {
  id: string;
  name: string;
  temperature: number;
  humidity: number;
  nh3Level: number;
  status: 'optimal' | 'warning' | 'critical';
  cowCount: number;
}

interface BarnHeatMapProps {
  zones: ZoneData[];
  selectedMetric?: 'temperature' | 'humidity' | 'nh3' | 'status';
  onZoneClick?: (zoneId: string) => void;
}

export const BarnHeatMap: React.FC<BarnHeatMapProps> = ({
  zones,
  selectedMetric = 'temperature',
  onZoneClick,
}) => {
  const getColorByMetric = (zone: ZoneData): string => {
    switch (selectedMetric) {
      case 'temperature':
        if (zone.temperature < 18) return '#3b82f6'; // Blue - cold
        if (zone.temperature < 22) return '#22c55e'; // Green - optimal
        if (zone.temperature < 26) return '#f59e0b'; // Yellow - warm
        return '#ef4444'; // Red - hot
      case 'humidity':
        if (zone.humidity < 40) return '#f59e0b';
        if (zone.humidity < 70) return '#22c55e';
        if (zone.humidity < 85) return '#f59e0b';
        return '#ef4444';
      case 'nh3':
        if (zone.nh3Level < 10) return '#22c55e';
        if (zone.nh3Level < 20) return '#f59e0b';
        return '#ef4444';
      case 'status':
        return {
          optimal: '#22c55e',
          warning: '#f59e0b',
          critical: '#ef4444',
        }[zone.status];
      default:
        return '#6b7280';
    }
  };

  const getMetricValue = (zone: ZoneData): string => {
    switch (selectedMetric) {
      case 'temperature':
        return `${zone.temperature.toFixed(1)}°C`;
      case 'humidity':
        return `${zone.humidity.toFixed(0)}%`;
      case 'nh3':
        return `${zone.nh3Level.toFixed(1)}ppm`;
      case 'status':
        return zone.status.charAt(0).toUpperCase() + zone.status.slice(1);
      default:
        return '';
    }
  };

  return (
    <div className="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
      <div className="grid grid-cols-5 gap-3">
        {zones.map((zone) => (
          <button
            key={zone.id}
            onClick={() => onZoneClick?.(zone.id)}
            className="relative p-4 rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            style={{ backgroundColor: getColorByMetric(zone) }}
          >
            <div className="text-white">
              <p className="text-sm font-semibold">{zone.name}</p>
              <p className="text-2xl font-bold">{getMetricValue(zone)}</p>
              <div className="mt-2 flex items-center text-xs opacity-90">
                <span className="mr-2">🐄 {zone.cowCount}</span>
                {zone.status === 'critical' && <span className="animate-pulse">⚠️</span>}
              </div>
            </div>
          </button>
        ))}
      </div>
      
      {/* Legend */}
      <div className="mt-4 flex flex-wrap gap-4 text-sm">
        {selectedMetric === 'temperature' && (
          <>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-blue-500 mr-2"></span>Cold (&lt;18°C)</div>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-green-500 mr-2"></span>Optimal (18-22°C)</div>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-yellow-500 mr-2"></span>Warm (22-26°C)</div>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-red-500 mr-2"></span>Hot (&gt;26°C)</div>
          </>
        )}
        {selectedMetric === 'status' && (
          <>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-green-500 mr-2"></span>Optimal</div>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-yellow-500 mr-2"></span>Warning</div>
            <div className="flex items-center"><span className="w-4 h-4 rounded bg-red-500 mr-2"></span>Critical</div>
          </>
        )}
      </div>
    </div>
  );
};
```

### 5.4 Alerts Panel

**Real-time Alerts Panel:**

```typescript
// components/alerts/AlertsPanel.tsx
import React, { useState } from 'react';
import { useRealtimeData } from '../../hooks/useRealtimeData';

interface Alert {
  id: string;
  severity: 'critical' | 'high' | 'medium' | 'low';
  category: 'health' | 'environmental' | 'production' | 'system';
  title: string;
  message: string;
  timestamp: string;
  source: string;
  acknowledged: boolean;
  acknowledgedBy?: string;
  acknowledgedAt?: string;
}

export const AlertsPanel: React.FC = () => {
  const { useDataChannel, acknowledgeAlert, isConnected } = useRealtimeData();
  const [filter, setFilter] = useState<Alert['severity'] | 'all'>('all');
  const [showAcknowledged, setShowAcknowledged] = useState(false);
  
  const alerts = useDataChannel<Alert[]>('alerts:initial') || [];
  const newAlert = useDataChannel<Alert>('alert:new');
  
  // Combine initial and new alerts
  const allAlerts = newAlert ? [newAlert, ...alerts] : alerts;
  
  const filteredAlerts = allAlerts.filter(alert => {
    if (!showAcknowledged && alert.acknowledged) return false;
    if (filter !== 'all' && alert.severity !== filter) return false;
    return true;
  });

  const severityConfig = {
    critical: { color: 'bg-red-500', icon: '🔴', label: 'Critical' },
    high: { color: 'bg-orange-500', icon: '🟠', label: 'High' },
    medium: { color: 'bg-yellow-500', icon: '🟡', label: 'Medium' },
    low: { color: 'bg-blue-500', icon: '🔵', label: 'Low' },
  };

  const categoryIcons = {
    health: '🏥',
    environmental: '🌡️',
    production: '🥛',
    system: '⚙️',
  };

  return (
    <div className="bg-white dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
      {/* Header */}
      <div className="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div className="flex items-center">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-gray-100">
            Active Alerts
          </h3>
          <span className="ml-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">
            {filteredAlerts.filter(a => !a.acknowledged).length}
          </span>
        </div>
        <div className="flex items-center gap-2">
          {!isConnected && (
            <span className="text-xs text-red-500 animate-pulse">● Offline</span>
          )}
          <select
            value={filter}
            onChange={(e) => setFilter(e.target.value as Alert['severity'] | 'all')}
            className="text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-gray-100"
          >
            <option value="all">All Severities</option>
            <option value="critical">Critical</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
          </select>
          <label className="flex items-center text-sm">
            <input
              type="checkbox"
              checked={showAcknowledged}
              onChange={(e) => setShowAcknowledged(e.target.checked)}
              className="mr-1 rounded border-gray-300"
            />
            Show Acknowledged
          </label>
        </div>
      </div>

      {/* Alerts List */}
      <div className="max-h-96 overflow-y-auto">
        {filteredAlerts.length === 0 ? (
          <div className="p-8 text-center text-gray-500 dark:text-gray-400">
            <p className="text-4xl mb-2">✅</p>
            <p>No active alerts</p>
          </div>
        ) : (
          filteredAlerts.map((alert) => (
            <div
              key={alert.id}
              className={`px-4 py-3 border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors ${
                alert.acknowledged ? 'opacity-50' : ''
              }`}
            >
              <div className="flex items-start gap-3">
                <div className={`w-2 h-2 mt-2 rounded-full ${severityConfig[alert.severity].color}`} />
                <div className="flex-1">
                  <div className="flex items-center gap-2">
                    <span className="text-lg">{categoryIcons[alert.category]}</span>
                    <span className={`text-xs font-medium px-2 py-0.5 rounded ${severityConfig[alert.severity].color} text-white`}>
                      {severityConfig[alert.severity].label}
                    </span>
                    <span className="text-xs text-gray-500 dark:text-gray-400">
                      {new Date(alert.timestamp).toLocaleString()}
                    </span>
                  </div>
                  <h4 className="font-medium text-gray-800 dark:text-gray-100 mt-1">
                    {alert.title}
                  </h4>
                  <p className="text-sm text-gray-600 dark:text-gray-400">
                    {alert.message}
                  </p>
                  <p className="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    Source: {alert.source}
                  </p>
                </div>
                {!alert.acknowledged && (
                  <button
                    onClick={() => acknowledgeAlert(alert.id)}
                    className="px-3 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 border border-blue-600 dark:border-blue-400 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                  >
                    Acknowledge
                  </button>
                )}
                {alert.acknowledged && (
                  <div className="text-xs text-gray-400 dark:text-gray-500">
                    ✓ {alert.acknowledgedBy}
                    <br />
                    {alert.acknowledgedAt && new Date(alert.acknowledgedAt).toLocaleTimeString()}
                  </div>
                )}
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
};
```

### 5.5 KPI Cards

**Key Performance Indicator Cards:**

```typescript
// components/kpi/KPICard.tsx
import React from 'react';

interface KPICardProps {
  title: string;
  value: string | number;
  unit?: string;
  trend?: {
    value: number;
    direction: 'up' | 'down' | 'neutral';
    label?: string;
  };
  status?: 'good' | 'warning' | 'critical' | 'neutral';
  icon?: React.ReactNode;
  subtitle?: string;
  loading?: boolean;
  onClick?: () => void;
}

export const KPICard: React.FC<KPICardProps> = ({
  title,
  value,
  unit,
  trend,
  status = 'neutral',
  icon,
  subtitle,
  loading = false,
  onClick,
}) => {
  const statusColors = {
    good: 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800',
    warning: 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800',
    critical: 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800',
    neutral: 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700',
  };

  const trendColors = {
    up: 'text-green-600 dark:text-green-400',
    down: 'text-red-600 dark:text-red-400',
    neutral: 'text-gray-500 dark:text-gray-400',
  };

  const trendIcons = {
    up: '↑',
    down: '↓',
    neutral: '→',
  };

  if (loading) {
    return (
      <div className={`p-4 rounded-lg border animate-pulse ${statusColors.neutral}`}>
        <div className="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 mb-2"></div>
        <div className="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
      </div>
    );
  }

  return (
    <div
      onClick={onClick}
      className={`p-4 rounded-lg border shadow-sm transition-all duration-200 ${
        onClick ? 'cursor-pointer hover:shadow-md hover:scale-[1.02]' : ''
      } ${statusColors[status]}`}
    >
      <div className="flex items-start justify-between">
        <div>
          <p className="text-sm font-medium text-gray-600 dark:text-gray-400">{title}</p>
          <div className="flex items-baseline mt-1">
            <span className="text-2xl font-bold text-gray-900 dark:text-gray-100">
              {value}
            </span>
            {unit && (
              <span className="ml-1 text-sm text-gray-500 dark:text-gray-400">{unit}</span>
            )}
          </div>
          {subtitle && (
            <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">{subtitle}</p>
          )}
        </div>
        {icon && (
          <div className="p-2 rounded-lg bg-gray-100 dark:bg-gray-700">
            {icon}
          </div>
        )}
      </div>
      
      {trend && (
        <div className="mt-3 flex items-center">
          <span className={`text-sm font-medium ${trendColors[trend.direction]}`}>
            {trendIcons[trend.direction]} {Math.abs(trend.value)}%
          </span>
          {trend.label && (
            <span className="ml-2 text-xs text-gray-500 dark:text-gray-400">
              {trend.label}
            </span>
          )}
        </div>
      )}
    </div>
  );
};

// Example usage component
export const DashboardKPIGrid: React.FC = () => {
  const { useDataChannel } = useRealtimeData();
  const summary = useDataChannel<ProductionSummary>('production:summary');

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <KPICard
        title="Cows Online"
        value={summary?.cowsOnline || 0}
        unit={`/${summary?.totalCows || 150}`}
        status={summary && summary.cowsOnline / summary.totalCows > 0.95 ? 'good' : 'warning'}
        icon={<span className="text-2xl">🐄</span>}
        subtitle="Active monitoring"
      />
      <KPICard
        title="Today's Milk"
        value={summary?.todayVolume?.toFixed(1) || 0}
        unit="L"
        trend={{ value: 12.5, direction: 'up', label: 'vs yesterday' }}
        status="good"
        icon={<span className="text-2xl">🥛</span>}
        subtitle="Target: 3,000 L"
      />
      <KPICard
        title="Active Alerts"
        value={summary?.activeAlerts || 0}
        trend={{ value: 25, direction: 'down', label: 'vs last hour' }}
        status={summary?.activeAlerts === 0 ? 'good' : summary?.activeAlerts && summary.activeAlerts < 3 ? 'warning' : 'critical'}
        icon={<span className="text-2xl">⚠️</span>}
        subtitle={summary?.criticalAlerts ? `${summary.criticalAlerts} critical` : 'All clear'}
      />
      <KPICard
        title="Avg Efficiency"
        value={summary?.efficiency?.toFixed(1) || 0}
        unit="%"
        trend={{ value: 2.3, direction: 'up', label: 'vs last week' }}
        status={summary && summary.efficiency > 90 ? 'good' : 'warning'}
        icon={<span className="text-2xl">📊</span>}
        subtitle="Target: 95%"
      />
    </div>
  );
};
```

---

## 6. Dashboard Layout

### 6.1 Responsive Design

**Layout Breakpoints:**

| Breakpoint | Width | Target Device | Layout |
|------------|-------|---------------|--------|
| Mobile | < 640px | Phones | Single column, stacked |
| Tablet | 640px - 1024px | iPad (barn use) | 2-column grid |
| Desktop | 1024px - 1440px | Office computers | 3-4 column grid |
| Large | > 1440px | Wall displays | 4-6 column grid |

**Responsive Grid System:**

```typescript
// components/layout/DashboardGrid.tsx
import React from 'react';

interface DashboardGridProps {
  children: React.ReactNode;
  columns?: {
    mobile?: number;
    tablet?: number;
    desktop?: number;
    large?: number;
  };
  gap?: number;
}

export const DashboardGrid: React.FC<DashboardGridProps> = ({
  children,
  columns = { mobile: 1, tablet: 2, desktop: 3, large: 4 },
  gap = 4,
}) => {
  const getGridClasses = () => {
    const colClasses = [];
    if (columns.mobile) colClasses.push(`grid-cols-${columns.mobile}`);
    if (columns.tablet) colClasses.push(`sm:grid-cols-${columns.tablet}`);
    if (columns.desktop) colClasses.push(`lg:grid-cols-${columns.desktop}`);
    if (columns.large) colClasses.push(`xl:grid-cols-${columns.large}`);
    return colClasses.join(' ');
  };

  return (
    <div className={`grid ${getGridClasses()} gap-${gap}`}>
      {children}
    </div>
  );
};

// Widget sizing presets
export const WidgetSize = {
  SMALL: 'col-span-1 row-span-1',
  MEDIUM: 'col-span-1 sm:col-span-2 row-span-1',
  LARGE: 'col-span-1 sm:col-span-2 lg:col-span-3 row-span-2',
  FULL: 'col-span-full row-span-2',
  TALL: 'col-span-1 row-span-2',
} as const;

interface DashboardWidgetProps {
  children: React.ReactNode;
  size?: keyof typeof WidgetSize;
  title?: string;
  actions?: React.ReactNode;
  className?: string;
}

export const DashboardWidget: React.FC<DashboardWidgetProps> = ({
  children,
  size = 'SMALL',
  title,
  actions,
  className = '',
}) => {
  return (
    <div className={`bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col ${WidgetSize[size]} ${className}`}>
      {(title || actions) && (
        <div className="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          {title && (
            <h3 className="text-sm font-semibold text-gray-800 dark:text-gray-100">
              {title}
            </h3>
          )}
          {actions && <div className="flex items-center gap-2">{actions}</div>}
        </div>
      )}
      <div className="flex-1 p-4 overflow-auto">
        {children}
      </div>
    </div>
  );
};
```

### 6.2 Tablet-Optimized Layout

**Barn Tablet Interface (10-inch iPad):**

```typescript
// layouts/BarnTabletLayout.tsx
import React from 'react';
import { DashboardGrid, DashboardWidget } from '../components/layout/DashboardGrid';
import { AlertsPanel } from '../components/alerts/AlertsPanel';
import { BarnHeatMap } from '../components/maps/BarnHeatMap';
import { TemperatureGauge } from '../components/gauges/TemperatureGauge';
import { DashboardKPIGrid } from '../components/kpi/KPICard';

export const BarnTabletLayout: React.FC = () => {
  const { useDataChannel } = useRealtimeData();
  const barnStatus = useDataChannel<BarnStatus>('barn:status');
  
  return (
    <div className="min-h-screen bg-gray-100 dark:bg-gray-900 p-4">
      {/* Header */}
      <header className="mb-4 flex justify-between items-center">
        <div>
          <h1 className="text-xl font-bold text-gray-800 dark:text-gray-100">
            Farm Operations
          </h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">
            {new Date().toLocaleString('en-BD', { 
              weekday: 'long', 
              year: 'numeric', 
              month: 'long', 
              day: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
            })}
          </p>
        </div>
        <ConnectionStatus />
      </header>

      {/* KPI Row */}
      <div className="mb-4">
        <DashboardKPIGrid />
      </div>

      {/* Main Content - Optimized for tablet */}
      <DashboardGrid columns={{ tablet: 2, desktop: 2 }} gap={4}>
        {/* Barn Heat Map - Large widget */}
        <DashboardWidget size="LARGE" title="Barn Zones">
          <BarnHeatMap 
            zones={barnStatus?.zones || []}
            selectedMetric="temperature"
          />
        </DashboardWidget>

        {/* Environmental Gauges */}
        <DashboardWidget size="MEDIUM" title="Environment">
          <div className="grid grid-cols-2 gap-4">
            <TemperatureGauge
              value={barnStatus?.avgTemperature || 22}
              label="Avg Temp"
              size="md"
            />
            <TemperatureGauge
              value={barnStatus?.avgHumidity || 65}
              min={0}
              max={100}
              optimalRange={[50, 70]}
              unit="%"
              label="Humidity"
              size="md"
            />
          </div>
        </DashboardWidget>

        {/* Alerts Panel */}
        <DashboardWidget size="MEDIUM" title="Alerts">
          <AlertsPanel />
        </DashboardWidget>

        {/* Milking Parlor Status */}
        <DashboardWidget size="MEDIUM" title="Milking Parlor">
          <MilkingParlorStatus />
        </DashboardWidget>
      </DashboardGrid>

      {/* Bottom Navigation - Touch optimized */}
      <nav className="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-2 safe-area-pb">
        <div className="flex justify-around">
          <NavButton icon="🏠" label="Overview" active />
          <NavButton icon="🐄" label="Animals" />
          <NavButton icon="🥛" label="Production" />
          <NavButton icon="🌡️" label="Climate" />
          <NavButton icon="⚙️" label="Settings" />
        </div>
      </nav>
    </div>
  );
};

// Touch-optimized navigation button
const NavButton: React.FC<{ icon: string; label: string; active?: boolean }> = ({ 
  icon, 
  label, 
  active 
}) => (
  <button
    className={`flex flex-col items-center p-2 rounded-lg min-w-[64px] transition-colors ${
      active 
        ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
    }`}
  >
    <span className="text-2xl">{icon}</span>
    <span className="text-xs mt-1">{label}</span>
  </button>
);

// Connection status indicator
const ConnectionStatus: React.FC = () => {
  const { isConnected, latency, connectionQuality } = useRealtimeData();
  
  const getStatusColor = () => {
    if (!isConnected) return 'text-red-500';
    if (connectionQuality === 'poor') return 'text-yellow-500';
    return 'text-green-500';
  };
  
  return (
    <div className="flex items-center gap-2 text-sm">
      <span className={`w-3 h-3 rounded-full ${getStatusColor()} ${isConnected ? 'animate-pulse' : ''}`} />
      <span className="text-gray-600 dark:text-gray-400">
        {isConnected ? `${latency}ms` : 'Offline'}
      </span>
      {connectionQuality === 'poor' && isConnected && (
        <span className="text-yellow-500 text-xs">Weak signal</span>
      )}
    </div>
  );
};
```

### 6.3 Dark Mode Implementation

**CSS Variables for Theming:**

```css
/* styles/theme.css */
:root {
  /* Light mode (default) */
  --bg-primary: #ffffff;
  --bg-secondary: #f3f4f6;
  --bg-tertiary: #e5e7eb;
  
  --text-primary: #111827;
  --text-secondary: #6b7280;
  --text-tertiary: #9ca3af;
  
  --border-color: #e5e7eb;
  --accent-color: #3b82f6;
  
  /* Chart colors */
  --chart-grid: rgba(75, 85, 99, 0.2);
  --chart-text: #6b7280;
  
  /* Status colors */
  --status-good: #22c55e;
  --status-warning: #f59e0b;
  --status-critical: #ef4444;
}

.dark {
  /* Dark mode - optimized for night viewing */
  --bg-primary: #111827;
  --bg-secondary: #1f2937;
  --bg-tertiary: #374151;
  
  --text-primary: #f9fafb;
  --text-secondary: #d1d5db;
  --text-tertiary: #9ca3af;
  
  --border-color: #374151;
  --accent-color: #60a5fa;
  
  /* Chart colors - brighter for dark background */
  --chart-grid: rgba(156, 163, 175, 0.2);
  --chart-text: #9ca3af;
  
  /* Status colors - more vibrant */
  --status-good: #4ade80;
  --status-warning: #fbbf24;
  --status-critical: #f87171;
}

/* Reduce brightness for night mode */
.dark .dashboard-container {
  filter: brightness(0.85) contrast(1.1);
}

/* Preserve chart colors */
.dark canvas {
  filter: brightness(1.15);
}
```

**Theme Toggle Component:**

```typescript
// components/theme/ThemeToggle.tsx
import React, { useEffect, useState } from 'react';

export const ThemeToggle: React.FC = () => {
  const [isDark, setIsDark] = useState(false);
  
  useEffect(() => {
    // Check localStorage or system preference
    const savedTheme = localStorage.getItem('dashboard-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldUseDark = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);
    
    setIsDark(shouldUseDark);
    document.documentElement.classList.toggle('dark', shouldUseDark);
  }, []);
  
  const toggleTheme = () => {
    const newTheme = !isDark;
    setIsDark(newTheme);
    document.documentElement.classList.toggle('dark', newTheme);
    localStorage.setItem('dashboard-theme', newTheme ? 'dark' : 'light');
  };
  
  // Auto-enable dark mode between 7 PM and 6 AM
  useEffect(() => {
    const checkTime = () => {
      const hour = new Date().getHours();
      const isNightTime = hour >= 19 || hour < 6;
      const autoDark = localStorage.getItem('dashboard-auto-dark') !== 'false';
      
      if (autoDark && !localStorage.getItem('dashboard-theme')) {
        setIsDark(isNightTime);
        document.documentElement.classList.toggle('dark', isNightTime);
      }
    };
    
    checkTime();
    const interval = setInterval(checkTime, 60000); // Check every minute
    
    return () => clearInterval(interval);
  }, []);
  
  return (
    <button
      onClick={toggleTheme}
      className="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors"
      aria-label={isDark ? 'Switch to light mode' : 'Switch to dark mode'}
    >
      {isDark ? (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      ) : (
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      )}
    </button>
  );
};
```

---

## 7. Data Sources

### 7.1 TimescaleDB Queries

**Query Repository for Dashboards:**

```typescript
// db/queries/dashboardQueries.ts
import { Pool } from 'pg';

const pool = new Pool({
  host: process.env.TIMESCALE_HOST,
  database: 'smartdairy',
  user: process.env.TIMESCALE_USER,
  password: process.env.TIMESCALE_PASSWORD,
  ssl: true,
});

// Real-time barn status query
export async function getLatestBarnStatus(farmId: string) {
  const query = `
    WITH latest_readings AS (
      SELECT DISTINCT ON (zone_id, metric_type)
        zone_id,
        metric_type,
        value,
        time
      FROM environmental_readings
      WHERE farm_id = $1
        AND time >= NOW() - INTERVAL '5 minutes'
      ORDER BY zone_id, metric_type, time DESC
    ),
    zone_status AS (
      SELECT 
        z.zone_id,
        z.zone_name,
        z.cow_capacity,
        COUNT(DISTINCT a.cow_id) as current_cows,
        MAX(CASE WHEN lr.metric_type = 'temperature' THEN lr.value END) as temperature,
        MAX(CASE WHEN lr.metric_type = 'humidity' THEN lr.value END) as humidity,
        MAX(CASE WHEN lr.metric_type = 'nh3' THEN lr.value END) as nh3_level,
        MAX(CASE WHEN lr.metric_type = 'co2' THEN lr.value END) as co2_level
      FROM farm.zones z
      LEFT JOIN latest_readings lr ON z.zone_id = lr.zone_id
      LEFT JOIN farm.animal_locations a ON z.zone_id = a.zone_id AND a.active = true
      WHERE z.farm_id = $1
      GROUP BY z.zone_id, z.zone_name, z.cow_capacity
    )
    SELECT 
      zone_id,
      zone_name,
      current_cows,
      cow_capacity,
      ROUND(temperature::numeric, 1) as temperature,
      ROUND(humidity::numeric, 1) as humidity,
      ROUND(nh3_level::numeric, 2) as nh3_level,
      ROUND(co2_level::numeric, 1) as co2_level,
      CASE 
        WHEN temperature > 26 OR humidity > 80 OR nh3_level > 25 THEN 'critical'
        WHEN temperature > 24 OR humidity > 70 OR nh3_level > 20 THEN 'warning'
        ELSE 'optimal'
      END as status
    FROM zone_status
    ORDER BY zone_name;
  `;
  
  const result = await pool.query(query, [farmId]);
  return result.rows;
}

// Milk production summary
export async function getTodayProduction(farmId: string) {
  const query = `
    WITH today_milk AS (
      SELECT 
        COUNT(DISTINCT cow_id) as cows_milked,
        SUM(volume_liters) as total_volume,
        AVG(fat_percent) as avg_fat,
        AVG(protein_percent) as avg_protein,
        AVG(somatic_cell_count) as avg_scc,
        session
      FROM milk_production
      WHERE farm_id = $1
        AND DATE(production_time) = CURRENT_DATE
      GROUP BY session
    ),
    yesterday_comparison AS (
      SELECT 
        SUM(volume_liters) as yesterday_volume
      FROM milk_production
      WHERE farm_id = $1
        AND DATE(production_time) = CURRENT_DATE - INTERVAL '1 day'
    ),
    target_volume AS (
      SELECT 
        COUNT(*) * 20 as daily_target  -- 20L per cow target
      FROM farm.animals
      WHERE farm_id = $1
        AND status = 'lactating'
        AND active = true
    )
    SELECT 
      COALESCE(SUM(today_milk.total_volume), 0) as today_total,
      COALESCE(SUM(today_milk.cows_milked), 0) as cows_milked,
      ROUND(AVG(today_milk.avg_fat)::numeric, 2) as avg_fat,
      ROUND(AVG(today_milk.avg_protein)::numeric, 2) as avg_protein,
      ROUND(AVG(today_milk.avg_scc)::numeric, 0) as avg_scc,
      json_object_agg(today_milk.session, today_milk.total_volume) as by_session,
      ROUND(((COALESCE(SUM(today_milk.total_volume), 0) - yesterday_volume) / 
        NULLIF(yesterday_volume, 0) * 100)::numeric, 1) as trend_percent,
      ROUND((COALESCE(SUM(today_milk.total_volume), 0) / 
        NULLIF(daily_target, 0) * 100)::numeric, 1) as target_percent
    FROM today_milk
    CROSS JOIN yesterday_comparison
    CROSS JOIN target_volume
    GROUP BY yesterday_comparison.yesterday_volume, target_volume.daily_target;
  `;
  
  const result = await pool.query(query, [farmId]);
  return result.rows[0];
}

// Time-series data for charts
export async function getTimeSeriesData(
  farmId: string,
  metric: string,
  startTime: Date,
  endTime: Date,
  bucketInterval: string = '5 minutes'
) {
  const query = `
    SELECT 
      time_bucket($4, time) as bucket,
      AVG(value) as avg_value,
      MIN(value) as min_value,
      MAX(value) as max_value,
      COUNT(*) as reading_count
    FROM environmental_readings
    WHERE farm_id = $1
      AND metric_type = $2
      AND time >= $3
      AND time <= $4
    GROUP BY bucket
    ORDER BY bucket ASC;
  `;
  
  const result = await pool.query(query, [
    farmId,
    metric,
    startTime,
    endTime,
    bucketInterval,
  ]);
  
  return result.rows.map(row => ({
    timestamp: row.bucket,
    value: parseFloat(row.avg_value),
    min: parseFloat(row.min_value),
    max: parseFloat(row.max_value),
    count: parseInt(row.reading_count),
  }));
}

// Individual animal metrics
export async function getAnimalMetrics(
  cowId: string,
  hours: number = 24
) {
  const query = `
    WITH activity_data AS (
      SELECT 
        time_bucket('1 hour', timestamp) as hour,
        AVG(activity_level) as avg_activity,
        AVG(rumination_minutes) as avg_rumination,
        AVG(body_temperature_celsius) as avg_temp
      FROM animal_health
      WHERE cow_id = $1
        AND timestamp >= NOW() - INTERVAL '${hours} hours'
      GROUP BY hour
      ORDER BY hour ASC
    ),
    current_status AS (
      SELECT 
        activity_level,
        rumination_minutes,
        body_temperature_celsius,
        health_score
      FROM animal_health
      WHERE cow_id = $1
      ORDER BY timestamp DESC
      LIMIT 1
    ),
    alerts AS (
      SELECT 
        id,
        severity,
        message,
        created_at,
        acknowledged
      FROM health_alerts
      WHERE cow_id = $1
        AND created_at >= NOW() - INTERVAL '${hours} hours'
      ORDER BY created_at DESC
    )
    SELECT 
      json_agg(activity_data) as hourly_data,
      (SELECT json_agg(current_status) FROM current_status) as current_status,
      (SELECT json_agg(alerts) FROM alerts) as alerts
    FROM activity_data;
  `;
  
  const result = await pool.query(query, [cowId]);
  return result.rows[0];
}

// Active alerts query
export async function getActiveAlerts(farmId: string, limit: number = 50) {
  const query = `
    SELECT 
      a.id,
      a.severity,
      a.category,
      a.title,
      a.message,
      a.source,
      a.created_at as timestamp,
      a.acknowledged,
      u.name as acknowledged_by,
      a.acknowledged_at
    FROM alerts a
    LEFT JOIN users u ON a.acknowledged_by = u.id
    WHERE a.farm_id = $1
      AND (a.resolved = false OR a.resolved_at >= NOW() - INTERVAL '1 hour')
    ORDER BY 
      CASE a.severity
        WHEN 'critical' THEN 1
        WHEN 'high' THEN 2
        WHEN 'medium' THEN 3
        WHEN 'low' THEN 4
      END,
      a.created_at DESC
    LIMIT $2;
  `;
  
  const result = await pool.query(query, [farmId, limit]);
  return result.rows;
}

// Continuous aggregate for 30-day trends
export async function getThirtyDayTrend(farmId: string, metric: string) {
  const query = `
    SELECT 
      bucket as date,
      avg_value,
      min_value,
      max_value
    FROM cagg_daily_${metric}
    WHERE farm_id = $1
      AND bucket >= NOW() - INTERVAL '30 days'
    ORDER BY bucket ASC;
  `;
  
  const result = await pool.query(query, [farmId]);
  return result.rows;
}
```

### 7.2 API Integration

**FastAPI Endpoints for Dashboard:**

```python
# api/dashboard.py
from fastapi import APIRouter, Depends, HTTPException, Query
from datetime import datetime, timedelta
from typing import List, Optional
from pydantic import BaseModel
from auth import get_current_user, require_role
from db import timescale_pool

router = APIRouter(prefix="/api/v1/dashboard", tags=["dashboard"])

class TimeSeriesData(BaseModel):
    timestamp: datetime
    value: float
    min: Optional[float] = None
    max: Optional[float] = None

class BarnStatus(BaseModel):
    zone_id: str
    zone_name: str
    temperature: float
    humidity: float
    nh3_level: float
    co2_level: float
    status: str
    current_cows: int
    cow_capacity: int

class ProductionSummary(BaseModel):
    today_total: float
    cows_milked: int
    avg_fat: Optional[float]
    avg_protein: Optional[float]
    avg_scc: Optional[float]
    by_session: dict
    trend_percent: Optional[float]
    target_percent: Optional[float]

@router.get("/barn-status", response_model=List[BarnStatus])
async def get_barn_status(
    current_user: User = Depends(get_current_user)
):
    """Get real-time barn environmental status by zone"""
    try:
        query = """
            WITH latest_readings AS (
                SELECT DISTINCT ON (zone_id, metric_type)
                    zone_id, metric_type, value
                FROM environmental_readings
                WHERE farm_id = %s AND time >= NOW() - INTERVAL '5 minutes'
                ORDER BY zone_id, metric_type, time DESC
            )
            SELECT 
                z.zone_id, z.zone_name,
                MAX(CASE WHEN lr.metric_type = 'temperature' THEN lr.value END) as temperature,
                MAX(CASE WHEN lr.metric_type = 'humidity' THEN lr.value END) as humidity,
                MAX(CASE WHEN lr.metric_type = 'nh3' THEN lr.value END) as nh3_level,
                MAX(CASE WHEN lr.metric_type = 'co2' THEN lr.value END) as co2_level,
                COUNT(DISTINCT al.cow_id) as current_cows, z.cow_capacity
            FROM farm.zones z
            LEFT JOIN latest_readings lr ON z.zone_id = lr.zone_id
            LEFT JOIN farm.animal_locations al ON z.zone_id = al.zone_id AND al.active = true
            WHERE z.farm_id = %s
            GROUP BY z.zone_id, z.zone_name, z.cow_capacity
            ORDER BY z.zone_name
        """
        
        async with timescale_pool.acquire() as conn:
            rows = await conn.fetch(query, current_user.farm_id, current_user.farm_id)
            
        result = []
        for row in rows:
            temp = row['temperature'] or 0
            hum = row['humidity'] or 0
            nh3 = row['nh3_level'] or 0
            
            status = 'optimal'
            if temp > 26 or hum > 80 or nh3 > 25:
                status = 'critical'
            elif temp > 24 or hum > 70 or nh3 > 20:
                status = 'warning'
                
            result.append(BarnStatus(
                zone_id=row['zone_id'],
                zone_name=row['zone_name'],
                temperature=round(temp, 1),
                humidity=round(hum, 1),
                nh3_level=round(nh3, 2),
                co2_level=round(row['co2_level'] or 0, 1),
                status=status,
                current_cows=row['current_cows'],
                cow_capacity=row['cow_capacity']
            ))
            
        return result
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/production/summary", response_model=ProductionSummary)
async def get_production_summary(
    current_user: User = Depends(get_current_user)
):
    """Get today's milk production summary"""
    try:
        query = """
            WITH today AS (
                SELECT 
                    COUNT(DISTINCT cow_id) as cows,
                    SUM(volume_liters) as volume,
                    AVG(fat_percent) as fat,
                    AVG(protein_percent) as protein,
                    session
                FROM milk_production
                WHERE farm_id = %s AND DATE(production_time) = CURRENT_DATE
                GROUP BY session
            ),
            yesterday AS (
                SELECT SUM(volume_liters) as vol
                FROM milk_production
                WHERE farm_id = %s AND DATE(production_time) = CURRENT_DATE - INTERVAL '1 day'
            ),
            target AS (
                SELECT COUNT(*) * 20 as tgt
                FROM farm.animals
                WHERE farm_id = %s AND status = 'lactating' AND active = true
            )
            SELECT 
                COALESCE(SUM(today.volume), 0) as today_total,
                COALESCE(SUM(today.cows), 0) as cows_milked,
                AVG(today.fat) as avg_fat,
                AVG(today.protein) as avg_protein,
                json_object_agg(today.session, today.volume) as by_session,
                (SELECT vol FROM yesterday) as yesterday_volume,
                (SELECT tgt FROM target) as daily_target
            FROM today
        """
        
        async with timescale_pool.acquire() as conn:
            row = await conn.fetchrow(query, 
                current_user.farm_id, 
                current_user.farm_id,
                current_user.farm_id
            )
        
        today_total = row['today_total']
        yesterday = row['yesterday_volume'] or 1
        target = row['daily_target'] or 1
        
        return ProductionSummary(
            today_total=round(today_total, 2),
            cows_milked=row['cows_milked'],
            avg_fat=round(row['avg_fat'], 2) if row['avg_fat'] else None,
            avg_protein=round(row['avg_protein'], 2) if row['avg_protein'] else None,
            avg_scc=None,
            by_session=row['by_session'] or {},
            trend_percent=round((today_total - yesterday) / yesterday * 100, 1),
            target_percent=round(today_total / target * 100, 1)
        )
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/timeseries/{metric}", response_model=List[TimeSeriesData])
async def get_timeseries(
    metric: str,
    start: datetime = Query(default=None),
    end: datetime = Query(default=None),
    interval: str = Query(default="5 minutes", regex="^(1|5|15|30) minutes|(1|6|24) hours|1 day$"),
    current_user: User = Depends(get_current_user)
):
    """Get time-series data for charts"""
    if not end:
        end = datetime.utcnow()
    if not start:
        start = end - timedelta(hours=24)
        
    try:
        query = """
            SELECT 
                time_bucket(%s, time) as bucket,
                ROUND(AVG(value)::numeric, 2) as avg_value,
                MIN(value) as min_value,
                MAX(value) as max_value
            FROM environmental_readings
            WHERE farm_id = %s AND metric_type = %s
                AND time >= %s AND time <= %s
            GROUP BY bucket
            ORDER BY bucket ASC
        """
        
        async with timescale_pool.acquire() as conn:
            rows = await conn.fetch(query, interval, current_user.farm_id, metric, start, end)
            
        return [
            TimeSeriesData(
                timestamp=row['bucket'],
                value=float(row['avg_value']),
                min=float(row['min_value']),
                max=float(row['max_value'])
            )
            for row in rows
        ]
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
```

---

## 8. Alert Visualization

### 8.1 Severity Colors

| Severity | Color Code | Hex | Usage |
|----------|-----------|-----|-------|
| Critical | Red | `#ef4444` | Immediate action required |
| High | Orange | `#f97316` | Urgent attention needed |
| Medium | Yellow | `#eab308` | Monitor closely |
| Low | Blue | `#3b82f6` | Informational |
| Resolved | Gray | `#6b7280` | No action needed |

### 8.2 Alert Acknowledgment Flow

```typescript
// hooks/useAlerts.ts
import { useState, useCallback } from 'react';
import { useRealtimeData } from './useRealtimeData';

interface UseAlertsOptions {
  autoRefresh?: boolean;
  refreshInterval?: number;
}

export function useAlerts(options: UseAlertsOptions = {}) {
  const { autoRefresh = true, refreshInterval = 30000 } = options;
  const { useDataChannel, acknowledgeAlert, isConnected } = useRealtimeData();
  const [isAcknowledging, setIsAcknowledging] = useState<string | null>(null);
  
  const alerts = useDataChannel<Alert[]>('alerts:initial') || [];
  const newAlert = useDataChannel<Alert>('alert:new');
  
  const allAlerts = newAlert ? [newAlert, ...alerts] : alerts;
  
  const stats = {
    total: allAlerts.length,
    critical: allAlerts.filter(a => a.severity === 'critical' && !a.acknowledged).length,
    high: allAlerts.filter(a => a.severity === 'high' && !a.acknowledged).length,
    acknowledged: allAlerts.filter(a => a.acknowledged).length,
  };
  
  const handleAcknowledge = useCallback(async (alertId: string) => {
    setIsAcknowledging(alertId);
    try {
      await acknowledgeAlert(alertId);
    } finally {
      setIsAcknowledging(null);
    }
  }, [acknowledgeAlert]);
  
  const handleBulkAcknowledge = useCallback(async (alertIds: string[]) => {
    await Promise.all(alertIds.map(id => acknowledgeAlert(id)));
  }, [acknowledgeAlert]);
  
  return {
    alerts: allAlerts,
    stats,
    isConnected,
    isAcknowledging,
    acknowledgeAlert: handleAcknowledge,
    acknowledgeBulk: handleBulkAcknowledge,
  };
}
```

---

## 9. Historical Analysis

### 9.1 Date Range Selection

```typescript
// components/DateRangePicker.tsx
import React, { useState } from 'react';

interface DateRange {
  start: Date;
  end: Date;
  label: string;
}

const PRESETS: DateRange[] = [
  { label: 'Last 24 Hours', start: new Date(Date.now() - 86400000), end: new Date() },
  { label: 'Last 7 Days', start: new Date(Date.now() - 604800000), end: new Date() },
  { label: 'Last 30 Days', start: new Date(Date.now() - 2592000000), end: new Date() },
  { label: 'This Month', start: new Date(new Date().getFullYear(), new Date().getMonth(), 1), end: new Date() },
  { label: 'Last Month', start: new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1), end: new Date(new Date().getFullYear(), new Date().getMonth(), 0) },
];

export const DateRangePicker: React.FC<{
  value: DateRange;
  onChange: (range: DateRange) => void;
}> = ({ value, onChange }) => {
  const [showCustom, setShowCustom] = useState(false);
  
  return (
    <div className="flex flex-wrap gap-2">
      {PRESETS.map(preset => (
        <button
          key={preset.label}
          onClick={() => onChange(preset)}
          className={`px-3 py-1.5 text-sm rounded-lg transition-colors ${
            value.label === preset.label
              ? 'bg-blue-500 text-white'
              : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
          }`}
        >
          {preset.label}
        </button>
      ))}
      <button
        onClick={() => setShowCustom(!showCustom)}
        className="px-3 py-1.5 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
      >
        Custom
      </button>
      
      {showCustom && (
        <div className="w-full mt-2 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
          <div className="flex gap-4">
            <div>
              <label className="block text-sm text-gray-600 dark:text-gray-400">Start</label>
              <input
                type="datetime-local"
                value={value.start.toISOString().slice(0, 16)}
                onChange={(e) => onChange({ ...value, start: new Date(e.target.value) })}
                className="mt-1 block rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
              />
            </div>
            <div>
              <label className="block text-sm text-gray-600 dark:text-gray-400">End</label>
              <input
                type="datetime-local"
                value={value.end.toISOString().slice(0, 16)}
                onChange={(e) => onChange({ ...value, end: new Date(e.target.value) })}
                className="mt-1 block rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
              />
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
```

### 9.2 Trend Analysis

**Trend Detection Algorithm:**

```typescript
// utils/trendAnalysis.ts
export interface TrendResult {
  direction: 'up' | 'down' | 'stable';
  slope: number;
  confidence: number;
  changePercent: number;
}

export function calculateTrend(values: number[], timestamps: number[]): TrendResult {
  if (values.length < 2) {
    return { direction: 'stable', slope: 0, confidence: 0, changePercent: 0 };
  }
  
  const n = values.length;
  const xMean = timestamps.reduce((a, b) => a + b, 0) / n;
  const yMean = values.reduce((a, b) => a + b, 0) / n;
  
  let numerator = 0;
  let denominator = 0;
  
  for (let i = 0; i < n; i++) {
    const xDiff = timestamps[i] - xMean;
    numerator += xDiff * (values[i] - yMean);
    denominator += xDiff * xDiff;
  }
  
  const slope = denominator !== 0 ? numerator / denominator : 0;
  
  // Calculate R-squared for confidence
  const yPred = timestamps.map(t => slope * (t - xMean) + yMean);
  const ssRes = values.reduce((sum, y, i) => sum + Math.pow(y - yPred[i], 2), 0);
  const ssTot = values.reduce((sum, y) => sum + Math.pow(y - yMean, 2), 0);
  const rSquared = ssTot !== 0 ? 1 - ssRes / ssTot : 0;
  
  const firstValue = values[0];
  const lastValue = values[values.length - 1];
  const changePercent = firstValue !== 0 ? ((lastValue - firstValue) / firstValue) * 100 : 0;
  
  let direction: 'up' | 'down' | 'stable' = 'stable';
  if (Math.abs(slope) > 0.001) {
    direction = slope > 0 ? 'up' : 'down';
  }
  
  return {
    direction,
    slope,
    confidence: rSquared,
    changePercent,
  };
}
```

---

## 10. Export Features

### 10.1 PDF Report Generation

```typescript
// utils/pdfExport.ts
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export async function generateDashboardReport(
  data: DashboardData,
  dateRange: DateRange,
  filename?: string
): Promise<void> {
  const doc = new jsPDF();
  
  // Header
  doc.setFontSize(20);
  doc.text('Smart Dairy - Farm Operations Report', 14, 20);
  
  doc.setFontSize(12);
  doc.text(`Farm: ${data.farmName}`, 14, 30);
  doc.text(`Period: ${dateRange.start.toLocaleDateString()} - ${dateRange.end.toLocaleDateString()}`, 14, 37);
  doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 44);
  
  // Production Summary
  doc.setFontSize(14);
  doc.text('Production Summary', 14, 55);
  
  autoTable(doc, {
    startY: 60,
    head: [['Metric', 'Value']],
    body: [
      ['Total Volume', `${data.production.totalVolume.toFixed(1)} L`],
      ['Cows Milked', data.production.cowsMilked.toString()],
      ['Average per Cow', `${(data.production.totalVolume / data.production.cowsMilked).toFixed(1)} L`],
      ['Fat Content', `${data.production.avgFat?.toFixed(2)}%`],
      ['Protein Content', `${data.production.avgProtein?.toFixed(2)}%`],
    ],
  });
  
  // Environmental Data
  const envY = (doc as any).lastAutoTable.finalY + 10;
  doc.setFontSize(14);
  doc.text('Environmental Averages', 14, envY);
  
  autoTable(doc, {
    startY: envY + 5,
    head: [['Zone', 'Temperature', 'Humidity', 'NH3', 'Status']],
    body: data.zones.map(z => [
      z.zoneName,
      `${z.temperature}°C`,
      `${z.humidity}%`,
      `${z.nh3Level} ppm`,
      z.status,
    ]),
  });
  
  // Alerts Summary
  const alertY = (doc as any).lastAutoTable.finalY + 10;
  doc.setFontSize(14);
  doc.text('Alerts Summary', 14, alertY);
  
  autoTable(doc, {
    startY: alertY + 5,
    head: [['Time', 'Severity', 'Category', 'Message', 'Status']],
    body: data.alerts.map(a => [
      new Date(a.timestamp).toLocaleString(),
      a.severity,
      a.category,
      a.message,
      a.acknowledged ? 'Acknowledged' : 'Active',
    ]),
  });
  
  doc.save(filename || `farm-report-${new Date().toISOString().split('T')[0]}.pdf`);
}
```

### 10.2 CSV Export

```typescript
// utils/csvExport.ts
export function exportToCSV(
  data: Record<string, any>[],
  filename: string
): void {
  if (data.length === 0) return;
  
  const headers = Object.keys(data[0]);
  const csvRows = [
    headers.join(','),
    ...data.map(row => 
      headers.map(header => {
        const value = row[header];
        // Escape values containing commas or quotes
        const stringValue = String(value ?? '');
        if (stringValue.includes(',') || stringValue.includes('"')) {
          return `"${stringValue.replace(/"/g, '""')}"`;
        }
        return stringValue;
      }).join(',')
    ),
  ];
  
  const csvContent = '\uFEFF' + csvRows.join('\n'); // BOM for Excel
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  link.click();
}
```

---

## 11. User Authentication

### 11.1 Role-Based Access Control

```typescript
// auth/roles.ts
export enum UserRole {
  SUPER_ADMIN = 'super_admin',
  FARM_MANAGER = 'farm_manager',
  VETERINARIAN = 'veterinarian',
  MILKING_SUPERVISOR = 'milking_supervisor',
  FIELD_WORKER = 'field_worker',
  VIEWER = 'viewer',
}

export interface Permission {
  resource: string;
  actions: ('read' | 'write' | 'delete' | 'acknowledge')[];
}

export const ROLE_PERMISSIONS: Record<UserRole, Permission[]> = {
  [UserRole.SUPER_ADMIN]: [
    { resource: '*', actions: ['read', 'write', 'delete', 'acknowledge'] },
  ],
  [UserRole.FARM_MANAGER]: [
    { resource: 'dashboard', actions: ['read'] },
    { resource: 'production', actions: ['read', 'write'] },
    { resource: 'environmental', actions: ['read', 'write'] },
    { resource: 'animals', actions: ['read', 'write'] },
    { resource: 'alerts', actions: ['read', 'acknowledge'] },
    { resource: 'reports', actions: ['read', 'write'] },
  ],
  [UserRole.VETERINARIAN]: [
    { resource: 'dashboard', actions: ['read'] },
    { resource: 'animals', actions: ['read', 'write'] },
    { resource: 'health_alerts', actions: ['read', 'acknowledge'] },
  ],
  [UserRole.MILKING_SUPERVISOR]: [
    { resource: 'dashboard', actions: ['read'] },
    { resource: 'production', actions: ['read', 'write'] },
    { resource: 'parlor', actions: ['read', 'write'] },
  ],
  [UserRole.FIELD_WORKER]: [
    { resource: 'dashboard', actions: ['read'] },
    { resource: 'alerts', actions: ['read', 'acknowledge'] },
  ],
  [UserRole.VIEWER]: [
    { resource: 'dashboard', actions: ['read'] },
  ],
};

// Farm isolation middleware
export function checkFarmAccess(userFarmId: string, requestedFarmId: string): boolean {
  return userFarmId === requestedFarmId;
}
```

---

## 12. Performance Optimization

### 12.1 Query Caching

```typescript
// cache/queryCache.ts
import NodeCache from 'node-cache';

const cache = new NodeCache({ stdTTL: 30, checkperiod: 60 });

export async function cachedQuery<T>(
  key: string,
  queryFn: () => Promise<T>,
  ttl: number = 30
): Promise<T> {
  const cached = cache.get<T>(key);
  if (cached) {
    return cached;
  }
  
  const result = await queryFn();
  cache.set(key, result, ttl);
  return result;
}

export function invalidateCache(pattern: string): void {
  const keys = cache.keys();
  const matchingKeys = keys.filter(k => k.includes(pattern));
  cache.del(matchingKeys);
}
```

### 12.2 Lazy Loading

```typescript
// components/LazyWidget.tsx
import React, { Suspense, lazy } from 'react';

const ProductionChart = lazy(() => import('./ProductionChart'));
const HeatMap = lazy(() => import('./HeatMap'));

export const LazyWidget: React.FC<{ type: string }> = ({ type }) => {
  const Widget = {
    production: ProductionChart,
    heatmap: HeatMap,
  }[type];
  
  return (
    <Suspense fallback={<WidgetSkeleton />}>
      <Widget />
    </Suspense>
  );
};

const WidgetSkeleton: React.FC = () => (
  <div className="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-lg h-64" />
);
```

---

## 13. Mobile App Integration

### 13.1 Dashboard Widgets in Mobile App

**React Native Widget Component:**

```typescript
// mobile/components/DashboardWidget.tsx
import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';

interface MobileWidgetProps {
  title: string;
  value: string | number;
  unit?: string;
  trend?: { direction: 'up' | 'down' | 'neutral'; value: number };
  status?: 'good' | 'warning' | 'critical';
  onPress?: () => void;
}

export const MobileDashboardWidget: React.FC<MobileWidgetProps> = ({
  title,
  value,
  unit,
  trend,
  status = 'good',
  onPress,
}) => {
  const statusColors = {
    good: '#22c55e',
    warning: '#f59e0b',
    critical: '#ef4444',
  };
  
  return (
    <TouchableOpacity 
      style={[styles.container, { borderLeftColor: statusColors[status] }]}
      onPress={onPress}
    >
      <Text style={styles.title}>{title}</Text>
      <View style={styles.valueRow}>
        <Text style={styles.value}>{value}</Text>
        {unit && <Text style={styles.unit}>{unit}</Text>}
      </View>
      {trend && (
        <Text style={[
          styles.trend,
          { color: trend.direction === 'up' ? '#22c55e' : trend.direction === 'down' ? '#ef4444' : '#6b7280' }
        ]}>
          {trend.direction === 'up' ? '↑' : trend.direction === 'down' ? '↓' : '→'} {Math.abs(trend.value)}%
        </Text>
      )}
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  container: {
    backgroundColor: '#ffffff',
    borderRadius: 12,
    padding: 16,
    margin: 8,
    borderLeftWidth: 4,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  title: {
    fontSize: 12,
    color: '#6b7280',
    textTransform: 'uppercase',
    fontWeight: '600',
  },
  valueRow: {
    flexDirection: 'row',
    alignItems: 'baseline',
    marginTop: 4,
  },
  value: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#111827',
  },
  unit: {
    fontSize: 14,
    color: '#6b7280',
    marginLeft: 4,
  },
  trend: {
    fontSize: 12,
    marginTop: 4,
    fontWeight: '500',
  },
});
```

---

## 14. Appendices

### Appendix A: Grafana Dashboard JSON Export

#### Farm Operations Dashboard

```json
{
  "dashboard": {
    "id": null,
    "uid": "farm-ops-001",
    "title": "Farm Operations - Real-time",
    "tags": ["farm", "operations", "real-time"],
    "timezone": "Asia/Dhaka",
    "schemaVersion": 38,
    "refresh": "5s",
    "panels": [
      {
        "id": 1,
        "title": "Cows Online",
        "type": "stat",
        "targets": [
          {
            "datasource": "TimescaleDB",
            "rawSql": "SELECT COUNT(DISTINCT cow_id) FROM animal_health WHERE farm_id = '$farm_id' AND time >= NOW() - INTERVAL '10 minutes'",
            "format": "table"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "thresholds": {
              "steps": [
                {"color": "red", "value": null},
                {"color": "yellow", "value": 100},
                {"color": "green", "value": 140}
              ]
            }
          }
        },
        "gridPos": {"h": 4, "w": 3, "x": 0, "y": 0}
      },
      {
        "id": 2,
        "title": "Today's Milk Production",
        "type": "stat",
        "targets": [
          {
            "datasource": "TimescaleDB",
            "rawSql": "SELECT SUM(volume_liters) FROM milk_production WHERE farm_id = '$farm_id' AND DATE(production_time) = CURRENT_DATE",
            "format": "table"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "liters",
            "thresholds": {
              "steps": [
                {"color": "yellow", "value": null},
                {"color": "green", "value": 2500}
              ]
            }
          }
        },
        "gridPos": {"h": 4, "w": 3, "x": 3, "y": 0}
      },
      {
        "id": 3,
        "title": "Active Alerts",
        "type": "stat",
        "targets": [
          {
            "datasource": "TimescaleDB",
            "rawSql": "SELECT COUNT(*) FROM alerts WHERE farm_id = '$farm_id' AND acknowledged = false",
            "format": "table"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "thresholds": {
              "steps": [
                {"color": "green", "value": null},
                {"color": "yellow", "value": 1},
                {"color": "red", "value": 5}
              ]
            }
          }
        },
        "gridPos": {"h": 4, "w": 3, "x": 6, "y": 0}
      },
      {
        "id": 4,
        "title": "Barn Temperature by Zone",
        "type": "timeseries",
        "targets": [
          {
            "datasource": "TimescaleDB",
            "rawSql": "SELECT time_bucket('5 minutes', time) AS time, zone_id, AVG(value) FROM environmental_readings WHERE farm_id = '$farm_id' AND metric_type = 'temperature' AND time >= NOW() - INTERVAL '24 hours' GROUP BY time_bucket('5 minutes', time), zone_id ORDER BY time",
            "format": "time_series"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "celsius",
            "custom": {
              "lineWidth": 2,
              "fillOpacity": 10
            }
          }
        },
        "gridPos": {"h": 8, "w": 12, "x": 0, "y": 4}
      },
      {
        "id": 5,
        "title": "Milk Production Trend",
        "type": "timeseries",
        "targets": [
          {
            "datasource": "TimescaleDB",
            "rawSql": "SELECT time_bucket('1 hour', production_time) AS time, SUM(volume_liters) FROM milk_production WHERE farm_id = '$farm_id' AND production_time >= NOW() - INTERVAL '7 days' GROUP BY time_bucket('1 hour', production_time) ORDER BY time",
            "format": "time_series"
          }
        ],
        "fieldConfig": {
          "defaults": {
            "unit": "liters",
            "custom": {
              "drawStyle": "bars",
              "fillOpacity": 50
            }
          }
        },
        "gridPos": {"h": 8, "w": 12, "x": 12, "y": 4}
      }
    ],
    "templating": {
      "list": [
        {
          "name": "farm_id",
          "type": "custom",
          "current": {"value": "FARM-001", "text": "Farm 001"},
          "query": "FARM-001,FARM-002,FARM-003"
        }
      ]
    }
  }
}
```

### Appendix B: React Component Code

#### WebSocket Provider

```typescript
// providers/WebSocketProvider.tsx
import React, { createContext, useContext, useEffect, useState } from 'react';
import { io, Socket } from 'socket.io-client';

interface WebSocketContextType {
  socket: Socket | null;
  isConnected: boolean;
  latency: number;
  connectionQuality: 'good' | 'poor' | 'offline';
}

const WebSocketContext = createContext<WebSocketContextType | undefined>(undefined);

export const WebSocketProvider: React.FC<{ children: React.ReactNode; token: string }> = ({
  children,
  token,
}) => {
  const [socket, setSocket] = useState<Socket | null>(null);
  const [isConnected, setIsConnected] = useState(false);
  const [latency, setLatency] = useState(0);
  const [connectionQuality, setConnectionQuality] = useState<'good' | 'poor' | 'offline'>('offline');

  useEffect(() => {
    const newSocket = io(process.env.REACT_APP_WS_URL || 'wss://ws.smartdairybd.com', {
      auth: { token },
      transports: ['websocket', 'polling'],
      reconnection: true,
      reconnectionAttempts: 10,
      reconnectionDelay: 1000,
    });

    newSocket.on('connect', () => {
      setIsConnected(true);
      setConnectionQuality('good');
    });

    newSocket.on('disconnect', () => {
      setIsConnected(false);
      setConnectionQuality('offline');
    });

    newSocket.on('connect_error', () => {
      setConnectionQuality('poor');
    });

    // Latency measurement
    const pingInterval = setInterval(() => {
      const start = Date.now();
      newSocket.emit('ping', () => {
        setLatency(Date.now() - start);
      });
    }, 5000);

    setSocket(newSocket);

    return () => {
      clearInterval(pingInterval);
      newSocket.close();
    };
  }, [token]);

  return (
    <WebSocketContext.Provider value={{ socket, isConnected, latency, connectionQuality }}>
      {children}
    </WebSocketContext.Provider>
  );
};

export const useWebSocket = () => {
  const context = useContext(WebSocketContext);
  if (!context) {
    throw new Error('useWebSocket must be used within WebSocketProvider');
  }
  return context;
};
```

### Appendix C: TimescaleDB SQL Queries

```sql
-- Create continuous aggregates for dashboard performance

-- Hourly production summary
CREATE MATERIALIZED VIEW cagg_hourly_production
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 hour', production_time) AS bucket,
    farm_id,
    COUNT(DISTINCT cow_id) AS cows_milked,
    SUM(volume_liters) AS total_volume,
    AVG(fat_percent) AS avg_fat,
    AVG(protein_percent) AS avg_protein
FROM milk_production
GROUP BY bucket, farm_id;

-- Daily production summary
CREATE MATERIALIZED VIEW cagg_daily_production
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 day', production_time) AS bucket,
    farm_id,
    COUNT(DISTINCT cow_id) AS cows_milked,
    SUM(volume_liters) AS total_volume,
    AVG(fat_percent) AS avg_fat,
    AVG(protein_percent) AS avg_protein
FROM milk_production
GROUP BY bucket, farm_id;

-- Hourly environmental averages
CREATE MATERIALIZED VIEW cagg_hourly_environmental
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 hour', time) AS bucket,
    farm_id,
    zone_id,
    AVG(CASE WHEN metric_type = 'temperature' THEN value END) AS avg_temperature,
    AVG(CASE WHEN metric_type = 'humidity' THEN value END) AS avg_humidity,
    AVG(CASE WHEN metric_type = 'nh3' THEN value END) AS avg_nh3
FROM environmental_readings
GROUP BY bucket, farm_id, zone_id;

-- Create refresh policies
SELECT add_continuous_aggregate_policy('cagg_hourly_production',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour');

-- Create indexes for dashboard queries
CREATE INDEX idx_dashboard_alerts ON alerts(farm_id, acknowledged, created_at DESC);
CREATE INDEX idx_dashboard_production ON milk_production(farm_id, production_date DESC);
CREATE INDEX idx_dashboard_environmental ON environmental_readings(farm_id, zone_id, time DESC);
```

### Appendix D: WebSocket Connection Handling

```typescript
// utils/connectionManager.ts
export class ConnectionManager {
  private reconnectAttempts = 0;
  private maxReconnectAttempts = 10;
  private reconnectDelay = 1000;
  private maxReconnectDelay = 30000;
  private offlineQueue: any[] = [];
  private isOnline = navigator.onLine;

  constructor() {
    window.addEventListener('online', () => this.handleOnline());
    window.addEventListener('offline', () => this.handleOffline());
  }

  private handleOnline() {
    this.isOnline = true;
    this.reconnectAttempts = 0;
    this.flushOfflineQueue();
  }

  private handleOffline() {
    this.isOnline = false;
    this.showOfflineIndicator();
  }

  private showOfflineIndicator() {
    const indicator = document.createElement('div');
    indicator.id = 'offline-indicator';
    indicator.className = 'fixed top-0 left-0 right-0 bg-red-500 text-white text-center py-2 z-50';
    indicator.textContent = '⚠️ Connection lost. Working offline...';
    document.body.prepend(indicator);
  }

  private hideOfflineIndicator() {
    const indicator = document.getElementById('offline-indicator');
    if (indicator) indicator.remove();
  }

  private flushOfflineQueue() {
    while (this.offlineQueue.length > 0) {
      const item = this.offlineQueue.shift();
      // Retry sending
    }
    this.hideOfflineIndicator();
  }

  public queueForRetry(data: any) {
    this.offlineQueue.push(data);
  }

  public getReconnectDelay(): number {
    const delay = Math.min(
      this.reconnectDelay * Math.pow(2, this.reconnectAttempts),
      this.maxReconnectDelay
    );
    this.reconnectAttempts++;
    return delay;
  }

  public resetReconnectAttempts() {
    this.reconnectAttempts = 0;
  }
}
```

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | I-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Frontend Developer |
| **Owner** | IoT Architect |
| **Reviewer** | Farm Operations Manager |
| **Classification** | Internal Use |
| **Status** | Approved |

---

**End of Document I-011: IoT Dashboard Implementation**
