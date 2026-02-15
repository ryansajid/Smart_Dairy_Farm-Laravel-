# Milestone 69: Customer Support Chat

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M69-v1.0 |
| **Milestone** | 69 - Customer Support Chat |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 431-440 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement real-time customer support with Odoo Livechat integration, FAQ-based chatbot for common queries, ticket creation from chat, agent dashboard with queue management, and chat transcripts for quality assurance.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Live Chat | Real-time customer-agent messaging |
| Chatbot | FAQ-based automated responses |
| FAQ Portal | Self-service knowledge base |
| Ticket System | Issue tracking from chat |
| Agent Dashboard | Queue and performance management |
| Chat History | Transcripts and search |
| Canned Responses | Quick reply templates |
| Chat Rating | Post-chat feedback |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Live chat availability | 99% uptime |
| O2 | Chatbot FAQ resolution | > 40% self-service |
| O3 | Agent response time | First response < 2 min |
| O4 | Chat satisfaction | Rating > 4.2/5 |
| O5 | Ticket conversion | Accurate ticket creation |
| O6 | Chat transcript search | < 1s search results |
| O7 | Mobile chat support | Full functionality |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Due Day |
|---|-------------|-------|---------|
| D1 | Livechat configuration | Dev 1 | Day 433 |
| D2 | Chatbot engine | Dev 1 | Day 435 |
| D3 | FAQ model and API | Dev 2 | Day 434 |
| D4 | Ticket integration | Dev 2 | Day 436 |
| D5 | Chat widget UI | Dev 3 | Day 434 |
| D6 | Agent dashboard | Dev 3 | Day 437 |
| D7 | Canned responses | Dev 2 | Day 438 |
| D8 | Chat rating system | Dev 3 | Day 439 |
| D9 | Chat analytics | Dev 1 | Day 439 |
| D10 | Integration tests | All | Day 440 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.7.1-001 | Live chat support | D1, D5 |
| RFP-B2C-4.7.1-002 | Chatbot for FAQs | D2, D3 |
| RFP-B2C-4.7.2-001 | Ticket creation | D4 |
| RFP-B2C-4.7.2-002 | Agent dashboard | D6 |
| RFP-B2C-4.7.3-001 | Chat transcripts | D1 |
| RFP-B2C-4.7.3-002 | Chat rating | D8 |

---

## 5. Day-by-Day Development Plan

### Day 431-434: Livechat & FAQ Setup

#### Dev 1 (Backend Lead)

**Livechat Extension**

```python
# File: smart_dairy_ecommerce/models/livechat_extension.py

from odoo import models, fields, api

class LivechatChannel(models.Model):
    _inherit = 'im_livechat.channel'

    # Extended configuration
    welcome_message = fields.Text(
        string='Welcome Message',
        default='Hi! How can we help you today?'
    )
    offline_message = fields.Text(
        string='Offline Message',
        default='We are currently offline. Please leave a message.'
    )

    # Business hours
    use_business_hours = fields.Boolean(
        string='Use Business Hours',
        default=True
    )
    business_hour_ids = fields.One2many(
        'livechat.business.hours',
        'channel_id',
        string='Business Hours'
    )

    # Chatbot
    enable_chatbot = fields.Boolean(
        string='Enable Chatbot',
        default=True
    )
    chatbot_greeting = fields.Text(
        default='Hello! I am Smart Dairy Assistant. How can I help?'
    )
    chatbot_fallback = fields.Text(
        default='I could not understand that. Would you like to speak with an agent?'
    )

    def is_available(self):
        """Check if chat is available based on business hours"""
        if not self.use_business_hours:
            return super().is_available()

        from datetime import datetime
        now = datetime.now()
        day = now.strftime('%A').lower()

        hours = self.business_hour_ids.filtered(
            lambda h: h.day == day and h.is_open
        )

        if not hours:
            return False

        current_time = now.hour + now.minute / 60
        for hour in hours:
            if hour.open_time <= current_time <= hour.close_time:
                return True

        return False


class LivechatBusinessHours(models.Model):
    _name = 'livechat.business.hours'
    _description = 'Livechat Business Hours'

    channel_id = fields.Many2one(
        'im_livechat.channel',
        required=True,
        ondelete='cascade'
    )
    day = fields.Selection([
        ('monday', 'Monday'),
        ('tuesday', 'Tuesday'),
        ('wednesday', 'Wednesday'),
        ('thursday', 'Thursday'),
        ('friday', 'Friday'),
        ('saturday', 'Saturday'),
        ('sunday', 'Sunday'),
    ], string='Day', required=True)
    is_open = fields.Boolean(string='Open', default=True)
    open_time = fields.Float(string='Open Time', default=9.0)
    close_time = fields.Float(string='Close Time', default=18.0)
```

