# SMART DAIRY LTD.
## CODE QUALITY & STANDARDS GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-015 |
| **Version** | 1.0 |
| **Date** | February 19, 2026 |
| **Author** | Tech Lead |
| **Owner** | Backend Team |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Python Coding Standards](#2-python-coding-standards)
3. [Odoo Development Standards](#3-odoo-development-standards)
4. [Code Review Process](#4-code-review-process)
5. [Testing Standards](#5-testing-standards)
6. [Documentation Standards](#6-documentation-standards)
7. [Git Workflow](#7-git-workflow)
8. [Static Analysis](#8-static-analysis)
9. [Appendices](#9-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document establishes coding standards, best practices, and quality guidelines for the Smart Dairy development team to ensure consistent, maintainable, and high-quality code across the project.

### 1.2 Scope

- Python coding standards
- Odoo-specific conventions
- Code review guidelines
- Testing requirements
- Documentation standards
- Git workflow

---

## 2. PYTHON CODING STANDARDS

### 2.1 Style Guide

Follow PEP 8 with the following additions/modifications:

```python
# Maximum line length: 100 characters (not 79)
# Use 4 spaces for indentation

# Good: Clear, descriptive names
class MilkProductionCalculator:
    def calculate_daily_average(self, production_records: list[MilkRecord]) -> float:
        """Calculate daily milk production average."""
        if not production_records:
            return 0.0
        
        total_volume = sum(record.quantity_liters for record in production_records)
        return total_volume / len(production_records)

# Bad: Unclear abbreviations
class MPC:
    def calc(self, recs):
        if not recs:
            return 0
        tot = sum(r.qty for r in recs)
        return tot / len(recs)
```

### 2.2 Type Hints

Use type hints throughout the codebase:

```python
from typing import Optional, List, Dict, Any
from datetime import datetime
from decimal import Decimal

def calculate_order_total(
    order_lines: List[OrderLine],
    discount_percent: Optional[Decimal] = None,
    apply_vat: bool = True
) -> Dict[str, Any]:
    """
    Calculate order total with optional discount and VAT.
    
    Args:
        order_lines: List of order line items
        discount_percent: Optional discount percentage (0-100)
        apply_vat: Whether to apply VAT
    
    Returns:
        Dictionary containing subtotal, discount, vat, and total
    """
    subtotal = sum(line.price * line.quantity for line in order_lines)
    
    discount = Decimal('0')
    if discount_percent:
        discount = subtotal * (discount_percent / 100)
    
    vat = Decimal('0')
    if apply_vat:
        vat = (subtotal - discount) * Decimal('0.15')  # 15% VAT
    
    return {
        'subtotal': subtotal,
        'discount': discount,
        'vat': vat,
        'total': subtotal - discount + vat
    }
```

### 2.3 Docstring Format

Use Google-style docstrings:

```python
def create_invoice(
    partner_id: int,
    invoice_lines: List[Dict],
    invoice_date: Optional[datetime] = None,
    payment_terms: str = 'immediate'
) -> Invoice:
    """
    Create a new customer invoice.
    
    Args:
        partner_id: ID of the customer partner
        invoice_lines: List of invoice line dictionaries with keys:
            - product_id: int
            - quantity: float
            - price_unit: Decimal
            - description: str (optional)
        invoice_date: Invoice date (defaults to today)
        payment_terms: Payment terms code ('immediate', 'net_30', etc.)
    
    Returns:
        Created Invoice object
    
    Raises:
        ValueError: If partner_id is invalid or invoice_lines is empty
        ValidationError: If any line item fails validation
    
    Example:
        >>> lines = [{
        ...     'product_id': 101,
        ...     'quantity': 10,
        ...     'price_unit': Decimal('50.00')
        ... }]
        >>> invoice = create_invoice(123, lines)
        >>> print(invoice.amount_total)
        Decimal('575.00')  # Including 15% VAT
    """
    pass
```

---

## 3. ODOO DEVELOPMENT STANDARDS

### 3.1 Module Structure

```
smart_farm_mgmt/
├── __init__.py                    # Module exports
├── __manifest__.py                # Module manifest
├── models/
│   ├── __init__.py
│   ├── farm_animal.py            # One model per file
│   ├── milk_production.py
│   └── health_record.py
├── controllers/
│   ├── __init__.py
│   └── main.py                   # External API endpoints
├── views/
│   ├── farm_animal_views.xml     # Grouped by model
│   ├── milk_production_views.xml
│   └── menu_views.xml            # Menu definitions
├── data/
│   ├── animal_breeds_data.xml    # Static data
│   └── farm_locations_data.xml
├── security/
│   ├── ir.model.access.csv       # Access rights
│   └── security_groups.xml       # Groups definition
├── static/
│   └── src/
│       ├── css/
│       ├── js/
│       └── xml/
├── tests/
│   ├── __init__.py
│   ├── test_farm_animal.py
│   └── test_milk_production.py
└── i18n/
    ├── bn.po                     # Bengali translations
    └── bn_BD.po
```

### 3.2 Model Naming

```python
# Module prefix: smart_farm
# Model names: smart_farm.{entity}

class FarmAnimal(models.Model):
    _name = 'farm.animal'           # Simplified for readability
    _description = 'Farm Animal'
    
    # OR with module prefix for uniqueness
    _name = 'smart_farm.animal'

# Inheritance
class FarmAnimalHealth(models.Model):
    _inherit = 'farm.animal'        # Extends existing model
    
    # OR
    _name = 'farm.animal.health'    # New model inheriting
    _inherit = ['mail.thread', 'mail.activity.mixin']
```

### 3.3 Field Definitions

```python
class FarmAnimal(models.Model):
    _name = 'farm.animal'
    
    # =====================================
    # Identification Fields
    # =====================================
    name = fields.Char(
        string='Animal ID',
        required=True,
        index=True,
        help='Unique identifier for the animal (e.g., SD-001)',
        tracking=True
    )
    
    rfid_tag = fields.Char(
        string='RFID Tag',
        index=True,
        tracking=True
    )
    
    # =====================================
    # Classification Fields
    # =====================================
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
    ], string='Species', required=True, default='cattle')
    
    status = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating'),
        ('dry', 'Dry'),
        ('pregnant', 'Pregnant'),
    ], string='Status', default='calf', tracking=True)
    
    # =====================================
    # Relational Fields
    # =====================================
    barn_id = fields.Many2one(
        'farm.location',
        string='Barn',
        ondelete='set null',
        tracking=True
    )
    
    milk_production_ids = fields.One2many(
        'farm.milk.production',
        'animal_id',
        string='Milk Production Records'
    )
    
    # =====================================
    # Computed Fields
    # =====================================
    age_months = fields.Integer(
        string='Age (Months)',
        compute='_compute_age',
        store=True
    )
    
    daily_milk_avg = fields.Float(
        string='Daily Average (L)',
        compute='_compute_milk_average',
        digits=(8, 2)
    )
    
    # =====================================
    # Constraints
    # =====================================
    _sql_constraints = [
        ('unique_rfid', 'UNIQUE(rfid_tag)', 'RFID tag must be unique!'),
    ]
    
    # =====================================
    # Compute Methods
    # =====================================
    @api.depends('birth_date')
    def _compute_age(self):
        for animal in self:
            if animal.birth_date:
                animal.age_months = (
                    datetime.now() - animal.birth_date
                ).days // 30
            else:
                animal.age_months = 0
    
    # =====================================
    # Constraint Methods
    # =====================================
    @api.constrains('birth_date')
    def _check_birth_date(self):
        for animal in self:
            if animal.birth_date and animal.birth_date > fields.Date.today():
                raise ValidationError(_('Birth date cannot be in the future.'))
    
    # =====================================
    # Business Methods
    # =====================================
    def action_record_milk(self):
        """Open milk recording form for this animal."""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': _('Record Milk Production'),
            'res_model': 'farm.milk.production',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.id,
            }
        }
```

### 3.4 Security Best Practices

```python
# ir.model.access.csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_farm_animal_user,farm.animal.user,model_farm_animal,base.group_user,1,0,0,0
access_farm_animal_manager,farm.animal.manager,model_farm_animal,group_farm_manager,1,1,1,1
access_farm_animal_worker,farm.animal.worker,model_farm_animal,group_farm_worker,1,1,1,0

# Record rules
<record id="rule_farm_animal_multi_company" model="ir.rule">
    <field name="name">Farm Animal: Multi-Company</field>
    <field name="model_id" ref="model_farm_animal"/>
    <field name="domain_force">[('company_id', 'in', company_ids)]</field>
</record>

<record id="rule_farm_animal_own_barn" model="ir.rule">
    <field name="name">Farm Animal: Own Barn Only</field>
    <field name="model_id" ref="model_farm_animal"/>
    <field name="domain_force">[('barn_id.supervisor_id', '=', user.id)]</field>
    <field name="groups" eval="[(4, ref('group_farm_supervisor'))]"/>
</record>
```

---

## 4. CODE REVIEW PROCESS

### 4.1 Review Checklist

```markdown
## Code Review Checklist

### Functionality
- [ ] Code meets requirements
- [ ] Edge cases handled
- [ ] Error handling implemented
- [ ] No hardcoded values (use config/constants)

### Code Quality
- [ ] Follows style guide
- [ ] Functions are focused and small
- [ ] No code duplication (DRY)
- [ ] Clear variable/function names

### Testing
- [ ] Unit tests added/updated
- [ ] Tests cover happy path and errors
- [ ] All tests pass

### Documentation
- [ ] Docstrings added
- [ ] Complex logic commented
- [ ] README updated if needed

### Security
- [ ] No SQL injection risks
- [ ] Input validation present
- [ ] Sensitive data not logged
- [ ] Access controls checked

### Performance
- [ ] No N+1 queries
- [ ] Efficient algorithms used
- [ ] Caching considered
```

### 4.2 Review Guidelines

| Aspect | Criteria |
|--------|----------|
| **Approvals Required** | 2 for critical code, 1 for minor changes |
| **Review Time** | Within 24 hours for PRs |
| **Comments** | Constructive, specific, actionable |
| **Approval** | Only approve when checklist complete |

---

## 5. TESTING STANDARDS

### 5.1 Test Structure

```python
# tests/test_farm_animal.py
from odoo.tests.common import TransactionCase, tagged
from odoo.exceptions import ValidationError

@tagged('post_install', '-at_install')
class TestFarmAnimal(TransactionCase):
    """Test cases for Farm Animal model."""
    
    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.FarmAnimal = cls.env['farm.animal']
        cls.Breed = cls.env['farm.breed']
        
        # Create test data
        cls.breed_holstein = cls.Breed.create({
            'name': 'Holstein Friesian',
            'species': 'cattle'
        })
    
    def test_create_animal(self):
        """Test animal creation with valid data."""
        animal = self.FarmAnimal.create({
            'name': 'TEST-001',
            'breed_id': self.breed_holstein.id,
            'gender': 'female',
            'species': 'cattle'
        })
        
        self.assertTrue(animal.id)
        self.assertEqual(animal.status, 'calf')
        self.assertEqual(animal.display_name, '[TEST-001] Holstein Friesian')
    
    def test_rfid_uniqueness(self):
        """Test RFID tag uniqueness constraint."""
        self.FarmAnimal.create({
            'name': 'TEST-001',
            'rfid_tag': 'RFID123',
            'breed_id': self.breed_holstein.id,
            'gender': 'female'
        })
        
        with self.assertRaises(ValidationError):
            self.FarmAnimal.create({
                'name': 'TEST-002',
                'rfid_tag': 'RFID123',
                'breed_id': self.breed_holstein.id,
                'gender': 'female'
            })
    
    def test_future_birth_date(self):
        """Test validation for future birth date."""
        from datetime import datetime, timedelta
        
        with self.assertRaises(ValidationError):
            self.FarmAnimal.create({
                'name': 'TEST-003',
                'breed_id': self.breed_holstein.id,
                'gender': 'female',
                'birth_date': datetime.now() + timedelta(days=1)
            })
    
    def test_milk_production_average(self):
        """Test milk production average calculation."""
        animal = self.FarmAnimal.create({
            'name': 'TEST-004',
            'breed_id': self.breed_holstein.id,
            'gender': 'female',
            'status': 'lactating'
        })
        
        # Create milk records
        self.env['farm.milk.production'].create([
            {'animal_id': animal.id, 'quantity': 10.0, 'production_date': '2026-01-01'},
            {'animal_id': animal.id, 'quantity': 12.0, 'production_date': '2026-01-02'},
            {'animal_id': animal.id, 'quantity': 11.0, 'production_date': '2026-01-03'},
        ])
        
        # Trigger computation
        animal._compute_milk_average()
        
        self.assertAlmostEqual(animal.daily_milk_avg, 11.0, places=2)
```

### 5.2 Test Coverage Requirements

| Component | Minimum Coverage |
|-----------|-----------------|
| Models | 85% |
| Services | 80% |
| Controllers | 70% |
| Utilities | 90% |
| Critical Paths | 100% |

---

## 6. DOCUMENTATION STANDARDS

### 6.1 Code Documentation

```python
class InventoryManager:
    """
    Manage inventory operations for Smart Dairy.
    
    This class handles stock movements, reservations, and
    availability calculations across all warehouses.
    
    Attributes:
        warehouse_id: Default warehouse for operations
        auto_reserve: Whether to auto-reserve stock on confirmation
    
    Example:
        >>> manager = InventoryManager(warehouse_id=1)
        >>> available = manager.get_available_qty(product_id=101)
        >>> print(f"Available: {available} units")
        Available: 500 units
    """
    
    def reserve_stock(
        self,
        product_id: int,
        quantity: float,
        order_id: int,
        expiration: Optional[datetime] = None
    ) -> Reservation:
        """
        Reserve stock for an order.
        
        Args:
            product_id: Product to reserve
            quantity: Amount to reserve
            order_id: Associated order
            expiration: Reservation expiry time
        
        Returns:
            Reservation object
        
        Raises:
            InsufficientStockError: If not enough stock available
            ProductNotFoundError: If product doesn't exist
        """
        pass
```

### 6.2 API Documentation

```python
@router.post("/orders", response_model=OrderResponse)
async def create_order(
    order_data: OrderCreate,
    current_user: User = Depends(get_current_user)
):
    """
    Create a new sales order.
    
    ## Authorization
    Requires `sales.create` permission.
    
    ## Request Body
    - `partner_id`: Customer ID (required)
    - `order_lines`: List of order lines (required)
    - `payment_method`: Preferred payment method
    
    ## Response
    Returns created order with calculated totals and taxes.
    
    ## Errors
    - `422`: Validation error (invalid product, insufficient stock)
    - `403`: Permission denied
    - `404`: Customer not found
    
    ## Example
    ```json
    {
        "partner_id": 123,
        "order_lines": [
            {"product_id": 101, "quantity": 10}
        ]
    }
    ```
    """
    pass
```

---

## 7. GIT WORKFLOW

### 7.1 Branch Naming

```
feature/SD-123-add-milk-production-report
bugfix/SD-456-fix-rfid-validation
hotfix/critical-payment-issue
refactor/simplify-pricing-engine
docs/update-api-documentation
```

### 7.2 Commit Message Format

```
type(scope): subject

body (optional)

footer (optional)
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `style`: Formatting, no code change
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

Examples:
```
feat(farm): add automatic lactation status update

- Update status based on calving records
- Add scheduled job for daily checks
- Send notification on status change

Fixes: SD-123
```

### 7.3 Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Unit tests added/updated
- [ ] Integration tests passed
- [ ] Manual testing completed

## Checklist
- [ ] Code follows style guide
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] No new warnings

## Related Issues
Fixes #123
Relates to #456
```

---

## 8. STATIC ANALYSIS

### 8.1 Tools Configuration

```ini
# setup.cfg
[flake8]
max-line-length = 100
exclude = .git,__pycache__,build,dist,*.egg-info,migrations
ignore = E203,W503
max-complexity = 10

[pylint]
max-line-length = 100
disable = C0103,R0903,R0913

[mypy]
python_version = 3.11
warn_return_any = True
warn_unused_configs = True
disallow_untyped_defs = True
```

### 8.2 Pre-commit Hooks

```yaml
# .pre-commit-config.yaml
repos:
  - repo: https://github.com/pre-commit/pre-commit-hooks
    rev: v4.4.0
    hooks:
      - id: trailing-whitespace
      - id: end-of-file-fixer
      - id: check-yaml
      - id: check-added-large-files

  - repo: https://github.com/psf/black
    rev: 23.7.0
    hooks:
      - id: black
        language_version: python3.11

  - repo: https://github.com/pycqa/isort
    rev: 5.12.0
    hooks:
      - id: isort
        args: ["--profile", "black"]

  - repo: https://github.com/pycqa/flake8
    rev: 6.1.0
    hooks:
      - id: flake8
        additional_dependencies: [flake8-docstrings]

  - repo: https://github.com/pre-commit/mirrors-mypy
    rev: v1.5.0
    hooks:
      - id: mypy
        additional_dependencies: [types-requests]
```

---

## 9. APPENDICES

### Appendix A: Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| Class | PascalCase | `MilkProductionCalculator` |
| Function | snake_case | `calculate_daily_average` |
| Variable | snake_case | `milk_records` |
| Constant | UPPER_SNAKE_CASE | `MAX_RETRY_ATTEMPTS` |
| Module | snake_case | `milk_production.py` |
| Package | snake_case | `farm_management` |
| Private | _leading_underscore | `_internal_method` |

### Appendix B: Quality Gates

| Gate | Requirement | Enforcement |
|------|-------------|-------------|
| Lint | No flake8 errors | Pre-commit/CI |
| Format | Black formatted | Pre-commit |
| Types | mypy passes | CI |
| Tests | >80% coverage | CI |
| Security | No bandit issues | CI |
| Complexity | <10 per function | flake8 |

---

**END OF CODE QUALITY & STANDARDS GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 19, 2026 | Tech Lead | Initial version |
