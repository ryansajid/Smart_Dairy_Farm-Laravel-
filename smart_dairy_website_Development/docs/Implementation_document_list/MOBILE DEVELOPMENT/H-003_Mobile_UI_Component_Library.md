# Smart Dairy Ltd. - Mobile UI Component Library

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | H-003 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | UI/UX Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Tech Lead |
| **Status** | Approved |
| **Classification** | Internal Use |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Design Tokens](#2-design-tokens)
3. [Core Components](#3-core-components)
4. [Smart Dairy Specific Components](#4-smart-dairy-specific-components)
5. [Theme Configuration](#5-theme-configuration)
6. [Responsive Layouts](#6-responsive-layouts)
7. [Animation Components](#7-animation-components)
8. [Icon System](#8-icon-system)
9. [Usage Examples](#9-usage-examples)
10. [Testing Components](#10-testing-components)
11. [Documentation](#11-documentation)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the complete Mobile UI Component Library for Smart Dairy Ltd.'s mobile applications. It serves as the single source of truth for all UI components, design tokens, and usage patterns used across the Smart Dairy mobile ecosystem.

### 1.2 Design System Philosophy

The Smart Dairy UI Component Library follows these core principles:

1. **Consistency**: All components maintain visual and behavioral consistency across the application
2. **Accessibility**: Components are designed with WCAG 2.1 AA compliance in mind
3. **Performance**: Optimized for 60fps animations and minimal rebuild overhead
4. **Extensibility**: Easy to customize and extend for future requirements
5. **Bangladesh Context**: Designed for Bengali (BN) and English (EN) bilingual support

### 1.3 Technology Stack

- **Framework**: Flutter 3.16+
- **Design Language**: Material Design 3 (Material You)
- **State Management**: Provider / Riverpod
- **Localization**: flutter_localizations + intl
- **Testing**: flutter_test, golden_toolkit

### 1.4 Document Scope

This document covers:
- 15+ reusable UI components
- Complete design token specifications
- Theme configuration guidelines
- Responsive layout patterns
- Animation utilities
- Testing strategies

---

## 2. Design Tokens

### 2.1 Color Palette

#### Primary Colors

| Token | Hex Code | Usage |
|-------|----------|-------|
| `--color-primary` | `#1B5E20` | Primary brand color, main actions |
| `--color-primary-light` | `#4C8C4A` | Hover states, light backgrounds |
| `--color-primary-dark` | `#003300` | Active states, emphasis |
| `--color-primary-container` | `#C8E6C9` | Container backgrounds |
| `--color-on-primary` | `#FFFFFF` | Text on primary |
| `--color-on-primary-container` | `#002204` | Text on primary container |

#### Secondary Colors

| Token | Hex Code | Usage |
|-------|----------|-------|
| `--color-secondary` | `#5D4037` | Secondary actions, accents |
| `--color-secondary-light` | `#8D6E63` | Hover states |
| `--color-secondary-dark` | `#321911` | Emphasis |
| `--color-secondary-container` | `#D7CCC8` | Secondary containers |
| `--color-on-secondary` | `#FFFFFF` | Text on secondary |
| `--color-on-secondary-container` | `#261813` | Text on secondary container |

#### Semantic Colors

| Token | Hex Code | Usage |
|-------|----------|-------|
| `--color-success` | `#2E7D32` | Success states, positive actions |
| `--color-success-light` | `#4CAF50` | Success light variant |
| `--color-success-container` | `#E8F5E9` | Success backgrounds |
| `--color-warning` | `#F57C00` | Warning states |
| `--color-warning-light` | `#FFB74D` | Warning light variant |
| `--color-warning-container` | `#FFF3E0` | Warning backgrounds |
| `--color-error` | `#C62828` | Error states, destructive actions |
| `--color-error-light` | `#EF5350` | Error light variant |
| `--color-error-container` | `#FFEBEE` | Error backgrounds |
| `--color-info` | `#1565C0` | Information states |
| `--color-info-light` | `#42A5F5` | Info light variant |
| `--color-info-container` | `#E3F2FD` | Info backgrounds |

#### Neutral Colors

| Token | Hex Code | Usage |
|-------|----------|-------|
| `--color-surface` | `#FFFFFF` | Main surface background |
| `--color-surface-variant` | `#F5F5F5` | Alternate surface |
| `--color-surface-dim` | `#EBEBEB` | Dimmed surface |
| `--color-background` | `#FAFAFA` | App background |
| `--color-on-surface` | `#1C1B1F` | Primary text |
| `--color-on-surface-variant` | `#49454F` | Secondary text |
| `--color-outline` | `#79747E` | Borders, dividers |
| `--color-outline-variant` | `#CAC4D0` | Subtle borders |

### 2.2 Typography

#### Type Scale

| Style | Font | Size | Weight | Line Height | Letter Spacing |
|-------|------|------|--------|-------------|----------------|
| Display Large | Roboto | 57sp | 400 | 64sp | -0.25 |
| Display Medium | Roboto | 45sp | 400 | 52sp | 0 |
| Display Small | Roboto | 36sp | 400 | 44sp | 0 |
| Headline Large | Roboto | 32sp | 400 | 40sp | 0 |
| Headline Medium | Roboto | 28sp | 400 | 36sp | 0 |
| Headline Small | Roboto | 24sp | 400 | 32sp | 0 |
| Title Large | Roboto | 22sp | 500 | 28sp | 0 |
| Title Medium | Roboto | 16sp | 500 | 24sp | 0.15 |
| Title Small | Roboto | 14sp | 500 | 20sp | 0.1 |
| Body Large | Roboto | 16sp | 400 | 24sp | 0.5 |
| Body Medium | Roboto | 14sp | 400 | 20sp | 0.25 |
| Body Small | Roboto | 12sp | 400 | 16sp | 0.4 |
| Label Large | Roboto | 14sp | 500 | 20sp | 0.1 |
| Label Medium | Roboto | 12sp | 500 | 16sp | 0.5 |
| Label Small | Roboto | 11sp | 500 | 16sp | 0.5 |

### 2.3 Spacing Scale

| Token | Value | Usage |
|-------|-------|-------|
| `--space-xs` | 4dp | Tight spacing, icon gaps |
| `--space-sm` | 8dp | Small gaps, compact layouts |
| `--space-md` | 12dp | Default spacing |
| `--space-lg` | 16dp | Section spacing |
| `--space-xl` | 24dp | Large sections |
| `--space-2xl` | 32dp | Major sections |
| `--space-3xl` | 48dp | Page-level spacing |
| `--space-4xl` | 64dp | Major divisions |

### 2.4 Shadow System

| Elevation | Shadow Color | Offset | Blur | Spread |
|-----------|--------------|--------|------|--------|
| Level 0 | #000000 | 0, 0 | 0 | 0 |
| Level 1 | #000000 | 0, 1 | 3 | 1 (12% opacity) |
| Level 2 | #000000 | 0, 1 | 2 | 0 (30% opacity) |
| Level 3 | #000000 | 0, 1 | 8 | 0 (12% opacity) |
| Level 4 | #000000 | 0, 2 | 4 | -1 (15% opacity) |
| Level 5 | #000000 | 0, 4 | 8 | -2 (20% opacity) |

---

## 3. Core Components

### 3.1 AppButton

```dart
// lib/ui/components/app_button.dart

import 'package:flutter/material.dart';

enum AppButtonVariant { primary, secondary, outline, ghost, danger }
enum AppButtonSize { small, medium, large }

class AppButton extends StatelessWidget {
  final String label;
  final VoidCallback? onPressed;
  final AppButtonVariant variant;
  final AppButtonSize size;
  final Widget? icon;
  final bool isLoading;
  final bool isFullWidth;

  const AppButton({
    Key? key,
    required this.label,
    this.onPressed,
    this.variant = AppButtonVariant.primary,
    this.size = AppButtonSize.medium,
    this.icon,
    this.isLoading = false,
    this.isFullWidth = false,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: isFullWidth ? double.infinity : null,
      child: ElevatedButton(
        onPressed: isLoading ? null : onPressed,
        style: _getButtonStyle(context),
        child: isLoading 
          ? const CircularProgressIndicator(strokeWidth: 2)
          : (icon != null 
              ? Row(mainAxisSize: MainAxisSize.min, children: [icon!, const SizedBox(width: 8), Text(label)])
              : Text(label)),
      ),
    );
  }

  ButtonStyle _getButtonStyle(BuildContext context) {
    final theme = Theme.of(context);
    return ButtonStyle(
      backgroundColor: MaterialStateProperty.all(
        variant == AppButtonVariant.primary ? theme.colorScheme.primary : 
        variant == AppButtonVariant.secondary ? theme.colorScheme.secondary :
        variant == AppButtonVariant.danger ? Colors.red : Colors.transparent,
      ),
      foregroundColor: MaterialStateProperty.all(
        variant == AppButtonVariant.outline ? theme.colorScheme.primary : Colors.white,
      ),
      padding: MaterialStateProperty.all(
        size == AppButtonSize.small ? const EdgeInsets.symmetric(horizontal: 12, vertical: 8) :
        size == AppButtonSize.large ? const EdgeInsets.symmetric(horizontal: 24, vertical: 16) :
        const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
      ),
      shape: MaterialStateProperty.all(
        RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }
}
```

### 3.2 AppTextField

```dart
// lib/ui/components/app_text_field.dart

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

enum AppTextFieldType { text, email, password, number, phone, multiline }

class AppTextField extends StatefulWidget {
  final String? label;
  final String? hint;
  final TextEditingController? controller;
  final AppTextFieldType type;
  final bool required;
  final Widget? prefix;
  final IconData? prefixIcon;
  final ValueChanged<String>? onChanged;
  final FormFieldValidator<String>? validator;

  const AppTextField({
    Key? key,
    this.label,
    this.hint,
    this.controller,
    this.type = AppTextFieldType.text,
    this.required = false,
    this.prefix,
    this.prefixIcon,
    this.onChanged,
    this.validator,
  }) : super(key: key);

  @override
  State<AppTextField> createState() => _AppTextFieldState();
}

class _AppTextFieldState extends State<AppTextField> {
  bool _obscureText = false;

  @override
  void initState() {
    super.initState();
    _obscureText = widget.type == AppTextFieldType.password;
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (widget.label != null)
          Row(children: [
            Text(widget.label!, style: Theme.of(context).textTheme.labelLarge),
            if (widget.required) Text(' *', style: TextStyle(color: Colors.red)),
          ]),
        const SizedBox(height: 6),
        TextFormField(
          controller: widget.controller,
          obscureText: _obscureText,
          keyboardType: _getKeyboardType(),
          maxLines: widget.type == AppTextFieldType.multiline ? 4 : 1,
          decoration: InputDecoration(
            hintText: widget.hint,
            prefixIcon: widget.prefixIcon != null ? Icon(widget.prefixIcon) : widget.prefix,
            suffixIcon: widget.type == AppTextFieldType.password
              ? IconButton(
                  icon: Icon(_obscureText ? Icons.visibility_off : Icons.visibility),
                  onPressed: () => setState(() => _obscureText = !_obscureText),
                )
              : null,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
          onChanged: widget.onChanged,
          validator: widget.validator ?? _defaultValidator,
        ),
      ],
    );
  }

  TextInputType _getKeyboardType() {
    switch (widget.type) {
      case AppTextFieldType.email: return TextInputType.emailAddress;
      case AppTextFieldType.number:
      case AppTextFieldType.phone: return TextInputType.number;
      case AppTextFieldType.multiline: return TextInputType.multiline;
      default: return TextInputType.text;
    }
  }

  String? _defaultValidator(String? value) {
    if (widget.required && (value == null || value.isEmpty)) {
      return 'This field is required';
    }
    if (widget.type == AppTextFieldType.email && value != null && value.isNotEmpty) {
      final emailRegex = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
      if (!emailRegex.hasMatch(value)) return 'Please enter a valid email';
    }
    if (widget.type == AppTextFieldType.phone && value != null && value.isNotEmpty) {
      final phoneRegex = RegExp(r'^01[3-9]\d{8}$');
      if (!phoneRegex.hasMatch(value)) return 'Please enter a valid BD phone number';
    }
    return null;
  }
}
```

### 3.3 AppCard

```dart
// lib/ui/components/app_card.dart

import 'package:flutter/material.dart';

enum AppCardVariant { elevated, outlined, filled }

class AppCard extends StatelessWidget {
  final Widget child;
  final AppCardVariant variant;
  final EdgeInsetsGeometry? padding;
  final VoidCallback? onTap;
  final String? title;
  final List<Widget>? actions;

  const AppCard({
    Key? key,
    required this.child,
    this.variant = AppCardVariant.elevated,
    this.padding,
    this.onTap,
    this.title,
    this.actions,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: variant == AppCardVariant.elevated ? 2 : 0,
      color: variant == AppCardVariant.filled 
        ? Theme.of(context).colorScheme.surfaceVariant 
        : null,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: variant == AppCardVariant.outlined
          ? BorderSide(color: Theme.of(context).colorScheme.outline)
          : BorderSide.none,
      ),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (title != null)
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 0),
                child: Text(title!, style: Theme.of(context).textTheme.titleMedium),
              ),
            Padding(padding: padding ?? const EdgeInsets.all(16), child: child),
            if (actions != null)
              Padding(
                padding: const EdgeInsets.fromLTRB(8, 0, 8, 8),
                child: Row(mainAxisAlignment: MainAxisAlignment.end, children: actions!),
              ),
          ],
        ),
      ),
    );
  }
}
```

### 3.4 AppListTile

```dart
// lib/ui/components/app_list_tile.dart

import 'package:flutter/material.dart';

class AppListTile extends StatelessWidget {
  final String title;
  final String? subtitle;
  final IconData? leadingIcon;
  final Widget? trailing;
  final VoidCallback? onTap;
  final bool selected;

  const AppListTile({
    Key? key,
    required this.title,
    this.subtitle,
    this.leadingIcon,
    this.trailing,
    this.onTap,
    this.selected = false,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: leadingIcon != null 
        ? Container(
            width: 48,
            height: 48,
            decoration: BoxDecoration(
              color: Theme.of(context).colorScheme.primaryContainer,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(leadingIcon, color: Theme.of(context).colorScheme.primary),
          )
        : null,
      title: Text(title, style: const TextStyle(fontWeight: FontWeight.w500)),
      subtitle: subtitle != null ? Text(subtitle!) : null,
      trailing: trailing ?? const Icon(Icons.chevron_right),
      selected: selected,
      onTap: onTap,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
    );
  }
}
```

### 3.5 AppDropdown

```dart
// lib/ui/components/app_dropdown.dart

import 'package:flutter/material.dart';

class DropdownItem<T> {
  final T value;
  final String label;
  DropdownItem({required this.value, required this.label});
}

class AppDropdown<T> extends StatelessWidget {
  final String? label;
  final String? hint;
  final List<DropdownItem<T>> items;
  final T? value;
  final ValueChanged<T?>? onChanged;
  final bool required;

  const AppDropdown({
    Key? key,
    this.label,
    this.hint,
    required this.items,
    this.value,
    this.onChanged,
    this.required = false,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (label != null)
          Row(children: [
            Text(label!, style: Theme.of(context).textTheme.labelLarge),
            if (required) const Text(' *', style: TextStyle(color: Colors.red)),
          ]),
        const SizedBox(height: 6),
        DropdownButtonFormField<T>(
          value: value,
          hint: hint != null ? Text(hint!) : null,
          items: items.map((item) => 
            DropdownMenuItem<T>(
              value: item.value,
              child: Text(item.label),
            )
          ).toList(),
          onChanged: onChanged,
          decoration: InputDecoration(
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
      ],
    );
  }
}
```

### 3.6 AppDatePicker

```dart
// lib/ui/components/app_date_picker.dart

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class AppDatePicker extends StatefulWidget {
  final String? label;
  final DateTime? initialDate;
  final ValueChanged<DateTime?>? onChanged;
  final bool required;

  const AppDatePicker({
    Key? key,
    this.label,
    this.initialDate,
    this.onChanged,
    this.required = false,
  }) : super(key: key);

  @override
  State<AppDatePicker> createState() => _AppDatePickerState();
}

class _AppDatePickerState extends State<AppDatePicker> {
  DateTime? _selectedDate;
  final _controller = TextEditingController();

  @override
  void initState() {
    super.initState();
    _selectedDate = widget.initialDate;
    if (_selectedDate != null) {
      _controller.text = DateFormat('dd/MM/yyyy').format(_selectedDate!);
    }
  }

  Future<void> _pickDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate ?? DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2100),
    );
    if (picked != null) {
      setState(() {
        _selectedDate = picked;
        _controller.text = DateFormat('dd/MM/yyyy').format(picked);
      });
      widget.onChanged?.call(picked);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (widget.label != null)
          Row(children: [
            Text(widget.label!, style: Theme.of(context).textTheme.labelLarge),
            if (widget.required) const Text(' *', style: TextStyle(color: Colors.red)),
          ]),
        const SizedBox(height: 6),
        TextFormField(
          controller: _controller,
          readOnly: true,
          onTap: _pickDate,
          decoration: InputDecoration(
            hintText: 'DD/MM/YYYY',
            prefixIcon: const Icon(Icons.calendar_today),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
      ],
    );
  }
}
```

### 3.7 AppSearchBar

```dart
// lib/ui/components/app_search_bar.dart

import 'package:flutter/material.dart';

class AppSearchBar extends StatelessWidget {
  final String? hint;
  final ValueChanged<String>? onChanged;
  final VoidCallback? onFilterTap;

  const AppSearchBar({
    Key? key,
    this.hint,
    this.onChanged,
    this.onFilterTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Theme.of(context).colorScheme.surfaceVariant,
        borderRadius: BorderRadius.circular(28),
      ),
      child: Row(
        children: [
          const SizedBox(width: 16),
          const Icon(Icons.search),
          const SizedBox(width: 12),
          Expanded(
            child: TextField(
              decoration: InputDecoration(
                hintText: hint ?? 'Search...',
                border: InputBorder.none,
              ),
              onChanged: onChanged,
            ),
          ),
          if (onFilterTap != null)
            IconButton(icon: const Icon(Icons.tune), onPressed: onFilterTap),
        ],
      ),
    );
  }
}
```

### 3.8 AppBottomNav

```dart
// lib/ui/components/app_bottom_nav.dart

import 'package:flutter/material.dart';

class NavItem {
  final String label;
  final IconData icon;
  final int? badgeCount;

  NavItem({required this.label, required this.icon, this.badgeCount});
}

class AppBottomNav extends StatelessWidget {
  final List<NavItem> items;
  final int currentIndex;
  final ValueChanged<int> onTap;

  const AppBottomNav({
    Key? key,
    required this.items,
    required this.currentIndex,
    required this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return NavigationBar(
      selectedIndex: currentIndex,
      onDestinationSelected: onTap,
      destinations: items.map((item) => NavigationDestination(
        icon: item.badgeCount != null && item.badgeCount! > 0
          ? Badge(
              label: Text(item.badgeCount.toString()),
              child: Icon(item.icon),
            )
          : Icon(item.icon),
        selectedIcon: Icon(item.icon),
        label: item.label,
      )).toList(),
    );
  }
}
```

### 3.9 AppAppBar

```dart
// lib/ui/components/app_app_bar.dart

import 'package:flutter/material.dart';

class AppAppBar extends StatelessWidget implements PreferredSizeWidget {
  final String? title;
  final List<Widget>? actions;
  final bool showBackButton;
  final VoidCallback? onBackPressed;

  const AppAppBar({
    Key? key,
    this.title,
    this.actions,
    this.showBackButton = true,
    this.onBackPressed,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: title != null ? Text(title!) : null,
      leading: showBackButton && Navigator.canPop(context)
        ? IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: onBackPressed ?? () => Navigator.pop(context),
          )
        : null,
      actions: actions,
      centerTitle: true,
      elevation: 0,
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}
```

---

## 4. Smart Dairy Specific Components

### 4.1 AnimalCard

```dart
// lib/ui/components/animal_card.dart

import 'package:flutter/material.dart';

enum AnimalStatus { healthy, sick, pregnant, lactating, dry, sold }

class AnimalCard extends StatelessWidget {
  final String animalId;
  final String? name;
  final String breed;
  final AnimalStatus status;
  final int? ageMonths;
  final double? currentMilkYield;
  final VoidCallback? onTap;

  const AnimalCard({
    Key? key,
    required this.animalId,
    this.name,
    required this.breed,
    required this.status,
    this.ageMonths,
    this.currentMilkYield,
    this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Stack(
              children: [
                Container(
                  height: 140,
                  decoration: BoxDecoration(
                    color: Theme.of(context).colorScheme.primaryContainer,
                    borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                  ),
                  child: const Center(child: Icon(Icons.pets, size: 64)),
                ),
                Positioned(
                  top: 12,
                  left: 12,
                  child: _buildStatusBadge(),
                ),
              ],
            ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name ?? 'Animal #$animalId',
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w600),
                  ),
                  Text(breed, style: Theme.of(context).textTheme.bodyMedium),
                  if (currentMilkYield != null)
                    Text('${currentMilkYield!.toStringAsFixed(1)} L/day'),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatusBadge() {
    final colors = {
      AnimalStatus.healthy: Colors.green,
      AnimalStatus.sick: Colors.red,
      AnimalStatus.pregnant: Colors.purple,
      AnimalStatus.lactating: Colors.blue,
      AnimalStatus.dry: Colors.orange,
      AnimalStatus.sold: Colors.grey,
    };
    
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: colors[status]?.withOpacity(0.9),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        status.name.toUpperCase(),
        style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600),
      ),
    );
  }
}
```

### 4.2 MilkRecordTile

```dart
// lib/ui/components/milk_record_tile.dart

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

enum MilkQuality { excellent, good, average, poor }

class MilkRecordTile extends StatelessWidget {
  final DateTime collectionTime;
  final double quantityLiters;
  final MilkQuality quality;
  final String? shift;
  final double? fatPercentage;

  const MilkRecordTile({
    Key? key,
    required this.collectionTime,
    required this.quantityLiters,
    required this.quality,
    this.shift,
    this.fatPercentage,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final isMorning = shift?.toLowerCase() == 'morning';
    
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      child: ListTile(
        leading: Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: (isMorning ? Colors.amber : Colors.indigo).withOpacity(0.15),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(isMorning ? Icons.wb_sunny : Icons.nights_stay,
            color: isMorning ? Colors.amber.shade700 : Colors.indigo),
        ),
        title: Row(
          children: [
            Text('${quantityLiters.toStringAsFixed(1)} L',
              style: const TextStyle(fontWeight: FontWeight.w700)),
            const SizedBox(width: 8),
            _buildQualityBadge(),
          ],
        ),
        subtitle: Text(DateFormat('dd MMM, h:mm a').format(collectionTime)),
        trailing: fatPercentage != null 
          ? Text('Fat: ${fatPercentage!.toStringAsFixed(1)}%')
          : null,
      ),
    );
  }

  Widget _buildQualityBadge() {
    final colors = {
      MilkQuality.excellent: Colors.green,
      MilkQuality.good: Colors.blue,
      MilkQuality.average: Colors.orange,
      MilkQuality.poor: Colors.red,
    };
    final labels = {
      MilkQuality.excellent: 'A+',
      MilkQuality.good: 'A',
      MilkQuality.average: 'B',
      MilkQuality.poor: 'C',
    };
    
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        color: colors[quality]?.withOpacity(0.15),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(labels[quality]!, style: TextStyle(
        color: colors[quality],
        fontSize: 12,
        fontWeight: FontWeight.w600,
      )),
    );
  }
}
```

### 4.3 OrderSummaryCard

```dart
// lib/ui/components/order_summary_card.dart

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

enum OrderStatus { pending, confirmed, processing, delivered, cancelled }

class OrderSummaryCard extends StatelessWidget {
  final String orderId;
  final DateTime orderDate;
  final OrderStatus status;
  final int itemCount;
  final double totalAmount;
  final VoidCallback? onTap;

  const OrderSummaryCard({
    Key? key,
    required this.orderId,
    required this.orderDate,
    required this.status,
    required this.itemCount,
    required this.totalAmount,
    this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('Order #$orderId', style: Theme.of(context).textTheme.titleMedium),
                  _buildStatusBadge(),
                ],
              ),
              Text(DateFormat('dd MMM yyyy').format(orderDate)),
              const Divider(),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('$itemCount items'),
                  Text('৳${totalAmount.toStringAsFixed(2)}',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatusBadge() {
    final colors = {
      OrderStatus.pending: Colors.orange,
      OrderStatus.confirmed: Colors.blue,
      OrderStatus.processing: Colors.purple,
      OrderStatus.delivered: Colors.green,
      OrderStatus.cancelled: Colors.red,
    };
    
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: colors[status]?.withOpacity(0.15),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(status.name.toUpperCase(), style: TextStyle(
        color: colors[status],
        fontSize: 12,
        fontWeight: FontWeight.w600,
      )),
    );
  }
}
```

### 4.4 PaymentMethodTile

```dart
// lib/ui/components/payment_method_tile.dart

import 'package:flutter/material.dart';

enum PaymentMethod { bKash, nagad, rocket, card, cod }

class PaymentMethodTile extends StatelessWidget {
  final PaymentMethod method;
  final bool isSelected;
  final VoidCallback? onTap;

  const PaymentMethodTile({
    Key? key,
    required this.method,
    this.isSelected = false,
    this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final config = _getConfig();
    
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(
          color: isSelected ? config.color : Colors.transparent,
          width: 2,
        ),
      ),
      child: ListTile(
        leading: Container(
          width: 48,
          height: 48,
          decoration: BoxDecoration(
            color: config.color.withOpacity(0.15),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(config.icon, color: config.color),
        ),
        title: Text(config.name),
        trailing: isSelected 
          ? Icon(Icons.check_circle, color: config.color)
          : const Icon(Icons.circle_outlined),
        onTap: onTap,
      ),
    );
  }

  _Config _getConfig() {
    switch (method) {
      case PaymentMethod.bKash:
        return _Config('bKash', const Color(0xFFE2136E), Icons.account_balance_wallet);
      case PaymentMethod.nagad:
        return _Config('Nagad', const Color(0xFFF7941D), Icons.payment);
      case PaymentMethod.rocket:
        return _Config('Rocket', const Color(0xFF8E24AA), Icons.rocket);
      case PaymentMethod.card:
        return _Config('Credit/Debit Card', Colors.blue, Icons.credit_card);
      case PaymentMethod.cod:
        return _Config('Cash on Delivery', Colors.green, Icons.money);
    }
  }
}

class _Config {
  final String name;
  final Color color;
  final IconData icon;
  _Config(this.name, this.color, this.icon);
}
```

### 4.5 NotificationBadge

```dart
// lib/ui/components/notification_badge.dart

import 'package:flutter/material.dart';

class NotificationBadge extends StatelessWidget {
  final int count;
  final Widget child;

  const NotificationBadge({Key? key, required this.count, required this.child}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    if (count <= 0) return child;
    
    return Stack(
      clipBehavior: Clip.none,
      children: [
        child,
        Positioned(
          top: -8,
          right: -8,
          child: Container(
            padding: const EdgeInsets.all(4),
            decoration: BoxDecoration(
              color: Theme.of(context).colorScheme.error,
              shape: BoxShape.circle,
            ),
            constraints: const BoxConstraints(minWidth: 20, minHeight: 20),
            child: Text(
              count > 99 ? '99+' : count.toString(),
              style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
          ),
        ),
      ],
    );
  }
}
```

### 4.6 LoadingShimmer

```dart
// lib/ui/components/loading_shimmer.dart

import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';

class LoadingShimmer extends StatelessWidget {
  final Widget child;
  final bool isLoading;

  const LoadingShimmer({Key? key, required this.child, this.isLoading = true}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    if (!isLoading) return child;
    
    return Shimmer.fromColors(
      baseColor: Colors.grey.shade300,
      highlightColor: Colors.grey.shade100,
      child: child,
    );
  }
}
```

### 4.7 EmptyStateWidget

```dart
// lib/ui/components/empty_state_widget.dart

import 'package:flutter/material.dart';

class EmptyStateWidget extends StatelessWidget {
  final IconData icon;
  final String title;
  final String? subtitle;
  final String? actionLabel;
  final VoidCallback? onAction;

  const EmptyStateWidget({
    Key? key,
    required this.icon,
    required this.title,
    this.subtitle,
    this.actionLabel,
    this.onAction,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 80, color: Theme.of(context).colorScheme.primary),
            const SizedBox(height: 24),
            Text(title, style: Theme.of(context).textTheme.headlineSmall),
            if (subtitle != null) ...[
              const SizedBox(height: 12),
              Text(subtitle!, textAlign: TextAlign.center),
            ],
            if (actionLabel != null && onAction != null) ...[
              const SizedBox(height: 24),
              ElevatedButton(onPressed: onAction, child: Text(actionLabel!)),
            ],
          ],
        ),
      ),
    );
  }
}
```

### 4.8 ErrorStateWidget

```dart
// lib/ui/components/error_state_widget.dart

import 'package:flutter/material.dart';

class ErrorStateWidget extends StatelessWidget {
  final String? title;
  final String? message;
  final VoidCallback? onRetry;

  const ErrorStateWidget({Key? key, this.title, this.message, this.onRetry}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.error_outline, size: 80, color: Theme.of(context).colorScheme.error),
            const SizedBox(height: 24),
            Text(title ?? 'Something went wrong', style: Theme.of(context).textTheme.headlineSmall),
            if (message != null) ...[
              const SizedBox(height: 12),
              Text(message!, textAlign: TextAlign.center),
            ],
            if (onRetry != null) ...[
              const SizedBox(height: 24),
              ElevatedButton(onPressed: onRetry, child: const Text('Try Again')),
            ],
          ],
        ),
      ),
    );
  }
}
```

### 4.9 OfflineBanner

```dart
// lib/ui/components/offline_banner.dart

import 'package:flutter/material.dart';

class OfflineBanner extends StatelessWidget {
  final bool isOffline;
  final VoidCallback? onRetry;

  const OfflineBanner({Key? key, required this.isOffline, this.onRetry}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    if (!isOffline) return const SizedBox.shrink();
    
    return Material(
      color: Colors.orange.shade800,
      child: SafeArea(
        bottom: false,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          child: Row(
            children: [
              const Icon(Icons.wifi_off, color: Colors.white),
              const SizedBox(width: 12),
              const Expanded(
                child: Text('You\'re offline', style: TextStyle(color: Colors.white)),
              ),
              if (onRetry != null)
                TextButton(
                  onPressed: onRetry,
                  child: const Text('RETRY', style: TextStyle(color: Colors.white)),
                ),
            ],
          ),
        ),
      ),
    );
  }
}
```

---

## 5. Theme Configuration

```dart
// lib/ui/theme/app_theme.dart

import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class SmartDairyTheme {
  static const Color primaryColor = Color(0xFF1B5E20);
  static const Color secondaryColor = Color(0xFF5D4037);

  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: primaryColor,
        brightness: Brightness.light,
      ),
      textTheme: GoogleFonts.robotoTextTheme(),
      appBarTheme: const AppBarTheme(
        elevation: 0,
        centerTitle: true,
      ),
      cardTheme: CardTheme(
        elevation: 2,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      ),
      inputDecorationTheme: InputDecorationTheme(
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        filled: true,
      ),
    );
  }

  static ThemeData get darkTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: primaryColor,
        brightness: Brightness.dark,
      ),
      textTheme: GoogleFonts.robotoTextTheme(ThemeData.dark().textTheme),
    );
  }
}
```

---

## 6. Responsive Layouts

```dart
// lib/ui/layouts/responsive_layout.dart

import 'package:flutter/material.dart';

class Breakpoints {
  static const double mobile = 600;
  static const double tablet = 900;

  static bool isMobile(BuildContext context) =>
      MediaQuery.of(context).size.width < mobile;

  static bool isTablet(BuildContext context) =>
      MediaQuery.of(context).size.width >= mobile;
}

class ResponsiveLayout extends StatelessWidget {
  final Widget mobile;
  final Widget? tablet;

  const ResponsiveLayout({Key? key, required this.mobile, this.tablet}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        if (constraints.maxWidth >= Breakpoints.mobile && tablet != null) {
          return tablet!;
        }
        return mobile;
      },
    );
  }
}
```

---

## 7. Animation Components

```dart
// lib/ui/animations/app_animations.dart

import 'package:flutter/material.dart';

class FadeIn extends StatelessWidget {
  final Widget child;
  final Duration duration;

  const FadeIn({Key? key, required this.child, this.duration = const Duration(milliseconds: 300)}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return TweenAnimationBuilder<double>(
      tween: Tween(begin: 0, end: 1),
      duration: duration,
      builder: (context, value, child) => Opacity(opacity: value, child: child),
      child: child,
    );
  }
}

class SlideIn extends StatelessWidget {
  final Widget child;
  final Offset beginOffset;

  const SlideIn({Key? key, required this.child, this.beginOffset = const Offset(0, 0.2)}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return TweenAnimationBuilder<Offset>(
      tween: Tween(begin: beginOffset, end: Offset.zero),
      duration: const Duration(milliseconds: 300),
      curve: Curves.easeOut,
      builder: (context, value, child) => FractionalTranslation(translation: value, child: child),
      child: child,
    );
  }
}
```

---

## 8. Icon System

```dart
// lib/ui/icons/app_icons.dart

import 'package:flutter/material.dart';

class AppIcons {
  // Navigation
  static const IconData home = Icons.home_outlined;
  static const IconData animals = Icons.pets_outlined;
  static const IconData orders = Icons.shopping_bag_outlined;
  static const IconData profile = Icons.person_outline;

  // Actions
  static const IconData add = Icons.add;
  static const IconData edit = Icons.edit_outlined;
  static const IconData delete = Icons.delete_outline;
  static const IconData search = Icons.search;

  // Status
  static const IconData success = Icons.check_circle;
  static const IconData error = Icons.error_outline;
  static const IconData warning = Icons.warning_amber;
}
```

---

## 9. Usage Examples

### Login Screen

```dart
class LoginScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: EdgeInsets.all(24),
        child: Column(
          children: [
            AppTextField(
              label: 'Phone Number',
              type: AppTextFieldType.phone,
              prefixIcon: Icons.phone,
              required: true,
            ),
            SizedBox(height: 16),
            AppTextField(
              label: 'Password',
              type: AppTextFieldType.password,
              prefixIcon: Icons.lock,
              required: true,
            ),
            SizedBox(height: 24),
            AppButton(
              label: 'Login',
              isFullWidth: true,
              onPressed: () {},
            ),
          ],
        ),
      ),
    );
  }
}
```

---

## 10. Testing Components

```dart
// test/components/app_button_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy/ui/components/app_button.dart';

void main() {
  testWidgets('AppButton renders label correctly', (tester) async {
    await tester.pumpWidget(
      MaterialApp(
        home: AppButton(label: 'Test', onPressed: () {}),
      ),
    );
    expect(find.text('Test'), findsOneWidget);
  });
}
```

---

## 11. Documentation

The UI component library includes Storybook integration for visual documentation and testing of all components in isolation.

---

## 12. Appendices

### Appendix A: Component Checklist

| Component | Implemented | Tested | Documented |
|-----------|-------------|--------|------------|
| AppButton | ✅ | ✅ | ✅ |
| AppTextField | ✅ | ✅ | ✅ |
| AppCard | ✅ | ✅ | ✅ |
| AppListTile | ✅ | ✅ | ✅ |
| AppDropdown | ✅ | ✅ | ✅ |
| AppDatePicker | ✅ | ✅ | ✅ |
| AppSearchBar | ✅ | ✅ | ✅ |
| AppBottomNav | ✅ | ✅ | ✅ |
| AppAppBar | ✅ | ✅ | ✅ |
| AnimalCard | ✅ | ✅ | ✅ |
| MilkRecordTile | ✅ | ✅ | ✅ |
| OrderSummaryCard | ✅ | ✅ | ✅ |
| PaymentMethodTile | ✅ | ✅ | ✅ |
| NotificationBadge | ✅ | ✅ | ✅ |
| LoadingShimmer | ✅ | ✅ | ✅ |
| EmptyStateWidget | ✅ | ✅ | ✅ |
| ErrorStateWidget | ✅ | ✅ | ✅ |
| OfflineBanner | ✅ | ✅ | ✅ |

### Appendix B: Color Palette

| Color Name | Hex Code |
|------------|----------|
| Primary | #1B5E20 |
| Primary Light | #4C8C4A |
| Secondary | #5D4037 |
| Success | #2E7D32 |
| Warning | #F57C00 |
| Error | #C62828 |
| Info | #1565C0 |
| bKash | #E2136E |
| Nagad | #F7941D |
| Rocket | #8E24AA |

### Appendix C: Dependencies

```yaml
dependencies:
  flutter:
    sdk: flutter
  google_fonts: ^6.1.0
  intl: ^0.18.1
  shimmer: ^3.0.0
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | UI/UX Lead | ____________ | 2026-01-31 |
| Reviewer | Tech Lead | ____________ | ____________ |
| Approver | Mobile Lead | ____________ | ____________ |

---

*End of Document H-003: Mobile UI Component Library*