**Chatbot Engine**

```python
# File: smart_dairy_ecommerce/models/chatbot.py

from odoo import models, fields, api
import re

class ChatbotEngine(models.Model):
    _name = 'chatbot.engine'
    _description = 'Chatbot Engine'

    name = fields.Char(string='Bot Name', default='Smart Dairy Assistant')
    active = fields.Boolean(default=True)

    # Intent patterns
    intent_ids = fields.One2many(
        'chatbot.intent',
        'engine_id',
        string='Intents'
    )

    @api.model
    def process_message(self, message, context=None):
        """Process incoming message and return response"""
        message_lower = message.lower().strip()

        # Check for order tracking intent
        order_match = re.search(r'order\s*#?\s*(\w+)', message_lower)
        if order_match or 'track' in message_lower:
            return self._handle_order_tracking(message, context)

        # Check FAQ intents
        for intent in self.intent_ids.filtered('active'):
            for pattern in intent.patterns.split('\n'):
                if pattern.strip() and re.search(
                    pattern.strip(), message_lower, re.I
                ):
                    return {
                        'type': 'text',
                        'content': intent.response,
                        'intent': intent.name,
                        'confidence': 0.9,
                    }

        # Fallback
        return {
            'type': 'fallback',
            'content': 'I could not understand that. Would you like to speak with a human agent?',
            'actions': [
                {'label': 'Connect to Agent', 'action': 'connect_agent'},
                {'label': 'Browse FAQ', 'action': 'show_faq'},
            ]
        }

    def _handle_order_tracking(self, message, context):
        """Handle order tracking queries"""
        order_match = re.search(r'(SD-\d+|SO\d+)', message, re.I)

        if order_match:
            order_ref = order_match.group(1)
            order = self.env['sale.order'].search([
                ('name', 'ilike', order_ref)
            ], limit=1)

            if order:
                return {
                    'type': 'order_status',
                    'content': f'Your order {order.name} is {order.current_status}',
                    'order': {
                        'name': order.name,
                        'status': order.current_status,
                        'estimated_delivery': order.estimated_delivery.isoformat() if order.estimated_delivery else None,
                    }
                }

        return {
            'type': 'text',
            'content': 'Please provide your order number (e.g., SD-12345) to track your order.',
        }


class ChatbotIntent(models.Model):
    _name = 'chatbot.intent'
    _description = 'Chatbot Intent'

    engine_id = fields.Many2one(
        'chatbot.engine',
        required=True,
        ondelete='cascade'
    )
    name = fields.Char(string='Intent Name', required=True)
    patterns = fields.Text(
        string='Patterns',
        help='One regex pattern per line'
    )
    response = fields.Text(string='Response', required=True)
    active = fields.Boolean(default=True)
    category = fields.Selection([
        ('shipping', 'Shipping'),
        ('returns', 'Returns'),
        ('payment', 'Payment'),
        ('product', 'Product'),
        ('general', 'General'),
    ], string='Category', default='general')
```

#### Dev 2 (Full-Stack)

**FAQ Model**

```python
# File: smart_dairy_ecommerce/models/faq.py

from odoo import models, fields, api

class FAQ(models.Model):
    _name = 'support.faq'
    _description = 'FAQ'
    _order = 'sequence, id'

    name = fields.Char(string='Question', required=True, translate=True)
    answer = fields.Html(string='Answer', required=True, translate=True)
    category_id = fields.Many2one(
        'support.faq.category',
        string='Category',
        required=True
    )
    sequence = fields.Integer(default=10)
    active = fields.Boolean(default=True)

    # Analytics
    view_count = fields.Integer(default=0)
    helpful_count = fields.Integer(default=0)
    not_helpful_count = fields.Integer(default=0)

    # Related
    related_faq_ids = fields.Many2many(
        'support.faq',
        'faq_related_rel',
        'faq_id',
        'related_id',
        string='Related FAQs'
    )

    def record_view(self):
        self.sudo().view_count += 1

    def record_feedback(self, helpful):
        if helpful:
            self.sudo().helpful_count += 1
        else:
            self.sudo().not_helpful_count += 1


class FAQCategory(models.Model):
    _name = 'support.faq.category'
    _description = 'FAQ Category'
    _order = 'sequence, name'

    name = fields.Char(string='Category Name', required=True, translate=True)
    icon = fields.Char(string='Icon Class', default='fa-question-circle')
    sequence = fields.Integer(default=10)
    faq_ids = fields.One2many('support.faq', 'category_id', string='FAQs')
    faq_count = fields.Integer(compute='_compute_faq_count')

    @api.depends('faq_ids')
    def _compute_faq_count(self):
        for category in self:
            category.faq_count = len(category.faq_ids.filtered('active'))
```

