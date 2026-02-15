# Smart Dairy Ltd. - Log Aggregation & Analysis (ELK Stack)

| **Document ID** | D-009 |
|-----------------|-------|
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | DevOps Lead |
| **Reviewer** | CTO |
| **Status** | Final |
| **Classification** | Internal Use |

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | DevOps Engineer | Initial version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [ELK Architecture](#2-elk-architecture)
3. [Elasticsearch Setup](#3-elasticsearch-setup)
4. [Logstash Configuration](#4-logstash-configuration)
5. [Kibana Dashboards](#5-kibana-dashboards)
6. [Filebeat Configuration](#6-filebeat-configuration)
7. [Log Management Policies](#7-log-management-policies)
8. [Alerting on Logs](#8-alerting-on-logs)
9. [Security](#9-security)
10. [Appendices](#10-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the Log Aggregation and Analysis architecture for Smart Dairy Ltd.'s web portal system using the ELK (Elasticsearch, Logstash, Kibana) Stack. It provides comprehensive guidelines for collecting, processing, storing, and visualizing logs from all applications and infrastructure components running on the EKS Kubernetes cluster.

### 1.2 Scope

This document covers:

- **Log Sources**: Odoo 19 application, FastAPI microservices, PostgreSQL database, EKS Kubernetes infrastructure
- **Log Formats**: Structured JSON logs and traditional plain text logs
- **Deployment Options**: AWS OpenSearch Service (managed) and self-hosted ELK Stack on EKS
- **Operations**: Log collection, parsing, indexing, retention, and alerting
- **Security**: TLS encryption, authentication, and access control

### 1.3 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| Log Shipper | Elastic Filebeat | 8.x |
| Log Processor | Elastic Logstash | 8.x |
| Search & Storage | Elasticsearch / AWS OpenSearch | 8.x / 2.x |
| Visualization | Kibana / OpenSearch Dashboards | 8.x / 2.x |
| Orchestration | Amazon EKS | 1.29+ |
| Applications | Odoo, FastAPI, PostgreSQL | 19, Latest, 16 |

### 1.4 Document Structure

```
┌─────────────────────────────────────────────────────────────┐
│                    ELK Stack Components                      │
├─────────────────────────────────────────────────────────────┤
│  Filebeat → Logstash → Elasticsearch → Kibana              │
│     ↑                                                    │
│  Kubernetes Pods / Applications / Infrastructure          │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. ELK Architecture

### 2.1 High-Level Architecture

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                              EKS Cluster                                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │ Odoo 19     │  │ FastAPI     │  │ PostgreSQL  │  │ EKS Control Plane   │  │
│  │ Pods        │  │ Services    │  │ Pods        │  │ (API Server, etc.)  │  │
│  │             │  │             │  │             │  │                     │  │
│  │ JSON Logs   │  │ JSON Logs   │  │ CSV/Text    │  │ Kubernetes Events   │  │
│  │ Plain Text  │  │ Structured  │  │ Logs        │  │                     │  │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘  │
│         │                │                │                    │             │
│         └────────────────┴────────────────┴────────────────────┘             │
│                                       │                                       │
│                                       ▼                                       │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                      Filebeat DaemonSet                              │    │
│  │  - Container logs (/var/log/containers/*.log)                        │    │
│  │  - Application logs (mounted volumes)                                │    │
│  │  - System logs (/var/log/syslog, /var/log/messages)                  │    │
│  │  - Kubernetes metadata enrichment                                    │    │
│  └────────────────────────────────────┬────────────────────────────────┘    │
│                                       │                                       │
└───────────────────────────────────────┼───────────────────────────────────────┘
                                        │
                                        ▼
┌──────────────────────────────────────────────────────────────────────────────┐
│                              Logstash Processing Layer                         │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │  Input Stage: Beats input (5044), Syslog input (5140)                    │ │
│  │  Filter Stage: Grok parsing, JSON parsing, GeoIP, Date parsing          │ │
│  │  Output Stage: Elasticsearch indexing with dynamic templates            │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌──────────────────────────────────────────────────────────────────────────────┐
│                           Elasticsearch / OpenSearch                          │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │  Index Strategy:                                                        │ │
│  │  - smart-dairy-logs-YYYY.MM.DD (daily indices)                          │ │
│  │  - smart-dairy-app-logs-YYYY.MM (monthly application logs)              │ │
│  │  - smart-dairy-audit-logs-YYYY.MM (audit trail)                         │ │
│  │                                                                         │ │
│  │  Cluster Configuration:                                                 │ │
│  │  - 3 master nodes, 3 data nodes (hot-warm architecture)                 │ │
│  │  - 1 replica, 1 primary shard per index                                 │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌──────────────────────────────────────────────────────────────────────────────┐
│                              Kibana / Dashboards                               │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │  - Application Performance Dashboard                                    │ │
│  │  - Error Tracking & Analysis Dashboard                                  │ │
│  │  - Security & Audit Dashboard                                           │ │
│  │  - Infrastructure Monitoring Dashboard                                  │ │
│  │  - Business Analytics Dashboard                                         │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌──────────────────────────────────────────────────────────────────────────────┐
│                              Alerting & Notifications                          │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │  - Watcher alerts (error thresholds)                                    │ │
│  │  - PagerDuty / Slack / Email notifications                              │ │
│  │  - Incident management integration                                      │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Data Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           Log Data Flow Pipeline                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Stage 1: Collection                                                        │
│  ─────────────────                                                          │
│  Applications → stdout/stderr → Docker/Containerd → /var/log/containers     │
│           ↓                                                                 │
│  Filebeat (DaemonSet) harvests logs from node filesystem                    │
│           ↓                                                                 │
│  Stage 2: Enrichment                                                        │
│  ─────────────────                                                          │
│  Filebeat adds Kubernetes metadata:                                         │
│    - pod.name, pod.namespace, container.name                                │
│    - node.name, kubernetes.labels                                           │
│    - cloud metadata (AWS region, instance ID)                               │
│           ↓                                                                 │
│  Stage 3: Processing                                                        │
│  ─────────────────                                                          │
│  Logstash receives via Beats input (5044)                                   │
│    - Input: Accepts multiple Filebeat instances                             │
│    - Filter: Parse based on log type (Odoo, FastAPI, PostgreSQL, K8s)       │
│    - Transform: Normalize fields, add tags, geo-IP enrichment               │
│    - Output: Route to appropriate Elasticsearch indices                     │
│           ↓                                                                 │
│  Stage 4: Storage                                                           │
│  ─────────────────                                                          │
│  Elasticsearch indexes with ILM (Index Lifecycle Management):               │
│    - Hot phase: Recent logs, high performance                               │
│    - Warm phase: 7+ day old logs, reduced resources                         │
│    - Cold phase: 30+ day old logs, searchable archive                       │
│    - Delete phase: 90+ day old logs (compliance retention)                  │
│           ↓                                                                 │
│  Stage 5: Visualization & Alerting                                          │
│  ─────────────────────────────────                                          │
│  Kibana: Dashboards, Discover, Visualizations                               │
│  Watcher: Automated alerting on conditions                                  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Component Responsibilities

| Component | Responsibility | Scaling Strategy |
|-----------|---------------|------------------|
| Filebeat | Log collection from all nodes | DaemonSet (1 per node) |
| Logstash | Log parsing and transformation | Horizontal Pod Autoscaler (HPA) based on CPU/memory |
| Elasticsearch | Log indexing and search | Vertical scaling + shard allocation |
| Kibana | Visualization and exploration | Single instance with session affinity |
| Curator | Index lifecycle management | CronJob (daily execution) |

---

## 3. Elasticsearch Setup

### 3.1 Cluster Architecture

#### Option A: Self-Hosted on EKS

```yaml
# elasticsearch-values.yaml
# Helm values for Elastic Cloud on Kubernetes (ECK) or Bitnami Elasticsearch

coordinating:
  replicaCount: 2
  resources:
    limits:
      cpu: 1000m
      memory: 2Gi
    requests:
      cpu: 500m
      memory: 1Gi

data:
  replicaCount: 3
  persistence:
    enabled: true
    size: 500Gi
    storageClass: gp3-encrypted
  resources:
    limits:
      cpu: 2000m
      memory: 8Gi
    requests:
      cpu: 1000m
      memory: 4Gi

master:
  replicaCount: 3
  persistence:
    enabled: true
    size: 50Gi
    storageClass: gp3-encrypted
  resources:
    limits:
      cpu: 1000m
      memory: 4Gi
    requests:
      cpu: 500m
      memory: 2Gi

ingest:
  enabled: false  # Using Logstash for ingest

security:
  enabled: true
  tls:
    restEncryption: true
    transportEncryption: true
  elasticPassword: ${ELASTIC_PASSWORD}
```

#### Option B: AWS OpenSearch Service

```yaml
# opensearch-domain.yaml
# AWS OpenSearch Service Domain configuration

AWSTemplateFormatVersion: '2010-09-09'
Description: 'Smart Dairy OpenSearch Domain'

Resources:
  SmartDairyOpenSearchDomain:
    Type: AWS::OpenSearchService::Domain
    Properties:
      DomainName: smart-dairy-logs
      EngineVersion: 'OpenSearch_2.11'
      
      ClusterConfig:
        InstanceType: r6g.large.search
        InstanceCount: 3
        DedicatedMasterEnabled: true
        DedicatedMasterType: r6g.large.search
        DedicatedMasterCount: 3
        ZoneAwarenessEnabled: true
        ZoneAwarenessConfig:
          AvailabilityZoneCount: 3
        WarmEnabled: true
        WarmType: ultrawarm1.medium.search
        WarmCount: 2
        ColdStorageOptions:
          Enabled: true
      
      EBSOptions:
        EBSEnabled: true
        VolumeType: gp3
        VolumeSize: 500
        Iops: 3000
        Throughput: 125
      
      AccessPolicies:
        Version: '2012-10-17'
        Statement:
          - Effect: Allow
            Principal:
              AWS: 
                - arn:aws:iam::ACCOUNT_ID:role/EKS-Logstash-Role
                - arn:aws:iam::ACCOUNT_ID:role/EKS-Kibana-Role
            Action: 'es:*'
            Resource: 'arn:aws:es:us-east-1:ACCOUNT_ID:domain/smart-dairy-logs/*'
      
      VPCOptions:
        SubnetIds:
          - subnet-abc123
          - subnet-def456
          - subnet-ghi789
        SecurityGroupIds:
          - sg-opensearch-logs
      
      EncryptionAtRestOptions:
        Enabled: true
        KmsKeyId: alias/smart-dairy-logs-key
      
      NodeToNodeEncryptionOptions:
        Enabled: true
      
      DomainEndpointOptions:
        EnforceHTTPS: true
        TLSSecurityPolicy: Policy-Min-TLS-1-2-2019-07
      
      AdvancedSecurityOptions:
        Enabled: true
        InternalUserDatabaseEnabled: true
        MasterUserOptions:
          MasterUserName: admin
          MasterUserPassword: ${MASTER_USER_PASSWORD}
      
      LogPublishingOptions:
        ES_APPLICATION_LOGS:
          CloudWatchLogsLogGroupArn: arn:aws:logs:us-east-1:ACCOUNT_ID:log-group:/aws/opensearch/smart-dairy-logs/application
          Enabled: true
        SEARCH_SLOW_LOGS:
          CloudWatchLogsLogGroupArn: arn:aws:logs:us-east-1:ACCOUNT_ID:log-group:/aws/opensearch/smart-dairy-logs/search-slow
          Enabled: true
        INDEX_SLOW_LOGS:
          CloudWatchLogsLogGroupArn: arn:aws:logs:us-east-1:ACCOUNT_ID:log-group:/aws/opensearch/smart-dairy-logs/index-slow
          Enabled: true
      
      Tags:
        - Key: Project
          Value: SmartDairy
        - Key: Environment
          Value: Production
        - Key: ManagedBy
          Value: DevOps
```

### 3.2 Index Templates

```json
// smart-dairy-logs-template.json
// Index template for application logs
{
  "index_patterns": ["smart-dairy-logs-*"],
  "template": {
    "settings": {
      "number_of_shards": 1,
      "number_of_replicas": 1,
      "index.refresh_interval": "5s",
      "index.lifecycle.name": "smart-dairy-logs-policy",
      "index.lifecycle.rollover_alias": "smart-dairy-logs",
      "analysis": {
        "analyzer": {
          "smart_dairy_analyzer": {
            "type": "custom",
            "tokenizer": "standard",
            "filter": ["lowercase", "asciifolding"]
          }
        }
      }
    },
    "mappings": {
      "dynamic_templates": [
        {
          "strings_as_keywords": {
            "match_mapping_type": "string",
            "mapping": {
              "type": "keyword",
              "ignore_above": 256
            }
          }
        }
      ],
      "properties": {
        "@timestamp": {
          "type": "date"
        },
        "@version": {
          "type": "keyword"
        },
        "log": {
          "properties": {
            "level": {
              "type": "keyword"
            },
            "logger": {
              "type": "keyword"
            },
            "message": {
              "type": "text",
              "analyzer": "smart_dairy_analyzer"
            },
            "thread": {
              "type": "keyword"
            }
          }
        },
        "service": {
          "properties": {
            "name": {
              "type": "keyword"
            },
            "version": {
              "type": "keyword"
            },
            "environment": {
              "type": "keyword"
            }
          }
        },
        "kubernetes": {
          "properties": {
            "pod": {
              "properties": {
                "name": {
                  "type": "keyword"
                },
                "uid": {
                  "type": "keyword"
                }
              }
            },
            "namespace": {
              "type": "keyword"
            },
            "container": {
              "properties": {
                "name": {
                  "type": "keyword"
                },
                "image": {
                  "type": "keyword"
                }
              }
            },
            "node": {
              "properties": {
                "name": {
                  "type": "keyword"
                }
              }
            },
            "labels": {
              "type": "object",
              "enabled": false
            }
          }
        },
        "http": {
          "properties": {
            "request": {
              "properties": {
                "method": {
                  "type": "keyword"
                },
                "url": {
                  "type": "keyword"
                },
                "headers": {
                  "type": "object",
                  "enabled": false
                }
              }
            },
            "response": {
              "properties": {
                "status_code": {
                  "type": "integer"
                },
                "duration_ms": {
                  "type": "float"
                }
              }
            }
          }
        },
        "error": {
          "properties": {
            "type": {
              "type": "keyword"
            },
            "message": {
              "type": "text"
            },
            "stack_trace": {
              "type": "text",
              "index": false
            },
            "code": {
              "type": "keyword"
            }
          }
        },
        "user": {
          "properties": {
            "id": {
              "type": "keyword"
            },
            "email": {
              "type": "keyword"
            },
            "role": {
              "type": "keyword"
            }
          }
        },
        "trace": {
          "properties": {
            "id": {
              "type": "keyword"
            },
            "span": {
              "type": "keyword"
            }
          }
        },
        "geoip": {
          "properties": {
            "location": {
              "type": "geo_point"
            }
          }
        }
      }
    }
  },
  "priority": 500,
  "composed_of": [],
  "version": 1,
  "_meta": {
    "description": "Template for Smart Dairy application logs"
  }
}
```

### 3.3 Index Lifecycle Management (ILM) Policy

```json
// smart-dairy-ilm-policy.json
{
  "policy": {
    "phases": {
      "hot": {
        "min_age": "0ms",
        "actions": {
          "rollover": {
            "max_primary_shard_size": "50gb",
            "max_age": "1d",
            "max_docs": 100000000
          },
          "set_priority": {
            "priority": 100
          }
        }
      },
      "warm": {
        "min_age": "7d",
        "actions": {
          "set_priority": {
            "priority": 50
          },
          "shrink": {
            "number_of_shards": 1
          },
          "forcemerge": {
            "max_num_segments": 1
          },
          "allocate": {
            "require": {
              "data": "warm"
            }
          }
        }
      },
      "cold": {
        "min_age": "30d",
        "actions": {
          "set_priority": {
            "priority": 0
          },
          "freeze": {},
          "allocate": {
            "require": {
              "data": "cold"
            }
          }
        }
      },
      "delete": {
        "min_age": "90d",
        "actions": {
          "delete": {}
        }
      }
    }
  }
}
```

### 3.4 Index Rollover Configuration

```bash
# Create initial index with write alias
PUT /smart-dairy-logs-000001
{
  "aliases": {
    "smart-dairy-logs": {
      "is_write_index": true
    }
  }
}

# Create ILM policy
PUT /_ilm/policy/smart-dairy-logs-policy
{
  "policy": {
    "phases": {
      "hot": {
        "min_age": "0ms",
        "actions": {
          "rollover": {
            "max_primary_shard_size": "50gb",
            "max_age": "1d",
            "max_docs": 100000000
          }
        }
      },
      "warm": {
        "min_age": "7d",
        "actions": {
          "shrink": {
            "number_of_shards": 1
          },
          "forcemerge": {
            "max_num_segments": 1
          }
        }
      },
      "cold": {
        "min_age": "30d",
        "actions": {
          "freeze": {}
        }
      },
      "delete": {
        "min_age": "90d",
        "actions": {
          "delete": {}
        }
      }
    }
  }
}

# Create component template for mappings
PUT /_component_template/smart-dairy-mappings
{
  "template": {
    "mappings": {
      "properties": {
        "@timestamp": {
          "type": "date"
        },
        "message": {
          "type": "text"
        },
        "log.level": {
          "type": "keyword"
        },
        "service.name": {
          "type": "keyword"
        }
      }
    }
  }
}

# Create index template
PUT /_index_template/smart-dairy-logs-template
{
  "index_patterns": ["smart-dairy-logs-*"],
  "composed_of": ["smart-dairy-mappings"],
  "priority": 500,
  "template": {
    "settings": {
      "index.lifecycle.name": "smart-dairy-logs-policy",
      "index.lifecycle.rollover_alias": "smart-dairy-logs"
    }
  }
}
```

---

## 4. Logstash Configuration

### 4.1 Kubernetes Deployment

```yaml
# logstash-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: logstash
  namespace: logging
  labels:
    app: logstash
    component: log-processor
spec:
  replicas: 2
  selector:
    matchLabels:
      app: logstash
  template:
    metadata:
      labels:
        app: logstash
        component: log-processor
    spec:
      containers:
        - name: logstash
          image: docker.elastic.co/logstash/logstash:8.11.0
          ports:
            - containerPort: 5044
              name: beats
            - containerPort: 9600
              name: monitoring
          env:
            - name: LS_JAVA_OPTS
              value: "-Xmx2g -Xms2g"
            - name: ELASTICSEARCH_HOST
              valueFrom:
                secretKeyRef:
                  name: elasticsearch-credentials
                  key: host
            - name: ELASTICSEARCH_USERNAME
              valueFrom:
                secretKeyRef:
                  name: elasticsearch-credentials
                  key: username
            - name: ELASTICSEARCH_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: elasticsearch-credentials
                  key: password
          resources:
            limits:
              cpu: "2000m"
              memory: "4Gi"
            requests:
              cpu: "1000m"
              memory: "2Gi"
          volumeMounts:
            - name: logstash-config
              mountPath: /usr/share/logstash/config/logstash.yml
              subPath: logstash.yml
            - name: logstash-pipeline
              mountPath: /usr/share/logstash/pipeline
            - name: logstash-patterns
              mountPath: /usr/share/logstash/patterns
          livenessProbe:
            httpGet:
              path: /
              port: 9600
            initialDelaySeconds: 60
            periodSeconds: 30
          readinessProbe:
            httpGet:
              path: /
              port: 9600
            initialDelaySeconds: 30
            periodSeconds: 10
      volumes:
        - name: logstash-config
          configMap:
            name: logstash-config
        - name: logstash-pipeline
          configMap:
            name: logstash-pipeline
        - name: logstash-patterns
          configMap:
            name: logstash-patterns
---
apiVersion: v1
kind: Service
metadata:
  name: logstash
  namespace: logging
  labels:
    app: logstash
spec:
  selector:
    app: logstash
  ports:
    - name: beats
      port: 5044
      targetPort: 5044
      protocol: TCP
    - name: monitoring
      port: 9600
      targetPort: 9600
      protocol: TCP
  type: ClusterIP
---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: logstash-hpa
  namespace: logging
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: logstash
  minReplicas: 2
  maxReplicas: 6
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
```

### 4.2 Logstash Configuration Files

```yaml
# logstash-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: logstash-config
  namespace: logging
data:
  logstash.yml: |
    http.host: "0.0.0.0"
    xpack.monitoring.enabled: true
    xpack.monitoring.elasticsearch.hosts: ["${ELASTICSEARCH_HOST}"]
    xpack.monitoring.elasticsearch.username: "${ELASTICSEARCH_USERNAME}"
    xpack.monitoring.elasticsearch.password: "${ELASTICSEARCH_PASSWORD}"
    log.level: info
    path.data: /usr/share/logstash/data
    pipeline.workers: 4
    pipeline.batch.size: 125
    pipeline.batch.delay: 50
```

### 4.3 Pipeline Configuration

```yaml
# logstash-pipeline-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: logstash-pipeline
  namespace: logging
data:
  smart-dairy-pipeline.conf: |
    # ===========================================
    # Input Section
    # ===========================================
    input {
      beats {
        port => 5044
        type => "beats"
        ssl => false
        tags => ["kubernetes", "filebeat"]
      }
      
      # Optional: Direct syslog input from external sources
      syslog {
        port => 5140
        type => "syslog"
        tags => ["syslog", "external"]
      }
    }

    # ===========================================
    # Filter Section - Smart Dairy Log Processing
    # ===========================================
    filter {
      # Common processing for all logs
      if [@metadata][beat] {
        mutate {
          add_field => { "[@metadata][index_prefix]" => "smart-dairy-logs" }
        }
      }

      # -------------------------------------------------
      # Odoo 19 Application Logs
      # -------------------------------------------------
      if [kubernetes][labels][app] == "odoo" or [service][name] == "odoo" {
        mutate {
          add_tag => ["odoo"]
          add_field => { "[@metadata][index_prefix]" => "smart-dairy-odoo" }
        }

        # Parse Odoo log format
        # Format: 2024-01-15 10:30:45,123 12345 INFO odoo.modules.loading: loading 1 modules...
        grok {
          match => { 
            "message" => [
              "%{TIMESTAMP_ISO8601:odoo.timestamp} %{NUMBER:odoo.process_id} %{LOGLEVEL:log.level} %{DATA:odoo.logger}: %{GREEDYDATA:log.message}",
              "%{TIMESTAMP_ISO8601:odoo.timestamp} %{NUMBER:odoo.process_id} %{LOGLEVEL:log.level} %{DATA:odoo.logger} %{GREEDYDATA:log.message}"
            ]
          }
          tag_on_failure => ["_grokparsefailure_odoo"]
        }

        # Parse timestamp
        date {
          match => [ "odoo.timestamp", "YYYY-MM-dd HH:mm:ss,SSS" ]
          target => "@timestamp"
          remove_field => [ "odoo.timestamp" ]
        }

        # Extract Odoo-specific fields
        if [log.message] =~ /database/ {
          grok {
            match => { "log.message" => "database %{DATA:odoo.database_name}" }
            tag_on_failure => []
          }
        }

        if [log.message] =~ /module/ {
          grok {
            match => { "log.message" => "module %{DATA:odoo.module_name}" }
            tag_on_failure => []
          }
        }

        # Detect Odoo errors
        if [log.level] in ["ERROR", "CRITICAL", "WARNING"] {
          mutate {
            add_field => { "alert.priority" => "high" }
            add_tag => ["odoo_error"]
          }
        }
      }

      # -------------------------------------------------
      # FastAPI Service Logs
      # -------------------------------------------------
      else if [kubernetes][labels][app] == "fastapi" or [service][name] == "fastapi" {
        mutate {
          add_tag => ["fastapi"]
          add_field => { "[@metadata][index_prefix]" => "smart-dairy-fastapi" }
        }

        # Try JSON parsing first (structured logging)
        json {
          source => "message"
          target => "parsed"
          skip_on_invalid_json => true
        }

        if [parsed] {
          mutate {
            rename => {
              "[parsed][level]" => "log.level"
              "[parsed][message]" => "log.message"
              "[parsed][logger]" => "log.logger"
              "[parsed][timestamp]" => "timestamp"
              "[parsed][request_id]" => "trace.id"
              "[parsed][user_id]" => "user.id"
              "[parsed][method]" => "http.request.method"
              "[parsed][path]" => "http.request.url"
              "[parsed][status_code]" => "http.response.status_code"
              "[parsed][duration_ms]" => "http.response.duration_ms"
            }
            remove_field => ["parsed", "message"]
          }

          date {
            match => [ "timestamp", "ISO8601" ]
            target => "@timestamp"
            remove_field => ["timestamp"]
          }
        } else {
          # Fallback: Parse standard Python logging format
          # Format: 2024-01-15 10:30:45,123 - app - INFO - message
          grok {
            match => { 
              "message" => [
                "%{TIMESTAMP_ISO8601:timestamp} - %{DATA:log.logger} - %{LOGLEVEL:log.level} - %{GREEDYDATA:log.message}",
                "%{TIMESTAMP_ISO8601:timestamp} %{DATA:log.logger} %{LOGLEVEL:log.level} %{GREEDYDATA:log.message}"
              ]
            }
            tag_on_failure => ["_grokparsefailure_fastapi"]
          }

          date {
            match => [ "timestamp", "ISO8601" ]
            target => "@timestamp"
            remove_field => ["timestamp"]
          }
        }

        # Extract HTTP information if present
        if [log.message] =~ /HTTP/ {
          grok {
            match => { 
              "log.message" => [
                "%{WORD:http.request.method} %{URIPATH:http.request.url} HTTP/%{NUMBER:http.version} %{NUMBER:http.response.status_code:int} %{NUMBER:http.response.duration_ms:float}ms",
                "%{WORD:http.request.method} %{URIPATH:http.request.url} HTTP/%{NUMBER:http.version} %{NUMBER:http.response.status_code:int}"
              ]
            }
            tag_on_failure => []
          }
        }

        # Detect API errors
        if [http.response.status_code] and [http.response.status_code] >= 500 {
          mutate {
            add_field => { "alert.priority" => "critical" }
            add_tag => ["api_error", "server_error"]
          }
        } else if [http.response.status_code] and [http.response.status_code] >= 400 {
          mutate {
            add_field => { "alert.priority" => "medium" }
            add_tag => ["api_error", "client_error"]
          }
        }
      }

      # -------------------------------------------------
      # PostgreSQL Database Logs
      # -------------------------------------------------
      else if [kubernetes][labels][app] == "postgresql" or [service][name] == "postgresql" {
        mutate {
          add_tag => ["postgresql"]
          add_field => { "[@metadata][index_prefix]" => "smart-dairy-postgresql" }
        }

        # Parse PostgreSQL log format
        # Format: 2024-01-15 10:30:45.123 EST [12345] user@database LOG:  message
        grok {
          match => { 
            "message" => [
              "%{TIMESTAMP_ISO8601:pg.timestamp} %{TZ:pg.timezone}? \[%{NUMBER:pg.process_id}\] %{DATA:pg.user}@%{DATA:pg.database} %{LOGLEVEL:log.level}:  %{GREEDYDATA:log.message}",
              "%{TIMESTAMP_ISO8601:pg.timestamp} \[%{NUMBER:pg.process_id}\] %{DATA:pg.user}@%{DATA:pg.database} %{LOGLEVEL:log.level}:  %{GREEDYDATA:log.message}",
              "%{TIMESTAMP_ISO8601:pg.timestamp} %{TZ:pg.timezone}? \[%{NUMBER:pg.process_id}\] %{LOGLEVEL:log.level}:  %{GREEDYDATA:log.message}"
            ]
          }
          tag_on_failure => ["_grokparsefailure_postgresql"]
        }

        date {
          match => [ "pg.timestamp", "YYYY-MM-dd HH:mm:ss.SSS" ]
          target => "@timestamp"
          remove_field => [ "pg.timestamp" ]
        }

        # Detect slow queries
        if [log.message] =~ /duration:/ {
          grok {
            match => { "log.message" => "duration: %{NUMBER:pg.duration_ms:float} ms" }
            tag_on_failure => []
          }

          if [pg.duration_ms] and [pg.duration_ms] > 1000 {
            mutate {
              add_tag => ["slow_query"]
              add_field => { "alert.priority" => "medium" }
            }
          }
        }

        # Detect connection issues
        if [log.message] =~ /connection/ and [log.level] in ["ERROR", "FATAL"] {
          mutate {
            add_tag => ["db_connection_error"]
            add_field => { "alert.priority" => "high" }
          }
        }
      }

      # -------------------------------------------------
      # Kubernetes System Logs
      # -------------------------------------------------
      else if [kubernetes] and ![service][name] {
        mutate {
          add_tag => ["kubernetes_system"]
        }

        # Parse container logs
        json {
          source => "message"
          target => "container_log"
          skip_on_invalid_json => true
        }

        if [container_log] {
          mutate {
            rename => {
              "[container_log][level]" => "log.level"
              "[container_log][msg]" => "log.message"
              "[container_log][ts]" => "timestamp"
            }
            remove_field => ["container_log"]
          }

          date {
            match => [ "timestamp", "ISO8601" ]
            target => "@timestamp"
            remove_field => ["timestamp"]
          }
        }
      }

      # -------------------------------------------------
      # Common Processing for All Logs
      # -------------------------------------------------
      # Normalize log levels
      translate {
        field => "log.level"
        destination => "log.level_normalized"
        dictionary => {
          "debug" => "DEBUG"
          "info" => "INFO"
          "warn" => "WARNING"
          "warning" => "WARNING"
          "error" => "ERROR"
          "fatal" => "FATAL"
          "critical" => "CRITICAL"
        }
        fallback => "UNKNOWN"
        override => true
      }

      # Extract error details if present
      if [log.level] in ["ERROR", "FATAL", "CRITICAL"] {
        grok {
          match => { 
            "log.message" => [
              "%{DATA:error.type}: %{GREEDYDATA:error.message}",
              "Exception: %{GREEDYDATA:error.message}",
              "Error: %{GREEDYDATA:error.message}"
            ]
          }
          tag_on_failure => []
        }

        if [error.message] {
          mutate {
            add_tag => ["error_detected"]
          }
        }
      }

      # GeoIP enrichment for external requests (if available)
      if [client][ip] {
        geoip {
          source => "[client][ip]"
          target => "geoip"
          database => "/usr/share/logstash/GeoLite2-City.mmdb"
          default_database_type => "City"
        }
      }

      # Add environment metadata
      mutate {
        add_field => { 
          "service.environment" => "${ENVIRONMENT:production}"
          "cloud.provider" => "aws"
          "cloud.region" => "${AWS_REGION:us-east-1}"
        }
      }

      # Clean up fields
      mutate {
        remove_field => ["@version", "agent", "ecs", "input", "log", "host"]
        remove_tag => ["beats_input_codec_plain_applied"]
      }
    }

    # ===========================================
    # Output Section
    # ===========================================
    output {
      # Debug output for development
      # stdout { codec => rubydebug }

      # Elasticsearch output with dynamic index naming
      elasticsearch {
        hosts => ["${ELASTICSEARCH_HOST}"]
        user => "${ELASTICSEARCH_USERNAME}"
        password => "${ELASTICSEARCH_PASSWORD}"
        
        # Dynamic index naming based on service and date
        index => "%{[@metadata][index_prefix]}-%{+YYYY.MM.dd}"
        
        # Enable ILM
        ilm_enabled => true
        ilm_rollover_alias => "%{[@metadata][index_prefix]}"
        ilm_pattern => "{now/d}-000001"
        ilm_policy => "smart-dairy-logs-policy"
        
        # SSL Configuration
        ssl => true
        ssl_certificate_verification => true
        cacert => "/usr/share/logstash/config/certs/ca.crt"
        
        # Performance tuning
        template_overwrite => false
        manage_template => false
        
        # Retry settings
        retry_on_conflict => 3
        retry_max_interval => 60
        retry_max_items => 500
        
        # Batch settings
        flush_size => 500
        idle_flush_time => 10
      }

      # Optional: Send critical errors to separate index for immediate attention
      if "error_detected" in [tags] and [alert.priority] in ["high", "critical"] {
        elasticsearch {
          hosts => ["${ELASTICSEARCH_HOST}"]
          user => "${ELASTICSEARCH_USERNAME}"
          password => "${ELASTICSEARCH_PASSWORD}"
          index => "smart-dairy-alerts-%{+YYYY.MM.dd}"
          ssl => true
          cacert => "/usr/share/logstash/config/certs/ca.crt"
        }
      }
    }
```

### 4.4 Grok Patterns (Custom)

```yaml
# logstash-patterns-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: logstash-patterns
  namespace: logging
data:
  # Custom patterns for Smart Dairy applications
  smart-dairy.patterns: |
    # Odoo patterns
    ODOO_MODULE [a-zA-Z0-9_]+
    ODOO_DATABASE [a-zA-Z0-9_]+
    ODOO_MODEL [a-zA-Z.]+  
    
    # FastAPI patterns
    FASTAPI_REQUEST_ID [a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}
    FASTAPI_ENDPOINT /[a-zA-Z0-9/_-]+
    
    # PostgreSQL patterns
    PG_STATEMENT [A-Z]+\s+[a-zA-Z0-9_.,\s]+
    PG_CONNECTION db=\w+ user=\w+
    PG_LOCK_WAIT deadlock detected|lock wait timeout|could not obtain lock
    
    # HTTP patterns
    HTTP_METHOD GET|POST|PUT|DELETE|PATCH|HEAD|OPTIONS
    HTTP_STATUS [1-5][0-9]{2}
    HTTP_VERSION 1\.[01]|2\.[01]
    
    # Error patterns
    PYTHON_EXCEPTION [A-Z][a-zA-Z0-9]*Error|Exception|Warning
    ODOO_EXCEPTION odoo\.exceptions\.[A-Za-z]+
    SQL_EXCEPTION psycopg2\.[A-Za-z]+|sqlalchemy\.[A-Za-z]+
    
    # Timestamp patterns
    ODOO_TIMESTAMP %{YEAR}-%{MONTHNUM}-%{MONTHDAY} %{HOUR}:%{MINUTE}:%{SECOND},%{INT}
    PG_TIMESTAMP %{YEAR}-%{MONTHNUM}-%{MONTHDAY} %{HOUR}:%{MINUTE}:%{SECOND}\.%{INT}
    
    # User patterns
    USER_EMAIL [a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}
    USER_ID [0-9]+
    SESSION_ID [a-f0-9]{32}
    
    # Odoo log line - comprehensive pattern
    ODOO_LOG_LINE %{ODOO_TIMESTAMP:timestamp} %{NUMBER:pid} %{LOGLEVEL:level} %{DATA:logger} %{GREEDYDATA:message}
    
    # FastAPI access log
    FASTAPI_ACCESS %{IPORHOST:client_ip} - %{USER:ident} \[%{HTTPDATE:timestamp}\] "%{HTTP_METHOD:method} %{URIPATHPARAM:request} HTTP/%{NUMBER:http_version}" %{NUMBER:status} %{NUMBER:bytes} "%{URI:referrer}" "%{DATA:agent}" %{NUMBER:duration_ms:float}
    
    # PostgreSQL slow query log
    PG_SLOW_QUERY %{PG_TIMESTAMP:timestamp} %{TZ:tz}? \[%{NUMBER:pid}\] %{DATA:user}@%{DATA:db} %{LOGLEVEL:level}:  duration: %{NUMBER:duration_ms:float} ms  statement: %{DATA:query}
```

---

## 5. Kibana Dashboards

### 5.1 Dashboard Export Configuration

```json
// kibana-dashboards-export.json
{
  "version": "8.11.0",
  "objects": [
    {
      "id": "smart-dairy-overview-dashboard",
      "type": "dashboard",
      "attributes": {
        "title": "Smart Dairy - Overview Dashboard",
        "hits": 0,
        "description": "High-level overview of all Smart Dairy application logs and system health",
        "panelsJSON": "[{\"id\":\"log-volume-over-time\",\"type\":\"visualization\",\"panelIndex\":1,\"gridData\":{\"x\":0,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"log-levels-distribution\",\"type\":\"visualization\",\"panelIndex\":2,\"gridData\":{\"x\":24,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"error-rate-by-service\",\"type\":\"visualization\",\"panelIndex\":3,\"gridData\":{\"x\":0,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"top-error-messages\",\"type\":\"visualization\",\"panelIndex\":4,\"gridData\":{\"x\":24,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"}]",
        "optionsJSON": "{\"useMargins\":true,\"syncColors\":false,\"hidePanelTitles\":false}",
        "version": 1,
        "timeRestore": false,
        "kibanaSavedObjectMeta": {
          "searchSourceJSON": "{\"query\":{\"language\":\"kuery\",\"query\":\"\"},\"filter\":[]}"
        }
      },
      "references": []
    },
    {
      "id": "smart-dairy-odoo-dashboard",
      "type": "dashboard",
      "attributes": {
        "title": "Smart Dairy - Odoo Application Dashboard",
        "hits": 0,
        "description": "Detailed monitoring of Odoo 19 application logs, module loading, and performance",
        "panelsJSON": "[{\"id\":\"odoo-log-volume\",\"type\":\"visualization\",\"panelIndex\":1,\"gridData\":{\"x\":0,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"odoo-module-loading\",\"type\":\"visualization\",\"panelIndex\":2,\"gridData\":{\"x\":24,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"odoo-database-operations\",\"type\":\"visualization\",\"panelIndex\":3,\"gridData\":{\"x\":0,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"odoo-error-details\",\"type\":\"visualization\",\"panelIndex\":4,\"gridData\":{\"x\":24,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"}]",
        "optionsJSON": "{\"useMargins\":true,\"syncColors\":false,\"hidePanelTitles\":false}"
      }
    },
    {
      "id": "smart-dairy-fastapi-dashboard",
      "type": "dashboard",
      "attributes": {
        "title": "Smart Dairy - FastAPI Services Dashboard",
        "hits": 0,
        "description": "API performance, request/response metrics, and error tracking for FastAPI services",
        "panelsJSON": "[{\"id\":\"fastapi-request-volume\",\"type\":\"visualization\",\"panelIndex\":1,\"gridData\":{\"x\":0,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"fastapi-response-times\",\"type\":\"visualization\",\"panelIndex\":2,\"gridData\":{\"x\":24,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"fastapi-http-status-codes\",\"type\":\"visualization\",\"panelIndex\":3,\"gridData\":{\"x\":0,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"fastapi-top-endpoints\",\"type\":\"visualization\",\"panelIndex\":4,\"gridData\":{\"x\":24,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"}]",
        "optionsJSON": "{\"useMargins\":true,\"syncColors\":false,\"hidePanelTitles\":false}"
      }
    },
    {
      "id": "smart-dairy-postgresql-dashboard",
      "type": "dashboard",
      "attributes": {
        "title": "Smart Dairy - PostgreSQL Database Dashboard",
        "hits": 0,
        "description": "Database performance metrics, slow query analysis, and connection monitoring",
        "panelsJSON": "[{\"id\":\"postgres-connection-count\",\"type\":\"visualization\",\"panelIndex\":1,\"gridData\":{\"x\":0,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"postgres-slow-queries\",\"type\":\"visualization\",\"panelIndex\":2,\"gridData\":{\"x\":24,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"postgres-query-duration\",\"type\":\"visualization\",\"panelIndex\":3,\"gridData\":{\"x\":0,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"postgres-error-log\",\"type\":\"visualization\",\"panelIndex\":4,\"gridData\":{\"x\":24,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"}]",
        "optionsJSON": "{\"useMargins\":true,\"syncColors\":false,\"hidePanelTitles\":false}"
      }
    },
    {
      "id": "smart-dairy-security-dashboard",
      "type": "dashboard",
      "attributes": {
        "title": "Smart Dairy - Security & Audit Dashboard",
        "hits": 0,
        "description": "Security events, authentication logs, and audit trail monitoring",
        "panelsJSON": "[{\"id\":\"security-login-attempts\",\"type\":\"visualization\",\"panelIndex\":1,\"gridData\":{\"x\":0,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"security-failed-logins\",\"type\":\"visualization\",\"panelIndex\":2,\"gridData\":{\"x\":24,\"y\":0,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"security-access-by-user\",\"type\":\"visualization\",\"panelIndex\":3,\"gridData\":{\"x\":0,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"},{\"id\":\"security-suspicious-activity\",\"type\":\"visualization\",\"panelIndex\":4,\"gridData\":{\"x\":24,\"y\":15,\"w\":24,\"h\":15},\"version\":\"8.11.0\"}]",
        "optionsJSON": "{\"useMargins\":true,\"syncColors\":false,\"hidePanelTitles\":false}"
      }
    }
  ]
}
```

### 5.2 Kibana Saved Objects Import Job

```yaml
# kibana-import-job.yaml
apiVersion: batch/v1
kind: Job
metadata:
  name: kibana-dashboard-import
  namespace: logging
  labels:
    app: kibana
    task: import-dashboards
spec:
  template:
    spec:
      restartPolicy: OnFailure
      containers:
        - name: import-dashboards
          image: curlimages/curl:latest
          command:
            - sh
            - -c
            - |
              echo "Waiting for Kibana to be ready..."
              until curl -s -o /dev/null -w "%{http_code}" http://kibana:5601/api/status | grep -q "200"; do
                echo "Kibana not ready yet, waiting..."
                sleep 10
              done
              echo "Kibana is ready, importing dashboards..."
              
              # Import dashboards
              curl -X POST \
                http://kibana:5601/api/saved_objects/_import \
                -H "kbn-xsrf: true" \
                -H "Content-Type: multipart/form-data" \
                -F file=@/dashboards/kibana-dashboards.ndjson \
                --fail
              
              echo "Dashboard import completed successfully"
          volumeMounts:
            - name: dashboards
              mountPath: /dashboards
      volumes:
        - name: dashboards
          configMap:
            name: kibana-dashboards
            items:
              - key: dashboards.ndjson
                path: kibana-dashboards.ndjson
```

### 5.3 Dashboard Descriptions

#### 5.3.1 Overview Dashboard

| Panel | Description | Query |
|-------|-------------|-------|
| Log Volume Over Time | Line chart showing log volume by service | `*` |
| Log Levels Distribution | Pie chart of ERROR/WARNING/INFO/DEBUG | `*` |
| Error Rate by Service | Bar chart of error counts per service | `log.level:ERROR OR log.level:CRITICAL` |
| Top Error Messages | Table of most frequent error messages | `log.level:ERROR` |

#### 5.3.2 Odoo Application Dashboard

| Panel | Description | Query |
|-------|-------------|-------|
| Log Volume | Log count over time | `kubernetes.labels.app:odoo` |
| Module Loading | Module load events and timing | `odoo.logger:odoo.modules.loading` |
| Database Operations | DB connection and query logs | `odoo.logger:*database*` |
| Error Details | Detailed error view with stack traces | `kubernetes.labels.app:odoo AND log.level:ERROR` |

#### 5.3.3 FastAPI Services Dashboard

| Panel | Description | Query |
|-------|-------------|-------|
| Request Volume | HTTP requests per minute | `kubernetes.labels.app:fastapi` |
| Response Times | P50, P95, P99 latency percentiles | `http.response.duration_ms:*` |
| HTTP Status Codes | Distribution of 2xx, 4xx, 5xx | `http.response.status_code:*` |
| Top Endpoints | Most frequently accessed APIs | `http.request.url:*` |

#### 5.3.4 PostgreSQL Database Dashboard

| Panel | Description | Query |
|-------|-------------|-------|
| Connection Count | Active database connections | `kubernetes.labels.app:postgresql` |
| Slow Queries | Queries taking > 1000ms | `pg.duration_ms > 1000` |
| Query Duration | Average query duration over time | `pg.duration_ms:*` |
| Error Log | Database errors and warnings | `log.level:ERROR OR log.level:WARNING` |

---

## 6. Filebeat Configuration

### 6.1 Filebeat DaemonSet Deployment

```yaml
# filebeat-daemonset.yaml
apiVersion: apps/v1
kind: DaemonSet
metadata:
  name: filebeat
  namespace: logging
  labels:
    app: filebeat
    component: log-shipper
spec:
  selector:
    matchLabels:
      app: filebeat
  template:
    metadata:
      labels:
        app: filebeat
        component: log-shipper
    spec:
      serviceAccountName: filebeat
      terminationGracePeriodSeconds: 30
      hostNetwork: true
      dnsPolicy: ClusterFirstWithHostNet
      containers:
        - name: filebeat
          image: docker.elastic.co/beats/filebeat:8.11.0
          args: ["-c", "/etc/filebeat.yml", "-e"]
          env:
            - name: ELASTICSEARCH_HOST
              value: "elasticsearch.logging.svc.cluster.local:9200"
            - name: ELASTICSEARCH_USERNAME
              valueFrom:
                secretKeyRef:
                  name: elasticsearch-credentials
                  key: username
            - name: ELASTICSEARCH_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: elasticsearch-credentials
                  key: password
            - name: LOGSTASH_HOST
              value: "logstash.logging.svc.cluster.local:5044"
            - name: NODE_NAME
              valueFrom:
                fieldRef:
                  fieldPath: spec.nodeName
            - name: POD_NAMESPACE
              valueFrom:
                fieldRef:
                  fieldPath: metadata.namespace
          securityContext:
            runAsUser: 0
            capabilities:
              add:
                - DAC_READ_SEARCH
          resources:
            limits:
              cpu: "1000m"
              memory: "1Gi"
            requests:
              cpu: "100m"
              memory: "256Mi"
          volumeMounts:
            - name: config
              mountPath: /etc/filebeat.yml
              subPath: filebeat.yml
              readOnly: true
            - name: data
              mountPath: /usr/share/filebeat/data
            - name: varlibdockercontainers
              mountPath: /var/lib/docker/containers
              readOnly: true
            - name: varlog
              mountPath: /var/log
              readOnly: true
            - name: varrun
              mountPath: /var/run
              readOnly: true
            - name: sys
              mountPath: /hostfs/sys
              readOnly: true
            - name: proc
              mountPath: /hostfs/proc
              readOnly: true
          livenessProbe:
            exec:
              command:
                - sh
                - -c
                - |
                  #!/usr/bin/env bash -e
                  filebeat test output
            initialDelaySeconds: 60
            periodSeconds: 30
      volumes:
        - name: config
          configMap:
            defaultMode: 0640
            name: filebeat-config
        - name: data
          hostPath:
            path: /var/lib/filebeat-data
            type: DirectoryOrCreate
        - name: varlibdockercontainers
          hostPath:
            path: /var/lib/docker/containers
        - name: varlog
          hostPath:
            path: /var/log
        - name: varrun
          hostPath:
            path: /var/run
        - name: sys
          hostPath:
            path: /sys
        - name: proc
          hostPath:
            path: /proc
---
apiVersion: v1
kind: ServiceAccount
metadata:
  name: filebeat
  namespace: logging
  labels:
    app: filebeat
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: filebeat
  labels:
    app: filebeat
rules:
  - apiGroups: [""]
    resources:
      - namespaces
      - pods
      - nodes
    verbs:
      - get
      - watch
      - list
  - apiGroups: ["apps"]
    resources:
      - replicasets
    verbs:
      - get
      - list
      - watch
---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: filebeat
subjects:
  - kind: ServiceAccount
    name: filebeat
    namespace: logging
roleRef:
  kind: ClusterRole
  name: filebeat
  apiGroup: rbac.authorization.k8s.io
```

### 6.2 Filebeat Configuration

```yaml
# filebeat-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: filebeat-config
  namespace: logging
  labels:
    app: filebeat
data:
  filebeat.yml: |
    # ============================================
    # Filebeat Configuration - Smart Dairy
    # ============================================
    
    filebeat.inputs:
      # ------------------------------------------
      # Container Logs Input
      # ------------------------------------------
      - type: container
        enabled: true
        paths:
          - /var/log/containers/*.log
        
        # Multiline configuration for stack traces
        multiline.pattern: '^\['
        multiline.negate: true
        multiline.match: after
        multiline.timeout: 5s
        
        # Exclude certain containers if needed
        exclude_files:
          - '\.gz$'
        
        # Add Kubernetes metadata
        processors:
          - add_kubernetes_metadata:
              host: ${NODE_NAME}
              namespace: ${POD_NAMESPACE}
              matchers:
                - logs_path:
                    logs_path: "/var/log/containers/"
          - add_fields:
              target: service
              fields:
                environment: production
        
        # Fields to add to all container logs
        fields:
          log_source: kubernetes_container
        fields_under_root: true
        
        # Harvester settings
        close_inactive: 5m
        clean_inactive: 72h
        ignore_older: 24h

      # ------------------------------------------
      # System Logs Input
      # ------------------------------------------
      - type: filestream
        enabled: true
        id: system-logs
        paths:
          - /var/log/syslog
          - /var/log/messages
          - /var/log/auth.log
          - /var/log/secure
        
        parsers:
          - syslog:
              format: auto
        
        processors:
          - add_fields:
              target: service
              fields:
                name: system
                type: infrastructure
        
        fields:
          log_source: system
        fields_under_root: true

      # ------------------------------------------
      # Kubernetes Audit Logs (if enabled)
      # ------------------------------------------
      - type: filestream
        enabled: true
        id: k8s-audit
        paths:
          - /var/log/kubernetes/audit.log
        
        parsers:
          - json:
              keys_under_root: true
        
        processors:
          - add_fields:
              target: service
              fields:
                name: kubernetes-audit
                type: security
        
        fields:
          log_source: k8s_audit
        fields_under_root: true

    # ============================================
    # Filebeat Modules
    # ============================================
    filebeat.modules:
      - module: system
        syslog:
          enabled: true
        auth:
          enabled: true
      
      - module: postgresql
        log:
          enabled: true
          var.paths:
            - /var/log/containers/postgresql*.log
      
      - module: redis
        log:
          enabled: false
      
      - module: nginx
        access:
          enabled: false
        error:
          enabled: false

    # ============================================
    # Autodiscover (Dynamic Configuration)
    # ============================================
    filebeat.autodiscover:
      providers:
        - type: kubernetes
          node: ${NODE_NAME}
          hints.enabled: true
          hints.default_config:
            type: container
            paths:
              - /var/log/containers/*${data.kubernetes.container.id}.log
          templates:
            # Odoo-specific configuration
            - condition:
                equals:
                  kubernetes.labels.app: odoo
              config:
                - type: container
                  paths:
                    - /var/log/containers/*${data.kubernetes.container.id}.log
                  multiline:
                    pattern: '^\d{4}-\d{2}-\d{2}'
                    negate: true
                    match: after
                  processors:
                    - add_tags:
                        tags: [odoo]
                    - add_fields:
                        target: service
                        fields:
                          name: odoo
                          version: "19"
            
            # FastAPI-specific configuration
            - condition:
                equals:
                  kubernetes.labels.app: fastapi
              config:
                - type: container
                  paths:
                    - /var/log/containers/*${data.kubernetes.container.id}.log
                  processors:
                    - add_tags:
                        tags: [fastapi]
                    - add_fields:
                        target: service
                        fields:
                          name: fastapi

    # ============================================
    # Processors (Global)
    # ============================================
    processors:
      - add_host_metadata:
          when.not.contains.tags: forwarded
      
      - add_cloud_metadata:
          provider: aws
      
      - add_docker_metadata:
          host: "unix:///var/run/docker.sock"
      
      - add_kubernetes_metadata:
          host: ${NODE_NAME}
          namespace: ${POD_NAMESPACE}
      
      # Drop unnecessary fields
      - drop_fields:
          fields:
            - agent.ephemeral_id
            - agent.hostname
            - agent.id
            - agent.name
            - agent.type
            - agent.version
            - ecs.version
            - input.type
            - log.offset
            - log.file.path
          ignore_missing: true
      
      # Add environment info
      - add_fields:
          target: cloud
          fields:
            provider: aws
            region: ${AWS_REGION:us-east-1}
      
      # Rate limiting (optional)
      # - rate_limit:
      #     limit: "100/s"

    # ============================================
    # Output Configuration
    # ============================================
    # Option 1: Direct to Elasticsearch
    # output.elasticsearch:
    #   hosts: ["${ELASTICSEARCH_HOST}"]
    #   username: "${ELASTICSEARCH_USERNAME}"
    #   password: "${ELASTICSEARCH_PASSWORD}"
    #   index: "smart-dairy-filebeat-%{+yyyy.MM.dd}"
    #   ssl.enabled: true
    #   ssl.certificate_authorities: ["/etc/filebeat/certs/ca.crt"]
    #   bulk_max_size: 500
    #   worker: 2
    #   compression_level: 3

    # Option 2: Via Logstash (Recommended)
    output.logstash:
      hosts: ["${LOGSTASH_HOST}"]
      ssl.enabled: false  # Set to true and configure cert in production
      # ssl.certificate_authorities: ["/etc/filebeat/certs/ca.crt"]
      # ssl.certificate: "/etc/filebeat/certs/filebeat.crt"
      # ssl.key: "/etc/filebeat/certs/filebeat.key"
      
      # Performance tuning
      worker: 2
      bulk_max_size: 2048
      slow_start: false
      
      # Load balancing
      loadbalance: true
      ttl: 30s
      
      # Connection settings
      timeout: 30s
      max_retries: 3

    # ============================================
    # Queue Configuration
    # ============================================
    queue.mem:
      events: 4096
      flush.min_events: 512
      flush.timeout: 5s

    # ============================================
    # Logging Configuration
    # ============================================
    logging.level: info
    logging.to_files: true
    logging.files:
      path: /usr/share/filebeat/logs
      name: filebeat
      keepfiles: 7
      permissions: 0644
    logging.metrics.enabled: true
    logging.metrics.period: 30s

    # ============================================
    # Monitoring (X-Pack)
    # ============================================
    monitoring.enabled: true
    monitoring.elasticsearch:
      hosts: ["${ELASTICSEARCH_HOST}"]
      username: "${ELASTICSEARCH_USERNAME}"
      password: "${ELASTICSEARCH_PASSWORD}"

    # ============================================
    # HTTP Endpoint for Metrics
    # ============================================
    http.enabled: true
    http.host: localhost
    http.port: 5066
```

### 6.3 Filebeat Pod Annotations for Hints

```yaml
# Example application deployment with Filebeat hints
apiVersion: apps/v1
kind: Deployment
metadata:
  name: smart-dairy-odoo
  namespace: smart-dairy
spec:
  replicas: 3
  selector:
    matchLabels:
      app: odoo
  template:
    metadata:
      labels:
        app: odoo
      annotations:
        # Filebeat hints for this pod
        co.elastic.logs/enabled: "true"
        co.elastic.logs/json.keys_under_root: "true"
        co.elastic.logs/json.add_error_key: "true"
        co.elastic.logs/json.message_key: "message"
        co.elastic.logs/multiline.pattern: '^\d{4}-\d{2}-\d{2}'
        co.elastic.logs/multiline.negate: "true"
        co.elastic.logs/multiline.match: after
        co.elastic.metrics/enabled: "true"
    spec:
      containers:
        - name: odoo
          image: smart-dairy/odoo:19.0
          env:
            - name: ODOO_LOG_LEVEL
              value: "info"
            - name: ODOO_LOG_FORMAT
              value: "json"  # Enable structured logging
          volumeMounts:
            - name: odoo-logs
              mountPath: /var/log/odoo
      volumes:
        - name: odoo-logs
          emptyDir: {}
```

---

## 7. Log Management Policies

### 7.1 Log Rotation Configuration

```yaml
# log-rotation-cronjob.yaml
apiVersion: batch/v1
kind: CronJob
metadata:
  name: log-rotation
  namespace: logging
  labels:
    app: log-management
spec:
  schedule: "0 2 * * *"  # Daily at 2 AM
  concurrencyPolicy: Forbid
  successfulJobsHistoryLimit: 3
  failedJobsHistoryLimit: 3
  jobTemplate:
    spec:
      template:
        spec:
          restartPolicy: OnFailure
          containers:
            - name: curator
              image: elasticsearch/curator:8.0.8
              command:
                - curator
                - --config
                - /etc/curator/curator.yml
                - /etc/curator/actions.yml
              volumeMounts:
                - name: curator-config
                  mountPath: /etc/curator
              resources:
                limits:
                  cpu: "500m"
                  memory: "512Mi"
                requests:
                  cpu: "100m"
                  memory: "128Mi"
          volumes:
            - name: curator-config
              configMap:
                name: curator-config
---
apiVersion: v1
kind: ConfigMap
metadata:
  name: curator-config
  namespace: logging
data:
  curator.yml: |
    client:
      hosts:
        - elasticsearch.logging.svc.cluster.local
      port: 9200
      use_ssl: true
      certificate: /etc/curator/certs/ca.crt
      client_cert: /etc/curator/certs/curator.crt
      client_key: /etc/curator/certs/curator.key
      ssl_no_validate: false
      username: curator
      password: ${CURATOR_PASSWORD}
      timeout: 30
      master_only: false
    
    logging:
      loglevel: INFO
      logfile: /var/log/curator/curator.log
      logformat: default
      blacklist: ['elasticsearch', 'urllib3']
  
  actions.yml: |
    actions:
      1:
        action: rollover
        description: >-
          Rollover the index associated with alias 'smart-dairy-logs',
          which should be writeable for the next index.
        options:
          disable_action: false
          name: smart-dairy-logs
          conditions:
            max_age: 1d
            max_size: 50gb
            max_docs: 100000000
      
      2:
        action: delete_indices
        description: >-
          Delete indices older than 90 days (compliance requirement)
          based on index name pattern.
        options:
          disable_action: false
          continue_if_exception: true
        filters:
          - filtertype: pattern
            kind: regex
            value: '^smart-dairy-.*$'
          - filtertype: age
            source: name
            direction: older
            timestring: '%Y.%m.%d'
            unit: days
            unit_count: 90
      
      3:
        action: close
        description: >-
          Close indices older than 30 days to save memory.
          They remain searchable but consume fewer resources.
        options:
          disable_action: false
          continue_if_exception: true
          delete_aliases: false
        filters:
          - filtertype: pattern
            kind: regex
            value: '^smart-dairy-.*$'
          - filtertype: age
            source: name
            direction: older
            timestring: '%Y.%m.%d'
            unit: days
            unit_count: 30
      
      4:
        action: forcemerge
        description: >-
          Perform forceMerge on indices older than 7 days
          to optimize storage and search performance.
        options:
          disable_action: false
          continue_if_exception: true
          max_num_segments: 1
          delay: 120
        filters:
          - filtertype: pattern
            kind: regex
            value: '^smart-dairy-.*$'
          - filtertype: age
            source: name
            direction: older
            timestring: '%Y.%m.%d'
            unit: days
            unit_count: 7
      
      5:
        action: snapshot
        description: >-
          Create snapshots of indices older than 1 day
          for disaster recovery.
        options:
          disable_action: false
          continue_if_exception: true
          repository: smart-dairy-snapshots
          wait_for_completion: true
          max_wait: 3600
          wait_interval: 10
        filters:
          - filtertype: pattern
            kind: regex
            value: '^smart-dairy-.*$'
          - filtertype: age
            source: name
            direction: older
            timestring: '%Y.%m.%d'
            unit: days
            unit_count: 1
```

### 7.2 Snapshot Repository Configuration

```json
// elasticsearch-snapshot-repository.json
{
  "type": "s3",
  "settings": {
    "bucket": "smart-dairy-elasticsearch-snapshots",
    "region": "us-east-1",
    "base_path": "elasticsearch/snapshots",
    "compress": true,
    "chunk_size": "1gb",
    "max_snapshot_bytes_per_sec": "100mb",
    "max_restore_bytes_per_sec": "100mb"
  }
}
```

```bash
# Create snapshot repository
PUT /_snapshot/smart-dairy-snapshots
{
  "type": "s3",
  "settings": {
    "bucket": "smart-dairy-elasticsearch-snapshots",
    "region": "us-east-1",
    "base_path": "elasticsearch/snapshots",
    "compress": true,
    "server_side_encryption": true
  }
}

# Create snapshot policy
PUT /_slm/policy/daily-snapshots
{
  "name": "<smart-dairy-snap-{now/d}>",
  "schedule": "0 30 1 * * ?",
  "repository": "smart-dairy-snapshots",
  "config": {
    "indices": ["smart-dairy-*"],
    "ignore_unavailable": true,
    "include_global_state": false
  },
  "retention": {
    "expire_after": "90d",
    "min_count": 5,
    "max_count": 50
  }
}
```

### 7.3 Retention Policy Matrix

| Phase | Age | Action | Storage Tier | Searchable |
|-------|-----|--------|--------------|------------|
| Hot | 0-7 days | Active indexing, replicas | Hot nodes | Yes (Full speed) |
| Warm | 7-30 days | Force merge, shrink to 1 shard | Warm nodes | Yes (Reduced) |
| Cold | 30-90 days | Freeze, minimize resources | Cold/UltraWarm | Yes (Slower) |
| Archive | 90+ days | Delete from ES, keep S3 snapshot | S3 Glacier | Restore required |

---

## 8. Alerting on Logs

### 8.1 Watcher Configuration

```json
// elasticsearch-watcher-alerts.json
{
  "metadata": {
    "color": "red",
    "threshold": {
      "error_count": 50,
      "error_rate": 10
    }
  },
  "trigger": {
    "schedule": {
      "interval": "5m"
    }
  },
  "input": {
    "search": {
      "request": {
        "search_type": "query_then_fetch",
        "indices": ["smart-dairy-logs-*"],
        "body": {
          "size": 0,
          "query": {
            "bool": {
              "filter": [
                {
                  "range": {
                    "@timestamp": {
                      "gte": "now-5m",
                      "lte": "now"
                    }
                  }
                },
                {
                  "terms": {
                    "log.level": ["ERROR", "FATAL", "CRITICAL"]
                  }
                }
              ]
            }
          },
          "aggs": {
            "by_service": {
              "terms": {
                "field": "service.name",
                "size": 10
              },
              "aggs": {
                "error_count": {
                  "value_count": {
                    "field": "_index"
                  }
                }
              }
            }
          }
        }
      }
    }
  },
  "condition": {
    "compare": {
      "ctx.payload.hits.total": {
        "gt": 0
      }
    }
  },
  "actions": {
    "send_email": {
      "email": {
        "profile": "standard",
        "to": ["devops@smartdairy.com", "oncall@smartdairy.com"],
        "subject": "ELK Alert: High Error Rate Detected",
        "body": {
          "text": "Error count in the last 5 minutes: {{ctx.payload.hits.total}}"
        }
      }
    },
    "send_slack": {
      "webhook": {
        "url": "https://hooks.slack.com/services/YOUR/SLACK/WEBHOOK_URL",
        "body": "{\"text\": \":warning: *High Error Rate Alert* :warning:\\n\\n*Environment:* Production\\n*Time Range:* Last 5 minutes\\n*Total Errors:* {{ctx.payload.hits.total}}\\n\\nPlease check Kibana: https://kibana.smartdairy.com/app/discover\"}"
      }
    }
  }
}
```

### 8.2 Alert Rules YAML

```yaml
# elasticsearch-alert-rules.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: elasticsearch-alerts
  namespace: logging
data:
  alerts.json: |
    [
      {
        "id": "high-error-rate-alert",
        "name": "High Error Rate",
        "enabled": true,
        "schedule": { "interval": "5m" },
        "input": {
          "search": {
            "request": {
              "indices": ["smart-dairy-logs-*"],
              "body": {
                "size": 0,
                "query": {
                  "bool": {
                    "filter": [
                      { "range": { "@timestamp": { "gte": "now-5m" } } },
                      { "terms": { "log.level": ["ERROR", "FATAL", "CRITICAL"] } }
                    ]
                  }
                }
              }
            }
          }
        },
        "condition": {
          "compare": {
            "ctx.payload.hits.total": { "gt": 50 }
          }
        },
        "actions": {
          "slack": {
            "webhook": {
              "url": "${SLACK_WEBHOOK_URL}",
              "body": "High error rate detected: {{ctx.payload.hits.total}} errors in last 5 minutes"
            }
          },
          "pagerduty": {
            "webhook": {
              "url": "${PAGERDUTY_INTEGRATION_URL}",
              "body": "{\"routing_key\": \"${PAGERDUTY_KEY}\", \"event_action\": \"trigger\", \"payload\": {\"summary\": \"High Error Rate: {{ctx.payload.hits.total}} errors\", \"severity\": \"critical\"}}"
            }
          }
        }
      },
      {
        "id": "odoo-critical-error-alert",
        "name": "Odoo Critical Error",
        "enabled": true,
        "schedule": { "interval": "1m" },
        "input": {
          "search": {
            "request": {
              "indices": ["smart-dairy-odoo-*"],
              "body": {
                "size": 0,
                "query": {
                  "bool": {
                    "filter": [
                      { "range": { "@timestamp": { "gte": "now-1m" } } },
                      { "terms": { "log.level": ["ERROR", "CRITICAL"] } }
                    ]
                  }
                }
              }
            }
          }
        },
        "condition": {
          "compare": {
            "ctx.payload.hits.total": { "gt": 5 }
          }
        },
        "actions": {
          "slack": {
            "webhook": {
              "url": "${SLACK_WEBHOOK_URL}",
              "body": "Odoo critical errors detected: {{ctx.payload.hits.total}} in last minute"
            }
          }
        }
      },
      {
        "id": "database-connection-error-alert",
        "name": "Database Connection Error",
        "enabled": true,
        "schedule": { "interval": "2m" },
        "input": {
          "search": {
            "request": {
              "indices": ["smart-dairy-postgresql-*"],
              "body": {
                "size": 0,
                "query": {
                  "bool": {
                    "filter": [
                      { "range": { "@timestamp": { "gte": "now-2m" } } },
                      { "match": { "log.message": "connection" } },
                      { "terms": { "log.level": ["ERROR", "FATAL"] } }
                    ]
                  }
                }
              }
            }
          }
        },
        "condition": {
          "compare": {
            "ctx.payload.hits.total": { "gt": 0 }
          }
        },
        "actions": {
          "slack": {
            "webhook": {
              "url": "${SLACK_WEBHOOK_URL}",
              "body": "Database connection errors detected"
            }
          },
          "pagerduty": {
            "webhook": {
              "url": "${PAGERDUTY_INTEGRATION_URL}",
              "body": "{\"routing_key\": \"${PAGERDUTY_KEY}\", \"event_action\": \"trigger\", \"payload\": {\"summary\": \"Database Connection Error\", \"severity\": \"critical\"}}"
            }
          }
        }
      },
      {
        "id": "slow-query-alert",
        "name": "Slow Database Query",
        "enabled": true,
        "schedule": { "interval": "10m" },
        "input": {
          "search": {
            "request": {
              "indices": ["smart-dairy-postgresql-*"],
              "body": {
                "size": 0,
                "query": {
                  "bool": {
                    "filter": [
                      { "range": { "@timestamp": { "gte": "now-10m" } } },
                      { "range": { "pg.duration_ms": { "gt": 5000 } } }
                    ]
                  }
                }
              }
            }
          }
        },
        "condition": {
          "compare": {
            "ctx.payload.hits.total": { "gt": 10 }
          }
        },
        "actions": {
          "slack": {
            "webhook": {
              "url": "${SLACK_WEBHOOK_URL}",
              "body": "Slow queries detected: {{ctx.payload.hits.total}} queries > 5s in last 10 minutes"
            }
          }
        }
      },
      {
        "id": "api-high-latency-alert",
        "name": "API High Latency",
        "enabled": true,
        "schedule": { "interval": "5m" },
        "input": {
          "search": {
            "request": {
              "indices": ["smart-dairy-fastapi-*"],
              "body": {
                "size": 0,
                "query": {
                  "bool": {
                    "filter": [
                      { "range": { "@timestamp": { "gte": "now-5m" } } },
                      { "range": { "http.response.duration_ms": { "gt": 2000 } } }
                    ]
                  }
                },
                "aggs": {
                  "avg_latency": {
                    "avg": {
                      "field": "http.response.duration_ms"
                    }
                  },
                  "p95_latency": {
                    "percentiles": {
                      "field": "http.response.duration_ms",
                      "percents": [95]
                    }
                  }
                }
              }
            }
          }
        },
        "condition": {
          "compare": {
            "ctx.payload.aggregations.p95_latency.values.95.0": { "gt": 3000 }
          }
        },
        "actions": {
          "slack": {
            "webhook": {
              "url": "${SLACK_WEBHOOK_URL}",
              "body": "API high latency detected. P95: {{ctx.payload.aggregations.p95_latency.values.95.0}}ms"
            }
          }
        }
      },
      {
        "id": "security-login-failure-alert",
        "name": "Multiple Login Failures",
        "enabled": true,
        "schedule": { "interval": "5m" },
        "input": {
          "search": {
            "request": {
              "indices": ["smart-dairy-logs-*"],
              "body": {
                "size": 0,
                "query": {
                  "bool": {
                    "filter": [
                      { "range": { "@timestamp": { "gte": "now-5m" } } },
                      { "match": { "log.message": "login" } },
                      { "match": { "log.message": "fail" } }
                    ]
                  }
                },
                "aggs": {
                  "by_user": {
                    "terms": {
                      "field": "user.email",
                      "size": 10
                    }
                  }
                }
              }
            }
          }
        },
        "condition": {
          "compare": {
            "ctx.payload.hits.total": { "gt": 10 }
          }
        },
        "actions": {
          "slack": {
            "webhook": {
              "url": "${SLACK_WEBHOOK_URL}",
              "body": "Multiple login failures detected: {{ctx.payload.hits.total}} in last 5 minutes"
            }
          }
        }
      }
    ]
```

### 8.3 Alert Severity Matrix

| Alert Type | Severity | Threshold | Response Time | Notification Channels |
|------------|----------|-----------|---------------|----------------------|
| High Error Rate | Critical | >50 errors/5min | 5 min | Slack + PagerDuty |
| Odoo Critical Error | Critical | >5 errors/min | 1 min | Slack |
| Database Connection Error | Critical | >0 errors | 2 min | Slack + PagerDuty |
| Slow Query | Warning | >10 queries >5s/10min | 10 min | Slack |
| API High Latency | Warning | P95 >3000ms | 5 min | Slack |
| Login Failures | Warning | >10 failures/5min | 5 min | Slack |
| Disk Space Warning | Warning | >80% used | 15 min | Slack |

---

## 9. Security

### 9.1 TLS Configuration

```yaml
# elasticsearch-tls-secret.yaml
apiVersion: v1
kind: Secret
metadata:
  name: elasticsearch-certs
  namespace: logging
type: kubernetes.io/tls
data:
  # Generate with: openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout tls.key -out tls.crt -subj "/CN=elasticsearch.logging.svc.cluster.local"
  tls.crt: LS0tLS1CRUdJTi...base64-encoded-cert...
  tls.key: LS0tLS1CRUdJTi...base64-encoded-key...
  ca.crt: LS0tLS1CRUdJTi...base64-encoded-ca...
```

```yaml
# elasticsearch-security-config.yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: elasticsearch-security
  namespace: logging
data:
  elasticsearch.yml: |
    # Security Settings
    xpack.security.enabled: true
    xpack.security.enrollment.enabled: true
    xpack.security.http.ssl:
      enabled: true
      certificate: /usr/share/elasticsearch/config/certs/tls.crt
      key: /usr/share/elasticsearch/config/certs/tls.key
      certificate_authorities: ["/usr/share/elasticsearch/config/certs/ca.crt"]
    xpack.security.transport.ssl:
      enabled: true
      certificate: /usr/share/elasticsearch/config/certs/tls.crt
      key: /usr/share/elasticsearch/config/certs/tls.key
      certificate_authorities: ["/usr/share/elasticsearch/config/certs/ca.crt"]
      client_authentication: required
    
    # Audit Logging
    xpack.security.audit.enabled: true
    xpack.security.audit.logfile.events.include:
      - authentication_success
      - authentication_failed
      - realm_authentication_failed
      - access_denied
      - connection_granted
      - connection_denied
    
    # Transport Settings
    transport.host: 0.0.0.0
    transport.port: 9300
    
    # HTTP Settings
    http.host: 0.0.0.0
    http.port: 9200
    
    # Cluster Settings
    cluster.name: smart-dairy-elasticsearch
    discovery.seed_hosts: ["es-master-0", "es-master-1", "es-master-2"]
    cluster.initial_master_nodes: ["es-master-0", "es-master-1", "es-master-2"]
```

### 9.2 Role-Based Access Control (RBAC)

```json
// elasticsearch-roles.json
{
  "smart_dairy_admin": {
    "cluster": ["all"],
    "indices": [
      {
        "names": ["smart-dairy-*"],
        "privileges": ["all"]
      }
    ],
    "kibana": [
      {
        "feature": {
          "discover": ["all"],
          "visualize": ["all"],
          "dashboard": ["all"],
          "dev_tools": ["all"],
          "advancedSettings": ["all"]
        },
        "spaces": ["*"]
      }
    ]
  },
  "smart_dairy_developer": {
    "cluster": ["monitor", "manage_index_templates"],
    "indices": [
      {
        "names": ["smart-dairy-*"],
        "privileges": ["read", "view_index_metadata"]
      }
    ],
    "kibana": [
      {
        "feature": {
          "discover": ["read"],
          "visualize": ["read"],
          "dashboard": ["read"],
          "dev_tools": ["read"]
        },
        "spaces": ["smart-dairy"]
      }
    ]
  },
  "smart_dairy_support": {
    "cluster": [],
    "indices": [
      {
        "names": ["smart-dairy-logs-*"],
        "privileges": ["read"]
      }
    ],
    "kibana": [
      {
        "feature": {
          "discover": ["read"],
          "dashboard": ["read"]
        },
        "spaces": ["smart-dairy"]
      }
    ]
  },
  "smart_dairy_logstash": {
    "cluster": ["manage_index_templates", "monitor", "manage_ilm"],
    "indices": [
      {
        "names": ["smart-dairy-*"],
        "privileges": ["create_index", "auto_create", "read", "index", "manage", "manage_ilm"]
      },
      {
        "names": [".monitoring-*"],
        "privileges": ["read", "index"]
      }
    ]
  },
  "smart_dairy_kibana": {
    "cluster": ["monitor", "manage"],
    "indices": [
      {
        "names": [".kibana*", ".reporting-*", ".monitoring-*", ".apm-*"],
        "privileges": ["all"]
      }
    ]
  },
  "smart_dairy_filebeat": {
    "cluster": ["monitor"],
    "indices": [
      {
        "names": ["filebeat-*"],
        "privileges": ["create_index", "index", "create"]
      }
    ]
  }
}
```

```bash
# Create roles via API
curl -X POST "localhost:9200/_security/role/smart_dairy_developer" \
  -H "Content-Type: application/json" \
  -u elastic:password \
  -d '{
    "cluster": ["monitor"],
    "indices": [
      {
        "names": ["smart-dairy-*"],
        "privileges": ["read", "view_index_metadata"]
      }
    ],
    "kibana": [
      {
        "feature": {
          "discover": ["read"],
          "visualize": ["read"],
          "dashboard": ["read"]
        },
        "spaces": ["*"]
      }
    ]
  }'

# Create users
curl -X POST "localhost:9200/_security/user/devops_engineer" \
  -H "Content-Type: application/json" \
  -u elastic:password \
  -d '{
    "password": "secure_password",
    "roles": ["smart_dairy_admin"],
    "full_name": "DevOps Engineer",
    "email": "devops@smartdairy.com"
  }'

curl -X POST "localhost:9200/_security/user/logstash_writer" \
  -H "Content-Type: application/json" \
  -u elastic:password \
  -d '{
    "password": "logstash_password",
    "roles": ["smart_dairy_logstash"],
    "full_name": "Logstash Writer"
  }'
```

### 9.3 Network Policies

```yaml
# elasticsearch-network-policy.yaml
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: elasticsearch-network-policy
  namespace: logging
spec:
  podSelector:
    matchLabels:
      app: elasticsearch
  policyTypes:
    - Ingress
    - Egress
  ingress:
    # Allow Logstash to connect
    - from:
        - podSelector:
            matchLabels:
              app: logstash
      ports:
        - protocol: TCP
          port: 9200
    # Allow Kibana to connect
    - from:
        - podSelector:
            matchLabels:
              app: kibana
      ports:
        - protocol: TCP
          port: 9200
    # Allow Filebeat (via Logstash, direct access not needed)
    # Allow monitoring
    - from:
        - namespaceSelector:
            matchLabels:
              name: monitoring
      ports:
        - protocol: TCP
          port: 9200
  egress:
    # Allow Elasticsearch nodes to communicate
    - to:
        - podSelector:
            matchLabels:
              app: elasticsearch
      ports:
        - protocol: TCP
          port: 9300
---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: logstash-network-policy
  namespace: logging
spec:
  podSelector:
    matchLabels:
      app: logstash
  policyTypes:
    - Ingress
    - Egress
  ingress:
    # Allow Filebeat to send logs
    - from:
        - podSelector:
            matchLabels:
              app: filebeat
      ports:
        - protocol: TCP
          port: 5044
  egress:
    # Allow Logstash to send to Elasticsearch
    - to:
        - podSelector:
            matchLabels:
              app: elasticsearch
      ports:
        - protocol: TCP
          port: 9200
---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata:
  name: kibana-network-policy
  namespace: logging
spec:
  podSelector:
    matchLabels:
      app: kibana
  policyTypes:
    - Ingress
    - Egress
  ingress:
    # Allow ingress from nginx/traefik
    - from:
        - namespaceSelector: {}
          podSelector:
            matchLabels:
              app.kubernetes.io/name: ingress-nginx
      ports:
        - protocol: TCP
          port: 5601
  egress:
    # Allow Kibana to connect to Elasticsearch
    - to:
        - podSelector:
            matchLabels:
              app: elasticsearch
      ports:
        - protocol: TCP
          port: 9200
```

### 9.4 Secret Management

```yaml
# elasticsearch-secrets.yaml
apiVersion: v1
kind: Secret
metadata:
  name: elasticsearch-credentials
  namespace: logging
type: Opaque
stringData:
  host: "https://elasticsearch.logging.svc.cluster.local:9200"
  username: "elastic"
  password: "changeme"  # Use external secrets management in production
---
apiVersion: external-secrets.io/v1beta1
kind: ExternalSecret
metadata:
  name: elasticsearch-credentials
  namespace: logging
spec:
  refreshInterval: 1h
  secretStoreRef:
    kind: ClusterSecretStore
    name: aws-secrets-manager
  target:
    name: elasticsearch-credentials
    creationPolicy: Owner
  data:
    - secretKey: host
      remoteRef:
        key: smart-dairy/elasticsearch
        property: host
    - secretKey: username
      remoteRef:
        key: smart-dairy/elasticsearch
        property: username
    - secretKey: password
      remoteRef:
        key: smart-dairy/elasticsearch
        property: password
```

---

## 10. Appendices

### Appendix A: Grok Pattern Reference

```
# ============================================
# Smart Dairy Grok Patterns Quick Reference
# ============================================

# Standard Syslog Pattern
SYSLOGLINE %{SYSLOGBASE2} %{GREEDYDATA:message}
SYSLOGBASE2 %{SYSLOGTIMESTAMP:timestamp} (?:%{SYSLOGFACILITY} )?%{SYSLOGHOST:logsource} %{SYSLOGPROG}:

# Odoo Log Pattern
ODOO_LOG ^%{ODOO_TIMESTAMP:timestamp} %{NUMBER:process_id} %{LOGLEVEL:level} %{DATA:logger} %{GREEDYDATA:message}
ODOO_TIMESTAMP %{YEAR}-%{MONTHNUM}-%{MONTHDAY} %{HOUR}:%{MINUTE}:%{SECOND},%{INT:millis}

# Odoo SQL Pattern
ODOO_SQL ^%{ODOO_TIMESTAMP:timestamp} %{NUMBER:process_id} %{LOGLEVEL:level} %{DATA:logger}: %{DATA:operation} %{GREEDYDATA:query} in %{NUMBER:duration_ms:float} ms

# FastAPI Access Log
FASTAPI_ACCESS %{IPORHOST:client_ip} %{HTTPDUSER:ident} %{USER:auth} \[%{HTTPDATE:timestamp}\] "(?:%{WORD:verb} %{NOTSPACE:request}(?: HTTP/%{NUMBER:httpversion})?|%{DATA:rawrequest})" %{NUMBER:response} (?:%{NUMBER:bytes}|-)

# FastAPI Structured Log (JSON)
FASTAPI_JSON \{"timestamp": "%{TIMESTAMP_ISO8601:timestamp}", "level": "%{LOGLEVEL:level}", "logger": "%{DATA:logger}", "message": "%{DATA:message}"(,(?<json_extra>.*))?\}

# PostgreSQL Log Pattern
POSTGRES_LOG %{PG_TIMESTAMP:timestamp} %{TZ:timezone}? \[%{NUMBER:process_id}\] %{DATA:user}@%{DATA:database} %{LOGLEVEL:level}:  %{GREEDYDATA:message}
PG_TIMESTAMP %{YEAR}-%{MONTHNUM}-%{MONTHDAY} %{HOUR}:%{MINUTE}:%{SECOND}\.%{INT:micros}

# PostgreSQL Slow Query
PG_SLOW_QUERY duration: %{NUMBER:duration_ms:float} ms  statement: %{GREEDYDATA:query}

# PostgreSQL Connection
PG_CONNECTION connection authorized: user=%{DATA:user} database=%{DATA:database}
PG_DISCONNECTION disconnection: session time: %{DATA:session_time} user=%{DATA:user} database=%{DATA:database}

# Kubernetes Container Log
K8S_CONTAINER_LOG %{TIMESTAMP_ISO8601:timestamp} %{LOGLEVEL:level} %{GREEDYDATA:message}

# HTTP Request/Response
HTTP_REQUEST %{WORD:method} %{URIPATHPARAM:path} HTTP/%{NUMBER:version}
HTTP_RESPONSE_STATUS %{NUMBER:status_code:int}
HTTP_RESPONSE_TIME %{NUMBER:duration_ms:float}

# Error Stack Trace (Python)
PYTHON_STACK_TRACE ^%{SPACE}File "%{PATH:file}", line %{NUMBER:line:int}, in %{WORD:function}
PYTHON_EXCEPTION ^%{WORD:exception_type}(?:\[%{DATA:exception_code}\])?: %{GREEDYDATA:exception_message}

# SQL Query Patterns
SQL_SELECT (?i)select\s+.*\s+from\s+\S+
SQL_INSERT (?i)insert\s+into\s+\S+
SQL_UPDATE (?i)update\s+\S+\s+set\s+.*
SQL_DELETE (?i)delete\s+from\s+\S+

# Email Pattern
EMAIL %{USER:local}@%{HOSTNAME:domain}

# UUID Pattern (for trace IDs)
UUID [a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}

# Session ID Pattern
SESSION_ID [a-f0-9]{32}

# IP with Port
IP_PORT %{IP}:%{POSINT:port}

# User Agent (simplified)
USER_AGENT %{DATA}

# Referrer URL
REFERERR %{URI}
```

### Appendix B: Index Templates

```json
// index-template-system.json
{
  "index_patterns": [".monitoring-*"],
  "settings": {
    "number_of_shards": 1,
    "number_of_replicas": 1
  },
  "mappings": {
    "dynamic_templates": [
      {
        "strings_as_keywords": {
          "match_mapping_type": "string",
          "mapping": {
            "type": "keyword",
            "ignore_above": 256
          }
        }
      }
    ]
  }
}
```

```json
// index-template-alerts.json
{
  "index_patterns": ["smart-dairy-alerts-*"],
  "settings": {
    "number_of_shards": 1,
    "number_of_replicas": 1,
    "index.refresh_interval": "1s",
    "index.lifecycle.name": "smart-dairy-alerts-policy",
    "index.lifecycle.rollover_alias": "smart-dairy-alerts"
  },
  "mappings": {
    "properties": {
      "@timestamp": { "type": "date" },
      "alert": {
        "properties": {
          "id": { "type": "keyword" },
          "name": { "type": "keyword" },
          "severity": { "type": "keyword" },
          "status": { "type": "keyword" },
          "message": { "type": "text" },
          "condition": { "type": "object", "enabled": false }
        }
      },
      "trigger": {
        "properties": {
          "schedule": { "type": "keyword" },
          "fired_at": { "type": "date" }
        }
      }
    }
  }
}
```

### Appendix C: Common Queries

```bash
# Search for all errors in the last hour
GET smart-dairy-logs-*/_search
{
  "query": {
    "bool": {
      "filter": [
        { "range": { "@timestamp": { "gte": "now-1h" } } },
        { "terms": { "log.level": ["ERROR", "FATAL", "CRITICAL"] } }
      ]
    }
  }
}

# Count logs by service
GET smart-dairy-logs-*/_search
{
  "size": 0,
  "aggs": {
    "by_service": {
      "terms": { "field": "service.name", "size": 10 }
    }
  }
}

# Find slow API requests
GET smart-dairy-fastapi-*/_search
{
  "query": {
    "bool": {
      "filter": [
        { "range": { "@timestamp": { "gte": "now-1h" } } },
        { "range": { "http.response.duration_ms": { "gt": 2000 } } }
      ]
    }
  },
  "sort": [{ "http.response.duration_ms": { "order": "desc" } }]
}

# Search for specific user activity
GET smart-dairy-logs-*/_search
{
  "query": {
    "bool": {
      "must": [
        { "match": { "user.email": "user@smartdairy.com" } }
      ],
      "filter": [
        { "range": { "@timestamp": { "gte": "now-24h" } } }
      ]
    }
  }
}

# PostgreSQL slow queries
GET smart-dairy-postgresql-*/_search
{
  "size": 0,
  "query": {
    "bool": {
      "filter": [
        { "range": { "@timestamp": { "gte": "now-1h" } } },
        { "range": { "pg.duration_ms": { "gt": 1000 } } }
      ]
    }
  },
  "aggs": {
    "avg_duration": { "avg": { "field": "pg.duration_ms" } },
    "max_duration": { "max": { "field": "pg.duration_ms" } }
  }
}
```

### Appendix D: Troubleshooting Guide

| Issue | Possible Causes | Solution |
|-------|-----------------|----------|
| No logs in Elasticsearch | Filebeat not running, Logstash down | Check DaemonSet status, verify connectivity |
| Grok parse failures | Incorrect patterns, log format changed | Update patterns in logstash-patterns ConfigMap |
| High memory usage | Too many indices, large batches | Adjust ILM policies, reduce batch sizes |
| Slow queries | Unoptimized indices, too many shards | Force merge old indices, reduce shard count |
| Logstash backlog | Insufficient resources, slow output | Scale Logstash replicas, tune batch settings |
| Authentication errors | Wrong credentials, expired certs | Check secrets, rotate certificates |
| Missing Kubernetes metadata | RBAC issues, wrong host setting | Verify ServiceAccount permissions |

### Appendix E: Monitoring Metrics

```yaml
# prometheus-service-monitor.yaml
apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: elasticsearch-metrics
  namespace: logging
  labels:
    app: elasticsearch
spec:
  selector:
    matchLabels:
      app: elasticsearch
  endpoints:
    - port: metrics
      interval: 30s
      path: /_prometheus/metrics
  namespaceSelector:
    matchNames:
      - logging
---
apiVersion: monitoring.coreos.com/v1
kind: PrometheusRule
metadata:
  name: elasticsearch-alerts
  namespace: logging
spec:
  groups:
    - name: elasticsearch
      rules:
        - alert: ElasticsearchHeapTooHigh
          expr: |
            elasticsearch_jvm_memory_used_bytes{area="heap"} / 
            elasticsearch_jvm_memory_max_bytes{area="heap"} > 0.8
          for: 5m
          labels:
            severity: warning
          annotations:
            summary: "Elasticsearch heap usage is high"
            
        - alert: ElasticsearchClusterRed
          expr: elasticsearch_cluster_health_status{color="red"} == 1
          for: 1m
          labels:
            severity: critical
          annotations:
            summary: "Elasticsearch cluster health is RED"
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | DevOps Engineer | _________________ | _________________ |
| Owner | DevOps Lead | _________________ | _________________ |
| Reviewer | CTO | _________________ | _________________ |

---

*Document ID: D-009*  
*Version: 1.0*  
*Classification: Internal Use*  
*© 2026 Smart Dairy Ltd. All rights reserved.*
