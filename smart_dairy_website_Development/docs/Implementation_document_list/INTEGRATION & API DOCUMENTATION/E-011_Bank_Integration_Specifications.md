# E-011: Bank Integration Specifications

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | E-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Document Control

| Version | Date | Author | Changes | Approver |
|---------|------|--------|---------|----------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial document creation | Pending |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Bangladesh Bank Options](#2-bangladesh-bank-options)
3. [Integration Types](#3-integration-types)
4. [Corporate Banking APIs](#4-corporate-banking-apis)
5. [NPSB Integration](#5-npsb-integration)
6. [RTGS Integration](#6-rtgs-integration)
7. [BEFTN Batch Processing](#7-beftn-batch-processing)
8. [Security Requirements](#8-security-requirements)
9. [Implementation](#9-implementation)
10. [Reconciliation](#10-reconciliation)
11. [Compliance](#11-compliance)
12. [Testing](#12-testing)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document specifies the bank integration requirements for Smart Dairy Ltd.'s payment processing systems. It defines the technical specifications, protocols, security requirements, and implementation guidelines for integrating with Bangladeshi banks to enable automated bulk payments, settlements, and payroll processing.

### 1.2 Scope

This specification covers:

- Direct bank integration for bulk supplier payments
- Employee payroll processing automation
- Customer refund processing to bank accounts
- Settlement reconciliation workflows
- Daily cash position reporting

### 1.3 Use Cases

| Use Case ID | Description | Priority | Frequency |
|-------------|-------------|----------|-----------|
| UC-BANK-001 | Bulk supplier payments | High | Weekly |
| UC-BANK-002 | Employee payroll processing | High | Monthly |
| UC-BANK-003 | Refund processing to customer accounts | Medium | Daily |
| UC-BANK-004 | Settlement reconciliation | High | Daily |
| UC-BANK-005 | Daily cash position reporting | High | Daily |

### 1.4 Target Banks

| Bank Name | Type | API Availability | Priority |
|-----------|------|------------------|----------|
| Dutch-Bangla Bank Ltd. (DBBL) | Commercial | Full API | Primary |
| BRAC Bank Ltd. | Commercial | Full API | Primary |
| Islami Bank Bangladesh Ltd. | Islamic | Limited API | Secondary |
| City Bank Ltd. | Commercial | Full API | Primary |
| Sonali Bank Ltd. | State-owned | Limited API | Secondary |

### 1.5 Document References

| Reference | Description |
|-----------|-------------|
| BB-GPS-2023 | Bangladesh Bank Guidelines on Payment Systems |
| BB-RTGS-2022 | RTGS Operating Procedures |
| BB-BEFTN-2023 | BEFTN User Manual |
| BB-NPSB-2023 | National Payment Switch Bangladesh Guidelines |

---

## 2. Bangladesh Bank Options

### 2.1 Dutch-Bangla Bank Ltd. (DBBL)

#### 2.1.1 Overview
DBBL offers comprehensive API-based corporate banking services with robust security and real-time processing capabilities.

#### 2.1.2 API Endpoints

```yaml
Base URL: https://api.dbbl.com.bd/corporate/v2
Authentication: Mutual TLS + OAuth 2.0
Supported Operations:
  - Balance Inquiry
  - Fund Transfer (Internal & External)
  - Bulk Payment Processing
  - Statement Retrieval
  - Real-time Transaction Status
```

#### 2.1.3 Service Matrix

| Service | API Available | Batch Support | Real-time |
|---------|---------------|---------------|-----------|
| Balance Inquiry | Yes | No | Yes |
| Internal Transfer | Yes | Yes | Yes |
| RTGS | Yes | Yes | Yes |
| BEFTN | Yes | Yes | No (T+1) |
| NPSB | Yes | No | Yes |
| Statement | Yes | No | Yes |

### 2.2 BRAC Bank Ltd.

#### 2.2.1 Overview
BRAC Bank provides comprehensive digital banking APIs with extensive documentation and sandbox environments.

#### 2.2.2 API Endpoints

```yaml
Base URL: https://api.bracbank.com/corporate/v1
Authentication: API Key + HMAC Signature
Supported Operations:
  - Account Management
  - Payment Initiation
  - Bulk Payment Processing
  - Transaction Reporting
  - Real-time Notifications
```

#### 2.2.3 Service Matrix

| Service | API Available | Batch Support | Real-time |
|---------|---------------|---------------|-----------|
| Balance Inquiry | Yes | No | Yes |
| Internal Transfer | Yes | Yes | Yes |
| RTGS | Yes | Yes | Yes |
| BEFTN | Yes | Yes | No (T+1) |
| NPSB | Yes | No | Yes |
| Statement | Yes | No | Yes |

### 2.3 Islami Bank Bangladesh Ltd.

#### 2.3.1 Overview
Islami Bank provides Shariah-compliant banking services with API support for basic operations.

#### 2.3.2 Service Matrix

| Service | API Available | Batch Support | Real-time |
|---------|---------------|---------------|-----------|
| Balance Inquiry | Yes | No | Yes |
| Internal Transfer | Yes | Limited | Yes |
| RTGS | Yes | Yes | Yes |
| BEFTN | Yes | Yes | No (T+1) |
| NPSB | Limited | No | Yes |
| Statement | Yes | No | Yes |

### 2.4 City Bank Ltd.

#### 2.4.1 Overview
City Bank offers modern API infrastructure with comprehensive corporate banking features.

#### 2.4.2 API Endpoints

```yaml
Base URL: https://api.thecitybank.com/corporate/v3
Authentication: OAuth 2.0 + Certificate Pinning
Supported Operations:
  - Multi-currency Account Operations
  - International Payments
  - Bulk Processing
  - Advanced Reporting
  - Webhook Notifications
```

### 2.5 Bank Comparison Matrix

| Feature | DBBL | BRAC Bank | Islami Bank | City Bank |
|---------|------|-----------|-------------|-----------|
| REST API | Full | Full | Limited | Full |
| SOAP API | Yes | No | Yes | No |
| SDK Available | Python, Java | Python, Node.js | Java | Python, Java |
| Sandbox | Yes | Yes | No | Yes |
| Webhook Support | Yes | Yes | No | Yes |
| API Rate Limit | 1000/hr | 500/hr | 200/hr | 1000/hr |
| SLA (Uptime) | 99.9% | 99.5% | 99.0% | 99.9% |
| Support Hours | 24/7 | 24/7 | Business Hours | 24/7 |

---

## 3. Integration Types

### 3.1 RTGS (Real-Time Gross Settlement)

#### 3.1.1 Overview
RTGS is a funds transfer system where transfer of money takes place from one bank to another on a "real-time" and on "gross" basis. Settlement in real-time means the payment transaction is not subjected to any waiting period.

#### 3.1.2 Key Features

| Feature | Specification |
|---------|---------------|
| Minimum Amount | BDT 100,000 |
| Maximum Amount | No limit |
| Operating Hours | 9:00 AM - 6:00 PM (Sunday-Thursday) |
| Settlement Type | Real-time, irrevocable |
| Transaction Fee | BDT 200-500 depending on amount |
| Processing Time | Immediate |

#### 3.1.3 Transaction Limits

```python
RTGS_LIMITS = {
    "minimum_amount": 100000,  # BDT
    "maximum_amount": None,     # No limit
    "daily_limit_per_account": 100000000,  # BDT 10 Crore
    "hourly_limit_per_account": 50000000,  # BDT 5 Crore
}
```

### 3.2 BEFTN (Bangladesh Electronic Funds Transfer Network)

#### 3.2.1 Overview
BEFTN is a nationwide electronic funds transfer system that facilitates inter-bank fund transfers in a batch processing mode.

#### 3.2.2 Key Features

| Feature | Specification |
|---------|---------------|
| Minimum Amount | BDT 1 |
| Maximum Amount | BDT 500,000 |
| Operating Hours | 24/7 submission, 3 batch cycles daily |
| Settlement Type | Net settlement, T+1 |
| Transaction Fee | BDT 5-25 depending on bank |
| Processing Time | Same day or next business day |

#### 3.2.3 Batch Cycles

| Batch | Submission Cut-off | Settlement Time |
|-------|-------------------|-----------------|
| Batch 1 | 10:00 AM | 12:00 PM |
| Batch 2 | 2:00 PM | 4:00 PM |
| Batch 3 | 4:00 PM | 6:00 PM |

### 3.3 NPSB (National Payment Switch Bangladesh)

#### 3.3.1 Overview
NPSB is Bangladesh's national payment switch that enables real-time inter-bank fund transfers using debit cards and account numbers.

#### 3.3.2 Key Features

| Feature | Specification |
|---------|---------------|
| Minimum Amount | BDT 10 |
| Maximum Amount | BDT 200,000 per transaction |
| Operating Hours | 24/7 |
| Settlement Type | Real-time |
| Transaction Fee | BDT 10-20 |
| Processing Time | Immediate |

#### 3.3.3 Transaction Limits

```python
NPSB_LIMITS = {
    "minimum_amount": 10,
    "maximum_amount_per_transaction": 200000,
    "daily_limit_per_card": 500000,
    "monthly_limit_per_card": 5000000,
}
```

### 3.4 Integration Type Comparison

| Aspect | RTGS | BEFTN | NPSB |
|--------|------|-------|------|
| Speed | Real-time | T+1 | Real-time |
| Min Amount | BDT 100,000 | BDT 1 | BDT 10 |
| Max Amount | Unlimited | BDT 500,000 | BDT 200,000 |
| Best For | Large payments | Bulk/Salary | Small transfers |
| Availability | Business hours | 24/7 submission | 24/7 |
| Cost | High | Low | Medium |
| Use Case | Suppliers, large settlements | Payroll, small suppliers | Refunds, small payments |

---

## 4. Corporate Banking APIs

### 4.1 API Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Smart Dairy Banking Layer                     │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────┐ │
│  │   Payment   │  │Reconciliation│  │   Report    │  │ Balance │ │
│  │   Service   │  │   Service   │  │   Service   │  │ Service │ │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └────┬────┘ │
│         └─────────────────┴─────────────────┴──────────────┘     │
│                              │                                   │
│                    ┌─────────┴─────────┐                         │
│                    │   Bank Adapter    │                         │
│                    │     Factory       │                         │
│                    └─────────┬─────────┘                         │
└──────────────────────────────┼───────────────────────────────────┘
                               │
        ┌──────────────────────┼──────────────────────┐
        │                      │                      │
   ┌────┴────┐           ┌────┴────┐           ┌────┴────┐
   │  DBBL   │           │ BRAC    │           │  City   │
   │   API   │           │  Bank   │           │  Bank   │
   └─────────┘           └─────────┘           └─────────┘
```

### 4.2 Balance Inquiry API

#### 4.2.1 Request/Response Format

```python
# models/balance.py
from dataclasses import dataclass
from decimal import Decimal
from datetime import datetime
from typing import Optional
from enum import Enum


class AccountType(Enum):
    CURRENT = "CURRENT"
    SAVINGS = "SAVINGS"
    OVERDRAFT = "OVERDRAFT"


@dataclass
class BalanceRequest:
    account_number: str
    currency: str = "BDT"
    request_id: Optional[str] = None


@dataclass
class BalanceResponse:
    account_number: str
    available_balance: Decimal
    ledger_balance: Decimal
    currency: str
    account_type: AccountType
    timestamp: datetime
    request_id: str
    status: str
    bank_reference: Optional[str] = None
```

#### 4.2.2 Implementation

```python
# services/balance_service.py
import logging
from typing import Dict, Optional
from decimal import Decimal
from datetime import datetime
import requests
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

from models.balance import BalanceRequest, BalanceResponse, AccountType
from config.bank_config import BankConfig

logger = logging.getLogger(__name__)


class BalanceService:
    """Service for querying account balances from bank APIs."""
    
    def __init__(self, bank_config: BankConfig):
        self.config = bank_config
        self.session = self._create_session()
    
    def _create_session(self) -> requests.Session:
        """Create a requests session with retry logic."""
        session = requests.Session()
        
        # Configure retries
        retries = Retry(
            total=3,
            backoff_factor=1,
            status_forcelist=[500, 502, 503, 504]
        )
        
        adapter = HTTPAdapter(max_retries=retries)
        session.mount('https://', adapter)
        session.mount('http://', adapter)
        
        # Set default headers
        session.headers.update({
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Client-ID': self.config.client_id,
        })
        
        return session
    
    def get_balance(self, request: BalanceRequest) -> BalanceResponse:
        """
        Retrieve account balance from the bank.
        
        Args:
            request: BalanceRequest object containing account details
            
        Returns:
            BalanceResponse with balance information
            
        Raises:
            BankAPIException: If the API call fails
        """
        try:
            endpoint = f"{self.config.base_url}/accounts/{request.account_number}/balance"
            
            headers = self._generate_auth_headers(request)
            
            response = self.session.get(
                endpoint,
                headers=headers,
                cert=(self.config.client_cert, self.config.client_key),
                timeout=30
            )
            response.raise_for_status()
            
            data = response.json()
            
            return BalanceResponse(
                account_number=request.account_number,
                available_balance=Decimal(str(data['available_balance'])),
                ledger_balance=Decimal(str(data['ledger_balance'])),
                currency=data.get('currency', 'BDT'),
                account_type=AccountType(data.get('account_type', 'CURRENT')),
                timestamp=datetime.fromisoformat(data['timestamp']),
                request_id=data.get('request_id', request.request_id),
                status=data.get('status', 'SUCCESS'),
                bank_reference=data.get('bank_reference')
            )
            
        except requests.exceptions.RequestException as e:
            logger.error(f"Balance inquiry failed: {str(e)}")
            raise BankAPIException(f"Failed to retrieve balance: {str(e)}")
    
    def get_balances_bulk(self, account_numbers: list) -> Dict[str, BalanceResponse]:
        """Retrieve balances for multiple accounts."""
        results = {}
        
        for account_number in account_numbers:
            try:
                request = BalanceRequest(account_number=account_number)
                results[account_number] = self.get_balance(request)
            except Exception as e:
                logger.error(f"Failed to get balance for {account_number}: {e}")
                results[account_number] = None
                
        return results
    
    def _generate_auth_headers(self, request: BalanceRequest) -> Dict[str, str]:
        """Generate authentication headers for the request."""
        import hmac
        import hashlib
        import base64
        
        timestamp = datetime.utcnow().strftime('%Y%m%d%H%M%S')
        message = f"{request.account_number}:{timestamp}:{self.config.client_id}"
        signature = hmac.new(
            self.config.client_secret.encode(),
            message.encode(),
            hashlib.sha256
        ).digest()
        
        return {
            'X-Timestamp': timestamp,
            'X-Signature': base64.b64encode(signature).decode(),
            'Authorization': f"Bearer {self._get_access_token()}"
        }
    
    def _get_access_token(self) -> str:
        """Obtain OAuth access token."""
        # Token caching and refresh logic would be implemented here
        return self.config.access_token


class BankAPIException(Exception):
    """Exception for bank API errors."""
    pass
```

### 4.3 Fund Transfer API

#### 4.3.1 Request/Response Format

```python
# models/transfer.py
from dataclasses import dataclass
from decimal import Decimal
from datetime import datetime
from typing import Optional
from enum import Enum


class TransferType(Enum):
    INTERNAL = "INTERNAL"  # Same bank
    RTGS = "RTGS"
    BEFTN = "BEFTN"
    NPSB = "NPSB"


class TransferStatus(Enum):
    PENDING = "PENDING"
    PROCESSING = "PROCESSING"
    COMPLETED = "COMPLETED"
    FAILED = "FAILED"
    CANCELLED = "CANCELLED"


@dataclass
class FundTransferRequest:
    source_account: str
    destination_account: str
    destination_bank_code: str
    amount: Decimal
    currency: str = "BDT"
    transfer_type: TransferType = TransferType.BEFTN
    reference: Optional[str] = None
    description: Optional[str] = None
    beneficiary_name: Optional[str] = None
    beneficiary_email: Optional[str] = None
    beneficiary_mobile: Optional[str] = None
    request_id: Optional[str] = None


@dataclass
class FundTransferResponse:
    request_id: str
    transaction_id: str
    bank_reference: str
    status: TransferStatus
    source_account: str
    destination_account: str
    amount: Decimal
    currency: str
    timestamp: datetime
    estimated_settlement: Optional[datetime] = None
    failure_reason: Optional[str] = None
```

#### 4.3.2 Implementation

```python
# services/transfer_service.py
import uuid
import logging
from datetime import datetime, timedelta
from typing import Optional
from decimal import Decimal

from models.transfer import (
    FundTransferRequest, 
    FundTransferResponse,
    TransferType,
    TransferStatus
)
from config.bank_config import BankConfig
from services.balance_service import BalanceService, BankAPIException

logger = logging.getLogger(__name__)


class FundTransferService:
    """Service for executing fund transfers."""
    
    # Transfer type validation rules
    TRANSFER_LIMITS = {
        TransferType.RTGS: {
            'min': Decimal('100000'),
            'max': None,
            'hours': (9, 18),  # 9 AM - 6 PM
        },
        TransferType.BEFTN: {
            'min': Decimal('1'),
            'max': Decimal('500000'),
            'hours': (0, 24),  # 24/7
        },
        TransferType.NPSB: {
            'min': Decimal('10'),
            'max': Decimal('200000'),
            'hours': (0, 24),  # 24/7
        },
        TransferType.INTERNAL: {
            'min': Decimal('1'),
            'max': None,
            'hours': (0, 24),  # 24/7
        }
    }
    
    def __init__(self, bank_config: BankConfig, balance_service: BalanceService):
        self.config = bank_config
        self.balance_service = balance_service
    
    def initiate_transfer(self, request: FundTransferRequest) -> FundTransferResponse:
        """
        Initiate a fund transfer transaction.
        
        Args:
            request: FundTransferRequest with transfer details
            
        Returns:
            FundTransferResponse with transaction details
        """
        # Validate transfer request
        self._validate_transfer(request)
        
        # Generate request ID if not provided
        if not request.request_id:
            request.request_id = str(uuid.uuid4())
        
        try:
            # Check source account balance
            balance = self.balance_service.get_balance(
                BalanceRequest(account_number=request.source_account)
            )
            
            if balance.available_balance < request.amount:
                raise InsufficientFundsException(
                    f"Insufficient funds. Available: {balance.available_balance}, "
                    f"Required: {request.amount}"
                )
            
            # Route to appropriate transfer method
            if request.transfer_type == TransferType.INTERNAL:
                return self._process_internal_transfer(request)
            elif request.transfer_type == TransferType.RTGS:
                return self._process_rtgs_transfer(request)
            elif request.transfer_type == TransferType.BEFTN:
                return self._process_beftn_transfer(request)
            elif request.transfer_type == TransferType.NPSB:
                return self._process_npsb_transfer(request)
            else:
                raise ValueError(f"Unsupported transfer type: {request.transfer_type}")
                
        except Exception as e:
            logger.error(f"Transfer failed: {str(e)}")
            raise TransferException(f"Transfer initiation failed: {str(e)}")
    
    def _validate_transfer(self, request: FundTransferRequest):
        """Validate transfer request against business rules."""
        limits = self.TRANSFER_LIMITS.get(request.transfer_type)
        
        if not limits:
            raise ValidationException(f"Invalid transfer type: {request.transfer_type}")
        
        # Check minimum amount
        if limits['min'] and request.amount < limits['min']:
            raise ValidationException(
                f"Amount {request.amount} below minimum {limits['min']} for {request.transfer_type.value}"
            )
        
        # Check maximum amount
        if limits['max'] and request.amount > limits['max']:
            raise ValidationException(
                f"Amount {request.amount} exceeds maximum {limits['max']} for {request.transfer_type.value}"
            )
        
        # Check operating hours for RTGS
        if request.transfer_type == TransferType.RTGS:
            current_hour = datetime.now().hour
            if not (limits['hours'][0] <= current_hour < limits['hours'][1]):
                raise ValidationException(
                    f"RTGS transfers only available between {limits['hours'][0]}:00 and {limits['hours'][1]}:00"
                )
    
    def _process_internal_transfer(self, request: FundTransferRequest) -> FundTransferResponse:
        """Process internal (same bank) transfer."""
        endpoint = f"{self.config.base_url}/transfers/internal"
        
        payload = {
            "source_account": request.source_account,
            "destination_account": request.destination_account,
            "amount": str(request.amount),
            "currency": request.currency,
            "reference": request.reference,
            "description": request.description,
            "request_id": request.request_id
        }
        
        response = self._execute_transfer(endpoint, payload)
        
        return FundTransferResponse(
            request_id=request.request_id,
            transaction_id=response['transaction_id'],
            bank_reference=response['bank_reference'],
            status=TransferStatus.COMPLETED,
            source_account=request.source_account,
            destination_account=request.destination_account,
            amount=request.amount,
            currency=request.currency,
            timestamp=datetime.now(),
            estimated_settlement=datetime.now()
        )
    
    def _process_rtgs_transfer(self, request: FundTransferRequest) -> FundTransferResponse:
        """Process RTGS transfer."""
        endpoint = f"{self.config.base_url}/transfers/rtgs"
        
        payload = {
            "source_account": request.source_account,
            "destination_account": request.destination_account,
            "destination_bank_code": request.destination_bank_code,
            "amount": str(request.amount),
            "currency": request.currency,
            "reference": request.reference,
            "description": request.description,
            "beneficiary_name": request.beneficiary_name,
            "request_id": request.request_id
        }
        
        response = self._execute_transfer(endpoint, payload)
        
        return FundTransferResponse(
            request_id=request.request_id,
            transaction_id=response['transaction_id'],
            bank_reference=response['bank_reference'],
            status=TransferStatus.PROCESSING,
            source_account=request.source_account,
            destination_account=request.destination_account,
            amount=request.amount,
            currency=request.currency,
            timestamp=datetime.now(),
            estimated_settlement=datetime.now()  # RTGS is immediate
        )
    
    def _process_beftn_transfer(self, request: FundTransferRequest) -> FundTransferResponse:
        """Process BEFTN transfer."""
        endpoint = f"{self.config.base_url}/transfers/beftn"
        
        payload = {
            "source_account": request.source_account,
            "destination_account": request.destination_account,
            "destination_bank_code": request.destination_bank_code,
            "amount": str(request.amount),
            "currency": request.currency,
            "reference": request.reference,
            "description": request.description,
            "beneficiary_name": request.beneficiary_name,
            "request_id": request.request_id
        }
        
        response = self._execute_transfer(endpoint, payload)
        
        # Calculate estimated settlement (next business day)
        settlement_time = self._calculate_beftn_settlement()
        
        return FundTransferResponse(
            request_id=request.request_id,
            transaction_id=response['transaction_id'],
            bank_reference=response['bank_reference'],
            status=TransferStatus.PENDING,
            source_account=request.source_account,
            destination_account=request.destination_account,
            amount=request.amount,
            currency=request.currency,
            timestamp=datetime.now(),
            estimated_settlement=settlement_time
        )
    
    def _process_npsb_transfer(self, request: FundTransferRequest) -> FundTransferResponse:
        """Process NPSB transfer."""
        endpoint = f"{self.config.base_url}/transfers/npsb"
        
        payload = {
            "source_account": request.source_account,
            "destination_account": request.destination_account,
            "destination_bank_code": request.destination_bank_code,
            "amount": str(request.amount),
            "currency": request.currency,
            "reference": request.reference,
            "description": request.description,
            "beneficiary_name": request.beneficiary_name,
            "beneficiary_mobile": request.beneficiary_mobile,
            "request_id": request.request_id
        }
        
        response = self._execute_transfer(endpoint, payload)
        
        return FundTransferResponse(
            request_id=request.request_id,
            transaction_id=response['transaction_id'],
            bank_reference=response['bank_reference'],
            status=TransferStatus.COMPLETED,
            source_account=request.source_account,
            destination_account=request.destination_account,
            amount=request.amount,
            currency=request.currency,
            timestamp=datetime.now(),
            estimated_settlement=datetime.now()  # NPSB is immediate
        )
    
    def _execute_transfer(self, endpoint: str, payload: dict) -> dict:
        """Execute transfer API call."""
        import requests
        
        headers = {
            'Content-Type': 'application/json',
            'Authorization': f"Bearer {self.config.access_token}",
            'X-Idempotency-Key': payload['request_id']
        }
        
        response = requests.post(
            endpoint,
            json=payload,
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=60
        )
        response.raise_for_status()
        
        return response.json()
    
    def _calculate_beftn_settlement(self) -> datetime:
        """Calculate estimated settlement time for BEFTN."""
        now = datetime.now()
        
        # BEFTN batch cutoffs
        batch_times = [10, 14, 16]  # 10 AM, 2 PM, 4 PM
        settlement_delay = 2  # hours after batch
        
        for batch_time in batch_times:
            if now.hour < batch_time:
                return now.replace(hour=batch_time + settlement_delay, minute=0, second=0)
        
        # Next day first batch
        next_day = now + timedelta(days=1)
        return next_day.replace(hour=batch_times[0] + settlement_delay, minute=0, second=0)


class InsufficientFundsException(Exception):
    pass


class ValidationException(Exception):
    pass


class TransferException(Exception):
    pass
```

### 4.4 Statement Retrieval API

```python
# services/statement_service.py
import logging
from datetime import datetime, timedelta
from typing import List, Optional
from decimal import Decimal
from dataclasses import dataclass
from enum import Enum

import requests


class TransactionType(Enum):
    CREDIT = "CREDIT"
    DEBIT = "DEBIT"


@dataclass
class Transaction:
    transaction_id: str
    date: datetime
    description: str
    reference: str
    amount: Decimal
    type: TransactionType
    balance: Decimal
    bank_reference: Optional[str] = None


@dataclass
class StatementRequest:
    account_number: str
    start_date: datetime
    end_date: datetime
    format: str = "JSON"  # JSON, CSV, PDF, MT940


class StatementService:
    """Service for retrieving account statements."""
    
    def __init__(self, bank_config):
        self.config = bank_config
        self.logger = logging.getLogger(__name__)
    
    def get_statement(self, request: StatementRequest) -> List[Transaction]:
        """Retrieve account statement for the specified period."""
        
        # Validate date range
        if request.end_date < request.start_date:
            raise ValueError("End date must be after start date")
        
        if (request.end_date - request.start_date).days > 90:
            raise ValueError("Date range cannot exceed 90 days")
        
        endpoint = f"{self.config.base_url}/accounts/{request.account_number}/statements"
        
        params = {
            'start_date': request.start_date.strftime('%Y-%m-%d'),
            'end_date': request.end_date.strftime('%Y-%m-%d'),
            'format': request.format
        }
        
        headers = {
            'Authorization': f"Bearer {self.config.access_token}",
            'Accept': 'application/json'
        }
        
        response = requests.get(
            endpoint,
            params=params,
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=30
        )
        response.raise_for_status()
        
        data = response.json()
        
        transactions = []
        for txn_data in data.get('transactions', []):
            transactions.append(Transaction(
                transaction_id=txn_data['transaction_id'],
                date=datetime.fromisoformat(txn_data['date']),
                description=txn_data['description'],
                reference=txn_data['reference'],
                amount=Decimal(str(txn_data['amount'])),
                type=TransactionType(txn_data['type']),
                balance=Decimal(str(txn_data['balance'])),
                bank_reference=txn_data.get('bank_reference')
            ))
        
        return transactions
    
    def get_daily_summary(self, account_number: str, date: datetime) -> dict:
        """Get daily transaction summary."""
        request = StatementRequest(
            account_number=account_number,
            start_date=date.replace(hour=0, minute=0, second=0),
            end_date=date.replace(hour=23, minute=59, second=59)
        )
        
        transactions = self.get_statement(request)
        
        credits = [t for t in transactions if t.type == TransactionType.CREDIT]
        debits = [t for t in transactions if t.type == TransactionType.DEBIT]
        
        return {
            'date': date.strftime('%Y-%m-%d'),
            'opening_balance': transactions[0].balance - transactions[0].amount if transactions else Decimal('0'),
            'closing_balance': transactions[-1].balance if transactions else Decimal('0'),
            'total_credits': sum(t.amount for t in credits),
            'total_debits': sum(t.amount for t in debits),
            'credit_count': len(credits),
            'debit_count': len(debits),
            'transaction_count': len(transactions)
        }
```



---

## 5. NPSB Integration

### 5.1 Overview

National Payment Switch Bangladesh (NPSB) enables real-time inter-bank fund transfers using both card-based and account-based transactions. Smart Dairy will use NPSB primarily for customer refunds and small-value supplier payments.

### 5.2 Technical Specifications

#### 5.2.1 Connectivity

| Parameter | Specification |
|-----------|---------------|
| Protocol | HTTPS (TLS 1.2+) |
| Message Format | ISO 8583 (adapted) |
| Character Encoding | UTF-8 |
| Timeout | 30 seconds |
| Retry Attempts | 3 with exponential backoff |

#### 5.2.2 NPSB Bank Codes

```python
# config/npsb_bank_codes.py
NPSB_BANK_CODES = {
    "DBBL": "110",      # Dutch-Bangla Bank
    "BRAC": "115",      # BRAC Bank
    "IBBL": "125",      # Islami Bank
    "CITY": "135",      # City Bank
    "SONALI": "200",    # Sonali Bank
    "JANATA": "210",    # Janata Bank
    "AGRANI": "220",    # Agrani Bank
    "RUPALI": "230",    # Rupali Bank
    "PUBALI": "120",    # Pubali Bank
    "UCB": "140",       # UCB Bank
    "AB": "150",        # AB Bank
    "EBL": "160",       # Eastern Bank
    "SIBL": "170",      # Social Islami Bank
    "MTB": "180",       # Mutual Trust Bank
    "IFIC": "190",      # IFIC Bank
}
```

### 5.3 NPSB Transfer Implementation

```python
# services/npsb_service.py
import logging
import uuid
from datetime import datetime
from decimal import Decimal
from typing import Optional, Dict
from dataclasses import dataclass

import requests
import hmac
import hashlib

from config.npsb_bank_codes import NPSB_BANK_CODES


@dataclass
class NPSBTransferRequest:
    source_account: str
    destination_account: str
    destination_bank_code: str
    amount: Decimal
    beneficiary_name: str
    beneficiary_mobile: Optional[str] = None
    narrative: Optional[str] = None
    request_id: Optional[str] = None


@dataclass
class NPSBTransferResponse:
    request_id: str
    transaction_id: str
    status: str
    response_code: str
    response_message: str
    timestamp: datetime
    settlement_reference: Optional[str] = None


class NPSBService:
    """
    Service for handling NPSB (National Payment Switch Bangladesh) transactions.
    NPSB enables real-time inter-bank fund transfers up to BDT 200,000.
    """
    
    # Transaction limits
    MIN_AMOUNT = Decimal('10')
    MAX_AMOUNT = Decimal('200000')
    
    # NPSB operating hours (24/7)
    AVAILABLE_HOURS = (0, 24)
    
    def __init__(self, config):
        self.config = config
        self.logger = logging.getLogger(__name__)
        self.base_url = config.npsb_base_url
        self.timeout = 30
    
    def validate_bank_code(self, bank_code: str) -> bool:
        """Validate if bank code is supported by NPSB."""
        return bank_code in NPSB_BANK_CODES.values()
    
    def validate_amount(self, amount: Decimal) -> tuple[bool, str]:
        """Validate transaction amount against NPSB limits."""
        if amount < self.MIN_AMOUNT:
            return False, f"Amount below minimum of BDT {self.MIN_AMOUNT}"
        if amount > self.MAX_AMOUNT:
            return False, f"Amount exceeds maximum of BDT {self.MAX_AMOUNT}"
        return True, "Valid"
    
    def initiate_transfer(self, request: NPSBTransferRequest) -> NPSBTransferResponse:
        """
        Initiate an NPSB fund transfer.
        
        Args:
            request: NPSBTransferRequest containing transfer details
            
        Returns:
            NPSBTransferResponse with transaction details
            
        Raises:
            NPSBValidationError: If validation fails
            NPSBAPIError: If API call fails
        """
        # Generate request ID
        if not request.request_id:
            request.request_id = str(uuid.uuid4())
        
        # Validate bank code
        if not self.validate_bank_code(request.destination_bank_code):
            raise NPSBValidationError(
                f"Invalid or unsupported bank code: {request.destination_bank_code}"
            )
        
        # Validate amount
        is_valid, message = self.validate_amount(request.amount)
        if not is_valid:
            raise NPSBValidationError(message)
        
        try:
            # Build ISO 8583 formatted message
            iso_message = self._build_iso_message(request)
            
            # Generate authentication headers
            headers = self._generate_auth_headers(request, iso_message)
            
            # Send request
            response = requests.post(
                f"{self.base_url}/npsb/transfer",
                json={
                    'iso_message': iso_message,
                    'request_id': request.request_id,
                    'source_account': request.source_account,
                    'destination_account': request.destination_account,
                    'destination_bank_code': request.destination_bank_code,
                    'amount': str(request.amount),
                    'beneficiary_name': request.beneficiary_name,
                    'beneficiary_mobile': request.beneficiary_mobile,
                    'narrative': request.narrative
                },
                headers=headers,
                cert=(self.config.client_cert, self.config.client_key),
                timeout=self.timeout
            )
            
            response.raise_for_status()
            data = response.json()
            
            return NPSBTransferResponse(
                request_id=request.request_id,
                transaction_id=data['transaction_id'],
                status=data['status'],
                response_code=data['response_code'],
                response_message=data['response_message'],
                timestamp=datetime.now(),
                settlement_reference=data.get('settlement_reference')
            )
            
        except requests.exceptions.Timeout:
            self.logger.error(f"NPSB transfer timeout for request {request.request_id}")
            raise NPSBAPIError("Transfer request timed out")
        except requests.exceptions.RequestException as e:
            self.logger.error(f"NPSB transfer failed: {str(e)}")
            raise NPSBAPIError(f"Transfer failed: {str(e)}")
    
    def _build_iso_message(self, request: NPSBTransferRequest) -> str:
        """
        Build ISO 8583 formatted message for NPSB.
        
        Field mapping for NPSB:
        - MTI: 0200 (Financial Transaction Request)
        - Field 2: Primary Account Number (PAN) / Source Account
        - Field 3: Processing Code
        - Field 4: Transaction Amount
        - Field 11: System Trace Audit Number
        - Field 12: Local Transaction Time
        - Field 13: Local Transaction Date
        - Field 32: Acquiring Institution ID Code
        - Field 35: Track 2 Data / Destination Account
        - Field 37: Retrieval Reference Number
        - Field 41: Card Acceptor Terminal ID
        - Field 43: Card Acceptor Name/Location
        - Field 48: Additional Data (Beneficiary Info)
        - Field 49: Transaction Currency Code (050 = BDT)
        - Field 100: Receiving Institution ID Code (Destination Bank)
        """
        timestamp = datetime.now()
        
        iso_fields = {
            'mti': '0200',
            'field_002': request.source_account,
            'field_003': '400000',  # Credit to account
            'field_004': str(int(request.amount * 100)).zfill(12),  # Amount in minor units
            'field_011': str(uuid.uuid4().int % 1000000).zfill(6),  # STAN
            'field_012': timestamp.strftime('%H%M%S'),
            'field_013': timestamp.strftime('%m%d'),
            'field_032': self.config.institution_id,
            'field_035': request.destination_account,
            'field_037': request.request_id[:12],
            'field_041': self.config.terminal_id,
            'field_043': 'SMART DAIRY LTD       DHAKA       BGD',
            'field_048': f"BEN:{request.beneficiary_name[:30]:<30}",
            'field_049': '050',  # BDT currency code
            'field_100': request.destination_bank_code,
        }
        
        # Convert to pipe-delimited format (simplified representation)
        return '|'.join([f"{k}={v}" for k, v in iso_fields.items()])
    
    def _generate_auth_headers(self, request: NPSBTransferRequest, iso_message: str) -> Dict[str, str]:
        """Generate authentication headers for NPSB API."""
        timestamp = datetime.utcnow().strftime('%Y%m%d%H%M%S')
        
        # Create signature
        message = f"{request.request_id}:{timestamp}:{iso_message}"
        signature = hmac.new(
            self.config.npsb_secret.encode(),
            message.encode(),
            hashlib.sha256
        ).hexdigest()
        
        return {
            'Content-Type': 'application/json',
            'X-Request-ID': request.request_id,
            'X-Timestamp': timestamp,
            'X-Signature': signature,
            'X-Institution-ID': self.config.institution_id,
            'Authorization': f"Bearer {self.config.npsb_token}"
        }
    
    def check_transaction_status(self, transaction_id: str) -> Dict:
        """Check the status of an NPSB transaction."""
        headers = self._generate_status_headers(transaction_id)
        
        response = requests.get(
            f"{self.base_url}/npsb/transaction/{transaction_id}/status",
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=self.timeout
        )
        
        response.raise_for_status()
        return response.json()
    
    def _generate_status_headers(self, transaction_id: str) -> Dict[str, str]:
        """Generate headers for status inquiry."""
        timestamp = datetime.utcnow().strftime('%Y%m%d%H%M%S')
        
        message = f"{transaction_id}:{timestamp}"
        signature = hmac.new(
            self.config.npsb_secret.encode(),
            message.encode(),
            hashlib.sha256
        ).hexdigest()
        
        return {
            'X-Timestamp': timestamp,
            'X-Signature': signature,
            'X-Institution-ID': self.config.institution_id,
            'Authorization': f"Bearer {self.config.npsb_token}"
        }
    
    def reverse_transaction(self, transaction_id: str, reason: str) -> NPSBTransferResponse:
        """
        Request reversal of an NPSB transaction.
        Note: Reversals must be initiated within 24 hours and are subject to approval.
        """
        request_id = str(uuid.uuid4())
        
        payload = {
            'original_transaction_id': transaction_id,
            'reversal_reason': reason,
            'request_id': request_id
        }
        
        headers = self._generate_auth_headers(
            NPSBTransferRequest(
                source_account="",
                destination_account="",
                destination_bank_code="",
                amount=Decimal('0'),
                beneficiary_name=""
            ),
            str(payload)
        )
        
        response = requests.post(
            f"{self.base_url}/npsb/transaction/reversal",
            json=payload,
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=self.timeout
        )
        
        response.raise_for_status()
        data = response.json()
        
        return NPSBTransferResponse(
            request_id=request_id,
            transaction_id=data.get('reversal_transaction_id', ''),
            status=data['status'],
            response_code=data['response_code'],
            response_message=data['response_message'],
            timestamp=datetime.now()
        )


class NPSBValidationError(Exception):
    """Exception for NPSB validation errors."""
    pass


class NPSBAPIError(Exception):
    """Exception for NPSB API errors."""
    pass
```

### 5.4 NPSB Response Codes

| Code | Description | Action Required |
|------|-------------|-----------------|
| 00 | Approved | None - Transaction successful |
| 05 | Do not honor | Contact bank for authorization |
| 12 | Invalid transaction | Check transaction details |
| 13 | Invalid amount | Verify amount within limits |
| 14 | Invalid account | Verify account number |
| 15 | No such issuer | Invalid bank code |
| 30 | Format error | Check message format |
| 41 | Lost card | Contact bank immediately |
| 51 | Insufficient funds | Check account balance |
| 54 | Expired card | Update card information |
| 57 | Transaction not permitted | Check account permissions |
| 61 | Exceeds limit | Amount exceeds daily/monthly limit |
| 91 | Issuer/switch inoperative | Retry after some time |
| 96 | System malfunction | Contact technical support |

---

## 6. RTGS Integration

### 6.1 Overview

Real-Time Gross Settlement (RTGS) is used for large-value, time-critical payments to suppliers and inter-company settlements. RTGS transactions are settled individually and immediately.

### 6.2 Technical Specifications

#### 6.2.1 Operating Parameters

| Parameter | Specification |
|-----------|---------------|
| Minimum Amount | BDT 100,000 |
| Maximum Amount | No limit |
| Operating Hours | 9:00 AM - 6:00 PM (Sunday-Thursday) |
| Settlement | Real-time, irrevocable |
| Message Format | ISO 20022 (XML) |
| Communication | SWIFT MT or proprietary API |

#### 6.2.2 Bangladesh Bank RTGS Codes

```python
# config/rtgs_codes.py
RTGS_ROUTING_CODES = {
    # Major commercial banks
    "DBBL": "110260013",
    "BRAC": "115110016",
    "IBBL": "125260018",
    "CITY": "135110026",
    "PUBALI": "120110014",
    "UCB": "140260019",
    "AB": "150110013",
    "EBL": "160110014",
    "SIBL": "165110015",
    "MTB": "170110015",
    "IFIC": "180110019",
    "NRB": "185110017",
    "NBL": "190110016",
    "SOUTHEAST": "195110015",
    "PRIME": "200110014",
    "ONE": "205110017",
    "PREMIER": "210110018",
    "BANKASIA": "215110019",
    "TRUST": "220110014",
    
    # State-owned banks
    "SONALI": "200110018",
    "JANATA": "210110026",
    "AGRANI": "220110020",
    "RUPALI": "230110017",
}
```

### 6.3 RTGS Implementation

```python
# services/rtgs_service.py
import logging
import uuid
from datetime import datetime, time
from decimal import Decimal
from typing import Optional, Dict
from dataclasses import dataclass, field
from xml.etree.ElementTree import Element, SubElement, tostring
from xml.dom import minidom

import requests
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.asymmetric import padding, rsa


@dataclass
class RTGSTransferRequest:
    source_account: str
    destination_account: str
    destination_bank_code: str
    destination_bank_name: str
    beneficiary_name: str
    beneficiary_address: str
    amount: Decimal
    currency: str = "BDT"
    value_date: Optional[datetime] = None
    purpose_code: str = "SPLS"  # Supplier payment
    reference: Optional[str] = None
    sender_to_receiver_info: Optional[str] = None
    request_id: Optional[str] = None
    
    def __post_init__(self):
        if not self.request_id:
            self.request_id = str(uuid.uuid4())
        if not self.value_date:
            self.value_date = datetime.now()


@dataclass
class RTGSTransferResponse:
    request_id: str
    transaction_id: str
    bank_reference: str
    status: str
    message_id: str
    created_at: datetime
    settlement_time: Optional[datetime] = None
    rejection_reason: Optional[str] = None


class RTGSService:
    """
    Service for handling RTGS (Real-Time Gross Settlement) transactions.
    Used for large-value payments (BDT 100,000+) requiring same-day settlement.
    """
    
    MIN_AMOUNT = Decimal('100000')
    OPERATING_HOURS_START = time(9, 0)
    OPERATING_HOURS_END = time(18, 0)
    
    def __init__(self, config):
        self.config = config
        self.logger = logging.getLogger(__name__)
        self.base_url = config.rtgs_base_url
        
        # Load signing certificate
        with open(config.signing_key_path, 'rb') as key_file:
            self.signing_key = serialization.load_pem_private_key(
                key_file.read(),
                password=config.signing_key_password.encode() if config.signing_key_password else None
            )
    
    def is_operating_hours(self) -> bool:
        """Check if current time is within RTGS operating hours."""
        now = datetime.now()
        
        # Check if weekend (Friday = 4, Saturday = 5)
        if now.weekday() >= 4:
            return False
        
        current_time = now.time()
        return self.OPERATING_HOURS_START <= current_time < self.OPERATING_HOURS_END
    
    def validate_request(self, request: RTGSTransferRequest) -> tuple[bool, str]:
        """Validate RTGS transfer request."""
        # Check amount
        if request.amount < self.MIN_AMOUNT:
            return False, f"Amount below RTGS minimum of BDT {self.MIN_AMOUNT}"
        
        # Check operating hours
        if not self.is_operating_hours():
            return False, "Outside RTGS operating hours (9 AM - 6 PM, Sunday-Thursday)"
        
        # Validate bank code
        if request.destination_bank_code not in RTGS_ROUTING_CODES.values():
            return False, f"Invalid RTGS bank code: {request.destination_bank_code}"
        
        return True, "Valid"
    
    def initiate_transfer(self, request: RTGSTransferRequest) -> RTGSTransferResponse:
        """
        Initiate an RTGS fund transfer.
        
        Args:
            request: RTGSTransferRequest containing transfer details
            
        Returns:
            RTGSTransferResponse with transaction details
        """
        # Validate request
        is_valid, message = self.validate_request(request)
        if not is_valid:
            raise RTGSValidationError(message)
        
        try:
            # Build ISO 20022 message
            iso_message = self._build_iso20022_message(request)
            
            # Sign the message
            signature = self._sign_message(iso_message)
            
            # Send to RTGS gateway
            response = self._send_to_rtgs(request, iso_message, signature)
            
            return RTGSTransferResponse(
                request_id=request.request_id,
                transaction_id=response['transaction_id'],
                bank_reference=response['bank_reference'],
                status=response['status'],
                message_id=response['message_id'],
                created_at=datetime.now(),
                settlement_time=datetime.now() if response['status'] == 'SETTLED' else None
            )
            
        except Exception as e:
            self.logger.error(f"RTGS transfer failed: {str(e)}")
            raise RTGSAPIError(f"RTGS transfer failed: {str(e)}")
    
    def _build_iso20022_message(self, request: RTGSTransferRequest) -> str:
        """
        Build ISO 20022 pacs.008 message for RTGS.
        
        pacs.008 = Customer Credit Transfer
        """
        # Create root element
        root = Element('Document')
        root.set('xmlns', 'urn:iso:std:iso:20022:tech:xsd:pacs.008.001.08')
        
        # Create FIToFICstmrCdtTrf element
        cdt_trf = SubElement(root, 'FIToFICstmrCdtTrf')
        
        # Group Header
        grp_hdr = SubElement(cdt_trf, 'GrpHdr')
        SubElement(grp_hdr, 'MsgId').text = request.request_id
        SubElement(grp_hdr, 'CreDtTm').text = datetime.now().isoformat()
        SubElement(grp_hdr, 'NbOfTxs').text = '1'
        SubElement(grp_hdr, 'SttlmInf').text = 'CLRG'  # Clearing
        
        # Credit Transfer Transaction Information
        cdt_trf_tx_inf = SubElement(cdt_trf, 'CdtTrfTxInf')
        
        # Payment Identification
        pmt_id = SubElement(cdt_trf_tx_inf, 'PmtId')
        SubElement(pmt_id, 'InstrId').text = request.request_id
        SubElement(pmt_id, 'EndToEndId').text = request.reference or request.request_id
        
        # Payment Type Information
        pmt_tp_inf = SubElement(cdt_trf_tx_inf, 'PmtTpInf')
        svc_lvl = SubElement(pmt_tp_inf, 'SvcLvl')
        SubElement(svc_lvl, 'Cd').text = 'URGP'  # Urgent
        
        # Settlement Information
        sttlm_inf = SubElement(cdt_trf_tx_inf, 'SttlmInf')
        SubElement(sttlm_inf, 'SttlmMtd').text = 'INDA'  # InstructedAmount
        
        # Settlement Amount
        intr_bk_sttlm_amt = SubElement(cdt_trf_tx_inf, 'IntrBkSttlmAmt')
        intr_bk_sttlm_amt.set('Ccy', request.currency)
        intr_bk_sttlm_amt.text = str(request.amount)
        
        # Value Date
        SubElement(cdt_trf_tx_inf, 'IntrBkSttlmDt').text = request.value_date.strftime('%Y-%m-%d')
        
        # Charge Bearer
        SubElement(cdt_trf_tx_inf, 'ChrgBr').text = 'SLEV'  # Shared
        
        # Instructing Agent (Source Bank)
        instg_agt = SubElement(cdt_trf_tx_inf, 'InstgAgt')
        fin_instn_id = SubElement(instg_agt, 'FinInstnId')
        SubElement(fin_instn_id, 'BICFI').text = self.config.bank_bic
        
        # Instructed Agent (Destination Bank)
        instd_agt = SubElement(cdt_trf_tx_inf, 'InstdAgt')
        fin_instn_id = SubElement(instd_agt, 'FinInstnId')
        SubElement(fin_instn_id, 'BICFI').text = request.destination_bank_code
        
        # Debtor (Source)
        dbtr = SubElement(cdt_trf_tx_inf, 'Dbtr')
        SubElement(dbtr, 'Nm').text = self.config.company_name
        dbtr_acct = SubElement(dbtr, 'Id')
        SubElement(dbtr_acct, 'Othr')
        SubElement(dbtr_acct, 'Id').text = request.source_account
        
        # Creditor (Destination)
        cdtr = SubElement(cdt_trf_tx_inf, 'Cdtr')
        SubElement(cdtr, 'Nm').text = request.beneficiary_name
        SubElement(cdtr, 'PstlAdr').text = request.beneficiary_address
        cdtr_acct = SubElement(cdtr, 'Id')
        SubElement(cdtr_acct, 'Othr')
        SubElement(cdtr_acct, 'Id').text = request.destination_account
        
        # Remittance Information
        rmt_inf = SubElement(cdt_trf_tx_inf, 'RmtInf')
        SubElement(rmt_inf, 'Ustrd').text = request.sender_to_receiver_info or 'Payment'
        
        # Convert to pretty XML
        rough_string = tostring(root, encoding='unicode')
        reparsed = minidom.parseString(rough_string)
        
        return reparsed.toprettyxml(indent="  ")
    
    def _sign_message(self, message: str) -> str:
        """Sign the ISO 20022 message using RSA private key."""
        signature = self.signing_key.sign(
            message.encode('utf-8'),
            padding.PKCS1v15(),
            hashes.SHA256()
        )
        return signature.hex()
    
    def _send_to_rtgs(self, request: RTGSTransferRequest, message: str, signature: str) -> Dict:
        """Send the signed message to RTGS gateway."""
        payload = {
            'message_type': 'pacs.008',
            'message': message,
            'signature': signature,
            'sender_bic': self.config.bank_bic,
            'receiver_bic': request.destination_bank_code,
            'request_id': request.request_id
        }
        
        headers = {
            'Content-Type': 'application/json',
            'X-Message-Id': request.request_id,
            'X-Sender-BIC': self.config.bank_bic,
            'Authorization': f"Bearer {self.config.rtgs_token}"
        }
        
        response = requests.post(
            f"{self.base_url}/rtgs/transfer",
            json=payload,
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=60
        )
        
        response.raise_for_status()
        return response.json()
    
    def get_transaction_status(self, message_id: str) -> Dict:
        """Get the status of an RTGS transaction."""
        headers = {
            'Authorization': f"Bearer {self.config.rtgs_token}",
            'X-Sender-BIC': self.config.bank_bic
        }
        
        response = requests.get(
            f"{self.base_url}/rtgs/transaction/{message_id}/status",
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=30
        )
        
        response.raise_for_status()
        return response.json()
    
    def cancel_transaction(self, message_id: str, reason: str) -> bool:
        """
        Request cancellation of an RTGS transaction.
        Note: Only possible if not yet settled.
        """
        payload = {
            'original_message_id': message_id,
            'cancellation_reason': reason,
            'request_id': str(uuid.uuid4())
        }
        
        headers = {
            'Content-Type': 'application/json',
            'Authorization': f"Bearer {self.config.rtgs_token}"
        }
        
        response = requests.post(
            f"{self.base_url}/rtgs/cancellation",
            json=payload,
            headers=headers,
            cert=(self.config.client_cert, self.config.client_key),
            timeout=30
        )
        
        if response.status_code == 200:
            result = response.json()
            return result.get('status') == 'ACCEPTED'
        
        return False


class RTGSValidationError(Exception):
    pass


class RTGSAPIError(Exception):
    pass
```

### 6.4 RTGS Purpose Codes

| Code | Description | Use Case |
|------|-------------|----------|
| SALA | Salary Payment | Monthly payroll |
| PENS | Pension Payment | Retirement benefits |
| SSBE | Social Security Benefit | Government benefits |
| TBIL | Telephone Bill | Utility payments |
| EBIL | Electricity Bill | Utility payments |
| GBIL | Gas Bill | Utility payments |
| WTER | Water Bill | Utility payments |
| LOAN | Loan | Loan disbursement |
| TRAD | Trade | Commercial payments |
| SPLS | Supplier Payment | Vendor payments |
| SAVG | Savings | Internal transfers |
| CASH | Cash Management | Liquidity management |
| GOVT | Government | Tax, duties |
| INVS | Investment | Investment transactions |
| INTC | Intra Company | Inter-company transfers |

---

## 7. BEFTN Batch Processing

### 7.1 Overview

BEFTN (Bangladesh Electronic Funds Transfer Network) is used for bulk payments including payroll processing, supplier payments, and customer refunds. BEFTN operates in batch cycles with T+1 settlement.

### 7.2 File Format Specifications

#### 7.2.1 BEFTN File Structure

BEFTN uses fixed-width text files with the following structure:

| Record Type | Position | Length | Description |
|-------------|----------|--------|-------------|
| Header Record | 1 | 1 | 'H' for Header |
| | 2-5 | 4 | File Identifier |
| | 6-13 | 8 | Processing Date (YYYYMMDD) |
| | 14-21 | 8 | Sending Bank Code |
| | 22-39 | 18 | Sending Account Number |
| | 40-47 | 8 | Total Debit Amount |
| | 48-55 | 8 | Total Credit Count |
| | 56-150 | 95 | Filler |

| Detail Record | 1 | 1 | 'D' for Detail |
| | 2-5 | 4 | Sequence Number |
| | 6-23 | 18 | Receiver Account Number |
| | 24-31 | 8 | Receiver Bank Code |
| | 32-39 | 8 | Receiver Branch Code |
| | 40-54 | 15 | Transaction Amount |
| | 55 | 1 | Transaction Type ('C' or 'D') |
| | 56-105 | 50 | Receiver Name |
| | 106-155 | 50 | Sender Reference |
| | 156-205 | 50 | Sender to Receiver Info |

| Trailer Record | 1 | 1 | 'T' for Trailer |
| | 2-9 | 8 | Total Records |
| | 10-27 | 18 | Total Debit Amount |
| | 28-35 | 8 | Total Debit Count |
| | 36-53 | 18 | Total Credit Amount |
| | 54-61 | 8 | Total Credit Count |
| | 62-150 | 89 | Filler |

### 7.3 BEFTN Batch Implementation

```python
# services/beftn_service.py
import os
import logging
from datetime import datetime, timedelta
from decimal import Decimal
from typing import List, Dict, Optional
from dataclasses import dataclass
from enum import Enum
import uuid


class TransactionType(Enum):
    CREDIT = "C"
    DEBIT = "D"


@dataclass
class BEFTNTransaction:
    sequence_number: int
    receiver_account: str
    receiver_bank_code: str
    receiver_branch_code: str
    receiver_name: str
    amount: Decimal
    transaction_type: TransactionType
    sender_reference: str
    sender_to_receiver_info: str = ""


@dataclass
class BEFTNBatch:
    batch_id: str
    sending_bank_code: str
    sending_account: str
    processing_date: datetime
    transactions: List[BEFTNTransaction]
    total_debit_amount: Decimal = Decimal('0')
    total_credit_amount: Decimal = Decimal('0')
    total_debit_count: int = 0
    total_credit_count: int = 0
    
    def __post_init__(self):
        self._calculate_totals()
    
    def _calculate_totals(self):
        """Calculate batch totals."""
        self.total_debit_amount = Decimal('0')
        self.total_credit_amount = Decimal('0')
        self.total_debit_count = 0
        self.total_credit_count = 0
        
        for txn in self.transactions:
            if txn.transaction_type == TransactionType.DEBIT:
                self.total_debit_amount += txn.amount
                self.total_debit_count += 1
            else:
                self.total_credit_amount += txn.amount
                self.total_credit_count += 1


class BEFTNFileGenerator:
    """
    Generator for BEFTN batch files.
    Creates fixed-width files conforming to Bangladesh Bank BEFTN specifications.
    """
    
    RECORD_LENGTH = 150
    
    def __init__(self, output_directory: str = "output/beftn"):
        self.output_directory = output_directory
        self.logger = logging.getLogger(__name__)
        os.makedirs(output_directory, exist_ok=True)
    
    def generate_file(self, batch: BEFTNBatch) -> str:
        """
        Generate a BEFTN batch file.
        
        Args:
            batch: BEFTNBatch containing transactions
            
        Returns:
            Path to the generated file
        """
        filename = self._generate_filename(batch)
        filepath = os.path.join(self.output_directory, filename)
        
        with open(filepath, 'w', encoding='utf-8') as f:
            # Write header
            f.write(self._create_header_record(batch) + '\n')
            
            # Write detail records
            for txn in batch.transactions:
                f.write(self._create_detail_record(txn) + '\n')
            
            # Write trailer
            f.write(self._create_trailer_record(batch) + '\n')
        
        self.logger.info(f"Generated BEFTN file: {filepath}")
        return filepath
    
    def _generate_filename(self, batch: BEFTNBatch) -> str:
        """Generate filename for BEFTN batch file."""
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        return f"BEFTN_{batch.sending_bank_code}_{batch.batch_id}_{timestamp}.txt"
    
    def _create_header_record(self, batch: BEFTNBatch) -> str:
        """Create BEFTN header record."""
        record = []
        record.append('H')  # Record type
        record.append(batch.batch_id[:4].ljust(4))  # File identifier
        record.append(batch.processing_date.strftime('%Y%m%d'))  # Processing date
        record.append(batch.sending_bank_code.ljust(8))  # Sending bank code
        record.append(batch.sending_account.ljust(18))  # Sending account
        record.append(str(int(batch.total_debit_amount * 100)).zfill(8))  # Total debit
        record.append(str(batch.total_credit_count).zfill(8))  # Credit count
        record.append('' .ljust(95))  # Filler
        
        return ''.join(record)[:self.RECORD_LENGTH]
    
    def _create_detail_record(self, txn: BEFTNTransaction) -> str:
        """Create BEFTN detail record."""
        record = []
        record.append('D')  # Record type
        record.append(str(txn.sequence_number).zfill(4))  # Sequence number
        record.append(txn.receiver_account.ljust(18))  # Receiver account
        record.append(txn.receiver_bank_code.ljust(8))  # Receiver bank code
        record.append(txn.receiver_branch_code.ljust(8))  # Receiver branch code
        record.append(str(int(txn.amount * 100)).zfill(15))  # Amount
        record.append(txn.transaction_type.value)  # Transaction type
        record.append(txn.receiver_name[:50].ljust(50))  # Receiver name
        record.append(txn.sender_reference[:50].ljust(50))  # Sender reference
        record.append(txn.sender_to_receiver_info[:50].ljust(50))  # Additional info
        
        result = ''.join(record)
        return result[:self.RECORD_LENGTH]
    
    def _create_trailer_record(self, batch: BEFTNBatch) -> str:
        """Create BEFTN trailer record."""
        record = []
        record.append('T')  # Record type
        record.append(str(len(batch.transactions) + 2).zfill(8))  # Total records
        record.append(str(int(batch.total_debit_amount * 100)).zfill(18))  # Total debit amount
        record.append(str(batch.total_debit_count).zfill(8))  # Total debit count
        record.append(str(int(batch.total_credit_amount * 100)).zfill(18))  # Total credit amount
        record.append(str(batch.total_credit_count).zjust(8))  # Total credit count
        record.append(''.ljust(89))  # Filler
        
        return ''.join(record)[:self.RECORD_LENGTH]


class BEFTNService:
    """
    Service for managing BEFTN batch processing operations.
    Handles payroll, supplier payments, and refunds through BEFTN network.
    """
    
    # BEFTN transaction limits
    MIN_AMOUNT = Decimal('1')
    MAX_AMOUNT = Decimal('500000')
    
    # Batch cut-off times
    BATCH_CUTOFFS = [10, 14, 16]  # 10 AM, 2 PM, 4 PM
    
    def __init__(self, bank_config, file_generator: BEFTNFileGenerator):
        self.config = bank_config
        self.file_generator = file_generator
        self.logger = logging.getLogger(__name__)
    
    def create_payroll_batch(
        self,
        employees: List[Dict],
        payroll_date: datetime,
        sender_account: str
    ) -> BEFTNBatch:
        """
        Create a BEFTN batch for payroll processing.
        
        Args:
            employees: List of employee payment details
            payroll_date: Date for payroll processing
            sender_account: Company account for debiting
            
        Returns:
            BEFTNBatch ready for processing
        """
        transactions = []
        
        for idx, employee in enumerate(employees, start=1):
            txn = BEFTNTransaction(
                sequence_number=idx,
                receiver_account=employee['account_number'],
                receiver_bank_code=employee['bank_code'],
                receiver_branch_code=employee.get('branch_code', '00000'),
                receiver_name=employee['name'][:50],
                amount=Decimal(str(employee['net_salary'])),
                transaction_type=TransactionType.CREDIT,
                sender_reference=f"SALARY-{payroll_date.strftime('%Y%m')}",
                sender_to_receiver_info=f"Salary for {payroll_date.strftime('%B %Y')}"
            )
            
            # Validate amount
            if self.MIN_AMOUNT <= txn.amount <= self.MAX_AMOUNT:
                transactions.append(txn)
            else:
                self.logger.warning(
                    f"Skipping employee {employee['name']}: Amount {txn.amount} out of range"
                )
        
        batch = BEFTNBatch(
            batch_id=f"PAYROLL-{uuid.uuid4().hex[:8].upper()}",
            sending_bank_code=self.config.bank_code,
            sending_account=sender_account,
            processing_date=payroll_date,
            transactions=transactions
        )
        
        return batch
    
    def create_supplier_payment_batch(
        self,
        invoices: List[Dict],
        payment_date: datetime,
        sender_account: str
    ) -> BEFTNBatch:
        """
        Create a BEFTN batch for supplier payments.
        
        Args:
            invoices: List of supplier invoice details
            payment_date: Date for payment processing
            sender_account: Company account for debiting
            
        Returns:
            BEFTNBatch ready for processing
        """
        transactions = []
        
        for idx, invoice in enumerate(invoices, start=1):
            txn = BEFTNTransaction(
                sequence_number=idx,
                receiver_account=invoice['supplier_account'],
                receiver_bank_code=invoice['supplier_bank_code'],
                receiver_branch_code=invoice.get('supplier_branch_code', '00000'),
                receiver_name=invoice['supplier_name'][:50],
                amount=Decimal(str(invoice['amount'])),
                transaction_type=TransactionType.CREDIT,
                sender_reference=f"INV-{invoice['invoice_number']}",
                sender_to_receiver_info=invoice.get('description', 'Payment')[:50]
            )
            
            if self.MIN_AMOUNT <= txn.amount <= self.MAX_AMOUNT:
                transactions.append(txn)
            else:
                self.logger.warning(
                    f"Invoice {invoice['invoice_number']} amount {txn.amount} out of BEFTN range"
                )
        
        batch = BEFTNBatch(
            batch_id=f"SUPPLIER-{uuid.uuid4().hex[:8].upper()}",
            sending_bank_code=self.config.bank_code,
            sending_account=sender_account,
            processing_date=payment_date,
            transactions=transactions
        )
        
        return batch
    
    def create_refund_batch(
        self,
        refunds: List[Dict],
        refund_date: datetime,
        sender_account: str
    ) -> BEFTNBatch:
        """
        Create a BEFTN batch for customer refunds.
        
        Args:
            refunds: List of refund details
            refund_date: Date for refund processing
            sender_account: Company account for debiting
            
        Returns:
            BEFTNBatch ready for processing
        """
        transactions = []
        
        for idx, refund in enumerate(refunds, start=1):
            txn = BEFTNTransaction(
                sequence_number=idx,
                receiver_account=refund['customer_account'],
                receiver_bank_code=refund['customer_bank_code'],
                receiver_branch_code=refund.get('customer_branch_code', '00000'),
                receiver_name=refund['customer_name'][:50],
                amount=Decimal(str(refund['amount'])),
                transaction_type=TransactionType.CREDIT,
                sender_reference=f"REF-{refund['order_id']}",
                sender_to_receiver_info=f"Refund for Order {refund['order_id']}"[:50]
            )
            
            if self.MIN_AMOUNT <= txn.amount <= self.MAX_AMOUNT:
                transactions.append(txn)
        
        batch = BEFTNBatch(
            batch_id=f"REFUND-{uuid.uuid4().hex[:8].upper()}",
            sending_bank_code=self.config.bank_code,
            sending_account=sender_account,
            processing_date=refund_date,
            transactions=transactions
        )
        
        return batch
    
    def submit_batch(self, batch: BEFTNBatch) -> Dict:
        """
        Submit BEFTN batch to bank.
        
        Args:
            batch: BEFTNBatch to submit
            
        Returns:
            Submission result with tracking information
        """
        # Generate file
        filepath = self.file_generator.generate_file(batch)
        
        # Upload to bank SFTP/API
        result = self._upload_to_bank(filepath, batch)
        
        return {
            'batch_id': batch.batch_id,
            'file_path': filepath,
            'transaction_count': len(batch.transactions),
            'total_amount': batch.total_credit_amount,
            'status': 'SUBMITTED',
            'bank_reference': result.get('reference'),
            'estimated_settlement': self._calculate_settlement_time()
        }
    
    def _upload_to_bank(self, filepath: str, batch: BEFTNBatch) -> Dict:
        """Upload BEFTN file to bank."""
        # Implementation depends on bank's preferred method
        # Options: SFTP, API upload, or manual upload portal
        import paramiko
        
        try:
            transport = paramiko.Transport((self.config.sftp_host, self.config.sftp_port))
            transport.connect(
                username=self.config.sftp_username,
                password=self.config.sftp_password,
                pkey=paramiko.RSAKey.from_private_key_file(self.config.sftp_key_file)
            )
            
            sftp = paramiko.SFTPClient.from_transport(transport)
            remote_path = f"{self.config.sftp_remote_path}/{os.path.basename(filepath)}"
            
            sftp.put(filepath, remote_path)
            sftp.close()
            transport.close()
            
            return {'reference': f"SFTP-{batch.batch_id}", 'status': 'UPLOADED'}
            
        except Exception as e:
            self.logger.error(f"SFTP upload failed: {str(e)}")
            raise BEFTNSubmissionError(f"Failed to upload batch: {str(e)}")
    
    def _calculate_settlement_time(self) -> datetime:
        """Calculate estimated settlement time based on current time."""
        now = datetime.now()
        
        for cutoff in self.BATCH_CUTOFFS:
            if now.hour < cutoff:
                return now.replace(hour=cutoff + 2, minute=0, second=0)
        
        # Next day
        next_day = now + timedelta(days=1)
        return next_day.replace(hour=self.BATCH_CUTOFFS[0] + 2, minute=0, second=0)
    
    def get_next_batch_time(self) -> datetime:
        """Get the next available batch cut-off time."""
        now = datetime.now()
        
        for cutoff in self.BATCH_CUTOFFS:
            cutoff_time = now.replace(hour=cutoff, minute=0, second=0)
            if now < cutoff_time:
                return cutoff_time
        
        # Tomorrow's first batch
        return (now + timedelta(days=1)).replace(hour=self.BATCH_CUTOFFS[0], minute=0, second=0)


class BEFTNSubmissionError(Exception):
    pass


class BEFTNValidationError(Exception):
    pass
```

### 7.4 BEFTN Bank Codes

```python
# config/beftn_bank_codes.py
BEFTN_BANK_CODES = {
    # Commercial Banks
    "110": "Dutch-Bangla Bank Ltd.",
    "115": "BRAC Bank Ltd.",
    "120": "Pubali Bank Ltd.",
    "125": "Islami Bank Bangladesh Ltd.",
    "135": "The City Bank Ltd.",
    "140": "United Commercial Bank Ltd.",
    "145": "IFIC Bank Ltd.",
    "150": "AB Bank Ltd.",
    "160": "Eastern Bank Ltd.",
    "165": "Social Islami Bank Ltd.",
    "170": "Mutual Trust Bank Ltd.",
    "175": "Mercantile Bank Ltd.",
    "180": "Prime Bank Ltd.",
    "185": "NRB Bank Ltd.",
    "190": "Standard Bank Ltd.",
    "195": "Southeast Bank Ltd.",
    "200": "One Bank Ltd.",
    "205": "Bank Asia Ltd.",
    "210": "Trust Bank Ltd.",
    "215": "NRB Commercial Bank Ltd.",
    "220": "Shahjalal Islami Bank Ltd.",
    
    # State-owned Banks
    "200": "Sonali Bank Ltd.",
    "210": "Janata Bank Ltd.",
    "220": "Agrani Bank Ltd.",
    "230": "Rupali Bank Ltd.",
    "240": "Bangladesh Krishi Bank",
    "250": "Rajshahi Krishi Unnayan Bank",
    "260": "Probashi Kallyan Bank",
    
    # Foreign Banks
    "300": "Standard Chartered Bank",
    "310": "HSBC Ltd.",
    "320": "Citibank N.A.",
    "330": "Commercial Bank of Ceylon",
    "340": "State Bank of India",
    "350": "Woori Bank",
    "360": "Bank Alfalah",
}
```



---

## 8. Security Requirements

### 8.1 Authentication Mechanisms

#### 8.1.1 Certificate-Based Authentication

All bank integrations must use mutual TLS (mTLS) authentication:

| Component | Requirement |
|-----------|-------------|
| Protocol | TLS 1.2 or higher |
| Certificate Type | X.509 v3 |
| Key Algorithm | RSA 2048-bit minimum |
| Hash Algorithm | SHA-256 |
| Certificate Validity | Maximum 2 years |
| Renewal Period | 30 days before expiry |

#### 8.1.2 Certificate Management

```python
# security/certificate_manager.py
import os
import logging
from datetime import datetime, timedelta
from cryptography import x509
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.asymmetric import rsa, padding
from cryptography.x509.oid import NameOID


class CertificateManager:
    """
    Manages SSL/TLS certificates for bank API authentication.
    Handles generation, renewal, and validation of certificates.
    """
    
    def __init__(self, cert_directory: str = "certs"):
        self.cert_directory = cert_directory
        self.logger = logging.getLogger(__name__)
        os.makedirs(cert_directory, exist_ok=True)
    
    def generate_csr(
        self,
        organization: str,
        common_name: str,
        country: str = "BD",
        state: str = "Dhaka",
        locality: str = "Dhaka",
        key_size: int = 2048
    ) -> tuple:
        """
        Generate Certificate Signing Request (CSR) for bank registration.
        
        Args:
            organization: Company name
            common_name: Domain or identifier
            country: Country code
            state: State/Province
            locality: City
            key_size: RSA key size
            
        Returns:
            Tuple of (private_key_pem, csr_pem)
        """
        # Generate private key
        private_key = rsa.generate_private_key(
            public_exponent=65537,
            key_size=key_size
        )
        
        # Build subject
        subject = x509.Name([
            x509.NameAttribute(NameOID.COUNTRY_NAME, country),
            x509.NameAttribute(NameOID.STATE_OR_PROVINCE_NAME, state),
            x509.NameAttribute(NameOID.LOCALITY_NAME, locality),
            x509.NameAttribute(NameOID.ORGANIZATION_NAME, organization),
            x509.NameAttribute(NameOID.COMMON_NAME, common_name),
        ])
        
        # Build CSR
        csr = x509.CertificateSigningRequestBuilder()
        csr = csr.subject_name(subject)
        
        # Add Subject Alternative Name for API endpoints
        csr = csr.add_extension(
            x509.SubjectAlternativeName([
                x509.DNSName(common_name),
                x509.DNSName(f"api.{common_name}"),
            ]),
            critical=False
        )
        
        # Sign CSR
        csr = csr.sign(private_key, hashes.SHA256())
        
        # Serialize
        private_key_pem = private_key.private_bytes(
            encoding=serialization.Encoding.PEM,
            format=serialization.PrivateFormat.TraditionalOpenSSL,
            encryption_algorithm=serialization.NoEncryption()
        )
        
        csr_pem = csr.public_bytes(serialization.Encoding.PEM)
        
        return private_key_pem, csr_pem
    
    def save_certificate(
        self,
        cert_name: str,
        certificate_pem: bytes,
        private_key_pem: bytes,
        chain_pem: bytes = None
    ):
        """Save certificate, key, and chain to files."""
        cert_path = os.path.join(self.cert_directory, f"{cert_name}.crt")
        key_path = os.path.join(self.cert_directory, f"{cert_name}.key")
        chain_path = os.path.join(self.cert_directory, f"{cert_name}_chain.crt")
        
        with open(cert_path, 'wb') as f:
            f.write(certificate_pem)
        
        with open(key_path, 'wb') as f:
            f.write(private_key_pem)
        
        if chain_pem:
            with open(chain_path, 'wb') as f:
                f.write(chain_pem)
        
        self.logger.info(f"Certificate saved: {cert_name}")
    
    def validate_certificate(self, cert_path: str) -> dict:
        """
        Validate certificate and return status information.
        
        Returns:
            Dictionary with validation results
        """
        with open(cert_path, 'rb') as f:
            cert_data = f.read()
        
        cert = x509.load_pem_x509_certificate(cert_data)
        
        # Check expiry
        now = datetime.now()
        days_until_expiry = (cert.not_valid_after - now).days
        
        return {
            'valid': now >= cert.not_valid_before and now <= cert.not_valid_after,
            'subject': cert.subject.rfc4514_string(),
            'issuer': cert.issuer.rfc4514_string(),
            'serial_number': str(cert.serial_number),
            'not_valid_before': cert.not_valid_before.isoformat(),
            'not_valid_after': cert.not_valid_after.isoformat(),
            'days_until_expiry': days_until_expiry,
            'needs_renewal': days_until_expiry <= 30
        }
    
    def check_all_certificates(self) -> list:
        """Check all certificates in the directory."""
        results = []
        
        for filename in os.listdir(self.cert_directory):
            if filename.endswith('.crt') and not filename.endswith('_chain.crt'):
                cert_path = os.path.join(self.cert_directory, filename)
                try:
                    status = self.validate_certificate(cert_path)
                    status['filename'] = filename
                    results.append(status)
                except Exception as e:
                    self.logger.error(f"Failed to validate {filename}: {e}")
        
        return results


class SecureStorage:
    """Secure storage for sensitive configuration data."""
    
    def __init__(self, encryption_key: bytes):
        from cryptography.fernet import Fernet
        self.cipher = Fernet(encryption_key)
    
    def encrypt_value(self, value: str) -> bytes:
        """Encrypt a string value."""
        return self.cipher.encrypt(value.encode())
    
    def decrypt_value(self, encrypted_value: bytes) -> str:
        """Decrypt an encrypted value."""
        return self.cipher.decrypt(encrypted_value).decode()
    
    def store_credentials(self, key: str, credentials: dict, storage_path: str = "secrets.enc"):
        """Store encrypted credentials."""
        import json
        
        encrypted = self.encrypt_value(json.dumps(credentials))
        
        with open(storage_path, 'ab') as f:
            f.write(f"{key}:{encrypted.decode()}\n".encode())
    
    def load_credentials(self, key: str, storage_path: str = "secrets.enc") -> dict:
        """Load encrypted credentials."""
        import json
        
        with open(storage_path, 'rb') as f:
            for line in f:
                line = line.decode().strip()
                if line.startswith(f"{key}:"):
                    encrypted = line[len(key)+1:].encode()
                    return json.loads(self.decrypt_value(encrypted))
        
        raise KeyError(f"Credentials not found for key: {key}")
```

### 8.2 Encryption Standards

#### 8.2.1 Data Encryption

| Layer | Algorithm | Key Size | Mode |
|-------|-----------|----------|------|
| Transport | TLS 1.3 | - | AEAD |
| At Rest | AES-256 | 256-bit | GCM |
| Key Exchange | ECDHE | P-256 | - |
| Hashing | SHA-256 | - | - |

#### 8.2.2 API Request Encryption

```python
# security/encryption.py
from cryptography.hazmat.primitives.ciphers.aead import AESGCM
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2
import os
import base64
import json


class RequestEncryption:
    """
    Handles encryption of sensitive API request data.
    Uses AES-256-GCM for authenticated encryption.
    """
    
    def __init__(self, master_key: bytes):
        self.master_key = master_key
    
    def derive_key(self, salt: bytes, context: str) -> bytes:
        """Derive encryption key from master key."""
        kdf = PBKDF2(
            algorithm=hashes.SHA256(),
            length=32,
            salt=salt,
            iterations=100000
        )
        return kdf.derive(self.master_key + context.encode())
    
    def encrypt_payload(self, payload: dict, context: str = "api") -> str:
        """
        Encrypt API payload.
        
        Args:
            payload: Dictionary to encrypt
            context: Encryption context
            
        Returns:
            Base64 encoded encrypted data
        """
        # Generate salt and nonce
        salt = os.urandom(16)
        nonce = os.urandom(12)
        
        # Derive key
        key = self.derive_key(salt, context)
        
        # Encrypt
        aesgcm = AESGCM(key)
        plaintext = json.dumps(payload).encode()
        ciphertext = aesgcm.encrypt(nonce, plaintext, None)
        
        # Combine components
        encrypted_package = salt + nonce + ciphertext
        
        return base64.b64encode(encrypted_package).decode()
    
    def decrypt_payload(self, encrypted_data: str, context: str = "api") -> dict:
        """Decrypt API payload."""
        data = base64.b64decode(encrypted_data)
        
        # Extract components
        salt = data[:16]
        nonce = data[16:28]
        ciphertext = data[28:]
        
        # Derive key
        key = self.derive_key(salt, context)
        
        # Decrypt
        aesgcm = AESGCM(key)
        plaintext = aesgcm.decrypt(nonce, ciphertext, None)
        
        return json.loads(plaintext.decode())
```

### 8.3 API Security

#### 8.3.1 HMAC Signature

```python
# security/hmac_auth.py
import hmac
import hashlib
import base64
from datetime import datetime
from typing import Dict


class HMACAuthenticator:
    """
    Handles HMAC-SHA256 authentication for API requests.
    Provides request signing and verification.
    """
    
    def __init__(self, api_secret: str):
        self.api_secret = api_secret.encode()
    
    def generate_signature(
        self,
        method: str,
        path: str,
        timestamp: str,
        body: str = ""
    ) -> str:
        """
        Generate HMAC signature for request.
        
        Args:
            method: HTTP method (GET, POST, etc.)
            path: API endpoint path
            timestamp: ISO format timestamp
            body: Request body (for POST/PUT)
            
        Returns:
            Base64 encoded HMAC signature
        """
        # Build canonical string
        canonical_string = f"{method}\n{path}\n{timestamp}\n{hashlib.sha256(body.encode()).hexdigest()}"
        
        # Generate HMAC
        signature = hmac.new(
            self.api_secret,
            canonical_string.encode(),
            hashlib.sha256
        ).digest()
        
        return base64.b64encode(signature).decode()
    
    def verify_signature(
        self,
        signature: str,
        method: str,
        path: str,
        timestamp: str,
        body: str = ""
    ) -> bool:
        """Verify request signature."""
        expected = self.generate_signature(method, path, timestamp, body)
        return hmac.compare_digest(signature, expected)
    
    def create_auth_headers(
        self,
        method: str,
        path: str,
        body: str = ""
    ) -> Dict[str, str]:
        """Create authentication headers for request."""
        timestamp = datetime.utcnow().isoformat()
        signature = self.generate_signature(method, path, timestamp, body)
        
        return {
            'X-Timestamp': timestamp,
            'X-Signature': signature,
            'X-Signature-Method': 'HMAC-SHA256'
        }
```

### 8.4 Security Monitoring

```python
# security/security_monitor.py
import logging
from datetime import datetime
from typing import Dict, List
from collections import defaultdict
import time


class SecurityMonitor:
    """
    Monitors API security events and detects anomalies.
    Tracks request patterns and alerts on suspicious activity.
    """
    
    def __init__(self):
        self.logger = logging.getLogger(__name__)
        self.request_log = defaultdict(list)
        self.failed_auth_attempts = defaultdict(int)
        self.blocked_ips = set()
    
    def log_request(
        self,
        endpoint: str,
        ip_address: str,
        status_code: int,
        response_time: float
    ):
        """Log API request for monitoring."""
        self.request_log[ip_address].append({
            'timestamp': datetime.now(),
            'endpoint': endpoint,
            'status_code': status_code,
            'response_time': response_time
        })
        
        # Check for rate limiting
        self._check_rate_limit(ip_address)
        
        # Check for anomalies
        self._detect_anomalies(ip_address)
    
    def log_auth_failure(self, ip_address: str, reason: str):
        """Log authentication failure."""
        self.failed_auth_attempts[ip_address] += 1
        
        if self.failed_auth_attempts[ip_address] >= 5:
            self._block_ip(ip_address, "Multiple authentication failures")
    
    def _check_rate_limit(self, ip_address: str, max_requests: int = 100, window: int = 60):
        """Check if IP exceeds rate limit."""
        now = time.time()
        recent_requests = [
            req for req in self.request_log[ip_address]
            if (now - req['timestamp'].timestamp()) < window
        ]
        
        if len(recent_requests) > max_requests:
            self._block_ip(ip_address, "Rate limit exceeded")
    
    def _detect_anomalies(self, ip_address: str):
        """Detect anomalous request patterns."""
        requests = self.request_log[ip_address]
        
        if len(requests) < 10:
            return
        
        # Check for high error rate
        recent_errors = sum(1 for req in requests[-10:] if req['status_code'] >= 400)
        if recent_errors >= 7:  # 70% error rate
            self.logger.warning(f"High error rate detected from {ip_address}")
    
    def _block_ip(self, ip_address: str, reason: str):
        """Block IP address."""
        if ip_address not in self.blocked_ips:
            self.blocked_ips.add(ip_address)
            self.logger.warning(f"Blocked IP {ip_address}: {reason}")
    
    def is_blocked(self, ip_address: str) -> bool:
        """Check if IP is blocked."""
        return ip_address in self.blocked_ips
    
    def get_security_report(self) -> Dict:
        """Generate security monitoring report."""
        return {
            'blocked_ips': list(self.blocked_ips),
            'total_requests': sum(len(reqs) for reqs in self.request_log.values()),
            'failed_auth_attempts': dict(self.failed_auth_attempts),
            'generated_at': datetime.now().isoformat()
        }
```

---

## 9. Implementation

### 9.1 Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        Smart Dairy Banking Module                        │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                      API Gateway Layer                           │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │    │
│  │  │   Rate       │  │   Auth       │  │    Load Balancer     │  │    │
│  │  │   Limiter    │  │   Filter     │  │                      │  │    │
│  │  └──────────────┘  └──────────────┘  └──────────────────────┘  │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                   │                                      │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                    Business Logic Layer                          │    │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │    │
│  │  │   Payment    │  │Reconciliation│  │   Reporting Service  │  │    │
│  │  │   Service    │  │   Service    │  │                      │  │    │
│  │  └──────────────┘  └──────────────┘  └──────────────────────┘  │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                   │                                      │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │                    Integration Adapters                          │    │
│  │  ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐  ┌──────────┐  │    │
│  │  │  DBBL  │  │  BRAC  │  │  CITY  │  │  IBBL  │  │  Generic │  │    │
│  │  │Adapter │  │Adapter │  │Adapter │  │Adapter │  │  Adapter │  │    │
│  │  └────────┘  └────────┘  └────────┘  └────────┘  └──────────┘  │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                   │                                      │
└───────────────────────────────────┼──────────────────────────────────────┘
                                    │
        ┌───────────────────────────┼───────────────────────────┐
        │                           │                           │
   ┌────┴────┐               ┌────┴────┐               ┌────┴────┐
   │  DBBL   │               │  BRAC   │               │  City   │
   │  Bank   │               │  Bank   │               │  Bank   │
   └─────────┘               └─────────┘               └─────────┘
```

### 9.2 Configuration Management

```python
# config/bank_config.py
from dataclasses import dataclass
from typing import Optional
import os
from enum import Enum


class BankType(Enum):
    DBBL = "dbbl"
    BRAC = "brac"
    CITY = "city"
    IBBL = "ibbl"


@dataclass
class BankConfig:
    """Configuration for bank API integration."""
    
    # Bank identification
    bank_type: BankType
    bank_code: str
    bank_bic: str
    company_name: str
    
    # API endpoints
    base_url: str
    auth_url: str
    npsb_base_url: Optional[str] = None
    rtgs_base_url: Optional[str] = None
    
    # Authentication
    client_id: str
    client_secret: str
    access_token: Optional[str] = None
    token_expiry: Optional[str] = None
    npsb_token: Optional[str] = None
    rtgs_token: Optional[str] = None
    npsb_secret: Optional[str] = None
    institution_id: Optional[str] = None
    terminal_id: Optional[str] = None
    
    # Certificate paths
    client_cert: str
    client_key: str
    ca_cert: Optional[str] = None
    signing_key_path: Optional[str] = None
    signing_key_password: Optional[str] = None
    
    # SFTP settings for file-based transactions
    sftp_host: Optional[str] = None
    sftp_port: int = 22
    sftp_username: Optional[str] = None
    sftp_password: Optional[str] = None
    sftp_key_file: Optional[str] = None
    sftp_remote_path: str = "/uploads"
    
    @classmethod
    def from_environment(cls, bank_type: BankType) -> 'BankConfig':
        """Load configuration from environment variables."""
        prefix = f"BANK_{bank_type.value.upper()}_"
        
        return cls(
            bank_type=bank_type,
            bank_code=os.getenv(f"{prefix}CODE"),
            bank_bic=os.getenv(f"{prefix}BIC"),
            company_name=os.getenv("COMPANY_NAME", "Smart Dairy Ltd."),
            base_url=os.getenv(f"{prefix}BASE_URL"),
            auth_url=os.getenv(f"{prefix}AUTH_URL"),
            npsb_base_url=os.getenv(f"{prefix}NPSB_URL"),
            rtgs_base_url=os.getenv(f"{prefix}RTGS_URL"),
            client_id=os.getenv(f"{prefix}CLIENT_ID"),
            client_secret=os.getenv(f"{prefix}CLIENT_SECRET"),
            client_cert=os.getenv(f"{prefix}CLIENT_CERT", f"certs/{bank_type.value}_client.crt"),
            client_key=os.getenv(f"{prefix}CLIENT_KEY", f"certs/{bank_type.value}_client.key"),
            ca_cert=os.getenv(f"{prefix}CA_CERT"),
            signing_key_path=os.getenv(f"{prefix}SIGNING_KEY"),
            signing_key_password=os.getenv(f"{prefix}SIGNING_KEY_PASS"),
            sftp_host=os.getenv(f"{prefix}SFTP_HOST"),
            sftp_username=os.getenv(f"{prefix}SFTP_USER"),
            sftp_key_file=os.getenv(f"{prefix}SFTP_KEY"),
        )


# Database configuration for transaction storage
@dataclass
class DatabaseConfig:
    host: str = os.getenv("DB_HOST", "localhost")
    port: int = int(os.getenv("DB_PORT", "5432"))
    database: str = os.getenv("DB_NAME", "smart_dairy_banking")
    username: str = os.getenv("DB_USER", "banking_user")
    password: str = os.getenv("DB_PASSWORD", "")
    
    @property
    def connection_string(self) -> str:
        return f"postgresql://{self.username}:{self.password}@{self.host}:{self.port}/{self.database}"
```

### 9.3 Factory Pattern for Bank Adapters

```python
# adapters/bank_adapter_factory.py
from typing import Dict, Type
from config.bank_config import BankConfig, BankType
from adapters.base_adapter import BaseBankAdapter
from adapters.dbbl_adapter import DBBLAdapter
from adapters.brac_adapter import BRACAdapter
from adapters.city_adapter import CityAdapter
from adapters.ibbl_adapter import IBBLAdapter


class BankAdapterFactory:
    """
    Factory for creating bank-specific adapters.
    Implements the Factory pattern for bank integration.
    """
    
    _adapters: Dict[BankType, Type[BaseBankAdapter]] = {
        BankType.DBBL: DBBLAdapter,
        BankType.BRAC: BRACAdapter,
        BankType.CITY: CityAdapter,
        BankType.IBBL: IBBLAdapter,
    }
    
    @classmethod
    def get_adapter(cls, bank_type: BankType, config: BankConfig) -> BaseBankAdapter:
        """
        Get appropriate adapter for bank type.
        
        Args:
            bank_type: Type of bank
            config: Bank configuration
            
        Returns:
            Configured bank adapter instance
            
        Raises:
            ValueError: If bank type is not supported
        """
        adapter_class = cls._adapters.get(bank_type)
        
        if not adapter_class:
            raise ValueError(f"No adapter available for bank type: {bank_type}")
        
        return adapter_class(config)
    
    @classmethod
    def register_adapter(
        cls,
        bank_type: BankType,
        adapter_class: Type[BaseBankAdapter]
    ):
        """Register a new bank adapter."""
        cls._adapters[bank_type] = adapter_class
    
    @classmethod
    def list_supported_banks(cls) -> list:
        """List all supported bank types."""
        return list(cls._adapters.keys())


# Base adapter interface
from abc import ABC, abstractmethod
from models.balance import BalanceRequest, BalanceResponse
from models.transfer import FundTransferRequest, FundTransferResponse


class BaseBankAdapter(ABC):
    """Abstract base class for bank adapters."""
    
    def __init__(self, config: BankConfig):
        self.config = config
    
    @abstractmethod
    def get_balance(self, request: BalanceRequest) -> BalanceResponse:
        """Get account balance."""
        pass
    
    @abstractmethod
    def transfer_funds(self, request: FundTransferRequest) -> FundTransferResponse:
        """Execute fund transfer."""
        pass
    
    @abstractmethod
    def get_transaction_status(self, transaction_id: str) -> dict:
        """Get transaction status."""
        pass
    
    @abstractmethod
    def validate_credentials(self) -> bool:
        """Validate API credentials."""
        pass
```

### 9.4 Database Schema

```sql
-- Database schema for banking module
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Banks table
CREATE TABLE banks (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    bank_code VARCHAR(10) UNIQUE NOT NULL,
    bank_name VARCHAR(100) NOT NULL,
    bank_type VARCHAR(20) NOT NULL,
    bank_bic VARCHAR(11),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Accounts table
CREATE TABLE bank_accounts (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    bank_id UUID REFERENCES banks(id),
    account_number VARCHAR(50) NOT NULL,
    account_name VARCHAR(100) NOT NULL,
    account_type VARCHAR(20) NOT NULL, -- CURRENT, SAVINGS, OVERDRAFT
    currency VARCHAR(3) DEFAULT 'BDT',
    is_primary BOOLEAN DEFAULT false,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    UNIQUE(bank_id, account_number)
);

-- Transactions table
CREATE TABLE bank_transactions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    request_id VARCHAR(100) UNIQUE NOT NULL,
    transaction_id VARCHAR(100),
    bank_reference VARCHAR(100),
    account_id UUID REFERENCES bank_accounts(id),
    transaction_type VARCHAR(20) NOT NULL, -- CREDIT, DEBIT
    transfer_type VARCHAR(20) NOT NULL, -- INTERNAL, RTGS, BEFTN, NPSB
    amount DECIMAL(18, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'BDT',
    status VARCHAR(20) NOT NULL, -- PENDING, PROCESSING, COMPLETED, FAILED, CANCELLED
    counterparty_account VARCHAR(50),
    counterparty_bank_code VARCHAR(10),
    counterparty_name VARCHAR(100),
    reference VARCHAR(100),
    description TEXT,
    failure_reason TEXT,
    settled_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- BEFTN batches table
CREATE TABLE beftn_batches (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    batch_id VARCHAR(50) UNIQUE NOT NULL,
    batch_type VARCHAR(20) NOT NULL, -- PAYROLL, SUPPLIER, REFUND
    account_id UUID REFERENCES bank_accounts(id),
    file_path VARCHAR(500),
    total_transactions INTEGER NOT NULL DEFAULT 0,
    total_amount DECIMAL(18, 2) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL, -- PENDING, SUBMITTED, PROCESSING, COMPLETED, FAILED
    submitted_at TIMESTAMP WITH TIME ZONE,
    processed_at TIMESTAMP WITH TIME ZONE,
    bank_reference VARCHAR(100),
    error_message TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Reconciliation records
CREATE TABLE reconciliation_records (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    account_id UUID REFERENCES bank_accounts(id),
    statement_date DATE NOT NULL,
    opening_balance DECIMAL(18, 2) NOT NULL,
    closing_balance DECIMAL(18, 2) NOT NULL,
    total_credits DECIMAL(18, 2) NOT NULL DEFAULT 0,
    total_debits DECIMAL(18, 2) NOT NULL DEFAULT 0,
    matched_count INTEGER NOT NULL DEFAULT 0,
    unmatched_count INTEGER NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL, -- PENDING, IN_PROGRESS, COMPLETED, EXCEPTIONS
    processed_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Exception records
CREATE TABLE reconciliation_exceptions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    reconciliation_id UUID REFERENCES reconciliation_records(id),
    transaction_id UUID REFERENCES bank_transactions(id),
    exception_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(18, 2),
    is_resolved BOOLEAN DEFAULT false,
    resolution_notes TEXT,
    resolved_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Indexes
CREATE INDEX idx_transactions_request_id ON bank_transactions(request_id);
CREATE INDEX idx_transactions_status ON bank_transactions(status);
CREATE INDEX idx_transactions_created_at ON bank_transactions(created_at);
CREATE INDEX idx_transactions_account_date ON bank_transactions(account_id, created_at);
CREATE INDEX idx_beftn_batches_status ON beftn_batches(status);
CREATE INDEX idx_reconciliation_account_date ON reconciliation_records(account_id, statement_date);

-- Triggers for updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_banks_updated_at BEFORE UPDATE ON banks
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_bank_accounts_updated_at BEFORE UPDATE ON bank_accounts
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_bank_transactions_updated_at BEFORE UPDATE ON bank_transactions
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
```

---

## 10. Reconciliation

### 10.1 Overview

Reconciliation ensures that transactions recorded in Smart Dairy's system match the bank statements. Automated reconciliation matches transactions based on reference numbers, amounts, and dates.

### 10.2 Reconciliation Engine

```python
# services/reconciliation_service.py
import logging
from datetime import datetime, timedelta
from decimal import Decimal
from typing import List, Dict, Optional, Tuple
from dataclasses import dataclass
from enum import Enum
import uuid

from models.transfer import Transaction, TransactionType
from services.statement_service import StatementService


class ReconciliationStatus(Enum):
    PENDING = "PENDING"
    IN_PROGRESS = "IN_PROGRESS"
    COMPLETED = "COMPLETED"
    EXCEPTIONS_FOUND = "EXCEPTIONS_FOUND"


class ExceptionType(Enum):
    MISSING_IN_SYSTEM = "MISSING_IN_SYSTEM"
    MISSING_IN_STATEMENT = "MISSING_IN_STATEMENT"
    AMOUNT_MISMATCH = "AMOUNT_MISMATCH"
    DUPLICATE_TRANSACTION = "DUPLICATE_TRANSACTION"
    DATE_MISMATCH = "DATE_MISMATCH"


@dataclass
class ReconciliationMatch:
    system_transaction: Dict
    statement_transaction: Transaction
    match_score: float
    match_reason: str


@dataclass
class ReconciliationException:
    exception_id: str
    exception_type: ExceptionType
    description: str
    system_transaction: Optional[Dict] = None
    statement_transaction: Optional[Transaction] = None
    amount_difference: Optional[Decimal] = None


@dataclass
class ReconciliationResult:
    account_id: str
    statement_date: datetime
    opening_balance: Decimal
    closing_balance: Decimal
    total_system_credits: Decimal
    total_system_debits: Decimal
    total_statement_credits: Decimal
    total_statement_debits: Decimal
    matched_count: int
    unmatched_count: int
    exceptions: List[ReconciliationException]
    status: ReconciliationStatus


class ReconciliationEngine:
    """
    Automated reconciliation engine for matching bank transactions
    with internal system records.
    """
    
    # Tolerance for amount matching (0.01 BDT)
    AMOUNT_TOLERANCE = Decimal('0.01')
    
    # Date tolerance for matching (1 day)
    DATE_TOLERANCE_DAYS = 1
    
    def __init__(
        self,
        statement_service: StatementService,
        transaction_repository,
        exception_repository
    ):
        self.statement_service = statement_service
        self.transaction_repo = transaction_repository
        self.exception_repo = exception_repository
        self.logger = logging.getLogger(__name__)
    
    def reconcile_account(
        self,
        account_id: str,
        statement_date: datetime
    ) -> ReconciliationResult:
        """
        Perform reconciliation for an account on a specific date.
        
        Args:
            account_id: Account to reconcile
            statement_date: Date of the statement
            
        Returns:
            ReconciliationResult with matching status and exceptions
        """
        self.logger.info(f"Starting reconciliation for account {account_id} on {statement_date}")
        
        # Get bank statement
        statement_transactions = self._get_statement_transactions(account_id, statement_date)
        
        # Get system transactions
        system_transactions = self._get_system_transactions(account_id, statement_date)
        
        # Calculate opening balance
        opening_balance = self._get_opening_balance(account_id, statement_date)
        
        # Match transactions
        matches, unmatched_system, unmatched_statement = self._match_transactions(
            system_transactions,
            statement_transactions
        )
        
        # Identify exceptions
        exceptions = self._identify_exceptions(
            unmatched_system,
            unmatched_statement,
            matches
        )
        
        # Calculate totals
        result = self._build_reconciliation_result(
            account_id,
            statement_date,
            opening_balance,
            statement_transactions,
            system_transactions,
            matches,
            exceptions
        )
        
        # Save results
        self._save_reconciliation_result(result)
        
        self.logger.info(
            f"Reconciliation complete: {result.matched_count} matched, "
            f"{result.unmatched_count} unmatched, {len(exceptions)} exceptions"
        )
        
        return result
    
    def _get_statement_transactions(
        self,
        account_id: str,
        date: datetime
    ) -> List[Transaction]:
        """Retrieve statement transactions from bank."""
        from services.statement_service import StatementRequest
        
        request = StatementRequest(
            account_number=account_id,
            start_date=date,
            end_date=date
        )
        
        return self.statement_service.get_statement(request)
    
    def _get_system_transactions(
        self,
        account_id: str,
        date: datetime
    ) -> List[Dict]:
        """Retrieve transactions from internal system."""
        return self.transaction_repo.get_by_account_and_date(account_id, date)
    
    def _get_opening_balance(self, account_id: str, date: datetime) -> Decimal:
        """Get opening balance for the date."""
        # Get balance from previous day's closing
        prev_date = date - timedelta(days=1)
        prev_reconciliation = self.transaction_repo.get_reconciliation(account_id, prev_date)
        
        if prev_reconciliation:
            return prev_reconciliation['closing_balance']
        
        # Fallback to current balance
        return self.transaction_repo.get_balance_as_of_date(account_id, date)
    
    def _match_transactions(
        self,
        system_transactions: List[Dict],
        statement_transactions: List[Transaction]
    ) -> Tuple[List[ReconciliationMatch], List[Dict], List[Transaction]]:
        """
        Match system transactions with statement transactions.
        
        Matching criteria (in order of priority):
        1. Exact reference number match
        2. Amount + Date match
        3. Amount + Reference partial match
        """
        matches = []
        unmatched_system = system_transactions.copy()
        unmatched_statement = statement_transactions.copy()
        
        # Pass 1: Exact reference match
        for sys_txn in system_transactions[:]:
            for stmt_txn in statement_transactions[:]:
                if self._is_exact_reference_match(sys_txn, stmt_txn):
                    matches.append(ReconciliationMatch(
                        system_transaction=sys_txn,
                        statement_transaction=stmt_txn,
                        match_score=1.0,
                        match_reason="Exact reference match"
                    ))
                    unmatched_system.remove(sys_txn)
                    unmatched_statement.remove(stmt_txn)
                    break
        
        # Pass 2: Amount and date match
        for sys_txn in unmatched_system[:]:
            for stmt_txn in unmatched_statement[:]:
                if self._is_amount_date_match(sys_txn, stmt_txn):
                    matches.append(ReconciliationMatch(
                        system_transaction=sys_txn,
                        statement_transaction=stmt_txn,
                        match_score=0.9,
                        match_reason="Amount and date match"
                    ))
                    unmatched_system.remove(sys_txn)
                    unmatched_statement.remove(stmt_txn)
                    break
        
        # Pass 3: Fuzzy reference match
        for sys_txn in unmatched_system[:]:
            for stmt_txn in unmatched_statement[:]:
                if self._is_fuzzy_reference_match(sys_txn, stmt_txn):
                    matches.append(ReconciliationMatch(
                        system_transaction=sys_txn,
                        statement_transaction=stmt_txn,
                        match_score=0.8,
                        match_reason="Fuzzy reference match"
                    ))
                    unmatched_system.remove(sys_txn)
                    unmatched_statement.remove(stmt_txn)
                    break
        
        return matches, unmatched_system, unmatched_statement
    
    def _is_exact_reference_match(self, sys_txn: Dict, stmt_txn: Transaction) -> bool:
        """Check for exact reference number match."""
        sys_ref = sys_txn.get('reference', '') or sys_txn.get('bank_reference', '')
        stmt_ref = stmt_txn.reference or stmt_txn.bank_reference or ''
        
        if not sys_ref or not stmt_ref:
            return False
        
        return sys_ref.strip().upper() == stmt_ref.strip().upper()
    
    def _is_amount_date_match(self, sys_txn: Dict, stmt_txn: Transaction) -> bool:
        """Check for amount and date match within tolerance."""
        # Amount match
        sys_amount = Decimal(str(sys_txn.get('amount', 0)))
        stmt_amount = stmt_txn.amount
        
        amount_match = abs(sys_amount - stmt_amount) <= self.AMOUNT_TOLERANCE
        
        # Date match
        sys_date = sys_txn.get('created_at')
        if isinstance(sys_date, str):
            sys_date = datetime.fromisoformat(sys_date.replace('Z', '+00:00'))
        
        date_diff = abs((sys_date.date() - stmt_txn.date.date()).days)
        date_match = date_diff <= self.DATE_TOLERANCE_DAYS
        
        # Type match
        sys_type = sys_txn.get('transaction_type', '').upper()
        stmt_type = stmt_txn.type.value
        type_match = sys_type == stmt_type
        
        return amount_match and date_match and type_match
    
    def _is_fuzzy_reference_match(self, sys_txn: Dict, stmt_txn: Transaction) -> bool:
        """Check for partial reference match."""
        sys_ref = (sys_txn.get('reference', '') or '').upper()
        stmt_ref = (stmt_txn.reference or '').upper()
        
        if not sys_ref or not stmt_ref:
            return False
        
        # Check if one contains the other
        if sys_ref in stmt_ref or stmt_ref in sys_ref:
            return self._is_amount_date_match(sys_txn, stmt_txn)
        
        return False
    
    def _identify_exceptions(
        self,
        unmatched_system: List[Dict],
        unmatched_statement: List[Transaction],
        matches: List[ReconciliationMatch]
    ) -> List[ReconciliationException]:
        """Identify and categorize reconciliation exceptions."""
        exceptions = []
        
        # Missing in system (present in statement but not in system)
        for stmt_txn in unmatched_statement:
            exceptions.append(ReconciliationException(
                exception_id=str(uuid.uuid4()),
                exception_type=ExceptionType.MISSING_IN_SYSTEM,
                description=f"Transaction found in statement but not in system",
                statement_transaction=stmt_txn,
                amount_difference=stmt_txn.amount
            ))
        
        # Missing in statement (present in system but not in statement)
        for sys_txn in unmatched_system:
            exceptions.append(ReconciliationException(
                exception_id=str(uuid.uuid4()),
                exception_type=ExceptionType.MISSING_IN_STATEMENT,
                description=f"Transaction found in system but not in statement",
                system_transaction=sys_txn,
                amount_difference=Decimal(str(sys_txn.get('amount', 0)))
            ))
        
        # Check for duplicates
        seen_refs = {}
        for match in matches:
            ref = match.system_transaction.get('reference')
            if ref in seen_refs:
                exceptions.append(ReconciliationException(
                    exception_id=str(uuid.uuid4()),
                    exception_type=ExceptionType.DUPLICATE_TRANSACTION,
                    description=f"Duplicate transaction reference: {ref}",
                    system_transaction=match.system_transaction,
                    statement_transaction=match.statement_transaction
                ))
            else:
                seen_refs[ref] = match
        
        return exceptions
    
    def _build_reconciliation_result(
        self,
        account_id: str,
        statement_date: datetime,
        opening_balance: Decimal,
        statement_transactions: List[Transaction],
        system_transactions: List[Dict],
        matches: List[ReconciliationMatch],
        exceptions: List[ReconciliationException]
    ) -> ReconciliationResult:
        """Build final reconciliation result."""
        # Calculate totals from statement
        stmt_credits = sum(
            t.amount for t in statement_transactions
            if t.type == TransactionType.CREDIT
        )
        stmt_debits = sum(
            t.amount for t in statement_transactions
            if t.type == TransactionType.DEBIT
        )
        
        # Calculate totals from system
        sys_credits = sum(
            Decimal(str(t.get('amount', 0)))
            for t in system_transactions
            if t.get('transaction_type') == 'CREDIT'
        )
        sys_debits = sum(
            Decimal(str(t.get('amount', 0)))
            for t in system_transactions
            if t.get('transaction_type') == 'DEBIT'
        )
        
        # Calculate closing balance
        closing_balance = opening_balance + stmt_credits - stmt_debits
        
        # Determine status
        status = (
            ReconciliationStatus.EXCEPTIONS_FOUND
            if exceptions
            else ReconciliationStatus.COMPLETED
        )
        
        return ReconciliationResult(
            account_id=account_id,
            statement_date=statement_date,
            opening_balance=opening_balance,
            closing_balance=closing_balance,
            total_system_credits=sys_credits,
            total_system_debits=sys_debits,
            total_statement_credits=stmt_credits,
            total_statement_debits=stmt_debits,
            matched_count=len(matches),
            unmatched_count=len(exceptions),
            exceptions=exceptions,
            status=status
        )
    
    def _save_reconciliation_result(self, result: ReconciliationResult):
        """Save reconciliation result to database."""
        self.transaction_repo.save_reconciliation(result)
        
        # Save exceptions
        for exception in result.exceptions:
            self.exception_repo.save_exception(exception)
    
    def resolve_exception(
        self,
        exception_id: str,
        resolution_notes: str,
        adjustment_amount: Optional[Decimal] = None
    ):
        """Mark an exception as resolved with notes."""
        self.exception_repo.resolve_exception(
            exception_id,
            resolution_notes,
            adjustment_amount
        )
    
    def generate_reconciliation_report(
        self,
        start_date: datetime,
        end_date: datetime
    ) -> Dict:
        """Generate reconciliation summary report."""
        return self.transaction_repo.get_reconciliation_summary(start_date, end_date)


class ReconciliationScheduler:
    """
    Scheduler for automated reconciliation tasks.
    """
    
    def __init__(self, reconciliation_engine: ReconciliationEngine):
        self.engine = reconciliation_engine
        self.logger = logging.getLogger(__name__)
    
    def schedule_daily_reconciliation(self, account_ids: List[str]):
        """Schedule daily reconciliation for specified accounts."""
        from apscheduler.schedulers.background import BackgroundScheduler
        
        scheduler = BackgroundScheduler()
        
        for account_id in account_ids:
            scheduler.add_job(
                self._run_reconciliation,
                'cron',
                hour=6,  # 6 AM daily
                minute=0,
                args=[account_id]
            )
        
        scheduler.start()
        self.logger.info(f"Scheduled daily reconciliation for {len(account_ids)} accounts")
    
    def _run_reconciliation(self, account_id: str):
        """Execute reconciliation for previous day."""
        yesterday = datetime.now() - timedelta(days=1)
        
        try:
            result = self.engine.reconcile_account(account_id, yesterday)
            self.logger.info(
                f"Daily reconciliation complete for {account_id}: "
                f"{result.status.value}"
            )
        except Exception as e:
            self.logger.error(f"Reconciliation failed for {account_id}: {e}")
```

---

## 11. Compliance

### 11.1 Bangladesh Bank Regulations

#### 11.1.1 Regulatory Requirements

| Regulation | Requirement | Implementation |
|------------|-------------|----------------|
| BB Circular 2023/12 | Transaction reporting | Daily transaction logs |
| BB Circular 2022/08 | RTGS compliance | ISO 20022 messaging |
| BB Circular 2021/15 | BEFTN batch limits | Max 10,000 transactions per file |
| BB Circular 2023/05 | NPSB security | TLS 1.2+, certificate pinning |

#### 11.1.2 Reporting Requirements

```python
# compliance/bb_reporting.py
from datetime import datetime, date
from typing import List, Dict
from decimal import Decimal


class BangladeshBankReporting:
    """
    Generates regulatory reports for Bangladesh Bank.
    """
    
    def generate_daily_transaction_report(
        self,
        report_date: date,
        transactions: List[Dict]
    ) -> Dict:
        """
        Generate daily transaction summary report for BB.
        
        Required fields:
        - Total transaction count and value
        - Breakdown by transaction type
        - Breakdown by channel (RTGS/BEFTN/NPSB)
        """
        report = {
            'report_date': report_date.isoformat(),
            'generated_at': datetime.now().isoformat(),
            'summary': {
                'total_transactions': len(transactions),
                'total_value': sum(Decimal(str(t['amount'])) for t in transactions),
            },
            'by_channel': {},
            'by_type': {}
        }
        
        # Channel breakdown
        channels = ['RTGS', 'BEFTN', 'NPSB', 'INTERNAL']
        for channel in channels:
            channel_txns = [t for t in transactions if t.get('channel') == channel]
            report['by_channel'][channel] = {
                'count': len(channel_txns),
                'value': sum(Decimal(str(t['amount'])) for t in channel_txns)
            }
        
        # Type breakdown
        types = ['CREDIT', 'DEBIT']
        for txn_type in types:
            type_txns = [t for t in transactions if t.get('type') == txn_type]
            report['by_type'][txn_type] = {
                'count': len(type_txns),
                'value': sum(Decimal(str(t['amount'])) for t in type_txns)
            }
        
        return report
    
    def generate_large_transaction_report(
        self,
        report_date: date,
        threshold: Decimal = Decimal('1000000')  # 10 Lakh
    ) -> List[Dict]:
        """
        Generate report of transactions exceeding threshold.
        Required for transactions above BDT 10 Lakhs.
        """
        large_transactions = self._fetch_large_transactions(report_date, threshold)
        
        return [
            {
                'transaction_id': t['id'],
                'date': t['created_at'],
                'amount': t['amount'],
                'currency': t['currency'],
                'type': t['type'],
                'counterparty': t['counterparty_name'],
                'purpose': t.get('purpose_code', 'SPLS'),
                'channel': t['channel']
            }
            for t in large_transactions
        ]
    
    def _fetch_large_transactions(
        self,
        date: date,
        threshold: Decimal
    ) -> List[Dict]:
        """Fetch transactions exceeding threshold from database."""
        # Implementation would query database
        pass


class RegulatoryComplianceManager:
    """
    Manages regulatory compliance requirements.
    """
    
    # BEFTN limits per BB guidelines
    BEFTN_MAX_TRANSACTIONS_PER_FILE = 10000
    BEFTN_MAX_DAILY_TRANSACTIONS = 50000
    
    # RTGS minimum as per BB
    RTGS_MINIMUM_AMOUNT = Decimal('100000')
    
    def validate_beftn_batch(self, transaction_count: int) -> bool:
        """Validate BEFTN batch against regulatory limits."""
        return transaction_count <= self.BEFTN_MAX_TRANSACTIONS_PER_FILE
    
    def validate_rtgs_amount(self, amount: Decimal) -> tuple[bool, str]:
        """Validate RTGS amount against BB requirements."""
        if amount < self.RTGS_MINIMUM_AMOUNT:
            return False, f"RTGS amount below BB minimum of BDT {self.RTGS_MINIMUM_AMOUNT}"
        return True, "Valid"
```

### 11.2 AML (Anti-Money Laundering)

#### 11.2.1 AML Screening

```python
# compliance/aml_screening.py
from typing import List, Dict, Set
from decimal import Decimal
from datetime import datetime, timedelta


class AMLEngine:
    """
    Anti-Money Laundering screening engine.
    Monitors transactions for suspicious patterns.
    """
    
    # Thresholds for AML monitoring
    SINGLE_TRANSACTION_THRESHOLD = Decimal('1000000')  # 10 Lakhs
    DAILY_AGGREGATE_THRESHOLD = Decimal('5000000')    # 50 Lakhs
    VELOCITY_THRESHOLD = 10  # Transactions per day
    
    def __init__(self, sanctions_list_client):
        self.sanctions_client = sanctions_list_client
        self.alert_history = []
    
    def screen_transaction(self, transaction: Dict) -> Dict:
        """
        Screen a transaction for AML compliance.
        
        Returns:
            Screening result with risk score and alerts
        """
        alerts = []
        risk_score = 0
        
        # 1. Amount screening
        amount_check = self._check_amount_thresholds(transaction)
        if amount_check['alert']:
            alerts.append(amount_check)
            risk_score += 20
        
        # 2. Velocity screening
        velocity_check = self._check_velocity(transaction)
        if velocity_check['alert']:
            alerts.append(velocity_check)
            risk_score += 15
        
        # 3. Counterparty screening
        counterparty_check = self._screen_counterparty(transaction)
        if counterparty_check['alert']:
            alerts.append(counterparty_check)
            risk_score += 50  # High risk for sanctions matches
        
        # 4. Pattern screening
        pattern_check = self._check_patterns(transaction)
        if pattern_check['alert']:
            alerts.append(pattern_check)
            risk_score += 25
        
        return {
            'transaction_id': transaction['id'],
            'risk_score': min(risk_score, 100),
            'risk_level': self._get_risk_level(risk_score),
            'alerts': alerts,
            'requires_review': risk_score >= 30,
            'screened_at': datetime.now().isoformat()
        }
    
    def _check_amount_thresholds(self, transaction: Dict) -> Dict:
        """Check transaction amount against AML thresholds."""
        amount = Decimal(str(transaction['amount']))
        
        if amount >= self.SINGLE_TRANSACTION_THRESHOLD:
            return {
                'alert': True,
                'type': 'LARGE_TRANSACTION',
                'severity': 'MEDIUM',
                'message': f"Transaction amount {amount} exceeds threshold",
                'details': {
                    'threshold': str(self.SINGLE_TRANSACTION_THRESHOLD),
                    'actual_amount': str(amount)
                }
            }
        
        return {'alert': False}
    
    def _check_velocity(self, transaction: Dict) -> Dict:
        """Check transaction velocity for account."""
        account_id = transaction['account_id']
        
        # Get count of transactions in last 24 hours
        recent_count = self._get_recent_transaction_count(account_id, hours=24)
        
        if recent_count > self.VELOCITY_THRESHOLD:
            return {
                'alert': True,
                'type': 'VELOCITY_EXCEEDED',
                'severity': 'HIGH',
                'message': f"Transaction velocity exceeded: {recent_count} in 24 hours",
                'details': {
                    'threshold': self.VELOCITY_THRESHOLD,
                    'actual_count': recent_count
                }
            }
        
        return {'alert': False}
    
    def _screen_counterparty(self, transaction: Dict) -> Dict:
        """Screen counterparty against sanctions lists."""
        counterparty_name = transaction.get('counterparty_name', '')
        
        # Check against sanctions lists
        matches = self.sanctions_client.search(counterparty_name)
        
        if matches:
            return {
                'alert': True,
                'type': 'SANCTIONS_MATCH',
                'severity': 'CRITICAL',
                'message': f"Potential sanctions list match: {counterparty_name}",
                'details': {
                    'matches': matches
                }
            }
        
        return {'alert': False}
    
    def _check_patterns(self, transaction: Dict) -> Dict:
        """Check for suspicious transaction patterns."""
        # Round amount check
        amount = Decimal(str(transaction['amount']))
        if amount % 100000 == 0:  # Round lakhs
            return {
                'alert': True,
                'type': 'ROUND_AMOUNT',
                'severity': 'LOW',
                'message': "Transaction amount is a round figure",
                'details': {'amount': str(amount)}
            }
        
        return {'alert': False}
    
    def _get_risk_level(self, score: int) -> str:
        """Convert risk score to risk level."""
        if score >= 70:
            return 'HIGH'
        elif score >= 30:
            return 'MEDIUM'
        else:
            return 'LOW'
    
    def _get_recent_transaction_count(self, account_id: str, hours: int) -> int:
        """Get number of recent transactions for account."""
        # Implementation would query database
        pass


class SuspiciousActivityReporter:
    """
    Handles reporting of suspicious transactions to Bangladesh Bank.
    """
    
    def __init__(self):
        self.reports = []
    
    def create_suspicious_activity_report(self, alert: Dict) -> str:
        """
        Create Suspicious Activity Report (SAR) for Bangladesh Bank.
        
        Returns:
            Report ID
        """
        report_id = f"SAR-{datetime.now().strftime('%Y%m%d')}-{len(self.reports) + 1}"
        
        report = {
            'report_id': report_id,
            'report_type': 'SAR',
            'alert_type': alert['type'],
            'transaction_id': alert['transaction_id'],
            'created_at': datetime.now().isoformat(),
            'status': 'DRAFT',
            'details': alert
        }
        
        self.reports.append(report)
        return report_id
    
    def submit_report(self, report_id: str) -> bool:
        """Submit SAR to Bangladesh Bank."""
        # Implementation would integrate with BB reporting API
        pass
```

---



## 12. Testing

### 12.1 Test Strategy

#### 12.1.1 Test Levels

| Level | Description | Tools |
|-------|-------------|-------|
| Unit Tests | Individual component testing | pytest |
| Integration Tests | API integration testing | pytest, requests-mock |
| End-to-End Tests | Full workflow testing | pytest, Playwright |
| Security Tests | Penetration testing, vulnerability scanning | OWASP ZAP, Burp Suite |
| Performance Tests | Load and stress testing | Locust, JMeter |

### 12.2 Sandbox Environments

#### 12.2.1 Available Sandboxes

| Bank | Sandbox URL | Features |
|------|-------------|----------|
| DBBL | https://sandbox.api.dbbl.com.bd | Full API simulation |
| BRAC Bank | https://sandbox.bracbank.com | Full API simulation |
| City Bank | https://sandbox.thecitybank.com | Full API simulation |

#### 12.2.2 Sandbox Configuration

```python
# config/sandbox_config.py
SANDBOX_CONFIG = {
    "dbbl": {
        "base_url": "https://sandbox.api.dbbl.com.bd/corporate/v2",
        "auth_url": "https://sandbox.api.dbbl.com.bd/oauth/token",
        "client_id": "sandbox_client_id",
        "client_secret": "sandbox_client_secret",
        "webhook_url": "https://sandbox.smartdairy.com/webhooks/dbbl",
    },
    "brac": {
        "base_url": "https://sandbox.bracbank.com/corporate/v1",
        "auth_url": "https://sandbox.bracbank.com/auth/token",
        "api_key": "sandbox_api_key",
        "webhook_url": "https://sandbox.smartdairy.com/webhooks/brac",
    },
    "city": {
        "base_url": "https://sandbox.thecitybank.com/corporate/v3",
        "auth_url": "https://sandbox.thecitybank.com/oauth2/token",
        "client_id": "sandbox_client_id",
        "client_secret": "sandbox_client_secret",
        "webhook_url": "https://sandbox.smartdairy.com/webhooks/city",
    }
}

# Test accounts for sandbox
SANDBOX_ACCOUNTS = {
    "source_account": {
        "account_number": "1101234567890",
        "account_type": "CURRENT",
        "balance": 10000000.00,
        "currency": "BDT"
    },
    "destination_accounts": [
        {
            "account_number": "1109876543210",
            "bank_code": "110",
            "bank_name": "DBBL",
            "account_type": "SAVINGS"
        },
        {
            "account_number": "1151234567890",
            "bank_code": "115",
            "bank_name": "BRAC Bank",
            "account_type": "CURRENT"
        },
        {
            "account_number": "1351234567890",
            "bank_code": "135",
            "bank_name": "City Bank",
            "account_type": "CURRENT"
        }
    ]
}
```

### 12.3 Test Cases

#### 12.3.1 Unit Tests

```python
# tests/unit/test_balance_service.py
import pytest
from unittest.mock import Mock, patch
from decimal import Decimal
from datetime import datetime

from services.balance_service import BalanceService, BankAPIException
from models.balance import BalanceRequest, BalanceResponse, AccountType


class TestBalanceService:
    """Unit tests for BalanceService."""
    
    @pytest.fixture
    def mock_config(self):
        config = Mock()
        config.base_url = "https://test.bank.com/api"
        config.client_id = "test_client"
        config.client_cert = "test.crt"
        config.client_key = "test.key"
        config.access_token = "test_token"
        return config
    
    @pytest.fixture
    def balance_service(self, mock_config):
        return BalanceService(mock_config)
    
    def test_get_balance_success(self, balance_service, mock_config):
        """Test successful balance retrieval."""
        # Arrange
        mock_response = Mock()
        mock_response.status_code = 200
        mock_response.json.return_value = {
            'available_balance': '1000000.00',
            'ledger_balance': '1000000.00',
            'currency': 'BDT',
            'account_type': 'CURRENT',
            'timestamp': datetime.now().isoformat(),
            'request_id': 'test-request-123',
            'status': 'SUCCESS',
            'bank_reference': 'BANK-REF-001'
        }
        
        with patch('requests.Session.get', return_value=mock_response):
            request = BalanceRequest(
                account_number='1101234567890',
                request_id='test-request-123'
            )
            
            # Act
            response = balance_service.get_balance(request)
            
            # Assert
            assert response.available_balance == Decimal('1000000.00')
            assert response.account_type == AccountType.CURRENT
            assert response.status == 'SUCCESS'
    
    def test_get_balance_api_error(self, balance_service):
        """Test balance retrieval with API error."""
        # Arrange
        mock_response = Mock()
        mock_response.status_code = 500
        mock_response.raise_for_status.side_effect = Exception("Internal Server Error")
        
        with patch('requests.Session.get', return_value=mock_response):
            request = BalanceRequest(account_number='1101234567890')
            
            # Act & Assert
            with pytest.raises(BankAPIException):
                balance_service.get_balance(request)
    
    def test_get_balance_invalid_account(self, balance_service):
        """Test balance retrieval with invalid account."""
        # Arrange
        mock_response = Mock()
        mock_response.status_code = 404
        mock_response.raise_for_status.side_effect = Exception("Account not found")
        
        with patch('requests.Session.get', return_value=mock_response):
            request = BalanceRequest(account_number='INVALID')
            
            # Act & Assert
            with pytest.raises(BankAPIException):
                balance_service.get_balance(request)


# tests/unit/test_transfer_service.py
import pytest
from unittest.mock import Mock, patch
from decimal import Decimal

from services.transfer_service import FundTransferService, ValidationException
from models.transfer import FundTransferRequest, TransferType


class TestFundTransferService:
    """Unit tests for FundTransferService."""
    
    @pytest.fixture
    def mock_config(self):
        return Mock(
            base_url="https://test.bank.com/api",
            access_token="test_token",
            client_cert="test.crt",
            client_key="test.key"
        )
    
    @pytest.fixture
    def mock_balance_service(self):
        service = Mock()
        service.get_balance.return_value = Mock(
            available_balance=Decimal('5000000.00')
        )
        return service
    
    @pytest.fixture
    def transfer_service(self, mock_config, mock_balance_service):
        return FundTransferService(mock_config, mock_balance_service)
    
    def test_rtgs_below_minimum(self, transfer_service):
        """Test RTGS validation below minimum amount."""
        request = FundTransferRequest(
            source_account='1101234567890',
            destination_account='1109876543210',
            destination_bank_code='110',
            amount=Decimal('50000'),  # Below 100,000
            transfer_type=TransferType.RTGS
        )
        
        with pytest.raises(ValidationException) as exc_info:
            transfer_service.initiate_transfer(request)
        
        assert 'minimum' in str(exc_info.value).lower()
    
    def test_beftn_above_maximum(self, transfer_service):
        """Test BEFTN validation above maximum amount."""
        request = FundTransferRequest(
            source_account='1101234567890',
            destination_account='1109876543210',
            destination_bank_code='110',
            amount=Decimal('600000'),  # Above 500,000
            transfer_type=TransferType.BEFTN
        )
        
        with pytest.raises(ValidationException) as exc_info:
            transfer_service.initiate_transfer(request)
        
        assert 'maximum' in str(exc_info.value).lower()
    
    def test_transfer_insufficient_funds(self, transfer_service, mock_balance_service):
        """Test transfer with insufficient funds."""
        mock_balance_service.get_balance.return_value = Mock(
            available_balance=Decimal('1000.00')
        )
        
        request = FundTransferRequest(
            source_account='1101234567890',
            destination_account='1109876543210',
            destination_bank_code='110',
            amount=Decimal('50000'),
            transfer_type=TransferType.BEFTN
        )
        
        with pytest.raises(Exception) as exc_info:
            transfer_service.initiate_transfer(request)
        
        assert 'insufficient' in str(exc_info.value).lower()
```

#### 12.3.2 Integration Tests

```python
# tests/integration/test_bank_integration.py
import pytest
import os
from decimal import Decimal

from services.bank_integration_factory import BankAdapterFactory
from config.bank_config import BankConfig, BankType


@pytest.mark.integration
class TestBankIntegration:
    """Integration tests for bank API connectivity."""
    
    @pytest.fixture(scope="module")
    def dbbl_config(self):
        """DBBL sandbox configuration."""
        return BankConfig.from_environment(BankType.DBBL)
    
    def test_dbbl_balance_inquiry(self, dbbl_config):
        """Test balance inquiry with DBBL sandbox."""
        # Arrange
        adapter = BankAdapterFactory.get_adapter(BankType.DBBL, dbbl_config)
        
        # Act
        is_valid = adapter.validate_credentials()
        
        # Assert
        assert is_valid is True
    
    def test_dbbl_internal_transfer(self, dbbl_config):
        """Test internal transfer with DBBL sandbox."""
        # Arrange
        adapter = BankAdapterFactory.get_adapter(BankType.DBBL, dbbl_config)
        
        from models.transfer import FundTransferRequest, TransferType
        
        request = FundTransferRequest(
            source_account=os.getenv('TEST_SOURCE_ACCOUNT'),
            destination_account=os.getenv('TEST_DEST_ACCOUNT'),
            destination_bank_code='110',
            amount=Decimal('1000.00'),
            transfer_type=TransferType.INTERNAL,
            reference='TEST-001'
        )
        
        # Act
        response = adapter.transfer_funds(request)
        
        # Assert
        assert response.status.value in ['COMPLETED', 'PROCESSING']
        assert response.transaction_id is not None


# tests/integration/test_beftn_processing.py
import pytest
import tempfile
import os
from datetime import datetime
from decimal import Decimal

from services.beftn_service import BEFTNService, BEFTNFileGenerator, BEFTNBatch
from config.beftn_bank_codes import BEFTN_BANK_CODES


@pytest.mark.integration
class TestBEFTNProcessing:
    """Integration tests for BEFTN batch processing."""
    
    @pytest.fixture
    def beftn_service(self, bank_config):
        generator = BEFTNFileGenerator(output_directory=tempfile.mkdtemp())
        return BEFTNService(bank_config, generator)
    
    def test_generate_payroll_batch(self, beftn_service):
        """Test payroll batch generation."""
        # Arrange
        employees = [
            {
                'name': 'John Doe',
                'account_number': '1109876543210',
                'bank_code': '110',
                'branch_code': '001',
                'net_salary': 50000.00
            },
            {
                'name': 'Jane Smith',
                'account_number': '1151234567890',
                'bank_code': '115',
                'branch_code': '002',
                'net_salary': 75000.00
            }
        ]
        
        # Act
        batch = beftn_service.create_payroll_batch(
            employees=employees,
            payroll_date=datetime.now(),
            sender_account='1101234567890'
        )
        
        # Assert
        assert len(batch.transactions) == 2
        assert batch.total_credit_amount == Decimal('125000.00')
    
    def test_generate_beftn_file(self, beftn_service):
        """Test BEFTN file generation."""
        # Arrange
        batch = BEFTNBatch(
            batch_id="TEST-BATCH-001",
            sending_bank_code="110",
            sending_account="1101234567890",
            processing_date=datetime.now(),
            transactions=[
                Mock(
                    sequence_number=1,
                    receiver_account="1109876543210",
                    receiver_bank_code="110",
                    receiver_branch_code="001",
                    receiver_name="John Doe",
                    amount=Decimal("50000.00"),
                    transaction_type=Mock(value="C"),
                    sender_reference="SALARY-202401",
                    sender_to_receiver_info="January 2024 Salary"
                )
            ]
        )
        
        # Act
        file_path = beftn_service.file_generator.generate_file(batch)
        
        # Assert
        assert os.path.exists(file_path)
        
        with open(file_path, 'r') as f:
            lines = f.readlines()
            assert len(lines) == 3  # Header, Detail, Trailer
            assert lines[0].startswith('H')
            assert lines[1].startswith('D')
            assert lines[2].startswith('T')
```

#### 12.3.3 End-to-End Test Scenarios

```python
# tests/e2e/test_payment_workflows.py
import pytest
from decimal import Decimal


@pytest.mark.e2e
class TestPaymentWorkflows:
    """End-to-end tests for complete payment workflows."""
    
    def test_complete_payroll_workflow(self, payment_service):
        """
        Test complete payroll processing workflow:
        1. Create payroll batch
        2. Generate BEFTN file
        3. Submit to bank
        4. Verify transactions created
        5. Confirm reconciliation
        """
        # Step 1: Create payroll data
        payroll_data = {
            'period': '2024-01',
            'employees': [
                {'id': 'E001', 'name': 'Employee 1', 'net_salary': 50000, 
                 'account': '1109876543210', 'bank': '110'},
                {'id': 'E002', 'name': 'Employee 2', 'net_salary': 60000,
                 'account': '1151234567890', 'bank': '115'},
            ]
        }
        
        # Step 2: Process payroll
        result = payment_service.process_payroll(payroll_data)
        
        # Step 3: Verify batch created
        assert result['batch_id'] is not None
        assert result['transaction_count'] == 2
        
        # Step 4: Verify file generated
        assert result['file_path'] is not None
        
        # Step 5: Verify status tracking
        assert result['status'] == 'SUBMITTED'
    
    def test_supplier_payment_workflow(self, payment_service):
        """
        Test supplier payment workflow:
        1. Select invoices for payment
        2. Determine payment method (RTGS/BEFTN)
        3. Execute payments
        4. Update invoice status
        5. Generate payment report
        """
        # Step 1: Select invoices
        invoices = [
            {'id': 'INV-001', 'amount': 150000, 'supplier': 'Supplier A',
             'account': '1109876543210', 'bank': '110'},  # BEFTN
            {'id': 'INV-002', 'amount': 500000, 'supplier': 'Supplier B',
             'account': '1151234567890', 'bank': '115'},  # BEFTN
            {'id': 'INV-003', 'amount': 2000000, 'supplier': 'Supplier C',
             'account': '1351234567890', 'bank': '135'},  # RTGS
        ]
        
        # Step 2: Process payments
        results = payment_service.process_supplier_payments(invoices)
        
        # Step 3: Verify routing
        assert results['beftn_count'] == 2
        assert results['rtgs_count'] == 1
        
        # Step 4: Verify all processed
        assert results['total_processed'] == 3
    
    def test_refund_processing_workflow(self, payment_service):
        """
        Test customer refund workflow:
        1. Receive refund request
        2. Validate customer bank details
        3. Process NPSB refund
        4. Update order status
        5. Notify customer
        """
        # Step 1: Create refund request
        refund_request = {
            'order_id': 'ORD-001',
            'customer_name': 'Customer Name',
            'amount': 5000,
            'reason': 'Product return',
            'bank_account': '1109876543210',
            'bank_code': '110'
        }
        
        # Step 2: Process refund
        result = payment_service.process_refund(refund_request)
        
        # Step 3: Verify refund
        assert result['status'] == 'COMPLETED'
        assert result['amount'] == Decimal('5000.00')
        assert result['transaction_id'] is not None
```

### 12.4 Test Data

```python
# tests/fixtures/test_data.py
"""Test data for banking integration tests."""

from decimal import Decimal

# Valid test accounts
VALID_ACCOUNTS = {
    'dbbl_current': {
        'account_number': '1101234567890',
        'account_type': 'CURRENT',
        'bank_code': '110',
        'balance': Decimal('10000000.00'),
        'currency': 'BDT',
        'status': 'ACTIVE'
    },
    'dbbl_savings': {
        'account_number': '1109876543210',
        'account_type': 'SAVINGS',
        'bank_code': '110',
        'balance': Decimal('500000.00'),
        'currency': 'BDT',
        'status': 'ACTIVE'
    },
    'brac_current': {
        'account_number': '1151234567890',
        'account_type': 'CURRENT',
        'bank_code': '115',
        'balance': Decimal('2000000.00'),
        'currency': 'BDT',
        'status': 'ACTIVE'
    }
}

# Invalid test accounts
INVALID_ACCOUNTS = {
    'closed': {
        'account_number': '1100000000000',
        'status': 'CLOSED'
    },
    'frozen': {
        'account_number': '1101111111111',
        'status': 'FROZEN'
    },
    'invalid_format': {
        'account_number': 'INVALID-ACCOUNT',
        'status': 'INVALID'
    }
}

# Test transactions
TEST_TRANSACTIONS = {
    'rtgs_large': {
        'amount': Decimal('5000000.00'),
        'transfer_type': 'RTGS',
        'valid': True
    },
    'rtgs_small': {
        'amount': Decimal('50000.00'),
        'transfer_type': 'RTGS',
        'valid': False,
        'error': 'Below minimum'
    },
    'beftn_normal': {
        'amount': Decimal('100000.00'),
        'transfer_type': 'BEFTN',
        'valid': True
    },
    'beftn_large': {
        'amount': Decimal('600000.00'),
        'transfer_type': 'BEFTN',
        'valid': False,
        'error': 'Above maximum'
    },
    'npsb_small': {
        'amount': Decimal('5000.00'),
        'transfer_type': 'NPSB',
        'valid': True
    },
    'npsb_large': {
        'amount': Decimal('250000.00'),
        'transfer_type': 'NPSB',
        'valid': False,
        'error': 'Above maximum'
    }
}

# Test payroll data
TEST_PAYROLL_DATA = {
    'department_it': [
        {'employee_id': 'E001', 'name': 'Software Engineer 1', 'net_salary': 80000},
        {'employee_id': 'E002', 'name': 'Software Engineer 2', 'net_salary': 90000},
        {'employee_id': 'E003', 'name': 'Team Lead', 'net_salary': 150000},
    ],
    'department_hr': [
        {'employee_id': 'E004', 'name': 'HR Manager', 'net_salary': 100000},
        {'employee_id': 'E005', 'name': 'HR Executive', 'net_salary': 50000},
    ]
}
```

---

## 13. Appendices

### Appendix A: API Specifications

#### A.1 Error Codes

| Code | HTTP Status | Description | Resolution |
|------|-------------|-------------|------------|
| AUTH_001 | 401 | Invalid credentials | Check API key and secret |
| AUTH_002 | 401 | Token expired | Refresh access token |
| AUTH_003 | 403 | Insufficient permissions | Verify account permissions |
| VAL_001 | 400 | Invalid account number | Verify account format |
| VAL_002 | 400 | Invalid amount | Check amount is positive and within limits |
| VAL_003 | 400 | Invalid bank code | Verify bank code exists |
| VAL_004 | 400 | Transfer type not supported | Check transfer type for amount |
| BUS_001 | 422 | Insufficient funds | Check account balance |
| BUS_002 | 422 | Account closed | Verify account status |
| BUS_003 | 422 | Account frozen | Contact bank to unfreeze |
| BUS_004 | 422 | Duplicate transaction | Check for duplicate reference |
| SYS_001 | 500 | Internal server error | Retry after some time |
| SYS_002 | 503 | Service unavailable | Bank system maintenance |
| SYS_003 | 504 | Gateway timeout | Retry with exponential backoff |

#### A.2 HTTP Status Codes

| Status Code | Meaning | Usage |
|-------------|---------|-------|
| 200 | OK | Successful GET/PUT |
| 201 | Created | Successful POST |
| 400 | Bad Request | Invalid parameters |
| 401 | Unauthorized | Authentication failed |
| 403 | Forbidden | Authorization failed |
| 404 | Not Found | Resource not found |
| 409 | Conflict | Resource conflict |
| 422 | Unprocessable | Business rule violation |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Error | Server error |
| 503 | Service Unavailable | Maintenance |
| 504 | Gateway Timeout | Timeout |

### Appendix B: Sample Files

#### B.1 BEFTN Sample File

```
HFILE20240131110260013110123456789000000000000000000002                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
D0001110987654321100000001100000010000000000500000CJohn Doe                                              SALARY-202401                                       January 2024 Salary                                 
D0002115123456789011500000200000000000750000CJane Smith                                            SALARY-202401                                       January 2024 Salary                                 
T00000003000000000000000000000000000020000000000001250000                                                                                                                                                                                                                                                                                                                                                                                                                                                          
```

#### B.2 RTGS ISO 20022 Sample Message

```xml
<?xml version="1.0" encoding="UTF-8"?>
<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pacs.008.001.08">
  <FIToFICstmrCdtTrf>
    <GrpHdr>
      <MsgId>PAYMENT-20240131-001</MsgId>
      <CreDtTm>2024-01-31T10:30:00</CreDtTm>
      <NbOfTxs>1</NbOfTxs>
      <SttlmInf>CLRG</SttlmInf>
    </GrpHdr>
    <CdtTrfTxInf>
      <PmtId>
        <InstrId>REQ-20240131-001</InstrId>
        <EndToEndId>INV-SUPPLIER-001</EndToEndId>
      </PmtId>
      <PmtTpInf>
        <SvcLvl>
          <Cd>URGP</Cd>
        </SvcLvl>
      </PmtTpInf>
      <SttlmInf>
        <SttlmMtd>INDA</SttlmMtd>
      </SttlmInf>
      <IntrBkSttlmAmt Ccy="BDT">2000000.00</IntrBkSttlmAmt>
      <IntrBkSttlmDt>2024-01-31</IntrBkSttlmDt>
      <ChrgBr>SLEV</ChrgBr>
      <InstgAgt>
        <FinInstnId>
          <BICFI>DBBLBDDHXXX</BICFI>
        </FinInstnId>
      </InstgAgt>
      <InstdAgt>
        <FinInstnId>
          <BICFI>CIBLBDDHXXX</BICFI>
        </FinInstnId>
      </InstdAgt>
      <Dbtr>
        <Nm>Smart Dairy Ltd.</Nm>
        <Id>
          <Othr>
            <Id>1101234567890</Id>
          </Othr>
        </Id>
      </Dbtr>
      <Cdtr>
        <Nm>Supplier Company Ltd.</Nm>
        <PstlAdr>Dhaka, Bangladesh</PstlAdr>
        <Id>
          <Othr>
            <Id>1359876543210</Id>
          </Othr>
        </Id>
      </Cdtr>
      <RmtInf>
        <Ustrd>Payment for Invoice INV-001</Ustrd>
      </RmtInf>
    </CdtTrfTxInf>
  </FIToFICstmrCdtTrf>
</Document>
```

#### B.3 NPSB ISO 8583 Sample Message

```
MTI: 0200
Field 002: 1101234567890123      (Primary Account Number)
Field 003: 400000                 (Processing Code - Credit)
Field 004: 0000000500000          (Amount: BDT 50,000.00)
Field 011: 123456                 (System Trace Audit Number)
Field 012: 103000                 (Local Transaction Time)
Field 013: 0131                   (Local Transaction Date)
Field 032: 110260                 (Acquiring Institution ID)
Field 035: 1109876543210          (Destination Account)
Field 037: 123456789012           (Retrieval Reference Number)
Field 041: TERM001                (Terminal ID)
Field 043: SMART DAIRY LTD  DHAKA       BGD
Field 048: BEN:John Doe                          
Field 049: 050                     (Currency: BDT)
Field 100: 110                     (Receiving Institution: DBBL)
```

### Appendix C: Environment Variables

```bash
# Bank API Configuration
BANK_DBBL_BASE_URL=https://api.dbbl.com.bd/corporate/v2
BANK_DBBL_CLIENT_ID=your_dbbl_client_id
BANK_DBBL_CLIENT_SECRET=your_dbbl_client_secret
BANK_DBBL_CERT_PATH=/path/to/dbbl_cert.pem
BANK_DBBL_KEY_PATH=/path/to/dbbl_key.pem

BANK_BRAC_BASE_URL=https://api.bracbank.com/corporate/v1
BANK_BRAC_API_KEY=your_brac_api_key
BANK_BRAC_CERT_PATH=/path/to/brac_cert.pem
BANK_BRAC_KEY_PATH=/path/to/brac_key.pem

BANK_CITY_BASE_URL=https://api.thecitybank.com/corporate/v3
BANK_CITY_CLIENT_ID=your_city_client_id
BANK_CITY_CLIENT_SECRET=your_city_client_secret

# Database Configuration
DB_HOST=localhost
DB_PORT=5432
DB_NAME=smart_dairy_banking
DB_USER=banking_user
DB_PASSWORD=your_secure_password

# Security
ENCRYPTION_KEY=your_32_byte_encryption_key
HMAC_SECRET=your_hmac_secret
JWT_SECRET=your_jwt_secret

# SFTP Configuration
SFTP_HOST=bank-sftp.example.com
SFTP_PORT=22
SFTP_USERNAME=your_sftp_user
SFTP_KEY_PATH=/path/to/sftp_key

# Monitoring
SENTRY_DSN=your_sentry_dsn
LOG_LEVEL=INFO
```

### Appendix D: Bank Contact Information

| Bank | Corporate Banking Contact | Technical Support | Emergency Contact |
|------|---------------------------|-------------------|-------------------|
| DBBL | corporate@dbbl.com.bd | +880-2-9551035 | +880-1711-000000 |
| BRAC Bank | corporate@bracbank.com | +880-2-9884848 | +880-1711-111111 |
| City Bank | corporate@thecitybank.com | +880-2-9117306 | +880-1711-222222 |
| Islami Bank | corporate@islamibankbd.com | +880-2-9560312 | +880-1711-333333 |

### Appendix E: Reference Documents

1. Bangladesh Bank Payment Systems Department Guidelines
2. RTGS Operating Procedures - Bangladesh Bank
3. BEFTN User Manual - Bangladesh Bank
4. NPSB Technical Specifications - Bangladesh Bank
5. ISO 20022 Message Guidelines
6. PCI-DSS Compliance Requirements
7. Bangladesh Bank AML/CFT Guidelines

### Appendix F: Glossary

| Term | Definition |
|------|------------|
| BEFTN | Bangladesh Electronic Funds Transfer Network |
| RTGS | Real-Time Gross Settlement |
| NPSB | National Payment Switch Bangladesh |
| mTLS | Mutual Transport Layer Security |
| HMAC | Hash-based Message Authentication Code |
| ISO 8583 | Financial transaction card originated messages standard |
| ISO 20022 | Universal financial industry message scheme |
| SWIFT | Society for Worldwide Interbank Financial Telecommunication |
| BIC | Bank Identifier Code (SWIFT code) |
| AML | Anti-Money Laundering |
| KYC | Know Your Customer |
| T+1 | Settlement one business day after transaction |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | 2026-01-31 |
| Reviewer | CTO | _________________ | ___________ |
| Approver | Tech Lead | _________________ | ___________ |

---

**End of Document E-011: Bank Integration Specifications**

*This document is the property of Smart Dairy Ltd. and contains confidential information. Unauthorized distribution is prohibited.*