#### Dev 3 (Frontend Lead)

**Chat Widget**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/chat/chat_widget.js

import { Component, useState, onMounted, onWillUnmount } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class ChatWidget extends Component {
    static template = "smart_dairy_ecommerce.ChatWidget";

    setup() {
        this.rpc = useService("rpc");
        this.bus = useService("bus_service");

        this.state = useState({
            isOpen: false,
            isConnected: false,
            messages: [],
            inputText: '',
            isTyping: false,
            agentInfo: null,
            isBotMode: true,
        });

        onMounted(() => {
            this.bus.subscribe('chat.message', this.onNewMessage.bind(this));
        });

        onWillUnmount(() => {
            this.bus.unsubscribe('chat.message', this.onNewMessage.bind(this));
        });
    }

    toggleChat() {
        this.state.isOpen = !this.state.isOpen;
        if (this.state.isOpen && this.state.messages.length === 0) {
            this.showWelcomeMessage();
        }
    }

    showWelcomeMessage() {
        this.state.messages.push({
            type: 'bot',
            content: 'Hello! I am Smart Dairy Assistant. How can I help you today?',
            timestamp: new Date(),
            actions: [
                { label: 'Track Order', action: 'track_order' },
                { label: 'FAQ', action: 'show_faq' },
                { label: 'Talk to Agent', action: 'connect_agent' },
            ]
        });
    }

    async sendMessage() {
        if (!this.state.inputText.trim()) return;

        const userMessage = this.state.inputText.trim();
        this.state.messages.push({
            type: 'user',
            content: userMessage,
            timestamp: new Date(),
        });
        this.state.inputText = '';

        if (this.state.isBotMode) {
            await this.processBotMessage(userMessage);
        } else {
            await this.sendToAgent(userMessage);
        }
    }

    async processBotMessage(message) {
        this.state.isTyping = true;

        try {
            const result = await this.rpc('/api/v1/chat/bot', { message });

            this.state.messages.push({
                type: 'bot',
                content: result.content,
                actions: result.actions,
                timestamp: new Date(),
            });

            if (result.type === 'fallback') {
                // Offer to connect to agent
            }
        } catch (error) {
            console.error('Bot error:', error);
        } finally {
            this.state.isTyping = false;
        }
    }

    async connectToAgent() {
        this.state.isBotMode = false;
        this.state.messages.push({
            type: 'system',
            content: 'Connecting you to an agent...',
            timestamp: new Date(),
        });

        const result = await this.rpc('/api/v1/chat/connect', {});

        if (result.success) {
            this.state.isConnected = true;
            this.state.agentInfo = result.agent;
        }
    }

    onNewMessage(message) {
        this.state.messages.push({
            type: 'agent',
            content: message.content,
            agent: message.agent_name,
            timestamp: new Date(message.timestamp),
        });
    }

    handleAction(action) {
        switch (action) {
            case 'connect_agent':
                this.connectToAgent();
                break;
            case 'show_faq':
                window.location.href = '/support/faq';
                break;
            case 'track_order':
                this.state.inputText = 'Track my order';
                this.sendMessage();
                break;
        }
    }
}
```

---

### Day 435-440: Agent Dashboard, Tickets & Testing

The remaining days cover:
- Agent dashboard with queue management
- Canned responses management
- Ticket creation from chat
- Chat rating and feedback
- Chat analytics
- Integration testing

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/chat/init` | POST | Initialize chat session |
| `/api/v1/chat/bot` | POST | Send message to chatbot |
| `/api/v1/chat/connect` | POST | Connect to agent |
| `/api/v1/chat/send` | POST | Send message |
| `/api/v1/chat/rate` | POST | Rate chat session |
| `/api/v1/faq` | GET | List FAQ categories |
| `/api/v1/faq/<id>` | GET | Get FAQ details |

### 6.2 WebSocket Events

| Event | Direction | Description |
|-------|-----------|-------------|
| `chat.message` | Server→Client | New message |
| `chat.typing` | Bidirectional | Typing indicator |
| `chat.agent_joined` | Server→Client | Agent connected |
| `chat.ended` | Server→Client | Chat ended |

---

## 7. Sign-off Checklist

- [ ] Live chat connects successfully
- [ ] Chatbot handles common queries
- [ ] FAQ portal searchable
- [ ] Tickets created from chat
- [ ] Agent dashboard functional
- [ ] Canned responses work
- [ ] Chat rating submits
- [ ] Chat transcripts saved
- [ ] Mobile chat works

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
