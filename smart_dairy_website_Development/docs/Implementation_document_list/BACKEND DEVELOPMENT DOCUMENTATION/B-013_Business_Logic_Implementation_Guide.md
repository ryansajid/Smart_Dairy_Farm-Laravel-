# SMART DAIRY LTD.
## BUSINESS LOGIC IMPLEMENTATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-013 |
| **Version** | 1.0 |
| **Date** | February 16, 2026 |
| **Author** | Tech Lead |
| **Owner** | Backend Team |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Core Business Rules](#2-core-business-rules)
3. [Farm Management Logic](#3-farm-management-logic)
4. [Sales & Pricing Logic](#4-sales--pricing-logic)
5. [Inventory Management](#5-inventory-management)
6. [Financial Calculations](#6-financial-calculations)
7. [Workflow Automation](#7-workflow-automation)
8. [Validation Rules](#8-validation-rules)
9. [Appendices](#9-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the core business logic implementation for Smart Dairy ERP, covering farm operations, sales processes, inventory management, and financial calculations.

### 1.2 Business Domain Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY BUSINESS DOMAINS                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐     ┌──────────────┐     ┌──────────────┐                │
│  │   FARM       │────►│  PRODUCTION  │────►│   SALES      │                │
│  │  MANAGEMENT  │     │  PROCESSING  │     │  & DELIVERY  │                │
│  └──────────────┘     └──────────────┘     └──────────────┘                │
│         │                   │                   │                          │
│         │                   │                   │                          │
│         ▼                   ▼                   ▼                          │
│  ┌────────────────────────────────────────────────────────────────────┐   │
│  │                      INVENTORY & WAREHOUSE                          │   │
│  └────────────────────────────────────────────────────────────────────┘   │
│         │                                                                  │
│         ▼                                                                  │
│  ┌────────────────────────────────────────────────────────────────────┐   │
│  │                      FINANCE & ACCOUNTING                           │   │
│  └────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. CORE BUSINESS RULES

### 2.1 Rule Engine Architecture

```python
# business/rules/engine.py
from abc import ABC, abstractmethod
from typing import List, Dict, Any, Optional
from dataclasses import dataclass
from enum import Enum

class RulePriority(Enum):
    CRITICAL = 1
    HIGH = 2
    NORMAL = 3
    LOW = 4

class RuleResult:
    def __init__(self, passed: bool, message: str = "", data: Dict = None):
        self.passed = passed
        self.message = message
        self.data = data or {}

class BusinessRule(ABC):
    """Base class for business rules."""
    
    name: str = ""
    description: str = ""
    priority: RulePriority = RulePriority.NORMAL
    
    @abstractmethod
    def evaluate(self, context: Dict[str, Any]) -> RuleResult:
        """Evaluate the rule against the given context."""
        pass
    
    def __str__(self):
        return f"{self.name} ({self.priority.name})"


class RuleEngine:
    """Engine for executing business rules."""
    
    def __init__(self):
        self.rules: List[BusinessRule] = []
    
    def register_rule(self, rule: BusinessRule):
        """Register a business rule."""
        self.rules.append(rule)
        # Sort by priority
        self.rules.sort(key=lambda r: r.priority.value)
    
    def evaluate_all(self, context: Dict[str, Any]) -> List[RuleResult]:
        """Evaluate all registered rules."""
        results = []
        for rule in self.rules:
            result = rule.evaluate(context)
            results.append(result)
            
            # Stop on critical failure
            if not result.passed and rule.priority == RulePriority.CRITICAL:
                break
        
        return results
    
    def validate(self, context: Dict[str, Any]) -> bool:
        """Validate all rules, return True if all pass."""
        results = self.evaluate_all(context)
        return all(r.passed for r in results)
```

---

## 3. FARM MANAGEMENT LOGIC

### 3.1 Animal Lifecycle Management

```python
# business/farm/animal_lifecycle.py
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta

class AnimalLifecycleManager:
    """Manage animal lifecycle state transitions."""
    
    # Age thresholds in days
    CALF_MAX_AGE = 180  # 6 months
    HEIFER_MAX_AGE = 730  # 2 years
    DRY_PERIOD_DAYS = 60  # 60 days before calving
    
    @staticmethod
    def calculate_age_status(birth_date: datetime, gender: str) -> str:
        """Determine animal status based on age and gender."""
        if not birth_date:
            return 'unknown'
        
        age_days = (datetime.now() - birth_date).days
        
        if age_days <= AnimalLifecycleManager.CALF_MAX_AGE:
            return 'calf'
        
        if gender == 'male':
            return 'bull' if age_days > 365 else 'young_bull'
        
        # Female logic
        if age_days <= AnimalLifecycleManager.HEIFER_MAX_AGE:
            return 'heifer'
        
        return 'adult'  # Will be further classified by lactation status
    
    @staticmethod
    def can_transition_to_lactating(animal) -> tuple[bool, str]:
        """Check if animal can transition to lactating status."""
        if animal.gender != 'female':
            return False, "Only females can lactate"
        
        if animal.status not in ['dry', 'pregnant']:
            return False, f"Cannot transition from {animal.status} to lactating"
        
        if not animal.last_calving_date:
            return False, "No calving record found"
        
        # Check if within reasonable time after calving
        days_since_calving = (datetime.now() - animal.last_calving_date).days
        if days_since_calving > 365:
            return False, "Too long since last calving"
        
        return True, "OK"
    
    @staticmethod
    def determine_dry_off_date(last_calving_date: datetime, 
                               expected_calving_date: datetime) -> datetime:
        """Calculate dry-off date (60 days before next calving)."""
        if expected_calving_date:
            return expected_calving_date - timedelta(days=60)
        
        # Default: 305 days after last calving (standard lactation)
        return last_calving_date + timedelta(days=305)
    
    @staticmethod
    def calculate_lactation_number(animal, new_calving_date: datetime) -> int:
        """Calculate new lactation number."""
        if not animal.calving_history:
            return 1
        
        # Count previous calvings
        previous_calvings = len([
            c for c in animal.calving_history 
            if c.date < new_calving_date
        ])
        
        return previous_calvings + 1


# Milk Production Calculations
class MilkProductionCalculator:
    """Calculate milk production metrics."""
    
    @staticmethod
    def calculate_daily_average(milk_records: list, days: int = 30) -> float:
        """Calculate average daily production over specified days."""
        cutoff_date = datetime.now() - timedelta(days=days)
        
        recent_records = [
            r for r in milk_records 
            if r.production_date >= cutoff_date
        ]
        
        if not recent_records:
            return 0.0
        
        total_volume = sum(r.quantity for r in recent_records)
        
        # Calculate actual days with records
        unique_dates = set(r.production_date for r in recent_records)
        
        return total_volume / len(unique_dates) if unique_dates else 0.0
    
    @staticmethod
    def calculate_305_day_yield(milk_records: list, 
                                 lactation_start: datetime) -> float:
        """Calculate 305-day milk yield."""
        lactation_end = lactation_start + timedelta(days=305)
        
        lactation_records = [
            r for r in milk_records
            if lactation_start <= r.production_date <= lactation_end
        ]
        
        return sum(r.quantity for r in lactation_records)
    
    @staticmethod
    def calculate_snf(fat_percentage: float, density: float) -> float:
        """Calculate Solids-Not-Fat percentage."""
        # SNF = (0.25 * density) + (0.22 * fat_percentage) + 0.72
        return (0.25 * density) + (0.22 * fat_percentage) + 0.72
    
    @staticmethod
    def calculate_price_based_on_quality(
        base_price: float,
        fat_percentage: float,
        snf_percentage: float
    ) -> float:
        """Calculate milk price based on quality parameters."""
        # Base: 4% fat, 8.5% SNF
        base_fat = 4.0
        base_snf = 8.5
        
        # Fat premium: +5% for each 0.1% above base
        fat_premium = max(0, (fat_percentage - base_fat) * 10 * 0.05)
        
        # SNF premium: +3% for each 0.1% above base
        snf_premium = max(0, (snf_percentage - base_snf) * 10 * 0.03)
        
        return base_price * (1 + fat_premium + snf_premium)
```

### 3.2 Breeding & Reproduction Logic

```python
# business/farm/breeding.py
from datetime import datetime, timedelta

class BreedingCalculator:
    """Calculate breeding and reproduction metrics."""
    
    GESTATION_PERIOD_DAYS = 283  # Average cattle gestation
    HEAT_CYCLE_DAYS = 21
    HEAT_DURATION_HOURS = 18
    
    @staticmethod
    def calculate_expected_calving_date(
        breeding_date: datetime,
        gestation_days: int = None
    ) -> datetime:
        """Calculate expected calving date."""
        days = gestation_days or BreedingCalculator.GESTATION_PERIOD_DAYS
        return breeding_date + timedelta(days=days)
    
    @staticmethod
    def calculate_next_heat_date(last_heat_date: datetime) -> datetime:
        """Predict next heat date."""
        return last_heat_date + timedelta(days=BreedingCalculator.HEAT_CYCLE_DAYS)
    
    @staticmethod
    def is_in_fertile_window(
        last_heat_date: datetime,
        current_date: datetime = None
    ) -> tuple[bool, str]:
        """Check if animal is in fertile window."""
        current = current_date or datetime.now()
        
        days_since_heat = (current - last_heat_date).days
        
        # Heat window: 12-18 hours after onset
        if days_since_heat == 0:
            hours_since_heat = (current - last_heat_date).seconds / 3600
            if 12 <= hours_since_heat <= 18:
                return True, "Optimal breeding window"
            elif hours_since_heat < 12:
                return False, "Too early - wait 12 hours"
            else:
                return False, "Window passed - wait for next cycle"
        
        # Next cycle prediction
        next_heat = BreedingCalculator.calculate_next_heat_date(last_heat_date)
        days_until = (next_heat - current).days
        
        return False, f"Next expected heat in {days_until} days"
    
    @staticmethod
    def calculate_conception_rate(
        total_breedings: int,
        successful_pregnancies: int
    ) -> float:
        """Calculate conception rate percentage."""
        if total_breedings == 0:
            return 0.0
        return (successful_pregnancies / total_breedings) * 100
    
    @staticmethod
    def calculate_calving_interval(
        calving_dates: list
    ) -> float:
        """Calculate average days between calvings."""
        if len(calving_dates) < 2:
            return 0.0
        
        intervals = []
        sorted_dates = sorted(calving_dates)
        
        for i in range(1, len(sorted_dates)):
            interval = (sorted_dates[i] - sorted_dates[i-1]).days
            intervals.append(interval)
        
        return sum(intervals) / len(intervals)
```

---

## 4. SALES & PRICING LOGIC

### 4.1 Pricing Engine

```python
# business/sales/pricing.py
from datetime import datetime
from typing import List, Optional

class PricingEngine:
    """Calculate prices based on various rules and conditions."""
    
    def calculate_product_price(
        self,
        product_id: int,
        quantity: float,
        customer_id: int = None,
        pricelist_id: int = None,
        date: datetime = None
    ) -> dict:
        """
        Calculate final price for a product.
        
        Considers:
        - Base price
        - Pricelist rules
        - Volume discounts
        - Customer tier
        - Promotions
        """
        product = self.env['product.product'].browse(product_id)
        base_price = product.list_price
        
        price_info = {
            'base_price': base_price,
            'discounts': [],
            'final_price': base_price,
            'currency': product.currency_id.name
        }
        
        # Apply pricelist
        if pricelist_id:
            pricelist_price = self._get_pricelist_price(
                pricelist_id, product_id, quantity, date
            )
            if pricelist_price and pricelist_price < price_info['final_price']:
                discount = price_info['final_price'] - pricelist_price
                price_info['discounts'].append({
                    'type': 'pricelist',
                    'amount': discount,
                    'description': f'Pricelist discount'
                })
                price_info['final_price'] = pricelist_price
        
        # Apply volume discount
        volume_discount = self._calculate_volume_discount(product_id, quantity)
        if volume_discount > 0:
            discount_amount = price_info['final_price'] * volume_discount
            price_info['discounts'].append({
                'type': 'volume',
                'amount': discount_amount,
                'description': f'Volume discount ({volume_discount*100}%)'
            })
            price_info['final_price'] -= discount_amount
        
        # Apply customer tier discount
        if customer_id:
            tier_discount = self._get_customer_tier_discount(customer_id)
            if tier_discount > 0:
                discount_amount = price_info['final_price'] * tier_discount
                price_info['discounts'].append({
                    'type': 'tier',
                    'amount': discount_amount,
                    'description': f'Customer tier discount'
                })
                price_info['final_price'] -= discount_amount
        
        # Apply active promotions
        promotion_discount = self._apply_promotions(product_id, quantity)
        if promotion_discount > 0:
            price_info['discounts'].append({
                'type': 'promotion',
                'amount': promotion_discount,
                'description': 'Active promotion'
            })
            price_info['final_price'] -= promotion_discount
        
        # Round to 2 decimal places
        price_info['final_price'] = round(price_info['final_price'], 2)
        price_info['total_discount'] = sum(d['amount'] for d in price_info['discounts'])
        
        return price_info
    
    def _calculate_volume_discount(self, product_id: int, quantity: float) -> float:
        """Calculate volume-based discount percentage."""
        tiers = [
            (100, 0.05),   # 5% for 100+
            (500, 0.10),   # 10% for 500+
            (1000, 0.15),  # 15% for 1000+
        ]
        
        for min_qty, discount in sorted(tiers, reverse=True):
            if quantity >= min_qty:
                return discount
        
        return 0.0
    
    def calculate_subscription_price(
        self,
        product_id: int,
        frequency: str,  # daily, alternate, weekly
        duration_months: int
    ) -> dict:
        """Calculate subscription pricing with discounts."""
        product = self.env['product.product'].browse(product_id)
        base_price = product.list_price
        
        # Frequency multiplier
        frequency_multiplier = {
            'daily': 30,
            'alternate': 15,
            'weekly': 4,
            'custom': 1
        }
        
        monthly_deliveries = frequency_multiplier.get(frequency, 30)
        monthly_base = base_price * monthly_deliveries
        
        # Duration discount
        duration_discount = 0.0
        if duration_months >= 12:
            duration_discount = 0.15  # 15% for annual
        elif duration_months >= 6:
            duration_discount = 0.10  # 10% for 6 months
        elif duration_months >= 3:
            duration_discount = 0.05  # 5% for 3 months
        
        discounted_monthly = monthly_base * (1 - duration_discount)
        total_price = discounted_monthly * duration_months
        
        return {
            'base_monthly': monthly_base,
            'discounted_monthly': round(discounted_monthly, 2),
            'duration_discount': duration_discount * 100,
            'total_subscription_price': round(total_price, 2),
            'savings': round((monthly_base * duration_months) - total_price, 2)
        }


# B2B Credit Management
class CreditManager:
    """Manage B2B customer credit limits and approvals."""
    
    def check_credit_availability(self, partner_id: int, order_amount: float) -> dict:
        """Check if customer has sufficient credit."""
        partner = self.env['res.partner'].browse(partner_id)
        
        credit_info = {
            'credit_limit': partner.credit_limit,
            'total_due': partner.total_due,
            'available_credit': partner.credit_limit - partner.total_due,
            'can_order': False,
            'requires_approval': False,
            'message': ''
        }
        
        if credit_info['available_credit'] >= order_amount:
            credit_info['can_order'] = True
            credit_info['message'] = 'Credit available'
        elif credit_info['available_credit'] > 0:
            # Partial credit available
            credit_info['can_order'] = True
            credit_info['requires_approval'] = True
            credit_info['message'] = 'Partial credit - requires approval'
        else:
            credit_info['can_order'] = False
            credit_info['message'] = 'Credit limit exceeded'
        
        return credit_info
    
    def calculate_credit_score(self, partner_id: int) -> int:
        """Calculate customer credit score (0-100)."""
        partner = self.env['res.partner'].browse(partner_id)
        
        score = 50  # Base score
        
        # Payment history (30%)
        if partner.payment_history:
            on_time_ratio = partner.on_time_payments / partner.total_payments
            score += on_time_ratio * 30
        
        # Order frequency (20%)
        recent_orders = self._get_recent_orders(partner_id, months=6)
        if len(recent_orders) >= 6:
            score += 20
        elif len(recent_orders) >= 3:
            score += 10
        
        # Average order value (20%)
        avg_order = self._calculate_average_order(partner_id)
        if avg_order > 10000:
            score += 20
        elif avg_order > 5000:
            score += 10
        
        # Account age (10%)
        account_age_days = (datetime.now() - partner.create_date).days
        if account_age_days > 365:
            score += 10
        elif account_age_days > 180:
            score += 5
        
        return min(100, max(0, int(score)))
```

---

## 5. INVENTORY MANAGEMENT

### 5.1 Stock Calculation Logic

```python
# business/inventory/stock.py
from datetime import datetime, timedelta

class StockCalculator:
    """Calculate stock levels and projections."""
    
    @staticmethod
    def calculate_available_qty(product_id: int, location_id: int = None) -> dict:
        """Calculate available quantity considering all factors."""
        product = self.env['product.product'].browse(product_id)
        
        # Base quantities
        on_hand = product.qty_available
        reserved = product.outgoing_qty
        incoming = product.incoming_qty
        
        # Calculate available
        available = on_hand - reserved
        virtual = available + incoming
        
        return {
            'on_hand': on_hand,
            'reserved': reserved,
            'available': available,
            'incoming': incoming,
            'virtual_available': virtual,
            'unit': product.uom_id.name
        }
    
    @staticmethod
    def calculate_reorder_point(
        product_id: int,
        lead_time_days: int = 7,
        safety_days: int = 3
    ) -> dict:
        """Calculate optimal reorder point."""
        product = self.env['product.product'].browse(product_id)
        
        # Get average daily consumption
        daily_consumption = product.average_daily_sales or 1
        
        # Lead time demand
        lead_time_demand = daily_consumption * lead_time_days
        
        # Safety stock (for safety_days + lead_time variability)
        variability_factor = 1.5  # 50% buffer
        safety_stock = daily_consumption * safety_days * variability_factor
        
        reorder_point = lead_time_demand + safety_stock
        
        # Maximum stock (reorder point + 2 weeks supply)
        max_stock = reorder_point + (daily_consumption * 14)
        
        return {
            'reorder_point': reorder_point,
            'safety_stock': safety_stock,
            'max_stock': max_stock,
            'suggested_order_qty': max_stock - product.qty_available,
            'daily_consumption': daily_consumption
        }
    
    @staticmethod
    def calculate_stock_value(
        product_id: int,
        location_id: int = None,
        valuation_method: str = 'fifo'
    ) -> dict:
        """Calculate stock value using specified valuation method."""
        product = self.env['product.product'].browse(product_id)
        
        quants = self.env['stock.quant'].search([
            ('product_id', '=', product_id),
            ('location_id.usage', '=', 'internal'),
            ('quantity', '>', 0)
        ])
        
        total_value = 0
        total_qty = 0
        layers = []
        
        if valuation_method == 'fifo':
            # Get stock valuation layers ordered by date
            valuation_layers = self.env['stock.valuation.layer'].search([
                ('product_id', '=', product_id),
                ('remaining_qty', '>', 0)
            ], order='create_date')
            
            remaining_to_value = sum(q.quantity for q in quants)
            
            for layer in valuation_layers:
                if remaining_to_value <= 0:
                    break
                
                qty = min(layer.remaining_qty, remaining_to_value)
                value = qty * layer.unit_cost
                
                layers.append({
                    'date': layer.create_date,
                    'quantity': qty,
                    'unit_cost': layer.unit_cost,
                    'value': value
                })
                
                total_value += value
                total_qty += qty
                remaining_to_value -= qty
        
        else:  # Average cost
            avg_cost = product.standard_price
            total_qty = sum(q.quantity for q in quants)
            total_value = total_qty * avg_cost
        
        return {
            'total_quantity': total_qty,
            'total_value': total_value,
            'average_cost': total_value / total_qty if total_qty else 0,
            'valuation_method': valuation_method,
            'layers': layers
        }
    
    @staticmethod
    def forecast_stock_out(
        product_id: int,
        days_ahead: int = 30
    ) -> dict:
        """Forecast when stock will run out."""
        product = self.env['product.product'].browse(product_id)
        
        current_stock = product.qty_available
        daily_consumption = product.average_daily_sales or 1
        
        if daily_consumption <= 0:
            return {
                'will_stock_out': False,
                'days_until_stock_out': float('inf'),
                'stock_out_date': None
            }
        
        days_until_stock_out = current_stock / daily_consumption
        stock_out_date = datetime.now() + timedelta(days=days_until_stock_out)
        
        return {
            'will_stock_out': days_until_stock_out <= days_ahead,
            'days_until_stock_out': int(days_until_stock_out),
            'stock_out_date': stock_out_date,
            'current_stock': current_stock,
            'daily_consumption': daily_consumption
        }
```

### 5.2 Cold Chain Management

```python
# business/inventory/cold_chain.py
from datetime import datetime, timedelta

class ColdChainManager:
    """Manage cold chain compliance for dairy products."""
    
    TEMPERATURE_RANGES = {
        'frozen': (-25, -18),      # Ice cream
        'deep_chill': (-2, 2),     # Frozen meat
        'chilled': (0, 4),         # Milk, yogurt
        'cool': (8, 12),           # Butter, cheese
    }
    
    SHELF_LIFE = {
        'fresh_milk': {'temp': 'chilled', 'days': 5},
        'pasteurized_milk': {'temp': 'chilled', 'days': 7},
        'uht_milk': {'temp': 'cool', 'days': 180},
        'yogurt': {'temp': 'chilled', 'days': 21},
        'cheese': {'temp': 'cool', 'days': 90},
        'butter': {'temp': 'cool', 'days': 120},
        'ice_cream': {'temp': 'frozen', 'days': 365},
    }
    
    @staticmethod
    def check_temperature_compliance(
        product_type: str,
        current_temp: float
    ) -> dict:
        """Check if current temperature is within acceptable range."""
        shelf_life_info = ColdChainManager.SHELF_LIFE.get(product_type)
        if not shelf_life_info:
            return {'compliant': None, 'message': 'Unknown product type'}
        
        temp_range = ColdChainManager.TEMPERATURE_RANGES[shelf_life_info['temp']]
        min_temp, max_temp = temp_range
        
        is_compliant = min_temp <= current_temp <= max_temp
        
        return {
            'compliant': is_compliant,
            'current_temp': current_temp,
            'required_range': temp_range,
            'deviation': current_temp - max_temp if current_temp > max_temp else 
                        current_temp - min_temp if current_temp < min_temp else 0,
            'alert_level': 'critical' if not is_compliant else 'normal'
        }
    
    @staticmethod
    def calculate_remaining_shelf_life(
        product_type: str,
        production_date: datetime,
        storage_temp: float = None
    ) -> dict:
        """Calculate remaining shelf life considering storage conditions."""
        shelf_life_info = ColdChainManager.SHELF_LIFE.get(product_type)
        if not shelf_life_info:
            return {'error': 'Unknown product type'}
        
        base_shelf_life = shelf_life_info['days']
        
        # Adjust for temperature violations
        temp_factor = 1.0
        if storage_temp:
            temp_range = ColdChainManager.TEMPERATURE_RANGES[shelf_life_info['temp']]
            if storage_temp > temp_range[1]:
                # Reduce shelf life for temperature abuse
                temp_factor = 0.5  # 50% reduction
        
        adjusted_shelf_life = base_shelf_life * temp_factor
        
        age_days = (datetime.now() - production_date).days
        remaining_days = max(0, adjusted_shelf_life - age_days)
        
        expiry_date = production_date + timedelta(days=adjusted_shelf_life)
        
        return {
            'production_date': production_date,
            'base_shelf_life_days': base_shelf_life,
            'adjusted_shelf_life_days': adjusted_shelf_life,
            'age_days': age_days,
            'remaining_days': remaining_days,
            'expiry_date': expiry_date,
            'freshness_percentage': (remaining_days / adjusted_shelf_life) * 100 if adjusted_shelf_life else 0
        }
```

---

## 6. FINANCIAL CALCULATIONS

### 6.1 VAT/Tax Calculations

```python
# business/finance/tax.py
from decimal import Decimal, ROUND_HALF_UP

class TaxCalculator:
    """Calculate taxes according to Bangladesh regulations."""
    
    VAT_RATES = {
        'standard': Decimal('0.15'),      # 15%
        'reduced': Decimal('0.05'),       # 5%
        'zero': Decimal('0'),             # 0%
    }
    
    @staticmethod
    def calculate_vat(
        amount: Decimal,
        vat_rate_key: str = 'standard',
        inclusive: bool = False
    ) -> dict:
        """
        Calculate VAT amount.
        
        Args:
            amount: Base amount
            vat_rate_key: 'standard', 'reduced', or 'zero'
            inclusive: True if amount includes VAT
        """
        rate = TaxCalculator.VAT_RATES.get(vat_rate_key, Decimal('0.15'))
        
        if inclusive:
            # Extract VAT from inclusive amount
            base_amount = amount / (1 + rate)
            vat_amount = amount - base_amount
        else:
            # Add VAT to exclusive amount
            base_amount = amount
            vat_amount = (amount * rate).quantize(Decimal('0.01'), rounding=ROUND_HALF_UP)
        
        return {
            'base_amount': base_amount.quantize(Decimal('0.01')),
            'vat_rate': rate,
            'vat_amount': vat_amount,
            'total_amount': (base_amount + vat_amount).quantize(Decimal('0.01'))
        }
    
    @staticmethod
    def calculate_invoice_totals(lines: list) -> dict:
        """Calculate invoice totals with VAT breakdown."""
        subtotal = Decimal('0')
        vat_by_rate = {}
        
        for line in lines:
            line_base = Decimal(str(line['quantity'])) * Decimal(str(line['unit_price']))
            line_discount = line_base * Decimal(str(line.get('discount', 0)))
            line_net = line_base - line_discount
            
            vat_rate = line.get('vat_rate', 'standard')
            vat_calc = TaxCalculator.calculate_vat(line_net, vat_rate)
            
            subtotal += line_net
            
            if vat_rate not in vat_by_rate:
                vat_by_rate[vat_rate] = Decimal('0')
            vat_by_rate[vat_rate] += vat_calc['vat_amount']
        
        total_vat = sum(vat_by_rate.values())
        
        return {
            'subtotal': subtotal.quantize(Decimal('0.01')),
            'vat_breakdown': {k: v.quantize(Decimal('0.01')) for k, v in vat_by_rate.items()},
            'total_vat': total_vat.quantize(Decimal('0.01')),
            'total_amount': (subtotal + total_vat).quantize(Decimal('0.01'))
        }
```

---

## 7. WORKFLOW AUTOMATION

### 7.1 Order Processing Workflow

```python
# business/workflows/order.py
from enum import Enum, auto

class OrderStatus(Enum):
    DRAFT = auto()
    CONFIRMED = auto()
    PROCESSING = auto()
    READY = auto()
    SHIPPED = auto()
    DELIVERED = auto()
    CANCELLED = auto()

class OrderWorkflow:
    """Manage order state transitions."""
    
    VALID_TRANSITIONS = {
        OrderStatus.DRAFT: [OrderStatus.CONFIRMED, OrderStatus.CANCELLED],
        OrderStatus.CONFIRMED: [OrderStatus.PROCESSING, OrderStatus.CANCELLED],
        OrderStatus.PROCESSING: [OrderStatus.READY, OrderStatus.CANCELLED],
        OrderStatus.READY: [OrderStatus.SHIPPED, OrderStatus.CANCELLED],
        OrderStatus.SHIPPED: [OrderStatus.DELIVERED],
        OrderStatus.DELIVERED: [],
        OrderStatus.CANCELLED: [],
    }
    
    @staticmethod
    def can_transition(from_status: OrderStatus, to_status: OrderStatus) -> bool:
        """Check if status transition is valid."""
        return to_status in OrderWorkflow.VALID_TRANSITIONS.get(from_status, [])
    
    @staticmethod
    def get_available_transitions(status: OrderStatus) -> list:
        """Get list of valid next statuses."""
        return OrderWorkflow.VALID_TRANSITIONS.get(status, [])
    
    @staticmethod
    def process_transition(order_id: int, new_status: OrderStatus) -> dict:
        """Process order status transition with side effects."""
        order = self.env['sale.order'].browse(order_id)
        current_status = OrderStatus[order.state.upper()]
        
        if not OrderWorkflow.can_transition(current_status, new_status):
            raise ValueError(
                f"Invalid transition from {current_status.name} to {new_status.name}"
            )
        
        # Perform transition-specific actions
        actions = []
        
        if new_status == OrderStatus.CONFIRMED:
            # Reserve inventory
            OrderWorkflow._reserve_inventory(order)
            actions.append('inventory_reserved')
            
            # Send confirmation email
            OrderWorkflow._send_confirmation(order)
            actions.append('confirmation_sent')
            
        elif new_status == OrderStatus.SHIPPED:
            # Create delivery record
            OrderWorkflow._create_delivery(order)
            actions.append('delivery_created')
            
            # Send tracking info
            OrderWorkflow._send_tracking(order)
            actions.append('tracking_sent')
            
        elif new_status == OrderStatus.DELIVERED:
            # Update inventory
            OrderWorkflow._confirm_delivery(order)
            actions.append('delivery_confirmed')
            
            # Request feedback
            OrderWorkflow._request_feedback(order)
            actions.append('feedback_requested')
            
        elif new_status == OrderStatus.CANCELLED:
            # Release inventory
            OrderWorkflow._release_inventory(order)
            actions.append('inventory_released')
            
            # Process refund if paid
            if order.payment_state == 'paid':
                OrderWorkflow._process_refund(order)
                actions.append('refund_processed')
        
        return {
            'success': True,
            'previous_status': current_status.name,
            'new_status': new_status.name,
            'actions_performed': actions
        }
```

---

## 8. VALIDATION RULES

### 8.1 Cross-Field Validation

```python
# business/validation/rules.py
from odoo import models, api
from odoo.exceptions import ValidationError

class ValidationRules:
    """Common validation rules."""
    
    @staticmethod
    def validate_milk_production_record(record):
        """Validate milk production record."""
        errors = []
        
        # Check quantity is positive
        if record.quantity <= 0:
            errors.append("Quantity must be positive")
        
        # Check quantity is within reasonable range
        if record.quantity > 50:  # Liters
            errors.append("Quantity seems unusually high (>50L)")
        
        # Check fat percentage
        if record.fat_percentage:
            if not (2 <= record.fat_percentage <= 10):
                errors.append("Fat percentage should be between 2% and 10%")
        
        # Check SNF percentage
        if record.snf_percentage:
            if not (7 <= record.snf_percentage <= 12):
                errors.append("SNF percentage should be between 7% and 12%")
        
        # Check animal is in lactating status
        if record.animal_id.status != 'lactating':
            errors.append(f"Animal is not in lactating status (current: {record.animal_id.status})")
        
        # Check for duplicate record
        existing = record.env['farm.milk.production'].search([
            ('animal_id', '=', record.animal_id.id),
            ('production_date', '=', record.production_date),
            ('session', '=', record.session),
            ('id', '!=', record.id)
        ])
        if existing:
            errors.append("Duplicate production record for this animal, date, and session")
        
        if errors:
            raise ValidationError("\n".join(errors))
    
    @staticmethod
    def validate_order_lines(order):
        """Validate order lines before confirmation."""
        errors = []
        
        for line in order.order_line:
            # Check stock availability
            available = line.product_id.qty_available
            if line.product_uom_qty > available:
                errors.append(
                    f"Insufficient stock for {line.product_id.name}. "
                    f"Requested: {line.product_uom_qty}, Available: {available}"
                )
            
            # Check minimum order quantity
            if line.product_uom_qty < line.product_id.sale_min_qty:
                errors.append(
                    f"Minimum order quantity for {line.product_id.name} "
                    f"is {line.product_id.sale_min_qty}"
                )
        
        # Check minimum order value for B2B
        if order.partner_id.is_b2b and order.amount_total < 5000:
            errors.append("Minimum order value for B2B is 5,000 BDT")
        
        if errors:
            raise ValidationError("\n".join(errors))
```

---

## 9. APPENDICES

### Appendix A: Business Rule Registry

| Rule ID | Rule Name | Domain | Priority |
|---------|-----------|--------|----------|
| BR-001 | Animal Status Transition | Farm | High |
| BR-002 | Milk Production Validation | Farm | Critical |
| BR-003 | Credit Limit Check | Sales | Critical |
| BR-004 | Stock Availability | Inventory | High |
| BR-005 | Price Calculation | Sales | High |
| BR-006 | VAT Calculation | Finance | Critical |
| BR-007 | Cold Chain Compliance | Inventory | Critical |

---

**END OF BUSINESS LOGIC IMPLEMENTATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 16, 2026 | Tech Lead | Initial version |
