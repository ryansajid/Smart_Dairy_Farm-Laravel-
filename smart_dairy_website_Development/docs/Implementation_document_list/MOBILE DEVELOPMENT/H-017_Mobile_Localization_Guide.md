# H-017: Mobile Localization Guide

---

## Document Information

| Field | Details |
|-------|---------|
| **Document ID** | H-017 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Product Manager |
| **Status** | Draft |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Supported Languages](#2-supported-languages)
3. [Flutter Localization Setup](#3-flutter-localization-setup)
4. [ARB Files](#4-arb-files)
5. [Translation Management](#5-translation-management)
6. [String Externalization](#6-string-externalization)
7. [Text Direction](#7-text-direction)
8. [Date & Time Formatting](#8-date--time-formatting)
9. [Number Formatting](#9-number-formatting)
10. [Currency Formatting](#10-currency-formatting)
11. [Pluralization](#11-pluralization)
12. [Gender](#12-gender)
13. [Cultural Adaptation](#13-cultural-adaptation)
14. [Voice Input](#14-voice-input)
15. [Testing Localization](#15-testing-localization)
16. [Right-to-Left (RTL)](#16-right-to-left-rtl)
17. [Maintenance](#17-maintenance)
18. [Appendices](#18-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for implementing localization in Smart Dairy mobile applications. It ensures consistent, culturally appropriate, and linguistically accurate user experiences across all supported languages and regions.

### 1.2 Localization Scope

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     SMART DAIRY MOBILE LOCALIZATION SCOPE                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  LANGUAGES                                                                   │
│  ├── English (en) - Primary/Default                                         │
│  └── Bengali/Bangla (bn) - Secondary (Localized)                            │
│                                                                              │
│  TARGET APPS                                                                 │
│  ├── Customer Mobile App (B2C)                                              │
│  ├── Farmer Mobile App (Farm Management)                                    │
│  └── Field Sales App (B2B)                                                  │
│                                                                              │
│  LOCALIZATION DOMAINS                                                        │
│  ├── UI Strings & Labels                                                    │
│  ├── Date/Time Formats (Bangladesh: DD/MM/YYYY)                            │
│  ├── Number Formats (English numerals preferred)                           │
│  ├── Currency (BDT - Bangladeshi Taka ৳)                                   │
│  ├── Measurement Units (Liters, Kilograms, Celsius)                        │
│  └── Farm Terminology (Bengali translations)                               │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.3 Localization Principles

| Principle | Description | Implementation |
|-----------|-------------|----------------|
| **Complete Coverage** | All user-facing text must be localized | No hardcoded strings |
| **Context Preservation** | Translations maintain original meaning | Context notes for translators |
| **Cultural Appropriateness** | Content adapted for local customs | Bengali month names, formats |
| **Consistency** | Same terms used throughout | Glossary enforcement |
| **Maintainability** | Easy updates and additions | Structured ARB files |

---

## 2. Supported Languages

### 2.1 Language Configuration

| Language | Code | Status | Default | Script |
|----------|------|--------|---------|--------|
| English | `en` | Primary | Yes | Latin |
| Bengali/Bangla | `bn` | Secondary | No | Bengali (Bangla) |

### 2.2 Language Selection Logic

```dart
// lib/core/localization/language_config.dart

class LanguageConfig {
  static const List<Locale> supportedLocales = [
    Locale('en'),        // English - Primary
    Locale('bn'),        // Bengali - Secondary
  ];

  static const Locale defaultLocale = Locale('en');

  // Language priority for new users
  static Locale getDeviceLocale(Locale? deviceLocale) {
    if (deviceLocale == null) return defaultLocale;
    
    // Check for exact match
    for (var locale in supportedLocales) {
      if (locale.languageCode == deviceLocale.languageCode) {
        return locale;
      }
    }
    
    return defaultLocale;
  }

  // Language display names
  static String getDisplayName(String languageCode, BuildContext context) {
    switch (languageCode) {
      case 'en':
        return 'English';
      case 'bn':
        return 'বাংলা (Bengali)';
      default:
        return 'Unknown';
    }
  }
}
```

### 2.3 Locale Resolution

```dart
// lib/core/localization/locale_resolution.dart

class LocaleResolution {
  static Locale? callback(Locale? locale, Iterable<Locale> supportedLocales) {
    // Return null to use system default
    if (locale == null) return null;

    // Check for exact match (language + country)
    for (var supportedLocale in supportedLocales) {
      if (supportedLocale.languageCode == locale.languageCode &&
          supportedLocale.countryCode == locale.countryCode) {
        return supportedLocale;
      }
    }

    // Check for language match only
    for (var supportedLocale in supportedLocales) {
      if (supportedLocale.languageCode == locale.languageCode) {
        return supportedLocale;
      }
    }

    // Return default
    return LanguageConfig.defaultLocale;
  }
}
```

---

## 3. Flutter Localization Setup

### 3.1 Dependencies

Add the following dependencies to `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  
  # Localization
  flutter_localizations:
    sdk: flutter
  intl: ^0.19.0
  
  # Additional utilities
  flutter_bloc: ^8.1.3
  shared_preferences: ^2.2.2

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  
flutter:
  uses-material-design: true
  generate: true  # Enable code generation
```

### 3.2 l10n.yaml Configuration

Create `l10n.yaml` in the project root:

```yaml
arb-dir: lib/l10n
template-arb-file: app_en.arb
output-localization-file: app_localizations.dart
output-class: AppLocalizations
output-dir: lib/generated/l10n
synthetic-package: false
preferred-supported-locales:
  - en
  - bn
untranslated-messages-file: untranslated_messages.txt
nullable-getter: false
```

### 3.3 MaterialApp Configuration

```dart
// lib/app.dart
import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:smart_dairy/generated/l10n/app_localizations.dart';
import 'package:smart_dairy/core/localization/language_config.dart';
import 'package:smart_dairy/core/localization/locale_bloc.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class SmartDairyApp extends StatelessWidget {
  const SmartDairyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<LocaleBloc, LocaleState>(
      builder: (context, state) {
        return MaterialApp(
          title: 'Smart Dairy',
          debugShowCheckedModeBanner: false,
          
          // Localization delegates
          localizationsDelegates: const [
            AppLocalizations.delegate,
            GlobalMaterialLocalizations.delegate,
            GlobalWidgetsLocalizations.delegate,
            GlobalCupertinoLocalizations.delegate,
          ],
          
          // Supported locales
          supportedLocales: LanguageConfig.supportedLocales,
          
          // Current locale from state
          locale: state.locale,
          
          // Locale resolution
          localeResolutionCallback: LocaleResolution.callback,
          
          theme: AppTheme.lightTheme,
          darkTheme: AppTheme.darkTheme,
          home: const HomePage(),
        );
      },
    );
  }
}
```

### 3.4 Locale BLoC Implementation

```dart
// lib/core/localization/locale_bloc.dart
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:shared_preferences/shared_preferences.dart';

// Events
abstract class LocaleEvent extends Equatable {
  const LocaleEvent();

  @override
  List<Object?> get props => [];
}

class LocaleChanged extends LocaleEvent {
  final Locale locale;
  
  const LocaleChanged(this.locale);
  
  @override
  List<Object?> get props => [locale];
}

class LocaleInitialized extends LocaleEvent {
  const LocaleInitialized();
}

// States
class LocaleState extends Equatable {
  final Locale locale;
  
  const LocaleState({required this.locale});
  
  factory LocaleState.initial() => 
      const LocaleState(locale: Locale('en'));
  
  LocaleState copyWith({Locale? locale}) {
    return LocaleState(locale: locale ?? this.locale);
  }
  
  @override
  List<Object?> get props => [locale];
}

// BLoC
class LocaleBloc extends Bloc<LocaleEvent, LocaleState> {
  static const String _localeKey = 'app_locale';
  
  LocaleBloc() : super(LocaleState.initial()) {
    on<LocaleInitialized>(_onInitialized);
    on<LocaleChanged>(_onLocaleChanged);
  }

  Future<void> _onInitialized(
    LocaleInitialized event,
    Emitter<LocaleState> emit,
  ) async {
    final prefs = await SharedPreferences.getInstance();
    final savedLocale = prefs.getString(_localeKey);
    
    if (savedLocale != null) {
      emit(state.copyWith(locale: Locale(savedLocale)));
    }
  }

  Future<void> _onLocaleChanged(
    LocaleChanged event,
    Emitter<LocaleState> emit,
  ) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_localeKey, event.locale.languageCode);
    emit(state.copyWith(locale: event.locale));
  }
}
```

### 3.5 Easy Access Helper

```dart
// lib/core/localization/extensions.dart
import 'package:flutter/material.dart';
import 'package:smart_dairy/generated/l10n/app_localizations.dart';

extension AppLocalizationsExtension on BuildContext {
  AppLocalizations get l10n => AppLocalizations.of(this);
  
  // Shortcut for common strings
  String get appName => l10n.appName;
  String get welcomeMessage => l10n.welcomeMessage;
}
```

---

## 4. ARB Files

### 4.1 Directory Structure

```
lib/
├── l10n/
│   ├── app_en.arb          # English (template)
│   ├── app_bn.arb          # Bengali
│   └── README.md           # Translation guidelines
├── generated/
│   └── l10n/
│       ├── app_localizations.dart
│       ├── app_localizations_en.dart
│       └── app_localizations_bn.dart
```

### 4.2 ARB File Structure

ARB (Application Resource Bundle) files are JSON files with ICU message format support.

#### English Template (app_en.arb)

```json
{
  "@@locale": "en",
  "@@last_modified": "2026-01-31T10:00:00Z",
  "@@context": "Smart Dairy Mobile App",
  
  "appName": "Smart Dairy",
  "@appName": {
    "description": "Application name displayed in app bar and launcher"
  },
  
  "welcomeMessage": "Welcome to Smart Dairy",
  "@welcomeMessage": {
    "description": "Welcome message on home screen"
  },
  
  "loginTitle": "Login",
  "@loginTitle": {
    "description": "Login screen title"
  },
  
  "emailLabel": "Email Address",
  "@emailLabel": {
    "description": "Email input field label"
  },
  
  "passwordLabel": "Password",
  "@passwordLabel": {
    "description": "Password input field label"
  },
  
  "loginButton": "Sign In",
  "@loginButton": {
    "description": "Login button text"
  },
  
  "forgotPassword": "Forgot Password?",
  "@forgotPassword": {
    "description": "Forgot password link"
  },
  
  "homeTitle": "Home",
  "productsTitle": "Products",
  "cartTitle": "Cart",
  "profileTitle": "Profile",
  
  "addToCart": "Add to Cart",
  "checkout": "Checkout",
  "continueShopping": "Continue Shopping",
  
  "milkProduction": "Milk Production",
  "@milkProduction": {
    "description": "Farm module - milk production section"
  },
  
  "animalHealth": "Animal Health",
  "@animalHealth": {
    "description": "Farm module - animal health section"
  },
  
  "breedingRecords": "Breeding Records",
  "@breedingRecords": {
    "description": "Farm module - breeding records section"
  },
  
  "literUnit": "L",
  "@literUnit": {
    "description": "Liter unit abbreviation"
  },
  
  "kilogramUnit": "kg",
  "@kilogramUnit": {
    "description": "Kilogram unit abbreviation"
  },
  
  "saveButton": "Save",
  "cancelButton": "Cancel",
  "deleteButton": "Delete",
  "editButton": "Edit",
  "submitButton": "Submit",
  
  "errorNetwork": "Network connection failed. Please check your internet.",
  "@errorNetwork": {
    "description": "Network error message"
  },
  
  "errorGeneric": "Something went wrong. Please try again.",
  "@errorGeneric": {
    "description": "Generic error message"
  },
  
  "requiredField": "This field is required",
  "@requiredField": {
    "description": "Validation error for required field"
  },
  
  "invalidEmail": "Please enter a valid email address",
  "@invalidEmail": {
    "description": "Email validation error"
  },
  
  "itemCount": "{count, plural, =0{No items} =1{1 item} other{{count} items}}",
  "@itemCount": {
    "description": "Item count with pluralization",
    "placeholders": {
      "count": {
        "type": "int"
      }
    }
  },
  
  "priceDisplay": "{symbol}{price}",
  "@priceDisplay": {
    "description": "Price display format",
    "placeholders": {
      "symbol": {
        "type": "String"
      },
      "price": {
        "type": "String"
      }
    }
  },
  
  "lastSynced": "Last synced: {time}",
  "@lastSynced": {
    "description": "Last sync timestamp",
    "placeholders": {
      "time": {
        "type": "String"
      }
    }
  }
}
```

#### Bengali Translation (app_bn.arb)

```json
{
  "@@locale": "bn",
  "@@last_modified": "2026-01-31T10:00:00Z",
  
  "appName": "স্মার্ট ডেইরি",
  "welcomeMessage": "স্মার্ট ডেইরিতে স্বাগতম",
  "loginTitle": "লগইন",
  "emailLabel": "ইমেইল ঠিকানা",
  "passwordLabel": "পাসওয়ার্ড",
  "loginButton": "সাইন ইন",
  "forgotPassword": "পাসওয়ার্ড ভুলে গেছেন?",
  
  "homeTitle": "হোম",
  "productsTitle": "পণ্য",
  "cartTitle": "কার্ট",
  "profileTitle": "প্রোফাইল",
  
  "addToCart": "কার্টে যোগ করুন",
  "checkout": "চেকআউট",
  "continueShopping": "শপিং চালিয়ে যান",
  
  "milkProduction": "দুধ উৎপাদন",
  "animalHealth": "পশু স্বাস্থ্য",
  "breedingRecords": "প্রজনন রেকর্ড",
  
  "literUnit": "লি",
  "kilogramUnit": "কেজি",
  
  "saveButton": "সংরক্ষণ",
  "cancelButton": "বাতিল",
  "deleteButton": "মুছুন",
  "editButton": "সম্পাদনা",
  "submitButton": "জমা দিন",
  
  "errorNetwork": "নেটওয়ার্ক সংযোগ ব্যর্থ। আপনার ইন্টারনেট চেক করুন।",
  "errorGeneric": "কিছু ভুল হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।",
  "requiredField": "এই ক্ষেত্রটি পূরণ করা আবশ্যক",
  "invalidEmail": "অনুগ্রহ করে একটি বৈধ ইমেইল ঠিকানা লিখুন",
  
  "itemCount": "{count, plural, =0{কোন আইটেম নেই} =1{1 আইটেম} other{{count} আইটেম}}",
  "priceDisplay": "{symbol}{price}",
  "lastSynced": "সর্বশেষ সিঙ্ক: {time}"
}
```

### 4.3 ARB File Organization

Group strings logically with prefixes:

| Category | Prefix | Example |
|----------|--------|---------|
| Common | `common` | `commonSave`, `commonCancel` |
| Auth | `auth` | `authLoginTitle`, `authForgotPassword` |
| Navigation | `nav` | `navHome`, `navProducts` |
| Farm | `farm` | `farmMilkProduction`, `farmAnimalHealth` |
| E-commerce | `shop` | `shopAddToCart`, `shopCheckout` |
| Errors | `error` | `errorNetwork`, `errorGeneric` |
| Validation | `val` | `valRequired`, `valInvalidEmail` |
| Notifications | `notif` | `notifOrderConfirmed` |

### 4.4 Code Generation

Run the following command to generate localization files:

```bash
flutter gen-l10n
```

This generates:
- `app_localizations.dart` - Abstract class with all strings
- `app_localizations_en.dart` - English implementation
- `app_localizations_bn.dart` - Bengali implementation

---

## 5. Translation Management

### 5.1 Workflow Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    TRANSLATION WORKFLOW                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  DEVELOPER                    MANAGER/PM                    TRANSLATOR       │
│     │                              │                              │          │
│     │  1. Add strings to           │                              │          │
│     │     app_en.arb               │                              │          │
│     │──────────────>│              │                              │          │
│     │               │              │  2. Review & Export          │          │
│     │               │─────────────>│     to Google Sheets         │          │
│     │               │              │──────────────>│              │          │
│     │               │              │               │  3. Translate│          │
│     │               │              │               │     to Bengali         │
│     │               │              │               │─────────────>│         │
│     │               │              │               │              │         │
│     │               │              │  4. Import    │              │         │
│     │               │              │     translations              │         │
│     │               │              │<──────────────│              │         │
│     │  5. Update app_bn.arb        │                              │         │
│     │<──────────────│              │                              │         │
│     │               │              │                              │         │
│     │  6. Run flutter gen-l10n     │                              │         │
│     │               │              │                              │         │
│     │  7. Test & Deploy            │                              │         │
│     ▼               ▼              ▼                              ▼         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Google Sheets Integration

Use a script to export/import translations:

```dart
// tools/translation_manager.dart
import 'dart:convert';
import 'dart:io';
import 'package:gsheets/gsheets.dart';

class TranslationManager {
  static const String credentialsPath = 'credentials.json';
  static const String spreadsheetId = 'YOUR_SPREADSHEET_ID';
  
  final GSheets _gsheets;
  
  TranslationManager(this._gsheets);
  
  /// Export ARB to Google Sheets
  Future<void> exportToSheets(String arbPath, String sheetName) async {
    final file = File(arbPath);
    final content = await file.readAsString();
    final arb = json.decode(content) as Map<String, dynamic>;
    
    final ss = await _gsheets.spreadsheet(spreadsheetId);
    var sheet = ss.worksheetByTitle(sheetName);
    sheet ??= await ss.addWorksheet(sheetName);
    
    // Headers
    await sheet.values.insertRow(1, ['Key', 'English', 'Bengali', 'Context']);
    
    int row = 2;
    for (final entry in arb.entries) {
      if (entry.key.startsWith('@')) continue;
      
      final key = entry.key;
      final english = entry.value;
      final context = arb['@$key']?['description'] ?? '';
      
      await sheet.values.insertRow(row, [key, english, '', context]);
      row++;
    }
    
    print('Exported ${row - 2} strings to Google Sheets');
  }
  
  /// Import translations from Google Sheets to ARB
  Future<void> importFromSheets(String sheetName, String arbPath) async {
    final ss = await _gsheets.spreadsheet(spreadsheetId);
    final sheet = ss.worksheetByTitle(sheetName);
    
    if (sheet == null) {
      throw Exception('Sheet $sheetName not found');
    }
    
    final rows = await sheet.values.allRows();
    final Map<String, dynamic> arb = {'@@locale': 'bn'};
    
    // Skip header row
    for (int i = 1; i < rows.length; i++) {
      final row = rows[i];
      if (row.length >= 3) {
        final key = row[0];
        final bengali = row[2];
        
        if (key.isNotEmpty && bengali.isNotEmpty) {
          arb[key] = bengali;
        }
      }
    }
    
    final file = File(arbPath);
    final encoder = JsonEncoder.withIndent('  ');
    await file.writeAsString(encoder.convert(arb));
    
    print('Imported ${arb.length - 1} translations to $arbPath');
  }
}
```

### 5.3 Translation Guidelines

#### For Developers

1. **Always use context**: Provide clear `@description` for every string
2. **Keep placeholders clear**: Use descriptive names like `{userName}` not `{x}`
3. **Avoid concatenation**: Don't build sentences from parts
4. **Use ICU format**: Follow standard pluralization and gender rules

```dart
// BAD - Concatenation
Text('Hello ' + userName + '!');

// GOOD - Placeholder
// ARB: "greeting": "Hello {userName}!",
Text(l10n.greeting(userName));

// BAD - Split sentence
Text('You have ' + count + ' items in your cart');

// GOOD - Pluralization
// ARB: "cartItems": "{count, plural, =0{...} =1{...} other{...}}"
Text(l10n.cartItems(count));
```

#### For Translators

| Guideline | Description |
|-----------|-------------|
| **Length** | Keep translations similar length to English (±30%) |
| **Tone** | Use formal but friendly tone ("আপনি" not "তুমি") |
| **Consistency** | Use glossary terms consistently |
| **Context** | Consider where the text appears (button vs. dialog) |
| **Variables** | Never translate placeholders `{variable}` |
| **Punctuation** | Follow Bengali punctuation rules |

---

## 6. String Externalization

### 6.1 Extracting Hardcoded Strings

#### Audit Existing Code

```bash
# Search for hardcoded strings in Dart files
grep -r '"[^"]*"' lib/ --include="*.dart" | grep -v "import\|//\|@override\|debugPrint"

# Search for single quotes
grep -r "'[^']*'" lib/ --include="*.dart" | grep -v "import\|//"
```

#### Refactoring Pattern

```dart
// BEFORE - Hardcoded strings
class LoginPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Column(
        children: [
          TextField(
            decoration: InputDecoration(
              labelText: 'Email Address',
              hintText: 'Enter your email',
            ),
          ),
          ElevatedButton(
            onPressed: () {},
            child: Text('Sign In'),
          ),
          TextButton(
            onPressed: () {},
            child: Text('Forgot Password?'),
          ),
        ],
      ),
    );
  }
}

// AFTER - Localized strings
class LoginPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final l10n = context.l10n;
    
    return Scaffold(
      appBar: AppBar(title: Text(l10n.loginTitle)),
      body: Column(
        children: [
          TextField(
            decoration: InputDecoration(
              labelText: l10n.emailLabel,
              hintText: l10n.emailHint,
            ),
          ),
          ElevatedButton(
            onPressed: () {},
            child: Text(l10n.loginButton),
          ),
          TextButton(
            onPressed: () {},
            child: Text(l10n.forgotPassword),
          ),
        ],
      ),
    );
  }
}
```

### 6.2 Dynamic Content Localization

```dart
// lib/core/localization/content_localizer.dart

class ContentLocalizer {
  static String localizeEnum<T extends Enum>(
    BuildContext context,
    T value,
    Map<T, String> translations,
  ) {
    return translations[value] ?? value.name;
  }
  
  static String localizeOrderStatus(BuildContext context, OrderStatus status) {
    final l10n = context.l10n;
    switch (status) {
      case OrderStatus.pending:
        return l10n.statusPending;
      case OrderStatus.processing:
        return l10n.statusProcessing;
      case OrderStatus.shipped:
        return l10n.statusShipped;
      case OrderStatus.delivered:
        return l10n.statusDelivered;
      case OrderStatus.cancelled:
        return l10n.statusCancelled;
    }
  }
}
```

### 6.3 Backend-Driven Content

For dynamic content from the backend:

```dart
// API Response with localized content
class Product {
  final String id;
  final Map<String, String> name;  // {"en": "Milk", "bn": "দুধ"}
  final Map<String, String> description;
  
  String getLocalizedName(String languageCode) {
    return name[languageCode] ?? name['en'] ?? '';
  }
}

// Usage
final productName = product.getLocalizedName(Localizations.localeOf(context).languageCode);
```

---

## 7. Text Direction

### 7.1 LTR/RTL Overview

| Language | Code | Direction | Status |
|----------|------|-----------|--------|
| English | `en` | LTR | Supported |
| Bengali | `bn` | LTR | Supported |

**Note**: Bengali (Bangla) is a **Left-to-Right (LTR)** language, same as English. No RTL handling is required for current supported languages.

### 7.2 Directionality Helper

```dart
// lib/core/localization/directionality_helper.dart

class DirectionalityHelper {
  static TextDirection getTextDirection(String languageCode) {
    switch (languageCode) {
      case 'ar':
      case 'he':
      case 'ur':
        return TextDirection.rtl;
      case 'en':
      case 'bn':
      default:
        return TextDirection.ltr;
    }
  }
  
  static bool isRtl(String languageCode) {
    return getTextDirection(languageCode) == TextDirection.rtl;
  }
  
  static EdgeInsets getDirectionalEdgeInsets({
    required String languageCode,
    double start = 0,
    double top = 0,
    double end = 0,
    double bottom = 0,
  }) {
    final isRtl = DirectionalityHelper.isRtl(languageCode);
    return EdgeInsets.only(
      left: isRtl ? end : start,
      top: top,
      right: isRtl ? start : end,
      bottom: bottom,
    );
  }
}
```

### 7.3 Bidirectional Text

For mixed content (e.g., English words in Bengali text):

```dart
// lib/core/widgets/bidi_text.dart

class BidiText extends StatelessWidget {
  final String text;
  final TextStyle? style;
  final TextAlign? textAlign;
  
  const BidiText({
    super.key,
    required this.text,
    this.style,
    this.textAlign,
  });

  @override
  Widget build(BuildContext context) {
    return Directionality(
      // Auto-detect text direction
      textDirection: intl.Bidi.detectRtlDirectionality(text) 
          ? TextDirection.rtl 
          : TextDirection.ltr,
      child: Text(
        text,
        style: style,
        textAlign: textAlign,
      ),
    );
  }
}
```

---

## 8. Date & Time Formatting

### 8.1 Bangladesh Locale Format

| Format Type | Pattern | Example |
|-------------|---------|---------|
| Short Date | DD/MM/YYYY | 31/01/2026 |
| Long Date | DD MMMM YYYY | ৩১ জানুয়ারি ২০২৬ |
| Short Time | HH:mm | 14:30 |
| DateTime | DD/MM/YYYY HH:mm | 31/01/2026 14:30 |
| Month-Year | MMMM YYYY | জানুয়ারি ২০২৬ |

### 8.2 Date Formatting Implementation

```dart
// lib/core/localization/date_formatter.dart

import 'package:intl/intl.dart';

class BDDateFormatter {
  static const String localeBn = 'bn_BD';
  static const String localeEn = 'en_BD';
  
  /// Format date for Bangladesh locale
  static String formatDate(
    DateTime date, {
    String? locale,
    String? pattern,
  }) {
    final effectiveLocale = locale ?? localeEn;
    final effectivePattern = pattern ?? 'dd/MM/yyyy';
    
    final formatter = DateFormat(effectivePattern, effectiveLocale);
    return formatter.format(date);
  }
  
  /// Short date: 31/01/2026
  static String shortDate(DateTime date, {String? locale}) {
    return formatDate(date, locale: locale, pattern: 'dd/MM/yyyy');
  }
  
  /// Long date: ৩১ জানুয়ারি ২০২৬ (Bengali) or 31 January 2026 (English)
  static String longDate(DateTime date, {String? locale}) {
    return formatDate(date, locale: locale, pattern: 'dd MMMM yyyy');
  }
  
  /// Month-year: জানুয়ারি ২০২৬
  static String monthYear(DateTime date, {String? locale}) {
    return formatDate(date, locale: locale, pattern: 'MMMM yyyy');
  }
  
  /// Time: 14:30
  static String time(DateTime date, {String? locale}) {
    return formatDate(date, locale: locale, pattern: 'HH:mm');
  }
  
  /// Full datetime: 31/01/2026 14:30
  static String dateTime(DateTime date, {String? locale}) {
    return formatDate(date, locale: locale, pattern: 'dd/MM/yyyy HH:mm');
  }
  
  /// Relative time: "Today", "Yesterday", "Tomorrow"
  static String relativeDate(DateTime date, {required String locale}) {
    final now = DateTime.now();
    final today = DateTime(now.year, now.month, now.day);
    final targetDate = DateTime(date.year, date.month, date.day);
    final difference = targetDate.difference(today).inDays;
    
    if (locale == 'bn') {
      switch (difference) {
        case 0:
          return 'আজ';
        case 1:
          return 'আগামীকাল';
        case -1:
          return 'গতকাল';
        default:
          return shortDate(date, locale: locale);
      }
    } else {
      switch (difference) {
        case 0:
          return 'Today';
        case 1:
          return 'Tomorrow';
        case -1:
          return 'Yesterday';
        default:
          return shortDate(date, locale: locale);
      }
    }
  }
}
```

### 8.3 Bengali Month Names

```dart
// lib/core/localization/bengali_calendar.dart

class BengaliCalendar {
  static const List<String> englishMonths = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ];
  
  static const List<String> bengaliMonths = [
    'জানুয়ারি',
    'ফেব্রুয়ারি',
    'মার্চ',
    'এপ্রিল',
    'মে',
    'জুন',
    'জুলাই',
    'আগস্ট',
    'সেপ্টেম্বর',
    'অক্টোবর',
    'নভেম্বর',
    'ডিসেম্বর',
  ];
  
  static const List<String> bengaliDays = [
    'রবিবার',
    'সোমবার',
    'মঙ্গলবার',
    'বুধবার',
    'বৃহস্পতিবার',
    'শুক্রবার',
    'শনিবার',
  ];
  
  static String getMonthName(int month, {String locale = 'en'}) {
    if (month < 1 || month > 12) return '';
    
    if (locale == 'bn') {
      return bengaliMonths[month - 1];
    }
    return englishMonths[month - 1];
  }
  
  static String getDayName(int weekday, {String locale = 'en'}) {
    if (weekday < 1 || weekday > 7) return '';
    
    if (locale == 'bn') {
      return bengaliDays[weekday - 1];
    }
    // Return English day name
    final days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    return days[weekday - 1];
  }
}
```

### 8.4 Usage in Widgets

```dart
class DateDisplayWidget extends StatelessWidget {
  final DateTime date;
  
  const DateDisplayWidget({super.key, required this.date});

  @override
  Widget build(BuildContext context) {
    final locale = Localizations.localeOf(context).languageCode;
    
    return Column(
      children: [
        Text(
          BDDateFormatter.longDate(date, locale: locale),
          style: Theme.of(context).textTheme.titleMedium,
        ),
        Text(
          BDDateFormatter.relativeDate(date, locale: locale),
          style: Theme.of(context).textTheme.bodySmall,
        ),
      ],
    );
  }
}
```

---

## 9. Number Formatting

### 9.1 Number Format Standards

| Type | English Format | Bengali Format | Notes |
|------|---------------|----------------|-------|
| Decimal | 1,234.56 | ১,২৩৪.৫৬ | Bengali numerals option |
| Percentage | 85.5% | ৮৫.৫% | Same format |
| Phone | +880 1XXX-XXXXXX | +৮৮০ ১XXX-XXXXXX | Bengali numerals |

### 9.2 Number Formatting Implementation

```dart
// lib/core/localization/number_formatter.dart

import 'package:intl/intl.dart';

class BDNumberFormatter {
  /// Format number with thousand separators
  static String formatNumber(
    num value, {
    String? locale,
    int decimalDigits = 0,
  }) {
    final effectiveLocale = locale ?? 'en';
    final formatter = NumberFormat.decimalPattern(effectiveLocale);
    formatter.minimumFractionDigits = decimalDigits;
    formatter.maximumFractionDigits = decimalDigits;
    return formatter.format(value);
  }
  
  /// Format with Bengali numerals if needed
  static String formatNumberBn(num value, {bool useBengaliNumerals = false}) {
    final formatted = formatNumber(value, locale: 'en');
    
    if (useBengaliNumerals) {
      return _toBengaliNumerals(formatted);
    }
    return formatted;
  }
  
  /// Convert English numerals to Bengali
  static String _toBengaliNumerals(String input) {
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    
    return input.split('').map((char) {
      final digit = int.tryParse(char);
      if (digit != null && digit >= 0 && digit <= 9) {
        return bengaliDigits[digit];
      }
      return char;
    }).join();
  }
  
  /// Format percentage
  static String formatPercentage(
    num value, {
    String? locale,
    int decimalDigits = 1,
  }) {
    final effectiveLocale = locale ?? 'en';
    final formatter = NumberFormat.percentPattern(effectiveLocale);
    formatter.minimumFractionDigits = decimalDigits;
    formatter.maximumFractionDigits = decimalDigits;
    return formatter.format(value / 100);
  }
  
  /// Format measurement (Liters, Kg)
  static String formatMeasurement(
    num value,
    String unit, {
    String? locale,
    int decimalDigits = 2,
  }) {
    final numberStr = formatNumber(value, locale: locale, decimalDigits: decimalDigits);
    return '$numberStr $unit';
  }
  
  /// Parse localized number string
  static num? parseNumber(String value, {String? locale}) {
    try {
      final effectiveLocale = locale ?? 'en';
      final formatter = NumberFormat.decimalPattern(effectiveLocale);
      return formatter.parse(value);
    } catch (e) {
      return null;
    }
  }
}
```

### 9.3 Input Formatting

For number input fields (always use English numerals):

```dart
// lib/core/widgets/localized_number_field.dart

class LocalizedNumberField extends StatelessWidget {
  final String label;
  final String? unit;
  final ValueChanged<num?> onChanged;
  final num? initialValue;
  
  const LocalizedNumberField({
    super.key,
    required this.label,
    this.unit,
    required this.onChanged,
    this.initialValue,
  });

  @override
  Widget build(BuildContext context) {
    final locale = Localizations.localeOf(context).languageCode;
    final displayValue = initialValue != null 
        ? BDNumberFormatter.formatNumber(initialValue!, locale: locale)
        : '';
    
    return TextFormField(
      initialValue: displayValue,
      keyboardType: const TextInputType.numberWithOptions(decimal: true),
      decoration: InputDecoration(
        labelText: label,
        suffixText: unit,
      ),
      onChanged: (value) {
        final parsed = BDNumberFormatter.parseNumber(value, locale: locale);
        onChanged(parsed);
      },
    );
  }
}
```

---

## 10. Currency Formatting

### 10.1 BDT (Bangladeshi Taka) Format

| Format | Display | Example |
|--------|---------|---------|
| Symbol | ৳ | ৳100 |
| Code | BDT | 100 BDT |
| Full | ৳100.00 | ৳1,234.56 |

### 10.2 Currency Formatter

```dart
// lib/core/localization/currency_formatter.dart

import 'package:intl/intl.dart';

class BDCurrencyFormatter {
  static const String currencyCode = 'BDT';
  static const String currencySymbol = '৳';
  static const String currencyNameBn = 'টাকা';
  
  /// Format amount with BDT symbol
  static String format(
    num amount, {
    String? locale,
    bool showSymbol = true,
    int decimalDigits = 0,
  }) {
    final effectiveLocale = locale ?? 'en';
    
    final formatPattern = showSymbol 
        ? '$currencySymbol#,##0${decimalDigits > 0 ? '.${'0' * decimalDigits}' : ''}'
        : '#,##0${decimalDigits > 0 ? '.${'0' * decimalDigits}' : ''}';
    
    final formatter = NumberFormat(formatPattern, effectiveLocale);
    return formatter.format(amount);
  }
  
  /// Format for display in UI (৳1,234)
  static String formatForDisplay(num amount, {String? locale}) {
    return format(amount, locale: locale, showSymbol: true, decimalDigits: 0);
  }
  
  /// Format with decimals for precision (৳1,234.56)
  static String formatWithDecimals(num amount, {String? locale}) {
    return format(amount, locale: locale, showSymbol: true, decimalDigits: 2);
  }
  
  /// Format for Bengali with word (১,২৩৪ টাকা)
  static String formatBengaliWord(num amount) {
    final numberStr = BDNumberFormatter.formatNumber(amount, locale: 'bn');
    return '$numberStr $currencyNameBn';
  }
  
  /// Parse currency string to number
  static num? parse(String value) {
    // Remove currency symbol and spaces
    final cleanValue = value
        .replaceAll(currencySymbol, '')
        .replaceAll(currencyNameBn, '')
        .replaceAll('BDT', '')
        .replaceAll(',', '')
        .trim();
    
    return num.tryParse(cleanValue);
  }
}
```

### 10.3 Currency Input Field

```dart
// lib/core/widgets/currency_input_field.dart

class CurrencyInputField extends StatefulWidget {
  final String label;
  final ValueChanged<num?> onChanged;
  final num? initialValue;
  final bool allowDecimals;
  
  const CurrencyInputField({
    super.key,
    required this.label,
    required this.onChanged,
    this.initialValue,
    this.allowDecimals = false,
  });

  @override
  State<CurrencyInputField> createState() => _CurrencyInputFieldState();
}

class _CurrencyInputFieldState extends State<CurrencyInputField> {
  late TextEditingController _controller;
  
  @override
  void initState() {
    super.initState();
    _controller = TextEditingController(
      text: widget.initialValue != null 
          ? widget.initialValue.toString()
          : '',
    );
  }
  
  @override
  Widget build(BuildContext context) {
    final l10n = context.l10n;
    
    return TextFormField(
      controller: _controller,
      keyboardType: TextInputType.numberWithOptions(
        decimal: widget.allowDecimals,
      ),
      decoration: InputDecoration(
        labelText: widget.label,
        prefixText: BDCurrencyFormatter.currencySymbol,
        hintText: '${BDCurrencyFormatter.currencySymbol}0',
      ),
      validator: (value) {
        if (value == null || value.isEmpty) {
          return l10n.requiredField;
        }
        final parsed = num.tryParse(value);
        if (parsed == null || parsed < 0) {
          return l10n.invalidAmount;
        }
        return null;
      },
      onChanged: (value) {
        final parsed = num.tryParse(value);
        widget.onChanged(parsed);
      },
    );
  }
}
```

### 10.4 Price Display Widget

```dart
// lib/core/widgets/price_display.dart

class PriceDisplay extends StatelessWidget {
  final num price;
  final num? originalPrice;
  final bool showDecimals;
  final TextStyle? style;
  
  const PriceDisplay({
    super.key,
    required this.price,
    this.originalPrice,
    this.showDecimals = false,
    this.style,
  });

  @override
  Widget build(BuildContext context) {
    final locale = Localizations.localeOf(context).languageCode;
    
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Text(
          BDCurrencyFormatter.format(
            price,
            locale: locale,
            decimalDigits: showDecimals ? 2 : 0,
          ),
          style: style ?? Theme.of(context).textTheme.titleMedium?.copyWith(
            fontWeight: FontWeight.bold,
            color: Theme.of(context).primaryColor,
          ),
        ),
        if (originalPrice != null && originalPrice! > price) ...[
          const SizedBox(width: 8),
          Text(
            BDCurrencyFormatter.format(
              originalPrice!,
              locale: locale,
              decimalDigits: showDecimals ? 2 : 0,
            ),
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              decoration: TextDecoration.lineThrough,
              color: Colors.grey,
            ),
          ),
        ],
      ],
    );
  }
}
```

---

## 11. Pluralization

### 11.1 Bengali Plural Rules

Bengali follows similar plural rules to English:
- `one`: For exactly 1
- `other`: For 0, 2, and all other numbers

### 11.2 ARB Plural Definitions

```json
{
  "itemCount": "{count, plural, =0{No items} =1{1 item} other{{count} items}}",
  "@itemCount": {
    "description": "Item count with pluralization",
    "placeholders": {
      "count": {
        "type": "int"
      }
    }
  },
  
  "cattleCount": "{count, plural, =0{No cattle} =1{1 cattle} other{{count} cattle}}",
  "@cattleCount": {
    "description": "Cattle count",
    "placeholders": {
      "count": {"type": "int"}
    }
  },
  
  "litersProduced": "{liters, plural, =0{No liters} =1{1 liter} other{{liters} liters}}",
  "@litersProduced": {
    "description": "Milk production amount",
    "placeholders": {
      "liters": {"type": "num"}
    }
  }
}
```

```json
{
  "itemCount": "{count, plural, =0{কোন আইটেম নেই} =1{১ আইটেম} other{{count} আইটেম}}",
  "cattleCount": "{count, plural, =0{কোন গবাদি পশু নেই} =1{১ গবাদি পশু} other{{count} গবাদি পশু}}",
  "litersProduced": "{liters, plural, =0{কোন লিটার নেই} =1{১ লিটার} other{{liters} লিটার}}"
}
```

### 11.3 Usage in Code

```dart
// Generated code is used automatically
text: AppLocalizations.of(context).itemCount(5),  // "5 items"
text: AppLocalizations.of(context).itemCount(1),  // "1 item"
text: AppLocalizations.of(context).itemCount(0),  // "No items"

// Bengali
text: AppLocalizations.of(context).itemCount(5),  // "৫ আইটেম"
text: AppLocalizations.of(context).itemCount(1),  // "১ আইটেম"
text: AppLocalizations.of(context).itemCount(0),  // "কোন আইটেম নেই"
```

---

## 12. Gender

### 12.1 Gender Forms in Bengali

Bengali does not have grammatical gender for inanimate objects. For addressing people, formal "আপনি" (you) is used regardless of gender.

### 12.2 Address Forms

| Context | English | Bengali | Notes |
|---------|---------|---------|-------|
| Formal | You | আপনি | Default for all users |
| Formal possessive | Your | আপনার | Default |
| Greeting (formal) | Mr./Ms. | জনাব / মিস | Use with care |

### 12.3 Gender-Aware Strings

```json
{
  "welcomeUser": "Welcome, {name}",
  "@welcomeUser": {
    "description": "Welcome message with user's name",
    "placeholders": {
      "name": {"type": "String"}
    }
  },
  
  "yourAccount": "Your Account",
  "yourOrders": "Your Orders",
  "yourProfile": "Your Profile"
}
```

```json
{
  "welcomeUser": "স্বাগতম, {name}",
  "yourAccount": "আপনার অ্যাকাউন্ট",
  "yourOrders": "আপনার অর্ডার",
  "yourProfile": "আপনার প্রোফাইল"
}
```

---

## 13. Cultural Adaptation

### 13.1 Icons and Imagery

| Category | Consideration | Implementation |
|----------|--------------|----------------|
| Animals | Use local cattle breeds | Deshi cow images |
| Food | Show Bengali cuisine context | Milk in traditional bowls |
| People | Diverse representation | Bangladeshi farmers, families |
| Gestures | Avoid ambiguous hand gestures | Use neutral icons |
| Colors | Respect cultural preferences | Green for agriculture |

### 13.2 Color Adaptation

```dart
// lib/core/theme/cultural_colors.dart

class CulturalColors {
  /// Bangladesh flag green - for agricultural/patriotic contexts
  static const Color bangladeshGreen = Color(0xFF006A4E);
  
  /// Bangladesh flag red - for alerts/important actions
  static const Color bangladeshRed = Color(0xFFF42A41);
  
  /// Traditional colors for festivals
  static const Color pohelaBoishakh = Color(0xFFFF6B35);  // Orange
  static const Color eidGreen = Color(0xFF228B22);        // Forest green
  
  /// Agricultural/natural colors
  static const Color paddyGreen = Color(0xFF7CB342);
  static const Color milkWhite = Color(0xFFF5F5F5);
  static const Color earthBrown = Color(0xFF8D6E63);
}
```

### 13.3 Farm Terminology Glossary

| English | Bengali | Context |
|---------|---------|---------|
| Cow | গাভী | Female cattle |
| Bull | বলদ | Male cattle |
| Calf | বাছুর | Young cattle |
| Heifer | পাগলী | Young female |
| Milking | দোহন | Milk extraction |
| Lactation | দুগ্ধদান | Milk production period |
| Dry Period | শুষ্ককাল | Non-milking period |
| Heat | গরম | Estrous cycle |
| Insemination | প্রজনন | Artificial breeding |
| Calving | বাচ্চা প্রসব | Giving birth |
| Weaning | দুধ ছাড়ানো | Stopping milk feeding |
| Feed | খাদ্য | Animal food |
| Fodder | খাদ্য তৈরির উপাদান | Green feed |
| Silage | সাইলেজ | Fermented feed |
| Mastitis | স্তন প্রদাহ | Udder infection |
| Vaccination | টিকা দেওয়া | Immunization |
| Deworming | কৃমি মুক্ত করা | Parasite treatment |

### 13.4 Image Localization

```dart
// lib/core/utils/localized_assets.dart

class LocalizedAssets {
  static String getImagePath(String baseName, String locale) {
    // Check for localized version first
    final localizedPath = 'assets/images/$locale/$baseName';
    // Fall back to default
    return 'assets/images/$baseName';
  }
  
  static String getFarmerIllustration(String locale) {
    if (locale == 'bn') {
      return 'assets/images/bn/farmer_bangladesh.png';
    }
    return 'assets/images/en/farmer_generic.png';
  }
}
```

---

## 14. Voice Input

### 14.1 Bengali Speech Recognition

```dart
// lib/core/services/voice_input_service.dart

import 'package:speech_to_text/speech_to_text.dart';

class VoiceInputService {
  final SpeechToText _speech = SpeechToText();
  bool _isInitialized = false;
  
  /// Initialize speech recognition
  Future<bool> initialize() async {
    if (_isInitialized) return true;
    
    _isInitialized = await _speech.initialize(
      onError: (error) => debugPrint('Speech error: $error'),
      onStatus: (status) => debugPrint('Speech status: $status'),
    );
    return _isInitialized;
  }
  
  /// Start listening for Bengali speech
  Future<void> startListening({
    required Function(String) onResult,
    String localeId = 'bn_BD',
  }) async {
    if (!await initialize()) return;
    
    await _speech.listen(
      onResult: (result) {
        if (result.finalResult) {
          onResult(result.recognizedWords);
        }
      },
      localeId: localeId,  // Bengali (Bangladesh)
      listenMode: ListenMode.confirmation,
    );
  }
  
  /// Stop listening
  Future<void> stopListening() async {
    await _speech.stop();
  }
  
  bool get isListening => _speech.isListening;
  bool get isAvailable => _speech.isAvailable;
}
```

### 14.2 Voice Input Widget

```dart
// lib/core/widgets/voice_input_button.dart

class VoiceInputButton extends StatefulWidget {
  final ValueChanged<String> onTextRecognized;
  final String locale;
  
  const VoiceInputButton({
    super.key,
    required this.onTextRecognized,
    this.locale = 'bn_BD',
  });

  @override
  State<VoiceInputButton> createState() => _VoiceInputButtonState();
}

class _VoiceInputButtonState extends State<VoiceInputButton> {
  final VoiceInputService _voiceService = VoiceInputService();
  bool _isListening = false;
  
  @override
  Widget build(BuildContext context) {
    final l10n = context.l10n;
    
    return IconButton(
      icon: Icon(_isListening ? Icons.mic : Icons.mic_none),
      color: _isListening ? Colors.red : null,
      tooltip: l10n.voiceInputTooltip,
      onPressed: _toggleListening,
    );
  }
  
  Future<void> _toggleListening() async {
    if (_isListening) {
      await _voiceService.stopListening();
      setState(() => _isListening = false);
    } else {
      setState(() => _isListening = true);
      await _voiceService.startListening(
        onResult: (text) {
          widget.onTextRecognized(text);
          setState(() => _isListening = false);
        },
        localeId: widget.locale,
      );
    }
  }
}
```

### 14.3 Text Field with Voice Input

```dart
// lib/core/widgets/voice_text_field.dart

class VoiceTextField extends StatefulWidget {
  final String label;
  final TextEditingController? controller;
  final String locale;
  
  const VoiceTextField({
    super.key,
    required this.label,
    this.controller,
    this.locale = 'bn_BD',
  });

  @override
  State<VoiceTextField> createState() => _VoiceTextFieldState();
}

class _VoiceTextFieldState extends State<VoiceTextField> {
  late TextEditingController _controller;
  
  @override
  void initState() {
    super.initState();
    _controller = widget.controller ?? TextEditingController();
  }
  
  @override
  Widget build(BuildContext context) {
    return TextField(
      controller: _controller,
      decoration: InputDecoration(
        labelText: widget.label,
        suffixIcon: VoiceInputButton(
          locale: widget.locale,
          onTextRecognized: (text) {
            _controller.text = text;
          },
        ),
      ),
    );
  }
}
```

---

## 15. Testing Localization

### 15.1 Locale Switching During Testing

```dart
// lib/core/localization/locale_switcher.dart

class LocaleSwitcher extends StatelessWidget {
  const LocaleSwitcher({super.key});

  @override
  Widget build(BuildContext context) {
    return PopupMenuButton<Locale>(
      icon: const Icon(Icons.language),
      onSelected: (locale) {
        context.read<LocaleBloc>().add(LocaleChanged(locale));
      },
      itemBuilder: (context) => [
        const PopupMenuItem(
          value: Locale('en'),
          child: Row(
            children: [
              Text('🇬🇧'),
              SizedBox(width: 8),
              Text('English'),
            ],
          ),
        ),
        const PopupMenuItem(
          value: Locale('bn'),
          child: Row(
            children: [
              Text('🇧🇩'),
              SizedBox(width: 8),
              Text('বাংলা'),
            ],
          ),
        ),
      ],
    );
  }
}
```

### 15.2 Pseudolocalization

```dart
// test/helpers/pseudolocalization.dart

class Pseudolocalization {
  /// Convert text to pseudolocale for testing
  static String convert(String text) {
    // Add accents and expand by ~30%
    final expanded = text.split('').map((c) {
      if (c == 'a') return 'à';
      if (c == 'e') return 'é';
      if (c == 'i') return 'î';
      if (c == 'o') return 'õ';
      if (c == 'u') return 'ü';
      return c;
    }).join();
    
    return '[$expanded]';
  }
  
  /// Wrap widget in pseudolocale
  static Widget wrap(Widget child) {
    return Localizations.override(
      context: child as BuildContext,
      locale: const Locale('en', 'XX'),  // Pseudo locale
      child: child,
    );
  }
}
```

### 15.3 Localization Tests

```dart
// test/localization/localization_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:flutter/material.dart';
import 'package:smart_dairy/generated/l10n/app_localizations.dart';

void main() {
  group('Localization Tests', () {
    testWidgets('All strings have Bengali translations', (tester) async {
      final enStrings = await loadArb('app_en.arb');
      final bnStrings = await loadArb('app_bn.arb');
      
      final missingTranslations = <String>[];
      
      for (final key in enStrings.keys) {
        if (key.startsWith('@')) continue;
        if (!bnStrings.containsKey(key)) {
          missingTranslations.add(key);
        }
      }
      
      expect(
        missingTranslations,
        isEmpty,
        reason: 'Missing Bengali translations for: $missingTranslations',
      );
    });
    
    testWidgets('English date format is DD/MM/YYYY', (tester) async {
      await tester.pumpWidget(
        MaterialApp(
          localizationsDelegates: AppLocalizations.localizationsDelegates,
          supportedLocales: AppLocalizations.supportedLocales,
          locale: const Locale('en'),
          home: Builder(
            builder: (context) {
              final date = DateTime(2026, 1, 31);
              final formatted = BDDateFormatter.shortDate(date, locale: 'en');
              expect(formatted, '31/01/2026');
              return const SizedBox();
            },
          ),
        ),
      );
    });
    
    testWidgets('BDT currency formatting', (tester) async {
      expect(
        BDCurrencyFormatter.formatForDisplay(1234),
        '৳1,234',
      );
      expect(
        BDCurrencyFormatter.formatForDisplay(1234.56),
        '৳1,235',
      );
    });
    
    testWidgets('Text direction is LTR for Bengali', (tester) async {
      final direction = DirectionalityHelper.getTextDirection('bn');
      expect(direction, TextDirection.ltr);
    });
  });
}

Future<Map<String, dynamic>> loadArb(String filename) async {
  // Load and parse ARB file
  // Implementation depends on test setup
  return {};
}
```

### 15.4 UI Overflow Testing

```dart
// test/localization/overflow_test.dart

testWidgets('No text overflow in Bengali', (tester) async {
  await tester.pumpWidget(
    MaterialApp(
      localizationsDelegates: AppLocalizations.localizationsDelegates,
      supportedLocales: AppLocalizations.supportedLocales,
      locale: const Locale('bn'),
      home: const HomePage(),
    ),
  );
  
  await tester.pumpAndSettle();
  
  // Check for overflow errors
  expect(tester.takeException(), isNull);
});
```

---

## 16. Right-to-Left (RTL)

### 16.1 RTL Preparation

While Bengali is LTR, the app should be prepared for future RTL languages (e.g., Arabic).

### 16.2 RTL-Aware Layouts

```dart
// Use EdgeInsetsDirectional instead of EdgeInsets
padding: const EdgeInsetsDirectional.only(
  start: 16,  // Instead of left
  end: 16,    // Instead of right
  top: 8,
  bottom: 8,
),

// Use PositionedDirectional instead of Positioned
PositionedDirectional(
  start: 0,   // Instead of left
  end: 0,     // Instead of right
  top: 0,
  child: Container(),
),

// Use AlignmentDirectional instead of Alignment
alignment: AlignmentDirectional.centerStart,  // Instead of centerLeft
```

### 16.3 RTL Testing

```dart
// test/rtl/rtl_test.dart

testWidgets('Layout adapts to RTL', (tester) async {
  await tester.pumpWidget(
    MaterialApp(
      localizationsDelegates: AppLocalizations.localizationsDelegates,
      supportedLocales: AppLocalizations.supportedLocales,
      locale: const Locale('ar'),  // Test with Arabic
      home: const HomePage(),
    ),
  );
  
  // Verify mirrored layout
  final finder = find.byType(BackButton);
  final button = tester.widget<BackButton>(finder);
  
  // Back button should point right in RTL
  expect(button, isNotNull);
});
```

---

## 17. Maintenance

### 17.1 Adding New Strings

1. Add to `app_en.arb` with context:
```json
"newFeatureTitle": "New Feature",
"@newFeatureTitle": {
  "description": "Title for new feature screen"
}
```

2. Run code generation:
```bash
flutter gen-l10n
```

3. Use in code:
```dart
text: context.l10n.newFeatureTitle,
```

4. Add Bengali translation to `app_bn.arb`:
```json
"newFeatureTitle": "নতুন ফিচার",
```

5. Export to translation management system

### 17.2 Translation Updates

```bash
# Check for untranslated strings
cat untranslated_messages.txt

# Sync with translation service
flutter pub run tools/translation_sync.dart

# Validate ARB files
flutter pub run tools/arb_validator.dart
```

### 17.3 Version Control

| File | Action | Notes |
|------|--------|-------|
| `app_en.arb` | Commit | Source of truth |
| `app_bn.arb` | Commit | Translation file |
| `generated/*` | Commit | Auto-generated, track for CI |
| `untranslated_messages.txt` | Don't commit | Temporary file |

### 17.4 CI/CD Integration

```yaml
# .github/workflows/localization.yml
name: Localization Check

on: [push, pull_request]

jobs:
  localization:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        
      - name: Generate localizations
        run: flutter gen-l10n
        
      - name: Check for untranslated strings
        run: |
          if [ -s untranslated_messages.txt ]; then
            echo "Found untranslated strings:"
            cat untranslated_messages.txt
            exit 1
          fi
          
      - name: Verify ARB syntax
        run: dart tools/arb_validator.dart
```

---

## 18. Appendices

### Appendix A: Complete ARB Example

```json
{
  "@@locale": "en",
  "@@last_modified": "2026-01-31T10:00:00Z",
  "@@context": "Smart Dairy Mobile App",
  
  "appName": "Smart Dairy",
  "appTagline": "Pure Milk, Pure Life",
  
  "common": {
    "save": "Save",
    "cancel": "Cancel",
    "delete": "Delete",
    "edit": "Edit",
    "submit": "Submit",
    "loading": "Loading...",
    "retry": "Retry",
    "close": "Close",
    "next": "Next",
    "back": "Back",
    "done": "Done",
    "continue": "Continue"
  },
  
  "auth": {
    "loginTitle": "Welcome Back",
    "registerTitle": "Create Account",
    "emailLabel": "Email Address",
    "emailHint": "Enter your email",
    "passwordLabel": "Password",
    "passwordHint": "Enter your password",
    "forgotPassword": "Forgot Password?",
    "loginButton": "Sign In",
    "registerButton": "Sign Up",
    "noAccount": "Don't have an account?",
    "hasAccount": "Already have an account?",
    "logoutConfirm": "Are you sure you want to logout?"
  },
  
  "farm": {
    "milkProduction": "Milk Production",
    "morningMilk": "Morning Milk",
    "eveningMilk": "Evening Milk",
    "totalProduction": "Total Production",
    "animalHealth": "Animal Health",
    "vaccinationDue": "Vaccination Due",
    "heatDetection": "Heat Detection",
    "breedingRecords": "Breeding Records",
    "inseminationDate": "Insemination Date",
    "expectedCalving": "Expected Calving",
    "lactationPeriod": "Lactation Period",
    "dryPeriod": "Dry Period"
  },
  
  "shop": {
    "products": "Products",
    "categories": "Categories",
    "freshMilk": "Fresh Milk",
    "yogurt": "Yogurt",
    "ghee": "Ghee",
    "cheese": "Cheese",
    "addToCart": "Add to Cart",
    "viewCart": "View Cart",
    "checkout": "Checkout",
    "orderSummary": "Order Summary",
    "deliveryAddress": "Delivery Address",
    "paymentMethod": "Payment Method",
    "bKash": "bKash",
    "nagad": "Nagad",
    "cod": "Cash on Delivery",
    "orderPlaced": "Order Placed Successfully!",
    "orderNumber": "Order Number: {number}"
  },
  
  "errors": {
    "network": "Network connection failed. Please check your internet.",
    "server": "Server error. Please try again later.",
    "timeout": "Request timed out. Please retry.",
    "unauthorized": "Session expired. Please login again.",
    "notFound": "Resource not found.",
    "validation": "Please check your input and try again."
  },
  
  "validation": {
    "required": "This field is required",
    "invalidEmail": "Please enter a valid email address",
    "invalidPhone": "Please enter a valid phone number",
    "minLength": "Must be at least {min} characters",
    "maxLength": "Must be at most {max} characters",
    "passwordMatch": "Passwords do not match",
    "positiveNumber": "Please enter a positive number"
  },
  
  "units": {
    "liter": "L",
    "kilogram": "kg",
    "gram": "g",
    "milliliter": "ml",
    "celsius": "°C"
  },
  
  "notifications": {
    "orderConfirmed": "Your order has been confirmed",
    "orderShipped": "Your order has been shipped",
    "orderDelivered": "Your order has been delivered",
    "lowStock": "Low stock alert: {product}",
    "vaccinationReminder": "Vaccination due for: {animal}"
  }
}
```

### Appendix B: Bengali ARB Example

```json
{
  "@@locale": "bn",
  
  "appName": "স্মার্ট ডেইরি",
  "appTagline": "পিউর মিল্ক, পিউর লাইফ",
  
  "commonSave": "সংরক্ষণ",
  "commonCancel": "বাতিল",
  "commonDelete": "মুছুন",
  "commonEdit": "সম্পাদনা",
  "commonSubmit": "জমা দিন",
  "commonLoading": "লোড হচ্ছে...",
  "commonRetry": "আবার চেষ্টা করুন",
  "commonClose": "বন্ধ করুন",
  "commonNext": "পরবর্তী",
  "commonBack": "পেছনে",
  "commonDone": "সম্পন্ন",
  "commonContinue": "চালিয়ে যান",
  
  "authLoginTitle": "স্বাগতম",
  "authRegisterTitle": "অ্যাকাউন্ট তৈরি করুন",
  "authEmailLabel": "ইমেইল ঠিকানা",
  "authEmailHint": "আপনার ইমেইল লিখুন",
  "authPasswordLabel": "পাসওয়ার্ড",
  "authPasswordHint": "আপনার পাসওয়ার্ড লিখুন",
  "authForgotPassword": "পাসওয়ার্ড ভুলে গেছেন?",
  "authLoginButton": "সাইন ইন",
  "authRegisterButton": "সাইন আপ",
  "authNoAccount": "অ্যাকাউন্ট নেই?",
  "authHasAccount": "অ্যাকাউন্ট আছে?",
  "authLogoutConfirm": "আপনি কি লগআউট করতে চান?",
  
  "farmMilkProduction": "দুধ উৎপাদন",
  "farmMorningMilk": "সকালের দুধ",
  "farmEveningMilk": "বিকেলের দুধ",
  "farmTotalProduction": "মোট উৎপাদন",
  "farmAnimalHealth": "পশু স্বাস্থ্য",
  "farmVaccinationDue": "টিকা দেওয়ার সময়",
  "farmHeatDetection": "গরম সনাক্তকরণ",
  "farmBreedingRecords": "প্রজনন রেকর্ড",
  "farmInseminationDate": "প্রজননের তারিখ",
  "farmExpectedCalving": "প্রসবের সম্ভাব্য তারিখ",
  "farmLactationPeriod": "দুগ্ধদানকাল",
  "farmDryPeriod": "শুষ্ককাল",
  
  "shopProducts": "পণ্য",
  "shopCategories": "বিভাগ",
  "shopFreshMilk": "তাজা দুধ",
  "shopYogurt": "দই",
  "shopGhee": "ঘি",
  "shopCheese": "ছানা/পনির",
  "shopAddToCart": "কার্টে যোগ করুন",
  "shopViewCart": "কার্ট দেখুন",
  "shopCheckout": "চেকআউট",
  "shopOrderSummary": "অর্ডার সারাংশ",
  "shopDeliveryAddress": "ডেলিভারি ঠিকানা",
  "shopPaymentMethod": "পেমেন্ট পদ্ধতি",
  "shopBKash": "বিকাশ",
  "shopNagad": "নগদ",
  "shopCod": "ক্যাশ অন ডেলিভারি",
  "shopOrderPlaced": "অর্ডার সফলভাবে সম্পন্ন হয়েছে!",
  
  "errorNetwork": "নেটওয়ার্ক সংযোগ ব্যর্থ। আপনার ইন্টারনেট চেক করুন।",
  "errorServer": "সার্ভার ত্রুটি। পরে আবার চেষ্টা করুন।",
  "errorTimeout": "অনুরোধের সময় শেষ। আবার চেষ্টা করুন।",
  "errorUnauthorized": "সেশনের মেয়াদ শেষ। আবার লগইন করুন।",
  "errorNotFound": "সম্পদ পাওয়া যায়নি।",
  "errorValidation": "অনুগ্রহ করে আপনার ইনপুট চেক করুন এবং আবার চেষ্টা করুন।",
  
  "valRequired": "এই ক্ষেত্রটি পূরণ করা আবশ্যক",
  "valInvalidEmail": "অনুগ্রহ করে একটি বৈধ ইমেইল ঠিকানা লিখুন",
  "valInvalidPhone": "অনুগ্রহ করে একটি বৈধ ফোন নম্বর লিখুন",
  "valPasswordMatch": "পাসওয়ার্ড মিলছে না",
  "valPositiveNumber": "অনুগ্রহ করে একটি ধনাত্মক সংখ্যা লিখুন",
  
  "literUnit": "লি",
  "kilogramUnit": "কেজি",
  "gramUnit": "গ্রাম",
  "milliliterUnit": "মি.লি.",
  "celsiusUnit": "°সে",
  
  "notifOrderConfirmed": "আপনার অর্ডার নিশ্চিত করা হয়েছে",
  "notifOrderShipped": "আপনার অর্ডার পাঠানো হয়েছে",
  "notifOrderDelivered": "আপনার অর্ডার ডেলিভারি হয়েছে",
  "notifLowStock": "কম স্টক সতর্কতা: {product}",
  "notifVaccinationReminder": "টিকা দেওয়ার সময়: {animal}"
}
```

### Appendix C: Glossary

| Term | Definition |
|------|------------|
| **ARB** | Application Resource Bundle - JSON-based localization file format |
| **BDT** | Bangladeshi Taka - Currency code |
| **ICU** | International Components for Unicode - Message format standard |
| **L10n** | Localization (L + 10 letters + n) |
| **LTR** | Left-to-Right text direction |
| **I18n** | Internationalization (I + 18 letters + n) |
| **RTL** | Right-to-Left text direction |
| **Locale** | Language + region combination (e.g., en_US, bn_BD) |
| **Placeholder** | Variable in string like `{name}` |
| **Pluralization** | Different forms based on number quantity |
| **Pseudolocalization** | Testing with fake translations |

### Appendix D: Quick Reference

```dart
// Getting localized strings
final l10n = AppLocalizations.of(context);
final message = l10n.welcomeMessage;

// Formatting dates
BDDateFormatter.shortDate(date, locale: 'bn');  // ৩১/০১/২০২৬
BDDateFormatter.longDate(date, locale: 'bn');   // ৩১ জানুয়ারি ২০২৬

// Formatting currency
BDCurrencyFormatter.formatForDisplay(1234);     // ৳1,234
BDCurrencyFormatter.formatWithDecimals(1234.56); // ৳1,234.56

// Formatting numbers
BDNumberFormatter.formatNumber(1234.56, locale: 'bn');  // ১,২৩৪.৫৬

// Changing locale
context.read<LocaleBloc>().add(const LocaleChanged(Locale('bn')));

// Checking text direction
final isRtl = DirectionalityHelper.isRtl('bn');  // false
```

### Appendix E: Resources

- [Flutter Internationalization](https://docs.flutter.dev/ui/accessibility-and-internationalization/internationalization)
- [ICU Message Format](https://unicode-org.github.io/icu/userguide/format_parse/messages/)
- [ARB Specification](https://github.com/google/app-resource-bundle/wiki/ApplicationResourceBundleSpecification)
- [Material Design Localization](https://m3.material.io/foundations/content-design/global-writing/translation)
- [Bengali Typography](https://fonts.google.com/specimen/Hind+Siliguri)

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Mobile Lead | Initial version |

---

*End of Document H-017: Mobile Localization Guide*
